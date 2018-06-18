class ActionBadge {
    constructor (element) {
        this.element = element;
    }

    markEnabled () {
        this.element.classList.add('action-badge-enabled');
    }

    markNotEnabled () {
        this.element.classList.remove('action-badge-enabled');
    }
}

module.exports = ActionBadge;
