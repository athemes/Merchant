;( function( $, window, document, undefined ) {
	'use strict';

	$( document ).ready( function() {
		$( document ).on( 'wc-product-gallery-after-init', function ( e, $el, params  ) {
			const $slides = $( $el ).find( '.woocommerce-product-gallery__image' );
			if ( $slides.length > 1 ) {
				const $productLabel = $( $el ).find( '.merchant-product-labels' ).clone();
				$( $el ).find( '.merchant-product-labels' ).remove();
				$slides.append( $productLabel );
			}
		} );
	} );

} )( jQuery, window, document );