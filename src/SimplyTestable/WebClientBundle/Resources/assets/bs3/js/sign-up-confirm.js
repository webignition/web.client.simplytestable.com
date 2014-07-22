$(document).ready(function() {
    var getSelectableField = function () {
        var fieldSelectors = ['#password', '#token'];

        for (var selectorIndex = 0; selectorIndex < fieldSelectors.length; selectorIndex++) {
            var field = $(fieldSelectors[selectorIndex]);
            if (field.length > 0 && field.stFormHelper().isEmpty()) {
                return field;
            }
        }

        return $('#token');
    }

    getSelectableField().stFormHelper().select();
});