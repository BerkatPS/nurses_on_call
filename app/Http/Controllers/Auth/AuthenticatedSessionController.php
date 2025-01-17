<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ], [
            'email.exists' => 'Email tidak terdaftar',
            'password.required' => 'Password wajib diisi'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Role-based redirection and status update
            $user = Auth::user();

            // Jika user adalah nurse, update status ke available
            if ($user->role === 'nurse') {
                $nurse = Nurse::where('user_id', $user->id)->first();
                if ($nurse) {
                    $nurse->availability_status = 'available';
                    $nurse->save();
                }
                return redirect()->route('nurse.index');
            }

            // Untuk user lain, lanjutkan seperti biasa
            if ($user->role === 'user') {
                return redirect()->route('user.index');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Cek apakah user adalah nurse
        $user = Auth::user();

        if ($user && $user->role === 'nurse') {
            $nurse = Nurse::where('user_id', $user->id)->first();
            if ($nurse) {
                $nurse->availability_status = 'offline';
                $nurse->save();
            }
        }

        // Lakukan logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ], [
            'email.exists' => 'Email tidak terdaftar',
            'password.required' => 'Password wajib diisi'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Role-based redirection
            $user = Auth::user();
            switch($user->role) {
                case 'nurse':
                    return redirect()->route('nurse.index');
                case 'user':
                    return redirect()->route('user.index');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid',
        ])->onlyInput('email');
    }
}
