<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Nurse On Call</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
    @include('nurses.partials.mobile-sidebar')
</div>

<div class="flex min-h-screen">
    <!-- Desktop Sidebar -->
    <div class="hidden lg:block w-72 bg-white shadow-lg">
        @include('nurses.partials.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 z-20">
        @include('nurses.partials.header')

        <main class="p-4 md:p-6">
            @yield('content')
        </main>
        @stack('scripts')

    </div>

</div>
</body>
</html>
