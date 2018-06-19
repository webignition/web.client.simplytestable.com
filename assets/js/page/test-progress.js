let Summary = require('../test-progress/summary');
let TestAlertContainer = require('../model/element/test-alert-container');
let HttpClient = require('../services/http-client');

class TestProgress {
    /**
     * @param {Document} document
     */
    constructor (document) {
        this.document = document;
        this.summary = new Summary(document.querySelector('.js-summary'));
        this.alertContainer = new TestAlertContainer(document.querySelector('.alert-container'));
    }

    init () {
        this.summary.init();
        this.alertContainer.init();
        this.summary.element.addEventListener(Summary.getRenderAmmendmentEventName(), (event) => {
            this.alertContainer.renderUrlLimitNotification();
        });

        this.document.body.addEventListener(HttpClient.getRetrievedEventName(), this._httpRequestRetrievedEventListener.bind(this));

        this._refreshSummary();
    };

    _httpRequestRetrievedEventListener (event) {
        if (event.detail.requestId === 'test-progress.summary.update') {
            if (event.detail.request.responseURL.indexOf(window.location.toString()) !== 0) {
                this.summary.progressBar.setCompletionPercent(100);
                window.location.href = event.detail.request.responseURL;

                return;
            }

            this.summary.render(event.detail.response);
        }

        window.setTimeout(() => {
            this._refreshSummary();
        }, 3000);
    };

    _refreshSummary () {
        let summaryUrl = this.document.body.getAttribute('data-summary-url');
        let now = new Date();

        HttpClient.getJson(summaryUrl + '?timestamp=' + now.getTime(), this.document.body, 'test-progress.summary.update');
    };
}

module.exports = TestProgress;
