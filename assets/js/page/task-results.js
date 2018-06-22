let FilteredIssueSection = require('../task-results/filtered-issue-section');
let SummaryStats = require('../task-results/summary-stats');

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

        /**
         * @type {FilteredIssueSection[]}
         */
        this.filteredIssueSections = [];

        [].forEach.call(this.document.querySelectorAll('.summary-stats'), (summaryStatsElement) => {
            this.summaryStats.push(new SummaryStats(summaryStatsElement));
        });

        [].forEach.call(this.document.querySelectorAll('[data-filter]'), (filteredIssueSectionElement) => {
            this.filteredIssueSections.push(new FilteredIssueSection(filteredIssueSectionElement));
        });
    }

    init () {
        this.summaryStats.forEach((summaryStats) => {
            summaryStats.init();
        });

        this.filteredIssueSections.forEach((filteredIssueSection) => {
            filteredIssueSection.element.addEventListener(
                FilteredIssueSection.getIssueCountChangedEventName(),
                this._filteredIssueSectionIssueCountChangedEventListener.bind(this)
            );

            filteredIssueSection.init();
        });
    };

    _filteredIssueSectionIssueCountChangedEventListener (event) {
        this.summaryStats.forEach((summaryStats) => {
            summaryStats.setIssueCount(event.detail['issue-type'], event.detail.count);
        });
    };
}

module.exports = TaskResults;
