<?php
/**
 * Merchant - Payment Logos
 */

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
			'label' => esc_html__( 'Select Logos', 'merchant' ),
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
			'default'      => 'flex-start',
		),

		array(
			'id'      => 'title',
			'type'    => 'text',
			'title'   => esc_html__( 'Text Above the Logos', 'merchant' ),
			'default' => esc_html__( 'Checkout safely using your preferred payment method', 'merchant' ),
		),

		array(
			'id'      => 'margin-top',
			'type'    => 'number',
			'title'   => esc_html__( 'Margin Top', 'merchant' ),
			'default' => 20,
		),

		array(
			'id'      => 'margin-bottom',
			'type'    => 'number',
			'title'   => esc_html__( 'Margin Bottom', 'merchant' ),
			'default' => 20,
		),

		array(
			'id'      => 'image-max-width',
			'type'    => 'number',
			'title'   => esc_html__( 'Image Max Width', 'merchant' ),
			'default' => 100,
		),

		array(
			'id'      => 'image-max-height',
			'type'    => 'number',
			'title'   => esc_html__( 'Image Max Height', 'merchant' ),
			'default' => 100,
		),

	),
) );
