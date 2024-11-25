'use strict';
(function ($) {
	class MerchantRecentSalesNotificationsColorRecommender {
		constructor(config) {
			this.config = config;
		}

		rgbToLuminance(r, g, b) {
			[r, g, b] = [r, g, b].map(c => {
				c /= 255;
				return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
			});
			return 0.2126 * r + 0.7152 * g + 0.0722 * b;
		}

		analyzeColor(color) {
			const [r, g, b] = color.match(/\d+/g).map(Number);
			const luminance = this.rgbToLuminance(r, g, b);
			return luminance > 0.5 ? 'dark-text-color-scheme' : 'light-text-color-scheme';
		}

		// Method to recommend text color based on background color or image
		colorRecommender() {
			let self = this;
			let widget = $(this.config.widgetSelector);
			let bgColor = widget.css("background-color");
			let bgImage = widget.css("background-image");

			// If background image is detected, analyze the dominant color in the center
			if (bgImage && bgImage !== 'none') {
				let imageUrl = bgImage.slice(5, -2); // Extract URL without `url("")`

				let img = new Image();
				img.crossOrigin = "Anonymous"; // Enable CORS for cross-origin images
				img.src = imageUrl;

				img.onload = function () {
					let canvas = document.createElement('canvas');
					let ctx = canvas.getContext('2d');
					let {width, height} = img;

					// Set canvas size to the center portion of the image for average color sampling
					canvas.width = canvas.height = 1;
					ctx.drawImage(img, width / 2, height / 2, 1, 1, 0, 0, 1, 1);

					let pixelData = ctx.getImageData(0, 0, 1, 1).data;
					let [r, g, b] = pixelData;

					// Recommend text color based on image's center color luminance
					const recommendedColor = self.analyzeColor(`rgb(${r}, ${g}, ${b})`);
					widget.removeClass('light-text-color-scheme dark-text-color-scheme');
					widget.addClass(recommendedColor);
				};

				return 'light-text-color-scheme'; // Temporary default class while image loads
			} else if (/^rgb/.test(bgColor)) {
				// If no background image, analyze background color
				let recommendedColor = self.analyzeColor(bgColor);
				widget.removeClass('light-text-color-scheme dark-text-color-scheme');
				widget.addClass(recommendedColor);
				return recommendedColor;
			}

			// Default to 'dark' if no valid color is detected
			return 'dark-text-color-scheme';
		}
	}

	/**
	 * Initializes the preview of the recent sales notifications.
	 *
	 * This function retrieves the current values of various form fields and uses them to update the styles of the progress bar.
	 *
	 * @return {void}
	 */
	function merchantInitPreview(e = null) {
		let widget = $('.merchant-recent-sales-notifications-widget'),
			textColoring = $('.merchant-field-text_coloring input:checked').val(),
			layout = $('.merchant-field-layout input:checked').val(),
			themeType = $('.merchant-field-theme_type input:checked').val(),
			theme = $('.merchant-field-theme input:checked').val(),
			customBgImage = $('.merchant-field-background_image input'),
			bgColor = $('.merchant-field-background_color input').val(),
			borderColor = $('.merchant-field-border_color input').val(),
			messageColor = $('.merchant-field-message_color input').val(),
			productNameColor = $('.merchant-field-product_name_color input').val(),
			timeColor = $('.merchant-field-time_color input').val(),
			hideProductImage = $('.merchant-field-hide_product_image input').is(':checked'),
			hideProductName = $('.merchant-field-hide_product_name input').is(':checked'),
			boxBorderRadius = $('.merchant-field-notification_box_radius input'),
			productImageBorderRadius = $('.merchant-field-product_image_radius input'),
			closeBtnBgColor = $('.merchant-field-close_btn_bg_color input').val(),
			closeBtnColor = $('.merchant-field-close_btn_color input').val();

		// remove all classes starts with merchant-rsn-
		widget.attr('class', function (i, c) {
			return c.replace(/(^|\s)widget-layout-\S+/g, '');
		})
		widget.addClass('widget-' + layout);

		if (e !== null && $(e.target).closest('.merchant-field-layout').length) {
			if (layout === 'layout-1' || layout === 'layout-2') {
				boxBorderRadius.val(8);
				productImageBorderRadius.val(8);
			}

			if (layout === 'layout-3') {
				boxBorderRadius.val(8);
				productImageBorderRadius.val(50);
			}

			if (layout === 'layout-4') {
				boxBorderRadius.val(60);
				productImageBorderRadius.val(50);
			}
		}

		document.documentElement.style.setProperty('--merchant-rsn-bg-color', bgColor);

		if (themeType === 'template') {
			if (customBgImage.val()) {
				let sizes = JSON.parse(customBgImage.closest('.merchant-field-background_image').find('.merchant-upload-image').attr('data-sizes'));
				if (sizes.full !== undefined) {
					document.documentElement.style.setProperty('--merchant-rsn-bg-image', 'url( "' + sizes.full.url + '")');
				} else {
					document.documentElement.style.setProperty('--merchant-rsn-bg-image', 'url( "' + MerchantRSN.merchant_url + 'assets/images/modules/recent-sales-notifications/' + theme + '.png")');
				}
			} else {
				document.documentElement.style.setProperty('--merchant-rsn-bg-image', 'url( "' + MerchantRSN.merchant_url + 'assets/images/modules/recent-sales-notifications/' + theme + '.png")');
			}
		} else {
			document.documentElement.style.setProperty('--merchant-rsn-bg-image', 'none');
		}

		// Change border color
		document.documentElement.style.setProperty('--merchant-rsn-border-color', borderColor);

		// Change close button background color
		document.documentElement.style.setProperty('--merchant-rsn-close-btn-bg', closeBtnBgColor);

		// Change box radius
		document.documentElement.style.setProperty('--merchant-rsn-box-radius', boxBorderRadius.val() + 'px');

		// Change product image border radius
		document.documentElement.style.setProperty('--merchant-rsn-box-product-image-radius', productImageBorderRadius.val() + 'px');

		widget.find('.merchant-notification-message').removeAttr('style');
		widget.find('.merchant-notification-title').removeAttr('style');
		widget.find('.merchant-notification-time').removeAttr('style');

		setTimeout(function () {
			if (textColoring === 'auto') {
				let colorRecommender = new MerchantRecentSalesNotificationsColorRecommender({
					widgetSelector: '.merchant-recent-sales-notifications-widget'
				});

				colorRecommender.colorRecommender();
			}
		}, 100);

		if (textColoring === 'manual') {
			widget.find('.merchant-notification-message').css('color', messageColor);
			widget.find('.merchant-notification-title').css('color', productNameColor);
			widget.find('.merchant-notification-time').css('color', timeColor);
		}

		if (hideProductImage) {
			widget.find('.merchant-notification-image').hide();
		} else {
			widget.find('.merchant-notification-image').show();
		}

		if (hideProductName){
			widget.find('.merchant-notification-title').hide();
		}else{
			widget.find('.merchant-notification-title').show();
		}

		$('.merchant-recent-sales-notifications-widget svg path').attr('fill', closeBtnColor);
	}

	$(document).on('change.merchant keyup click', 'input[type="text"]:not(.merchant-color-input)', function (e) {
		let widget = $('.merchant-recent-sales-notifications-widget');
		let message = widget.find('.merchant-notification-message');
		message.text(
			$(this).val()
				.replace(
					'{customer_name}',
					'John Doe'
				)
				.replace(
					'{country_code}',
					'US'
				)
				.replace(
					'{city}',
					'New York'
				)
				.replace(
					'{count}',
					'7'
				)
		);
	});

	$(document).on('change.merchant keyup', function (e) {
		merchantInitPreview(e);
	});

	$(document).ready(function () {
		merchantInitPreview();
	});
})(jQuery);