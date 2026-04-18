<header class="border-b border-gray-200 py-4">
    <div class="container mx-auto px-4 max-w-6xl flex items-center justify-between gap-4">

        <a href="/" class="text-3xl font-semibold text-brand tracking-wide">
            SHOO
        </a>

        <div class="flex items-center gap-3 ml-auto">

            @guest
                {{-- Tampilkan Sign Up & Sign In jika belum login --}}
                <x-button type="outline" label="Sign Up" href="{{ route('register') }}"
                    class="hidden md:block rounded-full px-5" />
                <x-button type="secondary" label="Sign In" href="{{ route('login') }}"
                    class="hidden md:block rounded-full px-5" />
            @else
                {{-- CEK APAKAH USER ADALAH ADMIN --}}
                @if(auth()->user()->status == 'admin')
                    <x-button type="secondary" label="Dashboard" href="{{ route('dashboard') }}"
                        class="hidden md:block rounded-full px-6" />
                @endif
            @endguest

            <div class="ml-2">
                <x-search-bar />
            </div>

            <a href="{{ route('cart.index') }}" title="Keranjang Belanja"
                class="relative w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-shopping-cart"></i>
                @php
                    $cartService = new \App\Services\CartService();
                    $count = $cartService->count();
                @endphp
                @if($count > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        {{ $count > 99 ? '99+' : $count }}
                    </span>
                @endif
            </a>

            @auth
                {{-- Tombol Profil --}}
                <a href="{{ route('profile.edit') }}" title="Profil Saya"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-brand text-brand hover:bg-gray-50 transition overflow-hidden">
                    
                    @if(auth()->user()->profile_picture)
                        {{-- Jika user sudah upload foto --}}
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Foto Profil" class="w-full h-full object-cover" />
                    @else
                        {{-- Jika belum ada foto, tampilkan inisial nama --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=5a4a0a&color=ffffff" alt="Foto Profil" class="w-full h-full object-cover" />
                    @endif

                </a>

                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" title="Log Out" class="text-gray-400 hover:text-red-500 transition ml-1">
                        <i class="fas fa-power-off"></i>
                    </button>
                </form>
            @endauth

        </div>
    </div>
</header>