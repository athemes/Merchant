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
		add_action( 'wp_ajax_merchant_get_top_performing_campaigns_table_data', array( $this, 'get_analytics_table_data' ) );
		add_action( 'wp_ajax_merchant_get_all_campaigns_table_data', array( $this, 'get_all_campaigns_table_data' ) );
		add_action( 'wp_ajax_merchant_get_impressions_chart_data', array( $this, 'get_impressions_chart_data' ) );
		add_action( 'wp_ajax_merchant_update_campaign_status', array( $this, 'update_campaign_status' ) );
	}

	/**
	 * Get revenue chart data.
	 */
	public function get_revenue_chart_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );

		$this->verify_capability();

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

		$this->verify_capability();

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
	 * Get analytics cards data.
	 */
	public function get_analytics_cards_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );

		$this->verify_capability();

		try {
			$start_date         = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date           = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';
			$compare_start_date = isset( $_GET['compare_start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['compare_start_date'] ) ) : '';
			$compare_end_date   = isset( $_GET['compare_end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['compare_end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' || $compare_start_date === '' || $compare_end_date === '' ) {
				wp_send_json_error( __( 'Invalid date ranges.', 'merchant' ) );
			}
			$start_range     = array(
				'start' => $start_date,
				'end'   => $end_date,
			);
			$compare_range   = array(
				'start' => $compare_start_date,
				'end'   => $compare_end_date,
			);
			$data            = array();
			$added_revenue   = $this->reports->get_reveue_card_report( $start_range, $compare_range );
			$added_orders    = $this->reports->get_total_new_orders_card_report( $start_range, $compare_range );
			$aov_rate        = $this->reports->get_aov_card_report( $start_range, $compare_range );
			$conversion_rate = $this->reports->get_conversion_rate_card_report( $start_range, $compare_range );
			$impressions     = $this->reports->get_impressions_card_report( $start_range, $compare_range );

			$overview_data = array(
				'cards' => array(
					'revenue'         => array(
						'title'   => __( 'Added revenue', 'merchant' ), // Raw string
						'value'   => wc_price( $added_revenue['revenue_second_period'] ), // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $added_revenue['revenue_change'][0], 2 ) . '%', // Raw value
							'class' => $added_revenue['revenue_change'][1], // Raw value
						),
						'tooltip' => __( 'Revenue added by Merchant.', 'merchant' ), // Raw string
					),
					'total-orders'    => array(
						'title'   => __( 'Total orders', 'merchant' ), // Raw string
						'value'   => $added_orders['orders_second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $added_orders['orders_change'][0], 2 ) . '%', // Raw value
							'class' => $added_orders['orders_change'][1], // Raw value
						),
						'tooltip' => __( 'Total number of orders involving Merchant.', 'merchant' ), // Raw string
					),
					'aov'             => array(
						'title'   => __( 'Average order value', 'merchant' ), // Raw string
						'value'   => wc_price( $aov_rate['aov_second_period'] ), // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $aov_rate['change'][0], 2 ) . '%', // Raw value
							'class' => $aov_rate['change'][1], // Raw value
						),
						'tooltip' => __( 'Average order value for Merchant orders.', 'merchant' ), // Raw string
					),
					'conversion-rate' => array(
						'title'   => __( 'Conversion rate', 'merchant' ), // Raw string
						'value'   => wc_format_decimal( $conversion_rate['conversion_second_period'], 2 ) . '%', // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $conversion_rate['change'][0], 2 ) . '%', // Raw value
							'class' => $conversion_rate['change'][1], // Raw value
						),
						'tooltip' => __( 'The percentage of Merchant offer viewers who made a purchase.', 'merchant' ), // Raw string
					),
					'impressions'     => array(
						'title'   => __( 'Impressions', 'merchant' ), // Raw string
						'value'   => $impressions['impressions_second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $impressions['change'][0], 2 ) . '%', // Raw value
							'class' => $impressions['change'][1], // Raw value
						),
						'tooltip' => __( 'The number of times Merchant offers were seen.', 'merchant' ), // Raw string
					),
				),
			);

			ob_start();
			require_once MERCHANT_DIR . 'admin/components/analytics-overview-cards.php';
			$overview_html = ob_get_clean();

			wp_send_json_success( $overview_html );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get analytics table data.
	 */
	public function get_analytics_table_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );

		try {
			$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' ) {
				wp_send_json_error( __( 'Invalid date ranges.', 'merchant' ) );
			}
			$date_range = array(
				'start' => $start_date,
				'end'   => $end_date,
			);
			$data       = $this->reports->get_top_performing_campaigns( $date_range );

			$data = array_map( static function ( $item ) {
				$item['revenue'] = wc_price( $item['revenue'] );

				return $item;
			}, $data );
			wp_send_json_success( $data );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get analytics table data.
	 */
	public function get_all_campaigns_table_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );

		$this->verify_capability();

		try {
			$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' ) {
				wp_send_json_error( __( 'Invalid date ranges.', 'merchant' ) );
			}
			$start_range = array(
				'start' => $start_date,
				'end'   => $end_date,
			);
			$data        = $this->reports->get_all_campaigns( $start_range );

			$data = array_map( static function ( $item ) {
				$item['revenue'] = wc_price( $item['revenue'] ?? '' );

				return $item;
			}, $data );
			wp_send_json_success( $data );
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

		$this->verify_capability();

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

	/**
	 * Update campaign status.
	 *
	 * @return void
	 */
	public function update_campaign_status() {
		check_ajax_referer( 'merchant', 'nonce' );

		$this->verify_capability();

		$campaign_data = $_POST['campaign_data'] ?? array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty( $campaign_data ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'No campaigns found.', 'merchant' ) ), 400 );
		}

		// Get current options
		$db_options = get_option( 'merchant', array() );

		if ( empty( $db_options ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'No campaigns found.', 'merchant' ) ), 400 );
		}

		$should_update = false;
		$new_status    = '';

		foreach ( $campaign_data as $module_id => $option ) {
			if ( ! is_string( $module_id ) || empty( $module_id ) ) {
				continue;
			}

			$campaign_key = sanitize_text_field( $option['campaign_key'] ?? '' );
			$campaigns    = $option['campaigns'] ?? array();

			if ( empty( $campaign_key ) || empty( $campaigns ) ) {
				continue;
			}

			foreach ( $campaigns as $index => $campaign ) {
				$campaign_id = $campaign['campaign_id'] ?? null;
				$status      = sanitize_text_field( $campaign['status'] ?? '' );

				if ( $campaign_id === null || ! in_array( $status, array( 'active', 'inactive' ), true ) ) {
					continue;
				}

				// Check if the campaign exists in the database.
				if ( isset( $db_options[ $module_id ][ $campaign_key ] ) ) {
					$db_campaigns = &$db_options[ $module_id ][ $campaign_key ];

					foreach ( $db_campaigns as &$item ) {
						if ( isset( $item['flexible_id'] ) && $item['flexible_id'] === $campaign_id ) {
							if ( $status === 'active' ) {
								$item['campaign_status'] = 'active';
							} else {
								$item['campaign_status'] = 'inactive';
							}
							$new_status    = $status;
							$should_update = true;
						}
					}
				}
			}
		}

		if ( $should_update ) {
			$updated = update_option( 'merchant', $db_options );
			if ( $updated ) {
				wp_send_json_success(
					array(
						'status'  => $new_status,
						'message' => esc_html__( 'Campaign updated successfully.', 'merchant' ),
					)
				);
			}
		}

		wp_send_json_error( array( 'message' => esc_html__( 'No campaigns were updated.', 'merchant' ) ) );
	}

	/**
	 * Verify capability.
	 */
	private function verify_capability() {
		/**
		 * Filter to verify capability.
		 *
		 * @param bool $verify_capability Default is true.
		 *
		 * @since 1.10.0
		 */
		if ( apply_filters( 'merchant_analytics_data_ajax_verify_capability', true ) && ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to do this.', 'merchant' ), 403 );
		}
	}
}

Merchant_Analytics_Data_Ajax::instance()->load_hooks();
