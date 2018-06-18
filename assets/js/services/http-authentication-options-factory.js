let HttpAuthenticationOptionsModal = require('../model/element/http-authentication-options-modal');
let HttpAuthenticationOptions = require('../model/http-authentication-options');
let ActionBadge = require('../model/element/action-badge');

class HttpAuthenticationOptionsFactory {
    static create (container) {
        return new HttpAuthenticationOptions(
            new HttpAuthenticationOptionsModal(container.querySelector('.modal')),
            new ActionBadge(container.querySelector('.modal-launcher')),
            container.querySelector('.status')
        );
    };
}

module.exports = HttpAuthenticationOptionsFactory;
