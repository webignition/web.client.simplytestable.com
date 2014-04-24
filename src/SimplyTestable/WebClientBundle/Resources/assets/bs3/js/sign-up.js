$(document).ready(function() {
    var email = $('#email');
    if (email.stFormHelper().isEmpty() || email.stFormHelper().hasError()) {
        email.stFormHelper().select();
        return;
    }

    var password = $('#password');
    if (password.stFormHelper().isEmpty()) {
        password.stFormHelper().select();
    }
});