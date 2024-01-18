<?php

/**
 * Twenty Twenty Four theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Twenty_Twenty_Four_Theme' ) ) {
	class Merchant_Twenty_Twenty_Four_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		}

		/**
		 * Load compatibility styles if the theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! $this->is_theme_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-twenty-twenty-four-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/twenty-twenty-four/style.css',
				array(),
				MERCHANT_VERSION
			);
		}

		/**
		 * Check if twenty twenty four theme is installed and active.
		 *
		 * @return bool
		 */
		private function is_theme_active() {
			return function_exists( 'twentytwentyfour_block_styles' );
		}
	}

	/**
	 * The class object can be accessed with "global $merchant_twenty_twenty_four_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_twenty_twenty_four_compatibility = new Merchant_Twenty_Twenty_Four_Theme();
}
