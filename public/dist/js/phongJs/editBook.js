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
                strHtml += `<option value="${response[i].id}" id="${response[i].id}">${response[i].category_name}</option>`
            }
            console.log(strHtml);
            $('#categoryChildren').html(strHtml);
        }
    });
}


var imageInput = document.getElementById('imageInput');
var previewImage = document.getElementById('previewImage');
imageInput.addEventListener('change', function(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImage.setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
});

var selectCategoryParent = document.getElementById('inputCategoryParent');
selectCategoryParent.addEventListener('change',function () {
    getCategoryChildren(selectCategoryParent.selectedOptions[0].id);
})
$('#editBook').click(function(event) {
    event.preventDefault();

    var formData = new FormData($('#formEditBook')[0]);
    $.ajax({
        url: 'http://localhost:8000/api/admin/edit/book',
        method: 'POST',
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(this.data)
            if (response.error) {
                alert(response.error)
                return
            }
            if (response.errorValidate) {
                $('#nameError').html('');
                $('#yearPublishError').html('');
                $('#priceRentError').html('');
                $('#quantityError').html('');
                $('#totalPageError').html('');
                $('#thumbnailError').html('');
                $('#weightError').html('');
                $('#categoryIdError').html('');
                $('#descriptionError').html('');
                for (var key in response.errorValidate) {
                    strHtml = `<p style="color: red">${response.errorValidate[key][0]}</p>`
                    $('#'+key+"Error").html(strHtml);
                }
                return
            }
            alert('Sửa sách thành công');
            window.location.href = "http://localhost:8000/admin/manage/book";
        }
    });

})
