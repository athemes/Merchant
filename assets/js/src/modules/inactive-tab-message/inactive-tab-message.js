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

			const { inactive_tab_message: noItemsMessage, inactive_tab_abandoned_message: hasItemsMessage } = setting || {}

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

			document.addEventListener( 'visibilitychange', () => {
				const modifiedTitle = cartCount ? hasItemsMessage : noItemsMessage;
				if ( ! modifiedTitle ) {
					return;
				}

				// Change the title.
				document.title = document.hidden ? modifiedTitle.replaceAll( '&#039;', "'" ) : defaultTitle;
			} );
		},
	};

	merchant.modules.inactiveTabMessage.init();
}( jQuery ) );
