<?php
/**
 * Merchant - Product Trust Badge
 */

/**
 * Product Trust Badge
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Product Trust Badge', 'merchant' ),
	'module' => 'product-trust-badge',
	'fields' => array(

		array(
			'id'    => 'badge',
			'type'  => 'upload',
			'label' => esc_html__( 'Select Badge', 'merchant' ),
		),

		array(
			'id'      => 'title',
			'type'    => 'text',
			'title'   => esc_html__( 'Title', 'merchant' ),
			'default' => esc_html__( 'Guaranteed Safe Checkout', 'merchant' ),
		),

		array(
			'id'      => 'border-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border Color', 'merchant' ),
			'default' => '#e5e5e5',
		),

	),
) );

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'product-trust-badge',
	'fields' => array(

		array(
			'id'       => 'align',
			'type'     => 'select',
			'title'    => esc_html__( 'Align Badge', 'merchant' ),
			'options'  => array(
				'left'   => esc_html__( 'Left', 'merchant' ),
				'center' => esc_html__( 'Center', 'merchant' ),
				'right'  => esc_html__( 'Right', 'merchant' ),
			),
			'default'  => 'left',
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
			'id'      => 'image-width',
			'type'    => 'number',
			'title'   => esc_html__( 'Image Width', 'merchant' ),
			'default' => 300,
		),

	),
) );
