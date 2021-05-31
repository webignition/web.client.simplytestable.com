let NavBarItem = require('./navbar-item');

class NavBarList {
    /**
     * @param {Element} element
     * @param {number} scrollOffset
     */
    constructor (element, scrollOffset) {
        this.element = element;
        this.navBarItems = [];

        for (let i = 0; i < element.children.length; i++) {
            this.navBarItems.push(new NavBarItem(element.children.item(i), scrollOffset, NavBarList));
        }
    };

    getTargets () {
        let targets = [];

        for (let i = 0; i < this.navBarItems.length; i++) {
            this.navBarItems[i].getTargets().forEach(function (target) {
                targets.push(target);
            });
        }

        return targets;
    };

    containsTargetId (targetId) {
        let contains = false;

        this.navBarItems.forEach(function (navBarItem) {
            if (navBarItem.containsTargetId(targetId)) {
                contains = true;
            }
        });

        return contains;
    };

    clearActive () {
        [].forEach.call(this.element.querySelectorAll('li'), function (listItemElement) {
            listItemElement.classList.remove('active');
        });
    };

    setActive (targetId) {
        this.navBarItems.forEach(function (navBarItem) {
            if (navBarItem.containsTargetId(targetId)) {
                navBarItem.setActive(targetId);
            }
        });
    };
}

module.exports = NavBarList;
