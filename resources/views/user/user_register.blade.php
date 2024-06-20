<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Account</title>

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
        background: url('{{ asset('assets/dist/img/register_back.png') }}')center center;
    }
  </style>
</head>

<body>
<div class="container register-container">
    <div class="row">
        <div class="col-md-12 register-form-1"><br>
            <h3 style="color: #01356c;">Register to Future Reserve It</h3><br>
            <form id="user_add" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" placeholder="Username" class="form-control" id="name" onchange="checkData()" name="name" required>
                    <div id="user_name_error" style="font-size:12px" class="text-danger"></div>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" onchange="checkData2(this)" placeholder="Email" name="email" required>
                    <div id="email_name_error" style="font-size:12px" class="text-danger"></div>
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
                    <input type="text" class="form-control" id="phone_num" onchange="checkData2(this)" placeholder="Phone Number: +60xxxxxxxxxx" name="phone_num" required>
                    <div id="phone_num_error" style="font-size:12px" class="text-danger"></div>
                </div>
                <!-- New Checkbox Group -->
                <div class="form-group">
                    <label>Preferred Message Type:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="message_type_whatsapp" name="message_type[]" value="WhatsApp">
                        <label class="form-check-label" for="message_type_whatsapp">WhatsApp</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="message_type_email" name="message_type[]" value="Email">
                        <label class="form-check-label" for="message_type_email">Email</label>
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn" style="width: 50%; border-radius: 20px; background-color:#ffde59;">Register Account</button> 
                    <div class="row justify-content-center">
                        <div class="col text-center">
                            <h2 class="d-inline small">Have account?</h2>
                            <span class="d-inline small" style="color: #002dce;"></span>
                            <a href="/login" class="d-inline small" style="color: #002dce;font-weight: bold;">Login</a>
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
    jQuery(document).ready(function($) {
        // Function to check data on name input change
        function checkData() { 
            var check = $('#name').val();
            var formData = new FormData();
            formData.append("name", check);
            formData.append("_token", "{{ csrf_token() }}");

            // Submit to backend
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
        }

        // Function to validate email and phone number
        function checkData2(input) { 
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
                var phonePattern = /^\+?[1-9]\d{1,14}$/; // Pattern requiring a '+' followed by digits
                if (!phonePattern.test(check)) {
                    document.getElementById("phone_num_error").innerHTML = "*Invalid Phone Number Format!";
                    return;
                }
                document.getElementById("phone_num_error").innerHTML = "";
                formData.append("phone_num", check);
            }

            formData.append("_token", "{{ csrf_token() }}");

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
        }

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

        // Form submission handler
        $('#user_add').submit(function(e) {
            e.preventDefault();

            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            
            // Check if passwords match
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords do not match!',
                });
                return; // Stop form submission
            }

            var email = $('#email').val();
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                document.getElementById("email_name_error").innerHTML ="*Invalid Email Format!";
                return;
            }

            // Validate phone number format
            var phoneNum = $('#phone_num').val();
            var phonePattern = /^\+[1-9]\d{1,14}$/;

            if (!phonePattern.test(phoneNum)) {
                document.getElementById("phone_num_error").innerHTML ="*Invalid Phone Number Format! Must be +xxxxxxxxxx";
                return;
            }

            // If passwords match, proceed with form submission
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "/register/user",
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
                        text: 'Register Account Successfully!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error for registration account. Please try again!'
                    });
                }
            });
        });

        // Call checkData and checkData2 on input change
        $('#name').on('input', checkData);
        $('#email').on('input', function() { checkData2(this); });
        $('#phone_num').on('input', function() { checkData2(this); });
    });
</script>


</body>
</html>
