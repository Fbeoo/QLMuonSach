<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            @if(\Illuminate\Support\Facades\Auth::user())
                <a id="nameUser" href="{{route('profile')}}" class="d-block">{{session()->get('user')->name}}</a>
            @elseif(\Illuminate\Support\Facades\Auth::guard('admin')->user())
                <a id="nameUser" class="d-block">Admin</a>
            @else
                <a id="nameUser" href="{{route('login')}}" class="d-block">Đăng nhập</a>
            @endif
        </div>
    </div>
    <!-- Sidebar Menu -->
    @if(\Illuminate\Support\Facades\Auth::guard('admin')->user())
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
                    <a href="{{route('report')}}" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <p>
                            Báo cáo
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    @else
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>
                            Trang chủ
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-book"></i>
                        <p>
                            Sách
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($category_parents as $category_parent)
                            <li class="nav-item">
                                <a href="./index.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{$category_parent->category_name}}</p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($category_children as $category_child)
                                        @if($category_child->category_parent_id === $category_parent->id)
                                            <li class="nav-item">
                                                <a href="{{route('getBookByCategory',['categoryId'=>$category_child->id])}}" class="nav-link">
                                                    <p>{{$category_child->category_name}}</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
                @if(\Illuminate\Support\Facades\Auth::user())
                    <li class="nav-item">
                        <a href="{{route('historyRentBook',['userId'=>session()->get('user')->id])}}" class="nav-link">
                            <i class="fas fa-history"></i>
                            <p>
                                Lịch sử mượn sách
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('calendarReturnBook')}}" class="nav-link">
                            <i class="fas fa-history"></i>
                            <p>
                                Lịch trả sách
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
