let Icon = require('./icon');

class FormButton {
    constructor (element) {
        let iconElement = element.querySelector(Icon.getSelector());

        this.element = element;
        this.icon = iconElement ? new Icon(iconElement) : null;
    }

    markAsBusy () {
        if (this.icon) {
            this.icon.setBusy();
            this.element.classList.add('de-emphasize');
        }
    }

    markAsAvailable () {
        if (this.icon) {
            this.icon.setAvailable('fa-caret-right');

            const iconClassList = this.icon.classList;

            this.element.classList.remove('de-emphasize');
            iconClassList.remove('fa-spinner', 'fa-spin');
            iconClassList.add('fa-caret-right');
        }
    }

    markSucceeded () {
        if (this.icon) {
            this.icon.setSuccessful();
        }
    }

    disable () {
        this.element.setAttribute('disabled', 'disabled');
    }

    enable () {
        this.element.removeAttribute('disabled');
    }
}

module.exports = FormButton;
