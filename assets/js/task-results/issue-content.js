let IssueSection = require('../task-results/issue-section');

class IssueContent {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;

        /**
         * @type {IssueSection[]}
         */
        this.issueSections = [];

        [].forEach.call(this.element.querySelectorAll('.issue-list'), (issueSectionElement) => {
            this.issueSections.push(new IssueSection(issueSectionElement));
        });
    }

    init () {
        this.issueSections.forEach((issueSection) => {
            issueSection.init();
        });

        this.issueSections.forEach((issueSection) => {
            if (issueSection.isFilterable()) {
                issueSection.filter();
            }
        });
    };

    /**
     * @param {string} type
     * @returns {IssueSection}
     */
    getIssueSection (type) {
        let issueSection = null;

        this.issueSections.forEach((currentIssueSection) => {
            if (currentIssueSection.issueType === type) {
                issueSection = currentIssueSection;
            }
        });

        return issueSection;
    };

    /**
     * @returns {string}
     */
    getFilteredIssueMessage () {
        let filteredIssueMessage = '';

        this.issueSections.forEach((filteredIssueSection) => {
            if (filteredIssueSection.isFiltered()) {
                filteredIssueMessage = filteredIssueSection.getFirstIssueMessage();
            }
        });

        return filteredIssueMessage;
    }

    /**
     * @returns {Element}
     */
    getFirstFilteredIssue () {
        let firstFilteredIssue = null;

        this.issueSections.forEach((filteredIssueSection) => {
            if (filteredIssueSection.isFiltered()) {
                firstFilteredIssue = filteredIssueSection.getFirstIssue();
            }
        });

        return firstFilteredIssue;
    };
}

module.exports = IssueContent;
