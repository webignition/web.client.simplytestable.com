var application = {};

application.taskOutputController = function () {
    var taskOutput = [];
    var test = 'thing';

};

application.taskOutputController.update = function () {
    
};


application.testProgressController = function () {
    var taskOutputController = new application.taskOutputController();
    var latestData = {};

    
    var setCompletionPercentValue = function () {
        var completionPercentValue = $('#completion-percent-value');
        
        if (completionPercentValue.text() != latestData.test.completionPercent) {
            completionPercentValue.text(latestData.test.completionPercent);

            $('#completion-percent-bar').css({
                'width':latestData.test.completionPercent + '%'
            });
        }        
    };
    
    var setCompletionPercentStateLabel = function () {
        var completionPercentStateLabel = $('#completion-percent-state-label');
        if (completionPercentStateLabel.text() != latestData.state_label) {
            completionPercentStateLabel.text(latestData.state_label);
        }         
    };
    
    var getTestQueueWidth = function (queueName) {
        if (latestData.test.taskCountByState[queueName] == 0) {
            return 1;
        }
        
        return (latestData.test.taskCountByState[queueName] / latestData.test.taskCount) * 100;
    };
    
    var setTestQueues = function () { 
        var queues = ['queued', 'in_progress', 'completed'];
        
        for (var queueNameIndex = 0; queueNameIndex < queues.length; queueNameIndex++) {
            var queueName = queues[queueNameIndex];

            $('#url_list .test-states .' + queueName).each(function () {
                var queueDetail = $(this);
                var bar = $('.bar .label', queueDetail)
                bar.animate({
                    'width': getTestQueueWidth(queueName) + '%'
                });
                
                bar.text(latestData.test.taskCountByState[queueName]);
            });               
        }
    };
    
    var setUrlCount = function () {
        $('#url_list_url_count').text(latestData.test.urlCount);
    };
    
    var setTaskCount = function () {
        $('#url_list_task_count').text(latestData.test.taskCount);
    };   
    
    var updateUrls = function () { 
        var getUrlList = function () {
            if ($('#url_list .urls').length === 0) {
                $('#url_list').append('<ul class="urls" />');
            }
            
            return $('#url_list .urls');
        };
        
        var inPageUrls = $('li', getUrlList());
        
        var findInPageUrl = function (url) {
            var inPageUrl = false;
            
            inPageUrls.each(function () {
                if ($('.url', this).text() == url) {
                    inPageUrl = this;
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
//                    taskOutputController.update();
//                    console.log(inPageTask, latestDataTask.id); 
                }
                
//                if (inPageTask.hasClass('completed')) {
//                     
//                }
                              
            }
        }      
    };
    
    var refresh = function () {
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
                setTestQueues();
                setUrlCount();               
                setTaskCount();                                         
                updateUrls();
                
                window.setTimeout(function () {
                    refresh();
                }, 1000);
            },
            url:window.location.href + '?output=json'
        });
    };
    
    this.initialise = function () {
        refresh();
    };
};

application.pages = {
    '/*':{
        'initialise':function () {
            if ($('body.app-progress').length > 0) {                
                testProgressController = new application.testProgressController();
                testProgressController.initialise();
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