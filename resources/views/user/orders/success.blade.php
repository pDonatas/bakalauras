@extends('layouts.main')
@section('title')
    {{ __('Order success') }}
@endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Order success') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        {{ __('We\'re happy to inform you that your order has been approved') }}
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('index') }}"><button class="btn btn-primary">{{ __('Back to main') }}</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection
