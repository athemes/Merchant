<?php

/**
 * Add To Cart Text Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$before_fields = array();
if ( defined( 'MERCHANT_PRO_VERSION' ) && merchant_is_checkout_block_layout() ) {
	$before_fields = array(
		'type'    => 'warning',
		'content' => sprintf(
		/* Translators: 1. docs link */
			__( 'Your checkout page is being rendered through the new WooCommerce checkout block. You must edit the checkout page to use the classic checkout shortcode instead. Check <a href="%1$s" target="_blank">this documentation</a> to learn more.', 'merchant' ),
			'https://docs.athemes.com/article/how-to-switch-cart-checkout-blocks-to-the-classic-shortcodes/'
		),
	);
}
// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Address_Autocomplete::MODULE_ID,
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'fields' => array(
		$before_fields,
		array(
			'id'    => 'api_key',
			'type'  => 'text',
			'title' => esc_html__( 'API Key', 'merchant' ),
			'desc'  => esc_html__( 'Add Google places API Key', 'merchant' ),
		),
		array(
			'id'    => 'url_params',
			'type'  => 'text',
			'title' => esc_html__( 'Optional API URL Parameters', 'merchant' ),
			'desc'  => esc_html__( 'Add extra parameters to the API URL. For example: &region=us&language=en.', 'merchant' ),
		),
	),
) );