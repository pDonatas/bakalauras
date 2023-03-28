@extends('layouts.admin')

@section('title') {{ __('Calendar') }} @endsection

@section('actions') <a href="{{ route('admin.calendar.manage') }}"><button class="button-black fr" href="#">{{ __('Calendar management') }}</button></a> @endsection

@section('content')
    <div id="calendar"></div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="order_id" value="" />
                    <h4>{{ __('Edit Order') }}</h4>

                    <label for="start_time">{{ __('Start time') }}</label>
                    <br />
                    <input type="text" class="form-control timepicker" name="start_time" id="start_time">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                    <input type="button" class="btn btn-primary" id="appointment_update" value="{{ __('Update') }}">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.4/locales-all.global.min.js"></script>
    <script src="
https://cdn.jsdelivr.net/npm/luxon@3.3.0/build/global/luxon.min.js
"></script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const initialLocaleCode = 'lt';
            const calendarEl = document.getElementById('calendar');
            const DateTime = luxon.DateTime;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                hiddenDays: JSON.parse('@json($hiddenDays)'),
                locale: initialLocaleCode,
                buttonIcons: false, // show the prev/next text
                weekNumbers: true,
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                selectMirror: true,
                events: JSON.parse('@json($events)'),
                eventOverlap: false,
                eventDrop: function(event) {
                    console.log(event);
                    const data = {
                        _token: '{{ csrf_token() }}',
                        order_id: event.event.id,
                        startDate: DateTime.fromJSDate(event.event.start).toISODate(),
                        startTime: DateTime.fromJSDate(event.event.start).toLocaleString(DateTime.TIME_24_SIMPLE),
                        length: DateTime.fromJSDate(event.event.end).diff(DateTime.fromJSDate(event.event.start), 'minutes').minutes,
                    };

                    $.post('{{ route('appointments_ajax_update') }}', data, function( result ) {
                        $('#calendar').fullCalendar('removeEvents', event.event.id);

                        $('#calendar').fullCalendar('renderEvent', JSON.parse(result), true);
                    });
                },
            });

            calendar.render();
        });

        $(document).ready(function() {
            $('#appointment_update').click(function(e) {
                e.preventDefault();
                var data = {
                    _token: '{{ csrf_token() }}',
                    appointment_id: $('#appointment_id').val(),
                    start_time: $('#start_time').val(),
                    finish_time: $('#finish_time').val(),
                };

                $.post('{{ route('appointments_ajax_update') }}', data, function( result ) {
                    $('#calendar').fullCalendar('removeEvents', $('#event_id').val());

                    $('#calendar').fullCalendar('renderEvent', JSON.parse(result), true);

                    $('#editModal').modal('hide');
                });
            });
        });

    </script>
@endsection

@section('styles')
    <style>
        .button-black {
            --fa-font-regular: normal 400 1em/1 "Font Awesome 6 Free";
            --lte-sidebar-width: 250px;
            --lte-sidebar-menu-active-bg: #0d6efd;
            --lte-sidebar-menu-active-color: #fff;
            --lte-sidebar-dark-bg: #343a40;
            --lte-sidebar-dark-hover-bg: rgba(255, 255, 255, .1);
            --lte-sidebar-dark-color: #c2c7d0;
            --lte-sidebar-dark-hover-color: #fff;
            --lte-sidebar-dark-active-color: #fff;
            --lte-sidebar-dark-submenu-bg: transparent;
            --lte-sidebar-dark-submenu-color: var(--lte-sidebar-dark-color);
            --lte-sidebar-dark-submenu-hover-color: #fff;
            --lte-sidebar-dark-submenu-hover-bg: var(--lte-sidebar-dark-hover-bg);
            --lte-sidebar-dark-submenu-active-color: var(--lte-sidebar-dark-bg);
            --lte-sidebar-dark-submenu-active-bg: rgba(255, 255, 255, .9);
            --lte-sidebar-dark-header-color: #c5cad2;
            --lte-sidebar-light-bg: #fff;
            --lte-sidebar-light-hover-bg: rgba(#000, .1);
            --lte-sidebar-light-color: #343a40;
            --lte-sidebar-light-hover-color: #212529;
            --lte-sidebar-light-active-color: #000;
            --lte-sidebar-light-submenu-bg: transparent;
            --lte-sidebar-light-submenu-color: #777;
            --lte-sidebar-light-submenu-hover-color: #000;
            --lte-sidebar-light-submenu-hover-bg: var(--lte-sidebar-light-hover-bg);
            --lte-sidebar-light-submenu-active-color: var(--lte-sidebar-light-hover-color);
            --lte-sidebar-light-submenu-active-bg: var(--lte-sidebar-light-submenu-hover-bg);
            --lte-sidebar-light-header-color: #31373d;
            --fa-style-family-brands: 'Font Awesome 6 Brands';
            --fa-font-brands: normal 400 1em/1 'Font Awesome 6 Brands';
            --fa-style-family-classic: 'Font Awesome 6 Free';
            --fa-font-solid: normal 900 1em/1 'Font Awesome 6 Free';
            --swiper-theme-color: #007aff;
            --swiper-navigation-size: 44px;
            --bs-blue: #0d6efd;
            --bs-indigo: #6610f2;
            --bs-purple: #6f42c1;
            --bs-pink: #d63384;
            --bs-red: #dc3545;
            --bs-orange: #fd7e14;
            --bs-yellow: #ffc107;
            --bs-green: #198754;
            --bs-teal: #20c997;
            --bs-cyan: #0dcaf0;
            --bs-black: #000;
            --bs-white: #fff;
            --bs-gray: #6c757d;
            --bs-gray-dark: #343a40;
            --bs-gray-100: #f8f9fa;
            --bs-gray-200: #e9ecef;
            --bs-gray-300: #dee2e6;
            --bs-gray-400: #ced4da;
            --bs-gray-500: #adb5bd;
            --bs-gray-600: #6c757d;
            --bs-gray-700: #495057;
            --bs-gray-800: #343a40;
            --bs-gray-900: #212529;
            --bs-primary: #0d6efd;
            --bs-secondary: #6c757d;
            --bs-success: #198754;
            --bs-info: #0dcaf0;
            --bs-warning: #ffc107;
            --bs-danger: #dc3545;
            --bs-light: #f8f9fa;
            --bs-dark: #212529;
            --bs-primary-rgb: 13, 110, 253;
            --bs-secondary-rgb: 108, 117, 125;
            --bs-success-rgb: 25, 135, 84;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-danger-rgb: 220, 53, 69;
            --bs-light-rgb: 248, 249, 250;
            --bs-dark-rgb: 33, 37, 41;
            --bs-white-rgb: 255, 255, 255;
            --bs-black-rgb: 0, 0, 0;
            --bs-body-color-rgb: 33, 37, 41;
            --bs-body-bg-rgb: 255, 255, 255;
            --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            --bs-body-font-family: var(--bs-font-sans-serif);
            --bs-body-font-size: 1rem;
            --bs-body-font-weight: 400;
            --bs-body-line-height: 1.5;
            --bs-body-color: #212529;
            --bs-body-bg: #fff;
            --bs-border-width: 1px;
            --bs-border-style: solid;
            --bs-border-color: #dee2e6;
            --bs-border-color-translucent: rgba(0, 0, 0, 0.175);
            --bs-border-radius: 0.375rem;
            --bs-border-radius-sm: 0.25rem;
            --bs-border-radius-lg: 0.5rem;
            --bs-border-radius-xl: 1rem;
            --bs-border-radius-2xl: 2rem;
            --bs-border-radius-pill: 50rem;
            --bs-link-color: #0d6efd;
            --bs-link-hover-color: #0a58ca;
            --bs-code-color: #d63384;
            --bs-highlight-bg: #fff3cd;
            --font-default: "Open Sans", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --font-primary: "Inter", sans-serif;
            --font-secondary: "Cardo", sans-serif;
            --color-default: #fafafa;
            --color-primary: #27a776;
            --color-secondary: #161718;
            --fc-small-font-size: .85em;
            --fc-page-bg-color: #fff;
            --fc-neutral-bg-color: hsla(0, 0%, 82%, .3);
            --fc-neutral-text-color: grey;
            --fc-border-color: #ddd;
            --fc-button-text-color: #fff;
            --fc-button-bg-color: #2c3e50;
            --fc-button-border-color: #2c3e50;
            --fc-button-hover-bg-color: #1e2b37;
            --fc-button-hover-border-color: #1a252f;
            --fc-button-active-bg-color: #1a252f;
            --fc-button-active-border-color: #151e27;
            --fc-event-bg-color: #3788d8;
            --fc-event-border-color: #3788d8;
            --fc-event-text-color: #fff;
            --fc-event-selected-overlay-color: rgba(0, 0, 0, .25);
            --fc-more-link-bg-color: #d0d0d0;
            --fc-more-link-text-color: inherit;
            --fc-event-resizer-thickness: 8px;
            --fc-event-resizer-dot-total-width: 8px;
            --fc-event-resizer-dot-border-width: 1px;
            --fc-non-business-color: hsla(0, 0%, 84%, .3);
            --fc-bg-event-color: #8fdf82;
            --fc-bg-event-opacity: 0.3;
            --fc-highlight-color: rgba(188, 232, 241, .3);
            --fc-today-bg-color: rgba(255, 220, 40, .15);
            --fc-now-indicator-color: red;
            --fc-daygrid-event-dot-width: 8px;
            --fc-list-event-dot-width: 10px;
            --fc-list-event-hover-bg-color: #f5f5f5;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            direction: ltr;
            box-sizing: border-box;
            font-family: inherit;
            margin: 0;
            overflow: visible;
            text-transform: none;
            -webkit-appearance: button;
            border: 1px solid transparent;
            border-radius: .25em;
            display: inline-block;
            font-size: 1em;
            font-weight: 400;
            line-height: 1.5;
            padding: .4em .65em;
            text-align: center;
            user-select: none;
            vertical-align: middle;
            background-color: var(--fc-button-bg-color);
            border-color: var(--fc-button-border-color);
            color: var(--fc-button-text-color);
            cursor: pointer;
            flex: 1 1 auto;
            position: relative;
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
@endsection
