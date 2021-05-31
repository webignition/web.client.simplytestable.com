let HttpClient = require('../../services/http-client');
let Icon = require('../element/icon');

class TestLockUnlock {
    constructor (element) {
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

    init () {
        this.element.classList.remove('invisible');
        this._render();

        this.element.addEventListener('click', this._clickEventListener.bind(this));
        this.element.addEventListener(HttpClient.getRetrievedEventName(), () => {
            this.element.classList.remove('de-emphasize');
            this._toggle();
        });
    };

    _toggle () {
        this.state = this.state === 'locked' ? 'unlocked' : 'locked';
        this._render();
    };

    _render () {
        this.icon.removePresentationClasses();

        let stateData = this.data[this.state];

        this.icon.setAvailable('fa-' + stateData.icon);
        this.action.innerText = stateData.action;
        this.description.innerText = stateData.description;
    };

    _clickEventListener () {
        event.preventDefault();
        this.icon.removePresentationClasses();

        this.element.classList.add('de-emphasize');
        this.icon.setBusy();

        HttpClient.post(this.data[this.state].url, this.element, 'default');
    };
}

module.exports = TestLockUnlock;
