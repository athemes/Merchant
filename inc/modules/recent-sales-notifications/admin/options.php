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
			'id'      => 'layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Layout', 'merchant' ),
			'options' => array(
				'layout-1' => esc_html__( 'Layout 1', 'merchant' ),
				'layout-2' => esc_html__( 'Layout 2', 'merchant' ),
				'layout-3' => esc_html__( 'Layout 3', 'merchant' ),
				'layout-4' => esc_html__( 'Layout 4', 'merchant' ),
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