;( function( $ ) {
	$( document ).ready( function() {

		// Update buy now button state based on selected variation
		$( 'form.variations_form' ).on( 'woocommerce_variation_has_changed', function() {
			const $form = $( this );
			const selectedVariationId = $form.find( '.variation_id' ).val();
			$form.find( '.merchant-buy-now-button' ).toggleClass( 'disabled', ! selectedVariationId );
		} );

	} );
} )( jQuery );