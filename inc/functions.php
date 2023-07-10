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
function merchant_get_template_part( $module_slug, $name = '', $args = array() ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$plugin_dir = ! defined( 'MERCHANT_PRO_DIR' ) ? MERCHANT_DIR : MERCHANT_PRO_DIR;
	$template	 = '';

	// Look in yourtheme/module-slug/name.php and yourtheme/merchant/module-slug/name.php.
	if ( $name ) {
		$template = locate_template( array( "merchant/{$module_slug}/{$name}.php" ) );
	}

	// Get default.
	if ( ! $template && $name && file_exists( $plugin_dir . "inc/modules/{$module_slug}/templates/{$name}.php" ) ) {
		$template = $plugin_dir . "inc/modules/{$module_slug}/templates/{$name}.php";
	}

	/**
	 * Hook: 'merchant_get_template_part'
	 * Allow 3rd party plugin filter template file from their plugin.
	 * 
	 * @since 1.0
	 */
	$template = apply_filters( 'merchant_get_template_part', $template, $module_slug, $name );

	if ( $template ) {
		return include( $template );
	}
}
