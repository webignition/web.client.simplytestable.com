let FormButton = require('../model/element/form-button');

class Summary {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.cancelAction = new FormButton(element.querySelector('.cancel-action'));
        this.cancelCrawlAction = new FormButton(element.querySelector('.cancel-crawl-action'));
    }

    init () {
        this._addEventListeners();
    };

    _addEventListeners () {
        this.cancelAction.element.addEventListener('click', this._cancelActionClickEventListener.bind(this));
        this.cancelCrawlAction.element.addEventListener('click', this._cancelCrawlActionClickEventListener.bind(this));
    };

    _cancelActionClickEventListener () {
        this.cancelAction.markAsBusy();
        this.cancelAction.deEmphasize();
    };

    _cancelCrawlActionClickEventListener () {
        this.cancelCrawlAction.markAsBusy();
        this.cancelCrawlAction.deEmphasize();
    };
}

module.exports = Summary;
