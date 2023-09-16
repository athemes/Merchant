/**
 * Merchant Floating Mini Cart.
 * 
 */

'use strict';

var merchant = merchant || {};

merchant.modules = merchant.modules || {};

(function($) {

	merchant.modules.floatingMiniCart = {

		count: typeof window.merchant.setting !== 'undefined' ? window.merchant.setting.floating_mini_cart_count : 2,

		init: function( $wrapper ) {

			var self         = this;
			var $body        = $('body');
			var $miniCart    = $('.merchant-floating-mini-cart-icon');
			var $sideCart    = $('.merchant-floating-side-mini-cart');
			var $sideOverlay = $('.merchant-floating-side-mini-cart-overlay');
			var $sideToggle  = $('.merchant-floating-side-mini-cart-toggle');

			if ( ! $miniCart.length ) {
				return false;
			}

			// Better UX inside customizer
			// Always show on customizer, even if display 'when cart is not empty' is set.
			if ( typeof window.parent.wp !== 'undefined' && typeof window.parent.wp.customize !== 'undefined' ) {

				$miniCart.addClass( 'merchant-show' );

			} else {

				// Do the check to display the floating mini cart icon after user interaction.
				let flag = false;

				$(window).on( 'scroll mousemove touchstart', function() {

					if ( ! flag ) {

						self.updateFloatingMiniCartIconCount();
						self.checkMiniCartCountAndDisplay();

						flag = true;

					}

				});

			}

			$( document.body ).on('wc_fragment_refresh added_to_cart removed_from_cart', function( event, data ) {

				if ( data && data['.merchant_cart_count'] !== undefined ) {
					self.count = data['.merchant_cart_count'];
				}

				self.updateFloatingMiniCartIconCount();
				self.checkMiniCartCountAndDisplay();
				self.updateFloatingMiniCartSideContentHeight();

			});

			$(window).on('resize merchant.floating-mini-cart-resize', function() {
				self.updateFloatingMiniCartSideContentHeight();
			});

			setTimeout(function(){
				self.updateFloatingMiniCartSideContentHeight();
			}, 200);

			$sideToggle.on( 'click', function(e){

				e.preventDefault();

				$body.toggleClass( 'merchant-floating-side-mini-cart-show' );

				$(window).trigger('merchant.floating-mini-cart-resize');

			});

		},

		checkMiniCartCountAndDisplay: function() {

			var self  = this;
			var $miniCart = $( '.merchant-floating-mini-cart-icon' );

			if ( $miniCart.data( 'display' ) === 'always' ) {

				$miniCart.addClass( 'merchant-show' );

			} else {

				if ( self.count == '0' ) {
					$miniCart.removeClass( 'merchant-show' );
				} else {
					$miniCart.addClass( 'merchant-show' );
				}

			}

		},

		updateFloatingMiniCartIconCount: function() {

			var self     = this;
			var $counter = $( '.merchant-floating-mini-cart-icon-counter' );

			$counter.text( );

			jQuery.ajax({
				type: 'POST',
				url: wc_add_to_cart_params.ajax_url,
				data: {
					'action': 'merchant_get_cart_count'
				},
				success: function(response) {
					$counter.text(response);
				}
			})

		},

		updateFloatingMiniCartSideContentHeight: function() {

			var $sideCart    = $('.merchant-floating-side-mini-cart');
			var $sideTitle   = $sideCart.find('.merchant-floating-side-mini-cart-widget-title');
			var $sideTotal   = $sideCart.find('.woocommerce-mini-cart__total');
			var $sideButtons = $sideCart.find('.woocommerce-mini-cart__buttons');

			var windowHeight = $(window).innerHeight() - ( $sideTitle.outerHeight() + $sideTotal.outerHeight() + $sideButtons.outerHeight() );

			$sideCart.find('.woocommerce-mini-cart').css({'height': windowHeight});

		},

	};

	$(document).ready(function() {
		merchant.modules.floatingMiniCart.init();
	});

}(jQuery));
