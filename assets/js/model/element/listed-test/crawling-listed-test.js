let ProgressingListedTest = require('./progressing-listed-test');

class CrawlingListedTest extends ProgressingListedTest {
    renderFromListedTest (listedTest) {
        super.renderFromListedTest(listedTest);

        this.element.querySelector('.processed-url-count').innerText = listedTest.element.getAttribute('data-processed-url-count');
        this.element.querySelector('.discovered-url-count').innerText = listedTest.element.getAttribute('data-discovered-url-count');
    };

    getType () {
        return 'CrawlingListedTest';
    };
}

module.exports = CrawlingListedTest;
