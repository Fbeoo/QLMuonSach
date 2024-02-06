@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    .pagination {
        display: flex;
        margin-top: 20px;
    }

    .pagination ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .pagination li {
        display: inline-block;
        margin-right: 5px;
    }

    .pagination li a {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        background-color: #f2f2f2;
        color: #333;
    }

    .pagination li a:hover {
        background-color: #ddd;
    }

    .pagination li a.active {
        background-color: #4CAF50;
        color: white;
    }

    .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .hidden {
        display: none;
    }
</style>
<div style="width: 60%; margin: auto; padding-top: 20px">
    <form id="formFilterUser">
    <div class="input-group" style="margin-top: 20px">
        <input name="contentSearch" type="search" class="form-control form-control-lg" placeholder="Nhập tên hoặc email để tìm kiếm khách hàng" style="height: 40px; width: 500px">
        <div class="input-group-append" style="height: 40px">
            <button class="btn btn-default" id="search">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    </form>
</div>
<div class="card" style="width: 80%; margin: auto; margin-top: 40px; margin-bottom: 40px">
    <div class="card-header">
        <h3 class="card-title">Quản lý người dùng</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Tên người dùng</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Email</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%">Yêu cầu chờ xử lý</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%">Yêu cầu đã được mượn</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%">Yêu cầu đã trả</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%">Yêu cầu từ chối</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Trạng thái</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Hành động</th>
                        </tr>
                        </thead>
                        <tbody id="user">
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->mail}}</td>
                                <td style="text-align: center">{{$user->requestStatusPending}}</td>
                                <td style="text-align: center">{{$user->requestStatusBorrowing}}</td>
                                <td style="text-align: center">{{$user->requestStatusReturned}}</td>
                                <td style="text-align: center">{{$user->requestStatusRefuse}}</td>
                                <td>
                                    @if($user->status === \App\Models\User::statusLock)
                                        <p id="userStatus{{$user->id}}" style="color: red">Khóa</p>
                                    @elseif($user->status === \App\Models\User::statusNormal)
                                        <p id="userStatus{{$user->id}}" style="color: green">Bình thường</p>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning">Hành động</button>
                                        <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu" style="">
                                            <a style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target=".viewRequestOfUserModal" data-id = "{{$user->id}}">Xem yêu cầu mượn</a>
                                            @if($user->status === \App\Models\User::statusNormal)
                                                <a id="actionStatus{{$user->id}}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "{{$user->id}}" data-value = "Lock">Khóa</a>
                                            @elseif($user->status === \App\Models\User::statusLock)
                                                <a id="actionStatus{{$user->id}}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "{{$user->id}}" data-value = "Unlock">Mở khóa</a>
                                            @endif

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<div class="pagination" style="align-items: center; justify-content: center">
    <ul id="ulPagination">

    </ul>
</div>
<div class="modal fade" id="confirmActionStatusUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="notification"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                <button id="actionStatus" type="button" class="btn btn-primary"></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade viewRequestOfUserModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card" style="width: 80%; margin: auto; margin-top: 40px; margin-bottom: 40px">
                <div class="card-header">
                    <h3 class="card-title">Yêu cầu mượn sách</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ngày mượn</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ngày trả</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Tổng tiền</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Trạng thái</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody id="allRequestOfUser">

                                    </tbody>
                                </table>
                                <div class="pagination" style="align-items: center; justify-content: center">
                                    <ul id="ulPaginationRequestOfUser">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade viewDetailRequestModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card" style="width: 80%; margin: auto; margin-top: 40px; margin-bottom: 40px">
                <div class="card-header">
                    <h3 class="card-title">Yêu cầu mượn sách</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ảnh sách</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Tên sách</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Đơn giá thuê</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Số lượng</th>
                                    </tr>
                                    </thead>
                                    <tbody id="detailRequest">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<div id="loaderContainer" class="loader-container hidden">
    <div class="loader"></div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{asset('dist/js/phongJs/manageUser.js')}}"></script>
@include('admin.layout.footer')
