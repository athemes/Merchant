<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Merchant_Recent_Sales_Notifications
 */
if ( ! class_exists( 'Merchant_Advanced_Reviews' ) ) {
	class Merchant_Recent_Sales_Notifications extends Merchant_Add_Module {
		/**
		 * Module ID.
		 *
		 */
		const MODULE_ID = 'recent-sales-notifications';

		/**
		 * Module template path.
		 */
		const MODULE_TEMPLATES_PATH = 'modules/' . self::MODULE_ID;

		/**
		 * Constructor.
		 *
		 * Sets up the module.
		 */
		public function __construct() {
			// Module id.
			$this->module_id = self::MODULE_ID;

			// WooCommerce only.
			$this->wc_only = true;

			// Module section.
			$this->module_section = 'convert-more';

			// Module data.
			$this->module_data = Merchant_Admin_Modules::$modules_data[ self::MODULE_ID ];

			// Module options path.
			$this->module_options_path = MERCHANT_DIR . 'inc/modules/' . self::MODULE_ID . '/admin/options.php';

			if ( Merchant_Modules::is_module_active( self::MODULE_ID ) && is_admin() ) {
				// Init translations.
				$this->init_translations();
			}

			if ( is_admin() && parent::is_module_settings_page() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
				// Admin preview box.
				add_filter( 'merchant_module_preview', array( $this, 'render_admin_preview' ), 10, 2 );
			}

			// Parent construct.
			parent::__construct();
		}

		/**
		 * Init translations.
		 *
		 * @return void
		 */
		public function init_translations() {
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
			if ( self::MODULE_ID === $module ) {
				// HTML.
				$preview->set_html( 'welcome' );
			}

			return $preview;
		}

		public function admin_enqueue_assets() {
			wp_enqueue_style( 'merchant-admin-' . self::MODULE_ID, MERCHANT_URI . 'assets/css/modules/' . self::MODULE_ID . '/admin/preview.min.css', array(), MERCHANT_VERSION );
			wp_enqueue_script(
				'merchant-admin-' . self::MODULE_ID,
				MERCHANT_URI . 'assets/js/modules/' . self::MODULE_ID . '/admin/preview.min.js',
				array( 'jquery' ),
				MERCHANT_VERSION,
				true
			);
		}
	}
}

add_action( 'init', static function () {
	Merchant_Modules::create_module( new Merchant_Recent_Sales_Notifications() );
} );