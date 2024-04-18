;( function( $ ) {
	$( document ).ready( function() {

		const $variationForm = $( 'form.variations_form' );

		// Update buy now button state based on selected variation
		$variationForm.each( function () {
			const $form = $( this );
			const $buyBtn = $form.find( '.merchant-buy-now-button' );

			$form.find( 'input[name="variation_id"]' ).on( 'change woocommerce_variation_has_changed', function () {
				const selectedVariationId = +$form.find( '.variation_id' ).val();
				$buyBtn.toggleClass( 'disabled', ! selectedVariationId );
			} )
		} );
	} );
} )( jQuery );