/**
 * Merchant side cart
 *
 */
jQuery(document).ready(function ($) {
	'use strict';

	/**
	 * Check if the current device is allowed to show the side cart.
	 *
	 * @returns {boolean}
	 */
	function merchant_is_allowed_device() {
		let allowed_devices = merchant_side_cart_params.allowed_devices;
		let screenWidth = window.innerWidth;
		if (screenWidth <= 768 && allowed_devices.includes('mobile')) {
			return true;
		} else if (screenWidth > 768 && allowed_devices.includes('desktop')) {
			return true;
		}

		return false;
	}

	// Toggle side cart
	if (merchant.setting.hasOwnProperty('show_after_add_to_cart_single_product') && merchant_is_allowed_device()) {
		const isSingleProductPage = $('body.single-product').length;
		const isNoticeVisible = $('.woocommerce-notices-wrapper').is(':visible') && !$('.woocommerce-notices-wrapper').is(':empty')
		const isBlockNoticeVisible = $('.wc-block-components-notice-banner').is(':visible') && !$('.wc-block-components-notice-banner').is(':empty')

		if (isSingleProductPage && (isNoticeVisible || isBlockNoticeVisible)) {
			$('body').toggleClass('merchant-floating-side-mini-cart-show');
			$(window).trigger('merchant.floating-mini-cart-resize');
		}
	}

	// Add to cart AJAX event.
	if (merchant.setting.hasOwnProperty('add_to_cart_slide_out') && merchant_is_allowed_device()) {
		$(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button, $context) {
			if ($context !== 'side-cart') {
				$('body').toggleClass('merchant-floating-side-mini-cart-show');
			}
			$(window).trigger('merchant.floating-mini-cart-resize');
		});
	}

	// On cart URL click
	if (merchant.setting.hasOwnProperty('cart_url') && merchant_is_allowed_device()) {
		$('[href="' + merchant.setting.cart_url + '"]').on('click', function (e) {
			e.preventDefault();
			$(window).trigger('merchant.floating-mini-cart-resize');
			$('body').toggleClass('merchant-floating-side-mini-cart-show');
		});
	}

	// Update Product quantity in Side Cart
	if ((merchant.setting.hasOwnProperty('add_to_cart_slide_out') || merchant.setting.hasOwnProperty('floating_mini_cart_count')) && merchant_is_allowed_device()) {
		// Update quantity on plus/minus click
		$(document).on('click', '.js-merchant-quantity-btn', function (e) {
			e.preventDefault();

			const $btn = $(this);

			const $input = $btn.closest('.merchant-quantity-wrap').find('.js-update-quantity');
			if (!$input.length) {
				return;
			}

			let quantity = +($input.val() || 1);
			const minimum = +($input.attr('min'));
			const maximum = +($input.attr('max'));
			const stepSize = Math.round(parseFloat($input.attr('step')));

			if ($btn.hasClass('merchant-quantity-plus')) {
				quantity += stepSize;
				quantity = maximum && maximum !== -1 ? Math.min(quantity, maximum) : quantity;
			} else if ($btn.hasClass('merchant-quantity-minus')) {
				quantity -= stepSize;
				quantity = minimum ? Math.max(quantity, minimum) : quantity;
			}

			$input.val(quantity);
			merchant_update_side_cart_quantity($input);
		});

		// Update quantity on input value change
		$(document).on('input change', '.js-update-quantity', function (e) {
			e.preventDefault();
			merchant_update_side_cart_quantity($(this));
		});

		// Update quantity helper
		let debounceTimer;

		function merchant_update_side_cart_quantity($input) {
			const {ajax_url, side_cart_nonce} = merchant.setting || {};

			if (!$input.length || !ajax_url || !side_cart_nonce) {
				return;
			}

			const cartItemKey = $input.attr('name');
			const quantity = Math.round(parseFloat($input.val() || 1));

			const $cart_item = $input.closest('.js-side-cart-item');

			// Clear previous timer
			clearTimeout(debounceTimer);

			// Set a new timer to delay the AJAX request
			debounceTimer = setTimeout(
				() => {
					$.ajax({
						type: 'POST',
						url: ajax_url,
						data: {
							action: 'update_side_cart_quantity',
							cart_item_key: cartItemKey,
							quantity: quantity,
							nonce: side_cart_nonce,
						},
						beforeSend: function () {
							if ($cart_item.length) {
								$cart_item.block({
									message: null,
									overlayCSS: {
										background: '#fff',
										opacity: 0.6,
									},
								});
							}
						},
						success: (response) => {
							if (!response || !response.fragments) {
								return;
							}

							$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $input, 'side-cart']);

							if ($cart_item.length) {
								$cart_item.unblock();
								$(document).trigger('merchant_destroy_carousel');
								$(document).trigger('merchant_init_carousel');
							}
						},
						error: (error) => {
							console.log('Error:', error);
						},
					});
				},
				350
			);
		}
	}

	let merchant_upsells = {
		init: function () {
			let self = this;
			this.bindEvents();

			setTimeout(function () {
				$(document).trigger('merchant_init_carousel');

				// Pause the slider on hover
				$(document).find('.woocommerce-mini-cart-item.merchant-upsell-widget').on('mouseenter', function () {
					if ($(document).find('.merchant-mini-cart-upsells.upsells-layout-carousel').hasClass('slick-initialized')) {
						$('.merchant-mini-cart-upsells.upsells-layout-carousel').slick('slickPause');
					}
				});

				// Resume the slider on mouse leave
				$(document).find('.woocommerce-mini-cart-item.merchant-upsell-widget').on('mouseleave', function () {
					if ($(document).find('.merchant-mini-cart-upsells.upsells-layout-carousel').hasClass('slick-initialized')) {
						$('.merchant-mini-cart-upsells.upsells-layout-carousel').slick('slickPlay');
					}
				});

				self.sideCartBtns();
			}, 500);
		},
		bindEvents: function () {
			$(document).on('change', '.merchant-mini-cart-upsell-item-wrap .variation-selector', this.handleVariationChange.bind(this));
			$(document).on('click', '.add-to-cart-wrap .merchant-upsell-add-to-cart:not(.disabled)', this.handleAddToCartClick.bind(this));
			$(document).on('click', '.merchant-coupon-form button', this.handleCouponBtnClick.bind(this));
			$(document).on('click', '.merchant-remove-coupon', this.handleCouponRemoveClick.bind(this));
			$(document).on('merchant_init_carousel', this.initCarousel.bind(this));
			$(document).on('merchant_destroy_carousel', this.destroyCarousel.bind(this));
			$(document).on('added_to_cart', this.handleAddToCart.bind(this));
			$(document).on('removed_from_cart', this.handleRemoveFromCart.bind(this));
		},
		handleVariationChange: function (event) {
			let variationField = $(event.target),
				container = variationField.closest('.merchant-mini-cart-upsell-item-wrap'),
				variations = container.attr('data-variations') && JSON.parse(container.attr('data-variations')) || [],
				dropDowns = container.find('.variation-selector');
			container.attr('data-variation-id', 0); // reset variation ID
			let currentField = {
				name: $(event.target).attr('data-attribute_name'),
				value: $(event.target).val()
			}
			let availableOptions = [];
			let matchingVariations = variations.filter(
				function (variation) {
					return typeof variation.attributes[currentField.name.toLowerCase()] !== 'undefined'
						&& variation.attributes[currentField.name.toLowerCase()] === currentField.value;
				}
			);

			// Hide not available options
			dropDowns.each(function () {
				let dropdown = $(this);
				let attribute_name = dropdown.attr('data-attribute_name');
				// Collect available options for this attribute
				matchingVariations.forEach(function (variation) {
					let optionValue = variation.attributes[attribute_name.toLowerCase()];
					if (typeof optionValue !== 'undefined' && optionValue !== '' && !availableOptions.includes(optionValue)) {
						availableOptions.push(optionValue);
					}
				});
				if (currentField.name.toLowerCase() !== attribute_name.toLowerCase()) {
					dropdown.find('option').each(function () {
						let optionValue = $(this).attr('value');
						if (optionValue !== '') {
							if (availableOptions.includes(optionValue)) {
								$(this).show();
							} else {
								$(this).hide();
							}
						}
					});
				}
			});

			if (this.isAllVariationsSelected(container)) {
				this.fetchVariationDetails(container, container.attr('data-product-id'), this.getSelectedAttributes(container), this);
				// ajax call here to get product information...
				this.handleAddToCartBtnState(container, true);
			} else {
				this.handleAddToCartBtnState(container, false);
			}
		},
		/**
		 * Fetches variation details via AJAX.
		 *
		 * @param {Object} container - The container element.
		 * @param {Object} productID - The product ID.
		 * @param {Object} selectedAttributes - The selected variation attributes.
		 * @param {Object} self - The current object.
		 *
		 * @return {void}
		 */
		fetchVariationDetails: function (container, productID, selectedAttributes, self) {
			$.ajax({
				type: 'POST',
				url: merchant_side_cart_params.ajax_url,
				data: {
					action: 'merchant_get_variation_data',
					product_id: productID,
					nonce: merchant_side_cart_params.variation_info_nonce,
					attributes: selectedAttributes
				},
				success: function (response) {
					if (response.success) {
						console.log(response.data)
						container.attr('data-variation-id', response.data.id);
						self.updateProductThumbnail(container, response.data.thumbnail_url);
					}
				},
				error: function (error) {
					console.log('Error:', error);
				}
			});
		},
		updateProductThumbnail: function (container, thumbnailUrl) {
			let productThumbnail = container.find('.product-thumbnail a img');
			productThumbnail.attr('src', thumbnailUrl);
		},
		getSelectedAttributes: function (container) {
			let attributes = {};
			container.find('.variation-selector').each(function () {
				attributes[$(this).attr('name')] = $(this).val();
			});
			return attributes;
		},
		handleAddToCartBtnState: function (container, allSelected) {
			let btn = container.find('.add-to-cart-wrap .merchant-upsell-add-to-cart');
			if (allSelected) {
				btn.removeClass('disabled');
				btn.prop('disabled', false);
			} else {
				btn.addClass('disabled');
				btn.prop('disabled', true);
			}
		},
		isAllVariationsSelected: function (container) {
			let variationFields = container.find('.variation-selector');
			return variationFields.length && variationFields.toArray().every(function (field) {
				return $(field).val() !== '';
			});
		},
		handleAddToCartClick: function (event) {
			event.preventDefault();
			let self = this,
				btn = $(event.currentTarget),
				container = btn.closest('.merchant-mini-cart-upsell-item-wrap'),
				productType = container.attr('data-product-type'),
				productId = container.attr('data-product-id'),
				variationId = container.attr('data-variation-id');

			if (productType === 'variable' && variationId !== '0') {
				this.addToCart(self, 'variable', productId, variationId, btn);
			} else if (productType === 'simple') {
				this.addToCart(self, 'simple', productId, variationId, btn);
			} else {
				console.log('Unsupported product type:', productType);
			}
		},
		addToCart: function (self, productType, productId, variationId, btn) {
			let data = {
				action: 'merchant_side_cart_upsells_add_to_cart',
				product_id: productId,
				variation_id: variationId,
				nonce: merchant_side_cart_params.nonce
			}
			$.ajax({
				type: 'POST',
				url: merchant_side_cart_params.ajax_url,
				data: data,
				beforeSend: function () {
					btn.addClass('loading');
				},
				success: function (response) {
					self.handleSuccess(response);
				},
				error: function (error) {
					self.handleError(error);
				},
				complete: function () {
					btn.removeClass('loading');
				}
			});
		},
		handleSuccess: function (response) {
			if (response.data.fragments) {
				$(document).trigger('merchant_destroy_carousel');
				$(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, null, 'side-cart']);
				$(document).trigger('merchant_init_carousel');
			}
		},
		handleError: function (error) {
			console.log('Error:', error);
		},
		handleAddToCart: function (event, fragments, cart_hash, $button, $context) {
			$(document).trigger('merchant_destroy_carousel');
			$(document).trigger('merchant_init_carousel');
		},
		handleRemoveFromCart: function (event) {
			$(document).trigger('merchant_destroy_carousel');
			$(document).trigger('merchant_init_carousel');
		},
		initCarousel: function () {
			// check if slick is initialized
			let carousel = $(document).find('.merchant-mini-cart-upsells.upsells-layout-carousel');
			if ('carousel' === merchant_side_cart_params.upsells_style && !carousel.hasClass('slick-initialized')) {
				carousel.slick({
					infinite: true,
					arrows: true,
					slidesToShow: 1,
					dots: false,
					autoplay: false,
					autoplaySpeed: 2000,
					fade: true,
					cssEase: 'linear',
					pauseOnFocus: true,
					pauseOnHover: true,
					prevArrow: '<button type="button" class="slick-prev"><</button>',
					nextArrow: '<button type="button" class="slick-next">></button>',
					rtl: merchant_side_cart_params.is_rtl === '1'
				});
			}
		},
		destroyCarousel: function () {
			// check if slick is initialized
			let carousel = $(document).find('.merchant-mini-cart-upsells.upsells-layout-carousel');
			if ('carousel' === merchant_side_cart_params.upsells_style && carousel.hasClass('slick-initialized')) {
				carousel.slick('unslick');
			}
		},
		handleCouponBtnClick: function (event) {
			event.preventDefault();
			let self = this,
				btn = $(event.currentTarget),
				container = btn.closest('.merchant-coupon-form'),
				couponCode = container.find('.coupon_code').val();
			if (couponCode === '') {
				return;
			}
			this.applyCoupon(self, couponCode, container);
		},
		applyCoupon: function (self, couponCode, container) {
			let data = {
				action: 'merchant_side_cart_apply_coupon',
				coupon_code: couponCode,
				nonce: merchant_side_cart_params.nonce
			}
			$.ajax({
				type: 'POST',
				url: merchant_side_cart_params.ajax_url,
				data: data,
				beforeSend: function () {
					container.addClass('loading');
				},
				success: function (response) {
					self.handleCouponSuccess(response);
				},
				error: function (error) {
					self.handleCouponError(error);
				},
				complete: function () {
					container.removeClass('loading');
				}
			});
		},
		removeCoupon: function (self, couponCode) {
			let data = {
				action: 'merchant_side_cart_remove_coupon',
				coupon_code: couponCode,
				nonce: merchant_side_cart_params.nonce
			}
			$.ajax({
				type: 'POST',
				url: merchant_side_cart_params.ajax_url,
				data: data,
				beforeSend: function () {
				},
				success: function (response) {
					self.handleCouponSuccess(response);
				},
				error: function (error) {
					self.handleCouponError(error);
				}
			});
		},
		handleCouponSuccess: function (response) {
			if (response.fragments !== undefined) {
				$(document).trigger('merchant_destroy_carousel');
				$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, null, 'side-cart']);
				$(document).trigger('merchant_init_carousel');
			}
		},
		handleCouponError: function (error) {
			console.log('Error:', error);
		},
		handleCouponRemoveClick: function (event) {
			event.preventDefault();
			let self = this,
				btn = $(event.currentTarget),
				couponCode = btn.attr('data-coupon');
			console.log(btn);
			this.removeCoupon(self, couponCode);
		},
		sideCartBtns: function () {
			console.log('xxxx');
			let showCheckoutBtn = merchant_side_cart_params.show_checkout_btn === '1',
				showViewCartBtn = merchant_side_cart_params.show_view_cart_btn === '1',
				checkoutBtnTxt = merchant_side_cart_params.checkout_btn_text,
				viewCartBtnTxt = merchant_side_cart_params.view_cart_btn_text,
				buttonsWrapper = $(document).find('.merchant-floating-side-mini-cart-body .woocommerce-mini-cart__buttons'),
				checkoutBtn = buttonsWrapper.find('.checkout'),
				viewCartBtn = buttonsWrapper.find('a:not(.checkout)');

			if (!showCheckoutBtn && !showViewCartBtn) {
				buttonsWrapper.hide();
				return;
			}
			if (!showCheckoutBtn) {
				checkoutBtn.hide();
			}
			if (!showViewCartBtn) {
				viewCartBtn.hide();
			}

			if (checkoutBtnTxt !== '') {
				checkoutBtn.text(checkoutBtnTxt);
			}

			if (viewCartBtnTxt !== '') {
				viewCartBtn.text(viewCartBtnTxt);
			}
		}
	}

	merchant_upsells.init();
});
