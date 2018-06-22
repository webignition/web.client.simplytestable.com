let FilteredIssueSection = require('../task-results/filtered-issue-section');

class IssueContent {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;

        /**
         * @type {FilteredIssueSection[]}
         */
        this.filteredIssueSections = [];

        [].forEach.call(this.element.querySelectorAll('[data-filter]'), (filteredIssueSectionElement) => {
            this.filteredIssueSections.push(new FilteredIssueSection(filteredIssueSectionElement));
        });
    }

    init () {
        this.filteredIssueSections.forEach((filteredIssueSection) => {
            filteredIssueSection.init();
        });
    };

    /**
     * @returns {string}
     */
    getFilteredIssueMessage () {
        let filteredIssueMessage = '';

        this.filteredIssueSections.forEach((filteredIssueSection) => {
            if (filteredIssueSection.isFiltered()) {
                filteredIssueMessage = filteredIssueSection.getFirstIssueMessage();
            }
        });

        return filteredIssueMessage;
    }
}

module.exports = IssueContent;
