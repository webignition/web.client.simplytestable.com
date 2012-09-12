var application = {};

application.taskOutputController = function () {
    this.taskOutput = {};
};

application.taskOutputController.prototype.get = function (taskIds, successCallback) {
    var outputUrl = function () {
        return document.location.href.replace('/progress/', '') + '/' + taskIds.join(',') + '/results/collection/';
    };
    
    var setTaskOutput = function (taskId, data) {
        this.taskOutput[taskId] = data;
    };
    
    var getTaskOutput = function (taskId) {
        return this.taskOutput[taskId];
    };
    
    var scope = this;
    
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
            for (var taskId in data) {
                if (data.hasOwnProperty(taskId)) {
                    setTaskOutput.call(scope, taskId, data[taskId]);                   
                    successCallback.call(scope, {
                        'taskId':taskId,
                        'output':getTaskOutput.call(scope, taskId)
                    });
                }
            }
        },
        url:outputUrl()
    });
}

application.taskOutputController.prototype.outputResult = function () {
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

application.taskOutputController.prototype.outputResult.prototype.error = {};

application.taskOutputController.prototype.outputResult.prototype.error['abstract'] = function () {
    var message = '';
    
    var setMessage = function (newMessage) {
        message = newMessage;
    }
    
    var getMessage = function () {
        return message;
    }
    
    var toString = function () {
        return getMessage();
    }
    
    this.setMessage = setMessage;
    this.getMessage = getMessage;
    this.toString = toString;
};

application.taskOutputController.prototype.outputResult.prototype.error['HTML validation'] = function () {
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
};

application.taskOutputController.prototype.outputResult.prototype.error['HTML validation'].prototype = new application.taskOutputController.prototype.outputResult.prototype.error['abstract']

application.taskOutputController.prototype.outputParser = {
    parsers: {
        'HTML validation': function (taskOutput) {            
            // line_number":65,"column_number":12,"message":"No p element in scope but a p end tag seen.","messageid":"html5","type":"error"            
            var messages = taskOutput.content.messages;
            var messageCount = messages.length;             
            var errors;
            
            var getErrors = function () {                
                if (errors == undefined) {
                    errors = [];
                    
                    for (var messageIndex = 0; messageIndex < messageCount; messageIndex++) {
                        var message = messages[messageIndex];
                        
                        if (message.type == 'error') {
                            var error = new this.prototype.outputResult.prototype.error['HTML validation']();
                            error.setMessage(message.message);
                            error.setLineNumber(message.line_number);
                            error.setColumnNumber(message.column_number);
                            
                            errors.push(error);                            
                        }
                    }
                }
                
                return errors;
            };
            
            this.getErrors = getErrors;
        }
    },
    
    getResults: function (taskOutput) {        
        var scope = this;
        
        var results = new this.prototype.outputResult();
        var parser = new this.prototype.outputParser.parsers[taskOutput.type](taskOutput);

        results.setErrors(parser.getErrors.call(scope));
        
        return results;        
    }
};

application.taskOutputController.prototype.update = function (tasksToRetrieveOutputFor) {        
    var getTaskIds = function (tasksToRetrieveOutputFor) {
        var taskIds = [];
        
        for (var taskId in tasksToRetrieveOutputFor) {
            if (tasksToRetrieveOutputFor.hasOwnProperty(taskId)) {
                taskIds.push(taskId);
            }
        }
        
        return taskIds;
    };    
    
    this.get(getTaskIds(tasksToRetrieveOutputFor), function (taskOutput) {        
        var outputResult = this.outputParser.getResults.call(application.taskOutputController, taskOutput.output);
        var inPageTask = tasksToRetrieveOutputFor[taskOutput.taskId];
      
        if (outputResult.hasErrors()) {
            var getErrorWording = function (errorCount) {
                if (errorCount === 1) {
                    return 'error';
                }
                
                return 'errors'
            };
            
            inPageTask.append($(' <span class="label label-important">' + outputResult.getErrorCount() + ' ' + getErrorWording(outputResult.getErrorCount()) + '</span>'));
        } else {
            inPageTask.append($(' <i class="icon-ok"></i>'));
        }     
    });
};

application.testProgressController = function () {
    var taskOutputController = new application.taskOutputController();
    var latestData = {};

    
    var setCompletionPercentValue = function () {
        var completionPercentValue = $('#completion-percent-value');
        
        if (completionPercentValue.text() != latestData.completionPercent) {
            completionPercentValue.text(latestData.completionPercent);
            
            if ($('html.csstransitions').length > 0) {
                $('#completion-percent-bar').css({
                    'width':latestData.completionPercent + '%'
                });                
            } else {
                $('#completion-percent-bar').animate({
                    'width':latestData.completionPercent + '%'
                });                
            }
        }        
    };
    
    var setCompletionPercentStateLabel = function () {
        var completionPercentStateLabel = $('#completion-percent-state-label');
        if (completionPercentStateLabel.text() != latestData.state_label) {
            completionPercentStateLabel.text(latestData.state_label);
        }         
    };
    
    var setCompletionPercentStateIcon = function () {        
        $('#completion-percent-state-icon').attr('class', '').addClass(latestData.state_icon);
     
    };    
    
    var getTestQueueWidth = function (queueName) {        
        if (latestData.taskCountByState[queueName] == 0) {
            return 1;
        }
        
        return (latestData.taskCountByState[queueName] / latestData.test.tasks.length) * 100;
    };
    
    var setTestQueues = function () { 
        var queues = ['queued', 'in-progress', 'completed'];
        
        for (var queueNameIndex = 0; queueNameIndex < queues.length; queueNameIndex++) {
            var queueName = queues[queueNameIndex];

            $('#test-summary .test-states .' + queueName).each(function () {                
                var queueDetail = $(this);
                var bar = $('.bar .label', queueDetail)
                bar.animate({
                    'width': getTestQueueWidth(queueName) + '%'
                });
                
                bar.text(latestData.taskCountByState[queueName]);
            });               
        }
    };
    
    var setUrlCount = function () {
        $('#url-list-url-count').text(latestData.urls.length);
    };
    
    var setTaskCount = function () {        
        $('#url-list-task-count').text(latestData.test.tasks.length);
    };   
    
    var updateUrls = function () { 
        var getUrlList = function () {
            if ($('#url-list ul.urls').length === 0) {
                $('#url-list').append('<ul class="urls" />');
            }
            
            return $('#url-list ul.urls');
        };
        
        var inPageUrls = $('span.url', getUrlList());
        
        var findInPageUrl = function (url) {
            var inPageUrl = false;
            
            inPageUrls.each(function () {
                var urlElement = $(this);
                
                if (urlElement.text() == url) {
                    inPageUrl = urlElement.parent();
                    return;
                }
            });
            
            return inPageUrl;
        };
        
        var findLatestDataTasks = function (url) {
            var latestDataTasks = [];
            
            for (var taskIndex = 0; taskIndex < latestData.test.tasks.length; taskIndex++) {
                var task = latestData.test.tasks[taskIndex];
                if (task.url == url) {
                    latestDataTasks.push(task);
                }
            }
            
            return latestDataTasks;
        };
        
        var findInPageTask = function (latestDataTask, inPageUrl) {
            var inPageTask = false;
            
            $('ul.tasks li', inPageUrl).each(function () {
                var currentInPageTask = $(this);
                if ($('.type', currentInPageTask).text() == latestDataTask.type) {
                    inPageTask = currentInPageTask;
                }
                
                return;
            });
            
            return inPageTask;
        };
        
        var taskStateIconMap = {
            'queued': 'icon-cog',
            'in-progress': 'icon-cogs',
            'completed': 'icon-bar-chart'
        };
        
        var getNewInPageTask = function (latestDataTask, inPageUrl) {
            return $('<li class="task '+latestDataTask.state+'">\n\
                        <span class="state">\n\
                            <i class="'+taskStateIconMap[latestDataTask.state]+'"></i>\n\
                        </span>\n\
                        <span class="type">'+latestDataTask.type+'</span>\n\
                      </li>'
            );
        };
        
        var getNewInPageUrl = function (url) {
            return $('<li class="url">\n\
                        <span class="url">'+url+'</span>\n\
                        <span class="tasks">\n\
                            <ul class="tasks"></ul>\n\
                        </span>\n\
                      </li>'
            );
        };
        
        var getInPageTaskState = function (inPageTask) {
            var states = ['in-progress', 'queued', 'completed'];
            for (var stateIndex = 0; stateIndex < states.length; stateIndex++) {
                if (inPageTask.hasClass(states[stateIndex])) {
                    return states[stateIndex];
                }
            }
            
            return false;            
        };
        
        var tasksToRetrieveOutputFor = {};
        var tasksToRetrieveOutputForCount = 0;   
        
        for (var urlIndex = 0; urlIndex < latestData.urls.length; urlIndex++) {
            var url = latestData.urls[urlIndex];
            var inPageUrl = findInPageUrl(url);
            
            if (inPageUrl === false) {
                inPageUrl = getNewInPageUrl(url);
                getUrlList().append(inPageUrl);
            }
            
            var latestDataTasks = findLatestDataTasks(url);            

            for (var latestDataTaskIndex = 0; latestDataTaskIndex < latestDataTasks.length; latestDataTaskIndex++) {
                var latestDataTask = latestDataTasks[latestDataTaskIndex];
                var inPageTask = findInPageTask(latestDataTask, inPageUrl);

                if (inPageTask === false) {
                    inPageTask = getNewInPageTask(latestDataTask, inPageUrl);
                    $('ul.tasks', inPageUrl).append(inPageTask);
                }  
                
                var previousState = getInPageTaskState(inPageTask);

                inPageTask.removeClass('in-progress').removeClass('queued').removeClass('completed').addClass(latestDataTask.state);
                $('.state i', inPageTask).removeClass('icon-cog').removeClass('icon-cogs').removeClass('icon-bar-chart').addClass(taskStateIconMap[latestDataTask.state]);
                
                if (getInPageTaskState(inPageTask) == 'completed' && previousState != 'completed') {                                        
                    tasksToRetrieveOutputFor[latestDataTask.id] = inPageTask
                    tasksToRetrieveOutputForCount++;;
                }                              
            }
        }         
        
        if (tasksToRetrieveOutputForCount > 0 && $('#completion-percent-value').text() < 100) {
            taskOutputController.update(tasksToRetrieveOutputFor);
        }
    };
    
    var refresh = function () {
        var now = new Date();
        
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
                
                latestData = data;
                
                setCompletionPercentValue();                
                setCompletionPercentStateLabel();
                setCompletionPercentStateIcon();
                setTestQueues();
                setUrlCount();               
                setTaskCount();
                updateUrls();
                
                window.setTimeout(function () {
                    refresh();
                }, 1000);
            },
            url:window.location.href + '?output=json&timestamp=' + now.getTime() 
        });
    };
    
    this.initialise = function () {
        refresh();
    };
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
                        
                    case '#tests-without-errors':
                        if (task.hasClass('failed') || task.hasClass('cancelled')) {
                            task.remove();
                        }
                        break;
                        
                    case '#tests-with-errors':
                        if (!task.hasClass('failed')) {
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
                testProgressController = new application.testProgressController();
                testProgressController.initialise();
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