<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3 row">
            <div class="col-sm-4">
                <x-input-label for="email" :value="__('Email')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="email" class="bg-light rounded-1" :invalid="$errors->has('email')"
                              type="email" name="email"
                              :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="my-1" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-3 row">
            <div class="col-sm-4">
                <x-input-label for="password" :value="__('Password')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="password" class="bg-light rounded-1" :invalid="$errors->has('password')"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="my-1" />
            </div>
        </div>

        <!-- Remember Me -->
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="remember" value="" id="remember_me">
            <label class="form-check-label fw-bold" for="remember_me">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            @if (Route::has('password.request'))
                <a class="link-primary" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3 px-5 fw-bold text-light">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
