$(document).ready(function() {
    getPagination();

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

var exportInvoice = document.querySelectorAll('.exportInvoice');
function getPagination() {
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/request-rent-book',
        method: 'GET',
        contentType: 'application/json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            var strHtmlPaging = '';
            for (var i = 0; i < response.last_page; i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPagination(${i + 1})">${i + 1}</a></li>`;
            }
            $('#ulPagination').html(strHtmlPaging);
        }
    });
}

function loadPagination(page) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: `http://localhost:8000/api/admin/all/request-rent-book/?page=${page}`,
        method: 'GET',
        contentType: 'json',
        error: function(response) {
            console.log('error to call api');
        },
        success: function(response) {
            var strHtml = '';
            var strStatus = '';
            var strBtnAction = '';
            const statusPending = 0;
            const statusBorrowing = 1;
            const statusReturned = 2;
            const statusRefuse = 3;
            for (var i=0;i<response.data.length;i++) {
                if (response.data[i].status === statusPending) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: blue">Đợi xác nhận</p>
                                        </td>`
                }
                else if (response.data[i].status === statusBorrowing) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: yellow">Đang mượn</p>
                                        </td>`
                }
                else if (response.data[i].status === statusReturned) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: green">Đã trả</p>
                                        </td>`
                }
                else {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: red">Từ chối</p>
                                        </td>`
                }
                if (response.data[i].status !== statusRefuse && response.data[i].status !== statusReturned) {
                    if (response.data[i].status === statusPending) {
                        strBtnAction = `<button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Accept" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Accept">Chấp nhận</button>
                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Refuse">Từ chối</button>`
                    }
                    else if (response.data[i].status === statusBorrowing) {
                        strBtnAction = `<button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Returned" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Returned">Đánh dấu đã trả</button>`
                    }
                }
                strHtml += `<tr class="odd">
                                    <td>${response.data[i].id}</td>
                                    <td>${response.data[i].rent_date}</td>
                                    <td>${response.data[i].expiration_date}</td>
                                    <td>${Number(response.data[i].total_price).toLocaleString('vi-VN')}</td>
                                    <td>${response.data[i].user.name}</td>
                                    ${strStatus}
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning">Hành động</button>
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest${response.data[i].id}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="${response.data[i].id}">Xem chi tiết</button>
                                                ${strBtnAction}
                                                <button style="cursor: pointer" class="dropdown-item exportInvoice" data-id="${response.data[i].id}">Xuất hóa đơn</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>`
            }
            $('#showRequest').html(strHtml);
            loaderContainer.classList.add("hidden");
            exportInvoice = document.querySelectorAll('.exportInvoice');
            exportInvoice.forEach(exportInvoice => {
                exportInvoice.addEventListener('click', function(event) {
                    exportInvoiceUser(exportInvoice.dataset.id)
                });
            });
        }
    });
}
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
        success: function (response) {
            if (response.errorQuantity) {
                alert('Số lượng sách không đủ đáp ứng yêu cầu mượn sách');
                refuseRequestRentBook(requestId);
                return;
            }
            else {
                alert('Chấp nhận yêu cầu thành công');
                document.getElementById('statusRequest'+requestId+'Accept').innerHTML = 'Đánh dấu đã trả'
                document.getElementById('statusRequest'+requestId+'Accept').dataset.value = 'Returned';
                document.getElementById('statusRequest'+requestId+'Accept').id = 'statusRequest'+requestId+'Returned';
                document.getElementById('statusRequest'+ requestId).innerHTML = 'Đang mượn'
                document.getElementById('statusRequest'+requestId).style.color = "yellow"
                document.getElementById('statusRequest'+requestId+'Refuse').parentNode.removeChild(document.getElementById('statusRequest'+requestId+'Refuse'));
                loaderContainer.classList.add("hidden");
            }
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
                            <td style="text-align: center; vertical-align: middle">${Number(response[0].detail_history_rent_book[i].book.price_rent).toLocaleString('vi-VN')} / 1 ngày</td>
                            <td style="text-align: center; vertical-align: middle">${response[0].detail_history_rent_book[i].quantity}</td>
                        </tr>`
                }
                $('#detailRequest').html(strHtml);
            }
        });
}
var formData;
document.getElementById('search').addEventListener('click',function () {
    event.preventDefault();

    formData = new FormData($('#formFilterRequest')[0]);
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/request-rent-book',
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formData,
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            var strHtml = '';
            var strStatus = '';
            var strBtnAction = '';
            const statusPending = 0;
            const statusBorrowing = 1;
            const statusReturned = 2;
            const statusRefuse = 3;
            for (var i=0;i<response.data.length;i++) {
                if (response.data[i].status === statusPending) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: blue">Đợi xác nhận</p>
                                        </td>`
                }
                else if (response.data[i].status === statusBorrowing) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: yellow">Đang mượn</p>
                                        </td>`
                }
                else if (response.data[i].status === statusReturned) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: green">Đã trả</p>
                                        </td>`
                }
                else {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: red">Từ chối</p>
                                        </td>`
                }
                if (response.data[i].status !== statusRefuse && response.data[i].status !== statusReturned) {
                    if (response.data[i].status === statusPending) {
                        strBtnAction = `<button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Accept" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Accept">Chấp nhận</button>
                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Refuse">Từ chối</button>`
                    }
                    else if (response.data[i].status === statusBorrowing) {
                        strBtnAction = `<button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Returned" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Returned">Đánh dấu đã trả</button>`
                    }
                }
                strHtml += `<tr class="odd">
                                    <td>${response.data[i].id}</td>
                                    <td>${response.data[i].rent_date}</td>
                                    <td>${response.data[i].expiration_date}</td>
                                    <td>${Number(response.data[i].total_price).toLocaleString('vi-VN')}</td>
                                    <td>${response.data[i].user.name}</td>
                                    ${strStatus}
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning">Hành động</button>
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest${response.data[i].id}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="${response.data[i].id}">Xem chi tiết</button>
                                                ${strBtnAction}
                                                <button style="cursor: pointer" class="dropdown-item exportInvoice" data-id="${response.data[i].id}">Xuất hóa đơn</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>`
            }
            $('#showRequest').html(strHtml);
            var strHtmlPaging = '';
            for (var i = 0; i < response.last_page; i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationForFilter(${i + 1})">${i + 1}</a></li>`;
            }
            $('#ulPagination').html(strHtmlPaging);
            loaderContainer.classList.add("hidden");
            exportInvoice = document.querySelectorAll('.exportInvoice');
            exportInvoice.forEach(exportInvoice => {
                exportInvoice.addEventListener('click', function(event) {
                    exportInvoiceUser(exportInvoice.dataset.id)
                });
            });
        }
    });
})

function loadPaginationForFilter(page) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: `http://localhost:8000/api/admin/filter/request-rent-book?page=${page}`,
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formData,
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            var strHtml = '';
            var strStatus = '';
            var strBtnAction = '';
            const statusPending = 0;
            const statusBorrowing = 1;
            const statusReturned = 2;
            const statusRefuse = 3;
            for (var i=0;i<response.data.length;i++) {
                if (response.data[i].status === statusPending) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: blue">Đợi xác nhận</p>
                                        </td>`
                }
                else if (response.data[i].status === statusBorrowing) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: yellow">Đang mượn</p>
                                        </td>`
                }
                else if (response.data[i].status === statusReturned) {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: green">Đã trả</p>
                                        </td>`
                }
                else {
                    strStatus = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: red">Từ chối</p>
                                        </td>`
                }
                if (response.data[i].status !== statusRefuse && response.data[i].status !== statusReturned) {
                    if (response.data[i].status === statusPending) {
                        strBtnAction = `<button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Accept" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Accept">Chấp nhận</button>
                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Refuse">Từ chối</button>`
                    }
                    else if (response.data[i].status === statusBorrowing) {
                        strBtnAction = `<button style="cursor: pointer" class="dropdown-item" id="statusRequest${response.data[i].id}Returned" data-toggle="modal" data-target="#exampleModal" data-id="${response.data[i].id}" data-value="Returned">Đánh dấu đã trả</button>`
                    }
                }
                strHtml += `<tr class="odd">
                                    <td>${response.data[i].id}</td>
                                    <td>${response.data[i].rent_date}</td>
                                    <td>${response.data[i].expiration_date}</td>
                                    <td>${Number(response.data[i].total_price).toLocaleString('vi-VN')}</td>
                                    <td>${response.data[i].user.name}</td>
                                    ${strStatus}
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning">Hành động</button>
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest${response.data[i].id}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="${response.data[i].id}">Xem chi tiết</button>
                                                ${strBtnAction}
                                                <button style="cursor: pointer" class="dropdown-item exportInvoice" data-id="${response.data[i].id}">Xuất hóa đơn</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>`
            }
            $('#showRequest').html(strHtml);
            loaderContainer.classList.add("hidden");
            exportInvoice = document.querySelectorAll('.exportInvoice');
            exportInvoice.forEach(exportInvoice => {
                exportInvoice.addEventListener('click', function(event) {
                    exportInvoiceUser(exportInvoice.dataset.id)
                });
            });
        }
    });
}
exportInvoice.forEach(exportInvoice => {
    exportInvoice.addEventListener('click', function(event) {
        exportInvoiceUser(exportInvoice.dataset.id)
    });
});

function exportInvoiceUser(requestId) {
    $.ajax({
        url : 'http://localhost:8000/api/admin/export-invoice',
        method : 'POST',
        contentType: "application/json; charset=utf-8",
        xhrFields: {
            responseType: 'blob' // Để xác định kiểu dữ liệu là blob (file)
        },
        data : JSON.stringify({
            "requestId" : requestId,
        }),
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.log(status);
            console.log(error);
        },
        success: function (response) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(response);
            a.href = url;
            a.download = 'invoice.xlsx';
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        },
    })
}
