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
	private $data_provider;

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
			'start' => $last_7_days_start->format( 'Y-m-d H:i:s' ),
			'end'   => $last_7_days_end->format( 'Y-m-d H:i:s' ),
		);

		$previous_7_days_range = array(
			'start' => $previous_7_days_start->format( 'Y-m-d H:i:s' ),
			'end'   => $previous_7_days_end->format( 'Y-m-d H:i:s' ),
		);

		// Return the ranges as an array
		return array(
			'last_7_days'     => $last_7_days_range,
			'previous_7_days' => $previous_7_days_range,
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
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_top_performing_campaigns( $first_period, $second_period ) {
		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );
		$campaigns    = array();
		$db_campaigns = $this->data_provider->get_top_performing_campaigns( 10 );
		if ( ! empty( $db_campaigns ) ) {
			foreach ( $db_campaigns as $campaign ) {
				$this->data_provider->set_start_date( $second_period['start'] );
				$this->data_provider->set_end_date( $second_period['end'] );
				$module_id     = $campaign['module_id'];
				$campaign_id   = $campaign['campaign_id'];
				$campaign_info = merchant_get_campaign_data( $campaign_id, $module_id );
				$impressions   = $this->data_provider->get_campaign_impressions( $campaign_id, $module_id );
				if ( ! empty( $campaign_info ) ) {
					$campaigns[] = array(
						'module_id'     => $module_id,
						'campaign_id'   => $campaign_id,
						'revenue'       => $this->data_provider->get_campaign_revenue( $campaign_id, $module_id ),
						'orders'        => $this->data_provider->get_campaign_orders_count( $campaign_id, $module_id ),
						'aov'           => $this->data_provider->get_campaign_average_order_value( $campaign_id, $module_id ),
						'ctr'           => $this->get_campaign_ctr_change( $campaign_id, $module_id, $first_period, $second_period ),
						'clicks'        => $this->data_provider->get_campaign_clicks( $campaign_id, $module_id ),
						// todo: add list for valid modules that can have impressions
						'impressions'   => $impressions === 0 ? '-' : $impressions,
						'campaign_info' => $campaign_info,
					);
				}
			}
		}

		return $campaigns;
	}

	/**
	 * Get all campaigns for the given date ranges.
	 *
	 * @param $first_period
	 * @param $second_period
	 *
	 * @return array
	 */
	public function get_all_campaigns( $first_period, $second_period ) {
		$campaigns_data = array();

		/**
		 * Filter all modules data for the campaigns report.
		 *
		 * @param array $all_modules All modules data.
		 *
		 * @since 2.0.0
		 */
		$all_modules = apply_filters( 'merchant_analytics_all_modules_data_campaigns_table', merchant_get_modules_data() );

		if ( empty( $all_modules ) ) {
			return $campaigns_data;
		}

		foreach ( $all_modules as $module_id => $module ) {
			if ( Merchant_Modules::is_module_active( $module_id ) ) {
				$data      = array();
				$campaigns = $module['campaigns'] ?? array();
				$this->data_provider->set_start_date( $second_period['start'] );
				$this->data_provider->set_end_date( $second_period['end'] );
				if ( ! empty( $module['campaigns'] ) ) {
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
							'impression'     => $impressions === 0 ? '-' : $impressions,
							'clicks'         => $this->data_provider->get_campaign_clicks( $campaign_id, $module_id ),
							'revenue'        => wc_price( $revenue ),
							'revenue_number' => $revenue,
							'ctr'            => $this->get_campaign_ctr_change( $campaign_id, $module_id, $first_period, $second_period ),
							'orders'         => $this->data_provider->get_campaign_orders_count( $campaign_id, $module_id ),
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
						'impression'     => $impressions === 0 ? '-' : $impressions,
						'clicks'         => $this->data_provider->get_module_clicks( $module_id ),
						'revenue'        => wc_price( $revenue ),
						'revenue_number' => $revenue,
						'ctr'            => $this->get_module_ctr_change( $module_id, $first_period, $second_period ),
						'orders'         => $this->data_provider->get_module_orders_count( $module_id ),
						'url'            => $module_url,
					);
				}

				// Prepare the campaigns data
				$campaigns_data[] = array(
					'module_id'   => $module_id,
					'module_name' => esc_html( $module['name'] ?? '' ),
					'campaigns'   => $data,
				);
			}
		}

		return $campaigns_data;
	}

	/**
	 * Get the CTR change for the given campaign and date ranges.
	 *
	 * @param int   $campaign_id   The campaign ID.
	 * @param int   $module_id     The module ID.
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_campaign_ctr_change( $campaign_id, $module_id, $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$ctr_first_period = (int) $this->data_provider->get_campaign_ctr_percentage( $campaign_id, $module_id );

		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$ctr_second_period = (int) $this->data_provider->get_campaign_ctr_percentage( $campaign_id, $module_id );

		$ctr_difference = $ctr_second_period - $ctr_first_period;

		$change = $this->calculate_percentage_difference( $ctr_second_period, $ctr_first_period );

		return array(
			'change'            => $change,
			'ctr_difference'    => $ctr_difference,
			'ctr_first_period'  => $ctr_first_period,
			'ctr_second_period' => $ctr_second_period,
		);
	}

	/**
	 * Get the CTR change for the given module and date ranges.
	 *
	 * @param int   $module_id     The module ID.
	 * @param array $first_period  The first date range.
	 * @param array $second_period The second date range.
	 *
	 * @return array
	 */
	public function get_module_ctr_change( $module_id, $first_period, $second_period ) {
		$this->data_provider->set_start_date( $first_period['start'] );
		$this->data_provider->set_end_date( $first_period['end'] );

		$ctr_first_period = (int) $this->data_provider->get_module_ctr_percentage( $module_id );

		$this->data_provider->set_start_date( $second_period['start'] );
		$this->data_provider->set_end_date( $second_period['end'] );

		$ctr_second_period = (int) $this->data_provider->get_module_ctr_percentage( $module_id );

		$ctr_difference = $ctr_second_period - $ctr_first_period;

		$change = $this->calculate_percentage_difference( $ctr_second_period, $ctr_first_period );

		return array(
			'change'            => $change,
			'ctr_difference'    => $ctr_difference,
			'ctr_first_period'  => $ctr_first_period,
			'ctr_second_period' => $ctr_second_period,
		);
	}

	/**
	 * Sort orders by timestamp in ascending order.
	 *
	 * @param array $orders The orders to sort.
	 *
	 * @return array
	 */
	private function sort_orders_by_timestamp( $orders ) {
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
	private function sort_impressions_by_timestamp( $impressions ) {
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
	private function determine_grouping_interval( $start_date, $end_date ) {
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
	private function calculate_days_between_dates( $start_date, $end_date ) {
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
	private function group_data_by_interval( $orders, $interval, $start_date, $end_date, $metric = 'revenue' ) {
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
	private function group_impressions_by_interval( $impressions, $interval, $start_date, $end_date ) {
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
	private function calculate_interval_end( $current_group_start, $interval ) {
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
	private function get_orders_in_interval( $orders, $interval_start, $interval_end ) {
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
	private function get_impressions_in_interval( $impressions, $interval_start, $interval_end ) {
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
	private function calculate_percentage_difference( $current_value, $previous_value ) {
		if ( $previous_value !== null ) {
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
	private function format_x_axis_label( $current_group_start, $interval_end, $interval ) {
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