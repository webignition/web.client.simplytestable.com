let Alert = require('./alert.js');

class AlertFactory {
    static createFromContent (document, errorContent, relatedFieldId) {
        let element = document.createElement('div');
        element.classList.add('alert', 'alert-danger', 'fade', 'in');
        element.setAttribute('role', 'alert');
        element.setAttribute('data-for', relatedFieldId);

        let elementInnerHTML = '';

        if (relatedFieldId) {
            elementInnerHTML += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
        }

        elementInnerHTML += errorContent;
        element.innerHTML = elementInnerHTML;

        return new Alert(element);
    };

    static createFromElement (alertElement) {
        return new Alert(alertElement);
    }
}

module.exports = AlertFactory;
