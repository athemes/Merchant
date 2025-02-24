<?php
/**
 * Merchant_Option Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_General_Hooks' ) ) {
	class Merchant_General_Hooks {

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
		private function __construct() {
		}

		/**
		 * Attach functions to WordPress
		 *
		 * @return void
		 */
		public function load_hooks() {
			add_action( 'init', array( $this, 'register_weekly_schedule' ) );
		}

		/**
		 * Register weekly schedule.
		 *
		 * @return void
		 */
		public function register_weekly_schedule() {
			if ( function_exists( 'as_schedule_recurring_action' ) ) {
				if ( ! as_next_scheduled_action( 'merchant_weekly_schedule' ) ) {
					as_schedule_recurring_action( time(), WEEK_IN_SECONDS, 'merchant_weekly_schedule' );
				}

				return;
			}

			if ( ! wp_next_scheduled( 'merchant_weekly_schedule' ) ) {
				wp_schedule_event( time(), 'weekly', 'merchant_weekly_schedule' );
			}
		}
	}
}

$merchant_general_hooks = Merchant_General_Hooks::instance();
$merchant_general_hooks->load_hooks();