let ProgressBar = require('../model/element/progress-bar');
let HttpClient = require('../services/http-client');

class TestResultsPreparing {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.unretrievedRemoteTaskIdsUrl = document.body.getAttribute('data-unretrieved-remote-task-ids-url');
        this.resultsRetrieveUrl = document.body.getAttribute('data-results-retrieve-url');
        this.retrievalStatsUrl = document.body.getAttribute('data-preparing-stats-url');
        this.resultsUrl = document.body.getAttribute('data-results-url');
        this.progressBar = new ProgressBar(document.querySelector('.progress-bar'));
        this.completionPercentValue = document.querySelector('.completion-percent-value');
        this.localTaskCount = document.querySelector('.local-task-count');
        this.remoteTaskCount = document.querySelector('.remote-task-count');
    }

    init () {
        this.document.body.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
        this._checkCompletionStatus();
    };

    _checkCompletionStatus () {
        if (parseInt(document.body.getAttribute('data-remaining-tasks-to-retrieve-count'), 10) === 0) {
            window.location.href = this.resultsUrl;
        } else {
            this._retrieveNextRemoteTaskIdCollection();
        }
    }

    _httpRequestRetrievedEventListener (event) {
        let requestId = event.detail.requestId;
        let response = event.detail.response;

        if (requestId === 'retrieveNextRemoteTaskIdCollection') {
            this._retrieveRemoteTaskCollection(response);
        }

        if (requestId === 'retrieveRemoteTaskCollection') {
            this._retrieveRetrievalStats();
        }

        if (requestId === 'retrieveRetrievalStats') {
            if (!response.hasOwnProperty('completion_percent')) {
                response.completion_percent = 0;
            }

            if (!response.hasOwnProperty('remaining_tasks_to_retrieve_count')) {
                response.remaining_tasks_to_retrieve_count = 0;
            }

            if (!response.hasOwnProperty('local_task_count')) {
                response.local_task_count = 0;
            }

            if (!response.hasOwnProperty('remote_task_count')) {
                response.remote_task_count = 0;
            }

            let completionPercent = response.completion_percent;

            this.document.body.setAttribute('data-remaining-tasks-to-retrieve-count', response.remaining_tasks_to_retrieve_count.toString(10));
            this.completionPercentValue.innerText = completionPercent;
            this.progressBar.setCompletionPercent(completionPercent);
            this.localTaskCount.innerText = response.local_task_count;
            this.remoteTaskCount.innerText = response.remote_task_count;

            this._checkCompletionStatus();
        }
    };

    _retrieveNextRemoteTaskIdCollection () {
        HttpClient.getJson(this.unretrievedRemoteTaskIdsUrl, this.document.body, 'retrieveNextRemoteTaskIdCollection');
    };

    _retrieveRemoteTaskCollection (remoteTaskIds) {
        HttpClient.post(this.resultsRetrieveUrl, this.document.body, 'retrieveRemoteTaskCollection', 'remoteTaskIds=' + remoteTaskIds.join(','));
    };

    _retrieveRetrievalStats () {
        HttpClient.getJson(this.retrievalStatsUrl, this.document.body, 'retrieveRetrievalStats');
    }
}

module.exports = TestResultsPreparing;
