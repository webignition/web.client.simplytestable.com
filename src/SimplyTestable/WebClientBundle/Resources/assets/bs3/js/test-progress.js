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
//                setTestQueues();
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

});