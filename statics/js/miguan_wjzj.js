$(document).ready(function(){
	//全站导航
	$(".global-nav").hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	})
	//热门游戏切换
	$(".game_com_tab ul .ffh").hover(function(){
		i=$(".game_com_tab ul .ffh").index(this);
		$(".game_com_tab ul .ffh").removeClass("on");
		$(this).addClass("on");
		$(".game_list_box .game_com_list").removeClass("on");
		$(".game_list_box").find(".game_com_list:eq("+i+")").addClass("on");
	})
	$(".game_list_box .game_com_list ul li").hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	})
	//游戏推荐
	$(".lf_ul li").hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	})
	//图片轮换
	function xrkd(i)
	{
		var sy_objjl=parseInt($(".main").find(".slide:eq("+i+")").css("width").replace("px",""));
		var sy_cs=0;
		var sy_text="";
		var sy_bojgs=$(".main").find(".slide:eq("+i+") .slide-cont li").size();
		var sy_objwidth = parseInt(sy_bojgs*sy_objjl);
		$(".main").find(".slide:eq("+i+") .slide-cont").animate({width:sy_objwidth+"px"},0);
		var sy_objzd=sy_objwidth/sy_objjl;
		$(".main").find(".slide:eq("+i+") #prev").click(function(){
			if(sy_cs>0)
				{
				sy_cs=sy_cs-1;
				$(".main").find(".slide:eq("+i+") .slide-cont").animate({left:("-"+sy_objjl*sy_cs)+"px"},300);
				}
		})
		$(".main").find(".slide:eq("+i+") #next").click(function(){
			if((sy_cs+1)<sy_objzd)
				{
				sy_cs=sy_cs+1;
				$(".main").find(".slide:eq("+i+") .slide-cont").animate({left:("-"+sy_objjl*sy_cs)+"px"},300);
				}
		})
	}
	$(".slide").each(function(){
		xrkd($(".main .slide").index(this));
	})
	//首页排行榜
	$(".paih .rank-cont:eq(0) .rank-cont-ul li").hover(function(){
		$(".paih .rank-cont:eq(0) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".paih .rank-cont:eq(1) .rank-cont-ul li").hover(function(){
		$(".paih .rank-cont:eq(1) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".paih .rank-cont:eq(2) .rank-cont-ul li").hover(function(){
		$(".paih .rank-cont:eq(2) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".paih .rank-cont:eq(3) .rank-cont-ul li").hover(function(){
		$(".paih .rank-cont:eq(3) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	//频道页排行
	$(".gdph_left dl:eq(0) dd ul li .thover").hover(function(){
		$(".gdph_left dl:eq(0) dd ul li").removeClass("on");
		$(this).parent().addClass("on");
	})
	$(".gdph_left dl:eq(1) dd ul li .thover").hover(function(){
		$(".gdph_left dl:eq(1) dd ul li").removeClass("on");
		$(this).parent().addClass("on");
	})
	$(".gdph_left dl:eq(2) dd ul li .thover").hover(function(){
		$(".gdph_left dl:eq(2) dd ul li").removeClass("on");
		$(this).parent().addClass("on");
	})
	//视频显示
	$(".video_list .video_B").hover(function(){
		$(this).find(".sml").hide(0);
		$(this).find(".big").show(0);
	},function(){
		$(this).find(".sml").show(0);
		$(this).find(".big").hide(0);
	})
	$(".spzxlm_gl .sp_video li").hover(function(){
		$(this).find(".sml").hide(0);
		$(this).find(".big").show(0);
	},function(){
		$(this).find(".sml").show(0);
		$(this).find(".big").hide(0);
	})
	$(".glsp dd ul li").hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	})
	$(".headlist li").hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	})
	//图片栏目页
	$(".tpbr .tpxt li").click(function(){
		$(".tpbr #img").attr("src",$(this).find("img").attr("src"));
		$(".tpbr .tpxt li").removeClass("active");
		$(this).addClass("active");
	})
	//游戏内容页切换
	$(".dgyx_tab_an li").hover(function(){
		i=$(".dgyx_tab_an li").index(this);
		$(".dgyx_tab_an li").removeClass("on");
		$(this).addClass("on");
		$(".dgyx_con .dgyx_conlist").removeClass("on");
		$(".dgyx_con").find(".dgyx_conlist:eq("+i+")").addClass("on");
	})
	$(".dgyx_xw dt a").hover(function(){
		i=$(".dgyx_xw dt a").index(this);
		$(".dgyx_tab_an li").removeClass("on");
		$(".dgyx_tab_an").find("li:eq("+i+")").addClass("on");
		$(".dgyx_con .dgyx_conlist").removeClass("on");
		$(".dgyx_con").find(".dgyx_conlist:eq("+i+")").addClass("on");
	})
	//H5游戏排行榜
	$(".yxph # ul li div").hover(function(){
		$(".yxph ul li").removeClass("on");
		$(this).parents("li").addClass("on");
	})
	$(".yxph # ul li div").hover(function(){
		$(".yxph ul li").removeClass("on");
		$(this).parents("li").addClass("on");
	})



	//内容页banner
	if($(".yxtp .xnrlh .mypic").html()!=null)
	{
		var nry_objjl=parseInt($(".yxtp .xnrlh .mypic").css("width").replace("px",""));
		var nry_cs=0;
		var nry_text="";
		var nry_bojgs=$('.xnrlh .mypic .ul li').size();
		var nry_objwidth = parseInt(nry_bojgs*nry_objjl);
		$(".mypic .ul").animate({width:nry_objwidth+"px"},0);
		var nry_objzd=nry_objwidth/nry_objjl;
		$(".xnrlh .lefton").click(function(){
			if(nry_cs>0)
				{
				nry_cs=nry_cs-1;
				$(".mypic .ul").animate({left:("-"+nry_objjl*nry_cs)+"px"},300);
				$(".mypic .smpic li").removeClass("on");
				$(".mypic .smpic li:eq("+nry_cs+")").addClass("on");
				}
		})
		$(".xnrlh .righton").click(function(){
			if((nry_cs+1)<nry_objzd)
				{
				nry_cs=nry_cs+1;
				$(".mypic .ul").animate({left:("-"+nry_objjl*nry_cs)+"px"},300);
				$(".mypic .smpic li").removeClass("on");
				$(".mypic .smpic li:eq("+nry_cs+")").addClass("on");
				}
		})
		for (var i=0;i<nry_bojgs;i++)
		{
			if(i==0)
			{nry_text=nry_text+"<li class=on></li>"}
			else
			{nry_text=nry_text+"<li></li>"}
		}
		$(".xnrlh .smpic").html(nry_text);
		$(".xnrlh .smpic li").click(function(){
			nry_cs=$(".xnrlh .smpic li").index(this);
			$(".mypic .ul").animate({left:("-"+nry_objjl*nry_cs)+"px"},300);
			$(".xnrlh .smpic li").removeClass("on");
			$(this).addClass("on");
		})
	}
	//礼包栏目页
	if($(".ny_main .box .ads").html()!=null)
	{
		var lb_objjl=parseInt($(".ny_main .box .ads").css("width").replace("px",""));
		var lb_cs=0;
		var lb_text="";
		var lb_bojgs=$(".ny_main .box .ads .ul li").size();
		var lb_objwidth = parseInt(lb_bojgs*lb_objjl);
		$(".ny_main .box .ads .ul").animate({width:lb_objwidth+"px"},0);
		var lb_objzd=lb_objwidth/lb_objjl;
		$(".ads .smpic li").hover(function(){
			lb_i=$(".ads .smpic li").index(this);
			$(".ads .smpic li").removeClass("on");
			$(this).addClass("on");
			$(".ads .ul li").removeClass("on");
			$(".ads .ul").find("li:eq("+lb_i+")").addClass("on");
		})

		var lbm_objjl=parseInt($(".ny_main .box .tab").css("width").replace("px",""));
		var lbm_cs=0;
		var lbm_text="";
		var lbm_bojgs=$(".ny_main .box .tab .ul li").size();
		var lbm_objwidth = parseInt(lbm_bojgs*lbm_objjl);
		$(".ny_main .box .tab .ul").animate({width:lbm_objwidth+"px"},0);
		var lbm_objzd=lbm_objwidth/lbm_objjl;
		$(".ny_main .box .tab .lefton").click(function(){
			if(lbm_cs>0)
				{
				lbm_cs=lbm_cs-1;
				$(".mypic .ul").animate({left:("-"+lbm_objjl*lbm_cs)+"px"},300);
				$(".mypic .smpic li").removeClass("on");
				$(".mypic .smpic li:eq("+lbm_cs+")").addClass("on");
				}
		})
		$(".ny_main .box .tab .righton").click(function(){
			if((lbm_cs+1)<lbm_objzd)
				{
				lbm_cs=lbm_cs+1;
				$(".mypic .ul").animate({left:("-"+lbm_objjl*lbm_cs)+"px"},300);
				$(".mypic .smpic li").removeClass("on");
				$(".mypic .smpic li:eq("+lbm_cs+")").addClass("on");
				}
		})
}

})