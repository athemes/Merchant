<?php

/**
 * Floating Mini Cart
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Todo: Delete this entire File in a future update. Now keeping it to avoid any unwanted error.

/**
 * Floating mini cart class.
 *
 */
class Merchant_Floating_Mini_Cart extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'floating-mini-cart';

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {

		// Module id.
		$this->module_id = self::MODULE_ID;

		// WooCommerce only.
		$this->wc_only = true;

		// Parent construct.
		parent::__construct();

		// Module section.
		$this->module_section = 'reduce-abandonment';

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Delete a module
		add_filter( 'merchant_modules', array( $this, 'delete_module' ) );
	}

	/**
	 * Delete the 'floating-mini-cart' module from Merchant dashboard.
	 *
	 * TODO: This is a temporary fix to avoid a fatal error while merging the Floating Cart with the Side Cart.
	 *
	 * In a future release, remove this code and delete the `floating-mini-cart` directory and all its files from `merchant/inc/modules/`.
	 * Also, remove the 'floating-mini-cart' entry from the `self::$modules_data` array in `merchant/inc/modules/class-add-module.php`.
	 *
	 * Changes was made in merchant v1.10.4
	 *
	 * @param array $modules
	 *
	 * @return array
	 */
	public function delete_module( $modules ) {
		if ( isset( $modules['reduce-abandonment']['modules']['floating-mini-cart'] ) ) {
			unset( $modules['reduce-abandonment']['modules']['floating-mini-cart'] );
		}

		return $modules;
	}

	/**
	 * Floating mini cart icon output.
	 *
	 * @return void
	 */
	public function floating_mini_cart_icon_output() {}

	/**
	 * Custom CSS.
	 *
	 * @return string
	 */
	public static function get_module_custom_css() {
		$css = '';

		return $css;
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Floating_Mini_Cart() );
} );
