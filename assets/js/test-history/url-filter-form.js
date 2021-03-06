/* global Awesomplete */

let formFieldFocuser = require('../form-field-focuser');
require('awesomplete');

class UrlFilterForm {
    /**
     * @param {HTMLElement} element
     */
    constructor (element) {
        this.element = element;
        this.applyButton = element.querySelector('#apply-filter-button');
        this.clearButton = element.querySelector('#clear-filter-button');
        this.closeButton = element.querySelector('.close');
        this.input = element.querySelector('input[name=filter]');
        this.filter = this.input.value.trim();
        this.previousFilter = this.input.value.trim();
        this.applyFilter = false;
        this.awesomeplete = new Awesomplete(this.input);
        this.suggestions = [];
        this.filterChangedEventName = 'url-filter-form.filter-changed';
        this.finishedEventName = 'url-filter-form.finished';
        this.suggestionSelectedEventName = 'url-filter-form.suggestion-selected';
        this.ecapeKeyFinishes = true;

        this.init();
    }

    /**
     * @param {String[]} suggestions
     */
    setSuggestions (suggestions) {
        this.suggestions = suggestions;
        this.awesomeplete.list = this.suggestions;
    }

    init () {
        let shownEventListener = function () {
            formFieldFocuser(this.input);
        };

        let hideEventListener = function () {
            const WILDCARD = '*';
            const filterIsEmpty = this.filter === '';
            const suggestionsIncludesFilter = this.suggestions.includes(this.filter);
            const filterIsWildcardPrefixed = this.filter.charAt(0) === WILDCARD;
            const filterIsWildcardSuffixed = this.filter.slice(-1) === WILDCARD;

            if (!filterIsEmpty && !suggestionsIncludesFilter) {
                if (!filterIsWildcardPrefixed) {
                    this.filter = WILDCARD + this.filter;
                }

                if (!filterIsWildcardSuffixed) {
                    this.filter += WILDCARD;
                }

                this.input.value = this.filter;
            }

            this.applyFilter = this.filter !== this.previousFilter;
        };

        let hiddenEventListener = function () {
            if (!this.applyFilter) {
                return;
            }

            this.element.dispatchEvent(new CustomEvent(this.filterChangedEventName, {
                detail: this.filter
            }));
        };

        let applyButtonClickEventListener = function () {
            this.filter = this.input.value.trim();
            this.element.dispatchEvent(new Event(this.finishedEventName));
        };

        let clearButtonClickEventListener = function () {
            this.input.value = '';
            this.filter = '';
            this.element.dispatchEvent(new Event(this.finishedEventName));
        };

        let closeButtonClickEventListener = function () {
            this.applyFilter = false;
        };

        this.element.addEventListener('shown.bs.modal', shownEventListener.bind(this));
        this.element.addEventListener('hide.bs.modal', hideEventListener.bind(this));
        this.element.addEventListener('hidden.bs.modal', hiddenEventListener.bind(this));
        this.applyButton.addEventListener('click', applyButtonClickEventListener.bind(this));
        this.clearButton.addEventListener('click', clearButtonClickEventListener.bind(this));
        this.closeButton.addEventListener('click', closeButtonClickEventListener.bind(this));

        this.element.addEventListener('awesomplete-selectcomplete', () => {
            this.element.dispatchEvent(new Event(this.suggestionSelectedEventName));
        });

        this.element.addEventListener('awesomplete-open', () => {
            this.ecapeKeyFinishes = false;
        });

        this.element.addEventListener('awesomplete-close', () => {
            window.setTimeout(() => {
                this.ecapeKeyFinishes = true;
            }, 100);
        });

        this.input.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                this.applyButton.click();
            }

            if (event.key === 'Escape' && this.ecapeKeyFinishes) {
                this.element.dispatchEvent(new Event(this.finishedEventName));
            }
        });
    };
}

module.exports = UrlFilterForm;
