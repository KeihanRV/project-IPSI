<header class="border-b border-gray-200 py-4">
    <div class="container mx-auto px-4 max-w-6xl flex items-center justify-between gap-4">
        
        <a href="/" class="text-3xl font-semibold text-brand tracking-wide">
            SHOO
        </a>

        <div class="flex items-center gap-3 ml-auto">
            
            @guest
                {{-- Tampilkan Sign Up & Sign In jika belum login --}}
                <x-button type="outline" label="Sign Up" href="{{ route('register') }}" class="hidden md:block rounded-full px-5" />
                <x-button type="secondary" label="Sign In" href="{{ route('login') }}" class="hidden md:block rounded-full px-5" />
            @else
                {{-- CEK APAKAH USER ADALAH ADMIN --}}
                @if(auth()->user()->role == 'admin')
                    <x-button 
                        type="secondary" 
                        label="Dashboard" 
                        href="{{ route('admin.dashboard') }}" 
                        class="hidden md:block rounded-full px-6" 
                    />
                @endif
            @endguest
            
            <div class="ml-2">
                <x-search-bar />
            </div>
            
            <button class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-shopping-cart"></i>
            </button>

            @auth
                {{-- Tombol Profil --}}
                <a href="{{ route('profile.edit') }}" title="Profil Saya" class="w-10 h-10 flex items-center justify-center rounded-full border border-brand text-brand hover:bg-gray-50 transition">
                    <i class="fas fa-user"></i>
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