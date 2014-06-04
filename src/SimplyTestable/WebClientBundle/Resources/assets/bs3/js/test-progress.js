$(document).ready(function() {
    var latestTestData = {};

    var setCompletionPercentValue = function () {
//        if (latestTestData.remote_test.state === 'failed-no-sitemap') {
//            if (!$('.progress').hasClass('progress-success')) {
//                $('.progress').addClass('progress-success');
//            }
//        } else {
//            if ($('.progress').hasClass('progress-success')) {
//                $('.progress').removeClass('progress-success');
//
//                if (latestTestData.is_owner) {
//                    $('#cancel-crawl-form').remove();
//                }
//            }
//        }

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
                '?output=json&timestamp=' + now.getTime()
            ].join('');

            return url;
        };

        jQuery.ajax({
            headers: {
                Accept : "application/json"
            },
            complete: function (request) {
//                console.log('complete');
//                if (request.getResponseHeader('content-type') && request.getResponseHeader('content-type').indexOf('application/json') === -1) {
//                    location.reload();
//                }
            },
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                if (data.this_url !== getProgressUrl()) {
                    window.location.href = data.this_url;
                    return;
                }

                latestTestData = data;

                //console.log(data);

//                if (latestTestData.remote_test.state === 'in-progress') {
//                    $('#test-summary-container').css({
//                        'display':'block'
//                    });
//
//                    $('#test-list').css({
//                        'display':'block'
//                    });
//                }
//


                setCompletionPercentValue();
                setCompletionPercentStateLabel();


//                setCompletionPercentStateIcon();
                setTaskQueues();
//                setUrlCount();
//                setTaskCount();
//                setAmmendments();
//
//                if (latestTestData.remote_test.state !== 'failed-no-sitemap') {
//                    storeEstimatedTimeRemaining();
//                }
//
//                if (latestTestData.remote_test.state === 'failed-no-sitemap' && latestTestData.is_owner === true) {
//                    setCancelCrawlButton();
//                }
//
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

});