@extends('layouts')
@section('title', 'Holiday')
@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
<div class="content-wrapper">
    <div class="container-fluid py-4"> 
    <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Restaurant Holidays</h2>
        <div class="card card-body mt-4">
            <div id='calendar'></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function() {
    var SITEURL = "{{ url('/') }}";
    var restaurantId = {{ Auth::guard('restaurant')->user()->id ?? 'null' }}; 
    var isEditable = {{ Auth::guard('restaurant')->check() && Auth::guard('restaurant')->user()->id ? 'true' : 'false' }};

    var calendar = $('#calendar').fullCalendar({
        editable: isEditable,
        selectable: true,
        events: SITEURL + "/holidays?restaurant_id=" + restaurantId,
        select: function(start, end) {
            var holidayName = prompt("Enter holiday name:");
            if (holidayName) {
                // Send data to server using AJAX
                $.ajax({
                    url: '{{ route("add_holiday") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'restaurant_id': '{{ Auth::guard('restaurant')->user()->id }}',
                        'holiday_name': holidayName,
                        'start_date': start.format(),
                        'end_date': end.format()
                    },
                    success: function(data) {
                        // Refresh the calendar to display the new event
                        $('#calendar').fullCalendar('refetchEvents');
                        toastr.success('Holiday Added Successfully!', 'Success');
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Failed to add holiday!', 'Error');
                    }
                });
            }
        },
        eventColor: '#355876',
        displayEventTime: false,
        eventRender: function(event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
            element.mouseover(function(e) {
                var content = '<div class="tooltip">' + event.title + '</div>';
                $(this).append(content);
                var tooltip = $(this).find('.tooltip');
                tooltip.css('top', e.pageY + 10);
                tooltip.css('left', e.pageX - (tooltip.width() / 2));
                tooltip.show();
                if (tooltip.get(0).scrollWidth > tooltip.width()) {
                    tooltip.css('overflow-x', 'scroll');
                }
                tooltip.css('background-color', '#FFF8DC');
            }).mouseout(function() {
                $(this).find('.tooltip').remove();
            });
            element.click(function() {
                alert('Event details: ' + event.title);
            });
        },
        eventDrop: function(event, delta, revertFunc) {
            if (isEditable) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                $.ajax({
                    url: SITEURL + '/update/holidays/' + event.id,
                    data: {
                        title: event.title,
                        start: start,
                        end: end,
                        id: event.id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "PUT",
                    success: function(response) {
                        displayMessage("Holiday Updated Successfully", 'success');
                    },
                    error: function(xhr) {
                        revertFunc();
                        displayMessage("Error Updating Holiday", 'error');
                    }
                });
            } else {
                alert("You do not have permission to update holidays.");
            }
        },
        eventResize: function(event, delta, revertFunc) {
            if (isEditable) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                $.ajax({
                    url: SITEURL + '/update/holidays/' + event.id,
                    data: {
                        title: event.title,
                        start: start,
                        end: end,
                        id: event.id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "PUT",
                    success: function(response) {
                        displayMessage("Holiday Updated Successfully", 'success');
                    },
                    error: function(xhr) {
                        revertFunc();
                        displayMessage("Error Updating Holiday", 'error');
                    }
                });
            } else {
                alert("You do not have permission to update holidays.");
            }
        },
        eventClick: function(event) {
            if (isEditable) {
                var now = moment();
                var eventEnd = moment(event.end);

                if (eventEnd.isSame(now, 'day') || eventEnd.isAfter(now)) {
                    if (confirm("Are you sure you want to delete this holiday?")) {
                        $.ajax({
                            type: "DELETE",
                            url: SITEURL + '/delete/holidays/' + event.id,
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove the event from the FullCalendar UI
                                    $('#calendar').fullCalendar('removeEvents', event.id);
                                    displayMessage("Holiday Deleted Successfully", 'success');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("XHR Status:", status);
                                console.error("XHR Error:", error);
                                revertFunc();
                                displayMessage("Error Deleting Holiday", 'error');
                            }
                        });
                    }
                } else {
                    alert("Cannot delete holidays that have started or occurred.");
                }
            } else {
                alert("You do not have permission to delete holidays.");
            }
        }
    });

    function displayMessage(message, type) {
        toastr[type](message, 'Event');
    }
});
</script>
@endsection
