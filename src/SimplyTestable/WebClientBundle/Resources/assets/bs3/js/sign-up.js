$(document).ready(function() {
    var setSelectedField = function () {
        var email = $('#email');
        if (email.stFormHelper().isEmpty() || email.stFormErrorHelper().hasError()) {
            email.stFormHelper().select();
            return;
        }

        var password = $('#password');
        if (password.stFormHelper().isEmpty()) {
            password.stFormHelper().select();
        }
    };

    setSelectedField();

    $('.plan input').change(function () {
        $('.plan.checked').removeClass('checked');

        $(this).closest('.plan').addClass('checked');
    })
});