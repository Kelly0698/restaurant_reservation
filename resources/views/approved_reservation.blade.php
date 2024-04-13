@extends('layouts')
@section('title', 'Approved Reservation')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            @foreach($approvedReservations as $reservation)
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
                            <!-- Button for Approve -->
                            @if($reservation->status === 'Approved')
                            <button class="btn" style="background-color:#36d2a3d7; border-radius: 20px !important;">Approved</button>
                            @else
                            <!-- Button for Pending -->
                            <button class="btn">Pending...</button>
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
