/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/build/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/js/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/css/app.scss":
/*!*****************************!*\
  !*** ./assets/css/app.scss ***!
  \*****************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./assets/js/app.js":
/*!**************************!*\
  !*** ./assets/js/app.js ***!
  \**************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! bootstrap.native */ "./node_modules/bootstrap.native/dist/bootstrap-native.js");
__webpack_require__(/*! ../css/app.scss */ "./assets/css/app.scss");

__webpack_require__(/*! classlist-polyfill */ "./node_modules/classlist-polyfill/src/index.js");
__webpack_require__(/*! ./polyfill/custom-event */ "./assets/js/polyfill/custom-event.js");
__webpack_require__(/*! ./polyfill/object-entries */ "./assets/js/polyfill/object-entries.js");

var formButtonSpinner = __webpack_require__(/*! ./form-button-spinner */ "./assets/js/form-button-spinner.js");
var formFieldFocuser = __webpack_require__(/*! ./form-field-focuser */ "./assets/js/form-field-focuser.js");
var modalControl = __webpack_require__(/*! ./modal-control */ "./assets/js/modal-control.js");
var collapseControlCaret = __webpack_require__(/*! ./collapse-control-caret */ "./assets/js/collapse-control-caret.js");

var Dashboard = __webpack_require__(/*! ./page/dashboard */ "./assets/js/page/dashboard.js");
var testHistoryPage = __webpack_require__(/*! ./page/test-history */ "./assets/js/page/test-history.js");
var TestResults = __webpack_require__(/*! ./page/test-results */ "./assets/js/page/test-results.js");
var UserAccount = __webpack_require__(/*! ./page/user-account */ "./assets/js/page/user-account.js");
var UserAccountCard = __webpack_require__(/*! ./page/user-account-card */ "./assets/js/page/user-account-card.js");
var AlertFactory = __webpack_require__(/*! ./services/alert-factory */ "./assets/js/services/alert-factory.js");
var TestProgress = __webpack_require__(/*! ./page/test-progress */ "./assets/js/page/test-progress.js");
var TestResultsPreparing = __webpack_require__(/*! ./page/test-results-preparing */ "./assets/js/page/test-results-preparing.js");
var TestResultsByTaskType = __webpack_require__(/*! ./page/test-results-by-task-type */ "./assets/js/page/test-results-by-task-type.js");
var TaskResults = __webpack_require__(/*! ./page/task-results */ "./assets/js/page/task-results.js");

var onDomContentLoaded = function onDomContentLoaded() {
    var body = document.getElementsByTagName('body').item(0);
    var focusedField = document.querySelector('[data-focused]');

    if (focusedField) {
        formFieldFocuser(focusedField);
    }

    [].forEach.call(document.querySelectorAll('.js-form-button-spinner'), function (formElement) {
        formButtonSpinner(formElement);
    });

    modalControl(document.querySelectorAll('.modal-control'));

    if (body.classList.contains('dashboard')) {
        var dashboard = new Dashboard(document);
        dashboard.init();
    }

    if (body.classList.contains('test-progress')) {
        var testProgress = new TestProgress(document);
        testProgress.init();
    }

    if (body.classList.contains('test-history')) {
        testHistoryPage(document);
    }

    if (body.classList.contains('test-results')) {
        var testResults = new TestResults(document);
        testResults.init();
    }

    if (body.classList.contains('task-results')) {
        var taskResults = new TaskResults(document);
        taskResults.init();
    }

    if (body.classList.contains('test-results-by-task-type')) {
        var testResultsByTaskType = new TestResultsByTaskType(document);
        testResultsByTaskType.init();
    }

    if (body.classList.contains('test-results-preparing')) {
        var testResultsPreparing = new TestResultsPreparing(document);
        testResultsPreparing.init();
    }

    if (body.classList.contains('user-account')) {
        var userAccount = new UserAccount(window, document);
        userAccount.init();
    }

    if (body.classList.contains('user-account-card')) {
        var userAccountCard = new UserAccountCard(document);
        userAccountCard.init();
    }

    collapseControlCaret(document.querySelectorAll('.collapse-control'));

    [].forEach.call(document.querySelectorAll('.alert'), function (alertElement) {
        AlertFactory.createFromElement(alertElement);
    });
};

document.addEventListener('DOMContentLoaded', onDomContentLoaded);

/***/ }),

/***/ "./assets/js/collapse-control-caret.js":
/*!*********************************************!*\
  !*** ./assets/js/collapse-control-caret.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

module.exports = function (controls) {
    var controlIconClass = 'fa';
    var caretUpClass = 'fa-caret-up';
    var caretDownClass = 'fa-caret-down';
    var controlCollapsedClass = 'collapsed';

    var createControlIcon = function createControlIcon(control) {
        var controlIcon = document.createElement('i');
        controlIcon.classList.add(controlIconClass);

        if (control.hasAttribute('data-icon-additional-classes')) {
            controlIcon.classList.add(control.getAttribute('data-icon-additional-classes'));
        }

        if (control.classList.contains(controlCollapsedClass)) {
            controlIcon.classList.add(caretDownClass);
        } else {
            controlIcon.classList.add(caretUpClass);
        }

        return controlIcon;
    };

    var toggleCaret = function toggleCaret(control, controlIcon) {
        if (control.classList.contains(controlCollapsedClass)) {
            controlIcon.classList.remove(caretUpClass);
            controlIcon.classList.add(caretDownClass);
        } else {
            controlIcon.classList.remove(caretDownClass);
            controlIcon.classList.add(caretUpClass);
        }
    };

    var handleControl = function handleControl(control) {
        var eventNameShown = 'shown.bs.collapse';
        var eventNameHidden = 'hidden.bs.collapse';
        var collapsibleElement = document.getElementById(control.getAttribute('data-target').replace('#', ''));
        var controlIcon = createControlIcon(control);

        control.append(controlIcon);

        var shownHiddenEventListener = function shownHiddenEventListener() {
            toggleCaret(control, controlIcon);
        };

        collapsibleElement.addEventListener(eventNameShown, shownHiddenEventListener.bind(this));
        collapsibleElement.addEventListener(eventNameHidden, shownHiddenEventListener.bind(this));
    };

    for (var i = 0; i < controls.length; i++) {
        handleControl(controls[i]);
    }
};

/***/ }),

/***/ "./assets/js/dashboard/recent-test-list.js":
/*!*************************************************!*\
  !*** ./assets/js/dashboard/recent-test-list.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ListedTestCollection = __webpack_require__(/*! ../model/listed-test-collection */ "./assets/js/model/listed-test-collection.js");
var HttpClient = __webpack_require__(/*! ../services/http-client */ "./assets/js/services/http-client.js");

var RecentTestList = function () {
    function RecentTestList(element) {
        _classCallCheck(this, RecentTestList);

        this.element = element;
        this.document = element.ownerDocument;
        this.sourceUrl = element.getAttribute('data-source-url');
        this.listedTestCollection = new ListedTestCollection();
        this.retrievedListedTestCollection = new ListedTestCollection();
    }

    _createClass(RecentTestList, [{
        key: 'init',
        value: function init() {
            this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));

            this._retrieveTests();
        }
    }, {
        key: '_httpRequestRetrievedEventListener',
        value: function _httpRequestRetrievedEventListener(event) {
            var _this = this;

            this._parseRetrievedTests(event.detail.response);
            this._renderRetrievedTests();

            window.setTimeout(function () {
                _this._retrieveTests();
            }, 3000);
        }
    }, {
        key: '_renderRetrievedTests',
        value: function _renderRetrievedTests() {
            var _this2 = this;

            this._removeSpinner();

            this.listedTestCollection.forEach(function (listedTest) {
                if (!_this2.retrievedListedTestCollection.contains(listedTest)) {
                    listedTest.element.parentNode.removeChild(listedTest.element);
                    _this2.listedTestCollection.remove(listedTest);
                }
            });

            this.retrievedListedTestCollection.forEach(function (retrievedListedTest) {
                if (_this2.listedTestCollection.contains(retrievedListedTest)) {
                    var listedTest = _this2.listedTestCollection.get(retrievedListedTest.getTestId());

                    if (retrievedListedTest.getType() !== listedTest.getType()) {
                        _this2.listedTestCollection.remove(listedTest);
                        _this2.element.replaceChild(retrievedListedTest.element, listedTest.element);

                        _this2.listedTestCollection.add(retrievedListedTest);
                        retrievedListedTest.enable();
                    } else {
                        listedTest.renderFromListedTest(retrievedListedTest);
                    }
                } else {
                    _this2.element.insertAdjacentElement('afterbegin', retrievedListedTest.element);
                    _this2.listedTestCollection.add(retrievedListedTest);
                    retrievedListedTest.enable();
                }
            });
        }
    }, {
        key: '_parseRetrievedTests',
        value: function _parseRetrievedTests(response) {
            var retrievedTestsMarkup = response.trim();
            var retrievedTestContainer = document.createElement('div');
            retrievedTestContainer.innerHTML = retrievedTestsMarkup;

            this.retrievedListedTestCollection = ListedTestCollection.createFromNodeList(retrievedTestContainer.querySelectorAll('.listed-test'), false);
        }
    }, {
        key: '_retrieveTests',
        value: function _retrieveTests() {
            HttpClient.getText(this.sourceUrl, this.element, 'retrieveTests');
        }
    }, {
        key: '_removeSpinner',
        value: function _removeSpinner() {
            var spinner = this.element.querySelector('.js-prefill-spinner');

            if (spinner) {
                this.element.removeChild(spinner);
            }
        }
    }]);

    return RecentTestList;
}();

module.exports = RecentTestList;

/***/ }),

/***/ "./assets/js/dashboard/test-start-form.js":
/*!************************************************!*\
  !*** ./assets/js/dashboard/test-start-form.js ***!
  \************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var FormButton = __webpack_require__(/*! ../model/element/form-button */ "./assets/js/model/element/form-button.js");
var HttpAuthenticationOptionsModal = __webpack_require__(/*! ../http-authentication-options-modal */ "./assets/js/http-authentication-options-modal.js");

var TestStartForm = function () {
    function TestStartForm(element) {
        var _this = this;

        _classCallCheck(this, TestStartForm);

        this.document = element.ownerDocument;
        this.element = element;
        this.submitButtons = [];
        this.httpAuthenticationOptionsModal = new HttpAuthenticationOptionsModal(this.document.querySelector('#http-authentication-options-modal'));

        [].forEach.call(this.element.querySelectorAll('[type=submit]'), function (submitButton) {
            _this.submitButtons.push(new FormButton(submitButton));
        });
    }

    _createClass(TestStartForm, [{
        key: 'init',
        value: function init() {
            var _this2 = this;

            this.element.addEventListener('submit', this._submitEventListener.bind(this));

            this.submitButtons.forEach(function (submitButton) {
                submitButton.element.addEventListener('click', _this2._submitButtonEventListener);
            });

            this.httpAuthenticationOptionsModal.init();
        }
    }, {
        key: '_submitEventListener',
        value: function _submitEventListener(event) {
            this.submitButtons.forEach(function (submitButton) {
                submitButton.deEmphasize();
            });

            this._replaceUncheckedCheckboxesWithHiddenFields();
        }
    }, {
        key: 'disable',
        value: function disable() {
            this.submitButtons.forEach(function (submitButton) {
                submitButton.disable();
            });
        }
    }, {
        key: 'enable',
        value: function enable() {
            this.submitButtons.forEach(function (submitButton) {
                submitButton.enable();
            });
        }
    }, {
        key: '_submitButtonEventListener',
        value: function _submitButtonEventListener(event) {
            var buttonElement = event.target;
            var button = new FormButton(buttonElement);

            button.markAsBusy();
        }
    }, {
        key: '_replaceUncheckedCheckboxesWithHiddenFields',
        value: function _replaceUncheckedCheckboxesWithHiddenFields() {
            var _this3 = this;

            [].forEach.call(this.element.querySelectorAll('input[type=checkbox]'), function (input) {
                if (!input.checked) {
                    var hiddenInput = _this3.document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', input.getAttribute('name'));
                    hiddenInput.value = '0';

                    _this3.element.append(hiddenInput);
                }
            });
        }
    }]);

    return TestStartForm;
}();

module.exports = TestStartForm;

/***/ }),

/***/ "./assets/js/form-button-spinner.js":
/*!******************************************!*\
  !*** ./assets/js/form-button-spinner.js ***!
  \******************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var FormButton = __webpack_require__(/*! ./model/element/form-button */ "./assets/js/model/element/form-button.js");

module.exports = function (form) {
    var submitButton = new FormButton(form.querySelector('button[type=submit]'));

    form.addEventListener('submit', function () {
        submitButton.markAsBusy();
    });
};

/***/ }),

/***/ "./assets/js/form-field-focuser.js":
/*!*****************************************!*\
  !*** ./assets/js/form-field-focuser.js ***!
  \*****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

module.exports = function (input) {
    var inputValue = input.value;

    window.setTimeout(function () {
        input.focus();
        input.value = '';
        input.value = inputValue;
    }, 1);
};

/***/ }),

/***/ "./assets/js/http-authentication-options-modal.js":
/*!********************************************************!*\
  !*** ./assets/js/http-authentication-options-modal.js ***!
  \********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpAuthenticationOptionsModal = function () {
    function HttpAuthenticationOptionsModal(element) {
        _classCallCheck(this, HttpAuthenticationOptionsModal);

        this.document = element.ownerDocument;
        this.element = element;
    }

    _createClass(HttpAuthenticationOptionsModal, [{
        key: 'init',
        value: function init() {
            var _this = this;

            this.element.addEventListener('show.bs.modal', function () {
                [].forEach.call(_this.element.querySelectorAll('.js-disable-readonly'), function (inputElement) {
                    inputElement.removeAttribute('readonly');
                });
            });
        }
    }]);

    return HttpAuthenticationOptionsModal;
}();

module.exports = HttpAuthenticationOptionsModal;

/***/ }),

/***/ "./assets/js/modal-control.js":
/*!************************************!*\
  !*** ./assets/js/modal-control.js ***!
  \************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

module.exports = function (controlElements) {
    var initialize = function initialize(controlElement) {
        controlElement.classList.add('btn', 'btn-link');
    };

    for (var i = 0; i < controlElements.length; i++) {
        initialize(controlElements[i]);
    }
};

/***/ }),

/***/ "./assets/js/model/badge-collection.js":
/*!*********************************************!*\
  !*** ./assets/js/model/badge-collection.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var BadgeCollection = function () {
    /**
     * @param {NodeList} badges
     */
    function BadgeCollection(badges) {
        _classCallCheck(this, BadgeCollection);

        this.badges = badges;
    }

    _createClass(BadgeCollection, [{
        key: 'applyUniformWidth',
        value: function applyUniformWidth() {
            var greatestWidth = this._deriveGreatestWidth();

            [].forEach.call(this.badges, function (badge) {
                badge.style.width = greatestWidth + 'px';
            });
        }
    }, {
        key: '_deriveGreatestWidth',


        /**
         * @returns {number}
         * @private
         */
        value: function _deriveGreatestWidth() {
            var greatestWidth = 0;

            [].forEach.call(this.badges, function (badge) {
                if (badge.offsetWidth > greatestWidth) {
                    greatestWidth = badge.offsetWidth;
                }
            });

            return greatestWidth;
        }
    }]);

    return BadgeCollection;
}();

module.exports = BadgeCollection;

/***/ }),

/***/ "./assets/js/model/cookie-options.js":
/*!*******************************************!*\
  !*** ./assets/js/model/cookie-options.js ***!
  \*******************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var CookieOptionsModal = __webpack_require__(/*! ./element/cookie-options-modal */ "./assets/js/model/element/cookie-options-modal.js");

var CookieOptions = function () {
    function CookieOptions(document, cookieOptionsModal, actionBadge, statusElement) {
        _classCallCheck(this, CookieOptions);

        this.document = document;
        this.cookieOptionsModal = cookieOptionsModal;
        this.actionBadge = actionBadge;
        this.statusElement = statusElement;
    }

    _createClass(CookieOptions, [{
        key: 'init',
        value: function init() {
            var _this = this;

            if (!this.cookieOptionsModal.isAccountRequiredModal) {
                var modalCloseEventListener = function modalCloseEventListener() {
                    if (_this.cookieOptionsModal.isEmpty()) {
                        _this.statusElement.innerText = 'not enabled';
                        _this.actionBadge.markNotEnabled();
                    } else {
                        _this.statusElement.innerText = 'enabled';
                        _this.actionBadge.markEnabled();
                    }
                };

                this.cookieOptionsModal.init();

                this.cookieOptionsModal.element.addEventListener(CookieOptionsModal.getOpenedEventName(), function () {
                    _this.document.dispatchEvent(new CustomEvent(CookieOptions.getModalOpenedEventName()));
                });

                this.cookieOptionsModal.element.addEventListener(CookieOptionsModal.getClosedEventName(), function () {
                    modalCloseEventListener();
                    _this.document.dispatchEvent(new CustomEvent(CookieOptions.getModalClosedEventName()));
                });
            }
        }
    }], [{
        key: 'getModalOpenedEventName',


        /**
         * @returns {string}
         */
        value: function getModalOpenedEventName() {
            return 'cookie-options.modal.opened';
        }
    }, {
        key: 'getModalClosedEventName',


        /**
         * @returns {string}
         */
        value: function getModalClosedEventName() {
            return 'cookie-options.modal.closed';
        }
    }]);

    return CookieOptions;
}();

module.exports = CookieOptions;

/***/ }),

/***/ "./assets/js/model/element/action-badge.js":
/*!*************************************************!*\
  !*** ./assets/js/model/element/action-badge.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ActionBadge = function () {
    function ActionBadge(element) {
        _classCallCheck(this, ActionBadge);

        this.element = element;
    }

    _createClass(ActionBadge, [{
        key: 'markEnabled',
        value: function markEnabled() {
            this.element.classList.add('action-badge-enabled');
        }
    }, {
        key: 'markNotEnabled',
        value: function markNotEnabled() {
            this.element.classList.remove('action-badge-enabled');
        }
    }]);

    return ActionBadge;
}();

module.exports = ActionBadge;

/***/ }),

/***/ "./assets/js/model/element/alert.js":
/*!******************************************!*\
  !*** ./assets/js/model/element/alert.js ***!
  \******************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var bsn = __webpack_require__(/*! bootstrap.native */ "./node_modules/bootstrap.native/dist/bootstrap-native.js");
var formFieldFocuser = __webpack_require__(/*! ../../form-field-focuser */ "./assets/js/form-field-focuser.js");

var Alert = function () {
    function Alert(element) {
        _classCallCheck(this, Alert);

        this.element = element;

        var closeButton = element.querySelector('.close');
        if (closeButton) {
            closeButton.addEventListener('click', this._closeButtonClickEventHandler.bind(this));
        }
    }

    _createClass(Alert, [{
        key: 'setStyle',
        value: function setStyle(style) {
            this._removePresentationalClasses();

            this.element.classList.add('alert-' + style);
        }
    }, {
        key: 'wrapContentInContainer',
        value: function wrapContentInContainer() {
            var container = this.element.ownerDocument.createElement('div');
            container.classList.add('container');

            container.innerHTML = this.element.innerHTML;
            this.element.innerHTML = '';

            this.element.appendChild(container);
        }
    }, {
        key: '_removePresentationalClasses',
        value: function _removePresentationalClasses() {
            var presentationalClassPrefix = 'alert-';

            this.element.classList.forEach(function (className, index, classList) {
                if (className.indexOf(presentationalClassPrefix) === 0) {
                    classList.remove(className);
                }
            });
        }
    }, {
        key: '_closeButtonClickEventHandler',
        value: function _closeButtonClickEventHandler() {
            var relatedFieldId = this.element.getAttribute('data-for');
            if (relatedFieldId) {
                var relatedField = this.element.ownerDocument.getElementById(relatedFieldId);

                if (relatedField) {
                    formFieldFocuser(relatedField);
                }
            }

            var bsnAlert = new bsn.Alert(this.element);
            bsnAlert.close();
        }
    }]);

    return Alert;
}();

module.exports = Alert;

/***/ }),

/***/ "./assets/js/model/element/cookie-options-modal.js":
/*!*********************************************************!*\
  !*** ./assets/js/model/element/cookie-options-modal.js ***!
  \*********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var formFieldFocuser = __webpack_require__(/*! ../../form-field-focuser */ "./assets/js/form-field-focuser.js");

var CookieOptionsModal = function () {
    function CookieOptionsModal(element) {
        _classCallCheck(this, CookieOptionsModal);

        this.isAccountRequiredModal = element.classList.contains('account-required-modal');
        this.element = element;
        this.closeButton = element.querySelector('[data-name=close]');
        this.addButton = element.querySelector('.js-add-button');
        this.applyButton = element.querySelector('[data-name=apply');
    }

    _createClass(CookieOptionsModal, [{
        key: 'init',
        value: function init() {
            this._addRemoveActions();
            this._addEventListeners();
        }
    }, {
        key: '_removeActionClickEventListener',
        value: function _removeActionClickEventListener(event) {
            var cookieDataRowCount = this.tableBody.querySelectorAll('.js-cookie').length;
            var removeAction = event.target;
            var tableRow = this.element.ownerDocument.getElementById(removeAction.getAttribute('data-for'));

            if (cookieDataRowCount === 1) {
                var nameInput = tableRow.querySelector('.name input');

                nameInput.value = '';
                tableRow.querySelector('.value input').value = '';

                formFieldFocuser(nameInput);
            } else {
                tableRow.parentNode.removeChild(tableRow);
            }
        }
    }, {
        key: '_inputKeyDownEventListener',


        /**
         * @param {KeyboardEvent} event
         * @private
         */
        value: function _inputKeyDownEventListener(event) {
            if (event.type === 'keydown' && event.key === 'Enter') {
                this.applyButton.click();
            }
        }
    }, {
        key: '_addEventListeners',
        value: function _addEventListeners() {
            var _this = this;

            var shownEventListener = function shownEventListener() {
                _this.tableBody = _this.element.querySelector('tbody');
                _this.previousTableBody = _this.tableBody.cloneNode(true);
                formFieldFocuser(_this.tableBody.querySelector('.js-cookie:last-of-type .name input'));
                _this.element.dispatchEvent(new CustomEvent(CookieOptionsModal.getOpenedEventName()));
            };

            var hiddenEventListener = function hiddenEventListener() {
                _this.element.dispatchEvent(new CustomEvent(CookieOptionsModal.getClosedEventName()));
            };

            var closeButtonClickEventListener = function closeButtonClickEventListener() {
                _this.tableBody.parentNode.replaceChild(_this.previousTableBody, _this.tableBody);
            };

            var addButtonClickEventListener = function addButtonClickEventListener() {
                var tableRow = _this._createTableRow();
                var removeAction = _this._createRemoveAction(tableRow.getAttribute('data-index'));

                tableRow.querySelector('.remove').appendChild(removeAction);

                _this.tableBody.appendChild(tableRow);
                _this._addRemoveActionClickEventListener(removeAction);

                formFieldFocuser(tableRow.querySelector('.name input'));
            };

            this.element.addEventListener('shown.bs.modal', shownEventListener);
            this.element.addEventListener('hidden.bs.modal', hiddenEventListener);
            this.closeButton.addEventListener('click', closeButtonClickEventListener);
            this.addButton.addEventListener('click', addButtonClickEventListener);

            [].forEach.call(this.element.querySelectorAll('.js-remove'), function (removeAction) {
                _this._addRemoveActionClickEventListener(removeAction);
            });

            [].forEach.call(this.element.querySelectorAll('.name input, .value input'), function (input) {
                input.addEventListener('keydown', _this._inputKeyDownEventListener.bind(_this));
            });
        }
    }, {
        key: '_addRemoveActions',
        value: function _addRemoveActions() {
            var _this2 = this;

            [].forEach.call(this.element.querySelectorAll('.remove'), function (removeContainer, index) {
                removeContainer.appendChild(_this2._createRemoveAction(index));
            });
        }
    }, {
        key: '_addRemoveActionClickEventListener',


        /**
         * @param {Element} removeAction
         * @private
         */
        value: function _addRemoveActionClickEventListener(removeAction) {
            removeAction.addEventListener('click', this._removeActionClickEventListener.bind(this));
        }
    }, {
        key: '_createRemoveAction',


        /**
         * @param {number} index
         * @returns {Element | null}
         * @private
         */
        value: function _createRemoveAction(index) {
            var removeActionContainer = this.element.ownerDocument.createElement('div');
            removeActionContainer.innerHTML = '<i class="fa fa-trash-o js-remove" title="Remove this cookie" data-for="cookie-data-row-' + index + '"></i>';

            return removeActionContainer.querySelector('.js-remove');
        }
    }, {
        key: '_createTableRow',


        /**
         * @returns {Node}
         * @private
         */
        value: function _createTableRow() {
            var nextCookieIndex = this.element.getAttribute('data-next-cookie-index');
            var lastTableRow = this.element.querySelector('.js-cookie');
            var tableRow = lastTableRow.cloneNode(true);
            var nameInput = tableRow.querySelector('.name input');
            var valueInput = tableRow.querySelector('.value input');

            nameInput.value = '';
            nameInput.setAttribute('name', 'cookies[' + nextCookieIndex + '][name]');
            nameInput.addEventListener('keyup', this._inputKeyDownEventListener.bind(this));
            valueInput.value = '';
            valueInput.setAttribute('name', 'cookies[' + nextCookieIndex + '][value]');
            valueInput.addEventListener('keyup', this._inputKeyDownEventListener.bind(this));

            tableRow.setAttribute('data-index', nextCookieIndex);
            tableRow.setAttribute('id', 'cookie-data-row-' + nextCookieIndex);
            tableRow.querySelector('.remove').innerHTML = '';

            this.element.setAttribute('data-next-cookie-index', parseInt(nextCookieIndex, 10) + 1);

            return tableRow;
        }
    }, {
        key: 'isEmpty',


        /**
         * @returns {boolean}
         */
        value: function isEmpty() {
            var isEmpty = true;

            [].forEach.call(this.element.querySelectorAll('input'), function (input) {
                if (isEmpty && input.value.trim() !== '') {
                    isEmpty = false;
                }
            });

            return isEmpty;
        }
    }], [{
        key: 'getOpenedEventName',


        /**
         * @returns {string}
         */
        value: function getOpenedEventName() {
            return 'cookie-options-modal.opened';
        }

        /**
         * @returns {string}
         */

    }, {
        key: 'getClosedEventName',
        value: function getClosedEventName() {
            return 'cookie-options-modal.closed';
        }
    }]);

    return CookieOptionsModal;
}();

module.exports = CookieOptionsModal;

/***/ }),

/***/ "./assets/js/model/element/form-button.js":
/*!************************************************!*\
  !*** ./assets/js/model/element/form-button.js ***!
  \************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Icon = __webpack_require__(/*! ./icon */ "./assets/js/model/element/icon.js");

var FormButton = function () {
    function FormButton(element) {
        _classCallCheck(this, FormButton);

        var iconElement = element.querySelector(Icon.getSelector());

        this.element = element;
        this.icon = iconElement ? new Icon(iconElement) : null;
    }

    _createClass(FormButton, [{
        key: 'markAsBusy',
        value: function markAsBusy() {
            if (this.icon) {
                this.icon.setBusy();
                this.deEmphasize();
            }
        }
    }, {
        key: 'markAsAvailable',
        value: function markAsAvailable() {
            if (this.icon) {
                this.icon.setAvailable('fa-caret-right');
                this.unDeEmphasize();
            }
        }
    }, {
        key: 'markSucceeded',
        value: function markSucceeded() {
            if (this.icon) {
                this.icon.setSuccessful();
            }
        }
    }, {
        key: 'disable',
        value: function disable() {
            this.element.setAttribute('disabled', 'disabled');
        }
    }, {
        key: 'enable',
        value: function enable() {
            this.element.removeAttribute('disabled');
        }
    }, {
        key: 'deEmphasize',
        value: function deEmphasize() {
            this.element.classList.add('de-emphasize');
        }
    }, {
        key: 'unDeEmphasize',
        value: function unDeEmphasize() {
            this.element.classList.remove('de-emphasize');
        }
    }]);

    return FormButton;
}();

module.exports = FormButton;

/***/ }),

/***/ "./assets/js/model/element/http-authentication-options-modal.js":
/*!**********************************************************************!*\
  !*** ./assets/js/model/element/http-authentication-options-modal.js ***!
  \**********************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var formFieldFocuser = __webpack_require__(/*! ../../form-field-focuser */ "./assets/js/form-field-focuser.js");

var HttpAuthenticationOptionsModal = function () {
    function HttpAuthenticationOptionsModal(element) {
        _classCallCheck(this, HttpAuthenticationOptionsModal);

        this.isAccountRequiredModal = element.classList.contains('account-required-modal');
        this.element = element;
        this.usernameInput = element.querySelector('[name=http-auth-username]');
        this.passwordInput = element.querySelector('[name=http-auth-password]');
        this.applyButton = element.querySelector('[data-name=apply]');
        this.closeButton = element.querySelector('[data-name=close]');
        this.clearButton = element.querySelector('[data-name=clear]');
        this.previousUsername = null;
        this.previousPassword = null;
    }

    /**
     * @returns {string}
     */


    _createClass(HttpAuthenticationOptionsModal, [{
        key: 'init',
        value: function init() {
            this._addEventListeners();
        }
    }, {
        key: 'isEmpty',
        value: function isEmpty() {
            return this.usernameInput.value.trim() === '' && this.passwordInput.value.trim() === '';
        }
    }, {
        key: '_addEventListeners',
        value: function _addEventListeners() {
            var _this = this;

            var shownEventListener = function shownEventListener() {
                _this.previousUsername = _this.usernameInput.value.trim();
                _this.previousPassword = _this.passwordInput.value.trim();

                var username = _this.usernameInput.value.trim();
                var password = _this.passwordInput.value.trim();

                var focusedInput = username === '' || username !== '' && password !== '' ? _this.usernameInput : _this.passwordInput;

                formFieldFocuser(focusedInput);

                _this.element.dispatchEvent(new CustomEvent(HttpAuthenticationOptionsModal.getOpenedEventName()));
            };

            var hiddenEventListener = function hiddenEventListener() {
                _this.element.dispatchEvent(new CustomEvent(HttpAuthenticationOptionsModal.getClosedEventName()));
            };

            var closeButtonClickEventListener = function closeButtonClickEventListener() {
                _this.usernameInput.value = _this.previousUsername;
                _this.passwordInput.value = _this.previousPassword;
            };

            var clearButtonClickEventListener = function clearButtonClickEventListener() {
                _this.usernameInput.value = '';
                _this.passwordInput.value = '';
            };

            this.element.addEventListener('shown.bs.modal', shownEventListener);
            this.element.addEventListener('hidden.bs.modal', hiddenEventListener);
            this.closeButton.addEventListener('click', closeButtonClickEventListener);
            this.clearButton.addEventListener('click', clearButtonClickEventListener);
            this.usernameInput.addEventListener('keydown', this._inputKeyDownEventListener.bind(this));
            this.passwordInput.addEventListener('keydown', this._inputKeyDownEventListener.bind(this));
        }
    }, {
        key: '_inputKeyDownEventListener',


        /**
         * @param {KeyboardEvent} event
         * @private
         */
        value: function _inputKeyDownEventListener(event) {
            if (event.type === 'keydown' && event.key === 'Enter') {
                this.applyButton.click();
            }
        }
    }], [{
        key: 'getOpenedEventName',
        value: function getOpenedEventName() {
            return 'cookie-options-modal.opened';
        }

        /**
         * @returns {string}
         */

    }, {
        key: 'getClosedEventName',
        value: function getClosedEventName() {
            return 'cookie-options-modal.closed';
        }
    }]);

    return HttpAuthenticationOptionsModal;
}();

module.exports = HttpAuthenticationOptionsModal;

/***/ }),

/***/ "./assets/js/model/element/icon.js":
/*!*****************************************!*\
  !*** ./assets/js/model/element/icon.js ***!
  \*****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Icon = function () {
    function Icon(element) {
        _classCallCheck(this, Icon);

        this.element = element;
    }

    _createClass(Icon, [{
        key: 'setBusy',
        value: function setBusy() {
            this.removePresentationClasses();
            this.element.classList.add('fa-spinner', 'fa-spin');
        }
    }, {
        key: 'setAvailable',
        value: function setAvailable() {
            var activeIconClass = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

            this.removePresentationClasses();

            if (activeIconClass !== null) {
                this.element.classList.add(activeIconClass);
            }
        }
    }, {
        key: 'setSuccessful',
        value: function setSuccessful() {
            this.removePresentationClasses();
            this.setAvailable('fa-check');
        }
    }, {
        key: 'removePresentationClasses',
        value: function removePresentationClasses() {
            var classesToRetain = [Icon.getClass(), Icon.getClass() + '-fw'];

            var presentationalClassPrefix = Icon.getClass() + '-';

            this.element.classList.forEach(function (className, index, classList) {
                if (!classesToRetain.includes(className) && className.indexOf(presentationalClassPrefix) === 0) {
                    classList.remove(className);
                }
            });
        }
    }], [{
        key: 'getClass',
        value: function getClass() {
            return 'fa';
        }
    }, {
        key: 'getSelector',
        value: function getSelector() {
            return '.' + Icon.getClass();
        }
    }]);

    return Icon;
}();

module.exports = Icon;

/***/ }),

/***/ "./assets/js/model/element/listed-test/crawling-listed-test.js":
/*!*********************************************************************!*\
  !*** ./assets/js/model/element/listed-test/crawling-listed-test.js ***!
  \*********************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProgressingListedTest = __webpack_require__(/*! ./progressing-listed-test */ "./assets/js/model/element/listed-test/progressing-listed-test.js");

var CrawlingListedTest = function (_ProgressingListedTes) {
    _inherits(CrawlingListedTest, _ProgressingListedTes);

    function CrawlingListedTest() {
        _classCallCheck(this, CrawlingListedTest);

        return _possibleConstructorReturn(this, (CrawlingListedTest.__proto__ || Object.getPrototypeOf(CrawlingListedTest)).apply(this, arguments));
    }

    _createClass(CrawlingListedTest, [{
        key: 'renderFromListedTest',
        value: function renderFromListedTest(listedTest) {
            _get(CrawlingListedTest.prototype.__proto__ || Object.getPrototypeOf(CrawlingListedTest.prototype), 'renderFromListedTest', this).call(this, listedTest);

            this.element.querySelector('.processed-url-count').innerText = listedTest.element.getAttribute('data-processed-url-count');
            this.element.querySelector('.discovered-url-count').innerText = listedTest.element.getAttribute('data-discovered-url-count');
        }
    }, {
        key: 'getType',
        value: function getType() {
            return 'CrawlingListedTest';
        }
    }]);

    return CrawlingListedTest;
}(ProgressingListedTest);

module.exports = CrawlingListedTest;

/***/ }),

/***/ "./assets/js/model/element/listed-test/listed-test.js":
/*!************************************************************!*\
  !*** ./assets/js/model/element/listed-test/listed-test.js ***!
  \************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ListedTest = function () {
    function ListedTest(element) {
        _classCallCheck(this, ListedTest);

        this.init(element);
    }

    _createClass(ListedTest, [{
        key: 'init',
        value: function init(element) {
            this.element = element;
        }
    }, {
        key: 'enable',
        value: function enable() {}
    }, {
        key: 'getTestId',
        value: function getTestId() {
            return this.element.getAttribute('data-test-id');
        }
    }, {
        key: 'getState',
        value: function getState() {
            return this.element.getAttribute('data-state');
        }
    }, {
        key: 'isFinished',
        value: function isFinished() {
            return this.element.classList.contains('finished');
        }
    }, {
        key: 'renderFromListedTest',
        value: function renderFromListedTest(listedTest) {
            if (this.isFinished()) {
                return;
            }

            if (this.getState() !== listedTest.getState()) {
                this.element.parentNode.replaceChild(listedTest.element, this.element);
                this.init(listedTest.element);
                this.enable();
            }
        }
    }, {
        key: 'getType',
        value: function getType() {
            return 'ListedTest';
        }
    }]);

    return ListedTest;
}();

module.exports = ListedTest;

/***/ }),

/***/ "./assets/js/model/element/listed-test/preparing-listed-test.js":
/*!**********************************************************************!*\
  !*** ./assets/js/model/element/listed-test/preparing-listed-test.js ***!
  \**********************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProgressingListedTest = __webpack_require__(/*! ./progressing-listed-test */ "./assets/js/model/element/listed-test/progressing-listed-test.js");
var TestResultRetriever = __webpack_require__(/*! ../../../services/test-result-retriever */ "./assets/js/services/test-result-retriever.js");

var PreparingListedTest = function (_ProgressingListedTes) {
    _inherits(PreparingListedTest, _ProgressingListedTes);

    function PreparingListedTest() {
        _classCallCheck(this, PreparingListedTest);

        return _possibleConstructorReturn(this, (PreparingListedTest.__proto__ || Object.getPrototypeOf(PreparingListedTest)).apply(this, arguments));
    }

    _createClass(PreparingListedTest, [{
        key: 'enable',
        value: function enable() {
            this._initialiseResultRetriever();
        }
    }, {
        key: '_initialiseResultRetriever',
        value: function _initialiseResultRetriever() {
            var _this2 = this;

            var preparingElement = this.element.querySelector('.preparing');
            var testResultsRetriever = new TestResultRetriever(preparingElement);

            preparingElement.addEventListener(testResultsRetriever.getRetrievedEventName(), function (retrievedEvent) {
                var parentNode = _this2.element.parentNode;
                var retrievedListedTest = retrievedEvent.detail;
                retrievedListedTest.classList.add('fade-out');

                _this2.element.addEventListener('transitionend', function () {
                    parentNode.replaceChild(retrievedListedTest, _this2.element);
                    _this2.element = retrievedEvent.detail;
                    _this2.element.classList.add('fade-in');
                    _this2.element.classList.remove('fade-out');
                });

                _this2.element.classList.add('fade-out');
            });

            testResultsRetriever.init();
        }
    }, {
        key: 'getType',
        value: function getType() {
            return 'PreparingListedTest';
        }
    }]);

    return PreparingListedTest;
}(ProgressingListedTest);

module.exports = PreparingListedTest;

/***/ }),

/***/ "./assets/js/model/element/listed-test/progressing-listed-test.js":
/*!************************************************************************!*\
  !*** ./assets/js/model/element/listed-test/progressing-listed-test.js ***!
  \************************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ListedTest = __webpack_require__(/*! ./listed-test */ "./assets/js/model/element/listed-test/listed-test.js");
var ProgressBar = __webpack_require__(/*! ../progress-bar */ "./assets/js/model/element/progress-bar.js");

var ProgressingListedTest = function (_ListedTest) {
    _inherits(ProgressingListedTest, _ListedTest);

    function ProgressingListedTest() {
        _classCallCheck(this, ProgressingListedTest);

        return _possibleConstructorReturn(this, (ProgressingListedTest.__proto__ || Object.getPrototypeOf(ProgressingListedTest)).apply(this, arguments));
    }

    _createClass(ProgressingListedTest, [{
        key: 'init',
        value: function init(element) {
            _get(ProgressingListedTest.prototype.__proto__ || Object.getPrototypeOf(ProgressingListedTest.prototype), 'init', this).call(this, element);

            var progressBarElement = this.element.querySelector('.progress-bar');
            this.progressBar = progressBarElement ? new ProgressBar(progressBarElement) : null;
        }
    }, {
        key: 'getCompletionPercent',
        value: function getCompletionPercent() {
            var completionPercent = this.element.getAttribute('data-completion-percent');

            if (this.isFinished() && completionPercent === null) {
                return 100;
            }

            return parseFloat(this.element.getAttribute('data-completion-percent'));
        }
    }, {
        key: 'setCompletionPercent',
        value: function setCompletionPercent(completionPercent) {
            this.element.setAttribute('data-completion-percent', completionPercent);
        }
    }, {
        key: 'renderFromListedTest',
        value: function renderFromListedTest(listedTest) {
            _get(ProgressingListedTest.prototype.__proto__ || Object.getPrototypeOf(ProgressingListedTest.prototype), 'renderFromListedTest', this).call(this, listedTest);

            if (this.getCompletionPercent() === listedTest.getCompletionPercent()) {
                return;
            }

            this.setCompletionPercent(listedTest.getCompletionPercent());

            if (this.progressBar) {
                this.progressBar.setCompletionPercent(this.getCompletionPercent());
            }
        }
    }, {
        key: 'getType',
        value: function getType() {
            return 'ProgressingListedTest';
        }
    }]);

    return ProgressingListedTest;
}(ListedTest);

module.exports = ProgressingListedTest;

/***/ }),

/***/ "./assets/js/model/element/progress-bar.js":
/*!*************************************************!*\
  !*** ./assets/js/model/element/progress-bar.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ProgressBar = function () {
    function ProgressBar(element) {
        _classCallCheck(this, ProgressBar);

        this.element = element;
    }

    _createClass(ProgressBar, [{
        key: 'setCompletionPercent',
        value: function setCompletionPercent(completionPercent) {
            this.element.style.width = completionPercent + '%';
            this.element.setAttribute('aria-valuenow', completionPercent);
        }
    }, {
        key: 'setStyle',
        value: function setStyle(style) {
            this._removePresentationalClasses();

            if (style === 'warning') {
                this.element.classList.add('progress-bar-warning');
            }
        }
    }, {
        key: '_removePresentationalClasses',
        value: function _removePresentationalClasses() {
            var presentationalClassPrefix = 'progress-bar-';

            this.element.classList.forEach(function (className, index, classList) {
                if (className.indexOf(presentationalClassPrefix) === 0) {
                    classList.remove(className);
                }
            });
        }
    }]);

    return ProgressBar;
}();

module.exports = ProgressBar;

/***/ }),

/***/ "./assets/js/model/element/sort-control.js":
/*!*************************************************!*\
  !*** ./assets/js/model/element/sort-control.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SortControl = function () {
    /**
     * @param {Element} element
     */
    function SortControl(element) {
        _classCallCheck(this, SortControl);

        this.element = element;
        this.keys = JSON.parse(element.getAttribute('data-sort-keys'));
    }

    _createClass(SortControl, [{
        key: 'init',
        value: function init() {
            this.element.addEventListener('click', this._clickEventListener.bind(this));
        }
    }, {
        key: '_clickEventListener',
        value: function _clickEventListener() {
            if (this.element.classList.contains('sorted')) {
                return;
            }

            this.element.dispatchEvent(new CustomEvent(SortControl.getSortRequestedEventName(), {
                detail: {
                    keys: this.keys
                }
            }));
        }
    }, {
        key: 'setSorted',
        value: function setSorted() {
            this.element.classList.add('sorted');
            this.element.classList.remove('link');
        }
    }, {
        key: 'setNotSorted',
        value: function setNotSorted() {
            this.element.classList.remove('sorted');
            this.element.classList.add('link');
        }
    }], [{
        key: 'getSortRequestedEventName',


        /**
         * @returns {string}
         */
        value: function getSortRequestedEventName() {
            return 'sort-control.sort.requested';
        }
    }]);

    return SortControl;
}();

module.exports = SortControl;

/***/ }),

/***/ "./assets/js/model/element/sortable-item.js":
/*!**************************************************!*\
  !*** ./assets/js/model/element/sortable-item.js ***!
  \**************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SortableItem = function () {
    /**
     * @param {Element} element
     */
    function SortableItem(element) {
        _classCallCheck(this, SortableItem);

        this.element = element;
        this.sortValues = JSON.parse(element.getAttribute('data-sort-values'));
    }

    _createClass(SortableItem, [{
        key: 'getSortValue',


        /**
         * @param {string} key
         *
         * @returns {*}
         */
        value: function getSortValue(key) {
            return this.sortValues[key];
        }
    }]);

    return SortableItem;
}();

module.exports = SortableItem;

/***/ }),

/***/ "./assets/js/model/element/summary-stats-label.js":
/*!********************************************************!*\
  !*** ./assets/js/model/element/summary-stats-label.js ***!
  \********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ScrollTo = __webpack_require__(/*! ../../scroll-to */ "./assets/js/scroll-to.js");

var SummaryStatsLabel = function () {
    /**
     * @param {Element} element
     */
    function SummaryStatsLabel(element) {
        _classCallCheck(this, SummaryStatsLabel);

        this.element = element;
    }

    _createClass(SummaryStatsLabel, [{
        key: 'init',
        value: function init() {
            var href = this.element.getAttribute('href');
            if (href) {
                this.element.addEventListener('click', this._clickEventListener.bind(this));
            }
        }
    }, {
        key: 'setCount',


        /**
         * @param {number} count
         */
        value: function setCount(count) {
            this.element.querySelector('.count').innerText = count;

            if (count === 1) {
                this.element.classList.add('singular');
            } else {
                this.element.classList.remove('singular');
            }
        }

        /**
         * @returns {string}
         */

    }, {
        key: 'getIssueType',
        value: function getIssueType() {
            var issueType = this.element.getAttribute('data-issue-type');

            return issueType === '' ? null : issueType;
        }

        /**
         * @param {Event} event
         * @private
         */

    }, {
        key: '_clickEventListener',
        value: function _clickEventListener(event) {
            event.preventDefault();

            var anchorElement = null;

            event.path.forEach(function (pathElement) {
                if (!anchorElement && pathElement.nodeName === 'A') {
                    anchorElement = pathElement;
                }
            });

            var targetId = anchorElement.getAttribute('href').replace('#', '');
            var target = this.element.ownerDocument.getElementById(targetId);

            if (target) {
                ScrollTo.scrollTo(target, -50);
            }
        }
    }]);

    return SummaryStatsLabel;
}();

module.exports = SummaryStatsLabel;

/***/ }),

/***/ "./assets/js/model/element/task-list.js":
/*!**********************************************!*\
  !*** ./assets/js/model/element/task-list.js ***!
  \**********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Task = __webpack_require__(/*! ./task */ "./assets/js/model/element/task.js");

var TaskList = function () {
    function TaskList(element) {
        var _this = this;

        _classCallCheck(this, TaskList);

        this.element = element;
        this.pageIndex = element ? parseInt(element.getAttribute('data-page-index'), 10) : null;
        this.tasks = {};

        if (element) {
            [].forEach.call(element.querySelectorAll('.task'), function (taskElement) {
                var task = new Task(taskElement);
                _this.tasks[task.getId()] = task;
            });
        }
    }

    /**
     * @returns {number | null}
     */


    _createClass(TaskList, [{
        key: 'getPageIndex',
        value: function getPageIndex() {
            return this.pageIndex;
        }

        /**
         * @returns {boolean}
         */

    }, {
        key: 'hasPageIndex',
        value: function hasPageIndex() {
            return this.pageIndex !== null;
        }

        /**
         * @param {string[]} states
         *
         * @returns {Task[]}
         */

    }, {
        key: 'getTasksByStates',
        value: function getTasksByStates(states) {
            var statesLength = states.length;
            var tasks = [];

            for (var stateIndex = 0; stateIndex < statesLength; stateIndex++) {
                var state = states[stateIndex];

                tasks = tasks.concat(this.getTasksByState(state));
            }

            return tasks;
        }
    }, {
        key: 'getTasksByState',


        /**
         * @param {string} state
         *
         * @returns {Task[]}
         */
        value: function getTasksByState(state) {
            var _this2 = this;

            var tasks = [];
            Object.keys(this.tasks).forEach(function (taskId) {
                var task = _this2.tasks[taskId];

                if (task.getState() === state) {
                    tasks.push(task);
                }
            });

            return tasks;
        }
    }, {
        key: 'updateFromTaskList',


        /**
         * @param {TaskList} updatedTaskList
         */
        value: function updateFromTaskList(updatedTaskList) {
            var _this3 = this;

            Object.keys(updatedTaskList.tasks).forEach(function (taskId) {
                var updatedTask = updatedTaskList.tasks[taskId];
                var task = _this3.tasks[updatedTask.getId()];

                task.updateFromTask(updatedTask);
            });
        }
    }]);

    return TaskList;
}();

module.exports = TaskList;

/***/ }),

/***/ "./assets/js/model/element/task-queue.js":
/*!***********************************************!*\
  !*** ./assets/js/model/element/task-queue.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var TaskQueue = function () {
    function TaskQueue(element) {
        _classCallCheck(this, TaskQueue);

        this.element = element;
        this.value = element.querySelector('.value');
        this.label = element.querySelector('.label-value');
    }

    _createClass(TaskQueue, [{
        key: 'init',
        value: function init() {
            this.label.style.width = this.label.getAttribute('data-width') + '%';
        }
    }, {
        key: 'getQueueId',
        value: function getQueueId() {
            return this.element.getAttribute('data-queue-id');
        }
    }, {
        key: 'setValue',
        value: function setValue(value) {
            this.value.innerText = value;
        }
    }, {
        key: 'setWidth',
        value: function setWidth(width) {
            this.label.style.width = width + '%';
        }
    }]);

    return TaskQueue;
}();

module.exports = TaskQueue;

/***/ }),

/***/ "./assets/js/model/element/task-queues.js":
/*!************************************************!*\
  !*** ./assets/js/model/element/task-queues.js ***!
  \************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var TaskQueue = __webpack_require__(/*! ./task-queue */ "./assets/js/model/element/task-queue.js");

var TaskQueues = function () {
    /**
     * @param {Element} element
     */
    function TaskQueues(element) {
        var _this = this;

        _classCallCheck(this, TaskQueues);

        this.element = element;
        this.queues = {};

        [].forEach.call(element.querySelectorAll('.queue'), function (queueElement) {
            var queue = new TaskQueue(queueElement);
            queue.init();
            _this.queues[queue.getQueueId()] = queue;
        });
    }

    _createClass(TaskQueues, [{
        key: 'render',
        value: function render(taskCount, taskCountByState) {
            var _this2 = this;

            var getWidthForState = function getWidthForState(state) {
                if (taskCount === 0) {
                    return 0;
                }

                if (!taskCountByState.hasOwnProperty(state)) {
                    return 0;
                }

                if (taskCountByState[state] === 0) {
                    return 0;
                }

                return Math.ceil(taskCountByState[state] / taskCount * 100);
            };

            Object.keys(taskCountByState).forEach(function (state) {
                var queue = _this2.queues[state];
                if (queue) {
                    queue.setValue(taskCountByState[state]);
                    queue.setWidth(getWidthForState(state));
                }
            });
        }
    }]);

    return TaskQueues;
}();

module.exports = TaskQueues;

/***/ }),

/***/ "./assets/js/model/element/task.js":
/*!*****************************************!*\
  !*** ./assets/js/model/element/task.js ***!
  \*****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Task = function () {
    function Task(element) {
        _classCallCheck(this, Task);

        this.element = element;
    }

    _createClass(Task, [{
        key: 'getState',
        value: function getState() {
            return this.element.getAttribute('data-state');
        }
    }, {
        key: 'getId',
        value: function getId() {
            return parseInt(this.element.getAttribute('data-task-id'), 10);
        }

        /**
         * @param {Task} updatedTask
         */

    }, {
        key: 'updateFromTask',
        value: function updateFromTask(updatedTask) {
            this.element.parentNode.replaceChild(updatedTask.element, this.element);
            this.element = updatedTask.element;
        }
    }]);

    return Task;
}();

module.exports = Task;

/***/ }),

/***/ "./assets/js/model/element/test-alert-container.js":
/*!*********************************************************!*\
  !*** ./assets/js/model/element/test-alert-container.js ***!
  \*********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpClient = __webpack_require__(/*! ../../services/http-client */ "./assets/js/services/http-client.js");
var AlertFactory = __webpack_require__(/*! ../../services/alert-factory */ "./assets/js/services/alert-factory.js");

var TestAlertContainer = function () {
    function TestAlertContainer(element) {
        _classCallCheck(this, TestAlertContainer);

        this.element = element;
        this.alert = element.querySelector('.alert-ammendment');
    }

    _createClass(TestAlertContainer, [{
        key: 'init',
        value: function init() {
            if (!this.alert) {
                this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
            }
        }
    }, {
        key: '_httpRequestRetrievedEventListener',
        value: function _httpRequestRetrievedEventListener(event) {
            var _this = this;

            var alert = AlertFactory.createFromContent(this.element.ownerDocument, event.detail.response);
            alert.setStyle('info');
            alert.wrapContentInContainer();
            alert.element.classList.add('alert-ammendment');

            this.element.appendChild(alert.element);
            this.alert = alert;

            this.element.classList.add('reveal');
            this.element.addEventListener('transitionend', function () {
                _this.alert.element.classList.add('reveal');
            });
        }
    }, {
        key: 'renderUrlLimitNotification',
        value: function renderUrlLimitNotification() {
            if (!this.alert) {
                HttpClient.get(this.element.getAttribute('data-url-limit-notification-url'), 'text', this.element, 'default');
            }
        }
    }]);

    return TestAlertContainer;
}();

module.exports = TestAlertContainer;

/***/ }),

/***/ "./assets/js/model/element/test-lock-unlock.js":
/*!*****************************************************!*\
  !*** ./assets/js/model/element/test-lock-unlock.js ***!
  \*****************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpClient = __webpack_require__(/*! ../../services/http-client */ "./assets/js/services/http-client.js");
var Icon = __webpack_require__(/*! ../element/icon */ "./assets/js/model/element/icon.js");

var TestLockUnlock = function () {
    function TestLockUnlock(element) {
        _classCallCheck(this, TestLockUnlock);

        this.element = element;
        this.state = element.getAttribute('data-state');
        this.data = {
            locked: JSON.parse(element.getAttribute('data-locked')),
            unlocked: JSON.parse(element.getAttribute('data-unlocked'))
        };
        this.icon = new Icon(element.querySelector(Icon.getSelector()));
        this.action = element.querySelector('.action');
        this.description = element.querySelector('.description');
    }

    _createClass(TestLockUnlock, [{
        key: 'init',
        value: function init() {
            var _this = this;

            this.element.classList.remove('invisible');
            this._render();

            this.element.addEventListener('click', this._clickEventListener.bind(this));
            this.element.addEventListener(HttpClient.getRetrievedEventName(), function () {
                _this.element.classList.remove('de-emphasize');
                _this._toggle();
            });
        }
    }, {
        key: '_toggle',
        value: function _toggle() {
            this.state = this.state === 'locked' ? 'unlocked' : 'locked';
            this._render();
        }
    }, {
        key: '_render',
        value: function _render() {
            this.icon.removePresentationClasses();

            var stateData = this.data[this.state];

            this.icon.setAvailable('fa-' + stateData.icon);
            this.action.innerText = stateData.action;
            this.description.innerText = stateData.description;
        }
    }, {
        key: '_clickEventListener',
        value: function _clickEventListener() {
            event.preventDefault();
            this.icon.removePresentationClasses();

            this.element.classList.add('de-emphasize');
            this.icon.setBusy();

            HttpClient.post(this.data[this.state].url, this.element, 'default');
        }
    }]);

    return TestLockUnlock;
}();

module.exports = TestLockUnlock;

/***/ }),

/***/ "./assets/js/model/http-authentication-options.js":
/*!********************************************************!*\
  !*** ./assets/js/model/http-authentication-options.js ***!
  \********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpAuthenticationOptionsModal = __webpack_require__(/*! ./element/http-authentication-options-modal */ "./assets/js/model/element/http-authentication-options-modal.js");

var HttpAuthenticationOptions = function () {
    function HttpAuthenticationOptions(document, httpAuthenticationOptionsModal, actionBadge, statusElement) {
        _classCallCheck(this, HttpAuthenticationOptions);

        this.document = document;
        this.httpAuthenticationOptionsModal = httpAuthenticationOptionsModal;
        this.actionBadge = actionBadge;
        this.statusElement = statusElement;
    }

    _createClass(HttpAuthenticationOptions, [{
        key: 'init',
        value: function init() {
            var _this = this;

            if (!this.httpAuthenticationOptionsModal.isAccountRequiredModal) {
                var modalCloseEventListener = function modalCloseEventListener() {
                    if (_this.httpAuthenticationOptionsModal.isEmpty()) {
                        _this.statusElement.innerText = 'not enabled';
                        _this.actionBadge.markNotEnabled();
                    } else {
                        _this.statusElement.innerText = 'enabled';
                        _this.actionBadge.markEnabled();
                    }
                };

                this.httpAuthenticationOptionsModal.init();

                this.httpAuthenticationOptionsModal.element.addEventListener(HttpAuthenticationOptionsModal.getOpenedEventName(), function () {
                    _this.document.dispatchEvent(new CustomEvent(HttpAuthenticationOptions.getModalOpenedEventName()));
                });

                this.httpAuthenticationOptionsModal.element.addEventListener(HttpAuthenticationOptionsModal.getClosedEventName(), function () {
                    modalCloseEventListener();
                    _this.document.dispatchEvent(new CustomEvent(HttpAuthenticationOptions.getModalClosedEventName()));
                });
            }
        }
    }], [{
        key: 'getModalOpenedEventName',


        /**
         * @returns {string}
         */
        value: function getModalOpenedEventName() {
            return 'http-authentication-options.modal.opened';
        }
    }, {
        key: 'getModalClosedEventName',


        /**
         * @returns {string}
         */
        value: function getModalClosedEventName() {
            return 'http-authentication-options.modal.closed';
        }
    }]);

    return HttpAuthenticationOptions;
}();

module.exports = HttpAuthenticationOptions;

/***/ }),

/***/ "./assets/js/model/listed-test-collection.js":
/*!***************************************************!*\
  !*** ./assets/js/model/listed-test-collection.js ***!
  \***************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ListedTestFactory = __webpack_require__(/*! ../services/listed-test-factory */ "./assets/js/services/listed-test-factory.js");

var ListedTestCollection = function () {
    function ListedTestCollection() {
        _classCallCheck(this, ListedTestCollection);

        this.listedTests = {};
    }

    /**
     * @param {ListedTest} listedTest
     */


    _createClass(ListedTestCollection, [{
        key: 'add',
        value: function add(listedTest) {
            this.listedTests[listedTest.getTestId()] = listedTest;
        }
    }, {
        key: 'remove',


        /**
         * @param {ListedTest} listedTest
         */
        value: function remove(listedTest) {
            if (this.contains(listedTest)) {
                delete this.listedTests[listedTest.getTestId()];
            }
        }
    }, {
        key: 'contains',


        /**
         * @param {ListedTest} listedTest
         *
         * @returns {boolean}
         */
        value: function contains(listedTest) {
            return this.containsTestId(listedTest.getTestId());
        }
    }, {
        key: 'containsTestId',


        /**
         * @param {number} testId
         *
         * @returns {boolean}
         */
        value: function containsTestId(testId) {
            return Object.keys(this.listedTests).includes(testId);
        }

        /**
         * @param {number} testId
         *
         * @returns {ListedTest|null}
         */

    }, {
        key: 'get',
        value: function get(testId) {
            return this.containsTestId(testId) ? this.listedTests[testId] : null;
        }

        /**
         * @param {function} callback
         */

    }, {
        key: 'forEach',
        value: function forEach(callback) {
            var _this = this;

            Object.keys(this.listedTests).forEach(function (testId) {
                callback(_this.listedTests[testId]);
            });
        }
    }], [{
        key: 'createFromNodeList',


        /**
         * @param {NodeList} nodeList
         *
         * @returns {ListedTestCollection}
         */
        value: function createFromNodeList(nodeList) {
            var collection = new ListedTestCollection();

            [].forEach.call(nodeList, function (listedTestElement) {
                collection.add(ListedTestFactory.createFromElement(listedTestElement));
            });

            return collection;
        }
    }]);

    return ListedTestCollection;
}();

module.exports = ListedTestCollection;

/***/ }),

/***/ "./assets/js/model/sort-control-collection.js":
/*!****************************************************!*\
  !*** ./assets/js/model/sort-control-collection.js ***!
  \****************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SortControlCollection = function () {
    /**
     * @param {SortControl[]} controls
     */
    function SortControlCollection(controls) {
        _classCallCheck(this, SortControlCollection);

        this.controls = controls;
    }

    _createClass(SortControlCollection, [{
        key: "setSorted",
        value: function setSorted(element) {
            this.controls.forEach(function (control) {
                if (control.element === element) {
                    control.setSorted();
                } else {
                    control.setNotSorted();
                }
            });
        }
    }]);

    return SortControlCollection;
}();

module.exports = SortControlCollection;

/***/ }),

/***/ "./assets/js/model/sortable-item-list.js":
/*!***********************************************!*\
  !*** ./assets/js/model/sortable-item-list.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SortableItemList = function () {
    /**
     * @param {SortableItem[]} items
     */
    function SortableItemList(items) {
        _classCallCheck(this, SortableItemList);

        this.items = items;
    }

    _createClass(SortableItemList, [{
        key: 'sort',


        /**
         * @param {string[]} keys
         * @returns {SortableItem[]}
         */
        value: function sort(keys) {
            var _this = this;

            var index = [];
            var sortedItems = [];

            this.items.forEach(function (sortableItem, position) {
                var values = [];

                keys.forEach(function (key) {
                    var value = sortableItem.getSortValue(key);
                    if (Number.isInteger(value)) {
                        value = (1 / value).toString();
                    }

                    values.push(value);
                });

                index.push({
                    position: position,
                    value: values.join(',')
                });
            });

            index.sort(this._compareFunction);

            index.forEach(function (indexItem) {
                sortedItems.push(_this.items[indexItem.position]);
            });

            return sortedItems;
        }
    }, {
        key: '_compareFunction',


        /**
         * @param {Object} a
         * @param {Object} b
         * @returns {number}
         * @private
         */
        value: function _compareFunction(a, b) {
            if (a.value < b.value) {
                return -1;
            }

            if (a.value > b.value) {
                return 1;
            }

            return 0;
        }
    }]);

    return SortableItemList;
}();

module.exports = SortableItemList;

/***/ }),

/***/ "./assets/js/page/dashboard.js":
/*!*************************************!*\
  !*** ./assets/js/page/dashboard.js ***!
  \*************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var unavailableTaskTypeModalLauncher = __webpack_require__(/*! ../unavailable-task-type-modal-launcher */ "./assets/js/unavailable-task-type-modal-launcher.js");
var TestStartForm = __webpack_require__(/*! ../dashboard/test-start-form */ "./assets/js/dashboard/test-start-form.js");
var RecentTestList = __webpack_require__(/*! ../dashboard/recent-test-list */ "./assets/js/dashboard/recent-test-list.js");
var HttpAuthenticationOptionsFactory = __webpack_require__(/*! ../services/http-authentication-options-factory */ "./assets/js/services/http-authentication-options-factory.js");
var HttpAuthenticationOptions = __webpack_require__(/*! ../model/http-authentication-options */ "./assets/js/model/http-authentication-options.js");
var CookieOptionsFactory = __webpack_require__(/*! ../services/cookie-options-factory */ "./assets/js/services/cookie-options-factory.js");
var CookieOptions = __webpack_require__(/*! ../model/cookie-options */ "./assets/js/model/cookie-options.js");

var Dashboard = function () {
    /**
     * @param {Document} document
     */
    function Dashboard(document) {
        _classCallCheck(this, Dashboard);

        this.document = document;
        this.testStartForm = new TestStartForm(document.getElementById('test-start-form'));
        this.recentTestList = new RecentTestList(document.querySelector('.test-list'));
        this.httpAuthenticationOptions = HttpAuthenticationOptionsFactory.create(document.querySelector('.http-authentication-test-option'));
        this.cookieOptions = CookieOptionsFactory.create(document.querySelector('.cookies-test-option'));
    }

    _createClass(Dashboard, [{
        key: 'init',
        value: function init() {
            var _this = this;

            this.document.querySelector('.recent-activity-container').classList.remove('hidden');

            unavailableTaskTypeModalLauncher(this.document.querySelectorAll('.task-type.not-available'));
            this.testStartForm.init();
            this.recentTestList.init();
            this.httpAuthenticationOptions.init();
            this.cookieOptions.init();

            this.document.addEventListener(HttpAuthenticationOptions.getModalOpenedEventName(), function () {
                _this.testStartForm.disable();
            });

            this.document.addEventListener(HttpAuthenticationOptions.getModalClosedEventName(), function () {
                _this.testStartForm.enable();
            });

            this.document.addEventListener(CookieOptions.getModalOpenedEventName(), function () {
                _this.testStartForm.disable();
            });

            this.document.addEventListener(CookieOptions.getModalClosedEventName(), function () {
                _this.testStartForm.enable();
            });
        }
    }]);

    return Dashboard;
}();

module.exports = Dashboard;

/***/ }),

/***/ "./assets/js/page/task-results.js":
/*!****************************************!*\
  !*** ./assets/js/page/task-results.js ***!
  \****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var IssueSection = __webpack_require__(/*! ../task-results/issue-section */ "./assets/js/task-results/issue-section.js");
var SummaryStats = __webpack_require__(/*! ../task-results/summary-stats */ "./assets/js/task-results/summary-stats.js");
var IssueContent = __webpack_require__(/*! ../task-results/issue-content */ "./assets/js/task-results/issue-content.js");

var TaskResults = function () {
    /**
     * @param {Document} document
     */
    function TaskResults(document) {
        var _this = this;

        _classCallCheck(this, TaskResults);

        this.document = document;

        /**
         * @type {SummaryStats[]}
         */
        this.summaryStats = [];

        this.issueContent = new IssueContent(document.querySelector('.issue-content'));

        [].forEach.call(this.document.querySelectorAll('.summary-stats'), function (summaryStatsElement) {
            _this.summaryStats.push(new SummaryStats(summaryStatsElement));
        });
    }

    _createClass(TaskResults, [{
        key: 'init',
        value: function init() {
            var _this2 = this;

            this.summaryStats.forEach(function (summaryStats) {
                summaryStats.init();
            });

            this.issueContent.issueSections.forEach(function (issueSection) {
                issueSection.element.addEventListener(IssueSection.getIssueCountChangedEventName(), _this2._filteredIssueSectionIssueCountChangedEventListener.bind(_this2));
            });

            this.issueContent.init();
        }
    }, {
        key: '_filteredIssueSectionIssueCountChangedEventListener',


        /**
         * @param {CustomEvent} event
         * @private
         */
        value: function _filteredIssueSectionIssueCountChangedEventListener(event) {
            this.summaryStats.forEach(function (summaryStats) {
                summaryStats.setIssueCount(event.detail['issue-type'], event.detail.count);
            });

            document.querySelector('.issue-content').insertAdjacentElement('afterbegin', this._createFilterNotice());

            [].forEach.call(this.document.querySelectorAll('.grouped-issues'), function (groupedIssuesElement) {
                [].forEach.call(groupedIssuesElement.querySelectorAll('.error-group'), function (errorGroupElement) {
                    var issueCount = errorGroupElement.querySelectorAll('.issue').length;

                    if (issueCount === 0) {
                        groupedIssuesElement.removeChild(errorGroupElement);
                    } else {
                        groupedIssuesElement.querySelector('.issue-count').innerText = issueCount;
                    }
                });
            });

            var firstFilteredIssue = this.issueContent.getFirstFilteredIssue();
            var fixElement = firstFilteredIssue.querySelector('.fix');

            if (fixElement) {
                var fixIssueSection = this.issueContent.getIssueSection('fix');

                /**
                 * @type {FixList}
                 */
                var fixList = fixIssueSection.issueLists[0];
                fixList.filterTo(fixElement.getAttribute('href'));

                var fixCount = fixList.count();
                fixIssueSection.renderIssueCount(fixCount);

                this.summaryStats.forEach(function (summaryStats) {
                    summaryStats.setIssueCount('fix', fixCount);
                });
            }
        }
    }, {
        key: '_createFilterNotice',


        /**
         * @returns {Element}
         * @private
         */
        value: function _createFilterNotice() {
            var container = this.document.createElement('div');
            container.innerHTML = '<p class="filter-notice lead">Showing only <span class="message">&ldquo;' + this.issueContent.getFilteredIssueMessage() + '&rdquo;</span> errors. <a href="">Show all.</a></p>';

            return container.querySelector('.filter-notice');
        }
    }]);

    return TaskResults;
}();

module.exports = TaskResults;

/***/ }),

/***/ "./assets/js/page/test-history.js":
/*!****************************************!*\
  !*** ./assets/js/page/test-history.js ***!
  \****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var Modal = __webpack_require__(/*! ../test-history/modal */ "./assets/js/test-history/modal.js");
var Suggestions = __webpack_require__(/*! ../test-history/suggestions */ "./assets/js/test-history/suggestions.js");
var ListedTestCollection = __webpack_require__(/*! ../model/listed-test-collection */ "./assets/js/model/listed-test-collection.js");

module.exports = function (document) {
    var modalId = 'filter-options-modal';
    var filterOptionsSelector = '.action-badge-filter-options';
    var modalElement = document.getElementById(modalId);
    var filterOptionsElement = document.querySelector(filterOptionsSelector);

    var modal = new Modal(modalElement);
    var suggestions = new Suggestions(document, modalElement.getAttribute('data-source-url'));

    /**
     * @param {CustomEvent} event
     */
    var suggestionsLoadedEventListener = function suggestionsLoadedEventListener(event) {
        modal.setSuggestions(event.detail);
        filterOptionsElement.classList.add('visible');
    };

    /**
     * @param {CustomEvent} event
     */
    var filterChangedEventListener = function filterChangedEventListener(event) {
        var filter = event.detail;
        var search = filter === '' ? '' : '?filter=' + filter;
        var currentSearch = window.location.search;

        if (currentSearch === '' && filter === '') {
            return;
        }

        if (search !== currentSearch) {
            window.location.search = search;
        }
    };

    document.addEventListener(suggestions.loadedEventName, suggestionsLoadedEventListener);
    modalElement.addEventListener(modal.filterChangedEventName, filterChangedEventListener);

    suggestions.retrieve();

    var listedTestCollection = ListedTestCollection.createFromNodeList(document.querySelectorAll('.listed-test'));
    listedTestCollection.forEach(function (listedTest) {
        listedTest.enable();
    });
};

/***/ }),

/***/ "./assets/js/page/test-progress.js":
/*!*****************************************!*\
  !*** ./assets/js/page/test-progress.js ***!
  \*****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Summary = __webpack_require__(/*! ../test-progress/summary */ "./assets/js/test-progress/summary.js");
var TaskList = __webpack_require__(/*! ../test-progress/task-list */ "./assets/js/test-progress/task-list.js");
var TaskListPagination = __webpack_require__(/*! ../test-progress/task-list-paginator */ "./assets/js/test-progress/task-list-paginator.js");
var TestAlertContainer = __webpack_require__(/*! ../model/element/test-alert-container */ "./assets/js/model/element/test-alert-container.js");
var HttpClient = __webpack_require__(/*! ../services/http-client */ "./assets/js/services/http-client.js");

var TestProgress = function () {
    /**
     * @param {Document} document
     */
    function TestProgress(document) {
        _classCallCheck(this, TestProgress);

        this.pageLength = 100;
        this.document = document;
        this.summary = new Summary(document.querySelector('.js-summary'));
        this.taskListElement = document.querySelector('.test-progress-tasks');
        this.taskList = new TaskList(this.taskListElement, this.pageLength);
        this.alertContainer = new TestAlertContainer(document.querySelector('.alert-container'));
        this.taskListPagination = null;
        this.taskListIsInitialized = false;
        this.summaryData = null;
    }

    _createClass(TestProgress, [{
        key: 'init',
        value: function init() {
            this.summary.init();
            this.alertContainer.init();
            this._addEventListeners();

            this._refreshSummary();
        }
    }, {
        key: '_addEventListeners',
        value: function _addEventListeners() {
            var _this = this;

            this.summary.element.addEventListener(Summary.getRenderAmmendmentEventName(), function () {
                _this.alertContainer.renderUrlLimitNotification();
            });

            this.document.body.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
            this.taskListElement.addEventListener(TaskList.getInitializedEventName(), this._taskListInitializedEventListener.bind(this));
        }
    }, {
        key: '_addPaginationEventListeners',
        value: function _addPaginationEventListeners() {
            var _this2 = this;

            this.taskListPagination.element.addEventListener(TaskListPagination.getSelectPageEventName(), function (event) {
                var pageIndex = event.detail;

                _this2.taskList.setCurrentPageIndex(pageIndex);
                _this2.taskListPagination.selectPage(pageIndex);
                _this2.taskList.render(pageIndex);
            });

            this.taskListPagination.element.addEventListener(TaskListPagination.getSelectPreviousPageEventName(), function (event) {
                var pageIndex = Math.max(_this2.taskList.currentPageIndex - 1, 0);

                _this2.taskList.setCurrentPageIndex(pageIndex);
                _this2.taskListPagination.selectPage(pageIndex);
                _this2.taskList.render(pageIndex);
            });

            this.taskListPagination.element.addEventListener(TaskListPagination.getSelectNextPageEventName(), function (event) {
                var pageIndex = Math.min(_this2.taskList.currentPageIndex + 1, _this2.taskListPagination.pageCount - 1);

                _this2.taskList.setCurrentPageIndex(pageIndex);
                _this2.taskListPagination.selectPage(pageIndex);
                _this2.taskList.render(pageIndex);
            });
        }
    }, {
        key: '_httpRequestRetrievedEventListener',
        value: function _httpRequestRetrievedEventListener(event) {
            var _this3 = this;

            if (event.detail.requestId === 'test-progress.summary.update') {
                if (event.detail.request.responseURL.indexOf(window.location.toString()) !== 0) {
                    this.summary.progressBar.setCompletionPercent(100);
                    window.location.href = event.detail.request.responseURL;

                    return;
                }

                console.log(event.detail.response);

                this.summaryData = event.detail.response;

                var state = this.summaryData.test.state;
                var taskCount = this.summaryData.test.task_count;
                var isStateQueuedOrInProgress = ['queued', 'in-progress'].indexOf(state) !== -1;

                this._setBodyJobClass(this.document.body.classList, state);
                this.summary.render(this.summaryData);

                if (taskCount > 0 && isStateQueuedOrInProgress && !this.taskListIsInitialized && !this.taskList.isInitializing) {
                    this.taskList.init();
                }
            }

            window.setTimeout(function () {
                _this3._refreshSummary();
            }, 3000);
        }
    }, {
        key: '_taskListInitializedEventListener',
        value: function _taskListInitializedEventListener() {
            this.taskListIsInitialized = true;
            this.taskList.render(0);
            this.taskListPagination = new TaskListPagination(this.pageLength, this.summaryData.test.task_count);

            if (this.taskListPagination.isRequired() && !this.taskListPagination.isRendered()) {
                this.taskListPagination.init(this._createPaginationElement());
                this.taskList.setPaginationElement(this.taskListPagination.element);
                this._addPaginationEventListeners();
            }
        }
    }, {
        key: '_createPaginationElement',


        /**
         * @returns {Element}
         * @private
         */
        value: function _createPaginationElement() {
            var container = this.document.createElement('div');
            container.innerHTML = this.taskListPagination.createMarkup();

            return container.querySelector(TaskListPagination.getSelector());
        }
    }, {
        key: '_refreshSummary',
        value: function _refreshSummary() {
            var summaryUrl = this.document.body.getAttribute('data-summary-url');
            var now = new Date();

            HttpClient.getJson(summaryUrl + '?timestamp=' + now.getTime(), this.document.body, 'test-progress.summary.update');
        }
    }, {
        key: '_setBodyJobClass',

        /**
         *
         * @param {DOMTokenList} bodyClassList
         * @param {string} testState
         * @private
         */
        value: function _setBodyJobClass(bodyClassList, testState) {
            var jobClassPrefix = 'job-';
            bodyClassList.forEach(function (className, index, classList) {
                if (className.indexOf(jobClassPrefix) === 0) {
                    classList.remove(className);
                }
            });

            bodyClassList.add('job-' + testState);
        }
    }]);

    return TestProgress;
}();

module.exports = TestProgress;

/***/ }),

/***/ "./assets/js/page/test-results-by-task-type.js":
/*!*****************************************************!*\
  !*** ./assets/js/page/test-results-by-task-type.js ***!
  \*****************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ByPageList = __webpack_require__(/*! ../test-results-by-task-type/by-page-list */ "./assets/js/test-results-by-task-type/by-page-list.js");
var ByErrorList = __webpack_require__(/*! ../test-results-by-task-type/by-error-list */ "./assets/js/test-results-by-task-type/by-error-list.js");

var TestResultsByTaskType = function () {
    /**
     * @param {Document} document
     */
    function TestResultsByTaskType(document) {
        _classCallCheck(this, TestResultsByTaskType);

        this.document = document;

        var byErrorContainerElement = document.querySelector('.by-error-container');

        this.byPageList = byErrorContainerElement ? null : new ByPageList(document.querySelector('.by-page-container'));
        this.byErrorList = byErrorContainerElement ? new ByErrorList(byErrorContainerElement) : null;
    }

    _createClass(TestResultsByTaskType, [{
        key: 'init',
        value: function init() {
            if (this.byPageList) {
                this.byPageList.init();
            }

            if (this.byErrorList) {
                this.byErrorList.init();
            }
        }
    }]);

    return TestResultsByTaskType;
}();

module.exports = TestResultsByTaskType;

/***/ }),

/***/ "./assets/js/page/test-results-preparing.js":
/*!**************************************************!*\
  !*** ./assets/js/page/test-results-preparing.js ***!
  \**************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ProgressBar = __webpack_require__(/*! ../model/element/progress-bar */ "./assets/js/model/element/progress-bar.js");
var HttpClient = __webpack_require__(/*! ../services/http-client */ "./assets/js/services/http-client.js");

var TestResultsPreparing = function () {
    /**
     * @param {Document} document
     */
    function TestResultsPreparing(document) {
        _classCallCheck(this, TestResultsPreparing);

        this.document = document;
        this.unretrievedRemoteTaskIdsUrl = document.body.getAttribute('data-unretrieved-remote-task-ids-url');
        this.resultsRetrieveUrl = document.body.getAttribute('data-results-retrieve-url');
        this.retrievalStatsUrl = document.body.getAttribute('data-preparing-stats-url');
        this.resultsUrl = document.body.getAttribute('data-results-url');
        this.progressBar = new ProgressBar(document.querySelector('.progress-bar'));
        this.completionPercentValue = document.querySelector('.completion-percent-value');
        this.localTaskCount = document.querySelector('.local-task-count');
        this.remoteTaskCount = document.querySelector('.remote-task-count');
    }

    _createClass(TestResultsPreparing, [{
        key: 'init',
        value: function init() {
            this.document.body.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
            this._checkCompletionStatus();
        }
    }, {
        key: '_checkCompletionStatus',
        value: function _checkCompletionStatus() {
            if (parseInt(document.body.getAttribute('data-remaining-tasks-to-retrieve-count'), 10) === 0) {
                window.location.href = this.resultsUrl;
            } else {
                this._retrieveNextRemoteTaskIdCollection();
            }
        }
    }, {
        key: '_httpRequestRetrievedEventListener',
        value: function _httpRequestRetrievedEventListener(event) {
            var requestId = event.detail.requestId;
            var response = event.detail.response;

            if (requestId === 'retrieveNextRemoteTaskIdCollection') {
                this._retrieveRemoteTaskCollection(response);
            }

            if (requestId === 'retrieveRemoteTaskCollection') {
                this._retrieveRetrievalStats();
            }

            if (requestId === 'retrieveRetrievalStats') {
                if (!response.hasOwnProperty('completion_percent')) {
                    response.completion_percent = 0;
                }

                if (!response.hasOwnProperty('remaining_tasks_to_retrieve_count')) {
                    response.remaining_tasks_to_retrieve_count = 0;
                }

                if (!response.hasOwnProperty('local_task_count')) {
                    response.local_task_count = 0;
                }

                if (!response.hasOwnProperty('remote_task_count')) {
                    response.remote_task_count = 0;
                }

                var completionPercent = response.completion_percent;

                this.document.body.setAttribute('data-remaining-tasks-to-retrieve-count', response.remaining_tasks_to_retrieve_count.toString(10));
                this.completionPercentValue.innerText = completionPercent;
                this.progressBar.setCompletionPercent(completionPercent);
                this.localTaskCount.innerText = response.local_task_count;
                this.remoteTaskCount.innerText = response.remote_task_count;

                this._checkCompletionStatus();
            }
        }
    }, {
        key: '_retrieveNextRemoteTaskIdCollection',
        value: function _retrieveNextRemoteTaskIdCollection() {
            HttpClient.getJson(this.unretrievedRemoteTaskIdsUrl, this.document.body, 'retrieveNextRemoteTaskIdCollection');
        }
    }, {
        key: '_retrieveRemoteTaskCollection',
        value: function _retrieveRemoteTaskCollection(remoteTaskIds) {
            HttpClient.post(this.resultsRetrieveUrl, this.document.body, 'retrieveRemoteTaskCollection', 'remoteTaskIds=' + remoteTaskIds.join(','));
        }
    }, {
        key: '_retrieveRetrievalStats',
        value: function _retrieveRetrievalStats() {
            HttpClient.getJson(this.retrievalStatsUrl, this.document.body, 'retrieveRetrievalStats');
        }
    }]);

    return TestResultsPreparing;
}();

module.exports = TestResultsPreparing;

/***/ }),

/***/ "./assets/js/page/test-results.js":
/*!****************************************!*\
  !*** ./assets/js/page/test-results.js ***!
  \****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var unavailableTaskTypeModalLauncher = __webpack_require__(/*! ../unavailable-task-type-modal-launcher */ "./assets/js/unavailable-task-type-modal-launcher.js");
var HttpAuthenticationOptionsFactory = __webpack_require__(/*! ../services/http-authentication-options-factory */ "./assets/js/services/http-authentication-options-factory.js");
var CookieOptionsFactory = __webpack_require__(/*! ../services/cookie-options-factory */ "./assets/js/services/cookie-options-factory.js");
var TestLockUnlock = __webpack_require__(/*! ../model/element/test-lock-unlock */ "./assets/js/model/element/test-lock-unlock.js");
var FormButton = __webpack_require__(/*! ../model/element/form-button */ "./assets/js/model/element/form-button.js");
var BadgeCollection = __webpack_require__(/*! ../model/badge-collection */ "./assets/js/model/badge-collection.js");
var HttpAuthenticationOptionsModal = __webpack_require__(/*! ../http-authentication-options-modal */ "./assets/js/http-authentication-options-modal.js");

var TestResults = function () {
    /**
     * @param {Document} document
     */
    function TestResults(document) {
        _classCallCheck(this, TestResults);

        this.document = document;
        this.httpAuthenticationOptions = HttpAuthenticationOptionsFactory.create(document.querySelector('.http-authentication-test-option'));
        this.cookieOptions = CookieOptionsFactory.create(document.querySelector('.cookies-test-option'));
        this.retestForm = document.querySelector('.retest-form');
        this.retestButton = new FormButton(this.retestForm.querySelector('button[type=submit]'));
        this.taskTypeSummaryBadgeCollection = new BadgeCollection(document.querySelectorAll('.task-type-summary .badge'));

        var testLockUnlockElement = document.querySelector('.btn-lock-unlock');
        this.testLockUnlock = testLockUnlockElement ? new TestLockUnlock(testLockUnlockElement) : null;

        this.httpAuthenticationOptionsModal = new HttpAuthenticationOptionsModal(this.document.querySelector('#http-authentication-options-modal'));
    }

    _createClass(TestResults, [{
        key: 'init',
        value: function init() {
            var _this = this;

            unavailableTaskTypeModalLauncher(this.document.querySelectorAll('.task-type-option.not-available'));
            this.httpAuthenticationOptions.init();
            this.cookieOptions.init();
            this.taskTypeSummaryBadgeCollection.applyUniformWidth();

            if (this.testLockUnlock) {
                this.testLockUnlock.init();
            }

            this.retestForm.addEventListener('submit', function () {
                _this.retestButton.deEmphasize();
                _this.retestButton.markAsBusy();
            });

            this.httpAuthenticationOptionsModal.init();
        }
    }]);

    return TestResults;
}();

module.exports = TestResults;

/***/ }),

/***/ "./assets/js/page/user-account-card.js":
/*!*********************************************!*\
  !*** ./assets/js/page/user-account-card.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Form = __webpack_require__(/*! ../user-account-card/form */ "./assets/js/user-account-card/form.js");
var FormValidator = __webpack_require__(/*! ../user-account-card/form-validator */ "./assets/js/user-account-card/form-validator.js");
var StripeHandler = __webpack_require__(/*! ../services/stripe-handler */ "./assets/js/services/stripe-handler.js");

var UserAccountCard = function () {
    /**
     * @param {Document} document
     */
    function UserAccountCard(document) {
        _classCallCheck(this, UserAccountCard);

        // eslint-disable-next-line no-undef
        var stripeJs = Stripe;
        var formValidator = new FormValidator(stripeJs);
        this.stripeHandler = new StripeHandler(stripeJs);

        this.form = new Form(document.getElementById('payment-form'), formValidator);
    }

    _createClass(UserAccountCard, [{
        key: 'init',
        value: function init() {
            this.form.init();
            this.stripeHandler.setStripePublishableKey(this.form.getStripePublishableKey());

            var updateCard = this.form.getUpdateCardEventName();
            var createCardTokenSuccess = this.stripeHandler.getCreateCardTokenSuccessEventName();
            var createCardTokenFailure = this.stripeHandler.getCreateCardTokenFailureEventName();

            this.form.element.addEventListener(updateCard, this._updateCardEventListener.bind(this));
            this.form.element.addEventListener(createCardTokenSuccess, this._createCardTokenSuccessEventListener.bind(this));
            this.form.element.addEventListener(createCardTokenFailure, this._createCardTokenFailureEventListener.bind(this));
        }
    }, {
        key: '_updateCardEventListener',
        value: function _updateCardEventListener(updateCardEvent) {
            this.stripeHandler.createCardToken(updateCardEvent.detail, this.form.element);
        }
    }, {
        key: '_createCardTokenSuccessEventListener',
        value: function _createCardTokenSuccessEventListener(stripeCreateCardTokenEvent) {
            var _this = this;

            var requestUrl = window.location.pathname + stripeCreateCardTokenEvent.detail.id + '/associate/';
            var request = new XMLHttpRequest();

            request.open('POST', requestUrl);
            request.responseType = 'json';
            request.setRequestHeader('Accept', 'application/json');

            request.addEventListener('load', function (event) {
                var data = request.response;

                if (data.hasOwnProperty('this_url')) {
                    _this.form.submitButton.markAsAvailable();
                    _this.form.submitButton.markSucceeded();

                    window.setTimeout(function () {
                        window.location = data.this_url;
                    }, 500);
                } else {
                    _this.form.enable();

                    if (data.hasOwnProperty('user_account_card_exception_param') && data['user_account_card_exception_param'] !== '') {
                        _this.form.handleResponseError({
                            'param': data.user_account_card_exception_param,
                            'message': data.user_account_card_exception_message
                        });
                    } else {
                        _this.form.handleResponseError({
                            'param': 'number',
                            'message': data.user_account_card_exception_message
                        });
                    }
                }
            });

            request.send();
        }
    }, {
        key: '_createCardTokenFailureEventListener',
        value: function _createCardTokenFailureEventListener(stripeCreateCardTokenEvent) {
            var responseError = this.form.createResponseError(stripeCreateCardTokenEvent.detail.error.param, 'invalid');

            this.form.enable();
            this.form.handleResponseError(responseError);
        }
    }]);

    return UserAccountCard;
}();

module.exports = UserAccountCard;

/***/ }),

/***/ "./assets/js/page/user-account.js":
/*!****************************************!*\
  !*** ./assets/js/page/user-account.js ***!
  \****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ScrollSpy = __webpack_require__(/*! ../user-account/scrollspy */ "./assets/js/user-account/scrollspy.js");
var NavBarList = __webpack_require__(/*! ../user-account/navbar-list */ "./assets/js/user-account/navbar-list.js");
var ScrollTo = __webpack_require__(/*! ../scroll-to */ "./assets/js/scroll-to.js");
var StickyFill = __webpack_require__(/*! stickyfilljs */ "./node_modules/stickyfilljs/dist/stickyfill.js");
var PositionStickyDetector = __webpack_require__(/*! ../services/position-sticky-detector */ "./assets/js/services/position-sticky-detector.js");

var UserAccount = function () {
    /**
     * @param {Window} window
     * @param {Document} document
     */
    function UserAccount(window, document) {
        _classCallCheck(this, UserAccount);

        this.window = window;
        this.document = document;
        this.scrollOffset = 60;
        var scrollSpyOffset = 100;
        this.sideNavElement = document.getElementById('sidenav');

        this.navBarList = new NavBarList(this.sideNavElement, this.scrollOffset);
        this.scrollspy = new ScrollSpy(this.navBarList, scrollSpyOffset);
    }

    _createClass(UserAccount, [{
        key: '_applyInitialScroll',
        value: function _applyInitialScroll() {
            var targetId = this.window.location.hash.trim().replace('#', '');

            if (targetId) {
                var target = this.document.getElementById(targetId);
                var relatedAnchor = this.sideNavElement.querySelector('a[href=\\#' + targetId + ']');

                if (target) {
                    if (relatedAnchor.classList.contains('js-first')) {
                        ScrollTo.goTo(target, 0);
                    } else {
                        ScrollTo.scrollTo(target, this.scrollOffset);
                    }
                }
            }
        }
    }, {
        key: '_applyPositionStickyPolyfill',
        value: function _applyPositionStickyPolyfill() {
            var stickyNavJsClass = 'js-sticky-nav';
            var stickyNavJsSelector = '.' + stickyNavJsClass;

            var stickyNav = document.querySelector(stickyNavJsSelector);

            var supportsPositionSticky = PositionStickyDetector.supportsPositionSticky();
            if (supportsPositionSticky) {
                stickyNav.classList.remove(stickyNavJsClass);
            } else {
                StickyFill.addOne(stickyNav);
            }
        }
    }, {
        key: 'init',
        value: function init() {
            this.sideNavElement.querySelector('a').classList.add('js-first');
            this.scrollspy.spy();
            this._applyPositionStickyPolyfill();
            this._applyInitialScroll();
        }
    }]);

    return UserAccount;
}();

module.exports = UserAccount;

/***/ }),

/***/ "./assets/js/polyfill/custom-event.js":
/*!********************************************!*\
  !*** ./assets/js/polyfill/custom-event.js ***!
  \********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

// Polyfill for browsers not supporting new CustomEvent()
// Lightly modified from polyfill provided at https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent#Polyfill
(function () {
    if (typeof window.CustomEvent === 'function') return false;

    function CustomEvent(event, params) {
        params = params || { bubbles: false, cancelable: false, detail: undefined };
        var customEvent = document.createEvent('CustomEvent');
        customEvent.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);

        return customEvent;
    }

    CustomEvent.prototype = window.Event.prototype;

    window.CustomEvent = CustomEvent;
})();

/***/ }),

/***/ "./assets/js/polyfill/object-entries.js":
/*!**********************************************!*\
  !*** ./assets/js/polyfill/object-entries.js ***!
  \**********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

// Polyfill for browsers not supporting Object.entries()
// Lightly modified from polyfill provided at https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/entries#Polyfill
if (!Object.entries) {
    Object.entries = function (obj) {
        var ownProps = Object.keys(obj);
        var i = ownProps.length;
        var resArray = new Array(i);

        while (i--) {
            resArray[i] = [ownProps[i], obj[ownProps[i]]];
        }

        return resArray;
    };
}

/***/ }),

/***/ "./assets/js/scroll-to.js":
/*!********************************!*\
  !*** ./assets/js/scroll-to.js ***!
  \********************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SmoothScroll = __webpack_require__(/*! smooth-scroll */ "./node_modules/smooth-scroll/dist/smooth-scroll.min.js");

var ScrollTo = function () {
    function ScrollTo() {
        _classCallCheck(this, ScrollTo);
    }

    _createClass(ScrollTo, null, [{
        key: 'scrollTo',
        value: function scrollTo(target, offset) {
            var scroll = new SmoothScroll();

            scroll.animateScroll(target.offsetTop + offset);
            ScrollTo._updateHistory(target);
        }
    }, {
        key: 'goTo',
        value: function goTo(target, offset) {
            var scroll = new SmoothScroll();

            scroll.animateScroll(offset);
            ScrollTo._updateHistory(target);
        }
    }, {
        key: '_updateHistory',
        value: function _updateHistory(target) {
            if (window.history.pushState) {
                window.history.pushState(null, null, '#' + target.getAttribute('id'));
            }
        }
    }]);

    return ScrollTo;
}();

module.exports = ScrollTo;

/***/ }),

/***/ "./assets/js/services/alert-factory.js":
/*!*********************************************!*\
  !*** ./assets/js/services/alert-factory.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Alert = __webpack_require__(/*! ../model/element/alert */ "./assets/js/model/element/alert.js");

var AlertFactory = function () {
    function AlertFactory() {
        _classCallCheck(this, AlertFactory);
    }

    _createClass(AlertFactory, null, [{
        key: 'createFromContent',
        value: function createFromContent(document, errorContent, relatedFieldId) {
            var element = document.createElement('div');
            element.classList.add('alert', 'alert-danger', 'fade', 'in');
            element.setAttribute('role', 'alert');

            var elementInnerHTML = '';

            if (relatedFieldId) {
                element.setAttribute('data-for', relatedFieldId);
                elementInnerHTML += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
            }

            elementInnerHTML += errorContent;
            element.innerHTML = elementInnerHTML;

            return new Alert(element);
        }
    }, {
        key: 'createFromElement',
        value: function createFromElement(alertElement) {
            return new Alert(alertElement);
        }
    }]);

    return AlertFactory;
}();

module.exports = AlertFactory;

/***/ }),

/***/ "./assets/js/services/cookie-options-factory.js":
/*!******************************************************!*\
  !*** ./assets/js/services/cookie-options-factory.js ***!
  \******************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var CookieOptionsModal = __webpack_require__(/*! ../model/element/cookie-options-modal */ "./assets/js/model/element/cookie-options-modal.js");
var CookieOptions = __webpack_require__(/*! ../model/cookie-options */ "./assets/js/model/cookie-options.js");
var ActionBadge = __webpack_require__(/*! ../model/element/action-badge */ "./assets/js/model/element/action-badge.js");

var CookieOptionsFactory = function () {
    function CookieOptionsFactory() {
        _classCallCheck(this, CookieOptionsFactory);
    }

    _createClass(CookieOptionsFactory, null, [{
        key: 'create',
        value: function create(container) {
            return new CookieOptions(container.ownerDocument, new CookieOptionsModal(container.querySelector('.modal')), new ActionBadge(container.querySelector('.modal-launcher')), container.querySelector('.status'));
        }
    }]);

    return CookieOptionsFactory;
}();

module.exports = CookieOptionsFactory;

/***/ }),

/***/ "./assets/js/services/http-authentication-options-factory.js":
/*!*******************************************************************!*\
  !*** ./assets/js/services/http-authentication-options-factory.js ***!
  \*******************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpAuthenticationOptionsModal = __webpack_require__(/*! ../model/element/http-authentication-options-modal */ "./assets/js/model/element/http-authentication-options-modal.js");
var HttpAuthenticationOptions = __webpack_require__(/*! ../model/http-authentication-options */ "./assets/js/model/http-authentication-options.js");
var ActionBadge = __webpack_require__(/*! ../model/element/action-badge */ "./assets/js/model/element/action-badge.js");

var HttpAuthenticationOptionsFactory = function () {
    function HttpAuthenticationOptionsFactory() {
        _classCallCheck(this, HttpAuthenticationOptionsFactory);
    }

    _createClass(HttpAuthenticationOptionsFactory, null, [{
        key: 'create',
        value: function create(container) {
            return new HttpAuthenticationOptions(container.ownerDocument, new HttpAuthenticationOptionsModal(container.querySelector('.modal')), new ActionBadge(container.querySelector('.modal-launcher')), container.querySelector('.status'));
        }
    }]);

    return HttpAuthenticationOptionsFactory;
}();

module.exports = HttpAuthenticationOptionsFactory;

/***/ }),

/***/ "./assets/js/services/http-client.js":
/*!*******************************************!*\
  !*** ./assets/js/services/http-client.js ***!
  \*******************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpClient = function () {
    function HttpClient() {
        _classCallCheck(this, HttpClient);
    }

    _createClass(HttpClient, null, [{
        key: 'getRetrievedEventName',
        value: function getRetrievedEventName() {
            return 'http-client.retrieved';
        }
    }, {
        key: 'request',
        value: function request(url, method, responseType, element, requestId) {
            var data = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;
            var requestHeaders = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : {};

            var request = new XMLHttpRequest();

            request.open(method, url);
            request.responseType = responseType;

            var _iteratorNormalCompletion = true;
            var _didIteratorError = false;
            var _iteratorError = undefined;

            try {
                for (var _iterator = Object.entries(requestHeaders)[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                    var _ref = _step.value;

                    var _ref2 = _slicedToArray(_ref, 2);

                    var key = _ref2[0];
                    var value = _ref2[1];

                    request.setRequestHeader(key, value);
                }
            } catch (err) {
                _didIteratorError = true;
                _iteratorError = err;
            } finally {
                try {
                    if (!_iteratorNormalCompletion && _iterator.return) {
                        _iterator.return();
                    }
                } finally {
                    if (_didIteratorError) {
                        throw _iteratorError;
                    }
                }
            }

            request.addEventListener('load', function (event) {
                var retrievedEvent = new CustomEvent(HttpClient.getRetrievedEventName(), {
                    detail: {
                        response: request.response,
                        requestId: requestId,
                        request: request
                    }
                });

                element.dispatchEvent(retrievedEvent);
            });

            if (data === null) {
                request.send();
            } else {
                request.send(data);
            }
        }
    }, {
        key: 'get',
        value: function get(url, responseType, element, requestId) {
            var requestHeaders = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : {};

            HttpClient.request(url, 'GET', responseType, element, requestId, null, requestHeaders);
        }
    }, {
        key: 'getJson',
        value: function getJson(url, element, requestId) {
            var requestHeaders = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};

            var realRequestHeaders = {
                'Accept': 'application/json'
            };

            var _iteratorNormalCompletion2 = true;
            var _didIteratorError2 = false;
            var _iteratorError2 = undefined;

            try {
                for (var _iterator2 = Object.entries(requestHeaders)[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
                    var _ref3 = _step2.value;

                    var _ref4 = _slicedToArray(_ref3, 2);

                    var key = _ref4[0];
                    var value = _ref4[1];

                    realRequestHeaders[key] = value;
                }
            } catch (err) {
                _didIteratorError2 = true;
                _iteratorError2 = err;
            } finally {
                try {
                    if (!_iteratorNormalCompletion2 && _iterator2.return) {
                        _iterator2.return();
                    }
                } finally {
                    if (_didIteratorError2) {
                        throw _iteratorError2;
                    }
                }
            }

            HttpClient.request(url, 'GET', 'json', element, requestId, null, realRequestHeaders);
        }
    }, {
        key: 'getText',
        value: function getText(url, element, requestId) {
            var requestHeaders = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};

            HttpClient.request(url, 'GET', '', element, requestId, requestHeaders);
        }
    }, {
        key: 'post',
        value: function post(url, element, requestId) {
            var data = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
            var requestHeaders = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : {};

            var realRequestHeaders = {
                'Content-type': 'application/x-www-form-urlencoded'
            };

            var _iteratorNormalCompletion3 = true;
            var _didIteratorError3 = false;
            var _iteratorError3 = undefined;

            try {
                for (var _iterator3 = Object.entries(requestHeaders)[Symbol.iterator](), _step3; !(_iteratorNormalCompletion3 = (_step3 = _iterator3.next()).done); _iteratorNormalCompletion3 = true) {
                    var _ref5 = _step3.value;

                    var _ref6 = _slicedToArray(_ref5, 2);

                    var key = _ref6[0];
                    var value = _ref6[1];

                    realRequestHeaders[key] = value;
                }
            } catch (err) {
                _didIteratorError3 = true;
                _iteratorError3 = err;
            } finally {
                try {
                    if (!_iteratorNormalCompletion3 && _iterator3.return) {
                        _iterator3.return();
                    }
                } finally {
                    if (_didIteratorError3) {
                        throw _iteratorError3;
                    }
                }
            }

            HttpClient.request(url, 'POST', '', element, requestId, data, realRequestHeaders);
        }
    }]);

    return HttpClient;
}();

module.exports = HttpClient;

/***/ }),

/***/ "./assets/js/services/listed-test-factory.js":
/*!***************************************************!*\
  !*** ./assets/js/services/listed-test-factory.js ***!
  \***************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ListedTest = __webpack_require__(/*! ../model/element/listed-test/listed-test */ "./assets/js/model/element/listed-test/listed-test.js");
var PreparingListedTest = __webpack_require__(/*! ../model/element/listed-test/preparing-listed-test */ "./assets/js/model/element/listed-test/preparing-listed-test.js");
var ProgressingListedTest = __webpack_require__(/*! ../model/element/listed-test/progressing-listed-test */ "./assets/js/model/element/listed-test/progressing-listed-test.js");
var CrawlingListedTest = __webpack_require__(/*! ../model/element/listed-test/crawling-listed-test */ "./assets/js/model/element/listed-test/crawling-listed-test.js");

var ListedTestFactory = function () {
    function ListedTestFactory() {
        _classCallCheck(this, ListedTestFactory);
    }

    _createClass(ListedTestFactory, null, [{
        key: 'createFromElement',

        /**
         * @param {Element} element
         *
         * @returns {ListedTest}
         */
        value: function createFromElement(element) {
            if (element.classList.contains('requires-results')) {
                return new PreparingListedTest(element);
            }

            var state = element.getAttribute('data-state');

            if (state === 'in-progress') {
                return new ProgressingListedTest(element);
            }

            if (state === 'crawling') {
                return new CrawlingListedTest(element);
            }

            return new ListedTest(element);
        }
    }]);

    return ListedTestFactory;
}();

module.exports = ListedTestFactory;

/***/ }),

/***/ "./assets/js/services/position-sticky-detector.js":
/*!********************************************************!*\
  !*** ./assets/js/services/position-sticky-detector.js ***!
  \********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PositionStickyDetector = function () {
    function PositionStickyDetector() {
        _classCallCheck(this, PositionStickyDetector);
    }

    _createClass(PositionStickyDetector, null, [{
        key: 'supportsPositionSticky',

        /**
         * @returns {boolean}
         */
        value: function supportsPositionSticky() {
            var element = document.createElement('a');
            var elementStyle = element.style;

            elementStyle.cssText = 'position:sticky;';

            return elementStyle.position.indexOf('sticky') !== -1;
        }
    }]);

    return PositionStickyDetector;
}();

module.exports = PositionStickyDetector;

/***/ }),

/***/ "./assets/js/services/stripe-handler.js":
/*!**********************************************!*\
  !*** ./assets/js/services/stripe-handler.js ***!
  \**********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var StripeHandler = function () {
    /**
     * @param {Stripe} stripeJs
     */
    function StripeHandler(stripeJs) {
        _classCallCheck(this, StripeHandler);

        this.stripeJs = stripeJs;
    }

    /**
     * @param {string} stripePublishableKey
     */


    _createClass(StripeHandler, [{
        key: 'setStripePublishableKey',
        value: function setStripePublishableKey(stripePublishableKey) {
            this.stripeJs.setPublishableKey(stripePublishableKey);
        }
    }, {
        key: 'getCreateCardTokenSuccessEventName',
        value: function getCreateCardTokenSuccessEventName() {
            return 'stripe-hander.create-card-token.success';
        }
    }, {
        key: 'getCreateCardTokenFailureEventName',
        value: function getCreateCardTokenFailureEventName() {
            return 'stripe-hander.create-card-token.failure';
        }
    }, {
        key: 'createCardToken',
        value: function createCardToken(data, formElement) {
            var _this = this;

            this.stripeJs.card.createToken(data, function (status, response) {
                var isErrorResponse = response.hasOwnProperty('error');

                var eventName = isErrorResponse ? _this.getCreateCardTokenFailureEventName() : _this.getCreateCardTokenSuccessEventName();

                formElement.dispatchEvent(new CustomEvent(eventName, {
                    detail: response
                }));
            });
        }
    }]);

    return StripeHandler;
}();

module.exports = StripeHandler;

/***/ }),

/***/ "./assets/js/services/test-result-retriever.js":
/*!*****************************************************!*\
  !*** ./assets/js/services/test-result-retriever.js ***!
  \*****************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpClient = __webpack_require__(/*! ./http-client */ "./assets/js/services/http-client.js");
var ProgressBar = __webpack_require__(/*! ../model/element/progress-bar */ "./assets/js/model/element/progress-bar.js");

var TestResultRetriever = function () {
    function TestResultRetriever(element) {
        _classCallCheck(this, TestResultRetriever);

        this.document = element.ownerDocument;
        this.element = element;
        this.statusUrl = element.getAttribute('data-status-url');
        this.unretrievedTaskIdsUrl = element.getAttribute('data-unretrieved-task-ids-url');
        this.retrieveTasksUrl = element.getAttribute('data-retrieve-tasks-url');
        this.summaryUrl = element.getAttribute('data-summary-url');
        this.progressBar = new ProgressBar(this.element.querySelector('.progress-bar'));
        this.summary = element.querySelector('.summary');
    }

    _createClass(TestResultRetriever, [{
        key: 'init',
        value: function init() {
            this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));

            this._retrievePreparingStatus();
        }
    }, {
        key: 'getRetrievedEventName',
        value: function getRetrievedEventName() {
            return 'test-result-retriever.retrieved';
        }
    }, {
        key: '_httpRequestRetrievedEventListener',


        /**
         * @param {CustomEvent} event
         * @private
         */
        value: function _httpRequestRetrievedEventListener(event) {
            var response = event.detail.response;
            var requestId = event.detail.requestId;

            if (requestId === 'retrievePreparingStatus') {
                var completionPercent = response.completion_percent;

                this.progressBar.setCompletionPercent(completionPercent);

                if (completionPercent >= 100) {
                    this._retrieveFinishedSummary();
                } else {
                    this._displayPreparingSummary(response);
                    this._retrieveNextRemoveTaskIdCollection();
                }
            }

            if (requestId === 'retrieveNextRemoveTaskIdCollection') {
                this._retrieveNextRemoteTaskCollection(response);
            }

            if (requestId === 'retrieveNextRemoteTaskCollection') {
                this._retrievePreparingStatus();
            }

            if (requestId === 'retrieveFinishedSummary') {
                var retrievedSummaryContainer = this.document.createElement('div');
                retrievedSummaryContainer.innerHTML = response;

                var retrievedEvent = new CustomEvent(this.getRetrievedEventName(), {
                    detail: retrievedSummaryContainer.querySelector('.listed-test')
                });

                this.element.dispatchEvent(retrievedEvent);
            }
        }
    }, {
        key: '_retrievePreparingStatus',
        value: function _retrievePreparingStatus() {
            HttpClient.getJson(this.statusUrl, this.element, 'retrievePreparingStatus');
        }
    }, {
        key: '_retrieveNextRemoveTaskIdCollection',
        value: function _retrieveNextRemoveTaskIdCollection() {
            HttpClient.getJson(this.unretrievedTaskIdsUrl, this.element, 'retrieveNextRemoveTaskIdCollection');
        }
    }, {
        key: '_retrieveNextRemoteTaskCollection',
        value: function _retrieveNextRemoteTaskCollection(remoteTaskIds) {
            HttpClient.post(this.retrieveTasksUrl, this.element, 'retrieveNextRemoteTaskCollection', 'remoteTaskIds=' + remoteTaskIds.join(','));
        }
    }, {
        key: '_retrieveFinishedSummary',
        value: function _retrieveFinishedSummary() {
            HttpClient.getText(this.summaryUrl, this.element, 'retrieveFinishedSummary');
        }
    }, {
        key: '_createPreparingSummary',
        value: function _createPreparingSummary(statusData) {
            var localTaskCount = statusData.local_task_count;
            var remoteTaskCount = statusData.remote_task_count;

            if (localTaskCount === undefined && remoteTaskCount === undefined) {
                return 'Preparing results &hellip;';
            }

            return 'Preparing &hellip; collected <strong class="local-task-count">' + localTaskCount + '</strong> results of <strong class="remote-task-count">' + remoteTaskCount + '</strong>';
        }
    }, {
        key: '_displayPreparingSummary',
        value: function _displayPreparingSummary(statusData) {
            this.element.querySelector('.preparing .summary').innerHTML = this._createPreparingSummary(statusData);
        }
    }]);

    return TestResultRetriever;
}();

module.exports = TestResultRetriever;

/***/ }),

/***/ "./assets/js/task-results/filterable-issue-list.js":
/*!*********************************************************!*\
  !*** ./assets/js/task-results/filterable-issue-list.js ***!
  \*********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var IssueList = __webpack_require__(/*! ./issue-list */ "./assets/js/task-results/issue-list.js");
var SparkMD5 = __webpack_require__(/*! spark-md5 */ "./node_modules/spark-md5/spark-md5.js");

var FilterableIssueList = function (_IssueList) {
    _inherits(FilterableIssueList, _IssueList);

    /**
     * @param {Element} element
     * @param {string} filterSelector
     */
    function FilterableIssueList(element, filterSelector) {
        _classCallCheck(this, FilterableIssueList);

        var _this = _possibleConstructorReturn(this, (FilterableIssueList.__proto__ || Object.getPrototypeOf(FilterableIssueList)).call(this, element));

        _this.filterSelector = filterSelector;
        _this.hashIdPrefix = 'hash-';
        return _this;
    }

    _createClass(FilterableIssueList, [{
        key: 'addHashIdAttributeToIssues',
        value: function addHashIdAttributeToIssues() {
            var _this2 = this;

            [].forEach.call(this.issues, function (issueElement) {
                var errorMessage = issueElement.querySelector(_this2.filterSelector).textContent.trim();
                var errorMessageHash = SparkMD5.hash(errorMessage);
                var id = _this2._generateUniqueId(_this2.hashIdPrefix + errorMessageHash);

                issueElement.setAttribute('id', id);
            });
        }
    }, {
        key: 'filter',


        /**
         * @param {string} filter
         */
        value: function filter(_filter) {
            _filter = this.hashIdPrefix + _filter;

            if (!this.element.ownerDocument.getElementById(_filter)) {
                return;
            }

            [].forEach.call(this.element.querySelectorAll('.issue:not([id^=' + _filter + '])'), function (issueElement) {
                issueElement.parentElement.removeChild(issueElement);
            });
        }
    }, {
        key: 'getFirstMessage',


        /**
         * @returns {string | null}
         */
        value: function getFirstMessage() {
            var firstIssue = this.getFirst();
            return firstIssue ? firstIssue.querySelector(this.filterSelector).innerText : null;
        }

        /**
         * @param {string} seed
         * @returns {string}
         * @private
         */

    }, {
        key: '_generateUniqueId',
        value: function _generateUniqueId(seed) {
            var originalSeed = seed;
            var seedSuffix = 1;

            while (this.element.ownerDocument.getElementById(seed)) {
                seed = originalSeed + '-' + seedSuffix;
                seedSuffix++;
            }

            return seed;
        }
    }]);

    return FilterableIssueList;
}(IssueList);

module.exports = FilterableIssueList;

/***/ }),

/***/ "./assets/js/task-results/fix-list.js":
/*!********************************************!*\
  !*** ./assets/js/task-results/fix-list.js ***!
  \********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var IssueList = __webpack_require__(/*! ./issue-list */ "./assets/js/task-results/issue-list.js");

var FixList = function (_IssueList) {
    _inherits(FixList, _IssueList);

    function FixList() {
        _classCallCheck(this, FixList);

        return _possibleConstructorReturn(this, (FixList.__proto__ || Object.getPrototypeOf(FixList)).apply(this, arguments));
    }

    _createClass(FixList, [{
        key: 'filterTo',
        value: function filterTo(fixHref) {
            [].forEach.call(this.issues, function (issueElement) {
                if (issueElement.querySelector('a').getAttribute('href') !== fixHref) {
                    issueElement.parentElement.removeChild(issueElement);
                }
            });
        }
    }]);

    return FixList;
}(IssueList);

module.exports = FixList;

/***/ }),

/***/ "./assets/js/task-results/issue-content.js":
/*!*************************************************!*\
  !*** ./assets/js/task-results/issue-content.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var IssueSection = __webpack_require__(/*! ../task-results/issue-section */ "./assets/js/task-results/issue-section.js");

var IssueContent = function () {
    /**
     * @param {Element} element
     */
    function IssueContent(element) {
        var _this = this;

        _classCallCheck(this, IssueContent);

        this.element = element;

        /**
         * @type {IssueSection[]}
         */
        this.issueSections = [];

        [].forEach.call(this.element.querySelectorAll('.issue-list'), function (issueSectionElement) {
            _this.issueSections.push(new IssueSection(issueSectionElement));
        });
    }

    _createClass(IssueContent, [{
        key: 'init',
        value: function init() {
            this.issueSections.forEach(function (issueSection) {
                issueSection.init();
            });

            this.issueSections.forEach(function (issueSection) {
                if (issueSection.isFilterable()) {
                    issueSection.filter();
                }
            });
        }
    }, {
        key: 'getIssueSection',


        /**
         * @param {string} type
         * @returns {IssueSection}
         */
        value: function getIssueSection(type) {
            var issueSection = null;

            this.issueSections.forEach(function (currentIssueSection) {
                if (currentIssueSection.issueType === type) {
                    issueSection = currentIssueSection;
                }
            });

            return issueSection;
        }
    }, {
        key: 'getFilteredIssueMessage',


        /**
         * @returns {string}
         */
        value: function getFilteredIssueMessage() {
            var filteredIssueMessage = '';

            this.issueSections.forEach(function (filteredIssueSection) {
                if (filteredIssueSection.isFiltered()) {
                    filteredIssueMessage = filteredIssueSection.getFirstIssueMessage();
                }
            });

            return filteredIssueMessage;
        }

        /**
         * @returns {Element}
         */

    }, {
        key: 'getFirstFilteredIssue',
        value: function getFirstFilteredIssue() {
            var firstFilteredIssue = null;

            this.issueSections.forEach(function (filteredIssueSection) {
                if (filteredIssueSection.isFiltered()) {
                    firstFilteredIssue = filteredIssueSection.getFirstIssue();
                }
            });

            return firstFilteredIssue;
        }
    }]);

    return IssueContent;
}();

module.exports = IssueContent;

/***/ }),

/***/ "./assets/js/task-results/issue-list.js":
/*!**********************************************!*\
  !*** ./assets/js/task-results/issue-list.js ***!
  \**********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var IssueList = function () {
    /**
     * @param {Element} element
     */
    function IssueList(element) {
        _classCallCheck(this, IssueList);

        this.element = element;
        this.issues = this.element.querySelectorAll('.issue');
    }

    /**
     * @returns {number}
     */


    _createClass(IssueList, [{
        key: 'count',
        value: function count() {
            return this.element.querySelectorAll('.issue').length;
        }

        /**
         * @returns {Element}
         */

    }, {
        key: 'getFirst',
        value: function getFirst() {
            return this.element.querySelector('.issue');
        }
    }]);

    return IssueList;
}();

module.exports = IssueList;

/***/ }),

/***/ "./assets/js/task-results/issue-section.js":
/*!*************************************************!*\
  !*** ./assets/js/task-results/issue-section.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var IssueList = __webpack_require__(/*! ./issue-list */ "./assets/js/task-results/issue-list.js");
var FixList = __webpack_require__(/*! ./fix-list */ "./assets/js/task-results/fix-list.js");
var FilterableIssueList = __webpack_require__(/*! ./filterable-issue-list */ "./assets/js/task-results/filterable-issue-list.js");

var IssueSection = function () {
    /**
     * @param {Element} element
     */
    function IssueSection(element) {
        var _this = this;

        _classCallCheck(this, IssueSection);

        this.element = element;
        this.issueType = element.getAttribute('data-issue-type');
        this.issueCountElement = element.querySelector('.issue-count');
        this.issueLists = [];

        [].forEach.call(element.querySelectorAll('.issues'), function (issuesElement) {
            var issueList = null;

            if (_this.element.hasAttribute('data-filter')) {
                issueList = new FilterableIssueList(issuesElement, element.getAttribute('data-filter-selector'));
            } else {
                if (_this.issueType === 'fix') {
                    issueList = new FixList(issuesElement);
                } else {
                    issueList = new IssueList(issuesElement);
                }
            }

            _this.issueLists.push(issueList);
        });
    }

    /**
     * @returns {string}
     */


    _createClass(IssueSection, [{
        key: 'init',
        value: function init() {
            if (this.isFilterable()) {
                var filter = window.location.hash.replace('#', '').trim();

                if (filter) {
                    this.issueLists.forEach(function (issueList) {
                        issueList.addHashIdAttributeToIssues();
                    });
                }
            }
        }
    }, {
        key: 'filter',
        value: function filter() {
            var filter = window.location.hash.replace('#', '').trim();

            if (filter) {
                var filteredIssueCount = 0;

                this.issueLists.forEach(function (issueList) {
                    issueList.filter(window.location.hash.replace('#', ''));
                    filteredIssueCount += issueList.count();
                });

                var issueCount = parseInt(this.issueCountElement.innerText.trim(), 10);
                if (issueCount !== filteredIssueCount) {
                    this.element.classList.add('filtered');
                    this.renderIssueCount(filteredIssueCount);
                    this.element.dispatchEvent(new CustomEvent(IssueSection.getIssueCountChangedEventName(), {
                        detail: {
                            'issue-type': this.issueType,
                            count: filteredIssueCount
                        }
                    }));
                } else {
                    this.element.classList.remove('filtered');
                }
            }
        }
    }, {
        key: 'renderIssueCount',


        /**
         * @param {number} count
         */
        value: function renderIssueCount(count) {
            this.issueCountElement.innerText = count;
        }
    }, {
        key: 'isFilterable',


        /**
         * @returns {boolean}
         */
        value: function isFilterable() {
            return this.element.hasAttribute('data-filter');
        }

        /**
         * @returns {boolean}
         */

    }, {
        key: 'isFiltered',
        value: function isFiltered() {
            return this.element.classList.contains('filtered');
        }
    }, {
        key: 'getFirstIssueMessage',


        /**
         * @returns {string}
         */
        value: function getFirstIssueMessage() {
            var firstIssueMessage = null;

            this.issueLists.forEach(function (issueList) {
                var issueListFirstMessage = issueList.getFirstMessage();
                if (firstIssueMessage === null && issueListFirstMessage !== null) {
                    firstIssueMessage = issueListFirstMessage;
                }
            });

            return firstIssueMessage;
        }
    }, {
        key: 'getFirstIssue',


        /**
         * @returns {Element}
         */
        value: function getFirstIssue() {
            var firstIssue = null;

            this.issueLists.forEach(function (issueList) {
                var issueListFirstIssue = issueList.getFirst();
                if (firstIssue === null && issueListFirstIssue !== null) {
                    firstIssue = issueListFirstIssue;
                }
            });

            return firstIssue;
        }
    }], [{
        key: 'getIssueCountChangedEventName',
        value: function getIssueCountChangedEventName() {
            return 'issues-section.issue-count.changed';
        }
    }]);

    return IssueSection;
}();

module.exports = IssueSection;

/***/ }),

/***/ "./assets/js/task-results/summary-stats.js":
/*!*************************************************!*\
  !*** ./assets/js/task-results/summary-stats.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SummaryStatsLabel = __webpack_require__(/*! ../model/element/summary-stats-label */ "./assets/js/model/element/summary-stats-label.js");

var SummaryStats = function () {
    /**
     * @param {Element} element
     */
    function SummaryStats(element) {
        var _this = this;

        _classCallCheck(this, SummaryStats);

        this.element = element;
        this.labels = {};

        [].forEach.call(this.element.querySelectorAll('.label'), function (labelElement) {
            var label = new SummaryStatsLabel(labelElement);
            _this.labels[label.getIssueType()] = label;
        });
    }

    _createClass(SummaryStats, [{
        key: 'init',
        value: function init() {
            var _this2 = this;

            Object.keys(this.labels).forEach(function (type) {
                _this2.labels[type].init();
            });
        }
    }, {
        key: 'setIssueCount',


        /**
         * @param {string} type
         * @param {number} count
         */
        value: function setIssueCount(type, count) {
            this.labels[type].setCount(count);
        }
    }]);

    return SummaryStats;
}();

module.exports = SummaryStats;

/***/ }),

/***/ "./assets/js/test-history/modal.js":
/*!*****************************************!*\
  !*** ./assets/js/test-history/modal.js ***!
  \*****************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global Awesomplete */

var formFieldFocuser = __webpack_require__(/*! ../form-field-focuser */ "./assets/js/form-field-focuser.js");
__webpack_require__(/*! awesomplete */ "./node_modules/awesomplete/awesomplete.js");

var Modal = function () {
    /**
     * @param {HTMLElement} element
     */
    function Modal(element) {
        _classCallCheck(this, Modal);

        this.element = element;
        this.applyButton = element.querySelector('#apply-filter-button');
        this.clearButton = element.querySelector('#clear-filter-button');
        this.closeButton = element.querySelector('.close');
        this.input = element.querySelector('input[name=filter]');
        this.filter = this.input.value.trim();
        this.previousFilter = this.input.value.trim();
        this.applyFilter = false;
        this.awesomeplete = new Awesomplete(this.input);
        this.suggestions = [];
        this.filterChangedEventName = 'test-history.modal.filter.changed';

        this.init();
    }

    /**
     * @param {String[]} suggestions
     */


    _createClass(Modal, [{
        key: 'setSuggestions',
        value: function setSuggestions(suggestions) {
            this.suggestions = suggestions;
            this.awesomeplete.list = this.suggestions;
        }
    }, {
        key: 'init',
        value: function init() {
            var shownEventListener = function shownEventListener() {
                formFieldFocuser(this.input);
            };

            var hideEventListener = function hideEventListener() {
                var WILDCARD = '*';
                var filterIsEmpty = this.filter === '';
                var suggestionsIncludesFilter = this.suggestions.includes(this.filter);
                var filterIsWildcardPrefixed = this.filter.charAt(0) === WILDCARD;
                var filterIsWildcardSuffixed = this.filter.slice(-1) === WILDCARD;

                if (!filterIsEmpty && !suggestionsIncludesFilter) {
                    if (!filterIsWildcardPrefixed) {
                        this.filter = WILDCARD + this.filter;
                    }

                    if (!filterIsWildcardSuffixed) {
                        this.filter += WILDCARD;
                    }

                    this.input.value = this.filter;
                }

                this.applyFilter = this.filter !== this.previousFilter;
            };

            var hiddenEventListener = function hiddenEventListener() {
                if (!this.applyFilter) {
                    return;
                }

                this.element.dispatchEvent(new CustomEvent(this.filterChangedEventName, {
                    detail: this.filter
                }));
            };

            var applyButtonClickEventListener = function applyButtonClickEventListener() {
                this.filter = this.input.value.trim();
            };

            var clearButtonClickEventListener = function clearButtonClickEventListener() {
                this.input.value = '';
                this.filter = '';
            };

            var closeButtonClickEventListener = function closeButtonClickEventListener() {
                this.applyFilter = false;
            };

            this.element.addEventListener('shown.bs.modal', shownEventListener.bind(this));
            this.element.addEventListener('hide.bs.modal', hideEventListener.bind(this));
            this.element.addEventListener('hidden.bs.modal', hiddenEventListener.bind(this));
            this.applyButton.addEventListener('click', applyButtonClickEventListener.bind(this));
            this.clearButton.addEventListener('click', clearButtonClickEventListener.bind(this));
            this.closeButton.addEventListener('click', closeButtonClickEventListener.bind(this));
        }
    }]);

    return Modal;
}();

module.exports = Modal;

/***/ }),

/***/ "./assets/js/test-history/suggestions.js":
/*!***********************************************!*\
  !*** ./assets/js/test-history/suggestions.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Suggestions = function () {
    /**
     * @param {Document} document
     * @param {String} sourceUrl
     */
    function Suggestions(document, sourceUrl) {
        _classCallCheck(this, Suggestions);

        this.document = document;
        this.sourceUrl = sourceUrl;
        this.loadedEventName = 'test-history.suggestions.loaded';
    }

    _createClass(Suggestions, [{
        key: 'retrieve',
        value: function retrieve() {
            var request = new XMLHttpRequest();
            var suggestions = null;

            request.open('GET', this.sourceUrl, false);

            var requestOnloadHandler = function requestOnloadHandler() {
                if (request.status >= 200 && request.status < 400) {
                    suggestions = JSON.parse(request.responseText);

                    this.document.dispatchEvent(new CustomEvent(this.loadedEventName, {
                        detail: suggestions
                    }));
                }
            };

            request.onload = requestOnloadHandler.bind(this);

            request.send();
        }
    }]);

    return Suggestions;
}();

module.exports = Suggestions;

/***/ }),

/***/ "./assets/js/test-progress/summary.js":
/*!********************************************!*\
  !*** ./assets/js/test-progress/summary.js ***!
  \********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var FormButton = __webpack_require__(/*! ../model/element/form-button */ "./assets/js/model/element/form-button.js");
var ProgressBar = __webpack_require__(/*! ../model/element/progress-bar */ "./assets/js/model/element/progress-bar.js");
var TaskQueues = __webpack_require__(/*! ../model/element/task-queues */ "./assets/js/model/element/task-queues.js");

var Summary = function () {
    /**
     * @param {Element} element
     */
    function Summary(element) {
        _classCallCheck(this, Summary);

        this.element = element;
        this.cancelAction = new FormButton(element.querySelector('.cancel-action'));
        this.cancelCrawlAction = new FormButton(element.querySelector('.cancel-crawl-action'));
        this.progressBar = new ProgressBar(element.querySelector('.progress-bar'));
        this.stateLabel = element.querySelector('.js-state-label');
        this.taskQueues = new TaskQueues(element.querySelector('.task-queues'));
    }

    _createClass(Summary, [{
        key: 'init',
        value: function init() {
            this._addEventListeners();
        }
    }, {
        key: '_addEventListeners',
        value: function _addEventListeners() {
            this.cancelAction.element.addEventListener('click', this._cancelActionClickEventListener.bind(this));
            this.cancelCrawlAction.element.addEventListener('click', this._cancelCrawlActionClickEventListener.bind(this));
        }
    }, {
        key: '_cancelActionClickEventListener',
        value: function _cancelActionClickEventListener() {
            this.cancelAction.markAsBusy();
            this.cancelAction.deEmphasize();
        }
    }, {
        key: '_cancelCrawlActionClickEventListener',
        value: function _cancelCrawlActionClickEventListener() {
            this.cancelCrawlAction.markAsBusy();
            this.cancelCrawlAction.deEmphasize();
        }
    }, {
        key: 'render',


        /**
         * @param {object} summaryData
         */
        value: function render(summaryData) {
            var test = summaryData.test;

            this.progressBar.setCompletionPercent(test.completion_percent);
            this.progressBar.setStyle(test.state === 'crawling' ? 'warning' : 'default');
            this.stateLabel.innerText = summaryData.state_label;
            this.taskQueues.render(test.task_count, test.task_count_by_state);

            if (test.amendments.length > 0) {
                this.element.dispatchEvent(new CustomEvent(Summary.getRenderAmmendmentEventName(), {
                    detail: test.amendments[0]
                }));
            }
        }
    }], [{
        key: 'getRenderAmmendmentEventName',


        /**
         * @returns {string}
         */
        value: function getRenderAmmendmentEventName() {
            return 'test-progress.summary.render-ammendment';
        }
    }]);

    return Summary;
}();

module.exports = Summary;

/***/ }),

/***/ "./assets/js/test-progress/task-id-list.js":
/*!*************************************************!*\
  !*** ./assets/js/test-progress/task-id-list.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var TaskIdList = function () {
    /**
     * @param {number[]} taskIds
     * @param {number} pageLength
     */
    function TaskIdList(taskIds, pageLength) {
        _classCallCheck(this, TaskIdList);

        this.taskIds = taskIds;
        this.pageLength = pageLength;
    }

    /**
     * @param {number} pageIndex
     *
     * @returns {number[]}
     */


    _createClass(TaskIdList, [{
        key: "getForPage",
        value: function getForPage(pageIndex) {
            var pageNumber = pageIndex + 1;

            return this.taskIds.slice(pageIndex * this.pageLength, pageNumber * this.pageLength);
        }
    }]);

    return TaskIdList;
}();

module.exports = TaskIdList;

/***/ }),

/***/ "./assets/js/test-progress/task-list-paginator.js":
/*!********************************************************!*\
  !*** ./assets/js/test-progress/task-list-paginator.js ***!
  \********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var TaskListPagination = function () {
    function TaskListPagination(pageLength, taskCount) {
        _classCallCheck(this, TaskListPagination);

        this.pageLength = pageLength;
        this.taskCount = taskCount;
        this.pageCount = Math.ceil(taskCount / pageLength);
        this.element = null;
    }

    _createClass(TaskListPagination, [{
        key: 'init',
        value: function init(element) {
            var _this = this;

            this.element = element;
            this.pageActions = element.querySelectorAll('a');
            this.previousAction = element.querySelector('[data-role=previous]');
            this.nextAction = element.querySelector('[data-role=next]');

            [].forEach.call(this.pageActions, function (pageActions) {
                pageActions.addEventListener('click', function (event) {
                    event.preventDefault();

                    var actionContainer = pageActions.parentNode;
                    if (!actionContainer.classList.contains('active')) {
                        var role = pageActions.getAttribute('data-role');

                        if (role === 'showPage') {
                            _this.element.dispatchEvent(new CustomEvent(TaskListPagination.getSelectPageEventName(), {
                                detail: parseInt(pageActions.getAttribute('data-page-index'), 10)
                            }));
                        }

                        if (role === 'previous') {
                            _this.element.dispatchEvent(new CustomEvent(TaskListPagination.getSelectPreviousPageEventName()));
                        }

                        if (role === 'next') {
                            _this.element.dispatchEvent(new CustomEvent(TaskListPagination.getSelectNextPageEventName()));
                        }
                    }
                });
            });
        }
    }, {
        key: 'createMarkup',
        value: function createMarkup() {
            var markup = '<ul class="pagination">';

            markup += '<li class="is-xs previous-next previous disabled hidden-lg hidden-md hidden-sm"><a href="#" data-role="previous"><i class="fa fa-caret-left"></i> Previous</a></li>';
            markup += '<li class="hidden-lg hidden-md hidden-sm disabled"><span>Page <strong class="page-number">1</strong> of <strong>' + this.pageCount + '</strong></span></li>';

            for (var pageIndex = 0; pageIndex < this.pageCount; pageIndex++) {
                var startIndex = pageIndex * this.pageLength + 1;
                var endIndex = Math.min(startIndex + this.pageLength - 1, this.taskCount);

                markup += '<li class="is-not-xs hidden-xs ' + (pageIndex === 0 ? 'active' : '') + '"><a href="#" data-page-index="' + pageIndex + '" data-role="showPage">' + startIndex + ' … ' + endIndex + '</a></li>';
            }

            markup += '<li class="next previous-next hidden-lg hidden-md hidden-sm"><a href="#" data-role="next">Next <i class="fa fa-caret-right"></i></a></li>';
            markup += '</ul>';

            return markup;
        }
    }, {
        key: 'isRequired',


        /**
         * @returns {boolean}
         */
        value: function isRequired() {
            return this.taskCount > this.pageLength;
        }
    }, {
        key: 'isRendered',


        /**
         * @returns {boolean}
         */
        value: function isRendered() {
            return this.element !== null;
        }
    }, {
        key: 'selectPage',


        /**
         * @param {number} pageIndex
         */
        value: function selectPage(pageIndex) {
            [].forEach.call(this.pageActions, function (pageAction) {
                var isActive = parseInt(pageAction.getAttribute('data-page-index'), 10) === pageIndex;
                var actionContainer = pageAction.parentNode;

                if (isActive) {
                    actionContainer.classList.add('active');
                } else {
                    actionContainer.classList.remove('active');
                }
            });

            this.element.querySelector('.page-number').innerText = pageIndex + 1;
            this.previousAction.parentElement.classList.remove('disabled');
            this.nextAction.parentElement.classList.remove('disabled');

            if (pageIndex === 0) {
                this.previousAction.parentElement.classList.add('disabled');
            } else if (pageIndex === this.pageCount - 1) {
                this.nextAction.parentElement.classList.add('disabled');
            }
        }
    }], [{
        key: 'getSelector',
        value: function getSelector() {
            return '.pagination';
        }
    }, {
        key: 'getSelectPageEventName',


        /**
         * @returns {string}
         */
        value: function getSelectPageEventName() {
            return 'task-list-pagination.select-page';
        }
    }, {
        key: 'getSelectPreviousPageEventName',


        /**
         * @returns {string}
         */
        value: function getSelectPreviousPageEventName() {
            return 'task-list-pagination.select-previous-page';
        }

        /**
         * @returns {string}
         */

    }, {
        key: 'getSelectNextPageEventName',
        value: function getSelectNextPageEventName() {
            return 'task-list-pagination.select-next-page';
        }
    }]);

    return TaskListPagination;
}();

module.exports = TaskListPagination;

/***/ }),

/***/ "./assets/js/test-progress/task-list.js":
/*!**********************************************!*\
  !*** ./assets/js/test-progress/task-list.js ***!
  \**********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var TaskListModel = __webpack_require__(/*! ../model/element/task-list */ "./assets/js/model/element/task-list.js");
var Icon = __webpack_require__(/*! ../model/element/icon */ "./assets/js/model/element/icon.js");
var TaskIdList = __webpack_require__(/*! ./task-id-list */ "./assets/js/test-progress/task-id-list.js");
var HttpClient = __webpack_require__(/*! ../services/http-client */ "./assets/js/services/http-client.js");

var TaskList = function () {
    /**
     * @param {Element} element
     * @param {number} pageLength
     */
    function TaskList(element, pageLength) {
        _classCallCheck(this, TaskList);

        this.element = element;
        this.currentPageIndex = 0;
        this.pageLength = pageLength;
        this.taskIdList = null;
        this.isInitializing = false;
        this.taskListModels = {};
        this.heading = element.querySelector('h2');

        /**
         * @type {Icon}
         */
        this.busyIcon = this._createBusyIcon();
        this.heading.appendChild(this.busyIcon.element);
    }

    _createClass(TaskList, [{
        key: 'init',
        value: function init() {
            this.isInitializing = true;
            this.element.classList.remove('hidden');
            this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
            this._requestTaskIds();
        }
    }, {
        key: 'setCurrentPageIndex',


        /**
         * @param {number} index
         */
        value: function setCurrentPageIndex(index) {
            this.currentPageIndex = index;
        }

        /**
         * @param {Element} paginationElement
         */

    }, {
        key: 'setPaginationElement',
        value: function setPaginationElement(paginationElement) {
            this.heading.insertAdjacentElement('afterend', paginationElement);
        }

        /**
         * @returns {string}
         */

    }, {
        key: '_httpRequestRetrievedEventListener',
        value: function _httpRequestRetrievedEventListener(event) {
            var requestId = event.detail.requestId;
            var response = event.detail.response;

            if (requestId === 'requestTaskIds') {
                this.taskIdList = new TaskIdList(response, this.pageLength);
                this.isInitializing = false;
                this.element.dispatchEvent(new CustomEvent(TaskList.getInitializedEventName()));
            }

            if (requestId === 'retrieveTaskPage') {
                var taskListModel = new TaskListModel(this._createTaskListElementFromHtml(response));
                var pageIndex = taskListModel.getPageIndex();

                this.taskListModels[pageIndex] = taskListModel;
                this.render(pageIndex);
                this._retrieveTaskSetWithDelay(pageIndex, taskListModel.getTasksByStates(['in-progress', 'queued-for-assignment', 'queued']).slice(0, 10));
            }

            if (requestId === 'retrieveTaskSet') {
                var updatedTaskListModel = new TaskListModel(this._createTaskListElementFromHtml(response));
                var _pageIndex = updatedTaskListModel.getPageIndex();
                var _taskListModel = this.taskListModels[_pageIndex];

                _taskListModel.updateFromTaskList(updatedTaskListModel);
                this._retrieveTaskSetWithDelay(_pageIndex, _taskListModel.getTasksByStates(['in-progress', 'queued-for-assignment', 'queued']).slice(0, 10));
            }
        }
    }, {
        key: '_requestTaskIds',
        value: function _requestTaskIds() {
            HttpClient.getJson(this.element.getAttribute('data-task-ids-url'), this.element, 'requestTaskIds');
        }
    }, {
        key: 'render',


        /**
         * @param {number} pageIndex
         */
        value: function render(pageIndex) {
            this.busyIcon.element.classList.remove('hidden');

            var hasTaskListElementForPage = Object.keys(this.taskListModels).includes(pageIndex.toString(10));
            if (!hasTaskListElementForPage) {
                this._retrieveTaskPage(pageIndex);

                return;
            }

            var taskListElement = this.taskListModels[pageIndex];

            if (pageIndex === this.currentPageIndex) {
                var renderedTaskListElement = new TaskListModel(this.element.querySelector('.task-list'));

                if (renderedTaskListElement.hasPageIndex()) {
                    var currentPageListElement = this.element.querySelector('.task-list');
                    var selectedPageListElement = this.taskListModels[this.currentPageIndex].element;

                    currentPageListElement.parentNode.replaceChild(selectedPageListElement, currentPageListElement);
                } else {
                    this.element.appendChild(taskListElement.element);
                }
            }

            this.busyIcon.element.classList.add('hidden');
        }
    }, {
        key: '_retrieveTaskPage',


        /**
         * @param {number} pageIndex
         * @private
         */
        value: function _retrieveTaskPage(pageIndex) {
            var taskIds = this.taskIdList.getForPage(pageIndex);
            var postData = 'pageIndex=' + pageIndex + '&taskIds[]=' + taskIds.join('&taskIds[]=');

            HttpClient.post(this.element.getAttribute('data-tasklist-url'), this.element, 'retrieveTaskPage', postData);
        }
    }, {
        key: '_retrieveTaskSetWithDelay',


        /**
         * @param {number} pageIndex
         * @param {Task[]} tasks
         * @private
         */
        value: function _retrieveTaskSetWithDelay(pageIndex, tasks) {
            var _this = this;

            window.setTimeout(function () {
                _this._retrieveTaskSet(pageIndex, tasks);
            }, 1000);
        }
    }, {
        key: '_retrieveTaskSet',


        /**
         * @param {number} pageIndex
         * @param {Task[]} tasks
         * @private
         */
        value: function _retrieveTaskSet(pageIndex, tasks) {
            if (this.currentPageIndex === pageIndex && tasks.length) {
                var taskIds = [];

                tasks.forEach(function (task) {
                    taskIds.push(task.getId());
                });

                var postData = 'pageIndex=' + pageIndex + '&taskIds[]=' + taskIds.join('&taskIds[]=');

                HttpClient.post(this.element.getAttribute('data-tasklist-url'), this.element, 'retrieveTaskSet', postData);
            }
        }
    }, {
        key: '_createTaskListElementFromHtml',


        /**
         * @param {string} html
         * @returns {Element}
         * @private
         */
        value: function _createTaskListElementFromHtml(html) {
            var container = this.element.ownerDocument.createElement('div');
            container.innerHTML = html;

            return container.querySelector('.task-list');
        }
    }, {
        key: '_createBusyIcon',


        /**
         * @returns {Icon}
         * @private
         */
        value: function _createBusyIcon() {
            var container = this.element.ownerDocument.createElement('div');
            container.innerHTML = '<i class="fa"></i>';

            var icon = new Icon(container.querySelector('.fa'));
            icon.setBusy();

            return icon;
        }
    }], [{
        key: 'getInitializedEventName',
        value: function getInitializedEventName() {
            return 'task-list.initialized';
        }
    }]);

    return TaskList;
}();

module.exports = TaskList;

/***/ }),

/***/ "./assets/js/test-results-by-task-type/by-error-list.js":
/*!**************************************************************!*\
  !*** ./assets/js/test-results-by-task-type/by-error-list.js ***!
  \**************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ByPageList = __webpack_require__(/*! ../test-results-by-task-type/by-page-list */ "./assets/js/test-results-by-task-type/by-page-list.js");

var ByErrorList = function () {
    /**
     * @param {Element} element
     */
    function ByErrorList(element) {
        var _this = this;

        _classCallCheck(this, ByErrorList);

        this.element = element;
        this.byPageLists = [];

        [].forEach.call(document.querySelectorAll('.by-page-container'), function (byPageContainerElement) {
            if (byPageContainerElement.querySelectorAll('.js-sortable-item').length > 1) {
                _this.byPageLists.push(new ByPageList(byPageContainerElement));
            }
        });
    }

    _createClass(ByErrorList, [{
        key: 'init',
        value: function init() {
            this.byPageLists.forEach(function (byPageList) {
                byPageList.init();
            });
        }
    }]);

    return ByErrorList;
}();

module.exports = ByErrorList;

/***/ }),

/***/ "./assets/js/test-results-by-task-type/by-page-list.js":
/*!*************************************************************!*\
  !*** ./assets/js/test-results-by-task-type/by-page-list.js ***!
  \*************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Sort = __webpack_require__(/*! ./sort */ "./assets/js/test-results-by-task-type/sort.js");

var ByPageList = function () {
    /**
     * @param {Element} element
     */
    function ByPageList(element) {
        _classCallCheck(this, ByPageList);

        this.element = element;
        this.sort = new Sort(element.querySelector('.sort'), element.querySelectorAll('.js-sortable-item'));
    }

    _createClass(ByPageList, [{
        key: 'init',
        value: function init() {
            this.sort.init();
        }
    }]);

    return ByPageList;
}();

module.exports = ByPageList;

/***/ }),

/***/ "./assets/js/test-results-by-task-type/sort.js":
/*!*****************************************************!*\
  !*** ./assets/js/test-results-by-task-type/sort.js ***!
  \*****************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SortControl = __webpack_require__(/*! ../model/element/sort-control */ "./assets/js/model/element/sort-control.js");
var SortableItem = __webpack_require__(/*! ../model/element/sortable-item */ "./assets/js/model/element/sortable-item.js");
var SortableItemList = __webpack_require__(/*! ../model/sortable-item-list */ "./assets/js/model/sortable-item-list.js");
var SortControlCollection = __webpack_require__(/*! ../model/sort-control-collection */ "./assets/js/model/sort-control-collection.js");

var Sort = function () {
    /**
     * @param {Element} element
     * @param {NodeList} sortableItemsNodeList
     */
    function Sort(element, sortableItemsNodeList) {
        _classCallCheck(this, Sort);

        this.element = element;
        this.sortControlCollection = this._createSortableControlCollection();
        this.sortableItemsNodeList = sortableItemsNodeList;
        this.sortableItemsList = this._createSortableItemList();
    }

    _createClass(Sort, [{
        key: 'init',
        value: function init() {
            var _this = this;

            this.element.classList.remove('invisible');
            this.sortControlCollection.controls.forEach(function (control) {
                control.init();
                control.element.addEventListener(SortControl.getSortRequestedEventName(), _this._sortControlClickEventListener.bind(_this));
            });
        }
    }, {
        key: '_createSortableItemList',


        /**
         * @returns {SortableItemList}
         * @private
         */
        value: function _createSortableItemList() {
            var sortableItems = [];

            [].forEach.call(this.sortableItemsNodeList, function (sortableItemElement) {
                sortableItems.push(new SortableItem(sortableItemElement));
            });

            return new SortableItemList(sortableItems);
        }

        /**
         * @returns {SortControlCollection}
         * @private
         */

    }, {
        key: '_createSortableControlCollection',
        value: function _createSortableControlCollection() {
            var controls = [];

            [].forEach.call(this.element.querySelectorAll('.sort-control'), function (sortControlElement) {
                controls.push(new SortControl(sortControlElement));
            });

            return new SortControlCollection(controls);
        }
    }, {
        key: '_sortControlClickEventListener',


        /**
         * @param {CustomEvent} event
         * @private
         */
        value: function _sortControlClickEventListener(event) {
            var parent = this.sortableItemsNodeList.item(0).parentElement;

            [].forEach.call(this.sortableItemsNodeList, function (sortableItemElement) {
                sortableItemElement.parentElement.removeChild(sortableItemElement);
            });

            var sortedItems = this.sortableItemsList.sort(event.detail.keys);

            sortedItems.forEach(function (sortableItem) {
                parent.insertAdjacentElement('beforeend', sortableItem.element);
            });

            this.sortControlCollection.setSorted(event.target);
        }
    }]);

    return Sort;
}();

module.exports = Sort;

/***/ }),

/***/ "./assets/js/unavailable-task-type-modal-launcher.js":
/*!***********************************************************!*\
  !*** ./assets/js/unavailable-task-type-modal-launcher.js ***!
  \***********************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var Modal = __webpack_require__(/*! bootstrap.native */ "./node_modules/bootstrap.native/dist/bootstrap-native.js").Modal;

/**
 * @param {NodeList} taskTypeContainers
 */
module.exports = function (taskTypeContainers) {
    var _loop = function _loop(i) {
        var unavailableTaskType = taskTypeContainers[i];
        var taskTypeKey = unavailableTaskType.getAttribute('data-task-type');
        var modalId = taskTypeKey + '-account-required-modal';
        var modalElement = document.getElementById(modalId);
        var modal = new Modal(modalElement);

        unavailableTaskType.addEventListener('click', function () {
            modal.show();
        });
    };

    for (var i = 0; i < taskTypeContainers.length; i++) {
        _loop(i);
    }
};

/***/ }),

/***/ "./assets/js/user-account-card/form-validator.js":
/*!*******************************************************!*\
  !*** ./assets/js/user-account-card/form-validator.js ***!
  \*******************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var FormValidator = function () {
    /**
     * @param {Stripe} stripeJs
     */
    function FormValidator(stripeJs) {
        _classCallCheck(this, FormValidator);

        this.stripeJs = stripeJs;
        this.invalidField = null;
        this.errorMessage = '';
    }

    _createClass(FormValidator, [{
        key: 'validate',


        /**
         * @param {Object} data
         * @returns {boolean}
         */
        value: function validate(data) {
            var _this = this;

            this.invalidField = null;

            Object.entries(data).forEach(function (_ref) {
                var _ref2 = _slicedToArray(_ref, 2),
                    key = _ref2[0],
                    value = _ref2[1];

                if (!_this.invalidField) {
                    var comparatorValue = value.trim();

                    if (comparatorValue === '') {
                        _this.invalidField = key;
                        _this.errorMessage = 'empty';
                    }
                }
            });

            if (this.invalidField) {
                return false;
            }

            if (!this.stripeJs.card.validateCardNumber(data.number)) {
                this.invalidField = 'number';
                this.errorMessage = 'invalid';

                return false;
            }

            if (!this.stripeJs.card.validateExpiry(data.exp_month, data.exp_year)) {
                this.invalidField = 'exp_month';
                this.errorMessage = 'invalid';

                return false;
            }

            if (!this.stripeJs.card.validateCVC(data.cvc)) {
                this.invalidField = 'cvc';
                this.errorMessage = 'invalid';

                return false;
            }

            return true;
        }
    }]);

    return FormValidator;
}();

module.exports = FormValidator;

/***/ }),

/***/ "./assets/js/user-account-card/form.js":
/*!*********************************************!*\
  !*** ./assets/js/user-account-card/form.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var formFieldFocuser = __webpack_require__(/*! ../form-field-focuser */ "./assets/js/form-field-focuser.js");
var AlertFactory = __webpack_require__(/*! ../services/alert-factory */ "./assets/js/services/alert-factory.js");
var FormButton = __webpack_require__(/*! ../model/element/form-button */ "./assets/js/model/element/form-button.js");

var Form = function () {
    function Form(element, validator) {
        _classCallCheck(this, Form);

        this.document = element.ownerDocument;
        this.element = element;
        this.validator = validator;
        this.submitButton = new FormButton(element.querySelector('button[type=submit]'));
    }

    _createClass(Form, [{
        key: 'init',
        value: function init() {
            this.element.addEventListener('submit', this._submitEventListener.bind(this));
        }
    }, {
        key: 'getStripePublishableKey',
        value: function getStripePublishableKey() {
            return this.element.getAttribute('data-stripe-publishable-key');
        }
    }, {
        key: 'getUpdateCardEventName',
        value: function getUpdateCardEventName() {
            return 'user.account.card.update';
        }
    }, {
        key: 'disable',
        value: function disable() {
            this.submitButton.disable();
        }
    }, {
        key: 'enable',
        value: function enable() {
            this.submitButton.markAsAvailable();
            this.submitButton.enable();
        }
    }, {
        key: '_getData',
        value: function _getData() {
            var data = {};

            [].forEach.call(this.element.querySelectorAll('[data-stripe]'), function (dataElement) {
                var fieldKey = dataElement.getAttribute('data-stripe');

                data[fieldKey] = dataElement.value;
            });

            return data;
        }
    }, {
        key: '_submitEventListener',
        value: function _submitEventListener(event) {
            event.preventDefault();
            event.stopPropagation();

            this._removeErrorAlerts();
            this.disable();

            var data = this._getData();
            var isValid = this.validator.validate(data);

            if (!isValid) {
                this.handleResponseError(this.createResponseError(this.validator.invalidField, this.validator.errorMessage));
                this.enable();
            } else {
                var _event = new CustomEvent(this.getUpdateCardEventName(), {
                    detail: data
                });

                this.element.dispatchEvent(_event);
            }
        }
    }, {
        key: '_removeErrorAlerts',
        value: function _removeErrorAlerts() {
            var errorAlerts = this.element.querySelectorAll('.alert');

            [].forEach.call(errorAlerts, function (errorAlert) {
                errorAlert.parentNode.removeChild(errorAlert);
            });
        }
    }, {
        key: '_displayFieldError',
        value: function _displayFieldError(field, error) {
            var alert = AlertFactory.createFromContent(this.document, '<p>' + error.message + '</p>', field.getAttribute('id'));
            var errorContainer = this.element.querySelector('[data-for=' + field.getAttribute('id') + ']');

            if (!errorContainer) {
                errorContainer = this.element.querySelector('[data-for*=' + field.getAttribute('id') + ']');
            }

            errorContainer.append(alert.element);
        }
    }, {
        key: 'handleResponseError',
        value: function handleResponseError(error) {
            var field = this.element.querySelector('[data-stripe=' + error.param + ']');

            formFieldFocuser(field);
            this._displayFieldError(field, error);
        }
    }, {
        key: 'createResponseError',
        value: function createResponseError(field, state) {
            var errorMessage = '';

            if (state === 'empty') {
                errorMessage = 'Hold on, you can\'t leave this empty';
            }

            if (state === 'invalid') {
                if (field === 'number') {
                    errorMessage = 'The card number is not quite right';
                }

                if (field === 'exp_month') {
                    errorMessage = 'An expiry date in the future is better';
                }

                if (field === 'cvc') {
                    errorMessage = 'The CVC should be 3 or 4 digits';
                }
            }

            return {
                param: field,
                message: errorMessage
            };
        }
    }]);

    return Form;
}();

module.exports = Form;

/***/ }),

/***/ "./assets/js/user-account/navbar-anchor.js":
/*!*************************************************!*\
  !*** ./assets/js/user-account/navbar-anchor.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ScrollTo = __webpack_require__(/*! ../scroll-to */ "./assets/js/scroll-to.js");

var NavBarAnchor = function () {
    /**
     * @param {Element} element
     * @param {number} scrollOffset
     */
    function NavBarAnchor(element, scrollOffset) {
        _classCallCheck(this, NavBarAnchor);

        this.element = element;
        this.scrollOffset = scrollOffset;
        this.targetId = element.getAttribute('href').replace('#', '');

        this.element.addEventListener('click', this.handleClick.bind(this));
    }

    _createClass(NavBarAnchor, [{
        key: 'handleClick',
        value: function handleClick(event) {
            event.preventDefault();
            event.stopPropagation();

            var target = this.getTarget();

            if (this.element.classList.contains('js-first')) {
                ScrollTo.goTo(target, 0);
            } else {
                ScrollTo.scrollTo(target, this.scrollOffset);
            }
        }
    }, {
        key: 'getTarget',
        value: function getTarget() {
            return document.getElementById(this.targetId);
        }
    }]);

    return NavBarAnchor;
}();

module.exports = NavBarAnchor;

/***/ }),

/***/ "./assets/js/user-account/navbar-item.js":
/*!***********************************************!*\
  !*** ./assets/js/user-account/navbar-item.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var NavBarAnchor = __webpack_require__(/*! ./navbar-anchor */ "./assets/js/user-account/navbar-anchor.js");

var NavBarItem = function () {
    /**
     * @param {Element} element
     * @param {number} scrollOffset
     * @param {function} NavBarList
     */
    function NavBarItem(element, scrollOffset, NavBarList) {
        _classCallCheck(this, NavBarItem);

        this.element = element;
        this.anchor = null;
        this.navBarList = null;

        for (var i = 0; i < element.children.length; i++) {
            var child = element.children.item(i);

            if (child.nodeName === 'A' && child.getAttribute('href')[0] === '#') {
                this.anchor = new NavBarAnchor(child, scrollOffset);
            }

            if (child.nodeName === 'UL') {
                this.navBarList = new NavBarList(child, scrollOffset);
            }
        }
    }

    _createClass(NavBarItem, [{
        key: 'getTargets',
        value: function getTargets() {
            var targets = [];

            if (this.anchor) {
                targets.push(this.anchor.getTarget());
            }

            if (this.navBarList) {
                this.navBarList.getTargets().forEach(function (target) {
                    targets.push(target);
                });
            }

            return targets;
        }
    }, {
        key: 'containsTargetId',
        value: function containsTargetId(targetId) {
            if (this.anchor && this.anchor.targetId === targetId) {
                return true;
            }

            if (this.navBarList) {
                return this.navBarList.containsTargetId(targetId);
            }

            return false;
        }
    }, {
        key: 'setActive',
        value: function setActive(targetId) {
            this.element.classList.add('active');

            if (this.navBarList && this.navBarList.containsTargetId(targetId)) {
                this.navBarList.setActive(targetId);
            }
        }
    }]);

    return NavBarItem;
}();

module.exports = NavBarItem;

/***/ }),

/***/ "./assets/js/user-account/navbar-list.js":
/*!***********************************************!*\
  !*** ./assets/js/user-account/navbar-list.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var NavBarItem = __webpack_require__(/*! ./navbar-item */ "./assets/js/user-account/navbar-item.js");

var NavBarList = function () {
    /**
     * @param {Element} element
     * @param {number} scrollOffset
     */
    function NavBarList(element, scrollOffset) {
        _classCallCheck(this, NavBarList);

        this.element = element;
        this.navBarItems = [];

        for (var i = 0; i < element.children.length; i++) {
            this.navBarItems.push(new NavBarItem(element.children.item(i), scrollOffset, NavBarList));
        }
    }

    _createClass(NavBarList, [{
        key: 'getTargets',
        value: function getTargets() {
            var targets = [];

            for (var i = 0; i < this.navBarItems.length; i++) {
                this.navBarItems[i].getTargets().forEach(function (target) {
                    targets.push(target);
                });
            }

            return targets;
        }
    }, {
        key: 'containsTargetId',
        value: function containsTargetId(targetId) {
            var contains = false;

            this.navBarItems.forEach(function (navBarItem) {
                if (navBarItem.containsTargetId(targetId)) {
                    contains = true;
                }
            });

            return contains;
        }
    }, {
        key: 'clearActive',
        value: function clearActive() {
            [].forEach.call(this.element.querySelectorAll('li'), function (listItemElement) {
                listItemElement.classList.remove('active');
            });
        }
    }, {
        key: 'setActive',
        value: function setActive(targetId) {
            this.navBarItems.forEach(function (navBarItem) {
                if (navBarItem.containsTargetId(targetId)) {
                    navBarItem.setActive(targetId);
                }
            });
        }
    }]);

    return NavBarList;
}();

module.exports = NavBarList;

/***/ }),

/***/ "./assets/js/user-account/scrollspy.js":
/*!*********************************************!*\
  !*** ./assets/js/user-account/scrollspy.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

__webpack_require__(/*! ./navbar-list */ "./assets/js/user-account/navbar-list.js");

var ScrollSpy = function () {
    /**
     * @param {NavBarList} navBarList
     * @param {number} offset
     */
    function ScrollSpy(navBarList, offset) {
        _classCallCheck(this, ScrollSpy);

        this.navBarList = navBarList;
        this.offset = offset;
    }

    _createClass(ScrollSpy, [{
        key: 'scrollEventListener',
        value: function scrollEventListener() {
            var activeLinkTarget = null;
            var linkTargets = this.navBarList.getTargets();
            var offset = this.offset;
            var linkTargetsPastThreshold = [];

            linkTargets.forEach(function (linkTarget) {
                if (linkTarget) {
                    var offsetTop = linkTarget.getBoundingClientRect().top;

                    if (offsetTop < offset) {
                        linkTargetsPastThreshold.push(linkTarget);
                    }
                }
            });

            if (linkTargetsPastThreshold.length === 0) {
                activeLinkTarget = linkTargets[0];
            } else if (linkTargetsPastThreshold.length === linkTargets.length) {
                activeLinkTarget = linkTargets[linkTargets.length - 1];
            } else {
                activeLinkTarget = linkTargetsPastThreshold[linkTargetsPastThreshold.length - 1];
            }

            if (activeLinkTarget) {
                this.navBarList.clearActive();
                this.navBarList.setActive(activeLinkTarget.getAttribute('id'));
            }
        }
    }, {
        key: 'spy',
        value: function spy() {
            window.addEventListener('scroll', this.scrollEventListener.bind(this), true);
        }
    }]);

    return ScrollSpy;
}();

module.exports = ScrollSpy;

/***/ }),

/***/ "./node_modules/awesomplete/awesomplete.js":
/*!*************************************************!*\
  !*** ./node_modules/awesomplete/awesomplete.js ***!
  \*************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

/**
 * Simple, lightweight, usable local autocomplete library for modern browsers
 * Because there weren’t enough autocomplete scripts in the world? Because I’m completely insane and have NIH syndrome? Probably both. :P
 * @author Lea Verou http://leaverou.github.io/awesomplete
 * MIT license
 */

(function () {

var _ = function (input, o) {
	var me = this;

	// Setup

	this.isOpened = false;

	this.input = $(input);
	this.input.setAttribute("autocomplete", "off");
	this.input.setAttribute("aria-autocomplete", "list");

	o = o || {};

	configure(this, {
		minChars: 2,
		maxItems: 10,
		autoFirst: false,
		data: _.DATA,
		filter: _.FILTER_CONTAINS,
		sort: o.sort === false ? false : _.SORT_BYLENGTH,
		item: _.ITEM,
		replace: _.REPLACE
	}, o);

	this.index = -1;

	// Create necessary elements

	this.container = $.create("div", {
		className: "awesomplete",
		around: input
	});

	this.ul = $.create("ul", {
		hidden: "hidden",
		inside: this.container
	});

	this.status = $.create("span", {
		className: "visually-hidden",
		role: "status",
		"aria-live": "assertive",
		"aria-relevant": "additions",
		inside: this.container
	});

	// Bind events

	this._events = {
		input: {
			"input": this.evaluate.bind(this),
			"blur": this.close.bind(this, { reason: "blur" }),
			"keydown": function(evt) {
				var c = evt.keyCode;

				// If the dropdown `ul` is in view, then act on keydown for the following keys:
				// Enter / Esc / Up / Down
				if(me.opened) {
					if (c === 13 && me.selected) { // Enter
						evt.preventDefault();
						me.select();
					}
					else if (c === 27) { // Esc
						me.close({ reason: "esc" });
					}
					else if (c === 38 || c === 40) { // Down/Up arrow
						evt.preventDefault();
						me[c === 38? "previous" : "next"]();
					}
				}
			}
		},
		form: {
			"submit": this.close.bind(this, { reason: "submit" })
		},
		ul: {
			"mousedown": function(evt) {
				var li = evt.target;

				if (li !== this) {

					while (li && !/li/i.test(li.nodeName)) {
						li = li.parentNode;
					}

					if (li && evt.button === 0) {  // Only select on left click
						evt.preventDefault();
						me.select(li, evt.target);
					}
				}
			}
		}
	};

	$.bind(this.input, this._events.input);
	$.bind(this.input.form, this._events.form);
	$.bind(this.ul, this._events.ul);

	if (this.input.hasAttribute("list")) {
		this.list = "#" + this.input.getAttribute("list");
		this.input.removeAttribute("list");
	}
	else {
		this.list = this.input.getAttribute("data-list") || o.list || [];
	}

	_.all.push(this);
};

_.prototype = {
	set list(list) {
		if (Array.isArray(list)) {
			this._list = list;
		}
		else if (typeof list === "string" && list.indexOf(",") > -1) {
				this._list = list.split(/\s*,\s*/);
		}
		else { // Element or CSS selector
			list = $(list);

			if (list && list.children) {
				var items = [];
				slice.apply(list.children).forEach(function (el) {
					if (!el.disabled) {
						var text = el.textContent.trim();
						var value = el.value || text;
						var label = el.label || text;
						if (value !== "") {
							items.push({ label: label, value: value });
						}
					}
				});
				this._list = items;
			}
		}

		if (document.activeElement === this.input) {
			this.evaluate();
		}
	},

	get selected() {
		return this.index > -1;
	},

	get opened() {
		return this.isOpened;
	},

	close: function (o) {
		if (!this.opened) {
			return;
		}

		this.ul.setAttribute("hidden", "");
		this.isOpened = false;
		this.index = -1;

		$.fire(this.input, "awesomplete-close", o || {});
	},

	open: function () {
		this.ul.removeAttribute("hidden");
		this.isOpened = true;

		if (this.autoFirst && this.index === -1) {
			this.goto(0);
		}

		$.fire(this.input, "awesomplete-open");
	},

	destroy: function() {
		//remove events from the input and its form
		$.unbind(this.input, this._events.input);
		$.unbind(this.input.form, this._events.form);

		//move the input out of the awesomplete container and remove the container and its children
		var parentNode = this.container.parentNode;

		parentNode.insertBefore(this.input, this.container);
		parentNode.removeChild(this.container);

		//remove autocomplete and aria-autocomplete attributes
		this.input.removeAttribute("autocomplete");
		this.input.removeAttribute("aria-autocomplete");

		//remove this awesomeplete instance from the global array of instances
		var indexOfAwesomplete = _.all.indexOf(this);

		if (indexOfAwesomplete !== -1) {
			_.all.splice(indexOfAwesomplete, 1);
		}
	},

	next: function () {
		var count = this.ul.children.length;
		this.goto(this.index < count - 1 ? this.index + 1 : (count ? 0 : -1) );
	},

	previous: function () {
		var count = this.ul.children.length;
		var pos = this.index - 1;

		this.goto(this.selected && pos !== -1 ? pos : count - 1);
	},

	// Should not be used, highlights specific item without any checks!
	goto: function (i) {
		var lis = this.ul.children;

		if (this.selected) {
			lis[this.index].setAttribute("aria-selected", "false");
		}

		this.index = i;

		if (i > -1 && lis.length > 0) {
			lis[i].setAttribute("aria-selected", "true");
			this.status.textContent = lis[i].textContent;

			// scroll to highlighted element in case parent's height is fixed
			this.ul.scrollTop = lis[i].offsetTop - this.ul.clientHeight + lis[i].clientHeight;

			$.fire(this.input, "awesomplete-highlight", {
				text: this.suggestions[this.index]
			});
		}
	},

	select: function (selected, origin) {
		if (selected) {
			this.index = $.siblingIndex(selected);
		} else {
			selected = this.ul.children[this.index];
		}

		if (selected) {
			var suggestion = this.suggestions[this.index];

			var allowed = $.fire(this.input, "awesomplete-select", {
				text: suggestion,
				origin: origin || selected
			});

			if (allowed) {
				this.replace(suggestion);
				this.close({ reason: "select" });
				$.fire(this.input, "awesomplete-selectcomplete", {
					text: suggestion
				});
			}
		}
	},

	evaluate: function() {
		var me = this;
		var value = this.input.value;

		if (value.length >= this.minChars && this._list.length > 0) {
			this.index = -1;
			// Populate list with options that match
			this.ul.innerHTML = "";

			this.suggestions = this._list
				.map(function(item) {
					return new Suggestion(me.data(item, value));
				})
				.filter(function(item) {
					return me.filter(item, value);
				});

			if (this.sort !== false) {
				this.suggestions = this.suggestions.sort(this.sort);
			}

			this.suggestions = this.suggestions.slice(0, this.maxItems);

			this.suggestions.forEach(function(text) {
					me.ul.appendChild(me.item(text, value));
				});

			if (this.ul.children.length === 0) {
				this.close({ reason: "nomatches" });
			} else {
				this.open();
			}
		}
		else {
			this.close({ reason: "nomatches" });
		}
	}
};

// Static methods/properties

_.all = [];

_.FILTER_CONTAINS = function (text, input) {
	return RegExp($.regExpEscape(input.trim()), "i").test(text);
};

_.FILTER_STARTSWITH = function (text, input) {
	return RegExp("^" + $.regExpEscape(input.trim()), "i").test(text);
};

_.SORT_BYLENGTH = function (a, b) {
	if (a.length !== b.length) {
		return a.length - b.length;
	}

	return a < b? -1 : 1;
};

_.ITEM = function (text, input) {
	var html = input.trim() === "" ? text : text.replace(RegExp($.regExpEscape(input.trim()), "gi"), "<mark>$&</mark>");
	return $.create("li", {
		innerHTML: html,
		"aria-selected": "false"
	});
};

_.REPLACE = function (text) {
	this.input.value = text.value;
};

_.DATA = function (item/*, input*/) { return item; };

// Private functions

function Suggestion(data) {
	var o = Array.isArray(data)
	  ? { label: data[0], value: data[1] }
	  : typeof data === "object" && "label" in data && "value" in data ? data : { label: data, value: data };

	this.label = o.label || o.value;
	this.value = o.value;
}
Object.defineProperty(Suggestion.prototype = Object.create(String.prototype), "length", {
	get: function() { return this.label.length; }
});
Suggestion.prototype.toString = Suggestion.prototype.valueOf = function () {
	return "" + this.label;
};

function configure(instance, properties, o) {
	for (var i in properties) {
		var initial = properties[i],
		    attrValue = instance.input.getAttribute("data-" + i.toLowerCase());

		if (typeof initial === "number") {
			instance[i] = parseInt(attrValue);
		}
		else if (initial === false) { // Boolean options must be false by default anyway
			instance[i] = attrValue !== null;
		}
		else if (initial instanceof Function) {
			instance[i] = null;
		}
		else {
			instance[i] = attrValue;
		}

		if (!instance[i] && instance[i] !== 0) {
			instance[i] = (i in o)? o[i] : initial;
		}
	}
}

// Helpers

var slice = Array.prototype.slice;

function $(expr, con) {
	return typeof expr === "string"? (con || document).querySelector(expr) : expr || null;
}

function $$(expr, con) {
	return slice.call((con || document).querySelectorAll(expr));
}

$.create = function(tag, o) {
	var element = document.createElement(tag);

	for (var i in o) {
		var val = o[i];

		if (i === "inside") {
			$(val).appendChild(element);
		}
		else if (i === "around") {
			var ref = $(val);
			ref.parentNode.insertBefore(element, ref);
			element.appendChild(ref);
		}
		else if (i in element) {
			element[i] = val;
		}
		else {
			element.setAttribute(i, val);
		}
	}

	return element;
};

$.bind = function(element, o) {
	if (element) {
		for (var event in o) {
			var callback = o[event];

			event.split(/\s+/).forEach(function (event) {
				element.addEventListener(event, callback);
			});
		}
	}
};

$.unbind = function(element, o) {
	if (element) {
		for (var event in o) {
			var callback = o[event];

			event.split(/\s+/).forEach(function(event) {
				element.removeEventListener(event, callback);
			});
		}
	}
};

$.fire = function(target, type, properties) {
	var evt = document.createEvent("HTMLEvents");

	evt.initEvent(type, true, true );

	for (var j in properties) {
		evt[j] = properties[j];
	}

	return target.dispatchEvent(evt);
};

$.regExpEscape = function (s) {
	return s.replace(/[-\\^$*+?.()|[\]{}]/g, "\\$&");
};

$.siblingIndex = function (el) {
	/* eslint-disable no-cond-assign */
	for (var i = 0; el = el.previousElementSibling; i++);
	return i;
};

// Initialization

function init() {
	$$("input.awesomplete").forEach(function (input) {
		new _(input);
	});
}

// Are we in a browser? Check for Document constructor
if (typeof Document !== "undefined") {
	// DOM already loaded?
	if (document.readyState !== "loading") {
		init();
	}
	else {
		// Wait for it
		document.addEventListener("DOMContentLoaded", init);
	}
}

_.$ = $;
_.$$ = $$;

// Make sure to export Awesomplete on self when in a browser
if (typeof self !== "undefined") {
	self.Awesomplete = _;
}

// Expose Awesomplete as a CJS module
if (typeof module === "object" && module.exports) {
	module.exports = _;
}

return _;

}());


/***/ }),

/***/ "./node_modules/bootstrap.native/dist/bootstrap-native.js":
/*!****************************************************************!*\
  !*** ./node_modules/bootstrap.native/dist/bootstrap-native.js ***!
  \****************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;// Native Javascript for Bootstrap 3 v2.0.23 | © dnp_theme | MIT-License
(function (root, factory) {
  if (true) {
    // AMD support:
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else if (typeof module === 'object' && module.exports) {
    // CommonJS-like:
    module.exports = factory();
  } else {
    // Browser globals (root is window)
    var bsn = factory();
    root.Modal = bsn.Modal;
    root.Collapse = bsn.Collapse;
    root.Alert = bsn.Alert;
  }
}(this, function () {
  
  /* Native Javascript for Bootstrap 3 | Internal Utility Functions
  ----------------------------------------------------------------*/
  "use strict";
  
  // globals
  var globalObject = typeof global !== 'undefined' ? global : this||window,
    DOC = document, HTML = DOC.documentElement, body = 'body', // allow the library to be used in <head>
  
    // Native Javascript for Bootstrap Global Object
    BSN = globalObject.BSN = {},
    supports = BSN.supports = [],
  
    // function toggle attributes
    dataToggle    = 'data-toggle',
    dataDismiss   = 'data-dismiss',
    dataSpy       = 'data-spy',
    dataRide      = 'data-ride',
    
    // components
    stringAffix     = 'Affix',
    stringAlert     = 'Alert',
    stringButton    = 'Button',
    stringCarousel  = 'Carousel',
    stringCollapse  = 'Collapse',
    stringDropdown  = 'Dropdown',
    stringModal     = 'Modal',
    stringPopover   = 'Popover',
    stringScrollSpy = 'ScrollSpy',
    stringTab       = 'Tab',
    stringTooltip   = 'Tooltip',
  
    // options DATA API
    databackdrop      = 'data-backdrop',
    dataKeyboard      = 'data-keyboard',
    dataTarget        = 'data-target',
    dataInterval      = 'data-interval',
    dataHeight        = 'data-height',
    dataPause         = 'data-pause',
    dataTitle         = 'data-title',  
    dataOriginalTitle = 'data-original-title',
    dataOriginalText  = 'data-original-text',
    dataDismissible   = 'data-dismissible',
    dataTrigger       = 'data-trigger',
    dataAnimation     = 'data-animation',
    dataContainer     = 'data-container',
    dataPlacement     = 'data-placement',
    dataDelay         = 'data-delay',
    dataOffsetTop     = 'data-offset-top',
    dataOffsetBottom  = 'data-offset-bottom',
  
    // option keys
    backdrop = 'backdrop', keyboard = 'keyboard', delay = 'delay',
    content = 'content', target = 'target', 
    interval = 'interval', pause = 'pause', animation = 'animation',
    placement = 'placement', container = 'container', 
  
    // box model
    offsetTop    = 'offsetTop',      offsetBottom   = 'offsetBottom',
    offsetLeft   = 'offsetLeft',
    scrollTop    = 'scrollTop',      scrollLeft     = 'scrollLeft',
    clientWidth  = 'clientWidth',    clientHeight   = 'clientHeight',
    offsetWidth  = 'offsetWidth',    offsetHeight   = 'offsetHeight',
    innerWidth   = 'innerWidth',     innerHeight    = 'innerHeight',
    scrollHeight = 'scrollHeight',   height         = 'height',
  
    // aria
    ariaExpanded = 'aria-expanded',
    ariaHidden   = 'aria-hidden',
  
    // event names
    clickEvent    = 'click',
    hoverEvent    = 'hover',
    keydownEvent  = 'keydown',
    keyupEvent    = 'keyup',  
    resizeEvent   = 'resize',
    scrollEvent   = 'scroll',
    // originalEvents
    showEvent     = 'show',
    shownEvent    = 'shown',
    hideEvent     = 'hide',
    hiddenEvent   = 'hidden',
    closeEvent    = 'close',
    closedEvent   = 'closed',
    slidEvent     = 'slid',
    slideEvent    = 'slide',
    changeEvent   = 'change',
  
    // other
    getAttribute           = 'getAttribute',
    setAttribute           = 'setAttribute',
    hasAttribute           = 'hasAttribute',
    createElement          = 'createElement',
    appendChild            = 'appendChild',
    innerHTML              = 'innerHTML',
    getElementsByTagName   = 'getElementsByTagName',
    preventDefault         = 'preventDefault',
    getBoundingClientRect  = 'getBoundingClientRect',
    querySelectorAll       = 'querySelectorAll',
    getElementsByCLASSNAME = 'getElementsByClassName',
    getComputedStyle       = 'getComputedStyle',  
  
    indexOf      = 'indexOf',
    parentNode   = 'parentNode',
    length       = 'length',
    toLowerCase  = 'toLowerCase',
    Transition   = 'Transition',
    Duration     = 'Duration',  
    Webkit       = 'Webkit',
    style        = 'style',
    push         = 'push',
    tabindex     = 'tabindex',
    contains     = 'contains',  
    
    active     = 'active',
    inClass    = 'in',
    collapsing = 'collapsing',
    disabled   = 'disabled',
    loading    = 'loading',
    left       = 'left',
    right      = 'right',
    top        = 'top',
    bottom     = 'bottom',
  
    // IE8 browser detect
    isIE8 = !('opacity' in HTML[style]),
  
    // tooltip / popover
    mouseHover = ('onmouseleave' in DOC) ? [ 'mouseenter', 'mouseleave'] : [ 'mouseover', 'mouseout' ],
    tipPositions = /\b(top|bottom|left|right)+/,
    
    // modal
    modalOverlay = 0,
    fixedTop = 'navbar-fixed-top',
    fixedBottom = 'navbar-fixed-bottom',  
    
    // transitionEnd since 2.0.4
    supportTransitions = Webkit+Transition in HTML[style] || Transition[toLowerCase]() in HTML[style],
    transitionEndEvent = Webkit+Transition in HTML[style] ? Webkit[toLowerCase]()+Transition+'End' : Transition[toLowerCase]()+'end',
    transitionDuration = Webkit+Duration in HTML[style] ? Webkit[toLowerCase]()+Transition+Duration : Transition[toLowerCase]()+Duration,
  
    // set new focus element since 2.0.3
    setFocus = function(element){
      element.focus ? element.focus() : element.setActive();
    },
  
    // class manipulation, since 2.0.0 requires polyfill.js
    addClass = function(element,classNAME) {
      element.classList.add(classNAME);
    },
    removeClass = function(element,classNAME) {
      element.classList.remove(classNAME);
    },
    hasClass = function(element,classNAME){ // since 2.0.0
      return element.classList[contains](classNAME);
    },
  
    // selection methods
    nodeListToArray = function(nodeList){
      var childItems = []; for (var i = 0, nll = nodeList[length]; i<nll; i++) { childItems[push]( nodeList[i] ) }
      return childItems;
    },
    getElementsByClassName = function(element,classNAME) { // getElementsByClassName IE8+
      var selectionMethod = isIE8 ? querySelectorAll : getElementsByCLASSNAME;      
      return nodeListToArray(element[selectionMethod]( isIE8 ? '.' + classNAME.replace(/\s(?=[a-z])/g,'.') : classNAME ));
    },
    queryElement = function (selector, parent) {
      var lookUp = parent ? parent : DOC;
      return typeof selector === 'object' ? selector : lookUp.querySelector(selector);
    },
    getClosest = function (element, selector) { //element is the element and selector is for the closest parent element to find
      // source http://gomakethings.com/climbing-up-and-down-the-dom-tree-with-vanilla-javascript/
      var firstChar = selector.charAt(0), selectorSubstring = selector.substr(1);
      if ( firstChar === '.' ) {// If selector is a class
        for ( ; element && element !== DOC; element = element[parentNode] ) { // Get closest match
          if ( queryElement(selector,element[parentNode]) !== null && hasClass(element,selectorSubstring) ) { return element; }
        }
      } else if ( firstChar === '#' ) { // If selector is an ID
        for ( ; element && element !== DOC; element = element[parentNode] ) { // Get closest match
          if ( element.id === selectorSubstring ) { return element; }
        }
      }
      return false;
    },
  
    // event attach jQuery style / trigger  since 1.2.0
    on = function (element, event, handler) {
      element.addEventListener(event, handler, false);
    },
    off = function(element, event, handler) {
      element.removeEventListener(event, handler, false);
    },
    one = function (element, event, handler) { // one since 2.0.4
      on(element, event, function handlerWrapper(e){
        handler(e);
        off(element, event, handlerWrapper);
      });
    },
    getTransitionDurationFromElement = function(element) {
      var duration = globalObject[getComputedStyle](element)[transitionDuration];
      duration = parseFloat(duration);
      duration = typeof duration === 'number' && !isNaN(duration) ? duration * 1000 : 0;
      return duration + 50; // we take a short offset to make sure we fire on the next frame after animation
    },
    emulateTransitionEnd = function(element,handler){ // emulateTransitionEnd since 2.0.4
      var called = 0, duration = getTransitionDurationFromElement(element);
      supportTransitions && one(element, transitionEndEvent, function(e){ handler(e); called = 1; });
      setTimeout(function() { !called && handler(); }, duration);
    },
    bootstrapCustomEvent = function (eventName, componentName, related) {
      var OriginalCustomEvent = new CustomEvent( eventName + '.bs.' + componentName);
      OriginalCustomEvent.relatedTarget = related;
      this.dispatchEvent(OriginalCustomEvent);
    },
  
    // tooltip / popover stuff
    getScroll = function() { // also Affix and ScrollSpy uses it
      return {
        y : globalObject.pageYOffset || HTML[scrollTop],
        x : globalObject.pageXOffset || HTML[scrollLeft]
      }
    },
    styleTip = function(link,element,position,parent) { // both popovers and tooltips (target,tooltip/popover,placement,elementToAppendTo)
      var elementDimensions = { w : element[offsetWidth], h: element[offsetHeight] },
          windowWidth = (HTML[clientWidth] || DOC[body][clientWidth]),
          windowHeight = (HTML[clientHeight] || DOC[body][clientHeight]),
          rect = link[getBoundingClientRect](), 
          scroll = parent === DOC[body] ? getScroll() : { x: parent[offsetLeft] + parent[scrollLeft], y: parent[offsetTop] + parent[scrollTop] },
          linkDimensions = { w: rect[right] - rect[left], h: rect[bottom] - rect[top] },
          arrow = queryElement('[class*="arrow"]',element),
          topPosition, leftPosition, arrowTop, arrowLeft,
  
          halfTopExceed = rect[top] + linkDimensions.h/2 - elementDimensions.h/2 < 0,
          halfLeftExceed = rect[left] + linkDimensions.w/2 - elementDimensions.w/2 < 0,
          halfRightExceed = rect[left] + elementDimensions.w/2 + linkDimensions.w/2 >= windowWidth,
          halfBottomExceed = rect[top] + elementDimensions.h/2 + linkDimensions.h/2 >= windowHeight,
          topExceed = rect[top] - elementDimensions.h < 0,
          leftExceed = rect[left] - elementDimensions.w < 0,
          bottomExceed = rect[top] + elementDimensions.h + linkDimensions.h >= windowHeight,
          rightExceed = rect[left] + elementDimensions.w + linkDimensions.w >= windowWidth;
  
      // recompute position
      position = (position === left || position === right) && leftExceed && rightExceed ? top : position; // first, when both left and right limits are exceeded, we fall back to top|bottom
      position = position === top && topExceed ? bottom : position;
      position = position === bottom && bottomExceed ? top : position;
      position = position === left && leftExceed ? right : position;
      position = position === right && rightExceed ? left : position;
      
      // apply styling to tooltip or popover
      if ( position === left || position === right ) { // secondary|side positions
        if ( position === left ) { // LEFT
          leftPosition = rect[left] + scroll.x - elementDimensions.w;
        } else { // RIGHT
          leftPosition = rect[left] + scroll.x + linkDimensions.w;
        }
  
        // adjust top and arrow
        if (halfTopExceed) {
          topPosition = rect[top] + scroll.y;
          arrowTop = linkDimensions.h/2;
        } else if (halfBottomExceed) {
          topPosition = rect[top] + scroll.y - elementDimensions.h + linkDimensions.h;
          arrowTop = elementDimensions.h - linkDimensions.h/2;
        } else {
          topPosition = rect[top] + scroll.y - elementDimensions.h/2 + linkDimensions.h/2;
        }
      } else if ( position === top || position === bottom ) { // primary|vertical positions
        if ( position === top) { // TOP
          topPosition =  rect[top] + scroll.y - elementDimensions.h;
        } else { // BOTTOM
          topPosition = rect[top] + scroll.y + linkDimensions.h;
        }
        // adjust left | right and also the arrow
        if (halfLeftExceed) {
          leftPosition = 0;
          arrowLeft = rect[left] + linkDimensions.w/2;
        } else if (halfRightExceed) {
          leftPosition = windowWidth - elementDimensions.w*1.01;
          arrowLeft = elementDimensions.w - ( windowWidth - rect[left] ) + linkDimensions.w/2;
        } else {
          leftPosition = rect[left] + scroll.x - elementDimensions.w/2 + linkDimensions.w/2;
        }
      }
  
      // apply style to tooltip/popover and it's arrow
      element[style][top] = topPosition + 'px';
      element[style][left] = leftPosition + 'px';
  
      arrowTop && (arrow[style][top] = arrowTop + 'px');
      arrowLeft && (arrow[style][left] = arrowLeft + 'px');
  
      element.className[indexOf](position) === -1 && (element.className = element.className.replace(tipPositions,position));
    };
  
  BSN.version = '2.0.23';
  
  /* Native Javascript for Bootstrap 3 | Modal
  -------------------------------------------*/
  
  // MODAL DEFINITION
  // ===============
  var Modal = function(element, options) { // element can be the modal/triggering button
  
    // the modal (both JavaScript / DATA API init) / triggering button element (DATA API)
    element = queryElement(element);
  
    // determine modal, triggering element
    var btnCheck = element[getAttribute](dataTarget)||element[getAttribute]('href'),
      checkModal = queryElement( btnCheck ),
      modal = hasClass(element,'modal') ? element : checkModal,
      overlayDelay,
  
      // strings
      component = 'modal',
      staticString = 'static',
      paddingLeft = 'paddingLeft',
      paddingRight = 'paddingRight',
      modalBackdropString = 'modal-backdrop';
  
    if ( hasClass(element,'modal') ) { element = null; } // modal is now independent of it's triggering element
  
    if ( !modal ) { return; } // invalidate
  
    // set options
    options = options || {};
  
    this[keyboard] = options[keyboard] === false || modal[getAttribute](dataKeyboard) === 'false' ? false : true;
    this[backdrop] = options[backdrop] === staticString || modal[getAttribute](databackdrop) === staticString ? staticString : true;
    this[backdrop] = options[backdrop] === false || modal[getAttribute](databackdrop) === 'false' ? false : this[backdrop];
    this[content]  = options[content]; // JavaScript only
  
    // bind, constants, event targets and other vars
    var self = this, relatedTarget = null,
      bodyIsOverflowing, modalIsOverflowing, scrollbarWidth, overlay,
  
      // also find fixed-top / fixed-bottom items
      fixedItems = getElementsByClassName(HTML,fixedTop).concat(getElementsByClassName(HTML,fixedBottom)),
  
      // private methods
      getWindowWidth = function() {
        var htmlRect = HTML[getBoundingClientRect]();
        return globalObject[innerWidth] || (htmlRect[right] - Math.abs(htmlRect[left]));
      },
      setScrollbar = function () {
        var bodyStyle = DOC[body].currentStyle || globalObject[getComputedStyle](DOC[body]),
            bodyPad = parseInt((bodyStyle[paddingRight]), 10), itemPad;
        if (bodyIsOverflowing) {
          DOC[body][style][paddingRight] = (bodyPad + scrollbarWidth) + 'px';
          if (fixedItems[length]){
            for (var i = 0; i < fixedItems[length]; i++) {
              itemPad = (fixedItems[i].currentStyle || globalObject[getComputedStyle](fixedItems[i]))[paddingRight];
              fixedItems[i][style][paddingRight] = ( parseInt(itemPad) + scrollbarWidth) + 'px';
            }
          }
        }
      },
      resetScrollbar = function () {
        DOC[body][style][paddingRight] = '';
        if (fixedItems[length]){
          for (var i = 0; i < fixedItems[length]; i++) {
            fixedItems[i][style][paddingRight] = '';
          }
        }
      },
      measureScrollbar = function () { // thx walsh
        var scrollDiv = DOC[createElement]('div'), scrollBarWidth;
        scrollDiv.className = component+'-scrollbar-measure'; // this is here to stay
        DOC[body][appendChild](scrollDiv);
        scrollBarWidth = scrollDiv[offsetWidth] - scrollDiv[clientWidth];
        DOC[body].removeChild(scrollDiv);
      return scrollBarWidth;
      },
      checkScrollbar = function () {
        bodyIsOverflowing = DOC[body][clientWidth] < getWindowWidth();
        modalIsOverflowing = modal[scrollHeight] > HTML[clientHeight];
        scrollbarWidth = measureScrollbar();
      },
      adjustDialog = function () {
        modal[style][paddingLeft] = !bodyIsOverflowing && modalIsOverflowing ? scrollbarWidth + 'px' : '';
        modal[style][paddingRight] = bodyIsOverflowing && !modalIsOverflowing ? scrollbarWidth + 'px' : '';
      },
      resetAdjustments = function () {
        modal[style][paddingLeft] = '';
        modal[style][paddingRight] = '';
      },
      createOverlay = function() {
        modalOverlay = 1;
        
        var newOverlay = DOC[createElement]('div');
        overlay = queryElement('.'+modalBackdropString);
  
        if ( overlay === null ) {
          newOverlay[setAttribute]('class',modalBackdropString+' fade');
          overlay = newOverlay;
          DOC[body][appendChild](overlay);
        }
      },
      removeOverlay = function() {
        overlay = queryElement('.'+modalBackdropString);
        if ( overlay && overlay !== null && typeof overlay === 'object' ) {
          modalOverlay = 0;
          DOC[body].removeChild(overlay); overlay = null;
        }
        bootstrapCustomEvent.call(modal, hiddenEvent, component);      
      },
      keydownHandlerToggle = function() {
        if (hasClass(modal,inClass)) {
          on(DOC, keydownEvent, keyHandler);
        } else {
          off(DOC, keydownEvent, keyHandler);
        }
      },
      resizeHandlerToggle = function() {
        if (hasClass(modal,inClass)) {
          on(globalObject, resizeEvent, self.update);
        } else {
          off(globalObject, resizeEvent, self.update);
        }
      },
      dismissHandlerToggle = function() {
        if (hasClass(modal,inClass)) {
          on(modal, clickEvent, dismissHandler);
        } else {
          off(modal, clickEvent, dismissHandler);
        }
      },
      // triggers
      triggerShow = function() {
        setFocus(modal);
        bootstrapCustomEvent.call(modal, shownEvent, component, relatedTarget);
      },
      triggerHide = function() {
        modal[style].display = '';
        element && (setFocus(element));
        
        (function(){
          if (!getElementsByClassName(DOC,component+' '+inClass)[0]) {
            resetAdjustments();
            resetScrollbar();
            removeClass(DOC[body],component+'-open');
            overlay && hasClass(overlay,'fade') ? (removeClass(overlay,inClass), emulateTransitionEnd(overlay,removeOverlay)) 
            : removeOverlay();
  
            resizeHandlerToggle();
            dismissHandlerToggle();
            keydownHandlerToggle();
          }
        }());
      },
      // handlers
      clickHandler = function(e) {
        var clickTarget = e[target];
        clickTarget = clickTarget[hasAttribute](dataTarget) || clickTarget[hasAttribute]('href') ? clickTarget : clickTarget[parentNode];
        if ( clickTarget === element && !hasClass(modal,inClass) ) {
          modal.modalTrigger = element;
          relatedTarget = element;
          self.show();
          e[preventDefault]();
        }
      },
      keyHandler = function(e) {
        var key = e.which || e.keyCode; // keyCode for IE8
        if (self[keyboard] && key == 27 && hasClass(modal,inClass)) {
          self.hide();
        }
      },
      dismissHandler = function(e) {
        var clickTarget = e[target];
        if ( hasClass(modal,inClass) && (clickTarget[parentNode][getAttribute](dataDismiss) === component
            || clickTarget[getAttribute](dataDismiss) === component
            || (clickTarget === modal && self[backdrop] !== staticString) ) ) {
          self.hide(); relatedTarget = null;
          e[preventDefault]();
        }
      };
  
    // public methods
    this.toggle = function() {
      if ( hasClass(modal,inClass) ) {this.hide();} else {this.show();}
    };
    this.show = function() {
      bootstrapCustomEvent.call(modal, showEvent, component, relatedTarget);
  
      // we elegantly hide any opened modal
      var currentOpen = getElementsByClassName(DOC,component+' in')[0];
      currentOpen && currentOpen !== modal && currentOpen.modalTrigger[stringModal].hide();
  
      if ( this[backdrop] ) {
        !modalOverlay && createOverlay();
      }
  
      if ( overlay && modalOverlay && !hasClass(overlay,inClass)) {
        overlay[offsetWidth]; // force reflow to enable trasition
        overlayDelay = getTransitionDurationFromElement(overlay);
        addClass(overlay,inClass);
      }
  
      setTimeout(function() {
        modal[style].display = 'block';
  
        checkScrollbar();
        setScrollbar();
        adjustDialog();
  
        addClass(DOC[body],component+'-open');
        addClass(modal,inClass);
        modal[setAttribute](ariaHidden, false);
        
        resizeHandlerToggle();
        dismissHandlerToggle();
        keydownHandlerToggle();
  
        hasClass(modal,'fade') ? emulateTransitionEnd(modal, triggerShow) : triggerShow();
      }, supportTransitions && overlay ? overlayDelay : 0);
    };
    this.hide = function() {
      bootstrapCustomEvent.call(modal, hideEvent, component);
      overlay = queryElement('.'+modalBackdropString);
      overlayDelay = overlay && getTransitionDurationFromElement(overlay);
  
      removeClass(modal,inClass);
      modal[setAttribute](ariaHidden, true);
  
      setTimeout(function(){
        hasClass(modal,'fade') ? emulateTransitionEnd(modal, triggerHide) : triggerHide();
      }, supportTransitions && overlay ? overlayDelay : 0);
    };
    this.setContent = function( content ) {
      queryElement('.'+component+'-content',modal)[innerHTML] = content;
    };
    this.update = function() {
      if (hasClass(modal,inClass)) {
        checkScrollbar();
        setScrollbar();
        adjustDialog();
      }
    };
  
    // init
    // prevent adding event handlers over and over
    // modal is independent of a triggering element
    if ( !!element && !(stringModal in element) ) {
      on(element, clickEvent, clickHandler);
    }
    if ( !!self[content] ) { self.setContent( self[content] ); }
    !!element && (element[stringModal] = self);
  };
  
  // DATA API
  supports[push]( [ stringModal, Modal, '['+dataToggle+'="modal"]' ] );
  
  /* Native Javascript for Bootstrap 3 | Collapse
  -----------------------------------------------*/
  
  // COLLAPSE DEFINITION
  // ===================
  var Collapse = function( element, options ) {
  
    // initialization element
    element = queryElement(element);
  
    // set options
    options = options || {};
  
    // event targets and constants
    var accordion = null, collapse = null, self = this,
      accordionData = element[getAttribute]('data-parent'),
      activeCollapse, activeElement,
  
      // component strings
      component = 'collapse',
      collapsed = 'collapsed',
      isAnimating = 'isAnimating',
  
      // private methods
      openAction = function(collapseElement,toggle) {
        bootstrapCustomEvent.call(collapseElement, showEvent, component);
        collapseElement[isAnimating] = true;
        addClass(collapseElement,collapsing);
        removeClass(collapseElement,component);
        collapseElement[style][height] = collapseElement[scrollHeight] + 'px';
        
        emulateTransitionEnd(collapseElement, function() {
          collapseElement[isAnimating] = false;
          collapseElement[setAttribute](ariaExpanded,'true');
          toggle[setAttribute](ariaExpanded,'true');          
          removeClass(collapseElement,collapsing);
          addClass(collapseElement, component);
          addClass(collapseElement, inClass);
          collapseElement[style][height] = '';
          bootstrapCustomEvent.call(collapseElement, shownEvent, component);
        });
      },
      closeAction = function(collapseElement,toggle) {
        bootstrapCustomEvent.call(collapseElement, hideEvent, component);
        collapseElement[isAnimating] = true;
        collapseElement[style][height] = collapseElement[scrollHeight] + 'px'; // set height first
        removeClass(collapseElement,component);
        removeClass(collapseElement, inClass);
        addClass(collapseElement, collapsing);
        collapseElement[offsetWidth]; // force reflow to enable transition
        collapseElement[style][height] = '0px';
        
        emulateTransitionEnd(collapseElement, function() {
          collapseElement[isAnimating] = false;
          collapseElement[setAttribute](ariaExpanded,'false');
          toggle[setAttribute](ariaExpanded,'false');
          removeClass(collapseElement,collapsing);
          addClass(collapseElement,component);
          collapseElement[style][height] = '';
          bootstrapCustomEvent.call(collapseElement, hiddenEvent, component);
        });
      },
      getTarget = function() {
        var href = element.href && element[getAttribute]('href'),
          parent = element[getAttribute](dataTarget),
          id = href || ( parent && parent.charAt(0) === '#' ) && parent;
        return id && queryElement(id);
      };
    
    // public methods
    this.toggle = function(e) {
      e[preventDefault]();
      if (!hasClass(collapse,inClass)) { self.show(); } 
      else { self.hide(); }
    };
    this.hide = function() {
      if ( collapse[isAnimating] ) return;
      closeAction(collapse,element);
      addClass(element,collapsed);
    };
    this.show = function() {
      if ( accordion ) {
        activeCollapse = queryElement('.'+component+'.'+inClass,accordion);
        activeElement = activeCollapse && (queryElement('['+dataToggle+'="'+component+'"]['+dataTarget+'="#'+activeCollapse.id+'"]', accordion)
                      || queryElement('['+dataToggle+'="'+component+'"][href="#'+activeCollapse.id+'"]',accordion) );
      }
  
      if ( !collapse[isAnimating] || activeCollapse && !activeCollapse[isAnimating] ) {
        if ( activeElement && activeCollapse !== collapse ) {
          closeAction(activeCollapse,activeElement);
          addClass(activeElement,collapsed); 
        }
        openAction(collapse,element);
        removeClass(element,collapsed);
      }
    };
  
    // init
    if ( !(stringCollapse in element ) ) { // prevent adding event handlers twice
      on(element, clickEvent, self.toggle);
    }
    collapse = getTarget();
    collapse[isAnimating] = false;  // when true it will prevent click handlers  
    accordion = queryElement(options.parent) || accordionData && getClosest(element, accordionData);
    element[stringCollapse] = self;
  };
  
  // COLLAPSE DATA API
  // =================
  supports[push]( [ stringCollapse, Collapse, '['+dataToggle+'="collapse"]' ] );
  
  
  /* Native Javascript for Bootstrap 3 | Alert
  -------------------------------------------*/
  
  // ALERT DEFINITION
  // ================
  var Alert = function( element ) {
    
    // initialization element
    element = queryElement(element);
  
    // bind, target alert, duration and stuff
    var self = this, component = 'alert',
      alert = getClosest(element,'.'+component),
      triggerHandler = function(){ hasClass(alert,'fade') ? emulateTransitionEnd(alert,transitionEndHandler) : transitionEndHandler(); },
      // handlers
      clickHandler = function(e){
        alert = getClosest(e[target],'.'+component);
        element = queryElement('['+dataDismiss+'="'+component+'"]',alert);
        element && alert && (element === e[target] || element[contains](e[target])) && self.close();
      },
      transitionEndHandler = function(){
        bootstrapCustomEvent.call(alert, closedEvent, component);
        off(element, clickEvent, clickHandler); // detach it's listener
        alert[parentNode].removeChild(alert);
      };
    
    // public method
    this.close = function() {
      if ( alert && element && hasClass(alert,inClass) ) {
        bootstrapCustomEvent.call(alert, closeEvent, component);
        removeClass(alert,inClass);
        alert && triggerHandler();
      }
    };
  
    // init
    if ( !(stringAlert in element ) ) { // prevent adding event handlers twice
      on(element, clickEvent, clickHandler);
    }
    element[stringAlert] = self;
  };
  
  // ALERT DATA API
  // ==============
  supports[push]([stringAlert, Alert, '['+dataDismiss+'="alert"]']);
  
  
  
  
  /* Native Javascript for Bootstrap 3 | Initialize Data API
  --------------------------------------------------------*/
  var initializeDataAPI = function( constructor, collection ){
      for (var i=0, l=collection[length]; i<l; i++) {
        new constructor(collection[i]);
      }
    },
    initCallback = BSN.initCallback = function(lookUp){
      lookUp = lookUp || DOC;
      for (var i=0, l=supports[length]; i<l; i++) {
        initializeDataAPI( supports[i][1], lookUp[querySelectorAll] (supports[i][2]) );
      }
    };
  
  // bulk initialize all components
  DOC[body] ? initCallback() : on( DOC, 'DOMContentLoaded', function(){ initCallback(); } );
  
  return {
    Modal: Modal,
    Collapse: Collapse,
    Alert: Alert
  };
}));

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! ./../../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/classlist-polyfill/src/index.js":
/*!******************************************************!*\
  !*** ./node_modules/classlist-polyfill/src/index.js ***!
  \******************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

/*
 * classList.js: Cross-browser full element.classList implementation.
 * 1.1.20170427
 *
 * By Eli Grey, http://eligrey.com
 * License: Dedicated to the public domain.
 *   See https://github.com/eligrey/classList.js/blob/master/LICENSE.md
 */

/*global self, document, DOMException */

/*! @source http://purl.eligrey.com/github/classList.js/blob/master/classList.js */

if ("document" in window.self) {

// Full polyfill for browsers with no classList support
// Including IE < Edge missing SVGElement.classList
if (!("classList" in document.createElement("_")) 
	|| document.createElementNS && !("classList" in document.createElementNS("http://www.w3.org/2000/svg","g"))) {

(function (view) {

"use strict";

if (!('Element' in view)) return;

var
	  classListProp = "classList"
	, protoProp = "prototype"
	, elemCtrProto = view.Element[protoProp]
	, objCtr = Object
	, strTrim = String[protoProp].trim || function () {
		return this.replace(/^\s+|\s+$/g, "");
	}
	, arrIndexOf = Array[protoProp].indexOf || function (item) {
		var
			  i = 0
			, len = this.length
		;
		for (; i < len; i++) {
			if (i in this && this[i] === item) {
				return i;
			}
		}
		return -1;
	}
	// Vendors: please allow content code to instantiate DOMExceptions
	, DOMEx = function (type, message) {
		this.name = type;
		this.code = DOMException[type];
		this.message = message;
	}
	, checkTokenAndGetIndex = function (classList, token) {
		if (token === "") {
			throw new DOMEx(
				  "SYNTAX_ERR"
				, "An invalid or illegal string was specified"
			);
		}
		if (/\s/.test(token)) {
			throw new DOMEx(
				  "INVALID_CHARACTER_ERR"
				, "String contains an invalid character"
			);
		}
		return arrIndexOf.call(classList, token);
	}
	, ClassList = function (elem) {
		var
			  trimmedClasses = strTrim.call(elem.getAttribute("class") || "")
			, classes = trimmedClasses ? trimmedClasses.split(/\s+/) : []
			, i = 0
			, len = classes.length
		;
		for (; i < len; i++) {
			this.push(classes[i]);
		}
		this._updateClassName = function () {
			elem.setAttribute("class", this.toString());
		};
	}
	, classListProto = ClassList[protoProp] = []
	, classListGetter = function () {
		return new ClassList(this);
	}
;
// Most DOMException implementations don't allow calling DOMException's toString()
// on non-DOMExceptions. Error's toString() is sufficient here.
DOMEx[protoProp] = Error[protoProp];
classListProto.item = function (i) {
	return this[i] || null;
};
classListProto.contains = function (token) {
	token += "";
	return checkTokenAndGetIndex(this, token) !== -1;
};
classListProto.add = function () {
	var
		  tokens = arguments
		, i = 0
		, l = tokens.length
		, token
		, updated = false
	;
	do {
		token = tokens[i] + "";
		if (checkTokenAndGetIndex(this, token) === -1) {
			this.push(token);
			updated = true;
		}
	}
	while (++i < l);

	if (updated) {
		this._updateClassName();
	}
};
classListProto.remove = function () {
	var
		  tokens = arguments
		, i = 0
		, l = tokens.length
		, token
		, updated = false
		, index
	;
	do {
		token = tokens[i] + "";
		index = checkTokenAndGetIndex(this, token);
		while (index !== -1) {
			this.splice(index, 1);
			updated = true;
			index = checkTokenAndGetIndex(this, token);
		}
	}
	while (++i < l);

	if (updated) {
		this._updateClassName();
	}
};
classListProto.toggle = function (token, force) {
	token += "";

	var
		  result = this.contains(token)
		, method = result ?
			force !== true && "remove"
		:
			force !== false && "add"
	;

	if (method) {
		this[method](token);
	}

	if (force === true || force === false) {
		return force;
	} else {
		return !result;
	}
};
classListProto.toString = function () {
	return this.join(" ");
};

if (objCtr.defineProperty) {
	var classListPropDesc = {
		  get: classListGetter
		, enumerable: true
		, configurable: true
	};
	try {
		objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
	} catch (ex) { // IE 8 doesn't support enumerable:true
		// adding undefined to fight this issue https://github.com/eligrey/classList.js/issues/36
		// modernie IE8-MSW7 machine has IE8 8.0.6001.18702 and is affected
		if (ex.number === undefined || ex.number === -0x7FF5EC54) {
			classListPropDesc.enumerable = false;
			objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
		}
	}
} else if (objCtr[protoProp].__defineGetter__) {
	elemCtrProto.__defineGetter__(classListProp, classListGetter);
}

}(window.self));

}

// There is full or partial native classList support, so just check if we need
// to normalize the add/remove and toggle APIs.

(function () {
	"use strict";

	var testElement = document.createElement("_");

	testElement.classList.add("c1", "c2");

	// Polyfill for IE 10/11 and Firefox <26, where classList.add and
	// classList.remove exist but support only one argument at a time.
	if (!testElement.classList.contains("c2")) {
		var createMethod = function(method) {
			var original = DOMTokenList.prototype[method];

			DOMTokenList.prototype[method] = function(token) {
				var i, len = arguments.length;

				for (i = 0; i < len; i++) {
					token = arguments[i];
					original.call(this, token);
				}
			};
		};
		createMethod('add');
		createMethod('remove');
	}

	testElement.classList.toggle("c3", false);

	// Polyfill for IE 10 and Firefox <24, where classList.toggle does not
	// support the second argument.
	if (testElement.classList.contains("c3")) {
		var _toggle = DOMTokenList.prototype.toggle;

		DOMTokenList.prototype.toggle = function(token, force) {
			if (1 in arguments && !this.contains(token) === !force) {
				return force;
			} else {
				return _toggle.call(this, token);
			}
		};

	}

	testElement = null;
}());

}


/***/ }),

/***/ "./node_modules/smooth-scroll/dist/smooth-scroll.min.js":
/*!**************************************************************!*\
  !*** ./node_modules/smooth-scroll/dist/smooth-scroll.min.js ***!
  \**************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*! smooth-scroll v14.2.0 | (c) 2018 Chris Ferdinandi | MIT License | http://github.com/cferdinandi/smooth-scroll */
!(function(e,t){ true?!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function(){return t(e)}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)):"object"==typeof exports?module.exports=t(e):e.SmoothScroll=t(e)})("undefined"!=typeof global?global:"undefined"!=typeof window?window:this,(function(e){"use strict";var t={ignore:"[data-scroll-ignore]",header:null,topOnEmptyHash:!0,speed:500,clip:!0,offset:0,easing:"easeInOutCubic",customEasing:null,updateURL:!0,popstate:!0,emitEvents:!0},n=function(){return"querySelector"in document&&"addEventListener"in e&&"requestAnimationFrame"in e&&"closest"in e.Element.prototype},o=function(){for(var e={},t=0;t<arguments.length;t++)!(function(t){for(var n in t)t.hasOwnProperty(n)&&(e[n]=t[n])})(arguments[t]);return e},r=function(t){return!!("matchMedia"in e&&e.matchMedia("(prefers-reduced-motion)").matches)},a=function(t){return parseInt(e.getComputedStyle(t).height,10)},i=function(e){var t;try{t=decodeURIComponent(e)}catch(n){t=e}return t},c=function(e){"#"===e.charAt(0)&&(e=e.substr(1));for(var t,n=String(e),o=n.length,r=-1,a="",i=n.charCodeAt(0);++r<o;){if(0===(t=n.charCodeAt(r)))throw new InvalidCharacterError("Invalid character: the input contains U+0000.");t>=1&&t<=31||127==t||0===r&&t>=48&&t<=57||1===r&&t>=48&&t<=57&&45===i?a+="\\"+t.toString(16)+" ":a+=t>=128||45===t||95===t||t>=48&&t<=57||t>=65&&t<=90||t>=97&&t<=122?n.charAt(r):"\\"+n.charAt(r)}var c;try{c=decodeURIComponent("#"+a)}catch(e){c="#"+a}return c},u=function(e,t){var n;return"easeInQuad"===e.easing&&(n=t*t),"easeOutQuad"===e.easing&&(n=t*(2-t)),"easeInOutQuad"===e.easing&&(n=t<.5?2*t*t:(4-2*t)*t-1),"easeInCubic"===e.easing&&(n=t*t*t),"easeOutCubic"===e.easing&&(n=--t*t*t+1),"easeInOutCubic"===e.easing&&(n=t<.5?4*t*t*t:(t-1)*(2*t-2)*(2*t-2)+1),"easeInQuart"===e.easing&&(n=t*t*t*t),"easeOutQuart"===e.easing&&(n=1- --t*t*t*t),"easeInOutQuart"===e.easing&&(n=t<.5?8*t*t*t*t:1-8*--t*t*t*t),"easeInQuint"===e.easing&&(n=t*t*t*t*t),"easeOutQuint"===e.easing&&(n=1+--t*t*t*t*t),"easeInOutQuint"===e.easing&&(n=t<.5?16*t*t*t*t*t:1+16*--t*t*t*t*t),e.customEasing&&(n=e.customEasing(t)),n||t},s=function(){return Math.max(document.body.scrollHeight,document.documentElement.scrollHeight,document.body.offsetHeight,document.documentElement.offsetHeight,document.body.clientHeight,document.documentElement.clientHeight)},l=function(t,n,o,r){var a=0;if(t.offsetParent)do{a+=t.offsetTop,t=t.offsetParent}while(t);return a=Math.max(a-n-o,0),r&&(a=Math.min(a,s()-e.innerHeight)),a},d=function(e){return e?a(e)+e.offsetTop:0},f=function(e,t,n){t||history.pushState&&n.updateURL&&history.pushState({smoothScroll:JSON.stringify(n),anchor:e.id},document.title,e===document.documentElement?"#top":"#"+e.id)},m=function(t,n,o){0===t&&document.body.focus(),o||(t.focus(),document.activeElement!==t&&(t.setAttribute("tabindex","-1"),t.focus(),t.style.outline="none"),e.scrollTo(0,n))},h=function(t,n,o,r){if(n.emitEvents&&"function"==typeof e.CustomEvent){var a=new CustomEvent(t,{bubbles:!0,detail:{anchor:o,toggle:r}});document.dispatchEvent(a)}};return function(a,p){var g,v,y,S,E,b,O,I={};I.cancelScroll=function(e){cancelAnimationFrame(O),O=null,e||h("scrollCancel",g)},I.animateScroll=function(n,r,a){var i=o(g||t,a||{}),c="[object Number]"===Object.prototype.toString.call(n),p=c||!n.tagName?null:n;if(c||p){var v=e.pageYOffset;i.header&&!S&&(S=document.querySelector(i.header)),E||(E=d(S));var y,b,C,w=c?n:l(p,E,parseInt("function"==typeof i.offset?i.offset(n,r):i.offset,10),i.clip),L=w-v,A=s(),H=0,q=function(t,o){var a=e.pageYOffset;if(t==o||a==o||(v<o&&e.innerHeight+a)>=A)return I.cancelScroll(!0),m(n,o,c),h("scrollStop",i,n,r),y=null,O=null,!0},Q=function(t){y||(y=t),H+=t-y,b=H/parseInt(i.speed,10),b=b>1?1:b,C=v+L*u(i,b),e.scrollTo(0,Math.floor(C)),q(C,w)||(O=e.requestAnimationFrame(Q),y=t)};0===e.pageYOffset&&e.scrollTo(0,0),f(n,c,i),h("scrollStart",i,n,r),I.cancelScroll(!0),e.requestAnimationFrame(Q)}};var C=function(t){if(!r()&&0===t.button&&!t.metaKey&&!t.ctrlKey&&"closest"in t.target&&(y=t.target.closest(a))&&"a"===y.tagName.toLowerCase()&&!t.target.closest(g.ignore)&&y.hostname===e.location.hostname&&y.pathname===e.location.pathname&&/#/.test(y.href)){var n=c(i(y.hash)),o=g.topOnEmptyHash&&"#"===n?document.documentElement:document.querySelector(n);o=o||"#top"!==n?o:document.documentElement,o&&(t.preventDefault(),I.animateScroll(o,y))}},w=function(e){if(history.state.smoothScroll&&history.state.smoothScroll===JSON.stringify(g)&&history.state.anchor){var t=document.querySelector(c(i(history.state.anchor)));t&&I.animateScroll(t,null,{updateURL:!1})}},L=function(e){b||(b=setTimeout((function(){b=null,E=d(S)}),66))};return I.destroy=function(){g&&(document.removeEventListener("click",C,!1),e.removeEventListener("resize",L,!1),e.removeEventListener("popstate",w,!1),I.cancelScroll(),g=null,v=null,y=null,S=null,E=null,b=null,O=null)},I.init=function(r){if(!n())throw"Smooth Scroll: This browser does not support the required JavaScript methods and browser APIs.";I.destroy(),g=o(t,r||{}),S=g.header?document.querySelector(g.header):null,E=d(S),document.addEventListener("click",C,!1),S&&e.addEventListener("resize",L,!1),g.updateURL&&g.popstate&&e.addEventListener("popstate",w,!1)},I.init(p),I}}));
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! ./../../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/spark-md5/spark-md5.js":
/*!*********************************************!*\
  !*** ./node_modules/spark-md5/spark-md5.js ***!
  \*********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

(function (factory) {
    if (true) {
        // Node/CommonJS
        module.exports = factory();
    } else if (typeof define === 'function' && define.amd) {
        // AMD
        define(factory);
    } else {
        // Browser globals (with support for web workers)
        var glob;

        try {
            glob = window;
        } catch (e) {
            glob = self;
        }

        glob.SparkMD5 = factory();
    }
}(function (undefined) {

    'use strict';

    /*
     * Fastest md5 implementation around (JKM md5).
     * Credits: Joseph Myers
     *
     * @see http://www.myersdaily.org/joseph/javascript/md5-text.html
     * @see http://jsperf.com/md5-shootout/7
     */

    /* this function is much faster,
      so if possible we use it. Some IEs
      are the only ones I know of that
      need the idiotic second function,
      generated by an if clause.  */
    var add32 = function (a, b) {
        return (a + b) & 0xFFFFFFFF;
    },
        hex_chr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];


    function cmn(q, a, b, x, s, t) {
        a = add32(add32(a, q), add32(x, t));
        return add32((a << s) | (a >>> (32 - s)), b);
    }

    function md5cycle(x, k) {
        var a = x[0],
            b = x[1],
            c = x[2],
            d = x[3];

        a += (b & c | ~b & d) + k[0] - 680876936 | 0;
        a  = (a << 7 | a >>> 25) + b | 0;
        d += (a & b | ~a & c) + k[1] - 389564586 | 0;
        d  = (d << 12 | d >>> 20) + a | 0;
        c += (d & a | ~d & b) + k[2] + 606105819 | 0;
        c  = (c << 17 | c >>> 15) + d | 0;
        b += (c & d | ~c & a) + k[3] - 1044525330 | 0;
        b  = (b << 22 | b >>> 10) + c | 0;
        a += (b & c | ~b & d) + k[4] - 176418897 | 0;
        a  = (a << 7 | a >>> 25) + b | 0;
        d += (a & b | ~a & c) + k[5] + 1200080426 | 0;
        d  = (d << 12 | d >>> 20) + a | 0;
        c += (d & a | ~d & b) + k[6] - 1473231341 | 0;
        c  = (c << 17 | c >>> 15) + d | 0;
        b += (c & d | ~c & a) + k[7] - 45705983 | 0;
        b  = (b << 22 | b >>> 10) + c | 0;
        a += (b & c | ~b & d) + k[8] + 1770035416 | 0;
        a  = (a << 7 | a >>> 25) + b | 0;
        d += (a & b | ~a & c) + k[9] - 1958414417 | 0;
        d  = (d << 12 | d >>> 20) + a | 0;
        c += (d & a | ~d & b) + k[10] - 42063 | 0;
        c  = (c << 17 | c >>> 15) + d | 0;
        b += (c & d | ~c & a) + k[11] - 1990404162 | 0;
        b  = (b << 22 | b >>> 10) + c | 0;
        a += (b & c | ~b & d) + k[12] + 1804603682 | 0;
        a  = (a << 7 | a >>> 25) + b | 0;
        d += (a & b | ~a & c) + k[13] - 40341101 | 0;
        d  = (d << 12 | d >>> 20) + a | 0;
        c += (d & a | ~d & b) + k[14] - 1502002290 | 0;
        c  = (c << 17 | c >>> 15) + d | 0;
        b += (c & d | ~c & a) + k[15] + 1236535329 | 0;
        b  = (b << 22 | b >>> 10) + c | 0;

        a += (b & d | c & ~d) + k[1] - 165796510 | 0;
        a  = (a << 5 | a >>> 27) + b | 0;
        d += (a & c | b & ~c) + k[6] - 1069501632 | 0;
        d  = (d << 9 | d >>> 23) + a | 0;
        c += (d & b | a & ~b) + k[11] + 643717713 | 0;
        c  = (c << 14 | c >>> 18) + d | 0;
        b += (c & a | d & ~a) + k[0] - 373897302 | 0;
        b  = (b << 20 | b >>> 12) + c | 0;
        a += (b & d | c & ~d) + k[5] - 701558691 | 0;
        a  = (a << 5 | a >>> 27) + b | 0;
        d += (a & c | b & ~c) + k[10] + 38016083 | 0;
        d  = (d << 9 | d >>> 23) + a | 0;
        c += (d & b | a & ~b) + k[15] - 660478335 | 0;
        c  = (c << 14 | c >>> 18) + d | 0;
        b += (c & a | d & ~a) + k[4] - 405537848 | 0;
        b  = (b << 20 | b >>> 12) + c | 0;
        a += (b & d | c & ~d) + k[9] + 568446438 | 0;
        a  = (a << 5 | a >>> 27) + b | 0;
        d += (a & c | b & ~c) + k[14] - 1019803690 | 0;
        d  = (d << 9 | d >>> 23) + a | 0;
        c += (d & b | a & ~b) + k[3] - 187363961 | 0;
        c  = (c << 14 | c >>> 18) + d | 0;
        b += (c & a | d & ~a) + k[8] + 1163531501 | 0;
        b  = (b << 20 | b >>> 12) + c | 0;
        a += (b & d | c & ~d) + k[13] - 1444681467 | 0;
        a  = (a << 5 | a >>> 27) + b | 0;
        d += (a & c | b & ~c) + k[2] - 51403784 | 0;
        d  = (d << 9 | d >>> 23) + a | 0;
        c += (d & b | a & ~b) + k[7] + 1735328473 | 0;
        c  = (c << 14 | c >>> 18) + d | 0;
        b += (c & a | d & ~a) + k[12] - 1926607734 | 0;
        b  = (b << 20 | b >>> 12) + c | 0;

        a += (b ^ c ^ d) + k[5] - 378558 | 0;
        a  = (a << 4 | a >>> 28) + b | 0;
        d += (a ^ b ^ c) + k[8] - 2022574463 | 0;
        d  = (d << 11 | d >>> 21) + a | 0;
        c += (d ^ a ^ b) + k[11] + 1839030562 | 0;
        c  = (c << 16 | c >>> 16) + d | 0;
        b += (c ^ d ^ a) + k[14] - 35309556 | 0;
        b  = (b << 23 | b >>> 9) + c | 0;
        a += (b ^ c ^ d) + k[1] - 1530992060 | 0;
        a  = (a << 4 | a >>> 28) + b | 0;
        d += (a ^ b ^ c) + k[4] + 1272893353 | 0;
        d  = (d << 11 | d >>> 21) + a | 0;
        c += (d ^ a ^ b) + k[7] - 155497632 | 0;
        c  = (c << 16 | c >>> 16) + d | 0;
        b += (c ^ d ^ a) + k[10] - 1094730640 | 0;
        b  = (b << 23 | b >>> 9) + c | 0;
        a += (b ^ c ^ d) + k[13] + 681279174 | 0;
        a  = (a << 4 | a >>> 28) + b | 0;
        d += (a ^ b ^ c) + k[0] - 358537222 | 0;
        d  = (d << 11 | d >>> 21) + a | 0;
        c += (d ^ a ^ b) + k[3] - 722521979 | 0;
        c  = (c << 16 | c >>> 16) + d | 0;
        b += (c ^ d ^ a) + k[6] + 76029189 | 0;
        b  = (b << 23 | b >>> 9) + c | 0;
        a += (b ^ c ^ d) + k[9] - 640364487 | 0;
        a  = (a << 4 | a >>> 28) + b | 0;
        d += (a ^ b ^ c) + k[12] - 421815835 | 0;
        d  = (d << 11 | d >>> 21) + a | 0;
        c += (d ^ a ^ b) + k[15] + 530742520 | 0;
        c  = (c << 16 | c >>> 16) + d | 0;
        b += (c ^ d ^ a) + k[2] - 995338651 | 0;
        b  = (b << 23 | b >>> 9) + c | 0;

        a += (c ^ (b | ~d)) + k[0] - 198630844 | 0;
        a  = (a << 6 | a >>> 26) + b | 0;
        d += (b ^ (a | ~c)) + k[7] + 1126891415 | 0;
        d  = (d << 10 | d >>> 22) + a | 0;
        c += (a ^ (d | ~b)) + k[14] - 1416354905 | 0;
        c  = (c << 15 | c >>> 17) + d | 0;
        b += (d ^ (c | ~a)) + k[5] - 57434055 | 0;
        b  = (b << 21 |b >>> 11) + c | 0;
        a += (c ^ (b | ~d)) + k[12] + 1700485571 | 0;
        a  = (a << 6 | a >>> 26) + b | 0;
        d += (b ^ (a | ~c)) + k[3] - 1894986606 | 0;
        d  = (d << 10 | d >>> 22) + a | 0;
        c += (a ^ (d | ~b)) + k[10] - 1051523 | 0;
        c  = (c << 15 | c >>> 17) + d | 0;
        b += (d ^ (c | ~a)) + k[1] - 2054922799 | 0;
        b  = (b << 21 |b >>> 11) + c | 0;
        a += (c ^ (b | ~d)) + k[8] + 1873313359 | 0;
        a  = (a << 6 | a >>> 26) + b | 0;
        d += (b ^ (a | ~c)) + k[15] - 30611744 | 0;
        d  = (d << 10 | d >>> 22) + a | 0;
        c += (a ^ (d | ~b)) + k[6] - 1560198380 | 0;
        c  = (c << 15 | c >>> 17) + d | 0;
        b += (d ^ (c | ~a)) + k[13] + 1309151649 | 0;
        b  = (b << 21 |b >>> 11) + c | 0;
        a += (c ^ (b | ~d)) + k[4] - 145523070 | 0;
        a  = (a << 6 | a >>> 26) + b | 0;
        d += (b ^ (a | ~c)) + k[11] - 1120210379 | 0;
        d  = (d << 10 | d >>> 22) + a | 0;
        c += (a ^ (d | ~b)) + k[2] + 718787259 | 0;
        c  = (c << 15 | c >>> 17) + d | 0;
        b += (d ^ (c | ~a)) + k[9] - 343485551 | 0;
        b  = (b << 21 | b >>> 11) + c | 0;

        x[0] = a + x[0] | 0;
        x[1] = b + x[1] | 0;
        x[2] = c + x[2] | 0;
        x[3] = d + x[3] | 0;
    }

    function md5blk(s) {
        var md5blks = [],
            i; /* Andy King said do it this way. */

        for (i = 0; i < 64; i += 4) {
            md5blks[i >> 2] = s.charCodeAt(i) + (s.charCodeAt(i + 1) << 8) + (s.charCodeAt(i + 2) << 16) + (s.charCodeAt(i + 3) << 24);
        }
        return md5blks;
    }

    function md5blk_array(a) {
        var md5blks = [],
            i; /* Andy King said do it this way. */

        for (i = 0; i < 64; i += 4) {
            md5blks[i >> 2] = a[i] + (a[i + 1] << 8) + (a[i + 2] << 16) + (a[i + 3] << 24);
        }
        return md5blks;
    }

    function md51(s) {
        var n = s.length,
            state = [1732584193, -271733879, -1732584194, 271733878],
            i,
            length,
            tail,
            tmp,
            lo,
            hi;

        for (i = 64; i <= n; i += 64) {
            md5cycle(state, md5blk(s.substring(i - 64, i)));
        }
        s = s.substring(i - 64);
        length = s.length;
        tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for (i = 0; i < length; i += 1) {
            tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
        }
        tail[i >> 2] |= 0x80 << ((i % 4) << 3);
        if (i > 55) {
            md5cycle(state, tail);
            for (i = 0; i < 16; i += 1) {
                tail[i] = 0;
            }
        }

        // Beware that the final length might not fit in 32 bits so we take care of that
        tmp = n * 8;
        tmp = tmp.toString(16).match(/(.*?)(.{0,8})$/);
        lo = parseInt(tmp[2], 16);
        hi = parseInt(tmp[1], 16) || 0;

        tail[14] = lo;
        tail[15] = hi;

        md5cycle(state, tail);
        return state;
    }

    function md51_array(a) {
        var n = a.length,
            state = [1732584193, -271733879, -1732584194, 271733878],
            i,
            length,
            tail,
            tmp,
            lo,
            hi;

        for (i = 64; i <= n; i += 64) {
            md5cycle(state, md5blk_array(a.subarray(i - 64, i)));
        }

        // Not sure if it is a bug, however IE10 will always produce a sub array of length 1
        // containing the last element of the parent array if the sub array specified starts
        // beyond the length of the parent array - weird.
        // https://connect.microsoft.com/IE/feedback/details/771452/typed-array-subarray-issue
        a = (i - 64) < n ? a.subarray(i - 64) : new Uint8Array(0);

        length = a.length;
        tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for (i = 0; i < length; i += 1) {
            tail[i >> 2] |= a[i] << ((i % 4) << 3);
        }

        tail[i >> 2] |= 0x80 << ((i % 4) << 3);
        if (i > 55) {
            md5cycle(state, tail);
            for (i = 0; i < 16; i += 1) {
                tail[i] = 0;
            }
        }

        // Beware that the final length might not fit in 32 bits so we take care of that
        tmp = n * 8;
        tmp = tmp.toString(16).match(/(.*?)(.{0,8})$/);
        lo = parseInt(tmp[2], 16);
        hi = parseInt(tmp[1], 16) || 0;

        tail[14] = lo;
        tail[15] = hi;

        md5cycle(state, tail);

        return state;
    }

    function rhex(n) {
        var s = '',
            j;
        for (j = 0; j < 4; j += 1) {
            s += hex_chr[(n >> (j * 8 + 4)) & 0x0F] + hex_chr[(n >> (j * 8)) & 0x0F];
        }
        return s;
    }

    function hex(x) {
        var i;
        for (i = 0; i < x.length; i += 1) {
            x[i] = rhex(x[i]);
        }
        return x.join('');
    }

    // In some cases the fast add32 function cannot be used..
    if (hex(md51('hello')) !== '5d41402abc4b2a76b9719d911017c592') {
        add32 = function (x, y) {
            var lsw = (x & 0xFFFF) + (y & 0xFFFF),
                msw = (x >> 16) + (y >> 16) + (lsw >> 16);
            return (msw << 16) | (lsw & 0xFFFF);
        };
    }

    // ---------------------------------------------------

    /**
     * ArrayBuffer slice polyfill.
     *
     * @see https://github.com/ttaubert/node-arraybuffer-slice
     */

    if (typeof ArrayBuffer !== 'undefined' && !ArrayBuffer.prototype.slice) {
        (function () {
            function clamp(val, length) {
                val = (val | 0) || 0;

                if (val < 0) {
                    return Math.max(val + length, 0);
                }

                return Math.min(val, length);
            }

            ArrayBuffer.prototype.slice = function (from, to) {
                var length = this.byteLength,
                    begin = clamp(from, length),
                    end = length,
                    num,
                    target,
                    targetArray,
                    sourceArray;

                if (to !== undefined) {
                    end = clamp(to, length);
                }

                if (begin > end) {
                    return new ArrayBuffer(0);
                }

                num = end - begin;
                target = new ArrayBuffer(num);
                targetArray = new Uint8Array(target);

                sourceArray = new Uint8Array(this, begin, num);
                targetArray.set(sourceArray);

                return target;
            };
        })();
    }

    // ---------------------------------------------------

    /**
     * Helpers.
     */

    function toUtf8(str) {
        if (/[\u0080-\uFFFF]/.test(str)) {
            str = unescape(encodeURIComponent(str));
        }

        return str;
    }

    function utf8Str2ArrayBuffer(str, returnUInt8Array) {
        var length = str.length,
           buff = new ArrayBuffer(length),
           arr = new Uint8Array(buff),
           i;

        for (i = 0; i < length; i += 1) {
            arr[i] = str.charCodeAt(i);
        }

        return returnUInt8Array ? arr : buff;
    }

    function arrayBuffer2Utf8Str(buff) {
        return String.fromCharCode.apply(null, new Uint8Array(buff));
    }

    function concatenateArrayBuffers(first, second, returnUInt8Array) {
        var result = new Uint8Array(first.byteLength + second.byteLength);

        result.set(new Uint8Array(first));
        result.set(new Uint8Array(second), first.byteLength);

        return returnUInt8Array ? result : result.buffer;
    }

    function hexToBinaryString(hex) {
        var bytes = [],
            length = hex.length,
            x;

        for (x = 0; x < length - 1; x += 2) {
            bytes.push(parseInt(hex.substr(x, 2), 16));
        }

        return String.fromCharCode.apply(String, bytes);
    }

    // ---------------------------------------------------

    /**
     * SparkMD5 OOP implementation.
     *
     * Use this class to perform an incremental md5, otherwise use the
     * static methods instead.
     */

    function SparkMD5() {
        // call reset to init the instance
        this.reset();
    }

    /**
     * Appends a string.
     * A conversion will be applied if an utf8 string is detected.
     *
     * @param {String} str The string to be appended
     *
     * @return {SparkMD5} The instance itself
     */
    SparkMD5.prototype.append = function (str) {
        // Converts the string to utf8 bytes if necessary
        // Then append as binary
        this.appendBinary(toUtf8(str));

        return this;
    };

    /**
     * Appends a binary string.
     *
     * @param {String} contents The binary string to be appended
     *
     * @return {SparkMD5} The instance itself
     */
    SparkMD5.prototype.appendBinary = function (contents) {
        this._buff += contents;
        this._length += contents.length;

        var length = this._buff.length,
            i;

        for (i = 64; i <= length; i += 64) {
            md5cycle(this._hash, md5blk(this._buff.substring(i - 64, i)));
        }

        this._buff = this._buff.substring(i - 64);

        return this;
    };

    /**
     * Finishes the incremental computation, reseting the internal state and
     * returning the result.
     *
     * @param {Boolean} raw True to get the raw string, false to get the hex string
     *
     * @return {String} The result
     */
    SparkMD5.prototype.end = function (raw) {
        var buff = this._buff,
            length = buff.length,
            i,
            tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            ret;

        for (i = 0; i < length; i += 1) {
            tail[i >> 2] |= buff.charCodeAt(i) << ((i % 4) << 3);
        }

        this._finish(tail, length);
        ret = hex(this._hash);

        if (raw) {
            ret = hexToBinaryString(ret);
        }

        this.reset();

        return ret;
    };

    /**
     * Resets the internal state of the computation.
     *
     * @return {SparkMD5} The instance itself
     */
    SparkMD5.prototype.reset = function () {
        this._buff = '';
        this._length = 0;
        this._hash = [1732584193, -271733879, -1732584194, 271733878];

        return this;
    };

    /**
     * Gets the internal state of the computation.
     *
     * @return {Object} The state
     */
    SparkMD5.prototype.getState = function () {
        return {
            buff: this._buff,
            length: this._length,
            hash: this._hash
        };
    };

    /**
     * Gets the internal state of the computation.
     *
     * @param {Object} state The state
     *
     * @return {SparkMD5} The instance itself
     */
    SparkMD5.prototype.setState = function (state) {
        this._buff = state.buff;
        this._length = state.length;
        this._hash = state.hash;

        return this;
    };

    /**
     * Releases memory used by the incremental buffer and other additional
     * resources. If you plan to use the instance again, use reset instead.
     */
    SparkMD5.prototype.destroy = function () {
        delete this._hash;
        delete this._buff;
        delete this._length;
    };

    /**
     * Finish the final calculation based on the tail.
     *
     * @param {Array}  tail   The tail (will be modified)
     * @param {Number} length The length of the remaining buffer
     */
    SparkMD5.prototype._finish = function (tail, length) {
        var i = length,
            tmp,
            lo,
            hi;

        tail[i >> 2] |= 0x80 << ((i % 4) << 3);
        if (i > 55) {
            md5cycle(this._hash, tail);
            for (i = 0; i < 16; i += 1) {
                tail[i] = 0;
            }
        }

        // Do the final computation based on the tail and length
        // Beware that the final length may not fit in 32 bits so we take care of that
        tmp = this._length * 8;
        tmp = tmp.toString(16).match(/(.*?)(.{0,8})$/);
        lo = parseInt(tmp[2], 16);
        hi = parseInt(tmp[1], 16) || 0;

        tail[14] = lo;
        tail[15] = hi;
        md5cycle(this._hash, tail);
    };

    /**
     * Performs the md5 hash on a string.
     * A conversion will be applied if utf8 string is detected.
     *
     * @param {String}  str The string
     * @param {Boolean} raw True to get the raw string, false to get the hex string
     *
     * @return {String} The result
     */
    SparkMD5.hash = function (str, raw) {
        // Converts the string to utf8 bytes if necessary
        // Then compute it using the binary function
        return SparkMD5.hashBinary(toUtf8(str), raw);
    };

    /**
     * Performs the md5 hash on a binary string.
     *
     * @param {String}  content The binary string
     * @param {Boolean} raw     True to get the raw string, false to get the hex string
     *
     * @return {String} The result
     */
    SparkMD5.hashBinary = function (content, raw) {
        var hash = md51(content),
            ret = hex(hash);

        return raw ? hexToBinaryString(ret) : ret;
    };

    // ---------------------------------------------------

    /**
     * SparkMD5 OOP implementation for array buffers.
     *
     * Use this class to perform an incremental md5 ONLY for array buffers.
     */
    SparkMD5.ArrayBuffer = function () {
        // call reset to init the instance
        this.reset();
    };

    /**
     * Appends an array buffer.
     *
     * @param {ArrayBuffer} arr The array to be appended
     *
     * @return {SparkMD5.ArrayBuffer} The instance itself
     */
    SparkMD5.ArrayBuffer.prototype.append = function (arr) {
        var buff = concatenateArrayBuffers(this._buff.buffer, arr, true),
            length = buff.length,
            i;

        this._length += arr.byteLength;

        for (i = 64; i <= length; i += 64) {
            md5cycle(this._hash, md5blk_array(buff.subarray(i - 64, i)));
        }

        this._buff = (i - 64) < length ? new Uint8Array(buff.buffer.slice(i - 64)) : new Uint8Array(0);

        return this;
    };

    /**
     * Finishes the incremental computation, reseting the internal state and
     * returning the result.
     *
     * @param {Boolean} raw True to get the raw string, false to get the hex string
     *
     * @return {String} The result
     */
    SparkMD5.ArrayBuffer.prototype.end = function (raw) {
        var buff = this._buff,
            length = buff.length,
            tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            i,
            ret;

        for (i = 0; i < length; i += 1) {
            tail[i >> 2] |= buff[i] << ((i % 4) << 3);
        }

        this._finish(tail, length);
        ret = hex(this._hash);

        if (raw) {
            ret = hexToBinaryString(ret);
        }

        this.reset();

        return ret;
    };

    /**
     * Resets the internal state of the computation.
     *
     * @return {SparkMD5.ArrayBuffer} The instance itself
     */
    SparkMD5.ArrayBuffer.prototype.reset = function () {
        this._buff = new Uint8Array(0);
        this._length = 0;
        this._hash = [1732584193, -271733879, -1732584194, 271733878];

        return this;
    };

    /**
     * Gets the internal state of the computation.
     *
     * @return {Object} The state
     */
    SparkMD5.ArrayBuffer.prototype.getState = function () {
        var state = SparkMD5.prototype.getState.call(this);

        // Convert buffer to a string
        state.buff = arrayBuffer2Utf8Str(state.buff);

        return state;
    };

    /**
     * Gets the internal state of the computation.
     *
     * @param {Object} state The state
     *
     * @return {SparkMD5.ArrayBuffer} The instance itself
     */
    SparkMD5.ArrayBuffer.prototype.setState = function (state) {
        // Convert string to buffer
        state.buff = utf8Str2ArrayBuffer(state.buff, true);

        return SparkMD5.prototype.setState.call(this, state);
    };

    SparkMD5.ArrayBuffer.prototype.destroy = SparkMD5.prototype.destroy;

    SparkMD5.ArrayBuffer.prototype._finish = SparkMD5.prototype._finish;

    /**
     * Performs the md5 hash on an array buffer.
     *
     * @param {ArrayBuffer} arr The array buffer
     * @param {Boolean}     raw True to get the raw string, false to get the hex one
     *
     * @return {String} The result
     */
    SparkMD5.ArrayBuffer.hash = function (arr, raw) {
        var hash = md51_array(new Uint8Array(arr)),
            ret = hex(hash);

        return raw ? hexToBinaryString(ret) : ret;
    };

    return SparkMD5;
}));


/***/ }),

/***/ "./node_modules/stickyfilljs/dist/stickyfill.js":
/*!******************************************************!*\
  !*** ./node_modules/stickyfilljs/dist/stickyfill.js ***!
  \******************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

/*!
  * Stickyfill – `position: sticky` polyfill
  * v. 2.0.5 | https://github.com/wilddeer/stickyfill
  * MIT License
  */

;(function(window, document) {
    'use strict';
    
    /*
     * 1. Check if the browser supports `position: sticky` natively or is too old to run the polyfill.
     *    If either of these is the case set `seppuku` flag. It will be checked later to disable key features
     *    of the polyfill, but the API will remain functional to avoid breaking things.
     */
    
    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
    
    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
    
    var seppuku = false;
    
    // The polyfill cant’t function properly without `getComputedStyle`.
    if (!window.getComputedStyle) seppuku = true;
    // Dont’t get in a way if the browser supports `position: sticky` natively.
    else {
            var testNode = document.createElement('div');
    
            if (['', '-webkit-', '-moz-', '-ms-'].some(function (prefix) {
                try {
                    testNode.style.position = prefix + 'sticky';
                } catch (e) {}
    
                return testNode.style.position != '';
            })) seppuku = true;
        }
    
    /*
     * 2. “Global” vars used across the polyfill
     */
    
    // Check if Shadow Root constructor exists to make further checks simpler
    var shadowRootExists = typeof ShadowRoot !== 'undefined';
    
    // Last saved scroll position
    var scroll = {
        top: null,
        left: null
    };
    
    // Array of created Sticky instances
    var stickies = [];
    
    /*
     * 3. Utility functions
     */
    function extend(targetObj, sourceObject) {
        for (var key in sourceObject) {
            if (sourceObject.hasOwnProperty(key)) {
                targetObj[key] = sourceObject[key];
            }
        }
    }
    
    function parseNumeric(val) {
        return parseFloat(val) || 0;
    }
    
    function getDocOffsetTop(node) {
        var docOffsetTop = 0;
    
        while (node) {
            docOffsetTop += node.offsetTop;
            node = node.offsetParent;
        }
    
        return docOffsetTop;
    }
    
    /*
     * 4. Sticky class
     */
    
    var Sticky = function () {
        function Sticky(node) {
            _classCallCheck(this, Sticky);
    
            if (!(node instanceof HTMLElement)) throw new Error('First argument must be HTMLElement');
            if (stickies.some(function (sticky) {
                return sticky._node === node;
            })) throw new Error('Stickyfill is already applied to this node');
    
            this._node = node;
            this._stickyMode = null;
            this._active = false;
    
            stickies.push(this);
    
            this.refresh();
        }
    
        _createClass(Sticky, [{
            key: 'refresh',
            value: function refresh() {
                if (seppuku || this._removed) return;
                if (this._active) this._deactivate();
    
                var node = this._node;
    
                /*
                 * 1. Save node computed props
                 */
                var nodeComputedStyle = getComputedStyle(node);
                var nodeComputedProps = {
                    top: nodeComputedStyle.top,
                    display: nodeComputedStyle.display,
                    marginTop: nodeComputedStyle.marginTop,
                    marginBottom: nodeComputedStyle.marginBottom,
                    marginLeft: nodeComputedStyle.marginLeft,
                    marginRight: nodeComputedStyle.marginRight,
                    cssFloat: nodeComputedStyle.cssFloat
                };
    
                /*
                 * 2. Check if the node can be activated
                 */
                if (isNaN(parseFloat(nodeComputedProps.top)) || nodeComputedProps.display == 'table-cell' || nodeComputedProps.display == 'none') return;
    
                this._active = true;
    
                /*
                 * 3. Get necessary node parameters
                 */
                var referenceNode = node.parentNode;
                var parentNode = shadowRootExists && referenceNode instanceof ShadowRoot ? referenceNode.host : referenceNode;
                var nodeWinOffset = node.getBoundingClientRect();
                var parentWinOffset = parentNode.getBoundingClientRect();
                var parentComputedStyle = getComputedStyle(parentNode);
    
                this._parent = {
                    node: parentNode,
                    styles: {
                        position: parentNode.style.position
                    },
                    offsetHeight: parentNode.offsetHeight
                };
                this._offsetToWindow = {
                    left: nodeWinOffset.left,
                    right: document.documentElement.clientWidth - nodeWinOffset.right
                };
                this._offsetToParent = {
                    top: nodeWinOffset.top - parentWinOffset.top - parseNumeric(parentComputedStyle.borderTopWidth),
                    left: nodeWinOffset.left - parentWinOffset.left - parseNumeric(parentComputedStyle.borderLeftWidth),
                    right: -nodeWinOffset.right + parentWinOffset.right - parseNumeric(parentComputedStyle.borderRightWidth)
                };
                this._styles = {
                    position: node.style.position,
                    top: node.style.top,
                    bottom: node.style.bottom,
                    left: node.style.left,
                    right: node.style.right,
                    width: node.style.width,
                    marginTop: node.style.marginTop,
                    marginLeft: node.style.marginLeft,
                    marginRight: node.style.marginRight
                };
    
                var nodeTopValue = parseNumeric(nodeComputedProps.top);
                this._limits = {
                    start: nodeWinOffset.top + window.pageYOffset - nodeTopValue,
                    end: parentWinOffset.top + window.pageYOffset + parentNode.offsetHeight - parseNumeric(parentComputedStyle.borderBottomWidth) - node.offsetHeight - nodeTopValue - parseNumeric(nodeComputedProps.marginBottom)
                };
    
                /*
                 * 4. Ensure that the node will be positioned relatively to the parent node
                 */
                var parentPosition = parentComputedStyle.position;
    
                if (parentPosition != 'absolute' && parentPosition != 'relative') {
                    parentNode.style.position = 'relative';
                }
    
                /*
                 * 5. Recalc node position.
                 *    It’s important to do this before clone injection to avoid scrolling bug in Chrome.
                 */
                this._recalcPosition();
    
                /*
                 * 6. Create a clone
                 */
                var clone = this._clone = {};
                clone.node = document.createElement('div');
    
                // Apply styles to the clone
                extend(clone.node.style, {
                    width: nodeWinOffset.right - nodeWinOffset.left + 'px',
                    height: nodeWinOffset.bottom - nodeWinOffset.top + 'px',
                    marginTop: nodeComputedProps.marginTop,
                    marginBottom: nodeComputedProps.marginBottom,
                    marginLeft: nodeComputedProps.marginLeft,
                    marginRight: nodeComputedProps.marginRight,
                    cssFloat: nodeComputedProps.cssFloat,
                    padding: 0,
                    border: 0,
                    borderSpacing: 0,
                    fontSize: '1em',
                    position: 'static'
                });
    
                referenceNode.insertBefore(clone.node, node);
                clone.docOffsetTop = getDocOffsetTop(clone.node);
            }
        }, {
            key: '_recalcPosition',
            value: function _recalcPosition() {
                if (!this._active || this._removed) return;
    
                var stickyMode = scroll.top <= this._limits.start ? 'start' : scroll.top >= this._limits.end ? 'end' : 'middle';
    
                if (this._stickyMode == stickyMode) return;
    
                switch (stickyMode) {
                    case 'start':
                        extend(this._node.style, {
                            position: 'absolute',
                            left: this._offsetToParent.left + 'px',
                            right: this._offsetToParent.right + 'px',
                            top: this._offsetToParent.top + 'px',
                            bottom: 'auto',
                            width: 'auto',
                            marginLeft: 0,
                            marginRight: 0,
                            marginTop: 0
                        });
                        break;
    
                    case 'middle':
                        extend(this._node.style, {
                            position: 'fixed',
                            left: this._offsetToWindow.left + 'px',
                            right: this._offsetToWindow.right + 'px',
                            top: this._styles.top,
                            bottom: 'auto',
                            width: 'auto',
                            marginLeft: 0,
                            marginRight: 0,
                            marginTop: 0
                        });
                        break;
    
                    case 'end':
                        extend(this._node.style, {
                            position: 'absolute',
                            left: this._offsetToParent.left + 'px',
                            right: this._offsetToParent.right + 'px',
                            top: 'auto',
                            bottom: 0,
                            width: 'auto',
                            marginLeft: 0,
                            marginRight: 0
                        });
                        break;
                }
    
                this._stickyMode = stickyMode;
            }
        }, {
            key: '_fastCheck',
            value: function _fastCheck() {
                if (!this._active || this._removed) return;
    
                if (Math.abs(getDocOffsetTop(this._clone.node) - this._clone.docOffsetTop) > 1 || Math.abs(this._parent.node.offsetHeight - this._parent.offsetHeight) > 1) this.refresh();
            }
        }, {
            key: '_deactivate',
            value: function _deactivate() {
                var _this = this;
    
                if (!this._active || this._removed) return;
    
                this._clone.node.parentNode.removeChild(this._clone.node);
                delete this._clone;
    
                extend(this._node.style, this._styles);
                delete this._styles;
    
                // Check whether element’s parent node is used by other stickies.
                // If not, restore parent node’s styles.
                if (!stickies.some(function (sticky) {
                    return sticky !== _this && sticky._parent && sticky._parent.node === _this._parent.node;
                })) {
                    extend(this._parent.node.style, this._parent.styles);
                }
                delete this._parent;
    
                this._stickyMode = null;
                this._active = false;
    
                delete this._offsetToWindow;
                delete this._offsetToParent;
                delete this._limits;
            }
        }, {
            key: 'remove',
            value: function remove() {
                var _this2 = this;
    
                this._deactivate();
    
                stickies.some(function (sticky, index) {
                    if (sticky._node === _this2._node) {
                        stickies.splice(index, 1);
                        return true;
                    }
                });
    
                this._removed = true;
            }
        }]);
    
        return Sticky;
    }();
    
    /*
     * 5. Stickyfill API
     */
    
    
    var Stickyfill = {
        stickies: stickies,
        Sticky: Sticky,
    
        addOne: function addOne(node) {
            // Check whether it’s a node
            if (!(node instanceof HTMLElement)) {
                // Maybe it’s a node list of some sort?
                // Take first node from the list then
                if (node.length && node[0]) node = node[0];else return;
            }
    
            // Check if Stickyfill is already applied to the node
            // and return existing sticky
            for (var i = 0; i < stickies.length; i++) {
                if (stickies[i]._node === node) return stickies[i];
            }
    
            // Create and return new sticky
            return new Sticky(node);
        },
        add: function add(nodeList) {
            // If it’s a node make an array of one node
            if (nodeList instanceof HTMLElement) nodeList = [nodeList];
            // Check if the argument is an iterable of some sort
            if (!nodeList.length) return;
    
            // Add every element as a sticky and return an array of created Sticky instances
            var addedStickies = [];
    
            var _loop = function _loop(i) {
                var node = nodeList[i];
    
                // If it’s not an HTMLElement – create an empty element to preserve 1-to-1
                // correlation with input list
                if (!(node instanceof HTMLElement)) {
                    addedStickies.push(void 0);
                    return 'continue';
                }
    
                // If Stickyfill is already applied to the node
                // add existing sticky
                if (stickies.some(function (sticky) {
                    if (sticky._node === node) {
                        addedStickies.push(sticky);
                        return true;
                    }
                })) return 'continue';
    
                // Create and add new sticky
                addedStickies.push(new Sticky(node));
            };
    
            for (var i = 0; i < nodeList.length; i++) {
                var _ret = _loop(i);
    
                if (_ret === 'continue') continue;
            }
    
            return addedStickies;
        },
        refreshAll: function refreshAll() {
            stickies.forEach(function (sticky) {
                return sticky.refresh();
            });
        },
        removeOne: function removeOne(node) {
            // Check whether it’s a node
            if (!(node instanceof HTMLElement)) {
                // Maybe it’s a node list of some sort?
                // Take first node from the list then
                if (node.length && node[0]) node = node[0];else return;
            }
    
            // Remove the stickies bound to the nodes in the list
            stickies.some(function (sticky) {
                if (sticky._node === node) {
                    sticky.remove();
                    return true;
                }
            });
        },
        remove: function remove(nodeList) {
            // If it’s a node make an array of one node
            if (nodeList instanceof HTMLElement) nodeList = [nodeList];
            // Check if the argument is an iterable of some sort
            if (!nodeList.length) return;
    
            // Remove the stickies bound to the nodes in the list
    
            var _loop2 = function _loop2(i) {
                var node = nodeList[i];
    
                stickies.some(function (sticky) {
                    if (sticky._node === node) {
                        sticky.remove();
                        return true;
                    }
                });
            };
    
            for (var i = 0; i < nodeList.length; i++) {
                _loop2(i);
            }
        },
        removeAll: function removeAll() {
            while (stickies.length) {
                stickies[0].remove();
            }
        }
    };
    
    /*
     * 6. Setup events (unless the polyfill was disabled)
     */
    function init() {
        // Watch for scroll position changes and trigger recalc/refresh if needed
        function checkScroll() {
            if (window.pageXOffset != scroll.left) {
                scroll.top = window.pageYOffset;
                scroll.left = window.pageXOffset;
    
                Stickyfill.refreshAll();
            } else if (window.pageYOffset != scroll.top) {
                scroll.top = window.pageYOffset;
                scroll.left = window.pageXOffset;
    
                // recalc position for all stickies
                stickies.forEach(function (sticky) {
                    return sticky._recalcPosition();
                });
            }
        }
    
        checkScroll();
        window.addEventListener('scroll', checkScroll);
    
        // Watch for window resizes and device orientation changes and trigger refresh
        window.addEventListener('resize', Stickyfill.refreshAll);
        window.addEventListener('orientationchange', Stickyfill.refreshAll);
    
        //Fast dirty check for layout changes every 500ms
        var fastCheckTimer = void 0;
    
        function startFastCheckTimer() {
            fastCheckTimer = setInterval(function () {
                stickies.forEach(function (sticky) {
                    return sticky._fastCheck();
                });
            }, 500);
        }
    
        function stopFastCheckTimer() {
            clearInterval(fastCheckTimer);
        }
    
        var docHiddenKey = void 0;
        var visibilityChangeEventName = void 0;
    
        if ('hidden' in document) {
            docHiddenKey = 'hidden';
            visibilityChangeEventName = 'visibilitychange';
        } else if ('webkitHidden' in document) {
            docHiddenKey = 'webkitHidden';
            visibilityChangeEventName = 'webkitvisibilitychange';
        }
    
        if (visibilityChangeEventName) {
            if (!document[docHiddenKey]) startFastCheckTimer();
    
            document.addEventListener(visibilityChangeEventName, function () {
                if (document[docHiddenKey]) {
                    stopFastCheckTimer();
                } else {
                    startFastCheckTimer();
                }
            });
        } else startFastCheckTimer();
    }
    
    if (!seppuku) init();
    
    /*
     * 7. Expose Stickyfill
     */
    if (typeof module != 'undefined' && module.exports) {
        module.exports = Stickyfill;
    } else {
        window.Stickyfill = Stickyfill;
    }
    
})(window, document);

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZTQ1ODY4NGFiMzkwY2JiZDEyY2MiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz80ZmIwIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWJ1dHRvbi1zcGlubmVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWZpZWxkLWZvY3VzZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kYWwtY29udHJvbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvYmFkZ2UtY29sbGVjdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvY29va2llLW9wdGlvbnMuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvYWN0aW9uLWJhZGdlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2FsZXJ0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2Nvb2tpZS1vcHRpb25zLW1vZGFsLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9pY29uLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2NyYXdsaW5nLWxpc3RlZC10ZXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2xpc3RlZC10ZXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L3ByZXBhcmluZy1saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcm9ncmVzc2luZy1saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9wcm9ncmVzcy1iYXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnRhYmxlLWl0ZW0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvc3VtbWFyeS1zdGF0cy1sYWJlbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLWxpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLXF1ZXVlcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rlc3QtYWxlcnQtY29udGFpbmVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rlc3QtbG9jay11bmxvY2suanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvbGlzdGVkLXRlc3QtY29sbGVjdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvc29ydC1jb250cm9sLWNvbGxlY3Rpb24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL3NvcnRhYmxlLWl0ZW0tbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS9kYXNoYm9hcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGFzay1yZXN1bHRzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtaGlzdG9yeS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LXByb2dyZXNzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLXByZXBhcmluZy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdXNlci1hY2NvdW50LWNhcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdXNlci1hY2NvdW50LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wb2x5ZmlsbC9jdXN0b20tZXZlbnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BvbHlmaWxsL29iamVjdC1lbnRyaWVzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zY3JvbGwtdG8uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL2FsZXJ0LWZhY3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWNsaWVudC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2VydmljZXMvbGlzdGVkLXRlc3QtZmFjdG9yeS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2VydmljZXMvcG9zaXRpb24tc3RpY2t5LWRldGVjdG9yLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9zdHJpcGUtaGFuZGxlci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2VydmljZXMvdGVzdC1yZXN1bHQtcmV0cmlldmVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90YXNrLXJlc3VsdHMvZmlsdGVyYWJsZS1pc3N1ZS1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90YXNrLXJlc3VsdHMvZml4LWxpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rhc2stcmVzdWx0cy9pc3N1ZS1jb250ZW50LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90YXNrLXJlc3VsdHMvaXNzdWUtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGFzay1yZXN1bHRzL2lzc3VlLXNlY3Rpb24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rhc2stcmVzdWx0cy9zdW1tYXJ5LXN0YXRzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LWhpc3RvcnkvbW9kYWwuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtaGlzdG9yeS9zdWdnZXN0aW9ucy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy9zdW1tYXJ5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2staWQtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy90YXNrLWxpc3QtcGFnaW5hdG9yLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZS9ieS1lcnJvci1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZS9zb3J0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91bmF2YWlsYWJsZS10YXNrLXR5cGUtbW9kYWwtbGF1bmNoZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0tdmFsaWRhdG9yLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQtY2FyZC9mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWFuY2hvci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1hY2NvdW50L25hdmJhci1pdGVtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWxpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2F3ZXNvbXBsZXRlL2F3ZXNvbXBsZXRlLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9ib290c3RyYXAubmF0aXZlL2Rpc3QvYm9vdHN0cmFwLW5hdGl2ZS5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvY2xhc3NsaXN0LXBvbHlmaWxsL3NyYy9pbmRleC5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvc21vb3RoLXNjcm9sbC9kaXN0L3Ntb290aC1zY3JvbGwubWluLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9zcGFyay1tZDUvc3BhcmstbWQ1LmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzIiwid2VicGFjazovLy8od2VicGFjaykvYnVpbGRpbi9nbG9iYWwuanMiXSwibmFtZXMiOlsicmVxdWlyZSIsImZvcm1CdXR0b25TcGlubmVyIiwiZm9ybUZpZWxkRm9jdXNlciIsIm1vZGFsQ29udHJvbCIsImNvbGxhcHNlQ29udHJvbENhcmV0IiwiRGFzaGJvYXJkIiwidGVzdEhpc3RvcnlQYWdlIiwiVGVzdFJlc3VsdHMiLCJVc2VyQWNjb3VudCIsIlVzZXJBY2NvdW50Q2FyZCIsIkFsZXJ0RmFjdG9yeSIsIlRlc3RQcm9ncmVzcyIsIlRlc3RSZXN1bHRzUHJlcGFyaW5nIiwiVGVzdFJlc3VsdHNCeVRhc2tUeXBlIiwiVGFza1Jlc3VsdHMiLCJvbkRvbUNvbnRlbnRMb2FkZWQiLCJib2R5IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsIml0ZW0iLCJmb2N1c2VkRmllbGQiLCJxdWVyeVNlbGVjdG9yIiwiZm9yRWFjaCIsImNhbGwiLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9ybUVsZW1lbnQiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsImRhc2hib2FyZCIsImluaXQiLCJ0ZXN0UHJvZ3Jlc3MiLCJ0ZXN0UmVzdWx0cyIsInRhc2tSZXN1bHRzIiwidGVzdFJlc3VsdHNCeVRhc2tUeXBlIiwidGVzdFJlc3VsdHNQcmVwYXJpbmciLCJ1c2VyQWNjb3VudCIsIndpbmRvdyIsInVzZXJBY2NvdW50Q2FyZCIsImFsZXJ0RWxlbWVudCIsImNyZWF0ZUZyb21FbGVtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsIm1vZHVsZSIsImV4cG9ydHMiLCJjb250cm9scyIsImNvbnRyb2xJY29uQ2xhc3MiLCJjYXJldFVwQ2xhc3MiLCJjYXJldERvd25DbGFzcyIsImNvbnRyb2xDb2xsYXBzZWRDbGFzcyIsImNyZWF0ZUNvbnRyb2xJY29uIiwiY29udHJvbCIsImNvbnRyb2xJY29uIiwiY3JlYXRlRWxlbWVudCIsImFkZCIsImhhc0F0dHJpYnV0ZSIsImdldEF0dHJpYnV0ZSIsInRvZ2dsZUNhcmV0IiwicmVtb3ZlIiwiaGFuZGxlQ29udHJvbCIsImV2ZW50TmFtZVNob3duIiwiZXZlbnROYW1lSGlkZGVuIiwiY29sbGFwc2libGVFbGVtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJyZXBsYWNlIiwiYXBwZW5kIiwic2hvd25IaWRkZW5FdmVudExpc3RlbmVyIiwiYmluZCIsImkiLCJsZW5ndGgiLCJMaXN0ZWRUZXN0Q29sbGVjdGlvbiIsIkh0dHBDbGllbnQiLCJSZWNlbnRUZXN0TGlzdCIsImVsZW1lbnQiLCJvd25lckRvY3VtZW50Iiwic291cmNlVXJsIiwibGlzdGVkVGVzdENvbGxlY3Rpb24iLCJyZXRyaWV2ZWRMaXN0ZWRUZXN0Q29sbGVjdGlvbiIsImdldFJldHJpZXZlZEV2ZW50TmFtZSIsIl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIiLCJfcmV0cmlldmVUZXN0cyIsImV2ZW50IiwiX3BhcnNlUmV0cmlldmVkVGVzdHMiLCJkZXRhaWwiLCJyZXNwb25zZSIsIl9yZW5kZXJSZXRyaWV2ZWRUZXN0cyIsInNldFRpbWVvdXQiLCJfcmVtb3ZlU3Bpbm5lciIsImxpc3RlZFRlc3QiLCJwYXJlbnROb2RlIiwicmVtb3ZlQ2hpbGQiLCJyZXRyaWV2ZWRMaXN0ZWRUZXN0IiwiZ2V0IiwiZ2V0VGVzdElkIiwiZ2V0VHlwZSIsInJlcGxhY2VDaGlsZCIsImVuYWJsZSIsInJlbmRlckZyb21MaXN0ZWRUZXN0IiwiaW5zZXJ0QWRqYWNlbnRFbGVtZW50IiwicmV0cmlldmVkVGVzdHNNYXJrdXAiLCJ0cmltIiwicmV0cmlldmVkVGVzdENvbnRhaW5lciIsImlubmVySFRNTCIsImNyZWF0ZUZyb21Ob2RlTGlzdCIsImdldFRleHQiLCJzcGlubmVyIiwiRm9ybUJ1dHRvbiIsIkh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCIsIlRlc3RTdGFydEZvcm0iLCJzdWJtaXRCdXR0b25zIiwiaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsIiwic3VibWl0QnV0dG9uIiwicHVzaCIsIl9zdWJtaXRFdmVudExpc3RlbmVyIiwiX3N1Ym1pdEJ1dHRvbkV2ZW50TGlzdGVuZXIiLCJkZUVtcGhhc2l6ZSIsIl9yZXBsYWNlVW5jaGVja2VkQ2hlY2tib3hlc1dpdGhIaWRkZW5GaWVsZHMiLCJkaXNhYmxlIiwiYnV0dG9uRWxlbWVudCIsInRhcmdldCIsImJ1dHRvbiIsIm1hcmtBc0J1c3kiLCJpbnB1dCIsImNoZWNrZWQiLCJoaWRkZW5JbnB1dCIsInNldEF0dHJpYnV0ZSIsInZhbHVlIiwiZm9ybSIsImlucHV0VmFsdWUiLCJmb2N1cyIsImlucHV0RWxlbWVudCIsInJlbW92ZUF0dHJpYnV0ZSIsImNvbnRyb2xFbGVtZW50cyIsImluaXRpYWxpemUiLCJjb250cm9sRWxlbWVudCIsIkJhZGdlQ29sbGVjdGlvbiIsImJhZGdlcyIsImdyZWF0ZXN0V2lkdGgiLCJfZGVyaXZlR3JlYXRlc3RXaWR0aCIsImJhZGdlIiwic3R5bGUiLCJ3aWR0aCIsIm9mZnNldFdpZHRoIiwiQ29va2llT3B0aW9uc01vZGFsIiwiQ29va2llT3B0aW9ucyIsImNvb2tpZU9wdGlvbnNNb2RhbCIsImFjdGlvbkJhZGdlIiwic3RhdHVzRWxlbWVudCIsImlzQWNjb3VudFJlcXVpcmVkTW9kYWwiLCJtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciIsImlzRW1wdHkiLCJpbm5lclRleHQiLCJtYXJrTm90RW5hYmxlZCIsIm1hcmtFbmFibGVkIiwiZ2V0T3BlbmVkRXZlbnROYW1lIiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwiZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUiLCJnZXRDbG9zZWRFdmVudE5hbWUiLCJnZXRNb2RhbENsb3NlZEV2ZW50TmFtZSIsIkFjdGlvbkJhZGdlIiwiYnNuIiwiQWxlcnQiLCJjbG9zZUJ1dHRvbiIsIl9jbG9zZUJ1dHRvbkNsaWNrRXZlbnRIYW5kbGVyIiwiX3JlbW92ZVByZXNlbnRhdGlvbmFsQ2xhc3NlcyIsImNvbnRhaW5lciIsImFwcGVuZENoaWxkIiwicHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCIsImNsYXNzTmFtZSIsImluZGV4IiwiaW5kZXhPZiIsInJlbGF0ZWRGaWVsZElkIiwicmVsYXRlZEZpZWxkIiwiYnNuQWxlcnQiLCJjbG9zZSIsImFkZEJ1dHRvbiIsImFwcGx5QnV0dG9uIiwiX2FkZFJlbW92ZUFjdGlvbnMiLCJfYWRkRXZlbnRMaXN0ZW5lcnMiLCJjb29raWVEYXRhUm93Q291bnQiLCJ0YWJsZUJvZHkiLCJyZW1vdmVBY3Rpb24iLCJ0YWJsZVJvdyIsIm5hbWVJbnB1dCIsInR5cGUiLCJrZXkiLCJjbGljayIsInNob3duRXZlbnRMaXN0ZW5lciIsInByZXZpb3VzVGFibGVCb2R5IiwiY2xvbmVOb2RlIiwiaGlkZGVuRXZlbnRMaXN0ZW5lciIsImNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwiYWRkQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwiX2NyZWF0ZVRhYmxlUm93IiwiX2NyZWF0ZVJlbW92ZUFjdGlvbiIsIl9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJfaW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lciIsInJlbW92ZUNvbnRhaW5lciIsIl9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdmVBY3Rpb25Db250YWluZXIiLCJuZXh0Q29va2llSW5kZXgiLCJsYXN0VGFibGVSb3ciLCJ2YWx1ZUlucHV0IiwicGFyc2VJbnQiLCJJY29uIiwiaWNvbkVsZW1lbnQiLCJnZXRTZWxlY3RvciIsImljb24iLCJzZXRCdXN5Iiwic2V0QXZhaWxhYmxlIiwidW5EZUVtcGhhc2l6ZSIsInNldFN1Y2Nlc3NmdWwiLCJ1c2VybmFtZUlucHV0IiwicGFzc3dvcmRJbnB1dCIsImNsZWFyQnV0dG9uIiwicHJldmlvdXNVc2VybmFtZSIsInByZXZpb3VzUGFzc3dvcmQiLCJ1c2VybmFtZSIsInBhc3N3b3JkIiwiZm9jdXNlZElucHV0IiwiY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzIiwiYWN0aXZlSWNvbkNsYXNzIiwiY2xhc3Nlc1RvUmV0YWluIiwiZ2V0Q2xhc3MiLCJpbmNsdWRlcyIsIlByb2dyZXNzaW5nTGlzdGVkVGVzdCIsIkNyYXdsaW5nTGlzdGVkVGVzdCIsIkxpc3RlZFRlc3QiLCJpc0ZpbmlzaGVkIiwiZ2V0U3RhdGUiLCJUZXN0UmVzdWx0UmV0cmlldmVyIiwiUHJlcGFyaW5nTGlzdGVkVGVzdCIsIl9pbml0aWFsaXNlUmVzdWx0UmV0cmlldmVyIiwicHJlcGFyaW5nRWxlbWVudCIsInRlc3RSZXN1bHRzUmV0cmlldmVyIiwicmV0cmlldmVkRXZlbnQiLCJQcm9ncmVzc0JhciIsInByb2dyZXNzQmFyRWxlbWVudCIsInByb2dyZXNzQmFyIiwiY29tcGxldGlvblBlcmNlbnQiLCJwYXJzZUZsb2F0IiwiZ2V0Q29tcGxldGlvblBlcmNlbnQiLCJzZXRDb21wbGV0aW9uUGVyY2VudCIsIlNvcnRDb250cm9sIiwia2V5cyIsIkpTT04iLCJwYXJzZSIsIl9jbGlja0V2ZW50TGlzdGVuZXIiLCJnZXRTb3J0UmVxdWVzdGVkRXZlbnROYW1lIiwiU29ydGFibGVJdGVtIiwic29ydFZhbHVlcyIsIlNjcm9sbFRvIiwiU3VtbWFyeVN0YXRzTGFiZWwiLCJocmVmIiwiY291bnQiLCJpc3N1ZVR5cGUiLCJwcmV2ZW50RGVmYXVsdCIsImFuY2hvckVsZW1lbnQiLCJwYXRoIiwicGF0aEVsZW1lbnQiLCJub2RlTmFtZSIsInRhcmdldElkIiwic2Nyb2xsVG8iLCJUYXNrIiwiVGFza0xpc3QiLCJwYWdlSW5kZXgiLCJ0YXNrcyIsInRhc2tFbGVtZW50IiwidGFzayIsImdldElkIiwic3RhdGVzIiwic3RhdGVzTGVuZ3RoIiwic3RhdGVJbmRleCIsInN0YXRlIiwiY29uY2F0IiwiZ2V0VGFza3NCeVN0YXRlIiwiT2JqZWN0IiwidGFza0lkIiwidXBkYXRlZFRhc2tMaXN0IiwidXBkYXRlZFRhc2siLCJ1cGRhdGVGcm9tVGFzayIsIlRhc2tRdWV1ZSIsImxhYmVsIiwiVGFza1F1ZXVlcyIsInF1ZXVlcyIsInF1ZXVlRWxlbWVudCIsInF1ZXVlIiwiZ2V0UXVldWVJZCIsInRhc2tDb3VudCIsInRhc2tDb3VudEJ5U3RhdGUiLCJnZXRXaWR0aEZvclN0YXRlIiwiaGFzT3duUHJvcGVydHkiLCJNYXRoIiwiY2VpbCIsInNldFZhbHVlIiwic2V0V2lkdGgiLCJUZXN0QWxlcnRDb250YWluZXIiLCJhbGVydCIsImNyZWF0ZUZyb21Db250ZW50Iiwic2V0U3R5bGUiLCJ3cmFwQ29udGVudEluQ29udGFpbmVyIiwiVGVzdExvY2tVbmxvY2siLCJkYXRhIiwibG9ja2VkIiwidW5sb2NrZWQiLCJhY3Rpb24iLCJkZXNjcmlwdGlvbiIsIl9yZW5kZXIiLCJfdG9nZ2xlIiwic3RhdGVEYXRhIiwicG9zdCIsInVybCIsIkh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMiLCJMaXN0ZWRUZXN0RmFjdG9yeSIsImxpc3RlZFRlc3RzIiwiY29udGFpbnNUZXN0SWQiLCJ0ZXN0SWQiLCJjYWxsYmFjayIsIm5vZGVMaXN0IiwiY29sbGVjdGlvbiIsImxpc3RlZFRlc3RFbGVtZW50IiwiU29ydENvbnRyb2xDb2xsZWN0aW9uIiwic2V0U29ydGVkIiwic2V0Tm90U29ydGVkIiwiU29ydGFibGVJdGVtTGlzdCIsIml0ZW1zIiwic29ydGVkSXRlbXMiLCJzb3J0YWJsZUl0ZW0iLCJwb3NpdGlvbiIsInZhbHVlcyIsImdldFNvcnRWYWx1ZSIsIk51bWJlciIsImlzSW50ZWdlciIsInRvU3RyaW5nIiwiam9pbiIsInNvcnQiLCJfY29tcGFyZUZ1bmN0aW9uIiwiaW5kZXhJdGVtIiwiYSIsImIiLCJ1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlciIsIkh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5IiwiQ29va2llT3B0aW9uc0ZhY3RvcnkiLCJ0ZXN0U3RhcnRGb3JtIiwicmVjZW50VGVzdExpc3QiLCJodHRwQXV0aGVudGljYXRpb25PcHRpb25zIiwiY3JlYXRlIiwiY29va2llT3B0aW9ucyIsIklzc3VlU2VjdGlvbiIsIlN1bW1hcnlTdGF0cyIsIklzc3VlQ29udGVudCIsInN1bW1hcnlTdGF0cyIsImlzc3VlQ29udGVudCIsInN1bW1hcnlTdGF0c0VsZW1lbnQiLCJpc3N1ZVNlY3Rpb25zIiwiaXNzdWVTZWN0aW9uIiwiZ2V0SXNzdWVDb3VudENoYW5nZWRFdmVudE5hbWUiLCJfZmlsdGVyZWRJc3N1ZVNlY3Rpb25Jc3N1ZUNvdW50Q2hhbmdlZEV2ZW50TGlzdGVuZXIiLCJzZXRJc3N1ZUNvdW50IiwiX2NyZWF0ZUZpbHRlck5vdGljZSIsImdyb3VwZWRJc3N1ZXNFbGVtZW50IiwiZXJyb3JHcm91cEVsZW1lbnQiLCJpc3N1ZUNvdW50IiwiZmlyc3RGaWx0ZXJlZElzc3VlIiwiZ2V0Rmlyc3RGaWx0ZXJlZElzc3VlIiwiZml4RWxlbWVudCIsImZpeElzc3VlU2VjdGlvbiIsImdldElzc3VlU2VjdGlvbiIsImZpeExpc3QiLCJpc3N1ZUxpc3RzIiwiZmlsdGVyVG8iLCJmaXhDb3VudCIsInJlbmRlcklzc3VlQ291bnQiLCJnZXRGaWx0ZXJlZElzc3VlTWVzc2FnZSIsIk1vZGFsIiwiU3VnZ2VzdGlvbnMiLCJtb2RhbElkIiwiZmlsdGVyT3B0aW9uc1NlbGVjdG9yIiwibW9kYWxFbGVtZW50IiwiZmlsdGVyT3B0aW9uc0VsZW1lbnQiLCJtb2RhbCIsInN1Z2dlc3Rpb25zIiwic3VnZ2VzdGlvbnNMb2FkZWRFdmVudExpc3RlbmVyIiwic2V0U3VnZ2VzdGlvbnMiLCJmaWx0ZXJDaGFuZ2VkRXZlbnRMaXN0ZW5lciIsImZpbHRlciIsInNlYXJjaCIsImN1cnJlbnRTZWFyY2giLCJsb2NhdGlvbiIsImxvYWRlZEV2ZW50TmFtZSIsImZpbHRlckNoYW5nZWRFdmVudE5hbWUiLCJyZXRyaWV2ZSIsIlN1bW1hcnkiLCJUYXNrTGlzdFBhZ2luYXRpb24iLCJwYWdlTGVuZ3RoIiwic3VtbWFyeSIsInRhc2tMaXN0RWxlbWVudCIsInRhc2tMaXN0IiwiYWxlcnRDb250YWluZXIiLCJ0YXNrTGlzdFBhZ2luYXRpb24iLCJ0YXNrTGlzdElzSW5pdGlhbGl6ZWQiLCJzdW1tYXJ5RGF0YSIsIl9yZWZyZXNoU3VtbWFyeSIsImdldFJlbmRlckFtbWVuZG1lbnRFdmVudE5hbWUiLCJyZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbiIsImdldEluaXRpYWxpemVkRXZlbnROYW1lIiwiX3Rhc2tMaXN0SW5pdGlhbGl6ZWRFdmVudExpc3RlbmVyIiwiZ2V0U2VsZWN0UGFnZUV2ZW50TmFtZSIsInNldEN1cnJlbnRQYWdlSW5kZXgiLCJzZWxlY3RQYWdlIiwicmVuZGVyIiwiZ2V0U2VsZWN0UHJldmlvdXNQYWdlRXZlbnROYW1lIiwibWF4IiwiY3VycmVudFBhZ2VJbmRleCIsImdldFNlbGVjdE5leHRQYWdlRXZlbnROYW1lIiwibWluIiwicGFnZUNvdW50IiwicmVxdWVzdElkIiwicmVxdWVzdCIsInJlc3BvbnNlVVJMIiwiY29uc29sZSIsImxvZyIsInRlc3QiLCJ0YXNrX2NvdW50IiwiaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyIsIl9zZXRCb2R5Sm9iQ2xhc3MiLCJpc0luaXRpYWxpemluZyIsImlzUmVxdWlyZWQiLCJpc1JlbmRlcmVkIiwiX2NyZWF0ZVBhZ2luYXRpb25FbGVtZW50Iiwic2V0UGFnaW5hdGlvbkVsZW1lbnQiLCJfYWRkUGFnaW5hdGlvbkV2ZW50TGlzdGVuZXJzIiwiY3JlYXRlTWFya3VwIiwic3VtbWFyeVVybCIsIm5vdyIsIkRhdGUiLCJnZXRKc29uIiwiZ2V0VGltZSIsImJvZHlDbGFzc0xpc3QiLCJ0ZXN0U3RhdGUiLCJqb2JDbGFzc1ByZWZpeCIsIkJ5UGFnZUxpc3QiLCJCeUVycm9yTGlzdCIsImJ5RXJyb3JDb250YWluZXJFbGVtZW50IiwiYnlQYWdlTGlzdCIsImJ5RXJyb3JMaXN0IiwidW5yZXRyaWV2ZWRSZW1vdGVUYXNrSWRzVXJsIiwicmVzdWx0c1JldHJpZXZlVXJsIiwicmV0cmlldmFsU3RhdHNVcmwiLCJyZXN1bHRzVXJsIiwiY29tcGxldGlvblBlcmNlbnRWYWx1ZSIsImxvY2FsVGFza0NvdW50IiwicmVtb3RlVGFza0NvdW50IiwiX2NoZWNrQ29tcGxldGlvblN0YXR1cyIsIl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uIiwiX3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24iLCJfcmV0cmlldmVSZXRyaWV2YWxTdGF0cyIsImNvbXBsZXRpb25fcGVyY2VudCIsInJlbWFpbmluZ190YXNrc190b19yZXRyaWV2ZV9jb3VudCIsImxvY2FsX3Rhc2tfY291bnQiLCJyZW1vdGVfdGFza19jb3VudCIsInJlbW90ZVRhc2tJZHMiLCJyZXRlc3RGb3JtIiwicmV0ZXN0QnV0dG9uIiwidGFza1R5cGVTdW1tYXJ5QmFkZ2VDb2xsZWN0aW9uIiwidGVzdExvY2tVbmxvY2tFbGVtZW50IiwidGVzdExvY2tVbmxvY2siLCJhcHBseVVuaWZvcm1XaWR0aCIsIkZvcm0iLCJGb3JtVmFsaWRhdG9yIiwiU3RyaXBlSGFuZGxlciIsInN0cmlwZUpzIiwiU3RyaXBlIiwiZm9ybVZhbGlkYXRvciIsInN0cmlwZUhhbmRsZXIiLCJzZXRTdHJpcGVQdWJsaXNoYWJsZUtleSIsImdldFN0cmlwZVB1Ymxpc2hhYmxlS2V5IiwidXBkYXRlQ2FyZCIsImdldFVwZGF0ZUNhcmRFdmVudE5hbWUiLCJjcmVhdGVDYXJkVG9rZW5TdWNjZXNzIiwiZ2V0Q3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TmFtZSIsImNyZWF0ZUNhcmRUb2tlbkZhaWx1cmUiLCJnZXRDcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnROYW1lIiwiX3VwZGF0ZUNhcmRFdmVudExpc3RlbmVyIiwiX2NyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudExpc3RlbmVyIiwiX2NyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudExpc3RlbmVyIiwidXBkYXRlQ2FyZEV2ZW50IiwiY3JlYXRlQ2FyZFRva2VuIiwic3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQiLCJyZXF1ZXN0VXJsIiwicGF0aG5hbWUiLCJpZCIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsInJlc3BvbnNlVHlwZSIsInNldFJlcXVlc3RIZWFkZXIiLCJtYXJrQXNBdmFpbGFibGUiLCJtYXJrU3VjY2VlZGVkIiwidGhpc191cmwiLCJoYW5kbGVSZXNwb25zZUVycm9yIiwidXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtIiwidXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX21lc3NhZ2UiLCJzZW5kIiwicmVzcG9uc2VFcnJvciIsImNyZWF0ZVJlc3BvbnNlRXJyb3IiLCJlcnJvciIsInBhcmFtIiwiU2Nyb2xsU3B5IiwiTmF2QmFyTGlzdCIsIlN0aWNreUZpbGwiLCJQb3NpdGlvblN0aWNreURldGVjdG9yIiwic2Nyb2xsT2Zmc2V0Iiwic2Nyb2xsU3B5T2Zmc2V0Iiwic2lkZU5hdkVsZW1lbnQiLCJuYXZCYXJMaXN0Iiwic2Nyb2xsc3B5IiwiaGFzaCIsInJlbGF0ZWRBbmNob3IiLCJnb1RvIiwic3RpY2t5TmF2SnNDbGFzcyIsInN0aWNreU5hdkpzU2VsZWN0b3IiLCJzdGlja3lOYXYiLCJzdXBwb3J0c1Bvc2l0aW9uU3RpY2t5IiwiYWRkT25lIiwic3B5IiwiX2FwcGx5UG9zaXRpb25TdGlja3lQb2x5ZmlsbCIsIl9hcHBseUluaXRpYWxTY3JvbGwiLCJwYXJhbXMiLCJidWJibGVzIiwiY2FuY2VsYWJsZSIsInVuZGVmaW5lZCIsImN1c3RvbUV2ZW50IiwiY3JlYXRlRXZlbnQiLCJpbml0Q3VzdG9tRXZlbnQiLCJwcm90b3R5cGUiLCJFdmVudCIsImVudHJpZXMiLCJvYmoiLCJvd25Qcm9wcyIsInJlc0FycmF5IiwiQXJyYXkiLCJTbW9vdGhTY3JvbGwiLCJvZmZzZXQiLCJzY3JvbGwiLCJhbmltYXRlU2Nyb2xsIiwib2Zmc2V0VG9wIiwiX3VwZGF0ZUhpc3RvcnkiLCJoaXN0b3J5IiwicHVzaFN0YXRlIiwiZXJyb3JDb250ZW50IiwiZWxlbWVudElubmVySFRNTCIsIm1ldGhvZCIsInJlcXVlc3RIZWFkZXJzIiwicmVhbFJlcXVlc3RIZWFkZXJzIiwiZWxlbWVudFN0eWxlIiwiY3NzVGV4dCIsInN0cmlwZVB1Ymxpc2hhYmxlS2V5Iiwic2V0UHVibGlzaGFibGVLZXkiLCJjYXJkIiwiY3JlYXRlVG9rZW4iLCJzdGF0dXMiLCJpc0Vycm9yUmVzcG9uc2UiLCJldmVudE5hbWUiLCJzdGF0dXNVcmwiLCJ1bnJldHJpZXZlZFRhc2tJZHNVcmwiLCJyZXRyaWV2ZVRhc2tzVXJsIiwiX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzIiwiX3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5IiwiX2Rpc3BsYXlQcmVwYXJpbmdTdW1tYXJ5IiwiX3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24iLCJfcmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24iLCJyZXRyaWV2ZWRTdW1tYXJ5Q29udGFpbmVyIiwic3RhdHVzRGF0YSIsIl9jcmVhdGVQcmVwYXJpbmdTdW1tYXJ5IiwiSXNzdWVMaXN0IiwiU3BhcmtNRDUiLCJGaWx0ZXJhYmxlSXNzdWVMaXN0IiwiZmlsdGVyU2VsZWN0b3IiLCJoYXNoSWRQcmVmaXgiLCJpc3N1ZXMiLCJpc3N1ZUVsZW1lbnQiLCJlcnJvck1lc3NhZ2UiLCJ0ZXh0Q29udGVudCIsImVycm9yTWVzc2FnZUhhc2giLCJfZ2VuZXJhdGVVbmlxdWVJZCIsInBhcmVudEVsZW1lbnQiLCJmaXJzdElzc3VlIiwiZ2V0Rmlyc3QiLCJzZWVkIiwib3JpZ2luYWxTZWVkIiwic2VlZFN1ZmZpeCIsIkZpeExpc3QiLCJmaXhIcmVmIiwiaXNzdWVTZWN0aW9uRWxlbWVudCIsImlzRmlsdGVyYWJsZSIsImN1cnJlbnRJc3N1ZVNlY3Rpb24iLCJmaWx0ZXJlZElzc3VlTWVzc2FnZSIsImZpbHRlcmVkSXNzdWVTZWN0aW9uIiwiaXNGaWx0ZXJlZCIsImdldEZpcnN0SXNzdWVNZXNzYWdlIiwiZ2V0Rmlyc3RJc3N1ZSIsImlzc3VlQ291bnRFbGVtZW50IiwiaXNzdWVzRWxlbWVudCIsImlzc3VlTGlzdCIsImFkZEhhc2hJZEF0dHJpYnV0ZVRvSXNzdWVzIiwiZmlsdGVyZWRJc3N1ZUNvdW50IiwiZmlyc3RJc3N1ZU1lc3NhZ2UiLCJpc3N1ZUxpc3RGaXJzdE1lc3NhZ2UiLCJnZXRGaXJzdE1lc3NhZ2UiLCJpc3N1ZUxpc3RGaXJzdElzc3VlIiwibGFiZWxzIiwibGFiZWxFbGVtZW50IiwiZ2V0SXNzdWVUeXBlIiwic2V0Q291bnQiLCJwcmV2aW91c0ZpbHRlciIsImFwcGx5RmlsdGVyIiwiYXdlc29tZXBsZXRlIiwiQXdlc29tcGxldGUiLCJsaXN0IiwiaGlkZUV2ZW50TGlzdGVuZXIiLCJXSUxEQ0FSRCIsImZpbHRlcklzRW1wdHkiLCJzdWdnZXN0aW9uc0luY2x1ZGVzRmlsdGVyIiwiZmlsdGVySXNXaWxkY2FyZFByZWZpeGVkIiwiY2hhckF0IiwiZmlsdGVySXNXaWxkY2FyZFN1ZmZpeGVkIiwic2xpY2UiLCJhcHBseUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciIsInJlcXVlc3RPbmxvYWRIYW5kbGVyIiwicmVzcG9uc2VUZXh0Iiwib25sb2FkIiwiY2FuY2VsQWN0aW9uIiwiY2FuY2VsQ3Jhd2xBY3Rpb24iLCJzdGF0ZUxhYmVsIiwidGFza1F1ZXVlcyIsIl9jYW5jZWxBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJfY2FuY2VsQ3Jhd2xBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJzdGF0ZV9sYWJlbCIsInRhc2tfY291bnRfYnlfc3RhdGUiLCJhbWVuZG1lbnRzIiwiVGFza0lkTGlzdCIsInRhc2tJZHMiLCJwYWdlTnVtYmVyIiwicGFnZUFjdGlvbnMiLCJwcmV2aW91c0FjdGlvbiIsIm5leHRBY3Rpb24iLCJhY3Rpb25Db250YWluZXIiLCJyb2xlIiwibWFya3VwIiwic3RhcnRJbmRleCIsImVuZEluZGV4IiwicGFnZUFjdGlvbiIsImlzQWN0aXZlIiwiVGFza0xpc3RNb2RlbCIsInRhc2tJZExpc3QiLCJ0YXNrTGlzdE1vZGVscyIsImhlYWRpbmciLCJidXN5SWNvbiIsIl9jcmVhdGVCdXN5SWNvbiIsIl9yZXF1ZXN0VGFza0lkcyIsInBhZ2luYXRpb25FbGVtZW50IiwidGFza0xpc3RNb2RlbCIsIl9jcmVhdGVUYXNrTGlzdEVsZW1lbnRGcm9tSHRtbCIsImdldFBhZ2VJbmRleCIsIl9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkiLCJnZXRUYXNrc0J5U3RhdGVzIiwidXBkYXRlZFRhc2tMaXN0TW9kZWwiLCJ1cGRhdGVGcm9tVGFza0xpc3QiLCJoYXNUYXNrTGlzdEVsZW1lbnRGb3JQYWdlIiwiX3JldHJpZXZlVGFza1BhZ2UiLCJyZW5kZXJlZFRhc2tMaXN0RWxlbWVudCIsImhhc1BhZ2VJbmRleCIsImN1cnJlbnRQYWdlTGlzdEVsZW1lbnQiLCJzZWxlY3RlZFBhZ2VMaXN0RWxlbWVudCIsImdldEZvclBhZ2UiLCJwb3N0RGF0YSIsIl9yZXRyaWV2ZVRhc2tTZXQiLCJodG1sIiwiYnlQYWdlTGlzdHMiLCJieVBhZ2VDb250YWluZXJFbGVtZW50IiwiU29ydCIsInNvcnRhYmxlSXRlbXNOb2RlTGlzdCIsInNvcnRDb250cm9sQ29sbGVjdGlvbiIsIl9jcmVhdGVTb3J0YWJsZUNvbnRyb2xDb2xsZWN0aW9uIiwic29ydGFibGVJdGVtc0xpc3QiLCJfY3JlYXRlU29ydGFibGVJdGVtTGlzdCIsIl9zb3J0Q29udHJvbENsaWNrRXZlbnRMaXN0ZW5lciIsInNvcnRhYmxlSXRlbXMiLCJzb3J0YWJsZUl0ZW1FbGVtZW50Iiwic29ydENvbnRyb2xFbGVtZW50IiwicGFyZW50IiwidGFza1R5cGVDb250YWluZXJzIiwidW5hdmFpbGFibGVUYXNrVHlwZSIsInRhc2tUeXBlS2V5Iiwic2hvdyIsImludmFsaWRGaWVsZCIsImNvbXBhcmF0b3JWYWx1ZSIsInZhbGlkYXRlQ2FyZE51bWJlciIsIm51bWJlciIsInZhbGlkYXRlRXhwaXJ5IiwiZXhwX21vbnRoIiwiZXhwX3llYXIiLCJ2YWxpZGF0ZUNWQyIsImN2YyIsInZhbGlkYXRvciIsImRhdGFFbGVtZW50IiwiZmllbGRLZXkiLCJzdG9wUHJvcGFnYXRpb24iLCJfcmVtb3ZlRXJyb3JBbGVydHMiLCJfZ2V0RGF0YSIsImlzVmFsaWQiLCJ2YWxpZGF0ZSIsImVycm9yQWxlcnRzIiwiZXJyb3JBbGVydCIsImZpZWxkIiwibWVzc2FnZSIsImVycm9yQ29udGFpbmVyIiwiX2Rpc3BsYXlGaWVsZEVycm9yIiwiTmF2QmFyQW5jaG9yIiwiaGFuZGxlQ2xpY2siLCJnZXRUYXJnZXQiLCJOYXZCYXJJdGVtIiwiYW5jaG9yIiwiY2hpbGRyZW4iLCJjaGlsZCIsInRhcmdldHMiLCJnZXRUYXJnZXRzIiwiY29udGFpbnNUYXJnZXRJZCIsInNldEFjdGl2ZSIsIm5hdkJhckl0ZW1zIiwibmF2QmFySXRlbSIsImxpc3RJdGVtRWxlbWVudCIsImFjdGl2ZUxpbmtUYXJnZXQiLCJsaW5rVGFyZ2V0cyIsImxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZCIsImxpbmtUYXJnZXQiLCJnZXRCb3VuZGluZ0NsaWVudFJlY3QiLCJ0b3AiLCJjbGVhckFjdGl2ZSIsInNjcm9sbEV2ZW50TGlzdGVuZXIiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsbUNBQTJCLDBCQUEwQixFQUFFO0FBQ3ZELHlDQUFpQyxlQUFlO0FBQ2hEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLDhEQUFzRCwrREFB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QUNBO0FBQ0E7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSxpREFBaUQ7QUFDakQsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLElBQUk7O0FBRUo7QUFDQTtBQUNBLGdCQUFnQixtQkFBbUI7QUFDbkM7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBLGdCQUFnQixzQkFBc0I7QUFDdEMsSUFBSTtBQUNKO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsZUFBZSxzQkFBc0I7QUFDckM7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRUFBRTtBQUNGOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSxxQ0FBcUMsYUFBYTs7QUFFbEQ7O0FBRUE7QUFDQTtBQUNBLE1BQU07QUFDTiw4RUFBOEU7O0FBRTlFO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLDBCQUEwQjtBQUM1QyxDQUFDO0FBQ0Q7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLCtCQUErQjtBQUMvQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBLHFDQUFxQztBQUNyQzs7QUFFQTtBQUNBO0FBQ0EsZ0JBQWdCLGdDQUFnQztBQUNoRDtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7QUFDRjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsQ0FBQzs7Ozs7Ozs7Ozs7Ozs4Q0NoZkQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLCtCQUErQjtBQUMvQjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0wsMkNBQTJDO0FBQzNDO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsMEJBQTBCLHdDQUF3QyxPQUFPLE9BQU87QUFDaEY7QUFDQSxLQUFLO0FBQ0wsMERBQTBEO0FBQzFELDhFO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMLCtDQUErQztBQUMvQztBQUNBO0FBQ0EsZ0NBQWdDO0FBQ2hDLGVBQWUsNEJBQTRCLGtDQUFrQztBQUM3RSw2R0FBNkcsZ0JBQWdCO0FBQzdIO0FBQ0EsT0FBTyxnQ0FBZ0M7QUFDdkMsZUFBZSw0QkFBNEIsa0NBQWtDO0FBQzdFLG1EQUFtRCxnQkFBZ0I7QUFDbkU7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTCw4Q0FBOEM7QUFDOUM7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJCQUEyQjtBQUMzQixLQUFLO0FBQ0wscURBQXFEO0FBQ3JEO0FBQ0EseUVBQXlFLFlBQVksWUFBWSxFQUFFO0FBQ25HLDZCQUE2QixzQkFBc0IsRUFBRTtBQUNyRCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0EsNEJBQTRCO0FBQzVCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMLHVEQUF1RDtBQUN2RCwrQkFBK0IscURBQXFEO0FBQ3BGO0FBQ0E7QUFDQTtBQUNBLHlEQUF5RCx1RkFBdUY7QUFDaEosNEJBQTRCLDJEQUEyRDtBQUN2RjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSx5R0FBeUc7QUFDekc7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxzREFBc0Q7QUFDdEQsa0NBQWtDO0FBQ2xDO0FBQ0EsU0FBUyxPQUFPO0FBQ2hCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsT0FBTyxzREFBc0Q7QUFDN0QsZ0NBQWdDO0FBQ2hDO0FBQ0EsU0FBUyxPQUFPO0FBQ2hCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDBDQUEwQzs7QUFFMUM7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxzQ0FBc0MsZ0JBQWdCLEVBQUU7O0FBRXhELG1CQUFtQixRQUFRLEVBQUU7O0FBRTdCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0Esc0NBQXNDOztBQUV0QztBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyQkFBMkIsd0JBQXdCO0FBQ25EO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0EseUJBQXlCLHdCQUF3QjtBQUNqRDtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1Asc0NBQXNDO0FBQ3RDO0FBQ0EsNkRBQTZEO0FBQzdEO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5Q0FBeUM7QUFDekM7QUFDQSxpRTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0EsdUNBQXVDO0FBQ3ZDO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCO0FBQ3RCO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esc0NBQXNDLGFBQWEsT0FBTztBQUMxRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLDZCQUE2QjtBQUM3QjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDRCQUE0QixrQ0FBa0M7QUFDOUQ7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxvRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLDhFQUE4RTtBQUM5RTtBQUNBO0FBQ0E7QUFDQSxxQ0FBcUM7QUFDckM7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLHdDQUF3QyxhQUFhLEU7QUFDckQsWUFBWSxhQUFhO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSw0QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSwwQ0FBMEM7QUFDMUM7QUFDQTtBQUNBO0FBQ0Esa0NBQWtDO0FBQ2xDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0Esa0NBQWtDLG9HQUFvRyxFQUFFO0FBQ3hJO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBLCtDQUErQztBQUMvQztBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7OztBQUtBO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QyxLQUFLO0FBQzlDO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLHVDQUF1QyxLQUFLO0FBQzVDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLHVFQUF1RSxnQkFBZ0IsRUFBRTs7QUFFekY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLENBQUM7Ozs7Ozs7Ozs7Ozs7O0FDOXVCRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsUUFBUSxTQUFTO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsUUFBUSxTQUFTO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsRUFBRTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRUFBRSxhQUFhO0FBQ2Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDO0FBQ0Q7QUFDQTs7QUFFQSxDQUFDOztBQUVEOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUEsZUFBZSxTQUFTO0FBQ3hCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBLENBQUM7O0FBRUQ7Ozs7Ozs7Ozs7Ozs7OENDL09BO0FBQ0EsZ0JBQWdCLHVGQUE0RCxZQUFZO0FBQUEsc0tBQW9FLHdGQUF3RixhQUFhLE9BQU8sd0tBQXdLLGNBQWMsdUhBQXVILGNBQWMsWUFBWSxLQUFLLG1CQUFtQixrQkFBa0IsZ0RBQWdELGdCQUFnQixTQUFTLGVBQWUsNkVBQTZFLGVBQWUsaURBQWlELGVBQWUsTUFBTSxJQUFJLHdCQUF3QixTQUFTLElBQUksU0FBUyxlQUFlLG1DQUFtQyw2REFBNkQsTUFBTSxFQUFFLDRHQUE0RyxtTUFBbU0sTUFBTSxJQUFJLDRCQUE0QixTQUFTLFFBQVEsU0FBUyxpQkFBaUIsTUFBTSwybUJBQTJtQixjQUFjLG9OQUFvTixxQkFBcUIsUUFBUSxxQkFBcUIsZ0NBQWdDLFNBQVMsa0VBQWtFLGVBQWUsNEJBQTRCLG1CQUFtQixzREFBc0QsMkNBQTJDLDhEQUE4RCxtQkFBbUIsMkpBQTJKLHFCQUFxQixtREFBbUQseUJBQXlCLG1CQUFtQixtQkFBbUIsRUFBRSw0QkFBNEIscUJBQXFCLHVCQUF1QiwyQkFBMkIsc0RBQXNELGlDQUFpQyxrQkFBa0IsaUZBQWlGLFNBQVMsb0JBQW9CLCtEQUErRCw4SEFBOEgsb0JBQW9CLG1IQUFtSCxlQUFlLHdJQUF3SSxtSEFBbUgsa0JBQWtCLGdQQUFnUCxrR0FBa0cseUZBQXlGLGVBQWUscUdBQXFHLHlEQUF5RCwyQkFBMkIsYUFBYSxHQUFHLGVBQWUsNkJBQTZCLGNBQWMsUUFBUSw0QkFBNEIsOExBQThMLG9CQUFvQiw4R0FBOEcsdUJBQXVCLG9NQUFvTSxjQUFjLEc7Ozs7Ozs7Ozs7Ozs7QUNEcGtLO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLENBQUM7O0FBRUQ7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7OztBQUdBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxjQUFjOztBQUVkLG1CQUFtQixRQUFRO0FBQzNCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxjQUFjOztBQUVkLG1CQUFtQixRQUFRO0FBQzNCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxvQkFBb0IsUUFBUTtBQUM1QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsbUJBQW1CLFlBQVk7QUFDL0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QixRQUFRO0FBQy9CO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsOENBQThDLElBQUk7QUFDbEQ7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsb0JBQW9CLFFBQVE7QUFDNUI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxtQkFBbUIsWUFBWTtBQUMvQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QixRQUFRO0FBQy9CO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsOENBQThDLElBQUk7QUFDbEQ7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsbUJBQW1CLE9BQU87QUFDMUI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLG1CQUFtQixjQUFjO0FBQ2pDO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLG1CQUFt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OztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkRBQTJEO0FBQzNEOztBQUVBO0FBQ0E7QUFDQSwyQkFBMkIscUJBQXFCO0FBQ2hEO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQTs7QUFFQSwyQkFBMkIscUJBQXFCO0FBQ2hEOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkRBQTJEO0FBQzNEOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7O0FBRUEsMkJBQTJCLHFCQUFxQjtBQUNoRDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQSxDQUFDLG9COzs7Ozs7Ozs7Ozs7QUN2Z0JEOztBQUVBO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0EsQ0FBQztBQUNEO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSw0Q0FBNEM7O0FBRTVDIiwiZmlsZSI6ImFwcC4yNTMwYWEzMC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwge1xuIFx0XHRcdFx0Y29uZmlndXJhYmxlOiBmYWxzZSxcbiBcdFx0XHRcdGVudW1lcmFibGU6IHRydWUsXG4gXHRcdFx0XHRnZXQ6IGdldHRlclxuIFx0XHRcdH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIi9idWlsZC9cIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSBcIi4vYXNzZXRzL2pzL2FwcC5qc1wiKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCBlNDU4Njg0YWIzOTBjYmJkMTJjYyIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9hc3NldHMvY3NzL2FwcC5zY3NzXG4vLyBtb2R1bGUgaWQgPSAuL2Fzc2V0cy9jc3MvYXBwLnNjc3Ncbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwicmVxdWlyZSgnYm9vdHN0cmFwLm5hdGl2ZScpO1xucmVxdWlyZSgnLi4vY3NzL2FwcC5zY3NzJyk7XG5cbnJlcXVpcmUoJ2NsYXNzbGlzdC1wb2x5ZmlsbCcpO1xucmVxdWlyZSgnLi9wb2x5ZmlsbC9jdXN0b20tZXZlbnQnKTtcbnJlcXVpcmUoJy4vcG9seWZpbGwvb2JqZWN0LWVudHJpZXMnKTtcblxubGV0IGZvcm1CdXR0b25TcGlubmVyID0gcmVxdWlyZSgnLi9mb3JtLWJ1dHRvbi1zcGlubmVyJyk7XG5sZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4vZm9ybS1maWVsZC1mb2N1c2VyJyk7XG5sZXQgbW9kYWxDb250cm9sID0gcmVxdWlyZSgnLi9tb2RhbC1jb250cm9sJyk7XG5sZXQgY29sbGFwc2VDb250cm9sQ2FyZXQgPSByZXF1aXJlKCcuL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQnKTtcblxubGV0IERhc2hib2FyZCA9IHJlcXVpcmUoJy4vcGFnZS9kYXNoYm9hcmQnKTtcbmxldCB0ZXN0SGlzdG9yeVBhZ2UgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1oaXN0b3J5Jyk7XG5sZXQgVGVzdFJlc3VsdHMgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1yZXN1bHRzJyk7XG5sZXQgVXNlckFjY291bnQgPSByZXF1aXJlKCcuL3BhZ2UvdXNlci1hY2NvdW50Jyk7XG5sZXQgVXNlckFjY291bnRDYXJkID0gcmVxdWlyZSgnLi9wYWdlL3VzZXItYWNjb3VudC1jYXJkJyk7XG5sZXQgQWxlcnRGYWN0b3J5ID0gcmVxdWlyZSgnLi9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5Jyk7XG5sZXQgVGVzdFByb2dyZXNzID0gcmVxdWlyZSgnLi9wYWdlL3Rlc3QtcHJvZ3Jlc3MnKTtcbmxldCBUZXN0UmVzdWx0c1ByZXBhcmluZyA9IHJlcXVpcmUoJy4vcGFnZS90ZXN0LXJlc3VsdHMtcHJlcGFyaW5nJyk7XG5sZXQgVGVzdFJlc3VsdHNCeVRhc2tUeXBlID0gcmVxdWlyZSgnLi9wYWdlL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUnKTtcbmxldCBUYXNrUmVzdWx0cyA9IHJlcXVpcmUoJy4vcGFnZS90YXNrLXJlc3VsdHMnKTtcblxuY29uc3Qgb25Eb21Db250ZW50TG9hZGVkID0gZnVuY3Rpb24gKCkge1xuICAgIGxldCBib2R5ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ2JvZHknKS5pdGVtKDApO1xuICAgIGxldCBmb2N1c2VkRmllbGQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1mb2N1c2VkXScpO1xuXG4gICAgaWYgKGZvY3VzZWRGaWVsZCkge1xuICAgICAgICBmb3JtRmllbGRGb2N1c2VyKGZvY3VzZWRGaWVsZCk7XG4gICAgfVxuXG4gICAgW10uZm9yRWFjaC5jYWxsKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5qcy1mb3JtLWJ1dHRvbi1zcGlubmVyJyksIGZ1bmN0aW9uIChmb3JtRWxlbWVudCkge1xuICAgICAgICBmb3JtQnV0dG9uU3Bpbm5lcihmb3JtRWxlbWVudCk7XG4gICAgfSk7XG5cbiAgICBtb2RhbENvbnRyb2woZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLm1vZGFsLWNvbnRyb2wnKSk7XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ2Rhc2hib2FyZCcpKSB7XG4gICAgICAgIGxldCBkYXNoYm9hcmQgPSBuZXcgRGFzaGJvYXJkKGRvY3VtZW50KTtcbiAgICAgICAgZGFzaGJvYXJkLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3Rlc3QtcHJvZ3Jlc3MnKSkge1xuICAgICAgICBsZXQgdGVzdFByb2dyZXNzID0gbmV3IFRlc3RQcm9ncmVzcyhkb2N1bWVudCk7XG4gICAgICAgIHRlc3RQcm9ncmVzcy5pbml0KCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd0ZXN0LWhpc3RvcnknKSkge1xuICAgICAgICB0ZXN0SGlzdG9yeVBhZ2UoZG9jdW1lbnQpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1yZXN1bHRzJykpIHtcbiAgICAgICAgbGV0IHRlc3RSZXN1bHRzID0gbmV3IFRlc3RSZXN1bHRzKGRvY3VtZW50KTtcbiAgICAgICAgdGVzdFJlc3VsdHMuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGFzay1yZXN1bHRzJykpIHtcbiAgICAgICAgbGV0IHRhc2tSZXN1bHRzID0gbmV3IFRhc2tSZXN1bHRzKGRvY3VtZW50KTtcbiAgICAgICAgdGFza1Jlc3VsdHMuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZScpKSB7XG4gICAgICAgIGxldCB0ZXN0UmVzdWx0c0J5VGFza1R5cGUgPSBuZXcgVGVzdFJlc3VsdHNCeVRhc2tUeXBlKGRvY3VtZW50KTtcbiAgICAgICAgdGVzdFJlc3VsdHNCeVRhc2tUeXBlLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcnKSkge1xuICAgICAgICBsZXQgdGVzdFJlc3VsdHNQcmVwYXJpbmcgPSBuZXcgVGVzdFJlc3VsdHNQcmVwYXJpbmcoZG9jdW1lbnQpO1xuICAgICAgICB0ZXN0UmVzdWx0c1ByZXBhcmluZy5pbml0KCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd1c2VyLWFjY291bnQnKSkge1xuICAgICAgICBsZXQgdXNlckFjY291bnQgPSBuZXcgVXNlckFjY291bnQod2luZG93LCBkb2N1bWVudCk7XG4gICAgICAgIHVzZXJBY2NvdW50LmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3VzZXItYWNjb3VudC1jYXJkJykpIHtcbiAgICAgICAgbGV0IHVzZXJBY2NvdW50Q2FyZCA9IG5ldyBVc2VyQWNjb3VudENhcmQoZG9jdW1lbnQpO1xuICAgICAgICB1c2VyQWNjb3VudENhcmQuaW5pdCgpO1xuICAgIH1cblxuICAgIGNvbGxhcHNlQ29udHJvbENhcmV0KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5jb2xsYXBzZS1jb250cm9sJykpO1xuXG4gICAgW10uZm9yRWFjaC5jYWxsKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hbGVydCcpLCBmdW5jdGlvbiAoYWxlcnRFbGVtZW50KSB7XG4gICAgICAgIEFsZXJ0RmFjdG9yeS5jcmVhdGVGcm9tRWxlbWVudChhbGVydEVsZW1lbnQpO1xuICAgIH0pO1xufTtcblxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIG9uRG9tQ29udGVudExvYWRlZCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYXBwLmpzIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoY29udHJvbHMpIHtcbiAgICBjb25zdCBjb250cm9sSWNvbkNsYXNzID0gJ2ZhJztcbiAgICBjb25zdCBjYXJldFVwQ2xhc3MgPSAnZmEtY2FyZXQtdXAnO1xuICAgIGNvbnN0IGNhcmV0RG93bkNsYXNzID0gJ2ZhLWNhcmV0LWRvd24nO1xuICAgIGNvbnN0IGNvbnRyb2xDb2xsYXBzZWRDbGFzcyA9ICdjb2xsYXBzZWQnO1xuXG4gICAgbGV0IGNyZWF0ZUNvbnRyb2xJY29uID0gZnVuY3Rpb24gKGNvbnRyb2wpIHtcbiAgICAgICAgY29uc3QgY29udHJvbEljb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpJyk7XG4gICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY29udHJvbEljb25DbGFzcyk7XG5cbiAgICAgICAgaWYgKGNvbnRyb2wuaGFzQXR0cmlidXRlKCdkYXRhLWljb24tYWRkaXRpb25hbC1jbGFzc2VzJykpIHtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY29udHJvbC5nZXRBdHRyaWJ1dGUoJ2RhdGEtaWNvbi1hZGRpdGlvbmFsLWNsYXNzZXMnKSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoY29udHJvbC5jbGFzc0xpc3QuY29udGFpbnMoY29udHJvbENvbGxhcHNlZENsYXNzKSkge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldERvd25DbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QuYWRkKGNhcmV0VXBDbGFzcyk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gY29udHJvbEljb247XG4gICAgfTtcblxuICAgIGxldCB0b2dnbGVDYXJldCA9IGZ1bmN0aW9uIChjb250cm9sLCBjb250cm9sSWNvbikge1xuICAgICAgICBpZiAoY29udHJvbC5jbGFzc0xpc3QuY29udGFpbnMoY29udHJvbENvbGxhcHNlZENsYXNzKSkge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LnJlbW92ZShjYXJldFVwQ2xhc3MpO1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldERvd25DbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QucmVtb3ZlKGNhcmV0RG93bkNsYXNzKTtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY2FyZXRVcENsYXNzKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBsZXQgaGFuZGxlQ29udHJvbCA9IGZ1bmN0aW9uIChjb250cm9sKSB7XG4gICAgICAgIGNvbnN0IGV2ZW50TmFtZVNob3duID0gJ3Nob3duLmJzLmNvbGxhcHNlJztcbiAgICAgICAgY29uc3QgZXZlbnROYW1lSGlkZGVuID0gJ2hpZGRlbi5icy5jb2xsYXBzZSc7XG4gICAgICAgIGNvbnN0IGNvbGxhcHNpYmxlRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGNvbnRyb2wuZ2V0QXR0cmlidXRlKCdkYXRhLXRhcmdldCcpLnJlcGxhY2UoJyMnLCAnJykpO1xuICAgICAgICBjb25zdCBjb250cm9sSWNvbiA9IGNyZWF0ZUNvbnRyb2xJY29uKGNvbnRyb2wpO1xuXG4gICAgICAgIGNvbnRyb2wuYXBwZW5kKGNvbnRyb2xJY29uKTtcblxuICAgICAgICBsZXQgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdG9nZ2xlQ2FyZXQoY29udHJvbCwgY29udHJvbEljb24pO1xuICAgICAgICB9O1xuXG4gICAgICAgIGNvbGxhcHNpYmxlRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50TmFtZVNob3duLCBzaG93bkhpZGRlbkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIGNvbGxhcHNpYmxlRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50TmFtZUhpZGRlbiwgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGNvbnRyb2xzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGhhbmRsZUNvbnRyb2woY29udHJvbHNbaV0pO1xuICAgIH1cbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY29sbGFwc2UtY29udHJvbC1jYXJldC5qcyIsImxldCBMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24nKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgUmVjZW50VGVzdExpc3Qge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuc291cmNlVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc291cmNlLXVybCcpO1xuICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uID0gbmV3IExpc3RlZFRlc3RDb2xsZWN0aW9uKCk7XG4gICAgICAgIHRoaXMucmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24gPSBuZXcgTGlzdGVkVGVzdENvbGxlY3Rpb24oKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLl9yZXRyaWV2ZVRlc3RzKCk7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIHRoaXMuX3BhcnNlUmV0cmlldmVkVGVzdHMoZXZlbnQuZGV0YWlsLnJlc3BvbnNlKTtcbiAgICAgICAgdGhpcy5fcmVuZGVyUmV0cmlldmVkVGVzdHMoKTtcblxuICAgICAgICB3aW5kb3cuc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRlc3RzKCk7XG4gICAgICAgIH0sIDMwMDApO1xuICAgIH07XG5cbiAgICBfcmVuZGVyUmV0cmlldmVkVGVzdHMgKCkge1xuICAgICAgICB0aGlzLl9yZW1vdmVTcGlubmVyKCk7XG5cbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5mb3JFYWNoKChsaXN0ZWRUZXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIXRoaXMucmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24uY29udGFpbnMobGlzdGVkVGVzdCkpIHtcbiAgICAgICAgICAgICAgICBsaXN0ZWRUZXN0LmVsZW1lbnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChsaXN0ZWRUZXN0LmVsZW1lbnQpO1xuICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24ucmVtb3ZlKGxpc3RlZFRlc3QpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uLmZvckVhY2goKHJldHJpZXZlZExpc3RlZFRlc3QpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmNvbnRhaW5zKHJldHJpZXZlZExpc3RlZFRlc3QpKSB7XG4gICAgICAgICAgICAgICAgbGV0IGxpc3RlZFRlc3QgPSB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmdldChyZXRyaWV2ZWRMaXN0ZWRUZXN0LmdldFRlc3RJZCgpKTtcblxuICAgICAgICAgICAgICAgIGlmIChyZXRyaWV2ZWRMaXN0ZWRUZXN0LmdldFR5cGUoKSAhPT0gbGlzdGVkVGVzdC5nZXRUeXBlKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5yZW1vdmUobGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5yZXBsYWNlQ2hpbGQocmV0cmlldmVkTGlzdGVkVGVzdC5lbGVtZW50LCBsaXN0ZWRUZXN0LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24uYWRkKHJldHJpZXZlZExpc3RlZFRlc3QpO1xuICAgICAgICAgICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGxpc3RlZFRlc3QucmVuZGVyRnJvbUxpc3RlZFRlc3QocmV0cmlldmVkTGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdhZnRlcmJlZ2luJywgcmV0cmlldmVkTGlzdGVkVGVzdC5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmFkZChyZXRyaWV2ZWRMaXN0ZWRUZXN0KTtcbiAgICAgICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3BhcnNlUmV0cmlldmVkVGVzdHMgKHJlc3BvbnNlKSB7XG4gICAgICAgIGxldCByZXRyaWV2ZWRUZXN0c01hcmt1cCA9IHJlc3BvbnNlLnRyaW0oKTtcbiAgICAgICAgbGV0IHJldHJpZXZlZFRlc3RDb250YWluZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgcmV0cmlldmVkVGVzdENvbnRhaW5lci5pbm5lckhUTUwgPSByZXRyaWV2ZWRUZXN0c01hcmt1cDtcblxuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uID0gTGlzdGVkVGVzdENvbGxlY3Rpb24uY3JlYXRlRnJvbU5vZGVMaXN0KFxuICAgICAgICAgICAgcmV0cmlldmVkVGVzdENvbnRhaW5lci5xdWVyeVNlbGVjdG9yQWxsKCcubGlzdGVkLXRlc3QnKSxcbiAgICAgICAgICAgIGZhbHNlXG4gICAgICAgICk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZVRlc3RzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRUZXh0KHRoaXMuc291cmNlVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZVRlc3RzJyk7XG4gICAgfTtcblxuICAgIF9yZW1vdmVTcGlubmVyICgpIHtcbiAgICAgICAgbGV0IHNwaW5uZXIgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXByZWZpbGwtc3Bpbm5lcicpO1xuXG4gICAgICAgIGlmIChzcGlubmVyKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQucmVtb3ZlQ2hpbGQoc3Bpbm5lcik7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFJlY2VudFRlc3RMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5sZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi4vaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLW1vZGFsJyk7XG5cbmNsYXNzIFRlc3RTdGFydEZvcm0ge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucyA9IFtdO1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IG5ldyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwoXG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJyNodHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwnKVxuICAgICAgICApO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnW3R5cGU9c3VibWl0XScpLCAoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMucHVzaChuZXcgRm9ybUJ1dHRvbihzdWJtaXRCdXR0b24pKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgdGhpcy5fc3VibWl0RXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX3N1Ym1pdEJ1dHRvbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5pbml0KCk7XG4gICAgfTtcblxuICAgIF9zdWJtaXRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZGVFbXBoYXNpemUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5fcmVwbGFjZVVuY2hlY2tlZENoZWNrYm94ZXNXaXRoSGlkZGVuRmllbGRzKCk7XG4gICAgfTtcblxuICAgIGRpc2FibGUgKCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZGlzYWJsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b25zLmZvckVhY2goKHN1Ym1pdEJ1dHRvbikgPT4ge1xuICAgICAgICAgICAgc3VibWl0QnV0dG9uLmVuYWJsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3N1Ym1pdEJ1dHRvbkV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCBidXR0b25FbGVtZW50ID0gZXZlbnQudGFyZ2V0O1xuICAgICAgICBsZXQgYnV0dG9uID0gbmV3IEZvcm1CdXR0b24oYnV0dG9uRWxlbWVudCk7XG5cbiAgICAgICAgYnV0dG9uLm1hcmtBc0J1c3koKTtcbiAgICB9O1xuXG4gICAgX3JlcGxhY2VVbmNoZWNrZWRDaGVja2JveGVzV2l0aEhpZGRlbkZpZWxkcyAoKSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW5wdXRbdHlwZT1jaGVja2JveF0nKSwgKGlucHV0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIWlucHV0LmNoZWNrZWQpIHtcbiAgICAgICAgICAgICAgICBsZXQgaGlkZGVuSW5wdXQgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICAgICAgaGlkZGVuSW5wdXQuc2V0QXR0cmlidXRlKCd0eXBlJywgJ2hpZGRlbicpO1xuICAgICAgICAgICAgICAgIGhpZGRlbklucHV0LnNldEF0dHJpYnV0ZSgnbmFtZScsIGlucHV0LmdldEF0dHJpYnV0ZSgnbmFtZScpKTtcbiAgICAgICAgICAgICAgICBoaWRkZW5JbnB1dC52YWx1ZSA9ICcwJztcblxuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmQoaGlkZGVuSW5wdXQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RTdGFydEZvcm07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvZGFzaGJvYXJkL3Rlc3Qtc3RhcnQtZm9ybS5qcyIsImxldCBGb3JtQnV0dG9uID0gcmVxdWlyZSgnLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5cbm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGZvcm0pIHtcbiAgICBjb25zdCBzdWJtaXRCdXR0b24gPSBuZXcgRm9ybUJ1dHRvbihmb3JtLnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG5cbiAgICBmb3JtLmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgc3VibWl0QnV0dG9uLm1hcmtBc0J1c3koKTtcbiAgICB9KTtcbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvZm9ybS1idXR0b24tc3Bpbm5lci5qcyIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGlucHV0KSB7XG4gICAgbGV0IGlucHV0VmFsdWUgPSBpbnB1dC52YWx1ZTtcblxuICAgIHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaW5wdXQuZm9jdXMoKTtcbiAgICAgICAgaW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgaW5wdXQudmFsdWUgPSBpbnB1dFZhbHVlO1xuICAgIH0sIDEpO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9mb3JtLWZpZWxkLWZvY3VzZXIuanMiLCJjbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc2hvdy5icy5tb2RhbCcsICgpID0+IHtcbiAgICAgICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLWRpc2FibGUtcmVhZG9ubHknKSwgZnVuY3Rpb24gKGlucHV0RWxlbWVudCkge1xuICAgICAgICAgICAgICAgIGlucHV0RWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoJ3JlYWRvbmx5Jyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWw7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLW1vZGFsLmpzIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoY29udHJvbEVsZW1lbnRzKSB7XG4gICAgbGV0IGluaXRpYWxpemUgPSBmdW5jdGlvbiAoY29udHJvbEVsZW1lbnQpIHtcbiAgICAgICAgY29udHJvbEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYnRuJywgJ2J0bi1saW5rJyk7XG4gICAgfTtcblxuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgY29udHJvbEVsZW1lbnRzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGluaXRpYWxpemUoY29udHJvbEVsZW1lbnRzW2ldKTtcbiAgICB9XG59O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGFsLWNvbnRyb2wuanMiLCJjbGFzcyBCYWRnZUNvbGxlY3Rpb24ge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7Tm9kZUxpc3R9IGJhZGdlc1xuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChiYWRnZXMpIHtcbiAgICAgICAgdGhpcy5iYWRnZXMgPSBiYWRnZXM7XG4gICAgfTtcblxuICAgIGFwcGx5VW5pZm9ybVdpZHRoICgpIHtcbiAgICAgICAgbGV0IGdyZWF0ZXN0V2lkdGggPSB0aGlzLl9kZXJpdmVHcmVhdGVzdFdpZHRoKCk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuYmFkZ2VzLCAoYmFkZ2UpID0+IHtcbiAgICAgICAgICAgIGJhZGdlLnN0eWxlLndpZHRoID0gZ3JlYXRlc3RXaWR0aCArICdweCc7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7bnVtYmVyfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2Rlcml2ZUdyZWF0ZXN0V2lkdGggKCkge1xuICAgICAgICBsZXQgZ3JlYXRlc3RXaWR0aCA9IDA7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuYmFkZ2VzLCAoYmFkZ2UpID0+IHtcbiAgICAgICAgICAgIGlmIChiYWRnZS5vZmZzZXRXaWR0aCA+IGdyZWF0ZXN0V2lkdGgpIHtcbiAgICAgICAgICAgICAgICBncmVhdGVzdFdpZHRoID0gYmFkZ2Uub2Zmc2V0V2lkdGg7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBncmVhdGVzdFdpZHRoO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBCYWRnZUNvbGxlY3Rpb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvYmFkZ2UtY29sbGVjdGlvbi5qcyIsImxldCBDb29raWVPcHRpb25zTW9kYWwgPSByZXF1aXJlKCcuL2VsZW1lbnQvY29va2llLW9wdGlvbnMtbW9kYWwnKTtcblxuY2xhc3MgQ29va2llT3B0aW9ucyB7XG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50LCBjb29raWVPcHRpb25zTW9kYWwsIGFjdGlvbkJhZGdlLCBzdGF0dXNFbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zTW9kYWwgPSBjb29raWVPcHRpb25zTW9kYWw7XG4gICAgICAgIHRoaXMuYWN0aW9uQmFkZ2UgPSBhY3Rpb25CYWRnZTtcbiAgICAgICAgdGhpcy5zdGF0dXNFbGVtZW50ID0gc3RhdHVzRWxlbWVudDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2Nvb2tpZS1vcHRpb25zLm1vZGFsLm9wZW5lZCc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldE1vZGFsQ2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy5tb2RhbC5jbG9zZWQnO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgaWYgKCF0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5pc0FjY291bnRSZXF1aXJlZE1vZGFsKSB7XG4gICAgICAgICAgICBsZXQgbW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY29va2llT3B0aW9uc01vZGFsLmlzRW1wdHkoKSkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ25vdCBlbmFibGVkJztcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrTm90RW5hYmxlZCgpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudC5pbm5lclRleHQgPSAnZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYWN0aW9uQmFkZ2UubWFya0VuYWJsZWQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5pbml0KCk7XG5cbiAgICAgICAgICAgIHRoaXMuY29va2llT3B0aW9uc01vZGFsLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihDb29raWVPcHRpb25zTW9kYWwuZ2V0T3BlbmVkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMuY29va2llT3B0aW9uc01vZGFsLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihDb29raWVPcHRpb25zTW9kYWwuZ2V0Q2xvc2VkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgICAgICBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lcigpO1xuICAgICAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoQ29va2llT3B0aW9ucy5nZXRNb2RhbENsb3NlZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQ29va2llT3B0aW9ucztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9jb29raWUtb3B0aW9ucy5qcyIsImNsYXNzIEFjdGlvbkJhZGdlIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIG1hcmtFbmFibGVkICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FjdGlvbi1iYWRnZS1lbmFibGVkJyk7XG4gICAgfVxuXG4gICAgbWFya05vdEVuYWJsZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aW9uLWJhZGdlLWVuYWJsZWQnKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQWN0aW9uQmFkZ2U7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hY3Rpb24tYmFkZ2UuanMiLCJsZXQgYnNuID0gcmVxdWlyZSgnYm9vdHN0cmFwLm5hdGl2ZScpO1xubGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi8uLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcblxuY2xhc3MgQWxlcnQge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG5cbiAgICAgICAgbGV0IGNsb3NlQnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY2xvc2UnKTtcbiAgICAgICAgaWYgKGNsb3NlQnV0dG9uKSB7XG4gICAgICAgICAgICBjbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2Nsb3NlQnV0dG9uQ2xpY2tFdmVudEhhbmRsZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBzZXRTdHlsZSAoc3R5bGUpIHtcbiAgICAgICAgdGhpcy5fcmVtb3ZlUHJlc2VudGF0aW9uYWxDbGFzc2VzKCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FsZXJ0LScgKyBzdHlsZSk7XG4gICAgfTtcblxuICAgIHdyYXBDb250ZW50SW5Db250YWluZXIgKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGNvbnRhaW5lci5jbGFzc0xpc3QuYWRkKCdjb250YWluZXInKTtcblxuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gdGhpcy5lbGVtZW50LmlubmVySFRNTDtcbiAgICAgICAgdGhpcy5lbGVtZW50LmlubmVySFRNTCA9ICcnO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChjb250YWluZXIpO1xuICAgIH07XG5cbiAgICBfcmVtb3ZlUHJlc2VudGF0aW9uYWxDbGFzc2VzICgpIHtcbiAgICAgICAgbGV0IHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXggPSAnYWxlcnQtJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNsYXNzTmFtZS5pbmRleE9mKHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX2Nsb3NlQnV0dG9uQ2xpY2tFdmVudEhhbmRsZXIgKCkge1xuICAgICAgICBsZXQgcmVsYXRlZEZpZWxkSWQgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWZvcicpO1xuICAgICAgICBpZiAocmVsYXRlZEZpZWxkSWQpIHtcbiAgICAgICAgICAgIGxldCByZWxhdGVkRmllbGQgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5nZXRFbGVtZW50QnlJZChyZWxhdGVkRmllbGRJZCk7XG5cbiAgICAgICAgICAgIGlmIChyZWxhdGVkRmllbGQpIHtcbiAgICAgICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHJlbGF0ZWRGaWVsZCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBsZXQgYnNuQWxlcnQgPSBuZXcgYnNuLkFsZXJ0KHRoaXMuZWxlbWVudCk7XG4gICAgICAgIGJzbkFsZXJ0LmNsb3NlKCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEFsZXJ0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvYWxlcnQuanMiLCJsZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uLy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xuXG5jbGFzcyBDb29raWVPcHRpb25zTW9kYWwge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuaXNBY2NvdW50UmVxdWlyZWRNb2RhbCA9IGVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdhY2NvdW50LXJlcXVpcmVkLW1vZGFsJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9Y2xvc2VdJyk7XG4gICAgICAgIHRoaXMuYWRkQnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuanMtYWRkLWJ1dHRvbicpO1xuICAgICAgICB0aGlzLmFwcGx5QnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1uYW1lPWFwcGx5Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuX2FkZFJlbW92ZUFjdGlvbnMoKTtcbiAgICAgICAgdGhpcy5fYWRkRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0T3BlbmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5vcGVuZWQnO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMtbW9kYWwuY2xvc2VkJztcbiAgICB9XG5cbiAgICBfcmVtb3ZlQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgY29va2llRGF0YVJvd0NvdW50ID0gdGhpcy50YWJsZUJvZHkucXVlcnlTZWxlY3RvckFsbCgnLmpzLWNvb2tpZScpLmxlbmd0aDtcbiAgICAgICAgbGV0IHJlbW92ZUFjdGlvbiA9IGV2ZW50LnRhcmdldDtcbiAgICAgICAgbGV0IHRhYmxlUm93ID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQocmVtb3ZlQWN0aW9uLmdldEF0dHJpYnV0ZSgnZGF0YS1mb3InKSk7XG5cbiAgICAgICAgaWYgKGNvb2tpZURhdGFSb3dDb3VudCA9PT0gMSkge1xuICAgICAgICAgICAgbGV0IG5hbWVJbnB1dCA9IHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lIGlucHV0Jyk7XG5cbiAgICAgICAgICAgIG5hbWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICAgICAgdGFibGVSb3cucXVlcnlTZWxlY3RvcignLnZhbHVlIGlucHV0JykudmFsdWUgPSAnJztcblxuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcihuYW1lSW5wdXQpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGFibGVSb3cucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh0YWJsZVJvdyk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtLZXlib2FyZEV2ZW50fSBldmVudFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC50eXBlID09PSAna2V5ZG93bicgJiYgZXZlbnQua2V5ID09PSAnRW50ZXInKSB7XG4gICAgICAgICAgICB0aGlzLmFwcGx5QnV0dG9uLmNsaWNrKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgbGV0IHNob3duRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGFibGVCb2R5ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ3Rib2R5Jyk7XG4gICAgICAgICAgICB0aGlzLnByZXZpb3VzVGFibGVCb2R5ID0gdGhpcy50YWJsZUJvZHkuY2xvbmVOb2RlKHRydWUpO1xuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcih0aGlzLnRhYmxlQm9keS5xdWVyeVNlbGVjdG9yKCcuanMtY29va2llOmxhc3Qtb2YtdHlwZSAubmFtZSBpbnB1dCcpKTtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zTW9kYWwuZ2V0T3BlbmVkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgaGlkZGVuRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zTW9kYWwuZ2V0Q2xvc2VkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnRhYmxlQm9keS5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZCh0aGlzLnByZXZpb3VzVGFibGVCb2R5LCB0aGlzLnRhYmxlQm9keSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGFkZEJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIGxldCB0YWJsZVJvdyA9IHRoaXMuX2NyZWF0ZVRhYmxlUm93KCk7XG4gICAgICAgICAgICBsZXQgcmVtb3ZlQWN0aW9uID0gdGhpcy5fY3JlYXRlUmVtb3ZlQWN0aW9uKHRhYmxlUm93LmdldEF0dHJpYnV0ZSgnZGF0YS1pbmRleCcpKTtcblxuICAgICAgICAgICAgdGFibGVSb3cucXVlcnlTZWxlY3RvcignLnJlbW92ZScpLmFwcGVuZENoaWxkKHJlbW92ZUFjdGlvbik7XG5cbiAgICAgICAgICAgIHRoaXMudGFibGVCb2R5LmFwcGVuZENoaWxkKHRhYmxlUm93KTtcbiAgICAgICAgICAgIHRoaXMuX2FkZFJlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lcihyZW1vdmVBY3Rpb24pO1xuXG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lIGlucHV0JykpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdzaG93bi5icy5tb2RhbCcsIHNob3duRXZlbnRMaXN0ZW5lcik7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRkZW4uYnMubW9kYWwnLCBoaWRkZW5FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5hZGRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBhZGRCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLXJlbW92ZScpLCAocmVtb3ZlQWN0aW9uKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIocmVtb3ZlQWN0aW9uKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubmFtZSBpbnB1dCwgLnZhbHVlIGlucHV0JyksIChpbnB1dCkgPT4ge1xuICAgICAgICAgICAgaW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfYWRkUmVtb3ZlQWN0aW9ucyAoKSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnJlbW92ZScpLCAocmVtb3ZlQ29udGFpbmVyLCBpbmRleCkgPT4ge1xuICAgICAgICAgICAgcmVtb3ZlQ29udGFpbmVyLmFwcGVuZENoaWxkKHRoaXMuX2NyZWF0ZVJlbW92ZUFjdGlvbihpbmRleCkpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSByZW1vdmVBY3Rpb25cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKHJlbW92ZUFjdGlvbikge1xuICAgICAgICByZW1vdmVBY3Rpb24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBpbmRleFxuICAgICAqIEByZXR1cm5zIHtFbGVtZW50IHwgbnVsbH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVSZW1vdmVBY3Rpb24gKGluZGV4KSB7XG4gICAgICAgIGxldCByZW1vdmVBY3Rpb25Db250YWluZXIgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgcmVtb3ZlQWN0aW9uQ29udGFpbmVyLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhIGZhLXRyYXNoLW8ganMtcmVtb3ZlXCIgdGl0bGU9XCJSZW1vdmUgdGhpcyBjb29raWVcIiBkYXRhLWZvcj1cImNvb2tpZS1kYXRhLXJvdy0nICsgaW5kZXggKyAnXCI+PC9pPic7XG5cbiAgICAgICAgcmV0dXJuIHJlbW92ZUFjdGlvbkNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcuanMtcmVtb3ZlJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtOb2RlfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVRhYmxlUm93ICgpIHtcbiAgICAgICAgbGV0IG5leHRDb29raWVJbmRleCA9IHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbmV4dC1jb29raWUtaW5kZXgnKTtcbiAgICAgICAgbGV0IGxhc3RUYWJsZVJvdyA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuanMtY29va2llJyk7XG4gICAgICAgIGxldCB0YWJsZVJvdyA9IGxhc3RUYWJsZVJvdy5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgICAgIGxldCBuYW1lSW5wdXQgPSB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcubmFtZSBpbnB1dCcpO1xuICAgICAgICBsZXQgdmFsdWVJbnB1dCA9IHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy52YWx1ZSBpbnB1dCcpO1xuXG4gICAgICAgIG5hbWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICBuYW1lSW5wdXQuc2V0QXR0cmlidXRlKCduYW1lJywgJ2Nvb2tpZXNbJyArIG5leHRDb29raWVJbmRleCArICddW25hbWVdJyk7XG4gICAgICAgIG5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXl1cCcsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHZhbHVlSW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgdmFsdWVJbnB1dC5zZXRBdHRyaWJ1dGUoJ25hbWUnLCAnY29va2llc1snICsgbmV4dENvb2tpZUluZGV4ICsgJ11bdmFsdWVdJyk7XG4gICAgICAgIHZhbHVlSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5dXAnLCB0aGlzLl9pbnB1dEtleURvd25FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuXG4gICAgICAgIHRhYmxlUm93LnNldEF0dHJpYnV0ZSgnZGF0YS1pbmRleCcsIG5leHRDb29raWVJbmRleCk7XG4gICAgICAgIHRhYmxlUm93LnNldEF0dHJpYnV0ZSgnaWQnLCAnY29va2llLWRhdGEtcm93LScgKyBuZXh0Q29va2llSW5kZXgpO1xuICAgICAgICB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcucmVtb3ZlJykuaW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LnNldEF0dHJpYnV0ZSgnZGF0YS1uZXh0LWNvb2tpZS1pbmRleCcsIHBhcnNlSW50KG5leHRDb29raWVJbmRleCwgMTApICsgMSk7XG5cbiAgICAgICAgcmV0dXJuIHRhYmxlUm93O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBpc0VtcHR5ICgpIHtcbiAgICAgICAgbGV0IGlzRW1wdHkgPSB0cnVlO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW5wdXQnKSwgKGlucHV0KSA9PiB7XG4gICAgICAgICAgICBpZiAoaXNFbXB0eSAmJiBpbnB1dC52YWx1ZS50cmltKCkgIT09ICcnKSB7XG4gICAgICAgICAgICAgICAgaXNFbXB0eSA9IGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gaXNFbXB0eTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IENvb2tpZU9wdGlvbnNNb2RhbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2Nvb2tpZS1vcHRpb25zLW1vZGFsLmpzIiwibGV0IEljb24gPSByZXF1aXJlKCcuL2ljb24nKTtcblxuY2xhc3MgRm9ybUJ1dHRvbiB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgbGV0IGljb25FbGVtZW50ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKEljb24uZ2V0U2VsZWN0b3IoKSk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5pY29uID0gaWNvbkVsZW1lbnQgPyBuZXcgSWNvbihpY29uRWxlbWVudCkgOiBudWxsO1xuICAgIH1cblxuICAgIG1hcmtBc0J1c3kgKCkge1xuICAgICAgICBpZiAodGhpcy5pY29uKSB7XG4gICAgICAgICAgICB0aGlzLmljb24uc2V0QnVzeSgpO1xuICAgICAgICAgICAgdGhpcy5kZUVtcGhhc2l6ZSgpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgbWFya0FzQXZhaWxhYmxlICgpIHtcbiAgICAgICAgaWYgKHRoaXMuaWNvbikge1xuICAgICAgICAgICAgdGhpcy5pY29uLnNldEF2YWlsYWJsZSgnZmEtY2FyZXQtcmlnaHQnKTtcbiAgICAgICAgICAgIHRoaXMudW5EZUVtcGhhc2l6ZSgpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgbWFya1N1Y2NlZWRlZCAoKSB7XG4gICAgICAgIGlmICh0aGlzLmljb24pIHtcbiAgICAgICAgICAgIHRoaXMuaWNvbi5zZXRTdWNjZXNzZnVsKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBkaXNhYmxlICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnNldEF0dHJpYnV0ZSgnZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgICB9XG5cbiAgICBlbmFibGUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQucmVtb3ZlQXR0cmlidXRlKCdkaXNhYmxlZCcpO1xuICAgIH1cblxuICAgIGRlRW1waGFzaXplICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2RlLWVtcGhhc2l6ZScpO1xuICAgIH07XG5cbiAgICB1bkRlRW1waGFzaXplICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2RlLWVtcGhhc2l6ZScpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gRm9ybUJ1dHRvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uLmpzIiwibGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi8uLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcblxuY2xhc3MgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmlzQWNjb3VudFJlcXVpcmVkTW9kYWwgPSBlbGVtZW50LmNsYXNzTGlzdC5jb250YWlucygnYWNjb3VudC1yZXF1aXJlZC1tb2RhbCcpO1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnVzZXJuYW1lSW5wdXQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPWh0dHAtYXV0aC11c2VybmFtZV0nKTtcbiAgICAgICAgdGhpcy5wYXNzd29yZElucHV0ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbbmFtZT1odHRwLWF1dGgtcGFzc3dvcmRdJyk7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9YXBwbHldJyk7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9Y2xvc2VdJyk7XG4gICAgICAgIHRoaXMuY2xlYXJCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9Y2xlYXJdJyk7XG4gICAgICAgIHRoaXMucHJldmlvdXNVc2VybmFtZSA9IG51bGw7XG4gICAgICAgIHRoaXMucHJldmlvdXNQYXNzd29yZCA9IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0T3BlbmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5vcGVuZWQnO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMtbW9kYWwuY2xvc2VkJztcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5fYWRkRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICB9O1xuXG4gICAgaXNFbXB0eSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnVzZXJuYW1lSW5wdXQudmFsdWUudHJpbSgpID09PSAnJyAmJiB0aGlzLnBhc3N3b3JkSW5wdXQudmFsdWUudHJpbSgpID09PSAnJztcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgbGV0IHNob3duRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNVc2VybmFtZSA9IHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgICAgICB0aGlzLnByZXZpb3VzUGFzc3dvcmQgPSB0aGlzLnBhc3N3b3JkSW5wdXQudmFsdWUudHJpbSgpO1xuXG4gICAgICAgICAgICBsZXQgdXNlcm5hbWUgPSB0aGlzLnVzZXJuYW1lSW5wdXQudmFsdWUudHJpbSgpO1xuICAgICAgICAgICAgbGV0IHBhc3N3b3JkID0gdGhpcy5wYXNzd29yZElucHV0LnZhbHVlLnRyaW0oKTtcblxuICAgICAgICAgICAgbGV0IGZvY3VzZWRJbnB1dCA9ICh1c2VybmFtZSA9PT0gJycgfHwgKHVzZXJuYW1lICE9PSAnJyAmJiBwYXNzd29yZCAhPT0gJycpKVxuICAgICAgICAgICAgICAgID8gdGhpcy51c2VybmFtZUlucHV0XG4gICAgICAgICAgICAgICAgOiB0aGlzLnBhc3N3b3JkSW5wdXQ7XG5cbiAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZm9jdXNlZElucHV0KTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5nZXRPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRkZW5FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5nZXRDbG9zZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZSA9IHRoaXMucHJldmlvdXNVc2VybmFtZTtcbiAgICAgICAgICAgIHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZSA9IHRoaXMucHJldmlvdXNQYXNzd29yZDtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnVzZXJuYW1lSW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgICAgIHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdzaG93bi5icy5tb2RhbCcsIHNob3duRXZlbnRMaXN0ZW5lcik7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRkZW4uYnMubW9kYWwnLCBoaWRkZW5FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5jbGVhckJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsZWFyQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy51c2VybmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2tleWRvd24nLCB0aGlzLl9pbnB1dEtleURvd25FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLnBhc3N3b3JkSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7S2V5Ym9hcmRFdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9pbnB1dEtleURvd25FdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBpZiAoZXZlbnQudHlwZSA9PT0gJ2tleWRvd24nICYmIGV2ZW50LmtleSA9PT0gJ0VudGVyJykge1xuICAgICAgICAgICAgdGhpcy5hcHBseUJ1dHRvbi5jbGljaygpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWw7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwuanMiLCJjbGFzcyBJY29uIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIHN0YXRpYyBnZXRDbGFzcyAoKSB7XG4gICAgICAgIHJldHVybiAnZmEnO1xuICAgIH1cblxuICAgIHN0YXRpYyBnZXRTZWxlY3RvciAoKSB7XG4gICAgICAgIHJldHVybiAnLicgKyBJY29uLmdldENsYXNzKCk7XG4gICAgfTtcblxuICAgIHNldEJ1c3kgKCkge1xuICAgICAgICB0aGlzLnJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMoKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2ZhLXNwaW5uZXInLCAnZmEtc3BpbicpO1xuICAgIH07XG5cbiAgICBzZXRBdmFpbGFibGUgKGFjdGl2ZUljb25DbGFzcyA9IG51bGwpIHtcbiAgICAgICAgdGhpcy5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgaWYgKGFjdGl2ZUljb25DbGFzcyAhPT0gbnVsbCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoYWN0aXZlSWNvbkNsYXNzKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBzZXRTdWNjZXNzZnVsICgpIHtcbiAgICAgICAgdGhpcy5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG4gICAgICAgIHRoaXMuc2V0QXZhaWxhYmxlKCdmYS1jaGVjaycpO1xuICAgIH1cblxuICAgIHJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMgKCkge1xuICAgICAgICBsZXQgY2xhc3Nlc1RvUmV0YWluID0gW1xuICAgICAgICAgICAgSWNvbi5nZXRDbGFzcygpLFxuICAgICAgICAgICAgSWNvbi5nZXRDbGFzcygpICsgJy1mdydcbiAgICAgICAgXTtcblxuICAgICAgICBsZXQgcHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCA9IEljb24uZ2V0Q2xhc3MoKSArICctJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKCFjbGFzc2VzVG9SZXRhaW4uaW5jbHVkZXMoY2xhc3NOYW1lKSAmJiBjbGFzc05hbWUuaW5kZXhPZihwcmVzZW50YXRpb25hbENsYXNzUHJlZml4KSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIGNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBJY29uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvaWNvbi5qcyIsImxldCBQcm9ncmVzc2luZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuL3Byb2dyZXNzaW5nLWxpc3RlZC10ZXN0Jyk7XG5cbmNsYXNzIENyYXdsaW5nTGlzdGVkVGVzdCBleHRlbmRzIFByb2dyZXNzaW5nTGlzdGVkVGVzdCB7XG4gICAgcmVuZGVyRnJvbUxpc3RlZFRlc3QgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgc3VwZXIucmVuZGVyRnJvbUxpc3RlZFRlc3QobGlzdGVkVGVzdCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9jZXNzZWQtdXJsLWNvdW50JykuaW5uZXJUZXh0ID0gbGlzdGVkVGVzdC5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1wcm9jZXNzZWQtdXJsLWNvdW50Jyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuZGlzY292ZXJlZC11cmwtY291bnQnKS5pbm5lclRleHQgPSBsaXN0ZWRUZXN0LmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWRpc2NvdmVyZWQtdXJsLWNvdW50Jyk7XG4gICAgfTtcblxuICAgIGdldFR5cGUgKCkge1xuICAgICAgICByZXR1cm4gJ0NyYXdsaW5nTGlzdGVkVGVzdCc7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBDcmF3bGluZ0xpc3RlZFRlc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9jcmF3bGluZy1saXN0ZWQtdGVzdC5qcyIsImNsYXNzIExpc3RlZFRlc3Qge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuaW5pdChlbGVtZW50KTtcbiAgICB9XG5cbiAgICBpbml0IChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgZW5hYmxlICgpIHt9O1xuXG4gICAgZ2V0VGVzdElkICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGVzdC1pZCcpO1xuICAgIH07XG5cbiAgICBnZXRTdGF0ZSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG4gICAgfVxuXG4gICAgaXNGaW5pc2hlZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdmaW5pc2hlZCcpO1xuICAgIH1cblxuICAgIHJlbmRlckZyb21MaXN0ZWRUZXN0IChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIGlmICh0aGlzLmlzRmluaXNoZWQoKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMuZ2V0U3RhdGUoKSAhPT0gbGlzdGVkVGVzdC5nZXRTdGF0ZSgpKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQucGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQobGlzdGVkVGVzdC5lbGVtZW50LCB0aGlzLmVsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy5pbml0KGxpc3RlZFRlc3QuZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLmVuYWJsZSgpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIGdldFR5cGUgKCkge1xuICAgICAgICByZXR1cm4gJ0xpc3RlZFRlc3QnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTGlzdGVkVGVzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2xpc3RlZC10ZXN0LmpzIiwibGV0IFByb2dyZXNzaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4vcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QnKTtcbmxldCBUZXN0UmVzdWx0UmV0cmlldmVyID0gcmVxdWlyZSgnLi4vLi4vLi4vc2VydmljZXMvdGVzdC1yZXN1bHQtcmV0cmlldmVyJyk7XG5cbmNsYXNzIFByZXBhcmluZ0xpc3RlZFRlc3QgZXh0ZW5kcyBQcm9ncmVzc2luZ0xpc3RlZFRlc3Qge1xuICAgIGVuYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuX2luaXRpYWxpc2VSZXN1bHRSZXRyaWV2ZXIoKTtcbiAgICB9O1xuXG4gICAgX2luaXRpYWxpc2VSZXN1bHRSZXRyaWV2ZXIgKCkge1xuICAgICAgICBsZXQgcHJlcGFyaW5nRWxlbWVudCA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJlcGFyaW5nJyk7XG4gICAgICAgIGxldCB0ZXN0UmVzdWx0c1JldHJpZXZlciA9IG5ldyBUZXN0UmVzdWx0UmV0cmlldmVyKHByZXBhcmluZ0VsZW1lbnQpO1xuXG4gICAgICAgIHByZXBhcmluZ0VsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih0ZXN0UmVzdWx0c1JldHJpZXZlci5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgKHJldHJpZXZlZEV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFyZW50Tm9kZSA9IHRoaXMuZWxlbWVudC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgbGV0IHJldHJpZXZlZExpc3RlZFRlc3QgPSByZXRyaWV2ZWRFdmVudC5kZXRhaWw7XG4gICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmNsYXNzTGlzdC5hZGQoJ2ZhZGUtb3V0Jyk7XG5cbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCd0cmFuc2l0aW9uZW5kJywgKCkgPT4ge1xuICAgICAgICAgICAgICAgIHBhcmVudE5vZGUucmVwbGFjZUNoaWxkKHJldHJpZXZlZExpc3RlZFRlc3QsIHRoaXMuZWxlbWVudCk7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50ID0gcmV0cmlldmVkRXZlbnQuZGV0YWlsO1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdmYWRlLWluJyk7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2ZhZGUtb3V0Jyk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2ZhZGUtb3V0Jyk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRlc3RSZXN1bHRzUmV0cmlldmVyLmluaXQoKTtcbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnUHJlcGFyaW5nTGlzdGVkVGVzdCc7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBQcmVwYXJpbmdMaXN0ZWRUZXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJlcGFyaW5nLWxpc3RlZC10ZXN0LmpzIiwibGV0IExpc3RlZFRlc3QgPSByZXF1aXJlKCcuL2xpc3RlZC10ZXN0Jyk7XG5sZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9wcm9ncmVzcy1iYXInKTtcblxuY2xhc3MgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0IGV4dGVuZHMgTGlzdGVkVGVzdCB7XG4gICAgaW5pdCAoZWxlbWVudCkge1xuICAgICAgICBzdXBlci5pbml0KGVsZW1lbnQpO1xuXG4gICAgICAgIGxldCBwcm9ncmVzc0JhckVsZW1lbnQgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByb2dyZXNzLWJhcicpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyID0gcHJvZ3Jlc3NCYXJFbGVtZW50ID8gbmV3IFByb2dyZXNzQmFyKHByb2dyZXNzQmFyRWxlbWVudCkgOiBudWxsO1xuICAgIH1cblxuICAgIGdldENvbXBsZXRpb25QZXJjZW50ICgpIHtcbiAgICAgICAgbGV0IGNvbXBsZXRpb25QZXJjZW50ID0gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1jb21wbGV0aW9uLXBlcmNlbnQnKTtcblxuICAgICAgICBpZiAodGhpcy5pc0ZpbmlzaGVkKCkgJiYgY29tcGxldGlvblBlcmNlbnQgPT09IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybiAxMDA7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gcGFyc2VGbG9hdCh0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWNvbXBsZXRpb24tcGVyY2VudCcpKTtcbiAgICB9XG5cbiAgICBzZXRDb21wbGV0aW9uUGVyY2VudCAoY29tcGxldGlvblBlcmNlbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnNldEF0dHJpYnV0ZSgnZGF0YS1jb21wbGV0aW9uLXBlcmNlbnQnLCBjb21wbGV0aW9uUGVyY2VudCk7XG4gICAgfTtcblxuICAgIHJlbmRlckZyb21MaXN0ZWRUZXN0IChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIHN1cGVyLnJlbmRlckZyb21MaXN0ZWRUZXN0KGxpc3RlZFRlc3QpO1xuXG4gICAgICAgIGlmICh0aGlzLmdldENvbXBsZXRpb25QZXJjZW50KCkgPT09IGxpc3RlZFRlc3QuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5zZXRDb21wbGV0aW9uUGVyY2VudChsaXN0ZWRUZXN0LmdldENvbXBsZXRpb25QZXJjZW50KCkpO1xuXG4gICAgICAgIGlmICh0aGlzLnByb2dyZXNzQmFyKSB7XG4gICAgICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldENvbXBsZXRpb25QZXJjZW50KHRoaXMuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0JztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFByb2dyZXNzaW5nTGlzdGVkVGVzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L3Byb2dyZXNzaW5nLWxpc3RlZC10ZXN0LmpzIiwiY2xhc3MgUHJvZ3Jlc3NCYXIge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgc2V0Q29tcGxldGlvblBlcmNlbnQgKGNvbXBsZXRpb25QZXJjZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5zdHlsZS53aWR0aCA9IGNvbXBsZXRpb25QZXJjZW50ICsgJyUnO1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdhcmlhLXZhbHVlbm93JywgY29tcGxldGlvblBlcmNlbnQpO1xuICAgIH1cblxuICAgIHNldFN0eWxlIChzdHlsZSkge1xuICAgICAgICB0aGlzLl9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMoKTtcblxuICAgICAgICBpZiAoc3R5bGUgPT09ICd3YXJuaW5nJykge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ3Byb2dyZXNzLWJhci13YXJuaW5nJyk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBfcmVtb3ZlUHJlc2VudGF0aW9uYWxDbGFzc2VzICgpIHtcbiAgICAgICAgbGV0IHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXggPSAncHJvZ3Jlc3MtYmFyLSc7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5mb3JFYWNoKChjbGFzc05hbWUsIGluZGV4LCBjbGFzc0xpc3QpID0+IHtcbiAgICAgICAgICAgIGlmIChjbGFzc05hbWUuaW5kZXhPZihwcmVzZW50YXRpb25hbENsYXNzUHJlZml4KSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIGNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBQcm9ncmVzc0JhcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhci5qcyIsImNsYXNzIFNvcnRDb250cm9sIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmtleXMgPSBKU09OLnBhcnNlKGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvcnQta2V5cycpKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0U29ydFJlcXVlc3RlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnc29ydC1jb250cm9sLnNvcnQucmVxdWVzdGVkJztcbiAgICB9O1xuXG4gICAgX2NsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIGlmICh0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdzb3J0ZWQnKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFNvcnRDb250cm9sLmdldFNvcnRSZXF1ZXN0ZWRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgZGV0YWlsOiB7XG4gICAgICAgICAgICAgICAga2V5czogdGhpcy5rZXlzXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pKTtcbiAgICB9O1xuXG4gICAgc2V0U29ydGVkICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ3NvcnRlZCcpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnbGluaycpO1xuICAgIH07XG5cbiAgICBzZXROb3RTb3J0ZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnc29ydGVkJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdsaW5rJyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0Q29udHJvbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnQtY29udHJvbC5qcyIsImNsYXNzIFNvcnRhYmxlSXRlbSB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zb3J0VmFsdWVzID0gSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zb3J0LXZhbHVlcycpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IGtleVxuICAgICAqXG4gICAgICogQHJldHVybnMgeyp9XG4gICAgICovXG4gICAgZ2V0U29ydFZhbHVlIChrZXkpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuc29ydFZhbHVlc1trZXldO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0YWJsZUl0ZW07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtLmpzIiwibGV0IFNjcm9sbFRvID0gcmVxdWlyZSgnLi4vLi4vc2Nyb2xsLXRvJyk7XG5cbmNsYXNzIFN1bW1hcnlTdGF0c0xhYmVsIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgbGV0IGhyZWYgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdocmVmJyk7XG4gICAgICAgIGlmIChocmVmKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9jbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IGNvdW50XG4gICAgICovXG4gICAgc2V0Q291bnQgKGNvdW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY291bnQnKS5pbm5lclRleHQgPSBjb3VudDtcblxuICAgICAgICBpZiAoY291bnQgPT09IDEpIHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdzaW5ndWxhcicpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ3Npbmd1bGFyJyk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIGdldElzc3VlVHlwZSAoKSB7XG4gICAgICAgIGxldCBpc3N1ZVR5cGUgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWlzc3VlLXR5cGUnKTtcblxuICAgICAgICByZXR1cm4gaXNzdWVUeXBlID09PSAnJyA/IG51bGwgOiBpc3N1ZVR5cGU7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jbGlja0V2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgbGV0IGFuY2hvckVsZW1lbnQgPSBudWxsO1xuXG4gICAgICAgIGV2ZW50LnBhdGguZm9yRWFjaChmdW5jdGlvbiAocGF0aEVsZW1lbnQpIHtcbiAgICAgICAgICAgIGlmICghYW5jaG9yRWxlbWVudCAmJiBwYXRoRWxlbWVudC5ub2RlTmFtZSA9PT0gJ0EnKSB7XG4gICAgICAgICAgICAgICAgYW5jaG9yRWxlbWVudCA9IHBhdGhFbGVtZW50O1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBsZXQgdGFyZ2V0SWQgPSBhbmNob3JFbGVtZW50LmdldEF0dHJpYnV0ZSgnaHJlZicpLnJlcGxhY2UoJyMnLCAnJyk7XG4gICAgICAgIGxldCB0YXJnZXQgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRJZCk7XG5cbiAgICAgICAgaWYgKHRhcmdldCkge1xuICAgICAgICAgICAgU2Nyb2xsVG8uc2Nyb2xsVG8odGFyZ2V0LCAtNTApO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTdW1tYXJ5U3RhdHNMYWJlbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3N1bW1hcnktc3RhdHMtbGFiZWwuanMiLCJsZXQgVGFzayA9IHJlcXVpcmUoJy4vdGFzaycpO1xuXG5jbGFzcyBUYXNrTGlzdCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5wYWdlSW5kZXggPSBlbGVtZW50ID8gcGFyc2VJbnQoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMCkgOiBudWxsO1xuICAgICAgICB0aGlzLnRhc2tzID0ge307XG5cbiAgICAgICAgaWYgKGVsZW1lbnQpIHtcbiAgICAgICAgICAgIFtdLmZvckVhY2guY2FsbChlbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrJyksICh0YXNrRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCB0YXNrID0gbmV3IFRhc2sodGFza0VsZW1lbnQpO1xuICAgICAgICAgICAgICAgIHRoaXMudGFza3NbdGFzay5nZXRJZCgpXSA9IHRhc2s7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtudW1iZXIgfCBudWxsfVxuICAgICAqL1xuICAgIGdldFBhZ2VJbmRleCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnBhZ2VJbmRleDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBoYXNQYWdlSW5kZXggKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5wYWdlSW5kZXggIT09IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmdbXX0gc3RhdGVzXG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7VGFza1tdfVxuICAgICAqL1xuICAgIGdldFRhc2tzQnlTdGF0ZXMgKHN0YXRlcykge1xuICAgICAgICBjb25zdCBzdGF0ZXNMZW5ndGggPSBzdGF0ZXMubGVuZ3RoO1xuICAgICAgICBsZXQgdGFza3MgPSBbXTtcblxuICAgICAgICBmb3IgKGxldCBzdGF0ZUluZGV4ID0gMDsgc3RhdGVJbmRleCA8IHN0YXRlc0xlbmd0aDsgc3RhdGVJbmRleCsrKSB7XG4gICAgICAgICAgICBsZXQgc3RhdGUgPSBzdGF0ZXNbc3RhdGVJbmRleF07XG5cbiAgICAgICAgICAgIHRhc2tzID0gdGFza3MuY29uY2F0KHRoaXMuZ2V0VGFza3NCeVN0YXRlKHN0YXRlKSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFza3M7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBzdGF0ZVxuICAgICAqXG4gICAgICogQHJldHVybnMge1Rhc2tbXX1cbiAgICAgKi9cbiAgICBnZXRUYXNrc0J5U3RhdGUgKHN0YXRlKSB7XG4gICAgICAgIGxldCB0YXNrcyA9IFtdO1xuICAgICAgICBPYmplY3Qua2V5cyh0aGlzLnRhc2tzKS5mb3JFYWNoKCh0YXNrSWQpID0+IHtcbiAgICAgICAgICAgIGxldCB0YXNrID0gdGhpcy50YXNrc1t0YXNrSWRdO1xuXG4gICAgICAgICAgICBpZiAodGFzay5nZXRTdGF0ZSgpID09PSBzdGF0ZSkge1xuICAgICAgICAgICAgICAgIHRhc2tzLnB1c2godGFzayk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiB0YXNrcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtUYXNrTGlzdH0gdXBkYXRlZFRhc2tMaXN0XG4gICAgICovXG4gICAgdXBkYXRlRnJvbVRhc2tMaXN0ICh1cGRhdGVkVGFza0xpc3QpIHtcbiAgICAgICAgT2JqZWN0LmtleXModXBkYXRlZFRhc2tMaXN0LnRhc2tzKS5mb3JFYWNoKCh0YXNrSWQpID0+IHtcbiAgICAgICAgICAgIGxldCB1cGRhdGVkVGFzayA9IHVwZGF0ZWRUYXNrTGlzdC50YXNrc1t0YXNrSWRdO1xuICAgICAgICAgICAgbGV0IHRhc2sgPSB0aGlzLnRhc2tzW3VwZGF0ZWRUYXNrLmdldElkKCldO1xuXG4gICAgICAgICAgICB0YXNrLnVwZGF0ZUZyb21UYXNrKHVwZGF0ZWRUYXNrKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdC5qcyIsImNsYXNzIFRhc2tRdWV1ZSB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy52YWx1ZSA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnZhbHVlJyk7XG4gICAgICAgIHRoaXMubGFiZWwgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5sYWJlbC12YWx1ZScpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmxhYmVsLnN0eWxlLndpZHRoID0gdGhpcy5sYWJlbC5nZXRBdHRyaWJ1dGUoJ2RhdGEtd2lkdGgnKSArICclJztcbiAgICB9O1xuXG4gICAgZ2V0UXVldWVJZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXF1ZXVlLWlkJyk7XG4gICAgfTtcblxuICAgIHNldFZhbHVlICh2YWx1ZSkge1xuICAgICAgICB0aGlzLnZhbHVlLmlubmVyVGV4dCA9IHZhbHVlO1xuICAgIH07XG5cbiAgICBzZXRXaWR0aCAod2lkdGgpIHtcbiAgICAgICAgdGhpcy5sYWJlbC5zdHlsZS53aWR0aCA9IHdpZHRoICsgJyUnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza1F1ZXVlO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZS5qcyIsImxldCBUYXNrUXVldWUgPSByZXF1aXJlKCcuL3Rhc2stcXVldWUnKTtcblxuY2xhc3MgVGFza1F1ZXVlcyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5xdWV1ZXMgPSB7fTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwoZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcucXVldWUnKSwgKHF1ZXVlRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHF1ZXVlID0gbmV3IFRhc2tRdWV1ZShxdWV1ZUVsZW1lbnQpO1xuICAgICAgICAgICAgcXVldWUuaW5pdCgpO1xuICAgICAgICAgICAgdGhpcy5xdWV1ZXNbcXVldWUuZ2V0UXVldWVJZCgpXSA9IHF1ZXVlO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICByZW5kZXIgKHRhc2tDb3VudCwgdGFza0NvdW50QnlTdGF0ZSkge1xuICAgICAgICBsZXQgZ2V0V2lkdGhGb3JTdGF0ZSA9IChzdGF0ZSkgPT4ge1xuICAgICAgICAgICAgaWYgKHRhc2tDb3VudCA9PT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoIXRhc2tDb3VudEJ5U3RhdGUuaGFzT3duUHJvcGVydHkoc3RhdGUpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIDA7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0YXNrQ291bnRCeVN0YXRlW3N0YXRlXSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICByZXR1cm4gTWF0aC5jZWlsKHRhc2tDb3VudEJ5U3RhdGVbc3RhdGVdIC8gdGFza0NvdW50ICogMTAwKTtcbiAgICAgICAgfTtcblxuICAgICAgICBPYmplY3Qua2V5cyh0YXNrQ291bnRCeVN0YXRlKS5mb3JFYWNoKChzdGF0ZSkgPT4ge1xuICAgICAgICAgICAgbGV0IHF1ZXVlID0gdGhpcy5xdWV1ZXNbc3RhdGVdO1xuICAgICAgICAgICAgaWYgKHF1ZXVlKSB7XG4gICAgICAgICAgICAgICAgcXVldWUuc2V0VmFsdWUodGFza0NvdW50QnlTdGF0ZVtzdGF0ZV0pO1xuICAgICAgICAgICAgICAgIHF1ZXVlLnNldFdpZHRoKGdldFdpZHRoRm9yU3RhdGUoc3RhdGUpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrUXVldWVzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZXMuanMiLCJjbGFzcyBUYXNrIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIGdldFN0YXRlICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcbiAgICB9O1xuXG4gICAgZ2V0SWQgKCkge1xuICAgICAgICByZXR1cm4gcGFyc2VJbnQodGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLWlkJyksIDEwKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1Rhc2t9IHVwZGF0ZWRUYXNrXG4gICAgICovXG4gICAgdXBkYXRlRnJvbVRhc2sgKHVwZGF0ZWRUYXNrKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZCh1cGRhdGVkVGFzay5lbGVtZW50LCB0aGlzLmVsZW1lbnQpO1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB1cGRhdGVkVGFzay5lbGVtZW50O1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFzaztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2suanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5sZXQgQWxlcnRGYWN0b3J5ID0gcmVxdWlyZSgnLi4vLi4vc2VydmljZXMvYWxlcnQtZmFjdG9yeScpO1xuXG5jbGFzcyBUZXN0QWxlcnRDb250YWluZXIge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuYWxlcnQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5hbGVydC1hbW1lbmRtZW50Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGlmICghdGhpcy5hbGVydCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IGFsZXJ0ID0gQWxlcnRGYWN0b3J5LmNyZWF0ZUZyb21Db250ZW50KHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LCBldmVudC5kZXRhaWwucmVzcG9uc2UpO1xuICAgICAgICBhbGVydC5zZXRTdHlsZSgnaW5mbycpO1xuICAgICAgICBhbGVydC53cmFwQ29udGVudEluQ29udGFpbmVyKCk7XG4gICAgICAgIGFsZXJ0LmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWxlcnQtYW1tZW5kbWVudCcpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChhbGVydC5lbGVtZW50KTtcbiAgICAgICAgdGhpcy5hbGVydCA9IGFsZXJ0O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdyZXZlYWwnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3RyYW5zaXRpb25lbmQnLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmFsZXJ0LmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgncmV2ZWFsJyk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICByZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbiAoKSB7XG4gICAgICAgIGlmICghdGhpcy5hbGVydCkge1xuICAgICAgICAgICAgSHR0cENsaWVudC5nZXQodGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11cmwtbGltaXQtbm90aWZpY2F0aW9uLXVybCcpLCAndGV4dCcsIHRoaXMuZWxlbWVudCwgJ2RlZmF1bHQnKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdEFsZXJ0Q29udGFpbmVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXIuanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5sZXQgSWNvbiA9IHJlcXVpcmUoJy4uL2VsZW1lbnQvaWNvbicpO1xuXG5jbGFzcyBUZXN0TG9ja1VubG9jayB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG4gICAgICAgIHRoaXMuZGF0YSA9IHtcbiAgICAgICAgICAgIGxvY2tlZDogSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1sb2NrZWQnKSksXG4gICAgICAgICAgICB1bmxvY2tlZDogSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11bmxvY2tlZCcpKVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmljb24gPSBuZXcgSWNvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoSWNvbi5nZXRTZWxlY3RvcigpKSk7XG4gICAgICAgIHRoaXMuYWN0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuYWN0aW9uJyk7XG4gICAgICAgIHRoaXMuZGVzY3JpcHRpb24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5kZXNjcmlwdGlvbicpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnaW52aXNpYmxlJyk7XG4gICAgICAgIHRoaXMuX3JlbmRlcigpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2RlLWVtcGhhc2l6ZScpO1xuICAgICAgICAgICAgdGhpcy5fdG9nZ2xlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfdG9nZ2xlICgpIHtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IHRoaXMuc3RhdGUgPT09ICdsb2NrZWQnID8gJ3VubG9ja2VkJyA6ICdsb2NrZWQnO1xuICAgICAgICB0aGlzLl9yZW5kZXIoKTtcbiAgICB9O1xuXG4gICAgX3JlbmRlciAoKSB7XG4gICAgICAgIHRoaXMuaWNvbi5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgbGV0IHN0YXRlRGF0YSA9IHRoaXMuZGF0YVt0aGlzLnN0YXRlXTtcblxuICAgICAgICB0aGlzLmljb24uc2V0QXZhaWxhYmxlKCdmYS0nICsgc3RhdGVEYXRhLmljb24pO1xuICAgICAgICB0aGlzLmFjdGlvbi5pbm5lclRleHQgPSBzdGF0ZURhdGEuYWN0aW9uO1xuICAgICAgICB0aGlzLmRlc2NyaXB0aW9uLmlubmVyVGV4dCA9IHN0YXRlRGF0YS5kZXNjcmlwdGlvbjtcbiAgICB9O1xuXG4gICAgX2NsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHRoaXMuaWNvbi5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2RlLWVtcGhhc2l6ZScpO1xuICAgICAgICB0aGlzLmljb24uc2V0QnVzeSgpO1xuXG4gICAgICAgIEh0dHBDbGllbnQucG9zdCh0aGlzLmRhdGFbdGhpcy5zdGF0ZV0udXJsLCB0aGlzLmVsZW1lbnQsICdkZWZhdWx0Jyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0TG9ja1VubG9jaztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rlc3QtbG9jay11bmxvY2suanMiLCJsZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbCcpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zIHtcbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQsIGh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCwgYWN0aW9uQmFkZ2UsIHN0YXR1c0VsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IGh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbDtcbiAgICAgICAgdGhpcy5hY3Rpb25CYWRnZSA9IGFjdGlvbkJhZGdlO1xuICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQgPSBzdGF0dXNFbGVtZW50O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbE9wZW5lZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLm1vZGFsLm9wZW5lZCc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldE1vZGFsQ2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdodHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMubW9kYWwuY2xvc2VkJztcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGlmICghdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwuaXNBY2NvdW50UmVxdWlyZWRNb2RhbCkge1xuICAgICAgICAgICAgbGV0IG1vZGFsQ2xvc2VFdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5pc0VtcHR5KCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zdGF0dXNFbGVtZW50LmlubmVyVGV4dCA9ICdub3QgZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYWN0aW9uQmFkZ2UubWFya05vdEVuYWJsZWQoKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ2VuYWJsZWQnO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFjdGlvbkJhZGdlLm1hcmtFbmFibGVkKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwuaW5pdCgpO1xuXG4gICAgICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsT3BlbmVkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgbW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnM7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLmpzIiwibGV0IExpc3RlZFRlc3RGYWN0b3J5ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvbGlzdGVkLXRlc3QtZmFjdG9yeScpO1xuXG5jbGFzcyBMaXN0ZWRUZXN0Q29sbGVjdGlvbiB7XG4gICAgY29uc3RydWN0b3IgKCkge1xuICAgICAgICB0aGlzLmxpc3RlZFRlc3RzID0ge307XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtMaXN0ZWRUZXN0fSBsaXN0ZWRUZXN0XG4gICAgICovXG4gICAgYWRkIChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIHRoaXMubGlzdGVkVGVzdHNbbGlzdGVkVGVzdC5nZXRUZXN0SWQoKV0gPSBsaXN0ZWRUZXN0O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0xpc3RlZFRlc3R9IGxpc3RlZFRlc3RcbiAgICAgKi9cbiAgICByZW1vdmUgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgaWYgKHRoaXMuY29udGFpbnMobGlzdGVkVGVzdCkpIHtcbiAgICAgICAgICAgIGRlbGV0ZSB0aGlzLmxpc3RlZFRlc3RzW2xpc3RlZFRlc3QuZ2V0VGVzdElkKCldO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7TGlzdGVkVGVzdH0gbGlzdGVkVGVzdFxuICAgICAqXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgY29udGFpbnMgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuY29udGFpbnNUZXN0SWQobGlzdGVkVGVzdC5nZXRUZXN0SWQoKSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSB0ZXN0SWRcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGNvbnRhaW5zVGVzdElkICh0ZXN0SWQpIHtcbiAgICAgICAgcmV0dXJuIE9iamVjdC5rZXlzKHRoaXMubGlzdGVkVGVzdHMpLmluY2x1ZGVzKHRlc3RJZCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHRlc3RJZFxuICAgICAqXG4gICAgICogQHJldHVybnMge0xpc3RlZFRlc3R8bnVsbH1cbiAgICAgKi9cbiAgICBnZXQgKHRlc3RJZCkge1xuICAgICAgICByZXR1cm4gdGhpcy5jb250YWluc1Rlc3RJZCh0ZXN0SWQpID8gdGhpcy5saXN0ZWRUZXN0c1t0ZXN0SWRdIDogbnVsbDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge2Z1bmN0aW9ufSBjYWxsYmFja1xuICAgICAqL1xuICAgIGZvckVhY2ggKGNhbGxiYWNrKSB7XG4gICAgICAgIE9iamVjdC5rZXlzKHRoaXMubGlzdGVkVGVzdHMpLmZvckVhY2goKHRlc3RJZCkgPT4ge1xuICAgICAgICAgICAgY2FsbGJhY2sodGhpcy5saXN0ZWRUZXN0c1t0ZXN0SWRdKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7Tm9kZUxpc3R9IG5vZGVMaXN0XG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7TGlzdGVkVGVzdENvbGxlY3Rpb259XG4gICAgICovXG4gICAgc3RhdGljIGNyZWF0ZUZyb21Ob2RlTGlzdCAobm9kZUxpc3QpIHtcbiAgICAgICAgbGV0IGNvbGxlY3Rpb24gPSBuZXcgTGlzdGVkVGVzdENvbGxlY3Rpb24oKTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwobm9kZUxpc3QsIChsaXN0ZWRUZXN0RWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgY29sbGVjdGlvbi5hZGQoTGlzdGVkVGVzdEZhY3RvcnkuY3JlYXRlRnJvbUVsZW1lbnQobGlzdGVkVGVzdEVsZW1lbnQpKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGNvbGxlY3Rpb247XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IExpc3RlZFRlc3RDb2xsZWN0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24uanMiLCJjbGFzcyBTb3J0Q29udHJvbENvbGxlY3Rpb24ge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7U29ydENvbnRyb2xbXX0gY29udHJvbHNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoY29udHJvbHMpIHtcbiAgICAgICAgdGhpcy5jb250cm9scyA9IGNvbnRyb2xzO1xuICAgIH07XG5cbiAgICBzZXRTb3J0ZWQgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5jb250cm9scy5mb3JFYWNoKChjb250cm9sKSA9PiB7XG4gICAgICAgICAgICBpZiAoY29udHJvbC5lbGVtZW50ID09PSBlbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgY29udHJvbC5zZXRTb3J0ZWQoKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgY29udHJvbC5zZXROb3RTb3J0ZWQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0Q29udHJvbENvbGxlY3Rpb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvc29ydC1jb250cm9sLWNvbGxlY3Rpb24uanMiLCJjbGFzcyBTb3J0YWJsZUl0ZW1MaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1NvcnRhYmxlSXRlbVtdfSBpdGVtc1xuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChpdGVtcykge1xuICAgICAgICB0aGlzLml0ZW1zID0gaXRlbXM7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nW119IGtleXNcbiAgICAgKiBAcmV0dXJucyB7U29ydGFibGVJdGVtW119XG4gICAgICovXG4gICAgc29ydCAoa2V5cykge1xuICAgICAgICBsZXQgaW5kZXggPSBbXTtcbiAgICAgICAgbGV0IHNvcnRlZEl0ZW1zID0gW107XG5cbiAgICAgICAgdGhpcy5pdGVtcy5mb3JFYWNoKChzb3J0YWJsZUl0ZW0sIHBvc2l0aW9uKSA9PiB7XG4gICAgICAgICAgICBsZXQgdmFsdWVzID0gW107XG5cbiAgICAgICAgICAgIGtleXMuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgICAgICAgICAgICAgbGV0IHZhbHVlID0gc29ydGFibGVJdGVtLmdldFNvcnRWYWx1ZShrZXkpO1xuICAgICAgICAgICAgICAgIGlmIChOdW1iZXIuaXNJbnRlZ2VyKHZhbHVlKSkge1xuICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9ICgxIC8gdmFsdWUpLnRvU3RyaW5nKCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgdmFsdWVzLnB1c2godmFsdWUpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGluZGV4LnB1c2goe1xuICAgICAgICAgICAgICAgIHBvc2l0aW9uOiBwb3NpdGlvbixcbiAgICAgICAgICAgICAgICB2YWx1ZTogdmFsdWVzLmpvaW4oJywnKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGluZGV4LnNvcnQodGhpcy5fY29tcGFyZUZ1bmN0aW9uKTtcblxuICAgICAgICBpbmRleC5mb3JFYWNoKChpbmRleEl0ZW0pID0+IHtcbiAgICAgICAgICAgIHNvcnRlZEl0ZW1zLnB1c2godGhpcy5pdGVtc1tpbmRleEl0ZW0ucG9zaXRpb25dKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIHNvcnRlZEl0ZW1zO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge09iamVjdH0gYVxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBiXG4gICAgICogQHJldHVybnMge251bWJlcn1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jb21wYXJlRnVuY3Rpb24gKGEsIGIpIHtcbiAgICAgICAgaWYgKGEudmFsdWUgPCBiLnZhbHVlKSB7XG4gICAgICAgICAgICByZXR1cm4gLTE7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoYS52YWx1ZSA+IGIudmFsdWUpIHtcbiAgICAgICAgICAgIHJldHVybiAxO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIDA7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0YWJsZUl0ZW1MaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL3NvcnRhYmxlLWl0ZW0tbGlzdC5qcyIsImxldCB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlciA9IHJlcXVpcmUoJy4uL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlcicpO1xubGV0IFRlc3RTdGFydEZvcm0gPSByZXF1aXJlKCcuLi9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtJyk7XG5sZXQgUmVjZW50VGVzdExpc3QgPSByZXF1aXJlKCcuLi9kYXNoYm9hcmQvcmVjZW50LXRlc3QtbGlzdCcpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zJyk7XG5sZXQgQ29va2llT3B0aW9uc0ZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5Jyk7XG5sZXQgQ29va2llT3B0aW9ucyA9IHJlcXVpcmUoJy4uL21vZGVsL2Nvb2tpZS1vcHRpb25zJyk7XG5cbmNsYXNzIERhc2hib2FyZCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLnRlc3RTdGFydEZvcm0gPSBuZXcgVGVzdFN0YXJ0Rm9ybShkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGVzdC1zdGFydC1mb3JtJykpO1xuICAgICAgICB0aGlzLnJlY2VudFRlc3RMaXN0ID0gbmV3IFJlY2VudFRlc3RMaXN0KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy50ZXN0LWxpc3QnKSk7XG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5LmNyZWF0ZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuaHR0cC1hdXRoZW50aWNhdGlvbi10ZXN0LW9wdGlvbicpKTtcbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zID0gQ29va2llT3B0aW9uc0ZhY3RvcnkuY3JlYXRlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jb29raWVzLXRlc3Qtb3B0aW9uJykpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5yZWNlbnQtYWN0aXZpdHktY29udGFpbmVyJykuY2xhc3NMaXN0LnJlbW92ZSgnaGlkZGVuJyk7XG5cbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIodGhpcy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzay10eXBlLm5vdC1hdmFpbGFibGUnKSk7XG4gICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybS5pbml0KCk7XG4gICAgICAgIHRoaXMucmVjZW50VGVzdExpc3QuaW5pdCgpO1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuaW5pdCgpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMuaW5pdCgpO1xuXG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsT3BlbmVkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybS5kaXNhYmxlKCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsQ2xvc2VkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybS5lbmFibGUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmRpc2FibGUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmVuYWJsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IERhc2hib2FyZDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL2Rhc2hib2FyZC5qcyIsImxldCBJc3N1ZVNlY3Rpb24gPSByZXF1aXJlKCcuLi90YXNrLXJlc3VsdHMvaXNzdWUtc2VjdGlvbicpO1xubGV0IFN1bW1hcnlTdGF0cyA9IHJlcXVpcmUoJy4uL3Rhc2stcmVzdWx0cy9zdW1tYXJ5LXN0YXRzJyk7XG5sZXQgSXNzdWVDb250ZW50ID0gcmVxdWlyZSgnLi4vdGFzay1yZXN1bHRzL2lzc3VlLWNvbnRlbnQnKTtcblxuY2xhc3MgVGFza1Jlc3VsdHMge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcblxuICAgICAgICAvKipcbiAgICAgICAgICogQHR5cGUge1N1bW1hcnlTdGF0c1tdfVxuICAgICAgICAgKi9cbiAgICAgICAgdGhpcy5zdW1tYXJ5U3RhdHMgPSBbXTtcblxuICAgICAgICB0aGlzLmlzc3VlQ29udGVudCA9IG5ldyBJc3N1ZUNvbnRlbnQoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmlzc3VlLWNvbnRlbnQnKSk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnN1bW1hcnktc3RhdHMnKSwgKHN1bW1hcnlTdGF0c0VsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIHRoaXMuc3VtbWFyeVN0YXRzLnB1c2gobmV3IFN1bW1hcnlTdGF0cyhzdW1tYXJ5U3RhdHNFbGVtZW50KSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLnN1bW1hcnlTdGF0cy5mb3JFYWNoKChzdW1tYXJ5U3RhdHMpID0+IHtcbiAgICAgICAgICAgIHN1bW1hcnlTdGF0cy5pbml0KCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuaXNzdWVDb250ZW50Lmlzc3VlU2VjdGlvbnMuZm9yRWFjaCgoaXNzdWVTZWN0aW9uKSA9PiB7XG4gICAgICAgICAgICBpc3N1ZVNlY3Rpb24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFxuICAgICAgICAgICAgICAgIElzc3VlU2VjdGlvbi5nZXRJc3N1ZUNvdW50Q2hhbmdlZEV2ZW50TmFtZSgpLFxuICAgICAgICAgICAgICAgIHRoaXMuX2ZpbHRlcmVkSXNzdWVTZWN0aW9uSXNzdWVDb3VudENoYW5nZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcylcbiAgICAgICAgICAgICk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuaXNzdWVDb250ZW50LmluaXQoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9maWx0ZXJlZElzc3VlU2VjdGlvbklzc3VlQ291bnRDaGFuZ2VkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5U3RhdHMuZm9yRWFjaCgoc3VtbWFyeVN0YXRzKSA9PiB7XG4gICAgICAgICAgICBzdW1tYXJ5U3RhdHMuc2V0SXNzdWVDb3VudChldmVudC5kZXRhaWxbJ2lzc3VlLXR5cGUnXSwgZXZlbnQuZGV0YWlsLmNvdW50KTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmlzc3VlLWNvbnRlbnQnKS5pbnNlcnRBZGphY2VudEVsZW1lbnQoJ2FmdGVyYmVnaW4nLCB0aGlzLl9jcmVhdGVGaWx0ZXJOb3RpY2UoKSk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmdyb3VwZWQtaXNzdWVzJyksIChncm91cGVkSXNzdWVzRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgW10uZm9yRWFjaC5jYWxsKGdyb3VwZWRJc3N1ZXNFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5lcnJvci1ncm91cCcpLCAoZXJyb3JHcm91cEVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgICAgICBsZXQgaXNzdWVDb3VudCA9IGVycm9yR3JvdXBFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5pc3N1ZScpLmxlbmd0aDtcblxuICAgICAgICAgICAgICAgIGlmIChpc3N1ZUNvdW50ID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIGdyb3VwZWRJc3N1ZXNFbGVtZW50LnJlbW92ZUNoaWxkKGVycm9yR3JvdXBFbGVtZW50KTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBncm91cGVkSXNzdWVzRWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuaXNzdWUtY291bnQnKS5pbm5lclRleHQgPSBpc3N1ZUNvdW50O1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcblxuICAgICAgICBsZXQgZmlyc3RGaWx0ZXJlZElzc3VlID0gdGhpcy5pc3N1ZUNvbnRlbnQuZ2V0Rmlyc3RGaWx0ZXJlZElzc3VlKCk7XG4gICAgICAgIGxldCBmaXhFbGVtZW50ID0gZmlyc3RGaWx0ZXJlZElzc3VlLnF1ZXJ5U2VsZWN0b3IoJy5maXgnKTtcblxuICAgICAgICBpZiAoZml4RWxlbWVudCkge1xuICAgICAgICAgICAgbGV0IGZpeElzc3VlU2VjdGlvbiA9IHRoaXMuaXNzdWVDb250ZW50LmdldElzc3VlU2VjdGlvbignZml4Jyk7XG5cbiAgICAgICAgICAgIC8qKlxuICAgICAgICAgICAgICogQHR5cGUge0ZpeExpc3R9XG4gICAgICAgICAgICAgKi9cbiAgICAgICAgICAgIGxldCBmaXhMaXN0ID0gZml4SXNzdWVTZWN0aW9uLmlzc3VlTGlzdHNbMF07XG4gICAgICAgICAgICBmaXhMaXN0LmZpbHRlclRvKGZpeEVsZW1lbnQuZ2V0QXR0cmlidXRlKCdocmVmJykpO1xuXG4gICAgICAgICAgICBsZXQgZml4Q291bnQgPSBmaXhMaXN0LmNvdW50KCk7XG4gICAgICAgICAgICBmaXhJc3N1ZVNlY3Rpb24ucmVuZGVySXNzdWVDb3VudChmaXhDb3VudCk7XG5cbiAgICAgICAgICAgIHRoaXMuc3VtbWFyeVN0YXRzLmZvckVhY2goKHN1bW1hcnlTdGF0cykgPT4ge1xuICAgICAgICAgICAgICAgIHN1bW1hcnlTdGF0cy5zZXRJc3N1ZUNvdW50KCdmaXgnLCBmaXhDb3VudCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7RWxlbWVudH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVGaWx0ZXJOb3RpY2UgKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmlubmVySFRNTCA9ICc8cCBjbGFzcz1cImZpbHRlci1ub3RpY2UgbGVhZFwiPlNob3dpbmcgb25seSA8c3BhbiBjbGFzcz1cIm1lc3NhZ2VcIj4mbGRxdW87JyArIHRoaXMuaXNzdWVDb250ZW50LmdldEZpbHRlcmVkSXNzdWVNZXNzYWdlKCkgKyAnJnJkcXVvOzwvc3Bhbj4gZXJyb3JzLiA8YSBocmVmPVwiXCI+U2hvdyBhbGwuPC9hPjwvcD4nO1xuXG4gICAgICAgIHJldHVybiBjb250YWluZXIucXVlcnlTZWxlY3RvcignLmZpbHRlci1ub3RpY2UnKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tSZXN1bHRzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGFzay1yZXN1bHRzLmpzIiwibGV0IE1vZGFsID0gcmVxdWlyZSgnLi4vdGVzdC1oaXN0b3J5L21vZGFsJyk7XG5sZXQgU3VnZ2VzdGlvbnMgPSByZXF1aXJlKCcuLi90ZXN0LWhpc3Rvcnkvc3VnZ2VzdGlvbnMnKTtcbmxldCBMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24nKTtcblxubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoZG9jdW1lbnQpIHtcbiAgICBjb25zdCBtb2RhbElkID0gJ2ZpbHRlci1vcHRpb25zLW1vZGFsJztcbiAgICBjb25zdCBmaWx0ZXJPcHRpb25zU2VsZWN0b3IgPSAnLmFjdGlvbi1iYWRnZS1maWx0ZXItb3B0aW9ucyc7XG4gICAgY29uc3QgbW9kYWxFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobW9kYWxJZCk7XG4gICAgbGV0IGZpbHRlck9wdGlvbnNFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihmaWx0ZXJPcHRpb25zU2VsZWN0b3IpO1xuXG4gICAgbGV0IG1vZGFsID0gbmV3IE1vZGFsKG1vZGFsRWxlbWVudCk7XG4gICAgbGV0IHN1Z2dlc3Rpb25zID0gbmV3IFN1Z2dlc3Rpb25zKGRvY3VtZW50LCBtb2RhbEVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvdXJjZS11cmwnKSk7XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqL1xuICAgIGxldCBzdWdnZXN0aW9uc0xvYWRlZEV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgbW9kYWwuc2V0U3VnZ2VzdGlvbnMoZXZlbnQuZGV0YWlsKTtcbiAgICAgICAgZmlsdGVyT3B0aW9uc0VsZW1lbnQuY2xhc3NMaXN0LmFkZCgndmlzaWJsZScpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqL1xuICAgIGxldCBmaWx0ZXJDaGFuZ2VkRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICBsZXQgZmlsdGVyID0gZXZlbnQuZGV0YWlsO1xuICAgICAgICBsZXQgc2VhcmNoID0gKGZpbHRlciA9PT0gJycpID8gJycgOiAnP2ZpbHRlcj0nICsgZmlsdGVyO1xuICAgICAgICBsZXQgY3VycmVudFNlYXJjaCA9IHdpbmRvdy5sb2NhdGlvbi5zZWFyY2g7XG5cbiAgICAgICAgaWYgKGN1cnJlbnRTZWFyY2ggPT09ICcnICYmIGZpbHRlciA9PT0gJycpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChzZWFyY2ggIT09IGN1cnJlbnRTZWFyY2gpIHtcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5zZWFyY2ggPSBzZWFyY2g7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihzdWdnZXN0aW9ucy5sb2FkZWRFdmVudE5hbWUsIHN1Z2dlc3Rpb25zTG9hZGVkRXZlbnRMaXN0ZW5lcik7XG4gICAgbW9kYWxFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIobW9kYWwuZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSwgZmlsdGVyQ2hhbmdlZEV2ZW50TGlzdGVuZXIpO1xuXG4gICAgc3VnZ2VzdGlvbnMucmV0cmlldmUoKTtcblxuICAgIGxldCBsaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IExpc3RlZFRlc3RDb2xsZWN0aW9uLmNyZWF0ZUZyb21Ob2RlTGlzdChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubGlzdGVkLXRlc3QnKSk7XG4gICAgbGlzdGVkVGVzdENvbGxlY3Rpb24uZm9yRWFjaCgobGlzdGVkVGVzdCkgPT4ge1xuICAgICAgICBsaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgIH0pO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtaGlzdG9yeS5qcyIsImxldCBTdW1tYXJ5ID0gcmVxdWlyZSgnLi4vdGVzdC1wcm9ncmVzcy9zdW1tYXJ5Jyk7XG5sZXQgVGFza0xpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdCcpO1xubGV0IFRhc2tMaXN0UGFnaW5hdGlvbiA9IHJlcXVpcmUoJy4uL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LXBhZ2luYXRvcicpO1xubGV0IFRlc3RBbGVydENvbnRhaW5lciA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXInKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgVGVzdFByb2dyZXNzIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSAxMDA7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zdW1tYXJ5ID0gbmV3IFN1bW1hcnkoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXN1bW1hcnknKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRlc3QtcHJvZ3Jlc3MtdGFza3MnKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdCA9IG5ldyBUYXNrTGlzdCh0aGlzLnRhc2tMaXN0RWxlbWVudCwgdGhpcy5wYWdlTGVuZ3RoKTtcbiAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lciA9IG5ldyBUZXN0QWxlcnRDb250YWluZXIoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmFsZXJ0LWNvbnRhaW5lcicpKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24gPSBudWxsO1xuICAgICAgICB0aGlzLnRhc2tMaXN0SXNJbml0aWFsaXplZCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnN1bW1hcnlEYXRhID0gbnVsbDtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5LmluaXQoKTtcbiAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lci5pbml0KCk7XG4gICAgICAgIHRoaXMuX2FkZEV2ZW50TGlzdGVuZXJzKCk7XG5cbiAgICAgICAgdGhpcy5fcmVmcmVzaFN1bW1hcnkoKTtcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5LmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihTdW1tYXJ5LmdldFJlbmRlckFtbWVuZG1lbnRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lci5yZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbigpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3QuZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUoKSwgdGhpcy5fdGFza0xpc3RJbml0aWFsaXplZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIF9hZGRQYWdpbmF0aW9uRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFBhZ2VFdmVudE5hbWUoKSwgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gZXZlbnQuZGV0YWlsO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0UHJldmlvdXNQYWdlRXZlbnROYW1lKCksIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IE1hdGgubWF4KHRoaXMudGFza0xpc3QuY3VycmVudFBhZ2VJbmRleCAtIDEsIDApO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0TmV4dFBhZ2VFdmVudE5hbWUoKSwgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gTWF0aC5taW4odGhpcy50YXNrTGlzdC5jdXJyZW50UGFnZUluZGV4ICsgMSwgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24ucGFnZUNvdW50IC0gMSk7XG5cbiAgICAgICAgICAgIHRoaXMudGFza0xpc3Quc2V0Q3VycmVudFBhZ2VJbmRleChwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uc2VsZWN0UGFnZShwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdC5yZW5kZXIocGFnZUluZGV4KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC5kZXRhaWwucmVxdWVzdElkID09PSAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnVwZGF0ZScpIHtcbiAgICAgICAgICAgIGlmIChldmVudC5kZXRhaWwucmVxdWVzdC5yZXNwb25zZVVSTC5pbmRleE9mKHdpbmRvdy5sb2NhdGlvbi50b1N0cmluZygpKSAhPT0gMCkge1xuICAgICAgICAgICAgICAgIHRoaXMuc3VtbWFyeS5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudCgxMDApO1xuICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gZXZlbnQuZGV0YWlsLnJlcXVlc3QucmVzcG9uc2VVUkw7XG5cbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNvbnNvbGUubG9nKGV2ZW50LmRldGFpbC5yZXNwb25zZSk7XG5cbiAgICAgICAgICAgIHRoaXMuc3VtbWFyeURhdGEgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG5cbiAgICAgICAgICAgIGxldCBzdGF0ZSA9IHRoaXMuc3VtbWFyeURhdGEudGVzdC5zdGF0ZTtcbiAgICAgICAgICAgIGxldCB0YXNrQ291bnQgPSB0aGlzLnN1bW1hcnlEYXRhLnRlc3QudGFza19jb3VudDtcbiAgICAgICAgICAgIGxldCBpc1N0YXRlUXVldWVkT3JJblByb2dyZXNzID0gWydxdWV1ZWQnLCAnaW4tcHJvZ3Jlc3MnXS5pbmRleE9mKHN0YXRlKSAhPT0gLTE7XG5cbiAgICAgICAgICAgIHRoaXMuX3NldEJvZHlKb2JDbGFzcyh0aGlzLmRvY3VtZW50LmJvZHkuY2xhc3NMaXN0LCBzdGF0ZSk7XG4gICAgICAgICAgICB0aGlzLnN1bW1hcnkucmVuZGVyKHRoaXMuc3VtbWFyeURhdGEpO1xuXG4gICAgICAgICAgICBpZiAodGFza0NvdW50ID4gMCAmJiBpc1N0YXRlUXVldWVkT3JJblByb2dyZXNzICYmICF0aGlzLnRhc2tMaXN0SXNJbml0aWFsaXplZCAmJiAhdGhpcy50YXNrTGlzdC5pc0luaXRpYWxpemluZykge1xuICAgICAgICAgICAgICAgIHRoaXMudGFza0xpc3QuaW5pdCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgd2luZG93LnNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5fcmVmcmVzaFN1bW1hcnkoKTtcbiAgICAgICAgfSwgMzAwMCk7XG4gICAgfTtcblxuICAgIF90YXNrTGlzdEluaXRpYWxpemVkRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIHRoaXMudGFza0xpc3RJc0luaXRpYWxpemVkID0gdHJ1ZTtcbiAgICAgICAgdGhpcy50YXNrTGlzdC5yZW5kZXIoMCk7XG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uID0gbmV3IFRhc2tMaXN0UGFnaW5hdGlvbih0aGlzLnBhZ2VMZW5ndGgsIHRoaXMuc3VtbWFyeURhdGEudGVzdC50YXNrX2NvdW50KTtcblxuICAgICAgICBpZiAodGhpcy50YXNrTGlzdFBhZ2luYXRpb24uaXNSZXF1aXJlZCgpICYmICF0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5pc1JlbmRlcmVkKCkpIHtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmluaXQodGhpcy5fY3JlYXRlUGFnaW5hdGlvbkVsZW1lbnQoKSk7XG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldFBhZ2luYXRpb25FbGVtZW50KHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy5fYWRkUGFnaW5hdGlvbkV2ZW50TGlzdGVuZXJzKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge0VsZW1lbnR9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlUGFnaW5hdGlvbkVsZW1lbnQgKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmlubmVySFRNTCA9IHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmNyZWF0ZU1hcmt1cCgpO1xuXG4gICAgICAgIHJldHVybiBjb250YWluZXIucXVlcnlTZWxlY3RvcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0b3IoKSk7XG4gICAgfVxuXG4gICAgX3JlZnJlc2hTdW1tYXJ5ICgpIHtcbiAgICAgICAgbGV0IHN1bW1hcnlVcmwgPSB0aGlzLmRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXN1bW1hcnktdXJsJyk7XG4gICAgICAgIGxldCBub3cgPSBuZXcgRGF0ZSgpO1xuXG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbihzdW1tYXJ5VXJsICsgJz90aW1lc3RhbXA9JyArIG5vdy5nZXRUaW1lKCksIHRoaXMuZG9jdW1lbnQuYm9keSwgJ3Rlc3QtcHJvZ3Jlc3Muc3VtbWFyeS51cGRhdGUnKTtcbiAgICB9O1xuICAgIC8qKlxuICAgICAqXG4gICAgICogQHBhcmFtIHtET01Ub2tlbkxpc3R9IGJvZHlDbGFzc0xpc3RcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30gdGVzdFN0YXRlXG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfc2V0Qm9keUpvYkNsYXNzIChib2R5Q2xhc3NMaXN0LCB0ZXN0U3RhdGUpIHtcbiAgICAgICAgbGV0IGpvYkNsYXNzUHJlZml4ID0gJ2pvYi0nO1xuICAgICAgICBib2R5Q2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNsYXNzTmFtZS5pbmRleE9mKGpvYkNsYXNzUHJlZml4KSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIGNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgYm9keUNsYXNzTGlzdC5hZGQoJ2pvYi0nICsgdGVzdFN0YXRlKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RQcm9ncmVzcztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcHJvZ3Jlc3MuanMiLCJsZXQgQnlQYWdlTGlzdCA9IHJlcXVpcmUoJy4uL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvYnktcGFnZS1saXN0Jyk7XG5sZXQgQnlFcnJvckxpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QnKTtcblxuY2xhc3MgVGVzdFJlc3VsdHNCeVRhc2tUeXBlIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG5cbiAgICAgICAgbGV0IGJ5RXJyb3JDb250YWluZXJFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmJ5LWVycm9yLWNvbnRhaW5lcicpO1xuXG4gICAgICAgIHRoaXMuYnlQYWdlTGlzdCA9IGJ5RXJyb3JDb250YWluZXJFbGVtZW50ID8gbnVsbCA6IG5ldyBCeVBhZ2VMaXN0KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5ieS1wYWdlLWNvbnRhaW5lcicpKTtcbiAgICAgICAgdGhpcy5ieUVycm9yTGlzdCA9IGJ5RXJyb3JDb250YWluZXJFbGVtZW50ID8gbmV3IEJ5RXJyb3JMaXN0KGJ5RXJyb3JDb250YWluZXJFbGVtZW50KSA6IG51bGw7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGlmICh0aGlzLmJ5UGFnZUxpc3QpIHtcbiAgICAgICAgICAgIHRoaXMuYnlQYWdlTGlzdC5pbml0KCk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5ieUVycm9yTGlzdCkge1xuICAgICAgICAgICAgdGhpcy5ieUVycm9yTGlzdC5pbml0KCk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RSZXN1bHRzQnlUYXNrVHlwZTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUuanMiLCJsZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhcicpO1xubGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9odHRwLWNsaWVudCcpO1xuXG5jbGFzcyBUZXN0UmVzdWx0c1ByZXBhcmluZyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLnVucmV0cmlldmVkUmVtb3RlVGFza0lkc1VybCA9IGRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXVucmV0cmlldmVkLXJlbW90ZS10YXNrLWlkcy11cmwnKTtcbiAgICAgICAgdGhpcy5yZXN1bHRzUmV0cmlldmVVcmwgPSBkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS1yZXN1bHRzLXJldHJpZXZlLXVybCcpO1xuICAgICAgICB0aGlzLnJldHJpZXZhbFN0YXRzVXJsID0gZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcHJlcGFyaW5nLXN0YXRzLXVybCcpO1xuICAgICAgICB0aGlzLnJlc3VsdHNVcmwgPSBkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS1yZXN1bHRzLXVybCcpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyID0gbmV3IFByb2dyZXNzQmFyKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9ncmVzcy1iYXInKSk7XG4gICAgICAgIHRoaXMuY29tcGxldGlvblBlcmNlbnRWYWx1ZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jb21wbGV0aW9uLXBlcmNlbnQtdmFsdWUnKTtcbiAgICAgICAgdGhpcy5sb2NhbFRhc2tDb3VudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5sb2NhbC10YXNrLWNvdW50Jyk7XG4gICAgICAgIHRoaXMucmVtb3RlVGFza0NvdW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJlbW90ZS10YXNrLWNvdW50Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYm9keS5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5fY2hlY2tDb21wbGV0aW9uU3RhdHVzKCk7XG4gICAgfTtcblxuICAgIF9jaGVja0NvbXBsZXRpb25TdGF0dXMgKCkge1xuICAgICAgICBpZiAocGFyc2VJbnQoZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmVtYWluaW5nLXRhc2tzLXRvLXJldHJpZXZlLWNvdW50JyksIDEwKSA9PT0gMCkge1xuICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSB0aGlzLnJlc3VsdHNVcmw7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgcmVxdWVzdElkID0gZXZlbnQuZGV0YWlsLnJlcXVlc3RJZDtcbiAgICAgICAgbGV0IHJlc3BvbnNlID0gZXZlbnQuZGV0YWlsLnJlc3BvbnNlO1xuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uJykge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbihyZXNwb25zZSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbicpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlUmV0cmlldmFsU3RhdHMoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVJldHJpZXZhbFN0YXRzJykge1xuICAgICAgICAgICAgaWYgKCFyZXNwb25zZS5oYXNPd25Qcm9wZXJ0eSgnY29tcGxldGlvbl9wZXJjZW50JykpIHtcbiAgICAgICAgICAgICAgICByZXNwb25zZS5jb21wbGV0aW9uX3BlcmNlbnQgPSAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoIXJlc3BvbnNlLmhhc093blByb3BlcnR5KCdyZW1haW5pbmdfdGFza3NfdG9fcmV0cmlldmVfY291bnQnKSkge1xuICAgICAgICAgICAgICAgIHJlc3BvbnNlLnJlbWFpbmluZ190YXNrc190b19yZXRyaWV2ZV9jb3VudCA9IDA7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICghcmVzcG9uc2UuaGFzT3duUHJvcGVydHkoJ2xvY2FsX3Rhc2tfY291bnQnKSkge1xuICAgICAgICAgICAgICAgIHJlc3BvbnNlLmxvY2FsX3Rhc2tfY291bnQgPSAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoIXJlc3BvbnNlLmhhc093blByb3BlcnR5KCdyZW1vdGVfdGFza19jb3VudCcpKSB7XG4gICAgICAgICAgICAgICAgcmVzcG9uc2UucmVtb3RlX3Rhc2tfY291bnQgPSAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBsZXQgY29tcGxldGlvblBlcmNlbnQgPSByZXNwb25zZS5jb21wbGV0aW9uX3BlcmNlbnQ7XG5cbiAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQuYm9keS5zZXRBdHRyaWJ1dGUoJ2RhdGEtcmVtYWluaW5nLXRhc2tzLXRvLXJldHJpZXZlLWNvdW50JywgcmVzcG9uc2UucmVtYWluaW5nX3Rhc2tzX3RvX3JldHJpZXZlX2NvdW50LnRvU3RyaW5nKDEwKSk7XG4gICAgICAgICAgICB0aGlzLmNvbXBsZXRpb25QZXJjZW50VmFsdWUuaW5uZXJUZXh0ID0gY29tcGxldGlvblBlcmNlbnQ7XG4gICAgICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldENvbXBsZXRpb25QZXJjZW50KGNvbXBsZXRpb25QZXJjZW50KTtcbiAgICAgICAgICAgIHRoaXMubG9jYWxUYXNrQ291bnQuaW5uZXJUZXh0ID0gcmVzcG9uc2UubG9jYWxfdGFza19jb3VudDtcbiAgICAgICAgICAgIHRoaXMucmVtb3RlVGFza0NvdW50LmlubmVyVGV4dCA9IHJlc3BvbnNlLnJlbW90ZV90YXNrX2NvdW50O1xuXG4gICAgICAgICAgICB0aGlzLl9jaGVja0NvbXBsZXRpb25TdGF0dXMoKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfcmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbiAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnVucmV0cmlldmVkUmVtb3RlVGFza0lkc1VybCwgdGhpcy5kb2N1bWVudC5ib2R5LCAncmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbicpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbiAocmVtb3RlVGFza0lkcykge1xuICAgICAgICBIdHRwQ2xpZW50LnBvc3QodGhpcy5yZXN1bHRzUmV0cmlldmVVcmwsIHRoaXMuZG9jdW1lbnQuYm9keSwgJ3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24nLCAncmVtb3RlVGFza0lkcz0nICsgcmVtb3RlVGFza0lkcy5qb2luKCcsJykpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVSZXRyaWV2YWxTdGF0cyAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnJldHJpZXZhbFN0YXRzVXJsLCB0aGlzLmRvY3VtZW50LmJvZHksICdyZXRyaWV2ZVJldHJpZXZhbFN0YXRzJyk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RSZXN1bHRzUHJlcGFyaW5nO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLXByZXBhcmluZy5qcyIsImxldCB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlciA9IHJlcXVpcmUoJy4uL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlcicpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBDb29raWVPcHRpb25zRmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBUZXN0TG9ja1VubG9jayA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGVzdC1sb2NrLXVubG9jaycpO1xubGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5sZXQgQmFkZ2VDb2xsZWN0aW9uID0gcmVxdWlyZSgnLi4vbW9kZWwvYmFkZ2UtY29sbGVjdGlvbicpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IHJlcXVpcmUoJy4uL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbCcpO1xuXG5jbGFzcyBUZXN0UmVzdWx0cyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeS5jcmVhdGUoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmh0dHAtYXV0aGVudGljYXRpb24tdGVzdC1vcHRpb24nKSk7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9ucyA9IENvb2tpZU9wdGlvbnNGYWN0b3J5LmNyZWF0ZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuY29va2llcy10ZXN0LW9wdGlvbicpKTtcbiAgICAgICAgdGhpcy5yZXRlc3RGb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJldGVzdC1mb3JtJyk7XG4gICAgICAgIHRoaXMucmV0ZXN0QnV0dG9uID0gbmV3IEZvcm1CdXR0b24odGhpcy5yZXRlc3RGb3JtLnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG4gICAgICAgIHRoaXMudGFza1R5cGVTdW1tYXJ5QmFkZ2VDb2xsZWN0aW9uID0gbmV3IEJhZGdlQ29sbGVjdGlvbihkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzay10eXBlLXN1bW1hcnkgLmJhZGdlJykpO1xuXG4gICAgICAgIGxldCB0ZXN0TG9ja1VubG9ja0VsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuYnRuLWxvY2stdW5sb2NrJyk7XG4gICAgICAgIHRoaXMudGVzdExvY2tVbmxvY2sgPSB0ZXN0TG9ja1VubG9ja0VsZW1lbnQgPyBuZXcgVGVzdExvY2tVbmxvY2sodGVzdExvY2tVbmxvY2tFbGVtZW50KSA6IG51bGw7XG5cbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwgPSBuZXcgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsKFxuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLW1vZGFsJylcbiAgICAgICAgKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIodGhpcy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzay10eXBlLW9wdGlvbi5ub3QtYXZhaWxhYmxlJykpO1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuaW5pdCgpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMuaW5pdCgpO1xuICAgICAgICB0aGlzLnRhc2tUeXBlU3VtbWFyeUJhZGdlQ29sbGVjdGlvbi5hcHBseVVuaWZvcm1XaWR0aCgpO1xuXG4gICAgICAgIGlmICh0aGlzLnRlc3RMb2NrVW5sb2NrKSB7XG4gICAgICAgICAgICB0aGlzLnRlc3RMb2NrVW5sb2NrLmluaXQoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMucmV0ZXN0Rm9ybS5hZGRFdmVudExpc3RlbmVyKCdzdWJtaXQnLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnJldGVzdEJ1dHRvbi5kZUVtcGhhc2l6ZSgpO1xuICAgICAgICAgICAgdGhpcy5yZXRlc3RCdXR0b24ubWFya0FzQnVzeSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5pbml0KCk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0UmVzdWx0cztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy5qcyIsImxldCBGb3JtID0gcmVxdWlyZSgnLi4vdXNlci1hY2NvdW50LWNhcmQvZm9ybScpO1xubGV0IEZvcm1WYWxpZGF0b3IgPSByZXF1aXJlKCcuLi91c2VyLWFjY291bnQtY2FyZC9mb3JtLXZhbGlkYXRvcicpO1xubGV0IFN0cmlwZUhhbmRsZXIgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9zdHJpcGUtaGFuZGxlcicpO1xuXG5jbGFzcyBVc2VyQWNjb3VudENhcmQge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50KSB7XG4gICAgICAgIC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBuby11bmRlZlxuICAgICAgICBsZXQgc3RyaXBlSnMgPSBTdHJpcGU7XG4gICAgICAgIGxldCBmb3JtVmFsaWRhdG9yID0gbmV3IEZvcm1WYWxpZGF0b3Ioc3RyaXBlSnMpO1xuICAgICAgICB0aGlzLnN0cmlwZUhhbmRsZXIgPSBuZXcgU3RyaXBlSGFuZGxlcihzdHJpcGVKcyk7XG5cbiAgICAgICAgdGhpcy5mb3JtID0gbmV3IEZvcm0oZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BheW1lbnQtZm9ybScpLCBmb3JtVmFsaWRhdG9yKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZm9ybS5pbml0KCk7XG4gICAgICAgIHRoaXMuc3RyaXBlSGFuZGxlci5zZXRTdHJpcGVQdWJsaXNoYWJsZUtleSh0aGlzLmZvcm0uZ2V0U3RyaXBlUHVibGlzaGFibGVLZXkoKSk7XG5cbiAgICAgICAgbGV0IHVwZGF0ZUNhcmQgPSB0aGlzLmZvcm0uZ2V0VXBkYXRlQ2FyZEV2ZW50TmFtZSgpO1xuICAgICAgICBsZXQgY3JlYXRlQ2FyZFRva2VuU3VjY2VzcyA9IHRoaXMuc3RyaXBlSGFuZGxlci5nZXRDcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnROYW1lKCk7XG4gICAgICAgIGxldCBjcmVhdGVDYXJkVG9rZW5GYWlsdXJlID0gdGhpcy5zdHJpcGVIYW5kbGVyLmdldENyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudE5hbWUoKTtcblxuICAgICAgICB0aGlzLmZvcm0uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKHVwZGF0ZUNhcmQsIHRoaXMuX3VwZGF0ZUNhcmRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmZvcm0uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGNyZWF0ZUNhcmRUb2tlblN1Y2Nlc3MsIHRoaXMuX2NyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmZvcm0uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGNyZWF0ZUNhcmRUb2tlbkZhaWx1cmUsIHRoaXMuX2NyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBfdXBkYXRlQ2FyZEV2ZW50TGlzdGVuZXIgKHVwZGF0ZUNhcmRFdmVudCkge1xuICAgICAgICB0aGlzLnN0cmlwZUhhbmRsZXIuY3JlYXRlQ2FyZFRva2VuKHVwZGF0ZUNhcmRFdmVudC5kZXRhaWwsIHRoaXMuZm9ybS5lbGVtZW50KTtcbiAgICB9O1xuXG4gICAgX2NyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudExpc3RlbmVyIChzdHJpcGVDcmVhdGVDYXJkVG9rZW5FdmVudCkge1xuICAgICAgICBsZXQgcmVxdWVzdFVybCA9IHdpbmRvdy5sb2NhdGlvbi5wYXRobmFtZSArIHN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50LmRldGFpbC5pZCArICcvYXNzb2NpYXRlLyc7XG4gICAgICAgIGxldCByZXF1ZXN0ID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG5cbiAgICAgICAgcmVxdWVzdC5vcGVuKCdQT1NUJywgcmVxdWVzdFVybCk7XG4gICAgICAgIHJlcXVlc3QucmVzcG9uc2VUeXBlID0gJ2pzb24nO1xuICAgICAgICByZXF1ZXN0LnNldFJlcXVlc3RIZWFkZXIoJ0FjY2VwdCcsICdhcHBsaWNhdGlvbi9qc29uJyk7XG5cbiAgICAgICAgcmVxdWVzdC5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgZGF0YSA9IHJlcXVlc3QucmVzcG9uc2U7XG5cbiAgICAgICAgICAgIGlmIChkYXRhLmhhc093blByb3BlcnR5KCd0aGlzX3VybCcpKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mb3JtLnN1Ym1pdEJ1dHRvbi5tYXJrQXNBdmFpbGFibGUoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmZvcm0uc3VibWl0QnV0dG9uLm1hcmtTdWNjZWVkZWQoKTtcblxuICAgICAgICAgICAgICAgIHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uID0gZGF0YS50aGlzX3VybDtcbiAgICAgICAgICAgICAgICB9LCA1MDApO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZvcm0uZW5hYmxlKCk7XG5cbiAgICAgICAgICAgICAgICBpZiAoZGF0YS5oYXNPd25Qcm9wZXJ0eSgndXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtJykgJiYgZGF0YVsndXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtJ10gIT09ICcnKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5oYW5kbGVSZXNwb25zZUVycm9yKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdwYXJhbSc6IGRhdGEudXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ21lc3NhZ2UnOiBkYXRhLnVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9tZXNzYWdlXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5oYW5kbGVSZXNwb25zZUVycm9yKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdwYXJhbSc6ICdudW1iZXInLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ21lc3NhZ2UnOiBkYXRhLnVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9tZXNzYWdlXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmVxdWVzdC5zZW5kKCk7XG4gICAgfTtcblxuICAgIF9jcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnRMaXN0ZW5lciAoc3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQpIHtcbiAgICAgICAgbGV0IHJlc3BvbnNlRXJyb3IgPSB0aGlzLmZvcm0uY3JlYXRlUmVzcG9uc2VFcnJvcihzdHJpcGVDcmVhdGVDYXJkVG9rZW5FdmVudC5kZXRhaWwuZXJyb3IucGFyYW0sICdpbnZhbGlkJyk7XG5cbiAgICAgICAgdGhpcy5mb3JtLmVuYWJsZSgpO1xuICAgICAgICB0aGlzLmZvcm0uaGFuZGxlUmVzcG9uc2VFcnJvcihyZXNwb25zZUVycm9yKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFVzZXJBY2NvdW50Q2FyZDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC1jYXJkLmpzIiwibGV0IFNjcm9sbFNweSA9IHJlcXVpcmUoJy4uL3VzZXItYWNjb3VudC9zY3JvbGxzcHknKTtcbmxldCBOYXZCYXJMaXN0ID0gcmVxdWlyZSgnLi4vdXNlci1hY2NvdW50L25hdmJhci1saXN0Jyk7XG5jb25zdCBTY3JvbGxUbyA9IHJlcXVpcmUoJy4uL3Njcm9sbC10bycpO1xuY29uc3QgU3RpY2t5RmlsbCA9IHJlcXVpcmUoJ3N0aWNreWZpbGxqcycpO1xuY29uc3QgUG9zaXRpb25TdGlja3lEZXRlY3RvciA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL3Bvc2l0aW9uLXN0aWNreS1kZXRlY3RvcicpO1xuXG5jbGFzcyBVc2VyQWNjb3VudCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtXaW5kb3d9IHdpbmRvd1xuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKHdpbmRvdywgZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy53aW5kb3cgPSB3aW5kb3c7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zY3JvbGxPZmZzZXQgPSA2MDtcbiAgICAgICAgY29uc3Qgc2Nyb2xsU3B5T2Zmc2V0ID0gMTAwO1xuICAgICAgICB0aGlzLnNpZGVOYXZFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NpZGVuYXYnKTtcblxuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuZXcgTmF2QmFyTGlzdCh0aGlzLnNpZGVOYXZFbGVtZW50LCB0aGlzLnNjcm9sbE9mZnNldCk7XG4gICAgICAgIHRoaXMuc2Nyb2xsc3B5ID0gbmV3IFNjcm9sbFNweSh0aGlzLm5hdkJhckxpc3QsIHNjcm9sbFNweU9mZnNldCk7XG4gICAgfTtcblxuICAgIF9hcHBseUluaXRpYWxTY3JvbGwgKCkge1xuICAgICAgICBsZXQgdGFyZ2V0SWQgPSB0aGlzLndpbmRvdy5sb2NhdGlvbi5oYXNoLnRyaW0oKS5yZXBsYWNlKCcjJywgJycpO1xuXG4gICAgICAgIGlmICh0YXJnZXRJZCkge1xuICAgICAgICAgICAgbGV0IHRhcmdldCA9IHRoaXMuZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGFyZ2V0SWQpO1xuICAgICAgICAgICAgbGV0IHJlbGF0ZWRBbmNob3IgPSB0aGlzLnNpZGVOYXZFbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbaHJlZj1cXFxcIycgKyB0YXJnZXRJZCArICddJyk7XG5cbiAgICAgICAgICAgIGlmICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBpZiAocmVsYXRlZEFuY2hvci5jbGFzc0xpc3QuY29udGFpbnMoJ2pzLWZpcnN0JykpIHtcbiAgICAgICAgICAgICAgICAgICAgU2Nyb2xsVG8uZ29Ubyh0YXJnZXQsIDApO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIFNjcm9sbFRvLnNjcm9sbFRvKHRhcmdldCwgdGhpcy5zY3JvbGxPZmZzZXQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfYXBwbHlQb3NpdGlvblN0aWNreVBvbHlmaWxsICgpIHtcbiAgICAgICAgY29uc3Qgc3RpY2t5TmF2SnNDbGFzcyA9ICdqcy1zdGlja3ktbmF2JztcbiAgICAgICAgY29uc3Qgc3RpY2t5TmF2SnNTZWxlY3RvciA9ICcuJyArIHN0aWNreU5hdkpzQ2xhc3M7XG5cbiAgICAgICAgbGV0IHN0aWNreU5hdiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc3RpY2t5TmF2SnNTZWxlY3Rvcik7XG5cbiAgICAgICAgbGV0IHN1cHBvcnRzUG9zaXRpb25TdGlja3kgPSBQb3NpdGlvblN0aWNreURldGVjdG9yLnN1cHBvcnRzUG9zaXRpb25TdGlja3koKTtcbiAgICAgICAgaWYgKHN1cHBvcnRzUG9zaXRpb25TdGlja3kpIHtcbiAgICAgICAgICAgIHN0aWNreU5hdi5jbGFzc0xpc3QucmVtb3ZlKHN0aWNreU5hdkpzQ2xhc3MpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgU3RpY2t5RmlsbC5hZGRPbmUoc3RpY2t5TmF2KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5zaWRlTmF2RWxlbWVudC5xdWVyeVNlbGVjdG9yKCdhJykuY2xhc3NMaXN0LmFkZCgnanMtZmlyc3QnKTtcbiAgICAgICAgdGhpcy5zY3JvbGxzcHkuc3B5KCk7XG4gICAgICAgIHRoaXMuX2FwcGx5UG9zaXRpb25TdGlja3lQb2x5ZmlsbCgpO1xuICAgICAgICB0aGlzLl9hcHBseUluaXRpYWxTY3JvbGwoKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFVzZXJBY2NvdW50O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdXNlci1hY2NvdW50LmpzIiwiLy8gUG9seWZpbGwgZm9yIGJyb3dzZXJzIG5vdCBzdXBwb3J0aW5nIG5ldyBDdXN0b21FdmVudCgpXG4vLyBMaWdodGx5IG1vZGlmaWVkIGZyb20gcG9seWZpbGwgcHJvdmlkZWQgYXQgaHR0cHM6Ly9kZXZlbG9wZXIubW96aWxsYS5vcmcvZW4tVVMvZG9jcy9XZWIvQVBJL0N1c3RvbUV2ZW50L0N1c3RvbUV2ZW50I1BvbHlmaWxsXG4oZnVuY3Rpb24gKCkge1xuICAgIGlmICh0eXBlb2Ygd2luZG93LkN1c3RvbUV2ZW50ID09PSAnZnVuY3Rpb24nKSByZXR1cm4gZmFsc2U7XG5cbiAgICBmdW5jdGlvbiBDdXN0b21FdmVudCAoZXZlbnQsIHBhcmFtcykge1xuICAgICAgICBwYXJhbXMgPSBwYXJhbXMgfHwgeyBidWJibGVzOiBmYWxzZSwgY2FuY2VsYWJsZTogZmFsc2UsIGRldGFpbDogdW5kZWZpbmVkIH07XG4gICAgICAgIGxldCBjdXN0b21FdmVudCA9IGRvY3VtZW50LmNyZWF0ZUV2ZW50KCdDdXN0b21FdmVudCcpO1xuICAgICAgICBjdXN0b21FdmVudC5pbml0Q3VzdG9tRXZlbnQoZXZlbnQsIHBhcmFtcy5idWJibGVzLCBwYXJhbXMuY2FuY2VsYWJsZSwgcGFyYW1zLmRldGFpbCk7XG5cbiAgICAgICAgcmV0dXJuIGN1c3RvbUV2ZW50O1xuICAgIH1cblxuICAgIEN1c3RvbUV2ZW50LnByb3RvdHlwZSA9IHdpbmRvdy5FdmVudC5wcm90b3R5cGU7XG5cbiAgICB3aW5kb3cuQ3VzdG9tRXZlbnQgPSBDdXN0b21FdmVudDtcbn0pKCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcG9seWZpbGwvY3VzdG9tLWV2ZW50LmpzIiwiLy8gUG9seWZpbGwgZm9yIGJyb3dzZXJzIG5vdCBzdXBwb3J0aW5nIE9iamVjdC5lbnRyaWVzKClcbi8vIExpZ2h0bHkgbW9kaWZpZWQgZnJvbSBwb2x5ZmlsbCBwcm92aWRlZCBhdCBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9KYXZhU2NyaXB0L1JlZmVyZW5jZS9HbG9iYWxfT2JqZWN0cy9PYmplY3QvZW50cmllcyNQb2x5ZmlsbFxuaWYgKCFPYmplY3QuZW50cmllcykge1xuICAgIE9iamVjdC5lbnRyaWVzID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgICBsZXQgb3duUHJvcHMgPSBPYmplY3Qua2V5cyhvYmopO1xuICAgICAgICBsZXQgaSA9IG93blByb3BzLmxlbmd0aDtcbiAgICAgICAgbGV0IHJlc0FycmF5ID0gbmV3IEFycmF5KGkpO1xuXG4gICAgICAgIHdoaWxlIChpLS0pIHtcbiAgICAgICAgICAgIHJlc0FycmF5W2ldID0gW293blByb3BzW2ldLCBvYmpbb3duUHJvcHNbaV1dXTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiByZXNBcnJheTtcbiAgICB9O1xufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BvbHlmaWxsL29iamVjdC1lbnRyaWVzLmpzIiwiY29uc3QgU21vb3RoU2Nyb2xsID0gcmVxdWlyZSgnc21vb3RoLXNjcm9sbCcpO1xuXG5jbGFzcyBTY3JvbGxUbyB7XG4gICAgc3RhdGljIHNjcm9sbFRvICh0YXJnZXQsIG9mZnNldCkge1xuICAgICAgICBjb25zdCBzY3JvbGwgPSBuZXcgU21vb3RoU2Nyb2xsKCk7XG5cbiAgICAgICAgc2Nyb2xsLmFuaW1hdGVTY3JvbGwodGFyZ2V0Lm9mZnNldFRvcCArIG9mZnNldCk7XG4gICAgICAgIFNjcm9sbFRvLl91cGRhdGVIaXN0b3J5KHRhcmdldCk7XG4gICAgfVxuXG4gICAgc3RhdGljIGdvVG8gKHRhcmdldCwgb2Zmc2V0KSB7XG4gICAgICAgIGNvbnN0IHNjcm9sbCA9IG5ldyBTbW9vdGhTY3JvbGwoKTtcblxuICAgICAgICBzY3JvbGwuYW5pbWF0ZVNjcm9sbChvZmZzZXQpO1xuICAgICAgICBTY3JvbGxUby5fdXBkYXRlSGlzdG9yeSh0YXJnZXQpO1xuICAgIH1cblxuICAgIHN0YXRpYyBfdXBkYXRlSGlzdG9yeSAodGFyZ2V0KSB7XG4gICAgICAgIGlmICh3aW5kb3cuaGlzdG9yeS5wdXNoU3RhdGUpIHtcbiAgICAgICAgICAgIHdpbmRvdy5oaXN0b3J5LnB1c2hTdGF0ZShudWxsLCBudWxsLCAnIycgKyB0YXJnZXQuZ2V0QXR0cmlidXRlKCdpZCcpKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU2Nyb2xsVG87XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2Nyb2xsLXRvLmpzIiwibGV0IEFsZXJ0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9hbGVydCcpO1xuXG5jbGFzcyBBbGVydEZhY3Rvcnkge1xuICAgIHN0YXRpYyBjcmVhdGVGcm9tQ29udGVudCAoZG9jdW1lbnQsIGVycm9yQ29udGVudCwgcmVsYXRlZEZpZWxkSWQpIHtcbiAgICAgICAgbGV0IGVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdhbGVydCcsICdhbGVydC1kYW5nZXInLCAnZmFkZScsICdpbicpO1xuICAgICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZSgncm9sZScsICdhbGVydCcpO1xuXG4gICAgICAgIGxldCBlbGVtZW50SW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgaWYgKHJlbGF0ZWRGaWVsZElkKSB7XG4gICAgICAgICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZSgnZGF0YS1mb3InLCByZWxhdGVkRmllbGRJZCk7XG4gICAgICAgICAgICBlbGVtZW50SW5uZXJIVE1MICs9ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImNsb3NlXCIgZGF0YS1kaXNtaXNzPVwiYWxlcnRcIiBhcmlhLWxhYmVsPVwiQ2xvc2VcIj48c3BhbiBhcmlhLWhpZGRlbj1cInRydWVcIj7Dlzwvc3Bhbj48L2J1dHRvbj4nO1xuICAgICAgICB9XG5cbiAgICAgICAgZWxlbWVudElubmVySFRNTCArPSBlcnJvckNvbnRlbnQ7XG4gICAgICAgIGVsZW1lbnQuaW5uZXJIVE1MID0gZWxlbWVudElubmVySFRNTDtcblxuICAgICAgICByZXR1cm4gbmV3IEFsZXJ0KGVsZW1lbnQpO1xuICAgIH07XG5cbiAgICBzdGF0aWMgY3JlYXRlRnJvbUVsZW1lbnQgKGFsZXJ0RWxlbWVudCkge1xuICAgICAgICByZXR1cm4gbmV3IEFsZXJ0KGFsZXJ0RWxlbWVudCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEFsZXJ0RmFjdG9yeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5LmpzIiwibGV0IENvb2tpZU9wdGlvbnNNb2RhbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvY29va2llLW9wdGlvbnMtbW9kYWwnKTtcbmxldCBDb29raWVPcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvY29va2llLW9wdGlvbnMnKTtcbmxldCBBY3Rpb25CYWRnZSA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvYWN0aW9uLWJhZGdlJyk7XG5cbmNsYXNzIENvb2tpZU9wdGlvbnNGYWN0b3J5IHtcbiAgICBzdGF0aWMgY3JlYXRlIChjb250YWluZXIpIHtcbiAgICAgICAgcmV0dXJuIG5ldyBDb29raWVPcHRpb25zKFxuICAgICAgICAgICAgY29udGFpbmVyLm93bmVyRG9jdW1lbnQsXG4gICAgICAgICAgICBuZXcgQ29va2llT3B0aW9uc01vZGFsKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubW9kYWwnKSksXG4gICAgICAgICAgICBuZXcgQWN0aW9uQmFkZ2UoY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5tb2RhbC1sYXVuY2hlcicpKSxcbiAgICAgICAgICAgIGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcuc3RhdHVzJylcbiAgICAgICAgKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IENvb2tpZU9wdGlvbnNGYWN0b3J5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnkuanMiLCJsZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwnKTtcbmxldCBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zJyk7XG5sZXQgQWN0aW9uQmFkZ2UgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZScpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeSB7XG4gICAgc3RhdGljIGNyZWF0ZSAoY29udGFpbmVyKSB7XG4gICAgICAgIHJldHVybiBuZXcgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyhcbiAgICAgICAgICAgIGNvbnRhaW5lci5vd25lckRvY3VtZW50LFxuICAgICAgICAgICAgbmV3IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbChjb250YWluZXIucXVlcnlTZWxlY3RvcignLm1vZGFsJykpLFxuICAgICAgICAgICAgbmV3IEFjdGlvbkJhZGdlKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubW9kYWwtbGF1bmNoZXInKSksXG4gICAgICAgICAgICBjb250YWluZXIucXVlcnlTZWxlY3RvcignLnN0YXR1cycpXG4gICAgICAgICk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeS5qcyIsImNsYXNzIEh0dHBDbGllbnQge1xuICAgIHN0YXRpYyBnZXRSZXRyaWV2ZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2h0dHAtY2xpZW50LnJldHJpZXZlZCc7XG4gICAgfTtcblxuICAgIHN0YXRpYyByZXF1ZXN0ICh1cmwsIG1ldGhvZCwgcmVzcG9uc2VUeXBlLCBlbGVtZW50LCByZXF1ZXN0SWQsIGRhdGEgPSBudWxsLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIGxldCByZXF1ZXN0ID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG5cbiAgICAgICAgcmVxdWVzdC5vcGVuKG1ldGhvZCwgdXJsKTtcbiAgICAgICAgcmVxdWVzdC5yZXNwb25zZVR5cGUgPSByZXNwb25zZVR5cGU7XG5cbiAgICAgICAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMocmVxdWVzdEhlYWRlcnMpKSB7XG4gICAgICAgICAgICByZXF1ZXN0LnNldFJlcXVlc3RIZWFkZXIoa2V5LCB2YWx1ZSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXF1ZXN0LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgIGxldCByZXRyaWV2ZWRFdmVudCA9IG5ldyBDdXN0b21FdmVudChIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiB7XG4gICAgICAgICAgICAgICAgICAgIHJlc3BvbnNlOiByZXF1ZXN0LnJlc3BvbnNlLFxuICAgICAgICAgICAgICAgICAgICByZXF1ZXN0SWQ6IHJlcXVlc3RJZCxcbiAgICAgICAgICAgICAgICAgICAgcmVxdWVzdDogcmVxdWVzdFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBlbGVtZW50LmRpc3BhdGNoRXZlbnQocmV0cmlldmVkRXZlbnQpO1xuICAgICAgICB9KTtcblxuICAgICAgICBpZiAoZGF0YSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmVxdWVzdC5zZW5kKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICByZXF1ZXN0LnNlbmQoZGF0YSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgc3RhdGljIGdldCAodXJsLCByZXNwb25zZVR5cGUsIGVsZW1lbnQsIHJlcXVlc3RJZCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBIdHRwQ2xpZW50LnJlcXVlc3QodXJsLCAnR0VUJywgcmVzcG9uc2VUeXBlLCBlbGVtZW50LCByZXF1ZXN0SWQsIG51bGwsIHJlcXVlc3RIZWFkZXJzKTtcbiAgICB9O1xuXG4gICAgc3RhdGljIGdldEpzb24gKHVybCwgZWxlbWVudCwgcmVxdWVzdElkLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIGxldCByZWFsUmVxdWVzdEhlYWRlcnMgPSB7XG4gICAgICAgICAgICAnQWNjZXB0JzogJ2FwcGxpY2F0aW9uL2pzb24nXG4gICAgICAgIH07XG5cbiAgICAgICAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMocmVxdWVzdEhlYWRlcnMpKSB7XG4gICAgICAgICAgICByZWFsUmVxdWVzdEhlYWRlcnNba2V5XSA9IHZhbHVlO1xuICAgICAgICB9XG5cbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ0dFVCcsICdqc29uJywgZWxlbWVudCwgcmVxdWVzdElkLCBudWxsLCByZWFsUmVxdWVzdEhlYWRlcnMpO1xuICAgIH07XG5cbiAgICBzdGF0aWMgZ2V0VGV4dCAodXJsLCBlbGVtZW50LCByZXF1ZXN0SWQsIHJlcXVlc3RIZWFkZXJzID0ge30pIHtcbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ0dFVCcsICcnLCBlbGVtZW50LCByZXF1ZXN0SWQsIHJlcXVlc3RIZWFkZXJzKTtcbiAgICB9O1xuXG4gICAgc3RhdGljIHBvc3QgKHVybCwgZWxlbWVudCwgcmVxdWVzdElkLCBkYXRhID0gbnVsbCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBsZXQgcmVhbFJlcXVlc3RIZWFkZXJzID0ge1xuICAgICAgICAgICAgJ0NvbnRlbnQtdHlwZSc6ICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnXG4gICAgICAgIH07XG5cbiAgICAgICAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMocmVxdWVzdEhlYWRlcnMpKSB7XG4gICAgICAgICAgICByZWFsUmVxdWVzdEhlYWRlcnNba2V5XSA9IHZhbHVlO1xuICAgICAgICB9XG5cbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ1BPU1QnLCAnJywgZWxlbWVudCwgcmVxdWVzdElkLCBkYXRhLCByZWFsUmVxdWVzdEhlYWRlcnMpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gSHR0cENsaWVudDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWNsaWVudC5qcyIsImxldCBMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9saXN0ZWQtdGVzdCcpO1xubGV0IFByZXBhcmluZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L3ByZXBhcmluZy1saXN0ZWQtdGVzdCcpO1xubGV0IFByb2dyZXNzaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QnKTtcbmxldCBDcmF3bGluZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2NyYXdsaW5nLWxpc3RlZC10ZXN0Jyk7XG5cbmNsYXNzIExpc3RlZFRlc3RGYWN0b3J5IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtMaXN0ZWRUZXN0fVxuICAgICAqL1xuICAgIHN0YXRpYyBjcmVhdGVGcm9tRWxlbWVudCAoZWxlbWVudCkge1xuICAgICAgICBpZiAoZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ3JlcXVpcmVzLXJlc3VsdHMnKSkge1xuICAgICAgICAgICAgcmV0dXJuIG5ldyBQcmVwYXJpbmdMaXN0ZWRUZXN0KGVsZW1lbnQpO1xuICAgICAgICB9XG5cbiAgICAgICAgbGV0IHN0YXRlID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcblxuICAgICAgICBpZiAoc3RhdGUgPT09ICdpbi1wcm9ncmVzcycpIHtcbiAgICAgICAgICAgIHJldHVybiBuZXcgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0KGVsZW1lbnQpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnY3Jhd2xpbmcnKSB7XG4gICAgICAgICAgICByZXR1cm4gbmV3IENyYXdsaW5nTGlzdGVkVGVzdChlbGVtZW50KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBuZXcgTGlzdGVkVGVzdChlbGVtZW50KTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTGlzdGVkVGVzdEZhY3Rvcnk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvbGlzdGVkLXRlc3QtZmFjdG9yeS5qcyIsImNsYXNzIFBvc2l0aW9uU3RpY2t5RGV0ZWN0b3Ige1xuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIHN0YXRpYyBzdXBwb3J0c1Bvc2l0aW9uU3RpY2t5ICgpIHtcbiAgICAgICAgbGV0IGVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdhJyk7XG4gICAgICAgIGxldCBlbGVtZW50U3R5bGUgPSBlbGVtZW50LnN0eWxlO1xuXG4gICAgICAgIGVsZW1lbnRTdHlsZS5jc3NUZXh0ID0gJ3Bvc2l0aW9uOnN0aWNreTsnO1xuXG4gICAgICAgIHJldHVybiBlbGVtZW50U3R5bGUucG9zaXRpb24uaW5kZXhPZignc3RpY2t5JykgIT09IC0xO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gUG9zaXRpb25TdGlja3lEZXRlY3RvcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9wb3NpdGlvbi1zdGlja3ktZGV0ZWN0b3IuanMiLCJjbGFzcyBTdHJpcGVIYW5kbGVyIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1N0cmlwZX0gc3RyaXBlSnNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoc3RyaXBlSnMpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcyA9IHN0cmlwZUpzO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBzdHJpcGVQdWJsaXNoYWJsZUtleVxuICAgICAqL1xuICAgIHNldFN0cmlwZVB1Ymxpc2hhYmxlS2V5IChzdHJpcGVQdWJsaXNoYWJsZUtleSkge1xuICAgICAgICB0aGlzLnN0cmlwZUpzLnNldFB1Ymxpc2hhYmxlS2V5KHN0cmlwZVB1Ymxpc2hhYmxlS2V5KTtcbiAgICB9XG5cbiAgICBnZXRDcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdzdHJpcGUtaGFuZGVyLmNyZWF0ZS1jYXJkLXRva2VuLnN1Y2Nlc3MnO1xuICAgIH07XG5cbiAgICBnZXRDcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdzdHJpcGUtaGFuZGVyLmNyZWF0ZS1jYXJkLXRva2VuLmZhaWx1cmUnO1xuICAgIH07XG5cbiAgICBjcmVhdGVDYXJkVG9rZW4gKGRhdGEsIGZvcm1FbGVtZW50KSB7XG4gICAgICAgIHRoaXMuc3RyaXBlSnMuY2FyZC5jcmVhdGVUb2tlbihkYXRhLCAoc3RhdHVzLCByZXNwb25zZSkgPT4ge1xuICAgICAgICAgICAgbGV0IGlzRXJyb3JSZXNwb25zZSA9IHJlc3BvbnNlLmhhc093blByb3BlcnR5KCdlcnJvcicpO1xuXG4gICAgICAgICAgICBsZXQgZXZlbnROYW1lID0gaXNFcnJvclJlc3BvbnNlXG4gICAgICAgICAgICAgICAgPyB0aGlzLmdldENyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudE5hbWUoKVxuICAgICAgICAgICAgICAgIDogdGhpcy5nZXRDcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnROYW1lKCk7XG5cbiAgICAgICAgICAgIGZvcm1FbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KGV2ZW50TmFtZSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDogcmVzcG9uc2VcbiAgICAgICAgICAgIH0pKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTdHJpcGVIYW5kbGVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL3N0cmlwZS1oYW5kbGVyLmpzIiwibGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuL2h0dHAtY2xpZW50Jyk7XG5sZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhcicpO1xuXG5jbGFzcyBUZXN0UmVzdWx0UmV0cmlldmVyIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnN0YXR1c1VybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXR1cy11cmwnKTtcbiAgICAgICAgdGhpcy51bnJldHJpZXZlZFRhc2tJZHNVcmwgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11bnJldHJpZXZlZC10YXNrLWlkcy11cmwnKTtcbiAgICAgICAgdGhpcy5yZXRyaWV2ZVRhc2tzVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmV0cmlldmUtdGFza3MtdXJsJyk7XG4gICAgICAgIHRoaXMuc3VtbWFyeVVybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN1bW1hcnktdXJsJyk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBuZXcgUHJvZ3Jlc3NCYXIodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9ncmVzcy1iYXInKSk7XG4gICAgICAgIHRoaXMuc3VtbWFyeSA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnN1bW1hcnknKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuXG4gICAgICAgIHRoaXMuX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzKCk7XG4gICAgfTtcblxuICAgIGdldFJldHJpZXZlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGVzdC1yZXN1bHQtcmV0cmlldmVyLnJldHJpZXZlZCc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7Q3VzdG9tRXZlbnR9IGV2ZW50XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgcmVzcG9uc2UgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG4gICAgICAgIGxldCByZXF1ZXN0SWQgPSBldmVudC5kZXRhaWwucmVxdWVzdElkO1xuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVByZXBhcmluZ1N0YXR1cycpIHtcbiAgICAgICAgICAgIGxldCBjb21wbGV0aW9uUGVyY2VudCA9IHJlc3BvbnNlLmNvbXBsZXRpb25fcGVyY2VudDtcblxuICAgICAgICAgICAgdGhpcy5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudChjb21wbGV0aW9uUGVyY2VudCk7XG5cbiAgICAgICAgICAgIGlmIChjb21wbGV0aW9uUGVyY2VudCA+PSAxMDApIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZUZpbmlzaGVkU3VtbWFyeSgpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9kaXNwbGF5UHJlcGFyaW5nU3VtbWFyeShyZXNwb25zZSk7XG4gICAgICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbigpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24nKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrQ29sbGVjdGlvbihyZXNwb25zZSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24nKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVByZXBhcmluZ1N0YXR1cygpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5Jykge1xuICAgICAgICAgICAgbGV0IHJldHJpZXZlZFN1bW1hcnlDb250YWluZXIgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgcmV0cmlldmVkU3VtbWFyeUNvbnRhaW5lci5pbm5lckhUTUwgPSByZXNwb25zZTtcblxuICAgICAgICAgICAgbGV0IHJldHJpZXZlZEV2ZW50ID0gbmV3IEN1c3RvbUV2ZW50KHRoaXMuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHJldHJpZXZlZFN1bW1hcnlDb250YWluZXIucXVlcnlTZWxlY3RvcignLmxpc3RlZC10ZXN0JylcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChyZXRyaWV2ZWRFdmVudCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHRoaXMuc3RhdHVzVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZVByZXBhcmluZ1N0YXR1cycpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbiAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnVucmV0cmlldmVkVGFza0lkc1VybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbicpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24gKHJlbW90ZVRhc2tJZHMpIHtcbiAgICAgICAgSHR0cENsaWVudC5wb3N0KHRoaXMucmV0cmlldmVUYXNrc1VybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24nLCAncmVtb3RlVGFza0lkcz0nICsgcmVtb3RlVGFza0lkcy5qb2luKCcsJykpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVGaW5pc2hlZFN1bW1hcnkgKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldFRleHQodGhpcy5zdW1tYXJ5VXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZUZpbmlzaGVkU3VtbWFyeScpO1xuICAgIH07XG5cbiAgICBfY3JlYXRlUHJlcGFyaW5nU3VtbWFyeSAoc3RhdHVzRGF0YSkge1xuICAgICAgICBsZXQgbG9jYWxUYXNrQ291bnQgPSBzdGF0dXNEYXRhLmxvY2FsX3Rhc2tfY291bnQ7XG4gICAgICAgIGxldCByZW1vdGVUYXNrQ291bnQgPSBzdGF0dXNEYXRhLnJlbW90ZV90YXNrX2NvdW50O1xuXG4gICAgICAgIGlmIChsb2NhbFRhc2tDb3VudCA9PT0gdW5kZWZpbmVkICYmIHJlbW90ZVRhc2tDb3VudCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICByZXR1cm4gJ1ByZXBhcmluZyByZXN1bHRzICZoZWxsaXA7JztcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiAnUHJlcGFyaW5nICZoZWxsaXA7IGNvbGxlY3RlZCA8c3Ryb25nIGNsYXNzPVwibG9jYWwtdGFzay1jb3VudFwiPicgKyBsb2NhbFRhc2tDb3VudCArICc8L3N0cm9uZz4gcmVzdWx0cyBvZiA8c3Ryb25nIGNsYXNzPVwicmVtb3RlLXRhc2stY291bnRcIj4nICsgcmVtb3RlVGFza0NvdW50ICsgJzwvc3Ryb25nPic7XG4gICAgfTtcblxuICAgIF9kaXNwbGF5UHJlcGFyaW5nU3VtbWFyeSAoc3RhdHVzRGF0YSkge1xuICAgICAgICB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByZXBhcmluZyAuc3VtbWFyeScpLmlubmVySFRNTCA9IHRoaXMuX2NyZWF0ZVByZXBhcmluZ1N1bW1hcnkoc3RhdHVzRGF0YSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0UmVzdWx0UmV0cmlldmVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL3Rlc3QtcmVzdWx0LXJldHJpZXZlci5qcyIsImxldCBJc3N1ZUxpc3QgPSByZXF1aXJlKCcuL2lzc3VlLWxpc3QnKTtcbmxldCBTcGFya01ENSA9IHJlcXVpcmUoJ3NwYXJrLW1kNScpO1xuXG5jbGFzcyBGaWx0ZXJhYmxlSXNzdWVMaXN0IGV4dGVuZHMgSXNzdWVMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30gZmlsdGVyU2VsZWN0b3JcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCwgZmlsdGVyU2VsZWN0b3IpIHtcbiAgICAgICAgc3VwZXIoZWxlbWVudCk7XG4gICAgICAgIHRoaXMuZmlsdGVyU2VsZWN0b3IgPSBmaWx0ZXJTZWxlY3RvcjtcbiAgICAgICAgdGhpcy5oYXNoSWRQcmVmaXggPSAnaGFzaC0nO1xuICAgIH1cblxuICAgIGFkZEhhc2hJZEF0dHJpYnV0ZVRvSXNzdWVzICgpIHtcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuaXNzdWVzLCAoaXNzdWVFbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgZXJyb3JNZXNzYWdlID0gaXNzdWVFbGVtZW50LnF1ZXJ5U2VsZWN0b3IodGhpcy5maWx0ZXJTZWxlY3RvcikudGV4dENvbnRlbnQudHJpbSgpO1xuICAgICAgICAgICAgbGV0IGVycm9yTWVzc2FnZUhhc2ggPSBTcGFya01ENS5oYXNoKGVycm9yTWVzc2FnZSk7XG4gICAgICAgICAgICBsZXQgaWQgPSB0aGlzLl9nZW5lcmF0ZVVuaXF1ZUlkKHRoaXMuaGFzaElkUHJlZml4ICsgZXJyb3JNZXNzYWdlSGFzaCk7XG5cbiAgICAgICAgICAgIGlzc3VlRWxlbWVudC5zZXRBdHRyaWJ1dGUoJ2lkJywgaWQpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IGZpbHRlclxuICAgICAqL1xuICAgIGZpbHRlciAoZmlsdGVyKSB7XG4gICAgICAgIGZpbHRlciA9IHRoaXMuaGFzaElkUHJlZml4ICsgZmlsdGVyO1xuXG4gICAgICAgIGlmICghdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoZmlsdGVyKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuaXNzdWU6bm90KFtpZF49JyArIGZpbHRlciArICddKScpLCAoaXNzdWVFbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBpc3N1ZUVsZW1lbnQucGFyZW50RWxlbWVudC5yZW1vdmVDaGlsZChpc3N1ZUVsZW1lbnQpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZyB8IG51bGx9XG4gICAgICovXG4gICAgZ2V0Rmlyc3RNZXNzYWdlICgpIHtcbiAgICAgICAgbGV0IGZpcnN0SXNzdWUgPSB0aGlzLmdldEZpcnN0KCk7XG4gICAgICAgIHJldHVybiBmaXJzdElzc3VlID8gZmlyc3RJc3N1ZS5xdWVyeVNlbGVjdG9yKHRoaXMuZmlsdGVyU2VsZWN0b3IpLmlubmVyVGV4dCA6IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IHNlZWRcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2dlbmVyYXRlVW5pcXVlSWQgKHNlZWQpIHtcbiAgICAgICAgbGV0IG9yaWdpbmFsU2VlZCA9IHNlZWQ7XG4gICAgICAgIGxldCBzZWVkU3VmZml4ID0gMTtcblxuICAgICAgICB3aGlsZSAodGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoc2VlZCkpIHtcbiAgICAgICAgICAgIHNlZWQgPSBvcmlnaW5hbFNlZWQgKyAnLScgKyBzZWVkU3VmZml4O1xuICAgICAgICAgICAgc2VlZFN1ZmZpeCsrO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHNlZWQ7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGaWx0ZXJhYmxlSXNzdWVMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rhc2stcmVzdWx0cy9maWx0ZXJhYmxlLWlzc3VlLWxpc3QuanMiLCJsZXQgSXNzdWVMaXN0ID0gcmVxdWlyZSgnLi9pc3N1ZS1saXN0Jyk7XG5cbmNsYXNzIEZpeExpc3QgZXh0ZW5kcyBJc3N1ZUxpc3Qge1xuICAgIGZpbHRlclRvIChmaXhIcmVmKSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmlzc3VlcywgKGlzc3VlRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgaWYgKGlzc3VlRWxlbWVudC5xdWVyeVNlbGVjdG9yKCdhJykuZ2V0QXR0cmlidXRlKCdocmVmJykgIT09IGZpeEhyZWYpIHtcbiAgICAgICAgICAgICAgICBpc3N1ZUVsZW1lbnQucGFyZW50RWxlbWVudC5yZW1vdmVDaGlsZChpc3N1ZUVsZW1lbnQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEZpeExpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGFzay1yZXN1bHRzL2ZpeC1saXN0LmpzIiwibGV0IElzc3VlU2VjdGlvbiA9IHJlcXVpcmUoJy4uL3Rhc2stcmVzdWx0cy9pc3N1ZS1zZWN0aW9uJyk7XG5cbmNsYXNzIElzc3VlQ29udGVudCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcblxuICAgICAgICAvKipcbiAgICAgICAgICogQHR5cGUge0lzc3VlU2VjdGlvbltdfVxuICAgICAgICAgKi9cbiAgICAgICAgdGhpcy5pc3N1ZVNlY3Rpb25zID0gW107XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuaXNzdWUtbGlzdCcpLCAoaXNzdWVTZWN0aW9uRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5pc3N1ZVNlY3Rpb25zLnB1c2gobmV3IElzc3VlU2VjdGlvbihpc3N1ZVNlY3Rpb25FbGVtZW50KSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmlzc3VlU2VjdGlvbnMuZm9yRWFjaCgoaXNzdWVTZWN0aW9uKSA9PiB7XG4gICAgICAgICAgICBpc3N1ZVNlY3Rpb24uaW5pdCgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmlzc3VlU2VjdGlvbnMuZm9yRWFjaCgoaXNzdWVTZWN0aW9uKSA9PiB7XG4gICAgICAgICAgICBpZiAoaXNzdWVTZWN0aW9uLmlzRmlsdGVyYWJsZSgpKSB7XG4gICAgICAgICAgICAgICAgaXNzdWVTZWN0aW9uLmZpbHRlcigpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IHR5cGVcbiAgICAgKiBAcmV0dXJucyB7SXNzdWVTZWN0aW9ufVxuICAgICAqL1xuICAgIGdldElzc3VlU2VjdGlvbiAodHlwZSkge1xuICAgICAgICBsZXQgaXNzdWVTZWN0aW9uID0gbnVsbDtcblxuICAgICAgICB0aGlzLmlzc3VlU2VjdGlvbnMuZm9yRWFjaCgoY3VycmVudElzc3VlU2VjdGlvbikgPT4ge1xuICAgICAgICAgICAgaWYgKGN1cnJlbnRJc3N1ZVNlY3Rpb24uaXNzdWVUeXBlID09PSB0eXBlKSB7XG4gICAgICAgICAgICAgICAgaXNzdWVTZWN0aW9uID0gY3VycmVudElzc3VlU2VjdGlvbjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGlzc3VlU2VjdGlvbjtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBnZXRGaWx0ZXJlZElzc3VlTWVzc2FnZSAoKSB7XG4gICAgICAgIGxldCBmaWx0ZXJlZElzc3VlTWVzc2FnZSA9ICcnO1xuXG4gICAgICAgIHRoaXMuaXNzdWVTZWN0aW9ucy5mb3JFYWNoKChmaWx0ZXJlZElzc3VlU2VjdGlvbikgPT4ge1xuICAgICAgICAgICAgaWYgKGZpbHRlcmVkSXNzdWVTZWN0aW9uLmlzRmlsdGVyZWQoKSkge1xuICAgICAgICAgICAgICAgIGZpbHRlcmVkSXNzdWVNZXNzYWdlID0gZmlsdGVyZWRJc3N1ZVNlY3Rpb24uZ2V0Rmlyc3RJc3N1ZU1lc3NhZ2UoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGZpbHRlcmVkSXNzdWVNZXNzYWdlO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtFbGVtZW50fVxuICAgICAqL1xuICAgIGdldEZpcnN0RmlsdGVyZWRJc3N1ZSAoKSB7XG4gICAgICAgIGxldCBmaXJzdEZpbHRlcmVkSXNzdWUgPSBudWxsO1xuXG4gICAgICAgIHRoaXMuaXNzdWVTZWN0aW9ucy5mb3JFYWNoKChmaWx0ZXJlZElzc3VlU2VjdGlvbikgPT4ge1xuICAgICAgICAgICAgaWYgKGZpbHRlcmVkSXNzdWVTZWN0aW9uLmlzRmlsdGVyZWQoKSkge1xuICAgICAgICAgICAgICAgIGZpcnN0RmlsdGVyZWRJc3N1ZSA9IGZpbHRlcmVkSXNzdWVTZWN0aW9uLmdldEZpcnN0SXNzdWUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGZpcnN0RmlsdGVyZWRJc3N1ZTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IElzc3VlQ29udGVudDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90YXNrLXJlc3VsdHMvaXNzdWUtY29udGVudC5qcyIsImNsYXNzIElzc3VlTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5pc3N1ZXMgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmlzc3VlJyk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge251bWJlcn1cbiAgICAgKi9cbiAgICBjb3VudCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmlzc3VlJykubGVuZ3RoO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtFbGVtZW50fVxuICAgICAqL1xuICAgIGdldEZpcnN0ICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuaXNzdWUnKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IElzc3VlTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90YXNrLXJlc3VsdHMvaXNzdWUtbGlzdC5qcyIsImxldCBJc3N1ZUxpc3QgPSByZXF1aXJlKCcuL2lzc3VlLWxpc3QnKTtcbmxldCBGaXhMaXN0ID0gcmVxdWlyZSgnLi9maXgtbGlzdCcpO1xubGV0IEZpbHRlcmFibGVJc3N1ZUxpc3QgPSByZXF1aXJlKCcuL2ZpbHRlcmFibGUtaXNzdWUtbGlzdCcpO1xuXG5jbGFzcyBJc3N1ZVNlY3Rpb24ge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuaXNzdWVUeXBlID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtaXNzdWUtdHlwZScpO1xuICAgICAgICB0aGlzLmlzc3VlQ291bnRFbGVtZW50ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuaXNzdWUtY291bnQnKTtcbiAgICAgICAgdGhpcy5pc3N1ZUxpc3RzID0gW107XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKGVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmlzc3VlcycpLCAoaXNzdWVzRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IGlzc3VlTGlzdCA9IG51bGw7XG5cbiAgICAgICAgICAgIGlmICh0aGlzLmVsZW1lbnQuaGFzQXR0cmlidXRlKCdkYXRhLWZpbHRlcicpKSB7XG4gICAgICAgICAgICAgICAgaXNzdWVMaXN0ID0gbmV3IEZpbHRlcmFibGVJc3N1ZUxpc3QoaXNzdWVzRWxlbWVudCwgZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtZmlsdGVyLXNlbGVjdG9yJykpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5pc3N1ZVR5cGUgPT09ICdmaXgnKSB7XG4gICAgICAgICAgICAgICAgICAgIGlzc3VlTGlzdCA9IG5ldyBGaXhMaXN0KGlzc3Vlc0VsZW1lbnQpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGlzc3VlTGlzdCA9IG5ldyBJc3N1ZUxpc3QoaXNzdWVzRWxlbWVudCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmlzc3VlTGlzdHMucHVzaChpc3N1ZUxpc3QpO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRJc3N1ZUNvdW50Q2hhbmdlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnaXNzdWVzLXNlY3Rpb24uaXNzdWUtY291bnQuY2hhbmdlZCc7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICBpZiAodGhpcy5pc0ZpbHRlcmFibGUoKSkge1xuICAgICAgICAgICAgbGV0IGZpbHRlciA9IHdpbmRvdy5sb2NhdGlvbi5oYXNoLnJlcGxhY2UoJyMnLCAnJykudHJpbSgpO1xuXG4gICAgICAgICAgICBpZiAoZmlsdGVyKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5pc3N1ZUxpc3RzLmZvckVhY2goKGlzc3VlTGlzdCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICBpc3N1ZUxpc3QuYWRkSGFzaElkQXR0cmlidXRlVG9Jc3N1ZXMoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBmaWx0ZXIgKCkge1xuICAgICAgICBsZXQgZmlsdGVyID0gd2luZG93LmxvY2F0aW9uLmhhc2gucmVwbGFjZSgnIycsICcnKS50cmltKCk7XG5cbiAgICAgICAgaWYgKGZpbHRlcikge1xuICAgICAgICAgICAgbGV0IGZpbHRlcmVkSXNzdWVDb3VudCA9IDA7XG5cbiAgICAgICAgICAgIHRoaXMuaXNzdWVMaXN0cy5mb3JFYWNoKChpc3N1ZUxpc3QpID0+IHtcbiAgICAgICAgICAgICAgICBpc3N1ZUxpc3QuZmlsdGVyKHdpbmRvdy5sb2NhdGlvbi5oYXNoLnJlcGxhY2UoJyMnLCAnJykpO1xuICAgICAgICAgICAgICAgIGZpbHRlcmVkSXNzdWVDb3VudCArPSBpc3N1ZUxpc3QuY291bnQoKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBsZXQgaXNzdWVDb3VudCA9IHBhcnNlSW50KHRoaXMuaXNzdWVDb3VudEVsZW1lbnQuaW5uZXJUZXh0LnRyaW0oKSwgMTApO1xuICAgICAgICAgICAgaWYgKGlzc3VlQ291bnQgIT09IGZpbHRlcmVkSXNzdWVDb3VudCkge1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdmaWx0ZXJlZCcpO1xuICAgICAgICAgICAgICAgIHRoaXMucmVuZGVySXNzdWVDb3VudChmaWx0ZXJlZElzc3VlQ291bnQpO1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChJc3N1ZVNlY3Rpb24uZ2V0SXNzdWVDb3VudENoYW5nZWRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgICAgICAgICBkZXRhaWw6IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdpc3N1ZS10eXBlJzogdGhpcy5pc3N1ZVR5cGUsXG4gICAgICAgICAgICAgICAgICAgICAgICBjb3VudDogZmlsdGVyZWRJc3N1ZUNvdW50XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdmaWx0ZXJlZCcpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBjb3VudFxuICAgICAqL1xuICAgIHJlbmRlcklzc3VlQ291bnQgKGNvdW50KSB7XG4gICAgICAgIHRoaXMuaXNzdWVDb3VudEVsZW1lbnQuaW5uZXJUZXh0ID0gY291bnQ7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzRmlsdGVyYWJsZSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuaGFzQXR0cmlidXRlKCdkYXRhLWZpbHRlcicpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzRmlsdGVyZWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5jb250YWlucygnZmlsdGVyZWQnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBnZXRGaXJzdElzc3VlTWVzc2FnZSAoKSB7XG4gICAgICAgIGxldCBmaXJzdElzc3VlTWVzc2FnZSA9IG51bGw7XG5cbiAgICAgICAgdGhpcy5pc3N1ZUxpc3RzLmZvckVhY2goKGlzc3VlTGlzdCkgPT4ge1xuICAgICAgICAgICAgbGV0IGlzc3VlTGlzdEZpcnN0TWVzc2FnZSA9IGlzc3VlTGlzdC5nZXRGaXJzdE1lc3NhZ2UoKTtcbiAgICAgICAgICAgIGlmIChmaXJzdElzc3VlTWVzc2FnZSA9PT0gbnVsbCAmJiBpc3N1ZUxpc3RGaXJzdE1lc3NhZ2UgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICBmaXJzdElzc3VlTWVzc2FnZSA9IGlzc3VlTGlzdEZpcnN0TWVzc2FnZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGZpcnN0SXNzdWVNZXNzYWdlO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7RWxlbWVudH1cbiAgICAgKi9cbiAgICBnZXRGaXJzdElzc3VlICgpIHtcbiAgICAgICAgbGV0IGZpcnN0SXNzdWUgPSBudWxsO1xuXG4gICAgICAgIHRoaXMuaXNzdWVMaXN0cy5mb3JFYWNoKChpc3N1ZUxpc3QpID0+IHtcbiAgICAgICAgICAgIGxldCBpc3N1ZUxpc3RGaXJzdElzc3VlID0gaXNzdWVMaXN0LmdldEZpcnN0KCk7XG4gICAgICAgICAgICBpZiAoZmlyc3RJc3N1ZSA9PT0gbnVsbCAmJiBpc3N1ZUxpc3RGaXJzdElzc3VlICE9PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgZmlyc3RJc3N1ZSA9IGlzc3VlTGlzdEZpcnN0SXNzdWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBmaXJzdElzc3VlO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBJc3N1ZVNlY3Rpb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGFzay1yZXN1bHRzL2lzc3VlLXNlY3Rpb24uanMiLCJsZXQgU3VtbWFyeVN0YXRzTGFiZWwgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3N1bW1hcnktc3RhdHMtbGFiZWwnKTtcblxuY2xhc3MgU3VtbWFyeVN0YXRzIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmxhYmVscyA9IHt9O1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmxhYmVsJyksIChsYWJlbEVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIGxldCBsYWJlbCA9IG5ldyBTdW1tYXJ5U3RhdHNMYWJlbChsYWJlbEVsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy5sYWJlbHNbbGFiZWwuZ2V0SXNzdWVUeXBlKCldID0gbGFiZWw7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgT2JqZWN0LmtleXModGhpcy5sYWJlbHMpLmZvckVhY2goKHR5cGUpID0+IHtcbiAgICAgICAgICAgIHRoaXMubGFiZWxzW3R5cGVdLmluaXQoKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSB0eXBlXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IGNvdW50XG4gICAgICovXG4gICAgc2V0SXNzdWVDb3VudCAodHlwZSwgY291bnQpIHtcbiAgICAgICAgdGhpcy5sYWJlbHNbdHlwZV0uc2V0Q291bnQoY291bnQpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU3VtbWFyeVN0YXRzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rhc2stcmVzdWx0cy9zdW1tYXJ5LXN0YXRzLmpzIiwiLyogZ2xvYmFsIEF3ZXNvbXBsZXRlICovXG5cbmxldCBmb3JtRmllbGRGb2N1c2VyID0gcmVxdWlyZSgnLi4vZm9ybS1maWVsZC1mb2N1c2VyJyk7XG5yZXF1aXJlKCdhd2Vzb21wbGV0ZScpO1xuXG5jbGFzcyBNb2RhbCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtIVE1MRWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJyNhcHBseS1maWx0ZXItYnV0dG9uJyk7XG4gICAgICAgIHRoaXMuY2xlYXJCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJyNjbGVhci1maWx0ZXItYnV0dG9uJyk7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jbG9zZScpO1xuICAgICAgICB0aGlzLmlucHV0ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPWZpbHRlcl0nKTtcbiAgICAgICAgdGhpcy5maWx0ZXIgPSB0aGlzLmlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c0ZpbHRlciA9IHRoaXMuaW5wdXQudmFsdWUudHJpbSgpO1xuICAgICAgICB0aGlzLmFwcGx5RmlsdGVyID0gZmFsc2U7XG4gICAgICAgIHRoaXMuYXdlc29tZXBsZXRlID0gbmV3IEF3ZXNvbXBsZXRlKHRoaXMuaW5wdXQpO1xuICAgICAgICB0aGlzLnN1Z2dlc3Rpb25zID0gW107XG4gICAgICAgIHRoaXMuZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSA9ICd0ZXN0LWhpc3RvcnkubW9kYWwuZmlsdGVyLmNoYW5nZWQnO1xuXG4gICAgICAgIHRoaXMuaW5pdCgpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7U3RyaW5nW119IHN1Z2dlc3Rpb25zXG4gICAgICovXG4gICAgc2V0U3VnZ2VzdGlvbnMgKHN1Z2dlc3Rpb25zKSB7XG4gICAgICAgIHRoaXMuc3VnZ2VzdGlvbnMgPSBzdWdnZXN0aW9ucztcbiAgICAgICAgdGhpcy5hd2Vzb21lcGxldGUubGlzdCA9IHRoaXMuc3VnZ2VzdGlvbnM7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGxldCBzaG93bkV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHRoaXMuaW5wdXQpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRlRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGNvbnN0IFdJTERDQVJEID0gJyonO1xuICAgICAgICAgICAgY29uc3QgZmlsdGVySXNFbXB0eSA9IHRoaXMuZmlsdGVyID09PSAnJztcbiAgICAgICAgICAgIGNvbnN0IHN1Z2dlc3Rpb25zSW5jbHVkZXNGaWx0ZXIgPSB0aGlzLnN1Z2dlc3Rpb25zLmluY2x1ZGVzKHRoaXMuZmlsdGVyKTtcbiAgICAgICAgICAgIGNvbnN0IGZpbHRlcklzV2lsZGNhcmRQcmVmaXhlZCA9IHRoaXMuZmlsdGVyLmNoYXJBdCgwKSA9PT0gV0lMRENBUkQ7XG4gICAgICAgICAgICBjb25zdCBmaWx0ZXJJc1dpbGRjYXJkU3VmZml4ZWQgPSB0aGlzLmZpbHRlci5zbGljZSgtMSkgPT09IFdJTERDQVJEO1xuXG4gICAgICAgICAgICBpZiAoIWZpbHRlcklzRW1wdHkgJiYgIXN1Z2dlc3Rpb25zSW5jbHVkZXNGaWx0ZXIpIHtcbiAgICAgICAgICAgICAgICBpZiAoIWZpbHRlcklzV2lsZGNhcmRQcmVmaXhlZCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZpbHRlciA9IFdJTERDQVJEICsgdGhpcy5maWx0ZXI7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgaWYgKCFmaWx0ZXJJc1dpbGRjYXJkU3VmZml4ZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5maWx0ZXIgKz0gV0lMRENBUkQ7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgdGhpcy5pbnB1dC52YWx1ZSA9IHRoaXMuZmlsdGVyO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmFwcGx5RmlsdGVyID0gdGhpcy5maWx0ZXIgIT09IHRoaXMucHJldmlvdXNGaWx0ZXI7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGhpZGRlbkV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoIXRoaXMuYXBwbHlGaWx0ZXIpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudCh0aGlzLmZpbHRlckNoYW5nZWRFdmVudE5hbWUsIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHRoaXMuZmlsdGVyXG4gICAgICAgICAgICB9KSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGFwcGx5QnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5maWx0ZXIgPSB0aGlzLmlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgICAgICB0aGlzLmZpbHRlciA9ICcnO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuYXBwbHlGaWx0ZXIgPSBmYWxzZTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc2hvd24uYnMubW9kYWwnLCBzaG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRlLmJzLm1vZGFsJywgaGlkZUV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRkZW4uYnMubW9kYWwnLCBoaWRkZW5FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmFwcGx5QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgYXBwbHlCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuY2xlYXJCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBjbGVhckJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTW9kYWw7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L21vZGFsLmpzIiwiY2xhc3MgU3VnZ2VzdGlvbnMge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICogQHBhcmFtIHtTdHJpbmd9IHNvdXJjZVVybFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCwgc291cmNlVXJsKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zb3VyY2VVcmwgPSBzb3VyY2VVcmw7XG4gICAgICAgIHRoaXMubG9hZGVkRXZlbnROYW1lID0gJ3Rlc3QtaGlzdG9yeS5zdWdnZXN0aW9ucy5sb2FkZWQnO1xuICAgIH1cblxuICAgIHJldHJpZXZlICgpIHtcbiAgICAgICAgbGV0IHJlcXVlc3QgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcbiAgICAgICAgbGV0IHN1Z2dlc3Rpb25zID0gbnVsbDtcblxuICAgICAgICByZXF1ZXN0Lm9wZW4oJ0dFVCcsIHRoaXMuc291cmNlVXJsLCBmYWxzZSk7XG5cbiAgICAgICAgbGV0IHJlcXVlc3RPbmxvYWRIYW5kbGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKHJlcXVlc3Quc3RhdHVzID49IDIwMCAmJiByZXF1ZXN0LnN0YXR1cyA8IDQwMCkge1xuICAgICAgICAgICAgICAgIHN1Z2dlc3Rpb25zID0gSlNPTi5wYXJzZShyZXF1ZXN0LnJlc3BvbnNlVGV4dCk7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KHRoaXMubG9hZGVkRXZlbnROYW1lLCB7XG4gICAgICAgICAgICAgICAgICAgIGRldGFpbDogc3VnZ2VzdGlvbnNcbiAgICAgICAgICAgICAgICB9KSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgcmVxdWVzdC5vbmxvYWQgPSByZXF1ZXN0T25sb2FkSGFuZGxlci5iaW5kKHRoaXMpO1xuXG4gICAgICAgIHJlcXVlc3Quc2VuZCgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU3VnZ2VzdGlvbnM7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L3N1Z2dlc3Rpb25zLmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5sZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhcicpO1xubGV0IFRhc2tRdWV1ZXMgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWVzJyk7XG5cbmNsYXNzIFN1bW1hcnkge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuY2FuY2VsQWN0aW9uID0gbmV3IEZvcm1CdXR0b24oZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY2FuY2VsLWFjdGlvbicpKTtcbiAgICAgICAgdGhpcy5jYW5jZWxDcmF3bEFjdGlvbiA9IG5ldyBGb3JtQnV0dG9uKGVsZW1lbnQucXVlcnlTZWxlY3RvcignLmNhbmNlbC1jcmF3bC1hY3Rpb24nKSk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBuZXcgUHJvZ3Jlc3NCYXIoZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJvZ3Jlc3MtYmFyJykpO1xuICAgICAgICB0aGlzLnN0YXRlTGFiZWwgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1zdGF0ZS1sYWJlbCcpO1xuICAgICAgICB0aGlzLnRhc2tRdWV1ZXMgPSBuZXcgVGFza1F1ZXVlcyhlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy50YXNrLXF1ZXVlcycpKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5fYWRkRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0UmVuZGVyQW1tZW5kbWVudEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnJlbmRlci1hbW1lbmRtZW50JztcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgdGhpcy5jYW5jZWxBY3Rpb24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NhbmNlbEFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5jYW5jZWxDcmF3bEFjdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2FuY2VsQ3Jhd2xBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIF9jYW5jZWxBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICB0aGlzLmNhbmNlbEFjdGlvbi5tYXJrQXNCdXN5KCk7XG4gICAgICAgIHRoaXMuY2FuY2VsQWN0aW9uLmRlRW1waGFzaXplKCk7XG4gICAgfTtcblxuICAgIF9jYW5jZWxDcmF3bEFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIHRoaXMuY2FuY2VsQ3Jhd2xBY3Rpb24ubWFya0FzQnVzeSgpO1xuICAgICAgICB0aGlzLmNhbmNlbENyYXdsQWN0aW9uLmRlRW1waGFzaXplKCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7b2JqZWN0fSBzdW1tYXJ5RGF0YVxuICAgICAqL1xuICAgIHJlbmRlciAoc3VtbWFyeURhdGEpIHtcbiAgICAgICAgbGV0IHRlc3QgPSBzdW1tYXJ5RGF0YS50ZXN0O1xuXG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQodGVzdC5jb21wbGV0aW9uX3BlcmNlbnQpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldFN0eWxlKHRlc3Quc3RhdGUgPT09ICdjcmF3bGluZycgPyAnd2FybmluZycgOiAnZGVmYXVsdCcpO1xuICAgICAgICB0aGlzLnN0YXRlTGFiZWwuaW5uZXJUZXh0ID0gc3VtbWFyeURhdGEuc3RhdGVfbGFiZWw7XG4gICAgICAgIHRoaXMudGFza1F1ZXVlcy5yZW5kZXIodGVzdC50YXNrX2NvdW50LCB0ZXN0LnRhc2tfY291bnRfYnlfc3RhdGUpO1xuXG4gICAgICAgIGlmICh0ZXN0LmFtZW5kbWVudHMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFN1bW1hcnkuZ2V0UmVuZGVyQW1tZW5kbWVudEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiB0ZXN0LmFtZW5kbWVudHNbMF1cbiAgICAgICAgICAgIH0pKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU3VtbWFyeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3N1bW1hcnkuanMiLCJjbGFzcyBUYXNrSWRMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcltdfSB0YXNrSWRzXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VMZW5ndGhcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAodGFza0lkcywgcGFnZUxlbmd0aCkge1xuICAgICAgICB0aGlzLnRhc2tJZHMgPSB0YXNrSWRzO1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSBwYWdlTGVuZ3RoO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtudW1iZXJbXX1cbiAgICAgKi9cbiAgICBnZXRGb3JQYWdlIChwYWdlSW5kZXgpIHtcbiAgICAgICAgbGV0IHBhZ2VOdW1iZXIgPSBwYWdlSW5kZXggKyAxO1xuXG4gICAgICAgIHJldHVybiB0aGlzLnRhc2tJZHMuc2xpY2UocGFnZUluZGV4ICogdGhpcy5wYWdlTGVuZ3RoLCBwYWdlTnVtYmVyICogdGhpcy5wYWdlTGVuZ3RoKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tJZExpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy90YXNrLWlkLWxpc3QuanMiLCJjbGFzcyBUYXNrTGlzdFBhZ2luYXRpb24ge1xuICAgIGNvbnN0cnVjdG9yIChwYWdlTGVuZ3RoLCB0YXNrQ291bnQpIHtcbiAgICAgICAgdGhpcy5wYWdlTGVuZ3RoID0gcGFnZUxlbmd0aDtcbiAgICAgICAgdGhpcy50YXNrQ291bnQgPSB0YXNrQ291bnQ7XG4gICAgICAgIHRoaXMucGFnZUNvdW50ID0gTWF0aC5jZWlsKHRhc2tDb3VudCAvIHBhZ2VMZW5ndGgpO1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBudWxsO1xuICAgIH1cblxuICAgIHN0YXRpYyBnZXRTZWxlY3RvciAoKSB7XG4gICAgICAgIHJldHVybiAnLnBhZ2luYXRpb24nO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRTZWxlY3RQYWdlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0YXNrLWxpc3QtcGFnaW5hdGlvbi5zZWxlY3QtcGFnZSc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGFzay1saXN0LXBhZ2luYXRpb24uc2VsZWN0LXByZXZpb3VzLXBhZ2UnO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldFNlbGVjdE5leHRQYWdlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0YXNrLWxpc3QtcGFnaW5hdGlvbi5zZWxlY3QtbmV4dC1wYWdlJztcbiAgICB9XG5cbiAgICBpbml0IChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMucGFnZUFjdGlvbnMgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2EnKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c0FjdGlvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtcm9sZT1wcmV2aW91c10nKTtcbiAgICAgICAgdGhpcy5uZXh0QWN0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1yb2xlPW5leHRdJyk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMucGFnZUFjdGlvbnMsIChwYWdlQWN0aW9ucykgPT4ge1xuICAgICAgICAgICAgcGFnZUFjdGlvbnMuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgICAgICAgICAgbGV0IGFjdGlvbkNvbnRhaW5lciA9IHBhZ2VBY3Rpb25zLnBhcmVudE5vZGU7XG4gICAgICAgICAgICAgICAgaWYgKCFhY3Rpb25Db250YWluZXIuY2xhc3NMaXN0LmNvbnRhaW5zKCdhY3RpdmUnKSkge1xuICAgICAgICAgICAgICAgICAgICBsZXQgcm9sZSA9IHBhZ2VBY3Rpb25zLmdldEF0dHJpYnV0ZSgnZGF0YS1yb2xlJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHJvbGUgPT09ICdzaG93UGFnZScpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0UGFnZUV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGV0YWlsOiBwYXJzZUludChwYWdlQWN0aW9ucy5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMClcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGlmIChyb2xlID09PSAncHJldmlvdXMnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSgpKSk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBpZiAocm9sZSA9PT0gJ25leHQnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdE5leHRQYWdlRXZlbnROYW1lKCkpKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgY3JlYXRlTWFya3VwICgpIHtcbiAgICAgICAgbGV0IG1hcmt1cCA9ICc8dWwgY2xhc3M9XCJwYWdpbmF0aW9uXCI+JztcblxuICAgICAgICBtYXJrdXAgKz0gJzxsaSBjbGFzcz1cImlzLXhzIHByZXZpb3VzLW5leHQgcHJldmlvdXMgZGlzYWJsZWQgaGlkZGVuLWxnIGhpZGRlbi1tZCBoaWRkZW4tc21cIj48YSBocmVmPVwiI1wiIGRhdGEtcm9sZT1cInByZXZpb3VzXCI+PGkgY2xhc3M9XCJmYSBmYS1jYXJldC1sZWZ0XCI+PC9pPiBQcmV2aW91czwvYT48L2xpPic7XG4gICAgICAgIG1hcmt1cCArPSAnPGxpIGNsYXNzPVwiaGlkZGVuLWxnIGhpZGRlbi1tZCBoaWRkZW4tc20gZGlzYWJsZWRcIj48c3Bhbj5QYWdlIDxzdHJvbmcgY2xhc3M9XCJwYWdlLW51bWJlclwiPjE8L3N0cm9uZz4gb2YgPHN0cm9uZz4nICsgdGhpcy5wYWdlQ291bnQgKyAnPC9zdHJvbmc+PC9zcGFuPjwvbGk+JztcblxuICAgICAgICBmb3IgKGxldCBwYWdlSW5kZXggPSAwOyBwYWdlSW5kZXggPCB0aGlzLnBhZ2VDb3VudDsgcGFnZUluZGV4KyspIHtcbiAgICAgICAgICAgIGxldCBzdGFydEluZGV4ID0gKHBhZ2VJbmRleCAqIHRoaXMucGFnZUxlbmd0aCkgKyAxO1xuICAgICAgICAgICAgbGV0IGVuZEluZGV4ID0gTWF0aC5taW4oc3RhcnRJbmRleCArIHRoaXMucGFnZUxlbmd0aCAtIDEsIHRoaXMudGFza0NvdW50KTtcblxuICAgICAgICAgICAgbWFya3VwICs9ICc8bGkgY2xhc3M9XCJpcy1ub3QteHMgaGlkZGVuLXhzICcgKyAocGFnZUluZGV4ID09PSAwID8gJ2FjdGl2ZScgOiAnJykgKyAnXCI+PGEgaHJlZj1cIiNcIiBkYXRhLXBhZ2UtaW5kZXg9XCInICsgcGFnZUluZGV4ICsgJ1wiIGRhdGEtcm9sZT1cInNob3dQYWdlXCI+JyArIHN0YXJ0SW5kZXggKyAnIOKApiAnICsgZW5kSW5kZXggKyAnPC9hPjwvbGk+JztcbiAgICAgICAgfVxuXG4gICAgICAgIG1hcmt1cCArPSAnPGxpIGNsYXNzPVwibmV4dCBwcmV2aW91cy1uZXh0IGhpZGRlbi1sZyBoaWRkZW4tbWQgaGlkZGVuLXNtXCI+PGEgaHJlZj1cIiNcIiBkYXRhLXJvbGU9XCJuZXh0XCI+TmV4dCA8aSBjbGFzcz1cImZhIGZhLWNhcmV0LXJpZ2h0XCI+PC9pPjwvYT48L2xpPic7XG4gICAgICAgIG1hcmt1cCArPSAnPC91bD4nO1xuXG4gICAgICAgIHJldHVybiBtYXJrdXA7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzUmVxdWlyZWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy50YXNrQ291bnQgPiB0aGlzLnBhZ2VMZW5ndGg7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzUmVuZGVyZWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50ICE9PSBudWxsO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICovXG4gICAgc2VsZWN0UGFnZSAocGFnZUluZGV4KSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLnBhZ2VBY3Rpb25zLCAocGFnZUFjdGlvbikgPT4ge1xuICAgICAgICAgICAgbGV0IGlzQWN0aXZlID0gcGFyc2VJbnQocGFnZUFjdGlvbi5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMCkgPT09IHBhZ2VJbmRleDtcbiAgICAgICAgICAgIGxldCBhY3Rpb25Db250YWluZXIgPSBwYWdlQWN0aW9uLnBhcmVudE5vZGU7XG5cbiAgICAgICAgICAgIGlmIChpc0FjdGl2ZSkge1xuICAgICAgICAgICAgICAgIGFjdGlvbkNvbnRhaW5lci5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgYWN0aW9uQ29udGFpbmVyLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnBhZ2UtbnVtYmVyJykuaW5uZXJUZXh0ID0gKHBhZ2VJbmRleCArIDEpO1xuICAgICAgICB0aGlzLnByZXZpb3VzQWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGlzYWJsZWQnKTtcbiAgICAgICAgdGhpcy5uZXh0QWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGlzYWJsZWQnKTtcblxuICAgICAgICBpZiAocGFnZUluZGV4ID09PSAwKSB7XG4gICAgICAgICAgICB0aGlzLnByZXZpb3VzQWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGlzYWJsZWQnKTtcbiAgICAgICAgfSBlbHNlIGlmIChwYWdlSW5kZXggPT09IHRoaXMucGFnZUNvdW50IC0gMSkge1xuICAgICAgICAgICAgdGhpcy5uZXh0QWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGlzYWJsZWQnKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza0xpc3RQYWdpbmF0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LXBhZ2luYXRvci5qcyIsImxldCBUYXNrTGlzdE1vZGVsID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC90YXNrLWxpc3QnKTtcbmxldCBJY29uID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9pY29uJyk7XG5sZXQgVGFza0lkTGlzdCA9IHJlcXVpcmUoJy4vdGFzay1pZC1saXN0Jyk7XG5sZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5cbmNsYXNzIFRhc2tMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUxlbmd0aFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBwYWdlTGVuZ3RoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuY3VycmVudFBhZ2VJbmRleCA9IDA7XG4gICAgICAgIHRoaXMucGFnZUxlbmd0aCA9IHBhZ2VMZW5ndGg7XG4gICAgICAgIHRoaXMudGFza0lkTGlzdCA9IG51bGw7XG4gICAgICAgIHRoaXMuaXNJbml0aWFsaXppbmcgPSBmYWxzZTtcbiAgICAgICAgdGhpcy50YXNrTGlzdE1vZGVscyA9IHt9O1xuICAgICAgICB0aGlzLmhlYWRpbmcgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2gyJyk7XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEB0eXBlIHtJY29ufVxuICAgICAgICAgKi9cbiAgICAgICAgdGhpcy5idXN5SWNvbiA9IHRoaXMuX2NyZWF0ZUJ1c3lJY29uKCk7XG4gICAgICAgIHRoaXMuaGVhZGluZy5hcHBlbmRDaGlsZCh0aGlzLmJ1c3lJY29uLmVsZW1lbnQpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmlzSW5pdGlhbGl6aW5nID0gdHJ1ZTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2hpZGRlbicpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuX3JlcXVlc3RUYXNrSWRzKCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBpbmRleFxuICAgICAqL1xuICAgIHNldEN1cnJlbnRQYWdlSW5kZXggKGluZGV4KSB7XG4gICAgICAgIHRoaXMuY3VycmVudFBhZ2VJbmRleCA9IGluZGV4O1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gcGFnaW5hdGlvbkVsZW1lbnRcbiAgICAgKi9cbiAgICBzZXRQYWdpbmF0aW9uRWxlbWVudCAocGFnaW5hdGlvbkVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5oZWFkaW5nLmluc2VydEFkamFjZW50RWxlbWVudCgnYWZ0ZXJlbmQnLCBwYWdpbmF0aW9uRWxlbWVudCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rhc2stbGlzdC5pbml0aWFsaXplZCc7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCByZXF1ZXN0SWQgPSBldmVudC5kZXRhaWwucmVxdWVzdElkO1xuICAgICAgICBsZXQgcmVzcG9uc2UgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JlcXVlc3RUYXNrSWRzJykge1xuICAgICAgICAgICAgdGhpcy50YXNrSWRMaXN0ID0gbmV3IFRhc2tJZExpc3QocmVzcG9uc2UsIHRoaXMucGFnZUxlbmd0aCk7XG4gICAgICAgICAgICB0aGlzLmlzSW5pdGlhbGl6aW5nID0gZmFsc2U7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3QuZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlVGFza1BhZ2UnKSB7XG4gICAgICAgICAgICBsZXQgdGFza0xpc3RNb2RlbCA9IG5ldyBUYXNrTGlzdE1vZGVsKHRoaXMuX2NyZWF0ZVRhc2tMaXN0RWxlbWVudEZyb21IdG1sKHJlc3BvbnNlKSk7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gdGFza0xpc3RNb2RlbC5nZXRQYWdlSW5kZXgoKTtcblxuICAgICAgICAgICAgdGhpcy50YXNrTGlzdE1vZGVsc1twYWdlSW5kZXhdID0gdGFza0xpc3RNb2RlbDtcbiAgICAgICAgICAgIHRoaXMucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkoXG4gICAgICAgICAgICAgICAgcGFnZUluZGV4LFxuICAgICAgICAgICAgICAgIHRhc2tMaXN0TW9kZWwuZ2V0VGFza3NCeVN0YXRlcyhbJ2luLXByb2dyZXNzJywgJ3F1ZXVlZC1mb3ItYXNzaWdubWVudCcsICdxdWV1ZWQnXSkuc2xpY2UoMCwgMTApXG4gICAgICAgICAgICApO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlVGFza1NldCcpIHtcbiAgICAgICAgICAgIGxldCB1cGRhdGVkVGFza0xpc3RNb2RlbCA9IG5ldyBUYXNrTGlzdE1vZGVsKHRoaXMuX2NyZWF0ZVRhc2tMaXN0RWxlbWVudEZyb21IdG1sKHJlc3BvbnNlKSk7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gdXBkYXRlZFRhc2tMaXN0TW9kZWwuZ2V0UGFnZUluZGV4KCk7XG4gICAgICAgICAgICBsZXQgdGFza0xpc3RNb2RlbCA9IHRoaXMudGFza0xpc3RNb2RlbHNbcGFnZUluZGV4XTtcblxuICAgICAgICAgICAgdGFza0xpc3RNb2RlbC51cGRhdGVGcm9tVGFza0xpc3QodXBkYXRlZFRhc2tMaXN0TW9kZWwpO1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUYXNrU2V0V2l0aERlbGF5KFxuICAgICAgICAgICAgICAgIHBhZ2VJbmRleCxcbiAgICAgICAgICAgICAgICB0YXNrTGlzdE1vZGVsLmdldFRhc2tzQnlTdGF0ZXMoWydpbi1wcm9ncmVzcycsICdxdWV1ZWQtZm9yLWFzc2lnbm1lbnQnLCAncXVldWVkJ10pLnNsaWNlKDAsIDEwKVxuICAgICAgICAgICAgKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfcmVxdWVzdFRhc2tJZHMgKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldEpzb24odGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLWlkcy11cmwnKSwgdGhpcy5lbGVtZW50LCAncmVxdWVzdFRhc2tJZHMnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqL1xuICAgIHJlbmRlciAocGFnZUluZGV4KSB7XG4gICAgICAgIHRoaXMuYnVzeUljb24uZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdoaWRkZW4nKTtcblxuICAgICAgICBsZXQgaGFzVGFza0xpc3RFbGVtZW50Rm9yUGFnZSA9IE9iamVjdC5rZXlzKHRoaXMudGFza0xpc3RNb2RlbHMpLmluY2x1ZGVzKHBhZ2VJbmRleC50b1N0cmluZygxMCkpO1xuICAgICAgICBpZiAoIWhhc1Rhc2tMaXN0RWxlbWVudEZvclBhZ2UpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlVGFza1BhZ2UocGFnZUluZGV4KTtcblxuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgbGV0IHRhc2tMaXN0RWxlbWVudCA9IHRoaXMudGFza0xpc3RNb2RlbHNbcGFnZUluZGV4XTtcblxuICAgICAgICBpZiAocGFnZUluZGV4ID09PSB0aGlzLmN1cnJlbnRQYWdlSW5kZXgpIHtcbiAgICAgICAgICAgIGxldCByZW5kZXJlZFRhc2tMaXN0RWxlbWVudCA9IG5ldyBUYXNrTGlzdE1vZGVsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcudGFzay1saXN0JykpO1xuXG4gICAgICAgICAgICBpZiAocmVuZGVyZWRUYXNrTGlzdEVsZW1lbnQuaGFzUGFnZUluZGV4KCkpIHtcbiAgICAgICAgICAgICAgICBsZXQgY3VycmVudFBhZ2VMaXN0RWxlbWVudCA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcudGFzay1saXN0Jyk7XG4gICAgICAgICAgICAgICAgbGV0IHNlbGVjdGVkUGFnZUxpc3RFbGVtZW50ID0gdGhpcy50YXNrTGlzdE1vZGVsc1t0aGlzLmN1cnJlbnRQYWdlSW5kZXhdLmVsZW1lbnQ7XG5cbiAgICAgICAgICAgICAgICBjdXJyZW50UGFnZUxpc3RFbGVtZW50LnBhcmVudE5vZGUucmVwbGFjZUNoaWxkKHNlbGVjdGVkUGFnZUxpc3RFbGVtZW50LCBjdXJyZW50UGFnZUxpc3RFbGVtZW50KTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZENoaWxkKHRhc2tMaXN0RWxlbWVudC5lbGVtZW50KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuYnVzeUljb24uZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdoaWRkZW4nKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX3JldHJpZXZlVGFza1BhZ2UgKHBhZ2VJbmRleCkge1xuICAgICAgICBsZXQgdGFza0lkcyA9IHRoaXMudGFza0lkTGlzdC5nZXRGb3JQYWdlKHBhZ2VJbmRleCk7XG4gICAgICAgIGxldCBwb3N0RGF0YSA9ICdwYWdlSW5kZXg9JyArIHBhZ2VJbmRleCArICcmdGFza0lkc1tdPScgKyB0YXNrSWRzLmpvaW4oJyZ0YXNrSWRzW109Jyk7XG5cbiAgICAgICAgSHR0cENsaWVudC5wb3N0KFxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrbGlzdC11cmwnKSxcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudCxcbiAgICAgICAgICAgICdyZXRyaWV2ZVRhc2tQYWdlJyxcbiAgICAgICAgICAgIHBvc3REYXRhXG4gICAgICAgICk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKiBAcGFyYW0ge1Rhc2tbXX0gdGFza3NcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkgKHBhZ2VJbmRleCwgdGFza3MpIHtcbiAgICAgICAgd2luZG93LnNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUYXNrU2V0KHBhZ2VJbmRleCwgdGFza3MpO1xuICAgICAgICB9LCAxMDAwKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqIEBwYXJhbSB7VGFza1tdfSB0YXNrc1xuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX3JldHJpZXZlVGFza1NldCAocGFnZUluZGV4LCB0YXNrcykge1xuICAgICAgICBpZiAodGhpcy5jdXJyZW50UGFnZUluZGV4ID09PSBwYWdlSW5kZXggJiYgdGFza3MubGVuZ3RoKSB7XG4gICAgICAgICAgICBsZXQgdGFza0lkcyA9IFtdO1xuXG4gICAgICAgICAgICB0YXNrcy5mb3JFYWNoKGZ1bmN0aW9uICh0YXNrKSB7XG4gICAgICAgICAgICAgICAgdGFza0lkcy5wdXNoKHRhc2suZ2V0SWQoKSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbGV0IHBvc3REYXRhID0gJ3BhZ2VJbmRleD0nICsgcGFnZUluZGV4ICsgJyZ0YXNrSWRzW109JyArIHRhc2tJZHMuam9pbignJnRhc2tJZHNbXT0nKTtcblxuICAgICAgICAgICAgSHR0cENsaWVudC5wb3N0KFxuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGFza2xpc3QtdXJsJyksXG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LFxuICAgICAgICAgICAgICAgICdyZXRyaWV2ZVRhc2tTZXQnLFxuICAgICAgICAgICAgICAgIHBvc3REYXRhXG4gICAgICAgICAgICApO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBodG1sXG4gICAgICogQHJldHVybnMge0VsZW1lbnR9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlVGFza0xpc3RFbGVtZW50RnJvbUh0bWwgKGh0bWwpIHtcbiAgICAgICAgbGV0IGNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gaHRtbDtcblxuICAgICAgICByZXR1cm4gY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy50YXNrLWxpc3QnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge0ljb259XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlQnVzeUljb24gKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmYVwiPjwvaT4nO1xuXG4gICAgICAgIGxldCBpY29uID0gbmV3IEljb24oY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5mYScpKTtcbiAgICAgICAgaWNvbi5zZXRCdXN5KCk7XG5cbiAgICAgICAgcmV0dXJuIGljb247XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LmpzIiwibGV0IEJ5UGFnZUxpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdCcpO1xuXG5jbGFzcyBCeUVycm9yTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5ieVBhZ2VMaXN0cyA9IFtdO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuYnktcGFnZS1jb250YWluZXInKSwgKGJ5UGFnZUNvbnRhaW5lckVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIGlmIChieVBhZ2VDb250YWluZXJFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5qcy1zb3J0YWJsZS1pdGVtJykubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgICAgIHRoaXMuYnlQYWdlTGlzdHMucHVzaChuZXcgQnlQYWdlTGlzdChieVBhZ2VDb250YWluZXJFbGVtZW50KSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5ieVBhZ2VMaXN0cy5mb3JFYWNoKChieVBhZ2VMaXN0KSA9PiB7XG4gICAgICAgICAgICBieVBhZ2VMaXN0LmluaXQoKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBCeUVycm9yTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QuanMiLCJsZXQgU29ydCA9IHJlcXVpcmUoJy4vc29ydCcpO1xuXG5jbGFzcyBCeVBhZ2VMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnNvcnQgPSBuZXcgU29ydChlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5zb3J0JyksIGVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLXNvcnRhYmxlLWl0ZW0nKSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLnNvcnQuaW5pdCgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQnlQYWdlTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdC5qcyIsImxldCBTb3J0Q29udHJvbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sJyk7XG5sZXQgU29ydGFibGVJdGVtID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtJyk7XG5sZXQgU29ydGFibGVJdGVtTGlzdCA9IHJlcXVpcmUoJy4uL21vZGVsL3NvcnRhYmxlLWl0ZW0tbGlzdCcpO1xubGV0IFNvcnRDb250cm9sQ29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL3NvcnQtY29udHJvbC1jb2xsZWN0aW9uJyk7XG5cbmNsYXNzIFNvcnQge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7Tm9kZUxpc3R9IHNvcnRhYmxlSXRlbXNOb2RlTGlzdFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzb3J0YWJsZUl0ZW1zTm9kZUxpc3QpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zb3J0Q29udHJvbENvbGxlY3Rpb24gPSB0aGlzLl9jcmVhdGVTb3J0YWJsZUNvbnRyb2xDb2xsZWN0aW9uKCk7XG4gICAgICAgIHRoaXMuc29ydGFibGVJdGVtc05vZGVMaXN0ID0gc29ydGFibGVJdGVtc05vZGVMaXN0O1xuICAgICAgICB0aGlzLnNvcnRhYmxlSXRlbXNMaXN0ID0gdGhpcy5fY3JlYXRlU29ydGFibGVJdGVtTGlzdCgpO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2ludmlzaWJsZScpO1xuICAgICAgICB0aGlzLnNvcnRDb250cm9sQ29sbGVjdGlvbi5jb250cm9scy5mb3JFYWNoKChjb250cm9sKSA9PiB7XG4gICAgICAgICAgICBjb250cm9sLmluaXQoKTtcbiAgICAgICAgICAgIGNvbnRyb2wuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFNvcnRDb250cm9sLmdldFNvcnRSZXF1ZXN0ZWRFdmVudE5hbWUoKSwgdGhpcy5fc29ydENvbnRyb2xDbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7U29ydGFibGVJdGVtTGlzdH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVTb3J0YWJsZUl0ZW1MaXN0ICgpIHtcbiAgICAgICAgbGV0IHNvcnRhYmxlSXRlbXMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QsIChzb3J0YWJsZUl0ZW1FbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBzb3J0YWJsZUl0ZW1zLnB1c2gobmV3IFNvcnRhYmxlSXRlbShzb3J0YWJsZUl0ZW1FbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBuZXcgU29ydGFibGVJdGVtTGlzdChzb3J0YWJsZUl0ZW1zKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7U29ydENvbnRyb2xDb2xsZWN0aW9ufVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVNvcnRhYmxlQ29udHJvbENvbGxlY3Rpb24gKCkge1xuICAgICAgICBsZXQgY29udHJvbHMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5zb3J0LWNvbnRyb2wnKSwgKHNvcnRDb250cm9sRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgY29udHJvbHMucHVzaChuZXcgU29ydENvbnRyb2woc29ydENvbnRyb2xFbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBuZXcgU29ydENvbnRyb2xDb2xsZWN0aW9uKGNvbnRyb2xzKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9zb3J0Q29udHJvbENsaWNrRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMuc29ydGFibGVJdGVtc05vZGVMaXN0Lml0ZW0oMCkucGFyZW50RWxlbWVudDtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QsIChzb3J0YWJsZUl0ZW1FbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBzb3J0YWJsZUl0ZW1FbGVtZW50LnBhcmVudEVsZW1lbnQucmVtb3ZlQ2hpbGQoc29ydGFibGVJdGVtRWxlbWVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGxldCBzb3J0ZWRJdGVtcyA9IHRoaXMuc29ydGFibGVJdGVtc0xpc3Quc29ydChldmVudC5kZXRhaWwua2V5cyk7XG5cbiAgICAgICAgc29ydGVkSXRlbXMuZm9yRWFjaCgoc29ydGFibGVJdGVtKSA9PiB7XG4gICAgICAgICAgICBwYXJlbnQuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdiZWZvcmVlbmQnLCBzb3J0YWJsZUl0ZW0uZWxlbWVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuc29ydENvbnRyb2xDb2xsZWN0aW9uLnNldFNvcnRlZChldmVudC50YXJnZXQpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU29ydDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL3NvcnQuanMiLCJsZXQgTW9kYWwgPSByZXF1aXJlKCdib290c3RyYXAubmF0aXZlJykuTW9kYWw7XG5cbi8qKlxuICogQHBhcmFtIHtOb2RlTGlzdH0gdGFza1R5cGVDb250YWluZXJzXG4gKi9cbm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKHRhc2tUeXBlQ29udGFpbmVycykge1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGFza1R5cGVDb250YWluZXJzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGxldCB1bmF2YWlsYWJsZVRhc2tUeXBlID0gdGFza1R5cGVDb250YWluZXJzW2ldO1xuICAgICAgICBsZXQgdGFza1R5cGVLZXkgPSB1bmF2YWlsYWJsZVRhc2tUeXBlLmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLXR5cGUnKTtcbiAgICAgICAgbGV0IG1vZGFsSWQgPSB0YXNrVHlwZUtleSArICctYWNjb3VudC1yZXF1aXJlZC1tb2RhbCc7XG4gICAgICAgIGxldCBtb2RhbEVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChtb2RhbElkKTtcbiAgICAgICAgbGV0IG1vZGFsID0gbmV3IE1vZGFsKG1vZGFsRWxlbWVudCk7XG5cbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG1vZGFsLnNob3coKTtcbiAgICAgICAgfSk7XG4gICAgfVxufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91bmF2YWlsYWJsZS10YXNrLXR5cGUtbW9kYWwtbGF1bmNoZXIuanMiLCJjbGFzcyBGb3JtVmFsaWRhdG9yIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1N0cmlwZX0gc3RyaXBlSnNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoc3RyaXBlSnMpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcyA9IHN0cmlwZUpzO1xuICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IG51bGw7XG4gICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJyc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBkYXRhXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgdmFsaWRhdGUgKGRhdGEpIHtcbiAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSBudWxsO1xuXG4gICAgICAgIE9iamVjdC5lbnRyaWVzKGRhdGEpLmZvckVhY2goKFtrZXksIHZhbHVlXSkgPT4ge1xuICAgICAgICAgICAgaWYgKCF0aGlzLmludmFsaWRGaWVsZCkge1xuICAgICAgICAgICAgICAgIGxldCBjb21wYXJhdG9yVmFsdWUgPSB2YWx1ZS50cmltKCk7XG5cbiAgICAgICAgICAgICAgICBpZiAoY29tcGFyYXRvclZhbHVlID09PSAnJykge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IGtleTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnZW1wdHknO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgaWYgKHRoaXMuaW52YWxpZEZpZWxkKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXMuc3RyaXBlSnMuY2FyZC52YWxpZGF0ZUNhcmROdW1iZXIoZGF0YS5udW1iZXIpKSB7XG4gICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9ICdudW1iZXInO1xuICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnaW52YWxpZCc7XG5cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICghdGhpcy5zdHJpcGVKcy5jYXJkLnZhbGlkYXRlRXhwaXJ5KGRhdGEuZXhwX21vbnRoLCBkYXRhLmV4cF95ZWFyKSkge1xuICAgICAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSAnZXhwX21vbnRoJztcbiAgICAgICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJ2ludmFsaWQnO1xuXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXMuc3RyaXBlSnMuY2FyZC52YWxpZGF0ZUNWQyhkYXRhLmN2YykpIHtcbiAgICAgICAgICAgIHRoaXMuaW52YWxpZEZpZWxkID0gJ2N2Yyc7XG4gICAgICAgICAgICB0aGlzLmVycm9yTWVzc2FnZSA9ICdpbnZhbGlkJztcblxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtVmFsaWRhdG9yO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0tdmFsaWRhdG9yLmpzIiwibGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcbmxldCBBbGVydEZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5Jyk7XG5sZXQgRm9ybUJ1dHRvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcblxuY2xhc3MgRm9ybSB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHZhbGlkYXRvcikge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnZhbGlkYXRvciA9IHZhbGlkYXRvcjtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24gPSBuZXcgRm9ybUJ1dHRvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgdGhpcy5fc3VibWl0RXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgZ2V0U3RyaXBlUHVibGlzaGFibGVLZXkgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdHJpcGUtcHVibGlzaGFibGUta2V5Jyk7XG4gICAgfTtcblxuICAgIGdldFVwZGF0ZUNhcmRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3VzZXIuYWNjb3VudC5jYXJkLnVwZGF0ZSc7XG4gICAgfVxuXG4gICAgZGlzYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9uLmRpc2FibGUoKTtcbiAgICB9O1xuXG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24ubWFya0FzQXZhaWxhYmxlKCk7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9uLmVuYWJsZSgpO1xuICAgIH07XG5cbiAgICBfZ2V0RGF0YSAoKSB7XG4gICAgICAgIGNvbnN0IGRhdGEgPSB7fTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tkYXRhLXN0cmlwZV0nKSwgZnVuY3Rpb24gKGRhdGFFbGVtZW50KSB7XG4gICAgICAgICAgICBsZXQgZmllbGRLZXkgPSBkYXRhRWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RyaXBlJyk7XG5cbiAgICAgICAgICAgIGRhdGFbZmllbGRLZXldID0gZGF0YUVsZW1lbnQudmFsdWU7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBkYXRhO1xuICAgIH1cblxuICAgIF9zdWJtaXRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICB0aGlzLl9yZW1vdmVFcnJvckFsZXJ0cygpO1xuICAgICAgICB0aGlzLmRpc2FibGUoKTtcblxuICAgICAgICBsZXQgZGF0YSA9IHRoaXMuX2dldERhdGEoKTtcbiAgICAgICAgbGV0IGlzVmFsaWQgPSB0aGlzLnZhbGlkYXRvci52YWxpZGF0ZShkYXRhKTtcblxuICAgICAgICBpZiAoIWlzVmFsaWQpIHtcbiAgICAgICAgICAgIHRoaXMuaGFuZGxlUmVzcG9uc2VFcnJvcih0aGlzLmNyZWF0ZVJlc3BvbnNlRXJyb3IodGhpcy52YWxpZGF0b3IuaW52YWxpZEZpZWxkLCB0aGlzLnZhbGlkYXRvci5lcnJvck1lc3NhZ2UpKTtcbiAgICAgICAgICAgIHRoaXMuZW5hYmxlKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsZXQgZXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQodGhpcy5nZXRVcGRhdGVDYXJkRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IGRhdGFcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChldmVudCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JlbW92ZUVycm9yQWxlcnRzICgpIHtcbiAgICAgICAgbGV0IGVycm9yQWxlcnRzID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hbGVydCcpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbChlcnJvckFsZXJ0cywgZnVuY3Rpb24gKGVycm9yQWxlcnQpIHtcbiAgICAgICAgICAgIGVycm9yQWxlcnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChlcnJvckFsZXJ0KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9kaXNwbGF5RmllbGRFcnJvciAoZmllbGQsIGVycm9yKSB7XG4gICAgICAgIGxldCBhbGVydCA9IEFsZXJ0RmFjdG9yeS5jcmVhdGVGcm9tQ29udGVudCh0aGlzLmRvY3VtZW50LCAnPHA+JyArIGVycm9yLm1lc3NhZ2UgKyAnPC9wPicsIGZpZWxkLmdldEF0dHJpYnV0ZSgnaWQnKSk7XG4gICAgICAgIGxldCBlcnJvckNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1mb3I9JyArIGZpZWxkLmdldEF0dHJpYnV0ZSgnaWQnKSArICddJyk7XG5cbiAgICAgICAgaWYgKCFlcnJvckNvbnRhaW5lcikge1xuICAgICAgICAgICAgZXJyb3JDb250YWluZXIgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtZm9yKj0nICsgZmllbGQuZ2V0QXR0cmlidXRlKCdpZCcpICsgJ10nKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGVycm9yQ29udGFpbmVyLmFwcGVuZChhbGVydC5lbGVtZW50KTtcbiAgICB9O1xuXG4gICAgaGFuZGxlUmVzcG9uc2VFcnJvciAoZXJyb3IpIHtcbiAgICAgICAgbGV0IGZpZWxkID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXN0cmlwZT0nICsgZXJyb3IucGFyYW0gKyAnXScpO1xuXG4gICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZmllbGQpO1xuICAgICAgICB0aGlzLl9kaXNwbGF5RmllbGRFcnJvcihmaWVsZCwgZXJyb3IpO1xuICAgIH07XG5cbiAgICBjcmVhdGVSZXNwb25zZUVycm9yIChmaWVsZCwgc3RhdGUpIHtcbiAgICAgICAgbGV0IGVycm9yTWVzc2FnZSA9ICcnO1xuXG4gICAgICAgIGlmIChzdGF0ZSA9PT0gJ2VtcHR5Jykge1xuICAgICAgICAgICAgZXJyb3JNZXNzYWdlID0gJ0hvbGQgb24sIHlvdSBjYW5cXCd0IGxlYXZlIHRoaXMgZW1wdHknO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnaW52YWxpZCcpIHtcbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnVGhlIGNhcmQgbnVtYmVyIGlzIG5vdCBxdWl0ZSByaWdodCc7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ2V4cF9tb250aCcpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnQW4gZXhwaXJ5IGRhdGUgaW4gdGhlIGZ1dHVyZSBpcyBiZXR0ZXInO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoZmllbGQgPT09ICdjdmMnKSB7XG4gICAgICAgICAgICAgICAgZXJyb3JNZXNzYWdlID0gJ1RoZSBDVkMgc2hvdWxkIGJlIDMgb3IgNCBkaWdpdHMnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHBhcmFtOiBmaWVsZCxcbiAgICAgICAgICAgIG1lc3NhZ2U6IGVycm9yTWVzc2FnZVxuICAgICAgICB9O1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0uanMiLCJjb25zdCBTY3JvbGxUbyA9IHJlcXVpcmUoJy4uL3Njcm9sbC10bycpO1xuXG5jbGFzcyBOYXZCYXJBbmNob3Ige1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBzY3JvbGxPZmZzZXRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCwgc2Nyb2xsT2Zmc2V0KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc2Nyb2xsT2Zmc2V0ID0gc2Nyb2xsT2Zmc2V0O1xuICAgICAgICB0aGlzLnRhcmdldElkID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKS5yZXBsYWNlKCcjJywgJycpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuaGFuZGxlQ2xpY2suYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIGhhbmRsZUNsaWNrIChldmVudCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICBsZXQgdGFyZ2V0ID0gdGhpcy5nZXRUYXJnZXQoKTtcblxuICAgICAgICBpZiAodGhpcy5lbGVtZW50LmNsYXNzTGlzdC5jb250YWlucygnanMtZmlyc3QnKSkge1xuICAgICAgICAgICAgU2Nyb2xsVG8uZ29Ubyh0YXJnZXQsIDApO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgU2Nyb2xsVG8uc2Nyb2xsVG8odGFyZ2V0LCB0aGlzLnNjcm9sbE9mZnNldCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBnZXRUYXJnZXQgKCkge1xuICAgICAgICByZXR1cm4gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy50YXJnZXRJZCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IE5hdkJhckFuY2hvcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWFuY2hvci5qcyIsImNvbnN0IE5hdkJhckFuY2hvciA9IHJlcXVpcmUoJy4vbmF2YmFyLWFuY2hvcicpO1xuXG5jbGFzcyBOYXZCYXJJdGVtIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gc2Nyb2xsT2Zmc2V0XG4gICAgICogQHBhcmFtIHtmdW5jdGlvbn0gTmF2QmFyTGlzdFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQsIE5hdkJhckxpc3QpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5hbmNob3IgPSBudWxsO1xuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBudWxsO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZWxlbWVudC5jaGlsZHJlbi5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgbGV0IGNoaWxkID0gZWxlbWVudC5jaGlsZHJlbi5pdGVtKGkpO1xuXG4gICAgICAgICAgICBpZiAoY2hpbGQubm9kZU5hbWUgPT09ICdBJyAmJiBjaGlsZC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKVswXSA9PT0gJyMnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5hbmNob3IgPSBuZXcgTmF2QmFyQW5jaG9yKGNoaWxkLCBzY3JvbGxPZmZzZXQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoY2hpbGQubm9kZU5hbWUgPT09ICdVTCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuZXcgTmF2QmFyTGlzdChjaGlsZCwgc2Nyb2xsT2Zmc2V0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBnZXRUYXJnZXRzICgpIHtcbiAgICAgICAgbGV0IHRhcmdldHMgPSBbXTtcblxuICAgICAgICBpZiAodGhpcy5hbmNob3IpIHtcbiAgICAgICAgICAgIHRhcmdldHMucHVzaCh0aGlzLmFuY2hvci5nZXRUYXJnZXQoKSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5uYXZCYXJMaXN0KSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QuZ2V0VGFyZ2V0cygpLmZvckVhY2goZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICAgICAgICAgIHRhcmdldHMucHVzaCh0YXJnZXQpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFyZ2V0cztcbiAgICB9XG5cbiAgICBjb250YWluc1RhcmdldElkICh0YXJnZXRJZCkge1xuICAgICAgICBpZiAodGhpcy5hbmNob3IgJiYgdGhpcy5hbmNob3IudGFyZ2V0SWQgPT09IHRhcmdldElkKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLm5hdkJhckxpc3QuY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfTtcblxuICAgIHNldEFjdGl2ZSAodGFyZ2V0SWQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QgJiYgdGhpcy5uYXZCYXJMaXN0LmNvbnRhaW5zVGFyZ2V0SWQodGFyZ2V0SWQpKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3Quc2V0QWN0aXZlKHRhcmdldElkKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTmF2QmFySXRlbTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWl0ZW0uanMiLCJsZXQgTmF2QmFySXRlbSA9IHJlcXVpcmUoJy4vbmF2YmFyLWl0ZW0nKTtcblxuY2xhc3MgTmF2QmFyTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHNjcm9sbE9mZnNldFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcyA9IFtdO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZWxlbWVudC5jaGlsZHJlbi5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5wdXNoKG5ldyBOYXZCYXJJdGVtKGVsZW1lbnQuY2hpbGRyZW4uaXRlbShpKSwgc2Nyb2xsT2Zmc2V0LCBOYXZCYXJMaXN0KSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VGFyZ2V0cyAoKSB7XG4gICAgICAgIGxldCB0YXJnZXRzID0gW107XG5cbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0aGlzLm5hdkJhckl0ZW1zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckl0ZW1zW2ldLmdldFRhcmdldHMoKS5mb3JFYWNoKGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICB0YXJnZXRzLnB1c2godGFyZ2V0KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRhcmdldHM7XG4gICAgfTtcblxuICAgIGNvbnRhaW5zVGFyZ2V0SWQgKHRhcmdldElkKSB7XG4gICAgICAgIGxldCBjb250YWlucyA9IGZhbHNlO1xuXG4gICAgICAgIHRoaXMubmF2QmFySXRlbXMuZm9yRWFjaChmdW5jdGlvbiAobmF2QmFySXRlbSkge1xuICAgICAgICAgICAgaWYgKG5hdkJhckl0ZW0uY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCkpIHtcbiAgICAgICAgICAgICAgICBjb250YWlucyA9IHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBjb250YWlucztcbiAgICB9O1xuXG4gICAgY2xlYXJBY3RpdmUgKCkge1xuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpJyksIGZ1bmN0aW9uIChsaXN0SXRlbUVsZW1lbnQpIHtcbiAgICAgICAgICAgIGxpc3RJdGVtRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHNldEFjdGl2ZSAodGFyZ2V0SWQpIHtcbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5mb3JFYWNoKGZ1bmN0aW9uIChuYXZCYXJJdGVtKSB7XG4gICAgICAgICAgICBpZiAobmF2QmFySXRlbS5jb250YWluc1RhcmdldElkKHRhcmdldElkKSkge1xuICAgICAgICAgICAgICAgIG5hdkJhckl0ZW0uc2V0QWN0aXZlKHRhcmdldElkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBOYXZCYXJMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItbGlzdC5qcyIsInJlcXVpcmUoJy4vbmF2YmFyLWxpc3QnKTtcblxuY2xhc3MgU2Nyb2xsU3B5IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05hdkJhckxpc3R9IG5hdkJhckxpc3RcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gb2Zmc2V0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKG5hdkJhckxpc3QsIG9mZnNldCkge1xuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuYXZCYXJMaXN0O1xuICAgICAgICB0aGlzLm9mZnNldCA9IG9mZnNldDtcbiAgICB9XG5cbiAgICBzY3JvbGxFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgbGV0IGFjdGl2ZUxpbmtUYXJnZXQgPSBudWxsO1xuICAgICAgICBsZXQgbGlua1RhcmdldHMgPSB0aGlzLm5hdkJhckxpc3QuZ2V0VGFyZ2V0cygpO1xuICAgICAgICBsZXQgb2Zmc2V0ID0gdGhpcy5vZmZzZXQ7XG4gICAgICAgIGxldCBsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQgPSBbXTtcblxuICAgICAgICBsaW5rVGFyZ2V0cy5mb3JFYWNoKGZ1bmN0aW9uIChsaW5rVGFyZ2V0KSB7XG4gICAgICAgICAgICBpZiAobGlua1RhcmdldCkge1xuICAgICAgICAgICAgICAgIGxldCBvZmZzZXRUb3AgPSBsaW5rVGFyZ2V0LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcDtcblxuICAgICAgICAgICAgICAgIGlmIChvZmZzZXRUb3AgPCBvZmZzZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgbGlua1RhcmdldHNQYXN0VGhyZXNob2xkLnB1c2gobGlua1RhcmdldCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBpZiAobGlua1RhcmdldHNQYXN0VGhyZXNob2xkLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzWzBdO1xuICAgICAgICB9IGVsc2UgaWYgKGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZC5sZW5ndGggPT09IGxpbmtUYXJnZXRzLmxlbmd0aCkge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzW2xpbmtUYXJnZXRzLmxlbmd0aCAtIDFdO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZFtsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQubGVuZ3RoIC0gMV07XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoYWN0aXZlTGlua1RhcmdldCkge1xuICAgICAgICAgICAgdGhpcy5uYXZCYXJMaXN0LmNsZWFyQWN0aXZlKCk7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3Quc2V0QWN0aXZlKGFjdGl2ZUxpbmtUYXJnZXQuZ2V0QXR0cmlidXRlKCdpZCcpKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIHNweSAoKSB7XG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFxuICAgICAgICAgICAgJ3Njcm9sbCcsXG4gICAgICAgICAgICB0aGlzLnNjcm9sbEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSxcbiAgICAgICAgICAgIHRydWVcbiAgICAgICAgKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU2Nyb2xsU3B5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9zY3JvbGxzcHkuanMiLCIvKipcbiAqIFNpbXBsZSwgbGlnaHR3ZWlnaHQsIHVzYWJsZSBsb2NhbCBhdXRvY29tcGxldGUgbGlicmFyeSBmb3IgbW9kZXJuIGJyb3dzZXJzXG4gKiBCZWNhdXNlIHRoZXJlIHdlcmVu4oCZdCBlbm91Z2ggYXV0b2NvbXBsZXRlIHNjcmlwdHMgaW4gdGhlIHdvcmxkPyBCZWNhdXNlIEnigJltIGNvbXBsZXRlbHkgaW5zYW5lIGFuZCBoYXZlIE5JSCBzeW5kcm9tZT8gUHJvYmFibHkgYm90aC4gOlBcbiAqIEBhdXRob3IgTGVhIFZlcm91IGh0dHA6Ly9sZWF2ZXJvdS5naXRodWIuaW8vYXdlc29tcGxldGVcbiAqIE1JVCBsaWNlbnNlXG4gKi9cblxuKGZ1bmN0aW9uICgpIHtcblxudmFyIF8gPSBmdW5jdGlvbiAoaW5wdXQsIG8pIHtcblx0dmFyIG1lID0gdGhpcztcblxuXHQvLyBTZXR1cFxuXG5cdHRoaXMuaXNPcGVuZWQgPSBmYWxzZTtcblxuXHR0aGlzLmlucHV0ID0gJChpbnB1dCk7XG5cdHRoaXMuaW5wdXQuc2V0QXR0cmlidXRlKFwiYXV0b2NvbXBsZXRlXCIsIFwib2ZmXCIpO1xuXHR0aGlzLmlucHV0LnNldEF0dHJpYnV0ZShcImFyaWEtYXV0b2NvbXBsZXRlXCIsIFwibGlzdFwiKTtcblxuXHRvID0gbyB8fCB7fTtcblxuXHRjb25maWd1cmUodGhpcywge1xuXHRcdG1pbkNoYXJzOiAyLFxuXHRcdG1heEl0ZW1zOiAxMCxcblx0XHRhdXRvRmlyc3Q6IGZhbHNlLFxuXHRcdGRhdGE6IF8uREFUQSxcblx0XHRmaWx0ZXI6IF8uRklMVEVSX0NPTlRBSU5TLFxuXHRcdHNvcnQ6IG8uc29ydCA9PT0gZmFsc2UgPyBmYWxzZSA6IF8uU09SVF9CWUxFTkdUSCxcblx0XHRpdGVtOiBfLklURU0sXG5cdFx0cmVwbGFjZTogXy5SRVBMQUNFXG5cdH0sIG8pO1xuXG5cdHRoaXMuaW5kZXggPSAtMTtcblxuXHQvLyBDcmVhdGUgbmVjZXNzYXJ5IGVsZW1lbnRzXG5cblx0dGhpcy5jb250YWluZXIgPSAkLmNyZWF0ZShcImRpdlwiLCB7XG5cdFx0Y2xhc3NOYW1lOiBcImF3ZXNvbXBsZXRlXCIsXG5cdFx0YXJvdW5kOiBpbnB1dFxuXHR9KTtcblxuXHR0aGlzLnVsID0gJC5jcmVhdGUoXCJ1bFwiLCB7XG5cdFx0aGlkZGVuOiBcImhpZGRlblwiLFxuXHRcdGluc2lkZTogdGhpcy5jb250YWluZXJcblx0fSk7XG5cblx0dGhpcy5zdGF0dXMgPSAkLmNyZWF0ZShcInNwYW5cIiwge1xuXHRcdGNsYXNzTmFtZTogXCJ2aXN1YWxseS1oaWRkZW5cIixcblx0XHRyb2xlOiBcInN0YXR1c1wiLFxuXHRcdFwiYXJpYS1saXZlXCI6IFwiYXNzZXJ0aXZlXCIsXG5cdFx0XCJhcmlhLXJlbGV2YW50XCI6IFwiYWRkaXRpb25zXCIsXG5cdFx0aW5zaWRlOiB0aGlzLmNvbnRhaW5lclxuXHR9KTtcblxuXHQvLyBCaW5kIGV2ZW50c1xuXG5cdHRoaXMuX2V2ZW50cyA9IHtcblx0XHRpbnB1dDoge1xuXHRcdFx0XCJpbnB1dFwiOiB0aGlzLmV2YWx1YXRlLmJpbmQodGhpcyksXG5cdFx0XHRcImJsdXJcIjogdGhpcy5jbG9zZS5iaW5kKHRoaXMsIHsgcmVhc29uOiBcImJsdXJcIiB9KSxcblx0XHRcdFwia2V5ZG93blwiOiBmdW5jdGlvbihldnQpIHtcblx0XHRcdFx0dmFyIGMgPSBldnQua2V5Q29kZTtcblxuXHRcdFx0XHQvLyBJZiB0aGUgZHJvcGRvd24gYHVsYCBpcyBpbiB2aWV3LCB0aGVuIGFjdCBvbiBrZXlkb3duIGZvciB0aGUgZm9sbG93aW5nIGtleXM6XG5cdFx0XHRcdC8vIEVudGVyIC8gRXNjIC8gVXAgLyBEb3duXG5cdFx0XHRcdGlmKG1lLm9wZW5lZCkge1xuXHRcdFx0XHRcdGlmIChjID09PSAxMyAmJiBtZS5zZWxlY3RlZCkgeyAvLyBFbnRlclxuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZS5zZWxlY3QoKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0ZWxzZSBpZiAoYyA9PT0gMjcpIHsgLy8gRXNjXG5cdFx0XHRcdFx0XHRtZS5jbG9zZSh7IHJlYXNvbjogXCJlc2NcIiB9KTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0ZWxzZSBpZiAoYyA9PT0gMzggfHwgYyA9PT0gNDApIHsgLy8gRG93bi9VcCBhcnJvd1xuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZVtjID09PSAzOD8gXCJwcmV2aW91c1wiIDogXCJuZXh0XCJdKCk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fSxcblx0XHRmb3JtOiB7XG5cdFx0XHRcInN1Ym1pdFwiOiB0aGlzLmNsb3NlLmJpbmQodGhpcywgeyByZWFzb246IFwic3VibWl0XCIgfSlcblx0XHR9LFxuXHRcdHVsOiB7XG5cdFx0XHRcIm1vdXNlZG93blwiOiBmdW5jdGlvbihldnQpIHtcblx0XHRcdFx0dmFyIGxpID0gZXZ0LnRhcmdldDtcblxuXHRcdFx0XHRpZiAobGkgIT09IHRoaXMpIHtcblxuXHRcdFx0XHRcdHdoaWxlIChsaSAmJiAhL2xpL2kudGVzdChsaS5ub2RlTmFtZSkpIHtcblx0XHRcdFx0XHRcdGxpID0gbGkucGFyZW50Tm9kZTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRpZiAobGkgJiYgZXZ0LmJ1dHRvbiA9PT0gMCkgeyAgLy8gT25seSBzZWxlY3Qgb24gbGVmdCBjbGlja1xuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZS5zZWxlY3QobGksIGV2dC50YXJnZXQpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH1cblx0fTtcblxuXHQkLmJpbmQodGhpcy5pbnB1dCwgdGhpcy5fZXZlbnRzLmlucHV0KTtcblx0JC5iaW5kKHRoaXMuaW5wdXQuZm9ybSwgdGhpcy5fZXZlbnRzLmZvcm0pO1xuXHQkLmJpbmQodGhpcy51bCwgdGhpcy5fZXZlbnRzLnVsKTtcblxuXHRpZiAodGhpcy5pbnB1dC5oYXNBdHRyaWJ1dGUoXCJsaXN0XCIpKSB7XG5cdFx0dGhpcy5saXN0ID0gXCIjXCIgKyB0aGlzLmlucHV0LmdldEF0dHJpYnV0ZShcImxpc3RcIik7XG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJsaXN0XCIpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdHRoaXMubGlzdCA9IHRoaXMuaW5wdXQuZ2V0QXR0cmlidXRlKFwiZGF0YS1saXN0XCIpIHx8IG8ubGlzdCB8fCBbXTtcblx0fVxuXG5cdF8uYWxsLnB1c2godGhpcyk7XG59O1xuXG5fLnByb3RvdHlwZSA9IHtcblx0c2V0IGxpc3QobGlzdCkge1xuXHRcdGlmIChBcnJheS5pc0FycmF5KGxpc3QpKSB7XG5cdFx0XHR0aGlzLl9saXN0ID0gbGlzdDtcblx0XHR9XG5cdFx0ZWxzZSBpZiAodHlwZW9mIGxpc3QgPT09IFwic3RyaW5nXCIgJiYgbGlzdC5pbmRleE9mKFwiLFwiKSA+IC0xKSB7XG5cdFx0XHRcdHRoaXMuX2xpc3QgPSBsaXN0LnNwbGl0KC9cXHMqLFxccyovKTtcblx0XHR9XG5cdFx0ZWxzZSB7IC8vIEVsZW1lbnQgb3IgQ1NTIHNlbGVjdG9yXG5cdFx0XHRsaXN0ID0gJChsaXN0KTtcblxuXHRcdFx0aWYgKGxpc3QgJiYgbGlzdC5jaGlsZHJlbikge1xuXHRcdFx0XHR2YXIgaXRlbXMgPSBbXTtcblx0XHRcdFx0c2xpY2UuYXBwbHkobGlzdC5jaGlsZHJlbikuZm9yRWFjaChmdW5jdGlvbiAoZWwpIHtcblx0XHRcdFx0XHRpZiAoIWVsLmRpc2FibGVkKSB7XG5cdFx0XHRcdFx0XHR2YXIgdGV4dCA9IGVsLnRleHRDb250ZW50LnRyaW0oKTtcblx0XHRcdFx0XHRcdHZhciB2YWx1ZSA9IGVsLnZhbHVlIHx8IHRleHQ7XG5cdFx0XHRcdFx0XHR2YXIgbGFiZWwgPSBlbC5sYWJlbCB8fCB0ZXh0O1xuXHRcdFx0XHRcdFx0aWYgKHZhbHVlICE9PSBcIlwiKSB7XG5cdFx0XHRcdFx0XHRcdGl0ZW1zLnB1c2goeyBsYWJlbDogbGFiZWwsIHZhbHVlOiB2YWx1ZSB9KTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0pO1xuXHRcdFx0XHR0aGlzLl9saXN0ID0gaXRlbXM7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0aWYgKGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQgPT09IHRoaXMuaW5wdXQpIHtcblx0XHRcdHRoaXMuZXZhbHVhdGUoKTtcblx0XHR9XG5cdH0sXG5cblx0Z2V0IHNlbGVjdGVkKCkge1xuXHRcdHJldHVybiB0aGlzLmluZGV4ID4gLTE7XG5cdH0sXG5cblx0Z2V0IG9wZW5lZCgpIHtcblx0XHRyZXR1cm4gdGhpcy5pc09wZW5lZDtcblx0fSxcblxuXHRjbG9zZTogZnVuY3Rpb24gKG8pIHtcblx0XHRpZiAoIXRoaXMub3BlbmVkKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0dGhpcy51bC5zZXRBdHRyaWJ1dGUoXCJoaWRkZW5cIiwgXCJcIik7XG5cdFx0dGhpcy5pc09wZW5lZCA9IGZhbHNlO1xuXHRcdHRoaXMuaW5kZXggPSAtMTtcblxuXHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLWNsb3NlXCIsIG8gfHwge30pO1xuXHR9LFxuXG5cdG9wZW46IGZ1bmN0aW9uICgpIHtcblx0XHR0aGlzLnVsLnJlbW92ZUF0dHJpYnV0ZShcImhpZGRlblwiKTtcblx0XHR0aGlzLmlzT3BlbmVkID0gdHJ1ZTtcblxuXHRcdGlmICh0aGlzLmF1dG9GaXJzdCAmJiB0aGlzLmluZGV4ID09PSAtMSkge1xuXHRcdFx0dGhpcy5nb3RvKDApO1xuXHRcdH1cblxuXHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLW9wZW5cIik7XG5cdH0sXG5cblx0ZGVzdHJveTogZnVuY3Rpb24oKSB7XG5cdFx0Ly9yZW1vdmUgZXZlbnRzIGZyb20gdGhlIGlucHV0IGFuZCBpdHMgZm9ybVxuXHRcdCQudW5iaW5kKHRoaXMuaW5wdXQsIHRoaXMuX2V2ZW50cy5pbnB1dCk7XG5cdFx0JC51bmJpbmQodGhpcy5pbnB1dC5mb3JtLCB0aGlzLl9ldmVudHMuZm9ybSk7XG5cblx0XHQvL21vdmUgdGhlIGlucHV0IG91dCBvZiB0aGUgYXdlc29tcGxldGUgY29udGFpbmVyIGFuZCByZW1vdmUgdGhlIGNvbnRhaW5lciBhbmQgaXRzIGNoaWxkcmVuXG5cdFx0dmFyIHBhcmVudE5vZGUgPSB0aGlzLmNvbnRhaW5lci5wYXJlbnROb2RlO1xuXG5cdFx0cGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUodGhpcy5pbnB1dCwgdGhpcy5jb250YWluZXIpO1xuXHRcdHBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5jb250YWluZXIpO1xuXG5cdFx0Ly9yZW1vdmUgYXV0b2NvbXBsZXRlIGFuZCBhcmlhLWF1dG9jb21wbGV0ZSBhdHRyaWJ1dGVzXG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJhdXRvY29tcGxldGVcIik7XG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJhcmlhLWF1dG9jb21wbGV0ZVwiKTtcblxuXHRcdC8vcmVtb3ZlIHRoaXMgYXdlc29tZXBsZXRlIGluc3RhbmNlIGZyb20gdGhlIGdsb2JhbCBhcnJheSBvZiBpbnN0YW5jZXNcblx0XHR2YXIgaW5kZXhPZkF3ZXNvbXBsZXRlID0gXy5hbGwuaW5kZXhPZih0aGlzKTtcblxuXHRcdGlmIChpbmRleE9mQXdlc29tcGxldGUgIT09IC0xKSB7XG5cdFx0XHRfLmFsbC5zcGxpY2UoaW5kZXhPZkF3ZXNvbXBsZXRlLCAxKTtcblx0XHR9XG5cdH0sXG5cblx0bmV4dDogZnVuY3Rpb24gKCkge1xuXHRcdHZhciBjb3VudCA9IHRoaXMudWwuY2hpbGRyZW4ubGVuZ3RoO1xuXHRcdHRoaXMuZ290byh0aGlzLmluZGV4IDwgY291bnQgLSAxID8gdGhpcy5pbmRleCArIDEgOiAoY291bnQgPyAwIDogLTEpICk7XG5cdH0sXG5cblx0cHJldmlvdXM6IGZ1bmN0aW9uICgpIHtcblx0XHR2YXIgY291bnQgPSB0aGlzLnVsLmNoaWxkcmVuLmxlbmd0aDtcblx0XHR2YXIgcG9zID0gdGhpcy5pbmRleCAtIDE7XG5cblx0XHR0aGlzLmdvdG8odGhpcy5zZWxlY3RlZCAmJiBwb3MgIT09IC0xID8gcG9zIDogY291bnQgLSAxKTtcblx0fSxcblxuXHQvLyBTaG91bGQgbm90IGJlIHVzZWQsIGhpZ2hsaWdodHMgc3BlY2lmaWMgaXRlbSB3aXRob3V0IGFueSBjaGVja3MhXG5cdGdvdG86IGZ1bmN0aW9uIChpKSB7XG5cdFx0dmFyIGxpcyA9IHRoaXMudWwuY2hpbGRyZW47XG5cblx0XHRpZiAodGhpcy5zZWxlY3RlZCkge1xuXHRcdFx0bGlzW3RoaXMuaW5kZXhdLnNldEF0dHJpYnV0ZShcImFyaWEtc2VsZWN0ZWRcIiwgXCJmYWxzZVwiKTtcblx0XHR9XG5cblx0XHR0aGlzLmluZGV4ID0gaTtcblxuXHRcdGlmIChpID4gLTEgJiYgbGlzLmxlbmd0aCA+IDApIHtcblx0XHRcdGxpc1tpXS5zZXRBdHRyaWJ1dGUoXCJhcmlhLXNlbGVjdGVkXCIsIFwidHJ1ZVwiKTtcblx0XHRcdHRoaXMuc3RhdHVzLnRleHRDb250ZW50ID0gbGlzW2ldLnRleHRDb250ZW50O1xuXG5cdFx0XHQvLyBzY3JvbGwgdG8gaGlnaGxpZ2h0ZWQgZWxlbWVudCBpbiBjYXNlIHBhcmVudCdzIGhlaWdodCBpcyBmaXhlZFxuXHRcdFx0dGhpcy51bC5zY3JvbGxUb3AgPSBsaXNbaV0ub2Zmc2V0VG9wIC0gdGhpcy51bC5jbGllbnRIZWlnaHQgKyBsaXNbaV0uY2xpZW50SGVpZ2h0O1xuXG5cdFx0XHQkLmZpcmUodGhpcy5pbnB1dCwgXCJhd2Vzb21wbGV0ZS1oaWdobGlnaHRcIiwge1xuXHRcdFx0XHR0ZXh0OiB0aGlzLnN1Z2dlc3Rpb25zW3RoaXMuaW5kZXhdXG5cdFx0XHR9KTtcblx0XHR9XG5cdH0sXG5cblx0c2VsZWN0OiBmdW5jdGlvbiAoc2VsZWN0ZWQsIG9yaWdpbikge1xuXHRcdGlmIChzZWxlY3RlZCkge1xuXHRcdFx0dGhpcy5pbmRleCA9ICQuc2libGluZ0luZGV4KHNlbGVjdGVkKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0c2VsZWN0ZWQgPSB0aGlzLnVsLmNoaWxkcmVuW3RoaXMuaW5kZXhdO1xuXHRcdH1cblxuXHRcdGlmIChzZWxlY3RlZCkge1xuXHRcdFx0dmFyIHN1Z2dlc3Rpb24gPSB0aGlzLnN1Z2dlc3Rpb25zW3RoaXMuaW5kZXhdO1xuXG5cdFx0XHR2YXIgYWxsb3dlZCA9ICQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLXNlbGVjdFwiLCB7XG5cdFx0XHRcdHRleHQ6IHN1Z2dlc3Rpb24sXG5cdFx0XHRcdG9yaWdpbjogb3JpZ2luIHx8IHNlbGVjdGVkXG5cdFx0XHR9KTtcblxuXHRcdFx0aWYgKGFsbG93ZWQpIHtcblx0XHRcdFx0dGhpcy5yZXBsYWNlKHN1Z2dlc3Rpb24pO1xuXHRcdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcInNlbGVjdFwiIH0pO1xuXHRcdFx0XHQkLmZpcmUodGhpcy5pbnB1dCwgXCJhd2Vzb21wbGV0ZS1zZWxlY3Rjb21wbGV0ZVwiLCB7XG5cdFx0XHRcdFx0dGV4dDogc3VnZ2VzdGlvblxuXHRcdFx0XHR9KTtcblx0XHRcdH1cblx0XHR9XG5cdH0sXG5cblx0ZXZhbHVhdGU6IGZ1bmN0aW9uKCkge1xuXHRcdHZhciBtZSA9IHRoaXM7XG5cdFx0dmFyIHZhbHVlID0gdGhpcy5pbnB1dC52YWx1ZTtcblxuXHRcdGlmICh2YWx1ZS5sZW5ndGggPj0gdGhpcy5taW5DaGFycyAmJiB0aGlzLl9saXN0Lmxlbmd0aCA+IDApIHtcblx0XHRcdHRoaXMuaW5kZXggPSAtMTtcblx0XHRcdC8vIFBvcHVsYXRlIGxpc3Qgd2l0aCBvcHRpb25zIHRoYXQgbWF0Y2hcblx0XHRcdHRoaXMudWwuaW5uZXJIVE1MID0gXCJcIjtcblxuXHRcdFx0dGhpcy5zdWdnZXN0aW9ucyA9IHRoaXMuX2xpc3Rcblx0XHRcdFx0Lm1hcChmdW5jdGlvbihpdGVtKSB7XG5cdFx0XHRcdFx0cmV0dXJuIG5ldyBTdWdnZXN0aW9uKG1lLmRhdGEoaXRlbSwgdmFsdWUpKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmZpbHRlcihmdW5jdGlvbihpdGVtKSB7XG5cdFx0XHRcdFx0cmV0dXJuIG1lLmZpbHRlcihpdGVtLCB2YWx1ZSk7XG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRpZiAodGhpcy5zb3J0ICE9PSBmYWxzZSkge1xuXHRcdFx0XHR0aGlzLnN1Z2dlc3Rpb25zID0gdGhpcy5zdWdnZXN0aW9ucy5zb3J0KHRoaXMuc29ydCk7XG5cdFx0XHR9XG5cblx0XHRcdHRoaXMuc3VnZ2VzdGlvbnMgPSB0aGlzLnN1Z2dlc3Rpb25zLnNsaWNlKDAsIHRoaXMubWF4SXRlbXMpO1xuXG5cdFx0XHR0aGlzLnN1Z2dlc3Rpb25zLmZvckVhY2goZnVuY3Rpb24odGV4dCkge1xuXHRcdFx0XHRcdG1lLnVsLmFwcGVuZENoaWxkKG1lLml0ZW0odGV4dCwgdmFsdWUpKTtcblx0XHRcdFx0fSk7XG5cblx0XHRcdGlmICh0aGlzLnVsLmNoaWxkcmVuLmxlbmd0aCA9PT0gMCkge1xuXHRcdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcIm5vbWF0Y2hlc1wiIH0pO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0dGhpcy5vcGVuKCk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0dGhpcy5jbG9zZSh7IHJlYXNvbjogXCJub21hdGNoZXNcIiB9KTtcblx0XHR9XG5cdH1cbn07XG5cbi8vIFN0YXRpYyBtZXRob2RzL3Byb3BlcnRpZXNcblxuXy5hbGwgPSBbXTtcblxuXy5GSUxURVJfQ09OVEFJTlMgPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0cmV0dXJuIFJlZ0V4cCgkLnJlZ0V4cEVzY2FwZShpbnB1dC50cmltKCkpLCBcImlcIikudGVzdCh0ZXh0KTtcbn07XG5cbl8uRklMVEVSX1NUQVJUU1dJVEggPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0cmV0dXJuIFJlZ0V4cChcIl5cIiArICQucmVnRXhwRXNjYXBlKGlucHV0LnRyaW0oKSksIFwiaVwiKS50ZXN0KHRleHQpO1xufTtcblxuXy5TT1JUX0JZTEVOR1RIID0gZnVuY3Rpb24gKGEsIGIpIHtcblx0aWYgKGEubGVuZ3RoICE9PSBiLmxlbmd0aCkge1xuXHRcdHJldHVybiBhLmxlbmd0aCAtIGIubGVuZ3RoO1xuXHR9XG5cblx0cmV0dXJuIGEgPCBiPyAtMSA6IDE7XG59O1xuXG5fLklURU0gPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0dmFyIGh0bWwgPSBpbnB1dC50cmltKCkgPT09IFwiXCIgPyB0ZXh0IDogdGV4dC5yZXBsYWNlKFJlZ0V4cCgkLnJlZ0V4cEVzY2FwZShpbnB1dC50cmltKCkpLCBcImdpXCIpLCBcIjxtYXJrPiQmPC9tYXJrPlwiKTtcblx0cmV0dXJuICQuY3JlYXRlKFwibGlcIiwge1xuXHRcdGlubmVySFRNTDogaHRtbCxcblx0XHRcImFyaWEtc2VsZWN0ZWRcIjogXCJmYWxzZVwiXG5cdH0pO1xufTtcblxuXy5SRVBMQUNFID0gZnVuY3Rpb24gKHRleHQpIHtcblx0dGhpcy5pbnB1dC52YWx1ZSA9IHRleHQudmFsdWU7XG59O1xuXG5fLkRBVEEgPSBmdW5jdGlvbiAoaXRlbS8qLCBpbnB1dCovKSB7IHJldHVybiBpdGVtOyB9O1xuXG4vLyBQcml2YXRlIGZ1bmN0aW9uc1xuXG5mdW5jdGlvbiBTdWdnZXN0aW9uKGRhdGEpIHtcblx0dmFyIG8gPSBBcnJheS5pc0FycmF5KGRhdGEpXG5cdCAgPyB7IGxhYmVsOiBkYXRhWzBdLCB2YWx1ZTogZGF0YVsxXSB9XG5cdCAgOiB0eXBlb2YgZGF0YSA9PT0gXCJvYmplY3RcIiAmJiBcImxhYmVsXCIgaW4gZGF0YSAmJiBcInZhbHVlXCIgaW4gZGF0YSA/IGRhdGEgOiB7IGxhYmVsOiBkYXRhLCB2YWx1ZTogZGF0YSB9O1xuXG5cdHRoaXMubGFiZWwgPSBvLmxhYmVsIHx8IG8udmFsdWU7XG5cdHRoaXMudmFsdWUgPSBvLnZhbHVlO1xufVxuT2JqZWN0LmRlZmluZVByb3BlcnR5KFN1Z2dlc3Rpb24ucHJvdG90eXBlID0gT2JqZWN0LmNyZWF0ZShTdHJpbmcucHJvdG90eXBlKSwgXCJsZW5ndGhcIiwge1xuXHRnZXQ6IGZ1bmN0aW9uKCkgeyByZXR1cm4gdGhpcy5sYWJlbC5sZW5ndGg7IH1cbn0pO1xuU3VnZ2VzdGlvbi5wcm90b3R5cGUudG9TdHJpbmcgPSBTdWdnZXN0aW9uLnByb3RvdHlwZS52YWx1ZU9mID0gZnVuY3Rpb24gKCkge1xuXHRyZXR1cm4gXCJcIiArIHRoaXMubGFiZWw7XG59O1xuXG5mdW5jdGlvbiBjb25maWd1cmUoaW5zdGFuY2UsIHByb3BlcnRpZXMsIG8pIHtcblx0Zm9yICh2YXIgaSBpbiBwcm9wZXJ0aWVzKSB7XG5cdFx0dmFyIGluaXRpYWwgPSBwcm9wZXJ0aWVzW2ldLFxuXHRcdCAgICBhdHRyVmFsdWUgPSBpbnN0YW5jZS5pbnB1dC5nZXRBdHRyaWJ1dGUoXCJkYXRhLVwiICsgaS50b0xvd2VyQ2FzZSgpKTtcblxuXHRcdGlmICh0eXBlb2YgaW5pdGlhbCA9PT0gXCJudW1iZXJcIikge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBwYXJzZUludChhdHRyVmFsdWUpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpbml0aWFsID09PSBmYWxzZSkgeyAvLyBCb29sZWFuIG9wdGlvbnMgbXVzdCBiZSBmYWxzZSBieSBkZWZhdWx0IGFueXdheVxuXHRcdFx0aW5zdGFuY2VbaV0gPSBhdHRyVmFsdWUgIT09IG51bGw7XG5cdFx0fVxuXHRcdGVsc2UgaWYgKGluaXRpYWwgaW5zdGFuY2VvZiBGdW5jdGlvbikge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBudWxsO1xuXHRcdH1cblx0XHRlbHNlIHtcblx0XHRcdGluc3RhbmNlW2ldID0gYXR0clZhbHVlO1xuXHRcdH1cblxuXHRcdGlmICghaW5zdGFuY2VbaV0gJiYgaW5zdGFuY2VbaV0gIT09IDApIHtcblx0XHRcdGluc3RhbmNlW2ldID0gKGkgaW4gbyk/IG9baV0gOiBpbml0aWFsO1xuXHRcdH1cblx0fVxufVxuXG4vLyBIZWxwZXJzXG5cbnZhciBzbGljZSA9IEFycmF5LnByb3RvdHlwZS5zbGljZTtcblxuZnVuY3Rpb24gJChleHByLCBjb24pIHtcblx0cmV0dXJuIHR5cGVvZiBleHByID09PSBcInN0cmluZ1wiPyAoY29uIHx8IGRvY3VtZW50KS5xdWVyeVNlbGVjdG9yKGV4cHIpIDogZXhwciB8fCBudWxsO1xufVxuXG5mdW5jdGlvbiAkJChleHByLCBjb24pIHtcblx0cmV0dXJuIHNsaWNlLmNhbGwoKGNvbiB8fCBkb2N1bWVudCkucXVlcnlTZWxlY3RvckFsbChleHByKSk7XG59XG5cbiQuY3JlYXRlID0gZnVuY3Rpb24odGFnLCBvKSB7XG5cdHZhciBlbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCh0YWcpO1xuXG5cdGZvciAodmFyIGkgaW4gbykge1xuXHRcdHZhciB2YWwgPSBvW2ldO1xuXG5cdFx0aWYgKGkgPT09IFwiaW5zaWRlXCIpIHtcblx0XHRcdCQodmFsKS5hcHBlbmRDaGlsZChlbGVtZW50KTtcblx0XHR9XG5cdFx0ZWxzZSBpZiAoaSA9PT0gXCJhcm91bmRcIikge1xuXHRcdFx0dmFyIHJlZiA9ICQodmFsKTtcblx0XHRcdHJlZi5wYXJlbnROb2RlLmluc2VydEJlZm9yZShlbGVtZW50LCByZWYpO1xuXHRcdFx0ZWxlbWVudC5hcHBlbmRDaGlsZChyZWYpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpIGluIGVsZW1lbnQpIHtcblx0XHRcdGVsZW1lbnRbaV0gPSB2YWw7XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0ZWxlbWVudC5zZXRBdHRyaWJ1dGUoaSwgdmFsKTtcblx0XHR9XG5cdH1cblxuXHRyZXR1cm4gZWxlbWVudDtcbn07XG5cbiQuYmluZCA9IGZ1bmN0aW9uKGVsZW1lbnQsIG8pIHtcblx0aWYgKGVsZW1lbnQpIHtcblx0XHRmb3IgKHZhciBldmVudCBpbiBvKSB7XG5cdFx0XHR2YXIgY2FsbGJhY2sgPSBvW2V2ZW50XTtcblxuXHRcdFx0ZXZlbnQuc3BsaXQoL1xccysvKS5mb3JFYWNoKGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0XHRlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoZXZlbnQsIGNhbGxiYWNrKTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxufTtcblxuJC51bmJpbmQgPSBmdW5jdGlvbihlbGVtZW50LCBvKSB7XG5cdGlmIChlbGVtZW50KSB7XG5cdFx0Zm9yICh2YXIgZXZlbnQgaW4gbykge1xuXHRcdFx0dmFyIGNhbGxiYWNrID0gb1tldmVudF07XG5cblx0XHRcdGV2ZW50LnNwbGl0KC9cXHMrLykuZm9yRWFjaChmdW5jdGlvbihldmVudCkge1xuXHRcdFx0XHRlbGVtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoZXZlbnQsIGNhbGxiYWNrKTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxufTtcblxuJC5maXJlID0gZnVuY3Rpb24odGFyZ2V0LCB0eXBlLCBwcm9wZXJ0aWVzKSB7XG5cdHZhciBldnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudChcIkhUTUxFdmVudHNcIik7XG5cblx0ZXZ0LmluaXRFdmVudCh0eXBlLCB0cnVlLCB0cnVlICk7XG5cblx0Zm9yICh2YXIgaiBpbiBwcm9wZXJ0aWVzKSB7XG5cdFx0ZXZ0W2pdID0gcHJvcGVydGllc1tqXTtcblx0fVxuXG5cdHJldHVybiB0YXJnZXQuZGlzcGF0Y2hFdmVudChldnQpO1xufTtcblxuJC5yZWdFeHBFc2NhcGUgPSBmdW5jdGlvbiAocykge1xuXHRyZXR1cm4gcy5yZXBsYWNlKC9bLVxcXFxeJCorPy4oKXxbXFxde31dL2csIFwiXFxcXCQmXCIpO1xufTtcblxuJC5zaWJsaW5nSW5kZXggPSBmdW5jdGlvbiAoZWwpIHtcblx0LyogZXNsaW50LWRpc2FibGUgbm8tY29uZC1hc3NpZ24gKi9cblx0Zm9yICh2YXIgaSA9IDA7IGVsID0gZWwucHJldmlvdXNFbGVtZW50U2libGluZzsgaSsrKTtcblx0cmV0dXJuIGk7XG59O1xuXG4vLyBJbml0aWFsaXphdGlvblxuXG5mdW5jdGlvbiBpbml0KCkge1xuXHQkJChcImlucHV0LmF3ZXNvbXBsZXRlXCIpLmZvckVhY2goZnVuY3Rpb24gKGlucHV0KSB7XG5cdFx0bmV3IF8oaW5wdXQpO1xuXHR9KTtcbn1cblxuLy8gQXJlIHdlIGluIGEgYnJvd3Nlcj8gQ2hlY2sgZm9yIERvY3VtZW50IGNvbnN0cnVjdG9yXG5pZiAodHlwZW9mIERvY3VtZW50ICE9PSBcInVuZGVmaW5lZFwiKSB7XG5cdC8vIERPTSBhbHJlYWR5IGxvYWRlZD9cblx0aWYgKGRvY3VtZW50LnJlYWR5U3RhdGUgIT09IFwibG9hZGluZ1wiKSB7XG5cdFx0aW5pdCgpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdC8vIFdhaXQgZm9yIGl0XG5cdFx0ZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcIkRPTUNvbnRlbnRMb2FkZWRcIiwgaW5pdCk7XG5cdH1cbn1cblxuXy4kID0gJDtcbl8uJCQgPSAkJDtcblxuLy8gTWFrZSBzdXJlIHRvIGV4cG9ydCBBd2Vzb21wbGV0ZSBvbiBzZWxmIHdoZW4gaW4gYSBicm93c2VyXG5pZiAodHlwZW9mIHNlbGYgIT09IFwidW5kZWZpbmVkXCIpIHtcblx0c2VsZi5Bd2Vzb21wbGV0ZSA9IF87XG59XG5cbi8vIEV4cG9zZSBBd2Vzb21wbGV0ZSBhcyBhIENKUyBtb2R1bGVcbmlmICh0eXBlb2YgbW9kdWxlID09PSBcIm9iamVjdFwiICYmIG1vZHVsZS5leHBvcnRzKSB7XG5cdG1vZHVsZS5leHBvcnRzID0gXztcbn1cblxucmV0dXJuIF87XG5cbn0oKSk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9hd2Vzb21wbGV0ZS9hd2Vzb21wbGV0ZS5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvYXdlc29tcGxldGUvYXdlc29tcGxldGUuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHYyLjAuMjMgfCDCqSBkbnBfdGhlbWUgfCBNSVQtTGljZW5zZVxuKGZ1bmN0aW9uIChyb290LCBmYWN0b3J5KSB7XG4gIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICAvLyBBTUQgc3VwcG9ydDpcbiAgICBkZWZpbmUoW10sIGZhY3RvcnkpO1xuICB9IGVsc2UgaWYgKHR5cGVvZiBtb2R1bGUgPT09ICdvYmplY3QnICYmIG1vZHVsZS5leHBvcnRzKSB7XG4gICAgLy8gQ29tbW9uSlMtbGlrZTpcbiAgICBtb2R1bGUuZXhwb3J0cyA9IGZhY3RvcnkoKTtcbiAgfSBlbHNlIHtcbiAgICAvLyBCcm93c2VyIGdsb2JhbHMgKHJvb3QgaXMgd2luZG93KVxuICAgIHZhciBic24gPSBmYWN0b3J5KCk7XG4gICAgcm9vdC5Nb2RhbCA9IGJzbi5Nb2RhbDtcbiAgICByb290LkNvbGxhcHNlID0gYnNuLkNvbGxhcHNlO1xuICAgIHJvb3QuQWxlcnQgPSBic24uQWxlcnQ7XG4gIH1cbn0odGhpcywgZnVuY3Rpb24gKCkge1xuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgSW50ZXJuYWwgVXRpbGl0eSBGdW5jdGlvbnNcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFwidXNlIHN0cmljdFwiO1xuICBcbiAgLy8gZ2xvYmFsc1xuICB2YXIgZ2xvYmFsT2JqZWN0ID0gdHlwZW9mIGdsb2JhbCAhPT0gJ3VuZGVmaW5lZCcgPyBnbG9iYWwgOiB0aGlzfHx3aW5kb3csXG4gICAgRE9DID0gZG9jdW1lbnQsIEhUTUwgPSBET0MuZG9jdW1lbnRFbGVtZW50LCBib2R5ID0gJ2JvZHknLCAvLyBhbGxvdyB0aGUgbGlicmFyeSB0byBiZSB1c2VkIGluIDxoZWFkPlxuICBcbiAgICAvLyBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIEdsb2JhbCBPYmplY3RcbiAgICBCU04gPSBnbG9iYWxPYmplY3QuQlNOID0ge30sXG4gICAgc3VwcG9ydHMgPSBCU04uc3VwcG9ydHMgPSBbXSxcbiAgXG4gICAgLy8gZnVuY3Rpb24gdG9nZ2xlIGF0dHJpYnV0ZXNcbiAgICBkYXRhVG9nZ2xlICAgID0gJ2RhdGEtdG9nZ2xlJyxcbiAgICBkYXRhRGlzbWlzcyAgID0gJ2RhdGEtZGlzbWlzcycsXG4gICAgZGF0YVNweSAgICAgICA9ICdkYXRhLXNweScsXG4gICAgZGF0YVJpZGUgICAgICA9ICdkYXRhLXJpZGUnLFxuICAgIFxuICAgIC8vIGNvbXBvbmVudHNcbiAgICBzdHJpbmdBZmZpeCAgICAgPSAnQWZmaXgnLFxuICAgIHN0cmluZ0FsZXJ0ICAgICA9ICdBbGVydCcsXG4gICAgc3RyaW5nQnV0dG9uICAgID0gJ0J1dHRvbicsXG4gICAgc3RyaW5nQ2Fyb3VzZWwgID0gJ0Nhcm91c2VsJyxcbiAgICBzdHJpbmdDb2xsYXBzZSAgPSAnQ29sbGFwc2UnLFxuICAgIHN0cmluZ0Ryb3Bkb3duICA9ICdEcm9wZG93bicsXG4gICAgc3RyaW5nTW9kYWwgICAgID0gJ01vZGFsJyxcbiAgICBzdHJpbmdQb3BvdmVyICAgPSAnUG9wb3ZlcicsXG4gICAgc3RyaW5nU2Nyb2xsU3B5ID0gJ1Njcm9sbFNweScsXG4gICAgc3RyaW5nVGFiICAgICAgID0gJ1RhYicsXG4gICAgc3RyaW5nVG9vbHRpcCAgID0gJ1Rvb2x0aXAnLFxuICBcbiAgICAvLyBvcHRpb25zIERBVEEgQVBJXG4gICAgZGF0YWJhY2tkcm9wICAgICAgPSAnZGF0YS1iYWNrZHJvcCcsXG4gICAgZGF0YUtleWJvYXJkICAgICAgPSAnZGF0YS1rZXlib2FyZCcsXG4gICAgZGF0YVRhcmdldCAgICAgICAgPSAnZGF0YS10YXJnZXQnLFxuICAgIGRhdGFJbnRlcnZhbCAgICAgID0gJ2RhdGEtaW50ZXJ2YWwnLFxuICAgIGRhdGFIZWlnaHQgICAgICAgID0gJ2RhdGEtaGVpZ2h0JyxcbiAgICBkYXRhUGF1c2UgICAgICAgICA9ICdkYXRhLXBhdXNlJyxcbiAgICBkYXRhVGl0bGUgICAgICAgICA9ICdkYXRhLXRpdGxlJywgIFxuICAgIGRhdGFPcmlnaW5hbFRpdGxlID0gJ2RhdGEtb3JpZ2luYWwtdGl0bGUnLFxuICAgIGRhdGFPcmlnaW5hbFRleHQgID0gJ2RhdGEtb3JpZ2luYWwtdGV4dCcsXG4gICAgZGF0YURpc21pc3NpYmxlICAgPSAnZGF0YS1kaXNtaXNzaWJsZScsXG4gICAgZGF0YVRyaWdnZXIgICAgICAgPSAnZGF0YS10cmlnZ2VyJyxcbiAgICBkYXRhQW5pbWF0aW9uICAgICA9ICdkYXRhLWFuaW1hdGlvbicsXG4gICAgZGF0YUNvbnRhaW5lciAgICAgPSAnZGF0YS1jb250YWluZXInLFxuICAgIGRhdGFQbGFjZW1lbnQgICAgID0gJ2RhdGEtcGxhY2VtZW50JyxcbiAgICBkYXRhRGVsYXkgICAgICAgICA9ICdkYXRhLWRlbGF5JyxcbiAgICBkYXRhT2Zmc2V0VG9wICAgICA9ICdkYXRhLW9mZnNldC10b3AnLFxuICAgIGRhdGFPZmZzZXRCb3R0b20gID0gJ2RhdGEtb2Zmc2V0LWJvdHRvbScsXG4gIFxuICAgIC8vIG9wdGlvbiBrZXlzXG4gICAgYmFja2Ryb3AgPSAnYmFja2Ryb3AnLCBrZXlib2FyZCA9ICdrZXlib2FyZCcsIGRlbGF5ID0gJ2RlbGF5JyxcbiAgICBjb250ZW50ID0gJ2NvbnRlbnQnLCB0YXJnZXQgPSAndGFyZ2V0JywgXG4gICAgaW50ZXJ2YWwgPSAnaW50ZXJ2YWwnLCBwYXVzZSA9ICdwYXVzZScsIGFuaW1hdGlvbiA9ICdhbmltYXRpb24nLFxuICAgIHBsYWNlbWVudCA9ICdwbGFjZW1lbnQnLCBjb250YWluZXIgPSAnY29udGFpbmVyJywgXG4gIFxuICAgIC8vIGJveCBtb2RlbFxuICAgIG9mZnNldFRvcCAgICA9ICdvZmZzZXRUb3AnLCAgICAgIG9mZnNldEJvdHRvbSAgID0gJ29mZnNldEJvdHRvbScsXG4gICAgb2Zmc2V0TGVmdCAgID0gJ29mZnNldExlZnQnLFxuICAgIHNjcm9sbFRvcCAgICA9ICdzY3JvbGxUb3AnLCAgICAgIHNjcm9sbExlZnQgICAgID0gJ3Njcm9sbExlZnQnLFxuICAgIGNsaWVudFdpZHRoICA9ICdjbGllbnRXaWR0aCcsICAgIGNsaWVudEhlaWdodCAgID0gJ2NsaWVudEhlaWdodCcsXG4gICAgb2Zmc2V0V2lkdGggID0gJ29mZnNldFdpZHRoJywgICAgb2Zmc2V0SGVpZ2h0ICAgPSAnb2Zmc2V0SGVpZ2h0JyxcbiAgICBpbm5lcldpZHRoICAgPSAnaW5uZXJXaWR0aCcsICAgICBpbm5lckhlaWdodCAgICA9ICdpbm5lckhlaWdodCcsXG4gICAgc2Nyb2xsSGVpZ2h0ID0gJ3Njcm9sbEhlaWdodCcsICAgaGVpZ2h0ICAgICAgICAgPSAnaGVpZ2h0JyxcbiAgXG4gICAgLy8gYXJpYVxuICAgIGFyaWFFeHBhbmRlZCA9ICdhcmlhLWV4cGFuZGVkJyxcbiAgICBhcmlhSGlkZGVuICAgPSAnYXJpYS1oaWRkZW4nLFxuICBcbiAgICAvLyBldmVudCBuYW1lc1xuICAgIGNsaWNrRXZlbnQgICAgPSAnY2xpY2snLFxuICAgIGhvdmVyRXZlbnQgICAgPSAnaG92ZXInLFxuICAgIGtleWRvd25FdmVudCAgPSAna2V5ZG93bicsXG4gICAga2V5dXBFdmVudCAgICA9ICdrZXl1cCcsICBcbiAgICByZXNpemVFdmVudCAgID0gJ3Jlc2l6ZScsXG4gICAgc2Nyb2xsRXZlbnQgICA9ICdzY3JvbGwnLFxuICAgIC8vIG9yaWdpbmFsRXZlbnRzXG4gICAgc2hvd0V2ZW50ICAgICA9ICdzaG93JyxcbiAgICBzaG93bkV2ZW50ICAgID0gJ3Nob3duJyxcbiAgICBoaWRlRXZlbnQgICAgID0gJ2hpZGUnLFxuICAgIGhpZGRlbkV2ZW50ICAgPSAnaGlkZGVuJyxcbiAgICBjbG9zZUV2ZW50ICAgID0gJ2Nsb3NlJyxcbiAgICBjbG9zZWRFdmVudCAgID0gJ2Nsb3NlZCcsXG4gICAgc2xpZEV2ZW50ICAgICA9ICdzbGlkJyxcbiAgICBzbGlkZUV2ZW50ICAgID0gJ3NsaWRlJyxcbiAgICBjaGFuZ2VFdmVudCAgID0gJ2NoYW5nZScsXG4gIFxuICAgIC8vIG90aGVyXG4gICAgZ2V0QXR0cmlidXRlICAgICAgICAgICA9ICdnZXRBdHRyaWJ1dGUnLFxuICAgIHNldEF0dHJpYnV0ZSAgICAgICAgICAgPSAnc2V0QXR0cmlidXRlJyxcbiAgICBoYXNBdHRyaWJ1dGUgICAgICAgICAgID0gJ2hhc0F0dHJpYnV0ZScsXG4gICAgY3JlYXRlRWxlbWVudCAgICAgICAgICA9ICdjcmVhdGVFbGVtZW50JyxcbiAgICBhcHBlbmRDaGlsZCAgICAgICAgICAgID0gJ2FwcGVuZENoaWxkJyxcbiAgICBpbm5lckhUTUwgICAgICAgICAgICAgID0gJ2lubmVySFRNTCcsXG4gICAgZ2V0RWxlbWVudHNCeVRhZ05hbWUgICA9ICdnZXRFbGVtZW50c0J5VGFnTmFtZScsXG4gICAgcHJldmVudERlZmF1bHQgICAgICAgICA9ICdwcmV2ZW50RGVmYXVsdCcsXG4gICAgZ2V0Qm91bmRpbmdDbGllbnRSZWN0ICA9ICdnZXRCb3VuZGluZ0NsaWVudFJlY3QnLFxuICAgIHF1ZXJ5U2VsZWN0b3JBbGwgICAgICAgPSAncXVlcnlTZWxlY3RvckFsbCcsXG4gICAgZ2V0RWxlbWVudHNCeUNMQVNTTkFNRSA9ICdnZXRFbGVtZW50c0J5Q2xhc3NOYW1lJyxcbiAgICBnZXRDb21wdXRlZFN0eWxlICAgICAgID0gJ2dldENvbXB1dGVkU3R5bGUnLCAgXG4gIFxuICAgIGluZGV4T2YgICAgICA9ICdpbmRleE9mJyxcbiAgICBwYXJlbnROb2RlICAgPSAncGFyZW50Tm9kZScsXG4gICAgbGVuZ3RoICAgICAgID0gJ2xlbmd0aCcsXG4gICAgdG9Mb3dlckNhc2UgID0gJ3RvTG93ZXJDYXNlJyxcbiAgICBUcmFuc2l0aW9uICAgPSAnVHJhbnNpdGlvbicsXG4gICAgRHVyYXRpb24gICAgID0gJ0R1cmF0aW9uJywgIFxuICAgIFdlYmtpdCAgICAgICA9ICdXZWJraXQnLFxuICAgIHN0eWxlICAgICAgICA9ICdzdHlsZScsXG4gICAgcHVzaCAgICAgICAgID0gJ3B1c2gnLFxuICAgIHRhYmluZGV4ICAgICA9ICd0YWJpbmRleCcsXG4gICAgY29udGFpbnMgICAgID0gJ2NvbnRhaW5zJywgIFxuICAgIFxuICAgIGFjdGl2ZSAgICAgPSAnYWN0aXZlJyxcbiAgICBpbkNsYXNzICAgID0gJ2luJyxcbiAgICBjb2xsYXBzaW5nID0gJ2NvbGxhcHNpbmcnLFxuICAgIGRpc2FibGVkICAgPSAnZGlzYWJsZWQnLFxuICAgIGxvYWRpbmcgICAgPSAnbG9hZGluZycsXG4gICAgbGVmdCAgICAgICA9ICdsZWZ0JyxcbiAgICByaWdodCAgICAgID0gJ3JpZ2h0JyxcbiAgICB0b3AgICAgICAgID0gJ3RvcCcsXG4gICAgYm90dG9tICAgICA9ICdib3R0b20nLFxuICBcbiAgICAvLyBJRTggYnJvd3NlciBkZXRlY3RcbiAgICBpc0lFOCA9ICEoJ29wYWNpdHknIGluIEhUTUxbc3R5bGVdKSxcbiAgXG4gICAgLy8gdG9vbHRpcCAvIHBvcG92ZXJcbiAgICBtb3VzZUhvdmVyID0gKCdvbm1vdXNlbGVhdmUnIGluIERPQykgPyBbICdtb3VzZWVudGVyJywgJ21vdXNlbGVhdmUnXSA6IFsgJ21vdXNlb3ZlcicsICdtb3VzZW91dCcgXSxcbiAgICB0aXBQb3NpdGlvbnMgPSAvXFxiKHRvcHxib3R0b218bGVmdHxyaWdodCkrLyxcbiAgICBcbiAgICAvLyBtb2RhbFxuICAgIG1vZGFsT3ZlcmxheSA9IDAsXG4gICAgZml4ZWRUb3AgPSAnbmF2YmFyLWZpeGVkLXRvcCcsXG4gICAgZml4ZWRCb3R0b20gPSAnbmF2YmFyLWZpeGVkLWJvdHRvbScsICBcbiAgICBcbiAgICAvLyB0cmFuc2l0aW9uRW5kIHNpbmNlIDIuMC40XG4gICAgc3VwcG9ydFRyYW5zaXRpb25zID0gV2Via2l0K1RyYW5zaXRpb24gaW4gSFRNTFtzdHlsZV0gfHwgVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKSBpbiBIVE1MW3N0eWxlXSxcbiAgICB0cmFuc2l0aW9uRW5kRXZlbnQgPSBXZWJraXQrVHJhbnNpdGlvbiBpbiBIVE1MW3N0eWxlXSA/IFdlYmtpdFt0b0xvd2VyQ2FzZV0oKStUcmFuc2l0aW9uKydFbmQnIDogVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKSsnZW5kJyxcbiAgICB0cmFuc2l0aW9uRHVyYXRpb24gPSBXZWJraXQrRHVyYXRpb24gaW4gSFRNTFtzdHlsZV0gPyBXZWJraXRbdG9Mb3dlckNhc2VdKCkrVHJhbnNpdGlvbitEdXJhdGlvbiA6IFRyYW5zaXRpb25bdG9Mb3dlckNhc2VdKCkrRHVyYXRpb24sXG4gIFxuICAgIC8vIHNldCBuZXcgZm9jdXMgZWxlbWVudCBzaW5jZSAyLjAuM1xuICAgIHNldEZvY3VzID0gZnVuY3Rpb24oZWxlbWVudCl7XG4gICAgICBlbGVtZW50LmZvY3VzID8gZWxlbWVudC5mb2N1cygpIDogZWxlbWVudC5zZXRBY3RpdmUoKTtcbiAgICB9LFxuICBcbiAgICAvLyBjbGFzcyBtYW5pcHVsYXRpb24sIHNpbmNlIDIuMC4wIHJlcXVpcmVzIHBvbHlmaWxsLmpzXG4gICAgYWRkQ2xhc3MgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSkge1xuICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKGNsYXNzTkFNRSk7XG4gICAgfSxcbiAgICByZW1vdmVDbGFzcyA9IGZ1bmN0aW9uKGVsZW1lbnQsY2xhc3NOQU1FKSB7XG4gICAgICBlbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOQU1FKTtcbiAgICB9LFxuICAgIGhhc0NsYXNzID0gZnVuY3Rpb24oZWxlbWVudCxjbGFzc05BTUUpeyAvLyBzaW5jZSAyLjAuMFxuICAgICAgcmV0dXJuIGVsZW1lbnQuY2xhc3NMaXN0W2NvbnRhaW5zXShjbGFzc05BTUUpO1xuICAgIH0sXG4gIFxuICAgIC8vIHNlbGVjdGlvbiBtZXRob2RzXG4gICAgbm9kZUxpc3RUb0FycmF5ID0gZnVuY3Rpb24obm9kZUxpc3Qpe1xuICAgICAgdmFyIGNoaWxkSXRlbXMgPSBbXTsgZm9yICh2YXIgaSA9IDAsIG5sbCA9IG5vZGVMaXN0W2xlbmd0aF07IGk8bmxsOyBpKyspIHsgY2hpbGRJdGVtc1twdXNoXSggbm9kZUxpc3RbaV0gKSB9XG4gICAgICByZXR1cm4gY2hpbGRJdGVtcztcbiAgICB9LFxuICAgIGdldEVsZW1lbnRzQnlDbGFzc05hbWUgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSkgeyAvLyBnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIElFOCtcbiAgICAgIHZhciBzZWxlY3Rpb25NZXRob2QgPSBpc0lFOCA/IHF1ZXJ5U2VsZWN0b3JBbGwgOiBnZXRFbGVtZW50c0J5Q0xBU1NOQU1FOyAgICAgIFxuICAgICAgcmV0dXJuIG5vZGVMaXN0VG9BcnJheShlbGVtZW50W3NlbGVjdGlvbk1ldGhvZF0oIGlzSUU4ID8gJy4nICsgY2xhc3NOQU1FLnJlcGxhY2UoL1xccyg/PVthLXpdKS9nLCcuJykgOiBjbGFzc05BTUUgKSk7XG4gICAgfSxcbiAgICBxdWVyeUVsZW1lbnQgPSBmdW5jdGlvbiAoc2VsZWN0b3IsIHBhcmVudCkge1xuICAgICAgdmFyIGxvb2tVcCA9IHBhcmVudCA/IHBhcmVudCA6IERPQztcbiAgICAgIHJldHVybiB0eXBlb2Ygc2VsZWN0b3IgPT09ICdvYmplY3QnID8gc2VsZWN0b3IgOiBsb29rVXAucXVlcnlTZWxlY3RvcihzZWxlY3Rvcik7XG4gICAgfSxcbiAgICBnZXRDbG9zZXN0ID0gZnVuY3Rpb24gKGVsZW1lbnQsIHNlbGVjdG9yKSB7IC8vZWxlbWVudCBpcyB0aGUgZWxlbWVudCBhbmQgc2VsZWN0b3IgaXMgZm9yIHRoZSBjbG9zZXN0IHBhcmVudCBlbGVtZW50IHRvIGZpbmRcbiAgICAgIC8vIHNvdXJjZSBodHRwOi8vZ29tYWtldGhpbmdzLmNvbS9jbGltYmluZy11cC1hbmQtZG93bi10aGUtZG9tLXRyZWUtd2l0aC12YW5pbGxhLWphdmFzY3JpcHQvXG4gICAgICB2YXIgZmlyc3RDaGFyID0gc2VsZWN0b3IuY2hhckF0KDApLCBzZWxlY3RvclN1YnN0cmluZyA9IHNlbGVjdG9yLnN1YnN0cigxKTtcbiAgICAgIGlmICggZmlyc3RDaGFyID09PSAnLicgKSB7Ly8gSWYgc2VsZWN0b3IgaXMgYSBjbGFzc1xuICAgICAgICBmb3IgKCA7IGVsZW1lbnQgJiYgZWxlbWVudCAhPT0gRE9DOyBlbGVtZW50ID0gZWxlbWVudFtwYXJlbnROb2RlXSApIHsgLy8gR2V0IGNsb3Nlc3QgbWF0Y2hcbiAgICAgICAgICBpZiAoIHF1ZXJ5RWxlbWVudChzZWxlY3RvcixlbGVtZW50W3BhcmVudE5vZGVdKSAhPT0gbnVsbCAmJiBoYXNDbGFzcyhlbGVtZW50LHNlbGVjdG9yU3Vic3RyaW5nKSApIHsgcmV0dXJuIGVsZW1lbnQ7IH1cbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmICggZmlyc3RDaGFyID09PSAnIycgKSB7IC8vIElmIHNlbGVjdG9yIGlzIGFuIElEXG4gICAgICAgIGZvciAoIDsgZWxlbWVudCAmJiBlbGVtZW50ICE9PSBET0M7IGVsZW1lbnQgPSBlbGVtZW50W3BhcmVudE5vZGVdICkgeyAvLyBHZXQgY2xvc2VzdCBtYXRjaFxuICAgICAgICAgIGlmICggZWxlbWVudC5pZCA9PT0gc2VsZWN0b3JTdWJzdHJpbmcgKSB7IHJldHVybiBlbGVtZW50OyB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICBcbiAgICAvLyBldmVudCBhdHRhY2ggalF1ZXJ5IHN0eWxlIC8gdHJpZ2dlciAgc2luY2UgMS4yLjBcbiAgICBvbiA9IGZ1bmN0aW9uIChlbGVtZW50LCBldmVudCwgaGFuZGxlcikge1xuICAgICAgZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyLCBmYWxzZSk7XG4gICAgfSxcbiAgICBvZmYgPSBmdW5jdGlvbihlbGVtZW50LCBldmVudCwgaGFuZGxlcikge1xuICAgICAgZWxlbWVudC5yZW1vdmVFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyLCBmYWxzZSk7XG4gICAgfSxcbiAgICBvbmUgPSBmdW5jdGlvbiAoZWxlbWVudCwgZXZlbnQsIGhhbmRsZXIpIHsgLy8gb25lIHNpbmNlIDIuMC40XG4gICAgICBvbihlbGVtZW50LCBldmVudCwgZnVuY3Rpb24gaGFuZGxlcldyYXBwZXIoZSl7XG4gICAgICAgIGhhbmRsZXIoZSk7XG4gICAgICAgIG9mZihlbGVtZW50LCBldmVudCwgaGFuZGxlcldyYXBwZXIpO1xuICAgICAgfSk7XG4gICAgfSxcbiAgICBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudCA9IGZ1bmN0aW9uKGVsZW1lbnQpIHtcbiAgICAgIHZhciBkdXJhdGlvbiA9IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShlbGVtZW50KVt0cmFuc2l0aW9uRHVyYXRpb25dO1xuICAgICAgZHVyYXRpb24gPSBwYXJzZUZsb2F0KGR1cmF0aW9uKTtcbiAgICAgIGR1cmF0aW9uID0gdHlwZW9mIGR1cmF0aW9uID09PSAnbnVtYmVyJyAmJiAhaXNOYU4oZHVyYXRpb24pID8gZHVyYXRpb24gKiAxMDAwIDogMDtcbiAgICAgIHJldHVybiBkdXJhdGlvbiArIDUwOyAvLyB3ZSB0YWtlIGEgc2hvcnQgb2Zmc2V0IHRvIG1ha2Ugc3VyZSB3ZSBmaXJlIG9uIHRoZSBuZXh0IGZyYW1lIGFmdGVyIGFuaW1hdGlvblxuICAgIH0sXG4gICAgZW11bGF0ZVRyYW5zaXRpb25FbmQgPSBmdW5jdGlvbihlbGVtZW50LGhhbmRsZXIpeyAvLyBlbXVsYXRlVHJhbnNpdGlvbkVuZCBzaW5jZSAyLjAuNFxuICAgICAgdmFyIGNhbGxlZCA9IDAsIGR1cmF0aW9uID0gZ2V0VHJhbnNpdGlvbkR1cmF0aW9uRnJvbUVsZW1lbnQoZWxlbWVudCk7XG4gICAgICBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb25lKGVsZW1lbnQsIHRyYW5zaXRpb25FbmRFdmVudCwgZnVuY3Rpb24oZSl7IGhhbmRsZXIoZSk7IGNhbGxlZCA9IDE7IH0pO1xuICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHsgIWNhbGxlZCAmJiBoYW5kbGVyKCk7IH0sIGR1cmF0aW9uKTtcbiAgICB9LFxuICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50ID0gZnVuY3Rpb24gKGV2ZW50TmFtZSwgY29tcG9uZW50TmFtZSwgcmVsYXRlZCkge1xuICAgICAgdmFyIE9yaWdpbmFsQ3VzdG9tRXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQoIGV2ZW50TmFtZSArICcuYnMuJyArIGNvbXBvbmVudE5hbWUpO1xuICAgICAgT3JpZ2luYWxDdXN0b21FdmVudC5yZWxhdGVkVGFyZ2V0ID0gcmVsYXRlZDtcbiAgICAgIHRoaXMuZGlzcGF0Y2hFdmVudChPcmlnaW5hbEN1c3RvbUV2ZW50KTtcbiAgICB9LFxuICBcbiAgICAvLyB0b29sdGlwIC8gcG9wb3ZlciBzdHVmZlxuICAgIGdldFNjcm9sbCA9IGZ1bmN0aW9uKCkgeyAvLyBhbHNvIEFmZml4IGFuZCBTY3JvbGxTcHkgdXNlcyBpdFxuICAgICAgcmV0dXJuIHtcbiAgICAgICAgeSA6IGdsb2JhbE9iamVjdC5wYWdlWU9mZnNldCB8fCBIVE1MW3Njcm9sbFRvcF0sXG4gICAgICAgIHggOiBnbG9iYWxPYmplY3QucGFnZVhPZmZzZXQgfHwgSFRNTFtzY3JvbGxMZWZ0XVxuICAgICAgfVxuICAgIH0sXG4gICAgc3R5bGVUaXAgPSBmdW5jdGlvbihsaW5rLGVsZW1lbnQscG9zaXRpb24scGFyZW50KSB7IC8vIGJvdGggcG9wb3ZlcnMgYW5kIHRvb2x0aXBzICh0YXJnZXQsdG9vbHRpcC9wb3BvdmVyLHBsYWNlbWVudCxlbGVtZW50VG9BcHBlbmRUbylcbiAgICAgIHZhciBlbGVtZW50RGltZW5zaW9ucyA9IHsgdyA6IGVsZW1lbnRbb2Zmc2V0V2lkdGhdLCBoOiBlbGVtZW50W29mZnNldEhlaWdodF0gfSxcbiAgICAgICAgICB3aW5kb3dXaWR0aCA9IChIVE1MW2NsaWVudFdpZHRoXSB8fCBET0NbYm9keV1bY2xpZW50V2lkdGhdKSxcbiAgICAgICAgICB3aW5kb3dIZWlnaHQgPSAoSFRNTFtjbGllbnRIZWlnaHRdIHx8IERPQ1tib2R5XVtjbGllbnRIZWlnaHRdKSxcbiAgICAgICAgICByZWN0ID0gbGlua1tnZXRCb3VuZGluZ0NsaWVudFJlY3RdKCksIFxuICAgICAgICAgIHNjcm9sbCA9IHBhcmVudCA9PT0gRE9DW2JvZHldID8gZ2V0U2Nyb2xsKCkgOiB7IHg6IHBhcmVudFtvZmZzZXRMZWZ0XSArIHBhcmVudFtzY3JvbGxMZWZ0XSwgeTogcGFyZW50W29mZnNldFRvcF0gKyBwYXJlbnRbc2Nyb2xsVG9wXSB9LFxuICAgICAgICAgIGxpbmtEaW1lbnNpb25zID0geyB3OiByZWN0W3JpZ2h0XSAtIHJlY3RbbGVmdF0sIGg6IHJlY3RbYm90dG9tXSAtIHJlY3RbdG9wXSB9LFxuICAgICAgICAgIGFycm93ID0gcXVlcnlFbGVtZW50KCdbY2xhc3MqPVwiYXJyb3dcIl0nLGVsZW1lbnQpLFxuICAgICAgICAgIHRvcFBvc2l0aW9uLCBsZWZ0UG9zaXRpb24sIGFycm93VG9wLCBhcnJvd0xlZnQsXG4gIFxuICAgICAgICAgIGhhbGZUb3BFeGNlZWQgPSByZWN0W3RvcF0gKyBsaW5rRGltZW5zaW9ucy5oLzIgLSBlbGVtZW50RGltZW5zaW9ucy5oLzIgPCAwLFxuICAgICAgICAgIGhhbGZMZWZ0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGxpbmtEaW1lbnNpb25zLncvMiAtIGVsZW1lbnREaW1lbnNpb25zLncvMiA8IDAsXG4gICAgICAgICAgaGFsZlJpZ2h0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGVsZW1lbnREaW1lbnNpb25zLncvMiArIGxpbmtEaW1lbnNpb25zLncvMiA+PSB3aW5kb3dXaWR0aCxcbiAgICAgICAgICBoYWxmQm90dG9tRXhjZWVkID0gcmVjdFt0b3BdICsgZWxlbWVudERpbWVuc2lvbnMuaC8yICsgbGlua0RpbWVuc2lvbnMuaC8yID49IHdpbmRvd0hlaWdodCxcbiAgICAgICAgICB0b3BFeGNlZWQgPSByZWN0W3RvcF0gLSBlbGVtZW50RGltZW5zaW9ucy5oIDwgMCxcbiAgICAgICAgICBsZWZ0RXhjZWVkID0gcmVjdFtsZWZ0XSAtIGVsZW1lbnREaW1lbnNpb25zLncgPCAwLFxuICAgICAgICAgIGJvdHRvbUV4Y2VlZCA9IHJlY3RbdG9wXSArIGVsZW1lbnREaW1lbnNpb25zLmggKyBsaW5rRGltZW5zaW9ucy5oID49IHdpbmRvd0hlaWdodCxcbiAgICAgICAgICByaWdodEV4Y2VlZCA9IHJlY3RbbGVmdF0gKyBlbGVtZW50RGltZW5zaW9ucy53ICsgbGlua0RpbWVuc2lvbnMudyA+PSB3aW5kb3dXaWR0aDtcbiAgXG4gICAgICAvLyByZWNvbXB1dGUgcG9zaXRpb25cbiAgICAgIHBvc2l0aW9uID0gKHBvc2l0aW9uID09PSBsZWZ0IHx8IHBvc2l0aW9uID09PSByaWdodCkgJiYgbGVmdEV4Y2VlZCAmJiByaWdodEV4Y2VlZCA/IHRvcCA6IHBvc2l0aW9uOyAvLyBmaXJzdCwgd2hlbiBib3RoIGxlZnQgYW5kIHJpZ2h0IGxpbWl0cyBhcmUgZXhjZWVkZWQsIHdlIGZhbGwgYmFjayB0byB0b3B8Ym90dG9tXG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSB0b3AgJiYgdG9wRXhjZWVkID8gYm90dG9tIDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSBib3R0b20gJiYgYm90dG9tRXhjZWVkID8gdG9wIDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSBsZWZ0ICYmIGxlZnRFeGNlZWQgPyByaWdodCA6IHBvc2l0aW9uO1xuICAgICAgcG9zaXRpb24gPSBwb3NpdGlvbiA9PT0gcmlnaHQgJiYgcmlnaHRFeGNlZWQgPyBsZWZ0IDogcG9zaXRpb247XG4gICAgICBcbiAgICAgIC8vIGFwcGx5IHN0eWxpbmcgdG8gdG9vbHRpcCBvciBwb3BvdmVyXG4gICAgICBpZiAoIHBvc2l0aW9uID09PSBsZWZ0IHx8IHBvc2l0aW9uID09PSByaWdodCApIHsgLy8gc2Vjb25kYXJ5fHNpZGUgcG9zaXRpb25zXG4gICAgICAgIGlmICggcG9zaXRpb24gPT09IGxlZnQgKSB7IC8vIExFRlRcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSByZWN0W2xlZnRdICsgc2Nyb2xsLnggLSBlbGVtZW50RGltZW5zaW9ucy53O1xuICAgICAgICB9IGVsc2UgeyAvLyBSSUdIVFxuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IHJlY3RbbGVmdF0gKyBzY3JvbGwueCArIGxpbmtEaW1lbnNpb25zLnc7XG4gICAgICAgIH1cbiAgXG4gICAgICAgIC8vIGFkanVzdCB0b3AgYW5kIGFycm93XG4gICAgICAgIGlmIChoYWxmVG9wRXhjZWVkKSB7XG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueTtcbiAgICAgICAgICBhcnJvd1RvcCA9IGxpbmtEaW1lbnNpb25zLmgvMjtcbiAgICAgICAgfSBlbHNlIGlmIChoYWxmQm90dG9tRXhjZWVkKSB7XG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueSAtIGVsZW1lbnREaW1lbnNpb25zLmggKyBsaW5rRGltZW5zaW9ucy5oO1xuICAgICAgICAgIGFycm93VG9wID0gZWxlbWVudERpbWVuc2lvbnMuaCAtIGxpbmtEaW1lbnNpb25zLmgvMjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9IHJlY3RbdG9wXSArIHNjcm9sbC55IC0gZWxlbWVudERpbWVuc2lvbnMuaC8yICsgbGlua0RpbWVuc2lvbnMuaC8yO1xuICAgICAgICB9XG4gICAgICB9IGVsc2UgaWYgKCBwb3NpdGlvbiA9PT0gdG9wIHx8IHBvc2l0aW9uID09PSBib3R0b20gKSB7IC8vIHByaW1hcnl8dmVydGljYWwgcG9zaXRpb25zXG4gICAgICAgIGlmICggcG9zaXRpb24gPT09IHRvcCkgeyAvLyBUT1BcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9ICByZWN0W3RvcF0gKyBzY3JvbGwueSAtIGVsZW1lbnREaW1lbnNpb25zLmg7XG4gICAgICAgIH0gZWxzZSB7IC8vIEJPVFRPTVxuICAgICAgICAgIHRvcFBvc2l0aW9uID0gcmVjdFt0b3BdICsgc2Nyb2xsLnkgKyBsaW5rRGltZW5zaW9ucy5oO1xuICAgICAgICB9XG4gICAgICAgIC8vIGFkanVzdCBsZWZ0IHwgcmlnaHQgYW5kIGFsc28gdGhlIGFycm93XG4gICAgICAgIGlmIChoYWxmTGVmdEV4Y2VlZCkge1xuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IDA7XG4gICAgICAgICAgYXJyb3dMZWZ0ID0gcmVjdFtsZWZ0XSArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfSBlbHNlIGlmIChoYWxmUmlnaHRFeGNlZWQpIHtcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSB3aW5kb3dXaWR0aCAtIGVsZW1lbnREaW1lbnNpb25zLncqMS4wMTtcbiAgICAgICAgICBhcnJvd0xlZnQgPSBlbGVtZW50RGltZW5zaW9ucy53IC0gKCB3aW5kb3dXaWR0aCAtIHJlY3RbbGVmdF0gKSArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSByZWN0W2xlZnRdICsgc2Nyb2xsLnggLSBlbGVtZW50RGltZW5zaW9ucy53LzIgKyBsaW5rRGltZW5zaW9ucy53LzI7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgXG4gICAgICAvLyBhcHBseSBzdHlsZSB0byB0b29sdGlwL3BvcG92ZXIgYW5kIGl0J3MgYXJyb3dcbiAgICAgIGVsZW1lbnRbc3R5bGVdW3RvcF0gPSB0b3BQb3NpdGlvbiArICdweCc7XG4gICAgICBlbGVtZW50W3N0eWxlXVtsZWZ0XSA9IGxlZnRQb3NpdGlvbiArICdweCc7XG4gIFxuICAgICAgYXJyb3dUb3AgJiYgKGFycm93W3N0eWxlXVt0b3BdID0gYXJyb3dUb3AgKyAncHgnKTtcbiAgICAgIGFycm93TGVmdCAmJiAoYXJyb3dbc3R5bGVdW2xlZnRdID0gYXJyb3dMZWZ0ICsgJ3B4Jyk7XG4gIFxuICAgICAgZWxlbWVudC5jbGFzc05hbWVbaW5kZXhPZl0ocG9zaXRpb24pID09PSAtMSAmJiAoZWxlbWVudC5jbGFzc05hbWUgPSBlbGVtZW50LmNsYXNzTmFtZS5yZXBsYWNlKHRpcFBvc2l0aW9ucyxwb3NpdGlvbikpO1xuICAgIH07XG4gIFxuICBCU04udmVyc2lvbiA9ICcyLjAuMjMnO1xuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgTW9kYWxcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBNT0RBTCBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PVxuICB2YXIgTW9kYWwgPSBmdW5jdGlvbihlbGVtZW50LCBvcHRpb25zKSB7IC8vIGVsZW1lbnQgY2FuIGJlIHRoZSBtb2RhbC90cmlnZ2VyaW5nIGJ1dHRvblxuICBcbiAgICAvLyB0aGUgbW9kYWwgKGJvdGggSmF2YVNjcmlwdCAvIERBVEEgQVBJIGluaXQpIC8gdHJpZ2dlcmluZyBidXR0b24gZWxlbWVudCAoREFUQSBBUEkpXG4gICAgZWxlbWVudCA9IHF1ZXJ5RWxlbWVudChlbGVtZW50KTtcbiAgXG4gICAgLy8gZGV0ZXJtaW5lIG1vZGFsLCB0cmlnZ2VyaW5nIGVsZW1lbnRcbiAgICB2YXIgYnRuQ2hlY2sgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oZGF0YVRhcmdldCl8fGVsZW1lbnRbZ2V0QXR0cmlidXRlXSgnaHJlZicpLFxuICAgICAgY2hlY2tNb2RhbCA9IHF1ZXJ5RWxlbWVudCggYnRuQ2hlY2sgKSxcbiAgICAgIG1vZGFsID0gaGFzQ2xhc3MoZWxlbWVudCwnbW9kYWwnKSA/IGVsZW1lbnQgOiBjaGVja01vZGFsLFxuICAgICAgb3ZlcmxheURlbGF5LFxuICBcbiAgICAgIC8vIHN0cmluZ3NcbiAgICAgIGNvbXBvbmVudCA9ICdtb2RhbCcsXG4gICAgICBzdGF0aWNTdHJpbmcgPSAnc3RhdGljJyxcbiAgICAgIHBhZGRpbmdMZWZ0ID0gJ3BhZGRpbmdMZWZ0JyxcbiAgICAgIHBhZGRpbmdSaWdodCA9ICdwYWRkaW5nUmlnaHQnLFxuICAgICAgbW9kYWxCYWNrZHJvcFN0cmluZyA9ICdtb2RhbC1iYWNrZHJvcCc7XG4gIFxuICAgIGlmICggaGFzQ2xhc3MoZWxlbWVudCwnbW9kYWwnKSApIHsgZWxlbWVudCA9IG51bGw7IH0gLy8gbW9kYWwgaXMgbm93IGluZGVwZW5kZW50IG9mIGl0J3MgdHJpZ2dlcmluZyBlbGVtZW50XG4gIFxuICAgIGlmICggIW1vZGFsICkgeyByZXR1cm47IH0gLy8gaW52YWxpZGF0ZVxuICBcbiAgICAvLyBzZXQgb3B0aW9uc1xuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICBcbiAgICB0aGlzW2tleWJvYXJkXSA9IG9wdGlvbnNba2V5Ym9hcmRdID09PSBmYWxzZSB8fCBtb2RhbFtnZXRBdHRyaWJ1dGVdKGRhdGFLZXlib2FyZCkgPT09ICdmYWxzZScgPyBmYWxzZSA6IHRydWU7XG4gICAgdGhpc1tiYWNrZHJvcF0gPSBvcHRpb25zW2JhY2tkcm9wXSA9PT0gc3RhdGljU3RyaW5nIHx8IG1vZGFsW2dldEF0dHJpYnV0ZV0oZGF0YWJhY2tkcm9wKSA9PT0gc3RhdGljU3RyaW5nID8gc3RhdGljU3RyaW5nIDogdHJ1ZTtcbiAgICB0aGlzW2JhY2tkcm9wXSA9IG9wdGlvbnNbYmFja2Ryb3BdID09PSBmYWxzZSB8fCBtb2RhbFtnZXRBdHRyaWJ1dGVdKGRhdGFiYWNrZHJvcCkgPT09ICdmYWxzZScgPyBmYWxzZSA6IHRoaXNbYmFja2Ryb3BdO1xuICAgIHRoaXNbY29udGVudF0gID0gb3B0aW9uc1tjb250ZW50XTsgLy8gSmF2YVNjcmlwdCBvbmx5XG4gIFxuICAgIC8vIGJpbmQsIGNvbnN0YW50cywgZXZlbnQgdGFyZ2V0cyBhbmQgb3RoZXIgdmFyc1xuICAgIHZhciBzZWxmID0gdGhpcywgcmVsYXRlZFRhcmdldCA9IG51bGwsXG4gICAgICBib2R5SXNPdmVyZmxvd2luZywgbW9kYWxJc092ZXJmbG93aW5nLCBzY3JvbGxiYXJXaWR0aCwgb3ZlcmxheSxcbiAgXG4gICAgICAvLyBhbHNvIGZpbmQgZml4ZWQtdG9wIC8gZml4ZWQtYm90dG9tIGl0ZW1zXG4gICAgICBmaXhlZEl0ZW1zID0gZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShIVE1MLGZpeGVkVG9wKS5jb25jYXQoZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShIVE1MLGZpeGVkQm90dG9tKSksXG4gIFxuICAgICAgLy8gcHJpdmF0ZSBtZXRob2RzXG4gICAgICBnZXRXaW5kb3dXaWR0aCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaHRtbFJlY3QgPSBIVE1MW2dldEJvdW5kaW5nQ2xpZW50UmVjdF0oKTtcbiAgICAgICAgcmV0dXJuIGdsb2JhbE9iamVjdFtpbm5lcldpZHRoXSB8fCAoaHRtbFJlY3RbcmlnaHRdIC0gTWF0aC5hYnMoaHRtbFJlY3RbbGVmdF0pKTtcbiAgICAgIH0sXG4gICAgICBzZXRTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBib2R5U3R5bGUgPSBET0NbYm9keV0uY3VycmVudFN0eWxlIHx8IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShET0NbYm9keV0pLFxuICAgICAgICAgICAgYm9keVBhZCA9IHBhcnNlSW50KChib2R5U3R5bGVbcGFkZGluZ1JpZ2h0XSksIDEwKSwgaXRlbVBhZDtcbiAgICAgICAgaWYgKGJvZHlJc092ZXJmbG93aW5nKSB7XG4gICAgICAgICAgRE9DW2JvZHldW3N0eWxlXVtwYWRkaW5nUmlnaHRdID0gKGJvZHlQYWQgKyBzY3JvbGxiYXJXaWR0aCkgKyAncHgnO1xuICAgICAgICAgIGlmIChmaXhlZEl0ZW1zW2xlbmd0aF0pe1xuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBmaXhlZEl0ZW1zW2xlbmd0aF07IGkrKykge1xuICAgICAgICAgICAgICBpdGVtUGFkID0gKGZpeGVkSXRlbXNbaV0uY3VycmVudFN0eWxlIHx8IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShmaXhlZEl0ZW1zW2ldKSlbcGFkZGluZ1JpZ2h0XTtcbiAgICAgICAgICAgICAgZml4ZWRJdGVtc1tpXVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICggcGFyc2VJbnQoaXRlbVBhZCkgKyBzY3JvbGxiYXJXaWR0aCkgKyAncHgnO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHJlc2V0U2Nyb2xsYmFyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBET0NbYm9keV1bc3R5bGVdW3BhZGRpbmdSaWdodF0gPSAnJztcbiAgICAgICAgaWYgKGZpeGVkSXRlbXNbbGVuZ3RoXSl7XG4gICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBmaXhlZEl0ZW1zW2xlbmd0aF07IGkrKykge1xuICAgICAgICAgICAgZml4ZWRJdGVtc1tpXVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIG1lYXN1cmVTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7IC8vIHRoeCB3YWxzaFxuICAgICAgICB2YXIgc2Nyb2xsRGl2ID0gRE9DW2NyZWF0ZUVsZW1lbnRdKCdkaXYnKSwgc2Nyb2xsQmFyV2lkdGg7XG4gICAgICAgIHNjcm9sbERpdi5jbGFzc05hbWUgPSBjb21wb25lbnQrJy1zY3JvbGxiYXItbWVhc3VyZSc7IC8vIHRoaXMgaXMgaGVyZSB0byBzdGF5XG4gICAgICAgIERPQ1tib2R5XVthcHBlbmRDaGlsZF0oc2Nyb2xsRGl2KTtcbiAgICAgICAgc2Nyb2xsQmFyV2lkdGggPSBzY3JvbGxEaXZbb2Zmc2V0V2lkdGhdIC0gc2Nyb2xsRGl2W2NsaWVudFdpZHRoXTtcbiAgICAgICAgRE9DW2JvZHldLnJlbW92ZUNoaWxkKHNjcm9sbERpdik7XG4gICAgICByZXR1cm4gc2Nyb2xsQmFyV2lkdGg7XG4gICAgICB9LFxuICAgICAgY2hlY2tTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGJvZHlJc092ZXJmbG93aW5nID0gRE9DW2JvZHldW2NsaWVudFdpZHRoXSA8IGdldFdpbmRvd1dpZHRoKCk7XG4gICAgICAgIG1vZGFsSXNPdmVyZmxvd2luZyA9IG1vZGFsW3Njcm9sbEhlaWdodF0gPiBIVE1MW2NsaWVudEhlaWdodF07XG4gICAgICAgIHNjcm9sbGJhcldpZHRoID0gbWVhc3VyZVNjcm9sbGJhcigpO1xuICAgICAgfSxcbiAgICAgIGFkanVzdERpYWxvZyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdMZWZ0XSA9ICFib2R5SXNPdmVyZmxvd2luZyAmJiBtb2RhbElzT3ZlcmZsb3dpbmcgPyBzY3JvbGxiYXJXaWR0aCArICdweCcgOiAnJztcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdSaWdodF0gPSBib2R5SXNPdmVyZmxvd2luZyAmJiAhbW9kYWxJc092ZXJmbG93aW5nID8gc2Nyb2xsYmFyV2lkdGggKyAncHgnIDogJyc7XG4gICAgICB9LFxuICAgICAgcmVzZXRBZGp1c3RtZW50cyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdMZWZ0XSA9ICcnO1xuICAgICAgICBtb2RhbFtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgfSxcbiAgICAgIGNyZWF0ZU92ZXJsYXkgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgbW9kYWxPdmVybGF5ID0gMTtcbiAgICAgICAgXG4gICAgICAgIHZhciBuZXdPdmVybGF5ID0gRE9DW2NyZWF0ZUVsZW1lbnRdKCdkaXYnKTtcbiAgICAgICAgb3ZlcmxheSA9IHF1ZXJ5RWxlbWVudCgnLicrbW9kYWxCYWNrZHJvcFN0cmluZyk7XG4gIFxuICAgICAgICBpZiAoIG92ZXJsYXkgPT09IG51bGwgKSB7XG4gICAgICAgICAgbmV3T3ZlcmxheVtzZXRBdHRyaWJ1dGVdKCdjbGFzcycsbW9kYWxCYWNrZHJvcFN0cmluZysnIGZhZGUnKTtcbiAgICAgICAgICBvdmVybGF5ID0gbmV3T3ZlcmxheTtcbiAgICAgICAgICBET0NbYm9keV1bYXBwZW5kQ2hpbGRdKG92ZXJsYXkpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgcmVtb3ZlT3ZlcmxheSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgICAgICAgaWYgKCBvdmVybGF5ICYmIG92ZXJsYXkgIT09IG51bGwgJiYgdHlwZW9mIG92ZXJsYXkgPT09ICdvYmplY3QnICkge1xuICAgICAgICAgIG1vZGFsT3ZlcmxheSA9IDA7XG4gICAgICAgICAgRE9DW2JvZHldLnJlbW92ZUNoaWxkKG92ZXJsYXkpOyBvdmVybGF5ID0gbnVsbDtcbiAgICAgICAgfVxuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBoaWRkZW5FdmVudCwgY29tcG9uZW50KTsgICAgICBcbiAgICAgIH0sXG4gICAgICBrZXlkb3duSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihET0MsIGtleWRvd25FdmVudCwga2V5SGFuZGxlcik7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgb2ZmKERPQywga2V5ZG93bkV2ZW50LCBrZXlIYW5kbGVyKTtcbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHJlc2l6ZUhhbmRsZXJUb2dnbGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgICAgb24oZ2xvYmFsT2JqZWN0LCByZXNpemVFdmVudCwgc2VsZi51cGRhdGUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG9mZihnbG9iYWxPYmplY3QsIHJlc2l6ZUV2ZW50LCBzZWxmLnVwZGF0ZSk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBkaXNtaXNzSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihtb2RhbCwgY2xpY2tFdmVudCwgZGlzbWlzc0hhbmRsZXIpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG9mZihtb2RhbCwgY2xpY2tFdmVudCwgZGlzbWlzc0hhbmRsZXIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgLy8gdHJpZ2dlcnNcbiAgICAgIHRyaWdnZXJTaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHNldEZvY3VzKG1vZGFsKTtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChtb2RhbCwgc2hvd25FdmVudCwgY29tcG9uZW50LCByZWxhdGVkVGFyZ2V0KTtcbiAgICAgIH0sXG4gICAgICB0cmlnZ2VySGlkZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV0uZGlzcGxheSA9ICcnO1xuICAgICAgICBlbGVtZW50ICYmIChzZXRGb2N1cyhlbGVtZW50KSk7XG4gICAgICAgIFxuICAgICAgICAoZnVuY3Rpb24oKXtcbiAgICAgICAgICBpZiAoIWdldEVsZW1lbnRzQnlDbGFzc05hbWUoRE9DLGNvbXBvbmVudCsnICcraW5DbGFzcylbMF0pIHtcbiAgICAgICAgICAgIHJlc2V0QWRqdXN0bWVudHMoKTtcbiAgICAgICAgICAgIHJlc2V0U2Nyb2xsYmFyKCk7XG4gICAgICAgICAgICByZW1vdmVDbGFzcyhET0NbYm9keV0sY29tcG9uZW50Kyctb3BlbicpO1xuICAgICAgICAgICAgb3ZlcmxheSAmJiBoYXNDbGFzcyhvdmVybGF5LCdmYWRlJykgPyAocmVtb3ZlQ2xhc3Mob3ZlcmxheSxpbkNsYXNzKSwgZW11bGF0ZVRyYW5zaXRpb25FbmQob3ZlcmxheSxyZW1vdmVPdmVybGF5KSkgXG4gICAgICAgICAgICA6IHJlbW92ZU92ZXJsYXkoKTtcbiAgXG4gICAgICAgICAgICByZXNpemVIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgICAgICBkaXNtaXNzSGFuZGxlclRvZ2dsZSgpO1xuICAgICAgICAgICAga2V5ZG93bkhhbmRsZXJUb2dnbGUoKTtcbiAgICAgICAgICB9XG4gICAgICAgIH0oKSk7XG4gICAgICB9LFxuICAgICAgLy8gaGFuZGxlcnNcbiAgICAgIGNsaWNrSGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGNsaWNrVGFyZ2V0ID0gZVt0YXJnZXRdO1xuICAgICAgICBjbGlja1RhcmdldCA9IGNsaWNrVGFyZ2V0W2hhc0F0dHJpYnV0ZV0oZGF0YVRhcmdldCkgfHwgY2xpY2tUYXJnZXRbaGFzQXR0cmlidXRlXSgnaHJlZicpID8gY2xpY2tUYXJnZXQgOiBjbGlja1RhcmdldFtwYXJlbnROb2RlXTtcbiAgICAgICAgaWYgKCBjbGlja1RhcmdldCA9PT0gZWxlbWVudCAmJiAhaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykgKSB7XG4gICAgICAgICAgbW9kYWwubW9kYWxUcmlnZ2VyID0gZWxlbWVudDtcbiAgICAgICAgICByZWxhdGVkVGFyZ2V0ID0gZWxlbWVudDtcbiAgICAgICAgICBzZWxmLnNob3coKTtcbiAgICAgICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAga2V5SGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGtleSA9IGUud2hpY2ggfHwgZS5rZXlDb2RlOyAvLyBrZXlDb2RlIGZvciBJRThcbiAgICAgICAgaWYgKHNlbGZba2V5Ym9hcmRdICYmIGtleSA9PSAyNyAmJiBoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSkge1xuICAgICAgICAgIHNlbGYuaGlkZSgpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgZGlzbWlzc0hhbmRsZXIgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgIHZhciBjbGlja1RhcmdldCA9IGVbdGFyZ2V0XTtcbiAgICAgICAgaWYgKCBoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSAmJiAoY2xpY2tUYXJnZXRbcGFyZW50Tm9kZV1bZ2V0QXR0cmlidXRlXShkYXRhRGlzbWlzcykgPT09IGNvbXBvbmVudFxuICAgICAgICAgICAgfHwgY2xpY2tUYXJnZXRbZ2V0QXR0cmlidXRlXShkYXRhRGlzbWlzcykgPT09IGNvbXBvbmVudFxuICAgICAgICAgICAgfHwgKGNsaWNrVGFyZ2V0ID09PSBtb2RhbCAmJiBzZWxmW2JhY2tkcm9wXSAhPT0gc3RhdGljU3RyaW5nKSApICkge1xuICAgICAgICAgIHNlbGYuaGlkZSgpOyByZWxhdGVkVGFyZ2V0ID0gbnVsbDtcbiAgICAgICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgICB9XG4gICAgICB9O1xuICBcbiAgICAvLyBwdWJsaWMgbWV0aG9kc1xuICAgIHRoaXMudG9nZ2xlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpICkge3RoaXMuaGlkZSgpO30gZWxzZSB7dGhpcy5zaG93KCk7fVxuICAgIH07XG4gICAgdGhpcy5zaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBzaG93RXZlbnQsIGNvbXBvbmVudCwgcmVsYXRlZFRhcmdldCk7XG4gIFxuICAgICAgLy8gd2UgZWxlZ2FudGx5IGhpZGUgYW55IG9wZW5lZCBtb2RhbFxuICAgICAgdmFyIGN1cnJlbnRPcGVuID0gZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShET0MsY29tcG9uZW50KycgaW4nKVswXTtcbiAgICAgIGN1cnJlbnRPcGVuICYmIGN1cnJlbnRPcGVuICE9PSBtb2RhbCAmJiBjdXJyZW50T3Blbi5tb2RhbFRyaWdnZXJbc3RyaW5nTW9kYWxdLmhpZGUoKTtcbiAgXG4gICAgICBpZiAoIHRoaXNbYmFja2Ryb3BdICkge1xuICAgICAgICAhbW9kYWxPdmVybGF5ICYmIGNyZWF0ZU92ZXJsYXkoKTtcbiAgICAgIH1cbiAgXG4gICAgICBpZiAoIG92ZXJsYXkgJiYgbW9kYWxPdmVybGF5ICYmICFoYXNDbGFzcyhvdmVybGF5LGluQ2xhc3MpKSB7XG4gICAgICAgIG92ZXJsYXlbb2Zmc2V0V2lkdGhdOyAvLyBmb3JjZSByZWZsb3cgdG8gZW5hYmxlIHRyYXNpdGlvblxuICAgICAgICBvdmVybGF5RGVsYXkgPSBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudChvdmVybGF5KTtcbiAgICAgICAgYWRkQ2xhc3Mob3ZlcmxheSxpbkNsYXNzKTtcbiAgICAgIH1cbiAgXG4gICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV0uZGlzcGxheSA9ICdibG9jayc7XG4gIFxuICAgICAgICBjaGVja1Njcm9sbGJhcigpO1xuICAgICAgICBzZXRTY3JvbGxiYXIoKTtcbiAgICAgICAgYWRqdXN0RGlhbG9nKCk7XG4gIFxuICAgICAgICBhZGRDbGFzcyhET0NbYm9keV0sY29tcG9uZW50Kyctb3BlbicpO1xuICAgICAgICBhZGRDbGFzcyhtb2RhbCxpbkNsYXNzKTtcbiAgICAgICAgbW9kYWxbc2V0QXR0cmlidXRlXShhcmlhSGlkZGVuLCBmYWxzZSk7XG4gICAgICAgIFxuICAgICAgICByZXNpemVIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgIGRpc21pc3NIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgIGtleWRvd25IYW5kbGVyVG9nZ2xlKCk7XG4gIFxuICAgICAgICBoYXNDbGFzcyhtb2RhbCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQobW9kYWwsIHRyaWdnZXJTaG93KSA6IHRyaWdnZXJTaG93KCk7XG4gICAgICB9LCBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb3ZlcmxheSA/IG92ZXJsYXlEZWxheSA6IDApO1xuICAgIH07XG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBoaWRlRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgICAgIG92ZXJsYXlEZWxheSA9IG92ZXJsYXkgJiYgZ2V0VHJhbnNpdGlvbkR1cmF0aW9uRnJvbUVsZW1lbnQob3ZlcmxheSk7XG4gIFxuICAgICAgcmVtb3ZlQ2xhc3MobW9kYWwsaW5DbGFzcyk7XG4gICAgICBtb2RhbFtzZXRBdHRyaWJ1dGVdKGFyaWFIaWRkZW4sIHRydWUpO1xuICBcbiAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgaGFzQ2xhc3MobW9kYWwsJ2ZhZGUnKSA/IGVtdWxhdGVUcmFuc2l0aW9uRW5kKG1vZGFsLCB0cmlnZ2VySGlkZSkgOiB0cmlnZ2VySGlkZSgpO1xuICAgICAgfSwgc3VwcG9ydFRyYW5zaXRpb25zICYmIG92ZXJsYXkgPyBvdmVybGF5RGVsYXkgOiAwKTtcbiAgICB9O1xuICAgIHRoaXMuc2V0Q29udGVudCA9IGZ1bmN0aW9uKCBjb250ZW50ICkge1xuICAgICAgcXVlcnlFbGVtZW50KCcuJytjb21wb25lbnQrJy1jb250ZW50Jyxtb2RhbClbaW5uZXJIVE1MXSA9IGNvbnRlbnQ7XG4gICAgfTtcbiAgICB0aGlzLnVwZGF0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgIGNoZWNrU2Nyb2xsYmFyKCk7XG4gICAgICAgIHNldFNjcm9sbGJhcigpO1xuICAgICAgICBhZGp1c3REaWFsb2coKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgb3ZlciBhbmQgb3ZlclxuICAgIC8vIG1vZGFsIGlzIGluZGVwZW5kZW50IG9mIGEgdHJpZ2dlcmluZyBlbGVtZW50XG4gICAgaWYgKCAhIWVsZW1lbnQgJiYgIShzdHJpbmdNb2RhbCBpbiBlbGVtZW50KSApIHtcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIGNsaWNrSGFuZGxlcik7XG4gICAgfVxuICAgIGlmICggISFzZWxmW2NvbnRlbnRdICkgeyBzZWxmLnNldENvbnRlbnQoIHNlbGZbY29udGVudF0gKTsgfVxuICAgICEhZWxlbWVudCAmJiAoZWxlbWVudFtzdHJpbmdNb2RhbF0gPSBzZWxmKTtcbiAgfTtcbiAgXG4gIC8vIERBVEEgQVBJXG4gIHN1cHBvcnRzW3B1c2hdKCBbIHN0cmluZ01vZGFsLCBNb2RhbCwgJ1snK2RhdGFUb2dnbGUrJz1cIm1vZGFsXCJdJyBdICk7XG4gIFxuICAvKiBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIDMgfCBDb2xsYXBzZVxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBDT0xMQVBTRSBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PT09PT1cbiAgdmFyIENvbGxhcHNlID0gZnVuY3Rpb24oIGVsZW1lbnQsIG9wdGlvbnMgKSB7XG4gIFxuICAgIC8vIGluaXRpYWxpemF0aW9uIGVsZW1lbnRcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBzZXQgb3B0aW9uc1xuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICBcbiAgICAvLyBldmVudCB0YXJnZXRzIGFuZCBjb25zdGFudHNcbiAgICB2YXIgYWNjb3JkaW9uID0gbnVsbCwgY29sbGFwc2UgPSBudWxsLCBzZWxmID0gdGhpcyxcbiAgICAgIGFjY29yZGlvbkRhdGEgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oJ2RhdGEtcGFyZW50JyksXG4gICAgICBhY3RpdmVDb2xsYXBzZSwgYWN0aXZlRWxlbWVudCxcbiAgXG4gICAgICAvLyBjb21wb25lbnQgc3RyaW5nc1xuICAgICAgY29tcG9uZW50ID0gJ2NvbGxhcHNlJyxcbiAgICAgIGNvbGxhcHNlZCA9ICdjb2xsYXBzZWQnLFxuICAgICAgaXNBbmltYXRpbmcgPSAnaXNBbmltYXRpbmcnLFxuICBcbiAgICAgIC8vIHByaXZhdGUgbWV0aG9kc1xuICAgICAgb3BlbkFjdGlvbiA9IGZ1bmN0aW9uKGNvbGxhcHNlRWxlbWVudCx0b2dnbGUpIHtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChjb2xsYXBzZUVsZW1lbnQsIHNob3dFdmVudCwgY29tcG9uZW50KTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IHRydWU7XG4gICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgcmVtb3ZlQ2xhc3MoY29sbGFwc2VFbGVtZW50LGNvbXBvbmVudCk7XG4gICAgICAgIGNvbGxhcHNlRWxlbWVudFtzdHlsZV1baGVpZ2h0XSA9IGNvbGxhcHNlRWxlbWVudFtzY3JvbGxIZWlnaHRdICsgJ3B4JztcbiAgICAgICAgXG4gICAgICAgIGVtdWxhdGVUcmFuc2l0aW9uRW5kKGNvbGxhcHNlRWxlbWVudCwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IGZhbHNlO1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwndHJ1ZScpO1xuICAgICAgICAgIHRvZ2dsZVtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwndHJ1ZScpOyAgICAgICAgICBcbiAgICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29sbGFwc2luZyk7XG4gICAgICAgICAgYWRkQ2xhc3MoY29sbGFwc2VFbGVtZW50LCBjb21wb25lbnQpO1xuICAgICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCwgaW5DbGFzcyk7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gJyc7XG4gICAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChjb2xsYXBzZUVsZW1lbnQsIHNob3duRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIH0pO1xuICAgICAgfSxcbiAgICAgIGNsb3NlQWN0aW9uID0gZnVuY3Rpb24oY29sbGFwc2VFbGVtZW50LHRvZ2dsZSkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgaGlkZUV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbaXNBbmltYXRpbmddID0gdHJ1ZTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gY29sbGFwc2VFbGVtZW50W3Njcm9sbEhlaWdodF0gKyAncHgnOyAvLyBzZXQgaGVpZ2h0IGZpcnN0XG4gICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb21wb25lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGluQ2xhc3MpO1xuICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGNvbGxhcHNpbmcpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbb2Zmc2V0V2lkdGhdOyAvLyBmb3JjZSByZWZsb3cgdG8gZW5hYmxlIHRyYW5zaXRpb25cbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gJzBweCc7XG4gICAgICAgIFxuICAgICAgICBlbXVsYXRlVHJhbnNpdGlvbkVuZChjb2xsYXBzZUVsZW1lbnQsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtpc0FuaW1hdGluZ10gPSBmYWxzZTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc2V0QXR0cmlidXRlXShhcmlhRXhwYW5kZWQsJ2ZhbHNlJyk7XG4gICAgICAgICAgdG9nZ2xlW3NldEF0dHJpYnV0ZV0oYXJpYUV4cGFuZGVkLCdmYWxzZScpO1xuICAgICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29tcG9uZW50KTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc3R5bGVdW2hlaWdodF0gPSAnJztcbiAgICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgaGlkZGVuRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIH0pO1xuICAgICAgfSxcbiAgICAgIGdldFRhcmdldCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaHJlZiA9IGVsZW1lbnQuaHJlZiAmJiBlbGVtZW50W2dldEF0dHJpYnV0ZV0oJ2hyZWYnKSxcbiAgICAgICAgICBwYXJlbnQgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oZGF0YVRhcmdldCksXG4gICAgICAgICAgaWQgPSBocmVmIHx8ICggcGFyZW50ICYmIHBhcmVudC5jaGFyQXQoMCkgPT09ICcjJyApICYmIHBhcmVudDtcbiAgICAgICAgcmV0dXJuIGlkICYmIHF1ZXJ5RWxlbWVudChpZCk7XG4gICAgICB9O1xuICAgIFxuICAgIC8vIHB1YmxpYyBtZXRob2RzXG4gICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbihlKSB7XG4gICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgaWYgKCFoYXNDbGFzcyhjb2xsYXBzZSxpbkNsYXNzKSkgeyBzZWxmLnNob3coKTsgfSBcbiAgICAgIGVsc2UgeyBzZWxmLmhpZGUoKTsgfVxuICAgIH07XG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGNvbGxhcHNlW2lzQW5pbWF0aW5nXSApIHJldHVybjtcbiAgICAgIGNsb3NlQWN0aW9uKGNvbGxhcHNlLGVsZW1lbnQpO1xuICAgICAgYWRkQ2xhc3MoZWxlbWVudCxjb2xsYXBzZWQpO1xuICAgIH07XG4gICAgdGhpcy5zaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGFjY29yZGlvbiApIHtcbiAgICAgICAgYWN0aXZlQ29sbGFwc2UgPSBxdWVyeUVsZW1lbnQoJy4nK2NvbXBvbmVudCsnLicraW5DbGFzcyxhY2NvcmRpb24pO1xuICAgICAgICBhY3RpdmVFbGVtZW50ID0gYWN0aXZlQ29sbGFwc2UgJiYgKHF1ZXJ5RWxlbWVudCgnWycrZGF0YVRvZ2dsZSsnPVwiJytjb21wb25lbnQrJ1wiXVsnK2RhdGFUYXJnZXQrJz1cIiMnK2FjdGl2ZUNvbGxhcHNlLmlkKydcIl0nLCBhY2NvcmRpb24pXG4gICAgICAgICAgICAgICAgICAgICAgfHwgcXVlcnlFbGVtZW50KCdbJytkYXRhVG9nZ2xlKyc9XCInK2NvbXBvbmVudCsnXCJdW2hyZWY9XCIjJythY3RpdmVDb2xsYXBzZS5pZCsnXCJdJyxhY2NvcmRpb24pICk7XG4gICAgICB9XG4gIFxuICAgICAgaWYgKCAhY29sbGFwc2VbaXNBbmltYXRpbmddIHx8IGFjdGl2ZUNvbGxhcHNlICYmICFhY3RpdmVDb2xsYXBzZVtpc0FuaW1hdGluZ10gKSB7XG4gICAgICAgIGlmICggYWN0aXZlRWxlbWVudCAmJiBhY3RpdmVDb2xsYXBzZSAhPT0gY29sbGFwc2UgKSB7XG4gICAgICAgICAgY2xvc2VBY3Rpb24oYWN0aXZlQ29sbGFwc2UsYWN0aXZlRWxlbWVudCk7XG4gICAgICAgICAgYWRkQ2xhc3MoYWN0aXZlRWxlbWVudCxjb2xsYXBzZWQpOyBcbiAgICAgICAgfVxuICAgICAgICBvcGVuQWN0aW9uKGNvbGxhcHNlLGVsZW1lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhlbGVtZW50LGNvbGxhcHNlZCk7XG4gICAgICB9XG4gICAgfTtcbiAgXG4gICAgLy8gaW5pdFxuICAgIGlmICggIShzdHJpbmdDb2xsYXBzZSBpbiBlbGVtZW50ICkgKSB7IC8vIHByZXZlbnQgYWRkaW5nIGV2ZW50IGhhbmRsZXJzIHR3aWNlXG4gICAgICBvbihlbGVtZW50LCBjbGlja0V2ZW50LCBzZWxmLnRvZ2dsZSk7XG4gICAgfVxuICAgIGNvbGxhcHNlID0gZ2V0VGFyZ2V0KCk7XG4gICAgY29sbGFwc2VbaXNBbmltYXRpbmddID0gZmFsc2U7ICAvLyB3aGVuIHRydWUgaXQgd2lsbCBwcmV2ZW50IGNsaWNrIGhhbmRsZXJzICBcbiAgICBhY2NvcmRpb24gPSBxdWVyeUVsZW1lbnQob3B0aW9ucy5wYXJlbnQpIHx8IGFjY29yZGlvbkRhdGEgJiYgZ2V0Q2xvc2VzdChlbGVtZW50LCBhY2NvcmRpb25EYXRhKTtcbiAgICBlbGVtZW50W3N0cmluZ0NvbGxhcHNlXSA9IHNlbGY7XG4gIH07XG4gIFxuICAvLyBDT0xMQVBTRSBEQVRBIEFQSVxuICAvLyA9PT09PT09PT09PT09PT09PVxuICBzdXBwb3J0c1twdXNoXSggWyBzdHJpbmdDb2xsYXBzZSwgQ29sbGFwc2UsICdbJytkYXRhVG9nZ2xlKyc9XCJjb2xsYXBzZVwiXScgXSApO1xuICBcbiAgXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IEFsZXJ0XG4gIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0qL1xuICBcbiAgLy8gQUxFUlQgREVGSU5JVElPTlxuICAvLyA9PT09PT09PT09PT09PT09XG4gIHZhciBBbGVydCA9IGZ1bmN0aW9uKCBlbGVtZW50ICkge1xuICAgIFxuICAgIC8vIGluaXRpYWxpemF0aW9uIGVsZW1lbnRcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBiaW5kLCB0YXJnZXQgYWxlcnQsIGR1cmF0aW9uIGFuZCBzdHVmZlxuICAgIHZhciBzZWxmID0gdGhpcywgY29tcG9uZW50ID0gJ2FsZXJ0JyxcbiAgICAgIGFsZXJ0ID0gZ2V0Q2xvc2VzdChlbGVtZW50LCcuJytjb21wb25lbnQpLFxuICAgICAgdHJpZ2dlckhhbmRsZXIgPSBmdW5jdGlvbigpeyBoYXNDbGFzcyhhbGVydCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQoYWxlcnQsdHJhbnNpdGlvbkVuZEhhbmRsZXIpIDogdHJhbnNpdGlvbkVuZEhhbmRsZXIoKTsgfSxcbiAgICAgIC8vIGhhbmRsZXJzXG4gICAgICBjbGlja0hhbmRsZXIgPSBmdW5jdGlvbihlKXtcbiAgICAgICAgYWxlcnQgPSBnZXRDbG9zZXN0KGVbdGFyZ2V0XSwnLicrY29tcG9uZW50KTtcbiAgICAgICAgZWxlbWVudCA9IHF1ZXJ5RWxlbWVudCgnWycrZGF0YURpc21pc3MrJz1cIicrY29tcG9uZW50KydcIl0nLGFsZXJ0KTtcbiAgICAgICAgZWxlbWVudCAmJiBhbGVydCAmJiAoZWxlbWVudCA9PT0gZVt0YXJnZXRdIHx8IGVsZW1lbnRbY29udGFpbnNdKGVbdGFyZ2V0XSkpICYmIHNlbGYuY2xvc2UoKTtcbiAgICAgIH0sXG4gICAgICB0cmFuc2l0aW9uRW5kSGFuZGxlciA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwoYWxlcnQsIGNsb3NlZEV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBvZmYoZWxlbWVudCwgY2xpY2tFdmVudCwgY2xpY2tIYW5kbGVyKTsgLy8gZGV0YWNoIGl0J3MgbGlzdGVuZXJcbiAgICAgICAgYWxlcnRbcGFyZW50Tm9kZV0ucmVtb3ZlQ2hpbGQoYWxlcnQpO1xuICAgICAgfTtcbiAgICBcbiAgICAvLyBwdWJsaWMgbWV0aG9kXG4gICAgdGhpcy5jbG9zZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKCBhbGVydCAmJiBlbGVtZW50ICYmIGhhc0NsYXNzKGFsZXJ0LGluQ2xhc3MpICkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGFsZXJ0LCBjbG9zZUV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhhbGVydCxpbkNsYXNzKTtcbiAgICAgICAgYWxlcnQgJiYgdHJpZ2dlckhhbmRsZXIoKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgaWYgKCAhKHN0cmluZ0FsZXJ0IGluIGVsZW1lbnQgKSApIHsgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgdHdpY2VcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIGNsaWNrSGFuZGxlcik7XG4gICAgfVxuICAgIGVsZW1lbnRbc3RyaW5nQWxlcnRdID0gc2VsZjtcbiAgfTtcbiAgXG4gIC8vIEFMRVJUIERBVEEgQVBJXG4gIC8vID09PT09PT09PT09PT09XG4gIHN1cHBvcnRzW3B1c2hdKFtzdHJpbmdBbGVydCwgQWxlcnQsICdbJytkYXRhRGlzbWlzcysnPVwiYWxlcnRcIl0nXSk7XG4gIFxuICBcbiAgXG4gIFxyXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IEluaXRpYWxpemUgRGF0YSBBUElcclxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXHJcbiAgdmFyIGluaXRpYWxpemVEYXRhQVBJID0gZnVuY3Rpb24oIGNvbnN0cnVjdG9yLCBjb2xsZWN0aW9uICl7XHJcbiAgICAgIGZvciAodmFyIGk9MCwgbD1jb2xsZWN0aW9uW2xlbmd0aF07IGk8bDsgaSsrKSB7XHJcbiAgICAgICAgbmV3IGNvbnN0cnVjdG9yKGNvbGxlY3Rpb25baV0pO1xyXG4gICAgICB9XHJcbiAgICB9LFxyXG4gICAgaW5pdENhbGxiYWNrID0gQlNOLmluaXRDYWxsYmFjayA9IGZ1bmN0aW9uKGxvb2tVcCl7XHJcbiAgICAgIGxvb2tVcCA9IGxvb2tVcCB8fCBET0M7XHJcbiAgICAgIGZvciAodmFyIGk9MCwgbD1zdXBwb3J0c1tsZW5ndGhdOyBpPGw7IGkrKykge1xyXG4gICAgICAgIGluaXRpYWxpemVEYXRhQVBJKCBzdXBwb3J0c1tpXVsxXSwgbG9va1VwW3F1ZXJ5U2VsZWN0b3JBbGxdIChzdXBwb3J0c1tpXVsyXSkgKTtcclxuICAgICAgfVxyXG4gICAgfTtcclxuICBcclxuICAvLyBidWxrIGluaXRpYWxpemUgYWxsIGNvbXBvbmVudHNcclxuICBET0NbYm9keV0gPyBpbml0Q2FsbGJhY2soKSA6IG9uKCBET0MsICdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24oKXsgaW5pdENhbGxiYWNrKCk7IH0gKTtcclxuICBcbiAgcmV0dXJuIHtcbiAgICBNb2RhbDogTW9kYWwsXG4gICAgQ29sbGFwc2U6IENvbGxhcHNlLFxuICAgIEFsZXJ0OiBBbGVydFxuICB9O1xufSkpO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvYm9vdHN0cmFwLm5hdGl2ZS9kaXN0L2Jvb3RzdHJhcC1uYXRpdmUuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2Jvb3RzdHJhcC5uYXRpdmUvZGlzdC9ib290c3RyYXAtbmF0aXZlLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qXG4gKiBjbGFzc0xpc3QuanM6IENyb3NzLWJyb3dzZXIgZnVsbCBlbGVtZW50LmNsYXNzTGlzdCBpbXBsZW1lbnRhdGlvbi5cbiAqIDEuMS4yMDE3MDQyN1xuICpcbiAqIEJ5IEVsaSBHcmV5LCBodHRwOi8vZWxpZ3JleS5jb21cbiAqIExpY2Vuc2U6IERlZGljYXRlZCB0byB0aGUgcHVibGljIGRvbWFpbi5cbiAqICAgU2VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbGlncmV5L2NsYXNzTGlzdC5qcy9ibG9iL21hc3Rlci9MSUNFTlNFLm1kXG4gKi9cblxuLypnbG9iYWwgc2VsZiwgZG9jdW1lbnQsIERPTUV4Y2VwdGlvbiAqL1xuXG4vKiEgQHNvdXJjZSBodHRwOi8vcHVybC5lbGlncmV5LmNvbS9naXRodWIvY2xhc3NMaXN0LmpzL2Jsb2IvbWFzdGVyL2NsYXNzTGlzdC5qcyAqL1xuXG5pZiAoXCJkb2N1bWVudFwiIGluIHdpbmRvdy5zZWxmKSB7XG5cbi8vIEZ1bGwgcG9seWZpbGwgZm9yIGJyb3dzZXJzIHdpdGggbm8gY2xhc3NMaXN0IHN1cHBvcnRcbi8vIEluY2x1ZGluZyBJRSA8IEVkZ2UgbWlzc2luZyBTVkdFbGVtZW50LmNsYXNzTGlzdFxuaWYgKCEoXCJjbGFzc0xpc3RcIiBpbiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiX1wiKSkgXG5cdHx8IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnROUyAmJiAhKFwiY2xhc3NMaXN0XCIgaW4gZG9jdW1lbnQuY3JlYXRlRWxlbWVudE5TKFwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIixcImdcIikpKSB7XG5cbihmdW5jdGlvbiAodmlldykge1xuXG5cInVzZSBzdHJpY3RcIjtcblxuaWYgKCEoJ0VsZW1lbnQnIGluIHZpZXcpKSByZXR1cm47XG5cbnZhclxuXHQgIGNsYXNzTGlzdFByb3AgPSBcImNsYXNzTGlzdFwiXG5cdCwgcHJvdG9Qcm9wID0gXCJwcm90b3R5cGVcIlxuXHQsIGVsZW1DdHJQcm90byA9IHZpZXcuRWxlbWVudFtwcm90b1Byb3BdXG5cdCwgb2JqQ3RyID0gT2JqZWN0XG5cdCwgc3RyVHJpbSA9IFN0cmluZ1twcm90b1Byb3BdLnRyaW0gfHwgZnVuY3Rpb24gKCkge1xuXHRcdHJldHVybiB0aGlzLnJlcGxhY2UoL15cXHMrfFxccyskL2csIFwiXCIpO1xuXHR9XG5cdCwgYXJySW5kZXhPZiA9IEFycmF5W3Byb3RvUHJvcF0uaW5kZXhPZiB8fCBmdW5jdGlvbiAoaXRlbSkge1xuXHRcdHZhclxuXHRcdFx0ICBpID0gMFxuXHRcdFx0LCBsZW4gPSB0aGlzLmxlbmd0aFxuXHRcdDtcblx0XHRmb3IgKDsgaSA8IGxlbjsgaSsrKSB7XG5cdFx0XHRpZiAoaSBpbiB0aGlzICYmIHRoaXNbaV0gPT09IGl0ZW0pIHtcblx0XHRcdFx0cmV0dXJuIGk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybiAtMTtcblx0fVxuXHQvLyBWZW5kb3JzOiBwbGVhc2UgYWxsb3cgY29udGVudCBjb2RlIHRvIGluc3RhbnRpYXRlIERPTUV4Y2VwdGlvbnNcblx0LCBET01FeCA9IGZ1bmN0aW9uICh0eXBlLCBtZXNzYWdlKSB7XG5cdFx0dGhpcy5uYW1lID0gdHlwZTtcblx0XHR0aGlzLmNvZGUgPSBET01FeGNlcHRpb25bdHlwZV07XG5cdFx0dGhpcy5tZXNzYWdlID0gbWVzc2FnZTtcblx0fVxuXHQsIGNoZWNrVG9rZW5BbmRHZXRJbmRleCA9IGZ1bmN0aW9uIChjbGFzc0xpc3QsIHRva2VuKSB7XG5cdFx0aWYgKHRva2VuID09PSBcIlwiKSB7XG5cdFx0XHR0aHJvdyBuZXcgRE9NRXgoXG5cdFx0XHRcdCAgXCJTWU5UQVhfRVJSXCJcblx0XHRcdFx0LCBcIkFuIGludmFsaWQgb3IgaWxsZWdhbCBzdHJpbmcgd2FzIHNwZWNpZmllZFwiXG5cdFx0XHQpO1xuXHRcdH1cblx0XHRpZiAoL1xccy8udGVzdCh0b2tlbikpIHtcblx0XHRcdHRocm93IG5ldyBET01FeChcblx0XHRcdFx0ICBcIklOVkFMSURfQ0hBUkFDVEVSX0VSUlwiXG5cdFx0XHRcdCwgXCJTdHJpbmcgY29udGFpbnMgYW4gaW52YWxpZCBjaGFyYWN0ZXJcIlxuXHRcdFx0KTtcblx0XHR9XG5cdFx0cmV0dXJuIGFyckluZGV4T2YuY2FsbChjbGFzc0xpc3QsIHRva2VuKTtcblx0fVxuXHQsIENsYXNzTGlzdCA9IGZ1bmN0aW9uIChlbGVtKSB7XG5cdFx0dmFyXG5cdFx0XHQgIHRyaW1tZWRDbGFzc2VzID0gc3RyVHJpbS5jYWxsKGVsZW0uZ2V0QXR0cmlidXRlKFwiY2xhc3NcIikgfHwgXCJcIilcblx0XHRcdCwgY2xhc3NlcyA9IHRyaW1tZWRDbGFzc2VzID8gdHJpbW1lZENsYXNzZXMuc3BsaXQoL1xccysvKSA6IFtdXG5cdFx0XHQsIGkgPSAwXG5cdFx0XHQsIGxlbiA9IGNsYXNzZXMubGVuZ3RoXG5cdFx0O1xuXHRcdGZvciAoOyBpIDwgbGVuOyBpKyspIHtcblx0XHRcdHRoaXMucHVzaChjbGFzc2VzW2ldKTtcblx0XHR9XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lID0gZnVuY3Rpb24gKCkge1xuXHRcdFx0ZWxlbS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCB0aGlzLnRvU3RyaW5nKCkpO1xuXHRcdH07XG5cdH1cblx0LCBjbGFzc0xpc3RQcm90byA9IENsYXNzTGlzdFtwcm90b1Byb3BdID0gW11cblx0LCBjbGFzc0xpc3RHZXR0ZXIgPSBmdW5jdGlvbiAoKSB7XG5cdFx0cmV0dXJuIG5ldyBDbGFzc0xpc3QodGhpcyk7XG5cdH1cbjtcbi8vIE1vc3QgRE9NRXhjZXB0aW9uIGltcGxlbWVudGF0aW9ucyBkb24ndCBhbGxvdyBjYWxsaW5nIERPTUV4Y2VwdGlvbidzIHRvU3RyaW5nKClcbi8vIG9uIG5vbi1ET01FeGNlcHRpb25zLiBFcnJvcidzIHRvU3RyaW5nKCkgaXMgc3VmZmljaWVudCBoZXJlLlxuRE9NRXhbcHJvdG9Qcm9wXSA9IEVycm9yW3Byb3RvUHJvcF07XG5jbGFzc0xpc3RQcm90by5pdGVtID0gZnVuY3Rpb24gKGkpIHtcblx0cmV0dXJuIHRoaXNbaV0gfHwgbnVsbDtcbn07XG5jbGFzc0xpc3RQcm90by5jb250YWlucyA9IGZ1bmN0aW9uICh0b2tlbikge1xuXHR0b2tlbiArPSBcIlwiO1xuXHRyZXR1cm4gY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKSAhPT0gLTE7XG59O1xuY2xhc3NMaXN0UHJvdG8uYWRkID0gZnVuY3Rpb24gKCkge1xuXHR2YXJcblx0XHQgIHRva2VucyA9IGFyZ3VtZW50c1xuXHRcdCwgaSA9IDBcblx0XHQsIGwgPSB0b2tlbnMubGVuZ3RoXG5cdFx0LCB0b2tlblxuXHRcdCwgdXBkYXRlZCA9IGZhbHNlXG5cdDtcblx0ZG8ge1xuXHRcdHRva2VuID0gdG9rZW5zW2ldICsgXCJcIjtcblx0XHRpZiAoY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKSA9PT0gLTEpIHtcblx0XHRcdHRoaXMucHVzaCh0b2tlbik7XG5cdFx0XHR1cGRhdGVkID0gdHJ1ZTtcblx0XHR9XG5cdH1cblx0d2hpbGUgKCsraSA8IGwpO1xuXG5cdGlmICh1cGRhdGVkKSB7XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lKCk7XG5cdH1cbn07XG5jbGFzc0xpc3RQcm90by5yZW1vdmUgPSBmdW5jdGlvbiAoKSB7XG5cdHZhclxuXHRcdCAgdG9rZW5zID0gYXJndW1lbnRzXG5cdFx0LCBpID0gMFxuXHRcdCwgbCA9IHRva2Vucy5sZW5ndGhcblx0XHQsIHRva2VuXG5cdFx0LCB1cGRhdGVkID0gZmFsc2Vcblx0XHQsIGluZGV4XG5cdDtcblx0ZG8ge1xuXHRcdHRva2VuID0gdG9rZW5zW2ldICsgXCJcIjtcblx0XHRpbmRleCA9IGNoZWNrVG9rZW5BbmRHZXRJbmRleCh0aGlzLCB0b2tlbik7XG5cdFx0d2hpbGUgKGluZGV4ICE9PSAtMSkge1xuXHRcdFx0dGhpcy5zcGxpY2UoaW5kZXgsIDEpO1xuXHRcdFx0dXBkYXRlZCA9IHRydWU7XG5cdFx0XHRpbmRleCA9IGNoZWNrVG9rZW5BbmRHZXRJbmRleCh0aGlzLCB0b2tlbik7XG5cdFx0fVxuXHR9XG5cdHdoaWxlICgrK2kgPCBsKTtcblxuXHRpZiAodXBkYXRlZCkge1xuXHRcdHRoaXMuX3VwZGF0ZUNsYXNzTmFtZSgpO1xuXHR9XG59O1xuY2xhc3NMaXN0UHJvdG8udG9nZ2xlID0gZnVuY3Rpb24gKHRva2VuLCBmb3JjZSkge1xuXHR0b2tlbiArPSBcIlwiO1xuXG5cdHZhclxuXHRcdCAgcmVzdWx0ID0gdGhpcy5jb250YWlucyh0b2tlbilcblx0XHQsIG1ldGhvZCA9IHJlc3VsdCA/XG5cdFx0XHRmb3JjZSAhPT0gdHJ1ZSAmJiBcInJlbW92ZVwiXG5cdFx0OlxuXHRcdFx0Zm9yY2UgIT09IGZhbHNlICYmIFwiYWRkXCJcblx0O1xuXG5cdGlmIChtZXRob2QpIHtcblx0XHR0aGlzW21ldGhvZF0odG9rZW4pO1xuXHR9XG5cblx0aWYgKGZvcmNlID09PSB0cnVlIHx8IGZvcmNlID09PSBmYWxzZSkge1xuXHRcdHJldHVybiBmb3JjZTtcblx0fSBlbHNlIHtcblx0XHRyZXR1cm4gIXJlc3VsdDtcblx0fVxufTtcbmNsYXNzTGlzdFByb3RvLnRvU3RyaW5nID0gZnVuY3Rpb24gKCkge1xuXHRyZXR1cm4gdGhpcy5qb2luKFwiIFwiKTtcbn07XG5cbmlmIChvYmpDdHIuZGVmaW5lUHJvcGVydHkpIHtcblx0dmFyIGNsYXNzTGlzdFByb3BEZXNjID0ge1xuXHRcdCAgZ2V0OiBjbGFzc0xpc3RHZXR0ZXJcblx0XHQsIGVudW1lcmFibGU6IHRydWVcblx0XHQsIGNvbmZpZ3VyYWJsZTogdHJ1ZVxuXHR9O1xuXHR0cnkge1xuXHRcdG9iakN0ci5kZWZpbmVQcm9wZXJ0eShlbGVtQ3RyUHJvdG8sIGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdFByb3BEZXNjKTtcblx0fSBjYXRjaCAoZXgpIHsgLy8gSUUgOCBkb2Vzbid0IHN1cHBvcnQgZW51bWVyYWJsZTp0cnVlXG5cdFx0Ly8gYWRkaW5nIHVuZGVmaW5lZCB0byBmaWdodCB0aGlzIGlzc3VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbGlncmV5L2NsYXNzTGlzdC5qcy9pc3N1ZXMvMzZcblx0XHQvLyBtb2Rlcm5pZSBJRTgtTVNXNyBtYWNoaW5lIGhhcyBJRTggOC4wLjYwMDEuMTg3MDIgYW5kIGlzIGFmZmVjdGVkXG5cdFx0aWYgKGV4Lm51bWJlciA9PT0gdW5kZWZpbmVkIHx8IGV4Lm51bWJlciA9PT0gLTB4N0ZGNUVDNTQpIHtcblx0XHRcdGNsYXNzTGlzdFByb3BEZXNjLmVudW1lcmFibGUgPSBmYWxzZTtcblx0XHRcdG9iakN0ci5kZWZpbmVQcm9wZXJ0eShlbGVtQ3RyUHJvdG8sIGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdFByb3BEZXNjKTtcblx0XHR9XG5cdH1cbn0gZWxzZSBpZiAob2JqQ3RyW3Byb3RvUHJvcF0uX19kZWZpbmVHZXR0ZXJfXykge1xuXHRlbGVtQ3RyUHJvdG8uX19kZWZpbmVHZXR0ZXJfXyhjbGFzc0xpc3RQcm9wLCBjbGFzc0xpc3RHZXR0ZXIpO1xufVxuXG59KHdpbmRvdy5zZWxmKSk7XG5cbn1cblxuLy8gVGhlcmUgaXMgZnVsbCBvciBwYXJ0aWFsIG5hdGl2ZSBjbGFzc0xpc3Qgc3VwcG9ydCwgc28ganVzdCBjaGVjayBpZiB3ZSBuZWVkXG4vLyB0byBub3JtYWxpemUgdGhlIGFkZC9yZW1vdmUgYW5kIHRvZ2dsZSBBUElzLlxuXG4oZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHR2YXIgdGVzdEVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiX1wiKTtcblxuXHR0ZXN0RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiYzFcIiwgXCJjMlwiKTtcblxuXHQvLyBQb2x5ZmlsbCBmb3IgSUUgMTAvMTEgYW5kIEZpcmVmb3ggPDI2LCB3aGVyZSBjbGFzc0xpc3QuYWRkIGFuZFxuXHQvLyBjbGFzc0xpc3QucmVtb3ZlIGV4aXN0IGJ1dCBzdXBwb3J0IG9ubHkgb25lIGFyZ3VtZW50IGF0IGEgdGltZS5cblx0aWYgKCF0ZXN0RWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoXCJjMlwiKSkge1xuXHRcdHZhciBjcmVhdGVNZXRob2QgPSBmdW5jdGlvbihtZXRob2QpIHtcblx0XHRcdHZhciBvcmlnaW5hbCA9IERPTVRva2VuTGlzdC5wcm90b3R5cGVbbWV0aG9kXTtcblxuXHRcdFx0RE9NVG9rZW5MaXN0LnByb3RvdHlwZVttZXRob2RdID0gZnVuY3Rpb24odG9rZW4pIHtcblx0XHRcdFx0dmFyIGksIGxlbiA9IGFyZ3VtZW50cy5sZW5ndGg7XG5cblx0XHRcdFx0Zm9yIChpID0gMDsgaSA8IGxlbjsgaSsrKSB7XG5cdFx0XHRcdFx0dG9rZW4gPSBhcmd1bWVudHNbaV07XG5cdFx0XHRcdFx0b3JpZ2luYWwuY2FsbCh0aGlzLCB0b2tlbik7XG5cdFx0XHRcdH1cblx0XHRcdH07XG5cdFx0fTtcblx0XHRjcmVhdGVNZXRob2QoJ2FkZCcpO1xuXHRcdGNyZWF0ZU1ldGhvZCgncmVtb3ZlJyk7XG5cdH1cblxuXHR0ZXN0RWxlbWVudC5jbGFzc0xpc3QudG9nZ2xlKFwiYzNcIiwgZmFsc2UpO1xuXG5cdC8vIFBvbHlmaWxsIGZvciBJRSAxMCBhbmQgRmlyZWZveCA8MjQsIHdoZXJlIGNsYXNzTGlzdC50b2dnbGUgZG9lcyBub3Rcblx0Ly8gc3VwcG9ydCB0aGUgc2Vjb25kIGFyZ3VtZW50LlxuXHRpZiAodGVzdEVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKFwiYzNcIikpIHtcblx0XHR2YXIgX3RvZ2dsZSA9IERPTVRva2VuTGlzdC5wcm90b3R5cGUudG9nZ2xlO1xuXG5cdFx0RE9NVG9rZW5MaXN0LnByb3RvdHlwZS50b2dnbGUgPSBmdW5jdGlvbih0b2tlbiwgZm9yY2UpIHtcblx0XHRcdGlmICgxIGluIGFyZ3VtZW50cyAmJiAhdGhpcy5jb250YWlucyh0b2tlbikgPT09ICFmb3JjZSkge1xuXHRcdFx0XHRyZXR1cm4gZm9yY2U7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRyZXR1cm4gX3RvZ2dsZS5jYWxsKHRoaXMsIHRva2VuKTtcblx0XHRcdH1cblx0XHR9O1xuXG5cdH1cblxuXHR0ZXN0RWxlbWVudCA9IG51bGw7XG59KCkpO1xuXG59XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qISBzbW9vdGgtc2Nyb2xsIHYxNC4yLjAgfCAoYykgMjAxOCBDaHJpcyBGZXJkaW5hbmRpIHwgTUlUIExpY2Vuc2UgfCBodHRwOi8vZ2l0aHViLmNvbS9jZmVyZGluYW5kaS9zbW9vdGgtc2Nyb2xsICovXG4hKGZ1bmN0aW9uKGUsdCl7XCJmdW5jdGlvblwiPT10eXBlb2YgZGVmaW5lJiZkZWZpbmUuYW1kP2RlZmluZShbXSwoZnVuY3Rpb24oKXtyZXR1cm4gdChlKX0pKTpcIm9iamVjdFwiPT10eXBlb2YgZXhwb3J0cz9tb2R1bGUuZXhwb3J0cz10KGUpOmUuU21vb3RoU2Nyb2xsPXQoZSl9KShcInVuZGVmaW5lZFwiIT10eXBlb2YgZ2xvYmFsP2dsb2JhbDpcInVuZGVmaW5lZFwiIT10eXBlb2Ygd2luZG93P3dpbmRvdzp0aGlzLChmdW5jdGlvbihlKXtcInVzZSBzdHJpY3RcIjt2YXIgdD17aWdub3JlOlwiW2RhdGEtc2Nyb2xsLWlnbm9yZV1cIixoZWFkZXI6bnVsbCx0b3BPbkVtcHR5SGFzaDohMCxzcGVlZDo1MDAsY2xpcDohMCxvZmZzZXQ6MCxlYXNpbmc6XCJlYXNlSW5PdXRDdWJpY1wiLGN1c3RvbUVhc2luZzpudWxsLHVwZGF0ZVVSTDohMCxwb3BzdGF0ZTohMCxlbWl0RXZlbnRzOiEwfSxuPWZ1bmN0aW9uKCl7cmV0dXJuXCJxdWVyeVNlbGVjdG9yXCJpbiBkb2N1bWVudCYmXCJhZGRFdmVudExpc3RlbmVyXCJpbiBlJiZcInJlcXVlc3RBbmltYXRpb25GcmFtZVwiaW4gZSYmXCJjbG9zZXN0XCJpbiBlLkVsZW1lbnQucHJvdG90eXBlfSxvPWZ1bmN0aW9uKCl7Zm9yKHZhciBlPXt9LHQ9MDt0PGFyZ3VtZW50cy5sZW5ndGg7dCsrKSEoZnVuY3Rpb24odCl7Zm9yKHZhciBuIGluIHQpdC5oYXNPd25Qcm9wZXJ0eShuKSYmKGVbbl09dFtuXSl9KShhcmd1bWVudHNbdF0pO3JldHVybiBlfSxyPWZ1bmN0aW9uKHQpe3JldHVybiEhKFwibWF0Y2hNZWRpYVwiaW4gZSYmZS5tYXRjaE1lZGlhKFwiKHByZWZlcnMtcmVkdWNlZC1tb3Rpb24pXCIpLm1hdGNoZXMpfSxhPWZ1bmN0aW9uKHQpe3JldHVybiBwYXJzZUludChlLmdldENvbXB1dGVkU3R5bGUodCkuaGVpZ2h0LDEwKX0saT1mdW5jdGlvbihlKXt2YXIgdDt0cnl7dD1kZWNvZGVVUklDb21wb25lbnQoZSl9Y2F0Y2gobil7dD1lfXJldHVybiB0fSxjPWZ1bmN0aW9uKGUpe1wiI1wiPT09ZS5jaGFyQXQoMCkmJihlPWUuc3Vic3RyKDEpKTtmb3IodmFyIHQsbj1TdHJpbmcoZSksbz1uLmxlbmd0aCxyPS0xLGE9XCJcIixpPW4uY2hhckNvZGVBdCgwKTsrK3I8bzspe2lmKDA9PT0odD1uLmNoYXJDb2RlQXQocikpKXRocm93IG5ldyBJbnZhbGlkQ2hhcmFjdGVyRXJyb3IoXCJJbnZhbGlkIGNoYXJhY3RlcjogdGhlIGlucHV0IGNvbnRhaW5zIFUrMDAwMC5cIik7dD49MSYmdDw9MzF8fDEyNz09dHx8MD09PXImJnQ+PTQ4JiZ0PD01N3x8MT09PXImJnQ+PTQ4JiZ0PD01NyYmNDU9PT1pP2ErPVwiXFxcXFwiK3QudG9TdHJpbmcoMTYpK1wiIFwiOmErPXQ+PTEyOHx8NDU9PT10fHw5NT09PXR8fHQ+PTQ4JiZ0PD01N3x8dD49NjUmJnQ8PTkwfHx0Pj05NyYmdDw9MTIyP24uY2hhckF0KHIpOlwiXFxcXFwiK24uY2hhckF0KHIpfXZhciBjO3RyeXtjPWRlY29kZVVSSUNvbXBvbmVudChcIiNcIithKX1jYXRjaChlKXtjPVwiI1wiK2F9cmV0dXJuIGN9LHU9ZnVuY3Rpb24oZSx0KXt2YXIgbjtyZXR1cm5cImVhc2VJblF1YWRcIj09PWUuZWFzaW5nJiYobj10KnQpLFwiZWFzZU91dFF1YWRcIj09PWUuZWFzaW5nJiYobj10KigyLXQpKSxcImVhc2VJbk91dFF1YWRcIj09PWUuZWFzaW5nJiYobj10PC41PzIqdCp0Oig0LTIqdCkqdC0xKSxcImVhc2VJbkN1YmljXCI9PT1lLmVhc2luZyYmKG49dCp0KnQpLFwiZWFzZU91dEN1YmljXCI9PT1lLmVhc2luZyYmKG49LS10KnQqdCsxKSxcImVhc2VJbk91dEN1YmljXCI9PT1lLmVhc2luZyYmKG49dDwuNT80KnQqdCp0Oih0LTEpKigyKnQtMikqKDIqdC0yKSsxKSxcImVhc2VJblF1YXJ0XCI9PT1lLmVhc2luZyYmKG49dCp0KnQqdCksXCJlYXNlT3V0UXVhcnRcIj09PWUuZWFzaW5nJiYobj0xLSAtLXQqdCp0KnQpLFwiZWFzZUluT3V0UXVhcnRcIj09PWUuZWFzaW5nJiYobj10PC41PzgqdCp0KnQqdDoxLTgqLS10KnQqdCp0KSxcImVhc2VJblF1aW50XCI9PT1lLmVhc2luZyYmKG49dCp0KnQqdCp0KSxcImVhc2VPdXRRdWludFwiPT09ZS5lYXNpbmcmJihuPTErLS10KnQqdCp0KnQpLFwiZWFzZUluT3V0UXVpbnRcIj09PWUuZWFzaW5nJiYobj10PC41PzE2KnQqdCp0KnQqdDoxKzE2Ki0tdCp0KnQqdCp0KSxlLmN1c3RvbUVhc2luZyYmKG49ZS5jdXN0b21FYXNpbmcodCkpLG58fHR9LHM9ZnVuY3Rpb24oKXtyZXR1cm4gTWF0aC5tYXgoZG9jdW1lbnQuYm9keS5zY3JvbGxIZWlnaHQsZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbEhlaWdodCxkb2N1bWVudC5ib2R5Lm9mZnNldEhlaWdodCxkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQub2Zmc2V0SGVpZ2h0LGRvY3VtZW50LmJvZHkuY2xpZW50SGVpZ2h0LGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRIZWlnaHQpfSxsPWZ1bmN0aW9uKHQsbixvLHIpe3ZhciBhPTA7aWYodC5vZmZzZXRQYXJlbnQpZG97YSs9dC5vZmZzZXRUb3AsdD10Lm9mZnNldFBhcmVudH13aGlsZSh0KTtyZXR1cm4gYT1NYXRoLm1heChhLW4tbywwKSxyJiYoYT1NYXRoLm1pbihhLHMoKS1lLmlubmVySGVpZ2h0KSksYX0sZD1mdW5jdGlvbihlKXtyZXR1cm4gZT9hKGUpK2Uub2Zmc2V0VG9wOjB9LGY9ZnVuY3Rpb24oZSx0LG4pe3R8fGhpc3RvcnkucHVzaFN0YXRlJiZuLnVwZGF0ZVVSTCYmaGlzdG9yeS5wdXNoU3RhdGUoe3Ntb290aFNjcm9sbDpKU09OLnN0cmluZ2lmeShuKSxhbmNob3I6ZS5pZH0sZG9jdW1lbnQudGl0bGUsZT09PWRvY3VtZW50LmRvY3VtZW50RWxlbWVudD9cIiN0b3BcIjpcIiNcIitlLmlkKX0sbT1mdW5jdGlvbih0LG4sbyl7MD09PXQmJmRvY3VtZW50LmJvZHkuZm9jdXMoKSxvfHwodC5mb2N1cygpLGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQhPT10JiYodC5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLFwiLTFcIiksdC5mb2N1cygpLHQuc3R5bGUub3V0bGluZT1cIm5vbmVcIiksZS5zY3JvbGxUbygwLG4pKX0saD1mdW5jdGlvbih0LG4sbyxyKXtpZihuLmVtaXRFdmVudHMmJlwiZnVuY3Rpb25cIj09dHlwZW9mIGUuQ3VzdG9tRXZlbnQpe3ZhciBhPW5ldyBDdXN0b21FdmVudCh0LHtidWJibGVzOiEwLGRldGFpbDp7YW5jaG9yOm8sdG9nZ2xlOnJ9fSk7ZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChhKX19O3JldHVybiBmdW5jdGlvbihhLHApe3ZhciBnLHYseSxTLEUsYixPLEk9e307SS5jYW5jZWxTY3JvbGw9ZnVuY3Rpb24oZSl7Y2FuY2VsQW5pbWF0aW9uRnJhbWUoTyksTz1udWxsLGV8fGgoXCJzY3JvbGxDYW5jZWxcIixnKX0sSS5hbmltYXRlU2Nyb2xsPWZ1bmN0aW9uKG4scixhKXt2YXIgaT1vKGd8fHQsYXx8e30pLGM9XCJbb2JqZWN0IE51bWJlcl1cIj09PU9iamVjdC5wcm90b3R5cGUudG9TdHJpbmcuY2FsbChuKSxwPWN8fCFuLnRhZ05hbWU/bnVsbDpuO2lmKGN8fHApe3ZhciB2PWUucGFnZVlPZmZzZXQ7aS5oZWFkZXImJiFTJiYoUz1kb2N1bWVudC5xdWVyeVNlbGVjdG9yKGkuaGVhZGVyKSksRXx8KEU9ZChTKSk7dmFyIHksYixDLHc9Yz9uOmwocCxFLHBhcnNlSW50KFwiZnVuY3Rpb25cIj09dHlwZW9mIGkub2Zmc2V0P2kub2Zmc2V0KG4scik6aS5vZmZzZXQsMTApLGkuY2xpcCksTD13LXYsQT1zKCksSD0wLHE9ZnVuY3Rpb24odCxvKXt2YXIgYT1lLnBhZ2VZT2Zmc2V0O2lmKHQ9PW98fGE9PW98fCh2PG8mJmUuaW5uZXJIZWlnaHQrYSk+PUEpcmV0dXJuIEkuY2FuY2VsU2Nyb2xsKCEwKSxtKG4sbyxjKSxoKFwic2Nyb2xsU3RvcFwiLGksbixyKSx5PW51bGwsTz1udWxsLCEwfSxRPWZ1bmN0aW9uKHQpe3l8fCh5PXQpLEgrPXQteSxiPUgvcGFyc2VJbnQoaS5zcGVlZCwxMCksYj1iPjE/MTpiLEM9ditMKnUoaSxiKSxlLnNjcm9sbFRvKDAsTWF0aC5mbG9vcihDKSkscShDLHcpfHwoTz1lLnJlcXVlc3RBbmltYXRpb25GcmFtZShRKSx5PXQpfTswPT09ZS5wYWdlWU9mZnNldCYmZS5zY3JvbGxUbygwLDApLGYobixjLGkpLGgoXCJzY3JvbGxTdGFydFwiLGksbixyKSxJLmNhbmNlbFNjcm9sbCghMCksZS5yZXF1ZXN0QW5pbWF0aW9uRnJhbWUoUSl9fTt2YXIgQz1mdW5jdGlvbih0KXtpZighcigpJiYwPT09dC5idXR0b24mJiF0Lm1ldGFLZXkmJiF0LmN0cmxLZXkmJlwiY2xvc2VzdFwiaW4gdC50YXJnZXQmJih5PXQudGFyZ2V0LmNsb3Nlc3QoYSkpJiZcImFcIj09PXkudGFnTmFtZS50b0xvd2VyQ2FzZSgpJiYhdC50YXJnZXQuY2xvc2VzdChnLmlnbm9yZSkmJnkuaG9zdG5hbWU9PT1lLmxvY2F0aW9uLmhvc3RuYW1lJiZ5LnBhdGhuYW1lPT09ZS5sb2NhdGlvbi5wYXRobmFtZSYmLyMvLnRlc3QoeS5ocmVmKSl7dmFyIG49YyhpKHkuaGFzaCkpLG89Zy50b3BPbkVtcHR5SGFzaCYmXCIjXCI9PT1uP2RvY3VtZW50LmRvY3VtZW50RWxlbWVudDpkb2N1bWVudC5xdWVyeVNlbGVjdG9yKG4pO289b3x8XCIjdG9wXCIhPT1uP286ZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LG8mJih0LnByZXZlbnREZWZhdWx0KCksSS5hbmltYXRlU2Nyb2xsKG8seSkpfX0sdz1mdW5jdGlvbihlKXtpZihoaXN0b3J5LnN0YXRlLnNtb290aFNjcm9sbCYmaGlzdG9yeS5zdGF0ZS5zbW9vdGhTY3JvbGw9PT1KU09OLnN0cmluZ2lmeShnKSYmaGlzdG9yeS5zdGF0ZS5hbmNob3Ipe3ZhciB0PWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoYyhpKGhpc3Rvcnkuc3RhdGUuYW5jaG9yKSkpO3QmJkkuYW5pbWF0ZVNjcm9sbCh0LG51bGwse3VwZGF0ZVVSTDohMX0pfX0sTD1mdW5jdGlvbihlKXtifHwoYj1zZXRUaW1lb3V0KChmdW5jdGlvbigpe2I9bnVsbCxFPWQoUyl9KSw2NikpfTtyZXR1cm4gSS5kZXN0cm95PWZ1bmN0aW9uKCl7ZyYmKGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLEMsITEpLGUucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLEwsITEpLGUucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInBvcHN0YXRlXCIsdywhMSksSS5jYW5jZWxTY3JvbGwoKSxnPW51bGwsdj1udWxsLHk9bnVsbCxTPW51bGwsRT1udWxsLGI9bnVsbCxPPW51bGwpfSxJLmluaXQ9ZnVuY3Rpb24ocil7aWYoIW4oKSl0aHJvd1wiU21vb3RoIFNjcm9sbDogVGhpcyBicm93c2VyIGRvZXMgbm90IHN1cHBvcnQgdGhlIHJlcXVpcmVkIEphdmFTY3JpcHQgbWV0aG9kcyBhbmQgYnJvd3NlciBBUElzLlwiO0kuZGVzdHJveSgpLGc9byh0LHJ8fHt9KSxTPWcuaGVhZGVyP2RvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZy5oZWFkZXIpOm51bGwsRT1kKFMpLGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLEMsITEpLFMmJmUuYWRkRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLEwsITEpLGcudXBkYXRlVVJMJiZnLnBvcHN0YXRlJiZlLmFkZEV2ZW50TGlzdGVuZXIoXCJwb3BzdGF0ZVwiLHcsITEpfSxJLmluaXQocCksSX19KSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvc21vb3RoLXNjcm9sbC9kaXN0L3Ntb290aC1zY3JvbGwubWluLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9zbW9vdGgtc2Nyb2xsL2Rpc3Qvc21vb3RoLXNjcm9sbC5taW4uanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiKGZ1bmN0aW9uIChmYWN0b3J5KSB7XG4gICAgaWYgKHR5cGVvZiBleHBvcnRzID09PSAnb2JqZWN0Jykge1xuICAgICAgICAvLyBOb2RlL0NvbW1vbkpTXG4gICAgICAgIG1vZHVsZS5leHBvcnRzID0gZmFjdG9yeSgpO1xuICAgIH0gZWxzZSBpZiAodHlwZW9mIGRlZmluZSA9PT0gJ2Z1bmN0aW9uJyAmJiBkZWZpbmUuYW1kKSB7XG4gICAgICAgIC8vIEFNRFxuICAgICAgICBkZWZpbmUoZmFjdG9yeSk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgLy8gQnJvd3NlciBnbG9iYWxzICh3aXRoIHN1cHBvcnQgZm9yIHdlYiB3b3JrZXJzKVxuICAgICAgICB2YXIgZ2xvYjtcblxuICAgICAgICB0cnkge1xuICAgICAgICAgICAgZ2xvYiA9IHdpbmRvdztcbiAgICAgICAgfSBjYXRjaCAoZSkge1xuICAgICAgICAgICAgZ2xvYiA9IHNlbGY7XG4gICAgICAgIH1cblxuICAgICAgICBnbG9iLlNwYXJrTUQ1ID0gZmFjdG9yeSgpO1xuICAgIH1cbn0oZnVuY3Rpb24gKHVuZGVmaW5lZCkge1xuXG4gICAgJ3VzZSBzdHJpY3QnO1xuXG4gICAgLypcbiAgICAgKiBGYXN0ZXN0IG1kNSBpbXBsZW1lbnRhdGlvbiBhcm91bmQgKEpLTSBtZDUpLlxuICAgICAqIENyZWRpdHM6IEpvc2VwaCBNeWVyc1xuICAgICAqXG4gICAgICogQHNlZSBodHRwOi8vd3d3Lm15ZXJzZGFpbHkub3JnL2pvc2VwaC9qYXZhc2NyaXB0L21kNS10ZXh0Lmh0bWxcbiAgICAgKiBAc2VlIGh0dHA6Ly9qc3BlcmYuY29tL21kNS1zaG9vdG91dC83XG4gICAgICovXG5cbiAgICAvKiB0aGlzIGZ1bmN0aW9uIGlzIG11Y2ggZmFzdGVyLFxuICAgICAgc28gaWYgcG9zc2libGUgd2UgdXNlIGl0LiBTb21lIElFc1xuICAgICAgYXJlIHRoZSBvbmx5IG9uZXMgSSBrbm93IG9mIHRoYXRcbiAgICAgIG5lZWQgdGhlIGlkaW90aWMgc2Vjb25kIGZ1bmN0aW9uLFxuICAgICAgZ2VuZXJhdGVkIGJ5IGFuIGlmIGNsYXVzZS4gICovXG4gICAgdmFyIGFkZDMyID0gZnVuY3Rpb24gKGEsIGIpIHtcbiAgICAgICAgcmV0dXJuIChhICsgYikgJiAweEZGRkZGRkZGO1xuICAgIH0sXG4gICAgICAgIGhleF9jaHIgPSBbJzAnLCAnMScsICcyJywgJzMnLCAnNCcsICc1JywgJzYnLCAnNycsICc4JywgJzknLCAnYScsICdiJywgJ2MnLCAnZCcsICdlJywgJ2YnXTtcblxuXG4gICAgZnVuY3Rpb24gY21uKHEsIGEsIGIsIHgsIHMsIHQpIHtcbiAgICAgICAgYSA9IGFkZDMyKGFkZDMyKGEsIHEpLCBhZGQzMih4LCB0KSk7XG4gICAgICAgIHJldHVybiBhZGQzMigoYSA8PCBzKSB8IChhID4+PiAoMzIgLSBzKSksIGIpO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIG1kNWN5Y2xlKHgsIGspIHtcbiAgICAgICAgdmFyIGEgPSB4WzBdLFxuICAgICAgICAgICAgYiA9IHhbMV0sXG4gICAgICAgICAgICBjID0geFsyXSxcbiAgICAgICAgICAgIGQgPSB4WzNdO1xuXG4gICAgICAgIGEgKz0gKGIgJiBjIHwgfmIgJiBkKSArIGtbMF0gLSA2ODA4NzY5MzYgfCAwO1xuICAgICAgICBhICA9IChhIDw8IDcgfCBhID4+PiAyNSkgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSAmIGIgfCB+YSAmIGMpICsga1sxXSAtIDM4OTU2NDU4NiB8IDA7XG4gICAgICAgIGQgID0gKGQgPDwgMTIgfCBkID4+PiAyMCkgKyBhIHwgMDtcbiAgICAgICAgYyArPSAoZCAmIGEgfCB+ZCAmIGIpICsga1syXSArIDYwNjEwNTgxOSB8IDA7XG4gICAgICAgIGMgID0gKGMgPDwgMTcgfCBjID4+PiAxNSkgKyBkIHwgMDtcbiAgICAgICAgYiArPSAoYyAmIGQgfCB+YyAmIGEpICsga1szXSAtIDEwNDQ1MjUzMzAgfCAwO1xuICAgICAgICBiICA9IChiIDw8IDIyIHwgYiA+Pj4gMTApICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGIgJiBjIHwgfmIgJiBkKSArIGtbNF0gLSAxNzY0MTg4OTcgfCAwO1xuICAgICAgICBhICA9IChhIDw8IDcgfCBhID4+PiAyNSkgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSAmIGIgfCB+YSAmIGMpICsga1s1XSArIDEyMDAwODA0MjYgfCAwO1xuICAgICAgICBkICA9IChkIDw8IDEyIHwgZCA+Pj4gMjApICsgYSB8IDA7XG4gICAgICAgIGMgKz0gKGQgJiBhIHwgfmQgJiBiKSArIGtbNl0gLSAxNDczMjMxMzQxIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNyB8IGMgPj4+IDE1KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjICYgZCB8IH5jICYgYSkgKyBrWzddIC0gNDU3MDU5ODMgfCAwO1xuICAgICAgICBiICA9IChiIDw8IDIyIHwgYiA+Pj4gMTApICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGIgJiBjIHwgfmIgJiBkKSArIGtbOF0gKyAxNzcwMDM1NDE2IHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA3IHwgYSA+Pj4gMjUpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGEgJiBiIHwgfmEgJiBjKSArIGtbOV0gLSAxOTU4NDE0NDE3IHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCAxMiB8IGQgPj4+IDIwKSArIGEgfCAwO1xuICAgICAgICBjICs9IChkICYgYSB8IH5kICYgYikgKyBrWzEwXSAtIDQyMDYzIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNyB8IGMgPj4+IDE1KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjICYgZCB8IH5jICYgYSkgKyBrWzExXSAtIDE5OTA0MDQxNjIgfCAwO1xuICAgICAgICBiICA9IChiIDw8IDIyIHwgYiA+Pj4gMTApICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGIgJiBjIHwgfmIgJiBkKSArIGtbMTJdICsgMTgwNDYwMzY4MiB8IDA7XG4gICAgICAgIGEgID0gKGEgPDwgNyB8IGEgPj4+IDI1KSArIGIgfCAwO1xuICAgICAgICBkICs9IChhICYgYiB8IH5hICYgYykgKyBrWzEzXSAtIDQwMzQxMTAxIHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCAxMiB8IGQgPj4+IDIwKSArIGEgfCAwO1xuICAgICAgICBjICs9IChkICYgYSB8IH5kICYgYikgKyBrWzE0XSAtIDE1MDIwMDIyOTAgfCAwO1xuICAgICAgICBjICA9IChjIDw8IDE3IHwgYyA+Pj4gMTUpICsgZCB8IDA7XG4gICAgICAgIGIgKz0gKGMgJiBkIHwgfmMgJiBhKSArIGtbMTVdICsgMTIzNjUzNTMyOSB8IDA7XG4gICAgICAgIGIgID0gKGIgPDwgMjIgfCBiID4+PiAxMCkgKyBjIHwgMDtcblxuICAgICAgICBhICs9IChiICYgZCB8IGMgJiB+ZCkgKyBrWzFdIC0gMTY1Nzk2NTEwIHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA1IHwgYSA+Pj4gMjcpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGEgJiBjIHwgYiAmIH5jKSArIGtbNl0gLSAxMDY5NTAxNjMyIHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCA5IHwgZCA+Pj4gMjMpICsgYSB8IDA7XG4gICAgICAgIGMgKz0gKGQgJiBiIHwgYSAmIH5iKSArIGtbMTFdICsgNjQzNzE3NzEzIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNCB8IGMgPj4+IDE4KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjICYgYSB8IGQgJiB+YSkgKyBrWzBdIC0gMzczODk3MzAyIHwgMDtcbiAgICAgICAgYiAgPSAoYiA8PCAyMCB8IGIgPj4+IDEyKSArIGMgfCAwO1xuICAgICAgICBhICs9IChiICYgZCB8IGMgJiB+ZCkgKyBrWzVdIC0gNzAxNTU4NjkxIHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA1IHwgYSA+Pj4gMjcpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGEgJiBjIHwgYiAmIH5jKSArIGtbMTBdICsgMzgwMTYwODMgfCAwO1xuICAgICAgICBkICA9IChkIDw8IDkgfCBkID4+PiAyMykgKyBhIHwgMDtcbiAgICAgICAgYyArPSAoZCAmIGIgfCBhICYgfmIpICsga1sxNV0gLSA2NjA0NzgzMzUgfCAwO1xuICAgICAgICBjICA9IChjIDw8IDE0IHwgYyA+Pj4gMTgpICsgZCB8IDA7XG4gICAgICAgIGIgKz0gKGMgJiBhIHwgZCAmIH5hKSArIGtbNF0gLSA0MDU1Mzc4NDggfCAwO1xuICAgICAgICBiICA9IChiIDw8IDIwIHwgYiA+Pj4gMTIpICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGIgJiBkIHwgYyAmIH5kKSArIGtbOV0gKyA1Njg0NDY0MzggfCAwO1xuICAgICAgICBhICA9IChhIDw8IDUgfCBhID4+PiAyNykgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSAmIGMgfCBiICYgfmMpICsga1sxNF0gLSAxMDE5ODAzNjkwIHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCA5IHwgZCA+Pj4gMjMpICsgYSB8IDA7XG4gICAgICAgIGMgKz0gKGQgJiBiIHwgYSAmIH5iKSArIGtbM10gLSAxODczNjM5NjEgfCAwO1xuICAgICAgICBjICA9IChjIDw8IDE0IHwgYyA+Pj4gMTgpICsgZCB8IDA7XG4gICAgICAgIGIgKz0gKGMgJiBhIHwgZCAmIH5hKSArIGtbOF0gKyAxMTYzNTMxNTAxIHwgMDtcbiAgICAgICAgYiAgPSAoYiA8PCAyMCB8IGIgPj4+IDEyKSArIGMgfCAwO1xuICAgICAgICBhICs9IChiICYgZCB8IGMgJiB+ZCkgKyBrWzEzXSAtIDE0NDQ2ODE0NjcgfCAwO1xuICAgICAgICBhICA9IChhIDw8IDUgfCBhID4+PiAyNykgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSAmIGMgfCBiICYgfmMpICsga1syXSAtIDUxNDAzNzg0IHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCA5IHwgZCA+Pj4gMjMpICsgYSB8IDA7XG4gICAgICAgIGMgKz0gKGQgJiBiIHwgYSAmIH5iKSArIGtbN10gKyAxNzM1MzI4NDczIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNCB8IGMgPj4+IDE4KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjICYgYSB8IGQgJiB+YSkgKyBrWzEyXSAtIDE5MjY2MDc3MzQgfCAwO1xuICAgICAgICBiICA9IChiIDw8IDIwIHwgYiA+Pj4gMTIpICsgYyB8IDA7XG5cbiAgICAgICAgYSArPSAoYiBeIGMgXiBkKSArIGtbNV0gLSAzNzg1NTggfCAwO1xuICAgICAgICBhICA9IChhIDw8IDQgfCBhID4+PiAyOCkgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSBeIGIgXiBjKSArIGtbOF0gLSAyMDIyNTc0NDYzIHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCAxMSB8IGQgPj4+IDIxKSArIGEgfCAwO1xuICAgICAgICBjICs9IChkIF4gYSBeIGIpICsga1sxMV0gKyAxODM5MDMwNTYyIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNiB8IGMgPj4+IDE2KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjIF4gZCBeIGEpICsga1sxNF0gLSAzNTMwOTU1NiB8IDA7XG4gICAgICAgIGIgID0gKGIgPDwgMjMgfCBiID4+PiA5KSArIGMgfCAwO1xuICAgICAgICBhICs9IChiIF4gYyBeIGQpICsga1sxXSAtIDE1MzA5OTIwNjAgfCAwO1xuICAgICAgICBhICA9IChhIDw8IDQgfCBhID4+PiAyOCkgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSBeIGIgXiBjKSArIGtbNF0gKyAxMjcyODkzMzUzIHwgMDtcbiAgICAgICAgZCAgPSAoZCA8PCAxMSB8IGQgPj4+IDIxKSArIGEgfCAwO1xuICAgICAgICBjICs9IChkIF4gYSBeIGIpICsga1s3XSAtIDE1NTQ5NzYzMiB8IDA7XG4gICAgICAgIGMgID0gKGMgPDwgMTYgfCBjID4+PiAxNikgKyBkIHwgMDtcbiAgICAgICAgYiArPSAoYyBeIGQgXiBhKSArIGtbMTBdIC0gMTA5NDczMDY0MCB8IDA7XG4gICAgICAgIGIgID0gKGIgPDwgMjMgfCBiID4+PiA5KSArIGMgfCAwO1xuICAgICAgICBhICs9IChiIF4gYyBeIGQpICsga1sxM10gKyA2ODEyNzkxNzQgfCAwO1xuICAgICAgICBhICA9IChhIDw8IDQgfCBhID4+PiAyOCkgKyBiIHwgMDtcbiAgICAgICAgZCArPSAoYSBeIGIgXiBjKSArIGtbMF0gLSAzNTg1MzcyMjIgfCAwO1xuICAgICAgICBkICA9IChkIDw8IDExIHwgZCA+Pj4gMjEpICsgYSB8IDA7XG4gICAgICAgIGMgKz0gKGQgXiBhIF4gYikgKyBrWzNdIC0gNzIyNTIxOTc5IHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNiB8IGMgPj4+IDE2KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjIF4gZCBeIGEpICsga1s2XSArIDc2MDI5MTg5IHwgMDtcbiAgICAgICAgYiAgPSAoYiA8PCAyMyB8IGIgPj4+IDkpICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGIgXiBjIF4gZCkgKyBrWzldIC0gNjQwMzY0NDg3IHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA0IHwgYSA+Pj4gMjgpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGEgXiBiIF4gYykgKyBrWzEyXSAtIDQyMTgxNTgzNSB8IDA7XG4gICAgICAgIGQgID0gKGQgPDwgMTEgfCBkID4+PiAyMSkgKyBhIHwgMDtcbiAgICAgICAgYyArPSAoZCBeIGEgXiBiKSArIGtbMTVdICsgNTMwNzQyNTIwIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNiB8IGMgPj4+IDE2KSArIGQgfCAwO1xuICAgICAgICBiICs9IChjIF4gZCBeIGEpICsga1syXSAtIDk5NTMzODY1MSB8IDA7XG4gICAgICAgIGIgID0gKGIgPDwgMjMgfCBiID4+PiA5KSArIGMgfCAwO1xuXG4gICAgICAgIGEgKz0gKGMgXiAoYiB8IH5kKSkgKyBrWzBdIC0gMTk4NjMwODQ0IHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA2IHwgYSA+Pj4gMjYpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGIgXiAoYSB8IH5jKSkgKyBrWzddICsgMTEyNjg5MTQxNSB8IDA7XG4gICAgICAgIGQgID0gKGQgPDwgMTAgfCBkID4+PiAyMikgKyBhIHwgMDtcbiAgICAgICAgYyArPSAoYSBeIChkIHwgfmIpKSArIGtbMTRdIC0gMTQxNjM1NDkwNSB8IDA7XG4gICAgICAgIGMgID0gKGMgPDwgMTUgfCBjID4+PiAxNykgKyBkIHwgMDtcbiAgICAgICAgYiArPSAoZCBeIChjIHwgfmEpKSArIGtbNV0gLSA1NzQzNDA1NSB8IDA7XG4gICAgICAgIGIgID0gKGIgPDwgMjEgfGIgPj4+IDExKSArIGMgfCAwO1xuICAgICAgICBhICs9IChjIF4gKGIgfCB+ZCkpICsga1sxMl0gKyAxNzAwNDg1NTcxIHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA2IHwgYSA+Pj4gMjYpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGIgXiAoYSB8IH5jKSkgKyBrWzNdIC0gMTg5NDk4NjYwNiB8IDA7XG4gICAgICAgIGQgID0gKGQgPDwgMTAgfCBkID4+PiAyMikgKyBhIHwgMDtcbiAgICAgICAgYyArPSAoYSBeIChkIHwgfmIpKSArIGtbMTBdIC0gMTA1MTUyMyB8IDA7XG4gICAgICAgIGMgID0gKGMgPDwgMTUgfCBjID4+PiAxNykgKyBkIHwgMDtcbiAgICAgICAgYiArPSAoZCBeIChjIHwgfmEpKSArIGtbMV0gLSAyMDU0OTIyNzk5IHwgMDtcbiAgICAgICAgYiAgPSAoYiA8PCAyMSB8YiA+Pj4gMTEpICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGMgXiAoYiB8IH5kKSkgKyBrWzhdICsgMTg3MzMxMzM1OSB8IDA7XG4gICAgICAgIGEgID0gKGEgPDwgNiB8IGEgPj4+IDI2KSArIGIgfCAwO1xuICAgICAgICBkICs9IChiIF4gKGEgfCB+YykpICsga1sxNV0gLSAzMDYxMTc0NCB8IDA7XG4gICAgICAgIGQgID0gKGQgPDwgMTAgfCBkID4+PiAyMikgKyBhIHwgMDtcbiAgICAgICAgYyArPSAoYSBeIChkIHwgfmIpKSArIGtbNl0gLSAxNTYwMTk4MzgwIHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNSB8IGMgPj4+IDE3KSArIGQgfCAwO1xuICAgICAgICBiICs9IChkIF4gKGMgfCB+YSkpICsga1sxM10gKyAxMzA5MTUxNjQ5IHwgMDtcbiAgICAgICAgYiAgPSAoYiA8PCAyMSB8YiA+Pj4gMTEpICsgYyB8IDA7XG4gICAgICAgIGEgKz0gKGMgXiAoYiB8IH5kKSkgKyBrWzRdIC0gMTQ1NTIzMDcwIHwgMDtcbiAgICAgICAgYSAgPSAoYSA8PCA2IHwgYSA+Pj4gMjYpICsgYiB8IDA7XG4gICAgICAgIGQgKz0gKGIgXiAoYSB8IH5jKSkgKyBrWzExXSAtIDExMjAyMTAzNzkgfCAwO1xuICAgICAgICBkICA9IChkIDw8IDEwIHwgZCA+Pj4gMjIpICsgYSB8IDA7XG4gICAgICAgIGMgKz0gKGEgXiAoZCB8IH5iKSkgKyBrWzJdICsgNzE4Nzg3MjU5IHwgMDtcbiAgICAgICAgYyAgPSAoYyA8PCAxNSB8IGMgPj4+IDE3KSArIGQgfCAwO1xuICAgICAgICBiICs9IChkIF4gKGMgfCB+YSkpICsga1s5XSAtIDM0MzQ4NTU1MSB8IDA7XG4gICAgICAgIGIgID0gKGIgPDwgMjEgfCBiID4+PiAxMSkgKyBjIHwgMDtcblxuICAgICAgICB4WzBdID0gYSArIHhbMF0gfCAwO1xuICAgICAgICB4WzFdID0gYiArIHhbMV0gfCAwO1xuICAgICAgICB4WzJdID0gYyArIHhbMl0gfCAwO1xuICAgICAgICB4WzNdID0gZCArIHhbM10gfCAwO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIG1kNWJsayhzKSB7XG4gICAgICAgIHZhciBtZDVibGtzID0gW10sXG4gICAgICAgICAgICBpOyAvKiBBbmR5IEtpbmcgc2FpZCBkbyBpdCB0aGlzIHdheS4gKi9cblxuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgNjQ7IGkgKz0gNCkge1xuICAgICAgICAgICAgbWQ1Ymxrc1tpID4+IDJdID0gcy5jaGFyQ29kZUF0KGkpICsgKHMuY2hhckNvZGVBdChpICsgMSkgPDwgOCkgKyAocy5jaGFyQ29kZUF0KGkgKyAyKSA8PCAxNikgKyAocy5jaGFyQ29kZUF0KGkgKyAzKSA8PCAyNCk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG1kNWJsa3M7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gbWQ1YmxrX2FycmF5KGEpIHtcbiAgICAgICAgdmFyIG1kNWJsa3MgPSBbXSxcbiAgICAgICAgICAgIGk7IC8qIEFuZHkgS2luZyBzYWlkIGRvIGl0IHRoaXMgd2F5LiAqL1xuXG4gICAgICAgIGZvciAoaSA9IDA7IGkgPCA2NDsgaSArPSA0KSB7XG4gICAgICAgICAgICBtZDVibGtzW2kgPj4gMl0gPSBhW2ldICsgKGFbaSArIDFdIDw8IDgpICsgKGFbaSArIDJdIDw8IDE2KSArIChhW2kgKyAzXSA8PCAyNCk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG1kNWJsa3M7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gbWQ1MShzKSB7XG4gICAgICAgIHZhciBuID0gcy5sZW5ndGgsXG4gICAgICAgICAgICBzdGF0ZSA9IFsxNzMyNTg0MTkzLCAtMjcxNzMzODc5LCAtMTczMjU4NDE5NCwgMjcxNzMzODc4XSxcbiAgICAgICAgICAgIGksXG4gICAgICAgICAgICBsZW5ndGgsXG4gICAgICAgICAgICB0YWlsLFxuICAgICAgICAgICAgdG1wLFxuICAgICAgICAgICAgbG8sXG4gICAgICAgICAgICBoaTtcblxuICAgICAgICBmb3IgKGkgPSA2NDsgaSA8PSBuOyBpICs9IDY0KSB7XG4gICAgICAgICAgICBtZDVjeWNsZShzdGF0ZSwgbWQ1YmxrKHMuc3Vic3RyaW5nKGkgLSA2NCwgaSkpKTtcbiAgICAgICAgfVxuICAgICAgICBzID0gcy5zdWJzdHJpbmcoaSAtIDY0KTtcbiAgICAgICAgbGVuZ3RoID0gcy5sZW5ndGg7XG4gICAgICAgIHRhaWwgPSBbMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMF07XG4gICAgICAgIGZvciAoaSA9IDA7IGkgPCBsZW5ndGg7IGkgKz0gMSkge1xuICAgICAgICAgICAgdGFpbFtpID4+IDJdIHw9IHMuY2hhckNvZGVBdChpKSA8PCAoKGkgJSA0KSA8PCAzKTtcbiAgICAgICAgfVxuICAgICAgICB0YWlsW2kgPj4gMl0gfD0gMHg4MCA8PCAoKGkgJSA0KSA8PCAzKTtcbiAgICAgICAgaWYgKGkgPiA1NSkge1xuICAgICAgICAgICAgbWQ1Y3ljbGUoc3RhdGUsIHRhaWwpO1xuICAgICAgICAgICAgZm9yIChpID0gMDsgaSA8IDE2OyBpICs9IDEpIHtcbiAgICAgICAgICAgICAgICB0YWlsW2ldID0gMDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIC8vIEJld2FyZSB0aGF0IHRoZSBmaW5hbCBsZW5ndGggbWlnaHQgbm90IGZpdCBpbiAzMiBiaXRzIHNvIHdlIHRha2UgY2FyZSBvZiB0aGF0XG4gICAgICAgIHRtcCA9IG4gKiA4O1xuICAgICAgICB0bXAgPSB0bXAudG9TdHJpbmcoMTYpLm1hdGNoKC8oLio/KSguezAsOH0pJC8pO1xuICAgICAgICBsbyA9IHBhcnNlSW50KHRtcFsyXSwgMTYpO1xuICAgICAgICBoaSA9IHBhcnNlSW50KHRtcFsxXSwgMTYpIHx8IDA7XG5cbiAgICAgICAgdGFpbFsxNF0gPSBsbztcbiAgICAgICAgdGFpbFsxNV0gPSBoaTtcblxuICAgICAgICBtZDVjeWNsZShzdGF0ZSwgdGFpbCk7XG4gICAgICAgIHJldHVybiBzdGF0ZTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBtZDUxX2FycmF5KGEpIHtcbiAgICAgICAgdmFyIG4gPSBhLmxlbmd0aCxcbiAgICAgICAgICAgIHN0YXRlID0gWzE3MzI1ODQxOTMsIC0yNzE3MzM4NzksIC0xNzMyNTg0MTk0LCAyNzE3MzM4NzhdLFxuICAgICAgICAgICAgaSxcbiAgICAgICAgICAgIGxlbmd0aCxcbiAgICAgICAgICAgIHRhaWwsXG4gICAgICAgICAgICB0bXAsXG4gICAgICAgICAgICBsbyxcbiAgICAgICAgICAgIGhpO1xuXG4gICAgICAgIGZvciAoaSA9IDY0OyBpIDw9IG47IGkgKz0gNjQpIHtcbiAgICAgICAgICAgIG1kNWN5Y2xlKHN0YXRlLCBtZDVibGtfYXJyYXkoYS5zdWJhcnJheShpIC0gNjQsIGkpKSk7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBOb3Qgc3VyZSBpZiBpdCBpcyBhIGJ1ZywgaG93ZXZlciBJRTEwIHdpbGwgYWx3YXlzIHByb2R1Y2UgYSBzdWIgYXJyYXkgb2YgbGVuZ3RoIDFcbiAgICAgICAgLy8gY29udGFpbmluZyB0aGUgbGFzdCBlbGVtZW50IG9mIHRoZSBwYXJlbnQgYXJyYXkgaWYgdGhlIHN1YiBhcnJheSBzcGVjaWZpZWQgc3RhcnRzXG4gICAgICAgIC8vIGJleW9uZCB0aGUgbGVuZ3RoIG9mIHRoZSBwYXJlbnQgYXJyYXkgLSB3ZWlyZC5cbiAgICAgICAgLy8gaHR0cHM6Ly9jb25uZWN0Lm1pY3Jvc29mdC5jb20vSUUvZmVlZGJhY2svZGV0YWlscy83NzE0NTIvdHlwZWQtYXJyYXktc3ViYXJyYXktaXNzdWVcbiAgICAgICAgYSA9IChpIC0gNjQpIDwgbiA/IGEuc3ViYXJyYXkoaSAtIDY0KSA6IG5ldyBVaW50OEFycmF5KDApO1xuXG4gICAgICAgIGxlbmd0aCA9IGEubGVuZ3RoO1xuICAgICAgICB0YWlsID0gWzAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDBdO1xuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgbGVuZ3RoOyBpICs9IDEpIHtcbiAgICAgICAgICAgIHRhaWxbaSA+PiAyXSB8PSBhW2ldIDw8ICgoaSAlIDQpIDw8IDMpO1xuICAgICAgICB9XG5cbiAgICAgICAgdGFpbFtpID4+IDJdIHw9IDB4ODAgPDwgKChpICUgNCkgPDwgMyk7XG4gICAgICAgIGlmIChpID4gNTUpIHtcbiAgICAgICAgICAgIG1kNWN5Y2xlKHN0YXRlLCB0YWlsKTtcbiAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCAxNjsgaSArPSAxKSB7XG4gICAgICAgICAgICAgICAgdGFpbFtpXSA9IDA7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICAvLyBCZXdhcmUgdGhhdCB0aGUgZmluYWwgbGVuZ3RoIG1pZ2h0IG5vdCBmaXQgaW4gMzIgYml0cyBzbyB3ZSB0YWtlIGNhcmUgb2YgdGhhdFxuICAgICAgICB0bXAgPSBuICogODtcbiAgICAgICAgdG1wID0gdG1wLnRvU3RyaW5nKDE2KS5tYXRjaCgvKC4qPykoLnswLDh9KSQvKTtcbiAgICAgICAgbG8gPSBwYXJzZUludCh0bXBbMl0sIDE2KTtcbiAgICAgICAgaGkgPSBwYXJzZUludCh0bXBbMV0sIDE2KSB8fCAwO1xuXG4gICAgICAgIHRhaWxbMTRdID0gbG87XG4gICAgICAgIHRhaWxbMTVdID0gaGk7XG5cbiAgICAgICAgbWQ1Y3ljbGUoc3RhdGUsIHRhaWwpO1xuXG4gICAgICAgIHJldHVybiBzdGF0ZTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiByaGV4KG4pIHtcbiAgICAgICAgdmFyIHMgPSAnJyxcbiAgICAgICAgICAgIGo7XG4gICAgICAgIGZvciAoaiA9IDA7IGogPCA0OyBqICs9IDEpIHtcbiAgICAgICAgICAgIHMgKz0gaGV4X2NoclsobiA+PiAoaiAqIDggKyA0KSkgJiAweDBGXSArIGhleF9jaHJbKG4gPj4gKGogKiA4KSkgJiAweDBGXTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gcztcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBoZXgoeCkge1xuICAgICAgICB2YXIgaTtcbiAgICAgICAgZm9yIChpID0gMDsgaSA8IHgubGVuZ3RoOyBpICs9IDEpIHtcbiAgICAgICAgICAgIHhbaV0gPSByaGV4KHhbaV0pO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB4LmpvaW4oJycpO1xuICAgIH1cblxuICAgIC8vIEluIHNvbWUgY2FzZXMgdGhlIGZhc3QgYWRkMzIgZnVuY3Rpb24gY2Fubm90IGJlIHVzZWQuLlxuICAgIGlmIChoZXgobWQ1MSgnaGVsbG8nKSkgIT09ICc1ZDQxNDAyYWJjNGIyYTc2Yjk3MTlkOTExMDE3YzU5MicpIHtcbiAgICAgICAgYWRkMzIgPSBmdW5jdGlvbiAoeCwgeSkge1xuICAgICAgICAgICAgdmFyIGxzdyA9ICh4ICYgMHhGRkZGKSArICh5ICYgMHhGRkZGKSxcbiAgICAgICAgICAgICAgICBtc3cgPSAoeCA+PiAxNikgKyAoeSA+PiAxNikgKyAobHN3ID4+IDE2KTtcbiAgICAgICAgICAgIHJldHVybiAobXN3IDw8IDE2KSB8IChsc3cgJiAweEZGRkYpO1xuICAgICAgICB9O1xuICAgIH1cblxuICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxuXG4gICAgLyoqXG4gICAgICogQXJyYXlCdWZmZXIgc2xpY2UgcG9seWZpbGwuXG4gICAgICpcbiAgICAgKiBAc2VlIGh0dHBzOi8vZ2l0aHViLmNvbS90dGF1YmVydC9ub2RlLWFycmF5YnVmZmVyLXNsaWNlXG4gICAgICovXG5cbiAgICBpZiAodHlwZW9mIEFycmF5QnVmZmVyICE9PSAndW5kZWZpbmVkJyAmJiAhQXJyYXlCdWZmZXIucHJvdG90eXBlLnNsaWNlKSB7XG4gICAgICAgIChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBmdW5jdGlvbiBjbGFtcCh2YWwsIGxlbmd0aCkge1xuICAgICAgICAgICAgICAgIHZhbCA9ICh2YWwgfCAwKSB8fCAwO1xuXG4gICAgICAgICAgICAgICAgaWYgKHZhbCA8IDApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIE1hdGgubWF4KHZhbCArIGxlbmd0aCwgMCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgcmV0dXJuIE1hdGgubWluKHZhbCwgbGVuZ3RoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgQXJyYXlCdWZmZXIucHJvdG90eXBlLnNsaWNlID0gZnVuY3Rpb24gKGZyb20sIHRvKSB7XG4gICAgICAgICAgICAgICAgdmFyIGxlbmd0aCA9IHRoaXMuYnl0ZUxlbmd0aCxcbiAgICAgICAgICAgICAgICAgICAgYmVnaW4gPSBjbGFtcChmcm9tLCBsZW5ndGgpLFxuICAgICAgICAgICAgICAgICAgICBlbmQgPSBsZW5ndGgsXG4gICAgICAgICAgICAgICAgICAgIG51bSxcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0LFxuICAgICAgICAgICAgICAgICAgICB0YXJnZXRBcnJheSxcbiAgICAgICAgICAgICAgICAgICAgc291cmNlQXJyYXk7XG5cbiAgICAgICAgICAgICAgICBpZiAodG8gIT09IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgICAgICAgICBlbmQgPSBjbGFtcCh0bywgbGVuZ3RoKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoYmVnaW4gPiBlbmQpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG5ldyBBcnJheUJ1ZmZlcigwKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBudW0gPSBlbmQgLSBiZWdpbjtcbiAgICAgICAgICAgICAgICB0YXJnZXQgPSBuZXcgQXJyYXlCdWZmZXIobnVtKTtcbiAgICAgICAgICAgICAgICB0YXJnZXRBcnJheSA9IG5ldyBVaW50OEFycmF5KHRhcmdldCk7XG5cbiAgICAgICAgICAgICAgICBzb3VyY2VBcnJheSA9IG5ldyBVaW50OEFycmF5KHRoaXMsIGJlZ2luLCBudW0pO1xuICAgICAgICAgICAgICAgIHRhcmdldEFycmF5LnNldChzb3VyY2VBcnJheSk7XG5cbiAgICAgICAgICAgICAgICByZXR1cm4gdGFyZ2V0O1xuICAgICAgICAgICAgfTtcbiAgICAgICAgfSkoKTtcbiAgICB9XG5cbiAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblxuICAgIC8qKlxuICAgICAqIEhlbHBlcnMuXG4gICAgICovXG5cbiAgICBmdW5jdGlvbiB0b1V0Zjgoc3RyKSB7XG4gICAgICAgIGlmICgvW1xcdTAwODAtXFx1RkZGRl0vLnRlc3Qoc3RyKSkge1xuICAgICAgICAgICAgc3RyID0gdW5lc2NhcGUoZW5jb2RlVVJJQ29tcG9uZW50KHN0cikpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHN0cjtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB1dGY4U3RyMkFycmF5QnVmZmVyKHN0ciwgcmV0dXJuVUludDhBcnJheSkge1xuICAgICAgICB2YXIgbGVuZ3RoID0gc3RyLmxlbmd0aCxcbiAgICAgICAgICAgYnVmZiA9IG5ldyBBcnJheUJ1ZmZlcihsZW5ndGgpLFxuICAgICAgICAgICBhcnIgPSBuZXcgVWludDhBcnJheShidWZmKSxcbiAgICAgICAgICAgaTtcblxuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgbGVuZ3RoOyBpICs9IDEpIHtcbiAgICAgICAgICAgIGFycltpXSA9IHN0ci5jaGFyQ29kZUF0KGkpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHJldHVyblVJbnQ4QXJyYXkgPyBhcnIgOiBidWZmO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIGFycmF5QnVmZmVyMlV0ZjhTdHIoYnVmZikge1xuICAgICAgICByZXR1cm4gU3RyaW5nLmZyb21DaGFyQ29kZS5hcHBseShudWxsLCBuZXcgVWludDhBcnJheShidWZmKSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnMoZmlyc3QsIHNlY29uZCwgcmV0dXJuVUludDhBcnJheSkge1xuICAgICAgICB2YXIgcmVzdWx0ID0gbmV3IFVpbnQ4QXJyYXkoZmlyc3QuYnl0ZUxlbmd0aCArIHNlY29uZC5ieXRlTGVuZ3RoKTtcblxuICAgICAgICByZXN1bHQuc2V0KG5ldyBVaW50OEFycmF5KGZpcnN0KSk7XG4gICAgICAgIHJlc3VsdC5zZXQobmV3IFVpbnQ4QXJyYXkoc2Vjb25kKSwgZmlyc3QuYnl0ZUxlbmd0aCk7XG5cbiAgICAgICAgcmV0dXJuIHJldHVyblVJbnQ4QXJyYXkgPyByZXN1bHQgOiByZXN1bHQuYnVmZmVyO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIGhleFRvQmluYXJ5U3RyaW5nKGhleCkge1xuICAgICAgICB2YXIgYnl0ZXMgPSBbXSxcbiAgICAgICAgICAgIGxlbmd0aCA9IGhleC5sZW5ndGgsXG4gICAgICAgICAgICB4O1xuXG4gICAgICAgIGZvciAoeCA9IDA7IHggPCBsZW5ndGggLSAxOyB4ICs9IDIpIHtcbiAgICAgICAgICAgIGJ5dGVzLnB1c2gocGFyc2VJbnQoaGV4LnN1YnN0cih4LCAyKSwgMTYpKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBTdHJpbmcuZnJvbUNoYXJDb2RlLmFwcGx5KFN0cmluZywgYnl0ZXMpO1xuICAgIH1cblxuICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxuXG4gICAgLyoqXG4gICAgICogU3BhcmtNRDUgT09QIGltcGxlbWVudGF0aW9uLlxuICAgICAqXG4gICAgICogVXNlIHRoaXMgY2xhc3MgdG8gcGVyZm9ybSBhbiBpbmNyZW1lbnRhbCBtZDUsIG90aGVyd2lzZSB1c2UgdGhlXG4gICAgICogc3RhdGljIG1ldGhvZHMgaW5zdGVhZC5cbiAgICAgKi9cblxuICAgIGZ1bmN0aW9uIFNwYXJrTUQ1KCkge1xuICAgICAgICAvLyBjYWxsIHJlc2V0IHRvIGluaXQgdGhlIGluc3RhbmNlXG4gICAgICAgIHRoaXMucmVzZXQoKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBBcHBlbmRzIGEgc3RyaW5nLlxuICAgICAqIEEgY29udmVyc2lvbiB3aWxsIGJlIGFwcGxpZWQgaWYgYW4gdXRmOCBzdHJpbmcgaXMgZGV0ZWN0ZWQuXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge1N0cmluZ30gc3RyIFRoZSBzdHJpbmcgdG8gYmUgYXBwZW5kZWRcbiAgICAgKlxuICAgICAqIEByZXR1cm4ge1NwYXJrTUQ1fSBUaGUgaW5zdGFuY2UgaXRzZWxmXG4gICAgICovXG4gICAgU3BhcmtNRDUucHJvdG90eXBlLmFwcGVuZCA9IGZ1bmN0aW9uIChzdHIpIHtcbiAgICAgICAgLy8gQ29udmVydHMgdGhlIHN0cmluZyB0byB1dGY4IGJ5dGVzIGlmIG5lY2Vzc2FyeVxuICAgICAgICAvLyBUaGVuIGFwcGVuZCBhcyBiaW5hcnlcbiAgICAgICAgdGhpcy5hcHBlbmRCaW5hcnkodG9VdGY4KHN0cikpO1xuXG4gICAgICAgIHJldHVybiB0aGlzO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBBcHBlbmRzIGEgYmluYXJ5IHN0cmluZy5cbiAgICAgKlxuICAgICAqIEBwYXJhbSB7U3RyaW5nfSBjb250ZW50cyBUaGUgYmluYXJ5IHN0cmluZyB0byBiZSBhcHBlbmRlZFxuICAgICAqXG4gICAgICogQHJldHVybiB7U3BhcmtNRDV9IFRoZSBpbnN0YW5jZSBpdHNlbGZcbiAgICAgKi9cbiAgICBTcGFya01ENS5wcm90b3R5cGUuYXBwZW5kQmluYXJ5ID0gZnVuY3Rpb24gKGNvbnRlbnRzKSB7XG4gICAgICAgIHRoaXMuX2J1ZmYgKz0gY29udGVudHM7XG4gICAgICAgIHRoaXMuX2xlbmd0aCArPSBjb250ZW50cy5sZW5ndGg7XG5cbiAgICAgICAgdmFyIGxlbmd0aCA9IHRoaXMuX2J1ZmYubGVuZ3RoLFxuICAgICAgICAgICAgaTtcblxuICAgICAgICBmb3IgKGkgPSA2NDsgaSA8PSBsZW5ndGg7IGkgKz0gNjQpIHtcbiAgICAgICAgICAgIG1kNWN5Y2xlKHRoaXMuX2hhc2gsIG1kNWJsayh0aGlzLl9idWZmLnN1YnN0cmluZyhpIC0gNjQsIGkpKSk7XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLl9idWZmID0gdGhpcy5fYnVmZi5zdWJzdHJpbmcoaSAtIDY0KTtcblxuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogRmluaXNoZXMgdGhlIGluY3JlbWVudGFsIGNvbXB1dGF0aW9uLCByZXNldGluZyB0aGUgaW50ZXJuYWwgc3RhdGUgYW5kXG4gICAgICogcmV0dXJuaW5nIHRoZSByZXN1bHQuXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0Jvb2xlYW59IHJhdyBUcnVlIHRvIGdldCB0aGUgcmF3IHN0cmluZywgZmFsc2UgdG8gZ2V0IHRoZSBoZXggc3RyaW5nXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtTdHJpbmd9IFRoZSByZXN1bHRcbiAgICAgKi9cbiAgICBTcGFya01ENS5wcm90b3R5cGUuZW5kID0gZnVuY3Rpb24gKHJhdykge1xuICAgICAgICB2YXIgYnVmZiA9IHRoaXMuX2J1ZmYsXG4gICAgICAgICAgICBsZW5ndGggPSBidWZmLmxlbmd0aCxcbiAgICAgICAgICAgIGksXG4gICAgICAgICAgICB0YWlsID0gWzAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDAsIDBdLFxuICAgICAgICAgICAgcmV0O1xuXG4gICAgICAgIGZvciAoaSA9IDA7IGkgPCBsZW5ndGg7IGkgKz0gMSkge1xuICAgICAgICAgICAgdGFpbFtpID4+IDJdIHw9IGJ1ZmYuY2hhckNvZGVBdChpKSA8PCAoKGkgJSA0KSA8PCAzKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuX2ZpbmlzaCh0YWlsLCBsZW5ndGgpO1xuICAgICAgICByZXQgPSBoZXgodGhpcy5faGFzaCk7XG5cbiAgICAgICAgaWYgKHJhdykge1xuICAgICAgICAgICAgcmV0ID0gaGV4VG9CaW5hcnlTdHJpbmcocmV0KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMucmVzZXQoKTtcblxuICAgICAgICByZXR1cm4gcmV0O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBSZXNldHMgdGhlIGludGVybmFsIHN0YXRlIG9mIHRoZSBjb21wdXRhdGlvbi5cbiAgICAgKlxuICAgICAqIEByZXR1cm4ge1NwYXJrTUQ1fSBUaGUgaW5zdGFuY2UgaXRzZWxmXG4gICAgICovXG4gICAgU3BhcmtNRDUucHJvdG90eXBlLnJlc2V0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLl9idWZmID0gJyc7XG4gICAgICAgIHRoaXMuX2xlbmd0aCA9IDA7XG4gICAgICAgIHRoaXMuX2hhc2ggPSBbMTczMjU4NDE5MywgLTI3MTczMzg3OSwgLTE3MzI1ODQxOTQsIDI3MTczMzg3OF07XG5cbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEdldHMgdGhlIGludGVybmFsIHN0YXRlIG9mIHRoZSBjb21wdXRhdGlvbi5cbiAgICAgKlxuICAgICAqIEByZXR1cm4ge09iamVjdH0gVGhlIHN0YXRlXG4gICAgICovXG4gICAgU3BhcmtNRDUucHJvdG90eXBlLmdldFN0YXRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgYnVmZjogdGhpcy5fYnVmZixcbiAgICAgICAgICAgIGxlbmd0aDogdGhpcy5fbGVuZ3RoLFxuICAgICAgICAgICAgaGFzaDogdGhpcy5faGFzaFxuICAgICAgICB9O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBHZXRzIHRoZSBpbnRlcm5hbCBzdGF0ZSBvZiB0aGUgY29tcHV0YXRpb24uXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge09iamVjdH0gc3RhdGUgVGhlIHN0YXRlXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtTcGFya01ENX0gVGhlIGluc3RhbmNlIGl0c2VsZlxuICAgICAqL1xuICAgIFNwYXJrTUQ1LnByb3RvdHlwZS5zZXRTdGF0ZSA9IGZ1bmN0aW9uIChzdGF0ZSkge1xuICAgICAgICB0aGlzLl9idWZmID0gc3RhdGUuYnVmZjtcbiAgICAgICAgdGhpcy5fbGVuZ3RoID0gc3RhdGUubGVuZ3RoO1xuICAgICAgICB0aGlzLl9oYXNoID0gc3RhdGUuaGFzaDtcblxuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogUmVsZWFzZXMgbWVtb3J5IHVzZWQgYnkgdGhlIGluY3JlbWVudGFsIGJ1ZmZlciBhbmQgb3RoZXIgYWRkaXRpb25hbFxuICAgICAqIHJlc291cmNlcy4gSWYgeW91IHBsYW4gdG8gdXNlIHRoZSBpbnN0YW5jZSBhZ2FpbiwgdXNlIHJlc2V0IGluc3RlYWQuXG4gICAgICovXG4gICAgU3BhcmtNRDUucHJvdG90eXBlLmRlc3Ryb3kgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGRlbGV0ZSB0aGlzLl9oYXNoO1xuICAgICAgICBkZWxldGUgdGhpcy5fYnVmZjtcbiAgICAgICAgZGVsZXRlIHRoaXMuX2xlbmd0aDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogRmluaXNoIHRoZSBmaW5hbCBjYWxjdWxhdGlvbiBiYXNlZCBvbiB0aGUgdGFpbC5cbiAgICAgKlxuICAgICAqIEBwYXJhbSB7QXJyYXl9ICB0YWlsICAgVGhlIHRhaWwgKHdpbGwgYmUgbW9kaWZpZWQpXG4gICAgICogQHBhcmFtIHtOdW1iZXJ9IGxlbmd0aCBUaGUgbGVuZ3RoIG9mIHRoZSByZW1haW5pbmcgYnVmZmVyXG4gICAgICovXG4gICAgU3BhcmtNRDUucHJvdG90eXBlLl9maW5pc2ggPSBmdW5jdGlvbiAodGFpbCwgbGVuZ3RoKSB7XG4gICAgICAgIHZhciBpID0gbGVuZ3RoLFxuICAgICAgICAgICAgdG1wLFxuICAgICAgICAgICAgbG8sXG4gICAgICAgICAgICBoaTtcblxuICAgICAgICB0YWlsW2kgPj4gMl0gfD0gMHg4MCA8PCAoKGkgJSA0KSA8PCAzKTtcbiAgICAgICAgaWYgKGkgPiA1NSkge1xuICAgICAgICAgICAgbWQ1Y3ljbGUodGhpcy5faGFzaCwgdGFpbCk7XG4gICAgICAgICAgICBmb3IgKGkgPSAwOyBpIDwgMTY7IGkgKz0gMSkge1xuICAgICAgICAgICAgICAgIHRhaWxbaV0gPSAwO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgLy8gRG8gdGhlIGZpbmFsIGNvbXB1dGF0aW9uIGJhc2VkIG9uIHRoZSB0YWlsIGFuZCBsZW5ndGhcbiAgICAgICAgLy8gQmV3YXJlIHRoYXQgdGhlIGZpbmFsIGxlbmd0aCBtYXkgbm90IGZpdCBpbiAzMiBiaXRzIHNvIHdlIHRha2UgY2FyZSBvZiB0aGF0XG4gICAgICAgIHRtcCA9IHRoaXMuX2xlbmd0aCAqIDg7XG4gICAgICAgIHRtcCA9IHRtcC50b1N0cmluZygxNikubWF0Y2goLyguKj8pKC57MCw4fSkkLyk7XG4gICAgICAgIGxvID0gcGFyc2VJbnQodG1wWzJdLCAxNik7XG4gICAgICAgIGhpID0gcGFyc2VJbnQodG1wWzFdLCAxNikgfHwgMDtcblxuICAgICAgICB0YWlsWzE0XSA9IGxvO1xuICAgICAgICB0YWlsWzE1XSA9IGhpO1xuICAgICAgICBtZDVjeWNsZSh0aGlzLl9oYXNoLCB0YWlsKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogUGVyZm9ybXMgdGhlIG1kNSBoYXNoIG9uIGEgc3RyaW5nLlxuICAgICAqIEEgY29udmVyc2lvbiB3aWxsIGJlIGFwcGxpZWQgaWYgdXRmOCBzdHJpbmcgaXMgZGV0ZWN0ZWQuXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge1N0cmluZ30gIHN0ciBUaGUgc3RyaW5nXG4gICAgICogQHBhcmFtIHtCb29sZWFufSByYXcgVHJ1ZSB0byBnZXQgdGhlIHJhdyBzdHJpbmcsIGZhbHNlIHRvIGdldCB0aGUgaGV4IHN0cmluZ1xuICAgICAqXG4gICAgICogQHJldHVybiB7U3RyaW5nfSBUaGUgcmVzdWx0XG4gICAgICovXG4gICAgU3BhcmtNRDUuaGFzaCA9IGZ1bmN0aW9uIChzdHIsIHJhdykge1xuICAgICAgICAvLyBDb252ZXJ0cyB0aGUgc3RyaW5nIHRvIHV0ZjggYnl0ZXMgaWYgbmVjZXNzYXJ5XG4gICAgICAgIC8vIFRoZW4gY29tcHV0ZSBpdCB1c2luZyB0aGUgYmluYXJ5IGZ1bmN0aW9uXG4gICAgICAgIHJldHVybiBTcGFya01ENS5oYXNoQmluYXJ5KHRvVXRmOChzdHIpLCByYXcpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBQZXJmb3JtcyB0aGUgbWQ1IGhhc2ggb24gYSBiaW5hcnkgc3RyaW5nLlxuICAgICAqXG4gICAgICogQHBhcmFtIHtTdHJpbmd9ICBjb250ZW50IFRoZSBiaW5hcnkgc3RyaW5nXG4gICAgICogQHBhcmFtIHtCb29sZWFufSByYXcgICAgIFRydWUgdG8gZ2V0IHRoZSByYXcgc3RyaW5nLCBmYWxzZSB0byBnZXQgdGhlIGhleCBzdHJpbmdcbiAgICAgKlxuICAgICAqIEByZXR1cm4ge1N0cmluZ30gVGhlIHJlc3VsdFxuICAgICAqL1xuICAgIFNwYXJrTUQ1Lmhhc2hCaW5hcnkgPSBmdW5jdGlvbiAoY29udGVudCwgcmF3KSB7XG4gICAgICAgIHZhciBoYXNoID0gbWQ1MShjb250ZW50KSxcbiAgICAgICAgICAgIHJldCA9IGhleChoYXNoKTtcblxuICAgICAgICByZXR1cm4gcmF3ID8gaGV4VG9CaW5hcnlTdHJpbmcocmV0KSA6IHJldDtcbiAgICB9O1xuXG4gICAgLy8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cbiAgICAvKipcbiAgICAgKiBTcGFya01ENSBPT1AgaW1wbGVtZW50YXRpb24gZm9yIGFycmF5IGJ1ZmZlcnMuXG4gICAgICpcbiAgICAgKiBVc2UgdGhpcyBjbGFzcyB0byBwZXJmb3JtIGFuIGluY3JlbWVudGFsIG1kNSBPTkxZIGZvciBhcnJheSBidWZmZXJzLlxuICAgICAqL1xuICAgIFNwYXJrTUQ1LkFycmF5QnVmZmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAvLyBjYWxsIHJlc2V0IHRvIGluaXQgdGhlIGluc3RhbmNlXG4gICAgICAgIHRoaXMucmVzZXQoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQXBwZW5kcyBhbiBhcnJheSBidWZmZXIuXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0FycmF5QnVmZmVyfSBhcnIgVGhlIGFycmF5IHRvIGJlIGFwcGVuZGVkXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtTcGFya01ENS5BcnJheUJ1ZmZlcn0gVGhlIGluc3RhbmNlIGl0c2VsZlxuICAgICAqL1xuICAgIFNwYXJrTUQ1LkFycmF5QnVmZmVyLnByb3RvdHlwZS5hcHBlbmQgPSBmdW5jdGlvbiAoYXJyKSB7XG4gICAgICAgIHZhciBidWZmID0gY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnModGhpcy5fYnVmZi5idWZmZXIsIGFyciwgdHJ1ZSksXG4gICAgICAgICAgICBsZW5ndGggPSBidWZmLmxlbmd0aCxcbiAgICAgICAgICAgIGk7XG5cbiAgICAgICAgdGhpcy5fbGVuZ3RoICs9IGFyci5ieXRlTGVuZ3RoO1xuXG4gICAgICAgIGZvciAoaSA9IDY0OyBpIDw9IGxlbmd0aDsgaSArPSA2NCkge1xuICAgICAgICAgICAgbWQ1Y3ljbGUodGhpcy5faGFzaCwgbWQ1YmxrX2FycmF5KGJ1ZmYuc3ViYXJyYXkoaSAtIDY0LCBpKSkpO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5fYnVmZiA9IChpIC0gNjQpIDwgbGVuZ3RoID8gbmV3IFVpbnQ4QXJyYXkoYnVmZi5idWZmZXIuc2xpY2UoaSAtIDY0KSkgOiBuZXcgVWludDhBcnJheSgwKTtcblxuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogRmluaXNoZXMgdGhlIGluY3JlbWVudGFsIGNvbXB1dGF0aW9uLCByZXNldGluZyB0aGUgaW50ZXJuYWwgc3RhdGUgYW5kXG4gICAgICogcmV0dXJuaW5nIHRoZSByZXN1bHQuXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0Jvb2xlYW59IHJhdyBUcnVlIHRvIGdldCB0aGUgcmF3IHN0cmluZywgZmFsc2UgdG8gZ2V0IHRoZSBoZXggc3RyaW5nXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtTdHJpbmd9IFRoZSByZXN1bHRcbiAgICAgKi9cbiAgICBTcGFya01ENS5BcnJheUJ1ZmZlci5wcm90b3R5cGUuZW5kID0gZnVuY3Rpb24gKHJhdykge1xuICAgICAgICB2YXIgYnVmZiA9IHRoaXMuX2J1ZmYsXG4gICAgICAgICAgICBsZW5ndGggPSBidWZmLmxlbmd0aCxcbiAgICAgICAgICAgIHRhaWwgPSBbMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMCwgMF0sXG4gICAgICAgICAgICBpLFxuICAgICAgICAgICAgcmV0O1xuXG4gICAgICAgIGZvciAoaSA9IDA7IGkgPCBsZW5ndGg7IGkgKz0gMSkge1xuICAgICAgICAgICAgdGFpbFtpID4+IDJdIHw9IGJ1ZmZbaV0gPDwgKChpICUgNCkgPDwgMyk7XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLl9maW5pc2godGFpbCwgbGVuZ3RoKTtcbiAgICAgICAgcmV0ID0gaGV4KHRoaXMuX2hhc2gpO1xuXG4gICAgICAgIGlmIChyYXcpIHtcbiAgICAgICAgICAgIHJldCA9IGhleFRvQmluYXJ5U3RyaW5nKHJldCk7XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnJlc2V0KCk7XG5cbiAgICAgICAgcmV0dXJuIHJldDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogUmVzZXRzIHRoZSBpbnRlcm5hbCBzdGF0ZSBvZiB0aGUgY29tcHV0YXRpb24uXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtTcGFya01ENS5BcnJheUJ1ZmZlcn0gVGhlIGluc3RhbmNlIGl0c2VsZlxuICAgICAqL1xuICAgIFNwYXJrTUQ1LkFycmF5QnVmZmVyLnByb3RvdHlwZS5yZXNldCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5fYnVmZiA9IG5ldyBVaW50OEFycmF5KDApO1xuICAgICAgICB0aGlzLl9sZW5ndGggPSAwO1xuICAgICAgICB0aGlzLl9oYXNoID0gWzE3MzI1ODQxOTMsIC0yNzE3MzM4NzksIC0xNzMyNTg0MTk0LCAyNzE3MzM4NzhdO1xuXG4gICAgICAgIHJldHVybiB0aGlzO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBHZXRzIHRoZSBpbnRlcm5hbCBzdGF0ZSBvZiB0aGUgY29tcHV0YXRpb24uXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtPYmplY3R9IFRoZSBzdGF0ZVxuICAgICAqL1xuICAgIFNwYXJrTUQ1LkFycmF5QnVmZmVyLnByb3RvdHlwZS5nZXRTdGF0ZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHN0YXRlID0gU3BhcmtNRDUucHJvdG90eXBlLmdldFN0YXRlLmNhbGwodGhpcyk7XG5cbiAgICAgICAgLy8gQ29udmVydCBidWZmZXIgdG8gYSBzdHJpbmdcbiAgICAgICAgc3RhdGUuYnVmZiA9IGFycmF5QnVmZmVyMlV0ZjhTdHIoc3RhdGUuYnVmZik7XG5cbiAgICAgICAgcmV0dXJuIHN0YXRlO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBHZXRzIHRoZSBpbnRlcm5hbCBzdGF0ZSBvZiB0aGUgY29tcHV0YXRpb24uXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge09iamVjdH0gc3RhdGUgVGhlIHN0YXRlXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtTcGFya01ENS5BcnJheUJ1ZmZlcn0gVGhlIGluc3RhbmNlIGl0c2VsZlxuICAgICAqL1xuICAgIFNwYXJrTUQ1LkFycmF5QnVmZmVyLnByb3RvdHlwZS5zZXRTdGF0ZSA9IGZ1bmN0aW9uIChzdGF0ZSkge1xuICAgICAgICAvLyBDb252ZXJ0IHN0cmluZyB0byBidWZmZXJcbiAgICAgICAgc3RhdGUuYnVmZiA9IHV0ZjhTdHIyQXJyYXlCdWZmZXIoc3RhdGUuYnVmZiwgdHJ1ZSk7XG5cbiAgICAgICAgcmV0dXJuIFNwYXJrTUQ1LnByb3RvdHlwZS5zZXRTdGF0ZS5jYWxsKHRoaXMsIHN0YXRlKTtcbiAgICB9O1xuXG4gICAgU3BhcmtNRDUuQXJyYXlCdWZmZXIucHJvdG90eXBlLmRlc3Ryb3kgPSBTcGFya01ENS5wcm90b3R5cGUuZGVzdHJveTtcblxuICAgIFNwYXJrTUQ1LkFycmF5QnVmZmVyLnByb3RvdHlwZS5fZmluaXNoID0gU3BhcmtNRDUucHJvdG90eXBlLl9maW5pc2g7XG5cbiAgICAvKipcbiAgICAgKiBQZXJmb3JtcyB0aGUgbWQ1IGhhc2ggb24gYW4gYXJyYXkgYnVmZmVyLlxuICAgICAqXG4gICAgICogQHBhcmFtIHtBcnJheUJ1ZmZlcn0gYXJyIFRoZSBhcnJheSBidWZmZXJcbiAgICAgKiBAcGFyYW0ge0Jvb2xlYW59ICAgICByYXcgVHJ1ZSB0byBnZXQgdGhlIHJhdyBzdHJpbmcsIGZhbHNlIHRvIGdldCB0aGUgaGV4IG9uZVxuICAgICAqXG4gICAgICogQHJldHVybiB7U3RyaW5nfSBUaGUgcmVzdWx0XG4gICAgICovXG4gICAgU3BhcmtNRDUuQXJyYXlCdWZmZXIuaGFzaCA9IGZ1bmN0aW9uIChhcnIsIHJhdykge1xuICAgICAgICB2YXIgaGFzaCA9IG1kNTFfYXJyYXkobmV3IFVpbnQ4QXJyYXkoYXJyKSksXG4gICAgICAgICAgICByZXQgPSBoZXgoaGFzaCk7XG5cbiAgICAgICAgcmV0dXJuIHJhdyA/IGhleFRvQmluYXJ5U3RyaW5nKHJldCkgOiByZXQ7XG4gICAgfTtcblxuICAgIHJldHVybiBTcGFya01ENTtcbn0pKTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL3NwYXJrLW1kNS9zcGFyay1tZDUuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL3NwYXJrLW1kNS9zcGFyay1tZDUuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLyohXHJcbiAgKiBTdGlja3lmaWxsIOKAkyBgcG9zaXRpb246IHN0aWNreWAgcG9seWZpbGxcclxuICAqIHYuIDIuMC41IHwgaHR0cHM6Ly9naXRodWIuY29tL3dpbGRkZWVyL3N0aWNreWZpbGxcclxuICAqIE1JVCBMaWNlbnNlXHJcbiAgKi9cclxuXHJcbjsoZnVuY3Rpb24od2luZG93LCBkb2N1bWVudCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG4gICAgXHJcbiAgICAvKlxyXG4gICAgICogMS4gQ2hlY2sgaWYgdGhlIGJyb3dzZXIgc3VwcG9ydHMgYHBvc2l0aW9uOiBzdGlja3lgIG5hdGl2ZWx5IG9yIGlzIHRvbyBvbGQgdG8gcnVuIHRoZSBwb2x5ZmlsbC5cclxuICAgICAqICAgIElmIGVpdGhlciBvZiB0aGVzZSBpcyB0aGUgY2FzZSBzZXQgYHNlcHB1a3VgIGZsYWcuIEl0IHdpbGwgYmUgY2hlY2tlZCBsYXRlciB0byBkaXNhYmxlIGtleSBmZWF0dXJlc1xyXG4gICAgICogICAgb2YgdGhlIHBvbHlmaWxsLCBidXQgdGhlIEFQSSB3aWxsIHJlbWFpbiBmdW5jdGlvbmFsIHRvIGF2b2lkIGJyZWFraW5nIHRoaW5ncy5cclxuICAgICAqL1xyXG4gICAgXHJcbiAgICB2YXIgX2NyZWF0ZUNsYXNzID0gZnVuY3Rpb24gKCkgeyBmdW5jdGlvbiBkZWZpbmVQcm9wZXJ0aWVzKHRhcmdldCwgcHJvcHMpIHsgZm9yICh2YXIgaSA9IDA7IGkgPCBwcm9wcy5sZW5ndGg7IGkrKykgeyB2YXIgZGVzY3JpcHRvciA9IHByb3BzW2ldOyBkZXNjcmlwdG9yLmVudW1lcmFibGUgPSBkZXNjcmlwdG9yLmVudW1lcmFibGUgfHwgZmFsc2U7IGRlc2NyaXB0b3IuY29uZmlndXJhYmxlID0gdHJ1ZTsgaWYgKFwidmFsdWVcIiBpbiBkZXNjcmlwdG9yKSBkZXNjcmlwdG9yLndyaXRhYmxlID0gdHJ1ZTsgT2JqZWN0LmRlZmluZVByb3BlcnR5KHRhcmdldCwgZGVzY3JpcHRvci5rZXksIGRlc2NyaXB0b3IpOyB9IH0gcmV0dXJuIGZ1bmN0aW9uIChDb25zdHJ1Y3RvciwgcHJvdG9Qcm9wcywgc3RhdGljUHJvcHMpIHsgaWYgKHByb3RvUHJvcHMpIGRlZmluZVByb3BlcnRpZXMoQ29uc3RydWN0b3IucHJvdG90eXBlLCBwcm90b1Byb3BzKTsgaWYgKHN0YXRpY1Byb3BzKSBkZWZpbmVQcm9wZXJ0aWVzKENvbnN0cnVjdG9yLCBzdGF0aWNQcm9wcyk7IHJldHVybiBDb25zdHJ1Y3RvcjsgfTsgfSgpO1xyXG4gICAgXHJcbiAgICBmdW5jdGlvbiBfY2xhc3NDYWxsQ2hlY2soaW5zdGFuY2UsIENvbnN0cnVjdG9yKSB7IGlmICghKGluc3RhbmNlIGluc3RhbmNlb2YgQ29uc3RydWN0b3IpKSB7IHRocm93IG5ldyBUeXBlRXJyb3IoXCJDYW5ub3QgY2FsbCBhIGNsYXNzIGFzIGEgZnVuY3Rpb25cIik7IH0gfVxyXG4gICAgXHJcbiAgICB2YXIgc2VwcHVrdSA9IGZhbHNlO1xyXG4gICAgXHJcbiAgICAvLyBUaGUgcG9seWZpbGwgY2FudOKAmXQgZnVuY3Rpb24gcHJvcGVybHkgd2l0aG91dCBgZ2V0Q29tcHV0ZWRTdHlsZWAuXHJcbiAgICBpZiAoIXdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKSBzZXBwdWt1ID0gdHJ1ZTtcclxuICAgIC8vIERvbnTigJl0IGdldCBpbiBhIHdheSBpZiB0aGUgYnJvd3NlciBzdXBwb3J0cyBgcG9zaXRpb246IHN0aWNreWAgbmF0aXZlbHkuXHJcbiAgICBlbHNlIHtcclxuICAgICAgICAgICAgdmFyIHRlc3ROb2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICBcclxuICAgICAgICAgICAgaWYgKFsnJywgJy13ZWJraXQtJywgJy1tb3otJywgJy1tcy0nXS5zb21lKGZ1bmN0aW9uIChwcmVmaXgpIHtcclxuICAgICAgICAgICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGVzdE5vZGUuc3R5bGUucG9zaXRpb24gPSBwcmVmaXggKyAnc3RpY2t5JztcclxuICAgICAgICAgICAgICAgIH0gY2F0Y2ggKGUpIHt9XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIHJldHVybiB0ZXN0Tm9kZS5zdHlsZS5wb3NpdGlvbiAhPSAnJztcclxuICAgICAgICAgICAgfSkpIHNlcHB1a3UgPSB0cnVlO1xyXG4gICAgICAgIH1cclxuICAgIFxyXG4gICAgLypcclxuICAgICAqIDIuIOKAnEdsb2JhbOKAnSB2YXJzIHVzZWQgYWNyb3NzIHRoZSBwb2x5ZmlsbFxyXG4gICAgICovXHJcbiAgICBcclxuICAgIC8vIENoZWNrIGlmIFNoYWRvdyBSb290IGNvbnN0cnVjdG9yIGV4aXN0cyB0byBtYWtlIGZ1cnRoZXIgY2hlY2tzIHNpbXBsZXJcclxuICAgIHZhciBzaGFkb3dSb290RXhpc3RzID0gdHlwZW9mIFNoYWRvd1Jvb3QgIT09ICd1bmRlZmluZWQnO1xyXG4gICAgXHJcbiAgICAvLyBMYXN0IHNhdmVkIHNjcm9sbCBwb3NpdGlvblxyXG4gICAgdmFyIHNjcm9sbCA9IHtcclxuICAgICAgICB0b3A6IG51bGwsXHJcbiAgICAgICAgbGVmdDogbnVsbFxyXG4gICAgfTtcclxuICAgIFxyXG4gICAgLy8gQXJyYXkgb2YgY3JlYXRlZCBTdGlja3kgaW5zdGFuY2VzXHJcbiAgICB2YXIgc3RpY2tpZXMgPSBbXTtcclxuICAgIFxyXG4gICAgLypcclxuICAgICAqIDMuIFV0aWxpdHkgZnVuY3Rpb25zXHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIGV4dGVuZCh0YXJnZXRPYmosIHNvdXJjZU9iamVjdCkge1xyXG4gICAgICAgIGZvciAodmFyIGtleSBpbiBzb3VyY2VPYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKHNvdXJjZU9iamVjdC5oYXNPd25Qcm9wZXJ0eShrZXkpKSB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRPYmpba2V5XSA9IHNvdXJjZU9iamVjdFtrZXldO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG4gICAgXHJcbiAgICBmdW5jdGlvbiBwYXJzZU51bWVyaWModmFsKSB7XHJcbiAgICAgICAgcmV0dXJuIHBhcnNlRmxvYXQodmFsKSB8fCAwO1xyXG4gICAgfVxyXG4gICAgXHJcbiAgICBmdW5jdGlvbiBnZXREb2NPZmZzZXRUb3Aobm9kZSkge1xyXG4gICAgICAgIHZhciBkb2NPZmZzZXRUb3AgPSAwO1xyXG4gICAgXHJcbiAgICAgICAgd2hpbGUgKG5vZGUpIHtcclxuICAgICAgICAgICAgZG9jT2Zmc2V0VG9wICs9IG5vZGUub2Zmc2V0VG9wO1xyXG4gICAgICAgICAgICBub2RlID0gbm9kZS5vZmZzZXRQYXJlbnQ7XHJcbiAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgcmV0dXJuIGRvY09mZnNldFRvcDtcclxuICAgIH1cclxuICAgIFxyXG4gICAgLypcclxuICAgICAqIDQuIFN0aWNreSBjbGFzc1xyXG4gICAgICovXHJcbiAgICBcclxuICAgIHZhciBTdGlja3kgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgZnVuY3Rpb24gU3RpY2t5KG5vZGUpIHtcclxuICAgICAgICAgICAgX2NsYXNzQ2FsbENoZWNrKHRoaXMsIFN0aWNreSk7XHJcbiAgICBcclxuICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkgdGhyb3cgbmV3IEVycm9yKCdGaXJzdCBhcmd1bWVudCBtdXN0IGJlIEhUTUxFbGVtZW50Jyk7XHJcbiAgICAgICAgICAgIGlmIChzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgIHJldHVybiBzdGlja3kuX25vZGUgPT09IG5vZGU7XHJcbiAgICAgICAgICAgIH0pKSB0aHJvdyBuZXcgRXJyb3IoJ1N0aWNreWZpbGwgaXMgYWxyZWFkeSBhcHBsaWVkIHRvIHRoaXMgbm9kZScpO1xyXG4gICAgXHJcbiAgICAgICAgICAgIHRoaXMuX25vZGUgPSBub2RlO1xyXG4gICAgICAgICAgICB0aGlzLl9zdGlja3lNb2RlID0gbnVsbDtcclxuICAgICAgICAgICAgdGhpcy5fYWN0aXZlID0gZmFsc2U7XHJcbiAgICBcclxuICAgICAgICAgICAgc3RpY2tpZXMucHVzaCh0aGlzKTtcclxuICAgIFxyXG4gICAgICAgICAgICB0aGlzLnJlZnJlc2goKTtcclxuICAgICAgICB9XHJcbiAgICBcclxuICAgICAgICBfY3JlYXRlQ2xhc3MoU3RpY2t5LCBbe1xyXG4gICAgICAgICAgICBrZXk6ICdyZWZyZXNoJyxcclxuICAgICAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHJlZnJlc2goKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoc2VwcHVrdSB8fCB0aGlzLl9yZW1vdmVkKSByZXR1cm47XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5fYWN0aXZlKSB0aGlzLl9kZWFjdGl2YXRlKCk7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIHZhciBub2RlID0gdGhpcy5fbm9kZTtcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDEuIFNhdmUgbm9kZSBjb21wdXRlZCBwcm9wc1xyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZUNvbXB1dGVkU3R5bGUgPSBnZXRDb21wdXRlZFN0eWxlKG5vZGUpO1xyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGVDb21wdXRlZFByb3BzID0ge1xyXG4gICAgICAgICAgICAgICAgICAgIHRvcDogbm9kZUNvbXB1dGVkU3R5bGUudG9wLFxyXG4gICAgICAgICAgICAgICAgICAgIGRpc3BsYXk6IG5vZGVDb21wdXRlZFN0eWxlLmRpc3BsYXksXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luVG9wOiBub2RlQ29tcHV0ZWRTdHlsZS5tYXJnaW5Ub3AsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luQm90dG9tOiBub2RlQ29tcHV0ZWRTdHlsZS5tYXJnaW5Cb3R0b20sXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogbm9kZUNvbXB1dGVkU3R5bGUubWFyZ2luTGVmdCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogbm9kZUNvbXB1dGVkU3R5bGUubWFyZ2luUmlnaHQsXHJcbiAgICAgICAgICAgICAgICAgICAgY3NzRmxvYXQ6IG5vZGVDb21wdXRlZFN0eWxlLmNzc0Zsb2F0XHJcbiAgICAgICAgICAgICAgICB9O1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogMi4gQ2hlY2sgaWYgdGhlIG5vZGUgY2FuIGJlIGFjdGl2YXRlZFxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICBpZiAoaXNOYU4ocGFyc2VGbG9hdChub2RlQ29tcHV0ZWRQcm9wcy50b3ApKSB8fCBub2RlQ29tcHV0ZWRQcm9wcy5kaXNwbGF5ID09ICd0YWJsZS1jZWxsJyB8fCBub2RlQ29tcHV0ZWRQcm9wcy5kaXNwbGF5ID09ICdub25lJykgcmV0dXJuO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSB0cnVlO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogMy4gR2V0IG5lY2Vzc2FyeSBub2RlIHBhcmFtZXRlcnNcclxuICAgICAgICAgICAgICAgICAqL1xyXG4gICAgICAgICAgICAgICAgdmFyIHJlZmVyZW5jZU5vZGUgPSBub2RlLnBhcmVudE5vZGU7XHJcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50Tm9kZSA9IHNoYWRvd1Jvb3RFeGlzdHMgJiYgcmVmZXJlbmNlTm9kZSBpbnN0YW5jZW9mIFNoYWRvd1Jvb3QgPyByZWZlcmVuY2VOb2RlLmhvc3QgOiByZWZlcmVuY2VOb2RlO1xyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGVXaW5PZmZzZXQgPSBub2RlLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xyXG4gICAgICAgICAgICAgICAgdmFyIHBhcmVudFdpbk9mZnNldCA9IHBhcmVudE5vZGUuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50Q29tcHV0ZWRTdHlsZSA9IGdldENvbXB1dGVkU3R5bGUocGFyZW50Tm9kZSk7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIHRoaXMuX3BhcmVudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICBub2RlOiBwYXJlbnROb2RlLFxyXG4gICAgICAgICAgICAgICAgICAgIHN0eWxlczoge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogcGFyZW50Tm9kZS5zdHlsZS5wb3NpdGlvblxyXG4gICAgICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb2Zmc2V0SGVpZ2h0OiBwYXJlbnROb2RlLm9mZnNldEhlaWdodFxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX29mZnNldFRvV2luZG93ID0ge1xyXG4gICAgICAgICAgICAgICAgICAgIGxlZnQ6IG5vZGVXaW5PZmZzZXQubGVmdCxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDogZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudFdpZHRoIC0gbm9kZVdpbk9mZnNldC5yaWdodFxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX29mZnNldFRvUGFyZW50ID0ge1xyXG4gICAgICAgICAgICAgICAgICAgIHRvcDogbm9kZVdpbk9mZnNldC50b3AgLSBwYXJlbnRXaW5PZmZzZXQudG9wIC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyVG9wV2lkdGgpLFxyXG4gICAgICAgICAgICAgICAgICAgIGxlZnQ6IG5vZGVXaW5PZmZzZXQubGVmdCAtIHBhcmVudFdpbk9mZnNldC5sZWZ0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyTGVmdFdpZHRoKSxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDogLW5vZGVXaW5PZmZzZXQucmlnaHQgKyBwYXJlbnRXaW5PZmZzZXQucmlnaHQgLSBwYXJzZU51bWVyaWMocGFyZW50Q29tcHV0ZWRTdHlsZS5ib3JkZXJSaWdodFdpZHRoKVxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX3N0eWxlcyA9IHtcclxuICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogbm9kZS5zdHlsZS5wb3NpdGlvbixcclxuICAgICAgICAgICAgICAgICAgICB0b3A6IG5vZGUuc3R5bGUudG9wLFxyXG4gICAgICAgICAgICAgICAgICAgIGJvdHRvbTogbm9kZS5zdHlsZS5ib3R0b20sXHJcbiAgICAgICAgICAgICAgICAgICAgbGVmdDogbm9kZS5zdHlsZS5sZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIHJpZ2h0OiBub2RlLnN0eWxlLnJpZ2h0LFxyXG4gICAgICAgICAgICAgICAgICAgIHdpZHRoOiBub2RlLnN0eWxlLndpZHRoLFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogbm9kZS5zdHlsZS5tYXJnaW5Ub3AsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogbm9kZS5zdHlsZS5tYXJnaW5MZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiBub2RlLnN0eWxlLm1hcmdpblJpZ2h0XHJcbiAgICAgICAgICAgICAgICB9O1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZVRvcFZhbHVlID0gcGFyc2VOdW1lcmljKG5vZGVDb21wdXRlZFByb3BzLnRvcCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9saW1pdHMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgc3RhcnQ6IG5vZGVXaW5PZmZzZXQudG9wICsgd2luZG93LnBhZ2VZT2Zmc2V0IC0gbm9kZVRvcFZhbHVlLFxyXG4gICAgICAgICAgICAgICAgICAgIGVuZDogcGFyZW50V2luT2Zmc2V0LnRvcCArIHdpbmRvdy5wYWdlWU9mZnNldCArIHBhcmVudE5vZGUub2Zmc2V0SGVpZ2h0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyQm90dG9tV2lkdGgpIC0gbm9kZS5vZmZzZXRIZWlnaHQgLSBub2RlVG9wVmFsdWUgLSBwYXJzZU51bWVyaWMobm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luQm90dG9tKVxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDQuIEVuc3VyZSB0aGF0IHRoZSBub2RlIHdpbGwgYmUgcG9zaXRpb25lZCByZWxhdGl2ZWx5IHRvIHRoZSBwYXJlbnQgbm9kZVxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50UG9zaXRpb24gPSBwYXJlbnRDb21wdXRlZFN0eWxlLnBvc2l0aW9uO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICBpZiAocGFyZW50UG9zaXRpb24gIT0gJ2Fic29sdXRlJyAmJiBwYXJlbnRQb3NpdGlvbiAhPSAncmVsYXRpdmUnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcGFyZW50Tm9kZS5zdHlsZS5wb3NpdGlvbiA9ICdyZWxhdGl2ZSc7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiA1LiBSZWNhbGMgbm9kZSBwb3NpdGlvbi5cclxuICAgICAgICAgICAgICAgICAqICAgIEl04oCZcyBpbXBvcnRhbnQgdG8gZG8gdGhpcyBiZWZvcmUgY2xvbmUgaW5qZWN0aW9uIHRvIGF2b2lkIHNjcm9sbGluZyBidWcgaW4gQ2hyb21lLlxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9yZWNhbGNQb3NpdGlvbigpO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogNi4gQ3JlYXRlIGEgY2xvbmVcclxuICAgICAgICAgICAgICAgICAqL1xyXG4gICAgICAgICAgICAgICAgdmFyIGNsb25lID0gdGhpcy5fY2xvbmUgPSB7fTtcclxuICAgICAgICAgICAgICAgIGNsb25lLm5vZGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgLy8gQXBwbHkgc3R5bGVzIHRvIHRoZSBjbG9uZVxyXG4gICAgICAgICAgICAgICAgZXh0ZW5kKGNsb25lLm5vZGUuc3R5bGUsIHtcclxuICAgICAgICAgICAgICAgICAgICB3aWR0aDogbm9kZVdpbk9mZnNldC5yaWdodCAtIG5vZGVXaW5PZmZzZXQubGVmdCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiBub2RlV2luT2Zmc2V0LmJvdHRvbSAtIG5vZGVXaW5PZmZzZXQudG9wICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IG5vZGVDb21wdXRlZFByb3BzLm1hcmdpblRvcCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Cb3R0b206IG5vZGVDb21wdXRlZFByb3BzLm1hcmdpbkJvdHRvbSxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5MZWZ0OiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5MZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5SaWdodCxcclxuICAgICAgICAgICAgICAgICAgICBjc3NGbG9hdDogbm9kZUNvbXB1dGVkUHJvcHMuY3NzRmxvYXQsXHJcbiAgICAgICAgICAgICAgICAgICAgcGFkZGluZzogMCxcclxuICAgICAgICAgICAgICAgICAgICBib3JkZXI6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgYm9yZGVyU3BhY2luZzogMCxcclxuICAgICAgICAgICAgICAgICAgICBmb250U2l6ZTogJzFlbScsXHJcbiAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246ICdzdGF0aWMnXHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgcmVmZXJlbmNlTm9kZS5pbnNlcnRCZWZvcmUoY2xvbmUubm9kZSwgbm9kZSk7XHJcbiAgICAgICAgICAgICAgICBjbG9uZS5kb2NPZmZzZXRUb3AgPSBnZXREb2NPZmZzZXRUb3AoY2xvbmUubm9kZSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19yZWNhbGNQb3NpdGlvbicsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiBfcmVjYWxjUG9zaXRpb24oKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXRoaXMuX2FjdGl2ZSB8fCB0aGlzLl9yZW1vdmVkKSByZXR1cm47XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIHZhciBzdGlja3lNb2RlID0gc2Nyb2xsLnRvcCA8PSB0aGlzLl9saW1pdHMuc3RhcnQgPyAnc3RhcnQnIDogc2Nyb2xsLnRvcCA+PSB0aGlzLl9saW1pdHMuZW5kID8gJ2VuZCcgOiAnbWlkZGxlJztcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuX3N0aWNreU1vZGUgPT0gc3RpY2t5TW9kZSkgcmV0dXJuO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICBzd2l0Y2ggKHN0aWNreU1vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICBjYXNlICdzdGFydCc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2Fic29sdXRlJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvUGFyZW50LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvUGFyZW50LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQudG9wICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJvdHRvbTogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgICAgICBjYXNlICdtaWRkbGUnOlxyXG4gICAgICAgICAgICAgICAgICAgICAgICBleHRlbmQodGhpcy5fbm9kZS5zdHlsZSwge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246ICdmaXhlZCcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiB0aGlzLl9vZmZzZXRUb1dpbmRvdy5sZWZ0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJpZ2h0OiB0aGlzLl9vZmZzZXRUb1dpbmRvdy5yaWdodCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0b3A6IHRoaXMuX3N0eWxlcy50b3AsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBib3R0b206ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdpZHRoOiAnYXV0bycsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5MZWZ0OiAwLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luUmlnaHQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IDBcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnZW5kJzpcclxuICAgICAgICAgICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX25vZGUuc3R5bGUsIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnYWJzb2x1dGUnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQubGVmdCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByaWdodDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQucmlnaHQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiAnYXV0bycsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBib3R0b206IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aWR0aDogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiAwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuICAgICAgICAgICAgICAgIH1cclxuICAgIFxyXG4gICAgICAgICAgICAgICAgdGhpcy5fc3RpY2t5TW9kZSA9IHN0aWNreU1vZGU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19mYXN0Q2hlY2snLFxyXG4gICAgICAgICAgICB2YWx1ZTogZnVuY3Rpb24gX2Zhc3RDaGVjaygpIHtcclxuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fYWN0aXZlIHx8IHRoaXMuX3JlbW92ZWQpIHJldHVybjtcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgaWYgKE1hdGguYWJzKGdldERvY09mZnNldFRvcCh0aGlzLl9jbG9uZS5ub2RlKSAtIHRoaXMuX2Nsb25lLmRvY09mZnNldFRvcCkgPiAxIHx8IE1hdGguYWJzKHRoaXMuX3BhcmVudC5ub2RlLm9mZnNldEhlaWdodCAtIHRoaXMuX3BhcmVudC5vZmZzZXRIZWlnaHQpID4gMSkgdGhpcy5yZWZyZXNoKCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19kZWFjdGl2YXRlJyxcclxuICAgICAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIF9kZWFjdGl2YXRlKCkge1xyXG4gICAgICAgICAgICAgICAgdmFyIF90aGlzID0gdGhpcztcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgaWYgKCF0aGlzLl9hY3RpdmUgfHwgdGhpcy5fcmVtb3ZlZCkgcmV0dXJuO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9jbG9uZS5ub2RlLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5fY2xvbmUubm9kZSk7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fY2xvbmU7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB0aGlzLl9zdHlsZXMpO1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX3N0eWxlcztcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgLy8gQ2hlY2sgd2hldGhlciBlbGVtZW504oCZcyBwYXJlbnQgbm9kZSBpcyB1c2VkIGJ5IG90aGVyIHN0aWNraWVzLlxyXG4gICAgICAgICAgICAgICAgLy8gSWYgbm90LCByZXN0b3JlIHBhcmVudCBub2Rl4oCZcyBzdHlsZXMuXHJcbiAgICAgICAgICAgICAgICBpZiAoIXN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBzdGlja3kgIT09IF90aGlzICYmIHN0aWNreS5fcGFyZW50ICYmIHN0aWNreS5fcGFyZW50Lm5vZGUgPT09IF90aGlzLl9wYXJlbnQubm9kZTtcclxuICAgICAgICAgICAgICAgIH0pKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX3BhcmVudC5ub2RlLnN0eWxlLCB0aGlzLl9wYXJlbnQuc3R5bGVzKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9wYXJlbnQ7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIHRoaXMuX3N0aWNreU1vZGUgPSBudWxsO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlID0gZmFsc2U7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9vZmZzZXRUb1dpbmRvdztcclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9vZmZzZXRUb1BhcmVudDtcclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9saW1pdHM7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ3JlbW92ZScsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiByZW1vdmUoKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgX3RoaXMyID0gdGhpcztcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgdGhpcy5fZGVhY3RpdmF0ZSgpO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3ksIGluZGV4KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHN0aWNreS5fbm9kZSA9PT0gX3RoaXMyLl9ub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0aWNraWVzLnNwbGljZShpbmRleCwgMSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9yZW1vdmVkID0gdHJ1ZTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1dKTtcclxuICAgIFxyXG4gICAgICAgIHJldHVybiBTdGlja3k7XHJcbiAgICB9KCk7XHJcbiAgICBcclxuICAgIC8qXHJcbiAgICAgKiA1LiBTdGlja3lmaWxsIEFQSVxyXG4gICAgICovXHJcbiAgICBcclxuICAgIFxyXG4gICAgdmFyIFN0aWNreWZpbGwgPSB7XHJcbiAgICAgICAgc3RpY2tpZXM6IHN0aWNraWVzLFxyXG4gICAgICAgIFN0aWNreTogU3RpY2t5LFxyXG4gICAgXHJcbiAgICAgICAgYWRkT25lOiBmdW5jdGlvbiBhZGRPbmUobm9kZSkge1xyXG4gICAgICAgICAgICAvLyBDaGVjayB3aGV0aGVyIGl04oCZcyBhIG5vZGVcclxuICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkge1xyXG4gICAgICAgICAgICAgICAgLy8gTWF5YmUgaXTigJlzIGEgbm9kZSBsaXN0IG9mIHNvbWUgc29ydD9cclxuICAgICAgICAgICAgICAgIC8vIFRha2UgZmlyc3Qgbm9kZSBmcm9tIHRoZSBsaXN0IHRoZW5cclxuICAgICAgICAgICAgICAgIGlmIChub2RlLmxlbmd0aCAmJiBub2RlWzBdKSBub2RlID0gbm9kZVswXTtlbHNlIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgICAgIC8vIENoZWNrIGlmIFN0aWNreWZpbGwgaXMgYWxyZWFkeSBhcHBsaWVkIHRvIHRoZSBub2RlXHJcbiAgICAgICAgICAgIC8vIGFuZCByZXR1cm4gZXhpc3Rpbmcgc3RpY2t5XHJcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgc3RpY2tpZXMubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgIGlmIChzdGlja2llc1tpXS5fbm9kZSA9PT0gbm9kZSkgcmV0dXJuIHN0aWNraWVzW2ldO1xyXG4gICAgICAgICAgICB9XHJcbiAgICBcclxuICAgICAgICAgICAgLy8gQ3JlYXRlIGFuZCByZXR1cm4gbmV3IHN0aWNreVxyXG4gICAgICAgICAgICByZXR1cm4gbmV3IFN0aWNreShub2RlKTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGFkZDogZnVuY3Rpb24gYWRkKG5vZGVMaXN0KSB7XHJcbiAgICAgICAgICAgIC8vIElmIGl04oCZcyBhIG5vZGUgbWFrZSBhbiBhcnJheSBvZiBvbmUgbm9kZVxyXG4gICAgICAgICAgICBpZiAobm9kZUxpc3QgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgbm9kZUxpc3QgPSBbbm9kZUxpc3RdO1xyXG4gICAgICAgICAgICAvLyBDaGVjayBpZiB0aGUgYXJndW1lbnQgaXMgYW4gaXRlcmFibGUgb2Ygc29tZSBzb3J0XHJcbiAgICAgICAgICAgIGlmICghbm9kZUxpc3QubGVuZ3RoKSByZXR1cm47XHJcbiAgICBcclxuICAgICAgICAgICAgLy8gQWRkIGV2ZXJ5IGVsZW1lbnQgYXMgYSBzdGlja3kgYW5kIHJldHVybiBhbiBhcnJheSBvZiBjcmVhdGVkIFN0aWNreSBpbnN0YW5jZXNcclxuICAgICAgICAgICAgdmFyIGFkZGVkU3RpY2tpZXMgPSBbXTtcclxuICAgIFxyXG4gICAgICAgICAgICB2YXIgX2xvb3AgPSBmdW5jdGlvbiBfbG9vcChpKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZSA9IG5vZGVMaXN0W2ldO1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAvLyBJZiBpdOKAmXMgbm90IGFuIEhUTUxFbGVtZW50IOKAkyBjcmVhdGUgYW4gZW1wdHkgZWxlbWVudCB0byBwcmVzZXJ2ZSAxLXRvLTFcclxuICAgICAgICAgICAgICAgIC8vIGNvcnJlbGF0aW9uIHdpdGggaW5wdXQgbGlzdFxyXG4gICAgICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGFkZGVkU3RpY2tpZXMucHVzaCh2b2lkIDApO1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnY29udGludWUnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAvLyBJZiBTdGlja3lmaWxsIGlzIGFscmVhZHkgYXBwbGllZCB0byB0aGUgbm9kZVxyXG4gICAgICAgICAgICAgICAgLy8gYWRkIGV4aXN0aW5nIHN0aWNreVxyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChzdGlja3kuX25vZGUgPT09IG5vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYWRkZWRTdGlja2llcy5wdXNoKHN0aWNreSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pKSByZXR1cm4gJ2NvbnRpbnVlJztcclxuICAgIFxyXG4gICAgICAgICAgICAgICAgLy8gQ3JlYXRlIGFuZCBhZGQgbmV3IHN0aWNreVxyXG4gICAgICAgICAgICAgICAgYWRkZWRTdGlja2llcy5wdXNoKG5ldyBTdGlja3kobm9kZSkpO1xyXG4gICAgICAgICAgICB9O1xyXG4gICAgXHJcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbm9kZUxpc3QubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgIHZhciBfcmV0ID0gX2xvb3AoaSk7XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIGlmIChfcmV0ID09PSAnY29udGludWUnKSBjb250aW51ZTtcclxuICAgICAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgICAgIHJldHVybiBhZGRlZFN0aWNraWVzO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgcmVmcmVzaEFsbDogZnVuY3Rpb24gcmVmcmVzaEFsbCgpIHtcclxuICAgICAgICAgICAgc3RpY2tpZXMuZm9yRWFjaChmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gc3RpY2t5LnJlZnJlc2goKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICByZW1vdmVPbmU6IGZ1bmN0aW9uIHJlbW92ZU9uZShub2RlKSB7XHJcbiAgICAgICAgICAgIC8vIENoZWNrIHdoZXRoZXIgaXTigJlzIGEgbm9kZVxyXG4gICAgICAgICAgICBpZiAoIShub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpKSB7XHJcbiAgICAgICAgICAgICAgICAvLyBNYXliZSBpdOKAmXMgYSBub2RlIGxpc3Qgb2Ygc29tZSBzb3J0P1xyXG4gICAgICAgICAgICAgICAgLy8gVGFrZSBmaXJzdCBub2RlIGZyb20gdGhlIGxpc3QgdGhlblxyXG4gICAgICAgICAgICAgICAgaWYgKG5vZGUubGVuZ3RoICYmIG5vZGVbMF0pIG5vZGUgPSBub2RlWzBdO2Vsc2UgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcbiAgICBcclxuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBzdGlja2llcyBib3VuZCB0byB0aGUgbm9kZXMgaW4gdGhlIGxpc3RcclxuICAgICAgICAgICAgc3RpY2tpZXMuc29tZShmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoc3RpY2t5Ll9ub2RlID09PSBub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgc3RpY2t5LnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZTogZnVuY3Rpb24gcmVtb3ZlKG5vZGVMaXN0KSB7XHJcbiAgICAgICAgICAgIC8vIElmIGl04oCZcyBhIG5vZGUgbWFrZSBhbiBhcnJheSBvZiBvbmUgbm9kZVxyXG4gICAgICAgICAgICBpZiAobm9kZUxpc3QgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgbm9kZUxpc3QgPSBbbm9kZUxpc3RdO1xyXG4gICAgICAgICAgICAvLyBDaGVjayBpZiB0aGUgYXJndW1lbnQgaXMgYW4gaXRlcmFibGUgb2Ygc29tZSBzb3J0XHJcbiAgICAgICAgICAgIGlmICghbm9kZUxpc3QubGVuZ3RoKSByZXR1cm47XHJcbiAgICBcclxuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBzdGlja2llcyBib3VuZCB0byB0aGUgbm9kZXMgaW4gdGhlIGxpc3RcclxuICAgIFxyXG4gICAgICAgICAgICB2YXIgX2xvb3AyID0gZnVuY3Rpb24gX2xvb3AyKGkpIHtcclxuICAgICAgICAgICAgICAgIHZhciBub2RlID0gbm9kZUxpc3RbaV07XHJcbiAgICBcclxuICAgICAgICAgICAgICAgIHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChzdGlja3kuX25vZGUgPT09IG5vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgc3RpY2t5LnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfTtcclxuICAgIFxyXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG5vZGVMaXN0Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICBfbG9vcDIoaSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZUFsbDogZnVuY3Rpb24gcmVtb3ZlQWxsKCkge1xyXG4gICAgICAgICAgICB3aGlsZSAoc3RpY2tpZXMubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgICAgICBzdGlja2llc1swXS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcbiAgICBcclxuICAgIC8qXHJcbiAgICAgKiA2LiBTZXR1cCBldmVudHMgKHVubGVzcyB0aGUgcG9seWZpbGwgd2FzIGRpc2FibGVkKVxyXG4gICAgICovXHJcbiAgICBmdW5jdGlvbiBpbml0KCkge1xyXG4gICAgICAgIC8vIFdhdGNoIGZvciBzY3JvbGwgcG9zaXRpb24gY2hhbmdlcyBhbmQgdHJpZ2dlciByZWNhbGMvcmVmcmVzaCBpZiBuZWVkZWRcclxuICAgICAgICBmdW5jdGlvbiBjaGVja1Njcm9sbCgpIHtcclxuICAgICAgICAgICAgaWYgKHdpbmRvdy5wYWdlWE9mZnNldCAhPSBzY3JvbGwubGVmdCkge1xyXG4gICAgICAgICAgICAgICAgc2Nyb2xsLnRvcCA9IHdpbmRvdy5wYWdlWU9mZnNldDtcclxuICAgICAgICAgICAgICAgIHNjcm9sbC5sZWZ0ID0gd2luZG93LnBhZ2VYT2Zmc2V0O1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICBTdGlja3lmaWxsLnJlZnJlc2hBbGwoKTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmICh3aW5kb3cucGFnZVlPZmZzZXQgIT0gc2Nyb2xsLnRvcCkge1xyXG4gICAgICAgICAgICAgICAgc2Nyb2xsLnRvcCA9IHdpbmRvdy5wYWdlWU9mZnNldDtcclxuICAgICAgICAgICAgICAgIHNjcm9sbC5sZWZ0ID0gd2luZG93LnBhZ2VYT2Zmc2V0O1xyXG4gICAgXHJcbiAgICAgICAgICAgICAgICAvLyByZWNhbGMgcG9zaXRpb24gZm9yIGFsbCBzdGlja2llc1xyXG4gICAgICAgICAgICAgICAgc3RpY2tpZXMuZm9yRWFjaChmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fcmVjYWxjUG9zaXRpb24oKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgY2hlY2tTY3JvbGwoKTtcclxuICAgICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgY2hlY2tTY3JvbGwpO1xyXG4gICAgXHJcbiAgICAgICAgLy8gV2F0Y2ggZm9yIHdpbmRvdyByZXNpemVzIGFuZCBkZXZpY2Ugb3JpZW50YXRpb24gY2hhbmdlcyBhbmQgdHJpZ2dlciByZWZyZXNoXHJcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIFN0aWNreWZpbGwucmVmcmVzaEFsbCk7XHJcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ29yaWVudGF0aW9uY2hhbmdlJywgU3RpY2t5ZmlsbC5yZWZyZXNoQWxsKTtcclxuICAgIFxyXG4gICAgICAgIC8vRmFzdCBkaXJ0eSBjaGVjayBmb3IgbGF5b3V0IGNoYW5nZXMgZXZlcnkgNTAwbXNcclxuICAgICAgICB2YXIgZmFzdENoZWNrVGltZXIgPSB2b2lkIDA7XHJcbiAgICBcclxuICAgICAgICBmdW5jdGlvbiBzdGFydEZhc3RDaGVja1RpbWVyKCkge1xyXG4gICAgICAgICAgICBmYXN0Q2hlY2tUaW1lciA9IHNldEludGVydmFsKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIHN0aWNraWVzLmZvckVhY2goZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBzdGlja3kuX2Zhc3RDaGVjaygpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH0sIDUwMCk7XHJcbiAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgZnVuY3Rpb24gc3RvcEZhc3RDaGVja1RpbWVyKCkge1xyXG4gICAgICAgICAgICBjbGVhckludGVydmFsKGZhc3RDaGVja1RpbWVyKTtcclxuICAgICAgICB9XHJcbiAgICBcclxuICAgICAgICB2YXIgZG9jSGlkZGVuS2V5ID0gdm9pZCAwO1xyXG4gICAgICAgIHZhciB2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lID0gdm9pZCAwO1xyXG4gICAgXHJcbiAgICAgICAgaWYgKCdoaWRkZW4nIGluIGRvY3VtZW50KSB7XHJcbiAgICAgICAgICAgIGRvY0hpZGRlbktleSA9ICdoaWRkZW4nO1xyXG4gICAgICAgICAgICB2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lID0gJ3Zpc2liaWxpdHljaGFuZ2UnO1xyXG4gICAgICAgIH0gZWxzZSBpZiAoJ3dlYmtpdEhpZGRlbicgaW4gZG9jdW1lbnQpIHtcclxuICAgICAgICAgICAgZG9jSGlkZGVuS2V5ID0gJ3dlYmtpdEhpZGRlbic7XHJcbiAgICAgICAgICAgIHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUgPSAnd2Via2l0dmlzaWJpbGl0eWNoYW5nZSc7XHJcbiAgICAgICAgfVxyXG4gICAgXHJcbiAgICAgICAgaWYgKHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUpIHtcclxuICAgICAgICAgICAgaWYgKCFkb2N1bWVudFtkb2NIaWRkZW5LZXldKSBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICBcclxuICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoZG9jdW1lbnRbZG9jSGlkZGVuS2V5XSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHN0b3BGYXN0Q2hlY2tUaW1lcigpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0gZWxzZSBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICB9XHJcbiAgICBcclxuICAgIGlmICghc2VwcHVrdSkgaW5pdCgpO1xyXG4gICAgXHJcbiAgICAvKlxyXG4gICAgICogNy4gRXhwb3NlIFN0aWNreWZpbGxcclxuICAgICAqL1xyXG4gICAgaWYgKHR5cGVvZiBtb2R1bGUgIT0gJ3VuZGVmaW5lZCcgJiYgbW9kdWxlLmV4cG9ydHMpIHtcclxuICAgICAgICBtb2R1bGUuZXhwb3J0cyA9IFN0aWNreWZpbGw7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgIHdpbmRvdy5TdGlja3lmaWxsID0gU3RpY2t5ZmlsbDtcclxuICAgIH1cclxuICAgIFxyXG59KSh3aW5kb3csIGRvY3VtZW50KTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBnO1xyXG5cclxuLy8gVGhpcyB3b3JrcyBpbiBub24tc3RyaWN0IG1vZGVcclxuZyA9IChmdW5jdGlvbigpIHtcclxuXHRyZXR1cm4gdGhpcztcclxufSkoKTtcclxuXHJcbnRyeSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiBldmFsIGlzIGFsbG93ZWQgKHNlZSBDU1ApXHJcblx0ZyA9IGcgfHwgRnVuY3Rpb24oXCJyZXR1cm4gdGhpc1wiKSgpIHx8ICgxLGV2YWwpKFwidGhpc1wiKTtcclxufSBjYXRjaChlKSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiB0aGUgd2luZG93IHJlZmVyZW5jZSBpcyBhdmFpbGFibGVcclxuXHRpZih0eXBlb2Ygd2luZG93ID09PSBcIm9iamVjdFwiKVxyXG5cdFx0ZyA9IHdpbmRvdztcclxufVxyXG5cclxuLy8gZyBjYW4gc3RpbGwgYmUgdW5kZWZpbmVkLCBidXQgbm90aGluZyB0byBkbyBhYm91dCBpdC4uLlxyXG4vLyBXZSByZXR1cm4gdW5kZWZpbmVkLCBpbnN0ZWFkIG9mIG5vdGhpbmcgaGVyZSwgc28gaXQnc1xyXG4vLyBlYXNpZXIgdG8gaGFuZGxlIHRoaXMgY2FzZS4gaWYoIWdsb2JhbCkgeyAuLi59XHJcblxyXG5tb2R1bGUuZXhwb3J0cyA9IGc7XHJcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vICh3ZWJwYWNrKS9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvd2VicGFjay9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwic291cmNlUm9vdCI6IiJ9