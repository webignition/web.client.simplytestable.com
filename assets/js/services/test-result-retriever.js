let HttpClient = require('./http-client');
let ProgressBar = require('../model/element/progress-bar');

class TestResultRetriever {
    constructor (element) {
        this.document = element.ownerDocument;
        this.element = element;
        this.statusUrl = element.getAttribute('data-status-url');
        this.unretrievedTaskIdsUrl = element.getAttribute('data-unretrieved-task-ids-url');
        this.retrieveTasksUrl = element.getAttribute('data-retrieve-tasks-url');
        this.summaryUrl = element.getAttribute('data-summary-url');
        this.progressBar = new ProgressBar(this.element.querySelector('.progress-bar'));
        this.summary = element.querySelector('.summary');
    }

    init () {
        this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));

        this._retrievePreparingStatus();
    };

    getRetrievedEventName () {
        return 'test-result-retriever.retrieved';
    };

    /**
     * @param {CustomEvent} event
     * @private
     */
    _httpRequestRetrievedEventListener (event) {
        let response = event.detail.response;
        let requestId = event.detail.requestId;

        if (requestId === 'retrievePreparingStatus') {
            let completionPercent = response.completion_percent;

            this.progressBar.setCompletionPercent(completionPercent);

            if (completionPercent >= 100) {
                this._retrieveFinishedSummary();
            } else {
                this._displayPreparingSummary(response);
                this._retrieveNextRemoveTaskIdCollection();
            }
        }

        if (requestId === 'retrieveNextRemoveTaskIdCollection') {
            this._retrieveNextRemoteTaskCollection(response);
        }

        if (requestId === 'retrieveNextRemoteTaskCollection') {
            this._retrievePreparingStatus();
        }

        if (requestId === 'retrieveFinishedSummary') {
            let retrievedSummaryContainer = this.document.createElement('div');
            retrievedSummaryContainer.innerHTML = response;

            let retrievedEvent = new CustomEvent(this.getRetrievedEventName(), {
                detail: retrievedSummaryContainer.querySelector('.listed-test')
            });

            this.element.dispatchEvent(retrievedEvent);
        }
    };

    _retrievePreparingStatus () {
        HttpClient.getJson(this.statusUrl, this.element, 'retrievePreparingStatus');
    };

    _retrieveNextRemoveTaskIdCollection () {
        HttpClient.getJson(this.unretrievedTaskIdsUrl, this.element, 'retrieveNextRemoveTaskIdCollection');
    };

    _retrieveNextRemoteTaskCollection (remoteTaskIds) {
        HttpClient.post(this.retrieveTasksUrl, this.element, 'retrieveNextRemoteTaskCollection', 'remoteTaskIds=' + remoteTaskIds.join(','));
    };

    _retrieveFinishedSummary () {
        HttpClient.getText(this.summaryUrl, this.element, 'retrieveFinishedSummary');
    };

    _createPreparingSummary (statusData) {
        let localTaskCount = statusData.local_task_count;
        let remoteTaskCount = statusData.remote_task_count;

        if (localTaskCount === undefined && remoteTaskCount === undefined) {
            return 'Preparing results &hellip;';
        }

        return 'Preparing &hellip; collected <strong class="local-task-count">' + localTaskCount + '</strong> results of <strong class="remote-task-count">' + remoteTaskCount + '</strong>';
    };

    _displayPreparingSummary (statusData) {
        this.element.querySelector('.preparing .summary').innerHTML = this._createPreparingSummary(statusData);
    };
}

module.exports = TestResultRetriever;
