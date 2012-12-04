var application = {};

application.progress = {};
application.progress.testController = function () {    
    var latestTestData = {};
    
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
        var queues = ['queued', 'in_progress', 'completed', 'failed', 'skipped'];
        
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
            return window.location.href + '?output=json&timestamp=' + now.getTime();            
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
                }, 3000);
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
        'failed',
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached',
        'skipped'
    ];
    
    var failedStates = [
        'failed',
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
        'failed-http-retrieval-redirect-loop': 'icon-refresh',
        'failed-no-retry-available': 'icon-ban-circle',
        'failed-retry-available': 'icon-ban-circle',
        'failed-retry-limit-reached': 'icon-ban-circle',
        'skipped': 'icon-random'
    };
    
    var pageLength = 100;
    var tabLimit = 9;
    
    var taskIds = null;
    var tasks = null;
    
    var taskListContainer = null;
    var tabList = null;
    
    var taskOutputController = null;
    
    var getUrlCount = function () {
        return $('#test-summary-url-count').text();
    };
    
    var getTaskCount = function () {
        return parseInt($('#test-summary-task-count').text(), 10);
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
    
    var getTasks = function (taskIds, callback) {        
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
                    
                    var returnTasks = {};
                    for (var taskIdIndex = 0; taskIdIndex < taskIds.length; taskIdIndex++) {
                        taskId = taskIds[taskIdIndex];
                        returnTasks[taskId] = tasks[taskId];
                    }
                    
                    callback(returnTasks);
                },
                url:getTasksUrl()
            });
        }
    };    
    
    var getTaskListContainer = function () {
        if (taskListContainer === null) {
            taskListContainer = $('#task-list-container');
        }
        
        return taskListContainer;
    };
    
    var getTabCount = function () {
        return Math.ceil(getTaskCount() / pageLength);
    };
    
    var getTabLabel = function (tabIndex) {
        var start = (pageLength * tabIndex) + 1;
        var end = start + pageLength - 1;
        
        if (end > getTaskCount()) {
            end = getTaskCount();
        }
        
        return start + ' &hellip; ' + end;
    };
    
    var getNextVisibleTabIndex = function (direction) {
        var nextVisibleTabIndex = 0;
        
        if (direction == 'right') {
            $('.tab', tabList).each(function (index) {
                if ($(this).hasClass('tab-visible')) {
                    nextVisibleTabIndex = index + 1;
                }
            });            
        } else {
            $($('.tab', tabList).get().reverse()).each(function(index) {                
                if ($(this).hasClass('tab-visible')) {
                    nextVisibleTabIndex = index + 1;
                }
            }); 
        }   
        
        return nextVisibleTabIndex;
    };
    
    var buildTabList = function () {
        tabList = $('<ul id="tab-list" class="nav nav-tabs pull-left" />');
        getTaskListContainer().append(tabList);
        
        var tabCount = getTabCount();        
        for (var tabIndex = 0; tabIndex < tabCount; tabIndex++) {
            (function (tabIndex) {
                tabList.append($('<li class="tab"><a href="#pane'+tabIndex+'" data-toggle="tab">'+getTabLabel(tabIndex)+'</a></li>').click(function () {                    
                    var parent = $('#pane'+tabIndex);
                    var getTaskListCallback = function (taskList) {                        
                        updateTaskList(taskList.list, taskList.tasks, function () {                            
                            if (parent.is('.active')) {
                                window.setTimeout(function () {
                                    getTaskList(tabIndex * 100, parent, getTaskListCallback);
                                }, 3000);                                   
                            }                            

                            $('.spinner', parent).remove();
                            parent.removeClass('pane-empty');
                        });                        
                    };

                    getTaskList(tabIndex * 100, parent, getTaskListCallback);
                }));                
            })(tabIndex);
        }
        
        if (tabCount > tabLimit) {
            tabList.addClass('constrained');
            
            $('.tab', tabList).each(function (index) {
                if (index >= tabLimit) {
                    $(this).addClass('tab-hidden');
                } else {
                    $(this).addClass('tab-visible');
                }
            });            
           
            tabList.before(
                $('<span class="tab-list-control tab-list-previous pull-left">&lsaquo;</span>').click(function () {
                    var nextVisibleTabIndex = getNextVisibleTabIndex('left');                    
                    if ((getTabCount() - nextVisibleTabIndex) < tabLimit) {
                        nextVisibleTabIndex = getTabCount() - tabLimit;
                    }

                    $($('.tab', tabList).get().reverse()).each(function(index) {
                        var tab = $(this);                        
                        tab.removeClass('tab-visible').removeClass('tab-hidden');
                        
                        if (index >= nextVisibleTabIndex && index < nextVisibleTabIndex + tabLimit) {
                            tab.addClass('tab-visible');
                        } else {
                            tab.addClass('tab-hidden');
                        }
                        
                        if (index == nextVisibleTabIndex) {
                            tab.click().addClass('active');
                            $('#pane' + index).addClass('active');
                        } else {
                            tab.removeClass('active');
                            $('#pane' + index).removeClass('active');
                        }   
                    }); 
                })
            ).after(
                $('<span class="tab-list-control tab-list-previous pull-left">&rsaquo;</span>').click(function () {
                    var nextVisibleTabIndex = getNextVisibleTabIndex('right');
                    if ((getTabCount() - nextVisibleTabIndex) < tabLimit) {
                        nextVisibleTabIndex = getTabCount() - tabLimit;
                    }
                    
                    $('.tab', tabList).each(function (index) {
                        var tab = $(this);                     
                        
                        tab.removeClass('tab-visible').removeClass('tab-hidden');
                        
                        if (index >= nextVisibleTabIndex && index < nextVisibleTabIndex + tabLimit) {
                            tab.addClass('tab-visible');
                        } else {
                            tab.addClass('tab-hidden');
                        }
                        
                        if (index == nextVisibleTabIndex) {
                            tab.click().addClass('active');
                            $('#pane' + index).addClass('active');
                        } else {
                            tab.removeClass('active');
                            $('#pane' + index).removeClass('active');
                        }                        
                    });                    
                })
            );
        }
    };
    
    var buildTabContent = function () {
        var tabContent = $('<div class="tab-content" />');        
        getTaskListContainer().append(tabContent);
        
        var tabCount = getTabCount();        
        for (var tabIndex = 0; tabIndex < tabCount; tabIndex++) {
            (function (tabIndex) {
                var pane = $('<div class="tab-pane pane-empty" id="pane'+tabIndex+'"><img class="spinner" src="/bundles/simplytestablewebclient/images/spinner.gif" /></div>');
                if (tabIndex === 0) {
                    pane.addClass('active');
                }
                
                tabContent.append(pane);           
            })(tabIndex);
        }
        
        tabContent.before('<div class="clearfix" style="width:100%;" />');
    };
    
    var getTabList = function () {
        if (tabList === null) {
            buildTabList();
            buildTabContent();
        }
       
        return tabList;
    };
    
    var getTaskCollection = function (offset, callback) {
        if (offset === undefined) {
            offset = 0;
        }
        
        var taskIds = getTaskIds().slice(offset, offset + pageLength);                        
        var tasks = getTasks(taskIds, function (tasks) {
            callback(tasks);
        });
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
    
    var getTaskList = function (offset, parent, callback) {        
        getTaskCollection(offset, function (tasks) {
            var taskListIdentifier = getTaskListIdentifier(tasks);        
            if ($('.' + taskListIdentifier, parent).length === 0) {            
                parent.append('<ul class="tasks '+taskListIdentifier+'" />');
            }           
            
            callback({
                'list':$('.' + taskListIdentifier, getTaskListContainer()),
                'tasks':tasks
            });
        });
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
    
    var isTaskListItemFailed = function (taskListItem) {
        for (var stateIndex = 0; stateIndex < failedStates.length; stateIndex++) {
            if (taskListItem.is('.' + failedStates[stateIndex])) {
                return true;
            }
        }
        
        return false;
    };
    
    
    var isFailedDueToRedirectLoop = function (task) {
        if (task.output == undefined) {
            return false;
        }
        
        if (task.output.content == undefined) {
            return false;
        }        
        
        var outputParser = new taskOutputController.outputParser();
        var outputResult = outputParser.getResults(task.output);

        if (outputResult.hasErrors() === false) {
            return false;
        }

        var errors = outputResult.getErrors();
        for (var errorIndex = 0; errorIndex < errors.length; errorIndex++) {
            if (errors[errorIndex].getClass() == 'http-retrieval-redirect-loop') {
                return true;
            }
        }

        return false;
    };
        
    
    var buildTaskSetListItem = function (taskSetListItem, tasks) {
        if ($('.url', taskSetListItem).length === 0) {
            taskSetListItem.append('<span class="url" />');
        }
        
        $('.url', taskSetListItem).html(tasks[0]['url']);

        for (var taskIndex = 0; taskIndex < tasks.length; taskIndex++) {
            updateTaskListItem(getTaskListItem(taskSetListItem, tasks[taskIndex]), tasks[taskIndex]);
        }
        
        //console.log(tasks);
        
        //var taskThings = $('.task', taskSetListItem);
        //console.log(taskSetListItem, taskThings.length);
        
//        if ((taskListItem.is('.completed') || isTaskListItemFailed(taskListItem)) && isFinished(task)) {
//            return;
//        }

        
        
        //taskListItem.html('');        
        
//        setTaskListItemState(taskListItem, task);
        
//        var stateIconIndex = task.state;
//        if (isFailedDueToRedirectLoop(task)) {
//            stateIconIndex = 'failed-http-retrieval-redirect-loop';
//        }
//
//        taskListItem.append('<span class="url">'+task.url+'</span>');
//        taskListItem.append('<div class="meta"><span class="state"><i class="'+stateIconMap[stateIconIndex]+'"></i></span><span class="type">'+task.type+'</span></div>');
//
//        if (isFinished(task)) {
//            var outputParser = new taskOutputController.outputParser();
//            var outputResult = outputParser.getResults(task.output);
//            
//            var outputIndicator;
//            
//            if (task.state == 'skipped') {
//                
//            } else {
//                if (outputResult.hasErrors()) {                
//                    outputIndicator = '<a href="'+(window.location.href.replace('/progress/', '/'+task.task_id+'/results/'))+'" class="output-indicator label label-important">'+outputResult.getErrorCount()+' error'+(outputResult.getErrorCount() == 1 ? '' : 's') +' <i class="icon-caret-down"></i></a>';
//                } else {
//                    outputIndicator = '<span class="output-indicator"><i class="icon-ok"></i></span>';
//                }                
//            }
//            
//
//             
//            $('.meta', taskListItem).append(outputIndicator);
//        }        
        
        //console.log(taskSetListItem);
    };
    
    
    /* NOT obsoleted by buildTaskSetListItem */
    var buildTaskListItem = function (taskListItem, task) {   
/**
<li class="task in-progress" id="task8552304">
    <span class="url">http://www.onebestway.com/2012/10/positioning.html</span>
    <div class="meta">
        <span class="state"><i class="icon-cogs"></i></span>
        <span class="type">HTML validation</span>
    </div>
</li>
 */        
        
        
//        console.log("cp01", taskListItem, task);
//        return;
        
        
        if ((taskListItem.is('.completed') || isTaskListItemFailed(taskListItem)) && isFinished(task)) {
            return;
        }
        
        taskListItem.html('');        
        setTaskListItemState(taskListItem, task);
        
        var stateIconIndex = task.state;
        if (isFailedDueToRedirectLoop(task)) {
            stateIconIndex = 'failed-http-retrieval-redirect-loop';
        }
//
//        taskListItem.append('<span class="url">'+task.url+'</span>');
//        taskListItem.append('<div class="meta"><span class="state"><i class="'+stateIconMap[stateIconIndex]+'"></i></span><span class="type">'+task.type+'</span></div>');
        taskListItem.append('<span class="meta"><span class="state"><i class="'+stateIconMap[stateIconIndex]+'"></i></span><span class="type">'+task.type+'</span></span>');//        
//
        if (isFinished(task)) {
            var outputParser = new taskOutputController.outputParser();
            var outputResult = outputParser.getResults(task.output);
            
            var outputIndicator;
            
            if (task.state == 'skipped') {
                
            } else {
                if (outputResult.hasErrors()) {                
                    outputIndicator = '<a href="'+(window.location.href.replace('/progress/', '/'+task.task_id+'/results/'))+'" class="output-indicator label label-important">'+outputResult.getErrorCount()+' error'+(outputResult.getErrorCount() == 1 ? '' : 's') +' <i class="icon-caret-right"></i></a>';
                } else {
                    outputIndicator = '<span class="output-indicator"><i class="icon-ok"></i></span>';
                }                
            }
            
            $('.meta', taskListItem).append(outputIndicator);
        }
    };       

    
    var updateTaskSetListItem = function (taskSetListItem, tasks) {        
        buildTaskSetListItem(taskSetListItem, tasks);
    };

    
    /* NOT obsoleted by updateTaskSetListItem */
    var updateTaskListItem = function (taskListItem, task) {        
        buildTaskListItem(taskListItem, task);        
    };
    
    
    var getTaskSetListItem = function (taskList, taskSet) {        
        var taskSetIdentifier = 'taskSet';
        for (var taskSetIndex = 0; taskSetIndex < taskSet.length; taskSetIndex++) {
            taskSetIdentifier += '-' + taskSet[taskSetIndex]['id'];
        }

        if ($('#' + taskSetIdentifier, taskList).length === 0) {
            taskList.append('<li class="taskSet" id="'+taskSetIdentifier+'" />');
        }   
        
        return $('#' + taskSetIdentifier, taskList);
    };    
    
    var getTaskListItem = function (taskSetListItem, task) {                
        var taskIdentifier = 'task' + task.task_id;
        
        if ($('#' + taskIdentifier, taskSetListItem).length === 0) {
            taskSetListItem.append('<div class="task" id="'+taskIdentifier+'" />');
        }   
       
        return $('#' + taskIdentifier, taskSetListItem);
    };
    
    var getTasksGroupedByUrl = function (tasks) {
        var tasksGroupedByUrl = {};
        
        for (var taskId in tasks) {
            if (tasks.hasOwnProperty(taskId)) {    
                if (tasksGroupedByUrl[tasks[taskId]['url']] === undefined) {
                    tasksGroupedByUrl[tasks[taskId]['url']] = [];
                }

                tasksGroupedByUrl[tasks[taskId]['url']].push(tasks[taskId]);
            }            
        }
        
        return tasksGroupedByUrl;
    };
    
    var updateTaskList = function (taskList, tasks, callback) {
        var tasksGroupedByUrl = getTasksGroupedByUrl(tasks);

        for (var url in tasksGroupedByUrl) {
            //var thing = getTaskSetListItem(taskList, tasksGroupedByUrl[url]);
            updateTaskSetListItem(getTaskSetListItem(taskList, tasksGroupedByUrl[url]), tasksGroupedByUrl[url]);
            
//            
//            
            //updateTaskListItem(getTaskListItem(taskList, tasks[taskId]), tasks[taskId]);
//            
//            //console.log(url);
////            if (tasks.hasOwnProperty(taskId)) {    
////                updateTaskListItem(getTaskListItem(taskList, tasks[taskId]), tasks[taskId]);
////            }
        }

        //console.log(tasks, getTasksGroupedByUrl(tasks));
        //return;
        
//        for (var taskId in tasks) {
//            if (tasks.hasOwnProperty(taskId)) {    
//                updateTaskListItem(getTaskListItem(taskList, tasks[taskId]), tasks[taskId]);
//            }
//        }
        
        //return;
                
        callback();
    };
    
    var initialise = function () {        
        taskOutputController = new application.progress.taskOutputController();
        
        if (getUrlCount() === 0 || getTaskCount() === 0 || getTaskIds() === null) {
            window.setTimeout(function () {
                initialise();
            }, 1000);
            
            return;
        }
        
        if (getTaskCount() <= pageLength) {            
            var parent = getTaskListContainer();
            var getTaskListCallback = function (taskList) {                
                updateTaskList(taskList.list, taskList.tasks, function () {
                    window.setTimeout(function () {
                        getTaskList(0, parent, getTaskListCallback);
                    }, 1000);
                });
            };
            
            getTaskList(0, parent, getTaskListCallback);
            return;
        }
        
        $('.tab', getTabList()).first().click().addClass('active');
    };        

    
    this.initialise = initialise;    
};

application.progress.taskOutputController = function () {
    
    var error = {
        'abstract': function () {
            var message = '';
            var errorClass = '';

            var setMessage = function (newMessage) {
                message = newMessage;
            };

            var getMessage = function () {
                return message;
            };
            
            var setClass = function (newClass) {
                errorClass = newClass;
            };
            
            var getClass = function () {
                return errorClass;
            };

            var toString = function () {
                return getMessage();
            } ;           

            this.setMessage = setMessage;
            this.getMessage = getMessage;
            this.setClass = setClass;
            this.getClass = getClass;
            this.toString = toString;            
        },
        'HTML validation': function () {
            var lineNumber = 0;
            var columnNumber = 0;

            var setLineNumber = function (newLineNumber) {
                lineNumber = newLineNumber;
            };

            var getLineNumber = function () {
                return lineNumber;
            };

            var setColumnNumber = function (newColumnNumber) {
                columnNumber = newColumnNumber;
            };

            var getColumnNumber = function () {
                return columnNumber;
            };  

            var toString = function () {
                return this.getMessage() + ' at line ' + getLineNumber() + ', column ' + getColumnNumber();
            }

            this.setLineNumber = setLineNumber;
            this.getLineNumber = getLineNumber;
            this.setColumnNumber = setColumnNumber;
            this.getColumnNumber = getColumnNumber;
            this.toString = toString;            
        },
        'CSS validation': function () {
            var lineNumber = 0;
            var context = '';
            var ref = '';

            var setLineNumber = function (newLineNumber) {
                lineNumber = newLineNumber;
            };

            var getLineNumber = function () {
                return lineNumber;
            };
            
            var setRef = function (newRef) {
                ref = newRef;
            };
            
            var getRef = function () {
                return ref;
            };
            
            var setContext = function (newContext) {
                context = newContext;
            };
            
            var getContext = function () {
                return context;
            };            

            var toString = function () {
                return 'Css validation error as a string';
                //return this.getMessage() + ' at line ' + getLineNumber() + ', column ' + getColumnNumber();
            }

            this.setLineNumber = setLineNumber;
            this.getLineNumber = getLineNumber;
            this.setContext = setContext;
            this.getContext = getContext;
            this.setRef = setRef;
            this.getRef = getRef;
            this.toString = toString;            
        },
        'JS static analysis': function () {
            var lineNumber = 0;
            var columnNumber = 0;
            var context = '';
            var fragment = '';

            var setLineNumber = function (newLineNumber) {
                lineNumber = newLineNumber;
            };

            var getLineNumber = function () {
                return lineNumber;
            };
            
            var setColumnNumber = function (newColumnNumber) {
                columnNumber = newColumnNumber;
            };

            var getColumnNumber = function () {
                return columnNumber;
            };            
            
            var setFragment = function (newFragment) {
                fragment = newFragment;
            };
            
            var getFragment = function () {
                return fragment;
            };
            
            var setContext = function (newContext) {
                context = newContext;
            };
            
            var getContext = function () {
                return context;
            };            

            var toString = function () {
                return 'JS static analysis error as a string';
                //return this.getMessage() + ' at line ' + getLineNumber() + ', column ' + getColumnNumber();
            }

            this.setLineNumber = setLineNumber;
            this.getLineNumber = getLineNumber;
            this.setColumnNumber = setColumnNumber;
            this.getColumnNumber = getColumnNumber;            
            this.setContext = setContext;
            this.getContext = getContext;
            this.setFragment = setFragment;
            this.getFragment = getFragment;            
            this.toString = toString;            
        }        
    };
    
    error['HTML validation'].prototype = new error['abstract'];
    error['CSS validation'].prototype = new error['abstract'];
    error['JS static analysis'].prototype = new error['abstract'];
    
    var outputResult = function () {
        var errorCount = 0;
        var errors = [];

        var getErrorCount = function () {
            return errorCount;
        };

        var setErrors = function (newErrors) {
            errors = newErrors;
            errorCount = errors.length;
        };   

        var getErrors = function () {
            return errors;
        };  

        var hasErrors = function () {
            return getErrorCount() > 0;
        };

        this.getErrorCount = getErrorCount;
        this.setErrors = setErrors;
        this.getErrors = getErrors;
        this.hasErrors = hasErrors;
    };
    
    var outputParser = function () {
        
        var parsers = {
            'HTML validation': function (taskOutput) {
                var getMessages = function () {
                    if (taskOutput.content == undefined) {
                        return [];
                    }
                    
                    if (taskOutput.content.messages == undefined) {
                        return [];
                    }                    
                    
                    return taskOutput.content.messages;
                };

                // line_number":65,"column_number":12,"message":"No p element in scope but a p end tag seen.","messageid":"html5","type":"error"                            
                var messages = getMessages();
                var messageCount = messages.length;             
                var errors;

                var getErrors = function () {                
                    if (errors == undefined) {
                        errors = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == 'error') {
                                var currentError = new error['HTML validation'];
                                
                                currentError.setMessage(message.message);
                                currentError.setLineNumber(message.line_number);
                                currentError.setColumnNumber(message.column_number);
                                currentError.setClass(message['class']);

                                errors.push(currentError);                            
                            }
                        }
                    }

                    return errors;
                };

                this.getErrors = getErrors;
            },
            'CSS validation': function (taskOutput) {
                var getMessages = function () {
                    if (taskOutput.content == undefined) {
                        return [];
                    }
                    
                    if (taskOutput.content.messages == undefined) {
                        return [];
                    }                    
                    
                    return taskOutput.content.messages;
                };

                var messages = getMessages();
                var messageCount = messages.length;             
                var errors;

                var getErrors = function () {                
                    if (errors == undefined) {
                        errors = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == 'error') {
                                var currentError = new error['CSS validation'];
                                
                               currentError.setMessage(message.message);
                               currentError.setLineNumber(message.line_number);
                               currentError.setContext(message.context);
                               currentError.setRef(message.ref);

                                errors.push(currentError);                            
                            }
                        }
                    }

                    return errors;
                };

                this.getErrors = getErrors;
            },
            'JS static analysis': function (taskOutput) {
                var getMessages = function () {
                    if (taskOutput.content == undefined) {
                        return [];
                    }
                    
                    if (taskOutput.content.messages == undefined) {
                        return [];
                    }                    
                    
                    return taskOutput.content.messages;
                };

                var messages = getMessages();
                var messageCount = messages.length;             
                var errors;

                var getErrors = function () {                    
                    if (errors == undefined) {
                        errors = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == 'error') {
                                var currentError = new error['JS static analysis'];
                                
                                currentError.setMessage(message.message);
                                currentError.setLineNumber(message.line_number);
                                currentError.setColumnNumber(message.column_number);
                                currentError.setContext(message.context);
                                currentError.setFragment(message.fragment);

                                errors.push(currentError);                            
                            }
                        }
                    }

                    return errors;
                };

                this.getErrors = getErrors;
            }            
        };
        
        var getResults = function (taskOutput) {
            
            
            var results = new outputResult();
            var parser = new parsers[taskOutput.type](taskOutput);
            
            results.setErrors(parser.getErrors());
            
            return results;       
        } 
        
        this.getResults = getResults;
        
    };
    
    this.outputParser = outputParser;    
};



//application.testList = {};
//
//application.testList.list = function () {
//    var testList;
//    
//    this.initialise = function (documentTestList) {
//        testList = documentTestList.clone();
//        documentTestList.remove();
//    };   
//    
//    this.get = function (identifier) {
//        var specificTestList = testList.clone();
//        
//        $('li.url', specificTestList).each(function () {
//            var url = $(this);
//            
//            $('.task', url).each(function () {
//                var task = $(this);
//                
//                switch (identifier) {
//                    case '#all':
//                        break;
//
//                    case '#tests-with-errors':
//                        if (!task.hasClass('failed')) {
//                            task.remove();
//                        }
//                        break; 
//
//                    case '#tests-without-errors':
//                        if (!task.hasClass('passed') || (task.hasClass('cancelled') || task.hasClass('awaiting-cancellation'))) {
//                            task.remove();
//                        }
//                        break;
//
//                    case '#cancelled-tests':
//                        if (!task.hasClass('cancelled')) {
//                            task.remove();
//                        }
//                        break; 
//                }
//            });
//            
//            if ($('.task', url).length === 0) {
//                url.remove();
//            }
//        });
//        
//        return specificTestList;
//    };
//};
//
//application.testList.controller = function () {   
//    var testList;
//    var getTestList = function () {
//        if (testList == undefined) {
//            testList = new application.testList.list();            
//        }
//        
//        return testList;
//    };
//    
//    this.getTestList = getTestList;
//    
//    this.initialise = function () {
//        getTestList().initialise($('#test-list .urls'));
//    };
//};
//
//application.resultsController = function () {
//    var testListController = new application.testList.controller();
//    testListController.initialise();
//    
//    $('#test-list .nav a').click(function () {
//        var identifier = $(this).attr('href');
//        if ($(identifier).html() == '') {
//            $(identifier).html(testListController.getTestList().get(identifier));
//        }
//        
//    });
//    
//    this.initialise = function () {
//        $('#test-list .nav a[href=#tests-with-errors]').click();
//    };
//};

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
                //resultsController = new application.resultsController();
                //resultsController.initialise();
            } 
            
            if ($('body.content').length > 0) {                
                getTwitters('footer-tweet', { 
                    id: 'simplytestable', 
                    count: 1, 
                    enableLinks: true, 
                    ignoreReplies: true, 
                    clearContents: true,
                    template: '%text% <a class="time" href="http://twitter.com/%user_screen_name%/statuses/%id_str%/">%time%</a>',
                    callback: function () {                       
                        $('.tweet-container .tweet').html($('#footer-tweet').html()).animate({
                            'opacity':1
                        });
                    }
                });
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