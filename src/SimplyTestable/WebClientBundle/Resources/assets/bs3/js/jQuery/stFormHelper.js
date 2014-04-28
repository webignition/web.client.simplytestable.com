;(function() {
    
    var methods = {
        isEmpty: function() {            
            var returnValue = null;
            
            this.each(function() {
                returnValue = jQuery.trim($(this).val()) === '';
            });            
            
            return returnValue;
        },
        
        select: function () {
            var field = $(this), oldVal = field.val();
            field.focus().val('').val(oldVal);                
        }
    };    

    $.fn.stFormHelper = function() {
        for (var methodKey in methods) {
            if (methods.hasOwnProperty(methodKey)) {
                this[methodKey] = methods[methodKey];
            }
        }

        return this;
    };

})();