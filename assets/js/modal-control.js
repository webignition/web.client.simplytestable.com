module.exports = function (controlElements) {
    let initialize = function (controlElement) {
        controlElement.classList.add('btn', 'btn-link');
    };

    for (let i = 0; i < controlElements.length; i++) {
        initialize(controlElements[i]);
    }
};
