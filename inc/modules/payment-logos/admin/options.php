<?php
/**
 * Payment Logos
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Payment Logos
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Payment Logos', 'merchant' ),
	'module' => 'payment-logos',
	'fields' => array(

		array(
			'id'    => 'logos',
			'type'  => 'gallery',
			'label' => esc_html__( 'Select Logos', 'merchant' )
		),

	),
) );

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'payment-logos',
	'fields' => array(

		array(
			'id'           => 'align',
			'type'         => 'select',
			'title'        => esc_html__( 'Align Logos', 'merchant' ),
			'options'      => array(
				'flex-start' => esc_html__( 'Left', 'merchant' ),
				'center'     => esc_html__( 'Center', 'merchant' ),
				'flex-end'   => esc_html__( 'Right', 'merchant' ),
			),
			'default'      => 'center',
		),

		array(
			'id'      => 'title',
			'type'    => 'text',
			'title'   => esc_html__( 'Text Above the Logos', 'merchant' ),
			'default' => esc_html__( '🔒 Safe & Secure Checkout', 'merchant' ),
		),

		array(
			'id'      => 'font-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Font Size', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 18,
			'unit'    => 'px',
		),

		array(
			'id'      => 'text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text Color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'margin-bottom',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin Bottom', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Image Max Width', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 100,
			'unit'    => 'px',
		),

		array(
			'id'      => 'image-max-height',
			'type'    => 'range',
			'title'   => esc_html__( 'Image Max Height', 'merchant' ),
			'min'     => 1,
			'max'     => 250,
			'step'    => 1,
			'default' => 100,
			'unit'    => 'px',
		),

	),
) );
