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
                <x-auth-validation-errors class="background-black" :errors="$errors" />
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form method="post" enctype="multipart/form-data" action="{{ route('orders.update', $order->id) }}">
                                @csrf
                                @method('PUT')
                                <h3>{{ __('Main order data') }}</h3>
                                <div class="form-group">
                                    <label for="datepicker">{{ __('Date') }}</label>
                                    <input type="text" class="form-control datepicker" id="datepicker" name="date" value="{{ $order->date }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="time">{{ __('Time') }}</label>
                                    <select id="time" class="form-control" disabled name="time"></select>
                                </div>
                                <div class="form-group">
                                    <label for="comment">{{ __('Comment') }}</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3">{{ $order->comment }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="order-type">{{ __('Order Type') }}</label>
                                    <select class="form-control" id="order-type" name="order_type" required>
                                        <option @if ($order->order_type === 1) selected @endif value="1">{{ __('Paysera') }}</option>
                                        <option @if ($order->order_type === 2) selected @endif value="2">{{ __('In person') }}</option>
                                    </select>
                                </div>
                                <div class="from-group mb-3">
                                    <label for="photo">{{ __('Current photo') }}</label>
                                    <div class="input-group">
                                        <div id="file" class="show">
                                            <input type="file" accept="image/*" class="form-control" id="photo" name="current_photo_file" value="{{ $order->current_photo }}">

                                            <button type="button" class="btn btn-primary" id="click-photo">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                        </div>
                                        <div id="capture" class="capture hide">
                                            <video id="video" class="form-control" width="640" height="480" autoplay></video>

                                            <button type="button" class="btn btn-primary" id="click-capture">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" id="click-cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div id="current_photo"><img src="{{ $order->current_photo }}" class="img-fluid" alt="Responsive image"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="ai_photo" id="ai-input" value="{{ $order->ai_image }}">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{{ __('Edit') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h3>{{ __('Example photo generation data') }}</h3>
                            <x-a-i-generator-component :service="$service" />
                            <div id="ai-images">
                                <img src="{{ $order->ai_photo }}" alt="AI image" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module">
        $(document).ready(function() {
            const datepicker = document.getElementById('datepicker');
            new TempusDominus(datepicker, {
                localization: {
                    locale: 'lt',
                    format: "yyyy-MM-dd",
                    startOfTheWeek: 1,
                    hourCycle: 'h23',
                },
                display: {
                    components: {
                        calendar: true,
                        date: true,
                        month: true,
                        year: true,
                        decades: false,
                        clock: false,
                        hours: false,
                        minutes: false,
                        seconds: false,
                    },
                    inline: false,
                    theme: 'auto',
                },
                allowInputToggle: true,
                useCurrent: true,
                defaultDate: undefined,
                restrictions: {
                    daysOfWeekDisabled: JSON.parse('@json($hiddenDays)'),
                }
            });

            datepicker.addEventListener('change', function (e) {
                let date = e.detail.date;
                date = date.format('yyyy-MM-dd');
                getTime(date);
            });

            $('#click-photo').click(function () {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    $('#file').removeClass('show').addClass('hide');
                    $('#capture').removeClass('hide').addClass('show');
                    navigator.mediaDevices.getUserMedia({video: true}).then(function (stream) {
                        const video = document.getElementById('video');
                        video.srcObject = stream;
                        video.play();
                    });
                }
            });

            $('#click-capture').click(function () {
                const video = document.getElementById('video');
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                const data = canvas.toDataURL('image/png');
                $('#current-photo').val(data);
                $('#current_photo').html(`<img src="${data}" class="img-fluid" alt="Responsive image">`);
                $('#capture').removeClass('show').addClass('hide');
                $('#file').removeClass('hide').addClass('show');
            });

            function getTime(date) {
                let currentTime = '{{ $order->time }}';

                $.ajax({
                    url: '{{ route('services.time', $service->id) }}',
                    type: 'POST',
                    data: {
                        date: date,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $('#time').removeAttr('disabled');
                        $('#time').empty();
                        $('#time').append(`<option value="${currentTime}" selected>${currentTime}</option>`);
                        const times = Object.values(data);
                        times.forEach(function (time) {
                            $('#time').append(`<option ${currentTime == time ? 'selected' : ''} value="${time}">${time}</option>`);
                        });
                    }
                });
            }

            getTime(datepicker.value);
        });
    </script>
@endsection

@section('styles')
    <style>
        .hide {
            display: none;
        }
        .show {
            display: block;
        }
    </style>
@endsection
