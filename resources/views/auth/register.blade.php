<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - {{ config('app.name', 'Nurse On Call') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-size: cover;
            background-attachment: fixed;
        }
        .register-container {
            backdrop-filter: blur(10px);
            background-color: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12">
<div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-100 opacity-90 z-0"></div>

<div class="relative z-10 max-w-md w-full space-y-8 register-container shadow-2xl rounded-3xl p-10 border border-white/20">
    <div class="text-center">
        <div class="mb-6 flex justify-center">
            <div class="bg-white/20  rounded-full shadow-lg backdrop-blur-md">
                <img
                    src="{{ asset('images/nurse.png') }}"
                    class="w-24 h-24 object-contain"
                    alt="Nurse On Call Logo"
                />
            </div>
        </div>

        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
            Daftar Akun Baru
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Buat akun Anda untuk menggunakan Nurse On Call
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6 mt-6">
        @csrf

        <!-- Input Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nama Lengkap
            </label>
            <input
                id="name"
                name="name"
                type="text"
                placeholder="Nama lengkap Anda"
                value="{{ old('name') }}"
                class="mt-1 block w-full px-3 py-2 border @error('name') border-red-500 @else dark:border-gray-600 @enderror bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                required
                autofocus
            />
            @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nomor Telepon
            </label>
            <input
                id="phone"
                name="phone"
                type="tel"
                placeholder="Nomor telepon Anda"
                value="{{ old('phone') }}"
                class="mt-1 block w-full px-3 py-2 border @error('phone') border-red-500 @else dark:border-gray-600 @enderror bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                required
            />
            @error('phone')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-                700 dark:text-gray-300">
                Email
            </label>
            <input
                id="email"
                name="email"
                type="email"
                placeholder="Email Anda"
                value="{{ old('email') }}"
                class="mt-1 block w-full px-3 py-2 border @error('email') border-red-500 @else dark:border-gray-600 @enderror bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                required
            />
            @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Password
            </label>
            <div class="relative">
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Password Anda"
                    class="mt-1 block w-full px-3 py-2 border @error('password') border-red-500 @else dark:border-gray-600 @enderror bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                    required
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button
                        type="button"
                        onclick="togglePasswordVisibility('password')"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none"
                    >
                        <i id="password-toggle-icon" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Konfirmasi Password
            </label>
            <div class="relative">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    placeholder="Ulangi password Anda"
                    class="mt-1 block w-full px-3 py-2 border @error('password_confirmation') border-red-500 @else dark:border-gray-600 @enderror bg-white/50 text-gray-800 placeholder-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                    required
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button
                        type="button"
                        onclick="togglePasswordVisibility('password_confirmation')"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none"
                    >
                        <i id="password_confirmation-toggle-icon" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition transform hover:scale-105 flex items-center justify-center space-x-2"
        >
            <i class="fas fa-user-plus"></i>
            <span>Daftar</span>
        </button>

        <!-- Login Link -->
        <div class="text-center mt-4">
            <p class="text-sm text-gray-700">
                Sudah punya akun?
                <a
                    href="{{ route('login') }}"
                    class="text-blue-600 hover:text-blue-800 hover:underline transition"
                >
                    Masuk sekarang
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
    function togglePasswordVisibility(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(`${inputId}-toggle-icon`);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }

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
