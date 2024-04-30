(function ($) {
	'use strict';

	let merchant_added_to_cart_popup_object = {
		/**
		 * Initialize the popup preview
		 */
		init: function () {
			this.events();
			this.activateLayout();
			this.updatePopupHeader();
			this.updateActionButtonsText();
			this.updatePopupColorsStyles();
			this.updateTitlePriceVisibility();
			this.updateCartDetailsVisibility();
			this.updateProductThumbVisibility();
			this.updateActionButtonsVisibility();
			this.updateSuggestedProductsVisibility();
			this.updateProductDescriptionVisibility();
		},

		/**
		 * Activate the layout
		 */
		activateLayout: function () {
			let layout = $('.merchant-image-picker').find('input:checked').val(),
				allPopups = $('.popup'),
				activePopup = $('.popup.' + layout);
			allPopups.removeClass('show');
			activePopup.addClass('show');
			this.refreshSlickSlider();
		},

		/**
		 * Refresh slick slider
		 */
		refreshSlickSlider: function () {
			let layout = $('.merchant-image-picker').find('input:checked').val(),
				activePopup = $('.popup.' + layout),
				arrowIcon = '<svg width="5" height="8" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.19824 1.14453L4.03516 3.98242L1.19824 6.81934" stroke="#E5E5E5"/></svg>';
			activePopup.find('.slider-products .products-list').each(function () {
				if ($(this).hasClass('slick-initialized')) {
					$(this).slick('unslick');
				}
				$(this).slick({
					// 4 products per slide
					slidesToShow: 3,
					prevArrow: '<button type="button" class="slick-prev">' + arrowIcon + '</button>',
					nextArrow: '<button type="button" class="slick-next">' + arrowIcon + '</button>',
					responsive: [
						{
							breakpoint: 768,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2,
								arrows: false,
							}
						}
					]
				})
			});
		},

		/**
		 * Update popup header
		 * @param val string - new value for the header
		 */
		updatePopupHeader: function (val = '') {
			let popupHeader = $('.popup-header-title');
			popupHeader.text(val ? val : $('.merchant-field-popup_message input').val());
		},

		/**
		 * Update action buttons text
		 */
		updateActionButtonsText: function () {
			$('.popup-actions .view-cart').text($('.merchant-field-view_cart_button_label input').val());
			$('.popup-actions .continue-shopping').text($('.merchant-field-view_continue_shopping_button_label input').val());
		},

		/**
		 * Update product thumbnail visibility
		 */
		updateProductThumbVisibility: function () {
			let thumbnailToggle = $('.merchant-field-show_product_info input[value="thumbnail"]'),
				thumbnailWrapper = $('.popup-product-image');

			if (!thumbnailToggle.is(':checked')) {
				thumbnailWrapper.hide();
			} else {
				thumbnailWrapper.show();
			}
		},

		/**
		 * Update product title and price visibility
		 */
		updateTitlePriceVisibility: function () {
			let titlePriceToggle = $('.merchant-field-show_product_info input[value="title_and_price"]'),
				titleWrapper = $('.popup-product-name'),
				priceWrapper = $('.popup-product-price');

			if (!titlePriceToggle.is(':checked')) {
				titleWrapper.hide();
				priceWrapper.hide();
			} else {
				titleWrapper.show();
				priceWrapper.show();
			}
		},

		/**
		 * Update product description visibility
		 */
		updateProductDescriptionVisibility: function () {
			let descriptionToggle = $('.merchant-field-show_product_info input[value="description"]'),
				descriptionWrapper = $('.popup-product-description');

			if (!descriptionToggle.is(':checked')) {
				descriptionWrapper.hide();
			} else {
				descriptionWrapper.show();
			}
		},

		/**
		 * Update cart details visibility
		 */
		updateCartDetailsVisibility: function () {
			let cartTotalToggle = $('.merchant-field-show_cart_details input[value="cart_total"]'),
				taxAmountToggle = $('.merchant-field-show_cart_details input[value="tax_amount"]'),
				shippingCostToggle = $('.merchant-field-show_cart_details input[value="shipping_cost"]'),
				cartDetailsWrapper = $('.popup-cart-info'),
				cartTotal = $('.popup-cart-info .cart-total'),
				taxAmount = $('.popup-cart-info .tax-amount'),
				shippingCost = $('.popup-cart-info .shipping-cost');

			if (
				!cartTotalToggle.is(':checked')
				&& !taxAmountToggle.is(':checked')
				&& !shippingCostToggle.is(':checked')
			) {
				cartDetailsWrapper.hide()
			} else {
				cartDetailsWrapper.show();
			}

			if (!cartTotalToggle.is(':checked')) {
				cartTotal.hide();
			} else {
				cartTotal.show();
			}

			if (!taxAmountToggle.is(':checked')) {
				taxAmount.hide();
			} else {
				taxAmount.show();
			}

			if (!shippingCostToggle.is(':checked')) {
				shippingCost.hide();
			} else {
				shippingCost.show();
			}
		},

		/**
		 * Update suggested products visibility
		 */
		updateActionButtonsVisibility: function () {
			let viewCartToggle = $('.merchant-field-show_view_cart_button input'),
				checkoutToggle = $('.merchant-field-show_checkout_button input'),
				continueShoppingToggle = $('.merchant-field-show_view_continue_shopping_button input'),
				actionButtonsWrapper = $('.popup-actions'),
				viewCartButton = $('.popup-actions .view-cart'),
				checkoutButton = $('.popup-actions .checkout'),
				continueShoppingButton = $('.popup-actions .continue-shopping');

			if (
				!viewCartToggle.is(':checked')
				&& !checkoutToggle.is(':checked')
				&& !continueShoppingToggle.is(':checked')
			) {
				actionButtonsWrapper.hide()
			} else {
				actionButtonsWrapper.show();
			}

			if (!viewCartToggle.is(':checked')) {
				viewCartButton.hide();
			} else {
				viewCartButton.show();
			}

			if (!checkoutToggle.is(':checked')) {
				checkoutButton.hide();
			} else {
				checkoutButton.show();
			}

			if (!continueShoppingToggle.is(':checked')) {
				continueShoppingButton.hide();
			} else {
				continueShoppingButton.show();
			}
		},

		/**
		 * Update suggested products visibility and content
		 */
		updateSuggestedProductsVisibility: function () {
			if ($('.merchant-field-show_suggested_products input:checked').length) {
				let suggestedProductsType = $('.merchant-field-suggested_products_module select').val(),
					allSuggestedProductsTypes = $('.suggested-products-content'),
					recentlyViewedProducts = $('.recently-viewed-products'),
					frequentlyBoughtTogether = $('.frequently-bought-together-popup'),
					relatedProducts = $('.related-products'),
					buyXGetY = $('.buy-x-get-y');
				allSuggestedProductsTypes.addClass('hidden');
				if (suggestedProductsType === 'recently_viewed_products') {
					recentlyViewedProducts.removeClass('hidden');
				} else if (suggestedProductsType === 'frequently_bought_together') {
					frequentlyBoughtTogether.removeClass('hidden');
				} else if (suggestedProductsType === 'related_products') {
					relatedProducts.removeClass('hidden');
				} else if (suggestedProductsType === 'buy_x_get_y') {
					buyXGetY.removeClass('hidden');
				}
				this.refreshSlickSlider();
				$('.suggested-products').show();
			} else {
				$('.suggested-products').hide();
			}
		},

		/**
		 * Update popup colors & styles
		 */
		updatePopupColorsStyles: function () {
			let popupBgColor = $('.merchant-field-popup_background_color input').val(),
				popupCornerRadius = $('.merchant-field-popup_corner_radius input').val(),
				closeBtnColor = $('.merchant-field-close_btn_color input').val(),
				messageTextColor = $('.merchant-field-popup_message_text_color input').val(),
				productTitleColor = $('.merchant-field-product_title_color input').val(),
				productDescriptionColor = $('.merchant-field-product_description_color input').val(),
				productPriceColor = $('.merchant-field-product_price_color input').val(),
				productPriceFontSize = $('.merchant-field-product_price_font_size input').val(),
				cartDetailsTextColor = $('.merchant-field-cart_details_color input').val(),
				buttonsMainColor = $('.merchant-field-cart_main_color input').val(),
				buttonsAlternateColor = $('.merchant-field-cart_alternate_color input').val(),
				buttonsCornerRadius = $('.merchant-field-buttons_corner_radius input').val(),
				suggestedProductsSectionTitleColor = $('.merchant-field-suggested_products_section_title_color input').val(),
				suggestedProductNameColor = $('.merchant-field-suggested_product_name_color input').val(),
				suggestedProductPriceColor = $('.merchant-field-suggested_product_price_color input').val(),
				borderColor = $('.merchant-field-border_color input').val();

			$('.popup').css({
				'background-color': popupBgColor,
				'border-radius': popupCornerRadius + 'px',
			});

			$('.popup-close .close-button svg path').attr('stroke', closeBtnColor);

			$('.popup .popup-header .popup-header-title').css({
				'color': messageTextColor
			});

			$('.popup .popup-body .added-product .popup-product-name a').css({
				'color': productTitleColor
			});

			$('.popup .popup-body .added-product .popup-product-description').css({
				'color': productDescriptionColor
			});

			$('.popup .popup-body .added-product .popup-product-price').css({
				'color': productPriceColor,
				'font-size': productPriceFontSize + 'px'
			});

			$('.popup-cart-info span').css({
				'color': cartDetailsTextColor
			});

			$('.popup-actions .merchant-button').css({
				'color': buttonsMainColor,
				'border-color': buttonsMainColor,
				'background-color': buttonsAlternateColor,
				'border-radius': buttonsCornerRadius + 'px'
			});

			$('.popup-actions .merchant-button .button-filled,.popup-actions .merchant-button:first-of-type').css({
				'background-color': buttonsMainColor,
				'color': buttonsAlternateColor
			});

			$('.popup .popup-body .popup-actions .merchant-button').hover(
				function () { // Mouse enter
					$(this).not(':first-of-type').css({
						'color': buttonsAlternateColor,
						//'border-color': buttonsAlternateColor,
						'background-color': buttonsMainColor
					});
					$(this).not(':first-of-type').find('.button-filled').css({
						'background-color': buttonsAlternateColor,
						'color': buttonsMainColor
					});
					if ($(this).is(':first-of-type')) {
						$(this).css({
							'color': buttonsMainColor,
							//'border-color': buttonsMainColor,
							'background-color': buttonsAlternateColor
						});
						$(this).find('.button-filled').css({
							'background-color': buttonsMainColor,
							'color': buttonsAlternateColor
						});
					}
				},
				function () { // Mouse leave
					$(this).not(':first-of-type').css({
						'color': buttonsMainColor,
						//'border-color': buttonsMainColor,
						'background-color': buttonsAlternateColor
					});
					$(this).not(':first-of-type').find('.button-filled').css({
						'background-color': buttonsMainColor,
						'color': buttonsAlternateColor
					});
					if ($(this).is(':first-of-type')) {
						$(this).css({
							'color': buttonsAlternateColor,
							//'border-color': buttonsAlternateColor,
							'background-color': buttonsMainColor
						});
						$(this).find('.button-filled').css({
							'background-color': buttonsAlternateColor,
							//'border-color': buttonsMainColor,
							'color': buttonsMainColor
						});
					}
				}
			);

			$('.popup .popup-body .recently-viewed-products .section-title').css({
				'color': suggestedProductsSectionTitleColor
			});

			$('.popup .popup-body .recently-viewed-products .viewed-products .product h3').css({
				'color': suggestedProductNameColor
			});

			$('.popup .popup-body .recently-viewed-products .viewed-products .product .product-price').css({
				'color': suggestedProductPriceColor
			});

			$('.popup .added-product .popup-cart-info, .popup .popup-body .added-product, .popup.layout-3 .top-area').css({
				'border-color': borderColor,
			});
		},

		/**
		 * Events
		 */
		events: function () {
			let self = this;
			// Update popup layout
			$(document).on('change', '.merchant-field-layout input', function (e) {
				self.activateLayout();
			});

			// Update popup header
			$(document).on('change keyup', '.merchant-field-popup_message input', function (e) {
				self.updatePopupHeader($(this).val());
			});

			// Update action buttons text
			$(document).on(
				'change keyup',
				'.merchant-field-view_cart_button_label input, .merchant-field-view_continue_shopping_button_label input',
				function (e) {
					self.updateActionButtonsText();
				}
			);

			// Update action buttons visibility
			$(document).on('change',
				'.merchant-field-show_view_cart_button input, .merchant-field-show_view_continue_shopping_button input, .merchant-field-show_checkout_button input',
				function (e) {
					self.updateActionButtonsVisibility();
				}
			);

			// Update cart details visibility
			$(document).on('change', '.merchant-field-show_cart_details input', function (e) {
				self.updateCartDetailsVisibility();
			});


			// Update product title, description and price visibility
			$(document).on('change', '.merchant-field-show_product_info input', function (e) {
				self.updateTitlePriceVisibility();
				self.updateProductThumbVisibility();
				self.updateProductDescriptionVisibility();
			});

			// Update suggested products visibility and content
			$(document).on('change', '.merchant-field-show_suggested_products input, .merchant-field-suggested_products_module select', function (e) {
				self.updateSuggestedProductsVisibility();
			});

			$(document).on('merchant-color-picker-updated change', function (e) {
				self.updatePopupColorsStyles();
			});
		}
	};

	merchant_added_to_cart_popup_object.init();

})(jQuery);