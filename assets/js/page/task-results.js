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
            anchorElement.addEventListener('click', (event) => {
                event.preventDefault();

                let targetId = anchorElement.getAttribute('href').replace('#', '');
                let target = this.document.getElementById(targetId);

                if (target) {
                    ScrollTo.scrollTo(target, -50);
                }
            });
        });
    };
}

module.exports = TaskResults;
