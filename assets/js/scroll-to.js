const SmoothScroll = require('smooth-scroll');

class ScrollTo {
    static scrollTo (target, offset) {
        const scroll = new SmoothScroll();

        scroll.animateScroll(target.offsetTop + offset);
        ScrollTo._updateHistory(target);
    }

    static goTo (target, offset) {
        const scroll = new SmoothScroll();

        scroll.animateScroll(offset);
        ScrollTo._updateHistory(target);
    }

    static _updateHistory (target) {
        if (window.history.pushState) {
            window.history.pushState(null, null, '#' + target.getAttribute('id'));
        }
    };
}

module.exports = ScrollTo;
