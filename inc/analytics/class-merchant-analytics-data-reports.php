<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Merchant_Analytics_Data
 *
 * This class is responsible for providing data for analytics.
 */
class Merchant_Analytics_Data_Reports {
	/**
	 * @var Merchant_Analytics_Data_Provider
	 */
	protected $data_provider;

	/**
	 * Constructor.
	 *
	 * @param Merchant_Analytics_Data_Provider|null $data_provider The data provider instance (optional).
	 */
	public function __construct( $data_provider = null ) {
		if ( $data_provider === null ) {
			$this->data_provider = new Merchant_Analytics_Data_Provider();
		} else {
			$this->data_provider = $data_provider;
		}
	}

	/**
	 * Get date ranges for the last 7 days and the 7 days before that.
	 *
	 * @return array[] An array containing the last 7 days and the 7 days before that date ranges.
	 */
	public function get_last_and_previous_7_days_ranges() {
		// Get the current date and time
		$now = new DateTime();

		// Calculate the last 7 days range
		$last_7_days_end   = clone $now;
		$last_7_days_start = clone $now;
		$last_7_days_start->modify( '-7 days' );

		// Calculate the 7 days before the last 7 days range
		$previous_7_days_end   = clone $last_7_days_start; // End of the previous range is the start of the last 7 days
		$previous_7_days_start = clone $last_7_days_start;
		$previous_7_days_start->modify( '-7 days' );

		// Format the dates to match the required format
		$last_7_days_range = array(
			'start' => $last_7_days_start->format( 'm/d/y' ),
			'end'   => $last_7_days_end->format( 'm/d/y' ),
		);

		$previous_7_days_range = array(
			'start' => $previous_7_days_start->format( 'm/d/y' ),
			'end'   => $previous_7_days_end->format( 'm/d/y' ),
		);

		// Return the ranges as an array
		return array(
			'recent_period' => $last_7_days_range,
			'last_period'   => $previous_7_days_range,
		);
	}

	/**
	 * Get the revenue card report for the given date ranges.
	 *
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_reveue_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$revenue_first_period = $this->data_provider->get_revenue();

		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$revenue_second_period = $this->data_provider->get_revenue();

		$net_revenue_change = $revenue_second_period - $revenue_first_period;
		$revenue_change     = $this->calculate_percentage_difference( $revenue_second_period, $revenue_first_period );

		return array(
			'net_revenue_change'             => $net_revenue_change,
			'revenue_change'                 => $revenue_change,
			'revenue_first_period'           => $revenue_first_period,
			'revenue_first_period_currency'  => wc_price( $revenue_first_period ),
			'revenue_second_period'          => $revenue_second_period,
			'revenue_second_period_currency' => wc_price( $revenue_second_period ),
		);
	}

	/**
	 * Get total new orders card report for the given date ranges.
	 *
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_total_new_orders_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$orders_first_period = (int) $this->data_provider->get_orders_count();
		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$orders_second_period = (int) $this->data_provider->get_orders_count();

		$new_orders_count = $orders_second_period - $orders_first_period;

		$change = $this->calculate_percentage_difference( $orders_second_period, $orders_first_period );

		return array(
			'orders_change'        => $change,
			'new_orders_count'     => $new_orders_count,
			'orders_first_period'  => $orders_first_period,
			'orders_second_period' => $orders_second_period,
		);
	}

	/**
	 * Get average order value (AOV) card report for the given date ranges.
	 *
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_aov_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$aov_first_period = $this->data_provider->get_average_order_value();

		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$aov_second_period = $this->data_provider->get_average_order_value();

		$diff = $aov_second_period - $aov_first_period;

		$change = $this->calculate_percentage_difference( $aov_second_period, $aov_first_period );

		return array(
			'diff'                       => $diff,
			'change'                     => $change,
			'aov_first_period'           => $aov_first_period,
			'aov_first_period_currency'  => wc_price( $aov_first_period ),
			'aov_second_period'          => $aov_second_period,
			'aov_second_period_currency' => wc_price( $aov_second_period ),
		);
	}

	/**
	 * Get conversion rate card report for the given date ranges.
	 *
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_conversion_rate_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$conversion_first_period = $this->data_provider->get_conversion_rate_percentage();

		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$conversion_second_period = $this->data_provider->get_conversion_rate_percentage();

		$diff = $conversion_second_period - $conversion_first_period;

		$change = $this->calculate_percentage_difference( $conversion_second_period, $conversion_first_period );

		return array(
			'diff'                                => $diff,
			'change'                              => $change,
			'conversion_first_period'             => $conversion_first_period,
			'conversion_first_period_percentage'  => wc_format_decimal( $conversion_first_period, 2 ) . '%',
			'conversion_second_period'            => $conversion_second_period,
			'conversion_second_period_percentage' => wc_format_decimal( $conversion_second_period, 2 ) . '%',
		);
	}

	/**
	 * Get impressions card report for the given date ranges.
	 *
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_impressions_card_report( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$impressions_first_period = (int) $this->data_provider->get_total_impressions();

		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$impressions_second_period = (int) $this->data_provider->get_total_impressions();

		$diff = $impressions_second_period - $impressions_first_period;

		$change = $this->calculate_percentage_difference( $impressions_second_period, $impressions_first_period );

		return array(
			'diff'                      => $diff,
			'change'                    => $change,
			'impressions_first_period'  => $impressions_first_period,
			'impressions_second_period' => $impressions_second_period,
		);
	}

	/**
	 * Get revenue data for the given date range.
	 *
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 *
	 * @return array
	 */
	public function get_revenue_chart_report( $start_date, $end_date ) {
		$this->data_provider->set_start_date( $start_date );
		$this->data_provider->set_end_date( $end_date );

		/**
		 * Filter the maximum number of orders to retrieve for revenue chart.
		 *
		 * @param int $limit The maximum number of orders to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit = apply_filters( 'merchant_analytics_max_orders_limit_revenue', - 1 );

		$orders = $this->data_provider->get_dated_orders_with_revenue( $limit );

		// Sort orders by timestamp (ascending order)
		$sorted_orders = $this->sort_orders_by_timestamp( $orders );

		// Determine the best grouping interval based on the date range
		$interval = $this->determine_grouping_interval( $start_date, $end_date );

		// Group data into the selected interval and calculate total revenue
		$chart_data = $this->group_data_by_interval( $sorted_orders, $interval, $start_date, $end_date, 'revenue' );

		return $chart_data;
	}

	/**
	 * Get average order value (AOV) data for the given date range.
	 *
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 *
	 * @return array
	 */
	public function get_aov_chart_report( $start_date, $end_date ) {
		$this->data_provider->set_start_date( $start_date );
		$this->data_provider->set_end_date( $end_date );

		/**
		 * Filter the maximum number of orders to retrieve for AOV chart.
		 *
		 * @param int $limit The maximum number of orders to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit = apply_filters( 'merchant_analytics_max_orders_limit_aov', - 1 );

		$orders = $this->data_provider->get_dated_orders_with_revenue( $limit );

		// Sort orders by timestamp (ascending order)
		$sorted_orders = $this->sort_orders_by_timestamp( $orders );

		// Determine the best grouping interval based on the date range
		$interval = $this->determine_grouping_interval( $start_date, $end_date );

		// Group data into the selected interval and calculate AOV
		$chart_data = $this->group_data_by_interval( $sorted_orders, $interval, $start_date, $end_date, 'aov' );

		return $chart_data;
	}

	/**
	 * Get impressions data for the given date range.
	 *
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 *
	 * @return array
	 */
	public function get_impressions_chart_report( $start_date, $end_date ) {
		$this->data_provider->set_start_date( $start_date );
		$this->data_provider->set_end_date( $end_date );

		/**
		 * Filter the maximum number of impressions to retrieve for the impressions chart.
		 *
		 * @param int $limit The maximum number of impressions to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit = apply_filters( 'merchant_analytics_max_impressions_limit', - 1 );

		$impressions = $this->data_provider->get_dated_impressions( $limit );

		// Sort impressions by timestamp (ascending order)
		$sorted_impressions = $this->sort_impressions_by_timestamp( $impressions );

		// Determine the best grouping interval based on the date range
		$interval = $this->determine_grouping_interval( $start_date, $end_date );

		// Group data into the selected interval and calculate total impressions
		$chart_data = $this->group_impressions_by_interval( $sorted_impressions, $interval, $start_date, $end_date );

		return $chart_data;
	}

	/**
	 * Get top performing campaigns for the given date ranges.
	 *
	 * @param array $date_range The date range.
	 *
	 * @return array
	 */
	public function get_top_performing_campaigns( $date_range ) {
		$this->data_provider->set_start_date( $date_range['start'] );
		$this->data_provider->set_end_date( $date_range['end'] );
		$campaigns = array();
		/**
		 * Filter the maximum number of top performing campaigns to retrieve.
		 *
		 * @param int $limit The maximum number of top performing campaigns to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit        = apply_filters( 'merchant_analytics_top_performing_campaigns_limit', 10 );
		$db_campaigns = $this->data_provider->get_top_performing_campaigns( $limit );

		/**
		 * Filter the top performing campaigns data.
		 *
		 * @param array $db_campaigns The top performing campaigns data.
		 * @param array $date_range   The date range.
		 *
		 * @since 2.0.0
		 */
		$db_campaigns = apply_filters( 'merchant_analytics_top_performing_campaigns', $db_campaigns, $date_range );
		if ( ! empty( $db_campaigns ) ) {
			foreach ( $db_campaigns as $campaign ) {
				$module_id     = $campaign['module_id'];
				$campaign_id   = $campaign['campaign_id'];
				$campaign_info = merchant_get_campaign_data( $campaign_id, $module_id );
				$impressions   = $this->data_provider->get_campaign_impressions( $campaign_id, $module_id );
				if ( ! empty( $campaign_info ) ) {
					$modules = $this->get_analytics_modules();
					if ( array_key_exists( $module_id, $modules ) ) {
						$module      = $modules[ $module_id ];
						$campaigns[] = array(
							'module_id'     => $module_id,
							'campaign_id'   => $campaign_id,
							'revenue'       => $module['metrics']['revenue'] ? $this->data_provider->get_campaign_revenue( $campaign_id, $module_id ) : '-',
							'orders'        => $module['metrics']['orders_count'] ? $this->data_provider->get_campaign_orders_count( $campaign_id, $module_id ) : '-',
							'aov'           => $module['metrics']['aov'] ? $this->data_provider->get_campaign_average_order_value( $campaign_id, $module_id ) : '-',
							'ctr'           => $module['metrics']['ctr'] ? $this->get_campaign_ctr_change( $campaign_id, $module_id, $date_range ) : '-',
							'clicks'        => $module['metrics']['clicks'] ? $this->data_provider->get_campaign_clicks( $campaign_id, $module_id ) : '-',
							'impressions'   => $module['metrics']['impressions'] ? $impressions : '-',
							'campaign_info' => $campaign_info,
						);
					}
				}
			}
		}

		/**
		 * Filter the top performing campaigns data.
		 *
		 * @param array $campaigns  The top performing campaigns data.
		 * @param array $date_range The date range.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'merchant_analytics_top_performing_campaigns_data', $campaigns, $date_range );
	}

	/**
	 * Get all campaigns for the given date ranges.
	 *
	 * @param $period
	 *
	 * @return array
	 */
	public function get_all_campaigns( $period ) {
		$campaigns_data = array();
		$all_modules    = $this->get_analytics_modules();

		if ( empty( $all_modules ) ) {
			return $campaigns_data;
		}

		foreach ( $all_modules as $module_id => $module ) {
			$data      = array();
			$campaigns = $module['data']['campaigns'] ?? array();
			$this->data_provider->set_start_date( $period['start'] );
			$this->data_provider->set_end_date( $period['end'] );
			if ( $module['module_object']->analytics_metrics()['campaigns'] === true ) {
				foreach ( $campaigns as $campaign_id => $campaign ) {
					$campaign_url = add_query_arg( array( 'page' => 'merchant', 'module' => $module_id, 'campaign_id' => $campaign_id ), 'admin.php' );
					$impressions  = $this->data_provider->get_campaign_impressions( $campaign_id, $module_id );
					$revenue      = $this->data_provider->get_campaign_revenue( $campaign_id, $module_id );
					// Prepare each campaign data
					$data[] = array(
						'campaign_key'   => $campaign['campaign_key'] ?? '',
						'campaign_id'    => $campaign_id,
						'title'          => $campaign['campaign_title'] ?? '',
						'status'         => $campaign['campaign_status'] ?? 'active',
						'impression'     => $module['metrics']['impressions'] ? $impressions : '-',
						'clicks'         => $module['metrics']['clicks'] ? $this->data_provider->get_campaign_clicks( $campaign_id, $module_id ) : '-',
						'revenue'        => $module['metrics']['revenue'] ? wc_price( $revenue ) : '-',
						'revenue_number' => $module['metrics']['revenue'] ? $revenue : '-',
						'ctr'            => $module['metrics']['ctr'] ? $this->get_campaign_ctr_change( $campaign_id, $module_id, $period ) : '-',
						'orders'         => $module['metrics']['orders_count'] ? $this->data_provider->get_campaign_orders_count( $campaign_id, $module_id ) : '-',
						'url'            => $campaign_url,
					);
				}
			} else {
				$module_url  = add_query_arg( array( 'page' => 'merchant', 'module' => $module_id ), 'admin.php' );
				$impressions = $this->data_provider->get_module_impressions( $module_id );
				$revenue     = $this->data_provider->get_module_revenue( $module_id );
				// Prepare the module data
				$data[] = array(
					'campaign_key'   => '',
					'campaign_id'    => '',
					'title'          => '-',
					'status'         => 'n\a',
					'impression'     => $module['metrics']['impressions'] ? $impressions : '-',
					'clicks'         => $module['metrics']['clicks'] ? $this->data_provider->get_module_clicks( $module_id ) : '-',
					'revenue'        => $module['metrics']['revenue'] ? wc_price( $revenue ) : '-',
					'revenue_number' => $module['metrics']['revenue'] ? $revenue : '-',
					'ctr'            => $module['metrics']['ctr'] ? $this->get_module_ctr_change( $module_id, $period ) : '-',
					'orders'         => $module['metrics']['orders_count'] ? $this->data_provider->get_module_orders_count( $module_id ) : '-',
					'url'            => $module_url,
				);
			}

			// Prepare the campaigns data
			$campaigns_data[] = array(
				'module_id'   => $module_id,
				'module_name' => esc_html( $module['data']['name'] ?? '' ),
				'campaigns'   => $data,
			);
		}

		/**
		 * Filter all campaigns data for the campaigns report.
		 *
		 * @param array $campaigns_data All campaigns data.
		 * @param array $period         The first date range.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'merchant_analytics_all_campaigns_data', $campaigns_data, $period );
	}

	/**
	 * Get the CTR change for the given campaign and date ranges.
	 *
	 * @param int   $campaign_id The campaign ID.
	 * @param int   $module_id   The module ID.
	 * @param array $period      The first date range.
	 *
	 * @return int The CTR change percentage.
	 */
	public function get_campaign_ctr_change( $campaign_id, $module_id, $period ) {
		$this->data_provider->set_start_date( $period['start'] );
		$this->data_provider->set_end_date( $period['end'] );

		return (int) $this->data_provider->get_campaign_ctr_percentage( $campaign_id, $module_id );
	}

	/**
	 * Prepare the data for the main analytics cards report component.
	 *
	 * @return array The main analytics cards report data.
	 */
	public function main_analytics_cards_report() {
		$date_ranges     = $this->get_last_and_previous_7_days_ranges();
		$added_revenue   = $this->get_reveue_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
		$added_orders    = $this->get_total_new_orders_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
		$aov_rate        = $this->get_aov_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
		$conversion_rate = $this->get_conversion_rate_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );
		$impressions     = $this->get_impressions_card_report( $date_ranges['last_period'], $date_ranges['recent_period'] );

		return array(
			'section_title' => __( 'Merchant Analytics Dashboard', 'merchant' ), // Raw string
			'date_ranges'   => $date_ranges,
			'action'        => 'merchant_get_analytics_cards_data',
			'cards'         => array(
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
	}

	/**
	 * Get the CTR change for the given module and date ranges.
	 *
	 * @param int   $module_id     The module ID.
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return int The CTR change percentage.
	 */
	public function get_module_ctr_change( $module_id, $first_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		return (int) $this->data_provider->get_module_ctr_percentage( $module_id );
	}

	/**
	 * Get all analytics modules.
	 *
	 * @return array
	 */
	protected function get_analytics_modules() {
		$modules = array();

		/**
		 * Filter all modules data for the campaigns report.
		 *
		 * @param array $all_modules All modules data.
		 *
		 * @since 2.0.0
		 */
		$all_modules = apply_filters( 'merchant_analytics_all_modules_data_campaigns_table', merchant_get_modules_data() );
		foreach ( $all_modules as $module_id => $module ) {
			if ( Merchant_Modules::is_module_active( $module_id ) ) {
				$module_object = Merchant_Modules::get_module( $module_id );
				if ( $module_object && $module_object->has_analytics() ) {
					$modules[ $module_id ] = array(
						'module_object' => $module_object,
						'metrics'       => $module_object->analytics_metrics(),
						'data'          => $module,
					);
				}
			}
		}

		return $modules;
	}

	/**
	 * Sort orders by timestamp in ascending order.
	 *
	 * @param array $orders The orders to sort.
	 *
	 * @return array
	 */
	protected function sort_orders_by_timestamp( $orders ) {
		usort( $orders, static function ( $a, $b ) {
			return strtotime( $a['timestamp'] ) - strtotime( $b['timestamp'] );
		} );

		return $orders;
	}

	/**
	 * Sort impressions by timestamp in ascending order.
	 *
	 * @param array $impressions The impressions to sort.
	 *
	 * @return array
	 */
	protected function sort_impressions_by_timestamp( $impressions ) {
		usort( $impressions, static function ( $a, $b ) {
			return strtotime( $a['timestamp'] ) - strtotime( $b['timestamp'] );
		} );

		return $impressions;
	}

	/**
	 * Determine the best grouping interval based on the date range.
	 *
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 *
	 * @return string
	 */
	protected function determine_grouping_interval( $start_date, $end_date ) {
		$days_between = $this->calculate_days_between_dates( $start_date, $end_date );

		if ( $days_between > 365 ) {
			return 'yearly'; // Use yearly grouping for very large date ranges
		}

		if ( $days_between > 90 ) {
			return 'monthly'; // Use monthly grouping for large date ranges
		}

		if ( $days_between > 30 ) {
			return 'weekly'; // Use weekly grouping (20 days) for medium date ranges
		}

		return 'daily'; // Use daily grouping for small date ranges
	}

	/**
	 * Calculate the number of days between two dates.
	 *
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 *
	 * @return int
	 */
	protected function calculate_days_between_dates( $start_date, $end_date ) {
		$start_date_time = new DateTime( $start_date );
		$end_date_time   = new DateTime( $end_date );

		return $start_date_time->diff( $end_date_time )->days;
	}

	/**
	 * Group data into intervals (daily, weekly, monthly, yearly) and calculate the desired metric.
	 *
	 * @param array  $orders     The orders to group.
	 * @param string $interval   The interval to group by ('daily', 'weekly', 'monthly', 'yearly').
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 * @param string $metric     The metric to calculate ('revenue' or 'aov').
	 *
	 * @return array
	 */
	protected function group_data_by_interval( $orders, $interval, $start_date, $end_date, $metric = 'revenue' ) {
		$grouped_data        = array();
		$current_group_start = strtotime( $start_date );
		$end_timestamp       = strtotime( $end_date );
		$previous_value      = null;

		// Loop through each interval
		while ( $current_group_start <= $end_timestamp ) {
			// Determine the interval end date
			$interval_end = $this->calculate_interval_end( $current_group_start, $interval );

			// Find orders within the current interval
			$current_group = $this->get_orders_in_interval( $orders, $current_group_start, $interval_end );

			// Calculate total revenue and orders count for the group
			$total_revenue  = array_sum( array_column( $current_group, 'revenue' ) );
			$order_subtotal = array_sum( array_column( $current_group, 'order_subtotal' ) );
			$orders_count   = count( $current_group );

			// Calculate the desired metric
			if ( $metric === 'aov' ) {
				$value = $orders_count > 0 ? $order_subtotal / $orders_count : 0; // AOV
			} else {
				$value = $total_revenue; // Total revenue
			}

			// Calculate percentage difference
			list( $difference, $diff_type ) = $this->calculate_percentage_difference( $value, $previous_value );

			// Format the x-axis label based on the interval
			$x_label = $this->format_x_axis_label( $current_group_start, $interval_end, $interval );

			// Add the group to the chart data
			$grouped_data[] = array(
				'x'               => $x_label,
				'y'               => wc_format_decimal( $value ),
				'number_currency' => wc_price( $value ),
				'orders_count'    => $orders_count,
				'diff_type'       => $diff_type,
				'difference'      => $difference,
				'timestamp'       => gmdate( 'Y-m-d H:i:s', $current_group_start ), // Start of the interval
			);

			// Update previous_value for the next group
			$previous_value = $value;

			// Move to the next interval
			$current_group_start = $interval_end;
		}

		return $grouped_data;
	}

	/**
	 * Group impressions into intervals (daily, weekly, monthly, yearly).
	 *
	 * @param array  $impressions The impressions to group.
	 * @param string $interval    The interval to group by ('daily', 'weekly', 'monthly', 'yearly').
	 * @param string $start_date  Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date    End date in 'Y-m-d H:i:s' format.
	 *
	 * @return array
	 */
	protected function group_impressions_by_interval( $impressions, $interval, $start_date, $end_date ) {
		$grouped_data        = array();
		$current_group_start = strtotime( $start_date );
		$end_timestamp       = strtotime( $end_date );
		$previous_value      = null;

		// Loop through each interval
		while ( $current_group_start <= $end_timestamp ) {
			// Determine the interval end date
			$interval_end = $this->calculate_interval_end( $current_group_start, $interval );

			// Find impressions within the current interval
			$current_group = $this->get_impressions_in_interval( $impressions, $current_group_start, $interval_end );

			// Calculate total impressions for the group
			$total_impressions = array_sum( array_column( $current_group, 'impressions_count' ) );

			// Calculate percentage difference
			list( $difference, $diff_type ) = $this->calculate_percentage_difference( $total_impressions, $previous_value );

			// Format the x-axis label based on the interval
			$x_label = $this->format_x_axis_label( $current_group_start, $interval_end, $interval );

			// Add the group to the chart data
			$grouped_data[] = array(
				'x'                 => $x_label,
				'y'                 => $total_impressions,
				'impressions_count' => count( $current_group ), // Number of impression records
				'diff_type'         => $diff_type,
				'difference'        => $difference,
				'timestamp'         => gmdate( 'Y-m-d H:i:s', $current_group_start ), // Start of the interval
			);

			// Update previous_value for the next group
			$previous_value = $total_impressions;

			// Move to the next interval
			$current_group_start = $interval_end;
		}

		return $grouped_data;
	}

	/**
	 * Calculate the end of the interval based on the interval type.
	 *
	 * @param int    $current_group_start The start timestamp of the interval.
	 * @param string $interval            The interval type ('daily', 'weekly', 'monthly', 'yearly').
	 *
	 * @return int
	 */
	protected function calculate_interval_end( $current_group_start, $interval ) {
		if ( $interval === 'daily' ) {
			return strtotime( '+1 day', $current_group_start );
		}

		if ( $interval === 'weekly' ) {
			return strtotime( '+20 days', $current_group_start ); // 20-day "weekly" interval
		}

		if ( $interval === 'monthly' ) {
			return strtotime( '+1 month', $current_group_start );
		}

		if ( $interval === 'yearly' ) {
			return strtotime( '+1 year', $current_group_start );
		}

		return $current_group_start; // Default to start timestamp
	}

	/**
	 * Get orders within the specified interval.
	 *
	 * @param array $orders         The orders to filter.
	 * @param int   $interval_start The start timestamp of the interval.
	 * @param int   $interval_end   The end timestamp of the interval.
	 *
	 * @return array
	 */
	protected function get_orders_in_interval( $orders, $interval_start, $interval_end ) {
		return array_filter( $orders, function ( $order ) use ( $interval_start, $interval_end ) {
			$timestamp = strtotime( $order['timestamp'] );

			return $timestamp >= $interval_start && $timestamp < $interval_end;
		} );
	}

	/**
	 * Get impressions within the specified interval.
	 *
	 * @param array $impressions    The impressions to filter.
	 * @param int   $interval_start The start timestamp of the interval.
	 * @param int   $interval_end   The end timestamp of the interval.
	 *
	 * @return array
	 */
	protected function get_impressions_in_interval( $impressions, $interval_start, $interval_end ) {
		return array_filter( $impressions, function ( $impression ) use ( $interval_start, $interval_end ) {
			$timestamp = strtotime( $impression['timestamp'] );

			return $timestamp >= $interval_start && $timestamp < $interval_end;
		} );
	}

	/**
	 * Calculate the percentage difference between the current and previous value.
	 *
	 * @param float      $current_value  The current value.
	 * @param float|null $previous_value The previous value.
	 *
	 * @return array [difference, diff_type]
	 */
	protected function calculate_percentage_difference( $current_value, $previous_value ) {
		if ( $previous_value !== null || is_numeric( $previous_value ) ) {
			if ( $previous_value === 0 ) {
				// If previous value is 0, handle it as a special case
				if ( $current_value === 0 ) {
					return array( 0, 'none' ); // No change
				}

				return array( 100, 'increase' ); // Infinite increase (from 0 to any positive value)
			}

			$difference = ( ( $current_value - $previous_value ) / $previous_value ) * 100;
			$diff_type  = ( $difference >= 0 ) ? 'increase' : 'decrease';

			if ( $difference === 0 ) {
				$diff_type = 'none';
			}

			return array( round( abs( $difference ), 2 ), $diff_type ); // Round to 2 decimal places
		}

		return array( 0, 'none' ); // No previous data to compare
	}

	/**
	 * Format the x-axis label based on the interval.
	 *
	 * @param int    $current_group_start The start timestamp of the interval.
	 * @param int    $interval_end        The end timestamp of the interval.
	 * @param string $interval            The interval type ('daily', 'weekly', 'monthly', 'yearly').
	 *
	 * @return string
	 */
	protected function format_x_axis_label( $current_group_start, $interval_end, $interval ) {
		if ( $interval === 'daily' ) {
			return gmdate( 'j M', $current_group_start ); // e.g., "1 Jan"
		}

		if ( $interval === 'weekly' ) {
			return gmdate( 'j M', $current_group_start ) . ' - ' . gmdate( 'j M', $interval_end - 1 ); // e.g., "1 Jan - 20 Jan"
		}

		if ( $interval === 'monthly' ) {
			return gmdate( 'F', $current_group_start ); // e.g., "January"
		}

		if ( $interval === 'yearly' ) {
			return gmdate( 'Y', $current_group_start ); // e.g., "2024"
		}

		return gmdate( 'Y-m-d', $current_group_start ); // Default to full date
	}
}