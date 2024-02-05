document.getElementById('saveChange').addEventListener('click',function () {
    event.preventDefault();

    var formData = new FormData($('#formEditUserProfile')[0]);

    $.ajax({
        url: 'http://localhost:8000/api/edit-profile',
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
            $('#nameError').empty();
            $('#dobError').empty();
            $('#addressError').empty();
            if (response.errorValidate) {
                for (var key in response.errorValidate) {
                    strHtml = `<p style="color: red">${response.errorValidate[key][0]}</p>`
                    $('#'+key+"Error").html(strHtml);
                }
                return
            }
            else if (response.error) {
                alert(response.error)
                return;
            }
            else {
                $('#nameUser').text(this.data.get('name'))
                alert('Sửa thông tin thành công');
            }
        }
    });
})
