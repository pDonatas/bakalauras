@extends('layouts.main')

@section('title') {{ $user->name }} @endsection

@section('content')
    <!-- Generate facebook like user profile page -->
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ $user->avatar }}" class="rounded-circle" width="150">
                            <h4 class="mt-2">{{ $user->name }}</h4>
                            <p class="lead text-muted">{{ $user->getRole() }}</p>
                            <p class="lead text-muted">{{ $user->email }}</p>
                            <p class="lead text-muted">{{ __('Joined') }} {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-default">
                    <div class="card-body">
                        <h3 class="card-title">About Me</h3>
                        <p class="card-text">I have {{ $user->owned_shops_count }} owned shops, {{ $user->orders_count }} orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection