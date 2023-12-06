<?php
/**
 * Merchant Buy Now
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'buy-now',
	'fields' => array(

		array(
			'id'      => 'button-text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button text', 'merchant' ),
			'default' => esc_html__( 'Buy Now', 'merchant' ),
		),

		array(
			'id'    => 'text-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button text color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'    => 'text-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button text color hover', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'    => 'border-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'    => 'border-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#414141',
		),

		array(
			'id'    => 'background-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'    => 'background-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#414141',
		),

		// Display Button in the Newline.
		array(
			'id'      => 'display-in-newline',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Display Button in the New Line', 'merchant' ),
			'default' => 0
		),

		array(
			'id'           => 'align',
			'type'         => 'select',
			'title'        => esc_html__( 'Align Button', 'merchant' ),
			'options'      => array(
				'flex-start' => esc_html__( 'Left', 'merchant' ),
				'center'     => esc_html__( 'Center', 'merchant' ),
				'flex-end'   => esc_html__( 'Right', 'merchant' ),
				'stretch'    => esc_html__( 'Stretch', 'merchant' ),
			),
			'default'      => 'center',
			'condition' => array( 'display-in-newline', '==', true )
		),

		// Customize The Button or Inherit from Themes.
		array(
			'id'      => 'customize-button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Customize Button (otherwise Inherit from Themes)', 'merchant' ),
			'default' => 0
		),

		array(
			'id'      => 'font-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Font size', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 15,
			'unit'    => 'px',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'      => 'padding',
			'type'    => 'range',
			'title'   => esc_html__( 'Padding', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'      => 'border-radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 15,
			'condition' => array( 'customize-button', '==', true )
		),

	),
) );
