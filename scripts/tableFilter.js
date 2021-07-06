$(document).ready(function () {
    $(".search-input").on("keyup", function () {

        let value = $(this).val().toLowerCase();
        let id = $(this).attr('data-filterName');

        $(id + "tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
