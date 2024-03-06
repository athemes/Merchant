<?php

/**
 * Sticky Add To Cart Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'sticky-add-to-cart',
	'fields' => array(

		// Position.
		array(
			'id'        => 'position',
			'type'      => 'select',
			'title'     => esc_html__( 'Position', 'merchant' ),
			'options'   => array(
				'position-top'     => esc_html__( 'Top', 'merchant' ),
				'position-bottom' => esc_html__( 'Bottom', 'merchant' ),
			),
			'default'   => 'position-bottom',
		),

		// Display after x amount of pixels.
		array(
			'id'        => 'display_after_amount',
			'type'      => 'range',
			'title'     => esc_html__( 'Display after amount of scroll', 'merchant' ),
			'desc'      => esc_html__( 'Start the sticky effect when the specified amount of pixels is scrolled.', 'merchant' ),
			'min'       => 0,
			'max'       => 1500,
			'step'      => 1,
			'unit'      => 'px',
			'default'   => 100,
		),

		// Hide Product Image.
		array(
			'id'      => 'hide_product_image',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Hide product image', 'merchant' ),
			'default' => 0,
		),

		// Hide Product Title.
		array(
			'id'      => 'hide_product_title',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Hide product title', 'merchant' ),
			'default' => 0,
		),

		// Hide Product Price.
		array(
			'id'      => 'hide_product_price',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Hide product price', 'merchant' ),
			'default' => 0,
		),

		// Elements spacing.
		array(
			'id'      => 'elements_spacing',
			'type'    => 'range',
			'title'   => esc_html__( 'Elements spacing', 'merchant' ),
			'min'     => 0,
			'max'     => 80,
			'step'    => 1,
			'unit'    => 'px',
			'default' => 35,
		),

		// Hide when scroll to top.
		array(
			'id'      => 'scroll_hide',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Hide when scroll to top', 'merchant' ),
			'default' => 0,
		),

		// Visibility.
		array(
			'id'        => 'visibility',
			'type'      => 'select',
			'title'     => esc_html__( 'Visibility', 'merchant' ),
			'options'   => array(
				'all'     => esc_html__( 'Show on all devices', 'merchant' ),
				'desktop' => esc_html__( 'Desktop only', 'merchant' ),
				'mobile'  => esc_html__( 'Mobile/tablet only', 'merchant' ),
			),
			'default'   => 'all',
		),

		// Allow 3rd party plugins hook.
		array(
			'id'      => 'allow_third_party_plugins',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Allow third-party plugin content', 'merchant' ),
			'desc'    => esc_html__( 'Control whether third-party plugin content should be rendered in the sticky add to cart content area.', 'merchant' ),
			'default' => 0,
		),

	),
) );

// Style Settings
Merchant_Admin_Options::create( array(
	'module'    => 'sticky-add-to-cart',
	'title'     => esc_html__( 'Style', 'merchant' ),
	'fields'    => array(

		// Border color.
		array(
			'id'      => 'border_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Border color', 'merchant' ),
			'default' => '#E2E2E2',
		),

		// Background color.
		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#FFFFFF',
		),
		
		// Content color.
		array(
			'id'      => 'content_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Content color', 'merchant' ),
			'default' => '#212121',
		),

		// Title color.
		array(
			'id'      => 'title_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		// Button Background color.
		array(
			'id'      => 'button_bg_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		// Button Background color (hover).
		array(
			'id'      => 'button_bg_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color (hover)', 'merchant' ),
			'default' => '#757575',
		),

		// Button color.
		array(
			'id'      => 'button_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color', 'merchant' ),
			'default' => '#FFF',
		),

		// Buton color (hover).
		array(
			'id'      => 'button_color_hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color (hover)', 'merchant' ),
			'default' => '#FFF',
		),

	),
) );
