$(function(){
  var carousel = layui.carousel;
  //建造实例
  carousel.render({
    elem: '#menuBanner'
    ,width: '100%' //设置容器宽度
    ,height: '405px'
    ,arrow: 'always' //始终显示箭头
  });

  $(".navCate li a").each(function(){
    var id = $(this).data("id");
    var vidId = "navCateDiv"+id;

    $(this).click(function() {
      $(".navCateDiv").addClass("layui-hide");
      $("#"+vidId).removeClass("layui-hide");
    });
    $(this).hover(function() {
      $(".navCateDiv").addClass("layui-hide");
      $("#"+vidId).removeClass("layui-hide");
    });
    $("#"+vidId).mouseleave(function() {
      $(".navCateDiv").addClass("layui-hide");
    });
    $(".bannerMenu").mouseleave(function() {
      $(".navCateDiv").addClass("layui-hide");
    });
  });

  $(".newsa").each(function(){
    $(this).hover(function() {
      $(this).children().first().addClass("layui-anim-scaleSpring");
    },function() {
      $(this).children().first().removeClass("layui-anim-scaleSpring");
    });
  });
})
