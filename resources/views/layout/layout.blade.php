<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline" action="{{route('searchBook')}}" method="post">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" name="bookNameSearch">
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

            <!-- Messages Dropdown Menu -->

            <!-- Notifications Dropdown Menu -->
            {{--            <li class="nav-item dropdown">--}}
            {{--                <a class="nav-link" data-toggle="dropdown" href="#">--}}
            {{--                    <i class="far fa-bell"></i>--}}
            {{--                    <span class="badge badge-warning navbar-badge">15</span>--}}
            {{--                </a>--}}
            {{--                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
            {{--                    <span class="dropdown-item dropdown-header">15 Notifications</span>--}}
            {{--                    <div class="dropdown-divider"></div>--}}
            {{--                    <div class="media">--}}
            {{--                        <img src="{{asset('dist/img/user1-128x128.jpg')}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">--}}
            {{--                        <div class="media-body">--}}
            {{--                            <h3 class="dropdown-item-title">--}}
            {{--                                Brad Diesel--}}
            {{--                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>--}}
            {{--                            </h3>--}}
            {{--                            <p class="text-sm">Call me whenever you can...</p>--}}
            {{--                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </li>--}}
            @if(\Illuminate\Support\Facades\Auth::user())
                <li class="nav-item">
                    <a class="nav-link" href="{{route('cart')}}" role="button">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="countBookInCart" class="badge badge-warning navbar-badge"></span>
                    </a>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-cog"></i>
                </a>
                @if(\Illuminate\Support\Facades\Auth::user())
                    <div class="dropdown-menu dropdown-menu-right">
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button style="width: 100%" type="submit">Logout</button>
                        </form>
                    </div>
                @elseif(\Illuminate\Support\Facades\Auth::guard('admin')->user())
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <form action="{{route('admin.logout')}}" method="post">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('login')}}" style="width: 100%" class="btn">Login</a>
                    </div>
                @endif
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        @if(\Illuminate\Support\Facades\Auth::guard('admin')->user())
            <a href="{{route('dashboard')}}" class="brand-link">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>
        @else
            <a href="{{route('home')}}" class="brand-link">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>
            <script>
                $(document).ready(function () {
                    $.ajax({
                        url: 'http://localhost:8000/api/count/book-in-cart',
                        method: 'GET',
                        dataType: 'json',
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr.responseText);
                            alert('Lỗi: ' + xhr.responseText);
                        },
                        success: function (response) {
                            if (response !== 0) {
                                $('#countBookInCart').text(response);
                            }
                        }
                    });
                })
            </script>
        @endif
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<x-sidebar/>
        @yield('content')






        </section>
        <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
{{--<script src="{{asset('dist/js/demo.js')}}"></script>--}}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{--<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>--}}
</body>
</html>
