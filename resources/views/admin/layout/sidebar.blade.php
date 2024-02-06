<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="{{route('dashboard')}}" class="d-block">Admin</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{route('dashboard')}}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <p>
                        Trang chủ
                    </p>
                </a>
            </li>
            <li class="nav-item ">
                <a href="#" class="nav-link">
                    <i class="fas fa-cogs"></i>
                    <p>
                        Quản lý
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('manageBook')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Quản lý sách</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('manageUser')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Quản lý người dùng</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{route('requestRentBook')}}" class="nav-link">
                    <i class="fas fa-history"></i>
                    <p>
                        Yêu cầu mượn sách
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('calendarPage')}}" class="nav-link">
                    <i class="fas fa-history"></i>
                    <p>
                        Lịch trả sách
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/test" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <p>
                        Báo cáo
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
