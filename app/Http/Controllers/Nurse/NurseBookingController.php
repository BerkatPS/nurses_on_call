<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
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

        // Layanan Mendatang
        $upcomingServices = $this->getUpcomingServices($nurse);

        // Panggilan Darurat
        $emergencyCalls = $this->getEmergencyCalls($nurse);

        return view('nurses.bookings.index', compact(
            'activeBookings',
            'upcomingServices',
            'emergencyCalls'
        ));
    }

    protected function getActiveBookings(Nurse $nurse)
    {
        return Booking::where('nurse_id', $nurse->id)
            ->whereIn('status', ['pending', 'confirmed'])
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
            ->where('start_time', '>', now())
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
                    'status' => 'Scheduled',
                    'description' => $booking->notes,
                ];
            });
    }

    protected function getEmergencyCalls(Nurse $nurse)
    {
        return EmergencyCall::where('assigned_nurse_id', $nurse->id)
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
