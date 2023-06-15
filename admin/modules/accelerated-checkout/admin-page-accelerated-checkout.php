<?php
/**
 * Merchant Accelerated Checkout
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'accelerated-checkout',
	'fields' => array(

		array(
			'type'    => 'content',
			'content' => esc_html__( 'If increasing average order value is not important for your store, send your customers directly to checkout instead of cart.', 'merchant' ),
		),

	),
) );
