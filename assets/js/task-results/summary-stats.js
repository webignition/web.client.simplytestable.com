let SummaryStatsLabel = require('../model/element/summary-stats-label');

class SummaryStats {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;

        /**
         * @type {SummaryStatsLabel[]}
         */
        this.labels = [];

        [].forEach.call(this.element.querySelectorAll('.label'), (labelElement) => {
            let label = new SummaryStatsLabel(labelElement);
            this.labels[label.getIssueType()] = label;
        });
    };

    init () {
        this.labels.forEach((label) => {
            label.init();
        });
    };

    /**
     * @param {string} type
     * @param {number} count
     */
    setIssueCount (type, count) {
        this.labels[type].setCount(count);
    };
}

module.exports = SummaryStats;
