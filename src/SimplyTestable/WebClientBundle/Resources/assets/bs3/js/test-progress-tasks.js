var testProgressTasksController = function () {
    var tasks = $('#tasks');
    var isInitialised = false;
    var pageLength = 100;
    var currentPage = 1;

    var busyIndicatorController = function () {

        this.show = function () {
            $('h2', tasks).append('<i class="fa fa-spinner fa-spin"></i>');
        };

        this.hide = function () {
            $('h2 .fa-spinner', tasks).remove();
        };

    };

    var taskIdListController = function () {
        var taskIds = null;
        var isReady = false;

        var getTaskIdsUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname.replace('/progress/', '/tasks/ids/')
            ].join('');

            return url;
        };

        var initialise = function () {
            if (taskIds === null) {
                jQuery.ajax({
                    dataType: 'json',
                    error: function(request, textStatus, errorThrown) {
                    },
                    success: function(data, textStatus, request) {
                        if (data.length > 0) {
                            taskIds = data;
                        }

                        isReady = true;
                    },
                    url: getTaskIdsUrl()
                });
            }
        };

        var getCurrentPageList = function () {
            var currentPageIndex = currentPage - 1;

            return taskIds.slice(currentPageIndex * pageLength, currentPage * pageLength);
        };

        this.initialise = initialise;
        this.getCurrentPageList = getCurrentPageList;

        this.isReady = function () {
            return isReady;
        };
    };

    var taskListController = function () {
        var taskIdList = new taskIdListController();
        var taskList = null;
        var isReady = false;

        var getTasksUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname.replace('/progress/', '/tasklist/')
            ].join('');

            return url;
        };

        var initialise = function () {
            taskIdList.initialise();

            var taskIdListReadinessCheck = function (callback) {
                if (taskIdList.isReady()) {
                    callback();
                } else {
                    window.setTimeout(function () {
                        taskIdListReadinessCheck(callback);
                    }, 300);
                }
            };

            taskIdListReadinessCheck(function () {
                jQuery.ajax({
                    type: 'POST',
                    data:{
                        'taskIds':taskIdList.getCurrentPageList()
                    },
                    error: function(request, textStatus, errorThrown) {
                        console.log('error');
                    },
                    success: function(data, textStatus, request) {
                        taskList = data;
                        isReady = true;
                        console.log("isReady");
                    },
                    url: getTasksUrl()
                });
            });

            console.log('task list controller init');
        };

        var draw = function () {
            $('#tasks').append(taskList);
        };

        var update = function () {

        };

        var render = function () {
            if (!isReady) {
                return;
            }

            if ($('.tasks').length) {
                update();
            } else {
                draw();
            }
        };

        this.isReady = function () {
            return isReady;
        };

        this.initialise = initialise;
        this.render = render;
    };

    var paginationController = function () {
        var isXs = function () {
            var firstItem = $(getItems().first());
            return firstItem.is('.is-xs') && firstItem.css('display') !== 'none';
        };

        var getItems = function () {
            return $('ul.pagination li');
        };

        var render = function () {
            var pageCount = Math.ceil(latestTestData.remote_test.task_count / pageLength);

            var pagination = $('<ul class="pagination">').append(
                    '<li class="is-xs disabled hidden-lg hidden-md hidden-sm"><span><i class="fa fa-caret-left"></i> Previous</span></li>'
                ).append(
                    '<li class="hidden-lg hidden-md hidden-sm disabled"><span>Page <strong id="page-index">1</strong> of <strong>' + pageCount + '</strong></span></li>'
                );

            for (var pageIndex = 0; pageIndex < pageCount; pageIndex++) {
                var startIndex = (pageIndex * pageLength) + 1;
                var endIndex = startIndex + pageLength - 1;

                pagination.append('<li class="is-not-xs hidden-xs" id="page-' + (pageIndex + 1) + '"><a href="#"><span>' + startIndex + ' … ' + endIndex + '</span></a></li>');
            }

            pagination.append('<li class="hidden-lg hidden-md hidden-sm"><span>Next <i class="fa fa-caret-right"></i></span></li>');

            pagination.click(function (event) {
                console.log(event.target);
            });

            tasks.append(pagination);
        };

        var isRequired = function () {
            return latestTestData.remote_test.task_count > pageLength;
        };

        var selectCurrentPage = function () {
            if (isXs()) {
                return;
            }

            getItems().each(function () {
                $(this).removeClass('active');
            });

            $('#page-' + currentPage).addClass('active');
        };

        var initialise = function () {
            render();
        };

        this.initialise = initialise;
        this.isRequired = isRequired;
        this.selectCurrentPage = selectCurrentPage;
        this.isXs = isXs;
    };

    var paginator = new paginationController();
    var taskList = new taskListController();
    var busyIndicator = new busyIndicatorController();

    var initialise = function () {
        if (isInitialised) {
            return;
        }

        isInitialised = true;

        tasks.css('display', 'block');
        busyIndicator.show();

        if (paginator.isRequired()) {
            paginator.initialise();
            paginator.selectCurrentPage();
        }

        taskList.initialise();

        var taskListReadinessCheck = function (callback) {
            if (taskList.isReady()) {
                callback();
            } else {
                window.setTimeout(function () {
                    taskListReadinessCheck(callback);
                }, 300);
            }
        };

        taskListReadinessCheck(function () {
            taskList.render();
            busyIndicator.hide();
        });
    };

    this.initialise = function () {
        initialise();
    };
};