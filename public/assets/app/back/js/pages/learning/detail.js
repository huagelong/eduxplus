$(function(){
    $(".dialog_list_title span").click(function(){
      $(this).parent("div").siblings().toggle()
          // console.log();
        if($(this).hasClass("down")){
            $(this).removeClass("down");
        }else{
          $(this).addClass("down");
        }
    });
});
