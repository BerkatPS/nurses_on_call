{{-- users/reviews/index.blade.php --}}
@extends('users.layouts.user')

@section('page_title', 'Review Layanan')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Review Modern -->
            <div class="relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl opacity-75 blur-lg"></div>
                <div class="relative bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 overflow-hidden">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div>
                            <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-2">
                                Riwayat Review
                            </h2>
                            <p class="text-xl text-gray-600">Kelola dan lihat pengalaman layanan Anda</p>
                        </div>
                        <button
                            id="showCreateReviewModalBtn"
                            class="group relative inline-flex items-center justify-center overflow-hidden rounded-full p-0.5 bg-gradient-to-br from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 transition-all duration-300"
                        >

                        </button>
                    </div>
                </div>
            </div>

            <!-- Review Grid -->
            <div class="grid md:grid-cols-1 gap-6">
                @forelse($reviews as $review)
                    <div class="group perspective-1000">
                        <div class="relative transform transition-all duration-500 ease-in-out
                    group-hover:rotate-y-10
                    bg-white/80 backdrop-blur-lg
                    rounded-3xl shadow-2xl
                    p-6 border border-white/20
                    hover:shadow-3xl
                    transform hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-100 opacity-0 group-hover:opacity-20 rounded-3xl transition-all duration-500"></div>

                            <div class="relative z-10 space-y-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                                        {{ $review->booking->service->name }}
                                    </h3>
                                    <div class="flex items-center space-x-1 text-yellow-500">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-user mr-2 text-blue-500"></i>
                                            {{ $review->user->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                            {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="border-t border-blue-100 pt-4">
                                    <p class="text-gray-700 italic">
                                        "{{ $review->comment }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/80 backdrop-blur -lg rounded-3xl shadow-2xl p-8 text-center">
                        <div class="flex flex-col items-center justify-center space-y-4">
                            <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-6xl text-blue-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-600 mb-2">Tidak Ada Review</h3>
                            <p class="text-gray-500 max-w-md">
                                Anda belum memberikan review untuk layanan apapun. Bagikan pengalaman Anda setelah menerima layanan!
                            </p>

                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal Buat Review -->
        <div id="createReviewModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl max-w-md w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0 border border-white/20 shadow-2xl">
                <button
                    id="closeCreateReviewModalBtn"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <h2 class="text-3xl font-bold text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6">
                    Buat Review
                </h2>

                <form id="reviewForm" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layanan</label>
                        <select
                            name="booking_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"
                            required
                        >
                            <option value="">Pilih Layanan yang Akan Direview</option>
                            @foreach($reviewableBookings as $booking)
                                <option value="{{ $booking->id }}" data-service="{{ $booking->service->name }}">
                                    {{ $booking->service->name }} - {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <select
                            name="rating"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"
                            required
                        >
                            <option value="">Pilih Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Star</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Komentar</label>
                        <textarea
                            name="comment"
                            rows="4"
                            placeholder="Tulis komentar Anda di sini..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition-all"
                    >
                        Kirim Review
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Fungsi untuk menambahkan event listener dengan safety check
        function safeAddEventListener(selector, eventType, handler) {
            const element = document.querySelector(selector);
            if (element) {
                element.addEventListener(eventType, handler);
            } else {
                console.warn(`Element ${selector} tidak ditemukan`);
            }
        }

        // Fungsi modal dengan error handling yang lebih baik
        function createModalHandler() {
            // Cari semua elemen modal dengan lebih robust
            const modal = document.getElementById('createReviewModal');
            const modalContent = modal ? modal.querySelector('.bg-white') : null;

            // Fungsi tampilkan modal
            function showModal() {
                if (!modal || !modalContent) {
                    console.error('Modal atau konten modal tidak ditemukan');

                    // Fallback: coba cari ulang elemen
                    const fallbackModal = document.getElementById('createReviewModal');
                    const fallbackModalContent = fallbackModal ? fallbackModal.querySelector('.bg-white') : null;

                    if (fallbackModal && fallbackModalContent) {
                        fallbackModal.classList.remove('hidden');
                        fallbackModal.classList.add('flex');

                        requestAnimationFrame(() => {
                            fallbackModalContent.classList.remove('scale-95', 'opacity-0');
                            fallbackModalContent.classList.add('scale-100', 'opacity-100');
                        });
                    } else {
                        // Peringatan terakhir jika tidak bisa menemukan modal
                        alert('Tidak dapat membuka modal. Silakan refresh halaman.');
                    }
                    return;
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                requestAnimationFrame(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                });
            }

            // Fungsi tutup modal
            function closeModal() {
                if (!modal || !modalContent) {
                    console.error('Modal atau konten modal tidak ditemukan saat menutup');
                    return;
                }

                modalContent.classList.add('scale-95', 'opacity-0');
                modalContent.classList.remove('scale-100', 'opacity-100');

                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 300);
            }

            return {
                showModal,
                closeModal
            };
        }

        // Inisialisasi modal handler
        document.addEventListener('DOMContentLoaded', function() {
            const modalHandler = createModalHandler();

            // Tambahkan event listener dengan safety
            safeAddEventListener('#showCreateReviewModalBtn', 'click', modalHandler.showModal);
            safeAddEventListener('#closeCreateReviewModalBtn', 'click', modalHandler.closeModal);
            safeAddEventListener('#emptyStateCreateReviewBtn', 'click', modalHandler.showModal);

            // Handler submit form review
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(event.target);

                    fetch('{{ route('user.reviews.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Terjadi kesalahan jaringan');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Review Berhasil!',
                                    text: data.message,
                                    customClass: {
                                        popup: 'rounded-3xl',
                                        confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                                    }
                                }).then(() => {
                                    modalHandler.closeModal();
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menambahkan Review',
                                    text: data.message || 'Terjadi kesalahan',
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
                                text: error.message || 'Terjadi kesalahan. Silakan coba lagi.',
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        });
                });
            }
        });

        // Tambahkan debug logging
        window.addEventListener('error', function(event) {
            console.error('Unhandled error:', event.error);
        });
    </script>
@endpush

<style>
    /* Tambahkan CSS kustom untuk efek 3D dan transisi halus */
    @keyframes floatAnimation {
        0% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0); }
    }
</style>
