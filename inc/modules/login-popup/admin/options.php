<?php

/**
 * Login Popup Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'login-popup',
	'fields' => array(

		array(
			'id'      => 'shortcode',
			'type'    => 'text_readonly',
			'title'   => esc_html__( 'Shortcode', 'merchant' ),
			'desc'    => esc_html__( 'Copy this shortcode into pages to show login popup.', 'merchant' ),
			'default' => '[merchant_login_popup]',
		),

		array(
			'id'      => 'login_link_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Login link text', 'merchant' ),
			'desc'    => esc_html__( 'Only visible to logout users.', 'merchant' ),
			'default' => esc_html__( 'Login', 'merchant' ),
		),

		array(
			'id'      => 'show_welcome_message',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Show welcome message', 'merchant' ),
			'desc'    => esc_html__( 'Show a welcome message when the user it\'s logged in.', 'merchant' ),
			'default' => true,
		),

		array(
			'id'        => 'welcome_message_text',
			'type'      => 'text',
			'title'     => esc_html__( 'Welcome message text', 'merchant' ),
			'desc'      => esc_html__( 'You can use the following tags: {user_firstname}, {user_lastname}, {user_email}, {user_login}, {display_name}', 'merchant' ),
			/* Translators: 1. Display name */
			'default'   => sprintf( esc_html__( 'Welcome %s', 'merchant' ), '{display_name}' ),
			'condition' => array( 'show_welcome_message', '==', '1' ),
		),

		array(
			'id'      => 'login-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Login text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'login-text-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Login text color hover', 'merchant' ),
			'default' => '#515151',
		),

		array(
			'id'      => 'dropdown-link-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Dropdown link color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'dropdown-link-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Dropdown link color hover', 'merchant' ),
			'default' => '#515151',
		),

		array(
			'id'      => 'dropdown-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Dropdown background color', 'merchant' ),
			'default' => '#ffffff',
		),

	),
) );

// Popup Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Popup Settings', 'merchant' ),
	'module' => 'login-popup',
	'fields' => array(

		array(
			'id'      => 'popup-width',
			'type'    => 'range',
			'title'   => esc_html__( 'Popup width', 'merchant' ),
			'min'     => 0,
			'max'     => 1000,
			'step'    => 1,
			'default' => 400,
			'unit'    => 'px',
		),

		array(
			'id'      => 'popup-title-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Title color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-icon-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Icon color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-link-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Link color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-button-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'popup-button-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button color hover', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'popup-button-border-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button border color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-button-border-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button border color hover', 'merchant' ),
			'default' => '#757575',
		),

		array(
			'id'      => 'popup-button-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-button-background-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Button background color hover', 'merchant' ),
			'default' => '#757575',
		),

		array(
			'id'      => 'popup-link-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Link color hover', 'merchant' ),
			'default' => '#515151',
		),

		array(
			'id'      => 'popup-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Background color', 'merchant' ),
			'default' => '#ffffff',
		),

		array(
			'id'      => 'popup-footer-text-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Footer text color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-footer-link-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Footer link color', 'merchant' ),
			'default' => '#212121',
		),

		array(
			'id'      => 'popup-footer-link-color-hover',
			'type'    => 'color',
			'title'   => esc_html__( 'Footer link color hover', 'merchant' ),
			'default' => '#515151',
		),

		array(
			'id'      => 'popup-footer-background-color',
			'type'    => 'color',
			'title'   => esc_html__( 'Footer background color', 'merchant' ),
			'default' => '#f5f5f5',
		),

	),
) );
