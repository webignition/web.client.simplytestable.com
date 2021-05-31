class SortableItemList {
    /**
     * @param {SortableItem[]} items
     */
    constructor (items) {
        this.items = items;
    };

    /**
     * @param {string[]} keys
     * @returns {SortableItem[]}
     */
    sort (keys) {
        let index = [];
        let sortedItems = [];

        this.items.forEach((sortableItem, position) => {
            let values = [];

            keys.forEach((key) => {
                let value = sortableItem.getSortValue(key);
                if (Number.isInteger(value)) {
                    value = (1 / value).toString();
                }

                values.push(value);
            });

            index.push({
                position: position,
                value: values.join(',')
            });
        });

        index.sort(this._compareFunction);

        index.forEach((indexItem) => {
            sortedItems.push(this.items[indexItem.position]);
        });

        return sortedItems;
    };

    /**
     * @param {Object} a
     * @param {Object} b
     * @returns {number}
     * @private
     */
    _compareFunction (a, b) {
        if (a.value < b.value) {
            return -1;
        }

        if (a.value > b.value) {
            return 1;
        }

        return 0;
    };
}

module.exports = SortableItemList;
