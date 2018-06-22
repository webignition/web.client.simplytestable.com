class IssueList {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.issues = this.element.querySelectorAll('.issue');
    }

    /**
     * @returns {number}
     */
    count () {
        return this.element.querySelectorAll('.issue').length;
    }

    /**
     * @returns {Element}
     */
    getFirst () {
        return this.element.querySelector('.issue');
    };
}

module.exports = IssueList;
