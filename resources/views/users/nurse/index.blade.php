@extends('users.layouts.user')

@section('page_title', 'Daftar Perawat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Daftar Perawat -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Daftar Perawat
                        </h2>
                        <p class="text-xl text-gray-600">Pantau  semua perawat yang tersedia</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Perawat -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($nurses as $nurse)
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-white/20 transform hover:scale-[1.02] transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500 transform rotate-45 translate-x-1/2 -translate-y-1/2 opacity-20"></div>

                        <div class="relative z-10">
                            <div class="flex items-center mb-4">
                                <img src="{{ $nurse->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($nurse->user->name) }}" alt="{{ $nurse->user->name }}" class="w-16 h-16 rounded-full border-4 border-blue-500 mr-4" />
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">{{ $nurse->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $nurse->specialization }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="text-gray-600">
                                    <i class="fas fa-phone-alt mr-2 text-blue-500"></i>
                                    {{ $nurse->user->phone }}
                                </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                                    @php
                                        // Mengonversi string JSON menjadi array
                                        $certifications = json_decode($nurse->certifications, true);
                                    @endphp

                                    @if($certifications && count($certifications) > 0)
                                        <span>Sertifikasi:</span>
                                <ul class="list-disc list-inside">
                                    @foreach($certifications as $cert)
                                        <li>{{ $cert }}</li>
                                    @endforeach
                                </ul>
                                @else
                                    <span>Tidak ada sertifikasi yang tersedia.</span>
                                    @endif
                                </p>
                                    <p class="text-gray-600">
                                        <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                                        @if ($nurse->availability_status == 'available')
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-800 bg-green-200 rounded-full">
            {{ strtoupper($nurse->availability_status) }}
        </span>
                                        @elseif ($nurse->availability_status == 'on-call')
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-white bg-gray-800 rounded-full">
            {{ strtoupper($nurse->availability_status) }}

        </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-red-800 bg-red-200 rounded-full">
            {{ strtoupper($nurse->availability_status) }}
                                        @endif
                                    </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                    Bergabung sejak: {{ $nurse->created_at->format('d M Y') }}
                                </p>
                            </div>

{{--                            <div class="mt-4 flex justify-end">--}}
{{--                                <button onclick="viewNurseDetails({{ $nurse->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all">--}}
{{--                                    Detail--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modal Detail Perawat -->
        <div id="nurseDetailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
            <div class=" bg-white rounded-3xl max-w-md w-full p-8 relative transform transition-all duration-300 scale-95 opacity-0">
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
            // Ambil detail perawat dari server
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

                        // Animasi modal
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
