@extends('layouts')
@section('title','Restaurant Details')
@section('content')

<head>
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .show-more-btn {
            margin: 10px auto;
            cursor: pointer;
            color: #052d64;
            font-size: 15px;
        }
        .text-end {
            text-align: end !important;
        }
        .text-start {
            text-align: start !important;
        }
        .fc-bg {
            background-color: #effdff;
        }
        .fc-today {
            background-color: #ffe79e;
        }
        .rating {
        font-size: 24px; /* Adjust the size of the stars */
        }

        .rating .fa-star {
            color: #ffc107; /* Set color for filled stars */
        }

        .rating .far.fa-star {
            /* Use the Font Awesome outlined star icon for empty stars */
            color: transparent; /* Set transparent color for the outlined star */
            border: 1px solid #ffc107; /* Add border to create an outline */
            padding: 0 3px; /* Adjust padding to maintain the shape */
        }

        .rating .fas.fa-star-half-alt {
            /* Use the Font Awesome half-filled star icon */
            color: #ffc107; /* Set color for the half-filled star */
        }
        .hidden {
            display: none;
        }
        @media (max-width: 767px) {
            .media-body {
                margin-left: 0; /* Remove left margin on smaller screens */
            }
        }
        .fixed-size-image {
            width: 200px;  /* Set the desired width */
            height: 200px; /* Set the desired height */
            object-fit: cover;  /* Ensures the image covers the specified dimensions without distortion */
        }
        .heart-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 24px;
            z-index: 10;
        }

        .heart-icon .fa-heart-o {
            color: #e74c3c;
        }

        .heart-icon .fa-heart {
            color: #e74c3c;
        }

        .heart-icon:hover .fa-heart-o,
        .heart-icon:hover .fa-heart {
            color: #c0392b;
        }
    </style>
</head>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-12 mx-auto">
                <div class="card">
                    <div class="blue text-center lucida-handwriting" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <h3 style="font-size: 30px; margin-bottom: 0;">{{ $restaurant->name }}</h3>
                        @php
                            $liked = Auth::user()->likes()->where('restaurant_id', $restaurant->id)->exists();
                        @endphp
                        <span class="heart-icon" onclick="saveRestaurant(event, {{ $restaurant->id }})">
                            <i class="fa {{ $liked ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="row" id="image-row">
                        <!-- Display the first 3 images -->
                        @foreach($attachments->take(3) as $attachment)
                        <div class="col-md-4 mb-3">
                            <div class="position-relative image-container">
                                <img src="{{ asset('storage/res_pic/' . $attachment->picture) }}" alt="Attachment" class="img-fluid image-with-outline fixed-size-image">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        @if(count($attachments) > 3)
                        <div class="col-md-6" style="text-align: right;">
                            <div class="show-more-btn" id="prev-btn" style="display: none;" onclick="showPrevious()">&#171; Previous</div>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <div class="show-more-btn" id="next-btn" onclick="showNext()">Next &#187;</div>
                        </div>
                        @else
                        <div class="col-md-12" style="text-align: left;">
                            <div class="show-more-btn" id="next-btn" style="display: none;" onclick="showNext()">Next &#187;</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="blue" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <h3 class="card-title">About Restaurant</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Address</strong>
                        <p class="text-muted">{{ $restaurant->address }}</p>
                        <hr>
                        <strong><i class="fas fa-phone mr-1"></i>Contact</strong>
                        <p class="text-muted">{{ $restaurant->phone_num }}</p>
                        <hr>
                        <strong><i class="fas fa-clock mr-1"></i>Operation Time</strong>
                        <p class="text-muted">{!! $restaurant->operation_time !!}</p>
                        <hr>
                        @if($restaurant->description)
                            <strong><i class="fas fa-info-circle mr-1"></i>Description</strong>
                            <p class="text-muted">{{ $restaurant->description }}</p>
                            <hr>
                        @endif
                        <strong><i class="fas fa-info-circle mr-1"></i>Holiday(Restaurant Close)</strong>
                        <div class="col-md-8 offset-md-2"> 
                            <div class="card">              
                                <div id='calendar'></div>        
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card" id="commentsContainer">
                    <div class="blue" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Display the count of ratings for the restaurant -->
                            <h3 class="card-title mb-0">Rating ({{ $restaurant->ratings->count() }} ratings)</h3>
                            <!-- Display average rating stars -->
                            <div class="rating">
                                @php
                                    // Retrieve ratings for the current restaurant
                                    $ratings = $restaurant->ratings;
                                    
                                    // Calculate average rating
                                    $totalRating = 0;
                                    $count = count($ratings);
                                    foreach ($ratings as $rating) {
                                        $totalRating += $rating->mark;
                                    }
                                    $averageRating = $count > 0 ? $totalRating / $count : 0;

                                    // Determine the number of filled stars
                                    $filledStars = floor($averageRating);
                                    // Determine if there should be a half-filled star
                                    $halfStar = $averageRating - $filledStars >= 0.5;
                                @endphp

                                <!-- Display filled stars -->
                                @for ($i = 0; $i < $filledStars; $i++)
                                    <span class="fa fa-star"></span>
                                @endfor

                                <!-- Display half-filled star if needed -->
                                @if ($halfStar)
                                    <span class="fa fa-star-half-o"></span>
                                @endif

                                <!-- Display empty stars -->
                                @for ($i = $filledStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                    <span class="fa fa-star-o"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @php $commentCount = 0; @endphp
                        @php $displayedComments = 0; @endphp
                        @if ($restaurant->ratings && $restaurant->ratings->isNotEmpty())
                            @foreach ($restaurant->ratings as $rating)
                                @if ($rating->comment)
                                    @php
                                        $user = App\Models\User::find($rating->user_id);
                                    @endphp
                                    @if ($user)
                                        @if ($commentCount >= $displayedComments && $displayedComments < 4)
                                            <div class="media mb-3 align-items-center">
                                                <!-- User Image -->
                                                <div class="col-md-1 col-1">
                                                    <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('assets/dist/img/defaultPic.png') }}" class="rounded-circle" alt="User Image" style="width: 50px; height: 50px;">
                                                </div>
                                                <!-- Rating Stars -->
                                                <div class="col-md-11 col-11">
                                                    <div class="rating d-inline-block">
                                                        @php
                                                            $filledStars = floor($rating->mark);
                                                            $halfStar = $rating->mark - $filledStars >= 0.5;
                                                        @endphp

                                                        <!-- Display filled stars -->
                                                        @for ($i = 0; $i < $filledStars; $i++)
                                                            <span class="fa fa-star" style="font-size: 16px;"></span>
                                                        @endfor

                                                        <!-- Display half-filled star if needed -->
                                                        @if ($halfStar)
                                                            <span class="fa fa-star-half-o" style="font-size: 16px;"></span>
                                                        @endif

                                                        <!-- Display empty stars -->
                                                        @for ($i = $filledStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                                            <span class="fa fa-star-o" style="font-size: 16px;"></span>
                                                        @endfor
                                                    </div>
                                                    <div class="media-body ml-2 ml-0">
                                                        <p>{{ $rating->comment }}</p>
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>
                                            @php $displayedComments++; @endphp
                                        @else
                                            <div class="media mb-3 align-items-center d-none">
                                                <!-- User Image -->
                                                <div class="col-md-1 col-1">
                                                    <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('assets/dist/img/defaultPic.png') }}" class="rounded-circle" alt="User Image" style="width: 50px; height: 50px;">
                                                </div>
                                                <!-- Rating Stars -->
                                                <div class="col-md-11 col-11">
                                                    <div class="rating d-inline-block">
                                                        @php
                                                            $filledStars = floor($rating->mark);
                                                            $halfStar = $rating->mark - $filledStars >= 0.5;
                                                        @endphp

                                                        <!-- Display filled stars -->
                                                        @for ($i = 0; $i < $filledStars; $i++)
                                                            <span class="fa fa-star" style="font-size: 16px;"></span>
                                                        @endfor

                                                        <!-- Display half-filled star if needed -->
                                                        @if ($halfStar)
                                                            <span class="fa fa-star-half-o" style="font-size: 16px;"></span>
                                                        @endif

                                                        <!-- Display empty stars -->
                                                        @for ($i = $filledStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                                            <span class="fa fa-star-o" style="font-size: 16px;"></span>
                                                        @endfor
                                                    </div>
                                                    <div class="media-body ml-md-2 ml-0">
                                                        <p>{{ $rating->comment }}</p>
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>
                                        @endif
                                        @php $commentCount++; @endphp
                                    @endif
                                @endif
                            @endforeach
                            @if ($commentCount > 4)
                                <hr>
                                <button id="viewMoreBtn" class="btn blue mt-3" onclick="showMore()" style="border-radius: 5px; display: block; margin: 0 auto;">View More</button>
                            @endif
                        @else
                            <p>No comments available.</p>
                        @endif
                    </div>
                </div>
                @if($restaurant->table_arrange_pic)
                <div class="card">
                    <div class="blue" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <h3 class="card-title">Tables In Restaurant</h3>
                    </div>
                    <div class="card-body">
                        <!-- Show table arrangement picture here -->
                        <div class="col-md-8 offset-md-2">
                            <img src="{{ asset('storage/' . $restaurant->table_arrange_pic) }}" alt="Table Arrangement" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="yellow" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <h3 class="card-title">Make A Reservation</h3>
                    </div>
                    <div class="card-body">
                        <form id="make_reservation" enctype="multipart/form-data">
                            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                            @csrf
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" name="date" class="form-control" id="dateInput" placeholder="Select date" readonly>
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" name="time" class="form-control" id="time">
                            </div>
                            <div class="form-group">
                                <label for="party_size">Party Size</label>
                                <input type="number" name="party_size" class="form-control" id="party_size" placeholder="Enter party size">
                            </div>
                            @if ($restaurant->table_num)
                                <div class="form-group">
                                    <label for="table_num">Table Number:</label>
                                    <select id="table_num" name="table_num" class="form-control">
                                        <option value="">Select a table</option>
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="remark">Remark</label>
                                <input type="text" name="remark" class="form-control" id="remark" placeholder="Write any remarks here...">
                            </div>
                            <br>
                            <div class="form-group text-center" style="border-radius: 5px;">
                                <button type="submit" class="btn btn-md yellow">Make Reservation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    var startIndex = 3; // Start index for the next batch of images

    function showPage() {
        var attachments = @json($attachments);

        // Clear the existing images
        document.getElementById('image-row').innerHTML = '';

        // Loop through attachments starting from the next index after the last shown image
        for (var i = startIndex - 3; i < startIndex; i++) {
            if (attachments[i]) { // Check if the attachment exists
                var attachment = attachments[i];
                var imageContainer = document.createElement('div');
                imageContainer.className = 'col-md-4 mb-3';
                var imageElement = document.createElement('img');
                imageElement.src = "{{ asset('storage/res_pic/') }}" + '/' + attachment.picture;
                imageElement.alt = 'Attachment';
                imageElement.className = 'img-fluid image-with-outline';
                imageElement.style.maxHeight = '200px';
                var positionRelative = document.createElement('div');
                positionRelative.className = 'position-relative image-container';
                positionRelative.style.maxHeight = '200px';
                positionRelative.appendChild(imageElement);
                imageContainer.appendChild(positionRelative);
                document.getElementById('image-row').appendChild(imageContainer);
            }
        }
    }

    function showNext() {
        var attachments = @json($attachments);
        var imagesToShow = 1; // Number of images to show in each batch

        if (startIndex + imagesToShow <= attachments.length) {
            startIndex += imagesToShow;
            showPage();
            document.getElementById('prev-btn').style.display = 'block'; // Display the previous button
        } else {
            document.getElementById('next-btn').style.display = 'none'; // Hide the next button if there are no more images
        }
    }

    function showPrevious() {
        var attachments = @json($attachments);
        var imagesToShow = 1; // Number of images to show in each batch

        if (startIndex - imagesToShow >= 3) {
            startIndex -= imagesToShow;
            showPage();
            document.getElementById('next-btn').style.display = 'block'; // Display the next button
        } else {
            document.getElementById('prev-btn').style.display = 'none'; // Hide the previous button if there are no previous images
        }
    }

    $.ajax({
        url: "/get-holidays", // replace with the URL that returns the list of holidays
        type: "GET",
        dataType: "json",
        success: function(response) {
            var holidays = response; // store the list of holidays

            // initialize the datepicker with a beforeShowDay function
            $('#dateInput').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0, // disable dates before today
                beforeShowDay: function(date) {
                    var dateString = $.datepicker.formatDate('yy-mm-dd', date);

                    // check if the date is a holiday for the current restaurant
                    var isHoliday = holidays.some(function(holiday) {
                        return holiday.restaurant_id == {{ $restaurant->id }} && dateString >= holiday.start && dateString < holiday.end;
                    });

                    if (isHoliday) {
                        return [false, 'holiday', 'This date is a holiday'];
                    } else {
                        return [true, ''];
                    }
                },
                onSelect: function(dateText) {
                    var selectedDate = $(this).val();
                    var isHoliday = holidays.some(function(holiday) {
                        return holiday.restaurant_id == {{ $restaurant->id }} && selectedDate >= holiday.start && selectedDate < holiday.end;
                    });

                    if (isHoliday) {
                        $(this).val('');
                        alert('You cannot select a holiday date');
                    }
                }
            });

            // set default date to today's date
            $('#log_date').datepicker('setDate', new Date());
        },
        error: function(xhr) {
            console.log("Error loading holidays: " + xhr.responseText);
        }
    });


    $('#make_reservation').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/reservation-make",
            beforeSend: function() {
                loadingModal();
            },
            
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Reservation made successfully! Please wait the approval from restaurant.'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('view_restaurant', ['id' => $restaurant->id]) }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                var errorMessage = 'There was an error making the reservation.';
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.table_num) {
                    errorMessage = xhr.responseJSON.errors.table_num;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            right: 'title',
        },
        events: @json($events), // Pass the events data from the controller
        height: 'auto', // Allow the calendar to adjust its height dynamically
        eventBorderColor: '#ff8274de', // Set event border color (optional)
        eventTextColor: '#ffffff', // Set event text color (optional)
        eventRender: function(event, element) {
            // Set custom background color for each event
            element.css('#ff8274de', event.backgroundColor);
        }
    });
});
</script>
<script>
    function showMore() {
        // Show the next 4 comments
        var comments = document.querySelectorAll('.media');
        for (var i = 4; i < comments.length; i++) {
            if (comments[i].classList.contains('d-none')) {
                comments[i].classList.remove('d-none');
            }
        }

        // Hide the "View More" button if there are no more hidden comments
        var hiddenComments = document.querySelectorAll('.media.d-none');
        if (hiddenComments.length === 0) {
            document.getElementById('viewMoreBtn').style.display = 'none';
        }
    }
</script>

<script>
    function showMore() {
        // Show the next 4 comments
        var comments = document.querySelectorAll('.media.d-none');
        for (var i = 0; i < 4; i++) {
            if (comments[i]) {
                comments[i].classList.remove('d-none');
            }
        }

        // Hide the "View More" button if there are no more hidden comments
        var hiddenComments = document.querySelectorAll('.media.d-none');
        if (hiddenComments.length === 0) {
            document.getElementById('viewMoreBtn').style.display = 'none';
        }
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('dateInput');
    const timeInput = document.getElementById('time');
    const tableSelect = document.getElementById('table_num');

    function fetchAvailableTables() {
        const date = dateInput.value;
        const time = timeInput.value;
        const restaurantId = "{{ $restaurant->id }}";

        if (date && time) {
            fetch(`/available-tables?restaurant_id=${restaurantId}&date=${date}&time=${time}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Response data:', data); // Log the response for debugging
                    tableSelect.innerHTML = '<option value="">Select a table</option>';
                    if (Array.isArray(data.available_tables)) {
                        data.available_tables.forEach(table => {
                            const option = document.createElement('option');
                            option.value = table;
                            option.textContent = `Table ${table}`;
                            tableSelect.appendChild(option);
                        });
                    } else {
                        console.error('available_tables is not an array', data);
                    }
                })
                .catch(error => console.error('Error fetching available tables:', error));
        } else {
            // Fetch and show all tables if date or time is not selected
            fetch(`/available-tables?restaurant_id=${restaurantId}`)
                .then(response => response.json())
                .then(data => {
                    tableSelect.innerHTML = '<option value="">Select a table</option>';
                    if (Array.isArray(data.all_tables)) {
                        data.all_tables.forEach(table => {
                            const option = document.createElement('option');
                            option.value = table;
                            option.textContent = `Table ${table}`;
                            tableSelect.appendChild(option);
                        });
                    } else {
                        console.error('all_tables is not an array', data);
                    }
                })
                .catch(error => console.error('Error fetching all tables:', error));
        }
    }

    dateInput.addEventListener('change', fetchAvailableTables);
    timeInput.addEventListener('change', fetchAvailableTables);
});
</script>
<script>
    function saveRestaurant(event, restaurantId) {
        event.preventDefault();
        fetch(`/save_restaurant/${restaurantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = event.target.classList.contains('fa') ? event.target : event.target.querySelector('.fa');
                icon.classList.toggle('fa-heart');
                icon.classList.toggle('fa-heart-o');
            } else {
                alert('Failed to save restaurant.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection
          