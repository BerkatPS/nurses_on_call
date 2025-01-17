@extends('nurses.layouts.nurse')

@section('page_title', 'Panggilan Darurat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            {{-- Header --}}
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <h2 class="text-4xl font-extrabold text-blue-600 mb-4">Panggilan Darurat</h2>
                <p class="text-xl text-gray-600">Pantau dan kelola situasi darurat dengan cepat dan efisien</p>
            </div>

            {{-- Statistik Panggilan --}}
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $statistikPanggilan = [
                        ['label' => 'Total Panggilan', 'value' => $emergencySummary['total'] ?? 0, 'icon' => 'fas fa-ambulance', 'color' => 'from-blue-500 to-blue-600'],
                        ['label' => 'Menunggu Tanggapan', 'value' => $emergencySummary['pending'] ?? 0, 'icon' => 'fas fa-clock', 'color' => 'from-yellow-500 to-yellow-600'],
                        ['label' => 'Panggilan Medis', 'value' => $emergencySummary['medical'] ?? 0, 'icon' => 'fas fa-notes-medical', 'color' => 'from-red-500 to-red-600']
                    ];
                @endphp

                @foreach($statistikPanggilan as $stat)
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20">
                        <div class="bg-gradient-to-br {{ $stat['color'] }} p-6 text-white relative">
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

            {{-- Filter dan Pencarian --}}
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 <form action="{{ route('nurse.emergency') }}" method="GET" class="flex space-x-4">
            <select name="status" class="w-1/3 px-4 py-2 border rounded-xl focus:ring focus:ring-blue-200">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Ditanggapi</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
            </select>

            <input type="text" name="search" placeholder="Cari berdasarkan nama atau lokasi"
                   value="{{ request('search') }}"
                   class="w-2/3 px-4 py-2 border rounded-xl focus:ring focus:ring-blue-200">

            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            </form>
        </div>

        {{-- Daftar Panggilan Darurat --}}
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
            @if(count($emergencyCalls) == 0)
                <div class="text-center py-16">
                    <i class="fas fa-ambulance text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Tidak Ada Panggilan Darurat</h3>
                    <p class="text-gray-500">Belum ada panggilan darurat yang tersedia</p>
                </div>
            @else
                <div class="grid md:grid-cols-1 gap-6">
                    @foreach($emergencyCalls as $call)
                        <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer">
                            <div class="p-6 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2 opacity-10"></div>
                                <div class="relative z-10 flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-blue-600 mb-2">Panggilan Darurat #{{ $call->id }}</h3>
                                        <div class="flex items-center gap-3 {{ $call->status == 'pending' ? 'text-yellow-500' : ($call->status == 'responded' ? 'text-green-500' : 'text-red-500') }}">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($call->user->name) }}"
                                                 alt="{{ $call->user->name }}"
                                                 class="w-12 h-12 rounded-full border-2 border-blue-500" />
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $call->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $call->user->phone }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2
                                            {{ $call->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                               ($call->status == 'responded' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                        <i class="{{ $call->status == 'pending' ? 'fas fa-clock' :
                                                       ($call->status == 'responded' ? 'fas fa-check-circle' : 'fas fa-check-double') }}"></i>
                                        {{ ucfirst($call->status) }}
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl mb-4">
                                    <p class="text-gray-600 italic">{{ $call->description }}</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-red-500 mr- 2"></i>
                                            <span class="text-gray-600">{{ $call->location }}</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        @if($call->status === 'pending')
                                            <button onclick="openEmergencyCallDetails({{ json_encode($call) }})"
                                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all">
                                                Detail
                                            </button>
                                            <button onclick="respondToEmergencyCall('{{ $call->id }}')"
                                                    class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-all">
                                                Tanggapi
                                            </button>
                                        @elseif($call->status === 'responded')
                                            <button onclick="openEmergencyCallDetails({{ json_encode($call) }})"
                                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all">
                                                Detail
                                            </button>
                                            <button onclick="completeEmergencyCall('{{ $call->id }}')"
                                                    class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-all">
                                                Selesaikan
                                            </button>
                                        @elseif($call->status === 'resolved')
                                            <button onclick="openEmergencyCallDetails({{ json_encode($call) }})"
                                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all">
                                                Detail
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
    <div id="emergencyCallModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
            <button onclick="closeEmergencyCallModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
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
        function respondToEmergencyCall(callId) {
            Swal.fire({
                title: 'Konfirmasi Tanggapi Panggilan',
                text: 'Apakah Anda yakin ingin menanggapi panggilan darurat ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tanggapi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfToken) {
                        Swal.fire('Error', 'Token CSRF tidak ditemukan', 'error');
                        return;
                    }

                    fetch(`/nurse/emergency/${callId}/respond`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(error => {
                                    throw new Error(error.message || 'Terjadi kesalahan');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Operasi gagal', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', error.message || 'Terjadi kesalahan', 'error');
                        });
                }
            });
        }

        function completeEmergencyCall(callId) {
            Swal.fire({
                title: 'Konfirmasi Selesaikan Panggilan',
                text: 'Apakah Anda yakin ingin menyelesaikan panggilan darurat ini?',
                input: 'textarea',
                inputPlaceholder: 'Catatan penyelesaian (opsional)',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Selesaikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfToken) {
                        Swal.fire('Error', 'Token CSRF tidak ditemukan', 'error');
                        return;
                    }

                    fetch(`/nurse/emergency/${callId}/complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            notes: result.value || ''
                        })
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(error => {
                                    throw new Error(error.message || 'Terjadi kesalahan');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Operasi gagal', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', error.message || 'Terjadi kesalahan', 'error');
                        });
                }
            });
        }

        function closeEmergencyCallModal() {
            const modal = document.getElementById('emergencyCallModal');
            const modalContent = document.getElementById('emergencyCallModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
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

        function getEmergencyTypeClass(type) {
            const typeMap = {
                medical: { text: 'Medis', color: 'bg-red-100 text-red-800' },
                accident: { text: 'Kecelakaan', color: 'bg-yellow-100 text-yellow-800' },
                other: { text: 'Lainnya', color: 'bg-blue-100 text-blue-800' }
            };
            return typeMap[type] || { text: 'Tidak Diketahui', color: 'bg-gray-100 text-gray-800' };
        }

        function getStatusClass(status) {
            const statusMap = {
                pending: { text: 'Menunggu', color: 'bg-yellow-100 text-yellow-800' },
                responded: { text: 'Ditanggapi', color: 'bg-green-100 text-green-800' },
                resolved: { text: 'Selesai', color: 'bg-blue-100 text-blue-800' }
            };
            return statusMap[status] || { text: 'Status Tidak Diketahui', color: 'bg-gray-100 text-gray-800' };
        }

        function openEmergencyCallDetails(call) {
            const modal = document.getElementById('emergencyCallModal');
            const modalContent = document.getElementById('emergencyCallModalContent');

            modalContent.innerHTML = `
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                        Detail Panggilan Darurat #${call.id}
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
                                    ${call.latitude && call.longitude ? `
                                <p>
                                    <i class="fas fa-map-pin text-green-500 mr-2"></i>
                                    <span class="font-semibold">Koordinat:</span>
                                    <a href="https://www.google.com/maps?q=${call.latitude}, ${call.longitude}" target="_blank" class="text-blue-600 hover:underline">
                                        ${call.latitude}, ${call.longitude}
                                    </a>
                                </p>` : ''}
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

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }
    </script>
@endpush
