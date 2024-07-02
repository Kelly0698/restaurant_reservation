@extends('layouts')
@section('title','Role List')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <br>
        <div class="row">   
            <div class="col-12">
                <!-- <button type="button" class="col-2 btn btn-default btn-lg yellow" data-toggle="modal" data-target="#addModal">
                    Add Role
                </button> -->
                <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Role List</h2><br>
                <br>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Role List</h2>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" name="table_search" id="table_search" class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: auto;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr style="text-align:center;">
                                    <th>#</th>
                                    <th>Role</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody id="roleTableBody">
                            @foreach($role as $item)
                                <tr style="text-align:center;">
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->role_name}}</td>
                                    <td>{{$item->level}}</td>
                                    <td>{{$item->status}}</td>
                    
                                    <!-- <td>
                                        <a class="btn yellow show_role" href="#" data-role-id="{{ $item->id }}">
                                            <span class="fas fa-eye"></span>
                                        </a>
                                        <button style="font-size:16.5px;" type="button" data-id="{{$item->id}}" class="btn blue delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td> -->
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>     
    </div>
</div>

<!-- Add Role Modal -->
<!-- <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
  
            <div class="modal-body">
            <form id="add_role">
                @csrf
                <div class="form-group">
                <label for="name" class="col-form-label">Role:</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control" id="role_name" name="role_name" required>
                <div id="role_name_error" class="text-danger" style="font-size:12px"></div>
                </div>
                <div class="form-group">
                <label for="level" class="col-form-label">Level:</label>
                <span class="text-danger">*</span>
                <input type="number" class="form-control" id="level" name="level" required>
                <div class="text-muted" style="font-size: 12px;">Level 1 is the lowest level (integer only)</div>
                </div>
                <div class="form-group">
                <label for="status" class="col-form-label">Status:</label>
                <select id="status" name="status" class="form-control" style="font-size: 1.2em;" required>
                    <option value="">Select status:</option>
                    <option value="Enable">Enable</option>
                    <option value="Disable">Disable</option>
                </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn blue" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn yellow" >Add</button>
                </div>
            </form>
            </div>
        </div> 
    </div>
</div>      -->
<!-- End Add Role Modal -->


<!-- Edit Role Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Role Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
  
            <div class="modal-body">
            <form id="edit-role-form">
                @csrf
                {{-- Pass the role id to the controller when submit form --}}
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="form-group">
                    <label for="role_name" class="col-form-label">Role:</label>
                    <p id="role-name-text" style="display:block">&nbsp{{ $item->role_name }}</p>
                    <input type="text" onchange="checkDataEdit(this)" class="form-control" name="role_name" id="role-name-input" value="{{ $item->role_name }}" style="display: none;">
                    <div id="role_name_error" class="text-danger" style="font-size:12px"></div>
                </div>

                <div class="form-group">
                    <label for="level" class="col-form-label">Level:</label>
                    <p id="level-text" style="display:block">&nbsp{{ $item->level }}</p>
                    <input type="number" class="form-control" name="level" id="level-input" value="{{ $item->level }}" style="display: none;">
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status:</label>
                    <p id="status-text" style="display:block">&nbsp{{ $item->status }}</p>
                    <select class="form-control" name="status" id="status-input" style="display: none;">
                        <option value="Enable" {{ $item->status == 'Enable' ? 'selected' : '' }}>Enable</option>
                        <option value="Disable" {{ $item->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                    </select>
                </div>

                <!-- <div class="d-flex justify-content-end mt-4">
                    <a href="/role" type="button" name="button" class="btn blue m-0">Back</a>
                    &nbsp
                    <button type="button" name="button" class="btn yellow m-0 ms-2" id="edit-btn">Edit</button>
                    <button type="submit" name="button" class="btn yellow m-0 ms-2" id="save-btn" style="display: none;">Save</button>
                </div> -->
            </form>
            </div>
        </div> 
    </div>
</div>     
<!-- End Edit Role Modal -->
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.table').DataTable({
        "paging": true,
        "searching": false,
        "ordering": false,
        "info": false,
        "lengthMenu": [5, 10, 20, 30]
    });
});
</script>

<script>
    function checkDataAdd(inputElement) {
        var check = inputElement.value;
        var formData = new FormData();
        formData.append("role_name", check);
        formData.append("_token", "{{csrf_token()}}");
        
        $.ajax({
            type: "POST",
            url: "/check/role",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                document.getElementById("role_name_error").innerHTML = "";
            },
            error: function (error) {
                document.getElementById("role_name_error").innerHTML = "*This Role Name Is Exist!";
            }
        });
    }

    $('#role_name').on('input', function() {
        checkDataAdd(this);
    });

    $('#add_role').submit(function(e) {
        e.preventDefault();
        
        var roleNameInput = $('#role_name');
        roleNameInput.val(roleNameInput.val().toUpperCase());
        
        $.ajax({
            type: "POST",
            url: "/create/role",
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Role added successfully!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('role_list') }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error adding the role: ' + error
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
                    url: "{{ url('delete/role') }}/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The item has been deleted.',
                            icon: 'success',
                        }).then((result) => {
                            window.location.href = "{{ route('role_list') }}";
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

    $(document).ready(function() {
        var roleIdToShow; // Variable to store the role ID when show role button is clicked

        // Handle click event on eye button
        $('.show_role').click(function(e) {
            e.preventDefault();
            
            // Get the ID of the role associated with this button
            roleIdToShow = $(this).data('role-id');

            // Fetch role details via AJAX request
            $.ajax({
                url: 'show/role/' + roleIdToShow, // Adjust the URL based on your route
                method: 'GET',
                success: function(response) {
                    // Populate the modal with role details
                    $('#role-name-text').text(response.role_name);
                    $('#level-text').text(response.level);
                    $('#status-text').text(response.status);

                    // Show the modal
                    $('#editModal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error case
                    console.error(error);
                }
            });
        });

        $('#edit-btn').on('click', function () {
        if (typeof roleIdToShow !== 'undefined') {
            $.ajax({
                url: 'get/role/' + roleIdToShow, // Adjust the URL based on your route
                method: 'GET',
                success: function(response) {
                    // Update input values with the fetched role information
                    $('#role-name-input').val(response.role_name);
                    $('#level-input').val(response.level);
                    $('#status-input').val(response.status);

                    // Show input fields and hide text fields
                    $('#role-name-text').hide();
                    $('#level-text').hide();
                    $('#status-text').hide();
                    $('#role-name-input').show();
                    $('#level-input').show();
                    $('#status-input').show();

                    // Hide edit button and show save button
                    $('#edit-btn').hide();
                    $('#save-btn').show();

                    // Toggle edit view mode and enable input fields
                    toggleEditViewMode();
                    enableInputFields();
                },
                error: function(xhr, status, error) {
                    console.error("Failed to fetch role details:", error);
                }
            });
        } else {
            console.error("Role ID is undefined.");
        }
    });

    $('.close').click(function(e) {
            e.preventDefault();
            $('#editModal').modal('hide'); // Close the modal
            
            // Show role information with display block
            $('#role-name-text').show();
            $('#level-text').show();
            $('#status-text').show();

            // Hide input fields with display none
            $('#role-name-input').hide();
            $('#level-input').hide();
            $('#status-input').hide();
            
            // Hide save button and show edit button
            $('#save-btn').hide();
            $('#edit-btn').show();
        });


    // Attach submit event to form
    $('#edit-role-form').on('submit', function (event) {
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(form[0]);

        if (typeof roleIdToShow !== 'undefined') {
            $.ajax({
                type: "POST",
                url: "{{url('edit/role')}}/" + roleIdToShow,
                beforeSend: function () {
                    loadingModal();
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Updated Successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            toggleEditViewMode();
                            disableInputFields(); // Call disableInputFields after successful update
                        }
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Updated fail',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    console.log(error);
                }
            });
        } else {
            console.error("Role ID is undefined.");
        }
    });
});
</script>
<script>
    $(document).ready(function(){
        $('#table_search').on('keyup', function(){
            let query = $(this).val();

            $.ajax({
                url: "{{ route('roles.search') }}",
                type: "GET",
                data: {'query': query},
                success: function(data){
                    $('#roleTableBody').html('');
                    if(data.length > 0){
                        $.each(data, function(index, role){
                            $('#roleTableBody').append(`
                                <tr style="text-align:center;">
                                    <td>${index + 1}</td>
                                    <td>${role.role_name}</td>
                                    <td>${role.level}</td>
                                    <td>${role.status}</td>
                                    <td>
                                        <a class="btn yellow show_role" href="#" data-role-id="${role.id}">
                                            <span class="fas fa-eye"></span>
                                        </a>
                                        <button style="font-size:16.5px;" type="button" data-id="${role.id}" class="btn blue delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        $('#roleTableBody').append('<tr><td colspan="5" style="text-align:center;">No results found</td></tr>');
                    }
                }
            });
        });
    });
</script>
@endsection
