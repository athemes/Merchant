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
		 * Plugin notifications.
		 */
		public $notifications = array();

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
			add_action( 'wp_ajax_merchant_notifications_read', array( $this, 'ajax_notifications_read' ) );

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
				MERCHANT_URI . 'assets/images/merchant-logo.svg',
				$this->priority
			);

		}

		/**
		 * Get Notifications
		 */
		public function get_notifications() {
			$notifications = get_transient( 'merchant_notifications' );

			if ( ! empty( $notifications ) ) {
				$this->notifications = $notifications;
			} else {

				/**
				 * Hook: merchant_changelog_api_url
				 * 
				 * @since 1.0
				 */
				$response = wp_remote_get( apply_filters( 'merchant_changelog_api_url', 'https://athemes.com/wp-json/wp/v2/changelogs?themes=7103&per_page=3' ) );

				if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
					$this->notifications = json_decode( wp_remote_retrieve_body( $response ) );
					set_transient( 'merchant_notifications', $this->notifications, 24 * HOUR_IN_SECONDS );
				}
			}

			return $this->notifications;
		}

		/**
		 * Check if the latest notification is read
		 */
		public function is_latest_notification_read() {

			if ( ! isset( $this->notifications ) || empty( $this->notifications ) ) {
				return false;
			}
			
			$user_id        = get_current_user_id();
			$user_read_meta = get_user_meta( $user_id, 'merchant_dashboard_notifications_latest_read', true );

			$last_notification_date      = strtotime( is_string( $this->notifications[0]->date ) ? $this->notifications[0]->date : '' );
			$last_notification_date_ondb = $user_read_meta ? strtotime( $user_read_meta ) : false;

			if ( ! $last_notification_date_ondb ) {
				return false;
			}

			if ( $last_notification_date > $last_notification_date_ondb ) {
				return false;
			}

			return true;

		}

		/**
		 * Ajax notifications.
		 */
		public function ajax_notifications_read() {

			check_ajax_referer( 'merchant', 'nonce' );

			$latest_notification_date = ( isset( $_POST[ 'latest_notification_date' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'latest_notification_date' ] ) ) : false;

			update_user_meta( get_current_user_id(), 'merchant_dashboard_notifications_latest_read', $latest_notification_date );

			wp_send_json_success();

		}

		public function page_dashboard() {
			require_once MERCHANT_DIR . 'admin/pages/page-dashboard.php';
		}

	}

	Merchant_Admin_Menu::instance();

}
