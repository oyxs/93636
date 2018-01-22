$(function(){
	var delay = 5000;   //5seconds.
	var elems = $("#focus ul li");
	elems.attr('target', '_blank');
	var num = elems.length;
	elems.hide();
	$(elems).eq(0).show().addClass("current");	
	
	if(elems.length <=1 ) return; 
	
	else{
	
		var timer = setInterval("slideShow()",delay); 	
		
		var createNav=function(){
			var html='<div class="num">';
			html+='<p>';
				for(var i=1;i<=num;i++){
					html+='<a>'+i+'</a>';
					//html+='<a href="javascript:void(0);"></a>';
				}
			html+='</p>';
			html+='</div>';
			$("#focus").append(html);
			$(".num a:eq(0)").addClass("active");
			$(".num a").mouseover(function(){
				if($(this).hasClass("active")){
					return false;	
				}
				else{
					stop();
					var pos_1 = $(elems).index($(".current"));
					var pos_2 = $(".num a").index($(this));
					//alert(pos_1+','+pos_2);
					i_fade(pos_1, pos_2);
					$(".num a").removeClass("active");
					$(this).addClass("active");
				}
				play();
			});
		};
		createNav();
		
		//fede effects
		i_fade = function(pos1, pos2){	
			$(".num a").removeClass("active");
			$(".num a").eq(pos2).addClass("active");
			$(elems[pos1]).fadeOut("slow").removeClass("current");
			$(elems[pos2]).fadeIn("2000").addClass("current");
		};
		
		slideShow = function(){
			var curr_pos = $(elems).index($(".current"));
			var next_pos = curr_pos + 1;
			if(next_pos >= num) next_pos = 0;	
			i_fade(curr_pos, next_pos);
		};
		
		
		var stop = function(){
			clearTimeout(timer);
		};
		var play=function(){
			timer = setInterval("slideShow()",delay); 
		};
	}	
});	