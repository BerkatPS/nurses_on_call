@extends('users.layouts.user')

@section('page_title', 'Riwayat Panggilan Darurat')

@section('content')

    <div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Panggilan Darurat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-pink-600 mb-4">
                            Riwayat Panggilan Darurat
                        </h2>
                        <p class="text-xl text-gray-600">Pantau dan kelola panggilan darurat Anda</p>
                    </div>
                    <button
                        id="showCreateEmergencyCallModalBtn"
                        class="flex items-center space-x-2 bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-3 rounded-full hover:scale-105 transition-all shadow-xl"
                    >
                        <i class="fas fa-plus-circle"></i>
                        <span>Buat Panggilan Darurat</span>
                    </button>
                </div>
            </div>

            <!-- Statistik Panggilan Darurat -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 text-center">
                    <i class="fas fa-phone-alt text-4xl text-red-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($filteredCalls) }}</h3>
                    <p class="text-gray-600">Total Panggilan</p>
                </div>
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 text-center">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $filteredCalls->where('status', 'resolved')->count() }}</h3>
                    <p class="text-gray-600">Panggilan Selesai</p>
                </div>
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 text-center">
                    <i class="fas fa-clock text-4xl text-yellow-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $filteredCalls->where('status', 'pending')->count() }}</h3>
                    <p class="text-gray-600">Panggilan Pending</p>
                </div>
            </div>

            <!-- Filter Panggilan Darurat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-pink-600">Filter Panggilan</h2>
                </div>

                <div class="flex space-x-4 overflow-x-auto pb-4">
                    @foreach($callFilters as $filter)
                        <button
                            class="filter-button flex items-center space-x-2 px-5 py-3 rounded-full transition-all duration-300
                            {{ $selectedFilter === $filter['value'] ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                            onclick="filterCalls('{{ $filter['value'] }}')"
                        >
                            <i class="{{ $filter['icon'] }} text-lg"></i>
                            <span>{{ $filter['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Daftar Panggilan Darurat -->
            <div class="grid md:grid-cols-1 gap-6">
                @forelse($filteredCalls as $call)
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 transform hover:scale-[1.02] transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red-500 transform rotate-45 translate-x-1/2 -translate-y-1/2 opacity-20"></div>

                        <div class="relative z-10 grid md:grid-cols-3 gap-6 items-center">
                            <!-- Informasi Panggilan -->
                            <div class="md:col-span-2">
                                <h3 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-pink-600 mb-2">
                                    {{ $call->description }}
                                </h3>
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    {{ $call->location }}
                                </p>
                            </div>

                            <!-- Status Panggilan -->
                            <div class="flex items-center">
                    <span class="
                        px-4 py-2 rounded-full text-xs font-semibold
                        {{ $call->status === 'resolved' ? 'bg-green-500' :
                           ($call->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}
                        text-white"
                    >
                        {{ ucfirst($call->status) }}
                    </span>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                <p class="text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-red-500"></i>
                                    {{ \Carbon\Carbon::parse($call->created_at)->translatedFormat('d F Y H:i') }} WIB
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    onclick="viewEmergencyCallDetails({{ $call->id }})"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all"
                                >
                                    Detail
                                </button>
                                @if($call->status === 'pending')
                                    <button
                                        onclick="cancelEmergencyCall({{ $call->id }})"
                                        class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all"
                                    >
                                        Batalkan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 text-center">
                        <i class="fas fa-exclamation-circle text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">Tidak Ada Panggilan Darurat</h3>
                        <p class="text-gray-500">Anda belum memiliki riwayat panggilan darurat</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Modal Detail Panggilan Darurat -->
        <div id="emergencyCallDetailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                <button
                    onclick="closeModal('emergencyCallDetailModal')"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <div id="emergencyCallDetailModalContent">
                    <!-- Konten akan diisi secara dinamis -->
                </div>
            </div>
        </div>

        <!-- Modal Buat Panggilan Darurat -->
        <div
            id="createEmergencyCallModal"
            class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        >
            <div class="bg-white rounded-3xl max-w-md w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                <button
                    id="closeCreateEmergencyCallModalBtn"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <h2 class="text-3xl font-bold text-center text-red-600 mb-6">
                    Buat Panggilan Darurat
                </h2>

                <form id="emergencyCallForm" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Panggilan</label>
                        <select
                            name="type"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500"
                            required
                        >
                            <option value="">Pilih Jenis Panggilan</option>
                            @foreach($emergencyTypes as $type)
                                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input
                            type="text"
                            name="location"
                            placeholder="Masukkan lokasi lengkap"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500"
                            required
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Jelaskan situasi darurat secara detail"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red- 500"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Perawat Ditugaskan</label>
                        <select
                            name="assigned_nurse_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500"
                            required
                        >
                            <option value="">Pilih Perawat</option>
                            @foreach($nurses as $nurse)
                                <option value="{{ $nurse->id }}">{{ $nurse->user->name }} ({{ $nurse->availability_status }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 transition-all"
                    >
                        Kirim Panggilan Darurat
                    </button>
                </form>
            </div>
        </div>
    </div>
    <style>
        /* Gaya untuk modal */
        #emergencyCallDetailModal {
            backdrop-filter: blur(5px);
        }

        .modal-content {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .text-red-600 {
            color: #e3342f;
        }

        .bg-yellow-500 {
            background-color: #f6e05e;
        }

        .bg-green-500 {
            background-color: #38a169;
        }

        .bg-red-500 {
            background-color: #e3342f;
        }

        .bg-gray-500 {
            background-color: #a0aec0;
        }
    </style>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('showCreateEmergencyCallModalBtn').addEventListener('click', showCreateEmergencyCallModal);
            document.getElementById('closeCreateEmergencyCallModalBtn').addEventListener('click', closeCreateEmergencyCallModal);

            window.userLocation = {
                latitude: null,
                longitude: null
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        window.userLocation.latitude = position.coords.latitude;
                        window.userLocation.longitude = position.coords.longitude;
                        console.log('Lokasi pengguna:', window.userLocation.latitude, window.userLocation.longitude);
                    },
                    function(error) {
                        console.error('Error mendapatkan lokasi:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        });

        function showCreateEmergencyCallModal() {
            const modal = document.getElementById('createEmergencyCallModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
                modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeCreateEmergencyCallModal() {
            const modal = document.getElementById('createEmergencyCallModal');
            modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
            modal.querySelector('.bg-white').classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        document.getElementById('emergencyCallForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);

            if (window.userLocation.latitude && window.userLocation.longitude) {
                formData.append('latitude', window.userLocation.latitude);
                formData.append('longitude', window.userLocation.longitude);
            }

            fetch('{{ route('user.emergency.create') }}', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Panggilan Darurat Berhasil!',
                            text: data.message,
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                            }
                        }).then(() => {
                            closeCreateEmergencyCallModal();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Membuat Panggilan',
                            text: data.message,
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
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
        });

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
            modal.querySelector('.bg-white').classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        function viewEmergencyCallDetails(callId) {
            fetch(`/user/emergency-calls/${callId}`)
                .then(response => response.json())
                .then(data => {
                    const modalContent = document.getElementById('emergencyCallDetailModalContent');

                    modalContent.innerHTML = `
                <h3 class="text-2xl font-bold text-red-600 mb-4">${data.emergency_type}</h3>
                <p ><strong>Status:</strong> <span class="${getStatusClass(data.status)} p-2 rounded-full ">${data.status}</span></p>
                <p><strong>Lokasi:</strong> ${data.location}</p>
                <p><strong>Tanggal:</strong> ${formatDate(data.created_at)}</p>
                <p><strong>Deskripsi:</strong> ${data.description || 'Tidak ada deskripsi'}</p>
                <div id="map" style="height: 300px; margin-top: 20px;"></div>
            `;

                    // Tampilkan modal
                    const modal = document.getElementById('emergencyCallDetailModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    // Animasi modal
                    const modalInner = modal.querySelector('.bg-white');
                    setTimeout(() => {
                        modalInner.classList.remove('scale-95', 'opacity-0');
                        modalInner.classList.add('scale-100', 'opacity-100');
                    }, 10);

                    // Inisialisasi peta menggunakan Leaflet
                    initMap(data.latitude, data.longitude);
                })
                .catch(error => {
                    console.error('Error fetching emergency call details:', error);
                });
        }

        function initMap(latitude, longitude) {
            const map = L.map('map').setView([latitude, longitude], 15);

            // Menggunakan OpenStreetMap sebagai layer peta
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Menambahkan marker untuk lokasi
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup('Lokasi Panggilan Darurat')
                .openPopup();
        }


        function cancelEmergencyCall(callId) {
            Swal.fire({
                title: 'Batalkan Panggilan Darurat?',
                text: "Apakah Anda yakin ingin membatalkan panggilan darurat ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full',
                    cancelButton: 'bg-gray-300 text-gray-800 px-6 py-2 rounded-full ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/user/emergency-calls/${callId}/cancel`, {
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
                                    title: 'Dibatalkan!',
                                    text: 'Panggilan darurat telah dibatalkan',
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
                                    title: 'Gagal Membatalkan',
                                    text: data.message || 'Tidak dapat membatalkan panggilan darurat',
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
                                text: 'Terjadi kesalahan saat membatalkan panggilan',
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        });
                }
            });
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getStatusClass(status) {
            const statusClasses = {
                'pending': 'bg-yellow-500 text-white',
                'resolved': 'bg-green-500 text-white',
                'cancelled': 'bg-red-500 text-white'
            };
            return statusClasses[status] || 'bg-gray-500 text-white';
        }

        function filterCalls(status) {
            window.location.href = `?status=${status}`;
        }
    </script>
@endpush
