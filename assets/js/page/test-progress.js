let Summary = require('../test-progress/summary');

class TestProgress {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.summary = new Summary(document.querySelector('.js-summary'));
    }

    init () {
        this.summary.init();
    };
}

module.exports = TestProgress;
