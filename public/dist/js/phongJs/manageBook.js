var selectSortBookByYearPublish = document.getElementById('sortBookByYearPublish');
var selectFilterBookByStatus = document.getElementById('filterBookByStatus');
var selectCategoryParent = document.getElementById('filterBookByCategoryParent');
var selectCategoryChildren = document.getElementById('filterBookByCategoryChildren');
function searchBook(bookName) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: 'http://localhost:8000/api/admin/search/book/' + bookName,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            var strHtml = '';
            var strHtmlPaging = '';
            for (var i=0;i<response.books.data.length;i++) {
                var pathImage = 'http://localhost:8000/storage/' + response.books.data[i].thumbnail;
                var strStatus = '';
                var strAction = '';
                if (response.books.data[i].status === 1) {
                    strStatus = `<td style="color: green" id="statusBook${response.books.data[i].id}">Bình thường</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.books.data[i].id}" onclick="lockBook(${response.books.data[i].id})">Khóa</button>`
                }
                else {
                    strStatus = `<td style="color: red" id="statusBook${response.books.data[i].id}">Khóa</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.books.data[i].id}" onclick="unlockBook(${response.books.data[i].id})">Mở khóa</button>`
                }
                strHtml += `<tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">${response.books.data[i].id}</td>
                            <td>${response.books.data[i].name}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="${pathImage}" style="max-width: 100%">
                            </td>
                            <td>${response.books.data[i].year_publish}</td>
                            <td>${Number(response.books.data[i].price_rent).toLocaleString('vi-VN')}</td>
                            <td>
                                ${response.books.data[i].category.category_name}
                            </td>
                            <td>
                                ${response.books.data[i].author_book[0].author_info.author_name}
                            </td>
                            <td>${response.books.data[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="http://localhost:8000/admin/edit/book/${response.books.data[i].id}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            for (var i = 0; i < response.totalPage; i++) {
                strHtmlPaging += `<li><a onclick="loadPaginationSearch(${i + 1}, '${bookName}')">${i + 1}</a></li>`;            }
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}
function loadPaginationSearch(page, bookName) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: `http://localhost:8000/api/admin/search/book/${bookName}?page=${page}`,
        method: 'GET',
        contentType: 'json',
        error: function(response) {
            console.log('error to call api');
        },
        success: function(response) {
            var strHtml = '';
            for (var i=0;i<response.books.data.length;i++) {
                var pathImage = 'http://localhost:8000/storage/' + response.books.data[i].thumbnail;
                var strStatus = '';
                var strAction = '';
                if (response.books.data[i].status === 1) {
                    strStatus = `<td style="color: green" id="statusBook${response.books.data[i].id}">Bình thường</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.books.data[i].id}" onclick="lockBook(${response.books.data[i].id})">Khóa</button>`
                }
                else {
                    strStatus = `<td style="color: red" id="statusBook${response.books.data[i].id}">Khóa</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response.books.data[i].id}" onclick="unlockBook(${response.books.data[i].id})">Mở khóa</button>`
                }
                strHtml += `<tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">${response.books.data[i].id}</td>
                            <td>${response.books.data[i].name}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="${pathImage}" style="max-width: 100%">
                            </td>
                            <td>${response.books.data[i].year_publish}</td>
                            <td>${Number(response.books.data[i].price_rent).toLocaleString('vi-VN')}</td>
                            <td>
                                ${response.books.data[i].category.category_name}
                            </td>
                            <td>
                                ${response.books.data[i].author_book[0].author_info.author_name}
                            </td>
                            <td>${response.books.data[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="http://localhost:8000/admin/edit/book/${response.books.data[i].id}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            $('#showBooks').html(strHtml);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function sortBookByYearPublish(type) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: 'http://localhost:8000/api/admin/sort/book/year_publish/' + type,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
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
            for (var i = 0; i < response.meta.last_page; i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationSortByYearPublish(${i + 1}, '${type}')">${i + 1}</a></li>`;
            }
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function loadPaginationSortByYearPublish(page, type) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: `http://localhost:8000/api/admin/sort/book/year_publish/${type}?page=${page}`,
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
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function filterBookByStatus(type) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/book/status/' + type,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
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
            for (var i = 0; i < response.meta.last_page; i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationFilterByStatus(${i + 1}, '${type}')">${i + 1}</a></li>`;
            }
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function loadPaginationFilterByStatus(page, type) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: `http://localhost:8000/api/admin/filter/book/status/${type}?page=${page}`,
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
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function filterBookByCategoryParent(categoryParentId) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/book/category_parent/' + categoryParentId,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
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
            for (var i = 0; i < response.last_page; i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationFilterByCategoryParent(${i + 1}, '${categoryParentId}')">${i + 1}</a></li>`;
            }
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function loadPaginationFilterByCategoryParent(page, categoryParentId) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: `http://localhost:8000/api/admin/filter/book/category_parent/${categoryParentId}?page=${page}`,
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
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
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
            var strHtml = '';
            for (var i=0;i<response.length;i++) {
                strHtml += `<option value="${response[i].id}">${response[i].category_name}</option>`
            }
            $('#filterBookByCategoryChildren').html(strHtml);
        }
    });
}

function filterBookByCategoryChildren(categoryChildrenId) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/book/category_children/' + categoryChildrenId,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
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
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationFilterByCategoryChildren(${i + 1}, '${categoryChildrenId}')">${i + 1}</a></li>`;
            }
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function loadPaginationFilterByCategoryChildren(page, categoryChildrenId) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: `http://localhost:8000/api/admin/filter/book/category_children/${categoryParentId}?page=${page}`,
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
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function filterBookByRangeOfYear() {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    var minYear = document.getElementById('minYear').value;
    var maxYear = document.getElementById('maxYear').value;
    $.ajax({
        url: 'http://localhost:8000/api/admin/filter/book/year_publish/range',
        method: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data:JSON.stringify({
            'minYear': minYear,
            'maxYear' : maxYear
        }),
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            var strHtml = '';
            var strHtmlPaging = '';
            if (response.errorValidate) {
                for (var key in response.errorValidate) {
                    strHtml = `<p style="color: red">${response.errorValidate[key][0]}</p>`
                    $('#'+key+"Error").html(strHtml);
                }
                $('#search').prop('disabled',false);
                $('#searchByYear').prop('disabled',false);
                selectFilterBookByStatus.disabled = false;
                selectSortBookByYearPublish.disabled = false;
                selectCategoryChildren.disabled = false;
                selectCategoryParent.disabled = false;
                return
            }
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
            for (var i=0;i<response.meta.last_page;i++) {
                strHtmlPaging += `<li><a style="cursor: pointer" onclick="loadPaginationFilterByRangeOfYear(${i + 1}, '${minYear}','${maxYear}')">${i + 1}</a></li>`;
            }
            console.log(strHtmlPaging);
            console.log(response.meta.last_page);
            $('#showBooks').html(strHtml);
            $('#ulPagination').html(strHtmlPaging);
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

function loadPaginationFilterByRangeOfYear(page, minYear,maxYear) {
    $('#search').prop('disabled',true);
    selectFilterBookByStatus.disabled = true;
    selectSortBookByYearPublish.disabled = true;
    selectCategoryChildren.disabled = true;
    selectCategoryParent.disabled = true;
    $('#searchByYear').prop('disabled',true);
    $.ajax({
        url: `http://localhost:8000/api/admin/filter/book/year_publish/${minYear}/to/${maxYear}?page=${page}`,
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
            $('#search').prop('disabled',false);
            $('#searchByYear').prop('disabled',false);
            selectFilterBookByStatus.disabled = false;
            selectSortBookByYearPublish.disabled = false;
            selectCategoryChildren.disabled = false;
            selectCategoryParent.disabled = false;
        }
    });
}

document.getElementById('search').addEventListener('click',function () {
    searchBook(document.getElementById('bookSearch').value);
    selectSortBookByYearPublish.selectedIndex = 0;
    selectFilterBookByStatus.selectedIndex = 0;

})

selectSortBookByYearPublish.addEventListener('change',function () {
    sortBookByYearPublish(selectSortBookByYearPublish.value);
})

selectFilterBookByStatus.addEventListener('change',function () {
    filterBookByStatus(selectFilterBookByStatus.value);
})

selectCategoryParent.addEventListener('change',function () {
    filterBookByCategoryParent(selectCategoryParent.value);
    getCategoryChildren(selectCategoryParent.value);
})

selectCategoryChildren.addEventListener('change',function () {
    filterBookByCategoryChildren(selectCategoryChildren.value);
})
document.getElementById('searchByYear').addEventListener('click',function () {
    filterBookByRangeOfYear();
});

