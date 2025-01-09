<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Merchant_Analytics_Data
 *
 * This class is responsible for providing ajax hooks for analytics.
 */
class Merchant_Analytics_Data_Ajax {
	/**
	 * The single class instance.
	 *
	 * @var Merchant_Analytics_Data_Ajax|null
	 */
	private static $instance = null;

	/**
	 * @var Merchant_Analytics_Data_Reports
	 */
	private $reports;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->reports = new Merchant_Analytics_Data_Reports();
	}

	/**
	 * Get the single class instance.
	 *
	 * @return Merchant_Analytics_Data_Ajax|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load WordPress hooks.
	 */
	public function load_hooks() {
		add_action( 'wp_ajax_merchant_get_revenue_chart_data', array( $this, 'get_revenue_chart_data' ) );
		add_action( 'wp_ajax_merchant_get_avg_order_value_chart_data', array( $this, 'get_aov_chart_data' ) );
		add_action( 'wp_ajax_merchant_get_analytics_cards_data', array( $this, 'get_analytics_cards_data' ) );
		add_action( 'wp_ajax_merchant_get_impressions_chart_data', array( $this, 'get_impressions_chart_data' ) );
	}

	/**
	 * Get revenue chart data.
	 */
	public function get_revenue_chart_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );
		try {
			// Get the date ranges.
			$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' ) {
				wp_send_json_error( __( 'Invalid date range.', 'merchant' ) );
			}

			wp_send_json_success( $this->reports->get_revenue_chart_report( $start_date, $end_date ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get average order value chart data.
	 */
	public function get_aov_chart_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );

		try {
			// Get the date ranges.
			$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' ) {
				wp_send_json_error( __( 'Invalid date range.', 'merchant' ) );
			}

			wp_send_json_success( $this->reports->get_aov_chart_report( $start_date, $end_date ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get impressions chart data.
	 */
	public function get_impressions_chart_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );
		try {
			// Get the date ranges.
			$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' ) {
				wp_send_json_error( __( 'Invalid date range.', 'merchant' ) );
			}

			wp_send_json_success( $this->reports->get_impressions_chart_report( $start_date, $end_date ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
}

Merchant_Analytics_Data_Ajax::instance()->load_hooks();