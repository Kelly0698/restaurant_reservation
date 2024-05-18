@extends('layouts')
@section('title', 'Approved Reservations')
@section('content')
<head>
    <style>
        .back {
            background-color: #eec3c774;
        }
        .btn-complete {
            background-color: #36d2a3d7;
            border-radius: 20px !important;
        }
        .btn-pending {
            background-color: #ccc;
        }
    </style>
    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            @foreach($approvedReservations as $reservation)
            @php
                $currentDateTime = \Carbon\Carbon::now();
                $reservationDateTime = \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time);
                $isPastReservation = $reservationDateTime->isPast();
                
                if ($isPastReservation && $reservation->completeness === 'Pending') {
                    $reservation->completeness = 'No_Show';
                    $reservation->save();
                }

                $cardClass = $isPastReservation && $reservation->completeness !== 'Done' ? 'back' : 'bg-light';
            @endphp
            <div class="col-md-4 mb-3"> 
                <div class="card {{ $cardClass }} d-flex flex-fill">
                    <div class="card-body pt-0">
                        <div class="col-12">
                            <br>
                            <div class="text-right">
                                @if($reservation->status === 'Approved')
                                <button class="btn btn-complete">Approved</button>
                                @else
                                <button class="btn btn-pending">Pending...</button>
                                @endif
                            </div> 
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
                        <hr>
                        @if($reservation->completeness !== 'Done')
                        <form id="complete-form-{{ $reservation->id }}" action="{{ route('update_completeness', $reservation->id) }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <div class="text-right">
                            <button type="button" class="btn blue" onclick="confirmCompletion({{ $reservation->id }})">Completed?</button>
                        </div>
                        @else
                        <div class="text-right">
                            <button class="btn btn-secondary" disabled>Done</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmCompletion(reservationId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to mark this reservation as complete.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, complete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('complete-form-' + reservationId).submit();
            }
        })
    }
</script>
@endsection
