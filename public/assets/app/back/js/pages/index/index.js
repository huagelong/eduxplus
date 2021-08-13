$(function(){



    		var showIndex = 0;
			var timer;
			$(function() {

				//追加跑马效果
				for(var i=0;i<$("ul.uItems li").length;i++){
					var item = i+1;
					if(i == 0){
						var span = $('<li class="bg">'+item+'</li>');
					}else{
						var span = $('<li>'+item+'</li>');
					}

					$('ul.uIndex').append(span);
				}
				$("ul.uItems li").not(":eq(0)").css("display", "none");
				//当图片大于两个时开启轮播
				if($("ul.uItems li").length >1){
				  startTimer();
 				}
				$("ul.uIndex li").hover(function() {
					clearInterval(timer);

					showIndex = $(this).index();
					showImg();
				}, function() {
					startTimer();
				});

				$(".btnPrev").click(function() {
					clearInterval(timer);

					if(showIndex == 0) showIndex = $("ul.uItems li").length;
					showIndex--;
					showImg();
					startTimer();
				});

				$(".btnNext").click(function() {
					clearInterval(timer);

					if(showIndex == $("ul.uItems li").length-2) showIndex = -1;
					showIndex++;
					showImg();
					startTimer();
				});
			});

			function startTimer() {
				timer = setInterval(function() {
					showIndex++;
					if(showIndex >= $("ul.uItems li").length) showIndex = 0;
					showImg();
				}, 4000);
			}

			function showImg() {
				$("ul.uItems li").stop(true, true);
				$("ul.uItems li").fadeOut(400).eq(showIndex).fadeIn(400);
				$("ul.uIndex li").removeClass("bg").eq(showIndex).addClass("bg");
			}



})
