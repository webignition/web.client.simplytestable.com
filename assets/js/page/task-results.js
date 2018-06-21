let ScrollTo = require('../scroll-to');

class TaskResults {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
    }

    init () {
        [].forEach.call(this.document.querySelectorAll('.summary-stats a'), (anchorElement) => {
            anchorElement.addEventListener('click', this._summaryStatsAnchorClickEventListener.bind(this));
        });
    };

    _summaryStatsAnchorClickEventListener (event) {
        event.preventDefault();

        let anchorElement = null;

        event.path.forEach(function (pathElement) {
            if (!anchorElement && pathElement.nodeName === 'A') {
                anchorElement = pathElement;
            }
        });

        let targetId = anchorElement.getAttribute('href').replace('#', '');
        let target = this.document.getElementById(targetId);

        if (target) {
            ScrollTo.scrollTo(target, -50);
        }
    };
}

module.exports = TaskResults;
