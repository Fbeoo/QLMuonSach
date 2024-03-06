{{--@include('layout.header');--}}
{{--<x-sidebar/>--}}
@extends('layout.layout')

@section('title')
    Giỏ hàng
@endsection

@section('content')
<div class="card" style="width: 80%; margin: auto; margin-top: 20px; padding-bottom: 70px">
    <div class="card-header">
        <h3 class="card-title">Giỏ hàng của bạn</h3>
    </div>
    <!-- /.card-header -->
    @if($cart)
    <div class="card-body">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">

                </div>
                <div class="col-sm-12 col-md-6">

                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-12" style="position: relative">
                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                            <thead>
                            <tr>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ảnh sách</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Tên sách</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Đơn giá thuê</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Số lượng</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Thành tiền</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"></th>
                            </tr>
                            </thead>
                            <tbody id="cartContent">
                            @foreach($cart->get('bookInCart') as $book)
                                <tr class="odd">
                                    <td style="width: 150px; height: 150px">
                                        <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="{{asset('storage/'.$book['book']->thumbnail)}}">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">{{$book['book']->name}}</td>
                                    <td style="text-align: center; vertical-align: middle">{{number_format($book['book']->price_rent, 0, ',', '.')}} / 1 ngày</td>
                                    <td style="text-align: center; vertical-align: middle; width: 150px">
                                        <input class="quantityBook" type="number" style="width: 80px;" value="{{$book['quantityLine']}}" data-line = "{{$book['line']}}">
{{--                                        <p style="color: red" id="quantityRentError"></p>--}}
                                    </td>
                                    <td id="linePrice{{$book['line']}}" style="text-align: center; vertical-align: middle">{{number_format($book['linePrice'], 0, ',', '.')}}</td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <a style="cursor: pointer" class="removeBook" data-line="{{$book['line']}}">
                                            <i class="fa fa-window-close"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    <div style="position: absolute; right: 0px; width: 200px">
                        <div class="row">
                            <div class="col-6" style="text-align: left">
                                Tổng tiền
                            </div>
                            <div class="col-6" style="text-align: right">
                                <p id="totalPrice">{{number_format($cart->get('totalPrice'), 0, ',', '.')}}</p>
                            </div>
                        </div>
                        <form id="formRentMultiBook" action="{{route('confirmRentBook')}}" method="post">
                            @csrf
                            <input type="hidden" name="typeRent" value="rentMultiBook">
                        <a id="rentBook" class="btn btn-success" style="width: 100%; margin-top: 10px">Thuê</a>
                        </form>
                    </div>
                    </div>
                <div class="col-lg-3 col-12">
                    <div style="display: flex; flex-direction: column;margin-top: 13px">
                        <label>Thời gian mượn</label>
                        <input id="dateRent" type="text" name="dateRent" value="{{$cart->get('dateRent')}}"/>
                        <p id="dateRentError">

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    @endif
</div>

<script src="{{asset('dist/js/phongJs/cart.js')}}"></script>
@endsection
{{--@include('layout.footer');--}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
