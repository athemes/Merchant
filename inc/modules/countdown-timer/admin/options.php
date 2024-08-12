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
			'id'      => 'end_date',
			'type'    => 'select',
			'title'   => esc_html__( 'Countdown timer end date', 'merchant' ),
			'options' => array(
				'evergreen'  => esc_html__( 'Evergreen', 'merchant' ),
				'sale-dates' => esc_html__( 'Sale price dates', 'merchant' ),
			),
			'default' => 'sale-dates',
			'desc'    => esc_html__( '"Sale Price Dates" will countdown to a specified end-date and time. "Evergreen" generates a unique expiration date for each visitor and product, within set minimum and maximum deadlines',
				'merchant' ),
		),

		array(
			'id'          => 'sale_start_date',
			'type'        => 'date_time',
			'title'       => esc_html__( 'Countdown starts at', 'merchant' ),
			'placeholder' => esc_html__( 'mm/dd/yy, --:-- --', 'merchant' ),
			'condition'   => array( 'end_date', '==', 'sale-dates' ),
		),

		array(
			'id'          => 'sale_end_date',
			'type'        => 'date_time',
			'title'       => esc_html__( 'Countdown ends at', 'merchant' ),
			'placeholder' => esc_html__( 'mm/dd/yy, --:-- --', 'merchant' ),
			'condition'   => array( 'end_date', '==', 'sale-dates' ),
			'desc'        => sprintf(
			/* Translators: %1$s: Time zone, %2$s WordPress setting link */
				esc_html__( 'The countdown bar will only be displayed during this range. The times set above are in the %1$s timezone, according to your settings from %2$s.',
					'merchant' ),
				'<strong>' . wp_timezone_string() . '</strong>',
				'<a href="' . esc_url( admin_url( 'options-general.php' ) ) . '" target="_blank">' . esc_html__( 'WordPress Settings', 'merchant' ) . '</a>'
			),
		),

		array(
			'id'        => 'cool_off_period',
			'type'      => 'number',
			'title'     => esc_html__( 'Cool off period (minutes)', 'merchant' ),
			'default'   => 15,
			'desc'      => esc_html__( 'Once the cool off period expires, the countdown timer will be shown again (individually for each customer on each product page).',
				'merchant' ),
			'condition' => array( 'end_date', '==', 'evergreen' ),
		),

		// Minimum
		array(
			'id'        => 'min_expiration_deadline_label',
			'type'      => 'content',
			'title'     => esc_html__( 'Minimum expiration deadline', 'merchant' ),
			'content'   => '',
			'desc'      => esc_html__( 'Sets the time before the end date (set by the Maximum expiration deadline option) when the timer will disappear.', 'merchant' ),
			'class'     => 'merchant-countdown-evergreen-content-field',
			'condition' => array( 'end_date', '==', 'evergreen' ),
		),
		array(
			'id'          => 'min_expiration_deadline_days',
			'type'        => 'number',
			'title'       => esc_html__( 'Days', 'merchant' ),
			'default'     => 0,
			'min'         => 0,
			'step'        => 1,
			'placeholder' => esc_html__( 'Days', 'merchant' ),
			'class'       => 'merchant-countdown-evergreen-field',
			'condition'   => array( 'end_date', '==', 'evergreen' ),
		),
		array(
			'id'          => 'min_expiration_deadline',
			'type'        => 'number',
			'title'       => esc_html__( 'Hours', 'merchant' ),
			'default'     => 2,
			'min'         => 0,
			'step'        => 1,
			'placeholder' => esc_html__( 'Hours', 'merchant' ),
			'class'       => 'merchant-countdown-evergreen-field',
			'condition'   => array( 'end_date', '==', 'evergreen' ),
		),
		array(
			'id'          => 'min_expiration_deadline_minutes',
			'type'        => 'number',
			'title'       => esc_html__( 'Minutes', 'merchant' ),
			'default'     => 0,
			'min'         => 0,
			'step'        => 1,
			'placeholder' => esc_html__( 'Minutes', 'merchant' ),
			'class'       => 'merchant-countdown-evergreen-field',
			'condition'   => array( 'end_date', '==', 'evergreen' ),
		),

		// Maximum
		array(
			'id'        => 'max_expiration_deadline_label',
			'type'      => 'content',
			'title'     => esc_html__( 'Maximum expiration deadline', 'merchant' ),
			'content'   => '',
			'desc'      => esc_html__( 'Defines the final countdown end date, when the timer hits 00:00:00.', 'merchant' ),
			'class'     => 'merchant-countdown-evergreen-content-field',
			'condition' => array( 'end_date', '==', 'evergreen' ),
		),
		array(
			'id'          => 'max_expiration_deadline_days',
			'type'        => 'number',
			'title'       => esc_html__( 'Days', 'merchant' ),
			'default'     => 0,
			'min'         => 0,
			'step'        => 1,
			'placeholder' => esc_html__( 'Days', 'merchant' ),
			'class'       => 'merchant-countdown-evergreen-field',
			'condition'   => array( 'end_date', '==', 'evergreen' ),
		),
		array(
			'id'          => 'max_expiration_deadline',
			'type'        => 'number',
			'title'       => esc_html__( 'Hours', 'merchant' ),
			'default'     => 26,
			'min'         => 0,
			'step'        => 1,
			'placeholder' => esc_html__( 'Hours', 'merchant' ),
			'class'       => 'merchant-countdown-evergreen-field',
			'condition'   => array( 'end_date', '==', 'evergreen' ),
		),
		array(
			'id'          => 'max_expiration_deadline_minutes',
			'type'        => 'number',
			'title'       => esc_html__( 'Minutes', 'merchant' ),
			'min'         => 0,
			'step'        => 1,
			'placeholder' => esc_html__( 'Minutes', 'merchant' ),
			'class'       => 'merchant-countdown-evergreen-field',
			'condition'   => array( 'end_date', '==', 'evergreen' ),
		),
	),
) );

// Display Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Countdown_Timer::MODULE_ID,
	'title'  => esc_html__( 'Display Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'discount_products_only',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display on discounted products only', 'merchant' ),
			'desc'    => esc_html__( 'Enable this to only show the countdown timer on discounted products.', 'merchant' ),
			'default' => true,
		),

		array(
			'id'      => 'theme',
			'type'    => 'image_picker',
			'title'   => esc_html__( 'Select layout', 'merchant' ),
			'options' => array(
				'classic'    => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/classic.png',
					'title' => esc_html__( 'Classic', 'merchant' ),
				),
				'progress'   => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/progress.png',
					'title' => esc_html__( 'Progress bar', 'merchant' ),
				),
				'circles'    => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/circles.png',
					'title' => esc_html__( 'Circles', 'merchant' ),
				),
				'squares'    => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/squares.png',
					'title' => esc_html__( 'Squares', 'merchant' ),
				),
				'minimalist' => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/minimalist.png',
					'title' => esc_html__( 'Minimalist', 'merchant' ),
				),
				'cards'      => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/cards.png',
					'title' => esc_html__( 'Cards', 'merchant' ),
				),
				'modern'     => array(
					'image' => MERCHANT_URI . 'assets/images/icons/countdown-timer/admin/modern.png',
					'title' => esc_html__( 'modern', 'merchant' ),
				),
			),
			'default' => 'classic',
		),

		array(
			'id'      => 'sale_ending_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Sale ending message', 'merchant' ),
			'default' => esc_html__( 'Sale ends in', 'merchant' ),
			'desc'    => esc_html__( 'The message that shows up above the countdown timer.', 'merchant' ),
		),

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
	),
) );

Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Look and Feel', 'merchant' ),
	'module' => Merchant_Countdown_Timer::MODULE_ID,
	'fields' => array(
		array(
			'id'      => 'digits_font_size',
			'type'    => 'range',
			'title'   => esc_html__( 'Digits\' font size', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 16,
			'unit'    => 'px',
		),

		array(
			'id'        => 'labels_font_size',
			'type'      => 'range',
			'title'     => esc_html__( 'Label font size', 'merchant' ),
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'default'   => 16,
			'unit'      => 'px',
			'condition' => array( 'theme', 'any', 'minimalist|cards|modern|squares|circles' ),
		),

		array(
			'id'        => 'icon_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Icon color', 'merchant' ),
			'default'   => '#626262',
			'condition' => array( 'theme', '=', 'classic' ),
		),

		array(
			'id'        => 'sale_ending_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Sale ending message text color', 'merchant' ),
			'default'   => '#626262',
			'condition' => array( 'theme', 'any', 'classic|progress' ),
		),

		array(
			'id'      => 'digits_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Digits\' color', 'merchant' ),
			'default' => '#444444',
		),

		array(
			'id'        => 'labels_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Label color', 'merchant' ),
			'default'   => '#444444',
			'condition' => array( 'theme', 'any', 'minimalist|cards|modern|squares|circles' ),
		),

		array(
			'id'        => 'digits_background',
			'type'      => 'color',
			'title'     => esc_html__( 'Digit background color', 'merchant' ),
			'default'   => '#fff',
			'condition' => array( 'theme', 'any', 'minimalist|cards|modern|squares|circles' ),
		),

		array(
			'id'        => 'digits_border',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color', 'merchant' ),
			'default'   => '#444',
			'condition' => array( 'theme', 'any', 'squares|circles' ),
		),

		array(
			'id'        => 'progress_color',
			'type'      => 'color',
			'title'     => esc_html__( 'Progress Bar color', 'merchant' ),
			'default'   => '#3858E9',
			'condition' => array( 'theme', 'any', 'progress|circles' ),
		),

		array(
			'id'        => 'digits_width',
			'type'      => 'range',
			'title'     => esc_html__( 'Width', 'merchant' ),
			'min'       => 1,
			'max'       => 250,
			'step'      => 1,
			'default'   => 80,
			'unit'      => 'px',
			'condition' => array( 'theme', 'any', 'minimalist|cards|modern|squares|circles' ),
		),

		array(
			'id'        => 'digits_height',
			'type'      => 'range',
			'title'     => esc_html__( 'Height', 'merchant' ),
			'min'       => 1,
			'max'       => 250,
			'step'      => 1,
			'default'   => 80,
			'unit'      => 'px',
			'condition' => array( 'theme', 'any', 'minimalist|cards|modern|squares|circles' ),
		),
	),
) );

// Shortcode
$merchant_module_id = Merchant_Countdown_Timer::MODULE_ID;
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
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.', 'merchant' ),
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
