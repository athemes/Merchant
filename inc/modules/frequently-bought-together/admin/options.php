<?php

/**
 * Frequently Bought Together Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'single_product_placement',
			'type'    => 'select',
			'title'   => esc_html__( 'Placement on product page', 'merchant' ),
			'options' => array(
				'after-summary' => esc_html__( 'After product summary', 'merchant' ),
				'after-tabs'    => esc_html__( 'After product tabs', 'merchant' ),
				'bottom'        => esc_html__( 'At the bottom', 'merchant' ),
			),
			'default' => 'after-summary',
		),
	),
) );

// Text Formatting Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Text Formatting Settings', 'merchant' ),
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'title',
			'type'    => 'text',
			'title'   => esc_html__( 'Title', 'merchant' ),
			'default' => esc_html__( 'Frequently Bought Together', 'merchant' ),
		),

		array(
			'id'      => 'price_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Price label', 'merchant' ),
			'default' => esc_html__( 'Bundle price', 'merchant' ),
		),

		array(
			'id'      => 'save_label',
			'type'    => 'text',
			'title'   => esc_html__( 'You save label', 'merchant' ),
			'default' => esc_html__( 'You save: {amount}', 'merchant' ),
		),

		array(
			'id'      => 'button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button text', 'merchant' ),
			'default' => esc_html__( 'Add to cart', 'merchant' ),
		),
	),
) );

// Style Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'title'  => esc_html__( 'Style Settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'plus_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Plus sign background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'plus_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Plus sign text color', 'merchant' ),
			'default' => '#fff',
		),

		array(
			'id'      => 'bundle_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Bundle border color', 'merchant' ),
			'default' => '#f9f9f9',
		),

		array(
			'id'      => 'bundle_border_radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Bundle border radius', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 5,
		),
	),
) );

// Shortcode
$merchant_module_id = Merchant_Frequently_Bought_Together::MODULE_ID;
Merchant_Admin_Options::create( array(
	'module' => $merchant_module_id,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __( 'Use shortcode', 'merchant' ),
			'default' => 0,
			'desc'      => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
				'merchant' ),
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