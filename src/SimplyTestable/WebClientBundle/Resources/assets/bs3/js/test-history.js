$(document).ready(function() {
    if ($('.requires-results').length > 0) {
        $('.requires-results').each(function () {
            $(this).stResultPreparer().init();
        });
    }
});