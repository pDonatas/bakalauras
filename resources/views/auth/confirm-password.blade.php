@extends('layouts.main')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    {{ __('Register') }}
                </div>
                <div class="card-body">
                    <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
                        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                    </div>

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <!-- Password -->
                        <div>
                            <x-label for="password" :value="__('Password')" />

                            <x-input id="password" class="form-control"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />
                        </div>

                        <div class="tw-flex tw-justify-end tw-mt-4">
                            <x-button class="btn default-button">
                                {{ __('Confirm') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
