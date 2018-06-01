const NavBarAnchor = require('./navbar-anchor');

class NavBarItem {
    /**
     * @param {Element} element
     * @param {number} scrollOffset
     * @param {function} NavBarList
     */
    constructor (element, scrollOffset, NavBarList) {
        this.element = element;
        this.anchor = null;
        this.navBarList = null;

        for (let i = 0; i < element.children.length; i++) {
            let child = element.children.item(i);

            if (child.nodeName === 'A' && child.getAttribute('href')[0] === '#') {
                this.anchor = new NavBarAnchor(child, scrollOffset);
            }

            if (child.nodeName === 'UL') {
                this.navBarList = new NavBarList(child, scrollOffset);
            }
        }
    };

    getTargets () {
        let targets = [];

        if (this.anchor) {
            targets.push(this.anchor.getTarget());
        }

        if (this.navBarList) {
            this.navBarList.getTargets().forEach(function (target) {
                targets.push(target);
            });
        }

        return targets;
    }

    containsTargetId (targetId) {
        if (this.anchor && this.anchor.targetId === targetId) {
            return true;
        }

        if (this.navBarList) {
            return this.navBarList.containsTargetId(targetId);
        }

        return false;
    };

    setActive (targetId) {
        this.element.classList.add('active');

        if (this.navBarList && this.navBarList.containsTargetId(targetId)) {
            this.navBarList.setActive(targetId);
        }
    };
}

module.exports = NavBarItem;
