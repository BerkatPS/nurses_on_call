@extends('users.layouts.user')

@section('page_title', 'Pengaturan Profil')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6 lg:p-12">
        <div class="container mx-auto space-y-8">
            <!-- Header Pengaturan Profil -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-4">
                            Pengaturan Profil
                        </h2>
                        <p class="text-xl text-gray-600">Kelola dan perbarui informasi pribadi Anda</p>
                    </div>
                </div>
            </div>

            <!-- Konten Utama -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Kartu Profil -->
                <div class="md:col-span-1">
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300 text-center">
                        <div class="relative inline-block mb-6">
                            <div class="relative">
                                <img
                                    src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff' }}"
                                    alt="Avatar"
                                    id="avatarPreview"
                                    class="w-48 h-48 rounded-full object-cover mx-auto shadow-2xl border-4 border-white/30 transform transition-all duration-300 hover:scale-105"
                                />
                                <label
                                    class="absolute bottom-0 right-0 bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-3 rounded-full cursor-pointer hover:scale-110 transition-all duration-300 shadow-xl"
                                >
                                    <i class="fas fa-camera"></i>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                        id="avatarUpload"
                                        name="avatar"
                                    />
                                </label>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                                {{ $user->name }}
                            </h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="flex justify-center space-x-4 mt-4">
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">
                                    {{ $user->role ?? 'Pengguna' }}
                                </span>
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Profil -->
                <div class="md:col-span-2 space-y-8">
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                        <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-6">
                            Informasi Profil
                        </h3>

                        <form id="profileForm" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ $user->name }}"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray- 300 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ $user->email }}"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="text"
                                            name="phone"
                                            value="{{ $user->phone }}"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <div class="relative">
                                        <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="text"
                                            name="address"
                                            value="{{ $user->address }}"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <div class="relative">
                                        <i class="fas fa-calendar-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="date"
                                            name="birth_date"
                                            value="{{ $user->birth_date }}"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <select name="gender" class="w-full pl-4 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-3 rounded-full hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-xl"
                                >
                                    Perbarui Profil
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Ubah Password -->
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 transform hover:scale-[1.02] transition-all duration-300">
                        <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-teal-600 mb-6">
                            Ubah Password
                        </h3>

                        <form id="passwordForm" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                                    <div class="relative">
                                        <i class="fas fa -lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="password"
                                            name="current_password"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-green-500 transition-all duration-300"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="password"
                                            name="new_password"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-green-500 transition-all duration-300"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input
                                            type="password"
                                            name="new_password_confirmation"
                                            class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-green-500 transition-all duration-300"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-8 py-3 rounded-full hover:from-green-600 hover:to-teal-700 transition-all duration-300 transform hover:scale-105 shadow-xl"
                                >
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk mengunggah avatar
        document.getElementById('avatarUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Validasi tipe dan ukuran file
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tipe File Tidak Valid',
                        text: 'Harap unggah file gambar (JPEG, PNG, atau GIF)',
                        customClass: {
                            popup: 'rounded-3xl',
                            confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                        }
                    });
                    return;
                }

                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran gambar maksimal 5MB',
                        customClass: {
                            popup: 'rounded-3xl',
                            confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                        }
                    });
                    return;
                }

                // Preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    preview.src = e.target.result;
                    preview.classList.add('animate-pulse');

                    setTimeout(() => {
                        preview.classList.remove('animate-pulse');
                    }, 1000);
                };
                reader.readAsDataURL(file);

                // Kirim avatar ke server
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('user.profile.uploadAvatar') }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Avatar Berhasil Diperbarui',
                                text: 'Foto profil Anda telah diperbarui',
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengunggah Avatar',
                                text: data.message || 'Terjadi kesalahan saat mengunggah foto',
                                customClass: {
                                    popup: 'rounded-3xl',
                                    confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Sistem',
                            text: 'Terjadi kesalahan saat mengunggah foto',
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                            }
                        });
                    });
            }
        });

        // Validasi form profil
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);

            fetch('{{ route('user.profile.update') }}', {
                method: 'PUT',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profil Berhasil Diperbarui',
                            text: 'Informasi profil Anda telah diperbarui',
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                            }
                        }).then(() => {
                            // Update nama dan email di halaman
                            document.querySelector('.text-3xl.font-bold').textContent = data.user.name;
                            document.querySelector('.text-gray-600').textContent = data.user.email;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memperbarui Profil',
                            text: data.message || 'Terjadi kesalahan saat memperbarui profil',
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                            }
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: 'Terjadi kesalahan saat memperbarui profil',
                        customClass: {
                            popup: 'rounded-3xl',
                            confirmButton: 'bg-blue-500 text-white px-6 py-2 rounded-full'
                        }
                    });
                });
        });

        // Validasi form ubah password
        document.getElementById('passwordForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);

            fetch('{{ route('user.profile.changePassword') }}', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Berhasil Diubah',
                            text: 'Password Anda telah berhasil diperbarui',
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                            }
                        }).then(() => {
                            // Reset form
                            event.target.reset();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengubah Password',
                            text: data.message || 'Terjadi kesalahan saat mengubah password',
                            customClass: {
                                popup: 'rounded-3xl',
                                confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                            }
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: 'Terjadi kesalahan saat mengubah password',
                        customClass: {
                            popup: 'rounded-3xl',
                            confirmButton: 'bg-green-500 text-white px-6 py-2 rounded-full'
                        }
                    });
                });
        });
    </script>

    <style>
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .animate-pulse {
            animation: pulse 1s ease-in-out;
        }
    </style>
@endpush
