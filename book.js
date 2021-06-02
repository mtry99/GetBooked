
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
    });

    $("#pages-range-slider").slider({
        range: true,
        min: 0,
        max: 1500,
        values: [500, 1200],
        slide: function (event, ui) {
            $("#pages-amount1").val("" + ui.values[0]);
            $("#pages-amount2").val("" + ui.values[1]);
        }
    });
    $("#pages-amount1").val($("#pages-range-slider").slider("values", 0));
    $("#pages-amount2").val($("#pages-range-slider").slider("values", 1));

    $("#year-range-slider").slider({
        range: true,
        min: 1500,
        max: 2021,
        values: [1900, 2021],
        slide: function (event, ui) {
            $("#year-amount1").val("" + ui.values[0]);
            $("#year-amount2").val("" + ui.values[1]);
        }
    });
    $("#year-amount1").val($("#year-range-slider").slider("values", 0));
    $("#year-amount2").val($("#year-range-slider").slider("values", 1));
});

function apply_filter() {

    console.log("gere");

    return false;
}
