$(document).ready(function(){
    $.ajax({
        type: 'post',
        url: '/index.php?m=help&c=index&a=login_check',
        dataType: 'json',
        success: function(data) {
            if (data.static == "1") {
                $(".ells").text(data.msg);
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
	   //游戏浮动效果
	   
	   
	   			 
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
		
         var lh = $('.cyxw').height(),
		     rh = $('.cyxw_rig').height();
			 
if(lh>rh){
	    	var  ht=ht=$('.top').height()+$('.head_all').height()+$('.laction').height()+$('.ny_main').height()+$('.cyxw').height()+$('.yxzx').height()+$('.pop').height()+50,

			htss=$('.top').height()+$('.head_all').height()+$('.main').height()-380;

	    var zh=document.documentElement.clientHeight,

	        fh=$('#foot').height(),

		    gdqy=$('#zcgs').height(),

	        ksgd =gdqy+fh;
			

		if($(window).scrollTop() <ht ){

		$('.yxsp_3').removeClass('nine_fixed');

		$('.yxsp_3').removeClass('two_fixed');

		}

		else if($(window).scrollTop() < htss){

			$('.yxsp_3').addClass('nine_fixed');

			$('.yxsp_3').removeClass('two_fixed');

			}

		else{

			$('.yxsp_3').removeClass('nine_fixed');

			$('.yxsp_3').addClass('two_fixed');

			}
	}

}
	   
var bannerWidth = document.body.clientWidth>1260?1200:980;
    var bannerLength = $('#banner li').length;
    var bennerCurrent = 1;
    var timer = null;
    $('#banner ul').css('left',-bannerWidth).append($('#banner li').clone()).prepend($('#banner li:last-child').clone());

    function rebind(){
        $('#banner .mask_l').click(bannerRight);
        $('#banner .mask_r').click(bannerLeft);
    }
    function unbind(){
        $('#banner .mask_l').unbind();
        $('#banner .mask_r').unbind();
    }
    function bannerLeft(){
        if(bennerCurrent>=bannerLength+1) {
            bennerCurrent = 1;
            $('#banner ul').css('left', -bennerCurrent * bannerWidth);
        }
        bennerCurrent++;
        bannerMove(-bennerCurrent*bannerWidth);
    }
    function bannerRight(){
        if(bennerCurrent<=1) {
            bennerCurrent = bannerLength + 1;
            $('#banner ul').css('left', -bennerCurrent * bannerWidth);
        }
        bennerCurrent--;
        bannerMove(-bennerCurrent*bannerWidth);
    }
    function bannerMove(targetX,finnalX){
        clearTimeout(timer);
        unbind();
        $('#banner ul').animate({left:targetX},function(){
            if(finnalX!=0) $(this).css('left',finnalX);
            rebind();
            timer = setTimeout(bannerLeft,5000);
        })
    }
    rebind();
    timer = setTimeout(bannerLeft,5000);
    function resize(clientWidth){
        clearTimeout(timer);
        if(clientWidth>1260 && bannerWidth==980){
            $('#banner ul').stop(true,true);
            bannerWidth=1200;
            $('#banner ul').css('left', -bannerWidth);
            bennerCurrent=1;
        }else if(clientWidth<=1260 && bannerWidth==1200){
            $('#banner ul').stop(true,true);
            bannerWidth=980;
            $('#banner ul').css('left', -bannerWidth);
            bennerCurrent=1;
        }
        timer = setTimeout(bannerLeft,5000);
    }
	//浮动
		var d=document.getElementById("banner");
			if(d!=null){
            var $navScroll = $("#banner");
            var $fixedNav = $(".head");
            var navTop = $navScroll.offset().top + $navScroll.height();
			var hh=$fixedNav.height();
            $(window).on("scroll", function () {
                var wt = $(this).scrollTop();
                if (wt > navTop) {
					$fixedNav.addClass('head_fixed');
					 $("#main").css("margin-top",hh);
                } else {
					$fixedNav.removeClass('head_fixed');
					$("#main").css("margin-top","0px");
                }
            });
		}
		

  //游戏合集

    var $slider = $('.slider ul');
    var $slider_child_l = $('.slider ul li').length;
    var $slider_width = $('.slider ul li').width()+10;
    $slider.width($slider_child_l * $slider_width);

    var slider_count = 0;

    if ($slider_child_l <= 4) {
        $('#btn-right').css({ cursor: 'auto' });
        $('#btn-right').addClass("dasabled");
    }

    $('#btn-right').click(function () {
        if ($slider_child_l <= 4 || slider_count >= $slider_child_l - 4) {
            return false;
        }

        slider_count++;
        $slider.animate({ left: '-=' + $slider_width + 'px' }, 'slow');
        slider_pic();
    });

    $('#btn-left').click(function () {
        if (slider_count <= 0) {
            return false;
        }

        slider_count--;
        $slider.animate({ left: '+=' + $slider_width + 'px' }, 'slow');
        slider_pic();
    });

    function slider_pic() {
        if (slider_count >= $slider_child_l - 4) {
            $('#btn-right').css({ cursor: 'auto' });
            $('#btn-right').addClass("dasabled");
            $('#btn-left').css({ cursor: 'pointer' });
            $('#btn-left').removeClass("dasabled");
        }
        else if (slider_count > 0 && slider_count <= $slider_child_l - 4) {
            $('#btn-right').css({ cursor: 'pointer' });
            $('#btn-right').removeClass("dasabled");
            $('#btn-left').css({ cursor: 'pointer' });
            $('#btn-left').removeClass("dasabled");
        }
        else if (slider_count <= 0 && slider_count <= $slider_child_l - 4) {
            $('#btn-left').css({ cursor: 'auto' });
            $('#btn-left').addClass("dasabled");
            $('#btn-right').css({ cursor: 'pointer' });
            $('#btn-right').removeClass("dasabled");
        }
    }

    $('.slider a').hover(function () {
        if ($(this).find('img:animated').length) return;
        $(this).animate({ marginTop: '0px' }, 200);
        //$(this).find('img').animate({ width: '70px' }, 200);
    }, function () {

        $(this).animate({ marginTop: '0' }, 200);
        //$(this).find('img').animate({ width: '70px' }, 200);
    });

    var t = n = 0, count = $(".content a").size();
    $(function () {
        var play = ".play";
        var playText = ".play .text";
        var playNum = ".play .num a";
        var playConcent = ".play .content a";

        $(playConcent + ":not(:first)").hide();
        $(playText).html($(playConcent + ":first").find("img").attr("alt"));
        $(playNum + ":first").addClass("on");
        $(playText).mouseover(function () { window.open($(playConcent + ":first").attr('href'), "_blank") });
        $(playNum).mouseover(function () {
            var i = $(this).text() - 1;
            n = i;
            if (i >= count) return;
            $(playText).html($(playConcent).eq(i).find("img").attr('alt'));
            $(playText).unbind().click(function () { window.open($(playConcent).eq(i).attr('href'), "_blank") });
            $(playConcent).filter(":visible").hide().parent().children().eq(i).show();
            $(this).removeClass("on").siblings().removeClass("on");
            $(this).removeClass("on2").siblings().removeClass("on2");
            $(this).addClass("on").siblings().addClass("on2");
        });
        t = setInterval("showAuto()", 5000);
        $(playConcent).hover(function () { clearInterval(t) }, function () { t = setInterval("showAuto()", 5000); });
    });
    function showAuto() {
        n = (n >= count - 1) ? 0 : ++n;

        $(".num a").eq(n).trigger('mouseover');
    }


	//热门游戏切换
	$(".game_com_tab ul li").hover(function(){
		i=$(".game_com_tab ul li").index(this);
		$(".game_com_tab ul li").removeClass("on");
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
	//专区页面
	$(".zone-title .w_li").click(function(){
		i=$(".zone-title .w_li").index(this);
		$(".zone-title .w_li").removeClass("cur");
		$(this).addClass("cur");
		$(".tabCon div").removeClass("cur");
		$(".tabCon").find("div:eq("+i+")").addClass("cur");
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
		$(".tpbr #img img").attr("src",$(this).find("img").attr("src"));
		$(".tpbr #img i").html($(this).find("img").attr("alt"));
		$(".tpbr #img").attr("href",$(this).find("img").attr("links"));
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
	$(".dgyx_xw dt a").click(function(){
		i=$(".dgyx_xw dt a").index(this)+1;
		$(".dgyx_tab_an li").removeClass("on");
		$(".dgyx_tab_an").find("li:eq("+i+")").addClass("on");
		$(".dgyx_con .dgyx_conlist").removeClass("on");
		$(".dgyx_con").find(".dgyx_conlist:eq("+i+")").addClass("on");
	})
	
    $(".main3_l .txts-b li").hover(function() {
        $(this).addClass("hov");
    }, function() {
        $(this).removeClass("hov");
    })
	//二维码切换
      $(".down-all a").hover(function() {
         i = $(".down-all a").index(this);
         $(".down-all a").removeClass("bick");
         $(this).addClass("bick");
         $(".yxzx li").removeClass("bick");
         $(".yxzx ").find(" li:eq(" + i + ")").addClass("bick");
      });
	//H5游戏排行榜
	$(".yxph #h5ph ul li div").hover(function(){
		$(".yxph ul li").removeClass("on");
		$(this).parents("li").addClass("on");
	})
	$(".yxph #pjph ul li div").hover(function(){
		$(".yxph ul li").removeClass("on");
		$(this).parents("li").addClass("on");
	})

	// 复制代码
	/*if ( window.clipboardData ) {  
    $('.code .button').click(function() {  
        window.clipboardData.setData("Text", $('.code .title').val());  
        alert('复制成功了！');  
    });  
} else {  
    $(".code .button").zclip({  
        path:'http://img3.job1001.com/js/ZeroClipboard/ZeroClipboard.swf',  
        copy:function(){return $('.code .title').val();},  
        afterCopy:function(){alert('复制成功！');}  
    });  
}  
	
	$(".code .button").click(function(){
	  var url = $('.code .title').val();
	  if(copy_text(url)){
		alert("地址已经复制到您的剪贴板");
	  }else{
		alert("被浏览器拒绝！\n请自行复制链接在浏览器地址栏"); 
	  }
	})
	function copy_text(sUrl){
          var tempCurLink=sUrl;
          if(window.clipboardData){
            var ok=window.clipboardData.setData("Text",tempCurLink);
            if(ok){ 
              return true;
            }else if(window.netscape){  
              try{  
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
              } catch (e) {  
                return false;  
              }  
              var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);  
              if (!clip) return;  
              var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);  
              if (!trans) return;  
              trans.addDataFlavor('text/unicode');  
              var str = new Object();  
              var len = new Object();  
              var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);  
              var copytext = tempCurLink;  
              str.data = copytext;  
              trans.setTransferData("text/unicode",str,copytext.length*2);  
              var clipid = Components.interfaces.nsIClipboard;  
              if (!clip) return false;  
              clip.setData(trans,null,clipid.kGlobalClipboard);
              return true;
            }  
          }
       }*/
	//领取礼包
            $(".lbsyl a").click(function() {
                var id=$(".nr_box .lbsyl a").attr("val");
                var userName = $.cookie("nickname");
                var id = $(".nr_box .lbsyl a").attr("val");
                    $.ajax({
                        url: "/index.php?m=help&id=" + id,
                        cache: false,
                        success: function(html) {
                            //$(".code .title").val(html);
                        	 var json=eval('('+html+')');
                             $(".code .title").val(json.code);
                             if(json.is_cache ==1){$(".code .title").val("您已经领取过了");}
                        }
                    });
                    setTimeout(function() {
                        $.ajax({
                            url: "/index.php?m=help&id=" + id + "&a=last",
                            cache: false,
                            success: function(html) {
                                $(".lbsyl .syds i").html(html);
                            }
                        })
                    }, 2000);
            });
	if($(".nr_box .lbsyl .syds").html()!=null)
	{
		var id=$(".nr_box .lbsyl a").attr("val");
		$.ajax({
		  url: "/index.php?m=help&id="+id+"&a=last",
		  cache: false,
		  success: function(html){
			$(".lbsyl .syds i").html(html);
		  }
		})
	}
	//顶和踩
	$(".hbhw .hw").click(function(){
		var id=$(".main .y_box .hbhw").attr("val");
		$.ajax({
		  url: "/index.php?m=help&id="+id+"&a=up",
		  cache: false,
		  success: function(html){
			  if(html.indexOf("已经评分过")<0){
				$(".main .y_box .hbhw .hw em").html("好玩("+html+")");
			  }
			  else
			  {alert("已经评分过")}
		  }
		})
	})
	$(".hbhw .bhw").click(function(){
		var id=$(".main .y_box .hbhw").attr("val");
		$.ajax({
		  url: "/index.php?m=help&id="+id+"&a=down",
		  cache: false,
		  success: function(html){
			if(html.indexOf("已经评分过")<0){
				$(".main .y_box .hbhw .bhw em").html("不好玩("+html+")");
			  }
			  else
			  {alert("已经评分过")}
		  }
		})
	})
	if($(".main .y_box .hbhw").html()!=null)
	{
		var id=$(".main .y_box .hbhw").attr("val");
		$.ajax({
		  url: "/index.php?m=help&id="+id+"&a=getUpDown",
		  cache: false,
		  success: function(html){
			  var jdata=eval('('+html+')');
			hwsl=parseInt(jdata.good);
			bhwsl=parseInt(jdata.bad);
			zsl=hwsl+bhwsl;
			if(zsl==0){zsl=1;}
			hwbfb=hwsl/zsl;
			hwbfb=hwbfb*100;
			if(String(hwbfb).indexOf(".")>0){
				hwbfbs=String(hwbfb).split(".");
				hwbfb=hwbfbs[0];
			}
			bhwbfb=bhwsl/zsl;
			bhwbfb=bhwbfb*100;
			if(String(bhwbfb).indexOf(".")>0){
				bhwbfbs=String(bhwbfb).split(".");
				bhwbfb=bhwbfbs[0];
			}
			$(".main .y_box .hbhw .hw").html("<em>好玩("+hwsl+")</em><span><i style=\"width:"+hwbfb+"%\"></i></span><i>"+hwbfb+"%</i>");
			$(".main .y_box .hbhw .bhw").html("<em>不好玩("+bhwsl+")</em><span><i style=\"width:"+bhwbfb+"%\"></i></span><i>"+bhwbfb+"%</i>");
		  }
		})
	}
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
		//登录后下载记录
	    $(".erweima-hover-ele").click(function() {
        var nickName = $.cookie("nickname");
        if (!(nickName == undefined || $.trim(nickName).length <= 0)) {
            var id = $(".hbhw").attr("val");
            $.ajax({
                url: "/index.php?m=help&a=mydown&game_id=" + id,
                cache: false,
                success: function(html) {
                }
            })
        }

    });
/*   // 栏目页侧面浮动
	  var d = document.getElementById("r-float");
          if (d != null) {
             var $navScroll = $("#r-float");
             var $fixedNav = $(".pop");
             var navTop = $navScroll.offset().top + $navScroll.height();
                 $(window).on("scroll", function() {
                   var wt = $(this).scrollTop();
                   if (wt > navTop & wt + $(window).height() < $(document).height()-$(".foot").height()) {
					   $fixedNav.addClass('r-fixed');
					   $(".cyxw_post").css("position", "static");
                    } else {
						$fixedNav.removeClass('r-fixed');
					   $(".cyxw_post").css("position", "absolute");
                    }
                   });
        }
		*/

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
	  //网游切换
	  $(".w_caption .toggle").click(function() {
			i = $(".w_caption .toggle").index(this);
			$(".w_caption .toggle").removeClass("active");
			$(this).addClass("active");
			$(".w_g_all ul").removeClass("on");
			$(".w_g_all ").find("ul:eq(" + i + ")").addClass("on");
	  });
	  
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

})