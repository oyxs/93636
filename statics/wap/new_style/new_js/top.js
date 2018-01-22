//回到顶部
$(document).ready(function() {
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
	var browser={
                versions:function(){
                    var u = navigator.userAgent, app = navigator.appVersion;
                    return {
                        trident: u.indexOf('Trident') > -1,
                        presto: u.indexOf('Presto') > -1,
                        webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                        gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
                        mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                        ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                        android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
                        iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
                        iPad: u.indexOf('iPad') > -1, //是否iPad
                        webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
                        weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
                        qq: u.match(/\sQQ/i) == " qq" //是否QQ
                    };
                }(),
                language:(navigator.browserLanguage || navigator.language).toLowerCase()
            }
      //礼包内页浮动效果
       var f = document.getElementById("j_head_nav");
          if (f != null) {
             var $navtype = $("#j_head_nav");
             var navTop = $navtype.offset().top + $navtype.height();
                 $(window).on("scroll", function() {
                   var wt = $(this).scrollTop();
                   if (wt > navTop) {
					   if(browser.versions.android){
						   $(".fix_bg").hide();
						   $("#yys").show();
					   }else if(browser.versions.iPhone||browser.versions.iPad){
						   $(".fix_bg").show();
						   $("#yys").hide();
					   }
                      //$(".fix_bg").show();
                    } else {
                      $(".fix_bg").hide();
					  $("#yys").hide();
                    }
					 
              });
        }
        //下载浮动效果
/*       var f = document.getElementById("content");
          if (f != null) {
             var $navtype = $("#content");
             var navTop = $navtype.offset().top + $navtype.height();
                 $(window).on("scroll", function() {
                   var wt = $(this).scrollTop();
                   if (wt > navTop) {
                      $(".zx-donw").addClass('zx_fixed');
					   $(".footer .copyright").css("height","71px");
                    } else {
                      $(".zx-donw").removeClass('zx_fixed');
					  $(".footer .copyright").css("height","35px");
                    }
              });
        }*/
    $(".navlist").click(function() {
			
			$(".header").removeClass("header_fix");
			$(".nav-bar").css('padding-top', '0');
        if ($('body').css('overflow') == 'hidden') {
            $('body').css('overflow', 'auto');
;
        }
        else {
            $('body').css('overflow', 'hidden');
			$(".header").addClass("header_fix");
			$(".nav-bar").css('padding-top', '68px')

        }
        $(".sidebar").toggle();
        if ($("#divSidebar").hasClass("cur")) {
            $("#divSidebar").removeClass("cur");
        } else {
            $("#divSidebar").addClass("cur");
        }
    });
    $(".side-kong").click(function() {
        if ($('body').css('overflow') == 'hidden') {
            $('body').css('overflow', 'auto');
        }
        else {
            $('body').css('overflow', 'hidden');
        }
        $(".sidebar").hide();
    });
    $("#qx").click(function() {
        $(".shares").hide();
    });
    $(".fb-new a").click(function() {
        $(".shares").show();
    });
    (function($) {
        var goToTopTime;
        $.fn.goToTop = function(options) {
            var opts = $.extend({}, $.fn.goToTop.def, options);
            var $window = $(window);
            $body = (window.opera) ? (document.compatMode === "CSS1Compat" ? $('html') : $('body')) : $('html,body'); // opera fix
            //$(this).hide();
            var $this = $(this);
            clearTimeout(goToTopTime);
            goToTopTime = setTimeout(function() {
                var controlLeft;
                if ($window.width() > opts.pageHeightJg * 2 + opts.pageWidth) {
                    controlLeft = ($window.width() - opts.pageWidth) / 2 + opts.pageWidth + opts.pageWidthJg;
                } else {
                    controlLeft = $window.width() - opts.pageWidthJg - $this.width();
                }
                var cssfixedsupport = $.browser.msie && parseFloat($.browser.version) < 7; //判断是否ie6

                var controlTop = $window.height() - $this.height() - opts.pageHeightJg;
                controlTop = cssfixedsupport ? $window.scrollTop() + controlTop : controlTop;
                var shouldvisible = ($window.scrollTop() >= opts.startline) ? true : false;
                if (shouldvisible) {
                    $this.stop().show();
                } else {
                    $this.stop().hide();
                }

                $this.css({
                    position: cssfixedsupport ? 'absolute' : 'fixed',
                    top: controlTop,
                    left: controlLeft
                });
            }, 30);
            $(this).click(function(event) {
                $body.stop().animate({scrollTop: $(opts.targetObg).offset().top}, opts.duration);
                $(this).blur();
                event.preventDefault();
                event.stopPropagation();
            });
        };
        $.fn.goToTop.def = {
            pageWidth: 100, //页面宽度
            pageWidthJg: 20, //按钮和页面的间隔距离
            pageHeightJg: 50, //按钮和页面底部的间隔距离
            startline: 130, //出现回到顶部按钮的滚动条scrollTop距离
            duration: 3000, //回到顶部的速度时间
            targetObg: "body"//目标位置
        };
    })(jQuery);
    $(function() {
        $('<a href="javascript:;" class="backToTop" title="返回顶部"></a>').appendTo("body");
    });
//返顶效果
    $(function() {
        $(".backToTop").goToTop();
        $(window).bind('scroll resize', function() {
            $(".backToTop").goToTop({
                pageWidth: 960,
                duration: 400
            });
        });
    });
});


function createLink() {
    if (browser.versions.ios || browser.versions.iPhone || browser.versions.iPad) {//当为iphone时做的事情
    $(".sw").attr('id','#ios');
    $("#ios").attr('href','***')
    }
    else if (browser.versions.android) {//当为安卓时做的事情
    $(".sw").attr('id','#anzhuo');
    $("#anzhuo").attr('href','***')
    }
	 else {//电脑时做的事情
       // alert('pc');
    }
}

var browser = {
    versions: function() {
        var u = navigator.userAgent, app = navigator.appVersion;
        return {//移动终端浏览器版本信息 
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
        };
    }(),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
};
