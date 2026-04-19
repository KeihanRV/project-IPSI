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

        <div class="mt-4">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-password-input id="password" class="block mt-1 w-full" name="password" required autocomplete="current-password" placeholder="**********" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-password-input id="password_confirmation" class="block mt-1 w-full" name="password_confirmation" required autocomplete="new-password" placeholder="**********" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Reset Password') }}
        </x-primary-button>
    </form>
</x-guest-layout>