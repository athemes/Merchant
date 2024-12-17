<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Merchant_Analytics_DB_ORM {
	private $table_name;
	private $wpdb;
	private $fillable
		= array(
			'source_product_id',
			'event_type',
			'customer_id',
			'related_event_id',
			'module_id',
			'campaign_id',
			'campaign_cost',
			'order_id',
			'order_subtotal',
			'meta_data',
		);

	// Query building properties
	private $query_where = '';
	private $query_params = array();
	private $query_results = null;

	public function __construct() {
		global $wpdb;
		$this->wpdb       = $wpdb;
		$this->table_name = $this->wpdb->prefix . 'merchant_modules_analytics';
	}

	/**
	 * Reset query builder state
	 *
	 * @return $this
	 */
	private function reset_query() {
		$this->query_where   = '';
		$this->query_params  = array();
		$this->query_results = null;

		return $this;
	}

	/**
	 * Creates a new record in the analytics table.
	 *
	 * @param array $attributes An associative array of attributes for the new record.
	 *
	 * @return int|false The ID of the inserted row on success, false on failure.
	 */
	public function create( array $attributes ) {
		$data = array();
		foreach ( $this->fillable as $field ) {
			if ( isset( $attributes[ $field ] ) ) {
				$data[ $field ] = $attributes[ $field ];
			}
		}
		$format = $this->get_data_formats( $data );

		$result = $this->wpdb->insert( $this->table_name, $data, $format );

		if ( $result === false ) {
			return false;
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Finds a record by its primary key (id).
	 *
	 * @param int $id The ID of the record to find.
	 *
	 * @return object|null The record object if found, null otherwise.
	 */
	public function find( $id ) {
		return $this->reset_query()
		            ->where( 'id = %d', $id )
		            ->first();
	}

	/**
	 * Retrieves all records from the analytics table.
	 *
	 * @return array|null An array of objects representing the matching records, or null if there are no results or an error occurs.
	 */
	public function get_all() {
		return $this->reset_query()
		            ->get();
	}

	/**
	 * Add a where clause with multiple conditions
	 *
	 * Supports multiple ways of adding conditions:
	 * 1. Multiple calls to where()
	 * 2. Array of conditions
	 * 3. Nested conditions
	 *
	 * @param array|string $conditions Conditions to filter by
	 * @param mixed        $value      Value for simple key-value condition
	 *
	 * @return $this
	 */
	public function where( $conditions, $value = null ) {
		// If query results already set, return existing results
		if ( $this->query_results !== null ) {
			return $this;
		}

		// Simple key-value condition
		if ( is_string( $conditions ) && $value !== null ) {
			$this->query_where .= empty( $this->query_where )
				? ' WHERE ' . $conditions
				: ' AND ' . $conditions;

			$this->query_params[] = $value;
			return $this;
		}

		// Array of conditions
		if ( is_array( $conditions ) ) {
			$and_conditions = array();
			foreach ( $conditions as $key => $val ) {
				// Support for more complex conditions
				if ( is_array( $val ) ) {
					// Handle array-based conditions like ['column', 'operator', 'value']
					if ( count( $val ) === 3 ) {
						list( $column, $operator, $comparison ) = $val;
						$and_conditions[] = "$column $operator %s";
						$this->query_params[] = $comparison;
					}
					// Handle IN and NOT IN conditions
					elseif ( isset( $val['in'] ) ) {
						$in_values = $val['in'];
						$placeholders = implode( ',', array_fill( 0, count( $in_values ), '%s' ) );
						$and_conditions[] = "$key IN ($placeholders)";
						$this->query_params = array_merge( $this->query_params, $in_values );
					}
					elseif ( isset( $val['not_in'] ) ) {
						$not_in_values = $val['not_in'];
						$placeholders = implode( ',', array_fill( 0, count( $not_in_values ), '%s' ) );
						$and_conditions[] = "$key NOT IN ($placeholders)";
						$this->query_params = array_merge( $this->query_params, $not_in_values );
					}
				} else {
					// Standard key-value condition
					$and_conditions[] = "$key = %s";
					$this->query_params[] = $val;
				}
			}

			$conditions_sql = implode( " AND ", $and_conditions );
			$this->query_where .= empty( $this->query_where )
				? ' WHERE ' . $conditions_sql
				: ' AND ' . $conditions_sql;
		}

		return $this;
	}

	/**
	 * Add OR conditions to the query
	 *
	 * @param array $conditions Conditions to filter by
	 *
	 * @return $this
	 */
	public function or_where( $conditions ) {
		// If query results already set, return existing results
		if ( $this->query_results !== null ) {
			return $this;
		}

		$or_conditions = array();
		foreach ( $conditions as $key => $val ) {
			$or_conditions[] = "$key = %s";
			$this->query_params[] = $val;
		}

		$conditions_sql = implode( " OR ", $or_conditions );
		$this->query_where .= empty( $this->query_where )
			? ' WHERE ' . $conditions_sql
			: ' OR ' . $conditions_sql;

		return $this;
	}

	/**
	 * Add date range filter for chaining
	 *
	 * @param string $start_date The start date for the range (YYYY-MM-DD HH:MM:SS).
	 * @param string $end_date   The end date for the range (YYYY-MM-DD HH:MM:SS).
	 *
	 * @return $this
	 */
	public function where_between_dates( $start_date, $end_date ) {
		// If query results already set, return existing results
		if ( $this->query_results !== null ) {
			return $this;
		}

		$this->query_where .= empty( $this->query_where )
			? ' WHERE timestamp BETWEEN %s AND %s'
			: ' AND timestamp BETWEEN %s AND %s';

		$this->query_params[] = $start_date;
		$this->query_params[] = $end_date;

		return $this;
	}

	/**
	 * Execute the query and get results
	 *
	 * @return array|null An array of objects representing the matching records, or null if there are no results or an error occurs.
	 */
	public function get() {
		// If results already cached, return them
		if ( $this->query_results !== null ) {
			return $this->query_results;
		}

		// Construct full SQL query
		$sql = "SELECT * FROM {$this->table_name}" . $this->query_where;

		// Prepare and execute query
		if ( ! empty( $this->query_params ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$sql = $this->wpdb->prepare( $sql, $this->query_params );
		}

		// Execute query and cache results
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$this->query_results = $this->wpdb->get_results( $sql );

		// Reset query builder for next use
		$this->reset_query();

		return $this->query_results;
	}

	/**
	 * Get the first result from the query
	 *
	 * @return object|null The first record object if found, null otherwise.
	 */
	public function first() {
		$results = $this->get();

		return ! empty( $results ) ? $results[0] : null;
	}

	/**
	 * Updates a record in the analytics table.
	 *
	 * @param int   $id         The ID of the record to update.
	 * @param array $attributes An associative array of attributes to update. Keys should match column names.
	 *
	 * @return int|false The number of rows affected, or false on error.
	 */
	public function update( $id, array $attributes ) {
		$data = array();
		foreach ( $this->fillable as $field ) {
			if ( isset( $attributes[ $field ] ) ) {
				$data[ $field ] = $attributes[ $field ];
			}
		}
		if ( empty( $data ) ) {
			return 0;
		}

		return $this->wpdb->update( $this->table_name, $data, array( 'id' => $id ), $this->get_data_formats( $data ) );
	}

	/**
	 * Deletes a record from the analytics table by its ID.
	 *
	 * @param int $id The ID of the record to delete.
	 *
	 * @return int|false The number of rows affected, or false on error.
	 */
	public function delete( $id ) {
		return $this->wpdb->delete( $this->table_name, array( 'id' => $id ), array( '%d' ) );
	}

	/**
	 * Gets the data formats for the wpdb update/insert function based on data
	 *
	 * @param array $data the data array to check
	 *
	 * @return array the formats array
	 */
	private function get_data_formats( $data ) {
		$format = array();
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, array( 'source_product_id', 'customer_id', 'related_event_id', 'module_id', 'campaign_id', 'order_id' ), true ) ) { //Strict comparison
				$format[] = '%d';
			} elseif ( $key === 'campaign_cost' || $key === 'order_subtotal' ) { //Strict comparison
				$format[] = '%f';
			} else {
				$format[] = '%s';
			}
		}

		return $format;
	}
}

