@extends('layouts')
@section('title', 'Rejected Reservation')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
    <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Rejected Reservation Record</h2><br>
        <div class="row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="{{ route('reject_page') }}" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control rounded-pill" placeholder="Search Record (User Name, Time, Party Size, Remark)" name="query" style="width: 50%;" value="{{ request('query') }}">
                            <input type="date" class="form-control rounded-pill" name="date" value="{{ request('date') }}">
                            <select class="form-control rounded-pill" name="sort">
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Asc</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Desc</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary yellow rounded-pill" type="submit">Search</button>
                                <a href="{{ route('reject_page') }}" class="btn btn-outline-secondary yellow rounded-pill" style="width: 50%;"><i class="fas fa-undo"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            @if($rejectedReservations->isEmpty())
            <div class="card blue text-center col-12">
                <div class="card-body">
                    <p class="m-0">No record found</p>
                </div>
            </div>
            @else
                @foreach($rejectedReservations as $reservation)
                <div class="col-md-4 mb-3"> 
                    <div class="card bg-light d-flex flex-fill">
                        <div class="card-body pt-0">
                            <div class="col-12">
                                <br>
                                <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2><br>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Phone Number: &nbsp{{$reservation->user->phone_num}}</p>
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
                            <div class="text-right">
                                @if($reservation->status === 'Rejected')
                                <button class="btn" style="background-color:#ff8274de; border-radius: 20px !important;">Rejected</button>
                                @else
                                <button class="btn">Pending...</button>
                                @endif
                            </div> 
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
