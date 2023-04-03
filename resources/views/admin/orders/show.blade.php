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
                      <ul class="list-group">
                          <li class="list-group-item">{{ __('Service name') }}: {{ $order->service->name }}</li>
                          <li class="list-group-item">{{ __('Order date') }}: {{ $order->created_at }}</li>
                          <li class="list-group-item">{{ __('Service date') }}: {{ $order->date }} {{ $order->time }}</li>
                          <li class="list-group-item">{{ __('Status') }}: {{ $order->statusText }}</li>
                          <li class="list-group-item">{{ __('Additional information') }}: {{ $order->comment }}</li>
                          <li class="list-group-item">{{ __('Selected example photo') }}: <img src="{{ $order->ai_photo }}" /></li>
                          <li class="list-group-item">{{ __('Selected current photo') }}: <img src="{{ $order->current_photo }}" /></li>
                      </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
