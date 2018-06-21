let ByPageList = require('../test-results-by-task-type/by-page-list');
let ByErrorList = require('../test-results-by-task-type/by-error-list');

class TestResultsByTaskType {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;

        let byErrorContainerElement = document.querySelector('.by-error-container');

        this.byPageList = byErrorContainerElement ? null : new ByPageList(document.querySelector('.by-page-container'));
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
