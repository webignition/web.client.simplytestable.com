let unavailableTaskTypeModalLauncher = require('../unavailable-task-type-modal-launcher');
let TestStartForm = require('../dashboard/test-start-form');
let ListedTest = require('../listed-test');
let RecentTestList = require('../dashboard/recent-test-list');

class Dashboard {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.window = window;
        this.document = document;
        this.testStartForm = new TestStartForm(document.getElementById('test-start-form'));
        this.recentTestList = new RecentTestList(document.querySelector('.test-list'));
    }

    init () {
        this.document.querySelector('.recent-activity-container').classList.remove('hidden');

        unavailableTaskTypeModalLauncher(document.querySelectorAll('.task-type.not-available'));
        this.testStartForm.init();
        this.recentTestList.init();


        // [].forEach.call(this.document.querySelectorAll('.requires-results'), (listedTestElement) => {
        //     let listedTest = new ListedTest(listedTestElement);
        //     listedTest.init();
        // });
    };
}

module.exports = Dashboard;
