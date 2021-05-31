class ProgressBar {
    constructor (element) {
        this.element = element;
    }

    setCompletionPercent (completionPercent) {
        this.element.style.width = completionPercent + '%';
        this.element.setAttribute('aria-valuenow', completionPercent);
    }

    setStyle (style) {
        this._removePresentationalClasses();

        if (style === 'warning') {
            this.element.classList.add('progress-bar-warning');
        }
    }

    _removePresentationalClasses () {
        let presentationalClassPrefix = 'progress-bar-';

        this.element.classList.forEach((className, index, classList) => {
            if (className.indexOf(presentationalClassPrefix) === 0) {
                classList.remove(className);
            }
        });
    };
}

module.exports = ProgressBar;
