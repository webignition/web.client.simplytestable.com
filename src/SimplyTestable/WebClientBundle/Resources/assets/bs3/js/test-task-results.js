$(document).ready(function() {
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