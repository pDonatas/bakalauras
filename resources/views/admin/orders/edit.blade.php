@extends('layouts.admin')
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Order') }}
                    </div>
                </div>
                <x-auth-validation-errors class="background-black" :errors="$errors" />
                <form method="post" action="{{ route('admin.orders.update', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="service">{{ __('Service') }}</label>
                            <select class="form-control" id="service" name="service_id" required>
                                @foreach($services as $service)
                                    <option @if($order->service_id == $service->id) selected @endif value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">{{ __('Date') }}</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ $order->date }}" required>
                        </div>
                        <div class="form-group">
                            <label for="time">{{ __('Time') }}</label>
                            <input type="time" class="form-control" id="time" name="time" value="{{ $order->time }}" required>
                        </div>
                        <div class="form-group">
                            <label for="comment">{{ __('Comment') }}</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3">{{ $order->comment }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="order-type">{{ __('Order Type') }}</label>
                            <select class="form-control" id="order-type" name="order_type" required>
                                <option @if($order->order_type == 1) selected @endif value="1">{{ __('Paysera') }}</option>
                                <option @if($order->order_type == 2) selected @endif value="2">{{ __('In person') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control" id="status" name="status" required>
                                <option @if($order->status == 0) selected @endif value="0">{{ __('Not Paid') }}</option>
                                <option @if($order->status == 1) selected @endif value="1">{{ __('Paid') }}</option>
                                <option @if($order->status == 2) selected @endif value="2">{{ __('Canceled') }}</option>
                                <option @if($order->status == 3) selected @endif value="3">{{ __('Fulfilled') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
