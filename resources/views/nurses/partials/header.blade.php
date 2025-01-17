<header class="bg-white/80 backdrop-blur-lg shadow-2xl py-4 px-6 flex justify-between items-center border-b border-gray-100 rounded-b-3xl relative z-40">
    <div class="flex items-center space-x-4">
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden text-gray-600 mr-4 transform transition-all hover:scale-110 hover:text-blue-500"
        >
            <i class="fas fa-bars text-2xl"></i>
        </button>

        <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
            @yield('page_title', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center space-x-6 relative z-50">
        <!-- Notifikasi -->
        <div class="relative group">
            <button class="text-gray-600 hover:text-blue-500 transition-colors duration-300 transform hover:scale-110 relative">
                <i class="fas fa-bell text-xl"></i>
                {{--                @php--}}
                {{--                    $notificationCount = auth()->user()->unreadNotifications->count();--}}
                {{--                @endphp--}}
                {{--                @if($notificationCount > 0)--}}
                {{--                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 animate-pulse">--}}
                {{--                        {{ $notificationCount }}--}}
                {{--                    </span>--}}
                {{--                @endif--}}
            </button>
            <div class="hidden group-hover:block absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl z-[100] border border-gray-100">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">Notifikasi</h4>
                        <span class="text-sm text-blue-500 cursor-pointer hover:underline">
                            Tandai Semua Dibaca
                        </span>
                    </div>
                    <div class="space-y-3">
                        {{--                        @forelse(auth()->user()->unreadNotifications->take(3) as $notification)--}}
                        {{--                            <div class="bg-blue-50 p-4 rounded-xl flex items-center space-x-4">--}}
                        {{--                                <div class="bg-blue-100 text-blue-600 p-3 rounded-full">--}}
                        {{--                                    <i class="fas fa-bell"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <div>--}}
                        {{--                                    <p class="text-sm font-semibold text-gray-800">--}}
                        {{--                                        {{ $notification->data['title'] ?? 'Notifikasi Baru' }}--}}
                        {{--                                    </p>--}}
                        {{--                                    <p class="text-xs text-gray-600">--}}
                        {{--                                        {{ $notification->data['message'] ?? 'Anda memiliki notifikasi baru' }}--}}
                        {{--                                    </p>--}}
                        {{--                                    <p class="text-xs text-gray-500 mt-1">--}}
                        {{--                                        {{ $notification->created_at->diffForHumans() }}--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        @empty--}}
                        {{--                            <div class="text-center text-gray-500 py-4">--}}
                        {{--                                Tidak ada notifikasi baru--}}
                        {{--                            </div>--}}
                        {{--                        @endforelse--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Profil Dropdown -->
        <div
            x-data="{ open: false }"
            @click.outside="open = false"
            class="relative z-50"
        >
            <button
                @click="open = !open"
                class="flex items-center space-x-3 bg-gray-100 hover:bg-gray-200 rounded-full px-4 py-2 transition-all duration-300 transform hover:scale-105 relative z-50"
            >
                <div class="relative">
                    <img
                        src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0D8ABC&color=fff' }}"
                        alt="Profile"
                        class="w-10 h-10 rounded-full border-2 border-white shadow-md object-cover"
                    >
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                </div>
                <div class="text-left hidden md:block">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ Str::limit(auth()->user()->name, 15) }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>
                <i class="fas fa-chevron-down text-gray-600"></i>
            </button>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl z-[100] border border-gray-100 overflow-hidden"
            >
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center space-x-3 mb-4">
                        <img
                            src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0D8ABC&color=fff' }}"
                            alt="Profile"
                            class="w-12 h-12 rounded-full border-2 border-white shadow-md object-cover"
                        >
                        <div>
                            <h4 class="font-bold text-gray-800">
                                {{ auth()->user()->name }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="py-2">
                    <a
                        href="{{ route('user.profile') }}"
                        class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors"
                    >
                        <i class="fas fa-user mr-3 text-blue-500"></i>
                        Profil
                    </a>

                    <hr class="my-2 border-gray-200">
                    <a
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition-colors"
                    >
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
