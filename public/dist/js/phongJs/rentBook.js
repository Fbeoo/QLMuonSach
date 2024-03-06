document.getElementById('addToCart').addEventListener('click',function () {
    event.preventDefault();
    var formData = new FormData($('#formAddToCart')[0]);
    $.ajax({
        url: 'http://localhost:8000/api/add-to-cart',
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
            if (response.success) {
                $('#countBookInCart').text(response.cart.totalBookInCart);
                alert('Thêm vào giỏ hàng thành công')
            }
            else if (response.error) {
                alert(response.error);
            }
        }
    });
});

document.getElementById('rent').addEventListener('click',function () {
    event.preventDefault();

    var formDataRentBook = new FormData($('#formRentBook')[0]);
    $.ajax({
        url: 'http://localhost:8000/api/validate-rent-single-book',
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formDataRentBook,
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response)
            if (response.errorValidate) {
                for (var key in response.errorValidate) {
                    $('#'+key+"Error").text(response.errorValidate[key][0]);
                    $('#'+key+'Error').css('color', 'red');
                }
                return
            }
            else if (response.success) {
                $('#formRentBook').submit();
            }
        }
    });
})
