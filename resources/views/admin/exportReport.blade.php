@include('admin.layout.header')
@include('admin.layout.sidebar')
<form id="formExportReport">
    @csrf
    <div class="row" style="width: 80%; margin: auto; padding-top: 30px">
        <div class="col-4">
            <label>Danh mục báo cáo</label>
            <select name="categoryReport" id="categoryReport" class="form-control custom-select">
                <option value="" selected>Lựa chọn danh mục báo cáo</option>
                <option value="book">Sách</option>
                <option value="requestRent">Yêu cầu mượn</option>
            </select>
        </div>
        <div class="col-4">
            <label>Loại báo cáo</label>
            <select name="typeReport" id="report" class="form-control custom-select">

            </select>
        </div>
        <div class="col-4" id="time">
            <div class="form-group">
                <label>Thời gian</label>
                <input class="form-control" type="text" name="dateRangeReport" id="dateRangeReport" value="" disabled/>
            </div>
        </div>
    </div>
    <div style="width: 10%; margin: 30px auto;">
            <a id="export" class="btn btn-success">
                Xuất báo cáo
            </a>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    $(function() {
        $('input[name="dateRangeReport"]').daterangepicker({
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            autoUpdateInput: false
        });
        $('input[name="dateRangeReport"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
        $('input[name="dateRangeReport"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
<script src="{{asset('dist/js/phongJs/exportReport.js')}}"></script>
@include('admin.layout.footer')
