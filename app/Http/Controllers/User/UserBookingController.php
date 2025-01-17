<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Nurse;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserBookingController extends Controller
{
    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil status filter dari query string, default adalah 'all'
        $statusFilter = $request->query('status', 'all');

        // Ambil data booking berdasarkan status
        $bookings = $this->getBookings($user->id, $statusFilter);

        // Ambil semua layanan untuk modal
        $services = Service::all();

        return view('users.bookings.index', compact('bookings', 'statusFilter', 'services'));
    }

    protected function getBookings($userId, $statusFilter)
    {
        $query = Booking::where('user_id', $userId)->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return $query->with(['service', 'nurse.user'])->get();
    }

    public function create(Request $request)
    {
        // Validasi input yang lebih komprehensif
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'emergency_level' => 'nullable|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Ambil detail service
            $service = Service::findOrFail($request->service_id);

            // Pilih nurse yang tersedia
            $nurse = $this->findAvailableNurse($service);

            if (!$nurse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perawat tersedia untuk layanan ini'
                ], 400);
            }

            // Hitung waktu selesai
            $startTime = Carbon::parse($request->start_time);
            $endTime = $this->calculateEndTime($service, $startTime);

            // Hitung total biaya
            $totalAmount = $this->calculateTotalAmount($service, $startTime, $endTime);

            // Buat booking baru
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'service_id' => $request->service_id,
                'nurse_id' => $nurse->id,
                'location' => $request->location ?? Auth::user()->address,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'notes' => $request->notes,
                'status' => 'pending', // Status awal booking
                'total_amount' => $totalAmount,
                'emergency_level' => $request->emergency_level ?? 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Booking Creation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat booking: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function findAvailableNurse($service)
    {
        return Nurse::where('availability_status', 'available')
            ->whereHas('user', function($query) use ($service) {
                $query->where('specializations', 'like', "%{$service->type}%");
            })
            ->first();
    }

    protected function calculateEndTime($service, $startTime)
    {
        $durationMap = [
            'homecare' => 2,
            'emergency' => 1,
            'consultation' => 1,
            'default' => 1.5
        ];

        $duration = $durationMap[$service->type] ?? $durationMap['default'];
        return $startTime->copy()->addHours($duration);
    }

    protected function calculateTotalAmount($service, $startTime, $endTime)
    {
        $hours = $startTime->diffInHours($endTime);
        $basePrice = $service->base_price;

        $totalAmount = $basePrice * (1 + ($hours * 0.2));

        return round($totalAmount, 2);
    }

    public function show($bookingId)
    {
        $user = Auth::user();

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $user->id)
            ->with(['service', 'nurse.user'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan atau Anda tidak memiliki akses'
            ], 403);
        }

        $booking->formatted_start_time = Carbon::parse($booking->start_time)->format('d M Y H:i');
        $booking->formatted_end_time = Carbon::parse($booking->end_time)->format('d M Y H:i');

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    public function cancel($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin membatalkan booking ini'
            ], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak dapat dibatalkan pada status ini'
            ], 400);
        }

        try {
            $booking->status = 'cancelled';
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
