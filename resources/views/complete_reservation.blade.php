@extends('layouts')
@section('title', 'Completed Reservations')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            @foreach($doneReservations as $reservation)
            <div class="col-md-4 mb-3"> 
                <div class="card bg-light d-flex flex-fill">
                    <div class="card-body pt-0">
                        <div class="col-12">
                            <br>
                            <div class="text-right">
                                <button class="btn btn-secondary" disabled style="border-radius: 20px !important">Done</button>
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
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
