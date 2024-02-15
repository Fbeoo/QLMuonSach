$(function() {
    var currentDate = moment().format('MM/DD/YYYY');
    $('input[name="dateRent"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),10),
        locale: {
            format: 'DD/MM/Y'
        }
    }).on('apply.daterangepicker', function (ev, picker) {
        changeDateRent(document.getElementById('dateRent'));
    });
});
var removeBook = document.querySelectorAll('.removeBook');
function changeNumberBook(line,quantity) {
    $.ajax({
        url: 'http://localhost:8000/api/change-number-book/' + line,
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "line" : line,
            "quantity" : quantity
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            $('#linePrice'+line).text(response.bookInCart[line-1].linePrice);
            $('#totalPrice').text(response.totalPrice);
            $('#countBookInCart').text(response.totalBookInCart);
        }
    });
}

function removeBookInCart(line) {
    $.ajax({
        url: 'http://localhost:8000/api/remove-book-in-cart/' + line,
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "line" : line
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            var strHtml = '';
            console.log(response)
            for (var i=0;i<Object.keys(response.bookInCart).length;i++) {
                strHtml += `<tr class="odd">
                                    <td style="width: 150px; height: 150px">
                                        <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="http://localhost:8000/storage/${response.bookInCart[i].book.thumbnail}">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">${response.bookInCart[i].book.name}</td>
                                    <td style="text-align: center; vertical-align: middle">${Number(response.bookInCart[i].book.price_rent).toLocaleString('vi-VN')} / 1 ngày</td>
                                    <td style="text-align: center; vertical-align: middle; width: 150px">
                                        <input class="quantityBook" type="number" style="width: 80px;" value="${response.bookInCart[i].quantityLine}" data-line = "${response.bookInCart[i].line}">
                                    </td>
                                    <td id="linePrice${response.bookInCart[i].line}" style="text-align: center; vertical-align: middle">${response.bookInCart[i].linePrice}</td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <a style="cursor: pointer" class="removeBook" data-line="${response.bookInCart[i].line}">
                                            <i class="fa fa-window-close"></i>
                                        </a>
                                    </td>
                                </tr>`
            }
            $('#cartContent').html(strHtml);
            $('#totalPrice').text(response.totalPrice);
            $('#countBookInCart').text(response.totalBookInCart);
            removeBook = document.querySelectorAll('.removeBook');
            console.log(removeBook);
            removeBook.forEach(removeBook => {
                removeBook.addEventListener('click', function(event) {
                    event.preventDefault();
                    const line = removeBook.dataset.line;
                    removeBookInCart(line);
                    console.log(removeBook);
                });
            });
            $('.quantityBook').change(function () {
                const inputElement = event.target;
                const line = inputElement.dataset.line;
                const quantity = inputElement.value;
                changeNumberBook(line,quantity);
            })
        }
    });
}

function changeDateRent(dateRent) {
    $.ajax({
        url: 'http://localhost:8000/api/change-date-rent/',
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "dateRent" : document.getElementById('dateRent').value
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            $('dateRentError').empty();
            if (response.errorValidate) {
                $('#dateRentError').text(response.errorValidate['dateRent'][0]);
                $('#dateRentError').css('color', 'red');
                return;
            }
            else if (response.error) {
                alert(response.error);
                return;
            }
            else {
                for (var i=0;i<response.bookInCart.length;i++) {
                    $('#linePrice'+(i+1)).text(response.bookInCart[i].linePrice);
                }
                $('#totalPrice').text(response.totalPrice);
            }
        }
    });
}

function rentMultiBook() {
    $.ajax({
        url: 'http://localhost:8000/api/validate-rent-multi-book',
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "dateRent" : document.getElementById('dateRent').value,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            $('#dateRentError').empty();
            console.log(response);
            if (response.errorValidate) {
                $('#dateRentError').text(response.errorValidate['dateRent'][0]);
                $('#dateRentError').css('color', 'red');
                return;
            }
            else if (response.error) {
                alert(response.error);
                return;
            }
            else {
                $('#formRentMultiBook').submit();
            }
        }
    });
}

$('.quantityBook').change(function () {
    const inputElement = event.target;
    const line = inputElement.dataset.line;
    const quantity = inputElement.value;
    changeNumberBook(line,quantity);
})

removeBook.forEach(removeBook => {
    removeBook.addEventListener('click', function(event) {
        event.preventDefault();
        const line = removeBook.dataset.line;
        removeBookInCart(line);
        console.log(removeBook);
    });
});

document.getElementById('rentBook').addEventListener('click',function () {
    rentMultiBook();
})
