let bsn = require('bootstrap.native');
let formFieldFocuser = require('../../form-field-focuser');

class Alert {
    constructor (element) {
        this.element = element;

        let closeButton = element.querySelector('.close');
        if (closeButton) {
            closeButton.addEventListener('click', this._closeButtonClickEventHandler.bind(this));
        }
    }

    _closeButtonClickEventHandler () {
        let relatedFieldId = this.element.getAttribute('data-for');
        if (relatedFieldId) {
            let relatedField = this.element.ownerDocument.getElementById(relatedFieldId);

            if (relatedField) {
                formFieldFocuser(relatedField);
            }
        }

        let bsnAlert = new bsn.Alert(this.element);
        bsnAlert.close();
    }
}

module.exports = Alert;
