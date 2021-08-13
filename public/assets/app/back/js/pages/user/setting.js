$(function(){
        $('#file').ajaxfileupload({
            action: upyunurl,
            valid_extensions: ['png','gif', 'jpg', 'jpeg'],
            onComplete: function(response) {
                if(response.code=='200'){
                    imgurl = response.data[1];
                    $("#fileImg").attr('src', imgurl);
                    $("#gravatar").val(imgurl);
                }
            },
            onStart: function() {

            },
            onCancel: function() {
            }
        });

	//右边详情列表tab切换
	$('.js_nav-tabs li').click(function(){
		var index = $(this).index();
		$(this).addClass('sec').siblings().removeClass('sec')
		$('.js_question_right_selected .js_question_test').eq(index).addClass('pagationSide_test_show').siblings().removeClass('pagationSide_test_show')
	})


   //上传头像
    document.getElementById('file').onchange = function() {
        var imgFile = this.files[0];
        var fr = new FileReader();
        fr.onload = function() {
            document.getElementById('image').getElementsByTagName('img')[0].src = fr.result;
        };
        fr.readAsDataURL(imgFile);
    };


    //弹框显示
    $('.js_class_picker').on('click',function(){
        $('.js_punch_contain_box').removeClass('exp_hide');
    })
    $('.js_class_picker').on('click',function(){
        $('.js_punch_contain_box').removeClass('exp_hide');
    })
    //点击统计选择个数
    var countsClass = 0;
    if($('.punch_contain ul li.evaluate_sec').length){
        $('#js_submint').css({"background": "#2C8CFF","color": "#fff"})
    }else{
        $('#js_submint').css({'background':'#fff','color':'#666'})
    }
    $('.js_evaluate_box ul.exp_clear li').on('click',function(){
        //判断个数
        if($('.punch_contain ul li.evaluate_sec').length <= 2){
            if($(this).hasClass('evaluate_sec')){
                $(this).removeClass('evaluate_sec');
                countsClass--
            }else{
                $(this).addClass('evaluate_sec');
                countsClass++;
            }
        }else{
            if($(this).hasClass('evaluate_sec')){
                $(this).removeClass('evaluate_sec');
                countsClass--
            }
        }
        if(countsClass != 0){
            $('#js_submint').css({"background": "#2C8CFF","color": "#fff"})
        }else{
            $('#js_submint').css({'background':'#fff','color':'#666'})
        }
        if($('.punch_contain ul li.evaluate_sec').length){
            $('#js_submint').css({"background": "#2C8CFF","color": "#fff"})
        }else{
            $('#js_submint').css({'background':'#fff','color':'#666'})
        }

    })
})
