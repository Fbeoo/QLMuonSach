@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    .chart-container {
        width: 500px;
        height: 300px;
    }
</style>
<input type="hidden" value="{{$countBookAvailable}}" id="countBookAvailable">
<input type="hidden" value="{{$countBookBorrowing}}" id="countBookBorrowing">
<input type="hidden" value="{{$countBookMissing}}" id="countBookMissing">
<div class="row" style="margin: 40px auto; width: 80%;">
    <div class="col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$countRequestRentBookHaveStatusPending}}</h3>

                <p>Số lượng yêu cầu mượn sách chờ xử lý</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
        </div>
    </div>
    <div class="col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{$countUser}}</h3>

                <p>Số lượng người dùng</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 50px">
    <div class="col-6">
        <div class="chart-container" style="margin: auto;">
            <canvas id="bar-chart"></canvas>
        </div>
    </div>
    <div class="col-6">
        <div class="chart-container" style="margin: auto">
            <canvas id="pie-chart"></canvas>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu cho biểu đồ
    var dataBarChart = {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'],
        datasets: [
            {
                label: 'Tổng số lượng đơn đã trả',
                backgroundColor: '#2196F3',
                data: [100, 150, 120, 200, 180]
            },
            {
                label: 'Tổng số lượng đơn từ chối',
                backgroundColor: '#FF5252',
                data: [50, 80, 70, 90, 60]
            }
        ]
    };

    // Tạo biểu đồ cột
    var ctx = document.getElementById('bar-chart').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: dataBarChart,
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var dataPieChart = {
        labels: ['Sách trong kho', 'Sách đang mượn', 'Sách đang thất lạc'],
        datasets: [
            {
                data: [document.getElementById('countBookAvailable').value, document.getElementById('countBookBorrowing').value, document.getElementById('countBookMissing').value],
                backgroundColor: ['#2196F3', '#FFC107', '#4CAF50']
            }
        ]
    };

    // Tạo biểu đồ tròn
    var ctx = document.getElementById('pie-chart').getContext('2d');
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: dataPieChart,
        options: {
            responsive: true
        }
    });
</script>
@include('admin.layout.footer')
