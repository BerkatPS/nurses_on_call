@extends('nurses.layouts.nurse')

@section('page_title', 'Profil Perawat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Konten Utama -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Kartu Profil Utama -->
                <div class="md:col-span-1">
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300 text-center">
                        <div class="relative mb-6">
                            <div class="relative inline-block z-10">
                                <img
                                    src="{{ $nurseProfile->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($nurseProfile->user->name) . '&background=0D8ABC&color=fff' }}"
                                    alt="Foto Profil"
                                    class="w-48 h-48 rounded-full object-cover mx-auto shadow-2xl border-4 border-white/30 transform transition-all duration-300 hover:scale-105"
                                />
                                <div class="absolute bottom-0 right-0 px-3 py-1 rounded-full text-xs {{ $availabilityColor }} shadow-md">
                                    {{ ucfirst($nurseProfile->availabilityStatus) }}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                                {{ $nurseProfile->user->name }}
                            </h2>
                            <p class="text-gray-600 mb-2">{{ implode(', ', $nurseProfile->specializations) }}</p>

                            <div class="flex justify-center items-center gap-2 mb-4">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-xl {{ $i < floor($nurseProfile->rating) ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="text-sm text-gray-600 ml-2">({{ number_format($nurseProfile->rating, 1) }})</span>
                            </div>

                            <div class="flex justify-center gap-4">
                                <button class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-full hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-xl">
                                    Edit Profil
                                </button>
                                <button class="bg-white text-gray-700 px-6 py-3 rounded-full hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-xl">
                                    Bagikan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="md:col-span-2 space-y-8 ">
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                        <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6">
                            Informasi Pribadi
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach([
                                ['label' => 'Email', 'value' => $nurseProfile->user->email, 'icon' => 'fas fa-envelope'],
                                ['label' => 'Nomor Telepon', 'value' => $nurseProfile->user->phone, 'icon' => 'fas fa-phone'],
                                ['label' => 'Alamat', 'value' => $nurseProfile->user->address ?? 'Tidak tersedia', 'icon' => 'fas fa-map-marker-alt'],
                                ['label' => 'Lokasi Saat Ini', 'value' => $nurseProfile->currentLocation ?? 'Tidak tersedia', 'icon' => 'fas fa-map-pin']
                            ] as $info)
                                <div class="bg-gray- 50 p-4 rounded-2xl flex items-center space-x-4">
                                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                                        <i class="{{ $info['icon'] }} text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $info['label'] }}</p>
                                        <p class="font-semibold text-gray-800">{{ $info['value'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Form Pengaturan Status -->
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                        <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6">
                            Pengaturan Status
                        </h3>

                        <form id="statusForm" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Ketersediaan</label>
                                <select name="availability_status" id="availability_status" class="w-full px-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                    <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="on-call" {{ $status == 'on-call' ? 'selected' : '' }}>Sedang Dipanggil</option>
                                    <option value="offline" {{ $status == 'offline' ? 'selected' : '' }}>Tidak Tersedia</option>
                                </select>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-3 rounded-full hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-xl"
                                >
                                    Perbarui Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Keahlian dan Statistik -->
            <div class="grid md:grid-cols-3 gap-8 pt-5">
                <!-- Keahlian -->
                <div class="md:col-span-2 bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                    <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 mb-6">
                        Keahlian
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        @if($nurseProfile->skills && count($nurseProfile->skills) > 0)
                            @foreach($nurseProfile->skills as $skill)
                                <span class="bg-gradient-to-br from-purple-100 to-pink-100 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold transform transition-all hover:scale-105">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        @else
                            <div class="text-center w-full py-12 bg-gray-50 rounded-3xl">
                                <i class="fas fa-brain text-7xl text-gray-300 mb-6"></i>
                                <p class="text-2xl font-semibold text-gray-600 mb-4">Belum ada keahlian yang ditambahkan</p>
                                <button class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-3 rounded-full hover:from-purple-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-xl">
                                    Tambah Keahlian
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistik -->
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                    <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-600 mb-6">
                        Statistik
                    </h3>
                    <div class="space-y-4">
                        @foreach([
                            ['label' => 'Total Layanan', 'value' => $nurseStatistics['totalServices'], 'icon' => 'fas fa-hospital-user', 'color' => 'bg-blue-100 text-blue-800'],
                            ['label' => 'Panggilan Darurat', 'value' => $nurseStatistics['emergencyCalls'], 'icon' => 'fas fa-ambulance', 'color' => 'bg-red-100 text-red-800'],
                            ['label' => 'Home Care', 'value' => $nurseStatistics['homeCareServices'], 'icon' => 'fas fa-home', 'color' => 'bg-green-100 text-green-800'],
                            ['label' => 'Pendapatan', 'value' => $nurseStatistics['totalEarnings'], 'icon' => 'fas fa-money-bill-wave', 'color' => 'bg-purple-100 text-purple-800']
                        ] as $stat)
                            <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between transform transition-all hover:scale-105">
                                <div class="flex items-center space-x-4">
                                    <div class="{{ $stat['color'] }} p-3 rounded-full bg-opacity-50">
                                        <i class="{{ $stat['icon'] }} text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $stat['label'] }}</p>
                                        <h3 class="text-xl font-bold text-gray-800">{{ $stat['value'] }}</h3>
                                    </div>
                                </div>
                                <div class="bg-white rounded-full p-2 shadow-md">
                                    <i class="fas fa-chevron-right text-gray-500"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Animasi dan interaktivitas tambahan
        document.addEventListener('DOMContentLoaded', () => {
            // Tangani submit form status
            // Tangani submit form status
            document.getElementById('statusForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(event.target);

                fetch('{{ route('nurse.profile.updateStatus') }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Berhasil Diperbarui',
                                text: data.message,
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memperbarui Status',
                                text: data.message,
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
                            text: 'Terjadi kesalahan saat memperbarui status',
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-full'
                            }
                        });
                    });
            });
        });
    </script>
@endpush
