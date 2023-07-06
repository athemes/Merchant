<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function merchant_add_buy_now_button() {
	if ( ! Merchant_Modules::is_module_active( 'buy-now' ) ) {
		return;
	}

	global $post, $product;

	if ( ! empty( $product ) ) {
		if ( 'yes' == get_post_meta( $post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) > time() ) {
			return;
		}
	}

	$text = Merchant_Admin_Options::get( 'buy-now', 'button-text', esc_html__( 'Buy Now', 'merchant' ) );

	?>

	<button type="submit" name="merchant-buy-now" value="<?php echo esc_attr( $product->get_ID() ); ?>" class="single_add_to_cart_button button alt wp-element-button merchant_buy_now_button"><?php echo esc_html( $text ); ?></button>

	<?php 
}
add_action( 'woocommerce_after_add_to_cart_button', 'merchant_add_buy_now_button' );

function merchant_add_buy_now_button_archive() {
	if ( ! Merchant_Modules::is_module_active( 'buy-now' ) ) {
		return;
	}

	global $post, $product;

	if ( ! $product->is_type( 'simple' ) ) {
	  return;
	}

	if ( ! empty( $product ) ) {
		if ( 'yes' == get_post_meta( $post->ID, '_is_pre_order', true ) && strtotime( get_post_meta( $post->ID, '_pre_order_date', true ) ) > time() ) {
			return;
		}
	}

	$text = Merchant_Admin_Options::get( 'buy-now', 'button-text', esc_html__( 'Buy Now', 'merchant' ) );

	?>
	
	<a href="<?php echo esc_url( add_query_arg( array( 'merchant-buy-now' => $product->get_ID() ), wc_get_checkout_url() ) ); ?>" class="button alt wp-element-button product_type_simple add_to_cart_button merchant_buy_now_button"><?php echo esc_html( $text ); ?></a>

	<?php

}
add_action( 'woocommerce_after_shop_loop_item', 'merchant_add_buy_now_button_archive', 20 );

function merchant_buy_now_listener() {
	if ( ! Merchant_Modules::is_module_active( 'buy-now' ) ) {
		return;
	}

	$product_id = ( isset( $_REQUEST['merchant-buy-now'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['merchant-buy-now'] ) ) : '';
	if ( $product_id ) {

		WC()->cart->empty_cart();
		
		$variation_id = ( isset( $_REQUEST['variation_id'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['variation_id'] ) ) : '';
		if ( $variation_id ) {
			WC()->cart->add_to_cart( $product_id, 1, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, 1 );
		}

		wp_safe_redirect( wc_get_checkout_url() );

		exit;
	}
}
add_action( 'wp', 'merchant_buy_now_listener' );
