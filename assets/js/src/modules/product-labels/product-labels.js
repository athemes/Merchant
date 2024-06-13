;( function( $, window, document, undefined ) {
	'use strict';

	$( document ).ready( function() {
		$( document ).on( 'wc-product-gallery-after-init', function ( e, $el, params  ) {
			const $productLabel = $( $el ).find( '.woocommerce-product-gallery__wrapper .merchant-product-labels' );
			const $flexSliderWrapper = $productLabel?.closest( '.flex-viewport' );
			if ( $productLabel.length && $flexSliderWrapper.length ) {
				$flexSliderWrapper.append( $productLabel );
			}
		} );
	} );

} )( jQuery, window, document );