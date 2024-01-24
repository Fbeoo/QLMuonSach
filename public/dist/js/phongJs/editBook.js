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
            var strHtml = '';
            for (var i=0;i<response.length;i++) {
                strHtml += `<option value="${response[i].id}" id="${response[i].id}">${response[i].category_name}</option>`
            }
            console.log(strHtml);
            $('#categoryChildren').html(strHtml);
        }
    });
}

function editBook() {
    $.ajax({
        url: 'http://localhost:8000/api/admin/edit/book',
        method: 'PUT',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "id" : document.getElementById('id').value,
            "name": document.getElementById('bookName').value,
            "yearPublish" : document.getElementById('yearPublish').value,
            "priceRent" : document.getElementById('priceRent').value,
            "weight" : document.getElementById('weight').value,
            "totalPage" : document.getElementById('totalPage').value,
            "thumbnail" : document.getElementById('bookImage').value,
            "categoryId" : document.getElementById('categoryChildren').value,
            "quantity" : document.getElementById('quantity').value,
            "description" : document.getElementById('bookDescription').value,
            "authorId" : document.getElementById('author').value
        }),
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
    document.getElementById('bookImage').value = file.name;
});

var selectCategoryParent = document.getElementById('inputCategoryParent');
selectCategoryParent.addEventListener('change',function () {
    getCategoryChildren(selectCategoryParent.selectedOptions[0].id);
})
document.getElementById('editBook').addEventListener('click',function () {
    editBook();
})
