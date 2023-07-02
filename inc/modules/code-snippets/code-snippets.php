<?php

function merchant_code_snippets_header_scripts() {

	// Header Scripts
	$header_scripts = Merchant_Option::get( 'code-snippets', 'header_scripts', '' );

	if ( ! empty( $header_scripts ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $header_scripts );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $header_scripts );
		}
	}

}

add_action( 'wp_head', 'merchant_code_snippets_header_scripts' );

function merchant_code_snippets_body_scripts() {

	// Body Scripts
	$body_scripts = Merchant_Option::get( 'code-snippets', 'body_scripts', '' );

	if ( ! empty( $body_scripts ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $body_scripts );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $body_scripts );
		}
	}

}

add_action( 'wp_body_open', 'merchant_code_snippets_body_scripts' );

function merchant_code_snippets_footer_scripts() {

	// Footer Scripts
	$footer_scripts = Merchant_Option::get( 'code-snippets', 'footer_scripts', '' );

	if ( ! empty( $footer_scripts ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', $footer_scripts );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', $footer_scripts );
		}
	}

}

add_action( 'wp_footer', 'merchant_code_snippets_footer_scripts', 99 );
