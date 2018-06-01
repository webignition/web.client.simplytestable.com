$(document).ready(function() {
    var fieldNameMessageMap = {
        'exp_year': 'Select your card expiry month and year',
        'exp_month': 'Select your card expiry month and year'
    };

    var errorCodeMessageMap = {
        'invalid_expiry_month':'Your card\'s expiry date is in the past',
        'invalid_expiry_year':'Your card\'s expiry date is in the past'
    };

    var getMessage = function (fieldName, code, message) {
        if (errorCodeMessageMap.hasOwnProperty(code)) {
            return errorCodeMessageMap[code];
        }

        if (fieldNameMessageMap.hasOwnProperty(fieldName)) {
            return fieldNameMessageMap[fieldName];
        }

        return message;
    };

    var displayFieldError = function (fieldName, code, message) {
        var field = $('[data-stripe=' + fieldName + ']');
        if (field.attr('data-stripe') === undefined) {
            return false;
        }

        var error = $('<div class="alert alert-danger" data-for="' + field.attr('id') + '"><button type="button" class="close" data-dismiss="alert">×</button><p>'+getMessage(field.attr('data-stripe'), code, message)+'</p></div>');
        var errorContainer = $('.error_container', field.closest('.form-group'));

        var errorSpacer = error.clone();
        errorSpacer.css({
            'display': 'none',
            'opacity': 0
        });

        errorSpacer.hide();

        errorContainer.prepend(errorSpacer);
        errorSpacer.slideDown(function () {
            errorSpacer.remove();
            error.css({
                'opacity': 0
            });
            errorContainer.prepend(error);
            error.animate({
                'opacity': 1
            });
        });

        return true;
    };

    var validate = function () {
        var requiredFieldNames = [
            'name',
            'address_line1',
            'address_city',
            'address_state',
            'address_zip',
            'number',
            'exp_month',
            'exp_year',
            'cvc'
        ];

        for (var requiredFieldIndex = 0; requiredFieldIndex < requiredFieldNames.length; requiredFieldIndex++) {
            var field = $('[data-stripe=' + requiredFieldNames[requiredFieldIndex] + ']');
            var value = $.trim(field.val());

            if (value === '') {
                return {
                    'error' : {
                        'param':requiredFieldNames[requiredFieldIndex],
                        'message':'Hold on, you can\'t leave this empty',
                        'code':null
                    }
                };
            }
        }

        return {};
    };

    var handleResponseError = function (error) {
        var field = $('[data-stripe=' + error.param + ']');
        field.stFormHelper().select();

        displayFieldError(error.param, error.code, error.message);
        $('.submit button').prop('disabled', false);
        $('.submit button .fa').remove();
        return false;
    };

    $('#payment-form').submit(function () {
        var form = $(this);
        var button = $('button', form);

        $('.alert', form).remove();

        var validationResponse = validate();
        if (validationResponse.hasOwnProperty('error')) {
            return handleResponseError(validationResponse.error);
        }

        button.prop('disabled', true).append('<i class="fa fa-spinner fa-spin"></i>');

        Stripe.createToken(form, function (status, response) {
            if (response.hasOwnProperty('error')) {
                return handleResponseError(response.error);
            }

            jQuery.ajax({
                type:'POST',
                error: function(request, textStatus, errorThrown) {
                },
                success: function(data, textStatus, request) {
                    if (data.hasOwnProperty('this_url')) {
                        $('.fa', button).remove();
                        button.append('<i class="fa fa-check"></i>');

                        window.setTimeout(function () {
                            window.location = data.this_url;
                        }, 500);

                        return false;
                    }

                    if (data.hasOwnProperty('user_account_card_exception_param') && data.user_account_card_exception_param !== '') {
                        handleResponseError({
                            'param': data.user_account_card_exception_param,
                            'code': data.user_account_card_exception_code,
                            'message': data.user_account_card_exception_message
                        });
                    } else {
                        handleResponseError({
                            'param': 'number',
                            'code': data.user_account_card_exception_code,
                            'message': data.user_account_card_exception_message
                        });
                    }

                    return false;
                },
                url: window.location.pathname + response.id + '/associate/',
                headers: {
                    'Accept':'application/json'
                }
            });
        });

        return false;
    });
});