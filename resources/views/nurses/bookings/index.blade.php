@extends('nurses.layouts.nurse')

@section('page_title', 'Jadwal Layanan')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Jadwal Layanan -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Jadwal Layanan
                        </h2>
                        <p class="text-xl text-gray-600">Kelola dan pantau semua layanan Anda</p>
                    </div>
                    <div class="flex space-x-4">
                        <div class="bg-blue-100 p-4 rounded-2xl text-center">
                            <h3 class="text-2xl font-bold text-blue-600">{{ count($activeBookings) }}</h3>
                            <p class="text-sm text-blue-500">Layanan Aktif</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-2xl text-center">
                            <h3 class="text-2xl font-bold text-green-600">{{ count($upcomingServices) }}</h3>
                            <p class="text-sm text-green-500">Layanan Mendatang</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pencarian dan Filter -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="relative">
                    <input
                        type="text"
                        id="serviceSearch"
                        placeholder="Cari berdasarkan nama pasien, layanan, atau lokasi..."
                        class="w-full px-6 py-4 pl-12 rounded-full bg-gray-100 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                    />
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Grid Layanan -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Layanan Aktif -->
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-blue-600">Layanan Aktif</h2>
                        <a href="#" class="text-blue-500 hover:text-blue-700 transition">Lihat Semua</a>
                    </div>

                    <div class="space-y-6">
                        @forelse($activeBookings as $booking)
                            <div class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2"></div>

                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-xl font-bold text-blue-600">
                                            {{ $booking->user->name }}
                                        </h4>
                                        <span class="
                        @switch($booking->status)
                            @case('confirmed') bg-green-500 @break
                            @case('pending') bg-yellow-500 @break
                            @default bg-gray-500
                        @endswitch
                        text-white px-3 py-1 rounded-full text-xs"
                                        >
                        {{ ucfirst($booking->status) }}
                    </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-gray-600">
                                                <i class="fas fa-notes-medical mr-2 text-blue-500"></i>
                                                {{ $booking->service->name }}
                                            </p>
                                            <p class="text-gray-600 text-sm">
                                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                                {{ \Carbon\Carbon::parse($booking->startTime)->translatedFormat('d F Y H:i') }}
                                            </p>
                                        </div>
                                        <button
                                            onclick="showBookingDetails({{ json_encode($booking) }})"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all"
                                        >
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Tidak Ada Layanan Aktif</h3>
                                <p class="text-gray-500 mt-2">Belum ada layanan yang sedang berlangsung</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Layanan Mendatang -->
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-green-600">Layanan Mendatang</h2>
                        <a href="#" class="text-green-500 hover:text-green-700 transition">Lihat Semua</a>
                    </div>

                    <div class="space-y-6">
                        @forelse($upcomingServices as $service)
                            <div class="bg-gradient-to-br from-white to-green-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-green-500 transform rotate-45 translate-x-1/2 -translate-y-1/2"></div>

                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-xl font-bold text-green-600">
                                            {{ $service->type }}
                                        </h4>
                                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">
                                        {{ $service->status }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-gray-600">
                                                <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                                                {{ \Carbon\Carbon::parse($service->date)->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-gray-600 text-sm">
                                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                                {{ \Carbon\Carbon::parse($service->date)->format('H:i') }} WIB
                                            </p>
                                        </div>
                                        <button
                                            onclick="showServiceDetails({{ json_encode($service) }})"
                                            class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-all"
                                        >
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Tidak Ada Layanan Mendatang</h3>
                                <p class="text-gray-500 mt-2">Belum ada jadwal layanan yang akan datang</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Panggilan Darurat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-red-600">Panggilan Darurat</h2>
                    <a href="{{ route('nurse.emergency') }}" class="text-red-500 hover:text-red-700 transition">Lihat Semua</a>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($emergencyCalls as $call)
        <div class="bg-gradient-to-br from-white to-red-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-500 transform rotate-45 translate-x-1/2 -translate-y-1/2"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-bold text-red-600">
                        {{ $call->type }}
                    </h4>
                    <span class="
                    @switch($call->status)
                        @case('Resolved') bg-green-500
                        @break
                        @default bg-red-500
                    @endswitch
                    text-white px-3 py-1 rounded-full text-xs"
                    >
                    {{ $call->status }}
                </span>
                </div>

                <div class="space-y-2">
                    <p class="text-gray-600">
                        <i class="fas fa-calendar mr-2 text-red-500"></i>
                        {{ \Carbon\Carbon::parse($call->date)->translatedFormat('d F Y') }}
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                        {{ $call->location }}
                    </p>
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        onclick="showEmergencyCallDetails({{ json_encode($call) }})"
                        class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all"
                    >
                        Detail
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-8">
            <i class="fas fa-ambulance text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700">Tidak Ada Panggilan Darurat</h3>
            <p class="text-gray-500 mt-2">Tidak ada panggilan darurat saat ini</p>
        </div>
    @endforelse
</div>
</div>
</div>

        <!-- Modal Detail Panggilan Darurat -->
        <div id="serviceDetailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                <button
                    onclick="closeModal('serviceDetailModal')"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <div id="serviceDetailModalContent">
                </div>
            </div>
        </div>
</div>
@endsection

@push('scripts')
<script>

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
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Detail Layanan</h3>
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <p class="mb-4">
                        <span class="font-semibold">Status:</span>
                        <span class="${getBookingStatusClass(booking.status)} px-3 py-1 rounded-full text-xs font-semibold">
                            ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                        </span>
                    </p>
                    <div class="space-y-2">
                        <p>
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                            <span class="font-semibold">Lokasi:</span> ${booking.location || 'Tidak ditentukan'}
                        </p>
                        <p>
                            <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>
                            <span class="font-semibold">Catatan Tambahan:</span>
                            <span class="italic text-gray-600">${booking.notes || 'Tidak ada catatan'}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button
                onclick="closeModal('serviceDetailModal')"
                class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all"
            >
                Tutup
            </button>
        </div>
    `;

    openModal('serviceDetailModal');
}

function showServiceDetails(service) {
    const modalContent = document.getElementById('serviceDetailModalContent');

    modalContent.innerHTML = `
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-teal-600 mb-4">
                    Detail Layanan Mendatang
                </h2>
                <p class="text-gray-600">Informasi lengkap tentang layanan yang akan datang</p>
            </div>

            <div class="bg-gray-50 p-8 rounded-3xl">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">Informasi Layanan</h3>
                        <div class="space-y-3">
                            <p>
                                <i class="fas fa-notes-medical text-green-500 mr-2"></i>
                                <span class="font-semibold">Tipe Layanan:</span> ${service.type}
                            </p>
                            <p>
                                <i class="fas fa-calendar text-green-500 mr-2"></i>
                                <span class="font-semibold">Tanggal:</span> ${formatDateTime(service.date)}
                            </p>
                            <p>
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="font-semibold">Status:</span>
                                <span class="${getServiceStatusClass(service.status)} px-3 py-1 rounded-full text-xs font-semibold">
                                    ${service.status}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">Informasi Tambahan</h3>
                        <div class="space-y-3">
                            <p>
                                <i class="fas fa-info-circle text-green-500 mr-2"></i>
                                <span class="font-semibold">Deskripsi:</span>
                                <span class="italic text-gray-600">${service.description || 'Tidak ada deskripsi tambahan'}</span>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                <span class="font-semibold">Lokasi:</span> ${service.location || 'Tidak ditentukan'}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button
                        onclick="closeModal('serviceDetailModal')"
                        class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-all"
                    >
                        Tutup
                    </button>
                </div>
            </div>
            `;

    openModal('serviceDetailModal');
}

function showEmergencyCallDetails(call) {
    const modalContent = document.getElementById('serviceDetailModalContent');

    modalContent.innerHTML = `
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-red-400 mb-4">
                Detail Panggilan Darurat
            </h2>
            <p class="text-gray-600">Informasi lengkap tentang panggilan darurat</p>
        </div>

        <div class="bg-gray-50 p-8 rounded-3xl">
            <div class="space-y-4">
                <p>
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <span class="font-semibold">Tipe Panggilan:</span> ${call.type}
                </p>
                <p>
                    <i class="fas fa-calendar-alt text-red-500 mr-2"></i>
                    <span class="font-semibold">Tanggal:</span> ${formatDateTime(call.date)}
                </p>
                <p>
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                    <span class="font-semibold">Lokasi:</span> ${call.location}
                </p>
                <p>
                    <i class="fas fa-info-circle text-red-500 mr-2"></i>
                    <span class="font-semibold">Catatan:</span> ${call.notes || 'Tidak ada catatan'}
                </p>
                <div id="map" style="height: 300px; width: 100%;"></div>
            </div>

            <div class="mt-8 flex justify-end">
                <button
                    onclick="closeModal('serviceDetailModal')"
                    class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all"
                >
                    Tutup
                </button>
            </div>
        </div>
    `;

    // Inisialisasi peta menggunakan Leaflet
    initMap(call.latitude, call.longitude);

    openModal('serviceDetailModal');
}

function initMap(latitude, longitude) {
    var map = L.map('map').setView([51.505, -0.09], 13);

    // Menggunakan OpenStreetMap sebagai layer peta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Menambahkan marker untuk lokasi
    L.marker([latitude, longitude]).addTo(map)
        .openPopup();
}

function formatDateTime(dateTime) {
    return new Date(dateTime).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function getBookingStatusClass(status) {
    switch (status) {
        case 'confirmed':
            return 'bg-green-500';
        case 'pending':
            return 'bg-yellow-500';
        default:
            return 'bg-gray-500';
    }
}

function getServiceStatusClass(status) {
    return status === 'active' ? 'bg-green-500' : 'bg-red-500';
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    modal.classList.add('flex', 'opacity-100');

    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
        modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'opacity-100');
    }, 300);
}
</script>
@endpush
