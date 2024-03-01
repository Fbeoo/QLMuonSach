document.getElementById('post').addEventListener('click',function () {
    event.preventDefault();

    var formData = new FormData($('#formCommentBook')[0]);

    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");

    $.ajax({
        url: 'http://localhost:8000/api/comment/book',
        method: 'POST',
        dataType: 'json',
        contentType: false,
        processData: false,
        data: formData,
        success : function (response) {
            if (response.error) {
                alert(response.error);
            }
            else if (response.success) {
                getCommentBook(document.getElementById('bookId').value)
                alert(response.success);
            }
            loaderContainer.classList.add("hidden");
        }
    });
})

function getCommentBook(bookId) {
    $.ajax({
        url : 'http://localhost:8000/api/comment/book/'+bookId,
        method : 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        success : function (response) {
            console.log(response);
            var strHtml = '';
            for (var i=0;i<response.length;i++) {
                strHtml += `<li class="box_result row">
                            <div class="avatar_comment col-md-1">
                                <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>
                            </div>
                            <div class="result_comment col-md-11">
                                <h4>${response[i].user.name}</h4>
                                <p>${response[i].content}</p>
                            </div>
                        </li>`
            }
            $('#list_comment').html(strHtml);
        }
    })
}
