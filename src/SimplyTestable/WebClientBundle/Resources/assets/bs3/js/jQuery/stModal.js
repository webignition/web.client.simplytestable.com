;(function() {

    var methods = {
        init: function () {
            var modalControl = $(this);
            var modal = $('#' + modalControl.attr('data-for'));

            $('.modal-content', modal).css({
                'display':'block'
            });
            var controlLink = $('<a href="#" class="expandable-control-action">' + modalControl.html() + ' <i class="fa fa-caret-right"></i></a>');
            modalControl.replaceWith(controlLink);

            controlLink.click(function (event) {
                modal.modal({
                    backdrop: true,
                    keyboard: true
                });
                event.preventDefault();
            });

            return this;
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