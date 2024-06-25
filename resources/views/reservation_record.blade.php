@extends('layouts')
@section('title', 'Reservation Record')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
    @if(Auth::guard('web')->check())
    <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Reservation Record</h2><br>
    @elseif(Auth::guard('restaurant')->check())
    <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Reservation Request</h2><br>
    @endif
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-5">
                        @if(Auth::guard('web')->check())
                        <div class="text-left">
                            <div class="dropdown">
                                <button class="btn yellow dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:20px; width: 120px; margin-top: 30px;">
                                    Filter
                                </button>
                                <div class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <a class="dropdown-item filter-btn active" href="#" data-filter="all">All Reservations</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="approved-completed">Approved (With Rating)</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="approved-pending">Pending Completion</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="rejected">Rejected</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <form action="{{ route('reservation_record') }}" method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <div class="form-group col-md-4 align-self-end d-flex justify-content-between">
                                    <button type="submit" class="btn blue btn-block" style="border-radius:20px; width: 150px;">Choose Date</button>
                                    <a href="{{ route('reservation_record') }}" class="btn btn-outline-secondary yellow rounded-pill align-self-end"><i class="fas fa-undo"></i></a>
                                </div>
                            </div> 
                        </form>
                        <br>
                    </div>

                    @if(Auth::guard('web')->check())
                    <div class="col-12 no-record-message" style="display: none;">
                        <br><br>
                        <div class="card blue text-center">
                            <div class="card-body">
                                <p class="m-0">No record found</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($reservations->isEmpty())
                    @if(Auth::guard('restaurant')->check())
                    <div class="col-12">
                        <div class="card blue text-center ">
                            <div class="card-body">
                                <p class="m-0">No User's Reservation Record</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @else
                    @foreach($reservations as $reservation)
                    @if(Auth::guard('web')->check() && ($reservation->status === 'Approved' || $reservation->status === 'Rejected'))
                    <div class="col-12 mb-3 reservation-card" data-status="{{ strtolower($reservation->status) }}" data-completeness="{{ strtolower($reservation->completeness) }}">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Phone Number: &nbsp{{$reservation->user->phone_num}}</p>
                                <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                                <p>Time: &nbsp{{ $reservation->time }}</p>
                                <p>Party Size: &nbsp{{ $reservation->party_size }}</p>
                                <p>Table: &nbsp{{ $reservation->table_num ?? 'None' }}</p>
                                <p>Remark: &nbsp{{ $reservation->remark ?? 'None' }}</p>
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
                                    @if($reservation->status === 'Approved' && \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time) < \Carbon\Carbon::now() && $reservation->completeness === 'Done')
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
                                                    <textarea name="comment" class="form-control" rows="3" placeholder="Any comment..." required></textarea>
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
                    <div class="col-12 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Phone Number: &nbsp{{$reservation->user->phone_num}}</p>
                                <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                                <p>Time: &nbsp{{ $reservation->time }}</p>
                                <p>Party Size: &nbsp{{ $reservation->party_size }}</p>
                                <p>Table: &nbsp{{ $reservation->table_num ?? 'None' }}</p>
                                <p>Remark: &nbsp{{ $reservation->remark ?? 'None' }}</p>
                                <p>Status:
                                    @if($reservation->status === 'Approved')
                                        <button class="btn btn-sm" style="background-color:#36d2a3d7; border-radius: 20px !important;">Approved</button>
                                    @elseif($reservation->status === 'Rejected')
                                        <button class="btn btn-sm" style="background-color:#ff8274de; border-radius: 20px !important;">Rejected</button>
                                    @else
                                        <span style="color: red;">Pending...</span>
                                    @endif
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    @if(Auth::guard('restaurant')->check())
                                        <a href="#" class="btn btn-md yellow" onclick="approveReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}', '{{ $reservation->user->phone_num }}', '{{ $reservation->user->message_type }}', '{{ $reservation->restaurant->name }}', '{{ $reservation->time }}', '{{ $reservation->date }}')">
                                            Approve
                                        </a>
                                        <a href="#" class="btn btn-md blue" onclick="rejectReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}', '{{ $reservation->user->phone_num }}', '{{ $reservation->user->message_type }}', '{{ $reservation->restaurant->name }}', '{{ $reservation->time }}', '{{ $reservation->date }}')">
                                            Reject
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const reservationCards = document.querySelectorAll('.reservation-card');
    const noRecordMessage = document.querySelector('.no-record-message');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filter = button.getAttribute('data-filter');
            let hasVisibleCard = false;

            reservationCards.forEach(card => {
                const status = card.getAttribute('data-status');
                const completeness = card.getAttribute('data-completeness');

                if (filter === 'all') {
                    card.style.display = 'block';
                    hasVisibleCard = true;
                } else if (filter === 'approved-completed') {
                    if (status === 'approved' && completeness === 'done') {
                        card.style.display = 'block';
                        hasVisibleCard = true;
                    } else {
                        card.style.display = 'none';
                    }
                } else if (filter === 'approved-pending') {
                    if (status === 'approved' && completeness !== 'done') {
                        card.style.display = 'block';
                        hasVisibleCard = true;
                    } else {
                        card.style.display = 'none';
                    }
                } else if (filter === 'rejected') {
                    if (status === 'rejected') {
                        card.style.display = 'block';
                        hasVisibleCard = true;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });

            if (!hasVisibleCard) {
                noRecordMessage.style.display = 'block';
            } else {
                noRecordMessage.style.display = 'none';
            }

            // Update active button state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // Initial check on page load
    checkNoRecordMessage();

    function checkNoRecordMessage() {
        let visibleCardsExist = false;
        reservationCards.forEach(card => {
            if (card.style.display !== 'none') {
                visibleCardsExist = true;
                return;
            }
        });

        if (!visibleCardsExist) {
            noRecordMessage.style.display = 'block';
        } else {
            noRecordMessage.style.display = 'none';
        }
    }
});
</script>
<script>
function approveReservation(reservationId, userName, userPhone, messageType, restaurantName, reservationTime, reservationDate) {
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
            $.ajax({
                url: '/approve-reservation/' + reservationId,
                beforeSend: function() {
                    loadingModal();
                },
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 'Approved'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Approved!',
                        text: 'The reservation for user ' + userName + ' has been approved.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (messageType === '["WhatsApp","Email"]' || messageType === '["WhatsApp"]') {
                            let message = `Hi, ${userName}, your reservation at ${restaurantName} has been approved. Details: ${reservationTime}, ${reservationDate}.`;
                            window.open(`https://web.whatsapp.com/send?phone=${userPhone}&text=${encodeURIComponent(message)}`);
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
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

function rejectReservation(reservationId, userName, userPhone, messageType, restaurantName, reservationTime, reservationDate) {
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
            $.ajax({
                url: '/reject-reservation/' + reservationId,
                beforeSend: function() {
                    loadingModal();
                },
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 'Rejected'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Rejected!',
                        text: 'The reservation for user ' + userName + ' has been rejected.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (messageType === '["WhatsApp","Email"]' || messageType === '["WhatsApp"]') {
                            let message = `Hi, ${userName}, your reservation at ${restaurantName} has been rejected. Date and Time will be: ${reservationDate}, ${reservationTime}.`;
                            window.open(`https://web.whatsapp.com/send?phone=${userPhone}&text=${encodeURIComponent(message)}`);
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Rejection failed',
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
