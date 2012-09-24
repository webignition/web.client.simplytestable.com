var application = {};

application.progress = {};
application.progress.testController = function () {
    var finishedStates = [
        'completed',
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached',
        'failed'
    ];    
    
    var latestTestData = {};
    
    var isFinishedState = function (state) {
        var finishedStateLength = finishedStates.length;

        for (var stateIndex = 0; stateIndex < finishedStateLength; stateIndex++) {
            if (state == finishedStates[stateIndex]) {
                return true;
            }
        }

        return false;
    };
    
    var setCompletionPercentValue = function () {
        var completionPercentValue = $('#completion-percent-value');
        
        if (completionPercentValue.text() != latestTestData.completion_percent) {
            completionPercentValue.text(latestTestData.completion_percent);
            
            if ($('html.csstransitions').length > 0) {
                $('#completion-percent-bar').css({
                    'width':latestTestData.completion_percent + '%'
                });                
            } else {
                $('#completion-percent-bar').animate({
                    'width':latestTestData.completion_percent + '%'
                });                
            }
        }        
    };
    
    var setCompletionPercentStateLabel = function () {
        var completionPercentStateLabel = $('#completion-percent-state-label');
        if (completionPercentStateLabel.text() != latestTestData.state_label) {
            completionPercentStateLabel.text(latestTestData.state_label);
        }         
    };
    
    var setCompletionPercentStateIcon = function () {        
        $('#completion-percent-state-icon').attr('class', '').addClass(latestTestData.state_icon);
     
    };    
    
    var getTestQueueWidth = function (queueName) {        
        var minimumQueueWidth = 2; 
        
        if (latestTestData.task_count_by_state[queueName] == 0) {
            return minimumQueueWidth;
        }
        
        var queueWidth = (latestTestData.task_count_by_state[queueName] / latestTestData.remote_test_summary.task_count) * 100;
        
        return (queueWidth < minimumQueueWidth) ? minimumQueueWidth : queueWidth;
    };
    
    var setTestQueues = function () { 
        var queues = ['queued', 'in_progress', 'completed', 'failed'];
        
        for (var queueNameIndex = 0; queueNameIndex < queues.length; queueNameIndex++) {
            var queueName = queues[queueNameIndex];

            $('#test-summary .test-states .' + queueName).each(function () {                
                var queueDetail = $(this);
                var bar = $('.bar .label', queueDetail)
                bar.animate({
                    'width': getTestQueueWidth(queueName) + '%'
                });
                
                bar.text(latestTestData.task_count_by_state[queueName]);
            });               
        }
    };
    
    var setUrlCount = function () {        
        $('#test-summary-url-count').text(latestTestData.remote_test_summary.url_count);
    };
    
    var setTaskCount = function () {        
        $('#test-summary-task-count').text(latestTestData.remote_test_summary.task_count);
    };
    
    var refreshTestSummary = function () {
        var now = new Date();
        
        var getProgressUrl = function () {            
            var taskIdsToGetProgressFor = [];
            var haslatestTestData = latestTestData.hasOwnProperty('tasksByUrl');
            
            for (var url in latestTestData.tasksByUrl) {
                var tasks = latestTestData.tasksByUrl[url];
                var taskCount = tasks.length;
                
                for (var taskIndex = 0; taskIndex < taskCount; taskIndex++) {
                    var task = tasks[taskIndex];
                    
                    if (!isFinishedState(task.state) && taskIdsToGetProgressFor.length <= limit) {
                        taskIdsToGetProgressFor.push(task.id);
                    }                    
                }
            }
            
            if (!haslatestTestData || taskIdsToGetProgressFor.length == 0) {
                return window.location.href + '?output=json&timestamp=' + now.getTime();
            }
            
            return window.location.href + '?output=json&timestamp=' + now.getTime() + '&taskIds=' + taskIdsToGetProgressFor.join(',');
            
        };
        
        jQuery.ajax({
            complete:function (request, textStatus) {
                //console.log('complete', request, textStatus);
            },
            dataType:'json',
            error:function (request, textStatus, errorThrown) {
                console.log('error', request, textStatus, request.getAllResponseHeaders());
            },
            statusCode: {
                403: function () {
                    console.log('403');
                },
                500: function () {
                    console.log('500');
                }
            },
            success: function (data, textStatus, request) {
                if (data.this_url != window.location.href) {
                    window.location.href = data.this_url;
                    return;
                }
                
                latestTestData = data;
                
                setCompletionPercentValue();                
                setCompletionPercentStateLabel();
                setCompletionPercentStateIcon();
                setTestQueues();
                setUrlCount();               
                setTaskCount();
                
                window.setTimeout(function () {
                    refreshTestSummary(10);
                }, 1000);
            },
            url:getProgressUrl()
        });
    };
    
    this.initialise = function () {
        refreshTestSummary();
    };
};

application.progress.taskController = function () {    
    
    var finishedStates = [
        'completed',
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached'        
    ];
    
    var incompleteStates = [
        'queued',
        'queued-for-assignment',
        'in-progress'
    ]; 
    
    var stateIconMap = {
        'queued-for-assignment':'icon-cog',
        'queued': 'icon-cog',
        'in-progress': 'icon-cogs',
        'completed': 'icon-bar-chart',
        'failed': 'icon-ban-circle',
        'failed-no-retry-available': 'icon-ban-circle',
        'failed-retry-available': 'icon-ban-circle',
        'failed-retry-limit-reached': 'icon-ban-circle'
    };
    
    var pageLength = 100;
    
    var taskIds = null;
    var tasks = null;
    
    var urlListContainer = null;
    var tabList = null;
    
    var getUrlCount = function () {
        return $('#test-summary-url-count').text();
    };
    
    var getTaskCount = function () {
        return $('#test-summary-task-count').text();
    };    
    
    var getTaskIdsUrl = function () {
        return window.location.href.replace('/progress/', '/tasks/ids/');
    };
    
    var getTasksUrl = function () {        
        return window.location.href.replace('/progress/', '/tasks/');
    };
    
    var getTaskIds = function () {
        if (taskIds === null) {
            jQuery.ajax({
                complete:function (request, textStatus) {
                    //console.log('complete', request, textStatus);
                },
                dataType:'json',
                error:function (request, textStatus, errorThrown) {
                    console.log('error', request, textStatus, request.getAllResponseHeaders());
                },
                statusCode: {
                    403: function () {
                        console.log('403');
                    },
                    500: function () {
                        console.log('500');
                    }
                },
                success: function (data, textStatus, request) {
                    if (data.length > 0) {
                        taskIds = data;
                    }
                },
                url:getTaskIdsUrl()
            });
            
            return null;
        }
        
        return taskIds;
    };
    
    var isTaskIdValid = function (taskId) {        
        var taskIdsLength = getTaskIds().length;
        
        for (var taskIdIndex = 0; taskIdIndex < taskIdsLength; taskIdIndex++) {
            if (getTaskIds()[taskIdIndex] == taskId) {
                return true;
            }
        }
        
        return false;
    };
    
    var hasTaskForIds = function (taskIds) {
        var taskIdsLength = taskIds.length;        
        var result = {};
        var taskId;
        
        for (var taskIdIndex = 0; taskIdIndex < taskIdsLength; taskIdIndex++) {
            taskId = taskIds[taskIdIndex];
            
            if (isTaskIdValid(taskId)) {
                result[taskId] = (!(tasks === null || tasks[taskId] === undefined)); 
            }                       
        }
        
        return result;
    };
    
    var isFinished = function (task) {
        for (var stateIndex = 0; stateIndex < finishedStates.length; stateIndex++) {
            if (task.state == finishedStates[stateIndex]) {
                return true;
            }
        }
        
        return false;
    };
    
    var getTasks = function (taskIds) {
        var hasTasks = hasTaskForIds(taskIds);
        var tasksToRetrieve = [];
        var taskId;
        var taskUpdateCount = 0;
        var taskUpdateLimit = 10;
        
        for (taskId in hasTasks) {
            if (hasTasks.hasOwnProperty(taskId)) {
                if (hasTasks[taskId] === false) {
                    tasksToRetrieve.push(taskId);
                } else {
                    if (!isFinished(tasks[taskId]) && taskUpdateCount <= taskUpdateLimit) {
                        tasksToRetrieve.push(taskId);
                        taskUpdateCount++;
                    }
                }
            }
        }
        
        if (tasksToRetrieve.length) {
            jQuery.ajax({
                type:'POST',
                data:{
                    'taskIds':tasksToRetrieve.join(',')
                },
                complete:function (request, textStatus) {
                    //console.log('complete', request, textStatus);
                },
                dataType:'json',
                error:function (request, textStatus, errorThrown) {
                    console.log('error', request, textStatus, request.getAllResponseHeaders());
                },
                statusCode: {
                    403: function () {
                        console.log('403');
                    },
                    500: function () {
                        console.log('500');
                    }
                },
                success: function (data, textStatus, request) {
                    for (var taskId in data) {
                        if (data.hasOwnProperty(taskId)) {
                            if (tasks === null) {
                                tasks = {};
                            }
                            
                            tasks[taskId] = data[taskId];
                        }
                    }
                },
                url:getTasksUrl()
            });
        }
        
        for (taskId in hasTasks) {
            if (hasTasks.hasOwnProperty(taskId)) {
                if (hasTasks[taskId] === false) {
                    return null;
                }
            }
        }
        
        var returnTasks = {};
        for (var taskIdIndex = 0; taskIdIndex < taskIds.length; taskIdIndex++) {
            taskId = taskIds[taskIdIndex];
            returnTasks[taskId] = tasks[taskId];
        }
        
        return returnTasks;
    };    
    
    var getTaskListContainer = function () {
        if (urlListContainer === null) {
            urlListContainer = $('#task-list-container');
        }
        
        return urlListContainer;
    };
    
    var buildTabList = function () {
        console.log("cp02:buildTabList");
    };
    
    var getTabList = function () {
        if (tabList === null) {
            buildTabList();
            
            //tabList = $('#tab-list')
        }
    };
    
    var getTaskCollection = function (offset) {               
        if (offset === undefined) {
            offset = 0;
        }
        
        var taskIds = getTaskIds(offset, offset + pageLength - 1);        
        var tasks = getTasks(taskIds);
        
        if (tasks === null) {
            return null;
        }
        
        return tasks;
    };
    
    var getTaskListIdentifier = function (tasks) {        
        var taskIds = [];        
        for (var taskId in tasks) {
            if (tasks.hasOwnProperty(taskId)) {
                taskIds.push(taskId);
            }
        }
        
        return 'tasks-' + taskIds.join('-');
    };
    
    var getTaskList = function (offset) {        
        var tasks = getTaskCollection(offset);
        if (tasks === null) {
            return null;
        }
        
        var taskListIdentifier = getTaskListIdentifier(tasks);        
        if ($('.' + taskListIdentifier, getTaskListContainer()).length === 0) {
            getTaskListContainer().append('<ul class="tasks '+taskListIdentifier+'" />');
        }
        
        return {
            'list':$('.' + taskListIdentifier, getTaskListContainer()),
            'tasks':tasks
        }; 
    };
    
    var setTaskListItemState = function (taskListItem, task) {
        var stateIndex = 0;
        for (stateIndex = 0; stateIndex < incompleteStates.length; stateIndex++) {
            taskListItem.removeClass(incompleteStates[stateIndex]);
        }
        
        for (stateIndex = 0; stateIndex < finishedStates.length; stateIndex++) {
            taskListItem.removeClass(finishedStates[stateIndex]);
        }  
        
        taskListItem.addClass(task.state);
    };
    
    
    var buildTaskListItem = function (taskListItem, task) {
        taskListItem.html('');
        
        setTaskListItemState(taskListItem, task);

        taskListItem.append('<span class="url">'+task.url+'</span>');
        taskListItem.append('<div class="meta"><span class="state"><i class="'+stateIconMap[task.state]+'"></i></span><span class="type">'+task.type+'</span></div>');       
    };       

    
    var updateTaskListItem = function (taskListItem, task) {
        buildTaskListItem(taskListItem, task);        
    };
    
    var getTaskListItem = function (taskList, task) {        
        var taskIdentifier = 'task' + task.task_id;
        
        if ($('#' + taskIdentifier, taskList).length === 0) {
            taskList.append('<li class="task" id="'+taskIdentifier+'" />');
        }   
        
        return $('#' + taskIdentifier, taskList);
    };
    
    var updateTaskList = function (taskList, tasks) {        
        for (var taskId in tasks) {
            if (tasks.hasOwnProperty(taskId)) {    
                updateTaskListItem(getTaskListItem(taskList, tasks[taskId]), tasks[taskId]);
            }
        }         
    };
    
    var refreshTaskList = function () {
        var taskList = getTaskList();
        if (taskList === null) {
            return;
        }

        updateTaskList(taskList.list, taskList.tasks);      
    };
    
    var initialise = function () {        
        if (getUrlCount() === 0 || getTaskCount() === 0 || getTaskIds() === null) {
            window.setTimeout(function () {
                initialise();
            }, 1000);
            
            return;
        }
        
        if (getUrlCount() <= pageLength) {
            refreshTaskList();
            window.setInterval(function () {
                refreshTaskList();
            }, 2000)            
        }
        
        //getTabList();
        
        //console.log(getTaskIds());
    };        

    
    this.initialise = initialise;    
};


application.testList = {};

application.testList.list = function () {
    var testList;
    
    this.initialise = function (documentTestList) {
        testList = documentTestList.clone();
        documentTestList.remove();
    };   
    
    this.get = function (identifier) {
        var specificTestList = testList.clone();
        
        $('li.url', specificTestList).each(function () {
            var url = $(this);
            
            $('.task', url).each(function () {
                var task = $(this);
                
                switch (identifier) {
                    case '#all':
                        break;

                    case '#tests-with-errors':
                        if (!task.hasClass('failed')) {
                            task.remove();
                        }
                        break; 

                    case '#tests-without-errors':
                        if (!task.hasClass('passed') || (task.hasClass('cancelled') || task.hasClass('awaiting-cancellation'))) {
                            task.remove();
                        }
                        break;

                    case '#cancelled-tests':
                        if (!task.hasClass('cancelled')) {
                            task.remove();
                        }
                        break; 
                }
            });
            
            if ($('.task', url).length === 0) {
                url.remove();
            }
        });
        
        return specificTestList;
    };
};

application.testList.controller = function () {   
    var testList;
    var getTestList = function () {
        if (testList == undefined) {
            testList = new application.testList.list();            
        }
        
        return testList;
    };
    
    this.getTestList = getTestList;
    
    this.initialise = function () {
        getTestList().initialise($('#test-list .urls'));
    };
};

application.resultsController = function () {
    var testListController = new application.testList.controller();
    testListController.initialise();
    
    $('#test-list .nav a').click(function () {
        var identifier = $(this).attr('href');
        if ($(identifier).html() == '') {
            $(identifier).html(testListController.getTestList().get(identifier));
        }
        
    });
    
    this.initialise = function () {
        $('#test-list .nav a[href=#tests-with-errors]').click();
    };
};

application.pages = {
    '/*':{
        'initialise':function () {
            if ($('body.app-progress').length > 0) {                
                testProgressController = new application.progress.testController();
                testProgressController.initialise();
                
                taskProgressController = new application.progress.taskController();
                taskProgressController.initialise();
            }
            
            
            if ($('body.app-results').length > 0) {                
                resultsController = new application.resultsController();
                resultsController.initialise();
            }            
        }         
    },
    '/':{
        'initialise':function () {
        }
    }    
};

var applicationController = function () {
    var getPagePath = function () {
        var pagePath = window.location.pathname;
        if (pagePath.substr(pagePath.length - 1, 1)  == '/') {
            pagePath = pagePath.substr(0, pagePath.length - 1);
        }        
        
        return pagePath;                
    };
    
    var getPagePathParts = function () {
        return getPagePath().split('/');
    };
    
    var getPageInitialisationPaths = function () {
        var pageInitialisationPaths = [];
        var pagePathParts = getPagePathParts();
        
        if (pagePathParts.length === 1) {
            return [
                '/',
                '/*'
            ];
        }
        
        var currentPath = '';
        var isFirstPath = true;
        
        while (pagePathParts.length > 0) {
            currentPath = pagePathParts.join('/');            
            if (!isFirstPath) {
                currentPath += '/*';
            }
            
            pageInitialisationPaths.push(currentPath);            

            pagePathParts = pagePathParts.slice(0, pagePathParts.length - 1);
            isFirstPath = false;
        }
        
        return pageInitialisationPaths;
    };    
    
    var getInitialisationMethods = function () {
        var pageInitialisationPaths = getPageInitialisationPaths();
        var initialisationMethods = [];
        
        for (var initialisationPathIndex = 0; initialisationPathIndex < pageInitialisationPaths.length; initialisationPathIndex++) {            
            if (typeof application.pages[pageInitialisationPaths[initialisationPathIndex]] == 'object') {
                if (typeof application.pages[pageInitialisationPaths[initialisationPathIndex]]['initialise'] == 'function') {
                    initialisationMethods.push(application.pages[pageInitialisationPaths[initialisationPathIndex]]['initialise']);
                }
            }
        }
        
        return initialisationMethods;        
    };    
    
    var initialise = function () {
        var initialisationMethods = getInitialisationMethods();        
        for (var initialisationMethodIndex = 0; initialisationMethodIndex < initialisationMethods.length; initialisationMethodIndex++) {
            initialisationMethods[initialisationMethodIndex]();
        }
    };    
    
    this.initialise = initialise;
};


$(document).ready(function() {
    var app = new applicationController();
    app.initialise();
});