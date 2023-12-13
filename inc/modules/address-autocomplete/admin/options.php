<?php

/**
 * Add To Cart Text Options.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Settings
Merchant_Admin_Options::create( array(
	'module' => Merchant_Address_Autocomplete::MODULE_ID,
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'fields' => array(
		array(
			'id'      => 'api_key',
			'type'    => 'text',
			'title'   => esc_html__( 'API Key', 'merchant' ),
			'desc'    => esc_html__( 'Add Google places API Key', 'merchant' ),
		),
	),
) );