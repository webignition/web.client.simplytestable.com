class HttpClient {
    static getRetrievedEventName () {
        return 'http-client.retrieved';
    };

    static request (url, method, responseType, element, requestId, data = null, requestHeaders = {}) {
        let request = new XMLHttpRequest();

        request.open(method, url);
        request.responseType = responseType;

        for (const [key, value] of Object.entries(requestHeaders)) {
            request.setRequestHeader(key, value);
        }

        request.addEventListener('load', (event) => {
            let retrievedEvent = new CustomEvent(HttpClient.getRetrievedEventName(), {
                detail: {
                    response: request.response,
                    requestId: requestId
                }
            });

            element.dispatchEvent(retrievedEvent);
        });

        if (data === null) {
            request.send();
        } else {
            request.send(data);
        }
    };

    static get (url, responseType, element, requestId, requestHeaders = {}) {
        HttpClient.request(url, 'GET', responseType, element, requestId, null, requestHeaders);
    };

    static getJson (url, element, requestId, requestHeaders = {}) {
        let realRequestHeaders = {
            'Accept': 'application/json'
        };

        for (const [key, value] of Object.entries(requestHeaders)) {
            realRequestHeaders[key] = value;
        }

        HttpClient.request(url, 'GET', 'json', element, requestId, null, realRequestHeaders);
    };

    static getText (url, element, requestId, requestHeaders = {}) {
        HttpClient.request(url, 'GET', '', element, requestId, requestHeaders);
    };

    static post (url, element, requestId, data = null, requestHeaders = {}) {
        let realRequestHeaders = {
            'Content-type': 'application/x-www-form-urlencoded'
        };

        for (const [key, value] of Object.entries(requestHeaders)) {
            realRequestHeaders[key] = value;
        }

        HttpClient.request(url, 'POST', '', element, requestId, data, realRequestHeaders);
    };
}

module.exports = HttpClient;
