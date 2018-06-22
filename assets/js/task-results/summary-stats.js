let ScrollTo = require('../scroll-to');

class SummaryStats {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
    };

    init () {
        [].forEach.call(this.element.querySelectorAll('.summary-stats a'), (anchorElement) => {
            anchorElement.addEventListener('click', this._anchorClickEventListener.bind(this));
        });
    };

    /**
     * @param {string} type
     * @param {number} count
     */
    setIssueCount (type, count) {
        this.element.querySelector('.' + type + '-count').innerText = count;
    };

    /**
     * @param {Event} event
     * @private
     */
    _anchorClickEventListener (event) {
        event.preventDefault();

        let anchorElement = null;

        event.path.forEach(function (pathElement) {
            if (!anchorElement && pathElement.nodeName === 'A') {
                anchorElement = pathElement;
            }
        });

        let targetId = anchorElement.getAttribute('href').replace('#', '');
        let target = this.element.ownerDocument.getElementById(targetId);

        if (target) {
            ScrollTo.scrollTo(target, -50);
        }
    };
}

module.exports = SummaryStats;
