let formFieldFocuser = require('../../form-field-focuser');

class HttpAuthenticationOptionsModal {
    constructor (element) {
        this.element = element;
        this.usernameInput = element.querySelector('[name=http-auth-username]');
        this.passwordInput = element.querySelector('[name=http-auth-password]');
        this.closeButton = element.querySelector('[data-name=close]');
        this.clearButton = element.querySelector('[data-name=clear]');
        // this.applyButton = element.querySelector('[data-name=apply]');
        this.previousUsername = null;
        this.previousPassword = null;
    }

    static getClosedEventName () {
        return 'http-authentication-options-modal.close';
    };

    isEmpty () {
        return this.usernameInput.value.trim() === '' && this.passwordInput.value.trim() === '';
    };

    init () {
        let showEventListener = () => {
            this.previousUsername = this.usernameInput.value.trim();
            this.previousPassword = this.passwordInput.value.trim();
        };

        let shownEventListener = () => {
            let username = this.usernameInput.value.trim();
            let password = this.passwordInput.value.trim();

            let focusedInput = (username === '' || (username !== '' && password !== ''))
                ? this.usernameInput
                : this.passwordInput;


            formFieldFocuser(focusedInput);
        };

        // let hideEventListener = () => {
        //     console.log('hide');
        // };

        let hiddenEventListener = () => {
            this.element.dispatchEvent(new CustomEvent(HttpAuthenticationOptionsModal.getClosedEventName()));

            console.log('hidden');


        };

        let closeButtonClickEventListener = () => {
            this.usernameInput.value = this.previousUsername;
            this.passwordInput.value = this.previousPassword;
        };

        let clearButtonClickEventListener = () => {
            this.usernameInput.value = '';
            this.passwordInput.value = '';
        };

        this.element.addEventListener('show.bs.modal', showEventListener);
        this.element.addEventListener('shown.bs.modal', shownEventListener);
        // this.element.addEventListener('hide.bs.modal', hideEventListener);
        this.element.addEventListener('hidden.bs.modal', hiddenEventListener);
        this.closeButton.addEventListener('click', closeButtonClickEventListener);
        this.clearButton.addEventListener('click', clearButtonClickEventListener);
    };
}

module.exports = HttpAuthenticationOptionsModal;
