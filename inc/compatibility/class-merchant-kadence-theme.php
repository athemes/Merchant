<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Kadence theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Kadence_Theme' ) ) {
	class Merchant_Kadence_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		}

		/**
		 * Load compatibility styles if the Kadence theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! merchant_is_kadence_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-kadence-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/kadence/style.min.css',
				array(),
				MERCHANT_VERSION
			);
		}
	}

	/**
	 * The class object can be accessed with "global $kadence_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_kadence_compatibility = new Merchant_Kadence_Theme();
}
