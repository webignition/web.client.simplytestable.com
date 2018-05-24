$(document).ready(function() {
    $('button[type=submit]').click(function () {
        var button = $(this);

        $('.fa', button).removeClass('fa-caret-right').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });
    });
});