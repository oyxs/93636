$(document).ready(function(){
$('.game_list ul li .one').each(function(i){
$(this).hover(function(){
			$(this).parent().find(".d_txt").css("display", "none");
			$(this).parent().find(".push").css("display", "block");
		},function(){
			$(this).parent().find(".d_txt").css("display", "block");
			$(this).parent().find(".push").css("display", "none");
	 }); 
  }); 
//Ê×Ò³ÅÅÐÐ°ñ
	$(".rank-cont:eq(0) .rank-cont-ul li").hover(function(){
		$(".rank-cont:eq(0) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".rank-cont:eq(1) .rank-cont-ul li").hover(function(){
		$(".rank-cont:eq(1) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".rank-cont:eq(2) .rank-cont-ul li").hover(function(){
		$(".rank-cont:eq(2) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".rank-cont:eq(3) .rank-cont-ul li").hover(function(){
		$(".rank-cont:eq(3) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
//µ¯´°
  $(".install .bule").click(function() {
	 var title=$(this).attr("data-title");
	 var thumb=$(this).attr("data-thumb");
	 $(".data-title").text(title);
	 $(".data_thumb").attr('src',thumb);
	 $(".ins_phone").show(0);
  });
   $(".ui-dialog .cloes").click(function() {
	 $(".ins_phone").hide(0);
  });
//ÅÅÐÐÇÐ»»
 $(".rank-tit span").click(function() {
		i = $(".rank-tit span").index(this);
		$(".rank-tit span").removeClass("on");
		$(this).addClass("on");
		$(".rank-cont ul").removeClass("on");
		$(".rank-cont").find(" ul:eq(" + i + ")").addClass("on");
  });
  $(".info li").click(function() {
		i = $(".info li").index(this);
		$(".info li").removeClass("on");
		$(this).addClass("on");
		$(".main .game_list ul").removeClass("on");
		$(".main .game_list").find(" ul:eq(" + i + ")").addClass("on");
 });	
  $(".list span").click(function() {
		i = $(".list span").index(this);
		$(".list span").removeClass("on");
		$(this).addClass("on");
		$(".w_list .words").removeClass("on");
		$(".w_list").find(".words:eq(" + i + ")").addClass("on");
 });	
 // ¶þÎ¬Âë
 $(".QR_code").hover(function(){
	    $(this).find(".CR").show(0);
	},function(){
		$(this).find(".CR").hide(0);
  });
  // Ð¡±àÍÆ¼·
	$('.air ul li .pop').each(function(i){
	$(this).hover(function(){
				$(this).parent().find(".down").css("display", "none");
				$(this).parent().find(".samlls").css("display", "block");
			},function(){
				$(this).parent().find(".down").css("display", "block");
				$(this).parent().find(".samlls").css("display", "none");
		 }); 
	  });
});					   
							   