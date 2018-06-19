var latestTestData = {};

$(document).ready(function() {
    var liveResultsController = new testProgressTasksController();

    var displayAmmendment = function(messageContent) {
        var ammendmentNotification = $('<div class="alert alert-info alert-ammendment">').append(
            $('<div class="container">').append(messageContent)
        );

        var ammendmentNotificationClone = ammendmentNotification.clone(ammendmentNotification);
        ammendmentNotificationClone.addClass('ammendment-clone').css({
            'display': 'none'
        });

        $('.alert-container').append(ammendmentNotificationClone);

        ammendmentNotificationClone.slideDown(function() {
            ammendmentNotificationClone.remove();

            ammendmentNotification.css({
                'opacity': 0

            });

            $('.alert-container').append(ammendmentNotification);

            ammendmentNotification.animate({
                'opacity': 1
            });
        });
    };

    var setAmmendments = function() {
        if ($('.alert-ammendment').length > 0) {
            return;
        }

        if (latestTestData.remote_test.ammendments && latestTestData.remote_test.ammendments[0]) {
            if (latestTestData.remote_test.ammendments[0].reason.indexOf('plan-url-limit-reached:') !== -1) {
                $.get(window.location.href.replace('progress', 'url-limit-notification'), function(data) {
                    displayAmmendment(data);
                });
            }
        }
    };

    var setQueueNameMinimumWidths = function () {
        var maximumWidth = 0;

        $('.task-queues .bar .label').each(function () {
            var label = $(this);
            if (label.width() > maximumWidth) {
                maximumWidth = label.outerWidth();
            }
        });

        $('.task-queues .bar .label').each(function () {
            var label = $(this);
            var width = maximumWidth + 'px';

            if (label.attr('data-width')) {
                width = label.attr('data-width') + '%';
            }

            $(this).css({
                'min-width':maximumWidth + 'px',
                'width':width,
                'display':'block'
            })
        });
    };

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
                    window.location.href = data.this_url;
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

                setAmmendments();
                initialiseLiveResults();

                window.setTimeout(function() {
                    refreshTestSummary(10);
                }, 3000);
            },
            url: getProgressUpdateUrl()
        });
    };

    refreshTestSummary();
    setQueueNameMinimumWidths();
});