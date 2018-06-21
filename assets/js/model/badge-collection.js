class BadgeCollection {
    /**
     * @param {NodeList} badges
     */
    constructor (badges) {
        this.badges = badges;
    };

    applyUniformWidth () {
        let greatestWidth = this._deriveGreatestWidth();

        [].forEach.call(this.badges, (badge) => {
            badge.style.width = greatestWidth + 'px';
        });
    };

    /**
     * @returns {number}
     * @private
     */
    _deriveGreatestWidth () {
        let greatestWidth = 0;

        [].forEach.call(this.badges, (badge) => {
            if (badge.offsetWidth > greatestWidth) {
                greatestWidth = badge.offsetWidth;
            }
        });

        return greatestWidth;
    }
}

module.exports = BadgeCollection;
