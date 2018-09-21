class HttpAuthenticationOptionsModal {
    constructor (element) {
        this.document = element.ownerDocument;
        this.element = element;
    };

    init () {
        this.element.addEventListener('show.bs.modal', () => {
            [].forEach.call(this.element.querySelectorAll('.js-disable-readonly'), function (inputElement) {
                inputElement.removeAttribute('readonly');
            });
        });
    };
}

module.exports = HttpAuthenticationOptionsModal;
