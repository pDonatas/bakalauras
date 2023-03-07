<div>
    @foreach($services as $service)
        <div class="service">
            <div class="name">{{ $service->name }} / 5</div>
            <div class="description">{!! $service->description !!}</div>
            <div class="author">
                <img src="{{ $service->worker->photo }}" alt="" />
                <span>{{ $service->worker->name }}</span>
            </div>
            <div class="price">{{ $service->price }} €</div>
            <div class="actions">
                <a href="{{ route('orders.create', $service->id) }}" class="btn btn-primary">{{ __('Order') }}</a>
            </div>
        </div>
    @endforeach
</div>
