let ProgressingListedTest = require('./progressing-listed-test');
let TestResultRetriever = require('../../../services/test-result-retriever');

class PreparingListedTest extends ProgressingListedTest {
    enable () {
        this._initialiseResultRetriever();
    };

    _initialiseResultRetriever () {
        let preparingElement = this.element.querySelector('.preparing');
        let testResultsRetriever = new TestResultRetriever(preparingElement);

        preparingElement.addEventListener(testResultsRetriever.getRetrievedEventName(), (retrievedEvent) => {
            let parentNode = this.element.parentNode;
            let retrievedListedTest = retrievedEvent.detail;
            retrievedListedTest.classList.add('fade-out');

            this.element.addEventListener('transitionend', () => {
                parentNode.replaceChild(retrievedListedTest, this.element);
                this.element = retrievedEvent.detail;
                this.element.classList.add('fade-in');
                this.element.classList.remove('fade-out');
            });

            this.element.classList.add('fade-out');
        });

        testResultsRetriever.init();
    };

    getType () {
        return 'PreparingListedTest';
    };
}

module.exports = PreparingListedTest;
