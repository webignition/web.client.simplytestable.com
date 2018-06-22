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

        let issuesElement = element.querySelector('.issues');

        if (this.element.hasAttribute('data-filter')) {
            this.issueList = new FilterableIssueList(issuesElement, element.getAttribute('data-filter-selector'));
        } else {
            if (this.issueType === 'fix') {
                this.issueList = new FixList(issuesElement);
            } else {
                this.issueList = new IssueList(issuesElement);
            }
        }
    }

    /**
     * @returns {string}
     */
    static getIssueCountChangedEventName () {
        return 'issues-section.issue-count.changed';
    };

    init () {
        if (this.isFilterable()) {
            this.issueList.addHashIdAttributeToIssues();
            this.issueList.filter(window.location.hash.replace('#', ''));

            let issueCount = parseInt(this.issueCountElement.innerText.trim(), 10);
            let filteredIssueCount = this.issueList.count();

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
        return this.issueList.getFirstMessage();
    };

    /**
     * @returns {Element}
     */
    getFirstIssue () {
        return this.issueList.getFirst();
    }
}

module.exports = IssueSection;
