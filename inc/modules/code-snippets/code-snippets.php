<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_code_snippets_header_code_snippets() {

	// Header Scripts
	$header_code_snippets = Merchant_Option::get( 'code-snippets', 'header_code_snippets', '' );

	if ( ! empty( $header_code_snippets ) ) {
		echo wp_unslash( $header_code_snippets ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
add_action( 'wp_head', 'merchant_code_snippets_header_code_snippets' );

function merchant_code_snippets_body_code_snippets() {

	// Body Scripts
	$body_code_snippets = Merchant_Option::get( 'code-snippets', 'body_code_snippets', '' );

	if ( ! empty( $body_code_snippets ) ) {
		echo wp_unslash( $body_code_snippets ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
add_action( 'wp_body_open', 'merchant_code_snippets_body_code_snippets' );

function merchant_code_snippets_footer_code_snippets() {

	// Footer Scripts
	$footer_code_snippets = Merchant_Option::get( 'code-snippets', 'footer_code_snippets', '' );

	if ( ! empty( $footer_code_snippets ) ) {
		echo wp_unslash( $footer_code_snippets ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
add_action( 'wp_footer', 'merchant_code_snippets_footer_code_snippets', 99 );
