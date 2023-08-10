<?php

/**
 * Pre Orders.
 * 
 * @package Merchant_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Pre Orders Class.
 * 
 */
class Merchant_Pre_Orders extends Merchant_Add_Module {

	/**
	 * Module ID.
	 *
	 */
	const MODULE_ID = 'pre-orders';

	/**
	 * Is module preview.
	 * 
	 */
	public static $is_module_preview = false;

	/**
	 * Constructor.
	 * 
	 */
	public function __construct( Merchant_Pre_Orders_Main_Functionality $main_func ) {
		parent::__construct();

		// Module section.
		$this->module_section = 'boost-revenue';

		// Module id.
		$this->module_id = self::MODULE_ID;

		// Module default settings.
		$this->module_default_settings = array(
			'button_text' => __( 'Pre Order Now!', 'merchant' ),
			'additional_text' => __( 'Ships on {date}.', 'merchant' )
		);

		// Mount preview url.
		$preview_url = site_url( '/' );

		if ( function_exists( 'wc_get_page_id' ) ) {
			$preview_url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		// Module data.
		$this->module_data = array(
			'icon' => '<svg width="18" height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17"><path d="m8.69 3.772-.243.123a1 1 0 1 1-.895-1.79l2-1a.998.998 0 0 1 1.434 1.06l-1 6a1 1 0 0 1-1.973-.33l.677-4.063ZM3.617 2.924a.997.997 0 0 1-.324-.217l-1-1A1 1 0 0 1 3.707.293l1 1a.999.999 0 0 1-1.09 1.631ZM14.383 2.924a.997.997 0 0 1-.94-.092 1 1 0 0 1-.15-1.54l1-1a1 1 0 1 1 1.414 1.415l-1 1a.996.996 0 0 1-.324.217ZM14.293 6.707A1 1 0 0 1 15 5h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-.707-.293ZM3 7H1a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2ZM0 15.5V10h2v2h3.5c.775 0 1.388.662 1.926 1.244l.11.12c.366.391.886.636 1.464.636s1.098-.245 1.463-.637l.11-.119C11.114 12.663 11.726 12 12.5 12H16v-2h2v5.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 0 15.5Z"/></svg>',
			'title' => esc_html__( 'Pre-Orders', 'merchant' ),
			'desc' => esc_html__( 'Allow visitors to pre-order products that are either out of stock or not yet released.', 'merchant' ),
			'placeholder' => MERCHANT_URI . 'assets/images/modules/pre-orders.png',
			'tutorial_url' => 'https://docs.athemes.com/article/pre-orders/',
			'preview_url' => $preview_url
		);

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );

			// Custom CSS.
			// The custom CSS should be added here as well due to ensure preview box works properly.
			add_filter( 'merchant_custom_css', array( $this, 'admin_custom_css' ) );

		}

		if ( ! Merchant_Modules::is_module_active( self::MODULE_ID ) ) {
			return;
		}

		// Init pre orders main functionality within the `woocommerce_loaded` hook. callback with a class method.
		// TODO: Refactor the 'Merchant_Pre_Orders_Main_Functionality' class to load admin things separated from frontend things.
		add_action( 'woocommerce_loaded', array( $main_func, 'init' ) );

		// Return early if it's on admin but not in the respective module settings page.
		if ( is_admin() && ! parent::is_module_settings_page() ) {
			return;	
		}

		// Enqueue styles.
		add_action( 'merchant_enqueue_before_main_css_js', array( $this, 'enqueue_css' ) );

		// Enqueue scripts.
		add_action( 'merchant_enqueue_after_main_css_js', array( $this, 'enqueue_scripts' ) );
		
		// Custom CSS.
		add_filter( 'merchant_custom_css', array( $this, 'frontend_custom_css' ) );

	}

	/**
	 * Admin enqueue CSS.
	 * 
	 * @return void
	 */
	public function admin_enqueue_css() {
		$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

		if ( 'merchant' === $page && self::MODULE_ID === $module ) {
			wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/pre-orders.min.css', [], MERCHANT_VERSION );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Enqueue CSS.
	 * 
	 * @return void
	 */
	public function enqueue_css() {

		// Specific module styles.
		wp_enqueue_style( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/pre-orders.min.css', array(), MERCHANT_VERSION );
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {

		// Register and enqueue the main module script.
		wp_enqueue_script( 'merchant-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/pre-orders.min.js', array(), MERCHANT_VERSION, true );
	}

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public function render_admin_preview( $preview, $module ) {
		if ( self::MODULE_ID === $module ) {
			ob_start();
			self::admin_preview_content();
			$content = ob_get_clean();

			// HTML.
			$preview->set_html( $content );

			// Button Text.

			// Additional Text.

		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 * 
	 * @return void
	 */
	public function admin_preview_content() {
		?>

		xxxx

		<?php
	}

	/**
	 * Custom CSS.
	 * 
	 * @return string
	 */
	public function get_module_custom_css() {
		$css = '';

		return $css;
	}

	/**
	 * Admin custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function admin_custom_css( $css ) {
		$css .= $this->get_module_custom_css(); 

		return $css;
	}

	/**
	 * Frontend custom CSS.
	 * 
	 * @param string $css The custom CSS.
	 * @return string $css The custom CSS.
	 */
	public function frontend_custom_css( $css ) {
		$css .= $this->get_module_custom_css();

		return $css;
	}

}

// Main functionality.
require MERCHANT_DIR . 'inc/modules/pre-orders/class-pre-orders-main-functionality.php';
$po_main_func = new Merchant_Pre_Orders_Main_Functionality();

// Initialize the module.
new Merchant_Pre_Orders( $po_main_func );
