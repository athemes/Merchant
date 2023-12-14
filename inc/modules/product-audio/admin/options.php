<?php

/**
 * Product Audio Options.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'product-audio',
	'fields' => array(

		array(
			'id'      => 'autoplay',
			'type'    => 'switcher',
			'title'   => esc_html__( 'Autoplay', 'merchant' ),
			'desc'    => esc_html__( 'Autoplay may cause usability issues for some users.', 'merchant' ),
			'default' => false,
		),

	),
) );
