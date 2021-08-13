$(function(){

    //下拉
    $('.js_list_slide').on('click',function(){
        var _li = $(this).parents('.js_slide_li');
        if( _li.hasClass('list_slide') ){
            _li.removeClass('list_slide').find('.js_slide_ul').stop(true,true).slideUp('normal');
        }else{
            _li.addClass('list_slide').find('.js_slide_ul').stop(true,true).slideDown('normal');
        }
    })


    if(isMobile){
        $('.js_active_detail_boxs').on('click', function(){
            $(this).find('div').toggle();
        })
    }


    $('.js_active_detail_slids_close').on('click', function(){
        $(this).parents('.js_active_detail_slids').stop(true,true).slideUp();
    });


    //划过时标题颜色变化  pc 端
    $('.now_down_check').find('.check_msg_name').on('mouseenter',function(){
    	if($(this).attr('href') !== 'javascript:void(0);'){
    		$(this).addClass('check_msg_name_hover')
    	}
    })
    $('.now_down_check').find('.check_msg_name').on('mouseleave',function(){
    	if($(this).attr('href') !== 'javascript:void(0);'){
    		$(this).removeClass('check_msg_name_hover')
    	}
    })



})
