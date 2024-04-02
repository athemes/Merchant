<?php
/**
 * Template for added to cart popup.
 *
 * @var $args array template args
 *
 * @since 1.9.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$device_visibility_classes = '';
if ( isset( $args['settings']['show_devices'] ) && is_array( $args['settings']['show_devices'] ) ) {
	$device_visibility_classes = ' hidden-on-mobile hidden-on-desktop';
	foreach ( $args['settings']['show_devices'] as $device ) {
		$device_visibility_classes .= ' show-on-' . $device;
	}
}
?>
<div class="merchant-added-to-cart-popup<?php
echo esc_attr( $device_visibility_classes ) ?>">
    <div class="overlay"></div>
</div>
<div class="merchant-hidden-popup-structure">
    <div class="popup layout-1">
        <div class="popup-header">
            <h3 class="popup-header-title"><?php
				echo ! empty( $args['settings']['popup_message'] )
					? esc_html( Merchant_Translator::translate( $args['settings']['popup_message'] ) )
					: esc_html__( 'Added to Cart', 'merchant' ); ?>
            </h3>
            <div class="popup-close">
                <span class="close-button popup-close-js" title="<?php
                esc_attr_e( 'Close', 'merchant' ) ?>">
                    <svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M10.333 1.43359L1.73047 10.0361M1.73047 1.43359L10.333 10.0361"
                                stroke="black"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                        />
                    </svg>
                </span>
            </div>
        </div>
        <div class="popup-body">
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
                </div>
            </div>
			<?php
			if ( isset( $args['settings']['show_cart_total'] ) && $args['settings']['show_cart_total'] ) { ?>
                <div class="popup-cart-info">
                    <span class="cart-subtotal"><?php
	                    esc_html_e( 'Cart subtotal:', 'merchant' ); ?> {{cart_subtotal}}</span>
                    <span class="cart-items">({{cart_quantity}})</span>
                </div>
				<?php
			} ?>
            <div class="popup-actions">
				<?php
				// View cart button.
				if ( isset( $args['settings']['show_view_cart_button'] ) && $args['settings']['show_view_cart_button'] ) { ?>
                    <a href="<?php
					echo esc_url( wc_get_cart_url() ); ?>" class="merchant-button button-filled view-cart">
						<?php
						echo $args['settings']['view_cart_button_label']
							? esc_html( Merchant_Translator::translate( $args['settings']['view_cart_button_label'] ) )
							: esc_html__
							( 'View Cart', 'merchant' ); ?>
                    </a>
					<?php
				} ?>
				<?php
				// Checkout button.
				if ( isset( $args['settings']['show_checkout_button'] ) && $args['settings']['show_checkout_button'] ) { ?>
                    <a href="<?php
					echo esc_url( wc_get_checkout_url() ); ?>" class="merchant-button checkout"><?php
						esc_html_e( 'Checkout', 'merchant' ); ?></a>
					<?php
				} ?>
				<?php
				// Continue shopping button.
				if ( isset( $args['settings']['show_view_continue_shopping_button'] ) && $args['settings']['show_view_continue_shopping_button'] ) { ?>
                    <a href="#" class="merchant-button continue-shopping popup-close-js">
						<?php
						echo $args['settings']['view_continue_shopping_button_label']
							? esc_html( Merchant_Translator::translate( $args['settings']['view_continue_shopping_button_label'] ) )
							: esc_html__( 'Continue Shopping', 'merchant' ); ?>
                    </a>
					<?php
				} ?>
                <a href="#" class="merchant-button buy-now"><?php
					esc_html_e( 'Buy Now', 'merchant' ); ?></a>
            </div>
			<?php
			if (
				isset(
					$args['settings']['show_suggested_products'],
					$args['settings']['suggested_products_module']
				)
				&& $args['settings']['suggested_products_module'] === 'recently_viewed_products'
				&& $args['settings']['show_suggested_products']
				&& ! empty( $args['recently_viewed_products'] )
			) { ?>
                <div class="recently-viewed-products">
                    <h3 class="section-title"><?php
						esc_html_e( 'Recently Viewed Products', 'merchant' ); ?></h3>
                    <ul class="products columns-4">
						<?php
						foreach ( $args['recently_viewed_products'] as $product ) {
							/**
							 * @var WC_Product $product
							 */
							?>
                            <li class="product">
                                <div class="image-wrapper">
                                    <a href="<?php
									echo esc_url( $product->get_permalink() ); ?>">
										<?php
										echo wp_kses_post( $product->get_image() ); ?>
                                    </a>
                                </div>
                                <div class="product-summary">
                                    <a href="<?php
									echo esc_url( $product->get_permalink() ); ?>">
                                        <h3><?php
											echo esc_html( $product->get_name() ); ?></h3></a>
                                    <div class="product-price"><?php
										echo wp_kses_post( $product->get_price_html() ); ?></div>
									<?php
									if ( $product->get_average_rating() ) { ?>
                                        <div class="product-rating"><?php
											echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?></div>
										<?php
									} ?>
                                </div>
                            </li>
							<?php
						} ?>
                </div>
				<?php
			} ?>
        </div>
    </div>
</div>