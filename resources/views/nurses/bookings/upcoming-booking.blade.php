@extends('nurses.layouts.nurse')

@section('page_title', 'Layanan Mendatang')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                    Layanan Mendatang
                </h2>
                <p class="text-xl text-gray-600">Kelola dan pantau semua layanan mendatang Anda</p>
            </div>

            <!-- Booking Grid -->
            <div class="grid md:grid-cols-1 gap-6">
                @forelse($upcomingServices as $service)
                    <div class="bg-white rounded-3xl shadow-lg p-6">
                        <h3 class="text-2xl font-bold text-blue-600 mb-2">Layanan #{{ $service->id }}</h3>
                        <p class="text-gray-600"><strong>Service:</strong> {{ $service->type }}</p>
                        <p class="text-gray-600"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($service->date)->translatedFormat('d F Y H:i') }}</p>
                        <p class="text-gray-600"><strong>Lokasi:</strong> {{ $service->location }}</p>
                        <div class="flex justify-end mt-4">
                            <button onclick="showBookingDetails({{ $service->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all">
                                Detail
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-3xl shadow-lg">
                        <i class=" fas fa-calendar-alt text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Tidak Ada Layanan Mendatang</h3>
                        <p class="text-gray-500">Belum ada layanan yang dijadwalkan</p>
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
            // Implementasi ajax untuk mendapatkan detail
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
