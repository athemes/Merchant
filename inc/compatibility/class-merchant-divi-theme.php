<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Divi theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Divi_Theme' ) ) {
	class Merchant_Divi_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
            // Nothing for now.
		}
	}

	/**
	 * The class object can be accessed with "global $divi_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_divi_compatibility = new Merchant_Divi_Theme();
}
