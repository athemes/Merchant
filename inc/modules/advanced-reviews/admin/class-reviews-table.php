<?php

/**
 * Product Reviews List Table on Dashboard.
 *
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Reviews_Table' ) ) {

	class Merchant_Reviews_Table {
		public function __construct() {
			//add_filter( 'manage_product_page_product-reviews_columns', array( $this, 'add_photos_columns' ) );

			//add_filter( 'manage_product_page_product-reviews_sortable_columns', array( $this, 'add_photos_sortable_columns' ) );
		}

		/**
		 * Add Photo Column.
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		public function add_photos_columns( $columns ): array {
			$columns['photos'] = esc_html__( 'Photos', 'merchant' );

			return $columns;
		}

		/**
		 * Make Photo column sortable.
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		public function add_photos_sortable_columns( $columns ): array {
			$columns['photos'] = 'photos';

			return $columns;
		}
	}

	new Merchant_Reviews_Table();
}
