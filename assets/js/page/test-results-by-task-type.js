let ByPageList = require('../test-results-by-task-type/by-page-list');

class TestResultsByTaskType {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.byPageList = new ByPageList(document.querySelector('.by-page-container'));
    }

    init () {
        this.byPageList.init();
    };
}

module.exports = TestResultsByTaskType;
