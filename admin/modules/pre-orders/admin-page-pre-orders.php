<?php
/**
 * Merchant - Pre Orders
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'pre-orders',
	'fields' => array(

		array(
			'id'      => 'add_button_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Add Button Title', 'merchant' ),
			'default' => esc_html__( 'Pre Order Now!', 'merchant' ),
		),

	),
) );
