<?php
/**
 * Pre Orders.
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
do_action( 'merchant_admin_before_include_modules_options', Merchant_Pre_Orders::MODULE_ID );

Merchant_Admin_Options::create(
	array(
		'title'  => esc_html__( 'Pre-order Rule', 'merchant' ),
		'module' => Merchant_Pre_Orders::MODULE_ID,
		'fields' => array(
			array(
				'id'           => 'rules',
				'type'         => 'flexible_content',
				'sorting'      => true,
				'duplicate'    => true,
				'accordion'    => true,
				'style'        => Merchant_Pre_Orders::MODULE_ID . '-style default',
				'button_label' => esc_html__( 'Add New Pre-Order', 'merchant' ),
				'layouts'      => array(
					'rule-details' => array(
						'title'       => esc_html__( 'Campaign', 'merchant' ),
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
								'title'   => esc_html__( 'Order name', 'merchant' ),
								'default' => esc_html__( 'Custom Pre-order', 'merchant' ),
								'desc'    => esc_html__( 'Internal campaign name. This is not visible to customers.', 'merchant' ),
							),
							array(
								'id'      => 'trigger_on',
								'type'    => 'select',
								'title'   => esc_html__( 'Trigger', 'merchant' ),
								'options' => array(
									'all'      => esc_html__( 'All Products', 'merchant' ),
									'product'  => esc_html__( 'Specific Products', 'merchant' ),
									'category' => esc_html__( 'Specific Categories', 'merchant' ),
									'tags'     => esc_html__( 'Specific Tags', 'merchant' ),
								),
								'default' => 'product',
							),
							array(
								'id'            => 'product_ids',
								'type'          => 'products_selector',
								'multiple'      => true,
								'allowed_types' => array( 'simple', 'variable', 'variation', 'merchant_pro_bundle' ),
								'desc'          => esc_html__( 'Select the product(s) included in this pre-order.', 'merchant' ),
								'condition'     => array( 'trigger_on', '==', 'product' ),
							),
							array(
								'id'          => 'category_slugs',
								'type'        => 'select_ajax',
								'title'       => esc_html__( 'Categories', 'merchant' ),
								'source'      => 'options',
								'multiple'    => true,
								'options'     => Merchant_Admin_Options::get_category_select2_choices(),
								'placeholder' => esc_html__( 'Select categories', 'merchant' ),
								'desc'        => esc_html__( 'Select the category or categories for which the products will be available for pre-order.', 'merchant' ),
								'condition'   => array( 'trigger_on', '==', 'category' ),
							),
							array(
								'id'          => 'tag_slugs',
								'type'        => 'select_ajax',
								'title'       => esc_html__( 'Tags', 'merchant' ),
								'source'      => 'options',
								'multiple'    => true,
								'options'     => Merchant_Admin_Options::get_tag_select2_choices(),
								'placeholder' => esc_html__( 'Select tags', 'merchant' ),
								'desc'        => esc_html__( 'Select the tag or tags for which the products will be available for pre-order.', 'merchant' ),
								'condition'   => array( 'trigger_on', '==', 'tags' ),
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
											'field'    => 'trigger_on',
											'operator' => 'in',
											'value'    => array( 'all', 'category', 'tags' ),
										),
									),
								),
							),

							array(
								'id'            => 'excluded_products',
								'type'          => 'products_selector',
								'title'         => esc_html__( 'Exclude products', 'merchant' ),
								'desc'          => esc_html__( 'Exclude products from this discount campaign.', 'merchant' ),
								'allowed_types' => array( 'simple', 'variable' ),
								'multiple'      => true,
								'conditions'    => array(
									'relation' => 'AND',
									'terms'    => array(
										array(
											'field'    => 'trigger_on',
											'operator' => 'in',
											'value'    => array( 'all', 'category', 'tags' ),
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
											'field'    => 'trigger_on',
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
											'field'    => 'trigger_on',
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
								'id'      => 'discount_toggle',
								'type'    => 'switcher',
								'title'   => __( 'Do you want to offer a discount on this pre-order?', 'merchant' ),
								'default' => 0,
							),
							array(
								'id'         => 'discount_type',
								'type'       => 'radio',
								'title'      => esc_html__( 'Discount Type', 'merchant' ),
								'options'    => array(
									'percentage' => esc_html__( 'Percentage', 'merchant' ),
									'fixed'      => esc_html__( 'Fixed', 'merchant' ),
								),
								'default'    => 'percentage',
								'conditions' => array(
									'terms' => array(
										array(
											'field'    => 'discount_toggle', // field ID
											'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
											'value'    => true, // can be a single value or an array of string/number/int
										),
									),
								),
							),
							array(
								'id'         => 'discount_amount',
								'type'       => 'number',
								'step'       => 0.01,
								'default'    => 10,
								'conditions' => array(
									'terms' => array(
										array(
											'field'    => 'discount_toggle', // field ID
											'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
											'value'    => true, // can be a single value or an array of string/number/int
										),
									),
								),
							),
							array(
								'id'          => 'shipping_date',
								'type'        => 'date_time',
								'title'       => esc_html__( 'Shipping date', 'merchant' ),
								'placeholder' => esc_html__( 'mm/dd/yy, --:-- --', 'merchant' ),
								'desc'        => sprintf(
								/* Translators: %1$s: Time zone, %2$s WordPress setting link */
									esc_html__( 'The times set above are in the %1$s timezone, according to your settings from %2$s.',
										'merchant' ),
									'<strong>' . wp_timezone_string() . '</strong>',
									'<a href="' . esc_url( admin_url( 'options-general.php' ) ) . '" target="_blank">' . esc_html__( 'WordPress Settings', 'merchant' ) . '</a>'
								),
							),
							array(
								'id'          => 'pre_order_start',
								'type'        => 'date_time',
								'title'       => esc_html__( 'Pre-order starts at', 'merchant' ),
								'placeholder' => esc_html__( 'mm/dd/yy, --:-- --', 'merchant' ),
								'desc'        => esc_html__( 'If you want your pre-order settings to take effect immediately, leave the pre-order start empty.', 'merchant' ),
							),
							array(
								'id'          => 'pre_order_end',
								'type'        => 'date_time',
								'title'       => esc_html__( 'Pre-order ends at', 'merchant' ),
								'placeholder' => esc_html__( 'mm/dd/yy, --:-- --', 'merchant' ),
								'desc'        => sprintf(
								/* Translators: %1$s: Time zone, %2$s WordPress setting link */
									esc_html__( 'Leave it empty if you don’t want to have an end date. The times set above are in the %1$s timezone, according to your settings from %2$s.',
										'merchant' ),
									'<strong>' . wp_timezone_string() . '</strong>',
									'<a href="' . esc_url( admin_url( 'options-general.php' ) ) . '" target="_blank">' . esc_html__( 'WordPress Settings', 'merchant' ) . '</a>'
								),
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
								'desc'      => esc_html__( 'This will limit the rule to users with these roles.', 'merchant' ),
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
								'desc'      => esc_html__( 'This will limit the rule to the selected customers.', 'merchant' ),
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
								'id'      => 'button_text',
								'type'    => 'text',
								'title'   => esc_html__( 'Button text', 'merchant' ),
								'default' => esc_html__( 'Pre-Order', 'merchant' ),
							),

							array(
								'id'      => 'additional_text',
								'type'    => 'text',
								'title'   => esc_html__( 'Additional information', 'merchant' ),
								'default' => esc_html__( 'Ships on {date}', 'merchant' ),
							),

							array(
								'id'      => 'placement',
								'type'    => 'radio',
								'title'   => esc_html__( 'Placement', 'merchant' ),
								'options' => array(
									'before' => esc_html__( 'Before Button', 'merchant' ),
									'after'  => esc_html__( 'After Button', 'merchant' ),
								),
								'default' => 'before',
							),
							array(
								'id'      => 'cart_label_text',
								'type'    => 'text',
								'title'   => esc_html__( 'Label text on cart', 'merchant' ),
								'default' => esc_html__( 'Ships on', 'merchant' ),
							),
							array(
								'id'      => 'text-color',
								'type'    => 'color',
								'title'   => esc_html__( 'Button text color', 'merchant' ),
								'default' => '#FFF',
							),

							array(
								'id'      => 'text-hover-color',
								'type'    => 'color',
								'title'   => esc_html__( 'Button text color hover', 'merchant' ),
								'default' => '#FFF',
							),

							array(
								'id'      => 'border-color',
								'type'    => 'color',
								'title'   => esc_html__( 'Button border color', 'merchant' ),
								'default' => '#212121',
							),

							array(
								'id'      => 'border-hover-color',
								'type'    => 'color',
								'title'   => esc_html__( 'Button border color hover', 'merchant' ),
								'default' => '#414141',
							),

							array(
								'id'      => 'background-color',
								'type'    => 'color',
								'title'   => esc_html__( 'Button background color', 'merchant' ),
								'default' => '#212121',
							),

							array(
								'id'      => 'background-hover-color',
								'type'    => 'color',
								'title'   => esc_html__( 'Button background color hover', 'merchant' ),
								'default' => '#414141',
							),
						),
					),
				),
				'default'      => array(
					array(
						'layout' => 'rule-details',
					),
				),
			),
			array(
				'id'      => 'modes',
				'type'    => 'select',
				'title'   => esc_html__( 'Pre-order Modes', 'merchant' ),
				'options' => array(
					'only_pre_orders'                => esc_html__( 'Allow only pre-orders', 'merchant' ),
					'unified_order'                  => esc_html__( 'Treat the whole order as pre-order', 'merchant' ),
//                  'separate_order_for_pre_orders'  => esc_html__( 'Generate separate orders for each pre-order product', 'merchant' ),
//                  'group_pre_order_into_one_order' => esc_html__( 'Generate two separate orders, one for pre-orders and one for in-stock products', 'merchant' ),
				),
				'default' => 'unified_order',
			),
			array(
				'id'          => 'helping_instructions_only_pre_orders',
				'type'        => 'info_block',
				'description' => esc_html__( 'Use this mode if you want to only allow your customers to either choose pre-order products or available ones.', 'merchant' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'field'    => 'modes', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'only_pre_orders', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'          => 'helping_instructions_unified_order',
				'type'        => 'info_block',
				'description' => esc_html__( 'Use this mode if you want to treat the whole order as a pre-order if at least one product is a pre-order.', 'merchant' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'field'    => 'modes', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'unified_order', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'          => 'helping_instructions_separate_order_for_pre_orders',
				'type'        => 'info_block',
				'description' => esc_html__( 'Use this mode if you want to generate separate orders for each pre-order product.', 'merchant' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'field'    => 'modes', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'separate_order_for_pre_orders', // can be a single value or an array of string/number/int
						),
					),
				),
			),
			array(
				'id'          => 'helping_instructions_group_pre_order_into_one_order',
				'type'        => 'info_block',
				'description' => esc_html__( 'Use this mode if you want to generate two separate orders, one for pre-orders and one for in-stock products.', 'merchant' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'field'    => 'modes', // field ID
							'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
							'value'    => 'group_pre_order_into_one_order', // can be a single value or an array of string/number/int
						),
					),
				),
			),
		),
	)
);