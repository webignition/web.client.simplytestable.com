class TaskQueue {
    constructor (element) {
        this.element = element;
        this.value = element.querySelector('.value');
        this.label = element.querySelector('.label-value');
    }

    init () {
        this.label.style.width = this.label.getAttribute('data-width') + '%';
    };

    getQueueId () {
        return this.element.getAttribute('data-queue-id');
    };

    setValue (value) {
        this.value.innerText = value;
    };

    setWidth (width) {
        this.label.style.width = width + '%';
    };
}

module.exports = TaskQueue;
