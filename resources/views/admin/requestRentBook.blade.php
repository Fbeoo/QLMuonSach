@include('admin.layout.header')
@include('admin.layout.sidebar')
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
                                        <td style="color: blue">Đợi xác nhận</td>
                                    @elseif($request->status === \App\Models\HistoryRentBook::statusBorrowing)
                                        <td style="color: yellow">Đang mượn</td>
                                    @elseif($request->status === \App\Models\HistoryRentBook::statusReturned)
                                        <td style="color: green">Đã trả</td>
                                    @else
                                        <td style="color: red">Từ chối</td>
                                    @endif
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning">Hành động</button>
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <a style="cursor: pointer" class="dropdown-item">Xem chi tiết</a>
                                                @if($request->status !== \App\Models\HistoryRentBook::statusRefuse && $request->status !== \App\Models\HistoryRentBook::statusReturned)
                                                    @if($request->status === \App\Models\HistoryRentBook::statusPending)
                                                        <button style="cursor: pointer" class="dropdown-item">Chấp nhận</button>
                                                        <button style="cursor: pointer" class="dropdown-item">Từ chối</button>
                                                    @elseif($request->status === \App\Models\HistoryRentBook::statusBorrowing)
                                                        <button style="cursor: pointer" class="dropdown-item">Đánh dấu đã trả</button>
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
@include('admin.layout.footer')
