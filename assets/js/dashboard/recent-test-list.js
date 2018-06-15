let ListedTestCollection = require('../listed-test-collection');
let HttpClient = require('../http-client');

class RecentTestList {
    constructor (element) {
        this.element = element;
        this.document = element.ownerDocument;
        this.sourceUrl = element.getAttribute('data-source-url');
        this.listedTestCollection = new ListedTestCollection();
        this.retrievedListedTestCollection = new ListedTestCollection();
    };

    init () {
        this.element.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));

        this._retrieveTests();
    };

    _httpRequestRetrievedEventListener (event) {
        this._parseRetrievedTests(event.detail.response);
        this._renderRetrievedTests();

        window.setTimeout(() => {
            this._retrieveTests();
        }, 3000);
    };

    _renderRetrievedTests () {
        this._removeSpinner();

        this.listedTestCollection.forEach((listedTest) => {
            if (!this.retrievedListedTestCollection.contains(listedTest)) {
                listedTest.element.parentNode.removeChild(listedTest.element);
                this.listedTestCollection.remove(listedTest);
            }
        });

        this.retrievedListedTestCollection.forEach((retrievedListedTest) => {
            if (this.listedTestCollection.contains(retrievedListedTest)) {
                let listedTest = this.listedTestCollection.get(retrievedListedTest.getTestId());

                if (retrievedListedTest.constructor.name !== listedTest.constructor.name) {
                    this.listedTestCollection.remove(listedTest);
                    this.element.replaceChild(retrievedListedTest.element, listedTest.element);

                    this.listedTestCollection.add(retrievedListedTest);
                    retrievedListedTest.enable();
                } else {
                    listedTest.renderFromListedTest(retrievedListedTest);
                }
            } else {
                this.element.insertAdjacentElement('afterbegin', retrievedListedTest.element);
                this.listedTestCollection.add(retrievedListedTest);
                retrievedListedTest.enable();
            }
        });
    };

    _parseRetrievedTests (response) {
        let retrievedTestsMarkup = response.trim();
        let retrievedTestContainer = document.createElement('div');
        retrievedTestContainer.innerHTML = retrievedTestsMarkup;

        this.retrievedListedTestCollection = ListedTestCollection.createFromNodeList(
            retrievedTestContainer.querySelectorAll('.listed-test'),
            false
        );
    };

    _retrieveTests () {
        HttpClient.getText(this.sourceUrl, this.element, 'retrieveTests');
    };

    _removeSpinner () {
        let spinner = this.element.querySelector('.js-prefill-spinner');

        if (spinner) {
            this.element.removeChild(spinner);
        }
    };
}

module.exports = RecentTestList;
