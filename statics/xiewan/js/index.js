$(function() {
    // 头部动画激活
    !isLowIE() && $('#js-game-hd').parallax();

    // 判断低版本IE
    function isLowIE(){
        var userAgent = navigator.userAgent;
        if (/(MSIE 6.0|MSIE 7.0|MSIE 8.0)/.test(userAgent)){
            return true;
        } else {
            return false;
        }
    }
});
/// <reference path="jquery-1.9.1.min.js" />
(function () {
	var winHeight = $(window).height(),
		floorLength = $('.floor').length,
		floorCoord = [],
		Index = 0,
		flag = true;
	var eles = $(".floor");
	var getCurrentEle = function () {
	    /// <summary>获取当前滚动条位置最近的标签</summa
	    var Top = $(window).scrollTop();
	    var Height = $(window).height();
	    var maxHeight = 0;
	    var maxIndex = 0;
	    eles.each(function (index, element) {
	        var ele = $(element);
	        var top = ele.offset().top;
	        var height = ele.outerHeight(false);
	        var _maxHeight = 0;
	        if (top > Top && top < Top + Height) {
	            _maxHeight = Height + Top - top;
	        } else if (Top > top && Top < top + height) {
	            _maxHeight = height + top - Top;
	        } else if (top == Top) {
	            _maxHeight = Height > height ? height : Height;
	        }
	        if (_maxHeight > maxHeight) {
	            maxHeight = _maxHeight;
	            maxIndex = index;
	        }
	        floorCoord[index] = top;
	        //console.log("Top:" + Top + ",Height:" + Height + ",top:" + top + ",height:" + height + "|" + _maxHeight)
	    });
	    return maxIndex;
	};

	$(window).resize(function () {
	    winHeight = $(window).height();
    	$('.floor').css('height', winHeight + "px");
    	getCurrentEle();
	});
	$(window).resize();

	//滚动
	$(window).scroll(function(){
		pageScroll();
	});

	pageScroll = function (event) {
	    var _index = getCurrentEle();
	    if (_index != Index) {
	        Index = _index;
	        $('.floor').eq(_index).addClass('current').siblings('.floor').removeClass('current');
	    }

		if (Index == 0) {
		    $('#head').addClass('current');
		    $("#headTagBtn").addClass("hide");
			$("#nav").removeClass("black");
			$('.to-next').show();
		} else if(Index == 1){
			$('#head').removeClass('current');
		    $("#headTagBtn").removeClass("hide");
		    $("#nav").addClass("black");
		    $('.to-next').show();
		} else if(Index == 2){
			$('#head').removeClass('current');
		    $("#headTagBtn").removeClass("hide");
		    $("#nav").addClass("black");
		    $('.to-next').show();
		} else if(Index == 3){
			$('#head').removeClass('current');
		    $("#headTagBtn").removeClass("hide");
		    $("#nav").addClass("black");
		    $('.to-next').show();
		} else {
			$('#head').removeClass('current');
		    $("#headTagBtn").removeClass("hide");
		    $("#nav").removeClass("black");
		    $('.to-next').hide();
		}
	};

	//鼠标中轴滚动
	$(document).mousewheel(function (e, delta, deltaX, deltaY) {
	    var index = getCurrentEle();
	    if (delta == 1) {
	        //上
	        if (index > 0) {
	            play(index - 1);
	        }
	    } else if (delta == -1) {
	        //下
	        if (index < floorLength - 1) {
	            play(index + 1);
	        }
	    }
	    return false;
	});

	var play = function (index) {
	    if (!flag) return;
	    flag = false;
	    if (index == 0) {
	        floorCoord[index] = 0;
	    };

	    $("#warpRightMeun>a").eq(index).addClass("on").siblings().removeClass("on");
		$("html:not(:animated), body:not(:animated)").stop().animate({
			"scrollTop": floorCoord[index]
		}, 500, function() {
			flag = true;
		});
	};

	$("html:not(:animated), body:not(:animated)").animate({
		"scrollTop": 0
	}, 500);

	$('.floor').eq(0).addClass('current');
	setTimeout(function() {
		$('.floor').eq(0).addClass('currentDefault');
	}, 1000);

	//右侧导航
	$("#warpRightMeun").on("click", "a", function () {
        play($(this).index());
	});
})();
