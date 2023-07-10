<?php
/**
 * Merchant - Cart Count Favicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'cart-count-favicon',
	'fields' => array(

		array(
			'id'      => 'shape',
			'type'    => 'select',
			'title'   => esc_html__( 'Shape', 'merchant' ),
			'options' => array(
				'circle'    => esc_html__( 'Circle', 'merchant' ),
				'rectangle' => esc_html__( 'Rectangle', 'merchant' ),
			),
			'default' => 'circle',
		),

		array(
			'id'      => 'position',
			'type'    => 'select',
			'title'   => esc_html__( 'Location of the bullet', 'merchant' ),
			'options' => array(
				'up-left'    => esc_html__( 'Top Left', 'merchant' ),
				'up-right'   => esc_html__( 'Top Right', 'merchant' ),
				'down-left'  => esc_html__( 'Bottom Left', 'merchant' ),
				'down-right' => esc_html__( 'Bottom Right', 'merchant' ),
			),
			'default' => 'down-left',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background Color', 'merchant' ),
			'default' => '#ff0101',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text Color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'delay',
			'type'    => 'select',
			'title'   => esc_html__( 'Animation repetition interval', 'merchant' ),
			'options' => array(
				'1' => esc_html__( '1 Second', 'merchant' ),
				'2' => esc_html__( '2 Seconds', 'merchant' ),
				'3' => esc_html__( '3 Seconds', 'merchant' ),
				'5' => esc_html__( '5 Seconds', 'merchant' ),
				'0' => esc_html__( 'Never', 'merchant' ),
			),
			'default' => '0',
		),

	),
) );
