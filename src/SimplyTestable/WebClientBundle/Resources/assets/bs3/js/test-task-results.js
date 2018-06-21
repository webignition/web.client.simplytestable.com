$(document).ready(function() {
    var filterErrors = function () {
        var generateUniqueErrorHash = function (error) {
            var sourceHash = SparkMD5.hash(error);

            if ($('#' + sourceHash).length === 0) {
                return sourceHash;
            }

            var suffixDigit = 1;
            var modifiedHash = sourceHash + '-' + suffixDigit;

            while ($('#' + modifiedHash).length) {
                suffixDigit++;
                modifiedHash = sourceHash + '-' + suffixDigit;
            }

            return modifiedHash;
        };

        var filterSelector = $('.issue-content').attr('data-filter-selector');
        var issuesList = $('.error-list .issues > li, .warning-list .issues > li');

        issuesList.each(function () {
            var issue = $(this);
            var content = jQuery.trim($(filterSelector, issue).text());

            var hash = generateUniqueErrorHash(content);
            issue.attr('id', hash);

            if (window.location.hash) {
                var comparator = window.location.hash.replace('#', '');

                if (hash.indexOf(comparator) !== -1) {
                    $('body').addClass('filtered');
                    issue.addClass('highlight');
                } else {
                    issue.remove();
                }
            }
        });

        var body = $('body');

        if (body.is('.filtered') && window.location.hash) {
            var filteredError = $(filterSelector, $(issuesList).filter('.highlight').first()).html();

            $('.issue-content').prepend(
                $('<p class="filter-notice lead">Showing only <span class="message">"' + jQuery.trim(filteredError) + '"</span> errors. <a href="">Show all.</span></p>')
            );

            if (body.is('.html-validation')) {
                var fixesList = $('.fixes-list li');

                fixesList.each(function () {
                    var fix = $(this);
                    var content = jQuery.trim($('a', fix).text());

                    var hash = generateUniqueErrorHash(content);
                    var comparator = window.location.hash.replace('#', '');

                    if (hash.indexOf(comparator) === -1) {
                        fix.remove();
                    }
                });

                var errorCount = $('.error-list li').length + '';
                $('.error-list .alert-danger').text(errorCount);
                $('a[href=#errors] .count').text(errorCount);
                $('a[href=#errors] .name').text((errorCount == 1) ? 'error' : 'errors');

                var warningCount = $('.warning-list li').length + '';
                $('a[href=#warnings] .count').text(warningCount);
                $('a[href=#warnings] .name').text((warningCount == 1) ? 'warning' : 'warnings');

                if ($('.fixes-list li').length) {
                    $('.alert-fixes').text(1);
                    $('.label-fixes .count').text(1);
                    $('.label-fixes .name').text('fix');
                } else {
                    $('#fixes').remove();
                    $('a[href=#fixes]').remove();
                }
            }

            if (body.is('.css-validation') || body.is('.js-static-analysis')) {
                var errorCount = $('.error-list .issues li, .warning-list .issues li').length;
                $('h2 .alert-danger').text(errorCount);
                $('a[href=#errors] .count').text(errorCount);
                $('a[href=#errors] .name').text((errorCount == 1) ? 'error' : 'errors');

                $('.error-group').each(function () {
                    var listItem = $(this);
                    var errors = $('.error', listItem);

                    if (errors.length === 0) {
                        listItem.remove();
                        return;
                    }

                    $('.alert-danger', listItem).text(errors.length);
                });
            }

            if (body.is('.js-static-analysis')) {
                $('.grouped-issues .issues').each(function () {
                    var issuesList = $(this);

                    if ($('li', issuesList).length === 0) {
                        issuesList.closest('li').remove();
                    }
                });
            }

            if (body.is('.link-integrity')) {
                var errorCount = $('.error-list .issues > li').length;

                console.log(errorCount);

                $('h2 .alert-danger').text(errorCount);
                $('a[href=#errors] .count').text(errorCount);
                $('a[href=#errors] .name').text((errorCount == 1) ? 'error' : 'errors');

            }

            window.scrollTo(0, 0);
        }
    };


    if ($('.issue-content').attr('data-filter-selector')) {
        filterErrors();
    }


    $('.summary-stats a').click(function () {
        var target = $($(this).attr('href'));

        $.scrollTo(target, {
            'offset':-50
        });

        window.location.hash = target.attr('id');

        return false;
    });

    if ($(window.location.hash).length) {
        var target = $(window.location.hash);

        $.scrollTo(target, {
            'offset':-50
        });
    }

    prettyPrint();
});