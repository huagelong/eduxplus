(function ($) {
    //tip
    $.fn.tips = function() {
        $(this).each(function(){
            $(this).css("cursor", "pointer");
            $(this).click(function(){
                var title = $(this).attr("title");
                layer.tips(title, $(this), {
                    tips: [1, '#333643'],
                    time: 2500
                });
            });
        });
    };

    $.fn.ajaxpage = function(options){
        var settings = $.extend({
            width:"420px",
            height:  '240px'
        }, options);

        var that = this;
        $(that).each(function(){
            $(this).click(function(){
                var chref = $(this).attr('href');
                var title = $(this).data('title');
                $.get(chref,{},function(responseText){
                    if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
                    if(!$.isEmptyObject(responseText.result)){
                        if(responseText.message.msg){
                            show(responseText.message.msgType, responseText.message.msg);
                        }else{
                            layer.open({
                                title: title,
                                type: 1,
                                area: [settings.width, settings.height], //宽高
                                content: responseText.result
                            });
                        }
                    }else{
                        if(responseText.message.msg){
                            show(responseText.message.msgType, responseText.message.msg);
                        }
                    }
                },'json');
                return false;
            });
        });

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

    }

}(jQuery));