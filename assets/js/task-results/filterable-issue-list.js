let IssueList = require('./issue-list');
let SparkMD5 = require('spark-md5');

class FilterableIssueList extends IssueList {
    /**
     * @param {Element} element
     * @param {string} filterSelector
     */
    constructor (element, filterSelector) {
        super(element);
        this.filterSelector = filterSelector;
        this.hashIdPrefix = 'hash-';
    }

    addHashIdAttributeToIssues () {
        [].forEach.call(this.issues, (issueElement) => {
            let errorMessage = issueElement.querySelector(this.filterSelector).textContent.trim();
            let errorMessageHash = SparkMD5.hash(errorMessage);
            let id = this._generateUniqueId(this.hashIdPrefix + errorMessageHash);

            issueElement.setAttribute('id', id);
        });
    };

    /**
     * @param {string} filter
     */
    filter (filter) {
        filter = this.hashIdPrefix + filter;

        if (!this.element.ownerDocument.getElementById(filter)) {
            return;
        }

        [].forEach.call(this.element.querySelectorAll('.issue:not([id^=' + filter + '])'), (issueElement) => {
            issueElement.parentElement.removeChild(issueElement);
        });
    };

    /**
     * @returns {string | null}
     */
    getFirstMessage () {
        let firstIssue = this.getFirst();
        return firstIssue ? firstIssue.querySelector(this.filterSelector).innerText : null;
    }

    /**
     * @param {string} seed
     * @returns {string}
     * @private
     */
    _generateUniqueId (seed) {
        let originalSeed = seed;
        let seedSuffix = 1;

        while (this.element.ownerDocument.getElementById(seed)) {
            seed = originalSeed + '-' + seedSuffix;
            seedSuffix++;
        }

        return seed;
    };
}

module.exports = FilterableIssueList;
