(function($){
	"use strict";
	$(document).ready(function(){
		var menuWidth = 'c4dmm-menu-col-';
		$('#menu-to-edit > .menu-item').each(function(index, el){
			if ($(el).hasClass('menu-item-depth-0') || $(el).hasClass('menu-item-depth-1')) {
				var buttonActive = '<span title="Enable Widget" class="c4d-mega-menu-button"><i class="fa fa-gear"></i></span>';
				$(el).find('.item-title').append(buttonActive);	
				var current = $(el).find('.edit-menu-item-classes').val();
				if (current.indexOf('c4d-mega-menu') > -1) {
					$(el).find('.c4d-mega-menu-button').addClass('active');
				}	
			}

			if ($(el).hasClass('menu-item-depth-1')) {
				var current = $(el).find('.edit-menu-item-classes').val(),
				col = current.match('/'+menuWidth+'[0-9]*/');
				col = (col && col.length) ? col[0].replace(menuWidth, '').trim() : 12;
				$(el).find('.item-title').append('<span title="Change Col Width" class="c4d-mega-menu-button-col"><span class="prev">-</span><span class="number">'+col+'</span><span>/12</span><span class="next">+</span></span>');
			}
		});
		
		$('.c4d-mega-menu-button').on('click', function(){
			var input = $(this).parents('.menu-item').find('.edit-menu-item-classes');
			$(this).toggleClass('active');
			if ($(this).hasClass('active')) {
				input.val('c4d-mega-menu ' + input.val().trim());
			} else {
				input.val(input.val().replace('c4d-mega-menu', '').trim());
			}
		});

		$('.c4d-mega-menu-button-col span').on('click', function(){
			var input = $(this).parents('.menu-item').find('.edit-menu-item-classes'),
			number = $(this).parent().find('.number'),
			current = parseInt(number.html());
			if ($(this).hasClass('prev')) {
				if (current > 1) {
					number.html(current - 1);	
					input.val((input.val().replace(menuWidth + current, '') + ' ' + menuWidth + (current - 1)).trim());
				}
				
			} else if ($(this).hasClass('next')) {
				if (current < 12) {
					number.html(current + 1);		
					input.val((input.val().replace(menuWidth + current, '') + ' ' + menuWidth + (current + 1)).trim());
				}
			}
		});
	});
})(jQuery);