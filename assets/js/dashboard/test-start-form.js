let FormButton = require('../model/element/form-button');
let HttpAuthenticationOptionsModal = require('../http-authentication-options-modal');

class TestStartForm {
    constructor (element) {
        this.document = element.ownerDocument;
        this.element = element;
        this.submitButtons = [];
        this.httpAuthenticationOptionsModal = new HttpAuthenticationOptionsModal(
            this.document.querySelector('#http-authentication-options-modal')
        );

        [].forEach.call(this.element.querySelectorAll('[type=submit]'), (submitButton) => {
            this.submitButtons.push(new FormButton(submitButton));
        });
    };

    init () {
        this.element.addEventListener('submit', this._submitEventListener.bind(this));

        this.submitButtons.forEach((submitButton) => {
            submitButton.element.addEventListener('click', this._submitButtonEventListener);
        });

        this.httpAuthenticationOptionsModal.init();
    };

    _submitEventListener (event) {
        this.submitButtons.forEach((submitButton) => {
            submitButton.deEmphasize();
        });

        this._replaceUncheckedCheckboxesWithHiddenFields();
    };

    disable () {
        this.submitButtons.forEach((submitButton) => {
            submitButton.disable();
        });
    };

    enable () {
        this.submitButtons.forEach((submitButton) => {
            submitButton.enable();
        });
    };

    _submitButtonEventListener (event) {
        let buttonElement = event.target;
        let button = new FormButton(buttonElement);

        button.markAsBusy();
    };

    _replaceUncheckedCheckboxesWithHiddenFields () {
        [].forEach.call(this.element.querySelectorAll('input[type=checkbox]'), (input) => {
            if (!input.checked) {
                let hiddenInput = this.document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', input.getAttribute('name'));
                hiddenInput.value = '0';

                this.element.append(hiddenInput);
            }
        });
    };
}

module.exports = TestStartForm;
