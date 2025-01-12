<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    // Halaman utama profil
    public function index()
    {
        $user = Auth::user();

        // gunakan method updateStatus

        return view('users.profile.index', compact('user'));
    }

    // Metode untuk memperbarui profil
    public function update(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female'
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
            $user = Auth::user(); // Ambil pengguna yang sedang login
            // Update profil pengguna
            $user->update($request->only(['name', 'email', 'phone', 'address', 'birth_date', 'gender']));

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Metode untuk mengubah password
    public function changePassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user(); // Ambil pengguna yang sedang login

        // Cek password saat ini
        if (!password_verify($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah'
            ], 400);
        }

        try {
            // Update password
            $user->password = bcrypt($request->input('new_password'));
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Metode untuk mengunggah avatar
    public function uploadAvatar(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
            $user = Auth::user(); // Ambil pengguna yang sedang login

            // Simpan file avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Update path avatar di pengguna
            $user->avatar = '/storage/' . $avatarPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar berhasil diperbarui',
                'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah avatar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
