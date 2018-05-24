module.exports = function (inputs) {
    let selectInput = function (input) {
        let inputValue = input.value;

        input.focus();
        input.value = '';
        input.value = inputValue;
    };

    for (let i = 0; i < inputs.length; i++) {
        selectInput(inputs[i]);
    }
};
