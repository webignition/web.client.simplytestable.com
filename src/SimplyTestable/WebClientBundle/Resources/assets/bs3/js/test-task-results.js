$(document).ready(function() {
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

    var issuesList = $('.error-list .issues li, .warning-list .issues li');

    issuesList.each(function () {
        var issue = $(this);
        var content = jQuery.trim($('p', issue).text());

        var hash = generateUniqueErrorHash(content);
        issue.attr('id', hash);

        if (window.location.hash) {
            var comparator = window.location.hash.replace('#', '');

            if (hash.indexOf(comparator) !== -1) {
                $('body').addClass('filtered');
                issue.addClass('highlight');
            }
        }
    });


    if ($('body').is('.filtered') && window.location.hash) {
        issuesList.not('.highlight').addClass('hidden');

        var fixesList = $('.fixes-list li');

        fixesList.each(function () {
            var fix = $(this);
            var content = jQuery.trim($('a', fix).text());

            var hash = generateUniqueErrorHash(content);
            var comparator = window.location.hash.replace('#', '');

            if (hash.indexOf(comparator) === -1) {
                fix.addClass('hidden');
            }
        });

        var errorCount = $('.error-list li').not('.hidden').length + '';
        $('.error-list .alert-danger').text(errorCount);
        $('a[href=#errors] .count').text(errorCount);
        $('a[href=#errors] .name').text((errorCount == 1) ? 'error' : 'errors');

        var warningCount = $('.warning-list li').not('.hidden').length + '';
        $('a[href=#warnings] .count').text(warningCount);
        $('a[href=#warnings] .name').text((warningCount == 1) ? 'warning' : 'warnings');

        if ($('.fixes-list li').not('.hidden').length) {
            $('.alert-fixes').text(1);
            $('.label-fixes .count').text(1);
            $('.label-fixes .name').text('fix');
        } else {
            $('#fixes').remove();
            $('a[href=#fixes]').remove();
        }

        var filteredError = $('p', $(issuesList).filter('.highlight').first()).html();

        $('.issue-content').prepend(
            $('<p class="filter-notice lead">Showing only <span class="message">"' + jQuery.trim(filteredError) + '"</span> errors. <a href="">Show all.</span></p>')
        );

        window.scrollTo(0, 0);
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

    $('h2').each(function () {
        var heading = $(this);
        var sectionName = heading.attr('data-section');

        if (sectionName === undefined) {
            return;
        }

        heading.append(
            '<span class="collapse-control link" data-toggle="collapse" data-target="#' + sectionName + '-content"><i class="fa fa-caret-up"></i></span>'
        );
    });

    $('.collapse-control').each(function () {
        var control = $(this);
        var detail = $(control.attr('data-target'));

        detail.on('shown.bs.collapse', function () {
            $('.fa', control).remove();
            control.append('<i class="fa fa-caret-up"></i>');
        });

        detail.on('hidden.bs.collapse', function () {
            $('.fa', control).remove();
            control.append('<i class="fa fa-caret-down"></i>');
        });
    });
});