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
	 * Get revenue data for the given date range.
	 *
	 * @param string $start_date Start date in 'Y-m-d H:i:s' format.
	 * @param string $end_date   End date in 'Y-m-d H:i:s' format.
	 *
	 * @return array
	 */
	public function get_revenue( $start_date, $end_date ) {
		$this->data_provider->set_start_date( $start_date );
		$this->data_provider->set_end_date( $end_date );

		/**
		 * Filter the maximum number of orders to retrieve for revenue chart.
		 *
		 * @param int $limit The maximum number of orders to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit = apply_filters( 'merchant_analytics_max_orders_limit_revenue', 1000000 );

		$orders = $this->data_provider->get_dated_orders( $limit );

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
	public function get_average_order_value( $start_date, $end_date ) {
		$this->data_provider->set_start_date( $start_date );
		$this->data_provider->set_end_date( $end_date );

		/**
		 * Filter the maximum number of orders to retrieve for AOV chart.
		 *
		 * @param int $limit The maximum number of orders to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit = apply_filters( 'merchant_analytics_max_orders_limit_aov', 1000000 );

		$orders = $this->data_provider->get_dated_orders( $limit );

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
	public function get_impressions( $start_date, $end_date ) {
		$this->data_provider->set_start_date( $start_date );
		$this->data_provider->set_end_date( $end_date );

		/**
		 * Filter the maximum number of impressions to retrieve for the impressions chart.
		 *
		 * @param int $limit The maximum number of impressions to retrieve.
		 *
		 * @since 2.0.0
		 */
		$limit = apply_filters( 'merchant_analytics_max_impressions_limit', 1000000 );

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
			$total_revenue = array_sum( array_column( $current_group, 'order_subtotal' ) );
			$orders_count  = count( $current_group );

			// Calculate the desired metric
			if ( $metric === 'aov' ) {
				$value = $orders_count > 0 ? $total_revenue / $orders_count : 0; // AOV
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