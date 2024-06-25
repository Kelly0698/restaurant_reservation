@extends('layouts')
@section('title', 'Cancelled Reservations')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Cancelled Reservation Record</h2><br>
        <div class="row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="{{ route('view_cancel') }}" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control rounded-pill" placeholder="Search Record: Restaurant Name, Time, Party Size, Remark" name="query" style="width: 40%;" value="{{ request('query') }}">
                            <input type="date" class="form-control rounded-pill" name="start_date" style="width: 20%;" value="{{ request('start_date') }}">
                            <input type="date" class="form-control rounded-pill" name="end_date" style="width: 20%;" value="{{ request('end_date') }}">
                            <select class="form-control rounded-pill" name="sort" style="width: 10%;">
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Asc</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Desc</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary yellow rounded-pill" type="submit" style="width: 100%;">Search</button>
                                <a href="{{ route('view_cancel') }}" class="btn btn-outline-secondary yellow rounded-pill" style="width: 50%;"><i class="fas fa-undo"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            @if($canceledReservations->isEmpty())
            <div class="card blue text-center col-12">
                <div class="card-body">
                    <p class="m-0">No record found</p>
                </div>
            </div>
            @else
                @foreach($canceledReservations as $reservation)
                <div class="col-md-4 mb-3"> 
                    <div class="card bg-light d-flex flex-fill">
                        <div class="card-body pt-0">
                            <div class="col-12">
                                <br>
                                <div class="text-right">
                                    <button class="btn btn-secondary" disabled style="border-radius: 20px !important">Cancelled</button>
                                </div> 
                                <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2><br>
                                <p>User Name: &nbsp{{ $reservation->user->name }}</p>
                                <p>Phone Number: &nbsp{{$reservation->user->phone_num}}</p>
                                <p>Reservation Date: &nbsp{{ $reservation->date }}</p>
                                <p>Time: &nbsp{{ $reservation->time }}</p>
                                @if($reservation->table_num)
                                    <p>Table: Table&nbsp{{ $reservation->table_num}}</p>
                                @else
                                    <p>Table: &nbspNone</p>
                                @endif
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
            @endif
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($canceledReservations->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $canceledReservations->previousPageUrl() }} " style="color: #ff9c62;" aria-label="Previous">Previous</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($canceledReservations->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $canceledReservations->nextPageUrl() }}" style="color: #ff9c62;" aria-label="Next">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
