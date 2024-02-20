var selectTypeReport = document.getElementById('report');
var selectCategoryReport = document.getElementById('categoryReport');
selectCategoryReport.addEventListener('change',function () {
    var strHtml = '';
    document.getElementById('dateRangeReport').disabled = true;
    if (selectCategoryReport.value === "book") {
        strHtml = `<option value="" selected>Lựa chọn loại báo cáo cần xuất</option>
                <option value="bookRent">Thống kê sách được mượn theo thời gian</option>
                <option value="bookMissing">Thống kê sách đang thất lạc</option>`;
    }
    else if (selectCategoryReport.value === 'requestRent') {
        strHtml = `<option value="" selected>Lựa chọn loại báo cáo cần xuất</option>
                <option value="requestRentInDay">Thống kê yêu cầu mượn trong ngày</option>
                <option value="requestRentByDateRange">Thống kê yêu cầu mượn theo thời gian</option>`;
    }
    else {
        strHtml = '';
    }
    selectTypeReport.innerHTML = strHtml;
})
selectTypeReport.addEventListener('change',function () {
    if (selectTypeReport.value === 'bookRent' || selectTypeReport.value === 'requestRentByDateRange') {
        document.getElementById('dateRangeReport').disabled = false;
    }
    else {
        document.getElementById('dateRangeReport').disabled = true;
        document.getElementById('dateRangeReport').value = '';
    }
})
document.getElementById('export').addEventListener('click',function () {
    event.preventDefault();

    var formData = new FormData($('#formExportReport')[0]);

    $.ajax({
        url : 'http://localhost:8000/api/admin/export-report',
        method : 'POST',
        contentType: false,
        processData: false,
        xhrFields: {
            responseType: 'blob' // Để xác định kiểu dữ liệu là blob (file)
        },
        data : formData,
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.log(status);
            console.log(error);
        },
        success: function (response) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(response.download);
            a.href = url;
            a.download = response.fileName;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        },
    })
})
