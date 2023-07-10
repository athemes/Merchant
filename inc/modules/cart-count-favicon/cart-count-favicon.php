<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_favicon_cart_count_fragment( $fragments ) {
	
	if ( Merchant_Modules::is_module_active( 'cart-count-favicon' ) ) {
		$fragments['.merchant_cart_count'] = WC()->cart->get_cart_contents_count();
	}
	
	return $fragments;

}
add_filter( 'woocommerce_add_to_cart_fragments', 'merchant_favicon_cart_count_fragment' );
