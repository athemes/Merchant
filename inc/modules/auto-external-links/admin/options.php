<?php
/**
 * Auto External Links
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
	'module' => 'auto-external-links',
	'fields' => array(

		array(
			'type'    => 'content',
			'content' => esc_html__( 'All the external links on your store will be opened in a browser tab by enabling this module. It prevents visitors from accidentally navigating away from your online store.', 'merchant' ),
		),

	),
) );
