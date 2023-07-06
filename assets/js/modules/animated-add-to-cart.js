'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

	merchant.modules.animatedAddToCart = {

		init: function() {

		  var self       = this;
			var $buttons = $('.merchant-animated-add-to-cart .add_to_cart_button:not(.merchant_buy_now_button), .merchant-animated-add-to-cart .single_add_to_cart_button:not(.merchant_buy_now_button), .merchant-animated-add-to-cart .product_type_grouped:not(.merchant_buy_now_button)');

			if ( ! $buttons.length ) {
				return;
			}

		  $(window).on('scroll', function() {
				self.onScroll( $buttons );
			});

		  self.onScroll( $buttons );

		},

		onScroll: function( $buttons ) {

			$buttons.each( function() {

				var $button = $(this);
			var btnRect     = $button.get(0).getBoundingClientRect();

				if ( $button.hasClass('merchant-animated') ) {
					return;
				}

				if ( btnRect.top >= 0 && btnRect.left >= 0 && btnRect.bottom <= ((window.innerHeight || document.documentElement.clientHeight) + ($button.outerHeight()/2)) && btnRect.right <= ((window.innerWidth || document.documentElement.clientWidth) + ($button.outerWidth()/2)) ) {

					$button.addClass('merchant-active');
					$button.addClass('merchant-animated');

					setTimeout(function() {
						$button.removeClass('merchant-active');
					}, 1000);

				}

			});

		},

	};

	$(document).ready(function() {
		merchant.modules.animatedAddToCart.init();
	});

}(jQuery));
