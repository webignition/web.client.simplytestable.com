let FilteredIssueSection = require('../task-results/filtered-issue-section');
let SummaryStats = require('../task-results/summary-stats');
let IssueContent = require('../task-results/issue-content');

class TaskResults {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;

        /**
         * @type {SummaryStats[]}
         */
        this.summaryStats = [];

        this.issueContent = new IssueContent(document.querySelector('.issue-content'));

        [].forEach.call(this.document.querySelectorAll('.summary-stats'), (summaryStatsElement) => {
            this.summaryStats.push(new SummaryStats(summaryStatsElement));
        });
    }

    init () {
        this.summaryStats.forEach((summaryStats) => {
            summaryStats.init();
        });

        this.issueContent.filteredIssueSections.forEach((filteredIssueSection) => {
            filteredIssueSection.element.addEventListener(
                FilteredIssueSection.getIssueCountChangedEventName(),
                this._filteredIssueSectionIssueCountChangedEventListener.bind(this)
            );
        });

        this.issueContent.init();
    };

    /**
     * @param {CustomEvent} event
     * @private
     */
    _filteredIssueSectionIssueCountChangedEventListener (event) {
        this.summaryStats.forEach((summaryStats) => {
            summaryStats.setIssueCount(event.detail['issue-type'], event.detail.count);
        });

        document.querySelector('.issue-content').insertAdjacentElement('afterbegin', this._createFilterNotice());
    };

    /**
     * @returns {Element}
     * @private
     */
    _createFilterNotice () {
        let container = this.document.createElement('div');
        container.innerHTML = '<p class="filter-notice lead">Showing only <span class="message">&ldquo;' + this.issueContent.getFilteredIssueMessage() + '&rdquo;</span> errors. <a href="">Show all.</a></p>';

        return container.querySelector('.filter-notice');
    };
}

module.exports = TaskResults;
