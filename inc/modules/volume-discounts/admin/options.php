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
			'sorting'      => true,
			'accordion'    => true,
			'duplicate'    => true,
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
							'title'   => esc_html__( 'Offered product(s)', 'merchant' ),
							'options' => array(
								'all'        => esc_html__( 'All Products', 'merchant' ),
								'products'   => esc_html__( 'Specific Products', 'merchant' ),
								'categories' => esc_html__( 'Specific Categories', 'merchant' ),
								'tags'       => esc_html__( 'Specific Tags', 'merchant' ),
							),
							'default' => 'products',
						),
						array(
							'id'      => 'include_all_cart_products',
							'type'    => 'switcher',
							'title'   => esc_html__( 'Include all cart products', 'merchant' ),
							'desc'    => esc_html__( 'Apply the discount based on the total quantity of products added to the cart, rather than requiring a specific quantity of each individual product.',   'merchant' ),
							'default' => false,
							'condition'   => array( 'rules_to_display', '==', 'all' ),
						),
						array(
							'id'            => 'product_id',
							'type'          => 'products_selector',
							//'title'     => esc_html__( 'Select product', 'merchant' ),
							'multiple'      => true,
							'desc'          => esc_html__( 'Select the product that the customer will get a discount on when they purchase the minimum required quantity.',
								'merchant' ),
							'allowed_types' => array( 'simple', 'variable' ),
							'condition'     => array( 'rules_to_display', '==', 'products' ),
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
							'id'      => 'include_all_category_products',
							'type'    => 'switcher',
							'title'   => esc_html__( 'Include all category products', 'merchant' ),
							'desc'    => esc_html__( 'Apply the discount based on the total quantity of products within the same category added to the cart, rather than requiring a specific quantity of each individual product.', 'merchant' ),
							'default' => false,
							'condition'   => array( 'rules_to_display', '==', 'categories' ),
						),
						array(
							'id'      => 'include_all_products_info',
							'type'    => 'info',
							'content'    => esc_html__( 'When configuring offer priorities, consider the order in which they are applied. Typically, more general offers should be prioritized higher to ensure consistent discount application. You can control the priority by sorting the offers—offers listed at the top have the highest priority. However, note that this setup will not work on the thank you page.',
								'merchant' ),
							'conditions' => array(
								'relation' => 'OR', // AND/OR, If not provided, only first term will be considered
								'terms'    => array(
									array(
										'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
										'terms'    => array(
											array(
												'field'    => 'rules_to_display', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => 'categories', // can be a single value or an array of string/number/int
											),
											array(
												'field'    => 'include_all_category_products', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => true, // can be a single value or an array of string/number/int
											),
										),
									),
									array(
										'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
										'terms'    => array(
											array(
												'field'    => 'rules_to_display', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => 'all', // can be a single value or an array of string/number/int
											),
											array(
												'field'    => 'include_all_cart_products', // field ID
												'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
												'value'    => true, // can be a single value or an array of string/number/int
											),
										),
									),
								),
							),
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
							'id'      => 'quantity',
							'type'    => 'number',
							'title'   => esc_html__( 'Quantity', 'merchant' ),
							'min'     => 1,
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
							'default' => 'percentage_discount',
						),
						array(
							'id'      => 'discount',
							'type'    => 'number',
							'step'    => 0.01,
							'default' => 10,
						),

						array(
							'id'      => 'discount_target',
							'type'    => 'select',
							'title'   => esc_html__( 'Apply discount to', 'merchant' ),
							'options' => array(
								'regular' => esc_html__( 'Regular Price', 'merchant' ),
								'sale'    => esc_html__( 'Sale Price', 'merchant' ),
							),
							'default' => 'sale',
						),

						array(
							'id'      => 'exclude_coupon',
							'type'    => 'switcher',
							'title'   => esc_html__( 'Exclude coupons', 'merchant' ),
							'desc'    => esc_html__( 'Coupon codes will not be applicable on top of this offer campaign.', 'merchant' ),
							'default' => false,
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
							'id'         => 'exclude_roles',
							'type'       => 'select_ajax',
							'title'      => esc_html__( 'Exclude Roles', 'merchant' ),
							'desc'       => esc_html__( 'This will exclude the offer for users with these roles.', 'merchant' ),
							'source'     => 'options',
							'multiple'   => true,
							'classes'    => array( 'flex-grow' ),
							'options'    => Merchant_Admin_Options::get_user_roles_select2_choices(),
							'conditions' => array(
								'relation' => 'AND',
								'terms'    => array(
									array(
										'field'    => 'user_condition',
										'operator' => 'in',
										'value'    => array( 'all' ),
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
							'accordion'      => true,
							'display_status' => true,
							'default'        => 'active',
							'fields'         => array(
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

								// `hidden_desc` depends on `desc`
								array(
									'id'          => 'save_label',
									'type'        => 'text',
									'title'       => esc_html__( 'Save label', 'merchant' ),
									'default'     => esc_html__( 'Save {amount}', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount, %2$s: Discount percentage */
										__( '<strong>%1$s:</strong> to show discount amount<br><strong>%2$s:</strong> to show discount percentage', 'merchant' ),
										'{amount}',
										'{percent}'
									),
								),

								array(
									'id'          => 'buy_text',
									'type'        => 'text',
									'title'       => esc_html__( 'Tier format text', 'merchant' ),
									'default'     => esc_html__( 'Buy {quantity}, get {discount} off each', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount percentage, %2$s: Quantity, %3$s: Discount amount */
										__( '<strong>%1$s:</strong> to show discount percentage<br><strong>%2$s:</strong> to show the number of items needed to buy to get the discount<br><strong>%3$s:</strong> to show discount amount on each item',
											'merchant' ),
										'{percent}',
										'{quantity}',
										'{discount}'
									),
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
									'id'          => 'cart_description_text',
									'type'        => 'text',
									'title'       => esc_html__( 'Cart item discount description', 'merchant' ),
									'default'     => esc_html__( 'A discount of {amount} has been applied.', 'merchant' ),
									'desc'        => __( 'This is displayed on the cart page. You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount, %2$s: Discount percentage */
										__( '<strong>%1$s:</strong> to show discount amount<br><strong>%2$s:</strong> to show discount percentage', 'merchant' ),
										'{amount}',
										'{percent}'
									),
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
									'id'      => 'title_text_color_hover',
									'type'    => 'color',
									'title'   => esc_html__( 'Title text color hover', 'merchant' ),
									'default' => '#212121',
								),

								array(
									'id'      => 'table_item_bg_color',
									'type'    => 'color',
									'title'   => esc_html__( 'Background color', 'merchant' ),
									'default' => '#fcf0f1',
								),

								array(
									'id'      => 'table_item_bg_color_hover',
									'type'    => 'color',
									'title'   => esc_html__( 'Background color hover', 'merchant' ),
									'default' => '#fcf0f1',
								),

								array(
									'id'      => 'table_item_border_color',
									'type'    => 'color',
									'title'   => esc_html__( 'Border color', 'merchant' ),
									'default' => '#d83b3b',
								),

								array(
									'id'      => 'table_item_border_color_hover',
									'type'    => 'color',
									'title'   => esc_html__( 'Border color hover', 'merchant' ),
									'default' => '#d83b3b',
								),

								array(
									'id'      => 'table_item_text_color',
									'type'    => 'color',
									'title'   => esc_html__( 'Text color', 'merchant' ),
									'default' => '#3c434a',
								),

								array(
									'id'      => 'table_item_text_color_hover',
									'type'    => 'color',
									'title'   => esc_html__( 'Text color hover', 'merchant' ),
									'default' => '#3c434a',
								),

								array(
									'id'      => 'table_label_bg_color',
									'type'    => 'color',
									'title'   => esc_html__( 'Label background color', 'merchant' ),
									'default' => '#d83b3b',
								),

								array(
									'id'      => 'table_label_text_color',
									'type'    => 'color',
									'title'   => esc_html__( 'Label text color', 'merchant' ),
									'default' => '#ffffff',
								),
							),
						),
						array(
							'id'             => 'cart_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Cart Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how bulk discount offers appear on the cart page.', 'merchant' ),
							'state'          => 'closed',
							'accordion'      => true,
							'display_status' => true,
							'default'        => 'inactive',
							'fields'         => array(
								// Text Formatting Settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'Add {quantity} more to get a {discount} discount off each', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: offer quantity, %2$s: discount amount */
										__(
											'<strong>%1$s:</strong> to show offer quantity<br><strong>%2$s:</strong> to show discount amount',
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
							),
						),
						array(
							'id'             => 'checkout_page',
							'type'           => 'fields_group',
							'title'          => esc_html__( 'Checkout Page', 'merchant' ),
							'sub-desc'       => esc_html__( 'Use these settings to control how bulk discount offers appear on the checkout page.', 'merchant' ),
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
									'default' => 'before_billing_details',
								),
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'Add {quantity} more to get {discount} off', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: offer quantity, %2$s: discount amount */
										__(
											'<strong>%1$s:</strong> to show offer quantity<br><strong>%2$s:</strong> to show discount amount',
											'merchant'
										),
										'{quantity}',
										'{discount}'
									),
								),
								array(
									'id'      => 'discount_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Discount text', 'merchant' ),
									'default' => esc_html__( '{product_price}', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: product price */
										__(
											'<strong>%1$s:</strong> to show product price<br><strong>%2$s:</strong> to show offer discount amount',
											'merchant'
										),
										'{product_price}',
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
							'sub-desc'       => esc_html__( 'Use these settings to control how bulk discount offers appear on the thank you page.', 'merchant' ),
							'state'          => 'closed',
							'accordion'      => true,
							'display_status' => true,
							'default'        => 'inactive',
							'fields'         => array(
								// Text Formatting Settings
								array(
									'id'      => 'title',
									'type'    => 'text',
									'title'   => esc_html__( 'Offer title', 'merchant' ),
									'default' => esc_html__( 'Add {quantity} more to get {discount} off', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: quantity, %2$s: post purchase discount, %3$s: In Stock, %4$s: Total quantity */
										__(
											'<strong>%1$s:</strong> to show product quantity<br><strong>%2$s:</strong> to show the discount amount',
											'merchant'
										),
										'{quantity}',
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
									'default' => esc_html__( '{product_price}', 'merchant' ),
									'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
									'hidden_desc' => sprintf(
									/* Translators: %1$s: Discount amount, %2$s: product price */
										__(
											'<strong>%1$s:</strong> to show the discount amount<br><strong>%2$s:</strong> to show the product price before and after the offer discount',
											'merchant'
										),
										'{discount}',
										'{product_price}'
									),
								),

								array(
									'id'      => 'bonus_tip_text',
									'type'    => 'text',
									'title'   => esc_html__( 'Bonus tip text', 'merchant' ),
									'default' => esc_html__( 'Bonus: You will also receive this discount off each item you just purchased as part of this bulk discount offer.', 'merchant' ),
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
					'layout'        => 'offer-details',
					'min_quantity'  => 2,
					'discount'      => 10,
					'discount_type' => 'fixed_discount',
				),
			),
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'General', 'merchant' ),
	'module' => Merchant_Volume_Discounts::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'max_checkout_offers',
			'type'    => 'number',
			'title'   => __( 'Max offers on checkout page', 'merchant' ),
			'desc'    => __( 'Set the maximum offers can be displayed on the checkout page.', 'merchant' ),
			'default' => 3,
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
