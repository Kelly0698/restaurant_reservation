@extends('layouts')

@section('title', 'Pending Reservations')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Pending Reservations</h2><br>
        <div class="row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="{{ route('pending_reservation') }}" method="GET">
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
                                        <a href="{{ route('pending_reservation') }}" class="btn btn-outline-secondary yellow" style="border-radius:20px; width: 40px; margin-top: 30px;"><i class="fas fa-undo"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div><br>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    @if($pendingReservations->isEmpty())
                        <div class="card blue text-center col-12">
                            <div class="card-body">
                                <p class="m-0">No record found</p>
                            </div>
                        </div>
                    @else
                        @foreach($pendingReservations as $reservation)
                            <div class="col-12 mb-3"> 
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h2 class="lead"><b>Reservation for: {{$reservation->restaurant->name}}</b></h2><br>
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
                    @endif
                </div>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                {{-- Previous Page Link --}}
                                @if ($pendingReservations->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pendingReservations->previousPageUrl() }} " style="color: #ff9c62;" aria-label="Previous">Previous</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($pendingReservations->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pendingReservations->nextPageUrl() }}" style="color: #ff9c62;" aria-label="Next">Next</a>
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
                            text: 'Your reservation has been cancelled.',
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
