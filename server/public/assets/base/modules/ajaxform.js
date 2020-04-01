(function ($) {
    $.fn.ajaxform=function(options){
        var settings = $.extend({
            target:"",
            dataType:  'json',        // 'xml', 'script', or 'json' (expected server response type)
            clearForm: false,        // clear all form fields after successful submit
            resetForm: false,       // reset the form after successful submit
            timeout:   10000,
            inputParent: ".form-group",
            submitPre:null,
            submitType:"form",
            success:null
        }, options);

        var options = {
            target:        settings.target,   // target element(s) to be updated with server response
            beforeSubmit:  showRequest,  // pre-submit callback
            success:       showResponse,  // post-submit callback
            error:         showError,
            dataType:  settings.dataType,        // 'xml', 'script', or 'json' (expected server response type)
            clearForm: settings.clearForm,        // clear all form fields after successful submit
            resetForm: settings.resetForm,       // reset the form after successful submit
            timeout:   settings.timeout,
            // iframe:true,
        };

        function showError(xhr, status, error, jqForm){
            if(xhr.status == 500){
                show(500, xhr.statusText);
                $("button[type='submit']", jqForm).attr("disabled", false);
                return ;
            }
            var responseText = xhr.responseText;

            console.log(xhr);

            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
            if(responseText.code != '200'){
                if(responseText.message){
                    show(responseText.code, responseText.message);
                }
            }
            $("button[type='submit']", jqForm).attr("disabled", false);
        }

        function showRequest(formData, jqForm, options) {
            $("button[type='submit']", jqForm).attr("disabled", true);
            var check = true;
            $(settings.inputParent, jqForm).each(function(){

                var field = $(this);
                $("input,textarea", this).each(function(){
                    var required = $(this).data("required");
                    var vl = $(this).val();
                    if(required){
                        if (vl === null || vl === undefined || vl === '') {
                            field.addClass("has-error");
                            check = false;
                            $("button[type='submit']", jqForm).attr("disabled", true);
                            $(this).focus();
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
                            $("button[type='submit']", jqForm).attr("disabled", false);
                        }
                    });
                });

            });


            return check;
        }

        function showResponse(responseText, statusText, xhr, jqForm)  {
            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
            $("button[type='submit']", jqForm).attr("disabled", false);
            if(responseText.code == '200'){
                var form_name = $.Cookie('form_name');
                form_name = form_name?form_name:"form";
                $.Cookie(form_name, 1);
            }
            if((!$.isEmptyObject(responseText.data)) && (!$.isPlainObject(responseText.data))){
                if(responseText.message){
                    show(responseText.code, responseText.message.msg);
                }
                setTimeout(function(){
                    location.assign(responseText.data);
                }, 1000);
            }else{
                if(responseText.message){
                    show(responseText.code, responseText.message);
                }
            }

            //请求成功的操作
          if(settings.success) (settings.success)(responseText);
            return false;
        }

        $(this).each(function(){
            if(settings.submitType=='form'){
                $(this).submit(function() {
                    $(this).ajaxSubmit(options);
                    return false;
                });
            }else{
                $(this).click(function() {
                    if(settings.submitPre){
                        (settings.submitPre)($(this));
                    }
                    var parent = $(this).parents('form').first();
                    // console.log(parent);
                    parent.ajaxSubmit(options);
                    return false;
                });
            }

        });
    }
}(jQuery));
