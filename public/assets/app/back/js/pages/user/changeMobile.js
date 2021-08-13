$(function(){
    var is_smssend1 = false, is_smssend2 = false, smssend_interval1 = null, smssend_interval2 = null;     //标记短信发送中
    // 可以提交
    $('.js_disabled_subbtn').attr('disabled', false);

    // 验证码图片
    $(".js_pic_recaptcha").each(function(i,el){
        $(el).captcha();
    })

    $(".js_pic_recaptcha").trigger("click");


    $(".js_login_settime").click(function(){
        var sms_mobile = $(".js_sub_phone").val();
        var imgcode = $(".imgCode").val();
        if(!imgcode) return showMsg(400, "图形验证码不能为空!");
        var imgType = $(".imgType").val();

        data = {
            mobile:sms_mobile,
            type:imgType,
            imgCode:imgcode
        };
        url = globOption.appSendCaptchaUrl;
        dataStr = JSON.stringify(data);
        $.postJSON(url, dataStr, function(responseText){
            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);

            if(responseText.message){
                showMsg(responseText.code, responseText.message);
            }

            if(responseText.code == '200'){
                //倒计时
                smssendInterval(2);   // 开启倒计时
            }
        },'json');

        $(".js_pic_recaptcha").trigger("click");
    });


    // 开启短信发送中状态定时器
    function smssendInterval(_type){
        var time_ele = $('.js_login_settime');
        if(_type==2){
            is_smssend2 = true;
            clearInterval(smssend_interval2);
            time_ele = $('.js_sub_form_typ2 .js_login_settime');    //倒计时dom
        }else{
            is_smssend1 = true;
            clearInterval(smssend_interval1);
            time_ele = $('.js_sub_formc_ph1 .js_login_settime');    //倒计时dom
        }

        var time = 60;
        time_ele.text('请'+time+'s后重新获取')

        smssend_interval = setInterval(function(){
            if( time <= 1 ){
                if(_type==2){
                    clearInterval(smssend_interval2);
                    is_smssend2 = false;
                }else{
                    is_smssend1 = false;
                    clearInterval(smssend_interval1);
                }
                time_ele.text('点击发送验证码')
                return false;
            }
            time--;
            time_ele.text('请'+time+'s后重新获取')
        }, 1000);
    }

});
