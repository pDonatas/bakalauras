@extends('layouts.main')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    {{ __('Register') }}
                </div>
                <div class="card-body">
                    <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="tw-mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="tw-mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="form-control"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="tw-mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="form-control"
                                type="password"
                                name="password_confirmation" required />
            </div>
            <div>
                <div class="tw-grid tw-grid-cols-3">
                    <div>
                        <x-label for="barber" :value="__('Registering as company?')" />
                        <x-input id="barber" type="checkbox" name="isBarber" :value="old('isBarber')" autofocus />
                    </div>
                </div>
            </div>
            <div id="barber-fields" class="d-none">
                <div class="tw-mt-4">
                    <x-label for="company_name" :value="__('Company name')" />

                    <x-input id="company_name" class="form-control" type="text" name="company_name" :value="old('company_name')" autofocus />
                </div>
                <div class="tw-mt-4">
                    <x-label for="company_code" :value="__('Company code')" />

                    <x-input id="company_code" class="form-control" type="text" name="company_code" :value="old('company_code')" autofocus />
                </div>
                <div class="tw-mt-4">
                    <x-label for="company_address" :value="__('Company address')" />

                    <x-input id="company_address" class="form-control" type="text" name="company_address" :value="old('company_address')" autofocus />
                </div>
                <div class="tw-mt-4">
                    <x-label for="company_phone" :value="__('Company phone')" />

                    <x-input id="company_phone" class="form-control" type="text" name="company_phone" :value="old('company_phone')" autofocus />
                </div>
            </div>
            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <a class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="btn default-button">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
        $('#barber').on('change', function () {
            const checked = $(this).is(':checked');

            if (checked) {
                $('#barber-fields').removeClass('d-none');
                $('#barber-fields input').attr('required', true);
            } else {
                $('#barber-fields').addClass('d-none');
                $('#barber-fields input').attr('required', false);
            }
        })
    </script>
@endsection
