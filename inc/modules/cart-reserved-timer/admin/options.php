<?php

/**
 * Cart Reserved Timer Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$before_fields = array();
if ( defined( 'MERCHANT_PRO_VERSION' ) && merchant_is_cart_block_layout() ) {
	$before_fields = array(
		'type'    => 'warning',
		'content' => sprintf( 
			/* Translators: 1. docs link */
			__( 'Your cart page is being rendered through the new WooCommerce cart block. You must edit the cart page to use the classic cart shortcode instead. Check <a href="%1$s" target="_blank">this documentation</a> to learn more.', 'merchant' ),
			'https://docs.athemes.com/article/how-to-switch-cart-checkout-blocks-to-the-classic-shortcodes/'
		),
	);
}

// Variables
$icon_path = MERCHANT_URI . 'assets/images/icons/' . Merchant_Cart_Reserved_timer::MODULE_ID;

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Cart_Reserved_timer::MODULE_ID,
	'fields' => array(

		$before_fields,

		array(
			'id'      => 'duration',
			'type'    => 'number',
			'title'   => esc_html__( 'Count down duration minutes', 'merchant' ),
			'default' => 10,
		),

		array(
			'id'      => 'reserved_message',
			'type'    => 'text',
			'title'   => esc_html__( 'Cart reserved message', 'merchant' ),
			'default' => esc_html__( 'An item in your cart is in high demand.', 'merchant' ),
		),

		array(
			'id'      => 'timer_message_minutes',
			'type'    => 'text',
			'title'   => esc_html__( 'Timer message for > 1 min ', 'merchant' ),
			'default' => esc_html__( 'Your cart is saved for {timer} minutes!', 'merchant' ),
		),

		array(
			'id'      => 'timer_message_seconds',
			'type'    => 'text',
			'title'   => esc_html__( 'Timer message for  < 1 min', 'merchant' ),
			'default' => esc_html__( 'Your cart is saved for {timer} seconds!', 'merchant' ),
		),

		array(
			'id'      => 'time_expires',
			'type'    => 'radio',
			'title'   => esc_html__( 'What to do after the timer expires?', 'merchant' ),
			'options' => array(
				'hide-timer' => esc_html__( 'Hide timer', 'merchant' ),
				'clear-cart' => esc_html__( 'Clear cart', 'merchant' ),
			),
			'default' => 'clear-cart',
		),

		array(
			'id'      => 'icon',
			'type'    => 'choices',
			'title'   => esc_html__( 'Choose an icon', 'merchant' ),
			'class'   => 'merchant-module-page-setting-field-choices-icon',
			'options' => array(
				'none'       => $icon_path . '/cancel.svg',
				'fire'       => $icon_path . '/fire.svg',
				'clock'      => $icon_path . '/clock.svg',
				'hour-glass' => $icon_path . '/hour-glass.svg',
			),
			'default' => 'fire',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Choose background Color', 'merchant' ),
			'default' => '#f4f6f8',
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Cart_Reserved_timer::MODULE_ID;
Merchant_Admin_Options::create( array(
	'module' => $merchant_module_id,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __( 'Use shortcode', 'merchant' ),
			'default' => 0,
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.', 'merchant' ),
		),
		array(
			'id'        => 'shortcode_text',
			'type'      => 'text_readonly',
			'title'     => esc_html__( 'Shortcode text', 'merchant' ),
			'default'   => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . ']',
			'condition' => array( 'use_shortcode', '==', '1' ),
		),
	),
) );