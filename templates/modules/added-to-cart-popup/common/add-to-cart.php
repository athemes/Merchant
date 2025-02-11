<?php
/**
 * Add to Cart template
 *
 * @var $args array template args
 *
 * @since 2.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product; // Required to prevent errors where `woocommerce_loop_add_to_cart_link` is used with a global product object. Example: Botiga theme.

$product = $args['product'] ?? null;
if ( ! is_object( $product ) ) {
	return;
}

$aria_describedby = isset( $args['aria-describedby_text'] ) ? sprintf( 'aria-describedby="woocommerce_loop_add_to_cart_link_describedby_%s"', esc_attr( $product->get_id() ) ) : '';

echo wp_kses(
	/**
	 * `woocommerce_loop_add_to_cart_link`
	 *
	 * @since WC 8.7.0
	 */
	apply_filters(
		'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf(
			'<a href="%s" %s data-quantity="%s" class="%s" %s>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			$aria_describedby,
			esc_attr( $args['quantity'] ?? 1 ),
			esc_attr( $args['class'] ?? 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() )
		),
		$product,
		$args
	),
	merchant_kses_allowed_tags( array( 'forms' ) )
);

