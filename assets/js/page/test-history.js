let UrlFilterForm = require('../test-history/url-filter-form');
let Suggestions = require('../test-history/suggestions');
let ListedTestCollection = require('../model/listed-test-collection');
let bsn = require('bootstrap.native');

module.exports = function (document) {
    const modalId = 'filter-options-modal';
    const filterOptionsSelector = '.action-badge-filter-options';
    const modalElement = document.getElementById(modalId);
    let filterOptionsElement = document.querySelector(filterOptionsSelector);

    let urlFilterForm = new UrlFilterForm(modalElement);
    let suggestions = new Suggestions(document, modalElement.getAttribute('data-source-url'));
    let modalControl = document.querySelector('.filter-modal-control');
    let modal = new bsn.Modal(modalElement);

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
    modalElement.addEventListener(urlFilterForm.filterChangedEventName, filterChangedEventListener);

    suggestions.retrieve();

    let listedTestCollection = ListedTestCollection.createFromNodeList(document.querySelectorAll('.listed-test'));
    listedTestCollection.forEach((listedTest) => {
        listedTest.enable();
    });

    modalControl.addEventListener('click', () => {
        modal.show();
    });
};
