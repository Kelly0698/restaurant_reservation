@extends('layouts')
@section('title', 'Reservation Record')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    @foreach($reservations as $reservation)
                    @if(Auth::guard('web')->check() && ($reservation->status === 'Approved' || $reservation->status === 'Rejected'))
                    <div class="col-12 mb-3"> 
                        <div class="card bg-light">
                            <div class="card-body">
                                <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                                <p>Time: &nbsp{{ $reservation->time }}</p>
                                <p>Party Size: &nbsp{{ $reservation->party_size }}</p>
                                @if($reservation->remark)
                                    <p>Remark: &nbsp{{ $reservation->remark }}</p>
                                @else
                                    <p>Remark: &nbspNone</p>
                                @endif
                                <p>Status:
                                    @if($reservation->status === 'Approved')
                                        <button class="btn btn-sm" style="background-color:#36d2a3d7; border-radius: 20px !important;">Approved</button>
                                    @elseif($reservation->status === 'Rejected')
                                        <button class="btn btn-sm" style="background-color:#ff8274de; border-radius: 20px !important;">Rejected</button>
                                    @else
                                        <span style="color: red;">Pending...</span>
                                    @endif
                                </p>
                                <div class="text-left">
                                    @if($reservation->status === 'Approved' && \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time) < \Carbon\Carbon::now())
                                        @if(!$reservation->rating)
                                        <div id="ratingForm_{{ $reservation->id }}" style="display: none;">
                                            <form class="rating-form" data-reservation-id="{{ $reservation->id }}">
                                                @csrf
                                                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                                <div class="form-group">
                                                    <label for="mark">Rating:</label>
                                                    <select name="mark" class="form-control">
                                                        <option value="1">1 (Lowest)</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5 (Highest)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="comment">Comment:</label>
                                                    <textarea name="comment" class="form-control" rows="3">Any comment...</textarea>
                                                </div>
                                                <button type="submit" class="btn yellow">Submit Rating</button>
                                            </form>
                                        </div>
                                        <div class="text-right">
                                            <button class="btn btn-primary" onclick="toggleRatingForm('{{ $reservation->id }}')" data-reservation-id="{{ $reservation->id }}" style="border-radius: 20px !important;">Rating</button>
                                        </div>
                                        @else
                                        <hr>
                                        <div class="existing-rating">
                                            <p><b>Rating:</b> {{ $reservation->rating->mark }}</p>
                                            <p><b>Comment:</b> {{ $reservation->rating->comment }}</p>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                @if($reservation->status == 'Pending')
                                    <button class="btn" style="background-color:#fd001974; border-radius: 20px !important;" onclick="cancelReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}')">Cancel Reservation</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @elseif(Auth::guard('restaurant')->check())
                    <!-- Show all reservations for restaurants -->
                    <div class="col-12 mb-3"> 
                        <div class="card bg-light">
                            <div class="card-body">
                                <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                                <p>Time: &nbsp{{ $reservation->time }}</p>
                                <p>Party Size: &nbsp{{ $reservation->party_size }}</p>
                                @if($reservation->remark)
                                    <p>Remark: &nbsp{{ $reservation->remark }}</p>
                                @else
                                    <p>Remark: &nbspNone</p>
                                @endif
                                <p>Status:
                                    @if($reservation->status === 'Approved')
                                        <button class="btn btn-sm" style="background-color:#36d2a3d7; border-radius: 20px !important;">Approved</button>
                                    @elseif($reservation->status === 'Rejected')
                                        <button class="btn btn-sm" style="background-color:#ff8274de; border-radius: 20px !important;">Rejected</button>
                                    @else
                                        <span style="color: red;">Pending...</span>
                                    @endif
                                </p>
                                <div class="text-left">
                                    @if($reservation->status === 'Approved' && \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time) < \Carbon\Carbon::now())
                                        @if(!$reservation->rating)
                                        <div id="ratingForm_{{ $reservation->id }}" style="display: none;">
                                            <form class="rating-form" data-reservation-id="{{ $reservation->id }}">
                                                @csrf
                                                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                                <div class="form-group">
                                                    <label for="mark">Rating:</label>
                                                    <select name="mark" class="form-control">
                                                        <option value="1">1 (Lowest)</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5 (Highest)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="comment">Comment:</label>
                                                    <textarea name="comment" class="form-control" rows="3">Any comment...</textarea>
                                                </div>
                                                <button type="submit" class="btn yellow">Submit Rating</button>
                                            </form>
                                        </div>
                                        <div class="text-right">
                                            <button class="btn btn-primary" onclick="toggleRatingForm('{{ $reservation->id }}')" data-reservation-id="{{ $reservation->id }}" style="border-radius: 20px !important;">Rating</button>
                                        </div>
                                        @else
                                        <hr>
                                        <div class="existing-rating">
                                            <p><b>Rating:</b> {{ $reservation->rating->mark }}</p>
                                            <p><b>Comment:</b> {{ $reservation->rating->comment }}</p>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    @if(Auth::guard('restaurant')->check())
                                        <a href="#" class="btn btn-md yellow" onclick="approveReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}')">
                                            Approve
                                        </a>
                                        <a href="#" class="btn btn-md blue" onclick="rejectReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}')">
                                            Reject
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    function approveReservation(reservationId, userName) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to approve the reservation for user ' + userName + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send an AJAX request to update the status
                $.ajax({
                    url: '/approve-reservation/' + reservationId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'Approved'
                    },
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            title: 'Approved!',
                            text: 'The reservation for user ' + userName + ' has been approved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.reload(); // Reload the page to reflect the changes
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if any
                        Swal.fire({
                            title: 'Error!',
                            text: 'Approval failed',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error(xhr.responseText);
                        console.log(error);
                    }
                });
            }
        });
    }

    function rejectReservation(reservationId, userName) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to reject the reservation for user ' + userName + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send an AJAX request to update the status
                $.ajax({
                    url: '/reject-reservation/' + reservationId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'Rejected'
                    },
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            title: 'Rejected!',
                            text: 'The reservation for user ' + userName + ' has been rejected.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.reload(); // Reload the page to reflect the changes
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if any
                        Swal.fire({
                            title: 'Error!',
                            text: 'Rejected failed',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error(xhr.responseText);
                        console.log(error);
                    }
                });
            }
        });
    }
</script>

<script>
    function toggleRatingForm(reservationId) {
        var ratingForm = document.getElementById('ratingForm_' + reservationId);
        if (ratingForm.style.display === "none") {
            ratingForm.style.display = "block";
        } else {
            ratingForm.style.display = "none";
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('.rating-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var reservationId = $(this).data('reservation-id');
            $.ajax({
                type: "POST",
                url: "/ratings",
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
                        text: 'Successfully commented on the restaurant!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error adding the rate: ' + error
                    });
                }
            });
        });
    });
</script>
@endsection
