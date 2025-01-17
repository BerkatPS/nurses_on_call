<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\EmergencyCall;
use App\Models\Service;
use Carbon\Carbon;

class NurseBookingController extends Controller
{
    public function index()
    {
        // Pastikan hanya nurse yang bisa mengakses
        $nurse = Auth::user()->nurse;

        if (!$nurse) {
            return redirect()->route('login')->with('error', 'Akses ditolak');
        }

        // Ambil data booking aktif
        $activeBookings = $this->getActiveBookings($nurse);
        $upcomingServices = $this->getUpcomingServices($nurse);
        $completedBookings = $this->getCompletedBookings($nurse);
        $emergencyCalls = $this->getEmergencyCalls($nurse);

        return view('nurses.bookings.index', compact(
            'activeBookings',
            'upcomingServices',
            'completedBookings',
            'emergencyCalls'
        ));
    }

    protected function getActiveBookings(Nurse $nurse)
    {
        return Booking::where('nurse_id', $nurse->id)
            ->whereIn('status', ['pending'])
            ->with(['user', 'service'])
            ->get()
            ->map(function($booking) {
                return (object)[
                    'id' => $booking->id,
                    'user' => (object)[
                        'name' => $booking->user->name,
                        'phone' => $booking->user->phone
                    ],
                    'service' => (object)[
                        'name' => $booking->service->name
                    ],
                    'total_amount' => $booking->total_amount,
                    'notes' => $booking->notes,
                    'startTime' => $booking->start_time,
                    'status' => $booking->status,
                    'location' => $booking->location
                ];
            });
    }

    protected function getUpcomingServices(Nurse $nurse)
    {
        return Booking::where('nurse_id', $nurse->id)
            ->where('status', 'confirmed')
            ->with('service')
            ->get()
            ->map(function($booking) {
                return (object)[
                    'id' => $booking->id,
                    'type' => $booking->service->name,
                    'date' => $booking->start_time,
                    'location' => $booking->location,
                    'notes' => $booking->notes,
                    'time' => Carbon::parse($booking->start_time)->format('H:i'),
                    'status' => 'confirmed',
                    'description' => $booking->notes,
                ];
            });
    }

    protected function getCompletedBookings(Nurse $nurse)
    {
        return Booking::where('nurse_id', $nurse->id)
            ->where('status', 'completed')
            ->with(['user', 'service'])
            ->get()
            ->map(function($booking) {
                return (object)[
                    'id' => $booking->id,
                    'user' => (object)[
                        'name' => $booking->user->name,
                        'phone' => $booking->user->phone
                    ],
                    'service' => (object)[
                        'name' => $booking->service->name
                    ],
                    'total_amount' => $booking->total_amount,
                    'notes' => $booking->notes,
                    'startTime' => $booking->start_time,
                    'status' => $booking->status,
                    'location' => $booking->location,
                    'file_path' => $booking->file_path // Menyertakan file_path
                ];
            });
    }

    public function updateBookingStatus(Request $request, $bookingId)
    {
        $nurse = Auth::user()->nurse;
        $booking = Booking::findOrFail($bookingId);

        // Validasi kepemilikan booking
        if ($booking->nurse_id !== $nurse->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengubah status booking ini.'], 403);
        }

        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        try {
            // Update status booking
            $booking->update(['status' => $validatedData['status']]);

            return response()->json(['success' => true, 'message' => 'Status booking berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status booking: ' . $e->getMessage()], 500);
        }
    }

    public function cancelBooking($bookingId) {
        $nurse = Auth::user()->nurse;
        $booking = Booking::findOrFail($bookingId);

        // Validasi kepemilikan booking
        if ($booking->nurse_id !== $nurse->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin untuk membatalkan booking ini.'], 403);
        }

        try {
            // Update status booking menjadi 'cancelled'
            $booking->update(['status' => 'cancelled']);
            return response()->json(['success' => true, 'message' => 'Booking berhasil dibatalkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan booking: ' . $e->getMessage()], 500);
        }
    }

    public function completeBooking($bookingId): \Illuminate\Http\JsonResponse
    {
        $nurse = Auth::user()->nurse;
        $booking = Booking::findOrFail($bookingId);

        // Validasi kepemilikan booking
        if ($booking->nurse_id !== $nurse->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin untuk menyelesaikan booking ini.'], 403);
        }

        try {
            // Update status booking menjadi 'completed'
            $booking->update(['status' => 'completed']);
            return response()->json(['success' => true, 'message' => 'Booking berhasil diselesaikan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyelesaikan booking: ' . $e->getMessage
        ()], 500);
            }
        }


    public function uploadProof(Request $request, $bookingId)
    {
        $request->validate([
            'proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validasi file
        ]);

        $booking = Booking::findOrFail($bookingId);

        // Validasi kepemilikan booking
        if ($booking->nurse_id !== Auth::user()->nurse->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengupload bukti ini.'], 403);
        }

        try {
            // Upload file
            $filePath = $request->file('proof')->store('uploads', 'public'); // Simpan file di storage/app/public/uploads

            // Update booking dengan file path
            $booking->update(['file_path' => $filePath]);

            return response()->json(['success' => true, 'message' => 'Bukti berhasil diupload.', 'file_path' => Storage::url($filePath)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengupload bukti: ' . $e->getMessage()], 500);
        }
    }

    protected function getEmergencyCalls(Nurse $nurse) {
        return EmergencyCall::where('assigned_nurse_id', $nurse->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function($call) {
                return (object)[
                    'id' => $call->id,
                    'type' => $call->emergency_type,
                    'date' => $call->created_at,
                    'status' => $call->status,
                    'location' => $call->location,
                    'description' => $call->description,
                    'latitude' => $call->latitude,
                    'longitude' => $call->longitude
                ];
            });
    }
}
