let ScrollTo = require('../../scroll-to');

class SummaryStatsLabel {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
    };

    init () {
        let href = this.element.getAttribute('href');
        if (href) {
            this.element.addEventListener('click', this._clickEventListener.bind(this));
        }
    };

    /**
     * @param {number} count
     */
    setCount (count) {
        this.element.querySelector('.count').innerText = count;

        if (count === 1) {
            this.element.classList.add('singular');
        } else {
            this.element.classList.remove('singular');
        }
    }

    /**
     * @returns {string}
     */
    getIssueType () {
        let issueType = this.element.getAttribute('data-issue-type');

        return issueType === '' ? null : issueType;
    }

    /**
     * @param {Event} event
     * @private
     */
    _clickEventListener (event) {
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

module.exports = SummaryStatsLabel;
