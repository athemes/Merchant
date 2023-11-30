<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Merchant - Multi Language Translator.
 */
if ( ! class_exists( 'Merchant_Translator' ) ) {
	class Merchant_Translator {

		/**
		 * @var Merchant_Language_Strategy $language_strategy The language strategy class.
		 */
		private static $language_strategy;

		/**
		 * Merchant_Translator constructor.
		 */
		public function __construct() {
			self::set_language_strategy();
		}

		/**
		 * Check if Polylang is active.
		 *
		 * @return bool
		 */
		public static function is_polylang_active() {
			return function_exists( 'pll_count_posts' );
		}

		/**
		 * Check if WPML is active.
		 *
		 * @return bool
		 */
		public static function is_wpml_active() {
			return function_exists( 'icl_object_id' );
		}

		/**
		 * Set the translator class.
		 */
		public static function set_language_strategy() {
			if ( self::is_polylang_active() ) {
				self::$language_strategy = new Merchant_PolyLang_Support();
			} elseif ( self::is_wpml_active() ) {
				self::$language_strategy = new Merchant_WPML_Support();
			} else {
				self::$language_strategy = new Merchant_No_Plugin_Support();
			}
		}

		/**
		 * Register a string for translation.
		 *
		 * @param string $string  The string to translate.
		 * @param string $context The context of the string.
		 */
		public static function register_string( $string, $context = 'merchant' ) {
			self::set_language_strategy();
			self::$language_strategy->register_string( $string, $context );
		}

		/**
		 * Translate a string.
		 *
		 * @param string $string The string to translate.
		 *
		 * @return string
		 */
		public static function translate( $string ) {
			self::set_language_strategy();
			$translated_string = self::$language_strategy->translate_string( $string );

			/**
			 * Filter the translated string.
			 *
			 * @param string $string            The string to translate.
			 * @param string $translated_string The translated string.
			 *
			 * @since 1.7
			 */
			return apply_filters( 'merchant_multi_lang_translated_string', $translated_string, $string );
		}
	}
}
