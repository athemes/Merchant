<?php

/**
 * Frequently Bought Together Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Offers', 'merchant' ),
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'offers',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New Offer', 'merchant' ),
			'style'        => Merchant_Frequently_Bought_Together::MODULE_ID . '-style default',
			'sorting'      => true,
			'accordion'    => true,
			'layouts'      => array(
				'offer-details' => array(
					'title'       => esc_html__( 'Create Discount Tiers', 'merchant' ),
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
							'title'   => esc_html__( 'Offered product(s)', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'Any product', 'merchant' ),
								'products'   => esc_html__( 'Specific products', 'merchant' ),
								'categories' => esc_html__( 'Product categories', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'        => 'product_to_display',
							'type'      => 'products_selector',
							'multiple'  => false,
							'desc'      => esc_html__( 'Select the products that will contain the bundle.',
								'merchant' ),
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
							'id'       => 'products',
							'title'    => esc_html__( 'Offered product(s)', 'merchant' ),
							'type'     => 'products_selector',
							'multiple' => true,
							'desc'     => esc_html__( 'Select the products that will be included the bundle.', 'merchant' ),
						),
						array(
							'id'      => 'external',
							'title'   => __( 'Display the offer on all products in the bundle', 'merchant' ),
							'type'    => 'checkbox',
							'default' => 0,
							'condition'   => array( 'rules_to_display', '==', 'products' ),
						),
						array(
							'id'      => 'discount_type',
							'type'    => 'radio',
							'title'   => esc_html__( 'Discount', 'merchant' ),
							'options' => array(
								'percentage_discount' => esc_html__( 'Percentage', 'merchant' ),
								'fixed_discount'      => esc_html__( 'Fixed', 'merchant' ),
							),
							'default' => 'percentage',
						),
						array(
							'id'      => 'discount_value',
							'type'    => 'number',
							'default' => 10,
						),
						array(
							'id'      => 'single_product_placement',
							'type'    => 'radio',
							'title'   => esc_html__( 'Placement on product page', 'merchant' ),
							'options' => array(
								'after-summary' => __( 'After product summary', 'merchant' ),
								'after-tabs'    => __( 'After product tabs', 'merchant' ),
								'bottom'        => __( 'At the bottom', 'merchant' ),
							),
							'default' => 'after-summary',
						),

						// text formatting settings
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
							'id'      => 'no_variation_selected_text_has_no_discount',
							'type'    => 'text',
							'title'   => __( 'No variation selected text (no discount)', 'merchant' ),
							'desc'    => __( 'This text will be displayed when the bundle has no discount and includes a variable product.', 'merchant' ),
							'default' => __( 'Please select an option to see the total price.', 'merchant' ),
						),

						array(
							'id'      => 'button_text',
							'type'    => 'text',
							'title'   => __( 'Button text', 'merchant' ),
							'default' => __( 'Add to cart', 'merchant' ),
						),

						// style settings
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
							'title'   => __( 'Bundle border radius', 'merchant' ),
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
					'layout'        => 'offer-details',
					'min_quantity'  => 2,
					'discount'      => 10,
					'discount_type' => 'percentage_discount',
				),
			),
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
			'desc'    => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
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