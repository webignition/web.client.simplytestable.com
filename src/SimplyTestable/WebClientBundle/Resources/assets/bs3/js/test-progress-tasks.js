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

        var getTaskIdsUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname.replace('/progress/', '/tasks/ids/')
            ].join('');

            return url;
        };

        var initialise = function (callback) {
            if (!isInitialised()) {
                jQuery.ajax({
                    dataType: 'json',
                    error: function(request, textStatus, errorThrown) {
                    },
                    success: function(data, textStatus, request) {
                        if (data.length > 0) {
                            taskIds = data;
                        }
                    },
                    url: getTaskIdsUrl()
                });

                window.setTimeout(function () {
                    initialise(callback);
                }, 300);

                return;
            }

            if (typeof callback === 'function') {
                callback();
            }
        };


        var getCurrentPageList = function () {
            var currentPageIndex = currentPage - 1;
            return taskIds.slice(currentPageIndex * pageLength, currentPage * pageLength);
        };

        /**
         *
         * @returns {boolean}
         */
        var isInitialised = function () {
            return taskIds !== null;
        };


        this.initialise = initialise;
        this.getCurrentPageList = getCurrentPageList;
    };

    var taskListRetrieveController = function () {
        var getTasksUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname.replace('/progress/', '/tasklist/')
            ].join('');

            return url;
        };

        var retrieve = function (taskIds, callback) {
            jQuery.ajax({
                type: 'POST',
                data:{
                    'taskIds':taskIds
                },
                error: function(request, textStatus, errorThrown) {
//                    console.log('error');
                },
                success: function(data, textStatus, request) {
                    if (typeof callback === 'function') {
                        callback(data);
                    }
                },
                url: getTasksUrl()
            });
        };

        this.retrieve = retrieve;
    };

    var taskListController = function () {
        var taskIdList = new taskIdListController();
        var taskLists = [];
        var isLoadingTaskList = false;
        var hasBeforeEventFired = false;

        /**
         *
         * @returns {boolean}
         */
        var hasCurrentPage = function () {
            return typeof taskLists[currentPage] !== 'undefined';
        };

        var isCurrentPageDisplayed = function () {
            return parseInt($('.tasks').attr('data-page'), 10) === currentPage;
        };

        var draw = function () {
            var currentTaskList = $('.tasks');
            if (currentTaskList.length) {
                currentTaskList.remove();
            }

            $('#tasks').append(taskLists[currentPage]);
        };

        var update = function () {
            $('.task', taskLists[currentPage]).each(function () {
                var updatedTask = $(this);
                var inPageTask = $('#' + updatedTask.attr('id'));

                if (inPageTask.length && inPageTask.attr('data-state') != updatedTask.attr('data-state')) {
                    inPageTask.replaceWith(updatedTask);
                }
            });
        };

        var render = function () {
            if (!hasBeforeEventFired) {
                $('body').trigger('tasklist.render.before');
                hasBeforeEventFired = true;
            }

            if (!hasCurrentPage()) {
                if (!isLoadingTaskList) {
                    isLoadingTaskList = true;

                    taskIdList.initialise(function () {
                        var retriever = new taskListRetrieveController();
                        retriever.retrieve(taskIdList.getCurrentPageList(), function (data) {
                            isLoadingTaskList = false;

                            var taskList = $('<div>').append(data);
                            $('.tasks', taskList).attr('data-page', currentPage);

                            taskLists[currentPage] = taskList.html();
                        });
                    });
                }

                window.setTimeout(function () {
                    render();
                }, 300);

                return;
            }



            if (isCurrentPageDisplayed()) {
                update();
            } else {
                draw();
            }

            $('body').trigger('tasklist.render.after');
            hasBeforeEventFired = false;
        };

        var store = function (tasks) {
            var taskList = $('<div>').append(taskLists[currentPage]);

            tasks.each(function () {
                var updatedTask = $(this);
                $('#' + updatedTask.attr('id'), taskList).replaceWith(updatedTask);
            });

            taskLists[currentPage] = taskList.html();
        };

        this.render = render;
        this.store = store;
        this.isCurrentPageDisplayed = isCurrentPageDisplayed;
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
                var item = $(event.target).closest('li');
                var selectedPageNumber = parseInt(item.attr('id').replace('page-', ''), 10);

                $(event.target).trigger('page.click', [selectedPageNumber]);

                event.preventDefault();
            });

            tasks.append(pagination);
        };

        var isRequired = function () {
            return latestTestData.remote_test.task_count > pageLength;
        };

        var getPagination = function () {
            return $('.pagination');
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
        this.getPagination = getPagination;
        this.isXs = isXs;
    };

    var taskUpdateController = function () {
        var getTaskIdCollection = function () {
            var taskIds = [];

            var states = ['in-progress', 'queued-for-assignment', 'queued'];

            for (var stateIndex = 0; stateIndex < states.length; stateIndex++) {
                if (taskIds.length <= 2) {
                    $('.tasks [data-state=' + states[stateIndex] + ']').each(function () {
                        taskIds.push(parseInt($(this).attr('id').replace('task', ''), 10));
                    });
                }
            }

            return taskIds;
        };

        var update = function () {
            var incompleteTaskIds = getTaskIdCollection();
            if (incompleteTaskIds.length) {
                var retriever = new taskListRetrieveController();
                retriever.retrieve(incompleteTaskIds.slice(0, 8), function (data) {
                    $('body').trigger('tasklist.update.retrieve', [data]);
                });
            }
        };

        this.update = update;
    };

    var paginator = new paginationController();
    var taskList = new taskListController();
    var busyIndicator = new busyIndicatorController();
    var taskUpdater = new taskUpdateController();

    var initialise = function () {
        if (isInitialised) {
            return;
        }

        isInitialised = true;

        tasks.css('display', 'block');

        if (paginator.isRequired()) {
            paginator.initialise();
            paginator.selectCurrentPage();

            paginator.getPagination().on('page.click', function (event, selectedPageNumber) {
                currentPage = selectedPageNumber;
                paginator.selectCurrentPage();
                taskList.render();
            });
        }

        taskList.render();
    };

    $('body').on('tasklist.render.before', function () {
        busyIndicator.show();

        if (!taskList.isCurrentPageDisplayed()) {
            $('.tasks').animate({
                'opacity':0.6
            });
        }
    });

    $('body').on('tasklist.render.after', function () {
        busyIndicator.hide();

        if (!taskList.isCurrentPageDisplayed()) {
            $('.tasks').animate({
                'opacity':1
            });
        }

        window.setTimeout(function () {
            console.log('calling update');
            taskUpdater.update();
        }, 3000);
    });

    $('body').on('tasklist.update.retrieve', function (event, data) {
        taskList.store($('.task', data));
        taskList.render();
    });

    this.initialise = function () {
        initialise();
    };
};