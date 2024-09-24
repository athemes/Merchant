;( function ( $ ) {
	'use strict';

	const $widthInput = $( 'input[name="merchant[image-max-width]' );
	const $heightInput = $( 'input[name="merchant[image-max-height]' );

	// On page load
	const imageDimensionPrev = {
		width: $widthInput.val(),
		height: $heightInput.val(),
	}

	$( document ).on( 'save.merchant', function ( e, module ) {
		if ( module === 'payment-logos' ) {
			const logosStr = $( 'input[name="merchant[logos]"]' ).val();
			const logosArr = logosStr.split( ',' );

			regenerate_images( logosArr )
		}
	} );

	function regenerate_images( attachments ) {
		const imageDimensionNext = {
			width: $widthInput.val(),
			height: $heightInput.val(),
		}

		// Check if image dimensions have changed
		const isDimensionChanged = imageDimensionPrev.width !== imageDimensionNext.width || imageDimensionPrev.height !== imageDimensionNext.height;

		// Update previous dimensions for the next comparison
		if ( isDimensionChanged ) {
			imageDimensionPrev.height = imageDimensionNext.height;
			imageDimensionPrev.width = imageDimensionNext.width;
		}

		$.ajax( {
			type: 'POST',
			url: merchant?.ajax_url,
			data: {
				action: 'merchant_regenerate_images',
				nonce: merchant?.nonce,
				is_dimension_changed: isDimensionChanged,
				attachments,
			},
			beforeSend: ( r ) => {
				if ( is_dimension_changed ) {
					//$('<span>Regenerating...</span>').insertAfter( $( '.merchant-gallery-button' ) );
				}
			},
			success: ( response ) => {
				if ( ! response || ! response.data ) {
					return;
				}
				// console.log( response.data );
				// Do something
			},
			error: ( error ) => {
				console.log( error )
			}
		} );
	}
} )( jQuery );
