let FormButton = require('../model/element/form-button');

class TestProgress {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.cancelAction = new FormButton(document.querySelector('.cancel-action'));
        this.cancelCrawlAction = new FormButton(document.querySelector('.cancel-crawl-action'));

    }

    init () {
        this._addEventListeners();
    };

    _addEventListeners () {
        this.cancelAction.element.addEventListener('click', this._cancelActionClickEventListener.bind(this));
        this.cancelCrawlAction.element.addEventListener('click', this._cancelCrawlActionClickEventListener.bind(this));
    };

    _cancelActionClickEventListener (event) {
        this.cancelAction.markAsBusy();
        this.cancelAction.deEmphasize();
    };

    _cancelCrawlActionClickEventListener (event) {
        this.cancelCrawlAction.markAsBusy();
        this.cancelCrawlAction.deEmphasize();
    };
}

module.exports = TestProgress;
