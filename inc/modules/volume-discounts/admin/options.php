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
	'title'  => esc_html__( 'Offers', 'merchant' ),
	'module' => Merchant_Volume_Discounts::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'offers',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New Offer', 'merchant' ),
			'style'        => Merchant_Volume_Discounts::MODULE_ID . '-style default',
			'sorting'      => false,
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
								'products'   => esc_html__( 'Specific product', 'merchant' ),
								'categories' => esc_html__( 'Product categories', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'        => 'product_id',
							'type'      => 'products_selector',
							//'title'     => esc_html__( 'Select product', 'merchant' ),
							'multiple'  => false,
							'desc'      => esc_html__( 'Select the product that the customer will get a discount on when they purchase the minimum required quantity.',
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
							'id'      => 'quantity',
							'type'    => 'number',
							'title'   => esc_html__( 'Quantity', 'merchant' ),
							'default' => 2,
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
							'id'      => 'discount',
							'type'    => 'number',
							'default' => 10,
						),

						array(
							'id'      => 'user_condition',
							'type'    => 'select',
							'title'   => esc_html__( 'User Condition', 'merchant' ),
							'options' => array(
								'all'       => esc_html__( 'All Users', 'merchant' ),
								'logged-in' => esc_html__( 'Logged In Users', 'merchant' ),
								'customers' => esc_html__( 'Selected Users', 'merchant' ),
								'roles'     => esc_html__( 'Selected Roles', 'merchant' ),
							),
							'default' => 'all',
						),

						array(
							'id'        => 'user_condition_roles',
							'type'      => 'select_ajax',
							'title'     => esc_html__( 'User Roles', 'merchant' ),
							'desc'      => esc_html__( 'This will limit the offer to users with these roles.', 'merchant' ),
							'source'    => 'options',
							'multiple'  => true,
							'classes'   => array( 'flex-grow' ),
							'options'   => Merchant_Admin_Options::get_user_roles_select2_choices(),
							'condition' => array( 'user_condition', '==', 'roles' ),
						),

						array(
							'id'        => 'user_condition_users',
							'type'      => 'select_ajax',
							'title'     => esc_html__( 'Customer', 'merchant' ),
							'desc'      => esc_html__( 'This will limit the offer to the selected customers.', 'merchant' ),
							'source'    => 'options',
							'multiple'  => true,
							'classes'   => array( 'flex-grow' ),
							'options'   => Merchant_Admin_Options::get_customers_select2_choices(),
							'condition' => array( 'user_condition', '==', 'customers' ),
						),

						array(
							'id'      => 'single_product_placement',
							'type'    => 'radio',
							'title'   => esc_html__( 'Placement on product page', 'merchant' ),
							'options' => array(
								'before-cart-form' => esc_html__( 'Before add to cart', 'merchant' ),
								'after-cart-form'  => esc_html__( 'After add to cart', 'merchant' ),
							),
							'default' => 'before-cart-form',
						),

						// text formatting
						array(
							'id'      => 'table_title',
							'type'    => 'text',
							'title'   => esc_html__( 'Offer title', 'merchant' ),
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
							'title'   => esc_html__( 'Tier format text', 'merchant' ),
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

						// style settings
						array(
							'id'      => 'title_font_size',
							'type'    => 'range',
							'title'   => esc_html__( 'Title font size', 'merchant' ),
							'min'     => 0,
							'max'     => 100,
							'step'    => 1,
							'unit'    => 'px',
							'default' => 16,
						),

						array(
							'id'      => 'title_font_weight',
							'type'    => 'select',
							'title'   => esc_html__( 'Title font weight', 'merchant' ),
							'options' => array(
								'lighter' => esc_html__( 'Light', 'merchant' ),
								'normal'  => esc_html__( 'Normal', 'merchant' ),
								'bold'    => esc_html__( 'Bold', 'merchant' ),
							),
							'default' => 'normal',
						),

						array(
							'id'      => 'title_text_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Title text color', 'merchant' ),
							'default' => '#212121',
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
							'default' => '#3c434a',
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
		),
		array(
			'type'    => 'info',
			'id'      => 'shortcode_info',
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
