$(document).ready(function () {
    getPagination();

    $(".viewRequestOfUserModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        loadRequestOfUser(button.data("id"));
    });

    $(".viewDetailRequestModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        console.log(button.data("id"))
        showDetailRequest(button.data("id"));
    });

    $("#confirmActionStatusUser").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        var buttonActionStatus = document.getElementById('actionStatus');
        if (button.attr("data-value") === "Lock") {
            $('#notification').text("Bạn có muốn khóa người dùng")
            buttonActionStatus.textContent = "Khóa";
            buttonActionStatus.onclick = function () {
                lockUser(button.data("id"));
            }
        }
        else if (button.attr("data-value") === "Unlock") {
            $('#notification').text("Bạn có muốn mở khóa người dùng")
            buttonActionStatus.textContent = "Mở khóa";
            buttonActionStatus.onclick = function () {
                unlockUser(button.data("id"));
            }
        }
    });
})

function getPagination() {
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/user',
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            strHtmlPaging = '';
            for (var i=0;i<response.last_page;i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPagination(${i + 1})">${i + 1}</a></li>`
            }
            $('#ulPagination').html(strHtmlPaging);
        }
    });
}

function loadPagination(page) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/user?page='+page,
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            var strHtml = '';
            const statusLock = 0;
            const statusNormal = 1;
            for (var i=0;i<response.data.length;i++) {
                var userStatus = '';
                var actionStatus = '';
                if (response.data[i].status === statusLock) {
                    userStatus = `<p id="userStatus${response.data[i].id}" style="color: red">Khóa</p>`
                    actionStatus = `<a id="actionStatus${response.data[i].id}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "${response.data[i].id}" data-value = "Unlock">Mở khóa</a>`
                }
                else if (response.data[i].status === statusNormal) {
                    userStatus = `<p id="userStatus${response.data[i].id}" style="color: green">Bình thường</p>`
                    actionStatus = `<a id="actionStatus${response.data[i].id}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "${response.data[i].id}" data-value = "Lock">Khóa</a>`
                }
                strHtml += `<tr>
                                <td>${response.data[i].id}</td>
                                <td>${response.data[i].name}</td>
                                <td>${response.data[i].mail}</td>
                                <td style="text-align: center">${response.data[i].requestStatusPending}</td>
                                <td style="text-align: center">${response.data[i].requestStatusBorrowing}</td>
                                <td style="text-align: center">${response.data[i].requestStatusReturned}</td>
                                <td style="text-align: center">${response.data[i].requestStatusRefuse}</td>
                                <td>
                                    ${userStatus}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning">Hành động</button>
                                        <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu" style="">
                                            <a style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target=".viewRequestOfUserModal" data-id = "${response.data[i].id}">Xem yêu cầu mượn</a>
                                            ${actionStatus}
                                        </div>
                                    </div>
                                </td>
                            </tr>`
            }
            $('#user').html(strHtml);
            loaderContainer.classList.add("hidden");
        }
    });
}

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
            document.getElementById('actionStatus'+userId).innerHTML = 'Mở khóa';
            document.getElementById('actionStatus'+userId).setAttribute('data-value', 'Unlock');
            document.getElementById('userStatus'+userId).innerHTML = 'Khóa'
            document.getElementById('userStatus'+userId).style.color = 'red'
            loaderContainer.classList.add("hidden");
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
            alert('Mở khóa người dùng thành công')
            console.log('actionStatus'+userId)
            document.getElementById('actionStatus'+userId).innerHTML = 'Khóa';
            document.getElementById('actionStatus'+userId).setAttribute('data-value', 'Lock');
            document.getElementById('userStatus'+userId).innerHTML = 'Bình thường'
            document.getElementById('userStatus'+userId).style.color = 'green'
            loaderContainer.classList.add("hidden");
        }
    });
}

var formDataFilterUser;
document.getElementById('search').addEventListener('click',function () {
    event.preventDefault();

    formDataFilterUser = new FormData($('#formFilterUser')[0]);

    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/user',
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formDataFilterUser,
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response)
            var strHtml = '';
            var strHtmlPaging = '';
            const statusLock = 0;
            const statusNormal = 1;
            for (var i=0;i<response.data.length;i++) {
                var userStatus = '';
                var actionStatus = '';
                if (response.data[i].status === statusLock) {
                    userStatus = `<p id="userStatus${response.data[i].id}" style="color: red">Khóa</p>`
                    actionStatus = `<a id="actionStatus${response.data[i].id}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "${response.data[i].id}" data-value = "Unlock">Mở khóa</a>`
                }
                else if (response.data[i].status === statusNormal) {
                    userStatus = `<p id="userStatus${response.data[i].id}" style="color: green">Bình thường</p>`
                    actionStatus = `<a id="actionStatus${response.data[i].id}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "${response.data[i].id}" data-value = "Lock">Khóa</a>`
                }
                strHtml += `<tr>
                                <td>${response.data[i].id}</td>
                                <td>${response.data[i].name}</td>
                                <td>${response.data[i].mail}</td>
                                <td style="text-align: center">${response.data[i].requestStatusPending}</td>
                                <td style="text-align: center">${response.data[i].requestStatusBorrowing}</td>
                                <td style="text-align: center">${response.data[i].requestStatusReturned}</td>
                                <td style="text-align: center">${response.data[i].requestStatusRefuse}</td>
                                <td>
                                    ${userStatus}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning">Hành động</button>
                                        <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu" style="">
                                            <a style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target=".viewRequestOfUserModal" data-id = "${response.data[i].id}">Xem yêu cầu mượn</a>
                                            ${actionStatus}
                                        </div>
                                    </div>
                                </td>
                            </tr>`
            }
            for (var i=0;i<response.last_page;i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationForFilter(${i + 1})">${i + 1}</a></li>`
            }
            $('#ulPagination').html(strHtmlPaging);
            $('#user').html(strHtml);
            loaderContainer.classList.add("hidden");
        }
    });
})

function loadPaginationForFilter(page) {
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/user?page='+page,
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formDataFilterUser,
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            var strHtml = '';
            const statusLock = 0;
            const statusNormal = 1;
            for (var i=0;i<response.data.length;i++) {
                var userStatus = '';
                var actionStatus = '';
                if (response.data[i].status === statusLock) {
                    userStatus = `<p id="userStatus${response.data[i].id}" style="color: red">Khóa</p>`
                    actionStatus = `<a id="actionStatus${response.data[i].id}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "${response.data[i].id}" data-value = "Unlock">Mở khóa</a>`
                }
                else if (response.data[i].status === statusNormal) {
                    userStatus = `<p id="userStatus${response.data[i].id}" style="color: green">Bình thường</p>`
                    actionStatus = `<a id="actionStatus${response.data[i].id}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#confirmActionStatusUser" data-id = "${response.data[i].id}" data-value = "Lock">Khóa</a>`
                }
                strHtml += `<tr>
                                <td>${response.data[i].id}</td>
                                <td>${response.data[i].name}</td>
                                <td>${response.data[i].mail}</td>
                                <td style="text-align: center">${response.data[i].requestStatusPending}</td>
                                <td style="text-align: center">${response.data[i].requestStatusBorrowing}</td>
                                <td style="text-align: center">${response.data[i].requestStatusReturned}</td>
                                <td style="text-align: center">${response.data[i].requestStatusRefuse}</td>
                                <td>
                                    ${userStatus}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning">Hành động</button>
                                        <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu" style="">
                                            <a style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target=".viewRequestOfUserModal" data-id = "${response.data[i].id}">Xem yêu cầu mượn</a>
                                            ${actionStatus}
                                        </div>
                                    </div>
                                </td>
                            </tr>`
            }
            $('#user').html(strHtml);
            loaderContainer.classList.add("hidden");
        }
    });
}
