<?php
/**
 * Free Shipping Progress Bar
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Free_Shipping_Progress_Bar::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'display_on',
			'type'    => 'checkbox_multiple',
			'title'   => esc_html__( 'Show on', 'merchant' ),
			'options' => array(
				'cart'      => esc_html__( 'Cart', 'merchant' ),
				'mini_cart' => esc_html__( 'Mini Cart', 'merchant' ),
				'checkout'  => esc_html__( 'Checkout', 'merchant' ),
			),
			'default' => array( 'cart', 'mini_cart', 'checkout' ),
			'desc'    => esc_html__( "To get this feature working, the free 'shipping method' plus a 'minimum order amount' must be enabled in the WooCommerce shipping settings.",
				'merchant' ),
		),

		array(
			'id'      => 'include_tax',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Include tax in calculation', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Progress bar background color', 'merchant' ),
			'default' => '#757575',
		),

		array(
			'id'      => 'foreground_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Progress bar foreground color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'bar_height',
			'type'    => 'range',
			'min'     => '4',
			'max'     => '30',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '10',
			'title'   => esc_html__( 'Progress bar height', 'merchant' ),
		),

		array(
			'id'      => 'select_border_radius',
			'type'    => 'dimensions',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'default' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
		),
	),
) );
