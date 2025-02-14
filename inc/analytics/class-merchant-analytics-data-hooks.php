<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Merchant_Analytics_Data_Hooks
 *
 * This class is responsible for providing all hooks for analytics.
 */
class Merchant_Analytics_Data_Hooks {
	/**
	 * The single class instance.
	 *
	 * @var Merchant_Analytics_Data_Hooks|null
	 */
	private static $instance = null;

	/**
	 * @var Merchant_Analytics_DB_ORM
	 */
	private $orm;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->orm = new Merchant_Analytics_DB_ORM();
	}

	/**
	 * Get the single class instance.
	 *
	 * @return Merchant_Analytics_Data_Hooks|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load WordPress hooks.
	 */
	public function load_hooks() {
		add_action( 'woocommerce_order_status_refunded', array( $this, 'delete_analytics_records_on_refund' ), 10, 2 );
	}

	/**
	 * Delete analytics records on refund.
	 *
	 * @param int      $order_id Order ID.
	 * @param WC_Order $order    Order object.
	 */
	public function delete_analytics_records_on_refund( $order_id, $order ) {
		if ( $order->get_status() === 'refunded' ) {
			$event = $this->orm
				->where( 'event_type = %s', 'order' )
				->where( 'order_id = %d', $order_id )
				->first();
			$this->orm->reset_query(); // Reset query after getting the data.

			if ( ! empty( $event ) ) {
				$event_id = $event['id'];
				$this->orm->delete( $event_id );
			}
		}
	}
}

Merchant_Analytics_Data_Hooks::instance()->load_hooks();
