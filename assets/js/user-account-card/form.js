let formFieldFocuser = require('../form-field-focuser');
let AlertFactory = require('../alert-factory');
let FormButton = require('../form-button');

class Form {
    constructor (element, validator) {
        this.document = element.ownerDocument;
        this.element = element;
        this.validator = validator;
        this.submitButton = new FormButton(element.querySelector('button[type=submit]'));
    };

    init () {
        this.element.addEventListener('submit', this._submitEventListener.bind(this));
    };

    getStripePublishableKey () {
        return this.element.getAttribute('data-stripe-publishable-key');
    };

    getUpdateCardEventName() {
        return 'user.account.card.update';
    }

    disable () {
        this.submitButton.disable();
    };

    enable () {
        this.submitButton.stopSpinning();
        this.submitButton.enable();
    };

    _getData () {
        const data = {};

        [].forEach.call(this.element.querySelectorAll('[data-stripe]'), function (dataElement) {
            let fieldKey = dataElement.getAttribute('data-stripe');

            data[fieldKey] = dataElement.value;
        });

        return data;
    }

    _submitEventListener (event) {
        event.preventDefault();
        event.stopPropagation();

        this._removeErrorAlerts();
        this.disable();

        let data = this._getData();
        let isValid = this.validator.validate(data);

        if (!isValid) {
            this.handleResponseError(this.createResponseError(this.validator.invalidField, this.validator.errorMessage));
            this.enable();
        } else {
            let event = new CustomEvent(this.getUpdateCardEventName(), {
                detail: data
            });

            this.element.dispatchEvent(event);
        }
    };

    _removeErrorAlerts () {
        let errorAlerts = this.element.querySelectorAll('.alert');

        [].forEach.call(errorAlerts, function (errorAlert) {
            errorAlert.parentNode.removeChild(errorAlert);
        });
    };

    _displayFieldError (field, error) {
        let alert = AlertFactory.createFromContent(this.document, '<p>' + error.message + '</p>', field.getAttribute('id'));
        let errorContainer = this.element.querySelector('[data-for=' + field.getAttribute('id') + ']');

        if (!errorContainer) {
            errorContainer = this.element.querySelector('[data-for*=' + field.getAttribute('id') + ']');
        }

        errorContainer.append(alert.element);
    };

    handleResponseError (error) {
        let field = this.element.querySelector('[data-stripe=' + error.param + ']');

        formFieldFocuser(field);
        this._displayFieldError(field, error);
    };

    createResponseError (field, state) {
        let errorMessage = '';

        if (state === 'empty') {
            errorMessage = 'Hold on, you can\'t leave this empty';
        }

        if (state === 'invalid') {
            if (field === 'number') {
                errorMessage = 'The card number is not quite right';
            }

            if (field === 'exp_month') {
                errorMessage = 'An expiry date in the future is better';
            }

            if (field === 'cvc') {
                errorMessage = 'The CVC should be 3 or 4 digits';
            }
        }

        return {
            param: field,
            message: errorMessage
        };
    }
}

module.exports = Form;
