let Sort = require('./sort');

class ByPageList {
    /**
     * @param {Element} element
     */
    constructor (element) {
        this.element = element;
        this.sort = new Sort(element.querySelector('.sort'), element.querySelectorAll('.js-sortable-item'));
    };

    init () {
        this.sort.init();
    };
}

module.exports = ByPageList;
