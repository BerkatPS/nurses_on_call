<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use Illuminate\Http\Request;

class UserListNurseController extends Controller
{
    public function index()
    {
        // Ambil semua data perawat
        $nurses = Nurse::with('user')->get();

        return view('users.nurse.index', compact('nurses'));
    }
    public function show($id)
    {
        $nurse = Nurse::with('user')->findOrFail($id);
        return response()->json(['success' => true, 'nurse' => $nurse]);
    }

    public function getNursesByServiceType(Request $request)
    {
        $serviceType = $request->input('service_type');
        $nurses = Nurse::with('user')->whereHas('user', function ($query) use ($serviceType) {
            $query->where('specializations', 'like', "%{$serviceType}%");
        })->get();

        return response()->json($nurses);
    }
}
