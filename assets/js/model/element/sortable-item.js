class SortableItem {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.sortValues = JSON.parse(element.getAttribute('data-sort-values'));
    };

    /**
     * @param {string} key
     *
     * @returns {*}
     */
    getSortValue (key) {
        return this.sortValues[key];
    }
}

module.exports = SortableItem;
