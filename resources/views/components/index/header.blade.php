<div class="main-carousel">
    <div class="container main-slider">
        <div class="row w-100">
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
        </div>
    </div>
    <div class="col">
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/slide1.jpg') }}" alt="">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/slide2.jpg') }}" alt="">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/slide3.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>


@section('js')
    <script type="module">
        const swiper = new Swiper('.swiper', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
        });
    </script>
@endsection
