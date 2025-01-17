@extends('nurses.layouts.nurse')

@section('page_title', 'Layanan Aktif')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                    Layanan Aktif
                </h2>
                <p class="text-xl text-gray-600">Kelola dan pantau semua layanan aktif Anda</p>
            </div>

            <!-- Booking Grid -->
            <div class="grid md:grid-cols-1 gap-6">
                @forelse($activeBookings as $booking)
                    <div class="booking-card bg-white rounded-3xl shadow-lg p-6 hover:shadow-xl transition-all">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl font-bold text-blue-600">
                                {{ $booking->service->name }} #{{ $booking->id }}
                            </h3>
                            <span class="px-3 py-1 rounded-full
                            {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600"><strong>Pasien:</strong> {{ $booking->user->name }}</p>
                                <p class="text-gray-600"><strong>Telepon:</strong> {{ $booking->user->phone }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600">
                                    <strong>Waktu:</strong>
                                    {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d F Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-3">
                            <button onclick="showBookingDetails({{ $booking->id }})"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">
                                Detail
                            </button>

                            @if($booking->status != 'completed')
                                <div class="dropdown">
                                    <button class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition">
                                        Ubah Status
                                    </button>
                                    <div class="dropdown-content">
                                        <a href="#" onclick="updateBookingStatus({{ $booking->id }}, 'in_progress')">Sedang Berlangsung</a>
                                        <a href="#" onclick="updateBookingStatus({{ $booking->id }}, 'completed')">Selesai</a>
                                        <a href="#" onclick="updateBookingStatus({{ $booking->id }}, 'cancelled')">Batalkan</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-3xl shadow-lg">
                        <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Tidak Ada Layanan Aktif</h3>
                        <p class="text-gray-500">Belum ada layanan yang sedang berlangsung</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal Detail Booking -->
        <div id="bookingDetailModal" class="modal">
            <!-- Modal content akan diisi secara dinamis -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showBookingDetails(bookingId) {
            // Implementasi ajax untuk mendapatkan detail ```javascript
            fetch(`/nurse/bookings/${bookingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modalContent = document.getElementById('bookingDetailModal');
                        modalContent.innerHTML = `
                        <div class="bg-white rounded-3xl p-8">
                            <h2 class="text-3xl font-bold mb-4">Detail Booking #${data.booking.id}</h2>
                            <p><strong>Nama Pasien:</strong> ${data.booking.user.name}</p>
                            <p><strong>Telepon:</strong> ${data.booking.user.phone}</p>
                            <p><strong>Service:</strong> ${data.booking.service.name}</p>
                            <p><strong>Status:</strong> ${data.booking.status}</p>
                            <p><strong>Waktu:</strong> ${new Date(data.booking.start_time).toLocaleString('id-ID')}</p>
                            <div class="mt-4 flex justify-end">
                                <button onclick="closeModal('bookingDetailModal')" class="px-4 py-2 bg-blue-500 text-white rounded-full">Tutup</button>
                            </div>
                        </div>
                    `;
                        openModal('bookingDetailModal');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error fetching booking details:', error));
        }

        function updateBookingStatus(bookingId, status) {
            fetch(`/nurse/bookings/${bookingId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to see updated status
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error updating booking status:', error));
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'opacity-100');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'opacity-100');
        }
    </script>
@endpush
