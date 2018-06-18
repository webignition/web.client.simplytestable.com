let unavailableTaskTypeModalLauncher = require('../unavailable-task-type-modal-launcher');
let TestStartForm = require('../dashboard/test-start-form');
let RecentTestList = require('../dashboard/recent-test-list');
let HttpAuthenticationOptionsFactory = require('../services/http-authentication-options-factory');
let HttpAuthenticationOptions = require('../model/http-authentication-options');
let CookieOptionsFactory = require('../services/cookie-options-factory');
let CookieOptions = require('../model/cookie-options');

class Dashboard {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.testStartForm = new TestStartForm(document.getElementById('test-start-form'));
        this.recentTestList = new RecentTestList(document.querySelector('.test-list'));
        this.httpAuthenticationOptions = HttpAuthenticationOptionsFactory.create(document.querySelector('.http-authentication-test-option'));
        this.cookieOptions = CookieOptionsFactory.create(document.querySelector('.cookies-test-option'));
    }

    init () {
        this.document.querySelector('.recent-activity-container').classList.remove('hidden');

        unavailableTaskTypeModalLauncher(this.document.querySelectorAll('.task-type.not-available'));
        this.testStartForm.init();
        this.recentTestList.init();
        this.httpAuthenticationOptions.init();
        this.cookieOptions.init();

        this.document.addEventListener(HttpAuthenticationOptions.getModalOpenedEventName(), () => {
            this.testStartForm.disable();
        });

        this.document.addEventListener(HttpAuthenticationOptions.getModalClosedEventName(), () => {
            this.testStartForm.enable();
        });

        this.document.addEventListener(CookieOptions.getModalOpenedEventName(), () => {
            this.testStartForm.disable();
        });

        this.document.addEventListener(CookieOptions.getModalClosedEventName(), () => {
            this.testStartForm.enable();
        });
    };
}

module.exports = Dashboard;
