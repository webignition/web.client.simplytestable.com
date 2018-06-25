let HttpAuthenticationOptionsModal = require('./element/http-authentication-options-modal');

class HttpAuthenticationOptions {
    constructor (document, httpAuthenticationOptionsModal, actionBadge, statusElement) {
        this.document = document;
        this.httpAuthenticationOptionsModal = httpAuthenticationOptionsModal;
        this.actionBadge = actionBadge;
        this.statusElement = statusElement;
    };

    /**
     * @returns {string}
     */
    static getModalOpenedEventName () {
        return 'http-authentication-options.modal.opened';
    };

    /**
     * @returns {string}
     */
    static getModalClosedEventName () {
        return 'http-authentication-options.modal.closed';
    };

    init () {
        if (!this.httpAuthenticationOptionsModal.isAccountRequiredModal) {
            let modalCloseEventListener = () => {
                if (this.httpAuthenticationOptionsModal.isEmpty()) {
                    this.statusElement.innerText = 'not enabled';
                    this.actionBadge.markNotEnabled();
                } else {
                    this.statusElement.innerText = 'enabled';
                    this.actionBadge.markEnabled();
                }
            };

            this.httpAuthenticationOptionsModal.init();

            this.httpAuthenticationOptionsModal.element.addEventListener(HttpAuthenticationOptionsModal.getOpenedEventName(), () => {
                this.document.dispatchEvent(new CustomEvent(HttpAuthenticationOptions.getModalOpenedEventName()));
            });

            this.httpAuthenticationOptionsModal.element.addEventListener(HttpAuthenticationOptionsModal.getClosedEventName(), () => {
                modalCloseEventListener();
                this.document.dispatchEvent(new CustomEvent(HttpAuthenticationOptions.getModalClosedEventName()));
            });
        }
    };
}

module.exports = HttpAuthenticationOptions;
