{{--@include('layout.header')--}}
{{--<x-sidebar/>--}}
@extends('layout.layout')

@section('title')
    Lịch sử mượn sách
@endsection

@section('content')
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
<div class="card" style="width: 80%; margin: auto; margin-top: 20px">
    <div class="card-header">
        <h3 class="card-title">Lịch sử mượn sách</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ngày mượn</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Tổng tiền</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Trạng thái</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($historyRent as $history)
                                <tr class="odd">
                                    <td>{{$history->id}}</td>
                                    <td>{{$history->rent_date}}</td>
                                    <td>{{number_format($history->total_price, 0, ',', '.')}}</td>
                                    @if($history->status === \App\Models\HistoryRentBook::statusPending)
                                        <td>
                                            <p id="statusRequest{{$history->id}}" style="color: blue">Đợi xác nhận</p>
                                        </td>
                                    @elseif($history->status === \App\Models\HistoryRentBook::statusBorrowing)
                                        <td style="color: yellow">
                                            <p style="color: yellow">Đang mượn</p>
                                        </td>
                                    @elseif($history->status === \App\Models\HistoryRentBook::statusReturned)
                                        <td style="color: yellow">
                                            <p style="color: green">Đã trả</p>
                                        </td>
                                    @else
                                        <td>
                                            <p style="color: red">Từ chối</p>
                                        </td>
                                    @endif
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning">Hành động</button>
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest{{$history->id}}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="{{$history->id}}">Xem chi tiết</button>
                                                @if($history->status == \App\Models\HistoryRentBook::statusPending)
                                                    <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$history->id}}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="{{$history->id}}">Hủy</button>
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
<div class="pagination" style="align-items: center; justify-content: center">
    <ul id="ulPagination">
        @for($i = 1; $i <= $historyRent->lastPage(); $i++)
            <li>
                <a href="{{ route('historyRentBook', ['userId'=>session()->get('user')->id,'page' => $i]) }}">{{ $i }}</a>
            </li>
        @endfor
    </ul>
</div>
<div id="loaderContainer" class="loader-container hidden">
    <div class="loader"></div>
</div>
    <script src="{{asset('dist/js/phongJs/historyRentBook.js')}}"></script>
{{--@include('layout.footer')--}}
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
