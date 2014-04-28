;(function() {
    
    var methods = {        
        hasError: function () {            
            return $('[data-for=' + $(this).attr('id') + ']').length > 0;   
       
        },
        
        focusFieldOnClear: function () {            
            $('.close', this).click(function () {                
                $('#' + $(this).parent().attr('data-for')).stFormHelper().select();
            });
            
            return this;
        }
    };    

    $.fn.stFormErrorHelper = function() {
        for (var methodKey in methods) {
            if (methods.hasOwnProperty(methodKey)) {
                this[methodKey] = methods[methodKey];
            }
        }

        return this;
    };

})();