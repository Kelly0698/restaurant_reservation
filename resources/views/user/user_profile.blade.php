@extends('layouts')
@section('title','User Profile')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="col-lg-7 mx-auto">
            <div class="card card-outline">
                <div class="card-body box-profile">
                    <form id="edit-user-form">
                    @csrf
                        <div class="text-center">
                            <h6 class="mb-0" style="font-size: 1.5em;">User Profile</h6>
                        </div>
                        <div class="form-group"> 
                            <br>
                            <div class="text-center">
                                <div id="profile-pic-img" style="position:relative ">&nbsp
                                    @if (Auth::user()->profile_pic)
                                        <img src="{{ asset('storage') }}/{{ Auth::user()->profile_pic }}" width="170" height="170" style="border-radius: 50%; overflow: hidden; border: 3px solid #adb5bd; margin: 0 auto; padding: 3px;">
                                    @else
                                        <img src="{{ asset('assets/dist/img/defaultPic.png') }}" width="170" height="170" style="border-radius: 50%; overflow: hidden; border: 3px solid #adb5bd; margin: 0 auto; padding: 3px;">
                                    @endif
                                    <a style="display:none; position:absolute; top:80%; left:56%" id="profile_pic-input" class="save-btn btn btn-sm btn-icon-only yellow" data-bs-toggle="modal" data-bs-target="#profile_pic">
                                        <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Image" aria-label="Edit Image"></i>
                                        <span class="sr-only">Edit Image</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark my-3">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Name</label>
                            <p id="name-text" style="display:block">&nbsp{{ Auth::user()->name }}</p>
                            <input type="text" onchange="checkData(this)" class="form-control" name="name" id="name-input" value="{{ Auth::user()->name }}" style="display: none;">
                            <div id="name_error" class="text-danger" style="font-size:12px"></div>
                        </div>

                        <div class="form-group">
                            <label for="email" style="font-size: 1em;">Email</label>
                            <p id="email-text" style="display:block">&nbsp{{ Auth::user()->email }}</p>
                            <input style="display:none" type="email" class="form-control" name="email" id="email-input" value="{{ Auth::user()->email }}" >
                        </div>

                        <div class="form-group">
                            <label for="phone_num" class="col-form-label">Phone Number</label>
                            <p id="phone-num-text" style="display:block">&nbsp{{ Auth::user()->phone_num }}</p>
                            <input type="text" class="form-control" name="phone_num" id="phone-num-input" value="{{ Auth::user()->phone_num }}" style="display: none;">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="/user" type="button" name="button" class="btn blue m-0">Back</a>
                            &nbsp
                            <button type="button" name="button" class="btn yellow m-0 ms-2" id="edit-btn">Edit</button>
                            <button type="submit" name="button" class="btn yellow m-0 ms-2" id="save-btn" style="display: none;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Pic Modal -->
<div class="modal fade" id="profile_pic" tabindex="-1" role="dialog" aria-labelledby="profile_picTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Profile Picture</h5>
            </div>

            <form id="profile_picture_update" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                    <label class="col-form-label">Profile Picture:</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" accept="image/*">
                                <label class="custom-file-label" for="profile_pic">Choose file</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light blue" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn yellow">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> 
<!-- End Edit Profile Pic Modal -->

@endsection

@section('scripts')
<script>
function checkData(){ 
    var check = $('#name-input').val();
    var formData = new FormData();
    formData.append("name", check);
    formData.append("_token", "{{csrf_token()}}");
    console.log(check);
    //submit to backend
    $.ajax({
              type: "post",
              url: "/check_user", 
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

$(function(){
    // Attach click event to edit button
    $('#edit-btn').on('click', function() {
        // Show input fields and hide text fields
        document.getElementById('name-text').style.display = "none";

        document.getElementById('email-text').style.display = "none";
        document.getElementById('phone-num-text').style.display = "none";

        document.getElementById('name-input').style.display = "block";

        document.getElementById('email-input').style.display = "block";
        document.getElementById('phone-num-input').style.display = "block";
        document.getElementById('profile_pic-input').style.display = "block";

        // Hide edit button and show save button
        document.getElementById('edit-btn').style.display = "none";
        document.getElementById('save-btn').style.display = "block";

        toggleEditViewMode();
        enableInputFields();
    });

    // Attach submit event to form
    $('#edit-user-form').on('submit', function(event) {
       
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(form[0]);
        
        $.ajax({
            type: "POST",
            url: "{{url('edit/user/'.Auth::user()->id)}}",
            data: formData,
            beforeSend: function() {
                loadingModal();
            },
            processData: false,
            contentType: false,
            success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: 'Updated Successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('user_profile', ['id' => Auth::user()->id]) }}";
                }
            });
        },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Updated fail',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                console.log(error);
            }
        });
    });
});
</script>

<script>
    $(document).ready(function() {
        // Attach click event to profile picture edit button
        $('#profile_pic-input').on('click', function() {
            $('#profile_pic').modal('show'); // Show the profile picture modal
        });

        // Handle profile picture update form submission
        $('#profile_picture_update').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = new FormData(this); // Get form data
            var userId = {{ Auth::user()->id}}; // Get user ID

            $.ajax({
                type: "POST",
                url: "{{url('/update-profile-pic')}}/{{Auth::user()->id}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // If update successful, show success message and reload the page
                    Swal.fire({
                        title: 'Success!',
                        text: 'Image updated successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#profile_pic').modal('hide'); // Hide the modal
                            location.reload(); // Reload the page
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // If there's an error, show error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update image',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endsection
