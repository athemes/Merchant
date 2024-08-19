;( function ( $, window, document, undefined ) {

	const autoClearEnabledOnLoad = $( '.merchant-field-enable_auto_clear input' ).is( ':checked' );

	// Clear Cookie
	$( document ).on( 'save.merchant', function ( e, module ) {
		if ( module === 'clear-cart' ) {
			const autoClearEnabledOnSave = $( '.merchant-field-enable_auto_clear input' ).is( ':checked' );

			if ( autoClearEnabledOnLoad && ! autoClearEnabledOnSave ) {
				document.cookie = 'merchant_clear_cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
			}
		}
	} );

	// Page load
	$( document ).on( 'ready', function () {
		initPreview();
	} );

	// Change settings
	$( document ).on( 'change', function () {
		initPreview();
	} )

	// Change button style
	$( document ).on( 'change', '.merchant-field-style input', function () {
		const val = $( this ).val();

		let color = "#ffffff";
		if ( val === 'outline' || val === 'text' ) {
			color = "#212121";
		}

		$( '.merchant-field-text_color input, .merchant-field-text_color_hover input' ).val( color )
			.attr( 'value', color )
			.trigger( 'change' )
			.trigger( 'input' );
	} );

	function initPreview() {
		const $buttonPreview = $( '.merchant-clear-cart-button' );

		const isCartPageEnabled = $( '.merchant-field-enable_cart_page input' ).is( ':checked' );
		const cartPagePosition = $( '.merchant-field-cart_page_position select' ).val();

		// If Cart Page is Disabled
		$buttonPreview.toggleClass( 'hide', ! isCartPageEnabled );

		if ( isCartPageEnabled ) {
			$buttonPreview.each( function () {
				if ( $( this ).hasClass( cartPagePosition ) ) {
					$( this ).removeClass( 'hide' );
				} else {
					$( this ).addClass( 'hide' );
				}
			} );
		}

		// Style - Solid/Outline/Text
		const $buttonStyleField = $( '.merchant-field-style input' );
		$buttonStyleField.each( function() {
			const value = $( this ).val();
			$buttonPreview.removeClass( `merchant-clear-cart-button--${value}` );

			if ( $( this ).is( ':checked' ) ) {
				$buttonPreview.addClass( `merchant-clear-cart-button--${value}` );
			}
		} );
	}

} )( jQuery, window, document );