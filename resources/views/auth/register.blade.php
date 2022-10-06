<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="tw-w-20 tw-h-20 tw-fill-current tw-text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="tw-block tw-mt-1 tw-w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="tw-mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="tw-block tw-mt-1 tw-w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="tw-mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="tw-block tw-mt-1 tw-w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="tw-block tw-mt-1 tw-w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>
            <div>
                <div class="tw-grid tw-grid-cols-3">
                    <div>
                        <x-label for="barber" :value="__('Wanna be a barber?')" />
                    </div>
                    <div>
                        <x-input id="barber" class="tw-inline-block" type="checkbox" name="isBarber" :value="old('isBarber')" autofocus />
                    </div>
                </div>
            </div>
            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <a class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="tw-ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
