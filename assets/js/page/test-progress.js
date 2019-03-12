let Summary = require('../test-progress/summary');
let TaskList = require('../test-progress/task-list');
let TaskListPagination = require('../test-progress/task-list-paginator');
let TestAlertContainer = require('../model/element/test-alert-container');
let HttpClient = require('../services/http-client');

class TestProgress {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.pageLength = 100;
        this.document = document;
        this.summary = new Summary(document.querySelector('.js-summary'));
        this.taskListElement = document.querySelector('.test-progress-tasks');
        this.taskList = new TaskList(this.taskListElement, this.pageLength);
        this.alertContainer = new TestAlertContainer(document.querySelector('.alert-container'));
        this.taskListPagination = null;
        this.taskListIsInitialized = false;
        this.summaryData = null;
    }

    init () {
        this.summary.init();
        this.alertContainer.init();
        this._addEventListeners();

        this._refreshSummary();
    };

    _addEventListeners () {
        this.summary.element.addEventListener(Summary.getRenderAmmendmentEventName(), () => {
            this.alertContainer.renderUrlLimitNotification();
        });

        this.document.body.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
        this.taskListElement.addEventListener(TaskList.getInitializedEventName(), this._taskListInitializedEventListener.bind(this));
    };

    _addPaginationEventListeners () {
        this.taskListPagination.element.addEventListener(TaskListPagination.getSelectPageEventName(), (event) => {
            let pageIndex = event.detail;

            this.taskList.setCurrentPageIndex(pageIndex);
            this.taskListPagination.selectPage(pageIndex);
            this.taskList.render(pageIndex);
        });

        this.taskListPagination.element.addEventListener(TaskListPagination.getSelectPreviousPageEventName(), (event) => {
            let pageIndex = Math.max(this.taskList.currentPageIndex - 1, 0);

            this.taskList.setCurrentPageIndex(pageIndex);
            this.taskListPagination.selectPage(pageIndex);
            this.taskList.render(pageIndex);
        });

        this.taskListPagination.element.addEventListener(TaskListPagination.getSelectNextPageEventName(), (event) => {
            let pageIndex = Math.min(this.taskList.currentPageIndex + 1, this.taskListPagination.pageCount - 1);

            this.taskList.setCurrentPageIndex(pageIndex);
            this.taskListPagination.selectPage(pageIndex);
            this.taskList.render(pageIndex);
        });
    };

    _httpRequestRetrievedEventListener (event) {
        if (event.detail.requestId === 'test-progress.summary.update') {
            if (event.detail.request.responseURL.indexOf(window.location.toString()) !== 0) {
                this.summary.progressBar.setCompletionPercent(100);
                window.location.href = event.detail.request.responseURL;

                return;
            }

            this.summaryData = event.detail.response;

            if (this.summaryData.test) {
                let state = this.summaryData.test.state;
                let taskCount = this.summaryData.test.task_count;
                let isStateQueuedOrInProgress = ['queued', 'in-progress'].indexOf(state) !== -1;

                this._setBodyJobClass(this.document.body.classList, state);
                this.summary.render(this.summaryData);

                if (taskCount > 0 && isStateQueuedOrInProgress && !this.taskListIsInitialized && !this.taskList.isInitializing) {
                    this.taskList.init();
                }
            }
        }

        window.setTimeout(() => {
            this._refreshSummary();
        }, 3000);
    };

    _taskListInitializedEventListener () {
        this.taskListIsInitialized = true;
        this.taskList.render(0);
        this.taskListPagination = new TaskListPagination(this.pageLength, this.summaryData.test.task_count);

        if (this.taskListPagination.isRequired() && !this.taskListPagination.isRendered()) {
            this.taskListPagination.init(this._createPaginationElement());
            this.taskList.setPaginationElement(this.taskListPagination.element);
            this._addPaginationEventListeners();
        }
    };

    /**
     * @returns {Element}
     * @private
     */
    _createPaginationElement () {
        let container = this.document.createElement('div');
        container.innerHTML = this.taskListPagination.createMarkup();

        return container.querySelector(TaskListPagination.getSelector());
    }

    _refreshSummary () {
        let summaryUrl = this.document.body.getAttribute('data-summary-url');
        let now = new Date();

        HttpClient.getJson(summaryUrl + '?timestamp=' + now.getTime(), this.document.body, 'test-progress.summary.update');
    };
    /**
     *
     * @param {DOMTokenList} bodyClassList
     * @param {string} testState
     * @private
     */
    _setBodyJobClass (bodyClassList, testState) {
        let jobClassPrefix = 'job-';
        bodyClassList.forEach((className, index, classList) => {
            if (className.indexOf(jobClassPrefix) === 0) {
                classList.remove(className);
            }
        });

        bodyClassList.add('job-' + testState);
    };
}

module.exports = TestProgress;
