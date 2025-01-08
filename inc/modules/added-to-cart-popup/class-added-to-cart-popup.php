<?php

/**
 * Add To Cart Text
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Add To Cart Text Class.
 *
 */
class Merchant_Added_To_Cart_Popup extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'added-to-cart-popup';

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

		// Module default settings.
		$this->module_default_settings = array();

		// Module data.
		$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

		// Module section.
		$this->module_section = $this->module_data['section'];

		// Module options path.
		$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

		// Is module preview page.
		if ( is_admin() && parent::is_module_settings_page() ) {
			self::$is_module_preview = true;

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}

		if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['popup_message'] ) ) {
			Merchant_Translator::register_string( $settings['popup_message'], esc_html__( 'Add to cart popup: header message text', 'merchant' ) );
		}
		if ( ! empty( $settings['view_cart_button_label'] ) ) {
			Merchant_Translator::register_string( $settings['view_cart_button_label'], esc_html__( 'Add to cart popup: view cart button label', 'merchant' ) );
		}
		if ( ! empty( $settings['view_continue_shopping_button_label'] ) ) {
			Merchant_Translator::register_string( $settings['view_continue_shopping_button_label'], esc_html__( 'Add to cart popup: continue shopping button label', 'merchant' ) );
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			// load slick slider
			wp_enqueue_style( 'slick-slider', MERCHANT_URI . 'assets/vendor/slick-slider/slick.css', array(), MERCHANT_VERSION );
			wp_enqueue_script( 'slick-slider', MERCHANT_URI . 'assets/vendor/slick-slider/slick.min.js', array( 'jquery' ), MERCHANT_VERSION, true );
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js', array( 'jquery' ),
				MERCHANT_VERSION, true );
		}
	}

	/**
	 * Render admin preview
	 *
	 * @param Merchant_Admin_Preview $preview
	 * @param string                 $module
	 *
	 * @return Merchant_Admin_Preview
	 */
	public function render_admin_preview( $preview, $module ) {
		if ( $module === self::MODULE_ID ) {
			// HTML.
			$preview->set_html( array( $this, 'admin_preview_content' ), $this->get_module_settings() );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
		merchant_get_template_part(
			'modules/' . self::MODULE_ID . '/admin',
			'layouts',
			array(
				'settings' => $settings,
			)
		);
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Added_To_Cart_Popup() );
} );
