$(document).ready(function() {

    if ($('.sidenav .active').length === 0) {
        $('.sidenav li:first').addClass('active');
    }

    $('.sidenav a').click(function () {
        var target = $($(this).attr('href'));

        $.scrollTo(target, {
            'offset':-80
        });

        window.location.hash = target.attr('id');

        return false;
    });

    if ($(window.location.hash).length) {
        var target = $(window.location.hash);

        $.scrollTo(target, {
            'offset':-80
        });
    }

});