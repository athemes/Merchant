<?php

function merchant_agree_to_terms_checkout() {

	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	$text = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'text', 'I have read, understood and agreed with your terms and conditions. <a href="##terms_link##" target="_blank">terms and conditions</a>' );
	$link = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'link', '' );
	
	woocommerce_form_field( 'merchant_agree_to_terms', array(
	'type'     => 'checkbox',
	'label'    => str_replace( '##terms_link##', $link, $text ),
	'required' => true,
	) );

}
add_action( 'woocommerce_checkout_terms_and_conditions', 'merchant_agree_to_terms_checkout', 20 );

function merchant_agree_to_terms_validation( $fields, $errors ){

	if ( ! Merchant_Modules::is_module_active( 'agree-to-terms-checkbox' ) ) {
		return;
	}

	if ( empty( $_POST['merchant_agree_to_terms'] ) ) {
		
		$warning_text = Merchant_Admin_Options::get( 'agree-to-terms-checkbox', 'warning_text', esc_html__( 'You must read and accept the terms and conditions to checkout.', 'merchant' ) );

		$errors->add( 'validation', $warning_text );
	
	}

}
add_action( 'woocommerce_after_checkout_validation', 'merchant_agree_to_terms_validation', 10, 2 );