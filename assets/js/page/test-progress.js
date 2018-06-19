let Summary = require('../test-progress/summary');
let TestAlertContainer = require('../model/element/test-alert-container');

class TestProgress {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.summary = new Summary(document.querySelector('.js-summary'));
        this.alertContainer = new TestAlertContainer(document.querySelector('.alert-container'));
    }

    init () {
        this.summary.init();
        this.alertContainer.init();
        this.summary.element.addEventListener(Summary.getRenderAmmendmentEventName(), (event) => {
            this.alertContainer.renderUrlLimitNotification();
        });
    };
}

module.exports = TestProgress;
