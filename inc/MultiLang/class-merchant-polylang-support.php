<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Merchant - Polylang Support
 *
 * This class is not meant to be used directly. It will be used by the Merchant_Translator class.
 */
if ( ! class_exists( 'Merchant_PolyLang_Support' ) ) {
	class Merchant_PolyLang_Support implements Merchant_Language_Strategy {

		/**
		 * Register a string for translation.
		 *
		 * @param string $string    The string to translate.
		 * @param string $context   The context of the string.
		 * @param bool   $multiline Whether the string is multiline or not.
		 */
		public function register_string( $string, $context, $multiline = false ) {
			pll_register_string( $context, $string, 'Merchant', $multiline );
		}

		/**
		 * Translate a string.
		 *
		 * @param string $string The string to translate.
		 *
		 * @return string
		 */
		public function translate_string( $string ) {
			return pll__( $string );
		}
	}
}