let CookieOptionsModal = require('./element/cookie-options-modal');

class CookieOptions {
    constructor (document, cookieOptionsModal, actionBadge, statusElement) {
        this.document = document;
        this.cookieOptionsModal = cookieOptionsModal;
        this.actionBadge = actionBadge;
        this.statusElement = statusElement;
    };

    /**
     * @returns {string}
     */
    static getModalOpenedEventName () {
        return 'cookie-options.modal.opened';
    };

    /**
     * @returns {string}
     */
    static getModalClosedEventName () {
        return 'cookie-options.modal.closed';
    };

    init () {
        let modalCloseEventListener = () => {
            if (this.cookieOptionsModal.isEmpty()) {
                this.statusElement.innerText = 'not enabled';
                this.actionBadge.markNotEnabled();
            } else {
                this.statusElement.innerText = 'enabled';
                this.actionBadge.markEnabled();
            }
        };

        this.cookieOptionsModal.init();
        this.cookieOptionsModal.element.addEventListener(
            CookieOptionsModal.getClosedEventName(),
            modalCloseEventListener
        );

        this.cookieOptionsModal.element.addEventListener(CookieOptionsModal.getOpenedEventName(), () => {
            this.document.dispatchEvent(new CustomEvent(CookieOptions.getModalOpenedEventName()));
        });

        this.cookieOptionsModal.element.addEventListener(CookieOptionsModal.getClosedEventName(), () => {
            this.document.dispatchEvent(new CustomEvent(CookieOptions.getModalClosedEventName()));
        });
    };
}

module.exports = CookieOptions;
