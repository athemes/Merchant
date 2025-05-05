<?php

/**
 * Spending Goal Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Spending_Goal::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'spending_goal',
			'type'    => 'number',
			'title'   => esc_html__( 'Spending goal', 'merchant' ),
			'step'    => 0.01,
			'default' => 150,
		),

		array(
			'id'      => 'total_type',
			'type'    => 'select',
			'title'   => esc_html__( 'Based on', 'merchant' ),
			'desc'    => esc_html__( 'Choose the basis for the spending goal. “Cart Subtotal” excludes additional calculated discounts, whereas “Cart Total” includes them.', 'merchant' ),
			'options' => array(
				'subtotal' => esc_html__( 'Cart subtotal', 'merchant' ),
				'total'    => esc_html__( 'Cart total', 'merchant' ),
			),
			'default' => 'subtotal',
		),

		array(
			'id'      => 'discount_type',
			'type'    => 'select',
			'title'   => esc_html__( 'Discount type', 'merchant' ),
			'options' => array(
				'percent' => esc_html__( 'Percent', 'merchant' ),
				'fixed'   => esc_html__( 'Fixed amount', 'merchant' ),
			),
			'default' => 'percent',
		),

		array(
			'id'      => 'discount_amount',
			'type'    => 'number',
			'title'   => esc_html__( 'Discount amount', 'merchant' ),
			'step'    => 0.01,
			'default' => 10,
		),

		array(
			'id'      => 'discount_name',
			'type'    => 'text',
			'title'   => esc_html__( 'Discount name', 'merchant' ),
			'default' => esc_html__( 'Spending goal', 'merchant' ),
			'desc'    => esc_html__( 'This will be the name of the applied discount on the cart page.', 'merchant' ),
		),

		array(
			'id'      => 'inclusion',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Product Inclusion', 'merchant' ),
			'desc'    => esc_html__( 'Include only certain products or categories', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'         => 'included_products',
			'type'       => 'products_selector',
			'title'      => esc_html__( 'Include Products', 'merchant' ),
			'multiple'   => true,
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'inclusion',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),

		array(
			'id'          => 'included_categories',
			'type'        => 'select_ajax',
			'title'       => esc_html__( 'Include Categories', 'merchant' ),
			'source'      => 'options',
			'multiple'    => true,
			'options'     => Merchant_Admin_Options::get_category_select2_choices(),
			'placeholder' => esc_html__( 'Select categories', 'merchant' ),
			'conditions'  => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'inclusion',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		),

		array(
			'id'      => 'exclusion',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Product Exclusion', 'merchant' ),
			'desc'    => esc_html__( 'Exclude certain products or categories', 'merchant' ),
			'default' => 0,
		),

		array(
			'id'         => 'excluded_products',
			'type'       => 'products_selector',
			'title'      => esc_html__( 'Exclude Products', 'merchant' ),
			'multiple'   => true,
			'conditions' => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'exclusion',
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
			'conditions'  => array(
				'relation' => 'AND',
				'terms'    => array(
					array(
						'field'    => 'exclusion',
						'operator' => '===',
						'value'    => true,
					),
				),
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
			'desc'       => esc_html__( 'Choose the users who will not see this offer.', 'merchant' ),
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
			'id'      => 'enable_auto_slide_in',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Enable Auto Slide In', 'merchant' ),
			'desc'    => esc_html__( 'This will make the widget slide in each time a product is added to the cart.', 'merchant' ),
			'default' => 1,
		),
	),
) );

// Text Formatting Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Text Formatting Settings', 'merchant' ),
	'module' => Merchant_Spending_Goal::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'text_goal_zero',
			'type'    => 'text',
			'title'   => esc_html__( 'When the goal target is at 0%', 'merchant' ),
			'default' => esc_html__( 'Spend {spending_goal} to get a {discount_amount} discount!', 'merchant' ),
			'desc'    => esc_html__( 'Default is: Spend {spending_goal} to get a {discount_amount} discount!', 'merchant' ),
		),

		array(
			'id'      => 'text_goal_started',
			'type'    => 'text',
			'title'   => esc_html__( 'When the goal target is between 1-99%', 'merchant' ),
			'default' => esc_html__( 'Spend {spending_goal} more to get a {discount_amount} discount!', 'merchant' ),
			'desc'    => esc_html__( 'Default is: Spend {spending_goal} more to get a {discount_amount} discount!', 'merchant' ),
		),

		array(
			'id'      => 'text_goal_reached',
			'type'    => 'text',
			'title'   => esc_html__( 'When the goal target is at 100%', 'merchant' ),
			'default' => esc_html__( 'Congratulations! You got a discount of {discount_amount} on this order!', 'merchant' ),
			'desc'    => esc_html__( 'Default: Congratulations! You got a discount of {discount_amount} on this order!', 'merchant' ),
		),
	),
) );

// Style Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Spending_Goal::MODULE_ID,
	'title'  => esc_html__( 'Style', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'gradient_start',
			'type'    => 'color',
			'title'   => esc_html__( 'Gradient start', 'merchant' ),
			'default' => '#5e5e5e',
		),

		array(
			'id'      => 'gradient_end',
			'type'    => 'color',
			'title'   => esc_html__( 'Gradient end', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'progress_bar',
			'type'    => 'color',
			'title'   => esc_html__( 'Progress bar color', 'merchant' ),
			'default' => '#d83a3b',
		),

		array(
			'id'      => 'content_width',
			'type'    => 'range',
			'title'   => esc_html__( 'Content width', 'merchant' ),
			'min'     => 0,
			'max'     => 600,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 300,
		),

		array(
			'id'      => 'content_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content background color', 'merchant' ),
			'default' => '#f9f9f9',
		),

		array(
			'id'      => 'content_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content text color', 'merchant' ),
			'default' => '#3c434a',
		),
	),
) );
