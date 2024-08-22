(function ($) {
	'use strict';
	console.log(merchantFreeShippingProgressBar.presets);
	$(document).on('change', '.merchant-field-preset select', function (e) {
		let value = $(this).val();
		merchantUpdatePreview(value);
	});

	/**
	 * Updates the preview based on the provided preset value.
	 *
	 * @param {string} value - The value of the preset to update the preview with.
	 * @return {void}
	 */
	function merchantUpdatePreview(value) {
		if (merchantFreeShippingProgressBar.presets[value]) {
			let preset = merchantFreeShippingProgressBar.presets[value];

			$('.merchant-field-card_text_color input').val(preset.card_text_color).trigger('change');
			$('.merchant-field-variable_text_color input').val(preset.variable_text_color).trigger('change');
			$('.merchant-field-card_font_size input').val(preset.card_font_size).trigger('change');
			$('.merchant-field-card_background_color input').val(preset.card_background_color).trigger('change');
			$('.merchant-field-card_padding_top input').val(preset.card_padding_top).trigger('change');
			$('.merchant-field-card_padding_bottom input').val(preset.card_padding_bottom).trigger('change');
			$('.merchant-field-card_padding_left input').val(preset.card_padding_left).trigger('change');
			$('.merchant-field-card_padding_right input').val(preset.card_padding_right).trigger('change');
			$('.merchant-field-card_border_radius input').val(preset.card_border_radius).trigger('change');
			$('.merchant-field-card_border_width input').val(preset.card_border_width).trigger('change');
			$('.merchant-field-card_border_color input').val(preset.card_border_color).trigger('change');
			$('.merchant-field-bar_background_color input').val(preset.bar_background_color).trigger('change');
			$('.merchant-field-bar_foreground_color input').val(preset.bar_foreground_color).trigger('change');
			$('.merchant-field-bar_border_radius input').val(preset.bar_border_radius).trigger('change');
			$('.merchant-field-bar_height input').val(preset.bar_height).trigger('change');
			$('.merchant-field-bar_width input').val(preset.bar_width).trigger('change');

			initPreview();
		}
	}

	$(document).on('change.merchant keyup', function () {
		merchantInitPreview();
	});

	/**
	 * Initializes the preview of the free shipping progress bar.
	 *
	 * This function retrieves the current values of various form fields and uses them to update the styles of the progress bar.
	 *
	 * @return {void}
	 */
	function merchantInitPreview() {
		let freeShippingText = $('.merchant-field-free_shipping_text input').val(),
			textColor = $('.merchant-field-card_text_color input').val(),
			variableTextColor = $('.merchant-field-variable_text_color input').val(),
			fontSize = $('.merchant-field-card_font_size input').val(),
			cardBackgroundColor = $('.merchant-field-card_background_color input').val(),
			cardPaddingTop = $('.merchant-field-card_padding_top input').val(),
			cardPaddingBottom = $('.merchant-field-card_padding_bottom input').val(),
			cardPaddingLeft = $('.merchant-field-card_padding_left input').val(),
			cardPaddingRight = $('.merchant-field-card_padding_right input').val(),
			cardBorderRadius = $('.merchant-field-card_border_radius input').val(),
			cardBorderWidth = $('.merchant-field-card_border_width input').val(),
			CardBorderColor = $('.merchant-field-card_border_color input').val(),
			barBackgroundColor = $('.merchant-field-bar_background_color input').val(),
			barForegroundColor = $('.merchant-field-bar_foreground_color input').val(),
			barBorderRadius = $('.merchant-field-bar_border_radius input').val(),
			barHeight = $('.merchant-field-bar_height input').val(),
			barWidth = $('.merchant-field-bar_width input').val();

		$('.merchant-freespb-text').html(freeShippingText.replace('{amount_left}', merchantFreeShippingProgressBar.amount)).css({
			'color': textColor,
			'font-size': fontSize + 'px'
		});
		$('.merchant-freespb-text span').css('color', variableTextColor);

		$('.merchant-freespb-wrapper').css({
			'background-color': cardBackgroundColor,
			'padding-top': cardPaddingTop + 'px',
			'padding-bottom': cardPaddingBottom + 'px',
			'padding-left': cardPaddingLeft + 'px',
			'padding-right': cardPaddingRight + 'px',
			'border-radius': cardBorderRadius + 'px',
			'border-width': cardBorderWidth + 'px',
			'border-color': CardBorderColor
		});

		$('.merchant-freespb-progress-bar').css({
			'background-color': barBackgroundColor,
			'height': barHeight + 'px',
			'width': barWidth + '%',
			'border-radius': barBorderRadius + 'px'
		});

		$('.merchant-freespb-progress-bar-inner').css({
			'background-color': barForegroundColor,
			'height': barHeight + 'px',
			'border-radius': barBorderRadius + 'px'
		});
	}

	$(document).ready(function () {
		merchantInitPreview();
	});
})(jQuery);