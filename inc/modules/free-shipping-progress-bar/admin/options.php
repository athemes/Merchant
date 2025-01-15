<?php
/**
 * Free Shipping Progress Bar
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Hook functionality before including modules options.
 *
 * @since 1.9.15
 */
do_action( 'merchant_admin_before_include_modules_options', Merchant_Free_Shipping_Progress_Bar::MODULE_ID );

$merchant_module_id = Merchant_Free_Shipping_Progress_Bar::MODULE_ID;
/**
 * Content
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Content', 'merchant' ),
	'module' => Merchant_Free_Shipping_Progress_Bar::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'config_msg',
			'type'    => 'content',
			'title'   => esc_html__( 'Message Configuration', 'merchant' ),
			'content' => '',
			'desc'    => esc_html__( 'Configure the 3 states of the free shipping offer. Personalize the text to maximize the conversion.', 'merchant' ),
		),

		array(
			'id'          => 'free_shipping_initial_text',
			'type'        => 'text',
			'title'       => esc_html__( 'Initial Message', 'merchant' ),
			'default'     => esc_html__( 'Free shipping for orders over {amount}!', 'merchant' ),
			'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
			'hidden_desc' => sprintf(
			/* Translators: %1$s: the free shipping amount */
				__(
					'<strong>%1$s:</strong> to show the free shipping required amount',
					'merchant'
				),
				'{amount}'
			),
		),

		array(
			'id'          => 'free_shipping_text',
			'type'        => 'text',
			'title'       => esc_html__( 'Free shipping text', 'merchant' ),
			'default'     => esc_html__( 'You are {amount_left} away from free shipping.', 'merchant' ),
			'desc'        => __( 'You can use these codes in the content.', 'merchant' ),
			'hidden_desc' => sprintf(
			/* Translators: %1$s: the free shipping amount */
				__(
					'<strong>%1$s:</strong> to show the free shipping amount left',
					'merchant'
				),
				'{amount_left}'
			),
		),

		array(
			'id'      => 'qualified_free_shipping_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Qualified free shipping text', 'merchant' ),
			'default' => esc_html__( 'You have qualified for free shipping.', 'merchant' ),
		),

		array(
			'id'      => 'include_tax',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Include tax in calculation', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'      => 'user_condition',
			'type'    => 'select',
			'title'   => esc_html__( 'User Condition', 'merchant' ),
			'options' => array(
				'all'   => esc_html__( 'All Users', 'merchant' ),
				'users' => esc_html__( 'Selected Users', 'merchant' ),
				'roles' => esc_html__( 'Selected Roles', 'merchant' ),
			),
			'default' => 'all',
		),

		array(
			'id'         => 'user_condition_roles',
			'type'       => 'select_ajax',
			'title'      => esc_html__( 'User Roles', 'merchant' ),
			'desc'       => esc_html__( 'This will limit the offer to users with these roles.', 'merchant' ),
			'source'     => 'options',
			'multiple'   => true,
			'classes'    => array( 'flex-grow' ),
			'options'    => Merchant_Admin_Options::get_user_roles_select2_choices(),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'user_condition', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'roles', // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'user_condition_users',
			'type'       => 'select_ajax',
			'title'      => esc_html__( 'Users', 'merchant' ),
			'desc'       => esc_html__( 'This will limit the offer to the selected customers.', 'merchant' ),
			'source'     => 'user',
			'multiple'   => true,
			'classes'    => array( 'flex-grow' ),
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'user_condition', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => 'users', // can be a single value or an array of string/number/int
					),
				),
			),
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
	),
) );

/**
 * Placement
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Placement', 'merchant' ),
	'module' => Merchant_Free_Shipping_Progress_Bar::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'top_bottom_bar',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Top/Bottom Bar', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'         => 'scroll_visibility',
			'type'       => 'radio',
			'title'      => __( 'Position', 'merchant' ),
			'options'    => array(
				'sticky' => __( 'Sticky (always visible)', 'merchant' ),
				'fixed'  => __( 'Fixed (hidden on scroll)', 'merchant' ),
			),
			'default'    => 'fixed',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'top_bottom_bar_show_on',
			'type'       => 'select',
			'title'      => esc_html__( 'Show on', 'merchant' ),
			'options'    => array(
				'both'    => esc_html__( 'Both Desktop & Mobile', 'merchant' ),
				'desktop' => esc_html__( 'Desktop Only', 'merchant' ),
				'mobile'  => esc_html__( 'Mobile Only', 'merchant' ),
			),
			'default'    => 'both',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'desktop_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Desktop Placement', 'merchant' ),
			'options'    => array(
				'top'    => esc_html__( 'Top', 'merchant' ),
				'bottom' => esc_html__( 'Bottom', 'merchant' ),
			),
			'default'    => 'bottom',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar_show_on', // field ID
						'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => array( 'desktop', 'both' ), // can be a single value or an array of string/number/int
					),
				),
			),
		),

		//      array(
		//          'id'         => 'desktop_alignment',
		//          'type'       => 'select',
		//          'title'      => esc_html__( 'Desktop Alignment', 'merchant' ),
		//          'options'    => array(
		//              'right' => esc_html__( 'Right', 'merchant' ),
		//              'left'  => esc_html__( 'Left', 'merchant' ),
		//          ),
		//          'default'    => 'right',
		//          'conditions' => array(
		//              'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
		//              'terms'    => array(
		//                  array(
		//                      'field'    => 'desktop_placement', // field ID
		//                      'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
		//                      'value'    => 'top', // can be a single value or an array of string/number/int
		//                  ),
		//                  array(
		//                      'field'    => 'top_bottom_bar', // field ID
		//                      'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
		//                      'value'    => true, // can be a single value or an array of string/number/int
		//                  ),
		//                  array(
		//                      'field'    => 'top_bottom_bar_show_on', // field ID
		//                      'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
		//                      'value'    => array( 'desktop', 'both' ), // can be a single value or an array of string/number/int
		//                  ),
		//              ),
		//          ),
		//      ),

		array(
			'id'         => 'desktop_offset_toggle',
			'type'       => 'checkbox',
			'label'      => __( 'Custom vertical offset', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar_show_on', // field ID
						'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => array( 'desktop', 'both' ), // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'desktop_offset',
			'type'       => 'range',
			'title'      => esc_html__( 'Offset', 'merchant' ),
			'min'        => 0,
			'max'        => 200,
			'step'       => 1,
			'default'    => 0,
			'unit'       => 'px',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'desktop_offset_toggle', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar_show_on', // field ID
						'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => array( 'desktop', 'both' ), // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'mobile_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Mobile Placement', 'merchant' ),
			'options'    => array(
				'top'    => esc_html__( 'Top', 'merchant' ),
				'bottom' => esc_html__( 'Bottom', 'merchant' ),
			),
			'default'    => 'top',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar_show_on', // field ID
						'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => array( 'mobile', 'both' ), // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'mobile_offset_toggle',
			'type'       => 'checkbox',
			'label'      => __( 'Custom vertical offset', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar_show_on', // field ID
						'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => array( 'mobile', 'both' ), // can be a single value or an array of string/number/int
					),
				),
			),
		),

		array(
			'id'         => 'mobile_offset',
			'type'       => 'range',
			'title'      => esc_html__( 'Offset', 'merchant' ),
			'min'        => 0,
			'max'        => 200,
			'step'       => 1,
			'default'    => 0,
			'unit'       => 'px',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'mobile_offset_toggle', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar_show_on', // field ID
						'operator' => 'in', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => array( 'mobile', 'both' ), // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'close_icon',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Close (x) Icon', 'merchant' ),
			'default'    => 1,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'close_card_stay_time',
			'type'       => 'number',
			'min'        => '1',
			'step'       => '1',
			'default'    => '24',
			'title'      => esc_html__( 'Session Expiration Time', 'merchant' ),
			'desc'       => esc_html__( 'After this number of hours, the free shipping bar will show again, even if the user has closed it previously.', 'merchant' ),
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'close_icon', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'         => 'show_progress_bar',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Progress Bar', 'merchant' ),
			'desc'       => esc_html__( 'Enable Progress bar on top and bottom bar', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'top_bottom_bar',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'show_on_shop_page',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Show on Shop Page', 'merchant' ),
			'desc'       => esc_html__( 'Show the bar on shop page', 'merchant' ),
			'default'    => 1,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'top_bottom_bar',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Single Product Page', 'merchant' ),
		),
		array(
			'id'      => 'show_on_single_product_page',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Bar', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'single_product_page_shortcode',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Use Shortcode', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_single_product_page',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'single_product_page_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Position', 'merchant' ),
			'options'    => array(
				'woocommerce_before_add_to_cart_form'      => esc_html__( 'Before Add to Cart Form', 'merchant' ),
				'woocommerce_after_add_to_cart_quantity'   => esc_html__( 'After Add to Cart Quantity', 'merchant' ),
				'woocommerce_after_add_to_cart_form'       => esc_html__( 'After Add to Cart Form', 'merchant' ),
				'woocommerce_after_single_product_summary' => esc_html__( 'After Single Product Summary', 'merchant' ),
				'woocommerce_after_single_product'         => esc_html__( 'After Single Product', 'merchant' ),
			),
			'default'    => 'woocommerce_before_add_to_cart_form',
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_single_product_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'single_product_page_shortcode',
						'operator' => '===',
						'value'    => false,
					),
				),
			),
		),

		array(
			'id'         => 'single_product_page_shortcode_info',
			'type'       => 'info',
			'content'    => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that this shortcode can only be used on single product pages.',
				'merchant' ),
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_single_product_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'single_product_page_shortcode',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'single_product_page_shortcode_text',
			'type'       => 'text_readonly',
			'title'      => esc_html__( 'Shortcode text', 'merchant' ),
			'default'    => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . '_single_product_page]',
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_single_product_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'single_product_page_shortcode',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),

		array(
			'id'         => 'single_page_show_progress_bar',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Progress Bar', 'merchant' ),
			'desc'       => esc_html__( 'Enable Progress bar on single product page', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_single_product_page',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Mini Cart', 'merchant' ),
		),
		array(
			'id'      => 'show_on_mini_cart_widget',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Bar', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'mini_cart_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Position', 'merchant' ),
			'options'    => array(
				'woocommerce_before_mini_cart_contents'  => esc_html__( 'Before Cart Items', 'merchant' ),
				'woocommerce_widget_shopping_cart_total' => esc_html__( 'After Subtotal', 'merchant' ),
			),
			'default'    => 'woocommerce_before_mini_cart_contents',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_mini_cart_widget',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'mini_cart_show_progress_bar',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Progress Bar', 'merchant' ),
			'desc'       => esc_html__( 'Enable Progress bar on mini cart', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_mini_cart_widget',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Side Cart', 'merchant' ),
		),
		array(
			'id'      => 'show_on_side_cart_widget',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Bar', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'side_cart_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Position', 'merchant' ),
			'options'    => array(
				'merchant_before_mini_cart_contents'           => esc_html__( 'Before Cart Items', 'merchant' ),
				'merchant_widget_shopping_cart_before_buttons' => esc_html__( 'Before Cart Buttons', 'merchant' ),
				'merchant_widget_shopping_cart_after_buttons'  => esc_html__( 'After Cart Buttons', 'merchant' ),
			),
			'default'    => 'merchant_widget_shopping_cart_before_buttons',
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_side_cart_widget',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'side_cart_show_progress_bar',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Progress Bar', 'merchant' ),
			'desc'       => esc_html__( 'Enable Progress bar on side cart', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_side_cart_widget',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Cart Page', 'merchant' ),
		),
		array(
			'id'      => 'show_on_cart_page',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Bar', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'cart_page_shortcode',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Use Shortcode', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_cart_page',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'cart_page_shortcode_info',
			'type'       => 'info',
			'content'    => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that this shortcode can only be used on cart page.',
				'merchant' ),
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_cart_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'cart_page_shortcode',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'cart_page_shortcode_text',
			'type'       => 'text_readonly',
			'title'      => esc_html__( 'Shortcode text', 'merchant' ),
			'default'    => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . '_cart_page]',
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_cart_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'cart_page_shortcode',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'cart_page_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Position', 'merchant' ),
			'options'    => array(
				'woocommerce_before_cart'                   => esc_html__( 'Before Cart', 'merchant' ),
				'woocommerce_cart_totals_after_order_total' => esc_html__( 'After Order Total', 'merchant' ),
				'woocommerce_after_cart_table'              => esc_html__( 'After Cart Table', 'merchant' ),
			),
			'default'    => 'woocommerce_cart_totals_before_order_total',
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_cart_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'cart_page_shortcode',
						'operator' => '===',
						'value'    => false,
					),
				),
			),
		),
		array(
			'id'         => 'cart_page_show_progress_bar',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Progress Bar', 'merchant' ),
			'desc'       => esc_html__( 'Enable Progress bar on cart page', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_cart_page',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'type'  => 'divider',
			'title' => esc_html__( 'Checkout Page', 'merchant' ),
		),
		array(
			'id'      => 'show_on_checkout_page',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Bar', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'         => 'checkout_page_shortcode',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Use Shortcode', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_checkout_page',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'checkout_page_shortcode_info',
			'type'       => 'info',
			'content'    => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that this shortcode can only be used on checkout page.',
				'merchant' ),
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_checkout_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'checkout_page_shortcode',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'checkout_page_shortcode_text',
			'type'       => 'text_readonly',
			'title'      => esc_html__( 'Shortcode text', 'merchant' ),
			'default'    => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . '_checkout_page]',
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'show_on_checkout_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'checkout_page_shortcode',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
		array(
			'id'         => 'checkout_page_placement',
			'type'       => 'select',
			'title'      => esc_html__( 'Position', 'merchant' ),
			'options'    => array(
				'woocommerce_before_checkout_form'        => esc_html__( 'Before Checkout Form', 'merchant' ),
				'woocommerce_checkout_billing'            => esc_html__( 'Checkout Billing', 'merchant' ),
				'woocommerce_checkout_shipping'           => esc_html__( 'Checkout Shipping', 'merchant' ),
				'woocommerce_review_order_before_payment' => esc_html__( 'Before Payment', 'merchant' ),
				'woocommerce_review_order_after_payment'  => esc_html__( 'After Payment', 'merchant' ),
				'woocommerce_after_checkout_form'         => esc_html__( 'After Checkout Form', 'merchant' ),
			),
			'default'    => 'woocommerce_before_checkout_form',
			'conditions' => array(
				'relation' => 'AND',
				'terms' => array(
					array(
						'field'    => 'show_on_checkout_page',
						'operator' => '===',
						'value'    => true,
					),
					array(
						'field'    => 'checkout_page_shortcode',
						'operator' => '===',
						'value'    => false,
					),
				),
			),
		),
		array(
			'id'         => 'checkout_page_show_progress_bar',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Progress Bar', 'merchant' ),
			'desc'       => esc_html__( 'Enable Progress bar on checkout page', 'merchant' ),
			'default'    => 0,
			'conditions' => array(
				'terms' => array(
					array(
						'field'    => 'show_on_checkout_page',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),
	),
) );

/**
 * Placement
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => Merchant_Free_Shipping_Progress_Bar::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'preset',
			'type'    => 'select',
			'title'   => esc_html__( 'Template', 'merchant' ),
			'options' => array(
				'custom'        => esc_html__( 'Custom style', 'merchant' ),
				'solar_night'   => esc_html__( 'Solar Night', 'merchant' ),
				'lively_breeze' => esc_html__( 'Lively Breeze', 'merchant' ),
				'midnight_tide' => esc_html__( 'Midnight Tide', 'merchant' ),
				'clean_slate'   => esc_html__( 'Clean Slate', 'merchant' ),
				'fresh_frost'   => esc_html__( 'Fresh Frost', 'merchant' ),
				'sky_harmony'   => esc_html__( 'Sky Harmony', 'merchant' ),
				'ocean_breeze'  => esc_html__( 'Ocean Breeze', 'merchant' ),
			),
			'default' => 'custom',
		),
		array(
			'id'         => 'x_icon_color',
			'type'       => 'color',
			'title'      => esc_html__( 'Close (x) Icon color', 'merchant' ),
			'default'    => '#333333',
			'conditions' => array(
				'relation' => 'AND', // AND/OR, If not provided, only first term will be considered
				'terms'    => array(
					array(
						'field'    => 'close_icon', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
					array(
						'field'    => 'top_bottom_bar', // field ID
						'operator' => '===', // Available operators: ===, !==, >, <, >=, <=, in, !in, contains, !contains
						'value'    => true, // can be a single value or an array of string/number/int
					),
				),
			),
		),
		array(
			'id'      => 'card_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'variable_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Variable Text color', 'merchant' ),
			'default' => '#212121',
		),
		array(
			'id'      => 'card_font_size',
			'type'    => 'range',
			'min'     => '4',
			'max'     => '30',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '18',
			'title'   => esc_html__( 'Font Size', 'merchant' ),
		),
		array(
			'id'      => 'card_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background Color', 'merchant' ),
			'default' => '#e6e6e6',
		),
		array(
			'id'      => 'card_padding_top',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '15',
			'title'   => esc_html__( 'Spacing Inside Top', 'merchant' ),
		),
		array(
			'id'      => 'card_padding_bottom',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '15',
			'title'   => esc_html__( 'Spacing Inside Bottom', 'merchant' ),
		),
		array(
			'id'      => 'card_padding_right',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '15',
			'title'   => esc_html__( 'Spacing Inside Right', 'merchant' ),
		),
		array(
			'id'      => 'card_padding_left',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '15',
			'title'   => esc_html__( 'Spacing Inside Left', 'merchant' ),
		),
		array(
			'id'      => 'card_text_bottom_spacing',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '0',
			'title'   => esc_html__( 'Text Bottom Spacing', 'merchant' ),
		),
		array(
			'id'      => 'card_border_radius',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '45',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '4',
			'title'   => esc_html__( 'Corner Radius', 'merchant' ),
		),
		array(
			'id'      => 'card_border_width',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '20',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '0',
			'title'   => esc_html__( 'Border Size', 'merchant' ),
		),
		array(
			'id'      => 'card_border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border Color', 'merchant' ),
			'default' => 'rgba(255, 255, 255, 0)',
		),
		array(
			'id'      => 'bar_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Progress Bar Background Color', 'merchant' ),
			'default' => '#757575',
		),
		array(
			'id'      => 'bar_foreground_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Progress Bar Foreground Color', 'merchant' ),
			'default' => '#212121',
		),
		array(
			'id'      => 'bar_height',
			'type'    => 'range',
			'min'     => '4',
			'max'     => '30',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '10',
			'title'   => esc_html__( 'Progress Bar Height', 'merchant' ),
		),
		array(
			'id'      => 'bar_width',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '100',
			'step'    => '1',
			'unit'    => '%',
			'default' => '100',
			'title'   => esc_html__( 'Progress Bar Width', 'merchant' ),
		),
		array(
			'id'      => 'bar_border_radius',
			'type'    => 'range',
			'min'     => '0',
			'max'     => '30',
			'step'    => '1',
			'unit'    => 'PX',
			'default' => '0',
			'title'   => esc_html__( 'Corner Radius', 'merchant' ),
		),
	),
) );
