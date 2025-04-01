(function ($) {
	'use strict';

	$(document).on('click', '.merchant-flexible-content-control.complementary-products-style .layout', function () {
		var $this = $(this);
		var $parent = $this.closest('.merchant-flexible-content-control.complementary-products-style');
		$parent.find('.layout').removeClass('active');
		$this.addClass('active');
		initPreview();
	});

	$(document).on('change.merchant keyup', function () {
		initPreview();
	});

	function initPreview() {
		let layout = $('.merchant-flexible-content-control.complementary-products-style').find('.layout.active'),

			singleBundleTitle = layout.find('.merchant-group-field-product_single_page .merchant-field-offer-title input').val(),
			singleBundleDescription = layout.find('.merchant-group-field-product_single_page .merchant-field-offer-description textarea').val(),
			singleTitleDescAlignment = $('.merchant-field-title_description_alignment input:checked').val(),
			singleLayout = $('.merchant-field-layout input:checked').val(),
			headingSize = $('.merchant-field-title_heading_size select').val(),
			bundleDescFontSize = $('.merchant-field-description_font_size input').val(),
			productBorderRadius = $('.merchant-field-border_radius input').val(),
			productImageBorderRadius = $('.merchant-field-image_border_radius input').val(),
			bundleCheckboxStyle = $('.merchant-field-checkbox_style input:checked').val(),
			bundleCheckboxColor = $('.merchant-field-checkbox_color input').val(),
			bundleTitleColor = $('.merchant-field-title_color input').val(),
			bundleDescColor = $('.merchant-field-description_color input').val(),
			borderColor = $('.merchant-field-border_color input').val(),
			productImageBorderColor = $('.merchant-field-image_border_color input').val(),

			cartBundleTitle = layout.find('.merchant-group-field-cart_page .merchant-field-title input').val(),
			cartBundleBtnTitle = layout.find('.merchant-group-field-cart_page .merchant-field-button_text input').val(),

			checkoutBundleBtnTitle = layout.find('.merchant-group-field-checkout_page .merchant-field-button_text input').val(),
			checkoutBundleTitle = layout.find('.merchant-group-field-checkout_page .merchant-field-title input').val(),
			checkoutBundleDesc = layout.find('.merchant-group-field-checkout_page .merchant-field-offer_description textarea').val(),

			thankYouBundleBtnTitle = layout.find('.merchant-group-field-thank_you_page .merchant-field-button_text input').val(),
			thankYouBundleTitle = layout.find('.merchant-group-field-thank_you_page .merchant-field-title input').val(),
			thankYouDiscountText = layout.find('.merchant-group-field-thank_you_page .merchant-field-discount_text input').val()


		let title = $('.mrc-preview-bundle-title');
		title.replaceWith(`<${headingSize} class="mrc-preview-bundle-title">${title.text()}</${headingSize}>`);
		title.text(singleBundleTitle);
		$('.mrc-preview-bundle-wrapper').removeClass('slider compact classic').addClass(singleLayout);
		$('.mrc-preview-bundle-description').text(singleBundleDescription.replace('{discount_amount}', '10%'));

		$(":root").css({
			"--merchant-cp-product-border-color": borderColor,
			"--merchant-cp-bundle-title-desc-align": singleTitleDescAlignment,
			"--merchant-cp-product-border-radius": productBorderRadius + 'px',
			"--merchant-cp-product-image-border-radius": productImageBorderRadius + 'px',
			"--merchant-cp-bundle-desc-font-size": bundleDescFontSize + 'px',
			"--merchant-cp-product-image-border-color": productImageBorderColor,
			"--merchant-cp-bundle-title-color": bundleTitleColor,
			"--merchant-cp-bundle-desc-text-color": bundleDescColor,
			"--merchant-cp-checkbox-color": bundleCheckboxColor,
			"--merchant-cp-product-checkbox-radius": bundleCheckboxStyle === 'rounded' ? '5px' : '0px',
		});

		$('.merchant-cart-preview .upsell-offer').text(cartBundleTitle.replace('{discount_amount}', '10%'));
		$('.merchant-cart-preview .add-to-cart').text(cartBundleBtnTitle);

		$('.merchant-checkout-preview .offer-title').text(checkoutBundleTitle.replace('{discount_amount}', '10%'));
		$('.merchant-checkout-preview .offer-desc').text(checkoutBundleDesc.replace('{discount_amount}', '10%'));
		$('.merchant-checkout-preview .add-to-order').text(checkoutBundleBtnTitle);

		$('.merchant-thank-you-preview .offer-title').text(thankYouBundleTitle.replace('{discount_amount}', '10%'));
		$('.merchant-thank-you-preview .add-to-order').text(thankYouBundleBtnTitle);
		$('.merchant-thank-you-preview .discount-text').text(thankYouDiscountText.replace('{discount_amount}', '10%'));
	}

	$('.merchant-flexible-content-control .layout:first-child').addClass('active').trigger('click');

	function show_single_product_preview() {
		let element = $('.merchant-single-product-preview');
		element.addClass('show');
	}

	function hide_single_product_preview() {
		let element = $('.merchant-single-product-preview');
		element.removeClass('show');
	}

	function show_cart_page_preview() {
		let element = $('.merchant-cart-preview');
		element.addClass('show');
	}

	function hide_cart_page_preview() {
		let element = $('.merchant-cart-preview');
		element.removeClass('show');
	}

	function show_thank_you_page_preview() {
		let element = $('.merchant-thank-you-preview');
		element.addClass('show');
	}

	function hide_thank_you_page_preview() {
		let element = $('.merchant-thank-you-preview');
		element.removeClass('show');
	}

	function show_checkout_page_preview() {
		let element = $('.merchant-checkout-preview');
		element.addClass('show');
	}

	function hide_checkout_page_preview() {
		let element = $('.merchant-checkout-preview');
		element.removeClass('show');
	}

	$.fn.dragScroll = function () {
		return this.each(function () {
			const $element = $(this);
			let isDown = false;
			let startX;
			let scrollLeft;

			$element
				.css('cursor', 'grab')
				.on('mousedown', function (e) {
					isDown = true;
					$(this)
						.addClass('active')
						.css('cursor', 'grabbing');
					startX = e.pageX - $(this).offset().left;
					scrollLeft = $(this).scrollLeft();
				})
				.on('mouseleave mouseup', function () {
					isDown = false;
					$(this)
						.removeClass('active')
						.css('cursor', 'grab');
				})
				.on('mousemove', function (e) {
					if (!isDown) return;
					e.preventDefault();
					const x = e.pageX - $(this).offset().left;
					const walk = (x - startX) * 2;
					$(this).scrollLeft(scrollLeft - walk);
				});
		});
	};

	$('.merchant-module-page-setting-box').on('click', function (e) {
		let clickedElement = $(e.target);

		if (clickedElement.closest('.merchant-group-field-cart_page').length > 0 || clickedElement.hasClass('merchant-group-field-cart_page')) {
			show_cart_page_preview();
			hide_single_product_preview();
			hide_thank_you_page_preview();
			hide_checkout_page_preview();
		} else if (clickedElement.closest('.merchant-group-field-thank_you_page').length > 0 || clickedElement.hasClass('merchant-group-field-thank_you_page')) {
			show_thank_you_page_preview();
			hide_single_product_preview();
			hide_cart_page_preview();
			hide_checkout_page_preview()
		} else if (clickedElement.closest('.merchant-group-field-checkout_page').length > 0 || clickedElement.hasClass('merchant-group-field-checkout_page')) {
			show_checkout_page_preview();
			hide_single_product_preview();
			hide_cart_page_preview();
			hide_thank_you_page_preview();
		} else {
			show_single_product_preview();
			hide_cart_page_preview();
			hide_thank_you_page_preview();
			hide_checkout_page_preview();
		}
	});

	show_single_product_preview();

	$('.products').dragScroll();
})(jQuery);