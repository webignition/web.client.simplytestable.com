$(document).ready(function() {
    $('.summary-stats a').click(function () {
        var target = $($(this).attr('href'));

        $.scrollTo(target, {
            'offset':-50
        });
        
        return false;
    });
});