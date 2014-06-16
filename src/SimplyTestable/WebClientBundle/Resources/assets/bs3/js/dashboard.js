$(document).ready(function() {
    $('#test-start-form').submit(function () {
        var form = $(this);

        $('input[type=checkbox]', this).each(function () {
            var checkbox = $(this);

            if (checkbox.is(':checked') === false) {
                var hiddenInput = $('<input type="hidden">');
                hiddenInput.attr('name', checkbox.attr('name'));
                hiddenInput.val(0);

                form.append(hiddenInput);
            }
        });
    });

    $('.collapse-control-group .collapse-control').each(function () {
        var control = $(this);
        var detail = $(control.attr('data-target'));

        detail.on('show.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.collapse-control-group .collapse-control').each(function () {
                var currentControl = $(this);

                if (currentControl.attr('data-target') !== control.attr('data-target')) {
                    var currentDetail = $(currentControl.attr('data-target'));
                    if (currentDetail.is('.in')) {
                        currentDetail.collapse('hide');
                    }
                }
            });
        });

        detail.on('shown.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.fa', control).removeClass('fa-caret-up fa-caret-down').addClass('fa-caret-up');
        });

        detail.on('hidden.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.fa', control).removeClass('fa-caret-up fa-caret-down').addClass('fa-caret-down');
        });

        detail.on('hide.bs.collapse', function (event) {

        });
    });

    $('.buttons button[type=submit]').click(function () {
        var button = $(this);

        $('.fa', button).removeClass('fa-globe').removeClass('fa-circle-o').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });

        $('.buttons button').each(function () {
            if (button.val() !== $(this).val()) {
                $(this).animate({
                    'opacity':0.6
                }).attr('disabled', 'disabled');
            }
        });
    });

    if ($('body.no-test-types-selected').length === 0) {
        $('#website').stFormHelper().select();
    }

    $('body.no-test-types-selected .task-types').each(function () {
        var container = $(this);
        container.animate({ 'opacity':0.6}, function () {
            container.animate({'opacity': 1.0}, function () {
                container.animate({ 'opacity':0.6}, function () {
                    container.animate({'opacity': 1.0});
                });
            });
        });
    });

    var formError = $('#test-start-form .alert');
    if (formError.length > 0) {
        var collapseControlGroup = $('#test-start-form .collapse-control-group');

        collapseControlGroup.css({
            'margin-top': (formError.outerHeight() - 20) + 'px'
        });

        $('.close', formError).on('click', function () {
            collapseControlGroup.css({
                'margin-top': '-20px'
            });
        });
    }

    if ($('.requires-results').length > 0) {
        $('.requires-results').each(function () {
            $(this).stResultPreparer().init();
        });
    }

    var updatedTestList = null;

    var updateRecentTests = function () {

        var getRecentTestsUrl = function () {
            var url = (window.location.protocol + "//" + window.location.hostname + window.location.pathname + 'recent-tests/');
            return url ;
        };

        var getTestList = function () {
            return $('.test-list');
        };


        /**
         *
         * @param testId
         * @returns {jQuery|HTMLElement}
         */
        var getCurrentTest = function (testId) {
            return $('[data-test-id=' + testId + ']', getTestList());
        };

        /**
         *
         * @param testId
         * @returns {boolean}
         */
        var hasCurrentTest = function (testId) {
            return getCurrentTest(testId).length > 0;
        };



        /**
         *
         * @param testId
         * @returns {Query|HTMLElement}
         */
        var getUpdatedTest = function (testId) {
            return $('[data-test-id=' + testId + ']', updatedTestList);
        };


        /**
         *
         * @param testId
         * @returns {boolean}
         */
        var hasUpdatedTest = function (testId) {
            return getUpdatedTest(testId).length > 0;
        };


        var updateTestList = function () {
            $('.site', updatedTestList).each(function () {
                var updatedTest = $(this);
                var testId = updatedTest.attr('data-test-id');

                if (!hasCurrentTest(testId)) {
                    $('h2', getTestList()).after(updatedTest.clone());
                    return;
                }

                var currentTest = getCurrentTest(testId);

                if (currentTest.is('.finished')) {
                    return;
                }

                if (currentTest.attr('data-state') !== updatedTest.attr('data-state')) {
                    var replacementTest = updatedTest.clone();

                    currentTest.animate({
                        'opacity':0
                    }, function () {
                        replacementTest.css({
                            'opacity':0
                        })
                        currentTest.replaceWith(replacementTest);
                        replacementTest.animate({
                            'opacity':1
                        }, function () {
                            $('.requires-results').each(function () {
                                $(this).stResultPreparer().init();
                            });
                        });
                    });

                    return;
                }

                var updatedTestCompletionPercent = updatedTest.attr('data-completion-percent');

                if (currentTest.attr('data-completion-percent') === updatedTestCompletionPercent) {
                    return;
                }

                currentTest.attr('data-completion-percent', updatedTestCompletionPercent);

                var progressBar = $('.progress-bar', currentTest);

                progressBar.attr('aria-valuenow', updatedTestCompletionPercent);
                progressBar.css({
                    width: updatedTestCompletionPercent + '%'
                });

                if (currentTest.attr('data-state') === 'crawling') {
                    $('.processed-url-count', currentTest).text($('.processed-url-count', updatedTest).text());
                    $('.discovered-url-count', currentTest).text($('.discovered-url-count', updatedTest).text());
                }
            });

            $('.site', getTestList()).each(function () {
                var currentTest = $(this);
                var testId = currentTest.attr('data-test-id');

                if (!hasUpdatedTest(testId)) {
                    currentTest.remove();
                }
            });
        };

        jQuery.ajax({
            dataType: 'html',
            error: function(request, textStatus, errorThrown) {
            },
            success: function(data, textStatus, request) {
                updatedTestList = $('<div>').append(data);

                updateTestList();

                window.setTimeout(function () {
                    updateRecentTests();
                }, 3000);
            },
            url: getRecentTestsUrl()
        });
    };

    //window.setTimeout(function () {
        updateRecentTests();
    //}, 3000);
});