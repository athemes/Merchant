<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Botiga theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Botiga_Theme' ) ) {
	class Merchant_Botiga_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		}

		/**
		 * Load compatibility styles if the Botiga theme is installed and active.
		 *
		 * @return void
		 */
		public function styles() {
			if ( ! merchant_is_botiga_active() ) {
				return;
			}

			wp_enqueue_style(
				'merchant-botiga-compatibility',
				MERCHANT_URI . 'assets/css/compatibility/botiga/style.min.css',
				array(),
				MERCHANT_VERSION
			);
		}
	}

	/**
	 * The class object can be accessed with "global $botiga_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_botiga_compatibility = new Merchant_Botiga_Theme();
}
