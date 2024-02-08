<?php
/**
 * Inactive Tab Message
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
	'module' => 'inactive-tab-message',
	'fields' => array(

		array(
			'id'      => 'message',
			'type'    => 'text',
			'title'   => esc_html__( 'Message', 'merchant' ),
			'desc'    => esc_html__( 'This message will be shown if there\'s no item in the cart when user switches to another tab', 'merchant' ),
			'default' => esc_html__( '✋ Don\'t forget this', 'merchant' ),
		),

		array(
			'id'      => 'abandoned_message',
			'type'    => 'text',
			'title'   => esc_html__( 'Abandoned cart message', 'merchant' ),
			'desc'    => esc_html__( 'This message will be shown if the user left items in the cart when user switches to another tab', 'merchant' ),
			'default' => esc_html__( '✋ You left something in the cart', 'merchant' ),
		),

	),
) );
