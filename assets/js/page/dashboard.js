let unavailableTaskTypeModalLauncher = require('../unavailable-task-type-modal-launcher');
let TestStartForm = require('../dashboard/test-start-form');

class Dashboard {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.testStartForm = new TestStartForm(document.getElementById('test-start-form'));
    }

    init () {
        unavailableTaskTypeModalLauncher(document.querySelectorAll('.task-type.not-available'));
        this.testStartForm.init();
    };
}

module.exports = Dashboard;
