@extends('layouts')
@section('title', 'Reservation Record')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            @foreach($reservations as $reservation)
            <div class="col-md-4 mb-3"> 
                <div class="card bg-light d-flex flex-fill">
                    <div class="card-body pt-0">
                        <div class="col-12">
                            <br>
                            <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2><br>
                            <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                            <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                            <p>Time: &nbsp{{ $reservation->time }}</p>
                            <p>Party Size: &nbsp{{ $reservation->party_size }}</p>
                            @if($reservation->remark)
                                <p>Remark: &nbsp{{ $reservation->remark }}</p>
                            @else
                                <p>Remark: &nbspNone</p>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($reservation->status === 'Approved')
                                <button class="btn" style="background-color:#36d2a3d7; border-radius: 20px !important;">Approved</button>
                            @elseif($reservation->status === 'Rejected')
                                <button class="btn" style="background-color:#ff8274de; border-radius: 20px !important;">Rejected</button>
                            @else
                                <button class="btn" style="color:red;">Pending...</button>
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
                            @else                     
                            @if($reservation->status == 'Pending')
                                <button class="btn" style="background-color:#fd001974; border-radius: 20px !important;" onclick="cancelReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}')">Cancel Reservation</button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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

    function cancelReservation(reservationId, userName) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to cancel the reservation ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send an AJAX request to cancel the reservation
                $.ajax({
                    url: '/cancel-reservation/' + reservationId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            title: 'Canceled!',
                            text: 'Your reservation has been canceled.',
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
                            text: 'Cancellation failed',
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
@endsection
