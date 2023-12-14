<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Merchant - No Plugin Support
 *
 * This class is not meant to be used directly. It will be used by the Merchant_Translator class.
 */
if ( ! class_exists( 'Merchant_No_Plugin_Support' ) ) {
	class Merchant_No_Plugin_Support implements Merchant_Language_Strategy {

		/**
		 * Register a string for translation.
		 *
		 * @param string $string_to_register The string to translate.
		 * @param string $context            The context of the string.
		 * @param bool   $multiline          Whether the string is multiline or not.
		 */
		public function register_string( $string_to_register, $context, $multiline = false ) {
			// Do nothing.
		}

		/**
		 * Translate a string.
		 *
		 * @param string $string_to_translate The string to translate.
		 *
		 * @return string
		 */
		public function translate_string( $string_to_translate ) {
			return $string_to_translate;
		}

		/**
		 * Get WordPress language code
		 *
		 * @return string
		 */
		public function get_current_lang() {
			return get_locale();
		}
	}
}