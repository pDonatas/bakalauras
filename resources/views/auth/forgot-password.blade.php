@extends('layouts.main')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-6 justify-content-md-center">
            <div class="card card-default">
                <div class="card-header">
                    {{  __('Forgot Password') }}
                </div>
                <div class="card-body">
                    <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="tw-mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                        </div>

                        <div class="center mt-3">
                            <x-button class="btn default-button">
                                {{ __('Email Password Reset Link') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
