var application = {};

application.common = {};
application.common.isLoggedIn = function () {
    return $('.sign-out-form').prop('tagName') === 'FORM';
};

application.common.form = {};
application.common.form.field = {};

application.common.form.field.select = function (field) {
    var oldVal = field.val();
    field.focus().val('').val(oldVal);    
};

application.common.form.field.isEmpty = function (field) {
    return jQuery.trim(field.val()) === '';   
};

application.common.form.field.hasError = function (field) {
    return $('[data-for=' + field.attr('id') + ']').length > 0;   
};

application.progress = {};

application.progress.taskController = function() {

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
        'queued-for-assignment': 'icon-cog',
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

    var getUrlCount = function() {
        return $('#test-summary-url-count').text();
    };

    var getTaskCount = function() {
        return parseInt($('#test-summary-task-count').text(), 10);
    };

    var getTaskIdsUrl = function() {
        return window.location.href.replace('/progress/', '/tasks/ids/');
    };

    var getTasksUrl = function() {
        return window.location.href.replace('/progress/', '/tasks/');
    };

    var getTaskIds = function() {
        if (taskIds === null) {
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

            return null;
        }

        return taskIds;
    };

    var isTaskIdValid = function(taskId) {
        var taskIdsLength = getTaskIds().length;

        for (var taskIdIndex = 0; taskIdIndex < taskIdsLength; taskIdIndex++) {
            if (getTaskIds()[taskIdIndex] == taskId) {
                return true;
            }
        }

        return false;
    };

    var hasTaskForIds = function(taskIds) {
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

    var isFinished = function(task) {
        for (var stateIndex = 0; stateIndex < finishedStates.length; stateIndex++) {
            if (task.state == finishedStates[stateIndex]) {
                return true;
            }
        }

        return false;
    };

    var getTasks = function(taskIds, callback) {
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
                type: 'POST',
                data: {
                    'taskIds': tasksToRetrieve.join(',')
                },
                dataType: 'json',
                error: function(request, textStatus, errorThrown) {
                },
                success: function(data, textStatus, request) {
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
                url: getTasksUrl()
            });
        }
    };

    var getTaskListContainer = function() {
        if (taskListContainer === null) {
            taskListContainer = $('#task-list-container');
        }

        return taskListContainer;
    };

    var getTabCount = function() {
        return Math.ceil(getTaskCount() / pageLength);
    };

    var getTabLabel = function(tabIndex) {
        var start = (pageLength * tabIndex) + 1;
        var end = start + pageLength - 1;

        if (end > getTaskCount()) {
            end = getTaskCount();
        }

        return start + ' &hellip; ' + end;
    };

    var getNextVisibleTabIndex = function(direction) {
        var nextVisibleTabIndex = 0;

        if (direction == 'right') {
            $('.tab', tabList).each(function(index) {
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

    var buildTabList = function() {
        tabList = $('<ul id="tab-list" class="nav nav-tabs pull-left" />');
        getTaskListContainer().append(tabList);

        var tabCount = getTabCount();
        for (var tabIndex = 0; tabIndex < tabCount; tabIndex++) {
            (function(tabIndex) {
                tabList.append($('<li class="tab"><a href="#pane' + tabIndex + '" data-toggle="tab">' + getTabLabel(tabIndex) + '</a></li>').click(function() {
                    var parent = $('#pane' + tabIndex);
                    var getTaskListCallback = function(taskList) {
                        updateTaskList(taskList.list, taskList.tasks, function() {
                            if (parent.is('.active')) {
                                window.setTimeout(function() {
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

            $('.tab', tabList).each(function(index) {
                if (index >= tabLimit) {
                    $(this).addClass('tab-hidden');
                } else {
                    $(this).addClass('tab-visible');
                }
            });

            tabList.before(
                    $('<span class="tab-list-control tab-list-previous pull-left">&lsaquo;</span>').click(function() {
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
                    $('<span class="tab-list-control tab-list-previous pull-left">&rsaquo;</span>').click(function() {
                var nextVisibleTabIndex = getNextVisibleTabIndex('right');
                if ((getTabCount() - nextVisibleTabIndex) < tabLimit) {
                    nextVisibleTabIndex = getTabCount() - tabLimit;
                }

                $('.tab', tabList).each(function(index) {
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

    var buildTabContent = function() {
        var tabContent = $('<div class="tab-content" />');
        getTaskListContainer().append(tabContent);

        var tabCount = getTabCount();
        for (var tabIndex = 0; tabIndex < tabCount; tabIndex++) {
            (function(tabIndex) {
                var pane = $('<div class="tab-pane pane-empty" id="pane' + tabIndex + '"><img class="spinner" src="/bundles/simplytestablewebclient/images/spinner.gif" /></div>');
                if (tabIndex === 0) {
                    pane.addClass('active');
                }

                tabContent.append(pane);
            })(tabIndex);
        }

        tabContent.before('<div class="clearfix" style="width:100%;" />');
    };

    var getTabList = function() {
        if (tabList === null) {
            buildTabList();
            buildTabContent();
        }

        return tabList;
    };

    var getTaskCollection = function(offset, callback) {
        if (offset === undefined) {
            offset = 0;
        }

        var taskIds = getTaskIds().slice(offset, offset + pageLength);
        var tasks = getTasks(taskIds, function(tasks) {
            callback(tasks);
        });
    };

    var getTaskListIdentifier = function(tasks) {
        var taskIds = [];
        for (var taskId in tasks) {
            if (tasks.hasOwnProperty(taskId)) {
                taskIds.push(taskId);
            }
        }

        return 'tasks-' + taskIds.join('-');
    };

    var getTaskList = function(offset, parent, callback) {
        getTaskCollection(offset, function(tasks) {
            var taskListIdentifier = getTaskListIdentifier(tasks);
            if ($('.' + taskListIdentifier, parent).length === 0) {
                parent.append('<ul class="tasks ' + taskListIdentifier + '" />');
            }

            callback({
                'list': $('.' + taskListIdentifier, getTaskListContainer()),
                'tasks': tasks
            });
        });
    };

    var setTaskListItemState = function(taskListItem, task) {
        var stateIndex = 0;
        for (stateIndex = 0; stateIndex < incompleteStates.length; stateIndex++) {
            taskListItem.removeClass(incompleteStates[stateIndex]);
        }

        for (stateIndex = 0; stateIndex < finishedStates.length; stateIndex++) {
            taskListItem.removeClass(finishedStates[stateIndex]);
        }

        taskListItem.addClass(task.state);
    };

    var isTaskListItemFailed = function(taskListItem) {
        for (var stateIndex = 0; stateIndex < failedStates.length; stateIndex++) {
            if (taskListItem.is('.' + failedStates[stateIndex])) {
                return true;
            }
        }

        return false;
    };


    var isFailedDueToRedirectLoop = function(task) {
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


    var buildTaskSetListItem = function(taskSetListItem, tasks) {
        if ($('.url', taskSetListItem).length === 0) {
            taskSetListItem.append($('<span class="url" />'));
        }

        $('.url', taskSetListItem).html(punycode.toUnicode(decodeURIComponent((tasks[0]['url']+'').replace(/\+/g, '%20'))));

        for (var taskIndex = 0; taskIndex < tasks.length; taskIndex++) {
            updateTaskListItem(getTaskListItem(taskSetListItem, tasks[taskIndex]), tasks[taskIndex]);
        }
        
        $('.url').prepend('<span class="fade" />');
    };


    var buildTaskListItem = function(taskListItem, task) {
        if ((taskListItem.is('.completed') || isTaskListItemFailed(taskListItem)) && isFinished(task)) {
            return;
        }

        taskListItem.html('');
        setTaskListItemState(taskListItem, task);

        var stateIconIndex = task.state;
        if (isFailedDueToRedirectLoop(task)) {
            stateIconIndex = 'failed-http-retrieval-redirect-loop';
        }

        taskListItem.append('<span class="meta"><span class="state"><i class="' + stateIconMap[stateIconIndex] + '"></i></span><span class="type">' + task.type + '</span></span>');//        

        if (isFinished(task)) {
            var outputParser = new taskOutputController.outputParser();
            var outputResult = outputParser.getResults(task.output);

            var outputIndicator;

            if (task.state == 'skipped') {

            } else {
                if (outputResult.hasErrors() && outputResult.hasWarnings()) {
                    outputIndicator = '<a href="' + (window.location.href.replace('/progress/', '/' + task.task_id + '/results/')) + '" class="output-indicator label label-important">' + outputResult.getErrorCount() + ' error' + (outputResult.getErrorCount() == 1 ? '' : 's') + ' and ' + outputResult.getWarningCount() + ' warning' + (outputResult.getWarningCount() == 1 ? '' : 's') + '<i class="icon-caret-right"></i></a>';
                } else if (!outputResult.hasErrors() && outputResult.hasWarnings()) {
                    outputIndicator = '<a href="' + (window.location.href.replace('/progress/', '/' + task.task_id + '/results/')) + '" class="output-indicator label label-info">' + outputResult.getWarningCount() + ' warning' + (outputResult.getWarningCount() == 1 ? '' : 's') + ' <i class="icon-caret-right"></i></a>';
                } else if (outputResult.hasErrors() && !outputResult.hasWarnings()) {
                    outputIndicator = '<a href="' + (window.location.href.replace('/progress/', '/' + task.task_id + '/results/')) + '" class="output-indicator label label-important">' + outputResult.getErrorCount() + ' error' + (outputResult.getErrorCount() == 1 ? '' : 's') + ' <i class="icon-caret-right"></i></a>';
                } else {
                    outputIndicator = '<span class="output-indicator"><i class="icon-ok"></i></span>';
                }
            }

            $('.meta', taskListItem).append(outputIndicator);
        }
    };


    var updateTaskSetListItem = function(taskSetListItem, tasks) {
        buildTaskSetListItem(taskSetListItem, tasks);
    };


    var updateTaskListItem = function(taskListItem, task) {
        buildTaskListItem(taskListItem, task);
    };


    var getTaskSetListItem = function(taskList, taskSet) {
        var taskSetIdentifier = 'taskSet';
        for (var taskSetIndex = 0; taskSetIndex < taskSet.length; taskSetIndex++) {
            taskSetIdentifier += '-' + taskSet[taskSetIndex]['id'];
        }

        if ($('#' + taskSetIdentifier, taskList).length === 0) {
            taskList.append('<li class="taskSet" id="' + taskSetIdentifier + '" />');
        }

        return $('#' + taskSetIdentifier, taskList);
    };

    var getTaskListItem = function(taskSetListItem, task) {
        var taskIdentifier = 'task' + task.task_id;

        if ($('#' + taskIdentifier, taskSetListItem).length === 0) {
            taskSetListItem.append('<div class="task" id="' + taskIdentifier + '" />');
        }

        return $('#' + taskIdentifier, taskSetListItem);
    };

    var getTasksGroupedByUrl = function(tasks) {
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

    var updateTaskList = function(taskList, tasks, callback) {
        var tasksGroupedByUrl = getTasksGroupedByUrl(tasks);

        for (var url in tasksGroupedByUrl) {
            updateTaskSetListItem(getTaskSetListItem(taskList, tasksGroupedByUrl[url]), tasksGroupedByUrl[url]);
        }

        callback();
    };

    var initialise = function() {
        taskOutputController = new application.progress.taskOutputController();

        if (getUrlCount() === 0 || getTaskCount() === 0 || getTaskIds() === null) {
            window.setTimeout(function() {
                initialise();
            }, 1000);

            return;
        }

        if (getTaskCount() <= pageLength) {
            var parent = getTaskListContainer();
            var getTaskListCallback = function(taskList) {
                updateTaskList(taskList.list, taskList.tasks, function() {
                    window.setTimeout(function() {
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

application.progress.taskOutputController = function() {

    var outputMessage = {
        'abstract': function() {
            var type = '';
            var message = '';
            var errorClass = '';

            var setClass = function(newClass) {
                errorClass = newClass;
            };

            var getClass = function() {
                return errorClass;
            };

            var setMessage = function(newMessage) {
                message = newMessage;
            };

            var getMessage = function() {
                return message;
            };

            var setType = function(newType) {
                type = newType;
            };

            var getType = function() {
                return type;
            };

            var isType = function(queriedType) {
                return getType() == queriedType;
            };

            var toString = function() {
                return getMessage();
            };

            this.toString = toString;

            this.setClass = setClass;
            this.getClass = getClass;
            this.setType = setType;
            this.getType = getType;
            this.isType = isType;
            this.setMessage = setMessage;
            this.getMessage = getMessage;
        },
        'HTML validation': function() {
            var lineNumber = 0;
            var columnNumber = 0;

            var setLineNumber = function(newLineNumber) {
                lineNumber = newLineNumber;
            };

            var getLineNumber = function() {
                return lineNumber;
            };

            var setColumnNumber = function(newColumnNumber) {
                columnNumber = newColumnNumber;
            };

            var getColumnNumber = function() {
                return columnNumber;
            };

            var toString = function() {
                return this.getMessage() + ' at line ' + getLineNumber() + ', column ' + getColumnNumber();
            };

            this.setLineNumber = setLineNumber;
            this.getLineNumber = getLineNumber;
            this.setColumnNumber = setColumnNumber;
            this.getColumnNumber = getColumnNumber;
            this.toString = toString;
        },
        'CSS validation': function() {
            var lineNumber = 0;
            var context = '';
            var ref = '';

            var setLineNumber = function(newLineNumber) {
                lineNumber = newLineNumber;
            };

            var getLineNumber = function() {
                return lineNumber;
            };

            var setRef = function(newRef) {
                ref = newRef;
            };

            var getRef = function() {
                return ref;
            };

            var setContext = function(newContext) {
                context = newContext;
            };

            var getContext = function() {
                return context;
            };

            var toString = function() {
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
        'JS static analysis': function() {
            var lineNumber = 0;
            var columnNumber = 0;
            var context = '';
            var fragment = '';

            var setLineNumber = function(newLineNumber) {
                lineNumber = newLineNumber;
            };

            var getLineNumber = function() {
                return lineNumber;
            };

            var setColumnNumber = function(newColumnNumber) {
                columnNumber = newColumnNumber;
            };

            var getColumnNumber = function() {
                return columnNumber;
            };

            var setFragment = function(newFragment) {
                fragment = newFragment;
            };

            var getFragment = function() {
                return fragment;
            };

            var setContext = function(newContext) {
                context = newContext;
            };

            var getContext = function() {
                return context;
            };

            var toString = function() {
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
        },
        'Link integrity': function() {
            var context;
            var url;
            
            var setContext = function (newContext) {
                context = newContext;
            };
            
            var getContext = function () {
                return context;
            };
            
            var setUrl = function (newUrl) {
                url = newUrl;
            };
            
            var getUrl = function () {
                return url;
            };

            var toString = function() {
                return this.getMessage();
            };

            this.setContext = setContext;
            this.getContext = getContext;
            this.setUrl = setUrl;
            this.getUrl = getUrl;
            this.toString = toString;
        },                
    };

    outputMessage['abstract'].prototype = new outputMessage['abstract'];
    outputMessage['HTML validation'].prototype = new outputMessage['abstract'];
    outputMessage['CSS validation'].prototype = new outputMessage['abstract'];
    outputMessage['JS static analysis'].prototype = new outputMessage['abstract'];
    outputMessage['Link integrity'].prototype = new outputMessage['abstract'];

    var outputResult = function() {
        var errorCount = 0;
        var warningCount = 0;
        var errors = [];
        var warnings = [];

        var getErrorCount = function() {
            return errorCount;
        };

        var getWarningCount = function() {
            return warningCount;
        };

        var setErrors = function(newErrors) {            
            errors = newErrors;
            errorCount = errors.length;
        };

        var getErrors = function() {
            return errors;
        };

        var setWarnings = function(newWarnings) {
            warnings = newWarnings;
            warningCount = warnings.length;
        }

        var getWarnings = function() {
            return warnings;
        };

        var hasErrors = function() {
            return getErrorCount() > 0;
        };

        var hasWarnings = function() {
            return getWarningCount() > 0;
        };

        var hasMessages = function() {
            return hasErrors() || hasWarnings();
        }

        this.getErrorCount = getErrorCount;
        this.setErrors = setErrors;
        this.getErrors = getErrors;
        this.hasErrors = hasErrors;
        this.getWarningCount = getWarningCount;
        this.setWarnings = setWarnings;
        this.getWarnings = getWarnings;
        this.hasWarnings = hasWarnings;
        this.hasMessages = hasMessages;
    };

    var outputParser = function() {

        var parsers = {
            'HTML validation': function(taskOutput) {
                var getMessages = function() {
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

                var getErrors = function() {
                    if (errors == undefined) {
                        errors = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == 'error') {
                                var currentError = new outputMessage['HTML validation'];

                                currentError.setMessage(message.message);
                                currentError.setLineNumber(message.line_number);
                                currentError.setColumnNumber(message.column_number);
                                currentError.setClass(message['class']);

                                currentError.setType('error');
                                errors.push(currentError);
                            }
                        }
                    }

                    return errors;
                };

                var getWarnings = function() {
                    return [];
                };

                this.getErrors = getErrors;
                this.getWarnings = getWarnings;
            },
            'CSS validation': function(taskOutput) {
                var getMessages = function() {
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
                var outputMessages = {};

                var getOutputMessagesOfType = function(type) {
                    if (outputMessages[type] == undefined) {
                        outputMessages[type] = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == type) {
                                var currentOutputMessage = new outputMessage['CSS validation'];
                                currentOutputMessage.setType(type);

                                currentOutputMessage.setMessage(message.message);
                                currentOutputMessage.setMessage(message.line_number);
                                currentOutputMessage.setContext(message.context);
                                currentOutputMessage.setRef(message.ref);

                                outputMessages[type].push(currentOutputMessage);
                            }
                        }
                    }



                    return outputMessages[type];
                };

                var getErrors = function() {
                    return getOutputMessagesOfType('error');
                };

                var getWarnings = function() {
                    return getOutputMessagesOfType('warning');
                };

                this.getErrors = getErrors;
                this.getWarnings = getWarnings;
            },
            'JS static analysis': function(taskOutput) {
                var getMessages = function() {
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

                var getErrors = function() {
                    if (errors == undefined) {
                        errors = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == 'error') {
                                if (message.message.indexOf('Too many errors. ') === -1 && message.message.indexOf('Stopping') === -1) {
                                    var currentError = new outputMessage['JS static analysis'];
                                    currentError.setType('error');

                                    currentError.setMessage(message.message);
                                    currentError.setLineNumber(message.line_number);
                                    currentError.setColumnNumber(message.column_number);
                                    currentError.setContext(message.context);
                                    currentError.setFragment(message.fragment);

                                    errors.push(currentError);                                    
                                }
                            }
                        }
                    }

                    return errors;
                };

                var getWarnings = function() {
                    return [];
                };

                this.getErrors = getErrors;
                this.getWarnings = getWarnings;
            },
            'Link integrity': function(taskOutput) {
                var getMessages = function() {
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

                var getErrors = function() {                    
                    if (errors == undefined) {
                        errors = [];

                        for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                            var message = messages[messageIndex];

                            if (message.type == 'error') {
                                var currentError = new outputMessage['Link integrity'];
                                
                                currentError.setContext(message.context);
                                currentError.setUrl(message.url);
                                currentError.setClass(message['class']);
                                currentError.setMessage(message.message);

                                currentError.setType('error');
                                errors.push(currentError);
                            }
                        }
                    }

                    return errors;
                };

                var getWarnings = function() {
                    return [];
                };

                this.getErrors = getErrors;
                this.getWarnings = getWarnings;
            },                    
        };

        var getResults = function(taskOutput) {            
            var results = new outputResult();
            var parser = new parsers[taskOutput.type](taskOutput);

            results.setErrors(parser.getErrors());
            results.setWarnings(parser.getWarnings());

            return results;
        }

        this.getResults = getResults;

    };

    this.outputParser = outputParser;
};