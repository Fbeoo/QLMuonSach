{{--@include('layout.header')--}}
{{--<x-sidebar/>--}}
@extends('layout.layout')

@section('title')
    Lịch trả sách
@endsection

@section('content')
<style>
    .fc-event {
        cursor: pointer;
    }
</style>
<input type="hidden" id="userId" value="{{session()->get('user')->id}}">
<div  style="height: 800px; width: 80%; margin: auto">
    <div id='calendar' style="max-height: 100%; max-width: 100%"></div>
</div>
<div class="modal fade viewDetailRequestModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card" style="width: 80%; margin: auto; margin-top: 40px; margin-bottom: 40px">
                <div class="card-header">
                    <h3 class="card-title">Yêu cầu mượn sách</h3>
                </div>
                <div class="row invoice-info" style="margin-left: 20px; margin-top: 20px">
                    <!-- /.col -->
                    <div class="col-md-6 invoice-col">
                        <address>
                            <strong id="name"></strong><br>
                            <b>Địa chỉ: </b> <p id="address" style="display: inline"></p><br>
                            <b>Email: </b> <p id="mail" style="display: inline"></p>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 invoice-col" style="margin-top: 23px">
                        <b>Ngày mượn: </b> <p id="dateRent" style="display: inline"></p><br>
                        <b>Ngày trả: </b> <p id="dateExpire" style="display: inline"></p>
                    </div>
                    <!-- /.col -->
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
                <div class="row" style="margin-right: 20px;margin-bottom: 20px">
                    <div class="col-6" style="text-align: left">

                    </div>
                    <div class="col-6" style="text-align: right">
                        <div class="row">
                            <div class="col-10">
                                Tổng tiền
                            </div>
                            <div class="col-2" id="totalPrice"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="{{asset('dist/js/phongJs/calendarForUser.js')}}"></script>
@endsection
{{--@include('layout.footer')--}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
