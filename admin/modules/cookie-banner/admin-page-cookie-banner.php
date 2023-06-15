<?php
/**
 * Merchant - Cookie Banner
 */

/**
 * General and Design settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'General and Design settings', 'merchant' ),
	'module' => 'cookie-banner',
	'fields' => array(

		array(
			'id'             => 'banner_theme',
			'type'           => 'select',
			'title'          => esc_html__( 'Theme', 'merchant' ),
			'options'        => array(
				'floating'     => esc_html__( 'Floating', 'merchant' ),
				'fixed-bottom' => esc_html__( 'Fixed Bottom', 'merchant' ),
			),
			'default'        => 'floating',
		),

		array(
			'id'      => 'background_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Cookie banner background color', 'merchant' ),
			'default' => '#000000',
		),

		array(
			'id'      => 'text_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Cookie banner text color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'link_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Cookie banner link color', 'merchant' ),
			'default' => '#aeaeae',
		),

		array(
			'id'      => 'button_background_color',
			'type'    => 'color',
			'title'   => esc_html__( '"Accept" button background color', 'merchant' ),
			'default' => '#dddddd',
		),

		array(
			'id'      => 'button_text_color',
			'type'    => 'color',
			'title'   => esc_html__( '"Accept" button text color', 'merchant' ),
			'default' => '#151515',
		),

		array(
			'id'      => 'learn_more_text',
			'type'    => 'text',
			'title'   => esc_html__( '"Learn More"', 'merchant' ),
			'default' => esc_html__( 'Learn More', 'merchant' ),
		),

		array(
			'id'      => 'privacy_policy_url',
			'type'    => 'text',
			'title'   => esc_html__( 'URL of your Privacy Policy', 'merchant' ),
			'desc'    => esc_html__( 'Every site needs to have a privacy policy. Until you add a link to that page, the "Learn more" message will not be shown.', 'merchant' ),
			'default' => get_privacy_policy_url(),
		),

	),
) );

/**
 * Cookie Banner
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Cookie Banner', 'merchant' ),
	'module' => 'cookie-banner',
	'fields' => array(

		array(
			'id'      => 'banner_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Text on cookie banner', 'merchant' ),
			'default' => esc_html__( 'We use cookies to improve your experience and track website usage.', 'merchant' ),
		),

		array(
			'id'      => 'accept_text',
			'type'    => 'text',
			'title'   => esc_html__( '"I Accept"', 'merchant' ),
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
			'type'    => 'select',
			'title'   => esc_html__( 'Close X Button', 'merchant' ),
			'options' => array(
				'show'  => esc_html__( 'Show Button', 'merchant' ),
				'hide'  => esc_html__( 'Hide Button', 'merchant' ),
			),
			'default' => 'hide',
		),

	),
) );
