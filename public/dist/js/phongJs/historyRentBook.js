$(document).ready(function() {
    $("#exampleModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        $('#notification').text("Bạn có muốn từ chối yêu cầu mượn sách")
        var buttonActionStatus = document.getElementById('actionStatus');
        buttonActionStatus.textContent = "Từ chối";
        buttonActionStatus.onclick = function () {
            refuseRequestRentBook(button.data("id"))
        }
    });

    $(".viewDetailRequestModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        showDetailRequest(button.data("id"));
    });
});
function showDetailRequest(requestId) {
    $('#detailRequest').empty();
    $.ajax({
        url: 'http://localhost:8000/api/admin/detail-request/'+ requestId,
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "requestId" : requestId,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            var strHtml = '';
            for (var i=0;i<response[0].detail_history_rent_book.length;i++) {
                strHtml +=
                    `<tr>
                        <td style="width: 150px; height: 150px">
                            <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="http://localhost:8000/storage/${response[0].detail_history_rent_book[i].book.thumbnail}">
                        </td>
                        <td style="text-align: center; vertical-align: middle">${response[0].detail_history_rent_book[i].book.name}</td>
                        <td style="text-align: center; vertical-align: middle">${Number(response[0].detail_history_rent_book[i].book.price_rent).toLocaleString('vi-VN')} / 1 ngày</td>
                        <td style="text-align: center; vertical-align: middle">${response[0].detail_history_rent_book[i].quantity}</td>
                    </tr>`
            }
            $('#detailRequest').html(strHtml);
        }
    });
}

function refuseRequestRentBook(requestId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/refuse-request-rent-book',
        method: 'PUT',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "requestId" : requestId,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function () {
            alert('Hủy yêu cầu thành công');
            document.getElementById(`statusRequest${requestId}`).innerHTML = 'Từ chối'
            document.getElementById(`statusRequest${requestId}`).style.color = "red"
            document.getElementById('statusRequest'+requestId+'Refuse').parentNode.removeChild(document.getElementById('statusRequest'+requestId+'Refuse'));
            loaderContainer.classList.add("hidden");
        }
    });
}
