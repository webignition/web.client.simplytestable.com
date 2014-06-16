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

application.root = {};
application.root.testStartFormController = function () {
    
    var getForm = function () {
        return $('#test-start-form');
    };
    
    var getTextField = function () {
        return $('input[name=website]', getForm());
    };
    
    var getHeader = function () {
        return $('h1', getForm());
    };  
    
    var getFieldsContainer = function () {
        return $('.fields', getForm());
    };
    
    var getTestOptions = function () {
        return $('#test-options');
    };
    
    var getTaskTypeSelection = function () {
        var taskTypeSelection = {};
        
        $('.task-type-option').each(function () {
            var testOptionsSet = $(this);
            var label = $('label', testOptionsSet).first();
            var checkbox = $('input[type=checkbox]', label);
            
            taskTypeSelection[$('span.task-type-name b', label).text()] = checkbox.is(':checked') && checkbox.attr('disabled') !== 'disabled';
        });
        
        return taskTypeSelection;        
    };
    
    var getTaskTypeCheckboxes = function () {
        return $('input.task-type', getTestOptions());
    };
    
    var getTaskTypeCount = function () {
        var taskTypeCount = 0;
        var taskTypeSelection = getTaskTypeSelection();
        
        for (var taskTypeName in taskTypeSelection) {
            if (taskTypeSelection.hasOwnProperty(taskTypeName)) {
                taskTypeCount = taskTypeCount + 1;
            }
        }        
        
        return taskTypeCount;
    };
    
    var getTaskTypeSelectionString = function () {
        var taskTypeSelectionString = 'Testing ';
        
        var taskTypeSelection = getTaskTypeSelection();        
        var taskTypeCount = getTaskTypeCount();
        var taskTypeIndex = 0;
        
        for (var taskTypeName in taskTypeSelection) {
            if (taskTypeSelection.hasOwnProperty(taskTypeName)) {
                taskTypeIndex = taskTypeIndex + 1;                
                
                taskTypeSelectionString = taskTypeSelectionString + '<span class="' + (taskTypeSelection[taskTypeName] ? 'selected' : 'not-selected') + '">' + taskTypeName + '</span>';
                
                if (taskTypeIndex === taskTypeCount - 1) {
                    taskTypeSelectionString = taskTypeSelectionString + ' and ';
                } else {
                    if (taskTypeIndex < taskTypeCount) {
                        taskTypeSelectionString = taskTypeSelectionString + ', ';
                    }                    
                }                
            }
        }
        
        taskTypeSelectionString = taskTypeSelectionString + '.';        
        return taskTypeSelectionString;
    };
    
    var getIntro = function () {
        return $('.intro', getForm());
    };
    
    var setIntroContent = function () {
        $('.task-type-selection', getIntro()).html(getTaskTypeSelectionString());
    };
    
    var getAuthenticationEnabledString = function () {       
        if (hasAuthenticationCredentials()) {
            return 'enabled';
        } else {
            return 'not enabled';
        }
    }; 
    
    var getCustomCookiesEnabledString = function () {       
        if (hasCustomCookieValues()) {
            return 'enabled';
        } else {
            return 'not enabled';
        }
    };
    
    var setAuthenticationContent = function () {
        $('.authentication-enabled', getTestOptions()).html(getAuthenticationEnabledString());
    };
    
    var setCustomCookiesContent = function () {
        $('.cookies-enabled', getTestOptions()).html(getCustomCookiesEnabledString());
    };    
    
    var getAuthenticationCredentialInputs = function () {        
        return $('#authentication-options-modal input');
    };
    
    var hasAuthenticationCredentials = function () {
        var hasAuthenticationCredentials = false;
        getAuthenticationCredentialInputs().each(function () {
            if ($(this).val() !== '') {
                hasAuthenticationCredentials = true;
            }
        });
        
        return hasAuthenticationCredentials;
    };
    
    var getCustomCookieInputs = function () {
        return $('#cookies-options-modal input');
    };
    
    var hasCustomCookieValues = function () {
        var hasCustomCookieValues = false;
        
        getCustomCookieInputs().each(function () {
            if ($(this).val() !== '') {
                hasCustomCookieValues = true;
            }            
        });
        
        return hasCustomCookieValues;
    };
    
    this.setAuthenticationContent = function () {
        setAuthenticationContent();
    };
    
    this.hasAuthenticationCredentials = function () {
        return hasAuthenticationCredentials();
    };
    
    this.hasCustomCookieValues = function () {
        return hasCustomCookieValues();
    };
    
    this.setAuthenticationFieldInputs = function (modal) {
        var hiddenInputs = $('<div class="hidden-inputs">');

        $('input', modal).each(function () {
            var input = $(this);                                        
            var hiddenInput = $('<input type="hidden"/>').attr('name', input.attr('name')).attr('value', input.val());

            hiddenInputs.append(hiddenInput);
        });

        $('#authentication-options-modal').html(hiddenInputs);
    };     
    
    this.setCookieFieldInputs = function (modal) {
        var hiddenInputs = $('<div class="hidden-inputs">');

        $('input', modal).each(function () {
            var input = $(this);                                        
            var hiddenInput = $('<input type="hidden"/>').attr('name', input.attr('name')).attr('value', input.val());

            hiddenInputs.append(hiddenInput);
        });

        $('#cookies-options-modal').html(hiddenInputs);
    };   
    
    
    this.setCustomCookiesContent = function () {
        setCustomCookiesContent();
    };
    
    this.clearCookie = function (trashIcon) {
        if ($('.modal.in tbody tr').length === 1) {
            $('input', trashIcon.closest('tr')).val('');
        } else {
            trashIcon.closest('tr').remove();
        }
    };
  
    this.initialise = function () {
        try {
            if (Modernizr.input.placeholder) {
                var headerIcon = $('i', getHeader()).clone();
                getHeader().remove();
                getFieldsContainer().prepend(headerIcon);            
            }
        } catch (ReferenceError) {
        }
        
        getTaskTypeCheckboxes().each(function () {
            var checkbox = $(this);
            checkbox.change(function () {
                setIntroContent();
            });
        });
        
        $('.add-cookie', getForm()).click(function(event) {            
            var modal = $('.modal.in');
            
            var tableBody = $('.test-cookies table tbody', modal);            
            var cookieRows = $('tr', tableBody);            
            var currentCookieCount = cookieRows.length;
            var newRow = $(cookieRows.get(0)).clone();
            $('input', newRow).each(function () {
                var input = $(this);
                
                input.attr('value', '');
                
                if (input.attr('name').indexOf('name') !== -1) {
                    input.attr('name', 'cookies['+currentCookieCount+'][name]');
                }                
                
                if (input.attr('name').indexOf('value') !== -1) {
                    input.attr('name', 'cookies['+currentCookieCount+'][value]');
                } 
                
                input.keydown(function (event) {                                            
                    if (event.which === 13) {
                        modal.modal('hide');
                    }
                });                
            });
            
            $('.remove', newRow).html('');
                                        
            var trashIcon = $('<i class="icon icon-trash" title="Remove this cookie"></i>').click(function () {                                                
                testStartFormController.clearCookie($(this));
            }).hover(function () {
                $(this).css({
                    'cursor':'pointer'
                });
            });

            $('.remove', newRow).append(trashIcon);            
            
            tableBody.append(newRow);
            
            var lastRow = $('.test-cookies table tbody tr', modal).last();
            $($('input', lastRow).get(0)).focus();

            event.preventDefault();
        });
        
        $('#input-website').focus();
    };
};


application.root.currentTestController = function () {
    var getBaseUrl = function () {
        return window.location.protocol + "//" + window.location.hostname + window.location.pathname;
    };    
    
    var remoteTests = null;
    var previousRemoteTests = null;
    
    var getContainer = function () {
        return $('#current-tests-content');
    };
    
    var getTests = function () {
        return $('.site', getContainer());
    };
    
    var hasCurrentTests = function () {
        return getTests().length > 0;
    };
    
    var getTest = function (id) {                
        var test = $("[data-test-id='"+id+"']", getContainer());
        return (test.is('.site')) ? test : null;
    };
    
    var updateTest = function (testData) {        
        if (!testIsInCurrentList(testData.id)) {
            getCurrentTestContent(function (data) {
                $(data).each(function () {
                    var test = $(this);
                    if (test.is('.site') && parseInt(test.attr('data-test-id'), 10) === parseInt(testData.id, 10)) {
                        addNewTest(test);
                    }                    
                });
            });
        }        
        
        var test = getTest(testData.id);
        if (test === null) {
            return;
        }
        
        var getQueuedCount = function () {
            return getCountByState('queued') + getCountByState('queued-for-assignment');
        };
        
        var getInProgressCount = function () {
            return getCountByState('in-progress');
        };        
        
        var getCountByState = function (state) {
            for (var stateName in testData.task_count_by_state) {
                if (testData.task_count_by_state.hasOwnProperty(stateName)) {
                    if (stateName === state) {
                        return testData.task_count_by_state[state];
                    }
                }
            }
            
            return 0;
        };
        
        var getFinishedCount = function () {
            return Math.max(testData.task_count - getQueuedCount() - getInProgressCount(), 0);
        };
        
        var getCompletionPercent = function () {
            return testData.completion_percent + 0;
        };
        
        var setStateIcon = function () {
            var stateIcon = $('.state-icon', test);
            var iconClasses = stateIcon.attr('class').split(' ');            
            for (var iconIndex = 0; iconIndex < iconClasses.length; iconIndex++) {
                if (/icon-/.test(iconClasses[iconIndex])) {
                    stateIcon.removeClass(iconClasses[iconIndex]);
                }
            }
            
            stateIcon.addClass(testData.state_icon);
        };
        
        var setBadgeState = function () {
            var badge = $('.badge', test);
            var badgeClasses = badge.attr('class').split(' ');            
            for (var index = 0; index < badgeClasses.length; index++) {
                if (/badge-/.test(badgeClasses[index])) {
                    badge.removeClass(badgeClasses[index]);
                }
            }
            
            badge.addClass('badge-' + testData.state_label_class);
        };
        
        var hasSwitchedToOrFromCrawling = function () {
            if (testData.state === 'crawling' && test.attr('data-state') !== 'crawling') {
                return true;
            }
            
            if (testData.state !== 'crawling' && test.attr('data-state') === 'crawling') {
                return true;
            }
            
            return false;            
        };
        
        if (hasSwitchedToOrFromCrawling()) {
            getCurrentTestContent(function (data) {
                var tests = $(data);                
                tests.each(function () {
                    var currentTest = $(this);
                    
                    if (currentTest.is('.site')) {
                        if (testData.id === parseInt(currentTest.attr('data-test-id'), 10)) {
                            switchTest(currentTest);
                        }
                    }
                });
            });
            
            return;
        }
        
        if (testData.state === 'crawling') {
            $('.processed-url-count', test).text(testData.crawl.processed_url_count);
            $('.discovered-url-count', test).text(testData.crawl.discovered_url_count);
            
        } else {
            $('.queued-count', test).text(getQueuedCount());
            $('.in-progress-count', test).text(getInProgressCount());
            $('.finished-count', test).text(getFinishedCount());
            $('.task-count', test).text(testData.task_count);
            $('.url-count', test).text(testData.url_count);

            setStateIcon();
            setBadgeState();            
        }
        
        $('.bar', test).css({
            'width':Math.ceil(testData.completion_percent) + '%'
        });        
    };
    
    var testIsInRemoteList = function (id) {
        for (var testIndex = 0; testIndex < remoteTests.length; testIndex++) {            
            if (remoteTests[testIndex].id === id) {                
                return true;
            }
        }        

        return false;
    };    
    
    var testIsInCurrentList = function (id) {
        id = parseInt(id, 10);
        
        var is = false;
        
        getTests().each(function () {            
            if (parseInt($(this).attr('data-test-id'), 10) === id) {
                is = true;
            }
        });
        
        return is;
    };
    
    var removeCompletedTest = function (test) {        
        updateTest({
            'id':parseInt(test.attr('data-test-id'), 10),
            'task_count_by_state': {
                'queued':0,
                'queued-for-assignment':0,
                'in-progress':0
            },
            'completion_percent':100,
            'task_count':$('.task-count', test).text(),
            'state_icon':'icon-play-circle',
            'state_label_class':'success'
        });
        
        test.slideUp(function () {
            test.remove();
        });

    };
    
    var addNewTest = function (test) {
        var addCallback = function () {
            test.hide();            
            getContainer().prepend(test);
            test.slideDown();            
        };
        
        if ($('p.info', getContainer()).is('.info')) {        
            $('p.info', getContainer()).slideUp(function () {
                addCallback();
            });            
        } else {
            addCallback();
        }
    };
    
    var switchTest = function (newTest) {
        var currentTest = getTest(newTest.attr('data-test-id'));
        
        newTest.hide();
        getContainer().prepend(newTest);
        currentTest.slideUp(function () {
            newTest.slideDown(function () {
                currentTest.remove();
            });
        });
    };
    
    var updateList = function () {
        for (var testIndex = 0; testIndex < remoteTests.length; testIndex++) {
            updateTest(remoteTests[testIndex]);
        } 
        
        if (remoteTests.length === 0) {
            getTests().each(function () {
                var test = $(this);         
                removeCompletedTest(test);
            });            
        }

        getTests().each(function () {
            var test = $(this);            
            var testId = parseInt(test.attr('data-test-id'), 10);
            
            for (var testIndex = 0; testIndex < remoteTests.length; testIndex++) {
                if (testIsInRemoteList(testId) === false) {                    
                    removeCompletedTest(test);
                }
            }
        });
    };
    
    var hasCurrentTestBeenRemovedFromList = function () {
        if (previousRemoteTests === null) {
            return false;
        }
        
        if (remoteTests.length === 0 && previousRemoteTests.length !== 0) {
            return true;
        }
        
        for (var previousIndex = 0; previousIndex < previousRemoteTests.length; previousIndex++) {
            if (!testIsInRemoteList(previousRemoteTests[previousIndex].id)) {
                return true;
            }
        }
        
        return false;
    };
    
    var retrieve = function () {        
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {        
            },
            success: function(data, textStatus, request) {                
                previousRemoteTests = remoteTests;
                remoteTests = data;
                updateList();
                
                if (hasCurrentTestBeenRemovedFromList()) {
                    jQuery.ajax({
                        dataType: 'html',
                        error: function(request, textStatus, errorThrown) {             
                        },
                        success: function(data, textStatus, request) {                    
                            var finishedTestsUpdateController = new application.root.finishedTestsUpdateController();
                            finishedTestsUpdateController.initialise($(data));                            
                        },
                        url: getBaseUrl() + 'finished-content/'
                    });
                }                
                
                window.setTimeout(function() {
                    refresh();
                }, 3000); 
            },
            url: getBaseUrl() + 'current/'
        });        
    };
    
    var getCurrentTestContent = function (callback) {                
        jQuery.ajax({
            dataType: 'html',
            error: function(request, textStatus, errorThrown) {                    
            },
            success: function(data, textStatus, request) {
                if (typeof callback === 'function') {
                    callback(data);
                }               
            },
            url: getBaseUrl() + 'current-content/'
        });        
    };
    
    var refresh = function () {
        if (hasCurrentTests()) {
            retrieve();
        } else {
            getCurrentTestContent(function (data) {
                if ($($(data).get(0)).is('.site')) {                    
                    $(data).each(function () {
                        addNewTest($(this));
                    });                    
                } else {
                    $('#current-tests-content').html(data);                                 
                }
                
                window.setTimeout(function() {
                    refresh();
                }, 3000);                   
            });
        }
    };
    
    this.initialise = function () {
        window.setTimeout(function() {
            refresh();
        }, 3000);            
    };
};

application.root.finishedTestsPreparingController = function () {
    var getBaseUrl = function () {
        return window.location.protocol + "//" + window.location.hostname + window.location.pathname;
    };        
    
    var getContainer = function () {
        return $('#finished-tests');
    };
    
    var getTestsRequiringResults = function () {
        return $('.requires-results', getContainer());
    };
    
    var getSummary = function (localTaskCount, remoteTaskCount) {        
        if (localTaskCount === undefined && remoteTaskCount === undefined) {
            return 'Preparing summary &hellip;';
        }
        
        return 'Preparing summary &hellip; retrieved <strong class="local-task-count">'+ localTaskCount +'</strong> results of <strong class="remote-task-count">'+ remoteTaskCount +'</strong>';
    };
    
    var getUnretrievedRemoteTaskIdsUrl = function(test) {
        return getBaseUrl() + $('.website', test).text() + '/' + test.attr('data-test-id') + '/tasks/ids/unretrieved/100/';
    };

    var getTaskResultsRetrieveUrl = function(test) {
        return getBaseUrl() + $('.website', test).text() + '/' + test.attr('data-test-id') + '/results/retrieve/';
    };  
    
    var displayTestSummary = function (test) {
        var getTestSummaryUrl = function () {
            return getBaseUrl() + $('.website', test).text() + '/' + test.attr('data-test-id') + '/finished-summary/';
        };
        
        jQuery.ajax({
            dataType: 'html',
            error: function(request, textStatus, errorThrown) {                
            },
            success: function(data, textStatus, request) {
                var newTest = $(data);
                $('.summary-stats', newTest).hide();
                newTest.addClass('replacing');
                
                $('.summary-stats', test).slideUp(function () {
                    test.replaceWith(newTest);
                    $('.summary-stats', newTest).slideDown();
                    newTest.removeClass('replacing');
                });
            },
            url: getTestSummaryUrl(test)
        });        
    };
    
    var retrieveNextRemoteTaskIdCollection = function(test, taskIds) {        
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {                
            },
            data: {
                'remoteTaskIds': taskIds.join(',')
            },
            success: function(data, textStatus, request) {
                checkStatus(test, function (data) {
                    $('.bar', test).css({
                        'width':Math.ceil(data.completion_percent) + '%'
                    });
                    
                    if (data.completion_percent === 100) {
                        displayTestSummary(test);
                    } else {
                        $('.local-task-count', test).text(data.local_task_count);                        
                        getNextRemoteTaskIdCollection(test);
                    }
                });
            },
            url: getTaskResultsRetrieveUrl(test)
        });
    };    
    
    var getNextRemoteTaskIdCollection = function(test) {
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                retrieveNextRemoteTaskIdCollection(test, data);
            },
            url: getUnretrievedRemoteTaskIdsUrl(test)
        });
    };
    
    var checkStatus = function (test, callback) {
        var getPreparingStatsUrl = function () {
            return getBaseUrl() + $('.website', test).text() + '/' + test.attr('data-test-id') + '/results/preparing/stats/';
        };        
        
        jQuery.ajax({
            dataType: 'json',
            error: function(request, textStatus, errorThrown) {                 
            },
            success: function(data, textStatus, request) {        
                callback(data);
            },
            url: getPreparingStatsUrl()
        });        
    };
    
    var initialiseTest = function (test) {        
        checkStatus(test, function (data) {
            $('.summary-stats .summary', test).html(getSummary(data.local_task_count, data.remote_task_count));
            getNextRemoteTaskIdCollection(test);            
        });
        
       $('.summary-stats', test).html('<p class="summary">' + getSummary() + '</p><div class="progress progress-striped active"><div class="bar" style="width:0%;"></div></div>');
        

    };
    
    this.initialise = function () {                
        getTestsRequiringResults().each(function () {            
            initialiseTest($(this));
        });
    };
};

application.root.finishedTestsUpdateController = function () {
    var getContainer = function () {
        return $('#finished-tests-content');
    };
    
    var getTests = function () {
        return $('.site', getContainer());
    };
    
    var testSetContainsId = function (tests, id) {
        var contains = false;
        
        tests.each(function () {
            var test = $(this);
            if (test.is('.site')) {
                var testId = test.attr('data-test-id');

                if (testId === id) {
                    contains = true;
                }
            }            
        });
        
        return contains;
    };
    
    var remove = function (test) {
        test.slideUp(function () {
            test.remove();
        });
    };
    
    var removePreviousTests = function (currentTests, newTests) {
        currentTests.each(function () {
            var test = $(this);
            var testId = test.attr('data-test-id');
            
            if (!testSetContainsId(newTests, testId)) {
                remove(test);
            }
        });
    };
    
    var addNewTests = function (currentTests, newTests) {
        newTests.each(function () {
            var test = $(this);
            
            if (test.is('.site')) {
                var testId = test.attr('data-test-id');            
                if (!testSetContainsId(currentTests, testId)) {
                    if ($('p.info', getContainer()).is('.info')) {
                        $('p.info').remove();
                    }
                    
                    test.hide();
                    getContainer().prepend(test);
                    test.slideDown(function () {
                        finishedTestsPreparingController = new application.root.finishedTestsPreparingController();
                        finishedTestsPreparingController.initialise();                        
                    });
                }
            }
        });
    };
    
    this.initialise = function (newTests) {        
        removePreviousTests(getTests(), newTests);      
        addNewTests(getTests(), newTests);
    };
};

application.pages = {
    '/*': {
        'initialise': function() { 
            $(document).keyup(function (event) {               
                $('.modal.in').each(function () {
                    if (event.which === 27) {
                        $(this).modal('hide');
                    }
                });
//
            });

            if ($('body.app').length > 0) {
                testStartFormController = new application.root.testStartFormController();
                testStartFormController.initialise();
                
                currentTestController = new application.root.currentTestController();
                currentTestController.initialise();
                
                finishedTestsPreparingController = new application.root.finishedTestsPreparingController();
                finishedTestsPreparingController.initialise();
            }

            if ($('body.content').length > 0) {
                getTwitters('footer-tweet', {
                    id: 'simplytestable',
                    count: 1,
                    enableLinks: true,
                    ignoreReplies: true,
                    clearContents: true,
                    template: '%text% <a class="time" href="http://twitter.com/%user_screen_name%/statuses/%id_str%/">%time%</a>',
                    callback: function() {
                        $('.tweet-container .tweet').html($('#footer-tweet').html()).animate({
                            'opacity': 1
                        });
                    }
                });
            }
            
            $('.full-width-container .alert:last').css({
                'margin-bottom':'20px'
            });
            
            $('.modal-control').each(function () {
                var getModal = function (controlClass) {
                    var modalContent;
                    
                    var classes = controlClass.split(' ');                    
                    for (var classIndex = 0; classIndex < classes.length; classIndex++) {
                        if (classes[classIndex].match(/for\-.+/)) {
                            modalContent = $('#' + classes[classIndex].replace('for-', ''));

                            if (modalContent.length === 0) {
                                return false;
                            }
                        }
                    }                    
                    
                    var applyTransforms = function (contentSection) {
                        $('.modal-transform', contentSection).each(function () {
                            var item = $(this);
                            
                            var getTransformTo = function () {
                                var classes = item.attr('class').split(' ');                    
                                for (var classIndex = 0; classIndex < classes.length; classIndex++) {
                                    if (classes[classIndex].match(/modal-transform-./)) {
                                        return classes[classIndex].replace('modal-transform-', '');
                                    }
                                }
                                
                                return false;
                            };
                            
                            var transformTo = getTransformTo();
                            if (transformTo === false) {
                                return;
                            }                            
                     
                            item.replaceWith($('<'+transformTo+'>').append(item.html()));
                            
                        });
                    };
                    
                    var getModalHeader = function () {
                        var header = $('.modal-header-content', modalContent).prepend('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>');
                        applyTransforms(header);                        
                        return header;
                    };
                    
                    var getModalBody = function() {
                        var body = $('.modal-body-content', modalContent);                        
                        return body;
                    };
                    
                    var getModalFooter = function () {
                        var defaultContent = '<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">' + getModalOptions()['action-label'] + '</button>';
                        var footer = $('.modal-footer-content', modalContent);                        
                        
                        if (footer.length === 0) {
                            return defaultContent;
                        }
                        
                        var footerContent = footer.html() + defaultContent;
                        
                        footer.remove();
                        
                        return footerContent;
                    };
                    
                    var setEventHandlers = function () {
                        var handlers = {
                            'cookie-options': {
                                'show': function (event) {
                                    var modal = $(event.target);
                                    
                                    $('td.remove', modal).each(function() {
                                        $(this).html('');
                                        
                                        var trashIcon = $('<i class="icon icon-trash" title="Remove this cookie"></i>').click(function () {                                                
                                            testStartFormController.clearCookie($(this));
                                        }).hover(function () {
                                            $(this).css({
                                                'cursor':'pointer'
                                            });
                                        });
                                        
                                        $(this).append(trashIcon);
                                    });
                                },
                                'shown': function (event) {
                                    var modal = $(event.target);
                                    var lastUnfilledNameInput = false;
                                    
                                    $('.name input', modal).each(function () {
                                        var input = $(this);
                                        if (jQuery.trim(input.val()) !== '') {
                                            lastUnfilledNameInput = input;
                                        }
                                    });
                                    
                                    if (lastUnfilledNameInput === false) {
                                        lastUnfilledNameInput = $('.name input', modal).last();
                                    }
                                    
                                    lastUnfilledNameInput.focus();
                                },
                                'hide': function (event) {
                                    if ($('#test-start-form').length === 1) {
                                        testStartFormController.setCookieFieldInputs($(event.target));
                                        testStartFormController.setCustomCookiesContent();                                        
                                    }
                                    
                                    if (testStartFormController.hasCustomCookieValues()) {
                                        $('.action-badge-cookies-options').addClass('action-badge-enabled');
                                    } else {
                                        $('.action-badge-cookies-options').removeClass('action-badge-enabled');
                                    }                                      
                                }
                            },
                            'authentication-options':{
                                'show': function (event) {
                                },
                                'shown': function (event) {
                                    var modal = $(event.target);
                                    var clearButton = $('#clear-http-authentication-button', modal);
                                    var inputs = $('input', modal);
                                    
                                    var hasAuthenticationCredentials = function () {
                                        var has = false;
                                        
                                        inputs.each(function () {
                                            if (jQuery.trim($(this).val()) !== '') {
                                                has = true;
                                            }
                                        });                
                                        
                                        return has;
                                    };
                                    
                                    clearButton.click(function () {
                                        inputs.val('');
                                    });
                                    
                                    if (hasAuthenticationCredentials()) {
                                        clearButton.removeAttr('disabled');                                        
                                    } else {
                                        clearButton.attr('disabled', 'disabled');
                                    } 
                                    
                                    inputs.each(function () {
                                        $(this).keyup(function () {
                                            if (hasAuthenticationCredentials()) {
                                                clearButton.removeAttr('disabled');                                            
                                            } else {
                                                clearButton.attr('disabled', 'disabled');
                                            }                                            
                                        });
                                    });                                  
                                    
                                    inputs.first().focus();
                                },
                                'hide': function (event) {
                                    if ($('#test-start-form').length === 1) {
                                        testStartFormController.setAuthenticationFieldInputs($(event.target));
                                        testStartFormController.setAuthenticationContent();                                        
                                    }
                                    
                                    if (testStartFormController.hasAuthenticationCredentials()) {
                                        $('.action-badge-http-authentication').addClass('action-badge-enabled');
                                    } else {
                                        $('.action-badge-http-authentication').removeClass('action-badge-enabled');
                                    }                                    
                                }                                
                            }
                        };                        
                        
                        var getModalType = function () {
                            var classes = modalContent.attr('class').split(' ');                    
                            for (var classIndex = 0; classIndex < classes.length; classIndex++) {
                                if (classes[classIndex].match(/modal-type-./)) {
                                    return classes[classIndex].replace('modal-type-', '');
                                }
                            }
                            
                            return false;
                        };
                        
                        var hasModalType = function () {
                            return getModalType() !== false;
                        };
                        
                        var hasHandlers = function () {
                            return handlers.hasOwnProperty(getModalType());
                        };
                        
                        if (!hasModalType()) {
                            return;
                        }
                        
                        if (!hasHandlers()) {
                            return;
                        }
                        
                        var modalType = getModalType();
                        
                        for (var eventName in handlers[modalType]) {
                            if (handlers[modalType].hasOwnProperty(eventName)) {                                
                                modal.on(eventName, handlers[modalType][eventName]);
                            }
                        }
                        
                    };
                    
                    var getModalOptions = function () {
                        var retrievedOptions = {
                            'action-label':'Done'
                        };
                        
                        var optionKeys = [
                            'action-label'
                        ];
                        
                        var options = $('.modal-options', modalContent);
                        if (options.length !== 1) {
                            return retrievedOptions;
                        }
                        
                        for (var index = 0; index < optionKeys.length; index++) {
                            var option = $('.' + optionKeys[index], options);
                            if (option.length === 1) {
                                retrievedOptions[optionKeys[index]] = option.text();
                            }                            
                        }
                        
                        return retrievedOptions;
                    };
                    
                    var modal = $('<div class="modal hide"/>').append(
                            $('<div class="modal-header"/>').append(getModalHeader())
                    ).append(
                            $('<div class="modal-body"/>').append(getModalBody())              
                    ).append(
                            $('<div class="modal-footer" />').append(getModalFooter())
                    );
            
                    $('input[type=text],input[type=password]', modal).keyup(function (event) {                        
                        if (event.which === 13) {
                            modal.modal('hide');
                        }
                    });
            
                    setEventHandlers();

                    return modal;                                       
                    
                };
                
                var modalControl = $(this);
                var modal = getModal(modalControl.attr('class'));
                
                if (modal === false) {
                    return;
                }
                
                var controlLink = $('<a href="#" class="expandable-control-action">' + modalControl.html() + ' <i class="icon icon-caret-right"></i></a>');
                modalControl.replaceWith(controlLink);
                
                controlLink.click(function (event) {
                    modal.modal({
                        backdrop: true,
                        keyboard: true                        
                    });
                    event.preventDefault();
                });
                
                                
            });
            

            $('.expandable-control').each(function() {
                var getExpandableArea = function(expandableControlClass) {
                    var classes = expandableControlClass.split(' ');
                    for (var classIndex = 0; classIndex < classes.length; classIndex++) {
                        if (classes[classIndex].match(/for\-.+/)) {
                            var expandableAreaId = classes[classIndex].replace('for-', '');
                            var expandableArea = $('#' + expandableAreaId);

                            if (expandableArea.length === 1) {
                                return expandableArea;
                            }
                        }
                    }

                    return false;
                };

                var expandableControl = $(this);
                var expandableArea = getExpandableArea(expandableControl.attr('class'));

                if (expandableArea === false) {
                    return;
                }

                var defaultState = (expandableControl.is('.expandable-control-default-closed ')) ? 'closed' : 'open';
                var controlLinkIconName = (defaultState == 'closed') ? 'icon-caret-down' : 'icon-caret-up';

                var controlLink = $('<a href="#" class="expandable-control-action">' + expandableControl.html() + ' <i class="icon ' + controlLinkIconName + '"></i></a>');
                expandableControl.replaceWith(controlLink);

                if (defaultState == 'closed') {
                    expandableArea.css({'display': 'none'});
                    expandableArea.addClass('closed');
                }

                controlLink.click(function(event) {
                    if (expandableArea.is('.closed')) {
                        expandableArea.slideDown(function() {
                            expandableArea.removeClass('closed').addClass('open');
                            $('.icon-caret-down', controlLink).replaceWith('<i class="icon icon-caret-up" />');
                        });
                    } else {
                        expandableArea.slideUp(function() {
                            expandableArea.removeClass('open').addClass('closed');
                            $('.icon-caret-up', controlLink).replaceWith('<i class="icon icon-caret-down" />');
                        });
                    }

                    event.preventDefault();
                });
            });

        }
    },
    '/': {
        'initialise': function() {}
    },
};



var applicationController = function() {
    var getPagePath = function() {
        var pagePath = window.location.pathname;
        if (pagePath.substr(pagePath.length - 1, 1) == '/') {
            pagePath = pagePath.substr(0, pagePath.length - 1);
        }

        return pagePath;
    };

    var getPagePathParts = function() {
        return getPagePath().split('/');
    };

    var getPageInitialisationPaths = function() {
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

    var getInitialisationMethods = function() {
        var pageInitialisationPaths = getPageInitialisationPaths();
        var initialisationMethods = [];

        for (var initialisationPathIndex = 0; initialisationPathIndex < pageInitialisationPaths.length; initialisationPathIndex++) {
            if (typeof application.pages[pageInitialisationPaths[initialisationPathIndex]] === 'object') {
                if (typeof application.pages[pageInitialisationPaths[initialisationPathIndex]]['initialise'] === 'function') {
                    initialisationMethods.push(application.pages[pageInitialisationPaths[initialisationPathIndex]]['initialise']);
                }
            }
        }

        return initialisationMethods;
    };

    var initialise = function() {
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