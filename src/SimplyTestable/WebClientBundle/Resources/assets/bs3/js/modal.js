$(document).ready(function() {
    $('.modal-control, .modal-button').stModal().init();

    $('body').keyup(function (event) {
        if (event.which === 27) {
            $('.modal.in .close').click();
        }
    });
});