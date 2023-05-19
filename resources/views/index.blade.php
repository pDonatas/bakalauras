@extends('layouts.index')

@section('title')
    {{ __('Shops') }}
@endsection

@section('content')
    @if (count($favorites) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">{{ __('Favorites') }}</div>
                    <div class="card-body">
                        @foreach($favorites as $shop)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="gallery-item h-100">
                                    <img src="{{ $shop->photo }}" class="img-fluid" alt="{{ $shop->company_name }}">
                                    <div class="gallery-links d-flex align-items-center justify-content-center">
                                        <a href="{{ route('shop.show', $shop->id) }}" class="details-link"><i
                                                class="bi bi-link-45deg"></i> {{ $shop->name }}</a>
                                    </div>
                                </div>
                            </div><!-- End Gallery Item -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row gy-4 justify-content-center">
        @foreach($shops as $shop)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card card-default">
                    <img src="{{ $shop->photo }}" class="card-img-top" alt="{{ $shop->company_name }}">
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('shop.show', $shop->id) }}"
                                                  class="details-link">{{ $shop->company_name }}</a></h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($shop->description) }}</p>
                    </div>
                </div>
            </div><!-- End Gallery Item -->
        @endforeach
    </div>
@endsection
