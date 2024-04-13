@extends('layouts')
@section('title','Show Restaurant')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">   
            <div class="col-lg-9 col-12 mx-auto">
                <div class="card card-body mt-4">
                    <h6 class="mb-0" style="font-size: 1.5em;">Restaurant Details</h6>
                    <hr class="horizontal dark my-3">
                    <form id="edit-restaurant-form">
                        @csrf
                        {{-- Pass the user id to the controller when submit form --}}
                        <input type="hidden" name="id" value="{{ $restaurant->id }}">

                        <div class="form-group">
                            <label for="name" class="col-form-label">Name</label>
                            <p id="name-text" style="display:block">&nbsp{{ $restaurant->name }}</p>
                            <input type="text" onchange="checkData(this)" class="form-control" name="name" id="name-input" value="{{ $restaurant->name }}" style="display: none;">
                            <div id="name_error" class="text-danger" style="font-size:12px"></div>
                        </div>
                        <div class="form-group">
                            <label for="email" style="font-size: 1em;">Email</label>
                            <p id="email-text" style="display:block">&nbsp{{ $restaurant->email }}</p>
                            <input style="display:none" type="email" class="form-control" name="email" id="email-input" value="{{ $restaurant->email }}" >
                        </div>
                        <div class="form-group">
                            <label for="phone_num" class="col-form-label">Phone Number</label>
                            <p id="phone-num-text" style="display:block">&nbsp{{ $restaurant->phone_num }}</p>
                            <input type="text" class="form-control" name="phone_num" id="phone-num-input" value="{{ $restaurant->phone_num }}" style="display: none;">
                        </div>
                        <div class="form-group">
                            <label for="address" style="font-size: 1em;">Address</label>
                            <p id="address-text" style="display:block">&nbsp{{ $restaurant->address }}</p>
                            <input style="display:none" type="text" class="form-control" name="address" id="address-input" value="{{ $restaurant->address }}" >
                        </div>

                        <div class="form-group">
                            <label for="license" class="col-form-label">License:</label>
                            @php
                                $pdfName = pathinfo($restaurant->license_pdf, PATHINFO_FILENAME);
                                $pdfExtension = pathinfo($restaurant->license_pdf, PATHINFO_EXTENSION);
                                $pdfPath = Storage::url('license_pdf/' . $pdfName . '.' . $pdfExtension);
                            @endphp
                            <a href="{{ $pdfPath }}" target="_blank" id="license_pdf-text" style="display:block">&nbsp;{{ $pdfName . '.' . $pdfExtension }}</a>                     
                            <div class="custom-file" style="display:none" id="license_pdf-input">
                                <input type="file" class="custom-file-input" name="license_pdf" accept=".pdf">
                                <label class="custom-file-label" for="license_pdf">&nbsp;{{ $pdfName . '.' . $pdfExtension }}</label>
                            </div>                 
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-form-label">Status:</label>
                            <p id="status-text" style="display:block">&nbsp{{ $restaurant->status }}</p>
                            <select class="form-control" name="status" id="status-input" style="display: none;">
                                <option value="Pending" {{ $restaurant->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ $restaurant->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            </select>
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
        </div> 
    </div>
</div>   
@endsection

@section('scripts')
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
        document.getElementById('status-text').style.display = "none";

        document.getElementById('name-input').style.display = "block";
        document.getElementById('email-input').style.display = "block";
        document.getElementById('phone-num-input').style.display = "block";
        document.getElementById('address-input').style.display = "block";
        document.getElementById('license_pdf-input').style.display = "block";
        document.getElementById('status-input').style.display = "block";

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
            url: "{{url('edit/restaurant/'.$restaurant->id)}}",
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
                    window.location.href = "{{ route('show_restaurant', ['id' => $restaurant->id]) }}";
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
@endsection