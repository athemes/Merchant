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
			this.updateTitlePriceVisibility();
			this.updateCartDetailsVisibility();
			this.updateActionButtonsVisibility();
			this.updateProductDescriptionVisibility();
		},

		/**
		 * Activate the layout
		 */
		activateLayout: function () {
			let layout = $('.merchant-image-picker').find('input:checked').val(),
				allPopups = $('.popup'),
				activePopup = $('.popup.' + layout),
				arrowIcon = '<svg width="5" height="8" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.19824 1.14453L4.03516 3.98242L1.19824 6.81934" stroke="#E5E5E5"/></svg>';
			allPopups.removeClass('show');
			activePopup.addClass('show');
			activePopup.find('.recently-viewed-products .viewed-products').slick({
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
		 * Update suggested products visibility
		 */
		updateSuggestedProductsVisibility: function () {
			if ($('.merchant-field-show_suggested_products input:checked').length) {
				$('.suggested-products').show();
			} else {
				$('.suggested-products').hide();
			}
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
				self.updateProductDescriptionVisibility();
			});

			// Update suggested products visibility
			$(document).on('change', '.merchant-field-show_suggested_products input', function (e) {
				self.updateSuggestedProductsVisibility();
			});
		}
	};

	merchant_added_to_cart_popup_object.init();

})(jQuery);