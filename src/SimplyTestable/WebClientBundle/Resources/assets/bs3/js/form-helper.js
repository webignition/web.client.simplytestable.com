$(document).ready(function() {
    $('body').on('click', '.alert[data-for] .close', function (event) {
        $('#' + $(event.target).parent().attr('data-for')).stFormHelper().select();
    });
});