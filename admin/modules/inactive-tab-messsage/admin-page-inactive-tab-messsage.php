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
			'desc'    => esc_html__( 'The message that will show in the browser tab\'s title when the visitor changes to another tab.', 'merchant' ),
			'default' => esc_html__( '☞ Donʼt forget this...', 'merchant' ),
		),

	),
) );
