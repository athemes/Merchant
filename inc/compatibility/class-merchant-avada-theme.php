<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Avada theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Avada_Theme' ) ) {
	class Merchant_Avada_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		}

		/**
		 * Load compatibility styles if the Avada theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! merchant_is_avada_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-avada-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/avada/style.min.css',
				array(),
				MERCHANT_VERSION
			);
		}
	}

	/**
	 * The class object can be accessed with "global $avada_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_avada_compatibility = new Merchant_Avada_Theme();
}
