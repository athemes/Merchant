;( function( $ ) {
	$( document ).ready( function() {
		const $spendingWidget = $( '.js-merchant-spending-goal-widget' );

		if ( typeof merchant === 'undefined' || ! $spendingWidget.length ) {
			return;
		}

		const { enable_auto_slide_in } = merchant.setting || {};

		$spendingWidget.on( 'click', function () {
			toggleWidget();
		} );

		if ( enable_auto_slide_in ) {
			if ( $( 'body.single-product' ).length && $( '.woocommerce-notices-wrapper' ).is( ':visible' ) && ! $( '.woocommerce-notices-wrapper' ).is( ':empty' ) ) {
				toggleWidget( false );
			}

			$( document.body ).on( 'added_to_cart', function( event, fragments, cart_hash, $button, $context ) {
				toggleWidget( false );
			} );
		}

		function toggleWidget( toggle = true ) {
			toggle ? $spendingWidget.toggleClass( 'active' ) : $spendingWidget.addClass( 'active' );
		}
	} );
} )( jQuery );
