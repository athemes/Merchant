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

			$this->includes_not_hooked();

			add_action( 'init', array( $this, 'includes' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
			add_action( 'plugin_action_links_' . MERCHANT_BASE, array( $this, 'action_links' ) );
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ), 999 );
		}

		/**
		 * No hooked includes.
		 * These files are included without any WordPress hook.
		 */
		public function includes_not_hooked() {
			require_once MERCHANT_DIR . 'inc/classes/class-merchant-svg-icons.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-modules.php';
		}

		/**
		 * Include admin classes.
		 */
		public function includes() {

			// Notices.
			require_once MERCHANT_DIR . 'admin/notices/class-merchant-notice.php';
			require_once MERCHANT_DIR . 'admin/notices/class-merchant-notice-review.php';
			require_once MERCHANT_DIR . 'admin/notices/class-merchant-notice-upsell.php';
			require_once MERCHANT_DIR . 'admin/notices/class-merchant-notice-campaign.php';

			// Admin classes.
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-menu.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-options.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-utils.php';
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-admin-preview.php';
			
			// Plugin installer.
			require_once MERCHANT_DIR . 'admin/classes/class-merchant-plugin-installer.php';
		}

		/**
		 * Enqueue admin styles and scripts.
		 */
		public function enqueue_styles_scripts() {
			$page    = sanitize_text_field( wp_unslash( $_GET['page'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$section = sanitize_text_field( wp_unslash( $_GET['section'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			wp_register_script( 'merchant-select2', MERCHANT_URI . 'assets/vendor/select2/select2.full.min.js', array( 'jquery' ), '4.0.13', true );
			wp_register_style( 'merchant-select2', MERCHANT_URI . 'assets/vendor/select2/select2.min.css', array(), '4.0.13' );

			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) ) {

				// For settings page only
				if ( $section === 'settings' ) {
					wp_enqueue_code_editor( array( 'type' => 'text/css' ) ); // No need to enqueue again for JS. It'll be handled by JS.
					wp_enqueue_script( 'merchant-settings', MERCHANT_URI . 'assets/js/admin/settings.min.js', array( 'jquery', 'code-editor' ), MERCHANT_VERSION, true );
				}

				wp_enqueue_media();

				wp_enqueue_style( 'merchant-admin', MERCHANT_URI . 'assets/css/admin/admin.min.css', array(), MERCHANT_VERSION );

				if ( is_rtl() ) {
					wp_enqueue_style( 'merchant-admin-rtl', MERCHANT_URI . 'assets/css/admin/admin-rtl.min.css', array(), MERCHANT_VERSION );
				}

				wp_enqueue_script( 'merchant-jquery-form', MERCHANT_URI . 'assets/vendor/jquery-form/jquery.form.min.js', array( 'jquery' ), '4.3.0', true );

				wp_enqueue_script( 'merchant-pickr', MERCHANT_URI . 'assets/vendor/pickr/pickr.min.js', array( 'jquery' ), '1.8.2', true );

				wp_enqueue_script( 'merchant-admin', MERCHANT_URI . 'assets/js/admin/admin.min.js', array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-util' ), MERCHANT_VERSION, true );

				$localized_data = array(
					'nonce'    => wp_create_nonce( 'merchant' ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				);

				/**
				 * Hook 'merchant_admin_localize_script'
				 *
				 * @since 1.9.6
				 */
				$localized_data = apply_filters( 'merchant_admin_localize_script', $localized_data );

				wp_localize_script( 'merchant-admin', 'merchant', $localized_data );

				$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( ! empty( $module ) ) {
					wp_enqueue_script( 'merchant-admin-preview', MERCHANT_URI . 'assets/js/admin/merchant-preview.min.js', array( 'jquery' ), MERCHANT_VERSION, true );
				}
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
		 * Add admin body class.
		 */
		public function add_admin_body_class( $classes ) {          
			$page    = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$module  = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$section = ( ! empty( $_GET['section'] ) ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( ! empty( $page ) && false !== strpos( $page, 'merchant' ) ) {
				if ( ! empty( $module ) ) {
					$classes .= ' merchant-admin-page-module';
				} else {
					$section  = $section ? 'merchant-admin-page__' . $section : 'merchant-admin-page__dashboard';
					$classes .= ' merchant-admin-page ' . $section;

				}
			}

			return $classes;
		}
	}

	Merchant_Admin_Loader::instance();
}
