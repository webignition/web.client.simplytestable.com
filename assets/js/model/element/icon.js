class Icon {
    constructor (element) {
        this.element = element;
    }

    static getClass () {
        return 'fa';
    }

    static getSelector () {
        return '.' + Icon.getClass();
    };

    setBusy () {
        this._removePresentationClasses();
        this.element.classList.add('fa-spinner', 'fa-spin');
    };

    setAvailable (activeIconClass) {
        this._removePresentationClasses();
        this.element.classList.add(activeIconClass);
    };

    setSuccessful () {
        this._removePresentationClasses();
        this.setAvailable('fa-check');
    }

    _removePresentationClasses () {
        let classesToRetain = [
            Icon.getClass(),
            Icon.getClass() + '-fw'
        ];

        let presentationalClassPrefix = Icon.getClass() + '-';

        this.element.classList.forEach((className, index, classList) => {
            if (!classesToRetain.includes(className) && className.indexOf(presentationalClassPrefix) === 0) {
                classList.remove(className);
            }
        });
    };
}

module.exports = Icon;
