$(document).ready(function() {
    $('.collapse-control').each(function () {
        var control = $(this);
        var detail = $(control.attr('data-target'));

        detail.on('shown.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.fa', control).remove();
            control.append(' <i class="fa fa-caret-up"></i>');
        });

        detail.on('hidden.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.fa', control).remove();
            control.append(' <i class="fa fa-caret-down"></i>');
        });
    });

    $('.buttons button[type=submit]').click(function () {
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