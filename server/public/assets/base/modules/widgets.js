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
                    if(!$.isEmptyObject(responseText.data)){
                        if(responseText.message){
                            show(responseText.code, responseText.message);
                        }else{
                            layer.open({
                                title: title,
                                type: 1,
                                area: [settings.width, settings.height], //宽高
                                content: responseText.data
                            });
                        }
                    }else{
                        if(responseText.message){
                            show(responseText.code, responseText.message);
                        }
                    }
                },'json');
                return false;
            });
        });

    }

}(jQuery));
