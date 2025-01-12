<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman registrasi
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Proses penyimpanan pengguna baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:10', 'max:15', 'unique:users,phone'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            // Custom error messages
            'name.required' => 'Nama lengkap wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'verified' => false,
            'emergency_contacts' => json_encode([]), // Inisialisasi kontak darurat
            'address' => null
        ]);

        // Optional: Kirim email verifikasi
        // $user->sendEmailVerificationNotification();

        auth()->login($user);

        return redirect()->route('user.index')
            ->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }
}
