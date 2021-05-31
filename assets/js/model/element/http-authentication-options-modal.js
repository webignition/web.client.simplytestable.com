let formFieldFocuser = require('../../form-field-focuser');

class HttpAuthenticationOptionsModal {
    constructor (element) {
        this.isAccountRequiredModal = element.classList.contains('account-required-modal');
        this.element = element;
        this.usernameInput = element.querySelector('[name=http-auth-username]');
        this.passwordInput = element.querySelector('[name=http-auth-password]');
        this.applyButton = element.querySelector('[data-name=apply]');
        this.closeButton = element.querySelector('[data-name=close]');
        this.clearButton = element.querySelector('[data-name=clear]');
        this.previousUsername = null;
        this.previousPassword = null;
    }

    /**
     * @returns {string}
     */
    static getOpenedEventName () {
        return 'cookie-options-modal.opened';
    }

    /**
     * @returns {string}
     */
    static getClosedEventName () {
        return 'cookie-options-modal.closed';
    }

    init () {
        this._addEventListeners();
    };

    isEmpty () {
        return this.usernameInput.value.trim() === '' && this.passwordInput.value.trim() === '';
    };

    _addEventListeners () {
        let shownEventListener = () => {
            this.previousUsername = this.usernameInput.value.trim();
            this.previousPassword = this.passwordInput.value.trim();

            let username = this.usernameInput.value.trim();
            let password = this.passwordInput.value.trim();

            let focusedInput = (username === '' || (username !== '' && password !== ''))
                ? this.usernameInput
                : this.passwordInput;

            formFieldFocuser(focusedInput);

            this.element.dispatchEvent(new CustomEvent(HttpAuthenticationOptionsModal.getOpenedEventName()));
        };

        let hiddenEventListener = () => {
            this.element.dispatchEvent(new CustomEvent(HttpAuthenticationOptionsModal.getClosedEventName()));
        };

        let closeButtonClickEventListener = () => {
            this.usernameInput.value = this.previousUsername;
            this.passwordInput.value = this.previousPassword;
        };

        let clearButtonClickEventListener = () => {
            this.usernameInput.value = '';
            this.passwordInput.value = '';
        };

        this.element.addEventListener('shown.bs.modal', shownEventListener);
        this.element.addEventListener('hidden.bs.modal', hiddenEventListener);
        this.closeButton.addEventListener('click', closeButtonClickEventListener);
        this.clearButton.addEventListener('click', clearButtonClickEventListener);
        this.usernameInput.addEventListener('keydown', this._inputKeyDownEventListener.bind(this));
        this.passwordInput.addEventListener('keydown', this._inputKeyDownEventListener.bind(this));
    };

    /**
     * @param {KeyboardEvent} event
     * @private
     */
    _inputKeyDownEventListener (event) {
        if (event.type === 'keydown' && event.key === 'Enter') {
            this.applyButton.click();
        }
    };
}

module.exports = HttpAuthenticationOptionsModal;
