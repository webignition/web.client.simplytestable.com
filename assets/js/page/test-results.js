let unavailableTaskTypeModalLauncher = require('../unavailable-task-type-modal-launcher');
let HttpAuthenticationOptionsFactory = require('../services/http-authentication-options-factory');
let CookieOptionsFactory = require('../services/cookie-options-factory');
let TestLockUnlock = require('../model/element/test-lock-unlock');

class TestResults {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.httpAuthenticationOptions = HttpAuthenticationOptionsFactory.create(document.querySelector('.http-authentication-test-option'));
        this.cookieOptions = CookieOptionsFactory.create(document.querySelector('.cookies-test-option'));
        this.testLockUnlock = new TestLockUnlock(document.querySelector('.btn-lock-unlock'));
    }

    init () {
        unavailableTaskTypeModalLauncher(this.document.querySelectorAll('.task-type-option.not-available'));
        this.httpAuthenticationOptions.init();
        this.cookieOptions.init();
        this.testLockUnlock.init();
    };
}

module.exports = TestResults;
