@extends('layouts.admin')
@section('title') {{ $user->name }} {{ __('edit') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Users') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ $user->name }} {{ __('edit') }}
                            </div>
                        </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email address') }}</label>
                                    <input disabled type="email" name="email" class="form-control" id="email" value="{{ $user->email }}">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input disabled type="text" name="name" class="form-control" id="name" value="{{ $user->name }}">
                                </div>
                                <div class="mb-3 form-check">
                                    <input disabled name="barber" type="checkbox" @if($user->role === 1) checked @endif class="form-check-input" id="barber">
                                    <label class="form-check-label" for="barber">{{ __('Barber?') }}</label>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
