@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
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
<div class="card" style="width: 80%; margin: auto; margin-top: 20px">
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
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Người mượn</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Trạng thái</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requestRent as $request)
                                <tr class="odd">
                                    <td>{{$request->id}}</td>
                                    <td>{{$request->rent_date}}</td>
                                    <td>{{$request->expiration_date}}</td>
                                    <td>{{$request->total_price}}</td>
                                    <td>{{$request->user->name}}</td>
                                    @if($request->status === \App\Models\HistoryRentBook::statusPending)
                                        <td>
                                            <p id="statusRequest{{$request->id}}" style="color: blue">Đợi xác nhận</p>
                                        </td>
                                    @elseif($request->status === \App\Models\HistoryRentBook::statusBorrowing)
                                        <td>
                                            <p id="statusRequest{{$request->id}}" style="color: yellow">Đang mượn</p>
                                        </td>
                                    @elseif($request->status === \App\Models\HistoryRentBook::statusReturned)
                                        <td>
                                            <p id="statusRequest{{$request->id}}" style="color: green">Đã trả</p>
                                        </td>
                                    @else
                                        <td>
                                            <p id="statusRequest{{$request->id}}" style="color: red">Từ chối</p>
                                        </td>
                                    @endif
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning">Hành động</button>
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest{{$request->id}}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="{{$request->id}}">Xem chi tiết</button>
                                                @if($request->status !== \App\Models\HistoryRentBook::statusRefuse && $request->status !== \App\Models\HistoryRentBook::statusReturned)
                                                    @if($request->status === \App\Models\HistoryRentBook::statusPending)
                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Accept" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Accept">Chấp nhận</button>
                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Refuse">Từ chối</button>
                                                    @elseif($request->status === \App\Models\HistoryRentBook::statusBorrowing)
                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Returned" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Returned">Đánh dấu đã trả</button>
                                                    @endif
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
<script src="{{asset('dist/js/phongJs/requestRentBook.js')}}"></script>
@include('admin.layout.footer')
