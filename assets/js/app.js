require('../css/app.scss');

require('classlist-polyfill');

let formButtonSpinner = require('./form-button-spinner');
let formFieldFocuser = require('./form-field-focuser');
let modalControl = require('./modal-control');

const onDomContentLoaded = function () {
    formButtonSpinner(document.querySelectorAll('.js-form-button-spinner'));
    formFieldFocuser(document.querySelectorAll('[data-focused]'));
    modalControl(document.querySelectorAll('.modal-control'));
};

document.addEventListener('DOMContentLoaded', onDomContentLoaded);
