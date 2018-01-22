$(document).ready(function() {
    $(".navlist").click(function() {
        if ($('body').css('overflow') == 'hidden') {
            $('body').css('overflow', 'auto')
        } else {
            $('body').css('overflow', 'hidden')
        }
        $(".sidebar").toggle();
        if ($(this).hasClass("cur")) {
            $(this).removeClass("cur")
        } else {
            $(this).addClass("cur")
        }
    });
    $(".side-kong").click(function() {
        if ($('body').css('overflow') == 'hidden') {
            $('body').css('overflow', 'auto')
        } else {
            $('body').css('overflow', 'hidden')
        }
        $(".sidebar").hide()
    });
    $("#qx").click(function() {
        $(".shares").hide()
    });
    $(".fb-new a").click(function() {
        $(".shares").show()
    });
    $(".search_tab p").click(function() {
        i = $(".search_tab p").index(this);
        $(".search_tab p").removeClass("on");
        $(this).addClass("on");
        $(".zx-list").removeClass("on");
        $(".zx-list").find("zx-list:eq(" + i + ")").addClass("on")
    });
    $(".code .button").click(function() {
        var id = $(".code .button").attr("val");
        var id = $(".content .game-name a").attr("val");
        $.ajax({
            url: "/index.php?m=help&id=" + id,
            cache: false,
            success: function(html) {
                var json = eval('(' + html + ')');
                $(".code .titl").val(json.code);
                if (json.is_cache == 1) {
                    alert('您已领取过');
                    $(".code .titl").val('您已经领取过了')
                }
            }
        })
    });
    $(".game-name .sw").click(function() {
        var id = $(".game-name .sw").attr("val");
        var id = $(".content .game-name a").attr("val");
        $.ajax({
            url: "/index.php?m=help&id=" + id,
            cache: false,
            success: function(html) {
                var json = eval('(' + html + ')');
                $(".code .titl").val(json.code);
                if (json.is_cache == 1) {
                    alert('您已领取过');
                    $(".code .titl").val('您已经领取过了')
                }
            }
        });
        setTimeout(function() {
            $.ajax({
                url: "/index.php?m=help&id=" + id + "&a=last",
                cache: false,
                success: function(html) {}
            })
        },
        2000)
    });
    var d = document.getElementById("game-brief");
    if (d != null) {
        var $navScroll = $("#game-brief");
        var $fixedNav = $(".zx-donw");
        var navTop = $navScroll.offset().top + $navScroll.height();
        var hh = $fixedNav.height();
        $(window).on("scroll", 
        function() {
            var wt = $(this).scrollTop();
            if (wt > navTop) {
                $fixedNav.addClass('donw_fixed')
            } else {
                $fixedNav.removeClass('donw_fixed')
            }
            if ($(".game-name .img span").html() != null) {
                var id = $(".game-name .sw").attr("val");
                $.ajax({
                    url: "/index.php?m=help&id=" + id + "&a=last",
                    cache: false,
                    success: function(html) {
                        alert('12');
                        alert(html);
                        $(".game-name .img span").html(html)
                    }
                })
            }
        })
    } (function($) {
        var goToTopTime;
        $.fn.goToTop = function(options) {
            var opts = $.extend({},
            $.fn.goToTop.def, options);
            var $window = $(window);
            $body = (window.opera) ? (document.compatMode === "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            var $this = $(this);
            clearTimeout(goToTopTime);
            goToTopTime = setTimeout(function() {
                var controlLeft;
                if ($window.width() > opts.pageHeightJg * 2 + opts.pageWidth) {
                    controlLeft = ($window.width() - opts.pageWidth) / 2 + opts.pageWidth + opts.pageWidthJg
                } else {
                    controlLeft = $window.width() - opts.pageWidthJg - $this.width()
                }
                var cssfixedsupport = $.browser.msie && parseFloat($.browser.version) < 7;
                var controlTop = $window.height() - $this.height() - opts.pageHeightJg;
                controlTop = cssfixedsupport ? $window.scrollTop() + controlTop: controlTop;
                var shouldvisible = ($window.scrollTop() >= opts.startline) ? true: false;
                if (shouldvisible) {
                    $this.stop().show()
                } else {
                    $this.stop().hide()
                }
                $this.css({
                    position: cssfixedsupport ? 'absolute': 'fixed',
                    top: controlTop,
                    left: controlLeft
                })
            },
            30);
            $(this).click(function(event) {
                $body.stop().animate({
                    scrollTop: $(opts.targetObg).offset().top
                },
                opts.duration);
                $(this).blur();
                event.preventDefault();
                event.stopPropagation()
            })
        };
        $.fn.goToTop.def = {
            pageWidth: 100,
            pageWidthJg: 20,
            pageHeightJg: 50,
            startline: 130,
            duration: 3000,
            targetObg: "body"
        }
    })(jQuery);
    $(function() {
        $('<a href="javascript:;" class="backToTop" ></a>').appendTo("body")
    });
    $(function() {
        $(".backToTop").goToTop();
        $(window).bind('scroll resize', 
        function() {
            $(".backToTop").goToTop({
                pageWidth: 960,
                duration: 400
            })
        })
    })
});
/*function createLink() {
    if (browser.versions.ios || browser.versions.iPhone || browser.versions.iPad) {
        var href = $("#ios").attr('href') $('.sw').attr('href', href)
    } else if (browser.versions.android) {
        var href = $("#anzhuo").attr('href') $('.sw').attr('href', href)
    } else {
        alert('pc')
    }
}*/
$(function() {
    checkDevice()
});
function checkDevice() {
    if (browser.versions.Trident || browser.versions.Presto || browser.versions.AppleWebKit || browser.versions.Gecko) {
        window.location = 'http://www.93636.com'
    }
}
var browser = {
    versions: function() {
        var u = navigator.userAgent,
        app = navigator.appVersion;
        return {
            trident: u.indexOf('Trident') > -1,
            presto: u.indexOf('Presto') > -1,
            webKit: u.indexOf('AppleWebKit') > -1,
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
            mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
            iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1,
            iPad: u.indexOf('iPad') > -1,
            webApp: u.indexOf('Safari') == -1
        }
    } (),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
};