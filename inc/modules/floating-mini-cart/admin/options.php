<?php

/**
 * Floating Mini Cart Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Icon Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Icon Settings', 'merchant' ),
	'module' => 'floating-mini-cart',
	'fields' => array(

		array(
			'id'      => 'display',
			'type'    => 'select',
			'title'   => esc_html__( 'Display', 'merchant' ),
			'options' => array(
				'cart-not-empty' => esc_html__( 'When cart is not empty', 'merchant' ),
				'always'         => esc_html__( 'Always', 'merchant' ),
			),
			'default' => 'always',
		),

		array(
			'id'      => 'icon',
			'type'    => 'choices',
			'title'   => esc_html__( 'Icon', 'merchant' ),
			'options' => array(
				'cart-icon-1' => MERCHANT_URI . 'assets/images/icons/floating-mini-cart/admin/cart-icon-1.svg',
				'cart-icon-2' => MERCHANT_URI . 'assets/images/icons/floating-mini-cart/admin/cart-icon-2.svg',
				'cart-icon-3' => MERCHANT_URI . 'assets/images/icons/floating-mini-cart/admin/cart-icon-3.svg',
				'cart-icon-4' => MERCHANT_URI . 'assets/images/icons/floating-mini-cart/admin/cart-icon-4.svg',
				'cart-icon-5' => MERCHANT_URI . 'assets/images/icons/floating-mini-cart/admin/cart-icon-5.svg',
			),
			'default' => 'cart-icon-1',
		),

		array(
			'id'      => 'icon-position',
			'type'    => 'radio',
			'title'   => esc_html__( 'Position', 'merchant' ),
			'options' => array(
				'left'  => esc_html__( 'Left', 'merchant' ),
				'right' => esc_html__( 'Right', 'merchant' ),
			),
			'default' => 'right',
		),

		array(
			'id'      => 'icon-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Icon size', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 25,
			'unit'    => 'px',
		),

		array(
			'id'      => 'corner-offset',
			'type'    => 'range',
			'title'   => esc_html__( 'Corner offset', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 30,
			'unit'    => 'px',
		),

		array(
			'id'      => 'border-radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'min'     => 0,
			'max'     => 35,
			'step'    => 1,
			'default' => 35,
			'unit'    => 'px',
		),

		array(
			'id'      => 'icon-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'counter-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Counter color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'counter-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Counter background color', 'merchant' ),
			'default' => '#757575',
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
