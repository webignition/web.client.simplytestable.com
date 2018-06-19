let FormButton = require('../model/element/form-button');
let ProgressBar = require('../model/element/progress-bar');
let TaskQueues = require('../model/element/task-queues');
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
        this.progressBar = new ProgressBar(element.querySelector('.progress-bar'));
        this.stateLabel = element.querySelector('.js-state-label');
        this.summaryData = null;

        this.taskQueues = new TaskQueues(element.querySelector('.task-queues'));
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
        this._render();

        window.setTimeout(() => {
            this._update();
        }, 3000);
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

        HttpClient.getJson(updateUrl, this.element, 'default');
    };

    _render () {
        this.progressBar.setCompletionPercent(this.summaryData.remote_test.completion_percent);
        this.progressBar.setStyle(this.summaryData.test.state === 'crawling' ? 'warning' : 'default');
        this.stateLabel.innerText = this.summaryData.state_label;
        this.taskQueues.render(this.summaryData.remote_test.task_count, this.summaryData.remote_test.task_count_by_state);
    };
}

module.exports = Summary;
