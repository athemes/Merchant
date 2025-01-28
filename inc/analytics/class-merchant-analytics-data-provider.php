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
	 * @var string The start date in 'm/d/y H:i:s' format.
	 */
	private $start_date;

	/**
	 * @var string The end date in 'm/d/y H:i:s' format.
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
	 * @param string $start_date The start date in 'm/d/y H:i:s' format.
	 */
	public function set_start_date( $start_date ) {
		$this->start_date = $this->validate_date( $start_date );
	}

	/**
	 * Set the end date for filtering data.
	 *
	 * @param string $end_date The end date in 'm/d/y H:i:s' format.
	 */
	public function set_end_date( $end_date ) {
		$this->end_date = $this->validate_date( $end_date );
	}

	/**
	 * Get the start date. If not set, initialize with the default value.
	 *
	 * @return string The start date in 'm/d/y H:i:s' format.
	 */
	public function get_start_date() {
		if ( ! $this->start_date ) {
			// Default to the beginning of today in GMT
			$this->start_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-30 days' ) );
		}

		return $this->convert_date_format( $this->start_date );
	}

	/**
	 * Get the end date. If not set, initialize with the default value.
	 *
	 * @return string The end date in 'm/d/y H:i:s' format.
	 */
	public function get_end_date() {
		if ( ! $this->end_date ) {
			// Default to the current time in GMT
			$this->end_date = gmdate( 'Y-m-d H:i:s', time() );
		}

		return $this->convert_date_format( $this->end_date );
	}

	/**
	 * Get the total revenue.
	 *
	 * @return float The total revenue.
	 */
	public function get_revenue() {
		$orders_data = $this->get_dated_orders_with_revenue( - 1 );
		if ( ! empty( $orders_data ) ) {
			return array_sum( array_column( $orders_data, 'revenue' ) );
		}

		return 0;
	}

	/**
	 * Get orders records with in date range.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return array|null
	 */
	public function get_orders_in_period( $limit = 10000 ) {
		$db_orders_records = $this->analytics
			->select()
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->limit( $limit )
			->get();

		// Reset the query to avoid conflicts
		$this->analytics->reset_query();

		return $db_orders_records;
	}

	/**
	 * Get the dated revenue.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return array The dated revenue.
	 */
	public function get_dated_orders_with_revenue( $limit = 10000 ) {
		$db_orders_records = $this->get_orders_in_period( $limit );

		// Group campaigns by order_id and source_product_id
		$grouped_orders = array();
		foreach ( $db_orders_records as $order ) {
			$order_id      = $order['order_id'];
			$product_id    = $order['source_product_id'];
			$campaign_cost = (float) $order['campaign_cost'];

			// Initialize the order if it doesn't exist
			if ( ! isset( $grouped_orders[ $order_id ] ) ) {
				$grouped_orders[ $order_id ] = array(
					'order_id'       => $order_id,
					'order_subtotal' => (float) $order['order_subtotal'],
					'order_total'    => (float) $order['order_total'],
					'customer_id'    => $order['customer_id'],
					'timestamp'      => $order['timestamp'],
					'products'       => array(),
					'revenue'        => 0,
				);
			}

			// Update the product with the biggest campaign cost
			if (
				! isset( $grouped_orders[ $order_id ]['products'][ $product_id ] )
				|| $campaign_cost > $grouped_orders[ $order_id ]['products'][ $product_id ]
			) {
				$grouped_orders[ $order_id ]['products'][ $product_id ] = $campaign_cost;
			}
		}

		// Calculate revenue for each order
		foreach ( $grouped_orders as &$order ) {
			$order['revenue']  = array_sum( $order['products'] );
			$order['products'] = array_map( static function ( $product_id, $campaign_cost ) {
				return array(
					'product_id'    => $product_id,
					'campaign_cost' => $campaign_cost,
				);
			}, array_keys( $order['products'] ), $order['products'] );
		}
		unset( $order );

		// Return the grouped orders as an array
		return array_values( $grouped_orders );
	}

	/**
	 * Get the total number of reviews collected.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return int The total number of reviews collected.
	 */
	public function get_collected_reviews_count( $limit = 10000 ) {
		$result = $this->analytics
			->where( 'event_type = %s', 'submit_product_review' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->count( 'id' )
			->limit( $limit )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $result ) ) {
			return $result['count_id'];
		}

		return 0;
	}

	/**
	 * Get the total number of sent emails.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return int The total number of sent emails.
	 */
	public function get_sent_emails_count( $limit = 10000 ) {
		$result = $this->analytics
			->where( array(
				'event_type' => array(
					'in' => array(
						'send_review_request_email',
						'send_review_discount_code_email',
						'send_review_request_reminder_email',
						'send_review_discount_code_reminder_email',
					),
				),
			) )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->count( 'id' )
			->limit( $limit )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $result ) ) {
			return $result['count_id'];
		}

		return 0;
	}

	/**
	 * Get the total number of scheduled emails.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return int The total number of scheduled emails.
	 */
	public function get_scheduled_emails_count( $limit = 10000 ) {
		$result = $this->analytics
			->where( array(
				'event_type' => array(
					'in' => array(
						'schedule_review_request',
						'schedule_review_request_reminder',
						'schedule_discount_code_review_email',
					),
				),
			) )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->count( 'id' )
			->limit( $limit )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $result ) ) {
			return $result['count_id'];
		}

		return 0;
	}

	/**
	 * Get the total number of opened emails.
	 *
	 * @param int $limit The limit of the query.
	 *
	 * @return int The total number of opened emails.
	 */
	public function get_opened_emails_count( $limit = 10000 ) {
		$result = $this->analytics
			->where( 'event_type LIKE %s', 'email_open_%' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->count( 'id' )
			->limit( $limit )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $result ) ) {
			return $result['count_id'];
		}

		return 0;
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
		$orders_data = $this->get_dated_orders_with_revenue( - 1 );
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
		$orders_data = $this->get_dated_orders_with_revenue( - 1 );
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
			->where( 'campaign_id = %s', $campaign_id )
			->where( 'module_id = %s', $module_id )
			->count( 'id' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->first();

		$this->analytics->reset_query(); // Reset the query to avoid conflicts with other queries.

		if ( ! empty( $campaign_impressions ) ) {
			return (int) $campaign_impressions['count_id'];
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
			->where( 'campaign_id = %s', $campaign_id )
			->where( 'module_id = %s', $module_id )
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
			//->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id = %s', $campaign_id )
			->where( 'module_id = %s', $module_id )
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
		$revenue           = 0;
		$db_orders_records = $this->analytics
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id = %s', $campaign_id )
			->where( 'module_id = %s', $module_id )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->get();

		$this->analytics->reset_query();

		$grouped_orders = array();
		foreach ( $db_orders_records as $order ) {
			$order_id      = $order['order_id'];
			$product_id    = $order['source_product_id'];
			$campaign_cost = (float) $order['campaign_cost'];

			if ( ! isset( $grouped_orders[ $order_id ] ) ) {
				$grouped_orders[ $order_id ] = array( 'products' => array() );
			}

			if ( ! isset( $grouped_orders[ $order_id ]['products'][ $product_id ] ) || $campaign_cost > $grouped_orders[ $order_id ]['products'][ $product_id ] ) {
				$grouped_orders[ $order_id ]['products'][ $product_id ] = $campaign_cost;
			}
		}

		foreach ( $grouped_orders as $order ) {
			$revenue += array_sum( $order['products'] );
		}

		return $revenue;
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
			->where( 'campaign_id = %s', $campaign_id )
			->where( 'module_id = %s', $module_id )
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
	 * Get campaign CTR percentage.
	 *
	 * @param int $campaign_id The campaign ID.
	 * @param int $module_id   The module ID.
	 *
	 * @return float The campaign CTR percentage or 0 if not found.
	 */
	public function get_campaign_ctr_percentage( $campaign_id, $module_id ) {
		$campaign_orders_count = $this->get_campaign_clicks( $campaign_id, $module_id );
		$campaign_impressions  = $this->get_campaign_impressions( $campaign_id, $module_id );
		if ( $campaign_impressions > 0 && $campaign_orders_count > 0 ) {
			return ( $campaign_orders_count / $campaign_impressions ) * 100;
		}

		return 0;
	}

	/**
	 * Get module CTR percentage.
	 *
	 * @return float The module CTR percentage or 0 if not found.
	 */
	public function get_module_ctr_percentage( $module_id ) {
		$module_orders_count = $this->get_module_clicks( $module_id );
		$module_impressions  = $this->get_module_impressions( $module_id );
		if ( $module_impressions > 0 && $module_orders_count > 0 ) {
			return ( $module_orders_count / $module_impressions ) * 100;
		}

		return 0;
	}

	/**
	 * Get the top performing campaigns.
	 *
	 * @return array The top performing campaigns.
	 */
	public function get_top_performing_campaigns( $limit = 10 ) {
		$top_performing_campaigns = $this->analytics
			->select( array( '*', 'COUNT(id) as orders_count' ) )
			->where( 'event_type = %s', 'order' )
			->where( 'campaign_id != %s', '' )
			->where( 'campaign_id != %s', '0' )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->order_by( 'orders_count', 'DESC' )
			->group_by( 'campaign_id' )
			->limit( $limit )
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
			->where( 'module_id = %s', $module_id )
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
			->where( 'module_id = %s', $module_id )
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
			->where( 'module_id = %s', $module_id )
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
		$revenue           = 0;
		$db_orders_records = $this->analytics
			->where( 'order_id > %d', 0 )
			->where( 'event_type = %s', 'order' )
			->where( 'module_id = %s', $module_id )
			->where_between_dates( $this->get_start_date(), $this->get_end_date() )
			->get();

		$this->analytics->reset_query();

		$grouped_orders = array();
		foreach ( $db_orders_records as $order ) {
			$order_id      = $order['order_id'];
			$product_id    = $order['source_product_id'];
			$campaign_cost = (float) $order['campaign_cost'];

			if ( ! isset( $grouped_orders[ $order_id ] ) ) {
				$grouped_orders[ $order_id ] = array( 'products' => array() );
			}

			if ( ! isset( $grouped_orders[ $order_id ]['products'][ $product_id ] ) || $campaign_cost > $grouped_orders[ $order_id ]['products'][ $product_id ] ) {
				$grouped_orders[ $order_id ]['products'][ $product_id ] = $campaign_cost;
			}
		}

		foreach ( $grouped_orders as $order ) {
			$revenue += array_sum( $order['products'] );
		}

		return $revenue;
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
			->where( 'module_id = %s', $module_id )
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

	/**
	 * Validate the date format.
	 *
	 * @param string $date The date string to validate.
	 *
	 * @return string Validated date string.
	 * @throws InvalidArgumentException If the date format is invalid.
	 */
	private function validate_date( $date ) {
		$date .= ' 23:59:59'; // make the date compatible with the format without forcing the user to select the time.
		$d    = DateTime::createFromFormat( 'm/d/y H:i:s', $date );
		if ( $d && $d->format( 'm/d/y H:i:s' ) === $date ) {
			return $date;
		}
		throw new InvalidArgumentException( 'Invalid date format. Expected m/d/y H:i:s given ' . esc_html( $date ) );
	}

	/**
	 * Convert the date from m/d/y H:i:s format to 'Y-m-d H:i:s' to make it compatible with the database.
	 *
	 * @param string $date The date string to convert.
	 *
	 * @return string The date string in 'Y-m-d H:i:s' format.
	 */
	protected function convert_date_format( $date ) {
		$d = DateTime::createFromFormat( 'm/d/y H:i:s', $date );
		if ( $d ) {
			return $d->format( 'Y-m-d H:i:s' );
		}

		return '';
	}
}