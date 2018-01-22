$(function(){
  // 卡牌
	if($('#gla')){
		$('.gla_inbox').corner('8px');
		$('#gla_box>ul').roundabout({
			minOpacity:.8,
			btnNext: ".next",
			duration: 1000,
			reflect: true,
			btnPrev: '.prev',
			autoplay:true,
			autoplayDuration:2000,
			tilt:0,
			shape: 'figure8'
		});
	};
  // 换一换
	$('.ck-slide').ckSlide(/*{autoPlay: true}*/);

	// 视察滚动
	var scrollorama = $.scrollorama({
        blocks: '#js-act-page'
    });

    scrollorama.animate("#js-mod-p-1", {
       delay: 10,
       duration: 800,
       property: 'top',
       start: 45,
       end: 650
    }).animate("#js-mod-p-1", {
       delay: 10,
       duration: 800,
       property: "left",
       start: -153,
       end: -750
    }).animate("#js-mod-p-1", {
       delay: 10,
       duration: 8500,
       property: "zoom",
       start: 1,
       end: .5
    }).animate("#js-mod-p-1", {
       delay: 10,
       duration: 800,
       property: "opacity",
       start: 1,
       end: .5,
       easing: "easeOutQuad"
    }).animate("#js-mod-p-2", {
       delay: 50,
       duration: 400,
       property: 'top',
       start: 400,
       end: -58
    }).animate("#js-mod-p-2", {
       delay: 50,
       duration: 400,
       property: "right",
       start: -500,
       end: -178
    }).animate("#js-mod-p-2", {
       delay: 50,
       duration: 400,
       property: "zoom",
       start: .5,
       end: 1,
       easing: "easeOutQuad"
    }).animate("#js-mod-p-2", {
       delay: 50,
       duration: 400,
       property: "opacity",
       start: 0,
       end: 1,
       easing: "easeOutQuad"
    }).animate("#js-mod-p-3", {
       delay: 510,
       duration: 450,
       property: 'top',
       start: 450,
       end: -107
    }).animate("#js-mod-p-3", {
       delay: 510,
       duration: 450,
       property: "left",
       start: -530,
       end: -290
    }).animate("#js-mod-p-3", {
       delay: 510,
       duration: 450,
       property: "zoom",
       start: .5,
       end: 1
    }).animate("#js-mod-p-3", {
       delay: 510,
       duration: 450,
       property: "opacity",
       start: .5,
       end: 1,
       easing: "easeOutQuad"
    }).animate("#js-mod-p-4", {
       delay: 1200,
       duration: 400,
       property: 'top',
       start: 400,
       end: -24
    }).animate("#js-mod-p-4", {
       delay: 1200,
       duration: 400,
       property: "right",
       start: -800,
       end: -410
    }).animate("#js-mod-p-4", {
       delay: 1200,
       duration: 400,
       property: "zoom",
       start: .5,
       end: 1,
       easing: "easeOutQuad"
    }).animate("#js-mod-p-4", {
       delay: 1200,
       duration: 400,
       property: "opacity",
       start: 0,
       end: 1,
       easing: "easeOutQuad"
    }).animate("#js-mod-p-5", {
       delay: 1500,
       duration: 450,
       property: 'top',
       start: 450,
       end: -24
    }).animate("#js-mod-p-5", {
       delay: 1500,
       duration: 450,
       property: "left",
       start: -530,
       end: -360
    }).animate("#js-mod-p-5", {
       delay: 1500,
       duration: 450,
       property: "zoom",
       start: .5,
       end: 1
    }).animate("#js-mod-p-5", {
       delay: 1500,
       duration: 450,
       property: "opacity",
       start: .5,
       end: 1,
       easing: "easeOutQuad"
    })
});
  // 定位
$(function(){
  $('.js-to-p2').on('click', function(e) {
    e.preventDefault();
        $('html, body').animate({'scrollTop': 619}, 500);
  });
  $('.js-to-p3').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({'scrollTop': 1219}, 500);
  });
  $('.js-to-p4').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({'scrollTop': 1817}, 500);
  });
  $('.js-to-p5').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({'scrollTop': 2417}, 500);
  });
});

