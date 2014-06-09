var testProgressTasksController = function () {
    var tasks = $('#tasks');
    var isInitialised = false;
    var pageLength = 100;
    var currentPage = 1;

    var busyIndicatorController = function () {

        this.show = function () {
            var heading = $('h2', tasks);

            $('.fa', heading).remove();
            heading.append('<i class="fa fa-spinner fa-spin"></i>');
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
            $('body').trigger('tasklist.retrieve.before');

            jQuery.ajax({
                type: 'POST',
                data:{
                    'taskIds':taskIds
                },
                error: function(request, textStatus, errorThrown) {
                },
                success: function(data, textStatus, request) {
                    $('body').trigger('tasklist.retrieve.after');

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
        var hasBeforeEventFired = false;

        /**
         *
         * @returns {boolean}
         */
        var hasPage = function (pageNumber) {
            return typeof taskLists[pageNumber] !== 'undefined';
        };

        var isCurrentPageDisplayed = function () {
            return parseInt($('.tasks').attr('data-page'), 10) === currentPage;
        };

        var isAnyPageDisplayed = function () {
            return $('.tasks').length > 0;
        };

        var draw = function (pageNumber) {
            var currentTaskList = $('.tasks');
            if (currentTaskList.length) {
                currentTaskList.remove();
            }

            $('#tasks').append(taskLists[pageNumber]);
        };

        var update = function (pageNumber) {
            $('.task', taskLists[currentPage]).each(function () {
                var updatedTask = $(this);
                var inPageTask = $('#' + updatedTask.attr('id'));

                if (inPageTask.length && inPageTask.attr('data-state') != updatedTask.attr('data-state')) {
                    inPageTask.replaceWith(updatedTask);
                }
            });
        };

        var render = function (pageNumber) {
            $('body').trigger('tasklist.render.before', pageNumber);

            if (!hasPage(pageNumber)) {
                taskIdList.initialise(function () {
                    var retriever = new taskListRetrieveController();
                    retriever.retrieve(taskIdList.getCurrentPageList(), function (data) {
                        var taskList = $('<div>').append(data);
                        $('.tasks', taskList).attr('data-page', pageNumber);

                        taskLists[pageNumber] = taskList.html();
                        render(pageNumber);
                    });
                });

                return;
            }

            if (pageNumber === currentPage) {
                if (isCurrentPageDisplayed()) {
                    update(pageNumber);
                } else {
                    draw(pageNumber);
                }
            }

            $('body').trigger('tasklist.render.after', [pageNumber]);
            hasBeforeEventFired = false;
        };

        var store = function (tasks) {
            for (var taskListIndex = 1; taskListIndex < taskLists.length; taskListIndex++) {
                var taskList = $('<div>').append(taskLists[taskListIndex]);
                var taskListUpdated = false;

                tasks.each(function () {
                    var updatedTask = $(this);
                    var currentTask = $('#' + updatedTask.attr('id'), taskList);

                    if (currentTask.length) {
                        currentTask.replaceWith(updatedTask);
                        taskListUpdated = true;
                    }

                    //$('#' + updatedTask.attr('id'), taskList).replaceWith(updatedTask);
                });

                if (taskListUpdated) {
                    taskLists[taskListIndex] = taskList.html();
                }
            }
        };

        this.render = render;
        this.store = store;
        this.isCurrentPageDisplayed = isCurrentPageDisplayed;
        this.isAnyPageDisplayed = isAnyPageDisplayed;
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
                    '<li class="is-xs previous-next previous disabled hidden-lg hidden-md hidden-sm"><a href="#"><span><i class="fa fa-caret-left"></i> Previous</span></a></li>'
                ).append(
                    '<li class="hidden-lg hidden-md hidden-sm disabled"><span>Page <strong id="page-index">1</strong> of <strong id="page-count">' + pageCount + '</strong></span></li>'
                );

            for (var pageIndex = 0; pageIndex < pageCount; pageIndex++) {
                var startIndex = (pageIndex * pageLength) + 1;
                var endIndex = Math.min(startIndex + pageLength - 1, latestTestData.remote_test.task_count);

                pagination.append('<li class="is-not-xs hidden-xs" id="page-' + (pageIndex + 1) + '"><a href="#"><span>' + startIndex + ' … ' + endIndex + '</span></a></li>');
            }

            pagination.append('<li class="next previous-next hidden-lg hidden-md hidden-sm"><a href="#"><span>Next <i class="fa fa-caret-right"></i></span></a></li>');

            pagination.click(function (event) {
                var item = $(event.target).closest('li');

                if (item.is('.previous-next')) {
                    var pageIndex = parseInt($('#page-index').text(), 10);
                    var pageCount = parseInt($('#page-count').text(), 10);

                    var pageNumberChange = (item.is('.next')) ? +1 : -1;

                    var selectedPageNumber = pageIndex + pageNumberChange;
                    if (selectedPageNumber <= pageCount) {
                        $(event.target).trigger('page.click', [selectedPageNumber]);
                    }
                } else {
                    var selectedPageNumber = parseInt(item.attr('id').replace('page-', ''), 10);
                    $(event.target).trigger('page.click', [selectedPageNumber]);
                }

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
            $('#page-index').text(currentPage);

            if (currentPage === 1) {
                $('.previous-next.previous').addClass('disabled');
                $('.previous-next.next').removeClass('disabled');
            } else if (currentPage === parseInt($('#page-count').text(), 10)) {
                $('.previous-next.previous').removeClass('disabled');
                $('.previous-next.next').addClass('disabled');
            } else {
                $('.previous-next.previous').removeClass('disabled');
                $('.previous-next.next').removeClass('disabled');
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

        var isRetrieving = function () {
            if (retrieveRequest === null) {
                return false;
            }

            return retrieveRequest.readyState !== 4;
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
                taskList.render(currentPage);
            });
        }

        taskList.render(currentPage);
    };

    $('body').on('tasklist.render.before', function (event, pageNumber) {
        if (!taskList.isCurrentPageDisplayed()) {
            busyIndicator.show();
            $('.tasks').animate({
                'opacity':0.6
            });
        }
    });

    $('body').on('tasklist.render.after', function (event, pageNumber) {
        if (taskList.isCurrentPageDisplayed()) {
            busyIndicator.hide();

            $('.tasks').animate({
                'opacity':1
            });
        }

        window.setTimeout(function () {
            taskUpdater.update();
        }, 3000);
    });

    $('body').on('tasklist.update.retrieve', function (event, data) {
        taskList.store($('.task', data));
        taskList.render(currentPage);
    });

    $('body').on('tasklist.retrieve.before', function () {
    });

    $('body').on('tasklist.retrieve.after', function () {
    });

    this.initialise = function () {
        initialise();
    };
};