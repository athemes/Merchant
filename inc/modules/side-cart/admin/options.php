<?php

/**
 * Side Cart Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Merchant_Admin_Options::create( array(
	'module' => Merchant_Side_Cart::MODULE_ID,
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'show_after_add_to_cart',
			'type'    => 'switcher',
			'title'   => __( 'Display after adding a product to the cart on shop', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'show_after_add_to_cart_single_product',
			'type'    => 'switcher',
			'title'   => __( 'Display after adding a product to the cart on single product page', 'merchant' ),
			'default' => 0,
		),
		array(
			'id'      => 'show_on_cart_url_click',
			'type'    => 'switcher',
			'title'   => __( 'Display on cart URL click', 'merchant' ),
			'default' => 1,
		),
	),
) );


// Side Cart Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Side Cart Settings', 'merchant' ),
	'module' => 'floating-mini-cart',
	'fields' => array(

		array(
			'id'      => 'side-cart-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Side cart width', 'merchant' ),
			'min'     => 0,
			'max'     => 2000,
			'step'    => 1,
			'default' => 380,
			'unit'    => 'px',
		),

		array(
			'id'      => 'side-cart-title-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-title-icon-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title icon color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-title-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title background color', 'merchant' ),
			'default' => '#cccccc',
		),

		array(
			'id'      => 'side-cart-content-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-content-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content background color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-content-remove-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content (x) color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-content-remove-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content (x) background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-total-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Total text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-total-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Total background color', 'merchant' ),
			'default' => '#f5f5f5',
		),

		array(
			'id'      => 'side-cart-button-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-button-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color hover', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'side-cart-button-border-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-button-border-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#313131',
		),

		array(
			'id'      => 'side-cart-button-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'side-cart-button-background-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#313131',
		),

	),
) );
