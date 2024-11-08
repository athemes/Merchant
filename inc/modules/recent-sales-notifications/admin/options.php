<?php

/**
 * Recent Sales Notifications module.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Timing Settings', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'time_before_show_first_one',
			'type'    => 'number',
			'title'   => esc_html__( 'Seconds before the first notification is shown', 'merchant' ),
			'default' => '8',
		),
		array(
			'id'      => 'time_to_stay_on_screen',
			'type'    => 'number',
			'title'   => esc_html__( 'Time on screen (seconds)', 'merchant' ),
			'default' => '8',
		),
		array(
			'id'      => 'delay_between_notifications',
			'type'    => 'number',
			'title'   => esc_html__( 'Delay between notifications (seconds)', 'merchant' ),
			'default' => '12',
		),
		array(
			'id'      => 'notifications_per_session',
			'type'    => 'number',
			'title'   => esc_html__( 'Notifications per session', 'merchant' ),
			'desc'    => esc_html__( 'The maximum number of notifications shown to the same visitor during a browsing session.', 'merchant' ),
			'default' => '6',
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Add To Cart Settings', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'show_add_to_cart_events',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show Add To Cart events', 'merchant' ),
			'desc'    => esc_html__( 'Show notifications for products added to cart. This is especially useful if you don\'t have orders yet but you still want to show the activity on your store.',
				'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'show_grouped_add_to_cart_events',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show grouped Add To Cart events', 'merchant' ),
			'desc'    => esc_html__( 'Show events such as: "13 people added this product to cart today:”', 'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'minumum_add_to_cart_for_group_notification',
			'type'    => 'number',
			'title'   => esc_html__( 'Minimum number of add to carts for group notifications', 'merchant' ),
			'desc'    => esc_html__( 'Set the number of times a product must be added to cart in one day, for a group notification to be triggered', 'merchant' ),
			'default' => '10',
		),
		array(
			'id'          => 'group_notification_template',
			'type'        => 'text',
			'title'       => esc_html__( '"Add To Cart" group notification template', 'merchant' ),
			'desc'        => esc_html__( 'Eg.: "12 people added this product to cart today:" Do not modify the text to present fake data.', 'merchant' ),
			'placeholder' => esc_html__( '{{count}} people added this product to cart today:', 'merchant' ),
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Other Settings', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'show_customer_names',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show customer names', 'merchant' ),
			'desc'    => esc_html__( 'If you are based in the EU or you have customers from the EU, we recommend you deselect this option, for GDPR purposes.',
				'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'show_customer_location',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show the customer\'s location', 'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'open_links_in_new_tab',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Open links in new tab', 'merchant' ),
			'desc'    => esc_html__( 'When the visitor clicks on the Recent Sales widget, you can choose to open the link in a new tab or the current tab.',
				'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'hide_on_mobile',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Hide on Mobile', 'merchant' ),
			'desc'    => esc_html__( 'Because the mobile screen is much smaller, you might not want to take up space with this module.',
				'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'show_verified_badge',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show "Verified by aThemes" badge', 'merchant' ),
			'desc'    => esc_html__( 'If enabled, it will show a Verified by aThemes badge in the popup. This will create an extra sense of trust for your visitors.',
				'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'show_order_time_ago',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show when the order was placed', 'merchant' ),
			'desc'    => esc_html__( 'Eg.: "2 hours ago"', 'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'hide_date_for_old_events_than',
			'type'    => 'number',
			'title'   => esc_html__( 'Hide the date for events older than (days)', 'merchant' ),
			'desc'    => esc_html__( 'Events older than the selected period will not include the date in the notification.', 'merchant' ),
			'default' => '1',
		),
		array(
			'id'      => 'dont_show_events_older_than',
			'type'    => 'text',
			'title'   => esc_html__( 'Don\'t show events older than (days)', 'merchant' ),
			'desc'    => esc_html__( 'If no events are found in the selected period, no notifications will be displayed.', 'merchant' ),
			'default' => '60',
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Hide for some products and orders', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'product_exclusion',
			'type'    => 'switcher',
			'title'   => __( 'Product Exclusion', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'            => 'excluded_products',
			'type'          => 'products_selector',
			'title'         => esc_html__( 'Exclude products', 'merchant' ),
			'multiple'      => true,
			'allowed_types' => array( 'simple', 'variable' ),
			'conditions'    => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'product_exclusion', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
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
			'conditions'  => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'product_exclusion', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'order_exclusion',
			'type'    => 'switcher',
			'title'   => __( 'Order Exclusion', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'         => 'excluded_products',
			'type'       => 'text',
			'desc'       => esc_html__( 'Add comma separated order IDs that you don\'t want to show.', 'merchant' ),
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'order_exclusion', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
	),
) );