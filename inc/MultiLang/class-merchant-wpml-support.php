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
		 * @param string $string_to_register The string to translate.
		 * @param string $context            The context of the string.
		 * @param bool   $multiline          Not used!.
		 */
		public function register_string( $string_to_register, $context, $multiline = false ) {
			/**
			 * @see   https://wpml.org/wpml-hook/wpml_register_single_string/
			 *
			 * @param string $context            The context of the string.
			 * @param string $string_to_register The string to translate.
			 *
			 * @since 1.8.0
			 */
			do_action( 'wpml_register_single_string', 'Merchant', $context, $string_to_register );
		}

		/**
		 * Translate a string.
		 *
		 * @param string $string_to_translate The string to translate.
		 *
		 * @return string
		 */
		public function translate_string( $string_to_translate ) {
			/**
			 * @see   https://wpml.org/wpml-hook/wpml_translate_single_string/
			 *
			 * @param string $string_to_translate The string to translate.
			 *
			 * @since 1.8.0
			 */
			return apply_filters( 'wpml_translate_single_string', $string_to_translate, 'Merchant', $string_to_translate );
		}

		/**
		 * Get Current language code
		 *
		 * @return string
		 */
		public function get_current_lang() {
			/**
			 * @see   https://wpml.org/wpml-hook/wpml_current_language/
			 *
			 * @since 1.9.0
			 */
			return apply_filters( 'wpml_current_language', null );
		}
	}
}