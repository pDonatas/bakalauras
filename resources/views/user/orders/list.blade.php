@extends('layouts.main')
@section('title')
    {{ __('Orders') }}
@endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Orders') }} {{ $orders->links() }}
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-responsive">
<thead>
                            <tr>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('Shop') }}</th>
                                <th>{{ __('Specialist') }}</th>
                                <th>{{ __('Comment') }}</th>
                                <th>{{ __('Order Type') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->service->name }}</td>
                                    <td>{{ $order->date }}</td>
                                    <td>{{ $order->time }}</td>
                                    <td>{{ $order->service->shop->company_name }}</td>
                                    <td>{{ $order->service->worker->name }}</td>
                                    <td>{{ $order->comment }}</td>
                                    <td>{{ $order->order_type }}</td>
                                    <td>
                                        {{ $order->statusText }}
                                        <a href="{{ route('orders.show', $order->id) }}">{{ __('Show') }}</a>
                                        @if ($order->status < 2)
                                            <a href="{{ route('orders.cancel', $order->id) }}">{{ __('Cancel') }}</a>
                                            <a href="{{ route('orders.edit', $order->id) }}">{{ __('Edit') }}</a>
                                        @else
                                            <a href="{{ route('orders.review', $order->id) }}">{{ __('Leave a review') }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('index') }}"><button class="btn btn-primary">{{ __('Back to main') }}</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection
