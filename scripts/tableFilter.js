$(document).ready(function () {
    $(".search-input").on("keyup", function () {

        let value = $(this).val().toLowerCase();
        let id = $(this).attr('data-filtername');

        console.log($(id).children());

        $(id).children().filter(() => {
            console.log('a');
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
