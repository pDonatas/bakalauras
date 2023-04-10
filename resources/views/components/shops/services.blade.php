<div>
    @foreach($services as $service)
        <div class="service">
            <div class="name">{{ $service->name }} <span>5/ 5</span></div>
            <div class="description">{!! $service->description !!}</div>
            <div class="author">
                <img src="{{ $service->worker->photo }}" alt="" />
                <span>{{ $service->worker->name }}</span>
            </div>
            <div class="price">{{ $service->price }} €</div>
            <div class="actions">
                <a href="{{ route('orders.create', $service->id) }}" class="btn btn-primary">{{ __('Order') }}</a>
                <button type="button" data-service="{{ $service->id }}" class="btn btn-primary service-photos">
                    {{ __('Photo gallery') }}
                </button>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Photo gallery') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="photo-gallery" id="photo-gallery"></div>
            </div>
        </div>
    </div>
</div>


@section('styles')
    <script type="module">
        $(document).ready(function() {
            const modal = new Modal('#exampleModalCenter');

            $('.service-photos').click(function() {
                $('#photo-gallery').html('');
                const service = $(this).data('service');
                $.ajax({
                    url: `/api/services/${service}/photos`,
                    type: 'GET',
                    success: function(data) {
                        data.forEach(function(photo) {
                            $('#photo-gallery').append(`<img src="${photo.path}" alt="" />`);
                        });
                        modal.show();
                    }
                });
            });
        });
    </script>
@endsection
