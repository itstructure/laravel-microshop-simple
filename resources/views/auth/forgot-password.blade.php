<x-guest-layout>
    <div class="mb-4 text-dark">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="row">
            <div class="col-sm-4">
                <x-input-label for="email" :value="__('Email')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="email" class="bg-light rounded-1" :invalid="$errors->has('email')"
                              type="email" name="email"
                              :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3">
            <x-primary-button class="px-3 fw-bold text-light">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
