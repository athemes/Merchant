<?php
/**
 * Merchant_Modules Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Modules' ) ) {

	class Merchant_Modules {

		/**
		 * The modules container.
		 */
		private $container = array();

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

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( wp_verify_nonce( $nonce, 'merchant' ) && ! empty( $module ) ) {

				$modules = get_option( self::$option, array() );

				$modules[ $module ] = true;

				update_option( self::$option, $modules );

				/**
				 * Hook 'merchant_admin_module_activated'
				 * 
				 * @since 1.9.3
				 */
				do_action( 'merchant_admin_module_activated', $module );

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

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( wp_verify_nonce( $nonce, 'merchant' ) && ! empty( $module ) ) {

				$modules = get_option( self::$option, array() );

				$modules[ $module ] = false;

				update_option( self::$option, $modules );

				/**
				 * Hook 'merchant_admin_module_deactivated'
				 * 
				 * @since 1.9.3
				 */
				do_action( 'merchant_admin_module_deactivated', $module );

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
			$module  = ( isset( $_POST['module'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module'] ) ) : '';
			$from    = get_bloginfo( 'admin_email' );

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( wp_verify_nonce( $nonce, 'merchant' ) ) {
				$response = wp_remote_post( 'https://athemes.com/merchant/', array(
					'body' => array(
						'mailsender' => true,
						'from'    => $from,
						'subject' => $subject,
						'message' => $message,
						'module'  => $module,
					),
				) );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error();
				}

				wp_send_json_success();
			}
			
			wp_send_json_error();
		}

		/**
		 * Creates and adds the module instance to the container.
		 *
		 * @param Merchant_Add_Module $module The module instance.
		 *
		 * @return void
		 */
		public static function create_module( Merchant_Add_Module $module ) {
			static::instance()->container[ $module->module_id ] = $module;
		}

		/**
		 * Get the module instance.
		 *
		 * @param string $module_id The module ID.
		 *
		 * @return Merchant_Add_Module|mixed The module instance.
		 */
		public static function get_module( $module_id ) {
			return static::instance()->container[ $module_id ];
		}

		/**
		 * Determines if a module has already been added to the container.
		 *
		 * @param string $module_id The module ID.
		 *
		 * @return bool
		 */
		public static function is_module_created( $module_id ) {
			return in_array( $module_id, static::instance()->container, true );
		}

		/**
		 * Check if a specific module is activated
		 */
		public static function is_module_active( $module ) {
			/**
			 * Hook 'merchant_module_{$module}_deactivate'
			 * 
			 * @since 1.0
			 */
			if ( apply_filters( "merchant_module_{$module}_deactivate", false ) ) {
				add_filter( "merchant_admin_module_{$module}_list_item_class", static function( $class_name ) {
					return $class_name . ' merchant-module-deactivated-by-bp';
				} );

				if ( isset( $_GET['module'] ) && $_GET['module'] === $module ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					add_filter( "merchant_admin_module_{$module}_activate_button_class", static function( $class_name ) {
						if ( strpos( $class_name, 'merchant-module-deactivated-by-bp' ) !== false ) {
							return $class_name;
						}

						return $class_name . ' merchant-module-deactivated-by-bp';
					} );
				}

				return false;
			}

			$modules = get_option( self::$option, array() );

			// Preview Mode
			if ( isset( $_GET['preview'] ) && isset( $_GET['module'] ) && $_GET['module'] === $module && current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}

			// Preview Mode
			$operation_mode = Merchant_Option::get( 'global-settings', 'operating_mode', 'active' );

			if ( 'inactive' === $operation_mode ) {
				return false;
			}

			if ( 'preview' === $operation_mode ) {
				if ( ! function_exists( 'wp_get_current_user' ) ) {
					include ABSPATH . 'wp-includes/pluggable.php';
				}
				if ( ! current_user_can( 'manage_options' ) ) {
					return false;
				}
			}

			if ( is_array( $modules ) && array_key_exists( $module, $modules ) && true === $modules[ $module ] ) {
				return true;
			}

			return false;
		}
	}

	Merchant_Modules::instance();

}
