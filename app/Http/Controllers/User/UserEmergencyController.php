<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EmergencyCall;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserEmergencyController extends Controller
{
    // Halaman index panggilan darurat
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $selectedFilter = $request->input('status', 'all');

        // Ambil panggilan darurat berdasarkan filter
        $filteredCalls = $this->getFilteredCalls($user->id, $selectedFilter);

        // Jenis panggilan darurat untuk dropdown
        $emergencyTypes = [
            ['id' => 'medical', 'name' => 'Medical Emergency'],
            ['id' => 'accident', 'name' => 'Accident'],
            ['id' => 'fire', 'name' => 'Fire'],
            ['id' => 'other', 'name' => 'Other']
        ];

        // Filter untuk tampilan
        $callFilters = [
            ['label' => 'Semua', 'value' => 'all', 'icon' => 'fas fa-list'],
            ['label' => 'Pending', 'value' => 'pending', 'icon' => 'fas fa-clock'],
            ['label' => 'Resolved', 'value' => 'resolved', 'icon' => 'fas fa-check-circle'],
            ['label' => 'Cancelled', 'value' => 'cancelled', 'icon' => 'fas fa-times-circle']
        ];

        // Ambil perawat yang tersedia
        $nurses = Nurse::where('availability_status', 'available')->get();

        return view('users.emergency.index', [
            'filteredCalls' => $filteredCalls,
            'callFilters' => $callFilters,
            'emergencyTypes' => $emergencyTypes,
            'selectedFilter' => $selectedFilter,
            'nurses' => $nurses
        ]);
    }

    // Mendapatkan panggilan darurat berdasarkan filter
    protected function getFilteredCalls($userId, $status)
    {
        $query = EmergencyCall::where('user_id', $userId)->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->with('assignedNurse')->get();
    }

    // protected function findAvailableNurse
    protected function findAvailableNurse($service)
    {
        // Cari perawat dengan spesialisasi yang sesuai dan tersedia
        return Nurse::where('availability_status', 'available')
            ->whereHas('user', function($query) use ($service) {
                $query->where('specializations', 'like', "%{$service->type}%");
            })
            ->first();
    }
    public function createEmergencyCall(Request $request)
    {
        // Validasi input yang lebih komprehensif
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:medical,accident,fire,other',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'assigned_nurse_id' => 'nullable|exists:nurses,id'
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
            // Buat panggilan darurat baru
            $emergencyCall = EmergencyCall::create([
                'user_id' => Auth::id(),
                'emergency_type' => $request->input('type'),
                'location' => $request->input('location'),
                'description' => $request->input('description'),
                'status' => 'pending',
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'assigned_nurse_id' => $request->input('assigned_nurse_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);



            return response()->json([
                'success' => true,
                'message' => 'Panggilan darurat berhasil dibuat',
                'data' => $emergencyCall
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Emergency Call Creation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat panggilan darurat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Metode untuk membatalkan panggilan darurat
    public function cancelEmergencyCall($id)
    {
        $call = EmergencyCall::findOrFail($id);

        if ($call->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $call->status = 'cancelled';
        $call->save();

        return response()->json(['success' => true, 'message' => 'Panggilan darurat berhasil dibatalkan']);
    }

    // Metode untuk mendapatkan detail panggilan darurat
    public function getEmergencyCallDetail($id)
    {
        $call = EmergencyCall::with('assignedNurse')->findOrFail($id);
        return response()->json($call);
    }
}
