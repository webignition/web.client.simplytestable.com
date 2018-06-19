var latestTestData = {};

$(document).ready(function() {
    var liveResultsController = new testProgressTasksController();

    var initialiseLiveResults = function () {
        if (['queued', 'in-progress'].indexOf(latestTestData.test.state) !== -1) {
            liveResultsController.initialise();
        }
    };

    var refreshTestSummary = function() {
        var now = new Date();

        var getProgressUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname
            ].join('');

            return url;
        };

        var getProgressUpdateUrl = function() {
            var url = [
                getProgressUrl(),
                '?timestamp=' + now.getTime()
            ].join('');

            return url;
        };

        jQuery.ajax({
            headers: {
                Accept : "application/json"
            },
            complete: function (request) {
            },
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                if (data.this_url !== getProgressUrl()) {
                    return;
                }

                latestTestData = data;

                var body = $('body')
                var bodyClasses = body.attr('class').split(' ');

                for (var classIndex = 0; classIndex < bodyClasses.length; classIndex++) {
                    if (bodyClasses[classIndex].match(/^job-/)) {
                        body.removeClass(bodyClasses[classIndex]);
                    }
                }

                body.addClass('job-' + latestTestData.test.state);

                initialiseLiveResults();

                window.setTimeout(function() {
                    refreshTestSummary(10);
                }, 3000);
            },
            url: getProgressUpdateUrl()
        });
    };

    refreshTestSummary();
});