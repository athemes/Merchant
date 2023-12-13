<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Merchant - Multi Language
 */
if ( ! interface_exists( 'Merchant_Language_Strategy' ) ) {
	interface Merchant_Language_Strategy {

		/**
		 * Register a string for translation.
		 *
		 * @param string $string    The string to translate.
		 * @param string $context   The context of the string.
		 * @param bool   $multiline Whether the string is multiline or not.
		 */
		public function register_string( $string, $context, $multiline = false );

		/**
		 * Translate a string.
		 *
		 * @param string $string The string to translate.
		 *
		 * @return string
		 */
		public function translate_string( $string );

		/**
		 * Get Current language code
		 *
		 * @return string
		 */
		public function get_current_lang();
	}
}
