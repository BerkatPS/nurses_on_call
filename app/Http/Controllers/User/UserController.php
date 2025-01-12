<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EmergencyCall;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login
        $user = \auth()->user();

        // Persiapkan data dashboard
        $dashboardData = $this->prepareDashboardData($user);

        return view('users.index', compact('dashboardData'));
    }

    // Metode untuk mendapatkan statistik real-time
    private function prepareDashboardData(User $user)
    {
        // Statistik Booking
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $completedServices = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $pendingServices = Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Panggilan Darurat
        $emergencyCalls = EmergencyCall::where('user_id', $user->id)
            ->where('status', '!=', 'resolved')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function($call) {
                return [
                    'type' => $call->emergency_type,
                    'status' => $call->status,
                    'location' => $call->location,
                    'date' => $call->created_at,
                    'description' => $call->description
                ];
            });

        // Layanan Mendatang
        // Layanan Mendatang
        $upcomingServices = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->with('nurse', 'service') // Pastikan relasi ini ada
            ->orderBy('start_time', 'asc')
            ->limit(4)
            ->get()
            ->map(function($booking) {
                return [
                    'type' => $booking->service->type,
                    'status' => $booking->status,
                    'doctor' => [
                        'name' => $booking->nurse->user->name,
                        'specialization' => $booking->nurse->specializations
                    ],
                    'date' => $booking->start_time,
                    'time' => Carbon::parse($booking->start_time)->format('H:i')
                ];
            });


        // Profil Pengguna
        $userProfile = [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random',
            'role' => $user->role
        ];

        // Struktur Data Dashboard
        return (object) [
            'userProfile' => (object) $userProfile,
            'statistics' => (object) [
                'totalBookings' => $totalBookings,
                'completedServices' => $completedServices,
                'pendingServices' => $pendingServices,
                'emergencyCalls' => EmergencyCall::where('user_id', $user->id)->count()
            ],
            'upcomingServices' => $upcomingServices,
            'emergencyCalls' => $emergencyCalls
        ];
    }

    // Metode untuk refresh statistik real-time (opsional)
    public function refreshStats()
    {
        $user = Auth::user();
        $stats = $this->prepareDashboardData($user);

        return response()->json([
            'totalBookings' => $stats->statistics->totalBookings,
            'completedServices' => $stats->statistics->completedServices,
            'pendingServices' => $stats->statistics->pendingServices,
            'lastUpdated' => Carbon::now()->format('H:i:s')
        ]);
    }
}
