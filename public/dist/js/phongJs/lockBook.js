function lockBook(bookId) {
    $.ajax({
        url: 'http://localhost:8000/api/admin/lock/book',
        method: 'PUT',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "id" : bookId,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function () {
            alert('Khóa sách thành công');
            document.getElementById('actionStatusBook'+bookId).innerHTML = 'Mở khóa'
            document.getElementById('statusBook'+ bookId).innerHTML = 'Khóa'
            document.getElementById('statusBook'+bookId).style.color = "red"
            document.getElementById('actionStatusBook'+bookId).onclick = function () {
                unlockBook(bookId);
            };
        }
    });
}

function unlockBook(bookId) {
    $.ajax({
        url: 'http://localhost:8000/api/admin/unlock/book',
        method: 'PUT',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "id" : bookId,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function () {
            alert('Mở khóa sách thành công');
            document.getElementById('actionStatusBook'+bookId).innerHTML = 'Khóa'
            document.getElementById('statusBook'+ bookId).innerHTML = 'Bình thường'
            document.getElementById('statusBook'+bookId).style.color = "green"
            document.getElementById('actionStatusBook'+bookId).onclick = function () {
                lockBook(bookId);
            };
        }
    });
}
