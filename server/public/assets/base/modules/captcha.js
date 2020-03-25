(function ($) {
    //图像处理
    $.fn.captcha = function() {
        var chref = $(this).attr("src");
        $(this).css("cursor", "pointer");
        var chrefTmp = chref+"?"+parseInt(9999*Math.random());
        $(this).attr("src", chrefTmp);
        $(this).click(function(){
            var chrefTmp2 = chref+"?"+parseInt(9999*Math.random());
            $(this).attr("src", chrefTmp2);
        });
    };

    //检查验证码
    $.fn.checkcaptcha = function(options) {
        /**
         * checkurl 检查验证码网址
         * errorId 检查错误的时候给出提示的对象
         */
        var settings = $.extend({
            checkurl: "",
            errorId:"",
            errorClass:"am-form-error",
        }, options);

        $(this).focusout(function(){
            var vl = $(this).val();
            $.post(settings.checkurl, {vl:vl},function(data){
                if(data.statusCode != "200"){
                    $(settings.errorId).addClass(settings.errorClass);
                }else{
                    $(settings.errorId).removeClass(settings.errorClass);
                }
            });
        });
    };

}(jQuery));