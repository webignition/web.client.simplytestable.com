class TestStartForm {
    constructor (element) {
        this.document = element.ownerDocument;
        this.element = element;
        this.submitButtons = this.element.querySelectorAll('[type=submit]');
        this.taskTypeContainers = this.element.querySelectorAll('.task-type');
        this.elementAnimator = new ElementAnimator();
    };

    init () {
        this.element.addEventListener('submit', this._submitEventListener.bind(this));
        [].forEach.call(this.submitButtons, (button) => {
            button.addEventListener('click', this._submitButtonEventListener);
        });
    };

    _submitEventListener (event) {
        // event.preventDefault();
        // event.stopPropagation();

        [].forEach.call(this.submitButtons, (button) => {
            button.setAttribute('disabled', 'disabled');
        });

        this._replaceUncheckedCheckboxesWithHiddenFields();
    };

    _submitButtonEventListener (event) {
        let button = event.target;
        let icon = button.querySelector('.fa');
        let iconClassList = icon.classList;

        button.classList.add('de-emphasize');
        iconClassList.remove('fa-globe', 'fa-circle-o');
        iconClassList.add('fa-spinner', 'fa-spin');
    };

    _replaceUncheckedCheckboxesWithHiddenFields () {
        [].forEach.call(this.element.querySelectorAll('input[type=checkbox]'), (input) => {
            if (!input.checked) {
                let hiddenInput = this.document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', input.getAttribute('name'));
                hiddenInput.value = '0';

                this.element.append(hiddenInput);
            }
        });
    };
}

module.exports = TestStartForm;
