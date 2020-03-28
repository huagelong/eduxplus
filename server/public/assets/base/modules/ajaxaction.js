(function ($) {
    $.fn.ajaxDelete = function() {
        $(this).each(function(){
            var chref = $(this).attr("href");
            $(this).click(function(){
                    layer.msg(isconfirm, {
                        time: 0 //不自动关闭
                        ,btn: ['是', '否']
                        ,yes: function(index){
                            layer.close(index);
                            todoDelete(chref);
                        }
                    });
                return false;
            });
        });

        function todoDelete(chref)
        {
            $.deleteJSON(chref,{},function(responseText){
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

    };

    $.fn.ajaxPut = function() {
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
                            todoPut(chref);
                        }
                    });

                }else{
                    todoPut(chref);
                }
                return false;
            });
        });

        function todoPut(chref)
        {
            $.putJSON(chref,{},function(responseText){
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

    };


}(jQuery));
