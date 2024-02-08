/**
 * Merchant Inactive Tab Message.
 * 
 */

'use strict';

const merchant = merchant || {};

merchant.modules = merchant.modules || {};

( function( $ ) {

	merchant.modules.inactiveTabMessage = {

		init: function() {
			const { setting } = merchant;

			const {
				inactive_tab_message: noItemsMessage,
				inactive_tab_abandoned_message: itemsInCartMessage,
				inactive_tab_enable_blink: shouldBlink,
			} = setting || {}

			let { inactive_tab_cart_count: cartCount } = setting || {};

			$( document.body ).on( 'added_to_cart removed_from_cart updated_wc_div', function( event, data ) {
				if ( data && data['.merchant_cart_count'] !== undefined ) {
					cartCount = data['.merchant_cart_count'];
				} else {
					// Cart page
					cartCount = $( '.woocommerce-cart-form tr.cart_item' ).length
				}
			} );

			const defaultTitle = document.title;
			let blinkTimeout;

			document.addEventListener( 'visibilitychange', () => {
				const modifiedTitle = cartCount ? itemsInCartMessage : noItemsMessage;
				if ( ! modifiedTitle ) {
					return;
				}

				const isTabActive = ! document.hidden;

				// Change the title.
				document.title = isTabActive ? defaultTitle : modifiedTitle.replaceAll( '&#039;', "'" );

				// Blink the title when tab is inactive.
				if ( shouldBlink && ! isTabActive ) {
					blinkTimeout = setInterval( () => {
						document.title = document.title === modifiedTitle ? defaultTitle : modifiedTitle;
					}, 500 );
				} else {
					clearInterval( blinkTimeout );
				}
			} );
		},
	};

	merchant.modules.inactiveTabMessage.init();
}( jQuery ) );
