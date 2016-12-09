//表格奇偶背景（鼠标移上效果）////// 
$(function() {//定义函数 
  $('.rbox_list table tr').hover( 
    function(){
       $(this).addClass("tr_hover");
	   $(this).removeClass("tr_off"); 
    }, 
    function(){
	   $(this).addClass("tr_off");
       $(this).removeClass("tr_hover");
    } 
  ); 
}); 
