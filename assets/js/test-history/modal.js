let bsn = require('bootstrap.native');

class Modal {
    /**
     * @param {HTMLElement} element
     */
    constructor (element) {
        this.element = element;
        this.content = element.querySelector('.modal-content');
        this.closeButton = element.querySelector('.close');
        this.modal = new bsn.Modal(this.element);
        this.allowClose = true;

        this.init();
    }

    init () {
        this.closeButton.addEventListener('click', () => {
            this.close();
        });

        this.element.addEventListener('click', () => {
            if (this.allowClose) {
                this.close();
            }
        });

        this.content.addEventListener('click', (event) => {
            this.allowClose = true;
            event.stopPropagation();
        });

        this.element.addEventListener('hide.bs.modal', () => {
            this.allowClose = true;
        });
    };

    show () {
        this.modal.show();
    };

    close () {
        this.modal.hide();
    }
}

module.exports = Modal;
