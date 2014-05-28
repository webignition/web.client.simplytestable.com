$(document).ready(function() {
    var getDeviceSize = function () {
        if ($('#device-size-lg').css('visibility') === 'visible') {
            return 'lg';
        }

        if ($('#device-size-md').css('visibility') === 'visible') {
            return 'md';
        }

        if ($('#device-size-sm').css('visibility') === 'visible') {
            return 'sm';
        }

        if ($('#device-size-xs').css('visibility') === 'visible') {
            return 'xs';
        }
    }

//    var setSideActionElementsAtSameOffsetAsTestSummary = function (additionalOffset) {
//        if (additionalOffset === undefined) {
//            additionalOffset = 15;
//        }
//
//        var summaryStatsTop = $('.summary-stats').position().top
//
//        $('.side-action').each(function () {
//            var item = $(this);
//            item.show().css({
//                'visibility':'hidden'
//            });
//
//            var newOffset = summaryStatsTop + parseInt(item.attr('data-offset'), 10) + additionalOffset;
//
//            item.css({
//                'top':newOffset + 'px',
//                'visibility':'visible'
//            });
//        });
//    };

//    if (['lg', 'md'].indexOf(getDeviceSize()) !== -1) {
//        setSideActionElementsAtSameOffsetAsTestSummary(-90);
//    }
//
//    if (getDeviceSize() === 'sm') {
//        setSideActionElementsAtSameOffsetAsTestSummary();
//    }

//    $( window ).resize(function() {
//        if (['lg', 'md'].indexOf(getDeviceSize()) !== -1) {
//            setSideActionElementsAtSameOffsetAsTestSummary(-90);
//        }
//
//        if (getDeviceSize() === 'sm') {
//            setSideActionElementsAtSameOffsetAsTestSummary();
//        }
//    });


    $('.collapse-control').each(function () {
        var control = $(this);
        var detail = $(control.attr('data-target'));

        detail.on('shown.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.fa', control).remove();
            control.append(' <i class="fa fa-caret-up"></i>');
        });

        detail.on('hidden.bs.collapse', function (event) {
            var target = $(event.target);
            var control = $('[data-target=#' + target.attr('id') + ']');

            $('.fa', control).remove();
            control.append(' <i class="fa fa-caret-down"></i>');
        });
    });

    var greatestBadgeWidth = 0;

    $('.task-type-summary .badge').each(function () {
        var badge = $(this);
        if (badge.width() > greatestBadgeWidth) {
            greatestBadgeWidth = badge.width();
        }
    });

    $('.task-type-summary .badge').each(function () {
        $(this).width(greatestBadgeWidth);
    });

//    var maximumProseHeight = 0;
//
//    $('.summary-prose-section').each(function () {
//        var maximumBadgeWidth = 0;
//        var section = $(this);
//
//        if (section.height() > maximumProseHeight) {
//            maximumProseHeight = section.height();
//        }
//
//        $('.badge', section).each(function () {
//            var badge = $(this);
//            if (badge.width() > maximumBadgeWidth) {
//                maximumBadgeWidth = badge.width();
//            }
//        });
//
//        $('.badge', section).each(function () {
//            $(this).width(maximumBadgeWidth);
//        });
//
//    });
//
//    $('.summary-prose-section').each(function () {
//        $(this).height(maximumProseHeight);
//    });

    if ($(window.location.hash).length) {
        var target = $(window.location.hash);

        $.scrollTo(target, {
            'offset':-100
        });
    }

//    $('#test-options').on('show.bs.collapse', function (event) {
//        if (getDeviceSize() === 'sm') {
//            $('.side-action').hide();
//        }
//    });
//
//    $('#test-options').on('shown.bs.collapse', function (event) {
//        if (getDeviceSize() === 'sm') {
//            setSideActionElementsAtSameOffsetAsTestSummary();
//        }
//    });
//
//    $('#test-options').on('hide.bs.collapse', function (event) {
//        if (getDeviceSize() === 'sm') {
//            $('.side-action').hide();
//        }
//    });
//
//    $('#test-options').on('hidden.bs.collapse', function (event) {
//        if (getDeviceSize() === 'sm') {
//            setSideActionElementsAtSameOffsetAsTestSummary();
//        }
//    });

    if ($('#test-url').text().indexOf('…') !== -1) {
        $('h1').addClass('abridged').addClass('is-out');
    }

    $('h1').click(function () {
        var url = $('#test-url');
        var heading = $('h1');

        if (heading.is('.abridged')) {
            if (heading.is('.is-out')) {
                heading.attr('data-abridged', url.text());
                url.text(url.attr('title'));
                heading.removeClass('is-out').addClass('is-in');
            } else {
                url.text(heading.attr('data-abridged'));
                heading.removeClass('is-in').addClass('is-out');
            }
        }

        if (['lg', 'md'].indexOf(getDeviceSize()) !== -1) {
            setSideActionElementsAtSameOffsetAsTestSummary(-90);
        }

        if (getDeviceSize() === 'sm') {
            setSideActionElementsAtSameOffsetAsTestSummary();
        }
    });

});