<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Flatsome theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Flatsome_Theme' ) ) {
	class Merchant_Flatsome_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		}

		/**
		 * Load compatibility styles if the Flatsome theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! merchant_is_flatsome_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-flatsome-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/flatsome/style.min.css',
				array(),
				MERCHANT_VERSION
			);
		}
	}

	/**
	 * The class object can be accessed with "global $flatsome_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_flatsome_compatibility = new Merchant_Flatsome_Theme();
}
