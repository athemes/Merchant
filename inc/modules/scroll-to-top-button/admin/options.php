<?php
/**
 * Merchant - Scroll to Top Button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings for Desktop
 */
Merchant_Admin_Options::create( array(
	'module'    => 'scroll-to-top-button',
	'title'     => __( 'Settings for Desktop', 'merchant' ),
	'fields'    => array(

		array(
			'id'      => 'style',
			'type'    => 'select',
			'title'   => esc_html__( 'Button style', 'merchant' ),
			'options' => array(
				'merchant-style-filled'  => 'Filled',
				'merchant-style-outline' => 'Outline',
			),
			'default' => 'merchant-style-filled',
		),

		array(
			'id'      => 'type',
			'type'    => 'select',
			'title'   => esc_html__( 'Type', 'merchant' ),
			'options' => array(
				'icon'      => esc_html__( 'Icon', 'merchant' ),
				'text-icon' => esc_html__( 'Text + icon', 'merchant' ),
			),
			'default' => 'icon',
		),

		array(
			'id'      => 'icon',
			'type'    => 'choices',
			'title'   => esc_html__( 'Icon', 'merchant' ),
			'options' => array(
				'arrow-1' => '%s/arrow-1.svg',
				'arrow-2' => '%s/arrow-2.svg',
				'arrow-3' => '%s/arrow-3.svg',
				'arrow-4' => '%s/arrow-4.svg',
			),
			'default' => 'arrow-1',
		),

		array(
			'id'        => 'text',
			'type'      => 'text',
			'title'     => 'Text',
			'default'   => esc_html__( 'Back to top', 'merchant' ),
			'condition' => array( 'type', '==', 'text-icon' ),
		),

		array(
			'id'      => 'position',
			'type'    => 'select',
			'title'   => esc_html__( 'Position', 'merchant' ),
			'options' => array(
				'merchant-position-left'  => 'Left',
				'merchant-position-right' => 'Right',
			),
			'default' => 'merchant-position-right',
		),

		array(
			'id'      => 'side-offset',
			'type'    => 'range',
			'title'   => esc_html__( 'Side offset', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 30,
		),

		array(
			'id'      => 'bottom-offset',
			'type'    => 'range',
			'title'   => esc_html__( 'Bottom offset', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 30,
		),

		array(
			'id'      => 'visibility',
			'type'    => 'select',
			'title'   => esc_html__( 'Visibility', 'merchant' ),
			'options' => array(
				'all'          => esc_html__( 'Show on all devices', 'merchant' ),
				'desktop-only' => esc_html__( 'Desktop only', 'merchant' ),
				'mobile-only'  => esc_html__( 'Mobile only', 'merchant' ),
			),
			'default' => 'all',
		),

	),
) );

/**
 * Settings for Mobile
 */
Merchant_Admin_Options::create( array(
	'module'    => 'scroll-to-top-button',
	'title'     => 'Settings for Mobile',
	'fields'    => array(

		array(
			'id'      => 'side-offset-mobile',
			'type'    => 'range',
			'title'   => esc_html__( 'Side offset', 'merchant' ),
			'min'     => 1,
			'max'     => 50,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 30,
		),

		array(
			'id'      => 'bottom-offset-mobile',
			'type'    => 'range',
			'title'   => esc_html__( 'Bottom offset', 'merchant' ),
			'min'     => 1,
			'max'     => 50,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 30,
		),

	),
) );

/**
 * Style Settings
 */
Merchant_Admin_Options::create( array(
	'module'    => 'scroll-to-top-button',
	'title'     => esc_html__( 'Style', 'merchant' ),
	'fields'    => array(

		array(
			'id'      => 'icon-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Icon size', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 18,
		),

		array(
			'id'        => 'text-size',
			'type'      => 'range',
			'title'     => esc_html__( 'Text size', 'merchant' ),
			'min'       => 1,
			'max'       => 500,
			'step'      => 1,
			'unit'      => 'px',
			'default'   => 18,
			'condition' => array( 'type', '==', 'text-icon' ),
		),

		array(
			'id'      => 'padding',
			'type'    => 'range',
			'title'   => esc_html__( 'Padding', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 15,
		),

		array(
			'id'        => 'border-size',
			'type'      => 'range',
			'title'     => esc_html__( 'Border size', 'merchant' ),
			'min'       => 1,
			'max'       => 500,
			'step'      => 1,
			'unit'      => 'px',
			'default'   => 2,
			'condition' => array( 'style', '==', 'outline' ),
		),

		array(
			'id'      => 'border-radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'min'     => 1,
			'max'     => 500,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 30,
		),

		array(
			'id'      => 'icon-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon color', 'merchant' ),
			'default' => '#FFFFFF',
		),

		array(
			'id'      => 'icon-hover-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon color hover', 'merchant' ),
			'default' => '#FFFFFF',
		),

		array(
			'id'        => 'text-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Text color', 'merchant' ),
			'default'   => '#FFFFFF',
			'condition' => array( 'type', '==', 'text-icon' ),
		),

		array(
			'id'        => 'text-hover-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Text color hover', 'merchant' ),
			'default'   => '#FFFFFF',
			'condition' => array( 'type', '==', 'text-icon' ),
		),

		array(
			'id'        => 'border-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color', 'merchant' ),
			'default'   => '#212121',
			'condition' => array( 'style', '==', 'outline' ),
		),

		array(
			'id'        => 'border-hover-color',
			'type'      => 'color',
			'title'     => esc_html__( 'Border color hover', 'merchant' ),
			'default'   => '#757575',
			'condition' => array( 'style', '==', 'outline' ),
		),

		array(
			'id'      => 'background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'background-hover-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color hover', 'merchant' ),
			'default' => '#757575',
		),

	),
) );
