<?php

function merchant_distraction_free_checkout( $_template_file, $load_once, $args ) {

	if ( Merchant_Modules::is_module_active( 'distraction-free-checkout' ) ) {


	}

}

add_action( 'wp_before_load_template', 'merchant_distraction_free_checkout', 10, 3 );
