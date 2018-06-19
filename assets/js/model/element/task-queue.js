class TaskQueue {
    constructor (element) {
        this.element = element;
        this.value = element.querySelector('.value');
        this.label = element.querySelector('.label-value');
    }

    getQueueId () {
        return this.element.getAttribute('data-queue-id');
    };

    setValue (value) {
        this.value.innerText = value;
    };

    setWidth (width) {
        console.log(this.label);

        // this.label.setAttribute('data-width', width);
        // this.label.style('width', width + '%');

        // label.animate({
        //     'width':label.attr('data-width') + '%'
        // });

        this.label.style.width = width + '%';
    };
}

module.exports = TaskQueue;
