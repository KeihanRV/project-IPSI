<x-guest-layout>
    <h2 class="text-3xl font-bold text-center text-[#584C08] mb-8">Register</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="{{ __('Name') }}" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="{{ __('Email') }}" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-12" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/70 hover:text-white">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Register') }}
        </x-primary-button>

        <p class="text-center text-sm text-[#584C08] mt-4 font-medium">
            Already registered? 
            <a href="{{ route('login') }}" class="font-bold underline hover:text-[#D3B514]">Login here</a>
        </p>
    </form>
</x-guest-layout>