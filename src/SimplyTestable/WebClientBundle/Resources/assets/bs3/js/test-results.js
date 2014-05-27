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

    var sideActionTopDefaults = {};

    var storeSideActionTopDefaults = function () {
        $('.side-action').each(function () {
            var item = $(this);

            sideActionTopDefaults[item.attr('id')] = item.css('top');
        });
    };

    var setSideActionElementsAtSameOffsetAsTestSummary = function () {
        var summaryStatsOffset = $('.summary-stats').offset().top;

        $('.side-action').each(function () {
            var item = $(this);
            item.show().css({
                'visibility':'hidden'
            });

            var offset = item.offset();
            var diff = summaryStatsOffset + parseInt(item.attr('data-offset'), 10) - 147;

            item.css({
                'top':diff + 'px',
                'visibility':'visible'
            });
        });
    };

    storeSideActionTopDefaults();

    $( window ).resize(function() {

        console.log(getDeviceSize());

        if (getDeviceSize() === 'lg' || getDeviceSize() === 'md') {
            $($('.side-action').get(0)).css({
                'top':'0px'
            });

            $($('.side-action').get(1)).css({
                'top':'70px'
            });

            if ($('.side-action').length === 3) {
                $($('.side-action').get(2)).css({
                    'top':'140px'
                });
            }
        }

        if (getDeviceSize() === 'sm') {
            $($('.side-action').get(0)).css({
                'top':'145px'
            });

            $($('.side-action').get(1)).css({
                'top':'215px'
            });

            if ($('.side-action').length === 3) {
                $($('.side-action').get(2)).css({
                    'top':'285px'
                });
            }
        }

        storeSideActionTopDefaults();

        if (getDeviceSize() === 'sm') {
            if ($('#test-options.collapse.in').length) {
                setSideActionElementsAtSameOffsetAsTestSummary();
            }
        }
    });


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

    var maximumProseHeight = 0;

    $('.summary-prose-section').each(function () {
        var maximumBadgeWidth = 0;
        var section = $(this);

        if (section.height() > maximumProseHeight) {
            maximumProseHeight = section.height();
        }

        $('.badge', section).each(function () {
            var badge = $(this);
            if (badge.width() > maximumBadgeWidth) {
                maximumBadgeWidth = badge.width();
            }
        });

        $('.badge', section).each(function () {
            $(this).width(maximumBadgeWidth);
        });

    });

    $('.summary-prose-section').each(function () {
        $(this).height(maximumProseHeight);
    });

    if ($(window.location.hash).length) {
        var target = $(window.location.hash);

        $.scrollTo(target, {
            'offset':-100
        });
    }

    $('#test-options').on('show.bs.collapse', function (event) {
        if (getDeviceSize() === 'sm') {
            $('.side-action').hide();
        }
    });

    $('#test-options').on('shown.bs.collapse', function (event) {
        if (getDeviceSize() === 'sm') {
            setSideActionElementsAtSameOffsetAsTestSummary();
        }
    });

    $('#test-options').on('hide.bs.collapse', function (event) {
        if (getDeviceSize() === 'sm') {
            $('.side-action').hide();
        }
    });

    $('#test-options').on('hidden.bs.collapse', function (event) {
        if (getDeviceSize() === 'sm') {
            setSideActionElementsAtSameOffsetAsTestSummary();
        }
    });

});