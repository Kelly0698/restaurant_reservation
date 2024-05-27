<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

  <style>
    body{
        background: url('{{ asset('assets/dist/img/register_back.png') }}')center center;
    }
  </style>
</head>

<body class="login-page" style="min-height: 318.8px;">
<div class="login-box">
  <div class="card card-outline">
    <div class="card-header text-center">
        <div class="row" style="background-color: #ffde59;">
            <div class="col-auto">
                <p class="mt-3 mb-1">
                    <a href="#" onclick="window.history.back();" class="btn btn-sm">
                        <i class="fas fa-arrow-left" style="margin-bottom: 11px;"></i>
                    </a>
                </p>
            </div>
            <div class="col align-self-center">
                <p class="h3 mb-0" style="padding-right: 35px;"><b>Future Reserve It</b></p>
            </div>
        </div>
    </div>


    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form action="{{ route('ForgetPasswordPost') }}" method="POST">
      @csrf
        <div class="input-group mb-3">
          <!-- Inside the form tag -->
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-block" style="background-color: #ffde59;">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
</body>
</html>