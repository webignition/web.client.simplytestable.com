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
        //this.isInitialised = isInitialised;
    };

    var taskListController = function () {
        var taskIdList = new taskIdListController();
        var taskLists = [];
        var isLoadingTaskList = false;
        var hasBeforeEventFired = false;

        var getTasksUrl = function() {
            var url = [
                window.location.protocol,
                '//',
                window.location.host,
                window.location.pathname.replace('/progress/', '/tasklist/')
            ].join('');

            return url;
        };

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

            var taskListToRender = $(taskLists[currentPage]).attr('data-page', currentPage);

            $('#tasks').append(taskListToRender);
        };

        var update = function () {
            console.log('update');
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
                        jQuery.ajax({
                            type: 'POST',
                            data:{
                                'taskIds':taskIdList.getCurrentPageList()
                            },
                            error: function(request, textStatus, errorThrown) {
                                console.log('error');
                            },
                            success: function(data, textStatus, request) {
                                isLoadingTaskList = false;
                                taskLists[currentPage] = data;
                            },
                            url: getTasksUrl()
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

    var paginator = new paginationController();
    var taskList = new taskListController();
    var busyIndicator = new busyIndicatorController();

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
        $('.tasks').animate({
            'opacity':0.6
        });
    });

    $('body').on('tasklist.render.after', function () {
        busyIndicator.hide();
        $('.tasks').animate({
            'opacity':1
        });
    });

    this.initialise = function () {
        initialise();
    };
};