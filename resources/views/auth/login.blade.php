<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Nurse On Call') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-size: cover;
            background-attachment: fixed;
        }
        .login-container {
            backdrop-filter: blur(10px);
            background-color: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12">
<div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-100 opacity-90 z-0"></div>

<div class="relative z-10 max-w-md w-full space-y-8 login-container shadow-2xl rounded-3xl p-10 border border-white/20">
    <div class="text-center">
        <div class="mb-6 flex justify-center">
            <div class="rounded-full w-32 h-32 overflow-hidden backdrop-blur-md">
                <img
                    src="{{ asset('images/nurse.png') }}"
                    class="w-full h-full object-contain"
                    alt="Nurse On Call Logo"
                />
            </div>
        </div>

        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
            Nurse On Call
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Masuk untuk melanjutkan layanan kesehatan Anda
        </p>
    </div>

    <!-- Error Handling -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Input -->
        <div>
            <div class="relative">
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="Email"
                    required
                    autofocus
                    class="w-full pl-12 pr-4 py-3 bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Password Input -->
        <div>
            <div class="relative">
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Password"
                    required
                    class="w-full pl-12 pr-4 py-3 bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input
                    id="remember_me"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500"
                >
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    Ingat saya
                </label>
            </div>

            @if (Route::has('password.request'))
                <div>
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 transition"
                    >
                        Lupa Password?
                    </a>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition transform hover:scale-105 flex items-center justify-center space-x-2"
        >
            <i class="fas fa-sign-in-alt"></i>
            <span>Masuk</span>
        </button>

        <!-- Divider -->
        <div class="flex items-center justify-center space-x-4">
            <div class="w-full h-px bg-gray-300"></div>
            <span class="text-gray-500 text-sm">atau</span>
            <div class="w-full h-px bg-gray-300"></div>
        </div>

        <!-- Social Login -->
        <div class="flex justify-center space-x-4">
            <button type="button" class="bg-white/50 p-3 rounded-full hover:bg-white transition border border-gray-200">
                <i class="fab fa-google text-red-500"></i>
            </button>
            <button type="button" class="bg-white/50 p-3 rounded-full hover:bg-white transition border border-gray-200">
                <i class="fab fa-facebook text-blue-600"></i>
            </button>
            <button type="button" class="bg-white/50 p-3 rounded-full hover:bg-white transition border border-gray-200">
                <i class="fab fa-apple text-gray-800"></i>
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-4">
            <p class="text-sm text-                gray-700">
                Belum punya akun?
                <a
                    href="{{ route('register') }}"
                    class="text-blue-600 hover:text-blue-800 hover:underline transition"
                >
                    Daftar sekarang
                </a>
            </p>
        </div>
    </form>
</div>

<!-- Background Animation -->
<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-blue-100/20 rounded-full animate-blob"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-indigo-100/20 rounded-full animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-blue-50/20 rounded-full animate-blob animation-delay-4000"></div>
</div>

<script>
    // Tambahkan animasi dan interaktivitas
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('input');

        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.add('border-blue-500');
            });

            input.addEventListener('blur', function() {
                this.classList.remove('border-blue-500');
            });
        });
    });
</script>

<style>
    @keyframes blob {
        0% {
            transform: scale(1) translate(0, 0) rotate(0deg);
        }
        33% {
            transform: scale(1.1) translate(-10%, -10%) rotate(10deg);
        }
        66% {
            transform: scale(0.9) translate(10%, 10%) rotate(-10deg);
        }
        100% {
            transform: scale(1) translate(0, 0) rotate(0deg);
        }
    }

    .animate-blob {
        animation: blob 10s infinite;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }

    .animation-delay-4000 {
        animation-delay: 4s;
    }

    /* Tambahan efek hover pada tombol */
    button:hover {
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
    }

    /* Efek transisi input */
    input {
        transition: all 0.3s ease;
    }
</style>
</body>
</html>
