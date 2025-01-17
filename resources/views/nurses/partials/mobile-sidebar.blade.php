<div
    x-show="sidebarOpen"
    x-transition:enter="transition ease-in-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in-out duration-300"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform -translate-x-full"
    class="fixed inset-0 bg-gradient-to-br from-blue-600 to-indigo-800 z-50 overflow-y-auto"
>
    <div class="container mx-auto px-4 py-6 relative h-full flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center mb-12 relative z-10">
            <div class="flex items-center space-x-4">
                <img
                    src="{{ asset('images/nurse.png') }}"
                    alt="Profile"
                    class="w-32 h-32 rounded-full object-cover border-4 border-white/30 shadow-2xl"
                />
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-wider">
                        Nurse On Call
                    </h1>
                    <p class="text-white/70 text-sm">

                    </p>
                </div>
            </div>

            <button
                @click="sidebarOpen = false"
                class="bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-300 transform hover:rotate-180"
            >
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Navigasi -->
        <nav class="space-y-6 flex-grow relative z-10">
            @php
                $mobileMenuItems = [
                    [
                        'icon' => 'fa-home',
                        'label' => 'Dashboard',
                        'route' => 'nurse.index',
                        'color' => 'from-blue-500 to-blue-600',
                        'description' => 'Ringkasan aktivitas dan statistik'
                    ],
                    [
                        'icon' => 'fa-calendar-alt',
                        'label' => 'Jadwal Layanan',
                        'route' => 'nurse.bookings',
                        'color' => 'from-green-500 to-green-600',
                        'description' => 'Kelola jadwal dan tugas'
                    ],
                    [
                        'icon' => 'fa-ambulance',
                        'label' => 'Panggilan Darurat',
                        'route' => 'nurse.emergency',
                        'color' => 'from-red-500 to-red-600',
                        'description' => 'Pantau panggilan darurat'
                    ],
                    [
                        'icon' => 'fa-star',
                        'label' => 'Reviews',
                        'route' => 'nurse.reviews',
                        'color' => 'text-blue-300',
                        'description' => 'Pantau ulasan'
                    ],

                    [
                        'icon' => 'fa-user',
                        'label' => 'Profil',
                        'route' => 'nurse.profile',
                        'color' => 'from-purple-500 to-purple-600',
                        'description' => 'Pengaturan dan informasi profil'
                    ]
                ];
            @endphp

            @foreach($mobileMenuItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="block bg-white/10 backdrop-blur-lg rounded-3xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl relative overflow-hidden"
                >
                    <!-- Gradient Background -->
                    <div class="absolute inset-0 bg-gradient-to-r {{ $item['color'] }} opacity-20"></div>

                    <div class="relative z-10 flex items-center">
                        <div class="bg-white/20 p-4 rounded-full mr-6">
                            <i class="fas {{ $item['icon'] }} text-3xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-1">
                                {{ $item['label'] }}
                            </h3>
                            <p class="text-white/70 text-sm">
                                {{ $item['description'] }}
                            </p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-white/70 text-xl"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </nav>

        <!-- Footer -->
        <div class="relative z-10 mt-12">
            <button
                onclick="confirmLogout()"
                class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-4 rounded-3xl
                       hover:from-red-600 hover:to-pink-700 transition-all duration-300
                       transform hover:scale-105 hover:shadow-2xl flex items-center justify-center space-x-3"
            >
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>

        <!-- Dekoratif Elemen -->
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/30 to-transparent opacity-50 pointer-events-none"></div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmLogout() {
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
