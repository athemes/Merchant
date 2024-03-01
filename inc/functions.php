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
function merchant_get_template_part( $folder_path = '', $name = '', $args = array(), $return_results = false ) {
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
		if ( $return_results ) {
			ob_start();
			include( $template );

			return ob_get_clean();
		}

		return include( $template );
	}
}

/**
 * Check if Botiga theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_botiga_active' ) ) {
	function merchant_is_botiga_active() {
		return defined( 'BOTIGA_VERSION' );
	}
}

/**
 * Check if Divi theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_divi_active' ) ) {
	function merchant_is_divi_active() {
		return defined( 'ET_CORE_VERSION' );
	}
}

/**
 * Check if Avada theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_avada_active' ) ) {
	function merchant_is_avada_active() {
		return defined( 'AVADA_VERSION' );
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

/**
 * Check if Blocksy theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_blocksy_active' ) ) {
	function merchant_is_blocksy_active() {
		return class_exists( 'Blocksy_Manager' );
	}
}

/**
 * Check if Flatsome theme is installed and active.
 *
 * @return bool
 */
if ( ! function_exists( 'merchant_is_flatsome_active' ) ) {
    function merchant_is_flatsome_active() {
        return class_exists( 'Flatsome' );
    }
}

/**
 * Check if any shortcode starts with merchant.
 * If the shortcode is not registered, register it with return null to guarantee it exists.
 */
if ( ! function_exists( 'merchant_modules_shortcode_exists' ) ) {
	function merchant_modules_shortcode_exists() {
		/**
		 * Filter the shortcodes.
		 *
		 * @param array $shortcodes modules shortcodes
		 *
		 * @since 1.8
		 */
		$shortcodes = apply_filters( 'merchant_modules_shortcodes',
			array(
				'merchant_module_stock_scarcity',
				'merchant_module_wait_list',
				'merchant_module_volume_discounts',
				'merchant_module_payment_logos',
				'merchant_module_cart_reserved_timer',
				'merchant_module_product_brand_image',
				'merchant_module_size_chart',
				'merchant_module_reasons_to_buy',
				'merchant_module_recently_viewed_products',
				'merchant_module_trust_badges',
				'merchant_module_advanced_reviews',
				'merchant_module_frequency_bought_together',
				'merchant_module_buy_x_get_y',
				'merchant_module_product_bundles',
			)
		);

		// Loop through the shortcodes and register them if they don't exist.
		array_map( static function ( $shortcode ) {
			if ( ! shortcode_exists( $shortcode ) ) {
				add_shortcode( $shortcode, '__return_null' );
			}
		}, $shortcodes );
	}
}
add_action( 'init', 'merchant_modules_shortcode_exists' );