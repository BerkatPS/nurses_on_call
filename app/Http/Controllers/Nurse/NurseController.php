<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Nurse;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NurseController extends Controller
{
    public function index()
    {
        // Ambil data nurse yang sedang login
        $nurse = Auth::user()->nurse;


        // Data Tugas Aktif
        $activeAssignments = $this->getActiveAssignments($nurse);

        // Statistik Dashboard
        $dashboardData = $this->getDashboardStatistics($nurse);

        // Notifikasi
//        $notifications = $this->getNotifications($nurse);

        // Aktivitas Terakhir
        $recentActivities = $this->getRecentActivities($nurse);

        return view('nurses.index', compact(
            'activeAssignments',
            'dashboardData',
//            'notifications',
            'recentActivities'
        ));
    }

    protected function getActiveAssignments(Nurse $nurse)
    {
        return Booking::where('nurse_id', $nurse->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['user', 'service'])
            ->get()
            ->map(function($booking) {
                return (object)[
                    'id' => $booking->id,
                    'patient' => $booking->user->name,
                    'service' => $booking->service->name,
                    'date' => $booking->start_time,
                    'status' => $booking->status,
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($booking->user->name)
                ];
            });
    }

    protected function getDashboardStatistics(Nurse $nurse)
    {
        return (object)[
            'statistics' => (object)[
                'totalBookings' => Booking::where('nurse_id', $nurse->id)->count(),
                'completedServices' => Booking::where('nurse_id', $nurse->id)
                    ->where('status', 'completed')
                    ->count(),
                'pendingServices' => Booking::where('nurse_id', $nurse->id)
                    ->where('status', 'pending')
                    ->count()
            ]
        ];
    }

//    protected function getNotifications(Nurse $nurse)
//    {
//        // Ambil notifikasi terkait nurse
//        return $nurse->user->unreadNotifications
//            ->take(5)
//            ->map(function($notification) {
//                return (object)[
//                    'title' => $notification->data['title'] ?? 'Notifikasi Baru',
//                    'message' => $notification->data['message'] ?? 'Anda memiliki notifikasi baru',
//                    'created_at' => $notification->created_at
//                ];
//            });
//    }

    protected function getRecentActivities(Nurse $nurse)
    {
        // Ambil aktivitas terkini dari booking
        return Booking::where('nurse_id', $nurse->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($booking) {
                return (object)[
                    'title' => $this->getActivityTitle($booking),
                    'description' => $booking->notes ?? 'Layanan telah diperbarui',
                    'icon' => $this->getActivityIcon($booking),
                    'iconColor' => $this->getActivityIconColor($booking)
                ];
            });
    }

    private function getActivityTitle(Booking $booking)
    {
        switch ($booking->status) {
            case 'completed':
                return 'Layanan Selesai';
            case 'confirmed':
                return 'Layanan Dikonfirmasi';
            case 'pending':
                return 'Layanan Menunggu';
            default:
                return 'Aktivitas Layanan';
        }
    }

    private function getActivityIcon(Booking $booking)
    {
        switch ($booking->status) {
            case 'completed':
                return 'fas fa-check-circle';
            case 'confirmed':
                return 'fas fa-calendar-check';
            case 'pending':
                return 'fas fa-clock';
            default:
                return 'fas fa-notes-medical';
        }
    }

    public function confirmBooking(Request $request, Booking $booking)
    {
        // Validasi dan konfirmasi booking
        $booking->status = 'confirmed';
        $booking->save();

        return response()->json(['success' => true, 'message' => 'Booking berhasil diterima.']);
    }


    private function getActivityIconColor(Booking $booking)
    {
        switch ($booking->status) {
            case 'completed':
                return 'text-green-500 ';
            case 'confirmed':
                return 'text-blue-500';
            case 'pending':
                return 'text-yellow-500';
            default:
                return 'text-gray-500';
        }
    }

    // Metode untuk mendapatkan data grafik
    public function getPerformanceChartData(Nurse $nurse)
    {
        // Ambil data layanan per bulan
        $monthlyServices = Booking::where('nurse_id', $nurse->id)
            ->where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Lengkapi data untuk 6 bulan terakhir
        $chartData = [];
        for ($i = 1; $i <= 6; $i++) {
            $month = now()->subMonths(6 - $i)->month;
            $chartData[] = $monthlyServices[$month] ?? 0;
        }

        return response()->json($chartData);
    }

    // Metode untuk mendapatkan distribusi panggilan darurat
    public function getEmergencyCallDistribution(Nurse $nurse)
    {
        // Ambil distribusi berdasarkan service type
        $emergencyServices = Service::where('type', 'emergency')->get();

        $distribution = [];
        foreach ($emergencyServices as $service) {
            $distribution[] = Booking::where('nurse_id', $nurse->id)
                ->where('service_id', $service->id)
                ->count();
        }

        return response()->json($distribution);
    }


}
