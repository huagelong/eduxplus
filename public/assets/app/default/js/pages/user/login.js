$(function(){
  var is_smssend = false, smssend_interval = null;     //标记短信发送中

  $("#recaptcha_sms_login").captcha();

  $("#smssend").click(function(){
    if(is_smssend){     //短信发送中60s后发送
      return false;
    }

    var sms_mobile = $("#smsmobile").val();
    if(!sms_mobile) {
      layer.msg('手机号码不能为空!', {
        time: 2500, //2s后自动关闭
        icon: 2,
      });
      return false;
    }
    $("#mobilesend").val(sms_mobile);

    layer.open({
      title: '验证图形验证码',
      type: 1,
      content: $('#sms_model')
    });
    $('#sms_model').removeClass("layui-hide").
      //图片模拟点击
      $("#recaptcha_sms_login").trigger("click");
  });

  $(".ajaxsmsform").ajaxform({
    "success":function(responseJson){
      if (typeof responseJson == "string") var responseJson = $.parseJSON(responseJson);
      if(responseJson.code == '200'){
        // $("#sms_model").hide();
        $('#sms_model').addClass("layui-hide");
        layer.closeAll();
        smssendInterval();   // 开启倒计时
      }else{
        showMsg(responseJson.code, responseJson.message);
        //图片模拟点击
        $("#recaptcha_sms_login").trigger("click");
      }
    }
  });

  // 开启短信发送中状态定时器
  function smssendInterval(){
    is_smssend = true;      //开启倒计时状态
    clearInterval(smssend_interval);

    var time = 60;
    $('#smssend').text('请'+time+'s后重新获取')

    smssend_interval = setInterval(function(){
      if( time <= 1 ){
        clearInterval(smssend_interval);
        is_smssend = false;
        $('#smssend').text('点击发送验证码')
        return false;
      }
      time--;
      $('#smssend').text('请'+time+'s后重新获取')
    }, 1000);

  }

})
