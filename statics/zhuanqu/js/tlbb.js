$(document).ready(function(){
    //ȫվ����
	$(".global-nav").hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	});
	//�����л�
	$(".top_h li h2").hover(function(){
		i=$(".top_h li h2").index(this);
		$(".top_h li h2").removeClass("active");
		$(this).addClass("active");
		$(".news_content").css("display","none");
		$("#news").find(".news_content:eq("+i+")").css("display","block");
	});
	//ͼ���л�
	$(".picture dt h3").click(function(){
		i=$(".picture dt h3").index(this);
		$(".picture dt h3").removeClass("mv_on");
		$(this).addClass("mv_on");
		$(".picture dd ul").css("display","none");
		$(".picture dd").find("ul:eq("+i+")").css("display","block");
	});
	//ְҵ�л�
	$(".video h2").click(function(){
		i=$(".video h2").index(this);
		$(".video h2").removeClass("m_over");
		$(this).addClass("m_over");
		$(".video dd div").css("display","none");
		$(".video dd").find("div:eq("+i+")").css("display","block");
	});
	//��ά����ʾ����
	$(".game_load .andriod .ad_img").hover(function(){
		$(".game_load .andriod .ad_ewm").css("display","block");
	});
	$(".game_load .andriod .ad_img").mouseout(function(){
		$(".game_load .andriod .ad_ewm").css("display","none");
	});
	$(".game_load .apple .ap_img").hover(function(){
		$(".game_load .apple .ap_ewm").css("display","block");
	});
	$(".game_load .apple .ap_img").mouseout(function(){
		$(".game_load .apple .ap_ewm").css("display","none");
	});
	
	//ͼƬbanner
	var zt_box_objjl=315;
	var zt_box_gdobjjl=315;
	var zt_box_cs=0;
	var zt_box_bojgs=$(".career ul li").size();
	var zt_box_objwidth = parseInt(zt_box_bojgs*zt_box_objjl);
	var zt_box_gdzs=zt_box_objwidth/zt_box_gdobjjl;
	
	if(String(zt_box_gdzs).indexOf(".")>-1)
		{
			zt_box_gdzs=String(zt_box_gdzs).split(".");
			zt_box_gdzs=parseInt(zt_box_gdzs[0])+1;
		}
	$(".career ul").animate({width:zt_box_objwidth+"px"},0);
	$(".box_img .turn_right").click(function(){
		if(zt_box_cs>0)
			{
			zt_box_cs=zt_box_cs-1;
			$(".career ul").animate({left:("-"+zt_box_gdobjjl*zt_box_cs)+"px"},300);
			}
	});
	$(".box_img .turn_left").click(function(){
		if((zt_box_cs+1)<zt_box_gdzs)
			{
			zt_box_cs=zt_box_cs+1;
			$(".career ul").animate({left:("-"+zt_box_gdobjjl*zt_box_cs)+"px"},300);
			}
	});
	
});

//�ص�����
$(document).ready(function(){
(function($){
    var goToTopTime;
    $.fn.goToTop=function(options){
        var opts = $.extend({},$.fn.goToTop.def,options);
        var $window=$(window);
        $body = (window.opera) ? (document.compatMode === "CSS1Compat" ? $('html') : $('body')) : $('html,body'); // opera fix
        //$(this).hide();
        var $this=$(this);
        clearTimeout(goToTopTime);
        goToTopTime=setTimeout(function(){
            var controlLeft;
            if ($window.width() > opts.pageHeightJg * 2 + opts.pageWidth) {
                controlLeft = ($window.width() - opts.pageWidth) / 2 + opts.pageWidth + opts.pageWidthJg;
            }else{
                controlLeft = $window.width()- opts.pageWidthJg-$this.width();
            }
            var cssfixedsupport=$.browser.msie && parseFloat($.browser.version) < 7;//�ж��Ƿ�ie6

            var controlTop=$window.height() - $this.height()-opts.pageHeightJg;

            controlTop=cssfixedsupport ? $window.scrollTop() + controlTop : controlTop;
            var shouldvisible=( $window.scrollTop() >= opts.startline )? true : false;

            if (shouldvisible){
                $this.stop().show();
            }else{
                $this.stop().hide();
            }

            $this.css({
                position: cssfixedsupport ? 'absolute' : 'fixed',
                top: controlTop,
                left: controlLeft
            });
        },30);

        $(this).click(function(event){
            $body.stop().animate( { scrollTop: $(opts.targetObg).offset().top}, opts.duration);
            $(this).blur();
            event.preventDefault();
            event.stopPropagation();
        });
    };

    $.fn.goToTop.def={
        pageWidth:100,//ҳ����
        pageWidthJg:152,//��ť��ҳ��ļ������
        pageHeightJg:130,//��ť��ҳ��ײ��ļ������
        startline:130,//���ֻص�������ť�Ĺ�����scrollTop����
        duration:3000,//�ص��������ٶ�ʱ��
        targetObg:"body"//Ŀ��λ��
    };
})(jQuery);
$(function(){
    $('<a href="javascript:;" class="backToTop" title="���ض���"></a>').appendTo("body");
});

//����Ч��
$(function(){
  $(".backToTop").goToTop();
      $(window).bind('scroll resize',function(){
        $(".backToTop").goToTop({
          pageWidth:960,
          duration:400
        });
    });
});
//�л�Ч��
});