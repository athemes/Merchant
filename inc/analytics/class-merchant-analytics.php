<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Merchant_Analytics
 *
 * This class is responsible for logging analytics events.
 */
class Merchant_Analytics {

	/**
	 * @var Merchant_Analytics_DB_ORM
	 */
	private $database;

	/**
	 * @var mixed
	 */
	private $user_id = null;

	public function __construct() {
		$this->database = new Merchant_Analytics_DB_ORM();
	}

	/**
	 * Set the user ID.
	 *
	 * @param $user_id
	 */
	public function set_user_id( $user_id ) {
		$this->user_id = $user_id;
	}

	/**
	 * Log an event.
	 *
	 * @param $args array The arguments for the event to be logged.
	 *
	 * @return false|int The ID of the inserted row on success, false on failure.
	 */
	public function log_event( $args ) {
		$defaults = array(
			'source_product_id' => 0,
			'event_type'        => '',
			'customer_id'       => $this->user_id,
			'related_event_id'  => '',
			'module_id'         => '',
			'campaign_id'       => 0,
			'campaign_cost'     => 0,
			'order_id'          => 0,
			'order_subtotal'    => 0,
			'meta_data'         => '',
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Filter the arguments for the event to be logged.
		 *
		 * @param array $args The arguments for the event to be logged.
		 *
		 * @since 2.0
		 */
		$args = apply_filters(
			'merchant_analytics_log_event_args',
			$args
		);

		return $this->database->create( $args );
	}

	public function get_product_module_last_impression( $module_id, $product_id ) {
		$now       = current_time( 'mysql' );
		$last_hour = gmdate( 'Y-m-d H:i:s', strtotime( '-1 hour', strtotime( $now ) ) );

		$result = $this->database
			->where_between_dates( $last_hour, $now )
			->where( array(
				'event_type'        => 'impression',
				'source_product_id' => $product_id,
				'customer_id'       => $this->user_id,
				'module_id'         => $module_id,
			) )
			->first();

		$this->database->reset_query();

		return $result;
	}

	/**
	 * Get event data
	 *
	 * @param $event_id int The event ID.
	 *
	 * @return ARRAY|NULL  The event data.
	 */
	public function get_event( $event_id ) {
		if ( ! $this->is_db_table_exists() ) {
			return null;
		}
		$result = $this->database->where( 'id = %d', $event_id )->first();
		$this->database->reset_query();

		return $result;
	}

	/**
	 * Get events by attributes.
	 *
	 * @param $attributes array The attributes to search for.
	 *
	 * @return array|null The events found.
	 */
	public function get_event_by( $attributes ) {
		if ( ! $this->is_db_table_exists() ) {
			return null;
		}
		$result = $this->database->where( $attributes )->first();
		$this->database->reset_query();

		return $result;
	}

	/**
	 * Check if an event exists.
	 *
	 * @param $event_id int The event ID.
	 *
	 * @return bool True if the event exists, false otherwise.
	 */
	public function event_exists( $event_id ) {
		return ! empty( $this->database->where( 'id = %d', $event_id )->first() );
	}

	/**
	 * Update an event.
	 *
	 * @param $event_id int The event ID.
	 * @param $data     array The data to update.
	 *
	 * @return false|int The ID of the updated row on success, false on failure.
	 */
	public function update_event( $event_id, $data ) {
		return $this->database->update( $event_id, $data );
	}

	/**
	 * Delete an event.
	 *
	 * @param $event_id int The event ID.
	 *
	 * @return bool True if the event was deleted, false otherwise.
	 */
	public function delete_event( $event_id ) {
		return $this->database->delete( $event_id );
	}
}

