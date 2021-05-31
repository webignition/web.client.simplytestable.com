const ScrollTo = require('../scroll-to');

class NavBarAnchor {
    /**
     * @param {Element} element
     * @param {number} scrollOffset
     */
    constructor (element, scrollOffset) {
        this.element = element;
        this.scrollOffset = scrollOffset;
        this.targetId = element.getAttribute('href').replace('#', '');

        this.element.addEventListener('click', this.handleClick.bind(this));
    };

    handleClick (event) {
        event.preventDefault();
        event.stopPropagation();

        let target = this.getTarget();

        if (this.element.classList.contains('js-first')) {
            ScrollTo.goTo(target, 0);
        } else {
            ScrollTo.scrollTo(target, this.scrollOffset);
        }
    }

    getTarget () {
        return document.getElementById(this.targetId);
    }
}

module.exports = NavBarAnchor;
