class FormButton {
    constructor (element) {
        this.element = element;
        this.icon = element.querySelector('.fa');
    }

    startSpinning () {
        if (this.icon) {
            const iconClassList = this.icon.classList;

            iconClassList.remove('fa-caret-right');
            iconClassList.add('fa-spinner', 'fa-spin');
            this.element.classList.add('de-emphasize');
        }
    }

    stopSpinning () {
        if (this.icon) {
            const iconClassList = this.icon.classList;

            this.element.classList.remove('de-emphasize');
            iconClassList.remove('fa-spinner', 'fa-spin');
            iconClassList.add('fa-caret-right');
        }
    }

    markSucceeded () {
        if (this.icon) {
            const iconClassList = this.icon.classList;

            iconClassList.remove('fa-caret-right');
            iconClassList.add('fa-check');
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