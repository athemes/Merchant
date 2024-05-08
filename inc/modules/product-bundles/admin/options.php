<?php

/**
 * Product bundles Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Hook functionality before including modules options.
 *
 * @since 1.9.8
 */
do_action( 'merchant_admin_before_include_modules_options', Merchant_Product_Bundles::MODULE_ID );

Merchant_Admin_Options::create( array(
	'module' => Merchant_Product_Bundles::MODULE_ID,
	'title'  => esc_html__( 'Product page settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'bundled_thumb',
			'type'    => 'switcher',
			'title'   => __( 'Display bundled product thumbnails', 'merchant' ),
			'default' => '1',
		),

		array(
			'id'      => 'bundled_description',
			'type'    => 'switcher',
			'title'   => __( 'Display bundled product descriptions', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'      => 'bundled_qty',
			'type'    => 'switcher',
			'title'   => __( 'Display bundled product quantities', 'merchant' ),
			'default' => '1',
		),

		array(
			'id'      => 'bundled_link_single',
			'type'    => 'switcher',
			'title'   => __( 'Make bundled product thumbnails and titles clickable', 'merchant' ),
			'default' => '1',
		),

		array(
			'id'      => 'bundled_price',
			'type'    => 'select',
			'title'   => __( 'Display the prices of bundled products', 'merchant' ),
			'options' => array(
				'price'    => __( 'Price per unit', 'merchant' ),
				'subtotal' => __( 'Subtotal', 'merchant' ),
				'no'       => __( 'Hide', 'merchant' ),
			),
			'default' => 'price',
		),

		array(
			'id'      => 'bundled_price_from',
			'type'    => 'select',
			'title'   => __( 'Calculate the prices of bundled products based on', 'merchant' ),
			'options' => array(
				'regular_price' => __( 'Regular price', 'merchant' ),
				'sale_price'    => __( 'Sale Price', 'merchant' ),
			),
			'default' => 'sale_price',
		),

		array(
			'id'      => 'placement',
			'type'    => 'select',
			'title'   => __( 'Where to display the bundled products', 'merchant' ),
			'options' => array(
				'woocommerce_before_add_to_cart_form' => __( 'Before add to cart section', 'merchant' ),
				'woocommerce_after_add_to_cart_form'  => __( 'After add to cart section', 'merchant' ),
			),
			'default' => 'before_form',
		),
	),
) );

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Product_Bundles::MODULE_ID,
	'title'  => esc_html__( 'Cart settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'hide_bundled_cart',
			'type'    => 'switcher',
			'title'   => __( 'Hide bundled products in cart', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'      => 'hide_bundled_mini_cart',
			'type'    => 'switcher',
			'title'   => __( 'Hide bundled products in mini cart', 'merchant' ),
			'default' => 0,
		),

//      array(
//          'id'      => '_woopq_decimal',
//          'type'    => 'switcher',
//          'title'   => __( 'Allow decimal product quantity', 'merchant' ),
//          'default' => 0,
//      ),

		array(
			'id'      => 'bundled_link',
			'type'    => 'switcher',
			'title'   => __( 'Include links to bundled products on cart page', 'merchant' ),
			'default' => '1',
		),

		array(
			'id'      => 'cart_contents_count',
			'type'    => 'select',
			'title'   => __( 'Cart contents count will include', 'merchant' ),
			'options' => array(
				'bundle' => __( 'The bundle as one product', 'merchant' ),
				'both'   => __( 'Both bundle and bundled products', 'merchant' ),
			),
			'default' => 'bundle',
		),

		array(
			'id'      => 'hide_bundled',
			'type'    => 'radio',
			'title'   => __( 'Show bundled products', 'merchant' ),
			'options' => array(
				'text' => __( 'List inline', 'merchant' ),
				'list' => __( 'Bulleted list', 'merchant' ),
			),
			'default' => 'text',
		),
	),
) );


// Shortcode
Merchant_Admin_Options::create( array(
	'module' => Merchant_Product_Bundles::MODULE_ID,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __( 'Use shortcode', 'merchant' ),
			'default' => 0,
		),
		array(
			'type'    => 'info',
			'id'      => 'shortcode_info',
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
				'merchant' ),
		),
		array(
			'id'        => 'shortcode_text',
			'type'      => 'text_readonly',
			'title'     => esc_html__( 'Shortcode text', 'merchant' ),
			'default'   => '[merchant_module_' . str_replace( '-', '_', Merchant_Product_Bundles::MODULE_ID ) . ']',
			'condition' => array( 'use_shortcode', '==', '1' ),
		),
	),
) );