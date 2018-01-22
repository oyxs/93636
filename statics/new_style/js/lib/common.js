/**
* @Author: chendianhuai (773189176@qq.com)
* @Date:   2016-07-27 11:05:04
* @Desc:   93636手游公共脚本
* @Last Modified by:   chendianhuai
* @Last Modified time: 2016-08-03 11:40:41
*/

$(function() {
    //首页浮动效果
    var d = document.getElementById("nav");
    if (d != null) {
        var $navScroll = $(".nav");
        var $fixedNav = $(".top-nav");
        var navTop = 0;
        $(window).on("scroll", function() {
            var wt = $(this).scrollTop();
            if (wt > navTop) {
                $fixedNav.addClass('head_fixed');
                $('#nav .top').addClass('h60');
            } else {
                $fixedNav.removeClass('head_fixed');
                $("#nav").css("margin-top","0px");
                $('#nav .top').removeClass('h60');
            }
        });
    }
});
// 首页轮播图1
$(".fullSlide").slide({titCell: ".hd ul", mainCell: ".bd ul", effect: "fold", autoPlay: true, autoPage: true, trigger: "click"});
 // 首页  热门游戏切换
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
// 首页轮播图2
$(".m-slide").slide({ titCell:".tab li", mainCell:".img",effect:"fold", autoPlay:true});
// 经过切换
$('.index-data-box li,.news-list li,.ranking-list li').on('mouseover',function(){
	if(!$(this).hasClass('active')){
		$(this).addClass('active').siblings('li').removeClass('active');
	}
});


//网络入库单机入库
$('.w_caption h4').hover(function() {
    $(this).addClass('active').siblings().removeClass('active');
    $(".w_g_all > ul").eq($(this).index()).show().siblings(".w_game_list").hide();
});

// 友情连接
var link_index=1;
var ah=parseInt($("#links_box a").height());
var c=parseInt($("#links_box").height())/ah-1;
var d=parseInt($("#links_box").height())%ah;
$(".friendly_swiper .swiper-button-next").click(function(){
	var t=parseInt(-ah*link_index)+'px';
	if(link_index<(c+d)){
		jQuery("#links_box").stop(true).animate({top:t},300);
		link_index++;
	}else{
		return
	}
});
$(".friendly_swiper .swiper-button-prev").click(function(){
	var t=parseInt(-ah*link_index+ah*2)+'px';
	if(link_index>1){
		jQuery("#links_box").stop(true).animate({top:t},300);
		link_index--;
	}else{
		return
	}
});

// 手风琴
(function(){

var slideMenu=function(){
  var sp,st,t,m,sa,l,w,gw,ot;
  return{
    destruct:function(){
        if(m){
          clearInterval(m.htimer);
          clearInterval(m.timer);
        }
    },
    build:function(sm,sw,mt,s,sl,h){
      sp=s;
      st=sw;
      t=mt;
      m=document.getElementById(sm);
      sa=m.getElementsByTagName('li');
      l=sa.length;
      w=m.offsetWidth - 80;
      gw=w/l;
      ot=Math.floor((w-st)/(l-1));
      var i=0;
      for(i;i<l;i++){
        s=sa[i];
        s.style.width=gw+'px';
        this.timer(s)
      }
      // if(sl!=null){
      //   m.timer=setInterval(function(){
      //     slideMenu.slide(sa[sl-1])
      //   },t)}
    },
    timer:function(s){
      s.onmouseover=function(){
        clearInterval(m.htimer);
        clearInterval(m.timer);
        m.timer = setInterval(function(){
          slideMenu.slide(s)
        },t);
        $(this).find('span').hide()
    }
      s.onmouseout=function(){
        clearInterval(m.timer);
        clearInterval(m.htimer);
        m.htimer=setInterval(function(){
          slideMenu.slide(s,true)
        },t);
		    $(this).find('span').show()
     }
    },
    slide:function(s,c){
      var cw=parseInt(s.style.width);
      if((cw<st && !c) || (cw>gw && c)){
        var owt=0; var i=0;
        for(i;i<l;i++){
          if(sa[i]!=s){
            var o,ow; var oi=0; o=sa[i]; ow=parseInt(o.style.width);
            if(ow<gw && c){
              oi=Math.floor((gw-ow)/sp);
              oi=(oi>0)?oi:1;
              o.style.width=(ow+oi)+'px';
              //console.log(o);
            //console.log(o.style.width);
            }else if(ow>ot && !c){
              oi=Math.floor((ow-ot)/sp);
              oi=(oi>0)?oi:1;
              o.style.width=(ow-oi)+'px';
              //console.log(o);
              //console.log(o.style.width);
            }
            if(c){
              owt=owt+(ow+oi)
            }else{
              owt=owt+(ow-oi)
            }
          }
        }
        s.style.width=(w-owt)+'px';
      }else{
        if(m.htimer)
          clearInterval(m.htimer)
        if(m.timer)
          clearInterval(m.timer);
      }
    }
  };
}();
slideMenu.build('sm',430,10,10,1);
})();
