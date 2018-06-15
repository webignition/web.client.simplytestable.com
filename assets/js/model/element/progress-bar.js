class ProgressBar {
    constructor (element) {
        this.element = element;
    }

    setCompletionPercent (completionPercent) {
        this.element.style.width = completionPercent + '%';
        this.element.setAttribute('aria-valuenow', completionPercent);
    }
}

module.exports = ProgressBar;
