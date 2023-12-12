<?php
/**
 * Merchant Buy Now
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Display Settings
Merchant_Admin_Options::create( array(
	'module' => 'buy-now',
	'title'  => esc_html__( 'Display Settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'display-archive',
			'type'    => 'checkbox',
			'title'   => __( 'Show on Product Archive', 'merchant' ),
			'default' => 0
		),
		array(
			'id'      => 'display-product',
			'type'    => 'checkbox',
			'title'   => __( 'Show on product page', 'merchant' ),
			'default' => 0
		),
		array(
			'id'      => 'display-upsell-related',
			'type'    => 'checkbox',
			'title'   => __( 'Show on Upsell and related product', 'merchant' ),
			'default' => 0
		),
		array(
			'id'      => 'display-galleries',
			'type'    => 'checkbox',
			'title'   => __( 'Show on Homepage Product Galleries', 'merchant' ),
			'default' => 0
		),
	),
) );

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

	),
) );
