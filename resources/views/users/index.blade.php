@extends('users.layouts.user')

@section('page_title', 'Dashboard Pengguna')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">

            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Selamat Datang, {{ $dashboardData->userProfile->name }}!
                        </h2>
                        <p class="text-xl text-gray-600">Dashboard pribadi Anda - pusat kendali kesehatan</p>
                    </div>
                    <div class="flex space-x-4">
                        <div class="bg-blue-100 p-4 rounded-2xl text-center">
                            <h3 class="text-2xl font-bold text-blue-600">{{ $dashboardData->statistics->emergencyCalls }}</h3>
                            <p class="text-sm text-blue-500">Panggilan Darurat</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-2xl text-center">
                            <h3 class="text-2xl font-bold text-green-600">{{ $dashboardData->statistics->completedServices }}</h3>
                            <p class="text-sm text-green-500">Layanan Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Utama -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Kartu Profil -->
                <div class="md:col-span-1 bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 text-center transform hover:scale-[1.02] transition-all duration-300">
                    <div class="relative inline-block mb-6">
                        <img
                            src="{{ $dashboardData->userProfile->avatar }}"
                            alt="Profile"
                            class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-white shadow-2xl"
                        />
                    </div>
                    <h3 class="text-2xl font-bold text-blue-600">{{ $dashboardData->userProfile->name }}</h3>
                    <p class="text-gray-500 mb-4">{{ $dashboardData->userProfile->email }}</p>
                    <div class="flex justify-center space-x-4">
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">Online</span>
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">{{ $dashboardData->userProfile->role }}</span>
                    </div>
                </div>

                <!-- Statistik Layanan -->
                <div class="md:col-span-2 bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                    <h2 class="text-3xl font-bold text-blue-600 mb-6">Statistik Layanan</h2>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-blue-100 p-6 rounded-3xl text-center">
                            <i class="fas fa-calendar-check text-3xl text-blue-600 mb-4"></i>
                            <h3 class="text-xl font-semibold text-blue-700">Total Booking</h3>
                            <p class="text-3xl font-bold text-blue-800">{{ $dashboardData->statistics->totalBookings }}</p>
                        </div>
                        <div class="bg-green-100 p-6 rounded-3xl text-center">
                            <i class="fas fa-check-circle text-3xl text-green-600 mb-4"></i>
                            <h3 class="text-xl font-semibold text-green-700">Layanan Selesai</h3>
                            <p class="text-3xl font-bold text-green-800">{{ $dashboardData->statistics->completedServices }}</p>
                        </div>
                        <div class="bg-yellow-100 p-6 rounded-3xl text-center">
                            <i class="fas fa-clock text-3xl text-yellow-600 mb-4"></i>
                            <h3 class="text-xl font-semibold text-yellow-700">Layanan Pending</h3>
                            <p class="text-3xl font-bold text-yellow-800">{{ $dashboardData->statistics->pendingServices }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Layanan -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-blue-600">Jadwal Layanan</h2>
                    <a href="#" class="text-blue-500 hover:text-blue-700 transition">Lihat Semua</a>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($dashboardData->upcomingServices as $service)
                        <div class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xl font-bold text-blue-600">{{ $service['type'] }}</h4>
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                        {{ ucfirst($service['status']) }}
                    </span>
                            </div>
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user-md text-blue-500 mr-3"></i>
                                <div>
                                    <p class="font-semibold text-gray-700">
                                        {{ $service['doctor']['name'] }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $service['doctor']['specialization'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    {{ \Carbon\Carbon::parse($service['date'])->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    {{ $service['time'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panggilan Darurat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-blue-600">Riwayat Panggilan Darurat</h2>
                    <a href="#" class="text-blue-500 hover:text-blue-700 transition">Lihat Semua</a>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($dashboardData->emergencyCalls as $call)
                        <div class="bg-gradient-to-br from-white to-red-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xl font-bold text-red-600">{{ $call['type'] }}</h4>
                                <span
                                    class="
                                    {{ $call['status'] == 'resolved' ? 'bg-green-500' :
                                       ($call['status'] == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}
                                    text-white px-3 py-1 rounded-full text-xs
                                "
                                >
                                {{ ucfirst($call['status']) }}
                            </span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    {{ $call['location'] }}
                                </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-red-500"></i>
                                    {{ \Carbon\Carbon::parse($call['date'])->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-gray-600 italic">
                                    {{ $call['description'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #4299E1, #2C5282);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #3182CE, #2A4365);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi cards
            // const cards = document.querySelectorAll('.transform');
            //
            // cards.forEach((card, index) => {
            //     card.style.opacity = '0';
            //     card.style.transform = 'translateY(20px)';
            //
            //     setTimeout(() => {
            //         card.style.transition = 'all 0.8s ease';
            //         card.style.opacity = '1';
            //         card.style.transform = 'translateY(0)';
            //     }, index * 200);
            // });

            // // Efek hover interaktif
            // cards.forEach(card => {
            //     card.addEventListener('mouseover', function() {
            //         this.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
            //     });
            //
            //     card.addEventListener('mouseout', function() {
            //         this.style.boxShadow = '';
            //     });
            // });

            // Fungsi update statistik
            function updateRealtimeStats() {
                fetch()
                    .then(response => response.json())
                    .then(data => {
                        // Update statistik di halaman
                        document.querySelectorAll('.stats-container').forEach(container => {
                            const statsMap = {
                                'total-bookings': data.totalBookings,
                                'completed-services': data.completedServices,
                                'pending-services': data.pendingServices
                            };

                            Object.keys(statsMap).forEach(key => {
                                const element = container.querySelector(`.${key}`);
                                if (element) {
                                    element.textContent = statsMap[key];
                                }
                            });

                            // Tambahkan timestamp update
                            const updateTimeElement = container.querySelector('.last-updated');
                            if (updateTimeElement) {
                                updateTimeElement.textContent = `Terakhir diperbarui: ${data.lastUpdated}`;
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Gagal memperbarui statistik:', error);
                    });
            }

            // Perbarui statistik setiap 5 menit
            setInterval(updateRealtimeStats, 5 * 60 * 1000);

            // Notifikasi interaktif
            function showNotification(title, message, type = 'info') {
                const notificationContainer = document.createElement('div');
                notificationContainer.classList.add(
                    'fixed', 'top-4', 'right-4', 'z-50', 'bg-white',
                    'shadow-2xl', 'rounded-2xl', 'p-6', 'transform',
                    'transition-all', 'duration-300', 'ease-in-out'
                );

                // Warna berdasarkan tipe
                const typeColors = {
                    'info': 'border-blue-500',
                    'success': 'border-green-500',
                    'warning': 'border-yellow-500',
                    'error': 'border-red-500'
                };

                notificationContainer.classList.add(
                    'border-l-4',
                    typeColors[type] || typeColors['info']
                );

                notificationContainer.innerHTML = `
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-${
                    type === 'info' ? 'info-circle' :
                        type === 'success' ? 'check-circle' :
                            type === 'warning' ? 'exclamation-triangle' :
                                'times-circle'
                } text-3xl ${
                    type === 'info' ? 'text-blue-500' :
                        type === 'success' ? 'text-green-500' :
                            type === 'warning' ? 'text-yellow-500' :
                                'text-red-500'
                }"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">${title}</h3>
                        <p class="text-gray-600">${message}</p>
                    </div>
                </div>
            `;


            }

            // Contoh penggunaan notifikasi
            document.addEventListener('DOMContentLoaded', () => {
                // Notifikasi selamat datang
                showNotification(
                    'Selamat Datang!',
                    'Anda telah berhasil masuk ke dashboard.',
                    'success'
                );

                // Contoh notifikasi lainnya
                setTimeout(() => {
                    showNotification(
                        'Jadwal Layanan',
                        'Anda memiliki 2 jadwal layanan yang akan datang.',
                        'info'
                    );
                }, 3000);
            });


        });
    </script>
@endpush
