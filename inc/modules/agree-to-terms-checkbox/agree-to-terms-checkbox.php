<?php

function merchant_agree_to_terms_checkout() {

	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	$label = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'label', 'I agree with the' );
	$text  = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'text', 'Terms & Conditions' );
	$link  = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'link', '' );
	
	woocommerce_form_field( 'merchant_agree_to_terms', array(
	'type'     => 'checkbox',
	'label'    => sprintf( '%s <a href="%s" target="_blank">%s</a>', esc_html( $label ), esc_url( $link ), esc_html( $text ) ),
	'required' => true,
	) );

}
add_action( 'woocommerce_checkout_terms_and_conditions', 'merchant_agree_to_terms_checkout', 20 );

function merchant_botiga_agree_to_terms_checkout() {

	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	$shop_checkout_layout = get_theme_mod( 'shop_checkout_layout', 'layout1' );

	if ( ! in_array( $shop_checkout_layout, array( 'layout5', 'layout4' ) ) ) {
		return;
	}

	merchant_agree_to_terms_checkout();

}
add_action( 'woocommerce_review_order_before_submit', 'merchant_botiga_agree_to_terms_checkout', 20 );

function merchant_agree_to_terms_validation( $fields, $errors ) { // phpcs:ignore

	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	if ( empty( $_POST['merchant_agree_to_terms'] ) ) {
		
		$warning_text = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'warning_text', esc_html__( 'You must read and accept the terms and conditions to complete checkout.', 'merchant' ) );

		$errors->add( 'validation', $warning_text );
	
	}

}
add_action( 'woocommerce_after_checkout_validation', 'merchant_agree_to_terms_validation', 10, 2 );
