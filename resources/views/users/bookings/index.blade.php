@extends('users.layouts.user')

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
                        <p class="text-xl text-gray-600">Kelola dan pantau layanan medis Anda</p>
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
            <div class="grid md:grid-cols-1 gap-6">
                @forelse($bookings as $booking)
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 transform hover:scale-[1.02] transition-all duration-300 relative overflow-hidden">
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
                        <div class="mt-6 flex justify-between items-center border-t border-gray-200 pt-4">
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
            <div class="bg-white rounded-3xl max-w-4xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                <button
                    onclick="closeModal('serviceModal')"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6 text-center">
                    Pilih Layanan
                </h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white rounded-3xl p-6 text-center shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex justify-center mb-4">
                                <i class="{{ $service->icon }} text-5xl text-blue-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                            <div class="flex justify-between items-center">
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
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function filterBookings(status) {
            window.location.href = "{{ route('user.bookings') }}?status=" + status;
        }

        function showServiceModal() {
            const modal = document.getElementById('serviceModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            const modalContent = modal.querySelector('.bg-white');

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
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
                .then(data => {
                    // Tampilkan detail booking di modal
                    const modalContent = document.getElementById('bookingDetailModalContent');
                    modalContent.innerHTML = `
                        <h3 class="text-xl font-bold">${data.service.name}</h3>
                        <p><strong>Perawat:</strong> ${data.nurse.user.name}</p>
                        <p><strong>Waktu:</strong> ${formatDate(data.start_time)}</p>
                        <p><strong>Lokasi:</strong> ${data.location}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Catatan:</strong> ${data.notes}</p>
                    `;
                    // Tampilkan modal
                    const modal = document.getElementById('bookingDetailModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                })
                .catch(error => {
                    console.error('Error fetching booking details:', error);
                });
        }

        function selectService(serviceId) {
            fetch(`/user/nurse?service_id=${serviceId}`)
                .then(response => response.json())
                .then(nurses => {
                    console.log(nurses);
                    const nursesHtml = nurses.map(nurse => `
                        <div class="flex items-center p-4 border rounded-lg mb-2">
                            <input type="radio" name="nurse_id" value="${nurse.id}" class="mr-4" required>
                            <img src="${nurse.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(nurse.user.name)}" class="w-16 h-16 rounded-full mr-4">
                            <div>
                                <h4 class="font-bold">${nurse.name}</h4>
                                <p class="text-gray-600">${nurse.specializations}</p>
                            </div>
                        </div>
                    `).join('');

                    const modalHtml = `
                        <div id="bookingFormModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                            <div class="bg-white rounded-3xl max-w-lg w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                                <button onclick="closeModal('bookingFormModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                                <h2 class="text-2xl font-bold mb-4">Buat Jadwal Booking</h2>
                                <form id="bookingForm">
                                    <input type="hidden" name="service_id" value="${serviceId}">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Perawat</label>
                                        <div class="max-h-64 overflow-y-auto">
                                            ${nursesHtml}
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                                        <input type="datetime-local" name="start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="mb-4">
                                        <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                                        <input type="text" name="location" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="mb-4">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                                        <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="emergency_level" class="block text-sm font-medium text-gray-700">Tingkat Darurat</label>
                                        <select name="emergency_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                            <option value="1">Rendah</option>
                                            <option value="2">Sedang</option>
                                            <option value="3">Tinggi</option>
                                            <option value="4">Darurat</option>
                                            <option value="5">Kritis</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 text-white rounded-full py-2 hover:bg-blue-600 transition-all">
                                        Buat Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;

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
