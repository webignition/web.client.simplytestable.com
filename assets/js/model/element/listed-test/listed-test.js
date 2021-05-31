class ListedTest {
    constructor (element) {
        this.init(element);
    }

    init (element) {
        this.element = element;
    }

    enable () {};

    getTestId () {
        return this.element.getAttribute('data-test-id');
    };

    getState () {
        return this.element.getAttribute('data-state');
    }

    isFinished () {
        return this.element.classList.contains('finished');
    }

    renderFromListedTest (listedTest) {
        if (this.isFinished()) {
            return;
        }

        if (this.getState() !== listedTest.getState()) {
            this.element.parentNode.replaceChild(listedTest.element, this.element);
            this.init(listedTest.element);
            this.enable();
        }
    };

    getType () {
        return 'ListedTest';
    };
}

module.exports = ListedTest;
