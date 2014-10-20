$(document).ready(function() {

    $('.by-error-list li.error').each(function () {
        var container = $(this);
        var list = $('ul', container);

        if ($('li', list).length > 10) {
            var controller = $('p', container);
            list.addClass('collapse');

            controller.click(function () {
                list.collapse('toggle');
            }).text(controller.text().replace(':', '')).css({
                'cursor':'pointer'
            }).append(
                $('<a class="caret-container" href="#"><i class="fa fa-caret-down"></i> show page list</a>')
            );

            list.on('shown.bs.collapse', function () {
                $('a', controller).remove();
                controller.append(
                    $('<a class="caret-container" href="#"><i class="fa fa-caret-up"></i> hide page list</a>')
                );
            }).on('hidden.bs.collapse', function () {
                $('a', controller).remove();
                controller.append(
                    $('<a class="caret-container" href="#"><i class="fa fa-caret-down"></i> show page list</a>')
                );
            });
        }
    });

});