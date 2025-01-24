<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Merchant_Advanced_Reviews_Analytics
 *
 * This class is responsible for handling the analytics data for the Advanced Reviews module.
 */
class Merchant_Advanced_Reviews_Analytics extends Merchant_Analytics_Data_Reports {

	/**
	 * Merchant_Advanced_Reviews_Analytics constructor.
	 *
	 * @param null $data_provider
	 */
	public function __construct( $data_provider = null ) {
		parent::__construct( $data_provider );
	}

	/**
	 * Load hooks.
	 */
	public function load_hooks() {
		add_action( 'wp_ajax_merchant_get_adv_reviews_analytics_cards_data', array( $this, 'get_adv_reviews_analytics_cards_data' ) );
	}

	/**
	 * Get Advanced Reviews analytics cards data for ajax request.
	 */
	public function get_adv_reviews_analytics_cards_data() {
		// nonce verification.
		check_ajax_referer( 'merchant', 'nonce' );

		$this->verify_capability();

		if ( ! $this->is_active_module() ) {
			wp_send_json_error( array( 'message' => __( 'Advanced Reviews module is not active.', 'merchant' ) ) );
		}
		try {
			$start_date         = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
			$end_date           = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';
			$compare_start_date = isset( $_GET['compare_start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['compare_start_date'] ) ) : '';
			$compare_end_date   = isset( $_GET['compare_end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['compare_end_date'] ) ) : '';

			if ( $start_date === '' || $end_date === '' || $compare_start_date === '' || $compare_end_date === '' ) {
				wp_send_json_error( __( 'Invalid date ranges.', 'merchant' ) );
			}
			$start_range   = array(
				'start' => $start_date,
				'end'   => $end_date,
			);
			$compare_range = array(
				'start' => $compare_start_date,
				'end'   => $compare_end_date,
			);

			$date_ranges       = $this->get_last_and_previous_7_days_ranges();
			$collected_reviews = $this->get_collected_reviews_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$sent_emails       = $this->get_sent_emails_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$scheduled_emails  = $this->get_scheduled_emails_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$open_rate         = $this->get_opened_emails_rate_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
			$overview_data     = array(
				'cards' => array(
					'reviews-collected' => array(
						'title'   => __( 'Reviews collected', 'merchant' ), // Raw string
						'value'   => $collected_reviews['second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $collected_reviews['change'][0], 2 ) . '%', // Raw value
							'class' => $collected_reviews['change'][1], // Raw value
						),
						'tooltip' => __( 'Revenue added by Merchant.', 'merchant' ), // Raw string
					),
					'sent-emails'       => array(
						'title'   => __( 'Sent emails', 'merchant' ), // Raw string
						'value'   => $sent_emails['second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $sent_emails['change'][0], 2 ) . '%', // Raw value
							'class' => $sent_emails['change'][1], // Raw value
						),
						'tooltip' => __( 'Total number of orders involving Merchant.', 'merchant' ), // Raw string
					),
					'scheduled-emails'  => array(
						'title'   => __( 'Scheduled emails', 'merchant' ), // Raw string
						'value'   => $scheduled_emails['second_period'], // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $scheduled_emails['change'][0], 2 ) . '%', // Raw value
							'class' => $scheduled_emails['change'][1], // Raw value
						),
						'tooltip' => __( 'Average order value for Merchant orders.', 'merchant' ), // Raw string
					),
					'open-rate'         => array(
						'title'   => __( 'Open rate', 'merchant' ), // Raw string
						'value'   => wc_format_decimal( $open_rate['second_period'], 2 ) . '%', // Raw value
						'change'  => array(
							'value' => wc_format_decimal( $open_rate['change'][0], 2 ) . '%', // Raw value
							'class' => $open_rate['change'][1], // Raw value
						),
						'tooltip' => __( 'The percentage of Merchant offer viewers who made a purchase.', 'merchant' ), // Raw string
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
	 * Get collected reviews card report.
	 *
	 * @param array $first_period The first period.
	 * @param array $second_period The second period.
	 *
	 * @return array
	 */
	public function get_collected_reviews_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$count_first_period = (int) $this->data_provider->get_collected_reviews_count();
		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$count_second_period = (int) $this->data_provider->get_collected_reviews_count();

		$diff_count = $count_second_period - $count_first_period;

		$change = $this->calculate_percentage_difference( $count_second_period, $count_first_period );

		return array(
			'change'        => $change,
			'count'         => $diff_count,
			'first_period'  => $count_first_period,
			'second_period' => $count_second_period,
		);
	}

	/**
	 * Get scheduled emails card report.
	 *
	 * @param array $first_period The first period.
	 * @param array $second_period The second period.
	 *
	 * @return array
	 */
	public function get_scheduled_emails_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$count_first_period = (int) $this->data_provider->get_scheduled_emails_count();
		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$count_second_period = (int) $this->data_provider->get_scheduled_emails_count();

		$diff_count = $count_second_period - $count_first_period;

		$change = $this->calculate_percentage_difference( $count_second_period, $count_first_period );

		return array(
			'change'        => $change,
			'count'         => $diff_count,
			'first_period'  => $count_first_period,
			'second_period' => $count_second_period,
		);
	}

	/**
	 * Get sent emails card report.
	 *
	 * @param array $first_period The first period.
	 * @param array $second_period The second period.
	 *
	 * @return array
	 */
	public function get_sent_emails_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$count_first_period = (int) $this->data_provider->get_sent_emails_count();
		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$count_second_period = (int) $this->data_provider->get_sent_emails_count();

		$diff_count = $count_second_period - $count_first_period;

		$change = $this->calculate_percentage_difference( $count_second_period, $count_first_period );

		return array(
			'change'        => $change,
			'count'         => $diff_count,
			'first_period'  => $count_first_period,
			'second_period' => $count_second_period,
		);
	}

	/**
	 * Get opened emails rate report.
	 *
	 * @param array $first_period The first period.
	 * @param array $second_period The second period.
	 *
	 * @return array
	 */
	public function get_opened_emails_rate_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );
		$count_sent_second_period = (int) $this->data_provider->get_sent_emails_count();
		$count_sent_second_period = $count_sent_second_period > 0 ? $count_sent_second_period : 1;
		$count_opened_second_period = (int) $this->data_provider->get_opened_emails_count();
		$rate_second_period = $count_opened_second_period / $count_sent_second_period * 100;

		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );
		$count_sent_first_period = (int) $this->data_provider->get_sent_emails_count();
		$count_sent_first_period = $count_sent_first_period > 0 ? $count_sent_first_period : 1;
		$count_opened_first_period = (int) $this->data_provider->get_opened_emails_count();
		$rate_first_period = $count_opened_first_period / $count_sent_first_period * 100;

		$change = $this->calculate_percentage_difference( $rate_second_period, $rate_first_period );

		return array(
			'change'        => $change,
			'count'         => $rate_second_period,
			'first_period'  => $rate_first_period,
			'second_period' => $rate_second_period,
		);
	}

	/**
	 * Check if the Advanced Reviews module is active.
	 *
	 * @return bool
	 */
	private function is_active_module() {
		return Merchant_Modules::is_module_active( 'advanced-reviews' );
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

$merchant_advanced_reviews_analytics = new Merchant_Advanced_Reviews_Analytics();
$merchant_advanced_reviews_analytics->load_hooks();