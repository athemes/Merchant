<?php

/**
 * Product Navigation Links Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Product_Navigation_Links::MODULE_ID,
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'text',
			'type'    => 'select',
			'title'   => esc_html__( 'Navigation text', 'merchant' ),
			'options' => array(
				'titles'       => esc_html__( 'Show product titles', 'merchant' ),
				'navigational' => esc_html__( 'Show \'Previous\' and \'Next\' ', 'merchant' ),
			),
			'default' => 'titles',
		),

		array(
			'id'      => 'placement',
			'type'    => 'select',
			'title'   => esc_html__( 'Placement', 'merchant' ),
			'options' => array(
				'bottom'                 => esc_html__( 'Bottom of the page', 'merchant' ),
				'top'                    => esc_html__( 'Top of the page', 'merchant' ),
				'bottom-product-summary' => esc_html__( 'After product summary', 'merchant' ),
			),
			'default' => 'bottom',
		),

		array(
			'id'      => 'priority',
			'type'    => 'number',
			'title'   => esc_html__( 'Loading priority', 'merchant' ),
			'default' => 30,
		),
		array(
			'type'    => 'warning',
			'content' => esc_html__( 'This is a developer level feature. The product navigation links module is "hooked" into a specific location on the page. Themes and other plugins might also add additional elements to the same location. By modifying the hook priority, you have the ability to customize the placement of this element on that particular location. A lower number = a higher priority, so the module will appear higher on the page.', 'merchant' ),
		),      

	),
) );


// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Product_Navigation_Links::MODULE_ID,
	'title'  => esc_html__( 'Style Settings', 'merchant' ),
	'fields' => array(

		array(
			'id'      => 'text_decoration',
			'type'    => 'buttons',
			'title'   => esc_html__( 'Text decoration', 'merchant' ),
			'options' => array(
				'none'         => esc_html__( 'None', 'merchant' ),
				'underline'    => esc_html__( 'Underline', 'merchant' ),
				'line-through' => esc_html__( 'Line-through', 'merchant' ),
				'overline'     => esc_html__( 'Overline', 'merchant' ),
			),
			'default' => 'none',
		),


		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'text_hover_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text hover color', 'merchant' ),
			'default' => '#757575',
		),

		array(
			'id'      => 'justify_content',
			'type'    => 'buttons',
			'title'   => esc_html__( 'Justify content', 'merchant' ),
			'options' => array(
				'space-between' => esc_html__( 'Space between', 'merchant' ),
				'space-around'  => esc_html__( 'Space around', 'merchant' ),
				'space-evenly'  => esc_html__( 'Space evenly', 'merchant' ),
			),
			'default' => 'space-between',
		),

		array(
			'id'      => 'margin_top',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin top', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

		array(
			'id'      => 'margin_bottom',
			'type'    => 'range',
			'title'   => esc_html__( 'Margin bottom', 'merchant' ),
			'min'     => 0,
			'max'     => 250,
			'step'    => 1,
			'default' => 20,
			'unit'    => 'px',
		),

	),
) );
