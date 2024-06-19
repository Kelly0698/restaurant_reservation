<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">

<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<!-- JQVMap -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">

<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">

<!-- summernote -->
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">



<!-- Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">

<style>
    .nav-link.active{
        background-color: #CFB83E !important;
    }

    .blue{
      background-color: #01356c; 
      color: #ffc107;
    }

    .yellow{
      background-color: #ffde59; 
      color: #01356c;
    }

    .bar{
      background-color: #05192e;
    }

    .bar-word{
      font-size: 17px;
    }

    .image-container {
        display: inline-block;
        margin: 10px;
        position: relative;
    }

    .preview-image {
        max-width: 200px;
        max-height: 200px;
    }

    .delete-button {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: red;
        color: white;
        border: none;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 5px;
    }
    @font-face {
        font-family: 'Lucida Handwriting';
        src: url('/path/to/lucida-handwriting.woff2') format('woff2'),
            url('/path/to/lucida-handwriting.woff') format('woff');
        /* Add more src lines for different file formats if available */
        /* Add other font properties if needed */
    }
    .lucida-handwriting {
        font-family: 'Lucida Handwriting', cursive;
    }
    .container-fluid{
          background-color: #fcffec;
      }
      .content-wrapper {
          background-color: #fcffec;
      }
</style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <li class="nav-item dropdown">
      @if(Auth::guard('restaurant')->check())
          <a class="nav-link" data-toggle="dropdown" href="#">
              {{ Auth::guard('restaurant')->user()->name }}&nbsp;
              <i class="fa fa-angle-down"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Restaurant Menu</span>
          <div class="dropdown-divider"></div>
          <a href="/your-profile" class="dropdown-item">
            <i class="fa fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="/restaurant/password/reset" class="dropdown-item">
            <i class="fa fa-key mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="/login" class="dropdown-item">
            <i class="fas fa-utensils mr-2"></i> Login to User Account
          </a>
          <div class="dropdown-divider"></div>
          <a href="/res-tau-rant/logout" class="dropdown-item" id="reslogoutBtn">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
          <div class="dropdown-divider"></div>
        </div>
      @else
          <a class="nav-link" data-toggle="dropdown" href="#">
              {{ Auth::user()->name }}&nbsp;
              <i class="fa fa-angle-down"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">User Menu</span>
          <div class="dropdown-divider"></div>
          <a href="/profile-user" class="dropdown-item">
            <i class="fa fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="/user/password/reset" class="dropdown-item">
            <i class="fa fa-key mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="/your-restaurant" class="dropdown-item">
            <i class="fas fa-utensils mr-2"></i> Login to Your Restaurant
          </a>
          <div class="dropdown-divider"></div>
          <a href="/logout" class="dropdown-item" id="logoutBtn">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
          <div class="dropdown-divider"></div>
        </div>
      @endif
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar bar elevation-4">

    <!-- Sidebar -->
    <div class="sidebar bar-word">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 p-0 mb-0 d-flex">
        <!--<div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>-->
        <div class="info">
          <a href="{{ route('dashboard') }}"  style="color:#ffde59; font-size: 29px;">FutureReserveIt</a>
          <hr class="horizontal yellow" style="margin-top: 7px;">
        </div>
      </div>
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @if((auth()->check() && auth()->user()->role_id != '2') || Auth::guard('restaurant')->check())
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          @endif
          @if(auth()->check() && auth()->user()->role_id == '1')
          <li class="nav-item">
            <a href="{{ route('role_list') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Role
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('user_list') }}" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>User</p>
            </a>
          </li>
          @endif
          @if(auth()->check() && auth()->user()->role_id == '1')
          <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-utensils"></i>
                  <p>
                      Restaurants
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview" style="padding-left: 8px;">
                  <li class="nav-item">
                      <a href="{{ route('restaurant_list') }}" class="nav-link">
                          <i style="font-size: 15px" class="nav-icon fas fa-list-alt"></i>
                          <p style="font-size: 15px">
                              Restaurants List
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('restaurant_req_list') }}" class="nav-link">
                          <i style="font-size: 15px" class="nav-icon fas fa-clipboard-list"></i>
                          <p style="font-size: 15px">
                              Registration Requests
                          </p>
                      </a>
                  </li>
              </ul>
          </li>
          @endif
          @if(auth()->check() && auth()->user()->role_id == '2')
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Home</p>
            </a>
          </li>
          @endif
          @if(auth()->check() && auth()->user()->role_id == '2')
          <li class="nav-header" style="color: white !important;">Saved Restaurant</li>
          <li class="nav-item">
              <a href="{{ route('saved_restaurants') }}" class="nav-link">
                  <i class="nav-icon fas fa-clock"></i>
                  <p>Saved Restautants</p>
              </a>
          </li>
          @endif
          @if(Auth::guard('restaurant')->check())
          <li class="nav-header" style="color: white !important;">RESERVATION</li>
          <li class="nav-item">
            <a href="{{ route('reservation_record') }}" class="nav-link">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>Reservation Request</p>
            </a>
          </li>
          @endif
          @if(auth()->check() && auth()->user()->role_id == '2')
          <li class="nav-header" style="color: white !important;">RESERVATION</li>
          <li class="nav-item">
              <a href="{{ route('pending_reservation') }}" class="nav-link">
                  <i class="nav-icon fas fa-clock"></i>
                  <p>Pending Reservation</p>
              </a>
          </li>
          @endif
          @if(auth()->check() && auth()->user()->role_id == '2')
          <li class="nav-item">
            <a href="{{ route('reservation_record') }}" class="nav-link">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>Reservation Record</p>
            </a>
          </li>
          @endif
          @if(Auth::guard('restaurant')->check())
          <li class="nav-item">
            <a href="{{ route('approve_page') }}" class="nav-link">
                <i class="nav-icon fas fa-check-circle"></i>
                <p>Approved Reservation</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('reject_page') }}" class="nav-link">
                <i class="nav-icon fas fa-times-circle"></i>
                <p>Rejected Reservation</p>
            </a>
          </li>
          <li class="nav-item">
              <a href="{{ route('done_reservations') }}" class="nav-link">
                  <i class="nav-icon fas fa-thumbs-up"></i>
                  <p>Completed Reservation</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{ route('absent_page') }}" class="nav-link">
                  <i class="nav-icon fas fa-user-times"></i>
                  <p>Absent Reservation</p>
              </a>
          </li>
          <li class="nav-header" style="color: white !important;">HOLIDAY</li>
          <li class="nav-item">
              <a href="{{ route('holiday') }}" class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>Restaurant Holiday</p>
              </a>
          </li>
          <li class="nav-header" style="color: white !important;">TABLE ARRANGEMENT</li>
          <li class="nav-item">
              <a href="{{ route('table_arrangement') }}" class="nav-link">
                  <i class="nav-icon fas fa-chair"></i>
                  <p>Table Arrangement</p>
              </a>
          </li>

          @endif

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- /.sidebar -->
  <div class="container-fluid py-4">
    @yield('content')
  </div>

  <!-- /.content-wrapper -->
  <footer class="main-footer">

  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Sparkline -->
<script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include bs-custom-file-input JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script> 

<script src="{{ asset('assets/dist/js/custom.js') }}"></script>

<script>
  $(document).ready(function() {
    // Get the current URL of the page
    var currentUrl = window.location.href;

    // Loop through each navigation link
    $('.nav-link').each(function() {
      // Get the URL of the navigation link
      var linkUrl = $(this).attr('href');
      
      // Check if the link URL matches the current URL
      if (currentUrl.includes(linkUrl)) {
        // Add 'active' class to the link and its parent <li>
        $(this).addClass('active').closest('.nav-item').addClass('active');
        // Exit the loop since the active link has been found
        return false;
      }
    });
  });
</script>

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
    $(document).ready(function () {
        $('#reslogoutBtn').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout Confirmation',
                text: "Are you sure you want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('res_logout') }}";
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#logoutBtn').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout Confirmation',
                text: "Are you sure you want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}";
                }
            });
        });
    });
</script>
@yield('scripts')
</body>
</html>
