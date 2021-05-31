class TaskListPagination {
    constructor (pageLength, taskCount) {
        this.pageLength = pageLength;
        this.taskCount = taskCount;
        this.pageCount = Math.ceil(taskCount / pageLength);
        this.element = null;
    }

    static getSelector () {
        return '.pagination';
    };

    /**
     * @returns {string}
     */
    static getSelectPageEventName () {
        return 'task-list-pagination.select-page';
    };

    /**
     * @returns {string}
     */
    static getSelectPreviousPageEventName () {
        return 'task-list-pagination.select-previous-page';
    }

    /**
     * @returns {string}
     */
    static getSelectNextPageEventName () {
        return 'task-list-pagination.select-next-page';
    }

    init (element) {
        this.element = element;
        this.pageActions = element.querySelectorAll('a');
        this.previousAction = element.querySelector('[data-role=previous]');
        this.nextAction = element.querySelector('[data-role=next]');

        [].forEach.call(this.pageActions, (pageActions) => {
            pageActions.addEventListener('click', (event) => {
                event.preventDefault();

                let actionContainer = pageActions.parentNode;
                if (!actionContainer.classList.contains('active')) {
                    let role = pageActions.getAttribute('data-role');

                    if (role === 'showPage') {
                        this.element.dispatchEvent(new CustomEvent(TaskListPagination.getSelectPageEventName(), {
                            detail: parseInt(pageActions.getAttribute('data-page-index'), 10)
                        }));
                    }

                    if (role === 'previous') {
                        this.element.dispatchEvent(new CustomEvent(TaskListPagination.getSelectPreviousPageEventName()));
                    }

                    if (role === 'next') {
                        this.element.dispatchEvent(new CustomEvent(TaskListPagination.getSelectNextPageEventName()));
                    }
                }
            });
        });
    };

    createMarkup () {
        let markup = '<ul class="pagination">';

        markup += '<li class="is-xs previous-next previous disabled hidden-lg hidden-md hidden-sm"><a href="#" data-role="previous"><i class="fa fa-caret-left"></i> Previous</a></li>';
        markup += '<li class="hidden-lg hidden-md hidden-sm disabled"><span>Page <strong class="page-number">1</strong> of <strong>' + this.pageCount + '</strong></span></li>';

        for (let pageIndex = 0; pageIndex < this.pageCount; pageIndex++) {
            let startIndex = (pageIndex * this.pageLength) + 1;
            let endIndex = Math.min(startIndex + this.pageLength - 1, this.taskCount);

            markup += '<li class="is-not-xs hidden-xs ' + (pageIndex === 0 ? 'active' : '') + '"><a href="#" data-page-index="' + pageIndex + '" data-role="showPage">' + startIndex + ' … ' + endIndex + '</a></li>';
        }

        markup += '<li class="next previous-next hidden-lg hidden-md hidden-sm"><a href="#" data-role="next">Next <i class="fa fa-caret-right"></i></a></li>';
        markup += '</ul>';

        return markup;
    };

    /**
     * @returns {boolean}
     */
    isRequired () {
        return this.taskCount > this.pageLength;
    };

    /**
     * @returns {boolean}
     */
    isRendered () {
        return this.element !== null;
    };

    /**
     * @param {number} pageIndex
     */
    selectPage (pageIndex) {
        [].forEach.call(this.pageActions, (pageAction) => {
            let isActive = parseInt(pageAction.getAttribute('data-page-index'), 10) === pageIndex;
            let actionContainer = pageAction.parentNode;

            if (isActive) {
                actionContainer.classList.add('active');
            } else {
                actionContainer.classList.remove('active');
            }
        });

        this.element.querySelector('.page-number').innerText = (pageIndex + 1);
        this.previousAction.parentElement.classList.remove('disabled');
        this.nextAction.parentElement.classList.remove('disabled');

        if (pageIndex === 0) {
            this.previousAction.parentElement.classList.add('disabled');
        } else if (pageIndex === this.pageCount - 1) {
            this.nextAction.parentElement.classList.add('disabled');
        }
    };
}

module.exports = TaskListPagination;
