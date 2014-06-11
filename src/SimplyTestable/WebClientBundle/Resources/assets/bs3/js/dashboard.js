$(document).ready(function() {
    $('form').submit(function (event) {
        event.preventDefault();
    });

    $('.buttons button').click(function () {
        var button = $(this);

        $('.fa', button).removeClass('fa-globe').removeClass('fa-circle-o').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });

        $('.buttons button').each(function () {
            if (button.val() !== $(this).val()) {
                $(this).animate({
                    'opacity':0.6
                }).attr('disabled', 'disabled');
            }
        });
    });

    $('#website').stFormHelper().select();
});