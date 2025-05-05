<?php
/**
 * Merchant Admin Statistics Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Merchant_Admin_Statistics_Tracking
 *
 * This class handles the tracking of statistics for the Merchant Admin.
 * It includes methods for saving site keys, checking user consent, and sending module usage statistics.
 *
 * @package Merchant_Admin
 */
class Merchant_Admin_Statistics_Tracking {

	/**
	 * The URL for the website API.
	 *
	 * @var string
	 */
	private const WEBSITE_API_LINK = 'https://athemes.com/wp-json/athemes-stats/v1';

	/**
	 * The endpoint for registering the site.
	 *
	 * @var string
	 */
	private const REGISTER_SITE_ENDPOINT = 'register-site';

	/**
	 * The endpoint for sending bulk usage statistics.
	 *
	 * @var string
	 */
	private const SEND_BULK_USAGE_ENDPOINT = 'track-event-bulk';

	/**
	 * The endpoint for sending single usage statistics.
	 *
	 * @var string
	 */
	private const SEND_SINGLE_USAGE_ENDPOINT = 'track-event';

	/**
	 * The endpoint for sending heartbeat updates.
	 *
	 * @var string
	 */
	private const SEND_HEART_BEAT_ENDPOINT = 'update-heartbeat';

	/**
	 * @var Merchant_Admin_Statistics_Tracking $instance Singleton instance
	 */
	private static $instance;

	/**
	 * Get the singleton instance of the class
	 *
	 * @return Merchant_Admin_Statistics_Tracking
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Attach hooks to the WordPress API
	 *
	 * @return void
	 */
	public function load_hooks() {
		add_action( 'init', array( $this, 'register_website' ) );
		add_action( 'merchant_request_site_key', array( $this, 'register_website_background_process' ) );
		add_action( 'init', array( $this, 'send_bulk_usage_statistics' ) );
		add_action( 'merchant_bulk_send_statistics', array( $this, 'send_bulk_usage_statistics_background_process' ) );
		add_action( 'merchant_admin_module_activated', array( $this, 'log_module_activation' ) );
		add_action( 'merchant_single_module_activation_tracking', array( $this, 'log_module_activation_background_process' ) );
		add_action( 'merchant_admin_module_deactivated', array( $this, 'log_module_deactivation' ) );
		add_action( 'merchant_single_module_deactivation_tracking', array( $this, 'log_module_deactivation_background_process' ) );
		add_action( 'merchant_weekly_schedule', array( $this, 'update_last_seen' ) );
	}

	/**
	 * Log module activation
	 *
	 * @param string $module_id The module that was activated.
	 *
	 * @return void
	 */
	public function log_module_activation( $module_id ) {
		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			return;
		}
		$sent = as_next_scheduled_action( 'merchant_single_module_activation_tracking', array( $module_id ), 'merchant' );
		if ( $this->should_send_statistics() && ! $sent ) {
			as_enqueue_async_action(
				'merchant_single_module_activation_tracking',
				array( $module_id ),
				'merchant'
			);
		}
	}

	/**
	 * Log module activation background process
	 *
	 * @param string $module_id The module that was activated.
	 *
	 * @return void
	 */
	public function log_module_activation_background_process( $module_id ) {
		if ( $this->should_send_statistics() ) {
			$site_key   = $this->get_site_key();
			$event_type = 'activate';
			$body       = array(
				'site_key'     => $site_key,
				'event_type'   => $event_type,
				'module_slug'  => $module_id,
				'project_name' => 'merchant',
			);

			$response = wp_remote_post(
				self::WEBSITE_API_LINK . '/' . self::SEND_SINGLE_USAGE_ENDPOINT,
				array(
					'body'      => $body,
				)
			);

			/**
			 * Hook to track the response of the single module activation
			 *
			 * @param string $module_id The module that was activated.
			 * @param array  $response  The response from the API.
			 *
			 * @since 2.1.0
			 */
			do_action( 'merchant_single_module_activation_tracking_background_process', $module_id, $response );;
		}
	}

	/**
	 * Log module deactivation
	 *
	 * @param string $module_id The module that was deactivated.
	 *
	 * @return void
	 */
	public function log_module_deactivation( $module_id ) {
		$sent = as_next_scheduled_action( 'merchant_single_module_deactivation_tracking', array( $module_id ), 'merchant' );
		if ( $this->should_send_statistics() && ! $sent ) {
			as_enqueue_async_action(
				'merchant_single_module_deactivation_tracking',
				array( $module_id ),
				'merchant'
			);
		}
	}

	/**
	 * Log module deactivation background process
	 *
	 * @param string $module_id The module that was deactivated.
	 *
	 * @return void
	 */
	public function log_module_deactivation_background_process( $module_id ) {
		if ( $this->should_send_statistics() ) {
			$site_key   = $this->get_site_key();
			$event_type = 'deactivate';
			$body       = array(
				'site_key'     => $site_key,
				'event_type'   => $event_type,
				'module_slug'  => $module_id,
				'project_name' => 'merchant',
			);

			$response = wp_remote_post(
				self::WEBSITE_API_LINK . '/' . self::SEND_SINGLE_USAGE_ENDPOINT,
				array(
					'body'      => $body,
				)
			);

			/**
			 * Hook to track the response of the single module deactivation
			 *
			 * @param string $module_id The module that was deactivated.
			 * @param array  $response  The response from the API.
			 *
			 * @since 2.1.0
			 */
			do_action( 'merchant_single_module_deactivation_tracking_background_process', $module_id, $response );;
		}
	}

	/**
	 * Send bulk usage statistics
	 *
	 * This method is called during the 'init' action.
	 * It checks if the statistics should be sent and schedules the request if necessary.
	 *
	 * @return void
	 */
	public function send_bulk_usage_statistics() {
		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			return;
		}
		if ( $this->should_send_statistics() && ! empty( merchant_get_active_modules() ) && ! as_next_scheduled_action( 'merchant_bulk_send_statistics', array(), 'merchant' ) ) {
			as_enqueue_async_action(
				'merchant_bulk_send_statistics',
				array(),
				'merchant'
			);
		}
	}

	/**
	 * Send bulk usage statistics background process
	 *
	 * This method is called when the 'merchant_bulk_send_statistics' action is triggered.
	 * It sends the bulk usage statistics to the API.
	 *
	 * @return void
	 */
	public function send_bulk_usage_statistics_background_process() {
		if ( $this->should_send_statistics() && ! $this->is_bulk_statistics_sent() ) {
			$active_modules = merchant_get_active_modules();
			$site_key       = $this->get_site_key();
			$response       = wp_remote_post(
				self::WEBSITE_API_LINK . '/' . self::SEND_BULK_USAGE_ENDPOINT,
				array(
					'body'      => array(
						'site_key'     => $site_key,
						'module_slugs' => $active_modules,
						'project_name' => 'merchant',
						'event_type'   => 'activate',
					),
				)
			);

			if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
				$this->mark_bulk_statistics_sent();
			}

			/**
			 * Hook to track the response of the bulk statistics
			 *
			 * @param array $response The response from the API.
			 *
			 * @since 2.1.0
			 */
			do_action( 'merchant_bulk_send_statistics_background_process', $response );
		}
	}

	/**
	 * Registers the website key if it should be requested.
	 *
	 * This method is called during the 'init' action.
	 * It checks if the site key should be requested and schedules the request if necessary.
	 *
	 * @return void
	 */
	public function register_website() {
		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			return;
		}
		if ( $this->should_request_website_key() && ! as_next_scheduled_action( 'merchant_request_site_key', array(), 'merchant' ) ) {
			as_enqueue_async_action(
				'merchant_request_site_key',
				array(),
				'merchant'
			);
		}
	}

	/**
	 * Registers the website background process.
	 *
	 * This method is called when the 'merchant_request_site_key' action is triggered.
	 * It checks if the site key exists and requests a new one if it doesn't.
	 *
	 * @return void
	 */
	public function register_website_background_process() {
		if ( ! $this->get_site_key() ) {
			$site_key = $this->request_website_key();
			if ( $site_key ) {
				$this->save_site_key( $site_key );
			}
		}
	}

	/**
	 * Check if we should send statistics.
	 *
	 * @return bool
	 */
	public function should_send_statistics() {
		return $this->is_user_consent() && $this->get_site_key();
	}

	/**
	 * Checks if the site key should be requested.
	 *
	 * @return bool True if the site key should be requested, false otherwise.
	 */
	public function should_request_website_key() {
		return $this->is_user_consent() && ! $this->get_site_key();
	}

	/**
	 * Requests a new site key from the API.
	 *
	 * @return string|false The site key if successful, false otherwise.
	 */
	public function request_website_key() {
		$blog_id     = get_option( 'blog_id' );
		$website_url = get_option( 'siteurl' );

		$response = wp_remote_post(
			self::WEBSITE_API_LINK . '/' . self::REGISTER_SITE_ENDPOINT,
			array(
				'body'      => array(
					'site_url' => $website_url,
					'blog_id'  => $blog_id,
				),
			)
		);


		// check if response is 200
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response_body = wp_remote_retrieve_body( $response );
			$response_data = json_decode( $response_body, true );
			// save the new site key
			if ( ! empty( $response_data['data']['site_key'] ) ) {
				return $response_data['data']['site_key'];
			}
		}

		return false;
	}

	/**
	 * Update the last seen timestamp for the site
	 *
	 * This method is called during the 'merchant_weekly_schedule' action.
	 * It sends a request to the API to update the last seen timestamp for the site.
	 *
	 * @return void
	 */
	public function update_last_seen() {
		wp_remote_post(
			self::WEBSITE_API_LINK . '/' . self::SEND_HEART_BEAT_ENDPOINT,
			array(
				'body'      => array(
					'site_key' => $this->get_site_key(),
					'blog_id'  => get_option( 'blog_id' ),
				),
			)
		);
	}

	/**
	 * Checks if the site key is already saved in the database.
	 *
	 * @return string site key if the site key exists, false otherwise.
	 */
	private function get_site_key() {
		return get_option( 'merchant_site_key', false );
	}

	/**
	 * Retrieves the site key from the database.
	 *
	 * @return string|false The site key if it exists, false otherwise.
	 */
	private function save_site_key( $key ) {
		return update_option( 'merchant_site_key', $key, false );
	}

	/**
	 * Checks if the bulk statistics have already been sent.
	 *
	 * @return bool True if the bulk statistics have been sent, false otherwise.
	 */
	private function is_bulk_statistics_sent() {
		return get_option( 'merchant_statistics_sent', false );
	}

	/**
	 * Checks if the user has given consent for tracking.
	 *
	 * @return bool True if the user has given consent, false otherwise.
	 */
	private function is_user_consent() {
		return Merchant_Admin_Options::get( 'global-settings', 'usage_statistics_tracking', true );
	}

	/**
	 * Marks the bulk statistics as sent by updating the corresponding option in the database.
	 *
	 * @return bool True if the option was successfully updated, false otherwise.
	 */
	private function mark_bulk_statistics_sent() {
		return update_option( 'merchant_statistics_sent', true, false );
	}
}

$merchant_statistics_tracking = Merchant_Admin_Statistics_Tracking::instance();
$merchant_statistics_tracking->load_hooks();