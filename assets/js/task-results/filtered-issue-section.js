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
            this.issueCountElement.innerText = filteredIssueCount;
            this.element.dispatchEvent(new CustomEvent(FilteredIssueSection.getIssueCountChangedEventName(), {
                detail: {
                    'issue-type': this.issueType,
                    count: filteredIssueCount
                }
            }));
        }
    };
}

module.exports = FilteredIssueSection;
