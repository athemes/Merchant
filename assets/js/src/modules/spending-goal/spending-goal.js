;( function( $ ) {
	$( document ).ready( function() {
		const spendingWidgetSelector = '.js-merchant-spending-goal-widget';

		if ( typeof merchant === 'undefined' || ! $( spendingWidgetSelector ).length ) {
			return;
		}

		const { enable_auto_slide_in, spending_goal_nonce, ajax_url } = merchant.setting || {};

		// Show/Hide widget when clicking on it.
		$( document ).on( 'click', spendingWidgetSelector, function () {
			showWidget( true );
		} );

		// Auto open after a product is added to the Cart on Product Single Page.
		if ( enable_auto_slide_in ) {
			if ( $( 'body.single-product' ).length && $( '.woocommerce-notices-wrapper' ).is( ':visible' ) && ! $( '.woocommerce-notices-wrapper' ).is( ':empty' ) ) {
				showWidget();
			}
		}

		// Update the widget and Auto slide when a product is Added/Removed to cart via AJAX.
		// Works on pages like Shop & Cart, updated_cart_totals updated_wc_div events are required for the Cart page.
		$( document.body ).on( 'added_to_cart removed_from_cart updated_cart_totals updated_wc_div', function( event, data ) {
			$.ajax( {
				type: 'POST',
				url: ajax_url,
				data: {
					action: 'update_spending_goal_widget',
					nonce: spending_goal_nonce,
				},
				success: ( response ) => {
					if ( ! response || ! response.data ) {
						return;
					}

					// Check if widget is currently open
					const isVisible = $( spendingWidgetSelector ).hasClass( 'active' );

					// Replace the widget markup
					$( spendingWidgetSelector ).replaceWith( response.data.markup );

					// Open widget immediately if open, else with slight delay for slide-in effect
					if ( enable_auto_slide_in ) {
						isVisible ? showWidget() : setTimeout( showWidget, 66 );
					}
				},
				error: ( error ) => {
					console.log( error );
				},
			} );
		} );

		// Helper
		function showWidget( toggle = false ) {
			toggle ? $( spendingWidgetSelector ).toggleClass( 'active' ) : $( spendingWidgetSelector ).addClass( 'active' );
		}
	} );
} )( jQuery );
