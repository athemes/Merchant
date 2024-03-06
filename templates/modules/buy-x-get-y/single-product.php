<?php
/**
 * Template for buy x get y module content on single product.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = isset( $args['settings'] ) ? $args['settings'] : array();

if ( method_exists( 'Merchant_Pro_Buy_X_Get_Y', 'product_args' ) ) {
	$product = Merchant_Pro_Buy_X_Get_Y::product_args( isset( $args['product'] ) ? wc_get_product( $args['product'] ) : wc_get_product() );
} else {
	return;
}

if ( $product instanceof WC_Product ) {
	$is_purchasable     = $product->is_purchasable();
	$product_id         = $product->get_id();
	$product_image      = $product->get_image( 'woocommerce_gallery_thumbnail' );
	$product_permalink  = $product->get_permalink();
	$product_title      = $product->get_title();
	$product_price_html = $product->get_price_html();
	$product_price      = ! empty( $product->get_price() ) ? $product->get_price() : 0;
} else {
	$is_purchasable     = $product['is_purchasable'] ?? false;
	$product_id         = $product['id'] ?? 0;
	$product_image      = $product['image'] ?? '';
	$product_permalink  = $product['permalink'] ?? '';
	$product_title      = $product['title'] ?? '';
	$product_price_html = $product['price_html'] ?? '';
}
?>
<div class="merchant-bogo">
    <div class="merchant-bogo-offers" data-nonce="<?php
	echo isset( $args['nonce'] ) ? esc_attr( $args['nonce'] ) : '' ?>" data-cart-url="<?php
	echo esc_url( wc_get_cart_url() ); ?>">
		<?php
		foreach ( $args['offers'] as $key => $offer ):
		if ( method_exists( 'Merchant_Pro_Buy_X_Get_Y', 'product_args' ) ) {
		$buy_product = $offer['customer_get_product_ids'] ? Merchant_Pro_Buy_X_Get_Y::product_args( wc_get_product( $offer['customer_get_product_ids'] ) ) : null;
		?>
        <p class="merchant-bogo-title" style="<?php
		echo isset( $offer['title_font_weight'] ) ? esc_attr( 'font-weight: ' . $offer['title_font_weight'] . ';' ) : '';
		echo isset( $offer['title_font_size'] ) ? esc_attr( 'font-size: ' . $offer['title_font_size'] . 'px;' ) : '';
		echo isset( $offer['title_text_color'] ) ? esc_attr( 'color: ' . $offer['title_text_color'] . ';' ) : ''; ?>">
			<?php
			echo isset( $offer['title'] ) ? esc_html( Merchant_Translator::translate( $offer['title'] ) ) : esc_html__( 'Buy One Get One', 'merchant' ) ?>
        </p>
		<?php
		if ( ! $is_purchasable ) {
			continue;
		} ?>
        <div class="merchant-bogo-offer" data-product="<?php
		echo esc_attr( $product_id ) ?>" data-offer="<?php
		echo esc_attr( $key ); ?>">
            <div class="merchant-bogo-product-x">
                <div class="merchant-bogo-product-label merchant-bogo-product-buy-label" style="<?php
				echo isset( $offer['label_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['label_bg_color'] . ';' ) : '';
				echo isset( $offer['label_text_color'] ) ? esc_attr( 'color: ' . $offer['label_text_color'] . ';' ) : ''; ?>">
					<?php
					echo isset( $offer['buy_label'] )
						? esc_html( str_replace( '{quantity}', $offer['min_quantity'], Merchant_Translator::translate( $offer['buy_label'] ) ) )
						: esc_html(
						/* Translators: 1. quantity */
							sprintf( __( 'Buy %s', 'merchant' ), $offer['min_quantity'] )
						); ?>
                </div>
                <div class="merchant-bogo-product">
					<?php
					echo wp_kses_post( $product_image ); ?>
                    <div class="merchant-bogo-product-contents">
                        <p class="woocommerce-loop-product__title">
                            <a href="<?php
							echo esc_url( $product_permalink ); ?>" target="_blank">
								<?php
								echo esc_html( $product_title ); ?>
                            </a>
                        </p>
						<?php
						echo wp_kses( $product_price_html, merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                    </div>
                </div>
                <div class="merchant-bogo-arrow" style="<?php
				echo isset( $offer['arrow_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['arrow_bg_color'] . ';' ) : '';
				echo isset( $offer['arrow_text_color'] ) ? esc_attr( 'color: ' . $offer['arrow_text_color'] . ';' ) : ''; ?>">→
                </div>
            </div>
            <div class="merchant-bogo-product-y" style="<?php
			echo isset( $offer['offer_border_color'] ) ? esc_attr( 'border-color: ' . $offer['offer_border_color'] . ';' ) : '';
			echo isset( $offer['offer_border_radius'] ) ? esc_attr( 'border-radius: ' . $offer['offer_border_radius'] . 'px;' ) : ''; ?>"
            ">
            <form class="merchant-bogo-form" data-product="<?php echo isset( $buy_product['id'] ) ? esc_attr( $buy_product['id'] ) : ''; ?>">
                <div class="merchant-bogo-product-label merchant-bogo-product-get-label" style="<?php
				echo isset( $offer['label_bg_color'] ) ? esc_attr( 'background-color: ' . $offer['label_bg_color'] . ';' ) : '';
				echo isset( $offer['label_text_color'] ) ? esc_attr( 'color: ' . $offer['label_text_color'] . ';' ) : ''; ?>">
					<?php
					$discount = $offer['discount_type'] === 'percentage'
						? $offer['discount'] . '%'
						: wc_price( $offer['discount'] );
					echo isset( $offer['get_label'] )
						? wp_kses( str_replace(
							array(
								'{quantity}',
								'{discount}',
							),
							array(
								$offer['quantity'],
								$discount,
							),
							Merchant_Translator::translate( $offer['get_label'] )
						), merchant_kses_allowed_tags( array( 'bdi' ) ) )
						: wp_kses(
						/* Translators: 1. quantity 2. discount value*/
							sprintf( __( 'Get %1$s with %2$s off', 'merchant' ), $offer['quantity'], $discount ),
							merchant_kses_allowed_tags( array( 'bdi' ) )
						); ?>
                </div>
				<?php
				if ( $buy_product ) {
					?>
                    <div class="merchant-bogo-product">
						<?php
						echo wp_kses_post( $buy_product['image'] ); ?>
                        <div class="merchant-bogo-product-contents">
                            <p class="woocommerce-loop-product__title">
                                <a href="<?php
								echo esc_url( $buy_product['permalink'] ); ?>" target="_blank">
									<?php
									echo esc_html( $buy_product['title'] ); ?>
                                </a>
                            </p>
                            <div class="merchant-bogo-product-price">
								<?php
								echo wp_kses( $buy_product['price_html'], merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?>
                            </div>
                        </div>
                    </div>
					<?php
					if ( isset( $buy_product['attributes'] ) && ! empty( $buy_product['attributes'] ) ) : ?>
                        <div class="merchant-bogo-product-attributes" data-nonce="<?php
						echo esc_attr( wp_create_nonce( 'mrc_get_variation_data_nonce' ) ); ?>">
							<?php
							foreach ( $buy_product['attributes'] as $buy_product_key => $attribute ) : ?>
                                <select class="merchant-bogo-select-attribute" name="<?php
								echo esc_attr( $buy_product_key ) ?>" required>
                                    <option value="">
										<?php
										echo esc_html(
										/* Translators: 1. Attribute label */
											sprintf( __( 'Select %s', 'merchant' ), $attribute['label'] )
										); ?>
                                    </option>
									<?php
									foreach ( $attribute['terms'] as $_term ) : ?>
                                        <option value="<?php
										echo esc_attr( $_term['slug'] ) ?>"><?php
											echo esc_html( $_term['name'] ) ?></option>
									<?php
									endforeach; ?>
                                </select>
							<?php
							endforeach; ?>
                        </div>
					<?php
					endif; ?>
                    <button type="submit" name="merchant-bogo-add-to-cart" value="97" class="button alt wp-element-button merchant-bogo-add-to-cart">
						<?php
						echo isset( $offer['button_text'] ) ? esc_html( Merchant_Translator::translate( $offer['button_text'] ) )
							: esc_html__( 'Add To Cart', 'merchant' ); ?>
                    </button>
                    <div class="merchant-bogo-offer-error"></div>
					<?php
				} ?>
            </form>
        </div>
    </div>
	<?php
	} elseif ( is_admin() ) {
			echo '<p>' . esc_html__( 'Please update Merchant Pro plugin to the latest version to use this feature.', 'merchant' ) . '</p>';
	}
	endforeach; ?>
</div>
</div>
