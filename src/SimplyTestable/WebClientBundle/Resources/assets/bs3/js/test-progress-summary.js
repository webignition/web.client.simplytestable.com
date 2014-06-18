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

    var setCompletionPercentValue = function () {
        var completionPercentBar = $('#completion-percent-bar');
        completionPercentBar.attr('aria-valuenow', latestTestData.remote_test.completion_percent );

        if ($('html.csstransitions').length > 0) {
            completionPercentBar.css({
                'width': latestTestData.remote_test.completion_percent + '%'
            });
        } else {
            completionPercentBar.animate({
                'width': latestTestData.remote_test.completion_percent + '%'
            });
        }

    };

    var setCompletionPercentStateLabel = function() {
        var completionPercentStateLabel = $('#completion-percent-state-label');
        if (completionPercentStateLabel.text() !== latestTestData.state_label) {
            completionPercentStateLabel.text(latestTestData.state_label);
        }
    };

    var setTaskQueues = function () {
        var getWidthForState = function (state) {
            if (latestTestData.remote_test.task_count === 0) {
                return 0;
            }

            if (!latestTestData.remote_test.task_count_by_state.hasOwnProperty(state)) {
                return 0;
            }

            if (latestTestData.remote_test.task_count_by_state[state] === 0) {
                return 0;
            }

            return Math.ceil(latestTestData.remote_test.task_count_by_state[state] / latestTestData.remote_test.task_count * 100);
        };

        for (state in latestTestData.remote_test.task_count_by_state) {
            if (latestTestData.remote_test.task_count_by_state.hasOwnProperty(state)) {
                var label = $('#task-queue-' + state + ' .bar .label');
                if (label.length) {
                    var width = getWidthForState(state);

                    if (width !== label.attr('data-width')) {
                        label.attr('data-width', width);
                        label.animate({
                            'width':label.attr('data-width') + '%'
                        });
                    }

                    $('.value', label).text(latestTestData.remote_test.task_count_by_state[state]);
                }
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

    var initialiseTestSummary = function () {
        if (['queued', 'in-progress'].indexOf(latestTestData.test.state) !== -1) {
            $('.test-summary', '.test-options').css('visibility', 'visible');
        }
    };

    var initialiseLiveResults = function () {
        if (['queued', 'in-progress'].indexOf(latestTestData.test.state) !== -1) {
            liveResultsController.initialise();
        }
    };

    var setProgressBarStyle = function () {
        if (latestTestData.test.state === 'crawling') {
            $('.progress-bar').addClass('progress-bar-warning');
        } else {
            $('.progress-bar').removeClass('progress-bar-warning');
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

                setCompletionPercentValue();
                setCompletionPercentStateLabel();
                setTaskQueues();
                setAmmendments();
                initialiseTestSummary();
                initialiseLiveResults();
                setProgressBarStyle();

                window.setTimeout(function() {
                    refreshTestSummary(10);
                }, 3000);
            },
            url: getProgressUpdateUrl()
        });
    };

    refreshTestSummary();
    setQueueNameMinimumWidths();

    $('#cancel-button').click(function () {
        var button = $(this);
        $('.fa', button).removeClass('fa-power-off').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });
    });

    $('#cancel-crawl-button').click(function () {
        var button = $(this);
        $('.fa', button).removeClass('fa-power-off').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });
    });

});