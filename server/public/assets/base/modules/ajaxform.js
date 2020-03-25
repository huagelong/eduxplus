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
                $("input,textarea", this).each(function(){
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

            });

            if(check){
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var csrf_uri_token = $('meta[name="csrf-uri-token"]').attr('content');
                if(csrf_token){
                    $.ajaxPrefilter(function(options, originalOptions, jqXHR){
                        jqXHR.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                        jqXHR.setRequestHeader('X-CSRF-URI-TOKEN', csrf_uri_token);
                    });
                }
            }

            return check;
        }

        function showResponse(responseText, statusText, xhr, jqForm)  {
            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
            $("button[type='submit']", jqForm).attr("disabled", false);
            if(responseText.statusCode == '200'){
                var form_name = $.Cookie('form_name');
                form_name = form_name?form_name:"form";
                $.Cookie(form_name, 1);
            }
            if((!$.isEmptyObject(responseText.result)) && (!$.isPlainObject(responseText.result))){
                if(responseText.message.msg){
                    show(responseText.message.msgType, responseText.message.msg);
                }
                setTimeout(function(){
                    location.assign(responseText.result);
                }, 1000);
            }else{
                if(responseText.message.msg){
                    show(responseText.message.msgType, responseText.message.msg);
                }
            }

            //请求成功的操作
          if(settings.success) (settings.success)(responseText);

            return false;
        }

        function show(msgType, msg){
            var icontype = 4;
            switch(msgType){
                case "tinfo":icontype=4;break;
                case "tsuccess":icontype=1;break;
                case "terror":icontype=2;break;
                case "twarning":icontype=7;break;
                default :icontype = 4;
            }
            layer.msg(msg, {
                time: 2500, //2s后自动关闭
                icon: icontype,
                offset: '100px', //右下角弹出
            });
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