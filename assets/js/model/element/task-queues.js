let TaskQueue = require('./task-queue');

class TaskQueues {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.queues = {};

        [].forEach.call(element.querySelectorAll('.queue'), (queueElement) => {
            let queue = new TaskQueue(queueElement);
            this.queues[queue.getQueueId()] = queue;
        });
    }

    render (taskCount, taskCountByState) {
        let getWidthForState = (state) => {
            if (taskCount === 0) {
                return 0;
            }

            if (!taskCountByState.hasOwnProperty(state)) {
                return 0;
            }

            if (taskCountByState[state] === 0) {
                return 0;
            }

            return Math.ceil(taskCountByState[state] / taskCount * 100);
        };

        console.log(taskCountByState);

        Object.keys(taskCountByState).forEach((state) => {
            let queue = this.queues[state];
            if (queue) {
                queue.setValue(taskCountByState[state]);
                queue.setWidth(getWidthForState(state));
            }
        });
    };
}

module.exports = TaskQueues;
