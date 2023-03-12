@extends('layouts.admin')
@section('title') {{ __('Orders') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Orders') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('List of Orders') }}</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    {{ $orders->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10px">{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Provider') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->service->name }}</td>
                                        <td>{{ $order->service->worker->name }}</td>
                                        <td>
                                            {{ $order->statusText }}
                                            @if ($order->status < 2)
                                                <a href="{{ route('admin.orders.edit', $order->id) }}">{{ __('Edit') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
