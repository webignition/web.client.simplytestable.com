let SortControl = require('../model/element/sort-control');
let SortableItem = require('../model/element/sortable-item');
let SortableItemList = require('../model/sortable-item-list');
let SortControlCollection = require('../model/sort-control-collection');

class Sort {
    /**
     * @param {Element} element
     * @param {NodeList} sortableItemsNodeList
     */
    constructor (element, sortableItemsNodeList) {
        this.element = element;
        this.sortControlCollection = this._createSortableControlCollection();
        this.sortableItemsNodeList = sortableItemsNodeList;
        this.sortableItemsList = this._createSortableItemList();
    };

    init () {
        this.element.classList.remove('invisible');
        this.sortControlCollection.controls.forEach((control) => {
            control.init();
            control.element.addEventListener(SortControl.getSortRequestedEventName(), this._sortControlClickEventListener.bind(this));
        });
    };

    /**
     * @returns {SortableItemList}
     * @private
     */
    _createSortableItemList () {
        let sortableItems = [];

        [].forEach.call(this.sortableItemsNodeList, (sortableItemElement) => {
            sortableItems.push(new SortableItem(sortableItemElement));
        });

        return new SortableItemList(sortableItems);
    }

    /**
     * @returns {SortControlCollection}
     * @private
     */
    _createSortableControlCollection () {
        let controls = [];

        [].forEach.call(this.element.querySelectorAll('.sort-control'), (sortControlElement) => {
            controls.push(new SortControl(sortControlElement));
        });

        return new SortControlCollection(controls);
    };

    /**
     * @param {CustomEvent} event
     * @private
     */
    _sortControlClickEventListener (event) {
        let parent = this.sortableItemsNodeList.item(0).parentElement;

        [].forEach.call(this.sortableItemsNodeList, (sortableItemElement) => {
            sortableItemElement.parentElement.removeChild(sortableItemElement);
        });

        let sortedItems = this.sortableItemsList.sort(event.detail.keys);

        sortedItems.forEach((sortableItem) => {
            parent.insertAdjacentElement('beforeend', sortableItem.element);
        });

        this.sortControlCollection.setSorted(event.target);
    };
}

module.exports = Sort;
