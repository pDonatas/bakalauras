@extends('layouts.main')
@section('title')
    {{ __('Order failed') }}
@endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Order failed') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        {{ __('We\'re sorry to inform you that your order has been canceled') }}
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('index') }}"><button class="btn btn-primary">{{ __('Back to main') }}</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection
