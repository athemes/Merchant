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
			'order_total',
			'meta_data',
			'meta_data_2',
		);

	// Query building properties
	private $query_where = '';
	private $query_params = array();
	private $query_results = null;
	private $query_executed = false;
	private $select = '*';
	private $aggregates = array();
	private $order_by = '';
	private $sql_statement = '';
	private $limit = '';

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
	public function reset_query() {
		$this->query_where    = '';
		$this->sql_statement  = '';
		$this->query_params   = array();
		$this->query_results  = null;
		$this->query_executed = false;
		$this->select         = '*';
		$this->aggregates     = array();
		$this->order_by       = '';
		$this->limit          = '';

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
	 * Get the SQL statement for the last query
	 *
	 * @return string The SQL statement
	 */
	public function get_sql_statement() {
		return $this->sql_statement;
	}

	/**
	 * Finds a record by its primary key (id).
	 *
	 * @param int $id The ID of the record to find.
	 *
	 * @return array|null The record object if found, null otherwise.
	 */
	public function find( $id, $first_or_last = 'first' ) {
		if ( $first_or_last === 'last' ) {
			$results = $this->reset_query()
			                ->where( 'id = %d', $id )
			                ->last();
		} else {
			$results = $this->reset_query()
			                ->where( 'id = %d', $id )
			                ->first();
		}

		return $results;
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
	 * Sets columns to select.
	 *
	 * @param string|array $columns Columns to select.
	 *
	 * @return $this
	 */
	public function select( $columns = '*' ) {
		$this->select = is_array( $columns ) ? implode( ', ', $columns ) : $columns;

		return $this;
	}

	/**
	 * Add SUM to query.
	 *
	 * @param string $column Column to sum.
	 * @param string $alias  Optional alias for the sum.
	 *
	 * @return $this
	 */
	public function sum( $column, $alias = '' ) {
		$this->aggregates[] = array(
			'function' => 'SUM',
			'column'   => $column,
			'alias'    => $alias ? $alias : "sum_{$column}",
		);

		return $this;
	}


	/**
	 * Add COUNT to query.
	 *
	 * @param string  $column   Column to count.
	 * @param boolean $distinct Count distinct values.
	 * @param string  $alias    Optional alias.
	 *
	 * @return $this
	 */
	public function count( $column = '*', $distinct = false, $alias = '' ) {
		$this->aggregates[] = array(
			'function' => 'COUNT',
			'column'   => $distinct ? "DISTINCT {$column}" : $column,
			'alias'    => $alias ? $alias : "count_{$column}",
		);

		return $this;
	}

	/**
	 * Prepares DISTINCT query.
	 *
	 * @param string $column Column to get distinct values.
	 *
	 * @return $this
	 */
	public function distinct( $column ) {
		$this->select = "DISTINCT {$column}";

		return $this;
	}

	/**
	 * Prepares MAX query.
	 *
	 * @param string $column Column to get maximum value.
	 *
	 * @return $this
	 */
	public function max( $column, $alias = '' ) {
		$this->aggregates[] = array(
			'function' => 'MAX',
			'column'   => $column,
			'alias'    => $alias ? $alias : "max_{$column}",
		);

		return $this;
	}

	/**
	 * Prepares MIN query.
	 *
	 * @param string $column Column to get minimum value.
	 *
	 * @return $this
	 */
	public function min( $column, $alias = '' ) {
		$this->aggregates[] = array(
			'function' => 'MIN',
			'column'   => $column,
			'alias'    => $alias ? $alias : "min_{$column}",
		);

		return $this;
	}

	/**
	 * Add AVG to query.
	 *
	 * @param string $column Column to average.
	 * @param string $alias  Optional alias.
	 *
	 * @return $this
	 */
	public function avg( $column, $alias = '' ) {
		$this->aggregates[] = array(
			'function' => 'AVG',
			'column'   => $column,
			'alias'    => $alias ? $alias : "avg_{$column}",
		);

		return $this;
	}

	/**
	 * Add a group by clause to the query
	 *
	 * @param $column string The column to group by
	 *
	 * @return $this
	 */
	public function group_by( $column ) {
		$this->query_where .= " GROUP BY " . esc_sql( $column );

		return $this;
	}

	/**
	 * Add where not in clause to the query.
	 *
	 * @param string $column The column to check.
	 * @param array  $values Array of values to exclude.
	 *
	 * @return $this
	 */
	public function where_not_in( string $column, array $values ) {
		if ( empty( $values ) ) {
			return $this;
		}

		$column             = esc_sql( $column );
		$placeholders       = array_fill( 0, count( $values ), '%s' );
		$placeholder_string = implode( ',', $placeholders );

		if ( empty( $this->query_where ) ) {
			$this->query_where = ' WHERE ' . $column . ' NOT IN (' . $placeholder_string . ')';
		} else {
			$this->query_where .= ' AND ' . $column . ' NOT IN (' . $placeholder_string . ')';
		}

		$this->query_params = array_merge( $this->query_params, $values );

		return $this;
	}

	/**
	 * Add ORDER BY clause to query.
	 *
	 * @param string $column    Column name.
	 * @param string $direction Sort direction (ASC or DESC).
	 *
	 * @return $this
	 */
	public function order_by( $column, $direction = 'ASC' ) {
		$column    = esc_sql( $column );
		$direction = in_array( strtoupper( $direction ), array( 'ASC', 'DESC' ), true ) ? strtoupper( $direction ) : 'ASC';

		$this->order_by = " ORDER BY {$column} {$direction}";

		return $this;
	}

	/**
	 * Add LIMIT clause to query.
	 *
	 * @param int $limit  Number of records to return.
	 * @param int $offset Offset to start from.
	 *
	 * @return $this
	 */
	public function limit( $limit, $offset = 0 ) {
		if ( $limit >= 0 ) {
			$this->limit = " LIMIT {$limit} OFFSET {$offset}";
		}

		return $this;
	}

	/**
	 * Execute the query and get results
	 *
	 * @return array|null An array of objects representing the matching records, or null if there are no results or an error occurs.
	 */
	public function get() {
		if ( $this->query_executed && $this->query_results !== null ) {
			return $this->query_results;
		}

		$select = $this->select;

		if ( ! empty( $this->aggregates ) ) {
			$aggregate_parts = array();

			foreach ( $this->aggregates as $agg ) {
				$aggregate_parts[] = sprintf(
					'%s(%s) as %s',
					esc_sql( $agg['function'] ),
					esc_sql( $agg['column'] ),
					esc_sql( $agg['alias'] )
				);
			}

			$select = implode( ', ', $aggregate_parts );
		}

		$sql = "SELECT {$select} FROM {$this->table_name}" . $this->query_where . $this->order_by . $this->limit;

		if ( ! empty( $this->query_params ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$sql = $this->wpdb->prepare( $sql, $this->query_params );
		}

		$this->sql_statement = $sql;

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$this->query_results = $this->wpdb->get_results( $sql, ARRAY_A );
		$this->query_executed = true;

		return $this->query_results;
	}

	/**
	 * Get the first result from the query
	 *
	 * @return array|null The first record object if found, null otherwise.
	 */
	public function first() {
		$results = $this->get();

		return ! empty( $results ) ? $results[0] : null;
	}

	/**
	 * Get the last result from the query
	 *
	 * @return array|null The last record object if found, null otherwise.
	 */
	public function last() {
		$results = $this->get();

		return ! empty( $results ) ? $results[count($results) - 1] : null;
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
	 * Checks if the analytics table exists.
	 *
	 * @return bool True if the table exists, false otherwise.
	 */
	public function table_exists() {
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $this->wpdb->get_var( $this->wpdb->prepare( "SHOW TABLES LIKE %s", $this->table_name ) ) === $this->table_name;
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
			if ( in_array( $key, array( 'source_product_id', 'related_event_id', 'order_id' ), true ) ) { //Strict comparison
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

