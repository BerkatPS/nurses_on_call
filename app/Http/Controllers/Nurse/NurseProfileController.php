<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Nurse;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class NurseProfileController extends Controller
{
    public function index()
    {
        // Pastikan hanya nurse yang bisa mengakses
        $nurse = Auth::user()->nurse;

        if (!$nurse) {
            return redirect()->route('login')->with('error', 'Akses ditolak');
        }

        // Ambil profil nurse yang sedang login
        $nurseProfile = $this->getNurseProfile($nurse);
        // Tentukan warna status ketersediaan
        $availabilityColor = $this->getAvailabilityColor($nurse->availability_status);

        // Ambil statistik
        $nurseStatistics = $this->getNurseStatistics($nurse);

        // status
        $status = $nurse->availability_status;


        return view('nurses.profile.index', compact('nurseProfile', 'availabilityColor', 'nurseStatistics', 'status'));
    }

    private function getNurseProfile(Nurse $nurse)
    {
        return (object) [
            'user' => (object) [
                'name' => $nurse->user->name,
                'email' => $nurse->user->email,
                'phone' => $nurse->user->phone,
                'address' => $nurse->user->address,
                'profileImage' => $nurse->user->avatar ?? '/default-avatar.png',
            ],
            'specializations' => explode(',', $nurse->specializations),
            'currentLocation' => $nurse->current_location,
            'availabilityStatus' => $nurse->availability_status,
            'rating' => $nurse->rating ?? 0,
            'workHistory' => $this->getNurseWorkHistory($nurse),
            'skills' => $this->getNurseSkills($nurse)
        ];
    }

    private function getNurseWorkHistory(Nurse $nurse)
    {
        // Implementasi logika riwayat pekerjaan
        // Misalnya dari tabel terpisah atau field JSON
        return $nurse->certifications
            ? collect(json_decode($nurse->certifications, true))
                ->map(function($cert) {
                    return (object)[
                        'hospital' => $cert['institution'] ?? 'Tidak Diketahui',
                        'position' => $cert['title'] ?? 'Perawat',
                        'startDate' => Carbon::parse($cert['start_date'] ?? now()),
                        'endDate' => Carbon::parse($cert['end_date'] ?? now()),
                        'description' => $cert['description'] ?? ''
                    ];
                })
            : [];
    }
    public function updateStatus(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'availability_status' => 'required|in:available,on-call,offline',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nurse = Auth::user()->nurse; // Ambil nurse yang sedang login
            // Update status ketersediaan
            $nurse->availability_status = $request->availability_status;
            $nurse->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'availability_status' => $nurse->availability_status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function getNurseSkills(Nurse $nurse)
    {
        // Implementasi logika keahlian
        // Misalnya dari field JSON atau relasi
        return $nurse->skills
            ? json_decode($nurse->skills, true)
            : [
                'Resusitasi Jantung Paru',
                'Perawatan Intensif',
                'Manajemen Nyeri',
                'Konseling Pasien'
            ];
    }

    private function getNurseStatistics(Nurse $nurse)
    {
        // Ambil service untuk menentukan tipe layanan
        $emergencyServices = Service::where('type', 'emergency')->pluck('id');
        $homecareServices = Service::where('type', 'homecare')->pluck('id');

        // Hitung statistik berdasarkan booking
        $totalServices = Booking::where('nurse_id', $nurse->id)->count();
        $completedServices = Booking::where('nurse_id', $nurse->id)
            ->where('status', 'completed')
            ->count();

        // Gunakan service_id untuk menentukan tipe layanan
        $emergencyCalls = Booking::where('nurse_id', $nurse->id)
            ->whereIn('service_id', $emergencyServices)
            ->count();

        $homeCareServices = Booking::where('nurse_id', $nurse->id)
            ->whereIn('service_id', $homecareServices)
            ->count();

        // Hitung total pendapatan
        $totalEarnings = Booking::where('nurse_id', $nurse->id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->sum('total_amount');

        return [
            'totalServices' => $totalServices,
            'completedServices' => $completedServices,
            'emergencyCalls' => $emergencyCalls,
            'homeCareServices' => $homeCareServices,
            'totalEarnings' => number_format($totalEarnings, 0, ',', '.')
        ];
    }

    private function getAvailabilityColor($status)
    {
        switch ($status) {
            case 'available':
                return 'bg-green-100 text-green-800';
            case 'busy':
                return 'bg-yellow-100 text-yellow-800';
            case 'offline':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    // Metode untuk update profil
    public function updateProfile(Request $request)
    {
        $nurse = Auth::user()->nurse;

        $validatedData = $request->validate([
            'current_location' => 'nullable|string',
            'availability_status' => 'in:available,busy,offline',
            'skills' => 'nullable|array',
            'specializations' => 'nullable|string'
        ]);

        try {
            // Update nurse profile
            $nurse->update([
                'current_location' => $validatedData['current_location'] ?? $nurse->current_location,
                'availability_status' => $validatedData['availability_status'] ?? $nurse->availability_status,
                'skills' => json_encode($validatedData['skills'] ?? []),
                'specializations' => $validatedData['specializations'] ?? $nurse->specializations
            ]);

            return redirect()->route('nurse.profile')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}
