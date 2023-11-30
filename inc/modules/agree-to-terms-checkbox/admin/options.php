<?php
/**
 * Agree to Terms Checkout
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$before_fields = array();
if ( merchant_is_checkout_block_layout() ) {
	$before_fields = array(
		'type'    => 'warning',
		'content' => sprintf( 
			/* Translators: 1. docs link */
			__( 'Your checkout page is being rendered through the new WooCommerce checkout block. You must edit the checkout page to use the classic checkout shortcode instead. Check <a href="%1$s" target="_blank">this documentation</a> to learn more.', 'merchant' ),
			'https://docs.athemes.com/article/how-to-switch-cart-checkout-blocks-to-the-classic-shortcodes/'
		),
	);
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'agree-to-terms-checkbox',
	'fields' => array(

		$before_fields,

		array(
			'id'      => 'label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label', 'merchant' ),
			'default' => esc_html__( 'I agree with the', 'merchant' ),
		),

		array(
			'id'      => 'text',
			'type'    => 'text',
			'title'   => esc_html__( 'Terms and conditions text', 'merchant' ),
			'default' => esc_html__( 'Terms & Conditions', 'merchant' ),
		),

		array(
			'id'      => 'link',
			'type'    => 'text',
			'title'   => esc_html__( 'Terms and conditions link', 'merchant' ),
			'default' => get_privacy_policy_url(),
		),

	),
) );
