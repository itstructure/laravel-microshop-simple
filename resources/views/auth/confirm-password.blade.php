<x-guest-layout>
    <div class="mb-4 text-dark">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3 row">
            <div class="col-sm-4">
                <x-input-label for="password" :value="__('Password')" class="mt-md-2 mb-0 mb-md-2 fw-bold" />
            </div>
            <div class="col-sm-8">
                <x-text-input id="password" class="bg-light rounded-1" :invalid="$errors->has('password')"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3">
            <x-primary-button class="px-5 fw-bold text-light">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
