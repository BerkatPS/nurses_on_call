@extends('nurses.layouts.nurse')

@section('page_title', 'Panggilan Darurat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Panggilan Darurat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Panggilan Darurat
                        </h2>
                        <p class="text-xl text-gray-600">Pantau dan kelola situasi darurat dengan cepat dan efisien</p>
                    </div>
                </div>
            </div>

            <!-- Statistik Ringkasan -->
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $statistikPanggilan = [
                        [
                            'label' => 'Total Panggilan',
                            'value' => $emergencySummary['total'] ?? 0,
                            'icon' => 'fas fa-ambulance',
                            'color' => 'from-blue-500 to-blue-600',
                            'bgColor' => 'bg-gradient-to-br'
                        ],
                        [
                            'label' => 'Menunggu Tanggapan',
                            'value' => $emergencySummary['pending'] ?? 0,
                            'icon' => 'fas fa-clock',
                            'color' => 'from-yellow-500 to-yellow-600',
                            'bgColor' => 'bg-gradient-to-br'
                        ],
                        [
                            'label' => 'Panggilan Medis',
                            'value' => $emergencySummary['medical'] ?? 0,
                            'icon' => 'fas fa-notes-medical',
                            'color' => 'from-red-500 to-red-600',
                            'bgColor' => 'bg-gradient-to-br'
                        ]
                    ];
                @endphp

                @foreach($statistikPanggilan as $stat)
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 transform hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                        <div class="{{ $stat['bgColor'] }} {{ $stat['color'] }} p-6 text-white relative">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-16 -mr-16"></div>
                            <div class="relative z-10 flex justify-between items-center">
                                <div>
                                    <p class="text-sm opacity-80 mb-2">{{ $stat['label'] }}</p>
                                    <h3 class="text-4xl font-bold">{{ $stat['value'] }}</h3>
                                </div>
                                <div class="bg-white/20 p-4 rounded-full">
                                    <i class="{{ $stat['icon'] }} text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pencarian dan Filter -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex flex-col md:flex-row gap-6 justify-between items-center">
                    <div class="relative w-full max-w-md">
                        <form action="{{ route('nurse.emergency') }}" method="GET" class="w-full">
                            <input
                                type="text"
                                name="search"
                                placeholder="Cari panggilan darurat..."
                                value="{{ request('search') }}"
                                class="w-full px-6 py-4 pl-12 rounded-full bg-gray-100 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            />
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </form>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @php
                            $filters = [
                                ['label' => 'Semua', 'status' => 'all'],
                                ['label' => 'Menunggu', 'status' => 'pending'],
                                ['label' => 'Ditanggapi', 'status' => 'responded'],
                                ['label' => 'Selesai', 'status' => 'resolved']
                            ];
                        @endphp

                        @foreach($filters as $filter)
                            <a href="{{ route('nurse.emergency', ['status' => $filter['status']]) }}"
                               class="px-6 py-3 rounded-full transition-all duration-300
                           {{ request('status', 'all') === $filter['status']
                                ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300 hover:text-gray-900' }}">
                                {{ $filter['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Daftar Panggilan Darurat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                @if(count($emergencyCalls) == 0)
                    <div class="text-center py-16">
                        <i class="fas fa-ambulance text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Tidak Ada Panggilan Darurat</h3>
                        <p class="text-gray-500">Belum ada panggilan darurat yang tersedia</p>
                    </div>
                @else
                    <div class="grid md:grid-cols-1 gap-6">
                        @foreach($emergencyCalls as $call)
                            <div
                                class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] cursor-pointer"
                                onclick="openEmergencyCallDetails({{ json_encode($call) }})"
                            >
                                <div class="p-6 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2 opacity-10"></div>

                                    <div class="relative z-10 flex justify-between items-start">
                                        <div>
                                            <h3 class="text-2xl font-bold text-blue-600 mb-2">
                                                Panggilan Darurat #{{ $call->id }}
                                            </h3>
                                            <div class="flex items-center gap-3
                                                {{ $call->status == 'pending' ? 'text-yellow-500' : ($call->status == 'responded' ? 'text-green-500' : 'text-red-500') }}
                                            ">
                                                <img
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($call->user->name) }}"
                                                    alt="{{ $call->user->name }}"
                                                    class="w-12 h-12 rounded-full border-2 border-blue-500"
                                                />
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $call->user->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $call->user->phone }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="
                                        @switch($call->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('responded') bg-green-100 text-green-800 @break
                                            @case('resolved') bg-blue-100 text-blue-800 @break
                                        @endswitch
                                        px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2"
                                        >
                                            <i class="
                                            @switch($call->status)
                                                @case('pending') fas fa-clock @break
                                                @case('responded') fas fa-check-circle @break
                                                @case('resolved') fas fa-check-double @break
                                            @endswitch
                                        "></i>
                                            @switch($call->status)
                                                @case('pending') Menunggu @break
                                                @case('responded') Ditanggapi @break
                                                @case('resolved') Selesai @break
                                            @endswitch
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-2xl mb-4">
                                        <p class="text-gray-600 italic">{{ $call->description }}</p>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                                <span class="text-gray-600">{{ $call->location }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                                <span class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($call->createdAt)->translatedFormat('d F Y H:i') }}
                                            </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if($call->status === 'pending')
                                                <button
                                                    onclick="respondToEmergencyCall('{{ $call->id }}')"
                                                    class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-all"
                                                >
                                                    Tanggapi
                                                </button>
                                            @elseif($call->status === 'responded')
                                                <button
                                                    onclick="completeEmergencyCall('{{ $call->id }}')"
                                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all"
                                                >
                                                    Selesaikan
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Detail Panggilan Darurat -->
        <div
            id="emergencyCallModal"
            class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4"
        >
            <div class="bg-white rounded-3xl max-w-2xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                <button
                    onclick="closeEmergencyCallModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <div id="emergencyCallModalContent">
                    <!-- Konten akan diisi secara dinamis -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openEmergencyCallDetails(call) {
            const modal = document.getElementById('emergencyCallModal');
            const modalContent = document.getElementById('emergencyCallModalContent');

            // Isi konten modal
            modalContent.innerHTML = `
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                Detail Panggilan Darurat
            </h2>
            <p class="text-gray-600">Informasi lengkap tentang panggilan darurat</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Informasi Pasien</h3>
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <img
                            src="https://ui-avatars.com/api/?name=${encodeURIComponent(call.user.name)}"
                            alt="${call.user.name}"
                            class="w-16 h-16 rounded-full border-4 border-blue-500 mr-4"
                        />
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">${call.user.name}</h4>
                            <p class="text-gray-600">${call.user.phone}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p>
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            <span class="font-semibold">Lokasi:</span> ${call.location}
                        </p>
                        <p>
                            <i class="fas fa-calendar text-blue-500 mr-2"></i>
                            <span class="font-semibold">Waktu Panggilan:</span> ${formatDateTime(call.createdAt)}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Detail Panggilan</h3>
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <p class="mb-4">
                        <span class="font-semibold">Deskripsi:</span>
                        <span class="italic text-gray-700">${call.description}</span>
                    </p>
                    <p class="mb-4">
                        <span class="font-semibold">Tipe Darurat:</span>
                        <span class="${getEmergencyTypeClass(call.emergencyType).color} px-3 py-1 rounded-full text-xs font-semibold">
                            ${getEmergencyTypeClass(call.emergencyType).text}
                        </span>
                    </p>
                    <p>
                        <span class="font-semibold">Status:</span>
                        <span class="${getStatusClass(call.status).color} px-3 py-1 rounded-full text-xs font-semibold">
                            ${getStatusClass(call.status).text}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between space-x-4">
            ${getActionButtons(call)}
        </div>
    `;

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animasi
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }

        function closeEmergencyCallModal() {
            const modal = document.getElementById('emergencyCallModal');
            const modalContent = document.getElementById('emergencyCallModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            // Sembunyikan modal setelah animasi selesai
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function getEmergencyTypeClass(type) {
            const typeMap = {
                medical: {
                    text: 'Medis',
                    color: 'bg-red-100 text-red-800',
                    icon: 'fas fa-notes-medical'
                },
                accident: {
                    text: 'Kecelakaan',
                    color: 'bg-yellow-100 text-yellow-800',
                    icon: 'fas fa-ambulance'
                },
                other: {
                    text: 'Lainnya',
                    color: 'bg-blue-100 text-blue-800',
                    icon: 'fas fa-question-circle'
                }
            };
            return typeMap[type] || {
                text: 'Tidak Diketahui',
                color: 'bg-gray-100 text-gray-800',
                icon: 'fas fa-question-circle'
            };
        }

        function getStatusClass(status) {
            const statusMap = {
                pending: {
                    text: 'Menunggu',
                    color: 'bg-yellow-100 text-yellow-800',
                    icon: 'fas fa-clock'
                },
                responded: {
                    text: 'Ditanggapi',
                    color: 'bg-green-100 text-green-800',
                    icon: 'fas fa-check-circle'
                },
                resolved: {
                    text: 'Selesai',
                    color: 'bg-blue-100 text-blue-800',
                    icon: 'fas fa-check-double'
                }
            };
            return statusMap[status] || {
                text: 'Status Tidak Diketahui',
                color: 'bg-gray-100 text-gray-800',
                icon: 'fas fa-question-circle'
            };
        }

        function getActionButtons(call) {
            switch(call.status) {
                case 'pending':
                    return `
                    <button
                        onclick="respondToEmergencyCall('${call.id}')"
                        class="w-full px-6 py-3 bg-green-500 text-white rounded-full hover:bg-green-600 transition-all flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-ambulance"></i>
                        <span>Tanggapi Panggilan</span>
                    </button>
                `;
                case 'responded':
                    return `
                    <button
                        onclick="completeEmergencyCall('${call.id}')"
                        class="w-full px-6 py-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-check-double"></i>
                        <span>Selesaikan Panggilan</span>
                    </button>
                `;
                case 'resolved':
                    return `
                    <button
                        disabled
                        class="w-full px-6 py-3 bg-gray-300 text-gray-600 rounded-full cursor-not-allowed flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-archive"></i>
                        <span>Panggilan Selesai</span>
                    </button>
                `;
                default:
                    return '';
            }
        }

        function respondToEmergencyCall(callId) {
            Swal.fire({
                title: 'Tanggapi Panggilan Darurat',
                text: 'Apakah Anda yakin ingin menanggapi panggilan darurat ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tanggapi',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full',
                    cancelButton: 'bg-gray-300 text-gray-800 px-6 py-2 rounded-full ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/nurse/emergency/${callId}/respond`, {
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
                                    title: 'Panggilan Ditanggapi',
                                    text: 'Anda berhasil menanggapi panggilan darurat',
                                    customClass: {
                                        popup: 'rounded-3xl',
                                        confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menanggapi',
                                    text: data.message || 'Terjadi kesalahan saat menanggapi panggilan',
                                    customClass: {
                                        popup: 'rounded-3xl',
                                        confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Sistem',
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

        function completeEmergencyCall(callId) {
            Swal.fire({
                title: 'Selesaikan Panggilan Darurat',
                text: 'Apakah Anda yakin ingin menandai panggilan darurat ini sebagai selesai?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal',
                input: 'textarea',
                inputLabel: 'Catatan Penanganan',
                inputPlaceholder: 'Tuliskan detail penanganan yang dilakukan...',
                inputAttributes: {
                    'aria-label': 'Catatan penanganan'
                },
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full',
                    cancelButton: 'bg-gray-300 text-gray-800 px-6 py-2 rounded-full ml-2',
                    input: 'rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-500'
                },
                preConfirm: (notes) => {
                    if (!notes) {
                        Swal.showValidationMessage('Harap berikan catatan penanganan');
                    }
                    return notes;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/nurse/emergency/${callId}/complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            notes: result.value
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Panggilan Darurat Selesai',
                                    text: 'Panggilan darurat telah berhasil diselesaikan',
                                    html: `
                                <div class="text-left space-y-2">
                                    <p><strong>Catatan Penanganan:</strong></p>
                                    <p class="italic text-gray-600">${result.value}</p>
                                </div>
                            `,
                                    customClass: {
                                        popup: 'rounded-3xl',
                                        confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menyelesaikan',
                                    text: data.message || 'Terjadi kesalahan saat menyelesaikan panggilan',
                                    customClass: {
                                        popup: 'rounded-3xl',
                                        confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Sistem',
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

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Fungsi untuk menampilkan peta lokasi
        function showLocationMap(latitude, longitude) {
            Swal.fire({
                title: 'Lokasi Panggilan Darurat',
                html: `<div id="mapContainer" style="height: 400px; width: 100%;"></div>`,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-3xl',
                    closeButton: 'text-gray-500 hover:text-gray-800'
                },
                didOpen: () => {
                    // Inisialisasi peta menggunakan Leaflet atau Google Maps
                    const mapContainer = document.getElementById('mapContainer');
                    const map = L.map(mapContainer).setView([latitude, longitude], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    L.marker([latitude, longitude])
                        .addTo(map)
                        .bindPopup('Lokasi Panggilan Darurat')
                        .openPopup();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {

            // Tooltip untuk status dan tipe darurat
            const statusBadges = document.querySelectorAll('.badge');
            statusBadges.forEach(badge => {
                badge.addEventListener('mouseenter', (e) => {
                    const tooltip = document.createElement('div');
                    tooltip.classList.add('absolute', 'bg-black', 'text-white', 'p-2', 'rounded-lg', 'text-xs', 'z-50');
                    tooltip.textContent = 'Status ' + badge.textContent;

                    const rect = badge.getBoundingClientRect();
                    tooltip.style.top = `${rect.bottom + 5}px`;
                    tooltip.style.left = `${rect.left}px`;

                    document.body.appendChild(tooltip);

                    badge.addEventListener('mouseleave', () => {
                        document.body.removeChild(tooltip);
                    });
                });
            });
        });
    </script>
@endpush
