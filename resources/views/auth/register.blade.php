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
            {{ __('Register') }}
        </x-primary-button>

        <p class="text-center text-sm text-[#584C08] mt-4 font-medium">
            Already registered? 
            <a href="{{ route('login') }}" class="font-bold underline hover:text-[#D3B514]">Login here</a>
        </p>
    </form>
</x-guest-layout>