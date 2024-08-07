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
        .btn-eating {
            background-color: #ff9800d7;
            border-radius: 20px !important;
        }
        .btn-serving {
            background-color: #ff9800d7;
            border-radius: 20px !important;
        }
        .card-custom {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<div class="content-wrapper">
    <div class="container-fluid">
    <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Approved Reservation Record</h2><br>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('approve_page') }}" method="GET">
                    <div class="card p-3" style="background-color: #fff6ea;">
                        <div class="input-group mb-3">
                            <div class="row w-100">
                                <div class="col-md-4">
                                    <label for="query">Search Record</label>
                                    <input type="text" class="form-control rounded-pill" id="query" placeholder="Search Record (User Name, Time, Party Size, Remark)" name="query" value="{{ request('query') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control rounded-pill" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control rounded-pill" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="sort">Sort Order</label>
                                    <select class="form-control rounded-pill" id="sort" name="sort">
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Earliest</option>
                                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Latest</option>
                                    </select>
                                </div>
                                <div class="col-md-2 justify-content-between">
                                    <button class="btn btn-outline-secondary yellow" type="submit" style="border-radius:20px; width: 120px; margin-top: 30px;">Search</button>
                                    <a href="{{ route('approve_page') }}" class="btn btn-outline-secondary yellow" style="border-radius:20px; width: 40px; margin-top: 30px;"><i class="fas fa-undo"></i></a>
                                </div>
                            </div>
                        </div>
                    </div><br>
                </form>
            </div>
        </div>
        <div class="row">
        @if(count($approvedReservations) > 0)
            @foreach($approvedReservations as $reservation)
                @php
                    // Get current date and time
                    $currentDateTime = \Carbon\Carbon::now('Asia/Singapore');

                    // Parse reservation date and time
                    $reservationDateTime = \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time, 'Asia/Singapore');

                    // Check if reservation date and time have passed
                    $isPastReservation = $currentDateTime->greaterThanOrEqualTo($reservationDateTime);

                    // Determine card background color based on completeness and past status
                    $cardClass = $isPastReservation && $reservation->completeness !== 'Done' ? 'back' : 'bg-light';
                @endphp

                <div class="col-md-4 mb-3"> 
                    <div class="card {{ $cardClass }} d-flex flex-fill">
                        <div class="card-body pt-0">
                            <div class="col-12">
                                <br>
                                <div class="text-right">
                                    @if($reservation->completeness === 'Eating')
                                        <button class="btn btn-serving">Serving</button>
                                    @else
                                        <button class="btn btn-complete">Approved</button>
                                    @endif
                                </div> 
                                <!-- <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2><br> -->
                                <strong>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Phone Number: &nbsp{{$reservation->user->phone_num}}</p>
                                </strong>
                                <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                                <p>Time: &nbsp{{ $reservation->time }}</p>
                                <p>Party Size: &nbsp{{ $reservation->party_size }}</p>
                                @if($reservation->table_num)
                                    <p>Table: Table&nbsp{{ $reservation->table_num}}</p>
                                @else
                                    <p>Table: &nbspNone</p>
                                @endif
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
                                    <input type="hidden" name="completeness" id="completeness-{{ $reservation->id }}">
                                </form>
                                <div>
                                    @if($reservation->completeness !== 'Eating')
                                        <div class="row">
                                            <div class="col text-left">
                                                <button type="button" class="btn btn-eating" onclick="markAsEating({{ $reservation->id }})">Serving?</button>
                                            </div>
                                            <div class="col text-right">
                                                <button type="button" class="btn blue" onclick="confirmCompletion({{ $reservation->id }})">Completed?</button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-right">
                                            <button type="button" class="btn blue" onclick="confirmCompletion({{ $reservation->id }})">Completed?</button>
                                        </div>
                                    @endif
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
        @else
            <div class="card blue text-center col-12">
                <div class="card-body">
                    <p class="m-0">No record found</p>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmCompletion(reservationId) {
    Swal.fire({
        title: 'Complete Reservation',
        text: "Select an option:",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Customer Confirmed Absent',
        confirmButtonText: 'Customer Completed',
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('completeness-' + reservationId).value = 'Done';
            document.getElementById('complete-form-' + reservationId).submit();
            Swal.fire(
                'Success!',
                'The reservation has been marked as Completed.',
                'success'
            );
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            document.getElementById('completeness-' + reservationId).value = 'Confirmed Absent';
            document.getElementById('complete-form-' + reservationId).submit();
            Swal.fire(
                'Success!',
                'The reservation has been marked as Confirmed Absent.',
                'success'
            );
        }
    });
}

function markAsEating(reservationId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to mark this reservation as Serving.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff9800',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark as Serving!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('completeness-' + reservationId).value = 'Eating';
            document.getElementById('complete-form-' + reservationId).submit();
            Swal.fire(
                'Success!',
                'The reservation has been marked as Serving.',
                'success'
            );
        }
    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
