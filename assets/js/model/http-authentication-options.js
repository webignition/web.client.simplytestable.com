let HttpAuthenticationOptionsModal = require('./element/http-authentication-options-modal');

class HttpAuthenticationOptions {
    constructor (httpAuthenticationOptionsModal, actionBadge, statusElement) {
        this.httpAuthenticationOptionsModal = httpAuthenticationOptionsModal;
        this.actionBadge = actionBadge;
        this.statusElement = statusElement;
    };

    init () {
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
        this.httpAuthenticationOptionsModal.element.addEventListener(
            HttpAuthenticationOptionsModal.getClosedEventName(),
            modalCloseEventListener
        );
    };
}

module.exports = HttpAuthenticationOptions;
