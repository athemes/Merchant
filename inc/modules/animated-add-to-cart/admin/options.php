<?php
/**
 * Animated Add to Cart
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'module' => 'animated-add-to-cart',
	'title'  => 'Settings',
	'fields' => array(

		array(
			'id'      => 'trigger',
			'type'    => 'radio',
			'title'   => esc_html__( 'Activate this animation', 'merchant' ),
			'options' => array(
				'on-mouse-hover' => esc_html__( 'On mouse hover', 'merchant' ),
				'on-page-load'   => esc_html__( 'On page load', 'merchant' ),
			),
			'default' => 'on-mouse-hover',
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
			'title'   => esc_html__( 'Animation style', 'merchant' ),
			'class'   => 'merchant-animated-buttons',
			'desc'    => esc_html__( 'Move your mouse over each option to see the animations. Click on one of the buttons to select that animation.', 'merchant' ),
			'options' => array(
				'flash'       => 'Flash',
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
			'default' => 'swing',
		),

	),
) );
