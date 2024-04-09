<?php
/**
 * Template for added to cart popup layout 1.
 *
 * @var $args array template args
 *
 * @since 1.9.7
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
		}
		if (
			isset( $args['settings']['show_cart_details'] )
			&& is_array( $args['settings']['show_cart_details'] )
			&& array_intersect(
				array(
					'cart_total',
					'shipping_cost',
					'tax_amount',
				),
				$args['settings']['show_cart_details']
			)
		) { ?>
            <div class="popup-cart-info">
				<?php
				if ( in_array( 'shipping_cost', $args['settings']['show_cart_details'], true ) ) {
					?>
                    <div class="info-item">
                    <span class="info-label"><?php
	                    esc_html_e( 'Shipping Cost', 'merchant' ); ?></span>
                        <span class="info-value">{{cart_shipping_cost}}</span>
                    </div>
					<?php
				}
				if ( in_array( 'tax_amount', $args['settings']['show_cart_details'], true ) ) {
					?>
                    <div class="info-item">
                    <span class="info-label"><?php
	                    esc_html_e( 'Tax amount', 'merchant' ); ?></span>
                        <span class="info-value">{{cart_tax_amount}}</span>
                    </div>
					<?php
				}
				if ( in_array( 'cart_total', $args['settings']['show_cart_details'], true ) ) {
					?>
                    <div class="info-item">
                    <span class="info-label"><?php
	                    esc_html_e( 'Cart Total', 'merchant' ); ?></span>
                        <span class="info-value">{{cart_subtotal}}</span>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		} ?>
    </div>
</div>
