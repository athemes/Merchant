<?php
/**
 * Cart Count Favicon
 * 
 * @package Merchant
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
				'up-left'    => esc_html__( 'Top left', 'merchant' ),
				'up-right'   => esc_html__( 'Top right', 'merchant' ),
				'down-left'  => esc_html__( 'Bottom left', 'merchant' ),
				'down-right' => esc_html__( 'Bottom right', 'merchant' ),
			),
			'default' => 'up-right',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#ff0101',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'animation',
			'type'    => 'select',
			'title'   => esc_html__( 'Animation', 'merchant' ),
			'options' => array(
				'none' => esc_html__( 'None', 'merchant' ),
				'slide'    => esc_html__( 'Slide', 'merchant' ),
				'fade'   => esc_html__( 'Fade', 'merchant' ),
				'pop'  => esc_html__( 'Pop', 'merchant' ),
				'popFade' => esc_html__( 'popFade', 'merchant' ),
			),
			'default' => 'slide',
		),

		array(
			'id'      => 'delay',
			'type'    => 'select',
			'title'   => esc_html__( 'Animation repetition interval', 'merchant' ),
			'options' => array(
				'1s' => esc_html__( '1 second', 'merchant' ),
				'2s' => esc_html__( '2 seconds', 'merchant' ),
				'3s' => esc_html__( '3 seconds', 'merchant' ),
				'5s' => esc_html__( '5 seconds', 'merchant' ),
				'0s' => esc_html__( 'Never', 'merchant' ),
			),
			'default' => '0s',
		),

	),
) );
