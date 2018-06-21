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

var TestStartForm = function () {
    function TestStartForm(element) {
        var _this = this;

        _classCallCheck(this, TestStartForm);

        this.document = element.ownerDocument;
        this.element = element;
        this.submitButtons = [];

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
/***/ (function(module, exports, __webpack_require__) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SortControl = __webpack_require__(/*! ../model/element/sort-control */ "./assets/js/model/element/sort-control.js");

var SortControlCollection = function () {
    /**
     * @param {SortControl[]} controls
     */
    function SortControlCollection(controls) {
        _classCallCheck(this, SortControlCollection);

        this.controls = controls;
    }

    _createClass(SortControlCollection, [{
        key: 'setSorted',
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

                this.summaryData = event.detail.response;

                var state = this.summaryData.test.state;
                var taskCount = this.summaryData.remote_test.task_count;
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
            this.taskListPagination = new TaskListPagination(this.pageLength, this.summaryData.remote_test.task_count);

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

        var byPageContainerElement = document.querySelector('.by-page-container');
        var byErrorContainerElement = document.querySelector('.by-error-container');

        this.byPageList = byPageContainerElement ? new ByPageList(byPageContainerElement) : null;
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
                var completionPercent = response.completion_percent;

                this.document.body.setAttribute('data-remaining-tasks-to-retrieve-count', response.remaining_tasks_to_retrieve_count);
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

var TestResults = function () {
    /**
     * @param {Document} document
     */
    function TestResults(document) {
        _classCallCheck(this, TestResults);

        this.document = document;
        this.httpAuthenticationOptions = HttpAuthenticationOptionsFactory.create(document.querySelector('.http-authentication-test-option'));
        this.cookieOptions = CookieOptionsFactory.create(document.querySelector('.cookies-test-option'));
        this.testLockUnlock = new TestLockUnlock(document.querySelector('.btn-lock-unlock'));
        this.retestForm = document.querySelector('.retest-form');
        this.retestButton = new FormButton(this.retestForm.querySelector('button[type=submit]'));
        this.taskTypeSummaryBadgeCollection = new BadgeCollection(document.querySelectorAll('.task-type-summary .badge'));
    }

    _createClass(TestResults, [{
        key: 'init',
        value: function init() {
            var _this = this;

            unavailableTaskTypeModalLauncher(this.document.querySelectorAll('.task-type-option.not-available'));
            this.httpAuthenticationOptions.init();
            this.cookieOptions.init();
            this.testLockUnlock.init();
            this.taskTypeSummaryBadgeCollection.applyUniformWidth();

            this.retestForm.addEventListener('submit', function () {
                _this.retestButton.deEmphasize();
                _this.retestButton.markAsBusy();
            });
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
            var selector = '.csspositionsticky';
            var stickyNavJsClass = 'js-sticky-nav';
            var stickyNavJsSelector = '.' + stickyNavJsClass;

            var stickyNav = document.querySelector(stickyNavJsSelector);

            if (document.querySelector(selector)) {
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
            var remoteTest = summaryData.remote_test;

            this.progressBar.setCompletionPercent(remoteTest.completion_percent);
            this.progressBar.setStyle(summaryData.test.state === 'crawling' ? 'warning' : 'default');
            this.stateLabel.innerText = summaryData.state_label;
            this.taskQueues.render(remoteTest.task_count, remoteTest.task_count_by_state);

            if (remoteTest.ammendments && remoteTest.ammendments.length > 0) {
                this.element.dispatchEvent(new CustomEvent(Summary.getRenderAmmendmentEventName(), {
                    detail: remoteTest.ammendments[0]
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
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ByErrorList = function () {
    /**
     * @param {Element} element
     */
    function ByErrorList(element) {
        _classCallCheck(this, ByErrorList);

        this.element = element;
    }

    _createClass(ByErrorList, [{
        key: 'init',
        value: function init() {
            console.log('init');
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgNDVmMjI0MTFhYTg3NGE4ZDRiYTIiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz80ZmIwIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWJ1dHRvbi1zcGlubmVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWZpZWxkLWZvY3VzZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGFsLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2JhZGdlLWNvbGxlY3Rpb24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2Nvb2tpZS1vcHRpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hbGVydC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9jb29raWUtb3B0aW9ucy1tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvaWNvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9jcmF3bGluZy1saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcmVwYXJpbmctbGlzdGVkLXRlc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnQtY29udHJvbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLXF1ZXVlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWVzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2suanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1sb2NrLXVubG9jay5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9zb3J0LWNvbnRyb2wtY29sbGVjdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvc29ydGFibGUtaXRlbS1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL2Rhc2hib2FyZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LWhpc3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1wcm9ncmVzcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC1jYXJkLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcG9seWZpbGwvY3VzdG9tLWV2ZW50LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wb2x5ZmlsbC9vYmplY3QtZW50cmllcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2Nyb2xsLXRvLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2VydmljZXMvaHR0cC1jbGllbnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL2xpc3RlZC10ZXN0LWZhY3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL3N0cmlwZS1oYW5kbGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy90ZXN0LXJlc3VsdC1yZXRyaWV2ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtaGlzdG9yeS9tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L3N1Z2dlc3Rpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3N1bW1hcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1pZC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC1wYWdpbmF0b3IuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvYnktcGFnZS1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL3NvcnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1hY2NvdW50LWNhcmQvZm9ybS12YWxpZGF0b3IuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItYW5jaG9yLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWl0ZW0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1hY2NvdW50L3Njcm9sbHNweS5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvYXdlc29tcGxldGUvYXdlc29tcGxldGUuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2Jvb3RzdHJhcC5uYXRpdmUvZGlzdC9ib290c3RyYXAtbmF0aXZlLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9zbW9vdGgtc2Nyb2xsL2Rpc3Qvc21vb3RoLXNjcm9sbC5taW4uanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL3N0aWNreWZpbGxqcy9kaXN0L3N0aWNreWZpbGwuanMiLCJ3ZWJwYWNrOi8vLyh3ZWJwYWNrKS9idWlsZGluL2dsb2JhbC5qcyJdLCJuYW1lcyI6WyJyZXF1aXJlIiwiZm9ybUJ1dHRvblNwaW5uZXIiLCJmb3JtRmllbGRGb2N1c2VyIiwibW9kYWxDb250cm9sIiwiY29sbGFwc2VDb250cm9sQ2FyZXQiLCJEYXNoYm9hcmQiLCJ0ZXN0SGlzdG9yeVBhZ2UiLCJUZXN0UmVzdWx0cyIsIlVzZXJBY2NvdW50IiwiVXNlckFjY291bnRDYXJkIiwiQWxlcnRGYWN0b3J5IiwiVGVzdFByb2dyZXNzIiwiVGVzdFJlc3VsdHNQcmVwYXJpbmciLCJUZXN0UmVzdWx0c0J5VGFza1R5cGUiLCJvbkRvbUNvbnRlbnRMb2FkZWQiLCJib2R5IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsIml0ZW0iLCJmb2N1c2VkRmllbGQiLCJxdWVyeVNlbGVjdG9yIiwiZm9yRWFjaCIsImNhbGwiLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9ybUVsZW1lbnQiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsImRhc2hib2FyZCIsImluaXQiLCJ0ZXN0UHJvZ3Jlc3MiLCJ0ZXN0UmVzdWx0cyIsInRlc3RSZXN1bHRzQnlUYXNrVHlwZSIsInRlc3RSZXN1bHRzUHJlcGFyaW5nIiwidXNlckFjY291bnQiLCJ3aW5kb3ciLCJ1c2VyQWNjb3VudENhcmQiLCJhbGVydEVsZW1lbnQiLCJjcmVhdGVGcm9tRWxlbWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJtb2R1bGUiLCJleHBvcnRzIiwiY29udHJvbHMiLCJjb250cm9sSWNvbkNsYXNzIiwiY2FyZXRVcENsYXNzIiwiY2FyZXREb3duQ2xhc3MiLCJjb250cm9sQ29sbGFwc2VkQ2xhc3MiLCJjcmVhdGVDb250cm9sSWNvbiIsImNvbnRyb2wiLCJjb250cm9sSWNvbiIsImNyZWF0ZUVsZW1lbnQiLCJhZGQiLCJ0b2dnbGVDYXJldCIsInJlbW92ZSIsImhhbmRsZUNvbnRyb2wiLCJldmVudE5hbWVTaG93biIsImV2ZW50TmFtZUhpZGRlbiIsImNvbGxhcHNpYmxlRWxlbWVudCIsImdldEVsZW1lbnRCeUlkIiwiZ2V0QXR0cmlidXRlIiwicmVwbGFjZSIsImFwcGVuZCIsInNob3duSGlkZGVuRXZlbnRMaXN0ZW5lciIsImJpbmQiLCJpIiwibGVuZ3RoIiwiTGlzdGVkVGVzdENvbGxlY3Rpb24iLCJIdHRwQ2xpZW50IiwiUmVjZW50VGVzdExpc3QiLCJlbGVtZW50Iiwib3duZXJEb2N1bWVudCIsInNvdXJjZVVybCIsImxpc3RlZFRlc3RDb2xsZWN0aW9uIiwicmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24iLCJnZXRSZXRyaWV2ZWRFdmVudE5hbWUiLCJfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIiwiX3JldHJpZXZlVGVzdHMiLCJldmVudCIsIl9wYXJzZVJldHJpZXZlZFRlc3RzIiwiZGV0YWlsIiwicmVzcG9uc2UiLCJfcmVuZGVyUmV0cmlldmVkVGVzdHMiLCJzZXRUaW1lb3V0IiwiX3JlbW92ZVNwaW5uZXIiLCJsaXN0ZWRUZXN0IiwicGFyZW50Tm9kZSIsInJlbW92ZUNoaWxkIiwicmV0cmlldmVkTGlzdGVkVGVzdCIsImdldCIsImdldFRlc3RJZCIsImdldFR5cGUiLCJyZXBsYWNlQ2hpbGQiLCJlbmFibGUiLCJyZW5kZXJGcm9tTGlzdGVkVGVzdCIsImluc2VydEFkamFjZW50RWxlbWVudCIsInJldHJpZXZlZFRlc3RzTWFya3VwIiwidHJpbSIsInJldHJpZXZlZFRlc3RDb250YWluZXIiLCJpbm5lckhUTUwiLCJjcmVhdGVGcm9tTm9kZUxpc3QiLCJnZXRUZXh0Iiwic3Bpbm5lciIsIkZvcm1CdXR0b24iLCJUZXN0U3RhcnRGb3JtIiwic3VibWl0QnV0dG9ucyIsInN1Ym1pdEJ1dHRvbiIsInB1c2giLCJfc3VibWl0RXZlbnRMaXN0ZW5lciIsIl9zdWJtaXRCdXR0b25FdmVudExpc3RlbmVyIiwiZGVFbXBoYXNpemUiLCJfcmVwbGFjZVVuY2hlY2tlZENoZWNrYm94ZXNXaXRoSGlkZGVuRmllbGRzIiwiZGlzYWJsZSIsImJ1dHRvbkVsZW1lbnQiLCJ0YXJnZXQiLCJidXR0b24iLCJtYXJrQXNCdXN5IiwiaW5wdXQiLCJjaGVja2VkIiwiaGlkZGVuSW5wdXQiLCJzZXRBdHRyaWJ1dGUiLCJ2YWx1ZSIsImZvcm0iLCJpbnB1dFZhbHVlIiwiZm9jdXMiLCJjb250cm9sRWxlbWVudHMiLCJpbml0aWFsaXplIiwiY29udHJvbEVsZW1lbnQiLCJCYWRnZUNvbGxlY3Rpb24iLCJiYWRnZXMiLCJncmVhdGVzdFdpZHRoIiwiX2Rlcml2ZUdyZWF0ZXN0V2lkdGgiLCJiYWRnZSIsInN0eWxlIiwid2lkdGgiLCJvZmZzZXRXaWR0aCIsIkNvb2tpZU9wdGlvbnNNb2RhbCIsIkNvb2tpZU9wdGlvbnMiLCJjb29raWVPcHRpb25zTW9kYWwiLCJhY3Rpb25CYWRnZSIsInN0YXR1c0VsZW1lbnQiLCJtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciIsImlzRW1wdHkiLCJpbm5lclRleHQiLCJtYXJrTm90RW5hYmxlZCIsIm1hcmtFbmFibGVkIiwiZ2V0T3BlbmVkRXZlbnROYW1lIiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwiZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUiLCJnZXRDbG9zZWRFdmVudE5hbWUiLCJnZXRNb2RhbENsb3NlZEV2ZW50TmFtZSIsIkFjdGlvbkJhZGdlIiwiYnNuIiwiQWxlcnQiLCJjbG9zZUJ1dHRvbiIsIl9jbG9zZUJ1dHRvbkNsaWNrRXZlbnRIYW5kbGVyIiwiX3JlbW92ZVByZXNlbnRhdGlvbmFsQ2xhc3NlcyIsImNvbnRhaW5lciIsImFwcGVuZENoaWxkIiwicHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCIsImNsYXNzTmFtZSIsImluZGV4IiwiaW5kZXhPZiIsInJlbGF0ZWRGaWVsZElkIiwicmVsYXRlZEZpZWxkIiwiYnNuQWxlcnQiLCJjbG9zZSIsImFkZEJ1dHRvbiIsImFwcGx5QnV0dG9uIiwiX2FkZFJlbW92ZUFjdGlvbnMiLCJfYWRkRXZlbnRMaXN0ZW5lcnMiLCJjb29raWVEYXRhUm93Q291bnQiLCJ0YWJsZUJvZHkiLCJyZW1vdmVBY3Rpb24iLCJ0YWJsZVJvdyIsIm5hbWVJbnB1dCIsInR5cGUiLCJrZXkiLCJjbGljayIsInNob3duRXZlbnRMaXN0ZW5lciIsInByZXZpb3VzVGFibGVCb2R5IiwiY2xvbmVOb2RlIiwiaGlkZGVuRXZlbnRMaXN0ZW5lciIsImNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwiYWRkQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwiX2NyZWF0ZVRhYmxlUm93IiwiX2NyZWF0ZVJlbW92ZUFjdGlvbiIsIl9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJfaW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lciIsInJlbW92ZUNvbnRhaW5lciIsIl9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdmVBY3Rpb25Db250YWluZXIiLCJuZXh0Q29va2llSW5kZXgiLCJsYXN0VGFibGVSb3ciLCJ2YWx1ZUlucHV0IiwicGFyc2VJbnQiLCJJY29uIiwiaWNvbkVsZW1lbnQiLCJnZXRTZWxlY3RvciIsImljb24iLCJzZXRCdXN5Iiwic2V0QXZhaWxhYmxlIiwidW5EZUVtcGhhc2l6ZSIsInNldFN1Y2Nlc3NmdWwiLCJyZW1vdmVBdHRyaWJ1dGUiLCJIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwiLCJ1c2VybmFtZUlucHV0IiwicGFzc3dvcmRJbnB1dCIsImNsZWFyQnV0dG9uIiwicHJldmlvdXNVc2VybmFtZSIsInByZXZpb3VzUGFzc3dvcmQiLCJ1c2VybmFtZSIsInBhc3N3b3JkIiwiZm9jdXNlZElucHV0IiwiY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzIiwiYWN0aXZlSWNvbkNsYXNzIiwiY2xhc3Nlc1RvUmV0YWluIiwiZ2V0Q2xhc3MiLCJpbmNsdWRlcyIsIlByb2dyZXNzaW5nTGlzdGVkVGVzdCIsIkNyYXdsaW5nTGlzdGVkVGVzdCIsIkxpc3RlZFRlc3QiLCJpc0ZpbmlzaGVkIiwiZ2V0U3RhdGUiLCJUZXN0UmVzdWx0UmV0cmlldmVyIiwiUHJlcGFyaW5nTGlzdGVkVGVzdCIsIl9pbml0aWFsaXNlUmVzdWx0UmV0cmlldmVyIiwicHJlcGFyaW5nRWxlbWVudCIsInRlc3RSZXN1bHRzUmV0cmlldmVyIiwicmV0cmlldmVkRXZlbnQiLCJQcm9ncmVzc0JhciIsInByb2dyZXNzQmFyRWxlbWVudCIsInByb2dyZXNzQmFyIiwiY29tcGxldGlvblBlcmNlbnQiLCJwYXJzZUZsb2F0IiwiZ2V0Q29tcGxldGlvblBlcmNlbnQiLCJzZXRDb21wbGV0aW9uUGVyY2VudCIsIlNvcnRDb250cm9sIiwia2V5cyIsIkpTT04iLCJwYXJzZSIsIl9jbGlja0V2ZW50TGlzdGVuZXIiLCJnZXRTb3J0UmVxdWVzdGVkRXZlbnROYW1lIiwiU29ydGFibGVJdGVtIiwic29ydFZhbHVlcyIsIlRhc2siLCJUYXNrTGlzdCIsInBhZ2VJbmRleCIsInRhc2tzIiwidGFza0VsZW1lbnQiLCJ0YXNrIiwiZ2V0SWQiLCJzdGF0ZXMiLCJzdGF0ZXNMZW5ndGgiLCJzdGF0ZUluZGV4Iiwic3RhdGUiLCJjb25jYXQiLCJnZXRUYXNrc0J5U3RhdGUiLCJPYmplY3QiLCJ0YXNrSWQiLCJ1cGRhdGVkVGFza0xpc3QiLCJ1cGRhdGVkVGFzayIsInVwZGF0ZUZyb21UYXNrIiwiVGFza1F1ZXVlIiwibGFiZWwiLCJUYXNrUXVldWVzIiwicXVldWVzIiwicXVldWVFbGVtZW50IiwicXVldWUiLCJnZXRRdWV1ZUlkIiwidGFza0NvdW50IiwidGFza0NvdW50QnlTdGF0ZSIsImdldFdpZHRoRm9yU3RhdGUiLCJoYXNPd25Qcm9wZXJ0eSIsIk1hdGgiLCJjZWlsIiwic2V0VmFsdWUiLCJzZXRXaWR0aCIsIlRlc3RBbGVydENvbnRhaW5lciIsImFsZXJ0IiwiY3JlYXRlRnJvbUNvbnRlbnQiLCJzZXRTdHlsZSIsIndyYXBDb250ZW50SW5Db250YWluZXIiLCJUZXN0TG9ja1VubG9jayIsImRhdGEiLCJsb2NrZWQiLCJ1bmxvY2tlZCIsImFjdGlvbiIsImRlc2NyaXB0aW9uIiwiX3JlbmRlciIsIl90b2dnbGUiLCJzdGF0ZURhdGEiLCJwcmV2ZW50RGVmYXVsdCIsInBvc3QiLCJ1cmwiLCJIdHRwQXV0aGVudGljYXRpb25PcHRpb25zIiwiaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsIiwiTGlzdGVkVGVzdEZhY3RvcnkiLCJsaXN0ZWRUZXN0cyIsImNvbnRhaW5zVGVzdElkIiwidGVzdElkIiwiY2FsbGJhY2siLCJub2RlTGlzdCIsImNvbGxlY3Rpb24iLCJsaXN0ZWRUZXN0RWxlbWVudCIsIlNvcnRDb250cm9sQ29sbGVjdGlvbiIsInNldFNvcnRlZCIsInNldE5vdFNvcnRlZCIsIlNvcnRhYmxlSXRlbUxpc3QiLCJpdGVtcyIsInNvcnRlZEl0ZW1zIiwic29ydGFibGVJdGVtIiwicG9zaXRpb24iLCJ2YWx1ZXMiLCJnZXRTb3J0VmFsdWUiLCJOdW1iZXIiLCJpc0ludGVnZXIiLCJ0b1N0cmluZyIsImpvaW4iLCJzb3J0IiwiX2NvbXBhcmVGdW5jdGlvbiIsImluZGV4SXRlbSIsImEiLCJiIiwidW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIiLCJIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeSIsIkNvb2tpZU9wdGlvbnNGYWN0b3J5IiwidGVzdFN0YXJ0Rm9ybSIsInJlY2VudFRlc3RMaXN0IiwiaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyIsImNyZWF0ZSIsImNvb2tpZU9wdGlvbnMiLCJNb2RhbCIsIlN1Z2dlc3Rpb25zIiwibW9kYWxJZCIsImZpbHRlck9wdGlvbnNTZWxlY3RvciIsIm1vZGFsRWxlbWVudCIsImZpbHRlck9wdGlvbnNFbGVtZW50IiwibW9kYWwiLCJzdWdnZXN0aW9ucyIsInN1Z2dlc3Rpb25zTG9hZGVkRXZlbnRMaXN0ZW5lciIsInNldFN1Z2dlc3Rpb25zIiwiZmlsdGVyQ2hhbmdlZEV2ZW50TGlzdGVuZXIiLCJmaWx0ZXIiLCJzZWFyY2giLCJjdXJyZW50U2VhcmNoIiwibG9jYXRpb24iLCJsb2FkZWRFdmVudE5hbWUiLCJmaWx0ZXJDaGFuZ2VkRXZlbnROYW1lIiwicmV0cmlldmUiLCJTdW1tYXJ5IiwiVGFza0xpc3RQYWdpbmF0aW9uIiwicGFnZUxlbmd0aCIsInN1bW1hcnkiLCJ0YXNrTGlzdEVsZW1lbnQiLCJ0YXNrTGlzdCIsImFsZXJ0Q29udGFpbmVyIiwidGFza0xpc3RQYWdpbmF0aW9uIiwidGFza0xpc3RJc0luaXRpYWxpemVkIiwic3VtbWFyeURhdGEiLCJfcmVmcmVzaFN1bW1hcnkiLCJnZXRSZW5kZXJBbW1lbmRtZW50RXZlbnROYW1lIiwicmVuZGVyVXJsTGltaXROb3RpZmljYXRpb24iLCJnZXRJbml0aWFsaXplZEV2ZW50TmFtZSIsIl90YXNrTGlzdEluaXRpYWxpemVkRXZlbnRMaXN0ZW5lciIsImdldFNlbGVjdFBhZ2VFdmVudE5hbWUiLCJzZXRDdXJyZW50UGFnZUluZGV4Iiwic2VsZWN0UGFnZSIsInJlbmRlciIsImdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSIsIm1heCIsImN1cnJlbnRQYWdlSW5kZXgiLCJnZXRTZWxlY3ROZXh0UGFnZUV2ZW50TmFtZSIsIm1pbiIsInBhZ2VDb3VudCIsInJlcXVlc3RJZCIsInJlcXVlc3QiLCJyZXNwb25zZVVSTCIsImhyZWYiLCJ0ZXN0IiwicmVtb3RlX3Rlc3QiLCJ0YXNrX2NvdW50IiwiaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyIsIl9zZXRCb2R5Sm9iQ2xhc3MiLCJpc0luaXRpYWxpemluZyIsImlzUmVxdWlyZWQiLCJpc1JlbmRlcmVkIiwiX2NyZWF0ZVBhZ2luYXRpb25FbGVtZW50Iiwic2V0UGFnaW5hdGlvbkVsZW1lbnQiLCJfYWRkUGFnaW5hdGlvbkV2ZW50TGlzdGVuZXJzIiwiY3JlYXRlTWFya3VwIiwic3VtbWFyeVVybCIsIm5vdyIsIkRhdGUiLCJnZXRKc29uIiwiZ2V0VGltZSIsImJvZHlDbGFzc0xpc3QiLCJ0ZXN0U3RhdGUiLCJqb2JDbGFzc1ByZWZpeCIsIkJ5UGFnZUxpc3QiLCJCeUVycm9yTGlzdCIsImJ5UGFnZUNvbnRhaW5lckVsZW1lbnQiLCJieUVycm9yQ29udGFpbmVyRWxlbWVudCIsImJ5UGFnZUxpc3QiLCJieUVycm9yTGlzdCIsInVucmV0cmlldmVkUmVtb3RlVGFza0lkc1VybCIsInJlc3VsdHNSZXRyaWV2ZVVybCIsInJldHJpZXZhbFN0YXRzVXJsIiwicmVzdWx0c1VybCIsImNvbXBsZXRpb25QZXJjZW50VmFsdWUiLCJsb2NhbFRhc2tDb3VudCIsInJlbW90ZVRhc2tDb3VudCIsIl9jaGVja0NvbXBsZXRpb25TdGF0dXMiLCJfcmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbiIsIl9yZXRyaWV2ZVJlbW90ZVRhc2tDb2xsZWN0aW9uIiwiX3JldHJpZXZlUmV0cmlldmFsU3RhdHMiLCJjb21wbGV0aW9uX3BlcmNlbnQiLCJyZW1haW5pbmdfdGFza3NfdG9fcmV0cmlldmVfY291bnQiLCJsb2NhbF90YXNrX2NvdW50IiwicmVtb3RlX3Rhc2tfY291bnQiLCJyZW1vdGVUYXNrSWRzIiwidGVzdExvY2tVbmxvY2siLCJyZXRlc3RGb3JtIiwicmV0ZXN0QnV0dG9uIiwidGFza1R5cGVTdW1tYXJ5QmFkZ2VDb2xsZWN0aW9uIiwiYXBwbHlVbmlmb3JtV2lkdGgiLCJGb3JtIiwiRm9ybVZhbGlkYXRvciIsIlN0cmlwZUhhbmRsZXIiLCJzdHJpcGVKcyIsIlN0cmlwZSIsImZvcm1WYWxpZGF0b3IiLCJzdHJpcGVIYW5kbGVyIiwic2V0U3RyaXBlUHVibGlzaGFibGVLZXkiLCJnZXRTdHJpcGVQdWJsaXNoYWJsZUtleSIsInVwZGF0ZUNhcmQiLCJnZXRVcGRhdGVDYXJkRXZlbnROYW1lIiwiY3JlYXRlQ2FyZFRva2VuU3VjY2VzcyIsImdldENyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudE5hbWUiLCJjcmVhdGVDYXJkVG9rZW5GYWlsdXJlIiwiZ2V0Q3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TmFtZSIsIl91cGRhdGVDYXJkRXZlbnRMaXN0ZW5lciIsIl9jcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnRMaXN0ZW5lciIsIl9jcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnRMaXN0ZW5lciIsInVwZGF0ZUNhcmRFdmVudCIsImNyZWF0ZUNhcmRUb2tlbiIsInN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50IiwicmVxdWVzdFVybCIsInBhdGhuYW1lIiwiaWQiLCJYTUxIdHRwUmVxdWVzdCIsIm9wZW4iLCJyZXNwb25zZVR5cGUiLCJzZXRSZXF1ZXN0SGVhZGVyIiwibWFya0FzQXZhaWxhYmxlIiwibWFya1N1Y2NlZWRlZCIsInRoaXNfdXJsIiwiaGFuZGxlUmVzcG9uc2VFcnJvciIsInVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9wYXJhbSIsInVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9tZXNzYWdlIiwic2VuZCIsInJlc3BvbnNlRXJyb3IiLCJjcmVhdGVSZXNwb25zZUVycm9yIiwiZXJyb3IiLCJwYXJhbSIsIlNjcm9sbFNweSIsIk5hdkJhckxpc3QiLCJTY3JvbGxUbyIsIlN0aWNreUZpbGwiLCJzY3JvbGxPZmZzZXQiLCJzY3JvbGxTcHlPZmZzZXQiLCJzaWRlTmF2RWxlbWVudCIsIm5hdkJhckxpc3QiLCJzY3JvbGxzcHkiLCJ0YXJnZXRJZCIsImhhc2giLCJyZWxhdGVkQW5jaG9yIiwiZ29UbyIsInNjcm9sbFRvIiwic2VsZWN0b3IiLCJzdGlja3lOYXZKc0NsYXNzIiwic3RpY2t5TmF2SnNTZWxlY3RvciIsInN0aWNreU5hdiIsImFkZE9uZSIsInNweSIsIl9hcHBseVBvc2l0aW9uU3RpY2t5UG9seWZpbGwiLCJfYXBwbHlJbml0aWFsU2Nyb2xsIiwicGFyYW1zIiwiYnViYmxlcyIsImNhbmNlbGFibGUiLCJ1bmRlZmluZWQiLCJjdXN0b21FdmVudCIsImNyZWF0ZUV2ZW50IiwiaW5pdEN1c3RvbUV2ZW50IiwicHJvdG90eXBlIiwiRXZlbnQiLCJlbnRyaWVzIiwib2JqIiwib3duUHJvcHMiLCJyZXNBcnJheSIsIkFycmF5IiwiU21vb3RoU2Nyb2xsIiwib2Zmc2V0Iiwic2Nyb2xsIiwiYW5pbWF0ZVNjcm9sbCIsIm9mZnNldFRvcCIsIl91cGRhdGVIaXN0b3J5IiwiaGlzdG9yeSIsInB1c2hTdGF0ZSIsImVycm9yQ29udGVudCIsImVsZW1lbnRJbm5lckhUTUwiLCJtZXRob2QiLCJyZXF1ZXN0SGVhZGVycyIsInJlYWxSZXF1ZXN0SGVhZGVycyIsInN0cmlwZVB1Ymxpc2hhYmxlS2V5Iiwic2V0UHVibGlzaGFibGVLZXkiLCJjYXJkIiwiY3JlYXRlVG9rZW4iLCJzdGF0dXMiLCJpc0Vycm9yUmVzcG9uc2UiLCJldmVudE5hbWUiLCJzdGF0dXNVcmwiLCJ1bnJldHJpZXZlZFRhc2tJZHNVcmwiLCJyZXRyaWV2ZVRhc2tzVXJsIiwiX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzIiwiX3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5IiwiX2Rpc3BsYXlQcmVwYXJpbmdTdW1tYXJ5IiwiX3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24iLCJfcmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24iLCJyZXRyaWV2ZWRTdW1tYXJ5Q29udGFpbmVyIiwic3RhdHVzRGF0YSIsIl9jcmVhdGVQcmVwYXJpbmdTdW1tYXJ5IiwicHJldmlvdXNGaWx0ZXIiLCJhcHBseUZpbHRlciIsImF3ZXNvbWVwbGV0ZSIsIkF3ZXNvbXBsZXRlIiwibGlzdCIsImhpZGVFdmVudExpc3RlbmVyIiwiV0lMRENBUkQiLCJmaWx0ZXJJc0VtcHR5Iiwic3VnZ2VzdGlvbnNJbmNsdWRlc0ZpbHRlciIsImZpbHRlcklzV2lsZGNhcmRQcmVmaXhlZCIsImNoYXJBdCIsImZpbHRlcklzV2lsZGNhcmRTdWZmaXhlZCIsInNsaWNlIiwiYXBwbHlCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZXF1ZXN0T25sb2FkSGFuZGxlciIsInJlc3BvbnNlVGV4dCIsIm9ubG9hZCIsImNhbmNlbEFjdGlvbiIsImNhbmNlbENyYXdsQWN0aW9uIiwic3RhdGVMYWJlbCIsInRhc2tRdWV1ZXMiLCJfY2FuY2VsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIiwiX2NhbmNlbENyYXdsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIiwicmVtb3RlVGVzdCIsInN0YXRlX2xhYmVsIiwidGFza19jb3VudF9ieV9zdGF0ZSIsImFtbWVuZG1lbnRzIiwiVGFza0lkTGlzdCIsInRhc2tJZHMiLCJwYWdlTnVtYmVyIiwicGFnZUFjdGlvbnMiLCJwcmV2aW91c0FjdGlvbiIsIm5leHRBY3Rpb24iLCJhY3Rpb25Db250YWluZXIiLCJyb2xlIiwibWFya3VwIiwic3RhcnRJbmRleCIsImVuZEluZGV4IiwicGFnZUFjdGlvbiIsImlzQWN0aXZlIiwicGFyZW50RWxlbWVudCIsIlRhc2tMaXN0TW9kZWwiLCJ0YXNrSWRMaXN0IiwidGFza0xpc3RNb2RlbHMiLCJoZWFkaW5nIiwiYnVzeUljb24iLCJfY3JlYXRlQnVzeUljb24iLCJfcmVxdWVzdFRhc2tJZHMiLCJwYWdpbmF0aW9uRWxlbWVudCIsInRhc2tMaXN0TW9kZWwiLCJfY3JlYXRlVGFza0xpc3RFbGVtZW50RnJvbUh0bWwiLCJnZXRQYWdlSW5kZXgiLCJfcmV0cmlldmVUYXNrU2V0V2l0aERlbGF5IiwiZ2V0VGFza3NCeVN0YXRlcyIsInVwZGF0ZWRUYXNrTGlzdE1vZGVsIiwidXBkYXRlRnJvbVRhc2tMaXN0IiwiaGFzVGFza0xpc3RFbGVtZW50Rm9yUGFnZSIsIl9yZXRyaWV2ZVRhc2tQYWdlIiwicmVuZGVyZWRUYXNrTGlzdEVsZW1lbnQiLCJoYXNQYWdlSW5kZXgiLCJjdXJyZW50UGFnZUxpc3RFbGVtZW50Iiwic2VsZWN0ZWRQYWdlTGlzdEVsZW1lbnQiLCJnZXRGb3JQYWdlIiwicG9zdERhdGEiLCJfcmV0cmlldmVUYXNrU2V0IiwiaHRtbCIsImNvbnNvbGUiLCJsb2ciLCJTb3J0Iiwic29ydGFibGVJdGVtc05vZGVMaXN0Iiwic29ydENvbnRyb2xDb2xsZWN0aW9uIiwiX2NyZWF0ZVNvcnRhYmxlQ29udHJvbENvbGxlY3Rpb24iLCJzb3J0YWJsZUl0ZW1zTGlzdCIsIl9jcmVhdGVTb3J0YWJsZUl0ZW1MaXN0IiwiX3NvcnRDb250cm9sQ2xpY2tFdmVudExpc3RlbmVyIiwic29ydGFibGVJdGVtcyIsInNvcnRhYmxlSXRlbUVsZW1lbnQiLCJzb3J0Q29udHJvbEVsZW1lbnQiLCJwYXJlbnQiLCJ0YXNrVHlwZUNvbnRhaW5lcnMiLCJ1bmF2YWlsYWJsZVRhc2tUeXBlIiwidGFza1R5cGVLZXkiLCJzaG93IiwiaW52YWxpZEZpZWxkIiwiZXJyb3JNZXNzYWdlIiwiY29tcGFyYXRvclZhbHVlIiwidmFsaWRhdGVDYXJkTnVtYmVyIiwibnVtYmVyIiwidmFsaWRhdGVFeHBpcnkiLCJleHBfbW9udGgiLCJleHBfeWVhciIsInZhbGlkYXRlQ1ZDIiwiY3ZjIiwidmFsaWRhdG9yIiwiZGF0YUVsZW1lbnQiLCJmaWVsZEtleSIsInN0b3BQcm9wYWdhdGlvbiIsIl9yZW1vdmVFcnJvckFsZXJ0cyIsIl9nZXREYXRhIiwiaXNWYWxpZCIsInZhbGlkYXRlIiwiZXJyb3JBbGVydHMiLCJlcnJvckFsZXJ0IiwiZmllbGQiLCJtZXNzYWdlIiwiZXJyb3JDb250YWluZXIiLCJfZGlzcGxheUZpZWxkRXJyb3IiLCJOYXZCYXJBbmNob3IiLCJoYW5kbGVDbGljayIsImdldFRhcmdldCIsIk5hdkJhckl0ZW0iLCJhbmNob3IiLCJjaGlsZHJlbiIsImNoaWxkIiwibm9kZU5hbWUiLCJ0YXJnZXRzIiwiZ2V0VGFyZ2V0cyIsImNvbnRhaW5zVGFyZ2V0SWQiLCJzZXRBY3RpdmUiLCJuYXZCYXJJdGVtcyIsIm5hdkJhckl0ZW0iLCJsaXN0SXRlbUVsZW1lbnQiLCJhY3RpdmVMaW5rVGFyZ2V0IiwibGlua1RhcmdldHMiLCJsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQiLCJsaW5rVGFyZ2V0IiwiZ2V0Qm91bmRpbmdDbGllbnRSZWN0IiwidG9wIiwiY2xlYXJBY3RpdmUiLCJzY3JvbGxFdmVudExpc3RlbmVyIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG1DQUEyQiwwQkFBMEIsRUFBRTtBQUN2RCx5Q0FBaUMsZUFBZTtBQUNoRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQSw4REFBc0QsK0RBQStEOztBQUVySDtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7QUM3REEseUM7Ozs7Ozs7Ozs7OztBQ0FBLG1CQUFBQSxDQUFRLGtGQUFSO0FBQ0EsbUJBQUFBLENBQVEsOENBQVI7O0FBRUEsbUJBQUFBLENBQVEsMEVBQVI7QUFDQSxtQkFBQUEsQ0FBUSxxRUFBUjtBQUNBLG1CQUFB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O0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EscUNBQXFDO0FBQ3JDOztBQUVBO0FBQ0E7QUFDQSxnQkFBZ0IsZ0NBQWdDO0FBQ2hEO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsRUFBRTtBQUNGOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxDQUFDOzs7Ozs7Ozs7Ozs7OzhDQ2hmRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsK0JBQStCO0FBQy9COztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FB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QTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QztBQUN6QztBQUNBLGlFO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxzQkFBc0I7QUFDdEI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxzQ0FBc0MsYUFBYSxPQUFPO0FBQzFEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsNkJBQTZCO0FBQzdCO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNEJBQTRCLGtDQUFrQztBQUM5RDtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG9EO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0EsOEVBQThFO0FBQzlFO0FBQ0E7QUFDQTtBQUNBLHFDQUFxQztBQUNyQzs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDLGFBQWEsRTtBQUNyRCxZQUFZLGFBQWE7QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLDRDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLDBDQUEwQztBQUMxQztBQUNBO0FBQ0E7QUFDQSxrQ0FBa0M7QUFDbEM7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxrQ0FBa0Msb0dBQW9HLEVBQUU7QUFDeEk7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0EsK0NBQStDO0FBQy9DO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLHVDQUF1QztBQUN2QztBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7Ozs7O0FBS0E7QUFDQTtBQUNBO0FBQ0EseUNBQXlDLEtBQUs7QUFDOUM7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsdUNBQXVDLEtBQUs7QUFDNUM7QUFDQTtBQUNBOztBQUVBO0FBQ0EsdUVBQXVFLGdCQUFnQixFQUFFOztBQUV6RjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7Ozs7Ozs7Ozs7Ozs7QUM5dUJEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxRQUFRLFNBQVM7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxRQUFRLFNBQVM7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxFQUFFO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxFQUFFLGFBQWE7QUFDZjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLENBQUM7QUFDRDtBQUNBOztBQUVBLENBQUM7O0FBRUQ7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSxlQUFlLFNBQVM7QUFDeEI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0EsQ0FBQzs7QUFFRDs7Ozs7Ozs7Ozs7Ozs4Q0MvT0E7QUFDQSxnQkFBZ0IsdUZBQTRELFlBQVk7QUFBQSxzS0FBb0Usd0ZBQXdGLGFBQWEsT0FBTyx3S0FBd0ssY0FBYyx1SEFBdUgsY0FBYyxZQUFZLEtBQUssbUJBQW1CLGtCQUFrQixnREFBZ0QsZ0JBQWdCLFNBQVMsZUFBZSw2RUFBNkUsZUFBZSxpREFBaUQsZUFBZSxNQUFNLElBQUksd0JBQXdCLFNBQVMsSUFBSSxTQUFTLGVBQWUsbUNBQW1DLDZEQUE2RCxNQUFNLEVBQUUsNEdBQTRHLG1NQUFtTSxNQUFNLElBQUksNEJBQTRCLFNBQVMsUUFBUSxTQUFTLGlCQUFpQixNQUFNLDJtQkFBMm1CLGNBQWMsb05BQW9OLHFCQUFxQixRQUFRLHFCQUFxQixnQ0FBZ0MsU0FBUyxrRUFBa0UsZUFBZSw0QkFBNEIsbUJBQW1CLHNEQUFzRCwyQ0FBMkMsOERBQThELG1CQUFtQiwySkFBMkoscUJBQXFCLG1EQUFtRCx5QkFBeUIsbUJBQW1CLG1CQUFtQixFQUFFLDRCQUE0QixxQkFBcUIsdUJBQXVCLDJCQUEyQixzREFBc0QsaUNBQWlDLGtCQUFrQixpRkFBaUYsU0FBUyxvQkFBb0IsK0RBQStELDhIQUE4SCxvQkFBb0IsbUhBQW1ILGVBQWUsd0lBQXdJLG1IQUFtSCxrQkFBa0IsZ1BBQWdQLGtHQUFrRyx5RkFBeUYsZUFBZSxxR0FBcUcseURBQXlELDJCQUEyQixhQUFhLEdBQUcsZUFBZSw2QkFBNkIsY0FBYyxRQUFRLDRCQUE0Qiw4TEFBOEwsb0JBQW9CLDhHQUE4Ryx1QkFBdUIsb01BQW9NLGNBQWMsRzs7Ozs7Ozs7Ozs7OztBQ0Rwa0s7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxDQUFDO0FBQ0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxvQ0FBb0MsMkNBQTJDLGdCQUFnQixrQkFBa0IsT0FBTywyQkFBMkIsd0RBQXdELGdDQUFnQyx1REFBdUQsMkRBQTJELEVBQUUsRUFBRSx5REFBeUQscUVBQXFFLDZEQUE2RCxvQkFBb0IsR0FBRyxFQUFFOztBQUVyakIscURBQXFELDBDQUEwQywwREFBMEQsRUFBRTs7QUFFM0o7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkRBQTJEO0FBQzNEOztBQUVBO0FBQ0E7QUFDQSwyQkFBMkIscUJBQXFCO0FBQ2hEO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQTs7QUFFQSwyQkFBMkIscUJBQXFCO0FBQ2hEOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkRBQTJEO0FBQzNEOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7O0FBRUEsMkJBQTJCLHFCQUFxQjtBQUNoRDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQSxDQUFDLG9COzs7Ozs7Ozs7Ozs7QUN2Z0JEOztBQUVBO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0EsQ0FBQztBQUNEO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSw0Q0FBNEM7O0FBRTVDIiwiZmlsZSI6ImFwcC4wYTM3OTMxMy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwge1xuIFx0XHRcdFx0Y29uZmlndXJhYmxlOiBmYWxzZSxcbiBcdFx0XHRcdGVudW1lcmFibGU6IHRydWUsXG4gXHRcdFx0XHRnZXQ6IGdldHRlclxuIFx0XHRcdH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIi9idWlsZC9cIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSBcIi4vYXNzZXRzL2pzL2FwcC5qc1wiKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCA0NWYyMjQxMWFhODc0YThkNGJhMiIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9hc3NldHMvY3NzL2FwcC5zY3NzXG4vLyBtb2R1bGUgaWQgPSAuL2Fzc2V0cy9jc3MvYXBwLnNjc3Ncbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwicmVxdWlyZSgnYm9vdHN0cmFwLm5hdGl2ZScpO1xucmVxdWlyZSgnLi4vY3NzL2FwcC5zY3NzJyk7XG5cbnJlcXVpcmUoJ2NsYXNzbGlzdC1wb2x5ZmlsbCcpO1xucmVxdWlyZSgnLi9wb2x5ZmlsbC9jdXN0b20tZXZlbnQnKTtcbnJlcXVpcmUoJy4vcG9seWZpbGwvb2JqZWN0LWVudHJpZXMnKTtcblxubGV0IGZvcm1CdXR0b25TcGlubmVyID0gcmVxdWlyZSgnLi9mb3JtLWJ1dHRvbi1zcGlubmVyJyk7XG5sZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4vZm9ybS1maWVsZC1mb2N1c2VyJyk7XG5sZXQgbW9kYWxDb250cm9sID0gcmVxdWlyZSgnLi9tb2RhbC1jb250cm9sJyk7XG5sZXQgY29sbGFwc2VDb250cm9sQ2FyZXQgPSByZXF1aXJlKCcuL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQnKTtcblxubGV0IERhc2hib2FyZCA9IHJlcXVpcmUoJy4vcGFnZS9kYXNoYm9hcmQnKTtcbmxldCB0ZXN0SGlzdG9yeVBhZ2UgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1oaXN0b3J5Jyk7XG5sZXQgVGVzdFJlc3VsdHMgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1yZXN1bHRzJyk7XG5sZXQgVXNlckFjY291bnQgPSByZXF1aXJlKCcuL3BhZ2UvdXNlci1hY2NvdW50Jyk7XG5sZXQgVXNlckFjY291bnRDYXJkID0gcmVxdWlyZSgnLi9wYWdlL3VzZXItYWNjb3VudC1jYXJkJyk7XG5sZXQgQWxlcnRGYWN0b3J5ID0gcmVxdWlyZSgnLi9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5Jyk7XG5sZXQgVGVzdFByb2dyZXNzID0gcmVxdWlyZSgnLi9wYWdlL3Rlc3QtcHJvZ3Jlc3MnKTtcbmxldCBUZXN0UmVzdWx0c1ByZXBhcmluZyA9IHJlcXVpcmUoJy4vcGFnZS90ZXN0LXJlc3VsdHMtcHJlcGFyaW5nJyk7XG5sZXQgVGVzdFJlc3VsdHNCeVRhc2tUeXBlID0gcmVxdWlyZSgnLi9wYWdlL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUnKTtcblxuY29uc3Qgb25Eb21Db250ZW50TG9hZGVkID0gZnVuY3Rpb24gKCkge1xuICAgIGxldCBib2R5ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ2JvZHknKS5pdGVtKDApO1xuICAgIGxldCBmb2N1c2VkRmllbGQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1mb2N1c2VkXScpO1xuXG4gICAgaWYgKGZvY3VzZWRGaWVsZCkge1xuICAgICAgICBmb3JtRmllbGRGb2N1c2VyKGZvY3VzZWRGaWVsZCk7XG4gICAgfVxuXG4gICAgW10uZm9yRWFjaC5jYWxsKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5qcy1mb3JtLWJ1dHRvbi1zcGlubmVyJyksIGZ1bmN0aW9uIChmb3JtRWxlbWVudCkge1xuICAgICAgICBmb3JtQnV0dG9uU3Bpbm5lcihmb3JtRWxlbWVudCk7XG4gICAgfSk7XG5cbiAgICBtb2RhbENvbnRyb2woZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLm1vZGFsLWNvbnRyb2wnKSk7XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ2Rhc2hib2FyZCcpKSB7XG4gICAgICAgIGxldCBkYXNoYm9hcmQgPSBuZXcgRGFzaGJvYXJkKGRvY3VtZW50KTtcbiAgICAgICAgZGFzaGJvYXJkLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3Rlc3QtcHJvZ3Jlc3MnKSkge1xuICAgICAgICBsZXQgdGVzdFByb2dyZXNzID0gbmV3IFRlc3RQcm9ncmVzcyhkb2N1bWVudCk7XG4gICAgICAgIHRlc3RQcm9ncmVzcy5pbml0KCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd0ZXN0LWhpc3RvcnknKSkge1xuICAgICAgICB0ZXN0SGlzdG9yeVBhZ2UoZG9jdW1lbnQpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1yZXN1bHRzJykpIHtcbiAgICAgICAgbGV0IHRlc3RSZXN1bHRzID0gbmV3IFRlc3RSZXN1bHRzKGRvY3VtZW50KTtcbiAgICAgICAgdGVzdFJlc3VsdHMuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZScpKSB7XG4gICAgICAgIGxldCB0ZXN0UmVzdWx0c0J5VGFza1R5cGUgPSBuZXcgVGVzdFJlc3VsdHNCeVRhc2tUeXBlKGRvY3VtZW50KTtcbiAgICAgICAgdGVzdFJlc3VsdHNCeVRhc2tUeXBlLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcnKSkge1xuICAgICAgICBsZXQgdGVzdFJlc3VsdHNQcmVwYXJpbmcgPSBuZXcgVGVzdFJlc3VsdHNQcmVwYXJpbmcoZG9jdW1lbnQpO1xuICAgICAgICB0ZXN0UmVzdWx0c1ByZXBhcmluZy5pbml0KCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd1c2VyLWFjY291bnQnKSkge1xuICAgICAgICBsZXQgdXNlckFjY291bnQgPSBuZXcgVXNlckFjY291bnQod2luZG93LCBkb2N1bWVudCk7XG4gICAgICAgIHVzZXJBY2NvdW50LmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3VzZXItYWNjb3VudC1jYXJkJykpIHtcbiAgICAgICAgbGV0IHVzZXJBY2NvdW50Q2FyZCA9IG5ldyBVc2VyQWNjb3VudENhcmQoZG9jdW1lbnQpO1xuICAgICAgICB1c2VyQWNjb3VudENhcmQuaW5pdCgpO1xuICAgIH1cblxuICAgIGNvbGxhcHNlQ29udHJvbENhcmV0KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5jb2xsYXBzZS1jb250cm9sJykpO1xuXG4gICAgW10uZm9yRWFjaC5jYWxsKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hbGVydCcpLCBmdW5jdGlvbiAoYWxlcnRFbGVtZW50KSB7XG4gICAgICAgIEFsZXJ0RmFjdG9yeS5jcmVhdGVGcm9tRWxlbWVudChhbGVydEVsZW1lbnQpO1xuICAgIH0pO1xufTtcblxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIG9uRG9tQ29udGVudExvYWRlZCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYXBwLmpzIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoY29udHJvbHMpIHtcbiAgICBjb25zdCBjb250cm9sSWNvbkNsYXNzID0gJ2ZhJztcbiAgICBjb25zdCBjYXJldFVwQ2xhc3MgPSAnZmEtY2FyZXQtdXAnO1xuICAgIGNvbnN0IGNhcmV0RG93bkNsYXNzID0gJ2ZhLWNhcmV0LWRvd24nO1xuICAgIGNvbnN0IGNvbnRyb2xDb2xsYXBzZWRDbGFzcyA9ICdjb2xsYXBzZWQnO1xuXG4gICAgbGV0IGNyZWF0ZUNvbnRyb2xJY29uID0gZnVuY3Rpb24gKGNvbnRyb2wpIHtcbiAgICAgICAgY29uc3QgY29udHJvbEljb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpJyk7XG4gICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY29udHJvbEljb25DbGFzcyk7XG5cbiAgICAgICAgaWYgKGNvbnRyb2wuY2xhc3NMaXN0LmNvbnRhaW5zKGNvbnRyb2xDb2xsYXBzZWRDbGFzcykpIHtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY2FyZXREb3duQ2xhc3MpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldFVwQ2xhc3MpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIGNvbnRyb2xJY29uO1xuICAgIH07XG5cbiAgICBsZXQgdG9nZ2xlQ2FyZXQgPSBmdW5jdGlvbiAoY29udHJvbCwgY29udHJvbEljb24pIHtcbiAgICAgICAgaWYgKGNvbnRyb2wuY2xhc3NMaXN0LmNvbnRhaW5zKGNvbnRyb2xDb2xsYXBzZWRDbGFzcykpIHtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5yZW1vdmUoY2FyZXRVcENsYXNzKTtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY2FyZXREb3duQ2xhc3MpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LnJlbW92ZShjYXJldERvd25DbGFzcyk7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QuYWRkKGNhcmV0VXBDbGFzcyk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgbGV0IGhhbmRsZUNvbnRyb2wgPSBmdW5jdGlvbiAoY29udHJvbCkge1xuICAgICAgICBjb25zdCBldmVudE5hbWVTaG93biA9ICdzaG93bi5icy5jb2xsYXBzZSc7XG4gICAgICAgIGNvbnN0IGV2ZW50TmFtZUhpZGRlbiA9ICdoaWRkZW4uYnMuY29sbGFwc2UnO1xuICAgICAgICBjb25zdCBjb2xsYXBzaWJsZUVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChjb250cm9sLmdldEF0dHJpYnV0ZSgnZGF0YS10YXJnZXQnKS5yZXBsYWNlKCcjJywgJycpKTtcbiAgICAgICAgY29uc3QgY29udHJvbEljb24gPSBjcmVhdGVDb250cm9sSWNvbihjb250cm9sKTtcblxuICAgICAgICBjb250cm9sLmFwcGVuZChjb250cm9sSWNvbik7XG5cbiAgICAgICAgbGV0IHNob3duSGlkZGVuRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRvZ2dsZUNhcmV0KGNvbnRyb2wsIGNvbnRyb2xJY29uKTtcbiAgICAgICAgfTtcblxuICAgICAgICBjb2xsYXBzaWJsZUVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihldmVudE5hbWVTaG93biwgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICBjb2xsYXBzaWJsZUVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihldmVudE5hbWVIaWRkZW4sIHNob3duSGlkZGVuRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBjb250cm9scy5sZW5ndGg7IGkrKykge1xuICAgICAgICBoYW5kbGVDb250cm9sKGNvbnRyb2xzW2ldKTtcbiAgICB9XG59O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQuanMiLCJsZXQgTGlzdGVkVGVzdENvbGxlY3Rpb24gPSByZXF1aXJlKCcuLi9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uJyk7XG5sZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5cbmNsYXNzIFJlY2VudFRlc3RMaXN0IHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLnNvdXJjZVVybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvdXJjZS11cmwnKTtcbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbiA9IG5ldyBMaXN0ZWRUZXN0Q29sbGVjdGlvbigpO1xuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uID0gbmV3IExpc3RlZFRlc3RDb2xsZWN0aW9uKCk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG5cbiAgICAgICAgdGhpcy5fcmV0cmlldmVUZXN0cygpO1xuICAgIH07XG5cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICB0aGlzLl9wYXJzZVJldHJpZXZlZFRlc3RzKGV2ZW50LmRldGFpbC5yZXNwb25zZSk7XG4gICAgICAgIHRoaXMuX3JlbmRlclJldHJpZXZlZFRlc3RzKCk7XG5cbiAgICAgICAgd2luZG93LnNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUZXN0cygpO1xuICAgICAgICB9LCAzMDAwKTtcbiAgICB9O1xuXG4gICAgX3JlbmRlclJldHJpZXZlZFRlc3RzICgpIHtcbiAgICAgICAgdGhpcy5fcmVtb3ZlU3Bpbm5lcigpO1xuXG4gICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24uZm9yRWFjaCgobGlzdGVkVGVzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKCF0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uLmNvbnRhaW5zKGxpc3RlZFRlc3QpKSB7XG4gICAgICAgICAgICAgICAgbGlzdGVkVGVzdC5lbGVtZW50LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQobGlzdGVkVGVzdC5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLnJlbW92ZShsaXN0ZWRUZXN0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5yZXRyaWV2ZWRMaXN0ZWRUZXN0Q29sbGVjdGlvbi5mb3JFYWNoKChyZXRyaWV2ZWRMaXN0ZWRUZXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAodGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5jb250YWlucyhyZXRyaWV2ZWRMaXN0ZWRUZXN0KSkge1xuICAgICAgICAgICAgICAgIGxldCBsaXN0ZWRUZXN0ID0gdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5nZXQocmV0cmlldmVkTGlzdGVkVGVzdC5nZXRUZXN0SWQoKSk7XG5cbiAgICAgICAgICAgICAgICBpZiAocmV0cmlldmVkTGlzdGVkVGVzdC5nZXRUeXBlKCkgIT09IGxpc3RlZFRlc3QuZ2V0VHlwZSgpKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24ucmVtb3ZlKGxpc3RlZFRlc3QpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQucmVwbGFjZUNoaWxkKHJldHJpZXZlZExpc3RlZFRlc3QuZWxlbWVudCwgbGlzdGVkVGVzdC5lbGVtZW50KTtcblxuICAgICAgICAgICAgICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmFkZChyZXRyaWV2ZWRMaXN0ZWRUZXN0KTtcbiAgICAgICAgICAgICAgICAgICAgcmV0cmlldmVkTGlzdGVkVGVzdC5lbmFibGUoKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBsaXN0ZWRUZXN0LnJlbmRlckZyb21MaXN0ZWRUZXN0KHJldHJpZXZlZExpc3RlZFRlc3QpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50Lmluc2VydEFkamFjZW50RWxlbWVudCgnYWZ0ZXJiZWdpbicsIHJldHJpZXZlZExpc3RlZFRlc3QuZWxlbWVudCk7XG4gICAgICAgICAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5hZGQocmV0cmlldmVkTGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgcmV0cmlldmVkTGlzdGVkVGVzdC5lbmFibGUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9wYXJzZVJldHJpZXZlZFRlc3RzIChyZXNwb25zZSkge1xuICAgICAgICBsZXQgcmV0cmlldmVkVGVzdHNNYXJrdXAgPSByZXNwb25zZS50cmltKCk7XG4gICAgICAgIGxldCByZXRyaWV2ZWRUZXN0Q29udGFpbmVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIHJldHJpZXZlZFRlc3RDb250YWluZXIuaW5uZXJIVE1MID0gcmV0cmlldmVkVGVzdHNNYXJrdXA7XG5cbiAgICAgICAgdGhpcy5yZXRyaWV2ZWRMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IExpc3RlZFRlc3RDb2xsZWN0aW9uLmNyZWF0ZUZyb21Ob2RlTGlzdChcbiAgICAgICAgICAgIHJldHJpZXZlZFRlc3RDb250YWluZXIucXVlcnlTZWxlY3RvckFsbCgnLmxpc3RlZC10ZXN0JyksXG4gICAgICAgICAgICBmYWxzZVxuICAgICAgICApO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVUZXN0cyAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0VGV4dCh0aGlzLnNvdXJjZVVybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVUZXN0cycpO1xuICAgIH07XG5cbiAgICBfcmVtb3ZlU3Bpbm5lciAoKSB7XG4gICAgICAgIGxldCBzcGlubmVyID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1wcmVmaWxsLXNwaW5uZXInKTtcblxuICAgICAgICBpZiAoc3Bpbm5lcikge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LnJlbW92ZUNoaWxkKHNwaW5uZXIpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBSZWNlbnRUZXN0TGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9kYXNoYm9hcmQvcmVjZW50LXRlc3QtbGlzdC5qcyIsImxldCBGb3JtQnV0dG9uID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbicpO1xuXG5jbGFzcyBUZXN0U3RhcnRGb3JtIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1t0eXBlPXN1Ym1pdF0nKSwgKHN1Ym1pdEJ1dHRvbikgPT4ge1xuICAgICAgICAgICAgdGhpcy5zdWJtaXRCdXR0b25zLnB1c2gobmV3IEZvcm1CdXR0b24oc3VibWl0QnV0dG9uKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsIHRoaXMuX3N1Ym1pdEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG5cbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b25zLmZvckVhY2goKHN1Ym1pdEJ1dHRvbikgPT4ge1xuICAgICAgICAgICAgc3VibWl0QnV0dG9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9zdWJtaXRCdXR0b25FdmVudExpc3RlbmVyKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9zdWJtaXRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZGVFbXBoYXNpemUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5fcmVwbGFjZVVuY2hlY2tlZENoZWNrYm94ZXNXaXRoSGlkZGVuRmllbGRzKCk7XG4gICAgfTtcblxuICAgIGRpc2FibGUgKCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZGlzYWJsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b25zLmZvckVhY2goKHN1Ym1pdEJ1dHRvbikgPT4ge1xuICAgICAgICAgICAgc3VibWl0QnV0dG9uLmVuYWJsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3N1Ym1pdEJ1dHRvbkV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCBidXR0b25FbGVtZW50ID0gZXZlbnQudGFyZ2V0O1xuICAgICAgICBsZXQgYnV0dG9uID0gbmV3IEZvcm1CdXR0b24oYnV0dG9uRWxlbWVudCk7XG5cbiAgICAgICAgYnV0dG9uLm1hcmtBc0J1c3koKTtcbiAgICB9O1xuXG4gICAgX3JlcGxhY2VVbmNoZWNrZWRDaGVja2JveGVzV2l0aEhpZGRlbkZpZWxkcyAoKSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW5wdXRbdHlwZT1jaGVja2JveF0nKSwgKGlucHV0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIWlucHV0LmNoZWNrZWQpIHtcbiAgICAgICAgICAgICAgICBsZXQgaGlkZGVuSW5wdXQgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICAgICAgaGlkZGVuSW5wdXQuc2V0QXR0cmlidXRlKCd0eXBlJywgJ2hpZGRlbicpO1xuICAgICAgICAgICAgICAgIGhpZGRlbklucHV0LnNldEF0dHJpYnV0ZSgnbmFtZScsIGlucHV0LmdldEF0dHJpYnV0ZSgnbmFtZScpKTtcbiAgICAgICAgICAgICAgICBoaWRkZW5JbnB1dC52YWx1ZSA9ICcwJztcblxuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmQoaGlkZGVuSW5wdXQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RTdGFydEZvcm07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvZGFzaGJvYXJkL3Rlc3Qtc3RhcnQtZm9ybS5qcyIsImxldCBGb3JtQnV0dG9uID0gcmVxdWlyZSgnLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5cbm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGZvcm0pIHtcbiAgICBjb25zdCBzdWJtaXRCdXR0b24gPSBuZXcgRm9ybUJ1dHRvbihmb3JtLnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG5cbiAgICBmb3JtLmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgc3VibWl0QnV0dG9uLm1hcmtBc0J1c3koKTtcbiAgICB9KTtcbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvZm9ybS1idXR0b24tc3Bpbm5lci5qcyIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGlucHV0KSB7XG4gICAgbGV0IGlucHV0VmFsdWUgPSBpbnB1dC52YWx1ZTtcblxuICAgIHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaW5wdXQuZm9jdXMoKTtcbiAgICAgICAgaW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgaW5wdXQudmFsdWUgPSBpbnB1dFZhbHVlO1xuICAgIH0sIDEpO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9mb3JtLWZpZWxkLWZvY3VzZXIuanMiLCJtb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uIChjb250cm9sRWxlbWVudHMpIHtcbiAgICBsZXQgaW5pdGlhbGl6ZSA9IGZ1bmN0aW9uIChjb250cm9sRWxlbWVudCkge1xuICAgICAgICBjb250cm9sRWxlbWVudC5jbGFzc0xpc3QuYWRkKCdidG4nLCAnYnRuLWxpbmsnKTtcbiAgICB9O1xuXG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBjb250cm9sRWxlbWVudHMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgaW5pdGlhbGl6ZShjb250cm9sRWxlbWVudHNbaV0pO1xuICAgIH1cbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kYWwtY29udHJvbC5qcyIsImNsYXNzIEJhZGdlQ29sbGVjdGlvbiB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtOb2RlTGlzdH0gYmFkZ2VzXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGJhZGdlcykge1xuICAgICAgICB0aGlzLmJhZGdlcyA9IGJhZGdlcztcbiAgICB9O1xuXG4gICAgYXBwbHlVbmlmb3JtV2lkdGggKCkge1xuICAgICAgICBsZXQgZ3JlYXRlc3RXaWR0aCA9IHRoaXMuX2Rlcml2ZUdyZWF0ZXN0V2lkdGgoKTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5iYWRnZXMsIChiYWRnZSkgPT4ge1xuICAgICAgICAgICAgYmFkZ2Uuc3R5bGUud2lkdGggPSBncmVhdGVzdFdpZHRoICsgJ3B4JztcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtudW1iZXJ9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfZGVyaXZlR3JlYXRlc3RXaWR0aCAoKSB7XG4gICAgICAgIGxldCBncmVhdGVzdFdpZHRoID0gMDtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5iYWRnZXMsIChiYWRnZSkgPT4ge1xuICAgICAgICAgICAgaWYgKGJhZGdlLm9mZnNldFdpZHRoID4gZ3JlYXRlc3RXaWR0aCkge1xuICAgICAgICAgICAgICAgIGdyZWF0ZXN0V2lkdGggPSBiYWRnZS5vZmZzZXRXaWR0aDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGdyZWF0ZXN0V2lkdGg7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEJhZGdlQ29sbGVjdGlvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9iYWRnZS1jb2xsZWN0aW9uLmpzIiwibGV0IENvb2tpZU9wdGlvbnNNb2RhbCA9IHJlcXVpcmUoJy4vZWxlbWVudC9jb29raWUtb3B0aW9ucy1tb2RhbCcpO1xuXG5jbGFzcyBDb29raWVPcHRpb25zIHtcbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQsIGNvb2tpZU9wdGlvbnNNb2RhbCwgYWN0aW9uQmFkZ2UsIHN0YXR1c0VsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbCA9IGNvb2tpZU9wdGlvbnNNb2RhbDtcbiAgICAgICAgdGhpcy5hY3Rpb25CYWRnZSA9IGFjdGlvbkJhZGdlO1xuICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQgPSBzdGF0dXNFbGVtZW50O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbE9wZW5lZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMubW9kYWwub3BlbmVkJztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2Nvb2tpZS1vcHRpb25zLm1vZGFsLmNsb3NlZCc7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICBsZXQgbW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICBpZiAodGhpcy5jb29raWVPcHRpb25zTW9kYWwuaXNFbXB0eSgpKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zdGF0dXNFbGVtZW50LmlubmVyVGV4dCA9ICdub3QgZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrTm90RW5hYmxlZCgpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ2VuYWJsZWQnO1xuICAgICAgICAgICAgICAgIHRoaXMuYWN0aW9uQmFkZ2UubWFya0VuYWJsZWQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5pbml0KCk7XG5cbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zTW9kYWwuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKENvb2tpZU9wdGlvbnNNb2RhbC5nZXRPcGVuZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zLmdldE1vZGFsT3BlbmVkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zTW9kYWwuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKENvb2tpZU9wdGlvbnNNb2RhbC5nZXRDbG9zZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgbW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIoKTtcbiAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoQ29va2llT3B0aW9ucy5nZXRNb2RhbENsb3NlZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQ29va2llT3B0aW9ucztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9jb29raWUtb3B0aW9ucy5qcyIsImNsYXNzIEFjdGlvbkJhZGdlIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIG1hcmtFbmFibGVkICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FjdGlvbi1iYWRnZS1lbmFibGVkJyk7XG4gICAgfVxuXG4gICAgbWFya05vdEVuYWJsZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aW9uLWJhZGdlLWVuYWJsZWQnKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQWN0aW9uQmFkZ2U7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hY3Rpb24tYmFkZ2UuanMiLCJsZXQgYnNuID0gcmVxdWlyZSgnYm9vdHN0cmFwLm5hdGl2ZScpO1xubGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi8uLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcblxuY2xhc3MgQWxlcnQge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG5cbiAgICAgICAgbGV0IGNsb3NlQnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY2xvc2UnKTtcbiAgICAgICAgaWYgKGNsb3NlQnV0dG9uKSB7XG4gICAgICAgICAgICBjbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2Nsb3NlQnV0dG9uQ2xpY2tFdmVudEhhbmRsZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBzZXRTdHlsZSAoc3R5bGUpIHtcbiAgICAgICAgdGhpcy5fcmVtb3ZlUHJlc2VudGF0aW9uYWxDbGFzc2VzKCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FsZXJ0LScgKyBzdHlsZSk7XG4gICAgfTtcblxuICAgIHdyYXBDb250ZW50SW5Db250YWluZXIgKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGNvbnRhaW5lci5jbGFzc0xpc3QuYWRkKCdjb250YWluZXInKTtcblxuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gdGhpcy5lbGVtZW50LmlubmVySFRNTDtcbiAgICAgICAgdGhpcy5lbGVtZW50LmlubmVySFRNTCA9ICcnO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChjb250YWluZXIpO1xuICAgIH07XG5cbiAgICBfcmVtb3ZlUHJlc2VudGF0aW9uYWxDbGFzc2VzICgpIHtcbiAgICAgICAgbGV0IHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXggPSAnYWxlcnQtJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNsYXNzTmFtZS5pbmRleE9mKHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX2Nsb3NlQnV0dG9uQ2xpY2tFdmVudEhhbmRsZXIgKCkge1xuICAgICAgICBsZXQgcmVsYXRlZEZpZWxkSWQgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWZvcicpO1xuICAgICAgICBpZiAocmVsYXRlZEZpZWxkSWQpIHtcbiAgICAgICAgICAgIGxldCByZWxhdGVkRmllbGQgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5nZXRFbGVtZW50QnlJZChyZWxhdGVkRmllbGRJZCk7XG5cbiAgICAgICAgICAgIGlmIChyZWxhdGVkRmllbGQpIHtcbiAgICAgICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHJlbGF0ZWRGaWVsZCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBsZXQgYnNuQWxlcnQgPSBuZXcgYnNuLkFsZXJ0KHRoaXMuZWxlbWVudCk7XG4gICAgICAgIGJzbkFsZXJ0LmNsb3NlKCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEFsZXJ0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvYWxlcnQuanMiLCJsZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uLy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xuXG5jbGFzcyBDb29raWVPcHRpb25zTW9kYWwge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9Y2xvc2VdJyk7XG4gICAgICAgIHRoaXMuYWRkQnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuanMtYWRkLWJ1dHRvbicpO1xuICAgICAgICB0aGlzLmFwcGx5QnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1uYW1lPWFwcGx5Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuX2FkZFJlbW92ZUFjdGlvbnMoKTtcbiAgICAgICAgdGhpcy5fYWRkRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0T3BlbmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5vcGVuZWQnO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMtbW9kYWwuY2xvc2VkJztcbiAgICB9XG5cbiAgICBfcmVtb3ZlQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgY29va2llRGF0YVJvd0NvdW50ID0gdGhpcy50YWJsZUJvZHkucXVlcnlTZWxlY3RvckFsbCgnLmpzLWNvb2tpZScpLmxlbmd0aDtcbiAgICAgICAgbGV0IHJlbW92ZUFjdGlvbiA9IGV2ZW50LnRhcmdldDtcbiAgICAgICAgbGV0IHRhYmxlUm93ID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQocmVtb3ZlQWN0aW9uLmdldEF0dHJpYnV0ZSgnZGF0YS1mb3InKSk7XG5cbiAgICAgICAgaWYgKGNvb2tpZURhdGFSb3dDb3VudCA9PT0gMSkge1xuICAgICAgICAgICAgbGV0IG5hbWVJbnB1dCA9IHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lIGlucHV0Jyk7XG5cbiAgICAgICAgICAgIG5hbWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICAgICAgdGFibGVSb3cucXVlcnlTZWxlY3RvcignLnZhbHVlIGlucHV0JykudmFsdWUgPSAnJztcblxuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcihuYW1lSW5wdXQpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGFibGVSb3cucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh0YWJsZVJvdyk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtLZXlib2FyZEV2ZW50fSBldmVudFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC50eXBlID09PSAna2V5ZG93bicgJiYgZXZlbnQua2V5ID09PSAnRW50ZXInKSB7XG4gICAgICAgICAgICB0aGlzLmFwcGx5QnV0dG9uLmNsaWNrKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgbGV0IHNob3duRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGFibGVCb2R5ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ3Rib2R5Jyk7XG4gICAgICAgICAgICB0aGlzLnByZXZpb3VzVGFibGVCb2R5ID0gdGhpcy50YWJsZUJvZHkuY2xvbmVOb2RlKHRydWUpO1xuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcih0aGlzLnRhYmxlQm9keS5xdWVyeVNlbGVjdG9yKCcuanMtY29va2llOmxhc3Qtb2YtdHlwZSAubmFtZSBpbnB1dCcpKTtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zTW9kYWwuZ2V0T3BlbmVkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgaGlkZGVuRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zTW9kYWwuZ2V0Q2xvc2VkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnRhYmxlQm9keS5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZCh0aGlzLnByZXZpb3VzVGFibGVCb2R5LCB0aGlzLnRhYmxlQm9keSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGFkZEJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIGxldCB0YWJsZVJvdyA9IHRoaXMuX2NyZWF0ZVRhYmxlUm93KCk7XG4gICAgICAgICAgICBsZXQgcmVtb3ZlQWN0aW9uID0gdGhpcy5fY3JlYXRlUmVtb3ZlQWN0aW9uKHRhYmxlUm93LmdldEF0dHJpYnV0ZSgnZGF0YS1pbmRleCcpKTtcblxuICAgICAgICAgICAgdGFibGVSb3cucXVlcnlTZWxlY3RvcignLnJlbW92ZScpLmFwcGVuZENoaWxkKHJlbW92ZUFjdGlvbik7XG5cbiAgICAgICAgICAgIHRoaXMudGFibGVCb2R5LmFwcGVuZENoaWxkKHRhYmxlUm93KTtcbiAgICAgICAgICAgIHRoaXMuX2FkZFJlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lcihyZW1vdmVBY3Rpb24pO1xuXG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lIGlucHV0JykpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdzaG93bi5icy5tb2RhbCcsIHNob3duRXZlbnRMaXN0ZW5lcik7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRkZW4uYnMubW9kYWwnLCBoaWRkZW5FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5hZGRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBhZGRCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLXJlbW92ZScpLCAocmVtb3ZlQWN0aW9uKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIocmVtb3ZlQWN0aW9uKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubmFtZSBpbnB1dCwgLnZhbHVlIGlucHV0JyksIChpbnB1dCkgPT4ge1xuICAgICAgICAgICAgaW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfYWRkUmVtb3ZlQWN0aW9ucyAoKSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnJlbW92ZScpLCAocmVtb3ZlQ29udGFpbmVyLCBpbmRleCkgPT4ge1xuICAgICAgICAgICAgcmVtb3ZlQ29udGFpbmVyLmFwcGVuZENoaWxkKHRoaXMuX2NyZWF0ZVJlbW92ZUFjdGlvbihpbmRleCkpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSByZW1vdmVBY3Rpb25cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKHJlbW92ZUFjdGlvbikge1xuICAgICAgICByZW1vdmVBY3Rpb24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBpbmRleFxuICAgICAqIEByZXR1cm5zIHtFbGVtZW50IHwgbnVsbH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVSZW1vdmVBY3Rpb24gKGluZGV4KSB7XG4gICAgICAgIGxldCByZW1vdmVBY3Rpb25Db250YWluZXIgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgcmVtb3ZlQWN0aW9uQ29udGFpbmVyLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhIGZhLXRyYXNoLW8ganMtcmVtb3ZlXCIgdGl0bGU9XCJSZW1vdmUgdGhpcyBjb29raWVcIiBkYXRhLWZvcj1cImNvb2tpZS1kYXRhLXJvdy0nICsgaW5kZXggKyAnXCI+PC9pPic7XG5cbiAgICAgICAgcmV0dXJuIHJlbW92ZUFjdGlvbkNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcuanMtcmVtb3ZlJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtOb2RlfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVRhYmxlUm93ICgpIHtcbiAgICAgICAgbGV0IG5leHRDb29raWVJbmRleCA9IHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbmV4dC1jb29raWUtaW5kZXgnKTtcbiAgICAgICAgbGV0IGxhc3RUYWJsZVJvdyA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuanMtY29va2llJyk7XG4gICAgICAgIGxldCB0YWJsZVJvdyA9IGxhc3RUYWJsZVJvdy5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgICAgIGxldCBuYW1lSW5wdXQgPSB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcubmFtZSBpbnB1dCcpO1xuICAgICAgICBsZXQgdmFsdWVJbnB1dCA9IHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy52YWx1ZSBpbnB1dCcpO1xuXG4gICAgICAgIG5hbWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICBuYW1lSW5wdXQuc2V0QXR0cmlidXRlKCduYW1lJywgJ2Nvb2tpZXNbJyArIG5leHRDb29raWVJbmRleCArICddW25hbWVdJyk7XG4gICAgICAgIG5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXl1cCcsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHZhbHVlSW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgdmFsdWVJbnB1dC5zZXRBdHRyaWJ1dGUoJ25hbWUnLCAnY29va2llc1snICsgbmV4dENvb2tpZUluZGV4ICsgJ11bdmFsdWVdJyk7XG4gICAgICAgIHZhbHVlSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5dXAnLCB0aGlzLl9pbnB1dEtleURvd25FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuXG4gICAgICAgIHRhYmxlUm93LnNldEF0dHJpYnV0ZSgnZGF0YS1pbmRleCcsIG5leHRDb29raWVJbmRleCk7XG4gICAgICAgIHRhYmxlUm93LnNldEF0dHJpYnV0ZSgnaWQnLCAnY29va2llLWRhdGEtcm93LScgKyBuZXh0Q29va2llSW5kZXgpO1xuICAgICAgICB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcucmVtb3ZlJykuaW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LnNldEF0dHJpYnV0ZSgnZGF0YS1uZXh0LWNvb2tpZS1pbmRleCcsIHBhcnNlSW50KG5leHRDb29raWVJbmRleCwgMTApICsgMSk7XG5cbiAgICAgICAgcmV0dXJuIHRhYmxlUm93O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBpc0VtcHR5ICgpIHtcbiAgICAgICAgbGV0IGlzRW1wdHkgPSB0cnVlO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW5wdXQnKSwgKGlucHV0KSA9PiB7XG4gICAgICAgICAgICBpZiAoaXNFbXB0eSAmJiBpbnB1dC52YWx1ZS50cmltKCkgIT09ICcnKSB7XG4gICAgICAgICAgICAgICAgaXNFbXB0eSA9IGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gaXNFbXB0eTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IENvb2tpZU9wdGlvbnNNb2RhbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2Nvb2tpZS1vcHRpb25zLW1vZGFsLmpzIiwibGV0IEljb24gPSByZXF1aXJlKCcuL2ljb24nKTtcblxuY2xhc3MgRm9ybUJ1dHRvbiB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgbGV0IGljb25FbGVtZW50ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKEljb24uZ2V0U2VsZWN0b3IoKSk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5pY29uID0gaWNvbkVsZW1lbnQgPyBuZXcgSWNvbihpY29uRWxlbWVudCkgOiBudWxsO1xuICAgIH1cblxuICAgIG1hcmtBc0J1c3kgKCkge1xuICAgICAgICBpZiAodGhpcy5pY29uKSB7XG4gICAgICAgICAgICB0aGlzLmljb24uc2V0QnVzeSgpO1xuICAgICAgICAgICAgdGhpcy5kZUVtcGhhc2l6ZSgpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgbWFya0FzQXZhaWxhYmxlICgpIHtcbiAgICAgICAgaWYgKHRoaXMuaWNvbikge1xuICAgICAgICAgICAgdGhpcy5pY29uLnNldEF2YWlsYWJsZSgnZmEtY2FyZXQtcmlnaHQnKTtcbiAgICAgICAgICAgIHRoaXMudW5EZUVtcGhhc2l6ZSgpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgbWFya1N1Y2NlZWRlZCAoKSB7XG4gICAgICAgIGlmICh0aGlzLmljb24pIHtcbiAgICAgICAgICAgIHRoaXMuaWNvbi5zZXRTdWNjZXNzZnVsKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBkaXNhYmxlICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnNldEF0dHJpYnV0ZSgnZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgICB9XG5cbiAgICBlbmFibGUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQucmVtb3ZlQXR0cmlidXRlKCdkaXNhYmxlZCcpO1xuICAgIH1cblxuICAgIGRlRW1waGFzaXplICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2RlLWVtcGhhc2l6ZScpO1xuICAgIH07XG5cbiAgICB1bkRlRW1waGFzaXplICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2RlLWVtcGhhc2l6ZScpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gRm9ybUJ1dHRvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uLmpzIiwibGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi8uLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcblxuY2xhc3MgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnVzZXJuYW1lSW5wdXQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPWh0dHAtYXV0aC11c2VybmFtZV0nKTtcbiAgICAgICAgdGhpcy5wYXNzd29yZElucHV0ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbbmFtZT1odHRwLWF1dGgtcGFzc3dvcmRdJyk7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9YXBwbHldJyk7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9Y2xvc2VdJyk7XG4gICAgICAgIHRoaXMuY2xlYXJCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9Y2xlYXJdJyk7XG4gICAgICAgIHRoaXMucHJldmlvdXNVc2VybmFtZSA9IG51bGw7XG4gICAgICAgIHRoaXMucHJldmlvdXNQYXNzd29yZCA9IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0T3BlbmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5vcGVuZWQnO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMtbW9kYWwuY2xvc2VkJztcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5fYWRkRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICB9O1xuXG4gICAgaXNFbXB0eSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnVzZXJuYW1lSW5wdXQudmFsdWUudHJpbSgpID09PSAnJyAmJiB0aGlzLnBhc3N3b3JkSW5wdXQudmFsdWUudHJpbSgpID09PSAnJztcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgbGV0IHNob3duRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNVc2VybmFtZSA9IHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgICAgICB0aGlzLnByZXZpb3VzUGFzc3dvcmQgPSB0aGlzLnBhc3N3b3JkSW5wdXQudmFsdWUudHJpbSgpO1xuXG4gICAgICAgICAgICBsZXQgdXNlcm5hbWUgPSB0aGlzLnVzZXJuYW1lSW5wdXQudmFsdWUudHJpbSgpO1xuICAgICAgICAgICAgbGV0IHBhc3N3b3JkID0gdGhpcy5wYXNzd29yZElucHV0LnZhbHVlLnRyaW0oKTtcblxuICAgICAgICAgICAgbGV0IGZvY3VzZWRJbnB1dCA9ICh1c2VybmFtZSA9PT0gJycgfHwgKHVzZXJuYW1lICE9PSAnJyAmJiBwYXNzd29yZCAhPT0gJycpKVxuICAgICAgICAgICAgICAgID8gdGhpcy51c2VybmFtZUlucHV0XG4gICAgICAgICAgICAgICAgOiB0aGlzLnBhc3N3b3JkSW5wdXQ7XG5cbiAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZm9jdXNlZElucHV0KTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5nZXRPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRkZW5FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5nZXRDbG9zZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZSA9IHRoaXMucHJldmlvdXNVc2VybmFtZTtcbiAgICAgICAgICAgIHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZSA9IHRoaXMucHJldmlvdXNQYXNzd29yZDtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnVzZXJuYW1lSW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgICAgIHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdzaG93bi5icy5tb2RhbCcsIHNob3duRXZlbnRMaXN0ZW5lcik7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRkZW4uYnMubW9kYWwnLCBoaWRkZW5FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5jbGVhckJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsZWFyQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy51c2VybmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2tleWRvd24nLCB0aGlzLl9pbnB1dEtleURvd25FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLnBhc3N3b3JkSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7S2V5Ym9hcmRFdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9pbnB1dEtleURvd25FdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBpZiAoZXZlbnQudHlwZSA9PT0gJ2tleWRvd24nICYmIGV2ZW50LmtleSA9PT0gJ0VudGVyJykge1xuICAgICAgICAgICAgdGhpcy5hcHBseUJ1dHRvbi5jbGljaygpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWw7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwuanMiLCJjbGFzcyBJY29uIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIHN0YXRpYyBnZXRDbGFzcyAoKSB7XG4gICAgICAgIHJldHVybiAnZmEnO1xuICAgIH1cblxuICAgIHN0YXRpYyBnZXRTZWxlY3RvciAoKSB7XG4gICAgICAgIHJldHVybiAnLicgKyBJY29uLmdldENsYXNzKCk7XG4gICAgfTtcblxuICAgIHNldEJ1c3kgKCkge1xuICAgICAgICB0aGlzLnJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMoKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2ZhLXNwaW5uZXInLCAnZmEtc3BpbicpO1xuICAgIH07XG5cbiAgICBzZXRBdmFpbGFibGUgKGFjdGl2ZUljb25DbGFzcyA9IG51bGwpIHtcbiAgICAgICAgdGhpcy5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgaWYgKGFjdGl2ZUljb25DbGFzcyAhPT0gbnVsbCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoYWN0aXZlSWNvbkNsYXNzKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBzZXRTdWNjZXNzZnVsICgpIHtcbiAgICAgICAgdGhpcy5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG4gICAgICAgIHRoaXMuc2V0QXZhaWxhYmxlKCdmYS1jaGVjaycpO1xuICAgIH1cblxuICAgIHJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMgKCkge1xuICAgICAgICBsZXQgY2xhc3Nlc1RvUmV0YWluID0gW1xuICAgICAgICAgICAgSWNvbi5nZXRDbGFzcygpLFxuICAgICAgICAgICAgSWNvbi5nZXRDbGFzcygpICsgJy1mdydcbiAgICAgICAgXTtcblxuICAgICAgICBsZXQgcHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCA9IEljb24uZ2V0Q2xhc3MoKSArICctJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKCFjbGFzc2VzVG9SZXRhaW4uaW5jbHVkZXMoY2xhc3NOYW1lKSAmJiBjbGFzc05hbWUuaW5kZXhPZihwcmVzZW50YXRpb25hbENsYXNzUHJlZml4KSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIGNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBJY29uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvaWNvbi5qcyIsImxldCBQcm9ncmVzc2luZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuL3Byb2dyZXNzaW5nLWxpc3RlZC10ZXN0Jyk7XG5cbmNsYXNzIENyYXdsaW5nTGlzdGVkVGVzdCBleHRlbmRzIFByb2dyZXNzaW5nTGlzdGVkVGVzdCB7XG4gICAgcmVuZGVyRnJvbUxpc3RlZFRlc3QgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgc3VwZXIucmVuZGVyRnJvbUxpc3RlZFRlc3QobGlzdGVkVGVzdCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9jZXNzZWQtdXJsLWNvdW50JykuaW5uZXJUZXh0ID0gbGlzdGVkVGVzdC5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1wcm9jZXNzZWQtdXJsLWNvdW50Jyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuZGlzY292ZXJlZC11cmwtY291bnQnKS5pbm5lclRleHQgPSBsaXN0ZWRUZXN0LmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWRpc2NvdmVyZWQtdXJsLWNvdW50Jyk7XG4gICAgfTtcblxuICAgIGdldFR5cGUgKCkge1xuICAgICAgICByZXR1cm4gJ0NyYXdsaW5nTGlzdGVkVGVzdCc7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBDcmF3bGluZ0xpc3RlZFRlc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9jcmF3bGluZy1saXN0ZWQtdGVzdC5qcyIsImNsYXNzIExpc3RlZFRlc3Qge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuaW5pdChlbGVtZW50KTtcbiAgICB9XG5cbiAgICBpbml0IChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgZW5hYmxlICgpIHt9O1xuXG4gICAgZ2V0VGVzdElkICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGVzdC1pZCcpO1xuICAgIH07XG5cbiAgICBnZXRTdGF0ZSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG4gICAgfVxuXG4gICAgaXNGaW5pc2hlZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdmaW5pc2hlZCcpO1xuICAgIH1cblxuICAgIHJlbmRlckZyb21MaXN0ZWRUZXN0IChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIGlmICh0aGlzLmlzRmluaXNoZWQoKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMuZ2V0U3RhdGUoKSAhPT0gbGlzdGVkVGVzdC5nZXRTdGF0ZSgpKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQucGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQobGlzdGVkVGVzdC5lbGVtZW50LCB0aGlzLmVsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy5pbml0KGxpc3RlZFRlc3QuZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLmVuYWJsZSgpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIGdldFR5cGUgKCkge1xuICAgICAgICByZXR1cm4gJ0xpc3RlZFRlc3QnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTGlzdGVkVGVzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2xpc3RlZC10ZXN0LmpzIiwibGV0IFByb2dyZXNzaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4vcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QnKTtcbmxldCBUZXN0UmVzdWx0UmV0cmlldmVyID0gcmVxdWlyZSgnLi4vLi4vLi4vc2VydmljZXMvdGVzdC1yZXN1bHQtcmV0cmlldmVyJyk7XG5cbmNsYXNzIFByZXBhcmluZ0xpc3RlZFRlc3QgZXh0ZW5kcyBQcm9ncmVzc2luZ0xpc3RlZFRlc3Qge1xuICAgIGVuYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuX2luaXRpYWxpc2VSZXN1bHRSZXRyaWV2ZXIoKTtcbiAgICB9O1xuXG4gICAgX2luaXRpYWxpc2VSZXN1bHRSZXRyaWV2ZXIgKCkge1xuICAgICAgICBsZXQgcHJlcGFyaW5nRWxlbWVudCA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJlcGFyaW5nJyk7XG4gICAgICAgIGxldCB0ZXN0UmVzdWx0c1JldHJpZXZlciA9IG5ldyBUZXN0UmVzdWx0UmV0cmlldmVyKHByZXBhcmluZ0VsZW1lbnQpO1xuXG4gICAgICAgIHByZXBhcmluZ0VsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih0ZXN0UmVzdWx0c1JldHJpZXZlci5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgKHJldHJpZXZlZEV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFyZW50Tm9kZSA9IHRoaXMuZWxlbWVudC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgbGV0IHJldHJpZXZlZExpc3RlZFRlc3QgPSByZXRyaWV2ZWRFdmVudC5kZXRhaWw7XG4gICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmNsYXNzTGlzdC5hZGQoJ2ZhZGUtb3V0Jyk7XG5cbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCd0cmFuc2l0aW9uZW5kJywgKCkgPT4ge1xuICAgICAgICAgICAgICAgIHBhcmVudE5vZGUucmVwbGFjZUNoaWxkKHJldHJpZXZlZExpc3RlZFRlc3QsIHRoaXMuZWxlbWVudCk7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50ID0gcmV0cmlldmVkRXZlbnQuZGV0YWlsO1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdmYWRlLWluJyk7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2ZhZGUtb3V0Jyk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2ZhZGUtb3V0Jyk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRlc3RSZXN1bHRzUmV0cmlldmVyLmluaXQoKTtcbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnUHJlcGFyaW5nTGlzdGVkVGVzdCc7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBQcmVwYXJpbmdMaXN0ZWRUZXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJlcGFyaW5nLWxpc3RlZC10ZXN0LmpzIiwibGV0IExpc3RlZFRlc3QgPSByZXF1aXJlKCcuL2xpc3RlZC10ZXN0Jyk7XG5sZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9wcm9ncmVzcy1iYXInKTtcblxuY2xhc3MgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0IGV4dGVuZHMgTGlzdGVkVGVzdCB7XG4gICAgaW5pdCAoZWxlbWVudCkge1xuICAgICAgICBzdXBlci5pbml0KGVsZW1lbnQpO1xuXG4gICAgICAgIGxldCBwcm9ncmVzc0JhckVsZW1lbnQgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByb2dyZXNzLWJhcicpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyID0gcHJvZ3Jlc3NCYXJFbGVtZW50ID8gbmV3IFByb2dyZXNzQmFyKHByb2dyZXNzQmFyRWxlbWVudCkgOiBudWxsO1xuICAgIH1cblxuICAgIGdldENvbXBsZXRpb25QZXJjZW50ICgpIHtcbiAgICAgICAgbGV0IGNvbXBsZXRpb25QZXJjZW50ID0gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1jb21wbGV0aW9uLXBlcmNlbnQnKTtcblxuICAgICAgICBpZiAodGhpcy5pc0ZpbmlzaGVkKCkgJiYgY29tcGxldGlvblBlcmNlbnQgPT09IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybiAxMDA7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gcGFyc2VGbG9hdCh0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWNvbXBsZXRpb24tcGVyY2VudCcpKTtcbiAgICB9XG5cbiAgICBzZXRDb21wbGV0aW9uUGVyY2VudCAoY29tcGxldGlvblBlcmNlbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnNldEF0dHJpYnV0ZSgnZGF0YS1jb21wbGV0aW9uLXBlcmNlbnQnLCBjb21wbGV0aW9uUGVyY2VudCk7XG4gICAgfTtcblxuICAgIHJlbmRlckZyb21MaXN0ZWRUZXN0IChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIHN1cGVyLnJlbmRlckZyb21MaXN0ZWRUZXN0KGxpc3RlZFRlc3QpO1xuXG4gICAgICAgIGlmICh0aGlzLmdldENvbXBsZXRpb25QZXJjZW50KCkgPT09IGxpc3RlZFRlc3QuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5zZXRDb21wbGV0aW9uUGVyY2VudChsaXN0ZWRUZXN0LmdldENvbXBsZXRpb25QZXJjZW50KCkpO1xuXG4gICAgICAgIGlmICh0aGlzLnByb2dyZXNzQmFyKSB7XG4gICAgICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldENvbXBsZXRpb25QZXJjZW50KHRoaXMuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0JztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFByb2dyZXNzaW5nTGlzdGVkVGVzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L3Byb2dyZXNzaW5nLWxpc3RlZC10ZXN0LmpzIiwiY2xhc3MgUHJvZ3Jlc3NCYXIge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgc2V0Q29tcGxldGlvblBlcmNlbnQgKGNvbXBsZXRpb25QZXJjZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5zdHlsZS53aWR0aCA9IGNvbXBsZXRpb25QZXJjZW50ICsgJyUnO1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdhcmlhLXZhbHVlbm93JywgY29tcGxldGlvblBlcmNlbnQpO1xuICAgIH1cblxuICAgIHNldFN0eWxlIChzdHlsZSkge1xuICAgICAgICB0aGlzLl9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMoKTtcblxuICAgICAgICBpZiAoc3R5bGUgPT09ICd3YXJuaW5nJykge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ3Byb2dyZXNzLWJhci13YXJuaW5nJyk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBfcmVtb3ZlUHJlc2VudGF0aW9uYWxDbGFzc2VzICgpIHtcbiAgICAgICAgbGV0IHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXggPSAncHJvZ3Jlc3MtYmFyLSc7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5mb3JFYWNoKChjbGFzc05hbWUsIGluZGV4LCBjbGFzc0xpc3QpID0+IHtcbiAgICAgICAgICAgIGlmIChjbGFzc05hbWUuaW5kZXhPZihwcmVzZW50YXRpb25hbENsYXNzUHJlZml4KSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIGNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBQcm9ncmVzc0JhcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhci5qcyIsImNsYXNzIFNvcnRDb250cm9sIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmtleXMgPSBKU09OLnBhcnNlKGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvcnQta2V5cycpKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0U29ydFJlcXVlc3RlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnc29ydC1jb250cm9sLnNvcnQucmVxdWVzdGVkJztcbiAgICB9O1xuXG4gICAgX2NsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIGlmICh0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdzb3J0ZWQnKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFNvcnRDb250cm9sLmdldFNvcnRSZXF1ZXN0ZWRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgZGV0YWlsOiB7XG4gICAgICAgICAgICAgICAga2V5czogdGhpcy5rZXlzXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pKTtcbiAgICB9O1xuXG4gICAgc2V0U29ydGVkICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ3NvcnRlZCcpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnbGluaycpO1xuICAgIH07XG5cbiAgICBzZXROb3RTb3J0ZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnc29ydGVkJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdsaW5rJyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0Q29udHJvbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnQtY29udHJvbC5qcyIsImNsYXNzIFNvcnRhYmxlSXRlbSB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zb3J0VmFsdWVzID0gSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zb3J0LXZhbHVlcycpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IGtleVxuICAgICAqXG4gICAgICogQHJldHVybnMgeyp9XG4gICAgICovXG4gICAgZ2V0U29ydFZhbHVlIChrZXkpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuc29ydFZhbHVlc1trZXldO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0YWJsZUl0ZW07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtLmpzIiwibGV0IFRhc2sgPSByZXF1aXJlKCcuL3Rhc2snKTtcblxuY2xhc3MgVGFza0xpc3Qge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMucGFnZUluZGV4ID0gZWxlbWVudCA/IHBhcnNlSW50KGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXBhZ2UtaW5kZXgnKSwgMTApIDogbnVsbDtcbiAgICAgICAgdGhpcy50YXNrcyA9IHt9O1xuXG4gICAgICAgIGlmIChlbGVtZW50KSB7XG4gICAgICAgICAgICBbXS5mb3JFYWNoLmNhbGwoZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzaycpLCAodGFza0VsZW1lbnQpID0+IHtcbiAgICAgICAgICAgICAgICBsZXQgdGFzayA9IG5ldyBUYXNrKHRhc2tFbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLnRhc2tzW3Rhc2suZ2V0SWQoKV0gPSB0YXNrO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7bnVtYmVyIHwgbnVsbH1cbiAgICAgKi9cbiAgICBnZXRQYWdlSW5kZXggKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5wYWdlSW5kZXg7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgaGFzUGFnZUluZGV4ICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMucGFnZUluZGV4ICE9PSBudWxsO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nW119IHN0YXRlc1xuICAgICAqXG4gICAgICogQHJldHVybnMge1Rhc2tbXX1cbiAgICAgKi9cbiAgICBnZXRUYXNrc0J5U3RhdGVzIChzdGF0ZXMpIHtcbiAgICAgICAgY29uc3Qgc3RhdGVzTGVuZ3RoID0gc3RhdGVzLmxlbmd0aDtcbiAgICAgICAgbGV0IHRhc2tzID0gW107XG5cbiAgICAgICAgZm9yIChsZXQgc3RhdGVJbmRleCA9IDA7IHN0YXRlSW5kZXggPCBzdGF0ZXNMZW5ndGg7IHN0YXRlSW5kZXgrKykge1xuICAgICAgICAgICAgbGV0IHN0YXRlID0gc3RhdGVzW3N0YXRlSW5kZXhdO1xuXG4gICAgICAgICAgICB0YXNrcyA9IHRhc2tzLmNvbmNhdCh0aGlzLmdldFRhc2tzQnlTdGF0ZShzdGF0ZSkpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRhc2tzO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30gc3RhdGVcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtUYXNrW119XG4gICAgICovXG4gICAgZ2V0VGFza3NCeVN0YXRlIChzdGF0ZSkge1xuICAgICAgICBsZXQgdGFza3MgPSBbXTtcbiAgICAgICAgT2JqZWN0LmtleXModGhpcy50YXNrcykuZm9yRWFjaCgodGFza0lkKSA9PiB7XG4gICAgICAgICAgICBsZXQgdGFzayA9IHRoaXMudGFza3NbdGFza0lkXTtcblxuICAgICAgICAgICAgaWYgKHRhc2suZ2V0U3RhdGUoKSA9PT0gc3RhdGUpIHtcbiAgICAgICAgICAgICAgICB0YXNrcy5wdXNoKHRhc2spO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gdGFza3M7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7VGFza0xpc3R9IHVwZGF0ZWRUYXNrTGlzdFxuICAgICAqL1xuICAgIHVwZGF0ZUZyb21UYXNrTGlzdCAodXBkYXRlZFRhc2tMaXN0KSB7XG4gICAgICAgIE9iamVjdC5rZXlzKHVwZGF0ZWRUYXNrTGlzdC50YXNrcykuZm9yRWFjaCgodGFza0lkKSA9PiB7XG4gICAgICAgICAgICBsZXQgdXBkYXRlZFRhc2sgPSB1cGRhdGVkVGFza0xpc3QudGFza3NbdGFza0lkXTtcbiAgICAgICAgICAgIGxldCB0YXNrID0gdGhpcy50YXNrc1t1cGRhdGVkVGFzay5nZXRJZCgpXTtcblxuICAgICAgICAgICAgdGFzay51cGRhdGVGcm9tVGFzayh1cGRhdGVkVGFzayk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza0xpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLWxpc3QuanMiLCJjbGFzcyBUYXNrUXVldWUge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMudmFsdWUgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy52YWx1ZScpO1xuICAgICAgICB0aGlzLmxhYmVsID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcubGFiZWwtdmFsdWUnKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5sYWJlbC5zdHlsZS53aWR0aCA9IHRoaXMubGFiZWwuZ2V0QXR0cmlidXRlKCdkYXRhLXdpZHRoJykgKyAnJSc7XG4gICAgfTtcblxuICAgIGdldFF1ZXVlSWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1xdWV1ZS1pZCcpO1xuICAgIH07XG5cbiAgICBzZXRWYWx1ZSAodmFsdWUpIHtcbiAgICAgICAgdGhpcy52YWx1ZS5pbm5lclRleHQgPSB2YWx1ZTtcbiAgICB9O1xuXG4gICAgc2V0V2lkdGggKHdpZHRoKSB7XG4gICAgICAgIHRoaXMubGFiZWwuc3R5bGUud2lkdGggPSB3aWR0aCArICclJztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tRdWV1ZTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWUuanMiLCJsZXQgVGFza1F1ZXVlID0gcmVxdWlyZSgnLi90YXNrLXF1ZXVlJyk7XG5cbmNsYXNzIFRhc2tRdWV1ZXMge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMucXVldWVzID0ge307XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKGVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnF1ZXVlJyksIChxdWV1ZUVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIGxldCBxdWV1ZSA9IG5ldyBUYXNrUXVldWUocXVldWVFbGVtZW50KTtcbiAgICAgICAgICAgIHF1ZXVlLmluaXQoKTtcbiAgICAgICAgICAgIHRoaXMucXVldWVzW3F1ZXVlLmdldFF1ZXVlSWQoKV0gPSBxdWV1ZTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgcmVuZGVyICh0YXNrQ291bnQsIHRhc2tDb3VudEJ5U3RhdGUpIHtcbiAgICAgICAgbGV0IGdldFdpZHRoRm9yU3RhdGUgPSAoc3RhdGUpID0+IHtcbiAgICAgICAgICAgIGlmICh0YXNrQ291bnQgPT09IDApIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gMDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCF0YXNrQ291bnRCeVN0YXRlLmhhc093blByb3BlcnR5KHN0YXRlKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAodGFza0NvdW50QnlTdGF0ZVtzdGF0ZV0gPT09IDApIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gMDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIE1hdGguY2VpbCh0YXNrQ291bnRCeVN0YXRlW3N0YXRlXSAvIHRhc2tDb3VudCAqIDEwMCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgT2JqZWN0LmtleXModGFza0NvdW50QnlTdGF0ZSkuZm9yRWFjaCgoc3RhdGUpID0+IHtcbiAgICAgICAgICAgIGxldCBxdWV1ZSA9IHRoaXMucXVldWVzW3N0YXRlXTtcbiAgICAgICAgICAgIGlmIChxdWV1ZSkge1xuICAgICAgICAgICAgICAgIHF1ZXVlLnNldFZhbHVlKHRhc2tDb3VudEJ5U3RhdGVbc3RhdGVdKTtcbiAgICAgICAgICAgICAgICBxdWV1ZS5zZXRXaWR0aChnZXRXaWR0aEZvclN0YXRlKHN0YXRlKSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza1F1ZXVlcztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWVzLmpzIiwiY2xhc3MgVGFzayB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICB9XG5cbiAgICBnZXRTdGF0ZSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG4gICAgfTtcblxuICAgIGdldElkICgpIHtcbiAgICAgICAgcmV0dXJuIHBhcnNlSW50KHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGFzay1pZCcpLCAxMCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtUYXNrfSB1cGRhdGVkVGFza1xuICAgICAqL1xuICAgIHVwZGF0ZUZyb21UYXNrICh1cGRhdGVkVGFzaykge1xuICAgICAgICB0aGlzLmVsZW1lbnQucGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQodXBkYXRlZFRhc2suZWxlbWVudCwgdGhpcy5lbGVtZW50KTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdXBkYXRlZFRhc2suZWxlbWVudDtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2s7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLmpzIiwibGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuLi8uLi9zZXJ2aWNlcy9odHRwLWNsaWVudCcpO1xubGV0IEFsZXJ0RmFjdG9yeSA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2FsZXJ0LWZhY3RvcnknKTtcblxuY2xhc3MgVGVzdEFsZXJ0Q29udGFpbmVyIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmFsZXJ0ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuYWxlcnQtYW1tZW5kbWVudCcpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICBpZiAoIXRoaXMuYWxlcnQpIHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCBhbGVydCA9IEFsZXJ0RmFjdG9yeS5jcmVhdGVGcm9tQ29udGVudCh0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudCwgZXZlbnQuZGV0YWlsLnJlc3BvbnNlKTtcbiAgICAgICAgYWxlcnQuc2V0U3R5bGUoJ2luZm8nKTtcbiAgICAgICAgYWxlcnQud3JhcENvbnRlbnRJbkNvbnRhaW5lcigpO1xuICAgICAgICBhbGVydC5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FsZXJ0LWFtbWVuZG1lbnQnKTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuYXBwZW5kQ2hpbGQoYWxlcnQuZWxlbWVudCk7XG4gICAgICAgIHRoaXMuYWxlcnQgPSBhbGVydDtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgncmV2ZWFsJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCd0cmFuc2l0aW9uZW5kJywgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5hbGVydC5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ3JldmVhbCcpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgcmVuZGVyVXJsTGltaXROb3RpZmljYXRpb24gKCkge1xuICAgICAgICBpZiAoIXRoaXMuYWxlcnQpIHtcbiAgICAgICAgICAgIEh0dHBDbGllbnQuZ2V0KHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdXJsLWxpbWl0LW5vdGlmaWNhdGlvbi11cmwnKSwgJ3RleHQnLCB0aGlzLmVsZW1lbnQsICdkZWZhdWx0Jyk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RBbGVydENvbnRhaW5lcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rlc3QtYWxlcnQtY29udGFpbmVyLmpzIiwibGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuLi8uLi9zZXJ2aWNlcy9odHRwLWNsaWVudCcpO1xubGV0IEljb24gPSByZXF1aXJlKCcuLi9lbGVtZW50L2ljb24nKTtcblxuY2xhc3MgVGVzdExvY2tVbmxvY2sge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc3RhdGUgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdGF0ZScpO1xuICAgICAgICB0aGlzLmRhdGEgPSB7XG4gICAgICAgICAgICBsb2NrZWQ6IEpTT04ucGFyc2UoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbG9ja2VkJykpLFxuICAgICAgICAgICAgdW5sb2NrZWQ6IEpTT04ucGFyc2UoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdW5sb2NrZWQnKSlcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5pY29uID0gbmV3IEljb24oZWxlbWVudC5xdWVyeVNlbGVjdG9yKEljb24uZ2V0U2VsZWN0b3IoKSkpO1xuICAgICAgICB0aGlzLmFjdGlvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLmFjdGlvbicpO1xuICAgICAgICB0aGlzLmRlc2NyaXB0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuZGVzY3JpcHRpb24nKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2ludmlzaWJsZScpO1xuICAgICAgICB0aGlzLl9yZW5kZXIoKTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9jbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdkZS1lbXBoYXNpemUnKTtcbiAgICAgICAgICAgIHRoaXMuX3RvZ2dsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3RvZ2dsZSAoKSB7XG4gICAgICAgIHRoaXMuc3RhdGUgPSB0aGlzLnN0YXRlID09PSAnbG9ja2VkJyA/ICd1bmxvY2tlZCcgOiAnbG9ja2VkJztcbiAgICAgICAgdGhpcy5fcmVuZGVyKCk7XG4gICAgfTtcblxuICAgIF9yZW5kZXIgKCkge1xuICAgICAgICB0aGlzLmljb24ucmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcygpO1xuXG4gICAgICAgIGxldCBzdGF0ZURhdGEgPSB0aGlzLmRhdGFbdGhpcy5zdGF0ZV07XG5cbiAgICAgICAgdGhpcy5pY29uLnNldEF2YWlsYWJsZSgnZmEtJyArIHN0YXRlRGF0YS5pY29uKTtcbiAgICAgICAgdGhpcy5hY3Rpb24uaW5uZXJUZXh0ID0gc3RhdGVEYXRhLmFjdGlvbjtcbiAgICAgICAgdGhpcy5kZXNjcmlwdGlvbi5pbm5lclRleHQgPSBzdGF0ZURhdGEuZGVzY3JpcHRpb247XG4gICAgfTtcblxuICAgIF9jbGlja0V2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB0aGlzLmljb24ucmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcygpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdkZS1lbXBoYXNpemUnKTtcbiAgICAgICAgdGhpcy5pY29uLnNldEJ1c3koKTtcblxuICAgICAgICBIdHRwQ2xpZW50LnBvc3QodGhpcy5kYXRhW3RoaXMuc3RhdGVdLnVybCwgdGhpcy5lbGVtZW50LCAnZGVmYXVsdCcpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdExvY2tVbmxvY2s7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90ZXN0LWxvY2stdW5sb2NrLmpzIiwibGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IHJlcXVpcmUoJy4vZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwnKTtcblxuY2xhc3MgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyB7XG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50LCBodHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwsIGFjdGlvbkJhZGdlLCBzdGF0dXNFbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwgPSBodHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWw7XG4gICAgICAgIHRoaXMuYWN0aW9uQmFkZ2UgPSBhY3Rpb25CYWRnZTtcbiAgICAgICAgdGhpcy5zdGF0dXNFbGVtZW50ID0gc3RhdHVzRWxlbWVudDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy5tb2RhbC5vcGVuZWQnO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLm1vZGFsLmNsb3NlZCc7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICBsZXQgbW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICBpZiAodGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwuaXNFbXB0eSgpKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zdGF0dXNFbGVtZW50LmlubmVyVGV4dCA9ICdub3QgZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrTm90RW5hYmxlZCgpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ2VuYWJsZWQnO1xuICAgICAgICAgICAgICAgIHRoaXMuYWN0aW9uQmFkZ2UubWFya0VuYWJsZWQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5pbml0KCk7XG5cbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5nZXRPcGVuZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsT3BlbmVkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5nZXRDbG9zZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgbW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIoKTtcbiAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucy5nZXRNb2RhbENsb3NlZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMuanMiLCJsZXQgTGlzdGVkVGVzdEZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9saXN0ZWQtdGVzdC1mYWN0b3J5Jyk7XG5cbmNsYXNzIExpc3RlZFRlc3RDb2xsZWN0aW9uIHtcbiAgICBjb25zdHJ1Y3RvciAoKSB7XG4gICAgICAgIHRoaXMubGlzdGVkVGVzdHMgPSB7fTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0xpc3RlZFRlc3R9IGxpc3RlZFRlc3RcbiAgICAgKi9cbiAgICBhZGQgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0c1tsaXN0ZWRUZXN0LmdldFRlc3RJZCgpXSA9IGxpc3RlZFRlc3Q7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7TGlzdGVkVGVzdH0gbGlzdGVkVGVzdFxuICAgICAqL1xuICAgIHJlbW92ZSAobGlzdGVkVGVzdCkge1xuICAgICAgICBpZiAodGhpcy5jb250YWlucyhsaXN0ZWRUZXN0KSkge1xuICAgICAgICAgICAgZGVsZXRlIHRoaXMubGlzdGVkVGVzdHNbbGlzdGVkVGVzdC5nZXRUZXN0SWQoKV07XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtMaXN0ZWRUZXN0fSBsaXN0ZWRUZXN0XG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBjb250YWlucyAobGlzdGVkVGVzdCkge1xuICAgICAgICByZXR1cm4gdGhpcy5jb250YWluc1Rlc3RJZChsaXN0ZWRUZXN0LmdldFRlc3RJZCgpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHRlc3RJZFxuICAgICAqXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgY29udGFpbnNUZXN0SWQgKHRlc3RJZCkge1xuICAgICAgICByZXR1cm4gT2JqZWN0LmtleXModGhpcy5saXN0ZWRUZXN0cykuaW5jbHVkZXModGVzdElkKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gdGVzdElkXG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7TGlzdGVkVGVzdHxudWxsfVxuICAgICAqL1xuICAgIGdldCAodGVzdElkKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmNvbnRhaW5zVGVzdElkKHRlc3RJZCkgPyB0aGlzLmxpc3RlZFRlc3RzW3Rlc3RJZF0gOiBudWxsO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7ZnVuY3Rpb259IGNhbGxiYWNrXG4gICAgICovXG4gICAgZm9yRWFjaCAoY2FsbGJhY2spIHtcbiAgICAgICAgT2JqZWN0LmtleXModGhpcy5saXN0ZWRUZXN0cykuZm9yRWFjaCgodGVzdElkKSA9PiB7XG4gICAgICAgICAgICBjYWxsYmFjayh0aGlzLmxpc3RlZFRlc3RzW3Rlc3RJZF0pO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtOb2RlTGlzdH0gbm9kZUxpc3RcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtMaXN0ZWRUZXN0Q29sbGVjdGlvbn1cbiAgICAgKi9cbiAgICBzdGF0aWMgY3JlYXRlRnJvbU5vZGVMaXN0IChub2RlTGlzdCkge1xuICAgICAgICBsZXQgY29sbGVjdGlvbiA9IG5ldyBMaXN0ZWRUZXN0Q29sbGVjdGlvbigpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbChub2RlTGlzdCwgKGxpc3RlZFRlc3RFbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBjb2xsZWN0aW9uLmFkZChMaXN0ZWRUZXN0RmFjdG9yeS5jcmVhdGVGcm9tRWxlbWVudChsaXN0ZWRUZXN0RWxlbWVudCkpO1xuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gY29sbGVjdGlvbjtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTGlzdGVkVGVzdENvbGxlY3Rpb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvbGlzdGVkLXRlc3QtY29sbGVjdGlvbi5qcyIsImxldCBTb3J0Q29udHJvbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sJyk7XG5cbmNsYXNzIFNvcnRDb250cm9sQ29sbGVjdGlvbiB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtTb3J0Q29udHJvbFtdfSBjb250cm9sc1xuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChjb250cm9scykge1xuICAgICAgICB0aGlzLmNvbnRyb2xzID0gY29udHJvbHM7XG4gICAgfTtcblxuICAgIHNldFNvcnRlZCAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmNvbnRyb2xzLmZvckVhY2goKGNvbnRyb2wpID0+IHtcbiAgICAgICAgICAgIGlmIChjb250cm9sLmVsZW1lbnQgPT09IGVsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICBjb250cm9sLnNldFNvcnRlZCgpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBjb250cm9sLnNldE5vdFNvcnRlZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRDb250cm9sQ29sbGVjdGlvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9zb3J0LWNvbnRyb2wtY29sbGVjdGlvbi5qcyIsImNsYXNzIFNvcnRhYmxlSXRlbUxpc3Qge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7U29ydGFibGVJdGVtW119IGl0ZW1zXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGl0ZW1zKSB7XG4gICAgICAgIHRoaXMuaXRlbXMgPSBpdGVtcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmdbXX0ga2V5c1xuICAgICAqIEByZXR1cm5zIHtTb3J0YWJsZUl0ZW1bXX1cbiAgICAgKi9cbiAgICBzb3J0IChrZXlzKSB7XG4gICAgICAgIGxldCBpbmRleCA9IFtdO1xuICAgICAgICBsZXQgc29ydGVkSXRlbXMgPSBbXTtcblxuICAgICAgICB0aGlzLml0ZW1zLmZvckVhY2goKHNvcnRhYmxlSXRlbSwgcG9zaXRpb24pID0+IHtcbiAgICAgICAgICAgIGxldCB2YWx1ZXMgPSBbXTtcblxuICAgICAgICAgICAga2V5cy5mb3JFYWNoKChrZXkpID0+IHtcbiAgICAgICAgICAgICAgICBsZXQgdmFsdWUgPSBzb3J0YWJsZUl0ZW0uZ2V0U29ydFZhbHVlKGtleSk7XG4gICAgICAgICAgICAgICAgaWYgKE51bWJlci5pc0ludGVnZXIodmFsdWUpKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhbHVlID0gKDEvdmFsdWUpLnRvU3RyaW5nKCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgdmFsdWVzLnB1c2godmFsdWUpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGluZGV4LnB1c2goe1xuICAgICAgICAgICAgICAgIHBvc2l0aW9uOiBwb3NpdGlvbixcbiAgICAgICAgICAgICAgICB2YWx1ZTogdmFsdWVzLmpvaW4oJywnKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGluZGV4LnNvcnQodGhpcy5fY29tcGFyZUZ1bmN0aW9uKTtcblxuICAgICAgICBpbmRleC5mb3JFYWNoKChpbmRleEl0ZW0pID0+IHtcbiAgICAgICAgICAgIHNvcnRlZEl0ZW1zLnB1c2godGhpcy5pdGVtc1tpbmRleEl0ZW0ucG9zaXRpb25dKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIHNvcnRlZEl0ZW1zO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge09iamVjdH0gYVxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBiXG4gICAgICogQHJldHVybnMge251bWJlcn1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jb21wYXJlRnVuY3Rpb24gKGEsIGIpIHtcbiAgICAgICAgaWYgKGEudmFsdWUgPCBiLnZhbHVlKSB7XG4gICAgICAgICAgICByZXR1cm4gLTE7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoYS52YWx1ZSA+IGIudmFsdWUpIHtcbiAgICAgICAgICAgIHJldHVybiAxO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIDA7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0YWJsZUl0ZW1MaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL3NvcnRhYmxlLWl0ZW0tbGlzdC5qcyIsImxldCB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlciA9IHJlcXVpcmUoJy4uL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlcicpO1xubGV0IFRlc3RTdGFydEZvcm0gPSByZXF1aXJlKCcuLi9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtJyk7XG5sZXQgUmVjZW50VGVzdExpc3QgPSByZXF1aXJlKCcuLi9kYXNoYm9hcmQvcmVjZW50LXRlc3QtbGlzdCcpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zJyk7XG5sZXQgQ29va2llT3B0aW9uc0ZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5Jyk7XG5sZXQgQ29va2llT3B0aW9ucyA9IHJlcXVpcmUoJy4uL21vZGVsL2Nvb2tpZS1vcHRpb25zJyk7XG5cbmNsYXNzIERhc2hib2FyZCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLnRlc3RTdGFydEZvcm0gPSBuZXcgVGVzdFN0YXJ0Rm9ybShkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGVzdC1zdGFydC1mb3JtJykpO1xuICAgICAgICB0aGlzLnJlY2VudFRlc3RMaXN0ID0gbmV3IFJlY2VudFRlc3RMaXN0KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy50ZXN0LWxpc3QnKSk7XG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5LmNyZWF0ZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuaHR0cC1hdXRoZW50aWNhdGlvbi10ZXN0LW9wdGlvbicpKTtcbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zID0gQ29va2llT3B0aW9uc0ZhY3RvcnkuY3JlYXRlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jb29raWVzLXRlc3Qtb3B0aW9uJykpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5yZWNlbnQtYWN0aXZpdHktY29udGFpbmVyJykuY2xhc3NMaXN0LnJlbW92ZSgnaGlkZGVuJyk7XG5cbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIodGhpcy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzay10eXBlLm5vdC1hdmFpbGFibGUnKSk7XG4gICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybS5pbml0KCk7XG4gICAgICAgIHRoaXMucmVjZW50VGVzdExpc3QuaW5pdCgpO1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuaW5pdCgpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMuaW5pdCgpO1xuXG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsT3BlbmVkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybS5kaXNhYmxlKCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsQ2xvc2VkRXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybS5lbmFibGUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmRpc2FibGUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmVuYWJsZSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IERhc2hib2FyZDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL2Rhc2hib2FyZC5qcyIsImxldCBNb2RhbCA9IHJlcXVpcmUoJy4uL3Rlc3QtaGlzdG9yeS9tb2RhbCcpO1xubGV0IFN1Z2dlc3Rpb25zID0gcmVxdWlyZSgnLi4vdGVzdC1oaXN0b3J5L3N1Z2dlc3Rpb25zJyk7XG5sZXQgTGlzdGVkVGVzdENvbGxlY3Rpb24gPSByZXF1aXJlKCcuLi9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uJyk7XG5cbm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGRvY3VtZW50KSB7XG4gICAgY29uc3QgbW9kYWxJZCA9ICdmaWx0ZXItb3B0aW9ucy1tb2RhbCc7XG4gICAgY29uc3QgZmlsdGVyT3B0aW9uc1NlbGVjdG9yID0gJy5hY3Rpb24tYmFkZ2UtZmlsdGVyLW9wdGlvbnMnO1xuICAgIGNvbnN0IG1vZGFsRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG1vZGFsSWQpO1xuICAgIGxldCBmaWx0ZXJPcHRpb25zRWxlbWVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZmlsdGVyT3B0aW9uc1NlbGVjdG9yKTtcblxuICAgIGxldCBtb2RhbCA9IG5ldyBNb2RhbChtb2RhbEVsZW1lbnQpO1xuICAgIGxldCBzdWdnZXN0aW9ucyA9IG5ldyBTdWdnZXN0aW9ucyhkb2N1bWVudCwgbW9kYWxFbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zb3VyY2UtdXJsJykpO1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKi9cbiAgICBsZXQgc3VnZ2VzdGlvbnNMb2FkZWRFdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgIG1vZGFsLnNldFN1Z2dlc3Rpb25zKGV2ZW50LmRldGFpbCk7XG4gICAgICAgIGZpbHRlck9wdGlvbnNFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ3Zpc2libGUnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKi9cbiAgICBsZXQgZmlsdGVyQ2hhbmdlZEV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgbGV0IGZpbHRlciA9IGV2ZW50LmRldGFpbDtcbiAgICAgICAgbGV0IHNlYXJjaCA9IChmaWx0ZXIgPT09ICcnKSA/ICcnIDogJz9maWx0ZXI9JyArIGZpbHRlcjtcbiAgICAgICAgbGV0IGN1cnJlbnRTZWFyY2ggPSB3aW5kb3cubG9jYXRpb24uc2VhcmNoO1xuXG4gICAgICAgIGlmIChjdXJyZW50U2VhcmNoID09PSAnJyAmJiBmaWx0ZXIgPT09ICcnKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoc2VhcmNoICE9PSBjdXJyZW50U2VhcmNoKSB7XG4gICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uc2VhcmNoID0gc2VhcmNoO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoc3VnZ2VzdGlvbnMubG9hZGVkRXZlbnROYW1lLCBzdWdnZXN0aW9uc0xvYWRlZEV2ZW50TGlzdGVuZXIpO1xuICAgIG1vZGFsRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKG1vZGFsLmZpbHRlckNoYW5nZWRFdmVudE5hbWUsIGZpbHRlckNoYW5nZWRFdmVudExpc3RlbmVyKTtcblxuICAgIHN1Z2dlc3Rpb25zLnJldHJpZXZlKCk7XG5cbiAgICBsZXQgbGlzdGVkVGVzdENvbGxlY3Rpb24gPSBMaXN0ZWRUZXN0Q29sbGVjdGlvbi5jcmVhdGVGcm9tTm9kZUxpc3QoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmxpc3RlZC10ZXN0JykpO1xuICAgIGxpc3RlZFRlc3RDb2xsZWN0aW9uLmZvckVhY2goKGxpc3RlZFRlc3QpID0+IHtcbiAgICAgICAgbGlzdGVkVGVzdC5lbmFibGUoKTtcbiAgICB9KTtcbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFnZS90ZXN0LWhpc3RvcnkuanMiLCJsZXQgU3VtbWFyeSA9IHJlcXVpcmUoJy4uL3Rlc3QtcHJvZ3Jlc3Mvc3VtbWFyeScpO1xubGV0IFRhc2tMaXN0ID0gcmVxdWlyZSgnLi4vdGVzdC1wcm9ncmVzcy90YXNrLWxpc3QnKTtcbmxldCBUYXNrTGlzdFBhZ2luYXRpb24gPSByZXF1aXJlKCcuLi90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC1wYWdpbmF0b3InKTtcbmxldCBUZXN0QWxlcnRDb250YWluZXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Rlc3QtYWxlcnQtY29udGFpbmVyJyk7XG5sZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5cbmNsYXNzIFRlc3RQcm9ncmVzcyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5wYWdlTGVuZ3RoID0gMTAwO1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuc3VtbWFyeSA9IG5ldyBTdW1tYXJ5KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1zdW1tYXJ5JykpO1xuICAgICAgICB0aGlzLnRhc2tMaXN0RWxlbWVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy50ZXN0LXByb2dyZXNzLXRhc2tzJyk7XG4gICAgICAgIHRoaXMudGFza0xpc3QgPSBuZXcgVGFza0xpc3QodGhpcy50YXNrTGlzdEVsZW1lbnQsIHRoaXMucGFnZUxlbmd0aCk7XG4gICAgICAgIHRoaXMuYWxlcnRDb250YWluZXIgPSBuZXcgVGVzdEFsZXJ0Q29udGFpbmVyKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5hbGVydC1jb250YWluZXInKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uID0gbnVsbDtcbiAgICAgICAgdGhpcy50YXNrTGlzdElzSW5pdGlhbGl6ZWQgPSBmYWxzZTtcbiAgICAgICAgdGhpcy5zdW1tYXJ5RGF0YSA9IG51bGw7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuc3VtbWFyeS5pbml0KCk7XG4gICAgICAgIHRoaXMuYWxlcnRDb250YWluZXIuaW5pdCgpO1xuICAgICAgICB0aGlzLl9hZGRFdmVudExpc3RlbmVycygpO1xuXG4gICAgICAgIHRoaXMuX3JlZnJlc2hTdW1tYXJ5KCk7XG4gICAgfTtcblxuICAgIF9hZGRFdmVudExpc3RlbmVycyAoKSB7XG4gICAgICAgIHRoaXMuc3VtbWFyeS5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoU3VtbWFyeS5nZXRSZW5kZXJBbW1lbmRtZW50RXZlbnROYW1lKCksICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuYWxlcnRDb250YWluZXIucmVuZGVyVXJsTGltaXROb3RpZmljYXRpb24oKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5ib2R5LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLnRhc2tMaXN0RWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFRhc2tMaXN0LmdldEluaXRpYWxpemVkRXZlbnROYW1lKCksIHRoaXMuX3Rhc2tMaXN0SW5pdGlhbGl6ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBfYWRkUGFnaW5hdGlvbkV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFRhc2tMaXN0UGFnaW5hdGlvbi5nZXRTZWxlY3RQYWdlRXZlbnROYW1lKCksIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IGV2ZW50LmRldGFpbDtcblxuICAgICAgICAgICAgdGhpcy50YXNrTGlzdC5zZXRDdXJyZW50UGFnZUluZGV4KHBhZ2VJbmRleCk7XG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5zZWxlY3RQYWdlKHBhZ2VJbmRleCk7XG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnJlbmRlcihwYWdlSW5kZXgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSgpLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgIGxldCBwYWdlSW5kZXggPSBNYXRoLm1heCh0aGlzLnRhc2tMaXN0LmN1cnJlbnRQYWdlSW5kZXggLSAxLCAwKTtcblxuICAgICAgICAgICAgdGhpcy50YXNrTGlzdC5zZXRDdXJyZW50UGFnZUluZGV4KHBhZ2VJbmRleCk7XG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5zZWxlY3RQYWdlKHBhZ2VJbmRleCk7XG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnJlbmRlcihwYWdlSW5kZXgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdE5leHRQYWdlRXZlbnROYW1lKCksIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IE1hdGgubWluKHRoaXMudGFza0xpc3QuY3VycmVudFBhZ2VJbmRleCArIDEsIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnBhZ2VDb3VudCAtIDEpO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBpZiAoZXZlbnQuZGV0YWlsLnJlcXVlc3RJZCA9PT0gJ3Rlc3QtcHJvZ3Jlc3Muc3VtbWFyeS51cGRhdGUnKSB7XG4gICAgICAgICAgICBpZiAoZXZlbnQuZGV0YWlsLnJlcXVlc3QucmVzcG9uc2VVUkwuaW5kZXhPZih3aW5kb3cubG9jYXRpb24udG9TdHJpbmcoKSkgIT09IDApIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN1bW1hcnkucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQoMTAwKTtcbiAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IGV2ZW50LmRldGFpbC5yZXF1ZXN0LnJlc3BvbnNlVVJMO1xuXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLnN1bW1hcnlEYXRhID0gZXZlbnQuZGV0YWlsLnJlc3BvbnNlO1xuXG4gICAgICAgICAgICBsZXQgc3RhdGUgPSB0aGlzLnN1bW1hcnlEYXRhLnRlc3Quc3RhdGU7XG4gICAgICAgICAgICBsZXQgdGFza0NvdW50ID0gdGhpcy5zdW1tYXJ5RGF0YS5yZW1vdGVfdGVzdC50YXNrX2NvdW50O1xuICAgICAgICAgICAgbGV0IGlzU3RhdGVRdWV1ZWRPckluUHJvZ3Jlc3MgPSBbJ3F1ZXVlZCcsICdpbi1wcm9ncmVzcyddLmluZGV4T2Yoc3RhdGUpICE9PSAtMTtcblxuICAgICAgICAgICAgdGhpcy5fc2V0Qm9keUpvYkNsYXNzKHRoaXMuZG9jdW1lbnQuYm9keS5jbGFzc0xpc3QsIHN0YXRlKTtcbiAgICAgICAgICAgIHRoaXMuc3VtbWFyeS5yZW5kZXIodGhpcy5zdW1tYXJ5RGF0YSk7XG5cbiAgICAgICAgICAgIGlmICh0YXNrQ291bnQgPiAwICYmIGlzU3RhdGVRdWV1ZWRPckluUHJvZ3Jlc3MgJiYgIXRoaXMudGFza0xpc3RJc0luaXRpYWxpemVkICYmICF0aGlzLnRhc2tMaXN0LmlzSW5pdGlhbGl6aW5nKSB7XG4gICAgICAgICAgICAgICAgdGhpcy50YXNrTGlzdC5pbml0KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICB3aW5kb3cuc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9yZWZyZXNoU3VtbWFyeSgpO1xuICAgICAgICB9LCAzMDAwKTtcbiAgICB9O1xuXG4gICAgX3Rhc2tMaXN0SW5pdGlhbGl6ZWRFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgdGhpcy50YXNrTGlzdElzSW5pdGlhbGl6ZWQgPSB0cnVlO1xuICAgICAgICB0aGlzLnRhc2tMaXN0LnJlbmRlcigwKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24gPSBuZXcgVGFza0xpc3RQYWdpbmF0aW9uKHRoaXMucGFnZUxlbmd0aCwgdGhpcy5zdW1tYXJ5RGF0YS5yZW1vdGVfdGVzdC50YXNrX2NvdW50KTtcblxuICAgICAgICBpZiAodGhpcy50YXNrTGlzdFBhZ2luYXRpb24uaXNSZXF1aXJlZCgpICYmICF0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5pc1JlbmRlcmVkKCkpIHtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmluaXQodGhpcy5fY3JlYXRlUGFnaW5hdGlvbkVsZW1lbnQoKSk7XG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldFBhZ2luYXRpb25FbGVtZW50KHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy5fYWRkUGFnaW5hdGlvbkV2ZW50TGlzdGVuZXJzKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge0VsZW1lbnR9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlUGFnaW5hdGlvbkVsZW1lbnQgKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmlubmVySFRNTCA9IHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmNyZWF0ZU1hcmt1cCgpO1xuXG4gICAgICAgIHJldHVybiBjb250YWluZXIucXVlcnlTZWxlY3RvcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0b3IoKSk7XG4gICAgfVxuXG4gICAgX3JlZnJlc2hTdW1tYXJ5ICgpIHtcbiAgICAgICAgbGV0IHN1bW1hcnlVcmwgPSB0aGlzLmRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXN1bW1hcnktdXJsJyk7XG4gICAgICAgIGxldCBub3cgPSBuZXcgRGF0ZSgpO1xuXG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbihzdW1tYXJ5VXJsICsgJz90aW1lc3RhbXA9JyArIG5vdy5nZXRUaW1lKCksIHRoaXMuZG9jdW1lbnQuYm9keSwgJ3Rlc3QtcHJvZ3Jlc3Muc3VtbWFyeS51cGRhdGUnKTtcbiAgICB9O1xuICAgIC8qKlxuICAgICAqXG4gICAgICogQHBhcmFtIHtET01Ub2tlbkxpc3R9IGJvZHlDbGFzc0xpc3RcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30gdGVzdFN0YXRlXG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfc2V0Qm9keUpvYkNsYXNzIChib2R5Q2xhc3NMaXN0LCB0ZXN0U3RhdGUpIHtcbiAgICAgICAgbGV0IGpvYkNsYXNzUHJlZml4ID0gJ2pvYi0nO1xuICAgICAgICBib2R5Q2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNsYXNzTmFtZS5pbmRleE9mKGpvYkNsYXNzUHJlZml4KSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIGNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgYm9keUNsYXNzTGlzdC5hZGQoJ2pvYi0nICsgdGVzdFN0YXRlKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RQcm9ncmVzcztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcHJvZ3Jlc3MuanMiLCJsZXQgQnlQYWdlTGlzdCA9IHJlcXVpcmUoJy4uL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvYnktcGFnZS1saXN0Jyk7XG5sZXQgQnlFcnJvckxpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QnKTtcblxuY2xhc3MgVGVzdFJlc3VsdHNCeVRhc2tUeXBlIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG5cbiAgICAgICAgbGV0IGJ5UGFnZUNvbnRhaW5lckVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuYnktcGFnZS1jb250YWluZXInKTtcbiAgICAgICAgbGV0IGJ5RXJyb3JDb250YWluZXJFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmJ5LWVycm9yLWNvbnRhaW5lcicpO1xuXG4gICAgICAgIHRoaXMuYnlQYWdlTGlzdCA9IGJ5UGFnZUNvbnRhaW5lckVsZW1lbnQgPyBuZXcgQnlQYWdlTGlzdChieVBhZ2VDb250YWluZXJFbGVtZW50KSA6IG51bGw7XG4gICAgICAgIHRoaXMuYnlFcnJvckxpc3QgPSBieUVycm9yQ29udGFpbmVyRWxlbWVudCA/IG5ldyBCeUVycm9yTGlzdChieUVycm9yQ29udGFpbmVyRWxlbWVudCkgOiBudWxsO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICBpZiAodGhpcy5ieVBhZ2VMaXN0KSB7XG4gICAgICAgICAgICB0aGlzLmJ5UGFnZUxpc3QuaW5pdCgpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMuYnlFcnJvckxpc3QpIHtcbiAgICAgICAgICAgIHRoaXMuYnlFcnJvckxpc3QuaW5pdCgpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0UmVzdWx0c0J5VGFza1R5cGU7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlLmpzIiwibGV0IFByb2dyZXNzQmFyID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9wcm9ncmVzcy1iYXInKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgVGVzdFJlc3VsdHNQcmVwYXJpbmcge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy51bnJldHJpZXZlZFJlbW90ZVRhc2tJZHNVcmwgPSBkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS11bnJldHJpZXZlZC1yZW1vdGUtdGFzay1pZHMtdXJsJyk7XG4gICAgICAgIHRoaXMucmVzdWx0c1JldHJpZXZlVXJsID0gZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmVzdWx0cy1yZXRyaWV2ZS11cmwnKTtcbiAgICAgICAgdGhpcy5yZXRyaWV2YWxTdGF0c1VybCA9IGRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXByZXBhcmluZy1zdGF0cy11cmwnKTtcbiAgICAgICAgdGhpcy5yZXN1bHRzVXJsID0gZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmVzdWx0cy11cmwnKTtcbiAgICAgICAgdGhpcy5wcm9ncmVzc0JhciA9IG5ldyBQcm9ncmVzc0Jhcihkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcucHJvZ3Jlc3MtYmFyJykpO1xuICAgICAgICB0aGlzLmNvbXBsZXRpb25QZXJjZW50VmFsdWUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuY29tcGxldGlvbi1wZXJjZW50LXZhbHVlJyk7XG4gICAgICAgIHRoaXMubG9jYWxUYXNrQ291bnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubG9jYWwtdGFzay1jb3VudCcpO1xuICAgICAgICB0aGlzLnJlbW90ZVRhc2tDb3VudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5yZW1vdGUtdGFzay1jb3VudCcpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuX2NoZWNrQ29tcGxldGlvblN0YXR1cygpO1xuICAgIH07XG5cbiAgICBfY2hlY2tDb21wbGV0aW9uU3RhdHVzICgpIHtcbiAgICAgICAgaWYgKHBhcnNlSW50KGRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXJlbWFpbmluZy10YXNrcy10by1yZXRyaWV2ZS1jb3VudCcpLCAxMCkgPT09IDApIHtcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gdGhpcy5yZXN1bHRzVXJsO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbigpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IHJlcXVlc3RJZCA9IGV2ZW50LmRldGFpbC5yZXF1ZXN0SWQ7XG4gICAgICAgIGxldCByZXNwb25zZSA9IGV2ZW50LmRldGFpbC5yZXNwb25zZTtcblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbicpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24ocmVzcG9uc2UpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24nKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVJldHJpZXZhbFN0YXRzKCk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVSZXRyaWV2YWxTdGF0cycpIHtcbiAgICAgICAgICAgIGxldCBjb21wbGV0aW9uUGVyY2VudCA9IHJlc3BvbnNlLmNvbXBsZXRpb25fcGVyY2VudDtcblxuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5ib2R5LnNldEF0dHJpYnV0ZSgnZGF0YS1yZW1haW5pbmctdGFza3MtdG8tcmV0cmlldmUtY291bnQnLCByZXNwb25zZS5yZW1haW5pbmdfdGFza3NfdG9fcmV0cmlldmVfY291bnQpO1xuICAgICAgICAgICAgdGhpcy5jb21wbGV0aW9uUGVyY2VudFZhbHVlLmlubmVyVGV4dCA9IGNvbXBsZXRpb25QZXJjZW50O1xuICAgICAgICAgICAgdGhpcy5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudChjb21wbGV0aW9uUGVyY2VudCk7XG4gICAgICAgICAgICB0aGlzLmxvY2FsVGFza0NvdW50LmlubmVyVGV4dCA9IHJlc3BvbnNlLmxvY2FsX3Rhc2tfY291bnQ7XG4gICAgICAgICAgICB0aGlzLnJlbW90ZVRhc2tDb3VudC5pbm5lclRleHQgPSByZXNwb25zZS5yZW1vdGVfdGFza19jb3VudDtcblxuICAgICAgICAgICAgdGhpcy5fY2hlY2tDb21wbGV0aW9uU3RhdHVzKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JldHJpZXZlTmV4dFJlbW90ZVRhc2tJZENvbGxlY3Rpb24gKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldEpzb24odGhpcy51bnJldHJpZXZlZFJlbW90ZVRhc2tJZHNVcmwsIHRoaXMuZG9jdW1lbnQuYm9keSwgJ3JldHJpZXZlTmV4dFJlbW90ZVRhc2tJZENvbGxlY3Rpb24nKTtcbiAgICB9O1xuXG4gICAgX3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24gKHJlbW90ZVRhc2tJZHMpIHtcbiAgICAgICAgSHR0cENsaWVudC5wb3N0KHRoaXMucmVzdWx0c1JldHJpZXZlVXJsLCB0aGlzLmRvY3VtZW50LmJvZHksICdyZXRyaWV2ZVJlbW90ZVRhc2tDb2xsZWN0aW9uJywgJ3JlbW90ZVRhc2tJZHM9JyArIHJlbW90ZVRhc2tJZHMuam9pbignLCcpKTtcbiAgICB9O1xuXG4gICAgX3JldHJpZXZlUmV0cmlldmFsU3RhdHMgKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldEpzb24odGhpcy5yZXRyaWV2YWxTdGF0c1VybCwgdGhpcy5kb2N1bWVudC5ib2R5LCAncmV0cmlldmVSZXRyaWV2YWxTdGF0cycpO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0UmVzdWx0c1ByZXBhcmluZztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcuanMiLCJsZXQgdW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIgPSByZXF1aXJlKCcuLi91bmF2YWlsYWJsZS10YXNrLXR5cGUtbW9kYWwtbGF1bmNoZXInKTtcbmxldCBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1mYWN0b3J5Jyk7XG5sZXQgQ29va2llT3B0aW9uc0ZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5Jyk7XG5sZXQgVGVzdExvY2tVbmxvY2sgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Rlc3QtbG9jay11bmxvY2snKTtcbmxldCBGb3JtQnV0dG9uID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbicpO1xubGV0IEJhZGdlQ29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2JhZGdlLWNvbGxlY3Rpb24nKTtcblxuY2xhc3MgVGVzdFJlc3VsdHMge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkuY3JlYXRlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5odHRwLWF1dGhlbnRpY2F0aW9uLXRlc3Qtb3B0aW9uJykpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMgPSBDb29raWVPcHRpb25zRmFjdG9yeS5jcmVhdGUoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmNvb2tpZXMtdGVzdC1vcHRpb24nKSk7XG4gICAgICAgIHRoaXMudGVzdExvY2tVbmxvY2sgPSBuZXcgVGVzdExvY2tVbmxvY2soZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmJ0bi1sb2NrLXVubG9jaycpKTtcbiAgICAgICAgdGhpcy5yZXRlc3RGb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJldGVzdC1mb3JtJyk7XG4gICAgICAgIHRoaXMucmV0ZXN0QnV0dG9uID0gbmV3IEZvcm1CdXR0b24odGhpcy5yZXRlc3RGb3JtLnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG4gICAgICAgIHRoaXMudGFza1R5cGVTdW1tYXJ5QmFkZ2VDb2xsZWN0aW9uID0gbmV3IEJhZGdlQ29sbGVjdGlvbihkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzay10eXBlLXN1bW1hcnkgLmJhZGdlJykpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlcih0aGlzLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrLXR5cGUtb3B0aW9uLm5vdC1hdmFpbGFibGUnKSk7XG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucy5pbml0KCk7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9ucy5pbml0KCk7XG4gICAgICAgIHRoaXMudGVzdExvY2tVbmxvY2suaW5pdCgpO1xuICAgICAgICB0aGlzLnRhc2tUeXBlU3VtbWFyeUJhZGdlQ29sbGVjdGlvbi5hcHBseVVuaWZvcm1XaWR0aCgpO1xuXG4gICAgICAgIHRoaXMucmV0ZXN0Rm9ybS5hZGRFdmVudExpc3RlbmVyKCdzdWJtaXQnLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnJldGVzdEJ1dHRvbi5kZUVtcGhhc2l6ZSgpO1xuICAgICAgICAgICAgdGhpcy5yZXRlc3RCdXR0b24ubWFya0FzQnVzeSgpO1xuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RSZXN1bHRzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLmpzIiwibGV0IEZvcm0gPSByZXF1aXJlKCcuLi91c2VyLWFjY291bnQtY2FyZC9mb3JtJyk7XG5sZXQgRm9ybVZhbGlkYXRvciA9IHJlcXVpcmUoJy4uL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0tdmFsaWRhdG9yJyk7XG5sZXQgU3RyaXBlSGFuZGxlciA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL3N0cmlwZS1oYW5kbGVyJyk7XG5cbmNsYXNzIFVzZXJBY2NvdW50Q2FyZCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIG5vLXVuZGVmXG4gICAgICAgIGxldCBzdHJpcGVKcyA9IFN0cmlwZTtcbiAgICAgICAgbGV0IGZvcm1WYWxpZGF0b3IgPSBuZXcgRm9ybVZhbGlkYXRvcihzdHJpcGVKcyk7XG4gICAgICAgIHRoaXMuc3RyaXBlSGFuZGxlciA9IG5ldyBTdHJpcGVIYW5kbGVyKHN0cmlwZUpzKTtcblxuICAgICAgICB0aGlzLmZvcm0gPSBuZXcgRm9ybShkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGF5bWVudC1mb3JtJyksIGZvcm1WYWxpZGF0b3IpO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5mb3JtLmluaXQoKTtcbiAgICAgICAgdGhpcy5zdHJpcGVIYW5kbGVyLnNldFN0cmlwZVB1Ymxpc2hhYmxlS2V5KHRoaXMuZm9ybS5nZXRTdHJpcGVQdWJsaXNoYWJsZUtleSgpKTtcblxuICAgICAgICBsZXQgdXBkYXRlQ2FyZCA9IHRoaXMuZm9ybS5nZXRVcGRhdGVDYXJkRXZlbnROYW1lKCk7XG4gICAgICAgIGxldCBjcmVhdGVDYXJkVG9rZW5TdWNjZXNzID0gdGhpcy5zdHJpcGVIYW5kbGVyLmdldENyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudE5hbWUoKTtcbiAgICAgICAgbGV0IGNyZWF0ZUNhcmRUb2tlbkZhaWx1cmUgPSB0aGlzLnN0cmlwZUhhbmRsZXIuZ2V0Q3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TmFtZSgpO1xuXG4gICAgICAgIHRoaXMuZm9ybS5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIodXBkYXRlQ2FyZCwgdGhpcy5fdXBkYXRlQ2FyZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZm9ybS5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoY3JlYXRlQ2FyZFRva2VuU3VjY2VzcywgdGhpcy5fY3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZm9ybS5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoY3JlYXRlQ2FyZFRva2VuRmFpbHVyZSwgdGhpcy5fY3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIF91cGRhdGVDYXJkRXZlbnRMaXN0ZW5lciAodXBkYXRlQ2FyZEV2ZW50KSB7XG4gICAgICAgIHRoaXMuc3RyaXBlSGFuZGxlci5jcmVhdGVDYXJkVG9rZW4odXBkYXRlQ2FyZEV2ZW50LmRldGFpbCwgdGhpcy5mb3JtLmVsZW1lbnQpO1xuICAgIH07XG5cbiAgICBfY3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TGlzdGVuZXIgKHN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50KSB7XG4gICAgICAgIGxldCByZXF1ZXN0VXJsID0gd2luZG93LmxvY2F0aW9uLnBhdGhuYW1lICsgc3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQuZGV0YWlsLmlkICsgJy9hc3NvY2lhdGUvJztcbiAgICAgICAgbGV0IHJlcXVlc3QgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcblxuICAgICAgICByZXF1ZXN0Lm9wZW4oJ1BPU1QnLCByZXF1ZXN0VXJsKTtcbiAgICAgICAgcmVxdWVzdC5yZXNwb25zZVR5cGUgPSAnanNvbic7XG4gICAgICAgIHJlcXVlc3Quc2V0UmVxdWVzdEhlYWRlcignQWNjZXB0JywgJ2FwcGxpY2F0aW9uL2pzb24nKTtcblxuICAgICAgICByZXF1ZXN0LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgIGxldCBkYXRhID0gcmVxdWVzdC5yZXNwb25zZTtcblxuICAgICAgICAgICAgaWYgKGRhdGEuaGFzT3duUHJvcGVydHkoJ3RoaXNfdXJsJykpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZvcm0uc3VibWl0QnV0dG9uLm1hcmtBc0F2YWlsYWJsZSgpO1xuICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5zdWJtaXRCdXR0b24ubWFya1N1Y2NlZWRlZCgpO1xuXG4gICAgICAgICAgICAgICAgd2luZG93LnNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24gPSBkYXRhLnRoaXNfdXJsO1xuICAgICAgICAgICAgICAgIH0sIDUwMCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5lbmFibGUoKTtcblxuICAgICAgICAgICAgICAgIGlmIChkYXRhLmhhc093blByb3BlcnR5KCd1c2VyX2FjY291bnRfY2FyZF9leGNlcHRpb25fcGFyYW0nKSAmJiBkYXRhWyd1c2VyX2FjY291bnRfY2FyZF9leGNlcHRpb25fcGFyYW0nXSAhPT0gJycpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5mb3JtLmhhbmRsZVJlc3BvbnNlRXJyb3Ioe1xuICAgICAgICAgICAgICAgICAgICAgICAgJ3BhcmFtJzogZGF0YS51c2VyX2FjY291bnRfY2FyZF9leGNlcHRpb25fcGFyYW0sXG4gICAgICAgICAgICAgICAgICAgICAgICAnbWVzc2FnZSc6IGRhdGEudXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX21lc3NhZ2VcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5mb3JtLmhhbmRsZVJlc3BvbnNlRXJyb3Ioe1xuICAgICAgICAgICAgICAgICAgICAgICAgJ3BhcmFtJzogJ251bWJlcicsXG4gICAgICAgICAgICAgICAgICAgICAgICAnbWVzc2FnZSc6IGRhdGEudXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX21lc3NhZ2VcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXF1ZXN0LnNlbmQoKTtcbiAgICB9O1xuXG4gICAgX2NyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudExpc3RlbmVyIChzdHJpcGVDcmVhdGVDYXJkVG9rZW5FdmVudCkge1xuICAgICAgICBsZXQgcmVzcG9uc2VFcnJvciA9IHRoaXMuZm9ybS5jcmVhdGVSZXNwb25zZUVycm9yKHN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50LmRldGFpbC5lcnJvci5wYXJhbSwgJ2ludmFsaWQnKTtcblxuICAgICAgICB0aGlzLmZvcm0uZW5hYmxlKCk7XG4gICAgICAgIHRoaXMuZm9ybS5oYW5kbGVSZXNwb25zZUVycm9yKHJlc3BvbnNlRXJyb3IpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVXNlckFjY291bnRDYXJkO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdXNlci1hY2NvdW50LWNhcmQuanMiLCJsZXQgU2Nyb2xsU3B5ID0gcmVxdWlyZSgnLi4vdXNlci1hY2NvdW50L3Njcm9sbHNweScpO1xubGV0IE5hdkJhckxpc3QgPSByZXF1aXJlKCcuLi91c2VyLWFjY291bnQvbmF2YmFyLWxpc3QnKTtcbmNvbnN0IFNjcm9sbFRvID0gcmVxdWlyZSgnLi4vc2Nyb2xsLXRvJyk7XG5jb25zdCBTdGlja3lGaWxsID0gcmVxdWlyZSgnc3RpY2t5ZmlsbGpzJyk7XG5cbmNsYXNzIFVzZXJBY2NvdW50IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1dpbmRvd30gd2luZG93XG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAod2luZG93LCBkb2N1bWVudCkge1xuICAgICAgICB0aGlzLndpbmRvdyA9IHdpbmRvdztcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLnNjcm9sbE9mZnNldCA9IDYwO1xuICAgICAgICBjb25zdCBzY3JvbGxTcHlPZmZzZXQgPSAxMDA7XG4gICAgICAgIHRoaXMuc2lkZU5hdkVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2lkZW5hdicpO1xuXG4gICAgICAgIHRoaXMubmF2QmFyTGlzdCA9IG5ldyBOYXZCYXJMaXN0KHRoaXMuc2lkZU5hdkVsZW1lbnQsIHRoaXMuc2Nyb2xsT2Zmc2V0KTtcbiAgICAgICAgdGhpcy5zY3JvbGxzcHkgPSBuZXcgU2Nyb2xsU3B5KHRoaXMubmF2QmFyTGlzdCwgc2Nyb2xsU3B5T2Zmc2V0KTtcbiAgICB9O1xuXG4gICAgX2FwcGx5SW5pdGlhbFNjcm9sbCAoKSB7XG4gICAgICAgIGxldCB0YXJnZXRJZCA9IHRoaXMud2luZG93LmxvY2F0aW9uLmhhc2gudHJpbSgpLnJlcGxhY2UoJyMnLCAnJyk7XG5cbiAgICAgICAgaWYgKHRhcmdldElkKSB7XG4gICAgICAgICAgICBsZXQgdGFyZ2V0ID0gdGhpcy5kb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRJZCk7XG4gICAgICAgICAgICBsZXQgcmVsYXRlZEFuY2hvciA9IHRoaXMuc2lkZU5hdkVsZW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVxcXFwjJyArIHRhcmdldElkICsgJ10nKTtcblxuICAgICAgICAgICAgaWYgKHRhcmdldCkge1xuICAgICAgICAgICAgICAgIGlmIChyZWxhdGVkQW5jaG9yLmNsYXNzTGlzdC5jb250YWlucygnanMtZmlyc3QnKSkge1xuICAgICAgICAgICAgICAgICAgICBTY3JvbGxUby5nb1RvKHRhcmdldCwgMCk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgU2Nyb2xsVG8uc2Nyb2xsVG8odGFyZ2V0LCB0aGlzLnNjcm9sbE9mZnNldCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcblxuICAgIF9hcHBseVBvc2l0aW9uU3RpY2t5UG9seWZpbGwgKCkge1xuICAgICAgICBjb25zdCBzZWxlY3RvciA9ICcuY3NzcG9zaXRpb25zdGlja3knO1xuICAgICAgICBjb25zdCBzdGlja3lOYXZKc0NsYXNzID0gJ2pzLXN0aWNreS1uYXYnO1xuICAgICAgICBjb25zdCBzdGlja3lOYXZKc1NlbGVjdG9yID0gJy4nICsgc3RpY2t5TmF2SnNDbGFzcztcblxuICAgICAgICBsZXQgc3RpY2t5TmF2ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzdGlja3lOYXZKc1NlbGVjdG9yKTtcblxuICAgICAgICBpZiAoZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzZWxlY3RvcikpIHtcbiAgICAgICAgICAgIHN0aWNreU5hdi5jbGFzc0xpc3QucmVtb3ZlKHN0aWNreU5hdkpzQ2xhc3MpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgU3RpY2t5RmlsbC5hZGRPbmUoc3RpY2t5TmF2KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5zaWRlTmF2RWxlbWVudC5xdWVyeVNlbGVjdG9yKCdhJykuY2xhc3NMaXN0LmFkZCgnanMtZmlyc3QnKTtcbiAgICAgICAgdGhpcy5zY3JvbGxzcHkuc3B5KCk7XG4gICAgICAgIHRoaXMuX2FwcGx5UG9zaXRpb25TdGlja3lQb2x5ZmlsbCgpO1xuICAgICAgICB0aGlzLl9hcHBseUluaXRpYWxTY3JvbGwoKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFVzZXJBY2NvdW50O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdXNlci1hY2NvdW50LmpzIiwiLy8gUG9seWZpbGwgZm9yIGJyb3dzZXJzIG5vdCBzdXBwb3J0aW5nIG5ldyBDdXN0b21FdmVudCgpXG4vLyBMaWdodGx5IG1vZGlmaWVkIGZyb20gcG9seWZpbGwgcHJvdmlkZWQgYXQgaHR0cHM6Ly9kZXZlbG9wZXIubW96aWxsYS5vcmcvZW4tVVMvZG9jcy9XZWIvQVBJL0N1c3RvbUV2ZW50L0N1c3RvbUV2ZW50I1BvbHlmaWxsXG4oZnVuY3Rpb24gKCkge1xuICAgIGlmICh0eXBlb2Ygd2luZG93LkN1c3RvbUV2ZW50ID09PSAnZnVuY3Rpb24nKSByZXR1cm4gZmFsc2U7XG5cbiAgICBmdW5jdGlvbiBDdXN0b21FdmVudCAoZXZlbnQsIHBhcmFtcykge1xuICAgICAgICBwYXJhbXMgPSBwYXJhbXMgfHwgeyBidWJibGVzOiBmYWxzZSwgY2FuY2VsYWJsZTogZmFsc2UsIGRldGFpbDogdW5kZWZpbmVkIH07XG4gICAgICAgIGxldCBjdXN0b21FdmVudCA9IGRvY3VtZW50LmNyZWF0ZUV2ZW50KCdDdXN0b21FdmVudCcpO1xuICAgICAgICBjdXN0b21FdmVudC5pbml0Q3VzdG9tRXZlbnQoZXZlbnQsIHBhcmFtcy5idWJibGVzLCBwYXJhbXMuY2FuY2VsYWJsZSwgcGFyYW1zLmRldGFpbCk7XG5cbiAgICAgICAgcmV0dXJuIGN1c3RvbUV2ZW50O1xuICAgIH1cblxuICAgIEN1c3RvbUV2ZW50LnByb3RvdHlwZSA9IHdpbmRvdy5FdmVudC5wcm90b3R5cGU7XG5cbiAgICB3aW5kb3cuQ3VzdG9tRXZlbnQgPSBDdXN0b21FdmVudDtcbn0pKCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcG9seWZpbGwvY3VzdG9tLWV2ZW50LmpzIiwiLy8gUG9seWZpbGwgZm9yIGJyb3dzZXJzIG5vdCBzdXBwb3J0aW5nIE9iamVjdC5lbnRyaWVzKClcbi8vIExpZ2h0bHkgbW9kaWZpZWQgZnJvbSBwb2x5ZmlsbCBwcm92aWRlZCBhdCBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9KYXZhU2NyaXB0L1JlZmVyZW5jZS9HbG9iYWxfT2JqZWN0cy9PYmplY3QvZW50cmllcyNQb2x5ZmlsbFxuaWYgKCFPYmplY3QuZW50cmllcykge1xuICAgIE9iamVjdC5lbnRyaWVzID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgICBsZXQgb3duUHJvcHMgPSBPYmplY3Qua2V5cyhvYmopO1xuICAgICAgICBsZXQgaSA9IG93blByb3BzLmxlbmd0aDtcbiAgICAgICAgbGV0IHJlc0FycmF5ID0gbmV3IEFycmF5KGkpO1xuXG4gICAgICAgIHdoaWxlIChpLS0pIHtcbiAgICAgICAgICAgIHJlc0FycmF5W2ldID0gW293blByb3BzW2ldLCBvYmpbb3duUHJvcHNbaV1dXTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiByZXNBcnJheTtcbiAgICB9O1xufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BvbHlmaWxsL29iamVjdC1lbnRyaWVzLmpzIiwiY29uc3QgU21vb3RoU2Nyb2xsID0gcmVxdWlyZSgnc21vb3RoLXNjcm9sbCcpO1xuXG5jbGFzcyBTY3JvbGxUbyB7XG4gICAgc3RhdGljIHNjcm9sbFRvICh0YXJnZXQsIG9mZnNldCkge1xuICAgICAgICBjb25zdCBzY3JvbGwgPSBuZXcgU21vb3RoU2Nyb2xsKCk7XG5cbiAgICAgICAgc2Nyb2xsLmFuaW1hdGVTY3JvbGwodGFyZ2V0Lm9mZnNldFRvcCArIG9mZnNldCk7XG4gICAgICAgIFNjcm9sbFRvLl91cGRhdGVIaXN0b3J5KHRhcmdldCk7XG4gICAgfVxuXG4gICAgc3RhdGljIGdvVG8gKHRhcmdldCwgb2Zmc2V0KSB7XG4gICAgICAgIGNvbnN0IHNjcm9sbCA9IG5ldyBTbW9vdGhTY3JvbGwoKTtcblxuICAgICAgICBzY3JvbGwuYW5pbWF0ZVNjcm9sbChvZmZzZXQpO1xuICAgICAgICBTY3JvbGxUby5fdXBkYXRlSGlzdG9yeSh0YXJnZXQpO1xuICAgIH1cblxuICAgIHN0YXRpYyBfdXBkYXRlSGlzdG9yeSAodGFyZ2V0KSB7XG4gICAgICAgIGlmICh3aW5kb3cuaGlzdG9yeS5wdXNoU3RhdGUpIHtcbiAgICAgICAgICAgIHdpbmRvdy5oaXN0b3J5LnB1c2hTdGF0ZShudWxsLCBudWxsLCAnIycgKyB0YXJnZXQuZ2V0QXR0cmlidXRlKCdpZCcpKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU2Nyb2xsVG87XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2Nyb2xsLXRvLmpzIiwibGV0IEFsZXJ0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9hbGVydCcpO1xuXG5jbGFzcyBBbGVydEZhY3Rvcnkge1xuICAgIHN0YXRpYyBjcmVhdGVGcm9tQ29udGVudCAoZG9jdW1lbnQsIGVycm9yQ29udGVudCwgcmVsYXRlZEZpZWxkSWQpIHtcbiAgICAgICAgbGV0IGVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdhbGVydCcsICdhbGVydC1kYW5nZXInLCAnZmFkZScsICdpbicpO1xuICAgICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZSgncm9sZScsICdhbGVydCcpO1xuXG4gICAgICAgIGxldCBlbGVtZW50SW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgaWYgKHJlbGF0ZWRGaWVsZElkKSB7XG4gICAgICAgICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZSgnZGF0YS1mb3InLCByZWxhdGVkRmllbGRJZCk7XG4gICAgICAgICAgICBlbGVtZW50SW5uZXJIVE1MICs9ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImNsb3NlXCIgZGF0YS1kaXNtaXNzPVwiYWxlcnRcIiBhcmlhLWxhYmVsPVwiQ2xvc2VcIj48c3BhbiBhcmlhLWhpZGRlbj1cInRydWVcIj7Dlzwvc3Bhbj48L2J1dHRvbj4nO1xuICAgICAgICB9XG5cbiAgICAgICAgZWxlbWVudElubmVySFRNTCArPSBlcnJvckNvbnRlbnQ7XG4gICAgICAgIGVsZW1lbnQuaW5uZXJIVE1MID0gZWxlbWVudElubmVySFRNTDtcblxuICAgICAgICByZXR1cm4gbmV3IEFsZXJ0KGVsZW1lbnQpO1xuICAgIH07XG5cbiAgICBzdGF0aWMgY3JlYXRlRnJvbUVsZW1lbnQgKGFsZXJ0RWxlbWVudCkge1xuICAgICAgICByZXR1cm4gbmV3IEFsZXJ0KGFsZXJ0RWxlbWVudCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEFsZXJ0RmFjdG9yeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5LmpzIiwibGV0IENvb2tpZU9wdGlvbnNNb2RhbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvY29va2llLW9wdGlvbnMtbW9kYWwnKTtcbmxldCBDb29raWVPcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvY29va2llLW9wdGlvbnMnKTtcbmxldCBBY3Rpb25CYWRnZSA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvYWN0aW9uLWJhZGdlJyk7XG5cbmNsYXNzIENvb2tpZU9wdGlvbnNGYWN0b3J5IHtcbiAgICBzdGF0aWMgY3JlYXRlIChjb250YWluZXIpIHtcbiAgICAgICAgcmV0dXJuIG5ldyBDb29raWVPcHRpb25zKFxuICAgICAgICAgICAgY29udGFpbmVyLm93bmVyRG9jdW1lbnQsXG4gICAgICAgICAgICBuZXcgQ29va2llT3B0aW9uc01vZGFsKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubW9kYWwnKSksXG4gICAgICAgICAgICBuZXcgQWN0aW9uQmFkZ2UoY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5tb2RhbC1sYXVuY2hlcicpKSxcbiAgICAgICAgICAgIGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcuc3RhdHVzJylcbiAgICAgICAgKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IENvb2tpZU9wdGlvbnNGYWN0b3J5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnkuanMiLCJsZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwnKTtcbmxldCBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zJyk7XG5sZXQgQWN0aW9uQmFkZ2UgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZScpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeSB7XG4gICAgc3RhdGljIGNyZWF0ZSAoY29udGFpbmVyKSB7XG4gICAgICAgIHJldHVybiBuZXcgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyhcbiAgICAgICAgICAgIGNvbnRhaW5lci5vd25lckRvY3VtZW50LFxuICAgICAgICAgICAgbmV3IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbChjb250YWluZXIucXVlcnlTZWxlY3RvcignLm1vZGFsJykpLFxuICAgICAgICAgICAgbmV3IEFjdGlvbkJhZGdlKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubW9kYWwtbGF1bmNoZXInKSksXG4gICAgICAgICAgICBjb250YWluZXIucXVlcnlTZWxlY3RvcignLnN0YXR1cycpXG4gICAgICAgICk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeS5qcyIsImNsYXNzIEh0dHBDbGllbnQge1xuICAgIHN0YXRpYyBnZXRSZXRyaWV2ZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2h0dHAtY2xpZW50LnJldHJpZXZlZCc7XG4gICAgfTtcblxuICAgIHN0YXRpYyByZXF1ZXN0ICh1cmwsIG1ldGhvZCwgcmVzcG9uc2VUeXBlLCBlbGVtZW50LCByZXF1ZXN0SWQsIGRhdGEgPSBudWxsLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIGxldCByZXF1ZXN0ID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG5cbiAgICAgICAgcmVxdWVzdC5vcGVuKG1ldGhvZCwgdXJsKTtcbiAgICAgICAgcmVxdWVzdC5yZXNwb25zZVR5cGUgPSByZXNwb25zZVR5cGU7XG5cbiAgICAgICAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMocmVxdWVzdEhlYWRlcnMpKSB7XG4gICAgICAgICAgICByZXF1ZXN0LnNldFJlcXVlc3RIZWFkZXIoa2V5LCB2YWx1ZSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXF1ZXN0LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgIGxldCByZXRyaWV2ZWRFdmVudCA9IG5ldyBDdXN0b21FdmVudChIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiB7XG4gICAgICAgICAgICAgICAgICAgIHJlc3BvbnNlOiByZXF1ZXN0LnJlc3BvbnNlLFxuICAgICAgICAgICAgICAgICAgICByZXF1ZXN0SWQ6IHJlcXVlc3RJZCxcbiAgICAgICAgICAgICAgICAgICAgcmVxdWVzdDogcmVxdWVzdFxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBlbGVtZW50LmRpc3BhdGNoRXZlbnQocmV0cmlldmVkRXZlbnQpO1xuICAgICAgICB9KTtcblxuICAgICAgICBpZiAoZGF0YSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmVxdWVzdC5zZW5kKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICByZXF1ZXN0LnNlbmQoZGF0YSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgc3RhdGljIGdldCAodXJsLCByZXNwb25zZVR5cGUsIGVsZW1lbnQsIHJlcXVlc3RJZCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBIdHRwQ2xpZW50LnJlcXVlc3QodXJsLCAnR0VUJywgcmVzcG9uc2VUeXBlLCBlbGVtZW50LCByZXF1ZXN0SWQsIG51bGwsIHJlcXVlc3RIZWFkZXJzKTtcbiAgICB9O1xuXG4gICAgc3RhdGljIGdldEpzb24gKHVybCwgZWxlbWVudCwgcmVxdWVzdElkLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIGxldCByZWFsUmVxdWVzdEhlYWRlcnMgPSB7XG4gICAgICAgICAgICAnQWNjZXB0JzogJ2FwcGxpY2F0aW9uL2pzb24nXG4gICAgICAgIH07XG5cbiAgICAgICAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMocmVxdWVzdEhlYWRlcnMpKSB7XG4gICAgICAgICAgICByZWFsUmVxdWVzdEhlYWRlcnNba2V5XSA9IHZhbHVlO1xuICAgICAgICB9XG5cbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ0dFVCcsICdqc29uJywgZWxlbWVudCwgcmVxdWVzdElkLCBudWxsLCByZWFsUmVxdWVzdEhlYWRlcnMpO1xuICAgIH07XG5cbiAgICBzdGF0aWMgZ2V0VGV4dCAodXJsLCBlbGVtZW50LCByZXF1ZXN0SWQsIHJlcXVlc3RIZWFkZXJzID0ge30pIHtcbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ0dFVCcsICcnLCBlbGVtZW50LCByZXF1ZXN0SWQsIHJlcXVlc3RIZWFkZXJzKTtcbiAgICB9O1xuXG4gICAgc3RhdGljIHBvc3QgKHVybCwgZWxlbWVudCwgcmVxdWVzdElkLCBkYXRhID0gbnVsbCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBsZXQgcmVhbFJlcXVlc3RIZWFkZXJzID0ge1xuICAgICAgICAgICAgJ0NvbnRlbnQtdHlwZSc6ICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnXG4gICAgICAgIH07XG5cbiAgICAgICAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMocmVxdWVzdEhlYWRlcnMpKSB7XG4gICAgICAgICAgICByZWFsUmVxdWVzdEhlYWRlcnNba2V5XSA9IHZhbHVlO1xuICAgICAgICB9XG5cbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ1BPU1QnLCAnJywgZWxlbWVudCwgcmVxdWVzdElkLCBkYXRhLCByZWFsUmVxdWVzdEhlYWRlcnMpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gSHR0cENsaWVudDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWNsaWVudC5qcyIsImxldCBMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9saXN0ZWQtdGVzdCcpO1xubGV0IFByZXBhcmluZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L3ByZXBhcmluZy1saXN0ZWQtdGVzdCcpO1xubGV0IFByb2dyZXNzaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QnKTtcbmxldCBDcmF3bGluZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2NyYXdsaW5nLWxpc3RlZC10ZXN0Jyk7XG5cbmNsYXNzIExpc3RlZFRlc3RGYWN0b3J5IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtMaXN0ZWRUZXN0fVxuICAgICAqL1xuICAgIHN0YXRpYyBjcmVhdGVGcm9tRWxlbWVudCAoZWxlbWVudCkge1xuICAgICAgICBpZiAoZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ3JlcXVpcmVzLXJlc3VsdHMnKSkge1xuICAgICAgICAgICAgcmV0dXJuIG5ldyBQcmVwYXJpbmdMaXN0ZWRUZXN0KGVsZW1lbnQpO1xuICAgICAgICB9XG5cbiAgICAgICAgbGV0IHN0YXRlID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcblxuICAgICAgICBpZiAoc3RhdGUgPT09ICdpbi1wcm9ncmVzcycpIHtcbiAgICAgICAgICAgIHJldHVybiBuZXcgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0KGVsZW1lbnQpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnY3Jhd2xpbmcnKSB7XG4gICAgICAgICAgICByZXR1cm4gbmV3IENyYXdsaW5nTGlzdGVkVGVzdChlbGVtZW50KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBuZXcgTGlzdGVkVGVzdChlbGVtZW50KTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTGlzdGVkVGVzdEZhY3Rvcnk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvbGlzdGVkLXRlc3QtZmFjdG9yeS5qcyIsImNsYXNzIFN0cmlwZUhhbmRsZXIge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7U3RyaXBlfSBzdHJpcGVKc1xuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChzdHJpcGVKcykge1xuICAgICAgICB0aGlzLnN0cmlwZUpzID0gc3RyaXBlSnM7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IHN0cmlwZVB1Ymxpc2hhYmxlS2V5XG4gICAgICovXG4gICAgc2V0U3RyaXBlUHVibGlzaGFibGVLZXkgKHN0cmlwZVB1Ymxpc2hhYmxlS2V5KSB7XG4gICAgICAgIHRoaXMuc3RyaXBlSnMuc2V0UHVibGlzaGFibGVLZXkoc3RyaXBlUHVibGlzaGFibGVLZXkpO1xuICAgIH1cblxuICAgIGdldENyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3N0cmlwZS1oYW5kZXIuY3JlYXRlLWNhcmQtdG9rZW4uc3VjY2Vzcyc7XG4gICAgfTtcblxuICAgIGdldENyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3N0cmlwZS1oYW5kZXIuY3JlYXRlLWNhcmQtdG9rZW4uZmFpbHVyZSc7XG4gICAgfTtcblxuICAgIGNyZWF0ZUNhcmRUb2tlbiAoZGF0YSwgZm9ybUVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcy5jYXJkLmNyZWF0ZVRva2VuKGRhdGEsIChzdGF0dXMsIHJlc3BvbnNlKSA9PiB7XG4gICAgICAgICAgICBsZXQgaXNFcnJvclJlc3BvbnNlID0gcmVzcG9uc2UuaGFzT3duUHJvcGVydHkoJ2Vycm9yJyk7XG5cbiAgICAgICAgICAgIGxldCBldmVudE5hbWUgPSBpc0Vycm9yUmVzcG9uc2VcbiAgICAgICAgICAgICAgICA/IHRoaXMuZ2V0Q3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TmFtZSgpXG4gICAgICAgICAgICAgICAgOiB0aGlzLmdldENyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudE5hbWUoKTtcblxuICAgICAgICAgICAgZm9ybUVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoZXZlbnROYW1lLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiByZXNwb25zZVxuICAgICAgICAgICAgfSkpO1xuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFN0cmlwZUhhbmRsZXI7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvc3RyaXBlLWhhbmRsZXIuanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4vaHR0cC1jbGllbnQnKTtcbmxldCBQcm9ncmVzc0JhciA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyJyk7XG5cbmNsYXNzIFRlc3RSZXN1bHRSZXRyaWV2ZXIge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc3RhdHVzVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdHVzLXVybCcpO1xuICAgICAgICB0aGlzLnVucmV0cmlldmVkVGFza0lkc1VybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXVucmV0cmlldmVkLXRhc2staWRzLXVybCcpO1xuICAgICAgICB0aGlzLnJldHJpZXZlVGFza3NVcmwgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1yZXRyaWV2ZS10YXNrcy11cmwnKTtcbiAgICAgICAgdGhpcy5zdW1tYXJ5VXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3VtbWFyeS11cmwnKTtcbiAgICAgICAgdGhpcy5wcm9ncmVzc0JhciA9IG5ldyBQcm9ncmVzc0Jhcih0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByb2dyZXNzLWJhcicpKTtcbiAgICAgICAgdGhpcy5zdW1tYXJ5ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuc3VtbWFyeScpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG5cbiAgICAgICAgdGhpcy5fcmV0cmlldmVQcmVwYXJpbmdTdGF0dXMoKTtcbiAgICB9O1xuXG4gICAgZ2V0UmV0cmlldmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0ZXN0LXJlc3VsdC1yZXRyaWV2ZXIucmV0cmlldmVkJztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCByZXNwb25zZSA9IGV2ZW50LmRldGFpbC5yZXNwb25zZTtcbiAgICAgICAgbGV0IHJlcXVlc3RJZCA9IGV2ZW50LmRldGFpbC5yZXF1ZXN0SWQ7XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzJykge1xuICAgICAgICAgICAgbGV0IGNvbXBsZXRpb25QZXJjZW50ID0gcmVzcG9uc2UuY29tcGxldGlvbl9wZXJjZW50O1xuXG4gICAgICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldENvbXBsZXRpb25QZXJjZW50KGNvbXBsZXRpb25QZXJjZW50KTtcblxuICAgICAgICAgICAgaWYgKGNvbXBsZXRpb25QZXJjZW50ID49IDEwMCkge1xuICAgICAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5KCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuX2Rpc3BsYXlQcmVwYXJpbmdTdW1tYXJ5KHJlc3BvbnNlKTtcbiAgICAgICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZU5leHRSZW1vdmVUYXNrSWRDb2xsZWN0aW9uKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbicpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlTmV4dFJlbW90ZVRhc2tDb2xsZWN0aW9uKHJlc3BvbnNlKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZU5leHRSZW1vdGVUYXNrQ29sbGVjdGlvbicpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzKCk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVGaW5pc2hlZFN1bW1hcnknKSB7XG4gICAgICAgICAgICBsZXQgcmV0cmlldmVkU3VtbWFyeUNvbnRhaW5lciA9IHRoaXMuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICByZXRyaWV2ZWRTdW1tYXJ5Q29udGFpbmVyLmlubmVySFRNTCA9IHJlc3BvbnNlO1xuXG4gICAgICAgICAgICBsZXQgcmV0cmlldmVkRXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQodGhpcy5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDogcmV0cmlldmVkU3VtbWFyeUNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubGlzdGVkLXRlc3QnKVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KHJldHJpZXZlZEV2ZW50KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfcmV0cmlldmVQcmVwYXJpbmdTdGF0dXMgKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldEpzb24odGhpcy5zdGF0dXNVcmwsIHRoaXMuZWxlbWVudCwgJ3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzJyk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZU5leHRSZW1vdmVUYXNrSWRDb2xsZWN0aW9uICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHRoaXMudW5yZXRyaWV2ZWRUYXNrSWRzVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZU5leHRSZW1vdmVUYXNrSWRDb2xsZWN0aW9uJyk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrQ29sbGVjdGlvbiAocmVtb3RlVGFza0lkcykge1xuICAgICAgICBIdHRwQ2xpZW50LnBvc3QodGhpcy5yZXRyaWV2ZVRhc2tzVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZU5leHRSZW1vdGVUYXNrQ29sbGVjdGlvbicsICdyZW1vdGVUYXNrSWRzPScgKyByZW1vdGVUYXNrSWRzLmpvaW4oJywnKSk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZUZpbmlzaGVkU3VtbWFyeSAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0VGV4dCh0aGlzLnN1bW1hcnlVcmwsIHRoaXMuZWxlbWVudCwgJ3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5Jyk7XG4gICAgfTtcblxuICAgIF9jcmVhdGVQcmVwYXJpbmdTdW1tYXJ5IChzdGF0dXNEYXRhKSB7XG4gICAgICAgIGxldCBsb2NhbFRhc2tDb3VudCA9IHN0YXR1c0RhdGEubG9jYWxfdGFza19jb3VudDtcbiAgICAgICAgbGV0IHJlbW90ZVRhc2tDb3VudCA9IHN0YXR1c0RhdGEucmVtb3RlX3Rhc2tfY291bnQ7XG5cbiAgICAgICAgaWYgKGxvY2FsVGFza0NvdW50ID09PSB1bmRlZmluZWQgJiYgcmVtb3RlVGFza0NvdW50ID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIHJldHVybiAnUHJlcGFyaW5nIHJlc3VsdHMgJmhlbGxpcDsnO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuICdQcmVwYXJpbmcgJmhlbGxpcDsgY29sbGVjdGVkIDxzdHJvbmcgY2xhc3M9XCJsb2NhbC10YXNrLWNvdW50XCI+JyArIGxvY2FsVGFza0NvdW50ICsgJzwvc3Ryb25nPiByZXN1bHRzIG9mIDxzdHJvbmcgY2xhc3M9XCJyZW1vdGUtdGFzay1jb3VudFwiPicgKyByZW1vdGVUYXNrQ291bnQgKyAnPC9zdHJvbmc+JztcbiAgICB9O1xuXG4gICAgX2Rpc3BsYXlQcmVwYXJpbmdTdW1tYXJ5IChzdGF0dXNEYXRhKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJlcGFyaW5nIC5zdW1tYXJ5JykuaW5uZXJIVE1MID0gdGhpcy5fY3JlYXRlUHJlcGFyaW5nU3VtbWFyeShzdGF0dXNEYXRhKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RSZXN1bHRSZXRyaWV2ZXI7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvdGVzdC1yZXN1bHQtcmV0cmlldmVyLmpzIiwiLyogZ2xvYmFsIEF3ZXNvbXBsZXRlICovXG5cbmxldCBmb3JtRmllbGRGb2N1c2VyID0gcmVxdWlyZSgnLi4vZm9ybS1maWVsZC1mb2N1c2VyJyk7XG5yZXF1aXJlKCdhd2Vzb21wbGV0ZScpO1xuXG5jbGFzcyBNb2RhbCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtIVE1MRWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJyNhcHBseS1maWx0ZXItYnV0dG9uJyk7XG4gICAgICAgIHRoaXMuY2xlYXJCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJyNjbGVhci1maWx0ZXItYnV0dG9uJyk7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jbG9zZScpO1xuICAgICAgICB0aGlzLmlucHV0ID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPWZpbHRlcl0nKTtcbiAgICAgICAgdGhpcy5maWx0ZXIgPSB0aGlzLmlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c0ZpbHRlciA9IHRoaXMuaW5wdXQudmFsdWUudHJpbSgpO1xuICAgICAgICB0aGlzLmFwcGx5RmlsdGVyID0gZmFsc2U7XG4gICAgICAgIHRoaXMuYXdlc29tZXBsZXRlID0gbmV3IEF3ZXNvbXBsZXRlKHRoaXMuaW5wdXQpO1xuICAgICAgICB0aGlzLnN1Z2dlc3Rpb25zID0gW107XG4gICAgICAgIHRoaXMuZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSA9ICd0ZXN0LWhpc3RvcnkubW9kYWwuZmlsdGVyLmNoYW5nZWQnO1xuXG4gICAgICAgIHRoaXMuaW5pdCgpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7U3RyaW5nW119IHN1Z2dlc3Rpb25zXG4gICAgICovXG4gICAgc2V0U3VnZ2VzdGlvbnMgKHN1Z2dlc3Rpb25zKSB7XG4gICAgICAgIHRoaXMuc3VnZ2VzdGlvbnMgPSBzdWdnZXN0aW9ucztcbiAgICAgICAgdGhpcy5hd2Vzb21lcGxldGUubGlzdCA9IHRoaXMuc3VnZ2VzdGlvbnM7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGxldCBzaG93bkV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHRoaXMuaW5wdXQpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRlRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGNvbnN0IFdJTERDQVJEID0gJyonO1xuICAgICAgICAgICAgY29uc3QgZmlsdGVySXNFbXB0eSA9IHRoaXMuZmlsdGVyID09PSAnJztcbiAgICAgICAgICAgIGNvbnN0IHN1Z2dlc3Rpb25zSW5jbHVkZXNGaWx0ZXIgPSB0aGlzLnN1Z2dlc3Rpb25zLmluY2x1ZGVzKHRoaXMuZmlsdGVyKTtcbiAgICAgICAgICAgIGNvbnN0IGZpbHRlcklzV2lsZGNhcmRQcmVmaXhlZCA9IHRoaXMuZmlsdGVyLmNoYXJBdCgwKSA9PT0gV0lMRENBUkQ7XG4gICAgICAgICAgICBjb25zdCBmaWx0ZXJJc1dpbGRjYXJkU3VmZml4ZWQgPSB0aGlzLmZpbHRlci5zbGljZSgtMSkgPT09IFdJTERDQVJEO1xuXG4gICAgICAgICAgICBpZiAoIWZpbHRlcklzRW1wdHkgJiYgIXN1Z2dlc3Rpb25zSW5jbHVkZXNGaWx0ZXIpIHtcbiAgICAgICAgICAgICAgICBpZiAoIWZpbHRlcklzV2lsZGNhcmRQcmVmaXhlZCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZpbHRlciA9IFdJTERDQVJEICsgdGhpcy5maWx0ZXI7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgaWYgKCFmaWx0ZXJJc1dpbGRjYXJkU3VmZml4ZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5maWx0ZXIgKz0gV0lMRENBUkQ7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgdGhpcy5pbnB1dC52YWx1ZSA9IHRoaXMuZmlsdGVyO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmFwcGx5RmlsdGVyID0gdGhpcy5maWx0ZXIgIT09IHRoaXMucHJldmlvdXNGaWx0ZXI7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGhpZGRlbkV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoIXRoaXMuYXBwbHlGaWx0ZXIpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudCh0aGlzLmZpbHRlckNoYW5nZWRFdmVudE5hbWUsIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHRoaXMuZmlsdGVyXG4gICAgICAgICAgICB9KSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGFwcGx5QnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5maWx0ZXIgPSB0aGlzLmlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgICAgICB0aGlzLmZpbHRlciA9ICcnO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuYXBwbHlGaWx0ZXIgPSBmYWxzZTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc2hvd24uYnMubW9kYWwnLCBzaG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRlLmJzLm1vZGFsJywgaGlkZUV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdoaWRkZW4uYnMubW9kYWwnLCBoaWRkZW5FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmFwcGx5QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgYXBwbHlCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuY2xlYXJCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBjbGVhckJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTW9kYWw7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L21vZGFsLmpzIiwiY2xhc3MgU3VnZ2VzdGlvbnMge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICogQHBhcmFtIHtTdHJpbmd9IHNvdXJjZVVybFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCwgc291cmNlVXJsKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zb3VyY2VVcmwgPSBzb3VyY2VVcmw7XG4gICAgICAgIHRoaXMubG9hZGVkRXZlbnROYW1lID0gJ3Rlc3QtaGlzdG9yeS5zdWdnZXN0aW9ucy5sb2FkZWQnO1xuICAgIH1cblxuICAgIHJldHJpZXZlICgpIHtcbiAgICAgICAgbGV0IHJlcXVlc3QgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcbiAgICAgICAgbGV0IHN1Z2dlc3Rpb25zID0gbnVsbDtcblxuICAgICAgICByZXF1ZXN0Lm9wZW4oJ0dFVCcsIHRoaXMuc291cmNlVXJsLCBmYWxzZSk7XG5cbiAgICAgICAgbGV0IHJlcXVlc3RPbmxvYWRIYW5kbGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKHJlcXVlc3Quc3RhdHVzID49IDIwMCAmJiByZXF1ZXN0LnN0YXR1cyA8IDQwMCkge1xuICAgICAgICAgICAgICAgIHN1Z2dlc3Rpb25zID0gSlNPTi5wYXJzZShyZXF1ZXN0LnJlc3BvbnNlVGV4dCk7XG5cbiAgICAgICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KHRoaXMubG9hZGVkRXZlbnROYW1lLCB7XG4gICAgICAgICAgICAgICAgICAgIGRldGFpbDogc3VnZ2VzdGlvbnNcbiAgICAgICAgICAgICAgICB9KSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgcmVxdWVzdC5vbmxvYWQgPSByZXF1ZXN0T25sb2FkSGFuZGxlci5iaW5kKHRoaXMpO1xuXG4gICAgICAgIHJlcXVlc3Quc2VuZCgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU3VnZ2VzdGlvbnM7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L3N1Z2dlc3Rpb25zLmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5sZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhcicpO1xubGV0IFRhc2tRdWV1ZXMgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWVzJyk7XG5cbmNsYXNzIFN1bW1hcnkge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuY2FuY2VsQWN0aW9uID0gbmV3IEZvcm1CdXR0b24oZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY2FuY2VsLWFjdGlvbicpKTtcbiAgICAgICAgdGhpcy5jYW5jZWxDcmF3bEFjdGlvbiA9IG5ldyBGb3JtQnV0dG9uKGVsZW1lbnQucXVlcnlTZWxlY3RvcignLmNhbmNlbC1jcmF3bC1hY3Rpb24nKSk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBuZXcgUHJvZ3Jlc3NCYXIoZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJvZ3Jlc3MtYmFyJykpO1xuICAgICAgICB0aGlzLnN0YXRlTGFiZWwgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1zdGF0ZS1sYWJlbCcpO1xuICAgICAgICB0aGlzLnRhc2tRdWV1ZXMgPSBuZXcgVGFza1F1ZXVlcyhlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy50YXNrLXF1ZXVlcycpKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5fYWRkRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0UmVuZGVyQW1tZW5kbWVudEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnJlbmRlci1hbW1lbmRtZW50JztcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgdGhpcy5jYW5jZWxBY3Rpb24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NhbmNlbEFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5jYW5jZWxDcmF3bEFjdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2FuY2VsQ3Jhd2xBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIF9jYW5jZWxBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICB0aGlzLmNhbmNlbEFjdGlvbi5tYXJrQXNCdXN5KCk7XG4gICAgICAgIHRoaXMuY2FuY2VsQWN0aW9uLmRlRW1waGFzaXplKCk7XG4gICAgfTtcblxuICAgIF9jYW5jZWxDcmF3bEFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIHRoaXMuY2FuY2VsQ3Jhd2xBY3Rpb24ubWFya0FzQnVzeSgpO1xuICAgICAgICB0aGlzLmNhbmNlbENyYXdsQWN0aW9uLmRlRW1waGFzaXplKCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7b2JqZWN0fSBzdW1tYXJ5RGF0YVxuICAgICAqL1xuICAgIHJlbmRlciAoc3VtbWFyeURhdGEpIHtcbiAgICAgICAgbGV0IHJlbW90ZVRlc3QgPSBzdW1tYXJ5RGF0YS5yZW1vdGVfdGVzdDtcblxuICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldENvbXBsZXRpb25QZXJjZW50KHJlbW90ZVRlc3QuY29tcGxldGlvbl9wZXJjZW50KTtcbiAgICAgICAgdGhpcy5wcm9ncmVzc0Jhci5zZXRTdHlsZShzdW1tYXJ5RGF0YS50ZXN0LnN0YXRlID09PSAnY3Jhd2xpbmcnID8gJ3dhcm5pbmcnIDogJ2RlZmF1bHQnKTtcbiAgICAgICAgdGhpcy5zdGF0ZUxhYmVsLmlubmVyVGV4dCA9IHN1bW1hcnlEYXRhLnN0YXRlX2xhYmVsO1xuICAgICAgICB0aGlzLnRhc2tRdWV1ZXMucmVuZGVyKHJlbW90ZVRlc3QudGFza19jb3VudCwgcmVtb3RlVGVzdC50YXNrX2NvdW50X2J5X3N0YXRlKTtcblxuICAgICAgICBpZiAocmVtb3RlVGVzdC5hbW1lbmRtZW50cyAmJiByZW1vdGVUZXN0LmFtbWVuZG1lbnRzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChTdW1tYXJ5LmdldFJlbmRlckFtbWVuZG1lbnRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDogcmVtb3RlVGVzdC5hbW1lbmRtZW50c1swXVxuICAgICAgICAgICAgfSkpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTdW1tYXJ5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3Mvc3VtbWFyeS5qcyIsImNsYXNzIFRhc2tJZExpc3Qge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyW119IHRhc2tJZHNcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUxlbmd0aFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yICh0YXNrSWRzLCBwYWdlTGVuZ3RoKSB7XG4gICAgICAgIHRoaXMudGFza0lkcyA9IHRhc2tJZHM7XG4gICAgICAgIHRoaXMucGFnZUxlbmd0aCA9IHBhZ2VMZW5ndGg7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqXG4gICAgICogQHJldHVybnMge251bWJlcltdfVxuICAgICAqL1xuICAgIGdldEZvclBhZ2UgKHBhZ2VJbmRleCkge1xuICAgICAgICBsZXQgcGFnZU51bWJlciA9IHBhZ2VJbmRleCArIDE7XG5cbiAgICAgICAgcmV0dXJuIHRoaXMudGFza0lkcy5zbGljZShwYWdlSW5kZXggKiB0aGlzLnBhZ2VMZW5ndGgsIHBhZ2VOdW1iZXIgKiB0aGlzLnBhZ2VMZW5ndGgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza0lkTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2staWQtbGlzdC5qcyIsImNsYXNzIFRhc2tMaXN0UGFnaW5hdGlvbiB7XG4gICAgY29uc3RydWN0b3IgKHBhZ2VMZW5ndGgsIHRhc2tDb3VudCkge1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSBwYWdlTGVuZ3RoO1xuICAgICAgICB0aGlzLnRhc2tDb3VudCA9IHRhc2tDb3VudDtcbiAgICAgICAgdGhpcy5wYWdlQ291bnQgPSBNYXRoLmNlaWwodGFza0NvdW50IC8gcGFnZUxlbmd0aCk7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IG51bGw7XG4gICAgfVxuXG4gICAgc3RhdGljIGdldFNlbGVjdG9yICgpIHtcbiAgICAgICAgcmV0dXJuICcucGFnaW5hdGlvbic7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldFNlbGVjdFBhZ2VFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rhc2stbGlzdC1wYWdpbmF0aW9uLnNlbGVjdC1wYWdlJztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0U2VsZWN0UHJldmlvdXNQYWdlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0YXNrLWxpc3QtcGFnaW5hdGlvbi5zZWxlY3QtcHJldmlvdXMtcGFnZSc7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0U2VsZWN0TmV4dFBhZ2VFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rhc2stbGlzdC1wYWdpbmF0aW9uLnNlbGVjdC1uZXh0LXBhZ2UnO1xuICAgIH1cblxuICAgIGluaXQgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5wYWdlQWN0aW9ucyA9IGVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnYScpO1xuICAgICAgICB0aGlzLnByZXZpb3VzQWN0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1yb2xlPXByZXZpb3VzXScpO1xuICAgICAgICB0aGlzLm5leHRBY3Rpb24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXJvbGU9bmV4dF0nKTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5wYWdlQWN0aW9ucywgKHBhZ2VBY3Rpb25zKSA9PiB7XG4gICAgICAgICAgICBwYWdlQWN0aW9ucy5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgICAgICAgICBsZXQgYWN0aW9uQ29udGFpbmVyID0gcGFnZUFjdGlvbnMucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICBpZiAoIWFjdGlvbkNvbnRhaW5lci5jbGFzc0xpc3QuY29udGFpbnMoJ2FjdGl2ZScpKSB7XG4gICAgICAgICAgICAgICAgICAgIGxldCByb2xlID0gcGFnZUFjdGlvbnMuZ2V0QXR0cmlidXRlKCdkYXRhLXJvbGUnKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiAocm9sZSA9PT0gJ3Nob3dQYWdlJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFRhc2tMaXN0UGFnaW5hdGlvbi5nZXRTZWxlY3RQYWdlRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkZXRhaWw6IHBhcnNlSW50KHBhZ2VBY3Rpb25zLmdldEF0dHJpYnV0ZSgnZGF0YS1wYWdlLWluZGV4JyksIDEwKVxuICAgICAgICAgICAgICAgICAgICAgICAgfSkpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHJvbGUgPT09ICdwcmV2aW91cycpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0UHJldmlvdXNQYWdlRXZlbnROYW1lKCkpKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGlmIChyb2xlID09PSAnbmV4dCcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0TmV4dFBhZ2VFdmVudE5hbWUoKSkpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBjcmVhdGVNYXJrdXAgKCkge1xuICAgICAgICBsZXQgbWFya3VwID0gJzx1bCBjbGFzcz1cInBhZ2luYXRpb25cIj4nO1xuXG4gICAgICAgIG1hcmt1cCArPSAnPGxpIGNsYXNzPVwiaXMteHMgcHJldmlvdXMtbmV4dCBwcmV2aW91cyBkaXNhYmxlZCBoaWRkZW4tbGcgaGlkZGVuLW1kIGhpZGRlbi1zbVwiPjxhIGhyZWY9XCIjXCIgZGF0YS1yb2xlPVwicHJldmlvdXNcIj48aSBjbGFzcz1cImZhIGZhLWNhcmV0LWxlZnRcIj48L2k+IFByZXZpb3VzPC9hPjwvbGk+JztcbiAgICAgICAgbWFya3VwICs9ICc8bGkgY2xhc3M9XCJoaWRkZW4tbGcgaGlkZGVuLW1kIGhpZGRlbi1zbSBkaXNhYmxlZFwiPjxzcGFuPlBhZ2UgPHN0cm9uZyBjbGFzcz1cInBhZ2UtbnVtYmVyXCI+MTwvc3Ryb25nPiBvZiA8c3Ryb25nPicgKyB0aGlzLnBhZ2VDb3VudCArICc8L3N0cm9uZz48L3NwYW4+PC9saT4nO1xuXG4gICAgICAgIGZvciAobGV0IHBhZ2VJbmRleCA9IDA7IHBhZ2VJbmRleCA8IHRoaXMucGFnZUNvdW50OyBwYWdlSW5kZXgrKykge1xuICAgICAgICAgICAgbGV0IHN0YXJ0SW5kZXggPSAocGFnZUluZGV4ICogdGhpcy5wYWdlTGVuZ3RoKSArIDE7XG4gICAgICAgICAgICBsZXQgZW5kSW5kZXggPSBNYXRoLm1pbihzdGFydEluZGV4ICsgdGhpcy5wYWdlTGVuZ3RoIC0gMSwgdGhpcy50YXNrQ291bnQpO1xuXG4gICAgICAgICAgICBtYXJrdXAgKz0gJzxsaSBjbGFzcz1cImlzLW5vdC14cyBoaWRkZW4teHMgJyArIChwYWdlSW5kZXggPT09IDAgPyAnYWN0aXZlJyA6ICcnKSArICdcIj48YSBocmVmPVwiI1wiIGRhdGEtcGFnZS1pbmRleD1cIicgKyBwYWdlSW5kZXggKyAnXCIgZGF0YS1yb2xlPVwic2hvd1BhZ2VcIj4nICsgc3RhcnRJbmRleCArICcg4oCmICcgKyBlbmRJbmRleCArICc8L2E+PC9saT4nO1xuICAgICAgICB9XG5cbiAgICAgICAgbWFya3VwICs9ICc8bGkgY2xhc3M9XCJuZXh0IHByZXZpb3VzLW5leHQgaGlkZGVuLWxnIGhpZGRlbi1tZCBoaWRkZW4tc21cIj48YSBocmVmPVwiI1wiIGRhdGEtcm9sZT1cIm5leHRcIj5OZXh0IDxpIGNsYXNzPVwiZmEgZmEtY2FyZXQtcmlnaHRcIj48L2k+PC9hPjwvbGk+JztcbiAgICAgICAgbWFya3VwICs9ICc8L3VsPic7XG5cbiAgICAgICAgcmV0dXJuIG1hcmt1cDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgaXNSZXF1aXJlZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnRhc2tDb3VudCA+IHRoaXMucGFnZUxlbmd0aDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgaXNSZW5kZXJlZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQgIT09IG51bGw7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKi9cbiAgICBzZWxlY3RQYWdlIChwYWdlSW5kZXgpIHtcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMucGFnZUFjdGlvbnMsIChwYWdlQWN0aW9uKSA9PiB7XG4gICAgICAgICAgICBsZXQgaXNBY3RpdmUgPSBwYXJzZUludChwYWdlQWN0aW9uLmdldEF0dHJpYnV0ZSgnZGF0YS1wYWdlLWluZGV4JyksIDEwKSA9PT0gcGFnZUluZGV4O1xuICAgICAgICAgICAgbGV0IGFjdGlvbkNvbnRhaW5lciA9IHBhZ2VBY3Rpb24ucGFyZW50Tm9kZTtcblxuICAgICAgICAgICAgaWYgKGlzQWN0aXZlKSB7XG4gICAgICAgICAgICAgICAgYWN0aW9uQ29udGFpbmVyLmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBhY3Rpb25Db250YWluZXIuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucGFnZS1udW1iZXInKS5pbm5lclRleHQgPSAocGFnZUluZGV4ICsgMSk7XG4gICAgICAgIHRoaXMucHJldmlvdXNBY3Rpb24ucGFyZW50RWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdkaXNhYmxlZCcpO1xuICAgICAgICB0aGlzLm5leHRBY3Rpb24ucGFyZW50RWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdkaXNhYmxlZCcpO1xuXG4gICAgICAgIGlmIChwYWdlSW5kZXggPT09IDApIHtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNBY3Rpb24ucGFyZW50RWxlbWVudC5jbGFzc0xpc3QuYWRkKCdkaXNhYmxlZCcpO1xuICAgICAgICB9IGVsc2UgaWYgKHBhZ2VJbmRleCA9PT0gdGhpcy5wYWdlQ291bnQgLSAxKSB7XG4gICAgICAgICAgICB0aGlzLm5leHRBY3Rpb24ucGFyZW50RWxlbWVudC5jbGFzc0xpc3QuYWRkKCdkaXNhYmxlZCcpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrTGlzdFBhZ2luYXRpb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy90YXNrLWxpc3QtcGFnaW5hdG9yLmpzIiwibGV0IFRhc2tMaXN0TW9kZWwgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdCcpO1xubGV0IEljb24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2ljb24nKTtcbmxldCBUYXNrSWRMaXN0ID0gcmVxdWlyZSgnLi90YXNrLWlkLWxpc3QnKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgVGFza0xpc3Qge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlTGVuZ3RoXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHBhZ2VMZW5ndGgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5jdXJyZW50UGFnZUluZGV4ID0gMDtcbiAgICAgICAgdGhpcy5wYWdlTGVuZ3RoID0gcGFnZUxlbmd0aDtcbiAgICAgICAgdGhpcy50YXNrSWRMaXN0ID0gbnVsbDtcbiAgICAgICAgdGhpcy5pc0luaXRpYWxpemluZyA9IGZhbHNlO1xuICAgICAgICB0aGlzLnRhc2tMaXN0TW9kZWxzID0ge307XG4gICAgICAgIHRoaXMuaGVhZGluZyA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignaDInKTtcblxuICAgICAgICAvKipcbiAgICAgICAgICogQHR5cGUge0ljb259XG4gICAgICAgICAqL1xuICAgICAgICB0aGlzLmJ1c3lJY29uID0gdGhpcy5fY3JlYXRlQnVzeUljb24oKTtcbiAgICAgICAgdGhpcy5oZWFkaW5nLmFwcGVuZENoaWxkKHRoaXMuYnVzeUljb24uZWxlbWVudCk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuaXNJbml0aWFsaXppbmcgPSB0cnVlO1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnaGlkZGVuJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5fcmVxdWVzdFRhc2tJZHMoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IGluZGV4XG4gICAgICovXG4gICAgc2V0Q3VycmVudFBhZ2VJbmRleCAoaW5kZXgpIHtcbiAgICAgICAgdGhpcy5jdXJyZW50UGFnZUluZGV4ID0gaW5kZXg7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBwYWdpbmF0aW9uRWxlbWVudFxuICAgICAqL1xuICAgIHNldFBhZ2luYXRpb25FbGVtZW50IChwYWdpbmF0aW9uRWxlbWVudCkge1xuICAgICAgICB0aGlzLmhlYWRpbmcuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdhZnRlcmVuZCcsIHBhZ2luYXRpb25FbGVtZW50KTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRJbml0aWFsaXplZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGFzay1saXN0LmluaXRpYWxpemVkJztcbiAgICB9O1xuXG4gICAgX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IHJlcXVlc3RJZCA9IGV2ZW50LmRldGFpbC5yZXF1ZXN0SWQ7XG4gICAgICAgIGxldCByZXNwb25zZSA9IGV2ZW50LmRldGFpbC5yZXNwb25zZTtcblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmVxdWVzdFRhc2tJZHMnKSB7XG4gICAgICAgICAgICB0aGlzLnRhc2tJZExpc3QgPSBuZXcgVGFza0lkTGlzdChyZXNwb25zZSwgdGhpcy5wYWdlTGVuZ3RoKTtcbiAgICAgICAgICAgIHRoaXMuaXNJbml0aWFsaXppbmcgPSBmYWxzZTtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChUYXNrTGlzdC5nZXRJbml0aWFsaXplZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVUYXNrUGFnZScpIHtcbiAgICAgICAgICAgIGxldCB0YXNrTGlzdE1vZGVsID0gbmV3IFRhc2tMaXN0TW9kZWwodGhpcy5fY3JlYXRlVGFza0xpc3RFbGVtZW50RnJvbUh0bWwocmVzcG9uc2UpKTtcbiAgICAgICAgICAgIGxldCBwYWdlSW5kZXggPSB0YXNrTGlzdE1vZGVsLmdldFBhZ2VJbmRleCgpO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0TW9kZWxzW3BhZ2VJbmRleF0gPSB0YXNrTGlzdE1vZGVsO1xuICAgICAgICAgICAgdGhpcy5yZW5kZXIocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlVGFza1NldFdpdGhEZWxheShcbiAgICAgICAgICAgICAgICBwYWdlSW5kZXgsXG4gICAgICAgICAgICAgICAgdGFza0xpc3RNb2RlbC5nZXRUYXNrc0J5U3RhdGVzKFsnaW4tcHJvZ3Jlc3MnLCAncXVldWVkLWZvci1hc3NpZ25tZW50JywgJ3F1ZXVlZCddKS5zbGljZSgwLCAxMClcbiAgICAgICAgICAgICk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVUYXNrU2V0Jykge1xuICAgICAgICAgICAgbGV0IHVwZGF0ZWRUYXNrTGlzdE1vZGVsID0gbmV3IFRhc2tMaXN0TW9kZWwodGhpcy5fY3JlYXRlVGFza0xpc3RFbGVtZW50RnJvbUh0bWwocmVzcG9uc2UpKTtcbiAgICAgICAgICAgIGxldCBwYWdlSW5kZXggPSB1cGRhdGVkVGFza0xpc3RNb2RlbC5nZXRQYWdlSW5kZXgoKTtcbiAgICAgICAgICAgIGxldCB0YXNrTGlzdE1vZGVsID0gdGhpcy50YXNrTGlzdE1vZGVsc1twYWdlSW5kZXhdO1xuXG4gICAgICAgICAgICB0YXNrTGlzdE1vZGVsLnVwZGF0ZUZyb21UYXNrTGlzdCh1cGRhdGVkVGFza0xpc3RNb2RlbCk7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkoXG4gICAgICAgICAgICAgICAgcGFnZUluZGV4LFxuICAgICAgICAgICAgICAgIHRhc2tMaXN0TW9kZWwuZ2V0VGFza3NCeVN0YXRlcyhbJ2luLXByb2dyZXNzJywgJ3F1ZXVlZC1mb3ItYXNzaWdubWVudCcsICdxdWV1ZWQnXSkuc2xpY2UoMCwgMTApXG4gICAgICAgICAgICApO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIF9yZXF1ZXN0VGFza0lkcyAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXRhc2staWRzLXVybCcpLCB0aGlzLmVsZW1lbnQsICdyZXF1ZXN0VGFza0lkcycpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICovXG4gICAgcmVuZGVyIChwYWdlSW5kZXgpIHtcbiAgICAgICAgdGhpcy5idXN5SWNvbi5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2hpZGRlbicpO1xuXG4gICAgICAgIGxldCBoYXNUYXNrTGlzdEVsZW1lbnRGb3JQYWdlID0gT2JqZWN0LmtleXModGhpcy50YXNrTGlzdE1vZGVscykuaW5jbHVkZXMocGFnZUluZGV4LnRvU3RyaW5nKDEwKSk7XG4gICAgICAgIGlmICghaGFzVGFza0xpc3RFbGVtZW50Rm9yUGFnZSkge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUYXNrUGFnZShwYWdlSW5kZXgpO1xuXG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBsZXQgdGFza0xpc3RFbGVtZW50ID0gdGhpcy50YXNrTGlzdE1vZGVsc1twYWdlSW5kZXhdO1xuXG4gICAgICAgIGlmIChwYWdlSW5kZXggPT09IHRoaXMuY3VycmVudFBhZ2VJbmRleCkge1xuICAgICAgICAgICAgbGV0IHJlbmRlcmVkVGFza0xpc3RFbGVtZW50ID0gbmV3IFRhc2tMaXN0TW9kZWwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy50YXNrLWxpc3QnKSk7XG5cbiAgICAgICAgICAgIGlmIChyZW5kZXJlZFRhc2tMaXN0RWxlbWVudC5oYXNQYWdlSW5kZXgoKSkge1xuICAgICAgICAgICAgICAgIGxldCBjdXJyZW50UGFnZUxpc3RFbGVtZW50ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy50YXNrLWxpc3QnKTtcbiAgICAgICAgICAgICAgICBsZXQgc2VsZWN0ZWRQYWdlTGlzdEVsZW1lbnQgPSB0aGlzLnRhc2tMaXN0TW9kZWxzW3RoaXMuY3VycmVudFBhZ2VJbmRleF0uZWxlbWVudDtcblxuICAgICAgICAgICAgICAgIGN1cnJlbnRQYWdlTGlzdEVsZW1lbnQucGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQoc2VsZWN0ZWRQYWdlTGlzdEVsZW1lbnQsIGN1cnJlbnRQYWdlTGlzdEVsZW1lbnQpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuYXBwZW5kQ2hpbGQodGFza0xpc3RFbGVtZW50LmVsZW1lbnQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5idXN5SWNvbi5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2hpZGRlbicpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfcmV0cmlldmVUYXNrUGFnZSAocGFnZUluZGV4KSB7XG4gICAgICAgIGxldCB0YXNrSWRzID0gdGhpcy50YXNrSWRMaXN0LmdldEZvclBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgbGV0IHBvc3REYXRhID0gJ3BhZ2VJbmRleD0nICsgcGFnZUluZGV4ICsgJyZ0YXNrSWRzW109JyArIHRhc2tJZHMuam9pbignJnRhc2tJZHNbXT0nKTtcblxuICAgICAgICBIdHRwQ2xpZW50LnBvc3QoXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXRhc2tsaXN0LXVybCcpLFxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LFxuICAgICAgICAgICAgJ3JldHJpZXZlVGFza1BhZ2UnLFxuICAgICAgICAgICAgcG9zdERhdGFcbiAgICAgICAgKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqIEBwYXJhbSB7VGFza1tdfSB0YXNrc1xuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX3JldHJpZXZlVGFza1NldFdpdGhEZWxheSAocGFnZUluZGV4LCB0YXNrcykge1xuICAgICAgICB3aW5kb3cuc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRhc2tTZXQocGFnZUluZGV4LCB0YXNrcyk7XG4gICAgICAgIH0sIDEwMDApO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICogQHBhcmFtIHtUYXNrW119IHRhc2tzXG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfcmV0cmlldmVUYXNrU2V0IChwYWdlSW5kZXgsIHRhc2tzKSB7XG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRQYWdlSW5kZXggPT09IHBhZ2VJbmRleCAmJiB0YXNrcy5sZW5ndGgpIHtcbiAgICAgICAgICAgIGxldCB0YXNrSWRzID0gW107XG5cbiAgICAgICAgICAgIHRhc2tzLmZvckVhY2goZnVuY3Rpb24gKHRhc2spIHtcbiAgICAgICAgICAgICAgICB0YXNrSWRzLnB1c2godGFzay5nZXRJZCgpKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBsZXQgcG9zdERhdGEgPSAncGFnZUluZGV4PScgKyBwYWdlSW5kZXggKyAnJnRhc2tJZHNbXT0nICsgdGFza0lkcy5qb2luKCcmdGFza0lkc1tdPScpO1xuXG4gICAgICAgICAgICBIdHRwQ2xpZW50LnBvc3QoXG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrbGlzdC11cmwnKSxcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQsXG4gICAgICAgICAgICAgICAgJ3JldHJpZXZlVGFza1NldCcsXG4gICAgICAgICAgICAgICAgcG9zdERhdGFcbiAgICAgICAgICAgICk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IGh0bWxcbiAgICAgKiBAcmV0dXJucyB7RWxlbWVudH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVUYXNrTGlzdEVsZW1lbnRGcm9tSHRtbCAoaHRtbCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSBodG1sO1xuXG4gICAgICAgIHJldHVybiBjb250YWluZXIucXVlcnlTZWxlY3RvcignLnRhc2stbGlzdCcpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7SWNvbn1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVCdXN5SWNvbiAoKSB7XG4gICAgICAgIGxldCBjb250YWluZXIgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmlubmVySFRNTCA9ICc8aSBjbGFzcz1cImZhXCI+PC9pPic7XG5cbiAgICAgICAgbGV0IGljb24gPSBuZXcgSWNvbihjb250YWluZXIucXVlcnlTZWxlY3RvcignLmZhJykpO1xuICAgICAgICBpY29uLnNldEJ1c3koKTtcblxuICAgICAgICByZXR1cm4gaWNvbjtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza0xpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy90YXNrLWxpc3QuanMiLCJjbGFzcyBCeUVycm9yTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGNvbnNvbGUubG9nKCdpbml0Jyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBCeUVycm9yTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QuanMiLCJsZXQgU29ydCA9IHJlcXVpcmUoJy4vc29ydCcpO1xuXG5jbGFzcyBCeVBhZ2VMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnNvcnQgPSBuZXcgU29ydChlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5zb3J0JyksIGVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLXNvcnRhYmxlLWl0ZW0nKSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLnNvcnQuaW5pdCgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQnlQYWdlTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdC5qcyIsImxldCBTb3J0Q29udHJvbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sJyk7XG5sZXQgU29ydGFibGVJdGVtID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtJyk7XG5sZXQgU29ydGFibGVJdGVtTGlzdCA9IHJlcXVpcmUoJy4uL21vZGVsL3NvcnRhYmxlLWl0ZW0tbGlzdCcpO1xubGV0IFNvcnRDb250cm9sQ29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL3NvcnQtY29udHJvbC1jb2xsZWN0aW9uJyk7XG5cbmNsYXNzIFNvcnQge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7Tm9kZUxpc3R9IHNvcnRhYmxlSXRlbXNOb2RlTGlzdFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzb3J0YWJsZUl0ZW1zTm9kZUxpc3QpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zb3J0Q29udHJvbENvbGxlY3Rpb24gPSB0aGlzLl9jcmVhdGVTb3J0YWJsZUNvbnRyb2xDb2xsZWN0aW9uKCk7XG4gICAgICAgIHRoaXMuc29ydGFibGVJdGVtc05vZGVMaXN0ID0gc29ydGFibGVJdGVtc05vZGVMaXN0O1xuICAgICAgICB0aGlzLnNvcnRhYmxlSXRlbXNMaXN0ID0gdGhpcy5fY3JlYXRlU29ydGFibGVJdGVtTGlzdCgpO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2ludmlzaWJsZScpO1xuICAgICAgICB0aGlzLnNvcnRDb250cm9sQ29sbGVjdGlvbi5jb250cm9scy5mb3JFYWNoKChjb250cm9sKSA9PiB7XG4gICAgICAgICAgICBjb250cm9sLmluaXQoKTtcbiAgICAgICAgICAgIGNvbnRyb2wuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFNvcnRDb250cm9sLmdldFNvcnRSZXF1ZXN0ZWRFdmVudE5hbWUoKSwgdGhpcy5fc29ydENvbnRyb2xDbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7U29ydGFibGVJdGVtTGlzdH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVTb3J0YWJsZUl0ZW1MaXN0ICgpIHtcbiAgICAgICAgbGV0IHNvcnRhYmxlSXRlbXMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QsIChzb3J0YWJsZUl0ZW1FbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBzb3J0YWJsZUl0ZW1zLnB1c2gobmV3IFNvcnRhYmxlSXRlbShzb3J0YWJsZUl0ZW1FbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBuZXcgU29ydGFibGVJdGVtTGlzdChzb3J0YWJsZUl0ZW1zKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7U29ydENvbnRyb2xDb2xsZWN0aW9ufVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVNvcnRhYmxlQ29udHJvbENvbGxlY3Rpb24gKCkge1xuICAgICAgICBsZXQgY29udHJvbHMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5zb3J0LWNvbnRyb2wnKSwgKHNvcnRDb250cm9sRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgY29udHJvbHMucHVzaChuZXcgU29ydENvbnRyb2woc29ydENvbnRyb2xFbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBuZXcgU29ydENvbnRyb2xDb2xsZWN0aW9uKGNvbnRyb2xzKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9zb3J0Q29udHJvbENsaWNrRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMuc29ydGFibGVJdGVtc05vZGVMaXN0Lml0ZW0oMCkucGFyZW50RWxlbWVudDtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QsIChzb3J0YWJsZUl0ZW1FbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBzb3J0YWJsZUl0ZW1FbGVtZW50LnBhcmVudEVsZW1lbnQucmVtb3ZlQ2hpbGQoc29ydGFibGVJdGVtRWxlbWVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGxldCBzb3J0ZWRJdGVtcyA9IHRoaXMuc29ydGFibGVJdGVtc0xpc3Quc29ydChldmVudC5kZXRhaWwua2V5cyk7XG5cbiAgICAgICAgc29ydGVkSXRlbXMuZm9yRWFjaCgoc29ydGFibGVJdGVtKSA9PiB7XG4gICAgICAgICAgICBwYXJlbnQuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdiZWZvcmVlbmQnLCBzb3J0YWJsZUl0ZW0uZWxlbWVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuc29ydENvbnRyb2xDb2xsZWN0aW9uLnNldFNvcnRlZChldmVudC50YXJnZXQpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU29ydDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL3NvcnQuanMiLCJsZXQgTW9kYWwgPSByZXF1aXJlKCdib290c3RyYXAubmF0aXZlJykuTW9kYWw7XG5cbi8qKlxuICogQHBhcmFtIHtOb2RlTGlzdH0gdGFza1R5cGVDb250YWluZXJzXG4gKi9cbm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKHRhc2tUeXBlQ29udGFpbmVycykge1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGFza1R5cGVDb250YWluZXJzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGxldCB1bmF2YWlsYWJsZVRhc2tUeXBlID0gdGFza1R5cGVDb250YWluZXJzW2ldO1xuICAgICAgICBsZXQgdGFza1R5cGVLZXkgPSB1bmF2YWlsYWJsZVRhc2tUeXBlLmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLXR5cGUnKTtcbiAgICAgICAgbGV0IG1vZGFsSWQgPSB0YXNrVHlwZUtleSArICctYWNjb3VudC1yZXF1aXJlZC1tb2RhbCc7XG4gICAgICAgIGxldCBtb2RhbEVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChtb2RhbElkKTtcbiAgICAgICAgbGV0IG1vZGFsID0gbmV3IE1vZGFsKG1vZGFsRWxlbWVudCk7XG5cbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG1vZGFsLnNob3coKTtcbiAgICAgICAgfSk7XG4gICAgfVxufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91bmF2YWlsYWJsZS10YXNrLXR5cGUtbW9kYWwtbGF1bmNoZXIuanMiLCJjbGFzcyBGb3JtVmFsaWRhdG9yIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1N0cmlwZX0gc3RyaXBlSnNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoc3RyaXBlSnMpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcyA9IHN0cmlwZUpzO1xuICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IG51bGw7XG4gICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJyc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBkYXRhXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgdmFsaWRhdGUgKGRhdGEpIHtcbiAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSBudWxsO1xuXG4gICAgICAgIE9iamVjdC5lbnRyaWVzKGRhdGEpLmZvckVhY2goKFtrZXksIHZhbHVlXSkgPT4ge1xuICAgICAgICAgICAgaWYgKCF0aGlzLmludmFsaWRGaWVsZCkge1xuICAgICAgICAgICAgICAgIGxldCBjb21wYXJhdG9yVmFsdWUgPSB2YWx1ZS50cmltKCk7XG5cbiAgICAgICAgICAgICAgICBpZiAoY29tcGFyYXRvclZhbHVlID09PSAnJykge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IGtleTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnZW1wdHknO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgaWYgKHRoaXMuaW52YWxpZEZpZWxkKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXMuc3RyaXBlSnMuY2FyZC52YWxpZGF0ZUNhcmROdW1iZXIoZGF0YS5udW1iZXIpKSB7XG4gICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9ICdudW1iZXInO1xuICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnaW52YWxpZCc7XG5cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICghdGhpcy5zdHJpcGVKcy5jYXJkLnZhbGlkYXRlRXhwaXJ5KGRhdGEuZXhwX21vbnRoLCBkYXRhLmV4cF95ZWFyKSkge1xuICAgICAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSAnZXhwX21vbnRoJztcbiAgICAgICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJ2ludmFsaWQnO1xuXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXMuc3RyaXBlSnMuY2FyZC52YWxpZGF0ZUNWQyhkYXRhLmN2YykpIHtcbiAgICAgICAgICAgIHRoaXMuaW52YWxpZEZpZWxkID0gJ2N2Yyc7XG4gICAgICAgICAgICB0aGlzLmVycm9yTWVzc2FnZSA9ICdpbnZhbGlkJztcblxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtVmFsaWRhdG9yO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0tdmFsaWRhdG9yLmpzIiwibGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcbmxldCBBbGVydEZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5Jyk7XG5sZXQgRm9ybUJ1dHRvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcblxuY2xhc3MgRm9ybSB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHZhbGlkYXRvcikge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnZhbGlkYXRvciA9IHZhbGlkYXRvcjtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24gPSBuZXcgRm9ybUJ1dHRvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgdGhpcy5fc3VibWl0RXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgZ2V0U3RyaXBlUHVibGlzaGFibGVLZXkgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdHJpcGUtcHVibGlzaGFibGUta2V5Jyk7XG4gICAgfTtcblxuICAgIGdldFVwZGF0ZUNhcmRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3VzZXIuYWNjb3VudC5jYXJkLnVwZGF0ZSc7XG4gICAgfVxuXG4gICAgZGlzYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9uLmRpc2FibGUoKTtcbiAgICB9O1xuXG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24ubWFya0FzQXZhaWxhYmxlKCk7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9uLmVuYWJsZSgpO1xuICAgIH07XG5cbiAgICBfZ2V0RGF0YSAoKSB7XG4gICAgICAgIGNvbnN0IGRhdGEgPSB7fTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tkYXRhLXN0cmlwZV0nKSwgZnVuY3Rpb24gKGRhdGFFbGVtZW50KSB7XG4gICAgICAgICAgICBsZXQgZmllbGRLZXkgPSBkYXRhRWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RyaXBlJyk7XG5cbiAgICAgICAgICAgIGRhdGFbZmllbGRLZXldID0gZGF0YUVsZW1lbnQudmFsdWU7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBkYXRhO1xuICAgIH1cblxuICAgIF9zdWJtaXRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICB0aGlzLl9yZW1vdmVFcnJvckFsZXJ0cygpO1xuICAgICAgICB0aGlzLmRpc2FibGUoKTtcblxuICAgICAgICBsZXQgZGF0YSA9IHRoaXMuX2dldERhdGEoKTtcbiAgICAgICAgbGV0IGlzVmFsaWQgPSB0aGlzLnZhbGlkYXRvci52YWxpZGF0ZShkYXRhKTtcblxuICAgICAgICBpZiAoIWlzVmFsaWQpIHtcbiAgICAgICAgICAgIHRoaXMuaGFuZGxlUmVzcG9uc2VFcnJvcih0aGlzLmNyZWF0ZVJlc3BvbnNlRXJyb3IodGhpcy52YWxpZGF0b3IuaW52YWxpZEZpZWxkLCB0aGlzLnZhbGlkYXRvci5lcnJvck1lc3NhZ2UpKTtcbiAgICAgICAgICAgIHRoaXMuZW5hYmxlKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsZXQgZXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQodGhpcy5nZXRVcGRhdGVDYXJkRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IGRhdGFcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChldmVudCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JlbW92ZUVycm9yQWxlcnRzICgpIHtcbiAgICAgICAgbGV0IGVycm9yQWxlcnRzID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hbGVydCcpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbChlcnJvckFsZXJ0cywgZnVuY3Rpb24gKGVycm9yQWxlcnQpIHtcbiAgICAgICAgICAgIGVycm9yQWxlcnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChlcnJvckFsZXJ0KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9kaXNwbGF5RmllbGRFcnJvciAoZmllbGQsIGVycm9yKSB7XG4gICAgICAgIGxldCBhbGVydCA9IEFsZXJ0RmFjdG9yeS5jcmVhdGVGcm9tQ29udGVudCh0aGlzLmRvY3VtZW50LCAnPHA+JyArIGVycm9yLm1lc3NhZ2UgKyAnPC9wPicsIGZpZWxkLmdldEF0dHJpYnV0ZSgnaWQnKSk7XG4gICAgICAgIGxldCBlcnJvckNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1mb3I9JyArIGZpZWxkLmdldEF0dHJpYnV0ZSgnaWQnKSArICddJyk7XG5cbiAgICAgICAgaWYgKCFlcnJvckNvbnRhaW5lcikge1xuICAgICAgICAgICAgZXJyb3JDb250YWluZXIgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtZm9yKj0nICsgZmllbGQuZ2V0QXR0cmlidXRlKCdpZCcpICsgJ10nKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGVycm9yQ29udGFpbmVyLmFwcGVuZChhbGVydC5lbGVtZW50KTtcbiAgICB9O1xuXG4gICAgaGFuZGxlUmVzcG9uc2VFcnJvciAoZXJyb3IpIHtcbiAgICAgICAgbGV0IGZpZWxkID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXN0cmlwZT0nICsgZXJyb3IucGFyYW0gKyAnXScpO1xuXG4gICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZmllbGQpO1xuICAgICAgICB0aGlzLl9kaXNwbGF5RmllbGRFcnJvcihmaWVsZCwgZXJyb3IpO1xuICAgIH07XG5cbiAgICBjcmVhdGVSZXNwb25zZUVycm9yIChmaWVsZCwgc3RhdGUpIHtcbiAgICAgICAgbGV0IGVycm9yTWVzc2FnZSA9ICcnO1xuXG4gICAgICAgIGlmIChzdGF0ZSA9PT0gJ2VtcHR5Jykge1xuICAgICAgICAgICAgZXJyb3JNZXNzYWdlID0gJ0hvbGQgb24sIHlvdSBjYW5cXCd0IGxlYXZlIHRoaXMgZW1wdHknO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnaW52YWxpZCcpIHtcbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnVGhlIGNhcmQgbnVtYmVyIGlzIG5vdCBxdWl0ZSByaWdodCc7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ2V4cF9tb250aCcpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnQW4gZXhwaXJ5IGRhdGUgaW4gdGhlIGZ1dHVyZSBpcyBiZXR0ZXInO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoZmllbGQgPT09ICdjdmMnKSB7XG4gICAgICAgICAgICAgICAgZXJyb3JNZXNzYWdlID0gJ1RoZSBDVkMgc2hvdWxkIGJlIDMgb3IgNCBkaWdpdHMnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHBhcmFtOiBmaWVsZCxcbiAgICAgICAgICAgIG1lc3NhZ2U6IGVycm9yTWVzc2FnZVxuICAgICAgICB9O1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0uanMiLCJjb25zdCBTY3JvbGxUbyA9IHJlcXVpcmUoJy4uL3Njcm9sbC10bycpO1xuXG5jbGFzcyBOYXZCYXJBbmNob3Ige1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBzY3JvbGxPZmZzZXRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCwgc2Nyb2xsT2Zmc2V0KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc2Nyb2xsT2Zmc2V0ID0gc2Nyb2xsT2Zmc2V0O1xuICAgICAgICB0aGlzLnRhcmdldElkID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKS5yZXBsYWNlKCcjJywgJycpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuaGFuZGxlQ2xpY2suYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIGhhbmRsZUNsaWNrIChldmVudCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICBsZXQgdGFyZ2V0ID0gdGhpcy5nZXRUYXJnZXQoKTtcblxuICAgICAgICBpZiAodGhpcy5lbGVtZW50LmNsYXNzTGlzdC5jb250YWlucygnanMtZmlyc3QnKSkge1xuICAgICAgICAgICAgU2Nyb2xsVG8uZ29Ubyh0YXJnZXQsIDApO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgU2Nyb2xsVG8uc2Nyb2xsVG8odGFyZ2V0LCB0aGlzLnNjcm9sbE9mZnNldCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBnZXRUYXJnZXQgKCkge1xuICAgICAgICByZXR1cm4gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy50YXJnZXRJZCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IE5hdkJhckFuY2hvcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWFuY2hvci5qcyIsImNvbnN0IE5hdkJhckFuY2hvciA9IHJlcXVpcmUoJy4vbmF2YmFyLWFuY2hvcicpO1xuXG5jbGFzcyBOYXZCYXJJdGVtIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gc2Nyb2xsT2Zmc2V0XG4gICAgICogQHBhcmFtIHtmdW5jdGlvbn0gTmF2QmFyTGlzdFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQsIE5hdkJhckxpc3QpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5hbmNob3IgPSBudWxsO1xuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBudWxsO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZWxlbWVudC5jaGlsZHJlbi5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgbGV0IGNoaWxkID0gZWxlbWVudC5jaGlsZHJlbi5pdGVtKGkpO1xuXG4gICAgICAgICAgICBpZiAoY2hpbGQubm9kZU5hbWUgPT09ICdBJyAmJiBjaGlsZC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKVswXSA9PT0gJyMnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5hbmNob3IgPSBuZXcgTmF2QmFyQW5jaG9yKGNoaWxkLCBzY3JvbGxPZmZzZXQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoY2hpbGQubm9kZU5hbWUgPT09ICdVTCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuZXcgTmF2QmFyTGlzdChjaGlsZCwgc2Nyb2xsT2Zmc2V0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBnZXRUYXJnZXRzICgpIHtcbiAgICAgICAgbGV0IHRhcmdldHMgPSBbXTtcblxuICAgICAgICBpZiAodGhpcy5hbmNob3IpIHtcbiAgICAgICAgICAgIHRhcmdldHMucHVzaCh0aGlzLmFuY2hvci5nZXRUYXJnZXQoKSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5uYXZCYXJMaXN0KSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QuZ2V0VGFyZ2V0cygpLmZvckVhY2goZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICAgICAgICAgIHRhcmdldHMucHVzaCh0YXJnZXQpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFyZ2V0cztcbiAgICB9XG5cbiAgICBjb250YWluc1RhcmdldElkICh0YXJnZXRJZCkge1xuICAgICAgICBpZiAodGhpcy5hbmNob3IgJiYgdGhpcy5hbmNob3IudGFyZ2V0SWQgPT09IHRhcmdldElkKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLm5hdkJhckxpc3QuY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfTtcblxuICAgIHNldEFjdGl2ZSAodGFyZ2V0SWQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QgJiYgdGhpcy5uYXZCYXJMaXN0LmNvbnRhaW5zVGFyZ2V0SWQodGFyZ2V0SWQpKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3Quc2V0QWN0aXZlKHRhcmdldElkKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTmF2QmFySXRlbTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWl0ZW0uanMiLCJsZXQgTmF2QmFySXRlbSA9IHJlcXVpcmUoJy4vbmF2YmFyLWl0ZW0nKTtcblxuY2xhc3MgTmF2QmFyTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHNjcm9sbE9mZnNldFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcyA9IFtdO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZWxlbWVudC5jaGlsZHJlbi5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5wdXNoKG5ldyBOYXZCYXJJdGVtKGVsZW1lbnQuY2hpbGRyZW4uaXRlbShpKSwgc2Nyb2xsT2Zmc2V0LCBOYXZCYXJMaXN0KSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VGFyZ2V0cyAoKSB7XG4gICAgICAgIGxldCB0YXJnZXRzID0gW107XG5cbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0aGlzLm5hdkJhckl0ZW1zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckl0ZW1zW2ldLmdldFRhcmdldHMoKS5mb3JFYWNoKGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICB0YXJnZXRzLnB1c2godGFyZ2V0KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRhcmdldHM7XG4gICAgfTtcblxuICAgIGNvbnRhaW5zVGFyZ2V0SWQgKHRhcmdldElkKSB7XG4gICAgICAgIGxldCBjb250YWlucyA9IGZhbHNlO1xuXG4gICAgICAgIHRoaXMubmF2QmFySXRlbXMuZm9yRWFjaChmdW5jdGlvbiAobmF2QmFySXRlbSkge1xuICAgICAgICAgICAgaWYgKG5hdkJhckl0ZW0uY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCkpIHtcbiAgICAgICAgICAgICAgICBjb250YWlucyA9IHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBjb250YWlucztcbiAgICB9O1xuXG4gICAgY2xlYXJBY3RpdmUgKCkge1xuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpJyksIGZ1bmN0aW9uIChsaXN0SXRlbUVsZW1lbnQpIHtcbiAgICAgICAgICAgIGxpc3RJdGVtRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHNldEFjdGl2ZSAodGFyZ2V0SWQpIHtcbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5mb3JFYWNoKGZ1bmN0aW9uIChuYXZCYXJJdGVtKSB7XG4gICAgICAgICAgICBpZiAobmF2QmFySXRlbS5jb250YWluc1RhcmdldElkKHRhcmdldElkKSkge1xuICAgICAgICAgICAgICAgIG5hdkJhckl0ZW0uc2V0QWN0aXZlKHRhcmdldElkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBOYXZCYXJMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItbGlzdC5qcyIsInJlcXVpcmUoJy4vbmF2YmFyLWxpc3QnKTtcblxuY2xhc3MgU2Nyb2xsU3B5IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05hdkJhckxpc3R9IG5hdkJhckxpc3RcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gb2Zmc2V0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKG5hdkJhckxpc3QsIG9mZnNldCkge1xuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuYXZCYXJMaXN0O1xuICAgICAgICB0aGlzLm9mZnNldCA9IG9mZnNldDtcbiAgICB9XG5cbiAgICBzY3JvbGxFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgbGV0IGFjdGl2ZUxpbmtUYXJnZXQgPSBudWxsO1xuICAgICAgICBsZXQgbGlua1RhcmdldHMgPSB0aGlzLm5hdkJhckxpc3QuZ2V0VGFyZ2V0cygpO1xuICAgICAgICBsZXQgb2Zmc2V0ID0gdGhpcy5vZmZzZXQ7XG4gICAgICAgIGxldCBsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQgPSBbXTtcblxuICAgICAgICBsaW5rVGFyZ2V0cy5mb3JFYWNoKGZ1bmN0aW9uIChsaW5rVGFyZ2V0KSB7XG4gICAgICAgICAgICBpZiAobGlua1RhcmdldCkge1xuICAgICAgICAgICAgICAgIGxldCBvZmZzZXRUb3AgPSBsaW5rVGFyZ2V0LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcDtcblxuICAgICAgICAgICAgICAgIGlmIChvZmZzZXRUb3AgPCBvZmZzZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgbGlua1RhcmdldHNQYXN0VGhyZXNob2xkLnB1c2gobGlua1RhcmdldCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBpZiAobGlua1RhcmdldHNQYXN0VGhyZXNob2xkLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzWzBdO1xuICAgICAgICB9IGVsc2UgaWYgKGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZC5sZW5ndGggPT09IGxpbmtUYXJnZXRzLmxlbmd0aCkge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzW2xpbmtUYXJnZXRzLmxlbmd0aCAtIDFdO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZFtsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQubGVuZ3RoIC0gMV07XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoYWN0aXZlTGlua1RhcmdldCkge1xuICAgICAgICAgICAgdGhpcy5uYXZCYXJMaXN0LmNsZWFyQWN0aXZlKCk7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3Quc2V0QWN0aXZlKGFjdGl2ZUxpbmtUYXJnZXQuZ2V0QXR0cmlidXRlKCdpZCcpKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIHNweSAoKSB7XG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFxuICAgICAgICAgICAgJ3Njcm9sbCcsXG4gICAgICAgICAgICB0aGlzLnNjcm9sbEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSxcbiAgICAgICAgICAgIHRydWVcbiAgICAgICAgKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU2Nyb2xsU3B5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9zY3JvbGxzcHkuanMiLCIvKipcbiAqIFNpbXBsZSwgbGlnaHR3ZWlnaHQsIHVzYWJsZSBsb2NhbCBhdXRvY29tcGxldGUgbGlicmFyeSBmb3IgbW9kZXJuIGJyb3dzZXJzXG4gKiBCZWNhdXNlIHRoZXJlIHdlcmVu4oCZdCBlbm91Z2ggYXV0b2NvbXBsZXRlIHNjcmlwdHMgaW4gdGhlIHdvcmxkPyBCZWNhdXNlIEnigJltIGNvbXBsZXRlbHkgaW5zYW5lIGFuZCBoYXZlIE5JSCBzeW5kcm9tZT8gUHJvYmFibHkgYm90aC4gOlBcbiAqIEBhdXRob3IgTGVhIFZlcm91IGh0dHA6Ly9sZWF2ZXJvdS5naXRodWIuaW8vYXdlc29tcGxldGVcbiAqIE1JVCBsaWNlbnNlXG4gKi9cblxuKGZ1bmN0aW9uICgpIHtcblxudmFyIF8gPSBmdW5jdGlvbiAoaW5wdXQsIG8pIHtcblx0dmFyIG1lID0gdGhpcztcblxuXHQvLyBTZXR1cFxuXG5cdHRoaXMuaXNPcGVuZWQgPSBmYWxzZTtcblxuXHR0aGlzLmlucHV0ID0gJChpbnB1dCk7XG5cdHRoaXMuaW5wdXQuc2V0QXR0cmlidXRlKFwiYXV0b2NvbXBsZXRlXCIsIFwib2ZmXCIpO1xuXHR0aGlzLmlucHV0LnNldEF0dHJpYnV0ZShcImFyaWEtYXV0b2NvbXBsZXRlXCIsIFwibGlzdFwiKTtcblxuXHRvID0gbyB8fCB7fTtcblxuXHRjb25maWd1cmUodGhpcywge1xuXHRcdG1pbkNoYXJzOiAyLFxuXHRcdG1heEl0ZW1zOiAxMCxcblx0XHRhdXRvRmlyc3Q6IGZhbHNlLFxuXHRcdGRhdGE6IF8uREFUQSxcblx0XHRmaWx0ZXI6IF8uRklMVEVSX0NPTlRBSU5TLFxuXHRcdHNvcnQ6IG8uc29ydCA9PT0gZmFsc2UgPyBmYWxzZSA6IF8uU09SVF9CWUxFTkdUSCxcblx0XHRpdGVtOiBfLklURU0sXG5cdFx0cmVwbGFjZTogXy5SRVBMQUNFXG5cdH0sIG8pO1xuXG5cdHRoaXMuaW5kZXggPSAtMTtcblxuXHQvLyBDcmVhdGUgbmVjZXNzYXJ5IGVsZW1lbnRzXG5cblx0dGhpcy5jb250YWluZXIgPSAkLmNyZWF0ZShcImRpdlwiLCB7XG5cdFx0Y2xhc3NOYW1lOiBcImF3ZXNvbXBsZXRlXCIsXG5cdFx0YXJvdW5kOiBpbnB1dFxuXHR9KTtcblxuXHR0aGlzLnVsID0gJC5jcmVhdGUoXCJ1bFwiLCB7XG5cdFx0aGlkZGVuOiBcImhpZGRlblwiLFxuXHRcdGluc2lkZTogdGhpcy5jb250YWluZXJcblx0fSk7XG5cblx0dGhpcy5zdGF0dXMgPSAkLmNyZWF0ZShcInNwYW5cIiwge1xuXHRcdGNsYXNzTmFtZTogXCJ2aXN1YWxseS1oaWRkZW5cIixcblx0XHRyb2xlOiBcInN0YXR1c1wiLFxuXHRcdFwiYXJpYS1saXZlXCI6IFwiYXNzZXJ0aXZlXCIsXG5cdFx0XCJhcmlhLXJlbGV2YW50XCI6IFwiYWRkaXRpb25zXCIsXG5cdFx0aW5zaWRlOiB0aGlzLmNvbnRhaW5lclxuXHR9KTtcblxuXHQvLyBCaW5kIGV2ZW50c1xuXG5cdHRoaXMuX2V2ZW50cyA9IHtcblx0XHRpbnB1dDoge1xuXHRcdFx0XCJpbnB1dFwiOiB0aGlzLmV2YWx1YXRlLmJpbmQodGhpcyksXG5cdFx0XHRcImJsdXJcIjogdGhpcy5jbG9zZS5iaW5kKHRoaXMsIHsgcmVhc29uOiBcImJsdXJcIiB9KSxcblx0XHRcdFwia2V5ZG93blwiOiBmdW5jdGlvbihldnQpIHtcblx0XHRcdFx0dmFyIGMgPSBldnQua2V5Q29kZTtcblxuXHRcdFx0XHQvLyBJZiB0aGUgZHJvcGRvd24gYHVsYCBpcyBpbiB2aWV3LCB0aGVuIGFjdCBvbiBrZXlkb3duIGZvciB0aGUgZm9sbG93aW5nIGtleXM6XG5cdFx0XHRcdC8vIEVudGVyIC8gRXNjIC8gVXAgLyBEb3duXG5cdFx0XHRcdGlmKG1lLm9wZW5lZCkge1xuXHRcdFx0XHRcdGlmIChjID09PSAxMyAmJiBtZS5zZWxlY3RlZCkgeyAvLyBFbnRlclxuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZS5zZWxlY3QoKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0ZWxzZSBpZiAoYyA9PT0gMjcpIHsgLy8gRXNjXG5cdFx0XHRcdFx0XHRtZS5jbG9zZSh7IHJlYXNvbjogXCJlc2NcIiB9KTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0ZWxzZSBpZiAoYyA9PT0gMzggfHwgYyA9PT0gNDApIHsgLy8gRG93bi9VcCBhcnJvd1xuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZVtjID09PSAzOD8gXCJwcmV2aW91c1wiIDogXCJuZXh0XCJdKCk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fSxcblx0XHRmb3JtOiB7XG5cdFx0XHRcInN1Ym1pdFwiOiB0aGlzLmNsb3NlLmJpbmQodGhpcywgeyByZWFzb246IFwic3VibWl0XCIgfSlcblx0XHR9LFxuXHRcdHVsOiB7XG5cdFx0XHRcIm1vdXNlZG93blwiOiBmdW5jdGlvbihldnQpIHtcblx0XHRcdFx0dmFyIGxpID0gZXZ0LnRhcmdldDtcblxuXHRcdFx0XHRpZiAobGkgIT09IHRoaXMpIHtcblxuXHRcdFx0XHRcdHdoaWxlIChsaSAmJiAhL2xpL2kudGVzdChsaS5ub2RlTmFtZSkpIHtcblx0XHRcdFx0XHRcdGxpID0gbGkucGFyZW50Tm9kZTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRpZiAobGkgJiYgZXZ0LmJ1dHRvbiA9PT0gMCkgeyAgLy8gT25seSBzZWxlY3Qgb24gbGVmdCBjbGlja1xuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZS5zZWxlY3QobGksIGV2dC50YXJnZXQpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH1cblx0fTtcblxuXHQkLmJpbmQodGhpcy5pbnB1dCwgdGhpcy5fZXZlbnRzLmlucHV0KTtcblx0JC5iaW5kKHRoaXMuaW5wdXQuZm9ybSwgdGhpcy5fZXZlbnRzLmZvcm0pO1xuXHQkLmJpbmQodGhpcy51bCwgdGhpcy5fZXZlbnRzLnVsKTtcblxuXHRpZiAodGhpcy5pbnB1dC5oYXNBdHRyaWJ1dGUoXCJsaXN0XCIpKSB7XG5cdFx0dGhpcy5saXN0ID0gXCIjXCIgKyB0aGlzLmlucHV0LmdldEF0dHJpYnV0ZShcImxpc3RcIik7XG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJsaXN0XCIpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdHRoaXMubGlzdCA9IHRoaXMuaW5wdXQuZ2V0QXR0cmlidXRlKFwiZGF0YS1saXN0XCIpIHx8IG8ubGlzdCB8fCBbXTtcblx0fVxuXG5cdF8uYWxsLnB1c2godGhpcyk7XG59O1xuXG5fLnByb3RvdHlwZSA9IHtcblx0c2V0IGxpc3QobGlzdCkge1xuXHRcdGlmIChBcnJheS5pc0FycmF5KGxpc3QpKSB7XG5cdFx0XHR0aGlzLl9saXN0ID0gbGlzdDtcblx0XHR9XG5cdFx0ZWxzZSBpZiAodHlwZW9mIGxpc3QgPT09IFwic3RyaW5nXCIgJiYgbGlzdC5pbmRleE9mKFwiLFwiKSA+IC0xKSB7XG5cdFx0XHRcdHRoaXMuX2xpc3QgPSBsaXN0LnNwbGl0KC9cXHMqLFxccyovKTtcblx0XHR9XG5cdFx0ZWxzZSB7IC8vIEVsZW1lbnQgb3IgQ1NTIHNlbGVjdG9yXG5cdFx0XHRsaXN0ID0gJChsaXN0KTtcblxuXHRcdFx0aWYgKGxpc3QgJiYgbGlzdC5jaGlsZHJlbikge1xuXHRcdFx0XHR2YXIgaXRlbXMgPSBbXTtcblx0XHRcdFx0c2xpY2UuYXBwbHkobGlzdC5jaGlsZHJlbikuZm9yRWFjaChmdW5jdGlvbiAoZWwpIHtcblx0XHRcdFx0XHRpZiAoIWVsLmRpc2FibGVkKSB7XG5cdFx0XHRcdFx0XHR2YXIgdGV4dCA9IGVsLnRleHRDb250ZW50LnRyaW0oKTtcblx0XHRcdFx0XHRcdHZhciB2YWx1ZSA9IGVsLnZhbHVlIHx8IHRleHQ7XG5cdFx0XHRcdFx0XHR2YXIgbGFiZWwgPSBlbC5sYWJlbCB8fCB0ZXh0O1xuXHRcdFx0XHRcdFx0aWYgKHZhbHVlICE9PSBcIlwiKSB7XG5cdFx0XHRcdFx0XHRcdGl0ZW1zLnB1c2goeyBsYWJlbDogbGFiZWwsIHZhbHVlOiB2YWx1ZSB9KTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0pO1xuXHRcdFx0XHR0aGlzLl9saXN0ID0gaXRlbXM7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0aWYgKGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQgPT09IHRoaXMuaW5wdXQpIHtcblx0XHRcdHRoaXMuZXZhbHVhdGUoKTtcblx0XHR9XG5cdH0sXG5cblx0Z2V0IHNlbGVjdGVkKCkge1xuXHRcdHJldHVybiB0aGlzLmluZGV4ID4gLTE7XG5cdH0sXG5cblx0Z2V0IG9wZW5lZCgpIHtcblx0XHRyZXR1cm4gdGhpcy5pc09wZW5lZDtcblx0fSxcblxuXHRjbG9zZTogZnVuY3Rpb24gKG8pIHtcblx0XHRpZiAoIXRoaXMub3BlbmVkKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0dGhpcy51bC5zZXRBdHRyaWJ1dGUoXCJoaWRkZW5cIiwgXCJcIik7XG5cdFx0dGhpcy5pc09wZW5lZCA9IGZhbHNlO1xuXHRcdHRoaXMuaW5kZXggPSAtMTtcblxuXHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLWNsb3NlXCIsIG8gfHwge30pO1xuXHR9LFxuXG5cdG9wZW46IGZ1bmN0aW9uICgpIHtcblx0XHR0aGlzLnVsLnJlbW92ZUF0dHJpYnV0ZShcImhpZGRlblwiKTtcblx0XHR0aGlzLmlzT3BlbmVkID0gdHJ1ZTtcblxuXHRcdGlmICh0aGlzLmF1dG9GaXJzdCAmJiB0aGlzLmluZGV4ID09PSAtMSkge1xuXHRcdFx0dGhpcy5nb3RvKDApO1xuXHRcdH1cblxuXHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLW9wZW5cIik7XG5cdH0sXG5cblx0ZGVzdHJveTogZnVuY3Rpb24oKSB7XG5cdFx0Ly9yZW1vdmUgZXZlbnRzIGZyb20gdGhlIGlucHV0IGFuZCBpdHMgZm9ybVxuXHRcdCQudW5iaW5kKHRoaXMuaW5wdXQsIHRoaXMuX2V2ZW50cy5pbnB1dCk7XG5cdFx0JC51bmJpbmQodGhpcy5pbnB1dC5mb3JtLCB0aGlzLl9ldmVudHMuZm9ybSk7XG5cblx0XHQvL21vdmUgdGhlIGlucHV0IG91dCBvZiB0aGUgYXdlc29tcGxldGUgY29udGFpbmVyIGFuZCByZW1vdmUgdGhlIGNvbnRhaW5lciBhbmQgaXRzIGNoaWxkcmVuXG5cdFx0dmFyIHBhcmVudE5vZGUgPSB0aGlzLmNvbnRhaW5lci5wYXJlbnROb2RlO1xuXG5cdFx0cGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUodGhpcy5pbnB1dCwgdGhpcy5jb250YWluZXIpO1xuXHRcdHBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5jb250YWluZXIpO1xuXG5cdFx0Ly9yZW1vdmUgYXV0b2NvbXBsZXRlIGFuZCBhcmlhLWF1dG9jb21wbGV0ZSBhdHRyaWJ1dGVzXG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJhdXRvY29tcGxldGVcIik7XG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJhcmlhLWF1dG9jb21wbGV0ZVwiKTtcblxuXHRcdC8vcmVtb3ZlIHRoaXMgYXdlc29tZXBsZXRlIGluc3RhbmNlIGZyb20gdGhlIGdsb2JhbCBhcnJheSBvZiBpbnN0YW5jZXNcblx0XHR2YXIgaW5kZXhPZkF3ZXNvbXBsZXRlID0gXy5hbGwuaW5kZXhPZih0aGlzKTtcblxuXHRcdGlmIChpbmRleE9mQXdlc29tcGxldGUgIT09IC0xKSB7XG5cdFx0XHRfLmFsbC5zcGxpY2UoaW5kZXhPZkF3ZXNvbXBsZXRlLCAxKTtcblx0XHR9XG5cdH0sXG5cblx0bmV4dDogZnVuY3Rpb24gKCkge1xuXHRcdHZhciBjb3VudCA9IHRoaXMudWwuY2hpbGRyZW4ubGVuZ3RoO1xuXHRcdHRoaXMuZ290byh0aGlzLmluZGV4IDwgY291bnQgLSAxID8gdGhpcy5pbmRleCArIDEgOiAoY291bnQgPyAwIDogLTEpICk7XG5cdH0sXG5cblx0cHJldmlvdXM6IGZ1bmN0aW9uICgpIHtcblx0XHR2YXIgY291bnQgPSB0aGlzLnVsLmNoaWxkcmVuLmxlbmd0aDtcblx0XHR2YXIgcG9zID0gdGhpcy5pbmRleCAtIDE7XG5cblx0XHR0aGlzLmdvdG8odGhpcy5zZWxlY3RlZCAmJiBwb3MgIT09IC0xID8gcG9zIDogY291bnQgLSAxKTtcblx0fSxcblxuXHQvLyBTaG91bGQgbm90IGJlIHVzZWQsIGhpZ2hsaWdodHMgc3BlY2lmaWMgaXRlbSB3aXRob3V0IGFueSBjaGVja3MhXG5cdGdvdG86IGZ1bmN0aW9uIChpKSB7XG5cdFx0dmFyIGxpcyA9IHRoaXMudWwuY2hpbGRyZW47XG5cblx0XHRpZiAodGhpcy5zZWxlY3RlZCkge1xuXHRcdFx0bGlzW3RoaXMuaW5kZXhdLnNldEF0dHJpYnV0ZShcImFyaWEtc2VsZWN0ZWRcIiwgXCJmYWxzZVwiKTtcblx0XHR9XG5cblx0XHR0aGlzLmluZGV4ID0gaTtcblxuXHRcdGlmIChpID4gLTEgJiYgbGlzLmxlbmd0aCA+IDApIHtcblx0XHRcdGxpc1tpXS5zZXRBdHRyaWJ1dGUoXCJhcmlhLXNlbGVjdGVkXCIsIFwidHJ1ZVwiKTtcblx0XHRcdHRoaXMuc3RhdHVzLnRleHRDb250ZW50ID0gbGlzW2ldLnRleHRDb250ZW50O1xuXG5cdFx0XHQvLyBzY3JvbGwgdG8gaGlnaGxpZ2h0ZWQgZWxlbWVudCBpbiBjYXNlIHBhcmVudCdzIGhlaWdodCBpcyBmaXhlZFxuXHRcdFx0dGhpcy51bC5zY3JvbGxUb3AgPSBsaXNbaV0ub2Zmc2V0VG9wIC0gdGhpcy51bC5jbGllbnRIZWlnaHQgKyBsaXNbaV0uY2xpZW50SGVpZ2h0O1xuXG5cdFx0XHQkLmZpcmUodGhpcy5pbnB1dCwgXCJhd2Vzb21wbGV0ZS1oaWdobGlnaHRcIiwge1xuXHRcdFx0XHR0ZXh0OiB0aGlzLnN1Z2dlc3Rpb25zW3RoaXMuaW5kZXhdXG5cdFx0XHR9KTtcblx0XHR9XG5cdH0sXG5cblx0c2VsZWN0OiBmdW5jdGlvbiAoc2VsZWN0ZWQsIG9yaWdpbikge1xuXHRcdGlmIChzZWxlY3RlZCkge1xuXHRcdFx0dGhpcy5pbmRleCA9ICQuc2libGluZ0luZGV4KHNlbGVjdGVkKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0c2VsZWN0ZWQgPSB0aGlzLnVsLmNoaWxkcmVuW3RoaXMuaW5kZXhdO1xuXHRcdH1cblxuXHRcdGlmIChzZWxlY3RlZCkge1xuXHRcdFx0dmFyIHN1Z2dlc3Rpb24gPSB0aGlzLnN1Z2dlc3Rpb25zW3RoaXMuaW5kZXhdO1xuXG5cdFx0XHR2YXIgYWxsb3dlZCA9ICQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLXNlbGVjdFwiLCB7XG5cdFx0XHRcdHRleHQ6IHN1Z2dlc3Rpb24sXG5cdFx0XHRcdG9yaWdpbjogb3JpZ2luIHx8IHNlbGVjdGVkXG5cdFx0XHR9KTtcblxuXHRcdFx0aWYgKGFsbG93ZWQpIHtcblx0XHRcdFx0dGhpcy5yZXBsYWNlKHN1Z2dlc3Rpb24pO1xuXHRcdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcInNlbGVjdFwiIH0pO1xuXHRcdFx0XHQkLmZpcmUodGhpcy5pbnB1dCwgXCJhd2Vzb21wbGV0ZS1zZWxlY3Rjb21wbGV0ZVwiLCB7XG5cdFx0XHRcdFx0dGV4dDogc3VnZ2VzdGlvblxuXHRcdFx0XHR9KTtcblx0XHRcdH1cblx0XHR9XG5cdH0sXG5cblx0ZXZhbHVhdGU6IGZ1bmN0aW9uKCkge1xuXHRcdHZhciBtZSA9IHRoaXM7XG5cdFx0dmFyIHZhbHVlID0gdGhpcy5pbnB1dC52YWx1ZTtcblxuXHRcdGlmICh2YWx1ZS5sZW5ndGggPj0gdGhpcy5taW5DaGFycyAmJiB0aGlzLl9saXN0Lmxlbmd0aCA+IDApIHtcblx0XHRcdHRoaXMuaW5kZXggPSAtMTtcblx0XHRcdC8vIFBvcHVsYXRlIGxpc3Qgd2l0aCBvcHRpb25zIHRoYXQgbWF0Y2hcblx0XHRcdHRoaXMudWwuaW5uZXJIVE1MID0gXCJcIjtcblxuXHRcdFx0dGhpcy5zdWdnZXN0aW9ucyA9IHRoaXMuX2xpc3Rcblx0XHRcdFx0Lm1hcChmdW5jdGlvbihpdGVtKSB7XG5cdFx0XHRcdFx0cmV0dXJuIG5ldyBTdWdnZXN0aW9uKG1lLmRhdGEoaXRlbSwgdmFsdWUpKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmZpbHRlcihmdW5jdGlvbihpdGVtKSB7XG5cdFx0XHRcdFx0cmV0dXJuIG1lLmZpbHRlcihpdGVtLCB2YWx1ZSk7XG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRpZiAodGhpcy5zb3J0ICE9PSBmYWxzZSkge1xuXHRcdFx0XHR0aGlzLnN1Z2dlc3Rpb25zID0gdGhpcy5zdWdnZXN0aW9ucy5zb3J0KHRoaXMuc29ydCk7XG5cdFx0XHR9XG5cblx0XHRcdHRoaXMuc3VnZ2VzdGlvbnMgPSB0aGlzLnN1Z2dlc3Rpb25zLnNsaWNlKDAsIHRoaXMubWF4SXRlbXMpO1xuXG5cdFx0XHR0aGlzLnN1Z2dlc3Rpb25zLmZvckVhY2goZnVuY3Rpb24odGV4dCkge1xuXHRcdFx0XHRcdG1lLnVsLmFwcGVuZENoaWxkKG1lLml0ZW0odGV4dCwgdmFsdWUpKTtcblx0XHRcdFx0fSk7XG5cblx0XHRcdGlmICh0aGlzLnVsLmNoaWxkcmVuLmxlbmd0aCA9PT0gMCkge1xuXHRcdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcIm5vbWF0Y2hlc1wiIH0pO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0dGhpcy5vcGVuKCk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0dGhpcy5jbG9zZSh7IHJlYXNvbjogXCJub21hdGNoZXNcIiB9KTtcblx0XHR9XG5cdH1cbn07XG5cbi8vIFN0YXRpYyBtZXRob2RzL3Byb3BlcnRpZXNcblxuXy5hbGwgPSBbXTtcblxuXy5GSUxURVJfQ09OVEFJTlMgPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0cmV0dXJuIFJlZ0V4cCgkLnJlZ0V4cEVzY2FwZShpbnB1dC50cmltKCkpLCBcImlcIikudGVzdCh0ZXh0KTtcbn07XG5cbl8uRklMVEVSX1NUQVJUU1dJVEggPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0cmV0dXJuIFJlZ0V4cChcIl5cIiArICQucmVnRXhwRXNjYXBlKGlucHV0LnRyaW0oKSksIFwiaVwiKS50ZXN0KHRleHQpO1xufTtcblxuXy5TT1JUX0JZTEVOR1RIID0gZnVuY3Rpb24gKGEsIGIpIHtcblx0aWYgKGEubGVuZ3RoICE9PSBiLmxlbmd0aCkge1xuXHRcdHJldHVybiBhLmxlbmd0aCAtIGIubGVuZ3RoO1xuXHR9XG5cblx0cmV0dXJuIGEgPCBiPyAtMSA6IDE7XG59O1xuXG5fLklURU0gPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0dmFyIGh0bWwgPSBpbnB1dC50cmltKCkgPT09IFwiXCIgPyB0ZXh0IDogdGV4dC5yZXBsYWNlKFJlZ0V4cCgkLnJlZ0V4cEVzY2FwZShpbnB1dC50cmltKCkpLCBcImdpXCIpLCBcIjxtYXJrPiQmPC9tYXJrPlwiKTtcblx0cmV0dXJuICQuY3JlYXRlKFwibGlcIiwge1xuXHRcdGlubmVySFRNTDogaHRtbCxcblx0XHRcImFyaWEtc2VsZWN0ZWRcIjogXCJmYWxzZVwiXG5cdH0pO1xufTtcblxuXy5SRVBMQUNFID0gZnVuY3Rpb24gKHRleHQpIHtcblx0dGhpcy5pbnB1dC52YWx1ZSA9IHRleHQudmFsdWU7XG59O1xuXG5fLkRBVEEgPSBmdW5jdGlvbiAoaXRlbS8qLCBpbnB1dCovKSB7IHJldHVybiBpdGVtOyB9O1xuXG4vLyBQcml2YXRlIGZ1bmN0aW9uc1xuXG5mdW5jdGlvbiBTdWdnZXN0aW9uKGRhdGEpIHtcblx0dmFyIG8gPSBBcnJheS5pc0FycmF5KGRhdGEpXG5cdCAgPyB7IGxhYmVsOiBkYXRhWzBdLCB2YWx1ZTogZGF0YVsxXSB9XG5cdCAgOiB0eXBlb2YgZGF0YSA9PT0gXCJvYmplY3RcIiAmJiBcImxhYmVsXCIgaW4gZGF0YSAmJiBcInZhbHVlXCIgaW4gZGF0YSA/IGRhdGEgOiB7IGxhYmVsOiBkYXRhLCB2YWx1ZTogZGF0YSB9O1xuXG5cdHRoaXMubGFiZWwgPSBvLmxhYmVsIHx8IG8udmFsdWU7XG5cdHRoaXMudmFsdWUgPSBvLnZhbHVlO1xufVxuT2JqZWN0LmRlZmluZVByb3BlcnR5KFN1Z2dlc3Rpb24ucHJvdG90eXBlID0gT2JqZWN0LmNyZWF0ZShTdHJpbmcucHJvdG90eXBlKSwgXCJsZW5ndGhcIiwge1xuXHRnZXQ6IGZ1bmN0aW9uKCkgeyByZXR1cm4gdGhpcy5sYWJlbC5sZW5ndGg7IH1cbn0pO1xuU3VnZ2VzdGlvbi5wcm90b3R5cGUudG9TdHJpbmcgPSBTdWdnZXN0aW9uLnByb3RvdHlwZS52YWx1ZU9mID0gZnVuY3Rpb24gKCkge1xuXHRyZXR1cm4gXCJcIiArIHRoaXMubGFiZWw7XG59O1xuXG5mdW5jdGlvbiBjb25maWd1cmUoaW5zdGFuY2UsIHByb3BlcnRpZXMsIG8pIHtcblx0Zm9yICh2YXIgaSBpbiBwcm9wZXJ0aWVzKSB7XG5cdFx0dmFyIGluaXRpYWwgPSBwcm9wZXJ0aWVzW2ldLFxuXHRcdCAgICBhdHRyVmFsdWUgPSBpbnN0YW5jZS5pbnB1dC5nZXRBdHRyaWJ1dGUoXCJkYXRhLVwiICsgaS50b0xvd2VyQ2FzZSgpKTtcblxuXHRcdGlmICh0eXBlb2YgaW5pdGlhbCA9PT0gXCJudW1iZXJcIikge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBwYXJzZUludChhdHRyVmFsdWUpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpbml0aWFsID09PSBmYWxzZSkgeyAvLyBCb29sZWFuIG9wdGlvbnMgbXVzdCBiZSBmYWxzZSBieSBkZWZhdWx0IGFueXdheVxuXHRcdFx0aW5zdGFuY2VbaV0gPSBhdHRyVmFsdWUgIT09IG51bGw7XG5cdFx0fVxuXHRcdGVsc2UgaWYgKGluaXRpYWwgaW5zdGFuY2VvZiBGdW5jdGlvbikge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBudWxsO1xuXHRcdH1cblx0XHRlbHNlIHtcblx0XHRcdGluc3RhbmNlW2ldID0gYXR0clZhbHVlO1xuXHRcdH1cblxuXHRcdGlmICghaW5zdGFuY2VbaV0gJiYgaW5zdGFuY2VbaV0gIT09IDApIHtcblx0XHRcdGluc3RhbmNlW2ldID0gKGkgaW4gbyk/IG9baV0gOiBpbml0aWFsO1xuXHRcdH1cblx0fVxufVxuXG4vLyBIZWxwZXJzXG5cbnZhciBzbGljZSA9IEFycmF5LnByb3RvdHlwZS5zbGljZTtcblxuZnVuY3Rpb24gJChleHByLCBjb24pIHtcblx0cmV0dXJuIHR5cGVvZiBleHByID09PSBcInN0cmluZ1wiPyAoY29uIHx8IGRvY3VtZW50KS5xdWVyeVNlbGVjdG9yKGV4cHIpIDogZXhwciB8fCBudWxsO1xufVxuXG5mdW5jdGlvbiAkJChleHByLCBjb24pIHtcblx0cmV0dXJuIHNsaWNlLmNhbGwoKGNvbiB8fCBkb2N1bWVudCkucXVlcnlTZWxlY3RvckFsbChleHByKSk7XG59XG5cbiQuY3JlYXRlID0gZnVuY3Rpb24odGFnLCBvKSB7XG5cdHZhciBlbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCh0YWcpO1xuXG5cdGZvciAodmFyIGkgaW4gbykge1xuXHRcdHZhciB2YWwgPSBvW2ldO1xuXG5cdFx0aWYgKGkgPT09IFwiaW5zaWRlXCIpIHtcblx0XHRcdCQodmFsKS5hcHBlbmRDaGlsZChlbGVtZW50KTtcblx0XHR9XG5cdFx0ZWxzZSBpZiAoaSA9PT0gXCJhcm91bmRcIikge1xuXHRcdFx0dmFyIHJlZiA9ICQodmFsKTtcblx0XHRcdHJlZi5wYXJlbnROb2RlLmluc2VydEJlZm9yZShlbGVtZW50LCByZWYpO1xuXHRcdFx0ZWxlbWVudC5hcHBlbmRDaGlsZChyZWYpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpIGluIGVsZW1lbnQpIHtcblx0XHRcdGVsZW1lbnRbaV0gPSB2YWw7XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0ZWxlbWVudC5zZXRBdHRyaWJ1dGUoaSwgdmFsKTtcblx0XHR9XG5cdH1cblxuXHRyZXR1cm4gZWxlbWVudDtcbn07XG5cbiQuYmluZCA9IGZ1bmN0aW9uKGVsZW1lbnQsIG8pIHtcblx0aWYgKGVsZW1lbnQpIHtcblx0XHRmb3IgKHZhciBldmVudCBpbiBvKSB7XG5cdFx0XHR2YXIgY2FsbGJhY2sgPSBvW2V2ZW50XTtcblxuXHRcdFx0ZXZlbnQuc3BsaXQoL1xccysvKS5mb3JFYWNoKGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0XHRlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoZXZlbnQsIGNhbGxiYWNrKTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxufTtcblxuJC51bmJpbmQgPSBmdW5jdGlvbihlbGVtZW50LCBvKSB7XG5cdGlmIChlbGVtZW50KSB7XG5cdFx0Zm9yICh2YXIgZXZlbnQgaW4gbykge1xuXHRcdFx0dmFyIGNhbGxiYWNrID0gb1tldmVudF07XG5cblx0XHRcdGV2ZW50LnNwbGl0KC9cXHMrLykuZm9yRWFjaChmdW5jdGlvbihldmVudCkge1xuXHRcdFx0XHRlbGVtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoZXZlbnQsIGNhbGxiYWNrKTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxufTtcblxuJC5maXJlID0gZnVuY3Rpb24odGFyZ2V0LCB0eXBlLCBwcm9wZXJ0aWVzKSB7XG5cdHZhciBldnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudChcIkhUTUxFdmVudHNcIik7XG5cblx0ZXZ0LmluaXRFdmVudCh0eXBlLCB0cnVlLCB0cnVlICk7XG5cblx0Zm9yICh2YXIgaiBpbiBwcm9wZXJ0aWVzKSB7XG5cdFx0ZXZ0W2pdID0gcHJvcGVydGllc1tqXTtcblx0fVxuXG5cdHJldHVybiB0YXJnZXQuZGlzcGF0Y2hFdmVudChldnQpO1xufTtcblxuJC5yZWdFeHBFc2NhcGUgPSBmdW5jdGlvbiAocykge1xuXHRyZXR1cm4gcy5yZXBsYWNlKC9bLVxcXFxeJCorPy4oKXxbXFxde31dL2csIFwiXFxcXCQmXCIpO1xufTtcblxuJC5zaWJsaW5nSW5kZXggPSBmdW5jdGlvbiAoZWwpIHtcblx0LyogZXNsaW50LWRpc2FibGUgbm8tY29uZC1hc3NpZ24gKi9cblx0Zm9yICh2YXIgaSA9IDA7IGVsID0gZWwucHJldmlvdXNFbGVtZW50U2libGluZzsgaSsrKTtcblx0cmV0dXJuIGk7XG59O1xuXG4vLyBJbml0aWFsaXphdGlvblxuXG5mdW5jdGlvbiBpbml0KCkge1xuXHQkJChcImlucHV0LmF3ZXNvbXBsZXRlXCIpLmZvckVhY2goZnVuY3Rpb24gKGlucHV0KSB7XG5cdFx0bmV3IF8oaW5wdXQpO1xuXHR9KTtcbn1cblxuLy8gQXJlIHdlIGluIGEgYnJvd3Nlcj8gQ2hlY2sgZm9yIERvY3VtZW50IGNvbnN0cnVjdG9yXG5pZiAodHlwZW9mIERvY3VtZW50ICE9PSBcInVuZGVmaW5lZFwiKSB7XG5cdC8vIERPTSBhbHJlYWR5IGxvYWRlZD9cblx0aWYgKGRvY3VtZW50LnJlYWR5U3RhdGUgIT09IFwibG9hZGluZ1wiKSB7XG5cdFx0aW5pdCgpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdC8vIFdhaXQgZm9yIGl0XG5cdFx0ZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcIkRPTUNvbnRlbnRMb2FkZWRcIiwgaW5pdCk7XG5cdH1cbn1cblxuXy4kID0gJDtcbl8uJCQgPSAkJDtcblxuLy8gTWFrZSBzdXJlIHRvIGV4cG9ydCBBd2Vzb21wbGV0ZSBvbiBzZWxmIHdoZW4gaW4gYSBicm93c2VyXG5pZiAodHlwZW9mIHNlbGYgIT09IFwidW5kZWZpbmVkXCIpIHtcblx0c2VsZi5Bd2Vzb21wbGV0ZSA9IF87XG59XG5cbi8vIEV4cG9zZSBBd2Vzb21wbGV0ZSBhcyBhIENKUyBtb2R1bGVcbmlmICh0eXBlb2YgbW9kdWxlID09PSBcIm9iamVjdFwiICYmIG1vZHVsZS5leHBvcnRzKSB7XG5cdG1vZHVsZS5leHBvcnRzID0gXztcbn1cblxucmV0dXJuIF87XG5cbn0oKSk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9hd2Vzb21wbGV0ZS9hd2Vzb21wbGV0ZS5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvYXdlc29tcGxldGUvYXdlc29tcGxldGUuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHYyLjAuMjMgfCDCqSBkbnBfdGhlbWUgfCBNSVQtTGljZW5zZVxuKGZ1bmN0aW9uIChyb290LCBmYWN0b3J5KSB7XG4gIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICAvLyBBTUQgc3VwcG9ydDpcbiAgICBkZWZpbmUoW10sIGZhY3RvcnkpO1xuICB9IGVsc2UgaWYgKHR5cGVvZiBtb2R1bGUgPT09ICdvYmplY3QnICYmIG1vZHVsZS5leHBvcnRzKSB7XG4gICAgLy8gQ29tbW9uSlMtbGlrZTpcbiAgICBtb2R1bGUuZXhwb3J0cyA9IGZhY3RvcnkoKTtcbiAgfSBlbHNlIHtcbiAgICAvLyBCcm93c2VyIGdsb2JhbHMgKHJvb3QgaXMgd2luZG93KVxuICAgIHZhciBic24gPSBmYWN0b3J5KCk7XG4gICAgcm9vdC5Nb2RhbCA9IGJzbi5Nb2RhbDtcbiAgICByb290LkNvbGxhcHNlID0gYnNuLkNvbGxhcHNlO1xuICAgIHJvb3QuQWxlcnQgPSBic24uQWxlcnQ7XG4gIH1cbn0odGhpcywgZnVuY3Rpb24gKCkge1xuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgSW50ZXJuYWwgVXRpbGl0eSBGdW5jdGlvbnNcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFwidXNlIHN0cmljdFwiO1xuICBcbiAgLy8gZ2xvYmFsc1xuICB2YXIgZ2xvYmFsT2JqZWN0ID0gdHlwZW9mIGdsb2JhbCAhPT0gJ3VuZGVmaW5lZCcgPyBnbG9iYWwgOiB0aGlzfHx3aW5kb3csXG4gICAgRE9DID0gZG9jdW1lbnQsIEhUTUwgPSBET0MuZG9jdW1lbnRFbGVtZW50LCBib2R5ID0gJ2JvZHknLCAvLyBhbGxvdyB0aGUgbGlicmFyeSB0byBiZSB1c2VkIGluIDxoZWFkPlxuICBcbiAgICAvLyBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIEdsb2JhbCBPYmplY3RcbiAgICBCU04gPSBnbG9iYWxPYmplY3QuQlNOID0ge30sXG4gICAgc3VwcG9ydHMgPSBCU04uc3VwcG9ydHMgPSBbXSxcbiAgXG4gICAgLy8gZnVuY3Rpb24gdG9nZ2xlIGF0dHJpYnV0ZXNcbiAgICBkYXRhVG9nZ2xlICAgID0gJ2RhdGEtdG9nZ2xlJyxcbiAgICBkYXRhRGlzbWlzcyAgID0gJ2RhdGEtZGlzbWlzcycsXG4gICAgZGF0YVNweSAgICAgICA9ICdkYXRhLXNweScsXG4gICAgZGF0YVJpZGUgICAgICA9ICdkYXRhLXJpZGUnLFxuICAgIFxuICAgIC8vIGNvbXBvbmVudHNcbiAgICBzdHJpbmdBZmZpeCAgICAgPSAnQWZmaXgnLFxuICAgIHN0cmluZ0FsZXJ0ICAgICA9ICdBbGVydCcsXG4gICAgc3RyaW5nQnV0dG9uICAgID0gJ0J1dHRvbicsXG4gICAgc3RyaW5nQ2Fyb3VzZWwgID0gJ0Nhcm91c2VsJyxcbiAgICBzdHJpbmdDb2xsYXBzZSAgPSAnQ29sbGFwc2UnLFxuICAgIHN0cmluZ0Ryb3Bkb3duICA9ICdEcm9wZG93bicsXG4gICAgc3RyaW5nTW9kYWwgICAgID0gJ01vZGFsJyxcbiAgICBzdHJpbmdQb3BvdmVyICAgPSAnUG9wb3ZlcicsXG4gICAgc3RyaW5nU2Nyb2xsU3B5ID0gJ1Njcm9sbFNweScsXG4gICAgc3RyaW5nVGFiICAgICAgID0gJ1RhYicsXG4gICAgc3RyaW5nVG9vbHRpcCAgID0gJ1Rvb2x0aXAnLFxuICBcbiAgICAvLyBvcHRpb25zIERBVEEgQVBJXG4gICAgZGF0YWJhY2tkcm9wICAgICAgPSAnZGF0YS1iYWNrZHJvcCcsXG4gICAgZGF0YUtleWJvYXJkICAgICAgPSAnZGF0YS1rZXlib2FyZCcsXG4gICAgZGF0YVRhcmdldCAgICAgICAgPSAnZGF0YS10YXJnZXQnLFxuICAgIGRhdGFJbnRlcnZhbCAgICAgID0gJ2RhdGEtaW50ZXJ2YWwnLFxuICAgIGRhdGFIZWlnaHQgICAgICAgID0gJ2RhdGEtaGVpZ2h0JyxcbiAgICBkYXRhUGF1c2UgICAgICAgICA9ICdkYXRhLXBhdXNlJyxcbiAgICBkYXRhVGl0bGUgICAgICAgICA9ICdkYXRhLXRpdGxlJywgIFxuICAgIGRhdGFPcmlnaW5hbFRpdGxlID0gJ2RhdGEtb3JpZ2luYWwtdGl0bGUnLFxuICAgIGRhdGFPcmlnaW5hbFRleHQgID0gJ2RhdGEtb3JpZ2luYWwtdGV4dCcsXG4gICAgZGF0YURpc21pc3NpYmxlICAgPSAnZGF0YS1kaXNtaXNzaWJsZScsXG4gICAgZGF0YVRyaWdnZXIgICAgICAgPSAnZGF0YS10cmlnZ2VyJyxcbiAgICBkYXRhQW5pbWF0aW9uICAgICA9ICdkYXRhLWFuaW1hdGlvbicsXG4gICAgZGF0YUNvbnRhaW5lciAgICAgPSAnZGF0YS1jb250YWluZXInLFxuICAgIGRhdGFQbGFjZW1lbnQgICAgID0gJ2RhdGEtcGxhY2VtZW50JyxcbiAgICBkYXRhRGVsYXkgICAgICAgICA9ICdkYXRhLWRlbGF5JyxcbiAgICBkYXRhT2Zmc2V0VG9wICAgICA9ICdkYXRhLW9mZnNldC10b3AnLFxuICAgIGRhdGFPZmZzZXRCb3R0b20gID0gJ2RhdGEtb2Zmc2V0LWJvdHRvbScsXG4gIFxuICAgIC8vIG9wdGlvbiBrZXlzXG4gICAgYmFja2Ryb3AgPSAnYmFja2Ryb3AnLCBrZXlib2FyZCA9ICdrZXlib2FyZCcsIGRlbGF5ID0gJ2RlbGF5JyxcbiAgICBjb250ZW50ID0gJ2NvbnRlbnQnLCB0YXJnZXQgPSAndGFyZ2V0JywgXG4gICAgaW50ZXJ2YWwgPSAnaW50ZXJ2YWwnLCBwYXVzZSA9ICdwYXVzZScsIGFuaW1hdGlvbiA9ICdhbmltYXRpb24nLFxuICAgIHBsYWNlbWVudCA9ICdwbGFjZW1lbnQnLCBjb250YWluZXIgPSAnY29udGFpbmVyJywgXG4gIFxuICAgIC8vIGJveCBtb2RlbFxuICAgIG9mZnNldFRvcCAgICA9ICdvZmZzZXRUb3AnLCAgICAgIG9mZnNldEJvdHRvbSAgID0gJ29mZnNldEJvdHRvbScsXG4gICAgb2Zmc2V0TGVmdCAgID0gJ29mZnNldExlZnQnLFxuICAgIHNjcm9sbFRvcCAgICA9ICdzY3JvbGxUb3AnLCAgICAgIHNjcm9sbExlZnQgICAgID0gJ3Njcm9sbExlZnQnLFxuICAgIGNsaWVudFdpZHRoICA9ICdjbGllbnRXaWR0aCcsICAgIGNsaWVudEhlaWdodCAgID0gJ2NsaWVudEhlaWdodCcsXG4gICAgb2Zmc2V0V2lkdGggID0gJ29mZnNldFdpZHRoJywgICAgb2Zmc2V0SGVpZ2h0ICAgPSAnb2Zmc2V0SGVpZ2h0JyxcbiAgICBpbm5lcldpZHRoICAgPSAnaW5uZXJXaWR0aCcsICAgICBpbm5lckhlaWdodCAgICA9ICdpbm5lckhlaWdodCcsXG4gICAgc2Nyb2xsSGVpZ2h0ID0gJ3Njcm9sbEhlaWdodCcsICAgaGVpZ2h0ICAgICAgICAgPSAnaGVpZ2h0JyxcbiAgXG4gICAgLy8gYXJpYVxuICAgIGFyaWFFeHBhbmRlZCA9ICdhcmlhLWV4cGFuZGVkJyxcbiAgICBhcmlhSGlkZGVuICAgPSAnYXJpYS1oaWRkZW4nLFxuICBcbiAgICAvLyBldmVudCBuYW1lc1xuICAgIGNsaWNrRXZlbnQgICAgPSAnY2xpY2snLFxuICAgIGhvdmVyRXZlbnQgICAgPSAnaG92ZXInLFxuICAgIGtleWRvd25FdmVudCAgPSAna2V5ZG93bicsXG4gICAga2V5dXBFdmVudCAgICA9ICdrZXl1cCcsICBcbiAgICByZXNpemVFdmVudCAgID0gJ3Jlc2l6ZScsXG4gICAgc2Nyb2xsRXZlbnQgICA9ICdzY3JvbGwnLFxuICAgIC8vIG9yaWdpbmFsRXZlbnRzXG4gICAgc2hvd0V2ZW50ICAgICA9ICdzaG93JyxcbiAgICBzaG93bkV2ZW50ICAgID0gJ3Nob3duJyxcbiAgICBoaWRlRXZlbnQgICAgID0gJ2hpZGUnLFxuICAgIGhpZGRlbkV2ZW50ICAgPSAnaGlkZGVuJyxcbiAgICBjbG9zZUV2ZW50ICAgID0gJ2Nsb3NlJyxcbiAgICBjbG9zZWRFdmVudCAgID0gJ2Nsb3NlZCcsXG4gICAgc2xpZEV2ZW50ICAgICA9ICdzbGlkJyxcbiAgICBzbGlkZUV2ZW50ICAgID0gJ3NsaWRlJyxcbiAgICBjaGFuZ2VFdmVudCAgID0gJ2NoYW5nZScsXG4gIFxuICAgIC8vIG90aGVyXG4gICAgZ2V0QXR0cmlidXRlICAgICAgICAgICA9ICdnZXRBdHRyaWJ1dGUnLFxuICAgIHNldEF0dHJpYnV0ZSAgICAgICAgICAgPSAnc2V0QXR0cmlidXRlJyxcbiAgICBoYXNBdHRyaWJ1dGUgICAgICAgICAgID0gJ2hhc0F0dHJpYnV0ZScsXG4gICAgY3JlYXRlRWxlbWVudCAgICAgICAgICA9ICdjcmVhdGVFbGVtZW50JyxcbiAgICBhcHBlbmRDaGlsZCAgICAgICAgICAgID0gJ2FwcGVuZENoaWxkJyxcbiAgICBpbm5lckhUTUwgICAgICAgICAgICAgID0gJ2lubmVySFRNTCcsXG4gICAgZ2V0RWxlbWVudHNCeVRhZ05hbWUgICA9ICdnZXRFbGVtZW50c0J5VGFnTmFtZScsXG4gICAgcHJldmVudERlZmF1bHQgICAgICAgICA9ICdwcmV2ZW50RGVmYXVsdCcsXG4gICAgZ2V0Qm91bmRpbmdDbGllbnRSZWN0ICA9ICdnZXRCb3VuZGluZ0NsaWVudFJlY3QnLFxuICAgIHF1ZXJ5U2VsZWN0b3JBbGwgICAgICAgPSAncXVlcnlTZWxlY3RvckFsbCcsXG4gICAgZ2V0RWxlbWVudHNCeUNMQVNTTkFNRSA9ICdnZXRFbGVtZW50c0J5Q2xhc3NOYW1lJyxcbiAgICBnZXRDb21wdXRlZFN0eWxlICAgICAgID0gJ2dldENvbXB1dGVkU3R5bGUnLCAgXG4gIFxuICAgIGluZGV4T2YgICAgICA9ICdpbmRleE9mJyxcbiAgICBwYXJlbnROb2RlICAgPSAncGFyZW50Tm9kZScsXG4gICAgbGVuZ3RoICAgICAgID0gJ2xlbmd0aCcsXG4gICAgdG9Mb3dlckNhc2UgID0gJ3RvTG93ZXJDYXNlJyxcbiAgICBUcmFuc2l0aW9uICAgPSAnVHJhbnNpdGlvbicsXG4gICAgRHVyYXRpb24gICAgID0gJ0R1cmF0aW9uJywgIFxuICAgIFdlYmtpdCAgICAgICA9ICdXZWJraXQnLFxuICAgIHN0eWxlICAgICAgICA9ICdzdHlsZScsXG4gICAgcHVzaCAgICAgICAgID0gJ3B1c2gnLFxuICAgIHRhYmluZGV4ICAgICA9ICd0YWJpbmRleCcsXG4gICAgY29udGFpbnMgICAgID0gJ2NvbnRhaW5zJywgIFxuICAgIFxuICAgIGFjdGl2ZSAgICAgPSAnYWN0aXZlJyxcbiAgICBpbkNsYXNzICAgID0gJ2luJyxcbiAgICBjb2xsYXBzaW5nID0gJ2NvbGxhcHNpbmcnLFxuICAgIGRpc2FibGVkICAgPSAnZGlzYWJsZWQnLFxuICAgIGxvYWRpbmcgICAgPSAnbG9hZGluZycsXG4gICAgbGVmdCAgICAgICA9ICdsZWZ0JyxcbiAgICByaWdodCAgICAgID0gJ3JpZ2h0JyxcbiAgICB0b3AgICAgICAgID0gJ3RvcCcsXG4gICAgYm90dG9tICAgICA9ICdib3R0b20nLFxuICBcbiAgICAvLyBJRTggYnJvd3NlciBkZXRlY3RcbiAgICBpc0lFOCA9ICEoJ29wYWNpdHknIGluIEhUTUxbc3R5bGVdKSxcbiAgXG4gICAgLy8gdG9vbHRpcCAvIHBvcG92ZXJcbiAgICBtb3VzZUhvdmVyID0gKCdvbm1vdXNlbGVhdmUnIGluIERPQykgPyBbICdtb3VzZWVudGVyJywgJ21vdXNlbGVhdmUnXSA6IFsgJ21vdXNlb3ZlcicsICdtb3VzZW91dCcgXSxcbiAgICB0aXBQb3NpdGlvbnMgPSAvXFxiKHRvcHxib3R0b218bGVmdHxyaWdodCkrLyxcbiAgICBcbiAgICAvLyBtb2RhbFxuICAgIG1vZGFsT3ZlcmxheSA9IDAsXG4gICAgZml4ZWRUb3AgPSAnbmF2YmFyLWZpeGVkLXRvcCcsXG4gICAgZml4ZWRCb3R0b20gPSAnbmF2YmFyLWZpeGVkLWJvdHRvbScsICBcbiAgICBcbiAgICAvLyB0cmFuc2l0aW9uRW5kIHNpbmNlIDIuMC40XG4gICAgc3VwcG9ydFRyYW5zaXRpb25zID0gV2Via2l0K1RyYW5zaXRpb24gaW4gSFRNTFtzdHlsZV0gfHwgVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKSBpbiBIVE1MW3N0eWxlXSxcbiAgICB0cmFuc2l0aW9uRW5kRXZlbnQgPSBXZWJraXQrVHJhbnNpdGlvbiBpbiBIVE1MW3N0eWxlXSA/IFdlYmtpdFt0b0xvd2VyQ2FzZV0oKStUcmFuc2l0aW9uKydFbmQnIDogVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKSsnZW5kJyxcbiAgICB0cmFuc2l0aW9uRHVyYXRpb24gPSBXZWJraXQrRHVyYXRpb24gaW4gSFRNTFtzdHlsZV0gPyBXZWJraXRbdG9Mb3dlckNhc2VdKCkrVHJhbnNpdGlvbitEdXJhdGlvbiA6IFRyYW5zaXRpb25bdG9Mb3dlckNhc2VdKCkrRHVyYXRpb24sXG4gIFxuICAgIC8vIHNldCBuZXcgZm9jdXMgZWxlbWVudCBzaW5jZSAyLjAuM1xuICAgIHNldEZvY3VzID0gZnVuY3Rpb24oZWxlbWVudCl7XG4gICAgICBlbGVtZW50LmZvY3VzID8gZWxlbWVudC5mb2N1cygpIDogZWxlbWVudC5zZXRBY3RpdmUoKTtcbiAgICB9LFxuICBcbiAgICAvLyBjbGFzcyBtYW5pcHVsYXRpb24sIHNpbmNlIDIuMC4wIHJlcXVpcmVzIHBvbHlmaWxsLmpzXG4gICAgYWRkQ2xhc3MgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSkge1xuICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKGNsYXNzTkFNRSk7XG4gICAgfSxcbiAgICByZW1vdmVDbGFzcyA9IGZ1bmN0aW9uKGVsZW1lbnQsY2xhc3NOQU1FKSB7XG4gICAgICBlbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOQU1FKTtcbiAgICB9LFxuICAgIGhhc0NsYXNzID0gZnVuY3Rpb24oZWxlbWVudCxjbGFzc05BTUUpeyAvLyBzaW5jZSAyLjAuMFxuICAgICAgcmV0dXJuIGVsZW1lbnQuY2xhc3NMaXN0W2NvbnRhaW5zXShjbGFzc05BTUUpO1xuICAgIH0sXG4gIFxuICAgIC8vIHNlbGVjdGlvbiBtZXRob2RzXG4gICAgbm9kZUxpc3RUb0FycmF5ID0gZnVuY3Rpb24obm9kZUxpc3Qpe1xuICAgICAgdmFyIGNoaWxkSXRlbXMgPSBbXTsgZm9yICh2YXIgaSA9IDAsIG5sbCA9IG5vZGVMaXN0W2xlbmd0aF07IGk8bmxsOyBpKyspIHsgY2hpbGRJdGVtc1twdXNoXSggbm9kZUxpc3RbaV0gKSB9XG4gICAgICByZXR1cm4gY2hpbGRJdGVtcztcbiAgICB9LFxuICAgIGdldEVsZW1lbnRzQnlDbGFzc05hbWUgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSkgeyAvLyBnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIElFOCtcbiAgICAgIHZhciBzZWxlY3Rpb25NZXRob2QgPSBpc0lFOCA/IHF1ZXJ5U2VsZWN0b3JBbGwgOiBnZXRFbGVtZW50c0J5Q0xBU1NOQU1FOyAgICAgIFxuICAgICAgcmV0dXJuIG5vZGVMaXN0VG9BcnJheShlbGVtZW50W3NlbGVjdGlvbk1ldGhvZF0oIGlzSUU4ID8gJy4nICsgY2xhc3NOQU1FLnJlcGxhY2UoL1xccyg/PVthLXpdKS9nLCcuJykgOiBjbGFzc05BTUUgKSk7XG4gICAgfSxcbiAgICBxdWVyeUVsZW1lbnQgPSBmdW5jdGlvbiAoc2VsZWN0b3IsIHBhcmVudCkge1xuICAgICAgdmFyIGxvb2tVcCA9IHBhcmVudCA/IHBhcmVudCA6IERPQztcbiAgICAgIHJldHVybiB0eXBlb2Ygc2VsZWN0b3IgPT09ICdvYmplY3QnID8gc2VsZWN0b3IgOiBsb29rVXAucXVlcnlTZWxlY3RvcihzZWxlY3Rvcik7XG4gICAgfSxcbiAgICBnZXRDbG9zZXN0ID0gZnVuY3Rpb24gKGVsZW1lbnQsIHNlbGVjdG9yKSB7IC8vZWxlbWVudCBpcyB0aGUgZWxlbWVudCBhbmQgc2VsZWN0b3IgaXMgZm9yIHRoZSBjbG9zZXN0IHBhcmVudCBlbGVtZW50IHRvIGZpbmRcbiAgICAgIC8vIHNvdXJjZSBodHRwOi8vZ29tYWtldGhpbmdzLmNvbS9jbGltYmluZy11cC1hbmQtZG93bi10aGUtZG9tLXRyZWUtd2l0aC12YW5pbGxhLWphdmFzY3JpcHQvXG4gICAgICB2YXIgZmlyc3RDaGFyID0gc2VsZWN0b3IuY2hhckF0KDApLCBzZWxlY3RvclN1YnN0cmluZyA9IHNlbGVjdG9yLnN1YnN0cigxKTtcbiAgICAgIGlmICggZmlyc3RDaGFyID09PSAnLicgKSB7Ly8gSWYgc2VsZWN0b3IgaXMgYSBjbGFzc1xuICAgICAgICBmb3IgKCA7IGVsZW1lbnQgJiYgZWxlbWVudCAhPT0gRE9DOyBlbGVtZW50ID0gZWxlbWVudFtwYXJlbnROb2RlXSApIHsgLy8gR2V0IGNsb3Nlc3QgbWF0Y2hcbiAgICAgICAgICBpZiAoIHF1ZXJ5RWxlbWVudChzZWxlY3RvcixlbGVtZW50W3BhcmVudE5vZGVdKSAhPT0gbnVsbCAmJiBoYXNDbGFzcyhlbGVtZW50LHNlbGVjdG9yU3Vic3RyaW5nKSApIHsgcmV0dXJuIGVsZW1lbnQ7IH1cbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmICggZmlyc3RDaGFyID09PSAnIycgKSB7IC8vIElmIHNlbGVjdG9yIGlzIGFuIElEXG4gICAgICAgIGZvciAoIDsgZWxlbWVudCAmJiBlbGVtZW50ICE9PSBET0M7IGVsZW1lbnQgPSBlbGVtZW50W3BhcmVudE5vZGVdICkgeyAvLyBHZXQgY2xvc2VzdCBtYXRjaFxuICAgICAgICAgIGlmICggZWxlbWVudC5pZCA9PT0gc2VsZWN0b3JTdWJzdHJpbmcgKSB7IHJldHVybiBlbGVtZW50OyB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICBcbiAgICAvLyBldmVudCBhdHRhY2ggalF1ZXJ5IHN0eWxlIC8gdHJpZ2dlciAgc2luY2UgMS4yLjBcbiAgICBvbiA9IGZ1bmN0aW9uIChlbGVtZW50LCBldmVudCwgaGFuZGxlcikge1xuICAgICAgZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyLCBmYWxzZSk7XG4gICAgfSxcbiAgICBvZmYgPSBmdW5jdGlvbihlbGVtZW50LCBldmVudCwgaGFuZGxlcikge1xuICAgICAgZWxlbWVudC5yZW1vdmVFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyLCBmYWxzZSk7XG4gICAgfSxcbiAgICBvbmUgPSBmdW5jdGlvbiAoZWxlbWVudCwgZXZlbnQsIGhhbmRsZXIpIHsgLy8gb25lIHNpbmNlIDIuMC40XG4gICAgICBvbihlbGVtZW50LCBldmVudCwgZnVuY3Rpb24gaGFuZGxlcldyYXBwZXIoZSl7XG4gICAgICAgIGhhbmRsZXIoZSk7XG4gICAgICAgIG9mZihlbGVtZW50LCBldmVudCwgaGFuZGxlcldyYXBwZXIpO1xuICAgICAgfSk7XG4gICAgfSxcbiAgICBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudCA9IGZ1bmN0aW9uKGVsZW1lbnQpIHtcbiAgICAgIHZhciBkdXJhdGlvbiA9IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShlbGVtZW50KVt0cmFuc2l0aW9uRHVyYXRpb25dO1xuICAgICAgZHVyYXRpb24gPSBwYXJzZUZsb2F0KGR1cmF0aW9uKTtcbiAgICAgIGR1cmF0aW9uID0gdHlwZW9mIGR1cmF0aW9uID09PSAnbnVtYmVyJyAmJiAhaXNOYU4oZHVyYXRpb24pID8gZHVyYXRpb24gKiAxMDAwIDogMDtcbiAgICAgIHJldHVybiBkdXJhdGlvbiArIDUwOyAvLyB3ZSB0YWtlIGEgc2hvcnQgb2Zmc2V0IHRvIG1ha2Ugc3VyZSB3ZSBmaXJlIG9uIHRoZSBuZXh0IGZyYW1lIGFmdGVyIGFuaW1hdGlvblxuICAgIH0sXG4gICAgZW11bGF0ZVRyYW5zaXRpb25FbmQgPSBmdW5jdGlvbihlbGVtZW50LGhhbmRsZXIpeyAvLyBlbXVsYXRlVHJhbnNpdGlvbkVuZCBzaW5jZSAyLjAuNFxuICAgICAgdmFyIGNhbGxlZCA9IDAsIGR1cmF0aW9uID0gZ2V0VHJhbnNpdGlvbkR1cmF0aW9uRnJvbUVsZW1lbnQoZWxlbWVudCk7XG4gICAgICBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb25lKGVsZW1lbnQsIHRyYW5zaXRpb25FbmRFdmVudCwgZnVuY3Rpb24oZSl7IGhhbmRsZXIoZSk7IGNhbGxlZCA9IDE7IH0pO1xuICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHsgIWNhbGxlZCAmJiBoYW5kbGVyKCk7IH0sIGR1cmF0aW9uKTtcbiAgICB9LFxuICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50ID0gZnVuY3Rpb24gKGV2ZW50TmFtZSwgY29tcG9uZW50TmFtZSwgcmVsYXRlZCkge1xuICAgICAgdmFyIE9yaWdpbmFsQ3VzdG9tRXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQoIGV2ZW50TmFtZSArICcuYnMuJyArIGNvbXBvbmVudE5hbWUpO1xuICAgICAgT3JpZ2luYWxDdXN0b21FdmVudC5yZWxhdGVkVGFyZ2V0ID0gcmVsYXRlZDtcbiAgICAgIHRoaXMuZGlzcGF0Y2hFdmVudChPcmlnaW5hbEN1c3RvbUV2ZW50KTtcbiAgICB9LFxuICBcbiAgICAvLyB0b29sdGlwIC8gcG9wb3ZlciBzdHVmZlxuICAgIGdldFNjcm9sbCA9IGZ1bmN0aW9uKCkgeyAvLyBhbHNvIEFmZml4IGFuZCBTY3JvbGxTcHkgdXNlcyBpdFxuICAgICAgcmV0dXJuIHtcbiAgICAgICAgeSA6IGdsb2JhbE9iamVjdC5wYWdlWU9mZnNldCB8fCBIVE1MW3Njcm9sbFRvcF0sXG4gICAgICAgIHggOiBnbG9iYWxPYmplY3QucGFnZVhPZmZzZXQgfHwgSFRNTFtzY3JvbGxMZWZ0XVxuICAgICAgfVxuICAgIH0sXG4gICAgc3R5bGVUaXAgPSBmdW5jdGlvbihsaW5rLGVsZW1lbnQscG9zaXRpb24scGFyZW50KSB7IC8vIGJvdGggcG9wb3ZlcnMgYW5kIHRvb2x0aXBzICh0YXJnZXQsdG9vbHRpcC9wb3BvdmVyLHBsYWNlbWVudCxlbGVtZW50VG9BcHBlbmRUbylcbiAgICAgIHZhciBlbGVtZW50RGltZW5zaW9ucyA9IHsgdyA6IGVsZW1lbnRbb2Zmc2V0V2lkdGhdLCBoOiBlbGVtZW50W29mZnNldEhlaWdodF0gfSxcbiAgICAgICAgICB3aW5kb3dXaWR0aCA9IChIVE1MW2NsaWVudFdpZHRoXSB8fCBET0NbYm9keV1bY2xpZW50V2lkdGhdKSxcbiAgICAgICAgICB3aW5kb3dIZWlnaHQgPSAoSFRNTFtjbGllbnRIZWlnaHRdIHx8IERPQ1tib2R5XVtjbGllbnRIZWlnaHRdKSxcbiAgICAgICAgICByZWN0ID0gbGlua1tnZXRCb3VuZGluZ0NsaWVudFJlY3RdKCksIFxuICAgICAgICAgIHNjcm9sbCA9IHBhcmVudCA9PT0gRE9DW2JvZHldID8gZ2V0U2Nyb2xsKCkgOiB7IHg6IHBhcmVudFtvZmZzZXRMZWZ0XSArIHBhcmVudFtzY3JvbGxMZWZ0XSwgeTogcGFyZW50W29mZnNldFRvcF0gKyBwYXJlbnRbc2Nyb2xsVG9wXSB9LFxuICAgICAgICAgIGxpbmtEaW1lbnNpb25zID0geyB3OiByZWN0W3JpZ2h0XSAtIHJlY3RbbGVmdF0sIGg6IHJlY3RbYm90dG9tXSAtIHJlY3RbdG9wXSB9LFxuICAgICAgICAgIGFycm93ID0gcXVlcnlFbGVtZW50KCdbY2xhc3MqPVwiYXJyb3dcIl0nLGVsZW1lbnQpLFxuICAgICAgICAgIHRvcFBvc2l0aW9uLCBsZWZ0UG9zaXRpb24sIGFycm93VG9wLCBhcnJvd0xlZnQsXG4gIFxuICAgICAgICAgIGhhbGZUb3BFeGNlZWQgPSByZWN0W3RvcF0gKyBsaW5rRGltZW5zaW9ucy5oLzIgLSBlbGVtZW50RGltZW5zaW9ucy5oLzIgPCAwLFxuICAgICAgICAgIGhhbGZMZWZ0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGxpbmtEaW1lbnNpb25zLncvMiAtIGVsZW1lbnREaW1lbnNpb25zLncvMiA8IDAsXG4gICAgICAgICAgaGFsZlJpZ2h0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGVsZW1lbnREaW1lbnNpb25zLncvMiArIGxpbmtEaW1lbnNpb25zLncvMiA+PSB3aW5kb3dXaWR0aCxcbiAgICAgICAgICBoYWxmQm90dG9tRXhjZWVkID0gcmVjdFt0b3BdICsgZWxlbWVudERpbWVuc2lvbnMuaC8yICsgbGlua0RpbWVuc2lvbnMuaC8yID49IHdpbmRvd0hlaWdodCxcbiAgICAgICAgICB0b3BFeGNlZWQgPSByZWN0W3RvcF0gLSBlbGVtZW50RGltZW5zaW9ucy5oIDwgMCxcbiAgICAgICAgICBsZWZ0RXhjZWVkID0gcmVjdFtsZWZ0XSAtIGVsZW1lbnREaW1lbnNpb25zLncgPCAwLFxuICAgICAgICAgIGJvdHRvbUV4Y2VlZCA9IHJlY3RbdG9wXSArIGVsZW1lbnREaW1lbnNpb25zLmggKyBsaW5rRGltZW5zaW9ucy5oID49IHdpbmRvd0hlaWdodCxcbiAgICAgICAgICByaWdodEV4Y2VlZCA9IHJlY3RbbGVmdF0gKyBlbGVtZW50RGltZW5zaW9ucy53ICsgbGlua0RpbWVuc2lvbnMudyA+PSB3aW5kb3dXaWR0aDtcbiAgXG4gICAgICAvLyByZWNvbXB1dGUgcG9zaXRpb25cbiAgICAgIHBvc2l0aW9uID0gKHBvc2l0aW9uID09PSBsZWZ0IHx8IHBvc2l0aW9uID09PSByaWdodCkgJiYgbGVmdEV4Y2VlZCAmJiByaWdodEV4Y2VlZCA/IHRvcCA6IHBvc2l0aW9uOyAvLyBmaXJzdCwgd2hlbiBib3RoIGxlZnQgYW5kIHJpZ2h0IGxpbWl0cyBhcmUgZXhjZWVkZWQsIHdlIGZhbGwgYmFjayB0byB0b3B8Ym90dG9tXG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSB0b3AgJiYgdG9wRXhjZWVkID8gYm90dG9tIDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSBib3R0b20gJiYgYm90dG9tRXhjZWVkID8gdG9wIDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSBsZWZ0ICYmIGxlZnRFeGNlZWQgPyByaWdodCA6IHBvc2l0aW9uO1xuICAgICAgcG9zaXRpb24gPSBwb3NpdGlvbiA9PT0gcmlnaHQgJiYgcmlnaHRFeGNlZWQgPyBsZWZ0IDogcG9zaXRpb247XG4gICAgICBcbiAgICAgIC8vIGFwcGx5IHN0eWxpbmcgdG8gdG9vbHRpcCBvciBwb3BvdmVyXG4gICAgICBpZiAoIHBvc2l0aW9uID09PSBsZWZ0IHx8IHBvc2l0aW9uID09PSByaWdodCApIHsgLy8gc2Vjb25kYXJ5fHNpZGUgcG9zaXRpb25zXG4gICAgICAgIGlmICggcG9zaXRpb24gPT09IGxlZnQgKSB7IC8vIExFRlRcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSByZWN0W2xlZnRdICsgc2Nyb2xsLnggLSBlbGVtZW50RGltZW5zaW9ucy53O1xuICAgICAgICB9IGVsc2UgeyAvLyBSSUdIVFxuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IHJlY3RbbGVmdF0gKyBzY3JvbGwueCArIGxpbmtEaW1lbnNpb25zLnc7XG4gICAgICAgIH1cbiAgXG4gICAgICAgIC8vIGFkanVzdCB0b3AgYW5kIGFycm93XG4gICAgICAgIGlmIChoYWxmVG9wRXhjZWVkKSB7XG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueTtcbiAgICAgICAgICBhcnJvd1RvcCA9IGxpbmtEaW1lbnNpb25zLmgvMjtcbiAgICAgICAgfSBlbHNlIGlmIChoYWxmQm90dG9tRXhjZWVkKSB7XG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueSAtIGVsZW1lbnREaW1lbnNpb25zLmggKyBsaW5rRGltZW5zaW9ucy5oO1xuICAgICAgICAgIGFycm93VG9wID0gZWxlbWVudERpbWVuc2lvbnMuaCAtIGxpbmtEaW1lbnNpb25zLmgvMjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9IHJlY3RbdG9wXSArIHNjcm9sbC55IC0gZWxlbWVudERpbWVuc2lvbnMuaC8yICsgbGlua0RpbWVuc2lvbnMuaC8yO1xuICAgICAgICB9XG4gICAgICB9IGVsc2UgaWYgKCBwb3NpdGlvbiA9PT0gdG9wIHx8IHBvc2l0aW9uID09PSBib3R0b20gKSB7IC8vIHByaW1hcnl8dmVydGljYWwgcG9zaXRpb25zXG4gICAgICAgIGlmICggcG9zaXRpb24gPT09IHRvcCkgeyAvLyBUT1BcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9ICByZWN0W3RvcF0gKyBzY3JvbGwueSAtIGVsZW1lbnREaW1lbnNpb25zLmg7XG4gICAgICAgIH0gZWxzZSB7IC8vIEJPVFRPTVxuICAgICAgICAgIHRvcFBvc2l0aW9uID0gcmVjdFt0b3BdICsgc2Nyb2xsLnkgKyBsaW5rRGltZW5zaW9ucy5oO1xuICAgICAgICB9XG4gICAgICAgIC8vIGFkanVzdCBsZWZ0IHwgcmlnaHQgYW5kIGFsc28gdGhlIGFycm93XG4gICAgICAgIGlmIChoYWxmTGVmdEV4Y2VlZCkge1xuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IDA7XG4gICAgICAgICAgYXJyb3dMZWZ0ID0gcmVjdFtsZWZ0XSArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfSBlbHNlIGlmIChoYWxmUmlnaHRFeGNlZWQpIHtcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSB3aW5kb3dXaWR0aCAtIGVsZW1lbnREaW1lbnNpb25zLncqMS4wMTtcbiAgICAgICAgICBhcnJvd0xlZnQgPSBlbGVtZW50RGltZW5zaW9ucy53IC0gKCB3aW5kb3dXaWR0aCAtIHJlY3RbbGVmdF0gKSArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSByZWN0W2xlZnRdICsgc2Nyb2xsLnggLSBlbGVtZW50RGltZW5zaW9ucy53LzIgKyBsaW5rRGltZW5zaW9ucy53LzI7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgXG4gICAgICAvLyBhcHBseSBzdHlsZSB0byB0b29sdGlwL3BvcG92ZXIgYW5kIGl0J3MgYXJyb3dcbiAgICAgIGVsZW1lbnRbc3R5bGVdW3RvcF0gPSB0b3BQb3NpdGlvbiArICdweCc7XG4gICAgICBlbGVtZW50W3N0eWxlXVtsZWZ0XSA9IGxlZnRQb3NpdGlvbiArICdweCc7XG4gIFxuICAgICAgYXJyb3dUb3AgJiYgKGFycm93W3N0eWxlXVt0b3BdID0gYXJyb3dUb3AgKyAncHgnKTtcbiAgICAgIGFycm93TGVmdCAmJiAoYXJyb3dbc3R5bGVdW2xlZnRdID0gYXJyb3dMZWZ0ICsgJ3B4Jyk7XG4gIFxuICAgICAgZWxlbWVudC5jbGFzc05hbWVbaW5kZXhPZl0ocG9zaXRpb24pID09PSAtMSAmJiAoZWxlbWVudC5jbGFzc05hbWUgPSBlbGVtZW50LmNsYXNzTmFtZS5yZXBsYWNlKHRpcFBvc2l0aW9ucyxwb3NpdGlvbikpO1xuICAgIH07XG4gIFxuICBCU04udmVyc2lvbiA9ICcyLjAuMjMnO1xuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgTW9kYWxcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBNT0RBTCBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PVxuICB2YXIgTW9kYWwgPSBmdW5jdGlvbihlbGVtZW50LCBvcHRpb25zKSB7IC8vIGVsZW1lbnQgY2FuIGJlIHRoZSBtb2RhbC90cmlnZ2VyaW5nIGJ1dHRvblxuICBcbiAgICAvLyB0aGUgbW9kYWwgKGJvdGggSmF2YVNjcmlwdCAvIERBVEEgQVBJIGluaXQpIC8gdHJpZ2dlcmluZyBidXR0b24gZWxlbWVudCAoREFUQSBBUEkpXG4gICAgZWxlbWVudCA9IHF1ZXJ5RWxlbWVudChlbGVtZW50KTtcbiAgXG4gICAgLy8gZGV0ZXJtaW5lIG1vZGFsLCB0cmlnZ2VyaW5nIGVsZW1lbnRcbiAgICB2YXIgYnRuQ2hlY2sgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oZGF0YVRhcmdldCl8fGVsZW1lbnRbZ2V0QXR0cmlidXRlXSgnaHJlZicpLFxuICAgICAgY2hlY2tNb2RhbCA9IHF1ZXJ5RWxlbWVudCggYnRuQ2hlY2sgKSxcbiAgICAgIG1vZGFsID0gaGFzQ2xhc3MoZWxlbWVudCwnbW9kYWwnKSA/IGVsZW1lbnQgOiBjaGVja01vZGFsLFxuICAgICAgb3ZlcmxheURlbGF5LFxuICBcbiAgICAgIC8vIHN0cmluZ3NcbiAgICAgIGNvbXBvbmVudCA9ICdtb2RhbCcsXG4gICAgICBzdGF0aWNTdHJpbmcgPSAnc3RhdGljJyxcbiAgICAgIHBhZGRpbmdMZWZ0ID0gJ3BhZGRpbmdMZWZ0JyxcbiAgICAgIHBhZGRpbmdSaWdodCA9ICdwYWRkaW5nUmlnaHQnLFxuICAgICAgbW9kYWxCYWNrZHJvcFN0cmluZyA9ICdtb2RhbC1iYWNrZHJvcCc7XG4gIFxuICAgIGlmICggaGFzQ2xhc3MoZWxlbWVudCwnbW9kYWwnKSApIHsgZWxlbWVudCA9IG51bGw7IH0gLy8gbW9kYWwgaXMgbm93IGluZGVwZW5kZW50IG9mIGl0J3MgdHJpZ2dlcmluZyBlbGVtZW50XG4gIFxuICAgIGlmICggIW1vZGFsICkgeyByZXR1cm47IH0gLy8gaW52YWxpZGF0ZVxuICBcbiAgICAvLyBzZXQgb3B0aW9uc1xuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICBcbiAgICB0aGlzW2tleWJvYXJkXSA9IG9wdGlvbnNba2V5Ym9hcmRdID09PSBmYWxzZSB8fCBtb2RhbFtnZXRBdHRyaWJ1dGVdKGRhdGFLZXlib2FyZCkgPT09ICdmYWxzZScgPyBmYWxzZSA6IHRydWU7XG4gICAgdGhpc1tiYWNrZHJvcF0gPSBvcHRpb25zW2JhY2tkcm9wXSA9PT0gc3RhdGljU3RyaW5nIHx8IG1vZGFsW2dldEF0dHJpYnV0ZV0oZGF0YWJhY2tkcm9wKSA9PT0gc3RhdGljU3RyaW5nID8gc3RhdGljU3RyaW5nIDogdHJ1ZTtcbiAgICB0aGlzW2JhY2tkcm9wXSA9IG9wdGlvbnNbYmFja2Ryb3BdID09PSBmYWxzZSB8fCBtb2RhbFtnZXRBdHRyaWJ1dGVdKGRhdGFiYWNrZHJvcCkgPT09ICdmYWxzZScgPyBmYWxzZSA6IHRoaXNbYmFja2Ryb3BdO1xuICAgIHRoaXNbY29udGVudF0gID0gb3B0aW9uc1tjb250ZW50XTsgLy8gSmF2YVNjcmlwdCBvbmx5XG4gIFxuICAgIC8vIGJpbmQsIGNvbnN0YW50cywgZXZlbnQgdGFyZ2V0cyBhbmQgb3RoZXIgdmFyc1xuICAgIHZhciBzZWxmID0gdGhpcywgcmVsYXRlZFRhcmdldCA9IG51bGwsXG4gICAgICBib2R5SXNPdmVyZmxvd2luZywgbW9kYWxJc092ZXJmbG93aW5nLCBzY3JvbGxiYXJXaWR0aCwgb3ZlcmxheSxcbiAgXG4gICAgICAvLyBhbHNvIGZpbmQgZml4ZWQtdG9wIC8gZml4ZWQtYm90dG9tIGl0ZW1zXG4gICAgICBmaXhlZEl0ZW1zID0gZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShIVE1MLGZpeGVkVG9wKS5jb25jYXQoZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShIVE1MLGZpeGVkQm90dG9tKSksXG4gIFxuICAgICAgLy8gcHJpdmF0ZSBtZXRob2RzXG4gICAgICBnZXRXaW5kb3dXaWR0aCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaHRtbFJlY3QgPSBIVE1MW2dldEJvdW5kaW5nQ2xpZW50UmVjdF0oKTtcbiAgICAgICAgcmV0dXJuIGdsb2JhbE9iamVjdFtpbm5lcldpZHRoXSB8fCAoaHRtbFJlY3RbcmlnaHRdIC0gTWF0aC5hYnMoaHRtbFJlY3RbbGVmdF0pKTtcbiAgICAgIH0sXG4gICAgICBzZXRTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBib2R5U3R5bGUgPSBET0NbYm9keV0uY3VycmVudFN0eWxlIHx8IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShET0NbYm9keV0pLFxuICAgICAgICAgICAgYm9keVBhZCA9IHBhcnNlSW50KChib2R5U3R5bGVbcGFkZGluZ1JpZ2h0XSksIDEwKSwgaXRlbVBhZDtcbiAgICAgICAgaWYgKGJvZHlJc092ZXJmbG93aW5nKSB7XG4gICAgICAgICAgRE9DW2JvZHldW3N0eWxlXVtwYWRkaW5nUmlnaHRdID0gKGJvZHlQYWQgKyBzY3JvbGxiYXJXaWR0aCkgKyAncHgnO1xuICAgICAgICAgIGlmIChmaXhlZEl0ZW1zW2xlbmd0aF0pe1xuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBmaXhlZEl0ZW1zW2xlbmd0aF07IGkrKykge1xuICAgICAgICAgICAgICBpdGVtUGFkID0gKGZpeGVkSXRlbXNbaV0uY3VycmVudFN0eWxlIHx8IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShmaXhlZEl0ZW1zW2ldKSlbcGFkZGluZ1JpZ2h0XTtcbiAgICAgICAgICAgICAgZml4ZWRJdGVtc1tpXVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICggcGFyc2VJbnQoaXRlbVBhZCkgKyBzY3JvbGxiYXJXaWR0aCkgKyAncHgnO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHJlc2V0U2Nyb2xsYmFyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBET0NbYm9keV1bc3R5bGVdW3BhZGRpbmdSaWdodF0gPSAnJztcbiAgICAgICAgaWYgKGZpeGVkSXRlbXNbbGVuZ3RoXSl7XG4gICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBmaXhlZEl0ZW1zW2xlbmd0aF07IGkrKykge1xuICAgICAgICAgICAgZml4ZWRJdGVtc1tpXVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIG1lYXN1cmVTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7IC8vIHRoeCB3YWxzaFxuICAgICAgICB2YXIgc2Nyb2xsRGl2ID0gRE9DW2NyZWF0ZUVsZW1lbnRdKCdkaXYnKSwgc2Nyb2xsQmFyV2lkdGg7XG4gICAgICAgIHNjcm9sbERpdi5jbGFzc05hbWUgPSBjb21wb25lbnQrJy1zY3JvbGxiYXItbWVhc3VyZSc7IC8vIHRoaXMgaXMgaGVyZSB0byBzdGF5XG4gICAgICAgIERPQ1tib2R5XVthcHBlbmRDaGlsZF0oc2Nyb2xsRGl2KTtcbiAgICAgICAgc2Nyb2xsQmFyV2lkdGggPSBzY3JvbGxEaXZbb2Zmc2V0V2lkdGhdIC0gc2Nyb2xsRGl2W2NsaWVudFdpZHRoXTtcbiAgICAgICAgRE9DW2JvZHldLnJlbW92ZUNoaWxkKHNjcm9sbERpdik7XG4gICAgICByZXR1cm4gc2Nyb2xsQmFyV2lkdGg7XG4gICAgICB9LFxuICAgICAgY2hlY2tTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGJvZHlJc092ZXJmbG93aW5nID0gRE9DW2JvZHldW2NsaWVudFdpZHRoXSA8IGdldFdpbmRvd1dpZHRoKCk7XG4gICAgICAgIG1vZGFsSXNPdmVyZmxvd2luZyA9IG1vZGFsW3Njcm9sbEhlaWdodF0gPiBIVE1MW2NsaWVudEhlaWdodF07XG4gICAgICAgIHNjcm9sbGJhcldpZHRoID0gbWVhc3VyZVNjcm9sbGJhcigpO1xuICAgICAgfSxcbiAgICAgIGFkanVzdERpYWxvZyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdMZWZ0XSA9ICFib2R5SXNPdmVyZmxvd2luZyAmJiBtb2RhbElzT3ZlcmZsb3dpbmcgPyBzY3JvbGxiYXJXaWR0aCArICdweCcgOiAnJztcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdSaWdodF0gPSBib2R5SXNPdmVyZmxvd2luZyAmJiAhbW9kYWxJc092ZXJmbG93aW5nID8gc2Nyb2xsYmFyV2lkdGggKyAncHgnIDogJyc7XG4gICAgICB9LFxuICAgICAgcmVzZXRBZGp1c3RtZW50cyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdMZWZ0XSA9ICcnO1xuICAgICAgICBtb2RhbFtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgfSxcbiAgICAgIGNyZWF0ZU92ZXJsYXkgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgbW9kYWxPdmVybGF5ID0gMTtcbiAgICAgICAgXG4gICAgICAgIHZhciBuZXdPdmVybGF5ID0gRE9DW2NyZWF0ZUVsZW1lbnRdKCdkaXYnKTtcbiAgICAgICAgb3ZlcmxheSA9IHF1ZXJ5RWxlbWVudCgnLicrbW9kYWxCYWNrZHJvcFN0cmluZyk7XG4gIFxuICAgICAgICBpZiAoIG92ZXJsYXkgPT09IG51bGwgKSB7XG4gICAgICAgICAgbmV3T3ZlcmxheVtzZXRBdHRyaWJ1dGVdKCdjbGFzcycsbW9kYWxCYWNrZHJvcFN0cmluZysnIGZhZGUnKTtcbiAgICAgICAgICBvdmVybGF5ID0gbmV3T3ZlcmxheTtcbiAgICAgICAgICBET0NbYm9keV1bYXBwZW5kQ2hpbGRdKG92ZXJsYXkpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgcmVtb3ZlT3ZlcmxheSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgICAgICAgaWYgKCBvdmVybGF5ICYmIG92ZXJsYXkgIT09IG51bGwgJiYgdHlwZW9mIG92ZXJsYXkgPT09ICdvYmplY3QnICkge1xuICAgICAgICAgIG1vZGFsT3ZlcmxheSA9IDA7XG4gICAgICAgICAgRE9DW2JvZHldLnJlbW92ZUNoaWxkKG92ZXJsYXkpOyBvdmVybGF5ID0gbnVsbDtcbiAgICAgICAgfVxuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBoaWRkZW5FdmVudCwgY29tcG9uZW50KTsgICAgICBcbiAgICAgIH0sXG4gICAgICBrZXlkb3duSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihET0MsIGtleWRvd25FdmVudCwga2V5SGFuZGxlcik7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgb2ZmKERPQywga2V5ZG93bkV2ZW50LCBrZXlIYW5kbGVyKTtcbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHJlc2l6ZUhhbmRsZXJUb2dnbGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgICAgb24oZ2xvYmFsT2JqZWN0LCByZXNpemVFdmVudCwgc2VsZi51cGRhdGUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG9mZihnbG9iYWxPYmplY3QsIHJlc2l6ZUV2ZW50LCBzZWxmLnVwZGF0ZSk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBkaXNtaXNzSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihtb2RhbCwgY2xpY2tFdmVudCwgZGlzbWlzc0hhbmRsZXIpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG9mZihtb2RhbCwgY2xpY2tFdmVudCwgZGlzbWlzc0hhbmRsZXIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgLy8gdHJpZ2dlcnNcbiAgICAgIHRyaWdnZXJTaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHNldEZvY3VzKG1vZGFsKTtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChtb2RhbCwgc2hvd25FdmVudCwgY29tcG9uZW50LCByZWxhdGVkVGFyZ2V0KTtcbiAgICAgIH0sXG4gICAgICB0cmlnZ2VySGlkZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV0uZGlzcGxheSA9ICcnO1xuICAgICAgICBlbGVtZW50ICYmIChzZXRGb2N1cyhlbGVtZW50KSk7XG4gICAgICAgIFxuICAgICAgICAoZnVuY3Rpb24oKXtcbiAgICAgICAgICBpZiAoIWdldEVsZW1lbnRzQnlDbGFzc05hbWUoRE9DLGNvbXBvbmVudCsnICcraW5DbGFzcylbMF0pIHtcbiAgICAgICAgICAgIHJlc2V0QWRqdXN0bWVudHMoKTtcbiAgICAgICAgICAgIHJlc2V0U2Nyb2xsYmFyKCk7XG4gICAgICAgICAgICByZW1vdmVDbGFzcyhET0NbYm9keV0sY29tcG9uZW50Kyctb3BlbicpO1xuICAgICAgICAgICAgb3ZlcmxheSAmJiBoYXNDbGFzcyhvdmVybGF5LCdmYWRlJykgPyAocmVtb3ZlQ2xhc3Mob3ZlcmxheSxpbkNsYXNzKSwgZW11bGF0ZVRyYW5zaXRpb25FbmQob3ZlcmxheSxyZW1vdmVPdmVybGF5KSkgXG4gICAgICAgICAgICA6IHJlbW92ZU92ZXJsYXkoKTtcbiAgXG4gICAgICAgICAgICByZXNpemVIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgICAgICBkaXNtaXNzSGFuZGxlclRvZ2dsZSgpO1xuICAgICAgICAgICAga2V5ZG93bkhhbmRsZXJUb2dnbGUoKTtcbiAgICAgICAgICB9XG4gICAgICAgIH0oKSk7XG4gICAgICB9LFxuICAgICAgLy8gaGFuZGxlcnNcbiAgICAgIGNsaWNrSGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGNsaWNrVGFyZ2V0ID0gZVt0YXJnZXRdO1xuICAgICAgICBjbGlja1RhcmdldCA9IGNsaWNrVGFyZ2V0W2hhc0F0dHJpYnV0ZV0oZGF0YVRhcmdldCkgfHwgY2xpY2tUYXJnZXRbaGFzQXR0cmlidXRlXSgnaHJlZicpID8gY2xpY2tUYXJnZXQgOiBjbGlja1RhcmdldFtwYXJlbnROb2RlXTtcbiAgICAgICAgaWYgKCBjbGlja1RhcmdldCA9PT0gZWxlbWVudCAmJiAhaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykgKSB7XG4gICAgICAgICAgbW9kYWwubW9kYWxUcmlnZ2VyID0gZWxlbWVudDtcbiAgICAgICAgICByZWxhdGVkVGFyZ2V0ID0gZWxlbWVudDtcbiAgICAgICAgICBzZWxmLnNob3coKTtcbiAgICAgICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAga2V5SGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGtleSA9IGUud2hpY2ggfHwgZS5rZXlDb2RlOyAvLyBrZXlDb2RlIGZvciBJRThcbiAgICAgICAgaWYgKHNlbGZba2V5Ym9hcmRdICYmIGtleSA9PSAyNyAmJiBoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSkge1xuICAgICAgICAgIHNlbGYuaGlkZSgpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgZGlzbWlzc0hhbmRsZXIgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgIHZhciBjbGlja1RhcmdldCA9IGVbdGFyZ2V0XTtcbiAgICAgICAgaWYgKCBoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSAmJiAoY2xpY2tUYXJnZXRbcGFyZW50Tm9kZV1bZ2V0QXR0cmlidXRlXShkYXRhRGlzbWlzcykgPT09IGNvbXBvbmVudFxuICAgICAgICAgICAgfHwgY2xpY2tUYXJnZXRbZ2V0QXR0cmlidXRlXShkYXRhRGlzbWlzcykgPT09IGNvbXBvbmVudFxuICAgICAgICAgICAgfHwgKGNsaWNrVGFyZ2V0ID09PSBtb2RhbCAmJiBzZWxmW2JhY2tkcm9wXSAhPT0gc3RhdGljU3RyaW5nKSApICkge1xuICAgICAgICAgIHNlbGYuaGlkZSgpOyByZWxhdGVkVGFyZ2V0ID0gbnVsbDtcbiAgICAgICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgICB9XG4gICAgICB9O1xuICBcbiAgICAvLyBwdWJsaWMgbWV0aG9kc1xuICAgIHRoaXMudG9nZ2xlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpICkge3RoaXMuaGlkZSgpO30gZWxzZSB7dGhpcy5zaG93KCk7fVxuICAgIH07XG4gICAgdGhpcy5zaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBzaG93RXZlbnQsIGNvbXBvbmVudCwgcmVsYXRlZFRhcmdldCk7XG4gIFxuICAgICAgLy8gd2UgZWxlZ2FudGx5IGhpZGUgYW55IG9wZW5lZCBtb2RhbFxuICAgICAgdmFyIGN1cnJlbnRPcGVuID0gZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShET0MsY29tcG9uZW50KycgaW4nKVswXTtcbiAgICAgIGN1cnJlbnRPcGVuICYmIGN1cnJlbnRPcGVuICE9PSBtb2RhbCAmJiBjdXJyZW50T3Blbi5tb2RhbFRyaWdnZXJbc3RyaW5nTW9kYWxdLmhpZGUoKTtcbiAgXG4gICAgICBpZiAoIHRoaXNbYmFja2Ryb3BdICkge1xuICAgICAgICAhbW9kYWxPdmVybGF5ICYmIGNyZWF0ZU92ZXJsYXkoKTtcbiAgICAgIH1cbiAgXG4gICAgICBpZiAoIG92ZXJsYXkgJiYgbW9kYWxPdmVybGF5ICYmICFoYXNDbGFzcyhvdmVybGF5LGluQ2xhc3MpKSB7XG4gICAgICAgIG92ZXJsYXlbb2Zmc2V0V2lkdGhdOyAvLyBmb3JjZSByZWZsb3cgdG8gZW5hYmxlIHRyYXNpdGlvblxuICAgICAgICBvdmVybGF5RGVsYXkgPSBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudChvdmVybGF5KTtcbiAgICAgICAgYWRkQ2xhc3Mob3ZlcmxheSxpbkNsYXNzKTtcbiAgICAgIH1cbiAgXG4gICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV0uZGlzcGxheSA9ICdibG9jayc7XG4gIFxuICAgICAgICBjaGVja1Njcm9sbGJhcigpO1xuICAgICAgICBzZXRTY3JvbGxiYXIoKTtcbiAgICAgICAgYWRqdXN0RGlhbG9nKCk7XG4gIFxuICAgICAgICBhZGRDbGFzcyhET0NbYm9keV0sY29tcG9uZW50Kyctb3BlbicpO1xuICAgICAgICBhZGRDbGFzcyhtb2RhbCxpbkNsYXNzKTtcbiAgICAgICAgbW9kYWxbc2V0QXR0cmlidXRlXShhcmlhSGlkZGVuLCBmYWxzZSk7XG4gICAgICAgIFxuICAgICAgICByZXNpemVIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgIGRpc21pc3NIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgIGtleWRvd25IYW5kbGVyVG9nZ2xlKCk7XG4gIFxuICAgICAgICBoYXNDbGFzcyhtb2RhbCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQobW9kYWwsIHRyaWdnZXJTaG93KSA6IHRyaWdnZXJTaG93KCk7XG4gICAgICB9LCBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb3ZlcmxheSA/IG92ZXJsYXlEZWxheSA6IDApO1xuICAgIH07XG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBoaWRlRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgICAgIG92ZXJsYXlEZWxheSA9IG92ZXJsYXkgJiYgZ2V0VHJhbnNpdGlvbkR1cmF0aW9uRnJvbUVsZW1lbnQob3ZlcmxheSk7XG4gIFxuICAgICAgcmVtb3ZlQ2xhc3MobW9kYWwsaW5DbGFzcyk7XG4gICAgICBtb2RhbFtzZXRBdHRyaWJ1dGVdKGFyaWFIaWRkZW4sIHRydWUpO1xuICBcbiAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgaGFzQ2xhc3MobW9kYWwsJ2ZhZGUnKSA/IGVtdWxhdGVUcmFuc2l0aW9uRW5kKG1vZGFsLCB0cmlnZ2VySGlkZSkgOiB0cmlnZ2VySGlkZSgpO1xuICAgICAgfSwgc3VwcG9ydFRyYW5zaXRpb25zICYmIG92ZXJsYXkgPyBvdmVybGF5RGVsYXkgOiAwKTtcbiAgICB9O1xuICAgIHRoaXMuc2V0Q29udGVudCA9IGZ1bmN0aW9uKCBjb250ZW50ICkge1xuICAgICAgcXVlcnlFbGVtZW50KCcuJytjb21wb25lbnQrJy1jb250ZW50Jyxtb2RhbClbaW5uZXJIVE1MXSA9IGNvbnRlbnQ7XG4gICAgfTtcbiAgICB0aGlzLnVwZGF0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgIGNoZWNrU2Nyb2xsYmFyKCk7XG4gICAgICAgIHNldFNjcm9sbGJhcigpO1xuICAgICAgICBhZGp1c3REaWFsb2coKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgb3ZlciBhbmQgb3ZlclxuICAgIC8vIG1vZGFsIGlzIGluZGVwZW5kZW50IG9mIGEgdHJpZ2dlcmluZyBlbGVtZW50XG4gICAgaWYgKCAhIWVsZW1lbnQgJiYgIShzdHJpbmdNb2RhbCBpbiBlbGVtZW50KSApIHtcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIGNsaWNrSGFuZGxlcik7XG4gICAgfVxuICAgIGlmICggISFzZWxmW2NvbnRlbnRdICkgeyBzZWxmLnNldENvbnRlbnQoIHNlbGZbY29udGVudF0gKTsgfVxuICAgICEhZWxlbWVudCAmJiAoZWxlbWVudFtzdHJpbmdNb2RhbF0gPSBzZWxmKTtcbiAgfTtcbiAgXG4gIC8vIERBVEEgQVBJXG4gIHN1cHBvcnRzW3B1c2hdKCBbIHN0cmluZ01vZGFsLCBNb2RhbCwgJ1snK2RhdGFUb2dnbGUrJz1cIm1vZGFsXCJdJyBdICk7XG4gIFxuICAvKiBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIDMgfCBDb2xsYXBzZVxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBDT0xMQVBTRSBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PT09PT1cbiAgdmFyIENvbGxhcHNlID0gZnVuY3Rpb24oIGVsZW1lbnQsIG9wdGlvbnMgKSB7XG4gIFxuICAgIC8vIGluaXRpYWxpemF0aW9uIGVsZW1lbnRcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBzZXQgb3B0aW9uc1xuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICBcbiAgICAvLyBldmVudCB0YXJnZXRzIGFuZCBjb25zdGFudHNcbiAgICB2YXIgYWNjb3JkaW9uID0gbnVsbCwgY29sbGFwc2UgPSBudWxsLCBzZWxmID0gdGhpcyxcbiAgICAgIGFjY29yZGlvbkRhdGEgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oJ2RhdGEtcGFyZW50JyksXG4gICAgICBhY3RpdmVDb2xsYXBzZSwgYWN0aXZlRWxlbWVudCxcbiAgXG4gICAgICAvLyBjb21wb25lbnQgc3RyaW5nc1xuICAgICAgY29tcG9uZW50ID0gJ2NvbGxhcHNlJyxcbiAgICAgIGNvbGxhcHNlZCA9ICdjb2xsYXBzZWQnLFxuICAgICAgaXNBbmltYXRpbmcgPSAnaXNBbmltYXRpbmcnLFxuICBcbiAgICAgIC8vIHByaXZhdGUgbWV0aG9kc1xuICAgICAgb3BlbkFjdGlvbiA9IGZ1bmN0aW9uKGNvbGxhcHNlRWxlbWVudCx0b2dnbGUpIHtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChjb2xsYXBzZUVsZW1lbnQsIHNob3dFdmVudCwgY29tcG9uZW50KTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IHRydWU7XG4gICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgcmVtb3ZlQ2xhc3MoY29sbGFwc2VFbGVtZW50LGNvbXBvbmVudCk7XG4gICAgICAgIGNvbGxhcHNlRWxlbWVudFtzdHlsZV1baGVpZ2h0XSA9IGNvbGxhcHNlRWxlbWVudFtzY3JvbGxIZWlnaHRdICsgJ3B4JztcbiAgICAgICAgXG4gICAgICAgIGVtdWxhdGVUcmFuc2l0aW9uRW5kKGNvbGxhcHNlRWxlbWVudCwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IGZhbHNlO1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwndHJ1ZScpO1xuICAgICAgICAgIHRvZ2dsZVtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwndHJ1ZScpOyAgICAgICAgICBcbiAgICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29sbGFwc2luZyk7XG4gICAgICAgICAgYWRkQ2xhc3MoY29sbGFwc2VFbGVtZW50LCBjb21wb25lbnQpO1xuICAgICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCwgaW5DbGFzcyk7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gJyc7XG4gICAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChjb2xsYXBzZUVsZW1lbnQsIHNob3duRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIH0pO1xuICAgICAgfSxcbiAgICAgIGNsb3NlQWN0aW9uID0gZnVuY3Rpb24oY29sbGFwc2VFbGVtZW50LHRvZ2dsZSkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgaGlkZUV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbaXNBbmltYXRpbmddID0gdHJ1ZTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gY29sbGFwc2VFbGVtZW50W3Njcm9sbEhlaWdodF0gKyAncHgnOyAvLyBzZXQgaGVpZ2h0IGZpcnN0XG4gICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb21wb25lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGluQ2xhc3MpO1xuICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGNvbGxhcHNpbmcpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbb2Zmc2V0V2lkdGhdOyAvLyBmb3JjZSByZWZsb3cgdG8gZW5hYmxlIHRyYW5zaXRpb25cbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gJzBweCc7XG4gICAgICAgIFxuICAgICAgICBlbXVsYXRlVHJhbnNpdGlvbkVuZChjb2xsYXBzZUVsZW1lbnQsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtpc0FuaW1hdGluZ10gPSBmYWxzZTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc2V0QXR0cmlidXRlXShhcmlhRXhwYW5kZWQsJ2ZhbHNlJyk7XG4gICAgICAgICAgdG9nZ2xlW3NldEF0dHJpYnV0ZV0oYXJpYUV4cGFuZGVkLCdmYWxzZScpO1xuICAgICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29tcG9uZW50KTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc3R5bGVdW2hlaWdodF0gPSAnJztcbiAgICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgaGlkZGVuRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIH0pO1xuICAgICAgfSxcbiAgICAgIGdldFRhcmdldCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaHJlZiA9IGVsZW1lbnQuaHJlZiAmJiBlbGVtZW50W2dldEF0dHJpYnV0ZV0oJ2hyZWYnKSxcbiAgICAgICAgICBwYXJlbnQgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oZGF0YVRhcmdldCksXG4gICAgICAgICAgaWQgPSBocmVmIHx8ICggcGFyZW50ICYmIHBhcmVudC5jaGFyQXQoMCkgPT09ICcjJyApICYmIHBhcmVudDtcbiAgICAgICAgcmV0dXJuIGlkICYmIHF1ZXJ5RWxlbWVudChpZCk7XG4gICAgICB9O1xuICAgIFxuICAgIC8vIHB1YmxpYyBtZXRob2RzXG4gICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbihlKSB7XG4gICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgaWYgKCFoYXNDbGFzcyhjb2xsYXBzZSxpbkNsYXNzKSkgeyBzZWxmLnNob3coKTsgfSBcbiAgICAgIGVsc2UgeyBzZWxmLmhpZGUoKTsgfVxuICAgIH07XG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGNvbGxhcHNlW2lzQW5pbWF0aW5nXSApIHJldHVybjtcbiAgICAgIGNsb3NlQWN0aW9uKGNvbGxhcHNlLGVsZW1lbnQpO1xuICAgICAgYWRkQ2xhc3MoZWxlbWVudCxjb2xsYXBzZWQpO1xuICAgIH07XG4gICAgdGhpcy5zaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGFjY29yZGlvbiApIHtcbiAgICAgICAgYWN0aXZlQ29sbGFwc2UgPSBxdWVyeUVsZW1lbnQoJy4nK2NvbXBvbmVudCsnLicraW5DbGFzcyxhY2NvcmRpb24pO1xuICAgICAgICBhY3RpdmVFbGVtZW50ID0gYWN0aXZlQ29sbGFwc2UgJiYgKHF1ZXJ5RWxlbWVudCgnWycrZGF0YVRvZ2dsZSsnPVwiJytjb21wb25lbnQrJ1wiXVsnK2RhdGFUYXJnZXQrJz1cIiMnK2FjdGl2ZUNvbGxhcHNlLmlkKydcIl0nLCBhY2NvcmRpb24pXG4gICAgICAgICAgICAgICAgICAgICAgfHwgcXVlcnlFbGVtZW50KCdbJytkYXRhVG9nZ2xlKyc9XCInK2NvbXBvbmVudCsnXCJdW2hyZWY9XCIjJythY3RpdmVDb2xsYXBzZS5pZCsnXCJdJyxhY2NvcmRpb24pICk7XG4gICAgICB9XG4gIFxuICAgICAgaWYgKCAhY29sbGFwc2VbaXNBbmltYXRpbmddIHx8IGFjdGl2ZUNvbGxhcHNlICYmICFhY3RpdmVDb2xsYXBzZVtpc0FuaW1hdGluZ10gKSB7XG4gICAgICAgIGlmICggYWN0aXZlRWxlbWVudCAmJiBhY3RpdmVDb2xsYXBzZSAhPT0gY29sbGFwc2UgKSB7XG4gICAgICAgICAgY2xvc2VBY3Rpb24oYWN0aXZlQ29sbGFwc2UsYWN0aXZlRWxlbWVudCk7XG4gICAgICAgICAgYWRkQ2xhc3MoYWN0aXZlRWxlbWVudCxjb2xsYXBzZWQpOyBcbiAgICAgICAgfVxuICAgICAgICBvcGVuQWN0aW9uKGNvbGxhcHNlLGVsZW1lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhlbGVtZW50LGNvbGxhcHNlZCk7XG4gICAgICB9XG4gICAgfTtcbiAgXG4gICAgLy8gaW5pdFxuICAgIGlmICggIShzdHJpbmdDb2xsYXBzZSBpbiBlbGVtZW50ICkgKSB7IC8vIHByZXZlbnQgYWRkaW5nIGV2ZW50IGhhbmRsZXJzIHR3aWNlXG4gICAgICBvbihlbGVtZW50LCBjbGlja0V2ZW50LCBzZWxmLnRvZ2dsZSk7XG4gICAgfVxuICAgIGNvbGxhcHNlID0gZ2V0VGFyZ2V0KCk7XG4gICAgY29sbGFwc2VbaXNBbmltYXRpbmddID0gZmFsc2U7ICAvLyB3aGVuIHRydWUgaXQgd2lsbCBwcmV2ZW50IGNsaWNrIGhhbmRsZXJzICBcbiAgICBhY2NvcmRpb24gPSBxdWVyeUVsZW1lbnQob3B0aW9ucy5wYXJlbnQpIHx8IGFjY29yZGlvbkRhdGEgJiYgZ2V0Q2xvc2VzdChlbGVtZW50LCBhY2NvcmRpb25EYXRhKTtcbiAgICBlbGVtZW50W3N0cmluZ0NvbGxhcHNlXSA9IHNlbGY7XG4gIH07XG4gIFxuICAvLyBDT0xMQVBTRSBEQVRBIEFQSVxuICAvLyA9PT09PT09PT09PT09PT09PVxuICBzdXBwb3J0c1twdXNoXSggWyBzdHJpbmdDb2xsYXBzZSwgQ29sbGFwc2UsICdbJytkYXRhVG9nZ2xlKyc9XCJjb2xsYXBzZVwiXScgXSApO1xuICBcbiAgXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IEFsZXJ0XG4gIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0qL1xuICBcbiAgLy8gQUxFUlQgREVGSU5JVElPTlxuICAvLyA9PT09PT09PT09PT09PT09XG4gIHZhciBBbGVydCA9IGZ1bmN0aW9uKCBlbGVtZW50ICkge1xuICAgIFxuICAgIC8vIGluaXRpYWxpemF0aW9uIGVsZW1lbnRcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBiaW5kLCB0YXJnZXQgYWxlcnQsIGR1cmF0aW9uIGFuZCBzdHVmZlxuICAgIHZhciBzZWxmID0gdGhpcywgY29tcG9uZW50ID0gJ2FsZXJ0JyxcbiAgICAgIGFsZXJ0ID0gZ2V0Q2xvc2VzdChlbGVtZW50LCcuJytjb21wb25lbnQpLFxuICAgICAgdHJpZ2dlckhhbmRsZXIgPSBmdW5jdGlvbigpeyBoYXNDbGFzcyhhbGVydCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQoYWxlcnQsdHJhbnNpdGlvbkVuZEhhbmRsZXIpIDogdHJhbnNpdGlvbkVuZEhhbmRsZXIoKTsgfSxcbiAgICAgIC8vIGhhbmRsZXJzXG4gICAgICBjbGlja0hhbmRsZXIgPSBmdW5jdGlvbihlKXtcbiAgICAgICAgYWxlcnQgPSBnZXRDbG9zZXN0KGVbdGFyZ2V0XSwnLicrY29tcG9uZW50KTtcbiAgICAgICAgZWxlbWVudCA9IHF1ZXJ5RWxlbWVudCgnWycrZGF0YURpc21pc3MrJz1cIicrY29tcG9uZW50KydcIl0nLGFsZXJ0KTtcbiAgICAgICAgZWxlbWVudCAmJiBhbGVydCAmJiAoZWxlbWVudCA9PT0gZVt0YXJnZXRdIHx8IGVsZW1lbnRbY29udGFpbnNdKGVbdGFyZ2V0XSkpICYmIHNlbGYuY2xvc2UoKTtcbiAgICAgIH0sXG4gICAgICB0cmFuc2l0aW9uRW5kSGFuZGxlciA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwoYWxlcnQsIGNsb3NlZEV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBvZmYoZWxlbWVudCwgY2xpY2tFdmVudCwgY2xpY2tIYW5kbGVyKTsgLy8gZGV0YWNoIGl0J3MgbGlzdGVuZXJcbiAgICAgICAgYWxlcnRbcGFyZW50Tm9kZV0ucmVtb3ZlQ2hpbGQoYWxlcnQpO1xuICAgICAgfTtcbiAgICBcbiAgICAvLyBwdWJsaWMgbWV0aG9kXG4gICAgdGhpcy5jbG9zZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKCBhbGVydCAmJiBlbGVtZW50ICYmIGhhc0NsYXNzKGFsZXJ0LGluQ2xhc3MpICkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGFsZXJ0LCBjbG9zZUV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhhbGVydCxpbkNsYXNzKTtcbiAgICAgICAgYWxlcnQgJiYgdHJpZ2dlckhhbmRsZXIoKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgaWYgKCAhKHN0cmluZ0FsZXJ0IGluIGVsZW1lbnQgKSApIHsgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgdHdpY2VcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIGNsaWNrSGFuZGxlcik7XG4gICAgfVxuICAgIGVsZW1lbnRbc3RyaW5nQWxlcnRdID0gc2VsZjtcbiAgfTtcbiAgXG4gIC8vIEFMRVJUIERBVEEgQVBJXG4gIC8vID09PT09PT09PT09PT09XG4gIHN1cHBvcnRzW3B1c2hdKFtzdHJpbmdBbGVydCwgQWxlcnQsICdbJytkYXRhRGlzbWlzcysnPVwiYWxlcnRcIl0nXSk7XG4gIFxuICBcbiAgXG4gIFxyXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IEluaXRpYWxpemUgRGF0YSBBUElcclxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXHJcbiAgdmFyIGluaXRpYWxpemVEYXRhQVBJID0gZnVuY3Rpb24oIGNvbnN0cnVjdG9yLCBjb2xsZWN0aW9uICl7XHJcbiAgICAgIGZvciAodmFyIGk9MCwgbD1jb2xsZWN0aW9uW2xlbmd0aF07IGk8bDsgaSsrKSB7XHJcbiAgICAgICAgbmV3IGNvbnN0cnVjdG9yKGNvbGxlY3Rpb25baV0pO1xyXG4gICAgICB9XHJcbiAgICB9LFxyXG4gICAgaW5pdENhbGxiYWNrID0gQlNOLmluaXRDYWxsYmFjayA9IGZ1bmN0aW9uKGxvb2tVcCl7XHJcbiAgICAgIGxvb2tVcCA9IGxvb2tVcCB8fCBET0M7XHJcbiAgICAgIGZvciAodmFyIGk9MCwgbD1zdXBwb3J0c1tsZW5ndGhdOyBpPGw7IGkrKykge1xyXG4gICAgICAgIGluaXRpYWxpemVEYXRhQVBJKCBzdXBwb3J0c1tpXVsxXSwgbG9va1VwW3F1ZXJ5U2VsZWN0b3JBbGxdIChzdXBwb3J0c1tpXVsyXSkgKTtcclxuICAgICAgfVxyXG4gICAgfTtcclxuICBcclxuICAvLyBidWxrIGluaXRpYWxpemUgYWxsIGNvbXBvbmVudHNcclxuICBET0NbYm9keV0gPyBpbml0Q2FsbGJhY2soKSA6IG9uKCBET0MsICdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24oKXsgaW5pdENhbGxiYWNrKCk7IH0gKTtcclxuICBcbiAgcmV0dXJuIHtcbiAgICBNb2RhbDogTW9kYWwsXG4gICAgQ29sbGFwc2U6IENvbGxhcHNlLFxuICAgIEFsZXJ0OiBBbGVydFxuICB9O1xufSkpO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvYm9vdHN0cmFwLm5hdGl2ZS9kaXN0L2Jvb3RzdHJhcC1uYXRpdmUuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2Jvb3RzdHJhcC5uYXRpdmUvZGlzdC9ib290c3RyYXAtbmF0aXZlLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qXG4gKiBjbGFzc0xpc3QuanM6IENyb3NzLWJyb3dzZXIgZnVsbCBlbGVtZW50LmNsYXNzTGlzdCBpbXBsZW1lbnRhdGlvbi5cbiAqIDEuMS4yMDE3MDQyN1xuICpcbiAqIEJ5IEVsaSBHcmV5LCBodHRwOi8vZWxpZ3JleS5jb21cbiAqIExpY2Vuc2U6IERlZGljYXRlZCB0byB0aGUgcHVibGljIGRvbWFpbi5cbiAqICAgU2VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbGlncmV5L2NsYXNzTGlzdC5qcy9ibG9iL21hc3Rlci9MSUNFTlNFLm1kXG4gKi9cblxuLypnbG9iYWwgc2VsZiwgZG9jdW1lbnQsIERPTUV4Y2VwdGlvbiAqL1xuXG4vKiEgQHNvdXJjZSBodHRwOi8vcHVybC5lbGlncmV5LmNvbS9naXRodWIvY2xhc3NMaXN0LmpzL2Jsb2IvbWFzdGVyL2NsYXNzTGlzdC5qcyAqL1xuXG5pZiAoXCJkb2N1bWVudFwiIGluIHdpbmRvdy5zZWxmKSB7XG5cbi8vIEZ1bGwgcG9seWZpbGwgZm9yIGJyb3dzZXJzIHdpdGggbm8gY2xhc3NMaXN0IHN1cHBvcnRcbi8vIEluY2x1ZGluZyBJRSA8IEVkZ2UgbWlzc2luZyBTVkdFbGVtZW50LmNsYXNzTGlzdFxuaWYgKCEoXCJjbGFzc0xpc3RcIiBpbiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiX1wiKSkgXG5cdHx8IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnROUyAmJiAhKFwiY2xhc3NMaXN0XCIgaW4gZG9jdW1lbnQuY3JlYXRlRWxlbWVudE5TKFwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIixcImdcIikpKSB7XG5cbihmdW5jdGlvbiAodmlldykge1xuXG5cInVzZSBzdHJpY3RcIjtcblxuaWYgKCEoJ0VsZW1lbnQnIGluIHZpZXcpKSByZXR1cm47XG5cbnZhclxuXHQgIGNsYXNzTGlzdFByb3AgPSBcImNsYXNzTGlzdFwiXG5cdCwgcHJvdG9Qcm9wID0gXCJwcm90b3R5cGVcIlxuXHQsIGVsZW1DdHJQcm90byA9IHZpZXcuRWxlbWVudFtwcm90b1Byb3BdXG5cdCwgb2JqQ3RyID0gT2JqZWN0XG5cdCwgc3RyVHJpbSA9IFN0cmluZ1twcm90b1Byb3BdLnRyaW0gfHwgZnVuY3Rpb24gKCkge1xuXHRcdHJldHVybiB0aGlzLnJlcGxhY2UoL15cXHMrfFxccyskL2csIFwiXCIpO1xuXHR9XG5cdCwgYXJySW5kZXhPZiA9IEFycmF5W3Byb3RvUHJvcF0uaW5kZXhPZiB8fCBmdW5jdGlvbiAoaXRlbSkge1xuXHRcdHZhclxuXHRcdFx0ICBpID0gMFxuXHRcdFx0LCBsZW4gPSB0aGlzLmxlbmd0aFxuXHRcdDtcblx0XHRmb3IgKDsgaSA8IGxlbjsgaSsrKSB7XG5cdFx0XHRpZiAoaSBpbiB0aGlzICYmIHRoaXNbaV0gPT09IGl0ZW0pIHtcblx0XHRcdFx0cmV0dXJuIGk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybiAtMTtcblx0fVxuXHQvLyBWZW5kb3JzOiBwbGVhc2UgYWxsb3cgY29udGVudCBjb2RlIHRvIGluc3RhbnRpYXRlIERPTUV4Y2VwdGlvbnNcblx0LCBET01FeCA9IGZ1bmN0aW9uICh0eXBlLCBtZXNzYWdlKSB7XG5cdFx0dGhpcy5uYW1lID0gdHlwZTtcblx0XHR0aGlzLmNvZGUgPSBET01FeGNlcHRpb25bdHlwZV07XG5cdFx0dGhpcy5tZXNzYWdlID0gbWVzc2FnZTtcblx0fVxuXHQsIGNoZWNrVG9rZW5BbmRHZXRJbmRleCA9IGZ1bmN0aW9uIChjbGFzc0xpc3QsIHRva2VuKSB7XG5cdFx0aWYgKHRva2VuID09PSBcIlwiKSB7XG5cdFx0XHR0aHJvdyBuZXcgRE9NRXgoXG5cdFx0XHRcdCAgXCJTWU5UQVhfRVJSXCJcblx0XHRcdFx0LCBcIkFuIGludmFsaWQgb3IgaWxsZWdhbCBzdHJpbmcgd2FzIHNwZWNpZmllZFwiXG5cdFx0XHQpO1xuXHRcdH1cblx0XHRpZiAoL1xccy8udGVzdCh0b2tlbikpIHtcblx0XHRcdHRocm93IG5ldyBET01FeChcblx0XHRcdFx0ICBcIklOVkFMSURfQ0hBUkFDVEVSX0VSUlwiXG5cdFx0XHRcdCwgXCJTdHJpbmcgY29udGFpbnMgYW4gaW52YWxpZCBjaGFyYWN0ZXJcIlxuXHRcdFx0KTtcblx0XHR9XG5cdFx0cmV0dXJuIGFyckluZGV4T2YuY2FsbChjbGFzc0xpc3QsIHRva2VuKTtcblx0fVxuXHQsIENsYXNzTGlzdCA9IGZ1bmN0aW9uIChlbGVtKSB7XG5cdFx0dmFyXG5cdFx0XHQgIHRyaW1tZWRDbGFzc2VzID0gc3RyVHJpbS5jYWxsKGVsZW0uZ2V0QXR0cmlidXRlKFwiY2xhc3NcIikgfHwgXCJcIilcblx0XHRcdCwgY2xhc3NlcyA9IHRyaW1tZWRDbGFzc2VzID8gdHJpbW1lZENsYXNzZXMuc3BsaXQoL1xccysvKSA6IFtdXG5cdFx0XHQsIGkgPSAwXG5cdFx0XHQsIGxlbiA9IGNsYXNzZXMubGVuZ3RoXG5cdFx0O1xuXHRcdGZvciAoOyBpIDwgbGVuOyBpKyspIHtcblx0XHRcdHRoaXMucHVzaChjbGFzc2VzW2ldKTtcblx0XHR9XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lID0gZnVuY3Rpb24gKCkge1xuXHRcdFx0ZWxlbS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCB0aGlzLnRvU3RyaW5nKCkpO1xuXHRcdH07XG5cdH1cblx0LCBjbGFzc0xpc3RQcm90byA9IENsYXNzTGlzdFtwcm90b1Byb3BdID0gW11cblx0LCBjbGFzc0xpc3RHZXR0ZXIgPSBmdW5jdGlvbiAoKSB7XG5cdFx0cmV0dXJuIG5ldyBDbGFzc0xpc3QodGhpcyk7XG5cdH1cbjtcbi8vIE1vc3QgRE9NRXhjZXB0aW9uIGltcGxlbWVudGF0aW9ucyBkb24ndCBhbGxvdyBjYWxsaW5nIERPTUV4Y2VwdGlvbidzIHRvU3RyaW5nKClcbi8vIG9uIG5vbi1ET01FeGNlcHRpb25zLiBFcnJvcidzIHRvU3RyaW5nKCkgaXMgc3VmZmljaWVudCBoZXJlLlxuRE9NRXhbcHJvdG9Qcm9wXSA9IEVycm9yW3Byb3RvUHJvcF07XG5jbGFzc0xpc3RQcm90by5pdGVtID0gZnVuY3Rpb24gKGkpIHtcblx0cmV0dXJuIHRoaXNbaV0gfHwgbnVsbDtcbn07XG5jbGFzc0xpc3RQcm90by5jb250YWlucyA9IGZ1bmN0aW9uICh0b2tlbikge1xuXHR0b2tlbiArPSBcIlwiO1xuXHRyZXR1cm4gY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKSAhPT0gLTE7XG59O1xuY2xhc3NMaXN0UHJvdG8uYWRkID0gZnVuY3Rpb24gKCkge1xuXHR2YXJcblx0XHQgIHRva2VucyA9IGFyZ3VtZW50c1xuXHRcdCwgaSA9IDBcblx0XHQsIGwgPSB0b2tlbnMubGVuZ3RoXG5cdFx0LCB0b2tlblxuXHRcdCwgdXBkYXRlZCA9IGZhbHNlXG5cdDtcblx0ZG8ge1xuXHRcdHRva2VuID0gdG9rZW5zW2ldICsgXCJcIjtcblx0XHRpZiAoY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKSA9PT0gLTEpIHtcblx0XHRcdHRoaXMucHVzaCh0b2tlbik7XG5cdFx0XHR1cGRhdGVkID0gdHJ1ZTtcblx0XHR9XG5cdH1cblx0d2hpbGUgKCsraSA8IGwpO1xuXG5cdGlmICh1cGRhdGVkKSB7XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lKCk7XG5cdH1cbn07XG5jbGFzc0xpc3RQcm90by5yZW1vdmUgPSBmdW5jdGlvbiAoKSB7XG5cdHZhclxuXHRcdCAgdG9rZW5zID0gYXJndW1lbnRzXG5cdFx0LCBpID0gMFxuXHRcdCwgbCA9IHRva2Vucy5sZW5ndGhcblx0XHQsIHRva2VuXG5cdFx0LCB1cGRhdGVkID0gZmFsc2Vcblx0XHQsIGluZGV4XG5cdDtcblx0ZG8ge1xuXHRcdHRva2VuID0gdG9rZW5zW2ldICsgXCJcIjtcblx0XHRpbmRleCA9IGNoZWNrVG9rZW5BbmRHZXRJbmRleCh0aGlzLCB0b2tlbik7XG5cdFx0d2hpbGUgKGluZGV4ICE9PSAtMSkge1xuXHRcdFx0dGhpcy5zcGxpY2UoaW5kZXgsIDEpO1xuXHRcdFx0dXBkYXRlZCA9IHRydWU7XG5cdFx0XHRpbmRleCA9IGNoZWNrVG9rZW5BbmRHZXRJbmRleCh0aGlzLCB0b2tlbik7XG5cdFx0fVxuXHR9XG5cdHdoaWxlICgrK2kgPCBsKTtcblxuXHRpZiAodXBkYXRlZCkge1xuXHRcdHRoaXMuX3VwZGF0ZUNsYXNzTmFtZSgpO1xuXHR9XG59O1xuY2xhc3NMaXN0UHJvdG8udG9nZ2xlID0gZnVuY3Rpb24gKHRva2VuLCBmb3JjZSkge1xuXHR0b2tlbiArPSBcIlwiO1xuXG5cdHZhclxuXHRcdCAgcmVzdWx0ID0gdGhpcy5jb250YWlucyh0b2tlbilcblx0XHQsIG1ldGhvZCA9IHJlc3VsdCA/XG5cdFx0XHRmb3JjZSAhPT0gdHJ1ZSAmJiBcInJlbW92ZVwiXG5cdFx0OlxuXHRcdFx0Zm9yY2UgIT09IGZhbHNlICYmIFwiYWRkXCJcblx0O1xuXG5cdGlmIChtZXRob2QpIHtcblx0XHR0aGlzW21ldGhvZF0odG9rZW4pO1xuXHR9XG5cblx0aWYgKGZvcmNlID09PSB0cnVlIHx8IGZvcmNlID09PSBmYWxzZSkge1xuXHRcdHJldHVybiBmb3JjZTtcblx0fSBlbHNlIHtcblx0XHRyZXR1cm4gIXJlc3VsdDtcblx0fVxufTtcbmNsYXNzTGlzdFByb3RvLnRvU3RyaW5nID0gZnVuY3Rpb24gKCkge1xuXHRyZXR1cm4gdGhpcy5qb2luKFwiIFwiKTtcbn07XG5cbmlmIChvYmpDdHIuZGVmaW5lUHJvcGVydHkpIHtcblx0dmFyIGNsYXNzTGlzdFByb3BEZXNjID0ge1xuXHRcdCAgZ2V0OiBjbGFzc0xpc3RHZXR0ZXJcblx0XHQsIGVudW1lcmFibGU6IHRydWVcblx0XHQsIGNvbmZpZ3VyYWJsZTogdHJ1ZVxuXHR9O1xuXHR0cnkge1xuXHRcdG9iakN0ci5kZWZpbmVQcm9wZXJ0eShlbGVtQ3RyUHJvdG8sIGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdFByb3BEZXNjKTtcblx0fSBjYXRjaCAoZXgpIHsgLy8gSUUgOCBkb2Vzbid0IHN1cHBvcnQgZW51bWVyYWJsZTp0cnVlXG5cdFx0Ly8gYWRkaW5nIHVuZGVmaW5lZCB0byBmaWdodCB0aGlzIGlzc3VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbGlncmV5L2NsYXNzTGlzdC5qcy9pc3N1ZXMvMzZcblx0XHQvLyBtb2Rlcm5pZSBJRTgtTVNXNyBtYWNoaW5lIGhhcyBJRTggOC4wLjYwMDEuMTg3MDIgYW5kIGlzIGFmZmVjdGVkXG5cdFx0aWYgKGV4Lm51bWJlciA9PT0gdW5kZWZpbmVkIHx8IGV4Lm51bWJlciA9PT0gLTB4N0ZGNUVDNTQpIHtcblx0XHRcdGNsYXNzTGlzdFByb3BEZXNjLmVudW1lcmFibGUgPSBmYWxzZTtcblx0XHRcdG9iakN0ci5kZWZpbmVQcm9wZXJ0eShlbGVtQ3RyUHJvdG8sIGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdFByb3BEZXNjKTtcblx0XHR9XG5cdH1cbn0gZWxzZSBpZiAob2JqQ3RyW3Byb3RvUHJvcF0uX19kZWZpbmVHZXR0ZXJfXykge1xuXHRlbGVtQ3RyUHJvdG8uX19kZWZpbmVHZXR0ZXJfXyhjbGFzc0xpc3RQcm9wLCBjbGFzc0xpc3RHZXR0ZXIpO1xufVxuXG59KHdpbmRvdy5zZWxmKSk7XG5cbn1cblxuLy8gVGhlcmUgaXMgZnVsbCBvciBwYXJ0aWFsIG5hdGl2ZSBjbGFzc0xpc3Qgc3VwcG9ydCwgc28ganVzdCBjaGVjayBpZiB3ZSBuZWVkXG4vLyB0byBub3JtYWxpemUgdGhlIGFkZC9yZW1vdmUgYW5kIHRvZ2dsZSBBUElzLlxuXG4oZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHR2YXIgdGVzdEVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiX1wiKTtcblxuXHR0ZXN0RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiYzFcIiwgXCJjMlwiKTtcblxuXHQvLyBQb2x5ZmlsbCBmb3IgSUUgMTAvMTEgYW5kIEZpcmVmb3ggPDI2LCB3aGVyZSBjbGFzc0xpc3QuYWRkIGFuZFxuXHQvLyBjbGFzc0xpc3QucmVtb3ZlIGV4aXN0IGJ1dCBzdXBwb3J0IG9ubHkgb25lIGFyZ3VtZW50IGF0IGEgdGltZS5cblx0aWYgKCF0ZXN0RWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoXCJjMlwiKSkge1xuXHRcdHZhciBjcmVhdGVNZXRob2QgPSBmdW5jdGlvbihtZXRob2QpIHtcblx0XHRcdHZhciBvcmlnaW5hbCA9IERPTVRva2VuTGlzdC5wcm90b3R5cGVbbWV0aG9kXTtcblxuXHRcdFx0RE9NVG9rZW5MaXN0LnByb3RvdHlwZVttZXRob2RdID0gZnVuY3Rpb24odG9rZW4pIHtcblx0XHRcdFx0dmFyIGksIGxlbiA9IGFyZ3VtZW50cy5sZW5ndGg7XG5cblx0XHRcdFx0Zm9yIChpID0gMDsgaSA8IGxlbjsgaSsrKSB7XG5cdFx0XHRcdFx0dG9rZW4gPSBhcmd1bWVudHNbaV07XG5cdFx0XHRcdFx0b3JpZ2luYWwuY2FsbCh0aGlzLCB0b2tlbik7XG5cdFx0XHRcdH1cblx0XHRcdH07XG5cdFx0fTtcblx0XHRjcmVhdGVNZXRob2QoJ2FkZCcpO1xuXHRcdGNyZWF0ZU1ldGhvZCgncmVtb3ZlJyk7XG5cdH1cblxuXHR0ZXN0RWxlbWVudC5jbGFzc0xpc3QudG9nZ2xlKFwiYzNcIiwgZmFsc2UpO1xuXG5cdC8vIFBvbHlmaWxsIGZvciBJRSAxMCBhbmQgRmlyZWZveCA8MjQsIHdoZXJlIGNsYXNzTGlzdC50b2dnbGUgZG9lcyBub3Rcblx0Ly8gc3VwcG9ydCB0aGUgc2Vjb25kIGFyZ3VtZW50LlxuXHRpZiAodGVzdEVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKFwiYzNcIikpIHtcblx0XHR2YXIgX3RvZ2dsZSA9IERPTVRva2VuTGlzdC5wcm90b3R5cGUudG9nZ2xlO1xuXG5cdFx0RE9NVG9rZW5MaXN0LnByb3RvdHlwZS50b2dnbGUgPSBmdW5jdGlvbih0b2tlbiwgZm9yY2UpIHtcblx0XHRcdGlmICgxIGluIGFyZ3VtZW50cyAmJiAhdGhpcy5jb250YWlucyh0b2tlbikgPT09ICFmb3JjZSkge1xuXHRcdFx0XHRyZXR1cm4gZm9yY2U7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRyZXR1cm4gX3RvZ2dsZS5jYWxsKHRoaXMsIHRva2VuKTtcblx0XHRcdH1cblx0XHR9O1xuXG5cdH1cblxuXHR0ZXN0RWxlbWVudCA9IG51bGw7XG59KCkpO1xuXG59XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qISBzbW9vdGgtc2Nyb2xsIHYxNC4yLjAgfCAoYykgMjAxOCBDaHJpcyBGZXJkaW5hbmRpIHwgTUlUIExpY2Vuc2UgfCBodHRwOi8vZ2l0aHViLmNvbS9jZmVyZGluYW5kaS9zbW9vdGgtc2Nyb2xsICovXG4hKGZ1bmN0aW9uKGUsdCl7XCJmdW5jdGlvblwiPT10eXBlb2YgZGVmaW5lJiZkZWZpbmUuYW1kP2RlZmluZShbXSwoZnVuY3Rpb24oKXtyZXR1cm4gdChlKX0pKTpcIm9iamVjdFwiPT10eXBlb2YgZXhwb3J0cz9tb2R1bGUuZXhwb3J0cz10KGUpOmUuU21vb3RoU2Nyb2xsPXQoZSl9KShcInVuZGVmaW5lZFwiIT10eXBlb2YgZ2xvYmFsP2dsb2JhbDpcInVuZGVmaW5lZFwiIT10eXBlb2Ygd2luZG93P3dpbmRvdzp0aGlzLChmdW5jdGlvbihlKXtcInVzZSBzdHJpY3RcIjt2YXIgdD17aWdub3JlOlwiW2RhdGEtc2Nyb2xsLWlnbm9yZV1cIixoZWFkZXI6bnVsbCx0b3BPbkVtcHR5SGFzaDohMCxzcGVlZDo1MDAsY2xpcDohMCxvZmZzZXQ6MCxlYXNpbmc6XCJlYXNlSW5PdXRDdWJpY1wiLGN1c3RvbUVhc2luZzpudWxsLHVwZGF0ZVVSTDohMCxwb3BzdGF0ZTohMCxlbWl0RXZlbnRzOiEwfSxuPWZ1bmN0aW9uKCl7cmV0dXJuXCJxdWVyeVNlbGVjdG9yXCJpbiBkb2N1bWVudCYmXCJhZGRFdmVudExpc3RlbmVyXCJpbiBlJiZcInJlcXVlc3RBbmltYXRpb25GcmFtZVwiaW4gZSYmXCJjbG9zZXN0XCJpbiBlLkVsZW1lbnQucHJvdG90eXBlfSxvPWZ1bmN0aW9uKCl7Zm9yKHZhciBlPXt9LHQ9MDt0PGFyZ3VtZW50cy5sZW5ndGg7dCsrKSEoZnVuY3Rpb24odCl7Zm9yKHZhciBuIGluIHQpdC5oYXNPd25Qcm9wZXJ0eShuKSYmKGVbbl09dFtuXSl9KShhcmd1bWVudHNbdF0pO3JldHVybiBlfSxyPWZ1bmN0aW9uKHQpe3JldHVybiEhKFwibWF0Y2hNZWRpYVwiaW4gZSYmZS5tYXRjaE1lZGlhKFwiKHByZWZlcnMtcmVkdWNlZC1tb3Rpb24pXCIpLm1hdGNoZXMpfSxhPWZ1bmN0aW9uKHQpe3JldHVybiBwYXJzZUludChlLmdldENvbXB1dGVkU3R5bGUodCkuaGVpZ2h0LDEwKX0saT1mdW5jdGlvbihlKXt2YXIgdDt0cnl7dD1kZWNvZGVVUklDb21wb25lbnQoZSl9Y2F0Y2gobil7dD1lfXJldHVybiB0fSxjPWZ1bmN0aW9uKGUpe1wiI1wiPT09ZS5jaGFyQXQoMCkmJihlPWUuc3Vic3RyKDEpKTtmb3IodmFyIHQsbj1TdHJpbmcoZSksbz1uLmxlbmd0aCxyPS0xLGE9XCJcIixpPW4uY2hhckNvZGVBdCgwKTsrK3I8bzspe2lmKDA9PT0odD1uLmNoYXJDb2RlQXQocikpKXRocm93IG5ldyBJbnZhbGlkQ2hhcmFjdGVyRXJyb3IoXCJJbnZhbGlkIGNoYXJhY3RlcjogdGhlIGlucHV0IGNvbnRhaW5zIFUrMDAwMC5cIik7dD49MSYmdDw9MzF8fDEyNz09dHx8MD09PXImJnQ+PTQ4JiZ0PD01N3x8MT09PXImJnQ+PTQ4JiZ0PD01NyYmNDU9PT1pP2ErPVwiXFxcXFwiK3QudG9TdHJpbmcoMTYpK1wiIFwiOmErPXQ+PTEyOHx8NDU9PT10fHw5NT09PXR8fHQ+PTQ4JiZ0PD01N3x8dD49NjUmJnQ8PTkwfHx0Pj05NyYmdDw9MTIyP24uY2hhckF0KHIpOlwiXFxcXFwiK24uY2hhckF0KHIpfXZhciBjO3RyeXtjPWRlY29kZVVSSUNvbXBvbmVudChcIiNcIithKX1jYXRjaChlKXtjPVwiI1wiK2F9cmV0dXJuIGN9LHU9ZnVuY3Rpb24oZSx0KXt2YXIgbjtyZXR1cm5cImVhc2VJblF1YWRcIj09PWUuZWFzaW5nJiYobj10KnQpLFwiZWFzZU91dFF1YWRcIj09PWUuZWFzaW5nJiYobj10KigyLXQpKSxcImVhc2VJbk91dFF1YWRcIj09PWUuZWFzaW5nJiYobj10PC41PzIqdCp0Oig0LTIqdCkqdC0xKSxcImVhc2VJbkN1YmljXCI9PT1lLmVhc2luZyYmKG49dCp0KnQpLFwiZWFzZU91dEN1YmljXCI9PT1lLmVhc2luZyYmKG49LS10KnQqdCsxKSxcImVhc2VJbk91dEN1YmljXCI9PT1lLmVhc2luZyYmKG49dDwuNT80KnQqdCp0Oih0LTEpKigyKnQtMikqKDIqdC0yKSsxKSxcImVhc2VJblF1YXJ0XCI9PT1lLmVhc2luZyYmKG49dCp0KnQqdCksXCJlYXNlT3V0UXVhcnRcIj09PWUuZWFzaW5nJiYobj0xLSAtLXQqdCp0KnQpLFwiZWFzZUluT3V0UXVhcnRcIj09PWUuZWFzaW5nJiYobj10PC41PzgqdCp0KnQqdDoxLTgqLS10KnQqdCp0KSxcImVhc2VJblF1aW50XCI9PT1lLmVhc2luZyYmKG49dCp0KnQqdCp0KSxcImVhc2VPdXRRdWludFwiPT09ZS5lYXNpbmcmJihuPTErLS10KnQqdCp0KnQpLFwiZWFzZUluT3V0UXVpbnRcIj09PWUuZWFzaW5nJiYobj10PC41PzE2KnQqdCp0KnQqdDoxKzE2Ki0tdCp0KnQqdCp0KSxlLmN1c3RvbUVhc2luZyYmKG49ZS5jdXN0b21FYXNpbmcodCkpLG58fHR9LHM9ZnVuY3Rpb24oKXtyZXR1cm4gTWF0aC5tYXgoZG9jdW1lbnQuYm9keS5zY3JvbGxIZWlnaHQsZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbEhlaWdodCxkb2N1bWVudC5ib2R5Lm9mZnNldEhlaWdodCxkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQub2Zmc2V0SGVpZ2h0LGRvY3VtZW50LmJvZHkuY2xpZW50SGVpZ2h0LGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRIZWlnaHQpfSxsPWZ1bmN0aW9uKHQsbixvLHIpe3ZhciBhPTA7aWYodC5vZmZzZXRQYXJlbnQpZG97YSs9dC5vZmZzZXRUb3AsdD10Lm9mZnNldFBhcmVudH13aGlsZSh0KTtyZXR1cm4gYT1NYXRoLm1heChhLW4tbywwKSxyJiYoYT1NYXRoLm1pbihhLHMoKS1lLmlubmVySGVpZ2h0KSksYX0sZD1mdW5jdGlvbihlKXtyZXR1cm4gZT9hKGUpK2Uub2Zmc2V0VG9wOjB9LGY9ZnVuY3Rpb24oZSx0LG4pe3R8fGhpc3RvcnkucHVzaFN0YXRlJiZuLnVwZGF0ZVVSTCYmaGlzdG9yeS5wdXNoU3RhdGUoe3Ntb290aFNjcm9sbDpKU09OLnN0cmluZ2lmeShuKSxhbmNob3I6ZS5pZH0sZG9jdW1lbnQudGl0bGUsZT09PWRvY3VtZW50LmRvY3VtZW50RWxlbWVudD9cIiN0b3BcIjpcIiNcIitlLmlkKX0sbT1mdW5jdGlvbih0LG4sbyl7MD09PXQmJmRvY3VtZW50LmJvZHkuZm9jdXMoKSxvfHwodC5mb2N1cygpLGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQhPT10JiYodC5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLFwiLTFcIiksdC5mb2N1cygpLHQuc3R5bGUub3V0bGluZT1cIm5vbmVcIiksZS5zY3JvbGxUbygwLG4pKX0saD1mdW5jdGlvbih0LG4sbyxyKXtpZihuLmVtaXRFdmVudHMmJlwiZnVuY3Rpb25cIj09dHlwZW9mIGUuQ3VzdG9tRXZlbnQpe3ZhciBhPW5ldyBDdXN0b21FdmVudCh0LHtidWJibGVzOiEwLGRldGFpbDp7YW5jaG9yOm8sdG9nZ2xlOnJ9fSk7ZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChhKX19O3JldHVybiBmdW5jdGlvbihhLHApe3ZhciBnLHYseSxTLEUsYixPLEk9e307SS5jYW5jZWxTY3JvbGw9ZnVuY3Rpb24oZSl7Y2FuY2VsQW5pbWF0aW9uRnJhbWUoTyksTz1udWxsLGV8fGgoXCJzY3JvbGxDYW5jZWxcIixnKX0sSS5hbmltYXRlU2Nyb2xsPWZ1bmN0aW9uKG4scixhKXt2YXIgaT1vKGd8fHQsYXx8e30pLGM9XCJbb2JqZWN0IE51bWJlcl1cIj09PU9iamVjdC5wcm90b3R5cGUudG9TdHJpbmcuY2FsbChuKSxwPWN8fCFuLnRhZ05hbWU/bnVsbDpuO2lmKGN8fHApe3ZhciB2PWUucGFnZVlPZmZzZXQ7aS5oZWFkZXImJiFTJiYoUz1kb2N1bWVudC5xdWVyeVNlbGVjdG9yKGkuaGVhZGVyKSksRXx8KEU9ZChTKSk7dmFyIHksYixDLHc9Yz9uOmwocCxFLHBhcnNlSW50KFwiZnVuY3Rpb25cIj09dHlwZW9mIGkub2Zmc2V0P2kub2Zmc2V0KG4scik6aS5vZmZzZXQsMTApLGkuY2xpcCksTD13LXYsQT1zKCksSD0wLHE9ZnVuY3Rpb24odCxvKXt2YXIgYT1lLnBhZ2VZT2Zmc2V0O2lmKHQ9PW98fGE9PW98fCh2PG8mJmUuaW5uZXJIZWlnaHQrYSk+PUEpcmV0dXJuIEkuY2FuY2VsU2Nyb2xsKCEwKSxtKG4sbyxjKSxoKFwic2Nyb2xsU3RvcFwiLGksbixyKSx5PW51bGwsTz1udWxsLCEwfSxRPWZ1bmN0aW9uKHQpe3l8fCh5PXQpLEgrPXQteSxiPUgvcGFyc2VJbnQoaS5zcGVlZCwxMCksYj1iPjE/MTpiLEM9ditMKnUoaSxiKSxlLnNjcm9sbFRvKDAsTWF0aC5mbG9vcihDKSkscShDLHcpfHwoTz1lLnJlcXVlc3RBbmltYXRpb25GcmFtZShRKSx5PXQpfTswPT09ZS5wYWdlWU9mZnNldCYmZS5zY3JvbGxUbygwLDApLGYobixjLGkpLGgoXCJzY3JvbGxTdGFydFwiLGksbixyKSxJLmNhbmNlbFNjcm9sbCghMCksZS5yZXF1ZXN0QW5pbWF0aW9uRnJhbWUoUSl9fTt2YXIgQz1mdW5jdGlvbih0KXtpZighcigpJiYwPT09dC5idXR0b24mJiF0Lm1ldGFLZXkmJiF0LmN0cmxLZXkmJlwiY2xvc2VzdFwiaW4gdC50YXJnZXQmJih5PXQudGFyZ2V0LmNsb3Nlc3QoYSkpJiZcImFcIj09PXkudGFnTmFtZS50b0xvd2VyQ2FzZSgpJiYhdC50YXJnZXQuY2xvc2VzdChnLmlnbm9yZSkmJnkuaG9zdG5hbWU9PT1lLmxvY2F0aW9uLmhvc3RuYW1lJiZ5LnBhdGhuYW1lPT09ZS5sb2NhdGlvbi5wYXRobmFtZSYmLyMvLnRlc3QoeS5ocmVmKSl7dmFyIG49YyhpKHkuaGFzaCkpLG89Zy50b3BPbkVtcHR5SGFzaCYmXCIjXCI9PT1uP2RvY3VtZW50LmRvY3VtZW50RWxlbWVudDpkb2N1bWVudC5xdWVyeVNlbGVjdG9yKG4pO289b3x8XCIjdG9wXCIhPT1uP286ZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LG8mJih0LnByZXZlbnREZWZhdWx0KCksSS5hbmltYXRlU2Nyb2xsKG8seSkpfX0sdz1mdW5jdGlvbihlKXtpZihoaXN0b3J5LnN0YXRlLnNtb290aFNjcm9sbCYmaGlzdG9yeS5zdGF0ZS5zbW9vdGhTY3JvbGw9PT1KU09OLnN0cmluZ2lmeShnKSYmaGlzdG9yeS5zdGF0ZS5hbmNob3Ipe3ZhciB0PWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoYyhpKGhpc3Rvcnkuc3RhdGUuYW5jaG9yKSkpO3QmJkkuYW5pbWF0ZVNjcm9sbCh0LG51bGwse3VwZGF0ZVVSTDohMX0pfX0sTD1mdW5jdGlvbihlKXtifHwoYj1zZXRUaW1lb3V0KChmdW5jdGlvbigpe2I9bnVsbCxFPWQoUyl9KSw2NikpfTtyZXR1cm4gSS5kZXN0cm95PWZ1bmN0aW9uKCl7ZyYmKGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLEMsITEpLGUucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLEwsITEpLGUucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInBvcHN0YXRlXCIsdywhMSksSS5jYW5jZWxTY3JvbGwoKSxnPW51bGwsdj1udWxsLHk9bnVsbCxTPW51bGwsRT1udWxsLGI9bnVsbCxPPW51bGwpfSxJLmluaXQ9ZnVuY3Rpb24ocil7aWYoIW4oKSl0aHJvd1wiU21vb3RoIFNjcm9sbDogVGhpcyBicm93c2VyIGRvZXMgbm90IHN1cHBvcnQgdGhlIHJlcXVpcmVkIEphdmFTY3JpcHQgbWV0aG9kcyBhbmQgYnJvd3NlciBBUElzLlwiO0kuZGVzdHJveSgpLGc9byh0LHJ8fHt9KSxTPWcuaGVhZGVyP2RvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZy5oZWFkZXIpOm51bGwsRT1kKFMpLGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLEMsITEpLFMmJmUuYWRkRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLEwsITEpLGcudXBkYXRlVVJMJiZnLnBvcHN0YXRlJiZlLmFkZEV2ZW50TGlzdGVuZXIoXCJwb3BzdGF0ZVwiLHcsITEpfSxJLmluaXQocCksSX19KSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvc21vb3RoLXNjcm9sbC9kaXN0L3Ntb290aC1zY3JvbGwubWluLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9zbW9vdGgtc2Nyb2xsL2Rpc3Qvc21vb3RoLXNjcm9sbC5taW4uanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLyohXHJcbiAgKiBTdGlja3lmaWxsIOKAkyBgcG9zaXRpb246IHN0aWNreWAgcG9seWZpbGxcclxuICAqIHYuIDIuMC41IHwgaHR0cHM6Ly9naXRodWIuY29tL3dpbGRkZWVyL3N0aWNreWZpbGxcclxuICAqIE1JVCBMaWNlbnNlXHJcbiAgKi9cclxuXHJcbjsoZnVuY3Rpb24od2luZG93LCBkb2N1bWVudCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIC8qXHJcbiAgICAgKiAxLiBDaGVjayBpZiB0aGUgYnJvd3NlciBzdXBwb3J0cyBgcG9zaXRpb246IHN0aWNreWAgbmF0aXZlbHkgb3IgaXMgdG9vIG9sZCB0byBydW4gdGhlIHBvbHlmaWxsLlxyXG4gICAgICogICAgSWYgZWl0aGVyIG9mIHRoZXNlIGlzIHRoZSBjYXNlIHNldCBgc2VwcHVrdWAgZmxhZy4gSXQgd2lsbCBiZSBjaGVja2VkIGxhdGVyIHRvIGRpc2FibGUga2V5IGZlYXR1cmVzXHJcbiAgICAgKiAgICBvZiB0aGUgcG9seWZpbGwsIGJ1dCB0aGUgQVBJIHdpbGwgcmVtYWluIGZ1bmN0aW9uYWwgdG8gYXZvaWQgYnJlYWtpbmcgdGhpbmdzLlxyXG4gICAgICovXHJcblxyXG4gICAgdmFyIF9jcmVhdGVDbGFzcyA9IGZ1bmN0aW9uICgpIHsgZnVuY3Rpb24gZGVmaW5lUHJvcGVydGllcyh0YXJnZXQsIHByb3BzKSB7IGZvciAodmFyIGkgPSAwOyBpIDwgcHJvcHMubGVuZ3RoOyBpKyspIHsgdmFyIGRlc2NyaXB0b3IgPSBwcm9wc1tpXTsgZGVzY3JpcHRvci5lbnVtZXJhYmxlID0gZGVzY3JpcHRvci5lbnVtZXJhYmxlIHx8IGZhbHNlOyBkZXNjcmlwdG9yLmNvbmZpZ3VyYWJsZSA9IHRydWU7IGlmIChcInZhbHVlXCIgaW4gZGVzY3JpcHRvcikgZGVzY3JpcHRvci53cml0YWJsZSA9IHRydWU7IE9iamVjdC5kZWZpbmVQcm9wZXJ0eSh0YXJnZXQsIGRlc2NyaXB0b3Iua2V5LCBkZXNjcmlwdG9yKTsgfSB9IHJldHVybiBmdW5jdGlvbiAoQ29uc3RydWN0b3IsIHByb3RvUHJvcHMsIHN0YXRpY1Byb3BzKSB7IGlmIChwcm90b1Byb3BzKSBkZWZpbmVQcm9wZXJ0aWVzKENvbnN0cnVjdG9yLnByb3RvdHlwZSwgcHJvdG9Qcm9wcyk7IGlmIChzdGF0aWNQcm9wcykgZGVmaW5lUHJvcGVydGllcyhDb25zdHJ1Y3Rvciwgc3RhdGljUHJvcHMpOyByZXR1cm4gQ29uc3RydWN0b3I7IH07IH0oKTtcclxuXHJcbiAgICBmdW5jdGlvbiBfY2xhc3NDYWxsQ2hlY2soaW5zdGFuY2UsIENvbnN0cnVjdG9yKSB7IGlmICghKGluc3RhbmNlIGluc3RhbmNlb2YgQ29uc3RydWN0b3IpKSB7IHRocm93IG5ldyBUeXBlRXJyb3IoXCJDYW5ub3QgY2FsbCBhIGNsYXNzIGFzIGEgZnVuY3Rpb25cIik7IH0gfVxyXG5cclxuICAgIHZhciBzZXBwdWt1ID0gZmFsc2U7XHJcblxyXG4gICAgLy8gVGhlIHBvbHlmaWxsIGNhbnTigJl0IGZ1bmN0aW9uIHByb3Blcmx5IHdpdGhvdXQgYGdldENvbXB1dGVkU3R5bGVgLlxyXG4gICAgaWYgKCF3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZSkgc2VwcHVrdSA9IHRydWU7XHJcbiAgICAvLyBEb2504oCZdCBnZXQgaW4gYSB3YXkgaWYgdGhlIGJyb3dzZXIgc3VwcG9ydHMgYHBvc2l0aW9uOiBzdGlja3lgIG5hdGl2ZWx5LlxyXG4gICAgZWxzZSB7XHJcbiAgICAgICAgICAgIHZhciB0ZXN0Tm9kZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG5cclxuICAgICAgICAgICAgaWYgKFsnJywgJy13ZWJraXQtJywgJy1tb3otJywgJy1tcy0nXS5zb21lKGZ1bmN0aW9uIChwcmVmaXgpIHtcclxuICAgICAgICAgICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGVzdE5vZGUuc3R5bGUucG9zaXRpb24gPSBwcmVmaXggKyAnc3RpY2t5JztcclxuICAgICAgICAgICAgICAgIH0gY2F0Y2ggKGUpIHt9XHJcblxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHRlc3ROb2RlLnN0eWxlLnBvc2l0aW9uICE9ICcnO1xyXG4gICAgICAgICAgICB9KSkgc2VwcHVrdSA9IHRydWU7XHJcbiAgICAgICAgfVxyXG5cclxuICAgIC8qXHJcbiAgICAgKiAyLiDigJxHbG9iYWzigJ0gdmFycyB1c2VkIGFjcm9zcyB0aGUgcG9seWZpbGxcclxuICAgICAqL1xyXG5cclxuICAgIC8vIENoZWNrIGlmIFNoYWRvdyBSb290IGNvbnN0cnVjdG9yIGV4aXN0cyB0byBtYWtlIGZ1cnRoZXIgY2hlY2tzIHNpbXBsZXJcclxuICAgIHZhciBzaGFkb3dSb290RXhpc3RzID0gdHlwZW9mIFNoYWRvd1Jvb3QgIT09ICd1bmRlZmluZWQnO1xyXG5cclxuICAgIC8vIExhc3Qgc2F2ZWQgc2Nyb2xsIHBvc2l0aW9uXHJcbiAgICB2YXIgc2Nyb2xsID0ge1xyXG4gICAgICAgIHRvcDogbnVsbCxcclxuICAgICAgICBsZWZ0OiBudWxsXHJcbiAgICB9O1xyXG5cclxuICAgIC8vIEFycmF5IG9mIGNyZWF0ZWQgU3RpY2t5IGluc3RhbmNlc1xyXG4gICAgdmFyIHN0aWNraWVzID0gW107XHJcblxyXG4gICAgLypcclxuICAgICAqIDMuIFV0aWxpdHkgZnVuY3Rpb25zXHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIGV4dGVuZCh0YXJnZXRPYmosIHNvdXJjZU9iamVjdCkge1xyXG4gICAgICAgIGZvciAodmFyIGtleSBpbiBzb3VyY2VPYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKHNvdXJjZU9iamVjdC5oYXNPd25Qcm9wZXJ0eShrZXkpKSB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRPYmpba2V5XSA9IHNvdXJjZU9iamVjdFtrZXldO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIHBhcnNlTnVtZXJpYyh2YWwpIHtcclxuICAgICAgICByZXR1cm4gcGFyc2VGbG9hdCh2YWwpIHx8IDA7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gZ2V0RG9jT2Zmc2V0VG9wKG5vZGUpIHtcclxuICAgICAgICB2YXIgZG9jT2Zmc2V0VG9wID0gMDtcclxuXHJcbiAgICAgICAgd2hpbGUgKG5vZGUpIHtcclxuICAgICAgICAgICAgZG9jT2Zmc2V0VG9wICs9IG5vZGUub2Zmc2V0VG9wO1xyXG4gICAgICAgICAgICBub2RlID0gbm9kZS5vZmZzZXRQYXJlbnQ7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICByZXR1cm4gZG9jT2Zmc2V0VG9wO1xyXG4gICAgfVxyXG5cclxuICAgIC8qXHJcbiAgICAgKiA0LiBTdGlja3kgY2xhc3NcclxuICAgICAqL1xyXG5cclxuICAgIHZhciBTdGlja3kgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgZnVuY3Rpb24gU3RpY2t5KG5vZGUpIHtcclxuICAgICAgICAgICAgX2NsYXNzQ2FsbENoZWNrKHRoaXMsIFN0aWNreSk7XHJcblxyXG4gICAgICAgICAgICBpZiAoIShub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpKSB0aHJvdyBuZXcgRXJyb3IoJ0ZpcnN0IGFyZ3VtZW50IG11c3QgYmUgSFRNTEVsZW1lbnQnKTtcclxuICAgICAgICAgICAgaWYgKHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fbm9kZSA9PT0gbm9kZTtcclxuICAgICAgICAgICAgfSkpIHRocm93IG5ldyBFcnJvcignU3RpY2t5ZmlsbCBpcyBhbHJlYWR5IGFwcGxpZWQgdG8gdGhpcyBub2RlJyk7XHJcblxyXG4gICAgICAgICAgICB0aGlzLl9ub2RlID0gbm9kZTtcclxuICAgICAgICAgICAgdGhpcy5fc3RpY2t5TW9kZSA9IG51bGw7XHJcbiAgICAgICAgICAgIHRoaXMuX2FjdGl2ZSA9IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgc3RpY2tpZXMucHVzaCh0aGlzKTtcclxuXHJcbiAgICAgICAgICAgIHRoaXMucmVmcmVzaCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgX2NyZWF0ZUNsYXNzKFN0aWNreSwgW3tcclxuICAgICAgICAgICAga2V5OiAncmVmcmVzaCcsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiByZWZyZXNoKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHNlcHB1a3UgfHwgdGhpcy5fcmVtb3ZlZCkgcmV0dXJuO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuX2FjdGl2ZSkgdGhpcy5fZGVhY3RpdmF0ZSgpO1xyXG5cclxuICAgICAgICAgICAgICAgIHZhciBub2RlID0gdGhpcy5fbm9kZTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogMS4gU2F2ZSBub2RlIGNvbXB1dGVkIHByb3BzXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHZhciBub2RlQ29tcHV0ZWRTdHlsZSA9IGdldENvbXB1dGVkU3R5bGUobm9kZSk7XHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZUNvbXB1dGVkUHJvcHMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdG9wOiBub2RlQ29tcHV0ZWRTdHlsZS50b3AsXHJcbiAgICAgICAgICAgICAgICAgICAgZGlzcGxheTogbm9kZUNvbXB1dGVkU3R5bGUuZGlzcGxheSxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IG5vZGVDb21wdXRlZFN0eWxlLm1hcmdpblRvcCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Cb3R0b206IG5vZGVDb21wdXRlZFN0eWxlLm1hcmdpbkJvdHRvbSxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5MZWZ0OiBub2RlQ29tcHV0ZWRTdHlsZS5tYXJnaW5MZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiBub2RlQ29tcHV0ZWRTdHlsZS5tYXJnaW5SaWdodCxcclxuICAgICAgICAgICAgICAgICAgICBjc3NGbG9hdDogbm9kZUNvbXB1dGVkU3R5bGUuY3NzRmxvYXRcclxuICAgICAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDIuIENoZWNrIGlmIHRoZSBub2RlIGNhbiBiZSBhY3RpdmF0ZWRcclxuICAgICAgICAgICAgICAgICAqL1xyXG4gICAgICAgICAgICAgICAgaWYgKGlzTmFOKHBhcnNlRmxvYXQobm9kZUNvbXB1dGVkUHJvcHMudG9wKSkgfHwgbm9kZUNvbXB1dGVkUHJvcHMuZGlzcGxheSA9PSAndGFibGUtY2VsbCcgfHwgbm9kZUNvbXB1dGVkUHJvcHMuZGlzcGxheSA9PSAnbm9uZScpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiAzLiBHZXQgbmVjZXNzYXJ5IG5vZGUgcGFyYW1ldGVyc1xyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB2YXIgcmVmZXJlbmNlTm9kZSA9IG5vZGUucGFyZW50Tm9kZTtcclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnROb2RlID0gc2hhZG93Um9vdEV4aXN0cyAmJiByZWZlcmVuY2VOb2RlIGluc3RhbmNlb2YgU2hhZG93Um9vdCA/IHJlZmVyZW5jZU5vZGUuaG9zdCA6IHJlZmVyZW5jZU5vZGU7XHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZVdpbk9mZnNldCA9IG5vZGUuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50V2luT2Zmc2V0ID0gcGFyZW50Tm9kZS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnRDb21wdXRlZFN0eWxlID0gZ2V0Q29tcHV0ZWRTdHlsZShwYXJlbnROb2RlKTtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9wYXJlbnQgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbm9kZTogcGFyZW50Tm9kZSxcclxuICAgICAgICAgICAgICAgICAgICBzdHlsZXM6IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246IHBhcmVudE5vZGUuc3R5bGUucG9zaXRpb25cclxuICAgICAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgICAgIG9mZnNldEhlaWdodDogcGFyZW50Tm9kZS5vZmZzZXRIZWlnaHRcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9vZmZzZXRUb1dpbmRvdyA9IHtcclxuICAgICAgICAgICAgICAgICAgICBsZWZ0OiBub2RlV2luT2Zmc2V0LmxlZnQsXHJcbiAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRXaWR0aCAtIG5vZGVXaW5PZmZzZXQucmlnaHRcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9vZmZzZXRUb1BhcmVudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICB0b3A6IG5vZGVXaW5PZmZzZXQudG9wIC0gcGFyZW50V2luT2Zmc2V0LnRvcCAtIHBhcnNlTnVtZXJpYyhwYXJlbnRDb21wdXRlZFN0eWxlLmJvcmRlclRvcFdpZHRoKSxcclxuICAgICAgICAgICAgICAgICAgICBsZWZ0OiBub2RlV2luT2Zmc2V0LmxlZnQgLSBwYXJlbnRXaW5PZmZzZXQubGVmdCAtIHBhcnNlTnVtZXJpYyhwYXJlbnRDb21wdXRlZFN0eWxlLmJvcmRlckxlZnRXaWR0aCksXHJcbiAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IC1ub2RlV2luT2Zmc2V0LnJpZ2h0ICsgcGFyZW50V2luT2Zmc2V0LnJpZ2h0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyUmlnaHRXaWR0aClcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9zdHlsZXMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246IG5vZGUuc3R5bGUucG9zaXRpb24sXHJcbiAgICAgICAgICAgICAgICAgICAgdG9wOiBub2RlLnN0eWxlLnRvcCxcclxuICAgICAgICAgICAgICAgICAgICBib3R0b206IG5vZGUuc3R5bGUuYm90dG9tLFxyXG4gICAgICAgICAgICAgICAgICAgIGxlZnQ6IG5vZGUuc3R5bGUubGVmdCxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDogbm9kZS5zdHlsZS5yaWdodCxcclxuICAgICAgICAgICAgICAgICAgICB3aWR0aDogbm9kZS5zdHlsZS53aWR0aCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IG5vZGUuc3R5bGUubWFyZ2luVG9wLFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IG5vZGUuc3R5bGUubWFyZ2luTGVmdCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogbm9kZS5zdHlsZS5tYXJnaW5SaWdodFxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZVRvcFZhbHVlID0gcGFyc2VOdW1lcmljKG5vZGVDb21wdXRlZFByb3BzLnRvcCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9saW1pdHMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgc3RhcnQ6IG5vZGVXaW5PZmZzZXQudG9wICsgd2luZG93LnBhZ2VZT2Zmc2V0IC0gbm9kZVRvcFZhbHVlLFxyXG4gICAgICAgICAgICAgICAgICAgIGVuZDogcGFyZW50V2luT2Zmc2V0LnRvcCArIHdpbmRvdy5wYWdlWU9mZnNldCArIHBhcmVudE5vZGUub2Zmc2V0SGVpZ2h0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyQm90dG9tV2lkdGgpIC0gbm9kZS5vZmZzZXRIZWlnaHQgLSBub2RlVG9wVmFsdWUgLSBwYXJzZU51bWVyaWMobm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luQm90dG9tKVxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogNC4gRW5zdXJlIHRoYXQgdGhlIG5vZGUgd2lsbCBiZSBwb3NpdGlvbmVkIHJlbGF0aXZlbHkgdG8gdGhlIHBhcmVudCBub2RlXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnRQb3NpdGlvbiA9IHBhcmVudENvbXB1dGVkU3R5bGUucG9zaXRpb247XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKHBhcmVudFBvc2l0aW9uICE9ICdhYnNvbHV0ZScgJiYgcGFyZW50UG9zaXRpb24gIT0gJ3JlbGF0aXZlJykge1xyXG4gICAgICAgICAgICAgICAgICAgIHBhcmVudE5vZGUuc3R5bGUucG9zaXRpb24gPSAncmVsYXRpdmUnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiA1LiBSZWNhbGMgbm9kZSBwb3NpdGlvbi5cclxuICAgICAgICAgICAgICAgICAqICAgIEl04oCZcyBpbXBvcnRhbnQgdG8gZG8gdGhpcyBiZWZvcmUgY2xvbmUgaW5qZWN0aW9uIHRvIGF2b2lkIHNjcm9sbGluZyBidWcgaW4gQ2hyb21lLlxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9yZWNhbGNQb3NpdGlvbigpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiA2LiBDcmVhdGUgYSBjbG9uZVxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB2YXIgY2xvbmUgPSB0aGlzLl9jbG9uZSA9IHt9O1xyXG4gICAgICAgICAgICAgICAgY2xvbmUubm9kZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIEFwcGx5IHN0eWxlcyB0byB0aGUgY2xvbmVcclxuICAgICAgICAgICAgICAgIGV4dGVuZChjbG9uZS5ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgd2lkdGg6IG5vZGVXaW5PZmZzZXQucmlnaHQgLSBub2RlV2luT2Zmc2V0LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgIGhlaWdodDogbm9kZVdpbk9mZnNldC5ib3R0b20gLSBub2RlV2luT2Zmc2V0LnRvcCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luVG9wOiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5Ub3AsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luQm90dG9tOiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5Cb3R0b20sXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogbm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luTGVmdCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogbm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luUmlnaHQsXHJcbiAgICAgICAgICAgICAgICAgICAgY3NzRmxvYXQ6IG5vZGVDb21wdXRlZFByb3BzLmNzc0Zsb2F0LFxyXG4gICAgICAgICAgICAgICAgICAgIHBhZGRpbmc6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgYm9yZGVyOiAwLFxyXG4gICAgICAgICAgICAgICAgICAgIGJvcmRlclNwYWNpbmc6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgZm9udFNpemU6ICcxZW0nLFxyXG4gICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnc3RhdGljJ1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICAgICAgcmVmZXJlbmNlTm9kZS5pbnNlcnRCZWZvcmUoY2xvbmUubm9kZSwgbm9kZSk7XHJcbiAgICAgICAgICAgICAgICBjbG9uZS5kb2NPZmZzZXRUb3AgPSBnZXREb2NPZmZzZXRUb3AoY2xvbmUubm9kZSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19yZWNhbGNQb3NpdGlvbicsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiBfcmVjYWxjUG9zaXRpb24oKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXRoaXMuX2FjdGl2ZSB8fCB0aGlzLl9yZW1vdmVkKSByZXR1cm47XHJcblxyXG4gICAgICAgICAgICAgICAgdmFyIHN0aWNreU1vZGUgPSBzY3JvbGwudG9wIDw9IHRoaXMuX2xpbWl0cy5zdGFydCA/ICdzdGFydCcgOiBzY3JvbGwudG9wID49IHRoaXMuX2xpbWl0cy5lbmQgPyAnZW5kJyA6ICdtaWRkbGUnO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICh0aGlzLl9zdGlja3lNb2RlID09IHN0aWNreU1vZGUpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICBzd2l0Y2ggKHN0aWNreU1vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICBjYXNlICdzdGFydCc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2Fic29sdXRlJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvUGFyZW50LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvUGFyZW50LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQudG9wICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJvdHRvbTogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ21pZGRsZSc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2ZpeGVkJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvV2luZG93LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvV2luZG93LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogdGhpcy5fc3R5bGVzLnRvcCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJvdHRvbTogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2VuZCc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2Fic29sdXRlJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvUGFyZW50LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvUGFyZW50LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYm90dG9tOiAwLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgdGhpcy5fc3RpY2t5TW9kZSA9IHN0aWNreU1vZGU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19mYXN0Q2hlY2snLFxyXG4gICAgICAgICAgICB2YWx1ZTogZnVuY3Rpb24gX2Zhc3RDaGVjaygpIHtcclxuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fYWN0aXZlIHx8IHRoaXMuX3JlbW92ZWQpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAoTWF0aC5hYnMoZ2V0RG9jT2Zmc2V0VG9wKHRoaXMuX2Nsb25lLm5vZGUpIC0gdGhpcy5fY2xvbmUuZG9jT2Zmc2V0VG9wKSA+IDEgfHwgTWF0aC5hYnModGhpcy5fcGFyZW50Lm5vZGUub2Zmc2V0SGVpZ2h0IC0gdGhpcy5fcGFyZW50Lm9mZnNldEhlaWdodCkgPiAxKSB0aGlzLnJlZnJlc2goKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIHtcclxuICAgICAgICAgICAga2V5OiAnX2RlYWN0aXZhdGUnLFxyXG4gICAgICAgICAgICB2YWx1ZTogZnVuY3Rpb24gX2RlYWN0aXZhdGUoKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgX3RoaXMgPSB0aGlzO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fYWN0aXZlIHx8IHRoaXMuX3JlbW92ZWQpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9jbG9uZS5ub2RlLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5fY2xvbmUubm9kZSk7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fY2xvbmU7XHJcblxyXG4gICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX25vZGUuc3R5bGUsIHRoaXMuX3N0eWxlcyk7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fc3R5bGVzO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIENoZWNrIHdoZXRoZXIgZWxlbWVudOKAmXMgcGFyZW50IG5vZGUgaXMgdXNlZCBieSBvdGhlciBzdGlja2llcy5cclxuICAgICAgICAgICAgICAgIC8vIElmIG5vdCwgcmVzdG9yZSBwYXJlbnQgbm9kZeKAmXMgc3R5bGVzLlxyXG4gICAgICAgICAgICAgICAgaWYgKCFzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc3RpY2t5ICE9PSBfdGhpcyAmJiBzdGlja3kuX3BhcmVudCAmJiBzdGlja3kuX3BhcmVudC5ub2RlID09PSBfdGhpcy5fcGFyZW50Lm5vZGU7XHJcbiAgICAgICAgICAgICAgICB9KSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9wYXJlbnQubm9kZS5zdHlsZSwgdGhpcy5fcGFyZW50LnN0eWxlcyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fcGFyZW50O1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX3N0aWNreU1vZGUgPSBudWxsO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlID0gZmFsc2U7XHJcblxyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX29mZnNldFRvV2luZG93O1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX29mZnNldFRvUGFyZW50O1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX2xpbWl0cztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIHtcclxuICAgICAgICAgICAga2V5OiAncmVtb3ZlJyxcclxuICAgICAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHJlbW92ZSgpIHtcclxuICAgICAgICAgICAgICAgIHZhciBfdGhpczIgPSB0aGlzO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX2RlYWN0aXZhdGUoKTtcclxuXHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3ksIGluZGV4KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHN0aWNreS5fbm9kZSA9PT0gX3RoaXMyLl9ub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0aWNraWVzLnNwbGljZShpbmRleCwgMSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX3JlbW92ZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfV0pO1xyXG5cclxuICAgICAgICByZXR1cm4gU3RpY2t5O1xyXG4gICAgfSgpO1xyXG5cclxuICAgIC8qXHJcbiAgICAgKiA1LiBTdGlja3lmaWxsIEFQSVxyXG4gICAgICovXHJcblxyXG5cclxuICAgIHZhciBTdGlja3lmaWxsID0ge1xyXG4gICAgICAgIHN0aWNraWVzOiBzdGlja2llcyxcclxuICAgICAgICBTdGlja3k6IFN0aWNreSxcclxuXHJcbiAgICAgICAgYWRkT25lOiBmdW5jdGlvbiBhZGRPbmUobm9kZSkge1xyXG4gICAgICAgICAgICAvLyBDaGVjayB3aGV0aGVyIGl04oCZcyBhIG5vZGVcclxuICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkge1xyXG4gICAgICAgICAgICAgICAgLy8gTWF5YmUgaXTigJlzIGEgbm9kZSBsaXN0IG9mIHNvbWUgc29ydD9cclxuICAgICAgICAgICAgICAgIC8vIFRha2UgZmlyc3Qgbm9kZSBmcm9tIHRoZSBsaXN0IHRoZW5cclxuICAgICAgICAgICAgICAgIGlmIChub2RlLmxlbmd0aCAmJiBub2RlWzBdKSBub2RlID0gbm9kZVswXTtlbHNlIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgU3RpY2t5ZmlsbCBpcyBhbHJlYWR5IGFwcGxpZWQgdG8gdGhlIG5vZGVcclxuICAgICAgICAgICAgLy8gYW5kIHJldHVybiBleGlzdGluZyBzdGlja3lcclxuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBzdGlja2llcy5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNraWVzW2ldLl9ub2RlID09PSBub2RlKSByZXR1cm4gc3RpY2tpZXNbaV07XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIC8vIENyZWF0ZSBhbmQgcmV0dXJuIG5ldyBzdGlja3lcclxuICAgICAgICAgICAgcmV0dXJuIG5ldyBTdGlja3kobm9kZSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICBhZGQ6IGZ1bmN0aW9uIGFkZChub2RlTGlzdCkge1xyXG4gICAgICAgICAgICAvLyBJZiBpdOKAmXMgYSBub2RlIG1ha2UgYW4gYXJyYXkgb2Ygb25lIG5vZGVcclxuICAgICAgICAgICAgaWYgKG5vZGVMaXN0IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIG5vZGVMaXN0ID0gW25vZGVMaXN0XTtcclxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgdGhlIGFyZ3VtZW50IGlzIGFuIGl0ZXJhYmxlIG9mIHNvbWUgc29ydFxyXG4gICAgICAgICAgICBpZiAoIW5vZGVMaXN0Lmxlbmd0aCkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgLy8gQWRkIGV2ZXJ5IGVsZW1lbnQgYXMgYSBzdGlja3kgYW5kIHJldHVybiBhbiBhcnJheSBvZiBjcmVhdGVkIFN0aWNreSBpbnN0YW5jZXNcclxuICAgICAgICAgICAgdmFyIGFkZGVkU3RpY2tpZXMgPSBbXTtcclxuXHJcbiAgICAgICAgICAgIHZhciBfbG9vcCA9IGZ1bmN0aW9uIF9sb29wKGkpIHtcclxuICAgICAgICAgICAgICAgIHZhciBub2RlID0gbm9kZUxpc3RbaV07XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gSWYgaXTigJlzIG5vdCBhbiBIVE1MRWxlbWVudCDigJMgY3JlYXRlIGFuIGVtcHR5IGVsZW1lbnQgdG8gcHJlc2VydmUgMS10by0xXHJcbiAgICAgICAgICAgICAgICAvLyBjb3JyZWxhdGlvbiB3aXRoIGlucHV0IGxpc3RcclxuICAgICAgICAgICAgICAgIGlmICghKG5vZGUgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkpIHtcclxuICAgICAgICAgICAgICAgICAgICBhZGRlZFN0aWNraWVzLnB1c2godm9pZCAwKTtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gJ2NvbnRpbnVlJztcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAvLyBJZiBTdGlja3lmaWxsIGlzIGFscmVhZHkgYXBwbGllZCB0byB0aGUgbm9kZVxyXG4gICAgICAgICAgICAgICAgLy8gYWRkIGV4aXN0aW5nIHN0aWNreVxyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChzdGlja3kuX25vZGUgPT09IG5vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYWRkZWRTdGlja2llcy5wdXNoKHN0aWNreSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pKSByZXR1cm4gJ2NvbnRpbnVlJztcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBDcmVhdGUgYW5kIGFkZCBuZXcgc3RpY2t5XHJcbiAgICAgICAgICAgICAgICBhZGRlZFN0aWNraWVzLnB1c2gobmV3IFN0aWNreShub2RlKSk7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG5vZGVMaXN0Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgX3JldCA9IF9sb29wKGkpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChfcmV0ID09PSAnY29udGludWUnKSBjb250aW51ZTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgcmV0dXJuIGFkZGVkU3RpY2tpZXM7XHJcbiAgICAgICAgfSxcclxuICAgICAgICByZWZyZXNoQWxsOiBmdW5jdGlvbiByZWZyZXNoQWxsKCkge1xyXG4gICAgICAgICAgICBzdGlja2llcy5mb3JFYWNoKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgIHJldHVybiBzdGlja3kucmVmcmVzaCgpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZU9uZTogZnVuY3Rpb24gcmVtb3ZlT25lKG5vZGUpIHtcclxuICAgICAgICAgICAgLy8gQ2hlY2sgd2hldGhlciBpdOKAmXMgYSBub2RlXHJcbiAgICAgICAgICAgIGlmICghKG5vZGUgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkpIHtcclxuICAgICAgICAgICAgICAgIC8vIE1heWJlIGl04oCZcyBhIG5vZGUgbGlzdCBvZiBzb21lIHNvcnQ/XHJcbiAgICAgICAgICAgICAgICAvLyBUYWtlIGZpcnN0IG5vZGUgZnJvbSB0aGUgbGlzdCB0aGVuXHJcbiAgICAgICAgICAgICAgICBpZiAobm9kZS5sZW5ndGggJiYgbm9kZVswXSkgbm9kZSA9IG5vZGVbMF07ZWxzZSByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIC8vIFJlbW92ZSB0aGUgc3RpY2tpZXMgYm91bmQgdG8gdGhlIG5vZGVzIGluIHRoZSBsaXN0XHJcbiAgICAgICAgICAgIHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNreS5fbm9kZSA9PT0gbm9kZSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHN0aWNreS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICByZW1vdmU6IGZ1bmN0aW9uIHJlbW92ZShub2RlTGlzdCkge1xyXG4gICAgICAgICAgICAvLyBJZiBpdOKAmXMgYSBub2RlIG1ha2UgYW4gYXJyYXkgb2Ygb25lIG5vZGVcclxuICAgICAgICAgICAgaWYgKG5vZGVMaXN0IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIG5vZGVMaXN0ID0gW25vZGVMaXN0XTtcclxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgdGhlIGFyZ3VtZW50IGlzIGFuIGl0ZXJhYmxlIG9mIHNvbWUgc29ydFxyXG4gICAgICAgICAgICBpZiAoIW5vZGVMaXN0Lmxlbmd0aCkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBzdGlja2llcyBib3VuZCB0byB0aGUgbm9kZXMgaW4gdGhlIGxpc3RcclxuXHJcbiAgICAgICAgICAgIHZhciBfbG9vcDIgPSBmdW5jdGlvbiBfbG9vcDIoaSkge1xyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSBub2RlTGlzdFtpXTtcclxuXHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoc3RpY2t5Ll9ub2RlID09PSBub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0aWNreS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG5vZGVMaXN0Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICBfbG9vcDIoaSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZUFsbDogZnVuY3Rpb24gcmVtb3ZlQWxsKCkge1xyXG4gICAgICAgICAgICB3aGlsZSAoc3RpY2tpZXMubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgICAgICBzdGlja2llc1swXS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgLypcclxuICAgICAqIDYuIFNldHVwIGV2ZW50cyAodW5sZXNzIHRoZSBwb2x5ZmlsbCB3YXMgZGlzYWJsZWQpXHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIGluaXQoKSB7XHJcbiAgICAgICAgLy8gV2F0Y2ggZm9yIHNjcm9sbCBwb3NpdGlvbiBjaGFuZ2VzIGFuZCB0cmlnZ2VyIHJlY2FsYy9yZWZyZXNoIGlmIG5lZWRlZFxyXG4gICAgICAgIGZ1bmN0aW9uIGNoZWNrU2Nyb2xsKCkge1xyXG4gICAgICAgICAgICBpZiAod2luZG93LnBhZ2VYT2Zmc2V0ICE9IHNjcm9sbC5sZWZ0KSB7XHJcbiAgICAgICAgICAgICAgICBzY3JvbGwudG9wID0gd2luZG93LnBhZ2VZT2Zmc2V0O1xyXG4gICAgICAgICAgICAgICAgc2Nyb2xsLmxlZnQgPSB3aW5kb3cucGFnZVhPZmZzZXQ7XHJcblxyXG4gICAgICAgICAgICAgICAgU3RpY2t5ZmlsbC5yZWZyZXNoQWxsKCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAod2luZG93LnBhZ2VZT2Zmc2V0ICE9IHNjcm9sbC50b3ApIHtcclxuICAgICAgICAgICAgICAgIHNjcm9sbC50b3AgPSB3aW5kb3cucGFnZVlPZmZzZXQ7XHJcbiAgICAgICAgICAgICAgICBzY3JvbGwubGVmdCA9IHdpbmRvdy5wYWdlWE9mZnNldDtcclxuXHJcbiAgICAgICAgICAgICAgICAvLyByZWNhbGMgcG9zaXRpb24gZm9yIGFsbCBzdGlja2llc1xyXG4gICAgICAgICAgICAgICAgc3RpY2tpZXMuZm9yRWFjaChmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fcmVjYWxjUG9zaXRpb24oKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBjaGVja1Njcm9sbCgpO1xyXG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdzY3JvbGwnLCBjaGVja1Njcm9sbCk7XHJcblxyXG4gICAgICAgIC8vIFdhdGNoIGZvciB3aW5kb3cgcmVzaXplcyBhbmQgZGV2aWNlIG9yaWVudGF0aW9uIGNoYW5nZXMgYW5kIHRyaWdnZXIgcmVmcmVzaFxyXG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdyZXNpemUnLCBTdGlja3lmaWxsLnJlZnJlc2hBbGwpO1xyXG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdvcmllbnRhdGlvbmNoYW5nZScsIFN0aWNreWZpbGwucmVmcmVzaEFsbCk7XHJcblxyXG4gICAgICAgIC8vRmFzdCBkaXJ0eSBjaGVjayBmb3IgbGF5b3V0IGNoYW5nZXMgZXZlcnkgNTAwbXNcclxuICAgICAgICB2YXIgZmFzdENoZWNrVGltZXIgPSB2b2lkIDA7XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIHN0YXJ0RmFzdENoZWNrVGltZXIoKSB7XHJcbiAgICAgICAgICAgIGZhc3RDaGVja1RpbWVyID0gc2V0SW50ZXJ2YWwoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgc3RpY2tpZXMuZm9yRWFjaChmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fZmFzdENoZWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfSwgNTAwKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIHN0b3BGYXN0Q2hlY2tUaW1lcigpIHtcclxuICAgICAgICAgICAgY2xlYXJJbnRlcnZhbChmYXN0Q2hlY2tUaW1lcik7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICB2YXIgZG9jSGlkZGVuS2V5ID0gdm9pZCAwO1xyXG4gICAgICAgIHZhciB2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lID0gdm9pZCAwO1xyXG5cclxuICAgICAgICBpZiAoJ2hpZGRlbicgaW4gZG9jdW1lbnQpIHtcclxuICAgICAgICAgICAgZG9jSGlkZGVuS2V5ID0gJ2hpZGRlbic7XHJcbiAgICAgICAgICAgIHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUgPSAndmlzaWJpbGl0eWNoYW5nZSc7XHJcbiAgICAgICAgfSBlbHNlIGlmICgnd2Via2l0SGlkZGVuJyBpbiBkb2N1bWVudCkge1xyXG4gICAgICAgICAgICBkb2NIaWRkZW5LZXkgPSAnd2Via2l0SGlkZGVuJztcclxuICAgICAgICAgICAgdmlzaWJpbGl0eUNoYW5nZUV2ZW50TmFtZSA9ICd3ZWJraXR2aXNpYmlsaXR5Y2hhbmdlJztcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICh2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lKSB7XHJcbiAgICAgICAgICAgIGlmICghZG9jdW1lbnRbZG9jSGlkZGVuS2V5XSkgc3RhcnRGYXN0Q2hlY2tUaW1lcigpO1xyXG5cclxuICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoZG9jdW1lbnRbZG9jSGlkZGVuS2V5XSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHN0b3BGYXN0Q2hlY2tUaW1lcigpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0gZWxzZSBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKCFzZXBwdWt1KSBpbml0KCk7XHJcblxyXG4gICAgLypcclxuICAgICAqIDcuIEV4cG9zZSBTdGlja3lmaWxsXHJcbiAgICAgKi9cclxuICAgIGlmICh0eXBlb2YgbW9kdWxlICE9ICd1bmRlZmluZWQnICYmIG1vZHVsZS5leHBvcnRzKSB7XHJcbiAgICAgICAgbW9kdWxlLmV4cG9ydHMgPSBTdGlja3lmaWxsO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICB3aW5kb3cuU3RpY2t5ZmlsbCA9IFN0aWNreWZpbGw7XHJcbiAgICB9XHJcblxyXG59KSh3aW5kb3csIGRvY3VtZW50KTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBnO1xyXG5cclxuLy8gVGhpcyB3b3JrcyBpbiBub24tc3RyaWN0IG1vZGVcclxuZyA9IChmdW5jdGlvbigpIHtcclxuXHRyZXR1cm4gdGhpcztcclxufSkoKTtcclxuXHJcbnRyeSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiBldmFsIGlzIGFsbG93ZWQgKHNlZSBDU1ApXHJcblx0ZyA9IGcgfHwgRnVuY3Rpb24oXCJyZXR1cm4gdGhpc1wiKSgpIHx8ICgxLGV2YWwpKFwidGhpc1wiKTtcclxufSBjYXRjaChlKSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiB0aGUgd2luZG93IHJlZmVyZW5jZSBpcyBhdmFpbGFibGVcclxuXHRpZih0eXBlb2Ygd2luZG93ID09PSBcIm9iamVjdFwiKVxyXG5cdFx0ZyA9IHdpbmRvdztcclxufVxyXG5cclxuLy8gZyBjYW4gc3RpbGwgYmUgdW5kZWZpbmVkLCBidXQgbm90aGluZyB0byBkbyBhYm91dCBpdC4uLlxyXG4vLyBXZSByZXR1cm4gdW5kZWZpbmVkLCBpbnN0ZWFkIG9mIG5vdGhpbmcgaGVyZSwgc28gaXQnc1xyXG4vLyBlYXNpZXIgdG8gaGFuZGxlIHRoaXMgY2FzZS4gaWYoIWdsb2JhbCkgeyAuLi59XHJcblxyXG5tb2R1bGUuZXhwb3J0cyA9IGc7XHJcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vICh3ZWJwYWNrKS9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvd2VicGFjay9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwic291cmNlUm9vdCI6IiJ9