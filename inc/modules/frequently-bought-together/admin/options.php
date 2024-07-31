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
	'title'  => esc_html__( 'Bundle Offers', 'merchant' ),
	'module' => Merchant_Frequently_Bought_Together::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'offers',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New Bundle', 'merchant' ),
			'style'        => Merchant_Frequently_Bought_Together::MODULE_ID . '-style default',
			'sorting'      => true,
			'duplicate'    => true,
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
							'title'   => esc_html__( 'Trigger', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'All products', 'merchant' ),
								'products'   => esc_html__( 'Specific product', 'merchant' ),
								'categories' => esc_html__( 'Specific categories', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'        => 'product_to_display',
							'type'      => 'products_selector',
							'title'     => esc_html__( 'Select a product', 'merchant' ),
							'multiple'  => false,
							'desc'      => esc_html__( 'Select the product that you want to create the bundle for.', 'merchant' ),
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
							'id'        => 'excluded_products',
							'type'      => 'products_selector',
							'title'     => esc_html__( 'Exclude Products', 'merchant' ),
							'multiple'  => true,
							'desc'      => esc_html__( 'Exclude products from this campaign.', 'merchant' ),
							'condition' => array( 'rules_to_display', 'any', 'all|categories' ),
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
							'condition'   => array( 'rules_to_display', '==', 'all' ),
						),

						array(
							'id'       => 'products',
							'title'    => esc_html__( 'Products to offer', 'merchant' ),
							'type'     => 'products_selector',
							'multiple' => true,
							'desc'     => esc_html__( 'Select the products that will be included the bundle.', 'merchant' ),
						),

						array(
							'id'        => 'external',
							'label'     => __( 'Display the offer on all products in the bundle', 'merchant' ),
							'type'      => 'checkbox',
							'default'   => 0,
							'condition' => array( 'rules_to_display', '==', 'products' ),
						),
						array(
							'id'      => 'enable_discount',
							'type'    => 'switcher',
							'title'   => __( 'Offer a discount on this bundle', 'merchant' ),
							'default' => 0,
						),
						array(
							'id'        => 'discount_type',
							'type'      => 'radio',
							'title'     => esc_html__( 'Discount', 'merchant' ),
							'options'   => array(
								'percentage_discount' => esc_html__( 'Percentage', 'merchant' ),
								'fixed_discount'      => esc_html__( 'Fixed', 'merchant' ),
							),
							'default'   => 'percentage',
							'condition' => array( 'enable_discount', '==', '1' ),
						),
						array(
							'id'        => 'discount_value',
							'type'      => 'number',
							'default'   => 10,
							'condition' => array( 'enable_discount', '==', '1' ),
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
							'id'             => 'product_single_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Product Single Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how frequently bought together offers appear on product pages.',
								'merchant' ),
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
										'after-summary' => esc_html__( 'After Product Summary', 'merchant' ),
										'after-tabs'    => esc_html__( 'After Product Tabs', 'merchant' ),
										'bottom'        => esc_html__( 'At the Bottom', 'merchant' ),
									),
									'default' => 'after-summary',
								),

								// text formatting settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Bundle title', 'merchant' ),
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
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount */
										__(
											'<strong>%1$s:</strong> to show discount amount',
											'merchant'
										),
										'{amount}'
									),
								),

								array(
									'id'      => 'no_variation_selected_text',
									'type'    => 'text',
									'title'   => esc_html__( 'No variation selected text', 'merchant' ),
									'default' => esc_html__( 'Please select an option to see your savings.', 'merchant' ),
								),

								array(
									'id'      => 'no_variation_selected_text_has_no_discount',
									'type'    => 'text',
									'title'   => esc_html__( 'No variation selected text (no discount)', 'merchant' ),
									'desc'    => esc_html__( 'This text will be displayed when the bundle has no discount and includes a variable product.', 'merchant' ),
									'default' => esc_html__( 'Please select an option to see the total price.', 'merchant' ),
								),

								array(
									'id'      => 'button_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Button text', 'merchant' ),
									'default' => esc_html__( 'Add to cart', 'merchant' ),
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
									'title'   => esc_html__( 'Bundle border radius', 'merchant' ),
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
							'default'        => 'inactive',
							'sub-desc'       => esc_html__( 'Use these settings to control how frequently bought together offers appear on the cart page.', 'merchant' ),
							'state'          => 'closed',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								// text formatting settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Bundle title', 'merchant' ),
									'default' => esc_html__( 'Add', 'merchant' ),
								),

								//                              array(
								//                                  'id'      => 'price_label',
								//                                  'type'    => 'text',
								//                                  'title'   => esc_html__( 'Price label', 'merchant' ),
								//                                  'default' => esc_html__( 'Bundle price', 'merchant' ),
								//                              ),

								array(
									'id'      => 'save_label',
									'type'    => 'text',
									'title'   => esc_html__( 'And save label', 'merchant' ),
									'default' => esc_html__( 'and save: {amount}', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount */
										__(
											'<strong>%1$s:</strong> to show discount amount',
											'merchant'
										),
										'{amount}'
									),
								),

								//                              array(
								//                                  'id'      => 'no_variation_selected_text',
								//                                  'type'    => 'text',
								//                                  'title'   => esc_html__( 'No variation selected text', 'merchant' ),
								//                                  'default' => esc_html__( 'Please select an option to see your savings.', 'merchant' ),
								//                              ),
								//
								//                              array(
								//                                  'id'      => 'no_variation_selected_text_has_no_discount',
								//                                  'type'    => 'text',
								//                                  'title'   => esc_html__( 'No variation selected text (no discount)', 'merchant' ),
								//                                  'desc'    => esc_html__( 'This text will be displayed when the bundle has no discount and includes a variable product.', 'merchant' ),
								//                                  'default' => esc_html__( 'Please select an option to see the total price.', 'merchant' ),
								//                              ),

								array(
									'id'      => 'button_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Button text', 'merchant' ),
									'default' => esc_html__( 'Add to cart', 'merchant' ),
								),
							),
						),
						array(
							'id'             => 'checkout_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Checkout Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how Frequently bought together offers appear on the checkout page.', 'merchant' ),
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
									'default' => esc_html__( 'Bundle and Save!', 'merchant' ),
								),
								array(
									'id'      => 'discount_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Discount text', 'merchant' ),
									'default' => esc_html__( 'Add to get {discount} off all items in your bundle ({fbt_products}).', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount, %2$s: FBT offer product names */
										__(
											'<strong>%1$s:</strong> to show discount amount<br><strong>%2$s:</strong> to show the product names in the offer',
											'merchant'
										),
										'{discount}',
										'{fbt_products}'
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
							'title'          => esc_html__( 'Thank you Page', 'merchant' ),
							'default'        => 'inactive',
							'sub-desc'       => esc_html__( 'Use these settings to control how frequently bought together offers appear on the thank you page.', 'merchant' ),
							'state'          => 'closed',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Bundle title', 'merchant' ),
									'default' => esc_html__( 'Last chance to get {discount} off your bundle!', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount */
										__(
											'<strong>%1$s:</strong> to show discount amount',
											'merchant'
										),
										'{discount}'
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
									/* Translators: %1$s: Discount amount */
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
									'default' => esc_html__( 'Add to cart', 'merchant' ),
								),
							),
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