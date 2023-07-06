<?php

function merchant_inactive_tab_message_cart_count_fragment( $fragments ) {
	
	if ( Merchant_Modules::is_module_active( 'inactive-tab-message' ) ) {
		$fragments['.merchant_cart_count'] = WC()->cart->get_cart_contents_count();
	}
	
	return $fragments;

}
add_filter( 'woocommerce_add_to_cart_fragments', 'merchant_inactive_tab_message_cart_count_fragment' );
