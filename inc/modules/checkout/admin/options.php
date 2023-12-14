<?php

/**
 * Checkout Options.
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
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'checkout',
	'fields' => array(

		$before_fields,

		array(
			'id'      => 'layout',
			'type'    => 'radio',
			'title'   => esc_html__( 'Layout', 'merchant' ),
			'options' => array(
				'layout-shopify' => esc_html__( 'Shopify multi step', 'merchant' ),
				'layout-one-step' => esc_html__( 'One step', 'merchant' ),
				'layout-multi-step' => esc_html__( 'Multi step', 'merchant' ),
			),
			'default' => 'layout-shopify',
		),

		array(
			'id'        => 'sticky_totals_box',
			'type'      => 'switcher',
			'title'     => esc_html__( 'Sticky Totals Box', 'merchant' ),
			'condition' => array( 'layout', 'any', 'layout-shopify|layout-one-step' ),
		),

	),
) );
