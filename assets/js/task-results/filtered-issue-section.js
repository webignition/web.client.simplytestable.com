let IssueList = require('../model/element/issue-list');

class FilteredIssueSection {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.issueList = new IssueList(element.querySelector('.issues'), element.getAttribute('data-filter-selector'));
        this.issueCountElement = element.querySelector('.issue-count');
        this.issueType = element.getAttribute('data-issue-type');
    }

    /**
     * @returns {string}
     */
    static getIssueCountChangedEventName () {
        return 'filtered-issues-section.issue-count.changed';
    };

    init () {
        this.issueList.init();
        this.issueList.filter(window.location.hash.replace('#', ''));

        let issueCount = parseInt(this.issueCountElement.innerText.trim(), 10);
        let filteredIssueCount = this.issueList.count();

        if (issueCount !== filteredIssueCount) {
            this.element.classList.add('filtered');
            this.issueCountElement.innerText = filteredIssueCount;
            this.element.dispatchEvent(new CustomEvent(FilteredIssueSection.getIssueCountChangedEventName(), {
                detail: {
                    'issue-type': this.issueType,
                    count: filteredIssueCount
                }
            }));
        } else {
            this.element.classList.remove('filtered');
        }
    };

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
}

module.exports = FilteredIssueSection;
