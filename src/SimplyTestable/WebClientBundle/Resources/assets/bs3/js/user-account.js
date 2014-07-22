$(document).ready(function() {

    var sideNav = $('#sidenav');
    var sideNavActiveScope = sideNav.attr('data-active-scope');

    if ($('.active', sideNavActiveScope).length === 0) {
        $('li:first', sideNavActiveScope).addClass('active');
    }

    $('.sidenav a').click(function () {
        var href = $(this).attr('href');
        if (href.substr(0, 1) === '#') {
            var target = $(href);
            if (target.length) {
                $.scrollTo(target, {
                    'offset':-80
                });

                window.location.hash = target.attr('id');

                return false;
            }
        }
    });

    if ($(window.location.hash).length) {
        var target = $(window.location.hash);
        if (target.length) {
            $.scrollTo(target, {
                'offset':-80
            });
        }
    }

});