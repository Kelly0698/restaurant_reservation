<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurant Registration Request</title>

    <!-- Bootstrap CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap Custom File Input plugin -->
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <script src="{{ asset('assets/dist/js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script> 


  <style>
    .register-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Ensure the container takes up the full viewport height */
      width:200vh;
    }
    .register-form-1 {
        padding: 5%;
        background-color: rgba(255, 255, 255, 1.0); 
        width: 100%; 
    }
    .register-form-1 h3 {
      text-align: center;
      color: #333;
    }

    .register-container form {
      padding: 2.4%;
    }
    .btnSubmit {
      width: 50%;
      border-radius: 1rem;
      padding: 1.5%;
      border: none;
      cursor: pointer;
    }
    .register-form-1 .btnSubmit {
      font-weight: 600;
      color: #fff;
      background-color: #0062cc;
    }
    .register-form-2 .btnSubmit {
      font-weight: 600;
      color: #0062cc;
      background-color: #fff;
    }
    .register-form-1 .ForgetPwd {
      color: #0062cc;
      font-weight: 600;
      text-decoration: none;
    }
    body{
        background: url('{{ asset('assets/dist/img/restaurant_register.png') }}')center center;
    }
  </style>
</head>

<body>
<div class="container register-container">
    <div class="row">
        <div class="col-md-12 register-form-1"><br>
        <p class="mb-1" style="position: absolute; top: 4%; left: 3%;">
            <a href="#" onclick="window.history.back();" class="btn btn-sm">
            <i class="fas fa-arrow-left h4" style="color: #01356c"></i>
            </a>
        </p>
            <h3 style="color: #01356c;">Restaurant Registration Request For Future Reserve It</h3><br>
            <form id="restaurant_add" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Restaurant Name" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="phone_num" placeholder="Phone Number" name="phone_num" required>
                </div>   
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="toggle-password">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="toggle-password-confirm">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="address" name="address" placeholder="Restaurant Address" required>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="license_pdf" name="license_pdf" accept=".pdf" >
                            <label class="custom-file-label" for="license_pdf">Lisence PDF File (One Only)</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="logo_pic" name="logo_pic" accept="image/*">
                            <label class="custom-file-label" for="profile_pic">Restaurant Profile Picture</label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn" style="width: 70%; border-radius: 20px; background-color:#082A44; color:#ffffff">Send Registration Request</button> 
                    <div class="row justify-content-center">
                        <div class="col text-center">
                            <h2 class="d-inline small">Have account?</h2>
                            <span class="d-inline small" style="color: #002dce;"></span>
                            <a href="/login/restaurant" class="d-inline small" style="color: #002dce; font-weight: bold;">Login</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



@if(session('status')=="failed")
<script>                    
Swal.fire({
    title: 'Error!',
    text: 'Register failed, please try again!',
    icon: 'error',
});
</script>
@endif

@if(session('status')=="Pass")
<script>                    
Swal.fire({
    title: 'Success',
    text: 'We have emailed your password reset link!',
    icon: 'Success',
});
</script>
@endif

@if(session('status')=="Change")
<script>                    
Swal.fire({
    title: 'Success',
    text: 'Password has been reset successfully. Please register again!',
    icon: 'Success',
});
</script>
@endif

<script>
    $(document).ready(function() {
        bsCustomFileInput.init();
        
        // Optional: You can handle the change event to update the label with the selected file name
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop(); // Get the file name
            $(this).next('.custom-file-label').html(fileName); // Update the label with the file name
        });
    });
</script>

<script>
jQuery(document).ready(function($) {
    $('#restaurant_add').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/restaurant-register",
            beforeSend: function() {
                loadingModal();
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: 'info',
                    title: 'Registration Pending',
                    text: 'Your restaurant registration request is pending. Please allow 3-5 working days for processing.'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('restaurant_login_page') }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                var errorMessage = '';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else {
                    errorMessage = error;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
    });
});
</script>

<script>
    jQuery(document).ready(function($) {
        $("#toggle-password").click(function(){
            var passwordField = $("#password");
            var fieldType = passwordField.attr('type');
            passwordField.attr('type', fieldType === 'password' ? 'text' : 'password');
            $(this).find('i').toggleClass('fa-eye-slash fa-eye');
        });

        $("#toggle-password-confirm").click(function(){
            var passwordField = $("#password_confirmation");
            var fieldType = passwordField.attr('type');
            passwordField.attr('type', fieldType === 'password' ? 'text' : 'password');
            $(this).find('i').toggleClass('fa-eye-slash fa-eye');
        });

        // Function to validate password match
        $("#password_confirmation").on('keyup', function(){
            var password = $("#password").val();
            var confirmPassword = $(this).val();

            if(password === confirmPassword){
                $("#password_confirmation").removeClass('is-invalid');
                $("#password_confirmation").addClass('is-valid');
            } else {
                $("#password_confirmation").removeClass('is-valid');
                $("#password_confirmation").addClass('is-invalid');
            }
        });
    });
</script>

</body>
</html>
