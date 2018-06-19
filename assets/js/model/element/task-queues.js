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
                // console.log(state, getWidthForState(state), queue);
                queue.setValue(taskCountByState[state]);
                queue.setWidth(getWidthForState(state));
            }

        });

        // console.log(this.summaryData.remote_test.task_count_by_state);
        //
        // Object.entries(this.summaryData.remote_test.task_count_by_state).forEach((foo, bar) => {
        //     console.log(foo);
        //     console.log(bar);
        // });



        // for (let state in this.summaryData.remote_test.task_count_by_state) {
        //     console.log(state);
        //
        //     // if (latestTestData.remote_test.task_count_by_state.hasOwnProperty(state)) {
        //     //     var label = $('#task-queue-' + state + ' .bar .label');
        //     //     if (label.length) {
        //     //         var width = getWidthForState(state);
        //     //
        //     //         if (width !== label.attr('data-width')) {
        //     //             label.attr('data-width', width);
        //     //             label.animate({
        //     //                 'width':label.attr('data-width') + '%'
        //     //             });
        //     //         }
        //     //
        //     //         $('.value', label).text(latestTestData.remote_test.task_count_by_state[state]);
        //     //     }
        //     // }
        // }
    };
}

module.exports = TaskQueues;
