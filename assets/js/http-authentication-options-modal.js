class HttpAuthenticationOptionsModal {
    constructor (element) {
        this.enabled = element !== null;

        if (this.enabled) {
            this.document = element.ownerDocument;
            this.element = element;
        }
    };

    init () {
        if (this.enabled) {
            this.element.addEventListener('show.bs.modal', () => {
                [].forEach.call(this.element.querySelectorAll('.js-disable-readonly'), function (inputElement) {
                    inputElement.removeAttribute('readonly');
                });
            });
        }
    };
}

module.exports = HttpAuthenticationOptionsModal;
