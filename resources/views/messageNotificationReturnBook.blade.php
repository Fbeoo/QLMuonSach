<p>Hello {{$data['name']}}</p>
<p>Bạn có lịch trả sách</p>
@foreach($data['history_rent_book'] as $requestRentBook)
    <div class="invoice p-3 mb-3" style="width: 80%; margin: auto">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-globe"></i> AdminLTE, Inc.</h4>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <!-- /.col -->
            <div class="col-md-6 invoice-col">
                <address>
                    <strong>{{$data['name']}}</strong><br>
                    Địa chỉ: {{$data['address']}}<br>
                    Email: {{$data['mail']}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-md-6 invoice-col">
                <b>Ngày mượn:</b> {{$requestRentBook['rent_date']}}<br>
                <b>Ngày trả:</b> <p id="dateExpire">{{$requestRentBook['expiration_date']}}</p>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- accepted payments column -->
            <!-- /.col -->
            <div class="col-12">
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ảnh sách</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Tên sách</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Đơn giá thuê</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Số lượng</th>
                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody id="cartContent">
                        @foreach($requestRentBook['detail_history_rent_book'] as $item)
                            <tr class="odd">
                                <td style="width: 150px; height: 150px">
                                            <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="{{public_path('/storage/'.$item['book']['thumbnail'])}}">
                                </td>
                                <td style="text-align: center; vertical-align: middle">{{$item['book']['name']}}</td>
                                <td style="text-align: center; vertical-align: middle">{{number_format($item['book']['price_rent'], 0, ',', '.')}}</td>
                                <td style="text-align: center; vertical-align: middle; width: 150px">{{$item['quantity']}}</td>
                                <td style="text-align: center; vertical-align: middle; width: 150px">{{number_format($item['price'], 0, ',', '.')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-6" style="text-align: left">

            </div>
            <div class="col-6" style="text-align: right">
                <div class="row">
                    <div class="col-8">
                        Tổng tiền
                    </div>
                    <div class="col-4">{{number_format($requestRentBook['total_price'], 0, ',', '.')}}</div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">

        </div>
    </div>
    <!-- /.invoice -->
    </div>
@endforeach

