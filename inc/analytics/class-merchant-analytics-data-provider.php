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
class Merchant_Analytics_Data_Provider {

	/**
	 * @var string The start date in 'Y-m-d H:i:s' format.
	 */
	private $start_date;

	/**
	 * @var string The end date in 'Y-m-d H:i:s' format.
	 */
	private $end_date;

	/**
	 * @var Merchant_Analytics_DB_ORM The analytics Database ORM instance.
	 */
	private $analytics;

	public function __construct( $analytics = null ) {
		if ( ! $analytics ) {
			$this->analytics = new Merchant_Analytics_DB_ORM();
		} else {
			$this->analytics = $analytics;
		}
	}

	/**
	 * Set the start date for filtering data.
	 *
	 * @param string $start_date The start date in 'Y-m-d H:i:s' format.
	 */
	public function set_start_date( $start_date ) {
		$this->start_date = $this->validate_date( $start_date );
	}

	/**
	 * Set the end date for filtering data.
	 *
	 * @param string $end_date The end date in 'Y-m-d H:i:s' format.
	 */
	public function set_end_date( $end_date ) {
		$this->end_date = $this->validate_date( $end_date );
	}

	/**
	 * Validate the date format.
	 *
	 * @param string $date The date string to validate.
	 *
	 * @return string Validated date string.
	 * @throws InvalidArgumentException If the date format is invalid.
	 */
	private function validate_date( $date ) {
		$d = DateTime::createFromFormat( 'Y-m-d H:i:s', $date );
		if ( $d && $d->format( 'Y-m-d H:i:s' ) === $date ) {
			return $date;
		}
		throw new InvalidArgumentException( 'Invalid date format. Expected Y-m-d H:i:s given ' . esc_html( $date ) );
	}

	/**
	 * Get the start date. If not set, initialize with the default value.
	 *
	 * @return string The start date in 'Y-m-d H:i:s' format.
	 */
	public function get_start_date() {
		if ( ! $this->start_date ) {
			// Default to the beginning of today in GMT
			$this->start_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-30 days' ) );
		}

		return $this->start_date;
	}

	/**
	 * Get the end date. If not set, initialize with the default value.
	 *
	 * @return string The end date in 'Y-m-d H:i:s' format.
	 */
	public function get_end_date() {
		if ( ! $this->end_date ) {
			// Default to the current time in GMT
			$this->end_date = gmdate( 'Y-m-d H:i:s', time() );
		}

		return $this->end_date;
	}

	/**
	 * Get the total revenue.
	 *
	 * @return float The total revenue.
	 */
	public function get_revenue() {
		$orders_data = $this->get_dated_orders( - 1 );
		if ( ! empty( $orders_data ) ) {
			return array_sum( array_column( $orders_data, 'order_subtotal' ) );
		}

		return 0;
	}

	/**
	 * Get the dated revenue.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return array The dated revenue.
	 */
	public function get_dated_orders( $limit = 10000 ) {
		$orders = $this->analytics
			->select( array( 'timestamp, order_subtotal', 'order_id' ) )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->limit( $limit )
			->order_by( 'timestamp', 'ASC' )
			->get();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $orders ) ) {
			// Use array_column to get the order_ids
			$orderIds         = array_column( $orders, 'order_id' );
			$unique_order_ids = array_unique( $orderIds );
			$unique_orders    = array();
			foreach ( $orders as $order ) {
				if ( in_array( $order['order_id'], $unique_order_ids, true ) ) {
					$unique_orders[] = $order;
					// Remove the order_id from the uniqueOrderIds array to prevent duplicates
					$key = array_search( $order['order_id'], $unique_order_ids, true );
					unset( $unique_order_ids[ $key ] );
				}
			}

			return $unique_orders;
		}

		return array();
	}

	/**
	 * Get the dated revenue.
	 *
	 * @param $limit int The limit of the query.
	 *
	 * @return array|null
	 */
	public function get_dated_impressions( $limit = 10000 ) {
		$impressions = $this->analytics
			->select( array( 'timestamp', 'count(id) as impressions_count' ) )
			->where( 'event_type = %s', 'impression' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->group_by( 'DATE(timestamp)' )
			->order_by( 'timestamp', 'ASC' )
			->limit( $limit )
			->get();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		return $impressions;
	}

	/**
	 * Get the average order value.
	 *
	 * @return float The average order value.
	 */
	public function get_average_order_value() {
		$orders_data = $this->get_dated_orders( - 1 );
		if ( ! empty( $orders_data ) ) {
			$order_subtotals = array_column( $orders_data, 'order_subtotal' );
			$orders_count    = count( $order_subtotals );
			if ( $orders_count > 0 ) {
				return array_sum( $order_subtotals ) / $orders_count;
			}
		}

		return 0;
	}

	/**
	 * Get the total number of orders.
	 *
	 * @return int The total number of orders.
	 */
	public function get_orders_count() {
		$orders_data = $this->get_dated_orders( - 1 );
		if ( ! empty( $orders_data ) ) {
			return count( $orders_data );
		}

		return 0;
	}

	/**
	 * Get the total number of impressions.
	 *
	 * @return int The total number of impressions.
	 */
	public function get_total_impressions() {
		$total_impressions = $this->analytics
			->where( 'event_type = %s', 'impression' )
			->count( 'id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $total_impressions ) ) {
			return $total_impressions['count_id'];
		}

		return 0;
	}

	/**
	 * Get the conversion rate percentage.
	 *
	 * @return float The conversion rate percentage.
	 */
	public function get_conversion_rate_percentage() {
		$orders_count      = $this->get_orders_count();
		$total_impressions = $this->get_total_impressions();
		if ( $total_impressions > 0 && $orders_count > 0 ) {
			return ( $orders_count / $total_impressions ) * 100;
		}

		return 0;
	}

	/**
	 * Get total campaign impressions.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return int The total campaign impressions or 0 if not found.
	 */
	public function get_campaign_impressions( $campaign_id, $module_id ) {
		$campaign_impressions = $this->analytics
			->where( 'event_type = %s', 'impression' )
			->where( 'campaign_id = %d', $campaign_id )
			->where( 'module_id = %d', $module_id )
			->count( 'id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $campaign_impressions ) ) {
			return $campaign_impressions['count_id'];
		}

		return 0;
	}

	/**
	 * Get total campaign clicks.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return int The total campaign clicks or 0 if not found.
	 */
	public function get_campaign_clicks( $campaign_id, $module_id ) {
		$campaign_clicks = $this->analytics
			->where( 'event_type = %s', 'add_to_cart' )
			->where( 'campaign_id = %d', $campaign_id )
			->where( 'module_id = %d', $module_id )
			->count( 'id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $campaign_clicks ) ) {
			return $campaign_clicks['count_id'];
		}

		return 0;
	}

	/**
	 * Get total campaign orders count.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return int The total campaign orders count or 0 if not found.
	 */
	public function get_campaign_orders_count( $campaign_id, $module_id ) {
		$campaign_orders = $this->analytics
			->distinct( 'order_id' )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id = %d', $campaign_id )
			->where( 'module_id = %d', $module_id )
			->count( 'order_id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $campaign_orders ) ) {
			return $campaign_orders['count_order_id'];
		}

		return 0;
	}

	/**
	 * Get campaign revenue.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return float The campaign revenue or 0 if not found.
	 */
	public function get_campaign_revenue( $campaign_id, $module_id ) {
		$campaign_revenue = $this->analytics
			->distinct( 'order_id' )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id = %d', $campaign_id )
			->where( 'module_id = %d', $module_id )
			->sum( 'order_subtotal' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $campaign_revenue ) ) {
			return $campaign_revenue['sum_order_subtotal'];
		}

		return 0;
	}

	/**
	 * Get campaign average order value.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return float The campaign average order value or 0 if not found.
	 */
	public function get_campaign_average_order_value( $campaign_id, $module_id ) {
		$campaign_average_order_value = $this->analytics
			->distinct( 'order_id' )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id = %d', $campaign_id )
			->where( 'module_id = %d', $module_id )
			->avg( 'order_subtotal' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $campaign_average_order_value ) ) {
			return $campaign_average_order_value['avg_order_subtotal'];
		}

		return 0;
	}

	/**
	 * Get campaign conversion rate percentage.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return float The campaign conversion rate percentage or 0 if not found.
	 */
	public function get_campaign_conversion_rate_percentage( $campaign_id, $module_id ) {
		$campaign_orders_count = $this->get_campaign_orders_count( $campaign_id, $module_id );
		$campaign_impressions  = $this->get_campaign_impressions( $campaign_id, $module_id );
		if ( $campaign_impressions > 0 && $campaign_orders_count > 0 ) {
			return ( $campaign_orders_count / $campaign_impressions ) * 100;
		}

		return 0;
	}

	/**
	 * Get the top performing campaigns.
	 *
	 * @return array The top performing campaigns.
	 */
	public function get_top_performing_campaigns() {
		$top_performing_campaigns = $this->analytics
			->select( 'module_id, campaign_id, count(order_id) as orders_count' )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id != %s', '0' )
			->group_by( 'campaign_id' )
			->order_by( 'orders_count', 'DESC' )
			->limit( 10 )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->get();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		return $top_performing_campaigns;
	}

	/**
	 * Get module impressions count.
	 *
	 * @param $module_id string The module ID.
	 *
	 * @return int The module impressions count.
	 */
	public function get_module_impressions( $module_id ) {
		$module_impressions = $this->analytics
			->where( 'event_type = %s', 'impression' )
			->where( 'module_id = %d', $module_id )
			->count( 'id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $module_impressions ) ) {
			return $module_impressions['count_id'];
		}

		return 0;
	}

	/**
	 * Get module clicks count.
	 *
	 * @param $module_id string The module ID.
	 *
	 * @return int The module clicks count.
	 */
	public function get_module_clicks( $module_id ) {
		$module_clicks = $this->analytics
			->where( 'event_type = %s', 'add_to_cart' )
			->where( 'module_id = %d', $module_id )
			->count( 'id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $module_clicks ) ) {
			return $module_clicks['count_id'];
		}

		return 0;
	}

	/**
	 * Get module orders count.
	 *
	 * @param $module_id string The module ID.
	 *
	 * @return int The module orders count.
	 */
	public function get_module_orders_count( $module_id ) {
		$module_orders = $this->analytics
			->distinct( 'order_id' )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'module_id = %d', $module_id )
			->count( 'order_id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $module_orders ) ) {
			return $module_orders['count_order_id'];
		}

		return 0;
	}

	/**
	 * Get module revenue.
	 *
	 * @param $module_id string The module ID.
	 *
	 * @return float The module revenue.
	 */
	public function get_module_revenue( $module_id ) {
		$module_revenue = $this->analytics
			->distinct( 'order_id' )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'module_id = %d', $module_id )
			->sum( 'order_subtotal' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $module_revenue ) ) {
			return $module_revenue['sum_order_subtotal'];
		}

		return 0;
	}

	/**
	 * Get module average order value.
	 *
	 * @param $module_id string The module ID.
	 *
	 * @return float The module average order value.
	 */
	public function get_module_average_order_value( $module_id ) {
		$module_average_order_value = $this->analytics
			->distinct( 'order_id' )
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'module_id = %d', $module_id )
			->avg( 'order_subtotal' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $module_average_order_value ) ) {
			return $module_average_order_value['avg_order_subtotal'];
		}

		return 0;
	}

	/**
	 * Get module conversion rate percentage.
	 *
	 * @param $module_id string The module ID.
	 *
	 * @return float The module conversion rate percentage.
	 */
	public function get_module_conversion_rate_percentage( $module_id ) {
		$module_orders_count = $this->get_module_orders_count( $module_id );
		$module_impressions  = $this->get_module_impressions( $module_id );
		if ( $module_impressions > 0 && $module_orders_count > 0 ) {
			return ( $module_orders_count / $module_impressions ) * 100;
		}

		return 0;
	}
}