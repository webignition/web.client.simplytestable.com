let HttpClient = require('../../services/http-client');

class TestLockUnlock {
    constructor (element) {
        this.element = element;
        this.state = element.getAttribute('data-state');
        this.data = {
            locked: JSON.parse(element.getAttribute('data-locked')),
            unlocked: JSON.parse(element.getAttribute('data-unlocked'))
        };
        this.icon = element.querySelector('.fa');
        this.action = element.querySelector('.action');
        this.description = element.querySelector('.description');

        this.iconClasses = [
            this.data.locked.icon,
            this.data.unlocked.icon
        ];
    }

    init () {
        this.element.classList.remove('invisible');
        this._render();

        this.element.addEventListener('click', this._clickEventListener.bind(this));
        this.element.addEventListener(HttpClient.getRetrievedEventName(), () => {
            this.element.classList.remove('de-emphasize');
            this.icon.classList.remove('fa-spin', 'fa-spinner');
            this._toggle();
        });
    };

    _toggle () {
        this.state = this.state === 'locked' ? 'unlocked' : 'locked';
        this._render();
    };

    _render () {
        this._removeStateIcons();

        let stateData = this.data[this.state];
        this.icon.classList.add(this._createIconClassName(stateData.icon));
        this.action.innerText = stateData.action;
        this.description.innerText = stateData.description;
    };

    _removeStateIcons () {
        this.iconClasses.forEach((iconClass) => {
            this.icon.classList.remove(this._createIconClassName(iconClass));
        });
    };

    _clickEventListener (event) {
        event.preventDefault();

        this._removeStateIcons();
        this.element.classList.add('de-emphasize');
        this.icon.classList.add('fa-spin', 'fa-spinner');

        HttpClient.post(this.data[this.state].url, this.element, 'default');
    };

    /**
     * @param {string} name
     * @returns {string}
     * @private
     */
    _createIconClassName (name) {
        return 'fa-' + name;
    }
}

module.exports = TestLockUnlock;
