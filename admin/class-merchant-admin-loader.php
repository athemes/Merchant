<?php
/**
 * Merchant_Admin_Loader Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Admin_Loader' ) ) {

	class Merchant_Admin_Loader {

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

			$this->includes();

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
			add_action( 'plugin_action_links_' . MERCHANT_BASE, array( $this, 'action_links' ) );
			add_filter( 'admin_footer_text', array( $this, 'add_admin_footer_text' ), 999 );
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ), 999 );

		}

		/**
		 * Include admin classes.
		 */
		public function includes() {

			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-menu.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-modules.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-options.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-utils.php';

		}

		/**
		 * Enqueue admin styles and scripts.
		 */
		public function enqueue_styles_scripts() {
			
			$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) ) {

				wp_enqueue_media();

				wp_enqueue_style( 'merchant-admin', MERCHANT_URI . 'assets/css/admin/admin.min.css', array(), MERCHANT_VERSION );

				wp_enqueue_script( 'merchant-jquery-form', MERCHANT_URI . 'assets/vendor/jquery-form/jquery.form.min.js', array( 'jquery' ), '4.3.0', true );

				wp_enqueue_script( 'merchant-pickr', MERCHANT_URI . 'assets/vendor/pickr/pickr.min.js', array( 'jquery' ), '1.8.2', true );

				wp_enqueue_script( 'merchant-admin', MERCHANT_URI . 'assets/js/admin/admin.min.js', array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-core', 'wp-util' ), MERCHANT_VERSION, true );

				wp_localize_script( 'merchant-admin', 'merchant', array(
					'nonce'    => wp_create_nonce( 'merchant' ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				) );

			}

		}

		/**
		 * Add plugin settings link on the plugin page.
		 */
		public function action_links( $links ) {

			$page_url = add_query_arg( array( 'page' => 'merchant' ), admin_url( 'themes.php' ) );

			$action_links = array(
				'settings' => '<a href="' . esc_url( $page_url ) . '">' . esc_html__( 'Settings', 'merchant' ) . '</a>',
			);

			return array_merge( $action_links, $links );

		}

		/**
		 * Add plugin settings link on the plugin page.
		 */
		public function add_admin_footer_text( $text ) {
			$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
			$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';
			
			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) ) {

				$text = '';
				
				if ( empty( $module ) ) {
					
					$text .= sprintf( '<a href="https://athemes.com/" target="_blank" class="merchant-admin-footer-text-link">%s</a>', esc_html__( 'Join our community', 'merchant' ) );
					$text .= esc_html__( 'to discuss about the product and ask for support or help the community.', 'merchant' );
					
				}
				
			}
			
			return $text;
			
		}
		
		/**
		 * Add admin body class.
		 */
		public function add_admin_body_class( $classes ) {			
			$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
			$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : '';

			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) ) {

				if ( ! empty( $module ) ) {

					$classes .= ' merchant-admin-page-module';

				} else {

					$classes .= ' merchant-admin-page';

				}

			}

			return $classes;

		}

	}

	Merchant_Admin_Loader::instance();

}
