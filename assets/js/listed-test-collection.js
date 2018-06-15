let ListedTest = require('./listed-test');
let ListedTestFactory = require('./listed-test-factory');

class ListedTestCollection {
    constructor () {
        this.listedTests = {};
    }

    /**
     * @param {ListedTest} listedTest
     */
    add (listedTest) {
        this.listedTests[listedTest.getTestId()] = listedTest;
    };

    /**
     * @param {ListedTest} listedTest
     */
    remove (listedTest) {
        if (this.contains(listedTest)) {
            delete this.listedTests[listedTest.getTestId()];
        }
    };

    /**
     * @param {ListedTest} listedTest
     *
     * @returns {boolean}
     */
    contains (listedTest) {
        return this.containsTestId(listedTest.getTestId());
    };

    /**
     * @param {number} testId
     *
     * @returns {boolean}
     */
    containsTestId (testId) {
        return Object.keys(this.listedTests).includes(testId);
    }

    /**
     * @param {number} testId
     *
     * @returns {ListedTest|null}
     */
    get (testId) {
        return this.containsTestId(testId) ? this.listedTests[testId] : null;
    }

    /**
     * @param {function} callback
     */
    forEach (callback) {
        Object.keys(this.listedTests).forEach((testId) => {
            callback(this.listedTests[testId]);
        });
    };

    /**
     * @param {NodeList} nodeList
     *
     * @returns {ListedTestCollection}
     */
    static createFromNodeList (nodeList) {
        let collection = new ListedTestCollection();

        [].forEach.call(nodeList, (listedTestElement) => {
            collection.add(ListedTestFactory.createFromElement(listedTestElement));
        });

        return collection;
    }
}

module.exports = ListedTestCollection;
