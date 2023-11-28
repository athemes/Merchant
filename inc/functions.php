<?php

/**
 * Essential functions.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get template part.
 *
 */
function merchant_get_template_part( $folder_path = '', $name = '', $args = array(), $return = false ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$folder_path = ! empty( $folder_path ) ? "/$folder_path/" : '';

	$template = '';

	// Look in yourtheme/merchant/folder-path/name.php and yourtheme/merchant/folder-path/name.php.
	if ( $name ) {
		$template = locate_template( array( "merchant{$folder_path}{$name}.php" ) );
	}

	// Get default.
	if ( ! $template && $name ) {
		// Try to get it from the PRO dir if it exists.
		if ( defined( 'MERCHANT_PRO_DIR' ) && file_exists( MERCHANT_PRO_DIR . "templates{$folder_path}{$name}.php" ) ) {
			$template = MERCHANT_PRO_DIR . "templates{$folder_path}{$name}.php";

		// Otherwise take it from the base dir.
		} elseif ( file_exists( MERCHANT_DIR . "templates{$folder_path}{$name}.php" ) ) {
			$template = MERCHANT_DIR . "templates{$folder_path}{$name}.php";
		}
	}

	/**
	 * Hook: 'merchant_get_template_part'
	 *
	 * @since 1.0
	 */
	$template = apply_filters( 'merchant_get_template_part', $template, $folder_path, $name );


	if ( $template ) {
		// Whether to return template HTML as string or to echo it
		if ( $return ) {
			ob_start();
			include( $template );

			return ob_get_clean();
		} else {
			return include( $template );
		}
	}
}

/**
 * Check if Kadence theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_kadence_active' ) ) {
	function merchant_is_kadence_active() {
		return class_exists( '\Kadence\Theme' );
	}
}

/**
 * Check if OceanWP theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_oceanwp_active' ) ) {
	function merchant_is_oceanwp_active() {
		return class_exists( 'OCEANWP_Theme_Class' );
	}
}
