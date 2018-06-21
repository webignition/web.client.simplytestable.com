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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgMjIwNDhmNTVmNmEyM2I4MGMzYmUiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz80ZmIwIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxhcHNlLWNvbnRyb2wtY2FyZXQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWJ1dHRvbi1zcGlubmVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9mb3JtLWZpZWxkLWZvY3VzZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGFsLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2JhZGdlLWNvbGxlY3Rpb24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2Nvb2tpZS1vcHRpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hbGVydC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9jb29raWUtb3B0aW9ucy1tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtbW9kYWwuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvaWNvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9jcmF3bGluZy1saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9saXN0ZWQtdGVzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcmVwYXJpbmctbGlzdGVkLXRlc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnQtY29udHJvbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC90YXNrLXF1ZXVlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stcXVldWVzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2suanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1sb2NrLXVubG9jay5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9tb2RlbC9zb3J0LWNvbnRyb2wtY29sbGVjdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbW9kZWwvc29ydGFibGUtaXRlbS1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL2Rhc2hib2FyZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LWhpc3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1wcm9ncmVzcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC1jYXJkLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcG9seWZpbGwvY3VzdG9tLWV2ZW50LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wb2x5ZmlsbC9vYmplY3QtZW50cmllcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2Nyb2xsLXRvLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2VydmljZXMvaHR0cC1jbGllbnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL2xpc3RlZC10ZXN0LWZhY3RvcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlcnZpY2VzL3N0cmlwZS1oYW5kbGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9zZXJ2aWNlcy90ZXN0LXJlc3VsdC1yZXRyaWV2ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtaGlzdG9yeS9tb2RhbC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdGVzdC1oaXN0b3J5L3N1Z2dlc3Rpb25zLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3N1bW1hcnkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1pZC1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdC1wYWdpbmF0b3IuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvYnktcGFnZS1saXN0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL3NvcnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VuYXZhaWxhYmxlLXRhc2stdHlwZS1tb2RhbC1sYXVuY2hlci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1hY2NvdW50LWNhcmQvZm9ybS12YWxpZGF0b3IuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItYW5jaG9yLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWl0ZW0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1hY2NvdW50L3Njcm9sbHNweS5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvYXdlc29tcGxldGUvYXdlc29tcGxldGUuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2Jvb3RzdHJhcC5uYXRpdmUvZGlzdC9ib290c3RyYXAtbmF0aXZlLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9zbW9vdGgtc2Nyb2xsL2Rpc3Qvc21vb3RoLXNjcm9sbC5taW4uanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL3N0aWNreWZpbGxqcy9kaXN0L3N0aWNreWZpbGwuanMiLCJ3ZWJwYWNrOi8vLyh3ZWJwYWNrKS9idWlsZGluL2dsb2JhbC5qcyJdLCJuYW1lcyI6WyJyZXF1aXJlIiwiZm9ybUJ1dHRvblNwaW5uZXIiLCJmb3JtRmllbGRGb2N1c2VyIiwibW9kYWxDb250cm9sIiwiY29sbGFwc2VDb250cm9sQ2FyZXQiLCJEYXNoYm9hcmQiLCJ0ZXN0SGlzdG9yeVBhZ2UiLCJUZXN0UmVzdWx0cyIsIlVzZXJBY2NvdW50IiwiVXNlckFjY291bnRDYXJkIiwiQWxlcnRGYWN0b3J5IiwiVGVzdFByb2dyZXNzIiwiVGVzdFJlc3VsdHNQcmVwYXJpbmciLCJUZXN0UmVzdWx0c0J5VGFza1R5cGUiLCJvbkRvbUNvbnRlbnRMb2FkZWQiLCJib2R5IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsIml0ZW0iLCJmb2N1c2VkRmllbGQiLCJxdWVyeVNlbGVjdG9yIiwiZm9yRWFjaCIsImNhbGwiLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9ybUVsZW1lbnQiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsImRhc2hib2FyZCIsImluaXQiLCJ0ZXN0UHJvZ3Jlc3MiLCJ0ZXN0UmVzdWx0cyIsInRlc3RSZXN1bHRzQnlUYXNrVHlwZSIsInRlc3RSZXN1bHRzUHJlcGFyaW5nIiwidXNlckFjY291bnQiLCJ3aW5kb3ciLCJ1c2VyQWNjb3VudENhcmQiLCJhbGVydEVsZW1lbnQiLCJjcmVhdGVGcm9tRWxlbWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJtb2R1bGUiLCJleHBvcnRzIiwiY29udHJvbHMiLCJjb250cm9sSWNvbkNsYXNzIiwiY2FyZXRVcENsYXNzIiwiY2FyZXREb3duQ2xhc3MiLCJjb250cm9sQ29sbGFwc2VkQ2xhc3MiLCJjcmVhdGVDb250cm9sSWNvbiIsImNvbnRyb2wiLCJjb250cm9sSWNvbiIsImNyZWF0ZUVsZW1lbnQiLCJhZGQiLCJ0b2dnbGVDYXJldCIsInJlbW92ZSIsImhhbmRsZUNvbnRyb2wiLCJldmVudE5hbWVTaG93biIsImV2ZW50TmFtZUhpZGRlbiIsImNvbGxhcHNpYmxlRWxlbWVudCIsImdldEVsZW1lbnRCeUlkIiwiZ2V0QXR0cmlidXRlIiwicmVwbGFjZSIsImFwcGVuZCIsInNob3duSGlkZGVuRXZlbnRMaXN0ZW5lciIsImJpbmQiLCJpIiwibGVuZ3RoIiwiTGlzdGVkVGVzdENvbGxlY3Rpb24iLCJIdHRwQ2xpZW50IiwiUmVjZW50VGVzdExpc3QiLCJlbGVtZW50Iiwib3duZXJEb2N1bWVudCIsInNvdXJjZVVybCIsImxpc3RlZFRlc3RDb2xsZWN0aW9uIiwicmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24iLCJnZXRSZXRyaWV2ZWRFdmVudE5hbWUiLCJfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIiwiX3JldHJpZXZlVGVzdHMiLCJldmVudCIsIl9wYXJzZVJldHJpZXZlZFRlc3RzIiwiZGV0YWlsIiwicmVzcG9uc2UiLCJfcmVuZGVyUmV0cmlldmVkVGVzdHMiLCJzZXRUaW1lb3V0IiwiX3JlbW92ZVNwaW5uZXIiLCJsaXN0ZWRUZXN0IiwicGFyZW50Tm9kZSIsInJlbW92ZUNoaWxkIiwicmV0cmlldmVkTGlzdGVkVGVzdCIsImdldCIsImdldFRlc3RJZCIsImdldFR5cGUiLCJyZXBsYWNlQ2hpbGQiLCJlbmFibGUiLCJyZW5kZXJGcm9tTGlzdGVkVGVzdCIsImluc2VydEFkamFjZW50RWxlbWVudCIsInJldHJpZXZlZFRlc3RzTWFya3VwIiwidHJpbSIsInJldHJpZXZlZFRlc3RDb250YWluZXIiLCJpbm5lckhUTUwiLCJjcmVhdGVGcm9tTm9kZUxpc3QiLCJnZXRUZXh0Iiwic3Bpbm5lciIsIkZvcm1CdXR0b24iLCJUZXN0U3RhcnRGb3JtIiwic3VibWl0QnV0dG9ucyIsInN1Ym1pdEJ1dHRvbiIsInB1c2giLCJfc3VibWl0RXZlbnRMaXN0ZW5lciIsIl9zdWJtaXRCdXR0b25FdmVudExpc3RlbmVyIiwiZGVFbXBoYXNpemUiLCJfcmVwbGFjZVVuY2hlY2tlZENoZWNrYm94ZXNXaXRoSGlkZGVuRmllbGRzIiwiZGlzYWJsZSIsImJ1dHRvbkVsZW1lbnQiLCJ0YXJnZXQiLCJidXR0b24iLCJtYXJrQXNCdXN5IiwiaW5wdXQiLCJjaGVja2VkIiwiaGlkZGVuSW5wdXQiLCJzZXRBdHRyaWJ1dGUiLCJ2YWx1ZSIsImZvcm0iLCJpbnB1dFZhbHVlIiwiZm9jdXMiLCJjb250cm9sRWxlbWVudHMiLCJpbml0aWFsaXplIiwiY29udHJvbEVsZW1lbnQiLCJCYWRnZUNvbGxlY3Rpb24iLCJiYWRnZXMiLCJncmVhdGVzdFdpZHRoIiwiX2Rlcml2ZUdyZWF0ZXN0V2lkdGgiLCJiYWRnZSIsInN0eWxlIiwid2lkdGgiLCJvZmZzZXRXaWR0aCIsIkNvb2tpZU9wdGlvbnNNb2RhbCIsIkNvb2tpZU9wdGlvbnMiLCJjb29raWVPcHRpb25zTW9kYWwiLCJhY3Rpb25CYWRnZSIsInN0YXR1c0VsZW1lbnQiLCJtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciIsImlzRW1wdHkiLCJpbm5lclRleHQiLCJtYXJrTm90RW5hYmxlZCIsIm1hcmtFbmFibGVkIiwiZ2V0T3BlbmVkRXZlbnROYW1lIiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwiZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUiLCJnZXRDbG9zZWRFdmVudE5hbWUiLCJnZXRNb2RhbENsb3NlZEV2ZW50TmFtZSIsIkFjdGlvbkJhZGdlIiwiYnNuIiwiQWxlcnQiLCJjbG9zZUJ1dHRvbiIsIl9jbG9zZUJ1dHRvbkNsaWNrRXZlbnRIYW5kbGVyIiwiX3JlbW92ZVByZXNlbnRhdGlvbmFsQ2xhc3NlcyIsImNvbnRhaW5lciIsImFwcGVuZENoaWxkIiwicHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCIsImNsYXNzTmFtZSIsImluZGV4IiwiaW5kZXhPZiIsInJlbGF0ZWRGaWVsZElkIiwicmVsYXRlZEZpZWxkIiwiYnNuQWxlcnQiLCJjbG9zZSIsImFkZEJ1dHRvbiIsImFwcGx5QnV0dG9uIiwiX2FkZFJlbW92ZUFjdGlvbnMiLCJfYWRkRXZlbnRMaXN0ZW5lcnMiLCJjb29raWVEYXRhUm93Q291bnQiLCJ0YWJsZUJvZHkiLCJyZW1vdmVBY3Rpb24iLCJ0YWJsZVJvdyIsIm5hbWVJbnB1dCIsInR5cGUiLCJrZXkiLCJjbGljayIsInNob3duRXZlbnRMaXN0ZW5lciIsInByZXZpb3VzVGFibGVCb2R5IiwiY2xvbmVOb2RlIiwiaGlkZGVuRXZlbnRMaXN0ZW5lciIsImNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwiYWRkQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyIiwiX2NyZWF0ZVRhYmxlUm93IiwiX2NyZWF0ZVJlbW92ZUFjdGlvbiIsIl9hZGRSZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJfaW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lciIsInJlbW92ZUNvbnRhaW5lciIsIl9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdmVBY3Rpb25Db250YWluZXIiLCJuZXh0Q29va2llSW5kZXgiLCJsYXN0VGFibGVSb3ciLCJ2YWx1ZUlucHV0IiwicGFyc2VJbnQiLCJJY29uIiwiaWNvbkVsZW1lbnQiLCJnZXRTZWxlY3RvciIsImljb24iLCJzZXRCdXN5Iiwic2V0QXZhaWxhYmxlIiwidW5EZUVtcGhhc2l6ZSIsInNldFN1Y2Nlc3NmdWwiLCJyZW1vdmVBdHRyaWJ1dGUiLCJIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwiLCJ1c2VybmFtZUlucHV0IiwicGFzc3dvcmRJbnB1dCIsImNsZWFyQnV0dG9uIiwicHJldmlvdXNVc2VybmFtZSIsInByZXZpb3VzUGFzc3dvcmQiLCJ1c2VybmFtZSIsInBhc3N3b3JkIiwiZm9jdXNlZElucHV0IiwiY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzIiwiYWN0aXZlSWNvbkNsYXNzIiwiY2xhc3Nlc1RvUmV0YWluIiwiZ2V0Q2xhc3MiLCJpbmNsdWRlcyIsIlByb2dyZXNzaW5nTGlzdGVkVGVzdCIsIkNyYXdsaW5nTGlzdGVkVGVzdCIsIkxpc3RlZFRlc3QiLCJpc0ZpbmlzaGVkIiwiZ2V0U3RhdGUiLCJUZXN0UmVzdWx0UmV0cmlldmVyIiwiUHJlcGFyaW5nTGlzdGVkVGVzdCIsIl9pbml0aWFsaXNlUmVzdWx0UmV0cmlldmVyIiwicHJlcGFyaW5nRWxlbWVudCIsInRlc3RSZXN1bHRzUmV0cmlldmVyIiwicmV0cmlldmVkRXZlbnQiLCJQcm9ncmVzc0JhciIsInByb2dyZXNzQmFyRWxlbWVudCIsInByb2dyZXNzQmFyIiwiY29tcGxldGlvblBlcmNlbnQiLCJwYXJzZUZsb2F0IiwiZ2V0Q29tcGxldGlvblBlcmNlbnQiLCJzZXRDb21wbGV0aW9uUGVyY2VudCIsIlNvcnRDb250cm9sIiwia2V5cyIsIkpTT04iLCJwYXJzZSIsIl9jbGlja0V2ZW50TGlzdGVuZXIiLCJnZXRTb3J0UmVxdWVzdGVkRXZlbnROYW1lIiwiU29ydGFibGVJdGVtIiwic29ydFZhbHVlcyIsIlRhc2siLCJUYXNrTGlzdCIsInBhZ2VJbmRleCIsInRhc2tzIiwidGFza0VsZW1lbnQiLCJ0YXNrIiwiZ2V0SWQiLCJzdGF0ZXMiLCJzdGF0ZXNMZW5ndGgiLCJzdGF0ZUluZGV4Iiwic3RhdGUiLCJjb25jYXQiLCJnZXRUYXNrc0J5U3RhdGUiLCJPYmplY3QiLCJ0YXNrSWQiLCJ1cGRhdGVkVGFza0xpc3QiLCJ1cGRhdGVkVGFzayIsInVwZGF0ZUZyb21UYXNrIiwiVGFza1F1ZXVlIiwibGFiZWwiLCJUYXNrUXVldWVzIiwicXVldWVzIiwicXVldWVFbGVtZW50IiwicXVldWUiLCJnZXRRdWV1ZUlkIiwidGFza0NvdW50IiwidGFza0NvdW50QnlTdGF0ZSIsImdldFdpZHRoRm9yU3RhdGUiLCJoYXNPd25Qcm9wZXJ0eSIsIk1hdGgiLCJjZWlsIiwic2V0VmFsdWUiLCJzZXRXaWR0aCIsIlRlc3RBbGVydENvbnRhaW5lciIsImFsZXJ0IiwiY3JlYXRlRnJvbUNvbnRlbnQiLCJzZXRTdHlsZSIsIndyYXBDb250ZW50SW5Db250YWluZXIiLCJUZXN0TG9ja1VubG9jayIsImRhdGEiLCJsb2NrZWQiLCJ1bmxvY2tlZCIsImFjdGlvbiIsImRlc2NyaXB0aW9uIiwiX3JlbmRlciIsIl90b2dnbGUiLCJzdGF0ZURhdGEiLCJwcmV2ZW50RGVmYXVsdCIsInBvc3QiLCJ1cmwiLCJIdHRwQXV0aGVudGljYXRpb25PcHRpb25zIiwiaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsIiwiTGlzdGVkVGVzdEZhY3RvcnkiLCJsaXN0ZWRUZXN0cyIsImNvbnRhaW5zVGVzdElkIiwidGVzdElkIiwiY2FsbGJhY2siLCJub2RlTGlzdCIsImNvbGxlY3Rpb24iLCJsaXN0ZWRUZXN0RWxlbWVudCIsIlNvcnRDb250cm9sQ29sbGVjdGlvbiIsInNldFNvcnRlZCIsInNldE5vdFNvcnRlZCIsIlNvcnRhYmxlSXRlbUxpc3QiLCJpdGVtcyIsInNvcnRlZEl0ZW1zIiwic29ydGFibGVJdGVtIiwicG9zaXRpb24iLCJ2YWx1ZXMiLCJnZXRTb3J0VmFsdWUiLCJOdW1iZXIiLCJpc0ludGVnZXIiLCJ0b1N0cmluZyIsImpvaW4iLCJzb3J0IiwiX2NvbXBhcmVGdW5jdGlvbiIsImluZGV4SXRlbSIsImEiLCJiIiwidW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIiLCJIdHRwQXV0aGVudGljYXRpb25PcHRpb25zRmFjdG9yeSIsIkNvb2tpZU9wdGlvbnNGYWN0b3J5IiwidGVzdFN0YXJ0Rm9ybSIsInJlY2VudFRlc3RMaXN0IiwiaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyIsImNyZWF0ZSIsImNvb2tpZU9wdGlvbnMiLCJNb2RhbCIsIlN1Z2dlc3Rpb25zIiwibW9kYWxJZCIsImZpbHRlck9wdGlvbnNTZWxlY3RvciIsIm1vZGFsRWxlbWVudCIsImZpbHRlck9wdGlvbnNFbGVtZW50IiwibW9kYWwiLCJzdWdnZXN0aW9ucyIsInN1Z2dlc3Rpb25zTG9hZGVkRXZlbnRMaXN0ZW5lciIsInNldFN1Z2dlc3Rpb25zIiwiZmlsdGVyQ2hhbmdlZEV2ZW50TGlzdGVuZXIiLCJmaWx0ZXIiLCJzZWFyY2giLCJjdXJyZW50U2VhcmNoIiwibG9jYXRpb24iLCJsb2FkZWRFdmVudE5hbWUiLCJmaWx0ZXJDaGFuZ2VkRXZlbnROYW1lIiwicmV0cmlldmUiLCJTdW1tYXJ5IiwiVGFza0xpc3RQYWdpbmF0aW9uIiwicGFnZUxlbmd0aCIsInN1bW1hcnkiLCJ0YXNrTGlzdEVsZW1lbnQiLCJ0YXNrTGlzdCIsImFsZXJ0Q29udGFpbmVyIiwidGFza0xpc3RQYWdpbmF0aW9uIiwidGFza0xpc3RJc0luaXRpYWxpemVkIiwic3VtbWFyeURhdGEiLCJfcmVmcmVzaFN1bW1hcnkiLCJnZXRSZW5kZXJBbW1lbmRtZW50RXZlbnROYW1lIiwicmVuZGVyVXJsTGltaXROb3RpZmljYXRpb24iLCJnZXRJbml0aWFsaXplZEV2ZW50TmFtZSIsIl90YXNrTGlzdEluaXRpYWxpemVkRXZlbnRMaXN0ZW5lciIsImdldFNlbGVjdFBhZ2VFdmVudE5hbWUiLCJzZXRDdXJyZW50UGFnZUluZGV4Iiwic2VsZWN0UGFnZSIsInJlbmRlciIsImdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSIsIm1heCIsImN1cnJlbnRQYWdlSW5kZXgiLCJnZXRTZWxlY3ROZXh0UGFnZUV2ZW50TmFtZSIsIm1pbiIsInBhZ2VDb3VudCIsInJlcXVlc3RJZCIsInJlcXVlc3QiLCJyZXNwb25zZVVSTCIsImhyZWYiLCJ0ZXN0IiwicmVtb3RlX3Rlc3QiLCJ0YXNrX2NvdW50IiwiaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyIsIl9zZXRCb2R5Sm9iQ2xhc3MiLCJpc0luaXRpYWxpemluZyIsImlzUmVxdWlyZWQiLCJpc1JlbmRlcmVkIiwiX2NyZWF0ZVBhZ2luYXRpb25FbGVtZW50Iiwic2V0UGFnaW5hdGlvbkVsZW1lbnQiLCJfYWRkUGFnaW5hdGlvbkV2ZW50TGlzdGVuZXJzIiwiY3JlYXRlTWFya3VwIiwic3VtbWFyeVVybCIsIm5vdyIsIkRhdGUiLCJnZXRKc29uIiwiZ2V0VGltZSIsImJvZHlDbGFzc0xpc3QiLCJ0ZXN0U3RhdGUiLCJqb2JDbGFzc1ByZWZpeCIsIkJ5UGFnZUxpc3QiLCJCeUVycm9yTGlzdCIsImJ5RXJyb3JDb250YWluZXJFbGVtZW50IiwiYnlQYWdlTGlzdCIsImJ5RXJyb3JMaXN0IiwidW5yZXRyaWV2ZWRSZW1vdGVUYXNrSWRzVXJsIiwicmVzdWx0c1JldHJpZXZlVXJsIiwicmV0cmlldmFsU3RhdHNVcmwiLCJyZXN1bHRzVXJsIiwiY29tcGxldGlvblBlcmNlbnRWYWx1ZSIsImxvY2FsVGFza0NvdW50IiwicmVtb3RlVGFza0NvdW50IiwiX2NoZWNrQ29tcGxldGlvblN0YXR1cyIsIl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uIiwiX3JldHJpZXZlUmVtb3RlVGFza0NvbGxlY3Rpb24iLCJfcmV0cmlldmVSZXRyaWV2YWxTdGF0cyIsImNvbXBsZXRpb25fcGVyY2VudCIsInJlbWFpbmluZ190YXNrc190b19yZXRyaWV2ZV9jb3VudCIsImxvY2FsX3Rhc2tfY291bnQiLCJyZW1vdGVfdGFza19jb3VudCIsInJlbW90ZVRhc2tJZHMiLCJ0ZXN0TG9ja1VubG9jayIsInJldGVzdEZvcm0iLCJyZXRlc3RCdXR0b24iLCJ0YXNrVHlwZVN1bW1hcnlCYWRnZUNvbGxlY3Rpb24iLCJhcHBseVVuaWZvcm1XaWR0aCIsIkZvcm0iLCJGb3JtVmFsaWRhdG9yIiwiU3RyaXBlSGFuZGxlciIsInN0cmlwZUpzIiwiU3RyaXBlIiwiZm9ybVZhbGlkYXRvciIsInN0cmlwZUhhbmRsZXIiLCJzZXRTdHJpcGVQdWJsaXNoYWJsZUtleSIsImdldFN0cmlwZVB1Ymxpc2hhYmxlS2V5IiwidXBkYXRlQ2FyZCIsImdldFVwZGF0ZUNhcmRFdmVudE5hbWUiLCJjcmVhdGVDYXJkVG9rZW5TdWNjZXNzIiwiZ2V0Q3JlYXRlQ2FyZFRva2VuU3VjY2Vzc0V2ZW50TmFtZSIsImNyZWF0ZUNhcmRUb2tlbkZhaWx1cmUiLCJnZXRDcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnROYW1lIiwiX3VwZGF0ZUNhcmRFdmVudExpc3RlbmVyIiwiX2NyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudExpc3RlbmVyIiwiX2NyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudExpc3RlbmVyIiwidXBkYXRlQ2FyZEV2ZW50IiwiY3JlYXRlQ2FyZFRva2VuIiwic3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQiLCJyZXF1ZXN0VXJsIiwicGF0aG5hbWUiLCJpZCIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsInJlc3BvbnNlVHlwZSIsInNldFJlcXVlc3RIZWFkZXIiLCJtYXJrQXNBdmFpbGFibGUiLCJtYXJrU3VjY2VlZGVkIiwidGhpc191cmwiLCJoYW5kbGVSZXNwb25zZUVycm9yIiwidXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtIiwidXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX21lc3NhZ2UiLCJzZW5kIiwicmVzcG9uc2VFcnJvciIsImNyZWF0ZVJlc3BvbnNlRXJyb3IiLCJlcnJvciIsInBhcmFtIiwiU2Nyb2xsU3B5IiwiTmF2QmFyTGlzdCIsIlNjcm9sbFRvIiwiU3RpY2t5RmlsbCIsInNjcm9sbE9mZnNldCIsInNjcm9sbFNweU9mZnNldCIsInNpZGVOYXZFbGVtZW50IiwibmF2QmFyTGlzdCIsInNjcm9sbHNweSIsInRhcmdldElkIiwiaGFzaCIsInJlbGF0ZWRBbmNob3IiLCJnb1RvIiwic2Nyb2xsVG8iLCJzZWxlY3RvciIsInN0aWNreU5hdkpzQ2xhc3MiLCJzdGlja3lOYXZKc1NlbGVjdG9yIiwic3RpY2t5TmF2IiwiYWRkT25lIiwic3B5IiwiX2FwcGx5UG9zaXRpb25TdGlja3lQb2x5ZmlsbCIsIl9hcHBseUluaXRpYWxTY3JvbGwiLCJwYXJhbXMiLCJidWJibGVzIiwiY2FuY2VsYWJsZSIsInVuZGVmaW5lZCIsImN1c3RvbUV2ZW50IiwiY3JlYXRlRXZlbnQiLCJpbml0Q3VzdG9tRXZlbnQiLCJwcm90b3R5cGUiLCJFdmVudCIsImVudHJpZXMiLCJvYmoiLCJvd25Qcm9wcyIsInJlc0FycmF5IiwiQXJyYXkiLCJTbW9vdGhTY3JvbGwiLCJvZmZzZXQiLCJzY3JvbGwiLCJhbmltYXRlU2Nyb2xsIiwib2Zmc2V0VG9wIiwiX3VwZGF0ZUhpc3RvcnkiLCJoaXN0b3J5IiwicHVzaFN0YXRlIiwiZXJyb3JDb250ZW50IiwiZWxlbWVudElubmVySFRNTCIsIm1ldGhvZCIsInJlcXVlc3RIZWFkZXJzIiwicmVhbFJlcXVlc3RIZWFkZXJzIiwic3RyaXBlUHVibGlzaGFibGVLZXkiLCJzZXRQdWJsaXNoYWJsZUtleSIsImNhcmQiLCJjcmVhdGVUb2tlbiIsInN0YXR1cyIsImlzRXJyb3JSZXNwb25zZSIsImV2ZW50TmFtZSIsInN0YXR1c1VybCIsInVucmV0cmlldmVkVGFza0lkc1VybCIsInJldHJpZXZlVGFza3NVcmwiLCJfcmV0cmlldmVQcmVwYXJpbmdTdGF0dXMiLCJfcmV0cmlldmVGaW5pc2hlZFN1bW1hcnkiLCJfZGlzcGxheVByZXBhcmluZ1N1bW1hcnkiLCJfcmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbiIsIl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrQ29sbGVjdGlvbiIsInJldHJpZXZlZFN1bW1hcnlDb250YWluZXIiLCJzdGF0dXNEYXRhIiwiX2NyZWF0ZVByZXBhcmluZ1N1bW1hcnkiLCJwcmV2aW91c0ZpbHRlciIsImFwcGx5RmlsdGVyIiwiYXdlc29tZXBsZXRlIiwiQXdlc29tcGxldGUiLCJsaXN0IiwiaGlkZUV2ZW50TGlzdGVuZXIiLCJXSUxEQ0FSRCIsImZpbHRlcklzRW1wdHkiLCJzdWdnZXN0aW9uc0luY2x1ZGVzRmlsdGVyIiwiZmlsdGVySXNXaWxkY2FyZFByZWZpeGVkIiwiY2hhckF0IiwiZmlsdGVySXNXaWxkY2FyZFN1ZmZpeGVkIiwic2xpY2UiLCJhcHBseUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciIsInJlcXVlc3RPbmxvYWRIYW5kbGVyIiwicmVzcG9uc2VUZXh0Iiwib25sb2FkIiwiY2FuY2VsQWN0aW9uIiwiY2FuY2VsQ3Jhd2xBY3Rpb24iLCJzdGF0ZUxhYmVsIiwidGFza1F1ZXVlcyIsIl9jYW5jZWxBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJfY2FuY2VsQ3Jhd2xBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIiLCJyZW1vdGVUZXN0Iiwic3RhdGVfbGFiZWwiLCJ0YXNrX2NvdW50X2J5X3N0YXRlIiwiYW1tZW5kbWVudHMiLCJUYXNrSWRMaXN0IiwidGFza0lkcyIsInBhZ2VOdW1iZXIiLCJwYWdlQWN0aW9ucyIsInByZXZpb3VzQWN0aW9uIiwibmV4dEFjdGlvbiIsImFjdGlvbkNvbnRhaW5lciIsInJvbGUiLCJtYXJrdXAiLCJzdGFydEluZGV4IiwiZW5kSW5kZXgiLCJwYWdlQWN0aW9uIiwiaXNBY3RpdmUiLCJwYXJlbnRFbGVtZW50IiwiVGFza0xpc3RNb2RlbCIsInRhc2tJZExpc3QiLCJ0YXNrTGlzdE1vZGVscyIsImhlYWRpbmciLCJidXN5SWNvbiIsIl9jcmVhdGVCdXN5SWNvbiIsIl9yZXF1ZXN0VGFza0lkcyIsInBhZ2luYXRpb25FbGVtZW50IiwidGFza0xpc3RNb2RlbCIsIl9jcmVhdGVUYXNrTGlzdEVsZW1lbnRGcm9tSHRtbCIsImdldFBhZ2VJbmRleCIsIl9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkiLCJnZXRUYXNrc0J5U3RhdGVzIiwidXBkYXRlZFRhc2tMaXN0TW9kZWwiLCJ1cGRhdGVGcm9tVGFza0xpc3QiLCJoYXNUYXNrTGlzdEVsZW1lbnRGb3JQYWdlIiwiX3JldHJpZXZlVGFza1BhZ2UiLCJyZW5kZXJlZFRhc2tMaXN0RWxlbWVudCIsImhhc1BhZ2VJbmRleCIsImN1cnJlbnRQYWdlTGlzdEVsZW1lbnQiLCJzZWxlY3RlZFBhZ2VMaXN0RWxlbWVudCIsImdldEZvclBhZ2UiLCJwb3N0RGF0YSIsIl9yZXRyaWV2ZVRhc2tTZXQiLCJodG1sIiwiYnlQYWdlTGlzdHMiLCJieVBhZ2VDb250YWluZXJFbGVtZW50IiwiU29ydCIsInNvcnRhYmxlSXRlbXNOb2RlTGlzdCIsInNvcnRDb250cm9sQ29sbGVjdGlvbiIsIl9jcmVhdGVTb3J0YWJsZUNvbnRyb2xDb2xsZWN0aW9uIiwic29ydGFibGVJdGVtc0xpc3QiLCJfY3JlYXRlU29ydGFibGVJdGVtTGlzdCIsIl9zb3J0Q29udHJvbENsaWNrRXZlbnRMaXN0ZW5lciIsInNvcnRhYmxlSXRlbXMiLCJzb3J0YWJsZUl0ZW1FbGVtZW50Iiwic29ydENvbnRyb2xFbGVtZW50IiwicGFyZW50IiwidGFza1R5cGVDb250YWluZXJzIiwidW5hdmFpbGFibGVUYXNrVHlwZSIsInRhc2tUeXBlS2V5Iiwic2hvdyIsImludmFsaWRGaWVsZCIsImVycm9yTWVzc2FnZSIsImNvbXBhcmF0b3JWYWx1ZSIsInZhbGlkYXRlQ2FyZE51bWJlciIsIm51bWJlciIsInZhbGlkYXRlRXhwaXJ5IiwiZXhwX21vbnRoIiwiZXhwX3llYXIiLCJ2YWxpZGF0ZUNWQyIsImN2YyIsInZhbGlkYXRvciIsImRhdGFFbGVtZW50IiwiZmllbGRLZXkiLCJzdG9wUHJvcGFnYXRpb24iLCJfcmVtb3ZlRXJyb3JBbGVydHMiLCJfZ2V0RGF0YSIsImlzVmFsaWQiLCJ2YWxpZGF0ZSIsImVycm9yQWxlcnRzIiwiZXJyb3JBbGVydCIsImZpZWxkIiwibWVzc2FnZSIsImVycm9yQ29udGFpbmVyIiwiX2Rpc3BsYXlGaWVsZEVycm9yIiwiTmF2QmFyQW5jaG9yIiwiaGFuZGxlQ2xpY2siLCJnZXRUYXJnZXQiLCJOYXZCYXJJdGVtIiwiYW5jaG9yIiwiY2hpbGRyZW4iLCJjaGlsZCIsIm5vZGVOYW1lIiwidGFyZ2V0cyIsImdldFRhcmdldHMiLCJjb250YWluc1RhcmdldElkIiwic2V0QWN0aXZlIiwibmF2QmFySXRlbXMiLCJuYXZCYXJJdGVtIiwibGlzdEl0ZW1FbGVtZW50IiwiYWN0aXZlTGlua1RhcmdldCIsImxpbmtUYXJnZXRzIiwibGlua1RhcmdldHNQYXN0VGhyZXNob2xkIiwibGlua1RhcmdldCIsImdldEJvdW5kaW5nQ2xpZW50UmVjdCIsInRvcCIsImNsZWFyQWN0aXZlIiwic2Nyb2xsRXZlbnRMaXN0ZW5lciJdLCJtYXBwaW5ncyI6IjtBQUFBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxtQ0FBMkIsMEJBQTBCLEVBQUU7QUFDdkQseUNBQWlDLGVBQWU7QUFDaEQ7QUFDQTtBQUNBOztBQUVBO0FBQ0EsOERBQXNELCtEQUErRDs7QUFFckg7QUFDQTs7QUFFQTtBQUNBOzs7Ozs7Ozs7Ozs7O0FDN0RBLHlDOzs7Ozs7Ozs7Ozs7QUNBQSxtQkFBQUEsQ0FBUSxrRkFBUjtBQUNBLG1CQUFBQSxDQUFRLDhDQUFSOztBQUVBLG1CQUFBQSxDQUFRLDBFQUFSO0FBQ0EsbUJBQUFBLENBQVEscUVBQVI7QUFDQSxtQkFBQUEs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QUNBLGtCQUFrQiwwQkFBMEI7QUFDNUMsQ0FBQztBQUNEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSwrQkFBK0I7QUFDL0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSxxQ0FBcUM7QUFDckM7O0FBRUE7QUFDQTtBQUNBLGdCQUFnQixnQ0FBZ0M7QUFDaEQ7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxFQUFFO0FBQ0Y7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLENBQUM7Ozs7Ozs7Ozs7Ozs7OENDaGZEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSwrQkFBK0I7QUFDL0I7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7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QTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6Qjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyREFBMkQ7QUFDM0Q7O0FBRUE7QUFDQTtBQUNBLDJCQUEyQixxQkFBcUI7QUFDaEQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBOztBQUVBLDJCQUEyQixxQkFBcUI7QUFDaEQ7O0FBRUE7QUFDQTs7QUFFQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyREFBMkQ7QUFDM0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjs7QUFFQSwyQkFBMkIscUJBQXFCO0FBQ2hEO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBLENBQUMsb0I7Ozs7Ozs7Ozs7OztBQ3ZnQkQ7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQSxDQUFDO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDRDQUE0Qzs7QUFFNUMiLCJmaWxlIjoiYXBwLjc1ZDQyNjI5LmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL2J1aWxkL1wiO1xuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IFwiLi9hc3NldHMvanMvYXBwLmpzXCIpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHdlYnBhY2svYm9vdHN0cmFwIDIyMDQ4ZjU1ZjZhMjNiODBjM2JlIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL2Fzc2V0cy9jc3MvYXBwLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IC4vYXNzZXRzL2Nzcy9hcHAuc2Nzc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJyZXF1aXJlKCdib290c3RyYXAubmF0aXZlJyk7XG5yZXF1aXJlKCcuLi9jc3MvYXBwLnNjc3MnKTtcblxucmVxdWlyZSgnY2xhc3NsaXN0LXBvbHlmaWxsJyk7XG5yZXF1aXJlKCcuL3BvbHlmaWxsL2N1c3RvbS1ldmVudCcpO1xucmVxdWlyZSgnLi9wb2x5ZmlsbC9vYmplY3QtZW50cmllcycpO1xuXG5sZXQgZm9ybUJ1dHRvblNwaW5uZXIgPSByZXF1aXJlKCcuL2Zvcm0tYnV0dG9uLXNwaW5uZXInKTtcbmxldCBmb3JtRmllbGRGb2N1c2VyID0gcmVxdWlyZSgnLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcbmxldCBtb2RhbENvbnRyb2wgPSByZXF1aXJlKCcuL21vZGFsLWNvbnRyb2wnKTtcbmxldCBjb2xsYXBzZUNvbnRyb2xDYXJldCA9IHJlcXVpcmUoJy4vY29sbGFwc2UtY29udHJvbC1jYXJldCcpO1xuXG5sZXQgRGFzaGJvYXJkID0gcmVxdWlyZSgnLi9wYWdlL2Rhc2hib2FyZCcpO1xubGV0IHRlc3RIaXN0b3J5UGFnZSA9IHJlcXVpcmUoJy4vcGFnZS90ZXN0LWhpc3RvcnknKTtcbmxldCBUZXN0UmVzdWx0cyA9IHJlcXVpcmUoJy4vcGFnZS90ZXN0LXJlc3VsdHMnKTtcbmxldCBVc2VyQWNjb3VudCA9IHJlcXVpcmUoJy4vcGFnZS91c2VyLWFjY291bnQnKTtcbmxldCBVc2VyQWNjb3VudENhcmQgPSByZXF1aXJlKCcuL3BhZ2UvdXNlci1hY2NvdW50LWNhcmQnKTtcbmxldCBBbGVydEZhY3RvcnkgPSByZXF1aXJlKCcuL3NlcnZpY2VzL2FsZXJ0LWZhY3RvcnknKTtcbmxldCBUZXN0UHJvZ3Jlc3MgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1wcm9ncmVzcycpO1xubGV0IFRlc3RSZXN1bHRzUHJlcGFyaW5nID0gcmVxdWlyZSgnLi9wYWdlL3Rlc3QtcmVzdWx0cy1wcmVwYXJpbmcnKTtcbmxldCBUZXN0UmVzdWx0c0J5VGFza1R5cGUgPSByZXF1aXJlKCcuL3BhZ2UvdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZScpO1xuXG5jb25zdCBvbkRvbUNvbnRlbnRMb2FkZWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgbGV0IGJvZHkgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYm9keScpLml0ZW0oMCk7XG4gICAgbGV0IGZvY3VzZWRGaWVsZCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLWZvY3VzZWRdJyk7XG5cbiAgICBpZiAoZm9jdXNlZEZpZWxkKSB7XG4gICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZm9jdXNlZEZpZWxkKTtcbiAgICB9XG5cbiAgICBbXS5mb3JFYWNoLmNhbGwoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLWZvcm0tYnV0dG9uLXNwaW5uZXInKSwgZnVuY3Rpb24gKGZvcm1FbGVtZW50KSB7XG4gICAgICAgIGZvcm1CdXR0b25TcGlubmVyKGZvcm1FbGVtZW50KTtcbiAgICB9KTtcblxuICAgIG1vZGFsQ29udHJvbChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubW9kYWwtY29udHJvbCcpKTtcblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygnZGFzaGJvYXJkJykpIHtcbiAgICAgICAgbGV0IGRhc2hib2FyZCA9IG5ldyBEYXNoYm9hcmQoZG9jdW1lbnQpO1xuICAgICAgICBkYXNoYm9hcmQuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1wcm9ncmVzcycpKSB7XG4gICAgICAgIGxldCB0ZXN0UHJvZ3Jlc3MgPSBuZXcgVGVzdFByb2dyZXNzKGRvY3VtZW50KTtcbiAgICAgICAgdGVzdFByb2dyZXNzLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3Rlc3QtaGlzdG9yeScpKSB7XG4gICAgICAgIHRlc3RIaXN0b3J5UGFnZShkb2N1bWVudCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd0ZXN0LXJlc3VsdHMnKSkge1xuICAgICAgICBsZXQgdGVzdFJlc3VsdHMgPSBuZXcgVGVzdFJlc3VsdHMoZG9jdW1lbnQpO1xuICAgICAgICB0ZXN0UmVzdWx0cy5pbml0KCk7XG4gICAgfVxuXG4gICAgaWYgKGJvZHkuY2xhc3NMaXN0LmNvbnRhaW5zKCd0ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlJykpIHtcbiAgICAgICAgbGV0IHRlc3RSZXN1bHRzQnlUYXNrVHlwZSA9IG5ldyBUZXN0UmVzdWx0c0J5VGFza1R5cGUoZG9jdW1lbnQpO1xuICAgICAgICB0ZXN0UmVzdWx0c0J5VGFza1R5cGUuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndGVzdC1yZXN1bHRzLXByZXBhcmluZycpKSB7XG4gICAgICAgIGxldCB0ZXN0UmVzdWx0c1ByZXBhcmluZyA9IG5ldyBUZXN0UmVzdWx0c1ByZXBhcmluZyhkb2N1bWVudCk7XG4gICAgICAgIHRlc3RSZXN1bHRzUHJlcGFyaW5nLmluaXQoKTtcbiAgICB9XG5cbiAgICBpZiAoYm9keS5jbGFzc0xpc3QuY29udGFpbnMoJ3VzZXItYWNjb3VudCcpKSB7XG4gICAgICAgIGxldCB1c2VyQWNjb3VudCA9IG5ldyBVc2VyQWNjb3VudCh3aW5kb3csIGRvY3VtZW50KTtcbiAgICAgICAgdXNlckFjY291bnQuaW5pdCgpO1xuICAgIH1cblxuICAgIGlmIChib2R5LmNsYXNzTGlzdC5jb250YWlucygndXNlci1hY2NvdW50LWNhcmQnKSkge1xuICAgICAgICBsZXQgdXNlckFjY291bnRDYXJkID0gbmV3IFVzZXJBY2NvdW50Q2FyZChkb2N1bWVudCk7XG4gICAgICAgIHVzZXJBY2NvdW50Q2FyZC5pbml0KCk7XG4gICAgfVxuXG4gICAgY29sbGFwc2VDb250cm9sQ2FyZXQoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmNvbGxhcHNlLWNvbnRyb2wnKSk7XG5cbiAgICBbXS5mb3JFYWNoLmNhbGwoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmFsZXJ0JyksIGZ1bmN0aW9uIChhbGVydEVsZW1lbnQpIHtcbiAgICAgICAgQWxlcnRGYWN0b3J5LmNyZWF0ZUZyb21FbGVtZW50KGFsZXJ0RWxlbWVudCk7XG4gICAgfSk7XG59O1xuXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgb25Eb21Db250ZW50TG9hZGVkKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9hcHAuanMiLCJtb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uIChjb250cm9scykge1xuICAgIGNvbnN0IGNvbnRyb2xJY29uQ2xhc3MgPSAnZmEnO1xuICAgIGNvbnN0IGNhcmV0VXBDbGFzcyA9ICdmYS1jYXJldC11cCc7XG4gICAgY29uc3QgY2FyZXREb3duQ2xhc3MgPSAnZmEtY2FyZXQtZG93bic7XG4gICAgY29uc3QgY29udHJvbENvbGxhcHNlZENsYXNzID0gJ2NvbGxhcHNlZCc7XG5cbiAgICBsZXQgY3JlYXRlQ29udHJvbEljb24gPSBmdW5jdGlvbiAoY29udHJvbCkge1xuICAgICAgICBjb25zdCBjb250cm9sSWNvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2knKTtcbiAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjb250cm9sSWNvbkNsYXNzKTtcblxuICAgICAgICBpZiAoY29udHJvbC5jbGFzc0xpc3QuY29udGFpbnMoY29udHJvbENvbGxhcHNlZENsYXNzKSkge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldERvd25DbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QuYWRkKGNhcmV0VXBDbGFzcyk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gY29udHJvbEljb247XG4gICAgfTtcblxuICAgIGxldCB0b2dnbGVDYXJldCA9IGZ1bmN0aW9uIChjb250cm9sLCBjb250cm9sSWNvbikge1xuICAgICAgICBpZiAoY29udHJvbC5jbGFzc0xpc3QuY29udGFpbnMoY29udHJvbENvbGxhcHNlZENsYXNzKSkge1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LnJlbW92ZShjYXJldFVwQ2xhc3MpO1xuICAgICAgICAgICAgY29udHJvbEljb24uY2xhc3NMaXN0LmFkZChjYXJldERvd25DbGFzcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjb250cm9sSWNvbi5jbGFzc0xpc3QucmVtb3ZlKGNhcmV0RG93bkNsYXNzKTtcbiAgICAgICAgICAgIGNvbnRyb2xJY29uLmNsYXNzTGlzdC5hZGQoY2FyZXRVcENsYXNzKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBsZXQgaGFuZGxlQ29udHJvbCA9IGZ1bmN0aW9uIChjb250cm9sKSB7XG4gICAgICAgIGNvbnN0IGV2ZW50TmFtZVNob3duID0gJ3Nob3duLmJzLmNvbGxhcHNlJztcbiAgICAgICAgY29uc3QgZXZlbnROYW1lSGlkZGVuID0gJ2hpZGRlbi5icy5jb2xsYXBzZSc7XG4gICAgICAgIGNvbnN0IGNvbGxhcHNpYmxlRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGNvbnRyb2wuZ2V0QXR0cmlidXRlKCdkYXRhLXRhcmdldCcpLnJlcGxhY2UoJyMnLCAnJykpO1xuICAgICAgICBjb25zdCBjb250cm9sSWNvbiA9IGNyZWF0ZUNvbnRyb2xJY29uKGNvbnRyb2wpO1xuXG4gICAgICAgIGNvbnRyb2wuYXBwZW5kKGNvbnRyb2xJY29uKTtcblxuICAgICAgICBsZXQgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdG9nZ2xlQ2FyZXQoY29udHJvbCwgY29udHJvbEljb24pO1xuICAgICAgICB9O1xuXG4gICAgICAgIGNvbGxhcHNpYmxlRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50TmFtZVNob3duLCBzaG93bkhpZGRlbkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIGNvbGxhcHNpYmxlRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50TmFtZUhpZGRlbiwgc2hvd25IaWRkZW5FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGNvbnRyb2xzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGhhbmRsZUNvbnRyb2woY29udHJvbHNbaV0pO1xuICAgIH1cbn07XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY29sbGFwc2UtY29udHJvbC1jYXJldC5qcyIsImxldCBMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24nKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgUmVjZW50VGVzdExpc3Qge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuc291cmNlVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc291cmNlLXVybCcpO1xuICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uID0gbmV3IExpc3RlZFRlc3RDb2xsZWN0aW9uKCk7XG4gICAgICAgIHRoaXMucmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24gPSBuZXcgTGlzdGVkVGVzdENvbGxlY3Rpb24oKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBDbGllbnQuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHRoaXMuX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLl9yZXRyaWV2ZVRlc3RzKCk7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIHRoaXMuX3BhcnNlUmV0cmlldmVkVGVzdHMoZXZlbnQuZGV0YWlsLnJlc3BvbnNlKTtcbiAgICAgICAgdGhpcy5fcmVuZGVyUmV0cmlldmVkVGVzdHMoKTtcblxuICAgICAgICB3aW5kb3cuc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRlc3RzKCk7XG4gICAgICAgIH0sIDMwMDApO1xuICAgIH07XG5cbiAgICBfcmVuZGVyUmV0cmlldmVkVGVzdHMgKCkge1xuICAgICAgICB0aGlzLl9yZW1vdmVTcGlubmVyKCk7XG5cbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5mb3JFYWNoKChsaXN0ZWRUZXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIXRoaXMucmV0cmlldmVkTGlzdGVkVGVzdENvbGxlY3Rpb24uY29udGFpbnMobGlzdGVkVGVzdCkpIHtcbiAgICAgICAgICAgICAgICBsaXN0ZWRUZXN0LmVsZW1lbnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChsaXN0ZWRUZXN0LmVsZW1lbnQpO1xuICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24ucmVtb3ZlKGxpc3RlZFRlc3QpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uLmZvckVhY2goKHJldHJpZXZlZExpc3RlZFRlc3QpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmNvbnRhaW5zKHJldHJpZXZlZExpc3RlZFRlc3QpKSB7XG4gICAgICAgICAgICAgICAgbGV0IGxpc3RlZFRlc3QgPSB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmdldChyZXRyaWV2ZWRMaXN0ZWRUZXN0LmdldFRlc3RJZCgpKTtcblxuICAgICAgICAgICAgICAgIGlmIChyZXRyaWV2ZWRMaXN0ZWRUZXN0LmdldFR5cGUoKSAhPT0gbGlzdGVkVGVzdC5nZXRUeXBlKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5saXN0ZWRUZXN0Q29sbGVjdGlvbi5yZW1vdmUobGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5yZXBsYWNlQ2hpbGQocmV0cmlldmVkTGlzdGVkVGVzdC5lbGVtZW50LCBsaXN0ZWRUZXN0LmVsZW1lbnQpO1xuXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubGlzdGVkVGVzdENvbGxlY3Rpb24uYWRkKHJldHJpZXZlZExpc3RlZFRlc3QpO1xuICAgICAgICAgICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGxpc3RlZFRlc3QucmVuZGVyRnJvbUxpc3RlZFRlc3QocmV0cmlldmVkTGlzdGVkVGVzdCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdhZnRlcmJlZ2luJywgcmV0cmlldmVkTGlzdGVkVGVzdC5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmxpc3RlZFRlc3RDb2xsZWN0aW9uLmFkZChyZXRyaWV2ZWRMaXN0ZWRUZXN0KTtcbiAgICAgICAgICAgICAgICByZXRyaWV2ZWRMaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3BhcnNlUmV0cmlldmVkVGVzdHMgKHJlc3BvbnNlKSB7XG4gICAgICAgIGxldCByZXRyaWV2ZWRUZXN0c01hcmt1cCA9IHJlc3BvbnNlLnRyaW0oKTtcbiAgICAgICAgbGV0IHJldHJpZXZlZFRlc3RDb250YWluZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgcmV0cmlldmVkVGVzdENvbnRhaW5lci5pbm5lckhUTUwgPSByZXRyaWV2ZWRUZXN0c01hcmt1cDtcblxuICAgICAgICB0aGlzLnJldHJpZXZlZExpc3RlZFRlc3RDb2xsZWN0aW9uID0gTGlzdGVkVGVzdENvbGxlY3Rpb24uY3JlYXRlRnJvbU5vZGVMaXN0KFxuICAgICAgICAgICAgcmV0cmlldmVkVGVzdENvbnRhaW5lci5xdWVyeVNlbGVjdG9yQWxsKCcubGlzdGVkLXRlc3QnKSxcbiAgICAgICAgICAgIGZhbHNlXG4gICAgICAgICk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZVRlc3RzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRUZXh0KHRoaXMuc291cmNlVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZVRlc3RzJyk7XG4gICAgfTtcblxuICAgIF9yZW1vdmVTcGlubmVyICgpIHtcbiAgICAgICAgbGV0IHNwaW5uZXIgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXByZWZpbGwtc3Bpbm5lcicpO1xuXG4gICAgICAgIGlmIChzcGlubmVyKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQucmVtb3ZlQ2hpbGQoc3Bpbm5lcik7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFJlY2VudFRlc3RMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0LmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Zvcm0tYnV0dG9uJyk7XG5cbmNsYXNzIFRlc3RTdGFydEZvcm0ge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBlbGVtZW50Lm93bmVyRG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucyA9IFtdO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnW3R5cGU9c3VibWl0XScpLCAoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMucHVzaChuZXcgRm9ybUJ1dHRvbihzdWJtaXRCdXR0b24pKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgdGhpcy5fc3VibWl0RXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcblxuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX3N1Ym1pdEJ1dHRvbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgX3N1Ym1pdEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucy5mb3JFYWNoKChzdWJtaXRCdXR0b24pID0+IHtcbiAgICAgICAgICAgIHN1Ym1pdEJ1dHRvbi5kZUVtcGhhc2l6ZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLl9yZXBsYWNlVW5jaGVja2VkQ2hlY2tib3hlc1dpdGhIaWRkZW5GaWVsZHMoKTtcbiAgICB9O1xuXG4gICAgZGlzYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9ucy5mb3JFYWNoKChzdWJtaXRCdXR0b24pID0+IHtcbiAgICAgICAgICAgIHN1Ym1pdEJ1dHRvbi5kaXNhYmxlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBlbmFibGUgKCkge1xuICAgICAgICB0aGlzLnN1Ym1pdEJ1dHRvbnMuZm9yRWFjaCgoc3VibWl0QnV0dG9uKSA9PiB7XG4gICAgICAgICAgICBzdWJtaXRCdXR0b24uZW5hYmxlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfc3VibWl0QnV0dG9uRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IGJ1dHRvbkVsZW1lbnQgPSBldmVudC50YXJnZXQ7XG4gICAgICAgIGxldCBidXR0b24gPSBuZXcgRm9ybUJ1dHRvbihidXR0b25FbGVtZW50KTtcblxuICAgICAgICBidXR0b24ubWFya0FzQnVzeSgpO1xuICAgIH07XG5cbiAgICBfcmVwbGFjZVVuY2hlY2tlZENoZWNrYm94ZXNXaXRoSGlkZGVuRmllbGRzICgpIHtcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbnB1dFt0eXBlPWNoZWNrYm94XScpLCAoaW5wdXQpID0+IHtcbiAgICAgICAgICAgIGlmICghaW5wdXQuY2hlY2tlZCkge1xuICAgICAgICAgICAgICAgIGxldCBoaWRkZW5JbnB1dCA9IHRoaXMuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcbiAgICAgICAgICAgICAgICBoaWRkZW5JbnB1dC5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnaGlkZGVuJyk7XG4gICAgICAgICAgICAgICAgaGlkZGVuSW5wdXQuc2V0QXR0cmlidXRlKCduYW1lJywgaW5wdXQuZ2V0QXR0cmlidXRlKCduYW1lJykpO1xuICAgICAgICAgICAgICAgIGhpZGRlbklucHV0LnZhbHVlID0gJzAnO1xuXG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZChoaWRkZW5JbnB1dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFN0YXJ0Rm9ybTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9kYXNoYm9hcmQvdGVzdC1zdGFydC1mb3JtLmpzIiwibGV0IEZvcm1CdXR0b24gPSByZXF1aXJlKCcuL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcblxubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoZm9ybSkge1xuICAgIGNvbnN0IHN1Ym1pdEJ1dHRvbiA9IG5ldyBGb3JtQnV0dG9uKGZvcm0ucXVlcnlTZWxlY3RvcignYnV0dG9uW3R5cGU9c3VibWl0XScpKTtcblxuICAgIGZvcm0uYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICBzdWJtaXRCdXR0b24ubWFya0FzQnVzeSgpO1xuICAgIH0pO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9mb3JtLWJ1dHRvbi1zcGlubmVyLmpzIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoaW5wdXQpIHtcbiAgICBsZXQgaW5wdXRWYWx1ZSA9IGlucHV0LnZhbHVlO1xuXG4gICAgd2luZG93LnNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICBpbnB1dC5mb2N1cygpO1xuICAgICAgICBpbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICBpbnB1dC52YWx1ZSA9IGlucHV0VmFsdWU7XG4gICAgfSwgMSk7XG59O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2Zvcm0tZmllbGQtZm9jdXNlci5qcyIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKGNvbnRyb2xFbGVtZW50cykge1xuICAgIGxldCBpbml0aWFsaXplID0gZnVuY3Rpb24gKGNvbnRyb2xFbGVtZW50KSB7XG4gICAgICAgIGNvbnRyb2xFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2J0bicsICdidG4tbGluaycpO1xuICAgIH07XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGNvbnRyb2xFbGVtZW50cy5sZW5ndGg7IGkrKykge1xuICAgICAgICBpbml0aWFsaXplKGNvbnRyb2xFbGVtZW50c1tpXSk7XG4gICAgfVxufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RhbC1jb250cm9sLmpzIiwiY2xhc3MgQmFkZ2VDb2xsZWN0aW9uIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05vZGVMaXN0fSBiYWRnZXNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoYmFkZ2VzKSB7XG4gICAgICAgIHRoaXMuYmFkZ2VzID0gYmFkZ2VzO1xuICAgIH07XG5cbiAgICBhcHBseVVuaWZvcm1XaWR0aCAoKSB7XG4gICAgICAgIGxldCBncmVhdGVzdFdpZHRoID0gdGhpcy5fZGVyaXZlR3JlYXRlc3RXaWR0aCgpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmJhZGdlcywgKGJhZGdlKSA9PiB7XG4gICAgICAgICAgICBiYWRnZS5zdHlsZS53aWR0aCA9IGdyZWF0ZXN0V2lkdGggKyAncHgnO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge251bWJlcn1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9kZXJpdmVHcmVhdGVzdFdpZHRoICgpIHtcbiAgICAgICAgbGV0IGdyZWF0ZXN0V2lkdGggPSAwO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLmJhZGdlcywgKGJhZGdlKSA9PiB7XG4gICAgICAgICAgICBpZiAoYmFkZ2Uub2Zmc2V0V2lkdGggPiBncmVhdGVzdFdpZHRoKSB7XG4gICAgICAgICAgICAgICAgZ3JlYXRlc3RXaWR0aCA9IGJhZGdlLm9mZnNldFdpZHRoO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gZ3JlYXRlc3RXaWR0aDtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQmFkZ2VDb2xsZWN0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2JhZGdlLWNvbGxlY3Rpb24uanMiLCJsZXQgQ29va2llT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi9lbGVtZW50L2Nvb2tpZS1vcHRpb25zLW1vZGFsJyk7XG5cbmNsYXNzIENvb2tpZU9wdGlvbnMge1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCwgY29va2llT3B0aW9uc01vZGFsLCBhY3Rpb25CYWRnZSwgc3RhdHVzRWxlbWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9uc01vZGFsID0gY29va2llT3B0aW9uc01vZGFsO1xuICAgICAgICB0aGlzLmFjdGlvbkJhZGdlID0gYWN0aW9uQmFkZ2U7XG4gICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudCA9IHN0YXR1c0VsZW1lbnQ7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldE1vZGFsT3BlbmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy5tb2RhbC5vcGVuZWQnO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbENsb3NlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnY29va2llLW9wdGlvbnMubW9kYWwuY2xvc2VkJztcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGxldCBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5pc0VtcHR5KCkpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ25vdCBlbmFibGVkJztcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGlvbkJhZGdlLm1hcmtOb3RFbmFibGVkKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudC5pbm5lclRleHQgPSAnZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrRW5hYmxlZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY29va2llT3B0aW9uc01vZGFsLmluaXQoKTtcblxuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lcigpO1xuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChDb29raWVPcHRpb25zLmdldE1vZGFsQ2xvc2VkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBDb29raWVPcHRpb25zO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2Nvb2tpZS1vcHRpb25zLmpzIiwiY2xhc3MgQWN0aW9uQmFkZ2Uge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgbWFya0VuYWJsZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWN0aW9uLWJhZGdlLWVuYWJsZWQnKTtcbiAgICB9XG5cbiAgICBtYXJrTm90RW5hYmxlZCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdhY3Rpb24tYmFkZ2UtZW5hYmxlZCcpO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBBY3Rpb25CYWRnZTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZS5qcyIsImxldCBic24gPSByZXF1aXJlKCdib290c3RyYXAubmF0aXZlJyk7XG5sZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uLy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xuXG5jbGFzcyBBbGVydCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcblxuICAgICAgICBsZXQgY2xvc2VCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jbG9zZScpO1xuICAgICAgICBpZiAoY2xvc2VCdXR0b24pIHtcbiAgICAgICAgICAgIGNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2xvc2VCdXR0b25DbGlja0V2ZW50SGFuZGxlci5iaW5kKHRoaXMpKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIHNldFN0eWxlIChzdHlsZSkge1xuICAgICAgICB0aGlzLl9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMoKTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWxlcnQtJyArIHN0eWxlKTtcbiAgICB9O1xuXG4gICAgd3JhcENvbnRlbnRJbkNvbnRhaW5lciAoKSB7XG4gICAgICAgIGxldCBjb250YWluZXIgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgY29udGFpbmVyLmNsYXNzTGlzdC5hZGQoJ2NvbnRhaW5lcicpO1xuXG4gICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSB0aGlzLmVsZW1lbnQuaW5uZXJIVE1MO1xuICAgICAgICB0aGlzLmVsZW1lbnQuaW5uZXJIVE1MID0gJyc7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZENoaWxkKGNvbnRhaW5lcik7XG4gICAgfTtcblxuICAgIF9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMgKCkge1xuICAgICAgICBsZXQgcHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCA9ICdhbGVydC0nO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuZm9yRWFjaCgoY2xhc3NOYW1lLCBpbmRleCwgY2xhc3NMaXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoY2xhc3NOYW1lLmluZGV4T2YocHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCkgPT09IDApIHtcbiAgICAgICAgICAgICAgICBjbGFzc0xpc3QucmVtb3ZlKGNsYXNzTmFtZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfY2xvc2VCdXR0b25DbGlja0V2ZW50SGFuZGxlciAoKSB7XG4gICAgICAgIGxldCByZWxhdGVkRmllbGRJZCA9IHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtZm9yJyk7XG4gICAgICAgIGlmIChyZWxhdGVkRmllbGRJZCkge1xuICAgICAgICAgICAgbGV0IHJlbGF0ZWRGaWVsZCA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHJlbGF0ZWRGaWVsZElkKTtcblxuICAgICAgICAgICAgaWYgKHJlbGF0ZWRGaWVsZCkge1xuICAgICAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIocmVsYXRlZEZpZWxkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGxldCBic25BbGVydCA9IG5ldyBic24uQWxlcnQodGhpcy5lbGVtZW50KTtcbiAgICAgICAgYnNuQWxlcnQuY2xvc2UoKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQWxlcnQ7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9hbGVydC5qcyIsImxldCBmb3JtRmllbGRGb2N1c2VyID0gcmVxdWlyZSgnLi4vLi4vZm9ybS1maWVsZC1mb2N1c2VyJyk7XG5cbmNsYXNzIENvb2tpZU9wdGlvbnNNb2RhbCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1jbG9zZV0nKTtcbiAgICAgICAgdGhpcy5hZGRCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1hZGQtYnV0dG9uJyk7XG4gICAgICAgIHRoaXMuYXBwbHlCdXR0b24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hbWU9YXBwbHknKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5fYWRkUmVtb3ZlQWN0aW9ucygpO1xuICAgICAgICB0aGlzLl9hZGRFdmVudExpc3RlbmVycygpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRPcGVuZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2Nvb2tpZS1vcHRpb25zLW1vZGFsLm9wZW5lZCc7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0Q2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5jbG9zZWQnO1xuICAgIH1cblxuICAgIF9yZW1vdmVBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCBjb29raWVEYXRhUm93Q291bnQgPSB0aGlzLnRhYmxlQm9keS5xdWVyeVNlbGVjdG9yQWxsKCcuanMtY29va2llJykubGVuZ3RoO1xuICAgICAgICBsZXQgcmVtb3ZlQWN0aW9uID0gZXZlbnQudGFyZ2V0O1xuICAgICAgICBsZXQgdGFibGVSb3cgPSB0aGlzLmVsZW1lbnQub3duZXJEb2N1bWVudC5nZXRFbGVtZW50QnlJZChyZW1vdmVBY3Rpb24uZ2V0QXR0cmlidXRlKCdkYXRhLWZvcicpKTtcblxuICAgICAgICBpZiAoY29va2llRGF0YVJvd0NvdW50ID09PSAxKSB7XG4gICAgICAgICAgICBsZXQgbmFtZUlucHV0ID0gdGFibGVSb3cucXVlcnlTZWxlY3RvcignLm5hbWUgaW5wdXQnKTtcblxuICAgICAgICAgICAgbmFtZUlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgICAgICB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcudmFsdWUgaW5wdXQnKS52YWx1ZSA9ICcnO1xuXG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKG5hbWVJbnB1dCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0YWJsZVJvdy5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHRhYmxlUm93KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0tleWJvYXJkRXZlbnR9IGV2ZW50XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfaW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgaWYgKGV2ZW50LnR5cGUgPT09ICdrZXlkb3duJyAmJiBldmVudC5rZXkgPT09ICdFbnRlcicpIHtcbiAgICAgICAgICAgIHRoaXMuYXBwbHlCdXR0b24uY2xpY2soKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfYWRkRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICBsZXQgc2hvd25FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50YWJsZUJvZHkgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcigndGJvZHknKTtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNUYWJsZUJvZHkgPSB0aGlzLnRhYmxlQm9keS5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgICAgICAgICBmb3JtRmllbGRGb2N1c2VyKHRoaXMudGFibGVCb2R5LnF1ZXJ5U2VsZWN0b3IoJy5qcy1jb29raWU6bGFzdC1vZi10eXBlIC5uYW1lIGlucHV0JykpO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnNNb2RhbC5nZXRPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRkZW5FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KENvb2tpZU9wdGlvbnNNb2RhbC5nZXRDbG9zZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudGFibGVCb2R5LnBhcmVudE5vZGUucmVwbGFjZUNoaWxkKHRoaXMucHJldmlvdXNUYWJsZUJvZHksIHRoaXMudGFibGVCb2R5KTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgYWRkQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgbGV0IHRhYmxlUm93ID0gdGhpcy5fY3JlYXRlVGFibGVSb3coKTtcbiAgICAgICAgICAgIGxldCByZW1vdmVBY3Rpb24gPSB0aGlzLl9jcmVhdGVSZW1vdmVBY3Rpb24odGFibGVSb3cuZ2V0QXR0cmlidXRlKCdkYXRhLWluZGV4JykpO1xuXG4gICAgICAgICAgICB0YWJsZVJvdy5xdWVyeVNlbGVjdG9yKCcucmVtb3ZlJykuYXBwZW5kQ2hpbGQocmVtb3ZlQWN0aW9uKTtcblxuICAgICAgICAgICAgdGhpcy50YWJsZUJvZHkuYXBwZW5kQ2hpbGQodGFibGVSb3cpO1xuICAgICAgICAgICAgdGhpcy5fYWRkUmVtb3ZlQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyKHJlbW92ZUFjdGlvbik7XG5cbiAgICAgICAgICAgIGZvcm1GaWVsZEZvY3VzZXIodGFibGVSb3cucXVlcnlTZWxlY3RvcignLm5hbWUgaW5wdXQnKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Nob3duLmJzLm1vZGFsJywgc2hvd25FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hpZGRlbi5icy5tb2RhbCcsIGhpZGRlbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmFkZEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGFkZEJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lcik7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuanMtcmVtb3ZlJyksIChyZW1vdmVBY3Rpb24pID0+IHtcbiAgICAgICAgICAgIHRoaXMuX2FkZFJlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lcihyZW1vdmVBY3Rpb24pO1xuICAgICAgICB9KTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5uYW1lIGlucHV0LCAudmFsdWUgaW5wdXQnKSwgKGlucHV0KSA9PiB7XG4gICAgICAgICAgICBpbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXlkb3duJywgdGhpcy5faW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9hZGRSZW1vdmVBY3Rpb25zICgpIHtcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcucmVtb3ZlJyksIChyZW1vdmVDb250YWluZXIsIGluZGV4KSA9PiB7XG4gICAgICAgICAgICByZW1vdmVDb250YWluZXIuYXBwZW5kQ2hpbGQodGhpcy5fY3JlYXRlUmVtb3ZlQWN0aW9uKGluZGV4KSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IHJlbW92ZUFjdGlvblxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2FkZFJlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lciAocmVtb3ZlQWN0aW9uKSB7XG4gICAgICAgIHJlbW92ZUFjdGlvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX3JlbW92ZUFjdGlvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IGluZGV4XG4gICAgICogQHJldHVybnMge0VsZW1lbnQgfCBudWxsfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVJlbW92ZUFjdGlvbiAoaW5kZXgpIHtcbiAgICAgICAgbGV0IHJlbW92ZUFjdGlvbkNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICByZW1vdmVBY3Rpb25Db250YWluZXIuaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmEgZmEtdHJhc2gtbyBqcy1yZW1vdmVcIiB0aXRsZT1cIlJlbW92ZSB0aGlzIGNvb2tpZVwiIGRhdGEtZm9yPVwiY29va2llLWRhdGEtcm93LScgKyBpbmRleCArICdcIj48L2k+JztcblxuICAgICAgICByZXR1cm4gcmVtb3ZlQWN0aW9uQ29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5qcy1yZW1vdmUnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge05vZGV9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlVGFibGVSb3cgKCkge1xuICAgICAgICBsZXQgbmV4dENvb2tpZUluZGV4ID0gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1uZXh0LWNvb2tpZS1pbmRleCcpO1xuICAgICAgICBsZXQgbGFzdFRhYmxlUm93ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1jb29raWUnKTtcbiAgICAgICAgbGV0IHRhYmxlUm93ID0gbGFzdFRhYmxlUm93LmNsb25lTm9kZSh0cnVlKTtcbiAgICAgICAgbGV0IG5hbWVJbnB1dCA9IHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lIGlucHV0Jyk7XG4gICAgICAgIGxldCB2YWx1ZUlucHV0ID0gdGFibGVSb3cucXVlcnlTZWxlY3RvcignLnZhbHVlIGlucHV0Jyk7XG5cbiAgICAgICAgbmFtZUlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgIG5hbWVJbnB1dC5zZXRBdHRyaWJ1dGUoJ25hbWUnLCAnY29va2llc1snICsgbmV4dENvb2tpZUluZGV4ICsgJ11bbmFtZV0nKTtcbiAgICAgICAgbmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2tleXVwJywgdGhpcy5faW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdmFsdWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICB2YWx1ZUlucHV0LnNldEF0dHJpYnV0ZSgnbmFtZScsICdjb29raWVzWycgKyBuZXh0Q29va2llSW5kZXggKyAnXVt2YWx1ZV0nKTtcbiAgICAgICAgdmFsdWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXl1cCcsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG5cbiAgICAgICAgdGFibGVSb3cuc2V0QXR0cmlidXRlKCdkYXRhLWluZGV4JywgbmV4dENvb2tpZUluZGV4KTtcbiAgICAgICAgdGFibGVSb3cuc2V0QXR0cmlidXRlKCdpZCcsICdjb29raWUtZGF0YS1yb3ctJyArIG5leHRDb29raWVJbmRleCk7XG4gICAgICAgIHRhYmxlUm93LnF1ZXJ5U2VsZWN0b3IoJy5yZW1vdmUnKS5pbm5lckhUTUwgPSAnJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdkYXRhLW5leHQtY29va2llLWluZGV4JywgcGFyc2VJbnQobmV4dENvb2tpZUluZGV4LCAxMCkgKyAxKTtcblxuICAgICAgICByZXR1cm4gdGFibGVSb3c7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzRW1wdHkgKCkge1xuICAgICAgICBsZXQgaXNFbXB0eSA9IHRydWU7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbnB1dCcpLCAoaW5wdXQpID0+IHtcbiAgICAgICAgICAgIGlmIChpc0VtcHR5ICYmIGlucHV0LnZhbHVlLnRyaW0oKSAhPT0gJycpIHtcbiAgICAgICAgICAgICAgICBpc0VtcHR5ID0gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBpc0VtcHR5O1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQ29va2llT3B0aW9uc01vZGFsO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvY29va2llLW9wdGlvbnMtbW9kYWwuanMiLCJsZXQgSWNvbiA9IHJlcXVpcmUoJy4vaWNvbicpO1xuXG5jbGFzcyBGb3JtQnV0dG9uIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICBsZXQgaWNvbkVsZW1lbnQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoSWNvbi5nZXRTZWxlY3RvcigpKTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmljb24gPSBpY29uRWxlbWVudCA/IG5ldyBJY29uKGljb25FbGVtZW50KSA6IG51bGw7XG4gICAgfVxuXG4gICAgbWFya0FzQnVzeSAoKSB7XG4gICAgICAgIGlmICh0aGlzLmljb24pIHtcbiAgICAgICAgICAgIHRoaXMuaWNvbi5zZXRCdXN5KCk7XG4gICAgICAgICAgICB0aGlzLmRlRW1waGFzaXplKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBtYXJrQXNBdmFpbGFibGUgKCkge1xuICAgICAgICBpZiAodGhpcy5pY29uKSB7XG4gICAgICAgICAgICB0aGlzLmljb24uc2V0QXZhaWxhYmxlKCdmYS1jYXJldC1yaWdodCcpO1xuICAgICAgICAgICAgdGhpcy51bkRlRW1waGFzaXplKCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBtYXJrU3VjY2VlZGVkICgpIHtcbiAgICAgICAgaWYgKHRoaXMuaWNvbikge1xuICAgICAgICAgICAgdGhpcy5pY29uLnNldFN1Y2Nlc3NmdWwoKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIGRpc2FibGUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdkaXNhYmxlZCcsICdkaXNhYmxlZCcpO1xuICAgIH1cblxuICAgIGVuYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoJ2Rpc2FibGVkJyk7XG4gICAgfVxuXG4gICAgZGVFbXBoYXNpemUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGUtZW1waGFzaXplJyk7XG4gICAgfTtcblxuICAgIHVuRGVFbXBoYXNpemUgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGUtZW1waGFzaXplJyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtQnV0dG9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24uanMiLCJsZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uLy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMudXNlcm5hbWVJbnB1dCA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW25hbWU9aHR0cC1hdXRoLXVzZXJuYW1lXScpO1xuICAgICAgICB0aGlzLnBhc3N3b3JkSW5wdXQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPWh0dHAtYXV0aC1wYXNzd29yZF0nKTtcbiAgICAgICAgdGhpcy5hcHBseUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1hcHBseV0nKTtcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1jbG9zZV0nKTtcbiAgICAgICAgdGhpcy5jbGVhckJ1dHRvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtbmFtZT1jbGVhcl0nKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c1VzZXJuYW1lID0gbnVsbDtcbiAgICAgICAgdGhpcy5wcmV2aW91c1Bhc3N3b3JkID0gbnVsbDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRPcGVuZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ2Nvb2tpZS1vcHRpb25zLW1vZGFsLm9wZW5lZCc7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0Q2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdjb29raWUtb3B0aW9ucy1tb2RhbC5jbG9zZWQnO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLl9hZGRFdmVudExpc3RlbmVycygpO1xuICAgIH07XG5cbiAgICBpc0VtcHR5ICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZS50cmltKCkgPT09ICcnICYmIHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZS50cmltKCkgPT09ICcnO1xuICAgIH07XG5cbiAgICBfYWRkRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICBsZXQgc2hvd25FdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5wcmV2aW91c1VzZXJuYW1lID0gdGhpcy51c2VybmFtZUlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgICAgIHRoaXMucHJldmlvdXNQYXNzd29yZCA9IHRoaXMucGFzc3dvcmRJbnB1dC52YWx1ZS50cmltKCk7XG5cbiAgICAgICAgICAgIGxldCB1c2VybmFtZSA9IHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgICAgICBsZXQgcGFzc3dvcmQgPSB0aGlzLnBhc3N3b3JkSW5wdXQudmFsdWUudHJpbSgpO1xuXG4gICAgICAgICAgICBsZXQgZm9jdXNlZElucHV0ID0gKHVzZXJuYW1lID09PSAnJyB8fCAodXNlcm5hbWUgIT09ICcnICYmIHBhc3N3b3JkICE9PSAnJykpXG4gICAgICAgICAgICAgICAgPyB0aGlzLnVzZXJuYW1lSW5wdXRcbiAgICAgICAgICAgICAgICA6IHRoaXMucGFzc3dvcmRJbnB1dDtcblxuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcihmb2N1c2VkSW5wdXQpO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGhpZGRlbkV2ZW50TGlzdGVuZXIgPSAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGNsb3NlQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy51c2VybmFtZUlucHV0LnZhbHVlID0gdGhpcy5wcmV2aW91c1VzZXJuYW1lO1xuICAgICAgICAgICAgdGhpcy5wYXNzd29yZElucHV0LnZhbHVlID0gdGhpcy5wcmV2aW91c1Bhc3N3b3JkO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBjbGVhckJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMudXNlcm5hbWVJbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICAgICAgdGhpcy5wYXNzd29yZElucHV0LnZhbHVlID0gJyc7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Nob3duLmJzLm1vZGFsJywgc2hvd25FdmVudExpc3RlbmVyKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hpZGRlbi5icy5tb2RhbCcsIGhpZGRlbkV2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLmNsZWFyQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIpO1xuICAgICAgICB0aGlzLnVzZXJuYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIHRoaXMuX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMucGFzc3dvcmRJbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXlkb3duJywgdGhpcy5faW5wdXRLZXlEb3duRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtLZXlib2FyZEV2ZW50fSBldmVudFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2lucHV0S2V5RG93bkV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC50eXBlID09PSAna2V5ZG93bicgJiYgZXZlbnQua2V5ID09PSAnRW50ZXInKSB7XG4gICAgICAgICAgICB0aGlzLmFwcGx5QnV0dG9uLmNsaWNrKCk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbC5qcyIsImNsYXNzIEljb24ge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgfVxuXG4gICAgc3RhdGljIGdldENsYXNzICgpIHtcbiAgICAgICAgcmV0dXJuICdmYSc7XG4gICAgfVxuXG4gICAgc3RhdGljIGdldFNlbGVjdG9yICgpIHtcbiAgICAgICAgcmV0dXJuICcuJyArIEljb24uZ2V0Q2xhc3MoKTtcbiAgICB9O1xuXG4gICAgc2V0QnVzeSAoKSB7XG4gICAgICAgIHRoaXMucmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcygpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZmEtc3Bpbm5lcicsICdmYS1zcGluJyk7XG4gICAgfTtcblxuICAgIHNldEF2YWlsYWJsZSAoYWN0aXZlSWNvbkNsYXNzID0gbnVsbCkge1xuICAgICAgICB0aGlzLnJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMoKTtcblxuICAgICAgICBpZiAoYWN0aXZlSWNvbkNsYXNzICE9PSBudWxsKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZChhY3RpdmVJY29uQ2xhc3MpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHNldFN1Y2Nlc3NmdWwgKCkge1xuICAgICAgICB0aGlzLnJlbW92ZVByZXNlbnRhdGlvbkNsYXNzZXMoKTtcbiAgICAgICAgdGhpcy5zZXRBdmFpbGFibGUoJ2ZhLWNoZWNrJyk7XG4gICAgfVxuXG4gICAgcmVtb3ZlUHJlc2VudGF0aW9uQ2xhc3NlcyAoKSB7XG4gICAgICAgIGxldCBjbGFzc2VzVG9SZXRhaW4gPSBbXG4gICAgICAgICAgICBJY29uLmdldENsYXNzKCksXG4gICAgICAgICAgICBJY29uLmdldENsYXNzKCkgKyAnLWZ3J1xuICAgICAgICBdO1xuXG4gICAgICAgIGxldCBwcmVzZW50YXRpb25hbENsYXNzUHJlZml4ID0gSWNvbi5nZXRDbGFzcygpICsgJy0nO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuZm9yRWFjaCgoY2xhc3NOYW1lLCBpbmRleCwgY2xhc3NMaXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoIWNsYXNzZXNUb1JldGFpbi5pbmNsdWRlcyhjbGFzc05hbWUpICYmIGNsYXNzTmFtZS5pbmRleE9mKHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEljb247XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9pY29uLmpzIiwibGV0IFByb2dyZXNzaW5nTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4vcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QnKTtcblxuY2xhc3MgQ3Jhd2xpbmdMaXN0ZWRUZXN0IGV4dGVuZHMgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0IHtcbiAgICByZW5kZXJGcm9tTGlzdGVkVGVzdCAobGlzdGVkVGVzdCkge1xuICAgICAgICBzdXBlci5yZW5kZXJGcm9tTGlzdGVkVGVzdChsaXN0ZWRUZXN0KTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByb2Nlc3NlZC11cmwtY291bnQnKS5pbm5lclRleHQgPSBsaXN0ZWRUZXN0LmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXByb2Nlc3NlZC11cmwtY291bnQnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5kaXNjb3ZlcmVkLXVybC1jb3VudCcpLmlubmVyVGV4dCA9IGxpc3RlZFRlc3QuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtZGlzY292ZXJlZC11cmwtY291bnQnKTtcbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnQ3Jhd2xpbmdMaXN0ZWRUZXN0JztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IENyYXdsaW5nTGlzdGVkVGVzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L2NyYXdsaW5nLWxpc3RlZC10ZXN0LmpzIiwiY2xhc3MgTGlzdGVkVGVzdCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5pbml0KGVsZW1lbnQpO1xuICAgIH1cblxuICAgIGluaXQgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICB9XG5cbiAgICBlbmFibGUgKCkge307XG5cbiAgICBnZXRUZXN0SWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10ZXN0LWlkJyk7XG4gICAgfTtcblxuICAgIGdldFN0YXRlICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcbiAgICB9XG5cbiAgICBpc0ZpbmlzaGVkICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZpbmlzaGVkJyk7XG4gICAgfVxuXG4gICAgcmVuZGVyRnJvbUxpc3RlZFRlc3QgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgaWYgKHRoaXMuaXNGaW5pc2hlZCgpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5nZXRTdGF0ZSgpICE9PSBsaXN0ZWRUZXN0LmdldFN0YXRlKCkpIHtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZChsaXN0ZWRUZXN0LmVsZW1lbnQsIHRoaXMuZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLmluaXQobGlzdGVkVGVzdC5lbGVtZW50KTtcbiAgICAgICAgICAgIHRoaXMuZW5hYmxlKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VHlwZSAoKSB7XG4gICAgICAgIHJldHVybiAnTGlzdGVkVGVzdCc7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBMaXN0ZWRUZXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvbGlzdGVkLXRlc3QuanMiLCJsZXQgUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi9wcm9ncmVzc2luZy1saXN0ZWQtdGVzdCcpO1xubGV0IFRlc3RSZXN1bHRSZXRyaWV2ZXIgPSByZXF1aXJlKCcuLi8uLi8uLi9zZXJ2aWNlcy90ZXN0LXJlc3VsdC1yZXRyaWV2ZXInKTtcblxuY2xhc3MgUHJlcGFyaW5nTGlzdGVkVGVzdCBleHRlbmRzIFByb2dyZXNzaW5nTGlzdGVkVGVzdCB7XG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5faW5pdGlhbGlzZVJlc3VsdFJldHJpZXZlcigpO1xuICAgIH07XG5cbiAgICBfaW5pdGlhbGlzZVJlc3VsdFJldHJpZXZlciAoKSB7XG4gICAgICAgIGxldCBwcmVwYXJpbmdFbGVtZW50ID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcmVwYXJpbmcnKTtcbiAgICAgICAgbGV0IHRlc3RSZXN1bHRzUmV0cmlldmVyID0gbmV3IFRlc3RSZXN1bHRSZXRyaWV2ZXIocHJlcGFyaW5nRWxlbWVudCk7XG5cbiAgICAgICAgcHJlcGFyaW5nRWxlbWVudC5hZGRFdmVudExpc3RlbmVyKHRlc3RSZXN1bHRzUmV0cmlldmVyLmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCAocmV0cmlldmVkRXZlbnQpID0+IHtcbiAgICAgICAgICAgIGxldCBwYXJlbnROb2RlID0gdGhpcy5lbGVtZW50LnBhcmVudE5vZGU7XG4gICAgICAgICAgICBsZXQgcmV0cmlldmVkTGlzdGVkVGVzdCA9IHJldHJpZXZlZEV2ZW50LmRldGFpbDtcbiAgICAgICAgICAgIHJldHJpZXZlZExpc3RlZFRlc3QuY2xhc3NMaXN0LmFkZCgnZmFkZS1vdXQnKTtcblxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3RyYW5zaXRpb25lbmQnLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgcGFyZW50Tm9kZS5yZXBsYWNlQ2hpbGQocmV0cmlldmVkTGlzdGVkVGVzdCwgdGhpcy5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQgPSByZXRyaWV2ZWRFdmVudC5kZXRhaWw7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2ZhZGUtaW4nKTtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZmFkZS1vdXQnKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZmFkZS1vdXQnKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGVzdFJlc3VsdHNSZXRyaWV2ZXIuaW5pdCgpO1xuICAgIH07XG5cbiAgICBnZXRUeXBlICgpIHtcbiAgICAgICAgcmV0dXJuICdQcmVwYXJpbmdMaXN0ZWRUZXN0JztcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFByZXBhcmluZ0xpc3RlZFRlc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcmVwYXJpbmctbGlzdGVkLXRlc3QuanMiLCJsZXQgTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4vbGlzdGVkLXRlc3QnKTtcbmxldCBQcm9ncmVzc0JhciA9IHJlcXVpcmUoJy4uL3Byb2dyZXNzLWJhcicpO1xuXG5jbGFzcyBQcm9ncmVzc2luZ0xpc3RlZFRlc3QgZXh0ZW5kcyBMaXN0ZWRUZXN0IHtcbiAgICBpbml0IChlbGVtZW50KSB7XG4gICAgICAgIHN1cGVyLmluaXQoZWxlbWVudCk7XG5cbiAgICAgICAgbGV0IHByb2dyZXNzQmFyRWxlbWVudCA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcucHJvZ3Jlc3MtYmFyJyk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBwcm9ncmVzc0JhckVsZW1lbnQgPyBuZXcgUHJvZ3Jlc3NCYXIocHJvZ3Jlc3NCYXJFbGVtZW50KSA6IG51bGw7XG4gICAgfVxuXG4gICAgZ2V0Q29tcGxldGlvblBlcmNlbnQgKCkge1xuICAgICAgICBsZXQgY29tcGxldGlvblBlcmNlbnQgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWNvbXBsZXRpb24tcGVyY2VudCcpO1xuXG4gICAgICAgIGlmICh0aGlzLmlzRmluaXNoZWQoKSAmJiBjb21wbGV0aW9uUGVyY2VudCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIDEwMDtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBwYXJzZUZsb2F0KHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtY29tcGxldGlvbi1wZXJjZW50JykpO1xuICAgIH1cblxuICAgIHNldENvbXBsZXRpb25QZXJjZW50IChjb21wbGV0aW9uUGVyY2VudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBsZXRpb24tcGVyY2VudCcsIGNvbXBsZXRpb25QZXJjZW50KTtcbiAgICB9O1xuXG4gICAgcmVuZGVyRnJvbUxpc3RlZFRlc3QgKGxpc3RlZFRlc3QpIHtcbiAgICAgICAgc3VwZXIucmVuZGVyRnJvbUxpc3RlZFRlc3QobGlzdGVkVGVzdCk7XG5cbiAgICAgICAgaWYgKHRoaXMuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSA9PT0gbGlzdGVkVGVzdC5nZXRDb21wbGV0aW9uUGVyY2VudCgpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnNldENvbXBsZXRpb25QZXJjZW50KGxpc3RlZFRlc3QuZ2V0Q29tcGxldGlvblBlcmNlbnQoKSk7XG5cbiAgICAgICAgaWYgKHRoaXMucHJvZ3Jlc3NCYXIpIHtcbiAgICAgICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQodGhpcy5nZXRDb21wbGV0aW9uUGVyY2VudCgpKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBnZXRUeXBlICgpIHtcbiAgICAgICAgcmV0dXJuICdQcm9ncmVzc2luZ0xpc3RlZFRlc3QnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gUHJvZ3Jlc3NpbmdMaXN0ZWRUZXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvcHJvZ3Jlc3NpbmctbGlzdGVkLXRlc3QuanMiLCJjbGFzcyBQcm9ncmVzc0JhciB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICB9XG5cbiAgICBzZXRDb21wbGV0aW9uUGVyY2VudCAoY29tcGxldGlvblBlcmNlbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LnN0eWxlLndpZHRoID0gY29tcGxldGlvblBlcmNlbnQgKyAnJSc7XG4gICAgICAgIHRoaXMuZWxlbWVudC5zZXRBdHRyaWJ1dGUoJ2FyaWEtdmFsdWVub3cnLCBjb21wbGV0aW9uUGVyY2VudCk7XG4gICAgfVxuXG4gICAgc2V0U3R5bGUgKHN0eWxlKSB7XG4gICAgICAgIHRoaXMuX3JlbW92ZVByZXNlbnRhdGlvbmFsQ2xhc3NlcygpO1xuXG4gICAgICAgIGlmIChzdHlsZSA9PT0gJ3dhcm5pbmcnKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgncHJvZ3Jlc3MtYmFyLXdhcm5pbmcnKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIF9yZW1vdmVQcmVzZW50YXRpb25hbENsYXNzZXMgKCkge1xuICAgICAgICBsZXQgcHJlc2VudGF0aW9uYWxDbGFzc1ByZWZpeCA9ICdwcm9ncmVzcy1iYXItJztcblxuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNsYXNzTmFtZSwgaW5kZXgsIGNsYXNzTGlzdCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNsYXNzTmFtZS5pbmRleE9mKHByZXNlbnRhdGlvbmFsQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFByb2dyZXNzQmFyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyLmpzIiwiY2xhc3MgU29ydENvbnRyb2wge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMua2V5cyA9IEpTT04ucGFyc2UoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc29ydC1rZXlzJykpO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGhpcy5fY2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRTb3J0UmVxdWVzdGVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdzb3J0LWNvbnRyb2wuc29ydC5yZXF1ZXN0ZWQnO1xuICAgIH07XG5cbiAgICBfY2xpY2tFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ3NvcnRlZCcpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoU29ydENvbnRyb2wuZ2V0U29ydFJlcXVlc3RlZEV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICBkZXRhaWw6IHtcbiAgICAgICAgICAgICAgICBrZXlzOiB0aGlzLmtleXNcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSkpO1xuICAgIH07XG5cbiAgICBzZXRTb3J0ZWQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnc29ydGVkJyk7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdsaW5rJyk7XG4gICAgfTtcblxuICAgIHNldE5vdFNvcnRlZCAoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdzb3J0ZWQnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2xpbmsnKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRDb250cm9sO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sLmpzIiwiY2xhc3MgU29ydGFibGVJdGVtIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnNvcnRWYWx1ZXMgPSBKU09OLnBhcnNlKGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvcnQtdmFsdWVzJykpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ30ga2V5XG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7Kn1cbiAgICAgKi9cbiAgICBnZXRTb3J0VmFsdWUgKGtleSkge1xuICAgICAgICByZXR1cm4gdGhpcy5zb3J0VmFsdWVzW2tleV07XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRhYmxlSXRlbTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3NvcnRhYmxlLWl0ZW0uanMiLCJsZXQgVGFzayA9IHJlcXVpcmUoJy4vdGFzaycpO1xuXG5jbGFzcyBUYXNrTGlzdCB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5wYWdlSW5kZXggPSBlbGVtZW50ID8gcGFyc2VJbnQoZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMCkgOiBudWxsO1xuICAgICAgICB0aGlzLnRhc2tzID0ge307XG5cbiAgICAgICAgaWYgKGVsZW1lbnQpIHtcbiAgICAgICAgICAgIFtdLmZvckVhY2guY2FsbChlbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrJyksICh0YXNrRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCB0YXNrID0gbmV3IFRhc2sodGFza0VsZW1lbnQpO1xuICAgICAgICAgICAgICAgIHRoaXMudGFza3NbdGFzay5nZXRJZCgpXSA9IHRhc2s7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtudW1iZXIgfCBudWxsfVxuICAgICAqL1xuICAgIGdldFBhZ2VJbmRleCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnBhZ2VJbmRleDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBoYXNQYWdlSW5kZXggKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5wYWdlSW5kZXggIT09IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtzdHJpbmdbXX0gc3RhdGVzXG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7VGFza1tdfVxuICAgICAqL1xuICAgIGdldFRhc2tzQnlTdGF0ZXMgKHN0YXRlcykge1xuICAgICAgICBjb25zdCBzdGF0ZXNMZW5ndGggPSBzdGF0ZXMubGVuZ3RoO1xuICAgICAgICBsZXQgdGFza3MgPSBbXTtcblxuICAgICAgICBmb3IgKGxldCBzdGF0ZUluZGV4ID0gMDsgc3RhdGVJbmRleCA8IHN0YXRlc0xlbmd0aDsgc3RhdGVJbmRleCsrKSB7XG4gICAgICAgICAgICBsZXQgc3RhdGUgPSBzdGF0ZXNbc3RhdGVJbmRleF07XG5cbiAgICAgICAgICAgIHRhc2tzID0gdGFza3MuY29uY2F0KHRoaXMuZ2V0VGFza3NCeVN0YXRlKHN0YXRlKSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFza3M7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBzdGF0ZVxuICAgICAqXG4gICAgICogQHJldHVybnMge1Rhc2tbXX1cbiAgICAgKi9cbiAgICBnZXRUYXNrc0J5U3RhdGUgKHN0YXRlKSB7XG4gICAgICAgIGxldCB0YXNrcyA9IFtdO1xuICAgICAgICBPYmplY3Qua2V5cyh0aGlzLnRhc2tzKS5mb3JFYWNoKCh0YXNrSWQpID0+IHtcbiAgICAgICAgICAgIGxldCB0YXNrID0gdGhpcy50YXNrc1t0YXNrSWRdO1xuXG4gICAgICAgICAgICBpZiAodGFzay5nZXRTdGF0ZSgpID09PSBzdGF0ZSkge1xuICAgICAgICAgICAgICAgIHRhc2tzLnB1c2godGFzayk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiB0YXNrcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtUYXNrTGlzdH0gdXBkYXRlZFRhc2tMaXN0XG4gICAgICovXG4gICAgdXBkYXRlRnJvbVRhc2tMaXN0ICh1cGRhdGVkVGFza0xpc3QpIHtcbiAgICAgICAgT2JqZWN0LmtleXModXBkYXRlZFRhc2tMaXN0LnRhc2tzKS5mb3JFYWNoKCh0YXNrSWQpID0+IHtcbiAgICAgICAgICAgIGxldCB1cGRhdGVkVGFzayA9IHVwZGF0ZWRUYXNrTGlzdC50YXNrc1t0YXNrSWRdO1xuICAgICAgICAgICAgbGV0IHRhc2sgPSB0aGlzLnRhc2tzW3VwZGF0ZWRUYXNrLmdldElkKCldO1xuXG4gICAgICAgICAgICB0YXNrLnVwZGF0ZUZyb21UYXNrKHVwZGF0ZWRUYXNrKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2stbGlzdC5qcyIsImNsYXNzIFRhc2tRdWV1ZSB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy52YWx1ZSA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnZhbHVlJyk7XG4gICAgICAgIHRoaXMubGFiZWwgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5sYWJlbC12YWx1ZScpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmxhYmVsLnN0eWxlLndpZHRoID0gdGhpcy5sYWJlbC5nZXRBdHRyaWJ1dGUoJ2RhdGEtd2lkdGgnKSArICclJztcbiAgICB9O1xuXG4gICAgZ2V0UXVldWVJZCAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXF1ZXVlLWlkJyk7XG4gICAgfTtcblxuICAgIHNldFZhbHVlICh2YWx1ZSkge1xuICAgICAgICB0aGlzLnZhbHVlLmlubmVyVGV4dCA9IHZhbHVlO1xuICAgIH07XG5cbiAgICBzZXRXaWR0aCAod2lkdGgpIHtcbiAgICAgICAgdGhpcy5sYWJlbC5zdHlsZS53aWR0aCA9IHdpZHRoICsgJyUnO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza1F1ZXVlO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZS5qcyIsImxldCBUYXNrUXVldWUgPSByZXF1aXJlKCcuL3Rhc2stcXVldWUnKTtcblxuY2xhc3MgVGFza1F1ZXVlcyB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5xdWV1ZXMgPSB7fTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwoZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCcucXVldWUnKSwgKHF1ZXVlRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHF1ZXVlID0gbmV3IFRhc2tRdWV1ZShxdWV1ZUVsZW1lbnQpO1xuICAgICAgICAgICAgcXVldWUuaW5pdCgpO1xuICAgICAgICAgICAgdGhpcy5xdWV1ZXNbcXVldWUuZ2V0UXVldWVJZCgpXSA9IHF1ZXVlO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICByZW5kZXIgKHRhc2tDb3VudCwgdGFza0NvdW50QnlTdGF0ZSkge1xuICAgICAgICBsZXQgZ2V0V2lkdGhGb3JTdGF0ZSA9IChzdGF0ZSkgPT4ge1xuICAgICAgICAgICAgaWYgKHRhc2tDb3VudCA9PT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoIXRhc2tDb3VudEJ5U3RhdGUuaGFzT3duUHJvcGVydHkoc3RhdGUpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIDA7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0YXNrQ291bnRCeVN0YXRlW3N0YXRlXSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICByZXR1cm4gTWF0aC5jZWlsKHRhc2tDb3VudEJ5U3RhdGVbc3RhdGVdIC8gdGFza0NvdW50ICogMTAwKTtcbiAgICAgICAgfTtcblxuICAgICAgICBPYmplY3Qua2V5cyh0YXNrQ291bnRCeVN0YXRlKS5mb3JFYWNoKChzdGF0ZSkgPT4ge1xuICAgICAgICAgICAgbGV0IHF1ZXVlID0gdGhpcy5xdWV1ZXNbc3RhdGVdO1xuICAgICAgICAgICAgaWYgKHF1ZXVlKSB7XG4gICAgICAgICAgICAgICAgcXVldWUuc2V0VmFsdWUodGFza0NvdW50QnlTdGF0ZVtzdGF0ZV0pO1xuICAgICAgICAgICAgICAgIHF1ZXVlLnNldFdpZHRoKGdldFdpZHRoRm9yU3RhdGUoc3RhdGUpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUYXNrUXVldWVzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGFzay1xdWV1ZXMuanMiLCJjbGFzcyBUYXNrIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgIH1cblxuICAgIGdldFN0YXRlICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RhdGUnKTtcbiAgICB9O1xuXG4gICAgZ2V0SWQgKCkge1xuICAgICAgICByZXR1cm4gcGFyc2VJbnQodGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLWlkJyksIDEwKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1Rhc2t9IHVwZGF0ZWRUYXNrXG4gICAgICovXG4gICAgdXBkYXRlRnJvbVRhc2sgKHVwZGF0ZWRUYXNrKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZCh1cGRhdGVkVGFzay5lbGVtZW50LCB0aGlzLmVsZW1lbnQpO1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB1cGRhdGVkVGFzay5lbGVtZW50O1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFzaztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rhc2suanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5sZXQgQWxlcnRGYWN0b3J5ID0gcmVxdWlyZSgnLi4vLi4vc2VydmljZXMvYWxlcnQtZmFjdG9yeScpO1xuXG5jbGFzcyBUZXN0QWxlcnRDb250YWluZXIge1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuYWxlcnQgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5hbGVydC1hbW1lbmRtZW50Jyk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGlmICghdGhpcy5hbGVydCkge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgX2h0dHBSZXF1ZXN0UmV0cmlldmVkRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IGFsZXJ0ID0gQWxlcnRGYWN0b3J5LmNyZWF0ZUZyb21Db250ZW50KHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LCBldmVudC5kZXRhaWwucmVzcG9uc2UpO1xuICAgICAgICBhbGVydC5zZXRTdHlsZSgnaW5mbycpO1xuICAgICAgICBhbGVydC53cmFwQ29udGVudEluQ29udGFpbmVyKCk7XG4gICAgICAgIGFsZXJ0LmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWxlcnQtYW1tZW5kbWVudCcpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChhbGVydC5lbGVtZW50KTtcbiAgICAgICAgdGhpcy5hbGVydCA9IGFsZXJ0O1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdyZXZlYWwnKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3RyYW5zaXRpb25lbmQnLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmFsZXJ0LmVsZW1lbnQuY2xhc3NMaXN0LmFkZCgncmV2ZWFsJyk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICByZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbiAoKSB7XG4gICAgICAgIGlmICghdGhpcy5hbGVydCkge1xuICAgICAgICAgICAgSHR0cENsaWVudC5nZXQodGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11cmwtbGltaXQtbm90aWZpY2F0aW9uLXVybCcpLCAndGV4dCcsIHRoaXMuZWxlbWVudCwgJ2RlZmF1bHQnKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdEFsZXJ0Q29udGFpbmVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXIuanMiLCJsZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uLy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5sZXQgSWNvbiA9IHJlcXVpcmUoJy4uL2VsZW1lbnQvaWNvbicpO1xuXG5jbGFzcyBUZXN0TG9ja1VubG9jayB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG4gICAgICAgIHRoaXMuZGF0YSA9IHtcbiAgICAgICAgICAgIGxvY2tlZDogSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1sb2NrZWQnKSksXG4gICAgICAgICAgICB1bmxvY2tlZDogSlNPTi5wYXJzZShlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11bmxvY2tlZCcpKVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmljb24gPSBuZXcgSWNvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoSWNvbi5nZXRTZWxlY3RvcigpKSk7XG4gICAgICAgIHRoaXMuYWN0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuYWN0aW9uJyk7XG4gICAgICAgIHRoaXMuZGVzY3JpcHRpb24gPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5kZXNjcmlwdGlvbicpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnaW52aXNpYmxlJyk7XG4gICAgICAgIHRoaXMuX3JlbmRlcigpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2RlLWVtcGhhc2l6ZScpO1xuICAgICAgICAgICAgdGhpcy5fdG9nZ2xlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBfdG9nZ2xlICgpIHtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IHRoaXMuc3RhdGUgPT09ICdsb2NrZWQnID8gJ3VubG9ja2VkJyA6ICdsb2NrZWQnO1xuICAgICAgICB0aGlzLl9yZW5kZXIoKTtcbiAgICB9O1xuXG4gICAgX3JlbmRlciAoKSB7XG4gICAgICAgIHRoaXMuaWNvbi5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgbGV0IHN0YXRlRGF0YSA9IHRoaXMuZGF0YVt0aGlzLnN0YXRlXTtcblxuICAgICAgICB0aGlzLmljb24uc2V0QXZhaWxhYmxlKCdmYS0nICsgc3RhdGVEYXRhLmljb24pO1xuICAgICAgICB0aGlzLmFjdGlvbi5pbm5lclRleHQgPSBzdGF0ZURhdGEuYWN0aW9uO1xuICAgICAgICB0aGlzLmRlc2NyaXB0aW9uLmlubmVyVGV4dCA9IHN0YXRlRGF0YS5kZXNjcmlwdGlvbjtcbiAgICB9O1xuXG4gICAgX2NsaWNrRXZlbnRMaXN0ZW5lciAoKSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHRoaXMuaWNvbi5yZW1vdmVQcmVzZW50YXRpb25DbGFzc2VzKCk7XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2RlLWVtcGhhc2l6ZScpO1xuICAgICAgICB0aGlzLmljb24uc2V0QnVzeSgpO1xuXG4gICAgICAgIEh0dHBDbGllbnQucG9zdCh0aGlzLmRhdGFbdGhpcy5zdGF0ZV0udXJsLCB0aGlzLmVsZW1lbnQsICdkZWZhdWx0Jyk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0TG9ja1VubG9jaztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9lbGVtZW50L3Rlc3QtbG9jay11bmxvY2suanMiLCJsZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsID0gcmVxdWlyZSgnLi9lbGVtZW50L2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy1tb2RhbCcpO1xuXG5jbGFzcyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zIHtcbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQsIGh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCwgYWN0aW9uQmFkZ2UsIHN0YXR1c0VsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IGRvY3VtZW50O1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IGh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbDtcbiAgICAgICAgdGhpcy5hY3Rpb25CYWRnZSA9IGFjdGlvbkJhZGdlO1xuICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQgPSBzdGF0dXNFbGVtZW50O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRNb2RhbE9wZW5lZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAnaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLm1vZGFsLm9wZW5lZCc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldE1vZGFsQ2xvc2VkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdodHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMubW9kYWwuY2xvc2VkJztcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIGxldCBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lciA9ICgpID0+IHtcbiAgICAgICAgICAgIGlmICh0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5pc0VtcHR5KCkpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXR1c0VsZW1lbnQuaW5uZXJUZXh0ID0gJ25vdCBlbmFibGVkJztcbiAgICAgICAgICAgICAgICB0aGlzLmFjdGlvbkJhZGdlLm1hcmtOb3RFbmFibGVkKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuc3RhdHVzRWxlbWVudC5pbm5lclRleHQgPSAnZW5hYmxlZCc7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3Rpb25CYWRnZS5tYXJrRW5hYmxlZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmluaXQoKTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbC5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc01vZGFsLmdldENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICBtb2RhbENsb3NlRXZlbnRMaXN0ZW5lcigpO1xuICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChIdHRwQXV0aGVudGljYXRpb25PcHRpb25zLmdldE1vZGFsQ2xvc2VkRXZlbnROYW1lKCkpKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucy5qcyIsImxldCBMaXN0ZWRUZXN0RmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2xpc3RlZC10ZXN0LWZhY3RvcnknKTtcblxuY2xhc3MgTGlzdGVkVGVzdENvbGxlY3Rpb24ge1xuICAgIGNvbnN0cnVjdG9yICgpIHtcbiAgICAgICAgdGhpcy5saXN0ZWRUZXN0cyA9IHt9O1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7TGlzdGVkVGVzdH0gbGlzdGVkVGVzdFxuICAgICAqL1xuICAgIGFkZCAobGlzdGVkVGVzdCkge1xuICAgICAgICB0aGlzLmxpc3RlZFRlc3RzW2xpc3RlZFRlc3QuZ2V0VGVzdElkKCldID0gbGlzdGVkVGVzdDtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtMaXN0ZWRUZXN0fSBsaXN0ZWRUZXN0XG4gICAgICovXG4gICAgcmVtb3ZlIChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIGlmICh0aGlzLmNvbnRhaW5zKGxpc3RlZFRlc3QpKSB7XG4gICAgICAgICAgICBkZWxldGUgdGhpcy5saXN0ZWRUZXN0c1tsaXN0ZWRUZXN0LmdldFRlc3RJZCgpXTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0xpc3RlZFRlc3R9IGxpc3RlZFRlc3RcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGNvbnRhaW5zIChsaXN0ZWRUZXN0KSB7XG4gICAgICAgIHJldHVybiB0aGlzLmNvbnRhaW5zVGVzdElkKGxpc3RlZFRlc3QuZ2V0VGVzdElkKCkpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gdGVzdElkXG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7Ym9vbGVhbn1cbiAgICAgKi9cbiAgICBjb250YWluc1Rlc3RJZCAodGVzdElkKSB7XG4gICAgICAgIHJldHVybiBPYmplY3Qua2V5cyh0aGlzLmxpc3RlZFRlc3RzKS5pbmNsdWRlcyh0ZXN0SWQpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSB0ZXN0SWRcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtMaXN0ZWRUZXN0fG51bGx9XG4gICAgICovXG4gICAgZ2V0ICh0ZXN0SWQpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuY29udGFpbnNUZXN0SWQodGVzdElkKSA/IHRoaXMubGlzdGVkVGVzdHNbdGVzdElkXSA6IG51bGw7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtmdW5jdGlvbn0gY2FsbGJhY2tcbiAgICAgKi9cbiAgICBmb3JFYWNoIChjYWxsYmFjaykge1xuICAgICAgICBPYmplY3Qua2V5cyh0aGlzLmxpc3RlZFRlc3RzKS5mb3JFYWNoKCh0ZXN0SWQpID0+IHtcbiAgICAgICAgICAgIGNhbGxiYWNrKHRoaXMubGlzdGVkVGVzdHNbdGVzdElkXSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05vZGVMaXN0fSBub2RlTGlzdFxuICAgICAqXG4gICAgICogQHJldHVybnMge0xpc3RlZFRlc3RDb2xsZWN0aW9ufVxuICAgICAqL1xuICAgIHN0YXRpYyBjcmVhdGVGcm9tTm9kZUxpc3QgKG5vZGVMaXN0KSB7XG4gICAgICAgIGxldCBjb2xsZWN0aW9uID0gbmV3IExpc3RlZFRlc3RDb2xsZWN0aW9uKCk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKG5vZGVMaXN0LCAobGlzdGVkVGVzdEVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIGNvbGxlY3Rpb24uYWRkKExpc3RlZFRlc3RGYWN0b3J5LmNyZWF0ZUZyb21FbGVtZW50KGxpc3RlZFRlc3RFbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBjb2xsZWN0aW9uO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBMaXN0ZWRUZXN0Q29sbGVjdGlvbjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9tb2RlbC9saXN0ZWQtdGVzdC1jb2xsZWN0aW9uLmpzIiwibGV0IFNvcnRDb250cm9sID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9zb3J0LWNvbnRyb2wnKTtcblxuY2xhc3MgU29ydENvbnRyb2xDb2xsZWN0aW9uIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1NvcnRDb250cm9sW119IGNvbnRyb2xzXG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGNvbnRyb2xzKSB7XG4gICAgICAgIHRoaXMuY29udHJvbHMgPSBjb250cm9scztcbiAgICB9O1xuXG4gICAgc2V0U29ydGVkIChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuY29udHJvbHMuZm9yRWFjaCgoY29udHJvbCkgPT4ge1xuICAgICAgICAgICAgaWYgKGNvbnRyb2wuZWxlbWVudCA9PT0gZWxlbWVudCkge1xuICAgICAgICAgICAgICAgIGNvbnRyb2wuc2V0U29ydGVkKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGNvbnRyb2wuc2V0Tm90U29ydGVkKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU29ydENvbnRyb2xDb2xsZWN0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL21vZGVsL3NvcnQtY29udHJvbC1jb2xsZWN0aW9uLmpzIiwiY2xhc3MgU29ydGFibGVJdGVtTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtTb3J0YWJsZUl0ZW1bXX0gaXRlbXNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoaXRlbXMpIHtcbiAgICAgICAgdGhpcy5pdGVtcyA9IGl0ZW1zO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge3N0cmluZ1tdfSBrZXlzXG4gICAgICogQHJldHVybnMge1NvcnRhYmxlSXRlbVtdfVxuICAgICAqL1xuICAgIHNvcnQgKGtleXMpIHtcbiAgICAgICAgbGV0IGluZGV4ID0gW107XG4gICAgICAgIGxldCBzb3J0ZWRJdGVtcyA9IFtdO1xuXG4gICAgICAgIHRoaXMuaXRlbXMuZm9yRWFjaCgoc29ydGFibGVJdGVtLCBwb3NpdGlvbikgPT4ge1xuICAgICAgICAgICAgbGV0IHZhbHVlcyA9IFtdO1xuXG4gICAgICAgICAgICBrZXlzLmZvckVhY2goKGtleSkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCB2YWx1ZSA9IHNvcnRhYmxlSXRlbS5nZXRTb3J0VmFsdWUoa2V5KTtcbiAgICAgICAgICAgICAgICBpZiAoTnVtYmVyLmlzSW50ZWdlcih2YWx1ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSAoMS92YWx1ZSkudG9TdHJpbmcoKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB2YWx1ZXMucHVzaCh2YWx1ZSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgaW5kZXgucHVzaCh7XG4gICAgICAgICAgICAgICAgcG9zaXRpb246IHBvc2l0aW9uLFxuICAgICAgICAgICAgICAgIHZhbHVlOiB2YWx1ZXMuam9pbignLCcpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgaW5kZXguc29ydCh0aGlzLl9jb21wYXJlRnVuY3Rpb24pO1xuXG4gICAgICAgIGluZGV4LmZvckVhY2goKGluZGV4SXRlbSkgPT4ge1xuICAgICAgICAgICAgc29ydGVkSXRlbXMucHVzaCh0aGlzLml0ZW1zW2luZGV4SXRlbS5wb3NpdGlvbl0pO1xuICAgICAgICB9KTtcblxuICAgICAgICByZXR1cm4gc29ydGVkSXRlbXM7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBhXG4gICAgICogQHBhcmFtIHtPYmplY3R9IGJcbiAgICAgKiBAcmV0dXJucyB7bnVtYmVyfVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NvbXBhcmVGdW5jdGlvbiAoYSwgYikge1xuICAgICAgICBpZiAoYS52YWx1ZSA8IGIudmFsdWUpIHtcbiAgICAgICAgICAgIHJldHVybiAtMTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChhLnZhbHVlID4gYi52YWx1ZSkge1xuICAgICAgICAgICAgcmV0dXJuIDE7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gMDtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNvcnRhYmxlSXRlbUxpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbW9kZWwvc29ydGFibGUtaXRlbS1saXN0LmpzIiwibGV0IHVuYXZhaWxhYmxlVGFza1R5cGVNb2RhbExhdW5jaGVyID0gcmVxdWlyZSgnLi4vdW5hdmFpbGFibGUtdGFzay10eXBlLW1vZGFsLWxhdW5jaGVyJyk7XG5sZXQgVGVzdFN0YXJ0Rm9ybSA9IHJlcXVpcmUoJy4uL2Rhc2hib2FyZC90ZXN0LXN0YXJ0LWZvcm0nKTtcbmxldCBSZWNlbnRUZXN0TGlzdCA9IHJlcXVpcmUoJy4uL2Rhc2hib2FyZC9yZWNlbnQtdGVzdC1saXN0Jyk7XG5sZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeScpO1xubGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMgPSByZXF1aXJlKCcuLi9tb2RlbC9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMnKTtcbmxldCBDb29raWVPcHRpb25zRmFjdG9yeSA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2Nvb2tpZS1vcHRpb25zLWZhY3RvcnknKTtcbmxldCBDb29raWVPcHRpb25zID0gcmVxdWlyZSgnLi4vbW9kZWwvY29va2llLW9wdGlvbnMnKTtcblxuY2xhc3MgRGFzaGJvYXJkIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMudGVzdFN0YXJ0Rm9ybSA9IG5ldyBUZXN0U3RhcnRGb3JtKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZXN0LXN0YXJ0LWZvcm0nKSk7XG4gICAgICAgIHRoaXMucmVjZW50VGVzdExpc3QgPSBuZXcgUmVjZW50VGVzdExpc3QoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRlc3QtbGlzdCcpKTtcbiAgICAgICAgdGhpcy5odHRwQXV0aGVudGljYXRpb25PcHRpb25zID0gSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkuY3JlYXRlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5odHRwLWF1dGhlbnRpY2F0aW9uLXRlc3Qtb3B0aW9uJykpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMgPSBDb29raWVPcHRpb25zRmFjdG9yeS5jcmVhdGUoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmNvb2tpZXMtdGVzdC1vcHRpb24nKSk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJlY2VudC1hY3Rpdml0eS1jb250YWluZXInKS5jbGFzc0xpc3QucmVtb3ZlKCdoaWRkZW4nKTtcblxuICAgICAgICB1bmF2YWlsYWJsZVRhc2tUeXBlTW9kYWxMYXVuY2hlcih0aGlzLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50YXNrLXR5cGUubm90LWF2YWlsYWJsZScpKTtcbiAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmluaXQoKTtcbiAgICAgICAgdGhpcy5yZWNlbnRUZXN0TGlzdC5pbml0KCk7XG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucy5pbml0KCk7XG4gICAgICAgIHRoaXMuY29va2llT3B0aW9ucy5pbml0KCk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxPcGVuZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmRpc2FibGUoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuZ2V0TW9kYWxDbG9zZWRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy50ZXN0U3RhcnRGb3JtLmVuYWJsZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9ucy5nZXRNb2RhbE9wZW5lZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnRlc3RTdGFydEZvcm0uZGlzYWJsZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoQ29va2llT3B0aW9ucy5nZXRNb2RhbENsb3NlZEV2ZW50TmFtZSgpLCAoKSA9PiB7XG4gICAgICAgICAgICB0aGlzLnRlc3RTdGFydEZvcm0uZW5hYmxlKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gRGFzaGJvYXJkO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvZGFzaGJvYXJkLmpzIiwibGV0IE1vZGFsID0gcmVxdWlyZSgnLi4vdGVzdC1oaXN0b3J5L21vZGFsJyk7XG5sZXQgU3VnZ2VzdGlvbnMgPSByZXF1aXJlKCcuLi90ZXN0LWhpc3Rvcnkvc3VnZ2VzdGlvbnMnKTtcbmxldCBMaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2xpc3RlZC10ZXN0LWNvbGxlY3Rpb24nKTtcblxubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiAoZG9jdW1lbnQpIHtcbiAgICBjb25zdCBtb2RhbElkID0gJ2ZpbHRlci1vcHRpb25zLW1vZGFsJztcbiAgICBjb25zdCBmaWx0ZXJPcHRpb25zU2VsZWN0b3IgPSAnLmFjdGlvbi1iYWRnZS1maWx0ZXItb3B0aW9ucyc7XG4gICAgY29uc3QgbW9kYWxFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobW9kYWxJZCk7XG4gICAgbGV0IGZpbHRlck9wdGlvbnNFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihmaWx0ZXJPcHRpb25zU2VsZWN0b3IpO1xuXG4gICAgbGV0IG1vZGFsID0gbmV3IE1vZGFsKG1vZGFsRWxlbWVudCk7XG4gICAgbGV0IHN1Z2dlc3Rpb25zID0gbmV3IFN1Z2dlc3Rpb25zKGRvY3VtZW50LCBtb2RhbEVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXNvdXJjZS11cmwnKSk7XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqL1xuICAgIGxldCBzdWdnZXN0aW9uc0xvYWRlZEV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgbW9kYWwuc2V0U3VnZ2VzdGlvbnMoZXZlbnQuZGV0YWlsKTtcbiAgICAgICAgZmlsdGVyT3B0aW9uc0VsZW1lbnQuY2xhc3NMaXN0LmFkZCgndmlzaWJsZScpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudFxuICAgICAqL1xuICAgIGxldCBmaWx0ZXJDaGFuZ2VkRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICBsZXQgZmlsdGVyID0gZXZlbnQuZGV0YWlsO1xuICAgICAgICBsZXQgc2VhcmNoID0gKGZpbHRlciA9PT0gJycpID8gJycgOiAnP2ZpbHRlcj0nICsgZmlsdGVyO1xuICAgICAgICBsZXQgY3VycmVudFNlYXJjaCA9IHdpbmRvdy5sb2NhdGlvbi5zZWFyY2g7XG5cbiAgICAgICAgaWYgKGN1cnJlbnRTZWFyY2ggPT09ICcnICYmIGZpbHRlciA9PT0gJycpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChzZWFyY2ggIT09IGN1cnJlbnRTZWFyY2gpIHtcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5zZWFyY2ggPSBzZWFyY2g7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihzdWdnZXN0aW9ucy5sb2FkZWRFdmVudE5hbWUsIHN1Z2dlc3Rpb25zTG9hZGVkRXZlbnRMaXN0ZW5lcik7XG4gICAgbW9kYWxFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIobW9kYWwuZmlsdGVyQ2hhbmdlZEV2ZW50TmFtZSwgZmlsdGVyQ2hhbmdlZEV2ZW50TGlzdGVuZXIpO1xuXG4gICAgc3VnZ2VzdGlvbnMucmV0cmlldmUoKTtcblxuICAgIGxldCBsaXN0ZWRUZXN0Q29sbGVjdGlvbiA9IExpc3RlZFRlc3RDb2xsZWN0aW9uLmNyZWF0ZUZyb21Ob2RlTGlzdChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubGlzdGVkLXRlc3QnKSk7XG4gICAgbGlzdGVkVGVzdENvbGxlY3Rpb24uZm9yRWFjaCgobGlzdGVkVGVzdCkgPT4ge1xuICAgICAgICBsaXN0ZWRUZXN0LmVuYWJsZSgpO1xuICAgIH0pO1xufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtaGlzdG9yeS5qcyIsImxldCBTdW1tYXJ5ID0gcmVxdWlyZSgnLi4vdGVzdC1wcm9ncmVzcy9zdW1tYXJ5Jyk7XG5sZXQgVGFza0xpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXByb2dyZXNzL3Rhc2stbGlzdCcpO1xubGV0IFRhc2tMaXN0UGFnaW5hdGlvbiA9IHJlcXVpcmUoJy4uL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LXBhZ2luYXRvcicpO1xubGV0IFRlc3RBbGVydENvbnRhaW5lciA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvdGVzdC1hbGVydC1jb250YWluZXInKTtcbmxldCBIdHRwQ2xpZW50ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvaHR0cC1jbGllbnQnKTtcblxuY2xhc3MgVGVzdFByb2dyZXNzIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSAxMDA7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zdW1tYXJ5ID0gbmV3IFN1bW1hcnkoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXN1bW1hcnknKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RFbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRlc3QtcHJvZ3Jlc3MtdGFza3MnKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdCA9IG5ldyBUYXNrTGlzdCh0aGlzLnRhc2tMaXN0RWxlbWVudCwgdGhpcy5wYWdlTGVuZ3RoKTtcbiAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lciA9IG5ldyBUZXN0QWxlcnRDb250YWluZXIoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmFsZXJ0LWNvbnRhaW5lcicpKTtcbiAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24gPSBudWxsO1xuICAgICAgICB0aGlzLnRhc2tMaXN0SXNJbml0aWFsaXplZCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnN1bW1hcnlEYXRhID0gbnVsbDtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5LmluaXQoKTtcbiAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lci5pbml0KCk7XG4gICAgICAgIHRoaXMuX2FkZEV2ZW50TGlzdGVuZXJzKCk7XG5cbiAgICAgICAgdGhpcy5fcmVmcmVzaFN1bW1hcnkoKTtcbiAgICB9O1xuXG4gICAgX2FkZEV2ZW50TGlzdGVuZXJzICgpIHtcbiAgICAgICAgdGhpcy5zdW1tYXJ5LmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihTdW1tYXJ5LmdldFJlbmRlckFtbWVuZG1lbnRFdmVudE5hbWUoKSwgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5hbGVydENvbnRhaW5lci5yZW5kZXJVcmxMaW1pdE5vdGlmaWNhdGlvbigpO1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMudGFza0xpc3RFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3QuZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUoKSwgdGhpcy5fdGFza0xpc3RJbml0aWFsaXplZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIF9hZGRQYWdpbmF0aW9uRXZlbnRMaXN0ZW5lcnMgKCkge1xuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFBhZ2VFdmVudE5hbWUoKSwgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gZXZlbnQuZGV0YWlsO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0UHJldmlvdXNQYWdlRXZlbnROYW1lKCksIChldmVudCkgPT4ge1xuICAgICAgICAgICAgbGV0IHBhZ2VJbmRleCA9IE1hdGgubWF4KHRoaXMudGFza0xpc3QuY3VycmVudFBhZ2VJbmRleCAtIDEsIDApO1xuXG4gICAgICAgICAgICB0aGlzLnRhc2tMaXN0LnNldEN1cnJlbnRQYWdlSW5kZXgocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLnNlbGVjdFBhZ2UocGFnZUluZGV4KTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0TmV4dFBhZ2VFdmVudE5hbWUoKSwgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gTWF0aC5taW4odGhpcy50YXNrTGlzdC5jdXJyZW50UGFnZUluZGV4ICsgMSwgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24ucGFnZUNvdW50IC0gMSk7XG5cbiAgICAgICAgICAgIHRoaXMudGFza0xpc3Quc2V0Q3VycmVudFBhZ2VJbmRleChwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uc2VsZWN0UGFnZShwYWdlSW5kZXgpO1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdC5yZW5kZXIocGFnZUluZGV4KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGlmIChldmVudC5kZXRhaWwucmVxdWVzdElkID09PSAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnVwZGF0ZScpIHtcbiAgICAgICAgICAgIGlmIChldmVudC5kZXRhaWwucmVxdWVzdC5yZXNwb25zZVVSTC5pbmRleE9mKHdpbmRvdy5sb2NhdGlvbi50b1N0cmluZygpKSAhPT0gMCkge1xuICAgICAgICAgICAgICAgIHRoaXMuc3VtbWFyeS5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudCgxMDApO1xuICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gZXZlbnQuZGV0YWlsLnJlcXVlc3QucmVzcG9uc2VVUkw7XG5cbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuc3VtbWFyeURhdGEgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG5cbiAgICAgICAgICAgIGxldCBzdGF0ZSA9IHRoaXMuc3VtbWFyeURhdGEudGVzdC5zdGF0ZTtcbiAgICAgICAgICAgIGxldCB0YXNrQ291bnQgPSB0aGlzLnN1bW1hcnlEYXRhLnJlbW90ZV90ZXN0LnRhc2tfY291bnQ7XG4gICAgICAgICAgICBsZXQgaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyA9IFsncXVldWVkJywgJ2luLXByb2dyZXNzJ10uaW5kZXhPZihzdGF0ZSkgIT09IC0xO1xuXG4gICAgICAgICAgICB0aGlzLl9zZXRCb2R5Sm9iQ2xhc3ModGhpcy5kb2N1bWVudC5ib2R5LmNsYXNzTGlzdCwgc3RhdGUpO1xuICAgICAgICAgICAgdGhpcy5zdW1tYXJ5LnJlbmRlcih0aGlzLnN1bW1hcnlEYXRhKTtcblxuICAgICAgICAgICAgaWYgKHRhc2tDb3VudCA+IDAgJiYgaXNTdGF0ZVF1ZXVlZE9ySW5Qcm9ncmVzcyAmJiAhdGhpcy50YXNrTGlzdElzSW5pdGlhbGl6ZWQgJiYgIXRoaXMudGFza0xpc3QuaXNJbml0aWFsaXppbmcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnRhc2tMaXN0LmluaXQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIHdpbmRvdy5zZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuX3JlZnJlc2hTdW1tYXJ5KCk7XG4gICAgICAgIH0sIDMwMDApO1xuICAgIH07XG5cbiAgICBfdGFza0xpc3RJbml0aWFsaXplZEV2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICB0aGlzLnRhc2tMaXN0SXNJbml0aWFsaXplZCA9IHRydWU7XG4gICAgICAgIHRoaXMudGFza0xpc3QucmVuZGVyKDApO1xuICAgICAgICB0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbiA9IG5ldyBUYXNrTGlzdFBhZ2luYXRpb24odGhpcy5wYWdlTGVuZ3RoLCB0aGlzLnN1bW1hcnlEYXRhLnJlbW90ZV90ZXN0LnRhc2tfY291bnQpO1xuXG4gICAgICAgIGlmICh0aGlzLnRhc2tMaXN0UGFnaW5hdGlvbi5pc1JlcXVpcmVkKCkgJiYgIXRoaXMudGFza0xpc3RQYWdpbmF0aW9uLmlzUmVuZGVyZWQoKSkge1xuICAgICAgICAgICAgdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uaW5pdCh0aGlzLl9jcmVhdGVQYWdpbmF0aW9uRWxlbWVudCgpKTtcbiAgICAgICAgICAgIHRoaXMudGFza0xpc3Quc2V0UGFnaW5hdGlvbkVsZW1lbnQodGhpcy50YXNrTGlzdFBhZ2luYXRpb24uZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLl9hZGRQYWdpbmF0aW9uRXZlbnRMaXN0ZW5lcnMoKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7RWxlbWVudH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVQYWdpbmF0aW9uRWxlbWVudCAoKSB7XG4gICAgICAgIGxldCBjb250YWluZXIgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gdGhpcy50YXNrTGlzdFBhZ2luYXRpb24uY3JlYXRlTWFya3VwKCk7XG5cbiAgICAgICAgcmV0dXJuIGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKFRhc2tMaXN0UGFnaW5hdGlvbi5nZXRTZWxlY3RvcigpKTtcbiAgICB9XG5cbiAgICBfcmVmcmVzaFN1bW1hcnkgKCkge1xuICAgICAgICBsZXQgc3VtbWFyeVVybCA9IHRoaXMuZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3VtbWFyeS11cmwnKTtcbiAgICAgICAgbGV0IG5vdyA9IG5ldyBEYXRlKCk7XG5cbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHN1bW1hcnlVcmwgKyAnP3RpbWVzdGFtcD0nICsgbm93LmdldFRpbWUoKSwgdGhpcy5kb2N1bWVudC5ib2R5LCAndGVzdC1wcm9ncmVzcy5zdW1tYXJ5LnVwZGF0ZScpO1xuICAgIH07XG4gICAgLyoqXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0RPTVRva2VuTGlzdH0gYm9keUNsYXNzTGlzdFxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSB0ZXN0U3RhdGVcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9zZXRCb2R5Sm9iQ2xhc3MgKGJvZHlDbGFzc0xpc3QsIHRlc3RTdGF0ZSkge1xuICAgICAgICBsZXQgam9iQ2xhc3NQcmVmaXggPSAnam9iLSc7XG4gICAgICAgIGJvZHlDbGFzc0xpc3QuZm9yRWFjaCgoY2xhc3NOYW1lLCBpbmRleCwgY2xhc3NMaXN0KSA9PiB7XG4gICAgICAgICAgICBpZiAoY2xhc3NOYW1lLmluZGV4T2Yoam9iQ2xhc3NQcmVmaXgpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgY2xhc3NMaXN0LnJlbW92ZShjbGFzc05hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBib2R5Q2xhc3NMaXN0LmFkZCgnam9iLScgKyB0ZXN0U3RhdGUpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFByb2dyZXNzO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1wcm9ncmVzcy5qcyIsImxldCBCeVBhZ2VMaXN0ID0gcmVxdWlyZSgnLi4vdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZS9ieS1wYWdlLWxpc3QnKTtcbmxldCBCeUVycm9yTGlzdCA9IHJlcXVpcmUoJy4uL3Rlc3QtcmVzdWx0cy1ieS10YXNrLXR5cGUvYnktZXJyb3ItbGlzdCcpO1xuXG5jbGFzcyBUZXN0UmVzdWx0c0J5VGFza1R5cGUge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50KSB7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcblxuICAgICAgICBsZXQgYnlFcnJvckNvbnRhaW5lckVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuYnktZXJyb3ItY29udGFpbmVyJyk7XG5cbiAgICAgICAgdGhpcy5ieVBhZ2VMaXN0ID0gYnlFcnJvckNvbnRhaW5lckVsZW1lbnQgPyBudWxsIDogbmV3IEJ5UGFnZUxpc3QoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmJ5LXBhZ2UtY29udGFpbmVyJykpO1xuICAgICAgICB0aGlzLmJ5RXJyb3JMaXN0ID0gYnlFcnJvckNvbnRhaW5lckVsZW1lbnQgPyBuZXcgQnlFcnJvckxpc3QoYnlFcnJvckNvbnRhaW5lckVsZW1lbnQpIDogbnVsbDtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgaWYgKHRoaXMuYnlQYWdlTGlzdCkge1xuICAgICAgICAgICAgdGhpcy5ieVBhZ2VMaXN0LmluaXQoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLmJ5RXJyb3JMaXN0KSB7XG4gICAgICAgICAgICB0aGlzLmJ5RXJyb3JMaXN0LmluaXQoKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFJlc3VsdHNCeVRhc2tUeXBlO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BhZ2UvdGVzdC1yZXN1bHRzLWJ5LXRhc2stdHlwZS5qcyIsImxldCBQcm9ncmVzc0JhciA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvcHJvZ3Jlc3MtYmFyJyk7XG5sZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5cbmNsYXNzIFRlc3RSZXN1bHRzUHJlcGFyaW5nIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMudW5yZXRyaWV2ZWRSZW1vdGVUYXNrSWRzVXJsID0gZG9jdW1lbnQuYm9keS5nZXRBdHRyaWJ1dGUoJ2RhdGEtdW5yZXRyaWV2ZWQtcmVtb3RlLXRhc2staWRzLXVybCcpO1xuICAgICAgICB0aGlzLnJlc3VsdHNSZXRyaWV2ZVVybCA9IGRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXJlc3VsdHMtcmV0cmlldmUtdXJsJyk7XG4gICAgICAgIHRoaXMucmV0cmlldmFsU3RhdHNVcmwgPSBkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS1wcmVwYXJpbmctc3RhdHMtdXJsJyk7XG4gICAgICAgIHRoaXMucmVzdWx0c1VybCA9IGRvY3VtZW50LmJvZHkuZ2V0QXR0cmlidXRlKCdkYXRhLXJlc3VsdHMtdXJsJyk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBuZXcgUHJvZ3Jlc3NCYXIoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnByb2dyZXNzLWJhcicpKTtcbiAgICAgICAgdGhpcy5jb21wbGV0aW9uUGVyY2VudFZhbHVlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmNvbXBsZXRpb24tcGVyY2VudC12YWx1ZScpO1xuICAgICAgICB0aGlzLmxvY2FsVGFza0NvdW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmxvY2FsLXRhc2stY291bnQnKTtcbiAgICAgICAgdGhpcy5yZW1vdGVUYXNrQ291bnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcucmVtb3RlLXRhc2stY291bnQnKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5kb2N1bWVudC5ib2R5LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLl9jaGVja0NvbXBsZXRpb25TdGF0dXMoKTtcbiAgICB9O1xuXG4gICAgX2NoZWNrQ29tcGxldGlvblN0YXR1cyAoKSB7XG4gICAgICAgIGlmIChwYXJzZUludChkb2N1bWVudC5ib2R5LmdldEF0dHJpYnV0ZSgnZGF0YS1yZW1haW5pbmctdGFza3MtdG8tcmV0cmlldmUtY291bnQnKSwgMTApID09PSAwKSB7XG4gICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IHRoaXMucmVzdWx0c1VybDtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlTmV4dFJlbW90ZVRhc2tJZENvbGxlY3Rpb24oKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCByZXF1ZXN0SWQgPSBldmVudC5kZXRhaWwucmVxdWVzdElkO1xuICAgICAgICBsZXQgcmVzcG9uc2UgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlTmV4dFJlbW90ZVRhc2tJZENvbGxlY3Rpb24nKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVJlbW90ZVRhc2tDb2xsZWN0aW9uKHJlc3BvbnNlKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVJlbW90ZVRhc2tDb2xsZWN0aW9uJykge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVSZXRyaWV2YWxTdGF0cygpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlUmV0cmlldmFsU3RhdHMnKSB7XG4gICAgICAgICAgICBsZXQgY29tcGxldGlvblBlcmNlbnQgPSByZXNwb25zZS5jb21wbGV0aW9uX3BlcmNlbnQ7XG5cbiAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQuYm9keS5zZXRBdHRyaWJ1dGUoJ2RhdGEtcmVtYWluaW5nLXRhc2tzLXRvLXJldHJpZXZlLWNvdW50JywgcmVzcG9uc2UucmVtYWluaW5nX3Rhc2tzX3RvX3JldHJpZXZlX2NvdW50KTtcbiAgICAgICAgICAgIHRoaXMuY29tcGxldGlvblBlcmNlbnRWYWx1ZS5pbm5lclRleHQgPSBjb21wbGV0aW9uUGVyY2VudDtcbiAgICAgICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0Q29tcGxldGlvblBlcmNlbnQoY29tcGxldGlvblBlcmNlbnQpO1xuICAgICAgICAgICAgdGhpcy5sb2NhbFRhc2tDb3VudC5pbm5lclRleHQgPSByZXNwb25zZS5sb2NhbF90YXNrX2NvdW50O1xuICAgICAgICAgICAgdGhpcy5yZW1vdGVUYXNrQ291bnQuaW5uZXJUZXh0ID0gcmVzcG9uc2UucmVtb3RlX3Rhc2tfY291bnQ7XG5cbiAgICAgICAgICAgIHRoaXMuX2NoZWNrQ29tcGxldGlvblN0YXR1cygpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHRoaXMudW5yZXRyaWV2ZWRSZW1vdGVUYXNrSWRzVXJsLCB0aGlzLmRvY3VtZW50LmJvZHksICdyZXRyaWV2ZU5leHRSZW1vdGVUYXNrSWRDb2xsZWN0aW9uJyk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZVJlbW90ZVRhc2tDb2xsZWN0aW9uIChyZW1vdGVUYXNrSWRzKSB7XG4gICAgICAgIEh0dHBDbGllbnQucG9zdCh0aGlzLnJlc3VsdHNSZXRyaWV2ZVVybCwgdGhpcy5kb2N1bWVudC5ib2R5LCAncmV0cmlldmVSZW1vdGVUYXNrQ29sbGVjdGlvbicsICdyZW1vdGVUYXNrSWRzPScgKyByZW1vdGVUYXNrSWRzLmpvaW4oJywnKSk7XG4gICAgfTtcblxuICAgIF9yZXRyaWV2ZVJldHJpZXZhbFN0YXRzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHRoaXMucmV0cmlldmFsU3RhdHNVcmwsIHRoaXMuZG9jdW1lbnQuYm9keSwgJ3JldHJpZXZlUmV0cmlldmFsU3RhdHMnKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGVzdFJlc3VsdHNQcmVwYXJpbmc7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFnZS90ZXN0LXJlc3VsdHMtcHJlcGFyaW5nLmpzIiwibGV0IHVuYXZhaWxhYmxlVGFza1R5cGVNb2RhbExhdW5jaGVyID0gcmVxdWlyZSgnLi4vdW5hdmFpbGFibGUtdGFzay10eXBlLW1vZGFsLWxhdW5jaGVyJyk7XG5sZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9odHRwLWF1dGhlbnRpY2F0aW9uLW9wdGlvbnMtZmFjdG9yeScpO1xubGV0IENvb2tpZU9wdGlvbnNGYWN0b3J5ID0gcmVxdWlyZSgnLi4vc2VydmljZXMvY29va2llLW9wdGlvbnMtZmFjdG9yeScpO1xubGV0IFRlc3RMb2NrVW5sb2NrID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC90ZXN0LWxvY2stdW5sb2NrJyk7XG5sZXQgRm9ybUJ1dHRvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcbmxldCBCYWRnZUNvbGxlY3Rpb24gPSByZXF1aXJlKCcuLi9tb2RlbC9iYWRnZS1jb2xsZWN0aW9uJyk7XG5cbmNsYXNzIFRlc3RSZXN1bHRzIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChkb2N1bWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuaHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyA9IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNGYWN0b3J5LmNyZWF0ZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuaHR0cC1hdXRoZW50aWNhdGlvbi10ZXN0LW9wdGlvbicpKTtcbiAgICAgICAgdGhpcy5jb29raWVPcHRpb25zID0gQ29va2llT3B0aW9uc0ZhY3RvcnkuY3JlYXRlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jb29raWVzLXRlc3Qtb3B0aW9uJykpO1xuICAgICAgICB0aGlzLnRlc3RMb2NrVW5sb2NrID0gbmV3IFRlc3RMb2NrVW5sb2NrKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5idG4tbG9jay11bmxvY2snKSk7XG4gICAgICAgIHRoaXMucmV0ZXN0Rm9ybSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5yZXRlc3QtZm9ybScpO1xuICAgICAgICB0aGlzLnJldGVzdEJ1dHRvbiA9IG5ldyBGb3JtQnV0dG9uKHRoaXMucmV0ZXN0Rm9ybS5xdWVyeVNlbGVjdG9yKCdidXR0b25bdHlwZT1zdWJtaXRdJykpO1xuICAgICAgICB0aGlzLnRhc2tUeXBlU3VtbWFyeUJhZGdlQ29sbGVjdGlvbiA9IG5ldyBCYWRnZUNvbGxlY3Rpb24oZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnRhc2stdHlwZS1zdW1tYXJ5IC5iYWRnZScpKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZU1vZGFsTGF1bmNoZXIodGhpcy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcudGFzay10eXBlLW9wdGlvbi5ub3QtYXZhaWxhYmxlJykpO1xuICAgICAgICB0aGlzLmh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMuaW5pdCgpO1xuICAgICAgICB0aGlzLmNvb2tpZU9wdGlvbnMuaW5pdCgpO1xuICAgICAgICB0aGlzLnRlc3RMb2NrVW5sb2NrLmluaXQoKTtcbiAgICAgICAgdGhpcy50YXNrVHlwZVN1bW1hcnlCYWRnZUNvbGxlY3Rpb24uYXBwbHlVbmlmb3JtV2lkdGgoKTtcblxuICAgICAgICB0aGlzLnJldGVzdEZvcm0uYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5yZXRlc3RCdXR0b24uZGVFbXBoYXNpemUoKTtcbiAgICAgICAgICAgIHRoaXMucmV0ZXN0QnV0dG9uLm1hcmtBc0J1c3koKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0UmVzdWx0cztcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3Rlc3QtcmVzdWx0cy5qcyIsImxldCBGb3JtID0gcmVxdWlyZSgnLi4vdXNlci1hY2NvdW50LWNhcmQvZm9ybScpO1xubGV0IEZvcm1WYWxpZGF0b3IgPSByZXF1aXJlKCcuLi91c2VyLWFjY291bnQtY2FyZC9mb3JtLXZhbGlkYXRvcicpO1xubGV0IFN0cmlwZUhhbmRsZXIgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9zdHJpcGUtaGFuZGxlcicpO1xuXG5jbGFzcyBVc2VyQWNjb3VudENhcmQge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGRvY3VtZW50KSB7XG4gICAgICAgIC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBuby11bmRlZlxuICAgICAgICBsZXQgc3RyaXBlSnMgPSBTdHJpcGU7XG4gICAgICAgIGxldCBmb3JtVmFsaWRhdG9yID0gbmV3IEZvcm1WYWxpZGF0b3Ioc3RyaXBlSnMpO1xuICAgICAgICB0aGlzLnN0cmlwZUhhbmRsZXIgPSBuZXcgU3RyaXBlSGFuZGxlcihzdHJpcGVKcyk7XG5cbiAgICAgICAgdGhpcy5mb3JtID0gbmV3IEZvcm0oZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BheW1lbnQtZm9ybScpLCBmb3JtVmFsaWRhdG9yKTtcbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuZm9ybS5pbml0KCk7XG4gICAgICAgIHRoaXMuc3RyaXBlSGFuZGxlci5zZXRTdHJpcGVQdWJsaXNoYWJsZUtleSh0aGlzLmZvcm0uZ2V0U3RyaXBlUHVibGlzaGFibGVLZXkoKSk7XG5cbiAgICAgICAgbGV0IHVwZGF0ZUNhcmQgPSB0aGlzLmZvcm0uZ2V0VXBkYXRlQ2FyZEV2ZW50TmFtZSgpO1xuICAgICAgICBsZXQgY3JlYXRlQ2FyZFRva2VuU3VjY2VzcyA9IHRoaXMuc3RyaXBlSGFuZGxlci5nZXRDcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnROYW1lKCk7XG4gICAgICAgIGxldCBjcmVhdGVDYXJkVG9rZW5GYWlsdXJlID0gdGhpcy5zdHJpcGVIYW5kbGVyLmdldENyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudE5hbWUoKTtcblxuICAgICAgICB0aGlzLmZvcm0uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKHVwZGF0ZUNhcmQsIHRoaXMuX3VwZGF0ZUNhcmRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmZvcm0uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGNyZWF0ZUNhcmRUb2tlblN1Y2Nlc3MsIHRoaXMuX2NyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmZvcm0uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGNyZWF0ZUNhcmRUb2tlbkZhaWx1cmUsIHRoaXMuX2NyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBfdXBkYXRlQ2FyZEV2ZW50TGlzdGVuZXIgKHVwZGF0ZUNhcmRFdmVudCkge1xuICAgICAgICB0aGlzLnN0cmlwZUhhbmRsZXIuY3JlYXRlQ2FyZFRva2VuKHVwZGF0ZUNhcmRFdmVudC5kZXRhaWwsIHRoaXMuZm9ybS5lbGVtZW50KTtcbiAgICB9O1xuXG4gICAgX2NyZWF0ZUNhcmRUb2tlblN1Y2Nlc3NFdmVudExpc3RlbmVyIChzdHJpcGVDcmVhdGVDYXJkVG9rZW5FdmVudCkge1xuICAgICAgICBsZXQgcmVxdWVzdFVybCA9IHdpbmRvdy5sb2NhdGlvbi5wYXRobmFtZSArIHN0cmlwZUNyZWF0ZUNhcmRUb2tlbkV2ZW50LmRldGFpbC5pZCArICcvYXNzb2NpYXRlLyc7XG4gICAgICAgIGxldCByZXF1ZXN0ID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG5cbiAgICAgICAgcmVxdWVzdC5vcGVuKCdQT1NUJywgcmVxdWVzdFVybCk7XG4gICAgICAgIHJlcXVlc3QucmVzcG9uc2VUeXBlID0gJ2pzb24nO1xuICAgICAgICByZXF1ZXN0LnNldFJlcXVlc3RIZWFkZXIoJ0FjY2VwdCcsICdhcHBsaWNhdGlvbi9qc29uJyk7XG5cbiAgICAgICAgcmVxdWVzdC5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgZGF0YSA9IHJlcXVlc3QucmVzcG9uc2U7XG5cbiAgICAgICAgICAgIGlmIChkYXRhLmhhc093blByb3BlcnR5KCd0aGlzX3VybCcpKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mb3JtLnN1Ym1pdEJ1dHRvbi5tYXJrQXNBdmFpbGFibGUoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmZvcm0uc3VibWl0QnV0dG9uLm1hcmtTdWNjZWVkZWQoKTtcblxuICAgICAgICAgICAgICAgIHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uID0gZGF0YS50aGlzX3VybDtcbiAgICAgICAgICAgICAgICB9LCA1MDApO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZvcm0uZW5hYmxlKCk7XG5cbiAgICAgICAgICAgICAgICBpZiAoZGF0YS5oYXNPd25Qcm9wZXJ0eSgndXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtJykgJiYgZGF0YVsndXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtJ10gIT09ICcnKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5oYW5kbGVSZXNwb25zZUVycm9yKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdwYXJhbSc6IGRhdGEudXNlcl9hY2NvdW50X2NhcmRfZXhjZXB0aW9uX3BhcmFtLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ21lc3NhZ2UnOiBkYXRhLnVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9tZXNzYWdlXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5oYW5kbGVSZXNwb25zZUVycm9yKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICdwYXJhbSc6ICdudW1iZXInLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ21lc3NhZ2UnOiBkYXRhLnVzZXJfYWNjb3VudF9jYXJkX2V4Y2VwdGlvbl9tZXNzYWdlXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmVxdWVzdC5zZW5kKCk7XG4gICAgfTtcblxuICAgIF9jcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnRMaXN0ZW5lciAoc3RyaXBlQ3JlYXRlQ2FyZFRva2VuRXZlbnQpIHtcbiAgICAgICAgbGV0IHJlc3BvbnNlRXJyb3IgPSB0aGlzLmZvcm0uY3JlYXRlUmVzcG9uc2VFcnJvcihzdHJpcGVDcmVhdGVDYXJkVG9rZW5FdmVudC5kZXRhaWwuZXJyb3IucGFyYW0sICdpbnZhbGlkJyk7XG5cbiAgICAgICAgdGhpcy5mb3JtLmVuYWJsZSgpO1xuICAgICAgICB0aGlzLmZvcm0uaGFuZGxlUmVzcG9uc2VFcnJvcihyZXNwb25zZUVycm9yKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFVzZXJBY2NvdW50Q2FyZDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC1jYXJkLmpzIiwibGV0IFNjcm9sbFNweSA9IHJlcXVpcmUoJy4uL3VzZXItYWNjb3VudC9zY3JvbGxzcHknKTtcbmxldCBOYXZCYXJMaXN0ID0gcmVxdWlyZSgnLi4vdXNlci1hY2NvdW50L25hdmJhci1saXN0Jyk7XG5jb25zdCBTY3JvbGxUbyA9IHJlcXVpcmUoJy4uL3Njcm9sbC10bycpO1xuY29uc3QgU3RpY2t5RmlsbCA9IHJlcXVpcmUoJ3N0aWNreWZpbGxqcycpO1xuXG5jbGFzcyBVc2VyQWNjb3VudCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtXaW5kb3d9IHdpbmRvd1xuICAgICAqIEBwYXJhbSB7RG9jdW1lbnR9IGRvY3VtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKHdpbmRvdywgZG9jdW1lbnQpIHtcbiAgICAgICAgdGhpcy53aW5kb3cgPSB3aW5kb3c7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQgPSBkb2N1bWVudDtcbiAgICAgICAgdGhpcy5zY3JvbGxPZmZzZXQgPSA2MDtcbiAgICAgICAgY29uc3Qgc2Nyb2xsU3B5T2Zmc2V0ID0gMTAwO1xuICAgICAgICB0aGlzLnNpZGVOYXZFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NpZGVuYXYnKTtcblxuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuZXcgTmF2QmFyTGlzdCh0aGlzLnNpZGVOYXZFbGVtZW50LCB0aGlzLnNjcm9sbE9mZnNldCk7XG4gICAgICAgIHRoaXMuc2Nyb2xsc3B5ID0gbmV3IFNjcm9sbFNweSh0aGlzLm5hdkJhckxpc3QsIHNjcm9sbFNweU9mZnNldCk7XG4gICAgfTtcblxuICAgIF9hcHBseUluaXRpYWxTY3JvbGwgKCkge1xuICAgICAgICBsZXQgdGFyZ2V0SWQgPSB0aGlzLndpbmRvdy5sb2NhdGlvbi5oYXNoLnRyaW0oKS5yZXBsYWNlKCcjJywgJycpO1xuXG4gICAgICAgIGlmICh0YXJnZXRJZCkge1xuICAgICAgICAgICAgbGV0IHRhcmdldCA9IHRoaXMuZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGFyZ2V0SWQpO1xuICAgICAgICAgICAgbGV0IHJlbGF0ZWRBbmNob3IgPSB0aGlzLnNpZGVOYXZFbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbaHJlZj1cXFxcIycgKyB0YXJnZXRJZCArICddJyk7XG5cbiAgICAgICAgICAgIGlmICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBpZiAocmVsYXRlZEFuY2hvci5jbGFzc0xpc3QuY29udGFpbnMoJ2pzLWZpcnN0JykpIHtcbiAgICAgICAgICAgICAgICAgICAgU2Nyb2xsVG8uZ29Ubyh0YXJnZXQsIDApO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIFNjcm9sbFRvLnNjcm9sbFRvKHRhcmdldCwgdGhpcy5zY3JvbGxPZmZzZXQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfYXBwbHlQb3NpdGlvblN0aWNreVBvbHlmaWxsICgpIHtcbiAgICAgICAgY29uc3Qgc2VsZWN0b3IgPSAnLmNzc3Bvc2l0aW9uc3RpY2t5JztcbiAgICAgICAgY29uc3Qgc3RpY2t5TmF2SnNDbGFzcyA9ICdqcy1zdGlja3ktbmF2JztcbiAgICAgICAgY29uc3Qgc3RpY2t5TmF2SnNTZWxlY3RvciA9ICcuJyArIHN0aWNreU5hdkpzQ2xhc3M7XG5cbiAgICAgICAgbGV0IHN0aWNreU5hdiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc3RpY2t5TmF2SnNTZWxlY3Rvcik7XG5cbiAgICAgICAgaWYgKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpKSB7XG4gICAgICAgICAgICBzdGlja3lOYXYuY2xhc3NMaXN0LnJlbW92ZShzdGlja3lOYXZKc0NsYXNzKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIFN0aWNreUZpbGwuYWRkT25lKHN0aWNreU5hdik7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuc2lkZU5hdkVsZW1lbnQucXVlcnlTZWxlY3RvcignYScpLmNsYXNzTGlzdC5hZGQoJ2pzLWZpcnN0Jyk7XG4gICAgICAgIHRoaXMuc2Nyb2xsc3B5LnNweSgpO1xuICAgICAgICB0aGlzLl9hcHBseVBvc2l0aW9uU3RpY2t5UG9seWZpbGwoKTtcbiAgICAgICAgdGhpcy5fYXBwbHlJbml0aWFsU2Nyb2xsKCk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBVc2VyQWNjb3VudDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYWdlL3VzZXItYWNjb3VudC5qcyIsIi8vIFBvbHlmaWxsIGZvciBicm93c2VycyBub3Qgc3VwcG9ydGluZyBuZXcgQ3VzdG9tRXZlbnQoKVxuLy8gTGlnaHRseSBtb2RpZmllZCBmcm9tIHBvbHlmaWxsIHByb3ZpZGVkIGF0IGh0dHBzOi8vZGV2ZWxvcGVyLm1vemlsbGEub3JnL2VuLVVTL2RvY3MvV2ViL0FQSS9DdXN0b21FdmVudC9DdXN0b21FdmVudCNQb2x5ZmlsbFxuKGZ1bmN0aW9uICgpIHtcbiAgICBpZiAodHlwZW9mIHdpbmRvdy5DdXN0b21FdmVudCA9PT0gJ2Z1bmN0aW9uJykgcmV0dXJuIGZhbHNlO1xuXG4gICAgZnVuY3Rpb24gQ3VzdG9tRXZlbnQgKGV2ZW50LCBwYXJhbXMpIHtcbiAgICAgICAgcGFyYW1zID0gcGFyYW1zIHx8IHsgYnViYmxlczogZmFsc2UsIGNhbmNlbGFibGU6IGZhbHNlLCBkZXRhaWw6IHVuZGVmaW5lZCB9O1xuICAgICAgICBsZXQgY3VzdG9tRXZlbnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudCgnQ3VzdG9tRXZlbnQnKTtcbiAgICAgICAgY3VzdG9tRXZlbnQuaW5pdEN1c3RvbUV2ZW50KGV2ZW50LCBwYXJhbXMuYnViYmxlcywgcGFyYW1zLmNhbmNlbGFibGUsIHBhcmFtcy5kZXRhaWwpO1xuXG4gICAgICAgIHJldHVybiBjdXN0b21FdmVudDtcbiAgICB9XG5cbiAgICBDdXN0b21FdmVudC5wcm90b3R5cGUgPSB3aW5kb3cuRXZlbnQucHJvdG90eXBlO1xuXG4gICAgd2luZG93LkN1c3RvbUV2ZW50ID0gQ3VzdG9tRXZlbnQ7XG59KSgpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3BvbHlmaWxsL2N1c3RvbS1ldmVudC5qcyIsIi8vIFBvbHlmaWxsIGZvciBicm93c2VycyBub3Qgc3VwcG9ydGluZyBPYmplY3QuZW50cmllcygpXG4vLyBMaWdodGx5IG1vZGlmaWVkIGZyb20gcG9seWZpbGwgcHJvdmlkZWQgYXQgaHR0cHM6Ly9kZXZlbG9wZXIubW96aWxsYS5vcmcvZW4tVVMvZG9jcy9XZWIvSmF2YVNjcmlwdC9SZWZlcmVuY2UvR2xvYmFsX09iamVjdHMvT2JqZWN0L2VudHJpZXMjUG9seWZpbGxcbmlmICghT2JqZWN0LmVudHJpZXMpIHtcbiAgICBPYmplY3QuZW50cmllcyA9IGZ1bmN0aW9uIChvYmopIHtcbiAgICAgICAgbGV0IG93blByb3BzID0gT2JqZWN0LmtleXMob2JqKTtcbiAgICAgICAgbGV0IGkgPSBvd25Qcm9wcy5sZW5ndGg7XG4gICAgICAgIGxldCByZXNBcnJheSA9IG5ldyBBcnJheShpKTtcblxuICAgICAgICB3aGlsZSAoaS0tKSB7XG4gICAgICAgICAgICByZXNBcnJheVtpXSA9IFtvd25Qcm9wc1tpXSwgb2JqW293blByb3BzW2ldXV07XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gcmVzQXJyYXk7XG4gICAgfTtcbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wb2x5ZmlsbC9vYmplY3QtZW50cmllcy5qcyIsImNvbnN0IFNtb290aFNjcm9sbCA9IHJlcXVpcmUoJ3Ntb290aC1zY3JvbGwnKTtcblxuY2xhc3MgU2Nyb2xsVG8ge1xuICAgIHN0YXRpYyBzY3JvbGxUbyAodGFyZ2V0LCBvZmZzZXQpIHtcbiAgICAgICAgY29uc3Qgc2Nyb2xsID0gbmV3IFNtb290aFNjcm9sbCgpO1xuXG4gICAgICAgIHNjcm9sbC5hbmltYXRlU2Nyb2xsKHRhcmdldC5vZmZzZXRUb3AgKyBvZmZzZXQpO1xuICAgICAgICBTY3JvbGxUby5fdXBkYXRlSGlzdG9yeSh0YXJnZXQpO1xuICAgIH1cblxuICAgIHN0YXRpYyBnb1RvICh0YXJnZXQsIG9mZnNldCkge1xuICAgICAgICBjb25zdCBzY3JvbGwgPSBuZXcgU21vb3RoU2Nyb2xsKCk7XG5cbiAgICAgICAgc2Nyb2xsLmFuaW1hdGVTY3JvbGwob2Zmc2V0KTtcbiAgICAgICAgU2Nyb2xsVG8uX3VwZGF0ZUhpc3RvcnkodGFyZ2V0KTtcbiAgICB9XG5cbiAgICBzdGF0aWMgX3VwZGF0ZUhpc3RvcnkgKHRhcmdldCkge1xuICAgICAgICBpZiAod2luZG93Lmhpc3RvcnkucHVzaFN0YXRlKSB7XG4gICAgICAgICAgICB3aW5kb3cuaGlzdG9yeS5wdXNoU3RhdGUobnVsbCwgbnVsbCwgJyMnICsgdGFyZ2V0LmdldEF0dHJpYnV0ZSgnaWQnKSk7XG4gICAgICAgIH1cbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFNjcm9sbFRvO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Njcm9sbC10by5qcyIsImxldCBBbGVydCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvYWxlcnQnKTtcblxuY2xhc3MgQWxlcnRGYWN0b3J5IHtcbiAgICBzdGF0aWMgY3JlYXRlRnJvbUNvbnRlbnQgKGRvY3VtZW50LCBlcnJvckNvbnRlbnQsIHJlbGF0ZWRGaWVsZElkKSB7XG4gICAgICAgIGxldCBlbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnYWxlcnQnLCAnYWxlcnQtZGFuZ2VyJywgJ2ZhZGUnLCAnaW4nKTtcbiAgICAgICAgZWxlbWVudC5zZXRBdHRyaWJ1dGUoJ3JvbGUnLCAnYWxlcnQnKTtcblxuICAgICAgICBsZXQgZWxlbWVudElubmVySFRNTCA9ICcnO1xuXG4gICAgICAgIGlmIChyZWxhdGVkRmllbGRJZCkge1xuICAgICAgICAgICAgZWxlbWVudC5zZXRBdHRyaWJ1dGUoJ2RhdGEtZm9yJywgcmVsYXRlZEZpZWxkSWQpO1xuICAgICAgICAgICAgZWxlbWVudElubmVySFRNTCArPSAnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJjbG9zZVwiIGRhdGEtZGlzbWlzcz1cImFsZXJ0XCIgYXJpYS1sYWJlbD1cIkNsb3NlXCI+PHNwYW4gYXJpYS1oaWRkZW49XCJ0cnVlXCI+w5c8L3NwYW4+PC9idXR0b24+JztcbiAgICAgICAgfVxuXG4gICAgICAgIGVsZW1lbnRJbm5lckhUTUwgKz0gZXJyb3JDb250ZW50O1xuICAgICAgICBlbGVtZW50LmlubmVySFRNTCA9IGVsZW1lbnRJbm5lckhUTUw7XG5cbiAgICAgICAgcmV0dXJuIG5ldyBBbGVydChlbGVtZW50KTtcbiAgICB9O1xuXG4gICAgc3RhdGljIGNyZWF0ZUZyb21FbGVtZW50IChhbGVydEVsZW1lbnQpIHtcbiAgICAgICAgcmV0dXJuIG5ldyBBbGVydChhbGVydEVsZW1lbnQpO1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBBbGVydEZhY3Rvcnk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvYWxlcnQtZmFjdG9yeS5qcyIsImxldCBDb29raWVPcHRpb25zTW9kYWwgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2Nvb2tpZS1vcHRpb25zLW1vZGFsJyk7XG5sZXQgQ29va2llT3B0aW9ucyA9IHJlcXVpcmUoJy4uL21vZGVsL2Nvb2tpZS1vcHRpb25zJyk7XG5sZXQgQWN0aW9uQmFkZ2UgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2FjdGlvbi1iYWRnZScpO1xuXG5jbGFzcyBDb29raWVPcHRpb25zRmFjdG9yeSB7XG4gICAgc3RhdGljIGNyZWF0ZSAoY29udGFpbmVyKSB7XG4gICAgICAgIHJldHVybiBuZXcgQ29va2llT3B0aW9ucyhcbiAgICAgICAgICAgIGNvbnRhaW5lci5vd25lckRvY3VtZW50LFxuICAgICAgICAgICAgbmV3IENvb2tpZU9wdGlvbnNNb2RhbChjb250YWluZXIucXVlcnlTZWxlY3RvcignLm1vZGFsJykpLFxuICAgICAgICAgICAgbmV3IEFjdGlvbkJhZGdlKGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCcubW9kYWwtbGF1bmNoZXInKSksXG4gICAgICAgICAgICBjb250YWluZXIucXVlcnlTZWxlY3RvcignLnN0YXR1cycpXG4gICAgICAgICk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBDb29raWVPcHRpb25zRmFjdG9yeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZXJ2aWNlcy9jb29raWUtb3B0aW9ucy1mYWN0b3J5LmpzIiwibGV0IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnNNb2RhbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLW1vZGFsJyk7XG5sZXQgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9ucyA9IHJlcXVpcmUoJy4uL21vZGVsL2h0dHAtYXV0aGVudGljYXRpb24tb3B0aW9ucycpO1xubGV0IEFjdGlvbkJhZGdlID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9hY3Rpb24tYmFkZ2UnKTtcblxuY2xhc3MgSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3Rvcnkge1xuICAgIHN0YXRpYyBjcmVhdGUgKGNvbnRhaW5lcikge1xuICAgICAgICByZXR1cm4gbmV3IEh0dHBBdXRoZW50aWNhdGlvbk9wdGlvbnMoXG4gICAgICAgICAgICBjb250YWluZXIub3duZXJEb2N1bWVudCxcbiAgICAgICAgICAgIG5ldyBIdHRwQXV0aGVudGljYXRpb25PcHRpb25zTW9kYWwoY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5tb2RhbCcpKSxcbiAgICAgICAgICAgIG5ldyBBY3Rpb25CYWRnZShjb250YWluZXIucXVlcnlTZWxlY3RvcignLm1vZGFsLWxhdW5jaGVyJykpLFxuICAgICAgICAgICAgY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5zdGF0dXMnKVxuICAgICAgICApO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gSHR0cEF1dGhlbnRpY2F0aW9uT3B0aW9uc0ZhY3Rvcnk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvaHR0cC1hdXRoZW50aWNhdGlvbi1vcHRpb25zLWZhY3RvcnkuanMiLCJjbGFzcyBIdHRwQ2xpZW50IHtcbiAgICBzdGF0aWMgZ2V0UmV0cmlldmVkRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdodHRwLWNsaWVudC5yZXRyaWV2ZWQnO1xuICAgIH07XG5cbiAgICBzdGF0aWMgcmVxdWVzdCAodXJsLCBtZXRob2QsIHJlc3BvbnNlVHlwZSwgZWxlbWVudCwgcmVxdWVzdElkLCBkYXRhID0gbnVsbCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBsZXQgcmVxdWVzdCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuXG4gICAgICAgIHJlcXVlc3Qub3BlbihtZXRob2QsIHVybCk7XG4gICAgICAgIHJlcXVlc3QucmVzcG9uc2VUeXBlID0gcmVzcG9uc2VUeXBlO1xuXG4gICAgICAgIGZvciAoY29uc3QgW2tleSwgdmFsdWVdIG9mIE9iamVjdC5lbnRyaWVzKHJlcXVlc3RIZWFkZXJzKSkge1xuICAgICAgICAgICAgcmVxdWVzdC5zZXRSZXF1ZXN0SGVhZGVyKGtleSwgdmFsdWUpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmVxdWVzdC5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICBsZXQgcmV0cmlldmVkRXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDoge1xuICAgICAgICAgICAgICAgICAgICByZXNwb25zZTogcmVxdWVzdC5yZXNwb25zZSxcbiAgICAgICAgICAgICAgICAgICAgcmVxdWVzdElkOiByZXF1ZXN0SWQsXG4gICAgICAgICAgICAgICAgICAgIHJlcXVlc3Q6IHJlcXVlc3RcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgZWxlbWVudC5kaXNwYXRjaEV2ZW50KHJldHJpZXZlZEV2ZW50KTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgaWYgKGRhdGEgPT09IG51bGwpIHtcbiAgICAgICAgICAgIHJlcXVlc3Quc2VuZCgpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgcmVxdWVzdC5zZW5kKGRhdGEpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHN0YXRpYyBnZXQgKHVybCwgcmVzcG9uc2VUeXBlLCBlbGVtZW50LCByZXF1ZXN0SWQsIHJlcXVlc3RIZWFkZXJzID0ge30pIHtcbiAgICAgICAgSHR0cENsaWVudC5yZXF1ZXN0KHVybCwgJ0dFVCcsIHJlc3BvbnNlVHlwZSwgZWxlbWVudCwgcmVxdWVzdElkLCBudWxsLCByZXF1ZXN0SGVhZGVycyk7XG4gICAgfTtcblxuICAgIHN0YXRpYyBnZXRKc29uICh1cmwsIGVsZW1lbnQsIHJlcXVlc3RJZCwgcmVxdWVzdEhlYWRlcnMgPSB7fSkge1xuICAgICAgICBsZXQgcmVhbFJlcXVlc3RIZWFkZXJzID0ge1xuICAgICAgICAgICAgJ0FjY2VwdCc6ICdhcHBsaWNhdGlvbi9qc29uJ1xuICAgICAgICB9O1xuXG4gICAgICAgIGZvciAoY29uc3QgW2tleSwgdmFsdWVdIG9mIE9iamVjdC5lbnRyaWVzKHJlcXVlc3RIZWFkZXJzKSkge1xuICAgICAgICAgICAgcmVhbFJlcXVlc3RIZWFkZXJzW2tleV0gPSB2YWx1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIEh0dHBDbGllbnQucmVxdWVzdCh1cmwsICdHRVQnLCAnanNvbicsIGVsZW1lbnQsIHJlcXVlc3RJZCwgbnVsbCwgcmVhbFJlcXVlc3RIZWFkZXJzKTtcbiAgICB9O1xuXG4gICAgc3RhdGljIGdldFRleHQgKHVybCwgZWxlbWVudCwgcmVxdWVzdElkLCByZXF1ZXN0SGVhZGVycyA9IHt9KSB7XG4gICAgICAgIEh0dHBDbGllbnQucmVxdWVzdCh1cmwsICdHRVQnLCAnJywgZWxlbWVudCwgcmVxdWVzdElkLCByZXF1ZXN0SGVhZGVycyk7XG4gICAgfTtcblxuICAgIHN0YXRpYyBwb3N0ICh1cmwsIGVsZW1lbnQsIHJlcXVlc3RJZCwgZGF0YSA9IG51bGwsIHJlcXVlc3RIZWFkZXJzID0ge30pIHtcbiAgICAgICAgbGV0IHJlYWxSZXF1ZXN0SGVhZGVycyA9IHtcbiAgICAgICAgICAgICdDb250ZW50LXR5cGUnOiAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJ1xuICAgICAgICB9O1xuXG4gICAgICAgIGZvciAoY29uc3QgW2tleSwgdmFsdWVdIG9mIE9iamVjdC5lbnRyaWVzKHJlcXVlc3RIZWFkZXJzKSkge1xuICAgICAgICAgICAgcmVhbFJlcXVlc3RIZWFkZXJzW2tleV0gPSB2YWx1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIEh0dHBDbGllbnQucmVxdWVzdCh1cmwsICdQT1NUJywgJycsIGVsZW1lbnQsIHJlcXVlc3RJZCwgZGF0YSwgcmVhbFJlcXVlc3RIZWFkZXJzKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IEh0dHBDbGllbnQ7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VydmljZXMvaHR0cC1jbGllbnQuanMiLCJsZXQgTGlzdGVkVGVzdCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvbGlzdGVkLXRlc3QvbGlzdGVkLXRlc3QnKTtcbmxldCBQcmVwYXJpbmdMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9wcmVwYXJpbmctbGlzdGVkLXRlc3QnKTtcbmxldCBQcm9ncmVzc2luZ0xpc3RlZFRlc3QgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L2xpc3RlZC10ZXN0L3Byb2dyZXNzaW5nLWxpc3RlZC10ZXN0Jyk7XG5sZXQgQ3Jhd2xpbmdMaXN0ZWRUZXN0ID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9saXN0ZWQtdGVzdC9jcmF3bGluZy1saXN0ZWQtdGVzdCcpO1xuXG5jbGFzcyBMaXN0ZWRUZXN0RmFjdG9yeSB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICpcbiAgICAgKiBAcmV0dXJucyB7TGlzdGVkVGVzdH1cbiAgICAgKi9cbiAgICBzdGF0aWMgY3JlYXRlRnJvbUVsZW1lbnQgKGVsZW1lbnQpIHtcbiAgICAgICAgaWYgKGVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKCdyZXF1aXJlcy1yZXN1bHRzJykpIHtcbiAgICAgICAgICAgIHJldHVybiBuZXcgUHJlcGFyaW5nTGlzdGVkVGVzdChlbGVtZW50KTtcbiAgICAgICAgfVxuXG4gICAgICAgIGxldCBzdGF0ZSA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXRlJyk7XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnaW4tcHJvZ3Jlc3MnKSB7XG4gICAgICAgICAgICByZXR1cm4gbmV3IFByb2dyZXNzaW5nTGlzdGVkVGVzdChlbGVtZW50KTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChzdGF0ZSA9PT0gJ2NyYXdsaW5nJykge1xuICAgICAgICAgICAgcmV0dXJuIG5ldyBDcmF3bGluZ0xpc3RlZFRlc3QoZWxlbWVudCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gbmV3IExpc3RlZFRlc3QoZWxlbWVudCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IExpc3RlZFRlc3RGYWN0b3J5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL2xpc3RlZC10ZXN0LWZhY3RvcnkuanMiLCJjbGFzcyBTdHJpcGVIYW5kbGVyIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1N0cmlwZX0gc3RyaXBlSnNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoc3RyaXBlSnMpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcyA9IHN0cmlwZUpzO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBzdHJpcGVQdWJsaXNoYWJsZUtleVxuICAgICAqL1xuICAgIHNldFN0cmlwZVB1Ymxpc2hhYmxlS2V5IChzdHJpcGVQdWJsaXNoYWJsZUtleSkge1xuICAgICAgICB0aGlzLnN0cmlwZUpzLnNldFB1Ymxpc2hhYmxlS2V5KHN0cmlwZVB1Ymxpc2hhYmxlS2V5KTtcbiAgICB9XG5cbiAgICBnZXRDcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdzdHJpcGUtaGFuZGVyLmNyZWF0ZS1jYXJkLXRva2VuLnN1Y2Nlc3MnO1xuICAgIH07XG5cbiAgICBnZXRDcmVhdGVDYXJkVG9rZW5GYWlsdXJlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICdzdHJpcGUtaGFuZGVyLmNyZWF0ZS1jYXJkLXRva2VuLmZhaWx1cmUnO1xuICAgIH07XG5cbiAgICBjcmVhdGVDYXJkVG9rZW4gKGRhdGEsIGZvcm1FbGVtZW50KSB7XG4gICAgICAgIHRoaXMuc3RyaXBlSnMuY2FyZC5jcmVhdGVUb2tlbihkYXRhLCAoc3RhdHVzLCByZXNwb25zZSkgPT4ge1xuICAgICAgICAgICAgbGV0IGlzRXJyb3JSZXNwb25zZSA9IHJlc3BvbnNlLmhhc093blByb3BlcnR5KCdlcnJvcicpO1xuXG4gICAgICAgICAgICBsZXQgZXZlbnROYW1lID0gaXNFcnJvclJlc3BvbnNlXG4gICAgICAgICAgICAgICAgPyB0aGlzLmdldENyZWF0ZUNhcmRUb2tlbkZhaWx1cmVFdmVudE5hbWUoKVxuICAgICAgICAgICAgICAgIDogdGhpcy5nZXRDcmVhdGVDYXJkVG9rZW5TdWNjZXNzRXZlbnROYW1lKCk7XG5cbiAgICAgICAgICAgIGZvcm1FbGVtZW50LmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KGV2ZW50TmFtZSwge1xuICAgICAgICAgICAgICAgIGRldGFpbDogcmVzcG9uc2VcbiAgICAgICAgICAgIH0pKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBTdHJpcGVIYW5kbGVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL3N0cmlwZS1oYW5kbGVyLmpzIiwibGV0IEh0dHBDbGllbnQgPSByZXF1aXJlKCcuL2h0dHAtY2xpZW50Jyk7XG5sZXQgUHJvZ3Jlc3NCYXIgPSByZXF1aXJlKCcuLi9tb2RlbC9lbGVtZW50L3Byb2dyZXNzLWJhcicpO1xuXG5jbGFzcyBUZXN0UmVzdWx0UmV0cmlldmVyIHtcbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnN0YXR1c1VybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN0YXR1cy11cmwnKTtcbiAgICAgICAgdGhpcy51bnJldHJpZXZlZFRhc2tJZHNVcmwgPSBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS11bnJldHJpZXZlZC10YXNrLWlkcy11cmwnKTtcbiAgICAgICAgdGhpcy5yZXRyaWV2ZVRhc2tzVXJsID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcmV0cmlldmUtdGFza3MtdXJsJyk7XG4gICAgICAgIHRoaXMuc3VtbWFyeVVybCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLXN1bW1hcnktdXJsJyk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIgPSBuZXcgUHJvZ3Jlc3NCYXIodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5wcm9ncmVzcy1iYXInKSk7XG4gICAgICAgIHRoaXMuc3VtbWFyeSA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnN1bW1hcnknKTtcbiAgICB9XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoSHR0cENsaWVudC5nZXRSZXRyaWV2ZWRFdmVudE5hbWUoKSwgdGhpcy5faHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuXG4gICAgICAgIHRoaXMuX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzKCk7XG4gICAgfTtcblxuICAgIGdldFJldHJpZXZlZEV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGVzdC1yZXN1bHQtcmV0cmlldmVyLnJldHJpZXZlZCc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7Q3VzdG9tRXZlbnR9IGV2ZW50XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfaHR0cFJlcXVlc3RSZXRyaWV2ZWRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBsZXQgcmVzcG9uc2UgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG4gICAgICAgIGxldCByZXF1ZXN0SWQgPSBldmVudC5kZXRhaWwucmVxdWVzdElkO1xuXG4gICAgICAgIGlmIChyZXF1ZXN0SWQgPT09ICdyZXRyaWV2ZVByZXBhcmluZ1N0YXR1cycpIHtcbiAgICAgICAgICAgIGxldCBjb21wbGV0aW9uUGVyY2VudCA9IHJlc3BvbnNlLmNvbXBsZXRpb25fcGVyY2VudDtcblxuICAgICAgICAgICAgdGhpcy5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudChjb21wbGV0aW9uUGVyY2VudCk7XG5cbiAgICAgICAgICAgIGlmIChjb21wbGV0aW9uUGVyY2VudCA+PSAxMDApIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZUZpbmlzaGVkU3VtbWFyeSgpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9kaXNwbGF5UHJlcGFyaW5nU3VtbWFyeShyZXNwb25zZSk7XG4gICAgICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbigpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlTmV4dFJlbW92ZVRhc2tJZENvbGxlY3Rpb24nKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZU5leHRSZW1vdGVUYXNrQ29sbGVjdGlvbihyZXNwb25zZSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAocmVxdWVzdElkID09PSAncmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24nKSB7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVByZXBhcmluZ1N0YXR1cygpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlRmluaXNoZWRTdW1tYXJ5Jykge1xuICAgICAgICAgICAgbGV0IHJldHJpZXZlZFN1bW1hcnlDb250YWluZXIgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgcmV0cmlldmVkU3VtbWFyeUNvbnRhaW5lci5pbm5lckhUTUwgPSByZXNwb25zZTtcblxuICAgICAgICAgICAgbGV0IHJldHJpZXZlZEV2ZW50ID0gbmV3IEN1c3RvbUV2ZW50KHRoaXMuZ2V0UmV0cmlldmVkRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHJldHJpZXZlZFN1bW1hcnlDb250YWluZXIucXVlcnlTZWxlY3RvcignLmxpc3RlZC10ZXN0JylcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChyZXRyaWV2ZWRFdmVudCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JldHJpZXZlUHJlcGFyaW5nU3RhdHVzICgpIHtcbiAgICAgICAgSHR0cENsaWVudC5nZXRKc29uKHRoaXMuc3RhdHVzVXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZVByZXBhcmluZ1N0YXR1cycpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbiAoKSB7XG4gICAgICAgIEh0dHBDbGllbnQuZ2V0SnNvbih0aGlzLnVucmV0cmlldmVkVGFza0lkc1VybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVOZXh0UmVtb3ZlVGFza0lkQ29sbGVjdGlvbicpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24gKHJlbW90ZVRhc2tJZHMpIHtcbiAgICAgICAgSHR0cENsaWVudC5wb3N0KHRoaXMucmV0cmlldmVUYXNrc1VybCwgdGhpcy5lbGVtZW50LCAncmV0cmlldmVOZXh0UmVtb3RlVGFza0NvbGxlY3Rpb24nLCAncmVtb3RlVGFza0lkcz0nICsgcmVtb3RlVGFza0lkcy5qb2luKCcsJykpO1xuICAgIH07XG5cbiAgICBfcmV0cmlldmVGaW5pc2hlZFN1bW1hcnkgKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldFRleHQodGhpcy5zdW1tYXJ5VXJsLCB0aGlzLmVsZW1lbnQsICdyZXRyaWV2ZUZpbmlzaGVkU3VtbWFyeScpO1xuICAgIH07XG5cbiAgICBfY3JlYXRlUHJlcGFyaW5nU3VtbWFyeSAoc3RhdHVzRGF0YSkge1xuICAgICAgICBsZXQgbG9jYWxUYXNrQ291bnQgPSBzdGF0dXNEYXRhLmxvY2FsX3Rhc2tfY291bnQ7XG4gICAgICAgIGxldCByZW1vdGVUYXNrQ291bnQgPSBzdGF0dXNEYXRhLnJlbW90ZV90YXNrX2NvdW50O1xuXG4gICAgICAgIGlmIChsb2NhbFRhc2tDb3VudCA9PT0gdW5kZWZpbmVkICYmIHJlbW90ZVRhc2tDb3VudCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICByZXR1cm4gJ1ByZXBhcmluZyByZXN1bHRzICZoZWxsaXA7JztcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiAnUHJlcGFyaW5nICZoZWxsaXA7IGNvbGxlY3RlZCA8c3Ryb25nIGNsYXNzPVwibG9jYWwtdGFzay1jb3VudFwiPicgKyBsb2NhbFRhc2tDb3VudCArICc8L3N0cm9uZz4gcmVzdWx0cyBvZiA8c3Ryb25nIGNsYXNzPVwicmVtb3RlLXRhc2stY291bnRcIj4nICsgcmVtb3RlVGFza0NvdW50ICsgJzwvc3Ryb25nPic7XG4gICAgfTtcblxuICAgIF9kaXNwbGF5UHJlcGFyaW5nU3VtbWFyeSAoc3RhdHVzRGF0YSkge1xuICAgICAgICB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByZXBhcmluZyAuc3VtbWFyeScpLmlubmVySFRNTCA9IHRoaXMuX2NyZWF0ZVByZXBhcmluZ1N1bW1hcnkoc3RhdHVzRGF0YSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBUZXN0UmVzdWx0UmV0cmlldmVyO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlcnZpY2VzL3Rlc3QtcmVzdWx0LXJldHJpZXZlci5qcyIsIi8qIGdsb2JhbCBBd2Vzb21wbGV0ZSAqL1xuXG5sZXQgZm9ybUZpZWxkRm9jdXNlciA9IHJlcXVpcmUoJy4uL2Zvcm0tZmllbGQtZm9jdXNlcicpO1xucmVxdWlyZSgnYXdlc29tcGxldGUnKTtcblxuY2xhc3MgTW9kYWwge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmFwcGx5QnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcjYXBwbHktZmlsdGVyLWJ1dHRvbicpO1xuICAgICAgICB0aGlzLmNsZWFyQnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcjY2xlYXItZmlsdGVyLWJ1dHRvbicpO1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuY2xvc2UnKTtcbiAgICAgICAgdGhpcy5pbnB1dCA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignaW5wdXRbbmFtZT1maWx0ZXJdJyk7XG4gICAgICAgIHRoaXMuZmlsdGVyID0gdGhpcy5pbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgIHRoaXMucHJldmlvdXNGaWx0ZXIgPSB0aGlzLmlucHV0LnZhbHVlLnRyaW0oKTtcbiAgICAgICAgdGhpcy5hcHBseUZpbHRlciA9IGZhbHNlO1xuICAgICAgICB0aGlzLmF3ZXNvbWVwbGV0ZSA9IG5ldyBBd2Vzb21wbGV0ZSh0aGlzLmlucHV0KTtcbiAgICAgICAgdGhpcy5zdWdnZXN0aW9ucyA9IFtdO1xuICAgICAgICB0aGlzLmZpbHRlckNoYW5nZWRFdmVudE5hbWUgPSAndGVzdC1oaXN0b3J5Lm1vZGFsLmZpbHRlci5jaGFuZ2VkJztcblxuICAgICAgICB0aGlzLmluaXQoKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1N0cmluZ1tdfSBzdWdnZXN0aW9uc1xuICAgICAqL1xuICAgIHNldFN1Z2dlc3Rpb25zIChzdWdnZXN0aW9ucykge1xuICAgICAgICB0aGlzLnN1Z2dlc3Rpb25zID0gc3VnZ2VzdGlvbnM7XG4gICAgICAgIHRoaXMuYXdlc29tZXBsZXRlLmxpc3QgPSB0aGlzLnN1Z2dlc3Rpb25zO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICBsZXQgc2hvd25FdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgZm9ybUZpZWxkRm9jdXNlcih0aGlzLmlucHV0KTtcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgaGlkZUV2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBjb25zdCBXSUxEQ0FSRCA9ICcqJztcbiAgICAgICAgICAgIGNvbnN0IGZpbHRlcklzRW1wdHkgPSB0aGlzLmZpbHRlciA9PT0gJyc7XG4gICAgICAgICAgICBjb25zdCBzdWdnZXN0aW9uc0luY2x1ZGVzRmlsdGVyID0gdGhpcy5zdWdnZXN0aW9ucy5pbmNsdWRlcyh0aGlzLmZpbHRlcik7XG4gICAgICAgICAgICBjb25zdCBmaWx0ZXJJc1dpbGRjYXJkUHJlZml4ZWQgPSB0aGlzLmZpbHRlci5jaGFyQXQoMCkgPT09IFdJTERDQVJEO1xuICAgICAgICAgICAgY29uc3QgZmlsdGVySXNXaWxkY2FyZFN1ZmZpeGVkID0gdGhpcy5maWx0ZXIuc2xpY2UoLTEpID09PSBXSUxEQ0FSRDtcblxuICAgICAgICAgICAgaWYgKCFmaWx0ZXJJc0VtcHR5ICYmICFzdWdnZXN0aW9uc0luY2x1ZGVzRmlsdGVyKSB7XG4gICAgICAgICAgICAgICAgaWYgKCFmaWx0ZXJJc1dpbGRjYXJkUHJlZml4ZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5maWx0ZXIgPSBXSUxEQ0FSRCArIHRoaXMuZmlsdGVyO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICghZmlsdGVySXNXaWxkY2FyZFN1ZmZpeGVkKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZmlsdGVyICs9IFdJTERDQVJEO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHRoaXMuaW5wdXQudmFsdWUgPSB0aGlzLmZpbHRlcjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy5hcHBseUZpbHRlciA9IHRoaXMuZmlsdGVyICE9PSB0aGlzLnByZXZpb3VzRmlsdGVyO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBoaWRkZW5FdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKCF0aGlzLmFwcGx5RmlsdGVyKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQodGhpcy5maWx0ZXJDaGFuZ2VkRXZlbnROYW1lLCB7XG4gICAgICAgICAgICAgICAgZGV0YWlsOiB0aGlzLmZpbHRlclxuICAgICAgICAgICAgfSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBhcHBseUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuZmlsdGVyID0gdGhpcy5pbnB1dC52YWx1ZS50cmltKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGNsZWFyQnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5pbnB1dC52YWx1ZSA9ICcnO1xuICAgICAgICAgICAgdGhpcy5maWx0ZXIgPSAnJztcbiAgICAgICAgfTtcblxuICAgICAgICBsZXQgY2xvc2VCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmFwcGx5RmlsdGVyID0gZmFsc2U7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Nob3duLmJzLm1vZGFsJywgc2hvd25FdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignaGlkZS5icy5tb2RhbCcsIGhpZGVFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignaGlkZGVuLmJzLm1vZGFsJywgaGlkZGVuRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICAgICAgdGhpcy5hcHBseUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGFwcGx5QnV0dG9uQ2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgICAgICB0aGlzLmNsZWFyQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xlYXJCdXR0b25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBjbG9zZUJ1dHRvbkNsaWNrRXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IE1vZGFsO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtaGlzdG9yeS9tb2RhbC5qcyIsImNsYXNzIFN1Z2dlc3Rpb25zIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0RvY3VtZW50fSBkb2N1bWVudFxuICAgICAqIEBwYXJhbSB7U3RyaW5nfSBzb3VyY2VVcmxcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZG9jdW1lbnQsIHNvdXJjZVVybCkge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMuc291cmNlVXJsID0gc291cmNlVXJsO1xuICAgICAgICB0aGlzLmxvYWRlZEV2ZW50TmFtZSA9ICd0ZXN0LWhpc3Rvcnkuc3VnZ2VzdGlvbnMubG9hZGVkJztcbiAgICB9XG5cbiAgICByZXRyaWV2ZSAoKSB7XG4gICAgICAgIGxldCByZXF1ZXN0ID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG4gICAgICAgIGxldCBzdWdnZXN0aW9ucyA9IG51bGw7XG5cbiAgICAgICAgcmVxdWVzdC5vcGVuKCdHRVQnLCB0aGlzLnNvdXJjZVVybCwgZmFsc2UpO1xuXG4gICAgICAgIGxldCByZXF1ZXN0T25sb2FkSGFuZGxlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmIChyZXF1ZXN0LnN0YXR1cyA+PSAyMDAgJiYgcmVxdWVzdC5zdGF0dXMgPCA0MDApIHtcbiAgICAgICAgICAgICAgICBzdWdnZXN0aW9ucyA9IEpTT04ucGFyc2UocmVxdWVzdC5yZXNwb25zZVRleHQpO1xuXG4gICAgICAgICAgICAgICAgdGhpcy5kb2N1bWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudCh0aGlzLmxvYWRlZEV2ZW50TmFtZSwge1xuICAgICAgICAgICAgICAgICAgICBkZXRhaWw6IHN1Z2dlc3Rpb25zXG4gICAgICAgICAgICAgICAgfSkpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHJlcXVlc3Qub25sb2FkID0gcmVxdWVzdE9ubG9hZEhhbmRsZXIuYmluZCh0aGlzKTtcblxuICAgICAgICByZXF1ZXN0LnNlbmQoKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFN1Z2dlc3Rpb25zO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtaGlzdG9yeS9zdWdnZXN0aW9ucy5qcyIsImxldCBGb3JtQnV0dG9uID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9mb3JtLWJ1dHRvbicpO1xubGV0IFByb2dyZXNzQmFyID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9wcm9ncmVzcy1iYXInKTtcbmxldCBUYXNrUXVldWVzID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC90YXNrLXF1ZXVlcycpO1xuXG5jbGFzcyBTdW1tYXJ5IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLmNhbmNlbEFjdGlvbiA9IG5ldyBGb3JtQnV0dG9uKGVsZW1lbnQucXVlcnlTZWxlY3RvcignLmNhbmNlbC1hY3Rpb24nKSk7XG4gICAgICAgIHRoaXMuY2FuY2VsQ3Jhd2xBY3Rpb24gPSBuZXcgRm9ybUJ1dHRvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jYW5jZWwtY3Jhd2wtYWN0aW9uJykpO1xuICAgICAgICB0aGlzLnByb2dyZXNzQmFyID0gbmV3IFByb2dyZXNzQmFyKGVsZW1lbnQucXVlcnlTZWxlY3RvcignLnByb2dyZXNzLWJhcicpKTtcbiAgICAgICAgdGhpcy5zdGF0ZUxhYmVsID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcuanMtc3RhdGUtbGFiZWwnKTtcbiAgICAgICAgdGhpcy50YXNrUXVldWVzID0gbmV3IFRhc2tRdWV1ZXMoZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcudGFzay1xdWV1ZXMnKSk7XG4gICAgfVxuXG4gICAgaW5pdCAoKSB7XG4gICAgICAgIHRoaXMuX2FkZEV2ZW50TGlzdGVuZXJzKCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldFJlbmRlckFtbWVuZG1lbnRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rlc3QtcHJvZ3Jlc3Muc3VtbWFyeS5yZW5kZXItYW1tZW5kbWVudCc7XG4gICAgfTtcblxuICAgIF9hZGRFdmVudExpc3RlbmVycyAoKSB7XG4gICAgICAgIHRoaXMuY2FuY2VsQWN0aW9uLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0aGlzLl9jYW5jZWxBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuY2FuY2VsQ3Jhd2xBY3Rpb24uZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuX2NhbmNlbENyYXdsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyLmJpbmQodGhpcykpO1xuICAgIH07XG5cbiAgICBfY2FuY2VsQWN0aW9uQ2xpY2tFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgdGhpcy5jYW5jZWxBY3Rpb24ubWFya0FzQnVzeSgpO1xuICAgICAgICB0aGlzLmNhbmNlbEFjdGlvbi5kZUVtcGhhc2l6ZSgpO1xuICAgIH07XG5cbiAgICBfY2FuY2VsQ3Jhd2xBY3Rpb25DbGlja0V2ZW50TGlzdGVuZXIgKCkge1xuICAgICAgICB0aGlzLmNhbmNlbENyYXdsQWN0aW9uLm1hcmtBc0J1c3koKTtcbiAgICAgICAgdGhpcy5jYW5jZWxDcmF3bEFjdGlvbi5kZUVtcGhhc2l6ZSgpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge29iamVjdH0gc3VtbWFyeURhdGFcbiAgICAgKi9cbiAgICByZW5kZXIgKHN1bW1hcnlEYXRhKSB7XG4gICAgICAgIGxldCByZW1vdGVUZXN0ID0gc3VtbWFyeURhdGEucmVtb3RlX3Rlc3Q7XG5cbiAgICAgICAgdGhpcy5wcm9ncmVzc0Jhci5zZXRDb21wbGV0aW9uUGVyY2VudChyZW1vdGVUZXN0LmNvbXBsZXRpb25fcGVyY2VudCk7XG4gICAgICAgIHRoaXMucHJvZ3Jlc3NCYXIuc2V0U3R5bGUoc3VtbWFyeURhdGEudGVzdC5zdGF0ZSA9PT0gJ2NyYXdsaW5nJyA/ICd3YXJuaW5nJyA6ICdkZWZhdWx0Jyk7XG4gICAgICAgIHRoaXMuc3RhdGVMYWJlbC5pbm5lclRleHQgPSBzdW1tYXJ5RGF0YS5zdGF0ZV9sYWJlbDtcbiAgICAgICAgdGhpcy50YXNrUXVldWVzLnJlbmRlcihyZW1vdGVUZXN0LnRhc2tfY291bnQsIHJlbW90ZVRlc3QudGFza19jb3VudF9ieV9zdGF0ZSk7XG5cbiAgICAgICAgaWYgKHJlbW90ZVRlc3QuYW1tZW5kbWVudHMgJiYgcmVtb3RlVGVzdC5hbW1lbmRtZW50cy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoU3VtbWFyeS5nZXRSZW5kZXJBbW1lbmRtZW50RXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IHJlbW90ZVRlc3QuYW1tZW5kbWVudHNbMF1cbiAgICAgICAgICAgIH0pKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU3VtbWFyeTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXByb2dyZXNzL3N1bW1hcnkuanMiLCJjbGFzcyBUYXNrSWRMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcltdfSB0YXNrSWRzXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VMZW5ndGhcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAodGFza0lkcywgcGFnZUxlbmd0aCkge1xuICAgICAgICB0aGlzLnRhc2tJZHMgPSB0YXNrSWRzO1xuICAgICAgICB0aGlzLnBhZ2VMZW5ndGggPSBwYWdlTGVuZ3RoO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKlxuICAgICAqIEByZXR1cm5zIHtudW1iZXJbXX1cbiAgICAgKi9cbiAgICBnZXRGb3JQYWdlIChwYWdlSW5kZXgpIHtcbiAgICAgICAgbGV0IHBhZ2VOdW1iZXIgPSBwYWdlSW5kZXggKyAxO1xuXG4gICAgICAgIHJldHVybiB0aGlzLnRhc2tJZHMuc2xpY2UocGFnZUluZGV4ICogdGhpcy5wYWdlTGVuZ3RoLCBwYWdlTnVtYmVyICogdGhpcy5wYWdlTGVuZ3RoKTtcbiAgICB9O1xufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tJZExpc3Q7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdGVzdC1wcm9ncmVzcy90YXNrLWlkLWxpc3QuanMiLCJjbGFzcyBUYXNrTGlzdFBhZ2luYXRpb24ge1xuICAgIGNvbnN0cnVjdG9yIChwYWdlTGVuZ3RoLCB0YXNrQ291bnQpIHtcbiAgICAgICAgdGhpcy5wYWdlTGVuZ3RoID0gcGFnZUxlbmd0aDtcbiAgICAgICAgdGhpcy50YXNrQ291bnQgPSB0YXNrQ291bnQ7XG4gICAgICAgIHRoaXMucGFnZUNvdW50ID0gTWF0aC5jZWlsKHRhc2tDb3VudCAvIHBhZ2VMZW5ndGgpO1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBudWxsO1xuICAgIH1cblxuICAgIHN0YXRpYyBnZXRTZWxlY3RvciAoKSB7XG4gICAgICAgIHJldHVybiAnLnBhZ2luYXRpb24nO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHN0YXRpYyBnZXRTZWxlY3RQYWdlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0YXNrLWxpc3QtcGFnaW5hdGlvbi5zZWxlY3QtcGFnZSc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSAoKSB7XG4gICAgICAgIHJldHVybiAndGFzay1saXN0LXBhZ2luYXRpb24uc2VsZWN0LXByZXZpb3VzLXBhZ2UnO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtzdHJpbmd9XG4gICAgICovXG4gICAgc3RhdGljIGdldFNlbGVjdE5leHRQYWdlRXZlbnROYW1lICgpIHtcbiAgICAgICAgcmV0dXJuICd0YXNrLWxpc3QtcGFnaW5hdGlvbi5zZWxlY3QtbmV4dC1wYWdlJztcbiAgICB9XG5cbiAgICBpbml0IChlbGVtZW50KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMucGFnZUFjdGlvbnMgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2EnKTtcbiAgICAgICAgdGhpcy5wcmV2aW91c0FjdGlvbiA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtcm9sZT1wcmV2aW91c10nKTtcbiAgICAgICAgdGhpcy5uZXh0QWN0aW9uID0gZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1yb2xlPW5leHRdJyk7XG5cbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKHRoaXMucGFnZUFjdGlvbnMsIChwYWdlQWN0aW9ucykgPT4ge1xuICAgICAgICAgICAgcGFnZUFjdGlvbnMuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgICAgICAgICAgbGV0IGFjdGlvbkNvbnRhaW5lciA9IHBhZ2VBY3Rpb25zLnBhcmVudE5vZGU7XG4gICAgICAgICAgICAgICAgaWYgKCFhY3Rpb25Db250YWluZXIuY2xhc3NMaXN0LmNvbnRhaW5zKCdhY3RpdmUnKSkge1xuICAgICAgICAgICAgICAgICAgICBsZXQgcm9sZSA9IHBhZ2VBY3Rpb25zLmdldEF0dHJpYnV0ZSgnZGF0YS1yb2xlJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHJvbGUgPT09ICdzaG93UGFnZScpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChUYXNrTGlzdFBhZ2luYXRpb24uZ2V0U2VsZWN0UGFnZUV2ZW50TmFtZSgpLCB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGV0YWlsOiBwYXJzZUludChwYWdlQWN0aW9ucy5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMClcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGlmIChyb2xlID09PSAncHJldmlvdXMnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdFByZXZpb3VzUGFnZUV2ZW50TmFtZSgpKSk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBpZiAocm9sZSA9PT0gJ25leHQnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3RQYWdpbmF0aW9uLmdldFNlbGVjdE5leHRQYWdlRXZlbnROYW1lKCkpKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgY3JlYXRlTWFya3VwICgpIHtcbiAgICAgICAgbGV0IG1hcmt1cCA9ICc8dWwgY2xhc3M9XCJwYWdpbmF0aW9uXCI+JztcblxuICAgICAgICBtYXJrdXAgKz0gJzxsaSBjbGFzcz1cImlzLXhzIHByZXZpb3VzLW5leHQgcHJldmlvdXMgZGlzYWJsZWQgaGlkZGVuLWxnIGhpZGRlbi1tZCBoaWRkZW4tc21cIj48YSBocmVmPVwiI1wiIGRhdGEtcm9sZT1cInByZXZpb3VzXCI+PGkgY2xhc3M9XCJmYSBmYS1jYXJldC1sZWZ0XCI+PC9pPiBQcmV2aW91czwvYT48L2xpPic7XG4gICAgICAgIG1hcmt1cCArPSAnPGxpIGNsYXNzPVwiaGlkZGVuLWxnIGhpZGRlbi1tZCBoaWRkZW4tc20gZGlzYWJsZWRcIj48c3Bhbj5QYWdlIDxzdHJvbmcgY2xhc3M9XCJwYWdlLW51bWJlclwiPjE8L3N0cm9uZz4gb2YgPHN0cm9uZz4nICsgdGhpcy5wYWdlQ291bnQgKyAnPC9zdHJvbmc+PC9zcGFuPjwvbGk+JztcblxuICAgICAgICBmb3IgKGxldCBwYWdlSW5kZXggPSAwOyBwYWdlSW5kZXggPCB0aGlzLnBhZ2VDb3VudDsgcGFnZUluZGV4KyspIHtcbiAgICAgICAgICAgIGxldCBzdGFydEluZGV4ID0gKHBhZ2VJbmRleCAqIHRoaXMucGFnZUxlbmd0aCkgKyAxO1xuICAgICAgICAgICAgbGV0IGVuZEluZGV4ID0gTWF0aC5taW4oc3RhcnRJbmRleCArIHRoaXMucGFnZUxlbmd0aCAtIDEsIHRoaXMudGFza0NvdW50KTtcblxuICAgICAgICAgICAgbWFya3VwICs9ICc8bGkgY2xhc3M9XCJpcy1ub3QteHMgaGlkZGVuLXhzICcgKyAocGFnZUluZGV4ID09PSAwID8gJ2FjdGl2ZScgOiAnJykgKyAnXCI+PGEgaHJlZj1cIiNcIiBkYXRhLXBhZ2UtaW5kZXg9XCInICsgcGFnZUluZGV4ICsgJ1wiIGRhdGEtcm9sZT1cInNob3dQYWdlXCI+JyArIHN0YXJ0SW5kZXggKyAnIOKApiAnICsgZW5kSW5kZXggKyAnPC9hPjwvbGk+JztcbiAgICAgICAgfVxuXG4gICAgICAgIG1hcmt1cCArPSAnPGxpIGNsYXNzPVwibmV4dCBwcmV2aW91cy1uZXh0IGhpZGRlbi1sZyBoaWRkZW4tbWQgaGlkZGVuLXNtXCI+PGEgaHJlZj1cIiNcIiBkYXRhLXJvbGU9XCJuZXh0XCI+TmV4dCA8aSBjbGFzcz1cImZhIGZhLWNhcmV0LXJpZ2h0XCI+PC9pPjwvYT48L2xpPic7XG4gICAgICAgIG1hcmt1cCArPSAnPC91bD4nO1xuXG4gICAgICAgIHJldHVybiBtYXJrdXA7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzUmVxdWlyZWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy50YXNrQ291bnQgPiB0aGlzLnBhZ2VMZW5ndGg7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAqL1xuICAgIGlzUmVuZGVyZWQgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50ICE9PSBudWxsO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUluZGV4XG4gICAgICovXG4gICAgc2VsZWN0UGFnZSAocGFnZUluZGV4KSB7XG4gICAgICAgIFtdLmZvckVhY2guY2FsbCh0aGlzLnBhZ2VBY3Rpb25zLCAocGFnZUFjdGlvbikgPT4ge1xuICAgICAgICAgICAgbGV0IGlzQWN0aXZlID0gcGFyc2VJbnQocGFnZUFjdGlvbi5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFnZS1pbmRleCcpLCAxMCkgPT09IHBhZ2VJbmRleDtcbiAgICAgICAgICAgIGxldCBhY3Rpb25Db250YWluZXIgPSBwYWdlQWN0aW9uLnBhcmVudE5vZGU7XG5cbiAgICAgICAgICAgIGlmIChpc0FjdGl2ZSkge1xuICAgICAgICAgICAgICAgIGFjdGlvbkNvbnRhaW5lci5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgYWN0aW9uQ29udGFpbmVyLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignLnBhZ2UtbnVtYmVyJykuaW5uZXJUZXh0ID0gKHBhZ2VJbmRleCArIDEpO1xuICAgICAgICB0aGlzLnByZXZpb3VzQWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGlzYWJsZWQnKTtcbiAgICAgICAgdGhpcy5uZXh0QWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGlzYWJsZWQnKTtcblxuICAgICAgICBpZiAocGFnZUluZGV4ID09PSAwKSB7XG4gICAgICAgICAgICB0aGlzLnByZXZpb3VzQWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGlzYWJsZWQnKTtcbiAgICAgICAgfSBlbHNlIGlmIChwYWdlSW5kZXggPT09IHRoaXMucGFnZUNvdW50IC0gMSkge1xuICAgICAgICAgICAgdGhpcy5uZXh0QWN0aW9uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGlzYWJsZWQnKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gVGFza0xpc3RQYWdpbmF0aW9uO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LXBhZ2luYXRvci5qcyIsImxldCBUYXNrTGlzdE1vZGVsID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC90YXNrLWxpc3QnKTtcbmxldCBJY29uID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9pY29uJyk7XG5sZXQgVGFza0lkTGlzdCA9IHJlcXVpcmUoJy4vdGFzay1pZC1saXN0Jyk7XG5sZXQgSHR0cENsaWVudCA9IHJlcXVpcmUoJy4uL3NlcnZpY2VzL2h0dHAtY2xpZW50Jyk7XG5cbmNsYXNzIFRhc2tMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gcGFnZUxlbmd0aFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBwYWdlTGVuZ3RoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuY3VycmVudFBhZ2VJbmRleCA9IDA7XG4gICAgICAgIHRoaXMucGFnZUxlbmd0aCA9IHBhZ2VMZW5ndGg7XG4gICAgICAgIHRoaXMudGFza0lkTGlzdCA9IG51bGw7XG4gICAgICAgIHRoaXMuaXNJbml0aWFsaXppbmcgPSBmYWxzZTtcbiAgICAgICAgdGhpcy50YXNrTGlzdE1vZGVscyA9IHt9O1xuICAgICAgICB0aGlzLmhlYWRpbmcgPSBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2gyJyk7XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEB0eXBlIHtJY29ufVxuICAgICAgICAgKi9cbiAgICAgICAgdGhpcy5idXN5SWNvbiA9IHRoaXMuX2NyZWF0ZUJ1c3lJY29uKCk7XG4gICAgICAgIHRoaXMuaGVhZGluZy5hcHBlbmRDaGlsZCh0aGlzLmJ1c3lJY29uLmVsZW1lbnQpO1xuICAgIH1cblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmlzSW5pdGlhbGl6aW5nID0gdHJ1ZTtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2hpZGRlbicpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihIdHRwQ2xpZW50LmdldFJldHJpZXZlZEV2ZW50TmFtZSgpLCB0aGlzLl9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIHRoaXMuX3JlcXVlc3RUYXNrSWRzKCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBpbmRleFxuICAgICAqL1xuICAgIHNldEN1cnJlbnRQYWdlSW5kZXggKGluZGV4KSB7XG4gICAgICAgIHRoaXMuY3VycmVudFBhZ2VJbmRleCA9IGluZGV4O1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gcGFnaW5hdGlvbkVsZW1lbnRcbiAgICAgKi9cbiAgICBzZXRQYWdpbmF0aW9uRWxlbWVudCAocGFnaW5hdGlvbkVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5oZWFkaW5nLmluc2VydEFkamFjZW50RWxlbWVudCgnYWZ0ZXJlbmQnLCBwYWdpbmF0aW9uRWxlbWVudCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge3N0cmluZ31cbiAgICAgKi9cbiAgICBzdGF0aWMgZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3Rhc2stbGlzdC5pbml0aWFsaXplZCc7XG4gICAgfTtcblxuICAgIF9odHRwUmVxdWVzdFJldHJpZXZlZEV2ZW50TGlzdGVuZXIgKGV2ZW50KSB7XG4gICAgICAgIGxldCByZXF1ZXN0SWQgPSBldmVudC5kZXRhaWwucmVxdWVzdElkO1xuICAgICAgICBsZXQgcmVzcG9uc2UgPSBldmVudC5kZXRhaWwucmVzcG9uc2U7XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JlcXVlc3RUYXNrSWRzJykge1xuICAgICAgICAgICAgdGhpcy50YXNrSWRMaXN0ID0gbmV3IFRhc2tJZExpc3QocmVzcG9uc2UsIHRoaXMucGFnZUxlbmd0aCk7XG4gICAgICAgICAgICB0aGlzLmlzSW5pdGlhbGl6aW5nID0gZmFsc2U7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoVGFza0xpc3QuZ2V0SW5pdGlhbGl6ZWRFdmVudE5hbWUoKSkpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlVGFza1BhZ2UnKSB7XG4gICAgICAgICAgICBsZXQgdGFza0xpc3RNb2RlbCA9IG5ldyBUYXNrTGlzdE1vZGVsKHRoaXMuX2NyZWF0ZVRhc2tMaXN0RWxlbWVudEZyb21IdG1sKHJlc3BvbnNlKSk7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gdGFza0xpc3RNb2RlbC5nZXRQYWdlSW5kZXgoKTtcblxuICAgICAgICAgICAgdGhpcy50YXNrTGlzdE1vZGVsc1twYWdlSW5kZXhdID0gdGFza0xpc3RNb2RlbDtcbiAgICAgICAgICAgIHRoaXMucmVuZGVyKHBhZ2VJbmRleCk7XG4gICAgICAgICAgICB0aGlzLl9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkoXG4gICAgICAgICAgICAgICAgcGFnZUluZGV4LFxuICAgICAgICAgICAgICAgIHRhc2tMaXN0TW9kZWwuZ2V0VGFza3NCeVN0YXRlcyhbJ2luLXByb2dyZXNzJywgJ3F1ZXVlZC1mb3ItYXNzaWdubWVudCcsICdxdWV1ZWQnXSkuc2xpY2UoMCwgMTApXG4gICAgICAgICAgICApO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHJlcXVlc3RJZCA9PT0gJ3JldHJpZXZlVGFza1NldCcpIHtcbiAgICAgICAgICAgIGxldCB1cGRhdGVkVGFza0xpc3RNb2RlbCA9IG5ldyBUYXNrTGlzdE1vZGVsKHRoaXMuX2NyZWF0ZVRhc2tMaXN0RWxlbWVudEZyb21IdG1sKHJlc3BvbnNlKSk7XG4gICAgICAgICAgICBsZXQgcGFnZUluZGV4ID0gdXBkYXRlZFRhc2tMaXN0TW9kZWwuZ2V0UGFnZUluZGV4KCk7XG4gICAgICAgICAgICBsZXQgdGFza0xpc3RNb2RlbCA9IHRoaXMudGFza0xpc3RNb2RlbHNbcGFnZUluZGV4XTtcblxuICAgICAgICAgICAgdGFza0xpc3RNb2RlbC51cGRhdGVGcm9tVGFza0xpc3QodXBkYXRlZFRhc2tMaXN0TW9kZWwpO1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUYXNrU2V0V2l0aERlbGF5KFxuICAgICAgICAgICAgICAgIHBhZ2VJbmRleCxcbiAgICAgICAgICAgICAgICB0YXNrTGlzdE1vZGVsLmdldFRhc2tzQnlTdGF0ZXMoWydpbi1wcm9ncmVzcycsICdxdWV1ZWQtZm9yLWFzc2lnbm1lbnQnLCAncXVldWVkJ10pLnNsaWNlKDAsIDEwKVxuICAgICAgICAgICAgKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBfcmVxdWVzdFRhc2tJZHMgKCkge1xuICAgICAgICBIdHRwQ2xpZW50LmdldEpzb24odGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLWlkcy11cmwnKSwgdGhpcy5lbGVtZW50LCAncmVxdWVzdFRhc2tJZHMnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqL1xuICAgIHJlbmRlciAocGFnZUluZGV4KSB7XG4gICAgICAgIHRoaXMuYnVzeUljb24uZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdoaWRkZW4nKTtcblxuICAgICAgICBsZXQgaGFzVGFza0xpc3RFbGVtZW50Rm9yUGFnZSA9IE9iamVjdC5rZXlzKHRoaXMudGFza0xpc3RNb2RlbHMpLmluY2x1ZGVzKHBhZ2VJbmRleC50b1N0cmluZygxMCkpO1xuICAgICAgICBpZiAoIWhhc1Rhc2tMaXN0RWxlbWVudEZvclBhZ2UpIHtcbiAgICAgICAgICAgIHRoaXMuX3JldHJpZXZlVGFza1BhZ2UocGFnZUluZGV4KTtcblxuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgbGV0IHRhc2tMaXN0RWxlbWVudCA9IHRoaXMudGFza0xpc3RNb2RlbHNbcGFnZUluZGV4XTtcblxuICAgICAgICBpZiAocGFnZUluZGV4ID09PSB0aGlzLmN1cnJlbnRQYWdlSW5kZXgpIHtcbiAgICAgICAgICAgIGxldCByZW5kZXJlZFRhc2tMaXN0RWxlbWVudCA9IG5ldyBUYXNrTGlzdE1vZGVsKHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcudGFzay1saXN0JykpO1xuXG4gICAgICAgICAgICBpZiAocmVuZGVyZWRUYXNrTGlzdEVsZW1lbnQuaGFzUGFnZUluZGV4KCkpIHtcbiAgICAgICAgICAgICAgICBsZXQgY3VycmVudFBhZ2VMaXN0RWxlbWVudCA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcudGFzay1saXN0Jyk7XG4gICAgICAgICAgICAgICAgbGV0IHNlbGVjdGVkUGFnZUxpc3RFbGVtZW50ID0gdGhpcy50YXNrTGlzdE1vZGVsc1t0aGlzLmN1cnJlbnRQYWdlSW5kZXhdLmVsZW1lbnQ7XG5cbiAgICAgICAgICAgICAgICBjdXJyZW50UGFnZUxpc3RFbGVtZW50LnBhcmVudE5vZGUucmVwbGFjZUNoaWxkKHNlbGVjdGVkUGFnZUxpc3RFbGVtZW50LCBjdXJyZW50UGFnZUxpc3RFbGVtZW50KTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZENoaWxkKHRhc2tMaXN0RWxlbWVudC5lbGVtZW50KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuYnVzeUljb24uZWxlbWVudC5jbGFzc0xpc3QuYWRkKCdoaWRkZW4nKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX3JldHJpZXZlVGFza1BhZ2UgKHBhZ2VJbmRleCkge1xuICAgICAgICBsZXQgdGFza0lkcyA9IHRoaXMudGFza0lkTGlzdC5nZXRGb3JQYWdlKHBhZ2VJbmRleCk7XG4gICAgICAgIGxldCBwb3N0RGF0YSA9ICdwYWdlSW5kZXg9JyArIHBhZ2VJbmRleCArICcmdGFza0lkc1tdPScgKyB0YXNrSWRzLmpvaW4oJyZ0YXNrSWRzW109Jyk7XG5cbiAgICAgICAgSHR0cENsaWVudC5wb3N0KFxuICAgICAgICAgICAgdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrbGlzdC11cmwnKSxcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudCxcbiAgICAgICAgICAgICdyZXRyaWV2ZVRhc2tQYWdlJyxcbiAgICAgICAgICAgIHBvc3REYXRhXG4gICAgICAgICk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBwYWdlSW5kZXhcbiAgICAgKiBAcGFyYW0ge1Rhc2tbXX0gdGFza3NcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9yZXRyaWV2ZVRhc2tTZXRXaXRoRGVsYXkgKHBhZ2VJbmRleCwgdGFza3MpIHtcbiAgICAgICAgd2luZG93LnNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgdGhpcy5fcmV0cmlldmVUYXNrU2V0KHBhZ2VJbmRleCwgdGFza3MpO1xuICAgICAgICB9LCAxMDAwKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHBhZ2VJbmRleFxuICAgICAqIEBwYXJhbSB7VGFza1tdfSB0YXNrc1xuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX3JldHJpZXZlVGFza1NldCAocGFnZUluZGV4LCB0YXNrcykge1xuICAgICAgICBpZiAodGhpcy5jdXJyZW50UGFnZUluZGV4ID09PSBwYWdlSW5kZXggJiYgdGFza3MubGVuZ3RoKSB7XG4gICAgICAgICAgICBsZXQgdGFza0lkcyA9IFtdO1xuXG4gICAgICAgICAgICB0YXNrcy5mb3JFYWNoKGZ1bmN0aW9uICh0YXNrKSB7XG4gICAgICAgICAgICAgICAgdGFza0lkcy5wdXNoKHRhc2suZ2V0SWQoKSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbGV0IHBvc3REYXRhID0gJ3BhZ2VJbmRleD0nICsgcGFnZUluZGV4ICsgJyZ0YXNrSWRzW109JyArIHRhc2tJZHMuam9pbignJnRhc2tJZHNbXT0nKTtcblxuICAgICAgICAgICAgSHR0cENsaWVudC5wb3N0KFxuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGFza2xpc3QtdXJsJyksXG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LFxuICAgICAgICAgICAgICAgICdyZXRyaWV2ZVRhc2tTZXQnLFxuICAgICAgICAgICAgICAgIHBvc3REYXRhXG4gICAgICAgICAgICApO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSBodG1sXG4gICAgICogQHJldHVybnMge0VsZW1lbnR9XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlVGFza0xpc3RFbGVtZW50RnJvbUh0bWwgKGh0bWwpIHtcbiAgICAgICAgbGV0IGNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5vd25lckRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gaHRtbDtcblxuICAgICAgICByZXR1cm4gY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy50YXNrLWxpc3QnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHJldHVybnMge0ljb259XG4gICAgICogQHByaXZhdGVcbiAgICAgKi9cbiAgICBfY3JlYXRlQnVzeUljb24gKCkge1xuICAgICAgICBsZXQgY29udGFpbmVyID0gdGhpcy5lbGVtZW50Lm93bmVyRG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSAnPGkgY2xhc3M9XCJmYVwiPjwvaT4nO1xuXG4gICAgICAgIGxldCBpY29uID0gbmV3IEljb24oY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5mYScpKTtcbiAgICAgICAgaWNvbi5zZXRCdXN5KCk7XG5cbiAgICAgICAgcmV0dXJuIGljb247XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IFRhc2tMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Rlc3QtcHJvZ3Jlc3MvdGFzay1saXN0LmpzIiwibGV0IEJ5UGFnZUxpc3QgPSByZXF1aXJlKCcuLi90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdCcpO1xuXG5jbGFzcyBCeUVycm9yTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5ieVBhZ2VMaXN0cyA9IFtdO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuYnktcGFnZS1jb250YWluZXInKSwgKGJ5UGFnZUNvbnRhaW5lckVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgIGlmIChieVBhZ2VDb250YWluZXJFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5qcy1zb3J0YWJsZS1pdGVtJykubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgICAgIHRoaXMuYnlQYWdlTGlzdHMucHVzaChuZXcgQnlQYWdlTGlzdChieVBhZ2VDb250YWluZXJFbGVtZW50KSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5ieVBhZ2VMaXN0cy5mb3JFYWNoKChieVBhZ2VMaXN0KSA9PiB7XG4gICAgICAgICAgICBieVBhZ2VMaXN0LmluaXQoKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBCeUVycm9yTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LWVycm9yLWxpc3QuanMiLCJsZXQgU29ydCA9IHJlcXVpcmUoJy4vc29ydCcpO1xuXG5jbGFzcyBCeVBhZ2VMaXN0IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnNvcnQgPSBuZXcgU29ydChlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5zb3J0JyksIGVsZW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLXNvcnRhYmxlLWl0ZW0nKSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLnNvcnQuaW5pdCgpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gQnlQYWdlTGlzdDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL2J5LXBhZ2UtbGlzdC5qcyIsImxldCBTb3J0Q29udHJvbCA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvc29ydC1jb250cm9sJyk7XG5sZXQgU29ydGFibGVJdGVtID0gcmVxdWlyZSgnLi4vbW9kZWwvZWxlbWVudC9zb3J0YWJsZS1pdGVtJyk7XG5sZXQgU29ydGFibGVJdGVtTGlzdCA9IHJlcXVpcmUoJy4uL21vZGVsL3NvcnRhYmxlLWl0ZW0tbGlzdCcpO1xubGV0IFNvcnRDb250cm9sQ29sbGVjdGlvbiA9IHJlcXVpcmUoJy4uL21vZGVsL3NvcnQtY29udHJvbC1jb2xsZWN0aW9uJyk7XG5cbmNsYXNzIFNvcnQge1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7Tm9kZUxpc3R9IHNvcnRhYmxlSXRlbXNOb2RlTGlzdFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzb3J0YWJsZUl0ZW1zTm9kZUxpc3QpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5zb3J0Q29udHJvbENvbGxlY3Rpb24gPSB0aGlzLl9jcmVhdGVTb3J0YWJsZUNvbnRyb2xDb2xsZWN0aW9uKCk7XG4gICAgICAgIHRoaXMuc29ydGFibGVJdGVtc05vZGVMaXN0ID0gc29ydGFibGVJdGVtc05vZGVMaXN0O1xuICAgICAgICB0aGlzLnNvcnRhYmxlSXRlbXNMaXN0ID0gdGhpcy5fY3JlYXRlU29ydGFibGVJdGVtTGlzdCgpO1xuICAgIH07XG5cbiAgICBpbml0ICgpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ2ludmlzaWJsZScpO1xuICAgICAgICB0aGlzLnNvcnRDb250cm9sQ29sbGVjdGlvbi5jb250cm9scy5mb3JFYWNoKChjb250cm9sKSA9PiB7XG4gICAgICAgICAgICBjb250cm9sLmluaXQoKTtcbiAgICAgICAgICAgIGNvbnRyb2wuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFNvcnRDb250cm9sLmdldFNvcnRSZXF1ZXN0ZWRFdmVudE5hbWUoKSwgdGhpcy5fc29ydENvbnRyb2xDbGlja0V2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7U29ydGFibGVJdGVtTGlzdH1cbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9jcmVhdGVTb3J0YWJsZUl0ZW1MaXN0ICgpIHtcbiAgICAgICAgbGV0IHNvcnRhYmxlSXRlbXMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QsIChzb3J0YWJsZUl0ZW1FbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBzb3J0YWJsZUl0ZW1zLnB1c2gobmV3IFNvcnRhYmxlSXRlbShzb3J0YWJsZUl0ZW1FbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBuZXcgU29ydGFibGVJdGVtTGlzdChzb3J0YWJsZUl0ZW1zKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBAcmV0dXJucyB7U29ydENvbnRyb2xDb2xsZWN0aW9ufVxuICAgICAqIEBwcml2YXRlXG4gICAgICovXG4gICAgX2NyZWF0ZVNvcnRhYmxlQ29udHJvbENvbGxlY3Rpb24gKCkge1xuICAgICAgICBsZXQgY29udHJvbHMgPSBbXTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5zb3J0LWNvbnRyb2wnKSwgKHNvcnRDb250cm9sRWxlbWVudCkgPT4ge1xuICAgICAgICAgICAgY29udHJvbHMucHVzaChuZXcgU29ydENvbnRyb2woc29ydENvbnRyb2xFbGVtZW50KSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBuZXcgU29ydENvbnRyb2xDb2xsZWN0aW9uKGNvbnRyb2xzKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtDdXN0b21FdmVudH0gZXZlbnRcbiAgICAgKiBAcHJpdmF0ZVxuICAgICAqL1xuICAgIF9zb3J0Q29udHJvbENsaWNrRXZlbnRMaXN0ZW5lciAoZXZlbnQpIHtcbiAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMuc29ydGFibGVJdGVtc05vZGVMaXN0Lml0ZW0oMCkucGFyZW50RWxlbWVudDtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5zb3J0YWJsZUl0ZW1zTm9kZUxpc3QsIChzb3J0YWJsZUl0ZW1FbGVtZW50KSA9PiB7XG4gICAgICAgICAgICBzb3J0YWJsZUl0ZW1FbGVtZW50LnBhcmVudEVsZW1lbnQucmVtb3ZlQ2hpbGQoc29ydGFibGVJdGVtRWxlbWVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGxldCBzb3J0ZWRJdGVtcyA9IHRoaXMuc29ydGFibGVJdGVtc0xpc3Quc29ydChldmVudC5kZXRhaWwua2V5cyk7XG5cbiAgICAgICAgc29ydGVkSXRlbXMuZm9yRWFjaCgoc29ydGFibGVJdGVtKSA9PiB7XG4gICAgICAgICAgICBwYXJlbnQuaW5zZXJ0QWRqYWNlbnRFbGVtZW50KCdiZWZvcmVlbmQnLCBzb3J0YWJsZUl0ZW0uZWxlbWVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuc29ydENvbnRyb2xDb2xsZWN0aW9uLnNldFNvcnRlZChldmVudC50YXJnZXQpO1xuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU29ydDtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy90ZXN0LXJlc3VsdHMtYnktdGFzay10eXBlL3NvcnQuanMiLCJsZXQgTW9kYWwgPSByZXF1aXJlKCdib290c3RyYXAubmF0aXZlJykuTW9kYWw7XG5cbi8qKlxuICogQHBhcmFtIHtOb2RlTGlzdH0gdGFza1R5cGVDb250YWluZXJzXG4gKi9cbm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24gKHRhc2tUeXBlQ29udGFpbmVycykge1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGFza1R5cGVDb250YWluZXJzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGxldCB1bmF2YWlsYWJsZVRhc2tUeXBlID0gdGFza1R5cGVDb250YWluZXJzW2ldO1xuICAgICAgICBsZXQgdGFza1R5cGVLZXkgPSB1bmF2YWlsYWJsZVRhc2tUeXBlLmdldEF0dHJpYnV0ZSgnZGF0YS10YXNrLXR5cGUnKTtcbiAgICAgICAgbGV0IG1vZGFsSWQgPSB0YXNrVHlwZUtleSArICctYWNjb3VudC1yZXF1aXJlZC1tb2RhbCc7XG4gICAgICAgIGxldCBtb2RhbEVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChtb2RhbElkKTtcbiAgICAgICAgbGV0IG1vZGFsID0gbmV3IE1vZGFsKG1vZGFsRWxlbWVudCk7XG5cbiAgICAgICAgdW5hdmFpbGFibGVUYXNrVHlwZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG1vZGFsLnNob3coKTtcbiAgICAgICAgfSk7XG4gICAgfVxufTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91bmF2YWlsYWJsZS10YXNrLXR5cGUtbW9kYWwtbGF1bmNoZXIuanMiLCJjbGFzcyBGb3JtVmFsaWRhdG9yIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge1N0cmlwZX0gc3RyaXBlSnNcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoc3RyaXBlSnMpIHtcbiAgICAgICAgdGhpcy5zdHJpcGVKcyA9IHN0cmlwZUpzO1xuICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IG51bGw7XG4gICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJyc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBkYXRhXG4gICAgICogQHJldHVybnMge2Jvb2xlYW59XG4gICAgICovXG4gICAgdmFsaWRhdGUgKGRhdGEpIHtcbiAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSBudWxsO1xuXG4gICAgICAgIE9iamVjdC5lbnRyaWVzKGRhdGEpLmZvckVhY2goKFtrZXksIHZhbHVlXSkgPT4ge1xuICAgICAgICAgICAgaWYgKCF0aGlzLmludmFsaWRGaWVsZCkge1xuICAgICAgICAgICAgICAgIGxldCBjb21wYXJhdG9yVmFsdWUgPSB2YWx1ZS50cmltKCk7XG5cbiAgICAgICAgICAgICAgICBpZiAoY29tcGFyYXRvclZhbHVlID09PSAnJykge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9IGtleTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnZW1wdHknO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgaWYgKHRoaXMuaW52YWxpZEZpZWxkKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXMuc3RyaXBlSnMuY2FyZC52YWxpZGF0ZUNhcmROdW1iZXIoZGF0YS5udW1iZXIpKSB7XG4gICAgICAgICAgICB0aGlzLmludmFsaWRGaWVsZCA9ICdudW1iZXInO1xuICAgICAgICAgICAgdGhpcy5lcnJvck1lc3NhZ2UgPSAnaW52YWxpZCc7XG5cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICghdGhpcy5zdHJpcGVKcy5jYXJkLnZhbGlkYXRlRXhwaXJ5KGRhdGEuZXhwX21vbnRoLCBkYXRhLmV4cF95ZWFyKSkge1xuICAgICAgICAgICAgdGhpcy5pbnZhbGlkRmllbGQgPSAnZXhwX21vbnRoJztcbiAgICAgICAgICAgIHRoaXMuZXJyb3JNZXNzYWdlID0gJ2ludmFsaWQnO1xuXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXMuc3RyaXBlSnMuY2FyZC52YWxpZGF0ZUNWQyhkYXRhLmN2YykpIHtcbiAgICAgICAgICAgIHRoaXMuaW52YWxpZEZpZWxkID0gJ2N2Yyc7XG4gICAgICAgICAgICB0aGlzLmVycm9yTWVzc2FnZSA9ICdpbnZhbGlkJztcblxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtVmFsaWRhdG9yO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0tdmFsaWRhdG9yLmpzIiwibGV0IGZvcm1GaWVsZEZvY3VzZXIgPSByZXF1aXJlKCcuLi9mb3JtLWZpZWxkLWZvY3VzZXInKTtcbmxldCBBbGVydEZhY3RvcnkgPSByZXF1aXJlKCcuLi9zZXJ2aWNlcy9hbGVydC1mYWN0b3J5Jyk7XG5sZXQgRm9ybUJ1dHRvbiA9IHJlcXVpcmUoJy4uL21vZGVsL2VsZW1lbnQvZm9ybS1idXR0b24nKTtcblxuY2xhc3MgRm9ybSB7XG4gICAgY29uc3RydWN0b3IgKGVsZW1lbnQsIHZhbGlkYXRvcikge1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gZWxlbWVudC5vd25lckRvY3VtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbGVtZW50O1xuICAgICAgICB0aGlzLnZhbGlkYXRvciA9IHZhbGlkYXRvcjtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24gPSBuZXcgRm9ybUJ1dHRvbihlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ2J1dHRvblt0eXBlPXN1Ym1pdF0nKSk7XG4gICAgfTtcblxuICAgIGluaXQgKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgdGhpcy5fc3VibWl0RXZlbnRMaXN0ZW5lci5iaW5kKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgZ2V0U3RyaXBlUHVibGlzaGFibGVLZXkgKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1zdHJpcGUtcHVibGlzaGFibGUta2V5Jyk7XG4gICAgfTtcblxuICAgIGdldFVwZGF0ZUNhcmRFdmVudE5hbWUgKCkge1xuICAgICAgICByZXR1cm4gJ3VzZXIuYWNjb3VudC5jYXJkLnVwZGF0ZSc7XG4gICAgfVxuXG4gICAgZGlzYWJsZSAoKSB7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9uLmRpc2FibGUoKTtcbiAgICB9O1xuXG4gICAgZW5hYmxlICgpIHtcbiAgICAgICAgdGhpcy5zdWJtaXRCdXR0b24ubWFya0FzQXZhaWxhYmxlKCk7XG4gICAgICAgIHRoaXMuc3VibWl0QnV0dG9uLmVuYWJsZSgpO1xuICAgIH07XG5cbiAgICBfZ2V0RGF0YSAoKSB7XG4gICAgICAgIGNvbnN0IGRhdGEgPSB7fTtcblxuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tkYXRhLXN0cmlwZV0nKSwgZnVuY3Rpb24gKGRhdGFFbGVtZW50KSB7XG4gICAgICAgICAgICBsZXQgZmllbGRLZXkgPSBkYXRhRWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3RyaXBlJyk7XG5cbiAgICAgICAgICAgIGRhdGFbZmllbGRLZXldID0gZGF0YUVsZW1lbnQudmFsdWU7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBkYXRhO1xuICAgIH1cblxuICAgIF9zdWJtaXRFdmVudExpc3RlbmVyIChldmVudCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICB0aGlzLl9yZW1vdmVFcnJvckFsZXJ0cygpO1xuICAgICAgICB0aGlzLmRpc2FibGUoKTtcblxuICAgICAgICBsZXQgZGF0YSA9IHRoaXMuX2dldERhdGEoKTtcbiAgICAgICAgbGV0IGlzVmFsaWQgPSB0aGlzLnZhbGlkYXRvci52YWxpZGF0ZShkYXRhKTtcblxuICAgICAgICBpZiAoIWlzVmFsaWQpIHtcbiAgICAgICAgICAgIHRoaXMuaGFuZGxlUmVzcG9uc2VFcnJvcih0aGlzLmNyZWF0ZVJlc3BvbnNlRXJyb3IodGhpcy52YWxpZGF0b3IuaW52YWxpZEZpZWxkLCB0aGlzLnZhbGlkYXRvci5lcnJvck1lc3NhZ2UpKTtcbiAgICAgICAgICAgIHRoaXMuZW5hYmxlKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsZXQgZXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQodGhpcy5nZXRVcGRhdGVDYXJkRXZlbnROYW1lKCksIHtcbiAgICAgICAgICAgICAgICBkZXRhaWw6IGRhdGFcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuZGlzcGF0Y2hFdmVudChldmVudCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgX3JlbW92ZUVycm9yQWxlcnRzICgpIHtcbiAgICAgICAgbGV0IGVycm9yQWxlcnRzID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hbGVydCcpO1xuXG4gICAgICAgIFtdLmZvckVhY2guY2FsbChlcnJvckFsZXJ0cywgZnVuY3Rpb24gKGVycm9yQWxlcnQpIHtcbiAgICAgICAgICAgIGVycm9yQWxlcnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChlcnJvckFsZXJ0KTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIF9kaXNwbGF5RmllbGRFcnJvciAoZmllbGQsIGVycm9yKSB7XG4gICAgICAgIGxldCBhbGVydCA9IEFsZXJ0RmFjdG9yeS5jcmVhdGVGcm9tQ29udGVudCh0aGlzLmRvY3VtZW50LCAnPHA+JyArIGVycm9yLm1lc3NhZ2UgKyAnPC9wPicsIGZpZWxkLmdldEF0dHJpYnV0ZSgnaWQnKSk7XG4gICAgICAgIGxldCBlcnJvckNvbnRhaW5lciA9IHRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS1mb3I9JyArIGZpZWxkLmdldEF0dHJpYnV0ZSgnaWQnKSArICddJyk7XG5cbiAgICAgICAgaWYgKCFlcnJvckNvbnRhaW5lcikge1xuICAgICAgICAgICAgZXJyb3JDb250YWluZXIgPSB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtZm9yKj0nICsgZmllbGQuZ2V0QXR0cmlidXRlKCdpZCcpICsgJ10nKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGVycm9yQ29udGFpbmVyLmFwcGVuZChhbGVydC5lbGVtZW50KTtcbiAgICB9O1xuXG4gICAgaGFuZGxlUmVzcG9uc2VFcnJvciAoZXJyb3IpIHtcbiAgICAgICAgbGV0IGZpZWxkID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXN0cmlwZT0nICsgZXJyb3IucGFyYW0gKyAnXScpO1xuXG4gICAgICAgIGZvcm1GaWVsZEZvY3VzZXIoZmllbGQpO1xuICAgICAgICB0aGlzLl9kaXNwbGF5RmllbGRFcnJvcihmaWVsZCwgZXJyb3IpO1xuICAgIH07XG5cbiAgICBjcmVhdGVSZXNwb25zZUVycm9yIChmaWVsZCwgc3RhdGUpIHtcbiAgICAgICAgbGV0IGVycm9yTWVzc2FnZSA9ICcnO1xuXG4gICAgICAgIGlmIChzdGF0ZSA9PT0gJ2VtcHR5Jykge1xuICAgICAgICAgICAgZXJyb3JNZXNzYWdlID0gJ0hvbGQgb24sIHlvdSBjYW5cXCd0IGxlYXZlIHRoaXMgZW1wdHknO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHN0YXRlID09PSAnaW52YWxpZCcpIHtcbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnVGhlIGNhcmQgbnVtYmVyIGlzIG5vdCBxdWl0ZSByaWdodCc7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ2V4cF9tb250aCcpIHtcbiAgICAgICAgICAgICAgICBlcnJvck1lc3NhZ2UgPSAnQW4gZXhwaXJ5IGRhdGUgaW4gdGhlIGZ1dHVyZSBpcyBiZXR0ZXInO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoZmllbGQgPT09ICdjdmMnKSB7XG4gICAgICAgICAgICAgICAgZXJyb3JNZXNzYWdlID0gJ1RoZSBDVkMgc2hvdWxkIGJlIDMgb3IgNCBkaWdpdHMnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHBhcmFtOiBmaWVsZCxcbiAgICAgICAgICAgIG1lc3NhZ2U6IGVycm9yTWVzc2FnZVxuICAgICAgICB9O1xuICAgIH1cbn1cblxubW9kdWxlLmV4cG9ydHMgPSBGb3JtO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC1jYXJkL2Zvcm0uanMiLCJjb25zdCBTY3JvbGxUbyA9IHJlcXVpcmUoJy4uL3Njcm9sbC10bycpO1xuXG5jbGFzcyBOYXZCYXJBbmNob3Ige1xuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7RWxlbWVudH0gZWxlbWVudFxuICAgICAqIEBwYXJhbSB7bnVtYmVyfSBzY3JvbGxPZmZzZXRcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvciAoZWxlbWVudCwgc2Nyb2xsT2Zmc2V0KSB7XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IGVsZW1lbnQ7XG4gICAgICAgIHRoaXMuc2Nyb2xsT2Zmc2V0ID0gc2Nyb2xsT2Zmc2V0O1xuICAgICAgICB0aGlzLnRhcmdldElkID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKS5yZXBsYWNlKCcjJywgJycpO1xuXG4gICAgICAgIHRoaXMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRoaXMuaGFuZGxlQ2xpY2suYmluZCh0aGlzKSk7XG4gICAgfTtcblxuICAgIGhhbmRsZUNsaWNrIChldmVudCkge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICBsZXQgdGFyZ2V0ID0gdGhpcy5nZXRUYXJnZXQoKTtcblxuICAgICAgICBpZiAodGhpcy5lbGVtZW50LmNsYXNzTGlzdC5jb250YWlucygnanMtZmlyc3QnKSkge1xuICAgICAgICAgICAgU2Nyb2xsVG8uZ29Ubyh0YXJnZXQsIDApO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgU2Nyb2xsVG8uc2Nyb2xsVG8odGFyZ2V0LCB0aGlzLnNjcm9sbE9mZnNldCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBnZXRUYXJnZXQgKCkge1xuICAgICAgICByZXR1cm4gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy50YXJnZXRJZCk7XG4gICAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IE5hdkJhckFuY2hvcjtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWFuY2hvci5qcyIsImNvbnN0IE5hdkJhckFuY2hvciA9IHJlcXVpcmUoJy4vbmF2YmFyLWFuY2hvcicpO1xuXG5jbGFzcyBOYXZCYXJJdGVtIHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge0VsZW1lbnR9IGVsZW1lbnRcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gc2Nyb2xsT2Zmc2V0XG4gICAgICogQHBhcmFtIHtmdW5jdGlvbn0gTmF2QmFyTGlzdFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQsIE5hdkJhckxpc3QpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5hbmNob3IgPSBudWxsO1xuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBudWxsO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZWxlbWVudC5jaGlsZHJlbi5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgbGV0IGNoaWxkID0gZWxlbWVudC5jaGlsZHJlbi5pdGVtKGkpO1xuXG4gICAgICAgICAgICBpZiAoY2hpbGQubm9kZU5hbWUgPT09ICdBJyAmJiBjaGlsZC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKVswXSA9PT0gJyMnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5hbmNob3IgPSBuZXcgTmF2QmFyQW5jaG9yKGNoaWxkLCBzY3JvbGxPZmZzZXQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoY2hpbGQubm9kZU5hbWUgPT09ICdVTCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuZXcgTmF2QmFyTGlzdChjaGlsZCwgc2Nyb2xsT2Zmc2V0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBnZXRUYXJnZXRzICgpIHtcbiAgICAgICAgbGV0IHRhcmdldHMgPSBbXTtcblxuICAgICAgICBpZiAodGhpcy5hbmNob3IpIHtcbiAgICAgICAgICAgIHRhcmdldHMucHVzaCh0aGlzLmFuY2hvci5nZXRUYXJnZXQoKSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5uYXZCYXJMaXN0KSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3QuZ2V0VGFyZ2V0cygpLmZvckVhY2goZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICAgICAgICAgIHRhcmdldHMucHVzaCh0YXJnZXQpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGFyZ2V0cztcbiAgICB9XG5cbiAgICBjb250YWluc1RhcmdldElkICh0YXJnZXRJZCkge1xuICAgICAgICBpZiAodGhpcy5hbmNob3IgJiYgdGhpcy5hbmNob3IudGFyZ2V0SWQgPT09IHRhcmdldElkKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLm5hdkJhckxpc3QuY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfTtcblxuICAgIHNldEFjdGl2ZSAodGFyZ2V0SWQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xuXG4gICAgICAgIGlmICh0aGlzLm5hdkJhckxpc3QgJiYgdGhpcy5uYXZCYXJMaXN0LmNvbnRhaW5zVGFyZ2V0SWQodGFyZ2V0SWQpKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3Quc2V0QWN0aXZlKHRhcmdldElkKTtcbiAgICAgICAgfVxuICAgIH07XG59XG5cbm1vZHVsZS5leHBvcnRzID0gTmF2QmFySXRlbTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFjY291bnQvbmF2YmFyLWl0ZW0uanMiLCJsZXQgTmF2QmFySXRlbSA9IHJlcXVpcmUoJy4vbmF2YmFyLWl0ZW0nKTtcblxuY2xhc3MgTmF2QmFyTGlzdCB7XG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtFbGVtZW50fSBlbGVtZW50XG4gICAgICogQHBhcmFtIHtudW1iZXJ9IHNjcm9sbE9mZnNldFxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yIChlbGVtZW50LCBzY3JvbGxPZmZzZXQpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcyA9IFtdO1xuXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZWxlbWVudC5jaGlsZHJlbi5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5wdXNoKG5ldyBOYXZCYXJJdGVtKGVsZW1lbnQuY2hpbGRyZW4uaXRlbShpKSwgc2Nyb2xsT2Zmc2V0LCBOYXZCYXJMaXN0KSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgZ2V0VGFyZ2V0cyAoKSB7XG4gICAgICAgIGxldCB0YXJnZXRzID0gW107XG5cbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0aGlzLm5hdkJhckl0ZW1zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckl0ZW1zW2ldLmdldFRhcmdldHMoKS5mb3JFYWNoKGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICB0YXJnZXRzLnB1c2godGFyZ2V0KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRhcmdldHM7XG4gICAgfTtcblxuICAgIGNvbnRhaW5zVGFyZ2V0SWQgKHRhcmdldElkKSB7XG4gICAgICAgIGxldCBjb250YWlucyA9IGZhbHNlO1xuXG4gICAgICAgIHRoaXMubmF2QmFySXRlbXMuZm9yRWFjaChmdW5jdGlvbiAobmF2QmFySXRlbSkge1xuICAgICAgICAgICAgaWYgKG5hdkJhckl0ZW0uY29udGFpbnNUYXJnZXRJZCh0YXJnZXRJZCkpIHtcbiAgICAgICAgICAgICAgICBjb250YWlucyA9IHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBjb250YWlucztcbiAgICB9O1xuXG4gICAgY2xlYXJBY3RpdmUgKCkge1xuICAgICAgICBbXS5mb3JFYWNoLmNhbGwodGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpJyksIGZ1bmN0aW9uIChsaXN0SXRlbUVsZW1lbnQpIHtcbiAgICAgICAgICAgIGxpc3RJdGVtRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHNldEFjdGl2ZSAodGFyZ2V0SWQpIHtcbiAgICAgICAgdGhpcy5uYXZCYXJJdGVtcy5mb3JFYWNoKGZ1bmN0aW9uIChuYXZCYXJJdGVtKSB7XG4gICAgICAgICAgICBpZiAobmF2QmFySXRlbS5jb250YWluc1RhcmdldElkKHRhcmdldElkKSkge1xuICAgICAgICAgICAgICAgIG5hdkJhckl0ZW0uc2V0QWN0aXZlKHRhcmdldElkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSBOYXZCYXJMaXN0O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9uYXZiYXItbGlzdC5qcyIsInJlcXVpcmUoJy4vbmF2YmFyLWxpc3QnKTtcblxuY2xhc3MgU2Nyb2xsU3B5IHtcbiAgICAvKipcbiAgICAgKiBAcGFyYW0ge05hdkJhckxpc3R9IG5hdkJhckxpc3RcbiAgICAgKiBAcGFyYW0ge251bWJlcn0gb2Zmc2V0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IgKG5hdkJhckxpc3QsIG9mZnNldCkge1xuICAgICAgICB0aGlzLm5hdkJhckxpc3QgPSBuYXZCYXJMaXN0O1xuICAgICAgICB0aGlzLm9mZnNldCA9IG9mZnNldDtcbiAgICB9XG5cbiAgICBzY3JvbGxFdmVudExpc3RlbmVyICgpIHtcbiAgICAgICAgbGV0IGFjdGl2ZUxpbmtUYXJnZXQgPSBudWxsO1xuICAgICAgICBsZXQgbGlua1RhcmdldHMgPSB0aGlzLm5hdkJhckxpc3QuZ2V0VGFyZ2V0cygpO1xuICAgICAgICBsZXQgb2Zmc2V0ID0gdGhpcy5vZmZzZXQ7XG4gICAgICAgIGxldCBsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQgPSBbXTtcblxuICAgICAgICBsaW5rVGFyZ2V0cy5mb3JFYWNoKGZ1bmN0aW9uIChsaW5rVGFyZ2V0KSB7XG4gICAgICAgICAgICBpZiAobGlua1RhcmdldCkge1xuICAgICAgICAgICAgICAgIGxldCBvZmZzZXRUb3AgPSBsaW5rVGFyZ2V0LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcDtcblxuICAgICAgICAgICAgICAgIGlmIChvZmZzZXRUb3AgPCBvZmZzZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgbGlua1RhcmdldHNQYXN0VGhyZXNob2xkLnB1c2gobGlua1RhcmdldCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBpZiAobGlua1RhcmdldHNQYXN0VGhyZXNob2xkLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzWzBdO1xuICAgICAgICB9IGVsc2UgaWYgKGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZC5sZW5ndGggPT09IGxpbmtUYXJnZXRzLmxlbmd0aCkge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzW2xpbmtUYXJnZXRzLmxlbmd0aCAtIDFdO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgYWN0aXZlTGlua1RhcmdldCA9IGxpbmtUYXJnZXRzUGFzdFRocmVzaG9sZFtsaW5rVGFyZ2V0c1Bhc3RUaHJlc2hvbGQubGVuZ3RoIC0gMV07XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoYWN0aXZlTGlua1RhcmdldCkge1xuICAgICAgICAgICAgdGhpcy5uYXZCYXJMaXN0LmNsZWFyQWN0aXZlKCk7XG4gICAgICAgICAgICB0aGlzLm5hdkJhckxpc3Quc2V0QWN0aXZlKGFjdGl2ZUxpbmtUYXJnZXQuZ2V0QXR0cmlidXRlKCdpZCcpKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIHNweSAoKSB7XG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFxuICAgICAgICAgICAgJ3Njcm9sbCcsXG4gICAgICAgICAgICB0aGlzLnNjcm9sbEV2ZW50TGlzdGVuZXIuYmluZCh0aGlzKSxcbiAgICAgICAgICAgIHRydWVcbiAgICAgICAgKTtcbiAgICB9XG59XG5cbm1vZHVsZS5leHBvcnRzID0gU2Nyb2xsU3B5O1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWNjb3VudC9zY3JvbGxzcHkuanMiLCIvKipcbiAqIFNpbXBsZSwgbGlnaHR3ZWlnaHQsIHVzYWJsZSBsb2NhbCBhdXRvY29tcGxldGUgbGlicmFyeSBmb3IgbW9kZXJuIGJyb3dzZXJzXG4gKiBCZWNhdXNlIHRoZXJlIHdlcmVu4oCZdCBlbm91Z2ggYXV0b2NvbXBsZXRlIHNjcmlwdHMgaW4gdGhlIHdvcmxkPyBCZWNhdXNlIEnigJltIGNvbXBsZXRlbHkgaW5zYW5lIGFuZCBoYXZlIE5JSCBzeW5kcm9tZT8gUHJvYmFibHkgYm90aC4gOlBcbiAqIEBhdXRob3IgTGVhIFZlcm91IGh0dHA6Ly9sZWF2ZXJvdS5naXRodWIuaW8vYXdlc29tcGxldGVcbiAqIE1JVCBsaWNlbnNlXG4gKi9cblxuKGZ1bmN0aW9uICgpIHtcblxudmFyIF8gPSBmdW5jdGlvbiAoaW5wdXQsIG8pIHtcblx0dmFyIG1lID0gdGhpcztcblxuXHQvLyBTZXR1cFxuXG5cdHRoaXMuaXNPcGVuZWQgPSBmYWxzZTtcblxuXHR0aGlzLmlucHV0ID0gJChpbnB1dCk7XG5cdHRoaXMuaW5wdXQuc2V0QXR0cmlidXRlKFwiYXV0b2NvbXBsZXRlXCIsIFwib2ZmXCIpO1xuXHR0aGlzLmlucHV0LnNldEF0dHJpYnV0ZShcImFyaWEtYXV0b2NvbXBsZXRlXCIsIFwibGlzdFwiKTtcblxuXHRvID0gbyB8fCB7fTtcblxuXHRjb25maWd1cmUodGhpcywge1xuXHRcdG1pbkNoYXJzOiAyLFxuXHRcdG1heEl0ZW1zOiAxMCxcblx0XHRhdXRvRmlyc3Q6IGZhbHNlLFxuXHRcdGRhdGE6IF8uREFUQSxcblx0XHRmaWx0ZXI6IF8uRklMVEVSX0NPTlRBSU5TLFxuXHRcdHNvcnQ6IG8uc29ydCA9PT0gZmFsc2UgPyBmYWxzZSA6IF8uU09SVF9CWUxFTkdUSCxcblx0XHRpdGVtOiBfLklURU0sXG5cdFx0cmVwbGFjZTogXy5SRVBMQUNFXG5cdH0sIG8pO1xuXG5cdHRoaXMuaW5kZXggPSAtMTtcblxuXHQvLyBDcmVhdGUgbmVjZXNzYXJ5IGVsZW1lbnRzXG5cblx0dGhpcy5jb250YWluZXIgPSAkLmNyZWF0ZShcImRpdlwiLCB7XG5cdFx0Y2xhc3NOYW1lOiBcImF3ZXNvbXBsZXRlXCIsXG5cdFx0YXJvdW5kOiBpbnB1dFxuXHR9KTtcblxuXHR0aGlzLnVsID0gJC5jcmVhdGUoXCJ1bFwiLCB7XG5cdFx0aGlkZGVuOiBcImhpZGRlblwiLFxuXHRcdGluc2lkZTogdGhpcy5jb250YWluZXJcblx0fSk7XG5cblx0dGhpcy5zdGF0dXMgPSAkLmNyZWF0ZShcInNwYW5cIiwge1xuXHRcdGNsYXNzTmFtZTogXCJ2aXN1YWxseS1oaWRkZW5cIixcblx0XHRyb2xlOiBcInN0YXR1c1wiLFxuXHRcdFwiYXJpYS1saXZlXCI6IFwiYXNzZXJ0aXZlXCIsXG5cdFx0XCJhcmlhLXJlbGV2YW50XCI6IFwiYWRkaXRpb25zXCIsXG5cdFx0aW5zaWRlOiB0aGlzLmNvbnRhaW5lclxuXHR9KTtcblxuXHQvLyBCaW5kIGV2ZW50c1xuXG5cdHRoaXMuX2V2ZW50cyA9IHtcblx0XHRpbnB1dDoge1xuXHRcdFx0XCJpbnB1dFwiOiB0aGlzLmV2YWx1YXRlLmJpbmQodGhpcyksXG5cdFx0XHRcImJsdXJcIjogdGhpcy5jbG9zZS5iaW5kKHRoaXMsIHsgcmVhc29uOiBcImJsdXJcIiB9KSxcblx0XHRcdFwia2V5ZG93blwiOiBmdW5jdGlvbihldnQpIHtcblx0XHRcdFx0dmFyIGMgPSBldnQua2V5Q29kZTtcblxuXHRcdFx0XHQvLyBJZiB0aGUgZHJvcGRvd24gYHVsYCBpcyBpbiB2aWV3LCB0aGVuIGFjdCBvbiBrZXlkb3duIGZvciB0aGUgZm9sbG93aW5nIGtleXM6XG5cdFx0XHRcdC8vIEVudGVyIC8gRXNjIC8gVXAgLyBEb3duXG5cdFx0XHRcdGlmKG1lLm9wZW5lZCkge1xuXHRcdFx0XHRcdGlmIChjID09PSAxMyAmJiBtZS5zZWxlY3RlZCkgeyAvLyBFbnRlclxuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZS5zZWxlY3QoKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0ZWxzZSBpZiAoYyA9PT0gMjcpIHsgLy8gRXNjXG5cdFx0XHRcdFx0XHRtZS5jbG9zZSh7IHJlYXNvbjogXCJlc2NcIiB9KTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0ZWxzZSBpZiAoYyA9PT0gMzggfHwgYyA9PT0gNDApIHsgLy8gRG93bi9VcCBhcnJvd1xuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZVtjID09PSAzOD8gXCJwcmV2aW91c1wiIDogXCJuZXh0XCJdKCk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fSxcblx0XHRmb3JtOiB7XG5cdFx0XHRcInN1Ym1pdFwiOiB0aGlzLmNsb3NlLmJpbmQodGhpcywgeyByZWFzb246IFwic3VibWl0XCIgfSlcblx0XHR9LFxuXHRcdHVsOiB7XG5cdFx0XHRcIm1vdXNlZG93blwiOiBmdW5jdGlvbihldnQpIHtcblx0XHRcdFx0dmFyIGxpID0gZXZ0LnRhcmdldDtcblxuXHRcdFx0XHRpZiAobGkgIT09IHRoaXMpIHtcblxuXHRcdFx0XHRcdHdoaWxlIChsaSAmJiAhL2xpL2kudGVzdChsaS5ub2RlTmFtZSkpIHtcblx0XHRcdFx0XHRcdGxpID0gbGkucGFyZW50Tm9kZTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRpZiAobGkgJiYgZXZ0LmJ1dHRvbiA9PT0gMCkgeyAgLy8gT25seSBzZWxlY3Qgb24gbGVmdCBjbGlja1xuXHRcdFx0XHRcdFx0ZXZ0LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRtZS5zZWxlY3QobGksIGV2dC50YXJnZXQpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH1cblx0fTtcblxuXHQkLmJpbmQodGhpcy5pbnB1dCwgdGhpcy5fZXZlbnRzLmlucHV0KTtcblx0JC5iaW5kKHRoaXMuaW5wdXQuZm9ybSwgdGhpcy5fZXZlbnRzLmZvcm0pO1xuXHQkLmJpbmQodGhpcy51bCwgdGhpcy5fZXZlbnRzLnVsKTtcblxuXHRpZiAodGhpcy5pbnB1dC5oYXNBdHRyaWJ1dGUoXCJsaXN0XCIpKSB7XG5cdFx0dGhpcy5saXN0ID0gXCIjXCIgKyB0aGlzLmlucHV0LmdldEF0dHJpYnV0ZShcImxpc3RcIik7XG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJsaXN0XCIpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdHRoaXMubGlzdCA9IHRoaXMuaW5wdXQuZ2V0QXR0cmlidXRlKFwiZGF0YS1saXN0XCIpIHx8IG8ubGlzdCB8fCBbXTtcblx0fVxuXG5cdF8uYWxsLnB1c2godGhpcyk7XG59O1xuXG5fLnByb3RvdHlwZSA9IHtcblx0c2V0IGxpc3QobGlzdCkge1xuXHRcdGlmIChBcnJheS5pc0FycmF5KGxpc3QpKSB7XG5cdFx0XHR0aGlzLl9saXN0ID0gbGlzdDtcblx0XHR9XG5cdFx0ZWxzZSBpZiAodHlwZW9mIGxpc3QgPT09IFwic3RyaW5nXCIgJiYgbGlzdC5pbmRleE9mKFwiLFwiKSA+IC0xKSB7XG5cdFx0XHRcdHRoaXMuX2xpc3QgPSBsaXN0LnNwbGl0KC9cXHMqLFxccyovKTtcblx0XHR9XG5cdFx0ZWxzZSB7IC8vIEVsZW1lbnQgb3IgQ1NTIHNlbGVjdG9yXG5cdFx0XHRsaXN0ID0gJChsaXN0KTtcblxuXHRcdFx0aWYgKGxpc3QgJiYgbGlzdC5jaGlsZHJlbikge1xuXHRcdFx0XHR2YXIgaXRlbXMgPSBbXTtcblx0XHRcdFx0c2xpY2UuYXBwbHkobGlzdC5jaGlsZHJlbikuZm9yRWFjaChmdW5jdGlvbiAoZWwpIHtcblx0XHRcdFx0XHRpZiAoIWVsLmRpc2FibGVkKSB7XG5cdFx0XHRcdFx0XHR2YXIgdGV4dCA9IGVsLnRleHRDb250ZW50LnRyaW0oKTtcblx0XHRcdFx0XHRcdHZhciB2YWx1ZSA9IGVsLnZhbHVlIHx8IHRleHQ7XG5cdFx0XHRcdFx0XHR2YXIgbGFiZWwgPSBlbC5sYWJlbCB8fCB0ZXh0O1xuXHRcdFx0XHRcdFx0aWYgKHZhbHVlICE9PSBcIlwiKSB7XG5cdFx0XHRcdFx0XHRcdGl0ZW1zLnB1c2goeyBsYWJlbDogbGFiZWwsIHZhbHVlOiB2YWx1ZSB9KTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0pO1xuXHRcdFx0XHR0aGlzLl9saXN0ID0gaXRlbXM7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0aWYgKGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQgPT09IHRoaXMuaW5wdXQpIHtcblx0XHRcdHRoaXMuZXZhbHVhdGUoKTtcblx0XHR9XG5cdH0sXG5cblx0Z2V0IHNlbGVjdGVkKCkge1xuXHRcdHJldHVybiB0aGlzLmluZGV4ID4gLTE7XG5cdH0sXG5cblx0Z2V0IG9wZW5lZCgpIHtcblx0XHRyZXR1cm4gdGhpcy5pc09wZW5lZDtcblx0fSxcblxuXHRjbG9zZTogZnVuY3Rpb24gKG8pIHtcblx0XHRpZiAoIXRoaXMub3BlbmVkKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0dGhpcy51bC5zZXRBdHRyaWJ1dGUoXCJoaWRkZW5cIiwgXCJcIik7XG5cdFx0dGhpcy5pc09wZW5lZCA9IGZhbHNlO1xuXHRcdHRoaXMuaW5kZXggPSAtMTtcblxuXHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLWNsb3NlXCIsIG8gfHwge30pO1xuXHR9LFxuXG5cdG9wZW46IGZ1bmN0aW9uICgpIHtcblx0XHR0aGlzLnVsLnJlbW92ZUF0dHJpYnV0ZShcImhpZGRlblwiKTtcblx0XHR0aGlzLmlzT3BlbmVkID0gdHJ1ZTtcblxuXHRcdGlmICh0aGlzLmF1dG9GaXJzdCAmJiB0aGlzLmluZGV4ID09PSAtMSkge1xuXHRcdFx0dGhpcy5nb3RvKDApO1xuXHRcdH1cblxuXHRcdCQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLW9wZW5cIik7XG5cdH0sXG5cblx0ZGVzdHJveTogZnVuY3Rpb24oKSB7XG5cdFx0Ly9yZW1vdmUgZXZlbnRzIGZyb20gdGhlIGlucHV0IGFuZCBpdHMgZm9ybVxuXHRcdCQudW5iaW5kKHRoaXMuaW5wdXQsIHRoaXMuX2V2ZW50cy5pbnB1dCk7XG5cdFx0JC51bmJpbmQodGhpcy5pbnB1dC5mb3JtLCB0aGlzLl9ldmVudHMuZm9ybSk7XG5cblx0XHQvL21vdmUgdGhlIGlucHV0IG91dCBvZiB0aGUgYXdlc29tcGxldGUgY29udGFpbmVyIGFuZCByZW1vdmUgdGhlIGNvbnRhaW5lciBhbmQgaXRzIGNoaWxkcmVuXG5cdFx0dmFyIHBhcmVudE5vZGUgPSB0aGlzLmNvbnRhaW5lci5wYXJlbnROb2RlO1xuXG5cdFx0cGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUodGhpcy5pbnB1dCwgdGhpcy5jb250YWluZXIpO1xuXHRcdHBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5jb250YWluZXIpO1xuXG5cdFx0Ly9yZW1vdmUgYXV0b2NvbXBsZXRlIGFuZCBhcmlhLWF1dG9jb21wbGV0ZSBhdHRyaWJ1dGVzXG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJhdXRvY29tcGxldGVcIik7XG5cdFx0dGhpcy5pbnB1dC5yZW1vdmVBdHRyaWJ1dGUoXCJhcmlhLWF1dG9jb21wbGV0ZVwiKTtcblxuXHRcdC8vcmVtb3ZlIHRoaXMgYXdlc29tZXBsZXRlIGluc3RhbmNlIGZyb20gdGhlIGdsb2JhbCBhcnJheSBvZiBpbnN0YW5jZXNcblx0XHR2YXIgaW5kZXhPZkF3ZXNvbXBsZXRlID0gXy5hbGwuaW5kZXhPZih0aGlzKTtcblxuXHRcdGlmIChpbmRleE9mQXdlc29tcGxldGUgIT09IC0xKSB7XG5cdFx0XHRfLmFsbC5zcGxpY2UoaW5kZXhPZkF3ZXNvbXBsZXRlLCAxKTtcblx0XHR9XG5cdH0sXG5cblx0bmV4dDogZnVuY3Rpb24gKCkge1xuXHRcdHZhciBjb3VudCA9IHRoaXMudWwuY2hpbGRyZW4ubGVuZ3RoO1xuXHRcdHRoaXMuZ290byh0aGlzLmluZGV4IDwgY291bnQgLSAxID8gdGhpcy5pbmRleCArIDEgOiAoY291bnQgPyAwIDogLTEpICk7XG5cdH0sXG5cblx0cHJldmlvdXM6IGZ1bmN0aW9uICgpIHtcblx0XHR2YXIgY291bnQgPSB0aGlzLnVsLmNoaWxkcmVuLmxlbmd0aDtcblx0XHR2YXIgcG9zID0gdGhpcy5pbmRleCAtIDE7XG5cblx0XHR0aGlzLmdvdG8odGhpcy5zZWxlY3RlZCAmJiBwb3MgIT09IC0xID8gcG9zIDogY291bnQgLSAxKTtcblx0fSxcblxuXHQvLyBTaG91bGQgbm90IGJlIHVzZWQsIGhpZ2hsaWdodHMgc3BlY2lmaWMgaXRlbSB3aXRob3V0IGFueSBjaGVja3MhXG5cdGdvdG86IGZ1bmN0aW9uIChpKSB7XG5cdFx0dmFyIGxpcyA9IHRoaXMudWwuY2hpbGRyZW47XG5cblx0XHRpZiAodGhpcy5zZWxlY3RlZCkge1xuXHRcdFx0bGlzW3RoaXMuaW5kZXhdLnNldEF0dHJpYnV0ZShcImFyaWEtc2VsZWN0ZWRcIiwgXCJmYWxzZVwiKTtcblx0XHR9XG5cblx0XHR0aGlzLmluZGV4ID0gaTtcblxuXHRcdGlmIChpID4gLTEgJiYgbGlzLmxlbmd0aCA+IDApIHtcblx0XHRcdGxpc1tpXS5zZXRBdHRyaWJ1dGUoXCJhcmlhLXNlbGVjdGVkXCIsIFwidHJ1ZVwiKTtcblx0XHRcdHRoaXMuc3RhdHVzLnRleHRDb250ZW50ID0gbGlzW2ldLnRleHRDb250ZW50O1xuXG5cdFx0XHQvLyBzY3JvbGwgdG8gaGlnaGxpZ2h0ZWQgZWxlbWVudCBpbiBjYXNlIHBhcmVudCdzIGhlaWdodCBpcyBmaXhlZFxuXHRcdFx0dGhpcy51bC5zY3JvbGxUb3AgPSBsaXNbaV0ub2Zmc2V0VG9wIC0gdGhpcy51bC5jbGllbnRIZWlnaHQgKyBsaXNbaV0uY2xpZW50SGVpZ2h0O1xuXG5cdFx0XHQkLmZpcmUodGhpcy5pbnB1dCwgXCJhd2Vzb21wbGV0ZS1oaWdobGlnaHRcIiwge1xuXHRcdFx0XHR0ZXh0OiB0aGlzLnN1Z2dlc3Rpb25zW3RoaXMuaW5kZXhdXG5cdFx0XHR9KTtcblx0XHR9XG5cdH0sXG5cblx0c2VsZWN0OiBmdW5jdGlvbiAoc2VsZWN0ZWQsIG9yaWdpbikge1xuXHRcdGlmIChzZWxlY3RlZCkge1xuXHRcdFx0dGhpcy5pbmRleCA9ICQuc2libGluZ0luZGV4KHNlbGVjdGVkKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0c2VsZWN0ZWQgPSB0aGlzLnVsLmNoaWxkcmVuW3RoaXMuaW5kZXhdO1xuXHRcdH1cblxuXHRcdGlmIChzZWxlY3RlZCkge1xuXHRcdFx0dmFyIHN1Z2dlc3Rpb24gPSB0aGlzLnN1Z2dlc3Rpb25zW3RoaXMuaW5kZXhdO1xuXG5cdFx0XHR2YXIgYWxsb3dlZCA9ICQuZmlyZSh0aGlzLmlucHV0LCBcImF3ZXNvbXBsZXRlLXNlbGVjdFwiLCB7XG5cdFx0XHRcdHRleHQ6IHN1Z2dlc3Rpb24sXG5cdFx0XHRcdG9yaWdpbjogb3JpZ2luIHx8IHNlbGVjdGVkXG5cdFx0XHR9KTtcblxuXHRcdFx0aWYgKGFsbG93ZWQpIHtcblx0XHRcdFx0dGhpcy5yZXBsYWNlKHN1Z2dlc3Rpb24pO1xuXHRcdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcInNlbGVjdFwiIH0pO1xuXHRcdFx0XHQkLmZpcmUodGhpcy5pbnB1dCwgXCJhd2Vzb21wbGV0ZS1zZWxlY3Rjb21wbGV0ZVwiLCB7XG5cdFx0XHRcdFx0dGV4dDogc3VnZ2VzdGlvblxuXHRcdFx0XHR9KTtcblx0XHRcdH1cblx0XHR9XG5cdH0sXG5cblx0ZXZhbHVhdGU6IGZ1bmN0aW9uKCkge1xuXHRcdHZhciBtZSA9IHRoaXM7XG5cdFx0dmFyIHZhbHVlID0gdGhpcy5pbnB1dC52YWx1ZTtcblxuXHRcdGlmICh2YWx1ZS5sZW5ndGggPj0gdGhpcy5taW5DaGFycyAmJiB0aGlzLl9saXN0Lmxlbmd0aCA+IDApIHtcblx0XHRcdHRoaXMuaW5kZXggPSAtMTtcblx0XHRcdC8vIFBvcHVsYXRlIGxpc3Qgd2l0aCBvcHRpb25zIHRoYXQgbWF0Y2hcblx0XHRcdHRoaXMudWwuaW5uZXJIVE1MID0gXCJcIjtcblxuXHRcdFx0dGhpcy5zdWdnZXN0aW9ucyA9IHRoaXMuX2xpc3Rcblx0XHRcdFx0Lm1hcChmdW5jdGlvbihpdGVtKSB7XG5cdFx0XHRcdFx0cmV0dXJuIG5ldyBTdWdnZXN0aW9uKG1lLmRhdGEoaXRlbSwgdmFsdWUpKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmZpbHRlcihmdW5jdGlvbihpdGVtKSB7XG5cdFx0XHRcdFx0cmV0dXJuIG1lLmZpbHRlcihpdGVtLCB2YWx1ZSk7XG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRpZiAodGhpcy5zb3J0ICE9PSBmYWxzZSkge1xuXHRcdFx0XHR0aGlzLnN1Z2dlc3Rpb25zID0gdGhpcy5zdWdnZXN0aW9ucy5zb3J0KHRoaXMuc29ydCk7XG5cdFx0XHR9XG5cblx0XHRcdHRoaXMuc3VnZ2VzdGlvbnMgPSB0aGlzLnN1Z2dlc3Rpb25zLnNsaWNlKDAsIHRoaXMubWF4SXRlbXMpO1xuXG5cdFx0XHR0aGlzLnN1Z2dlc3Rpb25zLmZvckVhY2goZnVuY3Rpb24odGV4dCkge1xuXHRcdFx0XHRcdG1lLnVsLmFwcGVuZENoaWxkKG1lLml0ZW0odGV4dCwgdmFsdWUpKTtcblx0XHRcdFx0fSk7XG5cblx0XHRcdGlmICh0aGlzLnVsLmNoaWxkcmVuLmxlbmd0aCA9PT0gMCkge1xuXHRcdFx0XHR0aGlzLmNsb3NlKHsgcmVhc29uOiBcIm5vbWF0Y2hlc1wiIH0pO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0dGhpcy5vcGVuKCk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0dGhpcy5jbG9zZSh7IHJlYXNvbjogXCJub21hdGNoZXNcIiB9KTtcblx0XHR9XG5cdH1cbn07XG5cbi8vIFN0YXRpYyBtZXRob2RzL3Byb3BlcnRpZXNcblxuXy5hbGwgPSBbXTtcblxuXy5GSUxURVJfQ09OVEFJTlMgPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0cmV0dXJuIFJlZ0V4cCgkLnJlZ0V4cEVzY2FwZShpbnB1dC50cmltKCkpLCBcImlcIikudGVzdCh0ZXh0KTtcbn07XG5cbl8uRklMVEVSX1NUQVJUU1dJVEggPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0cmV0dXJuIFJlZ0V4cChcIl5cIiArICQucmVnRXhwRXNjYXBlKGlucHV0LnRyaW0oKSksIFwiaVwiKS50ZXN0KHRleHQpO1xufTtcblxuXy5TT1JUX0JZTEVOR1RIID0gZnVuY3Rpb24gKGEsIGIpIHtcblx0aWYgKGEubGVuZ3RoICE9PSBiLmxlbmd0aCkge1xuXHRcdHJldHVybiBhLmxlbmd0aCAtIGIubGVuZ3RoO1xuXHR9XG5cblx0cmV0dXJuIGEgPCBiPyAtMSA6IDE7XG59O1xuXG5fLklURU0gPSBmdW5jdGlvbiAodGV4dCwgaW5wdXQpIHtcblx0dmFyIGh0bWwgPSBpbnB1dC50cmltKCkgPT09IFwiXCIgPyB0ZXh0IDogdGV4dC5yZXBsYWNlKFJlZ0V4cCgkLnJlZ0V4cEVzY2FwZShpbnB1dC50cmltKCkpLCBcImdpXCIpLCBcIjxtYXJrPiQmPC9tYXJrPlwiKTtcblx0cmV0dXJuICQuY3JlYXRlKFwibGlcIiwge1xuXHRcdGlubmVySFRNTDogaHRtbCxcblx0XHRcImFyaWEtc2VsZWN0ZWRcIjogXCJmYWxzZVwiXG5cdH0pO1xufTtcblxuXy5SRVBMQUNFID0gZnVuY3Rpb24gKHRleHQpIHtcblx0dGhpcy5pbnB1dC52YWx1ZSA9IHRleHQudmFsdWU7XG59O1xuXG5fLkRBVEEgPSBmdW5jdGlvbiAoaXRlbS8qLCBpbnB1dCovKSB7IHJldHVybiBpdGVtOyB9O1xuXG4vLyBQcml2YXRlIGZ1bmN0aW9uc1xuXG5mdW5jdGlvbiBTdWdnZXN0aW9uKGRhdGEpIHtcblx0dmFyIG8gPSBBcnJheS5pc0FycmF5KGRhdGEpXG5cdCAgPyB7IGxhYmVsOiBkYXRhWzBdLCB2YWx1ZTogZGF0YVsxXSB9XG5cdCAgOiB0eXBlb2YgZGF0YSA9PT0gXCJvYmplY3RcIiAmJiBcImxhYmVsXCIgaW4gZGF0YSAmJiBcInZhbHVlXCIgaW4gZGF0YSA/IGRhdGEgOiB7IGxhYmVsOiBkYXRhLCB2YWx1ZTogZGF0YSB9O1xuXG5cdHRoaXMubGFiZWwgPSBvLmxhYmVsIHx8IG8udmFsdWU7XG5cdHRoaXMudmFsdWUgPSBvLnZhbHVlO1xufVxuT2JqZWN0LmRlZmluZVByb3BlcnR5KFN1Z2dlc3Rpb24ucHJvdG90eXBlID0gT2JqZWN0LmNyZWF0ZShTdHJpbmcucHJvdG90eXBlKSwgXCJsZW5ndGhcIiwge1xuXHRnZXQ6IGZ1bmN0aW9uKCkgeyByZXR1cm4gdGhpcy5sYWJlbC5sZW5ndGg7IH1cbn0pO1xuU3VnZ2VzdGlvbi5wcm90b3R5cGUudG9TdHJpbmcgPSBTdWdnZXN0aW9uLnByb3RvdHlwZS52YWx1ZU9mID0gZnVuY3Rpb24gKCkge1xuXHRyZXR1cm4gXCJcIiArIHRoaXMubGFiZWw7XG59O1xuXG5mdW5jdGlvbiBjb25maWd1cmUoaW5zdGFuY2UsIHByb3BlcnRpZXMsIG8pIHtcblx0Zm9yICh2YXIgaSBpbiBwcm9wZXJ0aWVzKSB7XG5cdFx0dmFyIGluaXRpYWwgPSBwcm9wZXJ0aWVzW2ldLFxuXHRcdCAgICBhdHRyVmFsdWUgPSBpbnN0YW5jZS5pbnB1dC5nZXRBdHRyaWJ1dGUoXCJkYXRhLVwiICsgaS50b0xvd2VyQ2FzZSgpKTtcblxuXHRcdGlmICh0eXBlb2YgaW5pdGlhbCA9PT0gXCJudW1iZXJcIikge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBwYXJzZUludChhdHRyVmFsdWUpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpbml0aWFsID09PSBmYWxzZSkgeyAvLyBCb29sZWFuIG9wdGlvbnMgbXVzdCBiZSBmYWxzZSBieSBkZWZhdWx0IGFueXdheVxuXHRcdFx0aW5zdGFuY2VbaV0gPSBhdHRyVmFsdWUgIT09IG51bGw7XG5cdFx0fVxuXHRcdGVsc2UgaWYgKGluaXRpYWwgaW5zdGFuY2VvZiBGdW5jdGlvbikge1xuXHRcdFx0aW5zdGFuY2VbaV0gPSBudWxsO1xuXHRcdH1cblx0XHRlbHNlIHtcblx0XHRcdGluc3RhbmNlW2ldID0gYXR0clZhbHVlO1xuXHRcdH1cblxuXHRcdGlmICghaW5zdGFuY2VbaV0gJiYgaW5zdGFuY2VbaV0gIT09IDApIHtcblx0XHRcdGluc3RhbmNlW2ldID0gKGkgaW4gbyk/IG9baV0gOiBpbml0aWFsO1xuXHRcdH1cblx0fVxufVxuXG4vLyBIZWxwZXJzXG5cbnZhciBzbGljZSA9IEFycmF5LnByb3RvdHlwZS5zbGljZTtcblxuZnVuY3Rpb24gJChleHByLCBjb24pIHtcblx0cmV0dXJuIHR5cGVvZiBleHByID09PSBcInN0cmluZ1wiPyAoY29uIHx8IGRvY3VtZW50KS5xdWVyeVNlbGVjdG9yKGV4cHIpIDogZXhwciB8fCBudWxsO1xufVxuXG5mdW5jdGlvbiAkJChleHByLCBjb24pIHtcblx0cmV0dXJuIHNsaWNlLmNhbGwoKGNvbiB8fCBkb2N1bWVudCkucXVlcnlTZWxlY3RvckFsbChleHByKSk7XG59XG5cbiQuY3JlYXRlID0gZnVuY3Rpb24odGFnLCBvKSB7XG5cdHZhciBlbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCh0YWcpO1xuXG5cdGZvciAodmFyIGkgaW4gbykge1xuXHRcdHZhciB2YWwgPSBvW2ldO1xuXG5cdFx0aWYgKGkgPT09IFwiaW5zaWRlXCIpIHtcblx0XHRcdCQodmFsKS5hcHBlbmRDaGlsZChlbGVtZW50KTtcblx0XHR9XG5cdFx0ZWxzZSBpZiAoaSA9PT0gXCJhcm91bmRcIikge1xuXHRcdFx0dmFyIHJlZiA9ICQodmFsKTtcblx0XHRcdHJlZi5wYXJlbnROb2RlLmluc2VydEJlZm9yZShlbGVtZW50LCByZWYpO1xuXHRcdFx0ZWxlbWVudC5hcHBlbmRDaGlsZChyZWYpO1xuXHRcdH1cblx0XHRlbHNlIGlmIChpIGluIGVsZW1lbnQpIHtcblx0XHRcdGVsZW1lbnRbaV0gPSB2YWw7XG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0ZWxlbWVudC5zZXRBdHRyaWJ1dGUoaSwgdmFsKTtcblx0XHR9XG5cdH1cblxuXHRyZXR1cm4gZWxlbWVudDtcbn07XG5cbiQuYmluZCA9IGZ1bmN0aW9uKGVsZW1lbnQsIG8pIHtcblx0aWYgKGVsZW1lbnQpIHtcblx0XHRmb3IgKHZhciBldmVudCBpbiBvKSB7XG5cdFx0XHR2YXIgY2FsbGJhY2sgPSBvW2V2ZW50XTtcblxuXHRcdFx0ZXZlbnQuc3BsaXQoL1xccysvKS5mb3JFYWNoKGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0XHRlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoZXZlbnQsIGNhbGxiYWNrKTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxufTtcblxuJC51bmJpbmQgPSBmdW5jdGlvbihlbGVtZW50LCBvKSB7XG5cdGlmIChlbGVtZW50KSB7XG5cdFx0Zm9yICh2YXIgZXZlbnQgaW4gbykge1xuXHRcdFx0dmFyIGNhbGxiYWNrID0gb1tldmVudF07XG5cblx0XHRcdGV2ZW50LnNwbGl0KC9cXHMrLykuZm9yRWFjaChmdW5jdGlvbihldmVudCkge1xuXHRcdFx0XHRlbGVtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoZXZlbnQsIGNhbGxiYWNrKTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxufTtcblxuJC5maXJlID0gZnVuY3Rpb24odGFyZ2V0LCB0eXBlLCBwcm9wZXJ0aWVzKSB7XG5cdHZhciBldnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudChcIkhUTUxFdmVudHNcIik7XG5cblx0ZXZ0LmluaXRFdmVudCh0eXBlLCB0cnVlLCB0cnVlICk7XG5cblx0Zm9yICh2YXIgaiBpbiBwcm9wZXJ0aWVzKSB7XG5cdFx0ZXZ0W2pdID0gcHJvcGVydGllc1tqXTtcblx0fVxuXG5cdHJldHVybiB0YXJnZXQuZGlzcGF0Y2hFdmVudChldnQpO1xufTtcblxuJC5yZWdFeHBFc2NhcGUgPSBmdW5jdGlvbiAocykge1xuXHRyZXR1cm4gcy5yZXBsYWNlKC9bLVxcXFxeJCorPy4oKXxbXFxde31dL2csIFwiXFxcXCQmXCIpO1xufTtcblxuJC5zaWJsaW5nSW5kZXggPSBmdW5jdGlvbiAoZWwpIHtcblx0LyogZXNsaW50LWRpc2FibGUgbm8tY29uZC1hc3NpZ24gKi9cblx0Zm9yICh2YXIgaSA9IDA7IGVsID0gZWwucHJldmlvdXNFbGVtZW50U2libGluZzsgaSsrKTtcblx0cmV0dXJuIGk7XG59O1xuXG4vLyBJbml0aWFsaXphdGlvblxuXG5mdW5jdGlvbiBpbml0KCkge1xuXHQkJChcImlucHV0LmF3ZXNvbXBsZXRlXCIpLmZvckVhY2goZnVuY3Rpb24gKGlucHV0KSB7XG5cdFx0bmV3IF8oaW5wdXQpO1xuXHR9KTtcbn1cblxuLy8gQXJlIHdlIGluIGEgYnJvd3Nlcj8gQ2hlY2sgZm9yIERvY3VtZW50IGNvbnN0cnVjdG9yXG5pZiAodHlwZW9mIERvY3VtZW50ICE9PSBcInVuZGVmaW5lZFwiKSB7XG5cdC8vIERPTSBhbHJlYWR5IGxvYWRlZD9cblx0aWYgKGRvY3VtZW50LnJlYWR5U3RhdGUgIT09IFwibG9hZGluZ1wiKSB7XG5cdFx0aW5pdCgpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdC8vIFdhaXQgZm9yIGl0XG5cdFx0ZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcIkRPTUNvbnRlbnRMb2FkZWRcIiwgaW5pdCk7XG5cdH1cbn1cblxuXy4kID0gJDtcbl8uJCQgPSAkJDtcblxuLy8gTWFrZSBzdXJlIHRvIGV4cG9ydCBBd2Vzb21wbGV0ZSBvbiBzZWxmIHdoZW4gaW4gYSBicm93c2VyXG5pZiAodHlwZW9mIHNlbGYgIT09IFwidW5kZWZpbmVkXCIpIHtcblx0c2VsZi5Bd2Vzb21wbGV0ZSA9IF87XG59XG5cbi8vIEV4cG9zZSBBd2Vzb21wbGV0ZSBhcyBhIENKUyBtb2R1bGVcbmlmICh0eXBlb2YgbW9kdWxlID09PSBcIm9iamVjdFwiICYmIG1vZHVsZS5leHBvcnRzKSB7XG5cdG1vZHVsZS5leHBvcnRzID0gXztcbn1cblxucmV0dXJuIF87XG5cbn0oKSk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9hd2Vzb21wbGV0ZS9hd2Vzb21wbGV0ZS5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvYXdlc29tcGxldGUvYXdlc29tcGxldGUuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHYyLjAuMjMgfCDCqSBkbnBfdGhlbWUgfCBNSVQtTGljZW5zZVxuKGZ1bmN0aW9uIChyb290LCBmYWN0b3J5KSB7XG4gIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICAvLyBBTUQgc3VwcG9ydDpcbiAgICBkZWZpbmUoW10sIGZhY3RvcnkpO1xuICB9IGVsc2UgaWYgKHR5cGVvZiBtb2R1bGUgPT09ICdvYmplY3QnICYmIG1vZHVsZS5leHBvcnRzKSB7XG4gICAgLy8gQ29tbW9uSlMtbGlrZTpcbiAgICBtb2R1bGUuZXhwb3J0cyA9IGZhY3RvcnkoKTtcbiAgfSBlbHNlIHtcbiAgICAvLyBCcm93c2VyIGdsb2JhbHMgKHJvb3QgaXMgd2luZG93KVxuICAgIHZhciBic24gPSBmYWN0b3J5KCk7XG4gICAgcm9vdC5Nb2RhbCA9IGJzbi5Nb2RhbDtcbiAgICByb290LkNvbGxhcHNlID0gYnNuLkNvbGxhcHNlO1xuICAgIHJvb3QuQWxlcnQgPSBic24uQWxlcnQ7XG4gIH1cbn0odGhpcywgZnVuY3Rpb24gKCkge1xuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgSW50ZXJuYWwgVXRpbGl0eSBGdW5jdGlvbnNcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFwidXNlIHN0cmljdFwiO1xuICBcbiAgLy8gZ2xvYmFsc1xuICB2YXIgZ2xvYmFsT2JqZWN0ID0gdHlwZW9mIGdsb2JhbCAhPT0gJ3VuZGVmaW5lZCcgPyBnbG9iYWwgOiB0aGlzfHx3aW5kb3csXG4gICAgRE9DID0gZG9jdW1lbnQsIEhUTUwgPSBET0MuZG9jdW1lbnRFbGVtZW50LCBib2R5ID0gJ2JvZHknLCAvLyBhbGxvdyB0aGUgbGlicmFyeSB0byBiZSB1c2VkIGluIDxoZWFkPlxuICBcbiAgICAvLyBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIEdsb2JhbCBPYmplY3RcbiAgICBCU04gPSBnbG9iYWxPYmplY3QuQlNOID0ge30sXG4gICAgc3VwcG9ydHMgPSBCU04uc3VwcG9ydHMgPSBbXSxcbiAgXG4gICAgLy8gZnVuY3Rpb24gdG9nZ2xlIGF0dHJpYnV0ZXNcbiAgICBkYXRhVG9nZ2xlICAgID0gJ2RhdGEtdG9nZ2xlJyxcbiAgICBkYXRhRGlzbWlzcyAgID0gJ2RhdGEtZGlzbWlzcycsXG4gICAgZGF0YVNweSAgICAgICA9ICdkYXRhLXNweScsXG4gICAgZGF0YVJpZGUgICAgICA9ICdkYXRhLXJpZGUnLFxuICAgIFxuICAgIC8vIGNvbXBvbmVudHNcbiAgICBzdHJpbmdBZmZpeCAgICAgPSAnQWZmaXgnLFxuICAgIHN0cmluZ0FsZXJ0ICAgICA9ICdBbGVydCcsXG4gICAgc3RyaW5nQnV0dG9uICAgID0gJ0J1dHRvbicsXG4gICAgc3RyaW5nQ2Fyb3VzZWwgID0gJ0Nhcm91c2VsJyxcbiAgICBzdHJpbmdDb2xsYXBzZSAgPSAnQ29sbGFwc2UnLFxuICAgIHN0cmluZ0Ryb3Bkb3duICA9ICdEcm9wZG93bicsXG4gICAgc3RyaW5nTW9kYWwgICAgID0gJ01vZGFsJyxcbiAgICBzdHJpbmdQb3BvdmVyICAgPSAnUG9wb3ZlcicsXG4gICAgc3RyaW5nU2Nyb2xsU3B5ID0gJ1Njcm9sbFNweScsXG4gICAgc3RyaW5nVGFiICAgICAgID0gJ1RhYicsXG4gICAgc3RyaW5nVG9vbHRpcCAgID0gJ1Rvb2x0aXAnLFxuICBcbiAgICAvLyBvcHRpb25zIERBVEEgQVBJXG4gICAgZGF0YWJhY2tkcm9wICAgICAgPSAnZGF0YS1iYWNrZHJvcCcsXG4gICAgZGF0YUtleWJvYXJkICAgICAgPSAnZGF0YS1rZXlib2FyZCcsXG4gICAgZGF0YVRhcmdldCAgICAgICAgPSAnZGF0YS10YXJnZXQnLFxuICAgIGRhdGFJbnRlcnZhbCAgICAgID0gJ2RhdGEtaW50ZXJ2YWwnLFxuICAgIGRhdGFIZWlnaHQgICAgICAgID0gJ2RhdGEtaGVpZ2h0JyxcbiAgICBkYXRhUGF1c2UgICAgICAgICA9ICdkYXRhLXBhdXNlJyxcbiAgICBkYXRhVGl0bGUgICAgICAgICA9ICdkYXRhLXRpdGxlJywgIFxuICAgIGRhdGFPcmlnaW5hbFRpdGxlID0gJ2RhdGEtb3JpZ2luYWwtdGl0bGUnLFxuICAgIGRhdGFPcmlnaW5hbFRleHQgID0gJ2RhdGEtb3JpZ2luYWwtdGV4dCcsXG4gICAgZGF0YURpc21pc3NpYmxlICAgPSAnZGF0YS1kaXNtaXNzaWJsZScsXG4gICAgZGF0YVRyaWdnZXIgICAgICAgPSAnZGF0YS10cmlnZ2VyJyxcbiAgICBkYXRhQW5pbWF0aW9uICAgICA9ICdkYXRhLWFuaW1hdGlvbicsXG4gICAgZGF0YUNvbnRhaW5lciAgICAgPSAnZGF0YS1jb250YWluZXInLFxuICAgIGRhdGFQbGFjZW1lbnQgICAgID0gJ2RhdGEtcGxhY2VtZW50JyxcbiAgICBkYXRhRGVsYXkgICAgICAgICA9ICdkYXRhLWRlbGF5JyxcbiAgICBkYXRhT2Zmc2V0VG9wICAgICA9ICdkYXRhLW9mZnNldC10b3AnLFxuICAgIGRhdGFPZmZzZXRCb3R0b20gID0gJ2RhdGEtb2Zmc2V0LWJvdHRvbScsXG4gIFxuICAgIC8vIG9wdGlvbiBrZXlzXG4gICAgYmFja2Ryb3AgPSAnYmFja2Ryb3AnLCBrZXlib2FyZCA9ICdrZXlib2FyZCcsIGRlbGF5ID0gJ2RlbGF5JyxcbiAgICBjb250ZW50ID0gJ2NvbnRlbnQnLCB0YXJnZXQgPSAndGFyZ2V0JywgXG4gICAgaW50ZXJ2YWwgPSAnaW50ZXJ2YWwnLCBwYXVzZSA9ICdwYXVzZScsIGFuaW1hdGlvbiA9ICdhbmltYXRpb24nLFxuICAgIHBsYWNlbWVudCA9ICdwbGFjZW1lbnQnLCBjb250YWluZXIgPSAnY29udGFpbmVyJywgXG4gIFxuICAgIC8vIGJveCBtb2RlbFxuICAgIG9mZnNldFRvcCAgICA9ICdvZmZzZXRUb3AnLCAgICAgIG9mZnNldEJvdHRvbSAgID0gJ29mZnNldEJvdHRvbScsXG4gICAgb2Zmc2V0TGVmdCAgID0gJ29mZnNldExlZnQnLFxuICAgIHNjcm9sbFRvcCAgICA9ICdzY3JvbGxUb3AnLCAgICAgIHNjcm9sbExlZnQgICAgID0gJ3Njcm9sbExlZnQnLFxuICAgIGNsaWVudFdpZHRoICA9ICdjbGllbnRXaWR0aCcsICAgIGNsaWVudEhlaWdodCAgID0gJ2NsaWVudEhlaWdodCcsXG4gICAgb2Zmc2V0V2lkdGggID0gJ29mZnNldFdpZHRoJywgICAgb2Zmc2V0SGVpZ2h0ICAgPSAnb2Zmc2V0SGVpZ2h0JyxcbiAgICBpbm5lcldpZHRoICAgPSAnaW5uZXJXaWR0aCcsICAgICBpbm5lckhlaWdodCAgICA9ICdpbm5lckhlaWdodCcsXG4gICAgc2Nyb2xsSGVpZ2h0ID0gJ3Njcm9sbEhlaWdodCcsICAgaGVpZ2h0ICAgICAgICAgPSAnaGVpZ2h0JyxcbiAgXG4gICAgLy8gYXJpYVxuICAgIGFyaWFFeHBhbmRlZCA9ICdhcmlhLWV4cGFuZGVkJyxcbiAgICBhcmlhSGlkZGVuICAgPSAnYXJpYS1oaWRkZW4nLFxuICBcbiAgICAvLyBldmVudCBuYW1lc1xuICAgIGNsaWNrRXZlbnQgICAgPSAnY2xpY2snLFxuICAgIGhvdmVyRXZlbnQgICAgPSAnaG92ZXInLFxuICAgIGtleWRvd25FdmVudCAgPSAna2V5ZG93bicsXG4gICAga2V5dXBFdmVudCAgICA9ICdrZXl1cCcsICBcbiAgICByZXNpemVFdmVudCAgID0gJ3Jlc2l6ZScsXG4gICAgc2Nyb2xsRXZlbnQgICA9ICdzY3JvbGwnLFxuICAgIC8vIG9yaWdpbmFsRXZlbnRzXG4gICAgc2hvd0V2ZW50ICAgICA9ICdzaG93JyxcbiAgICBzaG93bkV2ZW50ICAgID0gJ3Nob3duJyxcbiAgICBoaWRlRXZlbnQgICAgID0gJ2hpZGUnLFxuICAgIGhpZGRlbkV2ZW50ICAgPSAnaGlkZGVuJyxcbiAgICBjbG9zZUV2ZW50ICAgID0gJ2Nsb3NlJyxcbiAgICBjbG9zZWRFdmVudCAgID0gJ2Nsb3NlZCcsXG4gICAgc2xpZEV2ZW50ICAgICA9ICdzbGlkJyxcbiAgICBzbGlkZUV2ZW50ICAgID0gJ3NsaWRlJyxcbiAgICBjaGFuZ2VFdmVudCAgID0gJ2NoYW5nZScsXG4gIFxuICAgIC8vIG90aGVyXG4gICAgZ2V0QXR0cmlidXRlICAgICAgICAgICA9ICdnZXRBdHRyaWJ1dGUnLFxuICAgIHNldEF0dHJpYnV0ZSAgICAgICAgICAgPSAnc2V0QXR0cmlidXRlJyxcbiAgICBoYXNBdHRyaWJ1dGUgICAgICAgICAgID0gJ2hhc0F0dHJpYnV0ZScsXG4gICAgY3JlYXRlRWxlbWVudCAgICAgICAgICA9ICdjcmVhdGVFbGVtZW50JyxcbiAgICBhcHBlbmRDaGlsZCAgICAgICAgICAgID0gJ2FwcGVuZENoaWxkJyxcbiAgICBpbm5lckhUTUwgICAgICAgICAgICAgID0gJ2lubmVySFRNTCcsXG4gICAgZ2V0RWxlbWVudHNCeVRhZ05hbWUgICA9ICdnZXRFbGVtZW50c0J5VGFnTmFtZScsXG4gICAgcHJldmVudERlZmF1bHQgICAgICAgICA9ICdwcmV2ZW50RGVmYXVsdCcsXG4gICAgZ2V0Qm91bmRpbmdDbGllbnRSZWN0ICA9ICdnZXRCb3VuZGluZ0NsaWVudFJlY3QnLFxuICAgIHF1ZXJ5U2VsZWN0b3JBbGwgICAgICAgPSAncXVlcnlTZWxlY3RvckFsbCcsXG4gICAgZ2V0RWxlbWVudHNCeUNMQVNTTkFNRSA9ICdnZXRFbGVtZW50c0J5Q2xhc3NOYW1lJyxcbiAgICBnZXRDb21wdXRlZFN0eWxlICAgICAgID0gJ2dldENvbXB1dGVkU3R5bGUnLCAgXG4gIFxuICAgIGluZGV4T2YgICAgICA9ICdpbmRleE9mJyxcbiAgICBwYXJlbnROb2RlICAgPSAncGFyZW50Tm9kZScsXG4gICAgbGVuZ3RoICAgICAgID0gJ2xlbmd0aCcsXG4gICAgdG9Mb3dlckNhc2UgID0gJ3RvTG93ZXJDYXNlJyxcbiAgICBUcmFuc2l0aW9uICAgPSAnVHJhbnNpdGlvbicsXG4gICAgRHVyYXRpb24gICAgID0gJ0R1cmF0aW9uJywgIFxuICAgIFdlYmtpdCAgICAgICA9ICdXZWJraXQnLFxuICAgIHN0eWxlICAgICAgICA9ICdzdHlsZScsXG4gICAgcHVzaCAgICAgICAgID0gJ3B1c2gnLFxuICAgIHRhYmluZGV4ICAgICA9ICd0YWJpbmRleCcsXG4gICAgY29udGFpbnMgICAgID0gJ2NvbnRhaW5zJywgIFxuICAgIFxuICAgIGFjdGl2ZSAgICAgPSAnYWN0aXZlJyxcbiAgICBpbkNsYXNzICAgID0gJ2luJyxcbiAgICBjb2xsYXBzaW5nID0gJ2NvbGxhcHNpbmcnLFxuICAgIGRpc2FibGVkICAgPSAnZGlzYWJsZWQnLFxuICAgIGxvYWRpbmcgICAgPSAnbG9hZGluZycsXG4gICAgbGVmdCAgICAgICA9ICdsZWZ0JyxcbiAgICByaWdodCAgICAgID0gJ3JpZ2h0JyxcbiAgICB0b3AgICAgICAgID0gJ3RvcCcsXG4gICAgYm90dG9tICAgICA9ICdib3R0b20nLFxuICBcbiAgICAvLyBJRTggYnJvd3NlciBkZXRlY3RcbiAgICBpc0lFOCA9ICEoJ29wYWNpdHknIGluIEhUTUxbc3R5bGVdKSxcbiAgXG4gICAgLy8gdG9vbHRpcCAvIHBvcG92ZXJcbiAgICBtb3VzZUhvdmVyID0gKCdvbm1vdXNlbGVhdmUnIGluIERPQykgPyBbICdtb3VzZWVudGVyJywgJ21vdXNlbGVhdmUnXSA6IFsgJ21vdXNlb3ZlcicsICdtb3VzZW91dCcgXSxcbiAgICB0aXBQb3NpdGlvbnMgPSAvXFxiKHRvcHxib3R0b218bGVmdHxyaWdodCkrLyxcbiAgICBcbiAgICAvLyBtb2RhbFxuICAgIG1vZGFsT3ZlcmxheSA9IDAsXG4gICAgZml4ZWRUb3AgPSAnbmF2YmFyLWZpeGVkLXRvcCcsXG4gICAgZml4ZWRCb3R0b20gPSAnbmF2YmFyLWZpeGVkLWJvdHRvbScsICBcbiAgICBcbiAgICAvLyB0cmFuc2l0aW9uRW5kIHNpbmNlIDIuMC40XG4gICAgc3VwcG9ydFRyYW5zaXRpb25zID0gV2Via2l0K1RyYW5zaXRpb24gaW4gSFRNTFtzdHlsZV0gfHwgVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKSBpbiBIVE1MW3N0eWxlXSxcbiAgICB0cmFuc2l0aW9uRW5kRXZlbnQgPSBXZWJraXQrVHJhbnNpdGlvbiBpbiBIVE1MW3N0eWxlXSA/IFdlYmtpdFt0b0xvd2VyQ2FzZV0oKStUcmFuc2l0aW9uKydFbmQnIDogVHJhbnNpdGlvblt0b0xvd2VyQ2FzZV0oKSsnZW5kJyxcbiAgICB0cmFuc2l0aW9uRHVyYXRpb24gPSBXZWJraXQrRHVyYXRpb24gaW4gSFRNTFtzdHlsZV0gPyBXZWJraXRbdG9Mb3dlckNhc2VdKCkrVHJhbnNpdGlvbitEdXJhdGlvbiA6IFRyYW5zaXRpb25bdG9Mb3dlckNhc2VdKCkrRHVyYXRpb24sXG4gIFxuICAgIC8vIHNldCBuZXcgZm9jdXMgZWxlbWVudCBzaW5jZSAyLjAuM1xuICAgIHNldEZvY3VzID0gZnVuY3Rpb24oZWxlbWVudCl7XG4gICAgICBlbGVtZW50LmZvY3VzID8gZWxlbWVudC5mb2N1cygpIDogZWxlbWVudC5zZXRBY3RpdmUoKTtcbiAgICB9LFxuICBcbiAgICAvLyBjbGFzcyBtYW5pcHVsYXRpb24sIHNpbmNlIDIuMC4wIHJlcXVpcmVzIHBvbHlmaWxsLmpzXG4gICAgYWRkQ2xhc3MgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSkge1xuICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKGNsYXNzTkFNRSk7XG4gICAgfSxcbiAgICByZW1vdmVDbGFzcyA9IGZ1bmN0aW9uKGVsZW1lbnQsY2xhc3NOQU1FKSB7XG4gICAgICBlbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOQU1FKTtcbiAgICB9LFxuICAgIGhhc0NsYXNzID0gZnVuY3Rpb24oZWxlbWVudCxjbGFzc05BTUUpeyAvLyBzaW5jZSAyLjAuMFxuICAgICAgcmV0dXJuIGVsZW1lbnQuY2xhc3NMaXN0W2NvbnRhaW5zXShjbGFzc05BTUUpO1xuICAgIH0sXG4gIFxuICAgIC8vIHNlbGVjdGlvbiBtZXRob2RzXG4gICAgbm9kZUxpc3RUb0FycmF5ID0gZnVuY3Rpb24obm9kZUxpc3Qpe1xuICAgICAgdmFyIGNoaWxkSXRlbXMgPSBbXTsgZm9yICh2YXIgaSA9IDAsIG5sbCA9IG5vZGVMaXN0W2xlbmd0aF07IGk8bmxsOyBpKyspIHsgY2hpbGRJdGVtc1twdXNoXSggbm9kZUxpc3RbaV0gKSB9XG4gICAgICByZXR1cm4gY2hpbGRJdGVtcztcbiAgICB9LFxuICAgIGdldEVsZW1lbnRzQnlDbGFzc05hbWUgPSBmdW5jdGlvbihlbGVtZW50LGNsYXNzTkFNRSkgeyAvLyBnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIElFOCtcbiAgICAgIHZhciBzZWxlY3Rpb25NZXRob2QgPSBpc0lFOCA/IHF1ZXJ5U2VsZWN0b3JBbGwgOiBnZXRFbGVtZW50c0J5Q0xBU1NOQU1FOyAgICAgIFxuICAgICAgcmV0dXJuIG5vZGVMaXN0VG9BcnJheShlbGVtZW50W3NlbGVjdGlvbk1ldGhvZF0oIGlzSUU4ID8gJy4nICsgY2xhc3NOQU1FLnJlcGxhY2UoL1xccyg/PVthLXpdKS9nLCcuJykgOiBjbGFzc05BTUUgKSk7XG4gICAgfSxcbiAgICBxdWVyeUVsZW1lbnQgPSBmdW5jdGlvbiAoc2VsZWN0b3IsIHBhcmVudCkge1xuICAgICAgdmFyIGxvb2tVcCA9IHBhcmVudCA/IHBhcmVudCA6IERPQztcbiAgICAgIHJldHVybiB0eXBlb2Ygc2VsZWN0b3IgPT09ICdvYmplY3QnID8gc2VsZWN0b3IgOiBsb29rVXAucXVlcnlTZWxlY3RvcihzZWxlY3Rvcik7XG4gICAgfSxcbiAgICBnZXRDbG9zZXN0ID0gZnVuY3Rpb24gKGVsZW1lbnQsIHNlbGVjdG9yKSB7IC8vZWxlbWVudCBpcyB0aGUgZWxlbWVudCBhbmQgc2VsZWN0b3IgaXMgZm9yIHRoZSBjbG9zZXN0IHBhcmVudCBlbGVtZW50IHRvIGZpbmRcbiAgICAgIC8vIHNvdXJjZSBodHRwOi8vZ29tYWtldGhpbmdzLmNvbS9jbGltYmluZy11cC1hbmQtZG93bi10aGUtZG9tLXRyZWUtd2l0aC12YW5pbGxhLWphdmFzY3JpcHQvXG4gICAgICB2YXIgZmlyc3RDaGFyID0gc2VsZWN0b3IuY2hhckF0KDApLCBzZWxlY3RvclN1YnN0cmluZyA9IHNlbGVjdG9yLnN1YnN0cigxKTtcbiAgICAgIGlmICggZmlyc3RDaGFyID09PSAnLicgKSB7Ly8gSWYgc2VsZWN0b3IgaXMgYSBjbGFzc1xuICAgICAgICBmb3IgKCA7IGVsZW1lbnQgJiYgZWxlbWVudCAhPT0gRE9DOyBlbGVtZW50ID0gZWxlbWVudFtwYXJlbnROb2RlXSApIHsgLy8gR2V0IGNsb3Nlc3QgbWF0Y2hcbiAgICAgICAgICBpZiAoIHF1ZXJ5RWxlbWVudChzZWxlY3RvcixlbGVtZW50W3BhcmVudE5vZGVdKSAhPT0gbnVsbCAmJiBoYXNDbGFzcyhlbGVtZW50LHNlbGVjdG9yU3Vic3RyaW5nKSApIHsgcmV0dXJuIGVsZW1lbnQ7IH1cbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmICggZmlyc3RDaGFyID09PSAnIycgKSB7IC8vIElmIHNlbGVjdG9yIGlzIGFuIElEXG4gICAgICAgIGZvciAoIDsgZWxlbWVudCAmJiBlbGVtZW50ICE9PSBET0M7IGVsZW1lbnQgPSBlbGVtZW50W3BhcmVudE5vZGVdICkgeyAvLyBHZXQgY2xvc2VzdCBtYXRjaFxuICAgICAgICAgIGlmICggZWxlbWVudC5pZCA9PT0gc2VsZWN0b3JTdWJzdHJpbmcgKSB7IHJldHVybiBlbGVtZW50OyB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICBcbiAgICAvLyBldmVudCBhdHRhY2ggalF1ZXJ5IHN0eWxlIC8gdHJpZ2dlciAgc2luY2UgMS4yLjBcbiAgICBvbiA9IGZ1bmN0aW9uIChlbGVtZW50LCBldmVudCwgaGFuZGxlcikge1xuICAgICAgZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyLCBmYWxzZSk7XG4gICAgfSxcbiAgICBvZmYgPSBmdW5jdGlvbihlbGVtZW50LCBldmVudCwgaGFuZGxlcikge1xuICAgICAgZWxlbWVudC5yZW1vdmVFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyLCBmYWxzZSk7XG4gICAgfSxcbiAgICBvbmUgPSBmdW5jdGlvbiAoZWxlbWVudCwgZXZlbnQsIGhhbmRsZXIpIHsgLy8gb25lIHNpbmNlIDIuMC40XG4gICAgICBvbihlbGVtZW50LCBldmVudCwgZnVuY3Rpb24gaGFuZGxlcldyYXBwZXIoZSl7XG4gICAgICAgIGhhbmRsZXIoZSk7XG4gICAgICAgIG9mZihlbGVtZW50LCBldmVudCwgaGFuZGxlcldyYXBwZXIpO1xuICAgICAgfSk7XG4gICAgfSxcbiAgICBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudCA9IGZ1bmN0aW9uKGVsZW1lbnQpIHtcbiAgICAgIHZhciBkdXJhdGlvbiA9IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShlbGVtZW50KVt0cmFuc2l0aW9uRHVyYXRpb25dO1xuICAgICAgZHVyYXRpb24gPSBwYXJzZUZsb2F0KGR1cmF0aW9uKTtcbiAgICAgIGR1cmF0aW9uID0gdHlwZW9mIGR1cmF0aW9uID09PSAnbnVtYmVyJyAmJiAhaXNOYU4oZHVyYXRpb24pID8gZHVyYXRpb24gKiAxMDAwIDogMDtcbiAgICAgIHJldHVybiBkdXJhdGlvbiArIDUwOyAvLyB3ZSB0YWtlIGEgc2hvcnQgb2Zmc2V0IHRvIG1ha2Ugc3VyZSB3ZSBmaXJlIG9uIHRoZSBuZXh0IGZyYW1lIGFmdGVyIGFuaW1hdGlvblxuICAgIH0sXG4gICAgZW11bGF0ZVRyYW5zaXRpb25FbmQgPSBmdW5jdGlvbihlbGVtZW50LGhhbmRsZXIpeyAvLyBlbXVsYXRlVHJhbnNpdGlvbkVuZCBzaW5jZSAyLjAuNFxuICAgICAgdmFyIGNhbGxlZCA9IDAsIGR1cmF0aW9uID0gZ2V0VHJhbnNpdGlvbkR1cmF0aW9uRnJvbUVsZW1lbnQoZWxlbWVudCk7XG4gICAgICBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb25lKGVsZW1lbnQsIHRyYW5zaXRpb25FbmRFdmVudCwgZnVuY3Rpb24oZSl7IGhhbmRsZXIoZSk7IGNhbGxlZCA9IDE7IH0pO1xuICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHsgIWNhbGxlZCAmJiBoYW5kbGVyKCk7IH0sIGR1cmF0aW9uKTtcbiAgICB9LFxuICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50ID0gZnVuY3Rpb24gKGV2ZW50TmFtZSwgY29tcG9uZW50TmFtZSwgcmVsYXRlZCkge1xuICAgICAgdmFyIE9yaWdpbmFsQ3VzdG9tRXZlbnQgPSBuZXcgQ3VzdG9tRXZlbnQoIGV2ZW50TmFtZSArICcuYnMuJyArIGNvbXBvbmVudE5hbWUpO1xuICAgICAgT3JpZ2luYWxDdXN0b21FdmVudC5yZWxhdGVkVGFyZ2V0ID0gcmVsYXRlZDtcbiAgICAgIHRoaXMuZGlzcGF0Y2hFdmVudChPcmlnaW5hbEN1c3RvbUV2ZW50KTtcbiAgICB9LFxuICBcbiAgICAvLyB0b29sdGlwIC8gcG9wb3ZlciBzdHVmZlxuICAgIGdldFNjcm9sbCA9IGZ1bmN0aW9uKCkgeyAvLyBhbHNvIEFmZml4IGFuZCBTY3JvbGxTcHkgdXNlcyBpdFxuICAgICAgcmV0dXJuIHtcbiAgICAgICAgeSA6IGdsb2JhbE9iamVjdC5wYWdlWU9mZnNldCB8fCBIVE1MW3Njcm9sbFRvcF0sXG4gICAgICAgIHggOiBnbG9iYWxPYmplY3QucGFnZVhPZmZzZXQgfHwgSFRNTFtzY3JvbGxMZWZ0XVxuICAgICAgfVxuICAgIH0sXG4gICAgc3R5bGVUaXAgPSBmdW5jdGlvbihsaW5rLGVsZW1lbnQscG9zaXRpb24scGFyZW50KSB7IC8vIGJvdGggcG9wb3ZlcnMgYW5kIHRvb2x0aXBzICh0YXJnZXQsdG9vbHRpcC9wb3BvdmVyLHBsYWNlbWVudCxlbGVtZW50VG9BcHBlbmRUbylcbiAgICAgIHZhciBlbGVtZW50RGltZW5zaW9ucyA9IHsgdyA6IGVsZW1lbnRbb2Zmc2V0V2lkdGhdLCBoOiBlbGVtZW50W29mZnNldEhlaWdodF0gfSxcbiAgICAgICAgICB3aW5kb3dXaWR0aCA9IChIVE1MW2NsaWVudFdpZHRoXSB8fCBET0NbYm9keV1bY2xpZW50V2lkdGhdKSxcbiAgICAgICAgICB3aW5kb3dIZWlnaHQgPSAoSFRNTFtjbGllbnRIZWlnaHRdIHx8IERPQ1tib2R5XVtjbGllbnRIZWlnaHRdKSxcbiAgICAgICAgICByZWN0ID0gbGlua1tnZXRCb3VuZGluZ0NsaWVudFJlY3RdKCksIFxuICAgICAgICAgIHNjcm9sbCA9IHBhcmVudCA9PT0gRE9DW2JvZHldID8gZ2V0U2Nyb2xsKCkgOiB7IHg6IHBhcmVudFtvZmZzZXRMZWZ0XSArIHBhcmVudFtzY3JvbGxMZWZ0XSwgeTogcGFyZW50W29mZnNldFRvcF0gKyBwYXJlbnRbc2Nyb2xsVG9wXSB9LFxuICAgICAgICAgIGxpbmtEaW1lbnNpb25zID0geyB3OiByZWN0W3JpZ2h0XSAtIHJlY3RbbGVmdF0sIGg6IHJlY3RbYm90dG9tXSAtIHJlY3RbdG9wXSB9LFxuICAgICAgICAgIGFycm93ID0gcXVlcnlFbGVtZW50KCdbY2xhc3MqPVwiYXJyb3dcIl0nLGVsZW1lbnQpLFxuICAgICAgICAgIHRvcFBvc2l0aW9uLCBsZWZ0UG9zaXRpb24sIGFycm93VG9wLCBhcnJvd0xlZnQsXG4gIFxuICAgICAgICAgIGhhbGZUb3BFeGNlZWQgPSByZWN0W3RvcF0gKyBsaW5rRGltZW5zaW9ucy5oLzIgLSBlbGVtZW50RGltZW5zaW9ucy5oLzIgPCAwLFxuICAgICAgICAgIGhhbGZMZWZ0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGxpbmtEaW1lbnNpb25zLncvMiAtIGVsZW1lbnREaW1lbnNpb25zLncvMiA8IDAsXG4gICAgICAgICAgaGFsZlJpZ2h0RXhjZWVkID0gcmVjdFtsZWZ0XSArIGVsZW1lbnREaW1lbnNpb25zLncvMiArIGxpbmtEaW1lbnNpb25zLncvMiA+PSB3aW5kb3dXaWR0aCxcbiAgICAgICAgICBoYWxmQm90dG9tRXhjZWVkID0gcmVjdFt0b3BdICsgZWxlbWVudERpbWVuc2lvbnMuaC8yICsgbGlua0RpbWVuc2lvbnMuaC8yID49IHdpbmRvd0hlaWdodCxcbiAgICAgICAgICB0b3BFeGNlZWQgPSByZWN0W3RvcF0gLSBlbGVtZW50RGltZW5zaW9ucy5oIDwgMCxcbiAgICAgICAgICBsZWZ0RXhjZWVkID0gcmVjdFtsZWZ0XSAtIGVsZW1lbnREaW1lbnNpb25zLncgPCAwLFxuICAgICAgICAgIGJvdHRvbUV4Y2VlZCA9IHJlY3RbdG9wXSArIGVsZW1lbnREaW1lbnNpb25zLmggKyBsaW5rRGltZW5zaW9ucy5oID49IHdpbmRvd0hlaWdodCxcbiAgICAgICAgICByaWdodEV4Y2VlZCA9IHJlY3RbbGVmdF0gKyBlbGVtZW50RGltZW5zaW9ucy53ICsgbGlua0RpbWVuc2lvbnMudyA+PSB3aW5kb3dXaWR0aDtcbiAgXG4gICAgICAvLyByZWNvbXB1dGUgcG9zaXRpb25cbiAgICAgIHBvc2l0aW9uID0gKHBvc2l0aW9uID09PSBsZWZ0IHx8IHBvc2l0aW9uID09PSByaWdodCkgJiYgbGVmdEV4Y2VlZCAmJiByaWdodEV4Y2VlZCA/IHRvcCA6IHBvc2l0aW9uOyAvLyBmaXJzdCwgd2hlbiBib3RoIGxlZnQgYW5kIHJpZ2h0IGxpbWl0cyBhcmUgZXhjZWVkZWQsIHdlIGZhbGwgYmFjayB0byB0b3B8Ym90dG9tXG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSB0b3AgJiYgdG9wRXhjZWVkID8gYm90dG9tIDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSBib3R0b20gJiYgYm90dG9tRXhjZWVkID8gdG9wIDogcG9zaXRpb247XG4gICAgICBwb3NpdGlvbiA9IHBvc2l0aW9uID09PSBsZWZ0ICYmIGxlZnRFeGNlZWQgPyByaWdodCA6IHBvc2l0aW9uO1xuICAgICAgcG9zaXRpb24gPSBwb3NpdGlvbiA9PT0gcmlnaHQgJiYgcmlnaHRFeGNlZWQgPyBsZWZ0IDogcG9zaXRpb247XG4gICAgICBcbiAgICAgIC8vIGFwcGx5IHN0eWxpbmcgdG8gdG9vbHRpcCBvciBwb3BvdmVyXG4gICAgICBpZiAoIHBvc2l0aW9uID09PSBsZWZ0IHx8IHBvc2l0aW9uID09PSByaWdodCApIHsgLy8gc2Vjb25kYXJ5fHNpZGUgcG9zaXRpb25zXG4gICAgICAgIGlmICggcG9zaXRpb24gPT09IGxlZnQgKSB7IC8vIExFRlRcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSByZWN0W2xlZnRdICsgc2Nyb2xsLnggLSBlbGVtZW50RGltZW5zaW9ucy53O1xuICAgICAgICB9IGVsc2UgeyAvLyBSSUdIVFxuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IHJlY3RbbGVmdF0gKyBzY3JvbGwueCArIGxpbmtEaW1lbnNpb25zLnc7XG4gICAgICAgIH1cbiAgXG4gICAgICAgIC8vIGFkanVzdCB0b3AgYW5kIGFycm93XG4gICAgICAgIGlmIChoYWxmVG9wRXhjZWVkKSB7XG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueTtcbiAgICAgICAgICBhcnJvd1RvcCA9IGxpbmtEaW1lbnNpb25zLmgvMjtcbiAgICAgICAgfSBlbHNlIGlmIChoYWxmQm90dG9tRXhjZWVkKSB7XG4gICAgICAgICAgdG9wUG9zaXRpb24gPSByZWN0W3RvcF0gKyBzY3JvbGwueSAtIGVsZW1lbnREaW1lbnNpb25zLmggKyBsaW5rRGltZW5zaW9ucy5oO1xuICAgICAgICAgIGFycm93VG9wID0gZWxlbWVudERpbWVuc2lvbnMuaCAtIGxpbmtEaW1lbnNpb25zLmgvMjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9IHJlY3RbdG9wXSArIHNjcm9sbC55IC0gZWxlbWVudERpbWVuc2lvbnMuaC8yICsgbGlua0RpbWVuc2lvbnMuaC8yO1xuICAgICAgICB9XG4gICAgICB9IGVsc2UgaWYgKCBwb3NpdGlvbiA9PT0gdG9wIHx8IHBvc2l0aW9uID09PSBib3R0b20gKSB7IC8vIHByaW1hcnl8dmVydGljYWwgcG9zaXRpb25zXG4gICAgICAgIGlmICggcG9zaXRpb24gPT09IHRvcCkgeyAvLyBUT1BcbiAgICAgICAgICB0b3BQb3NpdGlvbiA9ICByZWN0W3RvcF0gKyBzY3JvbGwueSAtIGVsZW1lbnREaW1lbnNpb25zLmg7XG4gICAgICAgIH0gZWxzZSB7IC8vIEJPVFRPTVxuICAgICAgICAgIHRvcFBvc2l0aW9uID0gcmVjdFt0b3BdICsgc2Nyb2xsLnkgKyBsaW5rRGltZW5zaW9ucy5oO1xuICAgICAgICB9XG4gICAgICAgIC8vIGFkanVzdCBsZWZ0IHwgcmlnaHQgYW5kIGFsc28gdGhlIGFycm93XG4gICAgICAgIGlmIChoYWxmTGVmdEV4Y2VlZCkge1xuICAgICAgICAgIGxlZnRQb3NpdGlvbiA9IDA7XG4gICAgICAgICAgYXJyb3dMZWZ0ID0gcmVjdFtsZWZ0XSArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfSBlbHNlIGlmIChoYWxmUmlnaHRFeGNlZWQpIHtcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSB3aW5kb3dXaWR0aCAtIGVsZW1lbnREaW1lbnNpb25zLncqMS4wMTtcbiAgICAgICAgICBhcnJvd0xlZnQgPSBlbGVtZW50RGltZW5zaW9ucy53IC0gKCB3aW5kb3dXaWR0aCAtIHJlY3RbbGVmdF0gKSArIGxpbmtEaW1lbnNpb25zLncvMjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBsZWZ0UG9zaXRpb24gPSByZWN0W2xlZnRdICsgc2Nyb2xsLnggLSBlbGVtZW50RGltZW5zaW9ucy53LzIgKyBsaW5rRGltZW5zaW9ucy53LzI7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgXG4gICAgICAvLyBhcHBseSBzdHlsZSB0byB0b29sdGlwL3BvcG92ZXIgYW5kIGl0J3MgYXJyb3dcbiAgICAgIGVsZW1lbnRbc3R5bGVdW3RvcF0gPSB0b3BQb3NpdGlvbiArICdweCc7XG4gICAgICBlbGVtZW50W3N0eWxlXVtsZWZ0XSA9IGxlZnRQb3NpdGlvbiArICdweCc7XG4gIFxuICAgICAgYXJyb3dUb3AgJiYgKGFycm93W3N0eWxlXVt0b3BdID0gYXJyb3dUb3AgKyAncHgnKTtcbiAgICAgIGFycm93TGVmdCAmJiAoYXJyb3dbc3R5bGVdW2xlZnRdID0gYXJyb3dMZWZ0ICsgJ3B4Jyk7XG4gIFxuICAgICAgZWxlbWVudC5jbGFzc05hbWVbaW5kZXhPZl0ocG9zaXRpb24pID09PSAtMSAmJiAoZWxlbWVudC5jbGFzc05hbWUgPSBlbGVtZW50LmNsYXNzTmFtZS5yZXBsYWNlKHRpcFBvc2l0aW9ucyxwb3NpdGlvbikpO1xuICAgIH07XG4gIFxuICBCU04udmVyc2lvbiA9ICcyLjAuMjMnO1xuICBcbiAgLyogTmF0aXZlIEphdmFzY3JpcHQgZm9yIEJvb3RzdHJhcCAzIHwgTW9kYWxcbiAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBNT0RBTCBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PVxuICB2YXIgTW9kYWwgPSBmdW5jdGlvbihlbGVtZW50LCBvcHRpb25zKSB7IC8vIGVsZW1lbnQgY2FuIGJlIHRoZSBtb2RhbC90cmlnZ2VyaW5nIGJ1dHRvblxuICBcbiAgICAvLyB0aGUgbW9kYWwgKGJvdGggSmF2YVNjcmlwdCAvIERBVEEgQVBJIGluaXQpIC8gdHJpZ2dlcmluZyBidXR0b24gZWxlbWVudCAoREFUQSBBUEkpXG4gICAgZWxlbWVudCA9IHF1ZXJ5RWxlbWVudChlbGVtZW50KTtcbiAgXG4gICAgLy8gZGV0ZXJtaW5lIG1vZGFsLCB0cmlnZ2VyaW5nIGVsZW1lbnRcbiAgICB2YXIgYnRuQ2hlY2sgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oZGF0YVRhcmdldCl8fGVsZW1lbnRbZ2V0QXR0cmlidXRlXSgnaHJlZicpLFxuICAgICAgY2hlY2tNb2RhbCA9IHF1ZXJ5RWxlbWVudCggYnRuQ2hlY2sgKSxcbiAgICAgIG1vZGFsID0gaGFzQ2xhc3MoZWxlbWVudCwnbW9kYWwnKSA/IGVsZW1lbnQgOiBjaGVja01vZGFsLFxuICAgICAgb3ZlcmxheURlbGF5LFxuICBcbiAgICAgIC8vIHN0cmluZ3NcbiAgICAgIGNvbXBvbmVudCA9ICdtb2RhbCcsXG4gICAgICBzdGF0aWNTdHJpbmcgPSAnc3RhdGljJyxcbiAgICAgIHBhZGRpbmdMZWZ0ID0gJ3BhZGRpbmdMZWZ0JyxcbiAgICAgIHBhZGRpbmdSaWdodCA9ICdwYWRkaW5nUmlnaHQnLFxuICAgICAgbW9kYWxCYWNrZHJvcFN0cmluZyA9ICdtb2RhbC1iYWNrZHJvcCc7XG4gIFxuICAgIGlmICggaGFzQ2xhc3MoZWxlbWVudCwnbW9kYWwnKSApIHsgZWxlbWVudCA9IG51bGw7IH0gLy8gbW9kYWwgaXMgbm93IGluZGVwZW5kZW50IG9mIGl0J3MgdHJpZ2dlcmluZyBlbGVtZW50XG4gIFxuICAgIGlmICggIW1vZGFsICkgeyByZXR1cm47IH0gLy8gaW52YWxpZGF0ZVxuICBcbiAgICAvLyBzZXQgb3B0aW9uc1xuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICBcbiAgICB0aGlzW2tleWJvYXJkXSA9IG9wdGlvbnNba2V5Ym9hcmRdID09PSBmYWxzZSB8fCBtb2RhbFtnZXRBdHRyaWJ1dGVdKGRhdGFLZXlib2FyZCkgPT09ICdmYWxzZScgPyBmYWxzZSA6IHRydWU7XG4gICAgdGhpc1tiYWNrZHJvcF0gPSBvcHRpb25zW2JhY2tkcm9wXSA9PT0gc3RhdGljU3RyaW5nIHx8IG1vZGFsW2dldEF0dHJpYnV0ZV0oZGF0YWJhY2tkcm9wKSA9PT0gc3RhdGljU3RyaW5nID8gc3RhdGljU3RyaW5nIDogdHJ1ZTtcbiAgICB0aGlzW2JhY2tkcm9wXSA9IG9wdGlvbnNbYmFja2Ryb3BdID09PSBmYWxzZSB8fCBtb2RhbFtnZXRBdHRyaWJ1dGVdKGRhdGFiYWNrZHJvcCkgPT09ICdmYWxzZScgPyBmYWxzZSA6IHRoaXNbYmFja2Ryb3BdO1xuICAgIHRoaXNbY29udGVudF0gID0gb3B0aW9uc1tjb250ZW50XTsgLy8gSmF2YVNjcmlwdCBvbmx5XG4gIFxuICAgIC8vIGJpbmQsIGNvbnN0YW50cywgZXZlbnQgdGFyZ2V0cyBhbmQgb3RoZXIgdmFyc1xuICAgIHZhciBzZWxmID0gdGhpcywgcmVsYXRlZFRhcmdldCA9IG51bGwsXG4gICAgICBib2R5SXNPdmVyZmxvd2luZywgbW9kYWxJc092ZXJmbG93aW5nLCBzY3JvbGxiYXJXaWR0aCwgb3ZlcmxheSxcbiAgXG4gICAgICAvLyBhbHNvIGZpbmQgZml4ZWQtdG9wIC8gZml4ZWQtYm90dG9tIGl0ZW1zXG4gICAgICBmaXhlZEl0ZW1zID0gZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShIVE1MLGZpeGVkVG9wKS5jb25jYXQoZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShIVE1MLGZpeGVkQm90dG9tKSksXG4gIFxuICAgICAgLy8gcHJpdmF0ZSBtZXRob2RzXG4gICAgICBnZXRXaW5kb3dXaWR0aCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaHRtbFJlY3QgPSBIVE1MW2dldEJvdW5kaW5nQ2xpZW50UmVjdF0oKTtcbiAgICAgICAgcmV0dXJuIGdsb2JhbE9iamVjdFtpbm5lcldpZHRoXSB8fCAoaHRtbFJlY3RbcmlnaHRdIC0gTWF0aC5hYnMoaHRtbFJlY3RbbGVmdF0pKTtcbiAgICAgIH0sXG4gICAgICBzZXRTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBib2R5U3R5bGUgPSBET0NbYm9keV0uY3VycmVudFN0eWxlIHx8IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShET0NbYm9keV0pLFxuICAgICAgICAgICAgYm9keVBhZCA9IHBhcnNlSW50KChib2R5U3R5bGVbcGFkZGluZ1JpZ2h0XSksIDEwKSwgaXRlbVBhZDtcbiAgICAgICAgaWYgKGJvZHlJc092ZXJmbG93aW5nKSB7XG4gICAgICAgICAgRE9DW2JvZHldW3N0eWxlXVtwYWRkaW5nUmlnaHRdID0gKGJvZHlQYWQgKyBzY3JvbGxiYXJXaWR0aCkgKyAncHgnO1xuICAgICAgICAgIGlmIChmaXhlZEl0ZW1zW2xlbmd0aF0pe1xuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBmaXhlZEl0ZW1zW2xlbmd0aF07IGkrKykge1xuICAgICAgICAgICAgICBpdGVtUGFkID0gKGZpeGVkSXRlbXNbaV0uY3VycmVudFN0eWxlIHx8IGdsb2JhbE9iamVjdFtnZXRDb21wdXRlZFN0eWxlXShmaXhlZEl0ZW1zW2ldKSlbcGFkZGluZ1JpZ2h0XTtcbiAgICAgICAgICAgICAgZml4ZWRJdGVtc1tpXVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICggcGFyc2VJbnQoaXRlbVBhZCkgKyBzY3JvbGxiYXJXaWR0aCkgKyAncHgnO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHJlc2V0U2Nyb2xsYmFyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBET0NbYm9keV1bc3R5bGVdW3BhZGRpbmdSaWdodF0gPSAnJztcbiAgICAgICAgaWYgKGZpeGVkSXRlbXNbbGVuZ3RoXSl7XG4gICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBmaXhlZEl0ZW1zW2xlbmd0aF07IGkrKykge1xuICAgICAgICAgICAgZml4ZWRJdGVtc1tpXVtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIG1lYXN1cmVTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7IC8vIHRoeCB3YWxzaFxuICAgICAgICB2YXIgc2Nyb2xsRGl2ID0gRE9DW2NyZWF0ZUVsZW1lbnRdKCdkaXYnKSwgc2Nyb2xsQmFyV2lkdGg7XG4gICAgICAgIHNjcm9sbERpdi5jbGFzc05hbWUgPSBjb21wb25lbnQrJy1zY3JvbGxiYXItbWVhc3VyZSc7IC8vIHRoaXMgaXMgaGVyZSB0byBzdGF5XG4gICAgICAgIERPQ1tib2R5XVthcHBlbmRDaGlsZF0oc2Nyb2xsRGl2KTtcbiAgICAgICAgc2Nyb2xsQmFyV2lkdGggPSBzY3JvbGxEaXZbb2Zmc2V0V2lkdGhdIC0gc2Nyb2xsRGl2W2NsaWVudFdpZHRoXTtcbiAgICAgICAgRE9DW2JvZHldLnJlbW92ZUNoaWxkKHNjcm9sbERpdik7XG4gICAgICByZXR1cm4gc2Nyb2xsQmFyV2lkdGg7XG4gICAgICB9LFxuICAgICAgY2hlY2tTY3JvbGxiYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGJvZHlJc092ZXJmbG93aW5nID0gRE9DW2JvZHldW2NsaWVudFdpZHRoXSA8IGdldFdpbmRvd1dpZHRoKCk7XG4gICAgICAgIG1vZGFsSXNPdmVyZmxvd2luZyA9IG1vZGFsW3Njcm9sbEhlaWdodF0gPiBIVE1MW2NsaWVudEhlaWdodF07XG4gICAgICAgIHNjcm9sbGJhcldpZHRoID0gbWVhc3VyZVNjcm9sbGJhcigpO1xuICAgICAgfSxcbiAgICAgIGFkanVzdERpYWxvZyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdMZWZ0XSA9ICFib2R5SXNPdmVyZmxvd2luZyAmJiBtb2RhbElzT3ZlcmZsb3dpbmcgPyBzY3JvbGxiYXJXaWR0aCArICdweCcgOiAnJztcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdSaWdodF0gPSBib2R5SXNPdmVyZmxvd2luZyAmJiAhbW9kYWxJc092ZXJmbG93aW5nID8gc2Nyb2xsYmFyV2lkdGggKyAncHgnIDogJyc7XG4gICAgICB9LFxuICAgICAgcmVzZXRBZGp1c3RtZW50cyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbW9kYWxbc3R5bGVdW3BhZGRpbmdMZWZ0XSA9ICcnO1xuICAgICAgICBtb2RhbFtzdHlsZV1bcGFkZGluZ1JpZ2h0XSA9ICcnO1xuICAgICAgfSxcbiAgICAgIGNyZWF0ZU92ZXJsYXkgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgbW9kYWxPdmVybGF5ID0gMTtcbiAgICAgICAgXG4gICAgICAgIHZhciBuZXdPdmVybGF5ID0gRE9DW2NyZWF0ZUVsZW1lbnRdKCdkaXYnKTtcbiAgICAgICAgb3ZlcmxheSA9IHF1ZXJ5RWxlbWVudCgnLicrbW9kYWxCYWNrZHJvcFN0cmluZyk7XG4gIFxuICAgICAgICBpZiAoIG92ZXJsYXkgPT09IG51bGwgKSB7XG4gICAgICAgICAgbmV3T3ZlcmxheVtzZXRBdHRyaWJ1dGVdKCdjbGFzcycsbW9kYWxCYWNrZHJvcFN0cmluZysnIGZhZGUnKTtcbiAgICAgICAgICBvdmVybGF5ID0gbmV3T3ZlcmxheTtcbiAgICAgICAgICBET0NbYm9keV1bYXBwZW5kQ2hpbGRdKG92ZXJsYXkpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgcmVtb3ZlT3ZlcmxheSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgICAgICAgaWYgKCBvdmVybGF5ICYmIG92ZXJsYXkgIT09IG51bGwgJiYgdHlwZW9mIG92ZXJsYXkgPT09ICdvYmplY3QnICkge1xuICAgICAgICAgIG1vZGFsT3ZlcmxheSA9IDA7XG4gICAgICAgICAgRE9DW2JvZHldLnJlbW92ZUNoaWxkKG92ZXJsYXkpOyBvdmVybGF5ID0gbnVsbDtcbiAgICAgICAgfVxuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBoaWRkZW5FdmVudCwgY29tcG9uZW50KTsgICAgICBcbiAgICAgIH0sXG4gICAgICBrZXlkb3duSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihET0MsIGtleWRvd25FdmVudCwga2V5SGFuZGxlcik7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgb2ZmKERPQywga2V5ZG93bkV2ZW50LCBrZXlIYW5kbGVyKTtcbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHJlc2l6ZUhhbmRsZXJUb2dnbGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgICAgb24oZ2xvYmFsT2JqZWN0LCByZXNpemVFdmVudCwgc2VsZi51cGRhdGUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG9mZihnbG9iYWxPYmplY3QsIHJlc2l6ZUV2ZW50LCBzZWxmLnVwZGF0ZSk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBkaXNtaXNzSGFuZGxlclRvZ2dsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykpIHtcbiAgICAgICAgICBvbihtb2RhbCwgY2xpY2tFdmVudCwgZGlzbWlzc0hhbmRsZXIpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG9mZihtb2RhbCwgY2xpY2tFdmVudCwgZGlzbWlzc0hhbmRsZXIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgLy8gdHJpZ2dlcnNcbiAgICAgIHRyaWdnZXJTaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHNldEZvY3VzKG1vZGFsKTtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChtb2RhbCwgc2hvd25FdmVudCwgY29tcG9uZW50LCByZWxhdGVkVGFyZ2V0KTtcbiAgICAgIH0sXG4gICAgICB0cmlnZ2VySGlkZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV0uZGlzcGxheSA9ICcnO1xuICAgICAgICBlbGVtZW50ICYmIChzZXRGb2N1cyhlbGVtZW50KSk7XG4gICAgICAgIFxuICAgICAgICAoZnVuY3Rpb24oKXtcbiAgICAgICAgICBpZiAoIWdldEVsZW1lbnRzQnlDbGFzc05hbWUoRE9DLGNvbXBvbmVudCsnICcraW5DbGFzcylbMF0pIHtcbiAgICAgICAgICAgIHJlc2V0QWRqdXN0bWVudHMoKTtcbiAgICAgICAgICAgIHJlc2V0U2Nyb2xsYmFyKCk7XG4gICAgICAgICAgICByZW1vdmVDbGFzcyhET0NbYm9keV0sY29tcG9uZW50Kyctb3BlbicpO1xuICAgICAgICAgICAgb3ZlcmxheSAmJiBoYXNDbGFzcyhvdmVybGF5LCdmYWRlJykgPyAocmVtb3ZlQ2xhc3Mob3ZlcmxheSxpbkNsYXNzKSwgZW11bGF0ZVRyYW5zaXRpb25FbmQob3ZlcmxheSxyZW1vdmVPdmVybGF5KSkgXG4gICAgICAgICAgICA6IHJlbW92ZU92ZXJsYXkoKTtcbiAgXG4gICAgICAgICAgICByZXNpemVIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgICAgICBkaXNtaXNzSGFuZGxlclRvZ2dsZSgpO1xuICAgICAgICAgICAga2V5ZG93bkhhbmRsZXJUb2dnbGUoKTtcbiAgICAgICAgICB9XG4gICAgICAgIH0oKSk7XG4gICAgICB9LFxuICAgICAgLy8gaGFuZGxlcnNcbiAgICAgIGNsaWNrSGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGNsaWNrVGFyZ2V0ID0gZVt0YXJnZXRdO1xuICAgICAgICBjbGlja1RhcmdldCA9IGNsaWNrVGFyZ2V0W2hhc0F0dHJpYnV0ZV0oZGF0YVRhcmdldCkgfHwgY2xpY2tUYXJnZXRbaGFzQXR0cmlidXRlXSgnaHJlZicpID8gY2xpY2tUYXJnZXQgOiBjbGlja1RhcmdldFtwYXJlbnROb2RlXTtcbiAgICAgICAgaWYgKCBjbGlja1RhcmdldCA9PT0gZWxlbWVudCAmJiAhaGFzQ2xhc3MobW9kYWwsaW5DbGFzcykgKSB7XG4gICAgICAgICAgbW9kYWwubW9kYWxUcmlnZ2VyID0gZWxlbWVudDtcbiAgICAgICAgICByZWxhdGVkVGFyZ2V0ID0gZWxlbWVudDtcbiAgICAgICAgICBzZWxmLnNob3coKTtcbiAgICAgICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAga2V5SGFuZGxlciA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgdmFyIGtleSA9IGUud2hpY2ggfHwgZS5rZXlDb2RlOyAvLyBrZXlDb2RlIGZvciBJRThcbiAgICAgICAgaWYgKHNlbGZba2V5Ym9hcmRdICYmIGtleSA9PSAyNyAmJiBoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSkge1xuICAgICAgICAgIHNlbGYuaGlkZSgpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgZGlzbWlzc0hhbmRsZXIgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgIHZhciBjbGlja1RhcmdldCA9IGVbdGFyZ2V0XTtcbiAgICAgICAgaWYgKCBoYXNDbGFzcyhtb2RhbCxpbkNsYXNzKSAmJiAoY2xpY2tUYXJnZXRbcGFyZW50Tm9kZV1bZ2V0QXR0cmlidXRlXShkYXRhRGlzbWlzcykgPT09IGNvbXBvbmVudFxuICAgICAgICAgICAgfHwgY2xpY2tUYXJnZXRbZ2V0QXR0cmlidXRlXShkYXRhRGlzbWlzcykgPT09IGNvbXBvbmVudFxuICAgICAgICAgICAgfHwgKGNsaWNrVGFyZ2V0ID09PSBtb2RhbCAmJiBzZWxmW2JhY2tkcm9wXSAhPT0gc3RhdGljU3RyaW5nKSApICkge1xuICAgICAgICAgIHNlbGYuaGlkZSgpOyByZWxhdGVkVGFyZ2V0ID0gbnVsbDtcbiAgICAgICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgICB9XG4gICAgICB9O1xuICBcbiAgICAvLyBwdWJsaWMgbWV0aG9kc1xuICAgIHRoaXMudG9nZ2xlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpICkge3RoaXMuaGlkZSgpO30gZWxzZSB7dGhpcy5zaG93KCk7fVxuICAgIH07XG4gICAgdGhpcy5zaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBzaG93RXZlbnQsIGNvbXBvbmVudCwgcmVsYXRlZFRhcmdldCk7XG4gIFxuICAgICAgLy8gd2UgZWxlZ2FudGx5IGhpZGUgYW55IG9wZW5lZCBtb2RhbFxuICAgICAgdmFyIGN1cnJlbnRPcGVuID0gZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShET0MsY29tcG9uZW50KycgaW4nKVswXTtcbiAgICAgIGN1cnJlbnRPcGVuICYmIGN1cnJlbnRPcGVuICE9PSBtb2RhbCAmJiBjdXJyZW50T3Blbi5tb2RhbFRyaWdnZXJbc3RyaW5nTW9kYWxdLmhpZGUoKTtcbiAgXG4gICAgICBpZiAoIHRoaXNbYmFja2Ryb3BdICkge1xuICAgICAgICAhbW9kYWxPdmVybGF5ICYmIGNyZWF0ZU92ZXJsYXkoKTtcbiAgICAgIH1cbiAgXG4gICAgICBpZiAoIG92ZXJsYXkgJiYgbW9kYWxPdmVybGF5ICYmICFoYXNDbGFzcyhvdmVybGF5LGluQ2xhc3MpKSB7XG4gICAgICAgIG92ZXJsYXlbb2Zmc2V0V2lkdGhdOyAvLyBmb3JjZSByZWZsb3cgdG8gZW5hYmxlIHRyYXNpdGlvblxuICAgICAgICBvdmVybGF5RGVsYXkgPSBnZXRUcmFuc2l0aW9uRHVyYXRpb25Gcm9tRWxlbWVudChvdmVybGF5KTtcbiAgICAgICAgYWRkQ2xhc3Mob3ZlcmxheSxpbkNsYXNzKTtcbiAgICAgIH1cbiAgXG4gICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICBtb2RhbFtzdHlsZV0uZGlzcGxheSA9ICdibG9jayc7XG4gIFxuICAgICAgICBjaGVja1Njcm9sbGJhcigpO1xuICAgICAgICBzZXRTY3JvbGxiYXIoKTtcbiAgICAgICAgYWRqdXN0RGlhbG9nKCk7XG4gIFxuICAgICAgICBhZGRDbGFzcyhET0NbYm9keV0sY29tcG9uZW50Kyctb3BlbicpO1xuICAgICAgICBhZGRDbGFzcyhtb2RhbCxpbkNsYXNzKTtcbiAgICAgICAgbW9kYWxbc2V0QXR0cmlidXRlXShhcmlhSGlkZGVuLCBmYWxzZSk7XG4gICAgICAgIFxuICAgICAgICByZXNpemVIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgIGRpc21pc3NIYW5kbGVyVG9nZ2xlKCk7XG4gICAgICAgIGtleWRvd25IYW5kbGVyVG9nZ2xlKCk7XG4gIFxuICAgICAgICBoYXNDbGFzcyhtb2RhbCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQobW9kYWwsIHRyaWdnZXJTaG93KSA6IHRyaWdnZXJTaG93KCk7XG4gICAgICB9LCBzdXBwb3J0VHJhbnNpdGlvbnMgJiYgb3ZlcmxheSA/IG92ZXJsYXlEZWxheSA6IDApO1xuICAgIH07XG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKG1vZGFsLCBoaWRlRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICBvdmVybGF5ID0gcXVlcnlFbGVtZW50KCcuJyttb2RhbEJhY2tkcm9wU3RyaW5nKTtcbiAgICAgIG92ZXJsYXlEZWxheSA9IG92ZXJsYXkgJiYgZ2V0VHJhbnNpdGlvbkR1cmF0aW9uRnJvbUVsZW1lbnQob3ZlcmxheSk7XG4gIFxuICAgICAgcmVtb3ZlQ2xhc3MobW9kYWwsaW5DbGFzcyk7XG4gICAgICBtb2RhbFtzZXRBdHRyaWJ1dGVdKGFyaWFIaWRkZW4sIHRydWUpO1xuICBcbiAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgaGFzQ2xhc3MobW9kYWwsJ2ZhZGUnKSA/IGVtdWxhdGVUcmFuc2l0aW9uRW5kKG1vZGFsLCB0cmlnZ2VySGlkZSkgOiB0cmlnZ2VySGlkZSgpO1xuICAgICAgfSwgc3VwcG9ydFRyYW5zaXRpb25zICYmIG92ZXJsYXkgPyBvdmVybGF5RGVsYXkgOiAwKTtcbiAgICB9O1xuICAgIHRoaXMuc2V0Q29udGVudCA9IGZ1bmN0aW9uKCBjb250ZW50ICkge1xuICAgICAgcXVlcnlFbGVtZW50KCcuJytjb21wb25lbnQrJy1jb250ZW50Jyxtb2RhbClbaW5uZXJIVE1MXSA9IGNvbnRlbnQ7XG4gICAgfTtcbiAgICB0aGlzLnVwZGF0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKGhhc0NsYXNzKG1vZGFsLGluQ2xhc3MpKSB7XG4gICAgICAgIGNoZWNrU2Nyb2xsYmFyKCk7XG4gICAgICAgIHNldFNjcm9sbGJhcigpO1xuICAgICAgICBhZGp1c3REaWFsb2coKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgb3ZlciBhbmQgb3ZlclxuICAgIC8vIG1vZGFsIGlzIGluZGVwZW5kZW50IG9mIGEgdHJpZ2dlcmluZyBlbGVtZW50XG4gICAgaWYgKCAhIWVsZW1lbnQgJiYgIShzdHJpbmdNb2RhbCBpbiBlbGVtZW50KSApIHtcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIGNsaWNrSGFuZGxlcik7XG4gICAgfVxuICAgIGlmICggISFzZWxmW2NvbnRlbnRdICkgeyBzZWxmLnNldENvbnRlbnQoIHNlbGZbY29udGVudF0gKTsgfVxuICAgICEhZWxlbWVudCAmJiAoZWxlbWVudFtzdHJpbmdNb2RhbF0gPSBzZWxmKTtcbiAgfTtcbiAgXG4gIC8vIERBVEEgQVBJXG4gIHN1cHBvcnRzW3B1c2hdKCBbIHN0cmluZ01vZGFsLCBNb2RhbCwgJ1snK2RhdGFUb2dnbGUrJz1cIm1vZGFsXCJdJyBdICk7XG4gIFxuICAvKiBOYXRpdmUgSmF2YXNjcmlwdCBmb3IgQm9vdHN0cmFwIDMgfCBDb2xsYXBzZVxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXG4gIFxuICAvLyBDT0xMQVBTRSBERUZJTklUSU9OXG4gIC8vID09PT09PT09PT09PT09PT09PT1cbiAgdmFyIENvbGxhcHNlID0gZnVuY3Rpb24oIGVsZW1lbnQsIG9wdGlvbnMgKSB7XG4gIFxuICAgIC8vIGluaXRpYWxpemF0aW9uIGVsZW1lbnRcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBzZXQgb3B0aW9uc1xuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICBcbiAgICAvLyBldmVudCB0YXJnZXRzIGFuZCBjb25zdGFudHNcbiAgICB2YXIgYWNjb3JkaW9uID0gbnVsbCwgY29sbGFwc2UgPSBudWxsLCBzZWxmID0gdGhpcyxcbiAgICAgIGFjY29yZGlvbkRhdGEgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oJ2RhdGEtcGFyZW50JyksXG4gICAgICBhY3RpdmVDb2xsYXBzZSwgYWN0aXZlRWxlbWVudCxcbiAgXG4gICAgICAvLyBjb21wb25lbnQgc3RyaW5nc1xuICAgICAgY29tcG9uZW50ID0gJ2NvbGxhcHNlJyxcbiAgICAgIGNvbGxhcHNlZCA9ICdjb2xsYXBzZWQnLFxuICAgICAgaXNBbmltYXRpbmcgPSAnaXNBbmltYXRpbmcnLFxuICBcbiAgICAgIC8vIHByaXZhdGUgbWV0aG9kc1xuICAgICAgb3BlbkFjdGlvbiA9IGZ1bmN0aW9uKGNvbGxhcHNlRWxlbWVudCx0b2dnbGUpIHtcbiAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChjb2xsYXBzZUVsZW1lbnQsIHNob3dFdmVudCwgY29tcG9uZW50KTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IHRydWU7XG4gICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgcmVtb3ZlQ2xhc3MoY29sbGFwc2VFbGVtZW50LGNvbXBvbmVudCk7XG4gICAgICAgIGNvbGxhcHNlRWxlbWVudFtzdHlsZV1baGVpZ2h0XSA9IGNvbGxhcHNlRWxlbWVudFtzY3JvbGxIZWlnaHRdICsgJ3B4JztcbiAgICAgICAgXG4gICAgICAgIGVtdWxhdGVUcmFuc2l0aW9uRW5kKGNvbGxhcHNlRWxlbWVudCwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W2lzQW5pbWF0aW5nXSA9IGZhbHNlO1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwndHJ1ZScpO1xuICAgICAgICAgIHRvZ2dsZVtzZXRBdHRyaWJ1dGVdKGFyaWFFeHBhbmRlZCwndHJ1ZScpOyAgICAgICAgICBcbiAgICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29sbGFwc2luZyk7XG4gICAgICAgICAgYWRkQ2xhc3MoY29sbGFwc2VFbGVtZW50LCBjb21wb25lbnQpO1xuICAgICAgICAgIGFkZENsYXNzKGNvbGxhcHNlRWxlbWVudCwgaW5DbGFzcyk7XG4gICAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gJyc7XG4gICAgICAgICAgYm9vdHN0cmFwQ3VzdG9tRXZlbnQuY2FsbChjb2xsYXBzZUVsZW1lbnQsIHNob3duRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIH0pO1xuICAgICAgfSxcbiAgICAgIGNsb3NlQWN0aW9uID0gZnVuY3Rpb24oY29sbGFwc2VFbGVtZW50LHRvZ2dsZSkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgaGlkZUV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbaXNBbmltYXRpbmddID0gdHJ1ZTtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gY29sbGFwc2VFbGVtZW50W3Njcm9sbEhlaWdodF0gKyAncHgnOyAvLyBzZXQgaGVpZ2h0IGZpcnN0XG4gICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb21wb25lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGluQ2xhc3MpO1xuICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsIGNvbGxhcHNpbmcpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnRbb2Zmc2V0V2lkdGhdOyAvLyBmb3JjZSByZWZsb3cgdG8gZW5hYmxlIHRyYW5zaXRpb25cbiAgICAgICAgY29sbGFwc2VFbGVtZW50W3N0eWxlXVtoZWlnaHRdID0gJzBweCc7XG4gICAgICAgIFxuICAgICAgICBlbXVsYXRlVHJhbnNpdGlvbkVuZChjb2xsYXBzZUVsZW1lbnQsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIGNvbGxhcHNlRWxlbWVudFtpc0FuaW1hdGluZ10gPSBmYWxzZTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc2V0QXR0cmlidXRlXShhcmlhRXhwYW5kZWQsJ2ZhbHNlJyk7XG4gICAgICAgICAgdG9nZ2xlW3NldEF0dHJpYnV0ZV0oYXJpYUV4cGFuZGVkLCdmYWxzZScpO1xuICAgICAgICAgIHJlbW92ZUNsYXNzKGNvbGxhcHNlRWxlbWVudCxjb2xsYXBzaW5nKTtcbiAgICAgICAgICBhZGRDbGFzcyhjb2xsYXBzZUVsZW1lbnQsY29tcG9uZW50KTtcbiAgICAgICAgICBjb2xsYXBzZUVsZW1lbnRbc3R5bGVdW2hlaWdodF0gPSAnJztcbiAgICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGNvbGxhcHNlRWxlbWVudCwgaGlkZGVuRXZlbnQsIGNvbXBvbmVudCk7XG4gICAgICAgIH0pO1xuICAgICAgfSxcbiAgICAgIGdldFRhcmdldCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaHJlZiA9IGVsZW1lbnQuaHJlZiAmJiBlbGVtZW50W2dldEF0dHJpYnV0ZV0oJ2hyZWYnKSxcbiAgICAgICAgICBwYXJlbnQgPSBlbGVtZW50W2dldEF0dHJpYnV0ZV0oZGF0YVRhcmdldCksXG4gICAgICAgICAgaWQgPSBocmVmIHx8ICggcGFyZW50ICYmIHBhcmVudC5jaGFyQXQoMCkgPT09ICcjJyApICYmIHBhcmVudDtcbiAgICAgICAgcmV0dXJuIGlkICYmIHF1ZXJ5RWxlbWVudChpZCk7XG4gICAgICB9O1xuICAgIFxuICAgIC8vIHB1YmxpYyBtZXRob2RzXG4gICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbihlKSB7XG4gICAgICBlW3ByZXZlbnREZWZhdWx0XSgpO1xuICAgICAgaWYgKCFoYXNDbGFzcyhjb2xsYXBzZSxpbkNsYXNzKSkgeyBzZWxmLnNob3coKTsgfSBcbiAgICAgIGVsc2UgeyBzZWxmLmhpZGUoKTsgfVxuICAgIH07XG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGNvbGxhcHNlW2lzQW5pbWF0aW5nXSApIHJldHVybjtcbiAgICAgIGNsb3NlQWN0aW9uKGNvbGxhcHNlLGVsZW1lbnQpO1xuICAgICAgYWRkQ2xhc3MoZWxlbWVudCxjb2xsYXBzZWQpO1xuICAgIH07XG4gICAgdGhpcy5zaG93ID0gZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIGFjY29yZGlvbiApIHtcbiAgICAgICAgYWN0aXZlQ29sbGFwc2UgPSBxdWVyeUVsZW1lbnQoJy4nK2NvbXBvbmVudCsnLicraW5DbGFzcyxhY2NvcmRpb24pO1xuICAgICAgICBhY3RpdmVFbGVtZW50ID0gYWN0aXZlQ29sbGFwc2UgJiYgKHF1ZXJ5RWxlbWVudCgnWycrZGF0YVRvZ2dsZSsnPVwiJytjb21wb25lbnQrJ1wiXVsnK2RhdGFUYXJnZXQrJz1cIiMnK2FjdGl2ZUNvbGxhcHNlLmlkKydcIl0nLCBhY2NvcmRpb24pXG4gICAgICAgICAgICAgICAgICAgICAgfHwgcXVlcnlFbGVtZW50KCdbJytkYXRhVG9nZ2xlKyc9XCInK2NvbXBvbmVudCsnXCJdW2hyZWY9XCIjJythY3RpdmVDb2xsYXBzZS5pZCsnXCJdJyxhY2NvcmRpb24pICk7XG4gICAgICB9XG4gIFxuICAgICAgaWYgKCAhY29sbGFwc2VbaXNBbmltYXRpbmddIHx8IGFjdGl2ZUNvbGxhcHNlICYmICFhY3RpdmVDb2xsYXBzZVtpc0FuaW1hdGluZ10gKSB7XG4gICAgICAgIGlmICggYWN0aXZlRWxlbWVudCAmJiBhY3RpdmVDb2xsYXBzZSAhPT0gY29sbGFwc2UgKSB7XG4gICAgICAgICAgY2xvc2VBY3Rpb24oYWN0aXZlQ29sbGFwc2UsYWN0aXZlRWxlbWVudCk7XG4gICAgICAgICAgYWRkQ2xhc3MoYWN0aXZlRWxlbWVudCxjb2xsYXBzZWQpOyBcbiAgICAgICAgfVxuICAgICAgICBvcGVuQWN0aW9uKGNvbGxhcHNlLGVsZW1lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhlbGVtZW50LGNvbGxhcHNlZCk7XG4gICAgICB9XG4gICAgfTtcbiAgXG4gICAgLy8gaW5pdFxuICAgIGlmICggIShzdHJpbmdDb2xsYXBzZSBpbiBlbGVtZW50ICkgKSB7IC8vIHByZXZlbnQgYWRkaW5nIGV2ZW50IGhhbmRsZXJzIHR3aWNlXG4gICAgICBvbihlbGVtZW50LCBjbGlja0V2ZW50LCBzZWxmLnRvZ2dsZSk7XG4gICAgfVxuICAgIGNvbGxhcHNlID0gZ2V0VGFyZ2V0KCk7XG4gICAgY29sbGFwc2VbaXNBbmltYXRpbmddID0gZmFsc2U7ICAvLyB3aGVuIHRydWUgaXQgd2lsbCBwcmV2ZW50IGNsaWNrIGhhbmRsZXJzICBcbiAgICBhY2NvcmRpb24gPSBxdWVyeUVsZW1lbnQob3B0aW9ucy5wYXJlbnQpIHx8IGFjY29yZGlvbkRhdGEgJiYgZ2V0Q2xvc2VzdChlbGVtZW50LCBhY2NvcmRpb25EYXRhKTtcbiAgICBlbGVtZW50W3N0cmluZ0NvbGxhcHNlXSA9IHNlbGY7XG4gIH07XG4gIFxuICAvLyBDT0xMQVBTRSBEQVRBIEFQSVxuICAvLyA9PT09PT09PT09PT09PT09PVxuICBzdXBwb3J0c1twdXNoXSggWyBzdHJpbmdDb2xsYXBzZSwgQ29sbGFwc2UsICdbJytkYXRhVG9nZ2xlKyc9XCJjb2xsYXBzZVwiXScgXSApO1xuICBcbiAgXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IEFsZXJ0XG4gIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0qL1xuICBcbiAgLy8gQUxFUlQgREVGSU5JVElPTlxuICAvLyA9PT09PT09PT09PT09PT09XG4gIHZhciBBbGVydCA9IGZ1bmN0aW9uKCBlbGVtZW50ICkge1xuICAgIFxuICAgIC8vIGluaXRpYWxpemF0aW9uIGVsZW1lbnRcbiAgICBlbGVtZW50ID0gcXVlcnlFbGVtZW50KGVsZW1lbnQpO1xuICBcbiAgICAvLyBiaW5kLCB0YXJnZXQgYWxlcnQsIGR1cmF0aW9uIGFuZCBzdHVmZlxuICAgIHZhciBzZWxmID0gdGhpcywgY29tcG9uZW50ID0gJ2FsZXJ0JyxcbiAgICAgIGFsZXJ0ID0gZ2V0Q2xvc2VzdChlbGVtZW50LCcuJytjb21wb25lbnQpLFxuICAgICAgdHJpZ2dlckhhbmRsZXIgPSBmdW5jdGlvbigpeyBoYXNDbGFzcyhhbGVydCwnZmFkZScpID8gZW11bGF0ZVRyYW5zaXRpb25FbmQoYWxlcnQsdHJhbnNpdGlvbkVuZEhhbmRsZXIpIDogdHJhbnNpdGlvbkVuZEhhbmRsZXIoKTsgfSxcbiAgICAgIC8vIGhhbmRsZXJzXG4gICAgICBjbGlja0hhbmRsZXIgPSBmdW5jdGlvbihlKXtcbiAgICAgICAgYWxlcnQgPSBnZXRDbG9zZXN0KGVbdGFyZ2V0XSwnLicrY29tcG9uZW50KTtcbiAgICAgICAgZWxlbWVudCA9IHF1ZXJ5RWxlbWVudCgnWycrZGF0YURpc21pc3MrJz1cIicrY29tcG9uZW50KydcIl0nLGFsZXJ0KTtcbiAgICAgICAgZWxlbWVudCAmJiBhbGVydCAmJiAoZWxlbWVudCA9PT0gZVt0YXJnZXRdIHx8IGVsZW1lbnRbY29udGFpbnNdKGVbdGFyZ2V0XSkpICYmIHNlbGYuY2xvc2UoKTtcbiAgICAgIH0sXG4gICAgICB0cmFuc2l0aW9uRW5kSGFuZGxlciA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIGJvb3RzdHJhcEN1c3RvbUV2ZW50LmNhbGwoYWxlcnQsIGNsb3NlZEV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICBvZmYoZWxlbWVudCwgY2xpY2tFdmVudCwgY2xpY2tIYW5kbGVyKTsgLy8gZGV0YWNoIGl0J3MgbGlzdGVuZXJcbiAgICAgICAgYWxlcnRbcGFyZW50Tm9kZV0ucmVtb3ZlQ2hpbGQoYWxlcnQpO1xuICAgICAgfTtcbiAgICBcbiAgICAvLyBwdWJsaWMgbWV0aG9kXG4gICAgdGhpcy5jbG9zZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKCBhbGVydCAmJiBlbGVtZW50ICYmIGhhc0NsYXNzKGFsZXJ0LGluQ2xhc3MpICkge1xuICAgICAgICBib290c3RyYXBDdXN0b21FdmVudC5jYWxsKGFsZXJ0LCBjbG9zZUV2ZW50LCBjb21wb25lbnQpO1xuICAgICAgICByZW1vdmVDbGFzcyhhbGVydCxpbkNsYXNzKTtcbiAgICAgICAgYWxlcnQgJiYgdHJpZ2dlckhhbmRsZXIoKTtcbiAgICAgIH1cbiAgICB9O1xuICBcbiAgICAvLyBpbml0XG4gICAgaWYgKCAhKHN0cmluZ0FsZXJ0IGluIGVsZW1lbnQgKSApIHsgLy8gcHJldmVudCBhZGRpbmcgZXZlbnQgaGFuZGxlcnMgdHdpY2VcbiAgICAgIG9uKGVsZW1lbnQsIGNsaWNrRXZlbnQsIGNsaWNrSGFuZGxlcik7XG4gICAgfVxuICAgIGVsZW1lbnRbc3RyaW5nQWxlcnRdID0gc2VsZjtcbiAgfTtcbiAgXG4gIC8vIEFMRVJUIERBVEEgQVBJXG4gIC8vID09PT09PT09PT09PT09XG4gIHN1cHBvcnRzW3B1c2hdKFtzdHJpbmdBbGVydCwgQWxlcnQsICdbJytkYXRhRGlzbWlzcysnPVwiYWxlcnRcIl0nXSk7XG4gIFxuICBcbiAgXG4gIFxyXG4gIC8qIE5hdGl2ZSBKYXZhc2NyaXB0IGZvciBCb290c3RyYXAgMyB8IEluaXRpYWxpemUgRGF0YSBBUElcclxuICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXHJcbiAgdmFyIGluaXRpYWxpemVEYXRhQVBJID0gZnVuY3Rpb24oIGNvbnN0cnVjdG9yLCBjb2xsZWN0aW9uICl7XHJcbiAgICAgIGZvciAodmFyIGk9MCwgbD1jb2xsZWN0aW9uW2xlbmd0aF07IGk8bDsgaSsrKSB7XHJcbiAgICAgICAgbmV3IGNvbnN0cnVjdG9yKGNvbGxlY3Rpb25baV0pO1xyXG4gICAgICB9XHJcbiAgICB9LFxyXG4gICAgaW5pdENhbGxiYWNrID0gQlNOLmluaXRDYWxsYmFjayA9IGZ1bmN0aW9uKGxvb2tVcCl7XHJcbiAgICAgIGxvb2tVcCA9IGxvb2tVcCB8fCBET0M7XHJcbiAgICAgIGZvciAodmFyIGk9MCwgbD1zdXBwb3J0c1tsZW5ndGhdOyBpPGw7IGkrKykge1xyXG4gICAgICAgIGluaXRpYWxpemVEYXRhQVBJKCBzdXBwb3J0c1tpXVsxXSwgbG9va1VwW3F1ZXJ5U2VsZWN0b3JBbGxdIChzdXBwb3J0c1tpXVsyXSkgKTtcclxuICAgICAgfVxyXG4gICAgfTtcclxuICBcclxuICAvLyBidWxrIGluaXRpYWxpemUgYWxsIGNvbXBvbmVudHNcclxuICBET0NbYm9keV0gPyBpbml0Q2FsbGJhY2soKSA6IG9uKCBET0MsICdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24oKXsgaW5pdENhbGxiYWNrKCk7IH0gKTtcclxuICBcbiAgcmV0dXJuIHtcbiAgICBNb2RhbDogTW9kYWwsXG4gICAgQ29sbGFwc2U6IENvbGxhcHNlLFxuICAgIEFsZXJ0OiBBbGVydFxuICB9O1xufSkpO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvYm9vdHN0cmFwLm5hdGl2ZS9kaXN0L2Jvb3RzdHJhcC1uYXRpdmUuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2Jvb3RzdHJhcC5uYXRpdmUvZGlzdC9ib290c3RyYXAtbmF0aXZlLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qXG4gKiBjbGFzc0xpc3QuanM6IENyb3NzLWJyb3dzZXIgZnVsbCBlbGVtZW50LmNsYXNzTGlzdCBpbXBsZW1lbnRhdGlvbi5cbiAqIDEuMS4yMDE3MDQyN1xuICpcbiAqIEJ5IEVsaSBHcmV5LCBodHRwOi8vZWxpZ3JleS5jb21cbiAqIExpY2Vuc2U6IERlZGljYXRlZCB0byB0aGUgcHVibGljIGRvbWFpbi5cbiAqICAgU2VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbGlncmV5L2NsYXNzTGlzdC5qcy9ibG9iL21hc3Rlci9MSUNFTlNFLm1kXG4gKi9cblxuLypnbG9iYWwgc2VsZiwgZG9jdW1lbnQsIERPTUV4Y2VwdGlvbiAqL1xuXG4vKiEgQHNvdXJjZSBodHRwOi8vcHVybC5lbGlncmV5LmNvbS9naXRodWIvY2xhc3NMaXN0LmpzL2Jsb2IvbWFzdGVyL2NsYXNzTGlzdC5qcyAqL1xuXG5pZiAoXCJkb2N1bWVudFwiIGluIHdpbmRvdy5zZWxmKSB7XG5cbi8vIEZ1bGwgcG9seWZpbGwgZm9yIGJyb3dzZXJzIHdpdGggbm8gY2xhc3NMaXN0IHN1cHBvcnRcbi8vIEluY2x1ZGluZyBJRSA8IEVkZ2UgbWlzc2luZyBTVkdFbGVtZW50LmNsYXNzTGlzdFxuaWYgKCEoXCJjbGFzc0xpc3RcIiBpbiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiX1wiKSkgXG5cdHx8IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnROUyAmJiAhKFwiY2xhc3NMaXN0XCIgaW4gZG9jdW1lbnQuY3JlYXRlRWxlbWVudE5TKFwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIixcImdcIikpKSB7XG5cbihmdW5jdGlvbiAodmlldykge1xuXG5cInVzZSBzdHJpY3RcIjtcblxuaWYgKCEoJ0VsZW1lbnQnIGluIHZpZXcpKSByZXR1cm47XG5cbnZhclxuXHQgIGNsYXNzTGlzdFByb3AgPSBcImNsYXNzTGlzdFwiXG5cdCwgcHJvdG9Qcm9wID0gXCJwcm90b3R5cGVcIlxuXHQsIGVsZW1DdHJQcm90byA9IHZpZXcuRWxlbWVudFtwcm90b1Byb3BdXG5cdCwgb2JqQ3RyID0gT2JqZWN0XG5cdCwgc3RyVHJpbSA9IFN0cmluZ1twcm90b1Byb3BdLnRyaW0gfHwgZnVuY3Rpb24gKCkge1xuXHRcdHJldHVybiB0aGlzLnJlcGxhY2UoL15cXHMrfFxccyskL2csIFwiXCIpO1xuXHR9XG5cdCwgYXJySW5kZXhPZiA9IEFycmF5W3Byb3RvUHJvcF0uaW5kZXhPZiB8fCBmdW5jdGlvbiAoaXRlbSkge1xuXHRcdHZhclxuXHRcdFx0ICBpID0gMFxuXHRcdFx0LCBsZW4gPSB0aGlzLmxlbmd0aFxuXHRcdDtcblx0XHRmb3IgKDsgaSA8IGxlbjsgaSsrKSB7XG5cdFx0XHRpZiAoaSBpbiB0aGlzICYmIHRoaXNbaV0gPT09IGl0ZW0pIHtcblx0XHRcdFx0cmV0dXJuIGk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybiAtMTtcblx0fVxuXHQvLyBWZW5kb3JzOiBwbGVhc2UgYWxsb3cgY29udGVudCBjb2RlIHRvIGluc3RhbnRpYXRlIERPTUV4Y2VwdGlvbnNcblx0LCBET01FeCA9IGZ1bmN0aW9uICh0eXBlLCBtZXNzYWdlKSB7XG5cdFx0dGhpcy5uYW1lID0gdHlwZTtcblx0XHR0aGlzLmNvZGUgPSBET01FeGNlcHRpb25bdHlwZV07XG5cdFx0dGhpcy5tZXNzYWdlID0gbWVzc2FnZTtcblx0fVxuXHQsIGNoZWNrVG9rZW5BbmRHZXRJbmRleCA9IGZ1bmN0aW9uIChjbGFzc0xpc3QsIHRva2VuKSB7XG5cdFx0aWYgKHRva2VuID09PSBcIlwiKSB7XG5cdFx0XHR0aHJvdyBuZXcgRE9NRXgoXG5cdFx0XHRcdCAgXCJTWU5UQVhfRVJSXCJcblx0XHRcdFx0LCBcIkFuIGludmFsaWQgb3IgaWxsZWdhbCBzdHJpbmcgd2FzIHNwZWNpZmllZFwiXG5cdFx0XHQpO1xuXHRcdH1cblx0XHRpZiAoL1xccy8udGVzdCh0b2tlbikpIHtcblx0XHRcdHRocm93IG5ldyBET01FeChcblx0XHRcdFx0ICBcIklOVkFMSURfQ0hBUkFDVEVSX0VSUlwiXG5cdFx0XHRcdCwgXCJTdHJpbmcgY29udGFpbnMgYW4gaW52YWxpZCBjaGFyYWN0ZXJcIlxuXHRcdFx0KTtcblx0XHR9XG5cdFx0cmV0dXJuIGFyckluZGV4T2YuY2FsbChjbGFzc0xpc3QsIHRva2VuKTtcblx0fVxuXHQsIENsYXNzTGlzdCA9IGZ1bmN0aW9uIChlbGVtKSB7XG5cdFx0dmFyXG5cdFx0XHQgIHRyaW1tZWRDbGFzc2VzID0gc3RyVHJpbS5jYWxsKGVsZW0uZ2V0QXR0cmlidXRlKFwiY2xhc3NcIikgfHwgXCJcIilcblx0XHRcdCwgY2xhc3NlcyA9IHRyaW1tZWRDbGFzc2VzID8gdHJpbW1lZENsYXNzZXMuc3BsaXQoL1xccysvKSA6IFtdXG5cdFx0XHQsIGkgPSAwXG5cdFx0XHQsIGxlbiA9IGNsYXNzZXMubGVuZ3RoXG5cdFx0O1xuXHRcdGZvciAoOyBpIDwgbGVuOyBpKyspIHtcblx0XHRcdHRoaXMucHVzaChjbGFzc2VzW2ldKTtcblx0XHR9XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lID0gZnVuY3Rpb24gKCkge1xuXHRcdFx0ZWxlbS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCB0aGlzLnRvU3RyaW5nKCkpO1xuXHRcdH07XG5cdH1cblx0LCBjbGFzc0xpc3RQcm90byA9IENsYXNzTGlzdFtwcm90b1Byb3BdID0gW11cblx0LCBjbGFzc0xpc3RHZXR0ZXIgPSBmdW5jdGlvbiAoKSB7XG5cdFx0cmV0dXJuIG5ldyBDbGFzc0xpc3QodGhpcyk7XG5cdH1cbjtcbi8vIE1vc3QgRE9NRXhjZXB0aW9uIGltcGxlbWVudGF0aW9ucyBkb24ndCBhbGxvdyBjYWxsaW5nIERPTUV4Y2VwdGlvbidzIHRvU3RyaW5nKClcbi8vIG9uIG5vbi1ET01FeGNlcHRpb25zLiBFcnJvcidzIHRvU3RyaW5nKCkgaXMgc3VmZmljaWVudCBoZXJlLlxuRE9NRXhbcHJvdG9Qcm9wXSA9IEVycm9yW3Byb3RvUHJvcF07XG5jbGFzc0xpc3RQcm90by5pdGVtID0gZnVuY3Rpb24gKGkpIHtcblx0cmV0dXJuIHRoaXNbaV0gfHwgbnVsbDtcbn07XG5jbGFzc0xpc3RQcm90by5jb250YWlucyA9IGZ1bmN0aW9uICh0b2tlbikge1xuXHR0b2tlbiArPSBcIlwiO1xuXHRyZXR1cm4gY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKSAhPT0gLTE7XG59O1xuY2xhc3NMaXN0UHJvdG8uYWRkID0gZnVuY3Rpb24gKCkge1xuXHR2YXJcblx0XHQgIHRva2VucyA9IGFyZ3VtZW50c1xuXHRcdCwgaSA9IDBcblx0XHQsIGwgPSB0b2tlbnMubGVuZ3RoXG5cdFx0LCB0b2tlblxuXHRcdCwgdXBkYXRlZCA9IGZhbHNlXG5cdDtcblx0ZG8ge1xuXHRcdHRva2VuID0gdG9rZW5zW2ldICsgXCJcIjtcblx0XHRpZiAoY2hlY2tUb2tlbkFuZEdldEluZGV4KHRoaXMsIHRva2VuKSA9PT0gLTEpIHtcblx0XHRcdHRoaXMucHVzaCh0b2tlbik7XG5cdFx0XHR1cGRhdGVkID0gdHJ1ZTtcblx0XHR9XG5cdH1cblx0d2hpbGUgKCsraSA8IGwpO1xuXG5cdGlmICh1cGRhdGVkKSB7XG5cdFx0dGhpcy5fdXBkYXRlQ2xhc3NOYW1lKCk7XG5cdH1cbn07XG5jbGFzc0xpc3RQcm90by5yZW1vdmUgPSBmdW5jdGlvbiAoKSB7XG5cdHZhclxuXHRcdCAgdG9rZW5zID0gYXJndW1lbnRzXG5cdFx0LCBpID0gMFxuXHRcdCwgbCA9IHRva2Vucy5sZW5ndGhcblx0XHQsIHRva2VuXG5cdFx0LCB1cGRhdGVkID0gZmFsc2Vcblx0XHQsIGluZGV4XG5cdDtcblx0ZG8ge1xuXHRcdHRva2VuID0gdG9rZW5zW2ldICsgXCJcIjtcblx0XHRpbmRleCA9IGNoZWNrVG9rZW5BbmRHZXRJbmRleCh0aGlzLCB0b2tlbik7XG5cdFx0d2hpbGUgKGluZGV4ICE9PSAtMSkge1xuXHRcdFx0dGhpcy5zcGxpY2UoaW5kZXgsIDEpO1xuXHRcdFx0dXBkYXRlZCA9IHRydWU7XG5cdFx0XHRpbmRleCA9IGNoZWNrVG9rZW5BbmRHZXRJbmRleCh0aGlzLCB0b2tlbik7XG5cdFx0fVxuXHR9XG5cdHdoaWxlICgrK2kgPCBsKTtcblxuXHRpZiAodXBkYXRlZCkge1xuXHRcdHRoaXMuX3VwZGF0ZUNsYXNzTmFtZSgpO1xuXHR9XG59O1xuY2xhc3NMaXN0UHJvdG8udG9nZ2xlID0gZnVuY3Rpb24gKHRva2VuLCBmb3JjZSkge1xuXHR0b2tlbiArPSBcIlwiO1xuXG5cdHZhclxuXHRcdCAgcmVzdWx0ID0gdGhpcy5jb250YWlucyh0b2tlbilcblx0XHQsIG1ldGhvZCA9IHJlc3VsdCA/XG5cdFx0XHRmb3JjZSAhPT0gdHJ1ZSAmJiBcInJlbW92ZVwiXG5cdFx0OlxuXHRcdFx0Zm9yY2UgIT09IGZhbHNlICYmIFwiYWRkXCJcblx0O1xuXG5cdGlmIChtZXRob2QpIHtcblx0XHR0aGlzW21ldGhvZF0odG9rZW4pO1xuXHR9XG5cblx0aWYgKGZvcmNlID09PSB0cnVlIHx8IGZvcmNlID09PSBmYWxzZSkge1xuXHRcdHJldHVybiBmb3JjZTtcblx0fSBlbHNlIHtcblx0XHRyZXR1cm4gIXJlc3VsdDtcblx0fVxufTtcbmNsYXNzTGlzdFByb3RvLnRvU3RyaW5nID0gZnVuY3Rpb24gKCkge1xuXHRyZXR1cm4gdGhpcy5qb2luKFwiIFwiKTtcbn07XG5cbmlmIChvYmpDdHIuZGVmaW5lUHJvcGVydHkpIHtcblx0dmFyIGNsYXNzTGlzdFByb3BEZXNjID0ge1xuXHRcdCAgZ2V0OiBjbGFzc0xpc3RHZXR0ZXJcblx0XHQsIGVudW1lcmFibGU6IHRydWVcblx0XHQsIGNvbmZpZ3VyYWJsZTogdHJ1ZVxuXHR9O1xuXHR0cnkge1xuXHRcdG9iakN0ci5kZWZpbmVQcm9wZXJ0eShlbGVtQ3RyUHJvdG8sIGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdFByb3BEZXNjKTtcblx0fSBjYXRjaCAoZXgpIHsgLy8gSUUgOCBkb2Vzbid0IHN1cHBvcnQgZW51bWVyYWJsZTp0cnVlXG5cdFx0Ly8gYWRkaW5nIHVuZGVmaW5lZCB0byBmaWdodCB0aGlzIGlzc3VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbGlncmV5L2NsYXNzTGlzdC5qcy9pc3N1ZXMvMzZcblx0XHQvLyBtb2Rlcm5pZSBJRTgtTVNXNyBtYWNoaW5lIGhhcyBJRTggOC4wLjYwMDEuMTg3MDIgYW5kIGlzIGFmZmVjdGVkXG5cdFx0aWYgKGV4Lm51bWJlciA9PT0gdW5kZWZpbmVkIHx8IGV4Lm51bWJlciA9PT0gLTB4N0ZGNUVDNTQpIHtcblx0XHRcdGNsYXNzTGlzdFByb3BEZXNjLmVudW1lcmFibGUgPSBmYWxzZTtcblx0XHRcdG9iakN0ci5kZWZpbmVQcm9wZXJ0eShlbGVtQ3RyUHJvdG8sIGNsYXNzTGlzdFByb3AsIGNsYXNzTGlzdFByb3BEZXNjKTtcblx0XHR9XG5cdH1cbn0gZWxzZSBpZiAob2JqQ3RyW3Byb3RvUHJvcF0uX19kZWZpbmVHZXR0ZXJfXykge1xuXHRlbGVtQ3RyUHJvdG8uX19kZWZpbmVHZXR0ZXJfXyhjbGFzc0xpc3RQcm9wLCBjbGFzc0xpc3RHZXR0ZXIpO1xufVxuXG59KHdpbmRvdy5zZWxmKSk7XG5cbn1cblxuLy8gVGhlcmUgaXMgZnVsbCBvciBwYXJ0aWFsIG5hdGl2ZSBjbGFzc0xpc3Qgc3VwcG9ydCwgc28ganVzdCBjaGVjayBpZiB3ZSBuZWVkXG4vLyB0byBub3JtYWxpemUgdGhlIGFkZC9yZW1vdmUgYW5kIHRvZ2dsZSBBUElzLlxuXG4oZnVuY3Rpb24gKCkge1xuXHRcInVzZSBzdHJpY3RcIjtcblxuXHR2YXIgdGVzdEVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiX1wiKTtcblxuXHR0ZXN0RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiYzFcIiwgXCJjMlwiKTtcblxuXHQvLyBQb2x5ZmlsbCBmb3IgSUUgMTAvMTEgYW5kIEZpcmVmb3ggPDI2LCB3aGVyZSBjbGFzc0xpc3QuYWRkIGFuZFxuXHQvLyBjbGFzc0xpc3QucmVtb3ZlIGV4aXN0IGJ1dCBzdXBwb3J0IG9ubHkgb25lIGFyZ3VtZW50IGF0IGEgdGltZS5cblx0aWYgKCF0ZXN0RWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoXCJjMlwiKSkge1xuXHRcdHZhciBjcmVhdGVNZXRob2QgPSBmdW5jdGlvbihtZXRob2QpIHtcblx0XHRcdHZhciBvcmlnaW5hbCA9IERPTVRva2VuTGlzdC5wcm90b3R5cGVbbWV0aG9kXTtcblxuXHRcdFx0RE9NVG9rZW5MaXN0LnByb3RvdHlwZVttZXRob2RdID0gZnVuY3Rpb24odG9rZW4pIHtcblx0XHRcdFx0dmFyIGksIGxlbiA9IGFyZ3VtZW50cy5sZW5ndGg7XG5cblx0XHRcdFx0Zm9yIChpID0gMDsgaSA8IGxlbjsgaSsrKSB7XG5cdFx0XHRcdFx0dG9rZW4gPSBhcmd1bWVudHNbaV07XG5cdFx0XHRcdFx0b3JpZ2luYWwuY2FsbCh0aGlzLCB0b2tlbik7XG5cdFx0XHRcdH1cblx0XHRcdH07XG5cdFx0fTtcblx0XHRjcmVhdGVNZXRob2QoJ2FkZCcpO1xuXHRcdGNyZWF0ZU1ldGhvZCgncmVtb3ZlJyk7XG5cdH1cblxuXHR0ZXN0RWxlbWVudC5jbGFzc0xpc3QudG9nZ2xlKFwiYzNcIiwgZmFsc2UpO1xuXG5cdC8vIFBvbHlmaWxsIGZvciBJRSAxMCBhbmQgRmlyZWZveCA8MjQsIHdoZXJlIGNsYXNzTGlzdC50b2dnbGUgZG9lcyBub3Rcblx0Ly8gc3VwcG9ydCB0aGUgc2Vjb25kIGFyZ3VtZW50LlxuXHRpZiAodGVzdEVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKFwiYzNcIikpIHtcblx0XHR2YXIgX3RvZ2dsZSA9IERPTVRva2VuTGlzdC5wcm90b3R5cGUudG9nZ2xlO1xuXG5cdFx0RE9NVG9rZW5MaXN0LnByb3RvdHlwZS50b2dnbGUgPSBmdW5jdGlvbih0b2tlbiwgZm9yY2UpIHtcblx0XHRcdGlmICgxIGluIGFyZ3VtZW50cyAmJiAhdGhpcy5jb250YWlucyh0b2tlbikgPT09ICFmb3JjZSkge1xuXHRcdFx0XHRyZXR1cm4gZm9yY2U7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRyZXR1cm4gX3RvZ2dsZS5jYWxsKHRoaXMsIHRva2VuKTtcblx0XHRcdH1cblx0XHR9O1xuXG5cdH1cblxuXHR0ZXN0RWxlbWVudCA9IG51bGw7XG59KCkpO1xuXG59XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9jbGFzc2xpc3QtcG9seWZpbGwvc3JjL2luZGV4LmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qISBzbW9vdGgtc2Nyb2xsIHYxNC4yLjAgfCAoYykgMjAxOCBDaHJpcyBGZXJkaW5hbmRpIHwgTUlUIExpY2Vuc2UgfCBodHRwOi8vZ2l0aHViLmNvbS9jZmVyZGluYW5kaS9zbW9vdGgtc2Nyb2xsICovXG4hKGZ1bmN0aW9uKGUsdCl7XCJmdW5jdGlvblwiPT10eXBlb2YgZGVmaW5lJiZkZWZpbmUuYW1kP2RlZmluZShbXSwoZnVuY3Rpb24oKXtyZXR1cm4gdChlKX0pKTpcIm9iamVjdFwiPT10eXBlb2YgZXhwb3J0cz9tb2R1bGUuZXhwb3J0cz10KGUpOmUuU21vb3RoU2Nyb2xsPXQoZSl9KShcInVuZGVmaW5lZFwiIT10eXBlb2YgZ2xvYmFsP2dsb2JhbDpcInVuZGVmaW5lZFwiIT10eXBlb2Ygd2luZG93P3dpbmRvdzp0aGlzLChmdW5jdGlvbihlKXtcInVzZSBzdHJpY3RcIjt2YXIgdD17aWdub3JlOlwiW2RhdGEtc2Nyb2xsLWlnbm9yZV1cIixoZWFkZXI6bnVsbCx0b3BPbkVtcHR5SGFzaDohMCxzcGVlZDo1MDAsY2xpcDohMCxvZmZzZXQ6MCxlYXNpbmc6XCJlYXNlSW5PdXRDdWJpY1wiLGN1c3RvbUVhc2luZzpudWxsLHVwZGF0ZVVSTDohMCxwb3BzdGF0ZTohMCxlbWl0RXZlbnRzOiEwfSxuPWZ1bmN0aW9uKCl7cmV0dXJuXCJxdWVyeVNlbGVjdG9yXCJpbiBkb2N1bWVudCYmXCJhZGRFdmVudExpc3RlbmVyXCJpbiBlJiZcInJlcXVlc3RBbmltYXRpb25GcmFtZVwiaW4gZSYmXCJjbG9zZXN0XCJpbiBlLkVsZW1lbnQucHJvdG90eXBlfSxvPWZ1bmN0aW9uKCl7Zm9yKHZhciBlPXt9LHQ9MDt0PGFyZ3VtZW50cy5sZW5ndGg7dCsrKSEoZnVuY3Rpb24odCl7Zm9yKHZhciBuIGluIHQpdC5oYXNPd25Qcm9wZXJ0eShuKSYmKGVbbl09dFtuXSl9KShhcmd1bWVudHNbdF0pO3JldHVybiBlfSxyPWZ1bmN0aW9uKHQpe3JldHVybiEhKFwibWF0Y2hNZWRpYVwiaW4gZSYmZS5tYXRjaE1lZGlhKFwiKHByZWZlcnMtcmVkdWNlZC1tb3Rpb24pXCIpLm1hdGNoZXMpfSxhPWZ1bmN0aW9uKHQpe3JldHVybiBwYXJzZUludChlLmdldENvbXB1dGVkU3R5bGUodCkuaGVpZ2h0LDEwKX0saT1mdW5jdGlvbihlKXt2YXIgdDt0cnl7dD1kZWNvZGVVUklDb21wb25lbnQoZSl9Y2F0Y2gobil7dD1lfXJldHVybiB0fSxjPWZ1bmN0aW9uKGUpe1wiI1wiPT09ZS5jaGFyQXQoMCkmJihlPWUuc3Vic3RyKDEpKTtmb3IodmFyIHQsbj1TdHJpbmcoZSksbz1uLmxlbmd0aCxyPS0xLGE9XCJcIixpPW4uY2hhckNvZGVBdCgwKTsrK3I8bzspe2lmKDA9PT0odD1uLmNoYXJDb2RlQXQocikpKXRocm93IG5ldyBJbnZhbGlkQ2hhcmFjdGVyRXJyb3IoXCJJbnZhbGlkIGNoYXJhY3RlcjogdGhlIGlucHV0IGNvbnRhaW5zIFUrMDAwMC5cIik7dD49MSYmdDw9MzF8fDEyNz09dHx8MD09PXImJnQ+PTQ4JiZ0PD01N3x8MT09PXImJnQ+PTQ4JiZ0PD01NyYmNDU9PT1pP2ErPVwiXFxcXFwiK3QudG9TdHJpbmcoMTYpK1wiIFwiOmErPXQ+PTEyOHx8NDU9PT10fHw5NT09PXR8fHQ+PTQ4JiZ0PD01N3x8dD49NjUmJnQ8PTkwfHx0Pj05NyYmdDw9MTIyP24uY2hhckF0KHIpOlwiXFxcXFwiK24uY2hhckF0KHIpfXZhciBjO3RyeXtjPWRlY29kZVVSSUNvbXBvbmVudChcIiNcIithKX1jYXRjaChlKXtjPVwiI1wiK2F9cmV0dXJuIGN9LHU9ZnVuY3Rpb24oZSx0KXt2YXIgbjtyZXR1cm5cImVhc2VJblF1YWRcIj09PWUuZWFzaW5nJiYobj10KnQpLFwiZWFzZU91dFF1YWRcIj09PWUuZWFzaW5nJiYobj10KigyLXQpKSxcImVhc2VJbk91dFF1YWRcIj09PWUuZWFzaW5nJiYobj10PC41PzIqdCp0Oig0LTIqdCkqdC0xKSxcImVhc2VJbkN1YmljXCI9PT1lLmVhc2luZyYmKG49dCp0KnQpLFwiZWFzZU91dEN1YmljXCI9PT1lLmVhc2luZyYmKG49LS10KnQqdCsxKSxcImVhc2VJbk91dEN1YmljXCI9PT1lLmVhc2luZyYmKG49dDwuNT80KnQqdCp0Oih0LTEpKigyKnQtMikqKDIqdC0yKSsxKSxcImVhc2VJblF1YXJ0XCI9PT1lLmVhc2luZyYmKG49dCp0KnQqdCksXCJlYXNlT3V0UXVhcnRcIj09PWUuZWFzaW5nJiYobj0xLSAtLXQqdCp0KnQpLFwiZWFzZUluT3V0UXVhcnRcIj09PWUuZWFzaW5nJiYobj10PC41PzgqdCp0KnQqdDoxLTgqLS10KnQqdCp0KSxcImVhc2VJblF1aW50XCI9PT1lLmVhc2luZyYmKG49dCp0KnQqdCp0KSxcImVhc2VPdXRRdWludFwiPT09ZS5lYXNpbmcmJihuPTErLS10KnQqdCp0KnQpLFwiZWFzZUluT3V0UXVpbnRcIj09PWUuZWFzaW5nJiYobj10PC41PzE2KnQqdCp0KnQqdDoxKzE2Ki0tdCp0KnQqdCp0KSxlLmN1c3RvbUVhc2luZyYmKG49ZS5jdXN0b21FYXNpbmcodCkpLG58fHR9LHM9ZnVuY3Rpb24oKXtyZXR1cm4gTWF0aC5tYXgoZG9jdW1lbnQuYm9keS5zY3JvbGxIZWlnaHQsZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbEhlaWdodCxkb2N1bWVudC5ib2R5Lm9mZnNldEhlaWdodCxkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQub2Zmc2V0SGVpZ2h0LGRvY3VtZW50LmJvZHkuY2xpZW50SGVpZ2h0LGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRIZWlnaHQpfSxsPWZ1bmN0aW9uKHQsbixvLHIpe3ZhciBhPTA7aWYodC5vZmZzZXRQYXJlbnQpZG97YSs9dC5vZmZzZXRUb3AsdD10Lm9mZnNldFBhcmVudH13aGlsZSh0KTtyZXR1cm4gYT1NYXRoLm1heChhLW4tbywwKSxyJiYoYT1NYXRoLm1pbihhLHMoKS1lLmlubmVySGVpZ2h0KSksYX0sZD1mdW5jdGlvbihlKXtyZXR1cm4gZT9hKGUpK2Uub2Zmc2V0VG9wOjB9LGY9ZnVuY3Rpb24oZSx0LG4pe3R8fGhpc3RvcnkucHVzaFN0YXRlJiZuLnVwZGF0ZVVSTCYmaGlzdG9yeS5wdXNoU3RhdGUoe3Ntb290aFNjcm9sbDpKU09OLnN0cmluZ2lmeShuKSxhbmNob3I6ZS5pZH0sZG9jdW1lbnQudGl0bGUsZT09PWRvY3VtZW50LmRvY3VtZW50RWxlbWVudD9cIiN0b3BcIjpcIiNcIitlLmlkKX0sbT1mdW5jdGlvbih0LG4sbyl7MD09PXQmJmRvY3VtZW50LmJvZHkuZm9jdXMoKSxvfHwodC5mb2N1cygpLGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQhPT10JiYodC5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLFwiLTFcIiksdC5mb2N1cygpLHQuc3R5bGUub3V0bGluZT1cIm5vbmVcIiksZS5zY3JvbGxUbygwLG4pKX0saD1mdW5jdGlvbih0LG4sbyxyKXtpZihuLmVtaXRFdmVudHMmJlwiZnVuY3Rpb25cIj09dHlwZW9mIGUuQ3VzdG9tRXZlbnQpe3ZhciBhPW5ldyBDdXN0b21FdmVudCh0LHtidWJibGVzOiEwLGRldGFpbDp7YW5jaG9yOm8sdG9nZ2xlOnJ9fSk7ZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChhKX19O3JldHVybiBmdW5jdGlvbihhLHApe3ZhciBnLHYseSxTLEUsYixPLEk9e307SS5jYW5jZWxTY3JvbGw9ZnVuY3Rpb24oZSl7Y2FuY2VsQW5pbWF0aW9uRnJhbWUoTyksTz1udWxsLGV8fGgoXCJzY3JvbGxDYW5jZWxcIixnKX0sSS5hbmltYXRlU2Nyb2xsPWZ1bmN0aW9uKG4scixhKXt2YXIgaT1vKGd8fHQsYXx8e30pLGM9XCJbb2JqZWN0IE51bWJlcl1cIj09PU9iamVjdC5wcm90b3R5cGUudG9TdHJpbmcuY2FsbChuKSxwPWN8fCFuLnRhZ05hbWU/bnVsbDpuO2lmKGN8fHApe3ZhciB2PWUucGFnZVlPZmZzZXQ7aS5oZWFkZXImJiFTJiYoUz1kb2N1bWVudC5xdWVyeVNlbGVjdG9yKGkuaGVhZGVyKSksRXx8KEU9ZChTKSk7dmFyIHksYixDLHc9Yz9uOmwocCxFLHBhcnNlSW50KFwiZnVuY3Rpb25cIj09dHlwZW9mIGkub2Zmc2V0P2kub2Zmc2V0KG4scik6aS5vZmZzZXQsMTApLGkuY2xpcCksTD13LXYsQT1zKCksSD0wLHE9ZnVuY3Rpb24odCxvKXt2YXIgYT1lLnBhZ2VZT2Zmc2V0O2lmKHQ9PW98fGE9PW98fCh2PG8mJmUuaW5uZXJIZWlnaHQrYSk+PUEpcmV0dXJuIEkuY2FuY2VsU2Nyb2xsKCEwKSxtKG4sbyxjKSxoKFwic2Nyb2xsU3RvcFwiLGksbixyKSx5PW51bGwsTz1udWxsLCEwfSxRPWZ1bmN0aW9uKHQpe3l8fCh5PXQpLEgrPXQteSxiPUgvcGFyc2VJbnQoaS5zcGVlZCwxMCksYj1iPjE/MTpiLEM9ditMKnUoaSxiKSxlLnNjcm9sbFRvKDAsTWF0aC5mbG9vcihDKSkscShDLHcpfHwoTz1lLnJlcXVlc3RBbmltYXRpb25GcmFtZShRKSx5PXQpfTswPT09ZS5wYWdlWU9mZnNldCYmZS5zY3JvbGxUbygwLDApLGYobixjLGkpLGgoXCJzY3JvbGxTdGFydFwiLGksbixyKSxJLmNhbmNlbFNjcm9sbCghMCksZS5yZXF1ZXN0QW5pbWF0aW9uRnJhbWUoUSl9fTt2YXIgQz1mdW5jdGlvbih0KXtpZighcigpJiYwPT09dC5idXR0b24mJiF0Lm1ldGFLZXkmJiF0LmN0cmxLZXkmJlwiY2xvc2VzdFwiaW4gdC50YXJnZXQmJih5PXQudGFyZ2V0LmNsb3Nlc3QoYSkpJiZcImFcIj09PXkudGFnTmFtZS50b0xvd2VyQ2FzZSgpJiYhdC50YXJnZXQuY2xvc2VzdChnLmlnbm9yZSkmJnkuaG9zdG5hbWU9PT1lLmxvY2F0aW9uLmhvc3RuYW1lJiZ5LnBhdGhuYW1lPT09ZS5sb2NhdGlvbi5wYXRobmFtZSYmLyMvLnRlc3QoeS5ocmVmKSl7dmFyIG49YyhpKHkuaGFzaCkpLG89Zy50b3BPbkVtcHR5SGFzaCYmXCIjXCI9PT1uP2RvY3VtZW50LmRvY3VtZW50RWxlbWVudDpkb2N1bWVudC5xdWVyeVNlbGVjdG9yKG4pO289b3x8XCIjdG9wXCIhPT1uP286ZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LG8mJih0LnByZXZlbnREZWZhdWx0KCksSS5hbmltYXRlU2Nyb2xsKG8seSkpfX0sdz1mdW5jdGlvbihlKXtpZihoaXN0b3J5LnN0YXRlLnNtb290aFNjcm9sbCYmaGlzdG9yeS5zdGF0ZS5zbW9vdGhTY3JvbGw9PT1KU09OLnN0cmluZ2lmeShnKSYmaGlzdG9yeS5zdGF0ZS5hbmNob3Ipe3ZhciB0PWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoYyhpKGhpc3Rvcnkuc3RhdGUuYW5jaG9yKSkpO3QmJkkuYW5pbWF0ZVNjcm9sbCh0LG51bGwse3VwZGF0ZVVSTDohMX0pfX0sTD1mdW5jdGlvbihlKXtifHwoYj1zZXRUaW1lb3V0KChmdW5jdGlvbigpe2I9bnVsbCxFPWQoUyl9KSw2NikpfTtyZXR1cm4gSS5kZXN0cm95PWZ1bmN0aW9uKCl7ZyYmKGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLEMsITEpLGUucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLEwsITEpLGUucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInBvcHN0YXRlXCIsdywhMSksSS5jYW5jZWxTY3JvbGwoKSxnPW51bGwsdj1udWxsLHk9bnVsbCxTPW51bGwsRT1udWxsLGI9bnVsbCxPPW51bGwpfSxJLmluaXQ9ZnVuY3Rpb24ocil7aWYoIW4oKSl0aHJvd1wiU21vb3RoIFNjcm9sbDogVGhpcyBicm93c2VyIGRvZXMgbm90IHN1cHBvcnQgdGhlIHJlcXVpcmVkIEphdmFTY3JpcHQgbWV0aG9kcyBhbmQgYnJvd3NlciBBUElzLlwiO0kuZGVzdHJveSgpLGc9byh0LHJ8fHt9KSxTPWcuaGVhZGVyP2RvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZy5oZWFkZXIpOm51bGwsRT1kKFMpLGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLEMsITEpLFMmJmUuYWRkRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLEwsITEpLGcudXBkYXRlVVJMJiZnLnBvcHN0YXRlJiZlLmFkZEV2ZW50TGlzdGVuZXIoXCJwb3BzdGF0ZVwiLHcsITEpfSxJLmluaXQocCksSX19KSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvc21vb3RoLXNjcm9sbC9kaXN0L3Ntb290aC1zY3JvbGwubWluLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9zbW9vdGgtc2Nyb2xsL2Rpc3Qvc21vb3RoLXNjcm9sbC5taW4uanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLyohXHJcbiAgKiBTdGlja3lmaWxsIOKAkyBgcG9zaXRpb246IHN0aWNreWAgcG9seWZpbGxcclxuICAqIHYuIDIuMC41IHwgaHR0cHM6Ly9naXRodWIuY29tL3dpbGRkZWVyL3N0aWNreWZpbGxcclxuICAqIE1JVCBMaWNlbnNlXHJcbiAgKi9cclxuXHJcbjsoZnVuY3Rpb24od2luZG93LCBkb2N1bWVudCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIC8qXHJcbiAgICAgKiAxLiBDaGVjayBpZiB0aGUgYnJvd3NlciBzdXBwb3J0cyBgcG9zaXRpb246IHN0aWNreWAgbmF0aXZlbHkgb3IgaXMgdG9vIG9sZCB0byBydW4gdGhlIHBvbHlmaWxsLlxyXG4gICAgICogICAgSWYgZWl0aGVyIG9mIHRoZXNlIGlzIHRoZSBjYXNlIHNldCBgc2VwcHVrdWAgZmxhZy4gSXQgd2lsbCBiZSBjaGVja2VkIGxhdGVyIHRvIGRpc2FibGUga2V5IGZlYXR1cmVzXHJcbiAgICAgKiAgICBvZiB0aGUgcG9seWZpbGwsIGJ1dCB0aGUgQVBJIHdpbGwgcmVtYWluIGZ1bmN0aW9uYWwgdG8gYXZvaWQgYnJlYWtpbmcgdGhpbmdzLlxyXG4gICAgICovXHJcblxyXG4gICAgdmFyIF9jcmVhdGVDbGFzcyA9IGZ1bmN0aW9uICgpIHsgZnVuY3Rpb24gZGVmaW5lUHJvcGVydGllcyh0YXJnZXQsIHByb3BzKSB7IGZvciAodmFyIGkgPSAwOyBpIDwgcHJvcHMubGVuZ3RoOyBpKyspIHsgdmFyIGRlc2NyaXB0b3IgPSBwcm9wc1tpXTsgZGVzY3JpcHRvci5lbnVtZXJhYmxlID0gZGVzY3JpcHRvci5lbnVtZXJhYmxlIHx8IGZhbHNlOyBkZXNjcmlwdG9yLmNvbmZpZ3VyYWJsZSA9IHRydWU7IGlmIChcInZhbHVlXCIgaW4gZGVzY3JpcHRvcikgZGVzY3JpcHRvci53cml0YWJsZSA9IHRydWU7IE9iamVjdC5kZWZpbmVQcm9wZXJ0eSh0YXJnZXQsIGRlc2NyaXB0b3Iua2V5LCBkZXNjcmlwdG9yKTsgfSB9IHJldHVybiBmdW5jdGlvbiAoQ29uc3RydWN0b3IsIHByb3RvUHJvcHMsIHN0YXRpY1Byb3BzKSB7IGlmIChwcm90b1Byb3BzKSBkZWZpbmVQcm9wZXJ0aWVzKENvbnN0cnVjdG9yLnByb3RvdHlwZSwgcHJvdG9Qcm9wcyk7IGlmIChzdGF0aWNQcm9wcykgZGVmaW5lUHJvcGVydGllcyhDb25zdHJ1Y3Rvciwgc3RhdGljUHJvcHMpOyByZXR1cm4gQ29uc3RydWN0b3I7IH07IH0oKTtcclxuXHJcbiAgICBmdW5jdGlvbiBfY2xhc3NDYWxsQ2hlY2soaW5zdGFuY2UsIENvbnN0cnVjdG9yKSB7IGlmICghKGluc3RhbmNlIGluc3RhbmNlb2YgQ29uc3RydWN0b3IpKSB7IHRocm93IG5ldyBUeXBlRXJyb3IoXCJDYW5ub3QgY2FsbCBhIGNsYXNzIGFzIGEgZnVuY3Rpb25cIik7IH0gfVxyXG5cclxuICAgIHZhciBzZXBwdWt1ID0gZmFsc2U7XHJcblxyXG4gICAgLy8gVGhlIHBvbHlmaWxsIGNhbnTigJl0IGZ1bmN0aW9uIHByb3Blcmx5IHdpdGhvdXQgYGdldENvbXB1dGVkU3R5bGVgLlxyXG4gICAgaWYgKCF3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZSkgc2VwcHVrdSA9IHRydWU7XHJcbiAgICAvLyBEb2504oCZdCBnZXQgaW4gYSB3YXkgaWYgdGhlIGJyb3dzZXIgc3VwcG9ydHMgYHBvc2l0aW9uOiBzdGlja3lgIG5hdGl2ZWx5LlxyXG4gICAgZWxzZSB7XHJcbiAgICAgICAgICAgIHZhciB0ZXN0Tm9kZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG5cclxuICAgICAgICAgICAgaWYgKFsnJywgJy13ZWJraXQtJywgJy1tb3otJywgJy1tcy0nXS5zb21lKGZ1bmN0aW9uIChwcmVmaXgpIHtcclxuICAgICAgICAgICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGVzdE5vZGUuc3R5bGUucG9zaXRpb24gPSBwcmVmaXggKyAnc3RpY2t5JztcclxuICAgICAgICAgICAgICAgIH0gY2F0Y2ggKGUpIHt9XHJcblxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHRlc3ROb2RlLnN0eWxlLnBvc2l0aW9uICE9ICcnO1xyXG4gICAgICAgICAgICB9KSkgc2VwcHVrdSA9IHRydWU7XHJcbiAgICAgICAgfVxyXG5cclxuICAgIC8qXHJcbiAgICAgKiAyLiDigJxHbG9iYWzigJ0gdmFycyB1c2VkIGFjcm9zcyB0aGUgcG9seWZpbGxcclxuICAgICAqL1xyXG5cclxuICAgIC8vIENoZWNrIGlmIFNoYWRvdyBSb290IGNvbnN0cnVjdG9yIGV4aXN0cyB0byBtYWtlIGZ1cnRoZXIgY2hlY2tzIHNpbXBsZXJcclxuICAgIHZhciBzaGFkb3dSb290RXhpc3RzID0gdHlwZW9mIFNoYWRvd1Jvb3QgIT09ICd1bmRlZmluZWQnO1xyXG5cclxuICAgIC8vIExhc3Qgc2F2ZWQgc2Nyb2xsIHBvc2l0aW9uXHJcbiAgICB2YXIgc2Nyb2xsID0ge1xyXG4gICAgICAgIHRvcDogbnVsbCxcclxuICAgICAgICBsZWZ0OiBudWxsXHJcbiAgICB9O1xyXG5cclxuICAgIC8vIEFycmF5IG9mIGNyZWF0ZWQgU3RpY2t5IGluc3RhbmNlc1xyXG4gICAgdmFyIHN0aWNraWVzID0gW107XHJcblxyXG4gICAgLypcclxuICAgICAqIDMuIFV0aWxpdHkgZnVuY3Rpb25zXHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIGV4dGVuZCh0YXJnZXRPYmosIHNvdXJjZU9iamVjdCkge1xyXG4gICAgICAgIGZvciAodmFyIGtleSBpbiBzb3VyY2VPYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKHNvdXJjZU9iamVjdC5oYXNPd25Qcm9wZXJ0eShrZXkpKSB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRPYmpba2V5XSA9IHNvdXJjZU9iamVjdFtrZXldO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIHBhcnNlTnVtZXJpYyh2YWwpIHtcclxuICAgICAgICByZXR1cm4gcGFyc2VGbG9hdCh2YWwpIHx8IDA7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gZ2V0RG9jT2Zmc2V0VG9wKG5vZGUpIHtcclxuICAgICAgICB2YXIgZG9jT2Zmc2V0VG9wID0gMDtcclxuXHJcbiAgICAgICAgd2hpbGUgKG5vZGUpIHtcclxuICAgICAgICAgICAgZG9jT2Zmc2V0VG9wICs9IG5vZGUub2Zmc2V0VG9wO1xyXG4gICAgICAgICAgICBub2RlID0gbm9kZS5vZmZzZXRQYXJlbnQ7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICByZXR1cm4gZG9jT2Zmc2V0VG9wO1xyXG4gICAgfVxyXG5cclxuICAgIC8qXHJcbiAgICAgKiA0LiBTdGlja3kgY2xhc3NcclxuICAgICAqL1xyXG5cclxuICAgIHZhciBTdGlja3kgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgZnVuY3Rpb24gU3RpY2t5KG5vZGUpIHtcclxuICAgICAgICAgICAgX2NsYXNzQ2FsbENoZWNrKHRoaXMsIFN0aWNreSk7XHJcblxyXG4gICAgICAgICAgICBpZiAoIShub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpKSB0aHJvdyBuZXcgRXJyb3IoJ0ZpcnN0IGFyZ3VtZW50IG11c3QgYmUgSFRNTEVsZW1lbnQnKTtcclxuICAgICAgICAgICAgaWYgKHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fbm9kZSA9PT0gbm9kZTtcclxuICAgICAgICAgICAgfSkpIHRocm93IG5ldyBFcnJvcignU3RpY2t5ZmlsbCBpcyBhbHJlYWR5IGFwcGxpZWQgdG8gdGhpcyBub2RlJyk7XHJcblxyXG4gICAgICAgICAgICB0aGlzLl9ub2RlID0gbm9kZTtcclxuICAgICAgICAgICAgdGhpcy5fc3RpY2t5TW9kZSA9IG51bGw7XHJcbiAgICAgICAgICAgIHRoaXMuX2FjdGl2ZSA9IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgc3RpY2tpZXMucHVzaCh0aGlzKTtcclxuXHJcbiAgICAgICAgICAgIHRoaXMucmVmcmVzaCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgX2NyZWF0ZUNsYXNzKFN0aWNreSwgW3tcclxuICAgICAgICAgICAga2V5OiAncmVmcmVzaCcsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiByZWZyZXNoKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHNlcHB1a3UgfHwgdGhpcy5fcmVtb3ZlZCkgcmV0dXJuO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuX2FjdGl2ZSkgdGhpcy5fZGVhY3RpdmF0ZSgpO1xyXG5cclxuICAgICAgICAgICAgICAgIHZhciBub2RlID0gdGhpcy5fbm9kZTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogMS4gU2F2ZSBub2RlIGNvbXB1dGVkIHByb3BzXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHZhciBub2RlQ29tcHV0ZWRTdHlsZSA9IGdldENvbXB1dGVkU3R5bGUobm9kZSk7XHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZUNvbXB1dGVkUHJvcHMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdG9wOiBub2RlQ29tcHV0ZWRTdHlsZS50b3AsXHJcbiAgICAgICAgICAgICAgICAgICAgZGlzcGxheTogbm9kZUNvbXB1dGVkU3R5bGUuZGlzcGxheSxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IG5vZGVDb21wdXRlZFN0eWxlLm1hcmdpblRvcCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Cb3R0b206IG5vZGVDb21wdXRlZFN0eWxlLm1hcmdpbkJvdHRvbSxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5MZWZ0OiBub2RlQ29tcHV0ZWRTdHlsZS5tYXJnaW5MZWZ0LFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpblJpZ2h0OiBub2RlQ29tcHV0ZWRTdHlsZS5tYXJnaW5SaWdodCxcclxuICAgICAgICAgICAgICAgICAgICBjc3NGbG9hdDogbm9kZUNvbXB1dGVkU3R5bGUuY3NzRmxvYXRcclxuICAgICAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICAgICAgLypcclxuICAgICAgICAgICAgICAgICAqIDIuIENoZWNrIGlmIHRoZSBub2RlIGNhbiBiZSBhY3RpdmF0ZWRcclxuICAgICAgICAgICAgICAgICAqL1xyXG4gICAgICAgICAgICAgICAgaWYgKGlzTmFOKHBhcnNlRmxvYXQobm9kZUNvbXB1dGVkUHJvcHMudG9wKSkgfHwgbm9kZUNvbXB1dGVkUHJvcHMuZGlzcGxheSA9PSAndGFibGUtY2VsbCcgfHwgbm9kZUNvbXB1dGVkUHJvcHMuZGlzcGxheSA9PSAnbm9uZScpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiAzLiBHZXQgbmVjZXNzYXJ5IG5vZGUgcGFyYW1ldGVyc1xyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB2YXIgcmVmZXJlbmNlTm9kZSA9IG5vZGUucGFyZW50Tm9kZTtcclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnROb2RlID0gc2hhZG93Um9vdEV4aXN0cyAmJiByZWZlcmVuY2VOb2RlIGluc3RhbmNlb2YgU2hhZG93Um9vdCA/IHJlZmVyZW5jZU5vZGUuaG9zdCA6IHJlZmVyZW5jZU5vZGU7XHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZVdpbk9mZnNldCA9IG5vZGUuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50V2luT2Zmc2V0ID0gcGFyZW50Tm9kZS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnRDb21wdXRlZFN0eWxlID0gZ2V0Q29tcHV0ZWRTdHlsZShwYXJlbnROb2RlKTtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9wYXJlbnQgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbm9kZTogcGFyZW50Tm9kZSxcclxuICAgICAgICAgICAgICAgICAgICBzdHlsZXM6IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246IHBhcmVudE5vZGUuc3R5bGUucG9zaXRpb25cclxuICAgICAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgICAgIG9mZnNldEhlaWdodDogcGFyZW50Tm9kZS5vZmZzZXRIZWlnaHRcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9vZmZzZXRUb1dpbmRvdyA9IHtcclxuICAgICAgICAgICAgICAgICAgICBsZWZ0OiBub2RlV2luT2Zmc2V0LmxlZnQsXHJcbiAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRXaWR0aCAtIG5vZGVXaW5PZmZzZXQucmlnaHRcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9vZmZzZXRUb1BhcmVudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICB0b3A6IG5vZGVXaW5PZmZzZXQudG9wIC0gcGFyZW50V2luT2Zmc2V0LnRvcCAtIHBhcnNlTnVtZXJpYyhwYXJlbnRDb21wdXRlZFN0eWxlLmJvcmRlclRvcFdpZHRoKSxcclxuICAgICAgICAgICAgICAgICAgICBsZWZ0OiBub2RlV2luT2Zmc2V0LmxlZnQgLSBwYXJlbnRXaW5PZmZzZXQubGVmdCAtIHBhcnNlTnVtZXJpYyhwYXJlbnRDb21wdXRlZFN0eWxlLmJvcmRlckxlZnRXaWR0aCksXHJcbiAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IC1ub2RlV2luT2Zmc2V0LnJpZ2h0ICsgcGFyZW50V2luT2Zmc2V0LnJpZ2h0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyUmlnaHRXaWR0aClcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9zdHlsZXMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246IG5vZGUuc3R5bGUucG9zaXRpb24sXHJcbiAgICAgICAgICAgICAgICAgICAgdG9wOiBub2RlLnN0eWxlLnRvcCxcclxuICAgICAgICAgICAgICAgICAgICBib3R0b206IG5vZGUuc3R5bGUuYm90dG9tLFxyXG4gICAgICAgICAgICAgICAgICAgIGxlZnQ6IG5vZGUuc3R5bGUubGVmdCxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDogbm9kZS5zdHlsZS5yaWdodCxcclxuICAgICAgICAgICAgICAgICAgICB3aWR0aDogbm9kZS5zdHlsZS53aWR0aCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IG5vZGUuc3R5bGUubWFyZ2luVG9wLFxyXG4gICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IG5vZGUuc3R5bGUubWFyZ2luTGVmdCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogbm9kZS5zdHlsZS5tYXJnaW5SaWdodFxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICB2YXIgbm9kZVRvcFZhbHVlID0gcGFyc2VOdW1lcmljKG5vZGVDb21wdXRlZFByb3BzLnRvcCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9saW1pdHMgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgc3RhcnQ6IG5vZGVXaW5PZmZzZXQudG9wICsgd2luZG93LnBhZ2VZT2Zmc2V0IC0gbm9kZVRvcFZhbHVlLFxyXG4gICAgICAgICAgICAgICAgICAgIGVuZDogcGFyZW50V2luT2Zmc2V0LnRvcCArIHdpbmRvdy5wYWdlWU9mZnNldCArIHBhcmVudE5vZGUub2Zmc2V0SGVpZ2h0IC0gcGFyc2VOdW1lcmljKHBhcmVudENvbXB1dGVkU3R5bGUuYm9yZGVyQm90dG9tV2lkdGgpIC0gbm9kZS5vZmZzZXRIZWlnaHQgLSBub2RlVG9wVmFsdWUgLSBwYXJzZU51bWVyaWMobm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luQm90dG9tKVxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKlxyXG4gICAgICAgICAgICAgICAgICogNC4gRW5zdXJlIHRoYXQgdGhlIG5vZGUgd2lsbCBiZSBwb3NpdGlvbmVkIHJlbGF0aXZlbHkgdG8gdGhlIHBhcmVudCBub2RlXHJcbiAgICAgICAgICAgICAgICAgKi9cclxuICAgICAgICAgICAgICAgIHZhciBwYXJlbnRQb3NpdGlvbiA9IHBhcmVudENvbXB1dGVkU3R5bGUucG9zaXRpb247XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKHBhcmVudFBvc2l0aW9uICE9ICdhYnNvbHV0ZScgJiYgcGFyZW50UG9zaXRpb24gIT0gJ3JlbGF0aXZlJykge1xyXG4gICAgICAgICAgICAgICAgICAgIHBhcmVudE5vZGUuc3R5bGUucG9zaXRpb24gPSAncmVsYXRpdmUnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiA1LiBSZWNhbGMgbm9kZSBwb3NpdGlvbi5cclxuICAgICAgICAgICAgICAgICAqICAgIEl04oCZcyBpbXBvcnRhbnQgdG8gZG8gdGhpcyBiZWZvcmUgY2xvbmUgaW5qZWN0aW9uIHRvIGF2b2lkIHNjcm9sbGluZyBidWcgaW4gQ2hyb21lLlxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9yZWNhbGNQb3NpdGlvbigpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qXHJcbiAgICAgICAgICAgICAgICAgKiA2LiBDcmVhdGUgYSBjbG9uZVxyXG4gICAgICAgICAgICAgICAgICovXHJcbiAgICAgICAgICAgICAgICB2YXIgY2xvbmUgPSB0aGlzLl9jbG9uZSA9IHt9O1xyXG4gICAgICAgICAgICAgICAgY2xvbmUubm9kZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIEFwcGx5IHN0eWxlcyB0byB0aGUgY2xvbmVcclxuICAgICAgICAgICAgICAgIGV4dGVuZChjbG9uZS5ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgd2lkdGg6IG5vZGVXaW5PZmZzZXQucmlnaHQgLSBub2RlV2luT2Zmc2V0LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgIGhlaWdodDogbm9kZVdpbk9mZnNldC5ib3R0b20gLSBub2RlV2luT2Zmc2V0LnRvcCArICdweCcsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luVG9wOiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5Ub3AsXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luQm90dG9tOiBub2RlQ29tcHV0ZWRQcm9wcy5tYXJnaW5Cb3R0b20sXHJcbiAgICAgICAgICAgICAgICAgICAgbWFyZ2luTGVmdDogbm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luTGVmdCxcclxuICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogbm9kZUNvbXB1dGVkUHJvcHMubWFyZ2luUmlnaHQsXHJcbiAgICAgICAgICAgICAgICAgICAgY3NzRmxvYXQ6IG5vZGVDb21wdXRlZFByb3BzLmNzc0Zsb2F0LFxyXG4gICAgICAgICAgICAgICAgICAgIHBhZGRpbmc6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgYm9yZGVyOiAwLFxyXG4gICAgICAgICAgICAgICAgICAgIGJvcmRlclNwYWNpbmc6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgZm9udFNpemU6ICcxZW0nLFxyXG4gICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnc3RhdGljJ1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICAgICAgcmVmZXJlbmNlTm9kZS5pbnNlcnRCZWZvcmUoY2xvbmUubm9kZSwgbm9kZSk7XHJcbiAgICAgICAgICAgICAgICBjbG9uZS5kb2NPZmZzZXRUb3AgPSBnZXREb2NPZmZzZXRUb3AoY2xvbmUubm9kZSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19yZWNhbGNQb3NpdGlvbicsXHJcbiAgICAgICAgICAgIHZhbHVlOiBmdW5jdGlvbiBfcmVjYWxjUG9zaXRpb24oKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXRoaXMuX2FjdGl2ZSB8fCB0aGlzLl9yZW1vdmVkKSByZXR1cm47XHJcblxyXG4gICAgICAgICAgICAgICAgdmFyIHN0aWNreU1vZGUgPSBzY3JvbGwudG9wIDw9IHRoaXMuX2xpbWl0cy5zdGFydCA/ICdzdGFydCcgOiBzY3JvbGwudG9wID49IHRoaXMuX2xpbWl0cy5lbmQgPyAnZW5kJyA6ICdtaWRkbGUnO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICh0aGlzLl9zdGlja3lNb2RlID09IHN0aWNreU1vZGUpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICBzd2l0Y2ggKHN0aWNreU1vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICBjYXNlICdzdGFydCc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2Fic29sdXRlJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvUGFyZW50LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvUGFyZW50LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogdGhpcy5fb2Zmc2V0VG9QYXJlbnQudG9wICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJvdHRvbTogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ21pZGRsZSc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2ZpeGVkJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvV2luZG93LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvV2luZG93LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogdGhpcy5fc3R5bGVzLnRvcCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJvdHRvbTogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpblRvcDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2VuZCc6XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9ub2RlLnN0eWxlLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2Fic29sdXRlJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IHRoaXMuX29mZnNldFRvUGFyZW50LmxlZnQgKyAncHgnLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmlnaHQ6IHRoaXMuX29mZnNldFRvUGFyZW50LnJpZ2h0ICsgJ3B4JyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogJ2F1dG8nLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYm90dG9tOiAwLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6ICdhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hcmdpbkxlZnQ6IDAsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5SaWdodDogMFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgdGhpcy5fc3RpY2t5TW9kZSA9IHN0aWNreU1vZGU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LCB7XHJcbiAgICAgICAgICAgIGtleTogJ19mYXN0Q2hlY2snLFxyXG4gICAgICAgICAgICB2YWx1ZTogZnVuY3Rpb24gX2Zhc3RDaGVjaygpIHtcclxuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fYWN0aXZlIHx8IHRoaXMuX3JlbW92ZWQpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAoTWF0aC5hYnMoZ2V0RG9jT2Zmc2V0VG9wKHRoaXMuX2Nsb25lLm5vZGUpIC0gdGhpcy5fY2xvbmUuZG9jT2Zmc2V0VG9wKSA+IDEgfHwgTWF0aC5hYnModGhpcy5fcGFyZW50Lm5vZGUub2Zmc2V0SGVpZ2h0IC0gdGhpcy5fcGFyZW50Lm9mZnNldEhlaWdodCkgPiAxKSB0aGlzLnJlZnJlc2goKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIHtcclxuICAgICAgICAgICAga2V5OiAnX2RlYWN0aXZhdGUnLFxyXG4gICAgICAgICAgICB2YWx1ZTogZnVuY3Rpb24gX2RlYWN0aXZhdGUoKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgX3RoaXMgPSB0aGlzO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fYWN0aXZlIHx8IHRoaXMuX3JlbW92ZWQpIHJldHVybjtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9jbG9uZS5ub2RlLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5fY2xvbmUubm9kZSk7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fY2xvbmU7XHJcblxyXG4gICAgICAgICAgICAgICAgZXh0ZW5kKHRoaXMuX25vZGUuc3R5bGUsIHRoaXMuX3N0eWxlcyk7XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fc3R5bGVzO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIENoZWNrIHdoZXRoZXIgZWxlbWVudOKAmXMgcGFyZW50IG5vZGUgaXMgdXNlZCBieSBvdGhlciBzdGlja2llcy5cclxuICAgICAgICAgICAgICAgIC8vIElmIG5vdCwgcmVzdG9yZSBwYXJlbnQgbm9kZeKAmXMgc3R5bGVzLlxyXG4gICAgICAgICAgICAgICAgaWYgKCFzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc3RpY2t5ICE9PSBfdGhpcyAmJiBzdGlja3kuX3BhcmVudCAmJiBzdGlja3kuX3BhcmVudC5ub2RlID09PSBfdGhpcy5fcGFyZW50Lm5vZGU7XHJcbiAgICAgICAgICAgICAgICB9KSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGV4dGVuZCh0aGlzLl9wYXJlbnQubm9kZS5zdHlsZSwgdGhpcy5fcGFyZW50LnN0eWxlcyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBkZWxldGUgdGhpcy5fcGFyZW50O1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX3N0aWNreU1vZGUgPSBudWxsO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlID0gZmFsc2U7XHJcblxyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX29mZnNldFRvV2luZG93O1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX29mZnNldFRvUGFyZW50O1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlIHRoaXMuX2xpbWl0cztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIHtcclxuICAgICAgICAgICAga2V5OiAncmVtb3ZlJyxcclxuICAgICAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHJlbW92ZSgpIHtcclxuICAgICAgICAgICAgICAgIHZhciBfdGhpczIgPSB0aGlzO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX2RlYWN0aXZhdGUoKTtcclxuXHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3ksIGluZGV4KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHN0aWNreS5fbm9kZSA9PT0gX3RoaXMyLl9ub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0aWNraWVzLnNwbGljZShpbmRleCwgMSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgICAgIHRoaXMuX3JlbW92ZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfV0pO1xyXG5cclxuICAgICAgICByZXR1cm4gU3RpY2t5O1xyXG4gICAgfSgpO1xyXG5cclxuICAgIC8qXHJcbiAgICAgKiA1LiBTdGlja3lmaWxsIEFQSVxyXG4gICAgICovXHJcblxyXG5cclxuICAgIHZhciBTdGlja3lmaWxsID0ge1xyXG4gICAgICAgIHN0aWNraWVzOiBzdGlja2llcyxcclxuICAgICAgICBTdGlja3k6IFN0aWNreSxcclxuXHJcbiAgICAgICAgYWRkT25lOiBmdW5jdGlvbiBhZGRPbmUobm9kZSkge1xyXG4gICAgICAgICAgICAvLyBDaGVjayB3aGV0aGVyIGl04oCZcyBhIG5vZGVcclxuICAgICAgICAgICAgaWYgKCEobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSkge1xyXG4gICAgICAgICAgICAgICAgLy8gTWF5YmUgaXTigJlzIGEgbm9kZSBsaXN0IG9mIHNvbWUgc29ydD9cclxuICAgICAgICAgICAgICAgIC8vIFRha2UgZmlyc3Qgbm9kZSBmcm9tIHRoZSBsaXN0IHRoZW5cclxuICAgICAgICAgICAgICAgIGlmIChub2RlLmxlbmd0aCAmJiBub2RlWzBdKSBub2RlID0gbm9kZVswXTtlbHNlIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgU3RpY2t5ZmlsbCBpcyBhbHJlYWR5IGFwcGxpZWQgdG8gdGhlIG5vZGVcclxuICAgICAgICAgICAgLy8gYW5kIHJldHVybiBleGlzdGluZyBzdGlja3lcclxuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBzdGlja2llcy5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNraWVzW2ldLl9ub2RlID09PSBub2RlKSByZXR1cm4gc3RpY2tpZXNbaV07XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIC8vIENyZWF0ZSBhbmQgcmV0dXJuIG5ldyBzdGlja3lcclxuICAgICAgICAgICAgcmV0dXJuIG5ldyBTdGlja3kobm9kZSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICBhZGQ6IGZ1bmN0aW9uIGFkZChub2RlTGlzdCkge1xyXG4gICAgICAgICAgICAvLyBJZiBpdOKAmXMgYSBub2RlIG1ha2UgYW4gYXJyYXkgb2Ygb25lIG5vZGVcclxuICAgICAgICAgICAgaWYgKG5vZGVMaXN0IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIG5vZGVMaXN0ID0gW25vZGVMaXN0XTtcclxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgdGhlIGFyZ3VtZW50IGlzIGFuIGl0ZXJhYmxlIG9mIHNvbWUgc29ydFxyXG4gICAgICAgICAgICBpZiAoIW5vZGVMaXN0Lmxlbmd0aCkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgLy8gQWRkIGV2ZXJ5IGVsZW1lbnQgYXMgYSBzdGlja3kgYW5kIHJldHVybiBhbiBhcnJheSBvZiBjcmVhdGVkIFN0aWNreSBpbnN0YW5jZXNcclxuICAgICAgICAgICAgdmFyIGFkZGVkU3RpY2tpZXMgPSBbXTtcclxuXHJcbiAgICAgICAgICAgIHZhciBfbG9vcCA9IGZ1bmN0aW9uIF9sb29wKGkpIHtcclxuICAgICAgICAgICAgICAgIHZhciBub2RlID0gbm9kZUxpc3RbaV07XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gSWYgaXTigJlzIG5vdCBhbiBIVE1MRWxlbWVudCDigJMgY3JlYXRlIGFuIGVtcHR5IGVsZW1lbnQgdG8gcHJlc2VydmUgMS10by0xXHJcbiAgICAgICAgICAgICAgICAvLyBjb3JyZWxhdGlvbiB3aXRoIGlucHV0IGxpc3RcclxuICAgICAgICAgICAgICAgIGlmICghKG5vZGUgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkpIHtcclxuICAgICAgICAgICAgICAgICAgICBhZGRlZFN0aWNraWVzLnB1c2godm9pZCAwKTtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gJ2NvbnRpbnVlJztcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAvLyBJZiBTdGlja3lmaWxsIGlzIGFscmVhZHkgYXBwbGllZCB0byB0aGUgbm9kZVxyXG4gICAgICAgICAgICAgICAgLy8gYWRkIGV4aXN0aW5nIHN0aWNreVxyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChzdGlja3kuX25vZGUgPT09IG5vZGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYWRkZWRTdGlja2llcy5wdXNoKHN0aWNreSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pKSByZXR1cm4gJ2NvbnRpbnVlJztcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBDcmVhdGUgYW5kIGFkZCBuZXcgc3RpY2t5XHJcbiAgICAgICAgICAgICAgICBhZGRlZFN0aWNraWVzLnB1c2gobmV3IFN0aWNreShub2RlKSk7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG5vZGVMaXN0Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgX3JldCA9IF9sb29wKGkpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChfcmV0ID09PSAnY29udGludWUnKSBjb250aW51ZTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgcmV0dXJuIGFkZGVkU3RpY2tpZXM7XHJcbiAgICAgICAgfSxcclxuICAgICAgICByZWZyZXNoQWxsOiBmdW5jdGlvbiByZWZyZXNoQWxsKCkge1xyXG4gICAgICAgICAgICBzdGlja2llcy5mb3JFYWNoKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgIHJldHVybiBzdGlja3kucmVmcmVzaCgpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZU9uZTogZnVuY3Rpb24gcmVtb3ZlT25lKG5vZGUpIHtcclxuICAgICAgICAgICAgLy8gQ2hlY2sgd2hldGhlciBpdOKAmXMgYSBub2RlXHJcbiAgICAgICAgICAgIGlmICghKG5vZGUgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkpIHtcclxuICAgICAgICAgICAgICAgIC8vIE1heWJlIGl04oCZcyBhIG5vZGUgbGlzdCBvZiBzb21lIHNvcnQ/XHJcbiAgICAgICAgICAgICAgICAvLyBUYWtlIGZpcnN0IG5vZGUgZnJvbSB0aGUgbGlzdCB0aGVuXHJcbiAgICAgICAgICAgICAgICBpZiAobm9kZS5sZW5ndGggJiYgbm9kZVswXSkgbm9kZSA9IG5vZGVbMF07ZWxzZSByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIC8vIFJlbW92ZSB0aGUgc3RpY2tpZXMgYm91bmQgdG8gdGhlIG5vZGVzIGluIHRoZSBsaXN0XHJcbiAgICAgICAgICAgIHN0aWNraWVzLnNvbWUoZnVuY3Rpb24gKHN0aWNreSkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHN0aWNreS5fbm9kZSA9PT0gbm9kZSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHN0aWNreS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICByZW1vdmU6IGZ1bmN0aW9uIHJlbW92ZShub2RlTGlzdCkge1xyXG4gICAgICAgICAgICAvLyBJZiBpdOKAmXMgYSBub2RlIG1ha2UgYW4gYXJyYXkgb2Ygb25lIG5vZGVcclxuICAgICAgICAgICAgaWYgKG5vZGVMaXN0IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIG5vZGVMaXN0ID0gW25vZGVMaXN0XTtcclxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgdGhlIGFyZ3VtZW50IGlzIGFuIGl0ZXJhYmxlIG9mIHNvbWUgc29ydFxyXG4gICAgICAgICAgICBpZiAoIW5vZGVMaXN0Lmxlbmd0aCkgcmV0dXJuO1xyXG5cclxuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBzdGlja2llcyBib3VuZCB0byB0aGUgbm9kZXMgaW4gdGhlIGxpc3RcclxuXHJcbiAgICAgICAgICAgIHZhciBfbG9vcDIgPSBmdW5jdGlvbiBfbG9vcDIoaSkge1xyXG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSBub2RlTGlzdFtpXTtcclxuXHJcbiAgICAgICAgICAgICAgICBzdGlja2llcy5zb21lKGZ1bmN0aW9uIChzdGlja3kpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoc3RpY2t5Ll9ub2RlID09PSBub2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0aWNreS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG5vZGVMaXN0Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICBfbG9vcDIoaSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIHJlbW92ZUFsbDogZnVuY3Rpb24gcmVtb3ZlQWxsKCkge1xyXG4gICAgICAgICAgICB3aGlsZSAoc3RpY2tpZXMubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgICAgICBzdGlja2llc1swXS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgLypcclxuICAgICAqIDYuIFNldHVwIGV2ZW50cyAodW5sZXNzIHRoZSBwb2x5ZmlsbCB3YXMgZGlzYWJsZWQpXHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIGluaXQoKSB7XHJcbiAgICAgICAgLy8gV2F0Y2ggZm9yIHNjcm9sbCBwb3NpdGlvbiBjaGFuZ2VzIGFuZCB0cmlnZ2VyIHJlY2FsYy9yZWZyZXNoIGlmIG5lZWRlZFxyXG4gICAgICAgIGZ1bmN0aW9uIGNoZWNrU2Nyb2xsKCkge1xyXG4gICAgICAgICAgICBpZiAod2luZG93LnBhZ2VYT2Zmc2V0ICE9IHNjcm9sbC5sZWZ0KSB7XHJcbiAgICAgICAgICAgICAgICBzY3JvbGwudG9wID0gd2luZG93LnBhZ2VZT2Zmc2V0O1xyXG4gICAgICAgICAgICAgICAgc2Nyb2xsLmxlZnQgPSB3aW5kb3cucGFnZVhPZmZzZXQ7XHJcblxyXG4gICAgICAgICAgICAgICAgU3RpY2t5ZmlsbC5yZWZyZXNoQWxsKCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAod2luZG93LnBhZ2VZT2Zmc2V0ICE9IHNjcm9sbC50b3ApIHtcclxuICAgICAgICAgICAgICAgIHNjcm9sbC50b3AgPSB3aW5kb3cucGFnZVlPZmZzZXQ7XHJcbiAgICAgICAgICAgICAgICBzY3JvbGwubGVmdCA9IHdpbmRvdy5wYWdlWE9mZnNldDtcclxuXHJcbiAgICAgICAgICAgICAgICAvLyByZWNhbGMgcG9zaXRpb24gZm9yIGFsbCBzdGlja2llc1xyXG4gICAgICAgICAgICAgICAgc3RpY2tpZXMuZm9yRWFjaChmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fcmVjYWxjUG9zaXRpb24oKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBjaGVja1Njcm9sbCgpO1xyXG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdzY3JvbGwnLCBjaGVja1Njcm9sbCk7XHJcblxyXG4gICAgICAgIC8vIFdhdGNoIGZvciB3aW5kb3cgcmVzaXplcyBhbmQgZGV2aWNlIG9yaWVudGF0aW9uIGNoYW5nZXMgYW5kIHRyaWdnZXIgcmVmcmVzaFxyXG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdyZXNpemUnLCBTdGlja3lmaWxsLnJlZnJlc2hBbGwpO1xyXG4gICAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdvcmllbnRhdGlvbmNoYW5nZScsIFN0aWNreWZpbGwucmVmcmVzaEFsbCk7XHJcblxyXG4gICAgICAgIC8vRmFzdCBkaXJ0eSBjaGVjayBmb3IgbGF5b3V0IGNoYW5nZXMgZXZlcnkgNTAwbXNcclxuICAgICAgICB2YXIgZmFzdENoZWNrVGltZXIgPSB2b2lkIDA7XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIHN0YXJ0RmFzdENoZWNrVGltZXIoKSB7XHJcbiAgICAgICAgICAgIGZhc3RDaGVja1RpbWVyID0gc2V0SW50ZXJ2YWwoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgc3RpY2tpZXMuZm9yRWFjaChmdW5jdGlvbiAoc3RpY2t5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHN0aWNreS5fZmFzdENoZWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfSwgNTAwKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIHN0b3BGYXN0Q2hlY2tUaW1lcigpIHtcclxuICAgICAgICAgICAgY2xlYXJJbnRlcnZhbChmYXN0Q2hlY2tUaW1lcik7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICB2YXIgZG9jSGlkZGVuS2V5ID0gdm9pZCAwO1xyXG4gICAgICAgIHZhciB2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lID0gdm9pZCAwO1xyXG5cclxuICAgICAgICBpZiAoJ2hpZGRlbicgaW4gZG9jdW1lbnQpIHtcclxuICAgICAgICAgICAgZG9jSGlkZGVuS2V5ID0gJ2hpZGRlbic7XHJcbiAgICAgICAgICAgIHZpc2liaWxpdHlDaGFuZ2VFdmVudE5hbWUgPSAndmlzaWJpbGl0eWNoYW5nZSc7XHJcbiAgICAgICAgfSBlbHNlIGlmICgnd2Via2l0SGlkZGVuJyBpbiBkb2N1bWVudCkge1xyXG4gICAgICAgICAgICBkb2NIaWRkZW5LZXkgPSAnd2Via2l0SGlkZGVuJztcclxuICAgICAgICAgICAgdmlzaWJpbGl0eUNoYW5nZUV2ZW50TmFtZSA9ICd3ZWJraXR2aXNpYmlsaXR5Y2hhbmdlJztcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICh2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lKSB7XHJcbiAgICAgICAgICAgIGlmICghZG9jdW1lbnRbZG9jSGlkZGVuS2V5XSkgc3RhcnRGYXN0Q2hlY2tUaW1lcigpO1xyXG5cclxuICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih2aXNpYmlsaXR5Q2hhbmdlRXZlbnROYW1lLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoZG9jdW1lbnRbZG9jSGlkZGVuS2V5XSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHN0b3BGYXN0Q2hlY2tUaW1lcigpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0gZWxzZSBzdGFydEZhc3RDaGVja1RpbWVyKCk7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKCFzZXBwdWt1KSBpbml0KCk7XHJcblxyXG4gICAgLypcclxuICAgICAqIDcuIEV4cG9zZSBTdGlja3lmaWxsXHJcbiAgICAgKi9cclxuICAgIGlmICh0eXBlb2YgbW9kdWxlICE9ICd1bmRlZmluZWQnICYmIG1vZHVsZS5leHBvcnRzKSB7XHJcbiAgICAgICAgbW9kdWxlLmV4cG9ydHMgPSBTdGlja3lmaWxsO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICB3aW5kb3cuU3RpY2t5ZmlsbCA9IFN0aWNreWZpbGw7XHJcbiAgICB9XHJcblxyXG59KSh3aW5kb3csIGRvY3VtZW50KTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9zdGlja3lmaWxsanMvZGlzdC9zdGlja3lmaWxsLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBnO1xyXG5cclxuLy8gVGhpcyB3b3JrcyBpbiBub24tc3RyaWN0IG1vZGVcclxuZyA9IChmdW5jdGlvbigpIHtcclxuXHRyZXR1cm4gdGhpcztcclxufSkoKTtcclxuXHJcbnRyeSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiBldmFsIGlzIGFsbG93ZWQgKHNlZSBDU1ApXHJcblx0ZyA9IGcgfHwgRnVuY3Rpb24oXCJyZXR1cm4gdGhpc1wiKSgpIHx8ICgxLGV2YWwpKFwidGhpc1wiKTtcclxufSBjYXRjaChlKSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiB0aGUgd2luZG93IHJlZmVyZW5jZSBpcyBhdmFpbGFibGVcclxuXHRpZih0eXBlb2Ygd2luZG93ID09PSBcIm9iamVjdFwiKVxyXG5cdFx0ZyA9IHdpbmRvdztcclxufVxyXG5cclxuLy8gZyBjYW4gc3RpbGwgYmUgdW5kZWZpbmVkLCBidXQgbm90aGluZyB0byBkbyBhYm91dCBpdC4uLlxyXG4vLyBXZSByZXR1cm4gdW5kZWZpbmVkLCBpbnN0ZWFkIG9mIG5vdGhpbmcgaGVyZSwgc28gaXQnc1xyXG4vLyBlYXNpZXIgdG8gaGFuZGxlIHRoaXMgY2FzZS4gaWYoIWdsb2JhbCkgeyAuLi59XHJcblxyXG5tb2R1bGUuZXhwb3J0cyA9IGc7XHJcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vICh3ZWJwYWNrKS9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvd2VicGFjay9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwic291cmNlUm9vdCI6IiJ9