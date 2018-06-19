let HttpClient = require('../../services/http-client');
let AlertFactory = require('../../services/alert-factory');

class TestAlertContainer {
    constructor (element) {
        this.element = element;
        this.alert = element.querySelector('.alert-ammendment');
    }

    init () {
        if (!this.alert) {
            this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
        }
    }

    _httpRequestRetrievedEventListener (event) {
        let alert = AlertFactory.createFromContent(this.element.ownerDocument, event.detail.response);
        alert.setStyle('info');
        alert.wrapContentInContainer();
        alert.element.classList.add('alert-ammendment');

        this.element.appendChild(alert.element);
        this.alert = alert;

        this.element.classList.add('reveal');
        this.element.addEventListener('transitionend', () => {
            this.alert.element.classList.add('reveal');
        });

    };

    renderUrlLimitNotification () {
        if (!this.alert) {
            HttpClient.get(this.element.getAttribute('data-url-limit-notification-url'), 'text', this.element, 'default');
        }
    };
}

module.exports = TestAlertContainer;
