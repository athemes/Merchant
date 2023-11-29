<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Merchant - WPML Support
 *
 * This class is not meant to be used directly. It will be used by the Merchant_Translator class.
 */
if ( ! class_exists( 'Merchant_WPML_Support' ) ) {
	class Merchant_WPML_Support implements Merchant_Language_Strategy {

		/**
		 * Register a string for translation.
		 *
		 * @param string $string The string to translate.
		 * @param string $context The context of the string.
		 * @param bool   $multiline Whether the string is multiline or not.
		 */
		public function register_string( $string, $context, $multiline = false ) {
			// TODO: Implement register_string() method.
		}

		/**
		 * Translate a string.
		 *
		 * @param string $string The string to translate.
		 *
		 * @return string
		 */
		public function translate_string( $string ) {
			return apply_filters( 'wpml_translate_single_string', $string, 'Merchant', $string );
		}
	}
}