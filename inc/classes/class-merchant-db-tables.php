<?php

/**
 * This file contains the methods that introduce the customized DB tables required to run merchant & merchant-pro functionalities.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_DB_Tables' ) ) {
	/**
	 * Class Merchant_DB_Tables
	 */
	class Merchant_DB_Tables {

		/**
		 * Initializes database by creating or updating the table structure.
		 */
		public static function init() {
			add_action( 'init', array( __CLASS__, 'maybe_create_tables' ) );
			add_action( 'upgrader_process_complete', array( __CLASS__, 'on_upgrade' ), 10, 2 );
			register_activation_hook( MERCHANT_FILE, array( __CLASS__, 'create_tables' ) );
		}

		/**
		 * Get the list of database tables to be created or updated.
		 *
		 * @return array
		 */
		private static function get_db_tables() {
			/**
			 * Filter the tables to be created in the database.
			 *
			 * @param array $tables The tables to be created in the database.
			 *
			 * @since 1.10.3
			 */
			return apply_filters(
				'merchant_db_tables',
				array(
					'sales_notifications_table'       => self::get_sales_notifications_table(),
					'sales_notifications_shown_table' => self::get_sales_notifications_shown_table(),
					'modules_analytics_table'         => self::get_modules_analytics_table(),
				)
			);
		}

		/**
		 * Get the modules analytics table definition.
		 * This table is used to store analytics data for merchant modules.
		 *
		 * @return array
		 */
		private static function get_modules_analytics_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . 'merchant_modules_analytics';
			$collate    = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

			return array(
				'name'           => $table_name,
				'query'          => "
			        CREATE TABLE $table_name (
			            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			            source_product_id BIGINT UNSIGNED DEFAULT NULL,
			            event_type VARCHAR(200) DEFAULT NULL,
			            customer_id VARCHAR(400) DEFAULT NULL,
			            related_event_id BIGINT UNSIGNED DEFAULT NULL,
			            module_id VARCHAR(200) DEFAULT NULL,
			            campaign_id VARCHAR(200) DEFAULT NULL,
			            campaign_cost DECIMAL(12, 4) DEFAULT NULL,
			            order_id BIGINT UNSIGNED DEFAULT NULL,
			            order_subtotal DECIMAL(12, 4) DEFAULT NULL,
			            order_total DECIMAL(12, 4) DEFAULT NULL,
			            meta_data LONGTEXT DEFAULT NULL,
			            meta_data_2 LONGTEXT DEFAULT NULL,
			            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			            PRIMARY KEY (id),
			            INDEX (event_type),
			            INDEX (event_type, timestamp),
			            INDEX (event_type, timestamp, module_id, campaign_id),
			            INDEX (module_id),
			            INDEX (campaign_id),
			            INDEX (order_id),
			            INDEX (timestamp)
			        ) $collate;
			    ",
				'version'        => 1,
				'schema_updater' => '', // Attach a callable here to update the schema when needed, you must increment the version number
			);
		}

		/**
		 * Get the sales notifications table definition.
		 *
		 * @return array
		 */
		private static function get_sales_notifications_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . 'merchant_sales_notifications';
			$collate    = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

			return array(
				'name'           => $table_name,
				'query'          => "
	                CREATE TABLE $table_name ( 
	                    ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		                event_type VARCHAR(255) NOT NULL,  -- cart, order, product_view.
		                product_id BIGINT UNSIGNED NOT NULL,
		                quantity BIGINT UNSIGNED NOT NULL,
		                order_id BIGINT UNSIGNED NULL,  -- To be used for excluding notifications for the some order.
		                customer_id VARCHAR(255) NULL,  -- Adjusted to accommodate string customer IDs
		                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		                PRIMARY KEY (ID),
		                INDEX product_event_type_idx (product_id, event_type),
		                INDEX product_event_type_customer_idx (product_id, event_type, customer_id),
		                INDEX timestamp_idx (timestamp),
		                CONSTRAINT fk_product_id FOREIGN KEY (product_id) REFERENCES `{$wpdb->prefix}posts`(ID) ON DELETE CASCADE 
	                ) $collate;
	            ",
				'version'        => 1,
				'schema_updater' => '', // Attach a callable here to update the schema when needed, you must increment the version number
			);
		}

		/**
		 * Get the sales notifications shown table definition.
		 *
		 * This table records the user events that have been shown to each customer.
		 * The event_id is a foreign key to the merchant_sales_notifications table.
		 * The customer_id is null when the notification is shown to an anonymous visitor.
		 *
		 * @return array
		 */
		private static function get_sales_notifications_shown_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . 'merchant_sales_notifications_shown';
			$collate    = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

			return array(
				'name'           => $table_name,
				'query'          => "
	                CREATE TABLE $table_name ( 
	                    ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		                event_id BIGINT UNSIGNED NOT NULL,
		                customer_id VARCHAR(255) NULL,  -- Adjusted to accommodate string customer IDs
		                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		                PRIMARY KEY (ID),
		                INDEX event_id_customer_idx (event_id, customer_id),
		                CONSTRAINT fk_event_id FOREIGN KEY (event_id) REFERENCES `{$wpdb->prefix}merchant_sales_notifications`(ID) ON DELETE CASCADE 
	                ) $collate;
	            ",
				'version'        => 1,
				'schema_updater' => '', // Attach a callable here to update the schema when needed, you must increment the version number
			);
		}

		/**
		 * Creates or updates all registered database tables.
		 * This method is called on plugin activation and plugin update.
		 * It will create the tables if they don't exist, and update them if needed.
		 */
		public static function create_tables() {
			global $wpdb;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			foreach ( self::get_db_tables() as $table_key => $table ) {
				$show_errors           = $wpdb->hide_errors();
				$table_name            = $table['name'];
				$current_table_version = get_option( 'merchant_' . $table_name . '_version' );
				$exists                = self::maybe_create_table( $table_name, $table['query'] );
				$schema_updater        = $table['schema_updater'];
				if ( $exists && is_callable( $schema_updater ) && $current_table_version < $table['version'] ) {
					call_user_func( $schema_updater, $table_name, $wpdb );
				}
				/**
				 * Fires after a database table is created or updated.
				 *
				 * @param string $table_name  The name of the table that was created or updated.
				 * @param bool   $exists      Whether the table already existed.
				 * @param bool   $show_errors Whether to show database errors.
				 *
				 * @since 1.10.3
				 */
				do_action( 'merchant_db_table_created', $table['name'], $exists, $show_errors );
				update_option( 'merchant_' . $table_name . '_version', $table['version'] );
			}
		}

		/**
		 * Create database table, if it doesn't already exist.
		 *
		 * Based on admin/install-helper.php maybe_create_table function.
		 *
		 * @param string $table_name Database table name.
		 * @param string $create_sql Create database table SQL.
		 *
		 * @return bool False on error, true if already exists or success.
		 */
		private static function maybe_create_table( $table_name, $create_sql ) {
			global $wpdb;

			if ( in_array( $table_name, $wpdb->get_col( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ), 0 ), true ) ) {
				return true;
			}

			$wpdb->query( $create_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			return in_array( $table_name, $wpdb->get_col( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ), 0 ), true );
		}

		/**
		 * Runs after plugin updates to check if the database schema needs updating.
		 *
		 * @param WP_Upgrader $upgrader The upgrader instance.
		 * @param array       $options  Array of data about the upgrade.
		 */
		public static function on_upgrade( $upgrader, $options ) {
			if ( self::is_plugin_update( $options ) ) {
				self::create_tables();
			}
		}

		/**
		 * Checks if the current update is for the Merchant plugin.
		 *
		 * @param array $options Array of data about the upgrade.
		 *
		 * @return bool
		 */
		private static function is_plugin_update( $options ) {
			if (
				isset( $options['action'], $options['type'], $options['plugins'] )
				&& $options['action'] === 'update'
				&& $options['type'] === 'plugin'
				&& in_array( plugin_basename( MERCHANT_FILE ), $options['plugins'], true )
			) {
				return true;
			}

			return false;
		}

		/**
		 * Check if the tables need to be created.
		 */
		public static function maybe_create_tables() {
			if ( ! is_blog_installed() ) {
				return;
			}

			if ( is_admin() ) {
				$installed_version = self::get_db_version();

				if ( MERCHANT_DB_VERSION !== $installed_version ) {
					self::create_tables();
					self::update_db_version();
				}
			}
		}

		/**
		 * Get the current database version.
		 *
		 * @return string
		 */
		private static function get_db_version() {
			return get_option( 'merchant_db_version' );
		}

		/**
		 * Update the database version option.
		 *
		 * @return void
		 */
		private static function update_db_version() {
			update_option( 'merchant_db_version', MERCHANT_DB_VERSION, false );
		}
	}
}

