<?php
/**
 * Merchant - Inactive Tab Message
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'inactive-tab-messsage',
	'fields' => array(

		array(
			'id'      => 'message',
			'type'    => 'text',
			'title'   => esc_html__( 'Message', 'merchant' ),
			'desc'    => esc_html__( 'This message will be shown if there\'s no item in the cart when user switches to another tab', 'merchant' ),
			'default' => esc_html__( '✋ Don\'t forget this..', 'merchant' ),
		),

	),
) );
