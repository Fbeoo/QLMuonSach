$(document).ready(function () {
    $(".viewRequestOfUserModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        loadRequestOfUser(button.data("id"));
    });

    $(".viewDetailRequestModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        console.log(button.data("id"))
        showDetailRequest(button.data("id"));
    });
})
function loadRequestOfUser(userId) {
    $('#allRequestOfUser').empty();
    $('#ulPaginationRequestOfUser').empty();
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/request-rent-book/'+ userId,
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response)
            var strHtml = '';
            var strHtmlPaging = '';
            for (var i=0;i<response.data.length;i++) {
                var strStatusRequest = '';
                if (response.data[i].status === 0) {
                    strStatusRequest = ` <td>
                                            <p id="statusRequest${response.data[i].id}" style="color: blue">Đợi xác nhận</p>
                                        </td>`
                }
                else if (response.data[i].status === 1) {
                    strStatusRequest = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: yellow">Đang mượn</p>
                                        </td>`
                }
                else if (response.data[i].status === 2) {
                    strStatusRequest = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: green">Đã trả</p>
                                        </td>`
                }
                else if (response.data[i].status === 3) {
                    strStatusRequest = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: red">Từ chối</p>
                                        </td>`
                }
                strHtml += `<tr class="odd">
                                    <td>${response.data[i].id}</td>
                                    <td>${response.data[i].rent_date}</td>
                                    <td>${response.data[i].expiration_date}</td>
                                    <td>${response.data[i].total_price}</td>
                                    ${strStatusRequest}
                                    <td>
                                        <div class="btn-group">
<!--                                            <button type="button" class="btn btn-warning">Hành động</button>-->
<!--                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">-->
<!--                                                <span class="sr-only">Toggle Dropdown</span>-->
<!--                                            </button>-->
<!--                                            <div class="dropdown-menu" role="menu" style="">-->
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest{{$request->id}}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="${response.data[i].id}">Xem chi tiết</button>
<!--                                                @if($request->status !== \\App\\Models\\HistoryRentBook::statusRefuse && $request->status !== \\App\\Models\\HistoryRentBook::statusReturned)-->
<!--                                                    @if($request->status === \\App\\Models\\HistoryRentBook::statusPending)-->
<!--                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Accept" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Accept">Chấp nhận</button>-->
<!--                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Refuse">Từ chối</button>-->
<!--                                                    @elseif($request->status === \\App\\Models\\HistoryRentBook::statusBorrowing)-->
<!--                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Returned" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Returned">Đánh dấu đã trả</button>-->
<!--                                                    @endif-->
<!--                                                @endif-->
<!--                                            </div>-->
                                        </div>
                                    </td>
                                </tr>`
            }
            for (var i=0;i<response.last_page;i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationForRequestOfUser(${i + 1},${userId})">${i + 1}</a></li>`
            }
            $('#allRequestOfUser').html(strHtml);
            $('#ulPaginationRequestOfUser').html(strHtmlPaging);
        }
    });
}

function loadPaginationForRequestOfUser(page,userId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/request-rent-book/'+ userId+'?page=' + page,
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            console.log(response);
            var strHtml = '';
            const statusPending = 0;
            const statusBorrowing = 1;
            const statusReturned = 2;
            const statusRefuse = 3;
            for (var i=0;i<response.data.length;i++) {
                var strStatusRequest = '';
                if (response.data[i].status === statusPending) {
                    strStatusRequest = ` <td>
                                            <p id="statusRequest${response.data[i].id}" style="color: blue">Đợi xác nhận</p>
                                        </td>`
                }
                else if (response.data[i].status === statusBorrowing) {
                    strStatusRequest = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: yellow">Đang mượn</p>
                                        </td>`
                }
                else if (response.data[i].status === statusReturned) {
                    strStatusRequest = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: green">Đã trả</p>
                                        </td>`
                }
                else if (response.data[i].status === statusRefuse) {
                    strStatusRequest = `<td>
                                            <p id="statusRequest${response.data[i].id}" style="color: red">Từ chối</p>
                                        </td>`
                }
                strHtml += `<tr class="odd">
                                    <td>${response.data[i].id}</td>
                                    <td>${response.data[i].rent_date}</td>
                                    <td>${response.data[i].expiration_date}</td>
                                    <td>${response.data[i].total_price}</td>
                                    ${strStatusRequest}
                                    <td>
                                        <div class="btn-group">
<!--                                            <button type="button" class="btn btn-warning">Hành động</button>-->
<!--                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">-->
<!--                                                <span class="sr-only">Toggle Dropdown</span>-->
<!--                                            </button>-->
<!--                                            <div class="dropdown-menu" role="menu" style="">-->
                                                <button style="cursor: pointer" class="dropdown-item" id="detailRequest${response.data[i].id}" data-toggle="modal" data-target=".viewDetailRequestModal" data-id="${response.data[i].id}">Xem chi tiết</button>
<!--                                                @if($request->status !== \\App\\Models\\HistoryRentBook::statusRefuse && $request->status !== \\App\\Models\\HistoryRentBook::statusReturned)-->
<!--                                                    @if($request->status === \\App\\Models\\HistoryRentBook::statusPending)-->
<!--                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Accept" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Accept">Chấp nhận</button>-->
<!--                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Refuse" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Refuse">Từ chối</button>-->
<!--                                                    @elseif($request->status === \\App\\Models\\HistoryRentBook::statusBorrowing)-->
<!--                                                        <button style="cursor: pointer" class="dropdown-item" id="statusRequest{{$request->id}}Returned" data-toggle="modal" data-target="#exampleModal" data-id="{{$request->id}}" data-value="Returned">Đánh dấu đã trả</button>-->
<!--                                                    @endif-->
<!--                                                @endif-->
<!--                                            </div>-->
                                        </div>
                                    </td>
                                </tr>`
            }
            $('#allRequestOfUser').html(strHtml);
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
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response);
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

function lockUser(userId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/lock/user',
        method: 'PUT',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "id" : userId,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function () {
            alert('Khóa người dùng thành công');

        }
    });
}

function unlockUser(userId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/unlock/user',
        method: 'PUT',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "id" : userId,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function () {

        }
    });
}
