$(document).ready(function () {
    var calendarEl = document.getElementById('calendar');
    calendarEl.style.height = '800px'; // Thêm dòng này để đặt chiều cao thành 800px
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
    });
    calendar.render();

    $.ajax({
        url: 'http://localhost:8000/api/admin/all/request-rent-book/status/borrowing',
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
                    title: 'Trả sách',
                    start: response[i].expiration_date,
                    end: response[i].expiration_date
                }
                calendar.addEvent(eventCalendar);
            }
        }
    });
})

function getRequestRentBookHaveStatusBorrowing() {
    var data;
    $.ajax({
        url: 'http://localhost:8000/api/admin/all/request-rent-book/status/borrowing',
        method: 'GET',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            alert('Lỗi: ' + xhr.responseText);
        },
        success: function (response) {
            console.log(response)
            var eventCalendar = [];
            for (var i=0;i<response.length;i++) {
                eventCalendar.push({
                    title: 'Trả sách',
                    start: response[i].expiration_date,
                    end: response[i].expiration_date
                })
            }
            data = eventCalendar;
        }
    });
    return data;
}
