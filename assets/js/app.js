require('bootstrap.native');
require('../css/app.scss');

require('classlist-polyfill');

let formButtonSpinner = require('./form-button-spinner');
let formFieldFocuser = require('./form-field-focuser');
let modalControl = require('./modal-control');

let dashboardPage = require('./page/dashboard');
let testHistoryPage = require('./page/test-history');
let testResultsPage = require('./page/test-results');

const onDomContentLoaded = function () {
    let body = document.getElementsByTagName('body').item(0);
    let focusedField = document.querySelector('[data-focused]');

    if (focusedField) {
        formFieldFocuser(focusedField);
    }

    formButtonSpinner(document.querySelectorAll('.js-form-button-spinner'));
    modalControl(document.querySelectorAll('.modal-control'));

    if (body.classList.contains('dashboard')) {
        dashboardPage(document);
    }

    if (body.classList.contains('test-history')) {
        testHistoryPage(document);
    }

    if (body.classList.contains('test-results')) {
        testResultsPage(document);
    }
};

document.addEventListener('DOMContentLoaded', onDomContentLoaded);
