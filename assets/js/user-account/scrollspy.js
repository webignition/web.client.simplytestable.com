require('./navbar-list');

class ScrollSpy {
    /**
     * @param {NavBarList} navBarList
     * @param {number} offset
     */
    constructor (navBarList, offset) {
        this.navBarList = navBarList;
        this.offset = offset;
    }

    scrollEventListener () {
        let activeLinkTarget = null;
        let linkTargets = this.navBarList.getTargets();
        let offset = this.offset;
        let linkTargetsPastThreshold = [];

        linkTargets.forEach(function (linkTarget) {
            if (linkTarget) {
                let offsetTop = linkTarget.getBoundingClientRect().top;

                if (offsetTop < offset) {
                    linkTargetsPastThreshold.push(linkTarget);
                }
            }
        });

        if (linkTargetsPastThreshold.length === 0) {
            activeLinkTarget = linkTargets[0];
        } else if (linkTargetsPastThreshold.length === linkTargets.length) {
            activeLinkTarget = linkTargets[linkTargets.length - 1];
        } else {
            activeLinkTarget = linkTargetsPastThreshold[linkTargetsPastThreshold.length - 1];
        }

        if (activeLinkTarget) {
            this.navBarList.clearActive();
            this.navBarList.setActive(activeLinkTarget.getAttribute('id'));
        }
    }

    spy () {
        window.addEventListener(
            'scroll',
            this.scrollEventListener.bind(this),
            true
        );
    }
}

module.exports = ScrollSpy;
