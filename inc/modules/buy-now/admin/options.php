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

		// Customize The Button or Inherit from Themes.
		array(
			'id'      => 'customize-button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Customize Button', 'merchant' ),
			'default' => 1
		),

		array(
			'id'    => 'text-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button text color', 'merchant' ),
			'default' => '#ffffff',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'    => 'text-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button text color hover', 'merchant' ),
			'default' => '#ffffff',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'    => 'border-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'    => 'border-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#414141',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'    => 'background-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'    => 'background-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#414141',
			'condition' => array( 'customize-button', '==', true )
		),

		array(
			'id'      => 'font-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Font size', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 16,
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
			'default' => 14,
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
			'default' => 5,
			'condition' => array( 'customize-button', '==', true )
		),

		// Hook Order.
		array(
			'id'      => 'hook-order',
			'type'    => 'range',
			'title'   => __( 'Loading priority on shop archive', 'merchant' ),
			'desc'    => __( 'Note: This is a developer level feature. The buy now button module is "hooked" into a specific location on the shop archive pages. Themes and other plugins might also add additional elements to the same location. By modifying the loading priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'unit'    => '',
			'default' => 20
		),

	),
) );
