let TaskListModel = require('../model/element/task-list');
let Icon = require('../model/element/icon');
let TaskIdList = require('./task-id-list');
let HttpClient = require('../services/http-client');

class TaskList {
    /**
     * @param {Element} element
     * @param {number} pageLength
     */
    constructor (element, pageLength) {
        this.element = element;
        this.currentPageIndex = 0;
        this.pageLength = pageLength;
        this.taskIdList = null;
        this.isInitializing = false;
        this.taskListModels = {};
        this.heading = element.querySelector('h2');

        /**
         * @type {Icon}
         */
        this.busyIcon = this._createBusyIcon();
        this.heading.appendChild(this.busyIcon.element);
    }

    init () {
        this.isInitializing = true;
        this.element.classList.remove('hidden');
        this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));
        this._requestTaskIds();
    };

    /**
     * @param {number} index
     */
    setCurrentPageIndex (index) {
        this.currentPageIndex = index;
    }

    /**
     * @param {Element} paginationElement
     */
    setPaginationElement (paginationElement) {
        this.heading.insertAdjacentElement('afterend', paginationElement);
    }

    /**
     * @returns {string}
     */
    static getInitializedEventName () {
        return 'task-list.initialized';
    };

    _httpRequestRetrievedEventListener (event) {
        let requestId = event.detail.requestId;
        let response = event.detail.response;

        if (requestId === 'requestTaskIds') {
            this.taskIdList = new TaskIdList(response, this.pageLength);
            this.isInitializing = false;
            this.element.dispatchEvent(new CustomEvent(TaskList.getInitializedEventName()));
        }

        if (requestId === 'retrieveTaskPage') {
            let taskListModel = new TaskListModel(this._createTaskListElementFromHtml(response));
            let pageIndex = taskListModel.getPageIndex();

            this.taskListModels[pageIndex] = taskListModel;
            this.render(pageIndex);
            this._retrieveTaskSetWithDelay(
                pageIndex,
                taskListModel.getTasksByStates(['in-progress', 'queued-for-assignment', 'queued']).slice(0, 10)
            );
        }

        if (requestId === 'retrieveTaskSet') {
            let updatedTaskListModel = new TaskListModel(this._createTaskListElementFromHtml(response));
            let pageIndex = updatedTaskListModel.getPageIndex();
            let taskListModel = this.taskListModels[pageIndex];

            taskListModel.updateFromTaskList(updatedTaskListModel);
            this._retrieveTaskSetWithDelay(
                pageIndex,
                taskListModel.getTasksByStates(['in-progress', 'queued-for-assignment', 'queued']).slice(0, 10)
            );
        }
    };

    _requestTaskIds () {
        HttpClient.getJson(this.element.getAttribute('data-task-ids-url'), this.element, 'requestTaskIds');
    };

    /**
     * @param {number} pageIndex
     */
    render (pageIndex) {
        this.busyIcon.element.classList.remove('hidden');

        let hasTaskListElementForPage = Object.keys(this.taskListModels).includes(pageIndex.toString(10));
        if (!hasTaskListElementForPage) {
            this._retrieveTaskPage(pageIndex);

            return;
        }

        let taskListElement = this.taskListModels[pageIndex];

        if (pageIndex === this.currentPageIndex) {
            let renderedTaskListElement = new TaskListModel(this.element.querySelector('.task-list'));

            if (renderedTaskListElement.hasPageIndex()) {
                let currentPageListElement = this.element.querySelector('.task-list');
                let selectedPageListElement = this.taskListModels[this.currentPageIndex].element;

                currentPageListElement.parentNode.replaceChild(selectedPageListElement, currentPageListElement);
            } else {
                this.element.appendChild(taskListElement.element);
            }
        }

        this.busyIcon.element.classList.add('hidden');
    };

    /**
     * @param {number} pageIndex
     * @private
     */
    _retrieveTaskPage (pageIndex) {
        let taskIds = this.taskIdList.getForPage(pageIndex);
        let postData = 'pageIndex=' + pageIndex + '&taskIds[]=' + taskIds.join('&taskIds[]=');

        HttpClient.post(
            this.element.getAttribute('data-tasklist-url'),
            this.element,
            'retrieveTaskPage',
            postData
        );
    };

    /**
     * @param {number} pageIndex
     * @param {Task[]} tasks
     * @private
     */
    _retrieveTaskSetWithDelay (pageIndex, tasks) {
        window.setTimeout(() => {
            this._retrieveTaskSet(pageIndex, tasks);
        }, 1000);
    };

    /**
     * @param {number} pageIndex
     * @param {Task[]} tasks
     * @private
     */
    _retrieveTaskSet (pageIndex, tasks) {
        if (this.currentPageIndex === pageIndex && tasks.length) {
            let taskIds = [];

            tasks.forEach(function (task) {
                taskIds.push(task.getId());
            });

            let postData = 'pageIndex=' + pageIndex + '&taskIds[]=' + taskIds.join('&taskIds[]=');

            HttpClient.post(
                this.element.getAttribute('data-tasklist-url'),
                this.element,
                'retrieveTaskSet',
                postData
            );
        }
    };

    /**
     * @param {string} html
     * @returns {Element}
     * @private
     */
    _createTaskListElementFromHtml (html) {
        let container = this.element.ownerDocument.createElement('div');
        container.innerHTML = html;

        return container.querySelector('.task-list');
    };

    /**
     * @returns {Icon}
     * @private
     */
    _createBusyIcon () {
        let container = this.element.ownerDocument.createElement('div');
        container.innerHTML = '<i class="fa"></i>';

        let icon = new Icon(container.querySelector('.fa'));
        icon.setBusy();

        return icon;
    }
}

module.exports = TaskList;
