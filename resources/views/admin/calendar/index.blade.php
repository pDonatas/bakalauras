@extends('layouts.admin')

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
                    <input type="text" class="form-control" name="start_time" id="start_time">
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
                locale: initialLocaleCode,
                buttonIcons: false, // show the prev/next text
                weekNumbers: true,
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                selectMirror: true,
                events: JSON.parse('@json($events)'),
                eventOverlap: false,
                // eventClick: function(calEvent, jsEvent, view) {
                //     console.log(calEvent);
                //     $('#order_id').val(calEvent.id);
                //     $('#start_time').val(DateTime.fromJSDate(calEvent.start));
                //     $('#finish_time').val(DateTime.fromJSDate(calEvent.end));
                //     $('#editModal').modal();
                // }
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
