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

var TestResultsByTaskType = function () {
    /**
     * @param {Document} document
     */
    function TestResultsByTaskType(document) {
        _classCallCheck(this, TestResultsByTaskType);

        this.document = document;
        this.byPageList = new ByPageList(document.querySelector('.by-page-container'));
    }

    _createClass(TestResultsByTaskType, [{
        key: 'init',
        value: function init() {
            this.byPageList.init();
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgOTRhNzdiN2YyZmQ0Y2Y2Zjc5NDgiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz80ZmIwIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWJ1dHRvbi1zcGlubmVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWZpZWxkLWZvY3VzZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGFsLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2JhZGdlLWNvbGxlY3Rpb24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2Nvb2tpZS1vcHRpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hbGVydC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9jb29raWUtb3B0aW9ucy1tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvaWNvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9jcmF3bGluZy1saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcmVwYXJpbmctbGlzdGVkLXRlc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnQtY29udHJvbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLXF1ZXVlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWVzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2suanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1sb2NrLXVubG9jay5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9zb3J0LWNvbnRyb2wtY29sbGVjdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvc29ydGFibGUtaXRlbS1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL2Rhc2hib2FyZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LWhpc3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1wcm9ncmVzcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC1jYXJkLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcG9seWZpbGwvY3VzdG9tLWV2ZW50LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wb2x5ZmlsbC9vYmplY3QtZW50cmllcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2Nyb2xsLXRvLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2VydmljZXMvaHR0cC1jbGllbnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL2xpc3RlZC10ZXN0LWZhY3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL3N0cmlwZS1oYW5kbGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy90ZXN0LXJlc3VsdC1yZXRyaWV2ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtaGlzdG9yeS9tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L3N1Z2dlc3Rpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3N1bW1hcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1pZC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC1wYWdpbmF0b3IuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZS9zb3J0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91bmF2YWlsYWJsZS10YXNrLXR5cGUtbW9kYWwtbGF1bmNoZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0tdmFsaWRhdG9yLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQtY2FyZC9mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWFuY2hvci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1hY2NvdW50L25hdmJhci1pdGVtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWxpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2F3ZXNvbXBsZXRlL2F3ZXNvbXBsZXRlLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9ib290c3RyYXAubmF0aXZlL2Rpc3QvYm9vdHN0cmFwLW5hdGl2ZS5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvY2xhc3NsaXN0LXBvbHlmaWxsL3NyYy9pbmRleC5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvc21vb3RoLXNjcm9sbC9kaXN0L3Ntb290aC1zY3JvbGwubWluLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzIiwid2VicGFjazovLy8od2VicGFjaykvYnVpbGRpbi9nbG9iYWwuanMiXSwibmFtZXMiOlsicmVxdWlyZSIsImZvcm1CdXR0b25TcGlubmVyIiwiZm9ybUZpZWxkRm9jdXNlciIsIm1vZGFsQ29udHJvbCIsImNvbGxhcHNlQ29udHJvbENhcmV0IiwiRGFzaGJvYXJkIiwidGVzdEhpc3RvcnlQYWdlIiwiVGVzdFJlc3VsdHMiLCJVc2VyQWNjb3VudCIsIlVzZXJBY2NvdW50Q2FyZCIsIkFsZXJ0RmFjdG9yeSIsIlRlc3RQcm9ncmVzcyIsIlRlc3RSZXN1bHRzUHJlcGFyaW5nIiwiVGVzdFJlc3VsdHNCeVRhc2tUeXBlIiwib25Eb21Db250ZW50TG9hZGVkIiwiYm9keSIsImRvY3VtZW50IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJpdGVtIiwiZm9jdXNlZEZpZWxkIiwicXVlcnlTZWxlY3RvciIsImZvckVhY2giLCJjYWxsIiwicXVlcnlTZWxlY3RvckFsbCIsImZvcm1FbGVtZW50IiwiY2xhc3NMaXN0IiwiY29udGFpbnMiLCJkYXNoYm9hcmQiLCJpbml0IiwidGVzdFByb2dyZXNzIiwidGVzdFJlc3VsdHMiLCJ0ZXN0UmVzdWx0c0J5VGFza1R5cGUiLCJ0ZXN0UmVzdWx0c1ByZXBhcmluZyIsInVzZXJBY2NvdW50Iiwid2luZG93IiwidXNlckFjY291bnRDYXJkIiwiYWxlcnRFbGVtZW50IiwiY3JlYXRlRnJvbUVsZW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwibW9kdWxlIiwiZXhwb3J0cyIsImNvbnRyb2xzIiwiY29udHJvbEljb25DbGFzcyIsImNhcmV0VXBDbGFzcyIsImNhcmV0RG93bkNsYXNzIiwiY29udHJvbENvbGxhcHNlZENsYXNzIiwiY3JlYXRlQ29udHJvbEljb24iLCJjb250cm9sIiwiY29udHJvbEljb24iLCJjcmVhdGVFbGVtZW50IiwiYWRkIiwidG9nZ2xlQ2FyZXQiLCJyZW1vdmUiLCJoYW5kbGVDb250cm9sIiwiZXZlbnROYW1lU2hvd24iLCJldmVudE5hbWVIaWRkZW4iLCJjb2xsYXBzaWJsZUVsZW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImdldEF0dHJpYnV0ZSIsInJlcGxhY2UiLCJhcHBlbmQiLCJzaG93bkhpZGRlbkV2ZW50TGlzdGVuZXIiLCJiaW5kIiwiaSIsImxlbmd0aCIsIkxpc3RlZFRlc3RDb2xsZWN0aW9uIiwiSHR0cENsaWVudCIsIlJlY2VudFRlc3RMaXN0IiwiZWxlbWVudCIsIm93bmVyRG9jdW1lbnQiLCJzb3VyY2VVcmwiLCJsaXN0ZWRUZXN0Q29sbGVjdGlvbiIsInJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uIiwiZ2V0UmV0cmlldmVkRXZlbnROYW1lIiwiX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciIsIl9yZXRyaWV2ZVRlc3RzIiwiZXZlbnQiLCJfcGFyc2VSZXRyaWV2ZWRUZXN0cyIsImRldGFpbCIsInJlc3BvbnNlIiwiX3JlbmRlclJldHJpZXZlZFRlc3RzIiwic2V0VGltZW91dCIsIl9yZW1vdmVTcGlubmVyIiwibGlzdGVkVGVzdCIsInBhcmVudE5vZGUiLCJyZW1vdmVDaGlsZCIsInJldHJpZXZlZExpc3RlZFRlc3QiLCJnZXQiLCJnZXRUZXN0SWQiLCJnZXRUeXBlIiwicmVwbGFjZUNoaWxkIiwiZW5hYmxlIiwicmVuZGVyRnJvbUxpc3RlZFRlc3QiLCJpbnNlcnRBZGphY2VudEVsZW1lbnQiLCJyZXRyaWV2ZWRUZXN0c01hcmt1cCIsInRyaW0iLCJyZXRyaWV2ZWRUZXN0Q29udGFpbmVyIiwiaW5uZXJIVE1MIiwiY3JlYXRlRnJvbU5vZGVMaXN0IiwiZ2V0VGV4dCIsInNwaW5uZXIiLCJGb3JtQnV0dG9uIiwiVGVzdFN0YXJ0Rm9ybSIsInN1Ym1pdEJ1dHRvbnMiLCJzdWJtaXRCdXR0b24iLCJwdXNoIiwiX3N1Ym1pdEV2ZW50TGlzdGVuZXIiLCJfc3VibWl0QnV0dG9uRXZlbnRMaXN0ZW5lciIsImRlRW1waGFzaXplIiwiX3JlcGxhY2VVbmNoZWNrZWRDaGVja2JveGVzV2l0aEhpZGRlbkZpZWxkcyIsImRpc2FibGUiLCJidXR0b25FbGVtZW50IiwidGFyZ2V0IiwiYnV0dG9uIiwibWFya0FzQnVzeSIsImlucHV0IiwiY2hlY2tlZCIsImhpZGRlbklucHV0Iiwic2V0QXR0cmlidXRlIiwidmFsdWUiLCJmb3JtIiwiaW5wdXRWYWx1ZSIsImZvY3VzIiwiY29udHJvbEVsZW1lbnRzIiwiaW5pdGlhbGl6ZSIsImNvbnRyb2xFbGVtZW50IiwiQmFkZ2VDb2xsZWN0aW9uIiwiYmFkZ2VzIiwiZ3JlYXRlc3RXaWR0aCIsIl9kZXJpdmVHcmVhdGVzdFdpZHRoIiwiYmFkZ2UiLCJzdHlsZSIsIndpZHRoIiwib2Zmc2V0V2lkdGgiLCJDb29raWVPcHRpb25zTW9kYWwiLCJDb29raWVPcHRpb25zIiwiY29va2llT3B0aW9uc01vZGFsIiwiYWN0aW9uQmFkZ2UiLCJzdGF0dXNFbGVtZW50IiwibW9kYWxDbG9zZUV2ZW50TGlzdGVuZXIiLCJpc0VtcHR5IiwiaW5uZXJUZXh0IiwibWFya05vdEVuYWJsZWQiLCJtYXJrRW5hYmxlZCIsImdldE9wZW5lZEV2ZW50TmFtZSIsImRpc3BhdGNoRXZlbnQiLCJDdXN0b21FdmVudCIsImdldE1vZGFsT3BlbmVkRXZlbnROYW1lIiwiZ2V0Q2xvc2VkRXZlbnROYW1lIiwiZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUiLCJBY3Rpb25CYWRnZSIsImJzbiIsIkFsZXJ0IiwiY2xvc2VCdXR0b24iLCJfY2xvc2VCdXR0b25DbGlja0V2ZW50SGFuZGxlciIsIl9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMiLCJjb250YWluZXIiLCJhcHBlbmRDaGlsZCIsInByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgiLCJjbGFzc05hbWUiLCJpbmRleCIsImluZGV4T2YiLCJyZWxhdGVkRmllbGRJZCIsInJlbGF0ZWRGaWVsZCIsImJzbkFsZXJ0IiwiY2xvc2UiLCJhZGRCdXR0b24iLCJhcHBseUJ1dHRvbiIsIl9hZGRSZW1vdmVBY3Rpb25zIiwiX2FkZEV2ZW50TGlzdGVuZXJzIiwiY29va2llRGF0YVJvd0NvdW50IiwidGFibGVCb2R5IiwicmVtb3ZlQWN0aW9uIiwidGFibGVSb3ciLCJuYW1lSW5wdXQiLCJ0eXBlIiwia2V5IiwiY2xpY2siLCJzaG93bkV2ZW50TGlzdGVuZXIiLCJwcmV2aW91c1RhYmxlQm9keSIsImNsb25lTm9kZSIsImhpZGRlbkV2ZW50TGlzdGVuZXIiLCJjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciIsImFkZEJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciIsIl9jcmVhdGVUYWJsZVJvdyIsIl9jcmVhdGVSZW1vdmVBY3Rpb24iLCJfYWRkUmVtb3ZlQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIiwiX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIiLCJyZW1vdmVDb250YWluZXIiLCJfcmVtb3ZlQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIiwicmVtb3ZlQWN0aW9uQ29udGFpbmVyIiwibmV4dENvb2tpZUluZGV4IiwibGFzdFRhYmxlUm93IiwidmFsdWVJbnB1dCIsInBhcnNlSW50IiwiSWNvbiIsImljb25FbGVtZW50IiwiZ2V0U2VsZWN0b3IiLCJpY29uIiwic2V0QnVzeSIsInNldEF2YWlsYWJsZSIsInVuRGVFbXBoYXNpemUiLCJzZXRTdWNjZXNzZnVsIiwicmVtb3ZlQXR0cmlidXRlIiwiSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsIiwidXNlcm5hbWVJbnB1dCIsInBhc3N3b3JkSW5wdXQiLCJjbGVhckJ1dHRvbiIsInByZXZpb3VzVXNlcm5hbWUiLCJwcmV2aW91c1Bhc3N3b3JkIiwidXNlcm5hbWUiLCJwYXNzd29yZCIsImZvY3VzZWRJbnB1dCIsImNsZWFyQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwicmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcyIsImFjdGl2ZUljb25DbGFzcyIsImNsYXNzZXNUb1JldGFpbiIsImdldENsYXNzIiwiaW5jbHVkZXMiLCJQcm9ncmVzc2luZ0xpc3RlZFRlc3QiLCJDcmF3bGluZ0xpc3RlZFRlc3QiLCJMaXN0ZWRUZXN0IiwiaXNGaW5pc2hlZCIsImdldFN0YXRlIiwiVGVzdFJlc3VsdFJldHJpZXZlciIsIlByZXBhcmluZ0xpc3RlZFRlc3QiLCJfaW5pdGlhbGlzZVJlc3VsdFJldHJpZXZlciIsInByZXBhcmluZ0VsZW1lbnQiLCJ0ZXN0UmVzdWx0c1JldHJpZXZlciIsInJldHJpZXZlZEV2ZW50IiwiUHJvZ3Jlc3NCYXIiLCJwcm9ncmVzc0JhckVsZW1lbnQiLCJwcm9ncmVzc0JhciIsImNvbXBsZXRpb25QZXJjZW50IiwicGFyc2VGbG9hdCIsImdldENvbXBsZXRpb25QZXJjZW50Iiwic2V0Q29tcGxldGlvblBlcmNlbnQiLCJTb3J0Q29udHJvbCIsImtleXMiLCJKU09OIiwicGFyc2UiLCJfY2xpY2tFdmVudExpc3RlbmVyIiwiZ2V0U29ydFJlcXVlc3RlZEV2ZW50TmFtZSIsIlNvcnRhYmxlSXRlbSIsInNvcnRWYWx1ZXMiLCJUYXNrIiwiVGFza0xpc3QiLCJwYWdlSW5kZXgiLCJ0YXNrcyIsInRhc2tFbGVtZW50IiwidGFzayIsImdldElkIiwic3RhdGVzIiwic3RhdGVzTGVuZ3RoIiwic3RhdGVJbmRleCIsInN0YXRlIiwiY29uY2F0IiwiZ2V0VGFza3NCeVN0YXRlIiwiT2JqZWN0IiwidGFza0lkIiwidXBkYXRlZFRhc2tMaXN0IiwidXBkYXRlZFRhc2siLCJ1cGRhdGVGcm9tVGFzayIsIlRhc2tRdWV1ZSIsImxhYmVsIiwiVGFza1F1ZXVlcyIsInF1ZXVlcyIsInF1ZXVlRWxlbWVudCIsInF1ZXVlIiwiZ2V0UXVldWVJZCIsInRhc2tDb3VudCIsInRhc2tDb3VudEJ5U3RhdGUiLCJnZXRXaWR0aEZvclN0YXRlIiwiaGFzT3duUHJvcGVydHkiLCJNYXRoIiwiY2VpbCIsInNldFZhbHVlIiwic2V0V2lkdGgiLCJUZXN0QWxlcnRDb250YWluZXIiLCJhbGVydCIsImNyZWF0ZUZyb21Db250ZW50Iiwic2V0U3R5bGUiLCJ3cmFwQ29udGVudEluQ29udGFpbmVyIiwiVGVzdExvY2tVbmxvY2siLCJkYXRhIiwibG9ja2VkIiwidW5sb2NrZWQiLCJhY3Rpb24iLCJkZXNjcmlwdGlvbiIsIl9yZW5kZXIiLCJfdG9nZ2xlIiwic3RhdGVEYXRhIiwicHJldmVudERlZmF1bHQiLCJwb3N0IiwidXJsIiwiSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyIsImh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCIsIkxpc3RlZFRlc3RGYWN0b3J5IiwibGlzdGVkVGVzdHMiLCJjb250YWluc1Rlc3RJZCIsInRlc3RJZCIsImNhbGxiYWNrIiwibm9kZUxpc3QiLCJjb2xsZWN0aW9uIiwibGlzdGVkVGVzdEVsZW1lbnQiLCJTb3J0Q29udHJvbENvbGxlY3Rpb24iLCJzZXRTb3J0ZWQiLCJzZXROb3RTb3J0ZWQiLCJTb3J0YWJsZUl0ZW1MaXN0IiwiaXRlbXMiLCJzb3J0ZWRJdGVtcyIsInNvcnRhYmxlSXRlbSIsInBvc2l0aW9uIiwidmFsdWVzIiwiZ2V0U29ydFZhbHVlIiwiTnVtYmVyIiwiaXNJbnRlZ2VyIiwidG9TdHJpbmciLCJqb2luIiwic29ydCIsIl9jb21wYXJlRnVuY3Rpb24iLCJpbmRleEl0ZW0iLCJhIiwiYiIsInVuYXZhaWxhYmxlVGFza1R5cGVNb2RhbExhdW5jaGVyIiwiSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkiLCJDb29raWVPcHRpb25zRmFjdG9yeSIsInRlc3RTdGFydEZvcm0iLCJyZWNlbnRUZXN0TGlzdCIsImh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMiLCJjcmVhdGUiLCJjb29raWVPcHRpb25zIiwiTW9kYWwiLCJTdWdnZXN0aW9ucyIsIm1vZGFsSWQiLCJmaWx0ZXJPcHRpb25zU2VsZWN0b3IiLCJtb2RhbEVsZW1lbnQiLCJmaWx0ZXJPcHRpb25zRWxlbWVudCIsIm1vZGFsIiwic3VnZ2VzdGlvbnMiLCJzdWdnZXN0aW9uc0xvYWRlZEV2ZW50TGlzdGVuZXIiLCJzZXRTdWdnZXN0aW9ucyIsImZpbHRlckNoYW5nZWRFdmVudExpc3RlbmVyIiwiZmlsdGVyIiwic2VhcmNoIiwiY3VycmVudFNlYXJjaCIsImxvY2F0aW9uIiwibG9hZGVkRXZlbnROYW1lIiwiZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSIsInJldHJpZXZlIiwiU3VtbWFyeSIsIlRhc2tMaXN0UGFnaW5hdGlvbiIsInBhZ2VMZW5ndGgiLCJzdW1tYXJ5IiwidGFza0xpc3RFbGVtZW50IiwidGFza0xpc3QiLCJhbGVydENvbnRhaW5lciIsInRhc2tMaXN0UGFnaW5hdGlvbiIsInRhc2tMaXN0SXNJbml0aWFsaXplZCIsInN1bW1hcnlEYXRhIiwiX3JlZnJlc2hTdW1tYXJ5IiwiZ2V0UmVuZGVyQW1tZW5kbWVudEV2ZW50TmFtZSIsInJlbmRlclVybExpbWl0Tm90aWZpY2F0aW9uIiwiZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUiLCJfdGFza0xpc3RJbml0aWFsaXplZEV2ZW50TGlzdGVuZXIiLCJnZXRTZWxlY3RQYWdlRXZlbnROYW1lIiwic2V0Q3VycmVudFBhZ2VJbmRleCIsInNlbGVjdFBhZ2UiLCJyZW5kZXIiLCJnZXRTZWxlY3RQcmV2aW91c1BhZ2VFdmVudE5hbWUiLCJtYXgiLCJjdXJyZW50UGFnZUluZGV4IiwiZ2V0U2VsZWN0TmV4dFBhZ2VFdmVudE5hbWUiLCJtaW4iLCJwYWdlQ291bnQiLCJyZXF1ZXN0SWQiLCJyZXF1ZXN0IiwicmVzcG9uc2VVUkwiLCJocmVmIiwidGVzdCIsInJlbW90ZV90ZXN0IiwidGFza19jb3VudCIsImlzU3RhdGVRdWV1ZWRPckluUHJvZ3Jlc3MiLCJfc2V0Qm9keUpvYkNsYXNzIiwiaXNJbml0aWFsaXppbmciLCJpc1JlcXVpcmVkIiwiaXNSZW5kZXJlZCIsIl9jcmVhdGVQYWdpbmF0aW9uRWxlbWVudCIsInNldFBhZ2luYXRpb25FbGVtZW50IiwiX2FkZFBhZ2luYXRpb25FdmVudExpc3RlbmVycyIsImNyZWF0ZU1hcmt1cCIsInN1bW1hcnlVcmwiLCJub3ciLCJEYXRlIiwiZ2V0SnNvbiIsImdldFRpbWUiLCJib2R5Q2xhc3NMaXN0IiwidGVzdFN0YXRlIiwiam9iQ2xhc3NQcmVmaXgiLCJCeVBhZ2VMaXN0IiwiYnlQYWdlTGlzdCIsInVucmV0cmlldmVkUmVtb3RlVGFza0lkc1VybCIsInJlc3VsdHNSZXRyaWV2ZVVybCIsInJldHJpZXZhbFN0YXRzVXJsIiwicmVzdWx0c1VybCIsImNvbXBsZXRpb25QZXJjZW50VmFsdWUiLCJsb2NhbFRhc2tDb3VudCIsInJlbW90ZVRhc2tDb3VudCIsIl9jaGVja0NvbXBsZXRpb25TdGF0dXMiLCJfcmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbiIsIl9yZXRyaWV2ZVJlbW90ZVRhc2tDb2xsZWN0aW9uIiwiX3JldHJpZXZlUmV0cmlldmFsU3RhdHMiLCJjb21wbGV0aW9uX3BlcmNlbnQiLCJyZW1haW5pbmdfdGFza3NfdG9fcmV0cmlldmVfY291bnQiLCJsb2NhbF90YXNrX2NvdW50IiwicmVtb3RlX3Rhc2tfY291bnQiLCJyZW1vdGVUYXNrSWRzIiwidGVzdExvY2tVbmxvY2siLCJyZXRlc3RGb3JtIiwicmV0ZXN0QnV0dG9uIiwidGFza1R5cGVTdW1tYXJ5QmFkZ2VDb2xsZWN0aW9uIiwiYXBwbHlVbmlmb3JtV2lkdGgiLCJGb3JtIiwiRm9ybVZhbGlkYXRvciIsIlN0cmlwZUhhbmRsZXIiLCJzdHJpcGVKcyIsIlN0cmlwZSIsImZvcm1WYWxpZGF0b3IiLCJzdHJpcGVIYW5kbGVyIiwic2V0U3RyaXBlUHVibGlzaGFibGVLZXkiLCJnZXRTdHJpcGVQdWJsaXNoYWJsZUtleSIsInVwZGF0ZUNhcmQiLCJnZXRVcGRhdGVDYXJkRXZlbnROYW1lIiwiY3JlYXRlQ2FyZFRva2VuU3VjY2VzcyIsImdldENyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudE5hbWUiLCJjcmVhdGVDYXJkVG9rZW5GYWlsdXJlIiwiZ2V0Q3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TmFtZSIsIl91cGRhdGVDYXJkRXZlbnRMaXN0ZW5lciIsIl9jcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnRMaXN0ZW5lciIsIl9jcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnRMaXN0ZW5lciIsInVwZGF0ZUNhcmRFdmVudCIsImNyZWF0ZUNhcmRUb2tlbiIsInN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50IiwicmVxdWVzdFVybCIsInBhdGhuYW1lIiwiaWQiLCJYTUxIdHRwUmVxdWVzdCIsIm9wZW4iLCJyZXNwb25zZVR5cGUiLCJzZXRSZXF1ZXN0SGVhZGVyIiwibWFya0FzQXZhaWxhYmxlIiwibWFya1N1Y2NlZWRlZCIsInRoaXNfdXJsIiwiaGFuZGxlUmVzcG9uc2VFcnJvciIsInVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9wYXJhbSIsInVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9tZXNzYWdlIiwic2VuZCIsInJlc3BvbnNlRXJyb3IiLCJjcmVhdGVSZXNwb25zZUVycm9yIiwiZXJyb3IiLCJwYXJhbSIsIlNjcm9sbFNweSIsIk5hdkJhckxpc3QiLCJTY3JvbGxUbyIsIlN0aWNreUZpbGwiLCJzY3JvbGxPZmZzZXQiLCJzY3JvbGxTcHlPZmZzZXQiLCJzaWRlTmF2RWxlbWVudCIsIm5hdkJhckxpc3QiLCJzY3JvbGxzcHkiLCJ0YXJnZXRJZCIsImhhc2giLCJyZWxhdGVkQW5jaG9yIiwiZ29UbyIsInNjcm9sbFRvIiwic2VsZWN0b3IiLCJzdGlja3lOYXZKc0NsYXNzIiwic3RpY2t5TmF2SnNTZWxlY3RvciIsInN0aWNreU5hdiIsImFkZE9uZSIsInNweSIsIl9hcHBseVBvc2l0aW9uU3RpY2t5UG9seWZpbGwiLCJfYXBwbHlJbml0aWFsU2Nyb2xsIiwicGFyYW1zIiwiYnViYmxlcyIsImNhbmNlbGFibGUiLCJ1bmRlZmluZWQiLCJjdXN0b21FdmVudCIsImNyZWF0ZUV2ZW50IiwiaW5pdEN1c3RvbUV2ZW50IiwicHJvdG90eXBlIiwiRXZlbnQiLCJlbnRyaWVzIiwib2JqIiwib3duUHJvcHMiLCJyZXNBcnJheSIsIkFycmF5IiwiU21vb3RoU2Nyb2xsIiwib2Zmc2V0Iiwic2Nyb2xsIiwiYW5pbWF0ZVNjcm9sbCIsIm9mZnNldFRvcCIsIl91cGRhdGVIaXN0b3J5IiwiaGlzdG9yeSIsInB1c2hTdGF0ZSIsImVycm9yQ29udGVudCIsImVsZW1lbnRJbm5lckhUTUwiLCJtZXRob2QiLCJyZXF1ZXN0SGVhZGVycyIsInJlYWxSZXF1ZXN0SGVhZGVycyIsInN0cmlwZVB1Ymxpc2hhYmxlS2V5Iiwic2V0UHVibGlzaGFibGVLZXkiLCJjYXJkIiwiY3JlYXRlVG9rZW4iLCJzdGF0dXMiLCJpc0Vycm9yUmVzcG9uc2UiLCJldmVudE5hbWUiLCJzdGF0dXNVcmwiLCJ1bnJldHJpZXZlZFRhc2tJZHNVcmwiLCJyZXRyaWV2ZVRhc2tzVXJsIiwiX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzIiwiX3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5IiwiX2Rpc3BsYXlQcmVwYXJpbmdTdW1tYXJ5IiwiX3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24iLCJfcmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24iLCJyZXRyaWV2ZWRTdW1tYXJ5Q29udGFpbmVyIiwic3RhdHVzRGF0YSIsIl9jcmVhdGVQcmVwYXJpbmdTdW1tYXJ5IiwicHJldmlvdXNGaWx0ZXIiLCJhcHBseUZpbHRlciIsImF3ZXNvbWVwbGV0ZSIsIkF3ZXNvbXBsZXRlIiwibGlzdCIsImhpZGVFdmVudExpc3RlbmVyIiwiV0lMRENBUkQiLCJmaWx0ZXJJc0VtcHR5Iiwic3VnZ2VzdGlvbnNJbmNsdWRlc0ZpbHRlciIsImZpbHRlcklzV2lsZGNhcmRQcmVmaXhlZCIsImNoYXJBdCIsImZpbHRlcklzV2lsZGNhcmRTdWZmaXhlZCIsInNsaWNlIiwiYXBwbHlCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZXF1ZXN0T25sb2FkSGFuZGxlciIsInJlc3BvbnNlVGV4dCIsIm9ubG9hZCIsImNhbmNlbEFjdGlvbiIsImNhbmNlbENyYXdsQWN0aW9uIiwic3RhdGVMYWJlbCIsInRhc2tRdWV1ZXMiLCJfY2FuY2VsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIiwiX2NhbmNlbENyYXdsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyIiwicmVtb3RlVGVzdCIsInN0YXRlX2xhYmVsIiwidGFza19jb3VudF9ieV9zdGF0ZSIsImFtbWVuZG1lbnRzIiwiVGFza0lkTGlzdCIsInRhc2tJZHMiLCJwYWdlTnVtYmVyIiwicGFnZUFjdGlvbnMiLCJwcmV2aW91c0FjdGlvbiIsIm5leHRBY3Rpb24iLCJhY3Rpb25Db250YWluZXIiLCJyb2xlIiwibWFya3VwIiwic3RhcnRJbmRleCIsImVuZEluZGV4IiwicGFnZUFjdGlvbiIsImlzQWN0aXZlIiwicGFyZW50RWxlbWVudCIsIlRhc2tMaXN0TW9kZWwiLCJ0YXNrSWRMaXN0IiwidGFza0xpc3RNb2RlbHMiLCJoZWFkaW5nIiwiYnVzeUljb24iLCJfY3JlYXRlQnVzeUljb24iLCJfcmVxdWVzdFRhc2tJZHMiLCJwYWdpbmF0aW9uRWxlbWVudCIsInRhc2tMaXN0TW9kZWwiLCJfY3JlYXRlVGFza0xpc3RFbGVtZW50RnJvbUh0bWwiLCJnZXRQYWdlSW5kZXgiLCJfcmV0cmlldmVUYXNrU2V0V2l0aERlbGF5IiwiZ2V0VGFza3NCeVN0YXRlcyIsInVwZGF0ZWRUYXNrTGlzdE1vZGVsIiwidXBkYXRlRnJvbVRhc2tMaXN0IiwiaGFzVGFza0xpc3RFbGVtZW50Rm9yUGFnZSIsIl9yZXRyaWV2ZVRhc2tQYWdlIiwicmVuZGVyZWRUYXNrTGlzdEVsZW1lbnQiLCJoYXNQYWdlSW5kZXgiLCJjdXJyZW50UGFnZUxpc3RFbGVtZW50Iiwic2VsZWN0ZWRQYWdlTGlzdEVsZW1lbnQiLCJnZXRGb3JQYWdlIiwicG9zdERhdGEiLCJfcmV0cmlldmVUYXNrU2V0IiwiaHRtbCIsIlNvcnQiLCJzb3J0YWJsZUl0ZW1zTm9kZUxpc3QiLCJzb3J0Q29udHJvbENvbGxlY3Rpb24iLCJfY3JlYXRlU29ydGFibGVDb250cm9sQ29sbGVjdGlvbiIsInNvcnRhYmxlSXRlbXNMaXN0IiwiX2NyZWF0ZVNvcnRhYmxlSXRlbUxpc3QiLCJfc29ydENvbnRyb2xDbGlja0V2ZW50TGlzdGVuZXIiLCJzb3J0YWJsZUl0ZW1zIiwic29ydGFibGVJdGVtRWxlbWVudCIsInNvcnRDb250cm9sRWxlbWVudCIsInBhcmVudCIsInRhc2tUeXBlQ29udGFpbmVycyIsInVuYXZhaWxhYmxlVGFza1R5cGUiLCJ0YXNrVHlwZUtleSIsInNob3ciLCJpbnZhbGlkRmllbGQiLCJlcnJvck1lc3NhZ2UiLCJjb21wYXJhdG9yVmFsdWUiLCJ2YWxpZGF0ZUNhcmROdW1iZXIiLCJudW1iZXIiLCJ2YWxpZGF0ZUV4cGlyeSIsImV4cF9tb250aCIsImV4cF95ZWFyIiwidmFsaWRhdGVDVkMiLCJjdmMiLCJ2YWxpZGF0b3IiLCJkYXRhRWxlbWVudCIsImZpZWxkS2V5Iiwic3RvcFByb3BhZ2F0aW9uIiwiX3JlbW92ZUVycm9yQWxlcnRzIiwiX2dldERhdGEiLCJpc1ZhbGlkIiwidmFsaWRhdGUiLCJlcnJvckFsZXJ0cyIsImVycm9yQWxlcnQiLCJmaWVsZCIsIm1lc3NhZ2UiLCJlcnJvckNvbnRhaW5lciIsIl9kaXNwbGF5RmllbGRFcnJvciIsIk5hdkJhckFuY2hvciIsImhhbmRsZUNsaWNrIiwiZ2V0VGFyZ2V0IiwiTmF2QmFySXRlbSIsImFuY2hvciIsImNoaWxkcmVuIiwiY2hpbGQiLCJub2RlTmFtZSIsInRhcmdldHMiLCJnZXRUYXJnZXRzIiwiY29udGFpbnNUYXJnZXRJZCIsInNldEFjdGl2ZSIsIm5hdkJhckl0ZW1zIiwibmF2QmFySXRlbSIsImxpc3RJdGVtRWxlbWVudCIsImFjdGl2ZUxpbmtUYXJnZXQiLCJsaW5rVGFyZ2V0cyIsImxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZCIsImxpbmtUYXJnZXQiLCJnZXRCb3VuZGluZ0NsaWVudFJlY3QiLCJ0b3AiLCJjbGVhckFjdGl2ZSIsInNjcm9sbEV2ZW50TGlzdGVuZXIiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsbUNBQTJCLDBCQUEwQixFQUFFO0FBQ3ZELHlDQUFpQyxlQUFlO0FBQ2hEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLDhEQUFzRCwrREFBK0Q7O0FBRXJIO0FBQ0E7O0FBRUE7QUFDQTs7Ozs7Ozs7Ozs7OztBQzdEQSx5Qzs7Ozs7Ozs7Ozs7O0FDQUEsbUJBQUFBLENBQVEsa0ZBQVI7QUFDQSxtQkFBQUEsQ0FBUSw4Q0FBUjs7QUFFQSxtQkFBQUEsQ0FBUSwwRUFBUjtBQUNBLG1CQUFBQSxDQUFRLHFFQUFSO0FBQ0EsbUJBQUFBLENBQVEseUVBQVI7O0FBRUEsSUFBSUMsb0JBQW9CLG1CQUFBRCxDQUFRLGlFQUFSLENBQXhCO0FBQ0EsSUFBSUUsbUJBQW1CLG1CQUFBRixDQUFRLCtEQUFSLENBQXZCO0FBQ0EsSUFBSUcsZUFBZSxtQkFBQUgsQ0FBUSxxREFBUixDQUFuQjtBQUNBLElBQUlJLHVCQUF1QixtQkFBQUos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O0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSxxQ0FBcUM7QUFDckM7O0FBRUE7QUFDQTtBQUNBLGdCQUFnQixnQ0FBZ0M7QUFDaEQ7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxFQUFFO0FBQ0Y7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLENBQUM7Ozs7Ozs7Ozs7Ozs7OENDaGZEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSwrQkFBK0I7QUFDL0I7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtB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Q0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6Qjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyREFBMkQ7QUFDM0Q7O0FBRUE7QUFDQTtBQUNBLDJCQUEyQixxQkFBcUI7QUFDaEQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBOztBQUVBLDJCQUEyQixxQkFBcUI7QUFDaEQ7O0FBRUE7QUFDQTs7QUFFQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyREFBMkQ7QUFDM0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjs7QUFFQSwyQkFBMkIscUJBQXFCO0FBQ2hEO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBLENBQUMsb0I7Ozs7Ozs7Ozs7OztBQ3ZnQkQ7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQSxDQUFDO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDRDQUE0Qzs7QUFFNUMiLCJmaWxlIjoiYXBwLjMxNjEyNzk0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL2J1aWxkL1wiO1xuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IFwiLi9hc3NldHMvanMvYXBwLmpzXCIpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHdlYnBhY2svYm9vdHN0cmFwIDk0YTc3YjdmMmZkNGNmNmY3OTQ4IiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL2Fzc2V0cy9jc3MvYXBwLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IC4vYXNzZXRzL2Nzcy9hcHAuc2Nzc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJyZXF1aXJlKCdib290c3RyYXAubmF0aXZlJyk7XG5yZXF1aXJlKCcuLi9jc3MvYXBwLnNjc3MnKTtcblxucmVxdWlyZSgnY2xhc3NsaXN0LXBvbHlmaWxsJyk7XG5yZXF1aXJlKCcuL3BvbHlmaWxsL2N1c3RvbS1ldmVudCcpO1xucmVxdWlyZSgnLi9wb2x5ZmlsbC9vYmplY3QtZW50cmllcycpO1xuXG5sZXQgZm9ybUJ1dHRvblNwaW5uZXIgPSByZXF1aXJlKCcuL2Zvcm0tYnV0dG9uLXNwaW5uZXInKTtcbmxldCBmb3JtRmllbGRGb2N1c2VyID0gcmVxdWlyZSgnLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcbmxldCBtb2RhbENvbnRyb2wgPSByZXF1aXJlKCcuL21vZGFsLWNvbnRyb2wnKTtcbmxldCBjb2xsYXBzZUNvbnRyb2xDYXJldCA9IHJlcXVpcmUoJy4vY29sbGFwc2UtY29udHJvbC1jYXJldCcpO1xuXG5sZXQgRGFzaGJvYXJkID0gcmVxdWlyZSgnLi9wYWdlL2Rhc2hib2FyZCcpO1xubGV0IHRlc3RIaXN0b3J5UGFnZSA9IHJlcXVpcmUoJy4vcGFnZS90ZXN0LWhpc3RvcnknKTtcbmxldCBUZXN0UmVzdWx0cyA9IHJlcXVpcmUoJy4vcGFnZS90ZXN0LXJlc3VsdHMnKTtcbmxldCBVc2VyQWNjb3VudCA9IHJlcXVpcmUoJy4vcGFnZS91c2VyLWFjY291bnQnKTtcbmxldCBVc2VyQWNjb3VudENhcmQgPSByZXF1aXJlKCcuL3BhZ2UvdXNlci1hY2NvdW50LWNhcmQnKTtcbmxldCBBbGVydEZhY3RvcnkgPSByZXF1aXJlKCcuL3NlcnZpY2VzL2FsZXJ0LWZhY3RvcnknKTtcbmxldCBUZXN0UHJvZ3Jlc3MgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1wcm9ncmVzcycpO1xubGV0IFRlc3RSZXN1bHRzUHJlcGFyaW5nID0gcmVxdWlyZSgnLi9wYWdlL3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcnKTtcbmxldCBUZXN0UmVzdWx0c0J5VGFza1R5cGUgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZScpO1xuXG5jb25zdCBvbkRvbUNvbnRlbnRMb2FkZWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgbGV0IGJvZHkgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYm9keScpLml0ZW0oMCk7XG4gICAgbGV0IGZvY3VzZWRGaWVsZCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLWZvY3VzZWRdJyk7XG5cbiAgICBpZiAoZm9jdXNlZEZpZWxkKSB7XG4gICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZm9jdXNlZEZpZWxkKTtcbiAgICB9XG5cbiAgICBbXS5mb3JFYWNoLmNhbGwoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLWZvcm0tYnV0dG9uLXNwaW5uZXInKSwgZnVuY3Rpb24gKGZvcm1FbGVtZW50KSB7XG4gICAgICAgIGZvcm1CdXR0b25TcGlubmVyKGZvcm1FbGVtZW50KTtcbiAgICB9KTtcblxuICAgIG1vZGFsQ29udHJvbChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubW9kYWwtY29udHJvbCcpKTtcblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygnZGFzaGJvYXJkJykpIHtcbiAgICAgICAgbGV0IGRhc2hib2FyZCA9IG5ldyBEYXNoYm9hcmQoZG9jdW1lbnQpO1xuICAgICAgICBkYXNoYm9hcmQuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1wcm9ncmVzcycpKSB7XG4gICAgICAgIGxldCB0ZXN0UHJvZ3Jlc3MgPSBuZXcgVGVzdFByb2dyZXNzKGRvY3VtZW50KTtcbiAgICAgICAgdGVzdFByb2dyZXNzLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3Rlc3QtaGlzdG9yeScpKSB7XG4gICAgICAgIHRlc3RIaXN0b3J5UGFnZShkb2N1bWVudCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd0ZXN0LXJlc3VsdHMnKSkge1xuICAgICAgICBsZXQgdGVzdFJlc3VsdHMgPSBuZXcgVGVzdFJlc3VsdHMoZG9jdW1lbnQpO1xuICAgICAgICB0ZXN0UmVzdWx0cy5pbml0KCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd0ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlJykpIHtcbiAgICAgICAgbGV0IHRlc3RSZXN1bHRzQnlUYXNrVHlwZSA9IG5ldyBUZXN0UmVzdWx0c0J5VGFza1R5cGUoZG9jdW1lbnQpO1xuICAgICAgICB0ZXN0UmVzdWx0c0J5VGFza1R5cGUuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1yZXN1bHRzLXByZXBhcmluZycpKSB7XG4gICAgICAgIGxldCB0ZXN0UmVzdWx0c1ByZXBhcmluZyA9IG5ldyBUZXN0UmVzdWx0c1ByZXBhcmluZyhkb2N1bWVudCk7XG4gICAgICAgIHRlc3RSZXN1bHRzUHJlcGFyaW5nLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3VzZXItYWNjb3VudCcpKSB7XG4gICAgICAgIGxldCB1c2VyQWNjb3VudCA9IG5ldyBVc2VyQWNjb3VudCh3aW5kb3csIGRvY3VtZW50KTtcbiAgICAgICAgdXNlckFjY291bnQuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndXNlci1hY2NvdW50LWNhcmQnKSkge1xuICAgICAgICBsZXQgdXNlckFjY291bnRDYXJkID0gbmV3IFVzZXJBY2NvdW50Q2FyZChkb2N1bWVudCk7XG4gICAgICAgIHVzZXJBY2NvdW50Q2FyZC5pbml0KCk7XG4gICAgfVxuXG4gICAgY29sbGFwc2VDb250cm9sQ2FyZXQoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmNvbGxhcHNlLWNvbnRyb2wnKSk7XG5cbiAgICBbXS5mb3JFYWNoLmNhbGwoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmFsZXJ0JyksIGZ1bmN0aW9uIChhbGVydEVsZW1lbnQpIHtcbiAgICAgICAgQWxlcnRGYWN0b3J5LmNyZWF0ZUZyb21FbGVtZW50KGFsZXJ0RWxlbWVudCk7XG4gICAgfSk7XG59O1xuXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgb25Eb21Db250ZW50TG9hZGVkKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9hcHAuanMiLCJtb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uIChjb250cm9scykge1xuICAgIGNvbnN0IGNvbnRyb2xJY29uQ2xhc3MgPSAnZmEnO1xuICAgIGNvbnN0IGNhcmV0VXBDbGFzcyA9ICdmYS1jYXJldC11cCc7XG4gICAgY29uc3QgY2FyZXREb3duQ2xhc3MgPSAnZmEtY2FyZXQtZG93bic7XG4gICAgY29uc3QgY29udHJvbENvbGxhcHNlZENsYXNzID0gJ2NvbGxhcHNlZCc7XG5cbiAgICBsZXQgY3JlYXRlQ29udHJvbEljb24gPSBmdW5jdGlvbiAoY29udHJvbCkge1xuICAgICAgICBjb25zdCBjb250cm9sSWNvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2knKTtcbiAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjb250cm9sSWNvbkNsYXNzKTtcblxuICAgICAgICBpZiAoY29udHJvbC5jbGFzc0xpc3QuY29udGFpbnMoY29udHJvbENvbGxhcHNlZENsYXNzKSkge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldERvd25DbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QuYWRkKGNhcmV0VXBDbGFzcyk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gY29udHJvbEljb247XG4gICAgfTtcblxuICAgIGxldCB0b2dnbGVDYXJldCA9IGZ1bmN0aW9uIChjb250cm9sLCBjb250cm9sSWNvbikge1xuICAgICAgICBpZiAoY29udHJvbC5jbGFzc0xpc3QuY29udGFpbnMoY29udHJvbENvbGxhcHNlZENsYXNzKSkge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LnJlbW92ZShjYXJldFVwQ2xhc3MpO1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldERvd25DbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QucmVtb3ZlKGNhcmV0RG93bkNsYXNzKTtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY2FyZXRVcENsYXNzKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBsZXQgaGFuZGxlQ29udHJvbCA9IGZ1bmN0aW9uIChjb250cm9sKSB7XG4gICAgICAgIGNvbnN0IGV2ZW50TmFtZVNob3duID0gJ3Nob3duLmJzLmNvbGxhcHNlJztcbiAgICAgICAgY29uc3QgZXZlbnROYW1lSGlkZGVuID0gJ2hpZGRlbi5icy5jb2xsYXBzZSc7XG4gICAgICAgIGNvbnN0IGNvbGxhcHNpYmxlRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGNvbnRyb2wuZ2V0QXR0cmlidXRlKCdkYXRhLXRhcmdldCcpLnJlcGxhY2UoJyMnLCAnJykpO1xuICAgICAgICBjb25zdCBjb250cm9sSWNvbiA9IGNyZWF0ZUNvbnRyb2xJY29uKGNvbnRyb2wpO1xuXG4gICAgICAgIGNvbnRyb2wuYXBwZW5kKGNvbnRyb2xJY29uKTtcblxuICAgICAgICBsZXQgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdG9nZ2xlQ2FyZXQoY29udHJvbCwgY29udHJvbEljb24pO1xuICAgICAgICB9O1xuXG4gICAgICAgIGNvbGxhcHNpYmxlRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50TmFtZVNob3duLCBzaG93bkhpZGRlbkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIGNvbGxhcHNpYmxlRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50TmFtZUhpZGRlbiwgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGNvbnRyb2xzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGhhbmRsZUNvbnRyb2woY29udHJvbHNbaV0pO1xuICAgIH1cbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY29sbGFwc2UtY29udHJvbC1jYXJldC5qcyIsImxldCBMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24nKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgUmVjZW50VGVzdExpc3Qge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuc291cmNlVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc291cmNlLXVybCcpO1xuICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uID0gbmV3IExpc3RlZFRlc3RDb2xsZWN0aW9uKCk7XG4gICAgICAgIHRoaXMucmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24gPSBuZXcgTGlzdGVkVGVzdENvbGxlY3Rpb24oKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLl9yZXRyaWV2ZVRlc3RzKCk7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIHRoaXMuX3BhcnNlUmV0cmlldmVkVGVzdHMoZXZlbnQuZGV0YWlsLnJlc3BvbnNlKTtcbiAgICAgICAgdGhpcy5fcmVuZGVyUmV0cmlldmVkVGVzdHMoKTtcblxuICAgICAgICB3aW5kb3cuc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRlc3RzKCk7XG4gICAgICAgIH0sIDMwMDApO1xuICAgIH07XG5cbiAgICBfcmVuZGVyUmV0cmlldmVkVGVzdHMgKCkge1xuICAgICAgICB0aGlzLl9yZW1vdmVTcGlubmVyKCk7XG5cbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5mb3JFYWNoKChsaXN0ZWRUZXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIXRoaXMucmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24uY29udGFpbnMobGlzdGVkVGVzdCkpIHtcbiAgICAgICAgICAgICAgICBsaXN0ZWRUZXN0LmVsZW1lbnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChsaXN0ZWRUZXN0LmVsZW1lbnQpO1xuICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24ucmVtb3ZlKGxpc3RlZFRlc3QpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uLmZvckVhY2goKHJldHJpZXZlZExpc3RlZFRlc3QpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmNvbnRhaW5zKHJldHJpZXZlZExpc3RlZFRlc3QpKSB7XG4gICAgICAgICAgICAgICAgbGV0IGxpc3RlZFRlc3QgPSB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmdldChyZXRyaWV2ZWRMaXN0ZWRUZXN0LmdldFRlc3RJZCgpKTtcblxuICAgICAgICAgICAgICAgIGlmIChyZXRyaWV2ZWRMaXN0ZWRUZXN0LmdldFR5cGUoKSAhPT0gbGlzdGVkVGVzdC5nZXRUeXBlKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5yZW1vdmUobGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5yZXBsYWNlQ2hpbGQocmV0cmlldmVkTGlzdGVkVGVzdC5lbGVtZW50LCBsaXN0ZWRUZXN0LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24uYWRkKHJldHJpZXZlZExpc3RlZFRlc3QpO1xuICAgICAgICAgICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGxpc3RlZFRlc3QucmVuZGVyRnJvbUxpc3RlZFRlc3QocmV0cmlldmVkTGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdhZnRlcmJlZ2luJywgcmV0cmlldmVkTGlzdGVkVGVzdC5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmFkZChyZXRyaWV2ZWRMaXN0ZWRUZXN0KTtcbiAgICAgICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3BhcnNlUmV0cmlldmVkVGVzdHMgKHJlc3BvbnNlKSB7XG4gICAgICAgIGxldCByZXRyaWV2ZWRUZXN0c01hcmt1cCA9IHJlc3BvbnNlLnRyaW0oKTtcbiAgICAgICAgbGV0IHJldHJpZXZlZFRlc3RDb250YWluZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgcmV0cmlldmVkVGVzdENvbnRhaW5lci5pbm5lckhUTUwgPSByZXRyaWV2ZWRUZXN0c01hcmt1cDtcblxuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uID0gTGlzdGVkVGVzdENvbGxlY3Rpb24uY3JlYXRlRnJvbU5vZGVMaXN0KFxuICAgICAgICAgICAgcmV0cmlldmVkVGVzdENvbnRhaW5lci5xdWVyeVNlbGVjdG9yQWxsKCcubGlzdGVkLXRlc3QnKSxcbiAgICAgICAgICAgIGZhbHNlXG4gICAgICAgICk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZVRlc3RzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRUZXh0KHRoaXMuc291cmNlVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZVRlc3RzJyk7XG4gICAgfTtcblxuICAgIF9yZW1vdmVTcGlubmVyICgpIHtcbiAgICAgICAgbGV0IHNwaW5uZXIgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXByZWZpbGwtc3Bpbm5lcicpO1xuXG4gICAgICAgIGlmIChzcGlubmVyKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQucmVtb3ZlQ2hpbGQoc3Bpbm5lcik7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFJlY2VudFRlc3RMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5cbmNsYXNzIFRlc3RTdGFydEZvcm0ge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucyA9IFtdO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnW3R5cGU9c3VibWl0XScpLCAoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMucHVzaChuZXcgRm9ybUJ1dHRvbihzdWJtaXRCdXR0b24pKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgdGhpcy5fc3VibWl0RXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX3N1Ym1pdEJ1dHRvbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3N1Ym1pdEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucy5mb3JFYWNoKChzdWJtaXRCdXR0b24pID0+IHtcbiAgICAgICAgICAgIHN1Ym1pdEJ1dHRvbi5kZUVtcGhhc2l6ZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLl9yZXBsYWNlVW5jaGVja2VkQ2hlY2tib3hlc1dpdGhIaWRkZW5GaWVsZHMoKTtcbiAgICB9O1xuXG4gICAgZGlzYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucy5mb3JFYWNoKChzdWJtaXRCdXR0b24pID0+IHtcbiAgICAgICAgICAgIHN1Ym1pdEJ1dHRvbi5kaXNhYmxlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBlbmFibGUgKCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZW5hYmxlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfc3VibWl0QnV0dG9uRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IGJ1dHRvbkVsZW1lbnQgPSBldmVudC50YXJnZXQ7XG4gICAgICAgIGxldCBidXR0b24gPSBuZXcgRm9ybUJ1dHRvbihidXR0b25FbGVtZW50KTtcblxuICAgICAgICBidXR0b24ubWFya0FzQnVzeSgpO1xuICAgIH07XG5cbiAgICBfcmVwbGFjZVVuY2hlY2tlZENoZWNrYm94ZXNXaXRoSGlkZGVuRmllbGRzICgpIHtcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbnB1dFt0eXBlPWNoZWNrYm94XScpLCAoaW5wdXQpID0+IHtcbiAgICAgICAgICAgIGlmICghaW5wdXQuY2hlY2tlZCkge1xuICAgICAgICAgICAgICAgIGxldCBoaWRkZW5JbnB1dCA9IHRoaXMuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcbiAgICAgICAgICAgICAgICBoaWRkZW5JbnB1dC5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnaGlkZGVuJyk7XG4gICAgICAgICAgICAgICAgaGlkZGVuSW5wdXQuc2V0QXR0cmlidXRlKCduYW1lJywgaW5wdXQuZ2V0QXR0cmlidXRlKCduYW1lJykpO1xuICAgICAgICAgICAgICAgIGhpZGRlbklucHV0LnZhbHVlID0gJzAnO1xuXG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZChoaWRkZW5JbnB1dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFN0YXJ0Rm9ybTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtLmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcblxubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoZm9ybSkge1xuICAgIGNvbnN0IHN1Ym1pdEJ1dHRvbiA9IG5ldyBGb3JtQnV0dG9uKGZvcm0ucXVlcnlTZWxlY3RvcignYnV0dG9uW3R5cGU9c3VibWl0XScpKTtcblxuICAgIGZvcm0uYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICBzdWJtaXRCdXR0b24ubWFya0FzQnVzeSgpO1xuICAgIH0pO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9mb3JtLWJ1dHRvbi1zcGlubmVyLmpzIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoaW5wdXQpIHtcbiAgICBsZXQgaW5wdXRWYWx1ZSA9IGlucHV0LnZhbHVlO1xuXG4gICAgd2luZG93LnNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICBpbnB1dC5mb2N1cygpO1xuICAgICAgICBpbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICBpbnB1dC52YWx1ZSA9IGlucHV0VmFsdWU7XG4gICAgfSwgMSk7XG59O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2Zvcm0tZmllbGQtZm9jdXNlci5qcyIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGNvbnRyb2xFbGVtZW50cykge1xuICAgIGxldCBpbml0aWFsaXplID0gZnVuY3Rpb24gKGNvbnRyb2xFbGVtZW50KSB7XG4gICAgICAgIGNvbnRyb2xFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2J0bicsICdidG4tbGluaycpO1xuICAgIH07XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGNvbnRyb2xFbGVtZW50cy5sZW5ndGg7IGkrKykge1xuICAgICAgICBpbml0aWFsaXplKGNvbnRyb2xFbGVtZW50c1tpXSk7XG4gICAgfVxufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RhbC1jb250cm9sLmpzIiwiY2xhc3MgQmFkZ2VDb2xsZWN0aW9uIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05vZGVMaXN0fSBiYWRnZXNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoYmFkZ2VzKSB7XG4gICAgICAgIHRoaXMuYmFkZ2VzID0gYmFkZ2VzO1xuICAgIH07XG5cbiAgICBhcHBseVVuaWZvcm1XaWR0aCAoKSB7XG4gICAgICAgIGxldCBncmVhdGVzdFdpZHRoID0gdGhpcy5fZGVyaXZlR3JlYXRlc3RXaWR0aCgpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmJhZGdlcywgKGJhZGdlKSA9PiB7XG4gICAgICAgICAgICBiYWRnZS5zdHlsZS53aWR0aCA9IGdyZWF0ZXN0V2lkdGggKyAncHgnO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge251bWJlcn1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9kZXJpdmVHcmVhdGVzdFdpZHRoICgpIHtcbiAgICAgICAgbGV0IGdyZWF0ZXN0V2lkdGggPSAwO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmJhZGdlcywgKGJhZGdlKSA9PiB7XG4gICAgICAgICAgICBpZiAoYmFkZ2Uub2Zmc2V0V2lkdGggPiBncmVhdGVzdFdpZHRoKSB7XG4gICAgICAgICAgICAgICAgZ3JlYXRlc3RXaWR0aCA9IGJhZGdlLm9mZnNldFdpZHRoO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gZ3JlYXRlc3RXaWR0aDtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQmFkZ2VDb2xsZWN0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2JhZGdlLWNvbGxlY3Rpb24uanMiLCJsZXQgQ29va2llT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi9lbGVtZW50L2Nvb2tpZS1vcHRpb25zLW1vZGFsJyk7XG5cbmNsYXNzIENvb2tpZU9wdGlvbnMge1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCwgY29va2llT3B0aW9uc01vZGFsLCBhY3Rpb25CYWRnZSwgc3RhdHVzRWxlbWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9uc01vZGFsID0gY29va2llT3B0aW9uc01vZGFsO1xuICAgICAgICB0aGlzLmFjdGlvbkJhZGdlID0gYWN0aW9uQmFkZ2U7XG4gICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudCA9IHN0YXR1c0VsZW1lbnQ7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldE1vZGFsT3BlbmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy5tb2RhbC5vcGVuZWQnO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMubW9kYWwuY2xvc2VkJztcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGxldCBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5pc0VtcHR5KCkpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ25vdCBlbmFibGVkJztcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGlvbkJhZGdlLm1hcmtOb3RFbmFibGVkKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudC5pbm5lclRleHQgPSAnZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrRW5hYmxlZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY29va2llT3B0aW9uc01vZGFsLmluaXQoKTtcblxuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lcigpO1xuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zLmdldE1vZGFsQ2xvc2VkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBDb29raWVPcHRpb25zO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2Nvb2tpZS1vcHRpb25zLmpzIiwiY2xhc3MgQWN0aW9uQmFkZ2Uge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgbWFya0VuYWJsZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWN0aW9uLWJhZGdlLWVuYWJsZWQnKTtcbiAgICB9XG5cbiAgICBtYXJrTm90RW5hYmxlZCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdhY3Rpb24tYmFkZ2UtZW5hYmxlZCcpO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBBY3Rpb25CYWRnZTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZS5qcyIsImxldCBic24gPSByZXF1aXJlKCdib290c3RyYXAubmF0aXZlJyk7XG5sZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uLy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xuXG5jbGFzcyBBbGVydCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcblxuICAgICAgICBsZXQgY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jbG9zZScpO1xuICAgICAgICBpZiAoY2xvc2VCdXR0b24pIHtcbiAgICAgICAgICAgIGNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2xvc2VCdXR0b25DbGlja0V2ZW50SGFuZGxlci5iaW5kKHRoaXMpKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIHNldFN0eWxlIChzdHlsZSkge1xuICAgICAgICB0aGlzLl9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMoKTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWxlcnQtJyArIHN0eWxlKTtcbiAgICB9O1xuXG4gICAgd3JhcENvbnRlbnRJbkNvbnRhaW5lciAoKSB7XG4gICAgICAgIGxldCBjb250YWluZXIgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmNsYXNzTGlzdC5hZGQoJ2NvbnRhaW5lcicpO1xuXG4gICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSB0aGlzLmVsZW1lbnQuaW5uZXJIVE1MO1xuICAgICAgICB0aGlzLmVsZW1lbnQuaW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZENoaWxkKGNvbnRhaW5lcik7XG4gICAgfTtcblxuICAgIF9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMgKCkge1xuICAgICAgICBsZXQgcHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCA9ICdhbGVydC0nO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuZm9yRWFjaCgoY2xhc3NOYW1lLCBpbmRleCwgY2xhc3NMaXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoY2xhc3NOYW1lLmluZGV4T2YocHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCkgPT09IDApIHtcbiAgICAgICAgICAgICAgICBjbGFzc0xpc3QucmVtb3ZlKGNsYXNzTmFtZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfY2xvc2VCdXR0b25DbGlja0V2ZW50SGFuZGxlciAoKSB7XG4gICAgICAgIGxldCByZWxhdGVkRmllbGRJZCA9IHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtZm9yJyk7XG4gICAgICAgIGlmIChyZWxhdGVkRmllbGRJZCkge1xuICAgICAgICAgICAgbGV0IHJlbGF0ZWRGaWVsZCA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHJlbGF0ZWRGaWVsZElkKTtcblxuICAgICAgICAgICAgaWYgKHJlbGF0ZWRGaWVsZCkge1xuICAgICAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIocmVsYXRlZEZpZWxkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGxldCBic25BbGVydCA9IG5ldyBic24uQWxlcnQodGhpcy5lbGVtZW50KTtcbiAgICAgICAgYnNuQWxlcnQuY2xvc2UoKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQWxlcnQ7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hbGVydC5qcyIsImxldCBmb3JtRmllbGRGb2N1c2VyID0gcmVxdWlyZSgnLi4vLi4vZm9ybS1maWVsZC1mb2N1c2VyJyk7XG5cbmNsYXNzIENvb2tpZU9wdGlvbnNNb2RhbCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1jbG9zZV0nKTtcbiAgICAgICAgdGhpcy5hZGRCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1hZGQtYnV0dG9uJyk7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9YXBwbHknKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5fYWRkUmVtb3ZlQWN0aW9ucygpO1xuICAgICAgICB0aGlzLl9hZGRFdmVudExpc3RlbmVycygpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRPcGVuZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2Nvb2tpZS1vcHRpb25zLW1vZGFsLm9wZW5lZCc7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0Q2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5jbG9zZWQnO1xuICAgIH1cblxuICAgIF9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCBjb29raWVEYXRhUm93Q291bnQgPSB0aGlzLnRhYmxlQm9keS5xdWVyeVNlbGVjdG9yQWxsKCcuanMtY29va2llJykubGVuZ3RoO1xuICAgICAgICBsZXQgcmVtb3ZlQWN0aW9uID0gZXZlbnQudGFyZ2V0O1xuICAgICAgICBsZXQgdGFibGVSb3cgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5nZXRFbGVtZW50QnlJZChyZW1vdmVBY3Rpb24uZ2V0QXR0cmlidXRlKCdkYXRhLWZvcicpKTtcblxuICAgICAgICBpZiAoY29va2llRGF0YVJvd0NvdW50ID09PSAxKSB7XG4gICAgICAgICAgICBsZXQgbmFtZUlucHV0ID0gdGFibGVSb3cucXVlcnlTZWxlY3RvcignLm5hbWUgaW5wdXQnKTtcblxuICAgICAgICAgICAgbmFtZUlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgICAgICB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcudmFsdWUgaW5wdXQnKS52YWx1ZSA9ICcnO1xuXG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKG5hbWVJbnB1dCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0YWJsZVJvdy5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHRhYmxlUm93KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0tleWJvYXJkRXZlbnR9IGV2ZW50XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfaW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgaWYgKGV2ZW50LnR5cGUgPT09ICdrZXlkb3duJyAmJiBldmVudC5rZXkgPT09ICdFbnRlcicpIHtcbiAgICAgICAgICAgIHRoaXMuYXBwbHlCdXR0b24uY2xpY2soKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfYWRkRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICBsZXQgc2hvd25FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50YWJsZUJvZHkgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcigndGJvZHknKTtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNUYWJsZUJvZHkgPSB0aGlzLnRhYmxlQm9keS5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHRoaXMudGFibGVCb2R5LnF1ZXJ5U2VsZWN0b3IoJy5qcy1jb29raWU6bGFzdC1vZi10eXBlIC5uYW1lIGlucHV0JykpO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnNNb2RhbC5nZXRPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRkZW5FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnNNb2RhbC5nZXRDbG9zZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGFibGVCb2R5LnBhcmVudE5vZGUucmVwbGFjZUNoaWxkKHRoaXMucHJldmlvdXNUYWJsZUJvZHksIHRoaXMudGFibGVCb2R5KTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgYWRkQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgbGV0IHRhYmxlUm93ID0gdGhpcy5fY3JlYXRlVGFibGVSb3coKTtcbiAgICAgICAgICAgIGxldCByZW1vdmVBY3Rpb24gPSB0aGlzLl9jcmVhdGVSZW1vdmVBY3Rpb24odGFibGVSb3cuZ2V0QXR0cmlidXRlKCdkYXRhLWluZGV4JykpO1xuXG4gICAgICAgICAgICB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcucmVtb3ZlJykuYXBwZW5kQ2hpbGQocmVtb3ZlQWN0aW9uKTtcblxuICAgICAgICAgICAgdGhpcy50YWJsZUJvZHkuYXBwZW5kQ2hpbGQodGFibGVSb3cpO1xuICAgICAgICAgICAgdGhpcy5fYWRkUmVtb3ZlQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyKHJlbW92ZUFjdGlvbik7XG5cbiAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIodGFibGVSb3cucXVlcnlTZWxlY3RvcignLm5hbWUgaW5wdXQnKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Nob3duLmJzLm1vZGFsJywgc2hvd25FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hpZGRlbi5icy5tb2RhbCcsIGhpZGRlbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmFkZEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGFkZEJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lcik7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuanMtcmVtb3ZlJyksIChyZW1vdmVBY3Rpb24pID0+IHtcbiAgICAgICAgICAgIHRoaXMuX2FkZFJlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lcihyZW1vdmVBY3Rpb24pO1xuICAgICAgICB9KTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5uYW1lIGlucHV0LCAudmFsdWUgaW5wdXQnKSwgKGlucHV0KSA9PiB7XG4gICAgICAgICAgICBpbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXlkb3duJywgdGhpcy5faW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9hZGRSZW1vdmVBY3Rpb25zICgpIHtcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcucmVtb3ZlJyksIChyZW1vdmVDb250YWluZXIsIGluZGV4KSA9PiB7XG4gICAgICAgICAgICByZW1vdmVDb250YWluZXIuYXBwZW5kQ2hpbGQodGhpcy5fY3JlYXRlUmVtb3ZlQWN0aW9uKGluZGV4KSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IHJlbW92ZUFjdGlvblxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2FkZFJlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lciAocmVtb3ZlQWN0aW9uKSB7XG4gICAgICAgIHJlbW92ZUFjdGlvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX3JlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IGluZGV4XG4gICAgICogQHJldHVybnMge0VsZW1lbnQgfCBudWxsfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVJlbW92ZUFjdGlvbiAoaW5kZXgpIHtcbiAgICAgICAgbGV0IHJlbW92ZUFjdGlvbkNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICByZW1vdmVBY3Rpb25Db250YWluZXIuaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtdHJhc2gtbyBqcy1yZW1vdmVcIiB0aXRsZT1cIlJlbW92ZSB0aGlzIGNvb2tpZVwiIGRhdGEtZm9yPVwiY29va2llLWRhdGEtcm93LScgKyBpbmRleCArICdcIj48L2k+JztcblxuICAgICAgICByZXR1cm4gcmVtb3ZlQWN0aW9uQ29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5qcy1yZW1vdmUnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge05vZGV9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlVGFibGVSb3cgKCkge1xuICAgICAgICBsZXQgbmV4dENvb2tpZUluZGV4ID0gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1uZXh0LWNvb2tpZS1pbmRleCcpO1xuICAgICAgICBsZXQgbGFzdFRhYmxlUm93ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1jb29raWUnKTtcbiAgICAgICAgbGV0IHRhYmxlUm93ID0gbGFzdFRhYmxlUm93LmNsb25lTm9kZSh0cnVlKTtcbiAgICAgICAgbGV0IG5hbWVJbnB1dCA9IHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lIGlucHV0Jyk7XG4gICAgICAgIGxldCB2YWx1ZUlucHV0ID0gdGFibGVSb3cucXVlcnlTZWxlY3RvcignLnZhbHVlIGlucHV0Jyk7XG5cbiAgICAgICAgbmFtZUlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgIG5hbWVJbnB1dC5zZXRBdHRyaWJ1dGUoJ25hbWUnLCAnY29va2llc1snICsgbmV4dENvb2tpZUluZGV4ICsgJ11bbmFtZV0nKTtcbiAgICAgICAgbmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2tleXVwJywgdGhpcy5faW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdmFsdWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICB2YWx1ZUlucHV0LnNldEF0dHJpYnV0ZSgnbmFtZScsICdjb29raWVzWycgKyBuZXh0Q29va2llSW5kZXggKyAnXVt2YWx1ZV0nKTtcbiAgICAgICAgdmFsdWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXl1cCcsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG5cbiAgICAgICAgdGFibGVSb3cuc2V0QXR0cmlidXRlKCdkYXRhLWluZGV4JywgbmV4dENvb2tpZUluZGV4KTtcbiAgICAgICAgdGFibGVSb3cuc2V0QXR0cmlidXRlKCdpZCcsICdjb29raWUtZGF0YS1yb3ctJyArIG5leHRDb29raWVJbmRleCk7XG4gICAgICAgIHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5yZW1vdmUnKS5pbm5lckhUTUwgPSAnJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdkYXRhLW5leHQtY29va2llLWluZGV4JywgcGFyc2VJbnQobmV4dENvb2tpZUluZGV4LCAxMCkgKyAxKTtcblxuICAgICAgICByZXR1cm4gdGFibGVSb3c7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzRW1wdHkgKCkge1xuICAgICAgICBsZXQgaXNFbXB0eSA9IHRydWU7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbnB1dCcpLCAoaW5wdXQpID0+IHtcbiAgICAgICAgICAgIGlmIChpc0VtcHR5ICYmIGlucHV0LnZhbHVlLnRyaW0oKSAhPT0gJycpIHtcbiAgICAgICAgICAgICAgICBpc0VtcHR5ID0gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBpc0VtcHR5O1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQ29va2llT3B0aW9uc01vZGFsO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvY29va2llLW9wdGlvbnMtbW9kYWwuanMiLCJsZXQgSWNvbiA9IHJlcXVpcmUoJy4vaWNvbicpO1xuXG5jbGFzcyBGb3JtQnV0dG9uIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICBsZXQgaWNvbkVsZW1lbnQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoSWNvbi5nZXRTZWxlY3RvcigpKTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmljb24gPSBpY29uRWxlbWVudCA/IG5ldyBJY29uKGljb25FbGVtZW50KSA6IG51bGw7XG4gICAgfVxuXG4gICAgbWFya0FzQnVzeSAoKSB7XG4gICAgICAgIGlmICh0aGlzLmljb24pIHtcbiAgICAgICAgICAgIHRoaXMuaWNvbi5zZXRCdXN5KCk7XG4gICAgICAgICAgICB0aGlzLmRlRW1waGFzaXplKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBtYXJrQXNBdmFpbGFibGUgKCkge1xuICAgICAgICBpZiAodGhpcy5pY29uKSB7XG4gICAgICAgICAgICB0aGlzLmljb24uc2V0QXZhaWxhYmxlKCdmYS1jYXJldC1yaWdodCcpO1xuICAgICAgICAgICAgdGhpcy51bkRlRW1waGFzaXplKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBtYXJrU3VjY2VlZGVkICgpIHtcbiAgICAgICAgaWYgKHRoaXMuaWNvbikge1xuICAgICAgICAgICAgdGhpcy5pY29uLnNldFN1Y2Nlc3NmdWwoKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIGRpc2FibGUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdkaXNhYmxlZCcsICdkaXNhYmxlZCcpO1xuICAgIH1cblxuICAgIGVuYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoJ2Rpc2FibGVkJyk7XG4gICAgfVxuXG4gICAgZGVFbXBoYXNpemUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGUtZW1waGFzaXplJyk7XG4gICAgfTtcblxuICAgIHVuRGVFbXBoYXNpemUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGUtZW1waGFzaXplJyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtQnV0dG9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24uanMiLCJsZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uLy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMudXNlcm5hbWVJbnB1dCA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW25hbWU9aHR0cC1hdXRoLXVzZXJuYW1lXScpO1xuICAgICAgICB0aGlzLnBhc3N3b3JkSW5wdXQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPWh0dHAtYXV0aC1wYXNzd29yZF0nKTtcbiAgICAgICAgdGhpcy5hcHBseUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1hcHBseV0nKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1jbG9zZV0nKTtcbiAgICAgICAgdGhpcy5jbGVhckJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1jbGVhcl0nKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c1VzZXJuYW1lID0gbnVsbDtcbiAgICAgICAgdGhpcy5wcmV2aW91c1Bhc3N3b3JkID0gbnVsbDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRPcGVuZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2Nvb2tpZS1vcHRpb25zLW1vZGFsLm9wZW5lZCc7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0Q2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5jbG9zZWQnO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLl9hZGRFdmVudExpc3RlbmVycygpO1xuICAgIH07XG5cbiAgICBpc0VtcHR5ICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZS50cmltKCkgPT09ICcnICYmIHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZS50cmltKCkgPT09ICcnO1xuICAgIH07XG5cbiAgICBfYWRkRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICBsZXQgc2hvd25FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5wcmV2aW91c1VzZXJuYW1lID0gdGhpcy51c2VybmFtZUlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNQYXNzd29yZCA9IHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZS50cmltKCk7XG5cbiAgICAgICAgICAgIGxldCB1c2VybmFtZSA9IHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgICAgICBsZXQgcGFzc3dvcmQgPSB0aGlzLnBhc3N3b3JkSW5wdXQudmFsdWUudHJpbSgpO1xuXG4gICAgICAgICAgICBsZXQgZm9jdXNlZElucHV0ID0gKHVzZXJuYW1lID09PSAnJyB8fCAodXNlcm5hbWUgIT09ICcnICYmIHBhc3N3b3JkICE9PSAnJykpXG4gICAgICAgICAgICAgICAgPyB0aGlzLnVzZXJuYW1lSW5wdXRcbiAgICAgICAgICAgICAgICA6IHRoaXMucGFzc3dvcmRJbnB1dDtcblxuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcihmb2N1c2VkSW5wdXQpO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGhpZGRlbkV2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy51c2VybmFtZUlucHV0LnZhbHVlID0gdGhpcy5wcmV2aW91c1VzZXJuYW1lO1xuICAgICAgICAgICAgdGhpcy5wYXNzd29yZElucHV0LnZhbHVlID0gdGhpcy5wcmV2aW91c1Bhc3N3b3JkO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbGVhckJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICAgICAgdGhpcy5wYXNzd29yZElucHV0LnZhbHVlID0gJyc7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Nob3duLmJzLm1vZGFsJywgc2hvd25FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hpZGRlbi5icy5tb2RhbCcsIGhpZGRlbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmNsZWFyQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLnVzZXJuYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMucGFzc3dvcmRJbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXlkb3duJywgdGhpcy5faW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtLZXlib2FyZEV2ZW50fSBldmVudFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC50eXBlID09PSAna2V5ZG93bicgJiYgZXZlbnQua2V5ID09PSAnRW50ZXInKSB7XG4gICAgICAgICAgICB0aGlzLmFwcGx5QnV0dG9uLmNsaWNrKCk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbC5qcyIsImNsYXNzIEljb24ge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgc3RhdGljIGdldENsYXNzICgpIHtcbiAgICAgICAgcmV0dXJuICdmYSc7XG4gICAgfVxuXG4gICAgc3RhdGljIGdldFNlbGVjdG9yICgpIHtcbiAgICAgICAgcmV0dXJuICcuJyArIEljb24uZ2V0Q2xhc3MoKTtcbiAgICB9O1xuXG4gICAgc2V0QnVzeSAoKSB7XG4gICAgICAgIHRoaXMucmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcygpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZmEtc3Bpbm5lcicsICdmYS1zcGluJyk7XG4gICAgfTtcblxuICAgIHNldEF2YWlsYWJsZSAoYWN0aXZlSWNvbkNsYXNzID0gbnVsbCkge1xuICAgICAgICB0aGlzLnJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMoKTtcblxuICAgICAgICBpZiAoYWN0aXZlSWNvbkNsYXNzICE9PSBudWxsKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZChhY3RpdmVJY29uQ2xhc3MpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHNldFN1Y2Nlc3NmdWwgKCkge1xuICAgICAgICB0aGlzLnJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMoKTtcbiAgICAgICAgdGhpcy5zZXRBdmFpbGFibGUoJ2ZhLWNoZWNrJyk7XG4gICAgfVxuXG4gICAgcmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcyAoKSB7XG4gICAgICAgIGxldCBjbGFzc2VzVG9SZXRhaW4gPSBbXG4gICAgICAgICAgICBJY29uLmdldENsYXNzKCksXG4gICAgICAgICAgICBJY29uLmdldENsYXNzKCkgKyAnLWZ3J1xuICAgICAgICBdO1xuXG4gICAgICAgIGxldCBwcmVzZW50YXRpb25hbENsYXNzUHJlZml4ID0gSWNvbi5nZXRDbGFzcygpICsgJy0nO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuZm9yRWFjaCgoY2xhc3NOYW1lLCBpbmRleCwgY2xhc3NMaXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIWNsYXNzZXNUb1JldGFpbi5pbmNsdWRlcyhjbGFzc05hbWUpICYmIGNsYXNzTmFtZS5pbmRleE9mKHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEljb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9pY29uLmpzIiwibGV0IFByb2dyZXNzaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4vcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QnKTtcblxuY2xhc3MgQ3Jhd2xpbmdMaXN0ZWRUZXN0IGV4dGVuZHMgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0IHtcbiAgICByZW5kZXJGcm9tTGlzdGVkVGVzdCAobGlzdGVkVGVzdCkge1xuICAgICAgICBzdXBlci5yZW5kZXJGcm9tTGlzdGVkVGVzdChsaXN0ZWRUZXN0KTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByb2Nlc3NlZC11cmwtY291bnQnKS5pbm5lclRleHQgPSBsaXN0ZWRUZXN0LmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXByb2Nlc3NlZC11cmwtY291bnQnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5kaXNjb3ZlcmVkLXVybC1jb3VudCcpLmlubmVyVGV4dCA9IGxpc3RlZFRlc3QuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtZGlzY292ZXJlZC11cmwtY291bnQnKTtcbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnQ3Jhd2xpbmdMaXN0ZWRUZXN0JztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IENyYXdsaW5nTGlzdGVkVGVzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2NyYXdsaW5nLWxpc3RlZC10ZXN0LmpzIiwiY2xhc3MgTGlzdGVkVGVzdCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5pbml0KGVsZW1lbnQpO1xuICAgIH1cblxuICAgIGluaXQgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICB9XG5cbiAgICBlbmFibGUgKCkge307XG5cbiAgICBnZXRUZXN0SWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10ZXN0LWlkJyk7XG4gICAgfTtcblxuICAgIGdldFN0YXRlICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcbiAgICB9XG5cbiAgICBpc0ZpbmlzaGVkICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZpbmlzaGVkJyk7XG4gICAgfVxuXG4gICAgcmVuZGVyRnJvbUxpc3RlZFRlc3QgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgaWYgKHRoaXMuaXNGaW5pc2hlZCgpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5nZXRTdGF0ZSgpICE9PSBsaXN0ZWRUZXN0LmdldFN0YXRlKCkpIHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZChsaXN0ZWRUZXN0LmVsZW1lbnQsIHRoaXMuZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLmluaXQobGlzdGVkVGVzdC5lbGVtZW50KTtcbiAgICAgICAgICAgIHRoaXMuZW5hYmxlKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnTGlzdGVkVGVzdCc7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBMaXN0ZWRUZXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvbGlzdGVkLXRlc3QuanMiLCJsZXQgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi9wcm9ncmVzc2luZy1saXN0ZWQtdGVzdCcpO1xubGV0IFRlc3RSZXN1bHRSZXRyaWV2ZXIgPSByZXF1aXJlKCcuLi8uLi8uLi9zZXJ2aWNlcy90ZXN0LXJlc3VsdC1yZXRyaWV2ZXInKTtcblxuY2xhc3MgUHJlcGFyaW5nTGlzdGVkVGVzdCBleHRlbmRzIFByb2dyZXNzaW5nTGlzdGVkVGVzdCB7XG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5faW5pdGlhbGlzZVJlc3VsdFJldHJpZXZlcigpO1xuICAgIH07XG5cbiAgICBfaW5pdGlhbGlzZVJlc3VsdFJldHJpZXZlciAoKSB7XG4gICAgICAgIGxldCBwcmVwYXJpbmdFbGVtZW50ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcmVwYXJpbmcnKTtcbiAgICAgICAgbGV0IHRlc3RSZXN1bHRzUmV0cmlldmVyID0gbmV3IFRlc3RSZXN1bHRSZXRyaWV2ZXIocHJlcGFyaW5nRWxlbWVudCk7XG5cbiAgICAgICAgcHJlcGFyaW5nRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKHRlc3RSZXN1bHRzUmV0cmlldmVyLmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCAocmV0cmlldmVkRXZlbnQpID0+IHtcbiAgICAgICAgICAgIGxldCBwYXJlbnROb2RlID0gdGhpcy5lbGVtZW50LnBhcmVudE5vZGU7XG4gICAgICAgICAgICBsZXQgcmV0cmlldmVkTGlzdGVkVGVzdCA9IHJldHJpZXZlZEV2ZW50LmRldGFpbDtcbiAgICAgICAgICAgIHJldHJpZXZlZExpc3RlZFRlc3QuY2xhc3NMaXN0LmFkZCgnZmFkZS1vdXQnKTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3RyYW5zaXRpb25lbmQnLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgcGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQocmV0cmlldmVkTGlzdGVkVGVzdCwgdGhpcy5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQgPSByZXRyaWV2ZWRFdmVudC5kZXRhaWw7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2ZhZGUtaW4nKTtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZmFkZS1vdXQnKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZmFkZS1vdXQnKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGVzdFJlc3VsdHNSZXRyaWV2ZXIuaW5pdCgpO1xuICAgIH07XG5cbiAgICBnZXRUeXBlICgpIHtcbiAgICAgICAgcmV0dXJuICdQcmVwYXJpbmdMaXN0ZWRUZXN0JztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFByZXBhcmluZ0xpc3RlZFRlc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcmVwYXJpbmctbGlzdGVkLXRlc3QuanMiLCJsZXQgTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4vbGlzdGVkLXRlc3QnKTtcbmxldCBQcm9ncmVzc0JhciA9IHJlcXVpcmUoJy4uL3Byb2dyZXNzLWJhcicpO1xuXG5jbGFzcyBQcm9ncmVzc2luZ0xpc3RlZFRlc3QgZXh0ZW5kcyBMaXN0ZWRUZXN0IHtcbiAgICBpbml0IChlbGVtZW50KSB7XG4gICAgICAgIHN1cGVyLmluaXQoZWxlbWVudCk7XG5cbiAgICAgICAgbGV0IHByb2dyZXNzQmFyRWxlbWVudCA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJvZ3Jlc3MtYmFyJyk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBwcm9ncmVzc0JhckVsZW1lbnQgPyBuZXcgUHJvZ3Jlc3NCYXIocHJvZ3Jlc3NCYXJFbGVtZW50KSA6IG51bGw7XG4gICAgfVxuXG4gICAgZ2V0Q29tcGxldGlvblBlcmNlbnQgKCkge1xuICAgICAgICBsZXQgY29tcGxldGlvblBlcmNlbnQgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWNvbXBsZXRpb24tcGVyY2VudCcpO1xuXG4gICAgICAgIGlmICh0aGlzLmlzRmluaXNoZWQoKSAmJiBjb21wbGV0aW9uUGVyY2VudCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIDEwMDtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBwYXJzZUZsb2F0KHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtY29tcGxldGlvbi1wZXJjZW50JykpO1xuICAgIH1cblxuICAgIHNldENvbXBsZXRpb25QZXJjZW50IChjb21wbGV0aW9uUGVyY2VudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBsZXRpb24tcGVyY2VudCcsIGNvbXBsZXRpb25QZXJjZW50KTtcbiAgICB9O1xuXG4gICAgcmVuZGVyRnJvbUxpc3RlZFRlc3QgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgc3VwZXIucmVuZGVyRnJvbUxpc3RlZFRlc3QobGlzdGVkVGVzdCk7XG5cbiAgICAgICAgaWYgKHRoaXMuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSA9PT0gbGlzdGVkVGVzdC5nZXRDb21wbGV0aW9uUGVyY2VudCgpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnNldENvbXBsZXRpb25QZXJjZW50KGxpc3RlZFRlc3QuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSk7XG5cbiAgICAgICAgaWYgKHRoaXMucHJvZ3Jlc3NCYXIpIHtcbiAgICAgICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQodGhpcy5nZXRDb21wbGV0aW9uUGVyY2VudCgpKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBnZXRUeXBlICgpIHtcbiAgICAgICAgcmV0dXJuICdQcm9ncmVzc2luZ0xpc3RlZFRlc3QnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QuanMiLCJjbGFzcyBQcm9ncmVzc0JhciB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICB9XG5cbiAgICBzZXRDb21wbGV0aW9uUGVyY2VudCAoY29tcGxldGlvblBlcmNlbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnN0eWxlLndpZHRoID0gY29tcGxldGlvblBlcmNlbnQgKyAnJSc7XG4gICAgICAgIHRoaXMuZWxlbWVudC5zZXRBdHRyaWJ1dGUoJ2FyaWEtdmFsdWVub3cnLCBjb21wbGV0aW9uUGVyY2VudCk7XG4gICAgfVxuXG4gICAgc2V0U3R5bGUgKHN0eWxlKSB7XG4gICAgICAgIHRoaXMuX3JlbW92ZVByZXNlbnRhdGlvbmFsQ2xhc3NlcygpO1xuXG4gICAgICAgIGlmIChzdHlsZSA9PT0gJ3dhcm5pbmcnKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgncHJvZ3Jlc3MtYmFyLXdhcm5pbmcnKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIF9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMgKCkge1xuICAgICAgICBsZXQgcHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCA9ICdwcm9ncmVzcy1iYXItJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNsYXNzTmFtZS5pbmRleE9mKHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFByb2dyZXNzQmFyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyLmpzIiwiY2xhc3MgU29ydENvbnRyb2wge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMua2V5cyA9IEpTT04ucGFyc2UoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc29ydC1rZXlzJykpO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRTb3J0UmVxdWVzdGVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdzb3J0LWNvbnRyb2wuc29ydC5yZXF1ZXN0ZWQnO1xuICAgIH07XG5cbiAgICBfY2xpY2tFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ3NvcnRlZCcpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoU29ydENvbnRyb2wuZ2V0U29ydFJlcXVlc3RlZEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICBkZXRhaWw6IHtcbiAgICAgICAgICAgICAgICBrZXlzOiB0aGlzLmtleXNcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSkpO1xuICAgIH07XG5cbiAgICBzZXRTb3J0ZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnc29ydGVkJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdsaW5rJyk7XG4gICAgfTtcblxuICAgIHNldE5vdFNvcnRlZCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdzb3J0ZWQnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2xpbmsnKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRDb250cm9sO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sLmpzIiwiY2xhc3MgU29ydGFibGVJdGVtIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnNvcnRWYWx1ZXMgPSBKU09OLnBhcnNlKGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvcnQtdmFsdWVzJykpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30ga2V5XG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7Kn1cbiAgICAgKi9cbiAgICBnZXRTb3J0VmFsdWUgKGtleSkge1xuICAgICAgICByZXR1cm4gdGhpcy5zb3J0VmFsdWVzW2tleV07XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRhYmxlSXRlbTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnRhYmxlLWl0ZW0uanMiLCJsZXQgVGFzayA9IHJlcXVpcmUoJy4vdGFzaycpO1xuXG5jbGFzcyBUYXNrTGlzdCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5wYWdlSW5kZXggPSBlbGVtZW50ID8gcGFyc2VJbnQoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMCkgOiBudWxsO1xuICAgICAgICB0aGlzLnRhc2tzID0ge307XG5cbiAgICAgICAgaWYgKGVsZW1lbnQpIHtcbiAgICAgICAgICAgIFtdLmZvckVhY2guY2FsbChlbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrJyksICh0YXNrRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCB0YXNrID0gbmV3IFRhc2sodGFza0VsZW1lbnQpO1xuICAgICAgICAgICAgICAgIHRoaXMudGFza3NbdGFzay5nZXRJZCgpXSA9IHRhc2s7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtudW1iZXIgfCBudWxsfVxuICAgICAqL1xuICAgIGdldFBhZ2VJbmRleCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnBhZ2VJbmRleDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBoYXNQYWdlSW5kZXggKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5wYWdlSW5kZXggIT09IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmdbXX0gc3RhdGVzXG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7VGFza1tdfVxuICAgICAqL1xuICAgIGdldFRhc2tzQnlTdGF0ZXMgKHN0YXRlcykge1xuICAgICAgICBjb25zdCBzdGF0ZXNMZW5ndGggPSBzdGF0ZXMubGVuZ3RoO1xuICAgICAgICBsZXQgdGFza3MgPSBbXTtcblxuICAgICAgICBmb3IgKGxldCBzdGF0ZUluZGV4ID0gMDsgc3RhdGVJbmRleCA8IHN0YXRlc0xlbmd0aDsgc3RhdGVJbmRleCsrKSB7XG4gICAgICAgICAgICBsZXQgc3RhdGUgPSBzdGF0ZXNbc3RhdGVJbmRleF07XG5cbiAgICAgICAgICAgIHRhc2tzID0gdGFza3MuY29uY2F0KHRoaXMuZ2V0VGFza3NCeVN0YXRlKHN0YXRlKSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFza3M7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBzdGF0ZVxuICAgICAqXG4gICAgICogQHJldHVybnMge1Rhc2tbXX1cbiAgICAgKi9cbiAgICBnZXRUYXNrc0J5U3RhdGUgKHN0YXRlKSB7XG4gICAgICAgIGxldCB0YXNrcyA9IFtdO1xuICAgICAgICBPYmplY3Qua2V5cyh0aGlzLnRhc2tzKS5mb3JFYWNoKCh0YXNrSWQpID0+IHtcbiAgICAgICAgICAgIGxldCB0YXNrID0gdGhpcy50YXNrc1t0YXNrSWRdO1xuXG4gICAgICAgICAgICBpZiAodGFzay5nZXRTdGF0ZSgpID09PSBzdGF0ZSkge1xuICAgICAgICAgICAgICAgIHRhc2tzLnB1c2godGFzayk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiB0YXNrcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtUYXNrTGlzdH0gdXBkYXRlZFRhc2tMaXN0XG4gICAgICovXG4gICAgdXBkYXRlRnJvbVRhc2tMaXN0ICh1cGRhdGVkVGFza0xpc3QpIHtcbiAgICAgICAgT2JqZWN0LmtleXModXBkYXRlZFRhc2tMaXN0LnRhc2tzKS5mb3JFYWNoKCh0YXNrSWQpID0+IHtcbiAgICAgICAgICAgIGxldCB1cGRhdGVkVGFzayA9IHVwZGF0ZWRUYXNrTGlzdC50YXNrc1t0YXNrSWRdO1xuICAgICAgICAgICAgbGV0IHRhc2sgPSB0aGlzLnRhc2tzW3VwZGF0ZWRUYXNrLmdldElkKCldO1xuXG4gICAgICAgICAgICB0YXNrLnVwZGF0ZUZyb21UYXNrKHVwZGF0ZWRUYXNrKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdC5qcyIsImNsYXNzIFRhc2tRdWV1ZSB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy52YWx1ZSA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnZhbHVlJyk7XG4gICAgICAgIHRoaXMubGFiZWwgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5sYWJlbC12YWx1ZScpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmxhYmVsLnN0eWxlLndpZHRoID0gdGhpcy5sYWJlbC5nZXRBdHRyaWJ1dGUoJ2RhdGEtd2lkdGgnKSArICclJztcbiAgICB9O1xuXG4gICAgZ2V0UXVldWVJZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXF1ZXVlLWlkJyk7XG4gICAgfTtcblxuICAgIHNldFZhbHVlICh2YWx1ZSkge1xuICAgICAgICB0aGlzLnZhbHVlLmlubmVyVGV4dCA9IHZhbHVlO1xuICAgIH07XG5cbiAgICBzZXRXaWR0aCAod2lkdGgpIHtcbiAgICAgICAgdGhpcy5sYWJlbC5zdHlsZS53aWR0aCA9IHdpZHRoICsgJyUnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza1F1ZXVlO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZS5qcyIsImxldCBUYXNrUXVldWUgPSByZXF1aXJlKCcuL3Rhc2stcXVldWUnKTtcblxuY2xhc3MgVGFza1F1ZXVlcyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5xdWV1ZXMgPSB7fTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwoZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcucXVldWUnKSwgKHF1ZXVlRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHF1ZXVlID0gbmV3IFRhc2tRdWV1ZShxdWV1ZUVsZW1lbnQpO1xuICAgICAgICAgICAgcXVldWUuaW5pdCgpO1xuICAgICAgICAgICAgdGhpcy5xdWV1ZXNbcXVldWUuZ2V0UXVldWVJZCgpXSA9IHF1ZXVlO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICByZW5kZXIgKHRhc2tDb3VudCwgdGFza0NvdW50QnlTdGF0ZSkge1xuICAgICAgICBsZXQgZ2V0V2lkdGhGb3JTdGF0ZSA9IChzdGF0ZSkgPT4ge1xuICAgICAgICAgICAgaWYgKHRhc2tDb3VudCA9PT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoIXRhc2tDb3VudEJ5U3RhdGUuaGFzT3duUHJvcGVydHkoc3RhdGUpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIDA7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0YXNrQ291bnRCeVN0YXRlW3N0YXRlXSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICByZXR1cm4gTWF0aC5jZWlsKHRhc2tDb3VudEJ5U3RhdGVbc3RhdGVdIC8gdGFza0NvdW50ICogMTAwKTtcbiAgICAgICAgfTtcblxuICAgICAgICBPYmplY3Qua2V5cyh0YXNrQ291bnRCeVN0YXRlKS5mb3JFYWNoKChzdGF0ZSkgPT4ge1xuICAgICAgICAgICAgbGV0IHF1ZXVlID0gdGhpcy5xdWV1ZXNbc3RhdGVdO1xuICAgICAgICAgICAgaWYgKHF1ZXVlKSB7XG4gICAgICAgICAgICAgICAgcXVldWUuc2V0VmFsdWUodGFza0NvdW50QnlTdGF0ZVtzdGF0ZV0pO1xuICAgICAgICAgICAgICAgIHF1ZXVlLnNldFdpZHRoKGdldFdpZHRoRm9yU3RhdGUoc3RhdGUpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrUXVldWVzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZXMuanMiLCJjbGFzcyBUYXNrIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIGdldFN0YXRlICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcbiAgICB9O1xuXG4gICAgZ2V0SWQgKCkge1xuICAgICAgICByZXR1cm4gcGFyc2VJbnQodGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLWlkJyksIDEwKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1Rhc2t9IHVwZGF0ZWRUYXNrXG4gICAgICovXG4gICAgdXBkYXRlRnJvbVRhc2sgKHVwZGF0ZWRUYXNrKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZCh1cGRhdGVkVGFzay5lbGVtZW50LCB0aGlzLmVsZW1lbnQpO1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB1cGRhdGVkVGFzay5lbGVtZW50O1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFzaztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2suanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5sZXQgQWxlcnRGYWN0b3J5ID0gcmVxdWlyZSgnLi4vLi4vc2VydmljZXMvYWxlcnQtZmFjdG9yeScpO1xuXG5jbGFzcyBUZXN0QWxlcnRDb250YWluZXIge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuYWxlcnQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5hbGVydC1hbW1lbmRtZW50Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGlmICghdGhpcy5hbGVydCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IGFsZXJ0ID0gQWxlcnRGYWN0b3J5LmNyZWF0ZUZyb21Db250ZW50KHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LCBldmVudC5kZXRhaWwucmVzcG9uc2UpO1xuICAgICAgICBhbGVydC5zZXRTdHlsZSgnaW5mbycpO1xuICAgICAgICBhbGVydC53cmFwQ29udGVudEluQ29udGFpbmVyKCk7XG4gICAgICAgIGFsZXJ0LmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWxlcnQtYW1tZW5kbWVudCcpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChhbGVydC5lbGVtZW50KTtcbiAgICAgICAgdGhpcy5hbGVydCA9IGFsZXJ0O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdyZXZlYWwnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3RyYW5zaXRpb25lbmQnLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmFsZXJ0LmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgncmV2ZWFsJyk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICByZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbiAoKSB7XG4gICAgICAgIGlmICghdGhpcy5hbGVydCkge1xuICAgICAgICAgICAgSHR0cENsaWVudC5nZXQodGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11cmwtbGltaXQtbm90aWZpY2F0aW9uLXVybCcpLCAndGV4dCcsIHRoaXMuZWxlbWVudCwgJ2RlZmF1bHQnKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdEFsZXJ0Q29udGFpbmVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXIuanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5sZXQgSWNvbiA9IHJlcXVpcmUoJy4uL2VsZW1lbnQvaWNvbicpO1xuXG5jbGFzcyBUZXN0TG9ja1VubG9jayB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG4gICAgICAgIHRoaXMuZGF0YSA9IHtcbiAgICAgICAgICAgIGxvY2tlZDogSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1sb2NrZWQnKSksXG4gICAgICAgICAgICB1bmxvY2tlZDogSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11bmxvY2tlZCcpKVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmljb24gPSBuZXcgSWNvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoSWNvbi5nZXRTZWxlY3RvcigpKSk7XG4gICAgICAgIHRoaXMuYWN0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuYWN0aW9uJyk7XG4gICAgICAgIHRoaXMuZGVzY3JpcHRpb24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5kZXNjcmlwdGlvbicpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnaW52aXNpYmxlJyk7XG4gICAgICAgIHRoaXMuX3JlbmRlcigpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2RlLWVtcGhhc2l6ZScpO1xuICAgICAgICAgICAgdGhpcy5fdG9nZ2xlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfdG9nZ2xlICgpIHtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IHRoaXMuc3RhdGUgPT09ICdsb2NrZWQnID8gJ3VubG9ja2VkJyA6ICdsb2NrZWQnO1xuICAgICAgICB0aGlzLl9yZW5kZXIoKTtcbiAgICB9O1xuXG4gICAgX3JlbmRlciAoKSB7XG4gICAgICAgIHRoaXMuaWNvbi5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgbGV0IHN0YXRlRGF0YSA9IHRoaXMuZGF0YVt0aGlzLnN0YXRlXTtcblxuICAgICAgICB0aGlzLmljb24uc2V0QXZhaWxhYmxlKCdmYS0nICsgc3RhdGVEYXRhLmljb24pO1xuICAgICAgICB0aGlzLmFjdGlvbi5pbm5lclRleHQgPSBzdGF0ZURhdGEuYWN0aW9uO1xuICAgICAgICB0aGlzLmRlc2NyaXB0aW9uLmlubmVyVGV4dCA9IHN0YXRlRGF0YS5kZXNjcmlwdGlvbjtcbiAgICB9O1xuXG4gICAgX2NsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHRoaXMuaWNvbi5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2RlLWVtcGhhc2l6ZScpO1xuICAgICAgICB0aGlzLmljb24uc2V0QnVzeSgpO1xuXG4gICAgICAgIEh0dHBDbGllbnQucG9zdCh0aGlzLmRhdGFbdGhpcy5zdGF0ZV0udXJsLCB0aGlzLmVsZW1lbnQsICdkZWZhdWx0Jyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0TG9ja1VubG9jaztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rlc3QtbG9jay11bmxvY2suanMiLCJsZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbCcpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zIHtcbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQsIGh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCwgYWN0aW9uQmFkZ2UsIHN0YXR1c0VsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IGh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbDtcbiAgICAgICAgdGhpcy5hY3Rpb25CYWRnZSA9IGFjdGlvbkJhZGdlO1xuICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQgPSBzdGF0dXNFbGVtZW50O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbE9wZW5lZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLm1vZGFsLm9wZW5lZCc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldE1vZGFsQ2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdodHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMubW9kYWwuY2xvc2VkJztcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGxldCBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5pc0VtcHR5KCkpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ25vdCBlbmFibGVkJztcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGlvbkJhZGdlLm1hcmtOb3RFbmFibGVkKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudC5pbm5lclRleHQgPSAnZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrRW5hYmxlZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmluaXQoKTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lcigpO1xuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsQ2xvc2VkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy5qcyIsImxldCBMaXN0ZWRUZXN0RmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2xpc3RlZC10ZXN0LWZhY3RvcnknKTtcblxuY2xhc3MgTGlzdGVkVGVzdENvbGxlY3Rpb24ge1xuICAgIGNvbnN0cnVjdG9yICgpIHtcbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0cyA9IHt9O1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7TGlzdGVkVGVzdH0gbGlzdGVkVGVzdFxuICAgICAqL1xuICAgIGFkZCAobGlzdGVkVGVzdCkge1xuICAgICAgICB0aGlzLmxpc3RlZFRlc3RzW2xpc3RlZFRlc3QuZ2V0VGVzdElkKCldID0gbGlzdGVkVGVzdDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtMaXN0ZWRUZXN0fSBsaXN0ZWRUZXN0XG4gICAgICovXG4gICAgcmVtb3ZlIChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIGlmICh0aGlzLmNvbnRhaW5zKGxpc3RlZFRlc3QpKSB7XG4gICAgICAgICAgICBkZWxldGUgdGhpcy5saXN0ZWRUZXN0c1tsaXN0ZWRUZXN0LmdldFRlc3RJZCgpXTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0xpc3RlZFRlc3R9IGxpc3RlZFRlc3RcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGNvbnRhaW5zIChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIHJldHVybiB0aGlzLmNvbnRhaW5zVGVzdElkKGxpc3RlZFRlc3QuZ2V0VGVzdElkKCkpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gdGVzdElkXG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBjb250YWluc1Rlc3RJZCAodGVzdElkKSB7XG4gICAgICAgIHJldHVybiBPYmplY3Qua2V5cyh0aGlzLmxpc3RlZFRlc3RzKS5pbmNsdWRlcyh0ZXN0SWQpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSB0ZXN0SWRcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtMaXN0ZWRUZXN0fG51bGx9XG4gICAgICovXG4gICAgZ2V0ICh0ZXN0SWQpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuY29udGFpbnNUZXN0SWQodGVzdElkKSA/IHRoaXMubGlzdGVkVGVzdHNbdGVzdElkXSA6IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtmdW5jdGlvbn0gY2FsbGJhY2tcbiAgICAgKi9cbiAgICBmb3JFYWNoIChjYWxsYmFjaykge1xuICAgICAgICBPYmplY3Qua2V5cyh0aGlzLmxpc3RlZFRlc3RzKS5mb3JFYWNoKCh0ZXN0SWQpID0+IHtcbiAgICAgICAgICAgIGNhbGxiYWNrKHRoaXMubGlzdGVkVGVzdHNbdGVzdElkXSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05vZGVMaXN0fSBub2RlTGlzdFxuICAgICAqXG4gICAgICogQHJldHVybnMge0xpc3RlZFRlc3RDb2xsZWN0aW9ufVxuICAgICAqL1xuICAgIHN0YXRpYyBjcmVhdGVGcm9tTm9kZUxpc3QgKG5vZGVMaXN0KSB7XG4gICAgICAgIGxldCBjb2xsZWN0aW9uID0gbmV3IExpc3RlZFRlc3RDb2xsZWN0aW9uKCk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKG5vZGVMaXN0LCAobGlzdGVkVGVzdEVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIGNvbGxlY3Rpb24uYWRkKExpc3RlZFRlc3RGYWN0b3J5LmNyZWF0ZUZyb21FbGVtZW50KGxpc3RlZFRlc3RFbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBjb2xsZWN0aW9uO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBMaXN0ZWRUZXN0Q29sbGVjdGlvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uLmpzIiwibGV0IFNvcnRDb250cm9sID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9zb3J0LWNvbnRyb2wnKTtcblxuY2xhc3MgU29ydENvbnRyb2xDb2xsZWN0aW9uIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1NvcnRDb250cm9sW119IGNvbnRyb2xzXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGNvbnRyb2xzKSB7XG4gICAgICAgIHRoaXMuY29udHJvbHMgPSBjb250cm9scztcbiAgICB9O1xuXG4gICAgc2V0U29ydGVkIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuY29udHJvbHMuZm9yRWFjaCgoY29udHJvbCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNvbnRyb2wuZWxlbWVudCA9PT0gZWxlbWVudCkge1xuICAgICAgICAgICAgICAgIGNvbnRyb2wuc2V0U29ydGVkKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGNvbnRyb2wuc2V0Tm90U29ydGVkKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU29ydENvbnRyb2xDb2xsZWN0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL3NvcnQtY29udHJvbC1jb2xsZWN0aW9uLmpzIiwiY2xhc3MgU29ydGFibGVJdGVtTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtTb3J0YWJsZUl0ZW1bXX0gaXRlbXNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoaXRlbXMpIHtcbiAgICAgICAgdGhpcy5pdGVtcyA9IGl0ZW1zO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ1tdfSBrZXlzXG4gICAgICogQHJldHVybnMge1NvcnRhYmxlSXRlbVtdfVxuICAgICAqL1xuICAgIHNvcnQgKGtleXMpIHtcbiAgICAgICAgbGV0IGluZGV4ID0gW107XG4gICAgICAgIGxldCBzb3J0ZWRJdGVtcyA9IFtdO1xuXG4gICAgICAgIHRoaXMuaXRlbXMuZm9yRWFjaCgoc29ydGFibGVJdGVtLCBwb3NpdGlvbikgPT4ge1xuICAgICAgICAgICAgbGV0IHZhbHVlcyA9IFtdO1xuXG4gICAgICAgICAgICBrZXlzLmZvckVhY2goKGtleSkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCB2YWx1ZSA9IHNvcnRhYmxlSXRlbS5nZXRTb3J0VmFsdWUoa2V5KTtcbiAgICAgICAgICAgICAgICBpZiAoTnVtYmVyLmlzSW50ZWdlcih2YWx1ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSAoMS92YWx1ZSkudG9TdHJpbmcoKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB2YWx1ZXMucHVzaCh2YWx1ZSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgaW5kZXgucHVzaCh7XG4gICAgICAgICAgICAgICAgcG9zaXRpb246IHBvc2l0aW9uLFxuICAgICAgICAgICAgICAgIHZhbHVlOiB2YWx1ZXMuam9pbignLCcpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgaW5kZXguc29ydCh0aGlzLl9jb21wYXJlRnVuY3Rpb24pO1xuXG4gICAgICAgIGluZGV4LmZvckVhY2goKGluZGV4SXRlbSkgPT4ge1xuICAgICAgICAgICAgc29ydGVkSXRlbXMucHVzaCh0aGlzLml0ZW1zW2luZGV4SXRlbS5wb3NpdGlvbl0pO1xuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gc29ydGVkSXRlbXM7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBhXG4gICAgICogQHBhcmFtIHtPYmplY3R9IGJcbiAgICAgKiBAcmV0dXJucyB7bnVtYmVyfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NvbXBhcmVGdW5jdGlvbiAoYSwgYikge1xuICAgICAgICBpZiAoYS52YWx1ZSA8IGIudmFsdWUpIHtcbiAgICAgICAgICAgIHJldHVybiAtMTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChhLnZhbHVlID4gYi52YWx1ZSkge1xuICAgICAgICAgICAgcmV0dXJuIDE7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gMDtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRhYmxlSXRlbUxpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvc29ydGFibGUtaXRlbS1saXN0LmpzIiwibGV0IHVuYXZhaWxhYmxlVGFza1R5cGVNb2RhbExhdW5jaGVyID0gcmVxdWlyZSgnLi4vdW5hdmFpbGFibGUtdGFzay10eXBlLW1vZGFsLWxhdW5jaGVyJyk7XG5sZXQgVGVzdFN0YXJ0Rm9ybSA9IHJlcXVpcmUoJy4uL2Rhc2hib2FyZC90ZXN0LXN0YXJ0LWZvcm0nKTtcbmxldCBSZWNlbnRUZXN0TGlzdCA9IHJlcXVpcmUoJy4uL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0Jyk7XG5sZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeScpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMgPSByZXF1aXJlKCcuLi9tb2RlbC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMnKTtcbmxldCBDb29raWVPcHRpb25zRmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBDb29raWVPcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvY29va2llLW9wdGlvbnMnKTtcblxuY2xhc3MgRGFzaGJvYXJkIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybSA9IG5ldyBUZXN0U3RhcnRGb3JtKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZXN0LXN0YXJ0LWZvcm0nKSk7XG4gICAgICAgIHRoaXMucmVjZW50VGVzdExpc3QgPSBuZXcgUmVjZW50VGVzdExpc3QoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRlc3QtbGlzdCcpKTtcbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkuY3JlYXRlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5odHRwLWF1dGhlbnRpY2F0aW9uLXRlc3Qtb3B0aW9uJykpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMgPSBDb29raWVPcHRpb25zRmFjdG9yeS5jcmVhdGUoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmNvb2tpZXMtdGVzdC1vcHRpb24nKSk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJlY2VudC1hY3Rpdml0eS1jb250YWluZXInKS5jbGFzc0xpc3QucmVtb3ZlKCdoaWRkZW4nKTtcblxuICAgICAgICB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlcih0aGlzLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrLXR5cGUubm90LWF2YWlsYWJsZScpKTtcbiAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmluaXQoKTtcbiAgICAgICAgdGhpcy5yZWNlbnRUZXN0TGlzdC5pbml0KCk7XG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucy5pbml0KCk7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9ucy5pbml0KCk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmRpc2FibGUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmVuYWJsZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9ucy5nZXRNb2RhbE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnRlc3RTdGFydEZvcm0uZGlzYWJsZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9ucy5nZXRNb2RhbENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnRlc3RTdGFydEZvcm0uZW5hYmxlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gRGFzaGJvYXJkO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvZGFzaGJvYXJkLmpzIiwibGV0IE1vZGFsID0gcmVxdWlyZSgnLi4vdGVzdC1oaXN0b3J5L21vZGFsJyk7XG5sZXQgU3VnZ2VzdGlvbnMgPSByZXF1aXJlKCcuLi90ZXN0LWhpc3Rvcnkvc3VnZ2VzdGlvbnMnKTtcbmxldCBMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24nKTtcblxubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoZG9jdW1lbnQpIHtcbiAgICBjb25zdCBtb2RhbElkID0gJ2ZpbHRlci1vcHRpb25zLW1vZGFsJztcbiAgICBjb25zdCBmaWx0ZXJPcHRpb25zU2VsZWN0b3IgPSAnLmFjdGlvbi1iYWRnZS1maWx0ZXItb3B0aW9ucyc7XG4gICAgY29uc3QgbW9kYWxFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobW9kYWxJZCk7XG4gICAgbGV0IGZpbHRlck9wdGlvbnNFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihmaWx0ZXJPcHRpb25zU2VsZWN0b3IpO1xuXG4gICAgbGV0IG1vZGFsID0gbmV3IE1vZGFsKG1vZGFsRWxlbWVudCk7XG4gICAgbGV0IHN1Z2dlc3Rpb25zID0gbmV3IFN1Z2dlc3Rpb25zKGRvY3VtZW50LCBtb2RhbEVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvdXJjZS11cmwnKSk7XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqL1xuICAgIGxldCBzdWdnZXN0aW9uc0xvYWRlZEV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgbW9kYWwuc2V0U3VnZ2VzdGlvbnMoZXZlbnQuZGV0YWlsKTtcbiAgICAgICAgZmlsdGVyT3B0aW9uc0VsZW1lbnQuY2xhc3NMaXN0LmFkZCgndmlzaWJsZScpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqL1xuICAgIGxldCBmaWx0ZXJDaGFuZ2VkRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICBsZXQgZmlsdGVyID0gZXZlbnQuZGV0YWlsO1xuICAgICAgICBsZXQgc2VhcmNoID0gKGZpbHRlciA9PT0gJycpID8gJycgOiAnP2ZpbHRlcj0nICsgZmlsdGVyO1xuICAgICAgICBsZXQgY3VycmVudFNlYXJjaCA9IHdpbmRvdy5sb2NhdGlvbi5zZWFyY2g7XG5cbiAgICAgICAgaWYgKGN1cnJlbnRTZWFyY2ggPT09ICcnICYmIGZpbHRlciA9PT0gJycpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChzZWFyY2ggIT09IGN1cnJlbnRTZWFyY2gpIHtcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5zZWFyY2ggPSBzZWFyY2g7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihzdWdnZXN0aW9ucy5sb2FkZWRFdmVudE5hbWUsIHN1Z2dlc3Rpb25zTG9hZGVkRXZlbnRMaXN0ZW5lcik7XG4gICAgbW9kYWxFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIobW9kYWwuZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSwgZmlsdGVyQ2hhbmdlZEV2ZW50TGlzdGVuZXIpO1xuXG4gICAgc3VnZ2VzdGlvbnMucmV0cmlldmUoKTtcblxuICAgIGxldCBsaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IExpc3RlZFRlc3RDb2xsZWN0aW9uLmNyZWF0ZUZyb21Ob2RlTGlzdChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubGlzdGVkLXRlc3QnKSk7XG4gICAgbGlzdGVkVGVzdENvbGxlY3Rpb24uZm9yRWFjaCgobGlzdGVkVGVzdCkgPT4ge1xuICAgICAgICBsaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgIH0pO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtaGlzdG9yeS5qcyIsImxldCBTdW1tYXJ5ID0gcmVxdWlyZSgnLi4vdGVzdC1wcm9ncmVzcy9zdW1tYXJ5Jyk7XG5sZXQgVGFza0xpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdCcpO1xubGV0IFRhc2tMaXN0UGFnaW5hdGlvbiA9IHJlcXVpcmUoJy4uL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LXBhZ2luYXRvcicpO1xubGV0IFRlc3RBbGVydENvbnRhaW5lciA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXInKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgVGVzdFByb2dyZXNzIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSAxMDA7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zdW1tYXJ5ID0gbmV3IFN1bW1hcnkoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXN1bW1hcnknKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRlc3QtcHJvZ3Jlc3MtdGFza3MnKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdCA9IG5ldyBUYXNrTGlzdCh0aGlzLnRhc2tMaXN0RWxlbWVudCwgdGhpcy5wYWdlTGVuZ3RoKTtcbiAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lciA9IG5ldyBUZXN0QWxlcnRDb250YWluZXIoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmFsZXJ0LWNvbnRhaW5lcicpKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24gPSBudWxsO1xuICAgICAgICB0aGlzLnRhc2tMaXN0SXNJbml0aWFsaXplZCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnN1bW1hcnlEYXRhID0gbnVsbDtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5LmluaXQoKTtcbiAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lci5pbml0KCk7XG4gICAgICAgIHRoaXMuX2FkZEV2ZW50TGlzdGVuZXJzKCk7XG5cbiAgICAgICAgdGhpcy5fcmVmcmVzaFN1bW1hcnkoKTtcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5LmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihTdW1tYXJ5LmdldFJlbmRlckFtbWVuZG1lbnRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lci5yZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbigpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3QuZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUoKSwgdGhpcy5fdGFza0xpc3RJbml0aWFsaXplZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIF9hZGRQYWdpbmF0aW9uRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFBhZ2VFdmVudE5hbWUoKSwgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gZXZlbnQuZGV0YWlsO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0UHJldmlvdXNQYWdlRXZlbnROYW1lKCksIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IE1hdGgubWF4KHRoaXMudGFza0xpc3QuY3VycmVudFBhZ2VJbmRleCAtIDEsIDApO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0TmV4dFBhZ2VFdmVudE5hbWUoKSwgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gTWF0aC5taW4odGhpcy50YXNrTGlzdC5jdXJyZW50UGFnZUluZGV4ICsgMSwgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24ucGFnZUNvdW50IC0gMSk7XG5cbiAgICAgICAgICAgIHRoaXMudGFza0xpc3Quc2V0Q3VycmVudFBhZ2VJbmRleChwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uc2VsZWN0UGFnZShwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdC5yZW5kZXIocGFnZUluZGV4KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC5kZXRhaWwucmVxdWVzdElkID09PSAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnVwZGF0ZScpIHtcbiAgICAgICAgICAgIGlmIChldmVudC5kZXRhaWwucmVxdWVzdC5yZXNwb25zZVVSTC5pbmRleE9mKHdpbmRvdy5sb2NhdGlvbi50b1N0cmluZygpKSAhPT0gMCkge1xuICAgICAgICAgICAgICAgIHRoaXMuc3VtbWFyeS5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudCgxMDApO1xuICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gZXZlbnQuZGV0YWlsLnJlcXVlc3QucmVzcG9uc2VVUkw7XG5cbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuc3VtbWFyeURhdGEgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG5cbiAgICAgICAgICAgIGxldCBzdGF0ZSA9IHRoaXMuc3VtbWFyeURhdGEudGVzdC5zdGF0ZTtcbiAgICAgICAgICAgIGxldCB0YXNrQ291bnQgPSB0aGlzLnN1bW1hcnlEYXRhLnJlbW90ZV90ZXN0LnRhc2tfY291bnQ7XG4gICAgICAgICAgICBsZXQgaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyA9IFsncXVldWVkJywgJ2luLXByb2dyZXNzJ10uaW5kZXhPZihzdGF0ZSkgIT09IC0xO1xuXG4gICAgICAgICAgICB0aGlzLl9zZXRCb2R5Sm9iQ2xhc3ModGhpcy5kb2N1bWVudC5ib2R5LmNsYXNzTGlzdCwgc3RhdGUpO1xuICAgICAgICAgICAgdGhpcy5zdW1tYXJ5LnJlbmRlcih0aGlzLnN1bW1hcnlEYXRhKTtcblxuICAgICAgICAgICAgaWYgKHRhc2tDb3VudCA+IDAgJiYgaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyAmJiAhdGhpcy50YXNrTGlzdElzSW5pdGlhbGl6ZWQgJiYgIXRoaXMudGFza0xpc3QuaXNJbml0aWFsaXppbmcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnRhc2tMaXN0LmluaXQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIHdpbmRvdy5zZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuX3JlZnJlc2hTdW1tYXJ5KCk7XG4gICAgICAgIH0sIDMwMDApO1xuICAgIH07XG5cbiAgICBfdGFza0xpc3RJbml0aWFsaXplZEV2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICB0aGlzLnRhc2tMaXN0SXNJbml0aWFsaXplZCA9IHRydWU7XG4gICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKDApO1xuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbiA9IG5ldyBUYXNrTGlzdFBhZ2luYXRpb24odGhpcy5wYWdlTGVuZ3RoLCB0aGlzLnN1bW1hcnlEYXRhLnJlbW90ZV90ZXN0LnRhc2tfY291bnQpO1xuXG4gICAgICAgIGlmICh0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5pc1JlcXVpcmVkKCkgJiYgIXRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmlzUmVuZGVyZWQoKSkge1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uaW5pdCh0aGlzLl9jcmVhdGVQYWdpbmF0aW9uRWxlbWVudCgpKTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3Quc2V0UGFnaW5hdGlvbkVsZW1lbnQodGhpcy50YXNrTGlzdFBhZ2luYXRpb24uZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLl9hZGRQYWdpbmF0aW9uRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7RWxlbWVudH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVQYWdpbmF0aW9uRWxlbWVudCAoKSB7XG4gICAgICAgIGxldCBjb250YWluZXIgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uY3JlYXRlTWFya3VwKCk7XG5cbiAgICAgICAgcmV0dXJuIGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKFRhc2tMaXN0UGFnaW5hdGlvbi5nZXRTZWxlY3RvcigpKTtcbiAgICB9XG5cbiAgICBfcmVmcmVzaFN1bW1hcnkgKCkge1xuICAgICAgICBsZXQgc3VtbWFyeVVybCA9IHRoaXMuZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3VtbWFyeS11cmwnKTtcbiAgICAgICAgbGV0IG5vdyA9IG5ldyBEYXRlKCk7XG5cbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHN1bW1hcnlVcmwgKyAnP3RpbWVzdGFtcD0nICsgbm93LmdldFRpbWUoKSwgdGhpcy5kb2N1bWVudC5ib2R5LCAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnVwZGF0ZScpO1xuICAgIH07XG4gICAgLyoqXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0RPTVRva2VuTGlzdH0gYm9keUNsYXNzTGlzdFxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSB0ZXN0U3RhdGVcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9zZXRCb2R5Sm9iQ2xhc3MgKGJvZHlDbGFzc0xpc3QsIHRlc3RTdGF0ZSkge1xuICAgICAgICBsZXQgam9iQ2xhc3NQcmVmaXggPSAnam9iLSc7XG4gICAgICAgIGJvZHlDbGFzc0xpc3QuZm9yRWFjaCgoY2xhc3NOYW1lLCBpbmRleCwgY2xhc3NMaXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoY2xhc3NOYW1lLmluZGV4T2Yoam9iQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBib2R5Q2xhc3NMaXN0LmFkZCgnam9iLScgKyB0ZXN0U3RhdGUpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFByb2dyZXNzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1wcm9ncmVzcy5qcyIsImxldCBCeVBhZ2VMaXN0ID0gcmVxdWlyZSgnLi4vdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZS9ieS1wYWdlLWxpc3QnKTtcblxuY2xhc3MgVGVzdFJlc3VsdHNCeVRhc2tUeXBlIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuYnlQYWdlTGlzdCA9IG5ldyBCeVBhZ2VMaXN0KGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5ieS1wYWdlLWNvbnRhaW5lcicpKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5ieVBhZ2VMaXN0LmluaXQoKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RSZXN1bHRzQnlUYXNrVHlwZTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUuanMiLCJsZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhcicpO1xubGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9odHRwLWNsaWVudCcpO1xuXG5jbGFzcyBUZXN0UmVzdWx0c1ByZXBhcmluZyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLnVucmV0cmlldmVkUmVtb3RlVGFza0lkc1VybCA9IGRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXVucmV0cmlldmVkLXJlbW90ZS10YXNrLWlkcy11cmwnKTtcbiAgICAgICAgdGhpcy5yZXN1bHRzUmV0cmlldmVVcmwgPSBkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS1yZXN1bHRzLXJldHJpZXZlLXVybCcpO1xuICAgICAgICB0aGlzLnJldHJpZXZhbFN0YXRzVXJsID0gZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcHJlcGFyaW5nLXN0YXRzLXVybCcpO1xuICAgICAgICB0aGlzLnJlc3VsdHNVcmwgPSBkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS1yZXN1bHRzLXVybCcpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyID0gbmV3IFByb2dyZXNzQmFyKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9ncmVzcy1iYXInKSk7XG4gICAgICAgIHRoaXMuY29tcGxldGlvblBlcmNlbnRWYWx1ZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jb21wbGV0aW9uLXBlcmNlbnQtdmFsdWUnKTtcbiAgICAgICAgdGhpcy5sb2NhbFRhc2tDb3VudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5sb2NhbC10YXNrLWNvdW50Jyk7XG4gICAgICAgIHRoaXMucmVtb3RlVGFza0NvdW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJlbW90ZS10YXNrLWNvdW50Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYm9keS5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5fY2hlY2tDb21wbGV0aW9uU3RhdHVzKCk7XG4gICAgfTtcblxuICAgIF9jaGVja0NvbXBsZXRpb25TdGF0dXMgKCkge1xuICAgICAgICBpZiAocGFyc2VJbnQoZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmVtYWluaW5nLXRhc2tzLXRvLXJldHJpZXZlLWNvdW50JyksIDEwKSA9PT0gMCkge1xuICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSB0aGlzLnJlc3VsdHNVcmw7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgcmVxdWVzdElkID0gZXZlbnQuZGV0YWlsLnJlcXVlc3RJZDtcbiAgICAgICAgbGV0IHJlc3BvbnNlID0gZXZlbnQuZGV0YWlsLnJlc3BvbnNlO1xuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uJykge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbihyZXNwb25zZSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbicpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlUmV0cmlldmFsU3RhdHMoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVJldHJpZXZhbFN0YXRzJykge1xuICAgICAgICAgICAgbGV0IGNvbXBsZXRpb25QZXJjZW50ID0gcmVzcG9uc2UuY29tcGxldGlvbl9wZXJjZW50O1xuXG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuc2V0QXR0cmlidXRlKCdkYXRhLXJlbWFpbmluZy10YXNrcy10by1yZXRyaWV2ZS1jb3VudCcsIHJlc3BvbnNlLnJlbWFpbmluZ190YXNrc190b19yZXRyaWV2ZV9jb3VudCk7XG4gICAgICAgICAgICB0aGlzLmNvbXBsZXRpb25QZXJjZW50VmFsdWUuaW5uZXJUZXh0ID0gY29tcGxldGlvblBlcmNlbnQ7XG4gICAgICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldENvbXBsZXRpb25QZXJjZW50KGNvbXBsZXRpb25QZXJjZW50KTtcbiAgICAgICAgICAgIHRoaXMubG9jYWxUYXNrQ291bnQuaW5uZXJUZXh0ID0gcmVzcG9uc2UubG9jYWxfdGFza19jb3VudDtcbiAgICAgICAgICAgIHRoaXMucmVtb3RlVGFza0NvdW50LmlubmVyVGV4dCA9IHJlc3BvbnNlLnJlbW90ZV90YXNrX2NvdW50O1xuXG4gICAgICAgICAgICB0aGlzLl9jaGVja0NvbXBsZXRpb25TdGF0dXMoKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfcmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbiAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnVucmV0cmlldmVkUmVtb3RlVGFza0lkc1VybCwgdGhpcy5kb2N1bWVudC5ib2R5LCAncmV0cmlldmVOZXh0UmVtb3RlVGFza0lkQ29sbGVjdGlvbicpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbiAocmVtb3RlVGFza0lkcykge1xuICAgICAgICBIdHRwQ2xpZW50LnBvc3QodGhpcy5yZXN1bHRzUmV0cmlldmVVcmwsIHRoaXMuZG9jdW1lbnQuYm9keSwgJ3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24nLCAncmVtb3RlVGFza0lkcz0nICsgcmVtb3RlVGFza0lkcy5qb2luKCcsJykpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVSZXRyaWV2YWxTdGF0cyAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnJldHJpZXZhbFN0YXRzVXJsLCB0aGlzLmRvY3VtZW50LmJvZHksICdyZXRyaWV2ZVJldHJpZXZhbFN0YXRzJyk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRlc3RSZXN1bHRzUHJlcGFyaW5nO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLXByZXBhcmluZy5qcyIsImxldCB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlciA9IHJlcXVpcmUoJy4uL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlcicpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBDb29raWVPcHRpb25zRmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBUZXN0TG9ja1VubG9jayA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGVzdC1sb2NrLXVubG9jaycpO1xubGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5sZXQgQmFkZ2VDb2xsZWN0aW9uID0gcmVxdWlyZSgnLi4vbW9kZWwvYmFkZ2UtY29sbGVjdGlvbicpO1xuXG5jbGFzcyBUZXN0UmVzdWx0cyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeS5jcmVhdGUoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmh0dHAtYXV0aGVudGljYXRpb24tdGVzdC1vcHRpb24nKSk7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9ucyA9IENvb2tpZU9wdGlvbnNGYWN0b3J5LmNyZWF0ZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuY29va2llcy10ZXN0LW9wdGlvbicpKTtcbiAgICAgICAgdGhpcy50ZXN0TG9ja1VubG9jayA9IG5ldyBUZXN0TG9ja1VubG9jayhkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuYnRuLWxvY2stdW5sb2NrJykpO1xuICAgICAgICB0aGlzLnJldGVzdEZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcucmV0ZXN0LWZvcm0nKTtcbiAgICAgICAgdGhpcy5yZXRlc3RCdXR0b24gPSBuZXcgRm9ybUJ1dHRvbih0aGlzLnJldGVzdEZvcm0ucXVlcnlTZWxlY3RvcignYnV0dG9uW3R5cGU9c3VibWl0XScpKTtcbiAgICAgICAgdGhpcy50YXNrVHlwZVN1bW1hcnlCYWRnZUNvbGxlY3Rpb24gPSBuZXcgQmFkZ2VDb2xsZWN0aW9uKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrLXR5cGUtc3VtbWFyeSAuYmFkZ2UnKSk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHVuYXZhaWxhYmxlVGFza1R5cGVNb2RhbExhdW5jaGVyKHRoaXMuZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnRhc2stdHlwZS1vcHRpb24ubm90LWF2YWlsYWJsZScpKTtcbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zLmluaXQoKTtcbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zLmluaXQoKTtcbiAgICAgICAgdGhpcy50ZXN0TG9ja1VubG9jay5pbml0KCk7XG4gICAgICAgIHRoaXMudGFza1R5cGVTdW1tYXJ5QmFkZ2VDb2xsZWN0aW9uLmFwcGx5VW5pZm9ybVdpZHRoKCk7XG5cbiAgICAgICAgdGhpcy5yZXRlc3RGb3JtLmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMucmV0ZXN0QnV0dG9uLmRlRW1waGFzaXplKCk7XG4gICAgICAgICAgICB0aGlzLnJldGVzdEJ1dHRvbi5tYXJrQXNCdXN5KCk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFJlc3VsdHM7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMuanMiLCJsZXQgRm9ybSA9IHJlcXVpcmUoJy4uL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0nKTtcbmxldCBGb3JtVmFsaWRhdG9yID0gcmVxdWlyZSgnLi4vdXNlci1hY2NvdW50LWNhcmQvZm9ybS12YWxpZGF0b3InKTtcbmxldCBTdHJpcGVIYW5kbGVyID0gcmVxdWlyZSgnLi4vc2VydmljZXMvc3RyaXBlLWhhbmRsZXInKTtcblxuY2xhc3MgVXNlckFjY291bnRDYXJkIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICAvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgbm8tdW5kZWZcbiAgICAgICAgbGV0IHN0cmlwZUpzID0gU3RyaXBlO1xuICAgICAgICBsZXQgZm9ybVZhbGlkYXRvciA9IG5ldyBGb3JtVmFsaWRhdG9yKHN0cmlwZUpzKTtcbiAgICAgICAgdGhpcy5zdHJpcGVIYW5kbGVyID0gbmV3IFN0cmlwZUhhbmRsZXIoc3RyaXBlSnMpO1xuXG4gICAgICAgIHRoaXMuZm9ybSA9IG5ldyBGb3JtKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwYXltZW50LWZvcm0nKSwgZm9ybVZhbGlkYXRvcik7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmZvcm0uaW5pdCgpO1xuICAgICAgICB0aGlzLnN0cmlwZUhhbmRsZXIuc2V0U3RyaXBlUHVibGlzaGFibGVLZXkodGhpcy5mb3JtLmdldFN0cmlwZVB1Ymxpc2hhYmxlS2V5KCkpO1xuXG4gICAgICAgIGxldCB1cGRhdGVDYXJkID0gdGhpcy5mb3JtLmdldFVwZGF0ZUNhcmRFdmVudE5hbWUoKTtcbiAgICAgICAgbGV0IGNyZWF0ZUNhcmRUb2tlblN1Y2Nlc3MgPSB0aGlzLnN0cmlwZUhhbmRsZXIuZ2V0Q3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TmFtZSgpO1xuICAgICAgICBsZXQgY3JlYXRlQ2FyZFRva2VuRmFpbHVyZSA9IHRoaXMuc3RyaXBlSGFuZGxlci5nZXRDcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnROYW1lKCk7XG5cbiAgICAgICAgdGhpcy5mb3JtLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih1cGRhdGVDYXJkLCB0aGlzLl91cGRhdGVDYXJkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5mb3JtLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihjcmVhdGVDYXJkVG9rZW5TdWNjZXNzLCB0aGlzLl9jcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5mb3JtLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihjcmVhdGVDYXJkVG9rZW5GYWlsdXJlLCB0aGlzLl9jcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgX3VwZGF0ZUNhcmRFdmVudExpc3RlbmVyICh1cGRhdGVDYXJkRXZlbnQpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVIYW5kbGVyLmNyZWF0ZUNhcmRUb2tlbih1cGRhdGVDYXJkRXZlbnQuZGV0YWlsLCB0aGlzLmZvcm0uZWxlbWVudCk7XG4gICAgfTtcblxuICAgIF9jcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnRMaXN0ZW5lciAoc3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQpIHtcbiAgICAgICAgbGV0IHJlcXVlc3RVcmwgPSB3aW5kb3cubG9jYXRpb24ucGF0aG5hbWUgKyBzdHJpcGVDcmVhdGVDYXJkVG9rZW5FdmVudC5kZXRhaWwuaWQgKyAnL2Fzc29jaWF0ZS8nO1xuICAgICAgICBsZXQgcmVxdWVzdCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuXG4gICAgICAgIHJlcXVlc3Qub3BlbignUE9TVCcsIHJlcXVlc3RVcmwpO1xuICAgICAgICByZXF1ZXN0LnJlc3BvbnNlVHlwZSA9ICdqc29uJztcbiAgICAgICAgcmVxdWVzdC5zZXRSZXF1ZXN0SGVhZGVyKCdBY2NlcHQnLCAnYXBwbGljYXRpb24vanNvbicpO1xuXG4gICAgICAgIHJlcXVlc3QuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IGRhdGEgPSByZXF1ZXN0LnJlc3BvbnNlO1xuXG4gICAgICAgICAgICBpZiAoZGF0YS5oYXNPd25Qcm9wZXJ0eSgndGhpc191cmwnKSkge1xuICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5zdWJtaXRCdXR0b24ubWFya0FzQXZhaWxhYmxlKCk7XG4gICAgICAgICAgICAgICAgdGhpcy5mb3JtLnN1Ym1pdEJ1dHRvbi5tYXJrU3VjY2VlZGVkKCk7XG5cbiAgICAgICAgICAgICAgICB3aW5kb3cuc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbiA9IGRhdGEudGhpc191cmw7XG4gICAgICAgICAgICAgICAgfSwgNTAwKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mb3JtLmVuYWJsZSgpO1xuXG4gICAgICAgICAgICAgICAgaWYgKGRhdGEuaGFzT3duUHJvcGVydHkoJ3VzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9wYXJhbScpICYmIGRhdGFbJ3VzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9wYXJhbSddICE9PSAnJykge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZvcm0uaGFuZGxlUmVzcG9uc2VFcnJvcih7XG4gICAgICAgICAgICAgICAgICAgICAgICAncGFyYW0nOiBkYXRhLnVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9wYXJhbSxcbiAgICAgICAgICAgICAgICAgICAgICAgICdtZXNzYWdlJzogZGF0YS51c2VyX2FjY291bnRfY2FyZF9leGNlcHRpb25fbWVzc2FnZVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZvcm0uaGFuZGxlUmVzcG9uc2VFcnJvcih7XG4gICAgICAgICAgICAgICAgICAgICAgICAncGFyYW0nOiAnbnVtYmVyJyxcbiAgICAgICAgICAgICAgICAgICAgICAgICdtZXNzYWdlJzogZGF0YS51c2VyX2FjY291bnRfY2FyZF9leGNlcHRpb25fbWVzc2FnZVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJlcXVlc3Quc2VuZCgpO1xuICAgIH07XG5cbiAgICBfY3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TGlzdGVuZXIgKHN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50KSB7XG4gICAgICAgIGxldCByZXNwb25zZUVycm9yID0gdGhpcy5mb3JtLmNyZWF0ZVJlc3BvbnNlRXJyb3Ioc3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQuZGV0YWlsLmVycm9yLnBhcmFtLCAnaW52YWxpZCcpO1xuXG4gICAgICAgIHRoaXMuZm9ybS5lbmFibGUoKTtcbiAgICAgICAgdGhpcy5mb3JtLmhhbmRsZVJlc3BvbnNlRXJyb3IocmVzcG9uc2VFcnJvcik7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBVc2VyQWNjb3VudENhcmQ7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFnZS91c2VyLWFjY291bnQtY2FyZC5qcyIsImxldCBTY3JvbGxTcHkgPSByZXF1aXJlKCcuLi91c2VyLWFjY291bnQvc2Nyb2xsc3B5Jyk7XG5sZXQgTmF2QmFyTGlzdCA9IHJlcXVpcmUoJy4uL3VzZXItYWNjb3VudC9uYXZiYXItbGlzdCcpO1xuY29uc3QgU2Nyb2xsVG8gPSByZXF1aXJlKCcuLi9zY3JvbGwtdG8nKTtcbmNvbnN0IFN0aWNreUZpbGwgPSByZXF1aXJlKCdzdGlja3lmaWxsanMnKTtcblxuY2xhc3MgVXNlckFjY291bnQge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7V2luZG93fSB3aW5kb3dcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yICh3aW5kb3csIGRvY3VtZW50KSB7XG4gICAgICAgIHRoaXMud2luZG93ID0gd2luZG93O1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuc2Nyb2xsT2Zmc2V0ID0gNjA7XG4gICAgICAgIGNvbnN0IHNjcm9sbFNweU9mZnNldCA9IDEwMDtcbiAgICAgICAgdGhpcy5zaWRlTmF2RWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdzaWRlbmF2Jyk7XG5cbiAgICAgICAgdGhpcy5uYXZCYXJMaXN0ID0gbmV3IE5hdkJhckxpc3QodGhpcy5zaWRlTmF2RWxlbWVudCwgdGhpcy5zY3JvbGxPZmZzZXQpO1xuICAgICAgICB0aGlzLnNjcm9sbHNweSA9IG5ldyBTY3JvbGxTcHkodGhpcy5uYXZCYXJMaXN0LCBzY3JvbGxTcHlPZmZzZXQpO1xuICAgIH07XG5cbiAgICBfYXBwbHlJbml0aWFsU2Nyb2xsICgpIHtcbiAgICAgICAgbGV0IHRhcmdldElkID0gdGhpcy53aW5kb3cubG9jYXRpb24uaGFzaC50cmltKCkucmVwbGFjZSgnIycsICcnKTtcblxuICAgICAgICBpZiAodGFyZ2V0SWQpIHtcbiAgICAgICAgICAgIGxldCB0YXJnZXQgPSB0aGlzLmRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRhcmdldElkKTtcbiAgICAgICAgICAgIGxldCByZWxhdGVkQW5jaG9yID0gdGhpcy5zaWRlTmF2RWxlbWVudC5xdWVyeVNlbGVjdG9yKCdhW2hyZWY9XFxcXCMnICsgdGFyZ2V0SWQgKyAnXScpO1xuXG4gICAgICAgICAgICBpZiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgaWYgKHJlbGF0ZWRBbmNob3IuY2xhc3NMaXN0LmNvbnRhaW5zKCdqcy1maXJzdCcpKSB7XG4gICAgICAgICAgICAgICAgICAgIFNjcm9sbFRvLmdvVG8odGFyZ2V0LCAwKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBTY3JvbGxUby5zY3JvbGxUbyh0YXJnZXQsIHRoaXMuc2Nyb2xsT2Zmc2V0KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX2FwcGx5UG9zaXRpb25TdGlja3lQb2x5ZmlsbCAoKSB7XG4gICAgICAgIGNvbnN0IHNlbGVjdG9yID0gJy5jc3Nwb3NpdGlvbnN0aWNreSc7XG4gICAgICAgIGNvbnN0IHN0aWNreU5hdkpzQ2xhc3MgPSAnanMtc3RpY2t5LW5hdic7XG4gICAgICAgIGNvbnN0IHN0aWNreU5hdkpzU2VsZWN0b3IgPSAnLicgKyBzdGlja3lOYXZKc0NsYXNzO1xuXG4gICAgICAgIGxldCBzdGlja3lOYXYgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHN0aWNreU5hdkpzU2VsZWN0b3IpO1xuXG4gICAgICAgIGlmIChkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKSkge1xuICAgICAgICAgICAgc3RpY2t5TmF2LmNsYXNzTGlzdC5yZW1vdmUoc3RpY2t5TmF2SnNDbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBTdGlja3lGaWxsLmFkZE9uZShzdGlja3lOYXYpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLnNpZGVOYXZFbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2EnKS5jbGFzc0xpc3QuYWRkKCdqcy1maXJzdCcpO1xuICAgICAgICB0aGlzLnNjcm9sbHNweS5zcHkoKTtcbiAgICAgICAgdGhpcy5fYXBwbHlQb3NpdGlvblN0aWNreVBvbHlmaWxsKCk7XG4gICAgICAgIHRoaXMuX2FwcGx5SW5pdGlhbFNjcm9sbCgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVXNlckFjY291bnQ7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFnZS91c2VyLWFjY291bnQuanMiLCIvLyBQb2x5ZmlsbCBmb3IgYnJvd3NlcnMgbm90IHN1cHBvcnRpbmcgbmV3IEN1c3RvbUV2ZW50KClcbi8vIExpZ2h0bHkgbW9kaWZpZWQgZnJvbSBwb2x5ZmlsbCBwcm92aWRlZCBhdCBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvQ3VzdG9tRXZlbnQvQ3VzdG9tRXZlbnQjUG9seWZpbGxcbihmdW5jdGlvbiAoKSB7XG4gICAgaWYgKHR5cGVvZiB3aW5kb3cuQ3VzdG9tRXZlbnQgPT09ICdmdW5jdGlvbicpIHJldHVybiBmYWxzZTtcblxuICAgIGZ1bmN0aW9uIEN1c3RvbUV2ZW50IChldmVudCwgcGFyYW1zKSB7XG4gICAgICAgIHBhcmFtcyA9IHBhcmFtcyB8fCB7IGJ1YmJsZXM6IGZhbHNlLCBjYW5jZWxhYmxlOiBmYWxzZSwgZGV0YWlsOiB1bmRlZmluZWQgfTtcbiAgICAgICAgbGV0IGN1c3RvbUV2ZW50ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoJ0N1c3RvbUV2ZW50Jyk7XG4gICAgICAgIGN1c3RvbUV2ZW50LmluaXRDdXN0b21FdmVudChldmVudCwgcGFyYW1zLmJ1YmJsZXMsIHBhcmFtcy5jYW5jZWxhYmxlLCBwYXJhbXMuZGV0YWlsKTtcblxuICAgICAgICByZXR1cm4gY3VzdG9tRXZlbnQ7XG4gICAgfVxuXG4gICAgQ3VzdG9tRXZlbnQucHJvdG90eXBlID0gd2luZG93LkV2ZW50LnByb3RvdHlwZTtcblxuICAgIHdpbmRvdy5DdXN0b21FdmVudCA9IEN1c3RvbUV2ZW50O1xufSkoKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wb2x5ZmlsbC9jdXN0b20tZXZlbnQuanMiLCIvLyBQb2x5ZmlsbCBmb3IgYnJvd3NlcnMgbm90IHN1cHBvcnRpbmcgT2JqZWN0LmVudHJpZXMoKVxuLy8gTGlnaHRseSBtb2RpZmllZCBmcm9tIHBvbHlmaWxsIHByb3ZpZGVkIGF0IGh0dHBzOi8vZGV2ZWxvcGVyLm1vemlsbGEub3JnL2VuLVVTL2RvY3MvV2ViL0phdmFTY3JpcHQvUmVmZXJlbmNlL0dsb2JhbF9PYmplY3RzL09iamVjdC9lbnRyaWVzI1BvbHlmaWxsXG5pZiAoIU9iamVjdC5lbnRyaWVzKSB7XG4gICAgT2JqZWN0LmVudHJpZXMgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgICAgIGxldCBvd25Qcm9wcyA9IE9iamVjdC5rZXlzKG9iaik7XG4gICAgICAgIGxldCBpID0gb3duUHJvcHMubGVuZ3RoO1xuICAgICAgICBsZXQgcmVzQXJyYXkgPSBuZXcgQXJyYXkoaSk7XG5cbiAgICAgICAgd2hpbGUgKGktLSkge1xuICAgICAgICAgICAgcmVzQXJyYXlbaV0gPSBbb3duUHJvcHNbaV0sIG9ialtvd25Qcm9wc1tpXV1dO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHJlc0FycmF5O1xuICAgIH07XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcG9seWZpbGwvb2JqZWN0LWVudHJpZXMuanMiLCJjb25zdCBTbW9vdGhTY3JvbGwgPSByZXF1aXJlKCdzbW9vdGgtc2Nyb2xsJyk7XG5cbmNsYXNzIFNjcm9sbFRvIHtcbiAgICBzdGF0aWMgc2Nyb2xsVG8gKHRhcmdldCwgb2Zmc2V0KSB7XG4gICAgICAgIGNvbnN0IHNjcm9sbCA9IG5ldyBTbW9vdGhTY3JvbGwoKTtcblxuICAgICAgICBzY3JvbGwuYW5pbWF0ZVNjcm9sbCh0YXJnZXQub2Zmc2V0VG9wICsgb2Zmc2V0KTtcbiAgICAgICAgU2Nyb2xsVG8uX3VwZGF0ZUhpc3RvcnkodGFyZ2V0KTtcbiAgICB9XG5cbiAgICBzdGF0aWMgZ29UbyAodGFyZ2V0LCBvZmZzZXQpIHtcbiAgICAgICAgY29uc3Qgc2Nyb2xsID0gbmV3IFNtb290aFNjcm9sbCgpO1xuXG4gICAgICAgIHNjcm9sbC5hbmltYXRlU2Nyb2xsKG9mZnNldCk7XG4gICAgICAgIFNjcm9sbFRvLl91cGRhdGVIaXN0b3J5KHRhcmdldCk7XG4gICAgfVxuXG4gICAgc3RhdGljIF91cGRhdGVIaXN0b3J5ICh0YXJnZXQpIHtcbiAgICAgICAgaWYgKHdpbmRvdy5oaXN0b3J5LnB1c2hTdGF0ZSkge1xuICAgICAgICAgICAgd2luZG93Lmhpc3RvcnkucHVzaFN0YXRlKG51bGwsIG51bGwsICcjJyArIHRhcmdldC5nZXRBdHRyaWJ1dGUoJ2lkJykpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTY3JvbGxUbztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zY3JvbGwtdG8uanMiLCJsZXQgQWxlcnQgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2FsZXJ0Jyk7XG5cbmNsYXNzIEFsZXJ0RmFjdG9yeSB7XG4gICAgc3RhdGljIGNyZWF0ZUZyb21Db250ZW50IChkb2N1bWVudCwgZXJyb3JDb250ZW50LCByZWxhdGVkRmllbGRJZCkge1xuICAgICAgICBsZXQgZWxlbWVudCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBlbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FsZXJ0JywgJ2FsZXJ0LWRhbmdlcicsICdmYWRlJywgJ2luJyk7XG4gICAgICAgIGVsZW1lbnQuc2V0QXR0cmlidXRlKCdyb2xlJywgJ2FsZXJ0Jyk7XG5cbiAgICAgICAgbGV0IGVsZW1lbnRJbm5lckhUTUwgPSAnJztcblxuICAgICAgICBpZiAocmVsYXRlZEZpZWxkSWQpIHtcbiAgICAgICAgICAgIGVsZW1lbnQuc2V0QXR0cmlidXRlKCdkYXRhLWZvcicsIHJlbGF0ZWRGaWVsZElkKTtcbiAgICAgICAgICAgIGVsZW1lbnRJbm5lckhUTUwgKz0gJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiY2xvc2VcIiBkYXRhLWRpc21pc3M9XCJhbGVydFwiIGFyaWEtbGFiZWw9XCJDbG9zZVwiPjxzcGFuIGFyaWEtaGlkZGVuPVwidHJ1ZVwiPsOXPC9zcGFuPjwvYnV0dG9uPic7XG4gICAgICAgIH1cblxuICAgICAgICBlbGVtZW50SW5uZXJIVE1MICs9IGVycm9yQ29udGVudDtcbiAgICAgICAgZWxlbWVudC5pbm5lckhUTUwgPSBlbGVtZW50SW5uZXJIVE1MO1xuXG4gICAgICAgIHJldHVybiBuZXcgQWxlcnQoZWxlbWVudCk7XG4gICAgfTtcblxuICAgIHN0YXRpYyBjcmVhdGVGcm9tRWxlbWVudCAoYWxlcnRFbGVtZW50KSB7XG4gICAgICAgIHJldHVybiBuZXcgQWxlcnQoYWxlcnRFbGVtZW50KTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQWxlcnRGYWN0b3J5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL2FsZXJ0LWZhY3RvcnkuanMiLCJsZXQgQ29va2llT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9jb29raWUtb3B0aW9ucy1tb2RhbCcpO1xubGV0IENvb2tpZU9wdGlvbnMgPSByZXF1aXJlKCcuLi9tb2RlbC9jb29raWUtb3B0aW9ucycpO1xubGV0IEFjdGlvbkJhZGdlID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9hY3Rpb24tYmFkZ2UnKTtcblxuY2xhc3MgQ29va2llT3B0aW9uc0ZhY3Rvcnkge1xuICAgIHN0YXRpYyBjcmVhdGUgKGNvbnRhaW5lcikge1xuICAgICAgICByZXR1cm4gbmV3IENvb2tpZU9wdGlvbnMoXG4gICAgICAgICAgICBjb250YWluZXIub3duZXJEb2N1bWVudCxcbiAgICAgICAgICAgIG5ldyBDb29raWVPcHRpb25zTW9kYWwoY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5tb2RhbCcpKSxcbiAgICAgICAgICAgIG5ldyBBY3Rpb25CYWRnZShjb250YWluZXIucXVlcnlTZWxlY3RvcignLm1vZGFsLWxhdW5jaGVyJykpLFxuICAgICAgICAgICAgY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5zdGF0dXMnKVxuICAgICAgICApO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQ29va2llT3B0aW9uc0ZhY3Rvcnk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvY29va2llLW9wdGlvbnMtZmFjdG9yeS5qcyIsImxldCBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbCcpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMgPSByZXF1aXJlKCcuLi9tb2RlbC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMnKTtcbmxldCBBY3Rpb25CYWRnZSA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvYWN0aW9uLWJhZGdlJyk7XG5cbmNsYXNzIEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5IHtcbiAgICBzdGF0aWMgY3JlYXRlIChjb250YWluZXIpIHtcbiAgICAgICAgcmV0dXJuIG5ldyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zKFxuICAgICAgICAgICAgY29udGFpbmVyLm93bmVyRG9jdW1lbnQsXG4gICAgICAgICAgICBuZXcgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubW9kYWwnKSksXG4gICAgICAgICAgICBuZXcgQWN0aW9uQmFkZ2UoY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5tb2RhbC1sYXVuY2hlcicpKSxcbiAgICAgICAgICAgIGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcuc3RhdHVzJylcbiAgICAgICAgKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1mYWN0b3J5LmpzIiwiY2xhc3MgSHR0cENsaWVudCB7XG4gICAgc3RhdGljIGdldFJldHJpZXZlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnaHR0cC1jbGllbnQucmV0cmlldmVkJztcbiAgICB9O1xuXG4gICAgc3RhdGljIHJlcXVlc3QgKHVybCwgbWV0aG9kLCByZXNwb25zZVR5cGUsIGVsZW1lbnQsIHJlcXVlc3RJZCwgZGF0YSA9IG51bGwsIHJlcXVlc3RIZWFkZXJzID0ge30pIHtcbiAgICAgICAgbGV0IHJlcXVlc3QgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcblxuICAgICAgICByZXF1ZXN0Lm9wZW4obWV0aG9kLCB1cmwpO1xuICAgICAgICByZXF1ZXN0LnJlc3BvbnNlVHlwZSA9IHJlc3BvbnNlVHlwZTtcblxuICAgICAgICBmb3IgKGNvbnN0IFtrZXksIHZhbHVlXSBvZiBPYmplY3QuZW50cmllcyhyZXF1ZXN0SGVhZGVycykpIHtcbiAgICAgICAgICAgIHJlcXVlc3Quc2V0UmVxdWVzdEhlYWRlcihrZXksIHZhbHVlKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJlcXVlc3QuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHJldHJpZXZlZEV2ZW50ID0gbmV3IEN1c3RvbUV2ZW50KEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHtcbiAgICAgICAgICAgICAgICAgICAgcmVzcG9uc2U6IHJlcXVlc3QucmVzcG9uc2UsXG4gICAgICAgICAgICAgICAgICAgIHJlcXVlc3RJZDogcmVxdWVzdElkLFxuICAgICAgICAgICAgICAgICAgICByZXF1ZXN0OiByZXF1ZXN0XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGVsZW1lbnQuZGlzcGF0Y2hFdmVudChyZXRyaWV2ZWRFdmVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGlmIChkYXRhID09PSBudWxsKSB7XG4gICAgICAgICAgICByZXF1ZXN0LnNlbmQoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHJlcXVlc3Quc2VuZChkYXRhKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBzdGF0aWMgZ2V0ICh1cmwsIHJlc3BvbnNlVHlwZSwgZWxlbWVudCwgcmVxdWVzdElkLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIEh0dHBDbGllbnQucmVxdWVzdCh1cmwsICdHRVQnLCByZXNwb25zZVR5cGUsIGVsZW1lbnQsIHJlcXVlc3RJZCwgbnVsbCwgcmVxdWVzdEhlYWRlcnMpO1xuICAgIH07XG5cbiAgICBzdGF0aWMgZ2V0SnNvbiAodXJsLCBlbGVtZW50LCByZXF1ZXN0SWQsIHJlcXVlc3RIZWFkZXJzID0ge30pIHtcbiAgICAgICAgbGV0IHJlYWxSZXF1ZXN0SGVhZGVycyA9IHtcbiAgICAgICAgICAgICdBY2NlcHQnOiAnYXBwbGljYXRpb24vanNvbidcbiAgICAgICAgfTtcblxuICAgICAgICBmb3IgKGNvbnN0IFtrZXksIHZhbHVlXSBvZiBPYmplY3QuZW50cmllcyhyZXF1ZXN0SGVhZGVycykpIHtcbiAgICAgICAgICAgIHJlYWxSZXF1ZXN0SGVhZGVyc1trZXldID0gdmFsdWU7XG4gICAgICAgIH1cblxuICAgICAgICBIdHRwQ2xpZW50LnJlcXVlc3QodXJsLCAnR0VUJywgJ2pzb24nLCBlbGVtZW50LCByZXF1ZXN0SWQsIG51bGwsIHJlYWxSZXF1ZXN0SGVhZGVycyk7XG4gICAgfTtcblxuICAgIHN0YXRpYyBnZXRUZXh0ICh1cmwsIGVsZW1lbnQsIHJlcXVlc3RJZCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBIdHRwQ2xpZW50LnJlcXVlc3QodXJsLCAnR0VUJywgJycsIGVsZW1lbnQsIHJlcXVlc3RJZCwgcmVxdWVzdEhlYWRlcnMpO1xuICAgIH07XG5cbiAgICBzdGF0aWMgcG9zdCAodXJsLCBlbGVtZW50LCByZXF1ZXN0SWQsIGRhdGEgPSBudWxsLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIGxldCByZWFsUmVxdWVzdEhlYWRlcnMgPSB7XG4gICAgICAgICAgICAnQ29udGVudC10eXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCdcbiAgICAgICAgfTtcblxuICAgICAgICBmb3IgKGNvbnN0IFtrZXksIHZhbHVlXSBvZiBPYmplY3QuZW50cmllcyhyZXF1ZXN0SGVhZGVycykpIHtcbiAgICAgICAgICAgIHJlYWxSZXF1ZXN0SGVhZGVyc1trZXldID0gdmFsdWU7XG4gICAgICAgIH1cblxuICAgICAgICBIdHRwQ2xpZW50LnJlcXVlc3QodXJsLCAnUE9TVCcsICcnLCBlbGVtZW50LCByZXF1ZXN0SWQsIGRhdGEsIHJlYWxSZXF1ZXN0SGVhZGVycyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQ2xpZW50O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL2h0dHAtY2xpZW50LmpzIiwibGV0IExpc3RlZFRlc3QgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2xpc3RlZC10ZXN0Jyk7XG5sZXQgUHJlcGFyaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJlcGFyaW5nLWxpc3RlZC10ZXN0Jyk7XG5sZXQgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcm9ncmVzc2luZy1saXN0ZWQtdGVzdCcpO1xubGV0IENyYXdsaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvY3Jhd2xpbmctbGlzdGVkLXRlc3QnKTtcblxuY2xhc3MgTGlzdGVkVGVzdEZhY3Rvcnkge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqXG4gICAgICogQHJldHVybnMge0xpc3RlZFRlc3R9XG4gICAgICovXG4gICAgc3RhdGljIGNyZWF0ZUZyb21FbGVtZW50IChlbGVtZW50KSB7XG4gICAgICAgIGlmIChlbGVtZW50LmNsYXNzTGlzdC5jb250YWlucygncmVxdWlyZXMtcmVzdWx0cycpKSB7XG4gICAgICAgICAgICByZXR1cm4gbmV3IFByZXBhcmluZ0xpc3RlZFRlc3QoZWxlbWVudCk7XG4gICAgICAgIH1cblxuICAgICAgICBsZXQgc3RhdGUgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdGF0ZScpO1xuXG4gICAgICAgIGlmIChzdGF0ZSA9PT0gJ2luLXByb2dyZXNzJykge1xuICAgICAgICAgICAgcmV0dXJuIG5ldyBQcm9ncmVzc2luZ0xpc3RlZFRlc3QoZWxlbWVudCk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoc3RhdGUgPT09ICdjcmF3bGluZycpIHtcbiAgICAgICAgICAgIHJldHVybiBuZXcgQ3Jhd2xpbmdMaXN0ZWRUZXN0KGVsZW1lbnQpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIG5ldyBMaXN0ZWRUZXN0KGVsZW1lbnQpO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBMaXN0ZWRUZXN0RmFjdG9yeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9saXN0ZWQtdGVzdC1mYWN0b3J5LmpzIiwiY2xhc3MgU3RyaXBlSGFuZGxlciB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtTdHJpcGV9IHN0cmlwZUpzXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKHN0cmlwZUpzKSB7XG4gICAgICAgIHRoaXMuc3RyaXBlSnMgPSBzdHJpcGVKcztcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30gc3RyaXBlUHVibGlzaGFibGVLZXlcbiAgICAgKi9cbiAgICBzZXRTdHJpcGVQdWJsaXNoYWJsZUtleSAoc3RyaXBlUHVibGlzaGFibGVLZXkpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcy5zZXRQdWJsaXNoYWJsZUtleShzdHJpcGVQdWJsaXNoYWJsZUtleSk7XG4gICAgfVxuXG4gICAgZ2V0Q3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnc3RyaXBlLWhhbmRlci5jcmVhdGUtY2FyZC10b2tlbi5zdWNjZXNzJztcbiAgICB9O1xuXG4gICAgZ2V0Q3JlYXRlQ2FyZFRva2VuRmFpbHVyZUV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnc3RyaXBlLWhhbmRlci5jcmVhdGUtY2FyZC10b2tlbi5mYWlsdXJlJztcbiAgICB9O1xuXG4gICAgY3JlYXRlQ2FyZFRva2VuIChkYXRhLCBmb3JtRWxlbWVudCkge1xuICAgICAgICB0aGlzLnN0cmlwZUpzLmNhcmQuY3JlYXRlVG9rZW4oZGF0YSwgKHN0YXR1cywgcmVzcG9uc2UpID0+IHtcbiAgICAgICAgICAgIGxldCBpc0Vycm9yUmVzcG9uc2UgPSByZXNwb25zZS5oYXNPd25Qcm9wZXJ0eSgnZXJyb3InKTtcblxuICAgICAgICAgICAgbGV0IGV2ZW50TmFtZSA9IGlzRXJyb3JSZXNwb25zZVxuICAgICAgICAgICAgICAgID8gdGhpcy5nZXRDcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnROYW1lKClcbiAgICAgICAgICAgICAgICA6IHRoaXMuZ2V0Q3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TmFtZSgpO1xuXG4gICAgICAgICAgICBmb3JtRWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChldmVudE5hbWUsIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHJlc3BvbnNlXG4gICAgICAgICAgICB9KSk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU3RyaXBlSGFuZGxlcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9zdHJpcGUtaGFuZGxlci5qcyIsImxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi9odHRwLWNsaWVudCcpO1xubGV0IFByb2dyZXNzQmFyID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9wcm9ncmVzcy1iYXInKTtcblxuY2xhc3MgVGVzdFJlc3VsdFJldHJpZXZlciB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGVsZW1lbnQub3duZXJEb2N1bWVudDtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zdGF0dXNVcmwgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdGF0dXMtdXJsJyk7XG4gICAgICAgIHRoaXMudW5yZXRyaWV2ZWRUYXNrSWRzVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdW5yZXRyaWV2ZWQtdGFzay1pZHMtdXJsJyk7XG4gICAgICAgIHRoaXMucmV0cmlldmVUYXNrc1VybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXJldHJpZXZlLXRhc2tzLXVybCcpO1xuICAgICAgICB0aGlzLnN1bW1hcnlVcmwgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdW1tYXJ5LXVybCcpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyID0gbmV3IFByb2dyZXNzQmFyKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJvZ3Jlc3MtYmFyJykpO1xuICAgICAgICB0aGlzLnN1bW1hcnkgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5zdW1tYXJ5Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLl9yZXRyaWV2ZVByZXBhcmluZ1N0YXR1cygpO1xuICAgIH07XG5cbiAgICBnZXRSZXRyaWV2ZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rlc3QtcmVzdWx0LXJldHJpZXZlci5yZXRyaWV2ZWQnO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IHJlc3BvbnNlID0gZXZlbnQuZGV0YWlsLnJlc3BvbnNlO1xuICAgICAgICBsZXQgcmVxdWVzdElkID0gZXZlbnQuZGV0YWlsLnJlcXVlc3RJZDtcblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVQcmVwYXJpbmdTdGF0dXMnKSB7XG4gICAgICAgICAgICBsZXQgY29tcGxldGlvblBlcmNlbnQgPSByZXNwb25zZS5jb21wbGV0aW9uX3BlcmNlbnQ7XG5cbiAgICAgICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQoY29tcGxldGlvblBlcmNlbnQpO1xuXG4gICAgICAgICAgICBpZiAoY29tcGxldGlvblBlcmNlbnQgPj0gMTAwKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVGaW5pc2hlZFN1bW1hcnkoKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fZGlzcGxheVByZXBhcmluZ1N1bW1hcnkocmVzcG9uc2UpO1xuICAgICAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24oKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZU5leHRSZW1vdmVUYXNrSWRDb2xsZWN0aW9uJykge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24ocmVzcG9uc2UpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlTmV4dFJlbW90ZVRhc2tDb2xsZWN0aW9uJykge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVQcmVwYXJpbmdTdGF0dXMoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZUZpbmlzaGVkU3VtbWFyeScpIHtcbiAgICAgICAgICAgIGxldCByZXRyaWV2ZWRTdW1tYXJ5Q29udGFpbmVyID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHJldHJpZXZlZFN1bW1hcnlDb250YWluZXIuaW5uZXJIVE1MID0gcmVzcG9uc2U7XG5cbiAgICAgICAgICAgIGxldCByZXRyaWV2ZWRFdmVudCA9IG5ldyBDdXN0b21FdmVudCh0aGlzLmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiByZXRyaWV2ZWRTdW1tYXJ5Q29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5saXN0ZWQtdGVzdCcpXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQocmV0cmlldmVkRXZlbnQpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZVByZXBhcmluZ1N0YXR1cyAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnN0YXR1c1VybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVQcmVwYXJpbmdTdGF0dXMnKTtcbiAgICB9O1xuXG4gICAgX3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24gKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldEpzb24odGhpcy51bnJldHJpZXZlZFRhc2tJZHNVcmwsIHRoaXMuZWxlbWVudCwgJ3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24nKTtcbiAgICB9O1xuXG4gICAgX3JldHJpZXZlTmV4dFJlbW90ZVRhc2tDb2xsZWN0aW9uIChyZW1vdGVUYXNrSWRzKSB7XG4gICAgICAgIEh0dHBDbGllbnQucG9zdCh0aGlzLnJldHJpZXZlVGFza3NVcmwsIHRoaXMuZWxlbWVudCwgJ3JldHJpZXZlTmV4dFJlbW90ZVRhc2tDb2xsZWN0aW9uJywgJ3JlbW90ZVRhc2tJZHM9JyArIHJlbW90ZVRhc2tJZHMuam9pbignLCcpKTtcbiAgICB9O1xuXG4gICAgX3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5ICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRUZXh0KHRoaXMuc3VtbWFyeVVybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVGaW5pc2hlZFN1bW1hcnknKTtcbiAgICB9O1xuXG4gICAgX2NyZWF0ZVByZXBhcmluZ1N1bW1hcnkgKHN0YXR1c0RhdGEpIHtcbiAgICAgICAgbGV0IGxvY2FsVGFza0NvdW50ID0gc3RhdHVzRGF0YS5sb2NhbF90YXNrX2NvdW50O1xuICAgICAgICBsZXQgcmVtb3RlVGFza0NvdW50ID0gc3RhdHVzRGF0YS5yZW1vdGVfdGFza19jb3VudDtcblxuICAgICAgICBpZiAobG9jYWxUYXNrQ291bnQgPT09IHVuZGVmaW5lZCAmJiByZW1vdGVUYXNrQ291bnQgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgcmV0dXJuICdQcmVwYXJpbmcgcmVzdWx0cyAmaGVsbGlwOyc7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gJ1ByZXBhcmluZyAmaGVsbGlwOyBjb2xsZWN0ZWQgPHN0cm9uZyBjbGFzcz1cImxvY2FsLXRhc2stY291bnRcIj4nICsgbG9jYWxUYXNrQ291bnQgKyAnPC9zdHJvbmc+IHJlc3VsdHMgb2YgPHN0cm9uZyBjbGFzcz1cInJlbW90ZS10YXNrLWNvdW50XCI+JyArIHJlbW90ZVRhc2tDb3VudCArICc8L3N0cm9uZz4nO1xuICAgIH07XG5cbiAgICBfZGlzcGxheVByZXBhcmluZ1N1bW1hcnkgKHN0YXR1c0RhdGEpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcmVwYXJpbmcgLnN1bW1hcnknKS5pbm5lckhUTUwgPSB0aGlzLl9jcmVhdGVQcmVwYXJpbmdTdW1tYXJ5KHN0YXR1c0RhdGEpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFJlc3VsdFJldHJpZXZlcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy90ZXN0LXJlc3VsdC1yZXRyaWV2ZXIuanMiLCIvKiBnbG9iYWwgQXdlc29tcGxldGUgKi9cblxubGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcbnJlcXVpcmUoJ2F3ZXNvbXBsZXRlJyk7XG5cbmNsYXNzIE1vZGFsIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0hUTUxFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5hcHBseUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignI2FwcGx5LWZpbHRlci1idXR0b24nKTtcbiAgICAgICAgdGhpcy5jbGVhckJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignI2NsZWFyLWZpbHRlci1idXR0b24nKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLmNsb3NlJyk7XG4gICAgICAgIHRoaXMuaW5wdXQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2lucHV0W25hbWU9ZmlsdGVyXScpO1xuICAgICAgICB0aGlzLmZpbHRlciA9IHRoaXMuaW5wdXQudmFsdWUudHJpbSgpO1xuICAgICAgICB0aGlzLnByZXZpb3VzRmlsdGVyID0gdGhpcy5pbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgIHRoaXMuYXBwbHlGaWx0ZXIgPSBmYWxzZTtcbiAgICAgICAgdGhpcy5hd2Vzb21lcGxldGUgPSBuZXcgQXdlc29tcGxldGUodGhpcy5pbnB1dCk7XG4gICAgICAgIHRoaXMuc3VnZ2VzdGlvbnMgPSBbXTtcbiAgICAgICAgdGhpcy5maWx0ZXJDaGFuZ2VkRXZlbnROYW1lID0gJ3Rlc3QtaGlzdG9yeS5tb2RhbC5maWx0ZXIuY2hhbmdlZCc7XG5cbiAgICAgICAgdGhpcy5pbml0KCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtTdHJpbmdbXX0gc3VnZ2VzdGlvbnNcbiAgICAgKi9cbiAgICBzZXRTdWdnZXN0aW9ucyAoc3VnZ2VzdGlvbnMpIHtcbiAgICAgICAgdGhpcy5zdWdnZXN0aW9ucyA9IHN1Z2dlc3Rpb25zO1xuICAgICAgICB0aGlzLmF3ZXNvbWVwbGV0ZS5saXN0ID0gdGhpcy5zdWdnZXN0aW9ucztcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgbGV0IHNob3duRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIodGhpcy5pbnB1dCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGhpZGVFdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgY29uc3QgV0lMRENBUkQgPSAnKic7XG4gICAgICAgICAgICBjb25zdCBmaWx0ZXJJc0VtcHR5ID0gdGhpcy5maWx0ZXIgPT09ICcnO1xuICAgICAgICAgICAgY29uc3Qgc3VnZ2VzdGlvbnNJbmNsdWRlc0ZpbHRlciA9IHRoaXMuc3VnZ2VzdGlvbnMuaW5jbHVkZXModGhpcy5maWx0ZXIpO1xuICAgICAgICAgICAgY29uc3QgZmlsdGVySXNXaWxkY2FyZFByZWZpeGVkID0gdGhpcy5maWx0ZXIuY2hhckF0KDApID09PSBXSUxEQ0FSRDtcbiAgICAgICAgICAgIGNvbnN0IGZpbHRlcklzV2lsZGNhcmRTdWZmaXhlZCA9IHRoaXMuZmlsdGVyLnNsaWNlKC0xKSA9PT0gV0lMRENBUkQ7XG5cbiAgICAgICAgICAgIGlmICghZmlsdGVySXNFbXB0eSAmJiAhc3VnZ2VzdGlvbnNJbmNsdWRlc0ZpbHRlcikge1xuICAgICAgICAgICAgICAgIGlmICghZmlsdGVySXNXaWxkY2FyZFByZWZpeGVkKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZmlsdGVyID0gV0lMRENBUkQgKyB0aGlzLmZpbHRlcjtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoIWZpbHRlcklzV2lsZGNhcmRTdWZmaXhlZCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZpbHRlciArPSBXSUxEQ0FSRDtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB0aGlzLmlucHV0LnZhbHVlID0gdGhpcy5maWx0ZXI7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuYXBwbHlGaWx0ZXIgPSB0aGlzLmZpbHRlciAhPT0gdGhpcy5wcmV2aW91c0ZpbHRlcjtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgaGlkZGVuRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICghdGhpcy5hcHBseUZpbHRlcikge1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KHRoaXMuZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDogdGhpcy5maWx0ZXJcbiAgICAgICAgICAgIH0pKTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgYXBwbHlCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmZpbHRlciA9IHRoaXMuaW5wdXQudmFsdWUudHJpbSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbGVhckJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuaW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgICAgIHRoaXMuZmlsdGVyID0gJyc7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5hcHBseUZpbHRlciA9IGZhbHNlO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdzaG93bi5icy5tb2RhbCcsIHNob3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hpZGUuYnMubW9kYWwnLCBoaWRlRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hpZGRlbi5icy5tb2RhbCcsIGhpZGRlbkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBhcHBseUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5jbGVhckJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGNsZWFyQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBNb2RhbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LWhpc3RvcnkvbW9kYWwuanMiLCJjbGFzcyBTdWdnZXN0aW9ucyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtEb2N1bWVudH0gZG9jdW1lbnRcbiAgICAgKiBAcGFyYW0ge1N0cmluZ30gc291cmNlVXJsXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50LCBzb3VyY2VVcmwpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLnNvdXJjZVVybCA9IHNvdXJjZVVybDtcbiAgICAgICAgdGhpcy5sb2FkZWRFdmVudE5hbWUgPSAndGVzdC1oaXN0b3J5LnN1Z2dlc3Rpb25zLmxvYWRlZCc7XG4gICAgfVxuXG4gICAgcmV0cmlldmUgKCkge1xuICAgICAgICBsZXQgcmVxdWVzdCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuICAgICAgICBsZXQgc3VnZ2VzdGlvbnMgPSBudWxsO1xuXG4gICAgICAgIHJlcXVlc3Qub3BlbignR0VUJywgdGhpcy5zb3VyY2VVcmwsIGZhbHNlKTtcblxuICAgICAgICBsZXQgcmVxdWVzdE9ubG9hZEhhbmRsZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAocmVxdWVzdC5zdGF0dXMgPj0gMjAwICYmIHJlcXVlc3Quc3RhdHVzIDwgNDAwKSB7XG4gICAgICAgICAgICAgICAgc3VnZ2VzdGlvbnMgPSBKU09OLnBhcnNlKHJlcXVlc3QucmVzcG9uc2VUZXh0KTtcblxuICAgICAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQodGhpcy5sb2FkZWRFdmVudE5hbWUsIHtcbiAgICAgICAgICAgICAgICAgICAgZGV0YWlsOiBzdWdnZXN0aW9uc1xuICAgICAgICAgICAgICAgIH0pKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICByZXF1ZXN0Lm9ubG9hZCA9IHJlcXVlc3RPbmxvYWRIYW5kbGVyLmJpbmQodGhpcyk7XG5cbiAgICAgICAgcmVxdWVzdC5zZW5kKCk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTdWdnZXN0aW9ucztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LWhpc3Rvcnkvc3VnZ2VzdGlvbnMuanMiLCJsZXQgRm9ybUJ1dHRvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcbmxldCBQcm9ncmVzc0JhciA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyJyk7XG5sZXQgVGFza1F1ZXVlcyA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZXMnKTtcblxuY2xhc3MgU3VtbWFyeSB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5jYW5jZWxBY3Rpb24gPSBuZXcgRm9ybUJ1dHRvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jYW5jZWwtYWN0aW9uJykpO1xuICAgICAgICB0aGlzLmNhbmNlbENyYXdsQWN0aW9uID0gbmV3IEZvcm1CdXR0b24oZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY2FuY2VsLWNyYXdsLWFjdGlvbicpKTtcbiAgICAgICAgdGhpcy5wcm9ncmVzc0JhciA9IG5ldyBQcm9ncmVzc0JhcihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9ncmVzcy1iYXInKSk7XG4gICAgICAgIHRoaXMuc3RhdGVMYWJlbCA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXN0YXRlLWxhYmVsJyk7XG4gICAgICAgIHRoaXMudGFza1F1ZXVlcyA9IG5ldyBUYXNrUXVldWVzKGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnRhc2stcXVldWVzJykpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLl9hZGRFdmVudExpc3RlbmVycygpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRSZW5kZXJBbW1lbmRtZW50RXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0ZXN0LXByb2dyZXNzLnN1bW1hcnkucmVuZGVyLWFtbWVuZG1lbnQnO1xuICAgIH07XG5cbiAgICBfYWRkRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICB0aGlzLmNhbmNlbEFjdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2FuY2VsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmNhbmNlbENyYXdsQWN0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9jYW5jZWxDcmF3bEFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgX2NhbmNlbEFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIHRoaXMuY2FuY2VsQWN0aW9uLm1hcmtBc0J1c3koKTtcbiAgICAgICAgdGhpcy5jYW5jZWxBY3Rpb24uZGVFbXBoYXNpemUoKTtcbiAgICB9O1xuXG4gICAgX2NhbmNlbENyYXdsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgdGhpcy5jYW5jZWxDcmF3bEFjdGlvbi5tYXJrQXNCdXN5KCk7XG4gICAgICAgIHRoaXMuY2FuY2VsQ3Jhd2xBY3Rpb24uZGVFbXBoYXNpemUoKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtvYmplY3R9IHN1bW1hcnlEYXRhXG4gICAgICovXG4gICAgcmVuZGVyIChzdW1tYXJ5RGF0YSkge1xuICAgICAgICBsZXQgcmVtb3RlVGVzdCA9IHN1bW1hcnlEYXRhLnJlbW90ZV90ZXN0O1xuXG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQocmVtb3RlVGVzdC5jb21wbGV0aW9uX3BlcmNlbnQpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyLnNldFN0eWxlKHN1bW1hcnlEYXRhLnRlc3Quc3RhdGUgPT09ICdjcmF3bGluZycgPyAnd2FybmluZycgOiAnZGVmYXVsdCcpO1xuICAgICAgICB0aGlzLnN0YXRlTGFiZWwuaW5uZXJUZXh0ID0gc3VtbWFyeURhdGEuc3RhdGVfbGFiZWw7XG4gICAgICAgIHRoaXMudGFza1F1ZXVlcy5yZW5kZXIocmVtb3RlVGVzdC50YXNrX2NvdW50LCByZW1vdGVUZXN0LnRhc2tfY291bnRfYnlfc3RhdGUpO1xuXG4gICAgICAgIGlmIChyZW1vdGVUZXN0LmFtbWVuZG1lbnRzICYmIHJlbW90ZVRlc3QuYW1tZW5kbWVudHMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFN1bW1hcnkuZ2V0UmVuZGVyQW1tZW5kbWVudEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiByZW1vdGVUZXN0LmFtbWVuZG1lbnRzWzBdXG4gICAgICAgICAgICB9KSk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFN1bW1hcnk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy9zdW1tYXJ5LmpzIiwiY2xhc3MgVGFza0lkTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJbXX0gdGFza0lkc1xuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlTGVuZ3RoXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKHRhc2tJZHMsIHBhZ2VMZW5ndGgpIHtcbiAgICAgICAgdGhpcy50YXNrSWRzID0gdGFza0lkcztcbiAgICAgICAgdGhpcy5wYWdlTGVuZ3RoID0gcGFnZUxlbmd0aDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7bnVtYmVyW119XG4gICAgICovXG4gICAgZ2V0Rm9yUGFnZSAocGFnZUluZGV4KSB7XG4gICAgICAgIGxldCBwYWdlTnVtYmVyID0gcGFnZUluZGV4ICsgMTtcblxuICAgICAgICByZXR1cm4gdGhpcy50YXNrSWRzLnNsaWNlKHBhZ2VJbmRleCAqIHRoaXMucGFnZUxlbmd0aCwgcGFnZU51bWJlciAqIHRoaXMucGFnZUxlbmd0aCk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrSWRMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1pZC1saXN0LmpzIiwiY2xhc3MgVGFza0xpc3RQYWdpbmF0aW9uIHtcbiAgICBjb25zdHJ1Y3RvciAocGFnZUxlbmd0aCwgdGFza0NvdW50KSB7XG4gICAgICAgIHRoaXMucGFnZUxlbmd0aCA9IHBhZ2VMZW5ndGg7XG4gICAgICAgIHRoaXMudGFza0NvdW50ID0gdGFza0NvdW50O1xuICAgICAgICB0aGlzLnBhZ2VDb3VudCA9IE1hdGguY2VpbCh0YXNrQ291bnQgLyBwYWdlTGVuZ3RoKTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gbnVsbDtcbiAgICB9XG5cbiAgICBzdGF0aWMgZ2V0U2VsZWN0b3IgKCkge1xuICAgICAgICByZXR1cm4gJy5wYWdpbmF0aW9uJztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0U2VsZWN0UGFnZUV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGFzay1saXN0LXBhZ2luYXRpb24uc2VsZWN0LXBhZ2UnO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRTZWxlY3RQcmV2aW91c1BhZ2VFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rhc2stbGlzdC1wYWdpbmF0aW9uLnNlbGVjdC1wcmV2aW91cy1wYWdlJztcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRTZWxlY3ROZXh0UGFnZUV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGFzay1saXN0LXBhZ2luYXRpb24uc2VsZWN0LW5leHQtcGFnZSc7XG4gICAgfVxuXG4gICAgaW5pdCAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnBhZ2VBY3Rpb25zID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCdhJyk7XG4gICAgICAgIHRoaXMucHJldmlvdXNBY3Rpb24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXJvbGU9cHJldmlvdXNdJyk7XG4gICAgICAgIHRoaXMubmV4dEFjdGlvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtcm9sZT1uZXh0XScpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLnBhZ2VBY3Rpb25zLCAocGFnZUFjdGlvbnMpID0+IHtcbiAgICAgICAgICAgIHBhZ2VBY3Rpb25zLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAgICAgICAgIGxldCBhY3Rpb25Db250YWluZXIgPSBwYWdlQWN0aW9ucy5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgIGlmICghYWN0aW9uQ29udGFpbmVyLmNsYXNzTGlzdC5jb250YWlucygnYWN0aXZlJykpIHtcbiAgICAgICAgICAgICAgICAgICAgbGV0IHJvbGUgPSBwYWdlQWN0aW9ucy5nZXRBdHRyaWJ1dGUoJ2RhdGEtcm9sZScpO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmIChyb2xlID09PSAnc2hvd1BhZ2UnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFBhZ2VFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRldGFpbDogcGFyc2VJbnQocGFnZUFjdGlvbnMuZ2V0QXR0cmlidXRlKCdkYXRhLXBhZ2UtaW5kZXgnKSwgMTApXG4gICAgICAgICAgICAgICAgICAgICAgICB9KSk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBpZiAocm9sZSA9PT0gJ3ByZXZpb3VzJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFRhc2tMaXN0UGFnaW5hdGlvbi5nZXRTZWxlY3RQcmV2aW91c1BhZ2VFdmVudE5hbWUoKSkpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHJvbGUgPT09ICduZXh0Jykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFRhc2tMaXN0UGFnaW5hdGlvbi5nZXRTZWxlY3ROZXh0UGFnZUV2ZW50TmFtZSgpKSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIGNyZWF0ZU1hcmt1cCAoKSB7XG4gICAgICAgIGxldCBtYXJrdXAgPSAnPHVsIGNsYXNzPVwicGFnaW5hdGlvblwiPic7XG5cbiAgICAgICAgbWFya3VwICs9ICc8bGkgY2xhc3M9XCJpcy14cyBwcmV2aW91cy1uZXh0IHByZXZpb3VzIGRpc2FibGVkIGhpZGRlbi1sZyBoaWRkZW4tbWQgaGlkZGVuLXNtXCI+PGEgaHJlZj1cIiNcIiBkYXRhLXJvbGU9XCJwcmV2aW91c1wiPjxpIGNsYXNzPVwiZmEgZmEtY2FyZXQtbGVmdFwiPjwvaT4gUHJldmlvdXM8L2E+PC9saT4nO1xuICAgICAgICBtYXJrdXAgKz0gJzxsaSBjbGFzcz1cImhpZGRlbi1sZyBoaWRkZW4tbWQgaGlkZGVuLXNtIGRpc2FibGVkXCI+PHNwYW4+UGFnZSA8c3Ryb25nIGNsYXNzPVwicGFnZS1udW1iZXJcIj4xPC9zdHJvbmc+IG9mIDxzdHJvbmc+JyArIHRoaXMucGFnZUNvdW50ICsgJzwvc3Ryb25nPjwvc3Bhbj48L2xpPic7XG5cbiAgICAgICAgZm9yIChsZXQgcGFnZUluZGV4ID0gMDsgcGFnZUluZGV4IDwgdGhpcy5wYWdlQ291bnQ7IHBhZ2VJbmRleCsrKSB7XG4gICAgICAgICAgICBsZXQgc3RhcnRJbmRleCA9IChwYWdlSW5kZXggKiB0aGlzLnBhZ2VMZW5ndGgpICsgMTtcbiAgICAgICAgICAgIGxldCBlbmRJbmRleCA9IE1hdGgubWluKHN0YXJ0SW5kZXggKyB0aGlzLnBhZ2VMZW5ndGggLSAxLCB0aGlzLnRhc2tDb3VudCk7XG5cbiAgICAgICAgICAgIG1hcmt1cCArPSAnPGxpIGNsYXNzPVwiaXMtbm90LXhzIGhpZGRlbi14cyAnICsgKHBhZ2VJbmRleCA9PT0gMCA/ICdhY3RpdmUnIDogJycpICsgJ1wiPjxhIGhyZWY9XCIjXCIgZGF0YS1wYWdlLWluZGV4PVwiJyArIHBhZ2VJbmRleCArICdcIiBkYXRhLXJvbGU9XCJzaG93UGFnZVwiPicgKyBzdGFydEluZGV4ICsgJyDigKYgJyArIGVuZEluZGV4ICsgJzwvYT48L2xpPic7XG4gICAgICAgIH1cblxuICAgICAgICBtYXJrdXAgKz0gJzxsaSBjbGFzcz1cIm5leHQgcHJldmlvdXMtbmV4dCBoaWRkZW4tbGcgaGlkZGVuLW1kIGhpZGRlbi1zbVwiPjxhIGhyZWY9XCIjXCIgZGF0YS1yb2xlPVwibmV4dFwiPk5leHQgPGkgY2xhc3M9XCJmYSBmYS1jYXJldC1yaWdodFwiPjwvaT48L2E+PC9saT4nO1xuICAgICAgICBtYXJrdXAgKz0gJzwvdWw+JztcblxuICAgICAgICByZXR1cm4gbWFya3VwO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBpc1JlcXVpcmVkICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMudGFza0NvdW50ID4gdGhpcy5wYWdlTGVuZ3RoO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBpc1JlbmRlcmVkICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudCAhPT0gbnVsbDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqL1xuICAgIHNlbGVjdFBhZ2UgKHBhZ2VJbmRleCkge1xuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5wYWdlQWN0aW9ucywgKHBhZ2VBY3Rpb24pID0+IHtcbiAgICAgICAgICAgIGxldCBpc0FjdGl2ZSA9IHBhcnNlSW50KHBhZ2VBY3Rpb24uZ2V0QXR0cmlidXRlKCdkYXRhLXBhZ2UtaW5kZXgnKSwgMTApID09PSBwYWdlSW5kZXg7XG4gICAgICAgICAgICBsZXQgYWN0aW9uQ29udGFpbmVyID0gcGFnZUFjdGlvbi5wYXJlbnROb2RlO1xuXG4gICAgICAgICAgICBpZiAoaXNBY3RpdmUpIHtcbiAgICAgICAgICAgICAgICBhY3Rpb25Db250YWluZXIuY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGFjdGlvbkNvbnRhaW5lci5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wYWdlLW51bWJlcicpLmlubmVyVGV4dCA9IChwYWdlSW5kZXggKyAxKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c0FjdGlvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2Rpc2FibGVkJyk7XG4gICAgICAgIHRoaXMubmV4dEFjdGlvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2Rpc2FibGVkJyk7XG5cbiAgICAgICAgaWYgKHBhZ2VJbmRleCA9PT0gMCkge1xuICAgICAgICAgICAgdGhpcy5wcmV2aW91c0FjdGlvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2Rpc2FibGVkJyk7XG4gICAgICAgIH0gZWxzZSBpZiAocGFnZUluZGV4ID09PSB0aGlzLnBhZ2VDb3VudCAtIDEpIHtcbiAgICAgICAgICAgIHRoaXMubmV4dEFjdGlvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2Rpc2FibGVkJyk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tMaXN0UGFnaW5hdGlvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC1wYWdpbmF0b3IuanMiLCJsZXQgVGFza0xpc3RNb2RlbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGFzay1saXN0Jyk7XG5sZXQgSWNvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvaWNvbicpO1xubGV0IFRhc2tJZExpc3QgPSByZXF1aXJlKCcuL3Rhc2staWQtbGlzdCcpO1xubGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9odHRwLWNsaWVudCcpO1xuXG5jbGFzcyBUYXNrTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VMZW5ndGhcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCwgcGFnZUxlbmd0aCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmN1cnJlbnRQYWdlSW5kZXggPSAwO1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSBwYWdlTGVuZ3RoO1xuICAgICAgICB0aGlzLnRhc2tJZExpc3QgPSBudWxsO1xuICAgICAgICB0aGlzLmlzSW5pdGlhbGl6aW5nID0gZmFsc2U7XG4gICAgICAgIHRoaXMudGFza0xpc3RNb2RlbHMgPSB7fTtcbiAgICAgICAgdGhpcy5oZWFkaW5nID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdoMicpO1xuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBAdHlwZSB7SWNvbn1cbiAgICAgICAgICovXG4gICAgICAgIHRoaXMuYnVzeUljb24gPSB0aGlzLl9jcmVhdGVCdXN5SWNvbigpO1xuICAgICAgICB0aGlzLmhlYWRpbmcuYXBwZW5kQ2hpbGQodGhpcy5idXN5SWNvbi5lbGVtZW50KTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5pc0luaXRpYWxpemluZyA9IHRydWU7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdoaWRkZW4nKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLl9yZXF1ZXN0VGFza0lkcygpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gaW5kZXhcbiAgICAgKi9cbiAgICBzZXRDdXJyZW50UGFnZUluZGV4IChpbmRleCkge1xuICAgICAgICB0aGlzLmN1cnJlbnRQYWdlSW5kZXggPSBpbmRleDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IHBhZ2luYXRpb25FbGVtZW50XG4gICAgICovXG4gICAgc2V0UGFnaW5hdGlvbkVsZW1lbnQgKHBhZ2luYXRpb25FbGVtZW50KSB7XG4gICAgICAgIHRoaXMuaGVhZGluZy5pbnNlcnRBZGphY2VudEVsZW1lbnQoJ2FmdGVyZW5kJywgcGFnaW5hdGlvbkVsZW1lbnQpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldEluaXRpYWxpemVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0YXNrLWxpc3QuaW5pdGlhbGl6ZWQnO1xuICAgIH07XG5cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgcmVxdWVzdElkID0gZXZlbnQuZGV0YWlsLnJlcXVlc3RJZDtcbiAgICAgICAgbGV0IHJlc3BvbnNlID0gZXZlbnQuZGV0YWlsLnJlc3BvbnNlO1xuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXF1ZXN0VGFza0lkcycpIHtcbiAgICAgICAgICAgIHRoaXMudGFza0lkTGlzdCA9IG5ldyBUYXNrSWRMaXN0KHJlc3BvbnNlLCB0aGlzLnBhZ2VMZW5ndGgpO1xuICAgICAgICAgICAgdGhpcy5pc0luaXRpYWxpemluZyA9IGZhbHNlO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFRhc2tMaXN0LmdldEluaXRpYWxpemVkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVRhc2tQYWdlJykge1xuICAgICAgICAgICAgbGV0IHRhc2tMaXN0TW9kZWwgPSBuZXcgVGFza0xpc3RNb2RlbCh0aGlzLl9jcmVhdGVUYXNrTGlzdEVsZW1lbnRGcm9tSHRtbChyZXNwb25zZSkpO1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IHRhc2tMaXN0TW9kZWwuZ2V0UGFnZUluZGV4KCk7XG5cbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RNb2RlbHNbcGFnZUluZGV4XSA9IHRhc2tMaXN0TW9kZWw7XG4gICAgICAgICAgICB0aGlzLnJlbmRlcihwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUYXNrU2V0V2l0aERlbGF5KFxuICAgICAgICAgICAgICAgIHBhZ2VJbmRleCxcbiAgICAgICAgICAgICAgICB0YXNrTGlzdE1vZGVsLmdldFRhc2tzQnlTdGF0ZXMoWydpbi1wcm9ncmVzcycsICdxdWV1ZWQtZm9yLWFzc2lnbm1lbnQnLCAncXVldWVkJ10pLnNsaWNlKDAsIDEwKVxuICAgICAgICAgICAgKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVRhc2tTZXQnKSB7XG4gICAgICAgICAgICBsZXQgdXBkYXRlZFRhc2tMaXN0TW9kZWwgPSBuZXcgVGFza0xpc3RNb2RlbCh0aGlzLl9jcmVhdGVUYXNrTGlzdEVsZW1lbnRGcm9tSHRtbChyZXNwb25zZSkpO1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IHVwZGF0ZWRUYXNrTGlzdE1vZGVsLmdldFBhZ2VJbmRleCgpO1xuICAgICAgICAgICAgbGV0IHRhc2tMaXN0TW9kZWwgPSB0aGlzLnRhc2tMaXN0TW9kZWxzW3BhZ2VJbmRleF07XG5cbiAgICAgICAgICAgIHRhc2tMaXN0TW9kZWwudXBkYXRlRnJvbVRhc2tMaXN0KHVwZGF0ZWRUYXNrTGlzdE1vZGVsKTtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlVGFza1NldFdpdGhEZWxheShcbiAgICAgICAgICAgICAgICBwYWdlSW5kZXgsXG4gICAgICAgICAgICAgICAgdGFza0xpc3RNb2RlbC5nZXRUYXNrc0J5U3RhdGVzKFsnaW4tcHJvZ3Jlc3MnLCAncXVldWVkLWZvci1hc3NpZ25tZW50JywgJ3F1ZXVlZCddKS5zbGljZSgwLCAxMClcbiAgICAgICAgICAgICk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JlcXVlc3RUYXNrSWRzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGFzay1pZHMtdXJsJyksIHRoaXMuZWxlbWVudCwgJ3JlcXVlc3RUYXNrSWRzJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKi9cbiAgICByZW5kZXIgKHBhZ2VJbmRleCkge1xuICAgICAgICB0aGlzLmJ1c3lJY29uLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnaGlkZGVuJyk7XG5cbiAgICAgICAgbGV0IGhhc1Rhc2tMaXN0RWxlbWVudEZvclBhZ2UgPSBPYmplY3Qua2V5cyh0aGlzLnRhc2tMaXN0TW9kZWxzKS5pbmNsdWRlcyhwYWdlSW5kZXgudG9TdHJpbmcoMTApKTtcbiAgICAgICAgaWYgKCFoYXNUYXNrTGlzdEVsZW1lbnRGb3JQYWdlKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRhc2tQYWdlKHBhZ2VJbmRleCk7XG5cbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGxldCB0YXNrTGlzdEVsZW1lbnQgPSB0aGlzLnRhc2tMaXN0TW9kZWxzW3BhZ2VJbmRleF07XG5cbiAgICAgICAgaWYgKHBhZ2VJbmRleCA9PT0gdGhpcy5jdXJyZW50UGFnZUluZGV4KSB7XG4gICAgICAgICAgICBsZXQgcmVuZGVyZWRUYXNrTGlzdEVsZW1lbnQgPSBuZXcgVGFza0xpc3RNb2RlbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnRhc2stbGlzdCcpKTtcblxuICAgICAgICAgICAgaWYgKHJlbmRlcmVkVGFza0xpc3RFbGVtZW50Lmhhc1BhZ2VJbmRleCgpKSB7XG4gICAgICAgICAgICAgICAgbGV0IGN1cnJlbnRQYWdlTGlzdEVsZW1lbnQgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnRhc2stbGlzdCcpO1xuICAgICAgICAgICAgICAgIGxldCBzZWxlY3RlZFBhZ2VMaXN0RWxlbWVudCA9IHRoaXMudGFza0xpc3RNb2RlbHNbdGhpcy5jdXJyZW50UGFnZUluZGV4XS5lbGVtZW50O1xuXG4gICAgICAgICAgICAgICAgY3VycmVudFBhZ2VMaXN0RWxlbWVudC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZChzZWxlY3RlZFBhZ2VMaXN0RWxlbWVudCwgY3VycmVudFBhZ2VMaXN0RWxlbWVudCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZCh0YXNrTGlzdEVsZW1lbnQuZWxlbWVudCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLmJ1c3lJY29uLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnaGlkZGVuJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9yZXRyaWV2ZVRhc2tQYWdlIChwYWdlSW5kZXgpIHtcbiAgICAgICAgbGV0IHRhc2tJZHMgPSB0aGlzLnRhc2tJZExpc3QuZ2V0Rm9yUGFnZShwYWdlSW5kZXgpO1xuICAgICAgICBsZXQgcG9zdERhdGEgPSAncGFnZUluZGV4PScgKyBwYWdlSW5kZXggKyAnJnRhc2tJZHNbXT0nICsgdGFza0lkcy5qb2luKCcmdGFza0lkc1tdPScpO1xuXG4gICAgICAgIEh0dHBDbGllbnQucG9zdChcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGFza2xpc3QtdXJsJyksXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQsXG4gICAgICAgICAgICAncmV0cmlldmVUYXNrUGFnZScsXG4gICAgICAgICAgICBwb3N0RGF0YVxuICAgICAgICApO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICogQHBhcmFtIHtUYXNrW119IHRhc2tzXG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfcmV0cmlldmVUYXNrU2V0V2l0aERlbGF5IChwYWdlSW5kZXgsIHRhc2tzKSB7XG4gICAgICAgIHdpbmRvdy5zZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlVGFza1NldChwYWdlSW5kZXgsIHRhc2tzKTtcbiAgICAgICAgfSwgMTAwMCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKiBAcGFyYW0ge1Rhc2tbXX0gdGFza3NcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9yZXRyaWV2ZVRhc2tTZXQgKHBhZ2VJbmRleCwgdGFza3MpIHtcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudFBhZ2VJbmRleCA9PT0gcGFnZUluZGV4ICYmIHRhc2tzLmxlbmd0aCkge1xuICAgICAgICAgICAgbGV0IHRhc2tJZHMgPSBbXTtcblxuICAgICAgICAgICAgdGFza3MuZm9yRWFjaChmdW5jdGlvbiAodGFzaykge1xuICAgICAgICAgICAgICAgIHRhc2tJZHMucHVzaCh0YXNrLmdldElkKCkpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGxldCBwb3N0RGF0YSA9ICdwYWdlSW5kZXg9JyArIHBhZ2VJbmRleCArICcmdGFza0lkc1tdPScgKyB0YXNrSWRzLmpvaW4oJyZ0YXNrSWRzW109Jyk7XG5cbiAgICAgICAgICAgIEh0dHBDbGllbnQucG9zdChcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXRhc2tsaXN0LXVybCcpLFxuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudCxcbiAgICAgICAgICAgICAgICAncmV0cmlldmVUYXNrU2V0JyxcbiAgICAgICAgICAgICAgICBwb3N0RGF0YVxuICAgICAgICAgICAgKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30gaHRtbFxuICAgICAqIEByZXR1cm5zIHtFbGVtZW50fVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVRhc2tMaXN0RWxlbWVudEZyb21IdG1sIChodG1sKSB7XG4gICAgICAgIGxldCBjb250YWluZXIgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmlubmVySFRNTCA9IGh0bWw7XG5cbiAgICAgICAgcmV0dXJuIGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcudGFzay1saXN0Jyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtJY29ufVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZUJ1c3lJY29uICgpIHtcbiAgICAgICAgbGV0IGNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmFcIj48L2k+JztcblxuICAgICAgICBsZXQgaWNvbiA9IG5ldyBJY29uKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcuZmEnKSk7XG4gICAgICAgIGljb24uc2V0QnVzeSgpO1xuXG4gICAgICAgIHJldHVybiBpY29uO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC5qcyIsImxldCBTb3J0ID0gcmVxdWlyZSgnLi9zb3J0Jyk7XG5cbmNsYXNzIEJ5UGFnZUxpc3Qge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc29ydCA9IG5ldyBTb3J0KGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnNvcnQnKSwgZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuanMtc29ydGFibGUtaXRlbScpKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuc29ydC5pbml0KCk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBCeVBhZ2VMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvYnktcGFnZS1saXN0LmpzIiwibGV0IFNvcnRDb250cm9sID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9zb3J0LWNvbnRyb2wnKTtcbmxldCBTb3J0YWJsZUl0ZW0gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3NvcnRhYmxlLWl0ZW0nKTtcbmxldCBTb3J0YWJsZUl0ZW1MaXN0ID0gcmVxdWlyZSgnLi4vbW9kZWwvc29ydGFibGUtaXRlbS1saXN0Jyk7XG5sZXQgU29ydENvbnRyb2xDb2xsZWN0aW9uID0gcmVxdWlyZSgnLi4vbW9kZWwvc29ydC1jb250cm9sLWNvbGxlY3Rpb24nKTtcblxuY2xhc3MgU29ydCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICogQHBhcmFtIHtOb2RlTGlzdH0gc29ydGFibGVJdGVtc05vZGVMaXN0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHNvcnRhYmxlSXRlbXNOb2RlTGlzdCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnNvcnRDb250cm9sQ29sbGVjdGlvbiA9IHRoaXMuX2NyZWF0ZVNvcnRhYmxlQ29udHJvbENvbGxlY3Rpb24oKTtcbiAgICAgICAgdGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QgPSBzb3J0YWJsZUl0ZW1zTm9kZUxpc3Q7XG4gICAgICAgIHRoaXMuc29ydGFibGVJdGVtc0xpc3QgPSB0aGlzLl9jcmVhdGVTb3J0YWJsZUl0ZW1MaXN0KCk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnaW52aXNpYmxlJyk7XG4gICAgICAgIHRoaXMuc29ydENvbnRyb2xDb2xsZWN0aW9uLmNvbnRyb2xzLmZvckVhY2goKGNvbnRyb2wpID0+IHtcbiAgICAgICAgICAgIGNvbnRyb2wuaW5pdCgpO1xuICAgICAgICAgICAgY29udHJvbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoU29ydENvbnRyb2wuZ2V0U29ydFJlcXVlc3RlZEV2ZW50TmFtZSgpLCB0aGlzLl9zb3J0Q29udHJvbENsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtTb3J0YWJsZUl0ZW1MaXN0fVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVNvcnRhYmxlSXRlbUxpc3QgKCkge1xuICAgICAgICBsZXQgc29ydGFibGVJdGVtcyA9IFtdO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLnNvcnRhYmxlSXRlbXNOb2RlTGlzdCwgKHNvcnRhYmxlSXRlbUVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIHNvcnRhYmxlSXRlbXMucHVzaChuZXcgU29ydGFibGVJdGVtKHNvcnRhYmxlSXRlbUVsZW1lbnQpKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIG5ldyBTb3J0YWJsZUl0ZW1MaXN0KHNvcnRhYmxlSXRlbXMpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtTb3J0Q29udHJvbENvbGxlY3Rpb259XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlU29ydGFibGVDb250cm9sQ29sbGVjdGlvbiAoKSB7XG4gICAgICAgIGxldCBjb250cm9scyA9IFtdO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnNvcnQtY29udHJvbCcpLCAoc29ydENvbnRyb2xFbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBjb250cm9scy5wdXNoKG5ldyBTb3J0Q29udHJvbChzb3J0Q29udHJvbEVsZW1lbnQpKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIG5ldyBTb3J0Q29udHJvbENvbGxlY3Rpb24oY29udHJvbHMpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX3NvcnRDb250cm9sQ2xpY2tFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QuaXRlbSgwKS5wYXJlbnRFbGVtZW50O1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLnNvcnRhYmxlSXRlbXNOb2RlTGlzdCwgKHNvcnRhYmxlSXRlbUVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIHNvcnRhYmxlSXRlbUVsZW1lbnQucGFyZW50RWxlbWVudC5yZW1vdmVDaGlsZChzb3J0YWJsZUl0ZW1FbGVtZW50KTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgbGV0IHNvcnRlZEl0ZW1zID0gdGhpcy5zb3J0YWJsZUl0ZW1zTGlzdC5zb3J0KGV2ZW50LmRldGFpbC5rZXlzKTtcblxuICAgICAgICBzb3J0ZWRJdGVtcy5mb3JFYWNoKChzb3J0YWJsZUl0ZW0pID0+IHtcbiAgICAgICAgICAgIHBhcmVudC5pbnNlcnRBZGphY2VudEVsZW1lbnQoJ2JlZm9yZWVuZCcsIHNvcnRhYmxlSXRlbS5lbGVtZW50KTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5zb3J0Q29udHJvbENvbGxlY3Rpb24uc2V0U29ydGVkKGV2ZW50LnRhcmdldCk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTb3J0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvc29ydC5qcyIsImxldCBNb2RhbCA9IHJlcXVpcmUoJ2Jvb3RzdHJhcC5uYXRpdmUnKS5Nb2RhbDtcblxuLyoqXG4gKiBAcGFyYW0ge05vZGVMaXN0fSB0YXNrVHlwZUNvbnRhaW5lcnNcbiAqL1xubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAodGFza1R5cGVDb250YWluZXJzKSB7XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0YXNrVHlwZUNvbnRhaW5lcnMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgbGV0IHVuYXZhaWxhYmxlVGFza1R5cGUgPSB0YXNrVHlwZUNvbnRhaW5lcnNbaV07XG4gICAgICAgIGxldCB0YXNrVHlwZUtleSA9IHVuYXZhaWxhYmxlVGFza1R5cGUuZ2V0QXR0cmlidXRlKCdkYXRhLXRhc2stdHlwZScpO1xuICAgICAgICBsZXQgbW9kYWxJZCA9IHRhc2tUeXBlS2V5ICsgJy1hY2NvdW50LXJlcXVpcmVkLW1vZGFsJztcbiAgICAgICAgbGV0IG1vZGFsRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG1vZGFsSWQpO1xuICAgICAgICBsZXQgbW9kYWwgPSBuZXcgTW9kYWwobW9kYWxFbGVtZW50KTtcblxuICAgICAgICB1bmF2YWlsYWJsZVRhc2tUeXBlLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbW9kYWwuc2hvdygpO1xuICAgICAgICB9KTtcbiAgICB9XG59O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlci5qcyIsImNsYXNzIEZvcm1WYWxpZGF0b3Ige1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7U3RyaXBlfSBzdHJpcGVKc1xuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChzdHJpcGVKcykge1xuICAgICAgICB0aGlzLnN0cmlwZUpzID0gc3RyaXBlSnM7XG4gICAgICAgIHRoaXMuaW52YWxpZEZpZWxkID0gbnVsbDtcbiAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnJztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtPYmplY3R9IGRhdGFcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICB2YWxpZGF0ZSAoZGF0YSkge1xuICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IG51bGw7XG5cbiAgICAgICAgT2JqZWN0LmVudHJpZXMoZGF0YSkuZm9yRWFjaCgoW2tleSwgdmFsdWVdKSA9PiB7XG4gICAgICAgICAgICBpZiAoIXRoaXMuaW52YWxpZEZpZWxkKSB7XG4gICAgICAgICAgICAgICAgbGV0IGNvbXBhcmF0b3JWYWx1ZSA9IHZhbHVlLnRyaW0oKTtcblxuICAgICAgICAgICAgICAgIGlmIChjb21wYXJhdG9yVmFsdWUgPT09ICcnKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuaW52YWxpZEZpZWxkID0ga2V5O1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmVycm9yTWVzc2FnZSA9ICdlbXB0eSc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBpZiAodGhpcy5pbnZhbGlkRmllbGQpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICghdGhpcy5zdHJpcGVKcy5jYXJkLnZhbGlkYXRlQ2FyZE51bWJlcihkYXRhLm51bWJlcikpIHtcbiAgICAgICAgICAgIHRoaXMuaW52YWxpZEZpZWxkID0gJ251bWJlcic7XG4gICAgICAgICAgICB0aGlzLmVycm9yTWVzc2FnZSA9ICdpbnZhbGlkJztcblxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKCF0aGlzLnN0cmlwZUpzLmNhcmQudmFsaWRhdGVFeHBpcnkoZGF0YS5leHBfbW9udGgsIGRhdGEuZXhwX3llYXIpKSB7XG4gICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9ICdleHBfbW9udGgnO1xuICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnaW52YWxpZCc7XG5cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICghdGhpcy5zdHJpcGVKcy5jYXJkLnZhbGlkYXRlQ1ZDKGRhdGEuY3ZjKSkge1xuICAgICAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSAnY3ZjJztcbiAgICAgICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJ2ludmFsaWQnO1xuXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEZvcm1WYWxpZGF0b3I7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdXNlci1hY2NvdW50LWNhcmQvZm9ybS12YWxpZGF0b3IuanMiLCJsZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xubGV0IEFsZXJ0RmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2FsZXJ0LWZhY3RvcnknKTtcbmxldCBGb3JtQnV0dG9uID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbicpO1xuXG5jbGFzcyBGb3JtIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCwgdmFsaWRhdG9yKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMudmFsaWRhdG9yID0gdmFsaWRhdG9yO1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbiA9IG5ldyBGb3JtQnV0dG9uKGVsZW1lbnQucXVlcnlTZWxlY3RvcignYnV0dG9uW3R5cGU9c3VibWl0XScpKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdzdWJtaXQnLCB0aGlzLl9zdWJtaXRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBnZXRTdHJpcGVQdWJsaXNoYWJsZUtleSAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0cmlwZS1wdWJsaXNoYWJsZS1rZXknKTtcbiAgICB9O1xuXG4gICAgZ2V0VXBkYXRlQ2FyZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndXNlci5hY2NvdW50LmNhcmQudXBkYXRlJztcbiAgICB9XG5cbiAgICBkaXNhYmxlICgpIHtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24uZGlzYWJsZSgpO1xuICAgIH07XG5cbiAgICBlbmFibGUgKCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbi5tYXJrQXNBdmFpbGFibGUoKTtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24uZW5hYmxlKCk7XG4gICAgfTtcblxuICAgIF9nZXREYXRhICgpIHtcbiAgICAgICAgY29uc3QgZGF0YSA9IHt9O1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnW2RhdGEtc3RyaXBlXScpLCBmdW5jdGlvbiAoZGF0YUVsZW1lbnQpIHtcbiAgICAgICAgICAgIGxldCBmaWVsZEtleSA9IGRhdGFFbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdHJpcGUnKTtcblxuICAgICAgICAgICAgZGF0YVtmaWVsZEtleV0gPSBkYXRhRWxlbWVudC52YWx1ZTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGRhdGE7XG4gICAgfVxuXG4gICAgX3N1Ym1pdEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgICAgIHRoaXMuX3JlbW92ZUVycm9yQWxlcnRzKCk7XG4gICAgICAgIHRoaXMuZGlzYWJsZSgpO1xuXG4gICAgICAgIGxldCBkYXRhID0gdGhpcy5fZ2V0RGF0YSgpO1xuICAgICAgICBsZXQgaXNWYWxpZCA9IHRoaXMudmFsaWRhdG9yLnZhbGlkYXRlKGRhdGEpO1xuXG4gICAgICAgIGlmICghaXNWYWxpZCkge1xuICAgICAgICAgICAgdGhpcy5oYW5kbGVSZXNwb25zZUVycm9yKHRoaXMuY3JlYXRlUmVzcG9uc2VFcnJvcih0aGlzLnZhbGlkYXRvci5pbnZhbGlkRmllbGQsIHRoaXMudmFsaWRhdG9yLmVycm9yTWVzc2FnZSkpO1xuICAgICAgICAgICAgdGhpcy5lbmFibGUoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGxldCBldmVudCA9IG5ldyBDdXN0b21FdmVudCh0aGlzLmdldFVwZGF0ZUNhcmRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDogZGF0YVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KGV2ZW50KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfcmVtb3ZlRXJyb3JBbGVydHMgKCkge1xuICAgICAgICBsZXQgZXJyb3JBbGVydHMgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmFsZXJ0Jyk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKGVycm9yQWxlcnRzLCBmdW5jdGlvbiAoZXJyb3JBbGVydCkge1xuICAgICAgICAgICAgZXJyb3JBbGVydC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGVycm9yQWxlcnQpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX2Rpc3BsYXlGaWVsZEVycm9yIChmaWVsZCwgZXJyb3IpIHtcbiAgICAgICAgbGV0IGFsZXJ0ID0gQWxlcnRGYWN0b3J5LmNyZWF0ZUZyb21Db250ZW50KHRoaXMuZG9jdW1lbnQsICc8cD4nICsgZXJyb3IubWVzc2FnZSArICc8L3A+JywgZmllbGQuZ2V0QXR0cmlidXRlKCdpZCcpKTtcbiAgICAgICAgbGV0IGVycm9yQ29udGFpbmVyID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLWZvcj0nICsgZmllbGQuZ2V0QXR0cmlidXRlKCdpZCcpICsgJ10nKTtcblxuICAgICAgICBpZiAoIWVycm9yQ29udGFpbmVyKSB7XG4gICAgICAgICAgICBlcnJvckNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1mb3IqPScgKyBmaWVsZC5nZXRBdHRyaWJ1dGUoJ2lkJykgKyAnXScpO1xuICAgICAgICB9XG5cbiAgICAgICAgZXJyb3JDb250YWluZXIuYXBwZW5kKGFsZXJ0LmVsZW1lbnQpO1xuICAgIH07XG5cbiAgICBoYW5kbGVSZXNwb25zZUVycm9yIChlcnJvcikge1xuICAgICAgICBsZXQgZmllbGQgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtc3RyaXBlPScgKyBlcnJvci5wYXJhbSArICddJyk7XG5cbiAgICAgICAgZm9ybUZpZWxkRm9jdXNlcihmaWVsZCk7XG4gICAgICAgIHRoaXMuX2Rpc3BsYXlGaWVsZEVycm9yKGZpZWxkLCBlcnJvcik7XG4gICAgfTtcblxuICAgIGNyZWF0ZVJlc3BvbnNlRXJyb3IgKGZpZWxkLCBzdGF0ZSkge1xuICAgICAgICBsZXQgZXJyb3JNZXNzYWdlID0gJyc7XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnZW1wdHknKSB7XG4gICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnSG9sZCBvbiwgeW91IGNhblxcJ3QgbGVhdmUgdGhpcyBlbXB0eSc7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoc3RhdGUgPT09ICdpbnZhbGlkJykge1xuICAgICAgICAgICAgaWYgKGZpZWxkID09PSAnbnVtYmVyJykge1xuICAgICAgICAgICAgICAgIGVycm9yTWVzc2FnZSA9ICdUaGUgY2FyZCBudW1iZXIgaXMgbm90IHF1aXRlIHJpZ2h0JztcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKGZpZWxkID09PSAnZXhwX21vbnRoJykge1xuICAgICAgICAgICAgICAgIGVycm9yTWVzc2FnZSA9ICdBbiBleHBpcnkgZGF0ZSBpbiB0aGUgZnV0dXJlIGlzIGJldHRlcic7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ2N2YycpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnVGhlIENWQyBzaG91bGQgYmUgMyBvciA0IGRpZ2l0cyc7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgcGFyYW06IGZpZWxkLFxuICAgICAgICAgICAgbWVzc2FnZTogZXJyb3JNZXNzYWdlXG4gICAgICAgIH07XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEZvcm07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdXNlci1hY2NvdW50LWNhcmQvZm9ybS5qcyIsImNvbnN0IFNjcm9sbFRvID0gcmVxdWlyZSgnLi4vc2Nyb2xsLXRvJyk7XG5cbmNsYXNzIE5hdkJhckFuY2hvciB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHNjcm9sbE9mZnNldFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zY3JvbGxPZmZzZXQgPSBzY3JvbGxPZmZzZXQ7XG4gICAgICAgIHRoaXMudGFyZ2V0SWQgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnaHJlZicpLnJlcGxhY2UoJyMnLCAnJyk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5oYW5kbGVDbGljay5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgaGFuZGxlQ2xpY2sgKGV2ZW50KSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgICAgIGxldCB0YXJnZXQgPSB0aGlzLmdldFRhcmdldCgpO1xuXG4gICAgICAgIGlmICh0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdqcy1maXJzdCcpKSB7XG4gICAgICAgICAgICBTY3JvbGxUby5nb1RvKHRhcmdldCwgMCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBTY3JvbGxUby5zY3JvbGxUbyh0YXJnZXQsIHRoaXMuc2Nyb2xsT2Zmc2V0KTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIGdldFRhcmdldCAoKSB7XG4gICAgICAgIHJldHVybiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLnRhcmdldElkKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTmF2QmFyQW5jaG9yO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItYW5jaG9yLmpzIiwiY29uc3QgTmF2QmFyQW5jaG9yID0gcmVxdWlyZSgnLi9uYXZiYXItYW5jaG9yJyk7XG5cbmNsYXNzIE5hdkJhckl0ZW0ge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBzY3JvbGxPZmZzZXRcbiAgICAgKiBAcGFyYW0ge2Z1bmN0aW9ufSBOYXZCYXJMaXN0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHNjcm9sbE9mZnNldCwgTmF2QmFyTGlzdCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmFuY2hvciA9IG51bGw7XG4gICAgICAgIHRoaXMubmF2QmFyTGlzdCA9IG51bGw7XG5cbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBlbGVtZW50LmNoaWxkcmVuLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICBsZXQgY2hpbGQgPSBlbGVtZW50LmNoaWxkcmVuLml0ZW0oaSk7XG5cbiAgICAgICAgICAgIGlmIChjaGlsZC5ub2RlTmFtZSA9PT0gJ0EnICYmIGNoaWxkLmdldEF0dHJpYnV0ZSgnaHJlZicpWzBdID09PSAnIycpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmFuY2hvciA9IG5ldyBOYXZCYXJBbmNob3IoY2hpbGQsIHNjcm9sbE9mZnNldCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChjaGlsZC5ub2RlTmFtZSA9PT0gJ1VMJykge1xuICAgICAgICAgICAgICAgIHRoaXMubmF2QmFyTGlzdCA9IG5ldyBOYXZCYXJMaXN0KGNoaWxkLCBzY3JvbGxPZmZzZXQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcblxuICAgIGdldFRhcmdldHMgKCkge1xuICAgICAgICBsZXQgdGFyZ2V0cyA9IFtdO1xuXG4gICAgICAgIGlmICh0aGlzLmFuY2hvcikge1xuICAgICAgICAgICAgdGFyZ2V0cy5wdXNoKHRoaXMuYW5jaG9yLmdldFRhcmdldCgpKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QpIHtcbiAgICAgICAgICAgIHRoaXMubmF2QmFyTGlzdC5nZXRUYXJnZXRzKCkuZm9yRWFjaChmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgdGFyZ2V0cy5wdXNoKHRhcmdldCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiB0YXJnZXRzO1xuICAgIH1cblxuICAgIGNvbnRhaW5zVGFyZ2V0SWQgKHRhcmdldElkKSB7XG4gICAgICAgIGlmICh0aGlzLmFuY2hvciAmJiB0aGlzLmFuY2hvci50YXJnZXRJZCA9PT0gdGFyZ2V0SWQpIHtcbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMubmF2QmFyTGlzdCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMubmF2QmFyTGlzdC5jb250YWluc1RhcmdldElkKHRhcmdldElkKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9O1xuXG4gICAgc2V0QWN0aXZlICh0YXJnZXRJZCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG5cbiAgICAgICAgaWYgKHRoaXMubmF2QmFyTGlzdCAmJiB0aGlzLm5hdkJhckxpc3QuY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCkpIHtcbiAgICAgICAgICAgIHRoaXMubmF2QmFyTGlzdC5zZXRBY3RpdmUodGFyZ2V0SWQpO1xuICAgICAgICB9XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBOYXZCYXJJdGVtO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItaXRlbS5qcyIsImxldCBOYXZCYXJJdGVtID0gcmVxdWlyZSgnLi9uYXZiYXItaXRlbScpO1xuXG5jbGFzcyBOYXZCYXJMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gc2Nyb2xsT2Zmc2V0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHNjcm9sbE9mZnNldCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLm5hdkJhckl0ZW1zID0gW107XG5cbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBlbGVtZW50LmNoaWxkcmVuLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckl0ZW1zLnB1c2gobmV3IE5hdkJhckl0ZW0oZWxlbWVudC5jaGlsZHJlbi5pdGVtKGkpLCBzY3JvbGxPZmZzZXQsIE5hdkJhckxpc3QpKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBnZXRUYXJnZXRzICgpIHtcbiAgICAgICAgbGV0IHRhcmdldHMgPSBbXTtcblxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHRoaXMubmF2QmFySXRlbXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIHRoaXMubmF2QmFySXRlbXNbaV0uZ2V0VGFyZ2V0cygpLmZvckVhY2goZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICAgICAgICAgIHRhcmdldHMucHVzaCh0YXJnZXQpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFyZ2V0cztcbiAgICB9O1xuXG4gICAgY29udGFpbnNUYXJnZXRJZCAodGFyZ2V0SWQpIHtcbiAgICAgICAgbGV0IGNvbnRhaW5zID0gZmFsc2U7XG5cbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5mb3JFYWNoKGZ1bmN0aW9uIChuYXZCYXJJdGVtKSB7XG4gICAgICAgICAgICBpZiAobmF2QmFySXRlbS5jb250YWluc1RhcmdldElkKHRhcmdldElkKSkge1xuICAgICAgICAgICAgICAgIGNvbnRhaW5zID0gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIGNvbnRhaW5zO1xuICAgIH07XG5cbiAgICBjbGVhckFjdGl2ZSAoKSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnbGknKSwgZnVuY3Rpb24gKGxpc3RJdGVtRWxlbWVudCkge1xuICAgICAgICAgICAgbGlzdEl0ZW1FbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgc2V0QWN0aXZlICh0YXJnZXRJZCkge1xuICAgICAgICB0aGlzLm5hdkJhckl0ZW1zLmZvckVhY2goZnVuY3Rpb24gKG5hdkJhckl0ZW0pIHtcbiAgICAgICAgICAgIGlmIChuYXZCYXJJdGVtLmNvbnRhaW5zVGFyZ2V0SWQodGFyZ2V0SWQpKSB7XG4gICAgICAgICAgICAgICAgbmF2QmFySXRlbS5zZXRBY3RpdmUodGFyZ2V0SWQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IE5hdkJhckxpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdXNlci1hY2NvdW50L25hdmJhci1saXN0LmpzIiwicmVxdWlyZSgnLi9uYXZiYXItbGlzdCcpO1xuXG5jbGFzcyBTY3JvbGxTcHkge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7TmF2QmFyTGlzdH0gbmF2QmFyTGlzdFxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBvZmZzZXRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAobmF2QmFyTGlzdCwgb2Zmc2V0KSB7XG4gICAgICAgIHRoaXMubmF2QmFyTGlzdCA9IG5hdkJhckxpc3Q7XG4gICAgICAgIHRoaXMub2Zmc2V0ID0gb2Zmc2V0O1xuICAgIH1cblxuICAgIHNjcm9sbEV2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICBsZXQgYWN0aXZlTGlua1RhcmdldCA9IG51bGw7XG4gICAgICAgIGxldCBsaW5rVGFyZ2V0cyA9IHRoaXMubmF2QmFyTGlzdC5nZXRUYXJnZXRzKCk7XG4gICAgICAgIGxldCBvZmZzZXQgPSB0aGlzLm9mZnNldDtcbiAgICAgICAgbGV0IGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZCA9IFtdO1xuXG4gICAgICAgIGxpbmtUYXJnZXRzLmZvckVhY2goZnVuY3Rpb24gKGxpbmtUYXJnZXQpIHtcbiAgICAgICAgICAgIGlmIChsaW5rVGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgbGV0IG9mZnNldFRvcCA9IGxpbmtUYXJnZXQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCkudG9wO1xuXG4gICAgICAgICAgICAgICAgaWYgKG9mZnNldFRvcCA8IG9mZnNldCkge1xuICAgICAgICAgICAgICAgICAgICBsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQucHVzaChsaW5rVGFyZ2V0KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGlmIChsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICBhY3RpdmVMaW5rVGFyZ2V0ID0gbGlua1RhcmdldHNbMF07XG4gICAgICAgIH0gZWxzZSBpZiAobGlua1RhcmdldHNQYXN0VGhyZXNob2xkLmxlbmd0aCA9PT0gbGlua1RhcmdldHMubGVuZ3RoKSB7XG4gICAgICAgICAgICBhY3RpdmVMaW5rVGFyZ2V0ID0gbGlua1RhcmdldHNbbGlua1RhcmdldHMubGVuZ3RoIC0gMV07XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBhY3RpdmVMaW5rVGFyZ2V0ID0gbGlua1RhcmdldHNQYXN0VGhyZXNob2xkW2xpbmtUYXJnZXRzUGFzdFRocmVzaG9sZC5sZW5ndGggLSAxXTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChhY3RpdmVMaW5rVGFyZ2V0KSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QuY2xlYXJBY3RpdmUoKTtcbiAgICAgICAgICAgIHRoaXMubmF2QmFyTGlzdC5zZXRBY3RpdmUoYWN0aXZlTGlua1RhcmdldC5nZXRBdHRyaWJ1dGUoJ2lkJykpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgc3B5ICgpIHtcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoXG4gICAgICAgICAgICAnc2Nyb2xsJyxcbiAgICAgICAgICAgIHRoaXMuc2Nyb2xsRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpLFxuICAgICAgICAgICAgdHJ1ZVxuICAgICAgICApO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTY3JvbGxTcHk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdXNlci1hY2NvdW50L3Njcm9sbHNweS5qcyIsIi8qKlxuICogU2ltcGxlLCBsaWdodHdlaWdodCwgdXNhYmxlIGxvY2FsIGF1dG9jb21wbGV0ZSBsaWJyYXJ5IGZvciBtb2Rlcm4gYnJvd3NlcnNcbiAqIEJlY2F1c2UgdGhlcmUgd2VyZW7igJl0IGVub3VnaCBhdXRvY29tcGxldGUgc2NyaXB0cyBpbiB0aGUgd29ybGQ/IEJlY2F1c2UgSeKAmW0gY29tcGxldGVseSBpbnNhbmUgYW5kIGhhdmUgTklIIHN5bmRyb21lPyBQcm9iYWJseSBib3RoLiA6UFxuICogQGF1dGhvciBMZWEgVmVyb3UgaHR0cDovL2xlYXZlcm91LmdpdGh1Yi5pby9hd2Vzb21wbGV0ZVxuICogTUlUIGxpY2Vuc2VcbiAqL1xuXG4oZnVuY3Rpb24gKCkge1xuXG52YXIgXyA9IGZ1bmN0aW9uIChpbnB1dCwgbykge1xuXHR2YXIgbWUgPSB0aGlzO1xuXG5cdC8vIFNldHVwXG5cblx0dGhpcy5pc09wZW5lZCA9IGZhbHNlO1xuXG5cdHRoaXMuaW5wdXQgPSAkKGlucHV0KTtcblx0dGhpcy5pbnB1dC5zZXRBdHRyaWJ1dGUoXCJhdXRvY29tcGxldGVcIiwgXCJvZmZcIik7XG5cdHRoaXMuaW5wdXQuc2V0QXR0cmlidXRlKFwiYXJpYS1hdXRvY29tcGxldGVcIiwgXCJsaXN0XCIpO1xuXG5cdG8gPSBvIHx8IHt9O1xuXG5cdGNvbmZpZ3VyZSh0aGlzLCB7XG5cdFx0bWluQ2hhcnM6IDIsXG5cdFx0bWF4SXRlbXM6IDEwLFxuXHRcdGF1dG9GaXJzdDogZmFsc2UsXG5cdFx0ZGF0YTogXy5EQVRBLFxuXHRcdGZpbHRlcjogXy5GSUxURVJfQ09OVEFJTlMsXG5cdFx0c29ydDogby5zb3J0ID09PSBmYWxzZSA/IGZhbHNlIDogXy5TT1JUX0JZTEVOR1RILFxuXHRcdGl0ZW06IF8uSVRFTSxcblx0XHRyZXBsYWNlOiBfLlJFUExBQ0Vcblx0fSwgbyk7XG5cblx0dGhpcy5pbmRleCA9IC0xO1xuXG5cdC8vIENyZWF0ZSBuZWNlc3NhcnkgZWxlbWVudHNcblxuXHR0aGlzLmNvbnRhaW5lciA9ICQuY3JlYXRlKFwiZGl2XCIsIHtcblx0XHRjbGFzc05hbWU6IFwiYXdlc29tcGxldGVcIixcblx0XHRhcm91bmQ6IGlucHV0XG5cdH0pO1xuXG5cdHRoaXMudWwgPSAkLmNyZWF0ZShcInVsXCIsIHtcblx0XHRoaWRkZW46IFwiaGlkZGVuXCIsXG5cdFx0aW5zaWRlOiB0aGlzLmNvbnRhaW5lclxuXHR9KTtcblxuXHR0aGlzLnN0YXR1cyA9ICQuY3JlYXRlKFwic3BhblwiLCB7XG5cdFx0Y2xhc3NOYW1lOiBcInZpc3VhbGx5LWhpZGRlblwiLFxuXHRcdHJvbGU6IFwic3RhdHVzXCIsXG5cdFx0XCJhcmlhLWxpdmVcIjogXCJhc3NlcnRpdmVcIixcblx0XHRcImFyaWEtcmVsZXZhbnRcIjogXCJhZGRpdGlvbnNcIixcblx0XHRpbnNpZGU6IHRoaXMuY29udGFpbmVyXG5cdH0pO1xuXG5cdC8vIEJpbmQgZXZlbnRzXG5cblx0dGhpcy5fZXZlbnRzID0ge1xuXHRcdGlucHV0OiB7XG5cdFx0XHRcImlucHV0XCI6IHRoaXMuZXZhbHVhdGUuYmluZCh0aGlzKSxcblx0XHRcdFwiYmx1clwiOiB0aGlzLmNsb3NlLmJpbmQodGhpcywgeyByZWFzb246IFwiYmx1clwiIH0pLFxuXHRcdFx0XCJrZXlkb3duXCI6IGZ1bmN0aW9uKGV2dCkge1xuXHRcdFx0XHR2YXIgYyA9IGV2dC5rZXlDb2RlO1xuXG5cdFx0XHRcdC8vIElmIHRoZSBkcm9wZG93biBgdWxgIGlzIGluIHZpZXcsIHRoZW4gYWN0IG9uIGtleWRvd24gZm9yIHRoZSBmb2xsb3dpbmcga2V5czpcblx0XHRcdFx0Ly8gRW50ZXIgLyBFc2MgLyBVcCAvIERvd25cblx0XHRcdFx0aWYobWUub3BlbmVkKSB7XG5cdFx0XHRcdFx0aWYgKGMgPT09IDEzICYmIG1lLnNlbGVjdGVkKSB7IC8vIEVudGVyXG5cdFx0XHRcdFx0XHRldnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHRcdG1lLnNlbGVjdCgpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRlbHNlIGlmIChjID09PSAyNykgeyAvLyBFc2Ncblx0XHRcdFx0XHRcdG1lLmNsb3NlKHsgcmVhc29uOiBcImVzY1wiIH0pO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRlbHNlIGlmIChjID09PSAzOCB8fCBjID09PSA0MCkgeyAvLyBEb3duL1VwIGFycm93XG5cdFx0XHRcdFx0XHRldnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHRcdG1lW2MgPT09IDM4PyBcInByZXZpb3VzXCIgOiBcIm5leHRcIl0oKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9LFxuXHRcdGZvcm06IHtcblx0XHRcdFwic3VibWl0XCI6IHRoaXMuY2xvc2UuYmluZCh0aGlzLCB7IHJlYXNvbjogXCJzdWJtaXRcIiB9KVxuXHRcdH0sXG5cdFx0dWw6IHtcblx0XHRcdFwibW91c2Vkb3duXCI6IGZ1bmN0aW9uKGV2dCkge1xuXHRcdFx0XHR2YXIgbGkgPSBldnQudGFyZ2V0O1xuXG5cdFx0XHRcdGlmIChsaSAhPT0gdGhpcykge1xuXG5cdFx0XHRcdFx0d2hpbGUgKGxpICYmICEvbGkvaS50ZXN0KGxpLm5vZGVOYW1lKSkge1xuXHRcdFx0XHRcdFx0bGkgPSBsaS5wYXJlbnROb2RlO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGlmIChsaSAmJiBldnQuYnV0dG9uID09PSAwKSB7ICAvLyBPbmx5IHNlbGVjdCBvbiBsZWZ0IGNsaWNrXG5cdFx0XHRcdFx0XHRldnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHRcdG1lLnNlbGVjdChsaSwgZXZ0LnRhcmdldCk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXHR9O1xuXG5cdCQuYmluZCh0aGlzLmlucHV0LCB0aGlzLl9ldmVudHMuaW5wdXQpO1xuXHQkLmJpbmQodGhpcy5pbnB1dC5mb3JtLCB0aGlzLl9ldmVudHMuZm9ybSk7XG5cdCQuYmluZCh0aGlzLnVsLCB0aGlzLl9ldmVudHMudWwpO1xuXG5cdGlmICh0aGlzLmlucHV0Lmhhc0F0dHJpYnV0ZShcImxpc3RcIikpIHtcblx0XHR0aGlzLmxpc3QgPSBcIiNcIiArIHRoaXMuaW5wdXQuZ2V0QXR0cmlidXRlKFwibGlzdFwiKTtcblx0XHR0aGlzLmlucHV0LnJlbW92ZUF0dHJpYnV0ZShcImxpc3RcIik7XG5cdH1cblx0ZWxzZSB7XG5cdFx0dGhpcy5saXN0ID0gdGhpcy5pbnB1dC5nZXRBdHRyaWJ1dGUoXCJkYXRhLWxpc3RcIikgfHwgby5saXN0IHx8IFtdO1xuXHR9XG5cblx0Xy5hbGwucHVzaCh0aGlzKTtcbn07XG5cbl8ucHJvdG90eXBlID0ge1xuXHRzZXQgbGlzdChsaXN0KSB7XG5cdFx0aWYgKEFycmF5LmlzQXJyYXkobGlzdCkpIHtcblx0XHRcdHRoaXMuX2xpc3QgPSBsaXN0O1xuXHRcdH1cblx0XHRlbHNlIGlmICh0eXBlb2YgbGlzdCA9PT0gXCJzdHJpbmdcIiAmJiBsaXN0LmluZGV4T2YoXCIsXCIpID4gLTEpIHtcblx0XHRcdFx0dGhpcy5fbGlzdCA9IGxpc3Quc3BsaXQoL1xccyosXFxzKi8pO1xuXHRcdH1cblx0XHRlbHNlIHsgLy8gRWxlbWVudCBvciBDU1Mgc2VsZWN0b3Jcblx0XHRcdGxpc3QgPSAkKGxpc3QpO1xuXG5cdFx0XHRpZiAobGlzdCAmJiBsaXN0LmNoaWxkcmVuKSB7XG5cdFx0XHRcdHZhciBpdGVtcyA9IFtdO1xuXHRcdFx0XHRzbGljZS5hcHBseShsaXN0LmNoaWxkcmVuKS5mb3JFYWNoKGZ1bmN0aW9uIChlbCkge1xuXHRcdFx0XHRcdGlmICghZWwuZGlzYWJsZWQpIHtcblx0XHRcdFx0XHRcdHZhciB0ZXh0ID0gZWwudGV4dENvbnRlbnQudHJpbSgpO1xuXHRcdFx0XHRcdFx0dmFyIHZhbHVlID0gZWwudmFsdWUgfHwgdGV4dDtcblx0XHRcdFx0XHRcdHZhciBsYWJlbCA9IGVsLmxhYmVsIHx8IHRleHQ7XG5cdFx0XHRcdFx0XHRpZiAodmFsdWUgIT09IFwiXCIpIHtcblx0XHRcdFx0XHRcdFx0aXRlbXMucHVzaCh7IGxhYmVsOiBsYWJlbCwgdmFsdWU6IHZhbHVlIH0pO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH1cblx0XHRcdFx0fSk7XG5cdFx0XHRcdHRoaXMuX2xpc3QgPSBpdGVtcztcblx0XHRcdH1cblx0XHR9XG5cblx0XHRpZiAoZG9jdW1lbnQuYWN0aXZlRWxlbWVudCA9PT0gdGhpcy5pbnB1dCkge1xuXHRcdFx0dGhpcy5ldmFsdWF0ZSgpO1xuXHRcdH1cblx0fSxcblxuXHRnZXQgc2VsZWN0ZWQoKSB7XG5cdFx0cmV0dXJuIHRoaXMuaW5kZXggPiAtMTtcblx0fSxcblxuXHRnZXQgb3BlbmVkKCkge1xuXHRcdHJldHVybiB0aGlzLmlzT3BlbmVkO1xuXHR9LFxuXG5cdGNsb3NlOiBmdW5jdGlvbiAobykge1xuXHRcdGlmICghdGhpcy5vcGVuZWQpIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHR0aGlzLnVsLnNldEF0dHJpYnV0ZShcImhpZGRlblwiLCBcIlwiKTtcblx0XHR0aGlzLmlzT3BlbmVkID0gZmFsc2U7XG5cdFx0dGhpcy5pbmRleCA9IC0xO1xuXG5cdFx0JC5maXJlKHRoaXMuaW5wdXQsIFwiYXdlc29tcGxldGUtY2xvc2VcIiwgbyB8fCB7fSk7XG5cdH0sXG5cblx0b3BlbjogZnVuY3Rpb24gKCkge1xuXHRcdHRoaXMudWwucmVtb3ZlQXR0cmlidXRlKFwiaGlkZGVuXCIpO1xuXHRcdHRoaXMuaXNPcGVuZWQgPSB0cnVlO1xuXG5cdFx0aWYgKHRoaXMuYXV0b0ZpcnN0ICYmIHRoaXMuaW5kZXggPT09IC0xKSB7XG5cdFx0XHR0aGlzLmdvdG8oMCk7XG5cdFx0fVxuXG5cdFx0JC5maXJlKHRoaXMuaW5wdXQsIFwiYXdlc29tcGxldGUtb3BlblwiKTtcblx0fSxcblxuXHRkZXN0cm95OiBmdW5jdGlvbigpIHtcblx0XHQvL3JlbW92ZSBldmVudHMgZnJvbSB0aGUgaW5wdXQgYW5kIGl0cyBmb3JtXG5cdFx0JC51bmJpbmQodGhpcy5pbnB1dCwgdGhpcy5fZXZlbnRzLmlucHV0KTtcblx0XHQkLnVuYmluZCh0aGlzLmlucHV0LmZvcm0sIHRoaXMuX2V2ZW50cy5mb3JtKTtcblxuXHRcdC8vbW92ZSB0aGUgaW5wdXQgb3V0IG9mIHRoZSBhd2Vzb21wbGV0ZSBjb250YWluZXIgYW5kIHJlbW92ZSB0aGUgY29udGFpbmVyIGFuZCBpdHMgY2hpbGRyZW5cblx0XHR2YXIgcGFyZW50Tm9kZSA9IHRoaXMuY29udGFpbmVyLnBhcmVudE5vZGU7XG5cblx0XHRwYXJlbnROb2RlLmluc2VydEJlZm9yZSh0aGlzLmlucHV0LCB0aGlzLmNvbnRhaW5lcik7XG5cdFx0cGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh0aGlzLmNvbnRhaW5lcik7XG5cblx0XHQvL3JlbW92ZSBhdXRvY29tcGxldGUgYW5kIGFyaWEtYXV0b2NvbXBsZXRlIGF0dHJpYnV0ZXNcblx0XHR0aGlzLmlucHV0LnJlbW92ZUF0dHJpYnV0ZShcImF1dG9jb21wbGV0ZVwiKTtcblx0XHR0aGlzLmlucHV0LnJlbW92ZUF0dHJpYnV0ZShcImFyaWEtYXV0b2NvbXBsZXRlXCIpO1xuXG5cdFx0Ly9yZW1vdmUgdGhpcyBhd2Vzb21lcGxldGUgaW5zdGFuY2UgZnJvbSB0aGUgZ2xvYmFsIGFycmF5IG9mIGluc3RhbmNlc1xuXHRcdHZhciBpbmRleE9mQXdlc29tcGxldGUgPSBfLmFsbC5pbmRleE9mKHRoaXMpO1xuXG5cdFx0aWYgKGluZGV4T2ZBd2Vzb21wbGV0ZSAhPT0gLTEpIHtcblx0XHRcdF8uYWxsLnNwbGljZShpbmRleE9mQXdlc29tcGxldGUsIDEpO1xuXHRcdH1cblx0fSxcblxuXHRuZXh0OiBmdW5jdGlvbiAoKSB7XG5cdFx0dmFyIGNvdW50ID0gdGhpcy51bC5jaGlsZHJlbi5sZW5ndGg7XG5cdFx0dGhpcy5nb3RvKHRoaXMuaW5kZXggPCBjb3VudCAtIDEgPyB0aGlzLmluZGV4ICsgMSA6IChjb3VudCA/IDAgOiAtMSkgKTtcblx0fSxcblxuXHRwcmV2aW91czogZnVuY3Rpb24gKCkge1xuXHRcdHZhciBjb3VudCA9IHRoaXMudWwuY2hpbGRyZW4ubGVuZ3RoO1xuXHRcdHZhciBwb3MgPSB0aGlzLmluZGV4IC0gMTtcblxuXHRcdHRoaXMuZ290byh0aGlzLnNlbGVjdGVkICYmIHBvcyAhPT0gLTEgPyBwb3MgOiBjb3VudCAtIDEpO1xuXHR9LFxuXG5cdC8vIFNob3VsZCBub3QgYmUgdXNlZCwgaGlnaGxpZ2h0cyBzcGVjaWZpYyBpdGVtIHdpdGhvdXQgYW55IGNoZWNrcyFcblx0Z290bzogZnVuY3Rpb24gKGkpIHtcblx0XHR2YXIgbGlzID0gdGhpcy51bC5jaGlsZHJlbjtcblxuXHRcdGlmICh0aGlzLnNlbGVjdGVkKSB7XG5cdFx0XHRsaXNbdGhpcy5pbmRleF0uc2V0QXR0cmlidXRlKFwiYXJpYS1zZWxlY3RlZFwiLCBcImZhbHNlXCIpO1xuXHRcdH1cblxuXHRcdHRoaXMuaW5kZXggPSBpO1xuXG5cdFx0aWYgKGkgPiAtMSAmJiBsaXMubGVuZ3RoID4gMCkge1xuXHRcdFx0bGlzW2ldLnNldEF0dHJpYnV0ZShcImFyaWEtc2VsZWN0ZWRcIiwgXCJ0cnVlXCIpO1xuXHRcdFx0dGhpcy5zdGF0dXMudGV4dENvbnRlbnQgPSBsaXNbaV0udGV4dENvbnRlbnQ7XG5cblx0XHRcdC8vIHNjcm9sbCB0byBoaWdobGlnaHRlZCBlbGVtZW50IGluIGNhc2UgcGFyZW50J3MgaGVpZ2h0IGlzIGZpeGVkXG5cdFx0XHR0aGlzLnVsLnNjcm9sbFRvcCA9IGxpc1tpXS5vZmZzZXRUb3AgLSB0aGlzLnVsLmNsaWVudEhlaWdodCArIGxpc1tpXS5jbGllbnRIZWlnaHQ7XG5cblx0XHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLWhpZ2hsaWdodFwiLCB7XG5cdFx0XHRcdHRleHQ6IHRoaXMuc3VnZ2VzdGlvbnNbdGhpcy5pbmRleF1cblx0XHRcdH0pO1xuXHRcdH1cblx0fSxcblxuXHRzZWxlY3Q6IGZ1bmN0aW9uIChzZWxlY3RlZCwgb3JpZ2luKSB7XG5cdFx0aWYgKHNlbGVjdGVkKSB7XG5cdFx0XHR0aGlzLmluZGV4ID0gJC5zaWJsaW5nSW5kZXgoc2VsZWN0ZWQpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRzZWxlY3RlZCA9IHRoaXMudWwuY2hpbGRyZW5bdGhpcy5pbmRleF07XG5cdFx0fVxuXG5cdFx0aWYgKHNlbGVjdGVkKSB7XG5cdFx0XHR2YXIgc3VnZ2VzdGlvbiA9IHRoaXMuc3VnZ2VzdGlvbnNbdGhpcy5pbmRleF07XG5cblx0XHRcdHZhciBhbGxvd2VkID0gJC5maXJlKHRoaXMuaW5wdXQsIFwiYXdlc29tcGxldGUtc2VsZWN0XCIsIHtcblx0XHRcdFx0dGV4dDogc3VnZ2VzdGlvbixcblx0XHRcdFx0b3JpZ2luOiBvcmlnaW4gfHwgc2VsZWN0ZWRcblx0XHRcdH0pO1xuXG5cdFx0XHRpZiAoYWxsb3dlZCkge1xuXHRcdFx0XHR0aGlzLnJlcGxhY2Uoc3VnZ2VzdGlvbik7XG5cdFx0XHRcdHRoaXMuY2xvc2UoeyByZWFzb246IFwic2VsZWN0XCIgfSk7XG5cdFx0XHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLXNlbGVjdGNvbXBsZXRlXCIsIHtcblx0XHRcdFx0XHR0ZXh0OiBzdWdnZXN0aW9uXG5cdFx0XHRcdH0pO1xuXHRcdFx0fVxuXHRcdH1cblx0fSxcblxuXHRldmFsdWF0ZTogZnVuY3Rpb24oKSB7XG5cdFx0dmFyIG1lID0gdGhpcztcblx0XHR2YXIgdmFsdWUgPSB0aGlzLmlucHV0LnZhbHVlO1xuXG5cdFx0aWYgKHZhbHVlLmxlbmd0aCA+PSB0aGlzLm1pbkNoYXJzICYmIHRoaXMuX2xpc3QubGVuZ3RoID4gMCkge1xuXHRcdFx0dGhpcy5pbmRleCA9IC0xO1xuXHRcdFx0Ly8gUG9wdWxhdGUgbGlzdCB3aXRoIG9wdGlvbnMgdGhhdCBtYXRjaFxuXHRcdFx0dGhpcy51bC5pbm5lckhUTUwgPSBcIlwiO1xuXG5cdFx0XHR0aGlzLnN1Z2dlc3Rpb25zID0gdGhpcy5fbGlzdFxuXHRcdFx0XHQubWFwKGZ1bmN0aW9uKGl0ZW0pIHtcblx0XHRcdFx0XHRyZXR1cm4gbmV3IFN1Z2dlc3Rpb24obWUuZGF0YShpdGVtLCB2YWx1ZSkpO1xuXHRcdFx0XHR9KVxuXHRcdFx0XHQuZmlsdGVyKGZ1bmN0aW9uKGl0ZW0pIHtcblx0XHRcdFx0XHRyZXR1cm4gbWUuZmlsdGVyKGl0ZW0sIHZhbHVlKTtcblx0XHRcdFx0fSk7XG5cblx0XHRcdGlmICh0aGlzLnNvcnQgIT09IGZhbHNlKSB7XG5cdFx0XHRcdHRoaXMuc3VnZ2VzdGlvbnMgPSB0aGlzLnN1Z2dlc3Rpb25zLnNvcnQodGhpcy5zb3J0KTtcblx0XHRcdH1cblxuXHRcdFx0dGhpcy5zdWdnZXN0aW9ucyA9IHRoaXMuc3VnZ2VzdGlvbnMuc2xpY2UoMCwgdGhpcy5tYXhJdGVtcyk7XG5cblx0XHRcdHRoaXMuc3VnZ2VzdGlvbnMuZm9yRWFjaChmdW5jdGlvbih0ZXh0KSB7XG5cdFx0XHRcdFx0bWUudWwuYXBwZW5kQ2hpbGQobWUuaXRlbSh0ZXh0LCB2YWx1ZSkpO1xuXHRcdFx0XHR9KTtcblxuXHRcdFx0aWYgKHRoaXMudWwuY2hpbGRyZW4ubGVuZ3RoID09PSAwKSB7XG5cdFx0XHRcdHRoaXMuY2xvc2UoeyByZWFzb246IFwibm9tYXRjaGVzXCIgfSk7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHR0aGlzLm9wZW4oKTtcblx0XHRcdH1cblx0XHR9XG5cdFx0ZWxzZSB7XG5cdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcIm5vbWF0Y2hlc1wiIH0pO1xuXHRcdH1cblx0fVxufTtcblxuLy8gU3RhdGljIG1ldGhvZHMvcHJvcGVydGllc1xuXG5fLmFsbCA9IFtdO1xuXG5fLkZJTFRFUl9DT05UQUlOUyA9IGZ1bmN0aW9uICh0ZXh0LCBpbnB1dCkge1xuXHRyZXR1cm4gUmVnRXhwKCQucmVnRXhwRXNjYXBlKGlucHV0LnRyaW0oKSksIFwiaVwiKS50ZXN0KHRleHQpO1xufTtcblxuXy5GSUxURVJfU1RBUlRTV0lUSCA9IGZ1bmN0aW9uICh0ZXh0LCBpbnB1dCkge1xuXHRyZXR1cm4gUmVnRXhwKFwiXlwiICsgJC5yZWdFeHBFc2NhcGUoaW5wdXQudHJpbSgpKSwgXCJpXCIpLnRlc3QodGV4dCk7XG59O1xuXG5fLlNPUlRfQllMRU5HVEggPSBmdW5jdGlvbiAoYSwgYikge1xuXHRpZiAoYS5sZW5ndGggIT09IGIubGVuZ3RoKSB7XG5cdFx0cmV0dXJuIGEubGVuZ3RoIC0gYi5sZW5ndGg7XG5cdH1cblxuXHRyZXR1cm4gYSA8IGI/IC0xIDogMTtcbn07XG5cbl8uSVRFTSA9IGZ1bmN0aW9uICh0ZXh0LCBpbnB1dCkge1xuXHR2YXIgaHRtbCA9IGlucHV0LnRyaW0oKSA9PT0gXCJcIiA/IHRleHQgOiB0ZXh0LnJlcGxhY2UoUmVnRXhwKCQucmVnRXhwRXNjYXBlKGlucHV0LnRyaW0oKSksIFwiZ2lcIiksIFwiPG1hcms+JCY8L21hcms+XCIpO1xuXHRyZXR1cm4gJC5jcmVhdGUoXCJsaVwiLCB7XG5cdFx0aW5uZXJIVE1MOiBodG1sLFxuXHRcdFwiYXJpYS1zZWxlY3RlZFwiOiBcImZhbHNlXCJcblx0fSk7XG59O1xuXG5fLlJFUExBQ0UgPSBmdW5jdGlvbiAodGV4dCkge1xuXHR0aGlzLmlucHV0LnZhbHVlID0gdGV4dC52YWx1ZTtcbn07XG5cbl8uREFUQSA9IGZ1bmN0aW9uIChpdGVtLyosIGlucHV0Ki8pIHsgcmV0dXJuIGl0ZW07IH07XG5cbi8vIFByaXZhdGUgZnVuY3Rpb25zXG5cbmZ1bmN0aW9uIFN1Z2dlc3Rpb24oZGF0YSkge1xuXHR2YXIgbyA9IEFycmF5LmlzQXJyYXkoZGF0YSlcblx0ICA/IHsgbGFiZWw6IGRhdGFbMF0sIHZhbHVlOiBkYXRhWzFdIH1cblx0ICA6IHR5cGVvZiBkYXRhID09PSBcIm9iamVjdFwiICYmIFwibGFiZWxcIiBpbiBkYXRhICYmIFwidmFsdWVcIiBpbiBkYXRhID8gZGF0YSA6IHsgbGFiZWw6IGRhdGEsIHZhbHVlOiBkYXRhIH07XG5cblx0dGhpcy5sYWJlbCA9IG8ubGFiZWwgfHwgby52YWx1ZTtcblx0dGhpcy52YWx1ZSA9IG8udmFsdWU7XG59XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoU3VnZ2VzdGlvbi5wcm90b3R5cGUgPSBPYmplY3QuY3JlYXRlKFN0cmluZy5wcm90b3R5cGUpLCBcImxlbmd0aFwiLCB7XG5cdGdldDogZnVuY3Rpb24oKSB7IHJldHVybiB0aGlzLmxhYmVsLmxlbmd0aDsgfVxufSk7XG5TdWdnZXN0aW9uLnByb3RvdHlwZS50b1N0cmluZyA9IFN1Z2dlc3Rpb24ucHJvdG90eXBlLnZhbHVlT2YgPSBmdW5jdGlvbiAoKSB7XG5cdHJldHVybiBcIlwiICsgdGhpcy5sYWJlbDtcbn07XG5cbmZ1bmN0aW9uIGNvbmZpZ3VyZShpbnN0YW5jZSwgcHJvcGVydGllcywgbykge1xuXHRmb3IgKHZhciBpIGluIHByb3BlcnRpZXMpIHtcblx0XHR2YXIgaW5pdGlhbCA9IHByb3BlcnRpZXNbaV0sXG5cdFx0ICAgIGF0dHJWYWx1ZSA9IGluc3RhbmNlLmlucHV0LmdldEF0dHJpYnV0ZShcImRhdGEtXCIgKyBpLnRvTG93ZXJDYXNlKCkpO1xuXG5cdFx0aWYgKHR5cGVvZiBpbml0aWFsID09PSBcIm51bWJlclwiKSB7XG5cdFx0XHRpbnN0YW5jZVtpXSA9IHBhcnNlSW50KGF0dHJWYWx1ZSk7XG5cdFx0fVxuXHRcdGVsc2UgaWYgKGluaXRpYWwgPT09IGZhbHNlKSB7IC8vIEJvb2xlYW4gb3B0aW9ucyBtdXN0IGJlIGZhbHNlIGJ5IGRlZmF1bHQgYW55d2F5XG5cdFx0XHRpbnN0YW5jZVtpXSA9IGF0dHJWYWx1ZSAhPT0gbnVsbDtcblx0XHR9XG5cdFx0ZWxzZSBpZiAoaW5pdGlhbCBpbnN0YW5jZW9mIEZ1bmN0aW9uKSB7XG5cdFx0XHRpbnN0YW5jZVtpXSA9IG51bGw7XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBhdHRyVmFsdWU7XG5cdFx0fVxuXG5cdFx0aWYgKCFpbnN0YW5jZVtpXSAmJiBpbnN0YW5jZVtpXSAhPT0gMCkge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSAoaSBpbiBvKT8gb1tpXSA6IGluaXRpYWw7XG5cdFx0fVxuXHR9XG59XG5cbi8vIEhlbHBlcnNcblxudmFyIHNsaWNlID0gQXJyYXkucHJvdG90eXBlLnNsaWNlO1xuXG5mdW5jdGlvbiAkKGV4cHIsIGNvbikge1xuXHRyZXR1cm4gdHlwZW9mIGV4cHIgPT09IFwic3RyaW5nXCI/IChjb24gfHwgZG9jdW1lbnQpLnF1ZXJ5U2VsZWN0b3IoZXhwcikgOiBleHByIHx8IG51bGw7XG59XG5cbmZ1bmN0aW9uICQkKGV4cHIsIGNvbikge1xuXHRyZXR1cm4gc2xpY2UuY2FsbCgoY29uIHx8IGRvY3VtZW50KS5xdWVyeVNlbGVjdG9yQWxsKGV4cHIpKTtcbn1cblxuJC5jcmVhdGUgPSBmdW5jdGlvbih0YWcsIG8pIHtcblx0dmFyIGVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KHRhZyk7XG5cblx0Zm9yICh2YXIgaSBpbiBvKSB7XG5cdFx0dmFyIHZhbCA9IG9baV07XG5cblx0XHRpZiAoaSA9PT0gXCJpbnNpZGVcIikge1xuXHRcdFx0JCh2YWwpLmFwcGVuZENoaWxkKGVsZW1lbnQpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpID09PSBcImFyb3VuZFwiKSB7XG5cdFx0XHR2YXIgcmVmID0gJCh2YWwpO1xuXHRcdFx0cmVmLnBhcmVudE5vZGUuaW5zZXJ0QmVmb3JlKGVsZW1lbnQsIHJlZik7XG5cdFx0XHRlbGVtZW50LmFwcGVuZENoaWxkKHJlZik7XG5cdFx0fVxuXHRcdGVsc2UgaWYgKGkgaW4gZWxlbWVudCkge1xuXHRcdFx0ZWxlbWVudFtpXSA9IHZhbDtcblx0XHR9XG5cdFx0ZWxzZSB7XG5cdFx0XHRlbGVtZW50LnNldEF0dHJpYnV0ZShpLCB2YWwpO1xuXHRcdH1cblx0fVxuXG5cdHJldHVybiBlbGVtZW50O1xufTtcblxuJC5iaW5kID0gZnVuY3Rpb24oZWxlbWVudCwgbykge1xuXHRpZiAoZWxlbWVudCkge1xuXHRcdGZvciAodmFyIGV2ZW50IGluIG8pIHtcblx0XHRcdHZhciBjYWxsYmFjayA9IG9bZXZlbnRdO1xuXG5cdFx0XHRldmVudC5zcGxpdCgvXFxzKy8pLmZvckVhY2goZnVuY3Rpb24gKGV2ZW50KSB7XG5cdFx0XHRcdGVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihldmVudCwgY2FsbGJhY2spO1xuXHRcdFx0fSk7XG5cdFx0fVxuXHR9XG59O1xuXG4kLnVuYmluZCA9IGZ1bmN0aW9uKGVsZW1lbnQsIG8pIHtcblx0aWYgKGVsZW1lbnQpIHtcblx0XHRmb3IgKHZhciBldmVudCBpbiBvKSB7XG5cdFx0XHR2YXIgY2FsbGJhY2sgPSBvW2V2ZW50XTtcblxuXHRcdFx0ZXZlbnQuc3BsaXQoL1xccysvKS5mb3JFYWNoKGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0XHRcdGVsZW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihldmVudCwgY2FsbGJhY2spO1xuXHRcdFx0fSk7XG5cdFx0fVxuXHR9XG59O1xuXG4kLmZpcmUgPSBmdW5jdGlvbih0YXJnZXQsIHR5cGUsIHByb3BlcnRpZXMpIHtcblx0dmFyIGV2dCA9IGRvY3VtZW50LmNyZWF0ZUV2ZW50KFwiSFRNTEV2ZW50c1wiKTtcblxuXHRldnQuaW5pdEV2ZW50KHR5cGUsIHRydWUsIHRydWUgKTtcblxuXHRmb3IgKHZhciBqIGluIHByb3BlcnRpZXMpIHtcblx0XHRldnRbal0gPSBwcm9wZXJ0aWVzW2pdO1xuXHR9XG5cblx0cmV0dXJuIHRhcmdldC5kaXNwYXRjaEV2ZW50KGV2dCk7XG59O1xuXG4kLnJlZ0V4cEVzY2FwZSA9IGZ1bmN0aW9uIChzKSB7XG5cdHJldHVybiBzLnJlcGxhY2UoL1stXFxcXF4kKis/LigpfFtcXF17fV0vZywgXCJcXFxcJCZcIik7XG59O1xuXG4kLnNpYmxpbmdJbmRleCA9IGZ1bmN0aW9uIChlbCkge1xuXHQvKiBlc2xpbnQtZGlzYWJsZSBuby1jb25kLWFzc2lnbiAqL1xuXHRmb3IgKHZhciBpID0gMDsgZWwgPSBlbC5wcmV2aW91c0VsZW1lbnRTaWJsaW5nOyBpKyspO1xuXHRyZXR1cm4gaTtcbn07XG5cbi8vIEluaXRpYWxpemF0aW9uXG5cbmZ1bmN0aW9uIGluaXQoKSB7XG5cdCQkKFwiaW5wdXQuYXdlc29tcGxldGVcIikuZm9yRWFjaChmdW5jdGlvbiAoaW5wdXQpIHtcblx0XHRuZXcgXyhpbnB1dCk7XG5cdH0pO1xufVxuXG4vLyBBcmUgd2UgaW4gYSBicm93c2VyPyBDaGVjayBmb3IgRG9jdW1lbnQgY29uc3RydWN0b3JcbmlmICh0eXBlb2YgRG9jdW1lbnQgIT09IFwidW5kZWZpbmVkXCIpIHtcblx0Ly8gRE9NIGFscmVhZHkgbG9hZGVkP1xuXHRpZiAoZG9jdW1lbnQucmVhZHlTdGF0ZSAhPT0gXCJsb2FkaW5nXCIpIHtcblx0XHRpbml0KCk7XG5cdH1cblx0ZWxzZSB7XG5cdFx0Ly8gV2FpdCBmb3IgaXRcblx0XHRkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiRE9NQ29udGVudExvYWRlZFwiLCBpbml0KTtcblx0fVxufVxuXG5fLiQgPSAkO1xuXy4kJCA9ICQkO1xuXG4vLyBNYWtlIHN1cmUgdG8gZXhwb3J0IEF3ZXNvbXBsZXRlIG9uIHNlbGYgd2hlbiBpbiBhIGJyb3dzZXJcbmlmICh0eXBlb2Ygc2VsZiAhPT0gXCJ1bmRlZmluZWRcIikge1xuXHRzZWxmLkF3ZXNvbXBsZXRlID0gXztcbn1cblxuLy8gRXhwb3NlIEF3ZXNvbXBsZXRlIGFzIGEgQ0pTIG1vZHVsZVxuaWYgKHR5cGVvZiBtb2R1bGUgPT09IFwib2JqZWN0XCIgJiYgbW9kdWxlLmV4cG9ydHMpIHtcblx0bW9kdWxlLmV4cG9ydHMgPSBfO1xufVxuXG5yZXR1cm4gXztcblxufSgpKTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL2F3ZXNvbXBsZXRlL2F3ZXNvbXBsZXRlLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9hd2Vzb21wbGV0ZS9hd2Vzb21wbGV0ZS5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvLyBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIDMgdjIuMC4yMyB8IMKpIGRucF90aGVtZSB8IE1JVC1MaWNlbnNlXG4oZnVuY3Rpb24gKHJvb3QsIGZhY3RvcnkpIHtcbiAgaWYgKHR5cGVvZiBkZWZpbmUgPT09ICdmdW5jdGlvbicgJiYgZGVmaW5lLmFtZCkge1xuICAgIC8vIEFNRCBzdXBwb3J0OlxuICAgIGRlZmluZShbXSwgZmFjdG9yeSk7XG4gIH0gZWxzZSBpZiAodHlwZW9mIG1vZHVsZSA9PT0gJ29iamVjdCcgJiYgbW9kdWxlLmV4cG9ydHMpIHtcbiAgICAvLyBDb21tb25KUy1saWtlOlxuICAgIG1vZHVsZS5leHBvcnRzID0gZmFjdG9yeSgpO1xuICB9IGVsc2Uge1xuICAgIC8vIEJyb3dzZXIgZ2xvYmFscyAocm9vdCBpcyB3aW5kb3cpXG4gICAgdmFyIGJzbiA9IGZhY3RvcnkoKTtcbiAgICByb290Lk1vZGFsID0gYnNuLk1vZGFsO1xuICAgIHJvb3QuQ29sbGFwc2UgPSBic24uQ29sbGFwc2U7XG4gICAgcm9vdC5BbGVydCA9IGJzbi5BbGVydDtcbiAgfVxufSh0aGlzLCBmdW5jdGlvbiAoKSB7XG4gIFxuICAvKiBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIDMgfCBJbnRlcm5hbCBVdGlsaXR5IEZ1bmN0aW9uc1xuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tKi9cbiAgXCJ1c2Ugc3RyaWN0XCI7XG4gIFxuICAvLyBnbG9iYWxzXG4gIHZhciBnbG9iYWxPYmplY3QgPSB0eXBlb2YgZ2xvYmFsICE9PSAndW5kZWZpbmVkJyA/IGdsb2JhbCA6IHRoaXN8fHdpbmRvdyxcbiAgICBET0MgPSBkb2N1bWVudCwgSFRNTCA9IERPQy5kb2N1bWVudEVsZW1lbnQsIGJvZHkgPSAnYm9keScsIC8vIGFsbG93IHRoZSBsaWJyYXJ5IHRvIGJlIHVzZWQgaW4gPGhlYWQ+XG4gIFxuICAgIC8vIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgR2xvYmFsIE9iamVjdFxuICAgIEJTTiA9IGdsb2JhbE9iamVjdC5CU04gPSB7fSxcbiAgICBzdXBwb3J0cyA9IEJTTi5zdXBwb3J0cyA9IFtdLFxuICBcbiAgICAvLyBmdW5jdGlvbiB0b2dnbGUgYXR0cmlidXRlc1xuICAgIGRhdGFUb2dnbGUgICAgPSAnZGF0YS10b2dnbGUnLFxuICAgIGRhdGFEaXNtaXNzICAgPSAnZGF0YS1kaXNtaXNzJyxcbiAgICBkYXRhU3B5ICAgICAgID0gJ2RhdGEtc3B5JyxcbiAgICBkYXRhUmlkZSAgICAgID0gJ2RhdGEtcmlkZScsXG4gICAgXG4gICAgLy8gY29tcG9uZW50c1xuICAgIHN0cmluZ0FmZml4ICAgICA9ICdBZmZpeCcsXG4gICAgc3RyaW5nQWxlcnQgICAgID0gJ0FsZXJ0JyxcbiAgICBzdHJpbmdCdXR0b24gICAgPSAnQnV0dG9uJyxcbiAgICBzdHJpbmdDYXJvdXNlbCAgPSAnQ2Fyb3VzZWwnLFxuICAgIHN0cmluZ0NvbGxhcHNlICA9ICdDb2xsYXBzZScsXG4gICAgc3RyaW5nRHJvcGRvd24gID0gJ0Ryb3Bkb3duJyxcbiAgICBzdHJpbmdNb2RhbCAgICAgPSAnTW9kYWwnLFxuICAgIHN0cmluZ1BvcG92ZXIgICA9ICdQb3BvdmVyJyxcbiAgICBzdHJpbmdTY3JvbGxTcHkgPSAnU2Nyb2xsU3B5JyxcbiAgICBzdHJpbmdUYWIgICAgICAgPSAnVGFiJyxcbiAgICBzdHJpbmdUb29sdGlwICAgPSAnVG9vbHRpcCcsXG4gIFxuICAgIC8vIG9wdGlvbnMgREFUQSBBUElcbiAgICBkYXRhYmFja2Ryb3AgICAgICA9ICdkYXRhLWJhY2tkcm9wJyxcbiAgICBkYXRhS2V5Ym9hcmQgICAgICA9ICdkYXRhLWtleWJvYXJkJyxcbiAgICBkYXRhVGFyZ2V0ICAgICAgICA9ICdkYXRhLXRhcmdldCcsXG4gICAgZGF0YUludGVydmFsICAgICAgPSAnZGF0YS1pbnRlcnZhbCcsXG4gICAgZGF0YUhlaWdodCAgICAgICAgPSAnZGF0YS1oZWlnaHQnLFxuICAgIGRhdGFQYXVzZSAgICAgICAgID0gJ2RhdGEtcGF1c2UnLFxuICAgIGRhdGFUaXRsZSAgICAgICAgID0gJ2RhdGEtdGl0bGUnLCAgXG4gICAgZGF0YU9yaWdpbmFsVGl0bGUgPSAnZGF0YS1vcmlnaW5hbC10aXRsZScsXG4gICAgZGF0YU9yaWdpbmFsVGV4dCAgPSAnZGF0YS1vcmlnaW5hbC10ZXh0JyxcbiAgICBkYXRhRGlzbWlzc2libGUgICA9ICdkYXRhLWRpc21pc3NpYmxlJyxcbiAgICBkYXRhVHJpZ2dlciAgICAgICA9ICdkYXRhLXRyaWdnZXInLFxuICAgIGRhdGFBbmltYXRpb24gICAgID0gJ2RhdGEtYW5pbWF0aW9uJyxcbiAgICBkYXRhQ29udGFpbmVyICAgICA9ICdkYXRhLWNvbnRhaW5lcicsXG4gICAgZGF0YVBsYWNlbWVudCAgICAgPSAnZGF0YS1wbGFjZW1lbnQnLFxuICAgIGRhdGFEZWxheSAgICAgICAgID0gJ2RhdGEtZGVsYXknLFxuICAgIGRhdGFPZmZzZXRUb3AgICAgID0gJ2RhdGEtb2Zmc2V0LXRvcCcsXG4gICAgZGF0YU9mZnNldEJvdHRvbSAgPSAnZGF0YS1vZmZzZXQtYm90dG9tJyxcbiAgXG4gICAgLy8gb3B0aW9uIGtleXNcbiAgICBiYWNrZHJvcCA9ICdiYWNrZHJvcCcsIGtleWJvYXJkID0gJ2tleWJvYXJkJywgZGVsYXkgPSAnZGVsYXknLFxuICAgIGNvbnRlbnQgPSAnY29udGVudCcsIHRhcmdldCA9ICd0YXJnZXQnLCBcbiAgICBpbnRlcnZhbCA9ICdpbnRlcnZhbCcsIHBhdXNlID0gJ3BhdXNlJywgYW5pbWF0aW9uID0gJ2FuaW1hdGlvbicsXG4gICAgcGxhY2VtZW50ID0gJ3BsYWNlbWVudCcsIGNvbnRhaW5lciA9ICdjb250YWluZXInLCBcbiAgXG4gICAgLy8gYm94IG1vZGVsXG4gICAgb2Zmc2V0VG9wICAgID0gJ29mZnNldFRvcCcsICAgICAgb2Zmc2V0Qm90dG9tICAgPSAnb2Zmc2V0Qm90dG9tJyxcbiAgICBvZmZzZXRMZWZ0ICAgPSAnb2Zmc2V0TGVmdCcsXG4gICAgc2Nyb2xsVG9wICAgID0gJ3Njcm9sbFRvcCcsICAgICAgc2Nyb2xsTGVmdCAgICAgPSAnc2Nyb2xsTGVmdCcsXG4gICAgY2xpZW50V2lkdGggID0gJ2NsaWVudFdpZHRoJywgICAgY2xpZW50SGVpZ2h0ICAgPSAnY2xpZW50SGVpZ2h0JyxcbiAgICBvZmZzZXRXaWR0aCAgPSAnb2Zmc2V0V2lkdGgnLCAgICBvZmZzZXRIZWlnaHQgICA9ICdvZmZzZXRIZWlnaHQnLFxuICAgIGlubmVyV2lkdGggICA9ICdpbm5lcldpZHRoJywgICAgIGlubmVySGVpZ2h0ICAgID0gJ2lubmVySGVpZ2h0JyxcbiAgICBzY3JvbGxIZWlnaHQgPSAnc2Nyb2xsSGVpZ2h0JywgICBoZWlnaHQgICAgICAgICA9ICdoZWlnaHQnLFxuICBcbiAgICAvLyBhcmlhXG4gICAgYXJpYUV4cGFuZGVkID0gJ2FyaWEtZXhwYW5kZWQnLFxuICAgIGFyaWFIaWRkZW4gICA9ICdhcmlhLWhpZGRlbicsXG4gIFxuICAgIC8vIGV2ZW50IG5hbWVzXG4gICAgY2xpY2tFdmVudCAgICA9ICdjbGljaycsXG4gICAgaG92ZXJFdmVudCAgICA9ICdob3ZlcicsXG4gICAga2V5ZG93bkV2ZW50ICA9ICdrZXlkb3duJyxcbiAgICBrZXl1cEV2ZW50ICAgID0gJ2tleXVwJywgIFxuICAgIHJlc2l6ZUV2ZW50ICAgPSAncmVzaXplJyxcbiAgICBzY3JvbGxFdmVudCAgID0gJ3Njcm9sbCcsXG4gICAgLy8gb3JpZ2luYWxFdmVudHNcbiAgICBzaG93RXZlbnQgICAgID0gJ3Nob3cnLFxuICAgIHNob3duRXZlbnQgICAgPSAnc2hvd24nLFxuICAgIGhpZGVFdmVudCAgICAgPSAnaGlkZScsXG4gICAgaGlkZGVuRXZlbnQgICA9ICdoaWRkZW4nLFxuICAgIGNsb3NlRXZlbnQgICAgPSAnY2xvc2UnLFxuICAgIGNsb3NlZEV2ZW50ICAgPSAnY2xvc2VkJyxcbiAgICBzbGlkRXZlbnQgICAgID0gJ3NsaWQnLFxuICAgIHNsaWRlRXZlbnQgICAgPSAnc2xpZGUnLFxuICAgIGNoYW5nZUV2ZW50ICAgPSAnY2hhbmdlJyxcbiAgXG4gICAgLy8gb3RoZXJcbiAgICBnZXRBdHRyaWJ1dGUgICAgICAgICAgID0gJ2dldEF0dHJpYnV0ZScsXG4gICAgc2V0QXR0cmlidXRlICAgICAgICAgICA9ICdzZXRBdHRyaWJ1dGUnLFxuICAgIGhhc0F0dHJpYnV0ZSAgICAgICAgICAgPSAnaGFzQXR0cmlidXRlJyxcbiAgICBjcmVhdGVFbGVtZW50ICAgICAgICAgID0gJ2NyZWF0ZUVsZW1lbnQnLFxuICAgIGFwcGVuZENoaWxkICAgICAgICAgICAgPSAnYXBwZW5kQ2hpbGQnLFxuICAgIGlubmVySFRNTCAgICAgICAgICAgICAgPSAnaW5uZXJIVE1MJyxcbiAgICBnZXRFbGVtZW50c0J5VGFnTmFtZSAgID0gJ2dldEVsZW1lbnRzQnlUYWdOYW1lJyxcbiAgICBwcmV2ZW50RGVmYXVsdCAgICAgICAgID0gJ3ByZXZlbnREZWZhdWx0JyxcbiAgICBnZXRCb3VuZGluZ0NsaWVudFJlY3QgID0gJ2dldEJvdW5kaW5nQ2xpZW50UmVjdCcsXG4gICAgcXVlcnlTZWxlY3RvckFsbCAgICAgICA9ICdxdWVyeVNlbGVjdG9yQWxsJyxcbiAgICBnZXRFbGVtZW50c0J5Q0xBU1NOQU1FID0gJ2dldEVsZW1lbnRzQnlDbGFzc05hbWUnLFxuICAgIGdldENvbXB1dGVkU3R5bGUgICAgICAgPSAnZ2V0Q29tcHV0ZWRTdHlsZScsICBcbiAgXG4gICAgaW5kZXhPZiAgICAgID0gJ2luZGV4T2YnLFxuICAgIHBhcmVudE5vZGUgICA9ICdwYXJlbnROb2RlJyxcbiAgICBsZW5ndGggICAgICAgPSAnbGVuZ3RoJyxcbiAgICB0b0xvd2VyQ2FzZSAgPSAndG9Mb3dlckNhc2UnLFxuICAgIFRyYW5zaXRpb24gICA9ICdUcmFuc2l0aW9uJyxcbiAgICBEdXJhdGlvbiAgICAgPSAnRHVyYXRpb24nLCAgXG4gICAgV2Via2l0ICAgICAgID0gJ1dlYmtpdCcsXG4gICAgc3R5bGUgICAgICAgID0gJ3N0eWxlJyxcbiAgICBwdXNoICAgICAgICAgPSAncHVzaCcsXG4gICAgdGFiaW5kZXggICAgID0gJ3RhYmluZGV4JyxcbiAgICBjb250YWlucyAgICAgPSAnY29udGFpbnMnLCAgXG4gICAgXG4gICAgYWN0aXZlICAgICA9ICdhY3RpdmUnLFxuICAgIGluQ2xhc3MgICAgPSAnaW4nLFxuICAgIGNvbGxhcHNpbmcgPSAnY29sbGFwc2luZycsXG4gICAgZGlzYWJsZWQgICA9ICdkaXNhYmxlZCcsXG4gICAgbG9hZGluZyAgICA9ICdsb2FkaW5nJyxcbiAgICBsZWZ0ICAgICAgID0gJ2xlZnQnLFxuICAgIHJpZ2h0ICAgICAgPSAncmlnaHQnLFxuICAgIHRvcCAgICAgICAgPSAndG9wJyxcbiAgICBib3R0b20gICAgID0gJ2JvdHRvbScsXG4gIFxuICAgIC8vIElFOCBicm93c2VyIGRldGVjdFxuICAgIGlzSUU4ID0gISgnb3BhY2l0eScgaW4gSFRNTFtzdHlsZV0pLFxuICBcbiAgICAvLyB0b29sdGlwIC8gcG9wb3ZlclxuICAgIG1vdXNlSG92ZXIgPSAoJ29ubW91c2VsZWF2ZScgaW4gRE9DKSA/IFsgJ21vdXNlZW50ZXInLCAnbW91c2VsZWF2ZSddIDogWyAnbW91c2VvdmVyJywgJ21vdXNlb3V0JyBdLFxuICAgIHRpcFBvc2l0aW9ucyA9IC9cXGIodG9wfGJvdHRvbXxsZWZ0fHJpZ2h0KSsvLFxuICAgIFxuICAgIC8vIG1vZGFsXG4gICAgbW9kYWxPdmVybGF5ID0gMCxcbiAgICBmaXhlZFRvcCA9ICduYXZiYXItZml4ZWQtdG9wJyxcbiAgICBmaXhlZEJvdHRvbSA9ICduYXZiYXItZml4ZWQtYm90dG9tJywgIFxuICAgIFxuICAgIC8vIHRyYW5zaXRpb25FbmQgc2luY2UgMi4wLjRcbiAgICBzdXBwb3J0VHJhbnNpdGlvbnMgPSBXZWJraXQrVHJhbnNpdGlvbiBpbiBIVE1MW3N0eWxlXSB8fCBUcmFuc2l0aW9uW3RvTG93ZXJDYXNlXSgpIGluIEhUTUxbc3R5bGVdLFxuICAgIHRyYW5zaXRpb25FbmRFdmVudCA9IFdlYmtpdCtUcmFuc2l0aW9uIGluIEhUTUxbc3R5bGVdID8gV2Via2l0W3RvTG93ZXJDYXNlXSgpK1RyYW5zaXRpb24rJ0VuZCcgOiBUcmFuc2l0aW9uW3RvTG93ZXJDYXNlXSgpKydlbmQnLFxuICAgIHRyYW5zaXRpb25EdXJhdGlvbiA9IFdlYmtpdCtEdXJhdGlvbiBpbiBIVE1MW3N0eWxlXSA/IFdlYmtpdFt0b0xvd2VyQ2FzZV0oKStUcmFuc2l0aW9uK0R1cmF0aW9uIDogVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKStEdXJhdGlvbixcbiAgXG4gICAgLy8gc2V0IG5ldyBmb2N1cyBlbGVtZW50IHNpbmNlIDIuMC4zXG4gICAgc2V0Rm9jdXMgPSBmdW5jdGlvbihlbGVtZW50KXtcbiAgICAgIGVsZW1lbnQuZm9jdXMgPyBlbGVtZW50LmZvY3VzKCkgOiBlbGVtZW50LnNldEFjdGl2ZSgpO1xuICAgIH0sXG4gIFxuICAgIC8vIGNsYXNzIG1hbmlwdWxhdGlvbiwgc2luY2UgMi4wLjAgcmVxdWlyZXMgcG9seWZpbGwuanNcbiAgICBhZGRDbGFzcyA9IGZ1bmN0aW9uKGVsZW1lbnQsY2xhc3NOQU1FKSB7XG4gICAgICBlbGVtZW50LmNsYXNzTGlzdC5hZGQoY2xhc3NOQU1FKTtcbiAgICB9LFxuICAgIHJlbW92ZUNsYXNzID0gZnVuY3Rpb24oZWxlbWVudCxjbGFzc05BTUUpIHtcbiAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShjbGFzc05BTUUpO1xuICAgIH0sXG4gICAgaGFzQ2xhc3MgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSl7IC8vIHNpbmNlIDIuMC4wXG4gICAgICByZXR1cm4gZWxlbWVudC5jbGFzc0xpc3RbY29udGFpbnNdKGNsYXNzTkFNRSk7XG4gICAgfSxcbiAgXG4gICAgLy8gc2VsZWN0aW9uIG1ldGhvZHNcbiAgICBub2RlTGlzdFRvQXJyYXkgPSBmdW5jdGlvbihub2RlTGlzdCl7XG4gICAgICB2YXIgY2hpbGRJdGVtcyA9IFtdOyBmb3IgKHZhciBpID0gMCwgbmxsID0gbm9kZUxpc3RbbGVuZ3RoXTsgaTxubGw7IGkrKykgeyBjaGlsZEl0ZW1zW3B1c2hdKCBub2RlTGlzdFtpXSApIH1cbiAgICAgIHJldHVybiBjaGlsZEl0ZW1zO1xuICAgIH0sXG4gICAgZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSA9IGZ1bmN0aW9uKGVsZW1lbnQsY2xhc3NOQU1FKSB7IC8vIGdldEVsZW1lbnRzQnlDbGFzc05hbWUgSUU4K1xuICAgICAgdmFyIHNlbGVjdGlvbk1ldGhvZCA9IGlzSUU4ID8gcXVlcnlTZWxlY3RvckFsbCA6IGdldEVsZW1lbnRzQnlDTEFTU05BTUU7ICAgICAgXG4gICAgICByZXR1cm4gbm9kZUxpc3RUb0FycmF5KGVsZW1lbnRbc2VsZWN0aW9uTWV0aG9kXSggaXNJRTggPyAnLicgKyBjbGFzc05BTUUucmVwbGFjZSgvXFxzKD89W2Etel0pL2csJy4nKSA6IGNsYXNzTkFNRSApKTtcbiAgICB9LFxuICAgIHF1ZXJ5RWxlbWVudCA9IGZ1bmN0aW9uIChzZWxlY3RvciwgcGFyZW50KSB7XG4gICAgICB2YXIgbG9va1VwID0gcGFyZW50ID8gcGFyZW50IDogRE9DO1xuICAgICAgcmV0dXJuIHR5cGVvZiBzZWxlY3RvciA9PT0gJ29iamVjdCcgPyBzZWxlY3RvciA6IGxvb2tVcC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKTtcbiAgICB9LFxuICAgIGdldENsb3Nlc3QgPSBmdW5jdGlvbiAoZWxlbWVudCwgc2VsZWN0b3IpIHsgLy9lbGVtZW50IGlzIHRoZSBlbGVtZW50IGFuZCBzZWxlY3RvciBpcyBmb3IgdGhlIGNsb3Nlc3QgcGFyZW50IGVsZW1lbnQgdG8gZmluZFxuICAgICAgLy8gc291cmNlIGh0dHA6Ly9nb21ha2V0aGluZ3MuY29tL2NsaW1iaW5nLXVwLWFuZC1kb3duLXRoZS1kb20tdHJlZS13aXRoLXZhbmlsbGEtamF2YXNjcmlwdC9cbiAgICAgIHZhciBmaXJzdENoYXIgPSBzZWxlY3Rvci5jaGFyQXQoMCksIHNlbGVjdG9yU3Vic3RyaW5nID0gc2VsZWN0b3Iuc3Vic3RyKDEpO1xuICAgICAgaWYgKCBmaXJzdENoYXIgPT09ICcuJyApIHsvLyBJZiBzZWxlY3RvciBpcyBhIGNsYXNzXG4gICAgICAgIGZvciAoIDsgZWxlbWVudCAmJiBlbGVtZW50ICE9PSBET0M7IGVsZW1lbnQgPSBlbGVtZW50W3BhcmVudE5vZGVdICkgeyAvLyBHZXQgY2xvc2VzdCBtYXRjaFxuICAgICAgICAgIGlmICggcXVlcnlFbGVtZW50KHNlbGVjdG9yLGVsZW1lbnRbcGFyZW50Tm9kZV0pICE9PSBudWxsICYmIGhhc0NsYXNzKGVsZW1lbnQsc2VsZWN0b3JTdWJzdHJpbmcpICkgeyByZXR1cm4gZWxlbWVudDsgfVxuICAgICAgICB9XG4gICAgICB9IGVsc2UgaWYgKCBmaXJzdENoYXIgPT09ICcjJyApIHsgLy8gSWYgc2VsZWN0b3IgaXMgYW4gSURcbiAgICAgICAgZm9yICggOyBlbGVtZW50ICYmIGVsZW1lbnQgIT09IERPQzsgZWxlbWVudCA9IGVsZW1lbnRbcGFyZW50Tm9kZV0gKSB7IC8vIEdldCBjbG9zZXN0IG1hdGNoXG4gICAgICAgICAgaWYgKCBlbGVtZW50LmlkID09PSBzZWxlY3RvclN1YnN0cmluZyApIHsgcmV0dXJuIGVsZW1lbnQ7IH1cbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gIFxuICAgIC8vIGV2ZW50IGF0dGFjaCBqUXVlcnkgc3R5bGUgLyB0cmlnZ2VyICBzaW5jZSAxLjIuMFxuICAgIG9uID0gZnVuY3Rpb24gKGVsZW1lbnQsIGV2ZW50LCBoYW5kbGVyKSB7XG4gICAgICBlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoZXZlbnQsIGhhbmRsZXIsIGZhbHNlKTtcbiAgICB9LFxuICAgIG9mZiA9IGZ1bmN0aW9uKGVsZW1lbnQsIGV2ZW50LCBoYW5kbGVyKSB7XG4gICAgICBlbGVtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoZXZlbnQsIGhhbmRsZXIsIGZhbHNlKTtcbiAgICB9LFxuICAgIG9uZSA9IGZ1bmN0aW9uIChlbGVtZW50LCBldmVudCwgaGFuZGxlcikgeyAvLyBvbmUgc2luY2UgMi4wLjRcbiAgICAgIG9uKGVsZW1lbnQsIGV2ZW50LCBmdW5jdGlvbiBoYW5kbGVyV3JhcHBlcihlKXtcbiAgICAgICAgaGFuZGxlcihlKTtcbiAgICAgICAgb2ZmKGVsZW1lbnQsIGV2ZW50LCBoYW5kbGVyV3JhcHBlcik7XG4gICAgICB9KTtcbiAgICB9LFxuICAgIGdldFRyYW5zaXRpb25EdXJhdGlvbkZyb21FbGVtZW50ID0gZnVuY3Rpb24oZWxlbWVudCkge1xuICAgICAgdmFyIGR1cmF0aW9uID0gZ2xvYmFsT2JqZWN0W2dldENvbXB1dGVkU3R5bGVdKGVsZW1lbnQpW3RyYW5zaXRpb25EdXJhdGlvbl07XG4gICAgICBkdXJhdGlvbiA9IHBhcnNlRmxvYXQoZHVyYXRpb24pO1xuICAgICAgZHVyYXRpb24gPSB0eXBlb2YgZHVyYXRpb24gPT09ICdudW1iZXInICYmICFpc05hTihkdXJhdGlvbikgPyBkdXJhdGlvbiAqIDEwMDAgOiAwO1xuICAgICAgcmV0dXJuIGR1cmF0aW9uICsgNTA7IC8vIHdlIHRha2UgYSBzaG9ydCBvZmZzZXQgdG8gbWFrZSBzdXJlIHdlIGZpcmUgb24gdGhlIG5leHQgZnJhbWUgYWZ0ZXIgYW5pbWF0aW9uXG4gICAgfSxcbiAgICBlbXVsYXRlVHJhbnNpdGlvbkVuZCA9IGZ1bmN0aW9uKGVsZW1lbnQsaGFuZGxlcil7IC8vIGVtdWxhdGVUcmFuc2l0aW9uRW5kIHNpbmNlIDIuMC40XG4gICAgICB2YXIgY2FsbGVkID0gMCwgZHVyYXRpb24gPSBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudChlbGVtZW50KTtcbiAgICAgIHN1cHBvcnRUcmFuc2l0aW9ucyAmJiBvbmUoZWxlbWVudCwgdHJhbnNpdGlvbkVuZEV2ZW50LCBmdW5jdGlvbihlKXsgaGFuZGxlcihlKTsgY2FsbGVkID0gMTsgfSk7XG4gICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkgeyAhY2FsbGVkICYmIGhhbmRsZXIoKTsgfSwgZHVyYXRpb24pO1xuICAgIH0sXG4gICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQgPSBmdW5jdGlvbiAoZXZlbnROYW1lLCBjb21wb25lbnROYW1lLCByZWxhdGVkKSB7XG4gICAgICB2YXIgT3JpZ2luYWxDdXN0b21FdmVudCA9IG5ldyBDdXN0b21FdmVudCggZXZlbnROYW1lICsgJy5icy4nICsgY29tcG9uZW50TmFtZSk7XG4gICAgICBPcmlnaW5hbEN1c3RvbUV2ZW50LnJlbGF0ZWRUYXJnZXQgPSByZWxhdGVkO1xuICAgICAgdGhpcy5kaXNwYXRjaEV2ZW50KE9yaWdpbmFsQ3VzdG9tRXZlbnQpO1xuICAgIH0sXG4gIFxuICAgIC8vIHRvb2x0aXAgLyBwb3BvdmVyIHN0dWZmXG4gICAgZ2V0U2Nyb2xsID0gZnVuY3Rpb24oKSB7IC8vIGFsc28gQWZmaXggYW5kIFNjcm9sbFNweSB1c2VzIGl0XG4gICAgICByZXR1cm4ge1xuICAgICAgICB5IDogZ2xvYmFsT2JqZWN0LnBhZ2VZT2Zmc2V0IHx8IEhUTUxbc2Nyb2xsVG9wXSxcbiAgICAgICAgeCA6IGdsb2JhbE9iamVjdC5wYWdlWE9mZnNldCB8fCBIVE1MW3Njcm9sbExlZnRdXG4gICAgICB9XG4gICAgfSxcbiAgICBzdHlsZVRpcCA9IGZ1bmN0aW9uKGxpbmssZWxlbWVudCxwb3NpdGlvbixwYXJlbnQpIHsgLy8gYm90aCBwb3BvdmVycyBhbmQgdG9vbHRpcHMgKHRhcmdldCx0b29sdGlwL3BvcG92ZXIscGxhY2VtZW50LGVsZW1lbnRUb0FwcGVuZFRvKVxuICAgICAgdmFyIGVsZW1lbnREaW1lbnNpb25zID0geyB3IDogZWxlbWVudFtvZmZzZXRXaWR0aF0sIGg6IGVsZW1lbnRbb2Zmc2V0SGVpZ2h0XSB9LFxuICAgICAgICAgIHdpbmRvd1dpZHRoID0gKEhUTUxbY2xpZW50V2lkdGhdIHx8IERPQ1tib2R5XVtjbGllbnRXaWR0aF0pLFxuICAgICAgICAgIHdpbmRvd0hlaWdodCA9IChIVE1MW2NsaWVudEhlaWdodF0gfHwgRE9DW2JvZHldW2NsaWVudEhlaWdodF0pLFxuICAgICAgICAgIHJlY3QgPSBsaW5rW2dldEJvdW5kaW5nQ2xpZW50UmVjdF0oKSwgXG4gICAgICAgICAgc2Nyb2xsID0gcGFyZW50ID09PSBET0NbYm9keV0gPyBnZXRTY3JvbGwoKSA6IHsgeDogcGFyZW50W29mZnNldExlZnRdICsgcGFyZW50W3Njcm9sbExlZnRdLCB5OiBwYXJlbnRbb2Zmc2V0VG9wXSArIHBhcmVudFtzY3JvbGxUb3BdIH0sXG4gICAgICAgICAgbGlua0RpbWVuc2lvbnMgPSB7IHc6IHJlY3RbcmlnaHRdIC0gcmVjdFtsZWZ0XSwgaDogcmVjdFtib3R0b21dIC0gcmVjdFt0b3BdIH0sXG4gICAgICAgICAgYXJyb3cgPSBxdWVyeUVsZW1lbnQoJ1tjbGFzcyo9XCJhcnJvd1wiXScsZWxlbWVudCksXG4gICAgICAgICAgdG9wUG9zaXRpb24sIGxlZnRQb3NpdGlvbiwgYXJyb3dUb3AsIGFycm93TGVmdCxcbiAgXG4gICAgICAgICAgaGFsZlRvcEV4Y2VlZCA9IHJlY3RbdG9wXSArIGxpbmtEaW1lbnNpb25zLmgvMiAtIGVsZW1lbnREaW1lbnNpb25zLmgvMiA8IDAsXG4gICAgICAgICAgaGFsZkxlZnRFeGNlZWQgPSByZWN0W2xlZnRdICsgbGlua0RpbWVuc2lvbnMudy8yIC0gZWxlbWVudERpbWVuc2lvbnMudy8yIDwgMCxcbiAgICAgICAgICBoYWxmUmlnaHRFeGNlZWQgPSByZWN0W2xlZnRdICsgZWxlbWVudERpbWVuc2lvbnMudy8yICsgbGlua0RpbWVuc2lvbnMudy8yID49IHdpbmRvd1dpZHRoLFxuICAgICAgICAgIGhhbGZCb3R0b21FeGNlZWQgPSByZWN0W3RvcF0gKyBlbGVtZW50RGltZW5zaW9ucy5oLzIgKyBsaW5rRGltZW5zaW9ucy5oLzIgPj0gd2luZG93SGVpZ2h0LFxuICAgICAgICAgIHRvcEV4Y2VlZCA9IHJlY3RbdG9wXSAtIGVsZW1lbnREaW1lbnNpb25zLmggPCAwLFxuICAgICAgICAgIGxlZnRFeGNlZWQgPSByZWN0W2xlZnRdIC0gZWxlbWVudERpbWVuc2lvbnMudyA8IDAsXG4gICAgICAgICAgYm90dG9tRXhjZWVkID0gcmVjdFt0b3BdICsgZWxlbWVudERpbWVuc2lvbnMuaCArIGxpbmtEaW1lbnNpb25zLmggPj0gd2luZG93SGVpZ2h0LFxuICAgICAgICAgIHJpZ2h0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGVsZW1lbnREaW1lbnNpb25zLncgKyBsaW5rRGltZW5zaW9ucy53ID49IHdpbmRvd1dpZHRoO1xuICBcbiAgICAgIC8vIHJlY29tcHV0ZSBwb3NpdGlvblxuICAgICAgcG9zaXRpb24gPSAocG9zaXRpb24gPT09IGxlZnQgfHwgcG9zaXRpb24gPT09IHJpZ2h0KSAmJiBsZWZ0RXhjZWVkICYmIHJpZ2h0RXhjZWVkID8gdG9wIDogcG9zaXRpb247IC8vIGZpcnN0LCB3aGVuIGJvdGggbGVmdCBhbmQgcmlnaHQgbGltaXRzIGFyZSBleGNlZWRlZCwgd2UgZmFsbCBiYWNrIHRvIHRvcHxib3R0b21cbiAgICAgIHBvc2l0aW9uID0gcG9zaXRpb24gPT09IHRvcCAmJiB0b3BFeGNlZWQgPyBib3R0b20gOiBwb3NpdGlvbjtcbiAgICAgIHBvc2l0aW9uID0gcG9zaXRpb24gPT09IGJvdHRvbSAmJiBib3R0b21FeGNlZWQgPyB0b3AgOiBwb3NpdGlvbjtcbiAgICAgIHBvc2l0aW9uID0gcG9zaXRpb24gPT09IGxlZnQgJiYgbGVmdEV4Y2VlZCA/IHJpZ2h0IDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSByaWdodCAmJiByaWdodEV4Y2VlZCA/IGxlZnQgOiBwb3NpdGlvbjtcbiAgICAgIFxuICAgICAgLy8gYXBwbHkgc3R5bGluZyB0byB0b29sdGlwIG9yIHBvcG92ZXJcbiAgICAgIGlmICggcG9zaXRpb24gPT09IGxlZnQgfHwgcG9zaXRpb24gPT09IHJpZ2h0ICkgeyAvLyBzZWNvbmRhcnl8c2lkZSBwb3NpdGlvbnNcbiAgICAgICAgaWYgKCBwb3NpdGlvbiA9PT0gbGVmdCApIHsgLy8gTEVGVFxuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IHJlY3RbbGVmdF0gKyBzY3JvbGwueCAtIGVsZW1lbnREaW1lbnNpb25zLnc7XG4gICAgICAgIH0gZWxzZSB7IC8vIFJJR0hUXG4gICAgICAgICAgbGVmdFBvc2l0aW9uID0gcmVjdFtsZWZ0XSArIHNjcm9sbC54ICsgbGlua0RpbWVuc2lvbnMudztcbiAgICAgICAgfVxuICBcbiAgICAgICAgLy8gYWRqdXN0IHRvcCBhbmQgYXJyb3dcbiAgICAgICAgaWYgKGhhbGZUb3BFeGNlZWQpIHtcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9IHJlY3RbdG9wXSArIHNjcm9sbC55O1xuICAgICAgICAgIGFycm93VG9wID0gbGlua0RpbWVuc2lvbnMuaC8yO1xuICAgICAgICB9IGVsc2UgaWYgKGhhbGZCb3R0b21FeGNlZWQpIHtcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9IHJlY3RbdG9wXSArIHNjcm9sbC55IC0gZWxlbWVudERpbWVuc2lvbnMuaCArIGxpbmtEaW1lbnNpb25zLmg7XG4gICAgICAgICAgYXJyb3dUb3AgPSBlbGVtZW50RGltZW5zaW9ucy5oIC0gbGlua0RpbWVuc2lvbnMuaC8yO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHRvcFBvc2l0aW9uID0gcmVjdFt0b3BdICsgc2Nyb2xsLnkgLSBlbGVtZW50RGltZW5zaW9ucy5oLzIgKyBsaW5rRGltZW5zaW9ucy5oLzI7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSBpZiAoIHBvc2l0aW9uID09PSB0b3AgfHwgcG9zaXRpb24gPT09IGJvdHRvbSApIHsgLy8gcHJpbWFyeXx2ZXJ0aWNhbCBwb3NpdGlvbnNcbiAgICAgICAgaWYgKCBwb3NpdGlvbiA9PT0gdG9wKSB7IC8vIFRPUFxuICAgICAgICAgIHRvcFBvc2l0aW9uID0gIHJlY3RbdG9wXSArIHNjcm9sbC55IC0gZWxlbWVudERpbWVuc2lvbnMuaDtcbiAgICAgICAgfSBlbHNlIHsgLy8gQk9UVE9NXG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueSArIGxpbmtEaW1lbnNpb25zLmg7XG4gICAgICAgIH1cbiAgICAgICAgLy8gYWRqdXN0IGxlZnQgfCByaWdodCBhbmQgYWxzbyB0aGUgYXJyb3dcbiAgICAgICAgaWYgKGhhbGZMZWZ0RXhjZWVkKSB7XG4gICAgICAgICAgbGVmdFBvc2l0aW9uID0gMDtcbiAgICAgICAgICBhcnJvd0xlZnQgPSByZWN0W2xlZnRdICsgbGlua0RpbWVuc2lvbnMudy8yO1xuICAgICAgICB9IGVsc2UgaWYgKGhhbGZSaWdodEV4Y2VlZCkge1xuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IHdpbmRvd1dpZHRoIC0gZWxlbWVudERpbWVuc2lvbnMudyoxLjAxO1xuICAgICAgICAgIGFycm93TGVmdCA9IGVsZW1lbnREaW1lbnNpb25zLncgLSAoIHdpbmRvd1dpZHRoIC0gcmVjdFtsZWZ0XSApICsgbGlua0RpbWVuc2lvbnMudy8yO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IHJlY3RbbGVmdF0gKyBzY3JvbGwueCAtIGVsZW1lbnREaW1lbnNpb25zLncvMiArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfVxuICAgICAgfVxuICBcbiAgICAgIC8vIGFwcGx5IHN0eWxlIHRvIHRvb2x0aXAvcG9wb3ZlciBhbmQgaXQncyBhcnJvd1xuICAgICAgZWxlbWVudFtzdHlsZV1bdG9wXSA9IHRvcFBvc2l0aW9uICsgJ3B4JztcbiAgICAgIGVsZW1lbnRbc3R5bGVdW2xlZnRdID0gbGVmdFBvc2l0aW9uICsgJ3B4JztcbiAgXG4gICAgICBhcnJvd1RvcCAmJiAoYXJyb3dbc3R5bGVdW3RvcF0gPSBhcnJvd1RvcCArICdweCcpO1xuICAgICAgYXJyb3dMZWZ0ICYmIChhcnJvd1tzdHlsZV1bbGVmdF0gPSBhcnJvd0xlZnQgKyAncHgnKTtcbiAgXG4gICAgICBlbGVtZW50LmNsYXNzTmFtZVtpbmRleE9mXShwb3NpdGlvbikgPT09IC0xICYmIChlbGVtZW50LmNsYXNzTmFtZSA9IGVsZW1lbnQuY2xhc3NOYW1lLnJlcGxhY2UodGlwUG9zaXRpb25zLHBvc2l0aW9uKSk7XG4gICAgfTtcbiAgXG4gIEJTTi52ZXJzaW9uID0gJzIuMC4yMyc7XG4gIFxuICAvKiBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIDMgfCBNb2RhbFxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tKi9cbiAgXG4gIC8vIE1PREFMIERFRklOSVRJT05cbiAgLy8gPT09PT09PT09PT09PT09XG4gIHZhciBNb2RhbCA9IGZ1bmN0aW9uKGVsZW1lbnQsIG9wdGlvbnMpIHsgLy8gZWxlbWVudCBjYW4gYmUgdGhlIG1vZGFsL3RyaWdnZXJpbmcgYnV0dG9uXG4gIFxuICAgIC8vIHRoZSBtb2RhbCAoYm90aCBKYXZhU2NyaXB0IC8gREFUQSBBUEkgaW5pdCkgLyB0cmlnZ2VyaW5nIGJ1dHRvbiBlbGVtZW50IChEQVRBIEFQSSlcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBkZXRlcm1pbmUgbW9kYWwsIHRyaWdnZXJpbmcgZWxlbWVudFxuICAgIHZhciBidG5DaGVjayA9IGVsZW1lbnRbZ2V0QXR0cmlidXRlXShkYXRhVGFyZ2V0KXx8ZWxlbWVudFtnZXRBdHRyaWJ1dGVdKCdocmVmJyksXG4gICAgICBjaGVja01vZGFsID0gcXVlcnlFbGVtZW50KCBidG5DaGVjayApLFxuICAgICAgbW9kYWwgPSBoYXNDbGFzcyhlbGVtZW50LCdtb2RhbCcpID8gZWxlbWVudCA6IGNoZWNrTW9kYWwsXG4gICAgICBvdmVybGF5RGVsYXksXG4gIFxuICAgICAgLy8gc3RyaW5nc1xuICAgICAgY29tcG9uZW50ID0gJ21vZGFsJyxcbiAgICAgIHN0YXRpY1N0cmluZyA9ICdzdGF0aWMnLFxuICAgICAgcGFkZGluZ0xlZnQgPSAncGFkZGluZ0xlZnQnLFxuICAgICAgcGFkZGluZ1JpZ2h0ID0gJ3BhZGRpbmdSaWdodCcsXG4gICAgICBtb2RhbEJhY2tkcm9wU3RyaW5nID0gJ21vZGFsLWJhY2tkcm9wJztcbiAgXG4gICAgaWYgKCBoYXNDbGFzcyhlbGVtZW50LCdtb2RhbCcpICkgeyBlbGVtZW50ID0gbnVsbDsgfSAvLyBtb2RhbCBpcyBub3cgaW5kZXBlbmRlbnQgb2YgaXQncyB0cmlnZ2VyaW5nIGVsZW1lbnRcbiAgXG4gICAgaWYgKCAhbW9kYWwgKSB7IHJldHVybjsgfSAvLyBpbnZhbGlkYXRlXG4gIFxuICAgIC8vIHNldCBvcHRpb25zXG4gICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gIFxuICAgIHRoaXNba2V5Ym9hcmRdID0gb3B0aW9uc1trZXlib2FyZF0gPT09IGZhbHNlIHx8IG1vZGFsW2dldEF0dHJpYnV0ZV0oZGF0YUtleWJvYXJkKSA9PT0gJ2ZhbHNlJyA/IGZhbHNlIDogdHJ1ZTtcbiAgICB0aGlzW2JhY2tkcm9wXSA9IG9wdGlvbnNbYmFja2Ryb3BdID09PSBzdGF0aWNTdHJpbmcgfHwgbW9kYWxbZ2V0QXR0cmlidXRlXShkYXRhYmFja2Ryb3ApID09PSBzdGF0aWNTdHJpbmcgPyBzdGF0aWNTdHJpbmcgOiB0cnVlO1xuICAgIHRoaXNbYmFja2Ryb3BdID0gb3B0aW9uc1tiYWNrZHJvcF0gPT09IGZhbHNlIHx8IG1vZGFsW2dldEF0dHJpYnV0ZV0oZGF0YWJhY2tkcm9wKSA9PT0gJ2ZhbHNlJyA/IGZhbHNlIDogdGhpc1tiYWNrZHJvcF07XG4gICAgdGhpc1tjb250ZW50XSAgPSBvcHRpb25zW2NvbnRlbnRdOyAvLyBKYXZhU2NyaXB0IG9ubHlcbiAgXG4gICAgLy8gYmluZCwgY29uc3RhbnRzLCBldmVudCB0YXJnZXRzIGFuZCBvdGhlciB2YXJzXG4gICAgdmFyIHNlbGYgPSB0aGlzLCByZWxhdGVkVGFyZ2V0ID0gbnVsbCxcbiAgICAgIGJvZHlJc092ZXJmbG93aW5nLCBtb2RhbElzT3ZlcmZsb3dpbmcsIHNjcm9sbGJhcldpZHRoLCBvdmVybGF5LFxuICBcbiAgICAgIC8vIGFsc28gZmluZCBmaXhlZC10b3AgLyBmaXhlZC1ib3R0b20gaXRlbXNcbiAgICAgIGZpeGVkSXRlbXMgPSBnZXRFbGVtZW50c0J5Q2xhc3NOYW1lKEhUTUwsZml4ZWRUb3ApLmNvbmNhdChnZXRFbGVtZW50c0J5Q2xhc3NOYW1lKEhUTUwsZml4ZWRCb3R0b20pKSxcbiAgXG4gICAgICAvLyBwcml2YXRlIG1ldGhvZHNcbiAgICAgIGdldFdpbmRvd1dpZHRoID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBodG1sUmVjdCA9IEhUTUxbZ2V0Qm91bmRpbmdDbGllbnRSZWN0XSgpO1xuICAgICAgICByZXR1cm4gZ2xvYmFsT2JqZWN0W2lubmVyV2lkdGhdIHx8IChodG1sUmVjdFtyaWdodF0gLSBNYXRoLmFicyhodG1sUmVjdFtsZWZ0XSkpO1xuICAgICAgfSxcbiAgICAgIHNldFNjcm9sbGJhciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGJvZHlTdHlsZSA9IERPQ1tib2R5XS5jdXJyZW50U3R5bGUgfHwgZ2xvYmFsT2JqZWN0W2dldENvbXB1dGVkU3R5bGVdKERPQ1tib2R5XSksXG4gICAgICAgICAgICBib2R5UGFkID0gcGFyc2VJbnQoKGJvZHlTdHlsZVtwYWRkaW5nUmlnaHRdKSwgMTApLCBpdGVtUGFkO1xuICAgICAgICBpZiAoYm9keUlzT3ZlcmZsb3dpbmcpIHtcbiAgICAgICAgICBET0NbYm9keV1bc3R5bGVdW3BhZGRpbmdSaWdodF0gPSAoYm9keVBhZCArIHNjcm9sbGJhcldpZHRoKSArICdweCc7XG4gICAgICAgICAgaWYgKGZpeGVkSXRlbXNbbGVuZ3RoXSl7XG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IGZpeGVkSXRlbXNbbGVuZ3RoXTsgaSsrKSB7XG4gICAgICAgICAgICAgIGl0ZW1QYWQgPSAoZml4ZWRJdGVtc1tpXS5jdXJyZW50U3R5bGUgfHwgZ2xvYmFsT2JqZWN0W2dldENvbXB1dGVkU3R5bGVdKGZpeGVkSXRlbXNbaV0pKVtwYWRkaW5nUmlnaHRdO1xuICAgICAgICAgICAgICBmaXhlZEl0ZW1zW2ldW3N0eWxlXVtwYWRkaW5nUmlnaHRdID0gKCBwYXJzZUludChpdGVtUGFkKSArIHNjcm9sbGJhcldpZHRoKSArICdweCc7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgcmVzZXRTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIERPQ1tib2R5XVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgICBpZiAoZml4ZWRJdGVtc1tsZW5ndGhdKXtcbiAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IGZpeGVkSXRlbXNbbGVuZ3RoXTsgaSsrKSB7XG4gICAgICAgICAgICBmaXhlZEl0ZW1zW2ldW3N0eWxlXVtwYWRkaW5nUmlnaHRdID0gJyc7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgbWVhc3VyZVNjcm9sbGJhciA9IGZ1bmN0aW9uICgpIHsgLy8gdGh4IHdhbHNoXG4gICAgICAgIHZhciBzY3JvbGxEaXYgPSBET0NbY3JlYXRlRWxlbWVudF0oJ2RpdicpLCBzY3JvbGxCYXJXaWR0aDtcbiAgICAgICAgc2Nyb2xsRGl2LmNsYXNzTmFtZSA9IGNvbXBvbmVudCsnLXNjcm9sbGJhci1tZWFzdXJlJzsgLy8gdGhpcyBpcyBoZXJlIHRvIHN0YXlcbiAgICAgICAgRE9DW2JvZHldW2FwcGVuZENoaWxkXShzY3JvbGxEaXYpO1xuICAgICAgICBzY3JvbGxCYXJXaWR0aCA9IHNjcm9sbERpdltvZmZzZXRXaWR0aF0gLSBzY3JvbGxEaXZbY2xpZW50V2lkdGhdO1xuICAgICAgICBET0NbYm9keV0ucmVtb3ZlQ2hpbGQoc2Nyb2xsRGl2KTtcbiAgICAgIHJldHVybiBzY3JvbGxCYXJXaWR0aDtcbiAgICAgIH0sXG4gICAgICBjaGVja1Njcm9sbGJhciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgYm9keUlzT3ZlcmZsb3dpbmcgPSBET0NbYm9keV1bY2xpZW50V2lkdGhdIDwgZ2V0V2luZG93V2lkdGgoKTtcbiAgICAgICAgbW9kYWxJc092ZXJmbG93aW5nID0gbW9kYWxbc2Nyb2xsSGVpZ2h0XSA+IEhUTUxbY2xpZW50SGVpZ2h0XTtcbiAgICAgICAgc2Nyb2xsYmFyV2lkdGggPSBtZWFzdXJlU2Nyb2xsYmFyKCk7XG4gICAgICB9LFxuICAgICAgYWRqdXN0RGlhbG9nID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV1bcGFkZGluZ0xlZnRdID0gIWJvZHlJc092ZXJmbG93aW5nICYmIG1vZGFsSXNPdmVyZmxvd2luZyA/IHNjcm9sbGJhcldpZHRoICsgJ3B4JyA6ICcnO1xuICAgICAgICBtb2RhbFtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9IGJvZHlJc092ZXJmbG93aW5nICYmICFtb2RhbElzT3ZlcmZsb3dpbmcgPyBzY3JvbGxiYXJXaWR0aCArICdweCcgOiAnJztcbiAgICAgIH0sXG4gICAgICByZXNldEFkanVzdG1lbnRzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV1bcGFkZGluZ0xlZnRdID0gJyc7XG4gICAgICAgIG1vZGFsW3N0eWxlXVtwYWRkaW5nUmlnaHRdID0gJyc7XG4gICAgICB9LFxuICAgICAgY3JlYXRlT3ZlcmxheSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbE92ZXJsYXkgPSAxO1xuICAgICAgICBcbiAgICAgICAgdmFyIG5ld092ZXJsYXkgPSBET0NbY3JlYXRlRWxlbWVudF0oJ2RpdicpO1xuICAgICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgXG4gICAgICAgIGlmICggb3ZlcmxheSA9PT0gbnVsbCApIHtcbiAgICAgICAgICBuZXdPdmVybGF5W3NldEF0dHJpYnV0ZV0oJ2NsYXNzJyxtb2RhbEJhY2tkcm9wU3RyaW5nKycgZmFkZScpO1xuICAgICAgICAgIG92ZXJsYXkgPSBuZXdPdmVybGF5O1xuICAgICAgICAgIERPQ1tib2R5XVthcHBlbmRDaGlsZF0ob3ZlcmxheSk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICByZW1vdmVPdmVybGF5ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIG92ZXJsYXkgPSBxdWVyeUVsZW1lbnQoJy4nK21vZGFsQmFja2Ryb3BTdHJpbmcpO1xuICAgICAgICBpZiAoIG92ZXJsYXkgJiYgb3ZlcmxheSAhPT0gbnVsbCAmJiB0eXBlb2Ygb3ZlcmxheSA9PT0gJ29iamVjdCcgKSB7XG4gICAgICAgICAgbW9kYWxPdmVybGF5ID0gMDtcbiAgICAgICAgICBET0NbYm9keV0ucmVtb3ZlQ2hpbGQob3ZlcmxheSk7IG92ZXJsYXkgPSBudWxsO1xuICAgICAgICB9XG4gICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwobW9kYWwsIGhpZGRlbkV2ZW50LCBjb21wb25lbnQpOyAgICAgIFxuICAgICAgfSxcbiAgICAgIGtleWRvd25IYW5kbGVyVG9nZ2xlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmIChoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSkge1xuICAgICAgICAgIG9uKERPQywga2V5ZG93bkV2ZW50LCBrZXlIYW5kbGVyKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBvZmYoRE9DLCBrZXlkb3duRXZlbnQsIGtleUhhbmRsZXIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgcmVzaXplSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihnbG9iYWxPYmplY3QsIHJlc2l6ZUV2ZW50LCBzZWxmLnVwZGF0ZSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgb2ZmKGdsb2JhbE9iamVjdCwgcmVzaXplRXZlbnQsIHNlbGYudXBkYXRlKTtcbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIGRpc21pc3NIYW5kbGVyVG9nZ2xlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmIChoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSkge1xuICAgICAgICAgIG9uKG1vZGFsLCBjbGlja0V2ZW50LCBkaXNtaXNzSGFuZGxlcik7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgb2ZmKG1vZGFsLCBjbGlja0V2ZW50LCBkaXNtaXNzSGFuZGxlcik7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICAvLyB0cmlnZ2Vyc1xuICAgICAgdHJpZ2dlclNob3cgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgc2V0Rm9jdXMobW9kYWwpO1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBzaG93bkV2ZW50LCBjb21wb25lbnQsIHJlbGF0ZWRUYXJnZXQpO1xuICAgICAgfSxcbiAgICAgIHRyaWdnZXJIaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIG1vZGFsW3N0eWxlXS5kaXNwbGF5ID0gJyc7XG4gICAgICAgIGVsZW1lbnQgJiYgKHNldEZvY3VzKGVsZW1lbnQpKTtcbiAgICAgICAgXG4gICAgICAgIChmdW5jdGlvbigpe1xuICAgICAgICAgIGlmICghZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShET0MsY29tcG9uZW50KycgJytpbkNsYXNzKVswXSkge1xuICAgICAgICAgICAgcmVzZXRBZGp1c3RtZW50cygpO1xuICAgICAgICAgICAgcmVzZXRTY3JvbGxiYXIoKTtcbiAgICAgICAgICAgIHJlbW92ZUNsYXNzKERPQ1tib2R5XSxjb21wb25lbnQrJy1vcGVuJyk7XG4gICAgICAgICAgICBvdmVybGF5ICYmIGhhc0NsYXNzKG92ZXJsYXksJ2ZhZGUnKSA/IChyZW1vdmVDbGFzcyhvdmVybGF5LGluQ2xhc3MpLCBlbXVsYXRlVHJhbnNpdGlvbkVuZChvdmVybGF5LHJlbW92ZU92ZXJsYXkpKSBcbiAgICAgICAgICAgIDogcmVtb3ZlT3ZlcmxheSgpO1xuICBcbiAgICAgICAgICAgIHJlc2l6ZUhhbmRsZXJUb2dnbGUoKTtcbiAgICAgICAgICAgIGRpc21pc3NIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgICAgICBrZXlkb3duSGFuZGxlclRvZ2dsZSgpO1xuICAgICAgICAgIH1cbiAgICAgICAgfSgpKTtcbiAgICAgIH0sXG4gICAgICAvLyBoYW5kbGVyc1xuICAgICAgY2xpY2tIYW5kbGVyID0gZnVuY3Rpb24oZSkge1xuICAgICAgICB2YXIgY2xpY2tUYXJnZXQgPSBlW3RhcmdldF07XG4gICAgICAgIGNsaWNrVGFyZ2V0ID0gY2xpY2tUYXJnZXRbaGFzQXR0cmlidXRlXShkYXRhVGFyZ2V0KSB8fCBjbGlja1RhcmdldFtoYXNBdHRyaWJ1dGVdKCdocmVmJykgPyBjbGlja1RhcmdldCA6IGNsaWNrVGFyZ2V0W3BhcmVudE5vZGVdO1xuICAgICAgICBpZiAoIGNsaWNrVGFyZ2V0ID09PSBlbGVtZW50ICYmICFoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSApIHtcbiAgICAgICAgICBtb2RhbC5tb2RhbFRyaWdnZXIgPSBlbGVtZW50O1xuICAgICAgICAgIHJlbGF0ZWRUYXJnZXQgPSBlbGVtZW50O1xuICAgICAgICAgIHNlbGYuc2hvdygpO1xuICAgICAgICAgIGVbcHJldmVudERlZmF1bHRdKCk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBrZXlIYW5kbGVyID0gZnVuY3Rpb24oZSkge1xuICAgICAgICB2YXIga2V5ID0gZS53aGljaCB8fCBlLmtleUNvZGU7IC8vIGtleUNvZGUgZm9yIElFOFxuICAgICAgICBpZiAoc2VsZltrZXlib2FyZF0gJiYga2V5ID09IDI3ICYmIGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgICAgc2VsZi5oaWRlKCk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBkaXNtaXNzSGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGNsaWNrVGFyZ2V0ID0gZVt0YXJnZXRdO1xuICAgICAgICBpZiAoIGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpICYmIChjbGlja1RhcmdldFtwYXJlbnROb2RlXVtnZXRBdHRyaWJ1dGVdKGRhdGFEaXNtaXNzKSA9PT0gY29tcG9uZW50XG4gICAgICAgICAgICB8fCBjbGlja1RhcmdldFtnZXRBdHRyaWJ1dGVdKGRhdGFEaXNtaXNzKSA9PT0gY29tcG9uZW50XG4gICAgICAgICAgICB8fCAoY2xpY2tUYXJnZXQgPT09IG1vZGFsICYmIHNlbGZbYmFja2Ryb3BdICE9PSBzdGF0aWNTdHJpbmcpICkgKSB7XG4gICAgICAgICAgc2VsZi5oaWRlKCk7IHJlbGF0ZWRUYXJnZXQgPSBudWxsO1xuICAgICAgICAgIGVbcHJldmVudERlZmF1bHRdKCk7XG4gICAgICAgIH1cbiAgICAgIH07XG4gIFxuICAgIC8vIHB1YmxpYyBtZXRob2RzXG4gICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbigpIHtcbiAgICAgIGlmICggaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykgKSB7dGhpcy5oaWRlKCk7fSBlbHNlIHt0aGlzLnNob3coKTt9XG4gICAgfTtcbiAgICB0aGlzLnNob3cgPSBmdW5jdGlvbigpIHtcbiAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwobW9kYWwsIHNob3dFdmVudCwgY29tcG9uZW50LCByZWxhdGVkVGFyZ2V0KTtcbiAgXG4gICAgICAvLyB3ZSBlbGVnYW50bHkgaGlkZSBhbnkgb3BlbmVkIG1vZGFsXG4gICAgICB2YXIgY3VycmVudE9wZW4gPSBnZXRFbGVtZW50c0J5Q2xhc3NOYW1lKERPQyxjb21wb25lbnQrJyBpbicpWzBdO1xuICAgICAgY3VycmVudE9wZW4gJiYgY3VycmVudE9wZW4gIT09IG1vZGFsICYmIGN1cnJlbnRPcGVuLm1vZGFsVHJpZ2dlcltzdHJpbmdNb2RhbF0uaGlkZSgpO1xuICBcbiAgICAgIGlmICggdGhpc1tiYWNrZHJvcF0gKSB7XG4gICAgICAgICFtb2RhbE92ZXJsYXkgJiYgY3JlYXRlT3ZlcmxheSgpO1xuICAgICAgfVxuICBcbiAgICAgIGlmICggb3ZlcmxheSAmJiBtb2RhbE92ZXJsYXkgJiYgIWhhc0NsYXNzKG92ZXJsYXksaW5DbGFzcykpIHtcbiAgICAgICAgb3ZlcmxheVtvZmZzZXRXaWR0aF07IC8vIGZvcmNlIHJlZmxvdyB0byBlbmFibGUgdHJhc2l0aW9uXG4gICAgICAgIG92ZXJsYXlEZWxheSA9IGdldFRyYW5zaXRpb25EdXJhdGlvbkZyb21FbGVtZW50KG92ZXJsYXkpO1xuICAgICAgICBhZGRDbGFzcyhvdmVybGF5LGluQ2xhc3MpO1xuICAgICAgfVxuICBcbiAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKSB7XG4gICAgICAgIG1vZGFsW3N0eWxlXS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgXG4gICAgICAgIGNoZWNrU2Nyb2xsYmFyKCk7XG4gICAgICAgIHNldFNjcm9sbGJhcigpO1xuICAgICAgICBhZGp1c3REaWFsb2coKTtcbiAgXG4gICAgICAgIGFkZENsYXNzKERPQ1tib2R5XSxjb21wb25lbnQrJy1vcGVuJyk7XG4gICAgICAgIGFkZENsYXNzKG1vZGFsLGluQ2xhc3MpO1xuICAgICAgICBtb2RhbFtzZXRBdHRyaWJ1dGVdKGFyaWFIaWRkZW4sIGZhbHNlKTtcbiAgICAgICAgXG4gICAgICAgIHJlc2l6ZUhhbmRsZXJUb2dnbGUoKTtcbiAgICAgICAgZGlzbWlzc0hhbmRsZXJUb2dnbGUoKTtcbiAgICAgICAga2V5ZG93bkhhbmRsZXJUb2dnbGUoKTtcbiAgXG4gICAgICAgIGhhc0NsYXNzKG1vZGFsLCdmYWRlJykgPyBlbXVsYXRlVHJhbnNpdGlvbkVuZChtb2RhbCwgdHJpZ2dlclNob3cpIDogdHJpZ2dlclNob3coKTtcbiAgICAgIH0sIHN1cHBvcnRUcmFuc2l0aW9ucyAmJiBvdmVybGF5ID8gb3ZlcmxheURlbGF5IDogMCk7XG4gICAgfTtcbiAgICB0aGlzLmhpZGUgPSBmdW5jdGlvbigpIHtcbiAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwobW9kYWwsIGhpZGVFdmVudCwgY29tcG9uZW50KTtcbiAgICAgIG92ZXJsYXkgPSBxdWVyeUVsZW1lbnQoJy4nK21vZGFsQmFja2Ryb3BTdHJpbmcpO1xuICAgICAgb3ZlcmxheURlbGF5ID0gb3ZlcmxheSAmJiBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudChvdmVybGF5KTtcbiAgXG4gICAgICByZW1vdmVDbGFzcyhtb2RhbCxpbkNsYXNzKTtcbiAgICAgIG1vZGFsW3NldEF0dHJpYnV0ZV0oYXJpYUhpZGRlbiwgdHJ1ZSk7XG4gIFxuICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICBoYXNDbGFzcyhtb2RhbCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQobW9kYWwsIHRyaWdnZXJIaWRlKSA6IHRyaWdnZXJIaWRlKCk7XG4gICAgICB9LCBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb3ZlcmxheSA/IG92ZXJsYXlEZWxheSA6IDApO1xuICAgIH07XG4gICAgdGhpcy5zZXRDb250ZW50ID0gZnVuY3Rpb24oIGNvbnRlbnQgKSB7XG4gICAgICBxdWVyeUVsZW1lbnQoJy4nK2NvbXBvbmVudCsnLWNvbnRlbnQnLG1vZGFsKVtpbm5lckhUTUxdID0gY29udGVudDtcbiAgICB9O1xuICAgIHRoaXMudXBkYXRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgY2hlY2tTY3JvbGxiYXIoKTtcbiAgICAgICAgc2V0U2Nyb2xsYmFyKCk7XG4gICAgICAgIGFkanVzdERpYWxvZygpO1xuICAgICAgfVxuICAgIH07XG4gIFxuICAgIC8vIGluaXRcbiAgICAvLyBwcmV2ZW50IGFkZGluZyBldmVudCBoYW5kbGVycyBvdmVyIGFuZCBvdmVyXG4gICAgLy8gbW9kYWwgaXMgaW5kZXBlbmRlbnQgb2YgYSB0cmlnZ2VyaW5nIGVsZW1lbnRcbiAgICBpZiAoICEhZWxlbWVudCAmJiAhKHN0cmluZ01vZGFsIGluIGVsZW1lbnQpICkge1xuICAgICAgb24oZWxlbWVudCwgY2xpY2tFdmVudCwgY2xpY2tIYW5kbGVyKTtcbiAgICB9XG4gICAgaWYgKCAhIXNlbGZbY29udGVudF0gKSB7IHNlbGYuc2V0Q29udGVudCggc2VsZltjb250ZW50XSApOyB9XG4gICAgISFlbGVtZW50ICYmIChlbGVtZW50W3N0cmluZ01vZGFsXSA9IHNlbGYpO1xuICB9O1xuICBcbiAgLy8gREFUQSBBUElcbiAgc3VwcG9ydHNbcHVzaF0oIFsgc3RyaW5nTW9kYWwsIE1vZGFsLCAnWycrZGF0YVRvZ2dsZSsnPVwibW9kYWxcIl0nIF0gKTtcbiAgXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IENvbGxhcHNlXG4gIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tKi9cbiAgXG4gIC8vIENPTExBUFNFIERFRklOSVRJT05cbiAgLy8gPT09PT09PT09PT09PT09PT09PVxuICB2YXIgQ29sbGFwc2UgPSBmdW5jdGlvbiggZWxlbWVudCwgb3B0aW9ucyApIHtcbiAgXG4gICAgLy8gaW5pdGlhbGl6YXRpb24gZWxlbWVudFxuICAgIGVsZW1lbnQgPSBxdWVyeUVsZW1lbnQoZWxlbWVudCk7XG4gIFxuICAgIC8vIHNldCBvcHRpb25zXG4gICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gIFxuICAgIC8vIGV2ZW50IHRhcmdldHMgYW5kIGNvbnN0YW50c1xuICAgIHZhciBhY2NvcmRpb24gPSBudWxsLCBjb2xsYXBzZSA9IG51bGwsIHNlbGYgPSB0aGlzLFxuICAgICAgYWNjb3JkaW9uRGF0YSA9IGVsZW1lbnRbZ2V0QXR0cmlidXRlXSgnZGF0YS1wYXJlbnQnKSxcbiAgICAgIGFjdGl2ZUNvbGxhcHNlLCBhY3RpdmVFbGVtZW50LFxuICBcbiAgICAgIC8vIGNvbXBvbmVudCBzdHJpbmdzXG4gICAgICBjb21wb25lbnQgPSAnY29sbGFwc2UnLFxuICAgICAgY29sbGFwc2VkID0gJ2NvbGxhcHNlZCcsXG4gICAgICBpc0FuaW1hdGluZyA9ICdpc0FuaW1hdGluZycsXG4gIFxuICAgICAgLy8gcHJpdmF0ZSBtZXRob2RzXG4gICAgICBvcGVuQWN0aW9uID0gZnVuY3Rpb24oY29sbGFwc2VFbGVtZW50LHRvZ2dsZSkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgc2hvd0V2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbaXNBbmltYXRpbmddID0gdHJ1ZTtcbiAgICAgICAgYWRkQ2xhc3MoY29sbGFwc2VFbGVtZW50LGNvbGxhcHNpbmcpO1xuICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29tcG9uZW50KTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gY29sbGFwc2VFbGVtZW50W3Njcm9sbEhlaWdodF0gKyAncHgnO1xuICAgICAgICBcbiAgICAgICAgZW11bGF0ZVRyYW5zaXRpb25FbmQoY29sbGFwc2VFbGVtZW50LCBmdW5jdGlvbigpIHtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbaXNBbmltYXRpbmddID0gZmFsc2U7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W3NldEF0dHJpYnV0ZV0oYXJpYUV4cGFuZGVkLCd0cnVlJyk7XG4gICAgICAgICAgdG9nZ2xlW3NldEF0dHJpYnV0ZV0oYXJpYUV4cGFuZGVkLCd0cnVlJyk7ICAgICAgICAgIFxuICAgICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGNvbXBvbmVudCk7XG4gICAgICAgICAgYWRkQ2xhc3MoY29sbGFwc2VFbGVtZW50LCBpbkNsYXNzKTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc3R5bGVdW2hlaWdodF0gPSAnJztcbiAgICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgc2hvd25FdmVudCwgY29tcG9uZW50KTtcbiAgICAgICAgfSk7XG4gICAgICB9LFxuICAgICAgY2xvc2VBY3Rpb24gPSBmdW5jdGlvbihjb2xsYXBzZUVsZW1lbnQsdG9nZ2xlKSB7XG4gICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwoY29sbGFwc2VFbGVtZW50LCBoaWRlRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIGNvbGxhcHNlRWxlbWVudFtpc0FuaW1hdGluZ10gPSB0cnVlO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc3R5bGVdW2hlaWdodF0gPSBjb2xsYXBzZUVsZW1lbnRbc2Nyb2xsSGVpZ2h0XSArICdweCc7IC8vIHNldCBoZWlnaHQgZmlyc3RcbiAgICAgICAgcmVtb3ZlQ2xhc3MoY29sbGFwc2VFbGVtZW50LGNvbXBvbmVudCk7XG4gICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCwgaW5DbGFzcyk7XG4gICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCwgY29sbGFwc2luZyk7XG4gICAgICAgIGNvbGxhcHNlRWxlbWVudFtvZmZzZXRXaWR0aF07IC8vIGZvcmNlIHJlZmxvdyB0byBlbmFibGUgdHJhbnNpdGlvblxuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc3R5bGVdW2hlaWdodF0gPSAnMHB4JztcbiAgICAgICAgXG4gICAgICAgIGVtdWxhdGVUcmFuc2l0aW9uRW5kKGNvbGxhcHNlRWxlbWVudCwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IGZhbHNlO1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwnZmFsc2UnKTtcbiAgICAgICAgICB0b2dnbGVbc2V0QXR0cmlidXRlXShhcmlhRXhwYW5kZWQsJ2ZhbHNlJyk7XG4gICAgICAgICAgcmVtb3ZlQ2xhc3MoY29sbGFwc2VFbGVtZW50LGNvbGxhcHNpbmcpO1xuICAgICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCxjb21wb25lbnQpO1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtzdHlsZV1baGVpZ2h0XSA9ICcnO1xuICAgICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwoY29sbGFwc2VFbGVtZW50LCBoaWRkZW5FdmVudCwgY29tcG9uZW50KTtcbiAgICAgICAgfSk7XG4gICAgICB9LFxuICAgICAgZ2V0VGFyZ2V0ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBocmVmID0gZWxlbWVudC5ocmVmICYmIGVsZW1lbnRbZ2V0QXR0cmlidXRlXSgnaHJlZicpLFxuICAgICAgICAgIHBhcmVudCA9IGVsZW1lbnRbZ2V0QXR0cmlidXRlXShkYXRhVGFyZ2V0KSxcbiAgICAgICAgICBpZCA9IGhyZWYgfHwgKCBwYXJlbnQgJiYgcGFyZW50LmNoYXJBdCgwKSA9PT0gJyMnICkgJiYgcGFyZW50O1xuICAgICAgICByZXR1cm4gaWQgJiYgcXVlcnlFbGVtZW50KGlkKTtcbiAgICAgIH07XG4gICAgXG4gICAgLy8gcHVibGljIG1ldGhvZHNcbiAgICB0aGlzLnRvZ2dsZSA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgIGVbcHJldmVudERlZmF1bHRdKCk7XG4gICAgICBpZiAoIWhhc0NsYXNzKGNvbGxhcHNlLGluQ2xhc3MpKSB7IHNlbGYuc2hvdygpOyB9IFxuICAgICAgZWxzZSB7IHNlbGYuaGlkZSgpOyB9XG4gICAgfTtcbiAgICB0aGlzLmhpZGUgPSBmdW5jdGlvbigpIHtcbiAgICAgIGlmICggY29sbGFwc2VbaXNBbmltYXRpbmddICkgcmV0dXJuO1xuICAgICAgY2xvc2VBY3Rpb24oY29sbGFwc2UsZWxlbWVudCk7XG4gICAgICBhZGRDbGFzcyhlbGVtZW50LGNvbGxhcHNlZCk7XG4gICAgfTtcbiAgICB0aGlzLnNob3cgPSBmdW5jdGlvbigpIHtcbiAgICAgIGlmICggYWNjb3JkaW9uICkge1xuICAgICAgICBhY3RpdmVDb2xsYXBzZSA9IHF1ZXJ5RWxlbWVudCgnLicrY29tcG9uZW50KycuJytpbkNsYXNzLGFjY29yZGlvbik7XG4gICAgICAgIGFjdGl2ZUVsZW1lbnQgPSBhY3RpdmVDb2xsYXBzZSAmJiAocXVlcnlFbGVtZW50KCdbJytkYXRhVG9nZ2xlKyc9XCInK2NvbXBvbmVudCsnXCJdWycrZGF0YVRhcmdldCsnPVwiIycrYWN0aXZlQ29sbGFwc2UuaWQrJ1wiXScsIGFjY29yZGlvbilcbiAgICAgICAgICAgICAgICAgICAgICB8fCBxdWVyeUVsZW1lbnQoJ1snK2RhdGFUb2dnbGUrJz1cIicrY29tcG9uZW50KydcIl1baHJlZj1cIiMnK2FjdGl2ZUNvbGxhcHNlLmlkKydcIl0nLGFjY29yZGlvbikgKTtcbiAgICAgIH1cbiAgXG4gICAgICBpZiAoICFjb2xsYXBzZVtpc0FuaW1hdGluZ10gfHwgYWN0aXZlQ29sbGFwc2UgJiYgIWFjdGl2ZUNvbGxhcHNlW2lzQW5pbWF0aW5nXSApIHtcbiAgICAgICAgaWYgKCBhY3RpdmVFbGVtZW50ICYmIGFjdGl2ZUNvbGxhcHNlICE9PSBjb2xsYXBzZSApIHtcbiAgICAgICAgICBjbG9zZUFjdGlvbihhY3RpdmVDb2xsYXBzZSxhY3RpdmVFbGVtZW50KTtcbiAgICAgICAgICBhZGRDbGFzcyhhY3RpdmVFbGVtZW50LGNvbGxhcHNlZCk7IFxuICAgICAgICB9XG4gICAgICAgIG9wZW5BY3Rpb24oY29sbGFwc2UsZWxlbWVudCk7XG4gICAgICAgIHJlbW92ZUNsYXNzKGVsZW1lbnQsY29sbGFwc2VkKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgaWYgKCAhKHN0cmluZ0NvbGxhcHNlIGluIGVsZW1lbnQgKSApIHsgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgdHdpY2VcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIHNlbGYudG9nZ2xlKTtcbiAgICB9XG4gICAgY29sbGFwc2UgPSBnZXRUYXJnZXQoKTtcbiAgICBjb2xsYXBzZVtpc0FuaW1hdGluZ10gPSBmYWxzZTsgIC8vIHdoZW4gdHJ1ZSBpdCB3aWxsIHByZXZlbnQgY2xpY2sgaGFuZGxlcnMgIFxuICAgIGFjY29yZGlvbiA9IHF1ZXJ5RWxlbWVudChvcHRpb25zLnBhcmVudCkgfHwgYWNjb3JkaW9uRGF0YSAmJiBnZXRDbG9zZXN0KGVsZW1lbnQsIGFjY29yZGlvbkRhdGEpO1xuICAgIGVsZW1lbnRbc3RyaW5nQ29sbGFwc2VdID0gc2VsZjtcbiAgfTtcbiAgXG4gIC8vIENPTExBUFNFIERBVEEgQVBJXG4gIC8vID09PT09PT09PT09PT09PT09XG4gIHN1cHBvcnRzW3B1c2hdKCBbIHN0cmluZ0NvbGxhcHNlLCBDb2xsYXBzZSwgJ1snK2RhdGFUb2dnbGUrJz1cImNvbGxhcHNlXCJdJyBdICk7XG4gIFxuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgQWxlcnRcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBBTEVSVCBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PT1cbiAgdmFyIEFsZXJ0ID0gZnVuY3Rpb24oIGVsZW1lbnQgKSB7XG4gICAgXG4gICAgLy8gaW5pdGlhbGl6YXRpb24gZWxlbWVudFxuICAgIGVsZW1lbnQgPSBxdWVyeUVsZW1lbnQoZWxlbWVudCk7XG4gIFxuICAgIC8vIGJpbmQsIHRhcmdldCBhbGVydCwgZHVyYXRpb24gYW5kIHN0dWZmXG4gICAgdmFyIHNlbGYgPSB0aGlzLCBjb21wb25lbnQgPSAnYWxlcnQnLFxuICAgICAgYWxlcnQgPSBnZXRDbG9zZXN0KGVsZW1lbnQsJy4nK2NvbXBvbmVudCksXG4gICAgICB0cmlnZ2VySGFuZGxlciA9IGZ1bmN0aW9uKCl7IGhhc0NsYXNzKGFsZXJ0LCdmYWRlJykgPyBlbXVsYXRlVHJhbnNpdGlvbkVuZChhbGVydCx0cmFuc2l0aW9uRW5kSGFuZGxlcikgOiB0cmFuc2l0aW9uRW5kSGFuZGxlcigpOyB9LFxuICAgICAgLy8gaGFuZGxlcnNcbiAgICAgIGNsaWNrSGFuZGxlciA9IGZ1bmN0aW9uKGUpe1xuICAgICAgICBhbGVydCA9IGdldENsb3Nlc3QoZVt0YXJnZXRdLCcuJytjb21wb25lbnQpO1xuICAgICAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KCdbJytkYXRhRGlzbWlzcysnPVwiJytjb21wb25lbnQrJ1wiXScsYWxlcnQpO1xuICAgICAgICBlbGVtZW50ICYmIGFsZXJ0ICYmIChlbGVtZW50ID09PSBlW3RhcmdldF0gfHwgZWxlbWVudFtjb250YWluc10oZVt0YXJnZXRdKSkgJiYgc2VsZi5jbG9zZSgpO1xuICAgICAgfSxcbiAgICAgIHRyYW5zaXRpb25FbmRIYW5kbGVyID0gZnVuY3Rpb24oKXtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChhbGVydCwgY2xvc2VkRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIG9mZihlbGVtZW50LCBjbGlja0V2ZW50LCBjbGlja0hhbmRsZXIpOyAvLyBkZXRhY2ggaXQncyBsaXN0ZW5lclxuICAgICAgICBhbGVydFtwYXJlbnROb2RlXS5yZW1vdmVDaGlsZChhbGVydCk7XG4gICAgICB9O1xuICAgIFxuICAgIC8vIHB1YmxpYyBtZXRob2RcbiAgICB0aGlzLmNsb3NlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGFsZXJ0ICYmIGVsZW1lbnQgJiYgaGFzQ2xhc3MoYWxlcnQsaW5DbGFzcykgKSB7XG4gICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwoYWxlcnQsIGNsb3NlRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIHJlbW92ZUNsYXNzKGFsZXJ0LGluQ2xhc3MpO1xuICAgICAgICBhbGVydCAmJiB0cmlnZ2VySGFuZGxlcigpO1xuICAgICAgfVxuICAgIH07XG4gIFxuICAgIC8vIGluaXRcbiAgICBpZiAoICEoc3RyaW5nQWxlcnQgaW4gZWxlbWVudCApICkgeyAvLyBwcmV2ZW50IGFkZGluZyBldmVudCBoYW5kbGVycyB0d2ljZVxuICAgICAgb24oZWxlbWVudCwgY2xpY2tFdmVudCwgY2xpY2tIYW5kbGVyKTtcbiAgICB9XG4gICAgZWxlbWVudFtzdHJpbmdBbGVydF0gPSBzZWxmO1xuICB9O1xuICBcbiAgLy8gQUxFUlQgREFUQSBBUElcbiAgLy8gPT09PT09PT09PT09PT1cbiAgc3VwcG9ydHNbcHVzaF0oW3N0cmluZ0FsZXJ0LCBBbGVydCwgJ1snK2RhdGFEaXNtaXNzKyc9XCJhbGVydFwiXSddKTtcbiAgXG4gIFxuICBcbiAgXHJcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgSW5pdGlhbGl6ZSBEYXRhIEFQSVxyXG4gIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tKi9cclxuICB2YXIgaW5pdGlhbGl6ZURhdGFBUEkgPSBmdW5jdGlvbiggY29uc3RydWN0b3IsIGNvbGxlY3Rpb24gKXtcclxuICAgICAgZm9yICh2YXIgaT0wLCBsPWNvbGxlY3Rpb25bbGVuZ3RoXTsgaTxsOyBpKyspIHtcclxuICAgICAgICBuZXcgY29uc3RydWN0b3IoY29sbGVjdGlvbltpXSk7XHJcbiAgICAgIH1cclxuICAgIH0sXHJcbiAgICBpbml0Q2FsbGJhY2sgPSBCU04uaW5pdENhbGxiYWNrID0gZnVuY3Rpb24obG9va1VwKXtcclxuICAgICAgbG9va1VwID0gbG9va1VwIHx8IERPQztcclxuICAgICAgZm9yICh2YXIgaT0wLCBsPXN1cHBvcnRzW2xlbmd0aF07IGk8bDsgaSsrKSB7XHJcbiAgICAgICAgaW5pdGlhbGl6ZURhdGFBUEkoIHN1cHBvcnRzW2ldWzFdLCBsb29rVXBbcXVlcnlTZWxlY3RvckFsbF0gKHN1cHBvcnRzW2ldWzJdKSApO1xyXG4gICAgICB9XHJcbiAgICB9O1xyXG4gIFxyXG4gIC8vIGJ1bGsgaW5pdGlhbGl6ZSBhbGwgY29tcG9uZW50c1xyXG4gIERPQ1tib2R5XSA/IGluaXRDYWxsYmFjaygpIDogb24oIERPQywgJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbigpeyBpbml0Q2FsbGJhY2soKTsgfSApO1xyXG4gIFxuICByZXR1cm4ge1xuICAgIE1vZGFsOiBNb2RhbCxcbiAgICBDb2xsYXBzZTogQ29sbGFwc2UsXG4gICAgQWxlcnQ6IEFsZXJ0XG4gIH07XG59KSk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9ib290c3RyYXAubmF0aXZlL2Rpc3QvYm9vdHN0cmFwLW5hdGl2ZS5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvYm9vdHN0cmFwLm5hdGl2ZS9kaXN0L2Jvb3RzdHJhcC1uYXRpdmUuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLypcbiAqIGNsYXNzTGlzdC5qczogQ3Jvc3MtYnJvd3NlciBmdWxsIGVsZW1lbnQuY2xhc3NMaXN0IGltcGxlbWVudGF0aW9uLlxuICogMS4xLjIwMTcwNDI3XG4gKlxuICogQnkgRWxpIEdyZXksIGh0dHA6Ly9lbGlncmV5LmNvbVxuICogTGljZW5zZTogRGVkaWNhdGVkIHRvIHRoZSBwdWJsaWMgZG9tYWluLlxuICogICBTZWUgaHR0cHM6Ly9naXRodWIuY29tL2VsaWdyZXkvY2xhc3NMaXN0LmpzL2Jsb2IvbWFzdGVyL0xJQ0VOU0UubWRcbiAqL1xuXG4vKmdsb2JhbCBzZWxmLCBkb2N1bWVudCwgRE9NRXhjZXB0aW9uICovXG5cbi8qISBAc291cmNlIGh0dHA6Ly9wdXJsLmVsaWdyZXkuY29tL2dpdGh1Yi9jbGFzc0xpc3QuanMvYmxvYi9tYXN0ZXIvY2xhc3NMaXN0LmpzICovXG5cbmlmIChcImRvY3VtZW50XCIgaW4gd2luZG93LnNlbGYpIHtcblxuLy8gRnVsbCBwb2x5ZmlsbCBmb3IgYnJvd3NlcnMgd2l0aCBubyBjbGFzc0xpc3Qgc3VwcG9ydFxuLy8gSW5jbHVkaW5nIElFIDwgRWRnZSBtaXNzaW5nIFNWR0VsZW1lbnQuY2xhc3NMaXN0XG5pZiAoIShcImNsYXNzTGlzdFwiIGluIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJfXCIpKSBcblx0fHwgZG9jdW1lbnQuY3JlYXRlRWxlbWVudE5TICYmICEoXCJjbGFzc0xpc3RcIiBpbiBkb2N1bWVudC5jcmVhdGVFbGVtZW50TlMoXCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiLFwiZ1wiKSkpIHtcblxuKGZ1bmN0aW9uICh2aWV3KSB7XG5cblwidXNlIHN0cmljdFwiO1xuXG5pZiAoISgnRWxlbWVudCcgaW4gdmlldykpIHJldHVybjtcblxudmFyXG5cdCAgY2xhc3NMaXN0UHJvcCA9IFwiY2xhc3NMaXN0XCJcblx0LCBwcm90b1Byb3AgPSBcInByb3RvdHlwZVwiXG5cdCwgZWxlbUN0clByb3RvID0gdmlldy5FbGVtZW50W3Byb3RvUHJvcF1cblx0LCBvYmpDdHIgPSBPYmplY3Rcblx0LCBzdHJUcmltID0gU3RyaW5nW3Byb3RvUHJvcF0udHJpbSB8fCBmdW5jdGlvbiAoKSB7XG5cdFx0cmV0dXJuIHRoaXMucmVwbGFjZSgvXlxccyt8XFxzKyQvZywgXCJcIik7XG5cdH1cblx0LCBhcnJJbmRleE9mID0gQXJyYXlbcHJvdG9Qcm9wXS5pbmRleE9mIHx8IGZ1bmN0aW9uIChpdGVtKSB7XG5cdFx0dmFyXG5cdFx0XHQgIGkgPSAwXG5cdFx0XHQsIGxlbiA9IHRoaXMubGVuZ3RoXG5cdFx0O1xuXHRcdGZvciAoOyBpIDwgbGVuOyBpKyspIHtcblx0XHRcdGlmIChpIGluIHRoaXMgJiYgdGhpc1tpXSA9PT0gaXRlbSkge1xuXHRcdFx0XHRyZXR1cm4gaTtcblx0XHRcdH1cblx0XHR9XG5cdFx0cmV0dXJuIC0xO1xuXHR9XG5cdC8vIFZlbmRvcnM6IHBsZWFzZSBhbGxvdyBjb250ZW50IGNvZGUgdG8gaW5zdGFudGlhdGUgRE9NRXhjZXB0aW9uc1xuXHQsIERPTUV4ID0gZnVuY3Rpb24gKHR5cGUsIG1lc3NhZ2UpIHtcblx0XHR0aGlzLm5hbWUgPSB0eXBlO1xuXHRcdHRoaXMuY29kZSA9IERPTUV4Y2VwdGlvblt0eXBlXTtcblx0XHR0aGlzLm1lc3NhZ2UgPSBtZXNzYWdlO1xuXHR9XG5cdCwgY2hlY2tUb2tlbkFuZEdldEluZGV4ID0gZnVuY3Rpb24gKGNsYXNzTGlzdCwgdG9rZW4pIHtcblx0XHRpZiAodG9rZW4gPT09IFwiXCIpIHtcblx0XHRcdHRocm93IG5ldyBET01FeChcblx0XHRcdFx0ICBcIlNZTlRBWF9FUlJcIlxuXHRcdFx0XHQsIFwiQW4gaW52YWxpZCBvciBpbGxlZ2FsIHN0cmluZyB3YXMgc3BlY2lmaWVkXCJcblx0XHRcdCk7XG5cdFx0fVxuXHRcdGlmICgvXFxzLy50ZXN0KHRva2VuKSkge1xuXHRcdFx0dGhyb3cgbmV3IERPTUV4KFxuXHRcdFx0XHQgIFwiSU5WQUxJRF9DSEFSQUNURVJfRVJSXCJcblx0XHRcdFx0LCBcIlN0cmluZyBjb250YWlucyBhbiBpbnZhbGlkIGNoYXJhY3RlclwiXG5cdFx0XHQpO1xuXHRcdH1cblx0XHRyZXR1cm4gYXJySW5kZXhPZi5jYWxsKGNsYXNzTGlzdCwgdG9rZW4pO1xuXHR9XG5cdCwgQ2xhc3NMaXN0ID0gZnVuY3Rpb24gKGVsZW0pIHtcblx0XHR2YXJcblx0XHRcdCAgdHJpbW1lZENsYXNzZXMgPSBzdHJUcmltLmNhbGwoZWxlbS5nZXRBdHRyaWJ1dGUoXCJjbGFzc1wiKSB8fCBcIlwiKVxuXHRcdFx0LCBjbGFzc2VzID0gdHJpbW1lZENsYXNzZXMgPyB0cmltbWVkQ2xhc3Nlcy5zcGxpdCgvXFxzKy8pIDogW11cblx0XHRcdCwgaSA9IDBcblx0XHRcdCwgbGVuID0gY2xhc3Nlcy5sZW5ndGhcblx0XHQ7XG5cdFx0Zm9yICg7IGkgPCBsZW47IGkrKykge1xuXHRcdFx0dGhpcy5wdXNoKGNsYXNzZXNbaV0pO1xuXHRcdH1cblx0XHR0aGlzLl91cGRhdGVDbGFzc05hbWUgPSBmdW5jdGlvbiAoKSB7XG5cdFx0XHRlbGVtLnNldEF0dHJpYnV0ZShcImNsYXNzXCIsIHRoaXMudG9TdHJpbmcoKSk7XG5cdFx0fTtcblx0fVxuXHQsIGNsYXNzTGlzdFByb3RvID0gQ2xhc3NMaXN0W3Byb3RvUHJvcF0gPSBbXVxuXHQsIGNsYXNzTGlzdEdldHRlciA9IGZ1bmN0aW9uICgpIHtcblx0XHRyZXR1cm4gbmV3IENsYXNzTGlzdCh0aGlzKTtcblx0fVxuO1xuLy8gTW9zdCBET01FeGNlcHRpb24gaW1wbGVtZW50YXRpb25zIGRvbid0IGFsbG93IGNhbGxpbmcgRE9NRXhjZXB0aW9uJ3MgdG9TdHJpbmcoKVxuLy8gb24gbm9uLURPTUV4Y2VwdGlvbnMuIEVycm9yJ3MgdG9TdHJpbmcoKSBpcyBzdWZmaWNpZW50IGhlcmUuXG5ET01FeFtwcm90b1Byb3BdID0gRXJyb3JbcHJvdG9Qcm9wXTtcbmNsYXNzTGlzdFByb3RvLml0ZW0gPSBmdW5jdGlvbiAoaSkge1xuXHRyZXR1cm4gdGhpc1tpXSB8fCBudWxsO1xufTtcbmNsYXNzTGlzdFByb3RvLmNvbnRhaW5zID0gZnVuY3Rpb24gKHRva2VuKSB7XG5cdHRva2VuICs9IFwiXCI7XG5cdHJldHVybiBjaGVja1Rva2VuQW5kR2V0SW5kZXgodGhpcywgdG9rZW4pICE9PSAtMTtcbn07XG5jbGFzc0xpc3RQcm90by5hZGQgPSBmdW5jdGlvbiAoKSB7XG5cdHZhclxuXHRcdCAgdG9rZW5zID0gYXJndW1lbnRzXG5cdFx0LCBpID0gMFxuXHRcdCwgbCA9IHRva2Vucy5sZW5ndGhcblx0XHQsIHRva2VuXG5cdFx0LCB1cGRhdGVkID0gZmFsc2Vcblx0O1xuXHRkbyB7XG5cdFx0dG9rZW4gPSB0b2tlbnNbaV0gKyBcIlwiO1xuXHRcdGlmIChjaGVja1Rva2VuQW5kR2V0SW5kZXgodGhpcywgdG9rZW4pID09PSAtMSkge1xuXHRcdFx0dGhpcy5wdXNoKHRva2VuKTtcblx0XHRcdHVwZGF0ZWQgPSB0cnVlO1xuXHRcdH1cblx0fVxuXHR3aGlsZSAoKytpIDwgbCk7XG5cblx0aWYgKHVwZGF0ZWQpIHtcblx0XHR0aGlzLl91cGRhdGVDbGFzc05hbWUoKTtcblx0fVxufTtcbmNsYXNzTGlzdFByb3RvLnJlbW92ZSA9IGZ1bmN0aW9uICgpIHtcblx0dmFyXG5cdFx0ICB0b2tlbnMgPSBhcmd1bWVudHNcblx0XHQsIGkgPSAwXG5cdFx0LCBsID0gdG9rZW5zLmxlbmd0aFxuXHRcdCwgdG9rZW5cblx0XHQsIHVwZGF0ZWQgPSBmYWxzZVxuXHRcdCwgaW5kZXhcblx0O1xuXHRkbyB7XG5cdFx0dG9rZW4gPSB0b2tlbnNbaV0gKyBcIlwiO1xuXHRcdGluZGV4ID0gY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKTtcblx0XHR3aGlsZSAoaW5kZXggIT09IC0xKSB7XG5cdFx0XHR0aGlzLnNwbGljZShpbmRleCwgMSk7XG5cdFx0XHR1cGRhdGVkID0gdHJ1ZTtcblx0XHRcdGluZGV4ID0gY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKTtcblx0XHR9XG5cdH1cblx0d2hpbGUgKCsraSA8IGwpO1xuXG5cdGlmICh1cGRhdGVkKSB7XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lKCk7XG5cdH1cbn07XG5jbGFzc0xpc3RQcm90by50b2dnbGUgPSBmdW5jdGlvbiAodG9rZW4sIGZvcmNlKSB7XG5cdHRva2VuICs9IFwiXCI7XG5cblx0dmFyXG5cdFx0ICByZXN1bHQgPSB0aGlzLmNvbnRhaW5zKHRva2VuKVxuXHRcdCwgbWV0aG9kID0gcmVzdWx0ID9cblx0XHRcdGZvcmNlICE9PSB0cnVlICYmIFwicmVtb3ZlXCJcblx0XHQ6XG5cdFx0XHRmb3JjZSAhPT0gZmFsc2UgJiYgXCJhZGRcIlxuXHQ7XG5cblx0aWYgKG1ldGhvZCkge1xuXHRcdHRoaXNbbWV0aG9kXSh0b2tlbik7XG5cdH1cblxuXHRpZiAoZm9yY2UgPT09IHRydWUgfHwgZm9yY2UgPT09IGZhbHNlKSB7XG5cdFx0cmV0dXJuIGZvcmNlO1xuXHR9IGVsc2Uge1xuXHRcdHJldHVybiAhcmVzdWx0O1xuXHR9XG59O1xuY2xhc3NMaXN0UHJvdG8udG9TdHJpbmcgPSBmdW5jdGlvbiAoKSB7XG5cdHJldHVybiB0aGlzLmpvaW4oXCIgXCIpO1xufTtcblxuaWYgKG9iakN0ci5kZWZpbmVQcm9wZXJ0eSkge1xuXHR2YXIgY2xhc3NMaXN0UHJvcERlc2MgPSB7XG5cdFx0ICBnZXQ6IGNsYXNzTGlzdEdldHRlclxuXHRcdCwgZW51bWVyYWJsZTogdHJ1ZVxuXHRcdCwgY29uZmlndXJhYmxlOiB0cnVlXG5cdH07XG5cdHRyeSB7XG5cdFx0b2JqQ3RyLmRlZmluZVByb3BlcnR5KGVsZW1DdHJQcm90bywgY2xhc3NMaXN0UHJvcCwgY2xhc3NMaXN0UHJvcERlc2MpO1xuXHR9IGNhdGNoIChleCkgeyAvLyBJRSA4IGRvZXNuJ3Qgc3VwcG9ydCBlbnVtZXJhYmxlOnRydWVcblx0XHQvLyBhZGRpbmcgdW5kZWZpbmVkIHRvIGZpZ2h0IHRoaXMgaXNzdWUgaHR0cHM6Ly9naXRodWIuY29tL2VsaWdyZXkvY2xhc3NMaXN0LmpzL2lzc3Vlcy8zNlxuXHRcdC8vIG1vZGVybmllIElFOC1NU1c3IG1hY2hpbmUgaGFzIElFOCA4LjAuNjAwMS4xODcwMiBhbmQgaXMgYWZmZWN0ZWRcblx0XHRpZiAoZXgubnVtYmVyID09PSB1bmRlZmluZWQgfHwgZXgubnVtYmVyID09PSAtMHg3RkY1RUM1NCkge1xuXHRcdFx0Y2xhc3NMaXN0UHJvcERlc2MuZW51bWVyYWJsZSA9IGZhbHNlO1xuXHRcdFx0b2JqQ3RyLmRlZmluZVByb3BlcnR5KGVsZW1DdHJQcm90bywgY2xhc3NMaXN0UHJvcCwgY2xhc3NMaXN0UHJvcERlc2MpO1xuXHRcdH1cblx0fVxufSBlbHNlIGlmIChvYmpDdHJbcHJvdG9Qcm9wXS5fX2RlZmluZUdldHRlcl9fKSB7XG5cdGVsZW1DdHJQcm90by5fX2RlZmluZUdldHRlcl9fKGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdEdldHRlcik7XG59XG5cbn0od2luZG93LnNlbGYpKTtcblxufVxuXG4vLyBUaGVyZSBpcyBmdWxsIG9yIHBhcnRpYWwgbmF0aXZlIGNsYXNzTGlzdCBzdXBwb3J0LCBzbyBqdXN0IGNoZWNrIGlmIHdlIG5lZWRcbi8vIHRvIG5vcm1hbGl6ZSB0aGUgYWRkL3JlbW92ZSBhbmQgdG9nZ2xlIEFQSXMuXG5cbihmdW5jdGlvbiAoKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuXG5cdHZhciB0ZXN0RWxlbWVudCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJfXCIpO1xuXG5cdHRlc3RFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJjMVwiLCBcImMyXCIpO1xuXG5cdC8vIFBvbHlmaWxsIGZvciBJRSAxMC8xMSBhbmQgRmlyZWZveCA8MjYsIHdoZXJlIGNsYXNzTGlzdC5hZGQgYW5kXG5cdC8vIGNsYXNzTGlzdC5yZW1vdmUgZXhpc3QgYnV0IHN1cHBvcnQgb25seSBvbmUgYXJndW1lbnQgYXQgYSB0aW1lLlxuXHRpZiAoIXRlc3RFbGVtZW50LmNsYXNzTGlzdC5jb250YWlucyhcImMyXCIpKSB7XG5cdFx0dmFyIGNyZWF0ZU1ldGhvZCA9IGZ1bmN0aW9uKG1ldGhvZCkge1xuXHRcdFx0dmFyIG9yaWdpbmFsID0gRE9NVG9rZW5MaXN0LnByb3RvdHlwZVttZXRob2RdO1xuXG5cdFx0XHRET01Ub2tlbkxpc3QucHJvdG90eXBlW21ldGhvZF0gPSBmdW5jdGlvbih0b2tlbikge1xuXHRcdFx0XHR2YXIgaSwgbGVuID0gYXJndW1lbnRzLmxlbmd0aDtcblxuXHRcdFx0XHRmb3IgKGkgPSAwOyBpIDwgbGVuOyBpKyspIHtcblx0XHRcdFx0XHR0b2tlbiA9IGFyZ3VtZW50c1tpXTtcblx0XHRcdFx0XHRvcmlnaW5hbC5jYWxsKHRoaXMsIHRva2VuKTtcblx0XHRcdFx0fVxuXHRcdFx0fTtcblx0XHR9O1xuXHRcdGNyZWF0ZU1ldGhvZCgnYWRkJyk7XG5cdFx0Y3JlYXRlTWV0aG9kKCdyZW1vdmUnKTtcblx0fVxuXG5cdHRlc3RFbGVtZW50LmNsYXNzTGlzdC50b2dnbGUoXCJjM1wiLCBmYWxzZSk7XG5cblx0Ly8gUG9seWZpbGwgZm9yIElFIDEwIGFuZCBGaXJlZm94IDwyNCwgd2hlcmUgY2xhc3NMaXN0LnRvZ2dsZSBkb2VzIG5vdFxuXHQvLyBzdXBwb3J0IHRoZSBzZWNvbmQgYXJndW1lbnQuXG5cdGlmICh0ZXN0RWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoXCJjM1wiKSkge1xuXHRcdHZhciBfdG9nZ2xlID0gRE9NVG9rZW5MaXN0LnByb3RvdHlwZS50b2dnbGU7XG5cblx0XHRET01Ub2tlbkxpc3QucHJvdG90eXBlLnRvZ2dsZSA9IGZ1bmN0aW9uKHRva2VuLCBmb3JjZSkge1xuXHRcdFx0aWYgKDEgaW4gYXJndW1lbnRzICYmICF0aGlzLmNvbnRhaW5zKHRva2VuKSA9PT0gIWZvcmNlKSB7XG5cdFx0XHRcdHJldHVybiBmb3JjZTtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdHJldHVybiBfdG9nZ2xlLmNhbGwodGhpcywgdG9rZW4pO1xuXHRcdFx0fVxuXHRcdH07XG5cblx0fVxuXG5cdHRlc3RFbGVtZW50ID0gbnVsbDtcbn0oKSk7XG5cbn1cblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL2NsYXNzbGlzdC1wb2x5ZmlsbC9zcmMvaW5kZXguanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2NsYXNzbGlzdC1wb2x5ZmlsbC9zcmMvaW5kZXguanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLyohIHNtb290aC1zY3JvbGwgdjE0LjIuMCB8IChjKSAyMDE4IENocmlzIEZlcmRpbmFuZGkgfCBNSVQgTGljZW5zZSB8IGh0dHA6Ly9naXRodWIuY29tL2NmZXJkaW5hbmRpL3Ntb290aC1zY3JvbGwgKi9cbiEoZnVuY3Rpb24oZSx0KXtcImZ1bmN0aW9uXCI9PXR5cGVvZiBkZWZpbmUmJmRlZmluZS5hbWQ/ZGVmaW5lKFtdLChmdW5jdGlvbigpe3JldHVybiB0KGUpfSkpOlwib2JqZWN0XCI9PXR5cGVvZiBleHBvcnRzP21vZHVsZS5leHBvcnRzPXQoZSk6ZS5TbW9vdGhTY3JvbGw9dChlKX0pKFwidW5kZWZpbmVkXCIhPXR5cGVvZiBnbG9iYWw/Z2xvYmFsOlwidW5kZWZpbmVkXCIhPXR5cGVvZiB3aW5kb3c/d2luZG93OnRoaXMsKGZ1bmN0aW9uKGUpe1widXNlIHN0cmljdFwiO3ZhciB0PXtpZ25vcmU6XCJbZGF0YS1zY3JvbGwtaWdub3JlXVwiLGhlYWRlcjpudWxsLHRvcE9uRW1wdHlIYXNoOiEwLHNwZWVkOjUwMCxjbGlwOiEwLG9mZnNldDowLGVhc2luZzpcImVhc2VJbk91dEN1YmljXCIsY3VzdG9tRWFzaW5nOm51bGwsdXBkYXRlVVJMOiEwLHBvcHN0YXRlOiEwLGVtaXRFdmVudHM6ITB9LG49ZnVuY3Rpb24oKXtyZXR1cm5cInF1ZXJ5U2VsZWN0b3JcImluIGRvY3VtZW50JiZcImFkZEV2ZW50TGlzdGVuZXJcImluIGUmJlwicmVxdWVzdEFuaW1hdGlvbkZyYW1lXCJpbiBlJiZcImNsb3Nlc3RcImluIGUuRWxlbWVudC5wcm90b3R5cGV9LG89ZnVuY3Rpb24oKXtmb3IodmFyIGU9e30sdD0wO3Q8YXJndW1lbnRzLmxlbmd0aDt0KyspIShmdW5jdGlvbih0KXtmb3IodmFyIG4gaW4gdCl0Lmhhc093blByb3BlcnR5KG4pJiYoZVtuXT10W25dKX0pKGFyZ3VtZW50c1t0XSk7cmV0dXJuIGV9LHI9ZnVuY3Rpb24odCl7cmV0dXJuISEoXCJtYXRjaE1lZGlhXCJpbiBlJiZlLm1hdGNoTWVkaWEoXCIocHJlZmVycy1yZWR1Y2VkLW1vdGlvbilcIikubWF0Y2hlcyl9LGE9ZnVuY3Rpb24odCl7cmV0dXJuIHBhcnNlSW50KGUuZ2V0Q29tcHV0ZWRTdHlsZSh0KS5oZWlnaHQsMTApfSxpPWZ1bmN0aW9uKGUpe3ZhciB0O3RyeXt0PWRlY29kZVVSSUNvbXBvbmVudChlKX1jYXRjaChuKXt0PWV9cmV0dXJuIHR9LGM9ZnVuY3Rpb24oZSl7XCIjXCI9PT1lLmNoYXJBdCgwKSYmKGU9ZS5zdWJzdHIoMSkpO2Zvcih2YXIgdCxuPVN0cmluZyhlKSxvPW4ubGVuZ3RoLHI9LTEsYT1cIlwiLGk9bi5jaGFyQ29kZUF0KDApOysrcjxvOyl7aWYoMD09PSh0PW4uY2hhckNvZGVBdChyKSkpdGhyb3cgbmV3IEludmFsaWRDaGFyYWN0ZXJFcnJvcihcIkludmFsaWQgY2hhcmFjdGVyOiB0aGUgaW5wdXQgY29udGFpbnMgVSswMDAwLlwiKTt0Pj0xJiZ0PD0zMXx8MTI3PT10fHwwPT09ciYmdD49NDgmJnQ8PTU3fHwxPT09ciYmdD49NDgmJnQ8PTU3JiY0NT09PWk/YSs9XCJcXFxcXCIrdC50b1N0cmluZygxNikrXCIgXCI6YSs9dD49MTI4fHw0NT09PXR8fDk1PT09dHx8dD49NDgmJnQ8PTU3fHx0Pj02NSYmdDw9OTB8fHQ+PTk3JiZ0PD0xMjI/bi5jaGFyQXQocik6XCJcXFxcXCIrbi5jaGFyQXQocil9dmFyIGM7dHJ5e2M9ZGVjb2RlVVJJQ29tcG9uZW50KFwiI1wiK2EpfWNhdGNoKGUpe2M9XCIjXCIrYX1yZXR1cm4gY30sdT1mdW5jdGlvbihlLHQpe3ZhciBuO3JldHVyblwiZWFzZUluUXVhZFwiPT09ZS5lYXNpbmcmJihuPXQqdCksXCJlYXNlT3V0UXVhZFwiPT09ZS5lYXNpbmcmJihuPXQqKDItdCkpLFwiZWFzZUluT3V0UXVhZFwiPT09ZS5lYXNpbmcmJihuPXQ8LjU/Mip0KnQ6KDQtMip0KSp0LTEpLFwiZWFzZUluQ3ViaWNcIj09PWUuZWFzaW5nJiYobj10KnQqdCksXCJlYXNlT3V0Q3ViaWNcIj09PWUuZWFzaW5nJiYobj0tLXQqdCp0KzEpLFwiZWFzZUluT3V0Q3ViaWNcIj09PWUuZWFzaW5nJiYobj10PC41PzQqdCp0KnQ6KHQtMSkqKDIqdC0yKSooMip0LTIpKzEpLFwiZWFzZUluUXVhcnRcIj09PWUuZWFzaW5nJiYobj10KnQqdCp0KSxcImVhc2VPdXRRdWFydFwiPT09ZS5lYXNpbmcmJihuPTEtIC0tdCp0KnQqdCksXCJlYXNlSW5PdXRRdWFydFwiPT09ZS5lYXNpbmcmJihuPXQ8LjU/OCp0KnQqdCp0OjEtOCotLXQqdCp0KnQpLFwiZWFzZUluUXVpbnRcIj09PWUuZWFzaW5nJiYobj10KnQqdCp0KnQpLFwiZWFzZU91dFF1aW50XCI9PT1lLmVhc2luZyYmKG49MSstLXQqdCp0KnQqdCksXCJlYXNlSW5PdXRRdWludFwiPT09ZS5lYXNpbmcmJihuPXQ8LjU/MTYqdCp0KnQqdCp0OjErMTYqLS10KnQqdCp0KnQpLGUuY3VzdG9tRWFzaW5nJiYobj1lLmN1c3RvbUVhc2luZyh0KSksbnx8dH0scz1mdW5jdGlvbigpe3JldHVybiBNYXRoLm1heChkb2N1bWVudC5ib2R5LnNjcm9sbEhlaWdodCxkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2Nyb2xsSGVpZ2h0LGRvY3VtZW50LmJvZHkub2Zmc2V0SGVpZ2h0LGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5vZmZzZXRIZWlnaHQsZG9jdW1lbnQuYm9keS5jbGllbnRIZWlnaHQsZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudEhlaWdodCl9LGw9ZnVuY3Rpb24odCxuLG8scil7dmFyIGE9MDtpZih0Lm9mZnNldFBhcmVudClkb3thKz10Lm9mZnNldFRvcCx0PXQub2Zmc2V0UGFyZW50fXdoaWxlKHQpO3JldHVybiBhPU1hdGgubWF4KGEtbi1vLDApLHImJihhPU1hdGgubWluKGEscygpLWUuaW5uZXJIZWlnaHQpKSxhfSxkPWZ1bmN0aW9uKGUpe3JldHVybiBlP2EoZSkrZS5vZmZzZXRUb3A6MH0sZj1mdW5jdGlvbihlLHQsbil7dHx8aGlzdG9yeS5wdXNoU3RhdGUmJm4udXBkYXRlVVJMJiZoaXN0b3J5LnB1c2hTdGF0ZSh7c21vb3RoU2Nyb2xsOkpTT04uc3RyaW5naWZ5KG4pLGFuY2hvcjplLmlkfSxkb2N1bWVudC50aXRsZSxlPT09ZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50P1wiI3RvcFwiOlwiI1wiK2UuaWQpfSxtPWZ1bmN0aW9uKHQsbixvKXswPT09dCYmZG9jdW1lbnQuYm9keS5mb2N1cygpLG98fCh0LmZvY3VzKCksZG9jdW1lbnQuYWN0aXZlRWxlbWVudCE9PXQmJih0LnNldEF0dHJpYnV0ZShcInRhYmluZGV4XCIsXCItMVwiKSx0LmZvY3VzKCksdC5zdHlsZS5vdXRsaW5lPVwibm9uZVwiKSxlLnNjcm9sbFRvKDAsbikpfSxoPWZ1bmN0aW9uKHQsbixvLHIpe2lmKG4uZW1pdEV2ZW50cyYmXCJmdW5jdGlvblwiPT10eXBlb2YgZS5DdXN0b21FdmVudCl7dmFyIGE9bmV3IEN1c3RvbUV2ZW50KHQse2J1YmJsZXM6ITAsZGV0YWlsOnthbmNob3I6byx0b2dnbGU6cn19KTtkb2N1bWVudC5kaXNwYXRjaEV2ZW50KGEpfX07cmV0dXJuIGZ1bmN0aW9uKGEscCl7dmFyIGcsdix5LFMsRSxiLE8sST17fTtJLmNhbmNlbFNjcm9sbD1mdW5jdGlvbihlKXtjYW5jZWxBbmltYXRpb25GcmFtZShPKSxPPW51bGwsZXx8aChcInNjcm9sbENhbmNlbFwiLGcpfSxJLmFuaW1hdGVTY3JvbGw9ZnVuY3Rpb24obixyLGEpe3ZhciBpPW8oZ3x8dCxhfHx7fSksYz1cIltvYmplY3QgTnVtYmVyXVwiPT09T2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKG4pLHA9Y3x8IW4udGFnTmFtZT9udWxsOm47aWYoY3x8cCl7dmFyIHY9ZS5wYWdlWU9mZnNldDtpLmhlYWRlciYmIVMmJihTPWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoaS5oZWFkZXIpKSxFfHwoRT1kKFMpKTt2YXIgeSxiLEMsdz1jP246bChwLEUscGFyc2VJbnQoXCJmdW5jdGlvblwiPT10eXBlb2YgaS5vZmZzZXQ/aS5vZmZzZXQobixyKTppLm9mZnNldCwxMCksaS5jbGlwKSxMPXctdixBPXMoKSxIPTAscT1mdW5jdGlvbih0LG8pe3ZhciBhPWUucGFnZVlPZmZzZXQ7aWYodD09b3x8YT09b3x8KHY8byYmZS5pbm5lckhlaWdodCthKT49QSlyZXR1cm4gSS5jYW5jZWxTY3JvbGwoITApLG0obixvLGMpLGgoXCJzY3JvbGxTdG9wXCIsaSxuLHIpLHk9bnVsbCxPPW51bGwsITB9LFE9ZnVuY3Rpb24odCl7eXx8KHk9dCksSCs9dC15LGI9SC9wYXJzZUludChpLnNwZWVkLDEwKSxiPWI+MT8xOmIsQz12K0wqdShpLGIpLGUuc2Nyb2xsVG8oMCxNYXRoLmZsb29yKEMpKSxxKEMsdyl8fChPPWUucmVxdWVzdEFuaW1hdGlvbkZyYW1lKFEpLHk9dCl9OzA9PT1lLnBhZ2VZT2Zmc2V0JiZlLnNjcm9sbFRvKDAsMCksZihuLGMsaSksaChcInNjcm9sbFN0YXJ0XCIsaSxuLHIpLEkuY2FuY2VsU2Nyb2xsKCEwKSxlLnJlcXVlc3RBbmltYXRpb25GcmFtZShRKX19O3ZhciBDPWZ1bmN0aW9uKHQpe2lmKCFyKCkmJjA9PT10LmJ1dHRvbiYmIXQubWV0YUtleSYmIXQuY3RybEtleSYmXCJjbG9zZXN0XCJpbiB0LnRhcmdldCYmKHk9dC50YXJnZXQuY2xvc2VzdChhKSkmJlwiYVwiPT09eS50YWdOYW1lLnRvTG93ZXJDYXNlKCkmJiF0LnRhcmdldC5jbG9zZXN0KGcuaWdub3JlKSYmeS5ob3N0bmFtZT09PWUubG9jYXRpb24uaG9zdG5hbWUmJnkucGF0aG5hbWU9PT1lLmxvY2F0aW9uLnBhdGhuYW1lJiYvIy8udGVzdCh5LmhyZWYpKXt2YXIgbj1jKGkoeS5oYXNoKSksbz1nLnRvcE9uRW1wdHlIYXNoJiZcIiNcIj09PW4/ZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50OmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Iobik7bz1vfHxcIiN0b3BcIiE9PW4/bzpkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQsbyYmKHQucHJldmVudERlZmF1bHQoKSxJLmFuaW1hdGVTY3JvbGwobyx5KSl9fSx3PWZ1bmN0aW9uKGUpe2lmKGhpc3Rvcnkuc3RhdGUuc21vb3RoU2Nyb2xsJiZoaXN0b3J5LnN0YXRlLnNtb290aFNjcm9sbD09PUpTT04uc3RyaW5naWZ5KGcpJiZoaXN0b3J5LnN0YXRlLmFuY2hvcil7dmFyIHQ9ZG9jdW1lbnQucXVlcnlTZWxlY3RvcihjKGkoaGlzdG9yeS5zdGF0ZS5hbmNob3IpKSk7dCYmSS5hbmltYXRlU2Nyb2xsKHQsbnVsbCx7dXBkYXRlVVJMOiExfSl9fSxMPWZ1bmN0aW9uKGUpe2J8fChiPXNldFRpbWVvdXQoKGZ1bmN0aW9uKCl7Yj1udWxsLEU9ZChTKX0pLDY2KSl9O3JldHVybiBJLmRlc3Ryb3k9ZnVuY3Rpb24oKXtnJiYoZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsQywhMSksZS5yZW1vdmVFdmVudExpc3RlbmVyKFwicmVzaXplXCIsTCwhMSksZS5yZW1vdmVFdmVudExpc3RlbmVyKFwicG9wc3RhdGVcIix3LCExKSxJLmNhbmNlbFNjcm9sbCgpLGc9bnVsbCx2PW51bGwseT1udWxsLFM9bnVsbCxFPW51bGwsYj1udWxsLE89bnVsbCl9LEkuaW5pdD1mdW5jdGlvbihyKXtpZighbigpKXRocm93XCJTbW9vdGggU2Nyb2xsOiBUaGlzIGJyb3dzZXIgZG9lcyBub3Qgc3VwcG9ydCB0aGUgcmVxdWlyZWQgSmF2YVNjcmlwdCBtZXRob2RzIGFuZCBicm93c2VyIEFQSXMuXCI7SS5kZXN0cm95KCksZz1vKHQscnx8e30pLFM9Zy5oZWFkZXI/ZG9jdW1lbnQucXVlcnlTZWxlY3RvcihnLmhlYWRlcik6bnVsbCxFPWQoUyksZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsQywhMSksUyYmZS5hZGRFdmVudExpc3RlbmVyKFwicmVzaXplXCIsTCwhMSksZy51cGRhdGVVUkwmJmcucG9wc3RhdGUmJmUuYWRkRXZlbnRMaXN0ZW5lcihcInBvcHN0YXRlXCIsdywhMSl9LEkuaW5pdChwKSxJfX0pKTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9zbW9vdGgtc2Nyb2xsL2Rpc3Qvc21vb3RoLXNjcm9sbC5taW4uanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL3Ntb290aC1zY3JvbGwvZGlzdC9zbW9vdGgtc2Nyb2xsLm1pbi5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvKiFcclxuICAqIFN0aWNreWZpbGwg4oCTIGBwb3NpdGlvbjogc3RpY2t5YCBwb2x5ZmlsbFxyXG4gICogdi4gMi4wLjUgfCBodHRwczovL2dpdGh1Yi5jb20vd2lsZGRlZXIvc3RpY2t5ZmlsbFxyXG4gICogTUlUIExpY2Vuc2VcclxuICAqL1xyXG5cclxuOyhmdW5jdGlvbih3aW5kb3csIGRvY3VtZW50KSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgLypcclxuICAgICAqIDEuIENoZWNrIGlmIHRoZSBicm93c2VyIHN1cHBvcnRzIGBwb3NpdGlvbjogc3RpY2t5YCBuYXRpdmVseSBvciBpcyB0b28gb2xkIHRvIHJ1biB0aGUgcG9seWZpbGwuXHJcbiAgICAgKiAgICBJZiBlaXRoZXIgb2YgdGhlc2UgaXMgdGhlIGNhc2Ugc2V0IGBzZXBwdWt1YCBmbGFnLiBJdCB3aWxsIGJlIGNoZWNrZWQgbGF0ZXIgdG8gZGlzYWJsZSBrZXkgZmVhdHVyZXNcclxuICAgICAqICAgIG9mIHRoZSBwb2x5ZmlsbCwgYnV0IHRoZSBBUEkgd2lsbCByZW1haW4gZnVuY3Rpb25hbCB0byBhdm9pZCBicmVha2luZyB0aGluZ3MuXHJcbiAgICAgKi9cclxuXHJcbiAgICB2YXIgX2NyZWF0ZUNsYXNzID0gZnVuY3Rpb24gKCkgeyBmdW5jdGlvbiBkZWZpbmVQcm9wZXJ0aWVzKHRhcmdldCwgcHJvcHMpIHsgZm9yICh2YXIgaSA9IDA7IGkgPCBwcm9wcy5sZW5ndGg7IGkrKykgeyB2YXIgZGVzY3JpcHRvciA9IHByb3BzW2ldOyBkZXNjcmlwdG9yLmVudW1lcmFibGUgPSBkZXNjcmlwdG9yLmVudW1lcmFibGUgfHwgZmFsc2U7IGRlc2NyaXB0b3IuY29uZmlndXJhYmxlID0gdHJ1ZTsgaWYgKFwidmFsdWVcIiBpbiBkZXNjcmlwdG9yKSBkZXNjcmlwdG9yLndyaXRhYmxlID0gdHJ1ZTsgT2JqZWN0LmRlZmluZVByb3BlcnR5KHRhcmdldCwgZGVzY3JpcHRvci5rZXksIGRlc2NyaXB0b3IpOyB9IH0gcmV0dXJuIGZ1bmN0aW9uIChDb25zdHJ1Y3RvciwgcHJvdG9Qcm9wcywgc3RhdGljUHJvcHMpIHsgaWYgKHByb3RvUHJvcHMpIGRlZmluZVByb3BlcnRpZXMoQ29uc3RydWN0b3IucHJvdG90eXBlLCBwcm90b1Byb3BzKTsgaWYgKHN0YXRpY1Byb3BzKSBkZWZpbmVQcm9wZXJ0aWVzKENvbnN0cnVjdG9yLCBzdGF0aWNQcm9wcyk7IHJldHVybiBDb25zdHJ1Y3RvcjsgfTsgfSgpO1xyXG5cclxuICAgIGZ1bmN0aW9uIF9jbGFzc0NhbGxDaGVjayhpbnN0YW5jZSwgQ29uc3RydWN0b3IpIHsgaWYgKCEoaW5zdGFuY2UgaW5zdGFuY2VvZiBDb25zdHJ1Y3RvcikpIHsgdGhyb3cgbmV3IFR5cGVFcnJvcihcIkNhbm5vdCBjYWxsIGEgY2xhc3MgYXMgYSBmdW5jdGlvblwiKTsgfSB9XHJcblxyXG4gICAgdmFyIHNlcHB1a3UgPSBmYWxzZTtcclxuXHJcbiAgICAvLyBUaGUgcG9seWZpbGwgY2FudOKAmXQgZnVuY3Rpb24gcHJvcGVybHkgd2l0aG91dCBgZ2V0Q29tcHV0ZWRTdHlsZWAuXHJcbiAgICBpZiAoIXdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKSBzZXBwdWt1ID0gdHJ1ZTtcclxuICAgIC8vIERvbnTigJl0IGdldCBpbiBhIHdheSBpZiB0aGUgYnJvd3NlciBzdXBwb3J0cyBgcG9zaXRpb246IHN0aWNreWAgbmF0aXZlbHkuXHJcbiAgICBlbHNlIHtcclxuICAgICAgICAgICAgdmFyIHRlc3ROb2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcblxyXG4gICAgICAgICAgICBpZiAoWycnLCAnLXdlYmtpdC0nLCAnLW1vei0nLCAnLW1zLSddLnNvbWUoZnVuY3Rpb24gKHByZWZpeCkge1xyXG4gICAgICAgICAgICAgICAgdHJ5IHtcclxuICAgICAgICAgICAgICAgICAgICB0ZXN0Tm9kZS5zdHlsZS5wb3NpdGlvbiA9IHByZWZpeCArICdzdGlja3knO1xyXG4gICAgICAgICAgICAgICAgfSBjYXRjaCAoZSkge31cclxuXHJcbiAgICAgICAgICAgICAgICByZXR1cm4gdGVzdE5vZGUuc3R5bGUucG9zaXRpb24gIT0gJyc7XHJcbiAgICAgICAgICAgIH0pKSBzZXBwdWt1ID0gdHJ1ZTtcclxuICAgICAgICB9XHJcblxyXG4gICAgLypcclxuICAgICAqIDIuIOKAnEdsb2JhbOKAnSB2YXJzIHVzZWQgYWNyb3NzIHRoZSBwb2x5ZmlsbFxyXG4gICAgICovXHJcblxyXG4gICAgLy8gQ2hlY2sgaWYgU2hhZG93IFJvb3QgY29uc3RydWN0b3IgZXhpc3RzIHRvIG1ha2UgZnVydGhlciBjaGVja3Mgc2ltcGxlclxyXG4gICAgdmFyIHNoYWRvd1Jvb3RFeGlzdHMgPSB0eXBlb2YgU2hhZG93Um9vdCAhPT0gJ3VuZGVmaW5lZCc7XHJcblxyXG4gICAgLy8gTGFzdCBzYXZlZCBzY3JvbGwgcG9zaXRpb25cclxuICAgIHZhciBzY3JvbGwgPSB7XHJcbiAgICAgICAgdG9wOiBudWxsLFxyXG4gICAgICAgIGxlZnQ6IG51bGxcclxuICAgIH07XHJcblxyXG4gICAgLy8gQXJyYXkgb2YgY3JlYXRlZCBTdGlja3kgaW5zdGFuY2VzXHJcbiAgICB2YXIgc3RpY2tpZXMgPSBbXTtcclxuXHJcbiAgICAvKlxyXG4gICAgICogMy4gVXRpbGl0eSBmdW5jdGlvbnNcclxuICAgICAqL1xyXG4gICAgZnVuY3Rpb24gZXh0ZW5kKHRhcmdldE9iaiwgc291cmNlT2JqZWN0KSB7XHJcbiAgICAgICAgZm9yICh2YXIga2V5IGluIHNvdXJjZU9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAoc291cmNlT2JqZWN0Lmhhc093blByb3BlcnR5KGtleSkpIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldE9ialtrZXldID0gc291cmNlT2JqZWN0W2tleV07XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gcGFyc2VOdW1lcmljKHZhbCkge1xyXG4gICAgICAgIHJldHVybiBwYXJzZUZsb2F0KHZhbCkgfHwgMDtcclxuICAgIH1cclxuXHJcbiAgICBmdW5jdGlvbiBnZXREb2NPZmZzZXRUb3Aobm9kZSkge1xyXG4gICAgICAgIHZhciBkb2NPZmZzZXRUb3AgPSAwO1xyXG5cclxuICAgICAgICB3aGlsZSAobm9kZSkge1xyXG4gICAgICAgICAgICBkb2NPZmZzZXRUb3AgKz0gbm9kZS5vZmZzZXRUb3A7XHJcbiAgICAgICAgICAgIG5vZGUgPSBub2RlLm9mZnNldFBhcmVudDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHJldHVybiBkb2NPZmZzZXRUb3A7XHJcbiAgICB9XHJcblxyXG4gICAgLypcclxuICAgICAqIDQuIFN0aWNreSBjbGFzc1xyXG4gICAgICovXHJcblxyXG4gICAgdmFyIFN0aWNreSA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBmdW5jdGlvbiBTdGlja3kobm9kZSkge1xyXG4gICAgICAgICAgICBfY2xhc3NDYWxsQ2hlY2sodGhpcywgU3RpY2t5KTtcclxuXHJcbiAgICAgICAgICAgIGlmICghKG5vZGUgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkpIHRocm93IG5ldyBFcnJvcignRmlyc3QgYXJndW1lbnQgbXVzdCBiZSBIVE1MRWxlbWVudCcpO1xyXG4gICAgICAgICAgICBpZiAoc3RpY2tpZXMuc29tZShmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gc3RpY2t5Ll9ub2RlID09PSBub2RlO1xyXG4gICAgICAgICAgICB9KSkgdGhyb3cgbmV3IEVycm9yKCdTdGlja3lmaWxsIGlzIGFscmVhZHkgYXBwbGllZCB0byB0aGlzIG5vZGUnKTtcclxuXHJcbiAgICAgICAgICAgIHRoaXMuX25vZGUgPSBub2RlO1xyXG4gICAgICAgICAgICB0aGlzLl9zdGlja3lNb2RlID0gbnVsbDtcclxuICAgICAgICAgICAgdGhpcy5fYWN0aXZlID0gZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBzdGlja2llcy5wdXNoKHRoaXMpO1xyXG5cclxuICAgICAgICAgICAgdGhpcy5yZWZyZXNoKCk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBfY3JlYXRlQ2xhc3MoU3RpY2t5LCBbe1xyXG4gICAgICAgICAgICBrZXk6ICdyZWZyZXNoJyxcclxuICAgICAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHJlZnJlc2goKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoc2VwcHVrdSB8fCB0aGlzLl9yZW1vdmVkKSByZXR1cm47XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5fYWN0aXZlKSB0aGlzLl9kZWFjdGl2YXRlKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSB0aGlzLl9ub2RlO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiAxLiBTYXZlIG5vZGUgY29tcHV0ZWQgcHJvcHNcclxuICAgICAgICAgICAgICAgICAqL1xyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGVDb21wdXRlZFN0eWxlID0gZ2V0Q29tcHV0ZWRTdHlsZShub2RlKTtcclxuICAgICAgICAgICAgICAgIHZhciBub2RlQ29tcHV0ZWRQcm9wcyA9IHtcclxuICAgICAgICAgICAgICAgICAgICB0b3A6IG5vZGVDb21wdXRlZFN0eWxlLnRvcCxcclxuICAgICAgICAgICAgICAgICAgICBkaXNwbGF5OiBub2RlQ29tcHV0ZWRTdHlsZS5kaXNwbGF5LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogbm9kZUNvbXB1dGVkU3R5bGUubWFyZ2luVG9wLFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpbkJvdHRvbTogbm9kZUNvbXB1dGVkU3R5bGUubWFyZ2luQm90dG9tLFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IG5vZGVDb21wdXRlZFN0eWxlLm1hcmdpbkxlZnQsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luUmlnaHQ6IG5vZGVDb21wdXRlZFN0eWxlLm1hcmdpblJpZ2h0LFxyXG4gICAgICAgICAgICAgICAgICAgIGNzc0Zsb2F0OiBub2RlQ29tcHV0ZWRTdHlsZS5jc3NGbG9hdFxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogMi4gQ2hlY2sgaWYgdGhlIG5vZGUgY2FuIGJlIGFjdGl2YXRlZFxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICBpZiAoaXNOYU4ocGFyc2VGbG9hdChub2RlQ29tcHV0ZWRQcm9wcy50b3ApKSB8fCBub2RlQ29tcHV0ZWRQcm9wcy5kaXNwbGF5ID09ICd0YWJsZS1jZWxsJyB8fCBub2RlQ29tcHV0ZWRQcm9wcy5kaXNwbGF5ID09ICdub25lJykgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX2FjdGl2ZSA9IHRydWU7XHJcblxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDMuIEdldCBuZWNlc3Nhcnkgbm9kZSBwYXJhbWV0ZXJzXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHZhciByZWZlcmVuY2VOb2RlID0gbm9kZS5wYXJlbnROb2RlO1xyXG4gICAgICAgICAgICAgICAgdmFyIHBhcmVudE5vZGUgPSBzaGFkb3dSb290RXhpc3RzICYmIHJlZmVyZW5jZU5vZGUgaW5zdGFuY2VvZiBTaGFkb3dSb290ID8gcmVmZXJlbmNlTm9kZS5ob3N0IDogcmVmZXJlbmNlTm9kZTtcclxuICAgICAgICAgICAgICAgIHZhciBub2RlV2luT2Zmc2V0ID0gbm9kZS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnRXaW5PZmZzZXQgPSBwYXJlbnROb2RlLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xyXG4gICAgICAgICAgICAgICAgdmFyIHBhcmVudENvbXB1dGVkU3R5bGUgPSBnZXRDb21wdXRlZFN0eWxlKHBhcmVudE5vZGUpO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX3BhcmVudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICBub2RlOiBwYXJlbnROb2RlLFxyXG4gICAgICAgICAgICAgICAgICAgIHN0eWxlczoge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogcGFyZW50Tm9kZS5zdHlsZS5wb3NpdGlvblxyXG4gICAgICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb2Zmc2V0SGVpZ2h0OiBwYXJlbnROb2RlLm9mZnNldEhlaWdodFxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX29mZnNldFRvV2luZG93ID0ge1xyXG4gICAgICAgICAgICAgICAgICAgIGxlZnQ6IG5vZGVXaW5PZmZzZXQubGVmdCxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDogZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudFdpZHRoIC0gbm9kZVdpbk9mZnNldC5yaWdodFxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX29mZnNldFRvUGFyZW50ID0ge1xyXG4gICAgICAgICAgICAgICAgICAgIHRvcDogbm9kZVdpbk9mZnNldC50b3AgLSBwYXJlbnRXaW5PZmZzZXQudG9wIC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyVG9wV2lkdGgpLFxyXG4gICAgICAgICAgICAgICAgICAgIGxlZnQ6IG5vZGVXaW5PZmZzZXQubGVmdCAtIHBhcmVudFdpbk9mZnNldC5sZWZ0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyTGVmdFdpZHRoKSxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDogLW5vZGVXaW5PZmZzZXQucmlnaHQgKyBwYXJlbnRXaW5PZmZzZXQucmlnaHQgLSBwYXJzZU51bWVyaWMocGFyZW50Q29tcHV0ZWRTdHlsZS5ib3JkZXJSaWdodFdpZHRoKVxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX3N0eWxlcyA9IHtcclxuICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogbm9kZS5zdHlsZS5wb3NpdGlvbixcclxuICAgICAgICAgICAgICAgICAgICB0b3A6IG5vZGUuc3R5bGUudG9wLFxyXG4gICAgICAgICAgICAgICAgICAgIGJvdHRvbTogbm9kZS5zdHlsZS5ib3R0b20sXHJcbiAgICAgICAgICAgICAgICAgICAgbGVmdDogbm9kZS5zdHlsZS5sZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIHJpZ2h0OiBub2RlLnN0eWxlLnJpZ2h0LFxyXG4gICAgICAgICAgICAgICAgICAgIHdpZHRoOiBub2RlLnN0eWxlLndpZHRoLFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogbm9kZS5zdHlsZS5tYXJnaW5Ub3AsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogbm9kZS5zdHlsZS5tYXJnaW5MZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiBub2RlLnN0eWxlLm1hcmdpblJpZ2h0XHJcbiAgICAgICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgICAgIHZhciBub2RlVG9wVmFsdWUgPSBwYXJzZU51bWVyaWMobm9kZUNvbXB1dGVkUHJvcHMudG9wKTtcclxuICAgICAgICAgICAgICAgIHRoaXMuX2xpbWl0cyA9IHtcclxuICAgICAgICAgICAgICAgICAgICBzdGFydDogbm9kZVdpbk9mZnNldC50b3AgKyB3aW5kb3cucGFnZVlPZmZzZXQgLSBub2RlVG9wVmFsdWUsXHJcbiAgICAgICAgICAgICAgICAgICAgZW5kOiBwYXJlbnRXaW5PZmZzZXQudG9wICsgd2luZG93LnBhZ2VZT2Zmc2V0ICsgcGFyZW50Tm9kZS5vZmZzZXRIZWlnaHQgLSBwYXJzZU51bWVyaWMocGFyZW50Q29tcHV0ZWRTdHlsZS5ib3JkZXJCb3R0b21XaWR0aCkgLSBub2RlLm9mZnNldEhlaWdodCAtIG5vZGVUb3BWYWx1ZSAtIHBhcnNlTnVtZXJpYyhub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5Cb3R0b20pXHJcbiAgICAgICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiA0LiBFbnN1cmUgdGhhdCB0aGUgbm9kZSB3aWxsIGJlIHBvc2l0aW9uZWQgcmVsYXRpdmVseSB0byB0aGUgcGFyZW50IG5vZGVcclxuICAgICAgICAgICAgICAgICAqL1xyXG4gICAgICAgICAgICAgICAgdmFyIHBhcmVudFBvc2l0aW9uID0gcGFyZW50Q29tcHV0ZWRTdHlsZS5wb3NpdGlvbjtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAocGFyZW50UG9zaXRpb24gIT0gJ2Fic29sdXRlJyAmJiBwYXJlbnRQb3NpdGlvbiAhPSAncmVsYXRpdmUnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcGFyZW50Tm9kZS5zdHlsZS5wb3NpdGlvbiA9ICdyZWxhdGl2ZSc7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDUuIFJlY2FsYyBub2RlIHBvc2l0aW9uLlxyXG4gICAgICAgICAgICAgICAgICogICAgSXTigJlzIGltcG9ydGFudCB0byBkbyB0aGlzIGJlZm9yZSBjbG9uZSBpbmplY3Rpb24gdG8gYXZvaWQgc2Nyb2xsaW5nIGJ1ZyBpbiBDaHJvbWUuXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuX3JlY2FsY1Bvc2l0aW9uKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDYuIENyZWF0ZSBhIGNsb25lXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHZhciBjbG9uZSA9IHRoaXMuX2Nsb25lID0ge307XHJcbiAgICAgICAgICAgICAgICBjbG9uZS5ub2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gQXBwbHkgc3R5bGVzIHRvIHRoZSBjbG9uZVxyXG4gICAgICAgICAgICAgICAgZXh0ZW5kKGNsb25lLm5vZGUuc3R5bGUsIHtcclxuICAgICAgICAgICAgICAgICAgICB3aWR0aDogbm9kZVdpbk9mZnNldC5yaWdodCAtIG5vZGVXaW5PZmZzZXQubGVmdCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiBub2RlV2luT2Zmc2V0LmJvdHRvbSAtIG5vZGVXaW5PZmZzZXQudG9wICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IG5vZGVDb21wdXRlZFByb3BzLm1hcmdpblRvcCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Cb3R0b206IG5vZGVDb21wdXRlZFByb3BzLm1hcmdpbkJvdHRvbSxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5MZWZ0OiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5MZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5SaWdodCxcclxuICAgICAgICAgICAgICAgICAgICBjc3NGbG9hdDogbm9kZUNvbXB1dGVkUHJvcHMuY3NzRmxvYXQsXHJcbiAgICAgICAgICAgICAgICAgICAgcGFkZGluZzogMCxcclxuICAgICAgICAgICAgICAgICAgICBib3JkZXI6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgYm9yZGVyU3BhY2luZzogMCxcclxuICAgICAgICAgICAgICAgICAgICBmb250U2l6ZTogJzFlbScsXHJcbiAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246ICdzdGF0aWMnXHJcbiAgICAgICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgICAgICByZWZlcmVuY2VOb2RlLmluc2VydEJlZm9yZShjbG9uZS5ub2RlLCBub2RlKTtcclxuICAgICAgICAgICAgICAgIGNsb25lLmRvY09mZnNldFRvcCA9IGdldERvY09mZnNldFRvcChjbG9uZS5ub2RlKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIHtcclxuICAgICAgICAgICAga2V5OiAnX3JlY2FsY1Bvc2l0aW9uJyxcclxuICAgICAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIF9yZWNhbGNQb3NpdGlvbigpIHtcclxuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fYWN0aXZlIHx8IHRoaXMuX3JlbW92ZWQpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICB2YXIgc3RpY2t5TW9kZSA9IHNjcm9sbC50b3AgPD0gdGhpcy5fbGltaXRzLnN0YXJ0ID8gJ3N0YXJ0JyA6IHNjcm9sbC50b3AgPj0gdGhpcy5fbGltaXRzLmVuZCA/ICdlbmQnIDogJ21pZGRsZSc7XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuX3N0aWNreU1vZGUgPT0gc3RpY2t5TW9kZSkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgICAgIHN3aXRjaCAoc3RpY2t5TW9kZSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ3N0YXJ0JzpcclxuICAgICAgICAgICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX25vZGUuc3R5bGUsIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnYWJzb2x1dGUnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQubGVmdCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByaWdodDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQucmlnaHQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiB0aGlzLl9vZmZzZXRUb1BhcmVudC50b3AgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYm90dG9tOiAnYXV0bycsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aWR0aDogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiAwLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luVG9wOiAwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnbWlkZGxlJzpcclxuICAgICAgICAgICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX25vZGUuc3R5bGUsIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnZml4ZWQnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogdGhpcy5fb2Zmc2V0VG9XaW5kb3cubGVmdCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByaWdodDogdGhpcy5fb2Zmc2V0VG9XaW5kb3cucmlnaHQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiB0aGlzLl9zdHlsZXMudG9wLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYm90dG9tOiAnYXV0bycsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aWR0aDogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiAwLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luVG9wOiAwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnZW5kJzpcclxuICAgICAgICAgICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX25vZGUuc3R5bGUsIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnYWJzb2x1dGUnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQubGVmdCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByaWdodDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQucmlnaHQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiAnYXV0bycsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBib3R0b206IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aWR0aDogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiAwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9zdGlja3lNb2RlID0gc3RpY2t5TW9kZTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIHtcclxuICAgICAgICAgICAga2V5OiAnX2Zhc3RDaGVjaycsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiBfZmFzdENoZWNrKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKCF0aGlzLl9hY3RpdmUgfHwgdGhpcy5fcmVtb3ZlZCkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChNYXRoLmFicyhnZXREb2NPZmZzZXRUb3AodGhpcy5fY2xvbmUubm9kZSkgLSB0aGlzLl9jbG9uZS5kb2NPZmZzZXRUb3ApID4gMSB8fCBNYXRoLmFicyh0aGlzLl9wYXJlbnQubm9kZS5vZmZzZXRIZWlnaHQgLSB0aGlzLl9wYXJlbnQub2Zmc2V0SGVpZ2h0KSA+IDEpIHRoaXMucmVmcmVzaCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSwge1xyXG4gICAgICAgICAgICBrZXk6ICdfZGVhY3RpdmF0ZScsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiBfZGVhY3RpdmF0ZSgpIHtcclxuICAgICAgICAgICAgICAgIHZhciBfdGhpcyA9IHRoaXM7XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKCF0aGlzLl9hY3RpdmUgfHwgdGhpcy5fcmVtb3ZlZCkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX2Nsb25lLm5vZGUucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh0aGlzLl9jbG9uZS5ub2RlKTtcclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9jbG9uZTtcclxuXHJcbiAgICAgICAgICAgICAgICBleHRlbmQodGhpcy5fbm9kZS5zdHlsZSwgdGhpcy5fc3R5bGVzKTtcclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9zdHlsZXM7XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gQ2hlY2sgd2hldGhlciBlbGVtZW504oCZcyBwYXJlbnQgbm9kZSBpcyB1c2VkIGJ5IG90aGVyIHN0aWNraWVzLlxyXG4gICAgICAgICAgICAgICAgLy8gSWYgbm90LCByZXN0b3JlIHBhcmVudCBub2Rl4oCZcyBzdHlsZXMuXHJcbiAgICAgICAgICAgICAgICBpZiAoIXN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBzdGlja3kgIT09IF90aGlzICYmIHN0aWNreS5fcGFyZW50ICYmIHN0aWNreS5fcGFyZW50Lm5vZGUgPT09IF90aGlzLl9wYXJlbnQubm9kZTtcclxuICAgICAgICAgICAgICAgIH0pKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX3BhcmVudC5ub2RlLnN0eWxlLCB0aGlzLl9wYXJlbnQuc3R5bGVzKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGRlbGV0ZSB0aGlzLl9wYXJlbnQ7XHJcblxyXG4gICAgICAgICAgICAgICAgdGhpcy5fc3RpY2t5TW9kZSA9IG51bGw7XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fb2Zmc2V0VG9XaW5kb3c7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fb2Zmc2V0VG9QYXJlbnQ7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fbGltaXRzO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSwge1xyXG4gICAgICAgICAgICBrZXk6ICdyZW1vdmUnLFxyXG4gICAgICAgICAgICB2YWx1ZTogZnVuY3Rpb24gcmVtb3ZlKCkge1xyXG4gICAgICAgICAgICAgICAgdmFyIF90aGlzMiA9IHRoaXM7XHJcblxyXG4gICAgICAgICAgICAgICAgdGhpcy5fZGVhY3RpdmF0ZSgpO1xyXG5cclxuICAgICAgICAgICAgICAgIHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSwgaW5kZXgpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoc3RpY2t5Ll9ub2RlID09PSBfdGhpczIuX25vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgc3RpY2tpZXMuc3BsaWNlKGluZGV4LCAxKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICAgICAgdGhpcy5fcmVtb3ZlZCA9IHRydWU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XSk7XHJcblxyXG4gICAgICAgIHJldHVybiBTdGlja3k7XHJcbiAgICB9KCk7XHJcblxyXG4gICAgLypcclxuICAgICAqIDUuIFN0aWNreWZpbGwgQVBJXHJcbiAgICAgKi9cclxuXHJcblxyXG4gICAgdmFyIFN0aWNreWZpbGwgPSB7XHJcbiAgICAgICAgc3RpY2tpZXM6IHN0aWNraWVzLFxyXG4gICAgICAgIFN0aWNreTogU3RpY2t5LFxyXG5cclxuICAgICAgICBhZGRPbmU6IGZ1bmN0aW9uIGFkZE9uZShub2RlKSB7XHJcbiAgICAgICAgICAgIC8vIENoZWNrIHdoZXRoZXIgaXTigJlzIGEgbm9kZVxyXG4gICAgICAgICAgICBpZiAoIShub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpKSB7XHJcbiAgICAgICAgICAgICAgICAvLyBNYXliZSBpdOKAmXMgYSBub2RlIGxpc3Qgb2Ygc29tZSBzb3J0P1xyXG4gICAgICAgICAgICAgICAgLy8gVGFrZSBmaXJzdCBub2RlIGZyb20gdGhlIGxpc3QgdGhlblxyXG4gICAgICAgICAgICAgICAgaWYgKG5vZGUubGVuZ3RoICYmIG5vZGVbMF0pIG5vZGUgPSBub2RlWzBdO2Vsc2UgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAvLyBDaGVjayBpZiBTdGlja3lmaWxsIGlzIGFscmVhZHkgYXBwbGllZCB0byB0aGUgbm9kZVxyXG4gICAgICAgICAgICAvLyBhbmQgcmV0dXJuIGV4aXN0aW5nIHN0aWNreVxyXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IHN0aWNraWVzLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoc3RpY2tpZXNbaV0uX25vZGUgPT09IG5vZGUpIHJldHVybiBzdGlja2llc1tpXTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gQ3JlYXRlIGFuZCByZXR1cm4gbmV3IHN0aWNreVxyXG4gICAgICAgICAgICByZXR1cm4gbmV3IFN0aWNreShub2RlKTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGFkZDogZnVuY3Rpb24gYWRkKG5vZGVMaXN0KSB7XHJcbiAgICAgICAgICAgIC8vIElmIGl04oCZcyBhIG5vZGUgbWFrZSBhbiBhcnJheSBvZiBvbmUgbm9kZVxyXG4gICAgICAgICAgICBpZiAobm9kZUxpc3QgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgbm9kZUxpc3QgPSBbbm9kZUxpc3RdO1xyXG4gICAgICAgICAgICAvLyBDaGVjayBpZiB0aGUgYXJndW1lbnQgaXMgYW4gaXRlcmFibGUgb2Ygc29tZSBzb3J0XHJcbiAgICAgICAgICAgIGlmICghbm9kZUxpc3QubGVuZ3RoKSByZXR1cm47XHJcblxyXG4gICAgICAgICAgICAvLyBBZGQgZXZlcnkgZWxlbWVudCBhcyBhIHN0aWNreSBhbmQgcmV0dXJuIGFuIGFycmF5IG9mIGNyZWF0ZWQgU3RpY2t5IGluc3RhbmNlc1xyXG4gICAgICAgICAgICB2YXIgYWRkZWRTdGlja2llcyA9IFtdO1xyXG5cclxuICAgICAgICAgICAgdmFyIF9sb29wID0gZnVuY3Rpb24gX2xvb3AoaSkge1xyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSBub2RlTGlzdFtpXTtcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBJZiBpdOKAmXMgbm90IGFuIEhUTUxFbGVtZW50IOKAkyBjcmVhdGUgYW4gZW1wdHkgZWxlbWVudCB0byBwcmVzZXJ2ZSAxLXRvLTFcclxuICAgICAgICAgICAgICAgIC8vIGNvcnJlbGF0aW9uIHdpdGggaW5wdXQgbGlzdFxyXG4gICAgICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGFkZGVkU3RpY2tpZXMucHVzaCh2b2lkIDApO1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnY29udGludWUnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIC8vIElmIFN0aWNreWZpbGwgaXMgYWxyZWFkeSBhcHBsaWVkIHRvIHRoZSBub2RlXHJcbiAgICAgICAgICAgICAgICAvLyBhZGQgZXhpc3Rpbmcgc3RpY2t5XHJcbiAgICAgICAgICAgICAgICBpZiAoc3RpY2tpZXMuc29tZShmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHN0aWNreS5fbm9kZSA9PT0gbm9kZSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhZGRlZFN0aWNraWVzLnB1c2goc3RpY2t5KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSkpIHJldHVybiAnY29udGludWUnO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIENyZWF0ZSBhbmQgYWRkIG5ldyBzdGlja3lcclxuICAgICAgICAgICAgICAgIGFkZGVkU3RpY2tpZXMucHVzaChuZXcgU3RpY2t5KG5vZGUpKTtcclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbm9kZUxpc3QubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgIHZhciBfcmV0ID0gX2xvb3AoaSk7XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKF9yZXQgPT09ICdjb250aW51ZScpIGNvbnRpbnVlO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICByZXR1cm4gYWRkZWRTdGlja2llcztcclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlZnJlc2hBbGw6IGZ1bmN0aW9uIHJlZnJlc2hBbGwoKSB7XHJcbiAgICAgICAgICAgIHN0aWNraWVzLmZvckVhY2goZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5yZWZyZXNoKCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgcmVtb3ZlT25lOiBmdW5jdGlvbiByZW1vdmVPbmUobm9kZSkge1xyXG4gICAgICAgICAgICAvLyBDaGVjayB3aGV0aGVyIGl04oCZcyBhIG5vZGVcclxuICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkge1xyXG4gICAgICAgICAgICAgICAgLy8gTWF5YmUgaXTigJlzIGEgbm9kZSBsaXN0IG9mIHNvbWUgc29ydD9cclxuICAgICAgICAgICAgICAgIC8vIFRha2UgZmlyc3Qgbm9kZSBmcm9tIHRoZSBsaXN0IHRoZW5cclxuICAgICAgICAgICAgICAgIGlmIChub2RlLmxlbmd0aCAmJiBub2RlWzBdKSBub2RlID0gbm9kZVswXTtlbHNlIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBzdGlja2llcyBib3VuZCB0byB0aGUgbm9kZXMgaW4gdGhlIGxpc3RcclxuICAgICAgICAgICAgc3RpY2tpZXMuc29tZShmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoc3RpY2t5Ll9ub2RlID09PSBub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgc3RpY2t5LnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZTogZnVuY3Rpb24gcmVtb3ZlKG5vZGVMaXN0KSB7XHJcbiAgICAgICAgICAgIC8vIElmIGl04oCZcyBhIG5vZGUgbWFrZSBhbiBhcnJheSBvZiBvbmUgbm9kZVxyXG4gICAgICAgICAgICBpZiAobm9kZUxpc3QgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgbm9kZUxpc3QgPSBbbm9kZUxpc3RdO1xyXG4gICAgICAgICAgICAvLyBDaGVjayBpZiB0aGUgYXJndW1lbnQgaXMgYW4gaXRlcmFibGUgb2Ygc29tZSBzb3J0XHJcbiAgICAgICAgICAgIGlmICghbm9kZUxpc3QubGVuZ3RoKSByZXR1cm47XHJcblxyXG4gICAgICAgICAgICAvLyBSZW1vdmUgdGhlIHN0aWNraWVzIGJvdW5kIHRvIHRoZSBub2RlcyBpbiB0aGUgbGlzdFxyXG5cclxuICAgICAgICAgICAgdmFyIF9sb29wMiA9IGZ1bmN0aW9uIF9sb29wMihpKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZSA9IG5vZGVMaXN0W2ldO1xyXG5cclxuICAgICAgICAgICAgICAgIHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChzdGlja3kuX25vZGUgPT09IG5vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgc3RpY2t5LnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbm9kZUxpc3QubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgIF9sb29wMihpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgcmVtb3ZlQWxsOiBmdW5jdGlvbiByZW1vdmVBbGwoKSB7XHJcbiAgICAgICAgICAgIHdoaWxlIChzdGlja2llcy5sZW5ndGgpIHtcclxuICAgICAgICAgICAgICAgIHN0aWNraWVzWzBdLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICAvKlxyXG4gICAgICogNi4gU2V0dXAgZXZlbnRzICh1bmxlc3MgdGhlIHBvbHlmaWxsIHdhcyBkaXNhYmxlZClcclxuICAgICAqL1xyXG4gICAgZnVuY3Rpb24gaW5pdCgpIHtcclxuICAgICAgICAvLyBXYXRjaCBmb3Igc2Nyb2xsIHBvc2l0aW9uIGNoYW5nZXMgYW5kIHRyaWdnZXIgcmVjYWxjL3JlZnJlc2ggaWYgbmVlZGVkXHJcbiAgICAgICAgZnVuY3Rpb24gY2hlY2tTY3JvbGwoKSB7XHJcbiAgICAgICAgICAgIGlmICh3aW5kb3cucGFnZVhPZmZzZXQgIT0gc2Nyb2xsLmxlZnQpIHtcclxuICAgICAgICAgICAgICAgIHNjcm9sbC50b3AgPSB3aW5kb3cucGFnZVlPZmZzZXQ7XHJcbiAgICAgICAgICAgICAgICBzY3JvbGwubGVmdCA9IHdpbmRvdy5wYWdlWE9mZnNldDtcclxuXHJcbiAgICAgICAgICAgICAgICBTdGlja3lmaWxsLnJlZnJlc2hBbGwoKTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmICh3aW5kb3cucGFnZVlPZmZzZXQgIT0gc2Nyb2xsLnRvcCkge1xyXG4gICAgICAgICAgICAgICAgc2Nyb2xsLnRvcCA9IHdpbmRvdy5wYWdlWU9mZnNldDtcclxuICAgICAgICAgICAgICAgIHNjcm9sbC5sZWZ0ID0gd2luZG93LnBhZ2VYT2Zmc2V0O1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIHJlY2FsYyBwb3NpdGlvbiBmb3IgYWxsIHN0aWNraWVzXHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5mb3JFYWNoKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc3RpY2t5Ll9yZWNhbGNQb3NpdGlvbigpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGNoZWNrU2Nyb2xsKCk7XHJcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGNoZWNrU2Nyb2xsKTtcclxuXHJcbiAgICAgICAgLy8gV2F0Y2ggZm9yIHdpbmRvdyByZXNpemVzIGFuZCBkZXZpY2Ugb3JpZW50YXRpb24gY2hhbmdlcyBhbmQgdHJpZ2dlciByZWZyZXNoXHJcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIFN0aWNreWZpbGwucmVmcmVzaEFsbCk7XHJcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ29yaWVudGF0aW9uY2hhbmdlJywgU3RpY2t5ZmlsbC5yZWZyZXNoQWxsKTtcclxuXHJcbiAgICAgICAgLy9GYXN0IGRpcnR5IGNoZWNrIGZvciBsYXlvdXQgY2hhbmdlcyBldmVyeSA1MDBtc1xyXG4gICAgICAgIHZhciBmYXN0Q2hlY2tUaW1lciA9IHZvaWQgMDtcclxuXHJcbiAgICAgICAgZnVuY3Rpb24gc3RhcnRGYXN0Q2hlY2tUaW1lcigpIHtcclxuICAgICAgICAgICAgZmFzdENoZWNrVGltZXIgPSBzZXRJbnRlcnZhbChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5mb3JFYWNoKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc3RpY2t5Ll9mYXN0Q2hlY2soKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9LCA1MDApO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gc3RvcEZhc3RDaGVja1RpbWVyKCkge1xyXG4gICAgICAgICAgICBjbGVhckludGVydmFsKGZhc3RDaGVja1RpbWVyKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHZhciBkb2NIaWRkZW5LZXkgPSB2b2lkIDA7XHJcbiAgICAgICAgdmFyIHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUgPSB2b2lkIDA7XHJcblxyXG4gICAgICAgIGlmICgnaGlkZGVuJyBpbiBkb2N1bWVudCkge1xyXG4gICAgICAgICAgICBkb2NIaWRkZW5LZXkgPSAnaGlkZGVuJztcclxuICAgICAgICAgICAgdmlzaWJpbGl0eUNoYW5nZUV2ZW50TmFtZSA9ICd2aXNpYmlsaXR5Y2hhbmdlJztcclxuICAgICAgICB9IGVsc2UgaWYgKCd3ZWJraXRIaWRkZW4nIGluIGRvY3VtZW50KSB7XHJcbiAgICAgICAgICAgIGRvY0hpZGRlbktleSA9ICd3ZWJraXRIaWRkZW4nO1xyXG4gICAgICAgICAgICB2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lID0gJ3dlYmtpdHZpc2liaWxpdHljaGFuZ2UnO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUpIHtcclxuICAgICAgICAgICAgaWYgKCFkb2N1bWVudFtkb2NIaWRkZW5LZXldKSBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcblxyXG4gICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGlmIChkb2N1bWVudFtkb2NIaWRkZW5LZXldKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgc3RvcEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIHN0YXJ0RmFzdENoZWNrVGltZXIoKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSBlbHNlIHN0YXJ0RmFzdENoZWNrVGltZXIoKTtcclxuICAgIH1cclxuXHJcbiAgICBpZiAoIXNlcHB1a3UpIGluaXQoKTtcclxuXHJcbiAgICAvKlxyXG4gICAgICogNy4gRXhwb3NlIFN0aWNreWZpbGxcclxuICAgICAqL1xyXG4gICAgaWYgKHR5cGVvZiBtb2R1bGUgIT0gJ3VuZGVmaW5lZCcgJiYgbW9kdWxlLmV4cG9ydHMpIHtcclxuICAgICAgICBtb2R1bGUuZXhwb3J0cyA9IFN0aWNreWZpbGw7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgIHdpbmRvdy5TdGlja3lmaWxsID0gU3RpY2t5ZmlsbDtcclxuICAgIH1cclxuXHJcbn0pKHdpbmRvdywgZG9jdW1lbnQpO1xuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL3N0aWNreWZpbGxqcy9kaXN0L3N0aWNreWZpbGwuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL3N0aWNreWZpbGxqcy9kaXN0L3N0aWNreWZpbGwuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIGc7XHJcblxyXG4vLyBUaGlzIHdvcmtzIGluIG5vbi1zdHJpY3QgbW9kZVxyXG5nID0gKGZ1bmN0aW9uKCkge1xyXG5cdHJldHVybiB0aGlzO1xyXG59KSgpO1xyXG5cclxudHJ5IHtcclxuXHQvLyBUaGlzIHdvcmtzIGlmIGV2YWwgaXMgYWxsb3dlZCAoc2VlIENTUClcclxuXHRnID0gZyB8fCBGdW5jdGlvbihcInJldHVybiB0aGlzXCIpKCkgfHwgKDEsZXZhbCkoXCJ0aGlzXCIpO1xyXG59IGNhdGNoKGUpIHtcclxuXHQvLyBUaGlzIHdvcmtzIGlmIHRoZSB3aW5kb3cgcmVmZXJlbmNlIGlzIGF2YWlsYWJsZVxyXG5cdGlmKHR5cGVvZiB3aW5kb3cgPT09IFwib2JqZWN0XCIpXHJcblx0XHRnID0gd2luZG93O1xyXG59XHJcblxyXG4vLyBnIGNhbiBzdGlsbCBiZSB1bmRlZmluZWQsIGJ1dCBub3RoaW5nIHRvIGRvIGFib3V0IGl0Li4uXHJcbi8vIFdlIHJldHVybiB1bmRlZmluZWQsIGluc3RlYWQgb2Ygbm90aGluZyBoZXJlLCBzbyBpdCdzXHJcbi8vIGVhc2llciB0byBoYW5kbGUgdGhpcyBjYXNlLiBpZighZ2xvYmFsKSB7IC4uLn1cclxuXHJcbm1vZHVsZS5leHBvcnRzID0gZztcclxuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gKHdlYnBhY2spL2J1aWxkaW4vZ2xvYmFsLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy93ZWJwYWNrL2J1aWxkaW4vZ2xvYmFsLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJzb3VyY2VSb290IjoiIn0=