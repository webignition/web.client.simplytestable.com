let UrlFilterForm = require('../test-history/url-filter-form');
let Suggestions = require('../test-history/suggestions');
let ListedTestCollection = require('../model/listed-test-collection');
let Modal = require('../test-history/modal');

module.exports = function (document) {
    const modalId = 'filter-options-modal';
    const filterOptionsSelector = '.action-badge-filter-options';
    const urlFilterFormElement = document.getElementById(modalId);
    let filterOptionsElement = document.querySelector(filterOptionsSelector);

    let urlFilterForm = new UrlFilterForm(urlFilterFormElement);
    let suggestions = new Suggestions(document, urlFilterFormElement.getAttribute('data-source-url'));
    let modalControl = document.querySelector('.filter-modal-control');
    let modal = new Modal(urlFilterFormElement);

    /**
     * @param {CustomEvent} event
     */
    let suggestionsLoadedEventListener = function (event) {
        urlFilterForm.setSuggestions(event.detail);
        filterOptionsElement.classList.add('visible');
    };

    /**
     * @param {CustomEvent} event
     */
    let filterChangedEventListener = function (event) {
        let filter = event.detail;
        let search = (filter === '') ? '' : '?filter=' + filter;
        let currentSearch = window.location.search;

        if (currentSearch === '' && filter === '') {
            return;
        }

        if (search !== currentSearch) {
            window.location.search = search;
        }
    };

    document.addEventListener(suggestions.loadedEventName, suggestionsLoadedEventListener);
    urlFilterFormElement.addEventListener(urlFilterForm.filterChangedEventName, filterChangedEventListener);

    suggestions.retrieve();

    let listedTestCollection = ListedTestCollection.createFromNodeList(document.querySelectorAll('.listed-test'));
    listedTestCollection.forEach((listedTest) => {
        listedTest.enable();
    });

    modalControl.addEventListener('click', () => {
        modal.show();
    });

    urlFilterFormElement.addEventListener(urlFilterForm.suggestionSelectedEventName, () => {
        modal.allowClose = false;

        window.setTimeout(() => {
            modal.allowClose = true;
        }, 100);
    });

    urlFilterFormElement.addEventListener(urlFilterForm.finishedEventName, () => {
        modal.close();
    });
};
