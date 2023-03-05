<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3 row">
            <div class="col-sm-4">
                <x-input-label for="name" :value="__('Name')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="name" class="bg-light rounded-1"  :invalid="$errors->has('name')"
                              type="text" name="name"
                              :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="my-1" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="my-3 row">
            <div class="col-sm-4">
                <x-input-label for="email" :value="__('Email')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="email" class="bg-light rounded-1" :invalid="$errors->has('email')"
                              type="email" name="email"
                              :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="my-1" />
            </div>
        </div>

        <!-- Password -->
        <div class="my-3 row">
            <div class="col-sm-4">
                <x-input-label for="password" :value="__('Password')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="password" class="bg-light rounded-1" :invalid="$errors->has('password')"
                              type="password"
                              name="password"
                              required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="my-1" />
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="my-3 row">
            <div class="col-sm-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="password_confirmation" class="bg-light rounded-1" :invalid="$errors->has('password_confirmation')"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="my-1" />
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <a class="link-primary" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-3 px-5 fw-bold text-light">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
