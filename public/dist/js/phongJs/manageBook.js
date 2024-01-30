document.addEventListener('DOMContentLoaded', function() {
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/book',
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
});

var selectSortBookByYearPublish = document.getElementById('sortBookByYearPublish');
var selectFilterBookByStatus = document.getElementById('filterBookByStatus');
var selectCategoryParent = document.getElementById('filterBookByCategoryParent');
var selectCategoryChildren = document.getElementById('filterBookByCategoryChildren');

function loadPagination(page) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: `http://localhost:8000/api/admin/all/book/?page=${page}`,
        method: 'GET',
        contentType: 'json',
        error: function(response) {
            console.log('error to call api');
        },
        success: function(response) {
            var strHtml = '';
            for (var i=0;i<response.data.length;i++) {
                var pathImage = 'http://localhost:8000/storage/' + response.data[i].thumbnail;
                var strStatus = '';
                var strAction = '';
                if (response.data[i].status === 1) {
                    strStatus = `<td style="color: green" id="statusBook${response.data[i].id}">Bình thường</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.data[i].id}" onclick="lockBook(${response.data[i].id})">Khóa</button>`
                }
                else {
                    strStatus = `<td style="color: red" id="statusBook${response.data[i].id}">Khóa</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.data[i].id}" onclick="unlockBook(${response.data[i].id})">Mở khóa</button>`
                }
                strHtml += `<tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">${response.data[i].id}</td>
                            <td>${response.data[i].name}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="${pathImage}" style="max-width: 100%">
                            </td>
                            <td>${response.data[i].year_publish}</td>
                            <td>${Number(response.data[i].price_rent).toLocaleString('vi-VN')}</td>
                            <td>
                                ${response.data[i].category.category_name}
                            </td>
                            <td>
                                ${response.data[i].author_book[0].author_info.author_name}
                            </td>
                            <td>${response.data[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="http://localhost:8000/admin/edit/book/${response.data[i].id}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            $('#showBooks').html(strHtml);
            loaderContainer.classList.add("hidden");
        }
    });
}

function getCategoryChildren(categoryParentId) {
    let data;
    $.ajax({
        url: 'http://localhost:8000/api/category/children/' + categoryParentId,
        method: 'GET',
        contentType: 'json',
        async: true,
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            if (response.error) {
                alert(response.error);
                return;
            }
            var strHtml = '<option value="">Chọn danh mục con</option>';
            for (var i=0;i<response.length;i++) {
                strHtml += `<option value="${response[i].id}">${response[i].category_name}</option>`
            }
            $('#filterBookByCategoryChildren').html(strHtml);
        }
    });
}
selectCategoryParent.addEventListener('change',function () {
    // filterBookByCategoryParent(selectCategoryParent.value);
    getCategoryChildren(selectCategoryParent.value);
})

var formData;
document.getElementById('search').addEventListener('click',function (){
    event.preventDefault();
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    formData = new FormData($('#formFilterBook')[0]);
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/book',
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
            console.log(response)
            var strHtml = '';
            var strHtmlPaging = '';
            for (var i=0;i<response.data.length;i++) {
                var pathImage = 'http://localhost:8000/storage/' + response.data[i].thumbnail;
                var strStatus = '';
                var strAction = '';
                if (response.data[i].status === 1) {
                    strStatus = `<td style="color: green" id="statusBook${response.data[i].id}">Bình thường</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.data[i].id}" onclick="lockBook(${response.data[i].id})">Khóa</button>`
                }
                else {
                    strStatus = `<td style="color: red" id="statusBook${response.data[i].id}">Khóa</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.data[i].id}" onclick="unlockBook(${response.data[i].id})">Mở khóa</button>`
                }
                strHtml += `<tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">${response.data[i].id}</td>
                            <td>${response.data[i].name}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="${pathImage}" style="max-width: 100%">
                            </td>
                            <td>${response.data[i].year_publish}</td>
                            <td>${Number(response.data[i].price_rent).toLocaleString('vi-VN')}</td>
                            <td>
                                ${response.data[i].category.category_name}
                            </td>
                            <td>
                                ${response.data[i].author_book[0].author_info.author_name}
                            </td>
                            <td>${response.data[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="http://localhost:8000/admin/edit/book/${response.data[i].id}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            for (var i=0;i<response.last_page;i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationAfterFilter(${i + 1})">${i + 1}</a></li>`;
            }
            console.log(strHtmlPaging);
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            loaderContainer.classList.add("hidden");
        }
    });
});
function loadPaginationAfterFilter(page) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: `http://localhost:8000/api/admin/filter/book?page=${page}`,
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formData,
        error: function(response) {
            console.log('error to call api');
        },
        success: function(response) {
            console.log(response);
            var strHtml = '';
            for (var i=0;i<response.data.length;i++) {
                var pathImage = 'http://localhost:8000/storage/' + response.data[i].thumbnail;
                var strStatus = '';
                var strAction = '';
                if (response.data[i].status === 1) {
                    strStatus = `<td style="color: green" id="statusBook${response.data[i].id}">Bình thường</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.data[i].id}" onclick="lockBook(${response.data[i].id})">Khóa</button>`
                }
                else {
                    strStatus = `<td style="color: red" id="statusBook${response.data[i].id}">Khóa</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.data[i].id}" onclick="unlockBook(${response.data[i].id})">Mở khóa</button>`
                }
                strHtml += `<tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">${response.data[i].id}</td>
                            <td>${response.data[i].name}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="${pathImage}" style="max-width: 100%">
                            </td>
                            <td>${response.data[i].year_publish}</td>
                            <td>${Number(response.data[i].price_rent).toLocaleString('vi-VN')}</td>
                            <td>
                                ${response.data[i].category.category_name}
                            </td>
                            <td>
                                ${response.data[i].author_book[0].author_info.author_name}
                            </td>
                            <td>${response.data[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="http://localhost:8000/admin/edit/book/${response.data[i].id}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            $('#showBooks').html(strHtml);
            loaderContainer.classList.add("hidden");
        }
    });
}
