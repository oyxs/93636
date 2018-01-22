/*
 * jQuery - jcMarquee v0.10
 * Copyright(c) 2012 by Riddick-design 
 * Date: 2012-01-07
 * ��ز��� 
   'marquee':'x',        \\ ���ù��������ṩ marquee:x �� marquee:y
   'margin_bottom':'0',  \\ ����margin_bottomֵ
   'margin_right':'0',   \\ ����margin_rightֵ
   'speed':'10'          \\ ���ù����ٶ�,��λmm
 * ��Ҫ����
   #Marquee_x ul li { float:left;} // �������ʱ����������li�󸡶� 
 * HTML�ṹ
   <div id="Marquee"> 
         <ul> 
              <li> 
                  <div><img height="50" width="100" alt="ͼƬ1" /></div>
              </li>
         </ul> 
   </div>  
 */
;(function($){
	$.fn.jcMarquee = function(options) {
		var defaults = {
			'marquee':'x',
			'margin_bottom':'0',
			'margin_right':'0',
            'speed':'10'
		};
		var options = $.extend(defaults,options);
		return this.each(function() {
	        var $marquee = $(this),
			    $marquee_scroll = $marquee.children('ul');
			var $marquee_left = $marquee_scroll.find('li');
			if (options.marquee == 'x') {
				var x = 0;
		        $marquee_scroll.css('width','1000%');
				$marquee_left.find('div').css({'margin-right':options.margin_right});
				$marquee_left.css({'margin-right':options.margin_right});
                function Marquee_x(){ 
	                $marquee.scrollLeft(++x);
					_margin = parseInt($marquee_left.find('div').css('margin-right'));
		            if(x==$marquee_left.width()+_margin) { x = 0 };
				};
		    	var MyMar=setInterval(Marquee_x,options.speed); 
                $marquee.hover(function(){
                    clearInterval(MyMar);
                },function(){
                    MyMar=setInterval(Marquee_x,options.speed);
                });
			}
			if (options.marquee == 'y') {
				var y = 0;
		        $marquee_scroll.css('height','1000%');
				$marquee_left.find('div').css('margin-bottom',options.margin_bottom);
				$marquee_left.css('margin-bottom',options.margin_bottom);
                function Marquee_y(){ 
	                $marquee.scrollTop(++y);
					_margin = parseInt($marquee_left.find('div').css('margin-bottom'));
		            if(y==$marquee_left.height()+_margin) { y = 0 };
				};
		    	var MyMar=setInterval(Marquee_y,options.speed); 
                $marquee.hover(function(){
                    clearInterval(MyMar);
                },function(){
                    MyMar=setInterval(Marquee_y,options.speed);
                });
			};
		});
	};
})(jQuery)