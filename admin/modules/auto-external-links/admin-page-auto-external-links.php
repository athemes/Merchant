<?php
/**
 * Merchant - Auto External Links
 */

/**
 * Settings
 */
Merchant_Admin_Options::create( array(
	'title'  => esc_html__( 'Settings', 'merchant' ),
	'module' => 'auto-external-links',
	'fields' => array(

		array(
			'type'    => 'content',
			'content' => esc_html__( 'All the external links on your store will be opened in a new browser tab by enabling this module. It prevents visitors from navigating away from your online store. Automatically open external links in a new browser tab and prevent users from navigating away from your online store.', 'merchant' ),
		),

	),
) );
