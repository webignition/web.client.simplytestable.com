let CookieOptionsModal = require('../model/element/cookie-options-modal');
let CookieOptions = require('../model/cookie-options');
let ActionBadge = require('../model/element/action-badge');

class CookieOptionsFactory {
    static create (container) {
        return new CookieOptions(
            container.ownerDocument,
            new CookieOptionsModal(container.querySelector('.modal')),
            new ActionBadge(container.querySelector('.modal-launcher')),
            container.querySelector('.status')
        );
    };
}

module.exports = CookieOptionsFactory;
