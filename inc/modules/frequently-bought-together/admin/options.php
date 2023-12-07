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
	'title'  => __( 'Settings', 'merchant' ),
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'single_product_placement',
			'type'    => 'select',
			'title'   => __( 'Placement on product page', 'merchant' ),
			'options' => array(
				'after-summary' => __( 'After product summary', 'merchant' ),
				'after-tabs'    => __( 'After product tabs', 'merchant' ),
				'bottom'        => __( 'At the bottom', 'merchant' ),
			),
			'default' => 'after-summary'
		),
	),
) );

// Text Formatting Settings
Merchant_Admin_Options::create( array(
	'title'  => __( 'Text Formatting Settings', 'merchant' ),
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'title',
			'type'    => 'text',
			'title'   => __( 'Title', 'merchant' ),
			'default' => __( 'Frequently Bought Together', 'merchant' ),
		),

		array(
			'id'      => 'price_label',
			'type'    => 'text',
			'title'   => __( 'Price label', 'merchant' ),
			'default' => __( 'Bundle price', 'merchant' ),
		),

		array(
			'id'      => 'save_label',
			'type'    => 'text',
			'title'   => __( 'You save label', 'merchant' ),
			'default' => __( 'You save: {amount}', 'merchant' ),
		),

		array(
			'id'      => 'no_variation_selected_text',
			'type'    => 'text',
			'title'   => __( 'No variation selected text', 'merchant' ),
			'default' => __( 'Please select an option to see your savings.', 'merchant' ),
		),

		array(
			'id'      => 'button_text',
			'type'    => 'text',
			'title'   => __( 'Button text', 'merchant' ),
			'default' => __( 'Add to cart', 'merchant' ),
		),
	)
) );

// Style Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'title'  => __( 'Style Settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'plus_bg_color',
			'type'    => 'color',
			'title'   => __( 'Plus sign background color', 'merchant' ),
			'default' => '#212121'
		),

		array(
			'id'      => 'plus_text_color',
			'type'    => 'color',
			'title'   => __( 'Plus sign text color', 'merchant' ),
			'default' => '#fff'
		),

		array(
			'id'      => 'bundle_border_color',
			'type'    => 'color',
			'title'   => __( 'Bundle border color', 'merchant' ),
			'default' => '#f9f9f9'
		),

		array(
			'id'      => 'bundle_border_radius',
			'type'    => 'range',
			'title'   => __( 'Bundle border radius', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 5
		),
	),
) );
