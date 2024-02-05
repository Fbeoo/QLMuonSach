function rentMultiBook() {
    $.ajax({
        url: 'http://localhost:8000/api/rent-multi-book',
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "dateRent" : document.getElementById('dateExpire').innerHTML,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            alert('Thuê sách thành công');
            window.location.href = "http://localhost:8000";
        }
    });
}

function rentSingleBook() {
    $.ajax({
        url: 'http://localhost:8000/api/rent-single-book',
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify({
            'bookId' : document.getElementById('bookId').value,
            'quantityRent' : document.getElementById('quantityRent').value,
            'totalPrice' : document.getElementById('totalPrice').value,
            'dateExpire' : document.getElementById('dateExpire').innerHTML
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            alert('Thuê sách thành công');
            window.location.href = "http://localhost:8000";
        }
    });
}
if (document.getElementById('rentMultiBook')) {
    document.getElementById('rentMultiBook').addEventListener('click',function () {
        rentMultiBook();
    })
}
else if (document.getElementById('rentSingleBook')) {
    document.getElementById('rentSingleBook').addEventListener('click',function () {
        rentSingleBook();
    });
}

