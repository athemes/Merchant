<?php
/**
 * Merchant_Ajax_Callbacks Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Ajax_Callbacks' ) ) {

	class Merchant_Ajax_Callbacks {

		/**
		 * Constructor.
		 * 
		 */
		public function __construct() {

			// Custom Add To Cart Button.
			add_action( 'wp_ajax_merchant_custom_addtocart', array( $this, 'custom_addtocart_button' ) );
			add_action( 'wp_ajax_nopriv_merchant_custom_addtocart', array( $this, 'custom_addtocart_button' ) );
		}

		/**
		 * Custom add to cart button callback.
		 * 
		 */
		public function custom_addtocart_button() {
			check_ajax_referer( 'merchant-custom-addtocart-nonce', 'nonce' );

			if ( ! isset( $_POST['product_id'] ) ) {
				return;
			}
		
			WC()->cart->add_to_cart( absint( $_POST['product_id'] ) );
		
			wp_die();
		}
	}

	new Merchant_Ajax_Callbacks();

}
