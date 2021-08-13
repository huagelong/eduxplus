$(function(){
  $(".moment").each(function(){
      // moment.locale("zh-cn");
      var time = $(this).data("time");
      if(time){
        var moment = timeago(time);
        $(this).text(moment)
      }
  });

  $(".gooda").each(function(){
    $(this).hover(function() {
      $(this).children().first().addClass("layui-anim-scaleSpring");
    },function() {
      $(this).children().first().removeClass("layui-anim-scaleSpring");
    });
  });

})
