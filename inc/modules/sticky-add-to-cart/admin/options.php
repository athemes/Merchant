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
	'title'  => __( 'Settings', 'merchant' ),
	'module' => 'sticky-add-to-cart',
	'fields' => array(

		// Position.
		array(
			'id'        => 'position',
			'type'      => 'select',
			'title'     => __( 'Position', 'merchant' ),
			'options'   => array(
				'position-top'     => __( 'Top', 'merchant' ),
				'position-bottom' => __( 'Bottom', 'merchant' ),
			),
			'default'   => 'position-bottom',
		),

		// Display after x amount of pixels.
		array(
			'id'        => 'display_after_amount',
			'type'      => 'range',
			'title'     => __( 'Display after amount of scroll', 'merchant' ),
			'desc'      => __( 'Start the sticky effect when the specified amount of pixels is scrolled.', 'merchant' ),
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
			'title'   => __( 'Hide product image', 'merchant' ),
			'default' => 0,
		),

		// Hide Product Title.
		array(
			'id'      => 'hide_product_title',
			'type'    => 'switcher',
			'title'   => __( 'Hide product title', 'merchant' ),
			'default' => 0,
		),

		// Hide Product Price.
		array(
			'id'      => 'hide_product_price',
			'type'    => 'switcher',
			'title'   => __( 'Hide product price', 'merchant' ),
			'default' => 0,
		),

		// Elements spacing.
		array(
			'id'      => 'elements_spacing',
			'type'    => 'range',
			'title'   => __( 'Elements spacing', 'merchant' ),
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
			'title'   => __( 'Hide when scroll to top', 'merchant' ),
			'default' => 0,
		),

		// Visibility.
		array(
			'id'        => 'visibility',
			'type'      => 'select',
			'title'     => __( 'Visibility', 'merchant' ),
			'options'   => array(
				'all'     => __( 'Show on all devices', 'merchant' ),
				'desktop' => __( 'Desktop only', 'merchant' ),
				'mobile'  => __( 'Mobile/tablet only', 'merchant' ),
			),
			'default'   => 'desktop',
		),

		// Allow 3rd party plugins hook.
		array(
			'id'      => 'allow_third_party_plugins',
			'type'    => 'switcher',
			'title'   => __( 'Allow third-party plugin content', 'merchant' ),
			'desc'    => __( 'Control whether third-party plugin content should be rendered in the sticky add to cart content area.', 'merchant' ),
			'default' => 0,
		),

	),
) );

// Style Settings
Merchant_Admin_Options::create( array(
	'module'    => 'sticky-add-to-cart',
	'title'     => __( 'Style', 'merchant' ),
	'fields'    => array(

		// Border color.
		array(
			'id'      => 'border_color',
			'type'    => 'color',
			'title'   => __( 'Border color', 'merchant' ),
			'default' => '#E2E2E2',
		),

		// Background color.
		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => __( 'Background color', 'merchant' ),
			'default' => '#FFFFFF',
		),
		
		// Content color.
		array(
			'id'      => 'content_color',
			'type'    => 'color',
			'title'   => __( 'Content color', 'merchant' ),
			'default' => '#212121',
		),

		// Title color.
		array(
			'id'      => 'title_color',
			'type'    => 'color',
			'title'   => __( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		// Button Background color.
		array(
			'id'      => 'button_bg_color',
			'type'    => 'color',
			'title'   => __( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		// Button Background color (hover).
		array(
			'id'      => 'button_bg_color_hover',
			'type'    => 'color',
			'title'   => __( 'Button background color (hover)', 'merchant' ),
			'default' => '#757575',
		),

		// Button color.
		array(
			'id'      => 'button_color',
			'type'    => 'color',
			'title'   => __( 'Button color', 'merchant' ),
			'default' => '#FFF',
		),

		// Buton color (hover).
		array(
			'id'      => 'button_color_hover',
			'type'    => 'color',
			'title'   => __( 'Button color (hover)', 'merchant' ),
			'default' => '#FFF',
		),

	),
) );
