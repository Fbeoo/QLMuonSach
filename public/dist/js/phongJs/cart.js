$(function() {
    var currentDate = moment().format('MM/DD/YYYY');
    $('input[name="dateRent"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 2024,
        maxYear: parseInt(moment().format('YYYY'), 10),
        startDate: currentDate
    });
});
