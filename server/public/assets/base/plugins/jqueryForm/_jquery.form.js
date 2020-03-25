(function ($) {
    $.fn.jqueryform=function(options){
        var settings = $.extend({
            target:"",
            dataType:  'json',        // 'xml', 'script', or 'json' (expected server response type)
            clearForm: false,        // clear all form fields after successful submit
            resetForm: false,       // reset the form after successful submit
            timeout:   3000,
            inputParent: ".form-group"
        }, options);

        var options = {
            target:        settings.target,   // target element(s) to be updated with server response
            beforeSubmit:  showRequest,  // pre-submit callback
            success:       showResponse,  // post-submit callback
            dataType:  settings.dataType,        // 'xml', 'script', or 'json' (expected server response type)
            clearForm: settings.clearForm,        // clear all form fields after successful submit
            resetForm: settings.resetForm,       // reset the form after successful submit
            timeout:   settings.timeout,
            // iframe:true,
        };

        function showRequest(formData, jqForm, options) {
            
            $("button[type='submit']", jqForm).attr("disabled", true);

            var check = true;
            $(settings.inputParent, jqForm).each(function(){
                var field = $(this);
                $("input", this).each(function(){
                    var required = $(this).data("required");
                    var vl = $(this).val();
                    if(required){
                        if (vl === null || vl === undefined || vl === '') {
                            field.addClass("has-error");
                            check = false;
                            $("button[type='submit']", jqForm).attr("disabled", false);
                        }else{
                            field.removeClass("has-error");
                        }
                    }else{
                        field.removeClass("has-error");
                    }

                    $(this).focusout(function(){
                        var required = $(this).data("required");
                        var vl = $(this).val();
                        if(required && vl){
                            field.removeClass("has-error");
                        }
                    });
                });

                var required = $(this).find("input").data(required);
            });

            return check?true:false;
        }
        
        function showResponse(responseText, statusText, xhr, jqForm)  {
            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
            $("button[type='submit']", jqForm).attr("disabled", false);

            if(!$.isEmptyObject(responseText.result)){
                if(responseText.msg.msg){
                    show(responseText.msg.msgType, responseText.msg.msg);
                }
                setTimeout(function(){
                    location.assign(responseText.result);
                }, 1000);
            }else{
                if(responseText.msg.msg){
                    show(responseText.msg.msgType, responseText.msg.msg);
                }
            }
            
            return false;
        }

        function show(msgType, msg){
            switch(msgType){
                case "tinfo":tinfo(msg);break;
                case "tsuccess":tsuccess(msg);break;
                case "terror":terror(msg);break;
                case "twarning":twarning(msg);break;
                default :tinfo(msg);
            }
        }

        $(this).each(function(){
            $(this).submit(function() {
                $(this).ajaxSubmit(options);
                return false;
            });
        });
    }
}(jQuery));