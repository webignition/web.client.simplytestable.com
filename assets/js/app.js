require('../css/app.scss');

require('classlist-polyfill');

let formButtonSpinner = require('./form-button-spinner');

const onDomContentLoaded = function () {
    formButtonSpinner(document.querySelectorAll('.js-form-button-spinner'));
};

document.addEventListener('DOMContentLoaded', onDomContentLoaded);
