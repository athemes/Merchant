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
			'default' => 1,
		),

		array(
			'id'    => 'text-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button text color', 'merchant' ),
			'default' => '#ffffff',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'    => 'text-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button text color hover', 'merchant' ),
			'default' => '#ffffff',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'    => 'border-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'    => 'border-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#414141',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'    => 'background-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'    => 'background-hover-color',
			'type'  => 'color',
			'title' => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#414141',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'      => 'font-size',
			'type'    => 'range',
			'title'   => esc_html__( 'Font size', 'merchant' ),
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 16,
			'unit'    => 'px',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'      => 'padding_top_bottom',
			'type'    => 'range',
			'title'   => esc_html__( 'Padding Top/Bottom', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'default' => 12,
			'unit'    => 'px',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'      => 'padding_left_right',
			'type'    => 'range',
			'title'   => esc_html__( 'Padding Left/Right', 'merchant' ),
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'default' => 24,
			'unit'    => 'px',
			'condition' => array( 'customize-button', '==', true ),
		),

		array(
			'id'      => 'border-radius',
			'type'    => 'range',
			'title'   => esc_html__( 'Border radius', 'merchant' ),
			'min'     => 0,
			'max'     => 35,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 0,
			'condition' => array( 'customize-button', '==', true ),
		),

	),
) );

// Display Settings
Merchant_Admin_Options::create( array(
	'module' => 'buy-now',
	'title'  => esc_html__( 'Display Settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'display-archive',
			'type'    => 'checkbox',
			'title'   => __( 'Show on product archive', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'display-product',
			'type'    => 'checkbox',
			'title'   => __( 'Show on single product page', 'merchant' ),
			'default' => 1,
		),
		array(
			'id'      => 'display-upsell-related',
			'type'    => 'checkbox',
			'title'   => __( 'Show on upsell and related products', 'merchant' ),
			'default' => 1,
		),

		// Loading position/priority on shop archive.
		array(
			'id'      => 'hook-order-shop-archive',
			'type'    => 'hook_select',
			'title'   => __( 'Loading position and priority on shop archive', 'merchant' ),
			'options' => array(
				'woocommerce_before_shop_loop_item' => __( 'Before shop loop item', 'merchant' ),
				'woocommerce_before_shop_loop_item_title' => __( 'Before shop loop item title', 'merchant' ),
				'woocommerce_shop_loop_item_title' => __( 'Shop loop item title', 'merchant' ),
				'woocommerce_after_shop_loop_item_title' => __( 'After shop loop item title', 'merchant' ),
				'woocommerce_after_shop_loop_item' => __( 'After shop loop item', 'merchant' ),
			),
			'min'     => -999,
			'max'     => 999,
			'step'    => 1,
			'unit'    => '',
			'order' => true,
			'default' => array(
				'hook_name'     => 'woocommerce_after_shop_loop_item',
				'hook_priority' => 10,
			),
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__( 'This is a developer level feature. The buy now button module is "hooked" into a specific location on the shop archive pages. Themes and other plugins might also add additional elements to the same location. By modifying the loading postiion and priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant' ),
		),

		// Loading position/priority on single product.
		array(
			'id'      => 'hook-order-single-product',
			'type'    => 'hook_select',
			'title'   => __( 'Loading position and priority on single product', 'merchant' ),
			'options' => array(
				'woocommerce_before_add_to_cart_button' => __( 'Before add to cart button', 'merchant' ),
				'woocommerce_after_add_to_cart_button' => __( 'After add to cart button', 'merchant' ),
				'woocommerce_before_add_to_cart_quantity' => __( 'Before add to cart quantity', 'merchant' ),
				'woocommerce_after_add_to_cart_quantity' => __( 'After add to cart quantity', 'merchant' ),
			),
			'min'     => -999,
			'max'     => 999,
			'step'    => 1,
			'unit'    => '',
			'order' => true,
			'default' => array(
				'hook_name'     => 'woocommerce_after_add_to_cart_button',
				'hook_priority' => 10,
			),
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__( 'This is a developer level feature. The buy now button module is "hooked" into a specific location on the single product pages. Themes and other plugins might also add additional elements to the same location. By modifying the loading postiion and priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant' ),
		),
	),
) );