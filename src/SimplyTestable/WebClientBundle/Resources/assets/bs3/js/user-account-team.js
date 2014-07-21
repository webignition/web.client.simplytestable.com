$(document).ready(function() {

    if ($('body.no-team-no-invite').length === 1) {
        var input = $('#name');
        if (input.stFormHelper().isEmpty() || input.stFormErrorHelper().hasError()) {
            input.stFormHelper().select();
        }
    }

    if ($('body.in-team.team-leader').length === 1) {

        var input = $('#email');
        if (input.stFormHelper().isEmpty() || input.stFormErrorHelper().hasError()) {
            input.stFormHelper().select();
        }
    }

});