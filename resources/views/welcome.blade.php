<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse On Call - Solusi Kesehatan Profesional</title>

    <!-- Critical Dependencies -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#3B82F6',
                            50: '#E6F2FF',
                            100: '#DBEAFE',
                            500: '#3B82F6',
                            900: '#1E40AF'
                        },
                        secondary: {
                            DEFAULT: '#10B981',
                            50: '#ECFDF5',
                            500: '#10B981'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
            font-family: 'Inter', sans-serif;
        }
        .custom-gradient {
            background: linear-gradient(135deg, #3B82F6 0%, #3B82F6 50%, #1E40AF 100%);
        }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
    class="bg-gray-50 text-gray-800"
>
<!-- Modern Responsive Navigation -->
<nav
    class="fixed w-full z-50 transition-all duration-300 ease-in-out"
    :class="{
            'bg-white/90 shadow-md': scrollPosition > 50,
            'bg-transparent': scrollPosition <= 50
        }"
>
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Nurse On Call" class="h-10 w-10 rounded-full">
            <span class="text-2xl font-bold text-primary">Nurse On Call</span>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-6">
            <nav class="flex space-x-6">
                <a href="#home" class="text-gray-600 hover:text-primary transition">Beranda</a>
                <a href="#services" class="text-gray-600 hover:text-primary transition">Layanan</a>
                <a href="#testimonials" class="text-gray-600 hover:text-primary transition">Testimoni</a>
            </nav>

            <div class="space-x-3">
                <a href="{{ route('login') }}" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition">
                    Daftar
                </a>
            </div>
        </div>

        <!-- Mobile Menu Toggle -->
        <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden text-primary text-2xl focus:outline-none"
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
            <a href="#home" @click="mobileMenuOpen = false" class="text-xl text-gray-600 hover:text-primary">Beranda</a>
            <a href="#services" @click="mobileMenuOpen = false" class="text-xl text-gray-600 hover:text-primary">Layanan</a>
            <a href="#testimonials" @click="mobileMenuOpen = false" class="text-xl text-gray-600 hover:text-primary">Testimoni</a>
            <div class="flex flex-col space-y-4 pt-4">
                <a href="{{ route('login') }}" class="px-6 py-3 border border-primary text-primary rounded-lg">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3 bg-primary text-white rounded-lg">
                    Daftar
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header
    id="home"
    class="relative min-h-screen flex items-center justify-center custom-gradient text-white"
>
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div
            data-aos="fade-right"
            class="space-y-6 text-center md:text-left"
        >
            <h1 class="text-5xl font-bold leading-tight">
                Kesehatan Profesional, Seketika
            </h1>
            <p class="text-lg opacity-80">
                Platform kesehatan digital terdepan dengan perawat profesional siap melayani kebutuhan medis Anda.
            </p>
            <div class="flex justify-center md:justify-start space-x-4">
                <a
                    href="{{ route('login') }}"
                    class="px-6 py-3 bg-white text-primary rounded-lg hover:bg-gray-100 transition flex items-center space-x-2"
                >
                    <i class="fas fa-ambulance"></i>
                    <span>Layanan Darurat</span>
                </a>
                <a
                    href="{{ route('login') }}"
                    class="px-6 py-3 border border-white text-white rounded-lg hover:bg-white/20 transition flex items-center space-x-2"
                >
                    <i class="fas fa-calendar-alt"></i>
                    <span>Booking Perawat</span>
                </a>
            </div>
        </div>
        <div
            data-aos="fade-left"
            class="hidden md:block"
        >
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
                    ['icon' => 'fa-ambulance', 'title' => 'Layanan Darurat', 'description' => 'Respon cepat dalam 15 menit dengan tim medis berpengalaman.'],
                    ['icon' => 'fa-home', 'title' => 'Home Care', 'description' => 'Perawatan komprehensif di kenyamanan rumah Anda.'],
                    ['icon' => 'fa-user-md', 'title' => 'Perawat Profesional', 'description' => 'Tenaga medis tersertifikasi dengan pengalaman terjamin.']
                ];
            @endphp
            @foreach($services as $service)
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-2">
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
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition">
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
            <a href="{{ route('register') }}" class="bg-white text-primary px-6 py-3 rounded-lg hover:bg-gray-200 transition">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-gray-200 hover:text-primary transition">Masuk</a>
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
    AOS.init();
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

        // Toggle Mobile Menu
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('translate-x-full');
            mobileMenu.classList.toggle('translate-x-0');
        });

        // Close Mobile Menu
        mobileMenuClose.addEventListener('click', function() {
            mobileMenu.classList.remove('translate-x-0');
            mobileMenu.classList.add('translate-x-full');
        });

        // Close Menu when Link is Clicked
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('translate-x-0');
                mobileMenu.classList.add('translate-x-full');
            });
        });
    });
</script>
</body>
</html>
