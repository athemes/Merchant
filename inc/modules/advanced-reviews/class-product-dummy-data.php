<?php

/**
 * Product with dummy data.
 * A class to generate dummy data for product. Useful to render product module preview.
 * 
 * @package Merchant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Merchant_Product_Dummy {

	/**
	 * Get id.
	 * 
	 * @return int $id Product id.
	 */
	public function get_id() {
		return 0;
	}

	/**
	 * Get review count.
	 * 
	 * @return int $review_count Review count.
	 */
	public function get_review_count() {
		return 3;
	}

	/**
	 * Get average rating.
	 * 
	 * @return int $average_rating Average rating.
	 */
	public function get_average_rating() {
		return 3.50;
	}
}
