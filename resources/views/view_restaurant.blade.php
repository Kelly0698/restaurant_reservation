@extends('layouts')
@section('title','Restaurant Details')
@section('content')

<head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

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

    </style>
</head>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-12 mx-auto">
                <div class="card">
                    <div class="blue text-center lucida-handwriting" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <h3 style="font-size: 30px; margin-bottom: 0;">{{ $restaurant->name }}</h3>
                    </div>
                    <div class="row" id="image-row">
                        <!-- Display the first 3 images -->
                        @foreach($attachments->take(3) as $attachment)
                        <div class="col-md-4 mb-3">
                            <div class="position-relative image-container">
                                <img src="{{ asset('storage/res_pic/' . $attachment->picture) }}" alt="Attachment" class="img-fluid image-with-outline">
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
            </div>

            <div class="col-md-8 col-12 mx-auto">
                <div class="card">
                    <div class="blue" style="padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 0 solid rgba(0, 0, 0, 0.125);">
                        <h3 class="card-title">About Restaurant</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Address</strong>
                        <p class="text-muted">{{ $restaurant->address }}</p>
                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i>Contact</strong>
                        <p class="text-muted">{{ $restaurant->phone_num }}</p>
                        <hr>

                        <strong><i class="fas fa-clock mr-1"></i>Operation Time</strong>
                        <p class="text-muted">{{ $restaurant->operation_time }}</p>
                        <hr>

                        <strong><i class="fas fa-info-circle mr-1"></i>Description</strong>
                        <p class="text-muted">{{ $restaurant->description }}</p>
                        <hr> 
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-12 mx-auto">
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
                                <input type="text" name="date" class="form-control" id="dateInput" placeholder="Select date">
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" name="time" class="form-control" id="time">
                            </div>
                            <div class="form-group">
                                <label for="party_size">Party Size</label>
                                <input type="number" name="party_size" class="form-control" id="party_size" placeholder="Enter party size">
                            </div>
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


    $(function() {
        $("#dateInput").datepicker();
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
                    text: 'Reservation made successfully, check your email!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('view_restaurant', ['id' => $restaurant->id]) }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error making the reservation: ' + error
                });
            }
        });
    });

</script>
@endsection















            