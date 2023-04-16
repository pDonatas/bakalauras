@extends('layouts.main')

@section('title') {{ __('Shops') }} @endsection

@section('content')
    <section id="gallery" class="gallery">
        <div class="container-fluid">
            <div class="row gy-4 justify-content-center">
                @if (count($favorites) > 0)
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">{{ __('Favorites') }}</div>
                        <div class="card-body">
                            @foreach($favorites as $shop)
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
                </div>
                @endif
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">{{ __('Filters') }}</div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label for="company_name">{{ __('Company Name') }}</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ request()->get('company_name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="category">{{ __('Category') }}</label>
                                    <select class="form-control" id="category" name="category_id">
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request()->get('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="city">{{ __('City') }}</label>
                                    <select class="form-control" id="city" name="city">
                                        <option value="">{{ __('Select City') }}</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}" {{ request()->get('city') == $city->value ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="price">{{ __('Price') }}</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" id="price_from" name="price_from" value="{{ request()->get('price_from') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" id="price_to" name="price_to" value="{{ request()->get('price_to') }}">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
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
