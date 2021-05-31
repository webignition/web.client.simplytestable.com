let ListedTest = require('./listed-test');
let ProgressBar = require('../progress-bar');

class ProgressingListedTest extends ListedTest {
    init (element) {
        super.init(element);

        let progressBarElement = this.element.querySelector('.progress-bar');
        this.progressBar = progressBarElement ? new ProgressBar(progressBarElement) : null;
    }

    getCompletionPercent () {
        let completionPercent = this.element.getAttribute('data-completion-percent');

        if (this.isFinished() && completionPercent === null) {
            return 100;
        }

        return parseFloat(this.element.getAttribute('data-completion-percent'));
    }

    setCompletionPercent (completionPercent) {
        this.element.setAttribute('data-completion-percent', completionPercent);
    };

    renderFromListedTest (listedTest) {
        super.renderFromListedTest(listedTest);

        if (this.getCompletionPercent() === listedTest.getCompletionPercent()) {
            return;
        }

        this.setCompletionPercent(listedTest.getCompletionPercent());

        if (this.progressBar) {
            this.progressBar.setCompletionPercent(this.getCompletionPercent());
        }
    };

    getType () {
        return 'ProgressingListedTest';
    };
}

module.exports = ProgressingListedTest;
