class PositionStickyDetector {
    /**
     * @returns {boolean}
     */
    static supportsPositionSticky () {
        let element = document.createElement('a');
        let elementStyle = element.style;

        elementStyle.cssText = 'position:sticky;';

        return elementStyle.position.indexOf('sticky') !== - 1;
    };
}

module.exports = PositionStickyDetector;
