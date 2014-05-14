$(document).ready(function() {

    var nextTaskIdCollection = [];

    var getProgressBar = function () {
        return $('.progress-bar');
    };

    var getRemainingTasksToRetrieveCount = function() {
        return parseInt(getProgressBar().attr('data-remaining-tasks-to-retrieve-count'), 10);
    };

    var getUnretrievedRemoteTaskIdsUrl = function() {
        return window.location.href.replace('/results/preparing/', '/tasks/ids/unretrieved/100/');
    };

    var getTaskResultsRetrieveUrl = function() {
        return window.location.href.replace('/preparing/', '/retrieve/');
    };

    var getRetrievalStatusUrl = function() {
        return getTaskResultsRetrieveUrl() + 'status/';
    };

    var getResultsUrl = function() {
        return window.location.href.replace('/preparing/', '');
    };

    var getRetrievalStatus = function() {
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                console.log(data);

                $('#completion-percent-value').text(data['completion-percent']);

                getProgressBar().attr('data-remaining-tasks-to-retrieve-count', data['remaining-tasks-to-retrieve-count']);


                getProgressBar().css({
                    'width': data['completion-percent'] + '%'
                });

                $('#local-task-count').text(data['local-task-count']);
                $('#remote-task-count').text(data['remote-task-count']);

                initialise();
            },
            url: getRetrievalStatusUrl()
        });
    };

    var retrieveNextRemoteTaskIdCollection = function() {
        jQuery.ajax({
            type: 'POST',
            error: function(request, textStatus, errorThrown) {
            },
            data: {
                'remoteTaskIds': nextTaskIdCollection.join(',')
            },
            success: function(data, textStatus, request) {
                getRetrievalStatus();
            },
            url: getTaskResultsRetrieveUrl()
        });
    };


    var getNextRemoteTaskIdCollection = function() {
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                nextTaskIdCollection = data;
                retrieveNextRemoteTaskIdCollection();
            },
            url: getUnretrievedRemoteTaskIdsUrl()
        });
    };

    var initialise = function () {
        if (getRemainingTasksToRetrieveCount() === 0) {
            window.location.href = getResultsUrl();
        } else {
            getNextRemoteTaskIdCollection();
        }
    };

    initialise();
});
