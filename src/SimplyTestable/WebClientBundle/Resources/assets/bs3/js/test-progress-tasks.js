var testProgressTasksController = function () {
    var tasks = $('#tasks');
    var isInitialised = false;
    var pageLength = 100;
    var currentPage = 1;

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

        var getList = function () {

        };

        this.initialise = initialise;
        this.getList = getList;

        this.isReady = function () {
            return isReady;
        };
    };

    var taskListController = function () {
        var taskIdList = new taskIdListController();

        var initialise = function () {
            taskIdList.initialise();
            console.log('task list controller init');
        };

        var render = function () {
            console.log('task list render');
        };

        this.isReady = function () {
            return taskIdList.isReady();
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

    var initialise = function () {
        if (isInitialised) {
            return;
        }

        isInitialised = true;

        tasks.css('display', 'block');
        $('h2', tasks).append('<i class="fa fa-spinner fa-spin"></i>');

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
        });
    };

    this.initialise = function () {
        initialise();
    };
};