class StripeHandler {
    /**
     * @param {Stripe} stripeJs
     */
    constructor (stripeJs) {
        this.stripeJs = stripeJs;
    }

    /**
     * @param {string} stripePublishableKey
     */
    setStripePublishableKey (stripePublishableKey) {
        this.stripeJs.setPublishableKey(stripePublishableKey);
    }

    getCreateCardTokenSuccessEventName () {
        return 'stripe-hander.create-card-token.success';
    };

    getCreateCardTokenFailureEventName () {
        return 'stripe-hander.create-card-token.failure';
    };

    createCardToken (data, formElement) {
        this.stripeJs.card.createToken(data, (status, response) => {
            let isErrorResponse = response.hasOwnProperty('error');

            let eventName = isErrorResponse
                ? this.getCreateCardTokenFailureEventName()
                : this.getCreateCardTokenSuccessEventName();

            formElement.dispatchEvent(new CustomEvent(eventName, {
                detail: response
            }));
        });
    };
}

module.exports = StripeHandler;
