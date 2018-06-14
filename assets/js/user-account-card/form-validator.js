class FormValidator {
    /**
     * @param {Stripe} stripeJs
     */
    constructor (stripeJs) {
        this.stripeJs = stripeJs;
        this.invalidField = null;
        this.errorMessage = '';
    };

    /**
     * @param {Object} data
     * @returns {boolean}
     */
    validate (data) {
        this.invalidField = null;

        Object.entries(data).forEach(([key, value]) => {
            if (!this.invalidField) {
                let comparatorValue = value.trim();

                if (comparatorValue === '') {
                    this.invalidField = key;
                    this.errorMessage = 'empty';
                }
            }
        });

        if (this.invalidField) {
            return false;
        }

        if (!this.stripeJs.card.validateCardNumber(data.number)) {
            this.invalidField = 'number';
            this.errorMessage = 'invalid';

            return false;
        }

        if (!this.stripeJs.card.validateExpiry(data.exp_month, data.exp_year)) {
            this.invalidField = 'exp_month';
            this.errorMessage = 'invalid';

            return false;
        }

        if (!this.stripeJs.card.validateCVC(data.cvc)) {
            this.invalidField = 'cvc';
            this.errorMessage = 'invalid';

            return false;
        }

        return true;
    };
}

module.exports = FormValidator;
