'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

	merchant.modules.animatedAddToCart = {

	  init: function() {

	  	var self     = this;
			var paused   = false;
			var delay    = parseInt(window.merchant.setting.trigger_delay) * 1000;
			var $buttons = $('.merchant-animated-add-to-cart .add_to_cart_button:not(.merchant_buy_now_button), .merchant-animated-add-to-cart .single_add_to_cart_button:not(.merchant_buy_now_button), .merchant-animated-add-to-cart .product_type_grouped:not(.merchant_buy_now_button)');

	  	if ( ! $buttons.length ) {
	  		return;
	  	}

			setInterval( function( $element ) {

				if ( ! paused ) {

					$buttons.each( function() {

						$buttons.addClass('merchant-active');

						setTimeout(function() {
							$buttons.removeClass('merchant-active');
						}, 1000);

					});

				}

			}, delay);

			$buttons.each( function() {

				var $button = $(this);

				$button.mouseover(function() {
					paused = true;
					$buttons.removeClass('merchant-active');
				}).mouseout(function() {
					paused = false;
				});

			});

	  },

	};

	$(document).ready(function() {
		merchant.modules.animatedAddToCart.init();
	});

}(jQuery));
