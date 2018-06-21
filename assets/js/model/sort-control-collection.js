let SortControl = require('../model/element/sort-control');

class SortControlCollection {
    /**
     * @param {SortControl[]} controls
     */
    constructor (controls) {
        this.controls = controls;
    };

    setSorted (element) {
        this.controls.forEach((control) => {
            if (control.element === element) {
                control.setSorted();
            } else {
                control.setNotSorted();
            }
        });
    };
}

module.exports = SortControlCollection;
