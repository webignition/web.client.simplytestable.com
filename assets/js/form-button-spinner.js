module.exports = function (forms) {
    let foo = function (form) {
        const submitButton = form.querySelector('button[type=submit]');
        const submitButtonIcon = submitButton.querySelector('.fa');
        const submitButtonIconClassList = submitButtonIcon.classList;

        form.addEventListener('submit', function (event) {
            submitButtonIconClassList.remove('fa-caret-right');
            submitButtonIconClassList.add('fa-spinner', 'fa-spin');
            submitButton.classList.add('de-emphasize');
        });
    };

    for (let i = 0; i < forms.length; i++) {
        foo(forms[i]);
    }
};
