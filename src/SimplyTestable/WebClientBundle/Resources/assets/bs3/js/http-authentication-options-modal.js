$(document).ready(function() {
    var actionBadge = $('#http-authentication-action-badge');

    var modal = $('#http-authentication-options-modal');
    var usernameInput = $('#http-auth-username');
    var passwordInput = $('#http-auth-password');

    var previousUsername = '';
    var previousPassword = '';

    var closedBy = $('input[name=closed-by]', modal);

    $('input[type=text], input[type=password]', modal).keyup(function (event) {
        if (event.which === 13) {
            $('[data-name=apply]', modal).click();
        }
    });

    $('[data-name]', modal).click(function () {
        closedBy.val($(this).attr('data-name'));
    });

    modal.on('show.bs.modal', function () {
        previousUsername = usernameInput.val();
        previousPassword = passwordInput.val();
    });

    modal.on('shown.bs.modal', function () {
        if (passwordInput.stFormHelper().isEmpty()) {
            passwordInput.stFormHelper().select();
        }

        if (usernameInput.stFormHelper().isEmpty()) {
            usernameInput.stFormHelper().select();
        }

        if (!usernameInput.stFormHelper().isEmpty() && !passwordInput.stFormHelper().isEmpty()) {
            usernameInput.stFormHelper().select();
        }
    });

    modal.on('hide.bs.modal', function () {
        if (closedBy.val() == 'close') {
            usernameInput.val(previousUsername);
            passwordInput.val(previousPassword);
        }

        if (closedBy.val() == 'clear') {
            usernameInput.val('');
            passwordInput.val('');
        }
    });

    modal.on('hidden.bs.modal', function () {
        if (usernameInput.stFormHelper().isEmpty() && passwordInput.stFormHelper().isEmpty()) {
            $('.status', actionBadge).text('not enabled');
            actionBadge.removeClass('action-badge-enabled');
        } else {
            $('.status', actionBadge).text('enabled');
            actionBadge.addClass('action-badge-enabled');
        }
    });

});