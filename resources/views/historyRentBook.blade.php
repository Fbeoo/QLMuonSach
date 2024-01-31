@include('layout.header')
<x-sidebar/>
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
                                    <td>{{$history->total_price}}</td>
                                    @if($history->status === \App\Models\HistoryRentBook::statusPending)
                                        <td style="color: blue">Đợi xác nhận</td>
                                    @elseif($history->status === \App\Models\HistoryRentBook::statusBorrowing)
                                        <td style="color: yellow">Đang mượn</td>
                                    @elseif($history->status === \App\Models\HistoryRentBook::statusReturned)
                                        <td style="color: yellow">Đã trả</td>
                                    @else
                                        <td style="color: red">Bị từ chối</td>
                                    @endif
                                    <td>
                                        <a>Xem chi tiết</a>
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
@include('layout.footer')
