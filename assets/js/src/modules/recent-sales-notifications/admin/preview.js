'use strict';
(function ($) {

	/**
	 * Set max value for the input field based on the unit
	 *
	 * @param element The input field
	 * @param unit The unit of the input field
	 */
	function setMaxValue(element, unit) {
		if('WEEK' === unit) {
			element.attr('max', 4);
			if (element.val() > 4) {
				element.val(4);
			}
		}
		if('DAY' === unit) {
			element.attr('max', 30);
			if (element.val() > 30) {
				element.val(30);
			}
		}
		if('HOUR' === unit) {
			element.attr('max', 720);
			if (element.val() > 720) {
				element.val(720);
			}
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
			closeBtnColor = $('.merchant-field-close_btn_color input').val(),
			recentPurchasesUnit = $('.merchant-field-single_product_purchase .merchant-field-time_unit select').val(),
			cartNotificationUnit = $('.merchant-field-single_product_add_to_cart .merchant-field-time_unit select').val(),
			visitorsCountUnit = $('.merchant-field-product_views_settings .merchant-field-time_unit select').val();

		setMaxValue($('.merchant-field-single_product_purchase .merchant-field-time_span input'), recentPurchasesUnit);
		setMaxValue($('.merchant-field-single_product_add_to_cart .merchant-field-time_span input'), cartNotificationUnit);
		setMaxValue($('.merchant-field-product_views_settings .merchant-field-time_span input'), visitorsCountUnit);

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

		widget.find('.merchant-notification-message').css('color', messageColor);
		widget.find('.merchant-notification-title').css('color', productNameColor);
		widget.find('.merchant-notification-time').css('color', timeColor);

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

		const customerName = getFormattedCustomerName( $( '.merchant-field-customer_name_format select' ).val() );

		message.html(
			$(this).val()
				.replace(
					'{customer_name}',
					`<span class="customer-name">${customerName}</span>`
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

	$(document).on('change', '.merchant-field-customer_name_format select', function (e) {
		const customerName = getFormattedCustomerName( $( this ).val() );
		$('.customer-name').text( customerName );
	});

	$(document).on('change.merchant keyup', function (e) {
		merchantInitPreview(e);
	});

	$(document).ready(function () {
		merchantInitPreview();
	});


	// Helpers
	function getFormattedCustomerName( format ) {
		let customerName;
		switch ( format ) {
			case 'firstname':
				customerName = 'John';
				break;

			case 'lastname':
				customerName = 'Doe';
				break;

			case 'firstname_initial':
				customerName = 'John D.';
				break;

			case 'lastname_initial':
				customerName = 'J. Doe';
				break;

			case 'full':
			default:
				customerName = 'John Doe';
		}

		return customerName;
	}

})(jQuery);