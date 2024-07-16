;( function( $ ) {
	$( document ).ready( function() {

		const updateBuyButtonState = function( $variationForm ) {
			$variationForm.each( function() {
				const $form = $( this );
				const $buyBtn = $form.find( '.merchant-buy-now-button' );

				$form.find( 'input[name="variation_id"]').on( 'change woocommerce_variation_has_changed', function() {
					const selectedVariationId = +$form.find( '.variation_id' ).val();
					$buyBtn.toggleClass('disabled', ! selectedVariationId);
					$buyBtn.val( selectedVariationId );
				} );
			} );
		};

		// On page load
		updateBuyButtonState( $( 'form.variations_form' ) );

		// On Quick view
		window.addEventListener( 'merchant.quickview.ajax.loaded', function( e ) {
			updateBuyButtonState( $( 'form.variations_form' ) );
		} );
	} );
} )( jQuery );