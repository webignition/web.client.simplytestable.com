$(document).ready(function() {
    var WILDCARD = '*';
    var SLASH = '/';

    var modal = $('.modal');
    var clearButton = $('#clear-filter-button');
    var applyButton = $('#apply-filter-button');
    var closeButton = $('.close', modal);
    var closedBy = $('input[name=closed_by]', modal);
    var input = $('#input-filter', modal);

    $(input).keyup(function (event) {
        if (event.which === 13) {
            applyButton.click();
        }
    });

    applyButton.click(function () {
        closedBy.val('apply');
    });

    closeButton.click(function () {
        closedBy.val('close');
    });

    clearButton.click(function () {
        closedBy.val('clear');
    });

    modal.on('shown.bs.modal', function () {
        input.stFormHelper().select();

        $.get(input.attr('data-source-url'), function (data) {
            input.typeahead({
                'source':data
            });

            $('.typeahead').css({
                'z-index':2000
            });
        });
    });

    modal.on('hide.bs.modal', function (event) {
        var sourceFilter = jQuery.trim(input.val());
        if (sourceFilter === '') {
            input.val('');
            return;
        }

        var modifiedFilter = sourceFilter;

        var hasSchemePrefix = function (filter) {
            return (!!filter.match(/^(http|https):\/\//));
        };

        var hasWildcardPrefix = function () {
            return sourceFilter.substr(0, 1) === WILDCARD;
        };

        var hasWildcardSuffix = function () {
            return sourceFilter.substr(sourceFilter.length - 1) === WILDCARD;
        }

        var requiresWildcardPrefix = function () {
            return !hasSchemePrefix(modifiedFilter) && !hasWildcardPrefix();
        }

        // Prefix with * if not starting with scheme and not starting with *
        if (requiresWildcardPrefix()) {
            modifiedFilter = WILDCARD + modifiedFilter;
        }

        // Suffix with * if
        // - not ending with slash
        // - and not ending with .foo
        // - and not ending with *

        var hasTrailingSlash = function () {
            return sourceFilter.substr(sourceFilter.length - 1) === SLASH;
        }

        var getParsedUrl = function(href) {
            var l = document.createElement("a");
            l.href = href;
            return l;
        };

        var hasPathEnd = function () {
            var parseableFilter = sourceFilter;

            if (!hasSchemePrefix(parseableFilter)) {
                parseableFilter = 'http://' + parseableFilter;
            }

            var parsedUrl = getParsedUrl(parseableFilter);

            if (parsedUrl.pathname === SLASH || parsedUrl.pathname === '') {
                return hasTrailingSlash();
            }

            return !!parsedUrl.pathname.match(/(([^\.]+\.[^\.]+)+)\.[^\.]+\.?/);
        };

        var requiresWildcardSuffix = function () {
            return !hasPathEnd() && !hasWildcardSuffix();
        }

        if (requiresWildcardSuffix()) {
            modifiedFilter += '*';
        }

        input.val(modifiedFilter);
    });

    modal.on('hidden.bs.modal', function (event) {
        if (closedBy.val() == 'close') {
            return;
        }

        if (closedBy.val() == 'clear') {
            input.val('');
        }

        var filter = jQuery.trim(input.val());
        var search = (filter === '') ? '' : '?filter=' + filter;
        var currentSearch = window.location.search;

        if (currentSearch === '' && filter === '') {
            return;
        }

        if (filter === '') {
            $('.action-badge-filter-options').removeClass('action-badge-enabled');
        } else {
            $('.action-badge-filter-options').addClass('action-badge-enabled');
        }

        if (search !== currentSearch) {
            window.location.search = search;
        }
    });

    if ($('.requires-results').length > 0) {
        $('.requires-results').each(function () {
            $(this).stResultPreparer().init();
        });
    }
});