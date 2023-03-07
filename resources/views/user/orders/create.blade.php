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
                <form method="post" action="{{ route('orders.store', $service->id) }}">
                    @csrf
                    <div class="card-body">
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
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Order') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
