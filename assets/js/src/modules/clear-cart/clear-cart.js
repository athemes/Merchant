const merchant = merchant || {};

;(function ( $, window, document, undefined ) {
	'use strict';

	$(document).on( 'click', '.merchant-clear-cart-button', function( e ) {
		e.preventDefault();

		const $clearBtn = $( this );

		// Disable the button to prevent multiple clicks
		$clearBtn.prop( 'disabled', true );

		$.ajax({
			url: merchant?.setting?.ajax_url,
			type: 'POST',
			data: {
				action: 'clear_cart',
				nonce: merchant?.setting?.nonce
			},
			success: function(response) {
				if ( response.success ) {
					const redirectUrl = response.data.url;

					if ( redirectUrl ) {
						window.location.href = redirectUrl;
					} else {
						// If no redirect URL, refresh the Cart table & Mini/Side Cart
						$( document.body )
							.trigger( 'wc_update_cart' )
							.trigger( 'wc_fragment_refresh' );
					}
				}
			},
			error: function ( error ) {
				console.log( error )
			},
			complete: function() {
				$clearBtn.prop( 'disabled', false );
			}
		} );
	} );





} )( jQuery, window, document );
