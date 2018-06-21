let ByPageList = require('../test-results-by-task-type/by-page-list');

class ByErrorList {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.byPageLists = [];

        [].forEach.call(document.querySelectorAll('.by-page-container'), (byPageContainerElement) => {
            if (byPageContainerElement.querySelectorAll('.js-sortable-item').length > 1) {
                this.byPageLists.push(new ByPageList(byPageContainerElement));
            }
        });
    };

    init () {
        this.byPageLists.forEach((byPageList) => {
            byPageList.init();
        });
    };
}

module.exports = ByErrorList;
