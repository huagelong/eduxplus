(function ($) {
    $.fn.ajaxload = function() {
        $(this).each(function(){
            var chref = $(this).attr("href");
            $(this).click(function(){
                var isconfirm = $(this).data("confirm");
                if(isconfirm){
                    layer.msg(isconfirm, {
                        time: 0 //不自动关闭
                        ,btn: ['是', '否']
                        ,yes: function(index){
                            layer.close(index);
                            todo(chref);
                        }
                    });

                }else{
                    todo(chref);
                }
                return false;
            });
        });

        function todo(chref)
        {
            $.get(chref,{},function(responseText){
                if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
                if(!$.isEmptyObject(responseText.data)){
                    if(responseText.message){
                        show(responseText.code, responseText.message);
                    }
                    setTimeout(function(){
                        location.assign(responseText.data);
                    }, 1000);
                }else{
                    if(responseText.message){
                        show(responseText.code, responseText.message);
                    }
                }
            },'json');
        }

        function show(code, msg){
            var icontype = 4;
            code = code+'';
            var msgType = code.substr(0,1);
            switch(msgType){
                case 2:icontype=1;break;
                case 5:icontype=2;break;
                case 4:icontype=7;break;
                default :icontype = 4;
            }
            layer.msg(msg, {
                time: 2500, //20s后自动关闭
                icon: icontype
            });
        }

    };

}(jQuery));
