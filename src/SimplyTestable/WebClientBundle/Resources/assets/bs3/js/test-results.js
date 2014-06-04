$(document).ready(function() {
//    var getDeviceSize = function () {
//        if ($('#device-size-lg').css('visibility') === 'visible') {
//            return 'lg';
//        }
//
//        if ($('#device-size-md').css('visibility') === 'visible') {
//            return 'md';
//        }
//
//        if ($('#device-size-sm').css('visibility') === 'visible') {
//            return 'sm';
//        }
//
//        if ($('#device-size-xs').css('visibility') === 'visible') {
//            return 'xs';
//        }
//    }

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