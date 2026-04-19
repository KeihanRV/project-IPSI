<x-guest-layout>
    <h2 class="text-3xl font-bold text-center text-[#584C08] mb-6">Login</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="email" value="Username" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Admin_Primary" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="password" value="Password" />
            <div class="relative">
                <x-text-input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required placeholder="**********" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/50">
                    </button>
            </div>
        </div>

        <div class="flex items-center justify-between mt-2 px-1">
            <label class="flex items-center">
                <input type="checkbox" class="rounded border-[#584C08] text-[#584C08] scale-75">
                <span class="ml-1 text-[10px] text-[#584C08]">{{ __('Remember me') }}</span>
            </label>
            <a class="text-[10px] font-bold text-[#584C08] underline" href="{{ route('password.request') }}">Forgot?</a>
        </div>
        
        <x-primary-button>Login</x-primary-button>
            <p class="text-center text-sm text-[#584C08] mt-4 font-medium">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-bold underline hover:text-[#D3B514]">Register here</a>
            </p>    
    </form>
</x-guest-layout>