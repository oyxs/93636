$(document).ready(function() {
    //头部登录判断
    $.ajax({
        type: 'post',
        url: '/index.php?m=help&c=index&a=login_check',
        dataType: 'json',
        success: function(data) {
            if (data.static == "1") {
                $(".ell").text(data.msg);
                $("#is_login").show();
            } else {
                $("#no_login").show();
            }
        }
    })

	 //单品新品效果
			 
	window.onscroll = window.onresize = function(){
		dddd();
	}
	 window.onload = function(){
	dddd();
   }

	function dddd() {	
		var htt=$('.foot').height()+30,

		    sjgElem = $('.two_fixed');
	
	        sjgElem.css('bottom', htt+'px');
		
         var lh = $('.mar_lt').height(),
		     rh = $('.dgyx_rig').height();
			 
if(lh>rh){
	    	var  ht=ht=$('.head_part').height()+$('.dgyx_big').height()+$('.dgyx_pp').height()+50,

			htss=$('.head_part').height()+$('.main').height()-580;

	    var zh=document.documentElement.clientHeight,

	        fh=$('#foot').height(),

		    gdqy=$('#zcgs').height(),

	        ksgd =gdqy+fh;
			

		if($(window).scrollTop() <ht ){

		$('#bourt').removeClass('nine_fixed');

		$('#bourt').removeClass('two_fixed');

		}

		else if($(window).scrollTop() < htss){

			$('#bourt').addClass('nine_fixed');

			$('#bourt').removeClass('two_fixed');

			}

		else{

			$('#bourt').removeClass('nine_fixed');

			$('#bourt').addClass('two_fixed');

			}
	}

}
		
		
  //视频显示
    $(".video_list .video_B ,.video_A").hover(function() {
        $(this).find(".sml").hide(0);
        $(this).find(".big").show(0);
    }, function() {
        $(this).find(".sml").show(0);
        $(this).find(".big").hide(0);
    })
  //网络入库单机入库
    $('.w_caption h4').hover(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $(".w_g_all > ul").eq($(this).index()).show().siblings(".w_game_list").hide();
    });
	//首页排行榜
	$(".ph_new:eq(0) .rank-cont-ul li").hover(function(){
		$(".ph_new:eq(0) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".ph_new:eq(1) .rank-cont-ul li").hover(function(){
		$(".ph_new:eq(1) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})
	$(".ph_new:eq(2) .rank-cont-ul li").hover(function(){
		$(".ph_new:eq(2) .rank-cont-ul li").removeClass("hover");
		$(this).addClass("hover");
	})

    //游戏内容页切换
	$(".dgyx_tab_an li").hover(function(){
		i=$(".dgyx_tab_an li").index(this);
		$(".dgyx_tab_an li").removeClass("on");
		$(this).addClass("on");
		$(".dgyx_con .dgyx_conlist").removeClass("on");
		$(".dgyx_con").find(".dgyx_conlist:eq("+i+")").addClass("on");
	})
	$(".dgyx_xw dt a").click(function(){
		i=$(".dgyx_xw dt a").index(this)+1;
		$(".dgyx_tab_an li").removeClass("on");
		$(".dgyx_tab_an").find("li:eq("+i+")").addClass("on");
		$(".dgyx_con .dgyx_conlist").removeClass("on");
		$(".dgyx_con").find(".dgyx_conlist:eq("+i+")").addClass("on");
	})
	//二维码
	$(".an_QR").hover(function(){
		$(".anz_bg").show();
	},function(){
		$(".anz_bg").hide();
	})
	$(".ios_QR").hover(function(){
		$(".QR_i").show();
	},function(){
		$(".QR_i").hide();
	})
	//二维码切换
	$(".anz_bor p").hover(function(){
		i=$(".anz_bor p").index(this);
		$(".anz_bor p").removeClass("on");
		$(this).addClass("on");
		$(".QR_m p").removeClass("on");
		$(".QR_m").find("p:eq("+i+")").addClass("on");
	})
	 //热门游戏切换
    $(".game_com_tab ul li").hover(function() {
        i = $(".game_com_tab ul li").index(this);
        $(".game_com_tab ul li").removeClass("on");
        $(this).addClass("on");
        $(".game_list_box .game_com_list").removeClass("on");
        $(".game_list_box").find(".game_com_list:eq(" + i + ")").addClass("on");
		$(".game_com_list:eq(" + i + ") ul li a ").hover(function() {
		  $(".game_com_list:eq(" + i + ") ul li a").removeClass("game_color");
		  $(this).addClass("game_color");
	   })
    })
	//领取礼包 
	
	$("#pr_cloes").click(function(){
		$(".prompt").hide();
	})
	$("#get_cloes").click(function(){
		$(".get_pr").hide();
	})
	
	//登录注册tab
	jQuery(".login_tab ul li").click(function(){
		  jQuery(this).addClass("hover").siblings().removeClass("hover");
		  i=jQuery(this).index();
		  jQuery(".iframe").eq(i).show().siblings().hide();
		})
	jQuery(".login_tx").focus(function(){
		jQuery(this).parent("li").addClass("hover");
		jQuery(this).css("color","#4d4d4d")
		}) 
	jQuery(".login_tx").blur(function(){
		jQuery(this).parent("li").removeClass("hover")
		}) 
	var w_width=jQuery(window).width();
	var h_height=jQuery(document).height();
	var i=0;
	jQuery(".opacity_box").width(w_width);
	jQuery(".opacity_box").height(h_height);
	jQuery(".opacity_box").css({ opacity: 0.4 });
	
	$(".box_close").click(function(){
		$(".login_box").hide();
	})
	$(".box_close").click(function(){
		$(".login_box").hide();
	})
	

	 $(".pr_logn a").click(function() {
       $(".login_box").show();
    })
});



