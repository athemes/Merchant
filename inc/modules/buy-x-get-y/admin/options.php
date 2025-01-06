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
			'duplicate'    => true,
			'style'        => Merchant_Buy_X_Get_Y::MODULE_ID . '-style default',
			'button_label' => esc_html__( 'Add New Offer', 'merchant' ),
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
								'all'        => esc_html__( 'All Products', 'merchant' ),
								'products'   => esc_html__( 'Specific Products', 'merchant' ),
								'categories' => esc_html__( 'Specific Categories', 'merchant' ),
								'tags'       => esc_html__( 'Specific Tags', 'merchant' ),
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
							'id'          => 'tag_slugs',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Tags', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_tag_select2_choices(),
							'placeholder' => esc_html__( 'Select tags', 'merchant' ),
							'desc'        => esc_html__( 'Select the product tags that will show the offer.', 'merchant' ),
							'condition'   => array( 'rules_to_display', '==', 'tags' ),
						),

						array(
							'id'         => 'exclusion_enabled',
							'type'       => 'switcher',
							'title'      => esc_html__( 'Exclusion List', 'merchant' ),
							'desc'       => esc_html__( 'Select the products that will not show the offer.', 'merchant' ),
							'default'    => 0,
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'rules_to_display',
										'operator' => 'in',
										'value'    => array( 'all', 'categories', 'tags' ),
									),
								),
							),
						),

						array(
							'id'         => 'excluded_products',
							'type'       => 'products_selector',
							'title'      => esc_html__( 'Exclude Products', 'merchant' ),
							'multiple'   => true,
							'desc'       => esc_html__( 'Exclude products from this campaign.', 'merchant' ),
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'rules_to_display',
										'operator' => 'in',
										'value'    => array( 'all', 'categories', 'tags' ),
									),
									array(
										'field'    => 'exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),
						array(
							'id'          => 'excluded_categories',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Exclude Categories', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_category_select2_choices(),
							'placeholder' => esc_html__( 'Select categories', 'merchant' ),
							'desc'        => esc_html__( 'Exclude categories from this campaign.', 'merchant' ),
							'conditions'  => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'rules_to_display',
										'operator' => 'in',
										'value'    => array( 'all' ),
									),
									array(
										'field'    => 'exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),

						array(
							'id'          => 'excluded_tags',
							'type'        => 'select_ajax',
							'title'       => esc_html__( 'Exclude Tags', 'merchant' ),
							'source'      => 'options',
							'multiple'    => true,
							'options'     => Merchant_Admin_Options::get_tag_select2_choices(),
							'placeholder' => esc_html__( 'Select tags', 'merchant' ),
							'desc'        => esc_html__( 'Exclude tags from this campaign.', 'merchant' ),
							'conditions'  => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'rules_to_display',
										'operator' => 'in',
										'value'    => array( 'all' ),
									),
									array(
										'field'    => 'exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
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
							'id'        => 'external',
							'label'     => __( 'Display the offer on all products in the bundle', 'merchant' ),
							'type'      => 'checkbox',
							'default'   => 0,
							'condition' => array( 'rules_to_display', '==', 'products' ),
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
							'default' => 'percentage',
						),
						array(
							'id'      => 'discount',
							'type'    => 'number',
							'min'     => 0,
							'step'    => 0.01,
							//'title'   => esc_html__( 'Discount Value', 'merchant' ),
							'default' => 1,
						),

						array(
							'id'      => 'user_condition',
							'type'    => 'select',
							'title'   => esc_html__( 'User Condition', 'merchant' ),
							'options' => array(
								'all'       => esc_html__( 'All Users', 'merchant' ),
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
							'title'     => esc_html__( 'Users', 'merchant' ),
							'desc'      => esc_html__( 'This will limit the offer to the selected customers.', 'merchant' ),
							'source'    => 'user',
							'multiple'  => true,
							'classes'   => array( 'flex-grow' ),
							'condition' => array( 'user_condition', '==', 'customers' ),
						),

						array(
							'id'         => 'user_exclusion_enabled',
							'type'       => 'switcher',
							'title'      => esc_html__( 'Exclusion List', 'merchant' ),
							'desc'       => esc_html__( 'Select the users that will not show the offer.', 'merchant' ),
							'default'    => 0,
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'user_condition',
										'operator' => 'in',
										'value'    => array( 'all', 'roles' ),
									),
								),
							),
						),

						array(
							'id'         => 'exclude_users',
							'type'       => 'select_ajax',
							'title'      => esc_html__( 'Exclude Users', 'merchant' ),
							'desc'       => esc_html__( 'This will exclude the offer for the selected customers.', 'merchant' ),
							'source'     => 'user',
							'multiple'   => true,
							'classes'    => array( 'flex-grow' ),
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'user_condition',
										'operator' => 'in',
										'value'    => array( 'all', 'roles' ),
									),
									array(
										'field'    => 'user_exclusion_enabled',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),

						array(
							'id'             => 'product_single_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Product Single Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how bulk discount offers appear on product pages.', 'merchant' ),
							'state'          => 'open',
							'default'        => 'active',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
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
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo offered product quantity */
										__(
											'<strong>%1$s:</strong> to show offered product quantity',
											'merchant'
										),
										'{quantity}'
									),
								),

								array(
									'id'      => 'get_label',
									'type'    => 'text',
									'title'   => esc_html__( 'Get label', 'merchant' ),
									'default' => esc_html__( 'Get {quantity} with {discount} off', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo offered product quantity, %2$s: bogo offer discount */
										__(
											'<strong>%1$s:</strong> to show offered product quantity<br><strong>%2$s:</strong> to show offer discount',
											'merchant'
										),
										'{quantity}',
										'{discount}'
									),
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
						array(
							'id'             => 'cart_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Cart Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how bulk discount offers appear on the cart page.', 'merchant' ),
							'state'          => 'closed',
							'default'        => 'inactive',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								// Text Formatting Settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'You are eligible to get {offer_quantity}x', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo offered product quantity */
										__(
											'<strong>%1$s:</strong> to show offered product quantity',
											'merchant'
										),
										'{offer_quantity}'
									),
								),

								array(
									'id'      => 'discount_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Discount text', 'merchant' ),
									'default' => esc_html__( 'with {discount} off', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo Discount amount */
										__(
											'<strong>%1$s:</strong> to show discount amount',
											'merchant'
										),
										'{discount}'
									),
								),

								array(
									'id'      => 'button_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Button text', 'merchant' ),
									'default' => esc_html__( 'Add To Cart', 'merchant' ),
								),
							),
						),
						array(
							'id'             => 'checkout_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Checkout Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how Buy X get Y offers appear on the checkout page.', 'merchant' ),
							'state'          => 'closed',
							'default'        => 'inactive',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								array(
									'id'      => 'placement',
									'type'    => 'select',
									'title'   => esc_html__( 'Placement', 'merchant' ),
									'options' => array(
										'before_billing_details'     => esc_html__( 'Before Billing Details', 'merchant' ),
										'after_billing_details'      => esc_html__( 'After Billing Details', 'merchant' ),
										'before_order_details'       => esc_html__( 'Before Order Details', 'merchant' ),
										'before_payment_options'     => esc_html__( 'Before Payment Gateways', 'merchant' ),
										'before_order_placement_btn' => esc_html__( 'Before Order Placement Button', 'merchant' ),
										'after_order_placement_btn'  => esc_html__( 'After Order Placement Button', 'merchant' ),
									),
									'default' => 'before_payment_options',
								),
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'You are eligible to get {offer_quantity}x', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo offer quantity */
										__(
											'<strong>%1$s:</strong> to show offer quantity',
											'merchant'
										),
										'{offer_quantity}'
									),
								),
								array(
									'id'      => 'discount_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Discount text', 'merchant' ),
									'default' => esc_html__( 'with {discount} off', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo Discount amount */
										__(
											'<strong>%1$s:</strong> to show discount amount',
											'merchant'
										),
										'{discount}'
									),
								),
								array(
									'id'      => 'button_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Button text', 'merchant' ),
									'default' => esc_html__( 'Add To Cart', 'merchant' ),
								),
							),
						),
						array(
							'id'             => 'thank_you_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Thank You Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how Buy X get Y offers appear on the thank you page.', 'merchant' ),
							'state'          => 'closed',
							'default'        => 'inactive',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								// Text Formatting Settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'Last chance to get {offer_quantity}x', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo {offer_quantity} tag */
										__(
											'<strong>%1$s:</strong> to show offer quantity',
											'merchant'
										),
										'{offer_quantity}'
									),
								),

								array(
									'id'      => 'placement',
									'type'    => 'select',
									'title'   => esc_html__( 'Placement', 'merchant' ),
									'options' => array(
										'on_top'               => esc_html__( 'On Top', 'merchant' ),
										'before_order_details' => esc_html__( 'Before Order details', 'merchant' ),
										'after_order_details'  => esc_html__( 'After Order details', 'merchant' ),
									),
									'default' => 'before_order_details',
								),

								array(
									'id'      => 'discount_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Discount text', 'merchant' ),
									'default' => esc_html__( 'with {discount} off', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: bogo Discount amount */
										__(
											'<strong>%1$s:</strong> to show discount amount',
											'merchant'
										),
										'{discount}'
									),
								),

								array(
									'id'      => 'button_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Button text', 'merchant' ),
									'default' => esc_html__( 'Add To Cart', 'merchant' ),
								),
							),
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
			'type'    => 'info',
			'id'      => 'shortcode_info',
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.',
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