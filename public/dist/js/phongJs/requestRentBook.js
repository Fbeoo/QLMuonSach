$(document).ready(function() {
    $("#exampleModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        var buttonValue = button.attr("data-value");
        console.log(buttonValue);
        var buttonActionStatus = document.getElementById('actionStatus');
        if (buttonValue === "Accept") {
            $('#notification').text("Bạn có muốn chấp nhận yêu cầu mượn sách")
            buttonActionStatus.textContent = "Chấp nhận";
            buttonActionStatus.onclick = function () {
                acceptRequestRentBook(button.data("id"))
            }
        }
        else if (buttonValue === "Refuse") {
            $('#notification').text("Bạn có muốn từ chối yêu cầu mượn sách")
            buttonActionStatus.textContent = "Từ chối";
            buttonActionStatus.onclick = function () {
                refuseRequestRentBook(button.data("id"))
            }
        }
        else if (buttonValue === "Returned") {
            buttonActionStatus.textContent = "Đánh dấu";
            $('#notification').text("Bạn có muốn đánh dấu người mượn đã trả sách")
            buttonActionStatus.onclick = function () {
                markReturnedBook(button.data("id"))
            }
        }
    });

    $(".viewDetailRequestModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        showDetailRequest(button.data("id"));
    });
});
function acceptRequestRentBook(requestId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/accept-request-rent-book',
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
            alert('Chấp nhận yêu cầu thành công');
            document.getElementById('statusRequest'+requestId+'Accept').innerHTML = 'Đánh dấu đã trả'
            document.getElementById('statusRequest'+requestId+'Accept').dataset.value = 'Returned';
            document.getElementById('statusRequest'+requestId+'Accept').id = 'statusRequest'+requestId+'Returned';
            document.getElementById('statusRequest'+ requestId).innerHTML = 'Đang mượn'
            document.getElementById('statusRequest'+requestId).style.color = "yellow"
            document.getElementById('statusRequest'+requestId+'Refuse').parentNode.removeChild(document.getElementById('statusRequest'+requestId+'Refuse'));
            loaderContainer.classList.add("hidden");
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
            alert('Từ chối yêu cầu thành công');
            document.getElementById('statusRequest'+ requestId).innerHTML = 'Từ chối'
            document.getElementById('statusRequest'+requestId).style.color = "red"
            document.getElementById('statusRequest'+requestId+'Accept').parentNode.removeChild(document.getElementById('statusRequest'+requestId+'Accept'));
            document.getElementById('statusRequest'+requestId+'Refuse').parentNode.removeChild(document.getElementById('statusRequest'+requestId+'Refuse'));
            loaderContainer.classList.add("hidden");
        }
    });
}

function markReturnedBook(requestId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/mark-returned-book',
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
            alert('Đánh dấu trả sách thành công');
            document.getElementById('statusRequest'+ requestId).innerHTML = 'Đã trả'
            document.getElementById('statusRequest'+requestId).style.color = "green"
            document.getElementById('statusRequest'+requestId+'Returned').parentNode.removeChild(document.getElementById('statusRequest'+requestId+'Returned'));
            loaderContainer.classList.add("hidden");
        }
    });
}

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
                        <td style="text-align: center; vertical-align: middle">${response[0].detail_history_rent_book[i].book.price_rent} / 1 ngày</td>
                        <td style="text-align: center; vertical-align: middle">${response[0].detail_history_rent_book[i].quantity}</td>
                    </tr>`
            }
            $('#detailRequest').html(strHtml);
        }
    });
}
