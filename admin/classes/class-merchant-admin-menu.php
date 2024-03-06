<?php
/**
 * Merchant_Admin_Menu Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Admin_Menu' ) ) {

	class Merchant_Admin_Menu {
		public $page_title       = 'Merchant';
		public $plugin_slug      = 'merchant';
		public $capability       = 'manage_options';
		public $priority         = 58;
		public $notifications    = array();
		private static $instance = null;

		/**
		 * Instance.
		 *
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 */
		public function __construct() {
			if ( defined( 'MERCHANT_AWL_ACTIVE' ) ) {
				return;
			}

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'wp_ajax_merchant_notifications_read', array( $this, 'ajax_notifications_read' ) );
			add_action('admin_footer', array( $this, 'footer_internal_scripts' ));
		}

		/**
		 * Include required classes.
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			global $submenu;

			// Dashboard.
			add_menu_page(
				$this->page_title,
				$this->page_title,
				$this->capability,
				$this->plugin_slug,
				array( $this, 'page_dashboard' ),
				MERCHANT_URI . 'assets/images/merchant-logo.svg',
				$this->priority
			);

			// Dashboard Sub Item.
			add_submenu_page(
				$this->plugin_slug,
				esc_html__('Dashboard', 'merchant'),
				esc_html__('Dashboard', 'merchant'),
				'manage_options',
				$this->plugin_slug,
				'',
				1
			);

			// Enabled Modules.
			add_submenu_page(
				$this->plugin_slug,
				esc_html__('Enabled Modules', 'merchant'),
				esc_html__('Enabled Modules', 'merchant'),
				'manage_options',
				'admin.php?page=merchant&section=modules',
				'',
				2
			);

			// Settings.
			add_submenu_page(
				$this->plugin_slug,
				esc_html__('Settings', 'merchant'),
				esc_html__('Settings', 'merchant'),
				'manage_options',
				'admin.php?page=merchant&section=settings',
				'',
				3
			);

			// Add 'Upgrade' link.
			if( ! defined( 'MERCHANT_PRO_VERSION' ) ) {
				add_submenu_page(
					$this->plugin_slug,
					esc_html__('Upgrade to Pro', 'merchant'),
					esc_html__('Upgrade to Pro', 'merchant'),
					'manage_options',
					'https://athemes.com/merchant-upgrade?utm_source=theme_submenu_page&utm_medium=button&utm_campaign=Merchant',
					'',
					4
				);
			}
		}

		/**
		 * Get Notifications
		 *
		 * @return array
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
				$response = wp_remote_get( apply_filters( 'merchant_changelog_api_url', 'https://athemes.com/wp-json/wp/v2/notifications?theme=7103&per_page=3' ) );

				if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
					$this->notifications = json_decode( wp_remote_retrieve_body( $response ) );
					set_transient( 'merchant_notifications', $this->notifications, 24 * HOUR_IN_SECONDS );
				}
			}

			return $this->notifications;
		}

		/**
		 * Check if the latest notification is read
		 *
		 * @return bool
		 */
		public function is_latest_notification_read() {

			if ( ! isset( $this->notifications ) || empty( $this->notifications ) ) {
				return false;
			}
			
			$user_id        = get_current_user_id();
			$user_read_meta = get_user_meta( $user_id, 'merchant_dashboard_notifications_latest_read', true );

			$last_notification_date      = strtotime( is_string( $this->notifications[0]->post_date ) ? $this->notifications[0]->post_date : '' );
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
		 *
		 * @return void
		 */
		public function ajax_notifications_read() {
			check_ajax_referer( 'merchant', 'nonce' );

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			$latest_notification_date = ( isset( $_POST[ 'latest_notification_date' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'latest_notification_date' ] ) ) : false;

			update_user_meta( get_current_user_id(), 'merchant_dashboard_notifications_latest_read', $latest_notification_date );

			wp_send_json_success();
		}

		/**
		 * Include Page dashboard
		 *
		 * @return void
		 */
		public function page_dashboard() {
			require_once MERCHANT_DIR . 'admin/pages/page-dashboard.php';
		}

		/**
		 * Footer internal scripts
		 *
		 * @return void
		 */
		public function footer_internal_scripts() {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? str_replace( '/wp-admin/', '', $_SERVER['REQUEST_URI'] ) : '';
			?>
			<style>
				#adminmenu .toplevel_page_merchant .wp-submenu li.current a {
                    color: rgba(240, 246, 252, 0.7);
                    font-weight: 400;
                }

				#adminmenu .toplevel_page_merchant .wp-submenu li a[href="<?php echo $request_uri; //phpcs:ignore ?>"] {
					color: #fff;
                    font-weight: 600;
				}

				#adminmenu .toplevel_page_merchant .wp-submenu a[href="https://athemes.com/merchant-upgrade?utm_source=theme_submenu_page&utm_medium=button&utm_campaign=Merchant"] {
					background-color: green;
					color: #FFF;
				}
			</style>
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function () {
                    const merchantUpsellMenuItem = document.querySelector('#adminmenu .toplevel_page_merchant .wp-submenu a[href="https://athemes.com/merchant-upgrade?utm_source=theme_submenu_page&utm_medium=button&utm_campaign=Merchant"]');

                    if (merchantUpsellMenuItem) {
                        merchantUpsellMenuItem.addEventListener('click', function (e) {
                            e.preventDefault();

                            const href = this.getAttribute('href');
                            window.open(href, '_blank');
                        });
                    }
                });
            </script>
			<?php
		}
	}

	Merchant_Admin_Menu::instance();

}
