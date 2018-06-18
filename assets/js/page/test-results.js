let unavailableTaskTypeModalLauncher = require('../unavailable-task-type-modal-launcher');
let HttpAuthenticationOptionsFactory = require('../services/http-authentication-options-factory');
let CookieOptionsFactory = require('../services/cookie-options-factory');

class TestResults {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.httpAuthenticationOptions = HttpAuthenticationOptionsFactory.create(document.querySelector('.http-authentication-test-option'));
        this.cookieOptions = CookieOptionsFactory.create(document.querySelector('.cookies-test-option'));
    }

    init () {
        unavailableTaskTypeModalLauncher(this.document.querySelectorAll('.task-type-option.not-available'));
        this.httpAuthenticationOptions.init();
        this.cookieOptions.init();
    };
}

module.exports = TestResults;
