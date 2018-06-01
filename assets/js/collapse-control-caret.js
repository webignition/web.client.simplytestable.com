module.exports = function (controls) {
    const controlIconClass = 'fa';
    const caretUpClass = 'fa-caret-up';
    const caretDownClass = 'fa-caret-down';
    const controlCollapsedClass = 'collapsed';

    let createControlIcon = function (control) {
        const controlIcon = document.createElement('i');
        controlIcon.classList.add(controlIconClass);

        if (control.classList.contains(controlCollapsedClass)) {
            controlIcon.classList.add(caretDownClass);
        } else {
            controlIcon.classList.add(caretUpClass);
        }

        return controlIcon;
    };

    let toggleCaret = function (control, controlIcon) {
        if (control.classList.contains(controlCollapsedClass)) {
            controlIcon.classList.remove(caretUpClass);
            controlIcon.classList.add(caretDownClass);
        } else {
            controlIcon.classList.remove(caretDownClass);
            controlIcon.classList.add(caretUpClass);
        }
    };

    let handleControl = function (control) {
        const eventNameShown = 'shown.bs.collapse';
        const eventNameHidden = 'hidden.bs.collapse';
        const collapsibleElement = document.getElementById(control.getAttribute('data-target').replace('#', ''));
        const controlIcon = createControlIcon(control);

        control.append(controlIcon);

        let shownHiddenEventListener = function () {
            toggleCaret(control, controlIcon);
        };

        collapsibleElement.addEventListener(eventNameShown, shownHiddenEventListener.bind(this));
        collapsibleElement.addEventListener(eventNameHidden, shownHiddenEventListener.bind(this));
    };

    for (let i = 0; i < controls.length; i++) {
        handleControl(controls[i]);
    }
};
