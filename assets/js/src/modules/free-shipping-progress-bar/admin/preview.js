(function ($) {
	'use strict';

	$(document).on('change.merchant keyup', function () {
		initPreview();
	});

	function initPreview() {
		let freeShippingText = $('.merchant-field-free_shipping_text input').val(),
			textColor = $('.merchant-field-text_color input').val(),
			backgroundColor = $('.merchant-field-background_color input').val(),
			foregroundColor = $('.merchant-field-foreground_color input').val(),
			barHeight = $('.merchant-field-bar_height input').val(),
			borderRadiusUnit = $('.merchant-field-select_border_radius select').val(),
			borderTopLeftRadius = $('.merchant-field-select_border_radius #merchant-select_border_radius-top').val(),
			borderTopRightRadius = $('.merchant-field-select_border_radius #merchant-select_border_radius-right').val(),
			borderBottomRightRadius = $('.merchant-field-select_border_radius #merchant-select_border_radius-bottom').val(),
			borderBottomLeftRadius = $('.merchant-field-select_border_radius #merchant-select_border_radius-left').val();

		$('.merchant-freespb-text').html(freeShippingText.replace('{amount_left}', merchantFreeShippingProgressBar.amount)).css('color', textColor);

		$('.merchant-freespb-progress-bar').css({
			'background-color': backgroundColor,
			'height': barHeight + 'px',
			'border-top-left-radius': borderTopLeftRadius + borderRadiusUnit,
			'border-top-right-radius': borderTopRightRadius + borderRadiusUnit,
			'border-bottom-right-radius': borderBottomRightRadius + borderRadiusUnit,
			'border-bottom-left-radius': borderBottomLeftRadius + borderRadiusUnit
		});

		$('.merchant-freespb-progress-bar-inner').css({
			'background-color': foregroundColor,
			'height': barHeight + 'px',
			'border-top-left-radius': borderTopLeftRadius + borderRadiusUnit,
			'border-top-right-radius': borderTopRightRadius + borderRadiusUnit,
			'border-bottom-right-radius': borderBottomRightRadius + borderRadiusUnit,
			'border-bottom-left-radius': borderBottomLeftRadius + borderRadiusUnit
		});

	}

	$(document).ready(function () {
		initPreview();
	});
})(jQuery);