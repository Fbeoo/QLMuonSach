@include('layout.header')
<x-sidebar/>
 <!-- Main content -->
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
                    <strong>{{session()->get('user')->name}}</strong><br>
                    Địa chỉ: {{session()->get('user')->address}}<br>
                    Email: {{session()->get('user')->mail}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-md-6 invoice-col">
                <b>Ngày mượn:</b> {{$dateRent}}<br>
                <b>Ngày trả:</b> <p id="dateExpire">{{$dateExpire}}</p>
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
                                @if($typeRent === 'rentSingleBook')
                                    <input id="bookId" type="hidden" value="{{$book->id}}">
                                    <input id="quantityRent" type="hidden" value="{{$quantityRent}}">
                                    <input id="totalPrice" type="hidden" value="{{$totalPrice}}">
                                    <tr class="odd">
                                        <td style="width: 150px; height: 150px">
                                            <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="http://localhost:8000/storage/{{$book->thumbnail}}">
                                        </td>
                                        <td style="text-align: center; vertical-align: middle">{{$book->name}}</td>
                                        <td style="text-align: center; vertical-align: middle">{{$book->price_rent}}</td>
                                        <td style="text-align: center; vertical-align: middle; width: 150px">{{$quantityRent}}</td>
                                        <td style="text-align: center; vertical-align: middle; width: 150px">{{$totalPrice}}</td>
                                    </tr>
                                @elseif($typeRent === 'rentMultiBook')
                                    @foreach($cart->get('bookInCart') as $book)
                                        <tr class="odd">
                                            <td style="width: 150px; height: 150px">
                                                <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="http://localhost:8000/storage/{{$book['book']->thumbnail}}">
                                            </td>
                                            <td style="text-align: center; vertical-align: middle">{{$book['book']->name}}</td>
                                            <td style="text-align: center; vertical-align: middle">{{$book['book']->price_rent}}</td>
                                            <td style="text-align: center; vertical-align: middle; width: 150px">{{$book['quantityLine']}}</td>
                                            <td style="text-align: center; vertical-align: middle; width: 150px">{{$book['linePrice']}}</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        @if($typeRent === 'rentSingleBook')
            <div class="row">
                <div class="col-6" style="text-align: left">

                </div>
                <div class="col-6" style="text-align: right">
                    <div class="row">
                        <div class="col-10">
                            Tổng tiền
                        </div>
                        <div class="col-2">{{$totalPrice}}</div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-12">
                    <a class="btn btn-success float-right" id="rentSingleBook">
                        Thuê
                    </a>
                </div>
            </div>
        @elseif($typeRent === 'rentMultiBook')
            <div class="row">
                <div class="col-6" style="text-align: left">

                </div>
                <div class="col-6" style="text-align: right">
                    <div class="row">
                        <div class="col-10">
                            Tổng tiền
                        </div>
                        <div class="col-2">
                            {{$cart->get('totalPrice')}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-12">
                    <a class="btn btn-success float-right" id="rentMultiBook">
                        Thuê
                    </a>
                </div>
            </div>
        @endif
        <!-- /.row -->

        <!-- this row will not appear when printing -->

    </div>
    <!-- /.invoice -->
</div>
<script src="{{asset('dist/js/phongJs/confirmRequestRentBook.js')}}"></script>
@include('layout.footer')
