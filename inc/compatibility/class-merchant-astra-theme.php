<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Astra theme compatibility layer
 */
if ( ! class_exists( 'Merchant_Astra_Theme' ) ) {
	class Merchant_Astra_Theme {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_filter( 'astra_woo_shop_product_structure', array( $this, 'fix_astra_shop_structure' ) );
		}

		/**
		 * Disable the add to cart button in the shop loop while product swatches module is active.
		 *
		 * @param $shop_structure
		 *
		 * @return void
		 */
		public function fix_astra_shop_structure( $shop_structure ) {
			if ( ! Merchant_Modules::is_module_active( 'product-swatches' ) ) {
				return $shop_structure;
			}
			if ( is_array( $shop_structure ) && ! empty( $shop_structure ) ) {
				foreach ( $shop_structure as $key => $value ) {
					if ( $value === 'add_cart' ) {
						unset( $shop_structure[ $key ] );
					}
				}
			}

			return $shop_structure;
		}
	}

	/**
	 * The class object can be accessed with "global $merchant_astra_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_astra_compatibility = new Merchant_Astra_Theme();
}
