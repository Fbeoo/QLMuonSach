function searchBook(bookName) {
    $('#search').prop('disabled',true);
    $.ajax({
        url: 'http://localhost:8000/api/admin/search/book/' + bookName,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
            var strHtml = '';
            for (var i=0;i<response.length;i++) {
                var pathImage = 'http://localhost:8000/storage/' + response[i].thumbnail;
                var strStatus = '';
                var strAction = '';
                if (response[i].status === 1) {
                    strStatus = `<td style="color: green" id="statusBook${response[i].id}">Bình thường</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response[i].id}" onclick="lockBook(${response[i].id})">Khóa</button>`
                }
                else {
                    strStatus = `<td style="color: red" id="statusBook${response[i].id}">Khóa</td>`
                    strAction = `<button style="cursor: pointer" class="dropdown-item" id="actionStatusBook${response[i].id}" onclick="unlockBook(${response[i].id})">Mở khóa</button>`
                }
                strHtml += `<tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">${response[i].id}</td>
                            <td>${response[i].name}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="${pathImage}" style="max-width: 100%">
                            </td>
                            <td>${response[i].year_publish}</td>
                            <td>${response[i].price_rent}</td>
                            <td>${response[i].weight}</td>
                            <td>${response[i].total_page}</td>
                            <td>${response[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="{{route('editBookPage',['bookId'=>$book->id])}}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            $('#showBooks').html(strHtml);
            $('#search').prop('disabled',false);
        }
    });
}

function sortBookByYearPublish(type) {
    $.ajax({
        url: 'http://localhost:8000/api/admin/sort/book/year_publish/' + type,
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('error to call api');
        },
        success: function (response) {
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
                            <td>${response.data[i].price_rent}</td>
                            <td>${response.data[i].weight}</td>
                            <td>${response.data[i].total_page}</td>
                            <td>${response.data[i].quantity}</td>
                            ${strStatus}
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="{{route('editBookPage',['bookId'=>$book->id])}}">Sửa</a>
                                        ${strAction}
                                    </div>
                                </div>
                            </td>
                            </tr>`
            }
            $('#showBooks').html(strHtml);
            console.log(response);
        }
    });
}

document.getElementById('search').addEventListener('click',function () {
    searchBook(document.getElementById('bookSearch').value);
})

var selectSortBookByYearPublish = document.getElementById('sortBookByYearPublish');
selectSortBookByYearPublish.addEventListener('change',function () {
    sortBookByYearPublish(selectSortBookByYearPublish.value);
})
