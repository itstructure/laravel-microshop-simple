<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3 row">
            <div class="col-sm-4">
                <x-input-label for="email" :value="__('Email')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="email" class="bg-light rounded-1" :invalid="$errors->has('email')"
                              type="email" name="email"
                              :value="old('email', $request->email)" required autofocus autocomplete="username" />
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
                              type="password" name="password"
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
                              type="password" name="password_confirmation"
                              required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="my-1" />
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3">
            <x-primary-button class="px-5 fw-bold text-light">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
