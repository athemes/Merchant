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
	'title'  => esc_html__( 'Notifications Control', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'             => 'single_product_purchase',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Recent Purchases', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display notifications of recent orders, optionally including customer details such as name and location.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'time_span',
					'type'    => 'number',
					'title'   => esc_html__( 'Time period', 'merchant' ),
					'desc'    => esc_html__( 'Search for orders within the last number of weeks, days or hours.',
						'merchant' ),
					'default' => '7',
					'min'     => '1',
				),
				array(
					'id'      => 'time_unit',
					'type'    => 'select',
					'title'   => esc_html__( 'Time unit', 'merchant' ),
					'options' => array(
						'WEEK'   => esc_html__( 'Weeks', 'merchant' ),
						'DAY'    => esc_html__( 'Days', 'merchant' ),
						'HOUR'   => esc_html__( 'Hours', 'merchant' ),
					),
					'default' => 'DAY',
				),
				array(
					'id'      => 'hide_date_for_old_events_than',
					'type'    => 'number',
					'title'   => esc_html__( 'Hide date for events older than (days)', 'merchant' ),
					'desc'    => esc_html__( 'For events that occurred before the specified number of days, the notification will not display the date.', 'merchant' ),
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
			'id'             => 'product_purchases_count',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Sales Count', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display the number of customers who purchased a specific product within a specified time period.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'minimum_purchases',
					'type'    => 'number',
					'title'   => esc_html__( 'Minimum number of purchases required', 'merchant' ),
					'desc'    => esc_html__( 'Set the minimum number of times a product must have been purchased in a period for a notification to be triggered.', 'merchant' ),
					'default' => '5',
					'min'     => '1',
				),
				array(
					'id'      => 'number_of_days',
					'type'    => 'number',
					'title'   => esc_html__( 'Time period (days)', 'merchant' ),
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
							'<strong>%1$s:</strong> displays number of customers who have purchased a product in specified period',
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
			'id'             => 'product_carts_count',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Cart Summary', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display the number of shoppers who added a specific product to their cart within a specified time period.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'minimum_count',
					'type'    => 'number',
					'title'   => esc_html__( 'Minimum number of add to carts required', 'merchant' ),
					'desc'    => esc_html__( 'Set the minimum number of times a product must be added to cart in a period for a notification to be triggered', 'merchant' ),
					'default' => '2',
					'min'     => '1',
				),
				array(
					'id'      => 'number_of_days',
					'type'    => 'number',
					'title'   => esc_html__( 'Time period (days)', 'merchant' ),
					'desc'    => esc_html__( 'Search for add to cart events within the last number of days', 'merchant' ),
					'default' => '1',
					'min'     => '1',
				),
				array(
					'id'          => 'template_singular',
					'type'        => 'text',
					'label'       => esc_html__( 'Singular Template', 'merchant' ),
					'desc'        => esc_html__( 'Singular text for displaying the number of customers who have added a product in their cart in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} person added this product to their cart today', 'merchant' ),
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
			'title'          => esc_html__( 'Individual Cart Adds', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display real-time notifications when shoppers add products to their cart, optionally including their name and location.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'time_span',
					'type'    => 'number',
					'title'   => esc_html__( 'Time period', 'merchant' ),
					'desc'    => esc_html__( 'Search for add to carts within the last number of weeks, days or hours.',
						'merchant' ),
					'default' => '2',
					'min'     => '1',
				),
				array(
					'id'      => 'time_unit',
					'type'    => 'select',
					'title'   => esc_html__( 'Time unit', 'merchant' ),
					'options' => array(
						'WEEK'   => esc_html__( 'Weeks', 'merchant' ),
						'DAY'    => esc_html__( 'Days', 'merchant' ),
						'HOUR'   => esc_html__( 'Hours', 'merchant' ),
					),
					'default' => 'HOUR',
				),
				array(
					'id'      => 'hide_date_for_old_events_than',
					'type'    => 'number',
					'title'   => esc_html__( 'Hide date for events older than (days)', 'merchant' ),
					'desc'    => esc_html__( 'For events that occurred before the specified number of days, the notification will not display the date.', 'merchant' ),
					'default' => '5',
				),
				array(
					'id'          => 'template_full_data',
					'type'        => 'text',
					'label'       => esc_html__( 'Full Data Template', 'merchant' ),
					'desc'        => esc_html__( 'Text template when name, country, and city are available.', 'merchant' ),
					'default'     => esc_html__( '{customer_name} in {country_code}, {city} added this to their cart', 'merchant' ),
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
					'default'     => esc_html__( '{customer_name} added this to their cart', 'merchant' ),
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
					'default' => esc_html__( 'Someone added this to their cart', 'merchant' ),
				),
			),
		),
		array(
			'id'             => 'product_views_settings',
			'type'           => 'fields_group',
			'title'          => esc_html__( 'Visitors Count', 'merchant' ),
			'sub-desc'       => esc_html__( 'Display the number of shoppers who viewed a specific product within a specified time period.', 'merchant' ),
			'state'          => 'closed',
			'default'        => 'active',
			'accordion'      => true,
			'display_status' => true,
			'fields'         => array(
				array(
					'id'      => 'time_span',
					'type'    => 'number',
					'title'   => esc_html__( 'Time period', 'merchant' ),
					'desc'    => esc_html__( 'Choose the number of days, weeks, or another time unit to define the period for displaying the count of shoppers who viewed a product.',
						'merchant' ),
					'default' => '1',
					'min'     => '1',
				),
				array(
					'id'      => 'time_unit',
					'type'    => 'select',
					'title'   => esc_html__( 'Time unit', 'merchant' ),
					'options' => array(
						'WEEK'   => esc_html__( 'Weeks', 'merchant' ),
						'DAY'    => esc_html__( 'Days', 'merchant' ),
						'HOUR'   => esc_html__( 'Hours', 'merchant' ),
					),
					'default' => 'DAY',
				),
				array(
					'id'          => 'template_singular',
					'type'        => 'text',
					'label'       => esc_html__( 'Singular Template', 'merchant' ),
					'desc'        => esc_html__( 'Singular template for displaying the number of people who have viewed a product in a period.', 'merchant' ),
					'default'     => esc_html__( '{count} person has viewed this today', 'merchant' ),
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
					'default'     => esc_html__( '{count} people have viewed this today', 'merchant' ),
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
	'title'  => esc_html__( 'Custom Settings', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'hide_on_mobile',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Hide on Mobile', 'merchant' ),
			'desc'    => esc_html__( 'Because the mobile screen is much smaller, you might not want to take up space with this module.', 'merchant' ),
			'default' => true,
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
			'id'      => 'hide_product_image',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Hide product image', 'merchant' ),
			'desc'    => esc_html__( 'If turned on, product image will be hidden from notification banners.', 'merchant' ),
			'default' => false,
		),
		array(
			'id'      => 'hide_product_name',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Hide product name from notifications', 'merchant' ),
			'desc'    => esc_html__( 'If turned on, product names will be hidden from notification banners.', 'merchant' ),
			'default' => false,
		),
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
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => Merchant_Recent_Sales_Notifications::MODULE_ID,
	'fields' => array(
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
			'default' => 'bottom-left',
			'desc'    => esc_html__( 'The direction from which the notification box will slide from.', 'merchant' ),
		),
		array(
			'id'      => 'layout',
			'type'    => 'image_picker',
			'title'   => esc_html__( 'Select layout', 'merchant' ),
			'options' => array(
				'layout-1' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/layout-1.png',
					'title' => esc_html__( 'Layout 1', 'merchant' ),
				),
				'layout-2' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/layout-2.png',
					'title' => esc_html__( 'Layout 2', 'merchant' ),
				),
				'layout-3' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/layout-3.png',
					'title' => esc_html__( 'Layout 3', 'merchant' ),
				),
				'layout-4' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/layout-4.png',
					'title' => esc_html__( 'Layout 4', 'merchant' ),
				),
			),
			'default' => 'layout-1',
			'desc'    => esc_html__( 'Choose the layout & style of the notification box.', 'merchant' ),
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
			'id'      => 'theme_type',
			'type'    => 'radio',
			'title'   => esc_html__( 'Choose theme', 'merchant' ),
			'desc'    => esc_html__( 'Choose the theme of the notification box.', 'merchant' ),
			'options' => array(
				'custom'   => esc_html__( 'Custom', 'merchant' ),
				'template' => esc_html__( 'Template', 'merchant' ),
			),
			'default' => 'custom',
		),
		array(
			'id'         => 'theme',
			'type'       => 'image_picker',
			'title'      => esc_html__( 'Select layout', 'merchant' ),
			'options'    => array(
				'new_year'      => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/new_year.png',
					'title' => esc_html__( 'New Year', 'merchant' ),
				),
				'halloween'     => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/halloween.png',
					'title' => esc_html__( 'Halloween', 'merchant' ),
				),
				'christmas'     => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/christmas.png',
					'title' => esc_html__( 'Christmas', 'merchant' ),
				),
				'black_friday'  => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/black_friday.png',
					'title' => esc_html__( 'Black Friday', 'merchant' ),
				),
				'cyber_monday'  => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/cyber_monday.png',
					'title' => esc_html__( 'Cyber Monday', 'merchant' ),
				),
				'valentine'     => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/valentine.png',
					'title' => esc_html__( 'Valentine', 'merchant' ),
				),
				'spring'        => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/spring.png',
					'title' => esc_html__( 'Spring', 'merchant' ),
				),
				'thanks_giving' => array(
					'image' => MERCHANT_URI . 'assets/images/modules/recent-sales-notifications/thanks_giving.png',
					'title' => esc_html__( 'Thanksgiving', 'merchant' ),
				),
			),
			'default'    => 'new_year',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'theme_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'template', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'background_image',
			'type'       => 'upload',
			'drag_drop'  => true,
			'title'      => esc_html__( 'Upload custom background image', 'merchant' ),
			'label'      => esc_html__( 'Click to upload or drag and drop', 'merchant' ),
			'desc'       => esc_html__( 'The selected image will automatically adjust to fit and center within the notification box. An ideal size is 420px by 110px.',
				'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'theme_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'template', // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'background_color',
			'type'       => 'color',
			'title'      => esc_html__( 'Background color', 'merchant' ),
			'default'    => '#ffffff',
			'desc'       => esc_html__( 'The background color of the notification box.', 'merchant' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'theme_type', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'custom', // can be a single value or an array of string/number/int
					),
				),
			),
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
			'default' => '#ffffff',
			'desc'    => esc_html__( 'The color of the border.', 'merchant' ),
		),
		array(
			'id'      => 'message_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Notification message color', 'merchant' ),
			'default' => '#000000',
		),
		array(
			'id'      => 'product_name_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Product name color', 'merchant' ),
			'default' => '#000000',
		),
		array(
			'id'      => 'time_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Time color', 'merchant' ),
			'default' => '#919191',
		),
	),
) );