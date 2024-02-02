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
                    <strong>John Doe</strong><br>
                    Address: 795 Folsom Ave, Suite 600 San Francisco, CA 94107<br>
                    Phone: (555) 539-1037<br>
                    Email: john.doe@example.com
                </address>
            </div>
            <!-- /.col -->
            <div class="col-md-6 invoice-col">
                <b>Date rent:</b> 2/22/2014<br>
                <b>Date expire:</b> 2/22/2014
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
                                <tr class="odd">
                                    <td style="width: 150px; height: 150px">
                                        <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle"></td>
                                    <td style="text-align: center; vertical-align: middle"></td>
                                    <td style="text-align: center; vertical-align: middle; width: 150px">

                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Thuê
                </button>
            </div>
        </div>
    </div>
    <!-- /.invoice -->
</div>
@include('layout.footer')
