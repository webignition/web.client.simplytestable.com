let SparkMD5 = require('spark-md5');

class IssueList {
    /**
     * @param {Element} element
     * @param {string} filterSelector
     */
    constructor (element, filterSelector) {
        this.element = element;
        this.filterSelector = filterSelector;
        this.hashIdPrefix = 'hash-';
    }

    init () {
        this._addHashIdAttributeToIssues();
    };

    _addHashIdAttributeToIssues () {
        [].forEach.call(this.element.querySelectorAll('.issue'), (issueElement) => {
            let errorMessage = issueElement.querySelector(this.filterSelector).innerText.trim();
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
     * @returns {number}
     */
    count () {
        return this.element.querySelectorAll('.issue').length;
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

module.exports = IssueList;
