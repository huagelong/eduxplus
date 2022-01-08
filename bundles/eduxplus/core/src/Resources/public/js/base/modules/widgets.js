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

    $.fn.msgtips = function() {
        $(this).each(function(){
            $(this).css("cursor", "pointer");
            $(this).click(function(){
                var title = $(this).attr("title");
                layer.open({
                    type: 1,
                    skin: "lyear-skin-info",
                    area: ['420px', '240px'], //宽高
                    content: title
                  });
            });
        });
    };

    $.fn.popPage = function(options){
        var that = this;
        $(that).each(function(){
            $(this).click(function(){
                var chref = $(this).attr('href');
                var title = $(this).data('title');
                var width = $(this).data('width');
                var height = $(this).data('height');

                width = width?width:"800px";
                height = height?height:"600px";
                layer.open({
                    type: 2,
                    title: title,
                    skin: "lyear-skin-info",
                    closeBtn: 1, //不显示关闭按钮
                    shade: [0],
                    area: [width, height],
                    anim: 2,
                    content: [chref, 'yes'], //iframe的url，no代表不显示滚动条
                });
                return false;
            });
        });

    }

}(jQuery));
