$(document).ready(function () {
    var calendarEl = document.getElementById('calendar');
    calendarEl.style.height = '800px'; // Thêm dòng này để đặt chiều cao thành 800px
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        eventClick: function (info) {
            var requestId = info.event.extendedProps.requestId
            showModalDetailRequest(info.event);
            getDetailRequest(requestId)
        }
    });
    calendar.render();

    $.ajax({
        url: 'http://localhost:8000/api/request-borrowing-book/'+document.getElementById('userId').value,
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response)
            for (var i=0;i<response.length;i++) {
                var eventCalendar = {
                    title: 'Bạn có lịch trả sách',
                    start: response[i].expiration_date,
                    end: response[i].expiration_date,
                    extendedProps: {
                        requestId: response[i].id
                    }
                }
                calendar.addEvent(eventCalendar);
            }
        }
    });
})

function showModalDetailRequest(event) {
    $('.viewDetailRequestModal').modal('show');
}

function getDetailRequest(requestId) {
    $('#name').empty()
    $('#address').empty()
    $('#mail').empty()
    $('#dateRent').empty()
    $('#dateExpire').empty()
    $('#detailRequest').empty()
    $('#totalPrice').empty()
    $.ajax({
        url: 'http://localhost:8000/api/admin/detail-request/'+requestId,
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response);
            strHtml = '';
            $('#name').text(response[0].user.name)
            $('#address').text(response[0].user.address)
            $('#mail').text(response[0].user.mail)
            $('#dateRent').text(response[0].rent_date)
            $('#dateExpire').text(response[0].expiration_date)
            for (var i=0;i<response[0].detail_history_rent_book.length;i++) {
                strHtml += `<tr class="odd">
                                <td style="width: 150px; height: 150px">
                                    <img style="max-width: 100%; max-height: 100%; padding-left: 25px" src="http://localhost:8000/storage/${response[0].detail_history_rent_book[i].book.thumbnail}">
                                </td>
                                <td style="text-align: center; vertical-align: middle">${response[0].detail_history_rent_book[i].book.name}</td>
                                <td style="text-align: center; vertical-align: middle">${Number(response[0].detail_history_rent_book[i].book.price_rent).toLocaleString('vi-VN')} / 1 Tháng</td>
                                <td style="text-align: center; vertical-align: middle; width: 150px">${response[0].detail_history_rent_book[i].quantity}</td>
                            </tr>`
            }
            $('#detailRequest').html(strHtml);
            $('#totalPrice').text(Number(response[0].total_price).toLocaleString('vi-VN'))
        }
    });
}
