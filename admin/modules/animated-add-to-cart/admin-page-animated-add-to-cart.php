<?php
/**
 * Merchant - Animated Add to Cart
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'module' => 'animated-add-to-cart',
	'title'  => 'Settings',
	'fields' => array(

		array(
			'id'      => 'trigger',
			'type'    => 'select',
			'title'   => esc_html__( 'Trigger Animation', 'merchant' ),
			'options' => array(
				''                 => esc_html__( 'Select an option', 'merchant' ),
				'on-hover-seconds' => esc_html__( 'On hover and every few seconds', 'merchant' ),
				'on-hover'         => esc_html__( 'On hover', 'merchant' ),
				'every-seconds'    => esc_html__( 'Every few seconds', 'merchant' ),
			),
			'default' => 'every-seconds'
		),

		array(
			'id'      => 'trigger_delay',
			'type'    => 'number',
			'title'   => esc_html__( 'Delay before playing animation (seconds)', 'merchant' ),
			'default' => 10
		),

	),
) );

/**
 * Animation
 */
Merchant_Admin_Options::create( array(
	'module' => 'animated-add-to-cart',
	'title'  => 'Animation',
	'fields' => array(

		array(
			'id'      => 'animation',
			'type'    => 'buttons_alt',
			'title'   => esc_html__( 'Animation Style', 'merchant' ),
			'options' => array(
				'bounce'      => 'Bounce',
				'zoom-in'     => 'Zoom in',
				'shake'       => 'Shake',
				'pulse'       => 'Pulse',
				'jello-shake' => 'Jello Shake',
				'wobble'      => 'Wobble',
				'vibrate'     => 'Vibrate',
				'swing'       => 'Swing',
				'tada'        => 'Tada',
			),
			'default' => 'bounce',
		),

	),
) );
