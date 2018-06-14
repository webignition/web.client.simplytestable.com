let Form = require('../user-account-card/form');
let FormValidator = require('../user-account-card/form-validator');
let StripeHandler = require('../stripe-handler');

class UserAccountCard {
    /**
     * @param {Document} document
     */
    constructor (document) {
        // eslint-disable-next-line no-undef
        let stripeJs = Stripe;
        let formValidator = new FormValidator(stripeJs);
        this.stripeHandler = new StripeHandler(stripeJs);

        this.form = new Form(document.getElementById('payment-form'), formValidator);
    };

    init () {
        this.form.init();
        this.stripeHandler.setStripePublishableKey(this.form.getStripePublishableKey());

        let updateCard = this.form.getUpdateCardEventName();
        let createCardTokenSuccess = this.stripeHandler.getCreateCardTokenSuccessEventName();
        let createCardTokenFailure = this.stripeHandler.getCreateCardTokenFailureEventName();

        this.form.element.addEventListener(updateCard, this._updateCardEventListener.bind(this));
        this.form.element.addEventListener(createCardTokenSuccess, this._createCardTokenSuccessEventListener.bind(this));
        this.form.element.addEventListener(createCardTokenFailure, this._createCardTokenFailureEventListener.bind(this));
    };

    _updateCardEventListener (updateCardEvent) {
        this.stripeHandler.createCardToken(updateCardEvent.detail, this.form.element);
    };

    _createCardTokenSuccessEventListener (stripeCreateCardTokenEvent) {
        let requestUrl = window.location.pathname + stripeCreateCardTokenEvent.detail.id + '/associate/';
        let request = new XMLHttpRequest();

        request.open('POST', requestUrl);
        request.responseType = 'json';
        request.setRequestHeader('Accept', 'application/json');

        request.addEventListener('load', (event) => {
            let data = request.response;

            if (data.hasOwnProperty('this_url')) {
                this.form.submitButton.stopSpinning();
                this.form.submitButton.markSucceeded();

                window.setTimeout(function () {
                    window.location = data.this_url;
                }, 500);
            } else {
                this.form.enable();

                if (data.hasOwnProperty('user_account_card_exception_param') && data['user_account_card_exception_param'] !== '') {
                    this.form.handleResponseError({
                        'param': data.user_account_card_exception_param,
                        'message': data.user_account_card_exception_message
                    });
                } else {
                    this.form.handleResponseError({
                        'param': 'number',
                        'message': data.user_account_card_exception_message
                    });
                }
            }
        });

        request.send();
    };

    _createCardTokenFailureEventListener (stripeCreateCardTokenEvent) {
        let responseError = this.form.createResponseError(stripeCreateCardTokenEvent.detail.error.param, 'invalid');

        this.form.enable();
        this.form.handleResponseError(responseError);
    };
}

module.exports = UserAccountCard;
