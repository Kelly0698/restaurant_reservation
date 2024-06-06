<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Forms</title>

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
    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Ensure the container takes up the full viewport height */
      width:300vh;
    }
    .login-form-1 {
      padding: 5%;
      background-color: rgba(255, 255, 255, 1.0);
      width: 100%;
    }
    .login-form-1 h3 {
      text-align: center;
      color: #333;
    }
    .login-container form {
      padding: 2.4%;
    }
    .btnSubmit {
      width: 50%;
      border-radius: 1rem;
      padding: 1.5%;
      border: none;
      cursor: pointer;
    }
    .login-form-1 .btnSubmit {
      font-weight: 600;
      color: #fff;
      background-color: #0062cc;
    }
    .login-form-2 .btnSubmit {
      font-weight: 600;
      color: #0062cc;
      background-color: #fff;
    }
    .login-form-1 .ForgetPwd {
      color: #0062cc;
      font-weight: 600;
      text-decoration: none;
    }
    body{
        background: url('{{ asset('assets/dist/img/login_back.png') }}')center center;
    }
  </style>
</head>

<body>

<div class="container login-container">
    <div class="row">
        <div class="col-md-12 login-form-1"><br>
            <h3 style="color: #01356c;">Login to Future Reserve It</h3><br><br>
            <form role="form" class="text-start" method="post" action="login">
              @csrf
                <div class="form-group">
                    <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="" style="border-radius: 15px;">
                </div>
   
                <div class="form-group">
                    <div class="input-group">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password" value="" style="border-radius: 15px;">
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePasswordVisibility('password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <a href="/password/forgot" class="text-right d-block small" style="color: #002dce;">Forgot Password?</a>
                </div>

                <br>
                <div class="text-center">
                    <button type="submit" class="btn" style="width: 50%; border-radius: 20px; background-color:#ffde59; ">Login</button> 
                    <div class="row justify-content-center">
                        <div class="col text-center">
                            <h2 class="d-inline small">No account?</h2>
                            <span class="d-inline small" style="color: #002dce;"></span>
                            <a href="/user/register" class="d-inline small" style="color: #002dce;">Register Account</a>
                        </div>
                    </div>
                </div><br>
            </form>
        </div>
    </div>
</div>
  
  @if(session('status')=="failed")
  <script>                    
  Swal.fire({
        title: 'Error!',
        text: 'Login failed, please try again!',
        icon: 'error',
    });
  </script>
  @endif

  @if(session('status')=="success")
  <script>                    
  Swal.fire({
        title: 'Success',
        text: 'We have emailed the new password! Check your inbox to login again.',
        icon: 'Success',
    });
  </script>
  @endif

  @if(session('status') == 'fail')
  <script>
    Swal.fire({
      title: 'Error',
      text: 'Email not found.',
      icon: 'error',
    });
  </script>
  @endif

  @if(session('status')=="Change")
  <script>                    
  Swal.fire({
        title: 'Success',
        text: 'Password has been reset successfully. Please login again!',
        icon: 'Success',
    });
  </script>
  @endif
  <script>
      function togglePasswordVisibility(fieldId, icon) {
          const field = document.getElementById(fieldId);
          if (field.type === "password") {
              field.type = "text";
              icon.innerHTML = '<i class="fas fa-eye-slash"></i>';
          } else {
              field.type = "password";
              icon.innerHTML = '<i class="fas fa-eye"></i>';
          }
      }
  </script>

</body>
</html>
