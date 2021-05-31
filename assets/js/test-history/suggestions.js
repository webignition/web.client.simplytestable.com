class Suggestions {
    /**
     * @param {Document} document
     * @param {String} sourceUrl
     */
    constructor (document, sourceUrl) {
        this.document = document;
        this.sourceUrl = sourceUrl;
        this.loadedEventName = 'test-history.suggestions.loaded';
    }

    retrieve () {
        let request = new XMLHttpRequest();
        let suggestions = null;

        request.open('GET', this.sourceUrl, false);

        let requestOnloadHandler = function () {
            if (request.status >= 200 && request.status < 400) {
                suggestions = JSON.parse(request.responseText);

                this.document.dispatchEvent(new CustomEvent(this.loadedEventName, {
                    detail: suggestions
                }));
            }
        };

        request.onload = requestOnloadHandler.bind(this);

        request.send();
    };
}

module.exports = Suggestions;
