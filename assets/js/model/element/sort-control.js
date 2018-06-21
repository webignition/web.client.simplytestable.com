class SortControl {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.keys = JSON.parse(element.getAttribute('data-sort-keys'));
    };

    init () {
        this.element.addEventListener('click', this._clickEventListener.bind(this));
    };

    /**
     * @returns {string}
     */
    static getSortRequestedEventName () {
        return 'sort-control.sort.requested';
    };

    _clickEventListener () {
        if (this.element.classList.contains('sorted')) {
            return;
        }

        this.element.dispatchEvent(new CustomEvent(SortControl.getSortRequestedEventName(), {
            detail: {
                keys: this.keys
            }
        }));
    };

    setSorted () {
        this.element.classList.add('sorted');
        this.element.classList.remove('link');
    };

    setNotSorted () {
        this.element.classList.remove('sorted');
        this.element.classList.add('link');
    };
}

module.exports = SortControl;
