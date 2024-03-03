<?php

/**
 * Recently Viewed Products Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => __( 'Settings', 'merchant' ),
	'module' => Merchant_Recently_Viewed_Products::MODULE_ID,
	'fields' => array(

		// Title.
		array(
			'id'        => 'title',
			'type'      => 'text',
			'title'     => __( 'Title', 'merchant' ),
			'default'   => __( 'Recently Viewed', 'merchant' ),
		),

		// Title HTML Tag.
		array(
			'id'        => 'title_tag',
			'type'      => 'select',
			'title'     => __( 'Title HTML tag', 'merchant' ),
			'options'   => array(
				'h1'  => __( 'H1', 'merchant' ),
				'h2'  => __( 'H2', 'merchant' ),
				'h3'  => __( 'H3', 'merchant' ),
				'h4'  => __( 'H4', 'merchant' ),
				'h5'  => __( 'H5', 'merchant' ),
				'h6'  => __( 'H6', 'merchant' ),
				'div' => __( 'div', 'merchant' ),
			),
			'default'   => 'h2',
		),

		// Hide Title.
		array(
			'id'      => 'hide_title',
			'type'    => 'switcher',
			'title'   => __( 'Hide title', 'merchant' ),
			'desc'    => __( 'Hide the title visually only. The title will continue being rendered in the HTML source code.', 'merchant' ),
			'default' => 0,
		),

		// Slider Style.
		array(
			'id'      => 'slider',
			'type'    => 'switcher',
			'title'   => __( 'Slider style', 'merchant' ),
			'desc'    => __( 'Display the products grid as a slider.' , 'merchant' ),
			'default' => 0,
		),

		// Slider Navigation.
		array(
			'id'      => 'slider_nav',
			'type'    => 'radio',
			'title'   => __( 'Slider navigation', 'merchant' ),
			'options' => array(
				'always-show'  => __( 'Always Show', 'merchant' ),
				'on-hover'     => __( 'Show On Hover', 'merchant' ),
			),
			'default' => 'on-hover',
			'condition' => array( 'slider', '==', true ),
		),

		// Number of Products.
		array(
			'id'        => 'posts_per_page',
			'type'      => 'range',
			'title'     => __( 'Products', 'merchant' ),
			'desc'      => __( 'Controls the number of products to display in the products grid.', 'merchant' ),
			'min'       => 1,
			'max'       => 30,
			'step'      => 1,
			'unit'      => '',
			'default'   => 10,
		),

		// Number of Columns.
		array(
			'id'        => 'columns',
			'type'      => 'range',
			'title'     => __( 'Columns', 'merchant' ),
			'desc'      => __( 'Controls the number of columns to display in the products grid.', 'merchant' ),
			'min'       => 1,
			'max'       => 6,
			'step'      => 1,
			'unit'      => '',
			'default'   => 3,
		),

		// Columns Gap.
		array(
			'id'        => 'columns_gap',
			'type'      => 'range',
			'title'     => __( 'Columns gap', 'merchant' ),
			'desc'      => __( 'Controls gap between each column in the products grid.', 'merchant' ),
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'unit'      => 'px',
			'default'   => 15,
		),

		// Order by.
		array(
			'id'        => 'orderby',
			'type'      => 'select',
			'title'     => __( 'Order by', 'merchant' ),
			'options'   => array(
				'id'         => __( 'ID', 'merchant' ),
				'rand'       => __( 'Random', 'merchant' ),
				'title'      => __( 'TItle', 'merchant' ),
				'date'       => __( 'Date', 'merchant' ),
				'modified'   => __( 'Modified date', 'merchant' ),
				'menu_order' => __( 'Menu order', 'merchant' ),
			),
			'default'   => 'rand',
		),

		// Order.
		array(
			'id'        => 'order',
			'type'      => 'select',
			'title'     => __( 'Order', 'merchant' ),
			'options'   => array(
				'asc'        => __( 'Asc', 'merchant' ),
				'desc'       => __( 'Desc', 'merchant' ),
			),
			'default'   => 'desc',
		),

		// Hook Order.
		array(
			'id'        => 'hook_order',
			'type'      => 'range',
			'title'     => __( 'Loading priority', 'merchant' ),
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'unit'      => '',
			'default'   => 20,
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__( 'This is a developer level feature. The recently viewed product module is "hooked" into a specific location on the page. Themes and other plugins might also add additional elements to the same location. By modifying the hook priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant' ),
		),

		// colors.

		// Title color.
		array(
			'id'      => 'title_color',
			'type'    => 'color',
			'title'   => __( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		// Navigation Icon color.
		array(
			'id'      => 'navigation_icon_color',
			'type'    => 'color',
			'title'   => __( 'Navigation icon color', 'merchant' ),
			'default' => '#FFF',
			'condition' => array( 'slider', '==', true ),
		),

		// Naviation color.
		array(
			'id'      => 'navigation_color',
			'type'    => 'color',
			'title'   => __( 'Navigation color', 'merchant' ),
			'default' => '#212121',
			'condition' => array( 'slider', '==', true ),
		),

		// Naviation color (hover).
		array(
			'id'      => 'navigation_color_hover',
			'type'    => 'color',
			'title'   => __( 'Navigation color (hover)', 'merchant' ),
			'default' => '#757575',
			'condition' => array( 'slider', '==', true ),
		),

	),
) );

// Shortcode
$merchant_module_id = Merchant_Recently_Viewed_Products::MODULE_ID;
Merchant_Admin_Options::create( array(
	'module' => $merchant_module_id,
	'title'  => esc_html__( 'Use shortcode', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'use_shortcode',
			'type'    => 'switcher',
			'title'   => __( 'Use shortcode', 'merchant' ),
			'default' => 0,
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__( 'If you are using a page builder or a theme that supports shortcodes, then you can output the module using the shortcode above. This might be useful if, for example, you find that you want to control the position of the module output more precisely than with the module settings. Note that the shortcodes can only be used on single product pages.', 'merchant' ),
		),
		array(
			'id'        => 'shortcode_text',
			'type'      => 'text_readonly',
			'title'     => esc_html__( 'Shortcode text', 'merchant' ),
			'default'   => '[merchant_module_' . str_replace( '-', '_', $merchant_module_id ) . ']',
			'condition' => array( 'use_shortcode', '==', '1' ),
		),
	),
) );