@extends('layouts')
@section('title', 'Restaurant Registration Requests')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        @php
            $pendingRestaurants = $restaurant->where('status', 'Pending');
        @endphp

        @if($pendingRestaurants->isEmpty())
            <div class="no-requests">
                <br><br>
                <h5 style="color:#6c757d">&nbsp;&nbsp; No Request Found!</h5>
            </div>
        @else
            @foreach($pendingRestaurants as $item)
            <div class="col-12 d-flex align-items-stretch flex-column restaurant-card" id="restaurant-card-{{ $item->id }}">
                <div class="card bg-light d-flex flex-fill">
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-7">
                                <br>
                                <h2 class="lead"><b>{{$item->name}}</b></h2>
                                <p class="text-muted text-sm"><b>Email: {{$item->email}}</b></p>
                                <p class="text-muted text-sm"><b>Phone Number: {{$item->phone_num}}</b></p>
                                <p class="text-muted text-sm"><b>Address: {{$item->address}}</b></p>
                                <p class="text-muted text-sm">
                                    <b>License:                                         
                                        @php
                                            $pdfName = pathinfo($item->license_pdf, PATHINFO_FILENAME);
                                            $pdfExtension = pathinfo($item->license_pdf, PATHINFO_EXTENSION);
                                            $pdfPath = Storage::url('license_pdf/' . $pdfName . '.' . $pdfExtension);
                                        @endphp
                                        <a href="{{ $pdfPath }}" target="_blank" id="pdf-text-{{ $loop->index }}">&nbsp;{{ $pdfName . '.' . $pdfExtension }}</a>
                                    </b>
                                </p>
                                <p class="text-muted text-sm"><b>Status: {{$item->status}}</b></p>
                            </div>
                            <div class="col-5">
                                <br><br>
                                <p>
                                    @if ($item->logo_pic)
                                        <img src="{{ asset('storage') }}/{{ $item->logo_pic }}" alt="Profile Picture" width="200" height="200" style="border-radius: 50%; overflow: hidden;">
                                    @else
                                        <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Profile Picture" width="200" height="200" style="border-radius: 50%; overflow: hidden;">
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="#" class="btn btn-sm yellow" onclick="approveRestaurant('{{ $item->id }}', '{{ $item->name }}')">Approve</a>
                            <a href="#" class="btn btn-sm blue" onclick="rejectRestaurant('{{ $item->id }}', '{{ $item->name }}')">Reject</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    function approveRestaurant(restaurantId, restaurantName) {
        Swal.fire({
            title: 'Are you sure?',
            html: 'Please enter <strong>approve</strong> below to approve the registration for restaurant ' + restaurantName + '.',
            input: 'text',
            inputPlaceholder: 'approve',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
            preConfirm: (value) => {
                if (value.trim().toLowerCase() === 'approve') {
                    return Promise.resolve();
                } else {
                    Swal.showValidationMessage('Incorrect input. Please enter "approve" to confirm.');
                    return Promise.reject();
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/update-status/' + restaurantId,
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
                            text: 'The registration for restaurant ' + restaurantName + ' has been approved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href = "{{ route('restaurant_req_list') }}";
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
                    }
                });
            }
        });
    }

    function rejectRestaurant(restaurantId, restaurantName) {
        Swal.fire({
            title: 'Are you sure?',
            html: 'Please enter <strong>reject</strong> below to reject the registration for restaurant ' + restaurantName + '.',
            input: 'text',
            inputPlaceholder: 'reject',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!',
            preConfirm: (value) => {
                if (value.trim().toLowerCase() === 'reject') {
                    return Promise.resolve();
                } else {
                    Swal.showValidationMessage('Incorrect input. Please enter "reject" to confirm.');
                    return Promise.reject();
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/update-status/' + restaurantId,
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
                            text: 'The registration for restaurant ' + restaurantName + ' has been rejected.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href = "{{ route('restaurant_req_list') }}";
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
                    }
                });
            }
        });
    }
</script>
@endsection
