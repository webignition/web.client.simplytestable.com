let ByPageList = require('../test-results-by-task-type/by-page-list');
let ByErrorList = require('../test-results-by-task-type/by-error-list');

class TestResultsByTaskType {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;

        let byPageContainerElement = document.querySelector('.by-page-container');
        let byErrorContainerElement = document.querySelector('.by-error-container');

        this.byPageList = byPageContainerElement ? new ByPageList(byPageContainerElement) : null;
        this.byErrorList = byErrorContainerElement ? new ByErrorList(byErrorContainerElement) : null;
    }

    init () {
        if (this.byPageList) {
            this.byPageList.init();
        }

        if (this.byErrorList) {
            this.byErrorList.init();
        }
    };
}

module.exports = TestResultsByTaskType;
