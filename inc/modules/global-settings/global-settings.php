<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
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
add_action( 'wp_enqueue_scripts', 'merchant_head_custom_js_first', 11 );

/**
 * Head Custom JS
 * 
 */
function merchant_head_custom_js() {

	// Custom JS
	$custom_js = Merchant_Option::get( 'global-settings', 'custom_js', '' );

	if ( ! empty( $custom_js ) ) {
		wp_add_inline_script( 'merchant', wp_kses( $custom_js, array() ), 'after' );
	}
}
add_action( 'wp_enqueue_scripts', 'merchant_head_custom_js', 12 );

/**
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
add_action( 'wp_enqueue_scripts', 'merchant_head_custom_js_later', 13 );