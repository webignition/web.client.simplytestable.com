$(document).ready(function() {
    var greatestBadgeWidth = 0;
    var greatestBadgeOuterWidth = 0;

    $('.task-type-summary .badge').each(function () {
        var badge = $(this);
        if (badge.width() > greatestBadgeWidth) {
            greatestBadgeWidth = badge.width();
        }

        if (badge.outerWidth() > greatestBadgeOuterWidth) {
            greatestBadgeOuterWidth = badge.outerWidth();
        }
    });

    $('.task-type-summary .badge').each(function () {
        $(this).width(greatestBadgeWidth);
    });

    $('.task-type-summary .not-available-icon').each(function () {
        $(this).width(greatestBadgeOuterWidth);
    });

    $('#retest-button').click(function () {
        var button = $(this);
        $('.fa', button).removeClass('fa-refresh').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });

        $('#retest-form').submit();
    });

    $('.summary-stats a').click(function () {
        var target = $($(this).attr('data-target'));

        $.scrollTo(target, {
            'offset':-100
        });

        window.location.hash = target.attr('id');

        return false;
    });

    if ($(window.location.hash).length) {
        var target = $(window.location.hash);

        $.scrollTo(target, {
            'offset':-100
        });
    }

    $('#lock-unlock-button').click(function () {
        var button = $(this);
        $('.fa', button).removeClass('fa-lock').removeClass('fa-unlock').addClass('fa-spinner fa-spin');
        button.animate({
            'opacity':0.6
        });
    });

});