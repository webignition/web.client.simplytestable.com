$(document).ready(function() {

    if ($('body.no-team-no-invite').length === 1) {
        var input = $('#name');
        if (input.stFormHelper().isEmpty() || input.stFormErrorHelper().hasError()) {
            input.stFormHelper().select();
        }
    }

    if ($('body.in-team.team-leader').length === 1) {
        var input = $('#email');

        if (input.is('.focus')) {
            if (input.stFormHelper().isEmpty() || input.stFormErrorHelper().hasError()) {
                input.stFormHelper().select();
            }
        }

        if ($('.invite').length === 0) {
            if (input.stFormHelper().isEmpty() || input.stFormErrorHelper().hasError()) {
                input.stFormHelper().select();
            }
        }

        $('#sidenav').on('activate.bs.scrollspy', function (event) {
            if ($('a', event.target).attr('href') == '#invites') {
                if (input.stFormHelper().isEmpty() || input.stFormErrorHelper().hasError()) {
                    input.stFormHelper().select();
                }
            }
        })
    }

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