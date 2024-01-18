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
			// Nothing for now.
		}
	}

	/**
	 * The class object can be accessed with "global $avada_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_avada_compatibility = new Merchant_Avada_Theme();
}
