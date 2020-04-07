(function ($) {
    $.fn.ajaxDelete = function() {
        $(this).each(function(){
            var chref = $(this).attr("href");
            $(this).click(function(){
                var isconfirm = $(this).data("confirm");
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
            $.postJSON(chref,{},function(responseText){
                if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
                if(!$.isEmptyObject(responseText.data)){
                    if(responseText.message){
                        show(responseText.code, responseText.message);
                    }

                    setTimeout(function(){
                        if (window.frames.length != parent.frames.length){
                            parent.location.assign(responseText.data._url);
                        }else{
                            location.assign(responseText.data._url);
                        }
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
            $.postJSON(chref,{},function(responseText){
                if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
                if(!$.isEmptyObject(responseText.data)){
                    if(responseText.message){
                        show(responseText.code, responseText.message);
                    }
                    setTimeout(function(){
                        if (window.frames.length != parent.frames.length){
                            parent.location.assign(responseText.data._url);
                        }else{
                            location.assign(responseText.data._url);
                        }
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
