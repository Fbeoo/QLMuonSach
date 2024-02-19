var selectTypeReport = document.getElementById('report');
selectTypeReport.addEventListener('change',function () {
    if (selectTypeReport.value === '1') {
        document.getElementById('dateRangeReport').disabled = true;
    }
    else {
        document.getElementById('dateRangeReport').disabled = false;
    }
})
document.getElementById('export').addEventListener('click',function () {
    event.preventDefault();

    var formData = new FormData($('#formExportReport')[0]);

    $.ajax({
        url : 'http://localhost:8000/api/admin/export-report',
        method : 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data : formData,
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.log(status);
            console.log(error);
        },
        success: function (response) {
            if (response.success) {
                alert(response.success);
            }
        },
    })
})
