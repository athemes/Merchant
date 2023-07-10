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
				'floating'     => esc_html__( 'Floating', 'merchant' ),
				'fixed-bottom' => esc_html__( 'Fixed Bottom', 'merchant' ),
			),
			'default'        => 'floating',
		),

		array(
			'id'      => 'bar_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Bar Text', 'merchant' ),
			'default' => esc_html__( '🍪 We\'re using cookies to give you the best experience on our site.', 'merchant' ),
		),

		array(
			'id'      => 'privacy_policy_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Privacy Policy Text', 'merchant' ),
			'desc'    => esc_html__( 'The error will be shown if the user tries to go to checkout without accepting terms & conditions.', 'merchant' ),
			'default' => esc_html__( 'Learn More', 'merchant' ),
		),

		array(
			'id'      => 'privacy_policy_url',
			'type'    => 'text',
			'title'   => esc_html__( 'Privacy Policy URL', 'merchant' ),
			'default' => get_privacy_policy_url(),
		),

		array(
			'id'      => 'button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button Text', 'merchant' ),
			'default' => esc_html__( 'I Understand', 'merchant' ),
		),

		array(
			'id'      => 'cookie_duration',
			'type'    => 'number',
			'title'   => esc_html__( 'Cookie Duration (days)', 'merchant' ),
			'default' => esc_html__( '365', 'merchant' ),
		),

		array(
			'id'      => 'close_button',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show Close ‘x’ Button', 'merchant' ),
			'default' => 1,
		),

	),
) );

/**
 * Design
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Design', 'merchant' ),
	'module' => 'cookie-banner',
	'fields' => array(

		array(
			'id'        => 'modal_width',
			'type'      => 'range',
			'title'     => esc_html__( 'Modal Width', 'merchant' ),
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
			'title'   => esc_html__( 'Modal Height', 'merchant' ),
			'min'     => 1,
			'max'     => 1000,
			'step'    => 1,
			'default' => 80,
			'unit'    => 'px',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Modal Background Color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text Color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'link_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Link Color', 'merchant' ),
			'default' => '#aeaeae',
		),

		array(
			'id'      => 'button_background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button Background Color', 'merchant' ),
			'default' => '#dddddd',
		),

		array(
			'id'      => 'button_text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button Text Color', 'merchant' ),
			'default' => '#151515',
		),

	),
) );
