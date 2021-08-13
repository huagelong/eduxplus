
// 配置登陆页等提示框样式

$(function(){
    var isMobile = isPc();
    function isPc() {
        var userAgentInfo = navigator.userAgent;
        var Agents=["Android","iPhone","SymbianOS", "Windows Phone","iPad", "iPod"];
        var flag=false;
        for(var v=0;v<Agents.length;v++) {
            if(userAgentInfo.indexOf(Agents[v])>0){
                flag=true;
                break;
            }
        }
        return flag;
    };

    // 移动端头部添加事件
    mobileHeadEvent();
    function mobileHeadEvent(){
        if( isMobile ){
            // 个人中心下拉
            $('.js_header_box').on('click', '.js_lear_mycourse', function(){
                var learning_lesson = $('.js_header_box').find('.learning_lesson');
                learning_lesson.toggle();
            })

            $('.js_header_box').on('click', '.js_already_login_box', function(){
                var personal_slide = $('.js_header_box').find('.js_personal_slide');
                personal_slide.toggle();
            })

            // $('.js_header_box .lear_center .learning_lesson, .js_header_box .already_login .personal_slide').on('click', function(e){
            //     general.stopBubble(e);  // 阻止冒泡
            // })

            // 左边学习中心
            $('.js_learning_navs_slide').on('click', function(){
                if( $(this).hasClass('sec_slide') ){
                    $(this).removeClass('sec_slide')
                    $(this).find('.js_learning_navs_list').stop(true,true).slideUp();
                }else{
                    $(this).addClass('sec_slide')
                    $(this).find('.js_learning_navs_list').stop(true,true).slideDown();
                }
            })
        }
    }

    // 侧边导航
    $(".js_nav_fixed .js_back_top").on("click",function(){
        $("html,body").animate({"scrollTop":0},300);
    });

    // 侧边导航置顶按钮显示
    topBtnShow();
    function topBtnShow(){
        var scroll_top = $(window).scrollTop();
        if(scroll_top>200){
            $(".js_nav_fixed .js_back_top").removeClass('exp_hide');
        }else{
            $(".js_nav_fixed .js_back_top").addClass('exp_hide');
        }

    }

    $(window).on('scroll', function(){
        topBtnShow();
    })


    // 移动端适配rem
    window.onresize = function(){               //修改resize
        seRem();
    }

    //限制刷新次数
    seRem();
    function seRem(){
        var oHtml = document.documentElement;
        var screenWidth = oHtml.clientWidth;
        if(screenWidth < 320){
            oHtml.style.fontSize = '20px';
        }else if(screenWidth > 640){
            oHtml.style.fontSize = '40px';
        }else{
            oHtml.style.fontSize = screenWidth/(720/40) + 'px';
        }
    }

    // 联系客服
    $('.js_doyoo_util').on('click', function(){
        var _ele = $("#icon_menu_module>.online_talk");
        if (_ele.length > 0) {
            _ele.trigger('click');
        }
    });


    var logincheck_timer = null;

    setTimeout(function () {
        if ($(".persona_center .opteion").length === 0) { //未登录不检测
            //@TODO 循环检测退出
            // multilogincheck();
        }
    }, 5 * 6 * 1000);

    //轮询是否被踢出
    function multilogincheck(){
        clearTimeout(logincheck_timer);
        //todo
        // general.subAjax({
        //     url: '/multilogincheck?r='+Math.random(),
        //     type: 'GET',
        //     is_error: false,
        //     success: function(data){
        //         if(data.result==1){  //被踢
        //             alert('你的账号在其他地方登录，你被踢出，请重新登录');
        //             window.location.href = '/login';
        //             return;
        //         }
        //         logincheck_timer = setTimeout(function(){
        //             multilogincheck();   // 轮询是否被踢出
        //         }, 5*60*1000);
        //     },
        //     fail: function(data){
        //         if(data.result==1){  //被踢
        //             alert('你的账号在其他地方登录，你被踢出，请重新登录');
        //             window.location.href = '/login';
        //             // layer.msg('你的账号在其他地方登录，你被踢出，请重新登录', {
        //             //     time: 2000,
        //             //     icon: 4
        //             // })
        //             return;
        //         }
        //         logincheck_timer = setTimeout(function(){
        //             multilogincheck();   // 轮询是否被踢出
        //         }, 5*60*1000);
        //     },
        //     error: function(){
        //         logincheck_timer = setTimeout(function(){
        //             multilogincheck();   // 轮询是否被踢出
        //         }, 5*60*1000);
        //     }
        //
        // })
    }




})
