<div class="h-full py-6 px-4 bg-gradient-to-br from-blue-600 to-indigo-800 shadow-2xl relative overflow-hidden">
    <!-- Efek gradient dinamis -->
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-gradient-to-br from-blue-600 to-indigo-800 opacity-90 blur-3xl"></div>

    <!-- Logo dan Judul -->
    <div class="relative z-10 text-center mb-12">
        <div class="inline-block mb-4 transform transition-all duration-500 hover:rotate-6 hover:scale-110">
            <img
                src="{{ asset('images/nurse.png') }}"
                alt="Nurse On Call"
                class="w-20 h-20 mx-auto rounded-full shadow-2xl border-4 border-white/20"
            />
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-wider drop-shadow-lg">
            Nurse On Call
        </h1>
    </div>

    <!-- Navigasi -->
    <nav class="space-y-4 relative z-10">
        @php
                $menuItems = [
                    ['icon' => 'fa-home', 'label' => 'Dashboard', 'route' => 'nurse.index', 'color' => 'text-blue-300'],
                    ['icon' => 'fa-calendar-alt', 'label' => 'Jadwal Layanan', 'route' => 'nurse.bookings', 'color' => 'text-blue-300'],
                    ['icon' => 'fa-ambulance', 'label' => 'Panggilan Darurat', 'route' => 'nurse.emergency', 'color' => 'text-blue-300'],
                    ['icon' => 'fa-user', 'label' => 'Pengaturan', 'route' => 'nurse.profile', 'color' => 'text-blue-300']
                ];
        @endphp

        @foreach($menuItems as $item)
            <a
                href="{{ route($item['route']) }}"
                class="group flex items-center px-6 py-4 text-white hover:bg-white/10 rounded-xl transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:scale-105 relative overflow-hidden"
            >
                <!-- Efek hover -->
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-all duration-300"></div>

                <div class="relative z-10 flex items-center">
                    <i class="fas {{ $item['icon'] }} mr-4 text-xl {{ $item['color'] }} transition-all duration-300 group-hover:scale-125"></i>
                    <span class="font-medium tracking-wider">{{ $item['label'] }}</span>
                </div>

                <!-- Indikator aktif -->
                @if(request()->routeIs($item['route']))
                    <span class="absolute right-4 w-2 h-2 bg-white rounded-full animate-pulse"></span>
                @endif
            </a>
        @endforeach
    </nav>

    <!-- Profil Singkat -->


    <!-- Tombol Logout -->
    <div class="relative z-10">
        <button
            onclick="confirmLogout()"
            class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-4 rounded-xl
                   hover:from-red-600 hover:to-pink-700 transition-all duration-300
                   transform hover:scale-105 hover:shadow-2xl flex items-center justify-center space-x-3"
        >
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </div>

    <!-- Efek dekoratif -->
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-black/20 to-transparent"></div>
</div>

@push('scripts')
    <script>
        function confirmLogout() {
            console.log('Logout button clicked'); // Tambahkan ini untuk debugging
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full',
                    cancelButton: 'bg-gray-300 text-gray-800 px-6 py-2 rounded-full ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proses logout
                    fetch('{{ route("logout") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => {
                            // Redirect ke halaman login
                            window.location.href = '{{ route("login") }}';
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Logout',
                                text: 'Terjadi kesalahan. Silakan coba lagi.',
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        });
                }
            });
        }
    </script>
@endpush
