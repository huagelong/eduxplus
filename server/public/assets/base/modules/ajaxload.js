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
                if(!$.isEmptyObject(responseText.result)){
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
            },'json');
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
                time: 2500, //20s后自动关闭
                icon: icontype
            });
        }

    };

}(jQuery));