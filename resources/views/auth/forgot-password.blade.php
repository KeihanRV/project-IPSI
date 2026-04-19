<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-[#584C08] mb-4">Forgot Password</h2>
    
    <div class="mb-6 text-sm text-[#584C08] text-center font-medium px-2">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="{{ __('Email') }}" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Email Password Reset Link') }}
        </x-primary-button>

        <p class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm font-bold text-[#584C08] underline hover:text-[#D3B514]">Back to Login</a>
        </p>
    </form>
</x-guest-layout>