<?php
/**
 * Template for added to cart popup layout 1.
 *
 * @var $args array template args
 *
 * @since 1.9.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="added-product">
	<?php
	if (
		isset( $args['settings']['show_product_thumb'] ) && $args['settings']['show_product_thumb']
	) { ?>
        <div class="popup-product-image">
            <a href="{{product_url}}" target="_blank" title="{{product_name}}">{{product_image_large}}</a>
        </div>
		<?php
	} ?>
    <div class="popup-product-content">
		<?php
		if (
			isset( $args['settings']['show_product_info'] )
			&& is_array( $args['settings']['show_product_info'] )
			&& in_array( 'title_and_price', $args['settings']['show_product_info'], true )
		) { ?>
            <div class="popup-product-name">
                <a href="{{product_url}}" target="_blank">{{product_name}}</a>
            </div>
			<?php
		} ?>
		<?php
		if (
			isset( $args['settings']['show_product_info'] )
			&& is_array( $args['settings']['show_product_info'] )
			&& in_array( 'description', $args['settings']['show_product_info'], true )
		) { ?>
            <p class="popup-product-description">{{product_description}}</p>
			<?php
		} ?>
		<?php
		if (
			isset( $args['settings']['show_product_info'] )
			&& is_array( $args['settings']['show_product_info'] )
			&& in_array( 'title_and_price', $args['settings']['show_product_info'], true )
		) { ?>
            <div class="popup-product-price">{{product_price}}</div>
			<?php
		} ?>
        <div class="popup-product-rating">{{product_rating}}</div>
		<?php
		if ( isset( $args['settings']['show_cart_total'] ) && $args['settings']['show_cart_total'] ) { ?>
            <div class="popup-cart-info">
                <span class="cart-subtotal"><?php
                    esc_html_e( 'Cart subtotal:', 'merchant' ); ?> {{cart_subtotal}}</span>
                <span class="cart-items">({{cart_quantity}})</span>
            </div>
			<?php
		} ?>
    </div>
</div>
