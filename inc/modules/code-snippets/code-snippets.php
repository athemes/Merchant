<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_code_snippets_header_scripts() {

	// Header Scripts
	$header_scripts = Merchant_Option::get( 'code-snippets', 'header_scripts', '' );

	if ( ! empty( $header_scripts ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', wp_kses( $header_scripts, array() ) );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', wp_kses( $header_scripts, array() ) );
		}
	}

}

add_action( 'wp_head', 'merchant_code_snippets_header_scripts' );

function merchant_code_snippets_body_scripts() {

	// Body Scripts
	$body_scripts = Merchant_Option::get( 'code-snippets', 'body_scripts', '' );

	if ( ! empty( $body_scripts ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', wp_kses( $body_scripts, array() ) );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', wp_kses( $body_scripts, array() ) );
		}
	}

}

add_action( 'wp_body_open', 'merchant_code_snippets_body_scripts' );

function merchant_code_snippets_footer_scripts() {

	// Footer Scripts
	$footer_scripts = Merchant_Option::get( 'code-snippets', 'footer_scripts', '' );

	if ( ! empty( $footer_scripts ) ) {
		if ( current_theme_supports( 'html5', 'script' ) ) {
			echo sprintf( '<script>%s</script>', wp_kses( $footer_scripts, array() ) );
		} else {
			echo sprintf( '<script type="text/javascript">%s</script>', wp_kses( $footer_scripts, array() ) );
		}
	}

}

add_action( 'wp_footer', 'merchant_code_snippets_footer_scripts', 99 );
