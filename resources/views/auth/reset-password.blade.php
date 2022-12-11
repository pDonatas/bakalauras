@extends('layouts.main')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    {{ __('Register') }}
                </div>
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                        </div>

                        <!-- Password -->
                        <div class="tw-mt-4">
                            <x-label for="password" :value="__('Password')" />

                            <x-input id="password" class="form-control" type="password" name="password" required />
                        </div>

                        <!-- Confirm Password -->
                        <div class="tw-mt-4">
                            <x-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-input id="password_confirmation" class="form-control"
                                                type="password"
                                                name="password_confirmation" required />
                        </div>

                        <div class="tw-mt-4">
                            <x-button class="btn default-button">
                                {{ __('Reset Password') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
