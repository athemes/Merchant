<?php

/**
 * Complementary Products Module.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Complementary Products Module.
 *
 */
class Merchant_Complementary_Products extends Merchant_Add_Module {

	/**
	 * Module ID.
	 */
	const MODULE_ID = 'complementary-products';

	/**
	 * Module template path.
	 */
	const MODULE_TEMPLATES_PATH = 'modules/' . self::MODULE_ID;

	/**
	 * Is module preview.
	 *
	 */
	public static $is_module_preview = false;

	/**
	 * Set the module as having analytics.
	 *
	 * @var bool
	 */
	protected $has_analytics = true;

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

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_js' ) );

			// Admin preview box.
			add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
		}

		if ( $this->is_module_active() && is_admin() ) {
			// Init translations.
			$this->init_translations();
		}

		if ( ! $this->is_module_active() ) {
			return;
		}
		// actions and filters goes here.
	}

	/**
	 * Init translations.
	 *
	 * @return void
	 */
	public function init_translations() {
		$settings = $this->get_module_settings();
		if ( ! empty( $settings['offers'] ) ) {
			foreach ( $settings['offers'] as $offer ) {
				if ( ! empty( $offer['product_single_page']['offer-title'] ) ) {
					Merchant_Translator::register_string( $offer['product_single_page']['offer-title'] );
				}

				if ( ! empty( $offer['product_single_page']['offer-description'] ) ) {
					Merchant_Translator::register_string( $offer['product_single_page']['offer-description'] );
				}

				if ( ! empty( $offer['cart_page']['title'] ) ) {
					Merchant_Translator::register_string( $offer['cart_page']['title'] );
				}

				if ( ! empty( $offer['cart_page']['button_text'] ) ) {
					Merchant_Translator::register_string( $offer['cart_page']['button_text'] );
				}

				if ( ! empty( $offer['checkout_page']['title'] ) ) {
					Merchant_Translator::register_string( $offer['checkout_page']['title'] );
				}

				if ( ! empty( $offer['checkout_page']['offer_description'] ) ) {
					Merchant_Translator::register_string( $offer['checkout_page']['offer_description'] );
				}

				if ( ! empty( $offer['checkout_page']['button_text'] ) ) {
					Merchant_Translator::register_string( $offer['checkout_page']['button_text'] );
				}

				if ( ! empty( $offer['thank_you_page']['title'] ) ) {
					Merchant_Translator::register_string( $offer['thank_you_page']['title'] );
				}

				if ( ! empty( $offer['thank_you_page']['discount_text'] ) ) {
					Merchant_Translator::register_string( $offer['thank_you_page']['discount_text'] );
				}

				if ( ! empty( $offer['thank_you_page']['button_text'] ) ) {
					Merchant_Translator::register_string( $offer['thank_you_page']['button_text'] );
				}
			}
		}
	}

	/**
	 * Admin enqueue CSS.
	 *
	 * @return void
	 */
	public function admin_enqueue_css() {
		if ( $this->is_module_settings_page() ) {
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
		}
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_js() {
		if ( $this->is_module_settings_page() ) {
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

			// Simple product add to cart text
			$preview->set_text( 'simple_product_shop_label', '.merchant-preview-add-to-cart-simple' );

			// Variable product add to cart text
			$preview->set_text( 'variable_product_shop_label', '.merchant-preview-add-to-cart-variable' );

			// Out of stock add to cart text
			$preview->set_text( 'out_of_stock_shop_label', '.merchant-preview-add-to-cart-out-of-stock' );

			// Out of stock add to cart text
			$preview->set_class( 'out_of_stock_custom_label', '.merchant-preview-product-out-of-stock', array(), 'display' );
		}

		return $preview;
	}

	/**
	 * Admin preview content.
	 *
	 * @param array $settings
	 *
	 * @return void
	 */
	public function admin_preview_content( $settings ) {
		merchant_get_template_part(
			self::MODULE_TEMPLATES_PATH . '/admin-preview/',
			'single-product'
		);
        merchant_get_template_part(
			self::MODULE_TEMPLATES_PATH . '/admin-preview/',
			'cart'
		);
        merchant_get_template_part(
			self::MODULE_TEMPLATES_PATH . '/admin-preview/',
			'checkout'
		);
        merchant_get_template_part(
			self::MODULE_TEMPLATES_PATH . '/admin-preview/',
			'thank-you-page'
		);
	}

	private function is_module_active() {
		return Merchant_Modules::is_module_active( self::MODULE_ID );
	}
}

// Initialize the module.
add_action( 'init', function () {
	Merchant_Modules::create_module( new Merchant_Complementary_Products() );
} );
