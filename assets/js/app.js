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
let TestResults = require('./page/test-results');
let UserAccount = require('./page/user-account');
let UserAccountCard = require('./page/user-account-card');
let AlertFactory = require('./services/alert-factory');
let TestProgress = require('./page/test-progress');
let TestResultsPreparing = require('./page/test-results-preparing');
let TestResultsByTaskType = require('./page/test-results-by-task-type');
let TaskResults = require('./page/task-results');

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

    if (body.classList.contains('test-progress')) {
        let testProgress = new TestProgress(document);
        testProgress.init();
    }

    if (body.classList.contains('test-history')) {
        testHistoryPage(document);
    }

    if (body.classList.contains('test-results')) {
        let testResults = new TestResults(document);
        testResults.init();
    }

    if (body.classList.contains('task-results')) {
        let taskResults = new TaskResults(document);
        taskResults.init();
    }

    if (body.classList.contains('test-results-by-task-type')) {
        let testResultsByTaskType = new TestResultsByTaskType(document);
        testResultsByTaskType.init();
    }

    if (body.classList.contains('test-results-preparing')) {
        let testResultsPreparing = new TestResultsPreparing(document);
        testResultsPreparing.init();
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
