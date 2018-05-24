require('../css/app.scss');

require('classlist-polyfill');

let formButtonSpinner = require('./form-button-spinner');
let formFieldFocuser = require('./form-field-focuser');

const onDomContentLoaded = function () {
    formButtonSpinner(document.querySelectorAll('.js-form-button-spinner'));
    formFieldFocuser(document.querySelectorAll('[data-focused]'));
};

document.addEventListener('DOMContentLoaded', onDomContentLoaded);
