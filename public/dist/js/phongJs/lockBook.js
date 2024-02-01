$(document).ready(function() {
    $("#exampleModal").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget);
        var buttonActionStatus = document.getElementById('actionStatus');
        if (button.data("value") === "Lock") {
            $('#notification').text("Bạn có muốn khóa sách")
            buttonActionStatus.textContent = "Khóa";
            buttonActionStatus.onclick = function () {
                lockBook(button.data("id"));
            }
        }
        else if (button.data("value") === "Unlock") {
            $('#notification').text("Bạn có muốn mở khóa sách")
            buttonActionStatus.textContent = "Mở khóa";
            buttonActionStatus.onclick = function () {
                unlockBook(button.data("id"));
            }
        }
    });
});
function lockBook(bookId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
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
            loaderContainer.classList.add("hidden");
        }
    });
}

function unlockBook(bookId) {
    var loaderContainer = document.getElementById("loaderContainer");
    loaderContainer.classList.remove("hidden");
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
            loaderContainer.classList.add("hidden");
        }
    });
}
