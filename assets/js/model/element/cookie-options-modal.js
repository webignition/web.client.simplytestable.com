let formFieldFocuser = require('../../form-field-focuser');

class CookieOptionsModal {
    constructor (element) {
        this.isAccountRequiredModal = element.classList.contains('account-required-modal');
        this.element = element;
        this.closeButton = element.querySelector('[data-name=close]');
        this.addButton = element.querySelector('.js-add-button');
        this.applyButton = element.querySelector('[data-name=apply');
    }

    init () {
        this._addRemoveActions();
        this._addEventListeners();
    };

    /**
     * @returns {string}
     */
    static getOpenedEventName () {
        return 'cookie-options-modal.opened';
    }

    /**
     * @returns {string}
     */
    static getClosedEventName () {
        return 'cookie-options-modal.closed';
    }

    _removeActionClickEventListener (event) {
        let cookieDataRowCount = this.tableBody.querySelectorAll('.js-cookie').length;
        let removeAction = event.target;
        let tableRow = this.element.ownerDocument.getElementById(removeAction.getAttribute('data-for'));

        if (cookieDataRowCount === 1) {
            let nameInput = tableRow.querySelector('.name input');

            nameInput.value = '';
            tableRow.querySelector('.value input').value = '';

            formFieldFocuser(nameInput);
        } else {
            tableRow.parentNode.removeChild(tableRow);
        }
    };

    /**
     * @param {KeyboardEvent} event
     * @private
     */
    _inputKeyDownEventListener (event) {
        if (event.type === 'keydown' && event.key === 'Enter') {
            this.applyButton.click();
        }
    };

    _addEventListeners () {
        let shownEventListener = () => {
            this.tableBody = this.element.querySelector('tbody');
            this.previousTableBody = this.tableBody.cloneNode(true);
            formFieldFocuser(this.tableBody.querySelector('.js-cookie:last-of-type .name input'));
            this.element.dispatchEvent(new CustomEvent(CookieOptionsModal.getOpenedEventName()));
        };

        let hiddenEventListener = () => {
            this.element.dispatchEvent(new CustomEvent(CookieOptionsModal.getClosedEventName()));
        };

        let closeButtonClickEventListener = () => {
            this.tableBody.parentNode.replaceChild(this.previousTableBody, this.tableBody);
        };

        let addButtonClickEventListener = () => {
            let tableRow = this._createTableRow();
            let removeAction = this._createRemoveAction(tableRow.getAttribute('data-index'));

            tableRow.querySelector('.remove').appendChild(removeAction);

            this.tableBody.appendChild(tableRow);
            this._addRemoveActionClickEventListener(removeAction);

            formFieldFocuser(tableRow.querySelector('.name input'));
        };

        this.element.addEventListener('shown.bs.modal', shownEventListener);
        this.element.addEventListener('hidden.bs.modal', hiddenEventListener);
        this.closeButton.addEventListener('click', closeButtonClickEventListener);
        this.addButton.addEventListener('click', addButtonClickEventListener);

        [].forEach.call(this.element.querySelectorAll('.js-remove'), (removeAction) => {
            this._addRemoveActionClickEventListener(removeAction);
        });

        [].forEach.call(this.element.querySelectorAll('.name input, .value input'), (input) => {
            input.addEventListener('keydown', this._inputKeyDownEventListener.bind(this));
        });
    };

    _addRemoveActions () {
        [].forEach.call(this.element.querySelectorAll('.remove'), (removeContainer, index) => {
            removeContainer.appendChild(this._createRemoveAction(index));
        });
    };

    /**
     * @param {Element} removeAction
     * @private
     */
    _addRemoveActionClickEventListener (removeAction) {
        removeAction.addEventListener('click', this._removeActionClickEventListener.bind(this));
    };

    /**
     * @param {number} index
     * @returns {Element | null}
     * @private
     */
    _createRemoveAction (index) {
        let removeActionContainer = this.element.ownerDocument.createElement('div');
        removeActionContainer.innerHTML = '<i class="fa fa-trash-o js-remove" title="Remove this cookie" data-for="cookie-data-row-' + index + '"></i>';

        return removeActionContainer.querySelector('.js-remove');
    };

    /**
     * @returns {Node}
     * @private
     */
    _createTableRow () {
        let nextCookieIndex = this.element.getAttribute('data-next-cookie-index');
        let lastTableRow = this.element.querySelector('.js-cookie');
        let tableRow = lastTableRow.cloneNode(true);
        let nameInput = tableRow.querySelector('.name input');
        let valueInput = tableRow.querySelector('.value input');

        nameInput.value = '';
        nameInput.setAttribute('name', 'cookies[' + nextCookieIndex + '][name]');
        nameInput.addEventListener('keyup', this._inputKeyDownEventListener.bind(this));
        valueInput.value = '';
        valueInput.setAttribute('name', 'cookies[' + nextCookieIndex + '][value]');
        valueInput.addEventListener('keyup', this._inputKeyDownEventListener.bind(this));

        tableRow.setAttribute('data-index', nextCookieIndex);
        tableRow.setAttribute('id', 'cookie-data-row-' + nextCookieIndex);
        tableRow.querySelector('.remove').innerHTML = '';

        this.element.setAttribute('data-next-cookie-index', parseInt(nextCookieIndex, 10) + 1);

        return tableRow;
    };

    /**
     * @returns {boolean}
     */
    isEmpty () {
        let isEmpty = true;

        [].forEach.call(this.element.querySelectorAll('input'), (input) => {
            if (isEmpty && input.value.trim() !== '') {
                isEmpty = false;
            }
        });

        return isEmpty;
    };
}

module.exports = CookieOptionsModal;
