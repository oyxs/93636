$(document).ready(function() {
    //头部登录判断
    $.ajax({
        type: 'post',
        url: '/index.php?m=help&c=index&a=login_check',
        dataType: 'json',
        success: function(data) {
        	if (data.static == "1") {
                $("#ells").text(data.msg);
                //获取头像
                $.ajax({
                   type: 'post',
                   url: '/index.php?m=member&c=index&a=avtar',
                   dataType: 'json',
                   success: function(data) {
                       if (data.static == "1") {
                    	   $("#avtar").attr("src",data.msg);   
                    	   $("#is_login").show();
                       } 
                   }
               })
            } else {
                $("#no_login").show();
            }
        }
    })
    //左侧悬浮代码
    $(function() {
        var b = navigator.userAgent;
        if (!(b.indexOf("MSIE 6") > 0)) {
            var $window = $(window),
                $document = $(document),
                $mainLeft = $(".mar_top"),
                $fixRight = $(".right_fix"),
                $leftTop = $mainLeft.offset().top,
                $rightTop = $fixRight.offset().top,
                $rightHeight = $fixRight.height(),
                $winHeight = $window.height();
            $window.on({
                scroll: toTop,
                resize: function() {
                    $winHeight = $window.height()
                }
            }), toTop()
        }
        function toTop() {
            var $docSollTop = $document.scrollTop(),
                $leftHeight = $mainLeft.innerHeight();
            $leftHeight > $rightHeight && ($docSollTop + $winHeight > $leftTop + $rightHeight ? $docSollTop + $winHeight > $leftTop + $leftHeight ? $fixRight.css({
                position: "absolute",
                bottom: "0px"
            }) : $fixRight.css({
                position: "fixed",
                bottom: "0px"
            }) : $fixRight.css({
                position: "static"
            }));
        }
    });
	 //单品新品效果

	// window.onscroll = window.onresize = function(){
	// 	dddd();
	// }
	//  window.onload = function(){
	// dddd();
 //   }

// 	function dddd() {
// 		var htt=$('.foot').height()+30,

// 		    sjgElem = $('.two_fixed');

// 	        sjgElem.css('bottom', htt+'px');

//          var lh = $('.mar_lt').height(),
// 		     rh = $('.dgyx_rig').height();

// if(lh>rh){
// 	    	var  ht=$('.head_part').height()+$('.dgyx_big').height()+$('.dgyx_pp').height()+50,

// 			htss=$('.head_part').height()+$('.main').height()-550;

// 	    var zh=document.documentElement.clientHeight,

// 	        fh=$('#foot').height(),

// 		    gdqy=$('#zcgs').height(),

// 	        ksgd =gdqy+fh;


// 		if($(window).scrollTop() <ht ){

// 		$('#bourt').removeClass('nine_fixed');

// 		$('#bourt').removeClass('two_fixed');

// 		}

// 		else if($(window).scrollTop() < htss){

// 			$('#bourt').addClass('nine_fixed');

// 			$('#bourt').removeClass('two_fixed');

// 			}

// 		else{

// 			$('#bourt').removeClass('nine_fixed');

// 			$('#bourt').addClass('two_fixed');

// 			}
// 	}

// }


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

	  /*
     * 生成级联菜单
     */
    var i=1945;
    var date = new Date();
    year = date.getFullYear();//获取当前年份
    var dropList;
    for(i;i<2012;i++){
        if(i == year){
            dropList = dropList + "<option value='"+i+"' selected>"+i+"</option>";
        }else{
            dropList = dropList + "<option value='"+i+"'>"+i+"</option>";
        }
    }
    $('select[name=year]').html(dropList);//生成年份下拉菜单
    var monthly;
    for(month=1;month<13;month++){
        monthly = monthly + "<option value='"+month+"'>"+month+"</option>";
    }
    $('select[name=month]').html(monthly);//生成月份下拉菜单
    var dayly;
    for(day=1;day<=31;day++){
        dayly = dayly + "<option value='"+day+"'>"+day+"</option>";
    }
    $('select[name=day]').html(dayly);//生成天数下拉菜单
    /*
     * 处理每个月有多少天---联动
     */
    $('select[name=month]').change(function(){
        var currentDay;
        var Flag = $('select[name=year]').val();
        var currentMonth = $('select[name=month]').val();
        switch(currentMonth){
            case "1" :
            case "3" :
            case "5" :
            case "7" :
            case "8" :
            case "10" :
            case "12" :total = 31;break;
            case "4" :
            case "6" :
            case "9" :
            case "11" :total = 30;break;
            case "2" :
                if((Flag%4 == 0 && Flag%100 != 0) || Flag%400 == 0){
                    total = 29;
                }else{
                    total = 28;
                }
            default:break;
        }
        for(day=1;day <= total;day++){
            currentDay = currentDay + "<option value='"+day+"'>"+day+"</option>";
        }
        $('select[name=day]').html(currentDay);//生成日期下拉菜单
        })


    $(".user_sign").click(function() {
    	$.ajax({
	        type: 'post',
	        url: '/index.php?m=member&c=index&a=sign',
	        dataType: 'json',
	        success: function(data) {
	            if (data.static == "1") {
	            	 $(".i_rad").append('<i class="ico_xz"></i>+'+data.integral);
	            	 $(".i_greet").append('<i class="ico_jb"></i>+'+data.gold);
	            	 $(".office").show();
	                 $(".of_one").show();
	                
	            } else {
	            	 alert(data.msg);
	            }
	        }
	    })
    })
	// $("#ph").click(function() {
	//    $(".office").show();
 //       $(".of_three").show();
 //    })

// 显示弹窗
$("#ph").click(function() {
   modalShow();
   $(".office,.of_three").show();
   $('.of_one').hide();
});
// 窗口调整时调整位置
$(window).on('resize',function(){
    modalShow();
});
modalShow = function(){
    $win = $(window),
    $ww = $win.width(), // 屏幕宽度
    $wh = $win.height(); // 屏幕高度
    var oModal = $('.of_three');
    var logW = oModal.width(),//获取元素宽度；
        logH = oModal.height();//获取元素高度；
    oModal.css({
        'position': logH >= $wh ? 'absolute': 'fixed',
        'left': (($ww - logW) / 2),
        'top': (logH >= $wh ? $(document).scrollTop() : ($wh-logH)/2),
        'margin':(logH >= $wh ? '10px auto' : false),
    });
    return false;
};

	$("#of_rule").click(function() {
	   $(".office").show();
       $(".of_two").show();
    })
	$("#one_cloes").click(function() {
	   $(".office").hide();
       $(".of_one").hide();
       window.location.reload();//刷新当前页面.
    })
    $("#two_cloes").click(function() {
    	 //$(".office").hide();
        //$(".of_two").show();
          $(".of_two").hide();
    })
    $("#three_cloes").click(function() {
	   $(".office").hide();
       $(".of_three").hide();
    })
	$("#three_cloes").click(function() {
	   $(".office").hide();
       $(".of_three").hide();
    })
	$(".djq_link").click(function() {
	   $(".djq_div").show();
       $(".of_four").show();
    })
	$("#four_cloes").click(function() {
	   $(".djq_div").hide();
       $(".of_four").hide();
    })
	$("#five_cloes").click(function() {
	   $(".djq_div").hide();
       $(".of_five").hide();
    })
	$(".four_link").click(function() {
       $(".of_four").hide();
	   $(".of_five").show();
    })
    $("#six_cloes").click(function() {
		$(".djq_div").hide();
		$(".office").hide();
		$(".of_six").hide();
	})
	$(".six_link").click(function() {
		$(".djq_div").hide();
		$(".office").hide();
		$(".of_six").hide();
	})
 	$("#checkall").click(function(){   
 		if(this.checked){   
 			$("input[name='checkbox']").each(function(){this.checked=true;});
 		}else{   
 			$("input[name='checkbox']").each(function(){this.checked=false;});   
 		}   
 	});       
    $(".qdph").click(function() {
       $(".office").show();
       //$(".of_one").hide();
       $(".of_three").show();
     });
	$(".sys_page .ok").toggle(function(){
	     $('.Sys_message li').addClass('mage_click')
		 $('.input_on').addClass('input_add')
		},function(){
		 $('.Sys_message li').removeClass('mage_click')
		 $('.input_on').removeClass('input_add')
	});

 $('.Sys_message li').toggle(function(){
	     $(this).addClass('mage_click')
		 $(this).find('.input_on').addClass('input_add')
		 },function(){
		 $(this).removeClass('mage_click')
		 $(this).find('.input_on').removeClass('input_add')
	 })
});
//私信
$('.Sys_message li #letter').toggle(function(){
		
			//修改消息已读状态
			var messageid=$(this).find("#messageid").val();
			$.ajax({
		        type: 'post',
		        url: '/index.php?m=member&c=index&a=message_read&messageid='+messageid,
		        dataType: 'json',
		        success: function(data) {
		        	 
		        }
		    })
		       $(this).parents("li").find('.input_on').addClass('input_add');
		       $(this).parents("li").find('.u_mynews_show').slideDown();
		       $(this).parents("li").siblings().find(".u_mynews_show").slideUp();
		       $(this).parents("li").addClass('u_mynews_on').siblings().removeClass("u_mynews_on");
		       $(this).parents("li").addClass('mage_click');
		 },function(){
			 $(this).parents("li").find('.input_on').removeClass('input_add');
		       $(this).parents("li").find('.u_mynews_show').slideUp();
	 })
	 //公告
	 $('.Sys_message li #notice').toggle(function(){
		
			//修改消息已读状态
			var messageid=$(this).find("#messageid").val();
			$.ajax({
		        type: 'post',
		        url: '/index.php?m=member&c=index&a=notice_read&messageid='+messageid,
		        dataType: 'json',
		        success: function(data) {
		        	 
		        }
		    })
		       $(this).parents("li").find('.input_on').addClass('input_add');
		       $(this).parents("li").find('.u_mynews_show').slideDown();
		       $(this).parents("li").siblings().find(".u_mynews_show").slideUp();
		       $(this).parents("li").addClass('u_mynews_on').siblings().removeClass("u_mynews_on");
		       $(this).parents("li").addClass('mage_click');
		 },function(){
			 $(this).parents("li").find('.input_on').removeClass('input_add');
		       $(this).parents("li").find('.u_mynews_show').slideUp();
	 })

	/*私信展开*/
	$('.Sys_message li').hover(function(){
		$(this).addClass('u_mynews_hover')
	},function(){
		$(this).removeClass('u_mynews_hover')
	});
	$(".Sys_message li .u_mynews_show .close").click(function(){
		$(this).parent(".u_mynews_show").slideUp();
		return false
	})
	$(".task_all .task_part li p").hover(function(){
			$(this).siblings(".rw_tc").show();
		},function(){
			$(this).siblings(".rw_tc").hide();
		})
	
	//删除私信
	$("#letter_del").click(function() {
		var text="";  
        $("input[name=checkbox]").each(function() {  
            if ($(this).attr("checked")) {  
            	text += $(this).val()+',';  
            }  
        });  
        if(text==''){
        	alert('请选择要删除的私信');return;
        }
        $.ajax({
	        type: 'post',
	        data :{messageid:text},
	        url: '/index.php?m=member&c=index&a=message_del',
	        dataType: 'json',
	        success: function(data) {
	        	alert('删除成功');
	        	window.location.reload();//刷新当前页面.
	        }
	    }) 
	})
	//私信标记为已读
	$("#read").click(function() {
        $.ajax({
	        type: 'post',
	        url: '/index.php?m=member&c=index&a=read',
	        dataType: 'json',
	        success: function(data) {
	        	alert('标记为已读成功');
	        	window.location.reload();//刷新当前页面.
	        }
	    }) 
	})
	//公告标记为已读
	$("#read_an").click(function() {
		var text="";  
        $("input[name=checkbox]").each(function() {  
            if ($(this).attr("checked")) {  
            	text += $(this).val()+',';  
            }
        });  
        $.ajax({
	        type: 'post',
	        data :{messageid:text},
	        url: '/index.php?m=member&c=index&a=read_announce',
	        dataType: 'json',
	        success: function(data) {
	        	alert('标记为已读成功');
	        	window.location.reload();//刷新当前页面.
	        }
	    }) 
	})
$(function() {
    /*banner 手风琴切换*/
    $(".page3 .tese li").hover(function(){
        $(this).addClass("on").siblings().removeClass("on")
    })

    /*游戏推荐 鼠标移入 出现遮罩*/
    $(".recommended-pic").on("mouseenter",
        function() {
            $(this).find(".down-slide").stop().animate({
                    top: 0
                },
                150)
        }),
        $(".recommended-pic").on("mouseleave",
            function() {
                $(this).find(".down-slide").stop().animate({
                        top: "-100%"
                    },
                    150)
            });
});




