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
        url: 'http://localhost:8000/api/rent-single-book',
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
                }
                return
            }
            else if (response.error) {
                alert(response.error);
                return;
            }
            else {
                alert('Thuê sách thành công')
            }
        }
    });
})
