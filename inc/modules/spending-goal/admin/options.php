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
			'id'      => 'enable_auto_slide_in',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Enable Auto Slide In', 'merchant' ),
			'desc'    => esc_html__( 'This will make the widget slide in after a product is added to the cart.', 'merchant' ),
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
			'default' => esc_html__( 'Spend {spending_goal} more to get a {discount_amount} discount', 'merchant' ),
			'desc'    => esc_html__( 'Default is: Spend {spending_goal} more to get a {discount_amount} discount', 'merchant' ),
		),

		array(
			'id'      => 'text_goal_reached',
			'type'    => 'text',
			'title'   => esc_html__( 'When the goal target is at 100%', 'merchant' ),
			'default' => esc_html__( 'Congratulations! You get a discount of {discount_amount} on this order!', 'merchant' ),
			'desc'    => esc_html__( 'Default: Congratulations! You get a discount of {discount_amount} on this order!', 'merchant' ),
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
