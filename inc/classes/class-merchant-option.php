<?php
/**
 * Merchant_Option Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Option' ) ) {

	class Merchant_Option {

		/**
		 * Option name
		 */
		public static $option = 'merchant';

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {}

		/**
		 * Get option.
		 */
		public static function get( $module, $setting, $default_val = null ) {

			$options = get_option( 'merchant', array() );

			$value = $default_val;

			if ( isset( $options[ $module ] ) && isset( $options[ $module ][ $setting ] ) ) {
				$value = $options[ $module ][ $setting ];
			}

			return $value;
		}
	}

}
