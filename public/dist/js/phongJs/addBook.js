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
            $('#categoryChildren').html(strHtml);
        }
    });
}

function addAuthor() {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/add/author',
        method: 'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            "authorName": document.getElementById('authorName').value,
        }),
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response)
            if (response.error) {
                alert(response.error)
                return
            }
            if (response.errorValidate) {
                for (var key in response.errorValidate) {
                    strHtml = `<p style="color: red">${response.errorValidate[key][0]}</p>`
                    $('#'+key+"Error").html(strHtml);
                }
                return
            }
            getAuthor();
            alert('Thêm tác giả thành công');
            loaderContainer.classList.add("hidden");
        }
    });
}

function getAuthor() {
    let data;
    $.ajax({
        url: 'http://localhost:8000/api/author',
        method: 'GET',
        contentType: 'json',
        error: function (response) {
            console.log('Không thể gọi api');
        },
        success: function (response) {
            var strHtml = '<option value="" selected>Chọn tác giả bất kì</option>';
            for (var i=0;i<response.length;i++) {
                strHtml += `<option value="${response[i].id}" id="${response[i].id}">${response[i].author_name}</option>`
            }
            console.log(strHtml);
            $('#author').html(strHtml);
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

var imageAuthorInput = document.getElementById('imageAuthorInput');
var previewAuthorImage = document.getElementById('previewAuthorImage');
imageAuthorInput.addEventListener('change', function(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewAuthorImage.setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
});

var selectCategoryParent = document.getElementById('inputCategoryParent');
selectCategoryParent.addEventListener('change',function () {
    getCategoryChildren(selectCategoryParent.selectedOptions[0].id);
});
document.getElementById('addAuthor').addEventListener('click',function () {
    event.preventDefault();
    var formDataAddAuthor = new FormData($('#formAddAuthor')[0]);
    $.ajax({
        url: 'http://localhost:8000/api/admin/add/author',
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formDataAddAuthor,
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            if (response.error) {
                alert(response.error)
                return
            }
            if (response.errorValidate) {
                for (var key in response.errorValidate) {
                    strHtml = `<p style="color: red">${response.errorValidate[key][0]}</p>`
                    $('#'+key+"Error").html(strHtml);
                }
                return
            }
            getAuthor();
            alert('Thêm tác giả thành công');
            loaderContainer.classList.add("hidden");
        }
    });
})
$('#addBook').click(function(event) {
    event.preventDefault();

    var formData = new FormData($('#formAddBook')[0]);
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
    $.ajax({
        url: 'http://localhost:8000/api/admin/add/book',
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
                $('#authorNameError').html('');
                for (var key in response.errorValidate) {
                    strHtml = `<p style="color: red">${response.errorValidate[key][0]}</p>`
                    $('#'+key+"Error").html(strHtml);
                }
                return
            }
            alert('Thêm sách thành công');
            loaderContainer.classList.add("hidden");
            window.location.href = "http://localhost:8000/admin/manage/book";
        }
    });
});
