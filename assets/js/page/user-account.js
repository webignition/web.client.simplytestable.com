let ScrollSpy = require('../user-account/scrollspy');
let NavBarList = require('../user-account/navbar-list');
const ScrollTo = require('../scroll-to');

class UserAccount {
    /**
     * @param {Window} window
     * @param {Document} document
     */
    constructor (window, document) {
        this.window = window;
        this.document = document;
        this.scrollOffset = 60;
        const scrollSpyOffset = 100;
        this.sideNavElement = document.getElementById('sidenav');

        this.navBarList = new NavBarList(this.sideNavElement, this.scrollOffset);
        this.scrollspy = new ScrollSpy(this.navBarList, scrollSpyOffset);
    };

    _applyInitialScroll () {
        let targetId = this.window.location.hash.trim().replace('#', '');

        if (targetId) {
            let target = this.document.getElementById(targetId);
            let relatedAnchor = this.sideNavElement.querySelector('a[href=\\#' + targetId + ']');

            if (target) {
                if (relatedAnchor.classList.contains('js-first')) {
                    ScrollTo.goTo(target, 0);
                } else {
                    ScrollTo.scrollTo(target, this.scrollOffset);
                }
            }
        }
    };

    init () {
        this.sideNavElement.querySelector('a').classList.add('js-first');
        this.scrollspy.spy();
        this._applyInitialScroll();
    };
}

module.exports = UserAccount;
