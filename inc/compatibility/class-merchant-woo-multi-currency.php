<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Woo Multi Currency plugin compatibility layer
 * https://wordpress.org/plugins/woo-multi-currency/
 */
if ( ! class_exists( 'Merchant_Woo_Multi_Currency' ) ) {
	class Merchant_Woo_Multi_Currency {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_filter( 'merchant_discounted_price', array( $this, 'multi_currency_support' ), 10, 3 );
		}

		/**
		 * Multi-currency support for merchant discounted price
		 *
		 * @param float  $price         The discounted price.
		 * @param string $cart_item_key Cart item key.
		 * @param array  $cart_item     Cart item array.
		 *
		 * @return float
		 */
		public function multi_currency_support( $price, $cart_item_key, $cart_item ) {
			if ( class_exists( 'WOOMULTI_CURRENCY_F_Data' ) ) {
				$wmc_data         = WOOMULTI_CURRENCY_F_Data::get_ins();
				$default_currency = $wmc_data->get_default_currency();
				$current_currency = $wmc_data->get_current_currency();
				$currency_list    = $wmc_data->get_list_currencies();

				// If current currency is not the default, convert price back to default currency
				if ( $current_currency !== $default_currency && isset( $currency_list[ $current_currency ]['rate'] ) && $currency_list[ $current_currency ]['rate'] > 0 ) {
					$price = $price / $currency_list[ $current_currency ]['rate'];
				}
			}

			return $price;
		}
	}

	/**
	 * The class object can be accessed with "global $merchant_woo_multi_currency", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_woo_multi_currency = new Merchant_Woo_Multi_Currency();
}
