@extends('layouts.admin')
@section('title') {{ $shop->company_name }} {{ __('edit') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Shops') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ $shop->company_name }} {{ __('view') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" disabled name="company_name" class="form-control" id="name" value="{{ $shop->company_name }}">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea name="description" disabled="disabled" class="form-control" id="description">{{ $shop->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="owner_id" class="form-label">{{ __('Owner') }}: </label>
                                {{ $shop->owner->name }}
                            </div>
                            <div class="mb-3">
                                <label for="workers" class="form-label">{{ __('Workers') }}: </label>
                                {{ $shop->workers->implode('name', ', ') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
