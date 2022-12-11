@extends('layouts.main')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    {{ __('Login') }}
                </div>
                <div class="card-body">
                    <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                    <form method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label for="remember_me">
                                <input id="remember_me" type="checkbox" class="tw-rounded tw-border-gray-300 tw-text-indigo-600 tw-shadow-sm focus:tw-border-indigo-300 focus:tw-ring focus:tw-ring-indigo-200 focus:tw-ring-opacity-50" name="remember">
                                <span class="tw-ml-2 tw-text-sm tw-text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>
                        <div class="tw-flex tw-items-center tw-justify-end mt-4">
                            @if (Route::has('password.request'))
                                <a class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <x-button class="tw-ml-3 default-button">
                                {{ __('Log in') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
