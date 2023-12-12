<?php

/**
 * Volume Discounts Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Volume_Discounts::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'single_product_placement',
			'type'    => 'select',
			'title'   => esc_html__( 'Placement on product page', 'merchant' ),
			'options' => array(
				'after-cart-form'  => esc_html__( 'After add to cart form', 'merchant' ),
				'before-cart-form' => esc_html__( 'Before add to cart form', 'merchant' ),
			),
			'default' => 'before-cart-form'
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Text Formatting Settings', 'merchant' ),
	'module' => Merchant_Volume_Discounts::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'table_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Title', 'merchant' ),
			'default' => __( 'Buy more, save more!', 'merchant' ),
		),

		array(
			'id'      => 'save_label',
			'type'    => 'text',
			'title'   => esc_html__( 'Save label', 'merchant' ),
			'default' => esc_html__( 'Save {amount}', 'merchant' ),
		),

		array(
			'id'      => 'buy_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Buy text', 'merchant' ),
			'default' => esc_html__( 'Buy {amount}, get {discount} off each', 'merchant' ),
		),

		array(
			'id'      => 'item_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Item text', 'merchant' ),
			'default' => esc_html__( 'Per item:', 'merchant' ),
		),

		array(
			'id'      => 'total_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Total text', 'merchant' ),
			'default' => esc_html__( 'Total price:', 'merchant' ),
		),

		array(
			'id'      => 'cart_title_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Cart item discount title', 'merchant' ),
			'default' => esc_html__( 'Discount', 'merchant' ),
			'desc'    => esc_html__( 'This is displayed on the cart page.', 'merchant' ),
		),

		array(
			'id'      => 'cart_description_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Cart item discount description', 'merchant' ),
			'default' => esc_html__( 'A discount of {amount} has been applied.', 'merchant' ),
			'desc'    => esc_html__( 'This is displayed on the cart page.', 'merchant' ),
		),
	),
) );


Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Style Settings', 'merchant' ),
	'module' => Merchant_Volume_Discounts::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'title_font_weight',
			'type'    => 'select',
			'title'   => esc_html__( 'Title font weight', 'merchant' ),
			'options' => array(
				'lighter' => esc_html__( 'Light', 'merchant' ),
				'normal'  => esc_html__( 'Normal', 'merchant' ),
				'bold' => esc_html__( 'Bold', 'merchant' ),
			),
			'default' => 'normal'
		),

		array(
			'id'      => 'title_font_size',
			'type'    => 'range',
			'title'   => esc_html__( 'Title font size', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 16
		),

		array(
			'id'      => 'title_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title text color', 'merchant' ),
			'default' => '#212121'
		),


		array(
			'id'      => 'table_item_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Choose background color', 'merchant' ),
			'default' => '#fcf0f1',
		),

		array(
			'id'      => 'table_item_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Choose border color', 'merchant' ),
			'default' => '#d83b3b',
		),

		array(
			'id'      => 'table_item_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Choose text color', 'merchant' ),
			'default' => '#3c434a'
		),

		array(
			'id'      => 'table_label_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Choose label background color', 'merchant' ),
			'default' => '#d83b3b',
		),

		array(
			'id'      => 'table_label_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Choose label text color', 'merchant' ),
			'default' => '#ffffff',
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Volume_Discounts::MODULE_ID;
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
