@extends('layouts')
@section('title','User List')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <br>
        <div class="row">   
            <div class="col-12">
                <button type="button" class="col-2 btn btn-default btn-lg yellow" data-toggle="modal" data-target="#addModal">
                    Add User
                </button>
                <br><br>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User List</h3>

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
                                <th>Role</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            @foreach($user as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td style="display: flex; ">                                        
                                        @if ($item->profile_pic)
                                            <img src="{{ asset('storage') }}/{{ $item->profile_pic }}" alt="Profile Picture" width="30" height="30" style="border-radius: 50%; overflow: hidden;">
                                        @else
                                            <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Profile Picture" width="30" height="30" style="border-radius: 50%; overflow: hidden;">
                                        @endif
                                        &nbsp
                                        {{$item->name}}
                                    </td>
                                    <td>{{$item->role->role_name}}</td>
                                    <td>
                                        {{$item->email}}
                                    </td>
                                    <td>{{$item->phone_num}}</td>
                                    <td>
                                        <a class="btn yellow show_user" href="{{ url('/show/user') }}/{{ $item->id }}">
                                            <span class="fas fa-eye"></span>
                                        </a>
                                        <button style="font-size:16.5px;" type="button" data-id="{{$item->id}}" class="btn blue delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
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

<!-- Add User Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
  
            <div class="modal-body">
            <form id="user_add" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-form-label">Name:</label>
                    <input type="text" class="form-control" id="name" onchange="checkData()" name="name" required>
                    <div id="user_name_error" style="font-size:12px" class="text-danger"></div>
                </div>
                
                <div class="form-group">
                    <label for="role" class="col-form-label">Role Position:</label>
                    <select id="role_id" name="role_id" class="form-control" style="font-size: 1.2em;" required>
                        @foreach($role as $item2)
                            <option value="{{ $item2->id }}">{{ $item2->role_name }}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="form-group">
                    <label for="email" class="col-form-label">Email:</label>
                    <input type="email" class="form-control" id="email" onchange="checkData2(this)" name="email" required>
                    <div id="email_name_error" style="font-size:12px" class="text-danger"></div>
                </div>
            
                <div class="form-group">
                    <label for="phone_num" class="col-form-label">Phone Number:</label>
                    <input type="text" class="form-control" id="phone_num" onchange="checkData2(this)" name="phone_num" required>
                    <div id="phone_num_error" style="font-size:12px" class="text-danger"></div>
                </div>
                <div class="form-group">
                    <label for="profile_pic" class="col-form-label">Profile Picture:</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" accept="image/*">
                            <label class="custom-file-label" for="profile_pic">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn blue" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn yellow" >Add</button>
                </div>
            </form>
            </div>
        </div> 
    </div>
</div>     
<!-- End Add User Modal -->
@endsection

@section('scripts')
<script>
    function checkData(){ 
        var check = $('#name').val();
        var formData = new FormData();
        formData.append("name", check);
        formData.append("_token", "{{csrf_token()}}");
        console.log(check);
        //submit to backend
        $.ajax({
            type: "post",
            url: "/check/user", 
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("success");
                document.getElementById("user_name_error").innerHTML ="";
            },
            error: function (error) {
            document.getElementById("user_name_error").innerHTML ="*This Username Is Exist!";
            }
        });
    };

    function checkData2(input){ 
        var check = $(input).val();
        var formData = new FormData();
        var id = $(input).attr('id');

        if (id === 'email') {
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(check)) {
                document.getElementById("email_name_error").innerHTML ="*Invalid Email Format!";
                return;
            }
            document.getElementById("email_name_error").innerHTML ="";
            formData.append("email", check);
        } else if (id === 'phone_num') {
            var phonePattern = /^\+?[1-9]\d{1,14}$/;
            if (!phonePattern.test(check)) {
                document.getElementById("phone_num_error").innerHTML ="*Invalid Phone Number Format!";
                return;
            }
            document.getElementById("phone_num_error").innerHTML ="";
            formData.append("phone_num", check);
        }

        formData.append("_token", "{{csrf_token()}}");

        //submit to backend
        $.ajax({
            type: "post",
            url: "/check/user", 
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("success");
                if (id === 'email') {
                    document.getElementById("email_name_error").innerHTML ="";
                } else if (id === 'phone_num') {
                    document.getElementById("phone_num_error").innerHTML ="";
                }
            },
            error: function (error) {
                if (id === 'email') {
                    document.getElementById("email_name_error").innerHTML ="*This Email Is Exist!";
                } else if (id === 'phone_num') {
                    document.getElementById("phone_num_error").innerHTML ="*Invalid Phone Number Format!";
                }
            }
        });
    };

    $('#user_add').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        // Validate email format
        var email = $('#email').val();
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            document.getElementById("email_name_error").innerHTML ="*Invalid Email Format!";
            return;
        }

        // Validate phone number format
        var phoneNum = $('#phone_num').val();
        var phonePattern = /^\+?[1-9]\d{1,14}$/;
        if (!phonePattern.test(phoneNum)) {
            document.getElementById("phone_num_error").innerHTML ="*Invalid Phone Number Format!";
            return;
        }

        // Check for duplicate email
        $.ajax({
            type: "POST",
            url: "/check/user",
            data: { email: email, _token: "{{ csrf_token() }}" },
            success: function (response) {
                if (response.exists) {
                    document.getElementById("email_name_error").innerHTML = "*This Email Is Exist!";
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/add/user",
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
                                text: 'User added successfully!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('user_list') }}";
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was an error adding the user: ' + error
                            });
                        }
                    });
                }
            },
            error: function (error) {
                console.log(error);
                document.getElementById("email_name_error").innerHTML = "*Error checking email!";
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
                    url: "{{ url('delete/user') }}/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The item has been deleted.',
                            icon: 'success',
                        }).then((result) => {
                            window.location.href = "{{ route('user_list') }}";
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

