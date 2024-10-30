(function($){
	"use strict";
	function c4d_mega_menu(){
		var ww = $(window).width();
		$('.c4d-mega-menu > ul, .c4d-mega-menu > .c4d-mega-menu-block').each(function(index, el){
			$(el).css({'left': '0'});
			var elw = $(el).width();
			if (ww < elw) {
				$(el).css({'width': ww});
				elw = ww;
			} else {
				$(el).css({'width': ''});
			}
			var pos = $(el).parent().offset(),
			left = pos.left - ((ww - elw) / 2);
			$(el).css({'left': '-' + left + 'px'});
		});
	};
	$(document).ready(function(){
		c4d_mega_menu();
		$(window).on('resize', function(){
			c4d_mega_menu();
		});
		var c4dLastScrollTop = 0;
		$(window).scroll(function(event){
		   var st = $(this).scrollTop();
		   if (st > c4dLastScrollTop){
		      	if (!$('body').hasClass('c4d-mega-menu-scroll-down')) {
					document.body.className += ' ' + 'c4d-mega-menu-scroll-down';
				}
				$('body').removeClass('c4d-mega-menu-scroll-up');
		   } else {
		    	if (!$('body').hasClass('c4d-mega-menu-scroll-up')) {
					document.body.className += ' ' + 'c4d-mega-menu-scroll-up';
				}
				$('body').removeClass('c4d-mega-menu-scroll-down');
		   }
		   if (st == 0) {
		   		$('body').removeClass('c4d-mega-menu-scroll-down').removeClass('c4d-mega-menu-scroll-up');
		   }
		   c4dLastScrollTop = st;
		});
	});
})(jQuery);