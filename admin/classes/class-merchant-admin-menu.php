<?php
/**
 * Merchant_Admin_Menu Class.
 */
if ( ! class_exists( 'Merchant_Admin_Menu' ) ) {

	class Merchant_Admin_Menu {

		/**
		 * Page title.
		 */
		public $page_title = 'Merchant';

		/**
		 * Plugin slug.
		 */
		public $plugin_slug = 'merchant';

		/**
		 * Plugin capability.
		 */
		public $capability = 'manage_options';

		/**
		 * Plugin priority.
		 */
		public $priority = 58;

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

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		}

		/**
		 * Include required classes.
		 */
		public function add_admin_menu() {

			global $submenu;

			// Dashboard
			add_menu_page(
				$this->page_title,
				$this->page_title,
				$this->capability,
				$this->plugin_slug,
				array( $this, 'page_dashboard' ),
				'dashicons-superhero-alt',
				$this->priority
			);

		}

		public function page_dashboard() {
			require_once MERCHANT_DIR . 'admin/pages/page-dashboard.php';
		}

	}

	Merchant_Admin_Menu::instance();

}
