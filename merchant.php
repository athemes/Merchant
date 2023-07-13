<?php
/**
 * Plugin Name: Merchant
 * Plugin URI:  https://athemes.com
 * Description: All-in-one plugin designed to help you grow your WooCommerce store. Pre-orders, Buy Now buttons, product labels, trust badges, payment logos, and more.
 * Version:     1.0
 * Author:      aThemes
 * Author URI:  https://athemes.com
 * License:     GPLv3 or later License
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: merchant
 * Domain Path: /languages
 * 
 * WC requires at least: 6.0
 * WC tested up to: 7.8
 *
 * @package Merchant
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Merchant constants.
define( 'MERCHANT_VERSION', '1.0' );
define( 'MERCHANT_FILE', __FILE__ );
define( 'MERCHANT_BASE', trailingslashit( plugin_basename( MERCHANT_FILE ) ) );
define( 'MERCHANT_DIR', trailingslashit( plugin_dir_path( MERCHANT_FILE ) ) );
define( 'MERCHANT_URI', trailingslashit( plugins_url( '/', MERCHANT_FILE ) ) );

/**
 * Merchant class.
 */
class Merchant {

	/**
	 * The single class instance.
	 */
	private static $instance = null;

	/**
	 * Instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->includes();
	}

	public function includes() {

		require_once MERCHANT_DIR . 'admin/class-merchant-admin-loader.php';
		require_once MERCHANT_DIR . 'inc/class-merchant-loader.php';

	}

}

/**
 * Function works with the Merchant class instance
 */
function merchant() {
	return Merchant::instance();
}
merchant();
