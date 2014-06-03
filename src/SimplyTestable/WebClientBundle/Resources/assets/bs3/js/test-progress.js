$(document).ready(function() {

    var refreshTestSummary = function() {
        var now = new Date();

        var getProgressUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname,
                '?output=json&timestamp=' + now.getTime()
            ].join('');

            return url;

            //return window.location.href + '?output=json&timestamp=' + now.getTime();
        };

        jQuery.ajax({
            headers: {
                Accept : "application/json"
            },
            complete: function (request) {
                console.log('complete');
//                if (request.getResponseHeader('content-type') && request.getResponseHeader('content-type').indexOf('application/json') === -1) {
//                    location.reload();
//                }
            },
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                console.log('success', data);
//                if (data.this_url !== window.location.href) {
//                    window.location.href = data.this_url;
//                    return;
//                }
//
//                latestTestData = data;
//
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
//                setCompletionPercentValue();
//                setCompletionPercentStateLabel();
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
//                window.setTimeout(function() {
//                    refreshTestSummary(10);
//                }, 3000);
            },
            url: getProgressUrl()
        });
    };

    refreshTestSummary();

});