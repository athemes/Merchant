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
	'title'  => esc_html__( 'Complementary Offers', 'merchant' ),
	'module' => Merchant_Complementary_Products::MODULE_ID,
	'fields' => array(
		array(
			'id'           => 'offers',
			'type'         => 'flexible_content',
			'button_label' => esc_html__( 'Add New Complementary Offer', 'merchant' ),
			'style'        => Merchant_Complementary_Products::MODULE_ID . '-style default',
			'sorting'      => true,
			'duplicate'    => true,
			'accordion'    => true,
			'layouts'      => array(
				'offer-details' => array(
					'title'       => esc_html__( 'Create Discount Tiers', 'merchant' ),
					'title-field' => 'offer-title', // text field ID to use as title for the layout
					'fields'      => array(
						array(
							'id'      => 'campaign_status',
							'type'    => 'select',
							'title'   => esc_html__( 'Status', 'merchant' ),
							'options' => array(
								'active'   => esc_html__( 'Active', 'merchant' ),
								'inactive' => esc_html__( 'Inactive', 'merchant' ),
							),
							'default' => 'active',
						),
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
							'id'        => 'product_to_display',
							'type'      => 'products_selector',
							'title'     => esc_html__( 'Select a product', 'merchant' ),
							'multiple'  => false,
							'desc'      => esc_html__( 'Select the product you want to pair with the complementary items.', 'merchant' ),
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
							'desc'        => esc_html__( 'Select the product categories that will show the complementary items.', 'merchant' ),
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
							'desc'        => esc_html__( 'Select the product tags that will show the complementary items.', 'merchant' ),
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
							'id'       => 'products',
							'title'    => esc_html__( 'Complementary products', 'merchant' ),
							'type'     => 'products_selector',
							'multiple' => true,
							'desc'     => esc_html__( 'Select the products you want to sell alongside the main product', 'merchant' ),
						),
						array(
							'id'      => 'enable_discount',
							'type'    => 'switcher',
							'title'   => __( 'Offer a discount on this bundle', 'merchant' ),
							'default' => 0,
						),
						array(
							'id'         => 'discount_type',
							'type'       => 'radio',
							'title'      => esc_html__( 'Discount', 'merchant' ),
							'options'    => array(
								'percentage_discount' => esc_html__( 'Percentage', 'merchant' ),
								'fixed_discount'      => esc_html__( 'Fixed', 'merchant' ),
								'cheapest_item_free'  => esc_html__( 'Cheapest item free', 'merchant' ),
								'free_shipping'       => esc_html__( 'Free Shipping', 'merchant' ),
							),
							'default'    => 'percentage_discount',
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'enable_discount',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),
						array(
							'id'         => 'discount_value',
							'type'       => 'number',
							'default'    => 10,
							'step'       => 0.01,
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'enable_discount',
										'operator' => '===',
										'value'    => true,
									),
									array(
										'field'    => 'discount_type',
										'operator' => 'in',
										'value'    => array( 'percentage_discount', 'fixed_discount' ),
									),
								),
							),
						),
						array(
							'id'         => 'min_selection_discount',
							'title'      => esc_html__( 'Minimum product selection to trigger the discount', 'merchant' ),
							'type'       => 'number',
							'default'    => 1,
							'step'       => 1,
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'enable_discount',
										'operator' => '===',
										'value'    => true,
									),
								),
							),
						),
						array(
							'id'      => 'checkboxes-state',
							'type'    => 'radio',
							'title'   => esc_html__( 'Checkboxes for each product', 'merchant' ),
							'options' => array(
								'auto-checked' => esc_html__( 'Auto checked', 'merchant' ),
								'unchecked'    => esc_html__( 'Unchecked', 'merchant' ),
							),
							'default' => 'unchecked',
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
							'desc'       => esc_html__( 'Hide this offer from certain users.', 'merchant' ),
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
							'sub-desc'       => esc_html__( 'Use these settings to control how complementary product offers appear on product pages.',
								'merchant' ),
							'state'          => 'open',
							'default'        => 'active',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								array(
									'id'      => 'single_product_placement',
									'type'    => 'select',
									'title'   => esc_html__( 'Placement on product page', 'merchant' ),
									'options' => array(
										'before-add-to-cart' => esc_html__( 'Before Add to Cart', 'merchant' ),
										'after-summary'      => esc_html__( 'After Product Summary', 'merchant' ),
										'after-tabs'         => esc_html__( 'After Product Tabs', 'merchant' ),
									),
									'default' => 'before-add-to-cart',
								),

								// text formatting settings
								array(
									'id'      => 'offer-title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'Campaign', 'merchant' ),
									'desc'    => esc_html__( 'Enter an optional title to show before the complementary products', 'merchant' ),
								),

								array(
									'id'    => 'offer-description',
									'type'  => 'textarea',
									'title' => esc_html__( 'Short description', 'merchant' ),
									'desc'  => esc_html__( 'Enter an optional description to display after the offer title.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Display the discount amount */
										__(
											'<strong>%1$s:</strong> Display the discount, only applies to percentage or fixed-amount discounts',
											'merchant'
										),
										'{discount_amount}',
									),
								),
							),
						),

						array(
							'id'             => 'cart_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Cart Page', 'merchant' ),
							'default'        => 'inactive',
							'sub-desc'       => esc_html__( 'Use these settings to control how complementary product offers appear on the cart page.', 'merchant' ),
							'state'          => 'closed',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
								// text formatting settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Campaign title', 'merchant' ),
									'default' => esc_html__( 'Add', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Display the discount amount */
										__(
											'<strong>%1$s:</strong> Display the discount, only applies to percentage or fixed-amount discounts',
											'merchant'
										),
										'{discount_amount}',
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

						array(
							'id'             => 'checkout_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Checkout Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how complementary product offers appear on the checkout page.', 'merchant' ),
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
									'default' => esc_html__( 'Recommended For You', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Display the discount amount */
										__(
											'<strong>%1$s:</strong> Display the discount, only applies to percentage or fixed-amount discounts',
											'merchant'
										),
										'{discount_amount}',
									),
								),
								array(
									'id'          => 'offer_description',
									'type'        => 'textarea',
									'title'       => esc_html__( 'Description', 'merchant' ),
									'desc'     => esc_html__( 'Enter an optional campaign description', 'merchant' ),
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
							'sub-desc'       => esc_html__( 'Use these settings to control how complementary product offers appear on the thank you page.', 'merchant' ),
							'state'          => 'closed',
							'accordion'      => true,
							'display_status' => true,
							'fields'         => array(
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
									'id'          => 'title',
									'type'        => 'text',
									'title'       => esc_html__( 'Bundle title', 'merchant' ),
									'default'     => esc_html__( 'Last chance to get', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Display the discount amount */
										__(
											'<strong>%1$s:</strong> Display the discount, only applies to percentage or fixed-amount discounts',
											'merchant'
										),
										'{discount_amount}',
									),
								),

								array(
									'id'          => 'discount_text',
									'type'        => 'text',
									'title'       => esc_html__( 'Discount text', 'merchant' ),
									'default'     => esc_html__( 'With discount', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Display the discount amount */
										__(
											'<strong>%1$s:</strong> Display the discount, only applies to percentage or fixed-amount discounts',
											'merchant'
										),
										'{discount_amount}',
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


/**
 * Style
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => Merchant_Complementary_Products::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'layout',
			'type'    => 'radio',
			'title'   => esc_html__( 'Layout', 'merchant' ),
			'options' => array(
				'classic' => esc_html__( 'Classic', 'merchant' ),
				// only the copy has been changed to carousel, but the naming is the same "slider"
				'slider'  => esc_html__( 'Carousel', 'merchant' ),
			),
			'default' => 'slider',
		),

		array(
			'id'      => 'checkbox_style',
			'type'    => 'radio',
			'title'   => esc_html__( 'Checkbox style', 'merchant' ),
			'options' => array(
				'rounded' => esc_html__( 'Rounded', 'merchant' ),
				'square'  => esc_html__( 'Square', 'merchant' ),
			),
			'default' => 'square',
		),

		array(
			'id'      => 'checkbox_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Checkbox color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'description_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Description color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'image_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Image border color', 'merchant' ),
			'default' => 'rgba(0,0,0,0)',
		),

		array(
			'id'      => 'title_heading_size',
			'type'    => 'select',
			'title'   => esc_html__( 'Title heading', 'merchant' ),
			'options' => array(
				'h1' => esc_html__( 'H1', 'merchant' ),
				'h2' => esc_html__( 'H2', 'merchant' ),
				'h3' => esc_html__( 'H3', 'merchant' ),
				'h4' => esc_html__( 'H4', 'merchant' ),
				'h5' => esc_html__( 'H5', 'merchant' ),
				'h6' => esc_html__( 'H6', 'merchant' ),
			),
			'default' => 'h4',
		),


		array(
			'id'      => 'description_font_size',
			'type'    => 'range',
			'title'   => esc_html__( 'Description font size', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 14,
		),

		array(
			'id'      => 'title_description_alignment',
			'type'    => 'radio',
			'title'   => esc_html__( 'Title and description alignment', 'merchant' ),
			'options' => array(
				'left'   => esc_html__( 'Left', 'merchant' ),
				'center' => esc_html__( 'Center', 'merchant' ),
				'right'  => esc_html__( 'Right', 'merchant' ),
			),
			'default' => 'left',
		),

		array(
			'id'        => 'border_radius',
			'type'      => 'range',
			'title'     => esc_html__( 'Border radius', 'merchant' ),
			'min'       => 0,
			'max'       => 60,
			'step'      => 1,
			'default'   => 0,
			'unit'      => 'px',
		),

		array(
			'id'        => 'image_border_radius',
			'type'      => 'range',
			'title'     => esc_html__( 'Image border radius', 'merchant' ),
			'min'       => 0,
			'max'       => 60,
			'step'      => 1,
			'default'   => 0,
			'unit'      => 'px',
		),
	),
) );

// Shortcode
$merchant_module_id = Merchant_Complementary_Products::MODULE_ID;
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