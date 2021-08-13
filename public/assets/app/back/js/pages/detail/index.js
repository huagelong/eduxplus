$(function(){

	var lesson_title = $('.js_lesson_title').text()+'-'+globOption.appName;
	document.title = lesson_title;
    // 课程tab切换
    $('.js_nav_secs>li').on('click',function(){
        var _this = $(this);
        if( !_this.hasClass('sec') ){
            var _i = _this.index();
            $('.js_nav_secs>li.sec').removeClass('sec');
            _this.addClass('sec');
            $('.js_lesson_msg .lesson_item').hide().eq(_i).show();
        }
    })

    $('.selectArea li').on('click',function(){
       $(this).toggleClass("li_selected").find('input');
       //todo更新价格

    })

    $(".js_buy").click(function(){

    });

})
