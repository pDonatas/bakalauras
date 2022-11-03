@extends('layouts.main')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-header">
                    {{ __('Register') }}
                </div>
                <div class="card-body">
                    <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <div class="tw-mt-4 tw-flex tw-items-center tw-justify-between">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div>
                                <x-button class="btn default-button">
                                    {{ __('Resend Verification Email') }}
                                </x-button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="btn default-button">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
