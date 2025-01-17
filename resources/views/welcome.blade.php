<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse On Call - Solusi Kesehatan Profesional</title>

    <!-- CSS Dependencies -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#4A90E2',
                            50: '#E6F2FF',
                            100: '#B6D4F4',
                            500: '#4A90E2',
                            900: '#2C5E9E'
                        },
                        secondary: {
                            DEFAULT: '#2ECC71',
                            50: '#E9F7EF',
                            500: '#2ECC71'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }
        .elegant-gradient {
            background: linear-gradient(135deg, #4A90E2 0%, #2C5E9E 100%);
        }
        .soft-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body
    x-data="{
        mobileMenuOpen: false,
        scrollPosition: 0,
        checkScroll() {
            this.scrollPosition = window.pageYOffset;
        }
    }"
    @scroll.window="checkScroll()"
    class="bg-gray-50 font-sans"
>
<!-- Navigation -->
<nav
    class="fixed w-full z-50 transition-all duration-300"
    :class="{
            'bg-white shadow-md': scrollPosition > 50,
            'bg-transparent': scrollPosition <= 50
        }"
>
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/nurse.png') }}" alt="Nurse On Call" class="h-10 w-10 rounded-full">
            <span class="text-2xl font-bold text-primary-400">Nurse On Call</span>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-6">
            <nav class="flex space-x-6">
                <a href="#home" class="text-gray-800 hover:text-primary">Beranda</a>
                <a href="#services" class="text-gray-800 hover:text-primary">Layanan</a>
                <a href="#testimonials" class="text-gray-800 hover:text-primary">Testimoni</a>
            </nav>

            <div class="space-x-3">
                <a href="/login" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white">
                    Masuk
                </a>
                <a href="/register" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                    Daftar
                </a>
            </div>
        </div>

        <!-- Mobile Menu Toggle -->
        <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden text-primary text-2xl"
        >
            <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="mobileMenuOpen"
        x-transition
        class="md:hidden fixed inset-0 bg-white z-40 p-6"
    >
        <div class="flex flex-col space-y-6 text-center">
            <a href="#home" @click="mobileMenuOpen = false" class="text-xl text-gray-600">Beranda</a>
            <a href="#services" @click="mobileMenuOpen = false" class="text-xl text-gray-600">Layanan</a>
            <a href="#testimonials" @click="mobileMenuOpen = false" class="text-xl text-gray-600">Testimoni</a>

            <div class="flex flex-col space-y-4 pt-4">
                <a href="#login" class="px-6 py-3 border border-primary text-primary rounded-lg">
                    Masuk
                </a>
                <a href="#register" class="px-6 py-3 bg-primary text-white rounded-lg">
                    Daftar
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header
    id="home"
    class="relative min-h-screen flex items-center elegant-gradient text-white"
>
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 text-center md:text-left">
            <h1 class="text-5xl font-bold leading-tight">
                Kesehatan Profesional, Seketika
            </h1>
            <p class="text-lg opacity-80">
                Platform kesehatan digital terdepan dengan perawat profesional.
            </p>
            <div class="flex justify-center md:justify-start space-x-4">
                <a href="/login" class="px-6 py-3 bg-white text-primary rounded-lg hover:bg-gray-100">
                    <i class="fas fa-ambulance mr-2"></i>Layanan Darurat
                </a>
                <a href="/login" class="px-6 py-3 border border-white text-white rounded-lg hover:bg-white/20">
                    <i class="fas fa-calendar-alt mr-2"></i>Booking Perawat
                </a>
            </div>
        </div>
        <div class="hidden md:block">
            <img
                src="{{ asset('images/nurse.png') }}"
                alt="Medical Illustration"
                class="w-full max-w-md mx-auto"
            />
        </div>
    </div>
</header>

<!-- Layanan Section -->
<section id="services" class="py-20 bg-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-primary mb-12">Layanan Kami</h2>
        <div class="grid md:grid-cols-3 gap-8">
            @php
                $services = [
                    ['icon' => 'fa-ambulance', 'title' => 'Layanan Darurat', 'description' => 'Respon cepat dalam 15 menit.'], ['icon' => 'fa-home', 'title' => 'Home Care', 'description' => 'Perawatan komprehensif di rumah Anda.'],
                    ['icon' => 'fa-user-md', 'title' => 'Perawat Profesional', 'description' => 'Tenaga medis tersertifikasi dan berpengalaman.']
                ];
            @endphp
            @foreach($services as $service)
                <div class="bg-white soft-shadow rounded-xl p-8 transition-transform transform hover:scale-105">
                    <div class="text-5xl flex justify-center mb-4 text-primary">
                        <i class="fas {{ $service['icon'] }}"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $service['title'] }}</h3>
                    <p class="text-gray-600">{{ $service['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimoni Section -->
<section id="testimonials" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-primary mb-12">Apa Kata Mereka</h2>
        @php
            $testimonials = [
                ['name' => 'Sarah Kumalasari', 'role' => 'Pasien Homecare', 'quote' => 'Pelayanan luar biasa! Perawat profesional, empati, dan sangat membantu.', 'rating' => 5],
                ['name' => 'Dr. Michael Rusli', 'role' => 'Praktisi Medis', 'quote' => 'Nurse On Call mengubah paradigma layanan kesehatan dengan teknologi canggih.', 'rating' => 5]
            ];
        @endphp
        <div class="grid md:grid-cols-2 gap-8">
            @foreach($testimonials as $testimonial)
                <div class="bg-white p-8 rounded-xl shadow-md transition-transform transform hover:scale-105">
                    <p class="italic text-gray-600 mb-6">"{{ $testimonial['quote'] }}"</p>
                    <div class="flex items-center justify-center space-x-4">
                        <div>
                            <h4 class="font-semibold">{{ $testimonial['name'] }}</h4>
                            <p class="text-gray-500">{{ $testimonial['role'] }}</p>
                            <div class="text-yellow-500">
                                @for($i = 0; $i < $testimonial['rating']; $i++)
                                    ★
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section id="cta" class="bg-primary text-white py-20 text-center">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold mb-6">Siap Memulai Perjalanan Kesehatan Anda?</h2>
        <p class="text-xl mb-8">Dapatkan layanan perawatan profesional hanya dengan beberapa klik</p>
        <div class="flex justify-center space-x-4">
            <a href="/register" class="bg-white text-primary px-6 py-3 rounded-lg hover:bg-gray-200">Daftar Sekarang</a>
            <a href="/login" class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-gray-200 hover:text-primary">Masuk</a>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; 2023 Nurse On Call. Semua hak dilindungi.</p>
        <div class="flex justify-center space-x-4 mt-4">
            <a href="/privacy" class="hover:underline">Kebijakan Privasi</a>
            <a href="/terms" class="hover:underline">Syarat dan Ketentuan</a>
        </div>
    </div>
</footer>

<!-- Script untuk Mobile Menu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.querySelector('button[aria-expanded]');
        const mobileMenu = document.querySelector('.md:hidden');

        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>
</body>
</html>
