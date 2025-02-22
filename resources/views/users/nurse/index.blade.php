@extends('users.layouts.user')

@section('page_title', 'Daftar Perawat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Daftar Perawat -->
            <div class="relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl opacity-75 blur-lg"></div>
                <div class="relative bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 rounded-full transform translate-x-1/2 -translate-y-1/2 opacity-10"></div>

                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                                Daftar Perawat
                            </h2>
                            <p class="text-xl text-gray-600">Temukan Profesional Kesehatan Terbaik</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input
                                    type="text"
                                    placeholder="Cari Perawat..."
                                    class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-blue-500 transition-all"
                                >
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Perawat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-semibold text-gray-800">Filter Perawat</h3>
                    <div class="flex space-x-2">
                        <select class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-blue-500">
                            <option>Spesialisasi</option>
                            @php
                                $specializations = $nurses->pluck('specialization')->unique();
                            @endphp
                            @foreach($specializations as $spec)
                                <option>{{ $spec }}</option>
                            @endforeach
                        </select>
                        <select class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-blue-500">
                            <option>Status Ketersediaan</option>
                            <option>Available</option>
                            <option>On Call</option>
                            <option>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Daftar Perawat -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 nurse-grid">
                @foreach($nurses as $nurse)
                    <div class="nurse-card group relative">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl opacity-0 group-hover:opacity-75 transition-all duration-300 blur-lg"></div>

                        <div class="relative bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-6 h-full transform transition-all duration-300 group-hover:scale-[1.03]">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500 rounded-full transform translate-x-1/2 -translate-y-1/2 opacity-10"></div>

                            <div class="flex items-center mb-4">
                                <div class="relative">
                                    <img
                                        src="{{ $nurse->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($nurse->user->name) }}"
                                        alt="{{ $nurse->user->name }}"
                                        class="w-20 h-20 rounded-full border-4 border-blue-500 mr-4 object-cover transition-all group-hover:scale-110"
                                    />

                                    @if($nurse->availability_status == 'available')
                                        <span class="absolute bottom-0 right-4 w-5 h-5 bg-green-500 rounded-full border-2 border-white"></span>
                                    @endif

                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                                        {{ $nurse->user->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600">{{ $nurse->specialization }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-phone-alt mr-3 text-blue-500 w-5"></i>
                                    <span>{{ $nurse->user->phone }}</span>
                                </div>

                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-graduation-cap mr-3 text-blue-500 w-5"></i>
                                    @php
                                        $certifications = json_decode($nurse->certifications, true);
                                    @endphp
                                    <div>
                                        @if($certifications && count($certifications) > 0)
                                            <details class="cursor-pointer">
                                                <summary class="text-blue-600">
                                                    {{ count($certifications) }} Sertifikasi
                                                </summary>
                                                <ul class="list-disc list-inside text-sm mt-2">
                                                    @foreach($certifications as $cert)
                                                        <li>{{ $cert }}</li>
                                                    @endforeach
                                                </ul>
                                            </details>
                                        @else
                                            <span>Tidak ada sertifikasi</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-briefcase mr-3 text-blue-500 w-5"></i>
                                    @switch($nurse->availability_status)
                                        @case('available')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                Tersedia
                                            </span>
                                            @break
                                        @case('on-call')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                Siap Dipanggil
                                            </span>
                                            @break
                                        @default
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                Tidak Tersedia
                 </span>
                                    @endswitch
                                </div>

                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar-alt mr-3 text-blue-500 w-5"></i>
                                    <span>Bergabung sejak: {{ $nurse->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modal Detail Perawat -->
        <div id="nurseDetailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
                <button onclick="closeNurseDetailModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6 text-center">
                    Detail Perawat
                </h2>

                <div id="nurseDetailContent" class="space-y-4">
                    <!-- Konten detail perawat akan dimuat di sini -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function viewNurseDetails(nurseId) {
            fetch(`/user/nurses/${nurseId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const nurse = data.nurse;
                        const content = `
                            <h4 class="text-lg font-bold text-gray-800">${nurse.user.name}</h4>
                            <p class="text-sm text-gray-600">${nurse.specialization}</p>
                            <p class="text-gray-600"><i class="fas fa-phone-alt mr-2 text-blue-500"></i>${nurse.user.phone}</p>
                            <p class="text-gray-600"><i class="fas fa-envelope mr-2 text-blue-500"></i>${nurse.user.email}</p>
                            <p class="text-gray-600"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>${nurse.location}</p>
                            <p class="text-gray-600"><i class="fas fa-calendar-alt mr-2 text-blue-500"></i> Bergabung sejak: ${new Date(nurse.created_at).toLocaleDateString()}</p>
                        `;
                        document.getElementById('nurseDetailContent').innerHTML = content;
                        const modal = document.getElementById('nurseDetailModal');
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');

                        setTimeout(() => {
                            modal.querySelector('> div').classList.remove('scale-95', 'opacity-0');
                            modal.querySelector('> div').classList.add('scale-100', 'opacity-100');
                        }, 50);
                    }
                });
        }

        function closeNurseDetailModal() {
            const modal = document.getElementById('nurseDetailModal');
            modal.querySelector('> div').classList.add('scale-95', 'opacity-0');
            modal.querySelector('> div').classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endpush
