module.exports = function (input) {
    let inputValue = input.value;

    input.focus();
    input.value = '';
    input.value = inputValue;
};
