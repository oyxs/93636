var $window = $(window);
var $document = $(document);
var u = navigator.appVersion;
var isIE6 = u.indexOf("MSIE 6") > -1;
var $showcase = $(".showcase");
if ($showcase.length > 0) {
    var _showcase_top = $showcase.offset().top;
} (function() {
    var $scrollbar = $showcase.find(".scrollbar");
    var $slider = $showcase.find(".slider");
    var _scrollbar_api;
    var _slider_api;
    function imageDownload($obj, callback) {
        var len = $obj.length;
        var num = 0;
        var list = [];
        $obj.each(function() {
            if (this.complete && this.width) {
                doSomething();
            } else {
                this.onload = doSomething;
            }
            function doSomething() {
                num++;
                list.push(this);
                if (num == len) {
                    callback(list);
                }
            }
        });
    }
    imageDownload($scrollbar.find("li img"),
    function() {
        $scrollbar.scrollbar({
            contentCls: "s-content",
            direction: "x",
            inEndEffect: true
        },
        function(api) {
            _scrollbar_api = api;
        });
    });
    $scrollbar.find(".s-content li").each(function(i) {
        var $this = $(this);
        $this.click(function() {
            $scrollbar.hide();
            $slider.fadeIn("fast");
            if (_slider_api) {
                _slider_api.setIndex(i, false);
            }
        }).mouseenter(function() {
            $this.addClass('active').siblings().removeClass('active');
        }).mouseleave(function() {
            if ($this.hasClass('video')) {
                $this.removeClass('active');
                $(".picture").eq(0).addClass('active');
            }
        });
    });
    $slider.slider({
        contentCls: "s-content",
        navCls: "s-nav",
        triggerCondition: "span span",
        triggerType: "click",
        duration: 300,
        keyboardAble: true,
        beforeEvent: function() {}
    },
    function(api) {
        _slider_api = api;
    });
    $slider.find(".s-content li").click(function() {
        _slider_api.next();
    });
    $slider.find(".trigger").click(function() {
        $scrollbar.fadeIn("fast");
        $slider.hide();
        if (_scrollbar_api) {
            _scrollbar_api.resize();
        }
    });
    imageDownload($slider.find("li img"),
    function(list) {
        var $box = $slider.find(".s-box");
        var $list = $box.find(".s-content li");
        reset();
        $window.on('resize', reset);
        function reset() {
            var box_height = 0;
            var box_width = $box.width();
            $list.children().each(function(i) {
                var width = this.width;
                var height = this.height;
                if (width > box_width) {
                    height *= box_width / width;
                }
                if (height > box_height) {
                    box_height = height;
                }
            });
            if (box_height > 600) {
                box_height = 600;
            }
            if (box_height < 375) {
                box_height = 375;
            }
            $slider.find(".s-box").css({
                'height': box_height + 'px'
            });
            $slider.find(".prev,.next").css({
                'height': 128 + 'px'
            });
            $list.css({
                'height': box_height + 'px'
            }).children().removeAttr('style').each(function() {
                var $this = $(this);
                var width = this.width;
                var height = this.height;
                if (height > width) {
                    if (height > box_height) {
                        $this.css({
                            'height': box_height + 'px'
                        });
                    } else {
                        $this.css({
                            'margin-top': (box_height - height) / 2 + 'px'
                        });
                    }
                } else {
                    if (width > box_width) {
                        $this.css({
                            'width': box_width + 'px',
                            'margin-top': (box_height - box_width / width * height) / 2 + 'px'
                        });
                    } else {
                        $this.css({
                            'margin-top': (box_height - height) / 2 + 'px'
                        });
                    }
                }
            });
        }
    });
})(); (function() {
    var h = $('.intro_art').height();
    if (h > 210) {
        $('.intro_art').css({
            "height": "180px"
        });
        $('.intro_more span').bind('click',
        function() {
            $('.intro_art').css({
                "height": h + "px"
            });
            $('.intro_more').hide();
        });
    } else {
        $('.intro_more').hide();
    }
})(); (function() {
    var module = $(".module");
    $("#nav li span a").each(function(i) {
        $(this).bind("click",
        function() {
            $("html,body").animate({
                scrollTop: module.eq(i).position().top - 60 + 197
            },
            500);
        });
    });
})();
function news_tab(obj) {
    var o1 = obj,
    pv = o1.find(".prve a"),
    nt = o1.find(".next a"),
    scrollbar = o1.find(".news_list"),
    page = 1,
    num = scrollbar.find("ul").size(),
    pva = nta = true;
    if (scrollbar.find("li").size() <= 6) {
        pv.hide();
        nt.hide();
        scrollbar.css("height", "auto");
        scrollbar.parent().parent(".bd").css("height", scrollbar.height() + "px");
    } else {
        scrollbar.css("width", +840 * num + "px");
        pv.addClass("none");
    }
    pv.bind("click",
    function() {
        if (page > 1 && !scrollbar.is(':animated')) {
            scrollbar.animate({
                left: '+=' + 840 + 'px'
            },
            500);
            page--;
            nta = true;
            if (page == 1) {
                pva = false;
            };
            nt.removeClass("none");
        }
        if (!pva) {
            pv.addClass("none");
        }
    });
    nt.bind("click",
    function() {
        if (page < num && !scrollbar.is(':animated')) {
            scrollbar.animate({
                left: '-=' + 840 + 'px'
            },
            500);
            page++;
            pva = true;
            if (page == num) {
                nta = false;
            };
            pv.removeClass("none");
        }
        if (!nta) {
            nt.addClass("none");
        }
    });
}
function news_tab1(obj) {
    var o1 = obj,
    pv = o1.find(".prve a"),
    nt = o1.find(".next a"),
    scrollbar = o1.find(".news_list"),
    page1 = 1,
    num1 = scrollbar.find("ul").size(),
    pva1 = nta1 = true;
    if (scrollbar.find("li").size() <= 6) {
        pv.hide();
        nt.hide();
        scrollbar.css("height", "auto");
        scrollbar.parent().parent(".bd").css("height", scrollbar.height() + "px");
    } else {
        scrollbar.css("width", +840 * num1 + "px");
        pv.addClass("none");
    }
    pv.bind("click",
    function() {
        if (page1 > 1 && !scrollbar.is(':animated')) {
            scrollbar.animate({
                left: '+=' + 840 + 'px'
            },
            500);
            page1--;
            nta1 = true;
            if (page1 == 1) {
                pva1 = false;
            };
            nt.removeClass("none");
        }
        if (!pva1) {
            pv.addClass("none");
        }
    });
    nt.bind("click",
    function() {
        if (page1 < num1 && !scrollbar.is(':animated')) {
            scrollbar.animate({
                left: '-=' + 840 + 'px'
            },
            500);
            page1++;
            pva1 = true;
            if (page1 == num1) {
                nta1 = false;
            };
            pv.removeClass("none");
        }
        if (!nta1) {
            nt.addClass("none");
        }
    });
}

$(".showcase .scrollbar li.picture .info,.showcase .scrollbar li img").click(function () {
	setTimeout(function(){
				var imgh=$(".showcase .slider .s-box li img").height();
				if(imgh<375){
					imgh=375;
					}
	             $(".showcase .slider .s-box").css("height",imgh+"px");
				},500);  
	
	});


