<?php

/**
 * Cart Reserved Timer Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => Merchant_Countdown_Timer::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'discount_products_only',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display on discounted products only', 'merchant' ),
			'default' => true,
		),

		array(
			'id'      => 'sale_ending_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Sale ending message', 'merchant' ),
			'default' => esc_html__( 'Sale ends in', 'merchant' ),
			'desc'    => esc_html__( 'The message that shows up above the countdown timer.', 'merchant' ),
		),

		array(
			'id'      => 'end_date',
			'type'    => 'select',
			'title'   => esc_html__( 'Countdown timer end date', 'merchant' ),
			'options' => array(
				'evergreen'  => esc_html__( 'Evergreen', 'merchant' ),
				'sale-dates' => esc_html__( 'Sale price dates', 'merchant' ),
			),
			'default' => 'evergreen',
			'desc'    => esc_html__( 'Using "Evergreen", a unique expiration date will be randomly generated for each visitor and product based on the set minimum expiration. If "Sale price dates" is selected, it will follow the sale dates specified in the sale schedule on the product editing page.', 'merchant' ),
		),


		array(
			'id'        => 'cool_off_period',
			'type'      => 'number',
			'title'     => esc_html__( 'Cool off period (minutes)', 'merchant' ),
			'default'   => 15,
			'desc'      => esc_html__( 'Once the cool off period expires, the countdown timer will be shown again (individually for each customer on each product page).', 'merchant' ),
			'condition' => array( 'end_date', '==', 'evergreen' ),
		),

		array(
			'id'        => 'min_expiration_deadline',
			'type'      => 'number',
			'title'     => esc_html__( 'Minimum expiration deadline (hours)', 'merchant' ),
			'default'   => 2,
			'condition' => array( 'end_date', '==', 'evergreen' ),
		),

		array(
			'id'        => 'max_expiration_deadline',
			'type'      => 'number',
			'title'     => esc_html__( 'Maximum expiration deadline (hours)', 'merchant' ),
			'default'   => 26,
			'condition' => array( 'end_date', '==', 'evergreen' ),
		),

	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Style', 'merchant' ),
	'module' => Merchant_Countdown_Timer::MODULE_ID,
	'fields' => array(

		array(
			'id'      => 'sale_ending_alignment',
			'type'    => 'select',
			'title'   => esc_html__( 'Align', 'merchant' ),
			'options' => array(
				'left'   => esc_html__( 'Left', 'merchant' ),
				'center' => esc_html__( 'Center', 'merchant' ),
				'right'  => esc_html__( 'Right', 'merchant' ),
			),
			'default' => 'left',
		),

		array(
			'id'      => 'icon_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon color', 'merchant' ),
			'default' => '#626262',
		),

		array(
			'id'      => 'sale_ending_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Sale ending message text color', 'merchant' ),
			'default' => '#626262',
		),

		array(
			'id'      => 'digits_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Color of the digits', 'merchant' ),
			'default' => '#444444',
		),

	),
) );



