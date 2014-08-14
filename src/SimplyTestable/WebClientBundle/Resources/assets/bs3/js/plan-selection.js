$(document).ready(function() {
    $('.plan input').change(function () {
        $('.plan.checked').removeClass('checked');

        $(this).closest('.plan').addClass('checked');
    });

    $('button[type=submit]').click(function () {
        var button = $(this);

        $('.fa', button).removeClass('fa-caret-right').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });
    });
});