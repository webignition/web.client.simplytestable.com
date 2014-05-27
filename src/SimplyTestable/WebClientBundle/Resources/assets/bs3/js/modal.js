$(document).ready(function() {
    $('.modal-control').stModal().init();

    $('body').keyup(function (event) {
        if (event.which === 27) {
            $('.modal.in .close').click();
        }
    });
});