<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Woo Payments plugin compatibility layer
 */
if ( ! class_exists( 'Merchant_Woo_Payments_Plugin' ) ) {
	class Merchant_Woo_Payments_Plugin {

		/**
		 * Constructor.
		 */
		public function __construct() {
            add_action( 'merchant_before_extra_checkouts_init', array( $this, 'checkout_remove_form_field_email_filter' ) );
            add_filter( 'merchant_one_step_checkout_billing_fields', array( $this, 'checkout_add_email_field_class' ) );
        }

        /**
         * Remove Woo Payments email field filter which is causing the email field not to be displayed.
         * Issue: Email field is not being displayed with Merchant extra checkouts.
         * 
         * @return void
         */
        function checkout_remove_form_field_email_filter() {
            remove_filter( 'woocommerce_form_field_email', array( 'WC_Payments', 'filter_woocommerce_form_field_woopay_email' ), 20 );
        }

		/**
         * Add Woo Payments class to the email field.
         * Issue: Email field is not being displayed with Merchant extra checkouts.
         * 
         * @return void
         */
        function checkout_add_email_field_class( $billing_fields ) {
            $billing_fields['billing_email']['class'][] = 'woopay-billing-email';

            return $billing_fields;
        }
	}

	/**
	 * The class object can be accessed with "global $merchant_woo_payments_compatibility", to allow removing actions.
	 * Improving Third-party integrations.
	 */
	$merchant_woo_payments_compatibility = new Merchant_Woo_Payments_Plugin();
}
