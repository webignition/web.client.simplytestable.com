let Task = require('./task');

class TaskList {
    constructor (element) {
        this.element = element;
        this.pageIndex = element ? parseInt(element.getAttribute('data-page-index'), 10) : null;
        this.tasks = {};

        if (element) {
            [].forEach.call(element.querySelectorAll('.task'), (taskElement) => {
                let task = new Task(taskElement);
                this.tasks[task.getId()] = task;
            });
        }
    }

    /**
     * @returns {number | null}
     */
    getPageIndex () {
        return this.pageIndex;
    }

    /**
     * @returns {boolean}
     */
    hasPageIndex () {
        return this.pageIndex !== null;
    }

    /**
     * @param {string[]} states
     *
     * @returns {Task[]}
     */
    getTasksByStates (states) {
        const statesLength = states.length;
        let tasks = [];

        for (let stateIndex = 0; stateIndex < statesLength; stateIndex++) {
            let state = states[stateIndex];

            tasks = tasks.concat(this.getTasksByState(state));
        }

        return tasks;
    };

    /**
     * @param {string} state
     *
     * @returns {Task[]}
     */
    getTasksByState (state) {
        let tasks = [];
        Object.keys(this.tasks).forEach((taskId) => {
            let task = this.tasks[taskId];

            if (task.getState() === state) {
                tasks.push(task);
            }
        });

        return tasks;
    };

    /**
     * @param {TaskList} updatedTaskList
     */
    updateFromTaskList (updatedTaskList) {
        Object.keys(updatedTaskList.tasks).forEach((taskId) => {
            let updatedTask = updatedTaskList.tasks[taskId];
            let task = this.tasks[updatedTask.getId()];

            task.updateFromTask(updatedTask);
        });
    };
}

module.exports = TaskList;
