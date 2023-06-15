<?php
/**
 * Plugin Name: Merchant
 * Description: Provides enhancements for your website. Get started now!
 * Version:     1.0.0
 * Author:      aThemes
 * Author URI:  https://athemes.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: merchant
 * Domain Path: /languages
 *
 * @package Merchant
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Merchant constants.
define( 'MERCHANT_VERSION', '1.0.0' );
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
