<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Todo: remove
 *
 * Head custom JS
 * 
 */
function merchant_head_custom_js_first() {
	// Custom JS First - runs at the beginning of Merchant
	$custom_js_first = Merchant_Option::get( 'global-settings', 'custom_js_first', '' );

	if ( ! empty( $custom_js_first ) ) {
		wp_add_inline_script( 'merchant', wp_kses( $custom_js_first, array() ), 'before' );
	}
}
// add_action( 'wp_enqueue_scripts', 'merchant_head_custom_js_first', 11 );

/**
 * Head Custom JS
 * 
 */
function merchant_head_custom_js() {
	$custom_js = Merchant_Option::get( 'global-settings', 'custom_js', '' );

	$custom_js = html_entity_decode( $custom_js, ENT_QUOTES, 'UTF-8' );

	if ( ! empty( $custom_js ) ) {
		// Simple minification
		$minified_js = preg_replace(
			array( '/\/\/.*/', '/\/\*.*?\*\//s', '/\s+/' ),
			array( '', '', ' ' ),
			$custom_js
		);

		wp_add_inline_script( 'merchant', $minified_js );
	}
}

/**
 * `merchant_head_custom_js_priority`
 *
 * @since 2.0.0
 */
add_action( 'wp_enqueue_scripts', 'merchant_head_custom_js', apply_filters( 'merchant_head_custom_js_priority', 12 ) );

/**
 * Todo: remove
 *
 * Head Custom JS Later
 * 
 */
function merchant_head_custom_js_later() {

	// Custom JS Last - runs at the end of Merchant
	$custom_js_last = Merchant_Option::get( 'global-settings', 'custom_js_last', '' );

	if ( ! empty( $custom_js_last ) ) {
		wp_add_inline_script( 'merchant', wp_kses( $custom_js_last, array() ), 'after' );
	}
}
// add_action( 'wp_enqueue_scripts', 'merchant_head_custom_js_later', 13 );
