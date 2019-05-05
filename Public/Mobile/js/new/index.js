$(function(){
	$(".footer li").each(function(i,item){
		$(item).click(function(){
			$(this).addClass('active').siblings().removeClass('active')
		})
	});
    if($(".itemdetail").length>0){
        var w= $(".itemdetail ul li").outerWidth();
        var len = $(".itemdetail ul li").length;

        $(".itemdetail ul").css("width",w*(len)+'px')
    }


});
