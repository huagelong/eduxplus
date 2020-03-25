(function ($) {
    $.fn.ajaxload = function() {
        $(this).each(function(){
            $(this).click(function(){
                var chref = $(this).attr("href");
                $.get(chref,{},function(responseText){
                    // var responseText = JSON.parse(responseTextTmp);
                    if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
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
                },'json');
                return false;
            });
        });
        function show(msgType, msg){
            switch(msgType){
                case "tinfo":tinfo(msg);break;
                case "tsuccess":tsuccess(msg);break;
                case "terror":terror(msg);break;
                case "twarning":twarning(msg);break;
                default :tinfo(msg);
            }
        }

    };

}(jQuery));