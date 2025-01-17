<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Nurse On Call</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#3B82F6',
                            50: '#EFF6FF',
                            500: '#3B82F6'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-gray-50 font-sans">
<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" class="fixed inset-0 bg-white z-50 overflow-y-auto">
    @include('users.partials.mobile-sidebar')
</div>

<div class="flex min-h-screen">
    <!-- Desktop Sidebar -->
    <div class="hidden lg:block w-80 bg-white shadow-lg">
        @include('users.partials.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 ">
        @include('users.partials.header')

        <main class="p-4 md:p-6">
            @yield('content')
        </main>

        @stack('scripts') <!-- Pastikan ini ada di bawah </body> -->
    </div>
</div>
</body>
</html>
