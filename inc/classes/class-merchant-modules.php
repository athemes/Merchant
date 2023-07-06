<?php
/**
 * Merchant_Modules Class.
 */
if ( ! class_exists( 'Merchant_Modules' ) ) {

	class Merchant_Modules {

		/**
		 * Option name
		 */
		public static $option = 'merchant-modules';

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
		public function __construct() {

			add_action( 'wp_ajax_merchant_module_activate', array( $this, 'activate_module' ) );
			add_action( 'wp_ajax_merchant_module_deactivate', array( $this, 'deactivate_module' ) );
			add_action( 'wp_ajax_merchant_module_feedback', array( $this, 'feedback_module' ) );

		}

		/**
		 * Activate module with Ajax.
		 */
		public function activate_module() {

			$nonce  = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$module = ( isset( $_POST['module'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module'] ) ) : '';

			if ( wp_verify_nonce( $nonce, 'merchant' ) && ! empty( $module ) ) {

				$modules = get_option( self::$option, array() );

				$modules[ $module ] = true;

				update_option( self::$option, $modules );

				wp_send_json_success();

			}
			
			wp_send_json_error();

		}

		/**
		 * Deactivate module with Ajax.
		 */
		public function deactivate_module() {

			$nonce  = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$module = ( isset( $_POST['module'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module'] ) ) : '';

			if ( wp_verify_nonce( $nonce, 'merchant' ) && ! empty( $module ) ) {

				$modules = get_option( self::$option, array() );

				$modules[ $module ] = false;

				update_option( self::$option, $modules );

				wp_send_json_success();

			}
			
			wp_send_json_error();

		}

		/**
		 * Feedback module with Ajax.
		 */
		public function feedback_module() {

			$nonce   = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$subject = ( isset( $_POST['subject'] ) ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
			$message = ( isset( $_POST['message'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

			if ( wp_verify_nonce( $nonce, 'merchant' ) ) {

				//
				// Do stuff.
				//
				// $subject
				// $message

				wp_send_json_success();

			}
			
			wp_send_json_error();

		}

		/**
		 * Check if a specific module is activated
		 */
		public static function is_module_active( $module ) {

			$modules = get_option( self::$option, array() );

			// Preview Mode
			if ( isset( $_GET['preview'] ) && isset( $_GET['module'] ) && $_GET['module'] === $module && current_user_can( 'manage_options' ) ) {
				return true;
			}

			// Preview Mode
			$operation_mode = Merchant_Option::get( 'global-settings', 'operating_mode', 'active' );

			if ( 'inactive' === $operation_mode ) {
				return false;
			} elseif ( 'preview' === $operation_mode && ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			if ( is_array( $modules ) && array_key_exists( $module, $modules ) && true === $modules[ $module ] ) {
				return true;
			}

			return false;

		}

	}

	Merchant_Modules::instance();

}
