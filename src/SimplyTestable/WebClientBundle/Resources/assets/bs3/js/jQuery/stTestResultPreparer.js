;(function() {

    var getBaseUrl = function (test) {
        var baseUrl = (window.location.protocol + "//" + window.location.hostname + window.location.pathname).replace('/history/', '/');

        baseUrl += $('.website', test).text();
        baseUrl += '/' + test.attr('data-test-id');

        return baseUrl ;
    };

    var getSummary = function (localTaskCount, remoteTaskCount) {
        if (localTaskCount === undefined && remoteTaskCount === undefined) {
            return 'Preparing summary &hellip;';
        }

        return 'Preparing summary &hellip; retrieved <strong class="local-task-count">'+ localTaskCount +'</strong> results of <strong class="remote-task-count">'+ remoteTaskCount +'</strong>';
    };

    var displayTestSummary = function (test) {

        jQuery.ajax({
            dataType: 'html',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                var summaryStats = $('.summary-stats', data);

                $('.summary-stats', test).replaceWith(summaryStats);
                $('.summary-stats' ,test).css({
                    'opacity':0
                });

                $('.preparing', test).fadeOut(function () {
                    test.removeClass('requires-results');

                    $('.summary-stats').animate({
                        'opacity':1
                    });
                });
            },
            url: getBaseUrl(test) + '/finished-summary-bs3/'
        });
    };

    var retrieveNextRemoteTaskIdCollection = function(test, taskIds) {
        jQuery.ajax({
            type: 'POST',
            error: function(request, textStatus, errorThrown) {
            },
            data: {
                'remoteTaskIds': taskIds.join(',')
            },
            success: function(data, textStatus, request) {
                checkStatus(test, function (data) {
                    var completionPercent = Math.ceil(data.completion_percent);

                    $('.progress-bar', test).css({
                        'width':completionPercent + '%'
                    }).attr('aria-valuenow', completionPercent);

                    if (data.completion_percent === 100) {
                        displayTestSummary(test);
                    } else {
                        $('.local-task-count', test).text(data.local_task_count);
                        getNextRemoteTaskIdCollection(test);
                    }
                });
            },
            url: getBaseUrl(test) + '/results/retrieve/'
        });
    };

    var getNextRemoteTaskIdCollection = function(test) {
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                retrieveNextRemoteTaskIdCollection(test, data);
            },
            url: getBaseUrl(test) + '/tasks/ids/unretrieved/100/'
        });
    };

    var checkStatus = function (test, callback) {
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                callback(data);
            },
            url: getBaseUrl(test) + '/results/preparing/stats/'
        });
    };


    var methods = {
        init: function (dummy) {
            var test = $(this);

            checkStatus(test, function (data) {
                $('.preparing .summary', test).html(getSummary(data.local_task_count, data.remote_task_count))
                getNextRemoteTaskIdCollection(test);
            });

            $('.summary-stats', test).replaceWith(dummy);
            $('.summary-stats', test).before('<div class="preparing"><p class="summary">' + getSummary() + '</p><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div></div>');

            return this;
        }
    };

    $.fn.stResultPreparer = function() {
        for (var methodKey in methods) {
            if (methods.hasOwnProperty(methodKey)) {
                this[methodKey] = methods[methodKey];
            }
        }

        return this;
    };

})();