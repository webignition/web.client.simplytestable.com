let FormButton = require('../model/element/form-button');
let ProgressBar = require('../model/element/progress-bar');
let TaskQueues = require('../model/element/task-queues');

class Summary {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.cancelAction = new FormButton(element.querySelector('.cancel-action'));
        this.cancelCrawlAction = new FormButton(element.querySelector('.cancel-crawl-action'));
        this.progressBar = new ProgressBar(element.querySelector('.progress-bar'));
        this.stateLabel = element.querySelector('.js-state-label');
        this.taskQueues = new TaskQueues(element.querySelector('.task-queues'));
    }

    init () {
        this._addEventListeners();
    };

    /**
     * @returns {string}
     */
    static getRenderAmmendmentEventName () {
        return 'test-progress.summary.render-ammendment';
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

    /**
     * @param {object} summaryData
     */
    render (summaryData) {
        let test = summaryData.test;

        this.progressBar.setCompletionPercent(test.completion_percent);
        this.progressBar.setStyle(test.state === 'crawling' ? 'warning' : 'default');
        this.stateLabel.innerText = summaryData.state_label;
        this.taskQueues.render(test.task_count, test.task_count_by_state);

        if (test.amendments.length > 0) {
            this.element.dispatchEvent(new CustomEvent(Summary.getRenderAmmendmentEventName(), {
                detail: test.amendments[0]
            }));
        }
    };
}

module.exports = Summary;
