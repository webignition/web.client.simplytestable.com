;(function() {

    var methods = {
        init: function () {

            $(this).each(function () {
                var modalAction = $(this);
                var modal = $('#' + modalAction.attr('data-for'));

                $('.modal-content', modal).css({
                    'display':'block'
                });

                if (modalAction.is('.modal-control')) {
                    var controlLink = $('<a href="#" data-for="' + modalAction.attr('data-for') + '">' + modalAction.html() + ' <i class="fa fa-caret-right"></i></a>');
                    modalAction.replaceWith(controlLink);

                    modalAction = controlLink;
                }

                modalAction.click(function (event) {
                    modal.modal({
                        backdrop: true,
                        keyboard: true
                    });
                    event.preventDefault();
                });
            });
        }
    };    

    $.fn.stModal = function() {
        for (var methodKey in methods) {
            if (methods.hasOwnProperty(methodKey)) {
                this[methodKey] = methods[methodKey];
            }
        }

        return this;
    };

})();