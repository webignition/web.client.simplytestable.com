let ListedTest = require('../model/element/listed-test/listed-test');
let PreparingListedTest = require('../model/element/listed-test/preparing-listed-test');
let ProgressingListedTest = require('../model/element/listed-test/progressing-listed-test');
let CrawlingListedTest = require('../model/element/listed-test/crawling-listed-test');

class ListedTestFactory {
    /**
     * @param {Element} element
     *
     * @returns {ListedTest}
     */
    static createFromElement (element) {
        if (element.classList.contains('requires-results')) {
            return new PreparingListedTest(element);
        }

        let state = element.getAttribute('data-state');

        if (state === 'in-progress') {
            return new ProgressingListedTest(element);
        }

        if (state === 'crawling') {
            return new CrawlingListedTest(element);
        }

        return new ListedTest(element);
    }
}

module.exports = ListedTestFactory;
