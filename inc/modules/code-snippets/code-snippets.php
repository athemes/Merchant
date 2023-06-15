<?php

function merchant_code_snippets_header_custom_js() {

	// Custom JS
	$custom_js = Merchant_Option::get( 'code-snippets', 'custom_js', '' );

	if ( ! empty( $custom_js ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $custom_js );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $custom_js );
		}
	}

	// Custom JS First - runs at the beginning of Merchant
	$custom_js_first = Merchant_Option::get( 'code-snippets', 'custom_js_first', '' );

	if ( ! empty( $custom_js_first ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $custom_js_first );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $custom_js_first );
		}
	}

}

add_action( 'wp_head', 'merchant_code_snippets_header_custom_js' );

function merchant_code_snippets_footer_custom_js() {

	// Custom JS Last - runs at the end of Merchant
	$custom_js_last = Merchant_Option::get( 'code-snippets', 'custom_js_last', '' );

	if ( ! empty( $custom_js_last ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $custom_js_last );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $custom_js_last );
		}
	}

}

add_action( 'wp_footer', 'merchant_code_snippets_footer_custom_js', 99 );
