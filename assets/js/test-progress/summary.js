let FormButton = require('../model/element/form-button');
let HttpClient = require('../services/http-client');

class Summary {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.sourceUrl = element.getAttribute('data-source-url');
        this.cancelAction = new FormButton(element.querySelector('.cancel-action'));
        this.cancelCrawlAction = new FormButton(element.querySelector('.cancel-crawl-action'));
        this.summaryData = null;
    }

    init () {
        this._addEventListeners();
        this._update();
    };

    _addEventListeners () {
        this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
        this.cancelAction.element.addEventListener('click', this._cancelActionClickEventListener.bind(this));
        this.cancelCrawlAction.element.addEventListener('click', this._cancelCrawlActionClickEventListener.bind(this));
    };

    _httpRequestRetrievedEventListener (event) {
        this.summaryData = event.detail.response;
    };

    _cancelActionClickEventListener () {
        this.cancelAction.markAsBusy();
        this.cancelAction.deEmphasize();
    };

    _cancelCrawlActionClickEventListener () {
        this.cancelCrawlAction.markAsBusy();
        this.cancelCrawlAction.deEmphasize();
    };

    _update () {
        let now = new Date();
        let updateUrl = this.sourceUrl + '?timestamp=' + now.getTime();

        console.log(updateUrl);

        HttpClient.getJson(updateUrl, this.element, 'default');
    };
}

module.exports = Summary;
