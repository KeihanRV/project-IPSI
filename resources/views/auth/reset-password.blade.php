<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-[#584C08] mb-6">Reset Password</h2>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="{{ __('Email') }}" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-12" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/70 hover:text-white">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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
            {{ __('Reset Password') }}
        </x-primary-button>
    </form>
</x-guest-layout>