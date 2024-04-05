<?php

/**
 * Add To Cart Text Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Add_To_Cart_Text::MODULE_ID,
	'title'  => esc_html__( 'Simple Product', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'simple_product_shop_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label', 'merchant' ),
			'default' => esc_html__( 'Add to cart', 'merchant' ),
			
		),
		array(
			'id'      => 'simple_product_shop_label_info',
			'type'    => 'info',
			/* Translators: 1. Link open tag 2. Link close tag */
			'content' => sprintf( esc_html__( 'If you want to add a different label for each product, go to %1$sProducts%2$s, edit the desired product, set the label inside the metabox.',
				'merchant' ),
				'<a href="' . esc_url( admin_url( 'edit.php?post_type=product' ) ) . '">',
				'</a>' ),
		),

		array(
			'id'      => 'simple_product_custom_single_label',
			'type'    => 'switcher',
			'title'   => __( 'Customize label on single product page', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'      => 'simple_product_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label on single product page', 'merchant' ),
			'default' => esc_html__( 'Add to cart', 'merchant' ),
			'condition' => array( 'simple_product_custom_single_label', '==', '1' ),
		),
	),
) );


// Text Formatting Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Add_To_Cart_Text::MODULE_ID,
	'title'  => esc_html__( 'Variable Product', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'variable_product_shop_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label', 'merchant' ),
			'default' => esc_html__( 'Select options', 'merchant' ),
			
		),
		array(
			'id'      => 'variable_product_shop_label_info',
			'type'    => 'info',
			/* Translators: 1. Link open tag 2. Link close tag */
			'content' => sprintf( esc_html__( 'If you want to add a different label for each product, go to %1$sProducts%2$s, edit the desired product, set the label inside the metabox.',
				'merchant' ),
				'<a href="' . esc_url( admin_url( 'edit.php?post_type=product' ) ) . '">',
				'</a>' ),
		),

		array(
			'id'      => 'variable_product_custom_single_label',
			'type'    => 'switcher',
			'title'   => __( 'Customize label on single product page', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'      => 'variable_product_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label on single product page', 'merchant' ),
			'default' => esc_html__( 'Add to cart', 'merchant' ),
			'condition' => array( 'variable_product_custom_single_label', '==', '1' ),
		),
	),
) );

Merchant_Admin_Options::create( array(
	'module' => Merchant_Add_To_Cart_Text::MODULE_ID,
	'title'  => esc_html__( 'Out of Stock Product', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'out_of_stock_custom_label',
			'type'    => 'switcher',
			'title'   => __( 'Alter the label text when the product is out of stock', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'      => 'out_of_stock_shop_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Label', 'merchant' ),
			'default' => esc_html__( 'Out of stock', 'merchant' ),
			'condition' => array( 'out_of_stock_custom_label', '==', '1' ),
		),
	),
) );
