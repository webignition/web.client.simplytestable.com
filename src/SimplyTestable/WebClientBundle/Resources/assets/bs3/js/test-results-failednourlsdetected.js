$(document).ready(function() {
    $('.collapse-control').each(function () {
        var control = $(this);
        var detail = $(control.attr('data-target'));

        control.append('<i class="fa fa-caret-down"></i>');

        detail.on('shown.bs.collapse', function () {
            $('.fa', control).remove();
            control.append('<i class="fa fa-caret-up"></i>');
        });

        detail.on('hidden.bs.collapse', function () {
            $('.fa', control).remove();
            control.append('<i class="fa fa-caret-down"></i>');
        });
    });
});