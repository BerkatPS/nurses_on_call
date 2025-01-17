@extends('nurses.layouts.nurse')

@section('page_title', 'Riwayat Review')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Review -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Riwayat Review
                        </h2>
                        <p class="text-xl text-gray-600">Kelola dan lihat pengalaman layanan Anda</p>
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
                                Anda belum memiliki review untuk layanan apapun !
                            </p>

                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal Buat Review -->
        <div id="createReviewModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Buat Review</h2>
                <form id="createReviewForm">
                    <div class="mb-4">
                        <label for="booking_id" class="block text-sm font-medium text-gray-700">Booking ID</label>
                        <input type="text" name="booking_id" id="booking_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                        <select name="rating" id="rating" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required>
                            <option value="">Pilih Rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700">Komentar</label>
                        <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="closeModalBtn" class="mr-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Kirim Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('showCreateReviewModalBtn').addEventListener('click', function() {
                document.getElementById('createReviewModal').classList.remove('hidden');
            });

            document.getElementById('closeModalBtn').addEventListener('click', function() {
                document.getElementById('createReviewModal').classList.add('hidden');
            });

            document.getElementById('createReviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('/nurse/reviews', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Reload the page to see the new review
                        } else {
                            alert('Gagal menambahkan review. Silakan coba lagi.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
    @endpush
    @endsection
