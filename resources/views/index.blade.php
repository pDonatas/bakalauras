@extends('layouts.main')

@section('title') {{ __('Shops') }} @endsection

@section('content')
    <section id="gallery" class="gallery">
        <div class="container-fluid">
            <div class="row gy-4 justify-content-center">
                @foreach($shops as $shop)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="gallery-item h-100">
                            <img src="{{ $shop->photo }}" class="img-fluid" alt="{{ $shop->company_name }}">
                            <div class="gallery-links d-flex align-items-center justify-content-center">
                                <a href="{{ route('shop.show', $shop->id) }}" class="details-link"><i class="bi bi-link-45deg"></i> {{ $shop->name }}</a>
                            </div>
                        </div>
                    </div><!-- End Gallery Item -->
                @endforeach
            </div>
        </div>
    </section>
@endsection
