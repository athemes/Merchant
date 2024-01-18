<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * OceanWP theme compatibility layer
 */
if ( ! class_exists( 'Merchant_OceanWP_Theme' ) ) {
	class Merchant_OceanWP_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		}

		/**
		 * Load compatibility styles if the OceanWP theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! merchant_is_oceanwp_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-oceanwp-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/oceanwp/style.min.css',
				array(),
				MERCHANT_VERSION
			);
		}
	}

	/**
	 * The class object can be accessed with "global $oceanWP_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_oceanWP_compatibility = new Merchant_OceanWP_Theme();
}
