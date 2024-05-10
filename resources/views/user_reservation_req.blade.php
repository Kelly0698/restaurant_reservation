@extends('layouts')
@section('title', 'Reservation Record')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    @foreach($pendingReservations as $reservation)
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
                            </div>
                            <div class="card-footer">
                                <div class="text-right">                 
                                    @if($reservation->status == 'Pending')
                                        <button class="btn" style="background-color:#fd001974; border-radius: 20px !important;" onclick="cancelReservation('{{ $reservation->id }}', '{{ $reservation->user->name }}')">Cancel Reservation</button>
                                    @endif           
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    function cancelReservation(reservationId, userName) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to cancel the reservation for user ' + userName + '?',
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
