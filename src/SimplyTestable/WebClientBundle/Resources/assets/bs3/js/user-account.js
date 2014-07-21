$(document).ready(function() {

    var sideNav = $('#sidenav');
    var sideNavActiveScope = sideNav.attr('data-active-scope');

    if ($('.active', sideNavActiveScope).length === 0) {
        $('li:first', sideNavActiveScope).addClass('active');
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