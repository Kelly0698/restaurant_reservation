@extends('layouts')
@section('title','Restaurant List')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <br>
        <div class="row">   
            <div class="col-12">
                <button type="button" class="col-2 btn btn-default btn-lg yellow" data-toggle="modal" data-target="#addModal">
                    Add Restaurant
                </button>
                <br><br>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Restaurant List</h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>License</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            @foreach($restaurant as $item)
                            @if($item->status == 'Approved')
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td style="display: flex; ">                                        
                                        @if ($item->logo_pic)
                                            <img src="{{ asset('storage') }}/{{ $item->logo_pic }}" alt="Profile Picture" width="30" height="30" style="border-radius: 50%; overflow: hidden;">
                                        @else
                                            <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Profile Picture" width="30" height="30" style="border-radius: 50%; overflow: hidden;">
                                        @endif
                                        &nbsp
                                        {{$item->name}}
                                    </td>
                                    <td>{{$item->email}}</td>
                                    <td>{{$item->phone_num}}</td>
                                    <td>
                                        @php
                                            $address = $item->address;
                                            $limitedAddress = substr($address, 0, 20); // Extract the first 20 characters
                                            if (strlen($address) > 20) {
                                                $limitedAddress .= '...';
                                            }
                                            echo $limitedAddress;
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            $pdfName = pathinfo($item->license_pdf, PATHINFO_FILENAME);
                                            $pdfExtension = pathinfo($item->license_pdf, PATHINFO_EXTENSION);
                                            $pdfPath = Storage::url('license_pdf/' . $pdfName . '.' . $pdfExtension);
                                        @endphp
                                        <a href="{{ $pdfPath }}" target="_blank" id="pdf-text-{{ $loop->index }}" style="display:block">&nbsp;{{ $pdfName . '.' . $pdfExtension }}</a>
                                    </td>
                                    <td>{{$item->status}}</td>
                                    <td>
                                        <a class="btn yellow show_user" href="{{ url('/show/restaurant') }}/{{ $item->id }}">
                                            <span class="fas fa-eye"></span>
                                        </a>
                                        <button style="font-size:16.5px;" type="button" data-id="{{$item->id}}" class="btn blue delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                            @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>     
    </div>
</div>

<!-- Add Restaurant Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Restaurant</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
  
            <div class="modal-body">
            <form id="restaurant_add" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email" class="col-form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" onchange="checkEmail(this)" required>
                    <div id="email_name_error" style="font-size:12px" class="text-danger"></div>
                </div>
                <div class="form-group">
                    <label for="phone_num" class="col-form-label">Phone Number:</label>
                    <input type="text" class="form-control" id="phone_num" name="phone_num" required>
                    <div id="phone_num_error" style="font-size:12px" class="text-danger"></div>
                </div>                  
                <div class="form-group">
                    <label for="address" class="col-form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="License" class="col-form-label">License:</label>
                    <span class="text-danger">*one PDF file only</span>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="license_pdf" name="license_pdf" accept=".pdf">
                            <label class="custom-file-label" for="license_pdf">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-form-label">Status:</label>
                    <select id="status" name="status" class="form-control" style="font-size: 1.2em;" required>
                        <option value="">Select status:</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn blue" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn yellow">Add</button>
                </div>
            </form>
            </div>
        </div> 
    </div>
</div>     
<!-- End Add Restaurant Modal -->
@endsection

@section('scripts')
<script>
    function checkEmail(input) {
        var email = input.value;
        var formData = new FormData();
        formData.append("email", email);
        formData.append("_token", "{{ csrf_token() }}");

        $.ajax({
            type: "POST",
            url: "{{ route('check.email') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.exists) {
                    document.getElementById("email_name_error").innerHTML = "*This Email Is Exist!";
                } else {
                    document.getElementById("email_name_error").innerHTML = "";
                }
            },
            error: function(xhr) {
                document.getElementById("email_name_error").innerHTML = "*Error checking email!";
            }
        });
    }

    $('#restaurant_add').submit(function(e) {
        e.preventDefault();

        var emailError = document.getElementById("email_name_error").innerHTML;
        if (emailError !== "") {
            return;
        }

        var phone_num = $('#phone_num').val();
        var phonePattern = /^[\d\s()+-]+$/;

        if (!phonePattern.test(phone_num)) {
            document.getElementById("phone_num_error").innerHTML = "*Invalid phone number format!";
            return;
        } else {
            document.getElementById("phone_num_error").innerHTML = "";
        }

        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/add/restaurant",
            beforeSend: function() {
                loadingModal();
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Restaurant added successfully!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('restaurant_list') }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error adding the restaurant: ' + error
                });
            }
        });
    });

    $('.delete-btn').click(function (e) {
        e.preventDefault(); // avoid executing the actual submit of the form.
        var id = $(this).data('id');
        console.log(id);
        Swal.fire({
            title: 'Are you sure?',
            html: 'Please enter <strong>delete</strong> below to confirm:',
            input: 'text',
            inputPlaceholder: 'delete',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            preConfirm: (value) => {
                if (value.trim().toLowerCase() === 'delete') {
                    return Promise.resolve();
                } else {
                    Swal.showValidationMessage('Incorrect input. Please enter "delete" to confirm.');
                    return Promise.reject();
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ url('delete/restaurant') }}/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The item has been deleted.',
                            icon: 'success',
                        }).then((result) => {
                            window.location.href = "{{ route('restaurant_list') }}";
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error deleting the item.',
                            icon: 'error',
                        });
                        console.log(error);
                    }
                });
            }
        });
    });
</script>
@endsection
