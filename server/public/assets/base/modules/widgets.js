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

    $.fn.popPage = function(options){
        var settings = $.extend({
            width:"600px",
            height:  '400px'
        }, options);

        var that = this;
        $(that).each(function(){
            $(this).click(function(){
                var chref = $(this).attr('href');
                var title = $(this).data('title');

                layer.open({
                    type: 2,
                    title: title,
                    closeBtn: 1, //不显示关闭按钮
                    shade: [0],
                    area: [settings.width, settings.height],
                    anim: 2,
                    content: [chref, 'yes'], //iframe的url，no代表不显示滚动条
                });
                return false;
            });
        });

    }

}(jQuery));
