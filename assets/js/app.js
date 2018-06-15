require('bootstrap.native');
require('../css/app.scss');

require('classlist-polyfill');
require('./polyfill/custom-event');
require('./polyfill/object-entries');

let formButtonSpinner = require('./form-button-spinner');
let formFieldFocuser = require('./form-field-focuser');
let modalControl = require('./modal-control');
let collapseControlCaret = require('./collapse-control-caret');

let Dashboard = require('./page/dashboard');
let testHistoryPage = require('./page/test-history');
let testResultsPage = require('./page/test-results');
let UserAccount = require('./page/user-account');
let UserAccountCard = require('./page/user-account-card');
let AlertFactory = require('./services/alert-factory');

const onDomContentLoaded = function () {
    let body = document.getElementsByTagName('body').item(0);
    let focusedField = document.querySelector('[data-focused]');

    if (focusedField) {
        formFieldFocuser(focusedField);
    }

    [].forEach.call(document.querySelectorAll('.js-form-button-spinner'), function (formElement) {
        formButtonSpinner(formElement);
    });

    modalControl(document.querySelectorAll('.modal-control'));

    if (body.classList.contains('dashboard')) {
        let dashboard = new Dashboard(document);
        dashboard.init();
    }

    if (body.classList.contains('test-history')) {
        testHistoryPage(document);
    }

    if (body.classList.contains('test-results')) {
        testResultsPage(document);
    }

    if (body.classList.contains('user-account')) {
        let userAccount = new UserAccount(window, document);
        userAccount.init();
    }

    if (body.classList.contains('user-account-card')) {
        let userAccountCard = new UserAccountCard(document);
        userAccountCard.init();
    }

    collapseControlCaret(document.querySelectorAll('.collapse-control'));

    [].forEach.call(document.querySelectorAll('.alert'), function (alertElement) {
        AlertFactory.createFromElement(alertElement);
    });
};

document.addEventListener('DOMContentLoaded', onDomContentLoaded);
