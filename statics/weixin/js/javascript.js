
window.onload=function(){
    if ( document.body.clientHeight <= window.screen.height) {
        document.body.clientHeight = window.screen.height
    } else if(document.documentElement.scrollHeight <= document.documentElement.clientHeight) {
        bodyTag = $('body')[0];
        bodyTag.style.height = document.documentElement.clientWidth / screen.width * screen.height + 'px';
    }
    setTimeout(function() {
        window.scrollTo(0, 1)
    }, 0);
};
/*  隐藏地址栏 end */

/* 初始化单位  rem */
(function (doc, win) {
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) return;
            docEl.style.fontSize = 20 * (clientWidth / 320) + 'px';
        };

    if (!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);

window.onload = function() {
    /*banner 轮播*/
    var mySwiper2 = new Swiper('#banner',{
        autoplay:5000,
        visibilityFullFit : true,
        loop:true,
        pagination : '.pagination'
    });

};

// 点赞
$('.ico_good').on('click',function(){
    var spanVal = $(this).find('span').text();
    if (!$(this).hasClass('on')) {
        $(this).addClass('on');
        $(this).find('span').text(spanVal*1 + 1);
    }else{
        $(this).removeClass('on');
        $(this).find('span').text(spanVal*1 - 1);
    }
});