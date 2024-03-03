<?php

/**
 * Buy X Get Y Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'module' => Merchant_Buy_X_Get_Y::MODULE_ID,
	'title'  => esc_html__( 'Offers', 'merchant' ),
	'fields' => array(
		array(
			'id'           => 'rules',
			'type'         => 'flexible_content',
			'sorting'      => true,
			'accordion'    => true,
			'style'        => Merchant_Buy_X_Get_Y::MODULE_ID . '-style default',
			'button_label' => esc_html__( 'Add New Campaign', 'merchant' ),
			'layouts'      => array(
				'offer-details' => array(
					'title'       => esc_html__( 'Campaign', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'offer-title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer name', 'merchant' ),
							'default' => esc_html__( 'Campaign', 'merchant' ),
						),
						array(
							'id'      => 'rules_to_display',
							'type'    => 'select',
							'title'   => esc_html__( 'Trigger', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'Any product', 'merchant' ),
								'products'   => esc_html__( 'Specific products', 'merchant' ),
								'categories' => esc_html__( 'Product categories', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'        => 'product_ids',
							'type'      => 'products_selector',
							'title'     => esc_html__( 'Customer buys', 'merchant' ),
							'multiple'  => true,
							'desc'      => esc_html__( 'Select the products that will show the offer', 'merchant' ),
							'condition' => array( 'rules_to_display', '==', 'products' ),
						),
						array(
							'id'          => 'category_slugs',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Select the product categories that will show the offer.', 'merchant' ),
							'condition'   => array( 'rules_to_display', '==', 'categories' ),
						),
						array(
							'id'      => 'min_quantity',
							'type'    => 'number',
							'min'     => 0,
							'step'    => 1,
							'title'   => esc_html__( 'Quantity', 'merchant' ),
							'desc'    => esc_html__( 'The minimum quantity that customers should purchase to get the offer', 'merchant' ),
							'default' => 1,
						),
						array(
							'id'       => 'customer_get_product_ids',
							'type'     => 'products_selector',
							'title'    => esc_html__( 'Customer Gets', 'merchant' ),
							'multiple' => false,
							'desc'     => esc_html__( 'Select the products that the customer will get when they purchase the minimum required quantity.',
								'merchant' ),
						),
						array(
							'id'      => 'quantity',
							'type'    => 'number',
							'min'     => 0,
							'step'    => 1,
							'title'   => esc_html__( 'Quantity', 'merchant' ),
							'default' => 3,
						),
						array(
							'id'      => 'discount_type',
							'type'    => 'radio',
							'title'   => esc_html__( 'Discount Type', 'merchant' ),
							'options' => array(
								'percentage' => esc_html__( 'Percentage Discount', 'merchant' ),
								'fixed'      => esc_html__( 'Fixed Discount', 'merchant' ),
							),
							'default' => 'fixed',
						),
						array(
							'id'      => 'discount',
							'type'    => 'number',
							'min'     => 0,
							'step'    => 1,
							//'title'   => esc_html__( 'Discount Value', 'merchant' ),
							'default' => 1,
						),
						array(
							'id'      => 'single_product_placement',
							'type'    => 'radio',
							'title'   => esc_html__( 'Placement on product page', 'merchant' ),
							'options' => array(
								'after-cart-form'  => esc_html__( 'After add to cart', 'merchant' ),
								'before-cart-form' => esc_html__( 'Before add to cart', 'merchant' ),
							),
							'default' => 'after-cart-form',
						),

						// Text Formatting Settings
						array(
							'id'      => 'title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer title', 'merchant' ),
							'default' => esc_html__( 'Buy One Get One', 'merchant' ),
						),

						array(
							'id'      => 'buy_label',
							'type'    => 'text',
							'title'   => esc_html__( 'Buy label', 'merchant' ),
							'default' => esc_html__( 'Buy {quantity}', 'merchant' ),
						),

						array(
							'id'      => 'get_label',
							'type'    => 'text',
							'title'   => esc_html__( 'Get label', 'merchant' ),
							'default' => esc_html__( 'Get {quantity} with {discount} off', 'merchant' ),
						),

						array(
							'id'      => 'button_text',
							'type'    => 'text',
							'title'   => esc_html__( 'Button text', 'merchant' ),
							'default' => esc_html__( 'Add To Cart', 'merchant' ),
						),

						// Style Settings
						array(
							'id'      => 'title_font_weight',
							'type'    => 'select',
							'title'   => esc_html__( 'Font weight', 'merchant' ),
							'options' => array(
								'lighter' => esc_html__( 'Light', 'merchant' ),
								'normal'  => esc_html__( 'Normal', 'merchant' ),
								'bold'    => esc_html__( 'Bold', 'merchant' ),
							),
							'default' => 'normal',
						),

						array(
							'id'      => 'title_font_size',
							'type'    => 'range',
							'title'   => esc_html__( 'Font size', 'merchant' ),
							'min'     => 0,
							'max'     => 100,
							'step'    => 1,
							'unit'    => 'px',
							'default' => 16,
						),

						array(
							'id'      => 'title_text_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Title text color', 'merchant' ),
							'default' => '#212121',
						),

						array(
							'id'      => 'label_bg_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Label background color', 'merchant' ),
							'default' => '#d61313',
						),

						array(
							'id'      => 'label_text_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Label text color', 'merchant' ),
							'default' => '#fff',
						),

						array(
							'id'      => 'arrow_bg_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Arrow background color', 'merchant' ),
							'default' => '#d61313',
						),

						array(
							'id'      => 'arrow_text_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Arrow text color', 'merchant' ),
							'default' => '#fff',
						),


						array(
							'id'      => 'offer_border_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Offer border color', 'merchant' ),
							'default' => '#cccccc',
						),

						array(
							'id'      => 'offer_border_radius',
							'type'    => 'range',
							'title'   => esc_html__( 'Offer border Radius', 'merchant' ),
							'min'     => 0,
							'max'     => 100,
							'step'    => 1,
							'unit'    => 'px',
							'default' => 5,
						),
					),
				),
			),
			'default'      => array(
				array(
					'layout' => 'offer-details',
					'label'  => esc_html__( 'Buy 1 Get 1', 'merchant' ),
				),
			),
		),
	),
) );

// Shortcode
$merchant_module_id = Merchant_Buy_X_Get_Y::MODULE_ID;
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