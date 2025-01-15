<?php
/**
 * Plugin Name: Merchant
 * Plugin URI:  https://athemes.com/merchant
 * Description: All-in-one plugin designed to help you grow your WooCommerce store. Pre-orders, Buy Now buttons, product labels, trust badges, payment logos, and more.
 * Version:     1.11.2
 * Author:      aThemes
 * Author URI:  https://athemes.com
 * License:     GPLv3 or later License
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: merchant
 * Domain Path: /languages
 *
 * WC requires at least: 6.0
 * WC tested up to: 9.4.1
 *
 * @package Merchant
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Merchant constants.
define( 'MERCHANT_VERSION', '1.11.2' );
define( 'MERCHANT_DB_VERSION', '1.0.0' ); // Update only when the database structure changes. In inc/classes/class-merchant-db-tables.php
define( 'MERCHANT_FILE', __FILE__ );
define( 'MERCHANT_BASE', trailingslashit( plugin_basename( MERCHANT_FILE ) ) );
define( 'MERCHANT_DIR', trailingslashit( plugin_dir_path( MERCHANT_FILE ) ) );
define( 'MERCHANT_URI', trailingslashit( plugins_url( '/', MERCHANT_FILE ) ) );
define( 'MERCHANT_REVIEW_URL', 'https://wordpress.org/support/plugin/merchant/reviews/#new-post' );

/**
 * Merchant class.
 *
 */
class Merchant {

	/**
	 * The single class instance.
	 *
	 */
	private static $instance = null;

	/**
	 * Instance.
	 *
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {

		// aThemes White Label Compatibility.
		if ( function_exists( 'athemes_wl_get_data' ) ) {
			$merchant_awl_data = athemes_wl_get_data();

			if ( ! empty( $merchant_awl_data[ 'activate_white_label' ] ) ) {
				define( 'MERCHANT_AWL_ACTIVE', true );
			}
		}

		// Translation.
		add_action( 'init', array( $this, 'translation' ) );

		// Declare WooCommerce HPOS Compatibility.
		add_action( 'before_woocommerce_init', function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} );

		// Declare incompatibility with Woo 8.3.0+ cart and checkout blocks.
		add_action( 'before_woocommerce_init', function() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
			}
		} );

		// Load the plugin functionality.
		$this->includes();

		// Initialize the merchant database tables.
		Merchant_DB_Tables::init();
	}

	/**
	 * Translation
	 * 
	 */
	public function translation() {
		load_plugin_textdomain( 'merchant', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}

	/**
	 * Includes.
	 *
	 */
	public function includes() {
		require_once MERCHANT_DIR . 'admin/class-merchant-admin-loader.php';
		require_once MERCHANT_DIR . 'inc/class-merchant-loader.php';
	}
}

/**
 * Run the plugin.
 */
Merchant::instance();

// Temporary
add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_script( 'merchant-analytics', MERCHANT_URI . 'assets/js/src/admin/analytics.js', array(), MERCHANT_VERSION, true );

	// This is temporary.
	wp_localize_script( 'merchant-analytics', 'merchant', array(
		'nonce'    => wp_create_nonce( 'merchant-nonce' ),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	) );
} );

add_action( 'wp_ajax_merchant_update_campaign_status', function() {
	check_ajax_referer( 'merchant-nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( esc_html__( 'You are not allowed to do this.', 'merchant' ), 403 );
	}

	$campaign_data = $_POST['campaign_data'] ?? array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( empty( $campaign_data ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'No campaigns found.', 'merchant' ) ), 400 );
	}

	// Get current options
	$db_options = get_option( 'merchant', array() );

	$should_update  = false;
	$new_status     = '';

	foreach ( $campaign_data as $module_id => $option ) {
		if ( ! is_string( $module_id ) || empty( $module_id ) ) {
			continue;
		}

		$campaign_key = sanitize_text_field( $option['campaign_key'] ?? '' );
		$campaigns    = $option['campaigns'] ?? array();

		if ( empty( $campaign_key ) || empty( $campaigns ) ) {
			continue;
		}

		foreach ( $campaigns as $index => $campaign ) {
			$campaign_id = isset( $campaign['campaign_id'] ) ? (int) $campaign['campaign_id'] : null;
			$status      = sanitize_text_field( $campaign['status'] ?? '' );

			if ( $campaign_id === null || ! in_array( $status, array( 'enable', 'disable' ), true ) ) {
				continue;
			}


			if ( isset( $db_options[ $module_id ][ $campaign_key ][ $campaign_id ] ) ) {
				if ( $status === 'disable' ) {
					$db_options[ $module_id ][ $campaign_key ][ $campaign_id ]['disable_campaign'] = true;
				} else {
					unset( $db_options[ $module_id ][ $campaign_key ][ $campaign_id ]['disable_campaign'] );
				}

				$new_status     = $status;
				$should_update  = true;
			}
		}
	}

	if ( $should_update ) {
		$updated = update_option( 'merchant', $db_options );
		if ( $updated ) {
			wp_send_json_success(
				array(
					'status'  => $new_status,
					'message' => esc_html__( 'Campaign updated successfully.', 'merchant' ),
				)
			);
		}
	}

	wp_send_json_error( array( 'message' => esc_html__( 'No campaigns were updated.', 'merchant' ) ) , 400  );
} );
