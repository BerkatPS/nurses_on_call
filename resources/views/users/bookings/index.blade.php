@extends('users.layouts.user')

@section('page_title', 'Jadwal Layanan')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Jadwal Layanan -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center booking-header">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4 booking-header-title">
                            Jadwal Layanan
                        </h2>
                        <p class="text-xl text-gray-600 booking-header-subtitle">Kelola dan pantau layanan medis Anda</p>
                    </div>
                    <button
                        onclick="showServiceModal()"
                        class="flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-3 rounded-full hover:scale-105 transition-all shadow-xl hover:shadow-2xl"
                    >
                        <i class="fas fa-plus-circle"></i>
                        <span>Buat Jadwal</span>
                    </button>
                </div>
            </div>

            <!-- Filter Status -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex space-x-4 overflow-x-auto pb-4 justify-center">
                    @php
                        $statusOptions = [
                            'all' => ['label' => 'Semua', 'icon' => 'fas fa-list'],
                            'confirmed' => ['label' => 'Dikonfirmasi', 'icon' => 'fas fa-check-circle'],
                            'pending' => ['label' => 'Pending', 'icon' => 'fas fa-clock'],
                            'completed' => ['label' => 'Selesai', 'icon' => 'fas fa-check-double'],
                        ];
                    @endphp
                    @foreach($statusOptions as $status => $option)
                        <button
                            class="filter-button flex items-center space-x-2 px-5 py-3 rounded-full transition-all duration-300
                            {{ $statusFilter === $status ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                            onclick="filterBookings('{{ $status }}')"
                        >
                            <i class="{{ $option['icon'] }}"></i>
                            <span>{{ $option['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Daftar Booking -->
            <div class="grid md:grid-cols-1 gap-6 booking-card-grid">
                @forelse($bookings as $booking)
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 transform hover:scale-[1.02] transition-all duration-300 relative overflow-hidden booking-card">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2 opacity-20"></div>

                        <div class="relative z-10 grid md:grid-cols-3 gap-6 items-center">
                            <!-- Informasi Layanan -->
                            <div>
                                <h3 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-2">
                                    {{ $booking->service->name }}
                                </h3>
                                <p class="text-gray-600">
                                    <i class="fas fa-tag mr-2 text-blue-500"></i>
                                    {{ $booking->service->type }}
                                </p>
                            </div>

                            <!-- Informasi Waktu -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <!-- Informasi Perawat -->
                            <div class="flex items-center">
                                <img
                                    src="{{ $booking->nurse->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($booking->nurse->user->name) . '&background=0D8ABC&color=fff' }}"
                                    alt="{{ $booking->nurse->name }}"
                                    class="w-16 h-16 rounded-full mr-4 object-cover border-2 border-blue-500"
                                />
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $booking->nurse->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $booking->nurse->specialization }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status dan Aksi -->
                        <div class="mt-6 flex justify-between items-center border-t border-gray-200 pt-4 booking-card-actions">
                            <div>
                                <span
                                    class="px-4 py-2 rounded-full text-xs font-semibold
                                    {{ $booking->status === 'confirmed' ? 'bg-green-500' :
                                       ($booking->status === 'pending' ? 'bg-yellow-500' :
                                       ($booking->status === 'completed' ? 'bg-blue-500' : 'bg-red-500')) }}
                                    text-white"
                                >
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <span class="ml-2 text-gray-600 text-sm">
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    onclick="viewBookingDetails({{ $booking->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all"
                                >
                                    Detail
                                </button>
                                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                    <button
                                        onclick="cancelBooking('{{ $booking->id }}')"
                                        class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all"
                                    >
                                        Batalkan
                                    </button>
                                @endif
                                @if($booking->status === 'completed' && !$booking->review)
                                    <button
                                        onclick="showReviewModal({{ $booking->id }}, '{{ $booking->nurse->user->name }}', '{{ $booking->service->name }}')"
                                        class="px-4 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition-all"
                                    >
                                        Beri Review
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 text-center">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">Tidak Ada Jadwal Layanan</h3>
                        <p class="text-gray-500">Anda belum memiliki jadwal layanan</p>
                        <button
                            onclick="showServiceModal()"
                            class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full hover:scale-105 transition-all"
                        >
                            Buat Layanan Baru
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal Pilih Layanan -->
        <div id="serviceModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-4xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0 shadow-lg">
                <button
                    onclick="closeModal('serviceModal')"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <h2 class="text-3xl font-bold text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6">
                    Pilih Layanan
                </h2>

                <!-- Kategori Layanan -->
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-4">Pilih Kategori Layanan</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button class="category-button bg-red-500 text-white rounded-lg p-4 hover:bg-red-600 transition-all flex flex-col items-center" onclick="filterServices('emergency')">
                            <i class="fas fa-ambulance text-3xl mb-2"></i>
                            <span>Panggilan Darurat</span>
                        </button>
                        <button class="category-button bg-blue-500 text-white rounded-lg p-4 hover:bg-blue-600 transition-all flex flex-col items-center" onclick="filterServices('homecare')">
                            <i class="fas fa-home text-3xl mb-2"></i>
                            <span>Perawatan Rumah</span>
                        </button>
                        <button class="category-button bg-green-500 text-white rounded-lg p-4 hover:bg-green-600 transition-all flex flex-col items-center" onclick="filterServices('checkup')">
                            <i class="fas fa-stethoscope text-3xl mb-2"></i>
                            <span>Pemeriksaan Kesehatan</span>
                        </button>
                        <button class="category-button bg-yellow-500 text-white rounded-lg p-4 hover:bg-yellow-600 transition-all flex flex-col items-center" onclick="filterServices('general')">
                            <i class="fas fa-user-md text-3xl mb-2"></i>
                            <span>Penyakit Umum</span>
                        </button>
                    </div>
                </div>

                <!-- Daftar Layanan Berdasarkan Kategori -->
                <div id="serviceList" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white rounded-3xl p-6 text-center shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 relative overflow-hidden service-item" data-category="{{ $service->type }}">
                            <div class="flex justify-center mb-4">
                                <i class="{{ $service->icon }} text-5xl text-blue-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-blue-600 font-semibold">
                                    Rp {{ number_format($service->base_price, 0, ',', '.') }}
                                </span>
                                <button
                                    onclick="selectService('{{ $service->id }}')"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all duration-300 transform hover:scale-105"
                                >
                                    Pilih
                                </button>
                            </div>
                            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2 opacity-10"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <style>
        .category-button {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .service-item.hidden {
            display: none;
        }
    </style>
@endsection

@push('scripts')
    <script>

        function filterServices(category) {
            const serviceItems = document.querySelectorAll('.service-item');
            serviceItems.forEach(item => {
                if (item.getAttribute('data-category') === category || category === 'all') {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        function showAllServices() {
            const serviceItems = document.querySelectorAll('.service-item');
            serviceItems.forEach(item => {
                item.classList.remove('hidden');
            });
        }


        function showServiceModal() {
            const modal = document.getElementById('serviceModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            showAllServices();
            const modalContent = modal.querySelector('.bg-white');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function showReviewModal(bookingId, nurseName, serviceName) {
            const modalHtml = `
    <div id="reviewModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
            <!-- Gradient Header -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-yellow-400 to-orange-500"></div>

            <!-- Close Button -->
            <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Review Content -->
            <div class="p-8 pt-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600">
                        Berikan Review
                    </h2>
                    <p class="text-gray-600 mt-2">
                        Layanan dengan Perawat <b><i>${nurseName}</i></b>
                    </p>
                </div>

                <form id="reviewForm" class="space-y-6">
                    <input type="hidden" name="booking_id" value="${bookingId}">
                    <input type="hidden" name="service_name" value="${serviceName}">

                    <!-- Star Rating -->
                    <div class="rating-container text-center mb-6">
                        <div class="star-rating flex justify-center space-x-2">
                            ${[5, 4, 3, 2, 1].map(star => `
                                <input
                                    type="radio"
                                    id="star${star}"
                                    name="rating"
                                    value="${star}"
                                    class="hidden"
                                />
                                <label
                                    for="star${star}"
                                    class="text-4xl cursor-pointer transition-all duration-300 star-icon text-gray-300 hover:text-yellow-500"
                                >
                                    ★
                                </label>
                            `).join('')}
                        </div>
                        <p id="ratingText" class="mt-2 text-gray-600">Pilih Rating</p>
                    </div>

                    <!-- Comment Textarea -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentar Review
                        </label>
                        <textarea
                            name="comment"
                            rows="4"
                            class="w-full p-2 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition-all"
                            placeholder="Ceritakan pengalaman Anda dengan layanan ini..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button
                            type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-full hover:scale-105 transition-all shadow-lg hover:shadow-xl"
                        >
                            Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .star-rating input:checked ~ label,
        .star-rating input:checked + label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #fbbf24;
        }
    </style>
    `;

            // Tambahkan modal ke body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Animasi modal
            const modal = document.getElementById('reviewModal');
            const modalContent = modal.querySelector('.bg-white');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);

            // Event listener untuk star rating
            const stars = document.querySelectorAll('.star-rating label');
            const ratingText = document.getElementById('ratingText');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.previousElementSibling.value;
                    const ratingTexts = [
                        'Sangat Buruk',
                        'Buruk',
                        'Cukup',
                        'Baik',
                        'Sangat Baik'
                    ];
                    ratingText.textContent = ratingTexts[5 - rating];
                });
            });

            // Submit review
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('/user/reviews', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Review Berhasil',
                                text: 'Terima kasih atas review Anda',
                                customClass: {
                                    popup: 'rounded-3xl'
                                }
                            }).then(() => {
                                closeReviewModal();
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengirim Review',
                                text: data.message || 'Terjadi kesalahan',
                                customClass: {
                                    popup: 'rounded-3xl'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Sistem',
                            text: 'Terjadi masalah saat mengirim review',
                            customClass: {
                                popup: 'rounded-3xl'
                            }
                        });
                    });
            });
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            const modalContent = modal.querySelector('.bg-white');

            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.remove();
            }, 300);
        }



        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = modal.querySelector('.bg-white');
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }


        function viewBookingDetails(bookingId) {
            fetch(`/user/bookings/${bookingId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;

                        const modalHtml = `
                <div id="bookingDetailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
                    <div class="bg-white/90 rounded-3xl max-w-4xl w-full p-0 relative overflow-hidden shadow-2xl border border-blue-100 transform transition-all duration-300 scale-95 opacity-0 animate-modal-pop">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                        <button onclick="closeModal('bookingDetailModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 z-10 bg-white/50 rounded-full p-2 hover:bg-white/80 transition-all">
                            <i class="fas fa-times text-2xl"></i>
                        </button>

                        <div class="grid md:grid-cols-3 gap-0 h-[600px]">
                            <!-- Background Gradient Section -->
                            <div class="md:col-span-1 bg-gradient-to-br from-blue-500 to-indigo-600 relative overflow-hidden flex items-center justify-center">
                                <div class="absolute inset-0 opacity-20 bg-pattern"></div>
                                <div class="text-center z-10 px-6">
                                    <img
                                        src="${data.nurse.avatar_url}"
                                        alt="${data.nurse.user.name}"
                                        class="w-64 h-64 rounded-full mx-auto mb-6 object-cover border-8 border-white/30 shadow-2xl transform hover:scale-105 transition-all"
                                    >
                                    <h3 class="text-3xl font-bold text-white mb-2">${data.nurse.user.name}</h3>
                                    <p class="text-white/80 text-lg">${data.nurse.specializations}</p>
                                    <div class="mt-6 flex justify-center space-x-3">
                                        <span class="bg-white/20 text-white px-4 py-2 rounded-full">
                                            <i class="fas fa-star mr-2 text-yellow-300"></i>
                                            ${data.nurse.rating || 'N/A'}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details Section -->
                            <div class="md:col-span-2 p-8 overflow-y-auto bg-white/90">
                                <div class="mb-8">
                                    <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 mb-2">
                                        ${data.service.name}
                                    </h2>
                                    <p class="text-gray-500 text-lg">${data.service.type} Service</p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="bg-blue-50 p-4 rounded-xl shadow-sm">
                                            <h4 class="text-sm font-semibold text-blue-600 mb-2">Waktu Layanan</h4>
                                            <p class="font-medium">
                                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                                ${data.formatted_start_time}
                                            </p>
                                            <p class="font-medium">
                                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                                Berakhir: ${data.formatted_end_time}
                                            </p>
                                        </div>

                                        <div class="bg-green-50 p-4 rounded-xl shadow-sm">
                                            <h4 class="text-sm font-semibold text-green-600 mb-2">Lokasi</h4>
                                            <p class="font-medium">
                                                <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>
                                                ${data.location}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="bg-purple-50 p-4 rounded-xl shadow-sm">
                                            <h4 class="text-sm font-semibold text-purple-600 mb-2">Status & Biaya</h4>
                                            <p class="font-medium mb-2">
                                                <span class="px-3 py-1 rounded-full ${getStatusClass(data.status)} text-sm">
                                                    ${data.status.toUpperCase()}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="bg-yellow-50 p-4 rounded-xl shadow-sm">
                                            <h4 class="text-sm font-semibold text-yellow-600 mb-2">Catatan</h4>
                                            <p class="italic text-gray-600">
                                                ${data.notes || 'Tidak ada catatan tambahan'}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .bg-pattern {
                        background-image:
                            linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.1) 75%, transparent 75%, transparent);
                        background-size: 50px 50px;
                    }

                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }

                    @keyframes modalPop {
                        from {
                            transform: scale(0.95);
                            opacity: 0;
                        }
                        to {
                            transform: scale(1);
                            opacity: 1;
                        }
                    }

                    .animate-fade-in {
                        animation: fadeIn 0.3s ease-out;
                    }

                    .animate-modal-pop {
                        animation: modalPop 0.3s ease-out forwards;
                    }
                </style>`;

                        // Hapus modal sebelumnya jika ada
                        const existingModal = document.getElementById('bookingDetailModal');
                        if (existingModal) {
                            existingModal.remove();
                        }

                        // Tambahkan modal baru
                        document.body.insertAdjacentHTML('beforeend', modalHtml);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: result.message || 'Tidak dapat mengambil detail booking'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching booking details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: 'Terjadi masalah saat mengambil detail booking'
                    });
                });
        }


        //filterBookings
        function filterBookings(status) {
            const bookingItems = document.querySelectorAll('.booking-item');
            bookingItems.forEach(item => {
                if (item.getAttribute('data-status') === status || status === 'all') {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        function selectService(serviceId) {
            fetch(`/user/nurse?service_id=${serviceId}`)
                .then(response => response.json())
                .then(nurses => {
                    const nursesHtml = nurses.map(nurse => `
                <div class="nurse-card group flex items-center p-4 border rounded-lg mb-2 transition-all duration-300 hover:shadow-lg">
                    <input type="radio" name="nurse_id" value="${nurse.id}" class="hidden peer" id="nurse-${nurse.id}" required>
                    <label for="nurse-${nurse.id}" class="cursor-pointer flex items-center w-full">
                        <img src="${nurse.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(nurse.user.name)}" class="w-16 h-16 rounded-full mr-4 border-2 border-blue-500 shadow-md">
                        <div>
                            <h4 class="font-bold text-gray-800">${nurse.user.name}</h4>
                            <p class="text-gray-600">${nurse.specializations}</p>
                        </div>
                    </label>
                </div>
            `).join('');

                    const modalHtml = `
                <div id="bookingFormModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-3xl max-w-lg w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0 shadow-lg">
                        <button onclick="closeModal('bookingFormModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                        <h2 class="text-3xl font-bold mb-4 text-center text-blue-600">Buat Jadwal Booking</h2>
                        <form id="bookingForm" class="space-y-6">
                            <input type="hidden" name="service_id" value="${serviceId}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Perawat</label>
                                <div class="max-h-64 overflow-y-auto">
                                     ${nursesHtml}
                                </div>
                            </div>
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                                <input type="datetime-local" name="start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                                <input type="text" name="location" required placeholder="Masukkan alamat lengkap" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                                <textarea name="notes" rows="3" placeholder="Tambahkan informasi tambahan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            <div>
                                <label for="emergency_level" class="block text-sm font-medium text-gray-700">Tingkat Darurat</label>
                                <select name="emergency_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="1">Rendah</option>
                                    <option value="2">Sedang</option>
                                    <option value="3">Tinggi</option>
                                    <option value="4">Darurat</option>
                                    <option value="5">Kritis</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full py-2 hover:bg-blue-600 transition-all">
                                Buat Booking
                            </button>
                        </form>
                    </div>
                </div>`;

                    // Hapus modal sebelumnya jika ada
                    const existingModal = document.getElementById('bookingFormModal');
                    if (existingModal) {
                        existingModal.remove();
                    }

                    // Tambahkan modal baru
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                    const modal = document.getElementById('bookingFormModal');
                    const modalContent = modal.querySelector('.bg-white');

                    // Animasi modal
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 50);

                    // Handle form submission
                    document.getElementById('bookingForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(this);

                        fetch('/user/bookings', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Booking Berhasil',
                                        text: data.message,
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Membuat Booking',
                                        text: data.message || 'Terjadi kesalahan',
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kesalahan Sistem',
                                    text: 'Terjadi kesalahan saat membuat booking',
                                });
                            });
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Perawat',
                        text: 'Tidak dapat mengambil daftar perawat',
                    });
                });
        }

        function cancelBooking(bookingId) {
            Swal.fire({
                title: 'Batalkan Booking',
                text: "Apakah Anda yakin ingin membatalkan booking ini?",
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
                    fetch(`/user/bookings/${bookingId}/cancel`, {
                        method: 'PUT',
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
                                    title: 'Booking Dibatalkan',
                                    text: data.message,
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
                                    text: data.message || 'Tidak dapat membatalkan booking',
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
                                text: 'Terjadi kesalahan saat membatalkan booking',
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        });
                }
            });
        }

        // Fungsi utilitas
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit '
            });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        function getStatusClass(status) {
            const statusClasses = {
                'pending': 'bg-yellow-500 text-white',
                'confirmed': 'bg-blue-500 text-white',
                'completed': 'bg-green-500 text-white',
                'cancelled': 'bg-red-500 text-white'
            };
            return statusClasses[status] || 'bg-gray-500 text-white';
        }
    </script>
@endpush
