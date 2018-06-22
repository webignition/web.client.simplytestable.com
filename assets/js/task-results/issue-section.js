let IssueList = require('./issue-list');
let FixList = require('./fix-list');
let FilterableIssueList = require('./filterable-issue-list');

class IssueSection {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.issueType = element.getAttribute('data-issue-type');
        this.issueCountElement = element.querySelector('.issue-count');
        this.issueLists = [];

        [].forEach.call(element.querySelectorAll('.issues'), (issuesElement) => {
            let issueList = null;

            if (this.element.hasAttribute('data-filter')) {
                issueList = new FilterableIssueList(issuesElement, element.getAttribute('data-filter-selector'));
            } else {
                if (this.issueType === 'fix') {
                    issueList = new FixList(issuesElement);
                } else {
                    issueList = new IssueList(issuesElement);
                }
            }

            this.issueLists.push(issueList);
        });
    }

    /**
     * @returns {string}
     */
    static getIssueCountChangedEventName () {
        return 'issues-section.issue-count.changed';
    };

    init () {
        if (this.isFilterable()) {
            let filter = window.location.hash.replace('#', '').trim();

            if (filter) {
                this.issueLists.forEach((issueList) => {
                    issueList.addHashIdAttributeToIssues();
                });
            }
        }
    };

    filter () {
        let filter = window.location.hash.replace('#', '').trim();

        if (filter) {
            let filteredIssueCount = 0;

            this.issueLists.forEach((issueList) => {
                issueList.filter(window.location.hash.replace('#', ''));
                filteredIssueCount += issueList.count();
            });

            let issueCount = parseInt(this.issueCountElement.innerText.trim(), 10);
            if (issueCount !== filteredIssueCount) {
                this.element.classList.add('filtered');
                this.renderIssueCount(filteredIssueCount);
                this.element.dispatchEvent(new CustomEvent(IssueSection.getIssueCountChangedEventName(), {
                    detail: {
                        'issue-type': this.issueType,
                        count: filteredIssueCount
                    }
                }));
            } else {
                this.element.classList.remove('filtered');
            }
        }
    };

    /**
     * @param {number} count
     */
    renderIssueCount (count) {
        this.issueCountElement.innerText = count;
    };

    /**
     * @returns {boolean}
     */
    isFilterable () {
        return this.element.hasAttribute('data-filter');
    }

    /**
     * @returns {boolean}
     */
    isFiltered () {
        return this.element.classList.contains('filtered');
    };

    /**
     * @returns {string}
     */
    getFirstIssueMessage () {
        let firstIssueMessage = null;

        this.issueLists.forEach((issueList) => {
            let issueListFirstMessage = issueList.getFirstMessage();
            if (firstIssueMessage === null && issueListFirstMessage !== null) {
                firstIssueMessage = issueListFirstMessage;
            }
        });

        return firstIssueMessage;
    };

    /**
     * @returns {Element}
     */
    getFirstIssue () {
        let firstIssue = null;

        this.issueLists.forEach((issueList) => {
            let issueListFirstIssue = issueList.getFirst();
            if (firstIssue === null && issueListFirstIssue !== null) {
                firstIssue = issueListFirstIssue;
            }
        });

        return firstIssue;
    }
}

module.exports = IssueSection;
