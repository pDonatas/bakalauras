@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Compare') }}</h1>

                <div id="compareContent"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module">
        $(document).ready(function () {
            $.ajax({
                url: '{{ route('shops.compare') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: localStorage.getItem('compare')
                },
                success: function (data) {
                    $('#compareContent').html(data);
                }
            });
        })
    </script>
@endsection

@section('styles')
    <style>
        table {
            color: #fff !important;
        }
    </style>
@endsection
