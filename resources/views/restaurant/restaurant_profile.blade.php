@extends('layouts')
@section('title','Restaurant Profile')
@section('content')
<head>
    <style>
        .custom-file-label::after {
            content: none !important;
        }
        .image-container {
            position: relative;
        }

        .delete-button {
            position: absolute;
            top: 0;
            right: 0;
            height: 25px; 
            width: 25px; 
            padding: 0; 
            font-size: 20px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }
        .image-with-outline {
            border: 1px solid #ccc; 
            border-radius: 5px; 
            padding: 5px; 
        }
    </style>
</head>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="col-lg-10 mx-auto">
            <div class="card card-outline">
                <div class="card-body box-profile">
                    <form id="edit-restaurant-form">
                    @csrf
                        <div class="text-center">
                            <h6 class="mb-0" style="font-size: 1.5em;">Restaurant Profile</h6>
                        </div>
                        <div class="form-group"> 
                            <br>
                            <div class="text-center">
                                <div id="profile-pic-img" style="position:relative ">&nbsp
                                    @if (Auth::guard('restaurant')->user()->logo_pic)
                                        <img src="{{ asset('storage') }}/{{ Auth::guard('restaurant')->user()->logo_pic }}" width="170" height="170" style="border-radius: 50%; overflow: hidden; border: 3px solid #adb5bd; margin: 0 auto; padding: 3px;">
                                    @else
                                        <img src="{{ asset('assets/dist/img/defaultPic.png') }}" width="170" height="170" style="border-radius: 50%; overflow: hidden; border: 3px solid #adb5bd; margin: 0 auto; padding: 3px;">
                                    @endif
                                    <a style="display:none; position:absolute; top:80%; left:56%" id="logo_pic-input" class="save-btn btn btn-sm btn-icon-only yellow" data-bs-toggle="modal" data-bs-target="#logo_pic">
                                        <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Image" aria-label="Edit Image"></i>
                                        <span class="sr-only">Edit Image</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark my-3">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Name</label>
                            <p id="name-text" style="display:block">&nbsp{{ Auth::guard('restaurant')->user()->name }}</p>
                            <input type="text" class="form-control" name="name" id="name-input" value="{{ Auth::guard('restaurant')->user()->name }}" style="display: none;">
                            <div id="name_error" class="text-danger" style="font-size:12px"></div>
                        </div>
                        <div class="form-group">
                            <label for="email" style="font-size: 1em;">Email</label>
                            <p id="email-text" style="display:block">&nbsp{{ Auth::guard('restaurant')->user()->email }}</p>
                            <input style="display:none" type="email" class="form-control" name="email" id="email-input" value="{{ Auth::guard('restaurant')->user()->email }}" >
                        </div>
                        <div class="form-group">
                            <label for="phone_num" class="col-form-label">Phone Number</label>
                            <p id="phone-num-text" style="display:block">&nbsp{{ Auth::guard('restaurant')->user()->phone_num }}</p>
                            <input type="text" class="form-control" name="phone_num" id="phone-num-input" value="{{ Auth::guard('restaurant')->user()->phone_num }}" style="display: none;">
                        </div>
                        <div class="form-group">
                            <label for="address" style="font-size: 1em;">Address</label>
                            <p id="address-text" style="display:block">&nbsp{{ Auth::guard('restaurant')->user()->address }}</p>
                            <input style="display:none" type="text" class="form-control" name="address" id="address-input" value="{{ Auth::guard('restaurant')->user()->address }}" >
                        </div>
                        <div class="form-group">
                            <label for="license" class="col-form-label">License:</label>
                            @php
                                $pdfName = pathinfo(Auth::guard('restaurant')->user()->license_pdf, PATHINFO_FILENAME);
                                $pdfExtension = pathinfo(Auth::guard('restaurant')->user()->license_pdf, PATHINFO_EXTENSION);
                                $pdfPath = Storage::url('license_pdf/' . $pdfName . '.' . $pdfExtension);
                            @endphp
                            <a href="{{ $pdfPath }}" target="_blank" id="license_pdf-text" style="display:block">&nbsp;{{ $pdfName . '.' . $pdfExtension }}</a>   
                            <div class="custom-file" style="display:none" id="license_pdf-input">
                                <input type="file" class="custom-file-input" name="license_pdf" accept=".pdf">
                                <label class="custom-file-label" for="license_pdf">&nbsp;{{ $pdfName . '.' . $pdfExtension }}</label>
                            </div>           
                        </div>
                        <div class="form-group">
                            <label for="operation_time" style="font-size: 1em;">Operation Time</label>
                            <p id="operation_time-text" style="display:block">&nbsp{{ Auth::guard('restaurant')->user()->operation_time }}</p>
                            <input style="display:none" type="text" class="form-control" name="operation_time" id="operation_time-input" value="{{ Auth::guard('restaurant')->user()->operation_time }}" >
                        </div>
                        <div class="form-group">
                            <label for="availability" style="font-size: 1em;">Availability</label>
                            <p id="availability-text" style="display: block;">&nbsp;{{ Auth::guard('restaurant')->user()->availability }}</p>
                            <select class="form-control" name="availability" id="availability-input" style="display: none;">
                                <option value="Yes" {{ Auth::guard('restaurant')->user()->availability == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ Auth::guard('restaurant')->user()->availability == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description" style="font-size: 1em;">Description</label>
                            @php
                                $description = Auth::guard('restaurant')->user()->description;
                            @endphp
                            <p id="description-text" style="display:block">
                                {{ $description ? $description : 'None' }}
                            </p>
                            <input style="display:none" type="text" class="form-control" name="description" id="description-input" value="{{ $description }}">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="/restaurant" type="button" name="button" class="btn blue m-0">Back</a>
                            &nbsp
                            <button type="button" name="button" class="btn yellow m-0 ms-2" id="edit-btn">Edit</button>
                            <button type="submit" name="button" class="btn yellow m-0 ms-2" id="save-btn" style="display: none;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <h6 class="mb-0" style="font-size: 1.5em;">Restaurant Pictures</h6>
                    </div>
                    <hr class="horizontal dark my-3">
                    
                    <h6 class="mb-0" style="font-size: 1.0em;">Your restaurant pictures:</h6>
                    <br>
                    <div class="row">
                        @foreach($restaurant->attachments as $attachment)
                        <div class="col-md-4 mb-3">
                            <div class="position-relative image-container" style="max-height: 200px;">
                                <img src="{{ asset('storage/res_pic/' . $attachment->picture) }}" alt="Attachment" class="img-fluid image-with-outline" style="max-height: 200px;">
                                <!-- Button to delete photo -->
                                <button type="button" class="btn delete-button delete-btn" data-id="{{ $attachment->id }}">x</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <form id="image-upload-form" method="POST" action="{{ route('upload_picture') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ Auth::guard('restaurant')->user()->id }}">
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="images[]" id="images" multiple accept="image/*">
                                <label class="custom-file-label" for="images">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <button type="submit" class="btn yellow">Start Upload</button>
                            </div>
                        </div>
                        <span id="upload-status" class="mb-2">*Make sure you click "Start Upload" after selecting images</span>
                        @if(isset($previewImages))
                            @foreach($previewImages as $previewImage)
                                <input type="hidden" name="preview_images[]" value="{{ $previewImage }}">
                            @endforeach
                        @endif
                    </form>
                    <div id="image-preview-container">
                        <!-- Images will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>   

<!-- Edit Profile Pic Modal -->
<div class="modal fade" id="logo_pic" tabindex="-1" role="dialog" aria-labelledby="logo_picTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Profile Picture</h5>
            </div>
            <form id="logo_picture_update" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                    <label class="col-form-label">Profile Picture:</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="logo_pic" name="logo_pic" accept="image/*">
                                <label class="custom-file-label" for="logo_pic">Choose file</label>
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
    document.getElementById('images').addEventListener('change', function() {
        var files = this.files;
        var label = document.querySelector('.custom-file-label');
        var status = document.getElementById('upload-status');

        if (files.length > 0) {
            label.innerText = files.length + (files.length > 1 ? ' files selected' : ' file selected');
            status.innerText = ''; // Clear the upload status message
        } else {
            label.innerText = 'Choose file';
            status.innerText = '*Make sure you click "Start Upload" after selecting images';
        }
    });


    // Function to display uploaded images
    function previewImages() {
        var previewContainer = document.getElementById('image-preview-container');
        var files = document.getElementById('images').files;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();

            reader.onload = (function(file) {
                return function(e) {
                    var image = document.createElement('img');
                    image.src = e.target.result;
                    image.classList.add('preview-image');

                    var deleteButton = document.createElement('button');
                    deleteButton.innerText = 'x';
                    deleteButton.classList.add('delete-button');
                    deleteButton.onclick = function() {
                        this.parentNode.remove(); // Remove the image preview when delete button is clicked
                    };

                    var imageContainer = document.createElement('div');
                    imageContainer.classList.add('image-container');
                    imageContainer.appendChild(image);
                    imageContainer.appendChild(deleteButton);

                    previewContainer.appendChild(imageContainer);

                    // Get base64 data of the preview image and send it to the server
                    var base64Data = e.target.result.split(',')[1]; // Extract base64 data (remove data:image/jpeg;base64,)
                    sendImageToServer(base64Data);

                     uploadedImages++; // Increment the count of uploaded images

                    // Check if all images have been uploaded
                    if (uploadedImages === files.length) {
                        // Show SweetAlert notification
                        Swal.fire({
                            title: 'Upload Success!',
                            text: 'All pictures have been uploaded successfully!',
                            icon: 'success',
                            timer: 3000, // Close alert after 3 seconds
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    }
                };
            })(file);

            reader.readAsDataURL(file);
        }
    }

    // Function to send base64 image data to the server
    function sendImageToServer(base64Data) {
        // Create a hidden input field to store base64 data
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'preview_images[]'; // Use 'preview_images[]' as the name to store multiple images
        hiddenInput.value = base64Data;

        // Append the hidden input field to the form
        document.getElementById('image-upload-form').appendChild(hiddenInput);
    }

    // Trigger previewImages function when file input changes
    document.getElementById('images').addEventListener('change', previewImages);

</script>

<script>
    $('.delete-btn').click(function (e) {
        e.preventDefault(); // avoid executing the actual submit of the form.
        var id = $(this).data('id');
        console.log(id);
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ url('pic-delete') }}/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The item has been deleted.',
                            icon: 'success',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('restaurant_profile') }}";
                            }
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

<script>
$(function(){
    // Attach click event to edit button
    $('#edit-btn').on('click', function() {
        // Show input fields and hide text fields
        document.getElementById('name-text').style.display = "none";
        document.getElementById('email-text').style.display = "none";
        document.getElementById('phone-num-text').style.display = "none";
        document.getElementById('address-text').style.display = "none";
        document.getElementById('license_pdf-text').style.display = "none";
        document.getElementById('operation_time-text').style.display = "none";
        document.getElementById('availability-text').style.display = "none";
        document.getElementById('description-text').style.display = "none";

        document.getElementById('name-input').style.display = "block";
        document.getElementById('email-input').style.display = "block";
        document.getElementById('phone-num-input').style.display = "block";
        document.getElementById('address-input').style.display = "block";
        document.getElementById('license_pdf-input').style.display = "block";
        document.getElementById('logo_pic-input').style.display = "block";
        document.getElementById('operation_time-input').style.display = "block";
        document.getElementById('availability-input').style.display = "block";
        document.getElementById('description-input').style.display = "block";

        // Hide edit button and show save button
        document.getElementById('edit-btn').style.display = "none";
        document.getElementById('save-btn').style.display = "block";

        toggleEditViewMode();
        enableInputFields();
    });

    // Attach submit event to form
    $('#edit-restaurant-form').on('submit', function(event) {
       
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(form[0]);
        
        $.ajax({
            type: "POST",
            url: "{{url('edit/restaurant/'.Auth::guard('restaurant')->user()->id)}}",
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
                    window.location.href = "{{ route('restaurant_profile', ['id' => Auth::guard('restaurant')->user()->id]) }}";
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
        $('#logo_pic-input').on('click', function() {
            $('#logo_pic').modal('show'); // Show the profile picture modal
        });

        // Handle profile picture update form submission
        $('#logo_picture_update').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = new FormData(this); // Get form data
            var userId = {{ Auth::guard('restaurant')->user()->id }};

            $.ajax({
                type: "POST",
                url: "{{url('/logo-pic/update/')}}/{{Auth::guard('restaurant')->user()->id}}",
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
                            $('#logo_pic').modal('hide'); // Hide the modal
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