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
			'min'     => '0',
			'title'   => esc_html__( 'Seconds before the first notification is shown', 'merchant' ),
			'default' => '8',
		),
		array(
			'id'      => 'time_to_stay_on_screen',
			'type'    => 'number',
			'min'     => '0',
			'title'   => esc_html__( 'Time on screen (seconds)', 'merchant' ),
			'default' => '8',
		),
		array(
			'id'      => 'delay_between_notifications',
			'type'    => 'number',
			'min'     => '0',
			'title'   => esc_html__( 'Delay between notifications (seconds)', 'merchant' ),
			'default' => '12',
		),
		array(
			'id'      => 'notifications_per_session',
			'type'    => 'number',
			'min'     => '0',
			'title'   => esc_html__( 'Notifications per session', 'merchant' ),
			'desc'    => esc_html__( 'The maximum number of notifications shown to the same visitor during a browsing session.', 'merchant' ),
			'default' => '6',
		),
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Notifications control', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'             => 'product_purchases_count',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Product purchases count', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display the number of customers who have purchased a product.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'minimum_purchases',
					'type'    => 'number',
					'title'   => esc_html__( 'Minimum number of purchases required', 'merchant' ),
					'desc'    => esc_html__( 'Set the minimum number of times a product must be purchased in period, for a notification to be triggered', 'merchant' ),
					'default' => '5',
					'min'     => '1',
				),
				array(
					'id'      => 'number_of_days',
					'type'    => 'number',
					'title'   => esc_html__( 'Number of days', 'merchant' ),
					'desc'    => esc_html__( 'Search for orders within the last number of days', 'merchant' ),
					'default' => '7',
					'min'     => '1',
				),
				array(
					'id'          => 'template_singular',
					'type'        => 'text',
					'label'       => esc_html__( 'Singular Template', 'merchant' ),
					'desc'        => esc_html__( 'Singular template for displaying the number of customers who have purchased a product in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} customer bought this product in the last 7 days', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: the customers count */
						__(
							'<strong>%1$s:</strong> displays customers count',
							'merchant'
						),
						'{count}'
					),
				),

				// Plural template for displaying the number of customers who have purchased a product in a period
				array(
					'id'          => 'template_plural',
					'type'        => 'text',
					'label'       => esc_html__( 'Plural Template', 'merchant' ),
					'desc'        => esc_html__( 'Plural template for displaying the number of customers who have purchased a product in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} customers bought this product in the last 7 days', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: the customers count */
						__(
							'<strong>%1$s:</strong> displays customers count',
							'merchant'
						),
						'{count}'
					),
				),
			),
		),
		array(
			'id'             => 'single_product_purchase',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Single product purchase', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display notification when someone purchases a product', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'time_span',
					'type'    => 'number',
					'title'   => esc_html__( 'Timespan', 'merchant' ),
					'desc'    => esc_html__( 'Number of time units to display the a customers who have purchased a product.', 'merchant' ),
					'default' => '7',
				),
				array(
					'id'      => 'time_unit',
					'type'    => 'select',
					'title'   => esc_html__( 'Time unit', 'merchant' ),
					'options' => array(
						'YEAR'   => esc_html__( 'Years', 'merchant' ),
						'MONTH'  => esc_html__( 'Months', 'merchant' ),
						'WEEK'   => esc_html__( 'Weeks', 'merchant' ),
						'DAY'    => esc_html__( 'Days', 'merchant' ),
						'HOUR'   => esc_html__( 'Hours', 'merchant' ),
						'MINUTE' => esc_html__( 'Minutes', 'merchant' ),
					),
					'default' => 'DAY',
				),
				array(
					'id'      => 'hide_date_for_old_events_than',
					'type'    => 'number',
					'title'   => esc_html__( 'Hide date for old events than (days)', 'merchant' ),
					'desc'    => esc_html__( 'Events older than the selected period will not include the date in the notification.', 'merchant' ),
					'default' => '5',
				),
				array(
					'id'          => 'template_full_data',
					'type'        => 'text',
					'label'       => esc_html__( 'Full Data Template', 'merchant' ),
					'desc'        => esc_html__( 'Text template when name, country, and city are available.', 'merchant' ),
					'default'     => esc_html__( '{customer_name} in {country_code}, {city} purchased', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: {customer_name}, %2$s: {country_code}, %3$s: {city} */
						__(
							'If you are based in the EU or you have customers from the EU, we recommend you to hide customer names for GDPR purposes. <br><br><strong>%1$s:</strong> displays the customer’s name<br>
<strong>%2$s:</strong> displays the customer’s country<br>
<strong>%3$s:</strong> displays the customer’s city',
							'merchant'
						),
						'{customer_name}',
						'{country_code}',
						'{city}'
					),
				),

				// Text template when only the name is available
				array(
					'id'          => 'template_name_only',
					'type'        => 'text',
					'label'       => esc_html__( 'Name Only Template', 'merchant' ),
					'desc'        => esc_html__( 'Text template when only name is available', 'merchant' ),
					'default'     => esc_html__( '{customer_name} purchased', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: {customer_name} */
						__(
							'If you are based in the EU or you have customers from the EU, we recommend you to hide customer names for GDPR purposes. <br><br><strong>%1$s:</strong> displays the customer’s name',
							'merchant'
						),
						'{customer_name}'
					),
				),

				// Fallback text template when no specific data is available
				array(
					'id'      => 'template_no_data',
					'type'    => 'text',
					'label'   => esc_html__( 'Fallback Template', 'merchant' ),
					'desc'    => esc_html__( 'Text template when no customer data is available.', 'merchant' ),
					'default' => esc_html__( 'Someone purchased', 'merchant' ),
				),
			),
		),
		array(
			'id'             => 'product_carts_count',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Product grouped add to cart', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display the number of customers who have added a product to cart.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'minimum_count',
					'type'    => 'number',
					'title'   => esc_html__( 'Minimum number of add to carts required', 'merchant' ),
					'desc'    => esc_html__( 'Set the minimum number of times a product must be added to cart in period, for a notification to be triggered', 'merchant' ),
					'default' => '2',
					'min'     => '1',
				),
				array(
					'id'      => 'number_of_days',
					'type'    => 'number',
					'title'   => esc_html__( 'Number of days', 'merchant' ),
					'desc'    => esc_html__( 'Search for add to cart events within the last number of days', 'merchant' ),
					'default' => '1',
					'min'     => '1',
				),
				array(
					'id'          => 'template_singular',
					'type'        => 'text',
					'label'       => esc_html__( 'Singular Template', 'merchant' ),
					'desc'        => esc_html__( 'Singular text for displaying the number of customers who have added a product in their cart in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} people added this product to cart today', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: the customers count */
						__(
							'<strong>%1$s:</strong> displays customers count',
							'merchant'
						),
						'{count}'
					),
				),

				// Plural template for displaying the number of customers who have purchased a product in a period
				array(
					'id'          => 'template_plural',
					'type'        => 'text',
					'label'       => esc_html__( 'Plural Template', 'merchant' ),
					'desc'        => esc_html__( 'Plural text for displaying the number of customers who have added a product in their cart in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} people added this product to cart today', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: the customers count */
						__(
							'<strong>%1$s:</strong> displays customers count',
							'merchant'
						),
						'{count}'
					),
				),
			),
		),
		array(
			'id'             => 'single_product_add_to_cart',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Single product add to cart', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display notification when someone adds a product to cart', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'time_span',
					'type'    => 'number',
					'title'   => esc_html__( 'Timespan', 'merchant' ),
					'desc'    => esc_html__( 'Number of time units to display the a customers who have add a product to cart.', 'merchant' ),
					'default' => '2',
				),
				array(
					'id'      => 'time_unit',
					'type'    => 'select',
					'title'   => esc_html__( 'Time unit', 'merchant' ),
					'options' => array(
						'YEAR'   => esc_html__( 'Years', 'merchant' ),
						'MONTH'  => esc_html__( 'Months', 'merchant' ),
						'WEEK'   => esc_html__( 'Weeks', 'merchant' ),
						'DAY'    => esc_html__( 'Days', 'merchant' ),
						'HOUR'   => esc_html__( 'Hours', 'merchant' ),
						'MINUTE' => esc_html__( 'Minutes', 'merchant' ),
					),
					'default' => 'HOUR',
				),
				array(
					'id'      => 'hide_date_for_old_events_than',
					'type'    => 'number',
					'title'   => esc_html__( 'Hide date for old events than (days)', 'merchant' ),
					'desc'    => esc_html__( 'Events older than the selected period will not include the date in the notification.', 'merchant' ),
					'default' => '5',
				),
				array(
					'id'          => 'template_full_data',
					'type'        => 'text',
					'label'       => esc_html__( 'Full Data Template', 'merchant' ),
					'desc'        => esc_html__( 'Text template when name, country, and city are available.', 'merchant' ),
					'default'     => esc_html__( '{customer_name} in {country_code}, {city} added to cart', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: {customer_name}, %2$s: {country_code}, %3$s: {city} */
						__(
							'If you are based in the EU or you have customers from the EU, we recommend you to hide customer names for GDPR purposes. <br><br><strong>%1$s:</strong> displays the customer’s name<br>
<strong>%2$s:</strong> displays the customer’s country<br>
<strong>%3$s:</strong> displays the customer’s city',
							'merchant'
						),
						'{customer_name}',
						'{country_code}',
						'{city}'
					),
				),

				// Text template when only the name is available
				array(
					'id'          => 'template_name_only',
					'type'        => 'text',
					'label'       => esc_html__( 'Name Only Template', 'merchant' ),
					'desc'        => esc_html__( 'Text template when only name is available', 'merchant' ),
					'default'     => esc_html__( '{customer_name} added to cart', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: {customer_name} */
						__(
							'If you are based in the EU or you have customers from the EU, we recommend you to hide customer names for GDPR purposes. <br><br><strong>%1$s:</strong> displays the customer’s name',
							'merchant'
						),
						'{customer_name}'
					),
				),

				// Fallback text template when no specific data is available
				array(
					'id'      => 'template_no_data',
					'type'    => 'text',
					'label'   => esc_html__( 'Fallback Template', 'merchant' ),
					'desc'    => esc_html__( 'Text template when no customer data is available.', 'merchant' ),
					'default' => esc_html__( 'Someone added to cart', 'merchant' ),
				),
			),
		),
		array(
			'id'             => 'product_views_settings',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Product views', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display the number of times a product has been viewed in a period.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'time_span',
					'type'    => 'number',
					'title'   => esc_html__( 'Timespan', 'merchant' ),
					'desc'    => esc_html__( 'Number of time units to display the a customers who have viewed a product.', 'merchant' ),
					'default' => '7',
				),
				array(
					'id'      => 'time_unit',
					'type'    => 'select',
					'title'   => esc_html__( 'Time unit', 'merchant' ),
					'options' => array(
						'YEAR'   => esc_html__( 'Years', 'merchant' ),
						'MONTH'  => esc_html__( 'Months', 'merchant' ),
						'WEEK'   => esc_html__( 'Weeks', 'merchant' ),
						'DAY'    => esc_html__( 'Days', 'merchant' ),
						'HOUR'   => esc_html__( 'Hours', 'merchant' ),
						'MINUTE' => esc_html__( 'Minutes', 'merchant' ),
					),
					'default' => 'DAY',
				),
				array(
					'id'          => 'template_singular',
					'type'        => 'text',
					'label'       => esc_html__( 'Singular Template', 'merchant' ),
					'desc'        => esc_html__( 'Singular template for displaying the number of people who have viewed a product in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} people viewed today', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: the customers count */
						__(
							'<strong>%1$s:</strong> displays customers count',
							'merchant'
						),
						'{count}'
					),
				),

				// Plural template for displaying the number of customers who have purchased a product in a period
				array(
					'id'          => 'template_plural',
					'type'        => 'text',
					'label'       => esc_html__( 'Plural Template', 'merchant' ),
					'desc'        => esc_html__( 'Plural template for displaying the number of people who have viewed a product in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} people viewed today', 'merchant' ),
					'hidden_desc' => sprintf(
					/* Translators: %1$s: the customers count */
						__(
							'<strong>%1$s:</strong> displays customers count',
							'merchant'
						),
						'{count}'
					),
				),
			),
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
			'id'         => 'excluded_categories',
			'type'       => 'select_ajax',
			'title'      => esc_html__( 'Exclude Categories', 'merchant' ),
			'source'     => 'options',
			'multiple'   => true,
			'options'    => Merchant_Admin_Options::get_category_select2_choices(),
			'conditions' => array(
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
			'id'         => 'excluded_order_ids',
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

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'hide_on_mobile',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Hide on Mobile', 'merchant' ),
			'desc'    => esc_html__( 'Because the mobile screen is much smaller, you might not want to take up space with this module.', 'merchant' ),
			'default' => false,
		),
		array(
			'id'      => 'hide_on_desktop',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Hide on Desktop', 'merchant' ),
			'desc'    => esc_html__( 'If you want to disable the Recent Sales Notifications on desktop, you can do it here.', 'merchant' ),
			'default' => false,
		),
		array(
			'id'      => 'shuffle_notifications',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Shuffle Notifications', 'merchant' ),
			'desc'    => esc_html__( 'If you want to shuffle the notifications, you can do it here.', 'merchant' ),
			'default' => true,
		),
		array(
			'id'      => 'layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Layout', 'merchant' ),
			'options' => array(
				'layout-1' => esc_html__( 'Layout 1', 'merchant' ),
				'layout-2' => esc_html__( 'Layout 2', 'merchant' ),
				'layout-3' => esc_html__( 'Layout 3', 'merchant' ),
				'layout-4' => esc_html__( 'Layout 4', 'merchant' ),
				'layout-5' => esc_html__( 'Layout 5', 'merchant' ),
				'layout-6' => esc_html__( 'Layout 6', 'merchant' ),
				'layout-7' => esc_html__( 'Layout 7', 'merchant' ),
			),
			'default' => 'top-right',
			'desc'    => esc_html__( 'Choose the layout of the notification box.', 'merchant' ),
		),
		array(
			'id'      => 'slide_from',
			'type'    => 'select',
			'title'   => esc_html__( 'Slide from', 'merchant' ),
			'options' => array(
				'top-right'    => esc_html__( 'Top Right', 'merchant' ),
				'top-left'     => esc_html__( 'Top Left', 'merchant' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'merchant' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'merchant' ),
			),
			'default' => 'top-right',
			'desc'    => esc_html__( 'The direction from which the notification box will slide from.', 'merchant' ),
		),
		array(
			'id'      => 'notification_box_width',
			'type'    => 'range',
			'min'     => '250',
			'max'     => '1000',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '420',
			'title'   => esc_html__( 'Notification box width', 'merchant' ),
			'desc'    => esc_html__( 'The width of the notification box.', 'merchant' ),
		),
		array(
			'id'      => 'notification_box_radius',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '0',
			'title'   => esc_html__( 'Notification corner radius', 'merchant' ),
			'desc'    => esc_html__( 'The corner radius of the notification box.', 'merchant' ),
		),
		array(
			'id'      => 'product_image_radius',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '0',
			'title'   => esc_html__( 'Product image radius', 'merchant' ),
			'desc'    => esc_html__( 'The corner radius of the product image.', 'merchant' ),
		),
		array(
			'id'        => 'background_image',
			'type'      => 'upload',
			'drag_drop' => true,
			'title'     => esc_html__( 'Upload custom background image', 'merchant' ),
			'label'     => esc_html__( 'Click to upload or drag and drop', 'merchant' ),
			'desc'      => esc_html__( 'The selected image will automatically adjust to fit and center within the notification box. An ideal size is 420px by 110px.', 'merchant' ),
		),
		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#ffffff',
			'desc'    => esc_html__( 'The background color of the notification box.', 'merchant' ),
		),
		array(
			'id'      => 'close_btn_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Close button color', 'merchant' ),
			'default' => '#000',
			'desc'    => esc_html__( 'The color of the close button.', 'merchant' ),
		),
		array(
			'id'      => 'close_btn_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Close button background color', 'merchant' ),
			'default' => '#f3f3f3',
			'desc'    => esc_html__( 'The background color of the close button.', 'merchant' ),
		),
		array(
			'id'      => 'border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => 'rgba(255, 255, 255, 0)',
			'desc'    => esc_html__( 'The color of the border.', 'merchant' ),
		),
	),
) );