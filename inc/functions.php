<?php

/**
 * Essential functions.
 *
 * @package Merchant_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get template part.
 * 
 */
function merchant_get_template_part( $folder_path = '', $name = '', $args = array() ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$plugin_dir = ! defined( 'MERCHANT_PRO_DIR' ) ? MERCHANT_DIR : MERCHANT_PRO_DIR;
	$template	 = '';

	// Look in yourtheme/merchant/folder-path/name.php and yourtheme/merchant/folder-path/name.php.
	if ( $name ) {
		$template = locate_template( array( "merchant/{$folder_path}/{$name}.php" ) );
	}

	// Get default.
	if ( ! $template && $name && file_exists( $plugin_dir . "templates/{$folder_path}/{$name}.php" ) ) {
		$template = $plugin_dir . "templates/{$folder_path}/{$name}.php";
	}

	/**
	 * Hook: 'merchant_get_template_part'
	 * 
	 * @since 1.0
	 */
	$template = apply_filters( 'merchant_get_template_part', $template, $folder_path, $name );

	if ( $template ) {
		return include( $template );
	}
}
