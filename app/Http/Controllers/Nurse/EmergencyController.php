<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmergencyCall;
use App\Models\Nurse;
use Carbon\Carbon;

class EmergencyController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan hanya nurse yang bisa mengakses
        $nurse = Auth::user()->nurse;

        if (!$nurse) {
            return redirect()->route('login')->with('error', 'Akses ditolak');
        }

        // Query dasar untuk panggilan darurat
        $query = EmergencyCall::query();

        // Filter berdasarkan status
        $status = $request->input('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status)->limit(5);
        }

        // Pencarian
        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->whereHas('user', function($userQuery) use ($searchQuery) {
                    $userQuery->where('name', 'like', "%{$searchQuery}%");
                })
                    ->orWhere('location', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        // Ambil panggilan darurat
        $emergencyCalls = $query->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($call) {
                return (object)[
                    'id' => $call->id,
                    'userId' => $call->user_id,
                    'location' => $call->location,
                    'description' => $call->description,
                    'emergencyType' => $call->emergency_type,
                    'status' => $call->status,
                    'latitude' => $call->latitude,
                    'longitude' => $call->longitude,
                    'createdAt' => $call->created_at,
                    'user' => (object)[
                        'name' => $call->user->name,
                        'phone' => $call->user->phone
                    ]
                ];
            });

        // Statistik panggilan darurat
        $emergencySummary = $this->getEmergencySummary($emergencyCalls);

        return view('nurses.emergency.index', compact('emergencyCalls', 'emergencySummary'));
    }

    protected function getEmergencySummary($emergencyCalls)
    {
        $emergencyCallsCollection = collect($emergencyCalls);

        return [
            'total' => $emergencyCallsCollection->count(),
            'pending' => $emergencyCallsCollection->filter(fn($call) => $call->status === 'pending')->count(),
            'responded' => $emergencyCallsCollection->filter(fn($call) => $call->status === 'responded')->count(),
            'resolved' => $emergencyCallsCollection->filter(fn($call) => $call->status === 'resolved')->count(),
            'medical' => $emergencyCallsCollection->filter(fn($call) => $call->emergencyType === 'medical')->count(),
            'accident' => $emergencyCallsCollection->filter(fn($call) => $call->emergencyType === 'accident')->count(),
            'other' => $emergencyCallsCollection->filter(fn($call) => $call->emergencyType === 'other')->count(),
        ];
    }

    // Metode untuk menanggapi panggilan darurat
    public function respondToEmergencyCall($callId)
    {
        $nurse = Auth::user()->nurse;

        try {
            $emergencyCall = EmergencyCall::findOrFail($callId);

            // Validasi status panggilan
            if ($emergencyCall->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Panggilan darurat sudah ditanggapi'
                ], 400);
            }

            // Update status dan assign nurse
            $emergencyCall->status = 'responded';
            $emergencyCall->assigned_nurse_id = $nurse->id;
            $emergencyCall->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menanggapi panggilan darurat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menanggapi panggilan darurat: ' . $e->getMessage()
            ], 500);
        }
    }

    // Metode untuk menyelesaikan panggilan darurat
    public function completeEmergencyCall(Request $request, $callId)
    {
        $nurse = Auth::user()->nurse;

        try {
            $emergencyCall = EmergencyCall::findOrFail($callId);

            // Validasi status panggilan
            if ($emergencyCall->status !== 'responded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Panggilan darurat belum ditanggapi'
                ], 400);
            }

            // Validasi bahwa nurse yang menyelesaikan adalah nurse yang menanggapi


            // Update status dan tambahkan catatan
            $emergencyCall->status = 'resolved';
            $emergencyCall->description = $request->input('notes', 'Panggilan darurat diselesaikan');
            $emergencyCall->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menyelesaikan panggilan darurat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan panggilan darurat: ' . $e->getMessage()
            ], 500);
        }
    }
}
