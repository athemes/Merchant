<?php

/**
 * Kadence theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Kadence_Theme' ) ) {
	class Merchant_Kadence_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		}

		/**
		 * Load compatibility styles if the Kadence theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! $this->is_kadence_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-kadence-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/kadence/style.css',
				array(),
				'1.0.0'
			);
		}

		/**
		 * Check if Kadence theme is installed and active.
		 *
		 * @return bool
		 */
		private function is_kadence_active() {
			return class_exists( '\Kadence\Theme' );
		}
	}

	/**
	 * The class object can be accessed with "global $kadence_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$kadence_compatibility = new Merchant_Kadence_Theme();
}
