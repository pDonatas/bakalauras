@extends('layouts.main')
@section('title')
    {{ $service->shop->company_name }} - {{ $service->name }} {{ __('by') }} {{ $service->worker->name }}
@endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title">
                        {{ $service->name }} {{ __('by') }} {{ $service->worker->name }}
                    </div>
                </div>
                <x-auth-validation-errors class="background-black" :errors="$errors" />
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" enctype="application/x-www-form-urlencoded" action="{{ route('orders.store', $service->id) }}">
                                    @csrf
                                    <h3>{{ __('Main order data') }}</h3>
                                    <div class="form-group">
                                        <label for="date">{{ __('Date') }}</label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">{{ __('Time') }}</label>
                                        <input type="time" class="form-control" id="time" name="time" value="{{ old('time') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">{{ __('Comment') }}</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="order-type">{{ __('Order Type') }}</label>
                                        <select class="form-control" id="order-type" name="order_type" required>
                                            <option value="1">{{ __('Paysera') }}</option>
                                            <option value="2">{{ __('In person') }}</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="ai_photo" id="ai-input" value="">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Order') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <h3>{{ __('Example photo generation data') }}</h3>
                                <x-a-i-generator-component :service="$service" />
                                <div id="ai-images"></div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
