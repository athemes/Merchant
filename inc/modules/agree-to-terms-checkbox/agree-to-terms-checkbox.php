<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Agree to terms checkout outpput.
 * 
 */
function merchant_agree_to_terms_checkout() {

	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	$label = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'label', 'I agree with the' );
	$text  = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'text', 'Terms & Conditions' );
	$link  = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'link', '' );
	
	echo '<div class="merchant-agree-to-terms-checkbox">';
		woocommerce_form_field( 'merchant_agree_to_terms', array(
			'type'     => 'checkbox',
			'label'    => sprintf( '%s <a href="%s" target="_blank">%s</a>', esc_html( $label ), esc_url( $link ), esc_html( $text ) ),
			'required' => true,
		) );
	echo '</div>';

}
add_action( 'woocommerce_checkout_terms_and_conditions', 'merchant_agree_to_terms_checkout', 99 );

/**
 * Validation stuff.
 * 
 */
function merchant_agree_to_terms_validation( $fields, $errors ) {
	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	if ( empty( $_REQUEST['merchant_agree_to_terms'] ) ) {
		$warning_text = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'warning_text', esc_html__( 'You must read and accept the terms and conditions to complete checkout.', 'merchant' ) );

		$errors->add( 'validation', $warning_text );
	
	}

}
add_action( 'woocommerce_after_checkout_validation', 'merchant_agree_to_terms_validation', 10, 2 );
