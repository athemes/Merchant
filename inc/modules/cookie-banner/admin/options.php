<?php
/**
 * Merchant - Cookie Banner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'cookie-banner',
	'fields' => array(

		array(
			'id'             => 'theme',
			'type'           => 'select',
			'title'          => esc_html__( 'Theme', 'merchant' ),
			'options'        => array(
				'merchant-cookie-banner-floating'     => esc_html__( 'Floating', 'merchant' ),
				'merchant-cookie-banner-fixed-bottom' => esc_html__( 'Fixed bottom', 'merchant' ),
			),
			'default'        => 'merchant-cookie-banner-floating',
		),

		array(
			'id'      => 'bar_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Bar text', 'merchant' ),
			'default' => esc_html__( '🍪 We\'re using cookies to give you the best experience on our site.', 'merchant' ),
		),

		array(
			'id'      => 'privacy_policy_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Privacy policy text', 'merchant' ),
			'desc'    => esc_html__( 'The error will be shown if the user tries to go to checkout without accepting terms & conditions.', 'merchant' ),
			'default' => esc_html__( 'Learn More', 'merchant' ),
		),

		array(
			'id'      => 'privacy_policy_url',
			'type'    => 'text',
			'title'   => esc_html__( 'Privacy policy URL', 'merchant' ),
			'default' => get_privacy_policy_url(),
		),

		array(
			'id'      => 'button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button text', 'merchant' ),
			'default' => esc_html__( 'I understand', 'merchant' ),
		),

		array(
			'id'      => 'cookie_duration',
			'type'    => 'number',
			'title'   => esc_html__( 'Cookie duration (days)', 'merchant' ),
			'default' => '365',
		),

		array(
			'id'      => 'close_button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show close \'x\' button', 'merchant' ),
			'default' => 1,
		),

	),
) );

/**
 * Style
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Style', 'merchant' ),
	'module' => 'cookie-banner',
	'fields' => array(

		array(
			'id'        => 'modal_width',
			'type'      => 'range',
			'title'     => esc_html__( 'Modal width', 'merchant' ),
			'min'       => 1,
			'max'       => 2000,
			'step'      => 1,
			'default'   => 750,
			'unit'      => 'px',
			'condition' => array( 'theme', '==', 'floating' ),
		),

		array(
			'id'      => 'modal_height',
			'type'    => 'range',
			'title'   => esc_html__( 'Modal height', 'merchant' ),
			'min'     => 1,
			'max'     => 1000,
			'step'    => 1,
			'default' => 80,
			'unit'    => 'px',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Modal background color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'link_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Link color', 'merchant' ),
			'default' => '#aeaeae',
		),

		array(
			'id'      => 'button_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#dddddd',
		),

		array(
			'id'      => 'button_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button text color', 'merchant' ),
			'default' => '#151515',
		),

	),
) );
