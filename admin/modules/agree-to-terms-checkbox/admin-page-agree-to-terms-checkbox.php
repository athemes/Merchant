<?php
/**
 * Merchant - Agree to Terms Checkout
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'agree-to-terms-checkbox',
	'fields' => array(

		array(
			'id'       => 'text',
			'type'     => 'text',
			'title'    => esc_html__( 'Checkbox text on checkbox page', 'merchant' ),
			'desc'     => esc_html__( "Please keep the ##terms_link## magic keyword there, it will be replaced with the link you set below.", 'merchant' ),
			'default'  => 'I have read, understood and agreed with your terms and conditions. <a href="##terms_link##" target="_blank">terms and conditions</a>',
			'sanitize' => 'wp_kses_post'
		),

		array(
			'id'    => 'link',
			'type'  => 'text',
			'title' => esc_html__( 'Terms and conditions link', 'merchant' ),
			'desc'  => esc_html__( 'The link to your Terms and Conditions page.', 'merchant' ),
		),

		array(
			'id'      => 'warning_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Checkbox warning text on checkbox page', 'merchant' ),
			'default' => esc_html__( 'You must read and accept the terms and conditions to checkbox.', 'merchant' ),
			'desc'    => esc_html__( 'The error that will be shown if the user tries to go to checkbox without accepting your terms and conditions.', 'merchant' ),
		),

	),
) );
