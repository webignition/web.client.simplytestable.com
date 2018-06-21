class SortableItemList {
    /**
     * @param {SortableItem[]} items
     */
    constructor (items) {
        this.items = items;
    };

    sort (key, type) {
        let index = [];

        let sortedItems = [];
        this.items.forEach((sortableItem, position) => {
            index.push({
                position: position,
                value: sortableItem.getSortValue(key)
            });
        });

        index.sort(this._compareFunction);
        if (type === 'number') {
            index.reverse();
        }

        index.forEach((indexItem) => {
            sortedItems.push(this.items[indexItem.position]);
        });

        // this.items.forEach((sortableItem) => {
        //
        //
        //     // let sortValue = sortableItem.getSortValue(key);
        //     // let indexPosition = sortValues.indexOf(sortValue);
        //     //
        //     // sortedItems[indexPosition] = sortableItem;
        // });

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
