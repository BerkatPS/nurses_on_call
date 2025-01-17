@extends('nurses.layouts.nurse')

@section('page_title', 'Dashboard Perawat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Selamat Datang -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Selamat Datang, {{ auth()->user()->name }}!
                        </h2>
                        <p class="text-xl text-gray-600">Dashboard pribadi Anda - pusat kendali layanan kesehatan</p>
                    </div>
                    <div class="flex space-x-4">
                        <div class="bg-blue-100 p-4 rounded-2xl text-center">
                            <h3 class="text-2xl font-bold text-blue-600">{{ $dashboardData->statistics->pendingServices }}</h3>
                            <p class="text-sm text-blue-500">Tugas Baru</p>
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
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                            alt="Profile"
                            class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-white shadow-2xl"
                        />
                    </div>
                    <h3 class="text-2xl font-bold text-blue-600">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-500 mb-4">Perawat Umum</p>
                    <div class="flex justify-center space-x-4">
                        <p class="text-gray-600">
                            @if (auth()->user()->nurse->availability_status == 'available')
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-800 bg-green-200 rounded-full">
            {{ strtoupper(auth()->user()->nurse->availability_status) }}
        </span>
                            @elseif (auth()->user()->nurse->availability_status == 'on-call')
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-white bg-gray-800 rounded-full">
            {{ strtoupper(auth()->user()->nurse->availability_status) }}

        </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-red-800 bg-red-200 rounded-full">
                                {{ strtoupper(auth()->user()->nurse->availability_status) }}
                            @endif
                        </p>                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">{{ strtoupper(auth()->user()->role) }}</span>
                    </div>
                </div>

                <!-- Tugas Aktif -->
                <div class="md:col-span-2 bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-blue-600">Tugas Aktif</h2>
                        <a href="/nurse/bookings" class="text-blue-500 hover:text-blue-700 transition">Lihat Semua</a>
                    </div>
                    <div class="space-y-6">
                        @forelse($activeAssignments as $assignment)
                            <div class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2"></div>
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-xl font-bold text-blue-600">{{ $assignment->patient }}</h4>
                                        <span class="
                        @switch($assignment->status)
                            @case('confirmed') bg-green-500 @break
                            @case('pending') bg-yellow-500 @break
                            @default bg-gray-500
                        @endswitch
                        text-white px-3 py-1 rounded-full text-xs"
                                        >
                        {{ ucfirst($assignment->status) }}
                    </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p class="text-gray-600">
                                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                            {{ \Carbon\Carbon::parse($assignment->date)->translatedFormat('d F Y') }}
                                        </p>
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="acceptBooking({{ $assignment->id }})"
                                                class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                                            >
                                                Terima
                                            </button>
                                            <button
                                                onclick="showBookingDetails({{ json_encode($assignment) }})"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
                                            >
                                                Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Tidak Ada Tugas Aktif</h3>
                                <p class="text-gray-500 mt-2">Belum ada tugas yang sedang berlangsung</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 text-center transform hover:scale-[1.02] transition-all duration-300">
                    <i class="fas fa-calendar-check text-4xl text-blue-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Total Booking</h3>
                    <p class="text-4xl font-extrabold text-blue-600">{{ $dashboardData->statistics->totalBookings }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 text-center transform hover:scale-[1.02] transition-all duration-300">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Layanan Selesai</h3>
                    <p class="text-4xl font-extrabold text-green-600">{{ $dashboardData->statistics->completedServices }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 text-center transform hover:scale-[1.02] transition-all duration-300">
                    <i class="fas fa-clock text-4xl text-yellow-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Layanan Pending</h3>
                    <p class="text-4xl font-extrabold text-yellow-600">{{ $dashboardData->statistics->pendingServices }}</p>
                </div>
            </div>

{{--            <!-- Grafik Kinerja -->--}}
{{--            <div class="">--}}
{{--                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">--}}
{{--                    <h3 class="text-3xl font-bold text-blue-600 mb-6">Kinerja Bulanan</h3>--}}
{{--                    <canvas id="performanceChart" class="w-full h-96"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="modal fade" id="serviceDetailModal" tabindex="-1" aria-labelledby="serviceDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="serviceDetailModalContent">
                            <!-- Konten detail booking akan dimasukkan di sini -->
                        </div>

                    </div>
                </div>
            </div>

{{--            <!-- Notifikasi dan Aktivitas -->--}}
{{--            <div class="grid md:grid-cols-2 gap-8">--}}
{{--                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">--}}
{{--                    <div class="flex justify-between items-center mb-6">--}}
{{--                        <h3 class="text-3xl font-bold text-blue-600">Notifikasi</h3>--}}
{{--                        <a href="#" class="text-blue-500 hover:text-blue-700 transition">Lihat Semua</a>--}}
{{--                    </div>--}}
{{--                    <div class="space-y-4">--}}
{{--                        @foreach($notifications as $notification)--}}
{{--                            <div class="bg-gray-50 p-4 rounded-2xl hover:bg-blue-50 transition">--}}
{{--                                <div class="flex justify-between items-center">--}}
{{--                                    <div>--}}
{{--                                        <h4 class="font-bold text-gray-800">{{ $notification->title }}</h4>--}}
{{--                                        <p class="text-gray-600 text-sm">{{ $notification->message }}</p>--}}
{{--                                    </div>--}}
{{--                                    <span class="text-xs text-gray-500">--}}
{{--                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">--}}
{{--                    <div class="flex justify-between items-center mb-6">--}}
{{--                        <h3 class="text-3xl font-bold text-blue-600">Aktivitas Terakhir</h3>--}}
{{--                        <a href="#" class="text-blue-500 hover:text-blue-700 transition">Riwayat Lengkap</a>--}}
{{--                    </div>--}}
{{--                    <div class="space-y-4">--}}
{{--                        @foreach($recentActivities as $activity)--}}
{{--                            <div class="bg-gray-50 p-4 rounded-2xl hover:bg-green-50 transition">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <div class="mr-4">--}}
{{--                                        <i class="{{ $activity->icon }} text-2xl {{ $activity->iconColor }}"></i>--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        <h4 class="font-bold text-gray-800">{{ $activity->title }}</h4>--}}
{{--                                        <p class="text-gray-600 text-sm">{{ $activity->description }}</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>


        function acceptBooking(bookingId) {
            Swal.fire({
                title: 'Konfirmasi Booking',
                text: "Apakah Anda yakin ingin menerima booking ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/nurse/bookings/${bookingId}/confirm`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Booking Diterima',
                                    text: data.message,
                                }).then(() => {
                                    window.location.reload(); // Reload halaman untuk memperbarui data
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menerima Booking',
                                    text: data.message || 'Terjadi kesalahan',
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Sistem',
                                text: 'Terjadi kesalahan saat menerima booking',
                            });
                        });
                }
            });
        }

        function showBookingDetails(booking) {
            const modalContent = document.getElementById('serviceDetailModalContent');

            modalContent.innerHTML = `
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                Detail Layanan
            </h2>
            <p class="text-gray-600">Informasi lengkap tentang layanan</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Informasi Pasien</h3>
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <img
                            src="https://ui-avatars.com/api/?name=${encodeURIComponent(booking.user.name)}"
                            alt="${booking.user.name}"
                            class="w-16 h-16 rounded-full border-4 border-blue-500 mr-4"
                        />
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">${booking.user.name}</h4>
                            <p class="text-gray-600">${booking.user.phone}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p>
                            <i class="fas fa-notes-medical text-blue-500 mr-2"></i>
                            <span class="font-semibold">Layanan:</span> ${booking.service.name}
                        </p>
                        <p>
                            <i class="fas fa-calendar text-blue-500 mr-2"></i>
                            <span class="font-semibold">Waktu:</span> ${formatDateTime(booking.startTime)}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <h <h3 class="text-xl font-semibold text-blue-600 mb-4">Informasi Booking</h3>
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <p>
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        <span class="font-semibold">Status:</span> ${booking.status}
                    </p>
                    <p>
                        <i class="fas fa-comment-dots text-blue-500 mr-2"></i>
                        <span class="font-semibold">Catatan:</span> ${booking.notes || 'Tidak ada catatan'}
                    </p>
                </div>
            </div>
        </div>
    `;

            // Tampilkan modal
            $('#serviceDetailModal').modal('show');
        }

        function formatDateTime(dateTime) {
            return new Date(dateTime).toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js tidak dimuat dengan benar.');
                return;
            }

            Chart.defaults.font.family = "'Inter', sans-serif"; // Ganti global.defaultFontFamily
            Chart.defaults.color = '#374151'; // Ganti global.defaultFontColor

            // Chart Kinerja Bulanan
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            const performanceChart = new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'], // Ganti dengan data bulan yang sesuai
                    datasets: [{
                        label: 'Layanan Diselesaikan',
                        data: [], // Data akan diisi dari server
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: 'white',
                        pointHoverBackgroundColor: 'white',
                        pointHoverBorderColor: 'rgba(59, 130, 246, 1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#6B7280'
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)',
                                zeroLineColor: 'rgba(107, 114, 128, 0.25)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#6B7280'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }
                    }
                }
            });

            // Chart Distribusi Panggilan Darurat
            const emergencyCtx = document.getElementById('emergencyChart').getContext('2d');
            const emergencyChart = new Chart(emergencyCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Medis', 'Trauma', 'Pediatrik', 'Geriatrik'], // Ganti dengan kategori yang sesuai
                    datasets: [{
                        data: [], // Data akan diisi dari server
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',   // Red
                            'rgba(59, 130, 246, 0.8)',  // Blue
                            'rgba(234, 179, 8, 0.8)',   // Yellow
                            'rgba(16, 185, 129, 0.8)'   // Green
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba( 17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                }
            });

            fetchPerformanceData(performanceChart);
            fetchEmergencyDistribution(emergencyChart);
        });

        function fetchPerformanceData(performanceChart) {
            fetch('{{ route("nurse.dashboard.performance") }}')
                .then(response => response.json())
                .then(data => {
                    performanceChart.data.datasets[0].data = data; // Pastikan data yang diterima sesuai
                    performanceChart.update();
                })
                .catch(error => console.error('Error fetching performance data:', error));
        }

        function fetchEmergencyDistribution(emergencyChart) {
            fetch('{{ route("nurse.dashboard.emergency") }}')
                .then(response => response.json())
                .then(data => {
                    emergencyChart.data.datasets[0].data = data; // Pastikan data yang diterima sesuai
                    emergencyChart.update();
                })
                .catch(error => console.error('Error fetching emergency distribution data:', error));
        }
    </script>
@endpush
