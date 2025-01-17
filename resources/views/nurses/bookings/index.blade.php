@extends('nurses.layouts.nurse')

@section('page_title', 'Jadwal Layanan')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Jadwal Layanan -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                    Jadwal Layanan
                </h2>
                <p class="text-xl text-gray-600">Kelola dan pantau semua layanan Anda dengan mudah</p>
            </div>

            <!-- Statistik Ringkasan -->
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 text-center">
                    <i class="fas fa-calendar-check text-4xl text-blue-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($activeBookings) }}</h3>
                    <p class="text-gray-600">Layanan Aktif</p>
                </div>
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 text-center">
                    <i class="fas fa-calendar-alt text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($upcomingServices) }}</h3>
                    <p class="text-gray-600">Layanan Mendatang</p>
                </div>
            </div>

            <!-- Daftar Layanan Aktif -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <h2 class="text-3xl font-bold text-blue-600 mb-4">Layanan Aktif</h2>
                <div class="space-y-6">
                    @forelse($activeBookings as $booking)
                        <div class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xl font-bold text-blue-600">
                                    {{ $booking->service->name }} #{{ $booking->id }}
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
                                    <p class="text-gray-600"><strong>Pasien:</strong> {{ $booking->user->name }}</p>
                                    <p class="text-gray-600"><strong>Telepon:</strong> {{ $booking->user->phone }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        onclick="showBookingDetails({{ json_encode($booking) }})"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
                                    >
                                        Detail
                                    </button>
                                    @if($booking->status === 'pending')
                                        <button
                                            onclick="updateBookingStatus({{ $booking->id }}, 'confirmed')"
                                            class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                                        >
                                            Terima
                                        </button>
                                    @elseif($booking->status === 'confirmed')
                                        <button
                                            onclick="showCompleteModal({{ json_encode($booking) }})"
                                            class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                                        >
                                            Selesaikan
                                        </button>
                                    @elseif($booking->status === 'completed')
                                        <button onclick="showUploadProofModal({{ $booking->id }})"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
                                        >
                                            Upload Bukti
                                        </button>
                                    @endif
                                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                        <button
                                            onclick="cancelBooking({{ $booking->id }})"
                                            class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition"
                                        >
                                            Batalkan
                                        </button>
                                    @endif
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

            <!-- Daftar Layanan Mendatang -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 mt-8">
                <h2 class="text-3xl font-bold text-green-600 mb-4">Layanan Mendatang</h2>
                <div class="space-y-6">
                    @forelse($upcomingServices as $service)
                        <div class="bg-gradient-to-br from-white to-green-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
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
                                    <p class="text-gray-600"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($service->date)->translatedFormat('d F Y') }}</p>
                                    <p class="text-gray-600"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($service->date)->format('H:i') }} WIB</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="completeUpcomingService({{ $service->id }})" class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition">Selesaikan</button>
                                    <button
                                        onclick="showServiceDetails({{ json_encode($service) }})"
                                        class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                                    >
                                        Detail
                                    </button>
                                    <button
                                        onclick="cancelBooking({{ $service->id }})"
                                        class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition"
                                    >
                                        Batalkan
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

            <!-- Modal Upload Bukti -->
            <div id="uploadProofModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div class="bg-white rounded-3xl max-w-lg w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                    <button
                        onclick="closeModal('uploadProofModal')"
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                    >
                        <i class="fas fa-times text-2xl"></i>
                    </button>

                    <h2 class="text-3xl font-bold text-center mb-4">Upload Bukti</h2>
                    <input type="hidden" id="uploadBookingId" value="">
                    <form id="uploadProofForm" enctype="multipart/form-data" method="POST">
                        <div class="mb-4">
                            <label for="proof" class="block text-gray-700">Pilih File Bukti:</label>
                            <input type="file" id="proof" name="proof" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition">Upload</button>
                            <button type="button" onclick="closeModal('uploadProofModal')" class="ml-2 px-4 py-2 bg-gray-300 text-gray-800 rounded-full hover:bg-gray-400 transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Layanan Selesai -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 mt-8">
                <h2 class="text-3xl font-bold text-gray-600 mb-4">Layanan Selesai</h2>
                <div class="space-y-6">
                    @forelse($completedBookings as $booking)
                        <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-3xl shadow-lg hover:shadow-2xl transition relative overflow-hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xl font-bold text-gray-600">
                                    {{ $booking->service->name }} #{{ $booking->id }}
                                </h4>
                                <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600"><strong>Pasien:</strong> {{ $booking->user->name }}</p>
                                    <p class="text-gray-600"><strong>Telepon:</strong> {{ $booking->user->phone }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        onclick="showBookingDetails({{ json_encode($booking) }})"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
                                    >
                                        Detail
                                    </button>
                                    @if (empty($booking->file_path) || is_null($booking->file_path))
                                        <button
                                            onclick="showUploadProofModal({{ $booking->id }})"
                                            class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                                        >
                                            Upload Bukti
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700">Tidak Ada Layanan Selesai</h3>
                            <p class="text-gray-500 mt-2">Belum ada layanan yang telah selesai</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Layanan -->
    <div id="serviceDetailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
            <button
                onclick="closeModal('serviceDetailModal')"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
            >
                <i class="fas fa-times text-2xl"></i>
            </button>

            <div id="serviceDetailModalContent"></div>
        </div>
    </div>

    <!-- Modal Selesaikan Layanan -->
    <div id="completeModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
            <button
                onclick="closeModal('completeModal')"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
            >
                <i class="fas fa-times text-2xl"></i>
            </button>

            <h2 class="text-3xl font-bold text-center mb-4">Selesaikan Layanan</h2>
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menyelesaikan layanan ini?</p>
            <div class="flex justify-end">
                <button
                    id="confirmCompleteButton"
                    class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                >
                    Selesaikan
                </button>
                <button
                    onclick="closeModal('completeModal')"
                    class="ml-2 px-4 py-2 bg-gray-300 text-gray-800 rounded-full hover:bg-gray-400 transition"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function completeUpcomingService(serviceId) {
            fetch(`/nurse/bookings/${serviceId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to see the updated booking
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function showUploadProofModal(bookingId) {
            document.getElementById('uploadBookingId').value = bookingId; // Set booking ID
            openModal('uploadProofModal'); // Open the modal
        }

        document.getElementById('uploadProofForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(this);
            const bookingId = document.getElementById('uploadBookingId').value;

            fetch(`/nurse/bookings/${bookingId}/upload-proof`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    // Periksa apakah respons adalah JSON
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text); // Tangkap kesalahan jika respons tidak ok
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to see the updated booking
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message); // Tampilkan pesan kesalahan
                });
        });

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
                </button>`;

            openModal('serviceDetailModal');
        }

        function showServiceDetails(service) {
            const modalContent = document.getElementById('serviceDetailModalContent');

            modalContent.innerHTML = `
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-emerald-600 mb-4">
                    Detail Layanan Mendatang
                </h2>
                <p class="text-gray-600">Informasi lengkap tentang layanan yang akan datang</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xl font-semibold text-green-600 mb-4">Informasi Layanan</h3>
                    <div class="bg-gray-50 p-6 rounded-2xl">
                        <div class="space-y-2">
                            <p>
                                <i class="fas fa-notes-medical text-green-500 mr-2"></i>
                                <span class="font-semibold">Tipe Layanan:</span> ${service.type}
                            </p>
                            <p>
                                <i class="fas fa-calendar text-green-500 mr-2"></i>
                                <span class="font-semibold">Tanggal:</span> ${formatDateTime(service.date)}
                            </p>
                            <p>
                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                <span class="font-semibold">Status:</span>
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    ${service.status}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-green-600 mb-4">Detail Tambahan</h3>
                    <div class="bg-gray-50 p-6 rounded-2xl">
                        <div class="space-y-2">
                            <p>
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                <span class="font-semibold">Lokasi:</span> ${service.location || 'Tidak ditentukan'}
                            </p>
                            <p>
                                <i class="fas fa-clipboard-list text-green-500 mr-2"></i>
                                <span class="font-semibold">Catatan:</span>
                                <span class="italic text-gray-600">${service.notes || 'Tidak ada catatan'}</span>
                            </p>
                        </div>
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
        `;

            openModal('serviceDetailModal');
        }

        function updateBookingStatus(bookingId, status) {
            fetch(`/nurse/bookings/${bookingId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
                .then(response => {
                    // Periksa apakah respons adalah JSON
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text); // Tangkap kesalahan jika respons tidak ok
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to see the updated status
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message); // Tampilkan pesan kesalahan
                });
        }

        function showCompleteModal(booking) {
            const confirmCompleteButton = document.getElementById('confirmCompleteButton');
            confirmCompleteButton.onclick = function() {
                updateBookingStatus(booking.id, 'completed');
                closeModal('completeModal');
            };
            openModal('completeModal');
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
                case 'completed':
                    return 'bg-gray-500';
                default:
                    return 'bg-gray-500';
            }
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'opacity-100');

            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
                modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
            }, 200);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'opacity-100');
            }, 100);
        }

        function cancelBooking(bookingId) {
            if (confirm('Apakah Anda yakin ingin membatalkan layanan ini?')) {
                fetch(`/nurse/bookings/${bookingId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
@endpush
