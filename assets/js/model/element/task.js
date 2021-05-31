class Task {
    constructor (element) {
        this.element = element;
    }

    getState () {
        return this.element.getAttribute('data-state');
    };

    getId () {
        return parseInt(this.element.getAttribute('data-task-id'), 10);
    }

    /**
     * @param {Task} updatedTask
     */
    updateFromTask (updatedTask) {
        this.element.parentNode.replaceChild(updatedTask.element, this.element);
        this.element = updatedTask.element;
    };
}

module.exports = Task;
